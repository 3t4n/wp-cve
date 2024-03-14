<?php

class WordablePlugin {
  private $check_connection_memory_cache;
  private $api_host_cache;
  private $secret_cache;
  private $categories_cache;
  private $connector_instance;
  private $wordable_row_cache;

  public static function install() {
    add_filter('tiny_mce_before_init', 'WordablePlugin::add_tiny_mce_before_init');
  }

  function load_styles($paths) {
    foreach($paths as $path) {
      wp_enqueue_style(basename($path), $this->asset_url($path), false, WORDABLE_VERSION);
    }
  }

  function load_scripts($paths) {
    foreach($paths as $path) {
      wp_enqueue_script(basename($path), $this->asset_url($path), false, WORDABLE_VERSION);
    }
  }

  function check_connection() {
    if(!$this->check_connection_memory_cache) {

      $this->check_connection_memory_cache = $this->get_check_connection_database_cache();

      if(!$this->check_connection_memory_cache) {
        $query_params = array(
          'destination[remote_id]' => $this->secret(),
          'destination[admin_url]' => admin_url()
        );

        $url = add_query_arg($query_params,
                             $this->api_url('/wordpress/connection_check'));

        $result = wp_remote_get($url);

        if(gettype($result) == 'array') {
          $this->check_connection_memory_cache = json_decode($result['body']);
          $this->set_check_connection_database_cache($this->check_connection_memory_cache);
        }
      }
    }

    return $this->check_connection_memory_cache;
  }

  function get_check_connection_database_cache() {
    $database_cache = $this->get_wordable_database_cache();

    if($database_cache != NULL) {
      return $database_cache->connection_check;
    }

    return NULL;
  }

  function set_check_connection_database_cache($connection_check) {
    $current_cache = $this->get_wordable_database_cache();

    if($current_cache == NULL) $current_cache = new stdClass();

    $current_cache->connection_check = $connection_check;

    $this->update_wordable_row(
      array(
        'cache' => json_encode($current_cache),
        'cache_updated_at' => date("Y-m-d H:i:s")
      )
    );
  }

  function nuke_database_cache() {
    $this->update_wordable_row(
      array(
        'cache' => NULL,
        'cache_updated_at' => NULL
      )
    );
  }

  function get_wordable_database_cache() {
    $row = $this->get_wordable_row();

    if($row->cache == NULL || $row->cache_updated_at == NULL) return NULL;

    $cache_time = $row->cache_updated_at->diff(new DateTime());

    if($cache_time->d > 1) return NULL;

    return json_decode($row->cache);
  }

  function update_wordable_row($columns) {
    global $wpdb;
    $row = $this->get_wordable_row();

    $wpdb->update($this->wordable_table_name(),
                  $columns,
                  array('id' => $row->id));

    $this->wordable_row_cache = NULL;
  }

  function is_connected() {
    if(!$this->check_connection()) {
      return false;
    }

    return $this->check_connection()->created_at;
  }

  function team_onboarding_step() {
    if(!$this->check_connection() || !$this->check_connection()->created_at) {
      return 0;
    }

    return $this->check_connection()->team_onboarding_step;
  }

  function team_onboarding_step_text() {
    $team_onboarding_step = $this->team_onboarding_step();

    if($team_onboarding_step == 0) {
      return 'Connect Google Drive';
    } else if($team_onboarding_step == 1) {
      return 'Connect WordPress';
    } else if($team_onboarding_step == 2) {
      return 'Import a Document into Wordable';
    }

    return 'Export Your First Document';
  }

  function api_url($path) {
    return esc_url($this->api_host().$path);
  }

  function connector() {
    if(!$this->connector_instance) {
      $this->connector_instance = new WordablePluginConnector($this);
    }

    return $this->connector_instance;
  }

  function connect_url() {
    $url = $this->api_url('/wordpress/connect');

    return esc_url("$url?" . $this->connector()->query_string());
  }

  function article_url($article) {
    $url = $this->api_url('/article' . $article);

    return esc_url("$url?" . 'utm_source=wp-plugin&utm_campaign=wp-plugin-articles&utm_medium=' . $this->secret());
  }

  function signup_url() {
    $url = $this->api_url('/u/sign_up');

    return esc_url("$url?" . 'utm_source=wp-plugin&utm_campaign=wp-plugin-cta&utm_medium=' . $this->secret());
  }

  function dashboard_url($campaign) {
    $url = $this->api_url('');

    return esc_url("$url?" . 'utm_source=wp-plugin&utm_campaign='.$campaign.'&utm_medium=' . $this->secret());
  }

  function authors() {
    return get_users(array(
      'role__in' => array('administrator', 'editor', 'author'),
      'fields' => array('ID', 'user_login', 'user_email', 'display_name'),
      'number' => 200
    ));
  }

  function user_id_to_be_current() {
    foreach (array('administrator', 'editor', 'author', 'contributor') as $role) {
      $users = get_users(array(
        'role__in' => array('administrator'),
        'fields' => array('ID', 'user_login', 'user_email', 'display_name'),
        'number' => 1
      ));

      if(count($users) == 1) {
        return $users[0]->ID;
      }
    }
  }

  function categories() {
    if(!$this->categories_cache) {
      $this->categories_cache = get_categories(array('hide_empty' => 0));
    }

    return $this->categories_cache;
  }

  function build_categories_tree($parent_category_id = 0) {
    $categories = get_categories(array('hide_empty' => 0,
                                       'orderby' => 'name',
                                       'order' => 'ASC',
                                       'parent' => $parent_category_id,
                                       'number' => 100));
    $tree_node = array();

    if($categories) {
      foreach($categories as $category) {
        array_push(
          $tree_node,
          array(
            "id" => $category->term_id,
            "name" => $category->name,
            "children" => $this->build_categories_tree($category->term_id)
          )
        );
      }
    }

    return $tree_node;
  }

  function api_host() {
    if(!$this->api_host_cache) {
      $host_config_file = $this->asset_path('host');

      if (file_exists($host_config_file)) {
        $this->api_host_cache = esc_url(file_get_contents($host_config_file));
      } else {
        $this->api_host_cache = esc_url("https://app.wordable.io");
      }
    }

    return $this->api_host_cache;
  }

  function secret() {
    if(!$this->secret_cache) {
      $this->secret_cache = $this->get_wordable_column('secret');
    }

    return $this->secret_cache;
  }

  function get_wordable_column($column) {
    return $this->get_wordable_row()->$column;
  }

  function get_wordable_row() {
    global $wpdb;

    if(!$this->wordable_row_cache) {
      $table_name = $this->wordable_table_name();
      $this->wordable_row_cache = $wpdb->get_row("SELECT * FROM $table_name");

      if($this->wordable_row_cache->cache_updated_at != NULL) {
        $this->wordable_row_cache->cache_updated_at = new DateTime($this->wordable_row_cache->cache_updated_at);
      }
    }

    return $this->wordable_row_cache;
  }

  function asset_url($path) {
    return WordablePlugin::static_asset_url($path);
  }

  public static function static_asset_url($path) {
    return esc_url(plugin_dir_url(__DIR__) . $path);
  }

  function asset_path($path) {
    $final_path_parts = explode('/', plugin_dir_path(__DIR__) . $path);
    $sanitized_path_parts = array_map('sanitize_file_name', $final_path_parts);
    return implode('/', $final_path_parts);
  }

  function system_report() {
    $system_report = array(
      'secret' => $this->secret(),
      'url' => get_site_url(),
      'admin_url' => admin_url(),
      'plugin_version' => WORDABLE_VERSION,
      'wordpress_version' => get_bloginfo('version'),
      'php_version' => phpversion('tidy'),
      'plugins' => array()
    );

    foreach(array_values(get_plugins()) as $installed_plugin) {
      array_push($system_report['plugins'], $installed_plugin['Name'].' by '.$installed_plugin['Author'].' - '.$installed_plugin['Version']);
    }

    return $system_report;
  }

  public static function add_tiny_mce_before_init($options) {
    if (isset($options['extended_valid_elements'])) {
      $options['extended_valid_elements'] .= ',style';
    } else {
      $options['extended_valid_elements'] = 'style';
    }

    return $options;
  }

  function wordable_table_name() {
    global $wpdb;

    return $wpdb->prefix . 'wordable';
  }
}

WordablePlugin::install();
