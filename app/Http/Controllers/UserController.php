<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ResponseAPI;

    public function registerUser(Request $request){
        try {
            $rules = array(
                'name'      => 'required',
                'username'  => 'required|unique:users,username',
                'password'  => 'required|confirmed|min:8',
            );

            $validator = Validator::make( $request->all(), $rules );
            if ( $validator->fails() ){
                return response()->json([
                    'status' => 500,
                    'message' => $validator->errors()
                ],500 );
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // return response()->json([
            //     'data' => New UserResource($user),
            //     'status' => 200,
            //     'message' => 'Success register user',
            // ], 200);

        } catch (\Exception $err) {
            return response()->json([
                'status' => 500,
                'message' => 'EXCEPTION : '. $err->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            if( !Auth::attempt($request->only('username', 'password')) ){
                // return response()->json(['message' => 'username atau password yang anda masukkan salah'], 500);
                return $this->error('username atau password yang anda masukkan salah', 500);
            }

            $user = User::where('username', $request['username'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            $data =
            [
                'access_token' => $token,
                'token_type' => 'Bearer',
            ];

            return $this->success('success login '.$user->name, $data, 200);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
