<?php
session_start();

function redirect ($message, $url)
{
    echo "<h1> $message </h1>";
    //user validation here put in the gatekeeper
    header("refresh: 7 ; url = $url");
    exit();
}


error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);


require_once("webRBMQ/rabbitClient.php");



$search = filter_input(INPUT_POST, 'search');

$rabbitArray = array();
$rabbitArray['type'] = 'search';
$rabbitArray['symbol'] = $search;




$array = runClient($rabbitArray);



?>

<!DOCTYPE html5>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="device-width, initial-scale =1">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="Crypto.css">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>

    <title> Homepage </title>

    <style>
        .body{
            background-color: #566573;
        }
        .vertical-center {
            min-height: 100%;
            min-height: 100vh;
            display: flex;
        }
        .page-header{
            text-align: center;
        }
        .container {
            background-color: #ABB2B9;
            width: 50%;
        }
        .h2 {
            font-color: white;
        }
        .jumbotron h1,
        .jumbotron .h1 {
            font-size: 2.1em;
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
    <div class="page-header">
        <h1> Coin View </h1>
    </div>
    <main>
        <section>
            <table class="table table-striped table-bordered table-hover table-condensed">
                <thead>
                <tr>
                    <center> <th>Coin Name</th> </center>
                    <center> <th>Price</th> </center>
                    <center> <th>Symbol</th> </center>
                    <center> <th>Volume</th> </center>
                    <center> <th>Percent Change</th> </center>
                    <center> <th>Trade</th> </center>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($array as $coin_array => $value) : ?>
                    <tr>
                        <td class="active"><center> <?php echo $value['symbol']; ?></center></td>
                        <td class="active"><center><?php echo $value['coinPrice']; ?></center></td>
                        <td class="active"><center><?php echo $value['symbol']; ?></center></td>
                        <td class="active"><center><?php echo number_format($value['24Volume'], 2); ?></center></td>
                        <td class="active"><center><?php echo number_format($value['changePct24Hour'], 2)."%"; ?></center></td>

                        <td class="active">
                            <form action="buy_sell.php" method="post">
                                <button type="submit" class="btn btn-block" name="coin_symbol"
                                        id="coin_symbol" value="<?php echo $value['symbol']; ?>"> Trade <?php echo $value['symbol']; ?></button>
                            </form>
                        </td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <form action="user_wallet.php" method="post">
            <button type="submit" class="btn btn-block" name="coin_symbol"
                    id="">Jump To Wallet</button>
        </form>

        <form method="post" action = "login.html" >
            <button type="submit" class="btn btn-block">Logout</button>
        </form>
    </main>

    <?php include_once 'view/footer.php'; ?>



