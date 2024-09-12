<?php
use Twilio\Rest\Client;

class NotificationService {
    public function sendSMS($message, $phoneNumber) {
        $client = new Client(TWILIO_SID, TWILIO_TOKEN);
        $client->messages->create(
            $phoneNumber,
            [
                'from' => '+123456789',  // Your Twilio number
                'body' => $message
            ]
        );
    }
}
?>
