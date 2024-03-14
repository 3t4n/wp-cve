<?php
/**
 * @file
 * Generates traffic source reports
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_audience_list_report_page($report_subtype = '-', $report_subfilter = '-', $entity_type = '-', $entity = '-', $vars = array()) {
  // TODO WP hack for debugging
  $_GET['return_type'] = 'nonajax';

  require_once INTEL_DIR . "includes/intel.reports.php";
  require_once INTEL_DIR . "includes/intel.ga.php";
  $output = '';

  if (!empty($entity_type) && $entity_type != '-') {
    $vars['entity_type'] = $entity_type;
    $vars['entity'] = $entity;
  }
  $report_params = !empty($_GET['report_params']) ? $_GET['report_params'] : '-';
  $vars = intel_init_reports_vars('audience_list', 'audience', $report_params, $report_subtype, $report_subfilter, $vars);

  $output = intel_build_report($vars);
  return $output;
}

//function intel_audience_list_report($vars['filters'] = array(), $vars['context'] = 'site', $sub_index = '-') {
function intel_audience_list_report($vars) {
  intel_include_library_file('ga/class.ga_model.php');
//intel_d($vars);
  if (empty($vars['report_subtype'])) {
    $vars['report_subtype'] = 'address';
  }

  $vars['indexBy'] = $vars['report_modes'][0];
  $vars['indexByLabel'] = $vars['report_info']['title'];

  $output = '';

  $ga_data = new LevelTen\Intel\GAModel();
  $ga_data->setContext($vars['context']);
  $ga_data->setContextMode($vars['context_mode']);
  $ga_data->setReportModes($vars['report_modes']);
  $ga_data->setAttributeInfoAll($vars['attribute_info']);
  $ga_data->buildFilters($vars['filters'], $vars['subsite']);
  $ga_data->setDateRange($vars['start_date'], $vars['end_date']);
  $ga_data->setRequestCallback('intel_ga_feed_request_callback', array('cache_options' => $vars['cache_options']));
//$ga_data->setDebug(1);

  $details = 0;
  if ($vars['report_subfilter_type'] == 'address') {
    $details = $vars['report_subfilter'];
  }

  $ga_data->setRequestSetting('indexBy', $vars['indexBy']);
  $ga_data->setRequestSetting('details', $details);

  $ga_data->setRequestDefaultParam('max_results', 2 * $vars['feed_rows']);
  $ga_data->loadFeedData('entrances');
  $ga_data->loadFeedData('entrances_events_valued');

  $d = $ga_data->data;
//intel_d($d);
  // append array if no data exists
  $d += array(
    'trafficcategory' => array(),
    'medium' => array(),
    'source' => array(),
    'referralHostpath' => array(),
    'socialNetwork' => array(),
    'searchKeyword' => array(),
    'keyword' => array(),
    'campaign' => array(),
  );

  foreach ($d[$vars['indexBy']] AS $index => $de) {
    $score_components = array();
    //$d[$indexBy][$index]['score'] = intel_score_visit_aggregation($de, 1, $score_components);
    $d[$vars['indexBy']][$index]['score'] = intel_score_item($de, 1, $score_components, '', 'entrance');
    $d[$vars['indexBy']][$index]['score_components'] = $score_components;
    if (empty($de['i'])) {
      continue;
    }

    if (isset($de['i'])) {
      $d[$vars['indexBy']][$index]['links'] = array();
      $val = $de['i'];
      // encode paths in filters
      $filter = $vars['indexBy'] . ':' . rawurlencode($val);
      $link_keys = array();
      $link_keys[] = array(
        'type' => 'scorecard',
      );
      $link_keys[] = array(
        'type' => 'content',
      );
      $link_keys[] = array(
        'type' => 'visitor',
      );
      if ($vars['report_subtype'] == 'source') {
        $link_keys[] = array(
          'type' => 'audience',
          'subtype' => 'medium',
        );
      }
      $d[$vars['indexBy']][$index]['links'] = intel_build_report_links($link_keys, $filter, $vars['report_params']);

      /*
      $params = $vars['report_params'];
      $params['_filter_add'] = $filter;
      $d[$indexBy][$index]['links'][] = l(t('+'), intel_build_report_path('scorecard', $params));

      $params = $vars['report_params'];
      $params['_filter_only'] = $filter;


      $d[$indexBy][$index]['links'][] = l(t('scorecard'), intel_build_report_path('scorecard', $params));
      $d[$indexBy][$index]['links'][] = l(t('content'), intel_build_report_path('content', $params));
      $d[$indexBy][$index]['links'][] = l(t('visitors'), intel_build_report_path('visitor', $params));
      if ($vars['report_subtype'] == 'source') {
        $link_keys[] = array(
          'type' => 'audience',
          'subtype' => 'medium',
        );
        $d[$indexBy][$index]['links'][] = l(t('mediums'), intel_build_report_path('audience', $vars['report_params'], 'medium', $filter));
      }
      elseif ($vars['report_subtype'] == 'medium') {
        $d[$indexBy][$index]['links'][] = l(t('sources'), intel_build_report_path('audience', $vars['report_params'], 'source', $filter));
      }
      elseif ($vars['report_subtype'] == 'searchengine') {
        $d[$indexBy][$index]['links'][] = l(t('keywords'), intel_build_report_path('audience', $vars['report_params'], 'keyword', $filter));
      }
      elseif ($vars['report_subtype'] == 'searchkeyword') {
        $d[$indexBy][$index]['links'][] = l(t('source'), intel_build_report_path('audience', $vars['report_params'], 'source', $filter));
      }
      elseif ($vars['report_subtype'] == 'referralhostname') {
        $d[$indexBy][$index]['links'][] = l(t('pages'), intel_build_report_path('audience', $vars['report_params'], 'referralhostpath', $filter));
      }
      elseif ($vars['report_subtype'] == 'socialnetwork') {
        $d[$indexBy][$index]['links'][] = l(t('pages'), intel_build_report_path('audience', $vars['report_params'], 'referralhostpath', $filter));
      }

      intel_build_report_links($link_keys, $filter, $vars['report_params']);
      */
    }
  }

  // order date data cronologically
  //ksort($d['content']);
  
//dsm($d);
  
  $vars['data'] = $d;
  $output .= theme_intel_report($vars);

  return $output;
}

