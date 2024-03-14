<?php

class WordablePluginActivator extends WordablePlugin {
  public static function install() {
    (new WordablePluginActivator())->migrate();
    add_action('admin_notices', 'WordablePluginActivator::admin_notices');
  }

  static function admin_notices() {
    $protocol = is_ssl() ? 'https://' : 'http://';
    $currentUrl = ($protocol) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    if(($currentUrl == admin_url() . 'options-general.php?page=wordable-plugin') ||
       $currentUrl == admin_url() . 'admin.php?page=wordable-plugin') return;

    $activator = new WordablePluginActivator();
    $team_onboarding_step = $activator->team_onboarding_step();

    if($team_onboarding_step == 0 || !$activator->is_connected()) {
      $plugin_url = esc_url(admin_url() . 'options-general.php?page=wordable-plugin');

      echo '<div class="notice notice-warning"><p>Wordable Activated! Next, connect to Wordable in your <a href="'.$plugin_url.'">Settings</a></p></div>';
    } else if($team_onboarding_step < 999) {
      echo '<div class="notice notice-warning"><p>Wordable Step '.$team_onboarding_step.': <a class="link" href="' . $activator->dashboard_url('wp-plugin-admin-notice-next-step') . '" target="blank">' . $activator->team_onboarding_step_text() . '</a></p></div>';
    }
  }

  static function activation_hook() {
    (new WordablePluginActivator())->activate();
  }

  function activate() {
    global $wpdb;

    $table_name = $this->wordable_table_name();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `secret` TINYTEXT NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);

    if ($this->secret() == "") {
      $wpdb->insert($table_name, array('secret' => $this->generate_secret()));
    }

    $this->migrate();
  }

  function migrate() {
    global $wpdb;
    $table_name = $this->wordable_table_name();
    $row = $this->get_wordable_row();

    if(isset($row->plugin_version) && $row->plugin_version == WORDABLE_VERSION) return;

    $migrations = [
      [ 'type' => 'add_column', 'column_name' => 'plugin_version', 'column_type' => 'TEXT' ],
      [ 'type' => 'add_column', 'column_name' => 'cache', 'column_type' => 'TEXT' ],
      [ 'type' => 'add_column', 'column_name' => 'cache_updated_at', 'column_type' => 'DATETIME' ]
    ];

    foreach($migrations as $migration) {
      if($migration['type'] == 'add_column') {
        $column_name = $migration['column_name'];
        $column_type = $migration['column_type'];

        if(!isset($row->$column_name)) {
          $wpdb->query("ALTER TABLE $table_name ADD $column_name $column_type");
        }
      }
    }

    $this->update_wordable_row(array('plugin_version' => WORDABLE_VERSION));
  }

  function generate_secret() {
    return implode('-', [
      round(microtime(1)),
      bin2hex(random_bytes(6)),
      bin2hex(random_bytes(6))
    ]);
  }
}

WordablePluginActivator::install();
