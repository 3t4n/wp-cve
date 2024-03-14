<?php

/**
 * Register metadata post type
 *
 */
function cluevo_create_metadata_post_type()
{
  register_post_type(
    CLUEVO_METADATA_POST_TYPE_COMPETENCE,
    array(
      'labels' => array(
        'name' => __('CLUEVO Competence Posts', "cluevo"),
        'singular_name' => __('CLUEVO Competence Post', "cluevo")
      ),
      'public' => true,
      'has_archive' => false,
      'hierarchical' => true,
      'show_in_menu' => false, // CLUEVO_ADMIN_PAGE_LEARNING_STRUCTURE,
      'show_in_rest' => true,
      'supports' => ['title', 'editor', 'revisions', 'excerpt', 'thumbnail', 'comments'],
      'rewrite' => ['slug' => __('cluevo/competence', "cluevo")],
      "taxonomies" => ["post_tag"]
    )
  );

  register_post_type(
    CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA,
    array(
      'labels' => array(
        'name' => __('CLUEVO Competence Group Posts', "cluevo"),
        'singular_name' => __('CLUEVO Competence Group Post', "cluevo")
      ),
      'public' => true,
      'has_archive' => false,
      'hierarchical' => true,
      'show_in_menu' => false, // CLUEVO_ADMIN_PAGE_LEARNING_STRUCTURE,
      'show_in_rest' => true,
      'supports' => ['title', 'editor', 'revisions', 'excerpt', 'thumbnail', 'comments'],
      'rewrite' => ['slug' => __('cluevo/competence-group', "cluevo")],
      "taxonomies" => ["post_tag"]
    )
  );

  register_post_type(
    CLUEVO_METADATA_POST_TYPE_SCORM_MODULE,
    array(
      'labels' => array(
        'name' => __('CLUEVO SCORM Module Posts', "cluevo"),
        'singular_name' => __('CLUEVO SCORM Module Post', "cluevo")
      ),
      'public' => true,
      'has_archive' => false,
      'hierarchical' => true,
      'show_in_menu' => false, // CLUEVO_ADMIN_PAGE_LEARNING_STRUCTURE,
      'show_in_nav_menus' => false,
      'show_in_rest' => true,
      'supports' => ['title', 'editor', 'revisions', 'excerpt', 'thumbnail', 'comments'],
      'rewrite' => ['slug' => __('cluevo/scorm', "cluevo")],
      "taxonomies" => ["post_tag"]
    )
  );

  return register_post_type(
    CLUEVO_METADATA_POST_TYPE,
    array(
      'labels' => array(
        'name' => __('CLUEVO LMS Posts', "cluevo"),
        'singular_name' => __('CLUEVO LMS Post', "cluevo")
      ),
      'public' => true,
      'has_archive' => false,
      'hierarchical' => true,
      'show_in_menu' => false, // CLUEVO_ADMIN_PAGE_LEARNING_STRUCTURE,
      //'show_in_menu' => CLUEVO_ADMIN_PAGE_LEARNING_STRUCTURE,
      'show_in_rest' => true,
      'supports' => ['title', 'editor', 'revisions', 'excerpt', 'thumbnail', 'comments'],
      'rewrite' => ['slug' => 'cluevo/lms'],
      "taxonomies" => ["post_tag"]
    )
  );
}

/**
 * Register cluevo shortcode
 *
 * @param mixed $atts
 */
function cluevo_add_shortcode($atts, $content)
{
  if (!empty($GLOBALS["cluevo"])) {
    $prevState = $GLOBALS["cluevo"];
  }
  if (is_array($atts)) {
    if (array_key_exists("item", $atts)) {
      $item = cluevo_get_learning_structure_item($atts["item"], get_current_user_id());
      if ($item !== false) { // display item if it exists
        $displayMode = strtolower(get_option("cluevo-modules-display-mode", "Iframe"));
        if ($displayMode == "popup" || $displayMode == "lightbox") {
          do_action('cluevo_enqueue_module_scripts', 'cluevo_enqueue_module_scripts');
        }
        if (!empty($item->module) && $item->module > 0) {
          $lms = new Cluevo(null, get_current_user_id());
          $lms->shortcode = true;
          $lms->items = [$item];
          $lms->item = $item;
          $lms->item_count = 1;
          $lms->current_item = 0;
          $lms->current_page = $item;
          $lms->shortcode_content = $content;
          $GLOBALS["cluevo"] = $lms;
          $out = '';
          ob_start();
          if (!empty($content)) {
            cluevo_display_template("cluevo-tree-item-shortcode-link");
          } else {
            if (in_array("tile", $atts)) {
              cluevo_display_template("part-tree-item");
            } else {
              if (in_array("row", $atts)) {
                echo "<div class=\"cluevo-content-list-style-row\">";
                cluevo_display_template("part-tree-item");
                echo "</div>";
              } else {
                cluevo_display_template("cluevo-tree-item-module-shortcode");
              }
            }
          }
          $out = ob_get_clean();
        } else { // if current item is not a module list children
          $lms = new Cluevo($atts["item"], get_current_user_id());
          $lms->shortcode = true;
          $GLOBALS['cluevo'] = $lms;
          $out = '';
          ob_start();
          if (in_array("single", $atts)) {
            $itemTpl = "part-tree-item";
            $lms->item = $item;
          } else {
            $itemTpl = "cluevo-tree-item-bare";
          }
          if (in_array("row", $atts)) {
            echo "<div class=\"cluevo-content-list-style-row\">";
            cluevo_display_template($itemTpl);
            echo "</div>";
          } else {
            cluevo_display_template($itemTpl);
          }
          $out = ob_get_clean();
        }
      } else {
        ob_start();
        cluevo_display_template("cluevo-item-not-found");
        $out = ob_get_clean();
      }
    } else {  // if the shortcode has no item arg display the course index
      $lms = new Cluevo(null, get_current_user_id());
      $lms->shortcode = true;
      $GLOBALS['cluevo'] = $lms;
      $out = '';
      ob_start();
      cluevo_display_template('cluevo-shortcode-tree-index');
      $out = ob_get_clean();
    }
  } else {  // if the shortcode has no arguments display the course index
    $lms = new Cluevo(null, get_current_user_id());
    $lms->shortcode = true;
    $GLOBALS['cluevo'] = $lms;
    $out = '';
    ob_start();
    cluevo_display_template('cluevo-shortcode-tree-index');
    $out = ob_get_clean();
  }

  if (!empty($prevState)) {
    $GLOBALS["cluevo"] = $prevState;
  }

  return $out;
}

function cluevo_add_toc_shortcode($atts, $content)
{
  $out = '';
  $outItems = '';
  $itemId = (!empty($atts["id"]) && is_numeric($atts["id"]))
    ? (int)$atts["id"]
    : null;
  $lms = new Cluevo($itemId, get_current_user_id());
  $lms->shortcode = true;
  $lms->shortcode_content = $content;
  $lms->shortcode_atts = $atts;

  if (!empty($GLOBALS["cluevo"])) {
    $prevState = $GLOBALS["cluevo"];
  }

  $GLOBALS["cluevo"] = $lms;
  $toc = cluevo_get_template("cluevo-toc");
  ob_start();
  include($toc);
  $outItems .= ob_get_clean();
  $out .= $outItems;

  if (!empty($prevState)) {
    $GLOBALS["cluevo"] = $prevState;
  }

  return $out;
}

function cluevo_add_guideline_shortcode($atts, $content)
{
  $items = [];
  if (!empty($atts["items"])) {
    $parts = explode(",", $atts["items"]);
    $items = array_map(function ($el) {
      return trim($el);
    }, $parts);
  }
  if (empty($items)) return;
  $list = [];
  foreach ($items as $item) {
    $lmsItem = cluevo_get_learning_structure_item($item, get_current_user_id());
    if (!empty($lmsItem)) {
      $list[] = $lmsItem;
    }
  }
  $out = '';
  $outItems = '';

  $lms = new Cluevo(null, get_current_user_id());
  $lms->items = $list;
  $lms->shortcode = true;
  $lms->shortcode_content = $content;
  $lms->shortcode_atts = $atts;
  $prevState = null;
  if (!empty($GLOBALS["cluevo"])) {
    $prevState = $GLOBALS["cluevo"];
  }
  $GLOBALS["cluevo"] = $lms;
  $guideline = cluevo_get_template("cluevo-guideline");
  ob_start();
  include($guideline);
  $outItems .= ob_get_clean();
  $out .= $outItems;

  if (!empty($prevState)) {
    $GLOBALS["cluevo"] = $prevState;
  }

  return $out;
}

function cluevo_add_user_widget_shortcode($atts, $content)
{
  ob_start();
  $tpl = cluevo_get_template('part-exp-title');
  include($tpl);
  $out = ob_get_clean();
  return $out;
}

/**
 * Initializes metadata taxonomy
 *
 */
function cluevo_meta_taxonomy_init()
{
  // create a new taxonomy
  $regResult = register_taxonomy(
    CLUEVO_TAXONOMY,
    CLUEVO_METADATA_POST_TYPE,
    array(
      'label' => __('CLUEVO Content', "cluevo"),
      'rewrite' => array('slug' => 'cluevo-content'),
      'show_in_rest' => true,
      'public' => false,
      'hierarchical' => true
    )
  );
  $result = register_taxonomy_for_object_type('CLUEVO', CLUEVO_METADATA_POST_TYPE);

  return $result;
}

/**
 * Register lms pages post type
 *
 */
function cluevo_create_lms_page_post_type()
{
  return register_post_type(
    CLUEVO_PAGE_POST_TYPE,
    array(
      'labels' => array(
        'name' => __('CLUEVO Pages', "cluevo"),
        'singular_name' => __('CLUEVO Page', "cluevo")
      ),
      'public' => true,
      'has_archive' => false,
      'hierarchical' => false,
      'show_in_menu' => '', // don't show in admin menu
      'show_in_nav_menus' => true,
      'supports' => ['title', 'thumbnail'],
      'rewrite' => ['slug' => 'cluevo/pages']
    )
  );
}

function cluevo_get_template($strName)
{
  $template = null;
  if (($tpl = locate_template(array(CLUEVO_THEME_TPL_PATH . '/' . $strName . '.php'))) != '') {
    $template = $tpl;
  } else {
    if (file_exists(cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/' . $strName . '.php')) {
      $template = cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/' . $strName . '.php';
    }
  }
  return $template;
}


/**
 * Display plugin page templates
 *
 * @param mixed $page_template
 */
function cluevo_page_template($page_template)
{
  if (get_post_type() === CLUEVO_PAGE_POST_TYPE) {
    $tpl = null;
    $title = sanitize_title(get_the_title());
    foreach (CLUEVO_PAGES as $page) {
      $page = sanitize_title($page);
      if (strtolower($title) === $page) {
        $tpl = $page;
        break;
      }
    }
    if (!empty($page)) {
      if (($tpl = locate_template(array(CLUEVO_THEME_TPL_PATH . '/page-' . $page . '.php'))) != '') {
        $page_template = $tpl;
      } else {
        if (file_exists(cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/page-' . $page . '.php')) {
          $page_template = cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/page-' . $page . '.php';
        }
      }
    }
  } else {
    if (get_post_type() === CLUEVO_METADATA_POST_TYPE) {
      $treeId = get_post_meta(get_the_ID(), CLUEVO_METADATA_KEY, true);
      $type = get_post_meta(get_the_ID(), CLUEVO_METADATA_TYPE, true);
      $parent = wp_get_post_parent_id(get_the_ID());
      if ($type !== 'module' || !empty($parent)) {
        if (($tpl = locate_template(array(CLUEVO_THEME_TPL_PATH . '/content-' . get_post_type() . '.php'))) != '') {
          $page_template = $tpl;
        } else {
          if (file_exists(cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-' . get_post_type() . '.php')) {
            $page_template = cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-' . get_post_type() . '.php';
          }
        }
      } else {
        if (file_exists(cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-' . get_post_type() . '-module.php')) {
          $page_template = cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-' . get_post_type() . '-module.php';
        } else {
          if (file_exists(cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-' . get_post_type() . '.php')) {
            $page_template = cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-' . get_post_type() . '.php';
          }
        }
      }
    } else if (get_post_type() === CLUEVO_METADATA_POST_TYPE_COMPETENCE) {
      if (file_exists(cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-' . get_post_type() . '.php')) {
        $page_template = cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-' . get_post_type() . '.php';
      }
    } else if (get_post_type() === CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA) {
      if (file_exists(cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-' . CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA . '.php')) {
        $page_template = cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-' . CLUEVO_METADATA_POST_TYPE_COMPETENCE_AREA . '.php';
      }
    } else if (get_post_type() === CLUEVO_METADATA_POST_TYPE_SCORM_MODULE) {
      if (file_exists(cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-module-post.php')) {
        $page_template = cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . 'templates/content-module-post.php';
      }
    }
  }

  return $page_template;
}

function cluevo_display_media_module($args)
{
  $module = $args["module"];
  $item = $args["item"];
  $valid = [
    strtolower(__('Video', "cluevo")),
    strtolower(__("Audio", "cluevo")),
    "audio",
    "video"
  ];

  if (!empty($module)) {
    if (in_array($module->type_name, $valid)) {
      $out = '<video class="cluevo-media-module ' . esc_attr($module->type_name) . '" controls src="' . esc_attr($item->iframe_index) . '" data-module-id="' . esc_attr($module->module_id) . '"></video>';
      $out = apply_filters('cluevo_filter_module_embed', $out);
      echo wp_kses($out, wp_kses_allowed_html("post"));
    }
  }
}

function cluevo_display_pdf_module($args)
{
  $module = $args["module"];
  $item = $args["item"];
  $valid = [
    strtolower(__('pdf', "cluevo")),
    "pdf",
  ];

  wp_enqueue_script('cluevo-pdf');
  wp_enqueue_script('cluevo-display-pdf');

  if (!empty($module)) {
    if (in_array($module->type_name, $valid)) {
      $out = '<div class="cluevo-pdf-target-container" class="cluevo-pdf-module ' . esc_attr($module->type_name) . '" data-document="' . esc_attr($item->iframe_index) . '" data-module-id="' . esc_attr($module->module_id) . '" data-item-id="' . esc_attr($item->item_id) . '" data-module-type="pdf"></div>';
      $out = apply_filters('cluevo_filter_module_embed', $out);
      echo wp_kses($out, wp_kses_allowed_html("post"));
    }
  }
}

function cluevo_display_scorm_module($args)
{
  $module = $args["module"];
  $item = $args["item"];
  $valid = [
    strtolower(__('SCORM 2004', "cluevo")),
    "scorm-2004",
    "scorm 2004",
    "scorm"
  ];

  if (!empty($module)) {
    if (in_array($module->type_name, $valid)) {
      $dir = cluevo_get_conf_const('CLUEVO_ABS_MODULE_PATH') . $module->module_dir . "/";
      $scos = cluevo_find_scos($dir);
      $scoSelect = '<div class="cluevo-scorm-module-iframe-container">';
      $src = esc_attr($item->iframe_index);
      if (count($scos) > 1) {
        $src = '';
        $scoSelect .= '<div class="cluevo-sco-select"><label for="sco-select">' . esc_html__("Please select a unit", "cluevo") . '<select size="1" class="iframe-sco-select">';
        $scoSelect .= '<option value="0">' . esc_html__("Please select a unit", "cluevo") . '</option>';
        foreach ($scos as $key => $sco) {
          $href = cluevo_get_conf_const('CLUEVO_MODULE_URL') . $module->module_dir . "/" . $sco["href"];
          $scoSelect .= '<option value="' . esc_url($href) . '">' . esc_html($sco["title"]) . '</option>';
        }
        $scoSelect .= '</select></label></div>';
      }
      $out = $scoSelect . '<iframe id="cluevo-module-iframe" data-module-id="' . esc_attr($module->module_id) . '" data-item-id="' . esc_attr($item->item_id) . '" data-src="' . $src . '"></iframe></div>';
      $out = apply_filters('cluevo_filter_module_embed', $out);
      $allowed = wp_kses_allowed_html("post");
      $allowed["iframe"] = [
        "id" => 1,
        "data-*" => 1
      ];
      echo wp_kses($out, $allowed);
    }
  }
}

function cluevo_enqueue_module_scripts()
{
  wp_enqueue_script('cluevo-scorm-wrapper');
  wp_enqueue_script('cluevo-scorm-parms');
  wp_enqueue_script('cluevo-lightbox');
  wp_enqueue_script('cluevo-scorm');
  wp_enqueue_script('cluevo-pdf');
  wp_enqueue_script('cluevo-display-pdf');
}

function cluevo_path_join()
{
  $args = func_get_args();
  $paths = array();
  foreach ($args as $arg) {
    $paths = array_merge($paths, (array)$arg);
  }

  $paths = array_map(function ($p) {
    return trim($p, "/");
  }, $paths);
  $paths = array_filter($paths);
  return '/' . join('/', $paths);
}

function cluevo_display_module_icon($module)
{
  echo '<img title="' . esc_attr(ucfirst($module->type_name)) . '" src="' . esc_attr(cluevo_get_conf_const('CLUEVO_IMAGE_URL') . "icon-" . sanitize_title($module->type_name) . ".svg") . '" />';
}

function cluevo_create_tag_string($mixedTags = [])
{
  $tags = null;
  if (is_array($mixedTags)) {
    $clean = [];
    foreach ($mixedTags as $t) {
      $t = trim($t);
      if (!empty($t) && !in_array($t, $clean)) $clean[] = $t;
    }
    $tags = implode(",", $clean);
  } else {
    if (!empty($mixedTags)) {
      $arr = explode(",", $mixedTags);
      $clean = [];
      foreach ($arr as $t) {
        $t = trim($t);
        if (!empty($t) && !in_array($t, $clean)) $clean[] = $t;
      }
      $tags = implode(",", $clean);
    }
  }
  return $tags;
}

function cluevo_redir_single_tree_index()
{
  global $post;
  if (empty($post)) return;
  $id = (int)get_option("cluevo-tree-index-page", null);
  $indexPage = null;
  if (!empty($id)) {
    $indexPage = get_post($id);
    $id = empty($indexPage) ? null : $id;
  }
  if (!empty($id) && $id !== $post->ID) return;
  if ((empty($id) || $id !== $post->ID) && $post->post_type !== CLUEVO_PAGE_POST_TYPE) return;
  if ((empty($id) || $id !== $post->ID) && $post->post_name !== 'index') return;

  $redirEnabled = get_option("cluevo-redirect-single-tree-index", false);
  if (empty($redirEnabled)) return;

  $trees = cluevo_turbo_get_trees();

  if (empty($trees)) return;
  if (!is_array($trees)) return;
  if (count($trees) !== 1) return;

  $tree = reset($trees);
  if (empty($tree->item->metadata_id)) return;
  $link = get_permalink($tree->item->metadata_id);
  wp_redirect($link);
  exit;
}

function cluevo_init_shortcodes()
{
  add_shortcode(CLUEVO_SHORTCODE, 'cluevo_add_shortcode');
  add_shortcode(CLUEVO_TOC_SHORTCODE, 'cluevo_add_toc_shortcode');
  add_shortcode(CLUEVO_GUIDELINE_SHORTCODE, 'cluevo_add_guideline_shortcode');
  add_shortcode(CLUEVO_USER_WIDGET_SHORTCODE, 'cluevo_add_user_widget_shortcode');
}

function cluevo_get_parent_permalink($item)
{
  if (empty($item)) return null;
  $parent = cluevo_get_learning_structure_item($item->parent_id);
  if (empty($parent) || !is_object($parent)) return $item->permalink;
  return get_permalink($parent->metadata_id);
}

function cluevo_module_completion_status_name($strStatus)
{
  switch ($strStatus) {
    case "completed":
      return __("Completed", "cluevo");
      break;
    case "incomplete":
      return __("Incomplete", "cluevo");
      break;
    case "not attempted":
      return __("Not Attempted", "cluevo");
      break;
    case "unknown":
      return __("Unknown", "cluevo");
      break;
    default:
      return $strStatus;
  }
}

function cluevo_module_success_status_name($strStatus)
{
  switch ($strStatus) {
    case "passed":
      return __("Passed", "cluevo");
      break;
    case "failed":
      return __("Failed", "cluevo");
      break;
    case "unknown":
      return __("Unknown", "cluevo");
      break;
    default:
      return $strStatus;
  }
}

function cluevo_module_lesson_status_name($strStatus)
{
  switch ($strStatus) {
    case "passed":
      return __("Passed", "cluevo");
      break;
    case "completed":
      return __("Completed", "cluevo");
      break;
    case "failed":
      return __("Failed", "cluevo");
      break;
    case "incomplete":
      return __("Incomplete", "cluevo");
      break;
    case "browsed":
      return __("Browsed", "cluevo");
      break;
    case "not attempted":
      return __("Not Attempted", "cluevo");
      break;
    default:
      return $strStatus;
  }
}

function cluevo_output_module_type($module)
{
  if (!empty($module)) {
    if (!empty($module->scorm_version)) {
      return "SCORM " . $module->scorm_version;
    }
  }

  return ucwords($module->type_name);
}
