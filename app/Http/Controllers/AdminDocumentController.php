<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Feedback;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class AdminDocumentController extends Controller
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
        $docs = $docs->with('document_file','department', 'userdoc')
        ->where('status', 0)
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
        $request->validate([
            'message' => 'required|string'
        ]);

        $doc = Document::find($request->document_id);
        $doc->status = $request->prove;
        Feedback::create([
            'message'=>$request->message,
            'document_id'=>$request->document_id,
            'sender_id'=>Auth::id(),
            // 'receiver_id'=>$doc->user_id,
        ]);

        $note = Notification::create([
            'sender_id' => 0,
            'receiver_id'=> $doc->user_id,
            'caption' =>"Your documents were APPROVED!",
            'document_id' => $doc->id,
            'role' => 1
        ]);
      
        $doc->save();
      


        return response()->json($doc, 200);
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
        //
    }

    public function publishDoc(Request $request){
        $docs = Document::query();
        if($request->search != ''){
            $docs = document::where('title','like','%'.$request->search.'%');
        }
        $docs = $docs->with('document_file','department', 'userdoc')
        ->where('status', 1)
        ->where('upload_type', 0)
        ->latest()->paginate(5);

        return response()->json($docs, 200);
    }

    public function archiveDoc(Request $request){
        $docs = Document::query();
        if($request->search != ''){
            $docs = document::where('title','like','%'.$request->search.'%');
        }
        $docs = $docs->with('document_file','department', 'userdoc')
        >where('status', 1)
        ->where('upload_type', 1)
        ->latest()->paginate(5);

        return response()->json($docs, 200);
    }

    public function cancelDoc(Request $request){
        $docs = Document::query();
        if($request->search != ''){
            $docs = document::where('title','like','%'.$request->search.'%');
        }
        if($request->filter != ''){
            $docs->where('upload_type', $request->filter);
        }
        $docs = $docs->with('document_file','department', 'userdoc')
        ->where('status', 2)
        ->latest()->paginate(5);

        return response()->json($docs, 200);
    }

    public function disapprove(Request $request){
        $request->validate([
            'message' => 'required|string'
        ]);

        $doc = Document::find($request->document_id);
        $doc->status = 2;
        Feedback::create([
            'message'=>$request->message,
            'document_id'=>$request->document_id,
            'sender_id'=>Auth::id(),
        ]);
        $note = Notification::create([
            'sender_id' => 0,
            'receiver_id'=> $doc->user_id,
            'caption' =>"Your documents were DISAPPROVED!",
            'document_id' => $doc->id,
            'role' => 1
        ]);
      
        $doc->save();

        return response()->json($doc, 200);
    }
}
