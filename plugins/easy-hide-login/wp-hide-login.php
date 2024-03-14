<?php
/*
  Plugin Name: Easy Hide Login
  Description: Hide wp-login.php file and increase security of your website. No files are changed.
  Author: WebFactory Ltd
  Author URI: https://www.webfactoryltd.com/
  Text Domain: easy-hide-login
  Version: 1.1

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('EASY_HIDE_LOGIN_URL', plugin_dir_url(__FILE__));
define('EASY_HIDE_LOGIN_DIR', dirname(__FILE__));
define('EASY_HIDE_LOGIN_OPTIONS', 'easy_hide_login_options');

require_once EASY_HIDE_LOGIN_DIR . '/wf-flyout/wf-flyout.php';

class Easy_Hide_Login
{
  static $version;
  static $options;

  static function init()
  {
    self::$version = self::get_plugin_version();
    $options = self::load_options();

    if (!empty($options['slug']) && parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY) == $options['slug']) {
      wp_safe_redirect(site_url("wp-login.php?" . $options['slug'] . "&redirect=false"));
      exit();
    }

    if (is_admin()) {
      new wf_flyout(__FILE__);

      add_action('admin_menu',  array(__CLASS__, 'admin_menu'));
      add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts'));
      add_action('admin_action_easy_hide_login_install_wp301', array(__CLASS__, 'install_wp301'));
      add_filter('admin_footer_text', array(__CLASS__, 'admin_footer_text'));
      add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(__CLASS__, 'plugin_action_links'));
    } else {
      if (!empty($options['slug'])) {
        add_action('login_init', array(__CLASS__, 'login_head'), 1);
        add_action('login_form', array(__CLASS__, 'hidden_field'));
        add_filter('lostpassword_url',  array(__CLASS__, 'lostpassword'), 10, 0);
        add_filter('lostpassword_redirect', array(__CLASS__, 'lostpassword_redirect'), 100, 1);
      }
    }
  } // init

  // add settings link to plugins page
  static function plugin_action_links($links)
  {
    $settings_link = '<a href="' . admin_url('options-general.php?page=easy-hide-login') . '" title="Settings">Settings</a>';
    $pro_link = '<a href="' . admin_url('options-general.php?page=easy-hide-login#get-pro') . '" title="Get PRO"><b>Get PRO</b></a>';

    array_unshift($links, $settings_link);
    array_unshift($links, $pro_link);

    return $links;
  } // plugin_action_links

  static function login_head()
  {
    $options = self::get_options();

    if (isset($_GET['action']) && isset($_GET['key'])) return;
    if (isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'resetpass') return;
    if (isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'rp') return;

    if (isset($_POST['redirect_slug']) && sanitize_text_field($_POST['redirect_slug']) == $options['slug']) return false;

    if (strpos($_SERVER['REQUEST_URI'], 'action=logout') !== false) {
      check_admin_referer('log-out');

      wp_logout();
      wp_safe_redirect(home_url(), 302);
      die;
    }

    if ((strpos($_SERVER['REQUEST_URI'], $options['slug']) === false) &&
      (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false)
    ) {
      wp_safe_redirect(home_url('404'), 302);
      exit();
    }
  } // login_head

  static function lostpassword_redirect($lostpassword_redirect)
  {
    $options = self::get_options();
    return 'wp-login.php?checkemail=confirm&redirect=false&' . $options['slug'];
  } // lostpassword_redirect

  static function lostpassword()
  {
    $options = self::get_options();
    return site_url('wp-login.php?action=lostpassword&' . $options['slug'] . '&redirect=false');
  } // lostpassword

  static function hidden_field()
  {
    $options = self::get_options();
    echo '<input type="hidden" name="redirect_slug" value="' . esc_attr__($options['slug']) . '" />';
  } // hidden_field

  static function admin_enqueue_scripts($hook)
  {
    if ('settings_page_easy-hide-login' == $hook) {
      wp_enqueue_style('wp-jquery-ui-dialog');
      wp_enqueue_style('easy-hide-login-admin', EASY_HIDE_LOGIN_URL . 'css/easy-hide-login.css', array(), self::$version);

      wp_enqueue_script('jquery-ui-core');
      wp_enqueue_script('jquery-ui-position');
      wp_enqueue_script('jquery-effects-core');
      wp_enqueue_script('jquery-effects-blind');
      wp_enqueue_script('jquery-ui-dialog');

      $js_localize = array(
        'wp301_install_url' => add_query_arg(array('action' => 'easy_hide_login_install_wp301', '_wpnonce' => wp_create_nonce('install_wp301'), 'rnd' => rand()), admin_url('admin.php')),
        'site_url' => site_url()
      );

      wp_enqueue_script('easy-hide-login-admin', EASY_HIDE_LOGIN_URL . 'js/easy-hide-login.js', array('jquery'), self::$version, true);
      wp_localize_script('easy-hide-login-admin', 'easy_hide_login_vars', $js_localize);
    }
  } // admin_enqueue_scripts

  static function is_plugin_page()
  {
    $current_screen = get_current_screen();

    if ($current_screen->id == 'settings_page_easy-hide-login') {
      return true;
    } else {
      return false;
    }
  } // is_plugin_page

  static function admin_footer_text($text)
  {
    if (!self::is_plugin_page()) {
      return $text;
    }

    $text = '<i class="easy-hide-login-footer">Easy Hide Login v' . self::$version . ' <a href="' . self::generate_web_link('admin_footer') . '" title="Visit Easy Hide Login page for more info" target="_blank">WebFactory Ltd</a>. Please <a target="_blank" href="https://wordpress.org/support/plugin/easy-hide-login/reviews/#new-post" title="Rate the plugin">rate the plugin <span>★★★★★</span></a> to help us spread the word. Thank you 🙌 from the WebFactory team!</i>';

    return $text;
  } // admin_footer_text

  static function generate_web_link($placement = '', $page = '/', $params = array(), $anchor = '')
  {
    $base_url = 'https://www.webfactoryltd.com';

    if ('/' != $page) {
      $page = '/' . trim($page, '/') . '/';
    }
    if ($page == '//') {
      $page = '/';
    }

    $parts = array_merge(array('utm_source' => 'easy-hide-login', 'utm_content' => $placement), $params);

    if (!empty($anchor)) {
      $anchor = '#' . trim($anchor, '#');
    }

    $out = $base_url . $page . '?' . http_build_query($parts, '', '&amp;') . $anchor;

    return $out;
  } // generate_web_link

  static function load_options()
  {
    $options = get_option(EASY_HIDE_LOGIN_OPTIONS, array());
    $change = false;

    if (!isset($options['meta'])) {
      $options['meta'] = array('first_version' => self::$version, 'first_install' => current_time('timestamp', true));
      $change = true;
    }
    if (!isset($options['dismissed_notices'])) {
      $options['dismissed_notices'] = array();
      $change = true;
    }

    if (!isset($options['options'])) {
      $options['options'] = array();

      $options['options']['slug'] = get_option('wpseh_l01gnhdlwp') !== false ? get_option('wpseh_l01gnhdlwp') : '';

      $change = true;
    }

    if (isset($_POST['submit'])) {
      if (!isset($_POST['easyhidelogin_update_admin_options_nonce']) || !wp_verify_nonce($_POST['easyhidelogin_update_admin_options_nonce'], 'easyhidelogin_update_admin_options')) {
        echo '<div id="message" class="updated fade">
                    <p><strong>' . esc_html__('Sorry, your nonce did not verify.', 'easy-hide-login') . '</strong></p>
                </div>';
      } else {
        if (isset($_POST['slug'])) {
          $options['options']['slug'] = sanitize_text_field($_POST['slug']);
        }

        $change = true;

        echo '<div id="message" class="updated fade">
                    <p><strong>' . esc_html__('Options saved.', 'easy-hide-login') . '</strong></p>
                </div>';
      }
    }


    if ($change) {
      update_option(EASY_HIDE_LOGIN_OPTIONS, $options, true);
    }

    self::$options = $options;
    return $options['options'];
  } // load_options

  static function get_options()
  {
    return self::$options['options'];
  } // get_options

  static function update_options($key, $data)
  {
    if (false === in_array($key, array('meta', 'dismissed_notices', 'options'))) {
      user_error('Unknown options key.', E_USER_ERROR);
      return false;
    }

    self::$options[$key] = $data;
    $tmp = update_option(EASY_HIDE_LOGIN_OPTIONS, self::$options);

    return $tmp;
  } // update_options

  static function get_plugin_version()
  {
    $plugin_data = get_file_data(__FILE__, array('version' => 'Version'), 'plugin');

    return $plugin_data['version'];
  } // get_plugin_version

  static function admin_menu()
  {
    add_options_page(
      esc_html__('Easy Hide Login'),
      esc_html__('Easy Hide Login'),
      'manage_options',
      'easy-hide-login',
      array(__CLASS__, 'options_page')
    );
  } // admin_menu

  static function create_toggle_switch($name, $options = array(), $output = true)
  {
    $default_options = array('value' => '1', 'saved_value' => '', 'option_key' => $name);
    $options = array_merge($default_options, $options);

    $out = "\n";
    $out .= '<div class="toggle-wrapper open-upsell">';
    $out .= '<input type="checkbox" id="' . $name . '" ' . self::checked($options['value'], $options['saved_value']) . ' type="checkbox" value="' . $options['value'] . '" name="' . $options['option_key'] . '">';
    $out .= '<label for="' . $name . '" class="toggle"><span class="toggle_handler"></span></label>';
    $out .= '</div>';

    if ($output) {
      self::wp_kses_wf($out);
    } else {
      return $out;
    }
  } // create_toggle_switch

  static function checked($value, $current, $echo = false)
  {
    $out = '';

    if (!is_array($current)) {
      $current = (array) $current;
    }

    if (in_array($value, $current)) {
      $out = ' checked="checked" ';
    }

    if ($echo) {
      self::wp_kses_wf($out);
    } else {
      return $out;
    }
  } // checked

  static function options_page()
  {
    if (!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $options = self::get_options();

    echo '<div class="wrap">';
    echo '<h1>Easy Hide Login</h1>';
    echo '<div id="easy_hide_login_settings">';

    echo '<form action="" method="POST">';
    wp_nonce_field('easyhidelogin_update_admin_options', 'easyhidelogin_update_admin_options_nonce');
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<td style="width:200px"><label for="login_slug">Slug Text:</label></td>';
    echo '<td><input type="text" id="login_slug" value="' . esc_attr__($options['slug']) . '" name="slug"></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><label>Login url:</label></td>';
    echo '<td><a id="login_url" target="_blank" href="' . esc_url(site_url('?' . $options['slug'])) . '">' . esc_url(site_url('?' . $options['slug'])) . '</a></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td><label data-feature="max_login_retries" class="open-upsell" for="max_login_retries">Max Login Retries:</label><a title="This feature is available in the PRO version. Click for details." href="#" data-feature="max_login_retries" id="max_login_retries" class="open-upsell pro-label">PRO</a></td>';
    echo '<td><input type="text" value="3" name="" disabled><p>After this number of failed login attempts, the IP attempting to login will be blocked.</p></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td><label class="open-upsell" data-feature="captcha">Captcha:</label><a title="This feature is available in the PRO version. Click for details." href="#" data-feature="captcha" class="open-upsell pro-label">PRO</a></td>';
    echo '<td><select data-feature="captcha" class="open-upsell" disabled><option>disabled</option></select><p>Captcha or "are you human" verification ensures bots can\'t attack your login page and provides additional protection with minimal impact to users.</p></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td><label class="open-upsell" data-feature="country_blocking">Country Blocking:</label><a title="This feature is available in the PRO version. Click for details." href="#" data-feature="country_blocking" class="open-upsell pro-label">PRO</a></td>';
    echo '<td><input type="text" value="select countries" disabled data-feature="country-blocking" class="open-upsell"><p>The Country Blocking feature allows you to easily block whole countries from either accessing the login form or the whole website. Or if preferred, you can just allow access from certain countries instead. <a href="#" class="open-pro-dialog" data-pro-feature="ehl-country-blocking">Get PRO now</a> to use the Country Blocking feature.</p></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td><label class="open-upsell" data-feature="2fa_email" for="2fa_email">Email Based Two Factor Authentication:<a title="This feature is available in the PRO version. Click for details." href="#" data-feature="2fa_email" class="open-upsell pro-label">PRO</a></label></td>';
    echo '<td data-feature="2fa_email">';
    self::create_toggle_switch('2fa_email', array('data-feature' => '2fa_email', 'saved_value' => 0, 'option_key' => '', 'class' => 'open-upsell'));
    echo '<p>The 2FA Features allows you to add an extra level of security to your website, requiring users logging in for the first time from a device to confirm their login by clicking a link that is emailed to them. Even if someone steals the username &amp; password they still won\'t be able to login without access to the account email. <a href="#" class="open-pro-dialog" data-pro-feature="2fa_email">Get PRO now</a> to use the 2FA feature.</p></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="2">';
    submit_button();
    echo '</td>';
    echo '</tr>';

    echo '</table>';
    echo '</form>';

    echo '</div>';

    echo '<div id="easy_hide_login_sidebar">';
    echo '<div class="sidebar-box pro-ad-box">
            <p class="text-center"><a href="#" data-pro-feature="ehl-sidebar-box-logo" class="open-pro-dialog">
            <img src="' . esc_url(EASY_HIDE_LOGIN_URL . '/images/loginlockdown-logo.png') . '" alt="Login Lockdown PRO" title="Login Lockdown PRO"></a><br>PRO version is here! Grab the launch discount.<br><b>All prices are LIFETIME!</b></p>
            <ul class="plain-list">
                <li>Firewall</li>
                <li>Login Page Customization</li>
                <li>GDPR Compatible Captcha</li>
                <li>Email Based 2FA</li>
                <li>Country Blocking</li>
                <li>Temporary Access Links</li>
                <li>Cloud Blacklists</li>
                <li>Licenses &amp; Sites Manager (remote SaaS dashboard)</li>
                <li>White-label Mode</li>
                <li>Complete Codeless Plugin Rebranding</li>
                <li>Email support from plugin developers</li>
            </ul>

            <p class="text-center"><a href="#" class="open-pro-dialog button button-buy" data-pro-feature="ehl-sidebar-box">Get PRO Now</a></p>
            </div>';

    if (!defined('EPS_REDIRECT_VERSION') && !defined('WF301_PLUGIN_FILE')) {
      echo '<div class="sidebar-box pro-ad-box box-301">
                <h3 class="textcenter"><b>Problems with redirects?<br>Moving content around or changing posts\' URL?<br>Old URLs giving you problems?<br><br><u>Improve your SEO &amp; manage all redirects in one place!</u></b></h3>

                <p class="text-center"><a href="#" class="install-wp301">
                <img src="' . esc_url(EASY_HIDE_LOGIN_URL . '/images/wp-301-logo.png') . '" alt="WP 301 Redirects" title="WP 301 Redirects"></a></p>

                <p class="text-center"><a href="#" class="button button-buy install-wp301">Install and activate the <u>free</u> WP 301 Redirects plugin</a></p>

                <p><a href="https://wordpress.org/plugins/eps-301-redirects/" target="_blank">WP 301 Redirects</a> is a free WP plugin maintained by the same team as this Easy Hide Login plugin. It has <b>+250,000 users, 5-star rating</b>, and is hosted on the official WP repository.</p>
                </div>';
    }

    echo '<div class="sidebar-box" style="margin-top: 35px; margin-bottom: 35px;">
    <p>Need help? We\'re here for you! Our <a href="https://wordpress.org/support/plugin/easy-hide-login/" target="_blank">support forum</a> is the fastest way to get help with the plugin.</p>
                <p>Please <a href="https://wordpress.org/support/plugin/easy-hide-login/reviews/#new-post" target="_blank">rate the plugin ★★★★★</a> to <b>keep it up-to-date &amp; maintained</b>. It only takes a second to rate. Thank you! 👋</p>
                </div>';
    echo '</div>';

    echo '</form>';

    echo ' <div id="loginlockdown-pro-dialog" style="display: none;" title="Login Lockdown PRO is here!"><span class="ui-helper-hidden-accessible"><input type="text"/></span>

        <div class="center logo"><a href="https://wploginlockdown.com/?ref=loginlockdown-free-pricing-table" target="_blank"><img src="' . esc_url(EASY_HIDE_LOGIN_URL . '/images/loginlockdown-logo.png') . '" alt="Login Lockdown PRO" title="Login Lockdown PRO"></a><br>

        <span>Limited PRO Launch Discount - <b>all prices are LIFETIME</b>! Pay once &amp; use forever!</span>
        </div>

        <table id="loginlockdown-pro-table">
        <tr>
        <td class="center">Lifetime Personal License</td>
        <td class="center">Lifetime Team License</td>
        <td class="center">Lifetime Agency License</td>
        </tr>

        <tr class="prices">
        <td class="center"><del>$89 /year</del><br><span>$89</span> <b>/lifetime</b></td>
        <td class="center"><del>$119 /year</del><br><span>$99</span> <b>/lifetime</b></td>
        <td class="center"><del>$299 /year</del><br><span>$179</span> <b>/lifetime</b></td>
        </tr>

        <tr>
        <td><span class="dashicons dashicons-yes"></span><b>1 Site License</b>  ($89 per site)</td>
        <td><span class="dashicons dashicons-yes"></span><b>5 Sites License</b>  ($19 per site)</td>
        <td><span class="dashicons dashicons-yes"></span><b>100 Sites License</b>  ($1.8 per site)</td>
        </tr>

        <tr>
        <td><span class="dashicons dashicons-yes"></span>All Plugin Features</td>
        <td><span class="dashicons dashicons-yes"></span>All Plugin Features</td>
        <td><span class="dashicons dashicons-yes"></span>All Plugin Features</td>
        </tr>

        <tr>
        <td><span class="dashicons dashicons-yes"></span>Lifetime Updates &amp; Support</td>
        <td><span class="dashicons dashicons-yes"></span>Lifetime Updates &amp; Support</td>
        <td><span class="dashicons dashicons-yes"></span>Lifetime Updates &amp; Support</td>
        </tr>

        <tr>
        <td><span class="dashicons dashicons-yes"></span>Firewall</td>
        <td><span class="dashicons dashicons-yes"></span>Firewall</td>
        <td><span class="dashicons dashicons-yes"></span>Firewall</td>
        </tr>

        <tr>
        <td><span class="dashicons dashicons-yes"></span>Login Page Customization</td>
        <td><span class="dashicons dashicons-yes"></span>Login Page Customization</td>
        <td><span class="dashicons dashicons-yes"></span>Login Page Customization</td>
        </tr>

        <tr>
        <td><span class="dashicons dashicons-yes"></span>Temporary Access Links</td>
        <td><span class="dashicons dashicons-yes"></span>Temporary Access Links</td>
        <td><span class="dashicons dashicons-yes"></span>Temporary Access Links</td>
        </tr>

        <tr>
        <td><span class="dashicons dashicons-yes"></span>Country Blocking</td>
        <td><span class="dashicons dashicons-yes"></span>Country Blocking</td>
        <td><span class="dashicons dashicons-yes"></span>Country Blocking</td>
        </tr>

        <tr>
        <td><span class="dashicons dashicons-yes"></span>Cloud Blacklists</td>
        <td><span class="dashicons dashicons-yes"></span>Cloud Blacklists</td>
        <td><span class="dashicons dashicons-yes"></span>Cloud Blacklists</td>
        </tr>

        <tr>
        <td><span class="dashicons dashicons-yes"></span>Dashboard</td>
        <td><span class="dashicons dashicons-yes"></span>Dashboard</td>
        <td><span class="dashicons dashicons-yes"></span>Dashboard</td>
        </tr>

        <tr>
        <td><span class="dashicons dashicons-no"></span>White-label Mode</td>
        <td><span class="dashicons dashicons-yes"></span>White-label Mode</td>
        <td><span class="dashicons dashicons-yes"></span>White-label Mode</td>
        </tr>

        <tr>
        <td><span class="dashicons dashicons-no"></span>Full Plugin Rebranding</td>
        <td><span class="dashicons dashicons-no"></span>Full Plugin Rebranding</td>
        <td><span class="dashicons dashicons-yes"></span>Full Plugin Rebranding</td>
        </tr>

        <tr>
        <td><a class="button button-buy" data-href-org="https://wploginlockdown.com/buy/?product=personal-launch&ref=ehl-pricing-table" href="https://wploginlockdown.com/buy/?product=personal-launch&ref=ehl-pricing-table" target="_blank">Lifetime License<br>$89 -&gt; BUY NOW</a>
        <br>or <a class="button-buy" data-href-org="https://wploginlockdown.com/buy/?product=personal-monthly&ref=ehl-pricing-table" href="https://wploginlockdown.com/buy/?product=personal-monthly&ref=ehl-pricing-table" target="_blank">only $7.99 <small>/month</small></a></td>
        <td><a class="button button-buy" data-href-org="https://wploginlockdown.com/buy/?product=team-launch&ref=ehl-pricing-table" href="https://wploginlockdown.com/buy/?product=team-launch&ref=ehl-pricing-table" target="_blank">Lifetime License<br>$99 -&gt; BUY NOW</a></td>
        <td><a class="button button-buy" data-href-org="https://wploginlockdown.com/buy/?product=agency-launch&ref=ehl-pricing-table" href="https://wploginlockdown.com/buy/?product=agency-launch&ref=ehl-pricing-table" target="_blank">Lifetime License<br>$179 -&gt; BUY NOW</a></td>
        </tr>

        </table>

        <div class="center footer"><b>100% No-Risk Money Back Guarantee!</b> If you don\'t like the plugin over the next 7 days, we will happily refund 100% of your money. No questions asked! Payments are processed by our merchant of records - <a href="https://paddle.com/" target="_blank">Paddle</a>.</div>
      </div>';
    echo '</div>';
  } // options_page

  static function install_wp301()
  {
    check_ajax_referer('install_wp301');

    if (false === current_user_can('administrator')) {
      wp_die('Sorry, you have to be an admin to run this action.');
    }

    $plugin_slug = 'eps-301-redirects/eps-301-redirects.php';
    $plugin_zip = 'https://downloads.wordpress.org/plugin/eps-301-redirects.latest-stable.zip';

    @include_once ABSPATH . 'wp-admin/includes/plugin.php';
    @include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    @include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    @include_once ABSPATH . 'wp-admin/includes/file.php';
    @include_once ABSPATH . 'wp-admin/includes/misc.php';
    echo '<style>
		body{
			font-family: sans-serif;
			font-size: 14px;
			line-height: 1.5;
			color: #444;
		}
		</style>';

    echo '<div style="margin: 20px; color:#444;">';
    echo 'If things are not done in a minute <a target="_parent" href="' . admin_url('plugin-install.php?s=301%20redirects%20webfactory&tab=search&type=term') . '">install the plugin manually via Plugins page</a><br><br>';
    echo 'Starting ...<br><br>';

    wp_cache_flush();
    $upgrader = new Plugin_Upgrader();
    echo 'Check if WP 301 Redirects is already installed ... <br />';
    if (self::is_plugin_installed($plugin_slug)) {
      echo 'WP 301 Redirects is already installed! <br /><br />Making sure it\'s the latest version.<br />';
      $upgrader->upgrade($plugin_slug);
      $installed = true;
    } else {
      echo 'Installing WP 301 Redirects.<br />';
      $installed = $upgrader->install($plugin_zip);
    }
    wp_cache_flush();

    if (!is_wp_error($installed) && $installed) {
      echo 'Activating WP 301 Redirects.<br />';
      $activate = activate_plugin($plugin_slug);

      if (is_null($activate)) {
        echo 'WP 301 Redirects Activated.<br />';

        echo '<script>setTimeout(function() { top.location = "' . admin_url('options-general.php?page=eps_redirects') . '"; }, 1000);</script>';
        echo '<br>If you are not redirected in a few seconds - <a href="' . admin_url('options-general.php?page=eps_redirects') . '" target="_parent">click here</a>.';
      }
    } else {
      echo 'Could not install WP 301 Redirects. You\'ll have to <a target="_parent" href="' . admin_url('plugin-install.php?s=301%20redirects%20webfactory&tab=search&type=term') . '">download and install manually</a>.';
    }

    echo '</div>';
  } // install_wp301

  static function is_plugin_installed($slug)
  {
    if (!function_exists('get_plugins')) {
      require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $all_plugins = get_plugins();

    if (!empty($all_plugins[$slug])) {
      return true;
    } else {
      return false;
    }
  } // is_plugin_installed

  static function wp_kses_wf($html)
  {
    add_filter('safe_style_css', function ($styles) {
      $styles_wf = array(
        'text-align',
        'margin',
        'color',
        'float',
        'border',
        'background',
        'background-color',
        'border-bottom',
        'border-bottom-color',
        'border-bottom-style',
        'border-bottom-width',
        'border-collapse',
        'border-color',
        'border-left',
        'border-left-color',
        'border-left-style',
        'border-left-width',
        'border-right',
        'border-right-color',
        'border-right-style',
        'border-right-width',
        'border-spacing',
        'border-style',
        'border-top',
        'border-top-color',
        'border-top-style',
        'border-top-width',
        'border-width',
        'caption-side',
        'clear',
        'cursor',
        'direction',
        'font',
        'font-family',
        'font-size',
        'font-style',
        'font-variant',
        'font-weight',
        'height',
        'letter-spacing',
        'line-height',
        'margin-bottom',
        'margin-left',
        'margin-right',
        'margin-top',
        'overflow',
        'padding',
        'padding-bottom',
        'padding-left',
        'padding-right',
        'padding-top',
        'text-decoration',
        'text-indent',
        'vertical-align',
        'width',
        'display',
      );

      foreach ($styles_wf as $style_wf) {
        $styles[] = $style_wf;
      }
      return $styles;
    });

    $allowed_tags = wp_kses_allowed_html('post');
    $allowed_tags['input'] = array(
      'type' => true,
      'style' => true,
      'class' => true,
      'id' => true,
      'checked' => true,
      'disabled' => true,
      'name' => true,
      'size' => true,
      'placeholder' => true,
      'value' => true,
      'data-*' => true,
      'size' => true,
      'disabled' => true
    );

    $allowed_tags['textarea'] = array(
      'type' => true,
      'style' => true,
      'class' => true,
      'id' => true,
      'checked' => true,
      'disabled' => true,
      'name' => true,
      'size' => true,
      'placeholder' => true,
      'value' => true,
      'data-*' => true,
      'cols' => true,
      'rows' => true,
      'disabled' => true,
      'autocomplete' => true
    );

    $allowed_tags['select'] = array(
      'type' => true,
      'style' => true,
      'class' => true,
      'id' => true,
      'checked' => true,
      'disabled' => true,
      'name' => true,
      'size' => true,
      'placeholder' => true,
      'value' => true,
      'data-*' => true,
      'multiple' => true,
      'disabled' => true
    );

    $allowed_tags['option'] = array(
      'type' => true,
      'style' => true,
      'class' => true,
      'id' => true,
      'checked' => true,
      'disabled' => true,
      'name' => true,
      'size' => true,
      'placeholder' => true,
      'value' => true,
      'selected' => true,
      'data-*' => true
    );

    $allowed_tags['optgroup'] = array(
      'type' => true,
      'style' => true,
      'class' => true,
      'id' => true,
      'checked' => true,
      'disabled' => true,
      'name' => true,
      'size' => true,
      'placeholder' => true,
      'value' => true,
      'selected' => true,
      'data-*' => true,
      'label' => true
    );

    $allowed_tags['a'] = array(
      'href' => true,
      'data-*' => true,
      'class' => true,
      'style' => true,
      'id' => true,
      'target' => true,
      'data-*' => true,
      'role' => true,
      'aria-controls' => true,
      'aria-selected' => true,
      'disabled' => true
    );

    $allowed_tags['div'] = array(
      'style' => true,
      'class' => true,
      'id' => true,
      'data-*' => true,
      'role' => true,
      'aria-labelledby' => true,
      'value' => true,
      'aria-modal' => true,
      'tabindex' => true
    );

    $allowed_tags['li'] = array(
      'style' => true,
      'class' => true,
      'id' => true,
      'data-*' => true,
      'role' => true,
      'aria-labelledby' => true,
      'value' => true,
      'aria-modal' => true,
      'tabindex' => true
    );

    $allowed_tags['span'] = array(
      'style' => true,
      'class' => true,
      'id' => true,
      'data-*' => true,
      'aria-hidden' => true
    );

    $allowed_tags['style'] = array(
      'class' => true,
      'id' => true,
      'type' => true,
      'style' => true
    );

    $allowed_tags['fieldset'] = array(
      'class' => true,
      'id' => true,
      'type' => true,
      'style' => true
    );

    $allowed_tags['link'] = array(
      'class' => true,
      'id' => true,
      'type' => true,
      'rel' => true,
      'href' => true,
      'media' => true,
      'style' => true
    );

    $allowed_tags['form'] = array(
      'style' => true,
      'class' => true,
      'id' => true,
      'method' => true,
      'action' => true,
      'data-*' => true,
      'style' => true
    );

    $allowed_tags['script'] = array(
      'class' => true,
      'id' => true,
      'type' => true,
      'src' => true,
      'style' => true
    );

    $allowed_tags['table'] = array(
      'class' => true,
      'id' => true,
      'type' => true,
      'cellpadding' => true,
      'cellspacing' => true,
      'border' => true,
      'style' => true
    );

    $allowed_tags['canvas'] = array(
      'class' => true,
      'id' => true,
      'style' => true
    );

    echo wp_kses($html, $allowed_tags);

    add_filter('safe_style_css', function ($styles) {
      $styles_wf = array(
        'text-align',
        'margin',
        'color',
        'float',
        'border',
        'background',
        'background-color',
        'border-bottom',
        'border-bottom-color',
        'border-bottom-style',
        'border-bottom-width',
        'border-collapse',
        'border-color',
        'border-left',
        'border-left-color',
        'border-left-style',
        'border-left-width',
        'border-right',
        'border-right-color',
        'border-right-style',
        'border-right-width',
        'border-spacing',
        'border-style',
        'border-top',
        'border-top-color',
        'border-top-style',
        'border-top-width',
        'border-width',
        'caption-side',
        'clear',
        'cursor',
        'direction',
        'font',
        'font-family',
        'font-size',
        'font-style',
        'font-variant',
        'font-weight',
        'height',
        'letter-spacing',
        'line-height',
        'margin-bottom',
        'margin-left',
        'margin-right',
        'margin-top',
        'overflow',
        'padding',
        'padding-bottom',
        'padding-left',
        'padding-right',
        'padding-top',
        'text-decoration',
        'text-indent',
        'vertical-align',
        'width'
      );

      foreach ($styles_wf as $style_wf) {
        if (($key = array_search($style_wf, $styles)) !== false) {
          unset($styles[$key]);
        }
      }
      return $styles;
    });
  } // wp_kses_wf
} // class Easy_Hide_login


add_action('init', array('Easy_Hide_Login', 'init'));
