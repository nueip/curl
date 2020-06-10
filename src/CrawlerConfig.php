<?php

namespace nueip\curl;

/**
 * Crawler Config
 *
 * @version  0.0.1
 * @author   Sean Lee
 * @author   Gunter Chou
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
        'site' => '',
        'uri' => '',
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
     * @return nueip\curl\CrawlerConfig
     */
    public function setTitle(string $title)
    {
        $this->config['title'] = $title;
        return $this;
    }

    /**
     * Set Site
     *
     * @param  string $site
     * @return nueip\curl\CrawlerConfig
     */
    public function setSite(string $site)
    {
        $this->config['site'] = preg_replace('/\/$/', '', $site);
        return $this;
    }

    /**
     * Set Uri
     *
     * @param  string $uri
     * @return nueip\curl\CrawlerConfig
     */
    public function setUri(string $uri)
    {
        $this->config['uri'] = preg_replace('/^\//', '', $uri);
        return $this;
    }

    /**
     * Set Url
     *
     * @param  string $url
     * @return nueip\curl\CrawlerConfig
     */
    public function setUrl(string $url)
    {
        $this->config['url'] = $url;
        return $this;
    }

    /**
     * Set Type
     *
     * @param  string $type
     * @return nueip\curl\CrawlerConfig
     */
    public function setType(string $type)
    {
        $type = strtolower($type);
        $this->config['type'] = isset($this->methodList[$type]) ? $type : null;
        return $this;
    }

    /**
     * Set Data
     *
     * @param  array $data
     * @return nueip\curl\CrawlerConfig
     */
    public function setData(array $data)
    {
        $this->config['data'] = $data;
        return $this;
    }

    /**
     * Append Data
     *
     * @param  array $data
     * @return nueip\curl\CrawlerConfig
     */
    public function appendData(array $data)
    {
        $this->config['data'] = $data + $this->config['data'];
        return $this;
    }

    /**
     * Set Cookies
     *
     * @param  array  $cookies
     * @return nueip\curl\CrawlerConfig
     */
    public function setCookies(array $cookies)
    {
        $this->config['cookies'] = $cookies;
        return $this;
    }

    /**
     * Set CurlOpt
     *
     * @param  array  $curlOpt
     * @return nueip\curl\CrawlerConfig
     */
    public function setCurlOpt(array $curlOpt)
    {
        $this->config['curlOpt'] = $curlOpt;
        return $this;
    }

    /**
     * Set FilePath
     *
     * @param  string|array $filePath
     * @return nueip\curl\CrawlerConfig
     */
    public function setFilePath($filePath)
    {
        $this->config['filePath'] = $filePath;
        return $this;
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

    /**
     * Get Url
     *
     * @return string
     */
    public function getUrl()
    {
        $config = $this->config;

        if (isset($config['url'][0])) {
            $url = $config['url'];
        } elseif (isset($config['site'][0])) {
            $url = implode('/', [$config['site'], $config['uri']]);
        } else {
            $url = '';
        }

        return $url;
    }
}
