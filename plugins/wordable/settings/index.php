<?php

class WordablePluginSettings extends WordablePlugin {
  public static function install() {
    add_action('admin_menu', 'WordablePluginSettings::admin_menu');
    add_filter('plugin_action_links_wordable/wordable.php', 'WordablePluginSettings::action_plugin', 10, 5);
  }

  static function admin_menu() {
    add_options_page('Wordable',
                     'Wordable',
                     'manage_options',
                     'wordable-plugin',
                     'WordablePluginSettings::render_index');

    $settings = new WordablePluginSettings();
    $team_onboarding_step = $settings->team_onboarding_step();

    $warning_badge = '';

    if($team_onboarding_step < 999) {
      $warning_badge = ' <span class="awaiting-mod">1</span>';
    }

    add_menu_page('Wordable',
                  "Wordable$warning_badge",
                  'manage_options',
                  'wordable-plugin',
                  'WordablePluginSettings::render_index',
                  WordablePlugin::static_asset_url('/settings/images/menu_logo.svg'));
  }

  static function action_plugin($actions, $plugin_file) {
    return array_merge($actions,
                       array('settings' =>
                             '<a href="options-general.php?page=wordable-plugin">' . __('Settings', 'General') . '</a>'
                       )
    );
  }

  function render($path, $locals = array()) {
    ob_start();
    extract($locals);
    include $this->asset_path('settings/views/' . $path . '.php');
    echo ob_get_clean();
  }

  static function render_index() {
    require_once(ABSPATH . 'wp-includes/pluggable.php');
    $plugin_settings = new WordablePluginSettings();

    $plugin_settings->load_styles(['settings/css/normalize.css', 'settings/css/wf.css', 'settings/css/wordable.css']);
    $plugin_settings->load_scripts(['settings/js/settings.js']);

    $plugin_settings->nuke_database_cache();
    $plugin_settings->render('index');
  }
}

WordablePluginSettings::install();
