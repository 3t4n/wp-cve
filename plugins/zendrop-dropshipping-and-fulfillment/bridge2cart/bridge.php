<?php
/*-----------------------------------------------------------------------------+
| API2Cart                                                                     |
| Copyright (c) 2017 API2Cart.com <manager@api2cart.com>                       |
| All rights reserved                                                          |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "license.txt"|
| FILE PROVIDED WITH THIS DISTRIBUTION.                                        |
|                                                                              |
| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE  |
| THIS SOFTWARE   PROGRAM   AND  ASSOCIATED  DOCUMENTATION   THAT  API2CART    |
| (hereinafter  referred to as "THE AUTHOR") IS FURNISHING  OR MAKING          |
| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").    |
| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT  |
| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING,  |
| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY  |
| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS  |
| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS  |
| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND  |
| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS  |
| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE  |
| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE. |
| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.       |
|                                                                              |
| The Developer of the Code is API2Cart,                                       |
| Copyright (C) 2006 - 2020 All Rights Reserved.                               |
+------------------------------------------------------------------------------+
|                                                                              |
|                            ATTENTION!                                        |
+------------------------------------------------------------------------------+
| By our Terms of Use you agreed not to change, modify, add, or remove portions|
| of Bridge Script source code as it is owned by API2Cart company.             |
| You agreed not to use, reproduce, modify, adapt, publish, translate          |
| the Bridge Script source code into any form, medium, or technology           |
| now known or later developed throughout the universe.                        |
|                                                                              |
| Full text of our TOS located at                                              |
|                       https://www.api2cart.com/terms-of-service              |
+-----------------------------------------------------------------------------*/

interface M1_Platform_Actions
{
  /**
   * @param array $a2cData
   *
   * @return mixed
   */
  public function productUpdateAction(array $a2cData);

  /**
   * @param array $a2cData
   *
   * @return mixed
   */
  public function sendEmailNotifications(array $a2cData);

  /**
   * @return mixed
   */
  public function getPlugins();

  /**
   * @param array $a2cData
   *
   * @return mixed
   */
  public function triggerEvents(array $a2cData);

  /**
   * @param array $a2cData Data
   *
   * @return mixed
   */
  public function setMetaData(array $a2cData);


  /**
   * @param array $a2cData Data
   *
   * @return mixed
   */
  public function getTranslations(array $a2cData);

  /**
   * @param array $a2cData Data
   *
   * @return mixed
   */
  public function setOrderNotes(array $a2cData);

  /**
   * @param array $a2cData Data
   *
   * @return mixed
   */
  public function getActiveModules(array $a2cData);

  /**
   * @param array $a2cData Data
   *
   * @return mixed
   */
  public function getImagesUrls(array $a2cData);

  /**
   * @param array $a2cData Data
   *
   * @return mixed
   */
  public function orderUpdate(array $a2cData);

}

abstract class M1_DatabaseLink
{
  protected static $_maxRetriesToConnect = 5;
  protected static $_sleepBetweenAttempts = 2;

  protected $_config = null;
  private $_databaseHandle = null;

  protected $_insertedId = 0;
  protected $_affectedRows = 0;

  /**
   * @param M1_Config_Adapter $config Config adapter
   * @return M1_DatabaseLink
   */
  public function __construct($config)
  {
    $this->_config = $config;
  }

  /**
   * @return void
   */
  public function __destruct()
  {
    $this->_releaseHandle();
  }

  /**
   * @return stdClass|bool
   */
  private function _tryToConnect()
  {
    $triesCount = self::$_maxRetriesToConnect;

    $link = null;

    while (!$link) {
      if (!$triesCount--) {
        break;
      }
      $link = $this->_connect();
      if (!$link) {
        sleep(self::$_sleepBetweenAttempts);
      }
    }

    if ($link) {
      $this->_afterConnect($link);
      return $link;
    } else {
      return false;
    }
  }

  /**
   * Database handle getter
   * @return stdClass
   */
  protected final function _getDatabaseHandle()
  {
    if ($this->_databaseHandle) {
      return $this->_databaseHandle;
    }
    if ($this->_databaseHandle = $this->_tryToConnect()) {
      return $this->_databaseHandle;
    } else {
      exit($this->_errorMsg('Can not connect to DB'));
    }
  }

  /**
   * Close DB handle and set it to null; used in reconnect attempts
   * @return void
   */
  protected final function _releaseHandle()
  {
    if ($this->_databaseHandle) {
      $this->_closeHandle($this->_databaseHandle);
    }
    $this->_databaseHandle = null;
  }

  /**
   * Format error message
   * @param string $error Raw error message
   * @return string
   */
  protected final function _errorMsg($error)
  {
    $className = get_class($this);
    return "[$className] MySQL Query Error: $error";
  }

  /**
   * @param string $sql       SQL query
   * @param int    $fetchType Fetch type
   * @param array  $extParams Extended params
   * @return array
   */
  public final function query($sql, $fetchType, $extParams)
  {
    if ($extParams['set_names']) {
      $this->_dbSetNames($extParams['set_names']);
    }
    if ($extParams['disable_checks']) {
      $this->_dbDisableChecks();
    }
    $res = $this->_query($sql, $fetchType, $extParams['fetch_fields']);

    if ($extParams['disable_checks']) {
      $this->_dbEnableChecks();
    }
    return $res;
  }

  /**
   * Disable checks
   * @return void
   */
  private function _dbDisableChecks()
  {
    $this->localQuery("SET @OLD_SQL_MODE=(SELECT @@SESSION.sql_mode), SQL_MODE = (SELECT IF(CAST(SUBSTR(VERSION(), 1,1) AS UNSIGNED) = 8, 'NO_AUTO_VALUE_ON_ZERO', 'NO_AUTO_VALUE_ON_ZERO,NO_AUTO_CREATE_USER') AS `mode`)");
  }

  /**
   * Restore old mode before disable checks
   * @return void
   */
  private function _dbEnableChecks()
  {
    $this->localQuery("SET SESSION SQL_MODE=(SELECT IFNULL(@OLD_SQL_MODE,''))");
  }

  /**
   * @return bool|null|resource
   */
  protected abstract function _connect();

  /**
   * Additional database handle manipulations - e.g. select DB
   * @param  stdClass $handle DB Handle
   * @return void
   */
  protected abstract function _afterConnect($handle);

  /**
   * Close DB handle
   * @param  stdClass $handle DB Handle
   * @return void
   */
  protected abstract function _closeHandle($handle);

  /**
   * @param string $sql sql query
   * @return array
   */
  public abstract function localQuery($sql);

  /**
   * @param string $sql         Sql query
   * @param int    $fetchType   Fetch Type
   * @param bool   $fetchFields Fetch fields metadata
   * @return array
   */
  protected abstract function _query($sql, $fetchType, $fetchFields = false);

  /**
   * @return string|int
   */
  public function getLastInsertId()
  {
    return $this->_insertedId;
  }

  /**
   * @return int
   */
  public function getAffectedRows()
  {
    return $this->_affectedRows;
  }

  /**
   * @param  string $charset Charset
   * @return void
   */
  protected abstract function _dbSetNames($charset);

}

class M1_Pdo extends M1_DatabaseLink
{
  public $noResult = array(
    'delete', 'update', 'move', 'truncate', 'insert', 'set', 'create', 'drop', 'replace', 'start transaction', 'commit'
  );

  /**
   * @return bool|PDO
   */
  protected function _connect()
  {
    try {
      $dsn = 'mysql:dbname=' . $this->_config->dbname . ';host=' . $this->_config->host;
      if ($this->_config->port) {
        $dsn .= ';port='. $this->_config->port;
      }
      if ($this->_config->sock != null) {
        $dsn .= ';unix_socket=' . $this->_config->sock;
      }

      $link = new PDO($dsn, $this->_config->username, $this->_config->password);
      $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      return $link;

    } catch (PDOException $e) {
      return false;
    }
  }

  /**
   * @inheritdoc
   */
  protected function _afterConnect($handle)
  {
  }

  /**
   * @inheritdoc
   */
  public function localQuery($sql)
  {
    $result = array();
    /**
     * @var PDO $databaseHandle
     */
    $databaseHandle = $this->_getDatabaseHandle();
    $sth = $databaseHandle->query($sql);

    foreach ($this->noResult as $statement) {
      if (!$sth || strpos(strtolower(trim($sql)), $statement) === 0) {
        return true;
      }
    }

    while (($row = $sth->fetch(PDO::FETCH_ASSOC)) != false) {
      $result[] = $row;
    }

    return $result;
  }

  /**
   * @inheritdoc
   */
  protected function _query($sql, $fetchType, $fetchFields = false)
  {
    $result = array(
      'result'        => null,
      'message'       => '',
      'fetchedFields' => array()
    );

    /**
     * @var PDO $databaseHandle
     */
    $databaseHandle = $this->_getDatabaseHandle();

    switch ($fetchType) {
      case 3:
        $databaseHandle->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
        break;
      case 2:
        $databaseHandle->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
        break;
      case 1:
      default:
        $databaseHandle->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        break;
    }

    try {
      $res = $databaseHandle->query($sql);
      $this->_affectedRows = $res->rowCount();
      $this->_insertedId = $databaseHandle->lastInsertId();
    } catch (PDOException $e) {
      $result['message'] = $this->_errorMsg($e->getCode() . ', ' . $e->getMessage());
      return $result;
    }

    foreach ($this->noResult as $statement) {
      if (!$res || strpos(strtolower(trim($sql)), $statement) === 0) {
        $result['result'] = true;
        return $result;
      }
    }

    $rows = array();
    while (($row = $res->fetch()) !== false) {
      $rows[] = $row;
    }

    if ($fetchFields) {
      $fetchedFields = array();
      $columnCount = $res->columnCount();
      for ($column = 0; $column < $columnCount; $column++) {
        $fetchedFields[] = $res->getColumnMeta($column);
      }
      $result['fetchedFields'] = $fetchedFields;
    }

    $result['result'] = $rows;

    unset($res);
    return $result;
  }

  /**
   * @inheritdoc
   */
  protected function _closeHandle($handle)
  {
  }

  /**
   * @inheritdoc
   */
  protected function _dbSetNames($charset)
  {
    /**
     * @var PDO $dataBaseHandle
     */
    $dataBaseHandle = $this->_getDatabaseHandle();
    $dataBaseHandle->exec('SET NAMES ' . $dataBaseHandle->quote($charset));
    $dataBaseHandle->exec('SET CHARACTER SET ' . $dataBaseHandle->quote($charset));
    $dataBaseHandle->exec('SET CHARACTER_SET_CONNECTION = ' . $dataBaseHandle->quote($charset));
  }

}

class M1_Mysqli extends M1_DatabaseLink
{
  protected function _connect()
  {
    return @mysqli_connect(
      $this->_config->host,
      $this->_config->username,
      $this->_config->password,
      $this->_config->dbname,
      $this->_config->port ? $this->_config->port : null,
      $this->_config->sock
    );
  }

  /**
   * @param  mysqli $handle DB Handle
   * @return void
   */
  protected function _afterConnect($handle)
  {
    mysqli_select_db($handle, $this->_config->dbname);
  }

  /**
   * @inheritdoc
   */
  public function localQuery($sql)
  {
    $result = array();
    /**
     * @var mysqli $databaseHandle
     */
    $databaseHandle = $this->_getDatabaseHandle();
    $sth = mysqli_query($databaseHandle, $sql);
    if (is_bool($sth)) {
      return $sth;
    }
    while (($row = mysqli_fetch_assoc($sth))) {
      $result[] = $row;
    }
    return $result;
  }

  /**
   * @inheritdoc
   */
  protected function _query($sql, $fetchType, $fetchFields = false)
  {
    $result = array(
      'result'        => null,
      'message'       => '',
      'fetchedFields' => ''
    );

    $fetchMode = MYSQLI_ASSOC;
    switch ($fetchType) {
      case 3:
        $fetchMode = MYSQLI_BOTH;
        break;
      case 2:
        $fetchMode = MYSQLI_NUM;
        break;
      case 1:
        $fetchMode = MYSQLI_ASSOC;
        break;
      default:
        break;
    }

    /**
     * @var mysqli $databaseHandle
     */
    $databaseHandle = $this->_getDatabaseHandle();

    $res = mysqli_query($databaseHandle, $sql);

    $triesCount = 10;
    while (mysqli_errno($databaseHandle) == 2013) {
      if (!$triesCount--) {
        break;
      }
      // reconnect
      $this->_releaseHandle();
      if (isset($_REQUEST['set_names'])) {
        mysqli_set_charset($databaseHandle, $_REQUEST['set_names']);
      }

      // execute query once again
      $res = mysqli_query($databaseHandle, $sql);
    }

    if (($errno = mysqli_errno($databaseHandle)) != 0) {
      $result['message'] = $this->_errorMsg($errno . ', ' . mysqli_error($databaseHandle));
      return $result;
    }

    $this->_affectedRows = mysqli_affected_rows($databaseHandle);
    $this->_insertedId = mysqli_insert_id($databaseHandle);

    if (is_bool($res)) {
      $result['result'] = $res;
      return $result;
    }

    if ($fetchFields) {
      $result['fetchedFields'] = mysqli_fetch_fields($res);
    }


    $rows = array();
    while ($row = mysqli_fetch_array($res, $fetchMode)) {
      $rows[] = $row;
    }

    $result['result'] = $rows;

    mysqli_free_result($res);

    return $result;
  }

  /**
   * @inheritdoc
   */
  protected function _dbSetNames($charset)
  {
    /**
     * @var mysqli $databaseHandle
     */
    $databaseHandle = $this->_getDatabaseHandle();
    mysqli_set_charset($databaseHandle, $charset);
  }

  /**
   * @param  mysqli $handle DB Handle
   * @return void
   */
  protected function _closeHandle($handle)
  {
    mysqli_close($handle);
  }

}

class M1_Config_Adapter implements M1_Platform_Actions
{
  public $host                = 'localhost';
  public $port                = null;
  public $sock                = null;
  public $username            = 'root';
  public $password            = '';
  public $dbname              = '';
  public $tblPrefix           = '';
  public $timeZone            = null;

  public $cartType                 = 'Wordpress';
  public $cartId                   = '';
  public $imagesDir                = '';
  public $categoriesImagesDir      = '';
  public $productsImagesDir        = '';
  public $manufacturersImagesDir   = '';
  public $categoriesImagesDirs     = '';
  public $productsImagesDirs       = '';
  public $manufacturersImagesDirs  = '';

  public $languages   = array();
  public $cartVars    = array();

  /**
   * @return mixed
   */
  public function create()
  {
    $cartType = $this->cartType;
    $className = "M1_Config_Adapter_" . $cartType;

    $obj = new $className();
    $obj->cartType = $cartType;

    return $obj;
  }

  /**
   * @param array $a2cData Data
   *
   * @return mixed
   */
  public function productUpdateAction(array $a2cData)
  {
    return array('error' => 'Action is not supported', 'data' => false);
  }

  /**
   * @param array $a2cData Data
   *
   * @return mixed
   */
  public function sendEmailNotifications(array $a2cData)
  {
    return array('error' => 'Action is not supported', 'data' => false);
  }

  /**
   * @param array $a2cData Data
   *
   * @return mixed
   */
  public function triggerEvents(array $a2cData)
  {
    return array('error' => 'Action is not supported', 'data' => false);
  }

  /**
   * @return mixed
   */
  public function getPlugins()
  {
    return array('error' => 'Action is not supported', 'data' => false);
  }

  /**
   * @inheritDoc
   */
  public function setMetaData(array $a2cData)
  {
    return array('error' => 'Action is not supported', 'data' => false);
  }

  /**
   * @inheritDoc
   */
  public function getTranslations(array $a2cData)
  {
    return array('error' => 'Action is not supported', 'data' => false);
  }

  /**
   * @inheritDoc
   */
  public function setOrderNotes(array $a2cData)
  {
    return array('error' => 'Action is not supported', 'data' => false);
  }

  /**
   * @inheritDoc
   */
  public function getActiveModules(array $a2cData)
  {
    return array('error' => 'Action is not supported', 'data' => false);
  }

  /**
   * @inheritDoc
   */
  public function getImagesUrls(array $a2cData)
  {
    return array('error' => 'Action is not supported', 'data' => false);
  }

  /**
   * @inheritDoc
   */
  public function orderUpdate(array $a2cData)
  {
    return array('error' => 'Action is not supported', 'data' => false);
  }

  /**
   * Get Card ID string from request parameters
   * @return string
   */
  protected function _getRequestCartId()
  {
    return isset($_POST['cart_id']) ? sanitize_text_field($_POST['cart_id']) : '';
  }

  /**
   * @param $cartType
   * @return string
   */
  public function getAdapterPath($cartType)
  {
    return A2CBC_STORE_BASE_DIR . A2CBC_BRIDGE_DIRECTORY_NAME . DIRECTORY_SEPARATOR
      . "app" . DIRECTORY_SEPARATOR
      . "class" . DIRECTORY_SEPARATOR
      . "config_adapter" . DIRECTORY_SEPARATOR . $cartType . ".php";
  }

  /**
   * @param $source
   */
  public function setHostPort($source)
  {
    $source = trim($source);

    if ($source == '') {
      $this->host = 'localhost';
      return;
    }

    if (strpos($source, '.sock') !== false) {
      $socket = ltrim($source, 'localhost:');
      $socket = ltrim($socket, '127.0.0.1:');

      $this->host = 'localhost';
      $this->sock = $socket;

      return;
    }

    $conf = explode(":", $source);

    if (isset($conf[0]) && isset($conf[1])) {
      $this->host = $conf[0];
      $this->port = $conf[1];
    } elseif ($source[0] == '/') {
      $this->host = 'localhost';
      $this->port = $source;
    } else {
      $this->host = $source;
    }
  }

  /**
   * @return bool|M1_Mysqli|M1_Pdo
   */
  public function connect()
  {
    if (extension_loaded('pdo_mysql')) {
      $link = new M1_Pdo($this);
    } elseif (function_exists('mysqli_connect')) {
      $link = new M1_Mysqli($this);
    } else {
      $link = false;
    }

    return $link;
  }

  /**
   * @param $field
   * @param $tableName
   * @param $where
   * @return string
   */
  public function getCartVersionFromDb($field, $tableName, $where)
  {
    global $wpdb;

    $version = '';
    $globalTables = ['users', 'usermeta', 'blogs', 'blogmeta', 'signups', 'site', 'sitemeta', 'sitecategories', 'registration_log'];

    if (in_array($tableName, $globalTables)) {
      $tblPrefix = isset($wpdb->base_prefix) ? $wpdb->base_prefix : $this->tblPrefix;
    } else {
      $tblPrefix = $this->tblPrefix;
    }

    $link = $this->connect();
    if (!$link) {
      return '[ERROR] MySQL Query Error: Can not connect to DB';
    }

    $result = $link->localQuery("
      SELECT " . $field . " AS version
      FROM " . $tblPrefix . $tableName . "
      WHERE " . $where
    );

    if (is_array($result) && isset($result[0]['version'])) {
      $version = $result[0]['version'];
    }

    return $version;
  }
}

class M1_Bridge
{
  /**
   * @var M1_DatabaseLink|null
   */
  protected $_link  = null; //mysql connection link
  public $config    = null; //config adapter

  /**
   * @var WP_REST_Request $request
   */
  public $request;

  /**
   * Bridge constructor
   *
   * M1_Bridge constructor.
   * @param $config
   */
  public function __construct(M1_Config_Adapter $config, WP_REST_Request $request)
  {
    $this->config = $config;
    $this->request = $request;

    if ($this->getAction() != "savefile") {
      $this->_link = $this->config->connect();
    }
  }

  /**
   * @return mixed
   */
  public function getTablesPrefix()
  {
    return $this->config->tblPrefix;
  }

  /**
   * @return M1_DatabaseLink|null
   */
  public function getLink()
  {
    return $this->_link;
  }

  /**
   * @return mixed|string
   */
  private function getAction()
  {
    if (isset($_POST['action'])) {
      return str_replace('.', '', sanitize_text_field($_POST['action']));
    }

    return '';
  }

  public function run()
  {
    $action = $this->getAction();

    if ($action == "checkbridge") {
      return 'BRIDGE_OK';
    }

    if (isset($_GET['token'])) {
      return 'ERROR: Field token is not correct';
    }

    if (empty($_POST)) {
      return 'BRIDGE INSTALLED.<br /> Version: ' . A2CBC_BRIDGE_VERSION;
    }

    if ($action == "update") {
      $this->_checkPossibilityUpdate();
    }

    $className = "M1_Bridge_Action_" . ucfirst($action);
    if (!class_exists($className)) {
      return 'ACTION_DO_NOT EXIST' . PHP_EOL;
    }

    $actionObj = new $className();
    @$actionObj->cartType = @$this->config->cartType;
    $res = $actionObj->perform($this);
    $this->_destroy();

    return $res;
  }

  private function _destroy()
  {
    $this->_link = null;
  }

  private function _checkPossibilityUpdate()
  {
    if (!is_writable(__DIR__)) {
      return "ERROR_BRIDGE_DIR_IS_NOT_WRITABLE";
    }

    if (!is_writable(__FILE__)) {
      return "ERROR_BRIDGE_IS_NOT_WRITABLE";
    }
  }

  /**
   * Remove php comments from string
   * @param string $str
   */
  public static function removeComments($str)
  {
    $result  = '';
    $commentTokens = array(T_COMMENT, T_DOC_COMMENT);
    $tokens = token_get_all($str);

    foreach ($tokens as $token) {
      if (is_array($token)) {
        if (in_array($token[0], $commentTokens))
          continue;
        $token = $token[1];
      }
      $result .= $token;
    }

    return $result;
  }

  /**
   * @param $str
   * @param string $constNames
   * @param bool $onlyString
   * @return array
   */
  public static function parseDefinedConstants($str, $constNames = '\w+', $onlyString = true )
  {
    $res = array();
    $pattern = '/define\s*\(\s*[\'"](' . $constNames . ')[\'"]\s*,\s*'
      . ($onlyString ? '[\'"]' : '') . '(.*?)' . ($onlyString ? '[\'"]' : '') . '\s*\)\s*;/';

    preg_match_all($pattern, $str, $matches);

    if (isset($matches[1]) && isset($matches[2])) {
      foreach ($matches[1] as $key => $constName) {
        $res[$constName] = $matches[2][$key];
      }
    }

    return $res;
  }

}


/**
 * Class M1_Config_Adapter_Wordpress
 */
class M1_Config_Adapter_Wordpress extends M1_Config_Adapter
{

  const ERROR_CODE_SUCCESS = 0;
  const ERROR_CODE_ENTITY_NOT_FOUND = 1;
  const ERROR_CODE_INTERNAL_ERROR = 2;

  private $_multiSiteEnabled = false;
  private $_pluginName = '';

  /**
   * M1_Config_Adapter_Wordpress constructor.
   */
  public function __construct()
  {
    $this->_tryLoadConfigs();

    $getActivePlugin = function(array $cartPlugins) {
      foreach ($cartPlugins as $plugin) {
        if ($cartId = $this->_getRequestCartId()) {
          if ($cartId == 'Woocommerce' && strpos($plugin, 'woocommerce.php') !== false) {
            return 'woocommerce';
          } elseif ($cartId == 'WPecommerce' && (strpos($plugin, 'wp-e-commerce') === 0 || strpos($plugin, 'wp-ecommerce') === 0)) {
            return 'wp-e-commerce';
          }
        } else {
          if (strpos($plugin, 'woocommerce.php') !== false) {
            return 'woocommerce';
          } elseif (strpos($plugin, 'wp-e-commerce') === 0 || strpos($plugin, 'wp-ecommerce') === 0) {
            return 'wp-e-commerce';
          }
        }
      };

      return false;
    };

    $activePlugin = false;
    $wpTblPrefix = $this->tblPrefix;

    if ($this->_multiSiteEnabled) {
      $cartPluginsNetwork = $this->getCartVersionFromDb(
        "meta_value", "sitemeta", "meta_key = 'active_sitewide_plugins'"
      );

      if ($cartPluginsNetwork) {
        $cartPluginsNetwork = unserialize($cartPluginsNetwork);
        $activePlugin = $getActivePlugin(array_keys($cartPluginsNetwork));
      }

      if ($activePlugin === false) {
        if ($link = $this->connect()) {
          $blogs = $link->localQuery('SELECT blog_id FROM ' . $this->tblPrefix . 'blogs');
          if ($blogs) {
            foreach ($blogs as $blog) {
              if ($blog['blog_id'] > 1) {
                $this->tblPrefix = $this->tblPrefix . $blog['blog_id'] . '_';
              }

              $cartPlugins = $this->getCartVersionFromDb("option_value", "options", "option_name = 'active_plugins'");
              if ($cartPlugins) {
                $activePlugin = $getActivePlugin(unserialize($cartPlugins));
              }

              if ($activePlugin) {
                break;
              } else {
                $this->tblPrefix = $wpTblPrefix;
              }
            }
          }
        } else {
          return '[ERROR] MySQL Query Error: Can not connect to DB';
        }
      }
    } else {
      $cartPlugins = $this->getCartVersionFromDb("option_value", "options", "option_name = 'active_plugins'");
      if ($cartPlugins) {
        $activePlugin = $getActivePlugin(unserialize($cartPlugins));
      }
    }

    if ($activePlugin == 'woocommerce') {
      $this->_setWoocommerceData();
    } elseif($activePlugin == 'wp-e-commerce') {
      $this->_setWpecommerceData();
    } else {
      return "CART_PLUGIN_IS_NOT_DETECTED";
    }

    $this->_pluginName = $activePlugin;
    $this->tblPrefix = $wpTblPrefix;
  }

  protected function _setWoocommerceData()
  {
    $this->cartId = "Woocommerce";
    $version = $this->getCartVersionFromDb("option_value", "options", "option_name = 'woocommerce_db_version'");

    if ($version != '') {
      $this->cartVars['dbVersion'] = $version;
    }

    $this->cartVars['categoriesDirRelative'] = 'images/categories/';
    $this->cartVars['productsDirRelative'] = 'images/products/';
  }

  /**
   * @return void
   */
  private function _resetGlobalVars()
  {
    foreach($GLOBALS as $varname => $value)
    {
      global $$varname; //$$ is no mistake here

      $$varname = $value;
    }
  }

  protected function _setWpecommerceData()
  {
    $this->cartId = "Wpecommerce";
    $version = $this->getCartVersionFromDb("option_value", "options", "option_name = 'wpsc_version'");
    if ($version != '') {
      $this->cartVars['dbVersion'] = $version;
    } else {
      $filePath = A2CBC_STORE_BASE_DIR . "wp-content" . DIRECTORY_SEPARATOR . "plugins" . DIRECTORY_SEPARATOR
        . "wp-shopping-cart" . DIRECTORY_SEPARATOR . "wp-shopping-cart.php";
      if (file_exists($filePath)) {
        $conf = file_get_contents ($filePath);
        preg_match("/define\('WPSC_VERSION.*/", $conf, $match);
        if (isset($match[0]) && !empty($match[0])) {
          preg_match("/\d.*/", $match[0], $project);
          if (isset($project[0]) && !empty($project[0])) {
            $version = $project[0];
            $version = str_replace(array(" ","-","_","'",");",")",";"), "", $version);
            if ($version != '') {
              $this->cartVars['dbVersion'] = strtolower($version);
            }
          }
        }
      }
    }

    if (file_exists(A2CBC_STORE_BASE_DIR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'shopp' . DIRECTORY_SEPARATOR . 'Shopp.php')
      || file_exists(A2CBC_STORE_BASE_DIR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'wp-e-commerce' . DIRECTORY_SEPARATOR . 'editor.php')) {
      $this->imagesDir = 'wp-content' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'wpsc' . DIRECTORY_SEPARATOR;
      $this->categoriesImagesDir    = $this->imagesDir.'category_images' . DIRECTORY_SEPARATOR;
      $this->productsImagesDir      = $this->imagesDir.'product_images' . DIRECTORY_SEPARATOR;
      $this->manufacturersImagesDir = $this->imagesDir;
    } elseif (file_exists(A2CBC_STORE_BASE_DIR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'wp-e-commerce' . DIRECTORY_SEPARATOR . 'wp-shopping-cart.php')) {
      $this->imagesDir = 'wp-content' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . '';
      $this->categoriesImagesDir    = $this->imagesDir.'wpsc' . DIRECTORY_SEPARATOR . 'category_images' . DIRECTORY_SEPARATOR;
      $this->productsImagesDir      = $this->imagesDir;
      $this->manufacturersImagesDir = $this->imagesDir;
    } else {
      $this->imagesDir = 'images' . DIRECTORY_SEPARATOR;
      $this->categoriesImagesDir    = $this->imagesDir;
      $this->productsImagesDir      = $this->imagesDir;
      $this->manufacturersImagesDir = $this->imagesDir;
    }
  }

  /**
   * @return bool
   */
  protected function _tryLoadConfigs()
  {
    global $wpdb;

    try {
      if (defined('DB_NAME') && defined('DB_USER') && defined('DB_HOST')) {
        $this->dbname   = DB_NAME;
        $this->username = DB_USER;
        $this->setHostPort(DB_HOST);
      } else {
        return false;
      }

      if (defined('DB_PASSWORD')) {
        $this->password = DB_PASSWORD;
      } elseif (defined('DB_PASS')) {
        $this->password = DB_PASS;
      } else {
        return false;
      }

      if (defined('WP_CONTENT_DIR')) {
        $this->imagesDir = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'uploads';
      } elseif (defined('UPLOADS')) {
        $this->imagesDir = UPLOADS;
      } else {
        $this->imagesDir = ABSPATH . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'uploads';
      }

      if ($this->_multiSiteEnabled = (defined('MULTISITE') && MULTISITE === true)) {
        if (defined('WP_SITEURL')) {
          $this->cartVars['wp_siteurl'] = WP_SITEURL;
        }

        if (defined('WP_HOME')) {
          $this->cartVars['wp_home'] = WP_HOME;
        }

        if (defined('WP_CONTENT_URL')) {
          $this->cartVars['wp_content_url'] = WP_CONTENT_URL;
        }
      } elseif (defined('WP_CONTENT_URL')) {
        $this->cartVars['wp_content_url'] = WP_CONTENT_URL;
      }

      if (isset($table_prefix)) {
        $this->tblPrefix = $table_prefix;
      } elseif(isset($wpdb->base_prefix)) {
        $this->tblPrefix = $wpdb->base_prefix;
      } elseif (isset($GLOBALS['table_prefix'])) {
        $this->tblPrefix = $GLOBALS['table_prefix'];
      }
    } catch (Exception $e) {
      die('ERROR_READING_STORE_CONFIG_FILE');
    }

    foreach (get_defined_vars() as $key => $val) {
      $GLOBALS[$key] = $val;
    }

    return true;
  }

  /**
   * @param array $a2cData Notifications data
   *
   * @return mixed
   * @throws Exception
   */
  public function sendEmailNotifications(array $a2cData)
  {
    if ($this->_pluginName === 'woocommerce') {
      return $this->_wcEmailNotification($a2cData);
    } else {
      throw new Exception('Action is not supported');
    }
  }

  /**
   * @param array $a2cData Notifications data
   *
   * @return bool
   */
  private function _wcEmailNotification(array $a2cData)
  {
    if (function_exists('switch_to_blog')) {
      switch_to_blog($a2cData['store_id']);
    }

    $emails = WC()->mailer()->get_emails();//init mailer

    foreach ($a2cData['notifications'] as $notification) {
      if (isset($notification['wc_class'])) {
        if (isset($emails[$notification['wc_class']])) {
          call_user_func_array(array($emails[$notification['wc_class']], 'trigger'), $notification['data']);
        } else {
          return false;
        }
      } else {
        do_action($notification['wc_action'], $notification['data']);
      }
    }

    return true;
  }

  /**
   * @inheritDoc
   * @return bool
   */
  public function triggerEvents(array $a2cData)
  {
    if (function_exists('switch_to_blog')) {
      switch_to_blog($a2cData['store_id']);
    }

    foreach ($a2cData['events'] as $event) {
      if ($event['event'] === 'update') {
        switch ($event['entity_type']) {
          case 'product':
            $product = WC()->product_factory->get_product($event['entity_id']);
            if (in_array( 'stock_status', $event['updated_meta'], true)) {
              do_action('woocommerce_product_set_stock_status', $product->get_id(), $product->get_stock_status(), $product);
            }
            if (in_array('stock_quantity', $event['updated_meta'], true)) {
              do_action('woocommerce_product_set_stock', $product);
            }

            do_action('woocommerce_product_object_updated_props', $product, $event['updated_meta']);
            break;
          case 'variant':
            $product = WC()->product_factory->get_product($event['entity_id']);
            if (in_array('stock_status', $event['updated_meta'], true)) {
              do_action('woocommerce_variation_set_stock_status', $event['entity_id'], $product->get_stock_status(), $product);
            }
            if (in_array('stock_quantity', $event['updated_meta'], true)) {
              do_action('woocommerce_variation_set_stock', $product);
            }

            do_action('woocommerce_product_object_updated_props', $product, $event['updated_meta']);
            break;
          case 'order':
            $entity = WC()->order_factory->get_order($event['entity_id']);
            do_action( 'woocommerce_order_status_' . $event['status']['to'], $entity->get_id(), $entity);

            if (isset($event['status']['from'])) {
              do_action('woocommerce_order_status_' . $event['status']['from'] . '_to_' . $event['status']['to'], $entity->get_id(), $entity);
              do_action('woocommerce_order_status_changed', $entity->get_id(), $event['status']['from'], $event['status']['to'], $entity);
            }
        }
      }
    }

    return true;
  }

  /**
   * @inheritDoc
   * @return array
   */
  public function setMetaData(array $a2cData)
  {
    $response = [
      'error_code' => self::ERROR_CODE_SUCCESS,
      'error' => null,
      'result' => array()
    ];

    $reportError = function ($e) use ($response) {
      $response['error'] = $e->getMessage();
      $response['error_code'] = self::ERROR_CODE_INTERNAL_ERROR;

      return $response;
    };

    try {
      if (function_exists('switch_to_blog')) {
        switch_to_blog($a2cData['store_id']);
      }

      $id = (int)$a2cData['entity_id'];

      switch ($a2cData['entity']) {
        case 'product':
          $entity = WC()->product_factory->get_product($id);
          break;
        case 'order':
          $entity = WC()->order_factory->get_order($id);
          break;

        case 'customer':
          $entity = new WC_Customer($id);
          break;
      }

      if (!$entity) {
        $response['error_code'] = self::ERROR_CODE_ENTITY_NOT_FOUND;
        $response['error'] = 'Entity not found';
      } else {
        if (isset($a2cData['meta'])) {
          foreach ($a2cData['meta'] as $key => $value) {
            $entity->add_meta_data($key, $value, true);
          }
        }

        if (isset($a2cData['unset_meta'])) {
          foreach ($a2cData['unset_meta'] as $key) {
            $entity->delete_meta_data($key);
          }
        }

        if (isset($a2cData['meta']) || isset($a2cData['unset_meta'])) {
          $entity->save();

          if (isset($a2cData['meta'])) {
            global $wpdb;
            $wpdb->set_blog_id($a2cData['store_id']);
            $keys = implode( "', '", $wpdb->_escape(array_keys($a2cData['meta'])));

            switch ($a2cData['entity']) {
              case 'product':
              case 'order':
                $qRes = $wpdb->get_results("
                SELECT pm.meta_id, pm.meta_key, pm.meta_value
                FROM {$wpdb->postmeta} AS pm
                WHERE pm.post_id = {$id}
                  AND pm.meta_key IN ('{$keys}')"
                );
                break;

              case 'customer':
                $qRes = $wpdb->get_results("
                SELECT um.umeta_id AS 'meta_id', um.meta_key, um.meta_value
                FROM {$wpdb->usermeta} AS um
                WHERE um.user_id = {$id}
                  AND um.meta_key IN ('{$keys}')"
                );

                break;
            }

            $response['result']['meta'] = $qRes;
          }

          if (isset($a2cData['unset_meta'])) {
            foreach ($a2cData['unset_meta'] as $key) {
              $response['result']['removed_meta'][$key] = !(bool)$entity->get_meta($key);
            }
          }
        }
      }
    } catch (Exception $e) {
      return $reportError($e);
    } catch (Throwable $e) {
      return $reportError($e);
    }

    return $response;
  }

  /**
   * @inheritDoc
   * @return array
   */
  public function getTranslations(array $a2cData)
  {
    $response = [
      'error_code' => self::ERROR_CODE_SUCCESS,
      'error' => null,
      'result' => array()
    ];

    $reportError = function ($e) use ($response) {
      $response['error'] = $e->getMessage();
      $response['error_code'] = self::ERROR_CODE_INTERNAL_ERROR;

      return $response;
    };

    try {
      if (function_exists('switch_to_blog')) {
        switch_to_blog($a2cData['store_id']);
      }

      foreach ($a2cData['strings'] as $key => $stringData) {
        $response['result'][$key] = __($stringData['id'], $stringData['domain']);
      }
    } catch (Exception $e) {
      return $reportError($e);
    } catch (Throwable $e) {
      return $reportError($e);
    }

    return $response;
  }

  /**
   * @inheritDoc
   * @return array
   */
  public function setOrderNotes(array $a2cData)
  {
    $response = array(
      'error_code' => self::ERROR_CODE_SUCCESS,
      'error' => null,
      'result' => array()
    );

    $reportError = function ($e) use ($response) {
      $response['error'] = $e->getMessage();
      $response['error_code'] = self::ERROR_CODE_INTERNAL_ERROR;

      return $response;
    };

    $getAdminId = function () {
      global $wpdb;

      $wpUserSearch = $wpdb->get_results("SELECT ID FROM {$wpdb->users} ORDER BY ID");
      $adminId = false;

      foreach ($wpUserSearch as $userId) {
        $currentUser = get_userdata($userId->ID);

        if (!empty($currentUser->user_level) && $currentUser->user_level >= 8) {//levels 8, 9 and 10 are admin
          $adminId = $userId->ID;
          break;
        }
      }

      return $adminId;
    };

    try {
      if (function_exists('switch_to_blog')) {
        switch_to_blog($a2cData['store_id']);
      }

      $order = WC()->order_factory->get_order((int)$a2cData['order_id']);

      if (!$order) {
        $response['error_code'] = self::ERROR_CODE_ENTITY_NOT_FOUND;
        $response['error'] = 'Entity not found';
      } else {
        if (empty($a2cData['from'])) {
          /* translators: %s: new order status */
          $transition_note = sprintf(__('Order status set to %s.', 'woocommerce'), wc_get_order_status_name($a2cData['to']));

          if (empty($a2cData['added_by_user'])) {
            $order->add_order_note($transition_note);
          } else {
            if ($adminId = $getAdminId()) {
              wp_set_current_user($adminId);
            }

            $order->add_order_note($transition_note, 0, true);
          }
        } else {
          /* translators: 1: old order status 2: new order status */
          $transition_note = sprintf(
            __('Order status changed from %1$s to %2$s.', 'woocommerce'),
            wc_get_order_status_name($a2cData['from']),
            wc_get_order_status_name($a2cData['to'])
          );

          if (empty($a2cData['added_by_user'])) {
            $order->add_order_note($transition_note);
          } else {
            if ($adminId = $getAdminId()) {
              wp_set_current_user($adminId);
            }

            $order->add_order_note($transition_note, 0, true);
          }
        }
      }
    } catch (Exception $e) {
      return $reportError($e);
    } catch (Throwable $e) {
      return $reportError($e);
    }

    return $response;
  }

  /**
   * @param array $a2cData
   *
   * @return array
   */
  public function getImagesUrls(array $a2cData)
  {
    $response = array(
      'error_code' => self::ERROR_CODE_SUCCESS,
      'error' => null,
      'result' => array()
    );

    $reportError = function ($e) use ($response) {
      $response['error'] = $e->getMessage();
      $response['error_code'] = self::ERROR_CODE_INTERNAL_ERROR;

      return $response;
    };

    try {
      foreach ($a2cData as $imagesCollection) {
        if (function_exists('switch_to_blog')) {
          switch_to_blog($imagesCollection['store_id']);
        }

        $images = array();
        foreach ($imagesCollection['ids'] as $id) {
          $images[$id] = wp_get_attachment_url($id);
        }

        $response['result'][$imagesCollection['store_id']] = array('images' => $images);
      }
    } catch (Exception $e) {
      return $reportError($e);
    } catch (Throwable $e) {
      return $reportError($e);
    }

    return $response;
  }

  /**
   * @return array
   */
  public function getPlugins()
  {
    $response = array(
      'error_code' => self::ERROR_CODE_SUCCESS,
      'error' => null,
      'result' => array()
    );

    $reportError = function ($e) use ($response) {
      $response['error'] = $e->getMessage();
      $response['error_code'] = self::ERROR_CODE_INTERNAL_ERROR;

      return $response;
    };

    try {
      if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $response['result']['plugins'] = get_plugins();
      } else {
        $response['result']['plugins'] = get_plugins();
      }

    } catch (Exception $e) {
      return $reportError($e);
    } catch (Throwable $e) {
      return $reportError($e);
    }

    return $response;
  }

  /**
   * @param array $a2cData Data
   *
   * @return array
   */
  public function orderUpdate(array $a2cData)
  {
    $response = array(
      'error_code' => self::ERROR_CODE_SUCCESS,
      'error' => null,
      'result' => array()
    );

    $reportError = function ($e) use ($response) {
      $response['error'] = $e->getMessage();
      $response['error_code'] = self::ERROR_CODE_INTERNAL_ERROR;

      return $response;
    };

    try {
      foreach (get_defined_vars() as $key => $val) {
        $GLOBALS[$key] = $val;
      }

      $this->_resetGlobalVars();

      if (function_exists('switch_to_blog')) {
        switch_to_blog($a2cData['order']['store_id']);
      }

      $entity = WC()->order_factory->get_order($a2cData['order']['id']);

      if (isset($a2cData['order']['notify_customer']) && $a2cData['order']['notify_customer'] === false) {
        $disableEmails = function () {
          return false;
        };

        add_filter('woocommerce_email_enabled_customer_completed_order', $disableEmails, 100, 0);
        add_filter('woocommerce_email_enabled_customer_invoice', $disableEmails, 100, 0);
        add_filter('woocommerce_email_enabled_customer_note', $disableEmails, 100, 0);
        add_filter('woocommerce_email_enabled_customer_on_hold_order', $disableEmails, 100, 0);
        add_filter('woocommerce_email_enabled_customer_processing_order', $disableEmails, 100, 0);
        add_filter('woocommerce_email_enabled_customer_refunded_order', $disableEmails, 100, 0);
      }

      if (isset($a2cData['order']['status']['id'])) {
        $entity->set_status(
          $a2cData['order']['status']['id'],
          isset($a2cData['order']['status']['transition_note']) ? $a2cData['order']['status']['transition_note'] : '',
          true
        );
      }

      if (isset($a2cData['order']['completed_date'])) {
        $entity->set_date_completed($a2cData['order']['completed_date']);
      }

      if (isset($a2cData['order']['admin_comment'])) {
        wp_set_current_user($a2cData['order']['admin_comment']['admin_user_id']);
        $entity->add_order_note($a2cData['order']['admin_comment']['text'], 1);
      }

      if (isset($a2cData['order']['customer_note'])) {
        $entity->set_customer_note($a2cData['order']['customer_note']);
      }

      if (isset($a2cData['order']['admin_private_comment'])) {
        wp_set_current_user($a2cData['order']['admin_private_comment']['admin_user_id']);
        $entity->add_order_note($a2cData['order']['admin_private_comment']['text'], 0, true);
      }

      $entity->save();

      $response['result'] = true;

    } catch (Exception $e) {
      return $reportError($e);
    } catch (Throwable $e) {
      return $reportError($e);
    }

    return $response;
  }

}

/**
 * Class M1_Bridge_Action_Send_Notification
 */
class M1_Bridge_Action_Send_Notification
{

  /**
   * @param M1_Bridge $bridge
   */
  public function perform(M1_Bridge $bridge)
  {
    $response = array(
      'error' => false,
      'code' => null,
      'message' => null,
    );

    $cartId = sanitize_text_field($_POST['cartId']);

    try {
      switch ($cartId) {
        case 'Woocommerce':

          $msgClasses = sanitize_text_field($_POST['data_notification']['msg_classes']);
          $callParams = sanitize_text_field($_POST['data_notification']['msg_params']);
          $storeId = sanitize_text_field($_POST['data_notification']['store_id']);
          if (function_exists('switch_to_blog')) {
            switch_to_blog($storeId);
          }
          $emails = wc()->mailer()->get_emails();
          foreach ($msgClasses as $msgClass) {
            if (isset($emails[$msgClass])) {
              call_user_func_array(array($emails[$msgClass], 'trigger'), $callParams[$msgClass]);
            }
          }
          return json_encode($response);
      }
    } catch (Exception $e) {
      $response['error'] = true;
      $response['code'] = $e->getCode();
      $response['message'] = $e->getMessage();

      return json_encode($response);
    }
  }
}

/**
 * Class M1_Bridge_Action_Savefile
 */
class M1_Bridge_Action_Savefile
{
  protected $_imageType = null;
  protected $_mageLoaded = false;

  /**
   * @param $bridge
   */
  public function perform(M1_Bridge $bridge)
  {
    $source      = sanitize_url($_POST['src']);
    $destination = sanitize_text_field($_POST['dst']);
    $width       = (int)sanitize_key($_POST['width']);
    $height      = (int)sanitize_key($_POST['height']);

    return $this->_saveFile($source, $destination, $width, $height);
  }

  /**
   * @param $source
   * @param $destination
   * @param $width
   * @param $height
   * @param string $local
   * @return string
   */
  public function _saveFile($source, $destination, $width, $height)
  {
    $destinationParts = explode('.', $destination);
    if (
      !in_array(end($destinationParts), ['png', 'jpg', 'jpeg', 'gif'])
    ) {
      return 'ERROR_INVALID_FILE_EXTENSION';
    }

    if (!preg_match('/^https?:\/\//i', $source)) {
      $result = $this->_createFile($source, $destination);
    } else {
      $result = $this->_saveFileCurl($source, $destination);
    }

    if ($result != "OK") {
      return $result;
    }

    $destination = A2CBC_STORE_BASE_DIR . $destination;

    if ($width != 0 && $height != 0) {
      $this->_scaled2( $destination, $width, $height );
    }

    return $result;
  }

  /**
   * @param $filename
   * @param bool $skipJpg
   * @return bool|resource
   */
  private function _loadImage($filename, $skipJpg = true)
  {
    $imageInfo = @getimagesize($filename);
    if ($imageInfo === false) {
      return false;
    }

    $this->_imageType = $imageInfo[2];

    switch ($this->_imageType) {
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($filename);
        break;
      case IMAGETYPE_GIF:
        $image = imagecreatefromgif($filename);
        break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($filename);
        break;
      default:
        return false;
    }

    if ($skipJpg && ($this->_imageType == IMAGETYPE_JPEG)) {
      return false;
    }

    return $image;
  }

  /**
   * @param $image
   * @param $filename
   * @param int $imageType
   * @param int $compression
   * @return bool
   */
  private function _saveImage($image, $filename, $imageType = IMAGETYPE_JPEG, $compression = 85)
  {
    $result = true;
    if ($imageType == IMAGETYPE_JPEG) {
      $result = imagejpeg($image, $filename, $compression);
    } elseif ($imageType == IMAGETYPE_GIF) {
      $result = imagegif($image, $filename);
    } elseif ($imageType == IMAGETYPE_PNG) {
      $result = imagepng($image, $filename);
    }

    imagedestroy($image);

    return $result;
  }

  /**
   * @param $source
   * @param $destination
   * @return string
   */
  private function _createFile($source, $destination)
  {
    if ($this->_createDir(dirname($destination)) !== false) {

      $body = base64_decode($source);
      if ($body === false || file_put_contents($destination, $body) === false) {
        return '[BRIDGE ERROR] File save failed!';
      }

      return 'OK';
    }

    return '[BRIDGE ERROR] Directory creation failed!';
  }

  /**
   * @param $source
   * @param $destination
   * @return string
   */
  private function _saveFileCurl($source, $destination)
  {
    $source = $this->_escapeSource($source);
    if ($this->_createDir(dirname($destination)) !== false) {

      $headers = [
        'Accept-Language:*',
        'User-Agent: "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1"'
      ];

      $dst = @fopen($destination, "wb");
      if ($dst === false) {
        return "[BRIDGE ERROR] Can't create  $destination!";
      }

      $request = wp_remote_get(
        $source,
        [
          'method'      => 'GET',
          'timeout'     => 60,
          'redirection' => 5,
          'httpversion' => '1.0',
          'blocking'    => true,
          'stream'      => true,
          'filename'    => $destination,
          'headers'     => $headers,
          'cookies'     => [],
        ]
      );

      if (wp_remote_retrieve_response_code($request) != 200) {
        return "[BRIDGE ERROR] Bad response received from source, HTTP code wp_remote_retrieve_response_code($request)!";
      }

      return "OK";

    } else {
      return "[BRIDGE ERROR] Directory creation failed!";
    }
  }

  /**
   * @param $source
   * @return mixed
   */
  private function _escapeSource($source)
  {
    return str_replace(" ", "%20", $source);
  }

  /**
   * @param $dir
   * @return bool
   */
  private function _createDir($dir)
  {
    if (defined('WP_CONTENT_DIR')) {
      $uploadsPath= WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'uploads';
    } elseif (defined('UPLOADS')) {
      $uploadsPath = UPLOADS;
    } else {
      $uploadsPath = A2CBC_STORE_BASE_DIR . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'uploads';
    }

    $dirParts = explode("/", str_replace($uploadsPath, '', $dir));
    $uploadsPath = rtrim($uploadsPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    foreach ($dirParts as $item) {
      if ($item == '') {
        continue;
      }

      $uploadsPath .= $item . DIRECTORY_SEPARATOR;

      if (!is_dir($uploadsPath)) {
        $res = @mkdir($uploadsPath, 0755);

        if (!$res) {
          return false;
        }
      }
    }

    return true;
  }

  /**
   * scaled2 method optimizet for prestashop
   *
   * @param $destination
   * @param $destWidth
   * @param $destHeight
   * @return string
   */
  private function _scaled2($destination, $destWidth, $destHeight)
  {
    $method = 0;

    $sourceImage = $this->_loadImage($destination, false);

    if ($sourceImage === false) {
      return "IMAGE NOT SUPPORTED";
    }

    $sourceWidth  = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);

    $widthDiff = $destWidth / $sourceWidth;
    $heightDiff = $destHeight / $sourceHeight;

    if ($widthDiff > 1 && $heightDiff > 1) {
      $nextWidth = $sourceWidth;
      $nextHeight = $sourceHeight;
    } else {
      if (intval($method) == 2 || (intval($method) == 0 AND $widthDiff > $heightDiff)) {
        $nextHeight = $destHeight;
        $nextWidth = intval(($sourceWidth * $nextHeight) / $sourceHeight);
        $destWidth = ((intval($method) == 0 ) ? $destWidth : $nextWidth);
      } else {
        $nextWidth = $destWidth;
        $nextHeight = intval($sourceHeight * $destWidth / $sourceWidth);
        $destHeight = (intval($method) == 0 ? $destHeight : $nextHeight);
      }
    }

    $borderWidth = intval(($destWidth - $nextWidth) / 2);
    $borderHeight = intval(($destHeight - $nextHeight) / 2);

    $destImage = imagecreatetruecolor($destWidth, $destHeight);

    $white = imagecolorallocate($destImage, 255, 255, 255);
    imagefill($destImage, 0, 0, $white);

    imagecopyresampled($destImage, $sourceImage, $borderWidth, $borderHeight, 0, 0, $nextWidth, $nextHeight, $sourceWidth, $sourceHeight);
    imagecolortransparent($destImage, $white);

    return $this->_saveImage($destImage, $destination, $this->_imageType, 100) ? "OK" : "CAN'T SCALE IMAGE";
  }
}

/**
 * Class M1_Bridge_Action_Query
 */
class M1_Bridge_Action_Query
{

  /**
   * Extract extended query params from post and request
   */
  public static function requestToExtParams()
  {
    return array(
      'fetch_fields' => (isset($_POST['fetchFields']) && (intval($_POST['fetchFields']) == 1)),
      'set_names' => isset($_REQUEST['set_names']) ? sanitize_text_field($_REQUEST['set_names']) : false,
      'disable_checks' => isset($_REQUEST['disable_checks']) ? boolval($_REQUEST['disable_checks']) : false,
    );
  }

  /**
   * @param M1_Bridge $bridge Bridge instance
   * @return bool
   */
  public function perform(M1_Bridge $bridge)
  {
    if (isset($_POST['query']) && isset($_POST['fetchMode'])) {
      $query = base64_decode(swapLetters($_POST['query']));

      $fetchMode = (int)$_POST['fetchMode'];

      $res = $bridge->getLink()->query($query, $fetchMode, self::requestToExtParams());

      if (is_array($res['result']) || is_bool($res['result'])) {
        $result = serialize(array(
          'res'           => $res['result'],
          'fetchedFields' => @$res['fetchedFields'],
          'insertId'      => $bridge->getLink()->getLastInsertId(),
          'affectedRows'  => $bridge->getLink()->getAffectedRows(),
        ));

        return base64_encode($result);
      } else {
        return base64_encode($res['message']);
      }
    } else {
      return false;
    }
  }
}

class M1_Bridge_Action_Platform_Action
{
  /**
   * @param M1_Bridge $bridge
   */
  public function perform(M1_Bridge $bridge)
  {
    if (isset($_POST['platform_action'], $_POST['data'])
      && $_POST['platform_action']
      && method_exists($bridge->config, $_POST['platform_action'])
    ) {
      $response = array('error' => null, 'data' => null);

      try {
        $data = json_decode(base64_decode(swapLetters($_POST['data'])), true);
        $response['data'] = $bridge->config->{sanitize_text_field($_POST['platform_action'])}($data);
      } catch (Exception $e) {
        $response['error']['message'] = $e->getMessage();
        $response['error']['code'] = $e->getCode();
      } catch (Throwable $e) {
        $response['error']['message'] = $e->getMessage();
        $response['error']['code'] = $e->getCode();
      }

      return json_encode($response);
    } else {
      return json_encode(array('error' => array('message' => 'Action is not supported'), 'data' => null));
    }
  }
}

/**
 * Class M1_Bridge_Action_Phpinfo
 */
class M1_Bridge_Action_Phpinfo
{

  /**
   * @param M1_Bridge $bridge
   */
  public function perform(M1_Bridge $bridge)
  {
    return phpinfo();
  }
}

class M1_Bridge_Action_Multiquery
{

  protected $_lastInsertIds = array();
  protected $_result        = false;

  /**
   * @param M1_Bridge $bridge
   * @return bool|null
   */
  public function perform(M1_Bridge $bridge)
  {
    if (isset($_POST['queries']) && isset($_POST['fetchMode'])) {
      @ini_set("memory_limit","512M");

      $queries = json_decode(base64_decode(swapLetters($_POST['queries'])));
      $count = 0;

      foreach ($queries as $queryId => $query) {

        if ($count++ > 0) {
          $query = preg_replace_callback('/_A2C_LAST_\{([a-zA-Z0-9_\-]{1,32})\}_INSERT_ID_/', array($this, '_replace'), $query);
          $query = preg_replace_callback('/A2C_USE_FIELD_\{([\w\d\s\-]+)\}_FROM_\{([a-zA-Z0-9_\-]{1,32})\}_QUERY/', array($this, '_replaceWithValues'), $query);
        }

        $res = $bridge->getLink()->query($query, (int)$_POST['fetchMode'], M1_Bridge_Action_Query::requestToExtParams());
        if (is_array($res['result']) || is_bool($res['result'])) {

          $queryRes = array(
            'res'           => $res['result'],
            'fetchedFields' => @$res['fetchedFields'],
            'insertId'      => $bridge->getLink()->getLastInsertId(),
            'affectedRows'  => $bridge->getLink()->getAffectedRows(),
          );

          $this->_result[$queryId] = $queryRes;
          $this->_lastInsertIds[$queryId] = $queryRes['insertId'];

        } else {
          $data['error'] = $res['message'];
          $data['failedQueryId'] = $queryId;
          $data['query'] = $query;

          return base64_encode(serialize($data));
        }
      }
      return base64_encode(serialize($this->_result));
    } else {
      return false;
    }
  }

  protected function _replace($matches)
  {
    return $this->_lastInsertIds[$matches[1]];
  }

  protected function _replaceWithValues($matches)
  {
    $values = array();
    if (isset($this->_result[$matches[2]]['res'])) {
      foreach ($this->_result[$matches[2]]['res'] as $row) {
        $values[] = addslashes($row[$matches[1]]);
      }
    }

    return '"' . implode('","', array_unique($values)) . '"';
  }

}

/**
 * Class M1_Bridge_Action_Getconfig
 */
class M1_Bridge_Action_Getconfig
{

  /**
   * @param $val
   * @return int
   */
  private function parseMemoryLimit($val)
  {
    $valInt = (int)$val;
    $last = strtolower($val[strlen($val)-1]);

    switch($last) {
      case 'g':
        $valInt *= 1024;
      case 'm':
        $valInt *= 1024;
      case 'k':
        $valInt *= 1024;
    }

    return $valInt;
  }

  /**
   * @return mixed
   */
  private function getMemoryLimit()
  {
    $memoryLimit = trim(@ini_get('memory_limit'));
    if (strlen($memoryLimit) === 0) {
      $memoryLimit = "0";
    }
    $memoryLimit = $this->parseMemoryLimit($memoryLimit);

    $maxPostSize = trim(@ini_get('post_max_size'));
    if (strlen($maxPostSize) === 0) {
      $maxPostSize = "0";
    }
    $maxPostSize = $this->parseMemoryLimit($maxPostSize);

    $suhosinMaxPostSize = trim(@ini_get('suhosin.post.max_value_length'));
    if (strlen($suhosinMaxPostSize) === 0) {
      $suhosinMaxPostSize = "0";
    }
    $suhosinMaxPostSize = $this->parseMemoryLimit($suhosinMaxPostSize);

    if ($suhosinMaxPostSize == 0) {
      $suhosinMaxPostSize = $maxPostSize;
    }

    if ($maxPostSize == 0) {
      $suhosinMaxPostSize = $maxPostSize = $memoryLimit;
    }

    return min($suhosinMaxPostSize, $maxPostSize, $memoryLimit);
  }

  /**
   * @return bool
   */
  private function isZlibSupported()
  {
    return function_exists('gzdecode');
  }

  /**
   * @param $bridge
   */
  public function perform(M1_Bridge $bridge)
  {
    if (!defined("DEFAULT_LANGUAGE_ISO2")) {
      define("DEFAULT_LANGUAGE_ISO2", ""); //variable for Interspire cart
    }

    try {
      $timeZone = date_default_timezone_get();
    } catch (Exception $e) {
      $timeZone = 'UTC';
    }

    $result = array(
      "images" => array(
        "imagesPath"                => $bridge->config->imagesDir, // path to images folder - relative to store root
        "categoriesImagesPath"      => $bridge->config->categoriesImagesDir,
        "categoriesImagesPaths"     => $bridge->config->categoriesImagesDirs,
        "productsImagesPath"        => $bridge->config->productsImagesDir,
        "productsImagesPaths"       => $bridge->config->productsImagesDirs,
        "manufacturersImagesPath"   => $bridge->config->manufacturersImagesDir,
        "manufacturersImagesPaths"  => $bridge->config->manufacturersImagesDirs,
      ),
      "languages"             => $bridge->config->languages,
      "baseDirFs"             => A2CBC_STORE_BASE_DIR,    // filesystem path to store root
      "bridgeVersion"         => A2CBC_BRIDGE_VERSION,
      "defaultLanguageIso2"   => DEFAULT_LANGUAGE_ISO2,
      "databaseName"          => $bridge->config->dbname,
      "cartDbPrefix"          => $bridge->config->tblPrefix,
      "memoryLimit"           => $this->getMemoryLimit(),
      "zlibSupported"         => $this->isZlibSupported(),
      "cartVars"              => $bridge->config->cartVars,
      "time_zone"             => $bridge->config->timeZone ?: $timeZone
    );

    return serialize($result);
  }

}

/**
 * Class M1_Bridge_Action_GetShipmentProviders
 */
class M1_Bridge_Action_GetShipmentProviders
{

  public function perform(M1_Bridge $bridge)
  {
    $response = array('error' => null, 'data' => null);

    switch ($bridge->config->cartType) {

      case 'Wordpress':

        if ($bridge->config->cartId === 'Woocommerce') {

          if (class_exists('WC_Shipment_Tracking_Actions')) {
            try {
              $st = new WC_Shipment_Tracking_Actions();
              $res = $st->get_providers();
              $data = array();

              foreach ($res as $country => $providers) {
                foreach ($providers as $providerName => $url) {
                  $data[sanitize_title($providerName)] = array(
                    'name' => $providerName,
                    'country' => $country,
                    'url' => $url
                  );
                }
              }

              $response['data'] = $data;

            } catch (Exception $e) {
              $response['error']['message'] = $e->getMessage();
              $response['error']['code'] = $e->getCode();
            }
          } else {
            $response['error']['message'] = 'File does not exist';
          }

        } else {
          $response['error']['message'] = 'Action is not supported';
        }

        break;
      default:
        $response['error']['message'] = 'Action is not supported';
    }

    return json_encode($response);
  }

}

/**
 * Class M1_Bridge_Action_CreateRefund
 */
class M1_Bridge_Action_CreateRefund
{

  /**
   * Check request key
   * @param string $requestKey Request Key
   * @return bool
   */
  private function _checkRequestKey($requestKey)
  {
    $request = wp_remote_post(
      A2CBC_BRIDGE_CHECK_REQUEST_KEY_LINK,
      [
        'method'      => 'POST',
        'timeout'     => 60,
        'redirection' => 5,
        'httpversion' => '1.0',
        'sslverify'   => false,
        http_build_query(array('request_key' => $requestKey, 'store_key' => A2CBC_TOKEN))
      ]
    );

    if (wp_remote_retrieve_response_code($request) != 200) {
      return "[BRIDGE ERROR] Bad response received from source, HTTP code wp_remote_retrieve_response_code($request)!";
    }

    try {
      $res = json_decode($request['body']);
    } catch (Exception $e) {
      return false;
    }

    return isset($res->success) && $res->success;
  }

  /**
   * @param M1_Bridge $bridge
   * @return void
   */
  public function perform(M1_Bridge $bridge)
  {
    $response = array('error' => null, 'data' => null);

    if (!isset($_POST['request_key']) || !$this->_checkRequestKey(sanitize_text_field($_POST['request_key']))) {
      $response['error']['message'] = 'Not authorized';
      echo json_encode($response);
      return;
    }

    $orderId = $_POST['order_id'];
    $isOnline = $_POST['is_online'];
    $refundMessage = isset($_POST['refund_message']) ? sanitize_text_field($_POST['refund_message']) : '';
    $itemsData = json_decode($_POST['items'], true);
    $totalRefund = isset($_POST['total_refund']) ? (float)$_POST['total_refund'] : null;
    $shippingRefund = isset($_POST['shipping_refund']) ? (float)$_POST['shipping_refund'] : null;
    $adjustmentRefund = isset($_POST['adjustment_refund']) ? (float)$_POST['adjustment_refund'] : null;
    $restockItems = isset($_POST['restock_items']) ? filter_var($_POST['restock_items'], FILTER_VALIDATE_BOOLEAN) : false;
    $sendNotifications = isset($_POST['send_notifications']) ? filter_var($_POST['send_notifications'], FILTER_VALIDATE_BOOLEAN) : false;

    try {

      switch ($bridge->config->cartType) {

        case 'Wordpress':

          if ($bridge->config->cartId === 'Woocommerce') {
            $order = wc_get_order($orderId);

            if ($isOnline) {
              if (WC()->payment_gateways()) {
                $paymentGateways = WC()->payment_gateways->payment_gateways();
              }

              if (!(isset($paymentGateways[$order->payment_method]) && $paymentGateways[$order->payment_method]->supports('refunds'))) {
                throw new Exception('Order payment method does not support refunds');
              }
            }

            $refund = wc_create_refund(array(
              'amount' => !is_null($totalRefund) ? (float)$totalRefund : $order->get_remaining_refund_amount(),
              'reason' => $refundMessage,
              'order_id' => $orderId,
              'line_items' => $itemsData,
              'refund_payment' => false, // dont repay refund immediately for better error processing
              'restock_items' => $restockItems
            ));

            if (is_wp_error($refund)) {
              $response['error']['code'] = $refund->get_error_code();
              $response['error']['message'] = $refund->get_error_message();
            } elseif (!$refund) {
              $response['error']['message'] = 'An error occurred while attempting to create the refund';
            }

            if ($response['error']) {
              echo json_encode($response);
              return;
            }

            if ($isOnline) {

              if (WC()->payment_gateways()) {
                $paymentGateways = WC()->payment_gateways->payment_gateways();
              }

              if (isset($paymentGateways[$order->payment_method])
                && $paymentGateways[$order->payment_method]->supports('refunds')
              ) {
                try {
                  $result = $paymentGateways[$order->payment_method]->process_refund($orderId,
                    $refund->get_refund_amount(), $refund->get_refund_reason());
                } catch (Exception $e) {
                  $refund->delete(true); // delete($force_delete = true)
                  throw $e;
                }
                if (is_wp_error($result)) {
                  $refund->delete(true);
                  $response['error']['code'] = $result->get_error_code();
                  $response['error']['message'] = $result->get_error_message();
                } elseif (!$result) {
                  $refund->delete(true);
                  $response['error']['message'] = 'An error occurred while attempting to repay the refund using the payment gateway API';
                } else {
                  $response['data']['refunds'][] = $refund->get_id();
                }
              } else {
                $refund->delete(true);
                $response['error']['message'] = 'Order payment method does not support refunds';
              }
            }

          } else {
            $response['error']['message'] = 'Action is not supported';
          }

          break;

        default:
          $response['error']['message'] = 'Action is not supported';
      }

    } catch (Exception $e) {
      unset($response['data']);
      $response['error']['message'] = $e->getMessage();
      $response['error']['code'] = $e->getCode();
    }

    return json_encode($response);
  }

}

/**
 * Class M1_Bridge_Action_Batchsavefile
 */
class M1_Bridge_Action_Batchsavefile extends M1_Bridge_Action_Savefile
{

  /**
   * @param M1_Bridge $bridge
   */
  public function perform(M1_Bridge $bridge)
  {
    $result = array();

    foreach ($_POST['files'] as $fileInfo) {
      $result[$fileInfo['id']] = $this->_saveFile(
        sanitize_text_field($fileInfo['source']),
        sanitize_text_field($fileInfo['target']),
        (int)$fileInfo['width'],
        (int)$fileInfo['height']
      );
    }

    return serialize($result);
  }

}

/**
 * Class M1_Bridge_Action_Basedirfs
 */
class M1_Bridge_Action_Basedirfs
{

  /**
   * @param M1_Bridge $bridge
   */
  public function perform(M1_Bridge $bridge)
  {
    echo A2CBC_STORE_BASE_DIR;
  }
}

define('A2CBC_BRIDGE_VERSION', '123');
define('A2CBC_BRIDGE_CHECK_REQUEST_KEY_LINK', 'http://app.api2cart.com/request/key/check');
define('A2CBC_BRIDGE_DIRECTORY_NAME', basename(getcwd()));

show_error(0);

require_once 'config.php';

if (!defined('A2CBC_TOKEN')) {
  die('ERROR_TOKEN_NOT_DEFINED');
}

if (strlen(A2CBC_TOKEN) !== 32) {
  die('ERROR_TOKEN_LENGTH');
}

function show_error($status)
{
  if ($status) {
    @ini_set('display_errors', 1);
    if (substr(phpversion(), 0, 1) >= 5) {
      error_reporting(E_ALL & ~E_STRICT);
    } else {
      error_reporting(E_ALL);
    }
  } else {
    @ini_set('display_errors', 0);
    error_reporting(0);
  }
}

/**
 * @param $array
 * @return array|string|stripslashes_array
 */
function stripslashes_array($array)
{
  return is_array($array) ? array_map('stripslashes_array', $array) : stripslashes($array);
}

function exceptions_error_handler($severity, $message, $filename, $lineno) {
  if (error_reporting() === 0) {
    return;
  }

  if (strpos($message, 'Declaration of') === 0) {
    return;
  }

  if (error_reporting() & $severity) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}

set_error_handler('exceptions_error_handler');

/**
 * @return bool|mixed|string
 */
function getPHPExecutable()
{
  $paths = explode(PATH_SEPARATOR, getenv('PATH'));
  $paths[] = PHP_BINDIR;
  foreach ($paths as $path) {
    // we need this for XAMPP (Windows)
    if (isset($_SERVER["WINDIR"]) && strstr($path, 'php.exe') && file_exists($path) && is_file($path)) {
      return $path;
    } else {
      $phpExecutable = $path . DIRECTORY_SEPARATOR . "php" . (isset($_SERVER["WINDIR"]) ? ".exe" : "");
      if (file_exists($phpExecutable) && is_file($phpExecutable)) {
        return $phpExecutable;
      }
    }
  }
  return false;
}

function swapLetters($input) {
  $default = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
  $custom  = "ZYXWVUTSRQPONMLKJIHGFEDCBAzyxwvutsrqponmlkjihgfedcba9876543210+/";

  return strtr($input, $default, $custom);
}

if (version_compare(phpversion(), '7.4', '<') && get_magic_quotes_gpc()) {
  $_COOKIE  = stripslashes_array($_COOKIE);
  $_FILES   = stripslashes_array($_FILES);
  $_GET     = stripslashes_array($_GET);
  $_POST    = stripslashes_array($_POST);
  $_REQUEST = stripslashes_array($_REQUEST);
}
