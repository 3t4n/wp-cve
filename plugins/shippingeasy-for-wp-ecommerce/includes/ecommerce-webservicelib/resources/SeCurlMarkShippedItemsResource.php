<?php
SeApiUtils::checkInclude(
  array(
    'SeCurlResource',
    'SeCurlMarkShippedItems',
    'SeShippingItem',
    'SeShippingProductItem'
  )
);

/**
 * SeCurlMarkShippedItemsResource.php.
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
 * This class represents an implementation of MarkShippedItems resource.
 *
 * @package    ShippingEasy
 * @subpackage ExternalApi
 * @author     Saturized - The Interactive Agency <office@saturized.com>
 * @version    Release: SeCurlMarkShippedItemsResource.v.0.1
 */
class SeCurlMarkShippedItemsResource extends SeCurlResource
{
  /**
   * Status to use when marking orders as shipped
   *
   * @var int
   */
  public $shippedStatus;

  /**
   * object's constructor
   *
   * @param $generatedApiKey
   *
   * @return
   */
  public function __construct($generatedApiKey)
  {
    $this->setRequestMethod('POST');

    $this->fetchMandatoryParameters(array());
    $this->setShippedStatus();

    parent::__construct($generatedApiKey);
  }

  public function setShippedStatus()
  {
    //get shipped status option from wp
    $settings = (array) get_option('shippingeasy_main_settings');
    $this->shippedStatus = $settings['shipped_status'];
    if (empty($this->shippedStatus) === true)
    {
      $this->shippedStatus = 4;
    }
  }

  public function verifyShipments($shipments)
  {
    global $wpdb;

    foreach ($shipments as $shipment)
    {
      $sql = $wpdb->prepare("SELECT * FROM `".WPSC_TABLE_PURCHASE_LOGS."` WHERE `id`=" .$shipment->orderId);
      $purchase_log = $wpdb->get_results($sql);

      if (empty($purchase_log) === true)
      {
        SeApiUtils::outputError('There is no order with Id: ' . $shipment->orderId, 404);
      }
      if (($purchase_log->processed == $this->shippedStatus) && ($purchase_log->track_id == $shipment->trackingNumber))
      {
        SeApiUtils::outputError('Order with Id: ' . $shipment->orderId.'already marked as shipped', 404);
      }
    }
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
    global $wpdb;

    $object = new SeCurlMarkShippedItems();

    try
    {
      // get variables
      $jsonString = $this->getParameter('postedJson');

      $json = json_decode($jsonString);

      if (is_array($json->shipments))
      {
        // if count is 0, return empty response
        if (count($json->shipments) == 0)
        {
          $object->setTimeStamp(time());

          $object->setErrorMessage('');

          $this->setResponseObject($object);

          return;
        }

        $this->verifyShipments($json->shipments);

        foreach($json->shipments as $shipment)
        {
          $sql = $wpdb->prepare('UPDATE `' . WPSC_TABLE_PURCHASE_LOGS .'` SET `track_id`="'.$shipment->trackingNumber. '", `processed`='.$this->shippedStatus. ' WHERE `id`=' .$shipment->orderId);
          $status = $wpdb->query($sql);

          if($status === false)
          {
            SeApiUtils::outputError('Failled to mark order order with Id ' . $shipment->orderId, 404);
          }
          elseif($status === 0)
          {
            SeApiUtils::outputError('Order with Id ' . $shipment->orderId. ' already marked as shipped.', 404);
          }

          $markedItem = new SeMarkedItem($shipment->orderId, '','');
          $object->addMarkedItem($markedItem);
        }

        $object->setTimeStamp(time());

        $object->setErrorMessage('');

        $this->setResponseObject($object);
      }
      else
      {
        SeApiUtils::outputError('This resource expects valid JSON object as input.', 500);
      }
    }
    catch(Exception $e)
    {
      // at this point we should rollback all inserts which are in $object shipments property
      // because foreach statement has failed before all inserts are finished
      $this->setResponseObject($object);

      $this->rollback();

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

      $result['timeStamp'] = $object->getTimeStamp();

      $result['errorMessage'] = $object->getErrorMessage();

      $result['markedItems'] = array();

      foreach($object->getMarkedItems() as $markedItem)
      {
        $loopItem = array();

        $loopItem['orderId'] = $markedItem->getOrderId();

        array_push($result['markedItems'], $loopItem);
      }

      SeApiUtils::outputSuccess($result);
    }
    catch (Exception $e)
    {
      $this->rollback();

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
    $object = $this->getResponseObject();

    if (is_array($object->getMarkedItems()))
    {
      foreach($object->getMarkedItems() as $markedItem)
      {
      }
    }
  }
}
?>