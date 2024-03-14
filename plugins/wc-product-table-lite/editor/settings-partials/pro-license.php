<div class="wcpt-toggle-options" wcpt-model-key="pro_license">
  <div class="wcpt-editor-light-heading wcpt-toggle-label">
    PRO License <?php echo wcpt_icon('chevron-down'); ?>
  </div>

  <?php
    function wcpt_print_license_key_markup($label= 'License key', $addon_slug= false, $addon_item_id= false){
      ?>
      <div 
        class="wcpt-editor-row-option wcpt-license-container"
        <?php
          if( $addon_slug ){
            echo " wcpt-addon-slug='$addon_slug' wcpt-addon-item-id='$addon_item_id' wcpt-model-key='$addon_slug' ";
          }
        ?>
      >
        <label>
          <?php echo $label; ?> - License key
          <span 
            class="wcpt-license-key-activated"
            wcpt-panel-condition="prop"
            wcpt-condition-prop="status"
            wcpt-condition-val="active"              
          >✓ Activated</span>
        </label>
        <input type="text" wcpt-model-key="key" class="wcpt-license-key" />
        <input type="hidden" wcpt-model-key="status" class="wcpt-license-key-status" />
        <label class="wcpt-license-feedback">
          <span class="wcpt-verifying-license-message wcpt-hide">
            <?php wcpt_icon('loader', 'wcpt-rotate'); ?>
            Connecting to WCPT server, please wait for response...
          </span>
          <span class="wcpt-response-active-elsewhere wcpt-hide">
            <?php wcpt_icon('x'); ?>
            Sorry! This license key could not be activated here as it is still active on another site.
          </span>
          <span class="wcpt-response-invalid-key wcpt-hide">
            <?php wcpt_icon('x'); ?>
            Error! This license key is not valid. Please check your purchase email.
          </span>
          <span class="wcpt-response-invalid-response wcpt-hide">
            <?php wcpt_icon('x'); ?>
            Error! No valid response received. Try later or contact plugin support.
          </span>
          <span class="wcpt-response-activated wcpt-hide">
            <?php wcpt_icon('check'); ?>
            Congrats! This license is now activated on your site.
          </span>
          <span class="wcpt-response-deactivated wcpt-hide">
            <?php wcpt_icon('check'); ?>
            Done! This license has been deactivated on your site.
          </span>
        </label>

        <label for="" style="cursor: default;">
          <button
            class="wcpt-button wcpt-activate-license"
            data-wcpt-purpose="activate"
            data-wcpt-nonce="<?php echo wp_create_nonce( "wcpt" ); ?>"
            <?php echo wcpt_get_license_key_status($addon_slug) == 'active' ? 'disabled' : ''; ?>
          >
            Activate
          </button>
          <button
            class="wcpt-button wcpt-deactivate-license"
            data-wcpt-purpose="deactivate"
            data-wcpt-nonce="<?php echo wp_create_nonce( "wcpt" ); ?>"
            <?php echo wcpt_get_license_key_status($addon_slug) == 'inactive' ? 'disabled' : ''; ?>
          >
            Deactivate
          </button>
        </label>
      </div>
      <?php
    }

    wcpt_print_license_key_markup( 'WooCommerce Product Table PRO', false, false );

    global $wcpt_addons;
    if( $wcpt_addons ){
      echo '<div class="wcpt-editor-row-option" wcpt-model-key="addon">';    
      echo '<label style="font-weight: bold;">Addons</label>';
      foreach( $wcpt_addons as $addon ){
        wcpt_print_license_key_markup( $addon['name'], $addon['slug'], $addon['item_id'] );
      }
      echo '</div>';
    }

  ?>
  <div class="wcpt-editor-row-option">  
    <label>
      <small>
        <strong>Note:</strong> You can get free license activation on a test domain if it matches any of these patterns: <br>
        localhost<br>
        10.0.0.0/8<br>
        172.16.0.0/12<br>
        192.168.0.0/16<br>
        *.dev<br>
        *.local<br>
        dev.*<br>
        staging.*
      </small>      
    </label>
  </div>
</div>