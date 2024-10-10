<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\DocumentAuthor;
use App\Models\Notification;
use App\Models\Keyword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $docs = Document::query();
        if($request->search != ''){
            $docs = $docs->where('title','like','%'.$request->search.'%');
        }
        if($request->filter != ''){
            $docs->where('upload_type', $request->filter);
        }
        $docs = $docs->with('document_file')
        ->where('status', 0)
        ->where('user_id', Auth::id())
        ->latest()->paginate(5);

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
        $user = Auth::user();

        if(json_decode($request->upload_type)){
      

            if($user->role == 2){
                $request->validate([
                    'title'=>'required',
                    'college_department'=>'required',
                    'type_of_paper'=>'required',
                    'issue_numbers'=>'required',
                    'doi'=>'required',
                    'publisher'=>'required',
                    'publication_date'=>'required',
                    'authors'=>'required|array',
                    'authors.*' =>'required|string',
                    'abstract'=>'required',
                    'keywords'=>'required|array',
                    'keywords.*'=>'required|string',
                ]);
            }else{
                $request->validate([
                    'title'=>'required',
                    // 'college_department'=>'required',
                    'type_of_paper'=>'required',
                    'issue_numbers'=>'required',
                    'doi'=>'required',
                    'publisher'=>'required',
                    'publication_date'=>'required',
                    'authors'=>'required|array',
                    'authors.*' =>'required|string',
                    'abstract'=>'required',
                    'keywords'=>'required|array',
                    'keywords.*'=>'required|string',
                ]);
            }
         
            $doc = Document::create([
                'title' => $request->title,
                'department_id' => $user->role == 2 ?$request->college_department : $user->department_id,
                'upload_type' => 0,
                'type_of_paper_id' => $request->type_of_paper,
                'issue_numbers' => $request->issue_numbers,
                'publication_date' => new Carbon($request->publication_date),
                'doi' => $request->doi,
                'abstract' => $request->abstract,
                'user_id' => $user->id,
                'publisher' => $request->publisher,
            ]);

    
                foreach (json_decode($request->keywords[0]) as $word) {
                    $kyw = Keyword::create([
                        'description'=>$word->description,
                        'document_id'=>$doc->id,
    
                    ]);
                }
    
                foreach (json_decode($request->authors[0]) as $author) {
                    $kyw = DocumentAuthor::create([
                        'first_name'=>$author->first_name,
                        'middle_name'=>$author->middle_name,
                        'last_name'=>$author->last_name,
                        'author_id'=>$author->id,
                        'document_id'=>$doc->id
                    ]);
                }
                $file = $request->file('file');
                $image = base64_encode(file_get_contents($file));
                $mimeType = $file->getClientMimeType();
                $size = $file->getSize();
                $name = $file->getClientOriginalName();
    
                $docfile = DocumentFile::create([
                    'base64' => $image,
                    'mime_type' => $mimeType,
                    'size' => $size,
                    'original_name' => $name,
                    'document_id' =>$doc->id
    
                ]);

                $note = Notification::create([
                    'sender_id' => $user->id,
                    'receiver_id'=> 0,
                    'caption' =>"submitted a document for publishing",
                    'document_id' => $doc->id
                ]);
                
        
            return response()->json($docfile, 200);
           
        }else{
            $request->validate([
                'title'=>'required',
                'type_of_paper'=>'required',
                'authors'=>'required|array',
                'authors.*' =>'required|string',
            ]);
    
            $doc = Document::create([
                'title' => $request->title,
                'department_id' => $user->department_id,
                'user_id' => $user->id,
                'upload_type' => 1,
                'type_of_paper_id' => $request->type_of_paper,
            ]);
    
                foreach (json_decode($request->authors[0]) as $author) {
                    $kyw = DocumentAuthor::create([
                        'first_name'=>$author->first_name,
                        'middle_name'=>$author->middle_name,
                        'last_name'=>$author->last_name,
                        'author_id'=>$author->id,
                        'document_id'=>$doc->id
                    ]);
                }
                $file = $request->file('file');
                $image = base64_encode(file_get_contents($file));
                $mimeType = $file->getClientMimeType();
                $size = $file->getSize();
                $name = $file->getClientOriginalName();
    
                $docfile = DocumentFile::create([
                    'base64' => $image,
                    'mime_type' => $mimeType,
                    'size' => $size,
                    'original_name' => $name,
                    'document_id' =>$doc->id
    
                ]);
                
                $note = Notification::create([
                    'sender_id' => $user->id,
                    'receiver_id'=> 0,
                    'caption' =>"submitted a document for achiving",
                    'document_id' => $doc->id
                ]);
                
            return response()->json($docfile, 200);
           

        }
       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doc = Document::with('document_file', 'keywords', 'authors', 'typeofpaper', 'feedback', 'department','feedback', 'userdoc')->find($id);
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

        $docfile = DocumentFile::where('document_id', $id)->get();

        foreach($docfile as $docf){
           $dc =  DocumentFile::find($docf->id);
           $dc->delete();
        }
        $doc->delete();
        return response()->json($doc, 200);
    }

    public function publishedAuth(Request $request){
        $docs = Document::query();
        if($request->search != ''){
            $docs = $docs->where('title','like','%'.$request->search.'%');
        }
        $docs = $docs
        ->with('document_file')
        ->where('status',1)
        ->where('upload_type',0)
        ->where('user_id', Auth::id())
        ->latest()->paginate(5);

        return response()->json($docs, 200);
    }

    public function archivedAuth(Request $request){
        $docs = Document::query();
        if($request->search != ''){
            $docs = $docs->where('title','like','%'.$request->search.'%');
        }
        $docs = $docs->with('document_file')
        ->where('status',1)
        ->where('upload_type',1)
        ->where('user_id', Auth::id())
        ->latest()->paginate(5);

        return response()->json($docs, 200);
    }

    public function canceledAuth(Request $request){
        $docs = Document::query();
        if($request->search != ''){
            $docs = $docs->where('title','like','%'.$request->search.'%');
        }
        if($request->filter != ''){
            $docs->where('upload_type', $request->filter);
        }
        $docs = $docs->with('document_file')
        ->where('status',2)
        ->where('user_id', Auth::id())
        ->latest()->paginate(5);

        return response()->json($docs, 200);
    }

    public function recentfaculty(){
        $data = Document::where('user_id', Auth::id())->latest()->limit(5)->get();

        return response()->json($data, 200);
    }

    public function searchList(Request $request){
        $docs = Document::query();
        if($request->search != ''){
            $docs = $docs->where('title','like','%'.$request->search.'%')
            ->where('status', 1)->where('upload_type',0)->latest()->limit(5)->get();
        }

        return response()->json($docs, 200);
    }
}
