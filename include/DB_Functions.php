<?php
/**
 * @author Ravi Tamada
 * @link http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/ Complete tutorial
 */
const DEFAULT_URL = 'https://vimarket-11dcb.firebaseio.com/';


class DB_Functions
{

    private $conn;

    // constructor
    function __construct()
    {
        require_once 'DB_Connect.php';
	

        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct()
    {
    }
	public function getUserInfo() {
    $sms = new SpeedSMSAPI();
    $userInfo = $sms->getUserInfo();
    var_dump($userInfo);
	}

	public function sendSMS($phones, $content) {
    $sms = new SpeedSMSAPI();
    $return = $sms->sendSMS($phones, $content, SpeedSMSAPI::SMS_TYPE_CSKH, "", 1);
    var_dump($return);
	}
    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($username, $email, $password, $phone, $address, $area, $firebaseid,$otp)
    {		require_once 'SpeedSMSAPI.php';
		   if (!$this->isUserExists($phone)) {

			    $stmt = $this->conn->prepare("DELETE sms_codes , users  FROM users  INNER JOIN sms_codes  
WHERE users.userid= sms_codes.userid and users.phone = ?");
				$stmt->bind_param("s",$phone);
				$stmt->execute();
            
        $api_key = $this->generateApiKey();
        $this->conn->set_charset('utf8');
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $stmt = $this->conn->prepare("INSERT INTO users(username,email,encrypted_password,salt,phone, address,area,datecreate,firebaseid,apikey) VALUES(?,?,?,?, ?, ?, ?, NOW(),?,?)  ON DUPLICATE KEY UPDATE    
username=?, email=?,encrypted_password=?,salt=?,phone=?, address=?,area=?,datecreate=NOW(),firebaseid=?,apikey=?");
        $stmt->bind_param("ssssssssssssssssss", $username, $email, $encrypted_password, $salt, $phone, $address, $area, $firebaseid, $api_key,$username, $email, $encrypted_password, $salt, $phone, $address, $area, $firebaseid, $api_key);
        $result = $stmt->execute();
		$new_user_id = $stmt->insert_id;
        $stmt->close();
        // check for successful store
        if ($result) {
            $otp_result = $this->createOtp($new_user_id, $otp);
			$this->sendSMS([$phone], "Hello! Welcome to Boss Sega. Your OPT is ". $otp);
               return USER_CREATED_SUCCESSFULLY;
        } else {
           return USER_CREATE_FAILED;
        }
		}
		 else {
            // User with same email already existed in the db
            return USER_ALREADY_EXISTED;
        }
		    }
    //store comments
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password, $firebaseupdate)
    {
        $this->conn->set_charset('utf8');
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? and status = 1");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $stmt->bind_result($userid, $email, $username, $encrypted_password, $salt, $phone, $address, $area, $userpic, $datecreate, $dateupdate, $status, $firebaseid, $rate, $count, $apikey);
            echo $encrypted_password;
            $user = $stmt->fetch();
            $stmt->close();
            // verifying user password
            $salt = $salt;
            $encrypted_password = $encrypted_password;
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                if ($user != false) {
                    $sql = "UPDATE users SET firebaseid='$firebaseupdate' WHERE userid = '$userid'";

                    if ($this->conn->query($sql) === TRUE) {
                        // use is found
                        $response["error"] = FALSE;
                        $response["user"]["userid"] = $userid;
                        $response["user"]["name"] = $username;
                        $response["user"]["email"] = $email;
                        $response["user"]["phone"] = $phone;
                        $response["user"]["address"] = $address;
                        $response["user"]["area"] = $area;
                        $response["user"]["userpic"] = $userpic;
                        $response["user"]["datecreate"] = $datecreate;
                        $response["user"]["dateupdate"] = $dateupdate;
                        $response["user"]["firebaseid"] = $firebaseupdate;
                        $response["user"]["rate"] = $rate;
                        $response["user"]["count"] = $count;
                        $response["user"]["apikey"] = $apikey;

                        echo json_encode($response, 256);
                        echo "Error updating record: " . $this->conn->error;
                    }

                } else {
                    // user is not found with the credentials
                    $response["error"] = TRUE;
                    $response["error_msg"] = "Login credentials are wrong. Please try again!";
                    echo json_encode($response);
                }
            } else {
                // required post params is missing

              
                $response["error"] = TRUE;
                $response["error_msg"] = "Required parameters email or password is missing!";
                echo json_encode($response);
            }
        } else {
            return NULL;
        }
    }
 public function getUser($id)
    {
        $this->conn->set_charset('utf8');
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE userid= ?");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            $stmt->bind_result($userid, $email, $username, $encrypted_password, $salt, $phone, $address, $area, $userpic, $datecreate, $dateupdate, $status, $firebaseid, $rate, $count, $apikey);
            $user = $stmt->fetch();
            $stmt->close();
			   $response["error"] = FALSE;
                        $response["user"]["userid"] = $userid;
                        $response["user"]["name"] = $username;
                        $response["user"]["email"] = $email;
                        $response["user"]["phone"] = $phone;
                        $response["user"]["address"] = $address;
                        $response["user"]["area"] = $area;
                        $response["user"]["userpic"] = $userpic;
                        $response["user"]["datecreate"] = $datecreate;
                        $response["user"]["dateupdate"] = $dateupdate;
                        $response["user"]["firebaseid"] = $firebaseupdate;
                        $response["user"]["rate"] = $rate;
                        $response["user"]["count"] = $count;
                        $response["user"]["apikey"] = $apikey;

                        echo json_encode($response, 256);
                        echo "Error updating record: " . $this->conn->error;
            // verifying user password
         
        } else {
            return NULL;
        }
    }
    public function getDetailProduct($productid, $userrated, $userrating)
    {

        $this->conn->set_charset('utf8');
        //fetch table rows from mysql db

        $this->conn->set_charset('utf8');
        $favorite = $this->isUserExistedFavorite($userrated, $userrating);
        $this->conn->set_charset('utf8');
        $stmt = $this->conn->prepare("SELECT product.productid,product.productname,product.price,users.userid,users.username,users.phone,users.userpic,users.email,category.categoryname,product.productaddress,product.areaproduct,product.productstatus,product.productimage,product.productdate,product.description,product.sharecount,product.lat,product.lot From product,users,category Where users.userid = product.userid and category.categoryid = product.categoryid and product.productid = ?");
        $stmt->bind_param("s", $productid);
        if ($stmt->execute()) {
            $stmt->bind_result($productid, $productname, $price, $userid, $username, $phone, $userpic, $email, $categoryname, $productaddress, $areaproduct, $productstatus, $productimage, $productdate, $description, $sharecount, $lat, $lot);
            $product = $stmt->fetch();
            $stmt->close();
            // $stmt1 = $this->conn->prepare("SELECT point FROM rate WHERE userrated = $userrated and userrating = $userrating");
            // $stmt1->execute();
            // $stmt1->bind_result($point);
            // $stmt1->fetch();


            // check for password equality
            if ($product != false) {
                $stmt = $this->conn->prepare("SELECT COUNT(*),AVG(point) FROM rate WHERE userrating = ?  and point != 0");
                $stmt->bind_param("s", $userid);
                $stmt->execute();
                $stmt->bind_result($count, $rate);
                $stmt->fetch();
                $stmt->close();
                $stmt = $this->conn->prepare("SELECT point FROM rate WHERE userrated = ? and userrating = ? and point != 0 ");
                $stmt->bind_param("ss", $userrated, $userrating);
                $stmt->execute();
                $stmt->bind_result($point);
                $stmt->fetch();
                $stmt->close();

                // use is found
                $response["product"]["productid"] = $productid;
                $response["product"]["productname"] = $productname;
                $response["product"]["price"] = $price;
                $response["product"]["userid"] = $userid;
                $response["product"]["username"] = $username;
                $response["product"]["phone"] = $phone;
                $response["product"]["userpic"] = $userpic;
                $response["product"]["email"] = $email;
                $response["product"]["rate"] = $rate;
                $response["product"]["count"] = $count;
                $response["product"]["point"] = $point;
                $response["product"]["categoryname"] = $categoryname;
                $response["product"]["productaddress"] = $productaddress;
                $response["product"]["areaproduct"] = $areaproduct;
                $response["product"]["productstatus"] = $productstatus;
                $response["product"]["productimage"] = $productimage;
                $response["product"]["productdate"] = $productdate;
                $response["product"]["description"] = $description;
                $response["product"]["sharecount"] = $sharecount;
                $response["product"]["favorite"] = $favorite;
                $response["product"]["lat"] = $lat;
                $response["product"]["lot"] = $lot;
                $response["product"]["favorite"] = $favorite;


                echo json_encode($response, 256);

            } else {
                // user is not found with the credentials
                $response["error"] = TRUE;
                $response["error_msg"] = "Login credentials are wrong. Please try again!";
                echo json_encode($response);
            }
        } else {
            return NULL;
        }
    }

    public function getRateDetail($userid)
    {
        $this->conn->set_charset('utf8');
        //fetch table rows from mysql db
        $sql = "SELECT rate.point,users.userid,users.username,users.userpic,rate.contentcomment,rate.time from rate,users where users.userid = rate.userrated and userrating=$userid and point != 0 GROUP BY userid ORDER BY rate.time DESC ";
        $result = $this->conn->query($sql);
        //create an array
        $encode = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $encode[] = $row;
        }
        echo json_encode($encode, 256);
        if ($encode == null) {
            return null;
        }
    }

     public function getRateDetailProfile($userid)
    {
        $this->conn->set_charset('utf8');
        //  $sql = "SELECT COUNT(*) as count,AVG(point) as rate FROM rate WHERE userrating = $userid  and point != 0";
        // $result = $this->conn->query($sql);
        // //create an array
        // $encode = array();
        // while ($row = mysqli_fetch_assoc($result)) {
        //     $encode[] = $row;
        // }
        // echo json_encode($encode, 256);
        // if ($encode == null) {
        //     return null;
        // }
      

        //fetch table rows from mysql db
        $sql = "SELECT point,count(*) AS count from rate where userrating=$userid and point != 0 GROUP BY point";
                $result = $this->conn->query($sql);

        //create an array
        $encode = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $encode[] = $row;
        }
        echo json_encode($encode, 256);
        if ($encode == null) {
            return null;
        }
    }
   public function getNotification($userid)
    {
        $this->conn->set_charset('utf8');
        //fetch table rows from mysql db
        $sql = "SELECT text FROM notification where userid = '$userid'";
        $result = $this->conn->query($sql);
        //create an array
        $encode = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $encode[] = $row;
        }
        echo json_encode($encode, 256);
        if ($encode == null) {
            return null;
        }
    }
    /**
     * Check user is existed or not
     */
    public function isUserExisted($email)
    {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ? and status = 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
			
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }
 public function isRateExisted($userid)
    {
        $stmt = $this->conn->prepare("SELECT userrating from rate WHERE userrating = ?");
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    public function isUserExistedRating($userrated, $userrating, $point)
    {
        $stmt = $this->conn->prepare("SELECT userrated,userrating from rate WHERE userrated = ? and userrating =?");
        $stmt->bind_param("ss", $userrated, $userrating);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // user existed
            $stmt = $this->conn->prepare("UPDATE rate SET point = $point WHERE userrated = $userrated and userrating = $userrating");
            $stmt->execute();

            // $query = "UPDATE rate SET point = $point WHERE userrated = $userrated and userrating = $userrating" ;
            // mysql_query($this->conn,$query);
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    public function isUserExistedFavorite($userrated, $userrating)
    {
        $stmt = $this->conn->prepare("SELECT userid,friendid from favorite WHERE userid = ? and friendid =?");
        $stmt->bind_param("ss", $userrated, $userrating);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // user existed
            // $stmt = $this->conn->prepare("UPDATE rate SET point = $point WHERE userrated = $userrated and userrating = $userrating");
            //     $stmt->execute();

            // $query = "UPDATE rate SET point = $point WHERE userrated = $userrated and userrating = $userrating" ;
            // mysql_query($this->conn,$query);
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password)
    {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array(
            "salt" => $salt,
            "encrypted" => $encrypted
        );
        return $hash;
    }


    public function checkhashSSHA($salt, $password)
    {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }

    public function storeComment($userid, $contentcomment, $productid)
    {
        function send_notification($tokens, $message)
        {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $fields = array(
                'registration_ids' => $tokens,
                'data' => $message
            );

            $headers = array(
                'Authorization:key = AIzaSyCEmeHXjGFCMzqhFrSPCE9zEmBuY7A6FLM ',
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
            return $result;
        }


        $this->conn->set_charset('utf8');
        $time = round(microtime(true) * 1000);
        $stmt = $this->conn->prepare("INSERT INTO comments(userid,productid,time,contentcomment) VALUES(?,?,?,?)");
        $stmt->bind_param("ssss", $userid, $productid, $time, $contentcomment);
        $result = $stmt->execute();
        $stmt->close();
        // check for successful store
        if ($result) {
            $conn = mysqli_connect("localhost", "root", "", "vimarket");

            $sql = " Select firebaseid From users";

            $result = mysqli_query($conn, $sql);
            $tokens = array();

            if (mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {
                    $tokens[] = $row["firebaseid"];
                }
            }

            mysqli_close($conn);

            $message = array(
                "message" => " có người bình luận sản phẩm của bạn "
            );
            $message_status = send_notification($tokens, $message);
            echo $message_status;

        } else {
            return false;
        }
    }

    public function notification($productname,$productid,$userid)
    {
        function send_notification($tokens, $message)
        {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $fields = array(
                'registration_ids' => $tokens,
                'data' => $message
            );

            $headers = array(
                'Authorization:key = AIzaSyCEmeHXjGFCMzqhFrSPCE9zEmBuY7A6FLM ',
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
            return $result;
        }

        $conn = mysqli_connect("localhost", "root", "trinhvandieu", "freemartket_9cd9");
        $sql = "Select firebaseid From users,notification WHERE notification.text LIKE '%$productname%' and notification.userid = users.userid";
        $result = mysqli_query($conn, $sql);
        $tokens = array();

        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {
                $tokens[] = $row["firebaseid"];
            }
        }

        mysqli_close($conn);

        $message = array(
            "message" => "CO HANG \"$productname\"", "productid" => "$productid", "userid" => "$userid"
        );
        $message_status = send_notification($tokens, $message);
        echo $message_status;


    }

    public function sendEmail($email, $temp_password)
    {

        $mail = $this->mail;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'cit.finger.co@gmail.com';
        $mail->Password = 'citfinger123';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->From = 'sega4revenge3@gmail.com';
        $mail->FromName = 'Your Name';
        $mail->addAddress($email, 'Your Name');

        $mail->addReplyTo('sega4revenge3@gmail.com', 'Your Name');

        $mail->WordWrap = 50;
        $mail->isHTML(true);

        $mail->Subject = 'Password Reset Request';
        $mail->Body = 'Hi,<br><br> Your password reset code is <b>' . $temp_password . '</b> . This code expires in 120 seconds. Enter this code within 120 seconds to reset your password.<br><br>Thanks,<br>Learn2Crack.';

        if (!$mail->send()) {

            return $mail->ErrorInfo;

        } else {

            return true;

        }
    }

    public function passwordResetRequest($phone)
    {

        $random_string = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 6)), 0, 6);
        $hash = $this->getHash($random_string);
        $encrypted_temp_password = $hash["encrypted"];
        $salt = $hash["salt"];
        $time = round(microtime(true) * 1000);

        $sql = 'SELECT * from password_reset_request WHERE phone = ?';
        $query = $this->conn->prepare($sql);
        $query->bind_param("s", $phone);
        $query->execute();

        if ($query) {

            $query->store_result();
            $row_count = $query->num_rows;

            if ($row_count == 0) {


                $insert_sql = 'INSERT INTO password_reset_request SET phone =?,encrypted_temp_password =?,
                    salt =?,created_at = ?';
                $insert_query = $this->conn->prepare($insert_sql);
                $insert_query->bind_param("ssss", $phone, $encrypted_temp_password, $salt, $time);
                $insert_query->execute();

                if ($insert_query) {

                    $user["phone"] = $phone;
                    $user["temp_password"] = $random_string;

                    return $user;

                } else {

                    return false;

                }


            } else {

                $update_sql = 'UPDATE password_reset_request SET phone = ?,encrypted_temp_password = ?,
                    salt =? ,created_at = ?';
                $update_query = $this->conn->prepare($update_sql);
                $update_query->bind_param("ssss", $phone, $encrypted_temp_password, $salt, $time);
                $update_query->execute();

                if ($update_query) {

                    $user["phone"] = $phone;
                    $user["temp_password"] = $random_string;
                    return $user;

                } else {

                    return false;

                }

            }
        } else {

            return false;
        }


    }

    public function getHash($password)
    {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = password_hash($password . $salt, PASSWORD_DEFAULT);
        $hash = array(
            "salt" => $salt,
            "encrypted" => $encrypted
        );

        return $hash;

    }

    public function checkUserExist($phone)
    {

        $sql = 'SELECT * from users WHERE phone = ?';

        $query = $this->conn->prepare($sql);
        $query->bind_param("s", $phone);
        $query->execute();

        if ($query) {
            $query->store_result();
            $row_count = $query->num_rows;

            if ($row_count == 0) {

                return false;

            } else {

                return true;

            }
        } else {

            return false;
        }
    }

    public function resetPassword($phone, $code, $password)
    {


        $stmt = $this->conn->prepare("SELECT * FROM password_reset_request WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->bind_result($sno, $phone, $encrypted_password, $salt, $created_at);
        $user = $stmt->fetch();
        $stmt->close();
        // verifying user password
        $salt = $salt;
        $encrypted_password = $encrypted_password;
        $hash = $this->checkhashSSHA($salt, $password);
        if ($this->verifyHash($code . $salt, $encrypted_password)) {

            	
                return $this->changePassword($phone, $password);

            }

         else {

            return false;
        }

    }

    public function changePassword($phone, $password)
    {
        $this->conn->set_charset('utf8');

        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password


        $salt = $hash["salt"];
        $stmt = $this->conn->prepare("UPDATE users SET encrypted_password = ?, salt = ? WHERE phone = ?");
        $stmt->bind_param("sss", $encrypted_password, $salt, $phone);
        $query = $stmt->execute();
        $stmt->close();


        if ($query) {
		            return true;
                    echo "ok";

        } else {


            return false;

        }

    }

    public function verifyHash($password, $hash)
    {

        return password_verify($password, $hash);
    }

    public function createOtp($user_id, $otp)
    {

        // delete the old otp if exists
        $stmt = $this->conn->prepare("DELETE FROM sms_codes where userid = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();


        $stmt = $this->conn->prepare("INSERT INTO sms_codes(userid, code, status) values(?, ?, 0)");
        $stmt->bind_param("is", $user_id, $otp);

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }

    /**
     * Checking for duplicate user by mobile number
     * @param String $email email to check in db
     * @return boolean
     */
    private function isUserExists($mobile)
    {
        $stmt = $this->conn->prepare("SELECT userid from users WHERE phone = ? and status = 1");
        $stmt->bind_param("s", $mobile);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }

    public function activateUser($otp)
    {
        $stmt = $this->conn->prepare("SELECT u.userid,u.email, u.username,u.encrypted_password,u.salt,u.phone,u.address,u.area,u.userpic,u.datecreate,u.dateupdate,u.status,u.firebaseid,u.rate,u.count, u.apikey FROM users u, sms_codes WHERE sms_codes.code = ? AND sms_codes.userid = u.userid");
        $stmt->bind_param("s", $otp);

        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($userid, $email, $username, $encrypted_password, $salt, $phone, $address, $area, $userpic, $datecreate, $dateupdate, $status, $firebaseid, $rate, $count, $apikey);
            $user = $stmt->fetch();
            $stmt->close();
            if ($user) {
                $sql = "UPDATE users SET firebaseid='' WHERE firebaseid = '$firebaseid' and userid!= $userid";

                if ($this->conn->query($sql) === TRUE) {
                    // use is found
                    $this->activateUserStatus($userid);
                    $response["error"] = FALSE;
                    $response["user"]["userid"] = $userid;
                    $response["user"]["username"] = $username;
                    $response["user"]["email"] = $email;
                    $response["user"]["phone"] = $phone;
                    $response["user"]["address"] = $address;
                    $response["user"]["area"] = $area;
                    $response["user"]["userpic"] = $userpic;
                    $response["user"]["datecreate"] = $datecreate;
                    $response["user"]["dateupdate"] = $dateupdate;
                    $response["user"]["firebaseid"] = $firebaseid;
                    $response["user"]["rate"] = $rate;
                    $response["user"]["count"] = $count;
                    $response["user"]["apikey"] = $apikey;
                    echo json_encode($response, 256);
                                  

            } else {
                // user failed to store
                $response["error"] = TRUE;
                $response["error_msg"] = "Unknown error occurred in registration!";
                echo json_encode($response);
            }
   
        } else {
            return NULL;
        }

          }
		  else{
			     $response["error"] =true;
				       $response["error_msg"] = "Wrong verification code!";
		  }
	}
    public function activateUserStatus($user_id)
    {
        $stmt = $this->conn->prepare("UPDATE users set status = 1 where userid = ?");
        $stmt->bind_param("i", $user_id);

        $stmt->execute();

        $stmt = $this->conn->prepare("UPDATE sms_codes set status = 1 where userid = ?");
        $stmt->bind_param("i", $user_id);

        $stmt->execute();
    }

    /**
     * Generating random Unique MD5 String for user Api key
     */
    private function generateApiKey()
    {
        return md5(uniqid(rand(), true));
    }
	

}

?>
				