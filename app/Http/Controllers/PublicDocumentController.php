<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\DocumentAuthor;
use App\Models\Keyword;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class PublicDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
   
        $data = Document::query();
      
        if($request->search != ""){
           $data =  $data->whereAny(['abstract', 'title'],'like','%'. $request->search.'%');
        }

        if($request->search != ""){

            $data->select('keywords.*', 
            'keywords.description',
            'keywords.document_id')
            ->join('keywords', 'keywords.document_id', '=', 'documents.id')
            ->orWhere('keywords.description','like','%'.$request->search.'%')->distinct()->select('documents.*')->get();
        }

        
        if($request->department > 0){
            $data =  $data->where('department_id', $request->department);
        }

        if($request->toyear != null && $request->fromyear != null){
            $data =  $data->whereYear('publication_date','>=', $request->fromyear)
            ->whereYear('publication_date','<=', $request->toyear);
        }

        if($request->typeofpapers != ""){
            $data =  $data->whereIn('type_of_paper_id', $request->typeofpapers);
        }
        if($request->authors != ""){

            $data->select('documents.*', 
            'document_authors.name',
            'document_authors.author_id')
            ->join('document_authors', 'document_authors.document_id', '=', 'documents.id')
            ->whereIn('document_authors.author_id', $request->authors)->distinct()->select('documents.*')->get();
        }

        $data = $data->where('status', 1)->where('upload_type', 0)->with('keywords')->paginate(5);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doc = Document::with('document_file', 'keywords', 'authors', 'typeofpaper')->where('status', 1)->where('upload_type', 0)->find($id);
        return response()->json($doc, 200);
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
        //
    }

   
}
