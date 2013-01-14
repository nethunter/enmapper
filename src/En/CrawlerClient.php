<?php
namespace En;

use Guzzle\Http\Client;

class CrawlerClient extends \Goutte\Client {
    public function getClient() {
        $client = parent::getClient();
        
        $client->setConfig(array(
            'curl.options' => array(
                CURLOPT_CONNECTTIMEOUT => 60,
                CURLOPT_TIMEOUT => 60,
            ),
        ));
        
        return $client;
    }
}