<?PHP

   $connection = mysqli_connect("localhost","root","trinhvandieu","freemartket_9cd9") or die("Error " . mysqli_error($connection));
    $connection->set_charset('utf8');
if(isset($_POST['image']) && isset($_POST['image2']) && isset($_POST['image3']) && isset($_POST['image4'])){

    $now = DateTime::createFromFormat('U.u', microtime(true));
    $id = $now->format('YmdHisu');
    $upload_folder = "upload";
    $path1 = "$upload_folder/$id.jpeg";
    $path2 = "$upload_folder/$id-2.jpeg";
    $path3 = "$upload_folder/$id-3.jpeg";
    $path4 = "$upload_folder/$id-4.jpeg";

    $path11 = "http://freemarkets.ga/$upload_folder/$id.jpeg";
    $path21 = "http://freemarkets.ga/$upload_folder/$id-2.jpeg";
    $path31 = "http://freemarkets.ga/$upload_folder/$id-3.jpeg";
    $path41 = "http://freemarkets.ga/$upload_folder/$id-4.jpeg";

    $image1 = $_POST['image'];
    $image2 = $_POST['image2'];
    $image3 = $_POST['image3'];
    $image4 = $_POST['image4'];

    if(file_put_contents($path1, base64_decode($image1)) != false && file_put_contents($path2, base64_decode($image2)) != false && file_put_contents($path3, base64_decode($image3)) != false&& file_put_contents($path4, base64_decode($image4)) != false){
        echo json_encode($path11);
        echo json_encode($path21);
        echo json_encode($path31);
        echo json_encode($path41);

       exit;
    }
    else{
        echo "uploaded_failed";
       exit;
    }

}

?>

