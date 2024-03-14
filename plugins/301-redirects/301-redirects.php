<?php
/*
  Plugin Name: 301 Redirects
  Plugin URI: https://wp301redirects.com/
  Description: Easily create & manage redirections, and view 404 error log.
  Version: 1.02
  Author: WebFactory Ltd
  Author URI: https://www.webfactoryltd.com/
  Requires at least: 4.0
  Tested up to: 6.4
  Requires PHP: 5.2

  Copyright 2019 - 2023  WebFactory Ltd  (email: wp301@webfactoryltd.com)
  Copyright 2015 - 2019  @tonyspiro

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


// this is an include only WP file
if (!defined('ABSPATH')) {
  die;
}

require_once 'controllers.php';
require_once 'lib/UserAgentParser.php';

require_once 'wf-flyout/wf-flyout.php';
new wf_flyout(__FILE__);


class ts_redirects
{
  static $version = 0;
  static $plugin_url = '';

  static function init()
  {
    add_action('admin_enqueue_scripts', array(__CLASS__, 'load_301_redirect_assets'));
    add_action('wp', array(__CLASS__, 'redirects_301_do_redirect'), 0, 0);
    add_action('admin_menu', array(__CLASS__, 'add_options_page'));
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(__CLASS__, 'plugin_action_links'));
    add_filter('admin_footer_text', array(__CLASS__, 'admin_footer_text'));
    add_action('wp_ajax_redirect_delete_rule', array(__CLASS__, 'ajax_delete_rule'));
    add_action('template_redirect', array(__CLASS__, 'check_404'), 1);
    add_action('wp_dashboard_setup', array(__CLASS__, 'add_widget'));
    add_action('admin_footer', array(__CLASS__, 'pro_dialog'));
    add_action('admin_notices', array(__CLASS__, 'admin_notices'));

    $plugin_data = get_file_data(__FILE__, array('version' => 'Version'), 'plugin');
    self::$version = $plugin_data['version'];
    self::$plugin_url = plugins_url('', __FILE__) . '/';
  } // init


  // add settings link to plugins page
  static function plugin_action_links($links)
  {
    $settings_link = '<a href="' . admin_url('options-general.php?page=301-redirects') . '" title="Manage redirects">Manage Redirects</a>';
    $pro_link = '<a target="_blank" href="' . admin_url('options-general.php?page=301-redirects#get-pro') . '" title="Get PRO"><b>Get PRO</b></a>';

    array_unshift($links, $pro_link);
    array_unshift($links, $settings_link);

    return $links;
  } // plugin_action_links


  static function load_301_redirect_assets($adminpage)
  {
    if ($adminpage != 'settings_page_301-redirects') {
      return;
    }

    wp_enqueue_style('redirect_301_bootstrap_css', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', false, self::$version);
    wp_enqueue_style('redirect_301_custom_css', plugin_dir_url(__FILE__) . 'css/style.css', false, self::$version);

    wp_enqueue_script('redirect_301_bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', true, self::$version);
    wp_enqueue_script('redirect_301_custom_js', plugin_dir_url(__FILE__) . 'js/ts-redirects.js', true, self::$version);

    wp_enqueue_style('wp-jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-dialog');

    $options = get_option('ts_301_redirection', array());

    $js_localize = array(
      'nonce_delete_rule' => wp_create_nonce('redirect_delete_rule'),
      'auto_open_pro_dialog' => empty($options['dismiss_auto_pro_modal']),
    );
    wp_localize_script('redirect_301_custom_js', 'redirect', $js_localize);

    $options['dismiss_auto_pro_modal'] = true;
    update_option('ts_301_redirection', $options);
  } //load_301_redirect_assets


  // additional powered by text in admin footer; only on 301 page
  static function admin_footer_text($text)
  {
    if (!self::is_plugin_page()) {
      return $text;
    }

    $text = '<i><a href="https://wp301redirects.com/?ref=301-redirects-free" title="Visit WP 301 Redirects site for more info" target="_blank">WP 301 Redirects</a> v' . self::$version . ' by <a href="https://www.webfactoryltd.com/" title="Visit our site to get more great plugins" target="_blank">WebFactory Ltd</a>. Please <a target="_blank" href="https://wordpress.org/support/plugin/301-redirects/reviews/#new-post" title="Rate the plugin">rate the plugin <span>★★★★★</span></a> to help us spread the word. Thank you!</i>';

    return $text;
  } // admin_footer_text


  // test if we're on plugin's page
  static function is_plugin_page()
  {
    $current_screen = get_current_screen();

    if ($current_screen->id == 'settings_page_301-redirects') {
      return true;
    } else {
      return false;
    }
  } // is_plugin_page


  static function check_404()
  {
    if (!is_404()) {
      return;
    }

    $log404 = get_option('301_redirects_404_log', array());

    if (!is_array($log404)) {
      $log404 = array();
    }

    $ua = \tsdonatj\UserAgent\parse_user_agent(@strip_tags($_SERVER['HTTP_USER_AGENT']));
    $agent = trim(@$ua['platform'] . ' ' . @$ua['browser']);
    if (empty($agent)) {
      $agent = 'unknown';
    }

    $last['timestamp'] = current_time('timestamp');
    $last['url'] = @strip_tags($_SERVER['REQUEST_URI']);
    $last['user_agent'] = $agent;
    array_unshift($log404, $last);

    $max = abs(apply_filters('301_redirects_max_404_logs', 50));
    $log404 = array_slice($log404, 0, $max);

    update_option('301_redirects_404_log', $log404);
  } // check_404


  static function redirects_301_do_redirect()
  {
    $siteurl = get_bloginfo('url');
    $actual_link = self::getUrl();
    $all_redirects = $GLOBALS['redirectsplugins']->getAll();

    if ($all_redirects) {
      foreach ($all_redirects as $redirect_id) {
        $la_redirect = $GLOBALS['redirectsplugins']->getFields($redirect_id);
        $la_old_link = str_replace($siteurl, "", $la_redirect['old_link']);
        if ($actual_link == $siteurl . $la_old_link) {
          header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
          header('Cache-Control: post-check=0, pre-check=0', false);
          header('Pragma: no-cache');
          header('Location: ' . $la_redirect['new_link'], true, 301);
          die();
        }
      } // foreach
    }
  } // redirects_301_do_redirect


  static function ajax_delete_rule()
  {
    $redirects = new Redirects;

    check_ajax_referer('redirect_delete_rule');

    if (isset($_POST['custom_id'])) {
      if ($_POST['custom_id'] == 'all') {
        $redirects->delete();
        die('1');
      } else {
        $custom_id = (int) sanitize_text_field($_POST['custom_id']);
        $redirects->remove($custom_id);
        die('1');
      }
    } else {
      die();
    }
  } // ajax_delete_rule


  static function getUrl()
  {
    $url  = @($_SERVER["HTTPS"] != 'on') ? 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] : 'https://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    return $url;
  } // getUrl


  static function redirects_301_options()
  {
    $redirects = new Redirects;

    $savedsuccesfully = false;

    if (isset($_POST['links_audit_submit']) && check_admin_referer('ts_save_rules')) {
      $redirects->delete();

      if (!empty($_POST['title'])) {
        $redirect_arr = $_POST['title'];
        foreach ($redirect_arr as $key => $redirect_title) {
          $title = sanitize_text_field($redirect_title);
          $section = sanitize_text_field($_POST['section'][$key]);
          $new_link = esc_url($_POST['new_link'][$key]);
          $old_link = esc_url($_POST['old_link'][$key]);
          $redirects->edit($title, $section, $new_link, $old_link);
        }
      }

      $savedsuccesfully = true;
    }
?>
    <div class="wrap">
      <h1><img class="logo" src="<?php echo plugin_dir_url(__FILE__); ?>/img/301-black-logo.png" alt="301 Redirects" title="301 Redirects">301 Redirects</h1><br>

      <?php
      if ($savedsuccesfully) {
      ?>
        <div class="alert alert-success">All redirect rules have been saved.<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>
      <?php
      }
      ?>

      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12 col-12 col-lg-9 col-xl-9">

            <ul class="nav nav-tabs" id="main-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="rules-tab" data-toggle="tab" href="#redirect-rules" role="tab" aria-controls="home" aria-selected="true">Redirect Rules</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="log-tab" data-toggle="tab" href="#error-log" role="tab" aria-controls="profile" aria-selected="false">404 Errors Log</a>
              </li>
              <li class="nav-item pro-tab">
                <a class="nav-link" id="pro-tab-link" data-toggle="tab" href="#" role="tab" aria-controls="profile" aria-selected="false">PRO</a>
              </li>
            </ul>

            <div class="tab-content" id="main-tab-content">
              <div class="tab-pane fade show active" id="redirect-rules" role="tabpanel" aria-labelledby="home-tab">

                <p>
                  Add your old path <code>/old-path-from-site/</code> (don't forget to start with a forward slash) in the old link field, and the new path <code>/new-path-somewhere/</code> in the new link field. If you're redirecting to an external URL add the <code>https://</code> prefix.<br>Name and Section serve only for your organization and convenience.
                </p>
                <form action="" method="post">
                  <?php
                  wp_nonce_field('ts_save_rules');
                  ?>
                  <input type="hidden" name="links_audit_submit" value="true">
                  <table class="table table-striped table-bordered">
                    <tr>
                      <th class="">Name</th>
                      <th class="">Section</th>
                      <th class="">Old URL</th>
                      <th class="">New URL</th>
                      <th class=""><a href="#addRowBtn"><span style="vertical-align: middle;" class="dashicons dashicons-plus"></span></a></th>
                    </tr>
                    <?php
                    $custom_redirects = $redirects->getAll();

                    if ($custom_redirects) {
                      $i = 1;
                      foreach ($redirects->getAll() as $custom_id) {

                        $fields = $redirects->getFields($custom_id);
                    ?>
                        <tr class="redirect-row" id="customRow<?php echo esc_attr($custom_id); ?>">
                          <td><input type="text" class="form-control" placeholder="Redirect name (for internal use)" name="title[]" value="<?php echo esc_attr($fields['title']); ?>" /></td>
                          <td><input type="text" class="form-control" placeholder="Section (for internal use)" name="section[]" value="<?php echo esc_attr($fields['section']); ?>" /></td>
                          <td>
                            <input placeholder="Old URL" name="old_link[]" class="form-control short-field" value="<?php echo esc_attr($fields['old_link']); ?>">
                            <a title="Test redirect rule" class="link-icon" target="_blank" href="<?php echo esc_attr($fields['old_link']); ?>"><span class="dashicons dashicons-external"></span></a>
                          </td>
                          <td>
                            <input type="text" class="form-control new-link short-field" placeholder="New URL" name="new_link[]" value="<?php echo esc_attr($fields['new_link']); ?>" />
                          </td>
                          <td>
                            <a title="Go PRO to access stats" class="open-301-pro-dialog pro-feature" data-pro-feature="redirect-rules-chart-icon-<?php echo esc_attr($i); ?>" href="#"><span class="dashicons dashicons-chart-area"></span></a>
                            <a title="Remove redirect rule" class="remove-custom" href="#" data-id="<?php echo esc_attr($custom_id); ?>"><span class="dashicons dashicons-trash"></span></a>
                          </td>
                        </tr>
                    <?php
                        if ($i == 4) {
                          echo '<tr class="row-banner"><td colspan="5">';
                          echo '<a href="#" class="open-301-pro-dialog pro-feature" data-pro-feature="redirect-rules-banner-1"><p><b>Are you tired of adding so many redirect rules one-by-one for each URL?</b><br>
                      WP 301 Redirects PRO automatically fixes URL typos, has advanced URL matching rules, and watcher over permalink changes so you don\'t have to write so many rules.</p></a>';
                          echo '</td></tr>';
                        }
                        if ($i == 12) {
                          echo '<tr class="row-banner"><td colspan="5">';
                          echo '<a href="#" class="open-301-pro-dialog pro-feature" data-pro-feature="redirect-rules-banner-2"><p><b>Need a better way to organize &amp; monitor your redirect rules?</b><br>
                      WP 301 Redirects PRO makes it easy to tag &amp; search rules so it\'s always easy to find them. And with advanced stats for redirects &amp; 404 errors you\'ll always know what\'s going on with your traffic.</p></a>';
                          echo '</td></tr>';
                        }
                        $i++;
                      } // foreach
                    }
                    ?>
                    <tr id="addRow">
                      <td colspan="10" class="text-left">
                        <button type="submit" class="btn btn-primary"><span style="vertical-align: middle;" class="dashicons dashicons-saved"></span> Save All Redirect Rules</button>&nbsp;&nbsp;&nbsp;
                        <a id="addRowBtn" class="btn btn-outline-primary" href="#"><span style="vertical-align: middle;" class="dashicons dashicons-plus"></span> Add a New Redirect Rule</a>&nbsp;&nbsp;&nbsp;<a id="deleteAllRules" class="btn btn-outline-primary" href="#"><span style="vertical-align: text-bottom;" class="dashicons dashicons-trash"></span> Delete All Redirect Rules</a>
                      </td>
                    </tr>
                  </table>
                </form>

              </div>
              <div class="tab-pane fade" id="error-log" role="tabpanel" aria-labelledby="profile-tab">
                <?php self::tab_404(); ?>
              </div>
            </div>

            <p class="d-none">Please <a href="https://wordpress.org/support/plugin/301-redirects/reviews/#new-post" target="_blank">rate the plugin &starf;&starf;&starf;&starf;&starf;</a> to <b>keep it free &amp; maintained</b>. It only takes a minute to rate. Thank you! 👋</p>
          </div>

          <div class="d-md-none d-sm-none d-xs-none col-3 d-lg-block d-xl-block">
            <div class="sidebar-box pro-ad-box">
              <p class="text-center"><a href="https://wp301redirects.com/?ref=301-free-sidebar-box" target="_blank"><img src="<?php echo esc_url(self::$plugin_url) . 'img/wp-301-logo-full.png'; ?>" alt="WP 301 Redirects PRO" title="WP 301 Redirects PRO"></a><br>PRO version is here! Grab the launch discount.<br><b>All prices are LIFETIME!</b></p>

              <ul class="plain-list">
                <li>Advanced Redirects Management &amp; URL Matching Rules</li>
                <li>Auto-fix URL Typos (no rules needed)</li>
                <li>Detailed 404 &amp; Redirect Stats + Email Reports</li>
                <li>URL Cloaking + other features for affiliate marketers</li>
                <li>Licenses &amp; Sites Manager (remote SaaS dashboard)</li>
                <li>Remote Site Stats (stats for all your sites in one place)</li>
                <li>White-label Mode + Complete Plugin Rebranding</li>
                <li>Branded PDF Reports</li>
                <li>Email support from plugin developers</li>
              </ul>

              <p class="text-center"><a href="#" class="open-301-pro-dialog button button-buy" data-pro-feature="sidebar-box">Get PRO Now</a></p>
            </div>
          </div>

        </div>
      </div>
    </div>
<?php
  } // redirects_301_options

  static function tab_404()
  {
    $log = get_option('301_redirects_404_log', array());
    if (!sizeof($log)) {
      echo '<p>You currently don\'t have any data in the 404 error log. That means that you either just installed the plugin, or that you never had a 404 error happen which is <b>awesome 🚀</b>!</p>';
    } else {
      echo '<div class="log-ad-box"><b>Need a more detailed 404 error log?</b> With more data, more insights, per-day stats &amp; an easier way to create redirect rules from 404 errors? Want to have a centralized log for all your websites in one place? <a href="#" class="open-301-pro-dialog pro-feature" data-pro-feature="404-log-banner">Upgrade to WP 301 Redirects PRO.</a></div>';
      echo '<table class="striped widefat">';
      echo '<tr>';
      echo '<th>Date &amp; Time <span class="dashicons dashicons-arrow-down"></span></th>';
      echo '<th>Target URL</th>';
      echo '<th>User Device</th>';
      echo '<th>User Location</th>';
      echo '<th>Referal URL</th>';
      echo '</tr>';

      foreach ($log as $l) {
        echo '<tr>';
        echo '<td nowrap><abbr title="' . esc_html(date(get_option('date_format'), $l['timestamp']) . ' @ ' . date(get_option('time_format'), $l['timestamp']))  . '">' . esc_html(human_time_diff(current_time('timestamp'), $l['timestamp'])) . ' ago</abbr></td>';
        echo '<td><a target="_blank" href="' . esc_html($l['url']) . '">' . esc_html($l['url']) . '</a></td>';
        echo '<td nowrap>' . esc_html($l['user_agent']) . '</td>';
        echo '<td nowrap><a href="#" class="open-301-pro-dialog pro-feature" data-pro-feature="404-log-user-location">Available in PRO</a></td>';
        echo '<td nowrap><a href="#" class="open-301-pro-dialog pro-feature" data-pro-feature="404-log-referral-url">Available in PRO</a></td>';
        echo '</tr>';
      } // foreach

      echo '</table>';

      echo '<p><br><i>By default, the log is limited to the last fifty (chronologically) 404 errors. This is a safe number that ensures the log works on all sites and doesn\'t slow anything down. ';
      echo 'The code imposes no limits on the log size and you can easily overwrite the default limit by using the <code>301_redirects_max_404_logs</code> filter.</i> Details are available in the <a href="https://wordpress.org/plugins/301-redirects/#faq-header" target="_blank">FAQ</a>.</p>';
      echo '<p>If your site gets hundreds and thousands of 404 errors a day we suggesting upgrading to <a href="#" class="open-301-pro-dialog pro-feature" data-pro-feature="404-log-footer">WP 301 Redirects PRO</a> as it automatically fixes 404 errors caused by URL typos, provides a more robust log that can handle tens of thousands of entries, and offers more tools to manage 404 errors.</p>';
    } // if 404
  } // tab_404

  // add widget to dashboard
  static function add_widget()
  {
    if (current_user_can('manage_options')) {
      add_meta_box('wp301_redirects_404_errors', '404 Errors Log', array(__CLASS__, 'widget_content'), 'dashboard', 'side', 'high');
    }
  } // add_widget


  // render widget
  static function widget_content()
  {
    $log = get_option('301_redirects_404_log', array());

    if (!self::check_permalinks()) {
      echo '<p style="padding: 15px; font-size: 14px;"><b>WARNING:</b> 301 Redirects plugin requires that a permalink structure is set. The default (plain) WordPress permalink structure is not compatible with 301 Redirects.<br>Please update the <a href="options-permalink.php" title="Permalinks">Permalink Structure</a>.</p>';
      return;
    }

    echo '<style>#wp301_redirects_404_errors .inside { padding: 0; margin: 0; }';
    echo '#wp301_redirects_404_errors table td { max-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }';
    echo '#wp301_redirects_404_errors table th { font-weight: 500; }';
    echo '#wp301_redirects_404_errors table { border-left: none; border-right: none; border-top: none; }';
    echo '#wp301_redirects_404_errors p { padding: 0 12px 12px 12px; }';
    echo '#wp301_redirects_404_errors .dashicons { opacity: 0.75; font-size: 17px; line-height: 17px; width: 17px; height: 17px;vertical-align: bottom; }</style>';

    if (!sizeof($log)) {
      echo '<p>You currently don\'t have any data in the 404 error log. That means that you either just installed the plugin, or that you never had a 404 error happen which is <b>awesome 🚀</b>!</p>';
      echo '<p>Don\'t like seeing an empty error log? Or just want to see see if the log works? Open any <a target="_blank" title="Open an nonexistent URL to see if the 404 error log works" href="' . esc_url(home_url('/nonexistent/url/')) . '">nonexistent URL</a> and then reload this page.</p>';
    } else {
      echo '<table class="striped widefat">';
      echo '<tr>';
      echo '<th>Date &amp;<br>Time <span class="dashicons dashicons-arrow-down"></span></th>';
      echo '<th>Target URL</th>';
      echo '<th>User Device</th>';
      echo '</tr>';

      $i = 1;
      foreach ($log as $l) {
        echo '<tr>';
        echo '<td nowrap><abbr title="' . esc_html(date(get_option('date_format'), $l['timestamp']) . ' @ ' . date(get_option('time_format'), $l['timestamp']))  . '">' . esc_html(human_time_diff(current_time('timestamp'), $l['timestamp'])) . ' ago</abbr></td>';
        echo '<td><a title="Open target URL in a new tab" target="_blank" href="' . esc_html($l['url']) . '">' . esc_html($l['url']) . '</a> <span class="dashicons dashicons-external"></span></td>';
        echo '<td>' . esc_html($l['user_agent']) . '</td>';
        echo '</tr>';
        $i++;
        if ($i >= 6) {
          break;
        }
      } // foreach
      echo '</table>';

      echo '<p>View the entire <a href="' . esc_url(admin_url('options-general.php?page=301-redirects#error-log')) . '">404 error log</a> in the 301 Redirects plugin or <a href="' . esc_url(admin_url('options-general.php?page=301-redirects#redirect-rules')) . '">create new redirect rules</a> to fix 404 errors.</p>';
    }
  } // widget_content


  static function pro_dialog()
  {
    if (!self::is_plugin_page()) {
      return;
    }
    $out = '';

    $out .= '<div id="wp301-pro-dialog" style="display: none;" title="WP 301 Redirects PRO is here!"><span class="ui-helper-hidden-accessible"><input type="text"/></span>';

    $out .= '<div class="center logo"><a href="https://wp301redirects.com/?ref=301-free-pricing-table" target="_blank"><img src="' . esc_url(self::$plugin_url) . 'img/wp-301-logo-full.png' . '" alt="WP 301 Redirects PRO" title="WP 301 Redirects PRO"></a><br>';

    $out .= '<span>Limited PRO Launch Discount - <b>all prices are LIFETIME</b>! Pay once &amp; use forever!</span>';
    $out .= '</div>';

    $out .= '<table id="wp301-pro-table">';
    $out .= '<tr>';
    $out .= '<td class="center">Lifetime Personal License</td>';
    $out .= '<td class="center">Lifetime Team License</td>';
    $out .= '<td class="center">Lifetime Agency License</td>';
    $out .= '</tr>';

    $out .= '<tr class="prices">';
    $out .= '<td class="center"><del>$79 /year</del><br><span>$49</span> /lifetime</td>';
    $out .= '<td class="center"><del>$159 /year</del><br><span>$59</span> /lifetime</td>';
    $out .= '<td class="center"><del>$299 /year</del><br><span>$99</span> /lifetime</td>';
    $out .= '</tr>';

    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-yes"></span><b>1 Site License</b> ($49 per site)</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span><b>5 Sites License</b> ($12 per site)</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span><b>100 Sites License</b> ($1 per site)</td>';
    $out .= '</tr>';

    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Advanced Redirects Management</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Advanced Redirects Management</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Advanced Redirects Management</td>';
    $out .= '</tr>';

    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Advanced URL Matching Rules</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Advanced URL Matching Rules</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Advanced URL Matching Rules</td>';
    $out .= '</tr>';

    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Auto-fix URL Typos</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Auto-fix URL Typos</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Auto-fix URL Typos</td>';
    $out .= '</tr>';

    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Detailed 404 &amp; Redirect Stats</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Detailed 404 &amp; Redirect Stats</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Detailed 404 &amp; Redirect Stats</td>';
    $out .= '</tr>';


    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>URL Cloaking</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>URL Cloaking</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>URL Cloaking</td>';
    $out .= '</tr>';


    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Licenses & Sites Manager (SaaS)</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Licenses & Sites Manager (SaaS)</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Licenses & Sites Manager (SaaS)</td>';
    $out .= '</tr>';

    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-no"></span>Remote Site Stats</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Remote Site Stats</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Remote Site Stats</td>';
    $out .= '</tr>';

    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-no"></span>White-label Mode</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>White-label Mode</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>White-label Mode</td>';
    $out .= '</tr>';

    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-no"></span>Full Plugin Rebranding</td>';
    $out .= '<td><span class="dashicons dashicons-no"></span>Full Plugin Rebranding</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Full Plugin Rebranding</td>';
    $out .= '</tr>';

    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-no"></span>Branded PDF Reports</td>';
    $out .= '<td><span class="dashicons dashicons-no"></span>Branded PDF Reports</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Branded PDF Reports</td>';
    $out .= '</tr>';

    $out .= '<tr>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Lifetime Updates &amp; Support</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Lifetime Updates &amp; Support</td>';
    $out .= '<td><span class="dashicons dashicons-yes"></span>Lifetime Updates &amp; Support</td>';
    $out .= '</tr>';

    $out .= '<tr>';
    $out .= '<td><span>one-time payment</span><a class="button button-buy" data-href-org="https://wp301redirects.com/buy/?product=personal-launch&ref=pricing-table" href="https://wp301redirects.com/buy/?product=personal-launch&ref=pricing-table" target="_blank">BUY NOW</a></td>';
    $out .= '<td><span>one-time payment</span><a class="button button-buy" data-href-org="https://wp301redirects.com/buy/?product=team-launch&ref=pricing-table" href="https://wp301redirects.com/buy/?product=team-launch&ref=pricing-table" target="_blank">BUY NOW</a></td>';
    $out .= '<td><span>one-time payment</span><a class="button button-buy" data-href-org="https://wp301redirects.com/buy/?product=agency-launch&ref=pricing-table" href="https://wp301redirects.com/buy/?product=agency-launch&ref=pricing-table" target="_blank">BUY NOW</a></td>';
    $out .= '</tr>';

    $out .= '</table>';

    $out .= '<div class="center footer"><b>100% No-Risk Money Back Guarantee!</b> If you don\'t like the plugin over the next 7 days, we will happily refund 100% of your money. No questions asked! Payments are processed by our merchant of records - <a href="https://paddle.com/" target="_blank">Paddle</a>.</div></div>';

    ts_redirects::wp_kses_wf($out);
  } // pro_dialog

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
            'type' => true
        );

        $allowed_tags['fieldset'] = array(
            'class' => true,
            'id' => true,
            'type' => true
        );

        $allowed_tags['link'] = array(
            'class' => true,
            'id' => true,
            'type' => true,
            'rel' => true,
            'href' => true,
            'media' => true
        );

        $allowed_tags['form'] = array(
            'style' => true,
            'class' => true,
            'id' => true,
            'method' => true,
            'action' => true,
            'data-*' => true
        );

        $allowed_tags['script'] = array(
            'class' => true,
            'id' => true,
            'type' => true,
            'src' => true
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
  }


  static function add_options_page()
  {
    add_options_page('301 Redirects', '301 Redirects', 'manage_options', '301-redirects', array(__CLASS__, 'redirects_301_options'));
  } //redirects_301


  static function admin_notices()
  {
    if (!self::check_permalinks()) {
      echo '<div class="notice notice-error">';
      echo '<p style="padding: 15px; font-size: 14px;"><b>WARNING:</b> 301 Redirects plugin requires that a permalink structure is set. The default (plain) WordPress permalink structure is not compatible with 301 Redirects.<br>Please update the <a href="options-permalink.php" title="Permalinks">Permalink Structure</a>.</p>';
      echo '</div>';
    }
  } // admin_notices

  static function check_permalinks()
  {
    global $wp_rewrite;

    if (!isset($wp_rewrite->permalink_structure) || empty($wp_rewrite->permalink_structure)) {
      return false;
    } else {
      return true;
    }
  } // check_permalinks

  static function _deactivation()
  {
    delete_option('ts_301_redirection');
  } // _deactivation
} // class ts_redirects

ts_redirects::init();
register_deactivation_hook(__FILE__, array('ts_redirects', '_deactivation'));
