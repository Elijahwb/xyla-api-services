<?php

  header("Access-Control-Allow-Origin: *");
  
  header("Content-Type:application/json");

  include("connection.php");

  $serverConnector = new ServerConnection();

  $connector = $serverConnector->connectToServer();

  $connection = $connector->openConnection();

  if($connection->message == "CONNECTION SUCCESSFUL")
  {
    $statement = $connection->pdo->query("SELECT * FROM celebrity");

    $allCategories = $statement->fetchAll(PDO::FETCH_NUM);

    $categoriesArray = [];

   foreach($allCategories as $category)
   {
      
          array_push($categoriesArray, array("id"=> $category[0],"name"=> $category[1],"info"=> $category[6],"photo"=> $category[4],"selfPrice"=> $category[7],"otherPrice"=> $category[8]));
        
    }
    #return the jsonified data to the client
    echo json_encode($categoriesArray);
  }
  else
  {
    echo json_encode([["error"=> "Server Is Not Reachable!"]]);
  }

  $connector->closeConnection();
?>
