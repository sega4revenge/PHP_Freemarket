<?php

if (isset($_POST['currency'])) {
	
	$conn =new mysqli("localhost", "root", "trinhvandieu", "freemartket_9cd9") or die("Error " . mysqli_error($connection));

	$currency =$_POST['currency'];
  $sql = "SELECT buy FROM currency WHERE currencycode = '$currency'";

  $result = $conn->query($sql);
	 while($row = $result->fetch_assoc()) {
       $response["code"] = $currency;
	    $response["rate"] = $row["buy"];
    }
	
        
        
		     echo json_encode($response, 256);
		
}else{
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email or password) is missing!";
    echo json_encode($response);
}

?>
