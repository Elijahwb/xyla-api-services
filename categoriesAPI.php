<?php
  header("Content-Type:application/json");

  include("connection.php");

  $serverConnector = new ServerConnection();

  $connector = $serverConnector->connectToServer();

  $connection = $connector->openConnection();

  if($connection->message == "CONNECTION SUCCESSFUL")
  {
    $statement = $connection->pdo->query("SELECT * FROM category");

    $allCategories = $statement->fetchAll(PDO::FETCH_NUM);

    $categoriesArray = [];

   foreach($allCategories as $category)
   {
      array_push($categoriesArray, array("name"=> $category[1],"id"=> $category[0],"photo"=> $category[2]));
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
