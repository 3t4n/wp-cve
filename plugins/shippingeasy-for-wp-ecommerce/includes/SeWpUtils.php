<?php
class SeWpUtils
{
  protected static $entryPoint  = "https://www.shippingeasy.com/api/v1/";

  /**
   * Function which calculates rate based on handling mark-up.
   */
  public static function calculateRateWithMarkup($rateAmount, $handlingMarkup)
  {
    if (trim($handlingMarkup) != '' && $handlingMarkup != 0)
    {
      if (stripos($handlingMarkup, '%') !== false)
      {
        return round($rateAmount + ($rateAmount * ($handlingMarkup / 100)), 2);
      }
      else
      {
        return round($rateAmount + $handlingMarkup, 2);
      }
    }
    else
    {
      return $rateAmount;
    }
  }

  public static function executeResource()
  {
    try
    {
      $apiKey = get_option('shippingeasy_generated_apikey', '');
      $resource = $_REQUEST['resource'];
      $api = new SeApi($apiKey, SeApiType::Curl);
      $result = $api->executeResource($resource);
    }
    catch (Exception $e)
    {
      SeApiUtils::outputError('Request error: ' . $e->getMessage(), 500);
    }
  }

  /**
   * Custom TOKEN generator.
   */

  public static function generateApiKey()
  {
    return self::generateRandomString(32);
  }

  public static function regenerateApiKey()
  {
    update_option('shippingeasy_generated_apikey', self::generateApiKey());
  }

  public static function generateRandomString($length)
  {
    $alphabet = "ybndrfg8ejkmcpqxot1uwisza345h769";

    $string = '';

    for ($i = 0; $i < $length; $i++)
    {
      $string .= $alphabet[mt_rand(0, strlen($alphabet)-1)];
    }

    return $string;
  }

  public static function defaultSettings()
  {
    $defaults = array();
    $defaults['main'] = array(
      'forshipping' => array('2' => 2, '3' => 3),
      'shipped_status' => 4,
    );
    $defaults['rate'] = array(
      'se_api_key' => '',
      'services' => array(),
      'handling_markup' => 0,
      'display_graphics' => 0,
      'display_time_estimate' => 0,
      'default_size' => array('height' => 1, 'width' => 1, 'length' => 1, 'weight' => 1),
      'measurement_units' => 1
    );

    $services = self::getAvailableServices();
    foreach ($services as $courier)
    {
      foreach ($courier['Services'] as $service)
      {
        $defaults['rate']['services'][$service['Id']] = $service['Id'];
      }
    }

    return $defaults;
  }

  public static function setDefaults()
  {
    $defaults = self::defaultSettings();
    add_option('shippingeasy_generated_apikey', self::generateApiKey());
    add_option('shippingeasy_main_settings', $defaults['main']);
    add_option('shippingeasy_rate_settings', $defaults['rate']);
  }

  public static function resetToDefaults($options=null)
  {
    if ($options != null)
    {
      $defaults = self::defaultSettings();

      switch ($options):
        case 'main':
          update_option('shippingeasy_main_settings', $defaults['main']);
          break;
        case 'rate':
          update_option('shippingeasy_rate_settings', $defaults['rate']);
          break;
        case 'all':
          update_option('shippingeasy_main_settings', $defaults['main']);
          update_option('shippingeasy_rate_settings', $defaults['rate']);
          break;
      endswitch;
    }
  }
  public static function checkSeApiKey($seApiKey)
  {
    if (empty($seApiKey) === false)
    {
      $url = self::$entryPoint.'/user';
      $headers = array('X-ShippingEasy-Channel' => 'wordpress', 'X-ShippingEasy-API-Key' => $seApiKey);
      $options = array('timeout' => 15, 'sslverify' => false, 'blocking' => true, 'headers' => $headers);
      $response = wp_remote_get($url, $options);

      // On error return false
      if (is_wp_error($response) === true || $response['response']['code'] != 200 || empty($response['body']) === true)
      {
        return false;
      }

      $responseBody = json_decode($response['body']);
      if (isset($responseBody->user) && isset($responseBody->user->Id) && isset($responseBody->user->Username))
      {
        return true;
      }
    }

    return false;
  }

  public static function getShippingEasyResource($resource, $params=null, $cache_expiration = 600)
  {

    $seRateSettings = get_option('shippingeasy_rate_settings');
    $seApiKey = $seRateSettings['se_api_key'];

    $headers = array('X-ShippingEasy-Channel' => 'wordpress', 'X-ShippingEasy-API-Key' => $seApiKey);

    if ($params != null)
    {
      // URL encode the parameters and apend them to url
      $params = array_map('urlencode', $params);
      $url = add_query_arg($params, self::$entryPoint.$resource);
    } else
    {
      $url = self::$entryPoint.$resource;
    }

    // See if we already have cached results
    $cacheKey = 'shippingeasy_' . md5($url);
    $cachedResult = get_transient($cacheKey);
    if ($cachedResult === false)
    {
      $options = array('timeout' => 15, 'sslverify' => false, 'blocking' => true, 'headers' => $headers);
      $response = wp_remote_get($url, $options);

      // On error return empty array
      if (is_wp_error($response) === true) // || $response['response']['code'] != 200 || empty($response['body']) === true)
      {
        return array();
      }

      $responseBody = $response['body'];

      // cache response body
      set_transient($cacheKey, $responseBody, $cache_expiration);

    } else
    {
      $responseBody = $cachedResult;
    }

    return json_decode($responseBody);
  }

  /*
   * Returns available courier services from shippingeasy API
   */
  public static function getAvailableServices()
  {

    $available_couriers = SeWpUtils::getShippingEasyResource('courier');
    $available_service_types = SeWpUtils::getShippingEasyResource('serviceType', array('perPage'=> '300'));

    $available_services = array();
    $descriptions = array(

      'FedEx' => __('Select which Fedex services are shown to the customer on checkout.', 'shippingeasy')
                 .'<br>'. __('Note: Fedex services are available for US domestic and any country to any country only.', 'shippingeasy'),
      'UPS' =>   __('Select which UPS services are shown to the customer on checkout.', 'shippingeasy')
                 .'<br/>'.__('Note: UPS services are available for AU export only.', 'shippingeasy'),
      'USPS' => __('Select which USPS services are shown to the customer on checkout.', 'shippingeasy')
                 .'<br>'.__('Note: USPS services are available for US domestic and US export only.', 'shippingeasy'),
      'Fastway' => __('Select which Fastway services are shown to the customer on checkout.', 'shippingeasy')
                 .'<br>'.__('Note: Fastway services are available for AU domestic only.', 'shippingeasy'),
      'MailCall' => __('Select which MailCall services are shown to the customer on checkout.', 'shippingeasy')
                 .'<br>'.__('Note: MailCall services are available for AU domestic - Sydney to Sydney and Melbourne to Melbourne only.', 'shippingeasy')
    );
    foreach ($available_couriers->couriers as $courier)
    {
      $courierId = trim($courier->Id);
      $available_services[$courierId] = array(
        'Name' => $courier->Name,
        'Description' => $descriptions[$courier->Name],
        'Services' => array()
      );

      foreach ($available_service_types->serviceTypes as &$service)
      {
        $serviceId = trim($service->Id);
        if ((int) $service->CourierId === (int) $courierId)
        {
          $available_services[$courierId]['Services'][$serviceId] = array(
          'Id'   => $serviceId,
          'Name' => trim($service->Name),
          'Code' => trim($service->Code)
          );
          unset($service);
        }
      }
    }
    update_option('shippingeasy_available_services', $available_services);
    return $available_services;
  }

  public static function convertWeightToAppropriateUnits($weight, $raw=false)
  {
    $seRateSettings = get_option('shippingeasy_rate_settings');
    $isMetric = $seRateSettings['measurement-units'];
    $in_unit = 'pound'; // WpEc keeps weight in pounds, units are only used for displaying

    if ($isMetric == 0)
    {
      $weight = self::convertWeight($weight, $in_unit, 'pound', $raw);

    } else
    {
      $weight = self::convertWeight($weight, $in_unit, 'kilogram', $raw);
    }
      return $weight;
  }

  public static function convertDimensionsToAppropriateUnits($dimensions, $raw=false)
  {
    $seRateSettings = get_option('shippingeasy_rate_settings');
    $isMetric = $seRateSettings['measurement-units'];

    if ($isMetric == 0)
    {
      $result = self::convertDimensions($dimensions, 'in', $raw);
    } else
    {
      $result = self::convertDimensions($dimensions, 'cm', $raw);
    }

    return $result;
  }

  public static function convertWeight($in_weight, $in_unit, $out_unit='kilogram', $raw=false)
  {
    switch($in_unit)
    {
      case "kilogram":
      $intermediate_weight = $in_weight * 1000;
      break;

      case "gram":
      $intermediate_weight = $in_weight;
      break;

      case "once":
      case "ounce":
      $intermediate_weight = ($in_weight / 16) * 453.59237;
      break;

      case "pound":
      default:
      $intermediate_weight = $in_weight * 453.59237;
      break;
    }

    switch($out_unit)
    {
      case "kilogram":
      $weight = $intermediate_weight / 1000;
      break;

      case "gram":
      $weight = $intermediate_weight;
      break;

      case "once":
      case "ounce":
      $weight = ($intermediate_weight / 453.59237) * 16;
      break;

      case "pound":
      default:
      $weight = $intermediate_weight / 453.59237;
      break;
    }
    if ($raw === false)
    {
      $weight = round($weight, 2);
    }

    return $weight;

  }

  public static function convertDimensions($dimensions, $out_unit='cm', $raw=false)
  {
    $convertedDimensions = array();
    foreach (array('width','height','length') as $dimension)
    {
      switch ($dimensions["{$dimension}_unit"])
      {
        case 'mm':
          $convertedDimensions[$dimension] = (float) $dimensions[$dimension] / 10;
          break;
        case 'meter':
          $convertedDimensions[$dimension] = (float) $dimensions[$dimension] * 100;
          break;
        case 'in':
          $convertedDimensions[$dimension] = (float) $dimensions[$dimension] * 2.54;
          break;

        default:
          $convertedDimensions[$dimension] = (float) $dimensions[$dimension];
          break;
      }

      switch($out_unit)
      {
        case "cm":
        break;
        case "in":
        $convertedDimensions[$dimension] = $convertedDimensions[$dimension] / 2.54;
        break;
        case "mm":
        $convertedDimensions[$dimension] = $convertedDimensions[$dimension] * 10;
        break;
        case "meter":
        $convertedDimensions[$dimension] = $convertedDimensions[$dimension] / 100;
        break;
      }

      if ($raw === false)
      {
        $convertedDimensions[$dimension] = round($convertedDimensions[$dimension], 2);
      }
    }

    return $convertedDimensions;
  }

  public static function getCurrencyCode()
  {
    global $wpdb;

    $currencyType = get_option('currency_type');
    $currencyCode = $wpdb->get_var("SELECT `code` FROM `".WPSC_TABLE_CURRENCY_LIST."` WHERE `id`='".$currencyType."' LIMIT 1") ;

    return $currencyCode;
  }

}