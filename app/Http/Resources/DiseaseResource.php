<?php

namespace App\Http\Resources;

use App\Models\DiseaseUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiseaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       $history = DiseaseUser::where('disease_id', $this->id)->where('user_id', auth('users')->user()->id)->first();
        return
            [

                'id' => $this->id,
                'name' => $this->name,
                'origin_name' => $this->origin_name,
                'scientific_name' => $this->scientific_name,
                'also_know_as' => ' “' . implode('”, “', is_array(json_decode($this->also_know_as, true)) ? json_decode($this->also_know_as, true) : []) . '”.',
                'type_disease' => $this->type_disease,
                'description' => $this->description,

                'symptoms' => $this->symptoms->map(function ($symptom) {
                    return [
                        'title' => $symptom->title,
                        'description' => $symptom->description,
                    ];
                }),

                'solutions' => $this->solutions->map(function ($solution) {
                    return [
                        'title' => $solution->title,
                        'description' => $solution->description,
                    ];
                }),


                'preventions' => $this->preventions->map(function ($prevention) {

                    return [

                        'title' => $prevention->title,
                        'description' => $prevention->description,
                    ];
                }),

                'homeRemedys' => $this->homeRemedys->map(function ($homeRemedy) {
                    return [
                        'title' => $homeRemedy->title,
                        'description' => $homeRemedy->description,
                    ];
                }),

                'disease_images' => $this->images->map(function ($image) {
                    return [
                        'image' => $image->image,
                    ];
                }),

                'repetitions' => $history ->repetitions ?? 0,


                'image_history' =>         $history 
                    ? url('storage/' .    $history ->image )
                    : "", 
                    
                // 'image_history' => $this->diseaseUser()->first()->image ?  $this->diseaseUser()->first()->image  : "",

            ];
    }
}
