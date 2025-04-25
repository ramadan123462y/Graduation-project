<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_name' => $this->user_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            // 'image'=>$this->profile_photo_url,  //'storage/' . $this->image ?? asset('assets/images/user.png'),
            'image' => $this->image  ? url('storage/' . $this->image) : url('assets/images/user.jpg'),
            'points' => $this->points
        ];
    }
}
