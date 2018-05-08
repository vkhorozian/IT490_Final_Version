<?php
/*
 * This is the structure i would like you to use for creating your DB stuff.
 * It will keep everything organized and clean.
 * Also it will allow you to work with far fewer files.
 */


//include('../error_log.php');
//require_once('connect.php');//Makes sql connection
require_once ('../RBMQ/rabbitClient.php');


//First include all the files you need.
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function doLogin($array)
{
    $username = $array['username'];
    $password = $array['password'];

    {
        if($username != "test" and $password != "test") {
            return false;
        } else {
            return true;
        }
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function insertUser($array)

{
    $username = $array['username'];
    $password = $array['password'];
    $email = ['email'];


    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    //Escape characters
    $username = mysqli_real_escape_string($conn, $username);
    $password_hash = mysqli_real_escape_string($conn, $password_hash);
    $email = mysqli_real_escape_string($conn, $email);

    while(true){//Randomize the userID

        $userID=(mt_rand(1,100));
        $checkTables = "SELECT userID from authTable where userID = '$userID'";
        $query = mysqli_query($conn, $checkTables);
        $numrows = mysqli_num_rows($query);

        if($numrows == 0){
            break;
        }
    }

    //Check for valid username registration
    $checkUsernameQuery = "SELECT * from authTable where userID = '$username'";
    $query = mysqli_query($conn, $checkUsernameQuery);
    $numrows = mysqli_num_rows($query);
    if($numrows > 0){//Need to send a message to the web server saying this shit not gunna happen
        $msg = "usernameError";
        return $msg;
    }

    $insertQuery = "INSERT INTO authTable (userID, username, password, email, lastLogin) 
    VALUES ('$userID','$username', '$password_hash', '$email', '0')";

    $walletDefaultQuery = "INSERT INTO userWallet(walletID, balance, bitCoinBalance, etheriumBalance, litecoinBalance, bitcoincashBalance, tronBalance)
    VALUES('$userID','100000','0','0','0','0','0')";

    $queryVal = mysqli_query($conn, $insertQuery);
    if ( $queryVal === false ) {
        echo 'error';
    }

    $queryVal2 = mysqli_query($conn, $walletDefaultQuery);
    if ( $queryVal2 === false ) {
        echo 'error';
    }

    return "success";

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getCoinData()  // this is suposed to send the coin data to me i think back to the user so this is the function i call on the RBMQ
    //RBMQ server for chipman
{
    makeCoinTable(); // this should run a function which will trigger the make coin table script

    ini_set('display_errors', 1);

    $retrieveCoinData = "SELECT * from coinTable";


    if ($result = mysqli_query($conn, $retrieveCoinData)) {

        /* fetch associative array */
        $count = 0;

        //while ($all = mysqli_fetch_all($result)) {
        $all = mysqli_fetch_all($result, MYSQLI_ASSOC);
        //print_r($all);

        //}

        /* free result set */
        mysqli_free_result($result);
        return $all;
    }
    mysqli_close($conn);
}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function makeCoinTable() // this is supposed to get an array from the api so i guess i need to run client here

{
    $coinArr = updateCoinData();

    ini_set('display_errors', 1);

    for ($i = 0; $i < 5; $i++){

        switch ($i) {

            case 0:
                $coin = $coinArr['bitcoin'];
                break;
            case 1:
                $coin = $coinArr['etherium'];
                break;
            case 2:
                $coin = $coinArr['litecoin'];
                break;
            case 3:
                $coin = $coinArr['bitcoincash'];
                break;
            case 4:
                $coin = $coinArr['tron'];
                break;
        }


        $symbol = $coin['FROMSYMBOL'];
        $coinID = $coin['COINID'];
        $coinPrice = $coin['PRICE'];
        $lastUpdate = $coin['LASTUPDATE'];
        $volume24 = $coin['VOLUME24HOUR'];
        $openDay = $coin['OPENDAY'];
        $highDay = $coin['HIGHDAY'];
        $lowDay = $coin['LOWDAY'];
        $changePCT24Hour = $coin['CHANGEPCT24HOUR'];
        $totalVolume24H = $coin['TOTALVOLUME24H'];

        //IF YOU WOULD LIKE TO CHECK THE VALUES//
        /*
        echo"***********************";
        echo(PHP_EOL);
        echo($symbol);
        echo(PHP_EOL);
        echo($coinID);
        echo(PHP_EOL);
        echo($coinPrice);
        echo(PHP_EOL);
        echo($lastUpdate);
        echo(PHP_EOL);
        echo($volume24);
        echo(PHP_EOL);
        echo($openDay);
        echo(PHP_EOL);
        echo($highDay);
        echo(PHP_EOL);
        echo($lowDay);
        echo(PHP_EOL);
        echo($changePCT24Hour);
        echo(PHP_EOL);
        echo($totalVolume24H);
        echo(PHP_EOL);
        */

        $insertCoinData = "INSERT INTO coinTable (symbol, coinID, coinPrice, lastUpdate, 24Volume, openDay, highDay, lowDay, changePct24Hour, totalVolume24H) VALUES (".$symbol.",'".$coinID."','".$coinPrice."','".$lastUpdate."','".$volume24."','".$openDay."','".$highDay."','".$lowDay."','".$changePCT24Hour."','".$totalVolume24H."')
	ON DUPLICATE KEY UPDATE
	coinPrice='".$coinPrice."', lastUpdate='".$lastUpdate."', 24Volume='".$volume24."', openDay='".$openDay."', highDay='".$highDay."', lowDay='".$lowDay."', changePct24Hour='".$changePCT24Hour."', totalVolume24H='".$totalVolume24H."'";

        //mysqli_query($conn,$insertCoinData) or die (mysqli_error($conn));

        if (!$conn->query($insertCoinData)) {
            echo "INSERT FAILED: (" . $conn->errno . ") " . $conn->error;
        }
    }


}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function updateCoinData() // this should actually run the client and grab the data from the api so i need something on dozas
    //RBMQ server that is going to accept an array of $array and use type runAPI

{

    $coin_criteria = array();

    $coin_criteria['type'] = "runAPI";
    $coin_criteria['$pageKey'] = "homepage";


    $updatedCoinArray = runClient($coin_criteria);

    ini_set('display_errors', 1);

    return $updatedCoinArray;

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function buyCoins($walletID, $crypto1, $crypto2, $amount){






}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function sellCoins($walletID, $crypto1, $crypto2, $amount){






}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



?>