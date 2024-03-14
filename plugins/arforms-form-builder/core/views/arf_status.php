<?php
 	global $wpdb, $MdlDb, $ARFLiteMdlDb;
    $setting_tab = get_option( 'arforms_current_tab' );
    if( !empty( $_GET['current_tab'] ) && 'status_settings' == $_GET['current_tab'] ){
        $setting_tab = sanitize_text_field( $_GET['current_tab'] );
    }
    $setting_tab = ( ! isset( $setting_tab ) || empty( $setting_tab ) ) ? 'status_settings' : $setting_tab;
 
    $selected_list_id      = '';
?>
<div id="status_settings"  class="<?php echo ( 'status_settings' != $setting_tab ) ? 'display-none-cls' : 'display-blck-cls'; ?>">
<div class="wrap frm_entries_page arf_popup_frm_list_page">
	<div class="top_bar">
        <span class="h2"><?php //echo addslashes(esc_html__('ARForms Status', 'arforms-form-builder')); ?></span>
    </div>
    <?php echo str_replace( 'id="{arf_id}"', 'id="arf_full_width_loader"', ARFLITE_LOADER_ICON ); //phpcs:ignore ?>
    <div id="poststuff" class="metabox-holder">
    	<div id="post-body">
            <div class="inside" style="background-color:#ffffff;">
            	<div class="arf_form_popup_entries_wrapper">            	
            		<div id="arf_form_migration_status_entries">
                            <input type="hidden" name="arflite_validation_nonce" id="arflite_validation_nonce" value="<?php echo esc_attr( wp_create_nonce( 'arflite_wp_nonce' ) ); ?>" />
		                    <table cellpadding="0" cellspacing="0" border="0" class="display table_grid arf_migratio_status_list_table" id="example">
		                    	<thead>
                                    <tr>
                                        <th class="arf_schedular_hook" style="width:35%;"><?php echo addslashes(esc_html__('Hook', 'arforms-form-builder')); //phpcs:ignore ?></th>
                                        <th class="arf_schedular_status" style="width:25%;"><?php echo esc_html__('Staus', 'arforms-form-builder'); //phpcs:ignore ?></th>
                                        <th class="arf_schedular_date" style="width:10%;"><?php echo esc_html__('Scheduled Date', 'arforms-form-builder'); //phpcs:ignore ?></th>
                                        <th class="arf_schedular_created_date" style="width:10%;"><?php echo esc_html__('Completed Date', 'arforms-form-builder'); //phpcs:ignore ?></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    
                                </tbody>
		                    </table>

		                    <div class="clear"></div>

		                    <div class="footer_grid"></div>
		            	<!-- </form> -->
	            	</div>
            	</div>
            </div>
        </div>
    </div>
</div>
</div>
<?php do_action( 'arforms_quick_help_links' ); ?>