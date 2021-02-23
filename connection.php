<?php

    include("connectionStatus.php");

    class Connection
    {
        public $pdo;

        public $domain;

        public $domainUserName;

        public $domainPassword;

        public $databaseName;

        public $host;

        function __construct($host, $databaseName, $userName, $domainPassword)
        {
            //initialization of the class properties
            $this->databaseName = $databaseName;
            $this->domainUserName = $userName;
            $this->domainPassword = $domainPassword;
            $this->databaseName = $databaseName;
            $this->host = $host;
            $this->domain = "mysql:host=$host;dbname=$databaseName";
            $this->pdo = null;
        }
        //instantiation of the connection to the DATABASE
        function openConnection()
        {
            try
            {
                $this->pdo = new PDO($this->domain, $this->domainUserName, $this->domainPassword);

                return new ConnectionStatus("CONNECTION SUCCESSFUL", $this->pdo);
            }
            //In case of an error
            catch(Exception $exception)
            {
                return new ConnectionStatus("CONNECTION FAILED", "Nothing");
            }
        }

        //Close the DATABASE connection
        function closeConnection()
        {
            $this->pdo = null;
        }
    }

    class ServerConnection{
        function connectToServer(){
        #return new Connection("localhost:3306", "id14449297_xyladb", "id14449297_root", "]PpH|60M!/x3up@g");
        return new Connection("n3plcpnl0115", "xyladb", "wavah", "=c7[EBF_,dwB");
        #return new Connection("localhost:3306", "xyladb", "wavah", "=c7[EBF_,dwB");
      }
    }
?>
