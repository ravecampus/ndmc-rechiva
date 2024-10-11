<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentAuthor;
use App\Models\Keyword;
use App\Models\DocumentFile;

class DocumentAuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = DocumentAuthor::find($id);
        $data->delete();

        return response()->json($data, 200);
    }

    public function keyword(string $id)
    {
        $data = Keyword::find($id);
        $data->delete();

        return response()->json($data, 200);
    }

    public function docUpload(Request $request){
        $file = $request->file('file');
        $image = base64_encode(file_get_contents($file));
        $mimeType = $file->getClientMimeType();
        $size = $file->getSize();
        $name = $file->getClientOriginalName();

        $docf = DocumentFile::find($request->id);
        $docf->base64 = $image;
        $docf->mime_type = $mimeType;
        $docf->size = $size;
        $docf->original_name = $name;
        $docf->save();

        return response()->json($docf, 200);

    }
}
