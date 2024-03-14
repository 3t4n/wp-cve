<?php
/**
 * @file
 * utility to help fiddle with Google Analytics API requests
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_ga_explorer_report($form) {
  require_once INTEL_DIR . "includes/intel.annotation.php";

  $request = get_option('intel_ga_explorer_request', '');
  $options = array('' => Intel_Df::t('- None -'));
  $presets = intel_ga_explorer_presets();
  foreach ($presets AS $name => $arr) {
    $options[$name] = $arr['title'];
  }

  $form['markup00'] = array(
    '#type' => 'markup',
    '#markup' => '<div class="panel panel-default"><h3 class="panel-heading">' . Intel_Df::t('Request form') . '</h3><div class="panel-body">',
  );

  $form['preset'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Preset'),
    '#options' => $options,
    '#default_value' => (isset($request['preset_name'])) ? $request['preset_name']: '',
  );
  if (!empty($request['preset_name'])) {
    $items = array();
    $preset = $presets[$request['preset_name']];
    unset($preset['title']);
    foreach ($preset AS $key => $value) {
      $item = $key . ': ';
      if (is_array($value)) {
        $item .= implode(',', $value);
      }
      else {
        $item .= $value;
      }
      $items[] = $item;
    }
    $form['preset_data'] = array(
      '#type' => 'item',
      '#title' => Intel_Df::t('Preset'),
      '#markup' => implode("<br />", $items),
    );
  }


  $form['markup1'] = array(
    '#type' => 'markup',
    '#markup' => '<div class="row"><div class="col-md-7">',
  );
  $form['dimensions'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('dimensions'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#default_value' => (isset($request['custom']['dimensions'])) ? implode(',', $request['custom']['dimensions']) : '',
    '#maxlength' => 200,
   );
  $form['metrics'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('metrics'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#default_value' => (isset($request['custom']['metrics'])) ? implode(',', $request['custom']['metrics']) : '',
    '#maxlength' => 200,
  );
  $form['segment'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('segment'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#default_value' => (isset($request['custom']['segment'])) ? $request['custom']['segment'] : '',
    '#rows' => 2,
  );
  $form['filters'] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('filters'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#default_value' => (isset($request['custom']['filters'])) ? $request['custom']['filters'] : '',
    '#rows' => 2,
  );
  $form['sort_metric'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('sort'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#default_value' => (isset($request['custom']['sort_metric'])) ? $request['custom']['sort_metric'] : '',
  );
  $form['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => Intel_Df::t('Advanced'),
    '#collapsable' => 1,
    '#collapsed' => 0,
  );
  $form['advanced']['annotation_launched'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Annotation launched'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#default_value' => (isset($request['custom']['annotation_launched'])) ? $request['custom']['annotation_launched'] : '',
  );
  $options = intel_annotation_period_options();
  $form['advanced']['annotation_timeframe'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Annotation timeframe'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#options' => $options,
    '#default_value' => (isset($request['custom']['annotation_timeframe'])) ? $request['custom']['annotation_timeframe'] : '',
  );
  $options = array(
    'a' => Intel_Df::t('After'),
    'b' => Intel_Df::t('Before'),
  );
  $form['advanced']['annotation_variant'] = array(
    '#type' => 'select',
    '#title' => Intel_Df::t('Annotation variant'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#options' => $options,
    '#default_value' => (isset($request['custom']['annotation_variant'])) ? $request['custom']['annotation_variant'] : '',
  );
  $form['markup2'] = array(
    '#type' => 'markup',
    '#markup' => '</div><div class="col-md-5">',
  );
  $form['start_date'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('start date'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#size' => 16,
    '#default_value' => (isset($request['custom']['start_date'])) ? Date("Y-m-d", $request['custom']['start_date']) : Date("Y-m-d", strtotime("-7 days")),
  );
  $form['end_date'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('end date'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#size' => 16,
    '#default_value' => (isset($request['custom']['end_date'])) ? Date("Y-m-d", $request['custom']['end_date']) : Date("Y-m-d"),
  );
  $form['max_results'] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('max results'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#size' => 16,
    '#default_value' => (isset($request['custom']['max_results'])) ? $request['custom']['max_results'] : 50,
  );

  $form['realtime_mode'] = array(
    '#type' => 'checkbox',
    '#title' => Intel_Df::t('Realtime API'),
    //'#description' => Intel_Df::t('Enter a value to assign to the event when the CTA is clicked. Must be an whole number.'),
    '#default_value' => (isset($request['realtime_mode'])) ? $request['realtime_mode'] : '',
  );
  
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Submit',
  );
  $items = array();
  $items[] = Intel_Df::l(Intel_Df::t('GA Query Explorer'), 'https://ga-dev-tools.appspot.com/query-explorer');
  $items[] = Intel_Df::l(Intel_Df::t('GA Dimensions & Metrics Explorer'), 'https://ga-dev-tools.appspot.com/dimensions-metrics-explorer/');
  $form['markup_reference'] = array(
    '#type' => 'markup',
    '#markup' => '<br><br><h4>' . Intel_Df::t('Tools &amp; Reference') . '</h4>' . Intel_Df::theme('item_list', array('items' => $items)),
  );
  $form['markup3'] = array(
    '#type' => 'markup',
    '#markup' => '</div></div>',
  );
  $form['markup01'] = array(
    '#type' => 'markup',
    '#markup' => '</div></div>',
  );
  if (!empty($request)) {
    //$req = drupal_array_merge_deep($request['preset'], $request['custom']);
    $def = array();

    $req = $request['preset'] + $request['custom'];
    $req['dimensions'] = !empty($request['preset']['dimensions']) ? $request['preset']['dimensions'] : array();
    if (!empty($request['custom']['dimensions'])) {
      $req['dimensions'] = array_merge($req['dimensions'], $request['custom']['dimensions']);
    }
    $req['metrics'] = !empty($request['preset']['metrics']) ? $request['preset']['metrics'] : array();
    if (!empty($request['custom']['metrics'])) {
      $req['metrics'] = array_merge($req['metrics'], $request['custom']['metrics']);
    }

    if (!empty($req['annotation_launched'])) {
      $timeframe = (int)$req['annotation_timeframe'];

      $timestamp_a0 = strtotime($req['annotation_launched']);
      $timestamp_b0 = $timestamp_a0 - (60 * 60 * 168);
      if ($timeframe > 168) {
        $timestamp_b0 = $timestamp_a0 - (60 * 60 * $timeframe);
      }

      $timestamp_b0 = $timestamp_a0 - (60 * 60 * 168);
      if ($timeframe > 168) {
        $timestamp_b0 = $timestamp_a0 - (60 * 60 * $timeframe);
      }

      $timestamp_a1 = $timestamp_a0 + (60 * 60 * $timeframe);
      $timestamp_b1 = $timestamp_b0 + (60 * 60 * $timeframe);

      if ($req['annotation_variant'] == 'b') {
        $dates = _intel_get_report_dates(date('YmdHi', $timestamp_b0), date('YmdHi', $timestamp_b1), 1);
      }
      else {
        $dates = _intel_get_report_dates(date('YmdHi', $timestamp_a0), date('YmdHi', $timestamp_a1), 1);
      }

      $dates['ga_start_date_hour_minute'] = date('YmdHi', $dates['ga_start_date']);
      $dates['ga_start_date_readable'] = date('D m/d/Y H:i', $dates['ga_start_date']);
      $dates['ga_end_date_hour_minute'] = date('YmdHi', $dates['ga_end_date']);
      $dates['ga_end_date_readable'] = date('D m/d/Y H:i', $dates['ga_end_date']);
      $tzi = $dates['timezone_info'];

      //intel_d("Annotation datetime: {$dates['ga_start_date_readable']} ({$tzi['ga_timezone_abv']}) - {$dates['ga_end_date_readable']} ({$tzi['ga_timezone_abv']})");

      if (!isset($req['filters'])) {
        $req['filters'] = '';
      }
      $req['filters'] .= (!empty($req['filters']) ? ';' : '') . LevelTen\Intel\GAModel::formatGtRegexFilter('ga:dateHourMinute', date('ymdHi', $dates['ga_start_date']), '', array('fixed_width' => 1, 'prefix' => '20'));
      $req['filters'] .= (!empty($req['filters']) ? ';' : '') . LevelTen\Intel\GAModel::formatLtRegexFilter('ga:dateHourMinute', date('ymdHi', $dates['ga_end_date']), '', array('fixed_width' => 1, 'prefix' => '20'));

    }

    if (intel_is_debug()) {
      intel_d($req);//
    }

    if (!empty($req['metrics']) || !empty($req['dimensions'])) {
      $items = array(
        'dimensions: ' . implode(',', $req['dimensions']),
        'metrics: ' . implode(',', $req['metrics']),
        'segment: ' . $req['segment'],
        'filters: ' . $req['filters'],
        'sort: ' . $req['sort_metric'],
        'start_date: ' . $req['start_date'],
        'end_date: ' . $req['end_date'],
        'max_results: ' . $req['max_results'],
      );
      $form['report_req'] = array(
        '#type' => 'markup',
        '#markup' => '<br><div class="panel panel-default"><h3 class="panel-heading">Request</h3><div class="panel-body">' . implode("<br>\n", $items) . '</div></div>',
      );

      if (!empty($request['realtime_mode'])) {
        $options = array('realtime' => TRUE);
      }
      else {
        $options = array('refresh' => TRUE);
      }

      //$data = google_analytics_reports_api_report_data($req, $options);
      $data = intel_ga_api_data($req, $options);

      if (intel_is_debug()) {
        intel_d($data);//
      }

      $header = array();
      $rows = array();
      $data_rows = intel_get_ga_feed_rows($data);
      $cols = array();
      intel_d($data_rows);//
      if (!empty($data_rows) && is_array($data_rows)) {
        foreach ($data_rows AS $r) {
          $row = array();
          if (!count($header)) {
            foreach ($r AS $k => $e) {
              $header[] = $k;
            }
          }
          foreach ($r AS $k => $e) {
            $row[] = $e;
          }
          $rows[] = $row;
        }
      }
      $row = array();
      $data_totals = intel_get_ga_feed_totals($data);
      foreach ($header as $r) {
        $row[] = isset($data_totals[$r]) ? "<strong>" . $data_totals[$r] . "</strong>" : '';
      }

      $rows[] = $row;
      $vars = array(
        'header' => $header,
        'rows' => $rows,
        'empty' => Intel_Df::t('No results found.'),
      );
      $form['report'] = array(
        '#type' => 'markup',
        '#markup' => '<div class="panel panel-default"><h3 class="panel-heading">Results</h3><div>' . Intel_Df::theme('table', $vars) . '</div></div>',
      );
    }
  }

  return $form;
}

function intel_ga_explorer_report_validate($form, &$form_state) {
  $values = &$form_state['values'];

  $ts = strtotime($values['annotation_launched']);

  if (!is_numeric($ts)) {
    $msg = Intel_Df::t('Annotation launced is invalid. Please provide a time in a valid format.');
    form_set_error('annotation_launched', $msg);
  }
}

function intel_ga_explorer_report_submit($form, &$form_state) {
  $values = $form_state['values'];

  $presets = intel_ga_explorer_presets();
  $request = array(
    'preset' => array(),
    'custom' => array(),
    'preset_name' => '',
  );
  if (!empty($values['preset'])) {
    $request['preset_name'] = $values['preset'];
    $request['preset'] = $presets[$values['preset']];
    unset($request['preset']['title']);
  }

  $request['custom'] = array(
    'dimensions' => array(),
    'metrics' => array(),
    'segment' => '',
    'filters' => '',
    'sort_metric' => '',
    'start_date' => '',
    'end_date' => '',
    'max_results' => '',
    'annotation_launched' => '',
    'annotation_timeframe' => '',
    'annotation_variant' => '',
  );
  $d = $values['dimensions'];
  $d = str_replace("'", '', $d);
  $d = explode(',', $d);
  foreach ($d AS $i => $e) {
    if (!empty($e)) {
      $request['custom']['dimensions'][] = trim($e);
    }
  }
  $d = $values['metrics'];
  $d = str_replace("'", '', $d);
  $d = explode(',', $d);
  foreach ($d AS $i => $e) {
    if (!empty($e)) {
      $request['custom']['metrics'][] = trim($e);
    }
  }
  $request['custom']['segment'] = str_replace("'", '', $values['segment']);
  $request['custom']['filters'] = str_replace("'", '', $values['filters']);
  $request['custom']['sort_metric'] = str_replace("'", '', $values['sort_metric']);
  $request['custom']['start_date'] = strtotime(str_replace("'", '', $values['start_date']));
  $request['custom']['end_date'] = strtotime(str_replace("'", '', $values['end_date']));
  $request['custom']['max_results'] = str_replace("'", '', $values['max_results']);
  $request['custom']['annotation_launched'] = str_replace("'", '', $values['annotation_launched']);
  $request['custom']['annotation_timeframe'] = str_replace("'", '', $values['annotation_timeframe']);
  $request['custom']['annotation_variant'] = str_replace("'", '', $values['annotation_variant']);
  $request['realtime_mode'] = str_replace("'", '', $values['realtime_mode']);

  update_option('intel_ga_explorer_request', $request);
}

function intel_ga_explorer_presets() {

  intel_include_library_file('ga/class.ga_model.php');

  $presets = array();

  // standard pageviews metrics w/o dimensions
  $presets['pageviews'] = array(
    'title' => Intel_Df::t('Pageviews (no dimensions)'),
    'metrics' => array('ga:pageviews', 'ga:uniquePageviews', 'ga:pageviewsPerSession', 'ga:timeOnPage', 'ga:exits', 'ga:goalValueAll', 'ga:goalCompletionsAll', 'ga:pageValue'),
  );

  // note goalValues lost when pagePath dimension used, calc goalValueAll using pageValue * uniquePageviews
  // using filter: ga:pagePath=@/ used to correct this, but no longer works
  $presets['pageviews_content_list'] = array(
    'title' => Intel_Df::t('Pageviews > content list'),
    'dimensions' => array('ga:pagePath'),
    'metrics' => array('ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnPage', 'ga:exits', 'ga:goalValueAll', 'ga:goalCompletionsAll', 'ga:pageValue'),
  );

  // standard valued events metrics w/o dimensions
  $presets['pageviews_events'] = array(
    'title' => Intel_Df::t('Pageviews Events (no dimensions)'),
    'metrics' => array('ga:totalEvents', 'ga:uniqueEvents', 'ga:eventValue'),
    'sort' => '-ga:totalEvents',
  );

  $presets['pageviews_events_content_list'] = array(
      'title' => Intel_Df::t('Pageviews Events > content list'),
      'dimensions' => array('ga:pagePath'),
    ) + $presets['pageviews_events'];

  $presets['pageviews_events_content_event_list'] = array(
      'title' => Intel_Df::t('Pageviews Events > content & event list'),
      'dimensions' => array('ga:pagePath,ga:eventCategory'),
    ) + $presets['pageviews_events'];

  // standard valued events metrics w/o dimensions
  $presets['pageviews_events_valued'] = array(
    'title' => Intel_Df::t('Pageviews Events Valued (no dimensions)'),
    'metrics' => array('ga:totalEvents', 'ga:uniqueEvents', 'ga:eventValue', 'ga:metric2'),
    'filters' => 'ga:eventCategory=~!$',
    'sort' => '-ga:metric2,-ga:totalEvents',
  );

  $presets['pageviews_events_valued_content_list'] = array(
    'title' => Intel_Df::t('Pageviews Events Valued > content list'),
    'dimensions' => array('ga:pagePath'),
  ) + $presets['pageviews_events_valued'];

  $presets['pageviews_events_valued_content_event_list'] = array(
    'title' => Intel_Df::t('Pageviews Events Valued > content & event list'),
    'dimensions' => array('ga:pagePath,ga:eventCategory'),
  ) + $presets['pageviews_events_valued'];


  // goalValues not reported when pagePath filter used
  $presets['pageviews_content_filter'] = array(
    'title' => Intel_Df::t('Pageviews > filter content type = post'),
    'metrics' => array('ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnPage', 'ga:exits', 'ga:goalValueAll', 'ga:goalCompletionsAll', 'ga:pageValue'),
    'filters' => 'ga:dimension6=@&rt2=post',
  );

  $presets['pageviews_content_list_pa'] = array(
    'title' => Intel_Df::t('Pageviews > content list > pa filter'),
    'dimensions' => array('ga:pagePath'),
    'metrics' => array('ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnPage', 'ga:exits', 'ga:goalValueAll', 'ga:goalCompletionsAll', 'ga:pageValue'),
    'filters' => 'ga:dimension1=@&a=1&'
  );

  // goalValue provides in seg completed goals numbers, ? on any goal
  // estimate pageValueAll
  $presets['pageviews_pa_list'] = array(
    'title' => Intel_Df::t('Pageviews > pa  list'),
    'dimensions' => array('ga:customVarValue1'),
    'metrics' => array('ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnPage', 'ga:exits', 'ga:goalValueAll', 'ga:goalCompletionsAll', 'ga:pageValue'),
    //'filters' => 'ga:customVarValue1=@&a='
  );

  $presets['pageviews_pa_list2'] = array(
    'title' => Intel_Df::t('Pageviews > pa  list2'),
    'dimensions' => array('ga:customVarValue1', 'ga:goalCompletionLocation'),
    'metrics' => array('ga:pageviews', 'ga:uniquePageviews', 'ga:pageviewsPerSession', 'ga:timeOnPage', 'ga:exits', 'ga:pageValue', 'ga:goalValueAll', 'ga:goalValuePerSession'),
    //'filters' => 'ga:customVarValue1=@&a=6&'
  );

  // - cannot sum pageValue for totals
  $presets['pageviews_pa_list'] = array(
    'title' => Intel_Df::t('Pageviews > pa  list'),
    'dimensions' => array('ga:customVarValue1'),
    'metrics' => array('ga:pageviews', 'ga:uniquePageviews', 'ga:pageviewsPerSession', 'ga:timeOnPage', 'ga:exits', 'ga:pageValue', 'ga:goalValueAll', 'ga:goalCompletionsAll'),
    //'filters' => 'ga:customVarValue1=@&a=6&'
  );


  // standard entrance (downstream metrics) metrics w/o dimensions
  $presets['entrances'] = array(
    'title' => Intel_Df::t('Entrances (no dimensions)'),
    'metrics' => array('ga:entrances', 'ga:pageviews', 'ga:uniquePageviews', 'ga:timeOnPage', 'ga:bounces', 'ga:goalValueAll', 'ga:goalCompletionsAll'),
  );

  // note goalValues lost when pagePath dimension used, calc goalValueAll using pageValue * uniquePageviews
  // using filter: ga:pagePath=@/ used to correct this, but no longer works
  $presets['entrances_content_list'] = array(
    'title' => Intel_Df::t('Entrances > content list'),
    'dimensions' => array('ga:landingPagePath'),
    'metrics' => array('ga:entrances', 'ga:pageviewsPerSession', 'ga:timeOnPage', 'ga:bounces', 'ga:goalValueAll', 'ga:goalCompletionsAll'),
  );

  $presets['entrances_events'] = array(
    'title' => Intel_Df::t('Entrances Events (no dimensions)'),
    'metrics' => array('ga:totalEvents', 'ga:uniqueEvents', 'ga:eventValue'),
    'sort' => '-ga:totalEvents',
  );

  $presets['entrances_events_content_list'] = array(
      'title' => Intel_Df::t('Entrances Events > content list'),
      'dimensions' => array('ga:landingPagePath'),
    ) + $presets['entrances_events'];

  $presets['entrances_events_content_event_list'] = array(
      'title' => Intel_Df::t('Entrances Events > content, event list'),
      'dimensions' => array('ga:landingPagePath,ga:eventCategory'),
    ) + $presets['entrances_events'];

  // standard valued events metrics w/o dimensions
  $presets['entrances_events_valued'] = array(
    'title' => Intel_Df::t('Entrances Events Valued (no dimensions)'),
    'metrics' => array('ga:totalEvents', 'ga:uniqueEvents', 'ga:eventValue', 'ga:metric2'),
    'filters' => 'ga:eventCategory=~!$',
    'sort' => '-ga:metric2,-ga:totalEvents',
  );

  $presets['entrances_events_valued_content_list'] = array(
      'title' => Intel_Df::t('Entrances Events Valued > content list'),
      'dimensions' => array('ga:landingPagePath'),
    ) + $presets['entrances_events_valued'];

  $presets['entrances_events_valued_content_event_list'] = array(
      'title' => Intel_Df::t('Entrances Events Valued > content, event list'),
      'dimensions' => array('ga:landingPagePath,ga:eventCategory'),
    ) + $presets['entrances_events_valued'];

  // standard visigtor (downstream metrics) metrics w/o dimensions
  $presets['visitors'] = array(
    'title' => Intel_Df::t('Visitors (no dimensions)'),
    'metrics' => array('ga:entrances', 'ga:pageviewsPerSession', 'ga:timeOnPage', 'ga:bounces', 'ga:goalValueAll', 'ga:goalCompletionsAll'),
  );

  // standard visigtor (downstream metrics) metrics w/o dimensions
  $presets['visitors_visitor'] = array(
    'title' => Intel_Df::t('Visitors > visitor list'),
    'dimensions' => array('ga:dimension5'),
    'metrics' => array('ga:entrances', 'ga:pageviewsPerSession', 'ga:timeOnPage', 'ga:bounces', 'ga:goalValueAll', 'ga:goalCompletionsAll'),
  );


  $presets['entrances_works'] = array(
    'title' => Intel_Df::t('Entrances (landingPagePath)'),
    'dimensions' => array('ga:landingPagePath'),
    'metrics' => array('ga:entrances', 'ga:newVisits', 'ga:pageviewsPerSession', 'ga:timeOnSite', 'ga:bounces', 'ga:goalValueAll', 'ga:goalCompletionsAll'),
  );


  $presets['pageviews_pageattr_list'] = array(
    'title' => Intel_Df::t('Pageviews (Page Attr list)'),
    'dimensions' => array('ga:customVarValue1'),
    'metrics' => array('ga:pageviews', 'ga:pageviewsPerSession', 'ga:timeOnPage', 'ga:exits', 'ga:goalValueAll', 'ga:goalCompletionsAll'),
    //'segment' => 'dynamic::ga:customVarValue1=@&a=6&',
    //'filters' => 'ga:customVarValue1=@&og=53&',
  );

  // ?goalValueAll = ga:entrances x ga:pageValue
  // filter: ga:customVarValue1=@&og=53&;entrances>0
  /*
  $presets['entrances_pageattr_list'] = array(
    'title' => Intel_Df::t('Entrances (Page Attr list)'),
    'dimensions' => array('ga:landingPagePath', 'ga:customVarValue1'),
    'metrics' => array('ga:entrances', 'ga:newVisits', 'ga:pageviewsPerSession', 'ga:timeOnSite', 'ga:bounces', 'ga:pageValue', 'ga:goalValueAll', 'ga:goalCompletionsAll'),
    //'filters' => 'ga:entrances>0',
    'filters' => 'ga:customVarValue1=@&og=53&;ga:entrances>0',
  );
  */



  // this works, but as soon as you add ga:customVarValue1 it breaks. Adding ga:customVarValue1 makes pageviews revert to hit mode
  $presets['entrances_pa_list'] = array(
    'title' => Intel_Df::t('Entrances  > pa  list'),
    'dimensions' => array('ga:landingPagePath'),
    //'dimensions' => array('ga:landingPagePath', 'ga:customVarValue1'),
    'metrics' => array('ga:entrances', 'ga:newVisits', 'ga:pageviewsPerSession', 'ga:timeOnSite', 'ga:bounces', 'ga:pageValue', 'ga:goalValueAll', 'ga:goalCompletionsAll'),
    //'segment' => 'sessions::sequence::^ga:customVarValue1=@&a=',
    //'filters' => 'ga:customVarValue1=@&a=6&;ga:entrances>0',
  );



  // ?goalValueAll = ga:entrances x ga:pageValue
  // filter: ga:customVarValue1=@&og=53&;entrances>0
  $presets['entrances_scorecard_date_pageattr'] = array(
    'title' => Intel_Df::t('Entrances Scorecard Date (filter: Page Attr)'),
    'dimensions' => array('ga:date'),
    'metrics' => array('ga:entrances', 'ga:newVisits', 'ga:pageviewsPerSession', 'ga:timeOnPage', 'ga:bounces', 'ga:pageValue', 'ga:goalValueAll', 'ga:goalCompletionsAll'),
    //'filters' => 'ga:entrances>0',
    'filters' => 'ga:customVarValue1=@&og=53&;ga:entrances>0',
  );


  $presets['clickstream'] = array(
    'title' => Intel_Df::t('Clickstream'),
    'dimensions' => array('ga:customVarValue5', 'ga:sessionCount', 'ga:customVarValue4'),
    'metrics' => array('ga:entrances', 'ga:pageviews', 'ga:goalValueAll', 'ga:goalCompletionsAll', 'ga:uniquePageviews', 'ga:pageValue'),
    'sort_metric' => 'ga:customVarValue5,ga:customVarValue4',
  );

  $presets['realtime_activeusers'] = array(
    'title' => Intel_Df::t('Realtime traffic source'),
    'dimensions' => array('rt:pagePath'),
    'metrics' => array('rt:activeUsers'),
  );

  $presets['realtime_events'] = array(
    'title' => Intel_Df::t('Realtime events'),
    'dimensions' => array('rt:eventCategory', 'rt:eventAction'),
    'metrics' => array('rt:totalEvents'),
  );

  return $presets;
}

