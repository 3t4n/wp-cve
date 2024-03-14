<?php 
/**
 * @author: Leadgenix
 * Plugin Name: Gravity Forms Tab Index
 * Author URI: http://www.leadgenix.com/
 * Description: Ability to set a custom tabindex for each field in your gravity form.  Gravity Forms, JavaScript, and JQuery are required.
 * Version: 1.0.1
 * Author: Brett Peterson
 * Credits:  WPSmith, for their tutorial:  http://wpsmith.net/2011/plugins/how-to-create-a-custom-form-field-in-gravity-forms-with-a-terms-of-service-form-field-example/
 * Instructions: On the "Advanced" tab of a field, set the Tab Index.
 * License: GPLv3
 * Last Change: 12/1/2014
*/


// Add our custom scripts, if necessary
/*
add_action( 'admin_enqueue_scripts', 'admin_enqueue_tabindex_js' );
function admin_enqueue_tabindex_js(){
	$url = plugins_url( 'gform_tabindex.js' , __FILE__ );
	wp_enqueue_script( "gform_tabindex_script", $url , array("jquery","gforms_gravityforms"), '1.0', true ); 
}
*/


// Add a custom setting to the tos advanced field
add_action( "gform_field_advanced_settings" , "wps_tabindex_settings" , 10, 2 );
function wps_tabindex_settings( $position, $form_id ){
    if( $position == 50 ){// Create settings on position 50 (right after Field Label)
 ?>
    <li class="tabindex_setting field_setting" style="display:list-item;">

        <label for="field_tabindex" class="inline">
            <?php _e("Tab Index", "gravityforms"); ?>
            <?php gform_tooltip("form_field_tabindex"); ?>
        </label>
        <input type="text" id="field_tabindex" onkeyup="SetFieldProperty('field_tabindex', this.value);" />
 
    </li>
    <?php
    }
}


//Action to inject supporting script to the form editor page so that the values can be saved/displayed
add_action("gform_editor_js", "editor_script");
function editor_script(){
    ?>
    <script type='text/javascript'>
        //adding setting to fields of type ...
		var index;
		var supportedFieldTypes = ["checkbox", "radio", "select", "text", "website", "textarea", "email", "hidden", "number", "phone", "multiselect", "post_title", "post_tags", "post_custom_field", "post_content", "post_excerpt", "date"];
		for(index = 0; index < supportedFieldTypes.length; ++index){		
        	//fieldSettings["text"] += ", .tabindex_setting";
			fieldSettings[supportedFieldTypes[index]] += ", .tabindex_setting";
		}//end for
        //binding to the load field settings event to initialize the checkbox
        jQuery(document).bind("gform_load_field_settings", function(event, field, form){
            jQuery("#field_tabindex").val(field["field_tabindex"]);
        });
    </script>
    <?php
}

 
// Filter to add a new tooltip
add_filter('gform_tooltips', 'wps_add_tabindex_tooltips');
function wps_add_tabindex_tooltips($tooltips){
   $tooltips["form_field_tabindex"] = "<h6>Tabindex</h6>Manually set the tab index for this field.";
   return $tooltips;
}



// Set the tab index
add_action( "gform_field_input" , "wps_tabindex_add", 10, 5 );
function wps_tabindex_add ( $input, $field, $value, $lead_id, $form_id ){
	if ( isset($field["field_tabindex"]) && $field["field_tabindex"] != "" ) {
		GFCommon::$tab_index = $field["field_tabindex"];
		return;
		/*
		$max_chars = "";
		if(!IS_ADMIN && !empty($field["maxLength"]) && is_numeric($field["maxLength"]))
			$max_chars = self::get_counter_script($form_id, $field_id, $field["maxLength"]);

		$input_name = $form_id .'_' . $field["id"];
		$tabindex = ' tabindex = "'.$field["field_tabindex"].'"';
		//GFCommon::get_tabindex();
		$css = isset( $field['cssClass'] ) ? $field['cssClass'] : '';
		return sprintf("<div class='ginput_container'><textarea readonly name='input_%s' id='%s' class='textarea gform_tos %s' $tabindex rows='10' cols='50'>%s</textarea></div>{$max_chars}", $field["id"], 'tos-'.$field['id'] , $field["type"] . ' ' . esc_attr( $css ) . ' ' . $field['size'] , esc_html($value));
		*/
	}
	return $input;
}
