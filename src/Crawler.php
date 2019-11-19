<?php

namespace nueip\curl;

/**
 * Crawler
 *
 * @version  0.1.1
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
     * curl post data
     *
     * @param  string $url
     * @param  array  $data
     * @return string $response
     */
    public static function post(string $url, array $data)
    {
        // create curl resource
        $curl = curl_init($url);

        // set curl option
        $opt = [
            CURLOPT_POST => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => [
                'Cache-Control: no-cache',
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ] + self::$defCurlOpt;

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
    public static function get(string $url)
    {
        $curl = curl_init($url);

        // set curl option
        $opt = [
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => '',
            CURLOPT_HTTPHEADER => [
                'Cache-Control: no-cache',
            ],
        ] + self::$defCurlOpt;

        curl_setopt_array($curl, $opt);

        // $response contains the output string
        $response = curl_exec($curl);

        // close curl resource to free up system resources
        curl_close($curl);

        return $response;
    }
};
