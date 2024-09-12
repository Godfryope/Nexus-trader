<?php
class MarketDataService {
    public function getMarketData($symbol) {
        $url = "https://api.twelvedata.com/time_series?symbol={$symbol}&interval=1min&apikey=" . TWELVE_DATA_API_KEY;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
}
?>
