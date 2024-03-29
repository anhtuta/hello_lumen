<?php

namespace App\Http\Controllers;

use App\Http\Common\Result;
use Illuminate\Http\Request;
use  App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'login', 'hashPw']]);
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        // Note: request param must include password_confirmation field:
        // https://laravel.com/docs/8.x/validation#rule-confirmed
        $this->validate($request, [
            'name' => 'required|string',
            'username' => 'required|unique:user',
            'password' => 'required|confirmed',
        ]);

        $result = new Result();
        $user = new User;
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $plainPassword = $request->input('password');
        $user->password = app('hash')->make($plainPassword);

        $user->save();

        $result->res("User has been created!", $user);
        return response()->json($result, 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $result = new Result();
        $credentials = $request->only(['username', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            $result->res("Username or password is incorrect!");
            return response()->json($result, 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $result = new Result();
        if (Auth::check()) {
            $result->res("SUCCESS!", Auth::guard()->user());
            return response()->json($result);
        } else {
            $result->res("Invalid token");
            return response()->json($result, 401);
        }
    }

    public function hashPw(Request $request)
    {
        $result = new Result();
        if (!isset($request->pw)) {
            $result->failRes("'pw' param cannot be null or empty!");
            return response()->json($result, 400);
        }
        return app('hash')->make($request->pw);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function refresh()
    // {
    //     return $this->respondWithToken(Auth::guard()->refresh());
    // }

    protected function respondWithToken($token)
    {
        $result = new Result();
        $result->res("SUCCESS!", [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
        return response()->json($result, 200);
    }
}
