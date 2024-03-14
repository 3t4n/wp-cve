<?php

require_once('../../EzGA.php');
if (!EzGA::isLoggedIn()) {
  http_response_code(400);
  die("Please login before doing this!");
}

$selected = EzGA::getTaxonomyList($_REQUEST);

include ABSPATH . '/wp-admin/includes/template.php';
http_response_code(200);
ob_start();
echo "<style>ul{list-style:none}</style><ul>";
$args['checked_ontop'] = false;
$args['selected_cats'] = $selected;
wp_terms_checklist(0, $args);
echo "</ul>";
$html = ob_get_clean();
$html = str_replace("<input", "<input class='selectit'", $html);

if (!EzGA::$isPro) {
  $slug = EzGA::getSlug();
  $html .= "<p class='red'><strong>This feature is implemented only in the <a href='#' class='goPro' data-product='$slug'>Pro Version</a> of this plugin.<//strong></p>";
}
echo $html;

exit();
