#!/usr/bin/php
<?php


error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);


require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

//include('example.txt');

function runClient($array){ // it could 1 var it 10 variables--- static -- now that it is accepting an array we can do it dynamicly


    $client = new rabbitMQClient("VtestRabbitMQ.ini", "sqlServer");

    $response = $client->send_request($array);
    //$response = $client->publish($request);

    echo "client received response: " . PHP_EOL;

    return $response;

}