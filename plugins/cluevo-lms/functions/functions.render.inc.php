<?php

function cluevo_render_tree_index()
{
  $trees = CluevoTree::load_all();
  if (!empty($trees)) {
    foreach ($trees as $tree) {
      do_action("cluevo_render_tree_tile", $tree);
    }
  } else if (current_user_can('administrator')) {
    cluevo_display_notice(
      __("Notice", "cluevo"),
      __("The course index is empty. You can add courses through the admin area.", "cluevo")
    );
  } else {
    cluevo_display_notice(
      __("Notice", "cluevo"),
      __("The course index is empty or you do not have the required permissions to access this page", "cluevo")
    );
  }
}

function cluevo_render_tree_tile($tree) {
  $out = '<div class="cluevo-tree-tile">';
  $out .= '</div>';
}
