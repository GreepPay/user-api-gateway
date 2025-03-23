<?php

namespace App\GraphQL\Mutations;

use App\Services\NotificationService;

final class NotificationMutator
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Save push notification token.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     */
    public function SavePushNotificationToken($_, array $args): bool
    {
        return $this->notificationService->savePushNotificationToken(
            $args['device_token'],
            $args['device_type']
        );
    }

    /**
     * Mark notifications as read.
     *
     * @param mixed $_
     * @param array $args
     * @return bool
     */
    public function MarkNotificationsAsRead($_, array $args): bool
    {
        return $this->notificationService->markNotificationsAsRead($args['notification_uuids']);
    }
}