<?php

session_start();


error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);
//echo "<b>Results from coin_view.php</b><br><br>";

require_once("webRBMQ/rabbitClient.php");

//include_once ("function.php");
//include('simpleapi.php'); // this is giving me an array called cooin list when i call a function
//include_once('api.php');

/*
 * Created by PhpStorm.
 * User: vkhor
 * Date: 4/18/2017
 * Time: 4:59 PM
 */


///$pageKeys = ; // this is the cases that the API switch statement to decide what API key you need for this webpage
$type = 'getCoinData';
$pageKey = array();
$pageKey['type'] = "$type";


$array = runClient($pageKey);//this is what gets the data



//echo "\n" . "\n" . "\n" . "\n";
/*
for($i = 0 ; $i < 3 ; $i++){ // mid1
    //echo $kingarr[$i] . "\n";
    for ($j = 0; $j < 3 ; $j++){ //arr1
       // echo $kingarr[$j] . "\n";
        for ($z = 0 ; $z < 4 ; $z++) // 1
            echo $array[$i][$j][$z] . "\n";
    }
}


//////////////////////////////////////////////////

    <section>
    <?php if ($array->num_rows > 0): ?>
        <table>
            <tr>
                <th>Coin Symbol</th>
                <th>Price</th>
                <th>Volume</th>
                <th>Percent Change</th>
            </tr>

            <?php while($row = $array->fetch_assoc()) : ?>
            <tr>
                <td><?php $row[""] ?></td>
                <td><?php $row["Email"] ?></td>
                <td><?php $row["Email"] ?></td>
            </tr>

        </table>
    </section>


foreach ($array as $key => $value) :
    echo $key . $value['bitcoin'];
endforeach;



( [0] => Array (
        [symbol] => BTC
        [coinID] => 0
        [coinPrice] => 8227.32000000
        [lastUpdate] => 1521256154
        [24Volume] => 118609.84186979
        [openDay] => 8283.23000000
        [highDay] => 8325.23000000
        [lowDay] => 8111.77000000
        [changePct24Hour] => 2.17874313
        [totalVolume24H] => 484145.31620195
)

*/


//foreach ($coinList as $key => $value) :
 //   echo $coins['bitcoin'];
//endforeach;

//$stuff = "stufffasljkdfhalkjwsehf";
//$transactions = admin_get_trans_data();

//print_r($array);

$coin_name = array('Bitcoin', 'Etherium', 'Litecoin', 'BitcoinCash', 'Tron');

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
                                <center> <th>Compare</th> </center>
                                <center> <th>Coin Name</th> </center>
                                <center> <th>Price</th> </center>
                                <center> <th>Symbol</th> </center>
                                <center> <th>Volume</th> </center>
                                <center> <th>Percent Change</th> </center>
                                <center> <th>Trade</th> </center>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; foreach ($array as $coin_array => $value) : ?>
                                <tr>
                                    <td class="active"><form method="post" action="compare.php"><input name="compare" id="compare" value="<?php echo $value['symbol']?>" type="checkbox"><button type="submit" class="btn btn-block">Compare</button></form></td>
                                    <td class="active"><center><?php echo $coin_name[$count]; ?></center></td>
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
                                <?php $count++; endforeach; ?>
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

