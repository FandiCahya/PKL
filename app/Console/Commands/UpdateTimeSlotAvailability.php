<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\TimeSlot;

class UpdateTimeSlotAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timeslot:update-availability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update TimeSlot availability based on current time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $timeSlots = TimeSlot::where('availability', false)
            ->where('end_time', '<=', $now->toTimeString())
            ->get();

        foreach ($timeSlots as $timeSlot) {
            $timeSlot->update(['availability' => true]);
        }

        $this->info('TimeSlot availability updated successfully.');
    }
}
