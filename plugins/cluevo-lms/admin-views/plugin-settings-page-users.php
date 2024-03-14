<?php
if (is_admin()) {
  function cluevo_init_user_management_page()
  {
    wp_register_script(
      "vue-js",
      plugins_url("/js/vue.js", plugin_dir_path(__FILE__)),
      "",
      CLUEVO_VERSION,
      true
    );
    wp_enqueue_script('vue-js');
    wp_register_script('lodash-js', plugins_url('/js/lodash.min.js', plugin_dir_path(__FILE__)), null, false, false);  // utilities
    wp_add_inline_script('lodash-js', 'window.lodash = _.noConflict();', 'after'); // gutenberg compatibility

    wp_register_script(
      'cluevo-admin-user-view',
      plugins_url('/js/user.admin.js', plugin_dir_path(__FILE__)),
      array("vue-js", "lodash-js"),
      CLUEVO_VERSION,
      true
    );
    wp_localize_script(
      'cluevo-admin-user-view',
      'misc_strings',
      array(
        'reporting_page' => CLUEVO_ADMIN_PAGE_REPORTS,
        'progress_tab' => CLUEVO_ADMIN_TAB_REPORTS_PROGRESS,
        'scorm_tab' => CLUEVO_ADMIN_TAB_REPORTS_SCORM_PARMS
      )
    );
    if (function_exists('wp_set_script_translations')) {
      wp_set_script_translations('cluevo-admin-user-view', 'cluevo', plugin_dir_path(__DIR__) . 'lang');
    }
    wp_localize_script('cluevo-admin-user-view', 'cluevoWpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));  // needed for ajax requests

    wp_enqueue_script('cluevo-admin-user-view');
  }

  function cluevo_render_user_management_page()
  {
    cluevo_init_user_management_page();
?>
    <div class="wrap cluevo-admin-page-container">
      <h1 class="cluevo-admin-page-title-container">
        <div><?php esc_html_e("User Management", "cluevo"); ?></div>
        <img class="plugin-logo" src="<?php echo esc_url(plugins_url("/assets/logo.png", plugin_dir_path(__FILE__)), ['http', 'https']); ?>" />
        <h1>
          <div class="cluevo-admin-page-content-container">
            <div id="user-admin-app" />
          </div>
    </div>

<?php
  }
}
?>
