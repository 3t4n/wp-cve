<?php
/*
Plugin Name: Plugin Security Checker
Plugin URI: http://blog.secupress.fr/
Description: This plugin will warn you if you're using or installing a known as vulnerable extension or removed from official repository, a security must have plugin!
Version: 2.2.1
Author: juliobox
Contributors: SecuPress
Author URI: http://blog.secupress.fr
*/

if( is_admin() && !defined('DOING_AJAX' ) && !defined('DOING_AUTOSAVE' ) ):

	define( 'SPPC_FULLNAME', 'Plugin Security Checker' );
	define( 'SPPC_SHORTNAME', 'Plugin Checker' );
	define( 'SPPC_VERSION', '2.2.0' );
	define( 'SPPC_SLUG', dirname( plugin_basename( __FILE__ ) ) );
	
	add_action( 'admin_init', 'sppc_admin_init' );
	function sppc_admin_init()
	{
		load_plugin_textdomain( 'sppc', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	}

	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'sppc_settings_action_links', PHP_INT_MAX );
	function sppc_settings_action_links( $links )
	{
	   array_push( $links, '<a href="' . admin_url( 'options-general.php?page='. SPPC_SLUG ) . '">' . __( 'Settings' ) . '</a>' );
	   array_push( $links, '<a href="' . admin_url( 'plugins.php?plugin_status=all' ) . '">' . __( 'Plugins' ) . '</a>' );
	   return $links;
	}

	add_action( 'admin_menu', 'sppc_create_menu' );
	function sppc_create_menu()
	{
		add_options_page( SPPC_FULLNAME, SPPC_SHORTNAME, 'manage_options', SPPC_SLUG, 'sppc_settings_page' );
	}
	
	function sppc_settings_page()
	{
		echo '<h3>'.__( 'This plugin doesn\'t need any settings. But now you\'re here, buy me a chocolate!', 'sppc' ).'</h3>';
		include( dirname( __FILE__ ) . '/inc/about.inc.php' );
	}

	add_action( 'load-plugins.php', 'sppc_load_plugins' );
	function sppc_load_plugins()
	{
		include_once( 'inc/vulnerables.inc.php' );
		global $sppsc_vulnerables, $sppsc_removed;
		$sppsc_removed_array = file( dirname( __FILE__ ) . '/inc/removed.inc.php', FILE_IGNORE_NEW_LINES );
		$sppsc_removed = str_replace( "\r\n", '', implode( ';', $sppsc_removed_array ) ) . ';';
		$sppsc_vulnerables_plucked = array_keys( $sppsc_vulnerables );
		$installes = array_merge( get_plugins(), get_mu_plugins() );
		$installed = array();
		foreach( $installes as $k=>$i )
			$installed[dirname($k)] = $i;
		$installed_plucked = array_map( 'dirname', array_keys( $installes ) );
		$diff_vuln = array_intersect( $sppsc_vulnerables_plucked, $installed_plucked );
		$diff_removed = array_intersect( $sppsc_removed_array, $installed_plucked );
		$sppc_plugins = array();
		foreach( $diff_vuln as $dif )
			if( version_compare( $installed[$dif]['Version'], $sppsc_vulnerables[$dif]['Version'] )<=0 && $sppsc_vulnerables[$dif]['Version']!='*' )
				$sppc_plugins[] = sprintf( __( '<strong>%s %s</strong> is known to contain vulnerabilities. <a href="%s">Go &raquo;</a>', 'sppc' ), $sppsc_vulnerables[$dif]['Name'], $sppsc_vulnerables[$dif]['Version']!='*' ? sprintf( __( 'v %s (or lower)', 'sppc' ), $sppsc_vulnerables[$dif]['Version'] ) : __( 'all versions', 'sppc' ), admin_url( 'plugins.php' ) );
		foreach( $diff_removed as $dif )
			$sppc_plugins[] = sprintf( __( 	'<strong>%s</strong> <em>(folder "%s")</em> have been removed from official repository. <a href="%s">Go &raquo;</a>', 'sppc' ), ucwords( str_replace( '-', ' ', $dif ) ), $dif, admin_url( 'plugins.php#' . $dif ) ); 
		delete_transient( 'sppc_plugins' );
		if( !empty( $sppc_plugins ) )
			set_transient( 'sppc_plugins', $sppc_plugins );
	}

	add_action( 'after_plugin_row', 'sppc_after_plugin_row', 10, 3 );
	function sppc_after_plugin_row( $plugin_file, $plugin_data, $context )
	{
		if ( ( is_network_admin() || !is_multisite() ) && 
			!current_user_can('update_plugins') && !current_user_can('delete_plugins') && !current_user_can('activate_plugins') ) // ie. Administrator
			return; 
		global $sppsc_vulnerables, $sppsc_removed;
		$plugin_file_dn = dirname( $plugin_file );
		$is_removed = $is_vuln = false;
		if( $is_removed = !strpos( $sppsc_removed, ';'.$plugin_file_dn.';' ) &&	$is_vuln = !isset( $sppsc_vulnerables[$plugin_file_dn] ) )
			return;
		if( version_compare( $plugin_data['Version'], $sppsc_vulnerables[$plugin_file_dn]['Version'] )==1 && $sppsc_vulnerables[$plugin_file_dn]['Version']!='*' )
			return;
		$wp_list_table = _get_list_table('WP_Plugins_List_Table'); 
		$page = get_query_var( 'paged' );
		$s = isset( $_REQUEST['s'] ) ? esc_attr( stripslashes( $_REQUEST['s'] ) ) : '';
		$current = get_site_transient( 'update_plugins' );
		$r = isset( $current->response[ $plugin_file ] ) ? $current->response[ $plugin_file ] : null;
		?><tr style="background-color: #f88;" class="sppc"><td colspan="<?php echo $wp_list_table->get_column_count(); ?>"><?php
		if( isset( $sppsc_vulnerables[$plugin_file_dn] ) ):
			$sppsc_vulnerables[$plugin_file_dn]['Flaws'] = array_map( create_function('$f', 'return "<a href=\"http://secu.boiteaweb.fr/mylab/glossaire/?mot=$f\" target=\"_blank\">$f</a>";' ), $sppsc_vulnerables[$plugin_file_dn]['Flaws'] );
			$sin = __( '<strong>%s %s</strong> is known to contain this vulnerability: %s.', 'sppc' );
			$plu = __( '<strong>%s %s</strong> is known to contain these vulnerabilities: %s.', 'sppc' );
			printf( _n(	$sin, $plu, count( $sppsc_vulnerables[$plugin_file_dn]['Flaws'] ), 'sppc' ), 
						$sppsc_vulnerables[$plugin_file_dn]['Name'], 
						$sppsc_vulnerables[$plugin_file_dn]['Version']!='*' ? sprintf( __( 'v %s (or lower)', 'sppc' ), $sppsc_vulnerables[$plugin_file_dn]['Version'] ) : __( 'all versions', 'sppc' ), 
						wp_sprintf( '%l', $sppsc_vulnerables[$plugin_file_dn]['Flaws'] )
					); 
			echo '</p>';
			if ( $sppsc_vulnerables[$plugin_file_dn]['Patch'] && current_user_can('update_plugins') ):
				echo '<p><img src="' . admin_url( 'images/yes.png' ) . '" /> ';
				if ( !empty($r->package) )
						printf( __( 'We invite you to <a href="%s">Update</a> this plugin to its last version (minimum: v %s).', 'sppc' ), 
							wp_nonce_url( admin_url('update.php?action=upgrade-plugin&plugin=') . $plugin_file, 'upgrade-plugin_' . $plugin_file ),
							$sppsc_vulnerables[$plugin_file_dn]['Patch']
						);
				else
						printf( __( 'We invite you to Update this plugin <em>(Automatic update is unavailable for this plugin.)</em>.', 'sppc' ), 
							wp_nonce_url( admin_url('update.php?action=upgrade-plugin&plugin=') . $plugin_file, 'upgrade-plugin_' . $plugin_file )
						);
	 
			else:
				echo '<p><img src="' . admin_url( 'images/yes.png' ) . '" /> ';
				if( is_plugin_active( $plugin_file ) && ( !$sppsc_vulnerables[$plugin_file_dn]['Patch'] || current_user_can('activate_plugins') ) )
					printf(  __( 'We invite you to <a href="%s">Deactivate</a> this plugin, then delete it.', 'sppc' ), 
							wp_nonce_url( admin_url( 'plugins.php?action=deactivate&plugin=' . $plugin_file . '&plugin_status=' . $context . '&paged=' . $page . '&s=' . $s ), 'deactivate-plugin_' . $plugin_file )
						);
				if( !is_plugin_active( $plugin_file ) && ( !$sppsc_vulnerables[$plugin_file_dn]['Patch'] || current_user_can('delete_plugins') ) )
					printf( __( 'We invite you to <a href="%s">Delete</a> this plugin, no patch has been made by its author.', 'sppc' ), 
							wp_nonce_url( admin_url( 'plugins.php?action=delete-selected&amp;checked[]=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s ), 'bulk-plugins' )
						);
			endif;
			echo '</p><p><img src="' . admin_url( 'images/yes.png' ) . '" /> ' . __( 'Get more info visiting this security sites: ', 'sppc' );
			$sppsc_vulnerables[$plugin_file_dn]['Links'] = array_filter( $sppsc_vulnerables[$plugin_file_dn]['Links'] );
			foreach( $sppsc_vulnerables[$plugin_file_dn]['Links'] as $k=>$link )
				$sppsc_vulnerables[$plugin_file_dn]['Links'][$k] = sprintf( '<a href="%s" target="_blank">%s</a>', $link, str_replace('_',' ',$k) );
			echo wp_sprintf( '%l', $sppsc_vulnerables[$plugin_file_dn]['Links'] );
			echo '</p>';
		elseif( !$is_removed ):
			printf( __( 	'<strong>%s</strong> have been removed from official repository for one of these reasons: Security Flaw, Author\'s demand, Not GPL, Another author\'s plugin is under investigation, this plugin is under investigation.', 'sppc' ), $plugin_data['Name'] ); 
				echo '<p><img src="' . admin_url( 'images/yes.png' ) . '" /> ';
				if( is_plugin_active( $plugin_file ) && current_user_can('activate_plugins') )
					printf(  __( 'We invite you to <a href="%s">Deactivate</a> this plugin, then delete it.<p>', 'sppc' ), 
							wp_nonce_url( admin_url( 'plugins.php?action=deactivate&plugin=' . $plugin_file . '&plugin_status=' . $context . '&paged=' . $page . '&s=' . $s ), 'deactivate-plugin_' . $plugin_file )
						);
				if( !is_plugin_active( $plugin_file ) && current_user_can('delete_plugins') )
					printf( __( 'We invite you to <a href="%s">Delete</a> this plugin, no patch has been made by its author.<p>', 'sppc' ), 
							wp_nonce_url( admin_url( 'plugins.php?action=delete-selected&amp;checked[]=' . $plugin_file . '&amp;plugin_status=' . $context . '&amp;paged=' . $page . '&amp;s=' . $s ), 'bulk-plugins' )
						);
		endif;
		?></td></tr><?php
	}


	add_action( 'admin_notices', 'sppc_admin_notices' );
	function sppc_admin_notices()
	{
		global $sppsc_vulnerables;
		if( ( !is_network_admin() || is_multisite() ) && 
			!current_user_can('update_plugins') && !current_user_can('delete_plugins') && !current_user_can('activate_plugins') // ie. Administrator
		) 
			return; 
		if( $vp = array_filter( (array)get_transient( 'sppc_plugins' ) ) ):
			$vp = implode( '<br><img src="' . admin_url( 'images/no.png' ) . '" /> ', $vp );
			echo '<div id="message" class="error"><p><strong><a href="'.admin_url( 'plugins.php' ).'">'.SPPC_FULLNAME.'</a>: '.__( 'Security Warning!', 'sppc' ).'</strong></p><p><img src="' . admin_url( 'images/no.png' ) . '" /> '.$vp.'</p></div>';
		else:
			include_once( 'inc/vulnerables.inc.php' );
			global $pagenow;
			$sppsc_removed = file( dirname( __FILE__ ) . '/inc/removed.inc.php', FILE_IGNORE_NEW_LINES );
			// $sppsc_removed = str_replace( "\r\n", '', implode( "\r\n", $sppsc_removed_array ) ) . ';';
			$nb = count( $sppsc_vulnerables ) + count( $sppsc_removed );
			if( $pagenow=='plugins.php' && !isset( $_GET['page'] ) )
				echo '<div id="message" class="updated"><p><strong>'.SPPC_FULLNAME.'</strong>: '.sprintf( __( '%s plugins in internal DB : No Security Warning, good!', 'sppc' ), $nb ).'</strong></p></div>';
		endif;
	}
	
	// add_action( 'plugin_row_meta', 'sppc_plugin_row_meta', PHP_INT_MAX, 2 ); // Future ;)
	function sppc_plugin_row_meta( $plugin_meta, $plugin_file )
	{
		$sppsc_verified = str_replace( "\r\n", '', implode( ';', file( dirname( __FILE__ ) . '/inc/verified.inc.php', FILE_IGNORE_NEW_LINES ) ) ) . ';';
		if( strpos( $sppsc_verified, ';' . dirname( $plugin_file ) . ';' ) )
			array_push( $plugin_meta, '<a href="http://' . __( 'www.secupress.com', 'sppc' ) . '/?plugin='.md5( dirname( $plugin_file ) ).'"><span style="color:green;">&#10004;</span> ' . __( 'Verified by SecuPress.com', 'sppc' ) . '</a>' );
		return $plugin_meta;
	}

endif;