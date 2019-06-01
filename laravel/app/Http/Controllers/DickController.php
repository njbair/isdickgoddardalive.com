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

    public function getAllData() {
        $checkIns = $this->airtable->getCheckIns();
        $currentWeather = $this->openweathermap->getCurrentWeather();

        if (empty($checkIns)) {
            $checkIns[] = (object) array(
                'alive' => "?",
                'notes' => "Unable to retrieve Dick's vital status at this time.",
            );
        }

        $result = (object) array(
            'status'  => $checkIns[0],
            'weather' => $currentWeather,
        );
        return response()->json($result);
    }
}
