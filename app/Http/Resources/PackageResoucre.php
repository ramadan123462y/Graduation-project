<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResoucre extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'conis' => $this->coins,
            'badge' => $this->getBadge(),
        ];
    }
    private function getBadge()
    {
        return match ($this->id) {
            6 => __('strings.best'),
            8 =>  __('strings.popular'),
            default => "",
        };
    }
}
