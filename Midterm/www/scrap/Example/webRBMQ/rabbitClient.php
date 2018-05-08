<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');


//include('example.txt');

function runClient($array) // it could 1 var it 10 variables--- static -- now that it is accepting an array we can do it dynamicly

    {
        $client = new rabbitMQClient("allRBMQ.ini", "apacheServer");

        $responce = $client->send_request($array);

        //$response = $client->publish($request);

        echo "client received response: " . PHP_EOL;

        return $responce;
    }

    ?>