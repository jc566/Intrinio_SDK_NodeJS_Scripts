<?php

require 'vendor/autoload.php';
use GuzzleHttp\Client;
//use GuzzleHttp\Message\Request;
//use GuzzleHttp\Message\MessageFactoryInterface;
//use GuzzleHttp\Stream\StreamInterface;
//use GuzzleHttp\Exception\RequestException;
//use GuzzleHttp\Pool;

//$client = new GuzzleHttp\Client(['base_uri'=> 'http://localhost:9090/stocks?sym=aapl']);



//Create a request but don't send it immediately
$client = new Client();

$client = new GuzzleHttp\Client(['base_url' => 'http://localhost:9090']);



$response = $client->get('http://localhost:9090/stocks?sym=goog');
echo $response->getBody();

?>
