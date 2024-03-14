<?php

class WCFM_vendor_settings_shipday {
	public static function init() {
		add_action('end_wcfm_vendor_settings', __CLASS__.'::settings');
	}
	public static function settings() {
		echo '<div class="page_collapsible" id="wcfm_settings_form_store_shipday_head">
				<label class="wcfmfa fa-truck"></label>
				Shipday<span></span>
			</div>
			<div class="wcfm-container">
				<div id="wcfm_settings_form_store_shipday_expander" class="wcfm-content">
					<div class="store_address">';

		global $WCFM;
		$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		$vendor_data = get_user_meta( $user_id, 'wcfmmp_profile_settings', true );

		$api_key = isset( $vendor_data['shipday']['api_key'] ) ? $vendor_data['shipday']['api_key'] : '';
		$fields = array(
			"api_key" => array("label" => __('Shipday API Key', 'wcfm-settings-tab-shipday'),
		                              "name" => "shipday[api_key]",
		                              "type" => "text",
		                              "class" => "wcfm-text wcfm_ele",
		                              "label_class" => "wcfm_title wcfm_ele",
		                              "value" => $api_key )
		);
		$WCFM->wcfm_fields->wcfm_generate_form_field($fields);

		echo '</div>
				</div>
			</div>';
	}
}
?>