<?php

if ($storageInfo && $storageInfo['plan']['name'] == 'Free') {
?>
  <div class="sirv-message error-message">
    <span style="font-size: 15px;font-weight: 800;">Upgrade your plan</span><br>
    <p>Your Free Sirv plan cannot use <a target="_blank" href=" https://sirv.com/help/articles/smart-gallery/">Sirv Media Viewer smart galleries.</a></p>
    <p>
      Upgrade to a paid plan to automatically add image zooms, 360 spins and videos to your product galleries.
    </p>
    <a class="sirv-plan-upgrade-btn sirv-no-blank-link-icon" href="https://my.sirv.com/#/account/billing/plan" target="_blank">Choose a plan</a>
  </div>
<?php }

require_once(dirname(__FILE__) . '/../includes/classes/options/woo.options.class.php');
$options = include(dirname(__FILE__) . '/../data/options/woo.options.data.php');
echo Woo_options::render_options($options);
?>
