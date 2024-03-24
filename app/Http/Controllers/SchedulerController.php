<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Session;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Helpers\SchedulerHelper;
use App\Models\Scheduler;
use App\Services\SchedulerService;

class SchedulerController extends Controller
{
    public function __construct(
        protected SchedulerService $schedulerService
    ){}

    public function preview(){
        $session = Session::where('active', true)->first();
        [$batches, $halls, $courses_main] = $this->schedulerService->liveGenerator();
        $stats = [
            'department' => Department::count(),
            'course' => Course::where('session_id', $session->id)->count()
        ];

        return view('schedule.preview', compact('batches', 'session', 'halls', 'courses_main', 'stats'));
    }

    public function printSetup(){
        $session = Session::where('active', true)->first();
        [$batches, $halls, $courses_main, $dailyBatches] = $this->schedulerService->liveGenerator();
        $stats = [
            'department' => Department::count(),
            'course' => Course::where('session_id', $session->id)->count()
        ];

        return view('schedule.setting', compact('batches', 'session', 'halls', 'courses_main', 'stats'));
    }

    public function setUpSave(Request $request){
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'semester' => 'required|string',
        ]);
        $session = Session::where('active', true)->first();

        $schedule = Scheduler::create([
            'session_id' => $session->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'semester' => $request->semester,
        ]);

        return redirect()->route('scheduler.generated.time-table', ['id' => $schedule->id]);
    }

    public function generatedTimeTable($id = null){
        $session = Session::where('active', true)->first();
        $scheduler = Scheduler::find($id);
        [$batches, $halls, $courses_main, $dailyBatches] = $this->schedulerService->liveGenerator();

        $badge_per_day = 10;

        $exam = [];
        $idMap = [];
        $current_time = date('Y-m-d h:i A', strtotime($scheduler->start_date . " " . $scheduler->start_time));
        // dd($current_time);
        foreach($dailyBatches as $day){
            foreach($day as $batchKey => $batch){
                // $newBatch = collect($batch);
                // $groupedBatch = $newBatch->groupBy('course');
                // foreach($groupedBatch as $key => $gBatch){
                //     $exam[$key] = ['count' => isset($exam[$key]['count']) ? $exam[$key]['count']+1 : 1];
                // }
                $x = 0;
                $hashMap = [];
                $courses = [];
                foreach($batch as $schedule){
                    // dd($current_time);
                    $department = Department::where('name', $schedule['department'])->first();
                    $course = Course::where('department_id', $department->id)->where('name', $schedule['course'])->first();
                    $courses[] = $schedule['course'];
                    $end_time = date('Y-m-d h:i A', strtotime($current_time . " + $course->duration minutes"));
                    // if(isset($exam[$schedule['course']]) && $exam[$schedule['course']]['u_key'] != $batchKey){
                    //     $exam[$schedule['course']]['end'] = $end_time;
                    // }else{
                    //     $exam[$schedule['course']]['start'] = $current_time;
                    //     $exam[$schedule['course']]['end'] = $end_time;
                    //     $exam[$schedule['course']]['u_key'] = $batchKey;

                    //     // $exam[$schedule['course']] = 1;

                    //     $x=0;
                    // }
                    // if(!in_array($course->id, $hashMap)){
                    //     $exam[$schedule['course']]['start'] = $current_time;
                    //     $exam[$schedule['course']]['end'] = $end_time;
                    // }else{
                    //     $exam[$schedule['course']]['end'] = $end_time;
                    // }
                    // if($x = 5){
                    //     dd($exam);
                    // }
                    $hashMap[] = $course->id;
                    $x++;
                }
                $mainCourses = array_values(array_unique($courses));
                foreach($mainCourses as $main){
                    if(isset($exam[$main])){
                        $exam[$main]['end'] = $end_time;
                    }else{
                        $exam[$main]['start'] = $current_time;
                        $exam[$main]['end'] = $end_time;

                        $x=0;
                        $current_time = $end_time;
                    }
                }
            }
        }
        dd($exam);

        dd($dailyBatches);
        return view('schedule.generated', ['schedule' => $schedule]);
    }
}
