<?php

namespace App\Interfaces;

interface ScheduleRepositoryInterface
{
    public function getAllSchedules();
    public function createSchedule(array $data);
    public function deleteSchedule($id);
}