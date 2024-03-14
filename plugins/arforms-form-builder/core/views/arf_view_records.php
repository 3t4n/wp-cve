<?php
global $arflitemainhelper, $arfliteformhelper, $arfliterecordhelper, $arfliterecordcontroller, $arflitemaincontroller, $arfliteversion, $arflitefieldhelper, $arformsmain, $arforms_general_settings;
$arf_edit_select_array = array();

$tabview = ( isset( $_GET['tabview'] ) && ! empty( $_GET['tabview'] ) ) ? sanitize_text_field( $_GET['tabview'] ) : '';

$_GET['form'] = isset( $_GET['form'] ) ? intval( $_GET['form'] ) : -1;

$form_id = intval( $_GET['form'] );

function arflitegetBrowser( $user_agent ) {
	$u_agent  = $user_agent;
	$bname    = 'Unknown';
	$platform = 'Unknown';
	$version  = '';

	if ( preg_match( '/linux/i', $u_agent ) ) {
		$platform = 'linux';
	} elseif ( preg_match( '/macintosh|mac os x/i', $u_agent ) ) {
		$platform = 'mac';
	} elseif ( preg_match( '/windows|win32/i', $u_agent ) ) {
		$platform = 'windows';
	}

	if ( preg_match( '/MSIE/i', $u_agent ) && ! preg_match( '/Opera/i', $u_agent ) ) {
		$bname = 'Internet Explorer';
		$ub    = 'MSIE';
	} elseif ( preg_match( '/Trident/i', $u_agent ) ) {
		$bname = 'Internet Explorer';
		$ub    = 'rv';
	} elseif ( preg_match( '/OPR/i', $u_agent ) ) {
		$bname = 'Opera';
		$ub    = 'OPR';
	} elseif ( preg_match( '/Firefox/i', $u_agent ) ) {
		$bname = 'Mozilla Firefox';
		$ub    = 'Firefox';
	} elseif ( preg_match( '/Edge/i', $u_agent ) ) {
		$bname = 'Edge';
		$ub    = 'Edge';
	} elseif ( preg_match( '/Chrome/i', $u_agent ) ) {
		$bname = 'Google Chrome';
		$ub    = 'Chrome';
	} elseif ( preg_match( '/Safari/i', $u_agent ) ) {
		$bname = 'Apple Safari';
		$ub    = 'Safari';
	} elseif ( preg_match( '/Opera/i', $u_agent ) ) {
		$bname = 'Opera';
		$ub    = 'Opera';
	} elseif ( preg_match( '/Netscape/i', $u_agent ) ) {
		$bname = 'Netscape';
		$ub    = 'Netscape';
	}

	$known = array( 'Version', $ub, 'other' );

	$pattern = '#(?<browser>' . join( '|', $known ) .
			')[/ |:]+(?<version>[0-9.|a-zA-Z.]*)#';
	if ( ! preg_match_all( $pattern, $u_agent, $matches ) ) {

	}

	$i = count( $matches['browser'] );
	if ( $i != 1 ) {

		if ( strripos( $u_agent, 'Version' ) < strripos( $u_agent, $ub ) ) {
			$version = $matches['version'][0];
		} else {
			$version = $matches['version'][1];
		}
	} else {
		$version = $matches['version'][0];
	}

	if ( $version == null || $version == '' ) {
		$version = '?';
	}

	return array(
		'userAgent' => $u_agent,
		'name'      => $bname,
		'version'   => $version,
		'platform'  => $platform,
		'pattern'   => $pattern,
	);
}

$actions = array(
	'-1'          => addslashes( esc_html__( 'Bulk Actions', 'arforms-form-builder' ) ),
	'bulk_delete' => addslashes( esc_html__( 'Delete', 'arforms-form-builder' ) ),
);

if ( current_user_can( 'arfchangesettings' ) ) {
	$actions['bulk_csv'] = addslashes( esc_html__( 'Export to CSV', 'arforms-form-builder' ) );
}

global $arfliteformcontroller;

?>

<?php
if ( isset( $form->id ) && $form->id == '-1' ) {
	$form_cols = array();
	$items     = array();
}

$exclude_from_sorting       = array( 0 );
$exclude_file_types_sorting = array( 'checkbox', 'image' );

if ( isset( $form->id ) && ( $form->id != '-1' || $form->id != '' ) ) {

	if( $arformsmain->arforms_is_pro_active() ){
		$form_cols = apply_filters('arfpredisplayformcols', $form_cols, $form->id);
		$items = apply_filters('arfpredisplaycolsitems', $items, $form->id);
		$exclude_file_types_sorting = array('file','password','checkbox','arf_multiselect','image','signature', 'arf_matrix');
	}

	$action_no = 0;

	$default_hide = array(
		'1' => 'ID',
	);

	if ( count( $form_cols ) > 0 ) {

		for ( $i = 2; 1 + count( $form_cols ) >= $i; $i++ ) {
			$j = $i - 2;
			if ( '' == trim( $form_cols[ $j ]->name ) ) {
				$form_cols[ $j ]->name = 'field_id:' . $form_cols[ $j ]->id;
			}
			$default_hide[ $i ] = $arflitemainhelper->arflitetruncate( $form_cols[ $j ]->name, 40 );

			if ( in_array( $form_cols[ $j ]->type, $exclude_file_types_sorting ) ) {
				array_push( $exclude_from_sorting, $i );
			}
		}

		$default_hide[ $i ]     = 'Entry Key';
		$default_hide[ $i + 1 ] = 'Entry creation date';
		$default_hide[ $i + 2 ] = 'Browser Name';
		$default_hide[ $i + 3 ] = 'IP Address';
		$default_hide[ $i + 4 ] = 'Country';
		$default_hide[ $i + 5 ] = 'Page URL';
		array_push( $exclude_from_sorting, ( $i + 5 ) );
		$default_hide[ $i + 6 ] = 'Referrer URL';
		array_push( $exclude_from_sorting, ( $i + 6 ) );
		$default_hide[ $i + 7 ] = 'Action';
		array_push( $exclude_from_sorting, ( $i + 7 ) );
		$action_no = $i + 7;
	} else {
		$default_hide['2'] = 'Entry Key';
		$default_hide['3'] = 'Entry creation date';
		$default_hide['4'] = 'Browser Name';
		$default_hide['5'] = 'IP Address';
		$default_hide['6'] = 'Country';
		$default_hide['7'] = 'Page URL';
		array_push( $exclude_from_sorting, 7 );
		$default_hide['8'] = 'Referrer URL';
		array_push( $exclude_from_sorting, 8 );
		$default_hide['9'] = 'Action';
		array_push( $exclude_from_sorting, 9 );
		$action_no = 9;
	}

	global $wpdb, $ARFLiteMdlDb, $tbl_arf_forms;


	$page_params = '&action=0&arfaction=0&form=';

	$page_params .= ( $form ) ? $form->id : 0;

	if ( ! empty( $_REQUEST['fid'] ) ) {
		$page_params .= '&fid=' . intval( $_REQUEST['fid'] );
	}

	
	/* $item_vars = $this->arflite_get_sort_vars( $params, $where_clause ); */
	$item_vars = $arfliterecordcontroller->arflite_get_sort_vars( $params, $where_clause );

	$page_params .= ( $page_params_ov ) ? $page_params_ov : $item_vars['page_params'];

	if ( $form ) {

	} else {
		$form_cols    = array();
		$record_where = $item_vars['where_clause'];
	}

	$columns_list_res = $wpdb->get_results( $wpdb->prepare( 'SELECT columns_list FROM ' . $tbl_arf_forms . ' WHERE id = %d', $form->id ), ARRAY_A ); //phpcs:ignore
	$columns_list_res = $columns_list_res[0];

	$columns_list   = ( ! empty( $columns_list_res['columns_list'] ) ) ? maybe_unserialize( $columns_list_res['columns_list'] ) : array();
	$is_colmn_array = is_array( $columns_list );

	$exclude = '';

	$exclude_array = array();
	if ( count( $columns_list ) > 0 && $columns_list != '' ) {

		foreach ( $columns_list as $keys => $column ) {
			$exclude_no = 0;
			foreach ( $default_hide as $key => $val ) {

				if ( $column == $val ) {
					if ( $exclude_array == '' ) {
						$exclude_array[] = $key;
					} else {
						if ( ! in_array( $key, $exclude_array ) ) {
							$exclude_array[] = $key;
							$exclude_no++;
						}
					}
				}
			}
		}
	}


	$ipcolumn            = ( $action_no - 4 );
	$page_url_column     = ( $action_no - 2 );
	$referrer_url_column = ( $action_no - 1 );

	if ( $exclude_array == '' && ! $is_colmn_array ) {
		$exclude_array = array( $ipcolumn, $page_url_column, $referrer_url_column );
	} elseif ( is_array( $exclude_array ) && ! $is_colmn_array ) {
		if ( ! in_array( $ipcolumn, $exclude_array ) ) {
			array_push( $exclude_array, $ipcolumn );
		}
		if ( ! in_array( $page_url_column, $exclude_array ) ) {
			array_push( $exclude_array, $page_url_column );
		}
		if ( ! in_array( $referrer_url_column, $exclude_array ) ) {
			array_push( $exclude_array, $referrer_url_column );
		}
	}
} else {
	$action_no     = 9;
	$exclude_array = array( 5, 7, 8 );
}

if ( isset( $exclude_array ) && $exclude_array != '' ) {
	$exclude = implode( ',', $exclude_array );
}

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'jquery-ui-core' );

global $arflite_style_settings;

$wp_format_date = get_option( 'date_format' );

if ( $wp_format_date == 'F j, Y' ) {
	$date_format_new  = 'MMMM D, YYYY';
	$date_format_new1 = 'MMMM D, YYYY';
	$start_date_new   = 'January 01, 1970';
	$end_date_new     = 'December 31, 2050';
} elseif ( $wp_format_date == 'Y-m-d' ) {
	$date_format_new  = 'YYYY-MM-DD';
	$date_format_new1 = 'YYYY-MM-DD';
	$start_date_new   = '1970-1-1';
	$end_date_new     = '2050-12-1';
} elseif ( $wp_format_date == 'm/d/Y' ) {
	$date_format_new  = 'MM/DD/YYYY';
	$date_format_new1 = 'MM-DD-YYYY';
	$start_date_new   = '01/01/1970';
	$end_date_new     = '12/31/2050';
} elseif ( $wp_format_date == 'd/m/Y' ) {
	$date_format_new  = 'DD/MM/YYYY';
	$date_format_new1 = 'DD-MM-YYYY';
	$start_date_new   = '01/01/1970';
	$end_date_new     = '31/12/2050';
} elseif ( $wp_format_date == 'Y/m/d' ) {
	$date_format_new  = 'DD/MM/YYYY';
	$date_format_new1 = 'DD-MM-YYYY';
	$start_date_new   = '01/01/1970';
	$end_date_new     = '31/12/2050';
} else {
	$date_format_new  = 'MM/DD/YYYY';
	$date_format_new1 = 'MM-DD-YYYY';
	$start_date_new   = '01/01/1970';
	$end_date_new     = '12/31/2050';
}


global $arflite_entries_action_column_width;
?>
<input type="hidden" id="ARForms-page" value="ARForms-entries" />
<input type="hidden" id="ARForms-page-item-counter" value="<?php echo esc_attr( count( $items ) ); ?>" />
<input type="hidden" id="ARForms-page-exclude" value="<?php echo esc_attr( $exclude ); ?>" />
<?php $arfget_form = intval($_GET['form']); ?>
<input type="hidden" id='ARForms-page-form-id' value="<?php echo esc_attr( $arfget_form ); ?>" />
<input type="hidden" id="ARForms-page-action-no" value="<?php echo esc_attr( $action_no ); ?>" />
<input type="hidden" id="ARForms-page-date-format-new" value="<?php echo esc_attr( $date_format_new ); ?>" />
<input type="hidden" id="ARForms-page-date-format-new1" value="<?php echo esc_attr( $date_format_new1 ); ?>" />
<input type="hidden" id="ARForms-page-start-date-new" value="<?php echo esc_attr( $start_date_new ); ?>" />
<input type="hidden" id="ARForms-page-end-date-new" value="<?php echo esc_attr( $end_date_new ); ?>" />
<input type="hidden" id="arforms_wp_nonce" value="<?php echo esc_attr(wp_create_nonce( 'arforms_wp_nonce' )); ?>" />

<?php
if ( intval( $_GET['form'] ) < 0 ) {
	echo str_replace( 'id="{arf_id}"', 'id="arf_full_width_loader" style="display:none;" ', ARFLITE_LOADER_ICON ); //phpcs:ignore
} else {
	if ( ( isset( $_GET['tabview'] ) && 'analytics' == sanitize_text_field( $_GET['tabview'] ) ) && sanitize_text_field( $_GET['form'] ) > 0 ) {
		echo str_replace( 'id="{arf_id}"', 'id="arf_full_width_loader" style="display:none;" ', ARFLITE_LOADER_ICON ); //phpcs:ignore
	} else {
		echo str_replace( 'id="{arf_id}"', 'id="arf_full_width_loader" ', ARFLITE_LOADER_ICON ); //phpcs:ignore
	}
}
?>
<div class="wrap frm_entries_page">
	<div class="top_bar">
		<span class="h2"><?php echo esc_html__( 'Form Entries', 'arforms-form-builder' ); ?></span>
		<input type="hidden" name="arfmainformurl" data-id="arfmainformurl" value="<?php echo esc_url(ARFLITEURL); ?>" />
		<input type="hidden" name="arflite_validation_nonce" id="arflite_validation_nonce" value="<?php echo esc_attr( wp_create_nonce( 'arflite_wp_nonce' ) ); ?>" />
	</div>
	<?php $arforms_general_settings->arforms_render_pro_settings( 'arforms_pro_render_license_notice' ); ?>
	<div id="success_message" class="arf_success_message">
		<div class="message_descripiton">
			<div style="float: left; margin-right: 15px;" id="records_suc_message_des"></div>
			<div class="message_svg_icon">
				<svg style="height: 14px;width: 14px;"><path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M6.075,14.407l-5.852-5.84l1.616-1.613l4.394,4.385L17.181,0.411l1.616,1.613L6.392,14.407H6.075z"></path></svg>
			</div>
		</div>
	</div>

	<div id="error_message" class="arf_error_message">
		<div class="message_descripiton">
			<div style="float: left; margin-right: 15px;" id="records_error_message_des"></div>
			<div class="message_svg_icon">
				<svg style="height: 14px;width: 14px;"><path fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></svg>
			</div>
		</div>
	</div>
	<div id="poststuff" class="metabox-holder">

		<div id="post-body">
			<div class="inside" style="background-color:#ffffff;">
				<div class="formsettings1" style="background-color:#ffffff;">
					<div class="setting_tabrow">
						<div class="arftab" style="padding-left:0px;">
							<ul class="arfmainformnavigation" style="height:43px !important; padding-bottom:0px; margin-bottom:0px;">
								<?php $arformsmain->arforms_render_entries_tab(); ?>
							</ul>
						</div>
					</div>
					
					<input type="hidden" name="action_column_width" id="action_column_width" value="<?php echo isset( $arflite_entries_action_column_width ) ? esc_attr( $arflite_entries_action_column_width ) : '120'; ?>" />
					<div class="frm_settings_form">

						<input type="hidden" name="arfcurrenttab" id="arfcurrenttab" value="form_entries" />

						<input type="hidden" name="arfformentriesurl" id="arfformentriesurl" value="<?php echo esc_url( admin_url( 'admin.php' ) . '?page=ARForms-entries' ); ?>" />

						<div id="form_entries">
							<div class="arf_form_entry_select">
								<table class="arf_form_entry_select_sub">
									<tr>
										<th class="arf_form_entry_left" style="float:none;<?php ( is_rtl() ) ? 'text-align:right;' : 'text-align:left;'; ?>"><?php echo esc_html__( 'Select form', 'arforms-form-builder' ); ?></th>
										<th class="arf_form_entry_left"><?php echo esc_html__( 'Select Date', 'arforms-form-builder' ); ?> (<?php echo esc_html__( 'optional', 'arforms-form-builder' ); ?>)</th>
									</tr>
									<tr>
										<td>
										<div class='sltstandard' style='float:none; width: 400px !important;<?php echo ( is_rtl() ) ? 'margin-left:60px;' : 'margin-right:60px;'; ?><?php echo ( $arformsmain->arforms_is_pro_active()) ? 'margin-top:-11px;' : ''; ?>'>
											
												<?php 
													if( $arformsmain->arforms_is_pro_active() ){
														global $arformhelper;
														$arformhelper->forms_dropdown('arfredirecttolist', intval( $_GET['form'] ), __( 'Select Form', 'arforms-form-builder' ), false, ''); //phpcs:ignore
													} else {
														$arfliteformhelper->arflite_forms_dropdown( 'arfredirecttolist', intval( $_GET['form'] ), __( 'Select Form', 'arforms-form-builder' ), false, ''); //phpcs:ignore 
													}
												?>
											</div>
										</td>
										<td>
											<?php
											if ( is_rtl() ) {
												$sel_frm_date_wrap = 'float:right;text-align:right;';
												$sel_frm_sel_date  = 'float:right;';
												$sel_frm_button    = 'float:right;';
											} else {
												$sel_frm_date_wrap = 'float:left;text-align:left;';
												$sel_frm_sel_date  = 'float:left;';
												$sel_frm_button    = 'float:left;';
											}
											?>
											<div style="position:relative; <?php echo esc_attr( $sel_frm_date_wrap ); ?>">
												<div style="<?php echo esc_attr( $sel_frm_sel_date ); ?>"><div class="arfentrytitle" style='margin-left:0;'><?php echo __( 'From', 'arforms-form-builder' ); ?></div><input type="text" class="txtmodal1" value="<?php echo ( isset( ($_GET['start_date']) ) ) ?  esc_attr( $_GET['start_date'] ) : ''; ?>" id="datepicker_from" name="datepicker_from" style="width:120px;height:35px;vertical-align:middle; " /></div> <div class="arfentrytitle"><?php echo __( 'To', 'arforms-form-builder' ); ?></div>&nbsp;&nbsp;<div style="<?php echo esc_attr( $sel_frm_sel_date ); ?>"><input type="text" class="txtmodal1" value="<?php echo ( isset( $_GET['end_date'] ) ) ? sanitize_text_field( $_GET['end_date'] ) : ''; ?>" id="datepicker_to" name="datepicker_to" style="vertical-align:middle; width:120px;height:35px;"/></div> <?php //phpcs:ignore ?>
												<div style=" <?php echo esc_attr( $sel_frm_button ); ?>">
													<div class="arf_form_entry_left">&nbsp;</div>
													<div style="float:left;text-align:left;"><button type="button" class="rounded_button arf_btn_dark_blue" onclick="arforms_change_frm_entries();" style="width: 35px !important;height: 35px;"><?php echo esc_html__( 'Go', 'arforms-form-builder' ); ?></button></div>
												</div>
												<input type="hidden" name="please_select_form" id="please_select_form" value="<?php echo esc_html__( 'Please select a form', 'arforms-form-builder' ); ?>" />
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div style="clear:both; height:30px;"></div>

							<?php do_action( 'arflitebeforelistingentries' ); ?>

							<form method="get" id="list_entry_form" class="arf_list_entries_form" onsubmit="return arfliteapply_bulk_action();" style="float:left;width:98%;padding-left: 15px;">

								<input type="hidden" name="page" value="ARForms-entries" />

								<input type="hidden" name="form" value="<?php echo ( $form ) ? esc_attr( $form->id ) : '-1'; ?>" />

								<input type="hidden" name="arfaction" value="list" />

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
								<input type="hidden" id="arflite_entries_exclude_cols" value="<?php echo esc_attr( json_encode( $exclude_from_sorting ) ); ?>"/>

								<div class="alignleft actions">
									<div class="arf_list_bulk_action_wrapper">
										<?php
											echo $arflitemaincontroller->arflite_selectpicker_dom( 'action1', 'arf_bulk_action_one', '', '', '-1', array(), $actions ); //phpcs:ignore
										?>
									</div>
									<input type="submit" id="doaction1" class="arf_bulk_action_btn rounded_button btn_green" value="<?php echo esc_html__( 'Apply', 'arforms-form-builder' ); ?>"/>
								</div>

								<table cellpadding="0" cellspacing="0" border="0" class="display table_grid import_export_entries" id="example">
									<thead>
										<tr>
											<th class="box">
												<div style="display:inline-block; position:relative;">
													<div class="arf_custom_checkbox_div arfmarginl15">
														<div class="arf_custom_checkbox_wrapper">
															<input id="cb-select-all-1" type="checkbox" class="">
															<svg width="18px" height="18px">
															<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
															<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
															</svg>
														</div>
													</div>

													<label for="cb-select-all-1"  class="cb-select-all"><span class="cb-select-all-checkbox"></span></label>
												</div>
											</th>
											<th class="ui-state-default"><?php echo esc_html__( 'ID', 'arforms-form-builder' ); ?></th>
											<?php
											if ( count( $form_cols ) > 0 ) {
												foreach ( $form_cols as $col ) {
													?>
													<th><?php echo $arflitemainhelper->arflitetruncate( $col->name, 40 ); //phpcs:ignore ?></th> 
													<?php
												}
											}
											?>
											<th class="ui-state-default"><?php echo esc_html__( 'Entry Key', 'arforms-form-builder' ); ?></th>
											<th class="ui-state-default"><?php echo esc_html__( 'Entry creation date', 'arforms-form-builder' ); ?></th>
											<th class="ui-state-default"><?php echo esc_html__( 'Browser Name', 'arforms-form-builder' ); ?></th>
											<th class="ui-state-default"><?php echo esc_html__( 'IP Address', 'arforms-form-builder' ); ?></th>
											<th class="ui-state-default"><?php echo esc_html__( 'Country', 'arforms-form-builder' ); ?></th>
											<th class="ui-state-default"><?php echo esc_html__( 'Page URL', 'arforms-form-builder' ); ?></th>
											<th class="ui-state-default"><?php echo esc_html__( 'Referrer URL', 'arforms-form-builder' ); ?></th>
											<th class="arf_col_action arf_action_cell"><?php echo esc_html__( 'Action', 'arforms-form-builder' ); ?></th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>

								<div class="alignleft actions">
									<div class="arf_list_bulk_action_wrapper">
										<?php
											echo $arflitemaincontroller->arflite_selectpicker_dom( 'action3', 'arf_inc_bulk_action_one', '', '', '', array(), $actions ); //phpcs:ignore
										?>
									</div>
									<input type="submit" id="doaction3" class="arf_bulk_action_btn rounded_button btn_green" value="<?php echo esc_html__( 'Apply', 'arforms-form-builder' ); ?>"/>
								</div>

								<div class="footer_grid"></div>
							</form>

							<?php do_action( 'arfliteafterlistingentries' ); ?>

							<div style="clear:both;"></div>
							<br /><br />
						</div>
						<?php
							$arforms_general_settings->arforms_include_pro_files( 'arf_pro_view.php', 'view' );
						?>
					</div>
				</div>
			</div>
		</div>

		<div class="arf_modal_overlay">
			<div id="delete_form_message" style="" class="arfmodal arfdeletemodabox arf_popup_container">
				<div class="arfnewmodalclose" data-dismiss="arfmodal"><img alt='' src="<?php echo esc_url(ARFLITEIMAGESURL) . '/close-button.png'; ?>" align="absmiddle" /></div>
				<input type="hidden" value="" id="delete_entry_id" />
				<div class="arfdelete_modal_title"><img alt='' src="<?php echo esc_url(ARFLITEIMAGESURL) . '/delete-field-icon.png'; ?>" align="absmiddle" style="margin-top:-5px;" />&nbsp;<?php echo esc_html__( 'DELETE ENTRY', 'arforms-form-builder' ); ?></div>
				<div class="arfdelete_modal_msg"><?php echo esc_html__( 'Are you sure you want to delete this entry?', 'arforms-form-builder' ); ?></div>
				<div class="arf_delete_modal_row">
					<input type="hidden" name="arflite_delete_entry_nonce" id="arflite_delete_entry_nonce" value="<?php echo esc_attr(wp_create_nonce("arflite_delete_entry_nonce") ); ?>">
					<div class="arf_delete_modal_left" onclick="arfliteentryactionfunc('delete', '' );"><img alt='' src="<?php echo esc_url(ARFLITEIMAGESURL) . '/okay-icon.png'; ?>" align="absmiddle" style="margin-right:10px;" />&nbsp;<?php echo esc_html__( 'Okay', 'arforms-form-builder' ); ?></div>
					<div class="arf_delete_modal_right" id="arf_close_single_entry_modal" data-dismiss="arfmodal"><img alt='' src="<?php echo esc_url(ARFLITEIMAGESURL) . '/cancel-btnicon.png'; ?>" align="absmiddle" style="margin-right:10px;" />&nbsp;<?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></div>
				</div>
			</div>
		</div>
		<div class='arf_modal_overlay'>
			<div class="arf_entry_popup_container_wrapper">
				<div class='arf_popup_container arf_view_entry_modal arf_popup_container_view_entry_modal'>
					<div class='arf_popup_container_header'><?php echo esc_html__( 'View entry', 'arforms-form-builder' ); ?> <span id="arf_view_entry_modal_form_title"></span>
						<?php if( ! $arformsmain->arforms_is_pro_active()) { ?>

							<button type="button" class="rounded_button arf_edit_entry_button arf_btn_dark_blue arf_restricted_control"><?php echo esc_html__( 'Edit Entries', 'arforms-form-builder' ); ?></button>
						<?php } ?>
						<div class="arf_modal_close_btn arf_entry_model_close"></div>
					</div>
					<div class='arfentry_modal_content arf_popup_content_container'></div>
					<div class="arf_popup_footer arf_view_entry_modal_footer">
						<div class="arf_navigation_button">
							<button class="rounded_button arf_btn_dark_blue" id="arf_prev_entry_button" name="arf_prev_entry_button" style="<?php echo ( is_rtl() ) ? 'margin-left:7px;' : 'margin-right:7px;'; ?>"><?php echo esc_html__( 'Previous Entry', 'arforms-form-builder' ); ?></button>

							<button class="rounded_button arf_btn_dark_blue" id="arf_next_entry_button" name="arf_next_entry_button"><?php echo esc_html__( 'Next Entry', 'arforms-form-builder' ); ?></button>
						</div>
						<?php do_action('arf_edit_entry_view'); ?>
						<button class="rounded_button" id="arf_entry_popup_close_btn" style="color:#666666;" name="arf_entry_popup_close_btn"><?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></button>
					</div>
				</div>
			</div>
		</div>
		<div class="arf_modal_overlay">
			<div id="delete_bulk_entry_message" class="arfdeletemodabox arfmodal arf_popup_container arfdeletemodalboxnew">
				<input type="hidden" value="false" id="delete_bulk_entry_flag"/>
				<div class="arfdelete_modal_msg delete_confirm_message"><?php echo esc_html__( 'Are you sure you want to delete this entries?', 'arforms-form-builder' ); ?></div>
				<div class="arf_delete_modal_row delete_popup_footer">
					<button class="rounded_button add_button arf_delete_modal_left arfdelete_color_red" onclick="arflite_delete_bulk_entries(true)">&nbsp;<?php echo esc_html__( 'Okay', 'arforms-form-builder' ); ?></button>&nbsp;&nbsp;<button class="arf_delete_modal_right rounded_button delete_button arfdelete_color_gray arf_bulk_delete_entry_close_btn" data-dismiss="arfmodal">&nbsp;<?php echo esc_html__( 'Cancel', 'arforms-form-builder' ); ?></button>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="arf_is_edit_entries" value="no" />

	<?php do_action( 'arforms_quick_help_links' ); ?>