<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date_start' => Carbon::parse($this->date_start)->format('d/m/Y'),
            'date_end' => Carbon::parse($this->date_end)->format('d/m/Y'),
            'price' => $this->price,
        ];
    }
}
