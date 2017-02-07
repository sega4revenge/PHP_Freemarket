<?php
//open connection to mysql db
$connection = mysqli_connect("localhost", "root", "trinhvandieu", "freemartket_9cd9") or die("Error " . mysqli_error($connection));
$connection->set_charset('utf8');

//fetch table rows from mysql db

if (isset($_POST['page'])&&isset($_POST['search'])&&isset($_POST['category'])&&isset($_POST['area'])&&isset($_POST['fitter'])&&isset($_POST['currency'])) {
 	echo "{";
		

	
  	 $page =$_POST['page'];
 	  $search = $_POST['search'];
    $category =$_POST['category'];
	  $area = $_POST['area'];
    $fitter =$_POST['fitter'];
			    $currency = $_POST['currency'];
	$conn =new mysqli("localhost", "root", "trinhvandieu", "freemartket_9cd9") or die("Error " . mysqli_error($connection));


  $sql = "SELECT buy FROM currency WHERE currencycode = '$currency'";

  $result = $conn->query($sql);
	 while($row = $result->fetch_assoc()) {
   
	    $response = $row["buy"];
    }
	
	echo "\"currency\":\"$currency\",";
	echo "\"rate\":\"$response\",";
	echo "feed:";
	if($search=="")
		$searchstring = "";
	else
		$searchstring = " AND product.productname LIKE '%$search%'" ;
	if($category=="0")
		$categorystring = "";
	else
		$categorystring = " AND product.categoryid = '$category'";
	if($area=="")
		$areastring = "";
	else
		$areastring = " AND product.areaproduct = '$area' ";
	if($fitter=="0")
		$fitterstring = " Order by product.productdate desc";
	else if($fitter=="1")
		$fitterstring ="";
	else
		$fitterstring =" Order by product.sharecount desc";
    if ($page < 0) {
        $page = 0;
    }
    $limit = 10;
    $start = ($limit * $page) - $limit;
    $sql = "Select product.productid,product.productname,product.price,users.userid,users.username,category.categoryname,product.productaddress,product.areaproduct,
  product.productstatus,product.productimage,product.productdate,product.description,product.sharecount,product.lot,product.lat 
			From product,users,category Where users.userid = product.userid and product.productstatus = '1' and category.categoryid = product.categoryid $searchstring  $categorystring $areastring $fitterstring  LIMIT $start,$limit";
    $result = $connection->query($sql);
	
    //create an array
    $encode = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $encode[] = $row;
    }

    echo json_encode($encode, 256);
				
    //close the db connection
    mysqli_close($connection);
   

	 echo "}";
} else {
   // required post params is missing
   $response["error"] = TRUE;
  $response["error_msg"] = "Required parameters email or password is missing!";
   echo json_encode($response);
// 	 $page = 1;
// 	  $search = "áo";
// 	    $category = "0";
// 		  $area = "Đà Nẵng";
// 		    $fitter ="0";
// if($search=="")
// 		$searchstring = "";
	
// 	else
// 		$searchstring = " AND product.productname LIKE '%$search%'" ;
// 	if($category=="0")
// 		$categorystring = "";
// 	else
// 		$categorystring = " AND product.categoryid = ".$category ;
// 	if($area=="")
// 		$areastring = "";
// 	else
// 		$areastring = " AND product.areaproduct = '$area' ";
// 	if($fitter=="0")
// 		$fitterstring = " Order by product.productdate desc";
// 	else if($fitter=="1")
// 		$fitterstring ="";
// 	else
// 		$fitterstring =" Order by product.sharecount desc";
//     if ($page < 1) {
//         $page = 1;
//     }
//     $limit = 10;
//     $start = ($limit * $page) - $limit;
//     $sql = "Select product.productid,product.productname,product.price,users.userid,users.username,category.categoryname,product.productaddress,product.areaproduct,product.producttype,
//   product.productstatus,product.productimage,product.productdate,product.description,product.sharecount,product.lot,product.lat 
// 			From product,users,category Where users.userid = product.userid and category.categoryid = product.categoryid $searchstring  $categorystring $areastring $fitterstring  LIMIT $start,$limit";
//     $result = $connection->query($sql);
// 	// echo $sql;

//     //create an array
//     $encode = array();

//     while ($row = mysqli_fetch_assoc($result)) {
//         $encode[] = $row;
//     }

//     echo json_encode($encode, 256);

//     //close the db connection
//     mysqli_close($connection);
//     echo "}";
}
?>		