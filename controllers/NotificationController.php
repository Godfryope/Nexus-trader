<?php
class NotificationController {
    public function sendTradeAlert($message, $phoneNumber) {
        $notificationService = new NotificationService();
        return $notificationService->sendSMS($message, $phoneNumber);
    }
}
?>
