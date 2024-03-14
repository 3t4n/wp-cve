<?php

class BSK_GFCV_Dashboard_FF_Field {
	
	function __construct() {
        if ( BSK_GFCV_Dashboard_Common::bsk_gfcv_is_form_plugin_supported('FF') ) {
            add_action( 'frm_after_field_options', array( $this, 'bsk_gfcv_ff_field_settings_html' ), 10, 1 );
            add_filter( 'frm_default_field_opts', array( $this, 'bsk_gfcv_ff_field_settings_save' ), 20, 3 );
        }
	}
	
    function bsk_gfcv_ff_field_settings_html( $field_display_values ) {
        extract( $field_display_values );
        if ( in_array( $field['type'], array( 'html', 'user_id', 'captcha', 'hidden' ) ) ) {
            return;
        }
        ?>
        <h3 class="ff2z-populating-from-zoho-field-title">BSK Validation<i class="frm_icon_font frm_arrowdown6_icon"></i></h3>
        <?php
        //form settings
        $form_id = $values['id'];
		$bsk_gfcv_form_settings = maybe_unserialize( get_option( BSK_GFCV_Dashboard_Formidable_Forms::$_bsk_gfcv_ff_form_settings_option_name_prefix . $form_id ) );
        
        $enable = true;
        $action_when_hit = array( 'BLOCK' );
        if( $bsk_gfcv_form_settings && is_array( $bsk_gfcv_form_settings ) && count( $bsk_gfcv_form_settings ) > 0 ){
            $enable = $bsk_gfcv_form_settings['enable'];
            $action_when_hit = $bsk_gfcv_form_settings['actions'];
        }
        
        if ( ! $enable ) {
            $form_settings_url = admin_url( sprintf( 'admin.php?page=formidable&frm_action=settings&id=%d#bsk_cvlist_ff_form_settings_tab_settings', $form_id ) );
            ?>
            <div class="bsk_gfcv_field_single_input_container frm_grid_container frm-collapse-me">
                <p><a href="<?php echo $form_settings_url; ?>">Enable for this form</a></p>
            </div>
            <?php
            
            return;
        }
        
        $cvlist_list = isset ( $field['bsk_gfcv_apply_cvlist_Property'] ) ? intval( $field['bsk_gfcv_apply_cvlist_Property'] ) : 0;
        ?>
        <div class="bsk_gfcv_field_single_input_container frm_grid_container frm-collapse-me">
            <ul>
                <?php
                $display = 'none';
                $checked = '';
                if ( $cvlist_list > 0 ) {
                    $display = 'block';
                    $checked = ' checked';
                }
                ?>
                <li class="bsk-gfcv-apply-cvlist-field-setting" style="display:list-item;">
                    <input type="checkbox" name="bsk_gfcv_ff_form_field_apply_cvlist_chk_<?php echo $field['id']; ?>" id="bsk_gfcv_ff_form_field_apply_cvlist_chk_<?php echo $field['id']; ?>_ID" class="toggle_setting bsk-gfcv-ff-form-field-apply-list-chk"<?php echo $checked; ?> />
                    <label class="inline" for="bsk_gfcv_ff_form_field_apply_cvlist_chk_<?php echo $field['id']; ?>_ID">
                        <?php _e("Apply List", "bsk-gfcv"); ?>
                    </label>
                    <br />
                    <select name="bsk_gfcv_ff_form_field_apply_cvlist_<?php echo $field['id']; ?>" class="bsk-gfcv-list" style="margin-top:10px; display:<?php echo $display ?>;">
                        <option value="">Select a list...</option>
                        <?php echo BSK_GFCV_Dashboard_Common::bsk_gfcv_get_list_by_type( 'CV_LIST', $cvlist_list ); ?>
                    </select>
                </li>
            </ul>
            <p>
                <input type="hidden" name="bsk_gfcv_ff_form_field_save" value="SAVE" />
            <p>
            <div style="clear: both;">&nbsp;</div>
        </div>
        <?php
    }
    
    function bsk_gfcv_ff_field_settings_save( $opts, $values, $field ){
        
        if ( isset( $_POST['bsk_gfcv_ff_form_field_apply_cvlist_'.$field->id] ) ) {
            $opts['bsk_gfcv_apply_cvlist_Property'] = sanitize_text_field( $_POST['bsk_gfcv_ff_form_field_apply_cvlist_'.$field->id] );
        } else {
            $opts['bsk_gfcv_apply_cvlist_Property'] = '';
        }

        return $opts;
    }
    
}
