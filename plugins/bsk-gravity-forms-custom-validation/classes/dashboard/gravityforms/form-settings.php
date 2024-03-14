<?php

class BSK_GFCV_Dashboard_GForm_Settings {
	
	function __construct() {
		
        if ( BSK_GFCV_Dashboard_Common::bsk_gfcv_is_form_plugin_supported('GF') ) {
            // add a custom menu item to the Form Settings page menu
            add_filter( 'gform_form_settings_menu', array( $this, 'bsk_gfcv_form_settings_menu_item' ) );
            // handle displaying content for our custom menu when selected
            add_action( 'gform_form_settings_page_bsk_gfcv_form_settings', array( $this, 'bsk_gfcv_form_settings_page' ) );
        }
	}
	
	
    function bsk_gfcv_form_settings_menu_item( $menu_items ) {

        $menu_items[] = array(
            'name' => 'bsk_gfcv_form_settings',
            'label' => __( 'BSK Validation' )
            );

        return $menu_items;
    }


    function bsk_gfcv_form_settings_page() {

        GFFormSettings::page_header();
        
        $form_id = absint( rgget( 'id' ) );
        
        if ( isset( $_POST['save_bsk_gfcv_settings'])) {
			$this->process_form_settings( $form_id );
		}
        
        $this->display_form_settings( $form_id );

        GFFormSettings::page_footer();

    }
    
    function display_form_settings( $form_id ) {

		$form = GFAPI::get_form( $form_id );
        
        //plugin gloabla settings
        $settings_data = get_option( BSK_GFCV_Dashboard::$_plugin_settings_option, false );
        $global_save_blocked_entry = 'NO';
        $global_notify_blocked = 'NO';
        $global_notify_details = false;
        $global_notify_send_to = '';
        if( $settings_data && is_array( $settings_data ) && count( $settings_data ) > 0 ){
            if( isset( $settings_data['save_blocked_entry'] ) ){
                $global_save_blocked_entry = $settings_data['save_blocked_entry'];
            }
            if( isset( $settings_data['notify_blocked'] ) ){
                $global_notify_blocked = $settings_data['notify_blocked'];
            }
            if( isset( $settings_data['notify_details'] ) ){
                $global_notify_details = $settings_data['notify_details'];
                if( isset( $global_notify_details['send_to'] ) && $global_notify_details['send_to'] ){
                    $global_notify_send_to = $global_notify_details['send_to'];
                }
            }
        }

        //form settings
		$bsk_gfcv_form_settings = rgar( $form, 'bsk_gfcv_form_settings' );
        
        $enable = true;
        $action_when_hit = array( 'BLOCK' );
        $save_blocked_data = 'NO';
        $notify_administrators = 'NO';
        $notify_send_to = '';

        if( $bsk_gfcv_form_settings && is_array( $bsk_gfcv_form_settings ) && count( $bsk_gfcv_form_settings ) > 0 ){
            $enable = $bsk_gfcv_form_settings['enable'];
            $action_when_hit = $bsk_gfcv_form_settings['actions'];
            $save_blocked_data = $bsk_gfcv_form_settings['save_blocked_data'];
            $notify_administrators = $bsk_gfcv_form_settings['notify_administrators'];
            $notify_send_to = isset( $bsk_gfcv_form_settings['notify_send_to'] ) ? $bsk_gfcv_form_settings['notify_send_to'] : '';
        }
        
        if( trim( $notify_send_to == '' ) && $global_notify_send_to ){
            $notify_send_to = $global_notify_send_to;
        }
        
        //process display
        $form_settings_actions_container_display = 'block';
        $form_settings_blocked_data_container_display = 'block';

        if( !$enable ){
            $form_settings_actions_container_display = 'none';
            $form_settings_blocked_data_container_display = 'none';
        }
        
		$action_url = admin_url( sprintf( 'admin.php?page=gf_edit_forms&view=settings&subview=bsk_gfcv_form_settings&id=%d', $form_id ) );
		?>
		<h3><span><i class="fa fa-lock"></i> <?php esc_html_e( 'BSK Validaiton', 'bsk_gfcv' ); ?></h3>
		<div class="gform_panel gform_panel_form_settings bsk-gfcv-form-settings-container" id="bsk_gfcv_settings">
			<form action="<?php esc_url( $action_url ); ?>" method="POST">
				<?php wp_nonce_field( 'gravityforms_bsk_gfcv_settings' ); ?>
                <div class="bsk-gfcv-form-settings-enable-disable-container">
                    <h4><?php esc_html_e( 'General settings', 'bsk-bsk_gfcv' ); ?></h4>
                    <table class="gforms_form_settings" cellspacing="0" cellpadding="0">
                        <?php
                        $enable_checked = $enable ? ' checked' : '';
                        $disable_checked = $enable ? '' : ' checked';
                        ?>
                        <tr>
                            <th>&nbsp;</th>
                            <td>
                                <label>
                                    <input type="radio" value="ENABLE" name="bsk_gfcv_form_settings_enable" class="bsk-gfcv-form-settings-enable-raido"<?php echo $enable_checked; ?>/> Enable for this form
                                </label>
                                <label style="margin-left:20px;">
                                    <input type="radio" value="DISABLE" name="bsk_gfcv_form_settings_enable" class="bsk-gfcv-form-settings-enable-raido"<?php echo $disable_checked; ?>/> Disable for this form
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="bsk-gfcv-form-settings-actions-container" style="display: <?php echo $form_settings_actions_container_display; ?>">
                    <h4><?php esc_html_e( 'Actions', 'bsk-bsk_gfcv' ); ?></h4>
                    <table class="gforms_form_settings" cellspacing="0" cellpadding="0">
                        <?php
                        $block_checked = ' checked';
                        ?>
                        <tr class="bsk-gfcv-form-settings-option-tr">
                            <th>&nbsp;</th>
                            <td>
                                <label>
                                    <input type="checkbox" value="BLOCK" name="bsk_gfcv_form_settings_actions[]"<?php echo $block_checked ?> class="bsk-gfcv-form-settings-action-block-chk" readonly /> Block form submitting
                                </label>
                            </td>
                        </tr>
                        <?php
                        $save_yes_checked = $save_blocked_data == 'YES' ? ' checked' : '';
                        $save_no_checked = $save_blocked_data == 'NO' ? ' checked' : '';
                        ?>
                    </table>
                </div>
                <?php
                $blocked_form_data_view_link = admin_url( 'admin.php?page='.BSK_GFCV_Dashboard::$_bsk_gfcv_pages['blocked_data']['slug'].'&target=general-settings' );
                $global_settings = admin_url( 'admin.php?page='.BSK_GFCV_Dashboard::$_bsk_gfcv_pages['settings']['slug'].'&target=blocked-data' );
                ?>
                <div class="bsk-gfcv-form-settings-blocked-data-container" style="display: <?php echo $form_settings_blocked_data_container_display; ?>">
                    <h4><?php esc_html_e( 'Blocked form data', 'bsk-bsk_gfcv' ); ?></h4>
                    <table class="gforms_form_settings" cellspacing="0" cellpadding="0">
                        <tr class="bsk-gfcv-form-settings-option-tr bsk-gfcv-form-settings-save-blocked-data">
                            <th>&nbsp;</th>
                            <td>
                                <span class="bsk-gfcv-form-settings-label">Save blocked form data:</span>
                                <?php
                                if( $global_save_blocked_entry == 'NO' ){
                                ?>
                                <span><a href="<?php echo $global_settings; ?>">Blocked Form Data Global Settings</a></span>
                                <input type="hidden" value="<?php echo $save_blocked_data; ?>" name="bsk_gfcv_save_blocked_data" />
                                <?php
                                }else{
                                ?>
                                <label>
                                    <input type="radio" value="YES" name="bsk_gfcv_save_blocked_data"<?php echo $save_yes_checked ?> /> Yes
                                </label>
                                <label style="margin-left:20px;">
                                    <input type="radio" value="NO" name="bsk_gfcv_save_blocked_data"<?php echo $save_no_checked ?> /> No
                                </label>
                                <span class="bsk-gfcv-form-settings-actions-desc" style="display: inline-block; margin-left: 50px;">Blocked form data listed <a href="<?php echo $blocked_form_data_view_link; ?>">here >></a></span>
                                <p class="bsk-gfcv-tips-box">This feature only availabe in <a href="<?php echo BSK_GFCV_Dashboard::$_bsk_gfcv_pro_verison_url; ?>" target="_blank">Pro version</a>.</p>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                        $notify_yes_checked = $notify_administrators == 'YES' ? ' checked' : '';
                        $notify_no_checked = $notify_administrators == 'NO' ? ' checked' : '';
                        ?>
                        <tr class="bsk-gfcv-form-settings-option-tr"><th>&nbsp;</th></tr>
                        <tr class="bsk-gfcv-form-settings-option-tr bsk-gfcv-form-settings-notify-administrators">
                            <th>&nbsp;</th>
                            <td>
                                <span class="bsk-gfcv-form-settings-label">Notify administrators: </span>
                                <?php
                                if( $global_notify_blocked == 'NO' ){
                                ?>
                                <span><a href="<?php echo $global_settings; ?>">Notify Administrators( emails ) Global Settings</a></span>
                                <input type="hidden" value="<?php echo $notify_administrators; ?>" name="bsk_gfcv_notify_administrators" />
                                <?php
                                }else{
                                ?>
                                <label>
                                    <input type="radio" value="YES" name="bsk_gfcv_notify_administrators"<?php echo $notify_yes_checked ?> class="bsk-gfcv-notifiy-administrators-raido" /> Yes
                                </label>
                                <label style="margin-left:20px;">
                                    <input type="radio" value="NO" name="bsk_gfcv_notify_administrators"<?php echo $notify_no_checked ?> class="bsk-gfcv-notifiy-administrators-raido" /> No
                                </label>
                                <p class="bsk-gfcv-tips-box">This feature only availabe in <a href="<?php echo BSK_GFCV_Dashboard::$_bsk_gfcv_pro_verison_url; ?>" target="_blank">Pro version</a>.</p>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                        $notify_send_to_display = $notify_administrators == 'YES' ? 'table-row' : 'none';
                        if( !$enable || $global_notify_blocked == 'NO' ){
                            $notify_send_to_display = 'none';
                        }
                        ?>
                        <tr class="bsk-gfcv-form-settings-option-tr bsk-gfcv-form-settings-notify-send-to" style="display: <?php echo $notify_send_to_display; ?>">
                            <th>&nbsp;</th>
                            <td>
                                <span class="bsk-gfcv-form-settings-label">Send to: </span>
                                <input type="text" value="<?php echo $notify_send_to; ?>" name="bsk_gfcv_notify_send_to" class="bsk-gfblvc-form-settings-input-width" />
                                <br />
                                <span class="bsk-gfcv-form-settings-label">&nbsp;</span>
                                <span class="bsk-gfcv-form-settings-actions-desc" style="display: inline-block;">user comma( , ) to separate multiple mails</span>
                                <br />
                                <span class="bsk-gfcv-form-settings-label">&nbsp;</span>
                                <span class="bsk-gfcv-form-settings-actions-desc" style="display: inline-block;">Set notify mail template <a href="<?php echo $global_settings; ?>">here >></span>
                            </td>
                        </tr>
                    </table>
                </div>
                <input
                    class="button-primary"
                    type="submit"
                    name="save_bsk_gfcv_settings"
                    value="<?php esc_attr_e( 'Save', 'gravityforms' ); ?>"
                />
			</form>
		</div>
		<?php
	}
    
    function process_form_settings( $form_id ) {
		check_admin_referer( 'gravityforms_bsk_gfcv_settings' );

		$form = GFAPI::get_form( $form_id );

		if ( ! isset( $form['bsk_gfcv_form_settings'] ) ) {
			$form['bsk_gfcv_form_settings'] = array();
		}

        $form_bsk_gfcv_settings = $form['bsk_gfcv_form_settings'];
		$form_bsk_gfcv_settings['enable'] = rgpost( 'bsk_gfcv_form_settings_enable' ) == 'ENABLE' ? true : false;
        $form_bsk_gfcv_settings['actions'] = rgpost( 'bsk_gfcv_form_settings_actions' );
        
        if( !is_array( $form_bsk_gfcv_settings['actions'] ) ){
            $form_bsk_gfcv_settings['actions'] = array( $form_bsk_gfcv_settings['actions'] );
        }

        $form_bsk_gfcv_settings['notify_administrators'] = rgpost( 'bsk_gfcv_notify_administrators' );
        $form_bsk_gfcv_settings['save_blocked_data'] = rgpost( 'bsk_gfcv_save_blocked_data' );
        
        $invalid_send_to_email = false;
        if( $form_bsk_gfcv_settings['notify_administrators'] == 'YES' ){
            $notify_send_to_str = rgpost( 'bsk_gfcv_notify_send_to' );
            $notify_send_to_array = explode( ',', $notify_send_to_str );
            foreach( $notify_send_to_array as $key => $val ){
                $val = trim( $val );
                if( !is_email( $val ) ){
                    $invalid_send_to_email = true;
                    unset( $notify_send_to_array[$key] );
                }
                $notify_send_to_array[$key] = $val;
            }
            $form_bsk_gfcv_settings['notify_send_to'] = implode( ',', $notify_send_to_array );
        }
        
		$form['bsk_gfcv_form_settings'] = $form_bsk_gfcv_settings;
        
        //remove old saved data
		GFAPI::update_form( $form );
		?>
		<div class="updated below-h2" id="after_update_dialog">
			<p>
				<strong><?php _e( 'Custom Validation settings updated successfully.', 'bsk-gfvc' ); ?></strong>
			</p>
		</div>
		<?php
        if( $form_bsk_gfcv_settings['enable'] && $invalid_send_to_email ){
        ?>
        <div class="error below-h2" id="after_update_dialog2" style="padding: 1px 12px;">
			<p>
				<strong><?php _e( 'Invalid email address found for "Send to" field', 'bsk-gfvc' ); ?></strong>
			</p>
		</div>
        <?php
        }
	}
    
}
