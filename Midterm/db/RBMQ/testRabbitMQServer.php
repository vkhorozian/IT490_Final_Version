#!/usr/bin/php
<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);


require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('../dbconnect/rickys_scripts.php');


function requestProcessor($request)
{
    echo "received request!!!\n".PHP_EOL;
    //var_dump($request);  //this is the request

    //this var dump will take all of the data that is coming from the client side and dump it into the $request Variable
    // echo "this is the request" . $request;
    if(!isset($request['type']))
    {
        return "ERROR: unsupported message type";
    }

    switch ($request['type']) // use this switch to add all of the functions that we will need to be calling.
    {
        case "login":
            print_r($request);
            return doLogin($request); //userid,

        case "send_mario":
            print_r($request);
            return sendMario();

        case "getCoinData":
            print_r($request);
            return getCoinData();

        case "insertUser":
            print_r($request);
            return insertUser($request);

        case "validate_session":
            print_r($request);
            return doValidate($request['sessionId']);

        case "print":
            print_r($request);
            return printData($request);

        case "updateCoinsTable":
            print_r($request);
            return updateCoinsAPI($request);

        case "getUserWalletData":
            print_r($request);
            return getUserWalletData($request['userID']);

        case "buy_sell":
            print_r($request);
            return buy_sell($request['userID'], $request['chosen_coin'], $request['action'], $request['amount']);

        case "coinTable":
            return CoinTable();

        case "search":
            print_r($request);
            return search($request['symbol']);
    }

    return $request; //array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("allRBMQ.ini","apacheServer");

$server->process_requests('requestProcessor');

exit();

?>
