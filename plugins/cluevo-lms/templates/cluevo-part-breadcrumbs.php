<?php

function print_crumbs($c) {
  if (!empty($c->children)) {
    $children = array_filter($c->children, function($child) {
      return (empty($child->module) || $child->module < 1 || $child->has_content());
    });
  }
  if (!empty($children)) {
    echo "<ul class=\"cluevo-crumb-children\">";
    foreach ($children as $child) {
      echo "<li><a href=\"" . esc_url($child->permalink) . "\">" . esc_html($child->name) . "</a>";
      if (!empty($child->children)) {
        print_crumbs($child);
      }
      echo "</li>";
    }
    echo "</ul>";
  }
}

$enabled = get_option("cluevo-breadcrumbs-enabled", false);
if (!$enabled) return;

$crumbs = cluevo_turbo_get_trees();
$item = cluevo_get_the_lms_page();
echo "<div class=\"cluevo-crumb-container\">";
echo "<div class=\"cluevo-crumb cluevo-breadcrumb-index\">";
echo "<p class=\"cluevo-crumb-title\">";
echo "<a href=\"" . cluevo_get_the_index_page_link() . "\">" . esc_html__("Index", "cluevo") . "</a></p>";
echo "</div>";
if (!empty($item->path) && !is_string($item->path)) {
  foreach ($item->path->id as $id) {
    $crumb = cluevo_get_learning_structure_item($id);
    if (!empty($crumb)) {
      echo "<div class=\"cluevo-crumb-spacer\"><span class=\"dashicons dashicons-arrow-right-alt2\"></span></div>";
      echo "<div class=\"cluevo-crumb\">";
      echo "<p class=\"cluevo-crumb-title\">";
      echo "<a href=\"" . esc_url($crumb->permalink) . "\">" . esc_html($crumb->name) . "</a></p>";
      print_crumbs($crumb);
      echo "</div>";
    }
  }
} else {
  if (!empty($item)) {
    echo "<div class=\"cluevo-crumb-spacer\"><span class=\"dashicons dashicons-arrow-right-alt2\"></span></div>";
    echo "<div class=\"cluevo-crumb\">";
    echo "<p class=\"cluevo-crumb-title\">";
    echo "<a href=\"" . esc_url($item->permalink) . "\">" . esc_html($item->name) . "</a></p>";
    print_crumbs($item);
    echo "</div>";
  }
}
echo "</div>";
?>
