<?php

require_once('../../EzGA.php');
if (!EzGA::isLoggedIn()) {
  http_response_code(400);
  die("Please login before doing this!");
}

$selected = EzGA::getTaxonomyList($_REQUEST);

http_response_code(200);
$pages = get_pages();
echo "<p>Select the pages and click OK to save.</p>";
foreach ($pages as $page) {
  $tab = '';
  if (!empty($page->post_parent)) {
    $tab .= '&nbsp;&nbsp&nbsp;&nbsp';
  }
  if (in_array($page->ID, $selected)) {
    $checked = 'checked="checked"';
  }
  else {
    $checked = '';
  }
  echo "$tab<input class='selectit' value='$page->ID' $checked type='checkbox'> $page->post_title<br>\n";
}
if (!EzGA::$isPro) {
  $slug = EzGA::getSlug();
  echo "<br><p class='red'><strong>This feature is implemented only in the <a href='#' class='goPro' data-product='$slug'>Pro Version</a> of this plugin.<//strong></p>";
}

exit();
