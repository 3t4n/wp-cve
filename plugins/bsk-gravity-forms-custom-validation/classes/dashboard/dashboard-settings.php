<?php
class BSK_GFCV_Dashboard_Settings {
	
	public function __construct() {
		
        add_action( 'bsk_gfcv_save_general_settings', array( $this, 'bsk_gfcv_save_general_settings_fun' ) );
        add_action( 'bsk_gfcv_save_block_data_settings', array( $this, 'bsk_gfcv_save_block_data_settings_fun' ) );
	}

    function display(){
        $settings_data = get_option( BSK_GFCV_Dashboard::$_plugin_settings_option, false );
		?>
        <div class="wrap" id="bsk_gfcv_setings_wrap_ID">
            <div id="icon-edit" class="icon32"><br/></div>
            <h2 class="nav-tab-wrapper">
                <a class="nav-tab nav-tab-active" href="javascript:void(0);" id="bsk_gfcv_setings_tab-general-settings"><?php esc_html_e( 'General', 'bskgfbl' ); ?></a>
                <a class="nav-tab" href="javascript:void(0);" id="bsk_gfcv_setings_tab-blocked-data"><?php esc_html_e( 'Block Form Data & Notify', 'bskgfbl' ); ?></a>
            </h2>
            <div id="bsk_gfcv_setings_tab_content_wrap_ID">
				<section><?php $this->show_general_settings( $settings_data, 'general-settings' ); ?></section>
                <section><?php $this->show_blocked_data_settings( $settings_data, 'blocked-data' ); ?></section>
            </div>
        </div>
        <?php
        $target_tab = isset($_REQUEST['target']) ? sanitize_text_field($_REQUEST['target']) : '';
		echo '<input type="hidden" id="bsk_gfcv_settings_target_tab_ID" value="'.$target_tab.'" />';
    }
	
    function show_general_settings( $settings_data, $target_tab ){
        $action_url = admin_url( 'admin.php?page='.BSK_GFCV_Dashboard::$_bsk_gfcv_pages['settings']['slug'].'&target='.$target_tab );
        $supported_form_plugins = array( 'GF' );
        $disable_supported_chk = '';
        if( $settings_data && isset( $settings_data['supported_form_plugins'] ) && is_array( $settings_data['supported_form_plugins'] ) ){
            $supported_form_plugins = $settings_data['supported_form_plugins'];
        }
    ?>
    <form action="<?php echo $action_url; ?>" method="POST" id="bsk_gfcv_general_settings_form_ID">
    <div>
        <h3 style="margin-top: 40px;">Supported form plugins</h3>
        <?php
        $gf_checked = '';
        $ff_checked = '';
        $wpf_checked = '';
        $cf7_checked = '';
        if( in_array( 'GF', $supported_form_plugins ) ){
            $gf_checked = 'checked';
        }
        if( in_array( 'FF', $supported_form_plugins ) ){
            $ff_checked = 'checked';
        }
        if( in_array( 'WPF', $supported_form_plugins ) ){
            $wpf_checked = 'checked';
        }
        if( in_array( 'CF7', $supported_form_plugins ) ){
            $cf7_checked = 'checked';
        }
        ?>
        <p>
            <label style="display: inline-block; width: 10%;">
                <input type="radio" name="bsk_gfcv_supported_form_plugins[]" value="GF" <?php echo $gf_checked ?> /> Gravity Forms
            </label>
            <label style="display: inline-block; width: 10%;">
                <input type="radio" name="bsk_gfcv_supported_form_plugins[]" value="FF" <?php echo $ff_checked ?> /> Formidable Forms
            </label>
            <?php if ( 0 ) { ?>
            <label style="display: inline-block; width: 10%;">
                <input type="radio" name="bsk_gfcv_supported_form_plugins[]" value="WPF" <?php echo $wpf_checked ?> /> WPForms
            </label>
            <label style="display: inline-block; width: 10%;">
                <input type="radio" name="bsk_gfcv_supported_form_plugins[]" value="CF7" <?php echo $cf7_checked ?> /> Contact Forms 7
            </label>
            <?php } ?>
        </p>
        <div class="bsk-gfcv-tips-box" style="width: 75%;">
            <p>The free verison can only choose one form plugin to support. </p>
            <p><span style="font-weight: bold;">CREATOR</span> and above license for <a href="<?php echo BSK_GFCV_Dashboard::$_bsk_gfcv_pro_verison_url; ?>" target="_blank">Pro version</a> can support all above form plugins. </p>
        </div>
    </div>
    <p style="margin-top: 40px;">
        <input type="submit" class="button-primary" name="bsk_gfcv_save_settings" value="Save" />
        <input type="hidden" name="bsk_gfcv_action" value="save_general_settings" />
        <?php wp_nonce_field( 'bsk_gfcv_general_settings_save_oper_nonce', '_general_settings_nonce' ); ?>    
    </p>
    </form>
    <?php
    }

    function bsk_gfcv_save_general_settings_fun(){
        //check nonce field
		if ( !wp_verify_nonce( $_POST['_general_settings_nonce'], 'bsk_gfcv_general_settings_save_oper_nonce' ) ){
			wp_die( 'Security check!' );
			return;
		}

		$settings_data = get_option( BSK_GFCV_Dashboard::$_plugin_settings_option, false );
        if( !$settings_data || !is_array( $settings_data ) ){
            $settings_data = array();    
        }

        $settings_data['supported_form_plugins'] = isset( $_POST['bsk_gfcv_supported_form_plugins'] ) ? $_POST['bsk_gfcv_supported_form_plugins'] : array( 'GF' );
        if( !$settings_data['supported_form_plugins'] || 
            !is_array($settings_data['supported_form_plugins']) || 
            count($settings_data['supported_form_plugins']) < 1 ) {
            
            $settings_data['supported_form_plugins'] = array( 'GF' );
        }

        update_option( BSK_GFCV_Dashboard::$_plugin_settings_option, $settings_data );
    }

    function show_blocked_data_settings( $settings_data, $target_tab ){

        $settings_data = get_option( BSK_GFCV_Dashboard::$_plugin_settings_option, false );
        $save_blocked_entry = 'NO';
        $notify_blocked = 'NO';
        $notify_details = false;
        if( $settings_data && is_array( $settings_data ) && count( $settings_data ) > 0 ){
            if( isset( $settings_data['save_blocked_entry'] ) ){
                $save_blocked_entry = $settings_data['save_blocked_entry'];
            }
            if( isset( $settings_data['notify_blocked'] ) ){
                $notify_blocked = $settings_data['notify_blocked'];
            }
            if( isset( $settings_data['notify_details'] ) ){
                $notify_details = $settings_data['notify_details'];
            }
        }
        
        $action_url = admin_url( 'admin.php?page='.BSK_GFCV_Dashboard::$_bsk_gfcv_pages['settings']['slug'].'&target='.$target_tab );
		?>
        <div class="wrap">
            <div id="icon-edit" class="icon32"><br/></div>
            <h2>Settings</h3>
            <form action="<?php echo $action_url; ?>" method="POST" id="bsk_gfcv_settings_form_ID">
            <div>
                <?php
                $save_blocked_entry_yes_checked = $save_blocked_entry == 'YES' ? ' checked' : '';
                $save_blocked_entry_no_checked = $save_blocked_entry == 'NO' ? ' checked' : '';
                ?>
                <h3 style="margin-top: 40px;">Enable save blocked form data</h3>
                <p class="bsk-gfcv-tips-box">This feature only availabe in <a href="<?php echo BSK_GFCV_Dashboard::$_bsk_gfcv_pro_verison_url; ?>" target="_blank">Pro version</a>.</p>
                <p>
                    <label><input type="radio" name="bsk_gfcv_save_blocked_entry_enable" value="YES" <?php echo $save_blocked_entry_yes_checked; ?>/> Yes</label>
                    <label style="margin-left: 40px;"><input type="radio" name="bsk_gfcv_save_blocked_entry_enable" value="NO" <?php echo $save_blocked_entry_no_checked; ?>/> No</label>
                </p>
                <p>With this eanbled, the form data will be saved if a submitting blocked.</p>
            </div>
            <div class="bsk-gfcv-notify-administrtor-settings">
                <?php
                $notify_blocked_yes_checked = $notify_blocked == 'YES' ? ' checked' : '';
                $notify_blocked_no_checked = $notify_blocked == 'NO' ? ' checked' : '';
                ?>
                <h3 style="margin-top: 40px;">Enable notify administrators</h3>
                <p class="bsk-gfcv-tips-box">This feature only availabe in <a href="<?php echo BSK_GFCV_Dashboard::$_bsk_gfcv_pro_verison_url; ?>" target="_blank">Pro version</a>.</p>
                <p>Notify administrators( emails ) when form submitting blocked</p>
                <p>
                    <label><input type="radio" name="bsk_gfcv_notify_blocked_enable" value="YES" <?php echo $notify_blocked_yes_checked; ?> class="bsk-gfcv-notify-bloked-enable-radio" /> Yes</label>
                    <label style="margin-left: 40px;"><input type="radio" name="bsk_gfcv_notify_blocked_enable" value="NO" <?php echo $notify_blocked_no_checked; ?> class="bsk-gfcv-notify-bloked-enable-radio" /> No</label>
                </p>
                <?php
                
                $details_container_display = $notify_blocked == 'YES' ? 'block' : 'none';
                $send_to = get_option( 'admin_email' );
                $from_name = '';
                $from_email = '';
                $subject = 'New submission from {form_title} on {form_submission_date}';
                $message = "<p>Submitted from IP: {form_submission_IP}</p>
                
                <p>Submission data:{form_submission_data}</p>";
                
                if( $notify_details && is_array( $notify_details ) && count( $notify_details ) > 0 ){
                    if( isset( $notify_details['send_to'] ) && $notify_details['send_to'] ){
                        $send_to = $notify_details['send_to'];
                    }
                    if( isset( $notify_details['from_name'] ) && $notify_details['from_name'] ){
                        $from_name = $notify_details['from_name'];
                    }
                    if( isset( $notify_details['from_email'] ) && $notify_details['from_email'] ){
                        $from_email = $notify_details['from_email'];
                    }
                    if( isset( $notify_details['subject'] ) && $notify_details['subject'] ){
                        $subject = $notify_details['subject'];
                    }
                    if( isset( $notify_details['message'] ) && $notify_details['message'] ){
                        $message = $notify_details['message'];
                    }
                }
                ?>
                <div class="bsk-gfcv-administrator-mails-details-container" style="display: <?php echo $details_container_display; ?>;">
                    <p>
                        <label>Send To</label>
                        <span style="display: inline-block;">
                            <input type="text" class="bsk-gfcv-administrator-mails-input" name="bsk_gfcv_administrator_mails_send_to" value="<?php echo $send_to; ?>" />
                            <span style="font-style: italic; margin-left: 20px;">user comma( , ) to separate multiple mails</span>
                        </span>
                    </p>
                    <p>
                        <label>From Name</label>
                        <span>
                            <input type="text" class="bsk-gfcv-administrator-mails-input" name="bsk_gfcv_administrator_mails_from_name" value="<?php echo $from_name; ?>" />
                        </span>
                    </p>
                    <p>
                        <label>From Email</label>
                        <span>
                            <input type="text" class="bsk-gfcv-administrator-mails-input" name="bsk_gfcv_administrator_mails_from_email" value="<?php echo $from_email; ?>" />
                        </span>
                    </p>
                    <p>
                        <label>Subject</label>
                        <span>
                            <input type="text" class="bsk-gfcv-administrator-mails-input" name="bsk_gfcv_administrator_mails_subject" value="<?php echo $subject; ?>" />
                        </span>
                    </p>
                    <p>
                        <label>Message</label>
                        <span>
                        <?php
                            $settings = array( 
                                    'media_buttons' => false,
                                    'editor_height' => 150,
                                    'wpautop' => false,
                                    'default_editor' => 'tinymce',
                                );
                            wp_editor( $message, 'bsk_gfcv_administrator_mails_message', $settings );
                        ?>
                        </span>
                    </p>
                    <p>* {form_title} will be replaced by form title</p>
                    <p>* {form_submission_data} will be replaced by form submission data</p>
                    <p>* {form_submission_IP} will be replaced by client ip address</p>
                    <p>* {form_submission_date} will be replaced by submission date</p>
                </div>
            </div>
            <p style="margin-top: 40px;">
                <input type="submit" class="button-primary" name="bsk_gfcv_save_settings" value="Save" />
                <input type="hidden" name="bsk_gfcv_action" id="bsk_gfcv_action_ID" value="save_block_data_settings" />
                <?php wp_nonce_field( 'bsk_gfcv_block_data_settings_save_oper_nonce' ); ?>    
            </p>
            </form>
        </div>
        <?php
    }
    
    function bsk_gfcv_save_block_data_settings_fun(){
        //check nonce field
		if ( !wp_verify_nonce( $_POST['_wpnonce'], 'bsk_gfcv_block_data_settings_save_oper_nonce' ) ){
			wp_die( 'Security check!' );
			return;
		}

		$settings_data = get_option( BSK_GFCV_Dashboard::$_plugin_settings_option, false );
        if( !$settings_data || !is_array( $settings_data ) ){
            $settings_data = array();    
        }
        
        $settings_data['save_blocked_entry'] = sanitize_text_field( $_POST['bsk_gfcv_save_blocked_entry_enable'] );
        $settings_data['notify_blocked'] = sanitize_text_field( $_POST['bsk_gfcv_notify_blocked_enable'] );
        $notify_details = array();
        
        $post_end_to = trim( sanitize_text_field( $_POST['bsk_gfcv_administrator_mails_send_to'] ) );
        if( $post_end_to ){
            $send_to = $post_end_to;
            $send_to_array = explode( ',', $send_to );
            if( count($send_to_array) ){
                foreach( $send_to_array as $key => $email ){
                    if( !is_email( $email ) ){
                        unset( $send_to_array[$key] );
                    }
                }
                $notify_details['send_to'] = count($send_to_array) ? implode( ',', $send_to_array ) : '';
            }else{
                $notify_details['send_to'] = '';
            }
        }
        if( isset( $_POST['bsk_gfcv_administrator_mails_from_name'] ) ){
            $post_mails_from_name = trim( sanitize_text_field( $_POST['bsk_gfcv_administrator_mails_from_name'] ) );
            if( $post_mails_from_name ){
                $notify_details['from_name'] = $post_mails_from_name;
            }
        }
        
        if( isset( $_POST['bsk_gfcv_administrator_mails_from_email'] ) ){
            $post_mails_from_email = trim( sanitize_email( $_POST['bsk_gfcv_administrator_mails_from_email'] ) );
            if( $post_mails_from_email ){
                $notify_details['from_email'] = $post_mails_from_name;
            }
        }
        
        if( isset( $_POST['bsk_gfcv_administrator_mails_subject'] ) ){
            $post_subject = trim( sanitize_text_field( $_POST['bsk_gfcv_administrator_mails_subject'] ) );
            if( $post_subject ){
                $notify_details['subject'] = $post_subject;
            }
        }
        
        if( isset( $_POST['bsk_gfcv_administrator_mails_message'] ) ){
            $post_mails_message = wp_filter_post_kses( $_POST['bsk_gfcv_administrator_mails_message'] );
            if( $post_mails_message ){
                $notify_details['message'] = $post_mails_message;
            }
        }
        $settings_data['notify_details'] = $notify_details;

        update_option( BSK_GFCV_Dashboard::$_plugin_settings_option, $settings_data );
    }
}
