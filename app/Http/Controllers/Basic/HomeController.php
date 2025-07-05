<?php

namespace App\Http\Controllers\Basic;
use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\Client;
use App\Models\OurService;
use App\Models\OurValue;
use App\Models\StrategicAdvantage;
use App\Models\WhyUs;

class HomeController extends Controller
{
    public function index()
    {
        $clients=Client::all();
        $ourValues=OurValue::all();
        $whyUs=WhyUs::all();
        $ourServices=OurService::all();
        $aboutUs=AboutUs::first();
        $strategyAdvantages=StrategicAdvantage::all();
        return view('home',compact('clients','ourValues','ourServices','aboutUs','strategyAdvantages','whyUs'));
    }
}
