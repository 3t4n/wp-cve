<?php
SeApiUtils::checkInclude(
  array(
    'SeCurlResource'
  )
);

/**
 * SeCurlAuthenticateResource.php.
 *
 * PHP Version 5.3.1
 *
 * @category  Resource
 * @package   Shippingeasy
 * @author    Saturized - The Interactive Agency <office@saturized.com>
 * @copyright 2010 Saturized - The Interactive Agency
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPLv2
 * @version   SVN: $Id: nebojsa $
 */

/**
 * This class represents an implementation of Authenticate resource.
 *
 * @package    ShippingEasy
 * @subpackage ExternalApi
 * @author     Saturized - The Interactive Agency <office@saturized.com>
 * @version    Release: SeCurlAuthenticateResource.v.0.1
 */
class SeCurlAuthenticateResource extends SeCurlResource
{
  /**
   * object's constructor
   *
   * @param $generatedApiKey
   *
   * @return
   */
  public function __construct($generatedApiKey)
  {
    $this->setRequestMethod('GET');

    $this->setShouldCheckRequest(false);

    $this->fetchMandatoryParameters(array());

    parent::__construct($generatedApiKey);
  }

  /**
   * resource executor
   *
   * @param
   *
   * @return
   */
  public function execute()
  {
    try
    {
      $this->checkRequest();

      $this->setResponseObject(new stdClass());
    }
    catch(Exception $e)
    {
      SeApiUtils::outputError($e->getMessage(), 400);
    }
  }

  /**
   * resource parser
   *
   * @param
   *
   * @return
   */
  public function parse()
  {
    $object = $this->getResponseObject();

    if (is_null($object))
    {
      SeApiUtils::outputError('Response object is not set.', 500);
    }

    try
    {
      $result = array();

      SeApiUtils::outputSuccess($result);
    }
    catch (Exception $e)
    {
      SeApiUtils::outputError('Error while parsing response object. ' . $e->getMessage(), 500);
    }
  }

  /**
   * resource rollback method in case of error
   *
   * @param
   *
   * @return
   */
  public function rollback()
  {

  }
}
?>