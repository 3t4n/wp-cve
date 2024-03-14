<?php
	global $current_user, $arfliteformhelper,$arflite_installed_field_types,$arfliterecordcontroller,$arfliteformcontroller, $arformsmain, $arforms_general_settings;
	$arf_import_export_useragent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
	$browser_info = $arfliterecordcontroller->arflitegetBrowser( $arf_import_export_useragent );
	$allowed_html = arflite_retrieve_attrs_for_wp_kses();
	@ini_set( 'max_execution_time', 0 );

	global $arfliteformcontroller;
?>

<div class="wrap arfforms_page arf_imortexport arf_imortexport_page_wrap">

	<div class="top_bar">
		<span class="h2"><?php echo esc_html__( 'Import / Export Forms', 'arforms-form-builder' ); ?></span>
	</div>
	<?php $arforms_general_settings->arforms_render_pro_settings( 'arforms_pro_render_license_notice' ); ?>
	<div id="poststuff" class="metabox-holder">
		<div id="post-body">
			<div class="inside">
				<div class="frm_settings_form ">
				<input type="hidden" name="arforms_validation_nonce" id="arforms_validation_nonce" value="<?php echo esc_attr( wp_create_nonce( 'arforms_wp_nonce' ) ); ?>" />
					<?php
						if ( isset( $_REQUEST['arf_import_btn'] ) && current_user_can( 'arfchangesettings' ) ) {

							if( $arformsmain->arforms_is_pro_active() ){

								if( class_exists('arforms_pro_import_export') ){

									global $arforms_pro_import_export;
									$arforms_pro_import_export->arforms_pro_import_form_data();
								}
							} else {
								
								global $arforms_import_export_settings;
								$arforms_import_export_settings->arforms_import_form_data();
							}
						}
					?>
					<div class="arflite-clear-float"></div>
					<div class="modal-body arfexportformwrap">

						<div class="opt_export_div">
							<label class="opt_export_lbl"><span></span>
								<span class="lbltitle"><?php echo esc_html__( 'Export Form(s)', 'arforms-form-builder' ); ?>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;<?php echo esc_html__( 'Entries', 'arforms-form-builder' ); ?></span>
							</label>
						</div>

						<div class="exportformseprater"></div>

						<div class="export_opt_part" id="export_opt_part">
							<?php $plugin_url_list = plugin_dir_url( __FILE__ ); ?>

							<form id="exportForm" onSubmit="return arforms_check_import_form_selected();" method="post">
								<?php 
									if( $arformsmain->arforms_is_pro_active() ){  ?>

										<input type="hidden" value="<?php echo site_url() . '/index.php?plugin=ARForms'; //phpcs:ignore ?>" name="arfcripturl_cus" id="arfcripturl_cus" />
									<?php } else { ?>

										<input type="hidden" value="<?php echo esc_url(site_url() . '/index.php?plugin=ARFormslite'); ?>" name="arfcripturl_cus" id="arfcripturl_cus" />
								<?php } ?>
								<div id="export_forms" class="export_forms" >
									<div class="export_options" id="export_options">
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div" >
												<div class="arf_custom_radio_wrapper">
													<input type="radio" class="arf_submit_action arf_custom_radio" name="arf_opt_export" id="arf_opt_export_form" value="arf_opt_export_form" checked="checked" />
													<svg width="18px" height="18px">
													<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
													<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="arf_opt_export_form"><?php echo esc_html__( 'Form(s) Only', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div" >
												<div class="arf_custom_radio_wrapper">
													<input type="radio" class="arf_submit_action arf_custom_radio" name="arf_opt_export" id="arf_opt_export_entries" value="arf_opt_export_entries" />
													<svg width="18px" height="18px">
													<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
													<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="arf_opt_export_entries"><?php echo esc_html__( 'Entries Only', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
										<div class="arf_radio_wrapper" style="<?php echo ( ! is_rtl() ) ? 'width:60%;' : ''; ?>">
											<div class="arf_custom_radio_div" >
												<div class="arf_custom_radio_wrapper">
													<input type="radio" class="arf_submit_action arf_custom_radio" name="arf_opt_export" id="arf_opt_export_both" value="arf_opt_export_both" />
													<svg width="18px" height="18px">
													<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
													<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="arf_opt_export_both"><?php echo esc_html__( 'Forms + Entries', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
									</div>

									<table class="form-table">
										
										<tr>
											<td colspan="2">
												<span class="lblsubtitle selection_msg lblnotetitle notelitespan">
													<?php echo esc_html__( 'Please Select Form', 'arforms-form-builder' ); ?>
												</span>

												<div class="arf_importselform">
													<?php 
														if( $arformsmain->arforms_is_pro_active() ){

															global $arformhelper;
															$arformhelper->forms_dropdown_new('frm_add_form_id', '', 'Select form', '', '', 'mutliple', 1, 1, 'arf_import_export_dropdown');

														} else {
															$arfliteformhelper->arflite_forms_dropdown_new( 'frm_add_form_id', '', 'Select form', '', '', 'mutliple', 1, 1, 'arf_import_export_dropdown' );
														}
													?>
												</div>
												<div id="arf_xml_select_form_error"><?php echo esc_html__( 'Please Select Form', 'arforms-form-builder' ); ?></div>
											</td>
										</tr>

									<tr class="arf_display_form_import_export_date" style="display:none;" >
										<td>
										<span class="lblsubtitle lblnotetitle" style="<?php echo ( is_rtl() ) ? 'width: 80px;margin-right: 0px;float:right;margin-left: 42px;' : 'width: 80px;float:left;margin-left: -56px;margin-top:5px;'; ?>">
												<?php echo addslashes( esc_html__( 'Select Date', 'arforms-form-builder' ) ); //phpcs:ignore ?> (<?php echo addslashes( esc_html__( 'optional', 'arforms-form-builder' ) ); //phpcs:ignore ?>)
											</span>
											<?php
											if ( is_rtl() ) {
												$sel_frm_date_wrap = 'float:right;text-align:right;';
												$sel_frm_sel_date  = 'float:right;';
												$sel_frm_button    = 'float:right;';
											} else {
												$sel_frm_date_wrap = 'float:left;text-align:left;margin-left: 12px;';
												$sel_frm_sel_date  = 'float:left;';
												$sel_frm_button    = 'float:left;';
											}
											?>
											<div style="position:relative; <?php echo esc_attr( $sel_frm_date_wrap ); ?>">
												<div style="<?php echo esc_attr( $sel_frm_sel_date ); ?>"><div class="arfentrytitle" style='margin-left:0;'><?php echo esc_html__( 'From', 'arforms-form-builder' ); ?></div><input type="text" class="txtmodal1" value="" id="datepicker_from2" name="datepicker_from2" style="width:120px;height:35px;vertical-align:middle;" autocomplete="off"/></div> <div class="arfentrytitle"><?php echo esc_html__( 'To', 'arforms-form-builder' ); ?></div>&nbsp;&nbsp;<div style="<?php echo esc_attr( $sel_frm_sel_date ); ?>"><input type="text" class="txtmodal1" value="" id="datepicker_to2" name="datepicker_to2" style="vertical-align:middle; width:120px;height:35px;" autocomplete="off"/></div>
												<div style=" <?php echo esc_attr( $sel_frm_button ); ?>">
													<div class="arf_form_entry_left">&nbsp;</div>
												</div>
											</div>
											<div id="arf_xml_select_date_error" ><?php echo esc_html__( 'Entry not found for selected time period', 'arforms-form-builder' ); ?></div>
										</td>
									</tr>



										<tr class="display_form_entry_separator display-none-cls">
											<td colspan="2">
												<span class="lblsubtitle arfcsv-file-seprater">
													<?php echo esc_html__( 'CSV File Separator', 'arforms-form-builder' ); ?>
												</span>

												<div class="arf_radio_wrapper">
													<div class="arf_custom_radio_div" >
														<div class="arf_custom_radio_wrapper">
															<input type="radio" name="arfexportentryseparator" id="arf_comma_separate" class="arf_submit_action arf_custom_radio" value="arf_comma"  <?php checked( get_option( 'arf_form_entry_separator' ), 'arf_comma' ); ?>/>
															<svg width="18px" height="18px">
															<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
															<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
															</svg>
														</div>
													</div>
													<span>
														<label for="arf_comma_separate"><?php echo esc_html__( 'Comma ( , )', 'arforms-form-builder' ); ?></label>
													</span>
												</div>
												<div class="arf_radio_wrapper">
													<div class="arf_custom_radio_div" >
														<div class="arf_custom_radio_wrapper">
															<input type="radio" name="arfexportentryseparator" id="arf_semicolon_separate" class="arf_submit_action arf_custom_radio" value="arf_semicolon" <?php checked( get_option( 'arf_form_entry_separator' ), 'arf_semicolon' ); ?> />
															<svg width="18px" height="18px">
															<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
															<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
															</svg>
														</div>
													</div>
													<span>
														<label for="arf_semicolon_separate"><?php echo esc_html__( 'Semicolon ( ; )', 'arforms-form-builder' ); ?></label>
													</span>
												</div>

												<div class="arf_radio_wrapper">
													<div class="arf_custom_radio_div" >
														<div class="arf_custom_radio_wrapper">
															<input type="radio" name="arfexportentryseparator" id="arf_pipe_separate" class="arf_submit_action arf_custom_radio" value="arf_pipe" <?php checked( get_option( 'arf_form_entry_separator' ), 'arf_pipe' ); ?>/>
															<svg width="18px" height="18px">
															<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
															<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON;  //phpcs:ignore ?>
															</svg>
														</div>
													</div>
													<span>
														<label for="arf_pipe_separate"><?php echo esc_html__( 'Pipe ( | )', 'arforms-form-builder' ); ?></label>
													</span>
												</div>
											</td>

										</tr>
										<br>
										<tr>
											<td colspan="2" class="export-btn-td">
												<input type="hidden" id="arf_export_action" name="s_action" value="arf_opt_export_form">
												<input type="hidden" name="_wpnonce_arforms" id="_wpnonce_arforms" value="<?php echo esc_attr( wp_create_nonce( 'arforms_wp_nonce' ) ); ?>" />
												<input name="export_button" type="submit" id="export_button" class="rounded_button arf_btn_dark_blue arfexportbtn" value="<?php echo esc_html__( 'Export', 'arforms-form-builder' ); ?>">
											</td>
										</tr>
									</table>
							</form>

						</div>
						<br />
						<div class="import-export-seprater"></div>
						<br />
						<div class="arfimport-form-title">
							<div class="opt_import_div">
								<label class="arfimport-form-lbl"><span></span>
									<span class="lbltitle"><?php echo esc_html__( 'Import Form(s)', 'arforms-form-builder' ); ?></span>
								</label>
								<br /><br />
							</div>

						<div class="import_opt_part" id="import_opt_part">
							<form  action="" method="post" enctype="multipart/form-data" >
								<table class="form-table">
									<tr>
										<td colspan="2"><span class="lblsubtitle arfexporttitlespan"><?php echo esc_html__( 'Exported File Content', 'arforms-form-builder' ); ?></span>

											<textarea id="arf_import_textarea" cols="100" rows="15" name="arf_import_textarea" class="txtmultimodal1 text_area_import_export_page export-form-textarea"></textarea>

											 <div class="arf_tooltip_main" ><img src="<?php echo esc_url(ARFLITEIMAGESURL); ?>/tooltips-icon.png" alt="?" class="arfhelptip tipso_style arfexport-form-note" title="<?php echo esc_html__( 'Please open your exported file, copy entire content & paste it here.', 'arforms-form-builder' ); ?>" data-tipso="<?php echo esc_html__( 'Please open your exported file, copy entire content & paste it here.', 'arforms-form-builder' ); ?>"/></div>
											 <div class="arf_import_textarea_error_wrapper">
												<span id="arf_import_content_null" class="arf_importerr"><?php echo esc_html__( 'Please enter content', 'arforms-form-builder' ); ?></span>
											 </div>
										</td>
									</tr>
									<tr class="blank-tr">
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td class="arfimportbtntd">
											<input type="hidden" name="arf_xml_file_name" id="arf_xml_file_name" value="" /><input type="hidden" name="arf_import_disable" id="arf_import_disable" value="1" />
											<input type="hidden" name="arf_import_form_nonce" value="<?php echo esc_attr(wp_create_nonce( 'arf_import_form' )); ?>" />
											<input type="submit" id="arf_import_btn" name="arf_import_btn"  class="rounded_button arf_btn_dark_blue arf_importbtn" value="<?php echo esc_html__( 'Import', 'arforms-form-builder' ); ?>">&nbsp;&nbsp;<span id="import_loader"><img src="<?php echo esc_url(ARFLITEURL . '/images/loading_299_1.gif'); ?>" height="15" /></span>

											</td>
										</tr>
									</table>
								</form>
							</div>
						</div>
						<br />
					</div>
				</div>
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

<?php do_action( 'arforms_quick_help_links' ); ?>