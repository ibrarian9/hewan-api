<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('animalApp')->plainTextToken;
            $success['name'] = $user->name;

            $response = [
              'success' => true,
              'data' => $success,
              'message' => 'Login success.'
            ];

            return response()->json($response);
        } else {

            $response = [
                'success' => false,
                'message' => 'Login failed.',
                'data' => 'Unauthorized'
            ];

            return response()->json($response, 404);
        }
    }

    public function daftar(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => "Validation Error.",
            ];

            if(!empty($errorMessages)){
                $response['data'] = $errorMessages;
            }

            return response()->json($response, 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('foodApp')->plainTextToken;
        $success['name'] =  $user->name;

        $response = [
            'success' => true,
            'data'    => $success,
            'message' => "Register success.",
        ];

        return response()->json($response);
    }
}
