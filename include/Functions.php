<?php

require_once 'DB_Functions.php';
require 'PHPMailer/PHPMailerAutoload.php';
require_once 'SpeedSMSAPI.php';

class Functions
{

    private $db;
    private $mail;

    public function __construct()
    {

        $this->db = new DB_Functions();
        $this->mail = new PHPMailer;

    }


    public function changePassword($email, $old_password, $new_password)
    {

        $db = $this->db;

        if (!empty($email) && !empty($old_password) && !empty($new_password)) {

            if (!$db->checkLogin($email, $old_password)) {

                $response["result"] = "failure";
                $response["message"] = 'Invalid Old Password';
                return json_encode($response);

            } else {


                $result = $db->changePassword($email, $new_password);

                if ($result) {

                    $response["result"] = "success";
                    $response["message"] = "Password Changed Successfully";
                    return json_encode($response);

                } else {

                    $response["result"] = "failure";
                    $response["message"] = 'Error Updating Password';
                    return json_encode($response);

                }

            }
        } else {

            return $this->getMsgParamNotEmpty();
        }

    }

    public function resetPasswordRequest($phone){

        $db = $this -> db;

        if ($db -> checkUserExist($phone)) {

            $result =  $db -> passwordResetRequest($phone);

            if(!$result){

                $response["result"] = "failure";
                $response["message"] = "Reset Password Failur4e";
                return json_encode($response);

            } else {
   
                $mail_result =  $this->sendSMS([$phone], "Hello! Welcome to Boss Sega. Your OPT is ". $result["temp_password"]);


             

                    $response["result"] = "success";
                    $response["message"] = "Check your mail for reset password code.";
                    return json_encode($response);

             
            }


        } else {

            $response["result"] = "failure";
            $response["message"] = "Email does not exist";
            return json_encode($response);

        }

    }


    public function resetPassword($phone, $code, $password)
    {

        $db = $this->db;

        if ($db->checkUserExist($phone)) {
            
            $result = $db->resetPassword($phone, $code, $password);

            if (!$result) {

                $response["result"] = "failure";
                $response["message"] = "Reset Password Failure2";
                return json_encode($result);

            } else {

                $response["result"] = "success";
                $response["message"] = "Password Changed Successfully";
                return json_encode($response);

            }


        } else {

            $response["result"] = "failure";
            $response["message"] = "Phone does not exist";
            return json_encode($response);

        }

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
        $mail->FromName = 'Boss Sega';
        $mail->addAddress($email, 'Boss Sega');

        $mail->addReplyTo('sega4revenge3@gmail.com', 'FreeMarket');

        $mail->WordWrap = 50;
        $mail->isHTML(true);

        $mail->Subject = 'Password Reset Request';
	$mail->Body = 'Hi,<br><br> Your password reset code is <b>' . $temp_password . '</b> . This code expires in 120 seconds. Enter this code within 120 seconds to reset your password.<br><br>Thanks,<br>Boss Sega<br><br><br><br><br><br>Chào,<br><br> Mã số yêu cầu lấy lại mật khẩu của bạn là <b>' . $temp_password . '</b> . Mã này có hiệu lực trong 120 giây. Nhập mã số này trong 120 giây để đặt lại mật khẩu.<br><br>Cảm ơn,<br>Sếp Sega.';

        if (!$mail->send()) {

            return $mail->ErrorInfo;

        } else {

            return true;

        }

    }

    public function sendPHPMail($email, $temp_password)
    {

        $subject = 'Password Reset Request';
        $message = 'Hi,\n\n Your password reset code is ' . $temp_password . ' . This code expires in 120 seconds. Enter this code within 120 seconds to reset your password.\n\nThanks,\nLearn2Crack.';
        $from = "your.email@example.com";
        $headers = "From:" . $from;

        return mail($email, $subject, $message, $headers);

    }


    public function isEmailValid($email)
    {

        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function getMsgParamNotEmpty()
    {


        $response["result"] = "failure";
        $response["message"] = "Parameters should not be empty !";
        return json_encode($response);

    }

    public function getMsgInvalidParam()
    {

        $response["result"] = "failure";
        $response["message"] = "Invalid Parameters";
        return json_encode($response);

    }

    public function getMsgInvalidEmail()
    {

        $response["result"] = "failure";
        $response["message"] = "Invalid Email";
        return json_encode($response);

    }

}
