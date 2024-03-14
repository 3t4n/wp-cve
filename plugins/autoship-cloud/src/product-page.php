<?php

/**
* Checks if an One or more invalid products exist stored in site settings.
* @return bool True if 1+ exist else false.
*/
function autoship_check_site_settings_for_invalid_products() {

  // Get the option value.
  $settings = autoship_get_remote_saved_site_settings( false, true );
  if ( is_wp_error( $settings ) || empty( $settings ) || !isset( $settings['hasActiveInvalidProducts'] ) || empty( $settings['hasActiveInvalidProducts'] ) )
  return false;

  return $settings['hasActiveInvalidProducts'];

}

/**
* Filters the Show Status Bubble on the Autoship Cloud Menu Option when invalid products exist.
* @param bool $display True if already showing else false.
*/
function autoship_admin_products_health_status_menu_bubble( $display ) {

  // Check if any need to actually show a badge. Might already be showing or no invalids.
  $invalids = autoship_check_site_settings_for_invalid_products();
  return $display ? $display : $invalids;

}
add_action( 'autoship_show_admin_health_status_menu_bubble', 'autoship_admin_products_health_status_menu_bubble', 10, 1 );


/**
* Shows a Status Bubble on the Autoship Cloud > Products SubMenu Option when invalid products exist.
* @param array $menu_options An array of the current Autosihip Menu Options.
*/
function autoship_admin_products_submenu_health_status_menu_bubble( $menu_options ) {


  // Check if invalids exist.
  $invalids = autoship_check_site_settings_for_invalid_products();

  // Add the Badge if to the menu title if it exists. Might now if customized.
  if ( $invalids &&  isset( $menu_options['products'] ) )
  $menu_options['products']['menu_title']  .= '<span class="autoship-health"><span class="health-error">!</span></span>';

  return $menu_options;

}
add_filter( 'autoship_admin_settings_submenu_pages', 'autoship_admin_products_submenu_health_status_menu_bubble', 10, 1 );

// ==========================================================
// Edit Product Page Functions
// ==========================================================

/**
* Get Product Summary Data Error Details.
* @param array|wp_error $summary_data
* @param WC_Product $product
* @return array The Error Data.
*/
function autoship_get_product_summary_error_details( $summary_data, $product ){

  // Gather the Error info for the template.
  $error_data = array(
    'type' => '',
    'code' => '',
    'msg'  => ''
  );
  $error = '';
  if ( is_wp_error( $summary_data ) ){
    $error = $summary_data;
    $error_data['type'] = 'error';
    $error_data['code'] = $summary_data->get_error_code();
    $error_data['msg']  = $summary_data->get_error_message( $error_data['code'] );
  } else if ( isset( $summary_data['SyncError'] ) && is_wp_error( $summary_data['SyncError'] ) ){
    $error = $summary_data['SyncError'];
    $error_data['type'] = 'error';
    $error_data['code'] = $summary_data['SyncError']->get_error_code();
    $error_data['msg']  = $summary_data['SyncError']->get_error_message( $error_data['code'] );
  }

  // Check if the current error should be a warning not error
  if ( apply_filters( 'autoship_sync_status_error_as_warning', false, $error_data, $product ) )
  $error_data['type'] = 'warning';

  return $error_data;

}

/**
* Returns the General Notice html to display for Active Scheduled Orders.
* @param array|wp_error $summary_data
* @param WC_Product $product
* @return string The notice.
*/
function autoship_get_active_product_summary_general_notice( $summary_data, $product ){

  if ( empty( $summary_data ) )
  return '';

  if ( is_wp_error( $summary_data ) )
  return $summary_data->get_error_message();

  $total_string = array();
  if ( isset( $summary_data['QuantityScheduled'] ) && $summary_data['QuantityScheduled'] ){

    if ( $summary_data['TotalQuantityScheduledActive'] )
    $total_string[] = $summary_data['TotalQuantityScheduledActive'] . ' Active';

    if ( $summary_data['TotalQuantityScheduledPaused'] )
    $total_string[] = $summary_data['TotalQuantityScheduledPaused'] . ' Paused';

    if ( $summary_data['TotalQuantityFailed'] )
    $total_string[] = $summary_data['TotalQuantityFailed'] . ' Failed';

    if ( $summary_data['TotalQuantityProcessing'] )
    $total_string[] = $summary_data['TotalQuantityProcessing'] . ' Processing';

  }

  $products_report_url = autoship_admin_products_page_url();

  $scheduled_notice = !empty( $total_string ) ? sprintf(
    __("<hr/><p>There are a total of <strong>%d Scheduled Orders ( %s )</strong> containing %s.<br/><strong>Important!</strong> Changes made to product data that is already synchronized (i.e. changing Product Type, Published Status, Moving to Trash, Deleting Products, etc. ) may <a href=\"%s\">invalidate the product in QPilot</a> and prevent it from processing with Scheduled Orders.</p>", 'autoship' ),
    $summary_data['QuantityScheduled'],
    implode(', ', $total_string ),
    'variable' == $product->get_type() ? 'this Products\' Variations' : 'this Product',
    $products_report_url )
    : sprintf( __( '<hr/><p><strong><span style=\"color:#11a0d2;\">Important!</span></strong> Changes made to product data that is already synchronized (i.e. changing Product Type, Published Status, Moving to Trash, Deleting Products, etc. ) may <a href="%s">invalidate the product in QPilot</a> and prevent it from processing with Scheduled Orders.</p>', 'autoship' ), $products_report_url );
  return $scheduled_notice;

}

/**
* Load Autoship Cloud Data on Edit Product Screen.
* TODO: Currently hooked into the admin notices but could try to find
*       better hook for early data load.
*/
function autoship_load_product_summary_data_display_notice (){

  // Get the Current Screen object and if doesn't exist escape.
  $screen = get_current_screen();
  if ( !$screen )
  return;

  if ( 'product' !== $screen->id || 'edit' !== $screen->parent_base )
  return;

  global $post;
	$product  = wc_get_product( $post->ID );
  $valid    = autoship_is_valid_sync_product( $post->ID );
  $active   = 'yes' == autoship_sync_active_enabled( $product );

  // Allow devs to disable admin notice if desired.
  if( apply_filters('autoship_display_edit_product_sync_admin_notice', !$active || !$valid, $product ) )
  return;

  /**
  * Get the Stored Autoship Product Sync Data
  * Retrieved and Initially stored in @see autoship_product_metaboxes_template_data()
  * hooked to add_meta_boxes_product
  */
  $summary_data = $active ? autoship_get_stored_product_sync_summary_data( $post->ID ) : array();

  // Get any Error or Warning Data.
  $error_data = autoship_get_product_summary_error_details( $summary_data, $product );

  // Check for Sync Error for active and valid products
  if ( $active && $valid && ( !empty( $error_data['type'] ) && ( 'error' == $error_data['type'] ) ) ){

    $title  = __( 'Error', 'autoship' );
    $status = 'error';
    $notice = sprintf( "<p>%s</p>", $error_data['msg'] );

  // If active and no general sync error
  } else if ( !empty( $summary_data ) && $active ){

    if ( $summary_data['QuantityScheduled'] ){

      $total_string = array();
      if ( $summary_data['TotalQuantityScheduledActive'] )
      $total_string[] = $summary_data['TotalQuantityScheduledActive'] . ' Active';

      if ( $summary_data['TotalQuantityScheduledActive'] )
      $total_string[] = $summary_data['TotalQuantityScheduledPaused'] . ' Paused';

      if ( $summary_data['TotalQuantityFailed'] )
      $total_string[] = $summary_data['TotalQuantityFailed'] . ' Failed';

      if ( $summary_data['TotalQuantityProcessing'] )
      $total_string[] = $summary_data['TotalQuantityProcessing'] . ' Processing';

    }

    // get the notice
    $scheduled_notice = autoship_get_active_product_summary_general_notice( $summary_data, $product );

    $title  = __( 'Active', 'autoship' );
    $status = 'active';
    $notice = apply_filters( 'autoship_product_admin_summary_metabox_notice', sprintf( __('<p>This product is currently <strong>Active</strong>.%s', 'autoship'), $scheduled_notice ), $status, $summary_data, $product );


  // Active but for some reason summary data isn't loaded.
  } else if ( $active ){

    $products_report_url = autoship_admin_products_page_url();
    $api_health_url = autoship_admin_settings_page_url();

    $title  = __( 'Error', 'autoship' );
    $status = 'error';
    $notice = sprintf( __( '<p>Product not synchronized: A problem was encountered while trying to retrieve this Product\'s Sync Information.  Please confirm your <a href="%s">API connection is healthy</a> and ensure there are no issues with this product in the <a href="%s">Autoship Cloud > Products</a> report.</p>', "autoship" ), $api_health_url, $products_report_url);

  }

  ?>
  <div class="autoship-admin-notice notice autoship-<?php echo $status;?>-notice is-dismissible">
    <h2><?php echo sprintf( __( 'Autoship Product Status: <span class="%s %s">%s</span>', 'autoship' ), $status, $error_data['type'], $title ); ?></h2>
    <?php echo $notice; ?>
    <?php do_action( 'autoship_product_admin_summary_metabox_notice', $status, $summary_data, $product ); ?>
  </div>
  <?php

}
add_action( 'admin_notices', 'autoship_load_product_summary_data_display_notice', 99 );

/**
 * Load Autoship Cloud Upsert Errors and Display on Edit Product Screen.
 */
function autoship_display_edit_product_upsert_errors (){

  // Get the Current Screen object and if doesn't exist escape.
  if ( ( $screen = get_current_screen() ) && 'product' === $screen->id && 'edit' === $screen->parent_base && !empty( $messages = autoship_get_messages( 'autoship_product_upsert_messages' ) ) ){

    ?>
    <div class="autoship-admin-notice notice autoship-error-notice is-dismissible">
      <h2><?php echo sprintf( __( 'Autoship Product Sync Error', 'autoship' ) ); ?></h2>
      <?php foreach ( $messages as $message )
      echo '<p>' . $message['message'] . '</p>'; ?>
    </div>
    <?php

  }

}
add_action( 'admin_notices', 'autoship_display_edit_product_upsert_errors', 99 );

/**
* Adds the Autoship Product Tab for Simple and Variable Products
* @see woocommerce_product_data_tabs
*/
function autoship_product_autoship_tab( $tabs ) {

	$tabs['autoship'] = array(
		'label'  => __( 'Autoship', 'autoship' ),
		'target' => 'autoship_product_data',
		'class'  => array( 'show_if_simple', 'show_if_variable' )
	);

	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'autoship_product_autoship_tab', 10, 1 );

/**
* Outputs the Autoship data tab in the WooCommerce Edit Product Screen
* @see woocommerce_product_data_panels
*/
function autoship_product_autoship_tab_content() {

  global $post;

  $_product = wc_get_product($post->ID);
  $active = 'yes' == autoship_sync_active_enabled( $_product );

  $tab_classes = apply_filters( 'autoship_product_custom_fields_options_tab_clases', 'show_if_simple show_if_variable', $_product );

	?>
	<div id="autoship_product_data" class="panel woocommerce_options_panel <?php echo $active ? 'autoship-active' : '';?>">
		<div class="options_group <?php echo $tab_classes; ?>">
			<?php do_action( 'woocommerce_product_options_autoship_product_data' ); ?>
		</div><!-- .options_group -->
	</div><!-- #autoship_product_data -->
	<?php
}
add_action( 'woocommerce_product_data_panels', 'autoship_product_autoship_tab_content' );

/**
* Outputs the Autoship Custom Fields for a Product
* @see woocommerce_product_options_autoship_product_data
*
* Outputs the following Autoship Custom Fields:
* 	_autoship_schedule_options_enabled
* 	_autoship_checkout_price
* 	_autoship_recurring_price
* 	_autoship_schedule_order_enabled
* 	_autoship_schedule_process_enabled
* 	_autoship_override_frequency_options
*   _autoship_override_product_data
*   _autoship_title_override
*   _autoship_weightunit_override
*   _autoship_lengthunit_override
*   _autoship_group_ids
*
*	The following dynamically created fields are
*	controlled by the autoship_get_frequency_options_count()
* 	_autoship_frequency_type_{$i}
* 	_autoship_frequency_{$i}
* 	_autoship_frequency_display_name_{$i}
*/
function autoship_print_product_custom_fields() {

  global $post;

  $_product = wc_get_product($post->ID);
  $active = 'yes' == autoship_sync_active_enabled( $_product );
  $next_occurrence =  autoship_get_product_relative_next_occurrence( $_product->get_id() );
  $next_occurrence_type = autoship_get_product_relative_next_occurrence_type( $_product->get_id() );

  $option_classes = apply_filters( 'autoship_simple_product_custom_fields_options_clases', 'show_if_simple hide_if_variable', $_product );

  ?>

  <div id="autoship-main-edit-product-options" class="options_group">

    <h4><?php echo __('Autoship Options', 'autoship'); ?></h4>

    <div class="options_group">

      <?php
      // Only show option when Global Sync is Not Active
      if ( !autoship_global_sync_active_enabled() ):?>

      <?php
      // Enable schedule options
      woocommerce_wp_checkbox(
        array(
          'id' => '_autoship_sync_active_enabled',
          'label' => __('Activate Product Sync', 'autoship'),
          'description' => __('Enable this setting to activate this product for Autoship Cloud Sync.', 'autoship'),
        )

      );?>

      <?php endif;?>

      <div class="autoship-sync-active-option-group">

        <?php
        // Enable schedule options
        woocommerce_wp_checkbox(
          array(
            'id' => '_autoship_schedule_options_enabled',
            'label' => __('Enable Schedule Options', 'autoship'),
            'description' => __('Enable this setting to show Autoship Schedule Options on the product page.', 'autoship')
          )
        );?>

        <?php do_action( 'autoship_before_print_product_custom_price_fields', $_product ); ?>

        <div class="<?php echo $option_classes; ?>">

          <?php
          // Checkout price
          woocommerce_wp_text_input(
            array(
              'id' => '_autoship_checkout_price',
              'label' => sprintf(__('Autoship Checkout Price(%s)', 'autoship'), get_woocommerce_currency_symbol() ),
              'description' => __('Override the product price for autoship checkout only.', 'autoship'),
              'placeholder' => __('(Optional)', 'autoship'),
              'data_type' => 'price',
              'desc_tip' => true
            )
          );?>

        </div><!-- Checkout Price -->

        <div class="<?php echo $option_classes; ?>">

          <?php
          // Recurring price
          woocommerce_wp_text_input(
            array(
              'id' => '_autoship_recurring_price',
              'label' => sprintf(__('Autoship Recurring Price(%s)', 'autoship'), get_woocommerce_currency_symbol()),
              'description' => __('Override the product price for recurring orders only.', 'autoship'),
              'placeholder' => __('(Optional)', 'autoship'),
              'data_type' => 'price',
              'desc_tip' => true
            )
          );?>

        </div><!-- Recurring price -->

        <?php do_action( 'autoship_after_print_product_custom_price_fields', $_product ); ?>

        <?php

        // Only display the Schedule Orders Enabled and
        // Process on Scheduled orders options on this tab if
        // The product is simple otherwise display on variation level.
        if ( apply_filters( 'autoship_simple_product_display_scheduled_order_enabled_edit_fields', $_product->is_type('simple'), $_product ) ) { ?>

        </br>
        <h4><?php echo __('Autoship Scheduled Order Settings', 'autoship'); ?></h4>
        <p><?php echo __('Autoship Scheduled Order settings can be updated in <a target="_blank" href="'. admin_url( '/admin.php?page=products' ).'">Autoship Cloud > Products</a>', 'autoship'); ?></p>

        <?php
        //add_update_Schedule_option($_product->get_id());
        // Enable This Product to be added to Scheduled Orders
        woocommerce_wp_checkbox(
          array(
            'id' => '_autoship_schedule_order_enabled',
            'label' => __('Add to Scheduled Order', 'autoship'),
            'custom_attributes' => array('readonly' => 'readonly', 'disabled' => 'disabled'),
            'description' => __(' Allow customers to add this product to existing Scheduled Orders.', 'autoship'),
          )
        );
        // Enable This Product to be Processed on Scheduled Orders
        woocommerce_wp_checkbox(
          array(
            'id' => '_autoship_schedule_process_enabled',
            'label' => __('Process on Scheduled Orders', 'autoship'),
            'custom_attributes' => array('readonly' => 'readonly', 'disabled' => 'disabled'),
            'description' => __(' Allow this product to process on Scheduled Orders. <a target="_blank" href="https://support.autoship.cloud/article/441-product-availability-and-stock-status">Read this</a> before disabling this setting.', 'autoship'),
          )
        );?>

        <?php } ?>

        <?php do_action( 'autoship_after_print_product_custom_order_settings_fields', $_product ); ?>

      </div><!-- /.autoship-sync-active-option-group -->

    </div><!-- options_group -->

    <div class="autoship-sync-active-option-group">

      <div class="<?php echo $option_classes; ?>">

        <h4><?php echo __('Autoship Group IDs', 'autoship'); ?></h4>

        <div class="options_group">

          <?php
          // Assigned Group Ids
          woocommerce_wp_text_input(
            array(
              'id' => '_autoship_group_ids',
              'label' => __('Assigned Group IDs', 'autoship'),
              'description' => __('Assign one or more group ids. Enter multiple group ids in a comma separated list.', 'autoship'),
              'placeholder' => __('(Optional)', 'autoship'),
              'data_type' => 'text',
              'desc_tip' => true
            )
          );?>

        </div><!-- Assign Autoship Group Ids -->

      </div>

    </div>

    <div class="autoship-sync-active-option-group">

      <div class="<?php echo $option_classes; ?>">

      <?php

      // Show or hide Product Data Overrides based on checkbox value.
      $override = autoship_override_product_data_enabled( $_product->get_id() );
      $show_prod_overrides = 'yes' !== $override ? 'style="display:none;"' : '';

      ?>

      <h4><?php echo __('Product Data Overrides', 'autoship'); ?></h4>
      <div class="options_group">

        <?php
        // Enable AUtoship Frequency Overrides
        woocommerce_wp_checkbox(
          array(
            'id' => "_autoship_override_product_data",
            'label' => __('Override Defaults', 'autoship'),
            'class' => 'autoship_hide_show_toggler',
            'description' => __('Select to override the default Product Data in QPilot.', 'autoship'),
            'desc_tip' => false,
            'value' => $override,
            'custom_attributes' => array( 'data-target' => '.show_hide_product_data_group_simple' ),
          )
        );?>

      </div><!-- Enable AUtoship Frequency Overrides -->

      <div class="product_data_options show_hide_product_data_group_simple" <?=$show_prod_overrides?>>

        <?php

        woocommerce_wp_text_input(
          array(
            'id' => "_autoship_title_override",
            'label' => __("Product Title", 'autoship'),
            'description' => __('Enter a custom Product Title for QPilot', 'autoship'),
            'placeholder' => autoship_get_product_display_name( $_product ),
            'data_type' => 'text',
            'type' => 'text',
            'desc_tip' => true
          )
        );

        woocommerce_wp_select(
          array(
            'id' => "_autoship_weightunit_override",
            'label' => __("Weight unit", 'autoship'),
            'description' => __('Select a Unit of Measurement for Weight', 'autoship'),
            'options' => array(
              ''          => __('--Select type--', 'autoship'),
              'Kilogram'  => 'kg',
              'Gram'      => 'g',
              'Pound'     => 'lbs',
              'Ounce'     => 'oz',
            ),
            'data_type' => 'string',
            'desc_tip' => true
          )
        );

        woocommerce_wp_select(
          array(
            'id' => "_autoship_lengthunit_override",
            'label' => __("Dimensions Unit", 'autoship'),
            'description' => __('Select a Unit of Measurement for Length', 'autoship'),
            'options' => array(
              '' => __('--Select type--', 'autoship'),
              'Meter'       => 'm',
              'Centimeter'  => 'cm',
              'Milimeter'   => 'mm',
              'Inch'        => 'in',
              'Foot'        => 'ft',
              'Yard'        => 'yd',
            ),
            'data_type' => 'string',
            'desc_tip' => true
          )
        );?>

      </div><!-- .product_data_options -->

      </div>

    </div><!-- /.autoship-sync-active-option-group -->

    <?php do_action( 'autoship_before_print_product_custom_frequency_option_fields', $_product ); ?>

    <div class="autoship-sync-active-option-group">

      <?php

      // Show or hide Frequency Overrides based on checkbox value.
      $override = autoship_override_frequency_options_enabled( $post->ID );
      $show_freq_overrides = 'yes' !== $override ? 'style="display:none;"' : '';

      ?>

      <h4><?php echo __('Frequency Options', 'autoship'); ?></h4>
      <div class="options_group">

        <?php
        // Enable AUtoship Frequency Overrides
        woocommerce_wp_checkbox(
          array(
            'id' => "_autoship_override_frequency_options",
            'label' => __('Override Defaults', 'autoship'),
            'class' => 'autoship_hide_show_toggler',
            'description' => __('Select to override the default frequency options.', 'autoship'),
            'desc_tip' => false,
            'value' => $override,
            'custom_attributes' => array( 'data-target' => '.show_hide_frequency_options_group_simple' ),
          )
        );?>

      </div><!-- Enable AUtoship Frequency Overrides -->

      <div class="frequency_options show_hide_frequency_options_group_simple" <?=$show_freq_overrides?>>

        <?php
        $autoship_option_count = autoship_get_frequency_options_count();
        for ($i = 0, $n = 1; $i < $autoship_option_count; $i++, $n++) { ?>

        <div class="options_group show_hide_frequency_options">
          <h4><?php echo sprintf(__('Frequency Option %d', 'autoship'), $n);?></h4>
          <?php

          woocommerce_wp_select(
            array(
              'id' => "_autoship_frequency_type_{$i}",
              'label' => __("Frequency Type", 'autoship'),
              'description' => __('Select a frequency type', 'autoship'),
              'options' => array(
              '' => __('--Select frequency type--', 'autoship'),
              'Days' => __('Days', 'autoship'),
              'Weeks' => __('Weeks', 'autoship'),
              'Months' => __('Months', 'autoship'),
              'DayOfTheWeek' => __('Day of the week', 'autoship'),
              'DayOfTheMonth' => __('Day of the month', 'autoship')
            ),
            'data_type' => 'string',
            'desc_tip' => true
            )
          );

          woocommerce_wp_text_input(
            array(
              'id' => "_autoship_frequency_{$i}",
              'label' => __("Frequency", 'autoship'),
              'description' => __('Enter a frequency number', 'autoship'),
              'placeholder' => __('Enter a frequency number', 'autoship'),
              'data_type' => 'number',
              'type' => 'number',
              'desc_tip' => true
            )
          );
          woocommerce_wp_text_input(
            array(
              'id' => "_autoship_frequency_display_name_{$i}",
              'label' => __("Display Name", 'autoship'),
              'description' => __('Enter a display name for this frequency option', 'autoship'),
              'placeholder' => __('(Optional) Enter a display name', 'autoship'),
              'data_type' => 'string',
              'desc_tip' => true
            )
          );?>

        </div><!-- Frequency Overide -->

        <?php } ?>

      </div><!-- .frequency_options -->

    </div><!-- /.autoship-sync-active-option-group -->

    <?php do_action( 'autoship_after_print_product_custom_frequency_option_fields', $_product ); ?>

    <div class="autoship-sync-active-option-group">

      <h4><?php echo __('Next Occurrence Options', 'autoship'); ?></h4>

      <?php

      // Show or hide Relative Next Occurrence settings based on checkbox value.
      $relative_enabled = autoship_relative_next_occurrence_enabled( $post->ID );
      $show_relative = 'yes' !== $relative_enabled ? 'style="display:none;"' : '';

      ?>

      <div class="options_group">

        <?php

        // Enable Relative Next Occurrence
        woocommerce_wp_checkbox(
          array(
            'id' => "_autoship_relative_next_occurrence_enabled",
            'label' => __('Override Defaults', 'autoship'),
            'class' => 'autoship_hide_show_toggler',
            'description' => __('Select to enable options for Next Occurrence.', 'autoship'),
            'desc_tip' => false,
            'value' => $relative_enabled,
            'custom_attributes' => array( 'data-target' => '.show_hide_autoship_next_occurrence_options' ),
          )
        );
        ?>

      </div><!-- Enable Autoship Relative Next Occurrence -->

      <div class="options_group options-form-group autoship-next-occurrence show_hide_autoship_next_occurrence_options" <?=$show_relative?>>

        <h4><?php echo __('Set Next Occurrence Date Relative to Checkout', 'autoship'); ?></h4>
        <p><?php echo __('When this product is scheduled for Autoship, set the Next Occurrence date for this product’s Scheduled Order to a specific number of days after checkout.', 'autoship'); ?></p>

        <div class="option-form-row auto-flex-row">
          <div class="option-form-group auto-flex-col">
            <?php
            // Enable Autoship Relative Next Occurrence ?>
            <select id="_autoship_relative_next_occurrence_type" class="option-form-control" name="_autoship_relative_next_occurrence_type">

              <?php

              // Get the options
              $options = autoship_valid_relative_next_occurrence_types();

              ?>

              <?php foreach ($options as $key => $value): ?>

              <option value="<?php echo $key;?>" <?php selected( $key, $next_occurrence_type );?> ><?php echo $value;?></option>

              <?php endforeach; ?>

            </select>
          </div>
          <div class="option-form-group auto-flex-col">
            <input type="number" class="option-form-control" id="_autoship_relative_next_occurrence" name="_autoship_relative_next_occurrence" placeholder="Enter a Value" value="<?php echo absint( $next_occurrence );?>">
          </div>
        </div>

      </div><!-- Enable Autoship Relative Next Occurrence -->

    </div><!-- /.autoship-sync-active-option-group -->

    <?php do_action( 'autoship_after_print_product_custom_fields', $_product ); ?>

  </div><!-- All Autoship Custom Product Fields -->

  <?php
}
add_action( 'woocommerce_product_options_autoship_product_data', 'autoship_print_product_custom_fields' );

/**
* Outputs the HTML for the Autoship custom fields for Variations
*
* @see woocommerce_product_after_variable_attributes hook.
*
* @param int     $loop
* @param array   $variation_data
* @param WP_Post $variation
*/
function autoship_print_variable_product_custom_fields( $loop, $variation_data, $variation ) {


  // Retrieve all the metadata values for this variation.
  $variation_data = autoship_get_variable_product_custom_field_values( $variation->ID );
  $wc_product = wc_get_product( $variation->ID );

  $active = 'yes' == autoship_sync_active_enabled( $variation->ID );

  ?>

  <div class="autoship-sync-active-option-group <?php echo $active ? '' : 'not-active';?>">

    <!-- Autoship Checkout & Re-Occurring Price options -->
    <h4><?php echo __( 'Autoship Options', 'autoship' ); ?></h4>

    <?php

    $disable = apply_filters( 'autoship_disable_schedule_order_options_default', $variation_data['_autoship_dissable_schedule_order_options'], $variation->ID );
    $show_section = empty( $disable ) || ( 'yes' !== $disable ) ?
    '' : 'style="display:none;"';

    $option     = '_autoship_dissable_schedule_order_options';
    $id         = $option . $loop;
    $name       = "{$option}[{$loop}]";
    $val        = "yes";
    $checked    = checked('yes', $disable, false );
    $label      = __( 'Disable Autoship Options for this variation', 'autoship' );
    $data_attr  = "data-target=\".autoship_product_options_group_{$loop}\"";

    ?>

    <input class="autoship_hide_show_toggler" type="checkbox" id="<?=$id?>" name="<?=$name?>" value="<?=$val?>" <?=$checked?> <?=$data_attr?> /> <?=$label?> <br></br>

    <div class="autoship_product_options_group_<?php echo $loop; ?>_wrapper">

      <?php do_action( 'autoship_before_print_variable_product_custom_price_fields', $loop, $variation_data, $variation  ); ?>

      <div class="variable_pricing autoship_product_options_group_<?php echo $loop; ?>" <?=$show_section?>>

        <p class="form-row form-row-full">

          <label for="autoship_variable_checkout_price_<?php echo $loop; ?>"><?php echo sprintf(__('Autoship Checkout Price (%s)', 'autoship'), get_woocommerce_currency_symbol()); ?></label>
          <input type="text" size="5" id="autoship_variable_checkout_price_<?php echo $loop; ?>" name="_autoship_checkout_price[<?php echo $loop; ?>]" value="<?php echo esc_attr($variation_data['_autoship_checkout_price']); ?>" class="wc_input_price" placeholder="<?php _e('(optional)', 'autoship'); ?>" />

        </p>
        <p class="form-row form-row-full">

          <label for="autoship_variable_recurring_price_<?php echo $loop; ?>"><?php echo sprintf(__('Autoship Recurring Price (%s)', 'autoship'), get_woocommerce_currency_symbol()); ?></label>
          <input type="text" size="5" id="autoship_variable_recurring_price_<?php echo $loop; ?>" name="_autoship_recurring_price[<?php echo $loop; ?>]" value="<?php echo esc_attr($variation_data['_autoship_recurring_price']); ?>" class="wc_input_price" placeholder="<?php _e('(optional)', 'autoship'); ?>" />

        </p>
      </div>

      <?php do_action( 'autoship_after_print_variable_product_custom_price_fields', $loop, $variation_data, $variation  ); ?>

      <!-- Autoship Scheduled Order options -->
      <h4><?php echo __( 'Autoship Scheduled Order Settings', 'autoship' ); ?></h4>
      <p>
        <b><?php echo __( sprintf( 'These Settings can be updated in <a target="_blank" href="%s">Autoship Cloud > Products</a>', admin_url( '/admin.php?page=products' ) ), 'autoship'); ?></b>
      </p>

      <?php

      $option = '_autoship_schedule_process_enabled';
      $id     = $option . $loop;
      $name   = "{$option}[{$loop}]";
      $val    = "yes";
      $checked= checked('yes', $variation_data[$option], false );
      $label  = __( 'Enabled to Add to Scheduled Orders', 'autoship' );

      ?>

      <input type="checkbox" readonly="readonly" disabled="disabled" id="<?=$id?>" name="<?=$name?>" value="<?=$val?>" <?=$checked?> /> <?=$label?> <br></br>

      <?php

      $option = '_autoship_schedule_process_enabled';
      $id     = $option . $loop;
      $name   = "{$option}[{$loop}]";
      $val    = "yes";
      $checked= checked('yes', $variation_data[$option], false );
      $url    = "https://support.autoship.cloud/article/441-product-availability-and-stock-status";
      $label  = __( sprintf( 'Enabled to Process on Scheduled Orders (<a target="_blank" href="%s">Read this</a> before disabling this setting)', $url ), 'autoship' );

      ?>

      <input type="checkbox" readonly="readonly" disabled="disabled" id="<?=$id?>" name="<?=$name?>" value="<?=$val?>" <?=$checked?> /> <?=$label?> <br></br>

      <?php do_action( 'autoship_after_print_variable_product_custom_order_settings_fields', $loop, $variation_data, $variation  ); ?>

      <div class="autoship-group-ids autoship_product_options_group_<?php echo $loop; ?>" <?=$show_section?>>

        <h4><?php echo __('Assigned Group IDs', 'autoship'); ?></h4>

        <p class="form-row form-row-full">

          <label for="autoship_assigned_group_ids_<?php echo $loop; ?>"><?php echo __('Autoship Group IDs', 'autoship'); ?></label>
          <input type="text" id="autoship_assigned_group_ids_<?php echo $loop; ?>" name="_autoship_group_ids[<?php echo $loop; ?>]" value="<?php echo esc_attr($variation_data['_autoship_group_ids']); ?>" placeholder="<?php _e('(optional)', 'autoship'); ?>" />

        </p>

      </div>

      <div class="autoship-data-override autoship_product_options_group_<?php echo $loop; ?>" <?=$show_section?>>

        <?php

        // Show or hide Product Data Overrides based on checkbox value.
        $data_override = autoship_override_product_data_enabled( $variation->ID );
        $show_prod_overrides = 'yes' !== $data_override ? 'style="display:none;"' : '';

        ?>

        <h4><?php echo __('Product Data Overrides', 'autoship'); ?></h4>
        <p><?php echo __( 'Select to override the default Product Data in QPilot.', 'autoship' );?></p>

        <?php

        $override = apply_filters( 'autoship_override_variable_frequency_options_default', $variation_data['_autoship_override_frequency_options'], $variation->ID );
        $show_freq_overrides = empty( $override ) || ( 'yes' !== $override ) ?
        'style="display:none;"' : '';

        $option = '_autoship_override_product_data';
        $id     = $option . '_' . $loop;
        $name   = "{$option}[{$loop}]";
        $val    = "yes";
        $checked= checked('yes', $data_override, false );
        $label  = __( 'Override defaults', 'autoship' );
        $data_attr  = "data-target=\".show_hide_product_data_group_variable_{$loop}\"";

        ?>

        <p class="form-row form-row-full">
          <input class="autoship_hide_show_toggler" type="checkbox" id="<?=$id?>" name="<?=$name?>" value="<?=$val?>" <?=$checked?> <?=$data_attr?> />
          <label for="<?=$id?>"><?=$label?></label>
        </p>

        <div class="product_data_options show_hide_product_data_group_variable_<?php echo $loop; ?>" <?=$show_prod_overrides?>>

          <?php

          $option = '_autoship_title_override';
          $id     = $option . '_' . $loop;
          $name   = "{$option}[{$loop}]";
          $val    = $variation_data[$option];
          $label  = __( 'Product Title', 'autoship' );
          $placeholder  = esc_attr( autoship_get_product_display_name( $variation->ID ) );
          $help   = wc_help_tip( __('Enter a custom Product Title for QPilot', 'autoship') );
          ?>

          <p class="form-row form-row-full helptip-left">
            <label for="<?=$id?>"><?=$label?></label><?=$help?>
            <input class="autoship_hide_show_toggler" type="text" id="<?=$id?>" size="200" name="<?=$name?>" value="<?=$val?>" placeholder="<?=$placeholder?>" />
          </p>

          <?php

          $option = '_autoship_weightunit_override';
          $id     = $option . '_' . $loop;
          $name   = "{$option}[{$loop}]";
          $val    = $variation_data[$option];
          $label  = __( 'Weight unit', 'autoship' );
          $default_val = apply_filters( 'autoship_get_mapped_product_weight_unit', get_option('woocommerce_weight_unit'), $wc_product );
          $placeholder = __( sprintf( "-- Select an Override ( default %s ) --", $default_val ), 'autoship' );
          $help   = wc_help_tip( __('Select a Unit of Measurement for Weight', 'autoship') );
          $selects= array(
            'Kilogram'  => 'kg',
            'Gram'      => 'g',
            'Pound'     => 'lbs',
            'Ounce'     => 'oz',
          );
          ?>

          <p class="form-row form-row-full helptip-left">

            <label for="<?=$id?>">
              <?php echo $label; ?>
            </label>
            <?php echo $help;?>
            <select id="<?=$id?>" name="<?=$name?>" >

              <option value=""><?php echo $placeholder; ?></option>

              <?php foreach ( $selects as $key => $value ) { ?>

                <option value="<?php echo $key;?>" <?php echo selected( $key, $val ); ?>><?php echo $value; ?></option>

              <?php } ?>

            </select>

          </p>

          <?php

          $option = '_autoship_lengthunit_override';
          $id     = $option . '_' . $loop;
          $name   = "{$option}[{$loop}]";
          $val    = $variation_data[$option];
          $label  = __( 'Dimensions Unit', 'autoship' );
          $default_val = apply_filters( 'autoship_get_mapped_product_length_unit', get_option('woocommerce_dimension_unit'), $wc_product );
          $placeholder = __( sprintf( "-- Select an Override ( default %s ) --", $default_val ), 'autoship' );
          $help   = wc_help_tip( __('Select a Unit of Measurement for Length', 'autoship') );
          $selects= array(
            'Meter'       => 'm',
            'Centimeter'  => 'cm',
            'Milimeter'   => 'mm',
            'Inch'        => 'in',
            'Foot'        => 'ft',
            'Yard'        => 'yd',
          );
          ?>

          <p class="form-row form-row-full helptip-left">

            <label for="<?=$id?>">
              <?php echo $label; ?>
            </label>
            <?php echo $help;?>
            <select id="<?=$id?>" name="<?=$name?>" >

              <option value=""><?php echo $placeholder; ?></option>

              <?php foreach ( $selects as $key => $value ) { ?>

                <option value="<?php echo $key;?>" <?php echo selected( $key, $val ); ?>><?php echo $value; ?></option>

              <?php } ?>

            </select>

          </p>

        </div><!-- .product_data_options -->

        </div>

      </div><!-- /.autoship-sync-active-option-group -->

      <div class="autoship_product_options_group_<?php echo $loop; ?>" <?=$show_section?>>

        <!-- Autoship frequency options -->
        <h4><?php echo __('More Autoship Options', 'autoship'); ?></h4>

        <?php

        $override = apply_filters( 'autoship_override_variable_frequency_options_default', $variation_data['_autoship_override_frequency_options'], $variation->ID );
        $show_freq_overrides = empty( $override ) || ( 'yes' !== $override ) ?
        'style="display:none;"' : '';

        $option = '_autoship_override_frequency_options';
        $id     = $option . '_' . $loop;
        $name   = "{$option}[{$loop}]";
        $val    = "yes";
        $checked= checked('yes', $override, false );
        $label  = __( 'Override frequency options', 'autoship' );
        $data_attr  = "data-target=\".show_hide_frequency_options_group_{$loop}\"";

        ?>

        <p class="form-row form-row-full">
          <input class="autoship_hide_show_toggler" type="checkbox" id="<?=$id?>" name="<?=$name?>" value="<?=$val?>" <?=$checked?> <?=$data_attr?> />
          <label for="<?=$id?>"><?=$label?></label>
        </p>

        <!-- Autoship Custom frequency options Group -->
        <div class="frequency_options show_hide_frequency_options_group_<?php echo $loop; ?>" <?=$show_freq_overrides?>>

        	<?php
          $autoship_frequency_options = autoship_get_frequency_options_count();
        	for ( $i = 0, $n = 1; $i < $autoship_frequency_options; $i++, $n++ ) {

        		$frequency_type = $variation_data["_autoship_frequency_type_{$i}"];
        		$frequency      = $variation_data["_autoship_frequency_{$i}"];
        		$display_name   = $variation_data["_autoship_frequency_display_name_{$i}"];

      		?>

      		<div class="show_hide_frequency_option<?php echo $loop; ?>">
      			<p class="form-row form-row-full">
      				<strong><?php echo sprintf( __( 'Frequency Option %d', 'autoship' ), $n ); ?></strong>
      			</p>
      			<p class="form-row form-row-full">
      				<label for="autoship_frequency_type_<?php echo $loop; ?>_<?php echo $i; ?>">
      					<?php echo __( "Frequency Type", 'autoship' ); ?>
      				</label>
      				<select id="autoship_frequency_type_<?php echo $loop; ?>_<?php echo $i; ?>"
      						name="_autoship_frequency_type_<?php echo $i; ?>[<?php echo $loop; ?>]">
      					<option value=""><?php echo __( '--Select a frequency type--', 'autoship' ); ?></option>
      					<option value="Days" <?php echo selected('Days', $frequency_type ); ?>><?php echo __( 'Days', 'autoship' ); ?></option>
      					<option value="Weeks" <?php echo selected('Weeks', $frequency_type ); ?>><?php echo __( 'Weeks', 'autoship' ); ?></option>
      					<option value="Months" <?php echo selected('Months', $frequency_type ); ?>><?php echo __( 'Months', 'autoship' ); ?></option>
      					<option value="DayOfTheWeek" <?php echo selected('DayOfTheWeek', $frequency_type ); ?>><?php echo __( 'Day of the week', 'autoship' ); ?></option>
      					<option value="DayOfTheMonth" <?php echo selected('DayOfTheMonth', $frequency_type ); ?>><?php echo __( 'Day of the month', 'autoship' ); ?></option>
      				</select>
      			</p>
      			<p class="form-row form-row-full">
      				<label for="autoship_frequency_<?php echo $loop; ?>_<?php echo $i; ?>">
      					<?php echo __( "Frequency", 'autoship' ); ?>
      				</label>
      				<input type="number"
  					   id="autoship_frequency_<?php echo $loop; ?>_<?php echo $i; ?>"
  					   name="_autoship_frequency_<?php echo $i; ?>[<?php echo $loop; ?>]"
  					   value="<?php echo esc_attr( $frequency ); ?>"
  					   placeholder="<?php _e( 'Enter a frequency number', 'autoship' ); ?>" />
      			</p>
      			<p class="form-row form-row-full">
      				<label for="autoship_frequency_display_name_<?php echo $loop; ?>_<?php echo $i; ?>">
      					<?php echo __( "Frequency Display Name", 'autoship' ); ?>
      				</label>
      				<input type="text"
  					   id="autoship_frequency_display_name_<?php echo $loop; ?>_<?php echo $i; ?>"
  					   name="_autoship_frequency_display_name_<?php echo $i; ?>[<?php echo $loop; ?>]"
  					   value="<?php echo esc_attr( $display_name ); ?>"
  					   placeholder="<?php _e( '(Optional) Enter a display name', 'autoship' ); ?>" />
      			</p>
      			<p>
              <hr />
            </p>
      		</div>

          <?php } ?>

        </div>

      </div>

      <?php do_action( 'autoship_after_print_variable_product_custom_fields_group', $loop, $variation_data, $variation ); ?>

    </div>

  </div>

  <?php do_action( 'autoship_after_print_variable_product_custom_fields', $loop, $variation_data, $variation ); ?>

  <?php


}
add_action( 'woocommerce_product_after_variable_attributes', 'autoship_print_variable_product_custom_fields', 10, 3 );

/**
* Adds the Autoship Add to Order link form to the Autoship tab on the edit product page.
*/
function autoship_add_to_order_link_builder_form ( $wc_product = NULL ) {

  // Grab the product and info.
  global $post;
  
  $product = wc_get_product($post->ID);
  $type = $product->is_type( 'variable' ) ? 'variation' : 'simple';

  autoship_print_scripts_data( array(
    'autoship_scheduled_orders_url' => autoship_get_scheduled_orders_url(),
    'autoship_cart_url'             => wc_get_cart_url(),
    'autoship_addtoschedule_help'   => __('To Create a <strong>Add to Schedule Link</strong> select the product, quantity, Autoship Schedule, and min and/or max cycles then click the Generate Link button.</span', 'autoship'),
    'autoship_addtocart_help'       => __('To Create a <strong>Add to Cart Link</strong> select the product, quantity, and Autoship Schedule then click the Generate Link button.</span', 'autoship'),
  ) );

  ?>

  <div class="autoship-sync-active-option-group">

    <div class="auto-flex-row">
      <div class="auto-flex-col">
        <a href="#" class="autoship-schedule-link-toggler" data-target=".autoship-schedule-link-builder"><?php echo __( 'Click to Show / Hide the Autoship Link Builder', 'autoship');?></a>
      </div>
    </diV>

    <div class="options_group autoship-schedule-link-builder hidden">
      <div class="autoship-schedule-link-builder-simple">

        <div class="autoship-schedule-link">

          <h4><?php echo __('Autoship Link Builder', 'autoship'); ?></h4>

          <p class="autoship-schedule-link-builder-description"><?php echo __('The Autoship Link Builder can be used to generate shareable links that allow your customers to add an Autoship product to their cart or to their next Autoship Scheduled Order with a single click. <span class="help-text">To Create an <strong>Add to Schedule Link</strong> select the product, quantity, Autoship Schedule, and min and/or max cycles then click the Generate Link button.</span>', 'autoship'); ?></p>

          <?php

          $available_variations = array();
          if ( $product->is_type( 'variable' ) ){

            $default = '';
            $title = __('Select A Product, Schedule, & Qty','autoship');
            foreach ( $product->get_available_variations() as $variation_data ) {
              $variation = wc_get_product($variation_data['variation_id']);
              $available_variations[$variation_data['variation_id']] = $variation->get_name();
            }

          } else {

            $default = 'selected';
            $title = __('Select Schedule & Qty','autoship');
            $available_variations[$product->get_id()] = $product->get_name();
          }

          ?>

          <h5><?php echo $title;?></h5>
          <div class="product <?php echo $type;?>-product">

            <div class="auto-flex-row">
              <div class="auto-flex-col auto-flex-left large-col product-select">
                <select class="autoship-link-builder-select" id="autoship-link-builder-product-select">

                  <option value=""><?php echo __('Select a variation','autoship');?></option>

                  <?php foreach ( $available_variations as $id => $name ): ?>

                    <option value="<?php echo $id; ?>" <?php echo $default; ?>><?php echo esc_html( $name ); ?></option>

                  <?php endforeach; ?>

                </select>

              </div>
              <div class="auto-flex-col auto-flex-left large-col autoship-schedule">
                <label for="autoship-link-max-cycle"><?php echo __('Schedule','autoship');?></label>
                <select class="autoship-link-frequency-select" id="autoship-link-frequency-select">
                  <?php foreach ( autoship_product_frequency_options( $product->get_id() ) as $option ): ?>
                    <option value="<?php echo esc_attr( json_encode( $option ) ); ?>">
                      <?php echo esc_html( $option['display_name'] ); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="auto-flex-col auto-flex-left small-col">
                <label for="autoship-link-qty"><?php echo __('Qty','autoship');?></label>
                <input type="number" class="input-text qty text" step="1" min="1" name="autoship-link-qty" value="1" title="Quantity" size="4" pattern="[0-9]*" inputmode="numeric">
              </div>
            </div>

            <div class="auto-flex-row ignore-schedule">
              <div class="auto-flex-col auto-flex-left">
                <input class="check-input" type="checkbox" value="" id="autoship-ignore-schedule" data-target=".autoship-schedule">
                <label class="check-label" for="autoship-ignore-schedule">
                  <?php echo __( 'Add to Next Scheduled Order (Regardless of Frequency)', 'autoship' ); echo wc_help_tip( __( 'Enable to add product to the next occurring Scheduled Order. Disable to limit the offer to only the next Scheduled Order matching a specific Frequency and Frequency Type', 'autoship' ) )?>
                </label>
              </div>
            </div>

          </div>

          <h5><?php echo __('Select Link Type','autoship');?></h5>
          <div class="auto-flex-row link-types">
            <div class="auto-flex-col auto-flex-left small-col">
              <input class="form-check-input" type="radio" name="autoship-link-type" id="addtoschedule" value="addtoschedule" checked="checked">
              <label class="form-check-label" for="addtoschedule"><?php echo __('Add to Schedule','autoship');?></label>
            </div>
            <div class="auto-flex-col auto-flex-left small-col">
              <input class="form-check-input" type="radio" name="autoship-link-type" id="addtocart" value="addtocart">
              <label class="form-check-label" for="addtocart"><?php echo __('Add to Cart','autoship');?></label>
            </div>
          </div>

          <div class="cycles">

            <div class="autoship-cycles">

              <h5><?php echo __('Select Cycles','autoship');?></h5>
              <div class="auto-flex-row">
                <div class="auto-flex-col auto-flex-left small-col">
                  <label for="autoship-link-min-cycle"><?php echo __('Min','autoship');?></label>
                  <input type="number" class="input-text qty text" step="1" min="0" name="autoship-link-min-cycle" value="0" title="Min Cycle" size="4" pattern="[0-9]*" inputmode="numeric">
                </div>
                <div class="auto-flex-col auto-flex-left small-col">
                  <label for="autoship-link-max-cycle"><?php echo __('Max','autoship');?></label>
                  <input type="number" class="input-text qty text" step="1" min="1" name="autoship-link-max-cycle" value="1" title="Max Cycle" size="4" pattern="[0-9]*" inputmode="numeric">
                </div>
              </div>

            </div>

            <div class="auto-flex-row">
              <div class="auto-flex-col auto-flex-left">
                <input class="check-input" type="checkbox" value="yes" name="autoship-ignore-cycles" id="autoship-ignore-cycles" checked="checked" data-target=".autoship-cycles">
                <label class="check-label" for="autoship-ignore-cycles">
                  <?php echo __( 'Add One Time Only','autoship' ); ?>
                </label>
              </div>
            </div>

          </div>

          <div class="auto-flex-row">
            <div class="auto-flex-col small-col">
              <button class="button button-primary button-large autoship-link-generate" data-product-id="<?php echo absint( $product->get_id() ); ?>" data-product-type="<?php echo $type;?>" data-link-action="add-to-scheduled-order"><?php echo __('Generate Link','autoship');?></button>
            </div>
            <div class="auto-flex-col">
              <input id="autoship-built-link" class="autoship-link-value" data-help-result="<?php echo __('Link Copied!', 'autoship');?>" type="text" placeholder="<?php echo __('Select the options and click Generate Link','autoship');?>" value="" readonly>
            </div>
            <div class="auto-flex-col extra-small-col">
              <button class="button button-secondary button-large autoship-link-copy" data-target="#autoship-built-link"><?php echo __('Copy','autoship');?></button>
            </div>
          </div>

          <div class="auto-flex-row">
            <div class="auto-flex-col"><p class="autoship-link-help-text"></p></div>
          </div>
        </div>

      </div>
    </div>

  </div><!-- /.autoship-sync-active-option-group -->
  <?php

}
add_action( 'woocommerce_product_options_autoship_product_data', 'autoship_add_to_order_link_builder_form', 99 );

/**
* Retrieves the Metabox template data.
* @param int $id The WC_Product id to retrieve.
* @return array $template_data The data for the metabox
*/
function autoship_product_metaboxes_template_data ( $id ){

  $template_data = array();
	$product       = wc_get_product( $id );
  $valid         = autoship_is_valid_sync_product( $id );
  $active        = 'yes' == autoship_sync_active_enabled( $product );

  // Allow devs to disable metabox notice if desired.
  if( apply_filters('autoship_display_edit_product_sync_metabox_notice', !$active , $product ) )
  return;

  // If Sync Enabled Then get Summary data.
  // Get Products Autoship Status and Details.
  $template_data['data'] = $active && $valid ? autoship_get_product_sync_summary ( $id ) : array();
  autoship_store_product_sync_summary_data( $id , $template_data['data'] );

  // Get any Error or Warning Data.
  $template_data['error'] = autoship_get_product_summary_error_details( $template_data['data'], $product );

  // Check for Non Active Status.
  if ( ( empty( $template_data['data'] ) || !$template_data['data'] ) && !$active){

    $template_data['title']     = __( 'Not Active', 'autoship' );
    $template_data['status']    = 'not-active';
    $template_data['template']  = 'product-summary-metabox-inactive';

  // Check for Sync Error for active and valid products
  } else if ( $active && $valid && ( !empty( $template_data['error']['type'] ) && ( 'error' == $template_data['error']['type'] ) ) ){

    $template_data['title']    = __( 'Error', 'autoship' );
    $template_data['status']   = 'error';
    $template_data['template'] = 'product-summary-metabox-error';

  // If active and no general sync error
  } else if ( !empty( $template_data['data'] ) && $active && $valid ){

    $template_data['title']    = __( 'Active', 'autoship' );
    $template_data['status']   = 'active';
    $template_data['template'] = 'product-summary-metabox';

  } else if ( $active && !$valid ){

    $template_data['title']    = __( 'Not Active', 'autoship' );
    $template_data['status']   = 'not-active';
    $template_data['template'] = 'product-summary-metabox-error';
    $template_data['data']     = new WP_Error( 'Product Invalid', __( 'Product not Synchronized: Unable to synchronize Product Data due to Invalid Product Type and/or Status.', "autoship" ) );

  } else {

    $template_data['title']    = __( 'Not Available', 'autoship' );
    $template_data['status']   = 'error';
    $template_data['template'] = 'product-summary-metabox-error';
    $template_data['data']     = new WP_Error( 'Product Summary Unavailable', __( 'While this product is currently Active, a problem was encountered while trying to retrieve this Product\'s Sync Information.  Please confirm your Autoship Connection is healthy and setup correctly.', "autoship" ) );

  }

  return $template_data;

}

/**
 * Add Autoship Product Meta box.
 * @param object $post WP_Post
 */
function autoship_product_metaboxes( $post ) {

  // Only include metabox for valid product
  if ( ! autoship_is_valid_sync_type_product( $post->ID ) )
  return;

  // Get the metabox data
  $data = autoship_product_metaboxes_template_data( $post->ID );

  if ( empty( $data ) )
  return;

  $metaboxes = apply_filters( 'autoship_product_metaboxes', array(
    array(
    'id'        => 'autoship-product-summary-' . $data['status'],
    'title'     => sprintf( __( 'Autoship Status: <span class="%s %s">%s</span>', 'autoship' ), $data['status'], $data['error']['type'], $data['title'] ),
    'callback'  => 'autoship_product_summary_metabox',
    'screen'    => 'product',
    'context'   => 'side',
    'priority'  => 'high',
    'args'      => array( 'autoship_summary' => $data['data'], 'template' => $data['template'], 'error' => $data['error'] )
    )
  ), $post, $data );

  foreach ( $metaboxes as $box )
  add_meta_box( $box['id'], $box['title'], $box['callback'], $box['screen'], $box['context'], $box['priority'], $box['args'] );

}
add_action("add_meta_boxes_product", "autoship_product_metaboxes", 10, 1 );

/**
* The Autoship Summary Metabox
* @param WC_Post $post
*/
function autoship_product_summary_metabox( $post, $params ) {

  if ( !$post->ID )
  return;

	$product = wc_get_product( $post->ID );

  autoship_print_scripts_data( array(
    'autoship_product_status' => $product ->get_status(),
    'autoship_product_type'   => $product ->get_type(),
    'autoship_error'          => !empty( $error ) && ('error' == $error['type'] ),
    'autoship_warning'        => !empty( $error ) && ('warning' == $error['type'] ),
    'autoship_product_error'  => !empty( $error ) && ('error' == $error['type'] ) ? $error: NULL,
    'autoship_product_warning'=> !empty( $error ) && ('warning' == $error['type'] ) ? $error: NULL,
    'autoship_summary_data'   => $params['args']['autoship_summary'],
  ));

  $notice = autoship_get_active_product_summary_general_notice( $params['args']['autoship_summary'] , $product );
  autoship_generate_modal( 'autoship_product_summary',
  apply_filters( 'autoship-active-product-modal-notice', sprintf( __( '<h3>Autoship Cloud Warning!</h3>%s', 'autoship') , $notice ) , $product ), 'warning-modal' );

  return autoship_render_template(
    apply_filters(
      'autoship_edit_product_display_template',
      'admin/' . $params['args']['template'],
      $product ),
    array(
      'autoship_summary'          => $params['args']['autoship_summary'],
      'error'                     => $params['args']['error'],
      'product'                   => $product
  ) );

}

/**
* The Autoship Summary Metabox Refresh Action
* Updates the product
* @param WC_Post $post
*/
function autoship_product_summary_metabox_action( $autoship_summary, $product ){
  ?><p><input id="autoship-update-sync" name="save" type="submit" class="button button-primary button-large" value="Update and Sync"></p><?php
}
add_action( 'autoship_after_product_summary_metabox_table', 'autoship_product_summary_metabox_action', 10, 2);
add_action( 'autoship_no_product_summary_metabox_table', 'autoship_product_summary_metabox_action', 10, 2);

/**
* The Autoship Summary Metabox Notice Adjustment
* @param array $table_data The current table data.
* @param WP_Error|array $autoship_summary The current metabox summary data.
* @param WC_Product $product The WC Product.
* @return array The filtered table data.
*/
function autoship_product_summary_metabox_table_data_rows( $table_data, $autoship_summary, $product ){

  if ( is_wp_error( $autoship_summary ) )
  return $table_data;

  if ( 'variable' == $product->get_type() ){
    if ( isset( $autoship_summary['ProductType'] ) && !empty( $autoship_summary['ProductType'] ) )
    $table_data[] = array( 'label' => __('Product Type', 'autoship') , 'value' => ucfirst( $autoship_summary['ProductType'] ) );
    if ( isset( $autoship_summary['TotalVariations'] ) )
    $table_data[] = array( 'label' => __('Total Variations:', 'autoship') , 'value' => $autoship_summary['TotalVariations'] );
    if ( isset( $autoship_summary['TotalInStock'] ) )
    $table_data[] = array( 'label' => __('Variations In Stock:', 'autoship') , 'value' => $autoship_summary['TotalInStock'] );
    if ( isset( $autoship_summary['TotalOutOfStock'] ) )
    $table_data[] = array( 'label' => __('Variations Out of Stock:', 'autoship') , 'value' => $autoship_summary['TotalOutOfStock'] );
    if ( isset( $autoship_summary['QuantityScheduled'] ) )
    $table_data[] = array( 'label' => __('Total Scheduled Orders:', 'autoship') , 'value' => $autoship_summary['QuantityScheduled'] );
  } else {
    if ( isset( $autoship_summary['ProductType'] ) && !empty( $autoship_summary['ProductType'] ) )
    $table_data[] = array( 'label' => __('Product Type', 'autoship') , 'value' => ucfirst( $autoship_summary['ProductType'] ) );
    if ( isset( $autoship_summary['StockLevel'] ) && !empty( $autoship_summary['StockLevel'] ) )
    $table_data[] = array( 'label' => __('Stock Status', 'autoship') , 'value' => $autoship_summary['StockLevel'] );
    if ( isset( $autoship_summary['QuantityScheduled'] ) )
    $table_data[] = array( 'label' => __('Total Scheduled Orders', 'autoship') , 'value' => $autoship_summary['QuantityScheduled'] );
  }

  return $table_data;

}
add_filter( 'autoship_product_summary_metabox_table_data', 'autoship_product_summary_metabox_table_data_rows', 10, 3);

/**
* The Autoship Summary Metabox Notice Adjustment
* @param string $notice The current table notice.
* @param string $status The current table status ( Active, Error, Not Active).
* @param array $autoship_summary The current metabox summary data.
* @param WC_Product $product The WC Product.
* @return string The filtered Notice.
*/
function autoship_product_summary_metabox_notice( $notice, $status, $autoship_summary, $product  ){

  $products_report_url = autoship_admin_products_page_url();
  $api_health_url = autoship_admin_settings_page_url();
  if ( ( 'active' == $status ) && !autoship_is_valid_sync_status_product( $product ) )
  return sprintf( __('The Product Status does not match between QPilot and WooCommerce. Please confirm your <a href="%s">API connection is healthy</a> and ensure there are no issues with this product in the <a href="%s">Autoship Cloud > Products</a> report.', 'autoship'), $api_health_url, $products_report_url );

  if ( ( 'active' == $status ) && !autoship_is_valid_sync_type_product( $product ) )
  return sprintf( __('The Product Type does not match between QPilot and WooCommerce. Please confirm your <a href="%s">API connection is healthy</a> and ensure there are no issues with this product in the <a href="%s">Autoship Cloud > Products</a> report.', 'autoship'), $api_health_url, $products_report_url );

  return $notice;

}
add_filter( 'autoship_product_summary_metabox_notice', 'autoship_product_summary_metabox_notice', 10, 4 );


// ==========================================================
// All Products Page Functions
// ==========================================================

/**
* Load Autoship Cloud Data on Edit All Products Screen.
* TODO: Currently hooked into the admin notices but could try to find
*       better hook for early data load.
*/
function autoship_load_all_products_summary_data_display_notice (){

  // Get the Current Screen object and if doesn't exist escape.
  $screen = get_current_screen();
  if ( !$screen )
  return;

  if ( 'edit-product' !== $screen->id || 'edit' !== $screen->parent_base )
  return;

  // Allow devs to disable admin notice if desired.
  if( apply_filters('autoship_display_all_products_sync_admin_notice', false ) )
  return;

  // Since we're on the All Products screen tap into the current query to
  // get the current product ids so we can call the api with these ids.
  global $wp_query;
  $posts = $wp_query->posts;
  $query_ids = wp_list_pluck( $posts, 'ID' );
  $filter_count = count ( $query_ids );

  // Query these products to see if any have the active flag set.
  $products = wc_get_products( array( 'include' => $query_ids, 'limit' => $filter_count ) );

  // If No Products Bail.
  if ( empty( $products ) )
  return;

  // Check if global flag is set and if so all are active else
  $gobal_active = autoship_global_sync_active_enabled();

  $ids = array();

  foreach ($products as $key => $product) {

    if ( $gobal_active ){

      $ids[] = $product->get_id();
      // Check if we need to get all variations if this is a variable product.
      if ( 'variable' == $product->get_type() && $product->has_child() )
      $ids = array_merge( $ids, $product->get_children() );

    } else {

      // Since global isn't active get check product level
      if ( 'yes' === autoship_sync_active_enabled( $product ) ){
        $ids[] = $product->get_id();
        // Check if we need to get all variations if this is a variable product.
        if ( 'variable' == $product->get_type() && $product->has_child() )
        $ids = array_merge( $ids, $product->get_children() );
      }

    }

  }

  $summary_data = !empty( $ids ) ? autoship_get_all_products_sync_summary ( $ids, count( $ids ) ) : array();
  autoship_store_all_products_sync_summary_data( $summary_data );

  // Check for a general invalid flag
  $invalid_exists = autoship_check_site_settings_for_invalid_products();

  if ( !$invalid_exists )
  return;

  $products_report_url  = autoship_admin_products_page_url();

  // For now we're only displaying notice for errors
  $title  = __( 'Invalid Product(s)', 'autoship' );
  $status = 'error';
  $notice[] = apply_filters( 'autoship_all_products_admin_summary_metabox_notice', sprintf( __("<p>1 or more products activated for Autoship Cloud have become invalid.  Please visit <a href=\"%s\"><strong>Autoship Cloud Products</strong></a> to resolve this.</p>" ), $products_report_url ), $summary_data, $products );


  // Disabled this functionality so users don't see the details for now.
  if ( false ){

    $notices = array();
    if ( !empty( $summary_data ) && !is_wp_error( $summary_data ) ) {

      // Check if there are any invalids
      if ( $summary_data['totals']['TotalActiveInvalids'] ){

        $title  = sprintf( __( '%d Invalid Product(s)', 'autoship' ), $summary_data['totals']['TotalActiveInvalids'] );
        $status = 'error';

      // All Active but for some reason summary data isn't loaded.
      } else if ( $summary_data['totals']['TotalAutoshipActive'] ){

        $title  = sprintf( __( '%d Active Product(s)', 'autoship' ), $summary_data['totals']['TotalAutoshipActive'] );
        $status = 'active';

      // All Not Active
      } else if ( !$summary_data['totals']['TotalAutoshipActive'] ){

        $title  = __( 'No Active Product(s)', 'autoship' );
        $status = 'not-active';

      }

      $products_report_url = autoship_admin_products_page_url();

      // Add any error notices
      if ( $summary_data['totals']['TotalAutoshipActive'] && $summary_data['totals']['TotalActiveInvalids'] ){

        $notice[] = apply_filters( 'autoship_all_products_admin_summary_metabox_notice', sprintf( __("<p>A total of <strong>%d</strong> of the filtered Products and associated Variations are Active. <strong>However, %d of the Products or associated Variations filtered are currently Invalid and not synchronized correctly with QPilot.</strong>  Invalid products may not process correctly with Scheduled Orders.  For additional details please please edit the WooCommerce Product or view the <a href=\"%s\">Autoship Cloud > Products</a> report.</p>" ), $summary_data['totals']['TotalAutoshipActive'], $summary_data['totals']['TotalActiveInvalids'], $products_report_url ), $summary_data, $products );

      } else if ( $summary_data['totals']['TotalAutoshipActive'] ){

        $notice[] = apply_filters( 'autoship_all_products_admin_summary_metabox_notice', sprintf( __( '<p>There are currently <strong>%d Products</strong> actively synchronized with QPilot. Changes made in WooCommerce to these Products and/or any associated Variations will be synchronized with QPilot.</p>', "autoship" ), $summary_data['totals']['TotalAutoshipActive']), $summary_data, $products );

      } else if ( $summary_data['totals']['TotalActiveInvalids'] ){

        $notice[] = apply_filters( 'autoship_all_products_admin_summary_metabox_notice', sprintf( __("<p><strong>All %d of the Products and associated Variations filtered below that are Activated are currently not synchronized correctly with QPilot and may be Invalid.</strong>  Invalid products may not process correctly with Scheduled Orders.  For additional details please please edit the WooCommerce Product or view the <a href=\"%s\">Autoship Cloud > Products</a> report.</p>" ), $summary_data['totals']['TotalActiveInvalids'], $products_report_url ), $summary_data, $products );

      } else if ( !$summary_data['totals']['TotalAutoshipActive'] ){

        $notice[] = apply_filters( 'autoship_all_products_admin_summary_metabox_notice', sprintf( __( '<p>There are currently <strong>No Products</strong> actively synchronized with QPilot. Only changes made in WooCommerce to Active Products and/or any associated Variations will be synchronized with QPilot.</p>', "autoship" ), $summary_data['totals']['TotalAutoshipActive']), $summary_data, $products );

      }

      if ( $summary_data['totals']['QuantityScheduled'] ){

        $total_string = array();
        if ( $summary_data['totals']['TotalQuantityScheduledActive'] )
        $total_string[] = $summary_data['totals']['TotalQuantityScheduledActive'] . ' Active';

        if ( $summary_data['totals']['TotalQuantityScheduledPaused'] )
        $total_string[] = $summary_data['totals']['TotalQuantityScheduledPaused'] . ' Paused';

        if ( $summary_data['totals']['TotalQuantityFailed'] )
        $total_string[] = $summary_data['totals']['TotalQuantityFailed'] . ' Failed';

        if ( $summary_data['totals']['TotalQuantityProcessing'] )
        $total_string[] = $summary_data['totals']['TotalQuantityProcessing'] . ' Processing';

        $notice[] = apply_filters( 'autoship_all_products_admin_summary_metabox_subnotice', sprintf(
        __("<hr/><p>There are a total of <strong>%d Scheduled Orders ( %s )</strong> containing the Active Products and associated Variations filtered below. <br/><strong>Important!</strong> Changes made to product data that is already synchronized (i.e. changing Product Type, Published Status, Moving to Trash, Deleting Products, etc. ) may invalidate those products in QPilot and prevent them from processing with Scheduled Orders' </p>", 'autpship' ),
        $summary_data['totals']['QuantityScheduled'],
        implode(', ', $total_string ) ), $summary_data, $products );
      }

    } else {

      // Check for Total Sync Error
      if ( is_wp_error( $summary_data ) ){

        $title  = __( 'Error', 'autoship' );
        $status = 'error';
        $notice[] = apply_filters( 'autoship_all_products_admin_summary_metabox_notice', sprintf( "<p>%s</p>", $summary_data->get_error_message() ), $summary_data, $products );

      // No Active Filtered Products.
      } else if ( empty( $ids ) ){

        $title  = __( 'No Active Product(s)', 'autoship' );
        $status = 'not-active';

        $notice[] = apply_filters( 'autoship_all_products_admin_summary_metabox_notice', sprintf( __( '<p>There are currently <strong>No Products</strong> actively synchronized with QPilot. Only changes made in WooCommerce to Active Products and/or any associated Variations will be synchronized with QPilot.</p>', "autoship" ), 0 ), $summary_data, $products );

      // Check for missing data & possible QPilot outage
      } else if ( empty( $summary_data ) ){
        $api_health_url       = autoship_admin_settings_page_url();

        $title  = __( 'Not Available', 'autoship' );
        $status = 'error';
        $notice[] = apply_filters( 'autoship_all_products_admin_summary_metabox_notice', sprintf(  __( "<p>A problem was encountered while trying to retrieve Product Sync Information from QPilot. Please confirm your <a href=\"%s\">API connection is healthy</a> and setup correctly.", "autoship" ), $api_health_url ), $summary_data, $products );

      }

    }

  }

  ?>
  <div class="autoship-admin-notice notice autoship-<?php echo $status;?>-notice is-dismissible">
    <h2><?php echo sprintf( __( 'Autoship Status: <span class="%s">%s</span>', 'autoship' ), $status, $title ); ?></h2>
    <?php echo implode('', $notice ); ?>
    <?php do_action( 'autoship_all_products_admin_summary_metabox_notice', $status, $summary_data, $products ); ?>
  </div>
  <?php

}
add_action( 'admin_notices', 'autoship_load_all_products_summary_data_display_notice', 99 );


/**
 * Retrieves the list of Autoship All Products Bulk Actions
 * @return array The Autoship Bulk Actions.
 */
function autoship_all_products_bulk_actions() {

  $options = autoship_global_sync_active_enabled() ? array() : array(
  'autoship_activate_sync' => __( 'Activate Autoship Sync', 'autoship' ),
  'autoship_deactivate_sync' => __( 'Deactivate Autoship Sync', 'autoship' ) );

  return apply_filters( 'autoship_all_products_bulk_actions', $options );

}

/**
 * Add Activate & Deactivate Sync Options to All Products Bulk Actions
 * @param array $bulk_actions  The current bulk actions
 * @return array The filtered Bulk Actions.
 */
function autoship_all_products_add_bulk_actions( $bulk_actions ) {
  // Get our actions and add to existing.
  $actions = autoship_all_products_bulk_actions();
	return $bulk_actions + $actions;
}
add_filter( 'bulk_actions-edit-product', 'autoship_all_products_add_bulk_actions', 10, 1 );

/**
 * Handles the Autoship All Products Bulk Actions.
 * @param string $redirect_url The url to redirect user to after done.
 * @param string $action The current action being performed.
 * @param array $post_ids The selected post ids being performed against.
 */
function autoship_all_products_bulk_actions_handler( $redirect_url, $action, $post_ids ) {

  $autosbip_bulk_actions = autoship_all_products_bulk_actions();

	if ( !isset( $autosbip_bulk_actions[$action] ) )
	return $redirect_url;

  $function_handler = "{$action}_bulk_action_handler";
  if ( !function_exists( $function_handler ) )
	return $redirect_url;

  return $function_handler ( $redirect_url, $action, $post_ids );

}
add_filter( 'handle_bulk_actions-edit-product', 'autoship_all_products_bulk_actions_handler', 10, 3 );

/**
 * Handles the Autoship All Products Activate Sync Bulk Action.
 * @param string $redirect_url The url to redirect user to after done.
 * @param string $action The current action being performed.
 * @param array $post_ids The selected post ids being performed against.
 *
 * @return string The redirect url.
 */
function autoship_activate_sync_bulk_action_handler( $redirect_url, $action, $post_ids ){

  // Go through the ids and activate the products.
  $updated = $not_updated = array();
	foreach ( $post_ids as $post_id ) {

    if ( !autoship_is_valid_sync_post_type( $post_id ) ) {
      $not_updated[] = $post_id;
      continue;
    }

    // Enable the option
    $result = autoship_set_product_sync_active_enabled ( $post_id ,'yes' );

    // Update the Simple, Variable or Variation in QPilot.
    $result = autoship_push_product ( $post_id, array( 'addToScheduledOrder' => true, 'processScheduledOrder' => true ) );

    // Set the avail flags
    if ( !is_wp_error( $result ) ){

      autoship_set_product_add_to_scheduled_order ( $post_id, 'yes' );
      autoship_set_product_process_on_scheduled_order ( $post_id, 'yes' );

    }

    $updated[] = $post_id;

  }

  $notice = sprintf( __('<h3>Autoship Cloud Bulk Action</h3><p><strong>%d Products Activated</strong> to synchronize with QPilot.</p>', 'autoship'), count( $updated ) );

  if ( !empty( $not_updated ) )
  $notice .= sprintf( __('<hr/><p>%d Products were not updated since they were not valid QPilot product types.</p>', 'autoship'), count( $not_updated ) );

  autoship_add_message( $notice, 'autoship-general-notice' , 'autoship_all_products_bulk_action_messages' );

  do_action( 'autoship_after_all_products_autoship_activate_sync_bulk_action', $post_ids, $action, $redirect_url );

	return $redirect_url;

}

/**
 * Handles the Autoship All Products Deactivate Sync Bulk Action
 * @param string $redirect_url The url to redirect user to after done.
 * @param string $action The current action being performed.
 * @param array $post_ids The selected post ids being performed against.
 *
 * @return string The redirect url.
 */
function autoship_deactivate_sync_bulk_action_handler( $redirect_url, $action, $post_ids ){

  // Go through the ids and deactivate the products.
  $updated = $not_updated = array();
	foreach ( $post_ids as $post_id ) {

    if ( !autoship_is_valid_sync_post_type( $post_id ) ) {
      $not_updated[] = $post_id;
      continue;
    }

    // Enable the option
    $result = autoship_set_product_sync_active_enabled ( $post_id ,'no' );

    // Update the Simple, Variable or Variation in QPilot.
    $result = autoship_push_product ( $post_id );

    $updated[] = $post_id;

  }

  $notice = sprintf( __('<h3>Autoship Cloud Bulk Action</h3><p><strong>%d Products Deactivated</strong> from synchronizing with QPilot.</p>', 'autoship'), count( $updated ) );

  if ( !empty( $not_updated ) )
  $notice .= sprintf( __('<hr/><p>%d Products were not updated since they were not valid QPilot product types.</p>', 'autoship'), count( $not_updated ) );

  autoship_add_message( $notice, 'autoship-general-notice' , 'autoship_all_products_bulk_action_messages' );

  do_action( 'autoship_after_all_products_autoship_deactivate_sync_bulk_action', $post_ids, $action, $redirect_url );

	return $redirect_url;

}

/**
 * Display Any notices related to the bulk actions.
 */
function autoship_all_products_autoship_bulk_action_admin_notice() {

  $notices = autoship_get_messages( 'autoship_all_products_bulk_action_messages', true );
  if ( empty( $notices ) )
  return;

  foreach ($notices as $notice) {

  ?>
  <div class="autoship-admin-notice notice <?php echo $notice['type'];?> is-dismissible">
    <?php echo $notice['message']; ?>
  </div>
  <?php

  }

}
add_action( 'admin_notices', 'autoship_all_products_autoship_bulk_action_admin_notice' );

/**
* Adds Custom Autoship Columns to the WooCommerce Orders Dash
*
* @param array $columns An array of column names
* @return array $new_columns The filtered columns
*/
function autoship_products_dashboard_columns(){

  /**
  * The Default Autoship Custom Dashboard Columns
  * Column ID => array {
  *   'location' Column ID after which it should be inserted.
  *   'label'    Column Label
  *   'meta_key' The meta key name to sort by.
  *   'orderby'  How the sort should work, value, number value etc.
  *   'callback' A valid callback function to populate the column.
  *              Takes the Post ID ( int ) as Parameter.
  * }
  */
  $columns = array(
    'product_autoship_sync_active' => array(
      'location' => 'name',
      'label'    => __( '', 'autoship' ),
      'meta_key' => '_autoship_sync_active_enabled',
      'orderby'  => 'meta_value', // or meta_value_num
      'callback' => 'autoship_populate_product_sync_dashboard_icon',
    ),
  );

  return apply_filters( 'autoship_products_dashboard_columns', $columns );

}

/**
* Callback for New Product Sync Status Dashboard Column
* @param int $product_id The WC Order ID.
*/
function autoship_populate_product_sync_dashboard_icon( $product_id ){

  $product = wc_get_product( $product_id );
  $active = 'yes' === autoship_sync_active_enabled( $product );

  // If this is not a valid product type then display nothing.
  if ( !autoship_is_valid_sync_type_product( $product ) )
  return;

  // Tap into the stored API Data
  $summary_data = autoship_get_stored_all_products_sync_summary_data ();

  if ( is_wp_error( $summary_data ) ){

    $status = 'error';
    $status_code = $summary_data->get_error_code();
    $tooltip[] = wc_sanitize_tooltip( $summary_data->get_error_message( $status_code ) );

    $tooltip = apply_filters( 'autoship_populate_products_dashboard_icon_tooltip_components', $tooltip, $product );

    printf( '<mark class="product-sync-status-icon %s %s tips" data-tip="%s"><span></span></mark>', $status , $status_code, apply_filters( 'autoship_populate_products_dashboard_icon_tooltip', wp_kses_post( implode( "\n", $tooltip ) ), $tooltip, $product ) );

    return;

  }

  // Gather ids to check.
  $ids = ( 'variable' === $product->get_type() ) ?
  array_merge( array( $product_id ), $product->get_children() ) : array( $product_id );

  // Since it's active we'll check if it's a valid status and/or type.
  $status = $active ? 'active' : 'not-active';
  $status_code = '';

  $tooltip = array();

  // Check for empty summary data.
  if ( !empty( $summary_data) ){
    // Check if this product is invalid.
    foreach ( $ids as $id ) {

      if ( in_array( $id, $summary_data['totals']['TotalActiveInvalidsIds'] ) && $active ){

        $status = 'error';
        $status_code = $summary_data['totals']['AllSyncError'][$id]->get_error_code();
      	$tooltip[] = wc_sanitize_tooltip( $summary_data['totals']['AllSyncError'][$id]->get_error_message($status_code) );
        break;
      }

    }
  }

  // If a notice is being returned there's an issue.
  if ( 'error' != $status ){
    if ( !autoship_is_valid_sync_status_product( $product ) || !autoship_is_valid_sync_type_product( $product ) ){
      $status = 'not-active';
    	$tooltip[] = wc_sanitize_tooltip( _( 'Autoship Sync Not Active due to Invalid Product Type or Status' ) );
    } else if ( $active ){
    	$tooltip[] = wc_sanitize_tooltip( __( 'Autoship Sync Active','autoship' ) );
    } else {
    	$tooltip[] = wc_sanitize_tooltip( __( 'Autoship Sync Not Active','autoship' ) );
    }
  }

  $tooltip = apply_filters( 'autoship_populate_products_dashboard_icon_tooltip_components', $tooltip, $product );

  printf( '<mark class="product-sync-status-icon %s %s tips" data-tip="%s"><span></span></mark>', $status , $status_code, apply_filters( 'autoship_populate_products_dashboard_icon_tooltip', wp_kses_post( implode( "\n", $tooltip ) ), $tooltip, $product ) );

}

/**
* Allow users to sort by the Custom Columns
* @param WP_Query $query The Current Query value.
* @return WP_Query The adjusted filter.
*/
function autoship_products_dashboard_custom_column_sort( $query ){

    // if it is not admin area, exit the filter immediately
  	if ( ! is_admin() )
    return;

    $columns = autoship_products_dashboard_columns();

  	if( empty( $_GET['orderby'] ) || empty( $_GET['order'] ) ) return;

  	if( isset( $columns[$_GET['orderby']] ) ) {
  		$query->set('meta_key', $columns[$_GET['orderby']]['meta_key'] );
  		$query->set('orderby', $columns[$_GET['orderby']]['orderby'] );
  		$query->set('order', $_GET['order'] );
  	}

  	return $query;
}
add_action( 'pre_get_posts', 'autoship_products_dashboard_custom_column_sort' );

/**
* Makes the custom column headers sortable.
* @param array $columns An array of sortable columns
* @return array The updated sortable columns array.
*/
function autoship_products_dashboard_custom_column_sortable ( $columns ) {

  $new_columns = autoship_products_dashboard_columns();

  foreach ( $new_columns as $key => $value) {
    $columns[$key] = $key;
  }

  return $columns;

}
add_filter( 'manage_edit-product_sortable_columns', 'autoship_products_dashboard_custom_column_sortable', 10, 1 );

/**
* Makes the custom columns searchable.
* @param array $meta_keys An array of sortable columns
* @return array The Searchable meta keys.
*/
function autoship_products_dashboard_custom_column_searchable ( $meta_keys ){

    $columns = autoship_products_dashboard_columns();

    foreach ($columns as $key => $value) {
      $meta_keys[] = $value['meta_key'];
    }

    return $meta_keys;
}
add_filter( 'woocommerce_product_search_fields', 'autoship_products_dashboard_custom_column_searchable', 10, 1 );

/**
* Adds Custom Autoship Columns to the WooCommerce Orders Dash
*
* @param array $columns An array of current order dash column names
* @return array $new_columns The filtered columns
*/
function autoship_products_dashboard_column_headers( $columns ) {

    $new_columns = autoship_products_dashboard_columns();

    foreach ($new_columns as $key => $values ) {

      $columns = autoship_array_insert_after( $values['location'], $columns, $key, $values['label'] );

    }
    return $columns;

}
add_filter( 'manage_edit-product_columns', 'autoship_products_dashboard_column_headers', 20, 1 );


/**
* Populates the Custom Autoship Columns in the WooCommerce All Products Dashboard
*
* @param string $column The current column being displayed.
*/
function autoship_products_populate_dashboard_columns( $column ) {
    global $post;

    $autoship_columns = autoship_products_dashboard_columns();

    if ( isset( $autoship_columns[$column] ) ){

      $callback = $autoship_columns[$column]['callback'];
      echo function_exists( $callback ) ? $callback( $post->ID ) : '';

    }
}
add_action( 'manage_product_posts_custom_column', 'autoship_products_populate_dashboard_columns' );


// ==========================================================
// Product Shop Page Functions
// ==========================================================

/**
* Outputs the HTML element for the Discount Product Price Element.
* @param WC_Product $product The WooCommerce Product.
*/
function autoship_product_discount_price_placeholder_html( $product ){

  echo apply_filters( 'autoship_product_discount_price_placeholder_html',
  '<div class="autoship-price-display"></div>',
  $product );

}
add_action( 'autoship_after_schedule_options_template', 'autoship_product_discount_price_placeholder_html', 10, 1 );
add_action( 'autoship_after_schedule_options_variable_template', 'autoship_product_discount_price_placeholder_html', 10, 1 );

/**
* Retrieves the HTML for the Autoship and Save Variation extended text
*
* @param int    $product_id.      The wc product or variation id.
*/
function autoship_checkout_recurring_variable_discount_string( $product_id ){

  $product = wc_get_product( $product_id );

  $strings['autoship_save_string'] = sprintf( '<span class="autoship-save">%s</span>',
  apply_filters( 'autoship_radio_label', autoship_translate_text(  'Autoship and Save', true ),
  'yes', true, $product ) );
  $strings['autoship_string'] = sprintf( '<span class="autoship">%s</span>',
  apply_filters( 'autoship_radio_label', autoship_translate_text( 'Autoship', true ),
  'yes', false, $product ) );

  ob_start();?>

  <span class="autoship-discount-label">
      <span class="autoship-save"><?php echo $strings['autoship_save_string']; ?></span>
      <span class="autoship-custom-percent-discount-str"></span>:
      <span class="autoship-checkout-price"></span>
  </span>
  <span class="autoship-no-discount-label">
      <span class="autoship"><?php echo $strings['autoship_string']; ?></span>
  </span>

  <?php
  return apply_filters( 'autoship_checkout_recurring_variable_discount_string_html', ob_get_clean(), $product, $strings );

}

/**
* Retrieves the HTML for the Autoship and Save extended text
*
* @param int    $product_id.      The wc product or variation id.
* @param bool   $variable.        True if this is a variable product else true.
* @param array  $prices.          Optional. The base prices to use in the strings.
*/
function autoship_checkout_recurring_discount_string( $product_id, $variable = false, $prices = array() ){

  $product = wc_get_product( $product_id );

  // Get the WC and Autoshp Prices.
  if ( empty( $prices ) )
  $prices = autoship_get_product_prices( $product_id );

  extract( $prices );

  $strings['autoship_string'] = $strings['price_string'] = '';

  if ( !$variable ){

    $strings['autoship_string']    = ( $autoship_percent_discount  || $autoship_percent_recurring_discount ) ?
    sprintf( '<span class="autoship-save">%s</span> ',
    apply_filters( 'autoship_radio_label', autoship_translate_text( 'Autoship and Save', true ),
    'yes', true, $product ) ) :
    sprintf( '<span class="autoship">%s</span>',
    apply_filters( 'autoship_radio_label', autoship_translate_text( 'Autoship', true ),
    'yes', false, $product ) );

    $strings['price_string']    = $autoship_percent_discount ? sprintf(
    '%s<span class="autoship-checkout-price">%s</span>',
    apply_filters( 'autoship_price_string', __( ': ', 'autoship' ), $product ),
    wc_price( $autoship_checkout_display_price ) ) : '';

  }

  $strings['checkout_percentage_string']   = sprintf(
    ' <span class="autoship-checkout-percent-discount">%s%%</span>',
    $autoship_percent_discount );

  $strings['checkout_string']     = sprintf(
    ' <span class="autoship-at-checkout">%s</span>',
    apply_filters( 'autoship_checkout_string', __( 'at checkout', 'autoship' ), $product ) );

  $strings['concat_string']       =
    apply_filters( 'autoship_concat_string', ' ' . __( 'and', 'autoship' ), $product );

  $strings['recurring_percentage_string']   = sprintf(
    ' <span class="autoship-recurring-percent-discount">%s%%</span>',
    $autoship_percent_recurring_discount );

  $strings['recurring_string']    = sprintf(
    ' <span class="autoship-recurring-percent-discount">%s</span>',
    apply_filters( 'autoship_recurring_string', __( 'on future orders', 'autoship' ), $product ) );

  // Allow individual components to be filtered.
  $strings = apply_filters('autoship_discount_strings_components', $strings, $prices, $product );

  $output  = $strings['autoship_string'];

  // If there's a discount at checkout & recurring
  if ( ( $autoship_percent_discount == $autoship_percent_recurring_discount ) && $autoship_percent_discount ) {

    $output  =
      $strings['autoship_string']
    . $strings['checkout_percentage_string']
    . $strings['price_string'];

    $output = apply_filters(
      'autoship_discount_checkout_and_recurring_same',
      $output,
      $strings,
      $prices,
      $product
    );

  } elseif ( $autoship_percent_discount && $autoship_percent_recurring_discount ){

    $output  =
      $strings['autoship_string']
    . $strings['checkout_percentage_string']
    . $strings['checkout_string']
    . $strings['concat_string']
    . $strings['recurring_percentage_string']
    . $strings['recurring_string']
    . $strings['price_string'];

    $output = apply_filters(
      'autoship_discount_checkout_and_recurring',
      $output,
      $strings,
      $prices,
      $product
    );

  } elseif ( $autoship_percent_discount ){

    $output  =
      $strings['autoship_string']
    . $strings['checkout_percentage_string']
    . $strings['checkout_string']
    . $strings['price_string'];

    $output = apply_filters(
      'autoship_discount_checkout',
      $output,
      $strings,
      $prices,
      $product
    );

  } elseif ( $autoship_percent_recurring_discount ){

    $output  =
      $strings['autoship_string']
    . $strings['recurring_percentage_string']
    . $strings['recurring_string']
    . $strings['price_string'];

    $output = apply_filters(
      'autoship_discount_recurring',
      $output,
      $strings,
      $prices,
      $product
    );

  } else {

    $output = apply_filters(
      'autoship_no_discount',
      $output,
      $strings,
      $prices,
      $product
    );

  }

  return apply_filters(
  'autoship_checkout_recurring_discount_string',
  $output,
  $strings,
  $prices,
  $product
  );

}
