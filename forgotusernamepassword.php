<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
// require 'PHPMailer-master/PHPMailerAutoload.php';
include 'model.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (isset($_REQUEST['recover-submit'])) {
    $email = $_REQUEST['email'];
    $productlist = New Product();
    $username = $productlist->forgotUserPass($email);
    $array = explode("/", $username);

    if ($array[0] == 'true') {
        include 'PHPMailer/PHPMailerAutoload.php';
        try {
            $mail = new PHPMailer();

            //Server settings
            $mail->isSMTP();                                          // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                 // Enable SMTP authentication
            $mail->Username   = 'example@gmail.com';         // SMTP username
            $mail->Password   = '<password>';                      // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          // Enable implicit TLS encryption
            $mail->Port       = 465;                                  // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('example@gmail.com', 'Product Inventory');
            $mail->addReplyTo('example@gmail.com', 'Adminstrator');

            $mail->addAddress($email);                                // Add a recipient

            //Content
            $mail->isHTML(true);                                      // Set email format to HTML
            $mail->Subject = 'Forgot Product Inventory Username/Password';
            $mail->Body = "Your Username is <b>" . $array[1] . "</b> and Password is <b>" . $array[2] . "</b>";

            if (! $mail->send()) {
                echo "<script>alert('Mail could not be sent');</script>";
            } else {
                echo "<script>alert('Mail Sent Successfully!');</script>";
                header('Location: index.php');
            }
        } catch (Exception $e) {
            echo "<script>alert('Mail could not be sent');</script>";
            echo "<script>alert('Message could not be sent. Mailer Error: " . $mail->ErrorInfo . "');</script>";
        }
    } else {
        echo "<script>alert('Invalid Email! Please Enter Valid Email Address');</script>";
    }
}
?>
<html>
    <head>
        <title>Forgot Password</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <!------ Include the above in your HEAD tag ---------->

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="form-gap"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="text-center">
                                <h3><i class="fa fa-lock fa-4x"></i></h3>
                                <h2 class="text-center">Forgot Password?</h2>
                                <p>You can reset your password here.</p>
                                <div class="panel-body">

                                    <form id="register-form" role="form" autocomplete="off" class="form" method="post">

                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                                <input id="email" name="email" placeholder="email address" class="form-control"  type="email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Send Password" type="submit">
                                        </div>

                                        <input type="hidden" class="hide" name="token" id="token" value=""> 
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>


