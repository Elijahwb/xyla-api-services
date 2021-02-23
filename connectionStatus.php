<?php
    class ConnectionStatus
    {
        public $message;
        public $pdo;

        function __construct($message, $pdo)
        {
            $this->message = $message;
            $this->pdo = $pdo;
        }
    }
?>