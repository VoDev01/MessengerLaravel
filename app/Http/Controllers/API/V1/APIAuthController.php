<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\APILoginRequest;
use App\Http\Requests\API\V1\APIRegisterRequest;
use App\Models\ApplicationAPIConsumer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class APIAuthController extends Controller
{
    public function register()
    {
        return view('api.register');
    }

    public function postRegister(APIRegisterRequest $request)
    {
        $validated = $request->validated();
        ApplicationAPIConsumer::create(array_merge(['app_id' => Str::ulid()], $validated));
        return $this->respondWithToken(auth('api')->attempt(array_filter($validated, fn ($k) => $k !== 'app_api_endpoint', ARRAY_FILTER_USE_KEY)));
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(APILoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
