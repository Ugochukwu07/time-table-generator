<?php
namespace App\Helpers;

class SchedulerHelper{
    public array $halls;
    public array $batches;

    public function __construct(
        public array $courses_main, public array $halls_data
    ){}

    /**
     * Greedily partitions a courses_main map (course => [department => students])
     * into groups where no two courses in the same group share a department.
     * Each group is safe to pack into the same time slot without risking a
     * student sitting two exams at once (approximated at department level,
     * since individual student enrollment isn't tracked).
     *
     * @param array<string, array<string, int>> $courses_main
     * @return array<int, array<string, array<string, int>>>
     */
    public static function partitionByDepartmentClash(array $courses_main): array {
        $groups = [];

        foreach ($courses_main as $courseName => $departments) {
            $departmentNames = array_keys($departments);
            $placed = false;

            foreach ($groups as &$group) {
                if (empty(array_intersect($departmentNames, $group['departments']))) {
                    $group['departments'] = array_merge($group['departments'], $departmentNames);
                    $group['courses'][$courseName] = $departments;
                    $placed = true;
                    break;
                }
            }
            unset($group);

            if (!$placed) {
                $groups[] = [
                    'departments' => $departmentNames,
                    'courses' => [$courseName => $departments],
                ];
            }
        }

        return array_column($groups, 'courses');
    }

    public function setData(){
        $this->halls = array_column($this->halls_data, 1);
        $this->batches = [];

        return $this;
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

    /**
     * Flattens courses_main into an ordered list of [course, department, students]
     * chunks, skipping any zero-student entries. The department name travels
     * with its chunk directly instead of being looked up later by array
     * position, so it can never desync from the course it belongs to.
     *
     * @return array<int, array{0: string, 1: string, 2: int}>
     */
    protected function buildChunks(): array {
        $chunks = [];

        foreach ($this->courses_main as $courseName => $departments) {
            foreach ($departments as $departmentName => $students) {
                if ($students > 0) {
                    $chunks[] = [$courseName, $departmentName, $students];
                }
            }
        }

        return $chunks;
    }

    /**
     * Streams each course/department chunk of students across the halls in
     * order, filling the current hall to capacity before moving to the next.
     * Wrapping back to the first hall closes out one batch (time slot) and
     * starts a new one with every hall refilled to its full capacity. A
     * chunk's remaining demand carries seamlessly across hall and batch
     * boundaries.
     */
    public function execute(){
        if (array_sum(array_column($this->halls_data, 1)) <= 0) {
            throw new \RuntimeException('Configured halls have no usable capacity.');
        }

        $chunks = $this->buildChunks();
        $hall_count = count($this->halls_data);

        $hall_index = 0;
        $hall_remaining = $this->halls_data[0][1];

        $this->batches = [];
        $currentBatch = [];

        foreach ($chunks as [$course, $department, $remaining]) {
            while ($remaining > 0) {
                while ($hall_remaining === 0) {
                    $hall_index++;

                    if ($hall_index >= $hall_count) {
                        $this->batches[] = $currentBatch;
                        $currentBatch = [];
                        $hall_index = 0;
                    }

                    $hall_remaining = $this->halls_data[$hall_index][1];
                }

                $assignment_value = min($remaining, $hall_remaining);
                $remaining -= $assignment_value;
                $hall_remaining -= $assignment_value;

                $currentBatch[] = $this->mapBatch(
                    $course,
                    $this->halls_data[$hall_index][0],
                    $assignment_value,
                    $department,
                    [$hall_remaining, $remaining]
                );
            }
        }

        if (!empty($currentBatch)) {
            $this->batches[] = $currentBatch;
        }

        return $this->batches;
    }
}
