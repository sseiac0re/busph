<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduleTemplate;
use App\Models\Schedule;
use Carbon\Carbon;

class GenerateSchedules extends Command
{
    // The command to run in terminal: "php artisan schedule:generate"
    protected $signature = 'schedule:generate {days=7 : How many days ahead to generate}';
    protected $description = 'Automatically generate bus schedules based on templates';

    public function handle()
    {
        $daysAhead = $this->argument('days');
        $templates = ScheduleTemplate::all();
        $count = 0;

        $this->info("Found {$templates->count()} templates. Generating schedules for the next {$daysAhead} days...");

        for ($i = 0; $i < $daysAhead; $i++) {
            $date = Carbon::today()->addDays($i);
            $dayName = $date->format('D'); // "Mon", "Tue", etc.

            foreach ($templates as $template) {
                // 1. Check if this bus runs on this day
                if (!in_array($dayName, $template->active_days ?? [])) {
                    continue; 
                }

                // FIX: Ensure we only get the time string (H:i:s) from the template
                // This handles cases where the model might cast it to a Carbon object
                $startTimeString = Carbon::parse($template->start_time)->format('H:i:s');
                $endTimeString = Carbon::parse($template->end_time)->format('H:i:s');

                // 2. Combine the Loop Date with the Template Time
                $currentTime = Carbon::parse($date->format('Y-m-d') . ' ' . $startTimeString);
                $endTime = Carbon::parse($date->format('Y-m-d') . ' ' . $endTimeString);

                while ($currentTime <= $endTime) {
                    // 3. Create the Schedule (if it doesn't exist yet)
                    $exists = Schedule::where('bus_id', $template->bus_id)
                        ->where('route_id', $template->route_id)
                        ->where('departure_time', $currentTime)
                        ->exists();

                    if (!$exists) {
                        Schedule::create([
                            'bus_id' => $template->bus_id,
                            'route_id' => $template->route_id,
                            'departure_time' => $currentTime,
                            'arrival_time' => $currentTime->copy()->addHours(6), 
                        ]);
                        $count++;
                    }

                    // 4. Move to next slot
                    $currentTime->addMinutes($template->frequency_minutes);
                }
            }
        }

        $this->info("Success! Generated {$count} new schedules.");
    }
}