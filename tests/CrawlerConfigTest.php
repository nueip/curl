<?php

namespace nueip\curl\tests;

use nueip\curl\CrawlerConfig;
use PHPUnit\Framework\TestCase;

class CrawlerConfigTest extends TestCase
{
    protected $defConf;

    // set default config
    protected function setUp(): void
    {
        $this->defConf = [
            'title' => 'test',
            'site' => '',
            'uri' => '',
            'url' => 'https://www.google.com',
            'type' => 'get',
            'data' => [
                'test_time' => '2020-01-08'
            ],
            'cookies' => [
                'test_cookies' => 'test4Cookie'
            ],
            'curlOpt' => [
                CURLOPT_POST => true,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ],
            'filePath' => 'tmp/test.php'
        ];
    }

    /**
     * test Init
     */
    public function testCrawlerConfig()
    {
        $crawlerConfig = new CrawlerConfig($this->defConf);

        $config = $crawlerConfig->getConfig();

        $this->assertEquals($this->defConf, $config);
    }

    public function testGetUrl()
    {
        $this->defConf['site'] = 'https://www.google.com/';

        $this->defConf['uri'] = '/search/something?test=1';

        $url = (new CrawlerConfig($this->defConf))->getUrl();

        $this->assertEquals($url, "https://www.google.com");

        unset($this->defConf['url']);

        $url = (new CrawlerConfig($this->defConf))->getUrl();

        $this->assertEquals($url, "https://www.google.com/search/something?test=1");
    }

    /**
     * test Set type
     * 
     * @dataProvider typeProvider
     *
     * @param string $type
     * @param string $ans
     */
    public function testSetType($type, $ans)
    {
        $crawlerConfig = new CrawlerConfig($this->defConf);

        $crawlerConfig->setType($type);

        $getType = $crawlerConfig->getConfig('type');

        $this->assertEquals($ans, $getType);
    }

    /**
     * Set Type data
     */
    public function typeProvider()
    {
        return [
            ['get', 'get'],
            ['post', 'post'],
            ['put', 'put'],
            ['delete', 'delete'],
            ['Downloadget', 'downloadget'],
            ['downloadPOST', 'downloadpost'],
            ['errorGet', null],
        ];
    }
};
