<?PHP


if(isset($_POST['userid'])){
	
	
  	 $userid =$_POST['userid'];
 
  
			
	$conn =new mysqli("localhost", "root", "trinhvandieu", "freemartket_9cd9") or die("Error " . mysqli_error($connection));
	  

  $sql = "SELECT * from messenger where room LIKE '%$userid%'";

  $result = $conn->query($sql);
$encode = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $encode[] = $row;
        }
        echo json_encode($encode, 256);
        if ($encode == null) {
            return null;
        }
    mysqli_close($conn);
   
	

    }
    else{
      
    

}

?>

