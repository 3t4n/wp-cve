<?php

if(!defined('ABSPATH')) {
	exit;
}

class GF_auto_address_complete_admin {

    function __construct() {
        add_filter( 'gform_addon_navigation', array($this,'pc_gf_api_key_menu_item') );
        add_action( 'gform_field_standard_settings', array($this,'pc_gf_advanced_field_settings'), 10, 2 );
		add_action( 'gform_editor_js', array($this,'pc_editor_script') );
		add_filter( 'gform_tooltips', array($this,'pc_add_autocomplete_tooltips') );
		add_action( 'admin_enqueue_scripts', array( $this, 'pc_admin_scripts' ) );
    }

    function pc_gf_api_key_menu_item($menu_items){
		$menu_items[] = array( 
			"name" => "pc_gf_api_key_settings", 
			"label" => "Autocomplete API Settings", 
			"callback" => array($this,"pc_gf_api_key_set_fields"), 
			"permission" => "manage_options" );
	    return $menu_items;	
    }
    
    function pc_gf_api_key_set_fields() {
        ?>
		<div class="wrap">
			<h3><?php _e("Gravity Forms Address Autocomplete Settings", "gravityforms"); ?></h3>
			<hr />
			<?php

			if(isset($_POST['pc_save_google_api'])) {
				update_option('pc_gf_google_api_key', sanitize_text_field($_POST['pc_gf_google_api_key']));
			}

			$pc_gf_google_api_key	=	get_option('pc_gf_google_api_key');
			echo $pc_gf_google_api_key;
			?>
			<div id="tab_settings" >
				<form method="post">
					<table class="form-table">
						<tbody>		
							<tr valign="top">
								<th><label><?php _e("Google Places API Key", "gravityforms"); ?></label></th>
								<td><input class="pc_gf_google_api_key" type="text" name="pc_gf_google_api_key" value="<?php echo esc_attr($pc_gf_google_api_key); ?>"></td>
							</tr>
							<tr valign="top">
								<th>&nbsp;</th>
								<td><input class="button button-large button-primary" type="submit" name="pc_save_google_api" value="Save"></td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
		<?php
    }


    function pc_gf_advanced_field_settings( $position, $form_id ) { 
        if ( $position == 25 ) { ?>
            <li class="gfautocomplete_setting field_setting">
	            <ul>
					<li>
						<input type="checkbox" id="pc_field_autocomplete_value" onclick="SetFieldProperty('autocompleteGField', this.checked);" />
						<label for="pc_field_autocomplete_value" class="inline">
							<?php _e("Enable Autocomplete with Google Places API", "gravityforms"); ?>
							<?php gform_tooltip("pc_autocomplete_tooltip"); ?>	
						</label>
					</li>
				</ul>
	        </li>
            <li class="gfautosingle_setting field_setting">
	            <ul>
					<li>
						<input type="checkbox" id="pc_singe_field_autocomplete_value" onclick="SetFieldProperty('singleAutofillGField', this.checked);" />
						<label for="pc_singe_field_autocomplete_value" class="inline">
							<?php _e("Use Single Line autofill ", "gravityforms"); ?>
							<?php gform_tooltip("pc_single_autocomplete_tooltip"); ?>	
						</label>
					</li>
					<li class="gfrestrict_setting field_setting">
						<label for="field_admin_label" class="section_label">
							<?php _e("Restrict country", "gravityforms"); ?>
							<?php gform_tooltip("restrict_tooltips"); ?>
						</label>
						<select name="pc_restrict_country_value" id="pc_restrict_country_value" onChange="SetFieldProperty('restrictCountryGField', this.value);">
							<option value="">Please select</option>
							<?php
								foreach ( GF_AA_Helper::get_countries() as $value => $name ) {
									echo '<option value="' . $value . '">' . $name . '</option>'; 
								}
							?>
						</select>
					</li>
				</ul>
	        </li>
			
			<li class="gftextautocomplete_setting field_setting">
	            <ul>
					<li>
						<input type="checkbox" id="pc_text_field_autocomplete_value" onclick="SetFieldProperty('textAutocompleteGField', this.checked);" />
						<label for="pc_text_field_autocomplete_value" class="inline">
							<?php _e("Enable Autocomplete with Google Places API", "gravityforms"); ?>
							<?php gform_tooltip("pc_autocomplete_tooltip"); ?>	
						</label>
					</li>
				</ul>
	        </li>

    <?php 
        }
    }

    function pc_editor_script() {
        ?>
	    <script type='text/javascript'>
	        //adding setting to fields of type "address"
	        fieldSettings.address  	+= ", .gfautocomplete_setting";
	        fieldSettings.address  	+= ", .gfautosingle_setting";
	        fieldSettings.address  	+= ", .gfrestrict_setting";
	        fieldSettings.text  	+= ", .gftextautocomplete_setting";
			
	        //binding to the load field settings event to initialize the checkbox
	       	jQuery(document).bind("gform_load_field_settings", function(event, field, form){
	            jQuery("#pc_field_autocomplete_value").attr("checked", field["autocompleteGField"] == true);
	            jQuery("#pc_singe_field_autocomplete_value").attr("checked", field["singleAutofillGField"] == true);
				jQuery("#pc_restrict_country_value").val( field["restrictCountryGField"] );
	            jQuery("#pc_text_field_autocomplete_value").attr("checked", field["textAutocompleteGField"] == true);
	        });
	    </script>
	    <?php
    }


	function pc_add_autocomplete_tooltips( $tooltips ) {
		$tooltips['pc_autocomplete_tooltip'] = "<h6>".esc_html__("Enable google auto suggestion", "gravityforms")."</h6>".esc_html__("Check this box to show google address auto complete suggestion", "gravityforms")."";
		$tooltips['pc_single_autocomplete_tooltip'] = esc_html__("Check this box for use single field autocomplete", "gravityforms");
		$tooltips['restrict_tooltips'] = esc_html__("Choose country for adding restriction.", "gravityforms");
		return $tooltips;
	}

	function pc_admin_scripts() {
		wp_enqueue_script( 'pc_admin', GF_AUTO_ADDRESS_COMPLETE_URL . 'js/pc_admin.js', array(), GF_AUTO_ADDRESS_COMPLETE_VERSION_NUM, true );
	}


}

new GF_auto_address_complete_admin();