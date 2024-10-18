<?php

namespace App\Applications\User\Controllers;

use App\Applications\User\DTO\UserDTO;
use App\Applications\User\Model\User;
use App\Applications\User\Requests\LoginRequest;
use App\Applications\User\Requests\NewUserRequest;
use App\Applications\User\Requests\RegisterRequest;
use App\Applications\User\Services\UserServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @property UserServiceInterface $userService
 */
class LoginController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $data = Auth::user();
            $token = $data->createToken('api-token')->plainTextToken;

            return response()
                ->json(compact('data'), 200)
                ->header('authorization', $token)
                ->header('Access-Control-Expose-Headers', 'Authorization');
        }

        return response()->json(['error' => 'Invalid Credentials'], 401);
    }

    public function logout(Request $request)
    {
        // Revoke the user's current token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Token revoked']);
    }

    public function refresh(Request $request)
    {
        // Revoke the user's current token
        $request->user()->currentAccessToken()->delete();
        $data = $request->user();

        // Issue new token
        $token = $request->user()->createToken('api-token')->plainTextToken;

        return response()
            ->json(compact('data'), 200)
            ->header('authorization', $token)
            ->header('Access-Control-Expose-Headers', 'Authorization');;
    }

    public function user(Request $request): JsonResponse
    {
        // Fetch the authenticated user
        $user = User::find($request->user()->id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Convert the user model to a DTO (or format as needed)
        $userDTO = UserDTO::fromModel($user);

        // Return the user details as a JSON response
        return response()->json(['user' => $userDTO], 200);
    }


    /**
     * Register a new user.
     */
    public function register(NewUserRequest $request): JsonResponse
    {
        $userDTO = UserDTO::fromRequestForCreate($request);
        $newUserDTO = $this->userService->create($userDTO, $request->input('password'));
        $user = User::where('id',$newUserDTO->id)->get()->first;
        $token = $user->createToken('api-token')->plainTextToken;

        return response()
            ->json(['user' => $newUserDTO], 201)
            ->header('Authorization', $token)
            ->header('Access-Control-Expose-Headers', 'Authorization');
    }
}
