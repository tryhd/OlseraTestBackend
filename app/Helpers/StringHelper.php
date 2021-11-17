<?php

namespace App\Helpers;

use Error;
use Illuminate\Database\QueryException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StringHelper
{
    public function setErrorResponseApi($exception, $requestResponseHttpCode = null, $requestStringHttpCode = null, $requestAction = null)
    {
        $action = '';

        if ($exception instanceOf FatalError || $exception instanceof Error) {
            $httpCode = 500;
            $message =  ('production' == config('app.env')) ? 'a fatal error occurred' : $exception->getMessage();
        } else {
            $httpCode = (false == method_exists($exception, 'getStatusCode')) ? 500 : $exception->getStatusCode();
            $message = $exception->getMessage();
        }

        if (true == $exception instanceOf ValidationException) {
            $message = app('string.helper')->getErrorLaravelFirstKey($exception->errors());
            $httpCode = 400;
        }

        if (true == $exception instanceOf NotFoundHttpException) {
        	$message = 'Route not found';
        	$httpCode = 404;
        }

        if (true == $exception instanceOf QueryException) {
        	$message = ('production' == config('app.env')) ? 'Query failed to execute' : $message;
        	$httpCode = 500;
        }

        if (true == $exception instanceOf ClientException) {
        	$message = ('production' == config('app.env')) ? 'Failed fetching request to client' : $message;
        	$httpCode = 500;
        }

        if (true == $exception instanceOf AuthenticationException) {
            $message = 'Invalid token';
            $action = 'redirect login';
        	$httpCode = 400;
        }

        return response()->api([
            'message' => $message,
            'code' => (is_null($requestStringHttpCode)) ? $httpCode : $requestStringHttpCode,
            'status' => false,
            'action' => (is_null($requestAction)) ? $action : $requestAction,
        ], [],
        (is_null($requestResponseHttpCode)) ? $httpCode : $requestResponseHttpCode);
    }

    /**
     * get single message error, from result validate form request laravel
     *
     * @param  array $arrError list error message
     * @return string if list error message is valid, return single message error
     */
    public function getErrorLaravelFirstKey($arrError)
    {
        if (is_array($arrError)) {
            foreach ($arrError as $key => $value) {
                return $value[0];
            }
        }
        return '';
    }
}
