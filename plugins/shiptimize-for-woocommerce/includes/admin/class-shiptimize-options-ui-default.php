<?php 
include_once (SHIPTIMIZE_PLUGIN_PATH.'/includes/admin/class-shiptimize-options-ui.php');

class ShiptimizeOptionsUIDefault extends ShiptimizeOptionsUI {

  public function print_automatic_export(){
    global  $shiptimize;  
    ?>
    <h2><?php echo $shiptimize->__('automaticexport')?></h2>
    <p><?php 
    echo $shiptimize->__('automaticexportdescription');
    ?>
    <select name='shiptimize_autoexport'>
      <option value=''>-</option>
    <?php 
    
    $statuses = wc_get_order_statuses();  
    
    foreach ( $statuses as $status_key => $status_label) {
      $selected = $this->autoexport_status  == $status_key ? 'selected' :''; 
      echo '<option value="' . $status_key . '" ' . $selected . '>' . $status_label . '</option>';
    }
    echo  '</select></p>'; 
  }

  public function print_brazilian_fields (){
    global $shiptimize; 

    if(get_locale() != 'pt_BR'){
      return; 
    }
    ?>
    <h2>Envios no Brasil</h2>
    <p> Escolha o que vai ser enviado para a Shiptimize </p>
    <div class='shiptimize-settings__field'>
      <div class='shiptimize-settings__field'>
        <label class='shiptimize-settings__label'>CNPJ</label>
        <?php 
        $cnpj = get_option('shiptimize_cnpj');  
        echo $this->get_custom_field_select('shiptimize_cnpj', $cnpj ? $cnpj : '_billing_cnpj');?>
      </div>
    </div>

    <div class='shiptimize-settings__field'>
      <div class='shiptimize-settings__field'>
        <label class='shiptimize-settings__label'>CPF</label>
        <?php 
        $cpf = get_option('shiptimize_cpf'); 
        echo $this->get_custom_field_select('shiptimize_cpf',$cpf ? $cpf : '_billing_cpf');?>
      </div>
    </div>

    <div class='shiptimize-settings__field'>
      <div class='shiptimize-settings__field'>
        <label class='shiptimize-settings__label'>Bairro</label>
        <?php 
        $neighborhood = get_option('shiptimize_neighborhood');
        echo $this->get_custom_field_select('shiptimize_neighborhood', $neighborhood ? $neighborhood : '_shipping_neighborhood');?>
      </div>
    </div>

    <div class='shiptimize-settings__field'>
      <div class='shiptimize-settings__field'>
        <label class='shiptimize-settings__label'>Número</label>
        <?php 
        $number = get_option('shiptimize_number'); 
        echo $this->get_custom_field_select('shiptimize_number', $number ? $number : '_shipping_number');?>
      </div>
    </div>
    <?php 
  }

  /** 
   * If the user has input credentials 
   * if the carriers are not cached 
   * try to get the carriers 
   */ 
  public function print_carriers ( ) {
    global $shiptimize; 

    if(!$this->token || !$this->carriers ){
      return; 
    }
  ?> 
    <div class='shiptimize-settings__field'>
      <label class='shiptimize-settings__label'><?php echo $shiptimize->translate('Carriers Available In your contract') ?></label>

      <span class='shiptimize-ib'>
        <?php  
          $i = 0; 
          foreach( $this->carriers as $carrier ) {
            $HasPickup = ShiptimizeShipping::is_carrier_pickup_able($carrier); 
            echo ( $i++ ? ", ": "" ).$carrier->Name.($HasPickup ? ' - '.$shiptimize->translate('Has Pickup') : '');
          }
        ?> 
        <br/> <?php echo $shiptimize->translate("You can add them to")?> 
        <a href="<?php echo admin_url("admin.php?page=wc-settings&tab=shipping")?>"><?php echo $shiptimize->translate('shipping zones' )?></a> 
        <?php echo $shiptimize->translate("Don't forget to set the appropriate cost for each carrier if you don't have free shipping for all orders" ); ?>
      </span>
    </div>
  <?php 
  }

  public function print_credentials( ) {  
    global $shiptimize;
  ?> 
    <div class='shiptimize-settings__field'>
      <div class='shiptimize-settings__field'>
        <label class='shiptimize-settings__label'><?php echo $shiptimize->translate('Public Key'); ?></label>
        <input type="text" value="<?php echo $this->obfuscate($this->public_key) ?>" name="shiptimize_public_key" class='shiptimize-settings__key'/>
      </div>
      <label class='shiptimize-settings__label'><?php echo $shiptimize->translate('Private Key'); ?></label>
      <input type="text" value="<?php echo $this->obfuscate($this->private_key) ?>" name="shiptimize_private_key" class='shiptimize-settings__key'/>
    </div>
   <!--  <div class='shiptimize-settings__field'>
      <label class='shiptimize-settings__label'><?php echo $shiptimize->translate('CallbackUrl'); ?></label>
      <span class='shiptimize-ib'>
        <a href='<?php echo  $this->CallbackUrl ?>' target="_blank"><?php echo $this->CallbackUrl ?></a>  
      </span> 
    </div> -->
    <?php if( $this->token ) { ?> 
    <div class='shiptimize-settings__field'>
      <label class='shiptimize-settings__label'><?php echo $shiptimize->translate('Token'); ?></label>
      <span class='shiptimize-ib'>
        <?php echo $this->token .' '.$shiptimize->translate('expires at').' '.$this->token_expires ?> 
      </span>
      <div>
        <label class='shiptimize-settings__label'>&nbsp;</label>
        <small><?php echo $shiptimize->translate('A new token will be automatically requested when this one expires');?></small></div>
    </div> 
    <?php } 
  }


  /** 
   * If people don't have an account allow them to create one directly from the plugin 
   */ 
  public function print_create_account_form( ) {
    global $woocommerce, $shiptimize;  
     ?>
    <section>
      <p> 
      <?php echo sprintf($shiptimize->translate("If you do not have a %s account"), SHIPTIMIZE_BRAND) ?> 
      <a href='<?php echo SHIPTIMIZE_CREATE_ACCOUNT ?>' target='_blank'><?php echo $shiptimize->translate("Click Here")?></a>
      <p> 
    </section>
    <?php  
  }
  

  public function print_export_all(){
    global $shiptimize;
    $statuses = wc_get_order_statuses();   
   ?>   
    <div class='shiptimize-settings__field'>
      <label class='shiptimize-settings__label'><?php echo $shiptimize->translate('When you click "Export Preset Orders" in the orders view, export all orders not exported successfully, with status');?></label>
      <div class='shiptimize-settings__checkbox-group'>
         <?php 

         foreach ( $statuses as $status_key => $status_label) {
          $checked = get_option('shiptimize_export_statuses-'.$status_key) ? 'checked' : ''; 
         ?>
          <span class="shiptimize-checkbox">
            <input name="<?php echo'shiptimize_export_statuses-' . $status_key ?>" <?php echo $checked?> type="checkbox" value="export"/><?php echo $status_label?>
          </span>
        <?php } ?> 
      </div>
    </div>
   <?php  
  }

  /** 
   * @param $field - the custom checkout field 
   * @param $value - the matching addresss propertie 
   **/
  public function print_flexible_checkout_field( $field, $value ) {
   $addressFields = ['CompanyName','Name','Streetname1','Streetname2','HouseNumber','NumberExtension','PostalCode','City','State','Country','Phone','Email']; 

    # var_dump($field);
    echo "<tr><td><label>" . $field['label'] . '</label></td><td>';
    ?>
    <select name='shiptimize_co_fields_<?php echo $field['name']?>'>
        <option value="">-</option>
      <?php foreach ( $addressFields as $field ) { ?> 
        <option <?php if ( $field == $value ) { echo "selected "; } ?> value="<?php echo $field?>">
          <?php echo $field ?>
        </option>
      <?php } ?>
    </select>
    </td></tr>
    <?php 
  }

  public function print_flexible_checkout_fields() {
    $custom_co_settings = get_option('inspire_checkout_fields_settings');
    $oursettings = get_option('shiptimize_custom_checkout_fields'); 

    if( isset( $custom_co_settings['billing'] ) ) { 
      echo "<h4># Billing</h4>
      <table>";
      foreach ( $custom_co_settings['billing'] as $field ) 
      {
        if ( isset( $field['custom_field'] ) ) {
          $value = isset($oursettings[$field['name']])  ? $oursettings[$field['name']] : '';
          $this->print_flexible_checkout_field( $field, $value ); 
        }
      }
      echo "</table>";
    }
    
    if( isset( $custom_co_settings['shipping'] ) ) {
      echo "<h4># Shipping</h4>
      <table>";
      foreach ( $custom_co_settings['shipping'] as $field ) 
      {
        if ( isset( $field['custom_field'] ) ) {
          $value = isset($oursettings[$field['name']])  ? $oursettings[$field['name']] : '';
          $this->print_flexible_checkout_field( $field, $value ); 
        }
      }

      echo "</table>";
    }

  }

  public function print_help_section () {
    global $shiptimize; 
?>

    <div class="shiptimize-settings__section">
      <h2><?php echo $shiptimize->translate('Export');?></h2>
      <div class="shiptimize-settings__field">
        <?php echo $shiptimize->translate('exportdescription');?>
      </div>
     </div>
     <div class="shiptimize-settings__section">
      <h2><?php echo $shiptimize->translate('Status');?></h2>
      <div class="shiptimize-settings__field">
        <?php echo $shiptimize->translate('statusdescription');?>
        <ul class="shiptimize-status-list">
          <li><span class="shiptimize-icon shiptimize-icon-not-exported"></span>  <?php echo $shiptimize->translate('notexporteddescription') ?></li>
          <li><span class="shiptimize-icon shiptimize-icon-success"></span>  <?php echo $shiptimize->translate('successdescription') ?></li>
          <li><span class="shiptimize-icon shiptimize-icon-error"></span>  <?php echo $shiptimize->translate('exporterrordescription') ?></li>
        </ul>
        <ul class="shiptimize-status-list"> 
          <li><span class="shiptimize-icon shiptimize-icon-print-printed"></span>  <?php echo $shiptimize->translate('printsuccesseddescription') ?></li>
          <li><span class="shiptimize-icon shiptimize-icon-print-error"></span>  <?php echo $shiptimize->translate('printerrordescription') ?></li>
        </ul>
      </div>

     <div class="shiptimize-settings__section">
      <h2><?php echo $shiptimize->translate('printlabeltitle');?></h2>
      <div class="shiptimize-settings__field">
        <ul>
          <li><?php echo $shiptimize->translate('labeltermsintro')?></li>
          <li><?php echo $shiptimize->translate('labelbuttondescription'); ?></li>
          <li><img src='<?php echo SHIPTIMIZE_PLUGIN_URL?>/assets/images/print-label.png'/></li>
          <li><?php echo $shiptimize->translate('labelterms')?></li>
        </ul>
      </div>
     </div>

     <div class="shiptimize-settings__section">
     <h2><?php echo $shiptimize->translate('labelbulkprintitle');?></h2>
     <div class="shiptimize-settings__field">
      <?php echo $shiptimize->translate('labelbulkprint');?>
     </div>
 
      <div class="shiptimize-settings__section">
        <h2><?php echo $shiptimize->translate('useapititle');?></h2>
        <div class="shiptimize-settings__field">
        <?php 
            $activateapihtml = '<p>' . $shiptimize->translate('usewpapi') . ':'; 
            $activateapihtml .= '<select name="shiptimize_usewpapi">' 
            . '<option Value="0" ' . ($this->usewpapi ? '' : 'selected') . '>' . $shiptimize->translate('no') .  '</option>'
            . '<option value="1" ' . ($this->usewpapi ? 'selected' : '') . '>' . $shiptimize->translate('yes') .  '</option>';
            $activateapihtml .= '</select></p>';

            if($this->is_api_active ) {  
              echo sprintf($shiptimize->translate('useapihelp'), $activateapihtml);  
            } 
            else { 
              echo sprintf($shiptimize->translate('useapihelpinactive'), $activateapihtml); 
            }
      echo "</div></div>";  
  }

  public function print_map( ) { 
    global $shiptimize;

  ?> 
    <div class='shiptimize-settings__field'>
      <label class='shiptimize-settings__label'><a target='_blank' href="https://cloud.google.com/console/google/maps-apis/overview">Google Maps Key</a></label>
      <span class='shiptimize-ib'>
        <input type='text' class='shiptimize-settings__maps_key' value="<?php echo $this->maps_key?>" name='shiptimize_maps_key'/>
        <br/><small><?php echo sprintf($shiptimize->translate('If a google key is provided the map served will be a google map else an openmap will be shown'),"https://developers.google.com/maps/documentation/geolocation/get-api-key")?></small>
      </span>
    </div>
  <?php 
  }

  /** 
   * If the user is using the table_rate_shipping_plus plugin 
   * then let them assign carriers to each rate 
   * {"id":"1","service":"2","zone":"1","class":"0","basis":"weight","min":"0","max":"*","cost":"20","item_cost":"0","weight_cost":"0","enabled":"1"} 
   * 
   */ 
  public function print_table_rate_shipping_plus(){
    $rates = get_option('mh_wc_table_rate_plus_table_rates');
    if(!$rates){ 
      echo "No rates are defined yet in table rate plus";
      return; 
    }

    if(empty($this->carriers)){
      echo "no carriers defined yet, if you've inserted valid credentials refresh the page <br/>"; 
      return;
    }

    $services = get_option('mh_wc_table_rate_plus_services');
    $zones = get_option('mh_wc_table_rate_plus_zones');   

    $rate_carriers = get_option('shiptimize_table_rate_shipping_plus');

    
    echo "<p> If you are using table rates you don't need to add extra methods to shipping zones, just choose a carrier from the list bellow. </p>";



    echo '<table class="wp-list-table widefat fixed striped posts shiptimize_table_rate_shipping_plus" style="width:auto">
    <thead>
    <tr>
      <td>Id</td>
      <td>Service</td>
      <td>Zone</td>
      <td>Class</td>
      <td>Basis</td>
      <td>Min</td>
      <td>Max</td>
      <td>Cost</td>
      <td>Item Cost</td>
      <td>Weight Cost</td> 
      <td>Carrier</td>
    </tr>
    </thead>';
    foreach ( $rates as $rate ){ 
      //echo "<tr><td colspan='9'>".var_export($rate, true)."</td></tr>";
      $service_name = ''; 
      $zone_name = ''; 
      $rate_id = $rate['id'];

      //Service 
      foreach ( $services as $service ){
        if ( $service['id'] == $rate['service'] ){
          $service_name = $service['name']; 
        }
      }

      //Zone 
      foreach ( $zones as $zone ) { 
        if( $zone['id'] == $rate['zone']){
          $zone_name = $zone['name'];  
        }
      }
 
      echo "   <tr>
      <td>$rate_id</td>
      <td>$service_name</td>
      <td>$zone_name</td>
      <td>" . ( $rate['class'] ? $rate['class'] : '-' ) . "</td>
      <td>{$rate['basis']}</td>
      <td>{$rate['min']}</td>
      <td>{$rate['max']}</td>
      <td>{$rate['cost']}</td>
      <td>{$rate['item_cost']}</td>
      <td>{$rate['weight_cost']}</td> 
      <td> 
        <select name='table_rate_carrier_{$rate_id}' id='shiptimize__table_rates_plus_$rate_id' onchange='shiptimize.platform.selectOptions(jQuery(this))'>
          <option>-</option>
      ";

      foreach( $this->carriers as $carrier ) {
        $rate = isset($rate_carriers[$rate_id]) ? $rate_carriers[$rate_id] : null; 
        $selected = ( $rate && $carrier->Id == $rate['carrier_id'] ) ? 'selected' : ''; 
        echo "<option value='$carrier->Id' $selected>$carrier->Name</option>";
      }

      echo "</select>
      <select name='shiptimize_service_level_$rate_id ' class='shiptimize__service-level shiptimize__carrier-options'>
      </select>
       <select name='shiptimize_extra_options_$rate_id' class='shiptimize__extra-options shiptimize__carrier-options'>
      </select>
      </td>
    </tr>";
    }

    echo "</table>

    <script>
    jQuery( function() { ";
    foreach( $rate_carriers as $id => $rate ) {
      if( isset($rate['service_level']) && ($service = $rate['service_level']) ){
        echo "
      shiptimize.platform.selectServiceLevel(jQuery('#shiptimize__table_rates_plus_$id'), '$service' ); 
        ";  
      }  

      if( isset($rate['extra_option']) && ($extra_option = $rate['extra_option']) ){ 
       echo "shiptimize.platform.selectExtraOptions(jQuery('#shiptimize__table_rates_plus_$id'), '$extra_option' );";
      }
    }
    echo "
    });
    </script>";

    
    $this->table_rate_checks($rates, $zones); 
  }
  public function print_settings_form () {
    global $shiptimize; 

    if (!function_exists('mb_detect_encoding')) {
      echo "<p> !!!!The mbstring extension is not installed!!!! This may cause issues in sending orders back to Shiptimize, if your website is not utf8, since we cannot detect your site's encoding.</p>"; 
    }
?> 
    
      <div class='shiptimize-settings__section'>
        <h2><?php echo $shiptimize->translate('Credentials')?></h2>
        <?php 
          $this->print_credentials(); 
        ?>
      </div>
     <div class="shiptimize-settings__section">
      <h2><?php echo $shiptimize->translate('Export Preset Orders');?></h2>
      <?php 
        $this->print_export_all();
      ?>
     </div>
     <div class="shiptimize-settings__section">
      <?php $this->print_automatic_export();?>
     </div>
     <?php if (defined('SHIPTIMIZE_CHECKOUT') && SHIPTIMIZE_CHECKOUT > 0) {  ?>
       <div class="shiptimize-settings__section">
        <h2><?php echo $shiptimize->translate('pickuppointsoptions');?></h2>
        <input type='checkbox' name="shiptimize_pickupdisable" value="1" <?php echo get_option('shiptimize_pickupdisable') ? 'checked': '' ?>/> <?php echo $shiptimize->translate('pickuppointsdisable'); ?>
       </div>
      <?php } ?>
      <div class='shiptimize-settings__section'>
        <h2><?php echo $shiptimize->translate('Map');?></h2>
        <?php
          $this->print_map();
        ?>
      </div>
     
      <div class='shiptimize-settings__section'>
        <?php 
          $this->print_carriers();  
        ?>
      </div>
      <div class='shiptimize-settings__section'>
        <h2><?php echo $shiptimize->translate('printlabeltitle');?></h2>
        <input type='checkbox' name="shiptimize_labelagree" value="1" <?php echo get_option('shiptimize_labelagree') ? 'checked': '' ?>/> <?php echo $shiptimize->translate('labelagree'); ?> 
      </div>

      <?php // 
      if( is_plugin_active( 'mh-woocommerce-table-rate-shipping-plus/mh-wc-table-rate-plus.php' ) ): ?>
          <h2>Table Rate Shipping Plus</h2>
          <?php $this->print_table_rate_shipping_plus();  ?>
       <?php endif; 

      $this->print_brazilian_fields();  
      //If there's a marketplace plugin display aditional marketplace Settings here 
      
      if($this->marketplace){
        $this->marketplace->shiptimize_options(); 
      } 

      $this->print_hide_not_free();
      ?>

      <div class='shiptimize-settings__section'>
        <h2><?php echo $shiptimize->translate('exportvirtualtitle');?></h2>
        <input type='checkbox' name="shiptimize_export_virtual_products" value="1" <?php echo get_option('shiptimize_export_virtual_products') ? 'checked': '' ?>/> <?php echo $shiptimize->translate('exportvirtualproducts'); ?>  
        <br/><input type='checkbox' name="shiptimize_export_virtual_orders" value="1" <?php echo get_option('shiptimize_export_virtual_orders') ? 'checked': '' ?>/> <?php echo $shiptimize->translate('exportvirtualorders'); ?> 
      </div>   

      <?php // 
      if( is_plugin_active( 'flexible-checkout-fields/flexible-checkout-fields.php' ) ): ?>
          <h2>Flexible Checkout Fields</h2>
      <?php $this->print_flexible_checkout_fields(); 
      endif;  
      submit_button();   
  }


 
  public function print_shiptimize_options() {
    global $shiptimize; 

  ?>
      <h1><?php echo 'Shiptimize ' . $shiptimize->translate('Settings');?></h1>  
      <?php 
          $this->print_create_account_form();

          if(shiptimize_is_marketplace()) {
            echo "<p><b>This site contains a marketplace plugin. Individual sellers will have access to their shiptimize settings in their dashboard</b></p>";
          }
      ?>

      <nav class="nav-tab-wrapper">
        <a href="#!" onclick="shiptimize.platform.selectTab(0)" class="nav-tab nav-tab-active"><?php echo $shiptimize->translate('settings')?></a>
        <a href="#!" onclick="shiptimize.platform.selectTab(1)" class="nav-tab"><?php echo $shiptimize->translate('help');?></a>
      </nav>


      <div class='tab active'>
        <?php echo $this->print_settings_form(); ?>
      </div>
      <div class='tab '>
        <?php echo $this->print_help_section(); ?>
      </div>

    <script>
      var shiptimize_carriers = <?php echo json_encode( $this->carriers ); ?>; 
    </script> 
  <?php 
  }

  public function print_hide_not_free() {
    global $shiptimize; 
    echo "<div class='shiptimize-settings__section'><h2>" . $shiptimize->translate( 'hidenotfreetitle' ) . "</h2>"; 
    echo '<input type="checkbox" name="shiptimize_hide_not_free" value="1" ' . ( $this->hidenotfree ? 'checked': '') . '/> ' .  $shiptimize->translate('hidenotfree');
    echo "</div>"; 
  }


}

$shiptimize_options_ui = new ShiptimizeOptionsUIDefault(); 
