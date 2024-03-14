<?php
/* wppa-adminbar.php
* Package: wp-photo-album-plus
*
* enhances the admin bar with wppa+ menu
* Version 8.4.03.001
*
*/

add_action( 'admin_bar_menu', 'wppa_admin_bar_menu', 97 );

function wppa_admin_bar_menu() {
	global $wp_admin_bar;
	global $wpdb;

	$wppaplus = 'wppa-admin-bar';

	$menu_items = array();

	// Pending comments
	$com_pend = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_comments WHERE status = 'pending'" );
	if ( $com_pend ) $com_pending = '&nbsp;<span id="ab-awaiting-mod" class="pending-count">'.$com_pend.'</span>';
	else $com_pending = '';

	// Pending uploads
	$upl_pend = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->wppa_photos WHERE status = 'pending' AND album > 0" );
	if ( $upl_pend ) $upl_pending = '&nbsp;<span id="ab-awaiting-mod" class="pending-count">'.$upl_pend.'</span>';
	else $upl_pending = '';

	// Tot
	$tot_pend = '0';
	if ( current_user_can('administrator') ) $tot_pend += $com_pend;
	if ( current_user_can('wppa_admin') ) $tot_pend += $upl_pend;
	if ( $tot_pend ) $tot_pending = '&nbsp;<span id="ab-awaiting-mod" class="pending-count">'.$tot_pend.'</span>';
	else $tot_pending = '';

	if ( current_user_can( 'wppa_admin' ) ) {
		$menu_items['admin'] = array(
			'parent' => $wppaplus,
			'title'  => _x( 'Albums', 'menu-item', 'wp-photo-album-plus' ) . $upl_pending,
			'href'   => admin_url( 'admin.php?page=wppa_admin_menu' )
		);
	}
	if ( current_user_can( 'wppa_upload' ) ) {
		$menu_items['upload'] = array(
			'parent' => $wppaplus,
			'title'  => _x( 'Upload', 'menu-item', 'wp-photo-album-plus' ),
			'href'   => admin_url( 'admin.php?page=wppa_upload_photos' )
		);
		if ( ! current_user_can( 'wppa_admin' ) && wppa_opt( 'upload_edit' ) != 'none' ) {
			$menu_items['edit'] = array(
				'parent' => $wppaplus,
				'title'  => _x( 'Edit', 'menu-item', 'wp-photo-album-plus' ),
				'href'   => admin_url( 'admin.php?page=wppa_edit_photo' )
			);
		}
	}
	if ( current_user_can( 'wppa_import' ) ) {
		$menu_items['import'] = array(
			'parent' => $wppaplus,
			'title'  => _x( 'Import', 'menu-item', 'wp-photo-album-plus' ),
			'href'   => admin_url( 'admin.php?page=wppa_import_photos' )
		);
	}
	if ( current_user_can( 'wppa_export' ) ) {
		$menu_items['export'] = array(
			'parent' => $wppaplus,
			'title'  => _x( 'Export', 'menu-item', 'wp-photo-album-plus' ),
			'href'   => admin_url( 'admin.php?page=wppa_export_photos' )
		);
	}
	if ( current_user_can( 'wppa_comments' ) && wppa_switch( 'show_comments' ) ) {
		$menu_items['comments'] = array(
			'parent' => $wppaplus,
			'title'  => _x( 'Comments', 'menu-item', 'wp-photo-album-plus' ),
			'href'   => admin_url( 'admin.php?page=wppa_manage_comments' )
		);
	}
	if ( current_user_can( 'wppa_moderate' ) && $upl_pending ) {
		$menu_items['moderate-uploads'] = array(
			'parent' => $wppaplus,
			'title'	 => _x( 'Moderate uploads', 'menu-item', 'wp-photo-album-plus' ) . $upl_pending,
			'href'   => admin_url( 'admin.php?page=wppa_moderate_photos' )
		);
	}
	if ( current_user_can( 'wppa_moderate' ) && $com_pending ) {
		$menu_items['moderate-comments'] = array(
			'parent' => $wppaplus,
			'title'	 => _x( 'Moderate comments', 'menu-item', 'wp-photo-album-plus' ) . $com_pending,
			'href'   => admin_url( 'admin.php?page=wppa_moderate_comments' )
		);
	}
	if ( current_user_can( 'wppa_settings' ) ) {
		$menu_items['settings'] = array(
			'parent' => $wppaplus,
			'title'  => _x( 'Settings', 'menu-item', 'wp-photo-album-plus' ),
			'href'   => admin_url( 'admin.php?page=wppa_options' )
		);
	}
	if ( current_user_can( 'wppa_admin' ) && wppa_switch( 'opt_menu_search' ) ) {
		$menu_items['search'] = array(
			'parent' => $wppaplus,
			'title'  => _x( 'Search', 'menu-item', 'wp-photo-album-plus' ),
			'href'   => admin_url( 'admin.php?page=wppa_search' )
		);
	}

	if ( current_user_can( 'wppa_edit_tags' ) && wppa_switch( 'opt_menu_edit_tags' ) ) {
		$menu_items['edit_tags'] = array(
			'parent' => $wppaplus,
			'title'	 => _x( 'Tags', 'menu-item', 'wp-photo-album-plus' ),
			'href'   => admin_url( 'admin.php?page=wppa_edit_tags' )
		);
	}
	if ( current_user_can( 'wppa_edit_sequence' ) && wppa_switch( 'opt_menu_edit_sequence' ) ) {
		$menu_items['edit_squence'] = array(
			'parent' => $wppaplus,
			'title'  => _x( 'Sequence', 'menu-item', 'wp-photo-album-plus' ),
			'href'   => admin_url( 'admin.php?page=wppa_edit_sequence' )
		);
	}
	if ( current_user_can( 'wppa_edit_email' ) && wppa_switch( 'opt_menu_edit_email' ) ) {
		$menu_items['edit_email'] = array(
			'parent' => $wppaplus,
			'title'  => _x( 'Email', 'menu-item', 'wp-photo-album-plus' ),
			'href'   => admin_url( 'admin.php?page=wppa_edit_email' )
		);
	}

	$menu_items['opajaap'] = array(
		'parent' => $wppaplus,
		'title'  => _x( 'Documentation', 'menu-item', 'wp-photo-album-plus' ),
		'href'   => 'https://wppa.nl'
	);

	if ( current_user_can( 'administrator' ) ) {
		if ( wppa_get_option( 'wppa_logfile_on_menu' ) == 'yes' ) {
			$menu_items['logfile'] = array(
				'parent' => $wppaplus,
				'title'  => _x( 'Logfile', 'menu-item', 'wp-photo-album-plus' ),
				'href'   => admin_url( 'admin.php?page=wppa_log' )
			);
		}
	}

	if ( current_user_can( 'administrator' ) ) {
		$hits = wppa_get_option( 'wppa_cache_hits', '0' );
		$miss = wppa_get_option( 'wppa_cache_misses', '1' );
		$perc = sprintf( '%5.2f', 100 * $hits / ( $hits + $miss ) );
		$menu_items['cache'] = array(
			'parent' => $wppaplus,
			'title'  => _x( 'Cache', 'menu-item', 'wp-photo-album-plus' ) . ' ' . $perc . '%',
			'href'   => admin_url( 'admin.php?page=wppa_cache' )
		);
	}

	// Add top-level item
	$wp_admin_bar->add_menu( array(
		'id'    => $wppaplus,
		'title' => _x( 'Photo Albums', 'menu-item', 'wp-photo-album-plus' ) . $tot_pending,
		'href'  => ''
	) );

	// Loop through menu items
	if ( $menu_items ) foreach ( $menu_items as $id => $menu_item ) {

		// Add in item ID
		$menu_item['id'] = 'wppa-' . $id;

		// Add meta target to each item where it's not already set, so links open in new tab
		if ( ! isset( $menu_item['meta']['target'] ) )
			$menu_item['meta']['target'] = '_self';

		// Add class to links that open up in a new tab
		if ( '_blank' === $menu_item['meta']['target'] ) {
			if ( ! isset( $menu_item['meta']['class'] ) )
				$menu_item['meta']['class'] = '';
			$menu_item['meta']['class'] .= 'wppa-' . 'new-tab';
		}

		// Add item
		$wp_admin_bar->add_menu( $menu_item );
	}

	// Add New -> Photo Album
	if ( current_user_can( 'wppa_admin' ) ) {

		$menu_item = array( 'id' 		=> 'wppa-album-new',
							'parent' 	=> 'new-content-default',
							'title' 	=> __( 'Album', 'wp-photo-album-plus' ),
							'href' 		=> admin_url( 'admin.php?page=wppa_admin_menu&tab=edit&edit-id=new&wppa-nonce=' . wp_create_nonce( 'wppa-nonce' ) ),
							);
		// Add item
		$wp_admin_bar->add_menu( $menu_item );
	}
}