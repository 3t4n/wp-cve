<?php
SeApiUtils::checkInclude(
  array(
    'SeCurlResource',
    'SeCurlGetItemsForShipping',
    'SeOrder',
    'SeRecipient',
    'SeOrderItem',
  )
);

/**
 * This class represents an implementation of GetItemsForShipping resource.
 *
 * @package    ShippingEasy
 * @subpackage ExternalApi
 * @author     Saturized - The Interactive Agency <office@saturized.com>
 * @version    Release: SeCurlGetItemsForShippingResource.v.0.1
 */
class SeCurlGetItemsForShippingResource extends SeCurlResource
{
  public $currencyCode;

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
    $this->fetchMandatoryParameters(array());
    $this->currencyCode = SeWpUtils::getCurrencyCode();

    parent::__construct($generatedApiKey);
  }

  /*
   * @return array of purchase logs
   */
  public function getPurchaseLogs()
  {
    global $wpdb;

    $settings = (array) get_option('shippingeasy_main_settings');
    //only get orders with appropriate order status
    $forshipping = $settings['forshipping'];
    if (empty($forshipping) === false)
    {
      $forshipping = implode(',', $forshipping);
    } else
    {
      $forshipping = '2, 3';
    }

    // order pagination
    if(isset($this->headers['X-ShippingEasy-Offset']) === true && is_numeric($this->headers['X-ShippingEasy-Offset']) === true)
    {
      $offset = $this->headers['X-ShippingEasy-Offset'];
    }

    if(isset($this->headers['X-ShippingEasy-PerPage']) === true && is_numeric($this->headers['X-ShippingEasy-PerPage']) === true)
    {
      $perPage = $this->headers['X-ShippingEasy-PerPage'];
    }

    $pagination = '';
    if (isset($offset) && isset($perPage))
    {
      $pagination = "LIMIT $perPage OFFSET $offset";
    }

    $sql = $wpdb->prepare("SELECT * FROM `".WPSC_TABLE_PURCHASE_LOGS."` WHERE `processed` IN (".$forshipping.") ORDER BY `date` DESC " .$pagination);
    $purchase_logs = $wpdb->get_results($sql);

    return $purchase_logs;
  }

  public function getBuyerInfo($orderId)
  {
    global $wpdb;

    $sql = $wpdb->prepare("SELECT `" . WPSC_TABLE_SUBMITED_FORM_DATA . "`.`value`, `" . WPSC_TABLE_CHECKOUT_FORMS .
    "`.`name`, `" . WPSC_TABLE_CHECKOUT_FORMS . "`.`unique_name` FROM `" . WPSC_TABLE_CHECKOUT_FORMS .
    "` LEFT JOIN `" . WPSC_TABLE_SUBMITED_FORM_DATA . "` ON `" . WPSC_TABLE_CHECKOUT_FORMS . "`.id = `"
    . WPSC_TABLE_SUBMITED_FORM_DATA . "`.`form_id` WHERE `" . WPSC_TABLE_SUBMITED_FORM_DATA .
    "`.`log_id`=" . $orderId . " ORDER BY `" . WPSC_TABLE_CHECKOUT_FORMS . "`.`checkout_order`");
    $buyerinfo = $wpdb->get_results($sql, ARRAY_A);

    if (empty($buyerinfo) === true) return $buyerinfo;


    foreach ((array) $buyerinfo as $input_row )
    {
      $shippinginfo[$input_row['unique_name']] = $input_row['value'];
    }

    return $shippinginfo;
  }

  public function getStateCode($stateId)
  {
    global $wpdb;
    $sql = $wpdb->prepare( "SELECT * FROM `wp_wpsc_region_tax` WHERE `id`=%d", $stateId);
    $state = $wpdb->get_results($sql, ARRAY_A);

    if (isset($state[0]['code']))
    {
        return $state[0]['code'];
    } else
    {
        return null;
    }

  }

  public function buildBuyer($order)
  {
    $buyerInfo = $this->getBuyerInfo($order->id);

    $id = $order->user_ID;

    if (empty($buyerInfo['shippingfirstname']) === false)
    {
      $firstName = $buyerInfo['shippingfirstname'];
    } else
    {
      $firstName = $buyerInfo['billingfirstname'];
    }

    if (empty($buyerInfo['shippinglastname']) === false)
    {
      $lastName = $buyerInfo['shippinglastname'];
    } else
    {
      $lastName = $buyerInfo['billinglastname'];
    }

    $phone = $buyerInfo['billingphone'];
    $email = $buyerInfo['billingemail'];

    if (empty($buyerInfo['shippingaddress']) === false)
    {
      $line1 = $buyerInfo['shippingaddress'];
    } else
    {
      $line1 = $buyerInfo['billingaddress'];
    }

    $line2 = '';

    if (empty($buyerInfo['shippingcity']) === false)
    {
      $city = $buyerInfo['shippingcity'];
    } else
    {
      $city = $buyerInfo['billingcity'];
    }

    if (empty($buyerInfo['shippingpostcode']) === false)
    {
      $postalCode = $buyerInfo['shippingpostcode'];
    } else
    {
      $postalCode = $buyerInfo['billingpostcode'];
    }

    if (empty($buyerInfo['shippingstate']) === false)
    {
      $state = $buyerInfo['shippingstate'];
    } else
    {
      $state = $buyerInfo['billingstate'];
    }

    if (empty($buyerInfo['shippingcountry']) === false)
    {
      $country = $buyerInfo['shippingcountry'];
    } else
    {
      $country = $buyerInfo['billingcountry'];
    }

    if (!empty($state))
    {
        $state = $this->getStateCode((int) $state);
    } else
    {
        $state = null;
    }
    $buyer = new SeRecipient($id, $firstName, $lastName, $phone, $email, $line1,
                             $line2, $city, $postalCode, $state, $country);
    return $buyer;
  }

  public function getItems($orderId)
  {
    global $wpdb;

    $sql = $wpdb->prepare( "SELECT * FROM `".WPSC_TABLE_CART_CONTENTS."` WHERE `purchaseid`=%d", $orderId);
    $cart_log = $wpdb->get_results($sql, ARRAY_A);

    return $cart_log;
  }

  public function buildItems($order)
  {
    $cart_log = $this->getItems($order->id);
    $items = array();

    foreach ($cart_log as $item)
    {
/*
      if ($item->uses_shipping === false)
      {
        // The "Disregard Shipping for this product" option is ticked for this item so skip it.
        continue;
      }
*/
      $productInfo = $this->getProductInfo($item['prodid']);
      $paid = ($order->processed == 3);

      $items[] = new SeOrderItem(
                                  $item['id'],
                                  $item['name'],
                                  $item['purchaseid'],
                                  $item['quantity'],
                                  $item['price'] * 10000,
                                  $item['quantity'] * $item['price'] * 10000,
                                  $this->currencyCode,
                                  $paid,
                                  $productInfo['height'],
                                  $productInfo['width'],
                                  $productInfo['length'],
                                  $productInfo['weight'],
                                  $productInfo['is_metric']
                                );
    }

    return $items;
  }

  public function getProductInfo($productId)
  {
    $info = get_post_meta($productId, '_wpsc_product_metadata', true);
    if (empty($info) === true)
    {
      return false;
    }

    $productInfo = array();
    $productInfo = SeWpUtils::convertDimensionsToAppropriateUnits($info['dimensions']);
    $productInfo['weight'] = SeWpUtils::convertWeightToAppropriateUnits($info['weight']);

    // get the mesurement units used
    $seRateSettings = get_option('shippingeasy_rate_settings');
    $isMetric = $seRateSettings['measurement-units'];

    if ($isMetric == 0)
    {
      $productInfo['is_metric'] = false;
    } else
    {
      $productInfo['is_metric'] = true;
    }

    return $productInfo;
  }

  public function getShippingOptions($order)
  {
      $options = new stdClass;
      $options->serviceId = null;
      $options->courier = null;
      $options->serviceName = null;
      $options->shipmentPrice = null;
      $options->shipmentCurrency = $this->currencyCode;
      $options->shippingEasyService = false;

      $shipping_method = $order->shipping_method;
      if ($shipping_method !== 'shippingeasy')
      {
          return $options;
      }

      $options->shippingEasyService = true;
      $options->shipmentPrice = $order->base_shipping*10000;
      $shipping_option = $order->shipping_option;
      $shipping_options = explode('</span>', $shipping_option);
      if (isset($shipping_options[1]))
      {
          $shipping_option = $shipping_options[1];
      }

      $shipping_options = explode(': ', $shipping_option);

      $options->serviceId = null;
      $options->courier = $shipping_options[0];
      $options->serviceName = rtrim($shipping_options[1],'- Eta');

      return $options;
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
      $object = new SeCurlGetItemsForShipping();

      $orders = array();

      $purchase_logs = $this->getPurchaseLogs();
      foreach ($purchase_logs as $order)
      {
        $items = $this->buildItems($order);
        $recipient = $this->buildBuyer($order);
        $shippingOptions = $this->getShippingOptions($order);

        $ordersObject = new SeOrder($order->id, $order->date, $recipient, $items,
                              $shippingOptions->serviceId, $shippingOptions->courier, $shippingOptions->serviceName,
                              $shippingOptions->shipmentPrice, $shippingOptions->shipmentCurrency,
                              $shippingOptions->shippingEasyService);

        // add order to object
        $object->addOrder($ordersObject);
      }

      $this->setResponseObject($object);
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

      $result['timeStamp'] = $object->getTimeStamp();

      $result['errorMessage'] = $object->getErrorMessage();

      $result['orders'] = array();

      foreach($object->getOrders() as $order)
      {
        $loopItem = array();

        $loopItem['id'] = $order->getId();
        $loopItem['date'] = $order->getDate();
        $loopItem['serviceId'] = $order->getServiceId();
        $loopItem['courier'] = $order->getCourier();
        $loopItem['serviceName'] = $order->getServiceName();
        $loopItem['shipmentPrice'] = $order->getShipmentPrice();
        $loopItem['shipmentCurrency'] = $order->getShipmentCurrency();
        $loopItem['shippingEasyService'] = $order->getShippingEasyService();

        $loopItem['recipient']['id'] = $order->getRecipient()->getId();
        $loopItem['recipient']['firstName'] = $order->getRecipient()->getFirstName();
        $loopItem['recipient']['lastName'] = $order->getRecipient()->getLastName();
        $loopItem['recipient']['phone'] = $order->getRecipient()->getPhone();
        $loopItem['recipient']['email'] = $order->getRecipient()->getEmail();
        $loopItem['recipient']['line1'] = $order->getRecipient()->getLine1();
        $loopItem['recipient']['line2'] = $order->getRecipient()->getLine2();
        $loopItem['recipient']['city'] = $order->getRecipient()->getCity();
        $loopItem['recipient']['postalCode'] = $order->getRecipient()->getPostalCode();
        $loopItem['recipient']['state'] = $order->getRecipient()->getState();
        $loopItem['recipient']['country'] = $order->getRecipient()->getCountry();

        $loopItem['items'] = array();

        foreach($order->getItems() as $item)
        {
          $loopSubItem = array();

          $loopSubItem['id'] = $item->getId();
          $loopSubItem['name'] = $item->getName();
          $loopSubItem['transactionId'] = $item->getTransactionId();
          $loopSubItem['quantity'] = $item->getQuantity();
          $loopSubItem['price'] = $item->getPrice();
          $loopSubItem['total'] = $item->getTotal();
          $loopSubItem['currency'] = $item->getCurrency();
          $loopSubItem['paid'] = $item->getPaid();
          $loopSubItem['height'] = $item->getHeight();
          $loopSubItem['width'] = $item->getWidth();
          $loopSubItem['length'] = $item->getLength();
          $loopSubItem['weight'] = $item->getWeight();
          $loopSubItem['isMetric'] = $item->getIsMetric();

          array_push($loopItem['items'], $loopSubItem);
        }

        array_push($result['orders'], $loopItem);
      }

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