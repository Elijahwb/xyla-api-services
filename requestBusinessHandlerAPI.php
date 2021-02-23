<?php

session_start();

  include("connection.php");

  #$connector = new Connection("sql313.epizy.com", "epiz_26340426_xyladb", "epiz_26340426", "LfLzX8XpjxNHhxC");

  $serverConnector = new ServerConnection();

  $connector = $serverConnector->connectToServer();

  $connection = $connector->openConnection();

  if($connection->message == "CONNECTION SUCCESSFUL")
  {

    try
    {
      $names = explode(" ", trim($_SESSION['globalUser']));
      
      $date = date('d F Y');
        
      $statement = $connection->pdo->prepare("INSERT INTO xylarequest (date, recipient, category, instructions,amount, userid, celebrityid, transactionToken) VALUES (?,?,?,?,?,?,?,?)");

      $statement->execute([$date,$_POST['businessname'],$_POST['occasion'],$_POST['instructions'],200000,intval($_SESSION['globalId']),intval($_POST['celebrityId']),"No Token"]);

      $statement = null;
        
        echo json_encode(["REQUEST"=>"SUCCESSFUL"]);
    }
    catch (Exception $e){
      #return the jsonified data to the client
      echo "REQUEST FAILED, Please check if you are connected to the internet";
    }
  }
  else
  {
    echo "error Connection To Server Failed!";
  }

  $connector->closeConnection();
?>
