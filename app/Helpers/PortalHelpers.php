<?php

namespace App\Helpers;

use App\Models\Auth\User;
use App\Models\Auth\UserOfficeTiming;
use App\Models\ResearchOrders\OrderInfo;
use App\Notifications\PortalNotifications;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use InvalidArgumentException;
use Exception;
use Illuminate\Http\JsonResponse;

class PortalHelpers
{
    public static function convertYmdToMdy($date): string
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }

    public static function convertMdyToYmd($date): string
    {
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }

    public static function visualizeRecordStatus($status)
    {
        if ($status === 'Working') {
            return '<span class="badge badge-primary mt-2">' . $status . '</span>';
        }
        if ($status === 'Completed') {
            return '<span class="badge badge-success mt-2">' . $status . '</span>';
        }
        if ($status === 'Revision') {
            return '<span class="badge badge-warning mt-2">' . $status . '</span>';
        }
        if ($status === 'Canceled') {
            return '<span class="badge badge-danger mt-2">' . $status . '</span>';
        }

        if ($status === 'Paid') {
            return '<span class="badge badge-success mt-2">' . $status . '</span>';
        }
        if ($status === 'Un-Paid') {
            return '<span class="badge badge-danger mt-2">' . $status . '</span>';
        }
        if ($status === 'Partial') {
            return '<span class="badge badge-primary mt-2">' . $status . '</span>';
        }

        return $status;
    }

    public static function checkValue($checkboxValue): string
    {
        if ((int)$checkboxValue === 1) {
            return 'checked';
        }
        return '';
    }

    public static function getPercentage(float $achieveWord, float $target): string
    {
        if ($target <= 0) {
            return '0.00 %';
        }

        if ($achieveWord < 0) {
            throw new InvalidArgumentException('Achieve word and target values must be non-negative.');
        }

        $percentage = ($achieveWord / $target) * 100;

        if ($achieveWord > $target) {
            $extraPercentage = (($achieveWord - $target) / $target) * 100;
            $percentage += $extraPercentage;
        }

        return number_format($percentage, 2) . '%';
    }

    public static function getOrderStatus($status)
    {
        if ((int)$status === 0) {
            return 'Working';
        }
        if ((int)$status === 1) {
            return 'Canceled';
        }
        if ((int)$status === 2) {
            return 'Completed';
        }
        if ((int)$status === 3) {
            return 'Revision';
        }
        return $status;
    }

    public static function getTargetAudienceGender($Target_Audience)
    {
        if ((int)$Target_Audience === 0) {
            return 'Male';
        }
        if ((int)$Target_Audience === 1) {
            return 'Female';
        }
        if ((int)$Target_Audience === 2) {
            return 'Male & Female Both';
        }
        return $Target_Audience;
    }

    public static function getFreeImage($Free_Image)
    {
        if ((int)$Free_Image === 0) {
            return 'Yes';
        }
        if ((int)$Free_Image === 1) {
            return 'No';
        }
        return $Free_Image;
    }

    public static function getGeneric_Type($Generic_Type)
    {
        if ((int)$Generic_Type === 0) {
            return 'Branded';
        }
        if ((int)$Generic_Type === 1) {
            return 'Generic';
        }
        return $Generic_Type;
    }

    public static function getPaymentStatus($status)
    {
        if ((int)$status === 0) {
            return 'Paid';
        }
        if ((int)$status === 1) {
            return 'Un-Paid';
        }
        if ((int)$status === 2) {
            return 'Partial';
        }
        return $status;
    }

    public static function getOrderType($Order_ID): ?int
    {
        if (empty($Order_ID)){
            return null;
        }
        $Order = OrderInfo::where('Order_ID', $Order_ID)->first();
        if ($Order){
            return (int)$Order->Order_Type;
        }
        return null;
    }

    public static function chatSenderName($Role_ID): ?string
    {
        if (in_array($Role_ID, [4, 5, 6, 7], true)) {
            return 'Production Team';
        }
        if (in_array($Role_ID, [8, 12], true)) {
            return 'Content Team';
        }
        if (in_array($Role_ID, [9, 10, 11], true)) {
            return 'Sales Team';
        }
        return null;
    }

    public static function notificationSenderName($Role_Name): ?string
    {
        if (in_array($Role_Name, ['Research Manager', 'Re-Search Coordinator', 'Re-Search Writer', 'Independent Research Writer'], true)) {
            return 'Production Team';
        }
        if (in_array($Role_Name, ['Independent Content Writer', 'Content Writer'], true)) {
            return 'Content Team';
        }
        if (in_array($Role_Name, ['Sales Manager', 'Sales Coordinator', 'Sales Executive'], true)) {
            return 'Sales Team';
        }
        return $Role_Name;
    }

    public static function sendNotification(?string $order_id, ?string $Order_ID, string $message, string $role, array $User_IDs, array $Role_IDs): void
    {
        $currentUser = Auth::guard('Authorized')->user();
        $notificationData = [
            'Order_ID' => $Order_ID,
            'Role_Name' => $role,
            'Message' => $message,
            'Play_Sound' => true,
            'sender_user_id' => $currentUser->id, // Include sender's user ID in the data
        ];

        if (empty($Order_ID)) {
            $Order_Info = OrderInfo::withTrashed()->find($order_id);
            $notificationData['Order_ID'] = $Order_Info->Order_ID;
        }

        $users = User::whereIn('id', $User_IDs)->orWhereIn('Role_ID', $Role_IDs)->get();
        Notification::send($users, new PortalNotifications($notificationData));
    }




    public static function getPageCount($Words, $Spacing): float
    {
        $perPage = match ($Spacing) {
            '1' => 550,
            '1.5' => 412,
            '2' => 275,
            default => 0,
        };

        if ($perPage > 0 && $Words > 0) {
            $result = $Words / $perPage;
        } else {
            $result = 0;
        }
        return round($result, 0);
    }

    public static function getIpAddress(): JsonResponse|string
    {
        $client = new Client();
        try {

            $response = $client->get('https://api.ipify.org');
            return $response->getBody()->getContents();
            
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorMessage = $e->getResponse()->getBody()->getContents();
                Log::error('Request failed with status code: ' . $statusCode);
                return response()->json(['error' => $errorMessage], $statusCode);
            }
            return response()->json(['error' => 'An error occurred. Please try again later.'], 500);
        } catch (Exception|GuzzleException $e) {
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public static function getAttendanceStatus($status)
    {
        if ((int)$status === 0) {
            return 'Present';
        }
        if ((int)$status === 1) {
            return 'Absent';
        }
        if ((int)$status === 2) {
            return 'Late';
        }
        if ((int)$status === 3) {
            return 'Half-Day';
        }
        return $status;
    }

    public static function visualizeAttendanceStatus($status)
    {
        if ($status === 'Present') {
            return '<span class="badge badge-primary mt-2">' . $status . '</span>';
        }
        if ($status === 'Absent') {
            return '<span class="badge badge-danger mt-2">' . $status . '</span>';
        }
        if ($status === 'Late') {
            return '<span class="badge badge-warning mt-2">' . $status . '</span>';
        }
        if ($status === 'Half-Day') {
            return '<span class="badge badge-warning mt-2">' . $status . '</span>';
        }
        if ($status === null) {
            return '<span class="badge badge-danger mt-2">Absent</span>';
        }
        return $status;
    }

    public static function calculateTotalTime($start, $end): string
    {
        return Carbon::parse($end)->diff(Carbon::parse($start))->format('%H:%I:%S'); // Output the total time
    }

    public static function setAttendanceStatus($check_in_time, $user_id): int
    {
        $shiftTiming = UserOfficeTiming::where('user_id', $user_id)->pluck('Start_Time')->first();

        $checkIn = Carbon::parse($check_in_time);
        $shiftStart = Carbon::parse($shiftTiming);
        $shiftEnd = $shiftStart->copy()->addHours(9); // Assuming shift duration is 8 hours
        $timeDifference = $checkIn->diffInMinutes($shiftStart);

        if ($timeDifference >= 270) { // User checked in after 4.5 hours (half shift duration)
            return 3; // Half a day
        }
        if ($timeDifference >= 30) { // User checked in after 30 minutes
            return 2; // Late check-in
        }
        return 0; // Full day
    }

    public static function getLeaveStatus($status): string
    {
        if ((int)$status === 1) {
            return 'Accepted';
        }
        if ((int)$status === 2) {
            return 'Rejected';
        }
        if ((int)$status === 3) {
            return 'Un-Paid';
        }
        return 'Pending';
    }

    public static function getRemainingTime($date, $currentDate = null): string
    {
        $createdAtDate = Carbon::createFromFormat('F d, Y H:i:s A', $date, env('APP_TIMEZONE'))->startOfDay();
        if (!is_null($currentDate)) {
            $currentDate = Carbon::createFromFormat('F d, Y H:i:s A', $currentDate, env('APP_TIMEZONE'))->startOfDay();
        }
        if (is_null($currentDate)) {
            $currentDate = Carbon::parse($currentDate ?? 'now', env('APP_TIMEZONE'))->startOfDay();
        }

        $diff = $currentDate->diff($createdAtDate);
        $remainingTime = '';

        if ($diff->y > 0) {
            $remainingTime .= $diff->y . ' year' . ($diff->y > 1 ? 's ' : ' ');
        }

        if ($diff->m > 0) {
            $remainingTime .= $diff->m . ' month' . ($diff->m > 1 ? 's ' : ' ');
        }

        if ($diff->d > 0) {
            $remainingTime .= $diff->d . ' day' . ($diff->d > 1 ? 's ' : ' ');
        }

        return $remainingTime ?? 'Less than a day';
    }

    public static function getLeaveQuotaPercentage($total, $obtained): int
    {
        if ($total <= 0) {
            return 0;
        }

        return (int)(($obtained / $total) * 100);
    }




    public static function getPortalNotification(): array
    {
        $currentUser = Auth::guard('Authorized')->user();

        // Fetch all notifications for the current user
        $allNotifications = DB::table('notifications')
            ->where('notifiable_id', '=', $currentUser->id)
            ->latest('created_at')
            ->get();

        // Filter notifications to exclude those sent by the current user
        $filteredNotifications = $allNotifications->filter(function ($notification) use ($currentUser) {
            $data = json_decode($notification->data);

            // Check if the sender_user_id is set and not equal to the current user's ID
            return !isset($data->sender_user_id) || $data->sender_user_id != $currentUser->id;
        });

        $notificationsCount = $filteredNotifications->whereNull('read_at')->count();

        return [
            'Notifications' => $filteredNotifications,
            'NotificationsCount' => $notificationsCount
        ];
    }






//    public static function downloadFile($filePath)
//    {
//        $full_filePath = public_path($filePath);
//        if (file_exists($full_filePath)) {
//            return response()->file($full_filePath);
//        }
//        return $full_filePath;
////        abort(404, 'File not found');
//    }

}
