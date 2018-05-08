<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);

session_start();


require_once('webRBMQ/rabbitClient.php');


$type = 'getUserWalletData';
$userID = $_SESSION['userID'];



$rabbitArr = array();
$rabbitArr['type'] = "$type";
$rabbitArr['userID'] = "$userID";

$wallet_array = runClient($rabbitArr);//this is what gets the data

//wallet array has all of my values

foreach ($wallet_array as $coin_array => $value) :

$_SESSION['USD_BALANCE'] = $value['balance'];
$_SESSION['BTC_BALANCE'] = $value['bitcoinBalance'];
$_SESSION['BTH_BALANCE'] = $value['bitcoincashBalance'];
$_SESSION['ETH_BALANCE'] = $value['etheriumBalance'];
$_SESSION['LTC_BALANCE'] = $value['litecoinBalance'];
$_SESSION['TRX_BALANCE'] = $value['tronBalance'];

endforeach;




//echo $wallet_array['balance'];

/*
 *Array ( [0] =>
 *                  Array (
 *                          [userID] => 47
 *                          [balance] => 100000.00
 *                          [bitcoinBalance] => 0.00000000
 *                          [etheriumBalance] => 0.00000000
 *                          [litecoinBalance] => 0.00000000
 *                          [bitcoincashBalance] => 0.00000000
 *                          [tronBalance] => 0.00000000
 *                          )
 *       )

 */

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
        .jumbotron {
            background-color: #566573;
        }
        .body{
            background-color: #566573;
        }
        .vertical-center {
            min-height: 100%;
            min-height: 100%;
            display: flex;
        }
        .fontForme{
            text-align: center;
        }
        .td{
            text-align: center;
        }
        .page-header{
            text-align: center;
        }
        .form-control {
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

<div class="wallet_view">
    <?php include_once 'view/wallet_view.php'?>
</div>

<div class="jumbotron vertical-center">
    <div class="container">

        <div class="page-header">
            <h1> User Wallet </h1>
        </div>
        <main>
            <section>
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>USD Balance</th>
                        <th>Bitcoin Balance</th>
                        <th>Bitcoin Cash Balance</th>
                        <th>Etherium Balance</th>
                        <th>Litecoin Balance</th>
                        <th>Tron Balance</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($wallet_array as $coin_array => $value) : ?>
                        <tr>

                            <td><center><?php echo $value['balance']; ?></center></td>
                            <td><center><?php echo $value['bitcoinBalance']; ?></center></td>
                            <td><center><?php echo $value['bitcoincashBalance']; ?></center></td>
                            <td><center><?php echo $value['etheriumBalance']; ?></center></td>
                            <td><center><?php echo $value['litecoinBalance']; ?></center></td>
                            <td><center><?php echo $value['tronBalance']; ?></center></td>


                            <td>

                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <form method="post" action = "coin_view.php" >
                <button type="submit" class="btn btn-block">Back To Coin View</button>
            </form>
        </main>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajas/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script scr="js/bootstrap.min.js"></script>

</html>
