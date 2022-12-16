<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Carbon\CarbonPeriod;

class MainController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * @param $dateStart
     * @param $dateEnd
     * @return array
     */
    public function getRange($dateStart, $dateEnd): array {
        $period = CarbonPeriod::create($dateStart, $dateEnd);

        return $period->toArray();
    }

    /**
     * @param $range
     * @return bool
     */
    public function checkRange($range): bool {
        $check = true;
        foreach ($range as $date){
            $vacantDay = Place::where('date', $date)->first();

            if($vacantDay and $vacantDay->number >= config('places.number')){
                $check = false;
                break;
            }
        }
        return $check;
    }
}
