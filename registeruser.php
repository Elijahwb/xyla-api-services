<?php
  header("Content-Type:application/json");

  include("connection.php");

  $serverConnector = new ServerConnection();

  $connector = $serverConnector->connectToServer();

  $connection = $connector->openConnection();

  if($connection->message == "CONNECTION SUCCESSFUL")
  {

    try
    {
      $statement = $connection->pdo->prepare("INSERT INTO user (name, phone, email, password) VALUES (?,?,?,?)");

      $statement->execute([$_POST['name'],$_POST['phone'],$_POST['email'],$_POST['pass']]);

      $statement = null;

      #return the jsonified data to the client
      echo json_encode(["REGISTERED"=> "SUCCESSFUL"]);
    }
    catch (Exception $e){
      #return the jsonified data to the client
      echo json_encode(["REGISTERED"=> "FAILED"]);
    }
  }
  else
  {
    echo json_encode([["error"=> "Connection To Server Failed!"]]);
  }

  $connector->closeConnection();
?>
