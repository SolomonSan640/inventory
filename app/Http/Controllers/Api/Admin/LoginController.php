<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        $validator = $this->validateLoginData($request);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // $this->setLocale(strtolower('en'));
        App::setLocale('en');
        $credentials = $request->only('email', 'password');
        $users = User::where('email', $credentials['email'])->first();

        if ($users && Hash::check($credentials['password'], $users->password)) {
            $token = $users->createToken('Token Name')->plainTextToken;
            return response()->json(['status' => 200, 'token' => $token, 'admin' => $users, 'message' => __('success.LoginSuccess')]);
        } else {
            return response()->json(['message' => __('error.loginFailed')], 401);
        }
    }

    public function logout(Request $request)
    {
        // $this->setLocale(strtolower($request->country));
        $request->user()->tokens()->delete();
        return response()->json(['message' => __('success.logout')]);
    }

    protected function validateLoginData(Request $request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase()
                    ->symbols(),
            ],
        ]);
    }

    protected function checkPasswordValidation($request)
    {
        $rules = [
            'old_password' => 'required|min:6',
            'new_password' => [
                'required',
                Password::min(6)
                    ->letters()
                    ->numbers()
                    ->mixedCase()
                    ->symbols(),
            ],
            'new_password_confirm' => 'required|same:new_password',
        ];
        // $messages = [
        //     'oldPassword.required' => "Old Password must be filled.",
        //     'newPassword.required' => "New Password must be Filled.",
        //     'newPassword.min' => 'The password must be at least :min characters long and must contain at least one letter, one number, one capitalized letter, and one special character.',
        //     'newPasswordConfirm.required' => "New Password Confirmation must be Filled.",
        // ];
        Validator::make($request->all(), $rules)->validate();
    }

    private function setLocale($country)
    {
        $supportedLocales = ['en', 'mm'];
        if (in_array($country, $supportedLocales)) {
            app()->setLocale($country);
        } else {
            app()->setLocale('en');
        }
    }
}
