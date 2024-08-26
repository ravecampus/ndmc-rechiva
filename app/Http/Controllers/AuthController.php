<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;

class AuthController extends Controller
{
        public function register(Request $request) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6|confirmed',

            ]);

           $user =  User::create([
                    'email'=>$request->email,
                    'password'=>bcrypt($request->password),
            ]);
            return response()->json($user, 200);
        }


        public function login(Request $request){
            
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',

            ]);
            if(!Auth::attempt([
                'email'=>$request->email,
                'password'=>$request->password
            ])):
                // $request->session()->regenerate();
                $errors = ['errors'=>['errs' => ['Incorrect email and password!']]];
                return response()->json($errors,401);
            endif;

            $user = Auth::user();
            return response()->json($user,200);
        }
}
