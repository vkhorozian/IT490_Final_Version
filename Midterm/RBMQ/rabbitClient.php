#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

//include('example.txt');

function runClient($array){ // it could 1 var it 10 variables--- static -- now that it is accepting an array we can do it dynamicly


    $client = new rabbitMQClient("VtestRabbitMQ.ini", "sqlServer");

    if (isset($argv[1])) {
        $msg = $argv[1];
    } else {
        $msg = "test message from the CLientRabbitMQ edited for testing purp"; //file_get_contents('example.txt');
        //$var = GetPriceList();
        //echo "got here";
    }

    $response = $client->send_request($array);
    //$response = $client->publish($request);

    echo "client received response: " . PHP_EOL;

    echo $argv[0] . " END" . PHP_EOL;

    return $response;
}