<?php
$out = '<div class="cluevo-toc-container">';
$tpl = cluevo_get_template("part-guideline-item");
if (!empty($tpl)) {
  while ($item = cluevo_the_lms_item()) {
    ob_start();
    include($tpl);
    $out .= ob_get_clean();
  }
}
$out .= '</div>';
