<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {

        $usersCount = User::count();

        $data = [

            'usersCount' => $usersCount
        ];


        return view('Dashboard.index', $data);
    }
}
