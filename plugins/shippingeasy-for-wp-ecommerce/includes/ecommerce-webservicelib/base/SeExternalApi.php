<?php
/**
 * SeExternalApi.php.
 *
 * PHP Version 5.3.1
 *
 * @category  SeExternalApi
 * @package   Shippingeasy
 * @author    Saturized - The Interactive Agency <office@saturized.com>
 * @copyright 2010 Saturized - The Interactive Agency
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPLv2
 * @version   SVN: $Id: nebojsa $
 */

/**
 * This class represents abstract implementation of Api library.
 *
 * @package    ShippingEasy
 * @subpackage SeApi
 * @author     Saturized - The Interactive Agency <office@saturized.com>
 * @version    Release: SeExternalApi.v.0.1
 */
abstract class SeExternalApi
{
  /**
   * Generated ApiKey.
   *
   * @var string
   */
  private $generatedApiKey;

  /**
   * Api type.
   *
   * @var string
   */
  private $apiType;

  /**
   * Username.
   *
   * @var string optional
   */
  private $username;

  /**
   * Password.
   *
   * @var string optional
   */
  private $password;

  // this function should implement login functionality if required
  // if login functionality is not required, this function should return true
  abstract public function login();

  /**
   * Returns Api type.
   *
   * @param
   * @return apiType
   */
  private function getApiType()
  {
    return $this->apiType;
  }

  /**
   * Returns generated ApiKey.
   *
   * @param
   * @return generatedApiKey
   */
  private function getGeneratedApiKey()
  {
    return $this->generatedApiKey;
  }

  /**
   * Returns username.
   *
   * @param
   * @return username
   */
  private function getUsername()
  {
    return $this->username;
  }

  /**
   * Returns password.
   *
   * @param
   * @return password
   */
  private function getPassword()
  {
    return $this->password;
  }

  /**
   * object's constructor
   *
   * @param $generatedApiKey, $apiType, $username, $password
   *
   * @return
   */
  public function __construct($generatedApiKey, $apiType=SeApiType::Curl, $username=null, $password=null)
  {
    $this->generatedApiKey = $generatedApiKey;
    $this->apiType = $apiType;
    $this->username = $username;
    $this->password = $password;
  }

  /**
   * Resource executor.
   *
   * @param $resource
   *
   * @return
   */
  public function executeResource($resource)
  {
    try
    {
      if ($this->login())
      {
        $res = explode('-', $resource);

        $resOutput = '';

        foreach($res as $item)
        {
          $resOutput = $resOutput . ucfirst($item);
        }

        $result = SeResourceFactory::create($resOutput, $this->getGeneratedApiKey(), $this->getApiType());
      }
      else
      {
        SeApiUtils::outputError('Cannot login user.', 400);
      }
    }
    catch (Exception $e)
    {
      SeApiUtils::outputError('Request error: ' . $e->getMessage(), 500);
    }
  }
}
?>