<?php
/**
 * Process otw cm actions
 *
 */
if( otw_post( 'otw_qtsw_action', false ) ){
	
	require_once( ABSPATH . WPINC . '/pluggable.php' );
	
	switch( otw_post( 'otw_qtsw_action', '' ) ){
		
		case 'otw_qtsw_settings_action':
				
				if( otw_post( 'otw_cm_promotions', false ) && !empty( otw_post( 'otw_cm_promotions', '' ) ) ){
					
					global $otw_qtsw_factory_object, $otw_qtsw_plugin_id;
					
					update_option( $otw_qtsw_plugin_id.'_dnms', otw_post( 'otw_cm_promotions', '' ) );
					
					if( is_object( $otw_qtsw_factory_object ) ){
						$otw_qtsw_factory_object->retrive_plungins_data( true );
					}
				}
				
				wp_redirect( admin_url( 'admin.php?page=otw-qtsw-settings&message=1' ) );
			break;
	}
}
?>