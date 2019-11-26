<?php

namespace nueip\curl;

/**
 * Crawler Config
 *
 * @version  0.0.1
 * @author   Sean Lee
 *
 * @see      https://github.com/nueip/curl
 */
class CrawlerConfig
{
    /**
     * Default data format
     *
     * @var array
     */
    protected $config = [
        'title' => '',
        'url' => '',
        'type' => '',
        'data' => [],
        'cookies' => [],
        'curlOpt' => [],
    ];

    /**
     * Access request method list
     *
     * @var array
     */
    protected $methodList = [
        'get' => '',
        'post' => '',
        'put' => '',
        'delete' => '',
    ];

    /**
     * Construct
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (count($config)) {
            // 確保資料正確
            $config = array_intersect_key($config + $this->config, $this->config);

            foreach ($config as $key => $value) {
                // 建立執行的 function Name
                $setFuncName = 'set' . ucfirst($key);
                // 依資料執行對應 function
                call_user_func_array([$this, $setFuncName], [$value]);
            }
        }
    }

    /**
     * *******************************************************
     * ******************* Set Function **********************
     * *******************************************************
     */

    /**
     * Set Title
     *
     * @param  string $title
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->config['title'] = $title;
    }

    /**
     * Set Url
     *
     * @param  string $url
     * @return void
     */
    public function setUrl(string $url)
    {
        $this->config['url'] = $url;
    }

    /**
     * Set Type
     *
     * @param  string $type
     * @return void
     */
    public function setType(string $type)
    {
        $type = strtolower($type);
        $this->config['type'] = isset($this->methodList[$type]) ? $type : null;
    }

    /**
     * Set Data
     *
     * @param  string $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->config['data'] = $data;
    }

    /**
     * Set Cookies
     *
     * @param  array  $cookies
     * @return void
     */
    public function setCookies(array $cookies)
    {
        $this->config['cookies'] = $cookies;
    }

    /**
     * Set CurlOpt
     *
     * @param  array  $curlOpt
     * @return void
     */
    public function setCurlOpt(array $curlOpt)
    {
        $this->config['curlOpt'] = $curlOpt;
    }

    /**
     * *******************************************************
     * ******************* Get Function **********************
     * *******************************************************
     */

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->config['title'];
    }

    /**
     * Get Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->config['type'];
    }

    /**
     * Get Url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->config['url'];
    }

    /**
     * Get Data
     *
     * @return array
     */
    public function getData()
    {
        return $this->config['data'];
    }

    /**
     * Get Cookie
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->config['cookies'];
    }

    /**
     * Get CurlOpt
     *
     * @return array
     */
    public function getCurlOpt()
    {
        return $this->config['curlOpt'];
    }
}