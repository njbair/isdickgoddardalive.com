<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class OpenWeatherMap {
    private static $cacheKeyPrefix = 'open-weather-map-';

    private $client;
    private $apiEndpoint;
    private $apiKey;
    private $apiCacheTime;

    private $weather;

    public function __construct() {
        $this->client = new Client();
        $this->apiEndpoint  = getenv('OPENWEATHERMAP_API_ENDPOINT');
        $this->apiKey       = getenv('OPENWEATHERMAP_API_KEY');
        $this->apiCacheTime = getenv('OPENWEATHERMAP_API_CACHE_TIME');
        $this->apiCityId    = getenv('OPENWEATHERMAP_API_CITY_ID');

    }

    public function getCurrentWeather($flush = false) {
        $cacheKey = self::$cacheKeyPrefix . 'current-weather';
        if ($flush) Cache::forget($cacheKey);
        $this->weather = Cache::remember($cacheKey, $this->apiCacheTime, function(){
            $endpoint = $this->apiEndpoint . '/weather';
            $result = $this->apiQuery($endpoint, [
                'id' => $this->apiCityId,
                'units' => 'imperial',
            ]);

            return $result;
        });

        return $this->weather;
    }

    private function apiQuery($endpoint, $query) {
        $query['appid'] = $this->apiKey;
        $response = $this->client->request('GET', $endpoint, [
            'query' => $query
        ]);

        if ( $response->getStatusCode() !== 200) {
            return false;
        }

        $data = json_decode($response->getBody()->getContents());

        return $data;
    }

    private static function parseField($data, $fieldName) {
        if ( ! isset ($data->fields->$fieldName)) {
            return false;
        }
        return $data->fields->$fieldName;
    }
}
