<?php
class TradeController {
    public function executeTrade($symbol, $units) {
        $tradeService = new TradeExecutionService();
        return $tradeService->executeTrade($_SESSION['userid'], $symbol, $units);
    }
}
?>
