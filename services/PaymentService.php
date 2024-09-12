<?php
class PaymentService {
    public function initiatePayment($amount, $userEmail) {
        $url = "https://api.flutterwave.com/v3/payments";
        $data = [
            'tx_ref' => uniqid(),
            'amount' => $amount,
            'currency' => 'NGN',
            'redirect_url' => 'https://your-website.com/payment/callback',
            'customer' => ['email' => $userEmail],
        ];

        $options = [
            'http' => [
                'header' => "Authorization: Bearer " . FLUTTERWAVE_API_KEY,
                'method' => 'POST',
                'content' => json_encode($data)
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        return json_decode($response, true);
    }
}
?>
