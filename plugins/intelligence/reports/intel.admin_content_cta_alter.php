<?php
/**
 * @file
 * admin > content > calls to action enhancements
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_admin_content_cta_alter_js($cta_type = '') {
  require_once drupal_get_path('module', 'intel') . "/includes/intel.ga.php";
  require_once drupal_get_path('module', 'intel') . "/includes/intel.page_data.php";
  intel_include_library_file('ga/class.ga_model.php');
  intel_include_library_file("reports/class.report_view.php");
  
  $steps = array();
  $request = array(
    'dimensions' => array('ga:eventCategory', 'ga:eventAction', 'ga:eventLabel'),
    'metrics' => array('ga:totalEvents', 'ga:uniqueEvents', 'ga:eventValue'),
    'sort_metric' => '-ga:totalEvents',
    'start_date' => strtotime("-31 days"),
    'end_date' => strtotime("Yesterday"),
    'max_results' => 1000,
    'filters' => 'ga:eventCategory=~^cta',
  );

  $data = intel_ga_api_data($request);

  $ctas = array();
  $rows = intel_get_ga_feed_rows($data);
  if (!empty($rows) && is_array($rows)) {
    foreach ($rows AS $r) {
      $ctaid = '/' . $r['eventLabel'];
      if (!isset($ctas[$ctaid])) {
        $ctas[$ctaid] = array(
          'views' => array(
            'total' => 0,
            'unique' => 0,
          ),
          'clicks' => array(
            'total' => 0,
            'unique' => 0,
          ),
          'conversions' => array(
            'total' => 0,
            'unique' => 0,
          ),
        );
      }
      $cat = strtolower($r['eventCategory']);
      if (substr($cat, -1) == '!') {
        $cat = substr($cat, 0, -1);
      }
      if ($cat == 'cta view') {
        $ctas[$ctaid]['views']['total'] += $r['totalEvents'];
        $ctas[$ctaid]['views']['unique'] += $r['uniqueEvents'];
      }
      if ($cat == 'cta click') {
        $ctas[$ctaid]['clicks']['total'] += $r['totalEvents'];
        $ctas[$ctaid]['clicks']['unique'] += $r['uniqueEvents'];
      }
      if ($cat == 'cta conversion') {
        $ctas[$ctaid]['conversions']['total'] += $r['totalEvents'];
        $ctas[$ctaid]['conversions']['unique'] += $r['uniqueEvents'];
      }
      //$rows['/' . $r['eventLabel']] = '<td class="insight-pageviews">' . number_format($r['pageviews']) . '</td><td class="insight-entrances">' . number_format($r['entrances']) . '</td><td class="insight-goal-value">' . number_format($r['goalValueAll']) . '</td>';
    }
  }
  $rows = array();
  foreach ($ctas AS $cid => $data) {
    $row = '<td>' . number_format($data['views']['total']) . '</td>';
    $row .= '<td>' . number_format($data['clicks']['total']) . '</td>';
    if ((int)$data['views']['total']) {
      $row .= '<td>' . number_format(100 * $data['clicks']['total']/$data['views']['total'], 1) . '%</td>';
    }
    else {
      $row .= '<td>0.0%</td>';
    }
    $row .= '<td>' . number_format($data['conversions']['total']) . '</td>';
    if ((int)$data['clicks']['total']) {
      $row .= '<td>' . number_format(100 * $data['conversions']['total']/$data['clicks']['total'], 1) . '%</td>';
    }
    else {
      $row .= '<td>0.0%</td>';
    }
    $rows[$cid] = $row;
  }
  $output['rows'] = $rows;
  $output['rowcount'] = count($rows);

  drupal_json_output($output);
}