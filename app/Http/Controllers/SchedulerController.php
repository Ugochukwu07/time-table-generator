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

        try {
            [$batches, $halls, $courses_main] = $this->schedulerService->liveGenerator();
        } catch (\RuntimeException $e) {
            return redirect()->route('venues.index')->with('error', $e->getMessage());
        }

        $stats = [
            'department' => Department::count(),
            'course' => Course::where('session_id', $session->id)->count()
        ];

        return view('schedule.preview', compact('batches', 'session', 'halls', 'courses_main', 'stats'));
    }

    public function printSetup(){
        $session = Session::where('active', true)->first();

        try {
            [$batches, $halls, $courses_main, $dailyBatches] = $this->schedulerService->liveGenerator();
        } catch (\RuntimeException $e) {
            return redirect()->route('venues.index')->with('error', $e->getMessage());
        }

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
            'include_weekends' => 'required|boolean',
        ]);
        $session = Session::where('active', true)->first();

        $schedule = Scheduler::create([
            'session_id' => $session->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'semester' => $request->semester,
            'include_weekends' => $request->include_weekends,
        ]);

        return redirect()->route('scheduler.generated.time-table', ['id' => $schedule->id]);
    }

    public function generatedTimeTable($id = null){
        $session = Session::where('active', true)->first();
        $scheduler = Scheduler::find($id);

        if (!$scheduler) {
            return redirect()->route('scheduler.print.setup')->with('error', 'Schedule setup not found.');
        }

        try {
            [$batches, $halls, $courses_main, $dailyBatches] = $this->schedulerService->liveGenerator();
        } catch (\RuntimeException $e) {
            return redirect()->route('venues.index')->with('error', $e->getMessage());
        }

        $rows = [];
        foreach ($dailyBatches as $day => $dayBatches) {
            $date = $this->examDateForDay($scheduler->start_date, $day, $scheduler->include_weekends);

            foreach ($dayBatches as $batch) {
                $time = preg_replace('/^Day \d+ : /', '', $batch[0]['time'] ?? '');

                foreach ($batch['formatted'] as $exam) {
                    $rows[] = [
                        'date' => $date,
                        'time' => $time,
                        'course' => $exam['course'],
                        'students' => $exam['students'],
                        'venue' => $exam['hall'],
                    ];
                }
            }
        }

        return view('schedule.generated', [
            'scheduler' => $scheduler,
            'session' => $session,
            'rows' => $rows,
        ]);
    }

    /**
     * Resolve the calendar date for the given exam day number, skipping
     * Saturdays and Sundays entirely when $includeWeekends is false.
     */
    protected function examDateForDay(string $startDate, int $day, bool $includeWeekends): string
    {
        $timestamp = strtotime($startDate);

        if (!$includeWeekends) {
            while (in_array((int) date('N', $timestamp), [6, 7])) {
                $timestamp = strtotime('+1 day', $timestamp);
            }
        }

        $remaining = $day - 1;
        while ($remaining > 0) {
            $timestamp = strtotime('+1 day', $timestamp);

            if (!$includeWeekends && in_array((int) date('N', $timestamp), [6, 7])) {
                continue;
            }

            $remaining--;
        }

        return date('l jS F Y', $timestamp);
    }
}
