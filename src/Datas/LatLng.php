<?php

namespace Dwikipeddos\PeddosLaravelTools\Datas;

use Spatie\LaravelData\Data;

class LatLng extends Data
{
    public float $lat;
    public float $lng;

    public static function fromString(string $latLng): LatLng
    {
        $res = explode(',', $latLng);
        return self::from([
            'lat' => $res[0],
            'lng' => $res[1],
        ]);
    }

    public function toString(): string
    {
        return $this->lat . "," . $this->lng;
    }
}
