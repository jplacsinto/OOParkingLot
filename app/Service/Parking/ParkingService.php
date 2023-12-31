<?php

namespace App\Service\Parking;

use App\Service\Parking\ParkingServiceInterface;

use App\Repository\ParkingRepositoryInterface;
use App\Repository\SlotRepositoryInterface;
use App\Repository\EntrypointRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ParkingService implements ParkingServiceInterface {


    public function __construct(
        protected ParkingRepositoryInterface $parkingRepo,
        protected EntrypointRepositoryInterface $entrypointRepository,
        protected SlotRepositoryInterface $parkingSlotRepo
    ){}

    public function park(array $parkingData) : ?Model
    {
        $hasActiveParking = $this->parkingRepo->hasActiveParking($parkingData['registration_id']);
        if($hasActiveParking) {
            $errMessage = 'Vehicle already parked. Unpark first';
            throw ValidationException::withMessages(['registration_id' => $errMessage]);
        }

        $entrypoint = $this->entrypointRepository->findById($parkingData['entrypoint_id']);
        $slot = $entrypoint->slots()->where('slot_id', $parkingData['slot_id'])->first();
        if( !$slot ) {
            $errMessage = 'The slot is not accessible to this entrypoint';
            throw ValidationException::withMessages(['slot_id' => $errMessage]);
        }

        if( $slot->occupant ) {
            $errMessage = 'Slot is occupied';
            throw ValidationException::withMessages(['slot_id' => $errMessage]);
        }

        //validate vehicle size if valid
        if( !in_array($parkingData['vehicle_size'], [0,1,2]) ||
            $parkingData['vehicle_size'] > $slot->size
        ) {
            $errMessage = 'Invalid vehicle size';
            throw ValidationException::withMessages(['vehicle_size' => $errMessage]);
        }

        $parked = $this->parkingRepo->create($parkingData);
        if (!$parked->exists) {
            abort(500, 'Something went wrong! call Spiderman! ');
        }
        return $parked;
    }

    public function unpark(int $id) : ?Model
    {
        $parking = $this->parkingRepo->findById($id);

        //validate parking
        if ($parking->date_unparked !== null) {
            $errMessage = 'Vehicle already unparked.';
            throw abort(500, $errMessage);
        }

        try {
            //get parking config parameters
            $parkingConf = config('parking');
            $initialParkingRate = $parkingConf['rates']['initial'];
            $parkingRate = $parkingConf['rates'][$parking->slot->size];
            $initialHours = $parkingConf['initial-hours'];
            $dailyRate = $parkingConf['rates']['daily'];

            $oneDay = 24;
            $secondsPerDay = 86400;
            $secondsPerHour = 3600;

            //compute parking charging fee
            $parkingDate = new Carbon($parking->date_parked);
            $unparkDate = Carbon::now();
            $totalSeconds = $parkingDate->diffInSeconds($unparkDate);
            $totalHours = $totalSeconds / $secondsPerHour;
            $totalFee = $initialParkingRate;

            //check if parking time duration exceeds the initial hours (3 hours)
            $exceedsInitialHours = ceil($totalHours) > $initialHours;

            //get the last parking time durartion in seconds
            $lastParking = $this->parkingRepo->lastUnParking($parking->registration_id);
            if($lastParking) {
                //get the last unparking date duration in seconds if the returning time is in 1 hour
                $lastParkingSeconds = $parkingDate->diffInSeconds($lastParking->date_unparked);
                $lastParkingTotalHours = $lastParkingSeconds / $secondsPerHour;
                $exceedsInitialHours = $lastParkingTotalHours <= 1;
            }

            //check if parking exceeds 24 hours
            if ($totalHours > $oneDay) {
                //compute the daily rate charge
                $numberOfDays = floor($totalHours / $oneDay); //ex: 2.3 days = 2 days 
                $dailyCharge = $numberOfDays * $dailyRate;

                //compute the left over hours
                $daysToSeconds = $numberOfDays * $secondsPerDay;
                $leftOverSeconds = $totalSeconds - $daysToSeconds;
                $leftOverHours = ceil($leftOverSeconds / $secondsPerHour);
                $leftOverTotalFee = $leftOverHours * $parkingRate;

                //compute total fee
                $totalFee = $dailyCharge + $leftOverTotalFee;
                
            } elseif($exceedsInitialHours) {
                //compute total fee
                $totalFee = ceil($totalHours) * $parkingRate;
            }

            $parking->update([
                'parking_time' => $totalSeconds,
                'date_unparked' => $unparkDate,
                'total_fee' => floatval($totalFee)
            ]);

            return $parking;

        } catch ( \Exception $e) {
            abort(500, $e->getMessage());
        }
    }
}