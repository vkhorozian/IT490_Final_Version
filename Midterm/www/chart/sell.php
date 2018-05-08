<?php
session_start();

require_once("webRBMQ/rabbitClient.php");

function redirect ($message, $url)
{
    echo "<h1> $message </h1>";
    //user validation here put in the gatekeeper
    header("refresh: 4 ; url = $url");
    exit();
}



$amount = filter_input(INPUT_POST, 'sell_amount'); // int
$userID = $_SESSION['userID'];
$coinBeingSold = $_SESSION['sell'];
$coinBeingBought = $_SESSION['buy'];

echo $amount . ' '; echo $coinBeingBought  . ' '; echo $coinBeingSold  . ' ' . $userID;

redirect("yo", 'coin_view.php');








?>