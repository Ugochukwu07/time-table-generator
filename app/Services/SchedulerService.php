<?php
namespace App\Services;

use App\Helpers\SchedulerHelper;
use App\Models\Course;
use App\Models\Session;
use Illuminate\Support\Facades\Cache;

class SchedulerService{
    public function testGenerator(){
        $courses_main = [
            "CSC101" => [
                "MAT" => 50, "CSC" => 300, "MCB" => 350, "BCH" => 200, "STA" => 100
            ],
            "MAT101" => [
                "MAT" => 50, "CSC" => 300, "MCB" => 350, "BCH" => 200, "STA" => 100, "ECE" => 200, "CHEM" => 150, "PAE" => 200, "BOTANY" => 70, "ZOOLOGY" => 180, "PHYSICS" => 200,
            ]
        ];

        $halls_main = [
            ["Hall 1", 120], ["Hall 2", 170], ["Hall 3", 75], ["Hall 4", 35]
        ];

        $schedulerHelper = new SchedulerHelper($courses_main, $halls_main);
        $batches = $schedulerHelper->setData()->execute();

        $timed_batches = $this->appendTimeToBatches($batches);
        $halls = $schedulerHelper->halls;
        $halls = $schedulerHelper->halls;

        foreach($timed_batches as $key => $batch){
            $timed_batches[$key]['formatted'] = $this->formatToHuman($batch);
        }

        return [$timed_batches, $halls, $courses_main];
    }

    public function liveGenerator(){
        $session = Session::active()->first();

        // Key the cache on the current course data's shape rather than a fixed
        // TTL, so it's reused across requests but recomputes the moment a
        // course is added, changed, or removed.
        $fingerprint = Course::where('session_id', $session->id)
            ->selectRaw('COUNT(*) as cnt, MAX(updated_at) as latest')
            ->first();
        $cacheKey = "scheduler.live_generator.{$session->id}.{$fingerprint->cnt}.{$fingerprint->latest}";

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($session) {
            $courses = Course::where('session_id', $session->id)->with('department')->get();
            $courses_main = [];
            $grouped_courses = $courses->groupBy('name');
            foreach($grouped_courses as $key => $grouped_course){
                $departments = [];
                foreach($grouped_course as $item){
                    $departments[$item->department->name] = $item->students;
                }
                $courses_main[$key] = $departments;
            }

            $halls_main = [
                ["Hall 1", 120], ["Hall 2", 170], ["Hall 3", 75], ["Hall 4", 35]
            ];

            $schedulerHelper = new SchedulerHelper($courses_main, $halls_main);
            $batches = $schedulerHelper->setData()->execute();

            [$timed_batches, $dailyBatches] = $this->appendTimeToBatches($batches);
            $halls = $schedulerHelper->halls;

            return [$timed_batches, $halls, $courses_main, $dailyBatches];
        });
    }

    public function formatToHuman(array $data):array {
        $formattedData = [];

        foreach ($data as $item) {
            $key = $item['course'] . '-' . $item['department'];
            if (!isset($formattedData[$key])) {
                $formattedData[$key] = [
                    'department' => $item['department'],
                    'hall' => $item['hall'],
                    'course' => $item['course'],
                    'students' => $item['capacity']
                ];
            } else {
                $formattedData[$key]['hall'] .= '/' . $item['hall'];
                $formattedData[$key]['students'] += $item['capacity'];
            }
        }

        return $formattedData;
    }

    public function appendTimeToBatches($batches){
        $day_start_time = strtotime('09:00');
        $day_end_time = strtotime('14:00');
        $max_day_duration = $day_end_time - $day_start_time;

        // Load every course's duration once instead of querying per batch entry
        $durationLookup = Course::currentSession()
            ->get(['name', 'students', 'duration'])
            ->mapWithKeys(fn ($course) => ["{$course->name}|{$course->students}" => $course->duration])
            ->all();

        $formattedData = [];
        $current_time = $day_start_time;
        $elapsed_today = 0;
        $day = 1;
        $dailyBatches = [];
        $dayBatchAccumulator = [];

        foreach ($batches as $item) {
            $durations = [];
            foreach($item as $ite){
                $durations[] = $durationLookup["{$ite['course']}|{$ite['capacity']}"] ?? 30;
            }
            $mins = max($durations);
            // Format the time
            $time = date('H:i A', $current_time);

            // Increment the current time by the sitting's duration
            $current_time += ($mins * 60);
            $elapsed_today += ($mins * 60);
            $stop = date('H:i A', $current_time);

            // Add the time and a human-readable summary to the item
            $item[0]['time'] = "Day $day : $time - $stop";
            $item['formatted'] = $this->formatToHuman($item);

            $formattedData[] = $item;
            $dayBatchAccumulator[] = $item;

            // Once the day's allotted window (09:00-14:00) is used up, close out the day
            if ($elapsed_today >= $max_day_duration) {
                $dailyBatches[$day] = $dayBatchAccumulator;
                $dayBatchAccumulator = [];
                $day++;
                $current_time = $day_start_time;
                $elapsed_today = 0;
            }
        }

        // Flush whatever is left of the final, partially-filled day
        if (!empty($dayBatchAccumulator)) {
            $dailyBatches[$day] = $dayBatchAccumulator;
        }

        return [$formattedData, $dailyBatches];
    }

    public function groupByDay($batches){
        $start_time = strtotime('09:00');

        $formattedData = [];
        $current_time = $start_time;
        $day = 1;

        foreach ($batches as $item) {
            $durations = [];
            foreach($item as $ite){
                $course = Course::currentSession()->where('name', $ite['course'])->where('students', $ite['capacity'])->first();
                $durations[] = $course->duration ?? 30;
            }
            $mins = max($durations);
            // Format the time
            $time = date('H:i A', $current_time);

            // Increment the current time by 30 minutes
            $current_time += ($mins * 60);
            $stop = date('H:i A', $current_time);

            // Add the time to the item
            $item[0]['time'] = "Day $day : $time - $stop";
            // dd($item);

            // Add the item to the formatted data
            $formattedData[] = $item;

            // Check if it's past 2:00 PM
            if (date('H:i', $current_time) == '14:00') {
                // Move to the next day and reset the time to 9:00 AM
                $current_time = strtotime('tomorrow 09:00');
                $day++;
            }
        }

        return $formattedData;
    }
}
