<?php
    global  $arfdebuglog,$arf_view_log_selector,$arformsmain;
    $email_notification = $arformsmain->arforms_get_settings( 'email_notification', 'debug_log_settings' );

    $arflite_view_debug_log_nonce= wp_create_nonce('arflite_view_debug_log_nonce');
    
    $setting_tab = get_option( 'arforms_current_tab' );
    $setting_tab = ( ! isset( $setting_tab ) || empty( $setting_tab ) ) ? 'general_settings' : $setting_tab;

    $selected_list_id      = '';

?>
<div id="logs_settings"  class="<?php echo ( 'logs_settings' != $setting_tab ) ? 'display-none-cls' : 'display-blck-cls'; ?>">
<input type="hidden" name="arf_validation_nonce" id="arf_validation_nonce" value="<?php echo esc_attr( wp_create_nonce( 'arf_wp_nonce' ) ); ?>" />
<div class="wrap arfforms_page arf_debug_log">
    <div id="poststuff" class="metabox-holder">
        <div id="post-body">
            <div class="wrap_content">
                <div class="arflite-clear-float"></div>
                <div class="arf_loader_icon_box" style="display:none">
                    <div class="arf-spinner arf-skeleton arf-grid-loader"></div>
                </div>
                <div class="arf_debug_log_parent_wrapper">
                    <div class="arf-debug-log-sub-heading">
                        <span class="lbltitle"><?php echo esc_html__( 'Email Debug Log', 'arforms-form-builder' ); ?></span>
                    </div>
                    <div class="arf-inner-heading">
                        <span class="lblsubtitle lblnotetitle "><?php echo esc_html__( 'Email Notification Debug Log', 'arforms-form-builder' ); ?></span>
                        <div class="arf_js_switch_wrapper arf-checkbox">                    
                            <input type="checkbox" class="js-switch" name="email_notification_log" id="email_notification_log" value="1"  <?php checked($email_notification, 1);?> onchange="arforms_hide_show_debug_settings(this.checked, 'email_notifications');" />                                
                            <span class="arf_js_switch"></span>
                        </div>
                        <div class="arforms_debug_log_setting_wrapper" data-type="email_notifications" style="<?php echo ( 1 == $email_notification ) ? 'display:table-row;' : 'display:none;'; ?>">
                            <div class="arf-log-button-div">
                                <button type="button" class="arf-debug-log-button arf-view-img arforms_view_debug_logs" id="arf_email_popup_view_log" data-log-type="email_notification" data-token="<?php echo $arflite_view_debug_log_nonce;//phpcs:ignore ?>" ><?php esc_html_e('View Logs', 'arforms-form-builder' ); ?></button>
                                <?php $arflite_download_debug_log_nonce = wp_create_nonce('arflite_download_debug_log_nonce'); ?>
                                <button type="button" class="arf-debug-log-button arf-download-img" id="arf_email_popup_download_log" onclick="return Show_downloadpopup('email_notification','<?php echo $arflite_download_debug_log_nonce; ?>')";><?php esc_html_e('Download Logs', 'arforms-form-builder'); ?></button> <?php //phpcs:ignore ?>
                                <button type="button" class="arf-debug-log-button arf-clear-img" id="arf_email_popup_clear_log" onclick="return Show_clearpopup('email_notification')";><?php esc_html_e('Clear Logs', 'arforms-form-builder'); ?></button> <?php //phpcs:ignore ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    $arfdebuglog->arforms_render_pro_debug_log();
                ?>
                <?php $arf_save_debug_log_nonce = wp_create_nonce('arf_save_debug_log_nonce'); ?>
                <input type="hidden" name="arf_save_debug_log_nonce" id="arf_save_debug_log_nonce" value="<?php echo $arf_save_debug_log_nonce; //phpcs:ignore ?>"> 
            </div>
        </div>
    </div>
</div>


<div class="arf_loader_icon_box" style="display:none">
    <div class="arf-spinner arf-skeleton arf-grid-loader"></div>
</div>

 <div class="arf_dialog_wrapper" id='close_view_popup' style="display:none;">
    <div class="arf-view-debug-log-popup">
        <div class="arf-view-popup-heading">
            <?php echo esc_html__( 'Debug Logs', 'arforms-form-builder' ); ?>
            <div id="nav_style_close" class="arf-view-debug-log-close">
                <img src="<?php echo esc_url( ARFLITEIMAGESURL ); //phpcs:ignore ?>/analytic_close_icon.png" >
            </div>
        </div>
        <div class="arf-view-content">
            <div id="arfviewformloader"><?php echo ARFLITE_LOADER_ICON; //phpcs:ignore?></div>
            <div id="display_message"></div>
        </div>         
    </div>
</div>

<div class="arf_dialog_wrapper1" id='close_downlaod_popup' style="display:none;">
    <div class="arf-download-debug-log-popup">
        <div class="arf-view-popup-heading">
            <?php echo esc_html__( 'Download Logs', 'arforms-form-builder' ); ?>
            <div id="nav_style_close" class="arf-download-debug-log-close"><img src="<?php echo esc_url( ARFLITEIMAGESURL );?>/analytic_close_icon.png"></div>
        </div>

        <div class="arf-download-debug-log-main">
            <div class="arf-download-debug-log-text"><?php esc_html_e('Select log duration', 'arforms-form-builder' ); ?></div>
            <div class="arf-download-debug-log-select-option" name='dropdown' onChange="Show_DatePicker();">
            <?php 
                $actions = array(
                    '1'        => addslashes( esc_html__( 'Last 1 Days', 'arforms-form-builder' ) ),
                    '3'        => addslashes( esc_html__( 'Last 3 Days', 'arforms-form-builder' ) ),
                    '7'        => addslashes( esc_html__( 'Last 7 Days', 'arforms-form-builder' ) ),
                    '14'       => addslashes( esc_html__( 'Last 2 Weeks', 'arforms-form-builder' ) ),
                    '30'       => addslashes( esc_html__( 'Last Month', 'arforms-form-builder' ) ),
                    'all'      => addslashes( esc_html__( 'All', 'arforms-form-builder' ) ),
                    'custom'   => addslashes( esc_html__( 'Custom Dates', 'arforms-form-builder' ) ),
                );?>
            
                <div class="arf_list_bulk_action_wrapper">
                    <?php 
                    global $arflitemaincontroller;
                        echo $arflitemaincontroller->arflite_selectpicker_dom( 'action1', 'arf-date-dropdown', '', '', '7', array(), $actions ); //phpcs:ignore
                    ?>
                </div>
            </div>
            <div id="other-div" style="display:none;margin-bottom:10px;margin-left:20px;">
            <?php
            if ( is_rtl() ) {
                $sel_frm_date_wrap = 'float:right;text-align:right;';
                $sel_frm_sel_date  = 'float:right;margin-left:20px;';
                $sel_frm_button    = 'float:right;';
            } else {
                $sel_frm_date_wrap = 'float:left;text-align:left;margin-left: 12px;';
                $sel_frm_sel_date  = 'float:left;margin-right:20px;';
                $sel_frm_button    = 'float:left;';
            }
            ?>
            <div style="position:relative; <?php echo esc_attr( $sel_frm_date_wrap ); ?>">
                <div style="position:relative;<?php echo esc_attr( $sel_frm_sel_date ); ?>">
                    <div class="arfentrytitle" style='position:absolute;top:10px;margin:0;left:0;'><?php echo esc_html__( 'From', 'arforms-form-builder' ); ?></div><input type="text" class="txtmodal1" value="" id="datepicker_from2" name="datepicker_from2" style="max-width:180px;height:35px;vertical-align:middle;position:relative;top:40px;" autocomplete="off"/>
                </div>
                
                <div style="position:relative; <?php echo esc_attr( $sel_frm_sel_date ); ?>">
                    <div class="arfentrytitle" style='position: absolute;top:10px;left:0px;'><?php echo esc_html__( 'To', 'arforms-form-builder' ); ?></div>
                    <input type="text" class="txtmodal1" value="" id="datepicker_to2" name="datepicker_to2" style="max-width:180px;height:35px;vertical-align:middle;position: relative;top:40px;left:10px;" autocomplete="off"/>
                </div>
            </div>
        </div>
                
         <div class="arf-download-log-action">
            <button class="arf-download-button" type="button" id="arf-email-download-log" ><?php esc_html_e('Download', 'arforms-form-builder'); ?></button>
        </div>
    </div>   
           
</div>  

</div>  

<div class="arf_dialog_wrapper2" id='close_clear_popup' style="display:none;">
    <div class="arf-clear-debug-log-popup">       
        <p class="arf-clear-debug-log-main"><?php esc_html_e( 'Are you sure you want to clear debug logs?', 'arforms-form-builder' ); ?></p>
        <div class="arf-clear-debug-log-action">
            <button class="arf-clear-debug-log-delete-button" type="button" data-type="" id='arf-email-clear-log'><?php echo esc_html_e( 'Delete', 'arforms-form-builder' ); ?></button>
            <button class="arf-clear-debug-log-cancel-button" type="button" data-type="" id="nav_style_close"><?php echo esc_html_e( 'Cancel', 'arforms-form-builder' ); ?></button>
        </div>
    </div>
</div>

<div id="display_message"></div>
<div class="success_message" id="success-message-save-log"> 
    <div class="message_descripiton">
        <div class="arffloatmargin">
            <?php esc_html_e( 'Debug Log Setting Saved Successfully.', 'arforms-form-builder' ); ?>
        </div>
    </div>
</div>

<div class="success_message" id="success-message-clear-log"> 
    <div class="arffloatmargin">
        <?php esc_html_e( 'Debug Log Cleared Successfully.', 'arforms-form-builder' ); ?>
    </div>
</div>

<div class="success_message" id="success-message-download-log"> 
    <div class="arffloatmargin">
        <?php esc_html_e( 'Debug Log Download Successfully.', 'arforms-form-builder' ); ?>
    </div>
</div>
<div id="error_message" class="arf_error_message">
		<div class="message_descripiton">
			<div id="form_error_message_des"></div>
			<div class="message_svg_icon">
				<svg class="arfheightwidth14"><path fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></svg>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" data-cfasync="false">
	jQuery(document).ready(function () {
		<?php
			$wp_format_date = get_option( 'date_format' );
		if ( $wp_format_date == 'F j, Y' ) {
			$date_format_new = 'MMMM D, YYYY';
			$start_date_new  = 'January 01, 1970';
			$end_date_new    = 'December 31, 2050';
		} elseif ( $wp_format_date == 'Y-m-d' ) {
			$date_format_new = 'YYYY-MM-DD';
			$start_date_new  = '1970-1-1';
			$end_date_new    = '2050-12-1';
		} elseif ( $wp_format_date == 'm/d/Y' ) {
			$date_format_new = 'MM/DD/YYYY';
			$start_date_new  = '01/01/1970';
			$end_date_new    = '12/31/2050';
		} elseif ( $wp_format_date == 'd/m/Y' ) {
			$date_format_new = 'DD/MM/YYYY';
			$start_date_new  = '01/01/1970';
			$end_date_new    = '31/12/2050';
		} elseif ( $wp_format_date == 'Y/m/d' ) {
			$date_format_new = 'DD/MM/YYYY';
			$start_date_new  = '01/01/1970';
			$end_date_new    = '31/12/2050';
		} else {
			$date_format_new = 'MM/DD/YYYY';
			$start_date_new  = '01/01/1970';
			$end_date_new    = '12/31/2050';
		}
		?>
        jQuery("#datepicker_from2").datetimepicker({
            useCurrent: true,
            format: '<?php echo esc_html($date_format_new); ?>',
            locale: '',
            minDate: moment('<?php echo esc_html($start_date_new); ?>','<?php echo esc_html($date_format_new); ?>'),
            maxDate: moment('<?php echo esc_html($end_date_new); ?>', '<?php echo esc_html($date_format_new); ?>')
        });
        
        jQuery("#datepicker_to2").datetimepicker({
            useCurrent: false,
            format: '<?php echo esc_html($date_format_new); ?>',
            locale: '',
            minDate: moment('<?php echo esc_html($start_date_new); ?>','<?php echo esc_html($date_format_new); ?>'),
            maxDate: moment('<?php echo esc_html($end_date_new); ?>', '<?php echo esc_html($date_format_new); ?>')
        });
        
        jQuery("#datepicker_from2").on("dp.change", function (e) {
            jQuery("#datepicker_to2").data("DateTimePicker").minDate(e.date);
        });
        jQuery("#datepicker_to2").on("dp.change", function (e) {
            jQuery("#datepicker_from2").data("DateTimePicker").maxDate(e.date);
        });
	});

</script>
<?php
do_action( 'arforms_quick_help_links' ,'arforms_log_page');