<?php
/*
 * Plugin Name: Block wp-login
 * Version: 1.5.3
 * Plugin URI: https://webd.uk/support/
 * Description: This plugin completely blocks access to wp-login.php and creates a new secret login URL
 * Author: Webd Ltd
 * Author URI: https://webd.uk
 * Text Domain: block-wp-login
 */



if (!defined('ABSPATH')) {
    exit(__('This isn\'t the page you\'re looking for. Move along, move along.', 'block-wp-login'));
}



if (!class_exists('bwpl_class')) {

	class bwpl_class {

        public static $version = '1.5.3';

		private $bwpl_new_slug = '';

		function __construct() {

	        add_action('admin_init', array($this, 'bwpl_configure_slug'));
            register_deactivation_hook(__FILE__, array($this, 'bwpl_uninstall'));

            if (get_option('bwpl_slug')) {

                if (get_option('bwpl_wp_version') !== get_bloginfo('version')) {

                    add_action('admin_init', array($this, 'bwpl_new_wordpress_version'));

        		}

                add_filter('login_url', array($this, 'bwpl_change_login_url'), 10, 3);
                add_filter('logout_url', array($this, 'bwpl_change_logout_url'), 10, 2);
                add_filter('wp_redirect', array($this, 'bwpl_change_login_redirect'), 10, 2);
                add_filter('logout_redirect', array($this, 'bwpl_change_logout_redirect'), 10, 3);
                add_filter('lostpassword_url', array($this, 'bwpl_change_logout_url'), 10, 2);

        	} else {

        		add_action('admin_notices', array($this, 'bwpl_setup_admin_notice'));

        	}

            if (is_admin()) {

                add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'bwpl_add_plugin_action_links'));
                add_action('admin_notices', 'bwplCommon::admin_notices');
                add_action('wp_ajax_dismiss_bwpl_notice_handler', 'bwplCommon::ajax_notice_handler');

            }

	        add_action('wp_login', 'bwpl_class::wp_login', 10, 2);

		}

		function bwpl_add_plugin_action_links($links) {

			$settings_links = bwplCommon::plugin_action_links(admin_url('options-permalink.php'));

			return array_merge($settings_links, $links);

		}

		function bwpl_configure_slug() {

			if (isset($_POST['bwpl_nonce']) && wp_verify_nonce($_POST['bwpl_nonce'], 'bwpl_slug_change') && isset($_POST['bwpl_slug']) && current_user_can('manage_options')) {

				$this->bwpl_new_slug = trim(sanitize_key(wp_strip_all_tags($_POST['bwpl_slug'])));

				if ($this->bwpl_new_slug) {

					$this->bwpl_uninstall();
					$this->bwpl_install();

				} else {

                    if (isset($_POST['bwpl_notify']) && 'true' === $_POST['bwpl_notify']) {

            			$this->bwpl_send_emails(false);

                    }

					$this->bwpl_uninstall();

				}

				update_option('bwpl_slug', $this->bwpl_new_slug);
				add_filter('login_url', array($this, 'bwpl_change_login_url'), 10, 3);
				add_filter('logout_url', array($this, 'bwpl_change_logout_url'), 10, 2);
                add_filter('logout_redirect', array($this, 'bwpl_change_logout_redirect'), 10, 3);
				add_filter('lostpassword_url', array($this, 'bwpl_change_logout_url'), 10, 2);

                $daf_options = get_option('daf_options');

                if (isset($daf_options['enable_firewall']) && $daf_options['enable_firewall']) {

                    global $daf;

                    if (method_exists($daf, 'daf_create_htaccess') && is_callable(array($daf, 'daf_create_htaccess')) && method_exists($daf, 'daf_remove_rules') && is_callable(array($daf, 'daf_remove_rules')) && method_exists($daf, 'daf_inject_rules') && is_callable(array($daf, 'daf_inject_rules'))) {

                        $daf_htaccess = $daf->daf_create_htaccess();

                        if ($daf_htaccess) {

                            if ($daf->daf_remove_rules()) {

                                $daf->daf_inject_rules($daf_htaccess);

                            }

            			}

                    }

                }

                if (
                    isset($_POST['bwpl_unknown_admin']) &&
                    $_POST['bwpl_unknown_admin'] &&
                    isset($_POST['bwpl_known_ips']) &&
                    $_POST['bwpl_known_ips']
                ) {

                    $known_ips = preg_split('/\r\n|[\r\n]/', $_POST['bwpl_known_ips']);

                    foreach ($known_ips AS $key => $known_ip) {

                        if (!(
                            filter_var($known_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ||
                            filter_var($known_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
                        )) {

                            unset($known_ips[$key]);

                        }

                    }

                    if ($known_ips) {

                        update_option('bwpl_known_ips', implode(PHP_EOL, $known_ips));

                    } else {

                        delete_option('bwpl_known_ips');

                    }

                } else {

                    delete_option('bwpl_known_ips');

                }

			}

			add_settings_section('bwpl', _x('Block wp-login', 'UI Strings', 'block-wp-login'), array($this, 'bwpl_settings_title'), 'permalink');
			add_settings_field('bwpl_slug', __('Login address', 'block-wp-login'), array($this, 'bwpl_settings_html'), 'permalink', 'bwpl', array('label_for' => 'bwpl_slug'));
			register_setting('permalink', 'bwpl_slug', 'strval');

		}

		function bwpl_settings_title() {

?>
<p><?php esc_html_e('Use the options below to completely block access to wp-login.php and create a new secret login address.', 'block-wp-login'); ?></p>
<?php

		}

		function bwpl_settings_html() {

            wp_nonce_field('bwpl_slug_change', 'bwpl_nonce');

			$characters = 'abcdefghijklmnopqrstuvwxyz';
			$randomString = '';

			for ($i = 0; $i < 8; $i++) {

				$randomString .= $characters[rand(0, strlen($characters) - 1)];

			}

?>
<input id="bwpl_slug" name="bwpl_slug" type="text" class="regular-text code" value="<?php echo get_option('bwpl_slug'); ?>" />

<script type="text/javascript">
jQuery('#bwpl_slug').change(function() {
    jQuery(this).val(jQuery(this).val().toLowerCase().replace(/[^a-z0-9]+/g,''));
    if (jQuery(this).val().length !== 0) {
        alert(<?php echo json_encode(__('WARNING! DO NOT LOCK YOURSELF OUT! Your new login address will be', 'block-wp-login') . ' ' . get_site_url() . '/'); ?> + jQuery(this).val() + '/');
    }
    jQuery('#bwpl_notify').prop('checked', true);
});
</script>

<p><?php esc_html_e('To change your WordPress login address, enter your chosen slug above. Leave it blank to enable the default login address.', 'block-wp-login'); ?></p>

<p><?php printf(esc_html__('%1$sClick here%2$s to generate a random login address.', 'block-wp-login'),'<a href="javascript:void(0)" class="randomlogin">','</a>'); ?></p>

<script type="text/javascript">
jQuery('.randomlogin').on('click',function() {
    var result = '',
        characters = 'abcdefghijklmnopqrstuvwxyz';
    for (var i = 0; i < 8; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    jQuery('#bwpl_slug').val(result);
    alert(<?php echo json_encode(__('WARNING! DO NOT LOCK YOURSELF OUT! Your new login address will be', 'block-wp-login') . ' ' . get_site_url() . '/'); ?> + result + '/');
    jQuery('#bwpl_slug').val(result);
});
</script>

<p><strong><?php esc_html_e('Current Login URL: ', 'block-wp-login'); ?></strong><?php

			if (get_option('bwpl_slug')) {

				echo get_site_url(null, (get_option('bwpl_slug') . '/'));

			} else {

				echo get_site_url(null, 'wp-login.php');

			} ?></p>

<p><input id="bwpl_notify" name="bwpl_notify" type="checkbox" value="true"> <?php esc_html_e('Notify administrators about the new login URL.', 'block-wp-login'); ?></p>

<?php

            $known_ips = get_option('bwpl_known_ips');

            if (!$known_ips) { $known_ips = false; }

?>
<p><input id="bwpl_unknown_admin" name="bwpl_unknown_admin" type="checkbox" value="true"<?php if ($known_ips) { echo ' checked'; } ?>> <?php esc_html_e('Notify site owner if an admin signs in with an unknown IP address (advanced)', 'block-wp-login'); ?></p>
<p id="bwpl_known_ips_wrapper"<?php if (!$known_ips) { echo ' style="display: none;"'; } ?>><label for="bwpl_known_ips"><?php esc_html_e('Known IPs (one IP per line)', 'block-wp-login'); ?></label><br>
<textarea name="bwpl_known_ips" id="bwpl_known_ips" class="large-text code" rows="5"><?php echo ($known_ips ? esc_html($known_ips) : esc_html(self::get_current_ip())); ?></textarea></p>
<script type="text/javascript">
    jQuery('#bwpl_unknown_admin').on('change',function() {
        jQuery('#bwpl_known_ips_wrapper').toggle();
    });
</script>
<?php

            if (!class_exists('daf_class')) {

?>
<p><strong><?php esc_html_e('Please Note: ', 'block-wp-login'); ?></strong><?php

                printf(
                    __('To lock down your website to only serve legitimate content, please take a look at our new plugin "%s".', 'block-wp-login'),
                    '<a href="' . esc_url(add_query_arg(array('s' => 'deny-all-firewall+genuine', 'tab' => 'search', 'type' => 'term'), self_admin_url('plugin-install.php'))) . '" title="' . __('Deny All Firewall', 'block-wp-login') . '">' . __('Deny All Firewall', 'block-wp-login') . '</a>'
                );

?></p>
<?php

            }

		}

		function bwpl_send_emails($new_version) {

			$blogusers = get_users('role=Administrator');
			$admin_emails = array();

			foreach ($blogusers as $user) {
				if ($user->user_email) {
					$this->bwpl_send_email($user->user_email, $new_version);
					array_push($admin_emails, $user->user_email);
				}
			}

			if (get_bloginfo('admin_email') && !in_array(get_bloginfo('admin_email'),$admin_emails)) {
				$this->bwpl_send_email(get_bloginfo('admin_email'), $new_version);
			}

		}

		function bwpl_install($new_version = false) {

            if ($new_version || (isset($_POST['bwpl_notify']) && 'true' === $_POST['bwpl_notify'])) {

    			$this->bwpl_send_emails($new_version);

            }

			if (file_exists(bwplCommon::get_home_path() . 'wp-login.php')) {

				$content = file_get_contents(bwplCommon::get_home_path() . 'wp-login.php');
				$content_chunks = explode('wp-login.php', $content);
				$content = implode($this->bwpl_new_slug . '-wp-login.php', $content_chunks);

				if ((!file_exists(bwplCommon::get_home_path() . $this->bwpl_new_slug . '-wp-login.php') && is_writable(bwplCommon::get_home_path())) || is_writable(bwplCommon::get_home_path() . $this->bwpl_new_slug . '-wp-login.php')) {

					file_put_contents(bwplCommon::get_home_path() . $this->bwpl_new_slug . '-wp-login.php', $content);

				}

			}

			if ((!file_exists(bwplCommon::get_home_path() . '.htaccess') && is_writable(bwplCommon::get_home_path())) || is_writable(bwplCommon::get_home_path() . '.htaccess')) {

                $markerdata = file(bwplCommon::get_home_path() . '.htaccess');

                if ($markerdata) {

    				$markerdata = explode("\n", implode('', $markerdata));
    				$found = false;
    				$newdata = '';

    				foreach ($markerdata as $line) {

    					if (!$found) {

    						$newdata .= "# BEGIN BlockWPLogin\n";
    						$newdata .= "<IfModule mod_rewrite.c>\n";
    						$newdata .= "RewriteEngine On\n";

                            $newdata .= "RewriteCond %{QUERY_STRING} \"^action=postpass$\"
RewriteRule \"^" . str_replace('.', '\.', substr(site_url('wp-login.php', 'relative'), 1)) . "$\" " . str_replace('.', '\.', site_url($this->bwpl_new_slug . '-wp-login.php', 'relative')) . " [QSA,L]
";

    						$newdata .= "RewriteRule \"^" . str_replace('.', '\.', substr(site_url('wp-login.php', 'relative'), 1)) . "\" - [F]\n";
    						$newdata .= "RewriteRule \"^$this->bwpl_new_slug\\/?$\" " . str_replace('.', '\.', site_url($this->bwpl_new_slug . '-wp-login.php', 'relative')) . " [R=301,QSA,L]\n";
    						$newdata .= "</IfModule>\n";
    						$newdata .= "# END BlockWPLogin\n\n";
    						$newdata .= "$line\n";
    						$found = true;

    					} else {

    						$newdata .= "$line\n";

    					}

    				}

				    $f = @fopen(bwplCommon::get_home_path() . '.htaccess', 'w');
    				fwrite($f, $newdata);

				}

			}

			update_option('bwpl_wp_version', get_bloginfo('version'));

		}

		function bwpl_setup_admin_notice() {

?>
<div class="notice notice-success">
    <p><?php printf(esc_html__('%1$sBlock wp-login%2$s activated. ', 'block-wp-login'),'<strong>','</strong>'); ?><a href="<?php echo admin_url('options-permalink.php'); ?>"><?php esc_html_e('Configure the plugin here.', 'block-wp-login'); ?></a></p>
</div>
<?php

		}

		function bwpl_uninstall() {

			if (is_writable(bwplCommon::get_home_path() . '.htaccess')) {

                $markerdata = file(bwplCommon::get_home_path() . '.htaccess');

                if ($markerdata) {

    				$markerdata = explode("\n", implode('', $markerdata));
    				$found = false;
    				$blank_line = false;
    				$newdata = '';

    				foreach ($markerdata as $line) {

						if ($blank_line && !$line) {

							$found = true;

						}

						if ($blank_line && $line) {

							$found = false;

						}

						if ($line) {

							$blank_line = false;

						} else {

							$blank_line = true;

						}

						if ('# BEGIN BlockWPLogin' === $line) {

							$found = true;

						}

						if (!$found) {

							$newdata .= "$line\n";

						}

						if ('# END BlockWPLogin' === $line) {

							$found = false;

						}

    				}

    				$f = @fopen(bwplCommon::get_home_path() . '.htaccess', 'w');
    				fwrite($f, $newdata);

				}

			}

			add_filter('logout_url', array($this, 'bwpl_reset_logout_url'));
            add_filter('logout_redirect', array($this, 'bwpl_reset_logout_url'));
			add_filter('lostpassword_url', array($this, 'bwpl_reset_logout_url'));

			if (is_writable(bwplCommon::get_home_path() . get_option('bwpl_slug') . '-wp-login.php') && get_option('bwpl_slug')) {

				unlink(bwplCommon::get_home_path() . get_option('bwpl_slug') . '-wp-login.php');

			}

			update_option('bwpl_slug', '');

            $daf_options = get_option('daf_options');

            if (isset($daf_options['enable_firewall']) && $daf_options['enable_firewall']) {

                global $daf;

                if (method_exists($daf, 'daf_create_htaccess') && is_callable(array($daf, 'daf_create_htaccess')) && method_exists($daf, 'daf_remove_rules') && is_callable(array($daf, 'daf_remove_rules')) && method_exists($daf, 'daf_inject_rules') && is_callable(array($daf, 'daf_inject_rules'))) {

                    $daf_htaccess = $daf->daf_create_htaccess();

                    if ($daf_htaccess) {

                        if ($daf->daf_remove_rules()) {

                            $daf->daf_inject_rules($daf_htaccess);

                        }

        			}

                }

            }

		}

		function bwpl_change_login_url($login_url, $redirect, $force_reauth) {

           if (function_exists('is_user_logged_in') && is_user_logged_in()) {

			    $login_url = str_replace('/wp-login.php', '/' . get_option('bwpl_slug') . '-wp-login.php', $login_url);

            }

			return $login_url;

		}

		function bwpl_change_logout_url($logout_url, $redirect) {

			$logout_url = str_replace('/wp-login.php', '/' . get_option('bwpl_slug') . '-wp-login.php', $logout_url);

			return $logout_url;

		}

        function bwpl_change_login_redirect($location, $status) {

            if (false !== strpos($location, '/wp-login.php?redirect_to=http') && strlen($location) > 41 && '%2Fwp-admin%2F&action=confirm_admin_email' === substr($location, -41)) {

			    $location = str_replace('/wp-login.php', '/' . get_option('bwpl_slug') . '-wp-login.php', $location);

            }

			return $location;

        }

        function bwpl_change_logout_redirect($redirect_to, $requested_redirect_to, $user) {

            if ($user->ID) {

			    $redirect_to = str_replace('/wp-login.php', '/' . get_option('bwpl_slug') . '-wp-login.php', $redirect_to);

            }

			return $redirect_to;

        }

		function bwpl_reset_logout_url($logout_url) {

			$logout_url = str_replace('/' . get_option('bwpl_slug') . '-wp-login.php', '/wp-login.php', $logout_url);

			return $logout_url;

		}

		function bwpl_send_email($recipient, $new_version) {

            if ($new_version) {

			    $message = __('A new version of WordPress has been detected so we have reinstalled "Block wp-login" and here is a reminder of your login URL:', 'block-wp-login') . "\r\n\r\n";

            } else {

    			$message = __('Your WordPress login URL has been changed:', 'block-wp-login') . "\r\n\r\n";

            }

			if ($this->bwpl_new_slug) {

			    $message .= get_site_url(null, ($this->bwpl_new_slug . '/')) . "\r\n\r\n";

			} else {

			    $message .= get_site_url(null, 'wp-login.php') . "\r\n\r\n";

			}

			$message .= __('Make sure you save this email and / or bookmark this address so you don\'t get locked out!', 'block-wp-login') . "\r\n\r\n";
			$message .= __('Contact us if you are having trouble with WordPress https://webd.uk', 'block-wp-login') . "\r\n\r\n";
			$message .= __('If you like our plugin please leave a short review: https://wordpress.org/support/plugin/block-wp-login/reviews/#new-post', 'block-wp-login') . "\r\n\r\n";

			if (is_multisite()) {

				$blogname = get_network()->site_name;

			} else {

				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

			}

            if ($new_version) {

		    	$title = sprintf(__('[%s] WordPress Login Reminder', 'block-wp-login'), $blogname);

            } else {

		    	$title = sprintf(__('[%s] WordPress Login Changed', 'block-wp-login'), $blogname);

            }

			if (!wp_mail($recipient, wp_specialchars_decode($title), $message)) {

				add_action('admin_notices', array($this, 'bwpl_admin_notice_email_html'));

			}

		}

		function bwpl_admin_notice_email_html() {

?>
<div class="notice notice-error">
    <p><?php printf(esc_html__('%1$sBlock wp-login%2$s activated email could not be sent.', 'block-wp-login'),'<strong>','</strong>'); ?></p>
</div>
<?php

		}

        private static function _get_bwpl_lock() {

        	global $wpdb;

        	$value = 0;

        	if (wp_using_ext_object_cache()) {

        		$value = wp_cache_get('doing_bwpl', 'transient', true);

        	} else {

        		$row = $wpdb->get_row($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", '_transient_doing_bwpl'));

        		if ( is_object( $row ) ) {

        			$value = $row->option_value;

        		}

        	}

        	return $value;

        }

		function bwpl_new_wordpress_version() {

            $gmt_time = microtime( true );
            $doing_bwpl_transient = get_transient('doing_bwpl');

    		if ($doing_bwpl_transient && ( $doing_bwpl_transient + 300 > $gmt_time)) {

    			return;

    		}

    		$doing_bwpl = sprintf('%.22F', microtime(true));
    		$doing_bwpl_transient = $doing_bwpl;
    		set_transient('doing_bwpl', $doing_bwpl);

			$bwpl_old_slug = get_option('bwpl_slug');
			$this->bwpl_uninstall();
			$this->bwpl_new_slug = $bwpl_old_slug;
			$this->bwpl_install(true);
			update_option('bwpl_slug', $bwpl_old_slug);
            $daf_options = get_option('daf_options');

            if (isset($daf_options['enable_firewall']) && $daf_options['enable_firewall']) {

                global $daf;

                if (method_exists($daf, 'daf_create_htaccess') && is_callable(array($daf, 'daf_create_htaccess')) && method_exists($daf, 'daf_remove_rules') && is_callable(array($daf, 'daf_remove_rules')) && method_exists($daf, 'daf_inject_rules') && is_callable(array($daf, 'daf_inject_rules'))) {

                    $daf_htaccess = $daf->daf_create_htaccess();

                    if ($daf_htaccess) {

                        if ($daf->daf_remove_rules()) {

                            $daf->daf_inject_rules($daf_htaccess);

                        }

        			}

                }

            }

			if (self::_get_bwpl_lock() !== $doing_bwpl) {

				return;

			}

            if (self::_get_bwpl_lock() === $doing_bwpl ) {

            	delete_transient('doing_bwpl');

            }

		}

        public static function wp_login($user_login, $user) {

            if (user_can($user->ID, 'manage_options')) {

                $ip = self::get_current_ip();

                if ($ip) {

                    $known_ips = get_option('bwpl_known_ips');

                    if ($known_ips) {

                        $known_ips = preg_split('/\r\n|[\r\n]/', $known_ips);

                        if (!in_array($ip, $known_ips, true)) {

                			if (is_multisite()) {

                				$blogname = get_network()->site_name;

                			} else {

                				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

                			}

            		    	$title = sprintf(__('[%s] WordPress Login Alert', 'block-wp-login'), $blogname);

                            $message = __('An administrator with an un-recognised IP address has signed in:', 'block-wp-login') . "\r\n\r\n";
                            $message .= __('User: ', 'block-wp-login') . $user_login . "\r\n\r\n";
                            $message .= __('IP: ', 'block-wp-login') . $ip . "\r\n\r\n";
                			$message .= __('Contact us if you are having trouble with WordPress https://webd.uk', 'block-wp-login') . "\r\n\r\n";
                			$message .= __('If you like our plugin please leave a short review: https://wordpress.org/support/plugin/block-wp-login/reviews/#new-post', 'block-wp-login') . "\r\n\r\n";

                			wp_mail(get_bloginfo('admin_email'), wp_specialchars_decode($title), $message);

                        }

                    }

                }

            }

        }

        private static function get_current_ip() {

            $ip = false;

            if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {

                $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

            } elseif (isset($_SERVER['REMOTE_ADDR'])) {

                $ip = $_SERVER['REMOTE_ADDR'];

            }


            if (
                $ip && 
                !(
                    filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || 
                    filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
                )
            ) {

                $ip = false;

            }

            return $ip;

        }

	}

    if (!class_exists('bwplCommon')) {

        require_once(dirname(__FILE__) . '/includes/class-bwpl-common.php');

    }

	$Block_wp_login = new bwpl_class();

}

?>
