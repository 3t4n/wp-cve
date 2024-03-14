<?php
if ( ! is_admin() ) {
	print 'Direct access not allowed.';
	exit;
}

$_GET['u'] = ( isset( $_GET['u'] ) ) ? intval( @$_GET['u'] ) : 0;
$_GET['c'] = ( isset( $_GET['c'] ) ) ? intval( @$_GET['c'] ) : 0;
$_GET['d'] = ( isset( $_GET['d'] ) ) ? intval( @$_GET['d'] ) : 0;

global $wpdb;
$cpcff_main = CPCFF_MAIN::instance();

$message = '';

if ( isset( $_GET['orderby'] ) ) {
	update_option( 'CP_CALCULATEDFIELDSF_FORMS_LIST_ORDERBY', 'form_name' == $_GET['orderby'] ? 'form_name' : 'id' );
}

$cp_default_template = CP_CALCULATEDFIELDSF_DEFAULT_template;

if ( isset( $_REQUEST['cp_default_template'] ) &&  'none' != $_REQUEST['cp_default_template'] ) {
	check_admin_referer( 'cff-default-settings', '_cpcff_nonce' );

	$cp_default_template = sanitize_text_field( wp_unslash( $_REQUEST['cp_default_template'] ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

	// Update default settings
	update_option( 'CP_CALCULATEDFIELDSF_DEFAULT_template', $cp_default_template );

	if ( isset( $_REQUEST['cp_default_existing_forms'] ) ) {
		$myrows = $wpdb->get_results( 'SELECT id,form_structure,enable_submit,cv_enable_captcha FROM ' . $wpdb->prefix . CP_CALCULATEDFIELDSF_FORMS_TABLE ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		foreach ( $myrows as $item ) {
			$form_structure = preg_replace( '/"formtemplate"\s*\:\s*"[^"]*"/', '"formtemplate":"' . esc_js( $cp_default_template ) . '"', $item->form_structure );

			$wpdb->update(
				$wpdb->prefix . CP_CALCULATEDFIELDSF_FORMS_TABLE,
				array(
					'form_structure' => $form_structure,
				),
				array(
					'id' => $item->id,
				),
				array( '%s' ),
				array( '%d' )
			);
		}
	}
	$message = __( 'Default settings updated', 'calculated-fields-form' );

}

if ( isset( $_GET['a'] ) && '1' == $_GET['a'] ) {
	check_admin_referer( 'cff-add-form', '_cpcff_nonce' );
	$new_form = $cpcff_main->create_form(
		isset( $_GET['name'] ) ? sanitize_text_field( wp_unslash( $_GET['name'] ) ) : '',
		isset( $_GET['category'] ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : '',
		isset( $_GET['ftpl'] ) ? sanitize_text_field( wp_unslash( $_GET['ftpl'] ) ) : 0
	);
	// Update the default category
	$cff_current_form_category = get_option( 'calculated-fields-form-category', '' );
	if ( ! empty( $cff_current_form_category ) ) {
		update_option( 'calculated-fields-form-category', sanitize_text_field( wp_unslash( $_GET['category'] ) ) );
	}

	$message = __( 'Item added', 'calculated-fields-form' );
	if ( $new_form ) {
		print "<script>document.location = 'admin.php?page=cp_calculated_fields_form&cal=" . esc_js( $new_form->get_id() ) . '&r=' . esc_js( rand() ) . '&_cpcff_nonce=' . esc_js( wp_create_nonce( 'cff-form-settings' ) ) . "';</script>";
	}
} elseif ( ! empty( $_GET['u'] ) ) {
	check_admin_referer( 'cff-update-form', '_cpcff_nonce' );
	$cpcff_main->get_form( sanitize_text_field( wp_unslash( $_GET['u'] ) ) )->update_name( ( isset( $_GET['name'] ) ) ? sanitize_text_field( wp_unslash( $_GET['name'] ) ) : '' );
	$message = __( 'Item updated', 'calculated-fields-form' );
} elseif ( ! empty( $_GET['d'] ) ) {
	check_admin_referer( 'cff-delete-form', '_cpcff_nonce' );
	$cpcff_main->delete_form( sanitize_text_field( wp_unslash( $_GET['d'] ) ) );
	$message = __( 'Item deleted', 'calculated-fields-form' );
} elseif ( ! empty( $_GET['c'] ) ) {
	check_admin_referer( 'cff-clone-form', '_cpcff_nonce' );
	if ( is_numeric( $_GET['c'] ) && $cpcff_main->clone_form( intval( $_GET['c'] ) ) !== false ) {
		$message = __( 'Item duplicated/cloned', 'calculated-fields-form' );
	} else {
		$message = __( 'Duplicate/Clone Error, the form cannot be cloned', 'calculated-fields-form' );
	}
} elseif ( isset( $_GET['ac'] ) && 'st' == $_GET['ac'] ) {
	check_admin_referer( 'cff-update-general-settings', '_cpcff_nonce' );
	update_option( 'CP_CFF_LOAD_SCRIPTS', ( isset( $_GET['scr'] ) && '1' == $_GET['scr'] ? '0' : '1' ) );
	update_option( 'CP_CALCULATEDFIELDSF_DISABLE_REVISIONS', ( isset( $_GET['dr'] ) && '1' == $_GET['dr'] ? 1 : 0 ) );
	update_option( 'CP_CALCULATEDFIELDSF_USE_CACHE', ( isset( $_GET['jsc'] ) && '1' == $_GET['jsc'] ? 1 : 0 ) );
	update_option( 'CP_CALCULATEDFIELDSF_OPTIMIZATION_PLUGIN', ( isset( $_GET['optm'] ) && '1' == $_GET['optm'] ? 1 : 0 ) );
	update_option( 'CP_CALCULATEDFIELDSF_EXCLUDE_CRAWLERS', ( isset( $_GET['ecr'] ) && '1' == $_GET['ecr'] ? 1 : 0 ) );
	update_option( 'CP_CALCULATEDFIELDSF_DIRECT_FORM_ACCESS', ( isset( $_GET['df'] ) && '1' == $_GET['df'] ? 1 : 0 ) );
	update_option( 'CP_CALCULATEDFIELDSF_AMP', ( isset( $_GET['amp'] ) && '1' == $_GET['amp'] ? 1 : 0 ) );

	$public_js_path = CP_CALCULATEDFIELDSF_BASE_PATH . '/js/cache/all.js';
	try {
		if ( get_option( 'CP_CALCULATEDFIELDSF_USE_CACHE', CP_CALCULATEDFIELDSF_USE_CACHE ) == false ) {
			if ( file_exists( $public_js_path ) ) {
				unlink( $public_js_path );
			}
		} else {
			if ( ! file_exists( $public_js_path ) ) {
				wp_remote_get( CPCFF_AUXILIARY::wp_url() . ( ( strpos( CPCFF_AUXILIARY::wp_url(), '?' ) === false ) ? '/?' : '&' ) . 'cp_cff_resources=public&min=1', array( 'sslverify' => false ) );
			}
		}
	} catch ( Exception $err ) {
		error_log( $err->getMessage() );
	}

	if ( ! empty( $_GET['chs'] ) ) {
		$target_charset = sanitize_text_field( wp_unslash( $_GET['chs'] ) );
		if ( ! in_array( $target_charset, array( 'utf8_general_ci', 'utf8mb4_general_ci', 'latin1_swedish_ci' ) ) ) {
			$target_charset = 'utf8_general_ci';
		}

		$tables = array( $wpdb->prefix . CP_CALCULATEDFIELDSF_FORMS_TABLE, $wpdb->prefix . CP_CALCULATEDFIELDSF_POSTS_TABLE_NAME_NO_PREFIX );
		foreach ( $tables as $tab ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride
			$myrows = $wpdb->get_results( "DESCRIBE {$tab}" ); // phpcs:ignore WordPress.DB.PreparedSQL
			foreach ( $myrows as $item ) {
				$name = $item->Field;
				$type = $item->Type; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
				if ( preg_match( '/^varchar\((\d+)\)$/i', $type, $mat ) || ! strcasecmp( $type, 'CHAR' ) || ! strcasecmp( $type, 'TEXT' ) || ! strcasecmp( $type, 'MEDIUMTEXT' ) ) {
					$wpdb->query( "ALTER TABLE {$tab} CHANGE {$name} {$name} {$type} COLLATE {$target_charset}" ); // phpcs:ignore WordPress.DB.PreparedSQL
				}
			}
		}
	}
	$message = __( 'Troubleshoot settings updated', 'calculated-fields-form' );
}

// For sortin the forms list
$orderby = get_option( 'CP_CALCULATEDFIELDSF_FORMS_LIST_ORDERBY', 'id' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
if ( $message ) {
	echo "<div id='setting-error-settings_updated' class='" . ( stripos( $message, 'error' ) !== false ? 'error' : 'updated' ) . " settings-error'><p><strong>" . esc_html( $message ) . '</strong></p></div>';
}

?>
<div class="wrap">
<?php
if ( get_option( 'cff-t-f', 0 ) ) :
	?>
	<div style="border:1px solid #F0AD4E;background:#FBE6CA;padding:10px;margin:10px 0;font-size:1.3em;">
	<?php print get_option( 'cff-t-t', '' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
	<?php
	delete_option( 'cff-t-f' );
endif;
?>
<h1><?php esc_html_e( 'Calculated Fields Form', 'calculated-fields-form' ); ?></h1>

<script type="text/javascript">
 var cff_metabox_nonce = '<?php print esc_js( wp_create_nonce( 'cff-metabox-status' ) ); ?>';
 function cp_addItem()
 {
	var e = jQuery("#cp_itemname"),
		form_tag = e.closest('form')[0],
		calname  = e.val().replace(/^\s*/, '').replace(/^\s*/, '').replace(/\s*$/, ''),
		category = document.getElementById("calculated-fields-form-category").value;

	e.val(calname);

	if('reportValidity' in form_tag && !form_tag.reportValidity()) return;

	document.location = 'admin.php?page=cp_calculated_fields_form&a=1&r='+Math.random()+'&name='+encodeURIComponent(calname)+'&category='+encodeURIComponent(category)+'&_cpcff_nonce=<?php echo esc_js( wp_create_nonce( 'cff-add-form' ) ); ?>';
 }

 function cp_addItem_keyup( e )
 {
	e.which = e.which || e.keyCode;
	if(e.which == 13) cp_addItem();
 }

 function cp_updateItem(id)
 {
	var calname = document.getElementById("calname_"+id).value;
	document.location = 'admin.php?page=cp_calculated_fields_form&u='+id+'&r='+Math.random()+'&name='+encodeURIComponent(calname)+'&_cpcff_nonce=<?php echo esc_js( wp_create_nonce( 'cff-update-form' ) ); ?>';
 }

 function cp_cloneItem(id)
 {
	document.location = 'admin.php?page=cp_calculated_fields_form&c='+id+'&r='+Math.random()+'&_cpcff_nonce=<?php echo esc_js( wp_create_nonce( 'cff-clone-form' ) ); ?>';
 }

 function cp_manageSettings(id)
 {
    let url = 'admin.php?page=cp_calculated_fields_form&cal='+id+'&r='+Math.random()+'&_cpcff_nonce=<?php echo esc_js( wp_create_nonce( 'cff-form-settings' ) ); ?>';
    //ctrl was held down during the click
    if (window.event.ctrlKey) {
        window.open(url, '_blank');
    } else {
        document.location = url;
    }
 }

 function cp_viewMessages(id)
 {
	alert('Not available in this version. Check other versions at: '+"\n\n"+'https://cff.dwbooster.com/download');
 }

 function cp_BookingsList(id)
 {
	document.location = 'admin.php?page=cp_calculated_fields_form&cal='+id+'&list=1&r='+Math.random();
 }

 function cp_deleteItem(id)
 {
	if (confirm('<?php esc_html_e( 'Are you sure you want to delete this item?', 'calculated-fields-form' ); ?>'))
	{
		document.location = 'admin.php?page=cp_calculated_fields_form&d='+id+'&r='+Math.random()+'&_cpcff_nonce=<?php echo esc_js( wp_create_nonce( 'cff-delete-form' ) ); ?>';
	}
 }

 function cp_updateConfig()
 {
	if (confirm('<?php esc_html_e( 'Are you sure you want to update these settings?', 'calculated-fields-form' ); ?>'))
	{
		var scr = document.getElementById("ccscriptload").value,
			chs = document.getElementById("cccharsets").value,
			dr  = (document.getElementById("ccdisablerevisions").checked) ? 1 : 0,
			jsc = (document.getElementById("ccjscache").checked) ? 1 : 0,
			optm = (document.getElementById("ccoptimizationplugin").checked) ? 1 : 0,
			df  = (document.getElementById("ccdirectform").checked) ? 1 : 0,
			amp = (document.getElementById("ccampform").checked) ? 1 : 0,
			ecr = (document.getElementById("ccexcludecrawler").checked) ? 1 : 0;

		document.location = 'admin.php?page=cp_calculated_fields_form&ecr='+ecr+'&ac=st&scr='+scr+'&chs='+chs+'&dr='+dr+'&jsc='+jsc+'&optm='+optm+'&df='+df+'&amp='+amp+'&r='+Math.random()+'&_cpcff_nonce=<?php echo esc_js( wp_create_nonce( 'cff-update-general-settings' ) ); ?>';
	}
 }

 function cp_select_template()
 {
	jQuery('.cp_template_info').hide();
	jQuery('.cp_template_'+jQuery('#cp_default_template').val()).show();
 }

 function cp_update_default_settings(e)
 {
	if(jQuery('[name="cp_default_existing_forms"]').prop('checked'))
	{
		if (confirm('<?php esc_html_e( 'Are you sure you want to modify existing forms?\\nWe recommend modifying the forms one by one.', 'calculated-fields-form' ); ?>'))
		{
			e.form.submit();
		}
	}
	else e.form.submit();
 }
</script>
<h2 class="nav-tab-wrapper">
	<a href="admin.php?page=cp_calculated_fields_form&cff-tab=forms" class="nav-tab <?php if ( empty( $_GET['cff-tab'] ) || 'forms' == $_GET['cff-tab'] ) {
		print 'nav-tab-active';} ?>"><?php esc_html_e( 'Forms and Settings', 'calculated-fields-form' ); ?></a>
	<a href="admin.php?page=cp_calculated_fields_form&cff-tab=marketplace" class="nav-tab <?php if ( ! empty( $_GET['cff-tab'] ) && 'marketplace' == $_GET['cff-tab'] ) {
		print 'nav-tab-active';} ?>"><?php esc_html_e( 'Marketplace', 'calculated-fields-form' ); ?></a>
</h2>
<div style="margin-top:20px;display:<?php print ( empty( $_GET['cff-tab'] ) || 'forms' == $_GET['cff-tab'] ) ? 'block' : 'none'; ?>;"><!-- Forms & Settings Section -->
	<div id="normal-sortables" class="meta-box-sortables">

        <!-- New Form -->
		<?php
		if ( isset( $_POST['calculated-fields-form-category'] ) ) {
			check_admin_referer( 'cff-change-category', '_cpcff_nonce' );
			update_option( 'calculated-fields-form-category', sanitize_text_field( wp_unslash( $_POST['calculated-fields-form-category'] ) ) );
			update_option( 'calculated-fields-search-form', sanitize_text_field( wp_unslash( $_POST['calculated-fields-search-form'] ) ) );
		}
			$cff_current_form_category = get_option( 'calculated-fields-form-category', '' );
            $cff_search_form_term = get_option( 'calculated-fields-search-form', '' );
		?>
        <div id="metabox_new_form_area" class="postbox" >
            <h3 class='hndle' style="padding:5px;"><span><?php _e( 'New Form', 'calculated-fields-form' ); ?></span></h3>
            <div class="inside">
                <form name="additem">
                    <?php _e( 'Item Name', 'calculated-fields-form' ); ?>(*):<br />
					<div>
						<input type="text" name="cp_itemname" id="cp_itemname"  value="" onkeyup="cp_addItem_keyup( event );"  style="margin-top:5px;" required />
						<input type="text" name="calculated-fields-form-category" id="calculated-fields-form-category"  value="<?php print esc_attr($cff_current_form_category); ?>" style="margin-top:5px;" placeholder="<?php esc_attr_e('Category', 'calculated-fields-form'); ?>" list="calculated-fields-form-categories" />
						<datalist id="calculated-fields-form-categories">
							<?php
								print $cpcff_main->get_categories('DATALIST');
							?>
						</datalist>
						<input type="button" onclick="cp_addItem();" name="gobtn" value="<?php esc_attr_e( 'Create Form', 'calculated-fields-form' ); ?>" class="button-primary" style="margin-top:5px;" />
						<input type="button" onclick="cff_openLibraryDialog();" name="gobtn" value="<?php esc_attr_e( 'From Template', 'calculated-fields-form' ); ?>" class="button-secondary" style="margin-top:5px;" />
                    </div>
                </form>
                <i id="cff-top-position"></i>
            </div>
        </div>

		<!-- Form Categories -->
        <form id="metabox_categories_list" action="admin.php?page=cp_calculated_fields_form#cff-top-position" method="post">
			<input type="hidden" name="_cpcff_nonce" value="<?php echo esc_attr( wp_create_nonce( 'cff-change-category' ) ); ?>" />
			<b><?php esc_html_e( 'Form Categories', 'calculated-fields-form' ); ?></b>
            <select name="calculated-fields-form-category" onchange="this.form.submit();">
				<option value=""><?php esc_html_e( 'All forms', 'calculated-fields-form' ); ?></option>
				<?php
					print $cpcff_main->get_categories( 'SELECT', $cff_current_form_category ); // phpcs:ignore WordPress.Security.EscapeOutput
				?>
			</select>
            <b><?php _e('Search', 'calculated-fields-form'); ?></b>
            <input type="text" name="calculated-fields-search-form" placeholder="<?php esc_attr_e( '- search term -', 'calculated-fields-form' ); ?>" value="<?php esc_attr_e( $cff_search_form_term ); ?>" />
            <input type="submit" value="<?php esc_attr_e( 'Search', 'calculated-fields-form' ); ?>" class="button-primary" />
            <input id="cff-reset-forms-filter" type="submit" value="<?php esc_attr_e( 'Reset', 'calculated-fields-form' ); ?>" onclick="jQuery('[name=\'calculated-fields-form-category\'] option:first-child').prop('selected', true);jQuery('[name=\'calculated-fields-search-form\']').val('');" class="button-secondary" />
		</form>

        <div id="forms_pagination">
			<?php
			if ( ! empty ( $_POST['calculated-fields-form-records-per-page'] ) ) {
				check_admin_referer( 'cff-records-per-page', '_cpcff_nonce' );

				if ( 'all' == sanitize_text_field( wp_unslash( $_POST['calculated-fields-form-records-per-page'] ) ) ) {
					$records_per_page = PHP_INT_MAX;
				} elseif ( is_numeric( $_POST['calculated-fields-form-records-per-page'] ) ) {
					$records_per_page = max( 0, intval( $_POST['calculated-fields-form-records-per-page'] ) );
				}
				if ( ! empty( $records_per_page ) ) {
					update_option(
						'calculated-fields-form-records-per-page',
						$records_per_page
					);
				}
			}
			$records_per_page = get_option('calculated-fields-form-records-per-page', 20);
			$myrows = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . CP_CALCULATEDFIELDSF_FORMS_TABLE . ' WHERE 1=1 ' . ( $cff_current_form_category != '' ? $wpdb->prepare( ' AND category=%s ', $cff_current_form_category ) : '' ) . ( $cff_search_form_term != '' ? $wpdb->prepare( ' AND (form_name LIKE %s OR form_structure LIKE %s)', '%' . $cff_search_form_term . '%', '%' . $cff_search_form_term . '%' ) : '' ) . " ORDER BY " . $orderby . ( 'id' == $orderby ? " DESC" : " ASC" ) );  // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			$total_pages = ceil( count( $myrows ) / $records_per_page );
			$current_page = ! empty( $_REQUEST['page-number'] ) && is_numeric( $_REQUEST['page-number'] ) ?
							min( $total_pages, max( 1, intval( sanitize_text_field( wp_unslash( $_REQUEST['page-number'] ) ) ) ) ) :
							1;
            ?>
            <form action="admin.php?page=cp_calculated_fields_form#cff-top-position" method="post">
                <input type="hidden" name="_cpcff_nonce" value="<?php echo wp_create_nonce( 'cff-records-per-page' ); ?>" />
                <input type="hidden" name="page-number" value="<?php echo esc_attr( $current_page ); ?>" />
                <select name="calculated-fields-form-records-per-page" onchange="this.form.submit();" style="margin-left: 20px; margin-bottom:10px;">
                    <option value="10"  <?php if ( $records_per_page == 10 ) print 'SELECTED'; ?>><?php print esc_html(__('10 forms', 'calculated-fields-form')); ?></option>
                    <option value="20"  <?php if ( $records_per_page == 20 ) print 'SELECTED'; ?>><?php print esc_html(__('20 forms', 'calculated-fields-form')); ?></option>
                    <option value="50"  <?php if ( $records_per_page == 50 ) print 'SELECTED'; ?>><?php print esc_html(__('50 forms', 'calculated-fields-form')); ?></option>
                    <option value="100" <?php if ( $records_per_page == 100 ) print 'SELECTED'; ?>><?php print esc_html(__('100 forms', 'calculated-fields-form')); ?></option>
                    <option value="all" <?php if ( $records_per_page == 'all' || 100 < $records_per_page ) print 'SELECTED'; ?>><?php print esc_html(__('All forms', 'calculated-fields-form')); ?></option>
                </select>
            </form>
        <?php

        $pages_links = paginate_links(  array(
            'base'         => 'admin.php?page=cp_calculated_fields_form%_%',
            'format'       => '&page-number=%#%',
            'total'        => $total_pages,
            'current'      => $current_page,
            'show_all'     => False,
            'end_size'     => 1,
            'mid_size'     => 2,
            'prev_next'    => True,
            'prev_text'    => __( '&laquo; Previous' ),
            'next_text'    => __( 'Next &raquo;' ),
            'type'         => 'plain',
            'add_args'     => False
            ) );

        print $pages_links;
        ?>
        </div>
        <div style="clear:both;Display:block"></div>
        <hr />
		<!-- Forms List -->
		<div id="metabox_form_list" class="postbox" >
			<h3 class='hndle' style="padding:5px;"><span><?php
				esc_html_e( 'Form List / Items List', 'calculated-fields-form' );

			if ( '' != $cff_current_form_category ) {
				print '&nbsp;' . esc_html__( 'in', 'calculated-fields-form' ) . '&nbsp;<u>' . esc_html( $cff_current_form_category ) . '</u>&nbsp;' . esc_html__( 'category', 'calculated-fields-form' );
			}

			if($cff_search_form_term != '')
			{
				print ',&nbsp;'.__('search term(s)', 'calculated-fields-form').'&nbsp;<u>' . esc_html( $cff_search_form_term ) . '</u>';
			}
			?></span></h3>
			<div class="inside" style="overflow-x:auto;">
				<table cellspacing="10" class="cff-custom-table cff-forms-list">
					<thead>
						<tr>
							<th align="left"><a href="?page=cp_calculated_fields_form&orderby=id" <?php if ( 'id' == $orderby ) {
								print 'class="cff-active-column"';} ?>><?php esc_html_e( 'ID', 'calculated-fields-form' ); ?></a></th>
							<th align="left"><a href="?page=cp_calculated_fields_form&orderby=form_name" <?php if ( 'form_name' == $orderby ) {
								print 'class="cff-active-column"';} ?>><?php esc_html_e( 'Form Name', 'calculated-fields-form' ); ?></a></th>
							<th align="center"><?php esc_html_e( 'Options', 'calculated-fields-form' ); ?></th>
							<th align="left"><?php esc_html_e( 'Category/Shortcode', 'calculated-fields-form' ); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if ( count( $myrows ) == 0 ) {
						print '<tr><td colspan="4" style="text-align:center;margin-top:20px;font-size:1.2em;">' .
						esc_html__( 'Forms list is empty.', 'calculated-fields-form' ) .
						(
							$cff_search_form_term != '' ?
							'&nbsp;' . esc_html__( 'No forms match the search term(s)', 'calculated-fields-form' ) . '&nbsp;<b><u>' . esc_html( $cff_search_form_term ) . '</u></b>' .
							(
								$cff_current_form_category != '' ?
								'&nbsp;' . esc_html__( 'in the', 'calculated-fields-form' ) .
								'&nbsp;<b><u>' . $cff_current_form_category . '</u></b>&nbsp;' . esc_html__( 'category', 'calculated-fields-form' )
								:  ''
							) . '&nbsp;(<a href="javascript:jQuery(\'#cff-reset-forms-filter\').trigger(\'click\');">' . esc_html__( 'reset', 'calculated-fields-form' ) . '</a>)' :
							''
						) . '</td></tr>';
					}

					for ( $items_index = max(0, ( $current_page - 1 ) * $records_per_page ); $items_index < min( $current_page * $records_per_page, count( $myrows ) ); $items_index++ ) {
						$item = $myrows[ $items_index ];
						?>
						<tr>
							<td nowrap><?php echo esc_html( $item->id ); ?></td>
							<td nowrap><input type="text" name="calname_<?php echo esc_attr( $item->id ); ?>" id="calname_<?php echo esc_attr( $item->id ); ?>" value="<?php echo esc_attr( $item->form_name ); ?>" /></td>
							<td nowrap>
								<input type="button" name="calupdate_<?php echo esc_attr( $item->id ); ?>" value="<?php esc_attr_e( 'Rename', 'calculated-fields-form' ); ?>" onclick="cp_updateItem(<?php echo esc_attr( $item->id ); ?>);" class="button-secondary" />
                                <input type="button" name="calmanage_<?php echo esc_attr( $item->id ); ?>" value="<?php esc_attr_e( 'Build', 'calculated-fields-form' ); ?>" onclick="cp_manageSettings(<?php echo esc_attr( $item->id ); ?>);" class="button-primary" style="padding-left:30px;padding-right:30px" title="<?php esc_attr_e( 'Ctrl+Click to open in new tab', 'calculated-fields-form' ); ?>" />
								<input type="button" name="calmanage_<?php echo esc_attr( $item->id ); ?>" value="<?php esc_attr_e( 'Entries', 'calculated-fields-form' ); ?>" onclick="cp_viewMessages(<?php echo esc_attr( $item->id ); ?>);" class="button-secondary" />
								<input type="button" name="calclone_<?php echo esc_attr( $item->id ); ?>" value="<?php esc_attr_e( 'Duplicate', 'calculated-fields-form' ); ?>" onclick="cp_cloneItem(<?php echo esc_attr( $item->id ); ?>);" class="button-secondary" />
								<input type="button" name="caldelete_<?php echo esc_attr( $item->id ); ?>" value="<?php esc_attr_e( 'Delete', 'calculated-fields-form' ); ?>" onclick="cp_deleteItem(<?php echo esc_attr( $item->id ); ?>);" class="button-secondary cff-delete-form" />
							</td>
							<td><?php if ( ! empty( $item->category ) ) {
								print esc_html__( 'Category: ', 'calculated-fields-form' ) . '<b>' . esc_html( $item->category ) . '</b><br>';} ?><div style="white-space:nowrap;">[CP_CALCULATED_FIELDS id="<?php echo esc_attr( $item->id ); ?>"]</div></td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
        <?php
            if ( ! empty( $pages_links ) ) {
                print '<div style="text-align: right;margin-bottom: 20px;">' . $pages_links . '</div>';
            }
        ?>

        <!-- Default Settings -->
		<i id="default-settings-section"></i>
		<div id="metabox_default_settings" class="postbox cff-metabox <?php print esc_attr( $cpcff_main->metabox_status( 'metabox_default_settings' ) ); ?>" >
			<h3 class='hndle' style="padding:5px;"><span><?php esc_html_e( 'Default Settings', 'calculated-fields-form' ); ?></span></h3>
			<div class="inside">
				<p><?php esc_html_e( 'Applies the default settings to new forms.', 'calculated-fields-form' ); ?></p>
				<form name="defaultsettings" action="admin.php?page=cp_calculated_fields_form" method="post">
					<?php esc_html_e( 'Default Template', 'calculated-fields-form' ); ?>:<br />
					<?php
						require_once CP_CALCULATEDFIELDSF_BASE_PATH . '/inc/cpcff_templates.inc.php';
						$templates_list       = CPCFF_TEMPLATES::load_templates();
                        $template_options     = '<option value="none">- No Change Template -</option><option value="">Use default template</option>';
						$template_information = '';
					foreach ( $templates_list as $template_item ) {
						$template_options     .= '<option value="' . esc_attr( $template_item['prefix'] ) . '" ' . ( $template_item['prefix'] == $cp_default_template ? 'SELECTED' : '' ) . '>' . esc_html( $template_item['title'] ) . '</option>';
						$template_information .= '<div class="width50 cp_template_info cp_template_' . esc_attr( $template_item['prefix'] ) . '" style="text-align:center;padding:10px 0; display:' . ( $template_item['prefix'] == $cp_default_template ? 'block' : 'none' ) . '; margin:10px 0; border: 1px dashed #CCC;">' . ( ! empty( $template_item['thumbnail'] ) ? '<img src="' . esc_attr( $template_item['thumbnail'] ) . '"><br>' : '' ) . ( ! empty( $template_item['description'] ) ? esc_html( $template_item['description'] ) : '' ) . '</div>';
					}
					?>
					<select name="cp_default_template" id="cp_default_template"class="width50" onchange="cp_select_template();"><?php print $template_options; // phpcs:ignore WordPress.Security.EscapeOutput ?></select><br />
					<?php print $template_information; // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<br /><br />
					<div style="border:1px solid #DADADA; padding:10px;" class="width50">
						<input type="checkbox" aria-label="<?php esc_attr_e( 'Apply To Existing Forms', 'calculated-fields-form' ); ?>" name="cp_default_existing_forms" /> <?php esc_html_e( 'Apply To Existing Forms', 'calculated-fields-form' ); ?> (<i><?php esc_html_e( 'It will modify the settings of existing forms', 'calculated-fields-form' ); ?></i>)
					</div>
					<br />
					<input type="button" name="cp_save_default_settings" value="<?php esc_attr_e( 'Update', 'calculated-fields-form' ); ?>" class="button-secondary" onclick="cp_update_default_settings(this);" />
					<input type="hidden" name="_cpcff_nonce" value="<?php echo esc_attr( wp_create_nonce( 'cff-default-settings' ) ); ?>" />
				</form>
			</div>
		</div>
		<div style="border:1px solid #F0AD4E;background:#FBE6CA;padding:10px;margin:10px 0;font-size:1.3em;">
			<div><?php esc_html_e( 'For additional resources visit the plugin\'s', 'calculated-fields-form' ); ?> <a href="https://cff-bundles.dwbooster.com" target="_blank" style="font-weight:bold;"><?php esc_html_e( 'Marketplace', 'calculated-fields-form' ); ?></a></div>
			<div class="cff-bundles-plugin"></div>
		</div>
		<div id="metabox_troubleshoot_area" class="postbox cff-metabox <?php print esc_attr( $cpcff_main->metabox_status( 'metabox_troubleshoot_area' ) ); ?>" >
			<h3 class='hndle' style="padding:5px;"><span><?php esc_html_e( 'Troubleshoot Area & General Settings', 'calculated-fields-form' ); ?></span></h3>
			<div class="inside">
				<form name="updatesettings">
					<div style="border:1px solid #DADADA; padding:10px;">
						<p><?php _e( '<strong>Important!</strong>: Use this area <strong>only</strong> if you are experiencing conflicts with third party plugins, with the theme scripts or with the character encoding.', 'calculated-fields-form' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
						<?php esc_html_e( 'Script load method', 'calculated-fields-form' ); ?>:<br />
						<select id="ccscriptload" name="ccscriptload"  class="width50">
							<option value="0" <?php if ( get_option( 'CP_CFF_LOAD_SCRIPTS', '0' ) == '1' ) {
								echo 'selected';} ?>><?php esc_html_e( 'Classic (Recommended)', 'calculated-fields-form' ); ?></option>
							<option value="1" <?php if ( get_option( 'CP_CFF_LOAD_SCRIPTS', '0' ) != '1' ) {
								echo 'selected';} ?>><?php esc_html_e( 'Direct', 'calculated-fields-form' ); ?></option>
						</select><br />
						<em><?php esc_html_e( '* Change the script load method if the form doesn\'t appear in the public website.', 'calculated-fields-form' ); ?></em>
						<br /><br />
						<?php esc_html_e( 'Character encoding', 'calculated-fields-form' ); ?>:<br />
						<select id="cccharsets" name="cccharsets" class="width50">
							<option value=""><?php esc_html_e( 'Keep current charset (Recommended)', 'calculated-fields-form' ); ?></option>
							<option value="utf8_general_ci">UTF-8 (<?php esc_html_e( 'try this first', 'calculated-fields-form' ); ?>)</option>
							<option value="utf8mb4_general_ci">UTF-8mb4 (<?php esc_html_e( 'Only from MySQL 5.5', 'calculated-fields-form' ); ?>)</option>
							<option value="latin1_swedish_ci">latin1_swedish_ci</option>
						</select><br />
						<em><?php esc_html_e( '* Update the charset if you are getting problems displaying special/non-latin characters. After updated you need to edit the special characters again.', 'calculated-fields-form' ); ?></em>
						<br /><br />
						<?php
							$compatibility_warnings = $cpcff_main->compatibility_warnings();
						if ( ! empty( $compatibility_warnings ) ) {
							print '<div style="margin:10px 0; border:1px dashed #FF0000; padding:10px; color:red;">' . $compatibility_warnings; // phpcs:ignore WordPress.Security.EscapeOutput
						}
							esc_html_e( 'There is active an optimization plugin in WordPress', 'calculated-fields-form' ); ?>:<br />
						<input type="checkbox" id="ccoptimizationplugin" name="ccoptimizationplugin" value="1" <?php echo ( get_option( 'CP_CALCULATEDFIELDSF_OPTIMIZATION_PLUGIN', CP_CALCULATEDFIELDSF_OPTIMIZATION_PLUGIN ) ) ? 'CHECKED' : ''; ?> /><em><?php esc_html_e( '* Tick the checkbox if there is an optimization plugin active on the website, and the forms are not visible.', 'calculated-fields-form' ); ?></em>
						<?php
						if ( ! empty( $compatibility_warnings ) ) {
							print '</div>';
						}
						?>
					</div>
					<br />
					<input type="checkbox" name="ccdisablerevisions" id="ccdisablerevisions" <?php echo ( get_option( 'CP_CALCULATEDFIELDSF_DISABLE_REVISIONS', CP_CALCULATEDFIELDSF_DISABLE_REVISIONS ) ) ? 'CHECKED' : ''; ?> /> <?php esc_html_e( 'Disable Form Revisions', 'calculated-fields-form' ); ?>
					<br /><br />
					<input type="checkbox" name="ccjscache" id="ccjscache" <?php echo ( get_option( 'CP_CALCULATEDFIELDSF_USE_CACHE', CP_CALCULATEDFIELDSF_USE_CACHE ) ) ? 'CHECKED' : ''; ?> /> <?php esc_html_e( 'Activate Javascript Cache', 'calculated-fields-form' ); ?>
					<br /><br />
					<input type="checkbox" name="ccdirectform" id="ccdirectform" <?php echo ( get_option( 'CP_CALCULATEDFIELDSF_DIRECT_FORM_ACCESS', CP_CALCULATEDFIELDSF_DIRECT_FORM_ACCESS ) ) ? 'CHECKED' : ''; ?> /> <?php esc_html_e( 'Allows to access the forms directly', 'calculated-fields-form' ); ?>
					<br /><br />
					<input type="checkbox" name="ccampform" id="ccampform" <?php echo ( get_option( 'CP_CALCULATEDFIELDSF_AMP', CP_CALCULATEDFIELDSF_AMP ) ) ? 'CHECKED' : ''; ?> /> <?php esc_html_e( 'Allows to access the forms from amp pages', 'calculated-fields-form' ); ?>
					<br /><br />
					<input type="checkbox" name="ccexcludecrawler" id="ccexcludecrawler" <?php echo ( get_option( 'CP_CALCULATEDFIELDSF_EXCLUDE_CRAWLERS', false ) ) ? 'CHECKED' : ''; ?> /> <?php esc_html_e( 'Do not load the forms with crawlers', 'calculated-fields-form' ); ?>
					<br /><i><?php esc_html_e( '* The forms are not loaded when website is being indexed by searchers.', 'calculated-fields-form' ); ?></i>
					<br /><br />
					<input type="button" onclick="cp_updateConfig();" name="gobtn" value="<?php esc_attr_e( 'UPDATE', 'calculated-fields-form' ); ?>" class="button-secondary" />
					<br />
				</form>
			</div>
		</div>
	</div>
</div><!-- End Forms & Settings Section -->
<div style="margin-top:20px;display:<?php print ( ! empty( $_GET['cff-tab'] ) && 'marketplace' == $_GET['cff-tab'] ) ? 'block' : 'none'; ?>;"><!-- Marketplace Section -->
	<div id="metabox_basic_settings" class="postbox" >
		<h3 class='hndle' style="padding:5px;"><span><?php esc_html_e( 'Calculated Fields Form Marketplace', 'calculated-fields-form' ); ?></span></h3>
		<div class="inside">
			<div class="cff-marketplace"></div>
		</div>
	</div>
</div><!-- End Marketplace Section -->
[<a href="https://cff.dwbooster.com/customization" target="_blank"><?php esc_html_e( 'Request Custom Modifications', 'calculated-fields-form' ); ?></a>] | [<a href="https://cff.dwbooster.com/download" target="_blank"><?php esc_html_e( 'Upgrade', 'calculated-fields-form' ); ?></a>] | [<a href="https://wordpress.org/support/plugin/calculated-fields-form#new-post" target="_blank"><?php esc_html_e( 'Help', 'calculated-fields-form' ); ?></a>]
</div>
<script>cff_current_version='free';</script>
<script src="https://cff-bundles.dwbooster.com/plugins/plugins.js?v=<?php print esc_attr( CP_CALCULATEDFIELDSF_VERSION . '_' . gmdate( 'Y-m-d' ) ); // phpcs:ignore WordPress.WP.EnqueuedResources ?>"></script>
<script src="<?php print esc_attr( plugins_url( '/vendors/forms.js', CP_CALCULATEDFIELDSF_MAIN_FILE_PATH ) . '?v=' . CP_CALCULATEDFIELDSF_VERSION . '_' . gmdate( 'Y-m-d' ) ); // phpcs:ignore WordPress.WP.EnqueuedResources ?>"></script>
