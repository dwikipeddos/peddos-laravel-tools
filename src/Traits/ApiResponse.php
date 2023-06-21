<?php

namespace Dwikipeddos\PeddosLaravelTools\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{

    public function responseOk(mixed $data = null): Response
    {
        return response($data, Response::HTTP_OK);
    }

    public function responseCreated(mixed $data = null): Response
    {
        return response($data, Response::HTTP_CREATED);
    }

    public function responseForbidden(mixed $data = null): Response
    {
        return response($data, Response::HTTP_FORBIDDEN);
    }

    public function responseUnauthorized(mixed $data = null): Response
    {
        return response($data, Response::HTTP_UNAUTHORIZED);
    }

    public function responseUnprocessable(mixed $data = null)
    {
        return response($data, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function responseError(mixed $data = null): Response
    {
        return response($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
