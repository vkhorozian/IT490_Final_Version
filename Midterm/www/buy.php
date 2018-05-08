<?php
session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);


require_once("webRBMQ/rabbitClient.php");

function redirect ($message, $url)
{
    echo "<h1> $message </h1>";
    //user validation here put in the gatekeeper
    header("refresh: 4 ; url = $url");
    exit();
}



$amount = filter_input(INPUT_POST, 'amount'); //int

$chosen_coin = $_SESSION['symbol'];


$action = $_POST['action'];


//$action = $_SESSION['action']; //this could be something from the forms we press hidden = <?php $_SESSION



$buy_sell_array = array();
$buy_sell_array['type'] = 'buy_sell'; //rabbitMQ's server swtich statement is going to use to choose the right function is rickys scripts
$buy_sell_array['action'] = $action; // the type goes into the top of the switch statement and selects buy or sell ( buy or sell )
$buy_sell_array['chosen_coin'] = $chosen_coin; // the coin we are going to buy or sell // a swithc statement that accepts the type (BTC ETC TRX ect....)
$buy_sell_array['userID'] = $_SESSION['userID']; // for the wallet selection
$buy_sell_array['amount'] = $amount;  // how much we are buying and selling


print_r($buy_sell_array);


//$word = print_r($buy_sell_array);


$result_message = runClient($buy_sell_array);


echo $result_message;

redirect("WORD UP \n", 'user_wallet.php');






?>