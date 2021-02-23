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
      
      $currentDate = new DateTime();
      
      $phoneNumber = $_SESSION['globalPhone'];
      
      $phoneNumber = ltrim($phoneNumber,$phoneNumber[0]);

	  $currString = $currentDate->format("Y-m-d");

	  $currentArray = explode("-", $currString);

	  $dpoFormat = $currentArray[0] . "/" . $currentArray[1] . "/" . $currentArray[2];
	  
	  //echo "<pre>";print_r($params);exit();
        $input_xml = '<?xml version="1.0" encoding="utf-8"?>
            <API3G>
                <CompanyToken>3C8F5BE8-C591-4B7E-AF0D-74B4F8ECA865</CompanyToken>
                <Request>createToken</Request>
                <Transaction>
                    <customerFirstName>'.$names[1].'</customerFirstName>
                    <customerLastName>' . $names[0] . '</customerLastName>
                    <customerPhone>+256' . $phoneNumber .'</customerPhone>
                    <customerZip>00256</customerZip>
                    <customerCity>Kampala</customerCity>
                    <customerAddress>Uganda</customerAddress>
                    <customerCountry>UG</customerCountry>
                    <customerEmail>'.$_SESSION['globalEmail'].'</customerEmail>
                    <PaymentAmount>'.$_POST['price'].'</PaymentAmount>
                    <PaymentCurrency>UGX</PaymentCurrency>
                    <DefaultPayment>MO</DefaultPayment>
                    <CompanyRef>'.$_SESSION['globalEmail'].'</CompanyRef>
                    <AllowRecurrent>1</AllowRecurrent>
                    <RedirectURL>http://xyla.club/confirm-request.php</RedirectURL>
                    <BackURL>http://xyla.club/index.php</BackURL>
                    <CompanyRefUnique>0</CompanyRefUnique>
                    <PTL>5</PTL>
                    <PTLtype>Hours</PTLtype>
                </Transaction>
                <Services>
                    <Service>
                        <ServiceType>39175</ServiceType>
                        <ServiceDescription>Advertising Space</ServiceDescription>
                        <ServiceDate>' .$dpoFormat.'</ServiceDate>
                    </Service>
                </Services>
            </API3G>';
        //echo $input_xml;exit();
        $url ="https://secure.3gdirectpay.com/API/v6/";
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSLVERSION,6);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $input_xml);

        $response = curl_exec($ch);
            
        curl_close($ch);
        
        parse_str( $response, $responseFields );
        $xml = new SimpleXMLElement($response);
        //echo "<pre>";print_r($xml);exit();
        if ($response === FALSE){
            echo 'Payment error in first_payment: Unable to connect to the payment gateway, please try again';
        }else{
            if ($xml->Result != '000') {
                echo 'Payment error code in first_payment: '.$xml->Result. ', '.$xml->ResultExplanation;
            }
            $statement = $connection->pdo->prepare("INSERT INTO xylarequest (date, recipient, category, instructions,amount, userid, celebrityid, transactionToken) VALUES (?,?,?,?,?,?,?,?)");

            $statement->execute([$date,"Me",$_POST['occasion'],$_POST['instructions'],50000,intval($_SESSION['globalId']),intval($_POST['celebrityId']),$xml->TransToken]);

            $statement = null;
            
            $paymentURL = "https://secure.3gdirectpay.com/dpopayment.php?ID=".$xml->TransToken;
            
            header('Location: '.$paymentURL);
        }
        
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
