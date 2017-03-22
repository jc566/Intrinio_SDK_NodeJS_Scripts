<?php

require 'vendor/autoload.php';
require_once 'send.php';
use GuzzleHttp\Client;
//use GuzzleHttp\Message\Request;
//use GuzzleHttp\Message\MessageFactoryInterface;
//use GuzzleHttp\Stream\StreamInterface;
//use GuzzleHttp\Exception\RequestException;
//use GuzzleHttp\Pool;

//$client = new GuzzleHttp\Client(['base_uri'=> 'http://localhost:9090/stocks?sym=aapl']);
function getInfo($string){

    //$sym = $_GET['sym'];


    $symname = $string;

    //if(strncasecmp($symname,'requestType',11))
    //echo(strncasecmp("/n",$symname, 'requesttype',11));
    //{
        $sym=ltrim($symname,"RequestType:StockInfo:Symbol:");
        //echo $sym;
        //Create a request but don't send it immediately
        $client = new Client();

        //$client = new GuzzleHttp\Client(['base_url' => 'http://localhost:9090']);
        $client = new GuzzleHttp\Client(['base_url' => 'http://localhost']);


        $response = $client->get('http://localhost:9090/stocks?sym='.$sym);

        $getInfo = $response->getBody();
        //echo json_encode($getInfo);

        //echo ($getInfo);
        return $getInfo;
        //sendMessage($getInfo);
        //}
        
}
//getInfo('aapl');


?>
