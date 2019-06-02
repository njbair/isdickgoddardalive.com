<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Airtable {
    private static $cacheKeyPrefix = 'airtable-';

    private $client;
    private $apiEndpoint;
    private $apiKey;
    private $apiCacheTime;

    private $records;

    public function __construct() {
        $this->client = new Client();
        $this->apiEndpoint  = getenv('AIRTABLE_API_ENDPOINT');
        $this->apiKey       = getenv('AIRTABLE_API_KEY');
        $this->apiCacheTime = getenv('AIRTABLE_API_CACHE_TIME');

    }

    public function getCheckIns($flush = false) {
        $cacheKey = self::$cacheKeyPrefix . 'check-ins';
        if ($flush) Cache::forget($cacheKey);
        $this->records = Cache::remember($cacheKey, $this->apiCacheTime, function(){
            $endpoint = $this->apiEndpoint . '/Check-Ins';
            $result = [];

            $checkIns = $this->apiQuery($endpoint, [
                'view' => 'Most Recent First',
                'pageSize' => 1
            ]);

            foreach ($checkIns as $c) {
                $out = (object) array(
                    'checkInDate' => self::parseField($c, 'Date'),
                    'alive'       => self::parseField($c, 'Is Dick Goddard Alive?'),
                    'notes'       => self::parseField($c, 'Notes'),
                );

                array_push($result, $out);
            }

            return $result;
        });

        return $this->records;
    }

    private function apiQuery($endpoint, $query) {
        $result = [];
        try {
            $response = $this->client->request('GET', $endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey
                ],
                'query' => $query
            ]);

            if ( $response->getStatusCode() !== 200) {
                return false;
            }

            $data = json_decode($response->getBody()->getContents());

            foreach ($data->records as $record) {
                array_push($result, $record);
            }

            return $result;
        } catch(GuzzleException $exception) {
            return [];
        }
    }

    private static function parseField($data, $fieldName) {
        if ( ! isset ($data->fields->$fieldName)) {
            return false;
        }
        return $data->fields->$fieldName;
    }
}