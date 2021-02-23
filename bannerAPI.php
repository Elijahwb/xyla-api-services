<?php
  header("Content-Type:application/json");

  include("connection.php");

  $serverConnector = new ServerConnection();

  $connector = $serverConnector->connectToServer();

  $connection = $connector->openConnection();

  if($connection->message == "CONNECTION SUCCESSFUL")
  {
    

    $banners = ['https://www.xyla.club/images/page-img/banner6.png'];
    
    echo json_encode($banners);
  }
  else
  {
    echo json_encode([["error"=> "Server Is Not Reachable!"]]);
  }

  $connector->closeConnection();
?>
