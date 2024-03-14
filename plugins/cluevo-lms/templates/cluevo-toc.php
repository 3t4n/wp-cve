<?php
$out = '<div class="cluevo-toc-container">';
$tpl = cluevo_get_template("part-toc-item");
if (!empty($tpl)) {
  while ($item = cluevo_the_lms_item()) {
    if (!$item->access) continue;
    ob_start();
    include($tpl);
    $out .= ob_get_clean();
  }
}
$out .= '</div>';
