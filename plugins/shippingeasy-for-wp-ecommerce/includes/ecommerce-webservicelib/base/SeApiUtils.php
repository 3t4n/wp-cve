<?php
/**
 * SeApiUtils.php.
 *
 * PHP Version 5.3.1
 *
 * @category  Utilities
 * @package   Shippingeasy
 * @author    Saturized - The Interactive Agency <office@saturized.com>
 * @copyright 2010 Saturized - The Interactive Agency
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPLv2
 * @version   SVN: $Id: nebojsa $
 */

/**
 * SeApiUtils is used to support different methods.
 *
 * @package    ShippingEasy
 * @subpackage SeApi
 * @author     Saturized - The Interactive Agency <office@saturized.com>
 * @version    Release: SeApiUtils.v.0.1
 */
class SeApiUtils
{
  /**
   * A list of API folders where included files should be searched for.
   *
   * @var array
   */
  private static $folders = array(
    'base',
    'dto',
    'resources'
  );

  /**
   * A list of allowed API resource names.
   *
   * @var array
   */
  private static $resourceNames = array(
    'GetItemsForShipping',
    'MarkShippedItems',
    'Authenticate'
  );

  /**
   * method to check if file/s is/are included and (if it's/they not) -> include it/them.
   * if className parameter is array, then all files in array are included
   * if className parameter is not array, only than className is included
   *
   * @return
   */
  public static function checkInclude($classNames)
  {
    $classNames = is_array($classNames) ? $classNames : array($classNames);

    // parse array of classes
    foreach($classNames as $class)
    {
      if (!class_exists($class))
      {
        foreach(self::$folders as $folder)
        {
          if (file_exists(ABSOLUTE_EXTERNAL_API_PATH . $folder . '/' . $class . '.php'))
          {
            require ABSOLUTE_EXTERNAL_API_PATH . $folder . '/' . $class . '.php';
            break;
          }
        }
      }
    }
  }

  /**
   * method to retrieve GET or POST parameters.
   * default value for parameter is optional.
   *
   * @return $param
   */
  public static function getParameter($name, $default=null)
  {
    $param = null;

    if (isset($_GET[$name]))
    {
      $param = htmlspecialchars($_GET[$name]);
    }
    elseif (isset($_POST[$name]))
    {
      $param = htmlspecialchars($_POST[$name]);
    }

    if (is_null($param))
    {
      $param = $default;
    }

    return $param;
  }

  /**
   * method to output JSON string in case of error.
   *
   * @return
   */
  public static function outputError($message, $code=400)
  {
    $result = array();

    $result['timeStamp'] = time();
    $result['errorMessage'] = $message;

    ob_clean();
    ob_start();
    header("Content-type: application/json", true, $code);
    echo json_encode($result);
    ob_end_flush();
    die;
  }

  /**
   * method to output JSON string in case of success.
   *
   * @return
   */
  public static function outputSuccess($outputArray=array(), $code=200)
  {
    $outputArray['timeStamp'] = time();
    $outputArray['errorMessage'] = '';

    ob_clean();
    ob_start();
    header("Content-type: application/json", true, $code);
    echo json_encode($outputArray);
    ob_end_flush();
    die;
  }

  /**
   * method to return available resource names.
   *
   * @return self::resourceNames
   */
  public static function getResourceNames()
  {
    return self::$resourceNames;
  }

  public static function checkApiKey($platform, $shopDomain, $shopName, $seApiKey, $generatedToken, $isTest=true)
  {
    try
    {
      // get userId by ApiKey
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ShippingEasy-API-Key: ' . $seApiKey));

      curl_setopt($ch, CURLOPT_HTTPGET, 1);

      if ($isTest)
      {
        curl_setopt($ch, CURLOPT_URL, 'https://staging.shippingeasy.com/api/v1/user');
      }
      else
      {
        curl_setopt($ch, CURLOPT_URL, 'https://www.shippingeasy.com/api/v1/user');
      }

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

      // do not allow request to last more than X seconds
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);

      // execute curl
      $body = curl_exec($ch);

      // check if error occured
      if ($body === false)
      {
        $errno = curl_errno($ch);

        if ($errno == 28)
        {
          return false;
        }
      }

      // get response headers
      $headers = curl_getinfo($ch);

      // close curl
      curl_close($ch);

      // check if response is 200
      if ($headers['http_code'] != 200)
      {
        return false;
      }

      $json = json_decode($body);

      if (isset($json->user->Id))
      {
        $userId = $json->user->Id;
      }
      else
      {
        return false;
      }

      // make POST curl request to register store
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-ShippingEasy-API-Key: ' . $seApiKey));

      curl_setopt($ch, CURLOPT_POST, 1);

      $request = array();

      $request['user_id'] = $userId;
      $request['platform'] = $platform;
      $request['credentials'] = array('shop_domain' => $shopDomain, 'store_name' => $shopName, 'api_key' => $generatedToken, 'store_unique_name' => $shopName);

      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

      if ($isTest)
      {
//        curl_setopt($ch, CURLOPT_URL, 'http://ecommerce.shippingeasy.dev/api/register_store');
        curl_setopt($ch, CURLOPT_URL, 'https://ecommerce-stage.shippingeasy.com/api/register_store');
      }
      else
      {
        curl_setopt($ch, CURLOPT_URL, 'https://ecommerce.shippingeasy.com/api/register_store');
      }

      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

      // do not allow request to last more than X seconds
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);

      // execute curl
      $body = curl_exec($ch);

      // check if error occured
      if ($body === false)
      {
        $errno = curl_errno($ch);

        if ($errno == 28)
        {
          return false;
        }
      }

      // get response headers
      $headers = curl_getinfo($ch);

      // close curl
      curl_close($ch);

      // check if response is 200
      if ($headers['http_code'] != 200)
      {
        return false;
      }

      return true;
    }
    catch (Exception $e)
    {
      return false;
    }
  }
}
?>