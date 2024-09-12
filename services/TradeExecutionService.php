<?php
class TradeExecutionService {
    public function executeTrade($accountId, $symbol, $units) {
        $url = "https://api-fxpractice.oanda.com/v3/accounts/{$accountId}/orders";
        $data = [
            'order' => [
                'instrument' => $symbol,
                'units' => $units,
                'type' => 'MARKET',
                'timeInForce' => 'FOK'
            ]
        ];

        $options = [
            'http' => [
                'header' => "Authorization: Bearer " . OANDA_API_KEY,
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
