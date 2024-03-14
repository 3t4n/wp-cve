<?php
defined('ABSPATH') or die("Cannot access pages directly.");

class BridgeConnector
{
  const CART_ID = 'Woocommerce';
  const BRIDGE_ACTION = 'checkbridge';
  const BRIDGE_FOLDER = 'bridge2cart';
  const BRIDGE_ENDPOINT = 'a2c/v1/bridge-action';

  var $bridgeUrl = '';
  var $root = '';
  var $bridgePath = '';
  var $errorMessage = '';
  var $configFilePath = '/config.php';

  public function __construct()
  {
    $this->root       = realpath(WP_CONTENT_DIR . '/..');
    $this->bridgePath = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . self::BRIDGE_FOLDER;
    $this->bridgeUrl  = get_home_url( null, rest_get_url_prefix(), 'rest' )  . DIRECTORY_SEPARATOR . self::BRIDGE_ENDPOINT;
  }

  /**
   * @return string
   */
  public function getBridgeUrl()
  {
    return $this->bridgeUrl;
  }

  /**
   * @return bool
   */
  public function isBridgeExist()
  {
    if (is_dir($this->bridgePath)
      && file_exists($this->bridgePath . '/bridge.php')
      && file_exists($this->bridgePath . '/config.php')
    ) {
      return true;
    }

    return false;
  }

  /**
   * @return array
   */
  public function installBridge()
  {
    if ($this->isBridgeExist()) {
      return  $this->_checkBridge(true);
    } else {
      return ['success' => false, 'message' => 'Bridge not exist. Please reinstall plugin', 'custom' => true];
    }
  }

  /**
   * @param $token
   *
   * @return array
   */
  public function updateToken($token)
  {
    $result = ['success' => false, 'message' => 'Can\'t update Store Key'];

    $config = @fopen($this->bridgePath . $this->configFilePath, 'w');

    if (!$config) {
      $result['message'] = 'Can\'t open config.php. Please check permissions';

      return $result;
    }

    $writed = fwrite($config, "<?php if (!defined('A2CBC_TOKEN')) {define('A2CBC_TOKEN', '" . $token . "');}");

    if ($writed === false) {
      $result['message'] = 'Can\'t save config.php. Please check permissions';

      return $result;
    }

    fclose($config);

    return ['success' => true, 'message' => 'Store Key updated successfully'];
  }

  /**
   * @return string
   * @throws Exception
   */
  public static function generateStoreKey()
  {
    $bytesLength = 256;

    if (function_exists('random_bytes')) { // available in PHP 7
      return md5(random_bytes($bytesLength));
    }

    if (function_exists('mcrypt_create_iv')) {
      $bytes = mcrypt_create_iv($bytesLength, MCRYPT_DEV_URANDOM);
      if ($bytes !== false && strlen($bytes) === $bytesLength) {
        return md5($bytes);
      }
    }

    if (function_exists('openssl_random_pseudo_bytes')) {
      $bytes = openssl_random_pseudo_bytes($bytesLength);
      if ($bytes !== false) {
        return md5($bytes);
      }
    }

    if (file_exists('/dev/urandom') && is_readable('/dev/urandom')) {
      $frandom = fopen('/dev/urandom', 'r');
      if ($frandom !== false) {
        return md5(fread($frandom, $bytesLength));
      }
    }

    $rand = '';
    for ($i = 0; $i < $bytesLength; $i++) {
      $rand .= chr(mt_rand(0, 255));
    }

    return md5($rand);
  }

  /**
   * @param string $storeKey Store Key
   *
   * @return array|WP_Error
   */
  protected function _request($storeKey, $isHttp = false)
  {
    $params = ['store_root' => isset($this->root) ? $this->root : ''];
    $data = $this->_prepareUseHash($storeKey, $params);
    $query = http_build_query($data['get']);
    $headers = [
      'Accept-Language:*',
      'User-Agent:' . $this->_randomUserAgent()
    ];

    $url = $this->bridgeUrl . '?' . $query;

    if ($isHttp) {
      $url = str_replace('https://', 'http://', $url);
    }

    return wp_remote_post(
      $url,
      [
        'method'      => 'POST',
        'timeout'     => 30,
        'redirection' => 5,
        'httpversion' => '1.0',
        'sslverify'   => false,
        'blocking'    => true,
        'headers'     => $headers,
        'body'        => $data['post'],
        'cookies'     => [],
      ]
    );
  }

  /**
   * @param string     $storeKey Store Key
   * @param array|null $params   Parameters
   *
   * @return array
   */
  private function _prepareUseHash($storeKey, array $params = null)
  {
    $getParams = [
      'unique'         => md5(uniqid(mt_rand(), 1)),
      'disable_checks' => 1,
      'cart_id'        => self::CART_ID,
    ];

    if (!is_array($params)) {
      $params = [];
    }

    $params['action']     = self::BRIDGE_ACTION;
    $params['cart_id']    = self::CART_ID;
    $params['store_root'] = rtrim($this->root, DIRECTORY_SEPARATOR);

    ksort($params, SORT_STRING);
    $params['a2c_sign'] = hash_hmac('sha256', http_build_query($params), $storeKey);

    return ['get' => $getParams, 'post' => $params];
  }

  /**
   * Generate random User-Agent
   * @return string
   */
  private function _randomUserAgent()
  {
    $rand = mt_rand(1, 3);
    switch ($rand) {
      case 1:
        return 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:25.0) Gecko/2010' . mt_rand(10, 12) . mt_rand(10, 30) . ' Firefox/' . mt_rand(10, 25) . '.0';

      case 2:
        return 'Mozilla/6.0 (Windows NT 6.2; WOW64; rv:16.0.1) Gecko/2012' . mt_rand(10, 12) . mt_rand(10, 30) . ' Firefox/' . mt_rand(10, 16) . '.0.1';

      case 3:
        return 'Opera/10.' . mt_rand(10, 60) . ' (Windows NT 5.1; U; en) Presto/2.6.30 Version/10.60';
    }
  }

  /**
   * @param bool $isCustom Custom Flag
   *
   * @return array
   */
  protected function _checkBridge($isCustom = false)
  {
    if ($file = @fopen($this->bridgePath . $this->configFilePath, 'r')) {
      $content  = fread($file, filesize( $this->bridgePath . $this->configFilePath));
      $storeKey = '';

      foreach (explode("\n", $content) as $line) {
        if (preg_match('/define\([\'|"]A2CBC_TOKEN[\'|"],[ ]*[\'|"](.*?)[\'|"]\)/s', $line, $matches)) {
          $storeKey = $matches[1];
          break;
        }
      }

      fclose($file);
      $res = $this->_request($storeKey);

      if (is_wp_error($res) && strpos($res->get_error_message(), 'cURL error') !== false) {//try to http
        $res = $this->_request($storeKey, true);
      }

      if (json_decode(wp_remote_retrieve_body($res)) === 'BRIDGE_OK') {
        return ['success' => true, 'message' => 'Bridge install successfully', 'custom' => $isCustom];
      }

      if ( is_wp_error( $res ) ) {
        return ['success' => false, 'message' => 'Url:' . $this->bridgeUrl . PHP_EOL . $res->get_error_message(), 'custom' => $isCustom];
      } else {
        return ['success' => false, 'message' => 'Can\'t verify bridge url: ' . $this->bridgeUrl . '. Status code:' . wp_remote_retrieve_response_code($res), 'custom' => $isCustom];
      }
    } else {
      $error = error_get_last();

      return ['success' => false, 'message' => 'Url:' . $this->bridgeUrl . PHP_EOL . $error['message'], 'custom' => $isCustom];
    }
  }

}