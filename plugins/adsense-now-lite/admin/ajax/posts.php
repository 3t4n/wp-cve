<?php

require_once('../../EzGA.php');
if (!EzGA::isLoggedIn()) {
  http_response_code(400);
  die("Please login before doing this!");
}

$selected = EzGA::getTaxonomyList($_REQUEST);

http_response_code(200);
$myposts = get_posts(array('numberposts' => -1));
foreach ($myposts as $mypost) {
  setup_postdata($mypost);
  if (in_array($mypost->ID, $selected)) {
    $checked = 'checked="checked"';
  }
  else {
    $checked = '';
  }
  echo "<input class='selectit' value='$mypost->ID' type='checkbox' $checked> $mypost->post_title<br>\n";
}
if (!EzGA::$isPro) {
  $slug = EzGA::getSlug();
  echo "<br><p class='red'><strong>This feature is implemented only in the <a href='#' class='goPro' data-product='$slug'>Pro Version</a> of this plugin.<//strong></p>";
}

exit();
