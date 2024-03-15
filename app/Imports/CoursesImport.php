<?php

namespace App\Imports;

use Exception;
use App\Models\Course;
use App\Models\Session;
use App\Models\Department;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class CoursesImport implements ToCollection
{
    public function __construct(
        public readonly string $file_name
    ){}
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection){
        // Extract course code from file name
        $courseName = extractCode($this->file_name);

        if (is_null($courseName)) {
            throw new \Exception("Invalid File Naming");
        }

        // Get active session
        $session = Session::where('active', true)->firstOrFail();

        // Process each item in the collection
        $collection->each(function($item) use ($courseName, $session) {
            // Create or retrieve department
            $department = Department::firstOrCreate(['name' => $item[1]]);

            // Create or update course
            $course = Course::updateOrCreate(
                ['name' => $courseName, 'session_id' => $session->id, 'department_id' => $department->id],
                ['department_id' => $department->id, 'students' => $item[2]]
            );
        });


    }
}
