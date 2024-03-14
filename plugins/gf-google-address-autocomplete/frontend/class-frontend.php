<?php

class GF_auto_address_complete_frontend {

    function __construct() {
        add_action( 'gform_enqueue_scripts', array($this, 'pc_enqueue_scripts'), 10, 2 );
    }

    function pc_enqueue_scripts( $form, $is_ajax ) {

        $form_id = $form['id'];
        $fields_data = [];
  
        foreach($form['fields'] as $field) {
			if (property_exists($field, 'autocompleteGField') && $field->autocompleteGField) {
                $form = (array) GFFormsModel::get_form_meta($field->formId);
    	        $fields_data[] = json_encode(GFFormsModel::get_field($form, $field->id));
			}
			if (property_exists($field, 'textAutocompleteGField') && $field->textAutocompleteGField) {
                $form = (array) GFFormsModel::get_form_meta($field->formId);
    	        $fields_data[] = json_encode(GFFormsModel::get_field($form, $field->id));
			}
			if (property_exists($field, 'restrictCountryGField') && $field->restrictCountryGField) {
                $form = (array) GFFormsModel::get_form_meta($field->formId);
    	        $fields_data[] = json_encode(GFFormsModel::get_field($form, $field->id));
			}
        }

        if (count($fields_data) === 0) { return; }


        $pc_gf_google_api_key	=	get_option('pc_gf_google_api_key');
        
		if(!empty($pc_gf_google_api_key)){
			wp_enqueue_script('pc-google-places',"https://maps.googleapis.com/maps/api/js?v=3.exp&key=".$pc_gf_google_api_key."&libraries=places");
		}
		else{
			wp_enqueue_script('pc-google-places',"https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places");
        }
        
        wp_enqueue_script('pc_ajax_data', GF_AUTO_ADDRESS_COMPLETE_URL.'js/map_data.js', array( 'pc-google-places' ), GF_AUTO_ADDRESS_COMPLETE_VERSION_NUM );
        wp_localize_script('pc_ajax_data', 'gfaacMainJsVars_'.$form_id, array(
            'elements' =>  $fields_data
            )
        );
    }


}

new GF_auto_address_complete_frontend();