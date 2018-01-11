<?php

require 'vendor/autoload.php';

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;


$stack = new HandlerStack();
$stack->setHandler(new CurlHandler());
// https://github.com/guzzle/guzzle/blob/master/src/HandlerStack.php#L41 とredirectとhttpErrorsの順番を変えている
// どちらか前後しても影響はしない
$stack->push(Middleware::redirect(), 'allow_redirects');
$stack->push(Middleware::httpErrors(), 'http_errors');
$stack->push(Middleware::cookies(), 'cookies');
$stack->push(Middleware::prepareBody(), 'prepare_body');

$client = new GuzzleHttp\Client(['handler' => $stack]);
for ($i = 0; $i < 10; $i++)
{
    http($client, 10);
}

function http($client, $limit)
{
    for($i = 1; $i <= $limit; $i++) {
        $tracesize = 0;
        try {
            $config = [
                //'allow_redirects' => ['max' => 1,  'strict' => true],
            ];
            $response = $client->request('GET', 'https://httpbin.org/status/500', $config);
        } catch (\Exception $e) {
            //xdebug_debug_zval('e');
            //file_put_contents('/tmp/debug', print_r($e, true));
        } finally {
            unset ($config, $response, $e);
        }
        if ($i % $limit === 0) {
            $usage = memory_get_usage();
            //xdebug_memory_usage();
            echo "$i:$usage B\n";
        }
    }
}
