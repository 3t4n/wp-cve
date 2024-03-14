<?php
/*
 * Version: 1.3.9
 */



if (!class_exists('rpbwCommon')) {

	class rpbwCommon {

        public static $plugin_name = 'Remove "Powered by Wordpress"';
        public static $plugin_prefix = 'rpbw';
        public static $plugin_text_domain = 'remove-powered-by-wp';
        public static $plugin_premium_class = '';
        public static $plugin_trial = false;
        public static $plugin_upgrade = false;

		public static function plugin_version() {

            return remove_powered_by_wp_class::$version;

        }

        public static function enqueue_customize_controls_js() {

            wp_enqueue_script('webd-customize-controls', plugin_dir_url(__FILE__) . 'customize-controls.js', array('jquery', 'customize-controls'), self::plugin_version(), true);

        }

        public static function plugin_name() {

            return self::$plugin_name;

        }

        public static function plugin_prefix() {

            return self::$plugin_prefix;

        }

        public static function plugin_text_domain() {

            return self::$plugin_text_domain;

        }

        public static function plugin_trial() {

            return self::$plugin_trial;

        }

        public static function plugin_upgrade() {

            return self::$plugin_upgrade;

        }

        public static function support_url() {

            return 'https://wordpress.org/support/plugin/' . self::$plugin_text_domain . '/';

        }

        public static function control_upgrade_text() {

            $upgrade_text = '<a href="' . esc_url(self::upgrade_link()) . '" title="' . esc_attr(sprintf(__('Upgrade now to %s Premium', self::$plugin_text_domain), self::$plugin_name)) . '">' . sprintf(__('Upgrade now to %s Premium', self::$plugin_text_domain), self::$plugin_name) . '</a>';

            if (!class_exists(self::$plugin_premium_class) || !get_option(self::$plugin_prefix . '_purchased')) {

                if (!class_exists(self::$plugin_premium_class)) {

                    $upgrade_text .= sprintf(wp_kses(__(' or <a href="%s" title="Download Free Trial">trial it for 7 days</a>', self::$plugin_text_domain), array('a' => array('href' => array(), 'title' => array()))), esc_url(self::premium_link()));

                }

            }

            return $upgrade_text;

        }

        public static function control_section_description() {

            $default_description = sprintf(wp_kses(__('If you have any requests for new features, please <a href="%s" title="Support Forum">let us know in the support forum</a>.', self::$plugin_text_domain), array('a' => array('href' => array(), 'title' => array()))), esc_url(self::support_url()));

            if (self::$plugin_premium_class) {

                $upgrade_text = self::control_upgrade_text() . '.';

                if (!class_exists(self::$plugin_premium_class) || !get_option(self::$plugin_prefix . '_purchased')) {

                    if (!class_exists(self::$plugin_premium_class)) {

                        $section_description = '<strong>' . __('For even more options', self::$plugin_text_domain) . '</strong>' . ' ' . $upgrade_text;

                    } else {

                        $section_description = '<strong>' . __('To keep using premium options', self::$plugin_text_domain) . '</strong>' . ' ' . $upgrade_text;

                    }

                } else {

                    $section_description = $default_description;

                }

            } else {

                $section_description = $default_description;

            }

            if (!class_exists('reset_customizer_class')) {

                $section_description .= ' ' . sprintf(
                    wp_kses(
                        __(
                            '<strong>To reset this section of options to default settings</strong> without affecting other sections in the customizer, install <a href="%s" title="Reset Customizer">Reset Customizer</a>.',
                            self::$plugin_text_domain
                        ),
                        array('strong' => array(), 'a' => array('href' => array(), 'title' => array()))
                    ),
                    esc_url(
                        add_query_arg(
                            array(
                                's' => 'reset+customizer+json+button+section+restore+deleted+publish+saved+injected',
                                'tab' => 'search',
                                'type' => 'term'
                            ),
                            self_admin_url('plugin-install.php')
                        )
                    )
                );

            }

            return $section_description;

        }

        public static function control_setting_upgrade_nag() {

            $upgrade_nag = self::control_upgrade_text() . __(' to use this option.', self::$plugin_text_domain);

            return $upgrade_nag;

        }

		public static function get_home_path() {

            if (!function_exists('get_home_path')) {

                require_once(ABSPATH . 'wp-admin/includes/file.php');

            }

            return get_home_path();

		}

        public static function add_hidden_control($wp_customize, $id, $section, $label = '', $description = '', $priority = 11) {

            $wp_customize->add_control($id, array(
                'label'         => $label,
                'description'   => $description,
                'section'       => $section,
                'settings'      => array(),
                'type'          => 'hidden',
				'priority'      => $priority
            ));

        }

		public static function sanitize_boolean($input = false) {

            if ($input) {

                return true;

            }

            return false;

        }

		public static function sanitize_options($input, $setting) {

            $choices = $setting->manager->get_control($setting->id)->choices;

            return (array_key_exists($input, $choices) ? $input : $setting->default);

        }

		public static function sanitize_multiple_options($input, $setting) {

            $valid_input = true;

            if ($input) {

                if (!is_array($input)) { $input = explode(',', $input); }

                $choices = $setting->manager->get_control($setting->id)->choices;

                foreach($input as $value) {

                    if (!array_key_exists($value, $choices)) {

                        $valid_input = false;

                    }

                }

            } else {

                $input = array();

            }

            return ($valid_input ? $input : $setting->default);

        }

		public static function generate_css($selector, $style, $mod_name, $prefix = '', $postfix = '', $value = '') {

            $generated_css = '';
            $mod = get_theme_mod($mod_name);

            if ($mod && $value === '') {

                $generated_css = sprintf('%s { %s: %s; }', $selector, $style, $prefix.$mod.$postfix);
                echo $generated_css;

            } elseif ($mod) {

                $generated_css = sprintf('%s { %s:%s; }', $selector, $style, $prefix.$value.$postfix);
                echo $generated_css;

            }

        }

        public static function upgrade_link() {

            if (self::$plugin_premium_class) {

                return add_query_arg('url', (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'], 'https://webd.uk/product/' . self::$plugin_text_domain . '-upgrade/');


            } else {

                return add_query_arg('plugin', urlencode(self::$plugin_name), 'https://webd.uk/product/support-us/');

            }

        }

        public static function premium_link() {

            return 'https://webd.uk/downloads/';

        }

        public static function plugin_action_links($settings_link, $premium = false) {

            if (!$premium) {

                add_filter('plugin_row_meta', __CLASS__ . '::plugin_row_meta', 10, 4);

            }

            $settings_links = array();

			$settings_links[] = '<a href="' . esc_url($settings_link) . '" title="' . esc_attr(__('Settings', self::$plugin_text_domain)) . '">' . __('Settings', self::$plugin_text_domain) . '</a>';

            if (!get_option(self::$plugin_prefix . '_purchased')) {

                if ($premium) {

                    if (self::$plugin_upgrade) {

                        $settings_links[] = '<a href="' . esc_url(self::upgrade_link()) . '" title="' . esc_attr(sprintf(__('Buy %s Premium', self::$plugin_text_domain), self::$plugin_name)) . '" style="color: orange; font-weight: bold;">' . __('Buy Now', self::$plugin_text_domain) . '</a>';

                    } else {

                        $settings_links[] = '<a href="' . esc_url(self::upgrade_link()) . '" title="' . esc_attr(sprintf(__('Buy %s', self::$plugin_text_domain), self::$plugin_name)) . '" style="color: orange; font-weight: bold;">' . __('Buy Now', self::$plugin_text_domain) . '</a>';

                    }

                } else {

                    $settings_links[] = '<a href="' . esc_url(self::upgrade_link()) . '" title="' . esc_attr((self::$plugin_premium_class ? sprintf(__('Upgrade now to %s Premium', self::$plugin_text_domain), self::$plugin_name) : sprintf(__('Contribute to %s', self::$plugin_text_domain), self::$plugin_name))) . '" style="color: orange; font-weight: bold;">' . (self::$plugin_premium_class ? __('Upgrade', self::$plugin_text_domain) : __('Support Us', self::$plugin_text_domain)) . '</a>';

                }

                if ($premium) {

                    $settings_links[] = '<a href="' . wp_nonce_url('?activate-' . self::$plugin_prefix . '=true', self::$plugin_prefix . '_activate') . '" id="' . self::$plugin_prefix . '_activate_upgrade" title="' . esc_attr(__('Activate Purchase', self::$plugin_text_domain)) . '" onclick="jQuery(this).append(&#39; <img src=&#34;/wp-admin/images/loading.gif&#34; style=&#34;float: none; width: auto; height: auto;&#34; />&#39;); setTimeout(function(){document.getElementById(\'' . self::$plugin_prefix . '_activate_upgrade\').removeAttribute(\'href\');},1); return true;">' . __('Activate Purchase', self::$plugin_text_domain) . '</a>';

                } elseif (self::$plugin_trial && !is_plugin_active(self::$plugin_text_domain . '-premium/' . self::$plugin_text_domain . '-premium.php')) {

                    $settings_links[] = '<a href="' . esc_url(self::premium_link()) . '" title="' . esc_attr(sprintf(__('Trial %s Premium', self::$plugin_text_domain), self::$plugin_name)) . ' for 7 days">' . __('Download Trial', self::$plugin_text_domain) . '</a>';

                }

            } elseif ($premium) {

                $settings_links[] = '<strong style="color: green; display: inline;">' . __('Purchase Confirmed', self::$plugin_text_domain) . '</strong>';

            }

			return $settings_links;

		}

        public static function plugin_row_meta($plugin_meta, $plugin_file, $plugin_data, $status) {

            if ($plugin_file === self::$plugin_text_domain . '/' . self::$plugin_text_domain . '.php') {

                $plugin_meta[] = '<a href="' . esc_url(self::support_url()) . '" title="' . __('Problems? We are here to help!', self::$plugin_text_domain) . '" style="color: orange; font-weight: bold;">' . __('Need help?', self::$plugin_text_domain) . '</a>';
                $plugin_meta[] = '<a href="https://wordpress.org/support/plugin/' . self::$plugin_text_domain . '/reviews/#new-post" title="' . esc_attr(sprintf(__('If you like %s, please leave a review!', self::$plugin_text_domain), self::$plugin_name)) . '">' . __('Review plugin', self::$plugin_text_domain) . '</a>';

            }

            return $plugin_meta;

        }

        public static function ajax_notice_handler() {

            check_ajax_referer(self::$plugin_prefix . '-ajax-nonce');

            if (is_user_logged_in()) {

                update_user_meta(get_current_user_id(), self::$plugin_prefix . '-notice-dismissed', self::plugin_version());

                if (isset($_REQUEST['donated']) && $_REQUEST['donated'] == 'true') {

    				update_option(self::$plugin_prefix . '_donated', true);

                }

            }

        }

        public static function admin_notices() {

            if (self::$plugin_premium_class) {

                if (get_option(self::$plugin_prefix . '_purchased') && !class_exists(self::$plugin_premium_class) && get_user_meta(get_current_user_id(), self::$plugin_prefix . '-notice-dismissed', true) != self::plugin_version()) {

?>

<div class="notice notice-error is-dismissible <?php echo self::$plugin_prefix; ?>-notice">

<p><strong><?php echo self::$plugin_name; ?></strong><br />
<?php esc_html_e('In order to use the premium features, you need to install the premium version of the plugin ...', self::$plugin_text_domain); ?></p>

<p><a href="<?php echo esc_url(self::premium_link()); ?>" title="<?php echo esc_attr(sprintf(__('Download %s Premium', self::$plugin_text_domain), self::$plugin_name)); ?>" class="button-primary"><?php printf(__('Download %s Premium', self::$plugin_text_domain), self::$plugin_name); ?></a></p>

</div>

<script type="text/javascript">
    jQuery(document).on('click', '.<?php echo self::$plugin_prefix; ?>-notice .notice-dismiss', function() {
	    jQuery.ajax({
    	    url: ajaxurl,
    	    data: {
        		action: 'dismiss_<?php echo self::$plugin_prefix; ?>_notice_handler',
        		_ajax_nonce: '<?php echo wp_create_nonce(self::$plugin_prefix . '-ajax-nonce'); ?>'
    	    }
    	});
    });
</script>

<?php

                } elseif (!class_exists(self::$plugin_premium_class) && time() > (strtotime('+1 hour', filectime(__DIR__))) && get_user_meta(get_current_user_id(), self::$plugin_prefix . '-notice-dismissed', true) != self::plugin_version()) {

?>

<div class="notice notice-info is-dismissible <?php echo self::$plugin_prefix; ?>-notice">

<p><strong><?php printf(__('Thank you for using %s plugin', self::$plugin_text_domain), self::$plugin_name); ?></strong><br />
<?php

                    if (self::$plugin_trial == true) {

                        _e('Would you like to try even more features? Download your 7 day free trial now!', self::$plugin_text_domain); 

                    } else {

                        echo sprintf(__('Upgrade now to %s Premium to enable more options and features and contribute to the further development of this plugin.', self::$plugin_text_domain), self::$plugin_name);

                    }

?></p>

<p><?php

                    if (self::$plugin_trial == true) {

?>

<a href="<?php echo esc_url(self::premium_link()); ?>" title="<?php echo esc_attr(sprintf(__('Try %s Premium', self::$plugin_text_domain), self::$plugin_name)); ?>" class="button-primary"><?php printf(__('Trial %s Premium for 7 days', self::$plugin_text_domain), self::$plugin_name); ?></a>

<?php

                    }

?>
<a href="<?php echo esc_url(self::upgrade_link()); ?>" title="<?php echo esc_attr(sprintf(__('Upgrade now to %s Premium', self::$plugin_text_domain), self::$plugin_name)); ?>" class="button-primary"><?php printf(__('Upgrade now to %s Premium', self::$plugin_text_domain), self::$plugin_name); ?></a></p>

</div>

<script type="text/javascript">
    jQuery(document).on('click', '.<?php echo self::$plugin_prefix; ?>-notice .notice-dismiss', function() {
	    jQuery.ajax({
    	    url: ajaxurl,
    	    data: {
        		action: 'dismiss_<?php echo self::$plugin_prefix; ?>_notice_handler',
        		_ajax_nonce: '<?php echo wp_create_nonce(self::$plugin_prefix . '-ajax-nonce'); ?>'
    	    }
    	});
    });
</script>

<?php

                }

            } elseif (time() > (strtotime('+1 hour', filectime(__DIR__))) && get_user_meta(get_current_user_id(), self::$plugin_prefix . '-notice-dismissed', true) != self::plugin_version() && !get_option(self::$plugin_prefix . '_donated')) {

?>

<div class="notice notice-info is-dismissible <?php echo self::$plugin_prefix; ?>-notice">
<p><strong><?php printf(__('Thank you for using %s plugin', self::$plugin_text_domain), self::$plugin_name); ?></strong></p>
<?php

                do_action(self::$plugin_prefix . '_admin_notice_donate');

?>
<p><?php esc_html_e('Funding plugins like this one with small financial contributions is essential to pay the developers to continue to do what they do. Please take a moment to give a small amount ...', self::$plugin_text_domain); ?></p>
<p><a href="<?php echo esc_url(self::upgrade_link()); ?>" title="<?php echo esc_attr(sprintf(__('Contribute to %s', self::$plugin_text_domain), self::$plugin_name)); ?>" class="button-primary"><?php printf(__('Contribute to %s', self::$plugin_text_domain), self::$plugin_name); ?></a> <a href="#" id="<?php echo self::$plugin_prefix; ?>-already-paid" title="<?php echo esc_attr(__('Aleady Contributed!', self::$plugin_text_domain)); ?>" class="button-primary"><?php esc_html_e('Aleady Contributed!', self::$plugin_text_domain); ?></a></p>
</div>

<script type="text/javascript">
    jQuery(document).on('click', '#<?php echo self::$plugin_prefix; ?>-already-paid', function() {
        if (confirm(<?php echo json_encode(__('Have you really? Press "Cancel" if you forgot to 🙂', self::$plugin_text_domain)); ?>)) {
            alert(<?php echo json_encode(__('Thank you!', self::$plugin_text_domain)); ?>);
            jQuery('.<?php echo self::$plugin_prefix; ?>-notice').fadeTo(100, 0, function() {
                jQuery('.<?php echo self::$plugin_prefix; ?>-notice').slideUp(100, function() {
                    jQuery('.<?php echo self::$plugin_prefix; ?>-notice').remove()
                });
            });
            jQuery.ajax({
            	url: ajaxurl,
            	data: {
                	action: 'dismiss_<?php echo self::$plugin_prefix; ?>_notice_handler',
            	    donated: 'true',
        		    _ajax_nonce: '<?php echo wp_create_nonce(self::$plugin_prefix . '-ajax-nonce'); ?>'
            	}
        	});
        } else {
            window.location.assign('<?php echo self::upgrade_link(); ?>');
        }
    });
    jQuery(document).on('click', '.<?php echo self::$plugin_prefix; ?>-notice .notice-dismiss', function() {
    	jQuery.ajax({
    	    url: ajaxurl,
    	    data: {
        		action: 'dismiss_<?php echo self::$plugin_prefix; ?>_notice_handler',
        		_ajax_nonce: '<?php echo wp_create_nonce(self::$plugin_prefix . '-ajax-nonce'); ?>'
    	    }
	    });
    });
</script>

<?php

            }

        }

        public static function is_theme_being_used($themes) {

            if (is_string($themes)) {

                $themes = array($themes);

            }

            if (!is_array($themes)) {

                return false;

            }

            global $pagenow;

            // Make sure a different theme is not being live previewed ...
            if (
                in_array(get_template(), $themes, true) && 
                !(
                    is_admin() && 
                    $pagenow === 'customize.php' &&
                    isset($_GET['theme']) && 
                    !in_array($_GET['theme'], $themes, true)
                ) && !(
                    !is_admin() && 
                    $pagenow === 'index.php' &&
                    isset($_GET['customize_theme']) && 
                    isset($_GET['customize_changeset_uuid']) && 
                    !in_array($_GET['customize_theme'], $themes, true)
                )
            ) {

                return true;

            }

            // Is an allowed theme being live previewed ... ?
            if (
                !in_array(get_template(), $themes, true) && 
                ((
                    is_admin() && 
                    $pagenow === 'customize.php' &&
                    isset($_GET['theme']) && 
                    in_array($_GET['theme'], $themes, true)
                ) || (
                    !is_admin() && 
                    $pagenow === 'index.php' &&
                    isset($_GET['customize_theme']) && 
                    isset($_GET['customize_changeset_uuid']) && 
                    in_array($_GET['customize_theme'], $themes, true)
                ))
            ) {

                return true;

            }

            // Is this a child of an allowed theme ... ?
            if (
                    !is_admin() && 
                    $pagenow === 'index.php' &&
                    isset($_GET['customize_theme']) && 
                    isset($_GET['customize_changeset_uuid'])
                
            ) {

                $child = wp_get_theme($_GET['customize_theme']);

                if (isset($child->template) && in_array($child->template, $themes, true)) {

                    return true;

                }

            }

            // Is a child of an allowed theme being live previewed ... ?
            if (
                is_admin() && 
                ($pagenow === 'customize.php' || $pagenow === 'admin-ajax.php') &&
                isset($_GET['theme']) || (isset($_POST['customize_theme']) && isset($_POST['customize_changeset_uuid']))
            ) {

                if (isset($_GET['theme'])) {

                    $child = wp_get_theme($_GET['theme']);

                } else {

                    $child = wp_get_theme($_POST['customize_theme']);

                }

                if (isset($child->template) && in_array($child->template, $themes, true)) {

                    return true;

                }

            }

            return false;

        }

	}

}

if (!function_exists('webd_customize_register')) {

	function webd_customize_register($wp_customize) {

		if (!class_exists('webd_Customize_Control_Checkbox_Multiple')) {

		    class webd_Customize_Control_Checkbox_Multiple extends WP_Customize_Control {

		        public $type = 'webd-checkbox-multiple';

		        public function render_content() {

		            if ($this->choices) {

		                if ($this->label) {

?>
<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
<?php

		                }

		                if ($this->description) {

?>
<span class="description customize-control-description"><?php echo $this->description; ?></span>
<?php

		                }

		                $multi_values = !is_array($this->value()) ? explode(',', $this->value()) : $this->value();

?>
<ul>
<?php

		                foreach ($this->choices as $value => $label) {

?>
    <li>
        <label>
            <input type="checkbox" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $multi_values)); ?> /><?php echo esc_html($label); ?>
        </label>
    </li>
<?php

		                }

?>
        </ul>
        <input type="hidden" id="_customize-input-<?php echo $this->id; ?>" <?php $this->link(); ?> value="<?php echo esc_attr(implode(',', $multi_values)); ?>" />
<?php

		            }

		        }

		    }

		}

	}

}
	
?>
