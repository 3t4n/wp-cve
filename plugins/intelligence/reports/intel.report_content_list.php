<?php
/**
 * @file
 * Generates content reports
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_content_list_report_page($report_subtype = '-', $report_subfilter = '-', $entity_type = '-', $entity = '-') {
  // TODO WP hack for debugging
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
  $vars = intel_init_reports_vars('content_list', 'content', $report_params, $report_subtype, $report_subfilter, $vars);

  $output = intel_build_report($vars);

  return $output;
}

function intel_content_list_report($vars) {
  $output = '';

  $scoreDataIndexes = array('content');
  $linkDataIndexes = array('content');

  if (isset($vars['report_modes'][2])) {
    if ($vars['report_modes'][2] == 'entrance') {
      $vars['context'] = 'entrance';
    }
  }
  
  if ($vars['report_modes'][0] == 'engagement') {
    $vars['feed_rows'] *= 4;
  }

  $ga_data_baseline = intel_get_content_report_ga_data($vars);
  $d = array();
  $d['content'] = !empty($ga_data_baseline->data['content']) ? $ga_data_baseline->data['content'] : array();
  // if vars set for comparison data, fetch that data
  if (!empty($vars['comp'])) {
    $vars_comp = $vars['comp'] + $vars;
    $ga_data_comp = intel_get_content_report_ga_data($vars_comp);

    $d['content_comp'] = $ga_data_comp->data['content'];

    $scoreDataIndexes[] = 'content_comp';
    $linkDataIndexes = array('content_delta');
  }



  // score items
  foreach ($scoreDataIndexes AS $dataIndex) {
    if (!isset($d[$dataIndex])) {
      continue;
    }
    foreach ($d[$dataIndex] AS $index => $de) {
      $score_components = array();
      if ($vars['report_modes'][0] == 'seo') {
        //$d['content'][$index]['score'] = intel_score_visit_aggregation($de, 1, $score_components, 'seo');
        $d[$dataIndex][$index]['score'] = intel_score_item($de, 1, $score_components, 'seo', 'entrance');
      }
      elseif ($vars['report_modes'][0] == 'social') {
        //$d['content'][$index]['score'] = intel_score_visit_aggregation($de, 1, $score_components, 'social');
        $d[$dataIndex][$index]['score'] = intel_score_item($de, 1, $score_components, 'social', 'entrance');
        $d[$dataIndex][$index]['score'] += $d['content'][$index]['pageview']['events']['_all']['value'];
      }
      elseif ($vars['report_modes'][0] == 'engagement') {
        $d[$dataIndex][$index]['score'] = $d['content'][$index]['pageview']['events']['_all']['value'];
      }
      else {
        if ($vars['context'] == 'page' || $vars['context'] == 'page-attr') {
          $d[$dataIndex][$index]['score'] = intel_score_item($de, 1, $score_components, '', 'page');
        }
        else {
          $d[$dataIndex][$index]['score'] = intel_score_item($de, 1, $score_components, '', '');
          //$d[$dataIndex][$index]['score'] = intel_score_item($de, 1, $score_components, '', 'site');
        }
      }
//intel_d($index);
//intel_d($de);
//intel_d($score_components);
      $d[$dataIndex][$index]['score_components'] = $score_components;
    }
  }

  // create delta data
  if (!empty($d['content_comp'])) {
    $d['content_delta'] = intel_build_report_data_delta($d['content'], $d['content_comp'], $vars);
  }


  //dsm($d);

  foreach ($linkDataIndexes AS $dataIndex) {
    if (!isset($d[$dataIndex])) {
      continue;
    }
    foreach ($d[$dataIndex] AS $index => $de) {
      if (isset($de['i'])) {
        $d[$dataIndex][$index]['links'] = array();
        $u = explode('/', $de['i'], 2);
        $uri = isset($u[1]) ? '/' . $u[1] : '/';
        $uri = str_replace('/', '|', $uri);
        $filter = 'pagePath:' . rawurlencode($uri);

        $link_keys = array();
        $link_keys[] = array(
          'type' => 'scorecard',
        );
        $link_keys[] = array(
          'type' => 'trafficsource',
        );
        $link_keys[] = array(
          'type' => 'visitor',
        );

        $d[$dataIndex][$index]['links'] = intel_build_report_links($link_keys, $filter, $vars['report_params']);
/*
        $filter = 'pagePath:' . $uri;
        $params = $vars['report_params'];
        $params['_filter_add'] = $filter;
        $d[$dataIndex][$index]['links'][] = Intel_Df::l(t('+'), intel_build_report_path('scorecard', $params));

        $params = $vars['report_params'];
        $params['_filter_only'] = $filter;
        $d[$dataIndex][$index]['links'][] = Intel_Df::l(t('scorecard'), intel_build_report_path('scorecard', $params));
        $d[$dataIndex][$index]['links'][] = Intel_Df::l(t('sources'), intel_build_report_path('trafficsource', $params));
        $d[$dataIndex][$index]['links'][] = Intel_Df::l(t('visitors'), intel_build_report_path('visitor', $params));
*/
      }
    }
  }

  $vars['data'] = $d;

  $output .= theme_intel_report($vars);

  return $output;
}

function intel_get_content_report_ga_data($vars = array()) {
  intel_include_library_file('ga/class.ga_model.php');
  require_once INTEL_DIR . "includes/intel.page_data.php";

  $ga_data = new LevelTen\Intel\GAModel();
  $ga_data->setContext($vars['context']);
  $ga_data->setContextMode($vars['context_mode']);
  $ga_data->setReportModes($vars['report_modes']);
  $ga_data->setAttributeInfoAll($vars['attribute_info']);
  $ga_data->buildFilters($vars['filters'], $vars['subsite']);
  $ga_data->setDateRange($vars['start_date'], $vars['end_date']);
  $ga_data->setRequestCallback('intel_ga_feed_request_callback', array('cache_options' => $vars['cache_options']));
  $ga_data->setFeedRowsCallback('intel_get_ga_feed_rows_callback');
//$ga_data->setDebug(1);
//dsm($context);
//dsm($ga_data->gaFilters);
  //$ga_data->setDataIndexCallback('category', '_intel_determine_category_index');

  $ga_data->setRequestSetting('indexBy', 'content');
  $ga_data->setRequestSetting('details', 0);

  $ga_data->setDebug(0);
  // base pageview/entrance data
  if ($vars['context'] != 'entrance' && ($vars['report_modes'][0] != 'social') && ($vars['report_modes'][0] != 'seo')) {
    $ga_data->setAdvancedSort(1);
    $ga_data->setRequestDefaultParam('max_results', 4 * $vars['feed_rows']);
    $ga_data->loadFeedData('pageviews');
    $ga_data->setAdvancedSort(0);
  }

  $ga_data->setDebug(0);

  if (($vars['report_modes'][0] != 'social') && ($vars['report_modes'][0] != 'seo')) {
    $ga_data->setRequestDefaultParam('max_results', 2 * $vars['feed_rows']);
    $ga_data->loadFeedData('entrances');
  }

  // events data
//$ga_data->setDebug(1);
  $use_default = 1;
  if (($vars['report_modes'][0] == 'engagement') || ($vars['report_modes'][0] == 'social')) {
    $ga_data->setRequestSetting('details', 1);
    $ga_data->setRequestDefaultParam('max_results', 5 * $vars['feed_rows']);
    $ga_data->loadFeedData('pageviews_events_valued');
    $use_default = 0;
  }
  elseif ($vars['report_modes'][0] == 'social') {
    $use_default = 0;
  }
  if ($use_default) {
    $ga_data->setRequestSetting('details', 0);
    if ($vars['context'] != 'entrance') {
      $ga_data->setRequestDefaultParam('max_results', 2 * $vars['feed_rows']);
      $ga_data->loadFeedData('pageviews_events_valued');
    }

    $ga_data->setRequestDefaultParam('max_results', $vars['feed_rows']);
    $ga_data->loadFeedData('entrances_events_valued');
  }

  // referrer data
//$ga_data->setDebug(1);
  if ($vars['report_modes'][0] == 'seo') {
    $ga_data->setRequestSetting('details', 0);
    $ga_data->setRequestDefaultParam('max_results', $vars['feed_rows']);
    $ga_data->setRequestSetting('subIndexBy', 'organicSearch');
    $ga_data->loadFeedData('entrances');

    $ga_data->setRequestSetting('details', 1);
    $ga_data->setRequestDefaultParam('max_results', 5 * $vars['feed_rows']);
    $ga_data->setRequestSetting('subIndexBy', 'searchKeyword');
    $ga_data->loadFeedData('entrances');
  }
  elseif ($vars['report_modes'][0] == 'social') {
    $ga_data->setRequestSetting('details', 1);
    $ga_data->setRequestDefaultParam('max_results', 10 * $vars['feed_rows']);
    $ga_data->setRequestSetting('subIndexBy', 'socialNetwork');
    $ga_data->loadFeedData('entrances');
  }

  return $ga_data;
}