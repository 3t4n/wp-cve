<?php
/**
 * ifeelweb.de WordPress Plugin Framework
 * For more information see http://www.ifeelweb.de/wp-plugin-framework
 *
 *
 *
 * @author   Timo Reith <timo@ifeelweb.de>
 * @version  $Id: Request.php 2990970 2023-11-07 16:18:32Z worschtebrot $
 */
class IfwPsn_Wp_Http_Request
{
    /**
     * @var IfwPsn_Wp_Plugin_Manager
     */
    protected $_pm;

    /**
     * @var string
     */
    protected $_url;

    /**
     * @var string
     */
    protected $_userAgent;

    /**
     * @var int
     */
    protected $_timeout;

    /**
     * @var bool
     */
    protected $_sslverify;

    /**
     * @var array
     */
    protected $_data = array();

    /**
     * @var string
     */
    protected $_sendMethod = 'post';



    /**
     * @param IfwPsn_Wp_Plugin_Manager $pm
     */
    public function __construct($pm = null)
    {
        if ($pm instanceof IfwPsn_Wp_Plugin_Manager) {
            $this->_pm = $pm;
        }

        $this->_init();
    }

    /**
     * @param array $options
     */
    public function setOptions($options = array())
    {
        if (isset($options['url']) && is_string($options['url'])) {
            $this->setUrl($options['url']);
        }
        if (isset($options['timeout']) && is_numeric($options['timeout'])) {
            $this->setTimeout((int)$options['timeout']);
        }
        if (isset($options['random_useragent']) && $options['random_useragent'] == true) {
            $this->setUserAgent(self::getRandomUserAgent());
        }
        if (isset($options['sslverify']) && is_bool($options['sslverify'])) {
            $this->setSslverify($options['sslverify']);
        }
        if (isset($options['method']) && in_array(strtolower($options['method']), array('get', 'post'))) {
            $this->setSendMethod($options['method']);
        }
    }

    /**
     *
     */
    protected function _init()
    {
    }

    /**
     * @return IfwPsn_Wp_Http_Response
     */
    public function send()
    {
        $args = array();

        if ($this->getUserAgent() !== null) {
            $args['user-agent'] = $this->getUserAgent();
        }
        if ($this->getTimeout() !== null) {
            $args['timeout'] = $this->getTimeout();
        }
        if ($this->getSslverify() !== null) {
            $args['sslverify'] = $this->getSslverify();
        }

        if ($this->getSendMethod() == 'post') {

            $args['body'] = $this->getData();

            $this->_log('Sending POST request', array_merge($args, array('url' => $this->getUrl())));

            $response = wp_remote_post($this->getUrl(), $args);

        } elseif ($this->getSendMethod() == 'get') {

            $url = add_query_arg($this->getData(), $this->getUrl());
            $url = esc_url_raw($url);

            $this->_log('Sending GET request', array_merge($args, array('url' => $url)));

            $response = wp_remote_get($url, $args);
        }

        $this->_log('Request completed', array('response' => $response));

        if (isset($response)) {
            return new IfwPsn_Wp_Http_Response($response);
        }

        return null;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @param string $userAgent
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->_userAgent = $userAgent;
        return $this;
    }

    /**
     * @return $this
     */
    public function setRandomUserAgent()
    {
        $this->_userAgent = self::getRandomUserAgent();
        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->_userAgent;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->_timeout;
    }

    /**
     * @param int $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->_timeout = $timeout;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getSslverify()
    {
        return $this->_sslverify;
    }

    /**
     * @param boolean $sslverify
     * @return $this
     */
    public function setSslverify($sslverify)
    {
        if (is_bool($sslverify)) {
            $this->_sslverify = $sslverify;
        }
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function addData($key, $value)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param string $sendMethod
     * @return $this
     */
    public function setSendMethod($sendMethod)
    {
        if (in_array($sendMethod, array('get', 'post'))) {
            $this->_sendMethod = $sendMethod;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getSendMethod()
    {
        return $this->_sendMethod;
    }

    /**
     * @param $message
     * @param array $options
     */
    protected function _log($message, array $options)
    {
        if ($this->_pm instanceof IfwPsn_Wp_Plugin_Manager) {
            $this->_pm->log('http_request', $message, $options);
        }
    }

    /**
     * @param bool $incluceMobile
     * @return string
     */
    public static function getRandomUserAgent($incluceMobile = false)
    {
        $agents = self::getUserAgents($incluceMobile);

        return $agents[rand(0, count($agents)-1)];
    }

    /**
     * @param bool $incluceModile
     * @return array
     */
    public static function getUserAgents($incluceModile = false)
    {
        $result = self::getUserAgentsDesktop();
        if ($incluceModile) {
            $result = array_merge($result, self::getUserAgentsMobile());
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getUserAgentsDesktop()
    {
        // newer added at the end

        return array(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.89 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML like Gecko) Chrome/51.0.2704.79 Safari/537.36 Edge/14.14931',
            'Chrome (AppleWebKit/537.1; Chrome50.0; Windows NT 6.3) AppleWebKit/537.36 (KHTML like Gecko) Chrome/51.0.2704.79 Safari/537.36 Edge/14.14393',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.157 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.1.2 Safari/605.1.15',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36 Edg/86.0.622.69',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.182 Safari/537.36 Edg/88.0.705.81',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36 Edg/89.0.774.57'
        );
    }

    public static function getUserAgentsMobile()
    {
        return array(
            'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) CriOS/56.0.2924.75 Mobile/14E5239e Safari/602.1',
            'Mozilla/5.0 (Linux; Android 4.4.3; KFTHWI Build/KTU84M) AppleWebKit/537.36 (KHTML, like Gecko) Silk/47.1.79 like Chrome/47.0.2526.80 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; SM-G532G Build/MMB29T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.83 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0; vivo 1713 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.124 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.4.2; XMP-6250 Build/HAWK) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Safari/537.36 ADAPI/2.0 (UUID:9e7df0ed-2a5c-4a19-bec7-2cc54800f99d) RK3188-ADAPI/1.2.84.533 (MODEL:XMP-6250)',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 13_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 12_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 13_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.1 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
            'Mozilla/5.0 (Linux; Android 7.0; SM-G892A Build/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/68.0.3440.1805 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 9; SM-G960F Build/PPR1.180610.011; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/74.0.3729.157 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; SM-T555 Build/NMF26X; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/83.0.4103.96 Safari/537.36'
        );
    }
}
 