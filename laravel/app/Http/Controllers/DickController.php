<?php

namespace App\Http\Controllers;

use App\Airtable;
use App\OpenWeatherMap;

class DickController extends Controller
{
    private $airtable;
    private $openweathermap;

    public function __construct()
    {
        $this->airtable = new Airtable();
        $this->openweathermap = new OpenWeatherMap();
    }

    public function getIndex() {
        return view('index');
    }

    public function getStatus() {
        $result = $this->airtable->getCheckIns();
        return response()->json($result[0]);
    }

    public function getWeather() {
        $result = $this->openweathermap->getCurrentWeather();
        return response()->json($result);
    }
}
