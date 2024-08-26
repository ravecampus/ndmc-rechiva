<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentFile;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $docs = Document::query();
        $docs = $docs->latest()->paginate(5);

        return response()->json($docs, 200);
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
            'title'=>'required',
        ]);

        $doc = Document::create([
            'title' => $request->title,
        ]);

        foreach($request->file('files') as $file) {
            $image = base64_encode(file_get_contents($file));
            $docfile = DocumentFile::create([
                'document_file' => $image,
                'document_id' =>$doc->id

            ]);
            
        }
        
     

        return response()->json($request, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doc = Document::find($id);
        return response()->json($doc, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $doc = Document::find($id);
        $doc->title = $request->title;
        $doc->description = $request->description;

        $doc->save();
        return response()->json($doc, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doc = Document::find($id);
        $doc->delete();
        return response()->json($doc, 200);
    }
}
