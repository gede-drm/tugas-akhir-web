<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecurityOfficerController extends Controller
{
    public function index(){
        return view('security.index');
    }
    public function add(){
        return view('security.add');
    }
    public function store(){
        //
    }
}
