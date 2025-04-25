<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowPlantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        [
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description,
                // 'image' => $this->image,
                'image' =>  $this->image ? url('storage/' . $this->image) : "",
                'sunlight_anmount' => $this->sunlight_anmount,
                'soil' => $this->soil,
                'watering_anmount' => $this->watering_anmount,
                'temperature_range' => $this->temperature_range,
                'nutrients' => $this->nutrients,
                'section' => $this->section->name
        ];
    }
}
