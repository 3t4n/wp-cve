<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}

if (defined('WEBTOTEM')) {

/**
 * Define which javascript and css files will be loaded in the header of the plugin pages.
 */
	$_page = WebTotemRequest::get('page');
	if(strpos($_page, 'wtotem') === 0) {
			add_action('admin_enqueue_scripts', 'WebTotemInterface::enqueueScripts', 1);
	}


	add_filter('pre_current_active_plugins', 'WebTotemInterface::registerDeletePrompt');

	/** Define role of current user */
	add_action('init', 'WebTotem::getUserRole');

	/** Execute pre-checks before every page */
	add_action('init', 'WebTotemInterface::startupChecks');

    /** Attach HTTP request handlers for the AJAX requests */
    add_action('wp_ajax_nopriv_wtotem_ajax', 'wtotem_public_ajax_callback');
    add_action('wp_ajax_wtotem_ajax', 'wtotem_ajax_callback');

    if(WebTotemOption::isActivated()){
        if(WebTotemCaptcha::isEnabled() or WebTotemLogin::anyTwoFactorActivated()){
            /** Login Page */
            add_action('login_enqueue_scripts', 'WebTotemInterface::loginEnqueueScripts');
        }

        /** Add authenticate filter */
        add_filter('authenticate', 'WebTotemInterface::wt_authenticate', 25, 3);

        /** Add lostpassword filter */
        add_action('lostpassword_errors', 'WebTotemInterface::wt_lost_password', 1, 2);

        /** Add site or new sites if it is multisite */
        add_action( 'wp_insert_site', 'WebTotemInterface::addNewSite' );
    }

	if (WebTotemOption::getPluginSettings('hide_wp_version')) {
        /** Restore readme file before WP update, then after update hide readme file */
		add_filter('update_feedback', 'WebTotemInterface::restoreReadmeWhenUpdating');

        /** Remove the WordPress generator meta-tag from the source code. */
        remove_action('wp_head', 'wp_generator');
	}

    /** User Profile */
    global $pagenow;
    if ( 'profile.php' === $pagenow or 'user-edit.php' === $pagenow) {
      add_action( 'admin_enqueue_scripts', 'WebTotemInterface::enqueueScripts', 1);
      add_action( 'show_user_profile', 'WebTotemInterface::add2faProfileForm');
      add_action( 'edit_user_profile', 'WebTotemInterface::add2faProfileForm' );
    }

	/** Launch of the daily cron. */
	add_action( 'wp', 'webtotem_add_cron_' );
	function webtotem_add_cron_() {
		if( ! wp_next_scheduled( 'webtotem_daily_cron' ) ) {
			wp_schedule_event( time(), 'daily', 'webtotem_daily_cron' );
		}
	}

  add_action( 'webtotem_daily_cron', 'WtotemDailyCron' );

  function WtotemDailyCron(){
	  WebTotemOption::setOptions(['scan_init' => 1]);
  }

	/** Launch of the minute cron. */
	if(WebTotemOption::getOption('scan_init')){

		// Register the n minute interval
		add_filter( 'cron_schedules', 'cron_add_some_min' );
		function cron_add_some_min( $schedules ) {
			$schedules['some_min'] = array(
					'interval' => 60,
					'display' => __('Every few minutes', 'wtotem'),
			);
			return $schedules;
		}

		// Registering an event
		add_action( 'wp', 'wtotem_step_cron' );
		function wtotem_step_cron() {
			if( ! wp_next_scheduled( 'wtotem_step_init_cron' ) ) {
				wp_schedule_event( time(), 'some_min', 'wtotem_step_init_cron' );
			}
		}

		// Linking the function to the cron event/task
		add_action( 'wtotem_step_init_cron', 'WebTotemScan::initialize' );
	}

  /**
   * List an associative array with the sub-pages of this plugin.
   *
   * @return array List of sub-pages of this plugin.
   */
  function wtotemPages() {
	  if( WebTotem::isMultiSite() ) {
		  $pages['wtotem_all_sites'] = [ 'title' =>  __('All sites', 'wtotem'), 'slug' => 'wtotem'];
	  }
		$slug = WebTotem::isMultiSite() ? 'wtotem_' : 'wtotem';

	  $pages['wtotem_dashboard'] = [ 'title' =>  __('Dashboard', 'wtotem'), 'slug' => $slug];
      $pages['wtotem_open_paths']  = [ 'title' =>  __('Open paths', 'wtotem'), 'slug' => $slug];
      $pages['wtotem_firewall']  = [ 'title' =>  __('Firewall', 'wtotem'), 'slug' => $slug];

	  if(!WebTotem::isMultiSite() or is_super_admin()) {
		  $pages['wtotem_antivirus'] = [ 'title' =>  __('Antivirus', 'wtotem'), 'slug' => $slug];
		  $pages['wtotem_settings']  = [ 'title' =>  __('Settings', 'wtotem'), 'slug' => $slug];
	  }
	  $pages['wtotem_reports']   = [ 'title' =>  __('Reports', 'wtotem'), 'slug' => $slug];
      $pages['wtotem_documentation'] = [ 'title' =>  __('Documentation', 'wtotem'), 'slug' => 'wtotem'];
      $pages['wtotem_wpscan'] = [ 'title' =>  __('WP scan', 'wtotem'), 'slug' => 'wtotem'];

      return $pages;
  }

  if (function_exists('add_action')) {
      /**
       * Display extension menu and submenu items in the correct interface.
       *
       * @return void
       */
      function wtotemAddMenu() {

          $page = ! WebTotemOption::isActivated() ? 'activation' : ( WebTotem::isMultiSite() ? 'all_sites' : 'dashboard' );

          add_menu_page(
              __('WebTotem', 'wtotem'),
              __('WebTotem', 'wtotem'),
              'manage_options',
              'wtotem',
              'wtotem_' . $page . '_page',
              WebTotem::getImagePath('logo_17x17_w.png')
          );

          if(WebTotemOption::isActivated()){
              $pages = wtotemPages();
              foreach ($pages as $sub_page_function => $sub_page) {
                  add_submenu_page(
                      $sub_page['slug'],
                      $sub_page['title'],
                      $sub_page['title'],
                      'manage_options',
                      $sub_page_function,
                      $sub_page_function . '_page'
                  );
              }

          } else {
              add_submenu_page(
                  'wtotem',
                  __('Activation', 'wtotem'),
                  __('Activation', 'wtotem'),
                  'manage_options',
                  'wtotem_activation',
                  'wtotem_activation_page'
              );
          }
      }

      /* Attach HTTP request handlers for the internal plugin pages */
	    if(WebTotem::isMultiSite()){
		    add_action('network_admin_menu', 'wtotemAddMenu');
	    }
	    add_action('admin_menu', 'wtotemAddMenu');
  }

    /**
     * Event hooks.
     *
     */
    if (class_exists('WebTotemEventListener')) {

			add_action('add_user_to_blog', 'WebTotemEventListener::hookAddUserToBlog', 50, 4);

        add_action('add_user_to_blog', 'WebTotemEventListener::hookAddUserToBlog', 50, 4);
        add_action('remove_user_from_blog', 'WebTotemEventListener::hookRemoveUserFromBlog', 50, 2);
        add_action('login_form_resetpass', 'WebTotemEventListener::hookLoginFormResetpass', 50, 5);
        add_action('profile_update', 'WebTotemEventListener::hookProfileUpdate', 50, 5);
        add_action('retrieve_password', 'WebTotemEventListener::hookRetrievePassword', 50, 5);
        add_action('user_register', 'WebTotemEventListener::hookUserRegister', 50, 5);
        add_action('deleted_user', 'WebTotemEventListener::hookUserDelete', 50, 3);
        add_action('wp_login', 'WebTotemEventListener::hookLoginSuccess', 50, 5);
        add_action('wp_login_failed', 'WebTotemEventListener::hookLoginFailure', 50, 5);
        add_action('add_link', 'WebTotemEventListener::hookLinkAdd', 50, 5);
        add_action('edit_link', 'WebTotemEventListener::hookLinkEdit', 50, 5);
        add_action('create_category', 'WebTotemEventListener::hookCategoryCreate', 50, 5);
        add_action('publish_post', 'WebTotemEventListener::hookPublishPost', 50, 5);
        add_action('transition_post_status', 'WebTotemEventListener::hookPostStatus', 50, 3);
        add_action('xmlrpc_publish_post', 'WebTotemEventListener::hookPublishPostXMLRPC', 50, 5);
        add_action('before_delete_post', 'WebTotemEventListener::hookPostBeforeDelete', 50, 5);
        add_action('delete_post', 'WebTotemEventListener::hookPostDelete', 50, 5);
        add_action('wp_trash_post', 'WebTotemEventListener::hookPostTrash', 50, 5);
        add_action('publish_page', 'WebTotemEventListener::hookPublishPage', 50, 5);
        add_action('add_attachment', 'WebTotemEventListener::hookAttachmentAdd', 50, 5);
        add_action('activated_plugin', 'WebTotemEventListener::hookPluginActivate', 50, 2);
        add_action('deactivated_plugin', 'WebTotemEventListener::hookPluginDeactivate', 50, 2);
        add_action('switch_theme', 'WebTotemEventListener::hookThemeSwitch', 50, 5);

        add_action('admin_init', 'WebTotemEventListener::hookCoreUpdate');
        add_action('admin_init', 'WebTotemEventListener::hookOptionsManagement');
        add_action('admin_init', 'WebTotemEventListener::hookPluginDelete');
        add_action('admin_init', 'WebTotemEventListener::hookPluginEditor');
        add_action('admin_init', 'WebTotemEventListener::hookPluginInstall');
        add_action('admin_init', 'WebTotemEventListener::hookPluginUpdate');
        add_action('admin_init', 'WebTotemEventListener::hookThemeDelete');
        add_action('admin_init', 'WebTotemEventListener::hookThemeEditor');
        add_action('admin_init', 'WebTotemEventListener::hookThemeInstall');
        add_action('admin_init', 'WebTotemEventListener::hookThemeUpdate');
        add_action('admin_init', 'WebTotemEventListener::hookWidgetAdd');
        add_action('admin_init', 'WebTotemEventListener::hookWidgetDelete');

    }

}
