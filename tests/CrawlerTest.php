<?php

namespace nueip\curl\tests;

use nueip\curl\Crawler;
use PHPUnit\Framework\TestCase;

class CrawlerTest extends TestCase
{
    /**
     * test setting curl default option
     */
    public function testSetGetCurlOpt()
    {
        $setOpt = [
            CURLOPT_FOLLOWLOCATION => true,
        ];

        $oriOpt = Crawler::getCurlOpt();

        Crawler::setCurlOpt($setOpt);

        $newOpt = Crawler::getCurlOpt();

        $this->assertEquals($newOpt, $setOpt + $oriOpt);
    }

    /**
     * test Init cookie file path
     */
    public function testInitCookie()
    {
        Crawler::initCookie();

        $opt = Crawler::getCurlOpt();

        $this->assertArrayHasKey(CURLOPT_COOKIEFILE, $opt);
        $this->assertArrayHasKey(CURLOPT_COOKIEJAR, $opt);

        $filePath = $opt[CURLOPT_COOKIEFILE];
        $this->assertGreaterThan(0, strlen($filePath));
        $this->assertFileExists($filePath);
        $this->assertFileIsWritable($filePath);
    }

    /**
     * test Set cookies value
     */
    public function testSetCookie()
    {
        // 設定的 Cookie 變數
        $cookieArr = [
            'name' => 'admin',
            'start' => '2019-10-01',
            'end' => '2019-11-19',
        ];

        // 預期 轉換結果
        $cookieStr = 'name=admin;start=2019-10-01;end=2019-11-19';

        Crawler::initCookie();
        Crawler::setCookie($cookieArr);

        $opt = Crawler::getCurlOpt();

        $this->assertEquals($opt[CURLOPT_COOKIE], $cookieStr);
    }
};
