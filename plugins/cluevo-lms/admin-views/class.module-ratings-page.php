<?php
if (!class_exists('CluevoModuleRatingsPage')) {


  class CluevoModuleRatingsPage
  {

    /**
     * Initializes admin functions
     *
     */
    public static function admin_init()
    {
      add_action("admin_menu", "CluevoModuleRatingsPage::add_page", 80);
    }

    /**
     * Adds the page entry to the cluevo menu
     *
     */
    public static function add_page()
    {

      add_submenu_page(
        CLUEVO_ADMIN_PAGE_LMS,
        __("Module Ratings", "cluevo"),
        __("Module Ratings", "cluevo"),
        "manage_options",
        "cluevo-module-ratings",
        "CluevoModuleRatingsPage::render",
        80
      );
    }

    public static function render()
    {
      if (empty($_GET["module_id"])) {
        self::renderList();
      } else {
        self::renderDetails();
      }
    }

    private static function renderDetails()
    {
      global $wpdb;
      $moduleId = (int)$_GET["module_id"];
      $module = cluevo_get_module($moduleId);
      $modules = $wpdb->prefix . CLUEVO_DB_TABLE_MODULES;
      global $wpdb;
      $sql = "SELECT u.*, meta_value, user_email FROM
        wp_usermeta m
        INNER JOIN {$modules} cm ON m.meta_key = CONCAT('cluevo-module-rating-', cm.module_id)
        LEFT JOIN {$wpdb->users} u ON u.ID = m.user_id
        WHERE m.meta_key LIKE 'cluevo-module-rating-%' AND m.meta_key != 'cluevo-module-rating-avg'
        AND cm.module_id = %d";
      $results = $wpdb->get_results(
        $wpdb->prepare($sql, [$moduleId])
      );
      $results = array_map(function ($item) {
        $item->meta_value = maybe_unserialize($item->meta_value);
        return $item;
      }, $results);

      $average = get_post_meta($module->metadata_id, 'cluevo-module-rating-avg', true);
?>
      <div class="wrap cluevo-admin-page-container cluevo-module-ratings">
        <h1 class="cluevo-admin-page-title-container">
          <div><?php echo sprintf(__("Module Ratings: %s", "cluevo"), $module->module_name); ?></div>
          <img class="plugin-logo" src="<?php echo esc_url(plugins_url("/assets/logo.png", plugin_dir_path(__FILE__)), ['http', 'https']); ?>" />
        </h1>
        <a href="<?php echo esc_url(remove_query_arg('module_id')); ?>" class="button cluevo-back-button">⮤ <?php esc_html_e("Back", "cluevo") ?></a>
        <div class="cluevo-admin-page-content-container">
          <?php if (!empty($average)) { ?>
            <div class="cluevo-module-rating-average">
              <dl>
                <dt><?php esc_html_e("Highest Rating", "cluevo"); ?></dt>
                <dd><?php echo esc_html(number_format($average["max"], 2)); ?></dd>
                <dt><?php esc_html_e("Lowest Rating", "cluevo"); ?></dt>
                <dd><?php echo esc_html(number_format($average["min"], 2)); ?></dd>
                <dt><?php esc_html_e("Total Ratings", "cluevo"); ?></dt>
                <dd><?php echo esc_html($average["count"]); ?></dd>
                <dt><?php esc_html_e("Average Rating", "cluevo"); ?></dt>
                <dd><?php echo esc_html(number_format($average["value"], 2)); ?></dd>
              </dl>
              <div>
              <?php } ?>
              <?php foreach ($results as $row) { ?>
                <details class="cluevo-user-rating-summary">
                  <summary>
                    <div class="cluevo-user-rating-name"><?php echo esc_html($row->display_name); ?></div>
                    <div class="cluevo-user-rating-avg">
                      <div>⭳<?php if (isset($row->meta_value["avg"]["min"])) echo esc_html(number_format($row->meta_value["avg"]["min"], 2)); ?></div>
                      <div>⭱<?php if (isset($row->meta_value["avg"]["max"])) echo esc_html(number_format($row->meta_value["avg"]["max"], 2)); ?></div>
                      <div>Ø<?php if (isset($row->meta_value["avg"]["value"])) echo esc_html(number_format($row->meta_value["avg"]["value"], 2)); ?></div>
                    </div>
                  </summary>
                  <table class="wp-list-table widefat fixed striped">
                    <thead>
                      <tr>
                        <th><?php esc_html_e("Attempt", "cluevo"); ?></th>
                        <th><?php esc_html_e("Date", "cluevo"); ?></th>
                        <th><?php esc_html_e("Rating", "cluevo"); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($row->meta_value["ratings"] as $item) { ?>
                        <tr>
                          <td><?php if (isset($item["attempt"])) echo esc_html($item["attempt"] + 1); ?></td>
                          <td class="cluevo-ts-to-locale-date"><?php echo esc_html($item["ts"]); ?></td>
                          <td><?php echo esc_html($item["rating"]); ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </details>
              <?php } ?>
              </div>
            </div>
          <?php }

        /**
         * Renders the diva user profile list page
         *
         */
        private static function renderList()
        {
          ?>
            <div class="wrap cluevo-admin-page-container">
              <h1 class="cluevo-admin-page-title-container">
                <div><?php esc_html_e("Module Ratings", "cluevo"); ?></div>
                <img class="plugin-logo" src="<?php echo esc_url(plugins_url("/assets/logo.png", plugin_dir_path(__FILE__)), ['http', 'https']); ?>" />
              </h1>
              <div class="cluevo-admin-page-content-container">
                <?php
                $modules = cluevo_get_modules();
                global $wpdb;
                $sql = "SELECT post_id, meta_value 
      FROM {$wpdb->prefix}postmeta
      WHERE meta_key = 'cluevo-module-rating-avg'";
                $results = $wpdb->get_results($sql);
                $rows = [];
                if (!empty($results)) {
                  foreach ($results as $r) {
                    $matches = array_filter($modules, function ($m) use ($r) {
                      return $m->metadata_id == $r->post_id;
                    });
                    if (empty($matches)) continue;
                    $module = array_pop($matches);
                    $module->rating_stats = maybe_unserialize($r->meta_value);
                    $rows[] = $module;
                  }
                }
                if (!empty($rows)) {
                ?>
                  <table class="wp-list-table widefat striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th class="left"><?php esc_html_e("Name", "cluevo"); ?></th>
                        <th><?php esc_html_e("Min", "cluevo"); ?></th>
                        <th><?php esc_html_e("Max", "cluevo"); ?></th>
                        <th><?php esc_html_e("Average", "cluevo"); ?></th>
                        <th><?php esc_html_e("Count", "cluevo"); ?></th>
                        <th><?php esc_html_e("Last Rating", "cluevo"); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($rows as $module) { ?>
                        <tr>
                          <td><?php echo esc_html($module->module_id); ?></td>
                          <td class="left">
                            <a href="<?php echo add_query_arg("module_id", $module->module_id); ?>"><?php echo esc_html($module->module_name); ?></a>
                          </td>
                          <td><?php if (!empty($module->rating_stats["min"])) echo esc_html($module->rating_stats['min']); ?></td>
                          <td><?php if (!empty($module->rating_stats["max"])) echo esc_html($module->rating_stats['max']); ?></td>
                          <td><?php echo esc_html(number_format($module->rating_stats['value'], 2)); ?></td>
                          <td><?php echo esc_html($module->rating_stats['count']); ?></td>
                          <td class="cluevo-ts-to-locale-date"><?php echo esc_html($module->rating_stats['ts']); ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                <?php } else {
                  cluevo_display_notice(__("Information", "cluevo"), __("Seems like no modules have been rated yet.", "cluevo"), 'warning');
                } ?>
              </div>
        <?php }
      }
      CluevoModuleRatingsPage::admin_init();
    }
        ?>
