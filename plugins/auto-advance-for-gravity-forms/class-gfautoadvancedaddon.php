<?php

GFForms::include_addon_framework();
class GFAutoAdvancedAddOn extends GFAddOn
{
    protected  $_version = AUTO_ADVANCED_ZZD ;
    protected  $_min_gravityforms_version = '1.9' ;
    protected  $_slug = 'auto-advance-for-gravity-forms' ;
    protected  $_path = 'auto-advance-for-gravity-forms/auto-advance-for-gravity-forms.php' ;
    protected  $_full_path = __FILE__ ;
    protected  $_title = 'Gravity Forms Auto Advanced Add-On' ;
    protected  $_short_title = 'Auto Advanced Add-On' ;
    private static  $_instance = null ;
    /**
     * Get an instance of this class.
     *
     * @return GFAutoAdvancedAddOn
     */
    public static function get_instance()
    {
        if ( self::$_instance == null ) {
            self::$_instance = new GFAutoAdvancedAddOn();
        }
        return self::$_instance;
    }
    
    /**
     * Handles hooks and loading of language files.
     */
    public function init()
    {
        parent::init();
        add_action(
            'gform_field_advanced_settings',
            array( $this, 'auto_advanced_field_settings' ),
            10,
            2
        );
        add_action( "gform_editor_js", array( $this, "editor_script_main" ), 10 );
        add_action( "gform_editor_js", array( $this, "editor_script" ), 12 );
        add_action( "wp_footer", array( $this, "footer_script" ), PHP_INT_MAX );
        add_filter( 'gform_pre_render', array( $this, "addon_pre_render" ) );
        
        if ( false && !wp_script_is( 'gform_conditional_logic' ) ) {
            $base_url = GFCommon::get_base_url();
            $version = GFForms::$version;
            $min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min' );
            wp_register_script(
                'gform_conditional_logic',
                $base_url . "/js/conditional_logic{$min}.js",
                array( 'jquery', 'gform_gravityforms' ),
                $version
            );
            wp_enqueue_script( 'gform_conditional_logic' );
        }
        
        wp_enqueue_script(
            "auto-trigger-next",
            plugin_dir_url( __FILE__ ) . "js/aafg_script.js",
            array( 'jquery' ),
            rand( 1, 122 ),
            true
        );
        wp_enqueue_style(
            "auto-trigger-next-css",
            plugin_dir_url( __FILE__ ) . "css/aafg_styles.css",
            array(),
            rand( 1, 100 )
        );
    }
    
    public function auto_advanced_field_settings( $position, $form_id )
    {
        // Get the form
        $form = GFAPI::get_form( $form_id );
        // Get form settings
        $settings = $this->get_form_settings( $form );
        // Only show settings if Sticky List is enabled for this form
        // Show below everything else
        
        if ( $position == -1 ) {
            ?>

				<li class="list_setting">
					<label class="section_label"><?php 
            _e( "Auto Advance", "gf-autoadvanced" );
            ?> </label>
					<ul>
						<li>
							<input type="checkbox" id="field_list_value" onclick="SetFieldProperty('autoAdvancedField', this.checked);" />
							<label class="inline" for="field_list_value"><?php 
            _e( "Auto advance form page when item is selected", "gf-autoadvanced" );
            ?> </label>			
						</li>
						<li>
							<input type="checkbox" id="hide_next_button" onclick="SetFieldProperty('hideNextButton', this.checked);" />
							<label class="inline" for="hide_next_button"><?php 
            _e( "Hide Next Button", "gf-autoadvanced" );
            ?></label>
						</li>
						<li>
							<input type="checkbox" id="hidePreviousButton" onclick="SetFieldProperty('hidePreviousButton', this.checked);" />
							<label class="inline" for="hidePreviousButton"><?php 
            _e( "Hide Previous Button", "gf-autoadvanced" );
            ?></label>
						</li>
						<li style="display:none;">
							<input type="checkbox" id="hideSubmitButton" onclick="SetFieldProperty('hideSubmitButton', this.checked);" />
							<label class="inline" for="hideSubmitButton"><?php 
            _e( "Hide Submit Button", "gf-autoadvanced" );
            ?></label>
						</li>
					</ul>
						
				</li>
				
				<?php 
        }
    
    }
    
    public function editor_script()
    {
        ?>
		<script type='text/javascript'>
			// Bind to the load field settings event to initialize the inputs
			
			var labels = "Auto advance form page when item is selected";
			var label_last = "Auto Complete Form";
			
			jQuery(document).bind("gform_load_field_settings", function(event, field, form){
				
				var total_pages = 1;
				if( form.pagination && form.pagination != 'null' && typeof form.pagination != 'undefined' ) {
					total_pages = form.pagination.pages.length;
				}
				
				setTimeout(function() {
					
					if( field.pageNumber ==  1 && total_pages == 1) { 
						// jQuery("#hide_next_button").parent().hide();
						// jQuery("#hidePreviousButton").parent().hide();
						jQuery("#hideSubmitButton").parent().show();
						// jQuery("#field_list_value").next().html(label_last);
					}
					else if( field.pageNumber ==  1 && total_pages > 1) {
						// jQuery("#hide_next_button").parent().show();
						// jQuery("#hidePreviousButton").parent().hide();
						jQuery("#hideSubmitButton").parent().hide();
						// jQuery("#field_list_value").next().html(labels);
					}
					else if( field.pageNumber == total_pages) {
						// jQuery("#hide_next_button").parent().hide();
						// jQuery("#hidePreviousButton").parent().show();
						jQuery("#hideSubmitButton").parent().show();
						// jQuery("#field_list_value").next().html(label_last);
					}
					else if( field.pageNumber >  1 && field.pageNumber < total_pages) {
						// jQuery("#hide_next_button").parent().show();
						// jQuery("#hidePreviousButton").parent().show();
						jQuery("#hideSubmitButton").parent().hide();
						// jQuery("#field_list_value").next().html(labels);
					}
					
					
				},100);
				
				var other_fields = false;
				if (typeof aagf_check_product_field == "function") {
					other_fields = aagf_check_product_field(field);
				}
				
				if(field.type == 'radio' || field.type == 'select' || field.type == 'quiz' || field.type == 'poll' || other_fields ) {
					setTimeout(function() {
						
						jQuery("#advanced_tab .list_setting").show();
						jQuery("#field_list_value").prop("checked", field["autoAdvancedField"] == true);
						jQuery("#hide_next_button").prop("checked", field["hideNextButton"] == true);
						jQuery("#hidePreviousButton").prop("checked", field["hidePreviousButton"] == true);
						jQuery("#hideSubmitButton").prop("checked", field["hideSubmitButton"] == true);
					}, 100);
					
				}
				else if(field.type == 'survey' ) {
					var type = jQuery("#gsurvey-field-type").val();
					
					if( type == "radio" || type == "select" || type == "likert" || type == "rating" ) {
						setTimeout(function() {
							
							jQuery("#advanced_tab .list_setting").show();
							jQuery("#field_list_value").prop("checked", field["autoAdvancedField"] == true);
							jQuery("#hide_next_button").prop("checked", field["hideNextButton"] == true);
							jQuery("#hidePreviousButton").prop("checked", field["hidePreviousButton"] == true);
							jQuery("#hideSubmitButton").prop("checked", field["hideSubmitButton"] == true);
						}, 100);
					}
					
				}
				else {
					setTimeout(function() {
						jQuery("#advanced_tab .list_setting").hide();
					}, 100);
				}
				
				if( total_pages == field.pageNumber ) {
					
				}
			
			});
		</script>
		<?php 
    }
    
    public function editor_script_main()
    {
    }
    
    public function footer_script()
    {
    }
    
    public function addon_pre_render( $form )
    {
        $fields = $form['fields'];
        foreach ( $fields as $field ) {
            if ( isset( $field->autoAdvancedField ) && $field->autoAdvancedField != "" && $field->autoAdvancedField != 0 ) {
                $field->cssClass .= " trigger-next-zzd";
            }
            if ( isset( $field->hideNextButton ) && $field->hideNextButton != "" && $field->hideNextButton != 0 ) {
                $field->cssClass .= " hide-next-button";
            }
            if ( isset( $field->hidePreviousButton ) && $field->hidePreviousButton != "" && $field->hidePreviousButton != 0 ) {
                $field->cssClass .= " hide-previous-button";
            }
            if ( isset( $field->hideSubmitButton ) && $field->hideSubmitButton != "" && $field->hideSubmitButton != 0 ) {
                $field->cssClass .= " hide-submit-button";
            }
            $input_type = $field->get_input_type();
            
            if ( is_array( $field->choices ) && $input_type != 'list' ) {
                $field_val = RGFormsModel::get_parameter_value( $field->inputName, [], $field );
                $field_val = $field->get_value_default_if_empty( $field_val );
                foreach ( $field->choices as $choice ) {
                    if ( $input_type == 'checkbox' && $choice_index % 10 == 0 ) {
                        $choice_index++;
                    }
                    $is_prepopulated = ( is_array( $field_val ) ? in_array( $choice['value'], $field_val ) : $choice['value'] == $field_val );
                    $is_choice_selected = rgar( $choice, 'isSelected' ) || $is_prepopulated;
                    if ( $is_prepopulated ) {
                        break;
                    }
                }
                $field->cssClass .= ( $is_prepopulated ? " has-input-name populated " : " has-input-name" );
            }
            
            // echo "<pre>"; print_r($field); echo "</pre>";
        }
        return $form;
    }

}
add_filter(
    'style_loader_src',
    'aafg_remove_ver_css_js',
    9999,
    2
);
add_filter(
    'script_loader_src',
    'aafg_remove_ver_css_js',
    9999,
    2
);
function aafg_remove_ver_css_js( $src, $handle )
{
    if ( !isset( $_REQUEST['zzd_dev'] ) ) {
        return $src;
    }
    $handles_with_version = [ 'style' ];
    // <-- Adjust to your needs!
    if ( strpos( $src, 'ver=' ) && !in_array( $handle, $handles_with_version, true ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    $src = add_query_arg( "ver", rand( 0, 1000 ), $src );
    return $src;
}

// add_filter('gform_field_container', 'gform_field_container_aa', 10, 6);
function gform_field_container_aa(
    $field_container,
    $field,
    $form,
    $css_class,
    $style,
    $field_content
)
{
    
    if ( is_array( $field->choices ) && $input_type != 'list' ) {
        $field_val = RGFormsModel::get_parameter_value( $field->inputName, $field_values, $field );
        $field_val = $field->get_value_default_if_empty( $field_val );
        foreach ( $field->choices as $choice ) {
            if ( $input_type == 'checkbox' && $choice_index % 10 == 0 ) {
                $choice_index++;
            }
            $is_prepopulated = ( is_array( $field_val ) ? in_array( $choice['value'], $field_val ) : $choice['value'] == $field_val );
            $is_choice_selected = rgar( $choice, 'isSelected' ) || $is_prepopulated;
        }
    }
    
    if ( isset( $field->inputName ) && $field->inputName != "" ) {
        $field_container = str_replace( "has-input-name", "has-input-name test", $field_container );
    }
    return $field_container;
}
