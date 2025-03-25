<?php

namespace App\GraphQL\Mutations;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\returnSelf;

final class NotificationMutator
{
    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    public function savePushNotificationToken($_, array $args): bool
    {
        $data = $this->notificationService->savePushNotificationToken([
            "device_token" => $args["device_token"],
            "device_type" => $args["device_type"],
        ]);

        return true;
    }

    public function markNotificationsAsRead($_, array $args): bool
    {
        $authUser = Auth::user();

        $allNotificationsToMark = $args["notification_ids"];

        foreach ($allNotificationsToMark as $notificationId) {
            $this->notificationService->updateNotificationStatus([
                "auth_user_id" => $authUser->id,
                "notification_id" => $notificationId,
                "is_read" => true,
            ]);
        }

        return true;
    }
}
