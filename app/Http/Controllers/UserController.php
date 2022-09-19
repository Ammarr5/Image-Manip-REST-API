<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create(RegisterUserRequest $request) {
        $rowData = [
            "name" => $request["name"],
            "email" => $request["email"],
            "password" => bcrypt($request["password"])
        ];

        $user = User::create($rowData);
        auth()->login($user);
        $data = new UserResource($user);
        $data["token"] = $user->createToken("sessionToken")->plainTextToken;
        return $data;
    }

    /**
     * // TODO
     */
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();
        // $request->session()->logout();

        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
    }

    public function login(LoginUserRequest $request) {
        $attemptData = [
            "email" => $request["email"],
            "password" => $request["password"]
        ];

        if(!auth()->attempt($attemptData)) {
            return response("Invalid credentials", 401);
        }
        // $request->session()->regenerate();
        return auth()->user()->createToken("sessionToken")->plainTextToken;
    }
}
