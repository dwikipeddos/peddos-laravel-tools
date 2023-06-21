<?php

namespace Dwikipeddos\PeddosLaravelTools\Queries\Customs;

use Dwikipeddos\PeddosLaravelTools\Datas\LatLng;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class DistanceSort implements Sort
{
  public function __invoke(Builder $builder, bool $descending, string $property)
  {
    $latlng = LatLng::fromString($property);
    $direction = $descending ? "DESC" : "ASC";
    $builder->raw("SELECT
        *, (
          6371 * acos (
            cos ( radians($latlng->lat) )
            * cos( radians( lat ) )
            * cos( radians( lng ) - radians($latlng->lng) )
            + sin ( radians($latlng->lat) )
            * sin( radians( lat ) )
          )
        ) AS distance
      FROM kendaraan
      HAVING distance <= 10
      ORDER BY distance $direction");
  }
}
