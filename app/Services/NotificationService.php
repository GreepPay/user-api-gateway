<?php

namespace App\Services;

use App\Datasource\NetworkHandler;
use Illuminate\Http\Request;

class NotificationService
{
    protected $serviceUrl;
    protected $notificationNetwork;

    public function __construct(
        $useCache = true,
        $headers = [],
        $apiType = "graphql"
    ) {
        $this->serviceUrl = env(
            "NOTIFICATION_API",
            env("SERVICE_BROKER_URL") . "/broker/greep-notification/" . env("APP_STATE")
        );
        $this->notificationNetwork = new NetworkHandler(
            "",
            $this->serviceUrl,
            $useCache,
            $headers,
            $apiType
        );
    }

    /**
     * Send a notification using a template.
     *
     * @param Request $request
     * @return mixed
     */
    public function saveNotificationtoken(Request $request)
    {
        return $this->notificationNetwork->post("/v1/device-tokens", $request->all());
    }

    /**
     * Update notification read status.
     *
     * @param Request $request
     * @return mixed
     */
    public function >markNotificationsAsRead(Request $request)
    {
        
        return $this->notificationNetwork->put("/v1/device-tokens", $request->all());
    }

    /**
     * Send a broadcast notification to multiple users.
     *
     * @param Request $request
     * @return mixed
     */
    public function sendBroadcastNotification(Request $request)
    {
        return $this->notificationNetwork->post("/v1/notifications/broadcast", $request->all());
    }
}