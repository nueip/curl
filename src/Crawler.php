<?php

namespace nueip\curl;

use Exception;

/**
 * Crawler
 *
 * @version  0.2.3
 * @author   Gunter Chou
 *
 * @see      https://github.com/nueip/curl
 */
class Crawler
{
    /**
     * Curl default option
     *
     * @var array
     */
    protected static $defCurlOpt = [
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // set follow redirects
        CURLOPT_FOLLOWLOCATION => false,
        // return the transfer as a string
        CURLOPT_RETURNTRANSFER => true,
        // close ssl verify
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        // set user agent
        CURLOPT_USERAGENT => 'Nueip Crawler',
    ];

    /**
     * Curl once option
     *
     * @var array
     */
    protected static $onceOpt = [];

    /**
     * Get curl default option
     *
     * @return array $opt
     */
    public static function getCurlOpt()
    {
        return self::$defCurlOpt;
    }

    /**
     * Set curl default option
     *
     * @param array $opt
     */
    public static function setCurlOpt(array $opt)
    {
        self::$defCurlOpt = $opt + self::$defCurlOpt;
    }

    /**
     * Init cookie file
     *
     * @return string
     */
    public static function initCookie()
    {
        $cookieFile = tempnam(sys_get_temp_dir(), 'NueipCrawler');

        self::setCurlOpt([
            CURLOPT_COOKIEFILE => $cookieFile,
            CURLOPT_COOKIEJAR => $cookieFile,
        ]);
    }

    /**
     * Set Cookie value
     *
     * @param array $data
     * @return void
     */
    public static function setCookie(array $data)
    {
        $str = [];
        foreach ($data as $cookieName => $value) {
            $str[] = "$cookieName=$value";
        }

        self::setCurlOpt([
            CURLOPT_COOKIE => implode(';', $str),
        ]);
    }

    /**
     * Run curl via config
     *
     * @param  CrawlerConfig $conf
     * @throws Exception
     * @return void
     */
    public static function run(CrawlerConfig $conf)
    {
        self::setCurlOpt($conf->getConfig('curlOpt'));
        self::setCookie($conf->getConfig('cookies'));

        $url = $conf->getConfig('url');
        $data = $conf->getConfig('data');
        $filePath = $conf->getConfig('filePath');

        switch ($conf->getConfig('type')) {
            case 'get':
                $result = self::get($url, $data);
                break;
            case 'post':
                $result = self::post($url, $data);
                break;
            case 'put':
                $result = self::put($url, $data);
                break;
            case 'delete':
                $result = self::delete($url, $data);
                break;
            case 'downloadget':
                $result = self::download($url, $data, 'GET', $filePath);
                break;
            case 'downloadpost':
                $result = self::download($url, $data, 'POST', $filePath);
                break;
            default:
                throw new Exception('Request type is not found', 400);
                break;
        }

        return $result;
    }

    /**
     * Curl download file
     *
     * @param string $url
     * @param array $data
     * @param string $method
     * @param string $filePath
     * @return string $filePath
     */
    public static function download(string $url, array $data, string $method = 'GET', string $filePath = null)
    {
        // Set default temp file path
        $filePath = $filePath ?? tempnam(sys_get_temp_dir(), 'NueipCrawlerTmpFile');

        // Open file
        $fp = fopen($filePath, 'w');

        // Extra curlOpt
        self::$onceOpt = [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FILE => $fp,
        ];

        if ($method === 'GET') {
            self::get($url, $data);
        } else {
            self::post($url, $data);
        }

        // init
        self::$onceOpt = [];

        return $filePath;
    }

    /**
     * curl post data
     *
     * @param  string $url
     * @param  array  $data
     * @param  string $method
     * @return string $response
     */
    public static function post(string $url, array $data, string $method = 'POST')
    {
        // create curl resource
        $curl = curl_init($url);

        // set curl option
        $opt = array_replace_recursive([
            CURLOPT_POST => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => [
                'Cache-Control: no-cache',
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ], self::$defCurlOpt, self::$onceOpt);

        curl_setopt_array($curl, $opt);

        // $response contains the output string
        $response = curl_exec($curl);

        // close curl resource to free up system resources
        curl_close($curl);

        return $response;
    }

    /**
     * curl get data
     *
     * @param  string $url
     * @return string $response
     */
    public static function get(string $url, array $data = [])
    {
        // 自動帶 url 參數
        count($data) && $url .= '?' . http_build_query($data);

        $curl = curl_init($url);

        // set curl option
        $opt = array_replace_recursive([
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => [
                'Cache-Control: no-cache',
            ],
        ], self::$defCurlOpt, self::$onceOpt);

        curl_setopt_array($curl, $opt);

        // $response contains the output string
        $response = curl_exec($curl);

        // close curl resource to free up system resources
        curl_close($curl);

        return $response;
    }

    /**
     * curl put data
     *
     * @param string $url
     * @param array $data
     * @return string $response
     */
    public static function put(string $url, array $data)
    {
        return self::post($url, $data, 'PUT');
    }

    /**
     * curl delete data
     *
     * @param string $url
     * @param array $data
     * @return string $response
     */
    public static function delete(string $url, array $data)
    {
        return self::post($url, $data, 'DELETE');
    }
};
