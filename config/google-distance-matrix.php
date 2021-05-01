<?php

use Mybit\GoogleDistanceMatrix\GoogleDistanceMatrix;

return [
    /**
     * The API key which should be used for calls to the Google Maps Distance Matrix API
     */
    'api_key' => env('GOOGLE_API_KEY', null),

    /**
     * Default values
     */
    'defaults' => [
        // Unit system used for distances
        'units' => GoogleDistanceMatrix::UNITS_METRIC,
        // Driving mode used for distance calculation
        'mode' => GoogleDistanceMatrix::MODE_DRIVING,
    ]
];
