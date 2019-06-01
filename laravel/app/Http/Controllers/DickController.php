<?php

namespace App\Http\Controllers;

use App\Airtable;

class DickController extends Controller
{
    private $airtable;

    public function __construct()
    {
        $this->airtable = new Airtable();
    }

    public function getIndex() {
        return view('index');
    }

    public function getStatus() {
        $result = $this->airtable->getCheckIns();

        return response()->json($result[0]);
    }
}
