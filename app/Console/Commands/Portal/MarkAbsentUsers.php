<?php

namespace App\Console\Commands\Portal;

use App\Helpers\PortalHelpers;
use App\Models\Attendance\Attendance;
use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbsentUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:mark-absent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark absent for users who have not marked attendance after a specific date';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle(): null|int
    {
        $currentDate = Carbon::now();

        if ($currentDate->isSunday()) {
            $this->info('Today is Sunday. No absent marking is required.');
            return null;
        }

        $users = User::whereDoesntHave('attendance', static function ($query) use ($currentDate) {
            $query->whereDate('created_at','<', $currentDate);
        })
            ->get();

        foreach ($users as $user) {
            Attendance::create([
                'user_id' => $user->id,
                'ip_address' => null,
                'check_in' => null,
                'status' => 1
            ]);
        }

        $this->info('Absent marked for users who have not marked attendance.');
        return Command::SUCCESS;
    }
}
