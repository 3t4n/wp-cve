<?php
if (!function_exists('is_login_page_pro')) {
    function is_login_page_pro()
    {
        return in_array(
            $GLOBALS['pagenow'],
            array('wp-login.php', 'wp-register.php'),
            true
        );
    }
}

if (!class_exists('WPLFLA_page_login_pro')) {
    class WPLFLA_page_login_pro
    {
        public $username = '';
        public $block_username = '';
        public $block_password = '';
        public $is_delete_block = false;
        public $block_id = null;
        public function __construct()
        {
            add_action('wp_login_failed', array($this, 'registration_failed'), 1);
            if (isset($_GET['hash_code']) && $_GET['hash_code'] != '') {
                add_action('login_init', array($this, 'remove_COOKIE_login_failed'), 30);
            }
            add_action('login_init', array($this, 'login_init'), 1);
            //add_action( 'authenticate', array($this,'add_login_field_validate'), 99  );
            add_action('init', array($this, 'add_login_field_validate'), 100);
            add_action('init', array($this, 'if_block_send_mail'), 100);
        }
        public function delete_block_ip_req($ip)
        {
            global $wpdb;
            $table  = $wpdb->prefix . 'WPLFLA_log_block_ip';
            $get_ip = $wpdb->get_row("select `ip` FROM $table WHERE md5(id) = '" . $ip . "'");
            if (isset($get_ip->ip)) {
                $table_failed  = $wpdb->prefix . 'WPLFLA_login_failed';
                $wpdb->query("UPDATE $table_failed SET `status` = 1 WHERE `ip` = '" . $get_ip->ip . "'");
            }
            $delete = $wpdb->query("DELETE FROM $table WHERE md5(id) = '" . $ip . "'");
            if ($delete) {
                return true;
            } else {
                return false;
            }
        }

        public function remove_COOKIE_login_failed()
        {
            if (isset($_GET['hash_code']) && $_GET['hash_code'] != '') {
                $hash_code = sanitize_text_field($_GET['hash_code']);
                if ($this->delete_block_ip_req($hash_code)) {
                    $this->is_delete_block = true;
                }
            }
        }
        public function get_remote_inf()
        {
            $data = array();
            $data['country'] = '';
            $data['country_code'] = '';
            $data['latitude'] = '';
            $data['longitude'] = '';
            $user_id = $this->get_the_user_ip();
            $response = wp_remote_get('http://www.geoplugin.net/json.gp?ip=' . $user_id);
            // Check for error
            if (is_wp_error($response)) {
                return;
            }
            if (!empty($response['body'])) {
                $json = json_decode($response['body']);
                if (!empty($json->geoplugin_countryCode)) {

                    $data['country_code'] = $json->geoplugin_countryCode;
                    $data['country'] = $json->geoplugin_countryName;
                    $data['city'] = $json->geoplugin_city;
                    $data['latitude'] = $json->geoplugin_latitude;
                    $data['longitude'] = $json->geoplugin_longitude;
                } else {

                    $data['country_code'] = '';
                    $data['country'] = '';
                    $data['city'] = '';
                    $data['latitude'] = '';
                    $data['longitude'] = '';
                }
            }



            if ($data['country_code'] == '') {

                $response = wp_remote_get('http://geoip-db.com/json/' . $user_id);
                // Check for error
                if (is_wp_error($response)) {
                    return;
                }
                if (!empty($response['body'])) {
                    $json = json_decode($response['body']);
                    if (!empty($json->country_code)) {
                        $data['country_code'] = $json->country_code;
                        $data['country'] = $json->country_name;
                        $data['city'] = $json->city;
                        $data['latitude'] = $json->latitude;
                        $data['longitude'] = $json->longitude;
                    } else {
                        $data['country_code'] = '';
                        $data['country'] = '';
                        $data['city'] = '';
                        $data['latitude'] = '';
                        $data['longitude'] = '';
                    }
                }
            }

            return $data;
        }
        public function detectDevice()
        {
            $deviceName = "";
            $userAgent = $_SERVER["HTTP_USER_AGENT"];
            $devicesTypes = array(
                "computer" => array("msie 10", "msie 9", "msie 8", "windows.*firefox", "windows.*chrome", "x11.*chrome", "x11.*firefox", "macintosh.*chrome", "macintosh.*firefox", "opera"),
                "tablet"   => array("tablet", "android", "ipad", "tablet.*firefox"),
                "mobile"   => array("mobile ", "android.*mobile", "iphone", "ipod", "opera mobi", "opera mini"),
                "bot"      => array("googlebot", "mediapartners-google", "adsbot-google", "duckduckbot", "msnbot", "bingbot", "ask", "facebook", "yahoo", "addthis")
            );
            foreach ($devicesTypes as $deviceType => $devices) {
                foreach ($devices as $device) {
                    if (preg_match("/" . $device . "/i", $userAgent)) {
                        $deviceName = $deviceType;
                    }
                }
            }
            return ucfirst($deviceName);
        }
        public function send_mail($hash, $username = 'N/A', $password = 'N/A')
        {



            $options = get_option('WPLFLA_options', array());
            if (!isset($options['WPLFLA_send_mail_status'])) {
                return true;
            }
            $to = $options['WPLFLA_email'] ? $options['WPLFLA_email'] : get_option('admin_email');


            $geo_location_info = $this->get_remote_inf();
            $the_country = $geo_location_info['country'];
            $the_country_code = $geo_location_info['country_code'];
            $the_city = $geo_location_info['city'];



            $email = $to;
            $subject = __('IMPORTANT - Security alert for your website.', 'codepressFailed_pro') . ' ' . get_site_url();
            $message = '<br><body style="background-color:#F8F9FA; padding:30px; font-size:15px"><center><h1 style="color:white; background-color:#C42032; padding:10px; width:100%">';
            $message .= __('Security Alert', 'codepressFailed_pro') . '</h1></center><br><br>';
            $message .= __('Dear Admin,', 'codepressFailed_pro') . '<br><br>';
            $message .=  __('Someone tried to access to your site dashboard using the following info:', 'codepressFailed_pro') . '<br><br>';
            $message .= '- ' . __('<b>Date/Time:</b> ', 'codepressFailed_pro') . date("Y-m-d H:i:s", time()) . '<br>';
            $message .= '- ' . __('<b>Country:</b> ', 'codepressFailed_pro') . '<img width="18px"  src="' . esc_url(WPLFLA_PLUGIN_URL . '/assets/images/flags/' . strtolower($the_country_code) . '.png') . '" >&nbsp;' . $the_country . '<br>';
            $message .= '- ' . __('<b>IP Address:</b> ', 'codepressFailed_pro') . '<a href="https://db-ip.com/' . $this->get_the_user_ip() . '" target="_blank">' . $this->get_the_user_ip() . '</a><br>';
            $message .= '- ' . __('<b>Device Name:</b> ', 'codepressFailed_pro') . $this->detectDevice() . '<br><br>';
            $message .= '- ' . __('<b>Username:</b> ', 'codepressFailed_pro') . $username . '<br>';
            $message .= '- ' . __('<b style="color:red">Password:</b> ', 'codepressFailed_pro') . '<a target="_blank" href="https://www.wp-buy.com/product/wp-limit-failed-login-attempts-pro/">Upgrade to PRO</a><br><br>';
            $message .= __('If this was you, ', 'codepressFailed_pro') . '<a href="' . esc_url(wp_login_url()) . '?hash_code=' . $hash . '"><b>' . __('Please click here to unlock', 'codepressFailed_pro') . '</b></a><br><br>';
            $message .= '<a style="background-color: #C42032;   color: white; padding: .5em;  text-decoration: none;" href="' . esc_url(get_admin_url() . 'admin.php?page=blockip&ip=' . $this->get_the_user_ip()) . '" target="_blank">' . __('Check Activity Log', 'codepressFailed_pro') . '</a><br><br>';
            $message .= '<p>' . __('This email was sent from your website by the', 'codepressFailed_pro') . ' (<a href="https://wordpress.org/plugins/wp-limit-failed-login-attempts/#description" target="_blank">' . __('WP limit failed login attempts', 'codepressFailed_pro') . '</a>)' . __('  plugin', 'codepressFailed_pro') . '</p>';
            $message .= __('</body>', 'codepressFailed_pro');

            $header = 'From: ' . get_option('blogname') . ' <' . $to . '>' . PHP_EOL;
            $header .= 'Reply-To: ' . $email . PHP_EOL;
            $header .= 'Content-Type: text/html; charset=UTF-8';

            wp_mail($to, $subject, $message, $header);
        }

        public function add_login_field_validate($user)
        {
            if ($GLOBALS['pagenow'] != 'wp-login.php') return;

            $login_failed_option = get_option('WPLFLA_options', array());
            if (!isset($login_failed_option["WPLFLA_min"]) || !isset($login_failed_option["WPLFLA_status"]) || $login_failed_option["WPLFLA_status"] != 1) {
                return $user;
            }
            global $wpdb;
            $ip = $this->get_the_user_ip();

            $login_failed_option = get_option('WPLFLA_options', array());
            $option_date = $login_failed_option['WPLFLA_min'] ? $login_failed_option['WPLFLA_min'] : 3;

            $newtimestamp = strtotime(date_i18n('Y-m-d H:i:s') . ' - ' . (int)$option_date . ' minute');
            $date_chick =  date('Y-m-d H:i:s', $newtimestamp);



            $log_block_count = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "WPLFLA_login_failed WHERE ip ='" . $ip . "' and status = 0 and  TIMESTAMP(`date`) >=  '" . $date_chick . "'  ORDER BY id DESC");

            if ((isset($log_block_count) && isset($login_failed_option["WPLFLA_allowed"]) && $login_failed_option["WPLFLA_allowed"] <= $log_block_count)) {
                $this->block_ip();
            }
            if ($this->get_user_if_block_ip()["status"] == true) {


                //$user = new WP_Error( 'WPLFLA_login_failed', '<strong>'.__('ERROR','codepressFailed_pro').'</strong>'.__(': too many failed login attempts. please try again after ','codepressFailed_pro').absint($min_to_unlock).__(' minutes','codepressFailed_pro') );
                add_action('login_footer', array($this, 'login_footer'), 100);
                add_action('login_message', array($this, 'add_login_field_too_many'), 100);
            }

            return $user;
        }
        public function add_login_field_too_many()
        {

            $date_time_wp = date_i18n('Y-m-d H:i:s');
            $min_exp = isset($login_failed_option["WPLFLA_min"]) ? $login_failed_option["WPLFLA_min"] : 30;
            if ($this->get_user_if_block_ip()["date"]) {
                $unlock_date_ip = date('Y-m-d H:i:s', strtotime($this->get_user_if_block_ip()["date"]));
                $to_time = strtotime($date_time_wp);
                $from_time = strtotime($unlock_date_ip);

                $min_to_unlock = round(abs($to_time - $from_time) / 60, 2);
            } else {
                $min_to_unlock = $min_exp;
            }

?>
            <div id="login_error">
                <strong><?php _e('ERROR', 'codepressFailed_pro'); ?></strong><?php _e(': too many failed login attempts. please try again after ', 'codepressFailed_pro');
                                                                            if (absint($min_to_unlock) == 0) {
                                                                                _e(' after few seconds', 'codepressFailed_pro');
                                                                            } else {
                                                                                echo absint($min_to_unlock);
                                                                                _e(' minutes', 'codepressFailed_pro');
                                                                            } ?>
            </div>
            <?php

        }
        function get_user_if_block_ip()
        {
            $data = array();
            $data["status"] = false;
            $data["date"] = array();

            $login_failed_option = get_option('WPLFLA_options', array());
            $option_date = $login_failed_option['WPLFLA_min'] ? $login_failed_option['WPLFLA_min'] : 3;
            global $wpdb;
            $ip = $this->get_the_user_ip();
            $newtimestamp = strtotime(date_i18n('Y-m-d H:i:s') . ' - ' . (int)$option_date . ' minute');
            $date_chick =  date('Y-m-d H:i:s', $newtimestamp);

            $log_block_count = $wpdb->get_row("SELECT date ,count(*) as count FROM " . $wpdb->prefix . "WPLFLA_log_block_ip WHERE ip ='" . $ip . "'   and TIMESTAMP(`date`) >=  '" . $date_chick . "'  ORDER BY id DESC; ");

            if ($log_block_count->count > 0) {
                $unlock_date_ip = date('Y-m-d H:i:s', strtotime('+' . (int)$option_date . ' minute', strtotime($log_block_count->date)));
                $data["status"] = true;
                $data["date"] = $unlock_date_ip;
                return $data;
            }


            return $data;
        }

        public function add_login_field()
        {

            global $wpdb;
            $ip = $this->get_the_user_ip();

            $login_failed_option = get_option('WPLFLA_options', array());
            $option_date = $login_failed_option['WPLFLA_min'] ? $login_failed_option['WPLFLA_min'] : 3;

            $newtimestamp = strtotime(date_i18n('Y-m-d H:i:s') . ' - ' . (int)$option_date . ' minute');
            $date_chick =  date('Y-m-d H:i:s', $newtimestamp);

            $log_block_count = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "WPLFLA_login_failed WHERE ip ='" . $ip . "' and status = '0' and  TIMESTAMP(`date`) >=  '" . $date_chick . "' ORDER BY id DESC");

            if ($log_block_count) {
            ?>
                <div id="login_error">
                    <strong><?php _e('Remaining attempts:') ?></strong> <?php esc_html_e($log_block_count); ?> <?php _e('from ') ?> <?php esc_html_e($login_failed_option["WPLFLA_allowed"]); ?> <br>
                </div>
            <?php
            }
        }


        public function ip_in_range($lower_range_ip_address, $upper_range_ip_address, $needle_ip_address)
        {
            # Get the numeric reprisentation of the IP Address with IP2long
            $min    = ip2long($lower_range_ip_address);
            $max    = ip2long($upper_range_ip_address);
            $needle = ip2long($needle_ip_address);

            # Then it's as simple as checking whether the needle falls between the lower and upper ranges
            return (($needle >= $min) and ($needle <= $max));
        }
        public function check_ip($type)
        {
            $ip = $this->get_the_user_ip();
            global $wpdb;
            $empRecords = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "WPLFLA_block_ip_range  WHERE `type_intr` = $type");
            foreach ($empRecords as $row) {
                if ($this->ip_in_range($row->start_ip, $row->end_ip, $ip)) {
                    return true;
                }
            }
            return false;
        }
        public function check_country($type)
        {
            $ip = $this->get_the_user_ip();
            $WPLFLA_countries = new WPLFLA_countries_PRO();
            global $wpdb;
            $totalRecords = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "WPLFLA_block_countries where `type_intr` = $type and (`country_code` = '" . $WPLFLA_countries->ip_info("Visitor", "Country Code") . "' or `country_code` = 'ALL')");
            return $totalRecords > 0;
        }
        public function login_init()
        {
            if (isset($_GET['Block_hash_code_range_ip']) && $_GET['Block_hash_code_range_ip'] != '') {
                $hash = get_option('Block_hash_code_range_ip');
                if ($hash == $_GET['Block_hash_code_range_ip']) {
                    return true;
                }
            }
            if (isset($_GET['Block_hash_code_country']) && $_GET['Block_hash_code_country'] != '') {
                $hash = get_option('Block_hash_code_country');
                if ($hash == $_GET['Block_hash_code_country']) {
                    return true;
                }
            }
            if (isset($_GET['Block_hash_code']) && $_GET['Block_hash_code'] != '') {
                $hash = get_option('Block_hash_code');
                if ($hash == $_GET['Block_hash_code']) {
                    return true;
                }
            }
            if (!$this->check_ip(2) && !$this->check_country(2)) {
                $options = get_option('WPLFLA_options', array());
                $link = isset($options['WPLFLA_url']) ? $options['WPLFLA_url'] : home_url();
                if ($this->check_ip(1)) {
                    wp_redirect($link);
                }
                if ($this->check_country(1)) {
                    wp_redirect($link);
                }
            }
        }
        public function registration_failed($username)
        {

            if (empty($_POST)) {
                return;
            }

            $this->username = $username;

            if ($this->get_user_if_block_ip()["status"] == true) {
                return;
            }
            $password = isset($_POST['pwd']) ? $_POST['pwd'] : '';
            $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : '';

            global $wpdb;
            $options = get_option('WPLFLA_options', array());
            if (isset($options['WPLFLA_save_password_status']) && $options['WPLFLA_save_password_status'] == 1) {
                $pass = sanitize_text_field($password);
                $pass = substr($pass, 0, -3) . '***';
            } else {
                $pass = '';
            }

            $redirect_to = esc_url_raw($redirect_to);
            $remote_inf = $this->get_remote_inf();

            $table_name = $wpdb->prefix . 'WPLFLA_login_failed';
            $insert = $wpdb->query(
                $wpdb->prepare(
                    "
                   INSERT INTO $table_name
                   ( username, ip, country,latitude,longitude,country_code,city,password,redirect_to,date )
                   VALUES ( %s, %s, %s,%s, %s, %s,%s,%s, %s, %s)
                   ",
                    array(
                        $username,
                        $this->get_the_user_ip(),
                        $remote_inf['country'],
                        $remote_inf['latitude'],
                        $remote_inf['longitude'],
                        $remote_inf['country_code'],
                        $remote_inf['city'],
                        $pass,
                        $redirect_to,
                        date_i18n('Y-m-d H:i:s')

                    )
                )
            );

            $last_insert_id = $wpdb->insert_id;

            add_action('login_message', array($this, 'add_login_field'), 100);
        }
        public function block_ip()
        {

            if (empty($_POST)) {
                return;
            }
            $username = isset($_POST['log']) ? sanitize_user($_POST['log']) : '';

            if ($this->get_user_if_block_ip()["status"] == true) {
                return;
            }
            $password = isset($_POST['pwd']) ? $_POST['pwd'] : '';
            $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : '';

            global $wpdb;



            $pass = sanitize_text_field($password);
            $pass = substr($pass, 0, -3) . '***';
            $redirect_to = esc_url_raw($redirect_to);
            $remote_inf = $this->get_remote_inf();

            $table_name = $wpdb->prefix . 'WPLFLA_log_block_ip';
            $insert = $wpdb->query(
                $wpdb->prepare(
                    "
                   INSERT INTO $table_name
                   ( username, ip, country,latitude,longitude,country_code,city,password,redirect_to,date )
                   VALUES ( %s, %s, %s,%s, %s, %s,%s,%s, %s , %s )
                   ",
                    array(
                        $username,
                        $this->get_the_user_ip(),
                        $remote_inf['country'],
                        $remote_inf['latitude'],
                        $remote_inf['longitude'],
                        $remote_inf['country_code'],
                        $remote_inf['city'],
                        $pass,
                        $redirect_to,
                        date_i18n('Y-m-d H:i:s')
                    )
                )
            );

            $last_insert_id = $wpdb->insert_id;
            add_action('init', array($this, 'add_login_field_validate'), 100);
            $this->block_username = $username;
            $this->block_password = $pass;
            $this->block_id = $last_insert_id;
        }
        public function if_block_send_mail()
        {
            if ($this->block_id == null && $this->block_username == null) return;
            $this->send_mail(md5($this->block_id), $this->block_username, $this->block_password);
        }
        public function get_the_user_ip()
        {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return apply_filters('wpb_get_ip', $ip);
        }
        function login_footer()
        {
            if ($this->is_delete_block) {
                return true;
            }
            ?>
            <script>
                jQuery(document).ready(function($) {
                    $("#user_login").attr("disabled", true);
                    $("#user_pass").attr("disabled", true);
                    $("#rememberme").attr("disabled", true);
                    $("#wp-submit").attr("disabled", true);

                    $('#loginform').submit(function(e) {
                        if (!$("#user_login").is(":disabled") || !$("#user_pass").is(":disabled")) {
                            e.preventDefault();
                            alert("Login form inputs are still disabled.");
                        }
                    });
                });
            </script>
<?php
        }
    }


    new WPLFLA_page_login_pro();
}
