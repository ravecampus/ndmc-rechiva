<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;
use Session;

class AuthController extends Controller
{
        public function register(Request $request) {

            // dd($request);
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6|confirmed',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                // 'middle_initial' => 'required|string',
                'college_department' => 'required',
            ]);

           $user =  User::create([
                    'email'=>$request->email,
                    'first_name'=>$request->first_name,
                    'middle_initial'=>$request->middle_initial,
                    'last_name'=>$request->last_name,
                    'department_id'=>$request->college_department,
                    'email'=>$request->email,
                    'role'=>1,
                    'password'=>bcrypt($request->password),
            ]);

            $note = Notification::create([
                'sender_id' => $user->id,
                'receiver_id'=> 0,
                'caption' =>"Newly Register",
                'role'=>2,
                'document_id' => 0
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
                $request->session()->regenerate();
                $errors = ['errors'=>['errs' => ['Incorrect email and password!']]];
                return response()->json($errors,401);
            endif;

            $user = Auth::user();
            return response()->json($user,200);
        }

        public function signout(){
            try {
                Session::flush();
                 Auth::logout();
                $success = true;
                $message = 'Successfully logged out';
            } catch (\Illuminate\Database\QueryException $ex) {
                $success = false;
                $message = $ex->getMessage();
            }
        
            // response
            $response = [
                'success' => $success,
                'message' => $message,
            ];
            return response()->json($response);
        }
}
