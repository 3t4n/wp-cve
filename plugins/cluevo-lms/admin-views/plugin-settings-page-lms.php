<?php
if (is_admin()) {
  /**
   * Outputs the selected course group
   *
   */
  function cluevo_render_learning_structure_ui()
  {
    $tab = (!empty($_GET["tab"]) && ctype_alpha($_GET["tab"])) ? cluevo_strip_non_alpha($_GET["tab"]) : CLUEVO_ADMIN_TAB_LMS_STRUCTURE;
    $create_page = (!empty($_GET["create-metadata-page"]) && is_numeric($_GET["create-metadata-page"])) ? (int)$_GET["create-metadata-page"] : null;
    if (!empty($create_page)) {

      $item = cluevo_get_learning_structure_item($create_page);
      if (!empty($item)) {
        $tmpTree = [$item->item_id => $item];
        $newMeta = cluevo_create_metadata_post($item, $item->parent_id, $tmpTree);
        $tmpTree[$item->item_id]->metadata_id = $newMeta;
        if (!empty($tmpTree)) {
          foreach ($tmpTree as $item) {
            if (!empty($item->metadata_id)) {
              cluevo_update_learning_structure_item($item);
            }
          }
        }
      }
    }

    $treeIndex = 1;

    $tree = cluevo_get_learning_structure_item(1);

    if (empty($tree)) {
      cluevo_create_default_tree();
      $tree = cluevo_get_learning_structure_item(1);
    }

    $treeName = $tree->name;

    $modules = cluevo_get_modules();
    $mysqlVersion = cluevo_get_mysql_server_version();
    global $wpdb;
    $mysql_server_type = $wpdb->db_server_info();
    $isMariaDb = false;
    if (stristr($mysql_server_type, 'mariadb')) {
      $isMariaDb = true;
    }
    $compareVersion = $isMariaDb ? "10.3" : "5.6.0";
    if (version_compare($compareVersion, $mysqlVersion) === 1) {
      cluevo_display_notice(
        __("Warning", "cluevo"),
        sprintf(__("CLUEVO LMS requires at least MySQL Server version 5.6 or a MariaDB equivalent version (10.x), you are currently using %s.\nIssues with empty learning structure or the failure to create and/or save your learning tree are commonly related to outdated MySQL server versions.", "cluevo"), $mysqlVersion),
        'warning',
        'cluevo-mysql-version-warning'
      );
    }
?>
    <form method="post" id="tree-form" action="<?php echo admin_url("admin-post.php"); ?>">
      <input type="hidden" name="page" value="<?php echo esc_attr(CLUEVO_ADMIN_PAGE_LMS); ?>" />
      <input type="hidden" name="tab" value="<?php echo esc_attr($tab); ?>" />
      <input type="hidden" name="action" value="cluevo-save-tree" />
      <?php wp_nonce_field('cluevo-save-tree', 'cluevo-save-tree-nonce', true, true); ?>
      <input type="hidden" name="lms-tree-flat" value="" />
      <div class="course-group-selection-container">
        <label><?php esc_html_e("Name of the group", "cluevo"); ?>
          <input type="text" name="rename-tree" id="lms-tree-name-input" value="<?php echo esc_attr($treeName); ?>" />
        </label>
        <button type="button" class="cluevo-btn cluevo-btn-primary cluevo-form-submit-btn"><?php esc_attr_e("Save", "cluevo"); ?></button>
        <input type="hidden" name="save-tree" value="true" />
        <?php if (!empty($tree->metadata_id)) { ?>
          <a href="<?php echo get_edit_post_link($tree->metadata_id); ?>" class="cluevo-btn edit-tree-metadata"><?php esc_html_e("Edit Post", "cluevo"); ?></a>
        <?php } ?>
        <a href="https://wp-lms.cluevo.at/pricing" target="_blank" class="cluevo-multi-tree-tease"><span class="dashicons dashicons-external"></span> <?php esc_attr_e("Get the Premium Bundle to create more than one tree", "cluevo"); ?></a>
      </div>
      <?php
      $tree = $tree->children;
      $tags = null;

      echo '<input type="hidden" name="lms-tree" value="" id="lms-tree" />';  // hidden field used to submit the course tree
      echo '<input type="hidden" name="lms-tree-flat" value="" id="lms-tree-flat" />';  // hidden field used to submit the course tree array
      echo '<input type="hidden" name="lms-tree-id" value="" id="lms-tree-id" />';  // hidden field used to submit the course tree array
      echo '<input type="hidden" name="lms-tree-name" value="" id="lms-tree-name" />';  // hidden field used to submit the course tree array

      echo '<script type="text/javascript">var modules = ' . json_encode($modules) . ';</script>';
      echo '<script type="text/javascript">var shortcode = "' . CLUEVO_SHORTCODE . '";</script>';
      echo '<div id="cluevo-module-selector"></div>';

      // hidden item template for js cloning
      cluevo_render_courses(new CluevoItem(), $modules, "item-tpl");

      // hidden interval meta fields for js cloning
      ?>
      <div class="meta-container repeating global template">
        <div class="label"><?php esc_html_e("Module has to be repeated periodically", "cluevo"); ?></div>
        <p class="help">
          <?php esc_html_e("Defines in what interval users have to repeat the module.", "cluevo"); ?>
        </p>
        <div class="meta-input-field-container">
          <input type="number" min="0" value="0" data-target="repeat-interval" />
          <select class="repeat-interval-type" data-target="repeat-interval-type">
            <?php foreach (CLUEVO_REPEAT_INTERVAL_TYPES as $key => $value) { ?>
              <option value="<?php echo esc_attr($key); ?>" <?php if ($key === 'day') echo 'selected="selected"'; ?>><?php echo esc_html($value); ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
  <?php

    $indexPage = cluevo_get_page_by_title('Index', OBJECT, CLUEVO_PAGE_POST_TYPE);
    $courses = $tree; //json_decode($opt);
    echo "<h2>" . esc_html($treeName) . "</h2>";

    echo "<div class=\"cluevo-admin-notice cluevo-notice-info\">";
    echo "<p>" . __("The startpage of the learning management system can be found here: ", "cluevo") . "<a target=\"_blank\" href=\"" . get_post_permalink($indexPage->ID) . "\">" . esc_attr__("CLUEVO LMS", "cluevo") . "</a></p>";
    echo "<p>" . sprintf(__("The learning tree can be displayed on any page with %s.", "cluevo"), "<code>[" . CLUEVO_SHORTCODE . " item=\"" . esc_html($treeIndex) . "\"]</code>") . "</p>";
    echo "<p>" . __("You can copy the shortcode to any item by clicking on the item's id or the [s] icon.", "cluevo") . "</p>";
    echo "</div>";
    if (empty($courses)) {
      cluevo_display_notice_html(
        __("Notice", "cluevo"),
        __("No courses have been created yet.", "cluevo"),
        "info"
      );
    }
    cluevo_display_notice_html(
      __("Filter", "cluevo"),
      '<div id="cluevo-tag-selector"></div>',
      'info'
    );
    cluevo_display_notice(
      __("Info", "cluevo"),
      __("Filter your tree items by name here, to filter by tags prefix your search query with '#'. To negate your filter prefix with either '-' or '!'.\nExample: to show all items tagged as 'archived' enter '#archived', to show all items not tagged as 'archived' enter '!#archived' or '-#archived'.\nTo show all elements containing the word 'test' simply type 'test', to find all elements that don't contain the word 'test' type '-test', or '!test'", "cluevo"),
      'info',
      'cluevo-tree-filter-hint'
    );

    echo '<ol id="level-1" class="sortable root course-structure" data-level="1" data-tree-id="' . esc_attr($treeIndex) . '" data-tree-name="' . esc_attr($treeName) . '">';
    if (!empty($courses) && is_array($courses)) {
      foreach ($courses as $key => $course) {
        cluevo_render_courses($course, $modules);
      }
    }
    echo "</ol>";
    echo "<div class=\"course-structure-buttons\">";
    $btnSize = (empty($courses)) ? "cluevo-inc-btn-size" : "";
    echo "<button class=\"cluevo-btn auto cluevo-btn-secondary add-course $btnSize\">" . esc_html__("Add Course", "cluevo") . "</button>";
    echo "</div>";
    echo "<hr />";
    echo "<div class=\"cluevo-course-structure-tools\">";
    echo '<div id="reset-dependencies" class="cluevo-btn reset-dependencies">' . esc_attr__("Reset Dependencies", "cluevo") . '</div>';
    echo '<div class="cluevo-btn cluevo-btn-primary save-tree cluevo-form-submit-btn">' . esc_attr__("Save", "cluevo") . '</div>';
    echo "</div>";
    echo '<input type="hidden" name="save-tree" value="true" />';
    echo "</form>";
  }
}
  ?>
