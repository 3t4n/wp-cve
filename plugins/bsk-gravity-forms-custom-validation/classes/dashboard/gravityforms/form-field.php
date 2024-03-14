<?php

class BSK_GFCV_Dashboard_GForm_Field {
	
	private $_bsk_gfcv_current_form_id = '';
	
	function __construct() {
		
		if ( BSK_GFCV_Dashboard_Common::bsk_gfcv_is_form_plugin_supported('GF') ) {
			add_filter( 'gform_admin_pre_render', array($this, 'bsk_gfcv_admin_pre_render') );
			add_action( 'gform_field_advanced_settings', array($this, 'bsk_gfcv_render_field_advanced_settings'), 10, 2 );
			add_action( 'gform_editor_js', array($this, 'bsk_gfcv_render_editor_js') );
			// filter to add a new tooltip
			add_filter( 'gform_tooltips', array($this, 'bsk_gfcv_add_gf_tooltips') );
		}
	}
	
	function bsk_gfcv_admin_pre_render( $form ){
		if( !isset( $form['fields'] ) || !is_array( $form['fields'] ) || count( $form['fields'] ) < 1 ){
			return $form;
		}
		
		return $form;
	}
	
	function bsk_gfcv_render_field_advanced_settings( $position, $form_id ){
		
        if($position != 50){
            return;
        }
        
		$this->_bsk_gfcv_current_form_id = $form_id;
		$form = GFAPI::get_form( $form_id );

        //form settings
		$bsk_gfcv_form_settings = rgar( $form, 'bsk_gfcv_form_settings' );
        
        $enable = true;
        $action_when_hit = array( 'BLOCK' );
        if( $bsk_gfcv_form_settings && is_array( $bsk_gfcv_form_settings ) && count( $bsk_gfcv_form_settings ) > 0 ){
            $enable = $bsk_gfcv_form_settings['enable'];
            $action_when_hit = $bsk_gfcv_form_settings['actions'];
        }
        
        if( $enable ){
        ?>
        <li class="bsk-gfbl-field-setting field_setting" style="display:list-item;">
            <label for="bsk-gfbl" class="section_label">BSK Validation<?php gform_tooltip("bsk_gfcv_form_field_section_label") ?></label>
            <div class="bsk_gfcv_field_single_input_container">
                <ul>
                    <li class="bsk-gfcv-apply-list-field-setting" style="display:list-item;">
                        <input type="checkbox" class="toggle_setting" id="bsk_gfcv_apply_list_chk_ID" />
                        <label class="inline" for="bsk_gfcv_apply_list_chk_ID">
                            <?php _e("Apply List", "bsk-gfbl"); ?>
                        </label>
                        <br />
                        <select class="bsk-gfbl-list fieldwidth-2" onchange="SetFieldProperty('bsk_gfcv_listperty', jQuery(this).val());" style="margin-top:10px; display:none;">
                            <option value="">Select a list...</option>
                            <?php echo BSK_GFCV_Dashboard_Common::bsk_gfcv_get_list_by_type( 'CV_LIST', '' ); ?>
                        </select>
                        <p class="bsk-gfbl-comparison" style="display: none;">The validation message was set when add rule to list</p>
                    </li>
                </ul>
            </div>
            <div class="bsk_gfcv_field_multiple_inputs_container"></div>
        </li>
        <?php
        }else{
            
            $form_settings_url = admin_url( sprintf( 'admin.php?page=gf_edit_forms&view=settings&subview=bsk_gfcv_form_settings&id=%d', $form_id ) );
        ?>
        <li class="bsk-gfbl-field-setting field_setting" style="display:list-item;">
            <label for="bsk-gfbl" class="section_label">BSK Validation<?php gform_tooltip("bsk_gfcv_form_field_section_label") ?></label>
            <p><a href="<?php echo $form_settings_url; ?>">Enable for this form</a></p>
        </li>
        <?php
        }
	}
	
	/*
	 * render some custom JS to get the settings to work
	 */
	function bsk_gfcv_render_editor_js(){
		?>
		<script type='text/javascript'>

			jQuery(document).bind("gform_load_field_settings", function(event, field, form){

				var bsk_gfcv_setting_container = jQuery(".bsk-gfbl-field-setting");
				if( !bsk_gfcv_setting_container ){
					return;
				}
				jQuery( ".bsk_gfcv_field_single_input_container").hide();
				jQuery( ".bsk_gfcv_field_multiple_inputs_container").hide();
				
				if( field['displayOnly'] || field['type'] == 'fileupload' ){
					//show the setting container!
					bsk_gfcv_setting_container.hide();
				}else{
					//show the setting container!
					bsk_gfcv_setting_container.show();
				}
				
				if( field['type'] == 'name' || field['type'] == 'address' ){
					//create fields map
                    var table_header = '<table class="default_input_values striped fieldwidth-2"><tbody style="width:100%;">';
					var table_body = '<tr><td style="width:30%;"><strong>Field</strong></td><td style="width:70%;"><strong>List</strong></td></tr>';
					jQuery.each( field['inputs'], function(key, input_obj){
						var id_str = input_obj['id'].replace( '.', '_' );
						table_body += '<tr class="bsk_gfcv_multiple_fields_row" id="bsk_gfcv_multiple_fields_row_' + id_str + '" data-input_id="' + input_obj['id'] + '">' + 
									  '<td><label class="inline">' + input_obj['label'] + '</label></td>' +
									  '<td>' +
									  '<select class="bsk_gfcv_list_id_select" style="width:100%;display:block;" field-id="' + input_obj['id'] + '"><option value="">Select...</option>' + '<?php echo BSK_GFCV_Dashboard_Common::bsk_gfcv_get_list_by_type( 'CV_LIST', '' ); ?>' + '</select>' + 
									  '</td>' + 
									  '</tr>';
					});
					var table_footer = '</tbody></table>';
					jQuery( ".bsk_gfcv_field_multiple_inputs_container").html( table_header + table_body + table_footer );
					jQuery( ".bsk_gfcv_field_multiple_inputs_container").show();
					
					//set val of select
					jQuery.each( field['inputs'], function(key, input_obj){
						
						var id_str = input_obj['id'].replace( '.', '_' );
						var row_container = jQuery("#bsk_gfcv_multiple_fields_row_" + id_str);

						row_container.find(".bsk_gfcv_list_type_select").val( "" );
						
                        //get saved cv list
						var property_key = 'bsk_gfcv_listperty_' + input_obj['id'];
						var cvperty = (typeof field[property_key] != 'undefined' && field[property_key] != '') ? field[property_key] : false;
                        
                        if( !cvperty ){
                            //compatibe with old
                            property_key = 'bsk_gfbl_apply_cv_listperty_' + input_obj['id'];
                            cvperty = (typeof field[property_key] != 'undefined' && field[property_key] != '') ? field[property_key] : false;
                        }
                        
                        var cvlist_options_array = [];
                        row_container.find(".bsk_gfcv_list_id_select > option").each(function(){
                           if( jQuery(this).val() == "" ){
                               return;
                           }
                           cvlist_options_array.push( jQuery(this).val() ); 
                        });
						if( cvperty && 
                            jQuery.inArray( cvperty, cvlist_options_array ) != -1 ){
							row_container.find(".bsk_gfcv_list_id_select").val( cvperty );
						}
					});
				}else{
					jQuery( ".bsk_gfcv_field_single_input_container").show();
					
                    var apply_cv_list_setting_container = jQuery(".bsk-gfcv-apply-list-field-setting");
					var cv_listperty = (typeof field['bsk_gfcv_listperty'] != 'undefined' && field['bsk_gfcv_listperty'] != '') ? field['bsk_gfcv_listperty'] : false;
					var cv_list_Action = (typeof field['bsk_gfcv_list_Comparison'] != 'undefined' && field['bsk_gfcv_list_Comparison'] != '') ? field['bsk_gfcv_list_Comparison'] : false;
                    
                    if( !cv_listperty ){
                        //compatible old
                        var cv_listperty = (typeof field['bsk_gfbl_apply_cv_listperty'] != 'undefined' && field['bsk_gfbl_apply_cv_listperty'] != '') ? field['bsk_gfbl_apply_cv_listperty'] : false;
				        var cv_list_Action = (typeof field['bsk_gfbl_apply_cv_list_Comparison'] != 'undefined' && field['bsk_gfbl_apply_cv_list_Comparison'] != '') ? field['bsk_gfbl_apply_cv_list_Comparison'] : false;
                    }
                    
                    var cv_list_options_array = [];
                    apply_cv_list_setting_container.find(".bsk-gfbl-list > option").each(function(){
                       if( jQuery(this).val() == "" ){
                           return;
                       }
                       cv_list_options_array.push( jQuery(this).val() ); 
                    });
                    
					if ( cv_listperty != false &&
                        jQuery.inArray( cv_listperty, cv_list_options_array ) != -1 ) {
						//check the checkbox if previously checked
						apply_cv_list_setting_container.find("input:checkbox").attr("checked", "checked");
						//set the list select and show
						apply_cv_list_setting_container.find(".bsk-gfbl-list").val( cv_listperty ).show();
						apply_cv_list_setting_container.find(".bsk-gfbl-comparison").show();

					} else {
						apply_cv_list_setting_container.find("input:checkbox").removeAttr("checked");
						apply_cv_list_setting_container.find(".bsk-gfbl-list").val('').hide();
						apply_cv_list_setting_container.find(".bsk-gfbl-comparison").hide();
					}
				}
			});
			
			
            jQuery(".bsk-gfcv-apply-list-field-setting input:checkbox").click(function() {
				var checked = jQuery(this).is(":checked");
				var select = jQuery(this).parent(".bsk-gfcv-apply-list-field-setting:first").find("select");
				if( checked ){
					select.slideDown();
                    jQuery(this).parent(".bsk-gfcv-apply-list-field-setting:first").find(".bsk-gfbl-comparison").slideDown();
				} else {
					SetFieldProperty( 'bsk_gfcv_listperty', '' );                    
					select.slideUp();
				}
			});
            
            //for multiple fields
			jQuery(".bsk_gfcv_field_multiple_inputs_container").on("change", "select", function() {
				var select_val = jQuery(this).val();
				var field_id = jQuery(this).attr("field-id");
				var class_val = jQuery(this).attr("class");
                var row_obj = jQuery(this).parents(".bsk_gfcv_multiple_fields_row");
                
				if( class_val == 'bsk_gfcv_list_id_select' ){
					SetFieldProperty( 'bsk_gfcv_listperty_' + field_id, select_val );
                    
					return;
				}
			});
            
		</script>
		<?php
	}
	
	/*
     * Add tooltips for the new field values
	 */
	function bsk_gfcv_add_gf_tooltips($tooltips){
		
		$tooltips["bsk_gfcv_form_field_section_label"] = 
                     'Block sumbmission if the imput value doesn\'t match any rule in the chosen list';
        
        $tooltips['bsk_gfcv_validation_message_tip'] = '[FIELD_LABEL] will be replaced with field label<br />[FIELD_VALUE] will be replaced with field value<br />[VISITOR_IP] will be replaced with visitor\'s IP';
		
		return $tooltips;
	}

}
