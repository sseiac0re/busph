<?php

namespace App\Repositories;

use App\Interfaces\ScheduleRepositoryInterface;
use App\Models\Schedule;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function getAllSchedules()
    {
        // Fetch schedules with their associated Bus and Route data
        return Schedule::with(['bus', 'route'])->get();
    }

    public function createSchedule(array $data)
    {
        return Schedule::create($data);
    }

    public function deleteSchedule($id)
    {
        return Schedule::destroy($id);
    }
}