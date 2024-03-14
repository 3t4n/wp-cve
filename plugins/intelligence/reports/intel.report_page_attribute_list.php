<?php
/**
 * @file
 * Generates author reports
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_page_attribute_list_report_page($report_subtype = '-', $report_subfilter = '-', $entity_type = '-', $entity = '-') {
  $_GET['return_type'] = 'nonajax';

  require_once INTEL_DIR . "includes/intel.reports.php";
  require_once INTEL_DIR . "includes/intel.ga.php";
  $output = '';

  $vars = array();
  if (!empty($entity_type) && $entity_type != '-') {
    $vars['entity_type'] = $entity_type;
    $vars['entity'] = $entity;
  }
  $report_params = !empty($_GET['report_params']) ? $_GET['report_params'] : '-';
  $vars = intel_init_reports_vars('page_attribute_list', 'content', $report_params, $report_subtype, $report_subfilter, $vars);

  $output = intel_build_report($vars);

  return $output;
}

//function intel_page_attribute_list_report($vars['filters'] = array(), $context = 'site', $sub_index = '-', $mode = '') {
function intel_page_attribute_list_report($vars) {
  intel_include_library_file('ga/class.ga_model.php');
  require_once Intel_Df::drupal_get_path('module', 'intel') . "/includes/intel.page_data.php";

  $a = explode(':', $vars['report_modes'][0]);
  $attr_key = $a[1];

  $vars['report_attribute_info'] = $attr_info = intel_get_page_attribute_info($attr_key);
  $vars['attribute_info']['page'][$attr_key] = $attr_info;
  $indexBy = $vars['indexBy'] = 'pageAttribute:' . $attr_key;

  // user larger than default number of result rows to get larger sampling
  // needed since data is summed across customVar strings (i.e. page) in
  // segment
  $vars['feed_rows'] = 50 * $vars['row_count'];
  
  $output = '';

  $ga_data = new LevelTen\Intel\GAModel();
  $ga_data->setContext($vars['context']);
  $ga_data->setContextMode($vars['context_mode']);

  $ga_data->setAttributeInfoAll($vars['attribute_info']);
  $ga_data->buildFilters($vars['filters'], $vars['subsite']);

  //dsm($ga_data->gaFilters);


  //$ga_data->addGAFilter('ga:dimension1!@&a=1&');

  $ga_data->setDateRange($vars['start_date'], $vars['end_date']);
  $ga_data->setRequestCallback('intel_ga_feed_request_callback', array('cache_options' => $vars['cache_options']));
  // set advancedSort to get better mix of data for pageviews and entraces
  $ga_data->setAdvancedSort(0);

  //$ga_data->setDataIndexCallback('category', '_intel_determine_category_index');

  $ga_data->setRequestSetting('indexBy', $indexBy);
  $ga_data->setRequestSetting('context', $vars['context']);
  //$ga_data->setDebug(1);
  // build pagepath map
  $ga_data->setRequestDefaultParam('max_results', 2 * $vars['feed_rows']);
  $ga_data->loadFeedData('entrances_pagepathmap');

//$ga_data->setDebug(1);
  $ga_data->setRequestSetting('details', 0);
  $ga_data->setRequestDefaultParam('max_results', 2 * $vars['feed_rows']);
  $ga_data->loadFeedData('pageviews');

  $ga_data->setRequestDefaultParam('max_results', 1 * $vars['feed_rows']);
  $ga_data->loadFeedData('entrances');

//$ga_data->setDebug(1);
  $ga_data->setRequestDefaultParam('max_results', 2 * $vars['feed_rows']);
  $ga_data->loadFeedData('sessions');
  // advanced sort not needed for valued events
  $ga_data->setAdvancedSort(0);


  $ga_data->setRequestDefaultParam('max_results', 2 * $vars['feed_rows']);
  $ga_data->loadFeedData('pageviews_events_valued');
  
  $ga_data->setRequestDefaultParam('max_results', 2 * $vars['feed_rows']);
  $ga_data->loadFeedData('entrances_events_valued');

  //$ga_data->setRequestDefaultParam('max_results', 2 * $vars['feed_rows']);
  $ga_data->loadFeedData('sessions_events_valued');

  $ga_data->loadFeedData('sessions_goals');

  $d = $ga_data->data;

  $data_options = array(
    'page_count' => 1,
  );
  $d[$indexBy]['_info'] = array(
    'item_count' => 0,
  );
  foreach ($d[$indexBy] AS $index => $de) {
    if (empty($de['i']) || (substr($de['i'], 0 , 1) == '_')) { 
      continue; 
    }
    $d[$indexBy]['_info']['item_count']++;

    if (in_array($attr_info['type'], array('value', 'scalar', 'item'))) {
      $optionInfo = intel_get_attribute_option_info('page', $attr_key, $de['i'], $data_options);
    }
    elseif (in_array($attr_info['type'], array('list', 'vector'))) {
      $optionInfo = intel_get_attribute_option_info('page', $attr_key, $de['i'], $data_options);
    }
    if (!empty($optionInfo['title'])) {
      $d[$indexBy][$index]['info'] = $optionInfo;
    }
  }

  //dsm($d);

  foreach ($d[$indexBy] AS $index => $de) {
    $score_components = array();

    if ($vars['context'] == 'page' || $vars['context'] == 'page-attr') {
      $method = 'page';
    }
    $d[$indexBy][$index]['score'] = intel_score_item($de, 1, $score_components, '', $method);
//intel_d($index);
//intel_d($de);
//intel_d($score_components);
    //$d[$indexBy][$index]['score'] = intel_score_page_aggregation($de, 1, $score_components);
    $d[$indexBy][$index]['score_components'] = $score_components;
    if (isset($de['i'])) {
      $val = $de['i'];

      $filter = 'pa-' . $attr_key . ':' . rawurlencode($val);

      $link_keys = array();
      $link_keys[] = array(
        'type' => 'scorecard',
      );
      $link_keys[] = array(
        'type' => 'content',
      );
      $link_keys[] = array(
        'type' => 'trafficsource',
      );
      $link_keys[] = array(
        'type' => 'visitor',
      );

      $d[$indexBy][$index]['links'] = intel_build_report_links($link_keys, $filter, $vars['report_params']);


      /*
      $d[$indexBy][$index]['links'] = array();
      $params = $vars['report_params'];
      $params['_filter_add'] = $filter;
      $d[$indexBy][$index]['links'][] = l(t('+'), intel_build_report_path('scorecard', $params));

      $params = $vars['report_params'];
      $params['_filter_only'] = $filter;
      $d[$indexBy][$index]['links'][] = l(t('scorecard'), intel_build_report_path('scorecard', $params));
      $d[$indexBy][$index]['links'][] = l(t('content'), intel_build_report_path('content', $params));
      $d[$indexBy][$index]['links'][] = l(t('sources'), intel_build_report_path('trafficsource', $params));
      $d[$indexBy][$index]['links'][] = l(t('visitors'), intel_build_report_path('visitor', $params));
      */
    }
  }

//dsm($d);

  $vars['data'] = $d;

  $output .= theme_intel_report($vars);

  return $output;
}

/*
function theme_intel_page_attribute_list_report($vars) {
  intel_include_library_file('reports/class.page_attribute_report_view.php');
  
  $output = '';

  $report_view = new LevelTen\Intel\PageAttributeReportView();
  $report_view->setData($vars['data']);
  $report_view->setTableRowCount($vars['row_count']);
  $report_view->setModes($vars['report_modes']);
  $report_view->setParam('context', $vars['context']);
  $report_view->setParam('context_mode', $vars['context_mode']);
  $report_view->setDateRange($vars['start_date'], $vars['end_date']);
  $report_view->setTargets(intel_get_targets());
  $report_view->setAttributeInfo($vars['attr_info']);
  \LevelTen\Intel\ReportPageHeader::setAddScriptCallback('intel_report_add_js_callback');
  $output .= $report_view->renderReport();
  
  return $output;
}
*/