<?php
global $arfliteformhelper, $arflitemaincontroller, $arformsmain, $arforms_general_settings;
$actions = array(
	'-1'          => addslashes( esc_html__( 'Bulk Actions', 'arforms-form-builder' ) ),
	'bulk_delete' => addslashes( esc_html__( 'Delete', 'arforms-form-builder' ) ),
);
if ( isset( $_REQUEST['err'] ) && $_REQUEST['err'] == 1 ) {
	$arflite_errors[] = __( 'This form is already deleted.', 'arforms-form-builder' );
}

$default_hide = array(
	'0' => '',
	'1' => 'ID',
	'2' => 'Name',
	'3' => 'Entries',
	'4' => 'Shortcodes',
	'5' => 'Create Date',
	'6' => 'Action',
);

//$columns_list   = ( get_option( 'arfliteformcolumnlist' ) != '' ) ? maybe_unserialize( get_option( 'arfliteformcolumnlist' ) ) : array();
$columns_list   = ( get_option( 'arfformcolumnlist' ) != '' ) ? maybe_unserialize( get_option( 'arfformcolumnlist' ) ) : array();
$is_colmn_array = is_array( $columns_list );

$exclude = '';

if ( count( $columns_list ) > 0 && $columns_list != '' ) {
	foreach ( $default_hide as $key => $val ) {
		foreach ( $columns_list as $column ) {
			if ( $column == $val ) {
				$exclude .= $key . ', ';
			}
		}
	}
}
$exclude = rtrim( trim( $exclude ), ',' );

global $arfliteformcontroller;
?>

<?php echo str_replace( 'id="{arf_id}"', 'id="arf_full_width_loader"', ARFLITE_LOADER_ICON ); //phpcs:ignore ?>
<input type="hidden" id="arflite_wp_nonce" value="<?php echo esc_attr( wp_create_nonce( 'arflite_wp_nonce' ) ); ?>">
<input type="hidden" name="arflite_validation_nonce" id="arflite_validation_nonce" value="<?php echo esc_attr( wp_create_nonce( 'arflite_wp_nonce' ) ); ?>" />
<input type="hidden" id="arforms_wp_nonce" value="<?php echo esc_attr(wp_create_nonce( 'arforms_wp_nonce' )); ?>" />
<input type="hidden" id="arflite_form_list_page" />
<input type="hidden" id="arflite_form_list_exclude" value="<?php echo esc_html( $exclude ); ?>" />
<div class="wrap arfforms_page">
	<div class="top_bar">
		<span class="h2"><?php echo esc_html__( 'Manage Forms', 'arforms-form-builder' ); ?></span>
	</div>
	<?php $arforms_general_settings->arforms_render_pro_settings( 'arforms_pro_render_license_notice' ); ?>
	<div id="success_message" class="arf_success_message">
		<div class="message_descripiton">
			<div id="form_suc_message_des"></div>
			<div class="message_svg_icon">
				<svg class="arfheightwidth14"><path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M6.075,14.407l-5.852-5.84l1.616-1.613l4.394,4.385L17.181,0.411l1.616,1.613L6.392,14.407H6.075z"></path></svg>
			</div>
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

	<div id="poststuff" class="metabox-holder">
		<div id="post-body">
			<div class="wrap_content">
				<div class="arflite-clear-float"></div>
				<div class="arflite_bulk_action-frm-wrap">
					<form method="get" id="arfmainformnewlist" method="POST" class="data_grid_list" onsubmit="return arflite_apply_bulk_action_form();">
					<?php $arflite_page_chk = isset( $_GET['page'] ) ? esc_attr( sanitize_text_field($_GET['page']) ) : ''; ?>
						<input type="hidden" name="page" value="<?php echo esc_attr($arflite_page_chk); ?>" />
						<input type="hidden" name="arfaction" value="list" />
						<div id="arfmainformnewlist">
							<?php
								if( $arformsmain->arforms_is_pro_active() ){
									do_action('arfbeforelistingforms');
                                	require(VIEWS_PATH . '/shared_errors.php');
								}

							if ( current_user_can( 'arfeditforms' ) ) {
								?>
							<div class="add-newfrm-btn-container">
								<button class="rounded_button arf_btn_dark_blue" type="button" onclick="location.href = '<?php echo esc_url(admin_url( 'admin.php?page=ARForms&arfaction=new&isp=1' )); ?>';"><svg width="20px" height="20px"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M16.997,7.32v2h-7v6.969h-2V9.32h-7v-2h7V0.289h2V7.32H16.997z"/></svg>&nbsp;<?php echo esc_html__( 'Add New Form', 'arforms-form-builder' ); ?></button>
							</div>
								<?php
							}
							?>

							<div class="alignleft actions">
								<div class="arf_list_bulk_action_wrapper">
									<?php
										echo $arflitemaincontroller->arflite_selectpicker_dom( 'action1', 'arf_bulk_action_one', '', '', '-1', array(), $actions ); //phpcs:ignore
									?>
								</div>
								<input type="submit" id="doaction1" class="arf_bulk_action_btn rounded_button btn_green" value="<?php echo esc_html__( 'Apply', 'arforms-form-builder' ); ?>"/>
							</div>

							<table cellpadding="0" cellspacing="0" border="0" class="display table_grid arf_manage_grid_tbl arf_manage_frm_grid_tbl" id="example">
								<thead>
									<tr>
										<th class="center box arf_manage_grid_thw10">
											<div class="manage-frm-header-div">
												<div class="arf_custom_checkbox_div arfmarginl20">
													<div class="arf_custom_checkbox_wrapper">
														<input id="cb-select-all-1" type="checkbox" class="">
														<svg width="18px" height="18px">
															<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
															<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
														</svg>
													</div>
												</div>
												<label for="cb-select-all-1" class="cb-select-all"><span></span></label>
											</div>
										</th>
										<th class="id_column"><?php echo esc_html__( 'ID', 'arforms-form-builder' ); ?></th>
										<th class="form_title_column" ><?php echo esc_html__( 'Form Title', 'arforms-form-builder' ); ?></th>
										<th class="center entry_column"><?php echo esc_html__( 'Entries', 'arforms-form-builder' ); ?></th>
										<th class="arf_shortcode_width"><?php echo esc_html__( 'Shortcodes', 'arforms-form-builder' ); ?></th>
										<th class="arf_created_date_col"><?php echo esc_html__( 'Create Date', 'arforms-form-builder' ); ?></th>
										<th class="arf_col_action hide_action_button_row arf_action_cell"><?php echo esc_html__( 'Action', 'arforms-form-builder' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
										global $wpdb, $arflite_db_record, $ARFLiteMdlDb, $arfliteformcontroller;

									?>
								</tbody>
							</table>

							<div class="clear"></div>
							<input type="hidden" name="show_hide_columns" id="show_hide_columns" value="<?php echo esc_html__( 'Show / Hide columns', 'arforms-form-builder' ); ?>"/>
							<input type="hidden" name="search_grid" id="search_grid" value="<?php echo esc_html__( 'Search', 'arforms-form-builder' ); ?>"/>
							<input type="hidden" name="entries_grid" id="entries_grid" value="<?php echo esc_html__( 'entries', 'arforms-form-builder' ); ?>"/>
							<input type="hidden" name="show_grid" id="show_grid" value="<?php echo esc_html__( 'Show', 'arforms-form-builder' ); ?>"/>
							<input type="hidden" name="showing_grid" id="showing_grid" value="<?php echo esc_html__( 'Showing', 'arforms-form-builder' ); ?>"/>
							<input type="hidden" name="to_grid" id="to_grid" value="<?php echo esc_html__( 'to', 'arforms-form-builder' ); ?>"/>
							<input type="hidden" name="of_grid" id="of_grid" value="<?php echo esc_html__( 'of', 'arforms-form-builder' ); ?>"/>
							<input type="hidden" name="no_match_record_grid" id="no_match_record_grid" value="<?php echo esc_html__( 'No matching records found', 'arforms-form-builder' ); ?>"/>
							<input type="hidden" name="no_record_grid" id="no_record_grid" value="<?php echo esc_html__( 'No data available in table', 'arforms-form-builder' ); ?>"/>
							<input type="hidden" name="filter_grid" id="filter_grid" value="<?php echo esc_html__( 'filtered from', 'arforms-form-builder' ); ?>"/>
							<input type="hidden" name="totalwd_grid" id="totalwd_grid" value="<?php echo esc_html__( 'total', 'arforms-form-builder' ); ?>"/>

							<div class="alignleft actions2">
								<div class="arf_list_bulk_action_wrapper">
									<?php
										echo $arflitemaincontroller->arflite_selectpicker_dom( 'action3', 'arf_bulk_action_two', '', '', '-1', array(), $actions ); //phpcs:ignore
									?>
								</div>
								<input type="submit" id="doaction3" class="arf_bulk_action_btn rounded_button btn_green" value="<?php echo esc_html__( 'Apply', 'arforms-form-builder' ); ?>" />
							</div>
						</div>
						<div class="footer_grid"></div>
							<?php 
								if( $arformsmain->arforms_is_pro_active() ){	
									do_action('arfafterlistingforms'); 
								}
							?>
					</form>
				</div>
				<div id="arfupdateformbulkoption_div"></div>
			</div>
			<div class="arf_modal_overlay">
				<div id="delete_form_message" class="arfmodal arfdeletemodabox arf_popup_container arfdeletemodalboxnew">
					<input type="hidden" value="" id="delete_id" />
					<div class="arfdelete_modal_msg delete_confirm_message"><?php echo esc_html__( 'Are you sure you want to delete this entry?', 'arforms-form-builder' ); ?></div>
					<div class="arf_delete_modal_row delete_popup_footer">
						<input type="hidden" value="false" id="bulk_delete_flag"/>
						<button class="rounded_button add_button arf_delete_modal_left arfdelete_color_red" onclick="arflite_delete_bulk_form('true');">&nbsp;<?php echo esc_html__( 'Okay', 'arforms-form-builder' ); ?></button>&nbsp;&nbsp;<button class="arf_delete_modal_right rounded_button delete_button arfdelete_color_gray" onclick="jQuery('.arf_popup_container,.arf_modal_overlay').removeClass('arfactive');">&nbsp;<?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></button>
					</div>
				</div>
			</div>
			<div class="arf_modal_overlay arf_whole_screen">
				<div id="form_previewmodal" class="arf_popup_container arf_hide_overflow">
					<div class="arf_preview_model_header">
						<div class="arf_preview_model_header_icons">
							<div onclick="arflitechangedevice('computer');" title="<?php echo esc_html__( 'Computer View', 'arforms-form-builder' ); ?>" class="arfdevicesbg arfhelptip arf_preview_model_device_icon"><div id="arfcomputer" class="arfdevices arfactive"><svg width="75px" height="60px" viewBox="-16 -14 75 60"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M40.561,28.591H24.996v2.996h8.107c0.779,0,1.434,0.28,1.434,1.059  c0,0.779-0.655,0.935-1.434,0.935H9.951c-0.779,0-1.435-0.156-1.435-0.935c0-0.778,0.656-1.059,1.435-1.059h8.045v-2.996H2.452  c-0.779,0-1.435-0.656-1.435-1.435V2.086c0-0.779,0.656-1.434,1.435-1.434h38.109c0.778,0,1.434,0.655,1.434,1.434v25.071  C41.995,27.936,41.339,28.591,40.561,28.591z M22.996,31.587v-2.996h-3v2.996H22.996z M39.995,2.642H3.017v23.895h36.978V2.642z"/></svg></div></div>
							<div onclick="arflitechangedevice('tablet');" title="<?php echo esc_html__( 'Tablet View', 'arforms-form-builder' ); ?>" class="arfdevicesbg arfhelptip arf_preview_model_device_icon"><div id="arftablet" class="arfdevices"><svg width="40px" height="60px" viewBox="-6 -15 40 60"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M23.091,33.642H4.088c-1.657,0-3-1.021-3-2.28V2.816  c0-1.259,1.343-2.28,3-2.28h19.003c1.657,0,3,1.021,3,2.28v28.546C26.091,32.622,24.749,33.642,23.091,33.642z M4.955,31.685h17.262  c1.035,0,1.875-0.638,1.875-1.425v-4.694H3.08v4.694C3.08,31.047,3.92,31.685,4.955,31.685z M24.092,4.002  c0-0.787-0.84-1.425-1.875-1.425H4.955c-1.035,0-1.875,0.638-1.875,1.425v1.563h21.012V4.002z M3.08,7.566v16h21.012v-16H3.08z   M13.618,26.551c1.09,0,1.974,0.896,1.974,2s-0.884,2-1.974,2c-1.09,0-1.974-0.896-1.974-2S12.527,26.551,13.618,26.551zz"/></svg></div></div>
							<div onclick="arflitechangedevice('mobile');" title="<?php echo esc_html__( 'Mobile View', 'arforms-form-builder' ); ?>" class="arfdevicesbg arfhelptip arf_preview_model_device_icon"><div id="arfmobile" class="arfdevices"><svg width="45px" height="60px" viewBox="-12 -15 45 60"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M17.894,33.726H3.452c-1.259,0-2.28-1.021-2.28-2.28V2.899  c0-1.259,1.021-2.28,2.28-2.28h14.442c1.259,0,2.28,1.021,2.28,2.28v28.546C20.174,32.705,19.153,33.726,17.894,33.726z   M18.18,4.086c0-0.787-0.638-1.425-1.425-1.425H4.585c-0.787,0-1.425,0.638-1.425,1.425v26.258c0,0.787,0.638,1.425,1.425,1.425  h12.169c0.787,0,1.425-0.638,1.425-1.425V4.086z M13.787,6.656H7.568c-0.252,0-0.456-0.43-0.456-0.959s0.204-0.959,0.456-0.959  h6.218c0.251,0,0.456,0.429,0.456,0.959S14.038,6.656,13.787,6.656z M10.693,25.635c1.104,0,2,0.896,2,2c0,1.105-0.895,2-2,2  c-1.105,0-2-0.895-2-2C8.693,26.53,9.588,25.635,10.693,25.635z"/></svg></div></div>
						</div>
						<div class="arf_popup_header_close_button arf_preview_close" data-dismiss="arfmodal">
							<svg width="16px" height="16px" viewBox="0 0 12 12"><path fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></svg>
						</div>
					</div>
					<div class="arfmodal-body">
						<div class="iframe_loader" align="center"><?php echo ARFLITE_LOADER_ICON; //phpcs:ignore ?></div>
						<iframe id="arfdevicepreview" name="arf_preview_frame" src="" frameborder="0" height="100%" width="100%"></iframe>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="arflite-clear-float"></div>
</div>

 <?php 
	if(isset($_GET['upgrade-to-pro']) && $_GET['upgrade-to-pro'] == 'yes'){
		$arf_current_date = current_time('timestamp', true );
		$arf_sale_start_time = '1700503200';
		$arf_sale_end_time = '1701561600';

		if( $arf_current_date >= $arf_sale_start_time && $arf_current_date <= $arf_sale_end_time ){
			?>
				<div id="myModal" class="modal" >
					<div class="arflite_upgred_to_premium_background"  onclick=upgrade_to_pro_main_page()>
						<button class="arflite_upgred_to_premium_button"><b>UPGRADE NOW : $19 </b><del class="discount_text"> $39</del></button>
					</div>
				</div>
			<?php
		} else {
			?>
			<a href="https://codecanyon.net/item/arforms-wordpress-form-builder-plugin/6023165" target="_blank" id="upgrade_link" style="visibli"></a>
			<?php
		}
	}
 ?>

<?php do_action( 'arforms_quick_help_links' ); ?>