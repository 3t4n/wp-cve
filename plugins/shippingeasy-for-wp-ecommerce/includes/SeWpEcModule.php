<?php
class ShippingEasyWpecModule
{
  public $internal_name = "shippingeasy";
  public $name = 'ShippingEasy';
  public $is_external = true;
  public $requires_weight = true;
  public $needs_zipcode = true;

  public function __construct()
  {
    return true;
  }

  /* Getter for shipping module name
   *
   * @return $name
   */
  function getName()
  {
    return $this->name;
  }

  /* Getter for shipping module internal name */
  function getInternalName()
  {
    return $this->internal_name;
  }

  /* This will appear in the WP E-Commerce admin area under Shipping
   * $output will be wrapped within the appropriate <form> tags, and <table> </table> block
   *
   * @return string
   * */

  function getForm()
  {

    $output = shippingeasy_rate_settings();
    delete_option('shippingeasy_rate_settings_errors');

    return $output;
  }

  /*
   * This function stores shippingeasy settings submitted by the form in the getForm() function.
   */

  function submit_form()
  {
    return shippingeasy_rate_settings();
  }

  function getQuote()
  {
    global $wpdb, $wpsc_cart, $destinationCitiesOutput, $rateQueryMessage;

    $CollectionCountry  = get_option('base_country');
    $CollectionCity     = get_option('base_city');
    $CollectionZip      = get_option('base_zipcode');
    $DestinationCountry = '';
    $DestinationCity    = '';
    $DestinationZip     = '';
    $destinationCities = array();
    $destinationCitiesOutput = '';
    $rateQueryMessage = '';

    if(isset($_POST['wpsc_ajax_action']) && $_POST['wpsc_ajax_action'] == 'update_shipping_price')
    {
      return $_SESSION['quoted_methods'];
    }

    // get destination country
    if(isset($_POST['country']))
    {
      $DestinationCountry = esc_attr($_POST['country']);
    }
    // get destination zipcode
    if(isset($_POST['zipcode']) && $_POST['zipcode'] != "Your Zipcode")
    {
      $DestinationZip = esc_attr($_POST['zipcode']);
    }
    if (empty($DestinationZip) === true)
    {
      // We cannot get rate quote without a zip code so return
      $rateQueryMessage = __('Please enter postal code.', 'shippingeasy');
      return array();
    }

    // get destination city
    if(isset($_POST['city']))
    {
      $DestinationCity = esc_attr($_POST['city']);
    }

    // If city is empty we can get one from city resource
    $params = array ("CountryCode" => $DestinationCountry,"PostalCode"  => $DestinationZip);

    $cities = SeWpUtils::getShippingEasyResource('city', $params, 6000);
    if (isset($cities->cities[0]->City) === false || empty($cities->cities) === true)
    {
      // we got empty response so return
      $rateQueryMessage = __('No city matched your country and postal code.', 'shippingeasy');
      return array();
    }
    // if there is only one city for this zipcode select it
    if (count($cities->cities) === 1)
    {
      $DestinationCity = $cities->cities[0]->City;
    }

    foreach ($cities->cities as $city)
    {
      $selected = '';

      if ($DestinationCity == $city->City)
      {
        $selected = 'selected="selected"';
      }
      $destinationCities[] = $city->City;
      $destinationCitiesOutput .= '<option value="'.$city->City.'" '.$selected.'>'.$city->City.'</option>';
    }

    if(in_array($DestinationCity, $destinationCities) === false)
    {
      // city has not been selected so return.
      $rateQueryMessage = __('Please select city.', 'shippingeasy');
      return array();
    }

    // Total number of item(s) in the cart
    $numItems = count($wpsc_cart->cart_items);

    if ($numItems == 0)
    {
      // The customer's cart is empty. This probably shouldn't occur, but just in case!
      $rateQueryMessage = __('There are no items in the cart.', 'shippingeasy');
      return array();
    }

    // Total number of item(s) that don't attract shipping charges.
    $numItemsWithDisregardShippingTicked = 0;

    //get rate settings from wp
    $rateSettings = get_option('shippingeasy_rate_settings');
    $handlingMarkup = $rateSettings['handling_markup'];
    $defaultPackageSize = $rateSettings['default_size'];

    // get the mesurement units used
    $isMetric = $rateSettings['measurement-units'];
    if ($isMetric == 0)
    {
      $measurementUnits = 'imperial';
    } else
    {
      $measurementUnits = 'metric';
    }

    $packages = array();
    $packageNum = 1;
    foreach($wpsc_cart->cart_items as $cart_item)
    {
      if ($cart_item->uses_shipping === false)
      {
        // The "Disregard Shipping for this product" option is ticked for this item.
        // Don't include it in the shipping quote.
        $numItemsWithDisregardShippingTicked++;
        continue;
      }

      // If we are here then this item should be shipped.
      $meta = $cart_item->meta[0];

      $dimensions = SeWpUtils::convertDimensionsToAppropriateUnits($meta['dimensions']);
      $weight = SeWpUtils::convertWeightToAppropriateUnits($meta['weight']);

      if ($cart_item->quantity > 1)
      {
        $weight = $weight*$cart_item->quantity;

        foreach ($dimensions as &$dimension)
        {
          $dimension = round($dimension * $cart_item->quantity / 2, 2);
        }
      }

      // check if package dimensions are smaller than default and if so set it to default
      $dimensions['weight'] = $weight;

      foreach (array("width","height","length","weight") as $key)
      {
        if (($dimensions[$key] < $defaultPackageSize[$key] === true))
        {
          $dimensions[$key] = $defaultPackageSize[$key];
        }
      }

      $packages['Package'.$packageNum] = $dimensions['weight'].'x'.$dimensions['height'].'x'.$dimensions['width'].'x'.$dimensions['length'].'-'.$measurementUnits;
      $packageNum++;
    }

    //There are no items to ship so return
    if ($numItemsWithDisregardShippingTicked == $numItems)
    {
      $rateQueryMessage = __('There are no shippable items in the cart.', 'shippingeasy');
      return array();
    }

    if(isset($_POST['se_current_time']) === true && empty($_POST['se_current_time']) === false
       && is_numeric($_POST['se_current_time']) === true)
    {
      $today = (int) $_POST['se_current_time'];
    } else
    {
      $today = time();
    }

    $rateQuery = array (
            "CollectionCountry"  => $CollectionCountry,
            "CollectionCity"     => $CollectionCity,
            "CollectionZip"      => $CollectionZip,
            "DestinationCountry" => $DestinationCountry,
            "DestinationCity"    => $DestinationCity,
            "DestinationZip"     => $DestinationZip,
            "Date"               => date('Y-m-d'),
            "Today"              => $today,
            "Currency"           => SeWpUtils::getCurrencyCode()
    );

    $rateQuery = array_merge($rateQuery, $packages);

    //check if any of the params is empty and if so return
    foreach ($rateQuery as $paramKey => $paramValue)
    {
     if (empty($paramValue) === true)
     {
       $rateQueryMessage = $paramKey . __('empty.', 'shippingeasy');
       return array();
     }
    }

    //get rates
    $rates = SeWpUtils::getShippingEasyResource('rate', $rateQuery);

    $quotedMethods = array();
    if(isset($rates->rates) === true && empty($rates->rates) === false)
    {

      $selectedServices = $rateSettings['services'];

      foreach ($rates->rates as $rate)
      {
        //skip rate if it's not enabled in settings
        if (in_array($rate->ServiceId, $selectedServices) === false)
        {
          continue;
        }

        // display carrier logos next to the available services
        $image = '';
        if ($rateSettings['display_graphics'] == 1)
        {
          $image = '<span class=se-courier-'.$rate->CourierId.'></span>';
        }

        // display time estimate
        $humanEta = '';
        if ($rateSettings['display_time_estimate'] == 1)
        {
          $humanEta = ' - Eta: '.$rate->HumanEta;
        }

        $serviceName = $image.$rate->Courier.': '.$rate->ServiceName.$humanEta;

        // clean quotes to prevent javascript errors when selecting different rate
        $serviceName = str_replace("'", "`", $serviceName);
        $serviceName = str_replace('"', '`', $serviceName);

        $quotedMethods[$serviceName] = SeWpUtils::calculateRateWithMarkup($rate->CurrencyValue, $handlingMarkup);
      }
    }
    if (empty($quotedMethods))
    {
      $rateQueryMessage = __('No qoutes available.', 'shippingeasy');
    } else
    {
      $rateQueryMessage = __('Quotes returned: ', 'shippingeasy').count($quotedMethods);
    }

    $_SESSION['quoted_methods'] = $quotedMethods;

    return $quotedMethods;
  }

  /*
   * @deprecated
   */
  function get_item_shipping()
  {
  }

}

add_filter('wpsc_shipping_modules', 'shippingeasy_add_wpec_module');
function shippingeasy_add_wpec_module($wpsc_shipping_modules)
{
  global $shippingeasyWpecModule;
  $shippingeasyWpecModule = new ShippingEasyWpecModule();

  $wpsc_shipping_modules[$shippingeasyWpecModule->getInternalName()] = $shippingeasyWpecModule;

  return $wpsc_shipping_modules;
}

add_action('wp_head', 'shippingeasy_get_quote_script_and_style');
function shippingeasy_get_quote_script_and_style() {
  global $destinationCitiesOutput, $rateQueryMessage;

  echo '<link rel="stylesheet" type="text/css" href="' .SE_PLUGIN_BASE_URL. '/css/rate-quote.css">';

  $output  = '<script type="text/javascript">';
  $output .=  'jQuery(document).ready(function($) {';
  $output .=    'if($("#change_country #region").length != 0) $("#change_country #region").css("display","none");';

  //get visitor's current time
  $output .=    "$('#change_country #current_country').before('";
  $output .=    '<input type="hidden" value="" id="se_current_time" name="se_current_time">'. "');";
  $output .=    'var se_d = new Date();';
  $output .=    'var se_n = se_d.getTime();';
  $output .=    '$("#change_country #se_current_time").val(se_n);';

  if(empty($destinationCitiesOutput) === false)
  {
    $output .= "$('#change_country #zipcode').after('";
    $output .=  '<select name="city" id="city">'.$destinationCitiesOutput;
    $output .=  "</select>');";
  }

  if(empty($rateQueryMessage) === false)
  {
    $output .= "$('.wpsc_shipping_info td').html('".$rateQueryMessage."');";
  }

  $output .= '});</script>';

  print $output;
}
