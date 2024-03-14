<?php
// this is an absolute library path as defined constant
define('ABSOLUTE_EXTERNAL_API_PATH', dirname(__FILE__) . '/');

// include utils file
require ABSOLUTE_EXTERNAL_API_PATH . 'base/SeApiUtils.php';

// include needed files
SeApiUtils::checkInclude(
  array(
    'SeExternalApi',
    'SeResourceFactory',
    'SeApiType',
    'SeCurlGetItemsForShipping',
    'SeCurlMarkShippedItems',
    'SeMarkedItem',
    'SeOrder',
    'SeOrderItem',
    'SeShippingItem',
    'SeShippingProductItem',
    'SeRecipient',
    'SeCurlResource'
  )
);

/**
 * SeApi.php.
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
 * This class implements library entry point. This is the only file which needs to be included in
 * shop extension. Class extends SeExternalApi abstract class.
 *
 * @package    ShippingEasy
 * @subpackage ExternalApi
 * @author     Saturized - The Interactive Agency <office@saturized.com>
 * @version    Release: SeApi.v.0.1
 */
class SeApi extends SeExternalApi
{
  /**
   * object's constructor
   *
   * @param $generatedApiKey, $apiType, $username, $password
   *
   * @return
   */
  public function __construct($generatedApiKey, $apiType=SeApiType::Curl, $username=null, $password=null)
  {
    parent::__construct($generatedApiKey, $apiType, $username, $password);
  }

  /**
   * Method which should implement internal shop login functionality if needed. If user login is not required,
   * then this method should return true by default
   *
   * @param
   *
   * @return boolean
   */
  public function login()
  {
    return true;
  }
}
?>