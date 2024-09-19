<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminContact;

class AdminContactController extends Controller
{
    public function index(){
        $data = AdminContact::find(1);

        return response()->json($data, 200);
    }

    public function store(Request $request){
        $data = AdminContact::find(1);
        $data->contact_number = $request->contact_number;
        $data->information = $request->information;
        $data->save();
        return response()->json($data, 200);
    }
}
