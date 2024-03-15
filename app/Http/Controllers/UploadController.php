<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Session;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Imports\CoursesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    public function uploadFirst(){
        $session = Session::where('active', true)->first();
        $stats = [
            'department' => Department::count(),
            'course' => Course::where('session_id', $session->id)->count()
        ];

        return view('upload', [
            'session' => $session->name,
            'stats' => $stats
        ]);
    }

    public function uploadDropzone(Request $request){
        $data = array();

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,csx,csv|max:4106'
        ]);

        if ($validator->fails()) {
            $data = ['success' => 0, 'message' => $validator->errors()->first()]; // Error response

        }else{
            if($request->file('file')) {
                $file = $request->file('file');
                $filePath = $file->store('uploads/courses');
                try{
                    Excel::import(new CoursesImport($file->getClientOriginalName()), $filePath);
                    // Response
                    $data['success'] = 1;
                    $data['message'] = 'Your file was uploaded successfully!';
                } catch(\Exception $e){
                    $data['success'] = 0;
                    $data['message'] = $e->getMessage();
                }
            }else{
                // Response
                $data['success'] = 0;
                $data['message'] = 'Your file not uploaded.';
            }
        }
        return response()->json($data);
    }

    public function statsDepartments(){
        $session = Session::where('active', true)->first();
        $courses = Course::currentSession()->get(['department_id'])->toArray();
        $department_ids = [];
        foreach($courses as $course){
            $department_ids[] = $course['department_id'];
        }

        $department_id_unique = array_unique($department_ids);

        $departments = Department::whereIn('id', $department_id_unique)->get();

        return view('upload.departments', compact('departments', 'session'));
    }

    public function statsCourse($id){
        $department = Department::find($id);
        if(!$department){
            return back()->with('error', 'No department found');
        }
        $session = Session::where('active', true)->first();
        $courses = Course::currentSession()->where('department_id', $id)->get();

        return view('upload.courses', compact('courses', 'session', 'department'));
    }
}
