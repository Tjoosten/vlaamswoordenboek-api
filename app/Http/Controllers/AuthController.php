<?php

namespace App\Http\Controllers;

use App\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    private Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get a JWT via given credentials.
     */
    public function store(Request $request): JsonResponse
    {
        $token = $this->auth->authenticateByEmailAndPassword(
            (string) $request->input('email'),
            (string) $request->input('password')
        );

        return response()->json($token, Response::HTTP_OK);
    }

    /**
     * Get the authenticated User.
     */
    public function show(): JsonResponse
    {
        return response()->json($this->auth->getAuthenticatedUser(), Response::HTTP_OK);
    }

    /**
     * Refresh a token.
     */
    public function update(): JsonResponse
    {
        return response()->json($this->auth->refreshAuthenticationToken(), Response::HTTP_OK);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function destroy(): JsonResponse
    {
        $this->auth->invalidateAuthenticationToken();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
