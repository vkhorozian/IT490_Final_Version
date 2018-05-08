<?php


function redirect ($message, $url)
{
    echo $message;
    header("refresh: 1 ; url = $url");
    exit();
}


?>