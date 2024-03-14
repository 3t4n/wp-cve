<?php

if (!class_exists('DB_Reset_Admin')) :

  class DB_Reset_Admin
  {

    private $code;
    private $notice_error;
    private $notice_success;
    private $request;
    private $resetter;
    private $user;
    private $version;
    private $wp_tables;

    public function __construct($version)
    {
      $this->resetter = new DB_Resetter();
      $this->version = $version;

      $this->set_request($_REQUEST);
      $this->set_view_variables();
    }

    private function set_request(array $request)
    {
      $this->request = $request;
    }

    private function set_view_variables()
    {
      $this->set_code();
      $this->set_user();
      $this->set_wp_tables();
    }

    private function set_code()
    {
      $this->code = $this->generate_code();
    }

    private function set_user()
    {
      $this->user = $this->resetter->get_user();
    }

    private function set_wp_tables()
    {
      $this->wp_tables = $this->resetter->get_wp_tables();
    }

    private function generate_code($length = 5)
    {
      return strtoupper(substr(md5(time()), 1, $length));
    }

    public function run()
    {
      add_action('admin_init', array($this, 'reset'));
      add_action('admin_menu', array($this, 'add_tools_menu'));
      add_action('admin_action_install_wpr', array($this, 'install_wpr'));

      add_filter('plugin_action_links_' . plugin_basename(DB_RESET_FILE), array($this, 'plugin_action_links'));
      add_filter('plugin_row_meta', array($this, 'plugin_meta_links'), 10, 2);
      add_filter('admin_footer_text', array($this, 'admin_footer_text'));
      add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }

    public function admin_enqueue_scripts() {
      $current_screen = get_current_screen();
      if ($current_screen->id != 'plugins') {
        return;
      }

      wp_enqueue_script('db-reset', plugins_url('assets/js/database-reset-plugins.js', __FILE__), array('jquery'), $this->version, true);

      $vars = array();
      $vars['info_link'] = admin_url('plugin-install.php?tab=plugin-information&plugin=wp-reset&TB_iframe=true&width=600&height=800');
      $vars['install_link'] = wp_nonce_url(add_query_arg(array('action' => 'install-plugin', 'plugin' => 'wp-reset'), admin_url('update.php')), 'install-plugin_wp-reset');

      wp_localize_script('db-reset', 'db_reset', $vars);
    } // admin_enqueue


    // additional powered by text in admin footer; only on plugin's page
    static function admin_footer_text($text)
    {
      $current_screen = get_current_screen();

      if ($current_screen->id != 'tools_page_database-reset') {
        return $text;
      }

      $text = '<i><a href="https://wordpress.org/plugins/wordpress-database-reset/" target="_blank">WP Database Reset</a> v' . DB_RESET_VERSION . ' by <a href="https://www.webfactoryltd.com/" title="' . __('Visit our site to get more great plugins', 'wordpress-database-reset') . '" target="_blank">' . __('WebFactory Ltd', 'wordpress-database-reset') . '</a>. Please <a href="https://wordpress.org/support/plugin/wordpress-database-reset/reviews/#new-post" target="_blank">rate the plugin &starf;&starf;&starf;&starf;&starf;</a>. Thank you!</i> ' . $text;

      return $text;
    } // admin_footer_text


    // add settings link to plugins page
    function plugin_action_links($links)
    {
      $settings_link = '<a href="' . admin_url('tools.php?page=database-reset') . '" title="' . __('Reset Database', 'wordpress-database-reset') . '">' . __('Reset Database', 'wordpress-database-reset') . '</a>';

      array_unshift($links, $settings_link);

      return $links;
    } // plugin_action_links


    // add links to plugin's description in plugins table
    function plugin_meta_links($links, $file)
    {
      $support_link = '<a target="_blank" href="https://wordpress.org/support/plugin/wordpress-database-reset" title="' . __('Get help', 'wordpress-database-reset') . '">' . __('Support', 'wordpress-database-reset') . '</a>';


      if ($file == plugin_basename(DB_RESET_FILE)) {
        $links[] = $support_link;
      }

      return $links;
    } // plugin_meta_links


    // auto download / install / activate WPR plugin
    function install_wpr()
    {
      check_ajax_referer('install_wpr');
      
      if (false === current_user_can('administrator')) {
        wp_die('Sorry, you have to be an admin to run this action.');
      }

      $plugin_slug = 'wp-reset/wp-reset.php';
      $plugin_zip = 'https://downloads.wordpress.org/plugin/wp-reset.latest-stable.zip';

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
      echo 'If things are not done in a minute <a target="_parent" href="' . admin_url('plugin-install.php?s=wp-reset&tab=search&type=term') . '">install the plugin manually via Plugins page</a><br><br>';
      echo 'Starting ...<br><br>';

      wp_cache_flush();
      $upgrader = new Plugin_Upgrader();
      echo 'Check if WP Reset is already installed ... <br />';
      if ($this->is_plugin_installed($plugin_slug)) {
        echo 'WP Reset is already installed! <br /><br />Making sure it\'s the latest version.<br />';
        $upgrader->upgrade($plugin_slug);
        $installed = true;
      } else {
        echo 'Installing WP Reset.<br />';
        $installed = $upgrader->install($plugin_zip);
      }
      wp_cache_flush();

      if (!is_wp_error($installed) && $installed) {
        echo 'Activating WP Reset.<br />';
        $activate = activate_plugin($plugin_slug);

        if (is_null($activate)) {
          echo 'WP Reset Activated.<br />';

          echo '<script>setTimeout(function() { top.location = "tools.php?page=wp-reset"; }, 1000);</script>';
          echo '<br>If you are not redirected in a few seconds - <a href="tools.php?page=wp-reset" target="_parent">click here</a>.';
        }
      } else {
        echo 'Could not install WP Reset. You\'ll have to <a target="_parent" href="' . admin_url('plugin-install.php?s=wp-reset&tab=search&type=term') . '">download and install manually</a>.';
      }

      echo '</div>';
    } // install_wpr


    function is_plugin_installed($slug)
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


    public function reset()
    {
      if ($this->form_is_safe_to_submit()) {
        try {
          $this->resetter->set_reactivate($this->request['db-reset-reactivate-theme-data']);
          $this->resetter->reset($this->request['db-reset-tables']);
          $this->handle_after_reset();
        } catch (Exception $e) {
          $this->notice_error = $e->getMessage();
        }
      }
    }

    private function form_is_safe_to_submit()
    {
      return isset($this->request['db-reset-code-confirm']) &&
        $this->assert_request_variables_not_empty() &&
        $this->assert_correct_code();
    }

    private function handle_after_reset()
    {
      if (empty($this->request['db-reset-reactivate-theme-data'])) {
        wp_safe_redirect(admin_url());
        exit;
      }

      $this->notice_success = __('The selected tables were reset', 'wordpress-database-reset');
    }

    private function assert_request_variables_not_empty()
    {
      $this->set_empty_request_key('db-reset-tables', array());
      $this->set_empty_request_key('db-reset-reactivate-theme-data', false);

      return true;
    }

    private function set_empty_request_key($key, $default)
    {
      if (!array_key_exists($key, $this->request)) {
        $this->request[$key] = $default;
      }
    }

    private function assert_correct_code()
    {
      if (
        $this->request['db-reset-code'] !==
        $this->request['db-reset-code-confirm']
      ) {
        $this->notice_error = __('You entered the wrong security code', 'wordpress-database-reset');
        return false;
      }

      return true;
    }

    public function add_tools_menu()
    {
      $plugin_page = add_management_page(
        __('Database Reset', 'wordpress-database-reset'),
        __('Database Reset', 'wordpress-database-reset'),
        'manage_options',
        'database-reset',
        array($this, 'render')
      );

      add_action('load-' . $plugin_page, array($this, 'load_assets'));
    }

    public function render()
    {
      require_once(DB_RESET_PATH . '/views/index.php');
    }

    public function load_assets()
    {
      $this->load_stylesheets();
      $this->load_javascript();
    }

    private function load_stylesheets()
    {
      wp_enqueue_style(
        'bsmselect',
        plugins_url('assets/css/bsmselect.css', __FILE__),
        array(),
        $this->version
      );

      wp_enqueue_style(
        'database-reset',
        plugins_url('assets/css/database-reset.css', __FILE__),
        array('bsmselect'),
        $this->version
      );
    }

    private function load_javascript()
    {
      wp_enqueue_script('jquery-ui-dialog');

      wp_enqueue_script(
        'bsmselect',
        plugins_url('assets/js/bsmselect.js', __FILE__),
        array('jquery'),
        $this->version,
        true
      );

      wp_enqueue_script(
        'bsmselect-compatibility',
        plugins_url('assets/js/bsmselect.compatibility.js', __FILE__),
        array('bsmselect'),
        $this->version,
        true
      );

      wp_enqueue_script(
        'database-reset',
        plugins_url('assets/js/database-reset.js', __FILE__),
        array('bsmselect', 'bsmselect-compatibility'),
        $this->version,
        true
      );

      wp_enqueue_style('wp-jquery-ui-dialog');

      wp_localize_script(
        'database-reset',
        'dbReset',
        $this->load_javascript_vars()
      );
    }

    private function load_javascript_vars()
    {
      return array(
        'confirmAlert' => __('Are you sure you want to continue? There is NO UNDO!', 'wordpress-database-reset'),
        'selectTable' => __('Select Tables', 'wordpress-database-reset'),
        'selectOneTable' => __('Please select at least one table to reset.', 'wordpress-database-reset'),
        'wprInstallUrl' => add_query_arg(array('action' => 'install_wpr', '_wpnonce' => wp_create_nonce('install_wpr')), admin_url('admin.php')),
        'wprDialogTitle' => '<img alt="WP Reset" title="WP Reset" src="' . plugins_url('assets/images/wp-reset-logo.png', DB_RESET_FILE) . '">',
      );
    }
  }

endif;
