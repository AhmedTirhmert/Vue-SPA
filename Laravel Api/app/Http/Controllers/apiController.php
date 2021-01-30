<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User as Users;

class apiController extends Controller
{
    //
    public function GetUsers(Request $request)
    {
        $ty = Users::all();
        return $ty;
    }
}
