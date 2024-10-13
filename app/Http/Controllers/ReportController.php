<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function report(Request $request){
        $from = Carbon::parse($request->date_from);
        $to = Carbon::parse($request->date_to);
        $to->addDays(1);

        $upload = Department::join('documents','documents.department_id','=', 'departments.id')
            ->select('department_id','description',DB::raw('count(department_id) as number_of_submission'))
            ->groupBy('department_id')
            ->whereBetween('documents.created_at',[$from, $to])

            ->get();

        $members = Department::join('users','users.department_id','=', 'departments.id')
            ->select('department_id','description',DB::raw('count(department_id) as number_of_member'))
            ->groupBy('department_id')
            ->whereBetween('users.created_at',[$from, $to])
            ->get();
        $data = [
            'upload'=>$upload,
            'members'=>$members
        ];

        return response()->json($data, 200);
    }

    public function mostDownload(){
        $doc = Document::join('downloads', 'downloads.document_id','=', 'documents.id')
            ->select('document_id', 'title', 'abstract', DB::raw('count(document_id) as download'))
            ->groupBy('document_id')->orderBy('download', 'desc')->limit(10)
            ->get();
        
        return response()->json($doc, 200);
    }
}
