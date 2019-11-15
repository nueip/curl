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
};
