<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Department::query();
        if($request->search != ''){
            $data = Department::where('description','like','%'.$request->search.'%')
            ->orWhere('abbreviation','like','%'.$request->search.'%');
        }
        $data = $data->latest()->paginate(5);
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
        $request->validate([
            'description' => 'required',
            'abbreviation' => 'required',
        ]);
        $data = Department::create([
            'description' => $request->description,
            'abbreviation' => $request->abbreviation
        ]);

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Department::find($id), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'description' => 'required',
            'abbreviation' => 'required',
        ]);
        $data = Department::find($id);
        $data->description = $request->description;
        $data->abbreviation = $request->abbreviation;
        $data->save();
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Department::find($id);
        $data->delete();

        return response()->json($data, 200);
    }

    public function listDepartment(Request $request){
        $data = Department::query();
        if($request->search != ''){
            $data = Department::where('description','like','%'.$request->search.'%');
        }
        $data = $data->latest()->get();
        return response()->json($data, 200);
    }

}
