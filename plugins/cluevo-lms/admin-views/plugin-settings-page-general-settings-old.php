<?php
if (is_admin()) {

  /**
   * Initializes general settings
   *
   */
  function cluevo_init_general_settings() {

    // general settings
    add_settings_section("cluevo-settings", __("Settings", "cluevo"), null, CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS);
    add_settings_field("cluevo-modules-display-mode", __("Module display type", "cluevo"), "cluevo_render_module_display_mode", CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS, "cluevo-settings", "cluevo");
    add_settings_field("cluevo-modules-display-position", __("Iframe position (only for iframe display mode)", "cluevo"), "cluevo_render_module_display_position", CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS, "cluevo-settings", "cluevo");
    add_settings_field("cluevo-auto-add-new-users", __("New users are LMS users by default", "cluevo"), "cluevo_render_auto_add_users_setting", CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS, "cluevo-settings", "cluevo");
    add_settings_field("cluevo-delete-data-on-uninstall", __("Delete all data (tables and modules) when uninstalling", "cluevo"), "cluevo_display_uninstall_data_handling", CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS, "cluevo-settings", "cluevo");

    register_setting("cluevo-settings", "cluevo-modules-display-mode");
    register_setting("cluevo-settings", "cluevo-modules-display-position");
    register_setting("cluevo-settings", "cluevo-enable-guests");
    register_setting("cluevo-settings", "cluevo-auto-add-new-users");
    register_setting("cluevo-settings", "cluevo-display-diagonal-tiles");
    register_setting("cluevo-settings", "cluevo-delete-data-on-uninstall");

    add_settings_section("cluevo-levels", __("Levels", "cluevo"), null, CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS);
    add_settings_field("cluevo-max-level", __("Maximum level", "cluevo"), "cluevo_display_max_level", CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS, "cluevo-levels", "cluevo");
    add_settings_field("cluevo-exp-first-level", __("Points needed for first level up", "cluevo"), "cluevo_display_exp_first_level", CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS, "cluevo-levels", "cluevo");
    add_settings_field("cluevo-level-titles", __("Titles", "cluevo"), "cluevo_display_level_titles", CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS, "cluevo-levels", "cluevo");

    register_setting("cluevo-settings", "cluevo-max-level", array("sanitize_callback" => "cluevo_sanitize_level_input"));
    register_setting("cluevo-settings", "cluevo-exp-first-level", array("sanitize_callback" => "cluevo_sanitize_first_level_exp"));
    register_setting("cluevo-settings", "cluevo-level-titles", array("sanitize_callback" => "cluevo_sanitize_level_titles"));

    add_settings_section("cluevo-security", __("Security", "cluevo"), null, CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS);
    add_settings_field("cluevo-basic-module-security", __("Enable basic module security (modules can't be accessed from outside your LMS)", "cluevo"), "cluevo_display_enable_basic_security", CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS, "cluevo-security", "cluevo");
    register_setting("cluevo-settings", "cluevo-basic-module-security");

    add_settings_field("cluevo-force-https-embeds", __("Force https for module embeds", "cluevo"), "cluevo_display_force_https_module_embeds", CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS, "cluevo-security", "cluevo");
    register_setting("cluevo-settings", "cluevo-force-https-embeds");
  }

  /**
   * Outputs a dropdown to select a display mode
   *
   */
  function cluevo_render_module_display_mode() {
    $curOpt = get_option("cluevo-modules-display-mode");
    $options = [ "Popup", "Iframe", "Lightbox" ];
  ?>
    <select size="1" id="cluevo-modules-display-mode" name="cluevo-modules-display-mode">
    <?php foreach ($options as $opt) { ?>
    <option value="<?php echo $opt; ?>" <?php echo ($curOpt == $opt) ? "selected" : ""; ?>><?php echo $opt; ?></option>
    <?php } ?>
    </select>
  <?php
  }

  /**
   * Outputs a dropdown to select a display position
   *
   */
  function cluevo_render_module_display_position() {
    $curOpt = get_option("cluevo-modules-display-position");
  ?>
    <select size="1" id="cluevo-modules-display-position" name="cluevo-modules-display-position">
    <option value="start" <?php echo ($curOpt == 'start') ? "selected" : ""; ?>><?php esc_html_e("Top of the page", "cluevo"); ?></option>
    <option value="end" <?php echo ($curOpt == 'end') ? "selected" : ""; ?>><?php echo esc_html_e("Bottom of the page", "cluevo"); ?></option>
    </select>
  <?php
  }

  /**
   * Outputs a checkbox to enable/disable diagonal header backgrounds for items
   *
   */
  function cluevo_render_diagonal_tiles_setting() {
    $curOpt = get_option("cluevo-display-diagonal-tiles");
    $checked = $curOpt ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-display-diagonal-tiles" ' . $checked . ' />';
  }

  /**
   * Outputs a checkbox to enable/disable guest account creation
   *
   */
  function cluevo_render_guest_account_creation_setting() {
    $curOpt = get_option("cluevo-enable-guests");
    $checked = $curOpt ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-enable-guests" ' . $checked . ' />';
  }

  /**
   * Outputs a checkbox to enable/disable automatically adding new wp users as lms users
   *
   */
  function cluevo_render_auto_add_users_setting() {
    $curOpt = get_option("cluevo-auto-add-new-users", "on");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-auto-add-new-users" ' . $checked . ' />';
  }

  /**
   * Outputs a checkbox to select whether cluevo data is retained or deleted on uninstall
   *
   */
  function cluevo_display_uninstall_data_handling() {
    $curOpt = get_option("cluevo-delete-data-on-uninstall", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-delete-data-on-uninstall" ' . $checked . ' />';
  }

  /**
   * Outputs a checkbox to enable/disable basic module security
   *
   */
  function cluevo_display_enable_basic_security() {
    $curOpt = get_option("cluevo-basic-module-security", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-basic-module-security" ' . $checked . ' />';
    $dirs = [];
    $dirs[] = cluevo_get_conf_const("CLUEVO_ABS_MODULE_PATH");
    $dirs[] = cluevo_get_conf_const("CLUEVO_ABS_MODULE_ARCHIVE_PATH");
    $out = " (";
    foreach ($dirs as $dir) {
      $file = $dir . ".htaccess";
      if (file_exists($file)) {
        $out .= esc_html(basename($dir)) . ": " . esc_html__("secured", "cluevo")  . ", ";
      } else {
        $out .= esc_html(basename($dir)) . ": " . esc_html__("not secured", "cluevo")  . ", ";
      }
    }
    $out = trim($out, ", ") . ")";
    echo $out;
  }

  function cluevo_display_force_https_module_embeds() {
    $curOpt = get_option("cluevo-force-https-embeds", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-force-https-embeds" ' . $checked . ' />';
  }

  /**
   * Outputs the general settings page
   *
   * @param mixed $args
   */
  function cluevo_render_general_settings_page($args) {
    do_action('cluevo_init_admin_page');
?>

  <div class="cluevo-admin-page-container">
    <div class="cluevo-admin-page-title-container">
      <h1><?php esc_html_e("General Settings", "cluevo"); ?></h1>
      <img class="plugin-logo" src="<?php echo plugins_url("/assets/logo-white.png", plugin_dir_path(__FILE__)); ?>" />
    </div>
    <div class="cluevo-admin-page-content-container">
    <form method="post" action="options.php" enctype="multipart/form-data">
  <?php
    do_settings_sections(CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS);
    settings_fields("cluevo-settings");
  ?>
    <div class="cluevo-btn cluevo-btn-primary cluevo-form-submit-btn" id="submit"><?php esc_attr_e("Save", "cluevo"); ?></div>
    </form>
  </div>
  <?php
  }

  /**
   * Outputs a checkbox to select whether modules are only scored once or not
   *
   */
  function cluevo_display_modules_only_score_once() {
    ?>
      <label for="modules-only-score-once">
          <input id="modules-only-score-once" type="checkbox" value="1" name="cluevo-modules-only-score-once" <?php checked( get_option( 'cluevo-modules-only-score-once', false) ); ?>>
      </label>
      <?php
  }

  /**
   * Outputs a textarea containing levels and the corresponding titles
   *
   */
  function cluevo_display_level_titles() {
    $titles =  get_option('cluevo-level-titles');
    $displayTitles = '';
    if (!empty($titles)) {
      foreach ($titles as $lvl => $title) {
        $displayTitles .= "$lvl: $title\n";
      }
    }
  ?>
    <div class="level-title-input-container">
      <textarea name="cluevo-level-titles" rows="10" placeholder="<?php esc_attr_e("Format: level: title. Example: 1: Beginner", "cluevo"); ?>"><?php echo $displayTitles; ?></textarea>
      <div class="explanation">
        <p><?php esc_html_e("One entry per line in the format: level: titel. Example: 1: Beginner", "cluevo"); ?></p>
      </div>
    </div>
  <?php
  }

  /**
   * Outputs an input field to enter the amount of exp needed for the first level up
   *
   */
  function cluevo_display_exp_first_level() {
  ?>
    <input type="text" name="cluevo-exp-first-level" value="<?php echo esc_attr( get_option('cluevo-exp-first-level', CLUEVO_DEFAULT_FIRST_LEVEL_EXP) ); ?>" />
  <?php
  }

  /**
   * Outputs an input field to enter the max. possible level
   *
   */
  function cluevo_display_max_level() {
  ?>
   <input type="text" name="cluevo-max-level" value="<?php echo esc_attr( get_option('cluevo-max-level', CLUEVO_DEFAULT_MAX_LEVEL) ); ?>" />
  <?php
  }

  add_action("admin_init", "cluevo_init_general_settings");

}
?>
