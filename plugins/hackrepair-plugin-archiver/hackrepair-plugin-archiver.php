<?php
/*
Plugin Name: The Hack Repair Guy's Plugin Archiver
Plugin URI: http://wordpress.org/extend/plugins/hackrepair-plugin-archiver/
Description: Quickly deactivate and archive a plugin for later use. Archiving a plugin both deactivates and removes the plugin from your visible Plugins list.
Author: Jim Walker, The Hack Repair Guy
Version: 2.0.4
Author URI: http://hackrepair.com/hackrepair-plugin-archiver/
*/


add_action('plugins_loaded', array( 'HackRepair_Plugin_Archiver', 'init' ) );

class HackRepair_Plugin_Archiver {
	public static $count = 0;
	public static $plugin_dir;
	public static $options = array(
		'archive_dir' => '',
		'archive_dir_add' => '',
		'deactivate'  => true,
	);
	public static function init() {
		self::$plugin_dir = plugin_dir_path( __FILE__ );
		$options = get_option( 'hackrepair-plugin-archiver_options' );
		self::$options = wp_parse_args( $options, self::$options );
		if ( '' === self::$options['archive_dir'] ) {
			self::$options['archive_dir'] = 'plugins-archive-'.substr( md5( get_bloginfo( 'url' ) ), 0, 6);
		}
		if ( is_admin() ) {
			add_action( 'admin_menu', array( 'HackRepair_Plugin_Archiver', 'admin_init'  ) );
		}
		if ( class_exists('HackRepair_Plugin_Archiver_Bulk_Action') ) {
			$bulk_action = new HackRepair_Plugin_Archiver_Bulk_Action();
			$bulk_action->init();
			$bulk_action->register_bulk_action( array(
				'action_name'  => 'archive-selected',
				'menu_text'    => __( 'Archive', 'hackrepair-plugin-archiver' ),
				'admin_notice' => _n_noop( 'Plugin archived sucessfully', '%d plugins archived sucessfully', 'hackrepair-plugin-archiver' ),
				'callback'     => array( 'HackRepair_Plugin_Archiver', 'bulk_archive' ),
			) );
		}
		add_filter( 'plugin_action_links', 			array( 'HackRepair_Plugin_Archiver', 'action_link' ), 10, 4 );
		add_action( 'admin_menu',          			array( 'HackRepair_Plugin_Archiver', 'menu' ) );
		add_filter( 'custom_menu_order',   			array( 'HackRepair_Plugin_Archiver', 'menu_order' ) );
		add_action( 'load-plugins_page_hackrepair-plugin-archiver', 	array( 'HackRepair_Plugin_Archiver', 'archive_actions' ) );
		add_action( 'admin_notices',          		array( 'HackRepair_Plugin_Archiver', 'admin_notice' ) );
		add_filter( 'views_plugins', 				array( 'HackRepair_Plugin_Archiver', 'plugin_views' ) );
	}
	public static function get_archive_dirs() {
	    global $wp_filesystem;
	    WP_Filesystem();
	    $dirlist = $wp_filesystem->dirlist(WP_CONTENT_DIR);
	    $result = array();
	    foreach ($dirlist as $key => $dir) {
	    	if ( 0 === strpos( $dir['name'], 'plugins-' ) ) {
	    		$result[] = $dir['name'];
	    	}
	    }
	    if ( !$result ) {
	    	$result[] = self::$options['archive_dir'];
	    } 
	    return $result;
	}
	public static function pointer_filter( $pointers ) {
		if ( !isset( $pointers['plugins_page_hackrepair-plugin-archiver'] ) ) {
			$pointers['plugins_page_hackrepair-plugin-archiver'] = array();
		}
		$pointers['plugins_page_hackrepair-plugin-archiver']['test3'] = array(
			'callback' => array( 'HackRepair_Plugin_Archiver', 'pointer_content' ),
		);
		return $pointers;
	}
	public static function pointer_content() {
		$content  = '<h3>' . __( 'Pick active plugin archive', 'hackrepair-plugin-archiver' ) . '</h3>';
		$content .= '<p>' . __( 'Choose the active plugin archive directory.', 'hackrepair-plugin-archiver' ) . '</p>';
		HackRepair_Plugin_Archiver_Pointer::print_js( 'test3', 'ul.subsubsub', array(
			'content' => $content,
			'position' => array( 'edge' => 'top', 'align' => 'left' ),
		) );
	}
	public static function admin_init() {
		global $pagenow;
		if ( 'plugins.php' === $pagenow ) {
			$dirs = self::get_archive_dirs();
			if ( 1 < sizeof($dirs) ) {
				require_once ( self::$plugin_dir . 'includes/pointers.php' );
				add_action( 'admin_enqueue_scripts', 				array( 'HackRepair_Plugin_Archiver_Pointer', 'enqueue_scripts' ) );
				add_filter( 'hackrepair_plugin_archiver_pointers', 	array( 'HackRepair_Plugin_Archiver', 		 'pointer_filter' ) );
			}
		}
		require_once ( self::$plugin_dir . 'includes/options.php' );
		$archive_dirs = self::get_archive_dirs();
		$fields =   array(
			"general" => array(
				'title' => '',
				'callback' => '',
				'options' => array(
					'archive_dir' => array(
						'title'=>__('Current Archive Directory','hackrepair-plugin-archiver'),
						'args' => array (
							'values' => $archive_dirs,
							'description' => __( 'Name of the directory to store archived plugins in. Relative to <code>WP_CONTENT_DIR</code>.', 'hackrepair-plugin-archiver' ),
						),
						'callback' => 'select',
					),
					'archive_dir_add' => array(
						'title'=>__('New Archive Directory','hackrepair-plugin-archiver'),
						'args' => array (
							'description' => __( 'Create a new Plugin Archive directory. Will be prefixed with <code>plugins-</code>.', 'hackrepair-plugin-archiver' ),
						),
						'callback' => 'text_plugins',
					),
					'deactivate' => array(
						'title'=>__('Deactivate Before Archiving','hackrepair-plugin-archiver'),
						'args' => array (
							'description' => __( 'Should the plugin be automatically deactivated before moving it to the archive?', 'hackrepair-plugin-archiver' ),
						),
						'callback' => 'checkbox',
					),
				),
			),
		);
		$tabs = array(
			'settings' => array(
				'title' => __( 'Settings', 'hackrepair-plugin-archiver' ),
				'href'  => admin_url('options-general.php?page=hackrepair-plugin-archiver-settings'),
				'class' => '',
				'callback' => 'settings',
			),
			'archive' => array(
				'title' => __( 'Archived Plugins', 'hackrepair-plugin-archiver' ),
				'href'  => admin_url('plugins.php?page=hackrepair-plugin-archiver'),
				'class' => '',
			),
			'notes' => array(
				'title' => __( 'Author Notes', 'hackrepair-plugin-archiver' ),
				'href'  => admin_url('options-general.php?page=hackrepair-plugin-archiver-settings&tab=notes'),
				'class' => '',
				'callback' => array( 'HackRepair_Plugin_Archiver', 'notes' ),
			),
		);
		HackRepair_Plugin_Archiver_Options::init(
		'hackrepair-plugin-archiver',
		__( 'Plugin Archiver',          'hackrepair-plugin-archiver' ),
		__( 'The Hack Repair Guy\'s Plugin Archiver: Settings', 'hackrepair-plugin-archiver' ),
		$fields,
		$tabs,
		'HackRepair_Plugin_Archiver',
		'hackrepair-plugin-archiver-settings'
		);
	}
	public static function notes() {
		echo '<div style="max-width: 600px; text-align:justify;">';
		include_once ( self::$plugin_dir . 'includes/notes.php' );
		echo '</div>';
	}

	public static function plugin_views( $views ){
		if ( isset( $_REQUEST['page'] ) && 'hackrepair-plugin-archiver' === $_REQUEST['page'] ) {
			return $views;
		}
		$plugins = self::get_archived_plugins();
		$count = sizeof( $plugins );
		if ( 0 < $count ) {
			$link = admin_url( 'plugins.php?page=hackrepair-plugin-archiver' );
			$title = __( 'Archived', 'hackrepair-plugin-archiver' );
			$view = "<a href=\"{$link}\">{$title} <span class=\"count\">({$count})</span></a>";
		    $views['archived'] = $view;
		}
	    return $views;
	}

	public static function get_archived_plugins($plugin_root = '') {	
        $wp_plugins = array ();
        if ( empty($plugin_root) ) {
                $plugin_root = WP_CONTENT_DIR.'/'.HackRepair_Plugin_Archiver::$options['archive_dir'];	        	
        }
	
        // Files in wp-content/plugins directory
        $plugins_dir = @ opendir( $plugin_root);
        $plugin_files = array();
        if ( $plugins_dir ) {
                while (($file = readdir( $plugins_dir ) ) !== false ) {
                        if ( substr($file, 0, 1) == '.' )
                                continue;
                        if ( is_dir( $plugin_root.'/'.$file ) ) {
                                $plugins_subdir = @ opendir( $plugin_root.'/'.$file );
                                if ( $plugins_subdir ) {
                                        while (($subfile = readdir( $plugins_subdir ) ) !== false ) {
                                                if ( substr($subfile, 0, 1) == '.' )
                                                        continue;
                                                if ( substr($subfile, -4) == '.php' )
                                                        $plugin_files[] = "$file/$subfile";
                                        }
                                        closedir( $plugins_subdir );
                                }
                        } else {
                                if ( substr($file, -4) == '.php' )
                                        $plugin_files[] = $file;
                        }
                }
                closedir( $plugins_dir );
        }
        if ( empty($plugin_files) )
                return $wp_plugins;

        foreach ( $plugin_files as $plugin_file ) {
                if ( !is_readable( "$plugin_root/$plugin_file" ) )
                        continue;

                $plugin_data = get_plugin_data( "$plugin_root/$plugin_file", false, false ); //Do not apply markup/translate as it'll be cached.

                if ( empty ( $plugin_data['Name'] ) )
                        continue;

                $wp_plugins[plugin_basename( $plugin_file )] = $plugin_data;
        }
        uasort( $wp_plugins, '_sort_uname_callback' );

        // $cache_plugins[ $plugin_folder ] = $wp_plugins;
        // wp_cache_set('plugins', $cache_plugins, 'plugins');

        return $wp_plugins;
	}

	public static function archive_actions() {
        if ( isset($_REQUEST['action']) ) {
 		   	switch ( $_REQUEST['action'] ) {
 		   		case 'restore-selected':
 		   		    $result = HackRepair_Plugin_Archiver::bulk_restore();
 		   			if ( !$result ) {
						include(ABSPATH . 'wp-admin/admin-footer.php');
						die();
 		   			} else {
 		   				wp_redirect( admin_url( 'plugins.php?page=hackrepair-plugin-archiver&success_action=restore-selected&count='.self::$count ) );
 		   			}
 		   			break;
 		   		case 'remove-selected':
 		   		    $result = HackRepair_Plugin_Archiver::bulk_remove();
 		   			if ( !$result ) {
						include(ABSPATH . 'wp-admin/admin-footer.php');
						die();
 		   			} else {
 		   				wp_redirect( admin_url( 'plugins.php?page=hackrepair-plugin-archiver&success_action=remove-selected&count='.self::$count ) );
 		   			}
 		   			break;
 		   	}
        }
    }	
	public static function admin_notice() {
		global $pagenow;
		if( $pagenow == 'plugins.php' ) {
			if (isset($_REQUEST['success_action']) && 'restore-selected' == $_REQUEST['success_action'] ) {
				//Print notice in admin bar
				$message = _n_noop( 'Plugin restored sucessfully', '%d plugins restored sucessfully', 'hackrepair-plugin-archiver' );
				if(!empty($message)) {
					$nooped_message = sprintf( translate_nooped_plural( $message, $_REQUEST['count'], 'hackrepair-plugin-archiver' ), $_REQUEST['count'] );
					echo "<div class=\"updated\"><p>{$nooped_message}</p></div>";
				}
			}
			if (isset($_REQUEST['success_action']) && 'remove-selected' == $_REQUEST['success_action'] ) {
				//Print notice in admin bar
				$message = _n_noop( 'Plugin deleted sucessfully', '%d plugins deleted sucessfully', 'hackrepair-plugin-archiver' );
				if(!empty($message)) {
					$nooped_message = sprintf( translate_nooped_plural( $message, $_REQUEST['count'], 'hackrepair-plugin-archiver' ), $_REQUEST['count'] );
					echo "<div class=\"updated\"><p>{$nooped_message}</p></div>";
				}
			}
		}
	}

	// hook into WP Admin menu structure
	public static function menu_order ( $menu_ord ) {
	    global $submenu;

	    $key = self::array_search( 'hackrepair-plugin-archiver', 2, $submenu['plugins.php'] );
	    if (false !== $key ) {
	    	$temp = $submenu['plugins.php'][$key];
	    	unset($submenu['plugins.php'][$key]);
	    	$submenu['plugins.php'][9.9] = $temp;
	    	ksort($submenu['plugins.php']);
	    }
	    return $menu_ord;
	}
	public static function menu() {
		$a = add_plugins_page( 
			__( 'The Hack Repair Guy\'s Plugin Archiver: Archived Plugins', 'hackrepair-plugin-archiver' ), 
			__( 'Archived Plugins',        'hackrepair-plugin-archiver' ),
			'install_plugins', 
			'hackrepair-plugin-archiver', 
			array( 'HackRepair_Plugin_Archiver', 'archive_page' )
		);
	}
	public static function archive_page() {
		global $title;
		add_screen_option( 'per_page', array( 'default' => 3 ) );
		$wp_list_table = new WP_Plugins_Archive_List_Table();
		$pagenum = $wp_list_table->get_pagenum();
		$wp_list_table->prepare_items();
		echo '<div class="wrap">';
		echo '<h2>'.esc_html( $title ) .'</h2>';
		$wp_list_table->views();
		echo '<form method="get">';
		echo '<input type="hidden" name="page" value="hackrepair-plugin-archiver" />';
		$wp_list_table->search_box( __( 'Search Archived Plugins', 'hackrepair-plugin-archiver' ), 'plugin' ); 
		echo '</form>';
		echo '<form method="post" id="bulk-action-form">';
		echo '<input type="hidden" name="page" value="hackrepair-plugin-archiver" />';
		$wp_list_table->display();
		echo '</form>';		
		echo '</div>';
	}

	// action link filter
	public static function action_link($actions, $plugin_file, $plugin_data, $context) {
		$exclude_context = array( 'mustuse', 'dropins' );
		$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '';
		if ( !in_array( $context, $exclude_context) && ( 'hackrepair-plugin-archiver/hackrepair-plugin-archiver.php' !== $plugin_file ) && ( 'hackrepair-plugin-archiver' !== $page) ) {
			$actions['archive'] = '<a href="' . wp_nonce_url( 'plugins.php?action=archive-selected&amp;checked%5B0%5D=' . $plugin_file, 'bulk-plugins' ) . '" aria-label="' . esc_attr( sprintf( __( 'Archive %s', 'hackrepair-plugin-archiver' ), $plugin_data['Name'] ) ) . '">' . __( 'Archive', 'hackrepair-plugin-archiver' ) . '</a>';
		} else if ( ('hackrepair-plugin-archiver/hackrepair-plugin-archiver.php' == $plugin_file ) && ( 'hackrepair-plugin-archiver' !== $page) ) {
			$actions['restore-all'] = '<a href="' . wp_nonce_url( 'plugins.php?page=hackrepair-plugin-archiver&amp;action=restore-selected&amp;all=true', 'bulk-plugins' ) . '" aria-label="' . esc_attr( __( 'Unarchive all archived plugins', 'hackrepair-plugin-archiver' ) ) . '">' . __( 'Unarchive All', 'hackrepair-plugin-archiver' ) . '</a>';			
		}
		return $actions;
	}

	// bulk actions
	public static function bulk_remove($checked=false) {
		global $wp_filesystem;
		$form_fields = $_REQUEST;
		unset($form_fields['_wpnonce']);
		unset($form_fields['_wp_http_referer']);
		$url = add_query_arg( $form_fields, admin_url( 'plugins.php' ) );
		ob_start();
		$creds = request_filesystem_credentials($url, get_filesystem_method(), false, false );
		$output = ob_get_contents();
    	ob_end_clean();
		if ( $creds ) {
			WP_Filesystem($creds);
			$archive_dir = trailingslashit( $wp_filesystem->wp_content_dir() . self::$options['archive_dir']);
			$wp_filesystem->mkdir( $archive_dir );
			$count = 0;
			foreach ( $_REQUEST['checked'] as $plugin ) {
				$plugin_dir = self::plugin_basename( $plugin, $archive_dir );
				$wp_filesystem->chmod( $plugin_dir , $wp_filesystem->is_dir( $plugin_dir ) ? FS_CHMOD_DIR : FS_CHMOD_FILE );
				$wp_filesystem->delete( $plugin_dir, true );
				$count++;
			}
			self::$count = $count;
			return true;
		} else {
			require_once( ABSPATH . 'wp-admin/admin.php' );
			require_once( ABSPATH . 'wp-admin/admin-header.php');
			echo '<div class="wrap">';
			echo $output;
			echo '</div>';
			// include(ABSPATH . 'wp-admin/admin-footer.php');
			return false;
		}
	}
	public static function bulk_restore( $checked=false ) {
		global $wp_filesystem;
		$form_fields = $_REQUEST;
		unset($form_fields['_wpnonce']);
		unset($form_fields['_wp_http_referer']);
		$url = add_query_arg( $form_fields, admin_url( 'plugins.php' ) );
		ob_start();
		$creds = request_filesystem_credentials($url, get_filesystem_method(), false, false );
		$output = ob_get_contents();
    	ob_end_clean();
		if ( $creds ) {
			WP_Filesystem($creds);
			$archive_dir = trailingslashit( $wp_filesystem->wp_content_dir() . self::$options['archive_dir']);
			$wp_filesystem->mkdir( $archive_dir );
			$count = 0;
			if ( isset( $_REQUEST['all'] ) &&  $_REQUEST['all'] ) {
				$plugins = self::get_archived_plugins();
				$checked = array_keys($plugins);
				$redirect = admin_url('plugins.php');
			} else {
				$checked = $_REQUEST['checked'];
			}
			foreach ( $checked as $plugin ) {
				//if ( isset( $plugins[$plugin] ) ) {
					$plugin_dir = self::plugin_basename( $plugin, $archive_dir );
					$target_dir = self::plugin_basename( $plugin, WP_PLUGIN_DIR );
					$result = $wp_filesystem->move( $plugin_dir, $target_dir );
					if ( $result) {
						$count++;
					}
				//}
			}
			self::$count = $count;
			return true;
		} else {
			require_once( ABSPATH . 'wp-admin/admin.php' );
			require_once( ABSPATH . 'wp-admin/admin-header.php');
			echo '<div class="wrap">';
			echo $output;
			echo '</div>';
			// include(ABSPATH . 'wp-admin/admin-footer.php');
			return false;
		}
	}
	public static function bulk_archive($checked) {
		global $wp_filesystem;
		$plugins = get_plugins();
		$form_fields = $_REQUEST;
		$url = add_query_arg( $form_fields, admin_url( 'plugins.php' ) );
		ob_start();
		$creds = request_filesystem_credentials($url, get_filesystem_method(), false, false );
		$output = ob_get_contents();
    	ob_end_clean();
		if ( $creds ) {
			WP_Filesystem($creds);
			$archive_dir = trailingslashit( $wp_filesystem->wp_content_dir() . self::$options['archive_dir']);
			$wp_filesystem->mkdir( $archive_dir );
			$count = 0;
			foreach ( $_REQUEST['checked'] as $plugin ) {
				if ( isset( $plugins[$plugin] ) ) {
					$target_dir = self::plugin_basename( $plugin, $archive_dir );
					$plugin_dir = self::plugin_basename( $plugin, WP_PLUGIN_DIR );
					if ( self::$options['deactivate'] ) {
		 				deactivate_plugins( $plugin );
					}
					$result = $wp_filesystem->move( $plugin_dir, $target_dir );
					if ( $result) {
						$count++;
					}
				}
			}
			self::$count = $count;
		} else {
			unset( $_REQUEST['success_action'] );
			require_once( ABSPATH . 'wp-admin/admin.php' );
			require_once( ABSPATH . 'wp-admin/admin-header.php');
			echo '<div class="wrap">';
			echo $output;
			echo '</div>';
			include(ABSPATH . 'wp-admin/admin-footer.php');
			die('');
		}
	}

	// utility functions
	private static function plugin_basename( $plugin, $base = '' ) {
		$dir = basename( $plugin );
		if ( $dir === $plugin ) {
			$result = $dir;
		} else {
		  $result = dirname( $plugin );
		}
		if ( $base ) {
			$base = trailingslashit( $base );
		}
		$result = $base . $result;
		return $result;
	}

	private static function array_search($needle,$key, $haystack) {
	    foreach($haystack as $main_key=>$value) {
	        if( $needle === $value[$key] ) {
	            return $main_key;
	        }
	    }
	    return false;
	}

}

// include admin classes - Bulk Action, List Table, Archive List Table
if ( is_admin() ) {
	if (!class_exists('HackRepair_Plugin_Archiver_Bulk_Action')) {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/bulk.php' ); 
	}

	if ( !class_exists('WP_List_Table') ) {
		require_once( ABSPATH. 'wp-admin/includes/class-wp-list-table.php');
	}
	require_once( plugin_dir_path( __FILE__ ) . 'includes/list.php' );	
}
