<?php
/**
 * @file
 * Functions to support extended Google Analytics data.
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_annotation_period_options() {
  $options = array(
    '1' => Intel_Df::t('1 hr'),
    '2' => Intel_Df::t('2 hr'),
    '4' => Intel_Df::t('4 hr'),
    '8' => Intel_Df::t('8 hr'),
    '24' => Intel_Df::t('24 hrs'),
    '48' => Intel_Df::t('2 days'),
    '96' => Intel_Df::t('4 days'),
    '168' => Intel_Df::t('1 week'),
    '336' => Intel_Df::t('2 weeks'),
    '504' => Intel_Df::t('3 weeks'),
    '672' => Intel_Df::t('4 weeks'),
    '1008' => Intel_Df::t('6 weeks'),
    '1344' => Intel_Df::t('8 weeks'),
    '2184' => Intel_Df::t('13 weeks'),
  );

  return $options;
}

function intel_annotation_get_latest_available_period($annotation) {

  return intel_get_latest_available_period($annotation->started);
}

function intel_get_latest_available_period($timestamp) {
  $period_options = intel_annotation_period_options();

  $sph = 3600;

  $period = 0;
  foreach ($period_options as $hrs => $label) {
    $delta = (int)$hrs * $sph;
    if (($timestamp + $delta) > REQUEST_TIME) {
      break;
    }
    $period = $delta;
  }

  return $period;
}

function intel_get_next_available_period($timestamp) {
  $period_options = intel_annotation_period_options();

  $sph = 3600;

  $period = 0;
  foreach ($period_options as $hrs => $label) {
    $delta = (int)$hrs * $sph;
    if (($timestamp + $delta) > REQUEST_TIME) {
      $period = $delta;
      break;
    }
  }

  return $period;
}

function intel_annotation_get_max_period($annotation = NULL) {
  $period = 2184 * 3600;
  return $period;
}

function intel_annotation_format_summary($text, $max_length = 32) {
  $l = preg_split('/\r\n|[\r\n]/', $text);
  $l = $l[0];
  if (strlen($l) > $max_length) {
    $l = substr($l, 0, $max_length - 3) . '...';
  }

  return $l;
}