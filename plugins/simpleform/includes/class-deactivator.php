<?php

/**
 * The class instantiated during the plugin's deactivation.
 *
 * @since      1.0
 */

class SimpleForm_Deactivator {

	/**
	 * Run during plugin deactivation.
	 *
	 * @since    1.6.1
	 */
	public static function deactivate() {
		
      // Edit pre-built pages status for contact form and thank you message
      $settings = get_option( 'sform_settings' );
	  $form_page_ID = ! empty( $settings['form_pageid'] ) ? esc_attr($settings['form_pageid']) : '';  
	  $confirmation_page_ID = ! empty( $settings['confirmation_pageid'] ) ? esc_attr($settings['confirmation_pageid']) : '';	  
	        
      if ( ! empty($form_page_ID) && get_post_status($form_page_ID) ) { 
	    wp_update_post(array( 'ID' => $form_page_ID, 'post_status' => 'trash' ));
	  }
      if ( ! empty($confirmation_page_ID) && get_post_status($confirmation_page_ID) ) { 
	    wp_update_post(array( 'ID' => $confirmation_page_ID, 'post_status' => 'trash' ));
	  }

	}

}