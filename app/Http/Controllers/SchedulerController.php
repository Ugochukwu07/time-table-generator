<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Session;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Helpers\SchedulerHelper;
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

    public function generatedTimeTable(){
        $session = Session::where('active', true)->first();
        [$batches, $halls, $courses_main, $dailyBatches] = $this->schedulerService->liveGenerator();

        $badge_per_day = 10;

        dd($dailyBatches);
        return view('schedule.generated');
    }
}
