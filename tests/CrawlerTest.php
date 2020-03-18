<?php

namespace nueip\curl\tests;

use nueip\curl\Crawler;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

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
        $crawler = new Crawler();

        $crawler::initCookie();

        $reflection = new ReflectionClass(get_class($crawler));

        $opt = $reflection->getStaticProperties()['defCurlOpt'] ?? null;

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

    /**
     * test Build multipart form
     *
     * @return void
     */
    public function testMultipartFormBuilder()
    {
        $content = '';

        $boundary = 'PHPUnit';

        $file = [
            'uploadfile' => 'tests/emptyTest.txt',
        ];

        $data = [
            'ID' => '487',
            'Type' => 'import',
        ];

        $crawler = new Crawler();

        $reflection = new ReflectionClass(get_class($crawler));

        $method = $reflection->getMethod('multipartFormBuilder');
        $method->setAccessible(true);
        list($buildBoundary, $buildContent) = $method->invokeArgs($crawler, [$data, $file, $boundary]);

        $eof = "\r\n";
        $content .= "--{$boundary}{$eof}";
        $content .= "Content-Disposition: form-data; name='uploadfile'; filename='emptyTest.txt'{$eof}";
        $content .= "Content-Type: text/plain{$eof}{$eof}";
        $content .= "Testing{$eof}";
        $content .= "--{$boundary}{$eof}";
        $content .= "Content-Disposition: form-data; name='ID'{$eof}";
        $content .= "{$eof}487{$eof}";
        $content .= "--{$boundary}{$eof}";
        $content .= "Content-Disposition: form-data; name='Type'{$eof}";
        $content .= "{$eof}import{$eof}";
        $content .= "--{$boundary}--";

        $this->assertEquals($buildBoundary, $boundary);
        $this->assertEquals($buildContent, $content);
    }
};
