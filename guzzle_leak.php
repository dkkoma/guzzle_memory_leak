<?php

require 'vendor/autoload.php';

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;


$client = new GuzzleHttp\Client;
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
