<?
    session_start();
    
    if(isset($_POST["useremail"]) && isset($_POST["userpassword"])){
        include("connection.php");

        $serverConnector = new ServerConnection();

        $connector = $serverConnector->connectToServer();

        $connection = $connector->openConnection();

        if($connection->message == "CONNECTION SUCCESSFUL")
        {
            $statement = $connection->pdo->prepare("SELECT * FROM user WHERE email = :email and password = :pass");

            $statement->bindParam(":email", $_POST['useremail'], PDO::PARAM_STR);
            
            $harsedPass = crypt(crypt($_POST['userpassword'],'xx'),'xx');

            $statement->bindParam(":pass", $harsedPass, PDO::PARAM_STR);

            $statement->execute();

            $allUsers = $statement->fetchAll(PDO::FETCH_NUM);

            if(count($allUsers) > 0){
                
                $_SESSION['loggedin'] = true;
                $_SESSION['globalUser'] = $allUsers[0][1];
                $_SESSION['globalEmail'] = $allUsers[0][3];
                $_SESSION['globalId'] = $allUsers[0][0];
                $_SESSION['globalPhone'] = $allUsers[0][2];
                
                if($_SESSION['fromRegister']){
                    header('location: ./index.php');
                }
                else{
                    header("Location: " . $_SESSION['prevPage'] );
                }
                
            }
            else
            {
                echo "user does not exist";
                header("Location: ./signin.php");
                
            }
        }
        else
        {
            echo json_encode([["error"=> "Connection To Server Failed!"]]);
        }

        $connector->closeConnection();
    }
?>