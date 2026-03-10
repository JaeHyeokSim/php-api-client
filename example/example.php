<?php

require "../src/HttpClient.php";
require "../src/Cache.php";
require "../src/ApiException.php";

$cache = new Cache();

$client = new HttpClient("https://jsonplaceholder.typicode.com", [
	"timeout" => 3,
	"retry" => 2,
	"cache" => $cache
]);

try {

	$result = $client->get("/posts", [
		"userId" => 1
	]);

	print_r($result);

} catch (ApiException $e) {

	echo "Error: " . $e->getMessage();

}