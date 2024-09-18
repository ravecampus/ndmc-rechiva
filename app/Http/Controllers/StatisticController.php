<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Download;
use App\Models\Document;
use App\Models\Department;
use App\Models\User;
use App\Models\Visitor;

class StatisticController extends Controller
{
    public function download(Request $request){
        $data = Download::create([
            'user_id' => 0,
            'document_id' => $request->id
        ]);

        return response()->json($data, 200);
    }

    public function visitor(Request $request){
        $data = Visitor::create([
            'hostname' => gethostname(),
        ]);

        return response()->json($data, 200);
    }

    public function statistic(){
        $dl = Download::count();
        $visit = Visitor::count();
        $collect = Document::where('status', 1)->where('upload_type', 0)->count();
        $mem = User::where('role', 1)->where('activate', 0)->count();

        $data = [
            'downloads'=> $dl,
            'visitors'=> $visit,
            'collections'=> $collect,
            'members'=> $mem,
        ];
        return response()->json($data, 200);
    }

    public function departmentstat(){
        $data = Department::with('docs')->get();
        return response()->json($data, 200);
    }

    public function recentSubmission(){
        $data = Document::where('status', 1)->where('upload_type', 0)->latest()->limit(5)->get();
        return response()->json($data, 200);
    }
}
