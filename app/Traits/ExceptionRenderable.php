<?php

namespace App\Traits;

use Error;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Throwable;

trait ExceptionRenderable
{
    /**
     * Response the exception in JSON.
     */
    private function renderJson(Request $request, Throwable $exception): JsonResponse
    {
        $error = fractal($exception, $this->transformer, $this->serializer)->toArray();

        return response()
            ->json($error)
            ->setStatusCode($this->getStatusCodeFromError($error))
            ->withHeaders($this->getHeadersFromException($exception));
    }

    /**
     * Check if the exception is renderable with JSON
     */
    private function checkIfJsonRenderable(Throwable $exception): bool
    {
        return !(config('app.debug') && $exception instanceof Error);
    }

    /**
     * Get status code from the error body.
     */
    private function getStatusCodeFromError(array $error): int
    {
        return Arr::get($error, 'data.status')
            ?? Arr::get($error, 'error.status')
            ?? Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * Get headers from the exception.
     */
    private function getHeadersFromException(Throwable $exception): array
    {
        if (! method_exists($exception, 'getHeaders')) {
            return [];
        }

        return call_user_func([$exception, 'getHeaders']);
    }
}
