<?php
//session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);
echo "<b>Results from Login.php</b><br><br>";

require_once("webRBMQ/rabbitClient.php"); // this needs to change to the rabbitClient file


function redirect ($message, $url)
{
    echo "<h1> $message </h1>";
    //user validation here put in the gatekeeper
    header("refresh: 4 ; url = $url");
    exit();
}

//require will fail if you

/**
 * Created by PhpStorm.
 * User: vkhor
 * Date: 4/26/2017
 * Time: 6:25 PM
 */

    $user_name = filter_input(INPUT_POST, 'user_name');
    $password = filter_input(INPUT_POST, 'password');
    $email = filter_input(INPUT_POST, 'email');
    $type = "insertUser";



    $user_data = array();
    $user_data['type'] = "$type";
    $user_data['username'] = "$user_name";
    $user_data['password'] = "$password";
    $user_data['email'] = "$email";


    $responce = runClient($user_data);

    if($responce === true){

        $message = "added user";
        $url = "login.html";

        redirect($message,$url);

    }else{

        $message = "Error Making username, Please Try Again";
        $url = "add_user.html";

        redirect($message,$url);
    }



?>


