<?php
namespace App\Helpers;

use Illuminate\Support\Str;

class SchedulerHelper{
    public array $halls;
    public bool $courses_dummy;
    public array $course_names;
    public array $batches;
    public array $final_courses;
    public int $total_capacity;
    public int $total_students;
    public int $number_of_batches;

    public function __construct(
        public array $courses_main, public array $halls_data
    ){}

    public function setData(){
        $this->halls = array_column($this->halls_data, 1);
        $this->total_capacity = array_sum($this->halls);

        $all_courses = array_map(function($course) {
            return array_values($course);
        }, $this->courses_main);

        $this->course_names = array_keys($this->courses_main);

        $this->batches = [];

        $this->final_courses = array_values($all_courses);
        $this->total_students = array_sum($this->flattenArray($this->final_courses));

        $this->number_of_batches = ceil($this->total_students/$this->total_capacity);

        return $this;
    }

    protected function flattenArray($array) {
        $result = [];

        foreach ($array as $item) {
            if (is_array($item)) {
                $result = array_merge($result, $this->flattenArray($item));
            } else {
                $result[] = $item;
            }
        }

        return $result;
    }

    //function to check if the total capacity of halls and the total demand of courses are equal
    protected function checkEquilibrium($halls, $courses) {
        $this->courses_dummy = false;
        if(array_sum($halls) < array_sum($courses)) {
            $halls[] = array_sum($courses) - array_sum($halls);
        }else if(array_sum($halls) > array_sum($courses)) {
            $this->courses_dummy = true;
            $courses[] = array_sum($halls) - array_sum($courses);
        }

        return [$halls, $courses];
    }

    //map batch
    protected function mapBatch($course, $hall, $capacity, $department, $remaining, $id = 0) {
        return [
            'id' => $id,
            "course" => $course,
            "hall" => $hall,
            "capacity" => $capacity,
            "department" => $department,
            'remaining' => [
                "hall" => $remaining[0],
                'students' => $remaining[1]
            ]
        ];

    }

    public function createBatch($courses, $halls, $hall_index = 0, $course_index = 0, $n = 0, $batchMap = []) {
        $course_name = $this->course_names[$n];

        [$halls, $courses] = $this->checkEquilibrium($halls, $courses);

        $equilibrium = array_sum($halls);
        $assigned = 0;

        $batchMap = $batchMap;

        while ($equilibrium > $assigned) {
            $assignment_value = min($halls[$hall_index], $courses[$course_index]);

            $halls[$hall_index] -= $assignment_value;
            $courses[$course_index] -= $assignment_value;
            $assigned += $assignment_value;

            $department  = array_keys($this->courses_main[$course_name])[$course_index];

            $batchMap[] = $this->mapBatch($course_name, $this->halls_data[$hall_index][0], $assignment_value, $department, [$halls[$hall_index], $courses[$course_index]]);

            if($halls[$hall_index] == 0) $hall_index++;

            if($courses[$course_index] == 0) $course_index++;

            if($this->courses_dummy && $hall_index == 4){
                $this->batches[] = $batchMap;
            }

            if($course_index == count($courses)-1){
                if($n >= count($this->final_courses)-1){
                    $this->batches[] = $batchMap;
                    break;
                }
                $n++;

                $courses = $this->final_courses[$n];
                $this->createBatch($courses, $halls, hall_index: $hall_index, course_index: 0, n: $n, batchMap: $batchMap);

                break;
            }

            if($hall_index == 4){
                $halls = array_column($this->halls_data, 1);
                $this->batches[] = $batchMap;
                $batchMap = [];
                if(count($this->batches) > $this->number_of_batches){
                    break;
                }

                $this->createBatch($courses, $halls, hall_index: 0, course_index: $course_index, n: $n);
                break;
            }
        }
    }

    public function execute(){
        $this->createBatch($this->final_courses[0], $this->halls);

        return $this->batches;
    }
}
