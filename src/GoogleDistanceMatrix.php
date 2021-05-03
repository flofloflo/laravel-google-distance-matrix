<?php

namespace Mybit\GoogleDistanceMatrix;

use GuzzleHttp\Client;
use Mybit\GoogleDistanceMatrix\Responses\GoogleDistanceMatrixResponse;

class GoogleDistanceMatrix
{
    public const AVOID_TOLLS = 'tolls';
    public const AVOID_HIGHWAYS = 'highways';
    public const AVOID_FERRIES = 'ferries';
    public const AVOID_INDOOR = 'indoor';

    public const MODE_BICYCLING = 'bicycling';
    public const MODE_DRIVING = 'driving';
    public const MODE_TRANSIT = 'transit';
    public const MODE_WALKING = 'walking';

    public const UNITS_IMPERIAL = 'imperial';
    public const UNITS_METRIC = 'metric';

    private const API_URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';
    private const LANGUAGE = 'en-US';

    private $avoid;
    private $destinations;
    private $language;
    private $mode;
    private $origins;
    private $units;

    public function getApiKey(): string
    {
        return config('google-distance-matrix.api_key');
    }

    public function getLanguage(): string
    {
        if (is_null($this->language)) {
            $this->language = config('google-distance-matrix.defaults.language', self::LANGUAGE);
        }
        return $this->language;
    }

    public function setLanguage($language): GoogleDistanceMatrix
    {
        $this->language = $language;
        return $this;
    }

    public function getUnits(): string
    {
        if (is_null($this->units)) {
            $this->units = config('google-distance-matrix.defaults.units', self::UNITS_METRIC);
        }
        return $this->units;
    }

    public function setUnits($units): GoogleDistanceMatrix
    {
        $this->units = $units;
        return $this;
    }

    public function getOrigins(): array
    {
        return $this->origins;
    }

    public function addOrigin($origin): GoogleDistanceMatrix
    {
        $this->origins[] = $origin;
        return $this;
    }

    public function getDestinations(): array
    {
        return $this->destinations;
    }

    public function addDestination($destination): GoogleDistanceMatrix
    {
        $this->destinations[] = $destination;
        return $this;
    }

    public function getMode(): string
    {
        if (is_null($this->mode)) {
            $this->mode = config('google-distance-matrix.defaults.mode', self::MODE_DRIVING);
        }
        return $this->mode;
    }

    public function setMode($mode): GoogleDistanceMatrix
    {
        $this->mode = $mode;
        return $this;
    }

    public function getAvoid(): string
    {
        if (is_null($this->avoid)) {
            $this->avoid = config('google-distance-matrix.defaults.avoid', self::AVOID_INDOOR);
        }
        return $this->avoid;
    }

    public function setAvoid($avoid): GoogleDistanceMatrix
    {
        $this->avoid = $avoid;
        return $this;
    }

    public function sendRequest(): GoogleDistanceMatrixResponse
    {
        $this->validateRequest();
        $data = [
            'key' => $this->getApiKey(),
            'language' => $this->getLanguage(),
            'origins' => count($this->origins) > 1 ? implode('|', $this->origins) : $this->origins[0],
            'destinations' => count($this->destinations) > 1 ? implode('|', $this->destinations) : $this->destinations[0],
            'mode' => $this->getMode(),
            'avoid' => $this->getAvoid(),
            'units' => $this->getUnits()
        ];
        $parameters = http_build_query($data);
        $url = self::API_URL . '?' . $parameters;

        return $this->request('GET', $url);
    }

    private function validateRequest(): void
    {
        if (empty($this->getOrigins())) {
            throw new Exceptions\OriginException('The origin must be set.');
        }
        if (empty($this->getDestinations())) {
            throw new Exceptions\DestinationException('The destination must be set.');
        }
    }

    private function request($type = 'GET', $url): GoogleDistanceMatrixResponse
    {
        $client = new Client();
        $response = $client->request($type, $url);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Response with status code ' . $response->getStatusCode());
        }
        $responseObject = new GoogleDistanceMatrixResponse(json_decode($response->getBody()->getContents()));
        $this->validateResponse($responseObject);
        return $responseObject;
    }

    private function validateResponse(GoogleDistanceMatrixResponse $response): void
    {
        switch ($response->getStatus()) {
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_OK:
                break;
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_INVALID_REQUEST:
                throw new Exceptions\ResponseException("Invalid request.", 1);
                break;
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_MAX_ELEMENTS_EXCEEDED:
                throw new Exceptions\ResponseException("The product of the origin and destination exceeds the limit per request.", 2);
                break;
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_OVER_QUERY_LIMIT:
                throw new Exceptions\ResponseException("The service has received too many requests from your application in the allowed time range.", 3);
                break;
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_REQUEST_DENIED:
                throw new Exceptions\ResponseException("The service denied the use of the Distance Matrix API service by your application.", 4);
                break;
            case GoogleDistanceMatrixResponse::RESPONSE_STATUS_UNKNOWN_ERROR:
                throw new Exceptions\ResponseException("Unknown error.", 5);
                break;
            default:
                throw new Exceptions\ResponseException(sprintf("Unknown status code: %s", $response->getStatus()), 6);
                break;
        }
    }
}
