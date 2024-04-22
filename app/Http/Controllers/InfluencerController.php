<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfluencerController extends Controller
{

    public function index()
    {

        return view('influencers.index');
    }

    public function perfil()
    {

        return view('influencers.perfil');
    }

    public function referidos()
    {

        return view('influencers.referidos');
    }

    public function retiros()
    {

        return view('influencers.retiros');
    }
}
