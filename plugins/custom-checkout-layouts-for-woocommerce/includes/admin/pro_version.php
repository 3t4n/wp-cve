<?php 
$prefix = 'cclw_';
$cclw_panel = new_cmb2_box( array(
        'id'            => $prefix .'pro_version',
        'title'         => __( 'Replace Text', 'cclw' ),
        'object_types'  => array( 'options-page', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
		'option_key'      => 'cclw_pro_version',
		'parent_slug'     => 'admin.php?page=custom_checkout_settings',
		
      
    ) );
		$image =  plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'asserts/images/cclw_pro_features.png';
		$html = '<div class ="cclw_pro_banner">
		        <div class="cclw_pro_link"><a target="_blank" href="https://blueplugins.com/woocommerce-one-page-checkout-and-layouts-pro/">Try Pro Version</a></div>
		       	<img src="'.$image.'">
				 <div class="cclw_pro_link"><a target="_blank" href="https://blueplugins.com/woocommerce-one-page-checkout-and-layouts-pro/">Try Pro Version</a></div>
				</div>';
		
		
		$cclw_panel->add_field( array(
			'desc' => $html,
			'type' => 'title',
			'id'   => 'cclw_pro_link',
		) );

				