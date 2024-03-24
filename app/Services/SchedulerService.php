<?php
namespace App\Services;

use App\Helpers\SchedulerHelper;
use App\Models\Course;

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
        $courses = Course::currentSession()->get();
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
        $halls = $schedulerHelper->halls;

        foreach($timed_batches as $key => $batch){
            $timed_batches[$key]['formatted'] = $this->formatToHuman($batch);
        }

        return [$timed_batches, $halls, $courses_main, $dailyBatches];
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
        $start_time = strtotime('09:00');

        $formattedData = [];
        $current_time = $start_time;
        $day = 1;
        $dailyBatches = [];

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
            // $col_items = collect($item);
            // dd($col_items->where('course', 'AST232'));
            // Add the item to the formatted data
            $formattedData[] = $item;

            // Check if it's past 2:00 PM
            if (date('H:i', $current_time) == '14:00') {
                // Move to the next day and reset the time to 9:00 AM
                $current_time = strtotime('tomorrow 09:00');
                $dailyBatches[$day] = $formattedData;
                // $formattedData = [];
                $day++;
            }
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
