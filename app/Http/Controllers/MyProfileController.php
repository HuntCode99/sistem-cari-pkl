<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyProfileController extends Controller
{
    public function show(){
        return view("public.myprofile");
    }
    public function edit(){
        return view("public.editProfile");
    }
}
