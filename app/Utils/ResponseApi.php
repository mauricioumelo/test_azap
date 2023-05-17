<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Throwable;

abstract class ResponseApi
{
    public static function success(array $data = [], string $message = 'Operation performed successfully', $httpCode = Response::HTTP_OK)
    {
        return response()->json(
            [
                'data' => $data,
                'message' => $message,
                'time' => Carbon::now(),
            ],
            $httpCode
        );
    }

    public static function fail(string $code, string $message = 'Could not complete the operation', $httpCode = Response::HTTP_BAD_REQUEST)
    {
        return response()->json(
            [
                'code' => $code,
                'message' => $message,
                'time' => Carbon::now(),
            ],
            $httpCode
        );
    }

    public static function error(Throwable $th)
    {
        $objectResponse = [
            'time' => Carbon::now(),
            'code' => 'internalServerError',
            'message' => 'Could not perform an operation',
        ];

        if (App::environment(['local'])) {
            $objectResponse['description'] = $th->getMessage();
        } else {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($th);
            }
        }

        return response()->json(
            $objectResponse,
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
