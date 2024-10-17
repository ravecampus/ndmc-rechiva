<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Document;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function report(Request $request){
        $from = Carbon::parse($request->date_from);
        $to = Carbon::parse($request->date_to);
        $to->addDays(1);

        $report = $request->report;
        $type_of = $request->type_of_paper;
        $department= $request->department;
        // All documents
        if($report == 1){
            // published
            $published = Department::join('documents','documents.department_id','=', 'departments.id')
            ->select('department_id','type_of_paper_id', 'description',DB::raw('count(upload_type) as published'))
            ->where('upload_type', 0)->where('status', 1)
            ->groupBy('department_id')
            ->whereBetween('documents.created_at',[$from, $to]);

            $archive = Department::join('documents','documents.department_id','=', 'departments.id')
            ->select('department_id','type_of_paper_id', 'description',DB::raw('count(upload_type) as archived'))
            ->where('upload_type', 1)->where('status', 1)
            ->groupBy('department_id')
            ->whereBetween('documents.created_at',[$from, $to]);

            $faculty = Department::join('users','users.department_id','=', 'departments.id')
            ->select('department_id',DB::raw('count(department_id) as faculty'))
            ->groupBy('department_id')
            ->whereBetween('users.created_at',[$from, $to]);

            if($department != 0){
                $faculty = $faculty->where('users.department_id',$department);
                $published = $published->where('documents.department_id',$department);
                $archive = $archive->where('documents.department_id',$department);
            }
            if($type_of != 0){
                $published = $published->where('documents.type_of_paper_id',$type_of);
                $archive = $archive->where('documents.type_of_paper_id',$type_of);
            }

            return response()->json([
                'published' =>$published->get(),
                'archived' =>$archive->get(),
                'faculty' =>$faculty->get(),
            ]);
            
        }else if($report == 2){

            $doc = Document::with('authors', 'typeofpaper', 'department', 'userdoc');
            if($department != 0){
                $doc = $doc->where('department_id',$department);
            }
            if($type_of != 0){
                $doc = $doc->where('type_of_paper_id',$type_of);
            }
            $doc = $doc->whereBetween('created_at',[$from, $to])
                        ->where('upload_type', 0)
                        ->where('status', 1)->get();

            return response()->json(['published_list'=>$doc], 200);
            
        }else if($report == 3){

            $doc = Document::with('authors', 'typeofpaper', 'department', 'userdoc');
            if($department != 0){
                $doc = $doc->where('department_id',$department);
            }
            if($type_of != 0){
                $doc = $doc->where('type_of_paper_id',$type_of);
            }
            $doc = $doc->whereBetween('created_at',[$from, $to])
                        ->where('upload_type', 1)
                        ->where('status', 1)->get();

            return response()->json(['archived_list'=>$doc], 200);
        }else if($report == 4){
           $user =  User::with('department');
           if($department != 0){
                $user = $user->where('department_id',$department);
            }

            $user = $user->whereBetween('created_at',[$from, $to]);

            return response()->json(['users'=>$user->get()], 200);

        }     
    }
    public function mostDownload(){
        $doc = Document::join('downloads', 'downloads.document_id','=', 'documents.id')
            ->select('document_id', 'title', 'abstract', DB::raw('count(document_id) as download'))
            ->groupBy('document_id')->orderBy('download', 'desc')->limit(10)
            ->get();
        
        return response()->json($doc, 200);
    }
}
