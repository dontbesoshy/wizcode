<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\MainController as MainController;
use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Price;
use App\Models\Reservation;
use App\Models\Place;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReservationController extends MainController {
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index() {
        return $this->sendResponse(ReservationResource::collection(Reservation::all()), 'showing all reservations');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReservationRequest $request
     * @return JsonResponse
     */
    public function store(ReservationRequest $request): JsonResponse {
        $range = $this->getRange($request['date_start'], $request['date_end']);
        $reservation_price = 0;
        if ($this->checkRange($range)){
            DB::transaction(function () use ($range, &$reservation_price){
                foreach ($range as $date){
                    $place = Place::where('date', $date)->first();

                    if(!$place){
                        $random_price = rand(100, 3999);
                        $reservation_price += $random_price;
                        $place = Place::create(['date' => $date, 'number' => 1]) ;
                    }else{
                        $place->number++;
                        $reservation_price += $place->price->price;
                    }

                    $place->save();
                    if (isset($random_price)){
                        Price::create(['place_id' => $place->id, 'price' => $random_price]);
                    }
                }
            });

            $reservation = Reservation::create(['date_start' => $request['date_start'], 'date_end' => $request['date_end'], 'price' => $reservation_price]);
            return $this->sendResponse(new ReservationResource($reservation), 'Reservation store successfully!');
        }else{
            return $this->sendError('error', ['error' => 'no vacancies']);
        }
    }

    public function destroy(Reservation $reservation){
        $range = $this->getRange($reservation->date_start, $reservation->date_end);
        foreach ($range as $date){
            $place = Place::where('date', $date)->first();
            $place->number--;
            $place->save();
        }
        $reservation->delete();

        return $this->sendResponse([],'Reservation deleted successfully!');
    }
}
