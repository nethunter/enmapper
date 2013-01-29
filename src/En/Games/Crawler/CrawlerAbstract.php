<?php
namespace En\Games\Crawler;

use En\CrawlerClient;

abstract class CrawlerAbstract
{
    protected $domain = null;
    protected $url = null;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function getCrawler()
    {
        $requestUrl = 'http://' . $this->domain . $this->url;

        $client = new CrawlerClient();
        $crawler = $client->request('GET', $requestUrl);
        
        return $crawler;
    }

    public function getUrl()
    {
        return $this->url;
    }
    
    abstract public function getData();
}
