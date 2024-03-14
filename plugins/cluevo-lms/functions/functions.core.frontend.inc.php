<?php

/**
 * Register frontend scripts
 */
function cluevo_scorm_plugin_scripts()
{
  wp_enqueue_script('jquery');
  wp_register_script('cluevo-scorm-wrapper', plugins_url('/js/scorm_wrapper.js', plugin_dir_path(__FILE__)), array(), CLUEVO_VERSION, true);
  wp_register_script('cluevo-scorm-parms', plugins_url('/js/scorm-parms.js', plugin_dir_path(__FILE__)), array(), CLUEVO_VERSION, true);
  wp_register_script('cluevo-scorm', plugins_url('/js/cluevo.js', plugin_dir_path(__FILE__)), array('cluevo-lightbox'), CLUEVO_VERSION, true);
  wp_register_script('cluevo-lightbox', plugins_url('/js/cluevo-lightbox.js', plugin_dir_path(__FILE__)), array(), CLUEVO_VERSION, true);
  wp_localize_script(
    'cluevo-scorm',
    'cluevoWpApiSettings',
    [
      'root' => esc_url_raw(rest_url()),
      'nonce' => wp_create_nonce('wp_rest'),
      'commitInterval' => get_option('cluevo-module-commit-interval', 0),
      'displayGuestProgressNotification' => get_option('cluevo-module-guest-progress-notification', false),
      'commitOnCompletion' => !empty(get_option('cluevo-modules-scorm-completion-commit', false)) ? 1 : 0
    ]
  );  // scorm stuff is finally enqueued in the iframe template when it's needed
  $strings = array(
    "spinner_text" => __("Loading module, one moment please...", "cluevo"),
    "error_loading_module" => __("The module failed to load.", "cluevo"),
    "error_message_close" => __("Close", "cluevo"),
    "sco_select_title" => __("Please select a unit to start.", "cluevo"),
    "message_title_error" => __("Error", "cluevo"),
    "message_module_already_running" => __("A module has already been started.", "cluevo"),
    "message_title_access_denied" => __("Access Denied", "cluevo"),
    "message_access_denied" => __("You do not have the required permissions to access this item.", "cluevo"),
    "lms_connection_error" => __("Failed to establish a connection to the lms.", "cluevo"),
    "start_over_dialog_header" => __("You have saved progress, do you want to start a new attempt or resume the previous attempt?", "cluevo"),
    "start_over_opt_resume" => __("Resume", "cluevo"),
    "start_over_opt_reset" => __("New attempt", "cluevo"),
    "message_unknown_error" => __("An error has occured", "cluevo"),
    "message_commit_error" => __("An error occured while trying to save your progress", "cluevo"),
    "message_commit_no_item_id_error" => __("Something went wrong. This connection has no module id associated with it.", "cluevo"),
    "message_missing_dependencies" => __("The following items must be completed before this element can be accessed", "cluevo"),
    "message_level_required" => __("You must be at least level %d to access this item.", "cluevo"),
    "message_points_required" => __("You must have collected at least %d points to access this item.", "cluevo"),
    "message_your_level" => __("Your current level is %d.", "cluevo"),
    "message_your_points" => __("You currently have %d points.", "cluevo"),
    "rate" => esc_html__("How was this module?", "cluevo"),
    "working" => esc_html__("Thanks for your rating!", "cluevo")
  );

  wp_localize_script(
    'cluevo-scorm',
    'cluevoStrings',
    $strings
  );

  $ratings = (empty(get_option('cluevo-module-ratings', false))) ? false : true;
  $ratingsTrigger = get_option('cluevo-module-ratings-trigger', 'always');
  wp_localize_script(
    'cluevo-scorm',
    'cluevoSettings',
    ['ratingsEnabled' => $ratings, 'ratingsTrigger' => $ratingsTrigger]
  );

  wp_register_script('user-js', plugins_url('/js/user.js', plugin_dir_path(__FILE__)), array(), CLUEVO_VERSION, false);
  wp_enqueue_script('user-js');
  wp_register_script(
    'cluevo-frontend-js',
    plugins_url('/js/frontend.js', plugin_dir_path(__FILE__)),
    ["vue-3-js"],
    CLUEVO_VERSION,
    false
  );
  wp_localize_script(
    'cluevo-frontend-js',
    'cluevoStrings',
    $strings
  );
  wp_localize_script(
    'cluevo-frontend-js',
    'cluevoModuleRatings',
    [
      "ajax_url" => admin_url("admin-ajax.php"),
      "nonce" => wp_create_nonce("cluevo-module-rating"),
      "action" => "cluevo-rate-module",
    ]
  );
  wp_enqueue_script('cluevo-frontend-js');
  $vue = CLUEVO_DEV_MODE
    ? "/js/vue.global.js"
    : "/js/vue.global.prod.js";
  wp_register_script(
    "vue-3-js",
    plugins_url($vue, __DIR__),
    [],
    CLUEVO_VERSION,
    true
  );
  wp_enqueue_script("vue-3-js");
  wp_register_script('polygraph-js', plugins_url('/js/polygraph-view.js', plugin_dir_path(__FILE__)), ["vue-3-js"], CLUEVO_VERSION, true);
  wp_localize_script('polygraph-js', 'cluevoWpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));  // scorm stuff is finally enqueued in the iframe template when it's needed
  wp_enqueue_script('polygraph-js');
  wp_register_script('lodash-js', plugins_url('/js/lodash.min.js', plugin_dir_path(__FILE__)), null, false, false);  // utilities
  wp_enqueue_script('lodash-js');
  wp_add_inline_script('lodash-js', 'window.lodash = _.noConflict();', 'after'); // gutenberg compatibility
  wp_register_script(
    'cluevo-pdf',
    plugins_url('/js/pdf/pdf.js', plugin_dir_path(__FILE__)),
    null,
    CLUEVO_VERSION,
    false
  );
  wp_register_script(
    'cluevo-display-pdf',
    plugins_url('/js/display-pdf.js', plugin_dir_path(__FILE__)),
    ['cluevo-pdf'],
    CLUEVO_VERSION,
    true
  );
  wp_localize_script(
    'cluevo-display-pdf',
    'cluevoPdf',
    ['workerSrc' => plugins_url('/js/pdf/pdf.worker.js', plugin_dir_path(__FILE__))]
  );
}

/**
 * Register/Enqueue frontend styles
 */
function cluevo_enque_theme_files()
{
  wp_register_style('cluevo-templates-style', plugins_url('/styles/templates.css', plugin_dir_path(__FILE__)), array(), CLUEVO_VERSION);
  wp_enqueue_style('cluevo-templates-style');
  wp_enqueue_style(
    'fontawesome5',
    plugins_url('/styles/fontawesome5_all.css', plugin_dir_path(__FILE__)),
    array(),
    null
  );
}

function cluevo_remove_sidebar_class($class)
{
  $cur = get_post_type();
  $types = [CLUEVO_METADATA_POST_TYPE, CLUEVO_METADATA_POST_TYPE_COMPETENCE, CLUEVO_METADATA_POST_TYPE_SCORM_MODULE, CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA, CLUEVO_PAGE_POST_TYPE];
  if (in_array($cur, $types)) {
    $class = str_replace("has-sidebar", "", $class);
  }
  return $class;
}

function cluevo_load_frontend_dashicons()
{
  wp_enqueue_style('dashicons');
}

function cluevo_add_pdf_download_icon($item)
{
  if (empty($item)) {
    return;
  }
  if (empty($item->module)) {
    return;
  }
  if (empty($item->access)) {
    return;
  }
  if (!is_numeric($item->module)) {
    return;
  }
  if (empty($item->settings["allow-pdf-download"])) {
    return;
  }
  if (empty($item->iframe_index)) {
    return;
  }
  $module = cluevo_get_module($item->module);
  if (empty($module)) {
    return;
  }
  if ($module->type_name !== 'pdf') {
    return;
  }
  echo '<div class="cluevo-meta-item cluevo-pdf-download-link" data-href="' . esc_url($item->iframe_index) . '">';
  // echo '<span class="dashicons dashicons-download"></span>';
  echo '<i class="fas fa-download"></i>';
  echo '</div>';
}

add_action("cluevo_display_user_competence_tile", 'cluevo_display_competence_as_row', 10, 1);
add_action("cluevo_display_competence_tile", 'cluevo_display_competence_tile', 10, 1);
function cluevo_display_competence_tile($comp)
{
  $img = null;
  if (has_post_thumbnail($comp->metadata_id)) {
    $img = get_the_post_thumbnail($comp->metadata_id);
  }
  if (empty($img)) {
    $imgDir = cluevo_get_conf_const('CLUEVO_IMAGE_URL');
    $img = '<img src="' . "$imgDir/lms-content-placeholder.jpg" . '" alt="" />';
  }
?>
  <div class="cluevo-competence-container cluevo-content">
    <a class="cluevo-content-item-link" href="<?php echo get_permalink($comp->metadata_id); ?>">
      <div class="cluevo-post-thumb">
        <?php if (!empty($img)) {
          echo wp_kses($img, ["img" => ["src" => 1, "alt" => 1]]);
        } ?>
        <!-- <div class="skew-container"> -->
        <div class="cluevo-meta-bg">
          <div class="meta-bg-corner"></div>
        </div>
        <div class="cluevo-meta-container">
          <?php if (!empty($comp->modules) && count($comp->modules) > 0) { ?> <p class="cluevo-competence-toggle-modules">i</p> <?php
                                                                                                                              } ?>
        </div>
      </div>
      <div class="cluevo-content-container">
        <div class="cluevo-description">
          <?php echo esc_html($comp->competence_name); ?>
          <?php $tmpScore = (!empty($comp->score->value) && is_numeric($comp->score->value)) ? $comp->score->value : 0; ?>
          <p><?php echo esc_html(number_format_i18n((float)($tmpScore * 100)), 2) ?>% / <?php echo (is_array($comp->modules) ? count($comp->modules) : 0) . " " .  esc_html__("Modules", "cluevo"); ?></p>
        </div>
      </div>
      <div class="cluevo-progress-container">
        <span class="cluevo-progress" style="width: <?php echo esc_attr(100 - $tmpScore * 100); ?>%;" data-value="<?php echo esc_attr($tmpScore); ?>" data-max="1"></span>
      </div>
      <div class="cluevo-competence-modules">
        <?php if (is_user_logged_in()) { ?>
          <?php if (!empty($comp->score->modules)) { ?>
            <ul>
              <?php foreach ($comp->score->modules as $m) { ?>
                <li>
                  <div class="cluevo-competence-module-progress-container">
                    <p class="cluevo-comp-module-name"><?php echo esc_html($m->name); ?></p>
                    <p class="cluevo-comp-module-score"><?php echo esc_html(round($m->score * 100), 2); ?>% / <?php echo esc_html(round($m->coverage * 100)); ?>%</p>
                  </div>
                  <?php cluevo_display_progress_bar($m->score, $m->coverage); ?>
                </li>
              <?php } ?>
            </ul>
          <?php } else { ?>
          <?php } ?>
        <?php } else { ?>
          <?php if (!empty($comp->modules)) { ?>
            <ul class="cluevo-competence-module-guest">
              <?php foreach ($comp->modules as $m) { ?>
                <?php if (!empty(cluevo_can_user_see_module($m->module_id))) { ?>
                  <li>
                    <p><?php echo esc_html($m->module_name); ?></p>
                    <p><?php echo esc_html($m->competence_coverage * 100); ?>%</p>
                  </li>
                <?php } ?>
              <?php } ?>
            </ul>
          <?php } ?>
        <?php } ?>
      </div>
    </a>
  </div>
<?php
}

function cluevo_display_competence_as_row($comp)
{
  $img = null;
  if (has_post_thumbnail($comp->metadata_id)) {
    $img = get_the_post_thumbnail($comp->metadata_id);
  }
  if (empty($img)) {
    $imgDir = cluevo_get_conf_const('CLUEVO_IMAGE_URL');
    $img = '<img src="' . "$imgDir/lms-content-placeholder.jpg" . '" alt="" />';
  }
?>
  <div class="cluevo-competence-container-row cluevo-content">
    <a class="cluevo-content-item-link" href="<?php echo get_permalink($comp->metadata_id); ?>">
      <?php if (!empty($comp->modules) && count($comp->modules) > 0) { ?> <p class="cluevo-competence-toggle-modules">i</p> <?php
                                                                                                                          } ?>
      <div class="cluevo-post-thumb">
        <?php if (!empty($img)) {
          echo wp_kses($img, ["img" => ["src" => 1, "alt" => 1]]);
        } ?>
      </div>
      <div class="cluevo-content-container">
        <div class="cluevo-description">
          <p class="cluevo-comp-name"><?php echo esc_html($comp->competence_name); ?></p>
          <?php $tmpScore = (!empty($comp->score->value) && is_numeric($comp->score->value)) ? $comp->score->value : 0; ?>
          <p><?php echo esc_html(number_format_i18n((float)($tmpScore * 100)), 2) ?>% / <?php echo (is_array($comp->modules) ? count($comp->modules) : 0) . " " .  esc_html__("Modules", "cluevo"); ?></p>
        </div>
      </div>
    </a>
    <div class="cluevo-progress-container">
      <span class="cluevo-progress" style="width: <?php echo esc_attr(100 - $tmpScore * 100); ?>%;" data-value="<?php echo esc_attr($tmpScore); ?>" data-max="1"></span>
    </div>
    <div class="cluevo-competence-modules">
      <?php if (!empty($comp->score->modules)) { ?>
        <div class="cluevo-competence-modules-container">
          <h4 class="cluevo-competence-modules-headline"><?php esc_html_e("Modules covering this competence", "cluevo"); ?></h4>
          <ul>
            <?php foreach ($comp->score->modules as $m) { ?>
              <li>
                <div class="cluevo-competence-module-progress-container">
                  <p class="cluevo-comp-module-name"><?php echo esc_html($m->name); ?></p>
                  <p class="cluevo-comp-module-score"><?php echo esc_html(round($m->score * 100), 2); ?>% / <?php echo esc_html(round($m->coverage * 100)); ?>%</p>
                </div>
                <?php cluevo_display_progress_bar($m->score, $m->coverage); ?>
              </li>
            <?php } ?>
          </ul>
        </div>
      <?php } ?>
    </div>
  </div>
<?php
}

add_action("cluevo_display_competence_area_tile", 'cluevo_display_competence_area_tile', 10, 1);
function cluevo_display_competence_area_tile($area)
{
  $img = null;
  if (has_post_thumbnail($area->metadata_id)) {
    $img = get_the_post_thumbnail($area->metadata_id);
  }
  if (empty($img)) {
    $imgDir = cluevo_get_conf_const('CLUEVO_IMAGE_URL');
    $img = '<img src="' . "$imgDir/lms-content-placeholder.jpg" . '" alt="" />';
  }
?>
  <div class="cluevo-competence-container cluevo-content">
    <a class="cluevo-content-item-link" href="<?php echo get_permalink($area->metadata_id); ?>">
      <div class="cluevo-post-thumb">
        <?php if (!empty($img)) {
          echo wp_kses($img, ["img" => ["src" => 1, "alt" => 1]]);
        } ?>
        <!-- <div class="skew-container"> -->
        <div class="cluevo-meta-bg">
          <div class="meta-bg-corner"></div>
        </div>
        <div class="cluevo-meta-container">
          <?php if (!empty($area->competences)) { ?> <p class="cluevo-competence-toggle-modules">i</p> <?php
                                                                                                      } ?>
        </div>
      </div>
      <div class="cluevo-content-container">
        <div class="cluevo-description">
          <?php echo esc_html($area->competence_area_name); ?>
          <?php $tmpScore = (!empty($area->score) && is_numeric($area->score)) ? $area->score : 0; ?>
          <p><?php echo esc_html(number_format_i18n((float)($tmpScore * 100)), 2) ?>% / <?php echo (is_array($area->competences) ? count($area->competences) : 0) . " " .  esc_html__("Competences", "cluevo"); ?></p>
        </div>
      </div>
      <div class="cluevo-progress-container">
        <span class="cluevo-progress" style="width: <?php echo esc_attr(100 - $tmpScore * 100); ?>%;" data-value="<?php echo esc_attr($tmpScore); ?>" data-max="1"></span>
      </div>
      <div class="cluevo-competence-modules">
        <?php if (!empty($area->competences)) { ?>
          <ul>
            <?php foreach ($area->competences as $c) { ?>
              <li>
                <div class="cluevo-competence-module-progress-container">
                  <p class="cluevo-comp-module-name"><?php echo esc_html($c->competence_name); ?></p>
                  <p class="cluevo-comp-module-score"><?php echo esc_html((!empty($c->score->value)) ? round($c->score->value * 100, 2) : 0); ?>% / 100%</p>
                </div>
                <?php cluevo_display_progress_bar((!empty($c->score->value)) ? $c->score->value : 0, 1); ?>
              </li>
            <?php } ?>
          </ul>
        <?php } ?>
      </div>
    </a>
  </div>
  <?php
}

add_action("cluevo_display_module_start_link", "cluevo_display_module_start_link", 10, 2);
function cluevo_display_module_start_link($item, $strText = '')
{
  if ($item->access_level == 0) {
    return;
  }
  $blocked = ($item->access_level < 2 || !$item->access) ? "blocked" : "";
  $blockAnyway = false;
  if (!empty($item->children)) {
    $blockAnyway = true;
    foreach ($item->children as $c) {
      if (!empty($c->access)) {
        $blockAnyway = false;
        break;
      }
    }
  }
  $module = null;
  if (!empty($item->module) && $item->module > 0) {
    if ($item->module) {
      $module = cluevo_get_module($item->module);
      do_action('cluevo_enqueue_module_scripts');
    }
  }
  $data = [];
  foreach ($item->settings as $key => $value) {
    if (is_array($value) && count($value) == 1) {
      $value = maybe_unserialize($value[0]);
    } else {
      $value = maybe_unserialize($value);
    }
    if (!empty($value)) {
      if (!is_string($value)) {
        $value = json_encode($value);
      }
      $key = str_replace(CLUEVO_META_DATA_PREFIX, '', $key);
      $key = str_replace('_', '-', $key);
      $data[] = "data-" . sanitize_key($key) . "='" . esc_attr($value) . "'";
    }
  }

  $missing = [];
  if (!$item->access) {
    if (!$item->access_status["dependencies"]) {
      foreach ($item->dependencies as $type => $deps) {
        foreach ($deps->all as $dep => $value) {
          if (empty($value) || !$value) {
            if ($type == "modules") {
              $page = cluevo_get_module_metadata_page($dep);
              $items = cluevo_get_modules_items($dep, true, true);
              if (!empty($items)) {
                foreach ($items as $el) {
                  if ($el->access_status["access_level"] > 1) {
                    continue;
                  }
                  $elPage = cluevo_get_metadata_page($el);
                  $elTitle = __("Module: ", "cluevo") . $page->post_title . " " . __("in", "cluevo") . " " . $elPage->post_title;
                  $missing[] = ["title" => $elTitle, "url" => get_permalink($elPage)];
                }
              }
            } else {
              $page = cluevo_get_metadata_page($dep);
              $missing[] = ["title" => $page->post_title, "url" => get_permalink($page)];
            }
          }
        }
      }
    }
  }
  $displayMode = cluevo_get_the_items_module_display_mode($item->item_id);
  $hideCloseButton = '';
  if ($item->get_setting('hide-lightbox-close-button') == 1) {
    $hideCloseButton =  'data-hide-lightbox-close-button="1"';
  }
  $out = '<a class="cluevo-content-item-link cluevo-module-link ';
  if (empty($strText)) {
    $out .= ' cluevo-start-button-no-label ';
  }
  $out .= esc_attr($item->type);
  $out .= esc_attr($blocked) . ' ';
  $out .= 'cluevo-module-mode-' . esc_attr($displayMode) . '" ';
  $out .= $hideCloseButton . ' ';
  $out .= 'href="' . esc_attr($item->permalink) . '" ';
  $out .= 'data-item-id="' . esc_attr($item->item_id) . '" ';
  $out .= cluevo_get_item_data_string($item) . ' ';
  $out .= 'data-module-id="' . ((!empty($item->module_id)) ? $item->module_id : 0) . '" ';
  $type = (!empty($module->type_name)) ? strtolower($module->type_name) : "";
  $out .= 'data-module-type="' . esc_attr($type) . '">';
  if (!empty($strText)) {
    $out .= esc_html($strText);
  }
  $out .= '<div class="cluevo-progress-container">';
  if (is_user_logged_in()) {
    $score = (float)cluevo_get_users_best_module_attempt(get_current_user_id(), $item->module_id);
  } else {
    $score = 0;
  }
  $out .= '<span class="cluevo-progress" style="width: ' . esc_attr(100 - $score * 100) . '%;" data-value="' . esc_attr($score) . '" data-max="1"></span>';
  $out .= '</div>';
  $out .= '</a>';
  if (!empty($missing)) {
    $out .= '<script type="application/json" id="cluevo-missing-dependencies-"' . esc_attr($item->item_id) . '">';
    $out .= json_encode($missing);
    $out .= '</script>';
  }
  echo $out;
}

add_action("cluevo_display_detailed_module_start_link", "cluevo_display_detailed_module_start_link", 10, 2);
function cluevo_display_detailed_module_start_link($item, $strText = '')
{
  if ($item->access_level == 0) {
    return;
  }
  $blocked = ($item->access_level < 2 || !$item->access) ? "blocked" : "";
  $blockAnyway = false;
  if (!empty($item->children)) {
    $blockAnyway = true;
    foreach ($item->children as $c) {
      if (!empty($c->access)) {
        $blockAnyway = false;
        break;
      }
    }
  }
  $module = null;
  if (!empty($item->module) && $item->module > 0) {
    if ($item->module) {
      $module = cluevo_get_module($item->module);
      do_action('cluevo_enqueue_module_scripts');
    }
  }
  $data = [];
  foreach ($item->settings as $key => $value) {
    if (is_array($value) && count($value) == 1) {
      $value = maybe_unserialize($value[0]);
    } else {
      $value = maybe_unserialize($value);
    }
    if (!empty($value)) {
      if (!is_string($value)) {
        $value = json_encode($value);
      }
      $key = str_replace(CLUEVO_META_DATA_PREFIX, '', $key);
      $key = str_replace('_', '-', $key);
      $data[] = "data-" . sanitize_key($key) . "='" . esc_attr($value) . "'";
    }
  }

  $missing = [];
  if (!$item->access) {
    if (!$item->access_status["dependencies"]) {
      foreach ($item->dependencies as $type => $deps) {
        foreach ($deps->all as $dep => $value) {
          if (empty($value) || !$value) {
            if ($type == "modules") {
              $page = cluevo_get_module_metadata_page($dep);
              $items = cluevo_get_modules_items($dep, true, true);
              if (!empty($items)) {
                foreach ($items as $el) {
                  if ($el->access_status["access_level"] > 1) {
                    continue;
                  }
                  $elPage = cluevo_get_metadata_page($el);
                  $elTitle = __("Module: ", "cluevo") . $page->post_title . " " . __("in", "cluevo") . " " . $elPage->post_title;
                  $missing[] = ["title" => $elTitle, "url" => get_permalink($elPage)];
                }
              }
            } else {
              $page = cluevo_get_metadata_page($dep);
              $missing[] = ["title" => $page->post_title, "url" => get_permalink($page)];
            }
          }
        }
      }
    }
  }
  $displayMode = cluevo_get_the_items_module_display_mode($item->item_id);
  $hideCloseButton = '';
  if ($item->get_setting('hide-lightbox-close-button') == 1) {
    $hideCloseButton =  'data-hide-lightbox-close-button="1"';
  }
  $out = '<a class="cluevo-content-item-link cluevo-module-link ';
  if (empty($strText)) {
    $out .= ' cluevo-start-button-no-label ';
  }
  $out .= esc_attr($item->type);
  $out .= esc_attr($blocked) . ' ';
  $out .= 'cluevo-module-mode-' . esc_attr($displayMode) . '" ';
  $out .= $hideCloseButton . ' ';
  $out .= 'href="' . esc_attr($item->permalink) . '" ';
  $out .= 'data-item-id="' . esc_attr($item->item_id) . '" ';
  $out .= cluevo_get_item_data_string($item) . ' ';
  $out .= 'data-module-id="' . ((!empty($item->module_id)) ? $item->module_id : 0) . '" ';
  $type = (!empty($module->type_name)) ? strtolower($module->type_name) : "";
  $out .= 'data-module-type="' . esc_attr($type) . '">';
  if (!empty($strText)) {
    $out .= '<div class="cluevo-label">';
    $out .= esc_html($strText);
    $out .= '</div>';
  }
  $out .= '<div class="cluevo-progress-container">';
  $score = (float)cluevo_get_users_best_module_attempt(get_current_user_id(), $item->module_id);
  $out .= '<span class="cluevo-progress" style="width: ' . esc_attr(100 - $score * 100) . '%;" data-value="' . esc_attr($score) . '" data-max="1"></span>';
  $out .= '</div>';
  $out .= '</a>';
  if (!empty($missing)) {
    $out .= '<script type="application/json" id="cluevo-missing-dependencies-"' . esc_attr($item->item_id) . '">';
    $out .= json_encode($missing);
    $out .= '</script>';
  }
  echo $out;
}

add_action("cluevo_missing_dependencies_json", "cluevo_output_missing_dependencies_json", 10, 1);
function cluevo_output_missing_dependencies_json($item)
{
  $missing = [];
  if (!$item->access) {
    if (!$item->access_status["dependencies"]) {
      foreach ($item->dependencies as $type => $deps) {
        foreach ($deps->all as $dep => $value) {
          if (empty($value) || !$value) {
            if ($type == "modules") {
              $page = cluevo_get_module_metadata_page($dep);
              $items = cluevo_get_modules_items($dep, true, true);
              if (!empty($items)) {
                foreach ($items as $el) {
                  $depItem = cluevo_get_learning_structure_item($el);
                  if (empty($depItem)) {
                    continue;
                  }
                  if ($depItem->access_status["access_level"] < 1) {
                    continue;
                  }
                  $elPage = cluevo_get_metadata_page($el);
                  $elTitle = __("Module: ", "cluevo") . $page->post_title . " " . __("in", "cluevo") . " " . $elPage->post_title;
                  $missing[] = ["title" => $elTitle, "url" => get_permalink($elPage), "module" => $page->post_title, "access" => $depItem->access];
                }
              }
            } else {
              $page = cluevo_get_metadata_page($dep);
              $depItem = cluevo_get_learning_structure_item($dep);
              $text = implode(" > ", $depItem->path->string);
              $missing[] = ["title" => $text, "url" => get_permalink($page), "item" => $depItem->name, "access" => $depItem->access];
            }
          }
        }
      }
    }
  }
  if (!empty($missing)) { ?>
    <script type="application/json" id="cluevo-missing-dependencies-<?php echo esc_attr($item->item_id); ?>">
      <?php echo json_encode($missing); ?>
    </script>
<?php
  }
}

add_action("cluevo_module_page_list_items", "cluevo_display_module_page_items", 10, 1);
function cluevo_display_module_page_items($module)
{
  $items = cluevo_get_modules_items($module->module_id, true, true);
  if (empty($items)) {
    cluevo_display_notice(__("Info", "cluevo"), __("This module is not assigned to any element and so is not available to open", "cluevo"), "warning");
    return;
  }
  $out = '<div class="cluevo-module-items-list">';
  foreach ($items as $itemId) {
    $item = cluevo_get_learning_structure_item($itemId);
    if (empty($item)) {
      continue;
    }
    $out .= '<div class="cluevo-module-item">';
    $img = null;
    $meta = cluevo_get_metadata_page($itemId);
    if (!empty($meta)) {
      if (has_post_thumbnail($meta->ID)) {
        $img = get_the_post_thumbnail($meta->ID);
      }
    }

    if (empty($img)) {
      $imgDir = cluevo_get_conf_const('CLUEVO_IMAGE_URL');
      $img = '<img src="' . "$imgDir/lms-content-placeholder.jpg" . '" alt="" />';
    }
    $out .= $img;
    $out .= '<div class="cluevo-module-item-data">';
    $out .= '<a href="' . esc_url(cluevo_get_parent_permalink($item)) . '">';
    $out .= esc_html($item->name) . '</a>';
    $out .= '<div class="cluevo-path">';
    if (!empty($item->path->string)) {
      foreach ($item->path->string as $part) {
        $out .= '<div class="cluevo-path-part">' . esc_html($part) . '</div>';
      }
    } else {
      $out .= "&nbsp;";
    }
    $out .= '</div>';
    $out .= '<div class="cluevo-module-progress-container">';
    $out .= cluevo_get_progress_bar(count($item->completed_children), count($item->children));
    $out .= '</div>';
    $out .= '</div>';
    $out .= '</div>';
  }
  $out .= '</div>';
  echo $out;
}

add_filter('the_content', 'cluevo_render_lms_page_contents');
function cluevo_render_lms_page_contents($content)
{
  $curPage = get_the_ID();
  $indexPage = get_option("cluevo-tree-index-page", null);
  if (empty($indexPage)) {
    return $content;
  }
  if ($indexPage != $curPage) {
    return $content;
  }
  ob_start();
  cluevo_display_template('cluevo-course-index');
  $cluevoContent = ob_get_clean();

  $opt = get_option("cluevo-tree-index-page-content-mode", "replace");
  switch ($opt) {
    case "before":
      $content = $cluevoContent . $content;
      break;
    case "after":
      $content .= $cluevoContent;
      break;
    case "replace":
    default:
      $content = $cluevoContent;
  }
  do_action("cluevo_display_page", "index");
  do_action("cluevo_display_page_index");
  return $content;
}

add_filter('the_content', 'cluevo_render_user_profile_contents');
function cluevo_render_user_profile_contents($content)
{
  $curPage = get_the_ID();
  $userPage = get_option("cluevo-user-profile-page", null);
  if (empty($userPage)) {
    return $content;
  }
  if ($userPage != $curPage) {
    return $content;
  }
  ob_start();
  cluevo_display_template('content-user-profile');
  $cluevoContent = ob_get_clean();

  $opt = get_option("cluevo-user-profile-page-content-mode", "replace");
  switch ($opt) {
    case "before":
      $content = $cluevoContent . $content;
      break;
    case "after":
      $content .= $cluevoContent;
      break;
    case "replace":
    default:
      $content = $cluevoContent;
  }
  do_action("cluevo_display_page", "user_profile");
  do_action("cluevo_display_page_user_profile");
  return $content;
}

add_filter('the_content', 'cluevo_render_login_page_contents');
function cluevo_render_login_page_contents($content)
{
  $curPage = get_the_ID();
  $loginPage = get_option("cluevo-login-page", null);
  $enabled = get_option("cluevo-login-enabled", "");
  if (empty($enabled)) {
    return $content;
  }
  if (empty($loginPage)) {
    return $content;
  }
  if ($loginPage != $curPage) {
    return $content;
  }
  ob_start();
  cluevo_display_template('part-login-form');
  $cluevoContent = ob_get_clean();

  $opt = get_option("cluevo-login-page-content-mode", "replace");
  switch ($opt) {
    case "before":
      $content = $cluevoContent . $content;
      break;
    case "after":
      $content .= $cluevoContent;
      break;
    case "replace":
    default:
      $content = $cluevoContent;
  }
  do_action("cluevo_display_page", "login");
  do_action("cluevo_display_page_login");
  return $content;
}
