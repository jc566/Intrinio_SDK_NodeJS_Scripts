<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;


//$x = array("Symbol"=>"AAPL");

/***********
Local Tests*
***********/
//buyStock($x);
//calcPriceChange($x);
//calcPercentChange($x);
//netGain($x);
//showBasicInfo($x);
//showLast7($x);
//showLast30($x);

/*
$mysql_server = '192.168.0.106';
    
$conn = new mysqli($mysql_server, "badgers", "honey", "user_info");
// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
} 
else 
{
    //echo "connected";
}
*/

/*****************************************
Connects to API and retrieves information*
*****************************************/
function getInfo($string){
    $sym = $string;
    
    //Create a request but don't send it immediately
    $client = new Client();

    //$Client is server we are using to connect server API
    $client = new GuzzleHttp\Client(['base_url' => '192.168.0.154']);

    //This 'page' is the one we use to gather Stock Information
    $response = $client->get('192.168.0.154:9090/stocks?sym='.$sym);
    
    $getInfo = $response->getBody();
    
    return $getInfo;
}
/*************************************************
Get the Quantity/Ticker requested from User input*
Run calculations to give Total Value             *
*************************************************/
function buyStock($data){
    //Local Testing
    $sym = $data["Symbol"];
    
    //Retrieve the Queries
    $sym = $data->Symbol;
    $qty = $data->Quantity;
    
    //grab the information needed
    $jsonObj = getInfo($sym);
    //decode the structure
    $newObj = json_decode($jsonObj);
    //Single out the Closing price aka Current Price
    $currentCost = $newObj->Close[0];
    //var_dump($newObj->Close[0]); //display the closing price

    //$username is either user input or given from DB
    $username = 'jc566';
    //$purCost is to store a value in DB, ex: $purCost = API->currentCost;
    $purCost = $currentCost;
    //Calculate the total value of your purchase. Cost * Shares
    $totalValue = $purCost * $shares;
    //make a new object to store data to return? 
    $returnObj = array("TotalValue"=>$totalValue);
    //example of how to single out a value is Below
    //var_dump($returnObj["TotalValue"]); 
    
    echo var_dump($returnObj);
    return ($returnObj);
}
/*****************************
Returns company info & prices*
*****************************/
function showBasicInfo($data){
    $sym = $data["Symbol"];

    $symbol = getInfo($sym);

    return $symbol;
}
/************************************
Returns the last 7 days Price Info  *
By Default, we grab the last 30 days*
************************************/
function showLast7($data){
    $sym = $data["Symbol"];
    $symbol = getInfo($sym);
    $newObj = json_decode($symbol);

    //How many days are we returning?
    $days = 7;

    //Populate a new object with the necesary information
    for ($i = 0; $i < 2;$i++)
    {
        $jsonObj['Open'][$i] = json_encode(array(array("Open"=>$newObj->Open[$i])));
        $jsonObj['Close'][$i] = json_encode(array(array("Close"=>$newObj->Close[$i])));
        $jsonObj['High'][$i] = json_encode(array(array("High"=>$newObj->High[$i])));
        $jsonObj['Low'][$i] = json_encode(array(array("Low"=>$newObj->Low[$i])));
    }
    //Example of how to display the Data
    for ($i = 0; $i < 2; $i++)
    {
        var_dump($jsonObj['Close'][$i]); //this is how to access specific info
    }
    //return the object
    return $jsonObj;
}
/***********************************
Returns the last 30 Days Price info  *
By Default we grab the last 30 Days*
***********************************/
function showLast30($data){
    $sym = $data["Symbol"];
    $symbol = getInfo($sym);
    $newObj = json_decode($symbol);

    //How many days are we returning?
    $days = 30;

    //Populate a new object with the necesary information
        for ($i = 0; $i < $days;$i++)
        {
            $jsonObj['Open'][$i] = json_encode(array(array("Open"=>$newObj->Open[$i])));
            $jsonObj['Close'][$i] = json_encode(array(array("Close"=>$newObj->Close[$i])));
            $jsonObj['High'][$i] = json_encode(array(array("High"=>$newObj->High[$i])));
            $jsonObj['Low'][$i] = json_encode(array(array("Low"=>$newObj->Low[$i])));
        }
        //Example of how to display the Data
        for ($i = 0; $i < 2; $i++)
        {
            var_dump($jsonObj['Close'][$i]); //this is how to access specific info
        }
    //return the object
    return $jsonObj;
}

function calcPriceChange($data){
    $sym = $data["Symbol"];
    //grab the information needed
    $jsonObj = getInfo($sym);
    //decode the structure
    $newObj = json_decode($jsonObj);
    //Single out the Closing price aka Current Price
    $currentCost = $newObj->Close[0];

    //$purCost is coming from the DB
    $purCost = 150.00; 

    //if the Purchase Cost is greater than Current, you lost money
    if($purCost > $currentCost)
    {
        //calculate the Price Change in $ amount.
        $priceChange = $purCost - $currentCost;
        $priceChange = -1 * abs($priceChange);
    }
    //if Purchase Cost is less than Current, you gained money
    elseif($purCost < $currentCost)
    {
        //calculate the Price Change in $ amount.
        $priceChange = ($currentCost - $purCost);
        
    }
    //make new object to store data to return
    $returnObj = array("PriceChg"=>$priceChange);
    //example of how to access data below
    var_dump($returnObj["PriceChg"]);
    return (json_encode($returnObj));
}

function calcPercentChange($data){
    $sym = $data["Symbol"];
    //grab the information needed
    $jsonObj = getInfo($sym);
    //decode the structure
    $newObj = json_decode($jsonObj);
    //Single out the Closing price aka Current Price
    $currentCost = $newObj->Close[0];

    //$purCost is coming from the DB
    $purCost = 150;

    //if currentCost is greater than purchase, you gained money
    if($currentCost > $purCost)
    {
        $percentChange = (($currentCost - $purCost) / $purCost) * 100;
    }
    //if currentCost is less than purchase, you lost money
    elseif($currentCost < $purCost)
    {
        $percentChange = (($purCost - $currentCost) /$purCost) * 100;
        $percentChange = -1 * abs($percentChange);
    }
    //make new object to store data to return
    $returnObj = array("PercentChg"=>$percentChange);
    //example of how to access data below
    var_dump($returnObj["PercentChg"]);
    //return the object
    return ($returnObj);

}

function netGain($data){
    $sym = $data["Symbol"];
    //grab the information needed
    $jsonObj = getInfo($sym);
    //decode the structure
    $newObj = json_decode($jsonObj);
    //Single out the Closing price aka Current Price
    $currentCost = $newObj->Close[0];

    $purCost = 150;

    //if Purchase Cost is greather than Current, you Lost money
    if($purCost > $currentCost)
    {
        $totalGL = ($purCost - $currentCost) / $currentCost;
        $totalGL = -1 * abs($totalGL);
    }
    //if Purchase Cost is less than Current, you Gained money
    elseif($purCost < $currentCost)
    {
        $totalGL = ($purCost - $currentCost) / $currentCost;
    }
    //make new object to store data to return
    $returnObj = array("totalGL"=>$totalGL);
    //example of how to access data below
    var_dump($returnObj["totalGL"]);
    //return the object
    return ($returnObj);
    }
?>