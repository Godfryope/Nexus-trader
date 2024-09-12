<?php
class AccountController {
    public function deposit($amount, $userEmail) {
        $paymentService = new PaymentService();
        return $paymentService->initiatePayment($amount, $userEmail);
    }

    public function getBalance($userId) {
        global $db_conn;
        $stmt = $db_conn->prepare("SELECT balance FROM accounts WHERE user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['balance'];
    }
}
?>
