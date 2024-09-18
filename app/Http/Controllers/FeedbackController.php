<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Feedback;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
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
        $request->validate([
            'message' => 'required|string'
        ]);

        $doc = Document::find($request->document_id);
        Feedback::create([
            'message'=>$request->message,
            'document_id'=>$request->document_id,
            'sender_id'=>Auth::id(),
        ]);
        if(Auth::user()->role == 2){
            $note = Notification::create([
                'sender_id' => 0,
                'receiver_id'=> $doc->user_id,
                'caption' =>"You have a message!",
                'document_id' => $doc->id,
                'role' => 1
            ]);
        }else{
            $note = Notification::create([
                'sender_id' => $doc->user_id,
                'receiver_id'=>0,
                'caption' =>"You have a message!",
                'document_id' => $doc->id,
                'role' => 0
            ]);
        }
       

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
}
