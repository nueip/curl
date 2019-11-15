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
};
