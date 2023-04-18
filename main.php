<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

require_once "vendor/autoload.php";

$balance = (int) readline("Enter cash: ");
$selectedCurrency = readline("Enter currency which you wish to convert to: ");

$url = "https://www.latvijasbanka.lv/vk/ecb.xml";

$client = new Client(
    ["verify" => false]
);

try {
    $response = $client->get($url);
} catch (GuzzleException $e) {
    echo $e . PHP_EOL;
}

$body = $response->getBody()->getContents();

$xml = simplexml_load_string($body, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml);
$data = json_decode($json,TRUE);

$unformattedData = $data["Currencies"];
$currencies = $unformattedData["Currency"];

$result = 0;


foreach ($currencies as $currency){
    if ($currency["ID"] == $selectedCurrency) {
        $id = $currency["ID"];
        $rate = $currency["Rate"];
        $result = $balance * (float) $rate;
        echo "You converted $balance EUR to $result $id";
        break;
    }
}


