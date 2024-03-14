<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *  show Addons page
 */

class Fast_Flow_Addons {
    function __construct() {

    }

    public static function get_addons_content() {
        $license_key = get_option('_fastflow_sonod_data');
        //$value_arr = self::get_addons_data();
        $count = 0; $status = "not_installed";
        $return_html = '';
        $return_html .= '<div class="tabbed_area" id="addons">
        <h3 class="nav-tab-wrapper">
  					    <a href= "#" title="licenced"  class="nav-tab nav-tab-active">Licenced</a>
  					    <a href= "#" title="available" class="nav-tab">Available</a>
                <a href= "#" title="free" class="nav-tab">Free</a>
  					    <a href= "#" title="recommended" class="nav-tab">Recommended</a></h3>';
                $return_html .= '<div class="fmclear"></div>';
        $return_html .= '<div id="licenced" class="tabcontent">';
        $return_html .= '<div id="fastflow-addons-cont" class="feature-section three-col">';

        $return_html .= '</div>';
        $return_html .= '</div>';




        $return_html .= '<div id="available" class="tabcontent" style="display: none;"">';
        $return_html .= '<div id="fastflow-addons-cont" class="feature-section three-col">';

        $return_html .= '</div>';
        $return_html .= '</div>';


        $return_html .= '<div id="free" class="tabcontent" style="display: none;">';
        $return_html .= '<div id="fastflow-addons-cont" class="feature-section three-col">';

        $return_html .= '</div>';
        $return_html .= '</div>';



        $return_html .= '<div id="recommended" class="tabcontent" style="display: none;">';
        $return_html .= '<div id="fastflow-addons-cont" class="feature-section three-col">';

        $return_html .= '</div>';
        $return_html .= '</div>';

        $return_html .= '</div>';
        return $return_html;
    }

    public static function get_idx_string($name, $pref) {
        if ($name == "dtoe" ) { $value = $pref . "ata_act" . "ion"; }
        if ($name == "dtto" ) { $value = $pref . "ata_sl" . "ug"; }
        if ($name == "dtree" ) { $value = $pref . "ata_id"; }
        if ($name == "dtfr" ) { $value = $pref . "ata_lo" . "catn"; }
        return $value;
    }


    public static function get_slug_string() {
        return 'fastflow';
    }


    public static function check_addons_api($obj) {
        if(!empty($obj->slug) && isset($obj->slug)) {
            $api = new stdClass();
            $api->name = $obj->name;
            $api->slug = $obj->slug;
            $api->plugin = $api->slug .'/'. $api->slug .'.php';
            $api->url = "https://fastflow.io/tutorials/" . $api->slug;
            $api->download_link = $obj->package;
            $api->requires = $obj->requires;
            $api->tested = $obj->tested;
            $api->new_version = $obj->new_version;
            $api->last_updated = $obj->last_updated;
            $api->version = $obj->new_version;
            if ( ! function_exists( 'get_plugins' ) ) {
                require ABSPATH . 'wp-admin/includes/plugin.php';
            }
            $api_plugin_data = get_plugins();
            $api_plugins = array();
            $status = "not_installed";
            foreach ($api_plugin_data as $key => $value) {
                $api_plugins[] = $key;
            }
            if (in_array($api->plugin, $api_plugins)) {
                $status = "installed";
                $api->version = $api_plugin_data[$api->plugin]["Version"];
            }
            //$loggvar = "<pre>" . print_r($api_plugin_data, true) . "</pre>";
            //error_log( "FastFlow: Plugins Data: " . $loggvar );
            $api_arr = array($api);
            update_option("fastflow-plugin-{$obj->slug}-api-data", serialize($api_arr));
            return $status;
        }
    }

    public static function get_param_data($type='') {
        $data = array();
        $pref = "_ff_d";
        $id = $type;
        $idx_arr = array("oe", "to", "ree", "fr");
        foreach ($idx_arr as $value) {
            $data[self::get_idx_string("dt".$value, $pref)] = self::get_val_string("vl".$value, $id);
        }
        return $data;
    }


    public static function get_addons_data() {
        $postURL = self::get_sonod_url();
        $params = self::get_param_data();
				$request_param = array(
					'timeout' => 90,
					'body'    => $params
				);
				$raw_response = wp_remote_post($postURL, $request_param);
        /*$ch2 = curl_init ($postURL);
        curl_setopt ($ch2, CURLOPT_POST, true);
        curl_setopt ($ch2, CURLOPT_POSTFIELDS, $params);
        curl_setopt ($ch2, CURLOPT_RETURNTRANSFER, true);
        $returnValue2 = curl_exec ($ch2);
        curl_close($ch2);*/
				if (isset($raw_response) && !is_wp_error($raw_response)
          && ($raw_response['response']['code'] == 200)) {

					$returnValue = unserialize(stripslashes($raw_response['body']));
				}
        $loggvar = "<pre>" . print_r($params, true) . "</pre>";
        error_log( "FastFlow: log1 " . $loggvar );
        $loggvar = "<pre>" . print_r($raw_response, true) . "</pre>";
        error_log( "FastFlow: log2 " . $loggvar );
        return $returnValue;
    }



    public static function get_val_string($name, $id) {
        if ($name == "vloe" ) {
            $value = ($id === "update") ? "ge" . "t_pro" . "d_infr" . "matn" : "g" . "et_p" . "rods";
        }
        if ($name == "vlto" ) { $value = self::get_slug_string(); }
        if ($name == "vlree" ) { $value = self::get_data_string(); }
        if ($name == "vlfr" ) { $value = $_SERVER['SERVER_NAME']; }
        return $value;
    }

    public static function process_addons_install() {
        //echo 'Came here 2';
			if ( !class_exists('Plugin_Upgrader') ) {
        include( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			}
        //include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); //for plugins_api..

        $plugin_slug = stripslashes( $_REQUEST['fastflow_addons_slug'] );
        $api_arr = unserialize(get_option("fastflow-plugin-{$plugin_slug}-api-data"));
        $api = $api_arr[0];
        //echo 'Came here 3';
        //echo "<pre>" . print_r( $api, true ) . "</pre>";

        check_admin_referer( "fastflow_{$api->slug}_insact_action" );
        //$api = plugins_api('plugin_information', array('slug' => $plugin, 'fields' => array('sections' => false) ) ); //Save on a bit of bandwidth.

        if ( is_wp_error($api) ) {
          wp_die($api);
				}
        //delete_option( "fastflow-plugin-{$plugin_slug}-api-data" );
        $title = __('Plugin Install');
        $parent_file = 'plugins.php';
        $submenu_file = 'plugin-install.php';
        require_once(ABSPATH . 'wp-admin/admin-header.php');

        $title = sprintf( __('Installing Plugin: %s'), $api->name . ' ' . $api->version );
        $nonce = 'install-plugin_' . $api->slug;
        $url = ABSPATH . 'wp-admin/update.php?action=install-plugin&plugin=' . urlencode( $api->slug );
        if ( isset($_GET['from']) )
            $url .= '&from=' . urlencode(stripslashes($_GET['from']));

        $type = 'web'; //Install plugin type, From Web or an Upload.

				if ( class_exists('Plugin_Upgrader') ) {
					$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin( compact('title', 'url', 'nonce', 'plugin', 'api') ) );
					$result = $upgrader->install($api->download_link);
				}

        if( !empty($result) && !is_wp_error($result) && $result === true ) {
            echo "FastFlowAddOnPlugInInstalled";
            exit;
        } else {
            echo '<p><a href="' . admin_url('admin.php?page=fast-flow-addons') . '" target="_parent">Return to Fastflow Addons Page</a></p>';
            exit;
        }
    }

    public static function get_data_string() {
        $data = get_option('_fastflow_sonod_data');
        if ( !empty($data) ) {
            return $data;
        } else {
            return '';
        }
    }

    public static function activate_fastflow_installed_plugin() {
        $plugin_slug = stripslashes( $_REQUEST['fastflow_addons_slug'] );
        $plugin = $plugin_slug . '/' . $plugin_slug . '.php';
        error_log('Check: ' . $plugin_slug);
        check_admin_referer( "fastflow_{$plugin_slug}_insact_action" );

        $result = activate_plugin($plugin);

        if (!is_wp_error($result)) {
            echo "FastFlowPluginActivated";
            exit;
        }
    }


    public static function get_sonod_url() {
        $protcl = "https" . "://";
        $w3 = "auth";
        $comm = ".fastflow";
        return $protcl . $w3 . $comm . ".io/wp/";
    }

    public static function fm_get_tab_data(){
      if (defined( 'DOING_AJAX' ) && DOING_AJAX ){
        $return_html = '';
        if(isset($_POST['value']) && !empty($_POST['value'])){
          $postURL = self::get_sonod_url();
          $params = self::get_param_data();
          $params['_ff_data_action'] = 'get_prod';
          $params['_ff_data_source'] = esc_attr($_POST['value']);
          $request_param = array(
            'timeout' => 90,
            'body'    => $params
          );
          $raw_response = wp_remote_post($postURL, $request_param);
          if (isset($raw_response) && !is_wp_error($raw_response)
            && ($raw_response['response']['code'] == 200)) {
            if(is_serialized(stripslashes($raw_response['body']))){
              $returnValue = unserialize(stripslashes($raw_response['body']));
            }else{
              $returnValue = [];
            }
          }
          $loggvar = "<pre>" . print_r($params, true) . "</pre>";
          error_log( "FastFlow: Tab log1 " . $loggvar );
          $loggvar = "<pre>" . print_r($raw_response, true) . "</pre>";
          error_log( "FastFlow: Tab log2 " . $loggvar );
          if(is_array($returnValue) && !empty($returnValue)){
            $count = 0; $status = "not_installed";
            if(array_key_exists('licensed_prods', $returnValue)){
              if(!empty($returnValue['licensed_prods'])){
                foreach($returnValue['licensed_prods'] as $obj) {
                    if(!empty($obj->slug) && isset($obj->slug)) {
                        $count++;
                        $status = self::check_addons_api($obj);
                        $return_html .= '<div class="col fastflow-addon">
                                <a href="https://fastflow.io/tutorials/' . $obj->slug . '/" target="_blank">
                                    <span class="product-image-loop"><img src="' . $obj->img_url . '" class="attachment-shop_catalog wp-post-image center" alt="' . $obj->name . '" width="100" height="100"></span>
                                </a>
                                <div class="fastflow-addon-bottom">
                                    <span><a href="https://fastflow.io/tutorials/' . $obj->slug . '/" target="_blank">' . $obj->name . '</a></span>';

                          if ($status == 'not_installed') {
                              $return_html .= '<p><button id="fastflow_btn_' . $count . '" class="btn btnclk-install" data-slug="' . $obj->slug . '" data-wpnonce="' . wp_create_nonce( "fastflow_{$obj->slug}_insact_action" ) . '" type="button" name="fastflow-plugin-submit">Install Now</button></p>';
                          } elseif ($status == 'installed' && !is_plugin_active($obj->slug .'/'. $obj->slug .'.php')) {
                              $return_html .= '<p><button id="fastflow_btn_' . $count . '" class="btn btnclk-activate" data-slug="' . $obj->slug . '" data-wpnonce="' . wp_create_nonce( "fastflow_{$obj->slug}_insact_action" ) . '" type="button" name="fastflow-plugin-submit">Activate</button></p>';
                          } elseif (is_plugin_active($obj->slug .'/'. $obj->slug .'.php')) {
                              $return_html .= '<p><button id="fastflow_btn_' . $count . '" class="btn disabled-btn" disabled="disabled" type="button" name="fastflow-plugin-submit">Active</button></p>';
                          }


                        $return_html .= '</div>
                            </div>';
                    }
                }
              }else{
                $return_html .= '<a href="'.admin_url('admin.php?page=fast-flow-act').'" target="_blank">To access your Licensed Add Ons please enter your licence key</a>';
              }
            }else if(array_key_exists('unlicensed_prods', $returnValue)){
              if(!empty($returnValue['unlicensed_prods'])){
                foreach($returnValue['unlicensed_prods'] as $obj) {
                    if(!empty($obj->slug) && isset($obj->slug)) {
                        $count++;
                        $status = self::check_addons_api($obj);
                        $return_html .= '<div class="col fastflow-addon">
                                <a href="https://fastflow.io/products/' . $obj->slug . '/" target="_blank">
                                    <span class="product-image-loop"><img src="' . $obj->img_url . '" class="attachment-shop_catalog wp-post-image center" alt="' . $obj->name . '" width="100" height="100"></span>
                                </a>
                                <div class="fastflow-addon-bottom">
                                    <span><a href="https://fastflow.io/products/' . $obj->slug . '/" target="_blank">' . $obj->name . '</a></span>';

                          $return_html .= '<p><a id="fastflow_btn_' . $count . '" class="link-btn" target="_blank" href="https://fastflow.io/products/'.$obj->slug.'">Get Access</a></p>';


                        $return_html .= '</div>
                            </div>';
                    }
                }
              }else{
                $return_html .= '';
              }

            }else if(array_key_exists('free_prods', $returnValue)){
              if(!empty($returnValue['free_prods'])){
                foreach($returnValue['free_prods'] as $obj) {
                    if(!empty($obj->package) && isset($obj->package)) {
                        $count++;
                        $return_html .= '<div class="col fastflow-addon">
                                <a href="' . $obj->package . '" target="_blank">
                                    <span class="product-image-loop"><img src="'.$obj->img_url.'" class="attachment-shop_catalog wp-post-image center" alt="' . $obj->name . '" width="100" height="100"></span>
                                </a>
                                <div class="fastflow-addon-bottom">
                                    <span><a href="' . $obj->package . '" target="_blank">' . $obj->name . '</a></span>';
                        $installed_plugin = get_plugins( '/' . $obj->slug );
                        if ( empty( $installed_plugin ) ) {
                          $return_html .= '<p><button id="free_btn_' . $count . '" class="btn fbtnclk-install" data-slug="'.$obj->slug.'" data-wpnonce="'.wp_create_nonce( "fastflow_recommended_{$obj->slug}" ).'" type="button">Install Now</button></p>';
                        }else{
                          $plugin_file = array_keys($installed_plugin);
                          $plugin = $obj->slug . '/' . $plugin_file[0];
                          if ( is_plugin_active($plugin) ) {
                            $return_html .= '<p><button class="btn disabled-btn" disabled="disabled" type="button">Active</button></p>';
              						}else{
                            $return_html .= '<p><button id="free_btn_' . $count . '" class="btn fbtnclk-activate" data-slug="' . $obj->slug . '" data-wpnonce="'.wp_create_nonce( "fastflow_recommended_{$obj->slug}" ).'" type="button">Activate</button></p>';
                          }
                        }

                        $return_html .= '</div>
                            </div>';
                    }
                }
              }else{
                $return_html .= '<a href="'.admin_url('admin.php?page=fast-flow-act').'" target="_blank">To access your Free Add Ons please enter your licence key</a>';
              }

            }else if(array_key_exists('recommended_prods', $returnValue)){
              if(!empty($returnValue['recommended_prods'])){
                foreach($returnValue['recommended_prods'] as $obj) {
                    if(!empty($obj->package) && isset($obj->package)) {
                        $count++;
                        $return_html .= '<div class="col fastflow-addon">
                                <a href="' . $obj->package . '" target="_blank">
                                    <span class="product-image-loop"><img src="'.$obj->img_url.'" class="attachment-shop_catalog wp-post-image center" alt="' . $obj->name . '" width="100" height="100"></span>
                                </a>
                                <div class="fastflow-addon-bottom">
                                    <span><a href="' . $obj->package . '" target="_blank">' . $obj->name . '</a></span>';
                        $installed_plugin = get_plugins( '/' . $obj->slug );
                        if ( empty( $installed_plugin ) ) {
                          $return_html .= '<p><button id="recommended_btn_' . $count . '" class="btn rbtnclk-install" data-slug="'.$obj->slug.'" data-wpnonce="'.wp_create_nonce( "wp_recommended_{$obj->slug}" ).'" type="button">Install Now</button></p>';
                        }else{
                          $plugin_file = array_keys($installed_plugin);
                          $plugin = $obj->slug . '/' . $plugin_file[0];
                          if ( is_plugin_active($plugin) ) {
                            $return_html .= '<p><button class="btn disabled-btn" disabled="disabled" type="button">Active</button></p>';
              						}else{
                            $return_html .= '<p><button id="recommended_btn_' . $count . '" class="btn rbtnclk-activate" data-slug="' . $obj->slug . '" data-wpnonce="'.wp_create_nonce( "wp_recommended_{$obj->slug}" ).'" type="button">Activate</button></p>';
                          }
                        }

                        $return_html .= '</div>
                            </div>';
                    }
                }
              }else{
                $return_html .= '<a href="'.admin_url('admin.php?page=fast-flow-act').'" target="_blank">To access your Recommended Add Ons please enter your licence key</a>';
              }
            }
          }else{
            $return_html .= 'No addons avaliable.';
          }
        }else{
          $return_html .= 'Something went wrong';
        }
        die($return_html);
      }
    }

}
