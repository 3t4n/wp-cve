<?php

add_action('admin_menu', 'shippingeasy_add_settings_page');
function shippingeasy_add_settings_page()
{
  add_options_page('ShippingEasy Settings', 'ShippingEasy', 'manage_options', 'shippingeasy_settings', 'shippingeasy_main_settings');
}

function shippingeasy_main_settings()
{
  global $purchlogs;
  global $wpsc_purchlog_statuses;

  if($_POST['shippingeasy_main_settings'] != null)
  {
    $submittedOptions = $_POST['shippingeasy_main_settings'];
    update_option('shippingeasy_main_settings', $submittedOptions);
  }

  if (@$_REQUEST['save'])
  {
    echo '<div id="message" class="updated"><p><strong>'. __('Settings saved.', 'shippingeasy').'</strong></p></div>';
  }

  if (@$_REQUEST['reset_to_defaults'])
  {
    SeWpUtils::resetToDefaults('main');
    echo '<div id="message" class="updated"><p><strong>'. __('Settings set to defaults.', 'shippingeasy').'</strong></p></div>';
  }

  $settings = (array) get_option('shippingeasy_main_settings');

  $output  = '<div class="wrap">';
  $output .= '  <div id="icon-options-general" class="icon32"><br></div>';
  $output .= '  <h2>ShippingEasy Settings</h2>';
  $output .= '  <form action="" method="post">';
  $output .= '    <table class="form-table"';
  $output .= '      <tr valign="top">';
  $output .= '        <th scope="row">API Key:</th>';
  $output .= '        <td><input type="text" readonly="readonly" value="'. get_option('shippingeasy_generated_apikey'). '" size="60">';
  $output .= '           <div class="description">'. __("When adding your store to the 'MyStores' hub in your account section of ShippingEasy.com, you will be asked for this API key.", 'shippingeasy') .'</div>';
  $output .= '        </td>';
  $output .= '      </tr>';
  $output .= '      <tr valign="top">';
  $output .= '        <th scope="row">Shop URL:</th>';
  $output .= '        <td><input type="text" readonly="readonly" value="'. network_site_url().'" size="60">';
  $output .= '          <div class="description">'. __("When adding your store to the 'MyStores' hub in your account section of ShippingEasy.com, you will be asked for this Shop URL.", 'shippingeasy').'</div>';
  $output .= '        </td>';
  $output .= '      </tr>';
  $output .= '      <tr valign="top">';
  $output .= '      <th scope="row">Select available for shipping statuses:</th>';
  $output .= '      <td>';

  $statuses = empty($purchlogs) === false ? $purchlogs->allpurchaselogstatuses : $wpsc_purchlog_statuses;

  if (empty($statuses) === false)
  {
    foreach ($statuses as $purchaselogstatus)
    {
      $output .= '       <label for="'. $purchaselogstatus['internalname'].'">';
      $output .= '       <input type="checkbox"';

      if ($settings['forshipping'][$purchaselogstatus['order']] == $purchaselogstatus['order'])
      {
       $output .=' checked="checked"';
      }

      $output .=' value="'. $purchaselogstatus['order'].'" id="'.$purchaselogstatus['internalname'].'" name="shippingeasy_main_settings[forshipping]['.$purchaselogstatus['order'].']"> ';
      $output .= $purchaselogstatus['label'].'</label><br/>';
    }
    $output .= '          <div class="description">'. __("Select order statuses which the 'MyStores' hub will show as available for shipping.", 'shippingeasy').'</div>';
  } else
  {
    $output .= '<span style="color:red">'.__('Order statuses not available. Please verify that WP-e-commerce plugin is installed.', 'shippingeasy').'</span>';
  }

  $output .= '        </td>';
  $output .= '      </tr>';
  $output .= '      <tr valign="top">';
  $output .= '        <th scope="row">'.__('Select status for shipped orders:','shippingeasy').'</th>';
  $output .= '        <td>';

  if (empty($statuses) === false)
  {
    $output .= '          <select name="shippingeasy_main_settings[shipped_status]">';

    foreach ($statuses as $purchaselogstatus)
    {
      $output .= '       <option ';

      if ($settings['shipped_status'] == (int) $purchaselogstatus['order'])
      {
        $output .= 'selected="selected"';
      }

      $output .= ' value="'. (int) $purchaselogstatus['order']. '">'. $purchaselogstatus['label'].'</option>';
    }
    $output .= '          </select>';
    $output .= '          <div class="description">'. __("Select status which the 'MyStores' hub will use to mark orders that have been shipped.", 'shippingeasy').'</div>';
  } else
  {
    $output .= '<span style="color:red">'.__('Order statuses not available. Please verify that WP-e-commerce plugin is installed.', 'shippingeasy').'</span>';
  }
  $output .= '        </td>';
  $output .= '      </tr>';
  $output .= '   </table>';

  $output .= '   <p class="submit">';
  $output .= '     <input name="save" type="submit" class="button-primary" value="'. __('Save Changes') .'" />';
  $output .= '     <input name="reset_to_defaults" id="reset-main-settings" type="submit" class="button-reset" value="'. __('Reset to Defaults') .'" />';
  $output .= '   </p>';
  $output .= '  </form>';
  $output .= '</div>';

  echo $output;
}

function shippingeasy_rate_settings()
{

  if (@$_REQUEST['reset_to_defaults'])
  {
    SeWpUtils::resetToDefaults('rate');
    return true;
  }

  if(isset($_POST['shippingeasy_rate_settings']) === true)
  {
    $seRateSettingsErrors = array(
      'se_api_key' => array(),
      'handling_markup' => array(),
      'default_size' => array()
    );

    $submittedOptions = (array) $_POST['shippingeasy_rate_settings'];

    if (empty($submittedOptions['se_api_key']) === true)
    {
      $submittedOptions['se_api_key'] = '';
      $seRateSettingsErrors['se_api_key'][] = 'API key is required.';
    }

    if (SeWpUtils::checkSeApiKey($submittedOptions['se_api_key']) === false)
    {
      $submittedOptions['se_api_key'] = '';
      $seRateSettingsErrors['se_api_key'][] = 'API key validation failed.';
    }

    $handlingMarkup = $submittedOptions['handling_markup'];
    if (is_numeric($handlingMarkup) === false && stripos($handlingMarkup, '%') === false)
    {
      $submittedOptions['handling_markup'] = 0;
      $seRateSettingsErrors['handling_markup'][] = 'Only allowed character is `%`.';
    }

    $handlingMarkup = str_replace('%', '', $handlingMarkup);

    if (is_numeric($handlingMarkup) === false)
    {
      $submittedOptions['handling_markup'] = 0;
      $seRateSettingsErrors['handling_markup'][] = 'Handling mark-up could not be converted to numeric value.';
    }

    if ($handlingMarkup < 0)
    {
      $submittedOptions['handling_markup'] = 0;
      $seRateSettingsErrors['handling_markup'][] = 'Handling mark-up must be > 0.';
    }

    foreach ($submittedOptions['default_size'] as $key => &$measurement)
    {
      if (empty($measurement) === true)
      {
        $measurement = 1;
        $seRateSettingsErrors['default_size'][] = ucfirst($key).' is required.';
      }

      if (is_numeric($measurement) === false)
      {
        $measurement = 1;
        $seRateSettingsErrors['default_size'][] = ucfirst($key).' must be numeric.';
      }

      if ($measurement <= 0)
      {
        $measurement = 1;
        $seRateSettingsErrors['default_size'][] = ucfirst($key).' must be greater than zero.';
      }
    }

    update_option('shippingeasy_rate_settings_errors', $seRateSettingsErrors);
    update_option('shippingeasy_rate_settings', $submittedOptions);

    return true;
  }

  $output .= '<td id="se-rate-settings">';
  $rateSettings  = get_option('shippingeasy_rate_settings');
  $mainSettings = get_option('shippingeasy_main_settings');
  $rateSettingsErrors = get_option('shippingeasy_rate_settings_errors');
  $available_services = SeWpUtils::getAvailableServices();

  $output .= '<h3>'.__('ShippingEasy API Key', 'shippingeasy').'</h3>';
  $output .= '<div class="shippingeasy-rate-settings">';

  // display validation errors
  if (empty($rateSettingsErrors['se_api_key']) === false)
  {
    $output .= '<ul class="se-rate-settings-errors">';
    foreach ($rateSettingsErrors['se_api_key'] as $value) {
      $output .= '<li>';
      $output .= $value;
      $output .= '</li>';
    }
    $output .= '</ul>';
  }
  $output .= '<div id="se_register">'.__('If you don’t have a ShippingEasy account then ', 'shippingeasy').'<a href="https://www.shippingeasy.com/#register?channel=wordpress">'.__('sign up now', 'shippingeasy').'</a></div>';
  $output .= __('ShippingEasy API Key: ', 'shippingeasy');
  $output .= '<input type="text" value="'.htmlentities($rateSettings ['se_api_key']).'" size="60" name="shippingeasy_rate_settings[se_api_key]" maxlength="128" style="width:350px;">';
  $output .= '<div class="description">'.__('To start using ShippingEasy, you will need to connect your store to a ShippingEasy account using an API key. The API key can be found at the bottom of the ‘My Details’ tab in the ‘<a href="https://www.shippingeasy.com/my#api-area" target="_blank">My Account</a>’ area of the site.', 'shippingeasy').'</div>';
  $output .= '</div>';

  foreach ($available_services as $courier)
  {
    $output .= '<h3>'.$courier['Name'].__(' Services', 'shippingeasy');
    $output .= '<span>Select: <a title="'.__('All', 'shippingeasy').'" class="se_select_all" href="javascript:">'.__('All', 'shippingeasy').'</a>';
    $output .= ' / <a title="'.__('None', 'shippingeasy').'" class="se_select_none" href="javascript:">'.__('None', 'shippingeasy').'</a></span></h3>';
    $output .= '<div class="shippingeasy-rate-settings">';
    $output .= '<div class="description">'.$courier['Description'].'</div>';

    foreach ($courier['Services'] as $service)
    {
      $output .= '<label for="service_'. $service['Id'] .'">';
      $output .= '<input type="checkbox" id="service_'. $service['Id'].'" name="shippingeasy_rate_settings[services]['.$service['Id'].']"';
      if (isset($rateSettings ['services'][$service['Id']]) && $rateSettings ['services'][$service['Id']] == $service['Id']) {
        $output .= ' checked="checked" ';
      }
      $output .= 'value="'.$service['Id'].'" >';
      $output .= $service['Name'].'</label><br/>';
    }
    $output .= '</div>';
  }

  $output .= '<h3>'.__('Handling mark-up', 'shippingeasy').'</h3>';
  $output .= '<div class="shippingeasy-rate-settings">';

  // display validation errors
  if (empty($rateSettingsErrors['handling_markup']) === false)
  {
    $output .= '<ul class="se-rate-settings-errors">';
    foreach ($rateSettingsErrors['handling_markup'] as $value) {
      $output .= '<li>';
      $output .= $value;
      $output .= '</li>';
    }
    $output .= '</ul>';
  }

  $output .= __('Handling mark-up: ', 'shippingeasy');
  $output .= '  <input type="text" class="se-max-length" maxlength="5" name="shippingeasy_rate_settings[handling_markup]" value="'.htmlentities($rateSettings ['handling_markup']).'"><br/>';
  $output .= '  <div class="description">'.__('For a fixed amount enter number, for percentage enter number followed by %', 'shippingeasy').'</div>';
  $output .= '</div>';

  if (isset($rateSettings ['display_graphics']) && $rateSettings ['display_graphics'] == '1') {
    $checked_1 .= ' checked="checked" ';
    $checked_0 .= '';
  } else
  {
    $checked_1 .= '';
    $checked_0 .= ' checked="checked"';
  }

  $output .= '<h3>'.__('Display Service Logos During Checkout?', 'shippingeasy').'</h3>';
  $output .= '<div class="shippingeasy-rate-settings">';
  $output .= '  <label for="shippingeasy-display-graphics-0">';
  $output .= '    <input type="radio" value="0" name="shippingeasy_rate_settings[display_graphics]" id="shippingeasy-display-graphics-0"'.$checked_0.'>';
  $output .= '      '.__('No', 'shippingeasy').'</label><br/>';
  $output .= '  <label for="shippingeasy-display-graphics-1">';
  $output .= '    <input type="radio" value="1" name="shippingeasy_rate_settings[display_graphics]" id="shippingeasy-display-graphics-1"'.$checked_1.'>';
  $output .= '      '.__('Yes', 'shippingeasy').'</label>';
  $output .= '  <div class="description">'.__('Indicate whether you would like the carrier logos shown next to the available services.', 'shippingeasy').'</div>';
  $output .= '</div>';

  if (isset($rateSettings ['display_time_estimate']) && $rateSettings ['display_time_estimate'] == '1') {
    $checked_1 .= ' checked="checked" ';
    $checked_0 .= '';
  } else
  {
    $checked_1 .= '';
    $checked_0 .= ' checked="checked"';
  }

  $output .= '<h3>'.__('Display Delivery Time Estimate During Checkout?', 'shippingeasy').'</h3>';
  $output .= '<div class="shippingeasy-rate-settings">';
  $output .= '  <label for="shippingeasy-display-time-estimate-0">';
  $output .= '    <input type="radio" value="0" name="shippingeasy_rate_settings[display_time_estimate]" id="shippingeasy-display-time-estimate-0"'.$checked_0.'>';
  $output .= '      '.__('No', 'shippingeasy').'</label><br/>';
  $output .= '  <label for="shippingeasy-display-time-estimate-1">';
  $output .= '    <input type="radio" value="1" name="shippingeasy_rate_settings[display_time_estimate]" id="shippingeasy-display-time-estimate-1"'.$checked_1.'>';
  $output .= '      '.__('Yes', 'shippingeasy').'</label>';
  $output .= '  <div class="description">'.__('Display estimated delivery time in days', 'shippingeasy').'</div>';
  $output .= '</div>';

  $output .= '<h3>'.__('Default Package Size:', 'shippingeasy').'</h3>';
  $output .= '<div class="shippingeasy-rate-settings">';

  // display validation errors
  if (empty($rateSettingsErrors['default_size']) === false)
  {
    $output .= '<ul class="se-rate-settings-errors">';
    foreach ($rateSettingsErrors['default_size'] as $value) {
      $output .= '<li>';
      $output .= $value;
      $output .= '</li>';
    }
    $output .= '</ul>';
  }

  $output .= '  <span class="default-size">'.__('Height: ', 'shippingeasy').'</span>';
  $output .= '  <input type="text" class="se-max-length" maxlength="5" name="shippingeasy_rate_settings[default_size][height]" value="'.htmlentities($rateSettings ['default_size']['height']).'"><br/>';
  $output .= '  <span class="default-size">'.__('Width: ', 'shippingeasy').'</span>';
  $output .= '  <input type="text" class="se-max-length" maxlength="5" name="shippingeasy_rate_settings[default_size][width]" value="'.htmlentities($rateSettings ['default_size']['width']).'"><br/>';
  $output .= '  <span class="default-size">'.__('Length: ', 'shippingeasy').'</span>';
  $output .= '  <input type="text" class="se-max-length" maxlength="5" name="shippingeasy_rate_settings[default_size][length]" value="'.htmlentities($rateSettings ['default_size']['length']).'"><br/>';
  $output .= '  <span class="default-size">'.__('Weight: ', 'shippingeasy').'</span>';
  $output .= '  <input type="text" class="se-max-length" maxlength="5" name="shippingeasy_rate_settings[default_size][weight]" value="'.htmlentities($rateSettings ['default_size']['weight']).'"><br/>';

  $output .= '  <div class="description">'.__('Please specify default package size to be used for shipping rate calculation, if no measurements have been defined for a product.', 'shippingeasy').'</div>';
  $output .= '         <label for="measurement-units-metric">';
  $output .= '           <input type="radio" value="1" name="shippingeasy_rate_settings[measurement_units]" id="measurement-units-metric"';

  if ($rateSettings['measurement_units'] == 1)
  {
    $output .= ' checked="checked"';
  }

  $output .= '> ';
  $output .= __('Metric (kgs, cm)', 'shippingeasy');
  $output .= '         </label><br/>';
  $output .= '         <label for="measurement-units-imperial">';
  $output .= '           <input type="radio" value="0" name="shippingeasy_rate_settings[measurement_units]" id="measurement-units-imperial"';

  if ($rateSettings['measurement_units'] == 0)
  {
    $output .= ' checked="checked"';
  }

  $output .= '> ';
  $output .= __('Imperial (lbs, inches)', 'shippingeasy');
  $output .= '         </label>';
  $output .= '         <div class="description">'. __('Since WPEC allows each measurement to be set in different unit we have to convert all item measurements to either metric or imperial system.', 'shippingeasy').'</div>';

  $output .= '</div>';

  $output .= '</td>';

  return $output;
}
