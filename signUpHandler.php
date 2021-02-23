<?php
    session_start();
    
    if(isset($_POST["email"]) && isset($_POST["password"])){
        include("connection.php");

        $serverConnector = new ServerConnection();

        $connector = $serverConnector->connectToServer();

        $connection = $connector->openConnection();

        if($connection->message == "CONNECTION SUCCESSFUL")
        {
            try
            {
            $statement = $connection->pdo->prepare("INSERT INTO user (name, phone, email, password) VALUES (?,?,?,?)");

            $statement->execute([$_POST['fullname'],$_POST['phone'],$_POST['email'],crypt(crypt($_POST['password'],'xx'),'xx')]);

            $statement = null;
            
            $_SESSION["fromRegister"] = true;
            
            header("Location: ./signin.php");
            
            }
            catch (Exception $e){
                if (isset($_SERVER["HTTP_REFERER"])) {
                    header("Location: " . $_SERVER["HTTP_REFERER"]);
                }
            }
        }
        else
        {
            echo json_encode([["error"=> "Connection To Server Failed!"]]);
        }

        $connector->closeConnection();
    }
?>