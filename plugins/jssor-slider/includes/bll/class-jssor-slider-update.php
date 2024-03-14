<?php

if( !defined( 'ABSPATH') ) exit();

/**
 * Class Jssor_Slider_Update
 * @author Neil.zhou
 */
class Jssor_Slider_Update
{
    private $plugin_slug = 'jssor-slider';
    private $plugin_path = 'jssor-slider/jssor-slider.php';
    private $option_key = 'wjssl_update_info';
    private $remote_version_info_url;
    private $remote_update_info_url;
    private $plugin_url;
    private $version;
    private $check_interval;

    private $error;
    private $error_message;

    /**
     * undocumented function
     *
     * @return void
     */
    public function __construct($args = array())
    {
        $default = array(
                'version'        => WP_JSSOR_SLIDER_VERSION,
                'check_interval' => 48 * 3600,
            );
        $args = array_merge(
            $default,
            $args
        );
        $this->version = $args['version'];
        $this->check_interval = $args['check_interval'];
        $this->remote_update_info_url = WP_Jssor_Slider_Globals::URL_JSSOR_SECURE() . '/api2/jssor_slider_update.ashx?method=GetUpdateInfo';
        $this->remote_version_info_url = WP_Jssor_Slider_Globals::URL_JSSOR_SECURE() . '/api2/jssor_slider_update.ashx?method=GetVersionInfo';
    }

    public function has_error()
    {
        return $this->error;
    }

    public function get_error_message()
    {
        return $this->error_message;
    }

    public function get_version()
    {
        return $this->version;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function add_update_check()
    {
        // define the alternative API for updating checking
        add_filter('pre_set_site_transient_update_plugins', array($this, 'set_updates_transient_api'));

        // Define the alternative response for information checking
        add_filter('plugins_api', array($this, 'set_updates_info_api'), 10, 3);
    }


    /**
     * undocumented function
     *
     * @return void
     */
    public function set_updates_transient_api($transient)
    {
		if(isset($transient) && !isset($transient->response)) {
			$transient->response = array();
		}

        // Get remote version
        $this->check_updates();
        if (
            !empty($this->data->basic)
            &&
            version_compare($this->version, $this->data->basic->version, '<')
        ) {
            $transient->response[$this->plugin_path] = $this->data->basic;
        }
        return $transient;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function set_updates_info_api($result, $action, $arg)
    {
        if (isset($arg->slug) && $arg->slug == $this->plugin_slug && $action == 'plugin_information') {
            $this->check_updates();
            if (
                !empty($this->data)
                &&
                is_object($this->data->full)
                &&
                !empty($this->data->full)
            ) {
                $result = $this->data->full;
            }
        }
        return $result;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function check_version_info($force_check = false)
    {
		$last_check = get_option('wjssl-update-check-short', 0);

		// Check for updates
        if(
            ((time() - $last_check) > $this->check_interval)
            ||
            ($force_check == true)
        ){
			update_option('wjssl-update-check-short', time());
            $res = $this->retrieve_remote_version_info();
            if (empty($res)) {
                return false;
            }

			if(isset($res->latest) && isset($res->latest->version)){
				update_option('wjssl-latest-version', $res->latest->version);
			}

			if(isset($res->stable) && isset($res->stable->version)){
				update_option('wjssl-stable-version', $res->stable->version);
			}

			if(isset($res->beta) && isset($res->beta->version)){
				update_option('wjssl-beta-version', $res->beta->version);
			}

            //if(isset($res->deactivated) && $res->deactivated === true){
            //    if(get_option('wjssl-valid', 'false') == 'true'){
            //        //remove validation, add notice
            //        update_option('wjssl-valid', 'false');
            //        update_option('wjssl-deact-notice', true);
            //    }
            //}
        }

		if($force_check == true){ //force that the update will be directly searched
			update_option('wjssl-update-check', 0);
		}
    }


    /**
     * undocumented function
     *
     * @return void
     */
    private function check_updates($force_check = false)
    {
		// Get data
		if(empty($this->data)) {
			$data = get_option($this->option_key, false);
			$data = $data ? $data : new stdClass;

			$this->data = is_object($data) ? $data : maybe_unserialize($data);
		}

		$last_check = get_option('wjssl-update-check', 0);

		// Check for updates
		if(time() - $last_check > $this->check_interval || $force_check == true) {

			$data = $this->get_remote_update_info();

			if(!empty($data) && isset($data->basic)) {
				update_option('wjssl-update-check', time());

				$this->data->checked = time();
				$this->data->basic = $data->basic;
				$this->data->full = $data->full;

                if(isset($data->full) && isset($data->full->stable)){
                    update_option('wjssl-stable-version', $data->full->stable);
                }
                if (! $this->is_update_stable_beta()) {
                    update_option('wjssl-latest-version', $data->full->version);
                }
			}
		}
		// Save results
		update_option($this->option_key, $this->data);
    }

    /**
     * undocumented function
     *
     * @return void
     */
    private function get_remote_update_info()
    {
        $res = $this->request_remote_update_info();
        if (empty($res) || !is_object($res) || empty($res->definition)) {
            return false;
        }
        $res = $res->definition;

        if (isset($res->full, $res->full->sections) && !is_array($res->full->sections)) {
            $res->full->sections = (array) $res->full->sections;
        }

        if (!isset($res->basic->new_version)) {
            $res->basic->new_version = $res->basic->version;
        }
        if (!isset($res->full->new_version)) {
            $res->full->new_version = $res->full->version;
        }
        return $res;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function request_remote_update_info()
    {
        global $wp_version;
        $data = array(
            'jssorext' => WP_JSSOR_SLIDER_EXTENSION_NAME,
            'hosturl' => esc_url_raw(WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url()),
            'instid' => get_option('wp_jssor_slider_instance_id', ''),
            'acckey' => get_option('wjssl_acckey', ''),
            'type' => $this->get_update_type(),
            'instver' => $wp_version,
            'extver' => get_option('wjssl-latest-version', $this->version)
        );

        $remote_url = esc_url_raw($this->remote_update_info_url);
        $accessible_url = WP_Jssor_Slider_Utils::to_accessible_jssor_url($remote_url);
        $request = wp_remote_post($accessible_url, array(
            'body' => array(
                'data' => json_encode($data)
            ),
            'timeout' => 30
        ));
        if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
            return json_decode($this->trimBOM($request['body']));
        }
        return false;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    private function is_update_stable_beta()
    {
        if (!empty($_GET['update_jssor'])) {
            if ($_GET['update_jssor'] == 'stable' || $_GET['update_jssor'] == 'beta') {
                return true;
            }
        }
        return false;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    private function get_update_type()
    {
        if (!empty($_GET['update_jssor'])) {
            $array = array('stable', 'beta');
            if (in_array($_GET['update_jssor'], $array)) {
                return $_GET['update_jssor'];
            }
        }
        return 'latest';
    }

    /**
     * undocumented function
     *
     * @return void
     */
    private function retrieve_remote_version_info()
    {
        global $wp_version;
        $data = array(
            'jssorext' => WP_JSSOR_SLIDER_EXTENSION_NAME,
            'hosturl' => esc_url_raw(WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url()),
            'instid' => get_option('wp_jssor_slider_instance_id', ''),
            'purchcode' => get_option('wjssl_purchcode', ''),
            'instver' => $wp_version,
            'extver' => $this->version
        );

        $remote_url = esc_url_raw($this->remote_version_info_url);
        $accessible_url = WP_Jssor_Slider_Utils::to_accessible_jssor_url($remote_url);
        $response = wp_remote_post($accessible_url, array(
            'body' => array(
                'data' => json_encode($data)
            ),
            'timeout' => 30
        ));

        if (!is_wp_error($response) || wp_remote_retrieve_response_code($response) === 200) {

            try {
                $response_text = $response['body'];
                $response_text_trimed = $this->trimBOM($response_text);
                $response_json = json_decode($response_text_trimed);

                if (empty($response_json->error)) {
                    return $response_json->definition;
                } else {
                    $this->error = true;
                    $this->error_message = $response_json->message;
                    return false;
                }
            }
            catch(Exception $e) {
                $this->error = true;
                $this->error_message = $e->getMessage();

                return false;
            }
        }
        else {
            $this->error = true;

            if(is_wp_error($response))
            {
                $this->error_message = $response->get_error_message();
            }
            else {
                $this->error_message = "Internal server error";
            }
        }

        return false;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    private function trimBOM($contents) {
        return WP_Jssor_Slider_Globals::trim_bom($contents);
    }

}
