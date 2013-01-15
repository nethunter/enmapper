<?php
namespace En\Games\Crawler;

use En\CrawlerClient;

abstract class CrawlerAbstract {
    protected $_domain = null;

    public function __construct($domain) {
        $this->_domain = $domain;
    }
    
    public function getCrawler()
    {
        $request_url = 'http://' . $this->_domain . $this->_url;

        $client = new CrawlerClient();
        $crawler = $client->request('GET', $request_url);
        
        return $crawler;
    }
    
    abstract function getData();
}