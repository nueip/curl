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
        'filePath' => null,
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
        'downloadget' => '',
        'downloadpost' => '',
        'upload' => '',
    ];

    /**
     * Construct
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (count($config)) {
            // make sure correct data
            $config = array_intersect_key($config + $this->config, $this->config);

            foreach ($config as $varName => $value) {
                // Building will be executing function Name
                $setFuncName = 'set' . ucfirst($varName);
                // access data exe function
                call_user_func_array([$this, $setFuncName], [$value]);
            }
        }
    }

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
     * Set FilePath
     *
     * @param  string|array $filePath
     * @return void
     */
    public function setFilePath($filePath)
    {
        $this->config['filePath'] = $filePath;
    }

    /**
     * Get Config
     *
     * @return string|array
     */
    public function getConfig($key = null)
    {
        return isset($key) ? ($this->config[$key] ?? null) : $this->config;
    }
}
