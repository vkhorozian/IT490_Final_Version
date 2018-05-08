<?php
session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);

//require_once('db/rickys_scripts.php');

$symbol_name = filter_input(INPUT_POST, 'coin_symbol');

$cleansed_symbol = substr($symbol_name, -3);

$_SESSION["symbol"] = $cleansed_symbol; // this is for the buy sell pages it saves the shit to the session


//<?php echo $symbol_name

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="device-width, initial-scale =1">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="Crypto.css">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>The Buy/Sell Page</title>
    <script src="chart/angular.js"></script>
    <script src="chart/jquery.js"></script>


    <style>
        .body{
            background-color: #566573;
        }
        .jumbotron {
            background-color: #566573;
        }
        .vertical-center {
            min-height: fit-content;
            min-height: 100vh;
            display: flex;
        }
        .form-control {
            text-align: center;
        }
        .container {
            background-color: #ABB2B9 ;
            width: 30%;
        }
        .h2 {
            font-color: white;
        }
        .jumbotron h1,
        .jumbotron .h1 {
            font-size: 4.5em;
            font-family: 'Pacifico', cursive;
        }
        .jumbotron p,
        .jumbotron .p {
            font-size: 0.8em;
        }
        .signup-session h2,
        .signup-session .h2 {
            font-color: rgb(0,0,0);
            font-family: 'Pacifico', cursive;
            text-align: center;
            line-height: 1.13;
            height: 34px;
            font-size: 100px;
        }
        .wallet_view {
            position:absolute;
            width:19%;
            height: 24%;
            background-color: #ABB2B9;
            left: 77%;
            top: 30%;
        }
    </style>


</head>

<body class="body">

<div class="navbar">
    <?php include_once 'view/navbar.php'?>
</div>
<!--
<div class="wallet_view">
    <?php //include_once 'view/wallet_view.php'?>
</div>
-->
<div class="container">
    <div class="col-md-5">>
        <div class="widget-preview-content" style="width: 320px; min-height: 320px;" ng-class="{'preview-feed-widget': forms.type == 5, 'preview-header-widget': forms.type == 6 || forms.type == 7 || forms.type == 8 || forms.type == 9 || forms.type == 11 || forms.type == 12}" id="widget-preview-content"><script async="" type="text/javascript" id="ccc-ws-999">(function() {
                    baseUrl = "https://widgets.cryptocompare.com/"; // allows use of the widget
                    var scripts = document.getElementsByTagName("script");
                    var embedder = document.getElementById('ccc-ws-999');

                    (function (){
                        var appName = encodeURIComponent(window.location.hostname);
                        if(appName==""){appName="local";}
                        var s = document.createElement('script');
                        s.type = 'text/javascript';

                        var theUrl = baseUrl+'serve/v1/coin/chart?fsym=<?php echo $cleansed_symbol?>&tsym=USD';// api call
                        s.src = theUrl + ( theUrl.indexOf("?") >= 0 ? "&" : "?") + 'app=' + appName;
                        embedder.parentNode.appendChild(s);
                    })();
                })();
            </script>
        </div>
    </div>

    <form  method="post" action = "buy.php">
        <div class="form-group">
            <div class="cols-lg-6">
                <input class=form-control type=text name="amount" id="amount" required="required" hidden="<?php $action = 'sell'; ?>" placeholder="Sell Amount">
                <input type="hidden" name="action" value="<?php echo $action = 'sell'; ?>">
            </div>
            <button type="submit" class="btn btn-info btn-block login-button">Sell <?php echo $cleansed_symbol?></button>
        </div>
    </form>

    <form  method="post" action = "buy.php">
        <div class="form-group">
            <div class="cols-lg-6">
                <input class=form-control type=text name="amount" id="amount" required="required" hidden="<?php $action = "buy"; ?>" placeholder="Buy Amount" >
                <input type="hidden" name="action" value="<?php echo $action = 'buy'; ?>">
            </div>
            <button type="submit" class="btn btn-info btn-block login-button">Buy <?php echo $cleansed_symbol?></button>
        </div>
    </form>

    <form method="post" action = "coin_view.php" >
        <button type="submit" class="btn btn-block">Back To Coin View</button>
    </form>

    <?php include_once 'view/footer.php'; ?>
