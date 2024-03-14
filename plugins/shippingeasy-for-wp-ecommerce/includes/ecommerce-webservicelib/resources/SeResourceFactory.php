<?php
SeApiUtils::checkInclude(array('SeApiType'));

/**
 * SeResourceFactory.php.
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
 * This class represents implementation to determine which resource should be instantiated and returned.
 *
 * @package    ShippingEasy
 * @subpackage ExternalApi
 * @author     Saturized - The Interactive Agency <office@saturized.com>
 * @version    Release: SeResourceFactory.v.0.1
 */
class SeResourceFactory
{
  /**
   * Method to determine which resource to initialize.
   *
   * @param $resourceName, $generatedApiKey, $apiType
   * @return SeCurlGetItemsForShippingResource or SeCurlAuthenticateResource or SeCurlMarkShippedItemsResource
   */
  public static function create($resourceName, $generatedApiKey, $apiType=SeApiType::Curl)
  {
    // class name concatenation
    $className = 'Se'.$apiType.$resourceName.'Resource';

    try
    {
      $resourceNames = SeApiUtils::getResourceNames();

      if (!in_array($resourceName, $resourceNames))
      {
        SeApiUtils::outputError('Resource name does not exist (' . $resourceName . ').', 404);
      }

      SeApiUtils::checkInclude($className);

      return new $className($generatedApiKey);
    }
    catch (Exception $e)
    {
      SeApiUtils::outputError('Error during resource execution (' . $resourceName . ').', 500);
    }
  }
}
?>