<?php
  header("Access-Control-Allow-Origin: *");
  header("Content-Type:application/json");

  include("connection.php");

  #$connector = new Connection("sql313.epizy.com", "epiz_26340426_xyladb", "epiz_26340426", "LfLzX8XpjxNHhxC");

  $serverConnector = new ServerConnection();

  $connector = $serverConnector->connectToServer();

  $connection = $connector->openConnection();

  if($connection->message == "CONNECTION SUCCESSFUL")
  {
    $statement = $connection->pdo->prepare("SELECT * FROM user WHERE email = :email and password = :pass");

    $statement->bindParam(":email", $_POST['email'], PDO::PARAM_STR);

    $statement->bindParam(":pass", $_POST['pass'], PDO::PARAM_STR);

    $statement->execute();

    $allUsers = $statement->fetchAll(PDO::FETCH_NUM);

    $usersArray = [];

    if(count($allUsers) > 0){
      array_push($usersArray, ["valid"=> "TRUE"]);
      foreach($allUsers as $user)
      {
         array_push($usersArray, array("id"=> $user[0],"name"=> $user[1],"phone"=> $user[2],"email"=> $user[3],));
       }
    }
    else
    {
      array_push($usersArray, ["valid"=> "FALSE"]);
    }
    #return the jsonified data to the client
    echo json_encode($usersArray);
  }
  else
  {
    echo json_encode([["error"=> "Connection To Server Failed!"]]);
  }

  $connector->closeConnection();
?>
