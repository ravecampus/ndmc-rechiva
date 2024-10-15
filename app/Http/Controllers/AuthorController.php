<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Author::query();
        if($request->search != ''){
            $data = Author::where('first_name','like','%'.$request->search.'%')
            ->orWhere('middle_name','like','%'.$request->search.'%')
            ->orWhere('last_name','like','%'.$request->search.'%');
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
            'first_name'=>'required|string',
            'middle_name'=>'required|string',
            'last_name'=>'required|string',
        ]);

        $author = Author::where('first_name', $request->first_name)
                    ->where('middle_name', $request->middle_name)
                    ->where('last_name', $request->last_name)->count();
        if($author > 0){
            $errors = ['errors'=>['first_name' => ['Duplicate entry!']]];
            return response()->json($errors,401);
        }
        $data = Author::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name
        ]);

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Author::find($id), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required'
        ]);
        $data = Author::find($id);
        $data->first_name = $request->first_name;
        $data->middle_name = $request->middle_name;
        $data->last_name = $request->last_name;
        $data->save();
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Author::find($id);
        $data->delete();

        return response()->json($data, 200);
    }

    public function listAuthor(Request $request){
        $data = Author::query();
        if($request->search != ''){
            $data = Author::where('first_name','like','%'.$request->search.'%')
            ->orWhere('middle_name','like','%'.$request->search.'%')
            ->orWhere('last_name','like','%'.$request->search.'%');
        }
        $data = $data->latest()->get();
        return response()->json($data, 200);
    }
}
