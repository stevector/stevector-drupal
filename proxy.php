<?php

use Proxy\Proxy;
use Proxy\Adapter\Guzzle\GuzzleAdapter;
use Proxy\Filter\RemoveEncodingFilter;
use Zend\Diactoros\ServerRequestFactory;


//print_r(get_defined_vars());

require_once 'vendor/autoload.php';

//print_r(get_defined_vars());






// Create a PSR7 request based on the current browser request.
//$request = ServerRequestFactory::fromGlobals();

$fake_server = [
//  'REQUEST_URI' =>  '/pr-19' . $_SERVER['REQUEST_URI']
];

$fake_server = $_SERVER;
$fake_server['REQUEST_URI'];

if (empty($fake_server['REQUEST_URI']) || $fake_server['REQUEST_URI'] == '/') {
  $fake_server['REQUEST_URI'] = '/index.html';
}

$fake_server['REQUEST_URI'] = '/' . $_ENV['PANTHEON_ENVIRONMENT'] . $fake_server['REQUEST_URI'];

$request = ServerRequestFactory::fromGlobals($fake_server);

// Create a guzzle client
$guzzle = new GuzzleHttp\Client();

// Create the proxy instance
$proxy = new Proxy(new GuzzleAdapter($guzzle));

// Add a response filter that removes the encoding headers.
$proxy->filter(new RemoveEncodingFilter());

$url = 'http://gcp-gatsby-bucket.stevector.com/';

//$url = rtrim($url, '/');
// Forward the request and get the response.
$response = $proxy->forward($request)->to($url);



//print_r($response);

// Output response to the browser.
(new Zend\Diactoros\Response\SapiEmitter)->emit($response);
print_r($url);
die();
