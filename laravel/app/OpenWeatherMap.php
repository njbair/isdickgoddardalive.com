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
            $data = $this->apiQuery($endpoint, [
                'id' => $this->apiCityId,
                'units' => 'imperial',
            ]);

            if ($data) {
                $result = (object) array(
                    'icon'        => self::parseIcon($data->weather[0]->icon),
                    'description' => $data->weather[0]->description,
                    'temp'        => round($data->main->temp),
                    'location'    => $data->name,
                );
                $result->asString = "{$result->temp}°, {$result->description} in {$result->location}";
                return $result;
            } else {
                return (object) array(
                    'icon'        => 'na',
                    'description' => false,
                    'temp'        => false,
                    'location'    => 'unknown',
                    'asString'    => 'Unable to retrieve weather data at this time.',
                );
            }
        });

        return $this->weather;
    }

    private function apiQuery($endpoint, $query) {
        $query['appid'] = $this->apiKey;
        try {
            $response = $this->client->request('GET', $endpoint, [
                'query' => $query
            ]);

            if ( $response->getStatusCode() !== 200) {
                return false;
            }

            $data = json_decode($response->getBody()->getContents());

            return $data;
        } catch(GuzzleException $exception) {
            return false;
        }
    }

    private static function parseField($data, $fieldName) {
        if ( ! isset ($data->fields->$fieldName)) {
            return false;
        }
        return $data->fields->$fieldName;
    }

    private static function parseIcon($data) {
        $icons = array(
            '01d' => 'sunny',
            '01n' => 'clear-night',
            '02d' => 'cloudy-day',
            '02n' => 'cloudy-night',
            '03d' => 'cloud',
            '03n' => 'cloud',
            '04d' => 'cloudy',
            '04n' => 'cloudy',
            '09d' => 'rain',
            '09n' => 'rain',
            '10d' => 'rain-day',
            '10n' => 'rain-night',
            '11d' => 'thunderstorm',
            '11n' => 'thunderstorm',
            '13d' => 'snow',
            '13n' => 'snow',
            '50d' => 'fog',
            '50n' => 'fog',
        );

        return $icons[$data];
    }
}
