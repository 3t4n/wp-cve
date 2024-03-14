<?php
if (EzGA::getSlug() == 'google-adsense') {
  $top = 'show_top';
  $middle = 'show_middle';
  $bottom = 'show_bottom';
  $floatLeft = 'floatLeft';
  $left = 'left';
  $center = 'center';
  $floatRight = 'floatRight';
  $right = 'right';
}
else {
  $top = 'show_leadin';
  $middle = 'show_midtext';
  $bottom = 'show_leadout';
  $floatLeft = 'float:left';
  $left = 'text-align:left';
  $center = 'text-align:center';
  $floatRight = 'float:right';
  $right = 'text-align:right';
}
$no = 'no';
$choices = compact('left', 'floatLeft', 'center', 'right', 'floatRight', 'no');
$ezOptions[$top] = array('name' => __("Top", 'easy-adsenser'),
    'help' => __("Where to show the top ad block? Select Left, Center, Right, or suppress this ad block. Select the <b>Wrap</b> Left, Center or Right option to wrap (or flow) the text around the ad block.", 'easy-adsenser'),
    'value' => $floatLeft,
    'type' => 'radio',
    'options' => $choices);
$ezOptions[$middle] = array('name' => __("Middle", 'easy-adsenser'),
    'help' => __("Where to show the mid-text ad block? Select left, center, right, or suppress this ad block.", 'easy-adsenser'),
    'value' => $floatRight,
    'type' => 'radio',
    'options' => $choices);
$ezOptions[$bottom] = array('name' => __("Bottom", 'easy-adsenser'),
    'help' => __("Where to show the bottom ad block? Select left, center, right, or suppress this ad block.", 'easy-adsenser'),
    'value' => $floatRight,
    'type' => 'radio',
    'options' => $choices);
