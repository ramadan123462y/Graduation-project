<?php

namespace App\Http\Controllers\Api;

use App\Models\Disease;
use App\Traits\Response;
use App\Models\DiseaseUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\DiseaseResource;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
    use Response;

    public function index()
    {

        $user = auth('users')->user();

        if (!$user) {
            return $this->sendError('Unauthenticated', [], 401);
        }


        $histroy =  DiseaseUser::where('user_id', $user->id)->get();

        if ($histroy->isEmpty()) {

            return  $this->sendResponse([], __('strings.historyEmpty'), 200);
        }


        $diseases =  Disease::with(['homeRemedys', 'images', 'symptoms', 'solutions', 'preventions', 'diseaseUser'])->whereIn('id', $histroy->pluck('disease_id'))->get();


        return $this->sendResponse(DiseaseResource::collection($diseases),  'Disease found successfully', 200);
    }


    public function saveHistory(Request $request)
    {
        $request->validate([
            'disease_id' => 'required|exists:diseases,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth('users')->user();

        if (!$user) {
            return $this->sendError('Unauthenticated', [], 401);
        }

        $diseaseUser = DiseaseUser::where('disease_id', $request->disease_id)
            ->where('user_id', $user->id)
            ->first();

        if ($diseaseUser) {
            $diseaseUser->repetitions += 1;

            if ($request->hasFile('image')) {
                if ($diseaseUser->image) {
                    Storage::disk('public')->delete($diseaseUser->image);
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('History', $imageName, 'public');
                $diseaseUser->image = $path;
            }

            $diseaseUser->save();

            return $this->sendResponse([], __('strings.existsDisease'), 200);
        }

        $newDiseaseUser = DiseaseUser::create([
            'user_id' => $user->id,
            'disease_id' => $request->disease_id,
            'repetitions' => 1,
            'image' => null,
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('History', $imageName, 'public');
            $newDiseaseUser->image = $path;
        }

        $newDiseaseUser->save();

        return $this->sendResponse([], __('strings.saveDisease'), 200);
    }
}
