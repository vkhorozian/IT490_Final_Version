<?php
session_start();



error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);


//require_once("RBMQ/rabbitMQLib.inc");
require_once("webRBMQ/rabbitClient.php");
//require_once('RBMQ/rickys_scripts.php');
//require_once('RBMQ/path.inc');
//require_once('RBMQ/get_host_info.inc');
//require_once('RBMQ/allRBMQ.ini');


function redirect ($message, $url)
{
    echo "<h1> $message </h1>";
    //user validation here put in the gatekeeper
    header("refresh: 3 ; url = $url");
    exit();
}


$user_name = filter_input(INPUT_POST, 'user_name'); //echo $user_name;
$password = filter_input(INPUT_POST, 'password'); //echo $password ."\n" ;



//$client = runClient($type, $user_name, $password);

$type = "login";// echo "type = " . $type . "\n";
$user_data = array();
$user_data['type'] = "$type";
$user_data['username'] = "$user_name";
$user_data['password'] = "$password";

//var_dump($user_data);

$responce = runClient($user_data);

print_r($responce);

$_SESSION['userID'] = $responce['userID'];

if($responce['worked'] === true){

        $message = "This is the redirect, it worked coin view here i come";
        $url = "user_wallet.php";
        redirect($message,$url);
}else{

        $message = "you got a false as a return";
        $url = "login.html";
        redirect($message,$url);
}

/* try{
    $message = "This is the redirect";
    $url = "login.html";
    redirect($message,$url);
}catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}




function redirect ($message, $url)
{
    echo $message;
    header("refresh: 5 ; url = $url");
    exit();
}
*/

?>