<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *  Class for FastFlow  main
 */

class Fast_Flow_Main {

    function __construct() {
    }

    public static function fastflow_activate() {

        global $wpdb;
		global $fast_tagger_db_version;

		$table_name = $wpdb->prefix . "tags_stats";
		$ref = $wpdb->prefix. "terms";
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name
				(`id` int(11) NOT NULL AUTO_INCREMENT,
			  `term_id` int(11) DEFAULT NULL,
			  `user_id` int(11) NOT NULL,
			  `set_date` datetime DEFAULT '0000-00-00 00:00:00',
			  `unset_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `status` tinyint(1) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		dbDelta( $sql );

		add_option( 'fast_tagger_db_version', $fast_tagger_db_version );

        $wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}fastflow_settings (
                ID int(10) unsigned NOT NULL AUTO_INCREMENT,
                settings_for text NOT NULL,
                settings_data text NOT NULL,
                extra_data text NOT NULL,
                PRIMARY KEY (ID) );" );
    }


	public static function fast_flow_before_init() {
		require_once(FAST_FLOW_DIR . '/includes/fast-tagger-taxonomy.php');
		add_action('init', 'fast_tagger_register_user_taxonomy', 0);
		add_action('init', 'fast_tagger_initial_terms', 1);
    add_action('after_setup_theme', array('Fast_Flow_Main','ff_dashboard_remove_admin_bar'));
    add_action( 'login_head', array('Fast_Flow_Main','ff_dashboard_logo'), 99);
    add_filter( 'login_headerurl', array('Fast_Flow_Main','ff_dashboard_logo_url'));
    add_action('wp_head', array('Fast_Flow_Main','ff_dashboard_favicon'));
		// Fast Tags init
		require_once ( FAST_FLOW_DIR . '/includes/fast-tagger-init.php' );
	}


    public static function fast_flow_main_init() {

		//global $pagenow;
        add_action('admin_menu', array('Fast_Flow_Main', 'fast_flow_admin_menu'),20);
        add_action('admin_enqueue_scripts', array('Fast_Flow_Main', 'fast_flow_load_scripts'));

		require_once(FAST_FLOW_DIR . '/includes/fast-tagger-users-functions.php');

		//add custom column Tags in users list
		add_filter('manage_users_columns', 'fast_tagger_modify_user_table');

		//add values for column Tags in users list
		add_filter('manage_users_custom_column', 'fast_tagger_modify_user_table_row', 10, 3);

		// add fast tags option
		add_action('show_user_profile', 'fast_tagger_user_profile');
		add_action('edit_user_profile', 'fast_tagger_user_profile');

		// save fast tag option
		add_action('personal_options_update', 'fast_tagger_save_profile');
		add_action('edit_user_profile_update', 'fast_tagger_save_profile');

    if( !class_exists('Fast_Flow_Addons') ) {
        require FAST_FLOW_DIR . '/lib/class.fastflow-addons.php';
    }
    add_action('wp_ajax_nopriv_fm_get_tab_data', array('Fast_Flow_Addons', 'fm_get_tab_data'));
    add_action('wp_ajax_fm_get_tab_data', array('Fast_Flow_Addons', 'fm_get_tab_data'));

		//check updates
        //add_filter('pre_set_site_transient_update_plugins', array('Fast_Flow_Main', 'fast_flow_check_update'));

	/*

			//$screen = get_current_screen();

			$log = print_r($pagenow,true);

			error_log("<pre>".$log."</pre>");

			//error_log("<pre>".$log."</pre>");

			//for ff widgets page

			if($pagenow == 'admin.php' && $_REQUEST['page'] == 'fast-flow-widgets'){

				$scripts = array('admin-widgets','jquery-ui-widgets','jquery-ui-draggable','jquery-ui-sortable','jquery-effects-shake');

				foreach ( $scripts as $script ) {

					wp_enqueue_script( $script );

				}

			}

			//for ff dashboard

			if($pagenow == 'admin.php' && $_REQUEST['page'] == 'fast-flow'){

				$scripts = array('common','wp-lists','postbox');

				foreach($scripts as $script){

					wp_enqueue_script($script);

				}

			}



            wp_enqueue_script('ff-jquery-ui','https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), '', true);

            wp_enqueue_style('ff-jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

			//wp_enqueue_script('ff-dashboard', FAST_FLOW_URL . 'includes/js/ff-dashboard.js', array(), '1.0', true);		*/

    }

    public static function ff_dashboard_remove_admin_bar() {
      global $wpdb;
      $dashboard_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fastflow_settings
                                      WHERE settings_for=%s", 'FF_Dashboard' ) );
      if( ($dashboard_data)) {
        $dashboard_options = empty( $dashboard_data->settings_data ) ? array() : unserialize( $dashboard_data->settings_data );
        $dashboard_hide_admin_bar = empty( $dashboard_options['dashboard_hide_admin_bar'] ) ? '' : $dashboard_options['dashboard_hide_admin_bar'];

        if($dashboard_hide_admin_bar){
          if (!current_user_can('administrator') && !is_admin()) {
              show_admin_bar(false);
          }
        }
      }
    }

    public static function ff_dashboard_logo() {
      global $wpdb;
      $dashboard_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fastflow_settings
                                      WHERE settings_for=%s", 'FF_Dashboard' ) );
      if( ($dashboard_data)) {
        $dashboard_options = empty( $dashboard_data->settings_data ) ? array() : unserialize( $dashboard_data->settings_data );
        $dashboard_logo = empty( $dashboard_options['dashboard_logo'] ) ? '' : $dashboard_options['dashboard_logo'];
        if($dashboard_logo){
          $image = wp_get_attachment_image_src($dashboard_logo, 'thumbnail');
          if($image){
            echo '<link rel="shortcut icon" href="'.$image[0].'" >';
            echo '<style type="text/css">'.
                     '.login h1 a { background-image:url('.$image[0].') !important; }'.
                 '</style>';
          }
        }
      }
    }

    public static function ff_dashboard_logo_url(){
      global $wpdb;
      $dashboard_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fastflow_settings
                                      WHERE settings_for=%s", 'FF_Dashboard' ) );
      if( ($dashboard_data)) {
        $dashboard_options = empty( $dashboard_data->settings_data ) ? array() : unserialize( $dashboard_data->settings_data );
        $dashboard_logo = empty( $dashboard_options['dashboard_logo'] ) ? '' : $dashboard_options['dashboard_logo'];
        if($dashboard_logo){
          return site_url();
        }
      }
    }

    public static function ff_dashboard_favicon(){
      global $wpdb;
      $dashboard_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fastflow_settings
                                      WHERE settings_for=%s", 'FF_Dashboard' ) );
      if( ($dashboard_data)) {
        $dashboard_options = empty( $dashboard_data->settings_data ) ? array() : unserialize( $dashboard_data->settings_data );
        $dashboard_logo = empty( $dashboard_options['dashboard_logo'] ) ? '' : $dashboard_options['dashboard_logo'];
        if($dashboard_logo){
          $image = wp_get_attachment_image_src($dashboard_logo, 'thumbnail');
          echo '<link rel="shortcut icon" href="'.$image[0].'" >';
        }
      }
    }


    public static function fast_flow_load_scripts() {

			global $pagenow;

			$pages = array('fast-flow','fast-flow-widgets','fast-flow-addons','fast-flow-settings','fast-flow-act');

			$current_page = isset($_REQUEST['page']) ? $_REQUEST['page'] : '';

			if(!is_admin() || !in_array($current_page,$pages))
				return;

			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_style('ff-jquery-ui', FAST_FLOW_URL . 'assets/css/jquery-ui.min.css');
      wp_enqueue_script('ff-jquery-ui-script', FAST_FLOW_URL . 'assets/js/jquery-ui.min.js', array(), '1.0', true);

		//for ff widgets page
			if($pagenow == 'admin.php' && $current_page == 'fast-flow-widgets'){
				$scripts = array('admin-widgets','jquery-ui-widgets','jquery-ui-draggable','jquery-ui-sortable','jquery-effects-shake');

				foreach ( $scripts as $script ) {

					wp_enqueue_script( $script );

				}

			}

		//for ff dashboard
    		if($pagenow == 'admin.php' && $current_page == 'fast-flow'){
				$scripts = array('common','wp-lists','postbox');

				foreach($scripts as $script){

					wp_enqueue_script($script);

				}

                //Sanjucta
				wp_enqueue_script('ff-dashboard', FAST_FLOW_URL . 'assets/js/ff-dashboard.js');
                //Sanjucta end
			}

		//for ff addons

			if($pagenow == 'admin.php' && $current_page == 'fast-flow-addons'){
				wp_enqueue_style( 'dashicons' );
			}

			//wp_enqueue_script('ff-jdatepicker','https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'));

            //wp_enqueue_style('chart-styles', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

		//ff plugin's charts js and css files

            //wp_enqueue_script('ff-jdatepicker-script', FAST_FLOW_URL . 'includes/js/jquery-datepicker.min.js', array('jquery'), '1.0');
						wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_style('ff-jdatepicker-styles', FAST_FLOW_URL . 'assets/css/jquery-datepicker.min.css');

		//ff plugin's charts js and css files

            wp_enqueue_script('chart-script', FAST_FLOW_URL . 'assets/js/chartist.min.js', array('jquery'), '1.0');

            wp_enqueue_style('chart-styles', FAST_FLOW_URL . 'assets/css/chartist.min.css');





		//ff plugin's custom js and css files

            wp_register_script('fastflow-script', FAST_FLOW_URL . 'assets/js/fastflow-script.js', array('jquery'), '1.0', TRUE);
            wp_localize_script('fastflow-script', 'myajax', array('ajaxurl' => admin_url('admin-ajax.php')));
            wp_enqueue_script('fastflow-script');

            wp_enqueue_style('fastflow-styles', FAST_FLOW_URL . 'assets/css/fastflow-style.css');







/*             //wp_enqueue_script('fastflow-settings-script', FAST_FLOW_URL . 'includes/js/fastflow-settings.js', array('jquery'), '1.0', true);

            //wp_enqueue_style('fastflow-settings-styles', FAST_FLOW_URL . 'includes/css/fastflow-settings.css');



        //}

        if ( !empty( $_GET['page'] ) && $_GET['page'] == 'fast-flow-addons' ) {

            wp_enqueue_script('fastflow-addons-scripts', FAST_FLOW_URL . 'includes/js/fastflow-addons-inact.js');

            wp_enqueue_style('fastflow-addons-styles', FAST_FLOW_URL . 'includes/css/fastflow-addons-inact.css');



        }

 */

	}



    public static function fast_flow_admin_menu() {

        add_submenu_page(FAST_FLOW_PLUGIN_SLUG, 'Add Ons - FastFlow', 'Add Ons', 'manage_options', FAST_FLOW_PLUGIN_SLUG.'-addons', array('Fast_Flow_Main', 'fast_flow_addons'));
        add_submenu_page(FAST_FLOW_PLUGIN_SLUG, 'Settings - FastFlow', 'Settings', 'manage_options', FAST_FLOW_PLUGIN_SLUG.'-settings', array('Fast_Flow_Main', 'fast_flow_settings'));
        add_submenu_page(FAST_FLOW_PLUGIN_SLUG, 'License - FastFlow', 'License', 'manage_options', FAST_FLOW_PLUGIN_SLUG.'-act', array('Fast_Flow_Main', 'fast_flow_act_page'));
		require_once(FAST_FLOW_DIR . '/includes/fast-tagger-pages.php');

	    add_menu_page('All Tagged Users', 'Fast Tags', 'manage_options', 'fast-tagger', 'fast_tags_list', 'dashicons-tag', 4);
	    add_submenu_page('fast-tagger', 'All Tags', 'All Tags', 'manage_options', 'fast-tagger', 'fast_tags_list');
	    add_submenu_page('fast-tagger', 'Add New Tag', 'Add New Tag', 'manage_options', 'edit-tags.php?taxonomy=fast_tag');
	    add_submenu_page('fast-tagger', 'All Tagged Users', 'Tagged Users', 'manage_options', 'fast_tagged_users', 'fast_tagged_users');

    }



    public static function fast_flow_addons() {
				if( !class_exists('Fast_Flow_Addons') ) {
						require FAST_FLOW_DIR . '/lib/class.fastflow-addons.php';
				}
        if( !isset( $_REQUEST['fastflow_action'] ) || empty($_REQUEST['fastflow_action']) ) {
					echo '<div class="wrap">';
					echo '<h2>Add Ons</h2>';
					if (class_exists('Fast_Flow_Addons')) {
						echo Fast_Flow_Addons::get_addons_content();
					}
					echo '</div>';
        }

        if( isset( $_REQUEST['fastflow_action'] ) && $_REQUEST['fastflow_action'] == 'fastflow-plugin-install' ) {
            //echo 'Came here 1';
					if (class_exists('Fast_Flow_Addons')) {
            Fast_Flow_Addons::process_addons_install();
					}
        }

        if( isset( $_REQUEST['fastflow_action'] ) && $_REQUEST['fastflow_action'] == 'fastflow-plugin-activate' ) {
            //echo 'Came here 11';
            //error_log('Check: Came here 11');
            if (class_exists('Fast_Flow_Addons')) {
							Fast_Flow_Addons::activate_fastflow_installed_plugin();
						}
        }

        if( isset( $_REQUEST['fastflow_action'] ) && $_REQUEST['fastflow_action'] == 'wp-repository-plugin-install' ) {
          if ( ! current_user_can( 'install_plugins' ) ) {
            wp_die( __( 'Sorry, you are not allowed to install plugins on this site.' ) );
          }
          if ( !class_exists('Plugin_Upgrader') ) {
            include( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
    			}
          include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
          $plugin = $_REQUEST['wp_repository_slug'];
          check_admin_referer( 'wp_recommended_' . $plugin );
          $api = plugins_api(
            'plugin_information',
            array(
              'slug'   => $plugin,
              'fields' => array(
                'sections' => false,
              ),
            )
          );

          if ( is_wp_error( $api ) ) {
            wp_die( $api );
          }

          $title        = __( 'Plugin Installation' );
          $parent_file  = 'plugins.php';
          $submenu_file = 'plugin-install.php';

          $title = sprintf( __( 'Installing Plugin: %s' ), $api->name . ' ' . $api->version );
          $nonce = 'install-plugin_' . $plugin;
          $url   = 'update.php?action=install-plugin&plugin=' . urlencode( $plugin );
          $type = 'web';

          $upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin( compact( 'title', 'url', 'nonce', 'plugin', 'api' ) ) );
          $upgrader->install( $api->download_link );
          echo 'WpRepositoryPlugInInstalled';
          exit;
        }

        if( isset( $_REQUEST['fastflow_action'] ) && $_REQUEST['fastflow_action'] == 'wp-repository-plugin-activate' ) {
          $plugin_slug = stripslashes( $_REQUEST['wp_repository_slug'] );
          $pluginArr = get_plugins( '/'.$plugin_slug  );
          $plugin_file = array_keys($pluginArr);
          $plugin = $plugin_slug . '/' . $plugin_file[0];
          check_admin_referer( 'wp_recommended_' . $plugin_slug );

          $result = activate_plugin($plugin);

          if (!is_wp_error($result)) {
              echo "WpRepositoryPluginActivated";
              exit;
          }
        }

        if( isset( $_REQUEST['fastflow_action'] ) && $_REQUEST['fastflow_action'] == 'fastflow-repository-plugin-install' ) {
          if ( ! current_user_can( 'install_plugins' ) ) {
            wp_die( __( 'Sorry, you are not allowed to install plugins on this site.' ) );
          }
          if ( !class_exists('Plugin_Upgrader') ) {
            include( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
    			}
          include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
          $plugin = $_REQUEST['fastflow_repository_slug'];
          check_admin_referer( 'fastflow_recommended_' . $plugin );
          $api = plugins_api(
            'plugin_information',
            array(
              'slug'   => $plugin,
              'fields' => array(
                'sections' => false,
              ),
            )
          );

          if ( is_wp_error( $api ) ) {
            wp_die( $api );
          }

          $title        = __( 'Plugin Installation' );
          $parent_file  = 'plugins.php';
          $submenu_file = 'plugin-install.php';

          $title = sprintf( __( 'Installing Plugin: %s' ), $api->name . ' ' . $api->version );
          $nonce = 'install-plugin_' . $plugin;
          $url   = 'update.php?action=install-plugin&plugin=' . urlencode( $plugin );
          $type = 'web';

          $upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin( compact( 'title', 'url', 'nonce', 'plugin', 'api' ) ) );
          $upgrader->install( $api->download_link );
          echo 'FastflowRepositoryPlugInInstalled';
          exit;
        }

        if( isset( $_REQUEST['fastflow_action'] ) && $_REQUEST['fastflow_action'] == 'fastflow-repository-plugin-activate' ) {
          $plugin_slug = stripslashes( $_REQUEST['fastflow_repository_slug'] );
          $pluginArr = get_plugins( '/'.$plugin_slug  );
          $plugin_file = array_keys($pluginArr);
          $plugin = $plugin_slug . '/' . $plugin_file[0];
          check_admin_referer( 'fastflow_recommended_' . $plugin_slug );

          $result = activate_plugin($plugin);

          if (!is_wp_error($result)) {
              echo "FastflowRepositoryPluginActivated";
              exit;
          }
        }
    }



    public static function fast_flow_settings() {

			if( !class_exists('Fast_Flow_Settings') ) {
        require FAST_FLOW_DIR . '/lib/class.fastflow-settings.php';
			}

			if( class_exists('Fast_Flow_Settings') ) {
        Fast_Flow_Settings::fast_flow_process_settings_data();
			}

        echo '<div class="wrap">';
        echo '<h2>Settings</h2><br />';

        if( !empty($_GET['msg']) && $_GET['msg'] == 'update' ) {

            echo '<div id="message" class="updated notice is-dismissible"><p>Settings <strong>updated</strong>.</p>

                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';

        }

				if( class_exists('Fast_Flow_Settings') ) {
					echo Fast_Flow_Settings::fast_flow_settings_page_content();
				}
        echo '</div>';
    }





    /*public static function fast_flow_check_update($checked_data) {

        if (!class_exists('Fast_Flow_Addons')) {
            require FAST_FLOW_DIR . '/lib/class.fastflow-addons.php';
        }

        if (class_exists('Fast_Flow_Addons')) {
            $fcbp_api_url = Fast_Flow_Addons::get_sonod_url();
            $fcbp_plugin_slug = Fast_Flow_Addons::get_slug_string();

            //echo var_dump($checked_data);
            if (empty($checked_data->checked)) {
                error_log("Got empty from FCB update check");
                return $checked_data;
            }

            $current_version = $checked_data->checked[$fcbp_plugin_slug .'/'. $fcbp_plugin_slug .'.php'];
            $request_param = array(
                'body' =>Fast_Flow_Addons::get_param_data('update')
            );

            // Start checking for an update
            $raw_response = wp_remote_post($fcbp_api_url, $request_param);
        }

        if (isset($raw_response) && is_wp_error($raw_response)) {
            error_log("Got error from FCB update check remote request");
        }

        if (isset($raw_response) && !is_wp_error($raw_response)
                && ($raw_response['response']['code'] == 200)) {

            error_log("Got data from FF update check remote request");
            $response = unserialize($raw_response['body']);
            $loggvar = "<pre>" . print_r($response, true) . "</pre>";
			error_log("FF: log up: " . $loggvar);
        }

        if (is_object($response) && !empty($response)) { // Feed the update data into WP updater
            $obj = new stdClass();
            $obj->name = $response->name;
            $obj->slug = $fcbp_plugin_slug;
            $obj->url = $response->url;
            $obj->version = $current_version;
            $obj->plugin = $fcbp_plugin_slug .'/'. $fcbp_plugin_slug .'.php';
            $obj->requires = $response->requires;
            $obj->tested = $response->tested;
            $obj->last_updated = $response->last_updated;
            if ( version_compare( $current_version, $response->new_version, '<' ) ) {
                $obj->new_version = $response->new_version;
                $obj->package = $response->package;
                $checked_data->response[$fcbp_plugin_slug .'/'. $fcbp_plugin_slug .'.php'] = $obj;
            } else {
                $checked_data->no_update[$fcbp_plugin_slug .'/'. $fcbp_plugin_slug .'.php'] = $obj;
            }
        }

        $loggvar = "<pre>" . print_r($checked_data, true) . "</pre>";
        error_log("FF: log up2: " . $loggvar);

        return $checked_data;
    }*/





    public static function process_act_data() {
        if (isset($_REQUEST['activate_sonod'])) {
            $api_params = self::get_param_data('at');
            $act_data = stripslashes($_REQUEST['fastflow_sonod']);
        } else if (isset($_REQUEST['deactivate_sonod'])) {
            $api_params = self::get_param_data('dot');
            $act_data = "";
        } else { return; }

        $postURL = self::get_sonod_url();
        $query = esc_url_raw(add_query_arg($api_params, $postURL));
        $response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

        if (is_wp_error($response)){
            echo '<div class="sonodresult error"><p><strong>Unexpected Error</strong>! The query returned with an error.</p></div>';
        }

        $recv_data = json_decode(wp_remote_retrieve_body($response));

        if($recv_data->result == 'success'){
            update_option('_fastflow_sonod_data', $act_data);
            echo '<div class="sonodresult updated notice is-dismissible"><p><strong>'.$recv_data->message.'</strong>.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
        } else {
            echo '<div class="sonodresult error"><p><strong>'.$recv_data->message.'</strong>.</p></div>';
        }

        return $recv_data;
    }



    public static function fast_flow_act_page(){

	?>

	<div class="wrap">
            <h2>License</h2>

            <iframe width="560" height="315" src="https://www.youtube.com/embed/BenMUC7W9GI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

            <?php  $recv_data = self::process_act_data();  ?>

            <p>Enter your FastFlow license key below to activate and install any Add-Ons.</p>
            <p>You can find your licence key inside the <a href="https://fastflow.io/members/licence/">Fast Flow members area.</a></p>

            <form action="" method="post">
                <table class="form-table">
                    <tr>
                            <th style="width:100px;"><label for="fastflow_sonod">License Key</label></th>
                            <td ><input class="regular-text" type="password" id="fastflow_sonod" name="fastflow_sonod"  value="<?php echo get_option('_fastflow_sonod_data'); ?>" ></td>
                    </tr>
                </table>
                <p class="submit">
                        <input type="submit" name="activate_sonod" value="Activate" class="button-primary" />
                        <input type="submit" name="deactivate_sonod" value="Deactivate" class="button" />
                </p>

                <?php if(isset($recv_data->date_expiry)){ echo $recv_data->date_expiry; } ?>

            </form>
	</div>

    <?php

    }



    public static function get_param_data($id) {

        $data = array();
        $idx_arr = array("oe", "to", "ree", "fr", "fv");
        foreach ($idx_arr as $value) {
            $data[self::get_idx_string("dt".$value)] = self::get_val_string("vl".$value, $id);
        }
        return $data;
    }



    public static function get_idx_string($name) {

        if ($name == "dtoe" ) { $value = "s" . "lm_a" . "ction"; }

        if ($name == "dtto" ) { $value = "se" . "cret_k" . "ey"; }

        if ($name == "dtree" ) { $value = "li" . "cense_ke" . "y"; }

        if ($name == "dtfr" ) { $value = "regis" . "tered_dom" . "ain"; }

        if ($name == "dtfv" ) { $value = "ite" . "m_refe" . "rence"; }

        return $value;
    }



    public static function get_sonod_url() {
        $protcl = "https" . "://";
        $w3 = "auth";
        $comm = ".fastflow";
        return $protcl . $w3 . $comm . ".io/wp/";
    }



    public static function get_val_string($name, $id) {

        if ($name == "vloe" ) {
            $value = $id === "at" ? "sl" . "m_act" . "ivate" : "sl" . "m_deac" . "tivate";
        }

        if ($name == "vlto" ) { $value = "598fc" . "ac34f" . "f5a5"
                                        . "." . "6721" . "7918"; }

        if ($name == "vlree" ) { $value = stripslashes($_REQUEST['fastflow_sonod']); }

        if ($name == "vlfr" ) { $value = $_SERVER['SERVER_NAME']; }

        if ($name == "vlfv" ) { $value = "fa" . "stfl" . "ow"; }

        return $value;

    }

}
