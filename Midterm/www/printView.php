<?php

session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);


require_once("webRBMQ/rabbitClient.php");

function redirect ($message, $url)
{
    echo "<h1> $message </h1>";
    //user validation here put in the gatekeeper
    header("refresh: 7 ; url = $url");
    exit();
}


$buy_sell_array = array();
$buy_sell_array['type'] = 'getUserWalletData';
$buy_sell_array['userID'] = $_SESSION['userID'];


$result_message = runClient($buy_sell_array);


print_r($result_message);


/*
 * Array (
 *      [0] => Array ( [coinPrice] => 9408.47000000
 *                      [0] => 9408.47000000 )
 *
 *      [1] => Array ( [coinPrice] => 0.08092000
 *                      [0] => 0.08092000 )
 *
 *      [2] => Array ( [coinPrice] => 0.01769000
 *                      [0] => 0.01769000 )
 *
 *      [3] => Array ( [coinPrice] => 0.17790000
 *                      [0] => 0.17790000 )
 *
 *      [4] => Array ( [coinPrice] => 0.00000889
 *                      [0] => 0.00000889 )
 * )
 *
 *
 * Array (
 *      [0] => Array ( [userID] => 47
 *                      [balance] => 100000.00
 *                      [bitcoinBalance] => 10.00000000
 *                      [etheriumBalance] => 0.00000000
 *                      [litecoinBalance] => 0.00000000
 *                      [bitcoincashBalance] => 0.00000000
 *                      [tronBalance] => 0.00000000
 *                   )
 *       )
 *
 *
 * Array (
 *      [0] => Array ( [coinPrice] => 9408.47000000 )
 *      [1] => Array ( [coinPrice] => 0.08092000 )
 *      [2] => Array ( [coinPrice] => 0.01769000 )
 *      [3] => Array ( [coinPrice] => 0.17790000 )
 *      [4] => Array ( [coinPrice] => 0.00000889 )
 * )
 *


*/
?>