<?php

namespace App\Http\Controllers;

use App\Models\TypeOfPaper;
use Illuminate\Http\Request;

class TypeOfPaperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = TypeOfPaper::query();

        if($request->searchData != ''){
            $data = TypeOfPaper::where('description','like','%'.$request->searchData.'%');
        }

        $data = $data->latest()->paginate(10);

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
            'description'=>'required',
        ]);
        $data = TypeOfPaper::create([
            'description' => $request->description
        ]);

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(TypeOfPaper::find($id), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeOfPaper $typeOfPaper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'description' => 'required'
        ]);
        $data = TypeOfPaper::find($id);
        $data->description = $request->description;
        $data->save();
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = TypeOfPaper::find($id);
        $data->delete();
        return response()->json($data, 200);
    }
}
