<?php

namespace App\Console\Commands\Portal;

use App\Models\Auth\User;
use App\Models\Auth\UserBasicInfo;
use App\Notifications\PortalNotifications;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class NotifyHrOnUserProbation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:user-probation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Its Command Run when probation period of user is completed!';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        info("Cron Job running at " . now());
        $userIds = UserBasicInfo::where('Permanent_Date', null)->pluck('id');

        if ($userIds->isNotEmpty()) {
            $users = User::whereIn('id', $userIds)->whereIn('Role_ID', [1, 2])->get();

            $users->each(function ($user) {
                $probationEndDate = Carbon::parse($user->Joining_Date)->addMonths($user->Probation_Period);
                $oneDayBeforeProbationEnd = $probationEndDate->subDay();

                $currentDate = Carbon::now();
                if ($currentDate->isSameDay($oneDayBeforeProbationEnd)) {
                    $notificationData = [
                        'Emp_ID' => $user->EMP_ID,
                        'Role_Name' => $user->designation->Designation_Name,
                        'Message' => 'The Probation Period of ' . $user->basic_info->full_name . ' is Completed within 1 Day',
                        'Play_Sound' => true,
                    ];
                    Notification::send($user, new PortalNotifications($notificationData));
                }
            });
        }
        return Command::SUCCESS;
    }
}
