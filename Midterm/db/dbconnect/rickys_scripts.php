<?php

//Richard Chipman & Varoujan Khorozian
//IT490
//Professor Kehoe
//Group: Crypto Bros


error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);

//echo "<b>Results from Login.php</b><br><br>";
//Database Scripts
//include('../error_log.php');
//Call connectToDB() to make connection
//require_once ('../testRabbitMQClient.php');

require_once('connect.inc');
require_once('simpleapi.php');
require_once ('database.php');


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function redirect ($message, $url)
{
    echo "<h1> $message </h1>";
    //user validation here put in the gatekeeper
    header("refresh: 2 ; url = $url");
    exit();
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Used to validate username and password entered at the front end

function doLogin($array){



    $resultsFound = false;
    $conn = connectToDb();
    $username = mysqli_real_escape_string($conn,$array['username']);
    $password = mysqli_real_escape_string ($conn,$array['password']);
    $currentEpochTime = time ();
    $validationQuery = "SELECT userID, username, password from authTable WHERE username = '".$username."'";
    $updateLastLogin = "update authTable SET lastLogin = '" . $currentEpochTime . "'";

    if($result = mysqli_query($conn, $validationQuery))
    {
        $all = mysqli_fetch_all($result);

        $all = array_filter($all);

        $ass = !empty($all);


        if($ass){	//True if a set of data is found
            $databaseUserID = ($all[0][0]);
            $databaseUsername = ($all[0][1]);
            $databasePasswordHash = ($all[0][2]);
            $resultsFound = true;
        }
        if($resultsFound){//Will not execute anything if the query returns nothing

            if($username == $databaseUsername)//The username is in the database
            {
                echo("Username in Database");

                if(password_verify($password, $databasePasswordHash))//Login will be successful
                {

                    echo("valid password");
                    mysqli_query($conn, $updateLastLogin);

                    //send an array with the id and something that is true so i can redirect
                    $array = array();
                    $array['userID'] = "$databaseUserID";
                    $array['worked'] = true;

                    return $array;   //Gives ID to the front end for session validation

                }

                else{
                    return (" however password is not valid");
                }
            }
            else {
                return ("Not a valid username");
            }
        }
    }
    mysqli_close($conn);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//This function creates a user and also creates an wallet with the same ID for them
function insertUser($array)//must receive username password and email in array

{
    $conn = connectToDb();
    $username = $array['username'];
    $password = $array['password'];
    $email = $array['email'];


    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    //Escape characters
    $username = mysqli_real_escape_string($conn, $username);
    $password_hash = mysqli_real_escape_string($conn, $password_hash);
    $email = mysqli_real_escape_string($conn, $email);

    //Check for valid username registration
    $checkUsernameQuery = "SELECT * from authTable where username = '$username'";
    $query = mysqli_query($conn, $checkUsernameQuery);
    $numrows = mysqli_num_rows($query);
    if($numrows > 0){//Need to send a message to the web server that this is not gunna happen
        $msg = "usernameError";
        return false;
    }

    while(true){//Randomize the userID

        $userID=(mt_rand(1,100));
        $checkTables = "SELECT userID from authTable where userID = '$userID'";
        $query = mysqli_query($conn, $checkTables);
        $numrows = mysqli_num_rows($query);

        if($numrows == 0){
            break;
        }
    }

    $insertQuery = "INSERT INTO authTable (userID, username, password, email, lastLogin) 
    VALUES ('$userID','$username', '$password_hash', '$email', '0')";

    $walletDefaultQuery = "INSERT INTO userWallet(userID, balance, bitCoinBalance, etheriumBalance, litecoinBalance, bitcoincashBalance, tronBalance)
    VALUES('$userID','100000.00000000','0.00000000','0.00000000','0.00000000','0.00000000','0.00000000')";

    $queryVal = mysqli_query($conn, $insertQuery);
    if ( $queryVal === false ) {
        echo 'error';
    }

    $queryVal2 = mysqli_query($conn, $walletDefaultQuery);
    if ( $queryVal2 === false ) {
        echo 'error';
    }
    mysqli_close($conn);
    return true;

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getCoinData()  // this is suposed to send the coin data to me i think back to the user so this is the function i call on the RBMQ
    //RBMQ server for chipman
{
    makeCoinTable(); // this should run a function which will trigger the make coin table script
    $conn = connectToDb();
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//this is going to be the function that will SELECT * FROM userWallets where "$_SESSION["userID"]" = userID; and then send back that array to the webserver to be displayed.


function getUserWalletData($userID){

    global $db;
    $query = "SELECT * FROM userWallet WHERE userID = '$userID'";
    $statement = $db->prepare($query);
    $statement->execute();
    $walletArray = $statement->fetchAll(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    return $walletArray;

}






/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function makeCoinTable() // this is supposed to get an array from the api so i guess i need to run client here //$PageKey for RBMQ from Server

{
    //$coinArr = updateCoinData();  //this is commented out rn but this is needed for the site to work the way it is supposed to
    $conn = ConnectToDb();
    $coinArr = runAPI("homepage"); //this is the one i set up rn just so you can use the run api //$PageKey for RBMQ from Server

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

function CoinTable(){
    global $db;
    $query = "SELECT coinPrice FROM coinTable"; //we always need BTC -> usd and ALT
    $statement = $db->prepare($query);
    $statement->execute();
    $coinPriceArray = $statement->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC
    $statement->closeCursor();

    return $coinPriceArray;
}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function search($symbol){

    global $db;
    $query = "SELECT * FROM coinTable WHERE symbol = '$symbol';"; //we always need BTC -> usd and ALT
    $statement = $db->prepare($query);
    $statement->execute();
    $searchArray = $statement->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC
    $statement->closeCursor();

    return $searchArray;

}




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function buy_sell($userID, $chosen_coin, $action, $amount){ //action is for buy or sell in switch

    //BUY
    //ALT with -> BTC
    //USD with -> BTC
    //SELL
    //ALT for -> BTC
    //BTC for -> USD
    //


    $user_wallet_array = getUserWalletData($userID);


        $USD_BALANCE = $user_wallet_array[0]['balance'];
        $BTC_BALANCE = $user_wallet_array[0]['bitcoinBalance'];
        $BCH_BALANCE = $user_wallet_array[0]['bitcoincashBalance'];
        $ETH_BALANCE = $user_wallet_array[0]['etheriumBalance'];
        $LTC_BALANCE = $user_wallet_array[0]['litecoinBalance'];
        $TRX_BALANCE = $user_wallet_array[0]['tronBalance'];



//----------------------------------------------------GETTING COIN VALUES-----------------------------------------------------------------------------

    global $db;
    $query = "SELECT coinPrice FROM coinTable"; //we always need BTC -> usd and ALT
    $statement = $db->prepare($query);
    $statement->execute();
    $coinPriceArray = $statement->fetchAll(); //PDO::FETCH_ASSOC
    $statement->closeCursor();



    $BTC_Price = $coinPriceArray[0]['coinPrice'];
    $ETH_Price = $coinPriceArray[1]['coinPrice'];
    $LTC_Price = $coinPriceArray[2]['coinPrice'];
    $BCH_Price = $coinPriceArray[3]['coinPrice'];
    $TRX_Price = $coinPriceArray[4]['coinPrice'];

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    switch($action){

        //if you ever select an ALT you will get BTC as a return
        //if you ever select BTC you will either get USD or ALT

        case "buy":

            if ($chosen_coin == 'BTC'){

                $worthInUSD = $BTC_Price * $amount; // how much usd is going to be subtracted from USD_BALANCE

                if($USD_BALANCE > $worthInUSD){

                    $newUSDbalance = $USD_BALANCE - $worthInUSD; // calcuate how much coin or usd is to be spent

                    //make purchese
                    //queary new balance

                    $newBTCbalance = $BTC_BALANCE + $amount; // new amount of BTC

                    global $db;
                    $query = "UPDATE userWallet SET bitcoinBalance = '$newBTCbalance' WHERE userID = '$userID'";
                    $statement = $db->prepare($query);
                    $statement->execute();
                    $statement->closeCursor();

                    $query2 = "UPDATE userWallet SET balance = '$newUSDbalance' WHERE userID = '$userID'";
                    $statement2 = $db->prepare($query2);
                    $statement2->execute();
                    $statement2->closeCursor();

                    return "sucessfully added " . $chosen_coin . " worth " . $amount;

                }else{

                    return "insuffecient funds";

                }
                //then buy btc with usd // add btc balance and usd balance
            }else{


                switch($chosen_coin){

                    case "ETH":

                        $worthOfETHinBTC = $ETH_Price * $amount; // how much usd is going to be subtracted from USD_BALANCE

                        if($BTC_BALANCE > $worthOfETHinBTC){

                            $newBTCbalance = $BTC_BALANCE - $worthOfETHinBTC; // calcuate how much coin or usd is to be spent

                            //make purchese
                            //queary new balance

                            $newETHbalance = $ETH_BALANCE + $amount; // new amount of BTC

                            global $db;
                            $query = "UPDATE userWallet SET etheriumBalance = '$newETHbalance' WHERE userID = '$userID'";
                            $statement = $db->prepare($query);
                            $statement->execute();
                            $statement->closeCursor();

                            $query2 = "UPDATE userWallet SET bitcoinBalance = '$newBTCbalance' WHERE userID = '$userID'";
                            $statement2 = $db->prepare($query2);
                            $statement2->execute();
                            $statement2->closeCursor();

                            return "sucessfully added " . $chosen_coin . " worth " . $amount;

                        }else{

                            return "insuffecient funds";

                        }

                    case "LTC":

                        $worthOfLTCinBTC = $LTC_Price * $amount; // how much usd is going to be subtracted from USD_BALANCE

                        if($BTC_BALANCE > $worthOfLTCinBTC){

                            $newBTCbalance = $BTC_BALANCE - $worthOfLTCinBTC; // calcuate how much coin or usd is to be spent

                            //make purchese
                            //queary new balance

                            $newLTCbalance = $LTC_BALANCE + $amount; // new amount of BTC

                            global $db;
                            $query = "UPDATE userWallet SET litecoinBalance = '$newLTCbalance' WHERE userID = '$userID'";
                            $statement = $db->prepare($query);
                            $statement->execute();
                            $statement->closeCursor();

                            $query2 = "UPDATE userWallet SET bitcoinBalance = '$newBTCbalance' WHERE userID = '$userID'";
                            $statement2 = $db->prepare($query2);
                            $statement2->execute();
                            $statement2->closeCursor();

                            return "sucessfully added " . $chosen_coin . " worth " . $amount;

                        }else{

                            return "insuffecient funds";

                        }

                    case "TRX":

                        $worthOfTRXinBTC = $TRX_Price * $amount; // how much usd is going to be subtracted from USD_BALANCE

                        if($BTC_BALANCE > $worthOfTRXinBTC){

                            $newBTCbalance = $BTC_BALANCE - $worthOfTRXinBTC; // calcuate how much coin or usd is to be spent

                            //make purchese
                            //queary new balance

                            $newTRXbalance = $TRX_BALANCE + $amount; // new amount of BTC

                            global $db;
                            $query = "UPDATE userWallet SET tronBalance = '$newTRXbalance' WHERE userID = '$userID'";
                            $statement = $db->prepare($query);
                            $statement->execute();
                            $statement->closeCursor();

                            $query2 = "UPDATE userWallet SET bitcoinBalance = '$newBTCbalance' WHERE userID = '$userID'";
                            $statement2 = $db->prepare($query2);
                            $statement2->execute();
                            $statement2->closeCursor();

                            return "sucessfully added " . $chosen_coin . " worth " . $amount;

                        }else{

                            return "insuffecient funds";

                        }

                    case "BCH":

                        $worthOfBCHinBTC = $BCH_Price * $amount; // how much usd is going to be subtracted from USD_BALANCE

                        if($BTC_BALANCE > $worthOfBCHinBTC){

                            $newBTCbalance = $BTC_BALANCE - $worthOfBCHinBTC; // calcuate how much coin or usd is to be spent

                            //make purchese
                            //queary new balance

                            $newBCHbalance = $BCH_BALANCE + $amount; // new amount of BTC

                            global $db;
                            $query = "UPDATE userWallet SET bitcoincashBalance = '$newBCHbalance' WHERE userID = '$userID'";
                            $statement = $db->prepare($query);
                            $statement->execute();
                            $statement->closeCursor();

                            $query2 = "UPDATE userWallet SET bitcoinBalance = '$newBTCbalance' WHERE userID = '$userID'";
                            $statement2 = $db->prepare($query2);
                            $statement2->execute();
                            $statement2->closeCursor();

                            return "sucessfully added " . $chosen_coin . " worth " . $amount;

                        }else{

                            return "insuffecient funds";

                        }


                }


                //then buy alt with btc // add alt balanace and btc balance
            }


        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        case "sell":

                    if ($chosen_coin == 'BTC'){

                        $worthInUSD = $BTC_Price * $amount; // how much usd is going to be subtracted from USD_BALANCE

                        if($BTC_BALANCE >= $amount){

                            $newUSDbalance = $USD_BALANCE + $worthInUSD; // calcuate how much coin or usd is to be spent

                            //make purchese
                            //queary new balance

                            $newBTCbalance = $BTC_BALANCE - $amount; // new amount of BTC

                            global $db;
                            $query = "UPDATE userWallet SET bitcoinBalance = '$newBTCbalance' WHERE userID = '$userID'";
                            $statement = $db->prepare($query);
                            $statement->execute();
                            $statement->closeCursor();

                            $query2 = "UPDATE userWallet SET balance = '$newUSDbalance' WHERE userID = '$userID'";
                            $statement2 = $db->prepare($query2);
                            $statement2->execute();
                            $statement2->closeCursor();

                            return "sucessfully added " . $chosen_coin . " worth " . $amount;

                        }else{

                            return "insuffecient funds";

                        }

                        //then sell btc for usd //add btc balance and usd balance

                    }else{

                        switch($chosen_coin){

                            case "ETH":  //selling ether

                                $worthOfETHinBTC = $ETH_Price * $amount;

                                if($ETH_BALANCE >= $amount){

                                    $newBTCbalance = $BTC_BALANCE + $worthOfETHinBTC; // calcuate how much coin or usd is to be spent

                                    //make purchese
                                    //queary new balance

                                    $newETHbalance = $ETH_BALANCE - $amount; // new amount of BTC

                                    global $db;
                                    $query = "UPDATE userWallet SET etheriumBalance = '$newETHbalance' WHERE userID = '$userID'";
                                    $statement = $db->prepare($query);
                                    $statement->execute();
                                    $statement->closeCursor();

                                    $query2 = "UPDATE userWallet SET bitcoinBalance = '$newBTCbalance' WHERE userID = '$userID'";
                                    $statement2 = $db->prepare($query2);
                                    $statement2->execute();
                                    $statement2->closeCursor();

                                    return "sucessfully added " . $chosen_coin . " worth " . $amount;

                                }else{

                                    return "insuffecient funds";

                                }

                            case "LTC":

                                $worthOfLTCinBTC = $LTC_Price * $amount; // how much usd is going to be subtracted from USD_BALANCE

                                if($LTC_BALANCE >= $amount){

                                    $newBTCbalance = $BTC_BALANCE + $worthOfLTCinBTC; // calcuate how much coin or usd is to be spent

                                    //make purchese
                                    //queary new balance

                                    $newLTCbalance = $LTC_BALANCE - $amount; // new amount of BTC

                                    global $db;
                                    $query = "UPDATE userWallet SET litecoinBalance = '$newLTCbalance' WHERE userID = '$userID'";
                                    $statement = $db->prepare($query);
                                    $statement->execute();
                                    $statement->closeCursor();

                                    $query2 = "UPDATE userWallet SET bitcoinBalance = '$newBTCbalance' WHERE userID = '$userID'";
                                    $statement2 = $db->prepare($query2);
                                    $statement2->execute();
                                    $statement2->closeCursor();

                                    return "sucessfully added " . $chosen_coin . " worth " . $amount;

                                }else{

                                    return "insuffecient funds";

                                }

                            case "TRX":

                                $worthOfTRXinBTC = $TRX_Price * $amount; // how much usd is going to be subtracted from USD_BALANCE

                                if($TRX_BALANCE >= $amount){

                                    $newBTCbalance = $BTC_BALANCE + $worthOfTRXinBTC; // calcuate how much coin or usd is to be spent

                                    //make purchese
                                    //queary new balance

                                    $newTRXbalance = $TRX_BALANCE - $amount; // new amount of BTC

                                    global $db;
                                    $query = "UPDATE userWallet SET tronBalance = '$newTRXbalance' WHERE userID = '$userID'";
                                    $statement = $db->prepare($query);
                                    $statement->execute();
                                    $statement->closeCursor();

                                    $query2 = "UPDATE userWallet SET bitcoinBalance = '$newBTCbalance' WHERE userID = '$userID'";
                                    $statement2 = $db->prepare($query2);
                                    $statement2->execute();
                                    $statement2->closeCursor();

                                    return "sucessfully added " . $chosen_coin . " worth " . $amount;

                                }else{

                                    return "insuffecient funds";

                                }

                            case "BCH":

                                $worthOfBCHinBTC = $BCH_Price * $amount; // how much usd is going to be subtracted from USD_BALANCE

                                if($BCH_BALANCE >= $amount){

                                    $newBTCbalance = $BTC_BALANCE + $worthOfBCHinBTC; // calcuate how much coin or usd is to be spent

                                    //make purchese
                                    //queary new balance

                                    $newBCHbalance = $BCH_BALANCE - $amount; // new amount of BTC

                                    global $db;
                                    $query = "UPDATE userWallet SET bitcoincashBalance = '$newBCHbalance' WHERE userID = '$userID'";
                                    $statement = $db->prepare($query);
                                    $statement->execute();
                                    $statement->closeCursor();

                                    $query2 = "UPDATE userWallet SET bitcoinBalance = '$newBTCbalance' WHERE userID = '$userID'";
                                    $statement2 = $db->prepare($query2);
                                    $statement2->execute();
                                    $statement2->closeCursor();

                                    return "sucessfully added " . $chosen_coin . " worth " . $amount;

                                }else{

                                    return "insuffecient funds";

                                }


                        }


                            //then sell chosen_coin for btc //add choosen coins balance and btc balance

                    }

    }



}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//How the front end should make calls to database function with array
/*
//To insert a new user
$testArray = array(
	"username" => "test",
	"password" => "test",
	"email" => "test@test.com"
	);

//insertUser($testArray);

//makeCoinTable();



//How the front end should make calls to the database function authentification with array
/*
$loginTestArray = array(
        "username" => "test",
        "password" => "test",
);

$authentication  = doLogin($loginTestArray);
*/

//How to test buy sell needs 4 values in order: (userID, coinsold, coinbought, amount)
//amount = amount of buyCoin
//-amount = amount of buyCoin sold
//Simply put buying a negative amount is selling instead

//$value = buyCoins(22, 'BTC', 'USD', 1);
//buyCoins(63, 'BTC', 'ETH', 1);
//buyCoins(63, 'BTC', 'TRX', 1);
//buyCoins(63, 'BTC', 'BCH', 1);
//buyCoins(63, 'BTC', 'LTC', 1);
//buyCoins(63, 'USD', 'BTC', 1);
//buyCoins(63, 'ETH', 'BTC', 1);
//buyCoins(63, 'TRX', 'BTC', 1);
//buyCoins(63, 'BCH', 'BTC', 1);
//buyCoins(63, 'LTC', 'BTC', 1);




?>
