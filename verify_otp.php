<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
 
$response = array();

 
if (isset($_POST['otp']) && $_POST['otp'] != '') {
    $otp = $_POST['otp'];
 
 
    $user = $db->activateUser($otp);
 
   
        $response["message"] = "User created successfully!";
              
     
} else {
   $response["message"] = "Sorry! OTP is missing.";
 
            
     

}
 
 
echo json_encode($response);
?>