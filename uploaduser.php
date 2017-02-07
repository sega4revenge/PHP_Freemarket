<?PHP

   $connection = mysqli_connect("localhost","root","trinhvandieu","freemartket_9cd9") or die("Error " . mysqli_error($connection));
    $connection->set_charset('utf8');
if(isset($_POST['image'])){

    $now = DateTime::createFromFormat('U.u', microtime(true));
    $id = $now->format('YmdHisu');
    $upload_folder = "upload";
    $path = "$upload_folder/$id.jpeg";

    $path2 = "http://freemarkets.ga/$upload_folder/$id.jpeg";

    $image = $_POST['image'];

    if(file_put_contents($path, base64_decode($image)) != false){
        echo json_encode($path2);

       exit;
    }
    else{
        echo "uploaded_failed";
       exit;
    }

}

?>


