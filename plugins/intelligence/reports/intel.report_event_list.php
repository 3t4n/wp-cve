<?php
/**
 * @file
 * Generates event reports
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_event_list_report_page($report_subtype = '-', $report_subfilter = '-', $entity_type = '-', $entity = '-') {
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
  $vars = intel_init_reports_vars('event_list', 'event', $report_params, $report_subtype, $report_subfilter, $vars);

  $output = intel_build_report($vars);

  return $output;
}

//function intel_event_list_report($vars['filters'] = array(), $vars['context'] = 'site', $report_mode = '-', $mode = '') {
function intel_event_list_report($vars) {
  intel_include_library_file('ga/class.ga_model.php');
  require_once INTEL_DIR . "/includes/intel.page_data.php";

  $output = '';

  /*
  if (!empty($vars['filters']['page'])) {
    $a = explode(":", $vars['filters']['page']);
    $path = $a[1];
  }
  */

  $details = 1;

  $dataScope = 'pageview';
  if (isset($vars['report_modes'][2]) && $vars['report_modes'][2] == 'entrance') {
    $dataScope = $vars['context'] = 'entrance';
  }

  if ($vars['context'] == 'trafficsource' || $vars['context'] == 'visitor') {
    $dataScope = 'entrance';
  }

  if (isset($vars['report_modes'][0]) && strpos($vars['report_modes'][0], 'event:') === 0) {
    $a = explode(':', $vars['report_modes'][0]);
    $event_key = $a[1];

    $vars['report_event_info'] = $event_info = intel_get_intel_event_info($event_key);
    if (!isset($vars['filters']['event'])) {
      $vars['filters']['event'] = array();
    }
    $details = 'eventCategory:' . $event_info['category'];
    $vars['filters']['event'][] = $details;
  }
  //dsm($vars);

  $value_type = '';
  if (isset($vars['report_modes'][3])) {
    if ($vars['report_modes'][3] == 'valued') {
      $value_type = '_valued';
    }
    elseif ($vars['report_modes'][3] == 'nonvalued') {
      $value_type = '_nonvalued';
    }
    elseif ($vars['report_modes'][3] == 'goal') {
      $value_type = '_goal';
    }
  }

  $indexBy = 'date';

  $ga_data = new LevelTen\Intel\GAModel();
  $ga_data->setContext($vars['context']);
  $ga_data->setContextMode($vars['context_mode']);
  $ga_data->setReportModes($vars['report_modes']);
  $ga_data->setAttributeInfoAll($vars['attribute_info']);
  $ga_data->buildFilters($vars['filters'], $vars['subsite']);
  $ga_data->setDateRange($vars['start_date'], $vars['end_date']);
  $ga_data->setRequestCallback('intel_ga_feed_request_callback', array('cache_options' => $vars['cache_options']));
//$ga_data->setDebug(1);
//dsm($vars['context']);  
//dsm($ga_data->gaFilters);

  $ga_data->setRequestSetting('indexBy', $indexBy);
  if ($vars['report_subfilter_type'] == 'event') {
    $a = explode(':', $vars['report_subfilter']);
    $details = $vars['report_subfilter'];
  }
  $ga_data->setRequestSetting('details', $details);
  $ga_data->setRequestDefaultParam('max_results', 20 *  ($vars['number_of_days']+1));

  if ($dataScope == 'entrance') {
    $ga_data->loadFeedData('entrances_events' . $value_type);
  }
  else {
    $ga_data->loadFeedData('pageviews_events' . $value_type);
  }


  $d = $ga_data->data;

  if (!empty($d[$indexBy])) {
    ksort($d[$indexBy]);
  }


  if (empty($d[$indexBy]['_all'][$dataScope]['events'])) {
    $d[$indexBy]['_all'][$dataScope]['events'] = array();
  }

  foreach ($d[$indexBy]['_all'][$dataScope]['events'] AS $index => $de) {
    if (isset($de['i'])) {
      $params = $vars['report_params'];
      $filter = 'event:' . rawurlencode($de['i']);

      $link_keys = array();
      $link_keys[] = array(
        'type' => 'scorecard',
      );
      $link_keys[] = array(
        'type' => 'trafficsource',
      );
      $link_keys[] = array(
        'type' => 'content',
      );
      $link_keys[] = array(
        'type' => 'visitor',
      );
      $d[$indexBy]['_all'][$dataScope]['events'][$index]['links'] = intel_build_report_links($link_keys, $filter, $vars['report_params']);
      if (!$vars['report_subfilter']) {
        $link = Intel_Df::l( Intel_Df::t('details'), intel_build_report_path('event', array(), '-', $filter));
        if (count($vars['report_params']) > 0) {
          $link .= Intel_Df::l('+', intel_build_report_path('event', $params, '-', $filter));
        }
        $d[$indexBy]['_all'][$dataScope]['events'][$index]['links'][] = $link;
      }
    }
  }






//dsm($d);

  // order date data chronologically
  //ksort($d['date']);
  $vars['data'] = $d;

  $output .= theme_intel_report($vars);

  return $output;
}

/*
function theme_intel_event_list_report($vars) {
  intel_include_library_file("reports/class.event_report_view.php");
  
  $output = '';

  $report_view = new levelten\intel\EventReportView();
  $report_view->setData($vars['data']);
  $report_view->setTableRowCount($vars['row_count']);
  $report_view->setModes($vars['report_modes']);
  $report_view->setParam('context', $vars['context']);
  $report_view->setParam('context_mode', $vars['context_mode']);
  $report_view->setDateRange($vars['start_date'], $vars['end_date']);
  $report_view->setPageMetaCallback('intel_get_page_meta_callback');
  $report_view->setTargets(intel_get_targets());
  \LevelTen\Intel\ReportPageHeader::setAddScriptCallback('intel_report_add_js_callback');
  $output .= $report_view->renderReport();
  
  return $output; 
}
*/