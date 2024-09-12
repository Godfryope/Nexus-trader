<?php
class Transaction {
    private $id;
    private $userId;
    private $amount;
    private $type;
    private $status;
    
    public function __construct($id, $userId, $amount, $type, $status) {
        $this->id = $id;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->type = $type;
        $this->status = $status;
    }

    public function getId() {
        return $this->id;
    }
}
?>
