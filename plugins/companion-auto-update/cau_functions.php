<?php

// What user rights can edit plugin settings?
function cau_allowed_user_rights_array() {

	global $wpdb;

	$allowed_roles[] 	= 'administrator';
	$table_name 		= $wpdb->prefix.'auto_updates'; 
	$cau_configs 		= $wpdb->get_results( "SELECT name, onoroff FROM {$table_name} WHERE name = 'allow_editor' OR name = 'allow_author'" );

	foreach ( $cau_configs as $config ) {
		if( $config->onoroff == 'on' ) $allowed_roles[] = str_replace( "allow_", "", $config->name );
	}

	return $allowed_roles;

}

// What user rights can edit plugin settings? TRUE/FALSE
function cau_allowed_user_rights() {
	$user 			= wp_get_current_user(); // Current user
	$allowed_roles 	= cau_allowed_user_rights_array(); // Allow roles
	return array_intersect( $allowed_roles, $user->roles ) ? true : false;
}

// Get database value
function cau_get_db_value( $name, $table = 'auto_updates' ) {
	global $wpdb;
	$table_name 	= $wpdb->prefix.$table; 
	$cau_configs 	= $wpdb->get_results( $wpdb->prepare( "SELECT onoroff FROM {$table_name} WHERE name = '%s'", $name ) );
	foreach ( $cau_configs as $config ) return $config->onoroff;
}

// Get database value
function cau_get_plugininfo( $check, $field ) {
	global $wpdb;
	$table_name 	= $wpdb->prefix.'update_log'; 
	$cau_configs 	= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE slug = '%s'", $check ) );
	foreach ( $cau_configs as $config ) return $config->$field;
}

// Get the set timezone
function cau_get_proper_timezone() {
	return ( wp_timezone_string() == '+00:00' ) ? 'UTC' : wp_timezone_string();
}

// List of incompatible plugins
function cau_incompatiblePluginlist() {

	// Pluginlist, write as Plugin path => Issue
	$pluginList = array( 
		'better-wp-security/better-wp-security.php' => "<span class='cau_disabled'><span class='dashicons dashicons-no'></span></span> May block auto-updating for everything.", 
		'updraftplus/updraftplus.php' 				=> "<span class='cau_warning'><span class='dashicons dashicons-warning'></span></span> By default this plugin will not be auto-updated. You'll have to do this manually or enable auto-updating in the settings. <u>Causes no issues with other plugins.</u>"
	);

	return $pluginList;

}
function cau_incompatiblePlugins() {

	$return	= false;

	foreach ( cau_incompatiblePluginlist() as $key => $value ) {
		if( function_exists( 'is_plugin_active' ) && is_plugin_active( $key ) ) {
			$return = true;
		}
	}

	return $return;

}

// Check if has issues
function cau_pluginHasIssues() {
	return ( cau_pluginIssueCount() > 0 ) ? true : false;
}
function cau_pluginIssueLevels() {
	return checkAutomaticUpdaterDisabled() ? 'high' : 'low';
}
function cau_pluginIssueCount() {
	
	$count = 0;

	// blog_public check
	if( get_option( 'blog_public' ) == 0 ) $count++;

	// checkAutomaticUpdaterDisabled
	if( checkAutomaticUpdaterDisabled() ) $count++;

	// checkCronjobsDisabled
	if( checkCronjobsDisabled() ) $count++;

	// cau_incorrectDatabaseVersion
	if( cau_incorrectDatabaseVersion() ) $count++;

	// cau_incompatiblePlugins
	if( cau_incompatiblePlugins() ) {
		foreach ( cau_incompatiblePluginlist() as $key => $value ) {
			if( function_exists( 'is_plugin_active' ) && is_plugin_active( $key ) ) {
				$count++;
			}
		}
	}

	return $count;
}
function cau_incorrectDatabaseVersion() {
	return ( get_option( "cau_db_version" ) != cau_db_version() ) ? true : false;
}

// Run custom hooks on plugin update
function cau_run_custom_hooks_p() {

	// Check if function exists
	if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

	// Create array
	$allDates 		= array();

	// Where to look for plugins
	$dirr    		= plugin_dir_path( __DIR__ );
	$listOfAll 		= get_plugins();

	// Number of updates
	$totalNum 		= 0;

	// Loop trough all plugins
	foreach ( $listOfAll as $key => $value ) {

		// Get data
		$fullPath 		= $dirr.'/'.$key;
		$fileDate 		= date ( 'YmdHi', filemtime( $fullPath ) );
		$fileTime 		= date ( 'Hi', filemtime( $fullPath ) );
		$update_time 	= wp_next_scheduled( 'wp_update_plugins' );
		$range_start 	= date( 'Hi', strtotime( '-30 minutes', $update_time ) );
		$range_end 		= date( 'Hi', strtotime( '+30 minutes', $update_time ) );

		// Check when the last update was
		switch( wp_get_schedule( 'wp_update_plugins' ) ) {
			case 'hourly':
				$lastday 	= date( 'YmdHi', strtotime( '-1 hour', time() ) );
				break;
			case 'twicedaily':
				$lastday 	= date( 'YmdHi', strtotime( '-12 hour', time() ) );
				break;
			case 'daily':
				$lastday 	= date( 'YmdHi', strtotime( '-1 day', time() ) );
				break;
			case 'weekly':
				$lastday 	= date( 'YmdHi', strtotime( '-1 week', time() ) );
				break;
			case 'monthly':
				$lastday 	= date( 'YmdHi', strtotime( '-1 month', time() ) );
				break;
			default:
				$lastday 	= date( 'YmdHi', strtotime( '-1 hour', time() ) );
				break;
		}

		if( $fileDate >= $lastday ) {
			array_push( $allDates, $fileDate );
			$status = ( $fileTime > $range_start && $fileTime < $range_end ) ? __( 'Automatic', 'companion-auto-update' ) : __( 'Manual', 'companion-auto-update' );
			$totalNum++;
			cau_updatePluginInformation( $key, $status );
		}

	}

	// If there have been plugin updates run hook
	if( $totalNum >= 1 ) {
		do_action( 'cau_after_plugin_update' );
	}

}

// Run custom hooks on theme update
function cau_run_custom_hooks_t() {

	$allDates 	= array();
	$totalNum 	= 0;
	$dirr    	= get_theme_root();
	$listOfAll 	= wp_get_themes();

	// Loop trough all plugins
	foreach ( $listOfAll as $key => $value) {

		// Get data
		$fullPath 		= $dirr.'/'.$key;
		$fileDate 		= date ( 'YmdHi', filemtime( $fullPath ) );
		$fileTime 		= date ( 'Hi', filemtime( $fullPath ) );
		$update_time 	= wp_next_scheduled( 'wp_update_themes' );
		$range_start 	= date( 'Hi', strtotime( '-30 minutes', $update_time ) );
		$range_end 		= date( 'Hi', strtotime( '+30 minutes', $update_time ) );

		// Check when the last update was
		switch( wp_get_schedule( 'wp_update_themes' ) ) {
			case 'hourly':
				$lastday 	= date( 'YmdHi', strtotime( '-1 hour', time() ) );
				break;
			case 'twicedaily':
				$lastday 	= date( 'YmdHi', strtotime( '-12 hour', time() ) );
				break;
			case 'daily':
				$lastday 	= date( 'YmdHi', strtotime( '-1 day', time() ) );
				break;
			case 'weekly':
				$lastday 	= date( 'YmdHi', strtotime( '-1 week', time() ) );
				break;
			case 'monthly':
				$lastday 	= date( 'YmdHi', strtotime( '-1 month', time() ) );
				break;
			default:
				$lastday 	= date( 'YmdHi', strtotime( '-1 hour', time() ) );
				break;
		}

		if( $fileDate >= $lastday ) {
			array_push( $allDates, $fileDate );
			$status = ( $fileTime > $range_start && $fileTime < $range_end ) ? __( 'Automatic', 'companion-auto-update' ) : __( 'Manual', 'companion-auto-update' );
			$totalNum++;
			cau_updatePluginInformation( $key, $status );
		}

	}

	// Count number of updated plugins
	foreach ( $allDates as $key => $value ) $totalNum++;

	// If there have been plugin updates run hook
	if( $totalNum > 0 ) {
		do_action( 'cau_after_theme_update' );
	}

}

// Run custom hooks on core update
function cau_run_custom_hooks_c() {

	$totalNum 		= 0;
	$fullPath 		= ABSPATH.'wp-includes/version.php';
	$fileDate 		= date ( 'YmdHi', filemtime( $fullPath ) );
	$update_time 	= wp_next_scheduled( 'wp_version_check' );
	$range_start 	= date( 'Hi', strtotime( '-30 minutes', $update_time ) );
	$range_end 		= date( 'Hi', strtotime( '+30 minutes', $update_time ) );

	// Check when the last update was
	switch( wp_get_schedule( 'wp_version_check' ) ) {
		case 'hourly':
			$lastday 	= date( 'YmdHi', strtotime( '-1 hour', time() ) );
			break;
		case 'twicedaily':
			$lastday 	= date( 'YmdHi', strtotime( '-12 hour', time() ) );
			break;
		case 'daily':
			$lastday 	= date( 'YmdHi', strtotime( '-1 day', time() ) );
			break;
		case 'weekly':
			$lastday 	= date( 'YmdHi', strtotime( '-1 week', time() ) );
			break;
		case 'monthly':
			$lastday 	= date( 'YmdHi', strtotime( '-1 month', time() ) );
			break;
		default:
			$lastday 	= date( 'YmdHi', strtotime( '-1 hour', time() ) );
			break;
	}

	if( $fileDate >= $lastday ) {
		$status = ( $fileTime > $range_start && $fileTime < $range_end ) ? __( 'Automatic', 'companion-auto-update' ) : __( 'Manual', 'companion-auto-update' );
		$totalNum++;
		cau_updatePluginInformation( 'core', $status );
	}

	// If there have been plugin updates run hook
	if( $totalNum > 0 ) {
		do_action( 'cau_after_core_update' );
	}

}

// Check if automatic updating is disabled globally
function checkAutomaticUpdaterDisabled() {

	// I mean, I know this can be done waaaay better but I's quite late and I need to push a fix so take it or leave it untill I decide to fix this :)
	if ( defined( 'automatic_updater_disabled' ) ) {
		return ( doing_filter( 'automatic_updater_disabled' ) OR in_array( constant( 'automatic_updater_disabled' ), array( 'true', 'minor' )  ) ) ? true : false;

	} else if ( defined( 'AUTOMATIC_UPDATER_DISABLED' ) ) {
		return ( doing_filter( 'AUTOMATIC_UPDATER_DISABLED' ) OR in_array( constant( 'AUTOMATIC_UPDATER_DISABLED' ), array( 'true', 'minor' )  ) ) ? true : false;

	} else {
		return false;
	}

}

// Check if cronjobs are disabled
function checkCronjobsDisabled() {
	return ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) ? true : false;
}

// Menu location
function cau_menloc( $after = '' ) {
	return 'tools.php'.$after;
}
function cau_url( $tab = '' ) {
	return admin_url( cau_menloc( '?page=cau-settings&tab='.$tab ) );
}

// Get the active tab
function active_tab( $page, $identifier = 'tab' ) {
	echo _active_tab( $page, $identifier );
}
function _active_tab( $page, $identifier = 'tab' ) {
	$cur_page = !isset( $_GET[ $identifier ] ) ? '' : $_GET[ $identifier ];
	if( $page == $cur_page ) {
		return 'nav-tab-active';
	}
}

// Get the active subtab
function active_subtab( $page, $identifier = 'tab' ) {
	$cur_page = !isset( $_GET[ $identifier ] ) ? '' : $_GET[ $identifier ];
	if( $page == $cur_page ) {
		echo 'current';
	}
}

// List of plugins that should not be updated
function donotupdatelist( $filter = 'plugins' ) {

	global $wpdb;

	$db_table 		= ( $filter == 'themes' ) ? 'notUpdateListTh' : 'notUpdateList';
	$table_name 	= $wpdb->prefix."auto_updates"; 
	$config 		= $wpdb->get_results( "SELECT * FROM {$table_name} WHERE name = '{$db_table}'");

	$list 			= explode( ", ", $config[0]->onoroff );
	$returnList 	= array();

	foreach ( $list as $key ) array_push( $returnList, $key );
	
	return $returnList;

}
function plugins_donotupdatelist() {

	$array = array();

	// Filtered plugins
	foreach ( donotupdatelist( 'plugins' ) as $filteredplugin ) {
		array_push( $array, $filteredplugin );
	}

	// Plugin added to the delay list
	foreach ( cau_delayed_updates__formated() as $delayedplugin ) {
		array_push( $array, $delayedplugin );
	}

	return $array;

}
function themes_donotupdatelist() {
	return donotupdatelist( 'themes' );
}

// Show the update log
function cau_fetch_log( $limiter, $format = 'simple' ) {

	global $wpdb;

	$updateLog 			= "update_log"; 
	$updateLogDB 		= $wpdb->prefix.$updateLog;
	$filter 			= isset( $_GET['filter'] ) ? $_GET['filter'] : 'all';
	$dateFormat 		= get_option( 'date_format' );
	$dateToday 			= date ( 'ydm' );
	$log_items 			= array();
	$limit 				= ( $limiter != 'all' ) ? $limiter : false;

	$show_plugins 		= ( in_array( $filter, array( 'plugins', 'all' ) ) ) ? true : false;
	$show_themes 		= ( in_array( $filter, array( 'themes', 'all' ) ) ) ? true : false;
	$show_core 			= ( $filter == 'all' ) ? true : false;
	$show_translations 	= ( $filter == 'translations' ) ? true : false;

	// PLUGINS
	if( $show_plugins ) {	

		// Make sure some required functions exits
		if ( !function_exists( 'get_plugins' ) ) {
	        require_once ABSPATH . 'wp-admin/includes/plugin.php';
	    }

		// Loop trough all plugins
		foreach ( get_plugins() as $key => $value ) {

			// Get data
			$fullPath 						= plugin_dir_path( __DIR__ ).'/'.$key;
			$pluginData 					= get_plugin_data( $fullPath );
			$pluginSlug 					= explode( '/', plugin_basename( $key ) );
			$pluginSlug						= $pluginSlug[0];

			$fileTime 						= date( 'Hi', filemtime( $fullPath ) );
			$fileDate 						= date( 'ydm', filemtime( $fullPath ) );
			$fileDateTime 					= strtotime( $fileDate );
			$updateSched 					= wp_next_scheduled( 'wp_update_plugins' );

			if( $dateToday == $fileDate ) {
				$method = ( $fileTime > date( 'Hi', strtotime( '-30 minutes', $updateSched ) ) && $fileTime < date( 'Hi', strtotime( '+30 minutes', $updateSched ) ) ) ? __( 'Automatic', 'companion-auto-update' ) : __( 'Manual', 'companion-auto-update' );
			} else {
				$method = cau_check_if_exists( $key, 'slug', $updateLog ) ? cau_get_plugininfo( $key, 'method' ) : '-';
			}

			$log_items[$fileDateTime.'_'.$pluginSlug] = array(
				'type' 		=> 'Plugin',
				'slug'		=> $pluginSlug,
				'name'		=> $pluginData['Name'],
				'date'		=> $fileDateTime,
				'version'	=> $pluginData['Version'],
				'method'	=> $method,
			);

		}

	}

	// THEMES
	if( $show_themes ) {

		// Loop trough all themes
		foreach ( wp_get_themes() as $key => $value ) {

			// Get data
			$fullPath 						= get_theme_root().'/'.$key;
			$path_parts 					= pathinfo( $fullPath );
			$theme_data 					= wp_get_theme( $path_parts['filename'] );

			$fileTime 						= date( 'Hi', filemtime( $fullPath ) );
			$fileDate 						= date( 'ydm', filemtime( $fullPath ) );
			$fileDateTime 					= strtotime( $fileDate );
			$updateSched 					= wp_next_scheduled( 'wp_update_themes' );

			if( $dateToday == $fileDate ) {
				$method = ( $fileTime > date( 'Hi', strtotime( '-30 minutes', $updateSched ) ) && $fileTime < date( 'Hi', strtotime( '+30 minutes', $updateSched ) ) ) ? __( 'Automatic', 'companion-auto-update' ) : __( 'Manual', 'companion-auto-update' );
			} else {
				$method = cau_check_if_exists( $key, 'slug', $updateLog ) ? cau_get_plugininfo( $key, 'method' ) : '-';
			}

			$log_items[$fileDateTime.'_'.$key] = array(
				'type' 		=> 'Theme',
				'slug'		=> '',
				'name'		=> $theme_data->get( 'Name' ),
				'date'		=> $fileDateTime,
				'version'	=> $theme_data->get( 'Version' ),
				'method'	=> $method,
			);

		}

	}

	// TRANSLATIONS
	if( $show_translations ) {

		$transFolder = get_home_path() . 'wp-content/languages'; // There is no way (at this time) to check if someone changed this link, so therefore it won't work when it's changed, sorry
		if( file_exists( $transFolder ) ) {

			// Plugin translations
			$files = glob( $transFolder.'/plugins/*.{mo}', GLOB_BRACE );
			foreach( $files as $file ) {

				$fileDateTime 	= strtotime( date( 'YmdHi', filemtime( $file ) ) );
				$bn 			= basename( $file );

				$log_items[$fileDateTime.'_'.$bn] = array(
					'type' 		=> __( 'Plugin translations', 'companion-auto-update' ),
					'slug'		=> '',
					'name'		=> str_replace( ".json", "", str_replace( ".mo", "", str_replace( "-", " ", $bn ) ) ),
					'date'		=> $fileDateTime,
					'version'	=> '',
					'method'	=> '',
				);

			}

			// Theme translations
			$files = glob( $transFolder.'/themes/*.{mo}', GLOB_BRACE );
			foreach( $files as $file ) {

				$fileDateTime 	= strtotime( date( 'YmdHi', filemtime( $file ) ) );
				$bn 			= basename( $file );

				$log_items[$fileDateTime.'_'.$bn] = array(
					'type' 		=> __( 'Theme translations', 'companion-auto-update' ),
					'slug'		=> '',
					'name'		=> str_replace( ".json", "", str_replace( ".mo", "", str_replace( "-", " ", $bn ) ) ),
					'date'		=> $fileDateTime,
					'version'	=> '',
					'method'	=> '',
				);

			}

			// Core translations
			$files = glob( $transFolder.'/*.{mo}', GLOB_BRACE );
			foreach( $files as $file ) {

				$fileDateTime 	= strtotime( date( 'YmdHi', filemtime( $file ) ) );
				$bn 			= basename( $file );

				$log_items[$fileDateTime.'_'.$bn] = array(
					'type' 		=> __( 'Core translations', 'companion-auto-update' ),
					'slug'		=> '',
					'name'		=> str_replace( ".json", "", str_replace( ".mo", "", str_replace( "-", " ", $bn ) ) ),
					'date'		=> $fileDateTime,
					'version'	=> '',
					'method'	=> '',
				);

			}

		}

	}

	// CORE
	if( $show_core ) {

		$coreFile = ABSPATH . 'wp-includes/version.php';
		if( file_exists( $coreFile ) ) {

			$fileTime 			= date( 'Hi', filemtime( $coreFile ) );
			$fileDate 			= date( 'ydm', filemtime( $coreFile ) );
			$fileDateTime 		= strtotime( $fileDate );
			$updateSched 		= wp_next_scheduled( 'wp_version_check' );

			if( $dateToday == $fileDate ) {
				$method = ( $fileTime > date( 'Hi', strtotime( '-30 minutes', $updateSched ) ) && $fileTime < date( 'Hi', strtotime( '+30 minutes', $updateSched ) ) ) ? __( 'Automatic', 'companion-auto-update' ) : __( 'Manual', 'companion-auto-update' );
			} else {
				$method = cau_check_if_exists( 'core', 'slug', $updateLog ) ? cau_get_plugininfo( 'core', 'method' ) : '-';
			}


		} else {
			$fileDateTime 	= 'Could not read core date.';
			$method 		= '-';
		}

		$log_items[$fileDateTime.'_'.$key] = array(
			'type' 		=> 'WordPress',
			'slug'		=> '',
			'name'		=> 'WordPress',
			'date'		=> $fileDateTime,
			'version'	=> get_bloginfo( 'version' ),
			'method'	=> $method,
		);

	}

	$listClasses = 'wp-list-table widefat autoupdate autoupdatelog';

	if( $format == 'table' ) {
		$listClasses .= ' autoupdatelog striped';
	} else {
		$listClasses .= ' autoupdatewidget';
	}

	echo '<table class="'.$listClasses.'">';

	// Show the last updated plugins
	if( $format == 'table' ) {

		echo '<thead>
			<tr>
				<th><strong>'.__( 'Name', 'companion-auto-update' ).'</strong></th>';
				if( !$translations ) echo '<th><strong>'.__( 'To version', 'companion-auto-update' ).'</strong></th>';
				echo '<th><strong>'.__( 'Type', 'companion-auto-update' ).'</strong></th>
				<th><strong>'.__( 'Last updated on', 'companion-auto-update' ).'</strong></th>
				<th><strong>'.__( 'Update method', 'companion-auto-update' ).'</strong></th>
			</tr>
		</thead>';

	}

	echo '<tbody id="the-list">';

	krsort( $log_items );
	$limited_log_items = $limit ? array_slice( $log_items, 0, $limit ) : $log_items;

	foreach ( $limited_log_items as $key => $value ) {

		echo '<tr>';

			$log_item__name 	= $value['name'];
			$log_item__name_f 	= ( $format != 'table' && strlen( $log_item__name ) > 25 ) ? substr( $log_item__name, 0, 25 ).'...' : $log_item__name;
			$log_item__type 	= $value['type'];
			$log_item__slug 	= $value['slug'];
			$log_item__version 	= $value['version'];
			$log_item__date 	= date_i18n( get_option( 'date_format' ), $value['date'] );
			$log_item__method 	= $value['method'];

			echo '<td class="column-updatetitle"><p><strong title="'.$log_item__name.'">'.cau_getChangelogUrl( $log_item__type, $log_item__name_f, $log_item__slug ).'</strong></p></td>';

			if( $format == 'table' ) {
				if( !$translations ) echo '<td class="cau_hide_on_mobile column-version" style="min-width: 100px;"><p>'.$log_item__version.'</p></td>';
				echo '<td class="cau_hide_on_mobile column-description"><p>'.$log_item__type.'</p></td>';
			}
			echo '<td class="column-date" style="min-width: 100px;"><p>'.$log_item__date.'</p></td>';


			if( $format == 'table' ) {
				echo '<td class="column-method"><p>'.$log_item__method.'</p></td>';
			}

		echo '</tr>';

	}

	echo "</tbody></table>";

}

// Get the proper changelog URL
function cau_getChangelogUrl( $type, $name, $plugslug ) {

	switch( $type ) {
	    case 'WordPress':
	        $url = '';
	        break;
	    case 'Plugin':
	    	$url = admin_url( 'plugin-install.php?tab=plugin-information&plugin='.$plugslug.'&section=changelog&TB_iframe=true&width=772&height=772' );
	        break;
	    case 'Theme':
	        $url = '';
	        break;
	}

	return !empty( $url ) ? "<a href='{$url}' class='thickbox open-plugin-details-modal' aria-label='More information about {$name}' data-title='{$name}'>{$name}</a>" : $name;

}

// Only update plugins which are enabled
function cau_dontUpdatePlugins( $update, $item ) {
	return in_array( $item->slug, plugins_donotupdatelist() ) ? false : true;
}
function cau_dontUpdateThemes( $update, $item ) {
	return in_array( $item->slug, themes_donotupdatelist() ) ? false : true;
}

// Get plugin information of repository
function cau_plugin_info( $slug, $what ) {

	$slug 				= sanitize_title( $slug );
    $cau_transient_name = 'cau' . $slug;
    $cau_info 			= get_transient( $cau_transient_name );

    if( !function_exists( 'plugins_api' ) ) require_once( ABSPATH.'wp-admin/includes/plugin-install.php' );
	$cau_info = plugins_api( 'plugin_information', array( 'slug' => $slug ) );

	if ( ! $cau_info or is_wp_error( $cau_info ) ) {
        return false;
    }

    set_transient( $cau_transient_name, $cau_info, 3600 );

    switch ( $what ) {
    	case 'versions':
    		return $cau_info->versions;
    		break;
    	case 'version':
    		return $cau_info->version;
    		break;
    	case 'name':
    		return $cau_info->name;
    		break;
    	case 'slug':
    		return $cau_info->slug;
    		break;
    }

}

// Get list of outdated plugins
function cau_list_outdated() {

	$outdatedList 	= array();	

	// Check if function exists
	if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
	
	if( !function_exists( 'plugins_api' ) ) {
		require_once( ABSPATH.'wp-admin/includes/plugin-install.php' );
	}

	foreach ( get_plugins() as $key => $value) {

		$slug 			= $key;
		$explosion 		= explode( '/', $slug );
		$actualSlug 	= array_shift( $explosion );

		// Get plugin name
		foreach ( $value as $k => $v ) if( $k == "Name" ) $name = $v;
		
		// Get plugins tested up to version
		$api = plugins_api( 'plugin_information', array( 'slug' => wp_unslash( $actualSlug ), 'tested' => true ) );

		// Version compare
		$tested_version 	= !empty( $api->tested ) ? substr( $api->tested, 0, 3 ) : false; // Format version number

		// Check if "tested up to" version number is set
		if( $tested_version ) {

			$current_version 	= substr( get_bloginfo( 'version' ), 0, 3 );  // Format version number
			$version_difference = ( (int)$current_version - (int)$tested_version ); // Get the difference
			// $tested_wp      	= ( empty( $api->tested ) || cau_version_compare( get_bloginfo( 'version' ), $api->tested, '<' ) );

			if( $version_difference >= '0.3' )  {
				$outdatedList[$name] = substr( $api->tested, 0, 3 );
			}

		} else {
			$outdatedList[$name] = ''; // We'll catch this when sending the e-mail
		}

	}

	return $outdatedList;

}

// Better version compare
function cau_version_compare( $ver1, $ver2, $operator = null ) {
    $p 		= '#(\.0+)+($|-)#';
    $ver1 	= preg_replace( $p, '', $ver1 );
    $ver2 	= preg_replace( $p, '', $ver2 );
    return isset( $operator ) ? version_compare( $ver1, $ver2, $operator ) : version_compare( $ver1, $ver2 );
}

// Get plugin information of currently installed plugins
function cau_active_plugin_info( $slug, $what ) {

	// Check if function exists
	if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

	$allPlugins = get_plugins();

	foreach( $allPlugins as $key => $value ) {
		$thisSlug 	= explode('/', $key);
		$thisSlugE 	= $thisSlug[0];
		if( $thisSlug == $slug ) {
			if( $what == 'version' ) return $value['Version'];
		}
	}

}

// Remove update nag when major updates are disabled
function cau_hideUpdateNag() {
	if( cau_get_db_value( 'major' ) != 'on' ) {
		remove_action( 'admin_notices', 'update_nag', 3 );
		remove_action( 'network_admin_notices', 'maintenance_nag', 10 );
	}
}
add_action( 'admin_head', 'cau_hideUpdateNag', 100 );

// Add more intervals to event schedules
function cau_addMoreIntervals( $schedules ) {

	// Add a weekly interval.
	$schedules['weekly'] = array(
		'interval' => 604800,
		'display'  => __( 'Every week', 'companion-auto-update' ),
	);
	
	// Add a twice montly interval.
	$schedules['twice_monthly'] = array(
		'interval' => 1209600,
		'display'  => __( 'Every 2 weeks', 'companion-auto-update' ),
	);
	
	// Add a montly interval.
	$schedules['once_monthly'] = array(
		'interval' => 2419200,
		'display'  => __( 'Every 4 weeks', 'companion-auto-update' ),
	);

	return $schedules;

}
add_filter( 'cron_schedules', 'cau_addMoreIntervals' ); 

// Get only unique schedules
function cau_wp_get_schedules() {

	// Start variables
	$availableIntervals = wp_get_schedules();
	$array_unique 		= array();
	$intervalTimes 		= array();
	$intervalNames 		= array();
	$intervalUniques 	= array();
	$counter 			= 0;

	// Get all intervals
	foreach ( $availableIntervals as $key => $value ) {

		// Do a bunch of checks to format them the right way
		foreach ( $value as $display => $interval ) {

			if( $display == 'interval' ) {
				
				if( $interval == '86400' ) $key = 'daily'; // Force the daily interval to be called daily, required by a bunch of handles of this plugin

				$intervalTimes[$counter] 	= $key;  // Add the backend name (i.e. "once_monthly" or "daily") 
				$intervalUniques[$counter] 	= $interval;  // Add the unix timestamp of this interval, used to identify unique items

				// Format display name in a proper way
				$numOfMinutes 	= ($interval/60);
				$identifier 	= __( 'minutes', 'companion-auto-update' );

				// I just know there's an easier way for this, but I can't come up with it and this works so...
				if( $interval >= (60*60) ) {
					$numOfMinutes 	= ($numOfMinutes/60);
					$identifier 	= __( 'hours', 'companion-auto-update' );
				}
				if( $interval >= (60*60*24) ) {
					$numOfMinutes 	= ($numOfMinutes/24);
					$identifier 	= __( 'days', 'companion-auto-update' );
				}
				if( $interval >= (60*60*24*7) ) {
					$numOfMinutes 	= ($numOfMinutes/7);
					$identifier 	= __( 'weeks', 'companion-auto-update' );
				}
				if( $interval >= (60*60*24*7*(52/12)) ) {
					$numOfMinutes 	= ($numOfMinutes/(52/12));
					$identifier 	= __( 'months', 'companion-auto-update' );
				}

				$display 					= sprintf( esc_html__( 'Every %s %s', 'companion-auto-update' ), round( $numOfMinutes, 2 ), $identifier ); // Translateble
				$intervalNames[$counter] 	= $display; // Add the display name (i.e. "Once a month" or "Once Daily")

				$counter++; // Make sure the next interval gets a new "key" value
			}

		}

	}

	// Sort the interval from smallest to largest
	asort( $intervalUniques ); 

	// Prevent duplicates
	foreach ( array_unique( $intervalUniques ) as $key => $value ) {
		// $value is the timestamp
		// $intervalTimes[$key] is the backend name
		// $intervalNames[$key] is the display name
		$array_unique[$intervalTimes[$key]] = $intervalNames[$key];
	}

	// Return the array
	return $array_unique;

} 

// Check if the update log db is empty
function cau_updateLogDBisEmpty() {

	global $wpdb;
	$updateDB 		= "update_log";
	$updateLog 		= $wpdb->prefix.$updateDB; 
	$row_count 		= $wpdb->get_var( "SELECT COUNT(*) FROM $updateLog" );

	return ( $row_count > 0 ) ? false : true;
}

// Plugin information to DB
function cau_savePluginInformation( $method = 'New' ) {

	// Check if function exists
	if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    // Set variables
	global $wpdb;
	$updateDB 		= "update_log";
	$updateLog 		= $wpdb->prefix.$updateDB; 
	$allPlugins 	= get_plugins();
	$allThemes 		= wp_get_themes();

	// Loop trough all themes
	foreach ( $allThemes as $key => $value ) {
		if( !cau_check_if_exists( $key, 'slug', $updateDB ) ) $wpdb->insert( $updateLog, array( 'slug' => $key, 'oldVersion' => '-', 'method' => $method ) );
	}

	// Loop trough all plugins
	foreach ( $allPlugins as $key => $value ) {
		if( !cau_check_if_exists( $key, 'slug', $updateDB ) ) $wpdb->insert( $updateLog, array( 'slug' => $key, 'oldVersion' => '-', 'method' => $method ) );
	}	

	// Core
	if( !cau_check_if_exists( 'core', 'slug', $updateDB ) ) $wpdb->insert( $updateLog, array( 'slug' => 'core', 'oldVersion' => '-', 'method' => $method ) );

}

function cau_updatePluginInformation( $slug, $method = '-', $newVersion = '-' ) {

	global $wpdb;
	$updateDB 		= "update_log";
	$updateLog 		= $wpdb->prefix.$updateDB; 
	$wpdb->query( $wpdb->prepare( "UPDATE $updateLog SET newVersion = '%s', method = %s WHERE slug = '%s'", $newVersion, $method, $slug ) );

}

function cau_siteHealthSignature() {
	return '<p style="font-size: 12px; color: #707070;">'.__( 'This was reported by the Companion Auto Update plugin', 'companion-auto-update' ).'</p>';
}

function cau_add_siteHealthTest( $tests ) {
    $tests['direct']['cau_disabled'] = array( 'label' => __( 'Companion Auto Update', 'companion-auto-update' ), 'test'  => 'cau_disabled_test' );
    return $tests;
}
add_filter( 'site_status_tests', 'cau_add_siteHealthTest' );
 
function cau_disabled_test() {

    $result = array(
        'label'       => __( 'Auto updating is enabled', 'companion-auto-update' ),
        'status'      => 'good',
        'badge'       => array(
            'label' => __( 'Security' ),
            'color' => 'blue',
        ),
        'description' => sprintf( '<p>%s</p>', __( "Automatic updating isn't disabled on this site.", 'companion-auto-update' ) ),
        'actions'     => '',
        'test'        => 'cau_disabled',
    );
 
    if ( checkAutomaticUpdaterDisabled() OR !has_filter( 'wp_version_check', 'wp_version_check' )  ) {
        $result['status'] 		= 'critical';
        $result['label'] 		= __( 'Auto updating is disabled', 'companion-auto-update' );
        $result['description'] 	= __( 'Automatic updating is disabled on this site by either WordPress, another plugin or your webhost.', 'companion-auto-update' );
        $result['description'] 	.= ' '.__( 'For more information about this error check the status page.', 'companion-auto-update' );
        $result['actions'] 		.= sprintf( '<p><a href="%s">%s</a>', esc_url( cau_url( 'status' ) ), __( 'Check the status page', 'companion-auto-update' ) );
    }

    $result['actions'] 		.= cau_siteHealthSignature();
 
    return $result;
}

// Check for version control
function cau_test_is_vcs_checkout( $context ) {

	$context_dirs 	= array( ABSPATH );
	$vcs_dirs 		= array( '.svn', '.git', '.hg', '.bzr' );
	$check_dirs 	= array();
	$result 		= array();

	foreach ( $context_dirs as $context_dir ) {
		// Walk up from $context_dir to the root.
		do {
			$check_dirs[] = $context_dir;

			// Once we've hit '/' or 'C:\', we need to stop. dirname will keep returning the input here.
			if ( $context_dir == dirname( $context_dir ) )
				break;

		// Continue one level at a time.
		} while ( $context_dir = dirname( $context_dir ) );
	}

	$check_dirs = array_unique( $check_dirs );

	// Search all directories we've found for evidence of version control.
	foreach ( $vcs_dirs as $vcs_dir ) {
		foreach ( $check_dirs as $check_dir ) {
			if ( $checkout = @is_dir( rtrim( $check_dir, '\\/' ) . "/$vcs_dir" ) ) {
				break 2;
			}
		}
	}

	if ( $checkout && ! apply_filters( 'automatic_updates_is_vcs_checkout', true, $context ) ) {
		$result['description'] 	= sprintf( __( 'The folder %s was detected as being under version control (%s), but the %s filter is allowing updates' , 'companion-auto-update' ), "<code>$check_dir</code>", "<code>automatic_updates_is_vcs_checkout</code>" );
		$result['icon'] 		= 'warning';
		$result['status'] 		= 'info';
	} else if ( $checkout ) {
		$result['description'] 	= sprintf( __( 'The folder %s was detected as being under version control (%s)' , 'companion-auto-update' ), "<code>$check_dir</code>", "<code>$vcs_dir</code>" );
		$result['icon'] 		= 'no';
		$result['status'] 		= 'disabled';
	} else {
		$result['description'] 	= __( 'No issues detected' , 'companion-auto-update' );
		$result['icon'] 		= 'yes-alt';
		$result['status'] 		= 'enabled';
	}

	return $result;
}

// Check if plugins need to be delayed
function cau_check_delayed() {
	if( cau_get_db_value( 'update_delay' ) == 'on' ) {
		cau_hold_updates();
		cau_unhold_updates();
	} else {
		cau_unhold_all_updates();
	}
}

// List of all delayed plugins 
function cau_delayed_updates() {

	global $wpdb;
	$plugin_list 	= array();
	$updateLog 		= $wpdb->prefix."update_log"; 
	$put_on_hold 	= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$updateLog} WHERE put_on_hold <> '%s'", '0' ) );
	foreach ( $put_on_hold as $plugin ) {
		array_push( $plugin_list, $plugin->slug );
	}
	return $plugin_list;

}

// List of all delayed plugins for the update function
function cau_delayed_updates__formated() {

	$plugin_list 	= array();
	foreach ( cau_delayed_updates() as $plugin ) {
		$explosion 		= explode( '/', $plugin );
		$short_slug 	= array_shift( $explosion );
		array_push( $plugin_list, $short_slug );
	}
	return $plugin_list;

}

// Add "put on hold" timestamp to the database if it hasn't been set yet
function cau_hold_updates() {

	if ( !function_exists( 'get_plugin_updates' ) ) require_once ABSPATH . 'wp-admin/includes/update.php';
	$plugins = get_plugin_updates();

	if ( !empty( $plugins ) ) {
		$list = array();
		foreach ( (array)$plugins as $plugin_file => $plugin_data ) {
			if( !in_array( $plugin_file, cau_delayed_updates() ) ) {
				global $wpdb;
				$updateLog = "{$wpdb->prefix}update_log"; 
				$wpdb->query( $wpdb->prepare( "UPDATE $updateLog SET put_on_hold = '%s' WHERE slug = '%s'", strtotime( "now" ), $plugin_file ) );
			}
		}
	}
}

// Remove plugins from "put on hold" after x days
function cau_unhold_updates() {


	global $wpdb;

	$after_x_days 	= ( cau_get_db_value( 'update_delay_days' ) != '' ) ? cau_get_db_value( 'update_delay_days' ) : '2';
	$today 			= strtotime( "now" );
	$updateLog 		= "{$wpdb->prefix}update_log"; 
	$put_on_hold 	= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$updateLog} WHERE put_on_hold <> '%s'", '0' ) );

	foreach ( $put_on_hold as $plugin ) {

		$plugin_file 		= $plugin->slug;
		$put_on_hold_date 	= $plugin->put_on_hold;
		$remove_after 		= strtotime( '+'.$after_x_days.' days', $put_on_hold_date );

		if( $remove_after <= $today ) {
			$wpdb->query( $wpdb->prepare( "UPDATE {$updateLog} SET put_on_hold = '%s' WHERE slug = '%s'", '0', $plugin_file ) );
		}

	}

}

// Remove all plugins from "put on hold" if option is disabled
function cau_unhold_all_updates() {
	global $wpdb;
	$updateLog 		= "{$wpdb->prefix}update_log"; 
	$put_on_hold 	= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$updateLog} WHERE put_on_hold <> '%s'", '0' ) );
	foreach ( $put_on_hold as $plugin ) {
		$plugin_file 		= $plugin->slug;
		$wpdb->query( $wpdb->prepare( "UPDATE {$updateLog} SET put_on_hold = '%s' WHERE slug = '%s'", '0', $plugin_file ) );
	}
}
