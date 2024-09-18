<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = User::query();

        if($request->search != ''){
            $data = $data->where('first_name','like','%'.$request->search.'%')
            ->orWhere('last_name','like','%'.$request->search.'%');
        }
        if($request->filter != ''){
           $data = $data->where('role', $request->filter);
        }
       $data = $data->with('department')->latest()->paginate(5);
        
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->role == 2){
            $request->validate([
                'first_name'  => 'required|string',
                'middle_initial'  => 'required|string',
                'last_name'  => 'required|string',
                'role'  => 'required',
                'email' => 'required|email',
                'password' => 'required|string|min:6|confirmed',
            ]);
    
        }else{
            $request->validate([
                'first_name'  => 'required|string',
                'middle_initial'  => 'required|string',
                'last_name'  => 'required|string',
                'role'  => 'required',
                'department'  => 'required',
                'email' => 'required|email',
                'password' => 'required|string|min:6|confirmed',
            ]);
        }
       

        $data = User::create([
            'first_name'=> $request->first_name,
            'middle_initial'=> $request->middle_initial,
            'last_name'=> $request->last_name,
            'role'=> $request->role,
            'activate'=> $request->role == 2 ? 1 : 0,
            'department_id'=> $request->department,
            'email'=> $request->email,
            'password'=> bcrypt($request->password),
            'activate'=>0,
        ]);

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $data = User::find($id);
        $data->activate = $data->activate == 0 ? 1 : 0;
        $data->save();
        $note = Notification::create([
            'sender_id' => 0,
            'receiver_id'=> $data->id,
            'caption' =>$data->activate == 0 ? "Sorry, You are Deactivated!" :"Congratulations, You are Activated",
            'role'=>2,
            'document_id' =>0 
        ]);

        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'first_name'  => 'required|string',
            'middle_initial'  => 'required|string',
            'last_name'  => 'required|string',
            'email' => 'required|email',
        ]);
        $data = User::find($id);
        $data->first_name = $request->first_name;
        $data->middle_initial = $request->middle_initial;
        $data->last_name = $request->last_name;
        $data->email = $request->email;
        $data->save();

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function changePassword(Request $request, string $id){
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $data = User::find($id);
        $data->password = bcrypt($request->password);
        $data->save();
        Session::flush();
        return response()->json($data, 200);

    }

    public function getFaculty($id){
        $data = User::find($id);
        return response()->json($data, 200);
    }
}
