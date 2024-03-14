<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
* Trait For Socail Settings
*/
trait Hmcab_Social_Settings {

    protected function set_social_settings( $networks, $post ) {

        $hmcabwSocialSettingsInfo = array();

        foreach ( $networks as $network ) {

            $hmcabwSocialSettingsInfo['hmcabw_' . $network . '_enable'] = ( isset( $post['hmcabw_' . $network . '_enable'] ) && filter_var( $post['hmcabw_' . $network . '_enable'], FILTER_SANITIZE_NUMBER_INT ) ) ? $post['hmcabw_' . $network . '_enable'] : '';
            $hmcabwSocialSettingsInfo['hmcabw_' . $network . '_link']   = isset( $post['hmcabw_' . $network . '_link'] ) ? sanitize_text_field( $post['hmcabw_' . $network . '_link'] ) : '';
        
        }

        return update_option('cab_social_settings', $hmcabwSocialSettingsInfo);
    }

    protected function get_social_settings() {

		return get_option('cab_social_settings');
	}

    protected function get_social_network() {

		return array(
			'facebook',
            'instagram', 
            'twitter', 
            'tumblr', 
            'linkedin', 
            'pinterest', 
            'youtube', 
            'quora', 
            'github', 
            'flickr', 
            'stack-overflow', 
            'reddit',
		);
	}
}