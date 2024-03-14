<?php
function cluevo_register_settings_section($strSlug, $strTitle, $strDescription = "", $badge = ["title" => "", "class" => ""])
{
  if (empty($strSlug)) return false;
  if (empty($strTitle)) return false;

  $strSlug = sanitize_title($strSlug);

  if (!array_key_exists($strSlug, $GLOBALS["cluevo_settings"])) {
    $GLOBALS["cluevo_settings"][$strSlug] = ["title" => $strTitle, "description" => $strDescription, "badge" => $badge, "sub_sections" => []];
  } else {
    $GLOBALS["cluevo_settings"][$strSlug]["title"] = $strTitle;
    $GLOBALS["cluevo_settings"][$strSlug]["description"] = $strDescription;
    $GLOBALS["cluevo_settings"][$strSlug]["badge"] = $badge;
  }
}

function cluevo_register_settings_sub_section($strSection, $strSlug, $strTitle, $strDescription = "")
{
  if (empty($strSection)) return false;
  if (empty($strSlug)) return false;
  if (empty($strTitle)) return false;

  $strSection = sanitize_title($strSection);
  $strSlug = sanitize_title($strSlug);

  if (!array_key_exists($strSection, $GLOBALS["cluevo_settings"])) return false;

  if (!array_key_exists($strSlug, $GLOBALS["cluevo_settings"][$strSection]["sub_sections"])) {
    $GLOBALS["cluevo_settings"][$strSection]["sub_sections"][$strSlug] = ["title" => $strTitle, "description" => $strDescription, "settings" => []];
  } else {
    $GLOBALS["cluevo_settings"][$strSection]["sub_sections"][$strSlug]["title"] = $strTitle;
    $GLOBALS["cluevo_settings"][$strSection]["sub_sections"][$strSlug]["description"] = $strDescription;
  }
}

function cluevo_register_setting($strSection, $strSubSection, $strSlug, $strTitle, $strDescription = "", $renderCallback = null, $sanitizeCallback = null)
{
  if (empty($strSection)) return false;
  if (empty($strSubSection)) return false;
  if (empty($strSlug)) return false;
  if (empty($strTitle)) return false;
  if (empty($renderCallback)) return false;

  $strSection = sanitize_title($strSection);
  $strSubSection = sanitize_title($strSubSection);
  $strSlug = sanitize_title($strSlug);

  add_action("cluevo_settings_setting_callback_{$strSlug}", $renderCallback);

  if (!array_key_exists($strSlug, $GLOBALS["cluevo_settings"][$strSection]["sub_sections"][$strSubSection]["settings"])) {
    $GLOBALS["cluevo_settings"][$strSection]["sub_sections"][$strSubSection]["settings"][$strSlug] = [
      "title" => $strTitle,
      "description" => $strDescription,
      "render_callback" => $renderCallback,
      "sanitize_callback" => $sanitizeCallback
    ];
  } else {
    return false;
  }
}

class CluevoSettingsPage
{

  public static function init()
  {

    do_action("cluevo_admin_settings_page_init");

    $GLOBALS["cluevo_settings"] = [];

    cluevo_register_settings_section("general-settings", __("General Settings", "cluevo"));
    cluevo_register_settings_section("security", __("Security", "cluevo"), __("You can set various security related options here", "cluevo"));

    cluevo_register_settings_sub_section("general-settings", "module-display", __("Module Display", "cluevo"), __("You can customize the way modules are displayed on your pages.", "cluevo"));
    cluevo_register_setting("general-settings", "module-display", "cluevo-modules-display-mode", __("Display Mode", "cluevo"), __("We recommended the display mode Lightbox where each module opens on the same page.", "cluevo"), "CluevoSettingsPage::render_display_mode");
    cluevo_register_setting("general-settings", "module-display", "cluevo-modules-display-position", __("Position", "cluevo"), __("This setting specifies where you want your modules to display on your pages. This is only applicable when using the iframe display mode.", "cluevo"), "CluevoSettingsPage::render_display_position");

    cluevo_register_settings_sub_section("general-settings", "module-settings", __("Module Settings", "cluevo"), __("Set various module related settings", "cluevo"));
    cluevo_register_setting("general-settings", "module-settings", "cluevo-modules-scorm-success", __("SCORM 1.2 Completion", "cluevo"), __("This settings specifies which lesson status values are required for a module to be considered successfully completed", "cluevo"), "CluevoSettingsPage::render_scorm_success");
    cluevo_register_setting("general-settings", "module-settings", "cluevo-modules-scorm-completion-commit", __("Commit on Completion", "cluevo"), __("Enabling this forces a commit of the progress when a module is finished.", "cluevo"), "CluevoSettingsPage::render_completion_commit");
    cluevo_register_setting("general-settings", "module-settings", "cluevo-modules-attempt-prompt", __("Module attempts", "cluevo"), __("This settings allows you to customize how attempts are handled", "cluevo"), "CluevoSettingsPage::render_attempt_setting");
    cluevo_register_setting("general-settings", "module-settings", "cluevo-module-ratings", __("Module Ratings", "cluevo"), __("This setting allows users to rate your learning modules", "cluevo"), "CluevoSettingsPage::render_module_rating_setting");
    cluevo_register_setting("general-settings", "module-settings", "cluevo-module-ratings-trigger", __("Module Rating Trigger", "cluevo"), __("This setting allows you to define when users are prompted to rate your modules", "cluevo"), "CluevoSettingsPage::render_module_rating_trigger_setting");
    cluevo_register_setting("general-settings", "module-settings", "cluevo-module-commit-interval", __("Module Commit Interval", "cluevo"), __("This setting auto commits a user's progress after a set amount of time. Set to zero to disable.\nWarning: Some modules may already periodically store progress and some browsers may not respect the interval set here and will throttle commits.", "cluevo"), "CluevoSettingsPage::render_module_commit_interval");
    cluevo_register_setting("general-settings", "module-settings", "cluevo-module-guest-progress-notification", __("Display Guest Progress Notification", "cluevo"), __("Displays a notification that progress won't be saved for guest accounts when opening a module", "cluevo"), "CluevoSettingsPage::render_module_guest_progress_notification");

    cluevo_register_settings_sub_section("general-settings", "user-levels", __("User Settings", "cluevo"));
    cluevo_register_setting("general-settings", "user-levels", "cluevo-auto-add-new-users", __("New users are LMS users by default", "cluevo"), null, "CluevoSettingsPage::render_auto_add_new_users");
    cluevo_register_setting("general-settings", "user-levels", "cluevo-max-level", __("Max. Level", "cluevo"), null, "CluevoSettingsPage::render_max_level");
    cluevo_register_setting("general-settings", "user-levels", "cluevo-exp-first-level", __("Exp. required for first level up", "cluevo"), null, "CluevoSettingsPage::render_first_level_exp");
    cluevo_register_setting("general-settings", "user-levels", "cluevo-level-titles", __("Titles", "cluevo"), null, "CluevoSettingsPage::render_level_titles", "cluevo_sanitize_level_titles");

    cluevo_register_settings_sub_section("general-settings", "pages", __("CLUEVO Pages", "cluevo"));
    cluevo_register_setting("general-settings", "pages", "cluevo-tree-index-page", __("Learning Tree Index", "cluevo"), __("You can display the LMS index on any existing page, just select one here.", "cluevo"), "CluevoSettingsPage::render_learning_tree_index", "cluevo_sanitize_post_id");
    cluevo_register_setting("general-settings", "pages", "cluevo-tree-index-page-content-mode", __("Index Page Content", "cluevo"), __("You can either replace the existing page content with the CLUEVO content or display the CLUEVO content before or after the existing content.", "cluevo"), __CLASS__ . "::render_index_page_content_mode", __CLASS__ . '::sanitize_content_mode');

    cluevo_register_setting("general-settings", "pages", "cluevo-login-page", __("Login Page", "cluevo"), __("You can select where you want to display the login page.", "cluevo"), "CluevoSettingsPage::render_login_page_select", "cluevo_sanitize_post_id");
    cluevo_register_setting("general-settings", "pages", "cluevo-login-page-content-mode", __("Login Page Content", "cluevo"), __("You can either replace the existing page content with the CLUEVO content or display the CLUEVO content before or after the existing content.", "cluevo"), __CLASS__ . "::render_login_page_content_mode", __CLASS__ . '::sanitize_content_mode');

    cluevo_register_setting("general-settings", "pages", "cluevo-user-profile-page", __("User Profile Page", "cluevo"), __("You can select where you want to display the user profile page.", "cluevo"), "CluevoSettingsPage::render_user_profile_page_select", "cluevo_sanitize_post_id");
    cluevo_register_setting("general-settings", "pages", "cluevo-user-profile-page-content-mode", __("User Profile Page Content", "cluevo"), __("You can either replace the existing page content with the CLUEVO content or display the CLUEVO content before or after the existing content.", "cluevo"), __CLASS__ . "::render_user_profile_page_content_mode", __CLASS__ . '::sanitize_content_mode');

    cluevo_register_settings_sub_section("general-settings", "advanced", __("Advanced", "cluevo"));
    cluevo_register_setting("general-settings", "advanced", "cluevo-delete-data-on-uninstall", __("Delete all data when uninstalling", "cluevo"), __("By enabling this option the uninstaller removes all CLUEVO data when you uninstall the plugin", "cluevo"), "CluevoSettingsPage::render_uninstall_data_handling");

    cluevo_register_settings_sub_section("security", "module-security", __("Module Security", "cluevo"));
    cluevo_register_setting("security", "module-security", "cluevo-basic-module-security", __("Enable basic module security", "cluevo"), __("Prohibits access to modules from outside your site", "cluevo"), "CluevoSettingsPage::render_basic_module_security");

    cluevo_register_settings_sub_section("security", "login", __("Login System", "cluevo"));
    cluevo_register_setting("security", "login", "cluevo-login-enabled", __("Enable Login Page", "cluevo"), __("Enables the CLUEVO login page", "cluevo"), "CluevoSettingsPage::render_enable_login_page");

    cluevo_register_settings_sub_section("general-settings", "misc", __("Miscellaneous", "cluevo"));
    cluevo_register_setting("general-settings", "misc", "cluevo-breadcrumbs-enabled", __("Display Breadcrumbs", "cluevo"), __("Toggles breadcrumbs on CLUEVO pages", "cluevo"), "CluevoSettingsPage::render_enable_breadcrumbs");
    cluevo_register_setting("general-settings", "misc", "cluevo-default-item-list-style", __("Default Item List Style", "cluevo"), __("Switch between default list styles", "cluevo"), "CluevoSettingsPage::render_default_item_list_style");
    cluevo_register_setting("general-settings", "misc", "cluevo-hide-item-list-style-switch", __("Hide List Style Switch", "cluevo"), __("Hides the item list style switcher", "cluevo"), "CluevoSettingsPage::render_hide_item_list_style_switch");
    cluevo_register_setting("general-settings", "misc", "cluevo-display-item-status-row", __("Display status row for content items", "cluevo"), __("Toggles the status row for content items on content tiles", "cluevo"), "CluevoSettingsPage::render_display_item_status_row");
    cluevo_register_setting("general-settings", "misc", "cluevo-display-item-type-text", __("Display the item type of tree items", "cluevo"), __("Toggles the item type display of items", "cluevo"), "CluevoSettingsPage::render_display_item_type");
    cluevo_register_setting("general-settings", "misc", "cluevo-show-tile-indicator", __("Show item type indicator on tiles", "cluevo"), __("Toggles the display of the item type indicator on tiles (colors for courses, chapters and modules)", "cluevo"), "CluevoSettingsPage::render_show_tile_indicator");
    cluevo_register_setting("general-settings", "misc", "cluevo-display-module-ratings", __("Display module ratings", "cluevo"), __("Toggles the display of module ratings", "cluevo"), "CluevoSettingsPage::render_display_module_ratings");
    cluevo_register_setting("general-settings", "misc", "cluevo-display-module-ratings-threshold", __("Display Rating Threshold", "cluevo"), __("Display only ratings that are equal to or higher than this value", "cluevo"), "CluevoSettingsPage::render_display_module_ratings_threshold");
    cluevo_register_setting("general-settings", "misc", "cluevo-redirect-single-tree-index", __("Redirect Index to Tree", "cluevo"), __("Redirects the index to inside the tree", "cluevo"), "CluevoSettingsPage::render_display_single_tree_redirect");
    cluevo_register_setting("general-settings", "misc", "cluevo-display-empty-item-message", __("Display empty item message", "cluevo"), __("Display a message that an item is empty if no children are accessible or available", "cluevo"), "CluevoSettingsPage::render_display_empty_item_message");

    cluevo_register_setting("security", "module-security", "cluevo-force-https-embeds", __("Force HTTPS for modules", "cluevo"), __("This option forces HTTPS for module embeds, regardless of how your WordPress site URL is configured.", "cluevo"), "CluevoSettingsPage::render_force_https_module_embeds");

    add_action("add_option_cluevo-basic-module-security", "CluevoSettingsPage::handle_basic_security_added", 10, 2);
    add_action("update_option_cluevo-basic-module-security", "CluevoSettingsPage::handle_basic_security_change", 10, 2);

    do_action("cluevo_register_general_settings");
  }

  public static function handle_basic_security_change($old, $new)
  {
    if (empty($new)) {
      self::remove_basic_security();
    } else {
      if ($new == "on") {
        self::add_basic_security();
      }
    }
  }

  public static function handle_basic_security_added($opt, $value)
  {
    if (empty($value)) {
      self::remove_basic_security();
    } else {
      if ($value == "on") {
        self::add_basic_security();
      }
    }
  }

  public static function add_basic_security()
  {
    $dirs = [];
    $dirs[] = cluevo_get_conf_const("CLUEVO_ABS_MODULE_PATH");
    $dirs[] = cluevo_get_conf_const("CLUEVO_ABS_MODULE_ARCHIVE_PATH");
    // see http://tltech.com/info/referrer-htaccess/
    // for cond: can't compare one server var with another
    $content = '<IfModule mod_rewrite.c>
      RewriteEngine On
      RewriteCond %{HTTP_HOST}@@%{HTTP_REFERER} !^([^@]*)@@https?://\1/.*
      RewriteRule ^ - [F]
    </IfModule>';
    foreach ($dirs as $dir) {
      $file = $dir . ".htaccess";
      if ($dir == cluevo_get_conf_const("CLUEVO_ABS_MODULE_ARCHIVE_PATH")) {
        $content .= "\norder deny,allow\ndeny from all";
      }
      @file_put_contents($file, $content);
    }
  }

  public static function remove_basic_security()
  {
    $dirs = [];
    $dirs[] = cluevo_get_conf_const("CLUEVO_ABS_MODULE_PATH");
    $dirs[] = cluevo_get_conf_const("CLUEVO_ABS_MODULE_ARCHIVE_PATH");
    foreach ($dirs as $dir) {
      $file = $dir . ".htaccess";
      if (file_exists($file)) {
        @unlink($file);
      }
    }
  }

  public static function render_enable_login_page()
  {
    $curOpt = get_option("cluevo-login-enabled", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-login-enabled" ' . $checked . ' />';
  }

  public static function render_enable_breadcrumbs()
  {
    $curOpt = get_option("cluevo-breadcrumbs-enabled", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-breadcrumbs-enabled" ' . $checked . ' />';
  }

  public static function render_display_item_status_row()
  {
    $curOpt = get_option("cluevo-display-item-status-row", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-display-item-status-row" ' . $checked . ' />';
  }

  public static function render_display_item_type()
  {
    $curOpt = get_option("cluevo-display-item-type-text", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-display-item-type-text" ' . $checked . ' />';
  }

  public static function render_show_tile_indicator()
  {
    $curOpt = get_option("cluevo-show-tile-indicator", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-show-tile-indicator" ' . $checked . ' />';
  }

  public static function render_display_module_ratings()
  {
    $curOpt = get_option("cluevo-display-module-ratings", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-display-module-ratings" ' . $checked . ' />';
  }

  public static function render_display_empty_item_message()
  {
    $curOpt = get_option("cluevo-display-empty-item-message", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-display-empty-item-message" ' . $checked . ' />';
  }

  public static function render_display_single_tree_redirect()
  {
    $curOpt = get_option("cluevo-redirect-single-tree-index", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-redirect-single-tree-index" ' . $checked . ' />';
  }

  public static function render_display_module_ratings_threshold()
  {
    $curOpt = get_option("cluevo-display-module-ratings-threshold", 0);
    $value = !empty($curOpt) ? (float)$curOpt : 0;
    echo '<input type="number" name="cluevo-display-module-ratings-threshold" value="' . esc_attr($value) . '" min="0" max="5" step="0.01"/>';
  }

  public static function render_set_login_page()
  {
    $ids = get_all_page_ids();
    $curOpt = get_option("cluevo-login-page", 0);
    echo '<select size="1" name="cluevo-login-page">';
    echo '<option value="0">' . esc_html__("CLUEVO Login", "cluevo") . '</option>';
    echo '<option value="-1">' . esc_html__("WordPress Login", "cluevo") . '</option>';
    if (!empty($ids)) {
      foreach ($ids as $id) {
        $selected = ($id == $curOpt) ? "selected" : "";
        echo '<option value="' . esc_attr($id) . '" ' . $selected . '>' . esc_html(get_the_title($id)) . '</option>';
      }
    }
    echo '</select>';
  }

  /**
   * Outputs a checkbox to enable/disable automatically adding new wp users as lms users
   *
   */
  public static function render_auto_add_new_users()
  {
    $curOpt = get_option("cluevo-auto-add-new-users", "on");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-auto-add-new-users" ' . $checked . ' />';
  }

  public static function render_force_https_module_embeds()
  {
    $curOpt = get_option("cluevo-force-https-embeds", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-force-https-embeds" ' . $checked . ' />';
  }

  public static function render_display_mode()
  {
    $curOpt = get_option("cluevo-modules-display-mode");
?>
    <div class="cluevo-radio-group">
      <div>
        <label><input type="radio" name="cluevo-modules-display-mode" value="Popup" <?php if ($curOpt === "Popup") echo "checked" ?> /> Popup</label> <?php esc_html_e("Make sure your users allow popups for your site", "cluevo"); ?>
      </div>
      <div>
        <label><input type="radio" name="cluevo-modules-display-mode" value="Iframe" <?php if ($curOpt === "Iframe") echo "checked" ?> /> Iframe</label> <?php esc_html_e("This display mode loads each module on it's own page", "cluevo"); ?>
      </div>
      <div>
        <label><input type="radio" name="cluevo-modules-display-mode" value="Lightbox" <?php if ($curOpt === "Lightbox") echo "checked" ?> /> Lightbox</label> <?php esc_html_e("This mode opens your modules in an overlay on the same page", "cluevo"); ?>
      </div>
    </div>
  <?php
  }

  /**
   * Outputs a dropdown to select a display position
   *
   */
  public static function render_display_position()
  {
    $curOpt = get_option("cluevo-modules-display-position");
  ?>
    <div class="cluevo-radio-group">
      <div>
        <label><input type="radio" name="cluevo-modules-display-position" value="start" <?php if ($curOpt === "start") echo "checked" ?> /> <?php esc_html_e("Top of the page", "cluevo"); ?></label>
      </div>
      <div>
        <label><input type="radio" name="cluevo-modules-display-position" value="end" <?php if ($curOpt === "end") echo "checked" ?> /> <?php esc_html_e("Bottom of the page", "cluevo"); ?></label>
      </div>
    </div>
  <?php
  }

  /**
   * Outputs a dropdown to select an attempt mode
   *
   */
  public static function render_attempt_setting()
  {
    $curOpt = get_option("cluevo-modules-attempt-prompt", "ask");
  ?>
    <div class="cluevo-radio-group">
      <div>
        <label><input type="radio" name="cluevo-modules-attempt-prompt" value="ask" <?php if ($curOpt === "ask") echo "checked" ?> /> <?php esc_html_e("Let user decide whether to resume or start a new attempt", "cluevo"); ?></label>
      </div>
      <div>
        <label><input type="radio" name="cluevo-modules-attempt-prompt" value="new" <?php if ($curOpt === "new") echo "checked" ?> /> <?php esc_html_e("Always start new attempts", "cluevo"); ?></label>
      </div>
      <div>
        <label><input type="radio" name="cluevo-modules-attempt-prompt" value="resume" <?php if ($curOpt === "resume") echo "checked" ?> /> <?php esc_html_e("Always resume existing attempts", "cluevo"); ?></label>
      </div>
    </div>
  <?php
  }

  /**
   * Renders the module rating setting
   *
   */
  public static function render_module_rating_setting()
  {
  ?>
    <label for="cluevo-module-ratings">
      <input id="cluevo-module-ratings" type="checkbox" value="1" name="cluevo-module-ratings" <?php checked(get_option('cluevo-module-ratings', false)); ?>>
    </label>
  <?php
  }

  /**
   * Renders the module commit on completion setting
   *
   */
  public static function render_completion_commit()
  {
  ?>
    <label for="cluevo-modules-scorm-completion-commit">
      <input id="cluevo-module-scorm-completion-commit" type="checkbox" value="1" name="cluevo-modules-scorm-completion-commit" <?php checked(get_option('cluevo-modules-scorm-completion-commit', false)); ?>>
    </label>
  <?php
  }

  /**
   * Renders the module rating trigger setting
   *
   */
  public static function render_module_rating_trigger_setting()
  {
    $opt = get_option('cluevo-module-ratings-trigger', 'always');
  ?>
    <label for="cluevo-module-ratings-trigger">
      <select id="cluevo-module-ratings-trigger" name="cluevo-module-ratings-trigger">
        <option value="always" <?php if ($opt == 'always') echo "selected"; ?>><?php esc_html_e("Always", "cluevo"); ?></option>
        <option value="success" <?php if ($opt == 'success') echo "selected"; ?>><?php esc_html_e("Successful Completion", "cluevo"); ?></option>
        <option value="completed" <?php if ($opt == 'completed') echo "selected"; ?>><?php esc_html_e("Any Completion", "cluevo"); ?></option>
      </select>
    </label>
  <?php
  }

  /**
   * Renders the module guest progress notification setting
   *
   */
  public static function render_module_guest_progress_notification()
  {
  ?>
    <label for="cluevo-module-guest-progress-notification">
      <input id="cluevo-module-guest-progress-notification" type="checkbox" value="1" name="cluevo-module-guest-progress-notification" <?php checked(get_option('cluevo-module-guest-progress-notification', false)); ?>>
    </label>
  <?php
  }

  /**
   * Renders the module commit interval
   *
   */
  public static function render_module_commit_interval()
  {
    $curOpt = get_option("cluevo-module-commit-interval", '0');
  ?>
    <label for="cluevo-module-commit-interval">
      <input id="cluevo-module-commit-interval" type="range" value="<?php echo esc_attr($curOpt); ?>" min="0" max="600" step="30" name="cluevo-module-commit-interval" />
      <span id="cluevo-module-commit-interval-value"><?php echo esc_attr($curOpt); ?> s</span>
    </label>
  <?php
  }

  /**
   * Outputs scorm status display
   *
   */
  public static function render_scorm_success()
  {
    $curOpt = get_option("cluevo-modules-scorm-success");
    if (empty($curOpt)) $curOpt = "default";
  ?>
    <div class="cluevo-radio-group">
      <div>
        <label><input type="radio" name="cluevo-modules-scorm-success" value="default" <?php if ($curOpt === "default") echo "checked" ?> /> <?php esc_html_e("Default: Status must be 'passed' to count as successfully completed", "cluevo"); ?></label>
      </div>
      <div>
        <label><input type="radio" name="cluevo-modules-scorm-success" value="completed" <?php if ($curOpt === "completed") echo "checked" ?> /> <?php esc_html_e("Completed: Status can be either 'passed' or 'completed' for modules to be considered successfully completed", "cluevo"); ?></label>
      </div>
    </div>
  <?php
  }

  /**
   * Outputs a checkbox to select whether modules are only scored once or not
   *
   */
  function cluevo_display_modules_only_score_once()
  {
  ?>
    <label for="modules-only-score-once">
      <input id="modules-only-score-once" type="checkbox" value="1" name="cluevo-modules-only-score-once" <?php checked(get_option('cluevo-modules-only-score-once', false)); ?>>
    </label>
  <?php
  }

  /**
   * Outputs a textarea containing levels and the corresponding titles
   *
   */
  public static function render_level_titles()
  {
    $titles =  get_option('cluevo-level-titles');
    if (empty($titles)) $titles = new StdClass();
    $displayTitles = '';
    if (!empty($titles)) {
      foreach ($titles as $lvl => $title) {
        $displayTitles .= "$lvl: $title\n";
      }
    }
  ?>
    <div is="cluevo-user-titles" id="cluevo-level-titles" :titles="<?php echo esc_attr(json_encode($titles)); ?>" inline-template>
      <div class="cluevo-user-titles">
        <input type="hidden" name="cluevo-level-titles" :value="titleText" />
        <table class="wp-list-table widefat striped cluevo-level-table">
          <thead>
            <tr>
              <th>Level</th>
              <th class="left">Title</th>
              <th class="left">Tools</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="title of list" inline-template>
              <tr>
                <td class="left"><input type="number" v-model="title.level" min="0" /></td>
                <td class="left"><input type="text" v-model="title.name" /></td>
                <td class="left">
                  <input type="button" @click="removeTitle(title)" class="button" value="<?php esc_attr_e("Remove", "cluevo"); ?>" />
                </td>
              </tr>
            </template>
            <tr>
              <td>
                <input type="number" v-model="newLevel" min="0" />
              </td>
              <td>
                <input type="text" v-model="newName" />
              </td>
              <td>
                <input type="button" class="button" @click="addTitle" :disabled="!isValidInput" value="<?php esc_attr_e("Add Title", "cluevo"); ?>" />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  <?php
  }

  /**
   * Outputs an input field to enter the amount of exp needed for the first level up
   *
   */
  public static function render_first_level_exp()
  {
  ?>
    <input type="text" name="cluevo-exp-first-level" value="<?php echo esc_attr(get_option('cluevo-exp-first-level', CLUEVO_DEFAULT_FIRST_LEVEL_EXP)); ?>" />
  <?php
  }

  /**
   * Outputs an input field to enter the max. possible level
   *
   */
  public static function render_max_level()
  {
  ?>
    <input type="number" name="cluevo-max-level" value="<?php echo esc_attr(get_option('cluevo-max-level', CLUEVO_DEFAULT_MAX_LEVEL)); ?>" />
  <?php
  }

  /**
   * Outputs a checkbox to enable/disable basic module security
   *
   */
  public static function render_basic_module_security()
  {
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
    echo wp_kses($out, wp_kses_allowed_html("post"));
  }

  /**
   * Renders the default item list style setting
   *
   */
  public static function render_hide_item_list_style_switch()
  {
    $curOpt = get_option("cluevo-hide-item-list-style-switch", "");
    echo '<input type="checkbox" name="cluevo-hide-item-list-style-switch" ' . checked($curOpt, "on", false) . ' />';
  }

  /**
   * Renders the default item list style setting
   *
   */
  public static function render_default_item_list_style()
  {
    $opt = get_option('cluevo-default-item-list-style', 'tile');
  ?>
    <label for="cluevo-default-item-list-style">
      <select id="cluevo-default-item-list-style" name="cluevo-default-item-list-style">
        <option value="tile" <?php selected($opt, "col", true); ?>><?php esc_html_e("Tiles", "cluevo"); ?></option>
        <option value="row" <?php selected($opt, "row", true) ?>><?php esc_html_e("Rows", "cluevo"); ?></option>
      </select>
    </label>
  <?php
  }

  /**
   * Outputs a checkbox to select whether cluevo data is retained or deleted on uninstall
   *
   */
  public static function render_uninstall_data_handling()
  {
    $curOpt = get_option("cluevo-delete-data-on-uninstall", "");
    $checked = ($curOpt === "on") ? "checked" : "";
    echo '<input type="checkbox" name="cluevo-delete-data-on-uninstall" ' . $checked . ' />';
  }

  public static function render_learning_tree_index() {
      $curOpt = get_option("cluevo-tree-index-page", 0);
      echo '<select size="1" name="cluevo-tree-index-page">';
      echo '<option value="0">' . esc_html__("", "cluevo") . '</option>';
      $pages = get_pages();
      foreach ($pages as $page) {
        echo '<option value="' . esc_attr($page->ID) . '" ' . selected($page->ID, $curOpt) . '>' . esc_html($page->post_title) . '</option>';
      }
      echo '</select>';
  }

  public static function render_index_page_content_mode()
  {
    $curOpt = get_option("cluevo-tree-index-page-content-mode", "replace");
    echo '<select size="1" name="cluevo-tree-index-page-content-mode">';
    echo '<option value="replace" ' . selected($curOpt, "replace") . '>' . esc_html__("Replace Page Content", "cluevo") . '</option>';
    echo '<option value="before" ' . selected($curOpt, "before") . '>' . esc_html__("Before Page Content", "cluevo") . '</option>';
    echo '<option value="after" ' . selected($curOpt, "after") . '>' . esc_html__("After Page Content", "cluevo") . '</option>';
    echo '</select>';
  }

  public static function render_user_profile_page_select() {
      $curOpt = get_option("cluevo-user-profile-page", 0);
      echo '<select size="1" name="cluevo-user-profile-page">';
      echo '<option value="0">' . esc_html__("", "cluevo") . '</option>';
      $pages = get_pages();
      foreach ($pages as $page) {
        echo '<option value="' . esc_attr($page->ID) . '" ' . selected($page->ID, $curOpt) . '>' . esc_html($page->post_title) . '</option>';
      }
      echo '</select>';
  }

  public static function render_user_profile_page_content_mode()
  {
    $curOpt = get_option("cluevo-user-profile-page-content-mode", "replace");
    echo '<select size="1" name="cluevo-user-profile-page-content-mode">';
    echo '<option value="replace" ' . selected($curOpt, "replace") . '>' . esc_html__("Replace Page Content", "cluevo") . '</option>';
    echo '<option value="before" ' . selected($curOpt, "before") . '>' . esc_html__("Before Page Content", "cluevo") . '</option>';
    echo '<option value="after" ' . selected($curOpt, "after") . '>' . esc_html__("After Page Content", "cluevo") . '</option>';
    echo '</select>';
  }

  public static function render_login_page_select() {
      $curOpt = get_option("cluevo-login-page", 0);
      echo '<select size="1" name="cluevo-login-page">';
      echo '<option value="0">' . esc_html__("", "cluevo") . '</option>';
      $pages = get_pages();
      foreach ($pages as $page) {
        echo '<option value="' . esc_attr($page->ID) . '" ' . selected($page->ID, $curOpt) . '>' . esc_html($page->post_title) . '</option>';
      }
      echo '</select>';
  }

  public static function render_login_page_content_mode()
  {
    $curOpt = get_option("cluevo-login-page-content-mode", "replace");
    echo '<select size="1" name="cluevo-login-page-content-mode">';
    echo '<option value="replace" ' . selected($curOpt, "replace") . '>' . esc_html__("Replace Page Content", "cluevo") . '</option>';
    echo '<option value="before" ' . selected($curOpt, "before") . '>' . esc_html__("Before Page Content", "cluevo") . '</option>';
    echo '<option value="after" ' . selected($curOpt, "after") . '>' . esc_html__("After Page Content", "cluevo") . '</option>';
    echo '</select>';
  }

  public static function save_settings()
  {
    if (!empty($_POST) && check_admin_referer('cluevo-save-settings', 'cluevo-save-settings-nonce')) {
      if (!isset($_POST["action"]) || !wp_verify_nonce($_POST['cluevo-save-settings-nonce'], 'cluevo-save-settings')) {
        wp_die(__("Invalid Nonce", "cluevo"), __("Error", "cluevo"));
      }
      if (!current_user_can("administrator")) {
        wp_die(__("You do not have the permissions to perform this action", "cluevo"), __("Error", "cluevo"));
      }
      if (!empty($_POST["cluevo-save-settings"])) {
        $updated = false;
        foreach ($GLOBALS["cluevo_settings"] as $sectionSlug => $section) {
          foreach ($section["sub_sections"] as $subSlug => $sub) {
            foreach ($sub["settings"] as $slug => $setting) {
              if (array_key_exists($slug, $_POST)) {
                if (!empty($setting["sanitize_callback"])) {
                  add_filter("sanitize_option_{$slug}", $setting["sanitize_callback"]);
                }
                $clean = self::sanitize_option($_POST[$slug]);
                $result = update_option($slug, $clean);
                $updated = ($result === true) ? true : $updated;
              } else {
                $result = update_option($slug, "");
                $updated = ($result === true) ? true : $updated;
              }
            }
          }
        }
        if ($updated) {
          $url = admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS);
          $url = add_query_arg("saved", "true", $url);
          header("Location: {$url}");
          exit;
        }
      }
      $url = admin_url("admin.php?page=" . CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS);
      header("Location: {$url}");
      exit;
    }
  }

  public static function sanitize_content_mode($opt) {
    if (!is_string($opt)) return 'replace';
    if (!in_array($opt, ["replace", "before", "after"])) return "replace";
    return $opt;
  }

  public static function sanitize_option($opt)
  {
    if (!empty($opt) && is_array($opt)) {
      $clean = [];
      foreach ($opt as $key => $value) {
        $cleanKey = sanitize_text_field($key);
        if (is_array($value)) {
          $cleanValue = self::sanitize_option($value);
        } else {
          $cleanValue = sanitize_textarea_field($value);
        }
        $clean[$cleanKey] = $cleanValue;
      }
      return $clean;
    }
    return sanitize_textarea_field($opt);
  }

  public static function render()
  {
    if (!is_array($GLOBALS["cluevo_settings"])) return;

    wp_register_script(
      "vue-js",
      plugins_url("/js/vue.min.js", plugin_dir_path(__FILE__)),
      [],
      CLUEVO_VERSION,
      true
    );
    wp_enqueue_script('vue-js');

    wp_register_script(
      'cluevo-admin-settings-view',
      plugins_url('/js/settings.admin.js', plugin_dir_path(__FILE__)),
      ["vue-js", "cluevo-admin-settings-titles"],
      CLUEVO_VERSION,
      true
    );
    wp_enqueue_script("cluevo-admin-settings-view");

    wp_register_script(
      'cluevo-admin-settings-titles',
      plugins_url('/js/settings.titles.js', plugin_dir_path(__FILE__)),
      ["vue-js"],
      CLUEVO_VERSION,
      true
    );
    wp_enqueue_script("cluevo-admin-settings-titles");
    $page = CLUEVO_ADMIN_PAGE_GENERAL_SETTINGS;
    do_action('cluevo_init_settings_page');
  ?>
    <div class="wrap cluevo-admin-page-container cluevo-general-settings">
      <h1 class="cluevo-admin-page-title-container">
        <div><?php esc_html_e("General Settings", "cluevo"); ?></div>
        <img class="plugin-logo" src="<?php echo plugins_url("/assets/logo.png", plugin_dir_path(__FILE__)); ?>" />
      </h1>
      <div class="cluevo-admin-page-content-container">
        <?php if (!empty($_GET["saved"])) { ?>
          <div class="cluevo-admin-notice cluevo-notice-success">
            <p><?php esc_html_e("Saved", "cluevo"); ?></p>
          </div>
        <?php } ?>
        <div id="cluevo-settings-page">
          <form action="<?php echo admin_url("admin-post.php"); ?>" method="post">
            <?php wp_nonce_field("cluevo-save-settings", "cluevo-save-settings-nonce"); ?>
            <input type="hidden" name="action" value="cluevo-save-settings" />
            <div is="tabs" inline-template class="cluevo-tabs">
              <div class="cluevo-tabs">
                <h2 class="nav-tab-wrapper cluevo">
                  <a v-for="(tab, index) in tabs" @click="selectTab(tab.id, $event)" class="nav-tab" :class="[{ 'nav-tab-active': tab.isActive }, tab.id]">
                    {{ tab.title }}
                    <span v-if="tab.badge && tab.badge.title" :class="[ tab.badge.class ]" class="cluevo-nav-tab-badge">{{ tab.badge.title }}</span>
                  </a>
                </h2>
                <div class="tabs">
                  <?php foreach ($GLOBALS["cluevo_settings"] as $sectionSlug => $section) { ?>
                    <div is="tab" inline-template id="<?php echo esc_attr($sectionSlug); ?>" title="<?php echo esc_attr($section["title"]); ?>" data-badge="<?php echo esc_attr(json_encode($section["badge"])); ?>">
                      <section v-show="isActive" class="cluevo-tab-content">
                        <div class="cluevo-settings-tab-before-description"><?php do_action("cluevo_settings_tab_before_description-$sectionSlug"); ?></div>
                        <?php if (!empty($section["description"])) { ?>
                          <p class="cluevo-settings-section-description <?php echo esc_attr($sectionSlug); ?>">
                            <?php echo nl2br(esc_html($section["description"])); ?>
                          </p>
                        <?php } ?>
                        <div class="cluevo-settings-tab-after-description"><?php do_action("cluevo_settings_tab_after_description-$sectionSlug"); ?></div>
                        <?php if (!empty($section["sub_sections"])) { ?>
                          <?php foreach ($section["sub_sections"] as $subSlug => $subSection) { ?>
                            <details>
                              <summary><?php echo esc_attr($subSection["title"]); ?></summary>
                              <?php do_action("cluevo_settings_sub_section_start-$sectionSlug-$subSlug"); ?>
                              <div class="cluevo-setting-sub-section-content <?php echo esc_attr($subSlug); ?>">
                                <?php if (!empty($subSection["description"])) { ?>
                                  <p class="cluevo-settings-sub-section-description <?php echo esc_attr($subSlug); ?>">
                                    <?php echo nl2br(esc_html($subSection["description"])); ?>
                                  </p>
                                <?php } ?>
                                <?php //if (is_callable($subSection["callback"])) $subSection["callback"](); 
                                ?>
                                <?php if (!empty($subSection["settings"])) { ?>
                                  <?php foreach ($subSection["settings"] as $slug => $setting) { ?>
                                    <div class="cluevo-setting <?php echo esc_attr($slug); ?>">
                                      <div class="cluevo-setting-content">
                                        <h3><?php echo esc_html($setting["title"]); ?></h3>
                                        <div class="cluevo-setting-callback <?php echo esc_attr($slug); ?>"><?php do_action("cluevo_settings_setting_callback_$slug"); ?></div>
                                      </div>
                                      <?php if (!empty($setting["description"])) { ?>
                                        <p class="cluevo-settings-setting-description <?php echo esc_attr($slug); ?>">
                                          <?php echo nl2br(esc_html($setting["description"])); ?>
                                        </p>
                                      <?php } ?>
                                    </div>
                                  <?php } ?>
                                <?php } ?>
                              </div>
                              <?php do_action("cluevo_settings_sub_section_end-$sectionSlug-$subSlug"); ?>
                            </details>
                          <?php } ?>
                        <?php } ?>
                      </section>
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <input type="submit" name="cluevo-save-settings" value="<?php echo esc_attr_e("Save", "cluevo"); ?>" class="button button-primary cluevo-settings-save-button" />
          </form>
        </div>
      </div>
    </div>
<?php } // /render
} // /CluevoSettingsPage

if (is_admin()) {
  add_action('admin_post_cluevo-save-settings', "CluevoSettingsPage::save_settings");
  add_action("admin_init", "CluevoSettingsPage::init");
}
