<?php
/**
 * @file
 * Administration of submission data
 *
 * @author Tom McCracken <tomm@getlevelten.com>
 */

//include_once INTEL_DIR . 'includes/class-intel-form.php';
//include_once INTEL_DIR . 'includes/intel.annotation.php';
intel_load_include('includes/class-intel-form');
intel_load_include('includes/intel.annotation');

function intel_admin_annotation_list_page() {

  global $wpdb;

  $data = array(
    100,
    0,
  );
  $sql = "
		  SELECT *
		  FROM {$wpdb->prefix}intel_annotation
      ORDER BY started DESC
      LIMIT %d OFFSET %d
		";

  $timezone_info = intel_get_timezone_info();

  $results = $wpdb->get_results( $wpdb->prepare($sql, $data) );

  $header = array(
    Intel_Df::t('Start time'),
    Intel_Df::t('Type'),
    Intel_Df::t('Summary'),
    array(
      'data' => Intel_Df::t('Score') . ' &Delta;',
      'class' => array('text-right'),
    ),

    Intel_Df::t('Ops'),
  );
  $rows = array();

  $options = array();
  $custom_default_value = '';
  $link_options = array(
    'query' => Intel_Df::drupal_get_destination(),
  );
  $link_options = array();
  $i = 0;
  foreach ($results as $row) {
    $ops = array();
    $ops[] = Intel_Df::l(Intel_Df::t('view'), 'annotation/' . $row->aid, $link_options);
    $ops[] = Intel_Df::l(Intel_Df::t('edit'), 'annotation/' . $row->aid . '/edit', $link_options);
    if (!empty($event['custom'])) {
      $ops[] = Intel_Df::l(Intel_Df::t('delete'), 'annotation/' . $row->aid . '/delete', $link_options);
    }
    else {
      //$ops[] = Intel_Df::t('NA');
    }
    $change = Intel_Df::t('NA');
    $data = unserialize($row->data);
    if (!empty($data['analytics'][0]['score'])) {
      $change = 100 * ($data['analytics'][1]['score'] - $data['analytics'][0]['score']) / $data['analytics'][0]['score'];
      $change = (($change > 0) ? '+' : '') . number_format($change, 1) . '%';
    }
    $rows[] = array(
      date("Y-m-d H:i", $row->started + intel_annotation_display_time_offset()),
      $row->type,
      intel_annotation_format_summary($row->message),
      array(
        'data' => $change,
        'class' => array('text-right'),
      ),
      //$row['type'],
      //$row['message'],
      implode(' ', $ops),
    );
    $i++;
  }

  $vars = array(
    'header' => $header,
    'rows' => $rows,
  );

  $output = Intel_Df::theme('table', $vars);

  $output .= '<div>' . Intel_Df::t('All displayed times based on %timezone timezone set in Google Analytics.', array(
      '%timezone' => $timezone_info['ga_timezone'],
    )) . '</div>';

  return $output;
}

function intel_annotation_page($annotation) {

  if (intel_is_debug()) {
    intel_d($annotation);//
  }

  if (empty($annotation->data['analytics'])) {
    $annotation = $annotation->controller->sync_ga($annotation);
  }

  $output = '';
  $build = array();

  $timezone_info = intel_get_timezone_info();

  //Intel_Df::drupal_set_title(Intel_Df::t('Annotation @title', array('@title' => $annotation->message)));
  //$form = Intel_Form::drupal_get_form('intel_admin_annotation_form', $annotation, 1);

  //$output = Intel_Df::render($form);

  $rows = array();
  $rows[] = array(
    array('data' => t('Start time'), 'header' => TRUE),
    date("m/d/Y H:i", $annotation->started + $timezone_info['offset']) . ' ' . $timezone_info['timezone_abv'] . ' - ' . $annotation->started,
  );
  $rows[] = array(
    array('data' => t('Timezone'), 'header' => TRUE),
    $timezone_info['timezone'] . ' (GMT ' . (($timezone_info['offset_hours'] >= 0) ? '+' : '') . $timezone_info['offset_hours'] . ')',
  );
  $rows[] = array(
    array('data' => Intel_Df::t('Type'), 'header' => TRUE),
    $annotation->type,
  );
  $rows[] = array(
    array('data' => Intel_Df::t('Description'), 'header' => TRUE),
    preg_replace('/\r\n|[\r\n]/', "<br />\n", $annotation->message),
  );

  $vars = array(
    'rows' => $rows
  );
  $table = Intel_Df::theme('table', $vars);

  $build['annotation_settings'] = array(
    '#theme' => 'intel_bootstrap_card',
    '#header' => Intel_Df::t('Settings'),
    '#body' => $table,
  );

  $body = '';
  $footer = '';
  if (!empty($annotation->data['analytics'])) {
    $header = array(
      '',
      array(
        'data' => 'Sessions',
        'class' => array('text-right'),
      ),
      array(
        'data' => 'Attraction score',
        'class' => array('text-right'),
      ),
      array(
        'data' => 'Engagement score',
        'class' => array('text-right'),
      ),
      array(
        'data' => 'Conversions',
        'class' => array('text-right'),
      ),
      array(
        'data' => 'Conversion score',
        'class' => array('text-right'),
      ),
      array(
        'data' => 'Total score',
        'class' => array('text-right'),
      ),
    );

    $keys = array('sessions', 'avalue', 'evalue', 'goals', 'cvalue', 'value');

    $rows = array();
    $data = array(array(), array());
    $col_decimals = array(
      0,
      2,
      2,
      0,
      2,
      2,
    );
    for ($i = 0; $i <= 1; $i++) {
      $d = $annotation->data['analytics'][$i];
      $s = $d['score_components']['_all'];
      $row = array();
      if ($i == 0) {
        $row[] = 'Before';
        //$row[] = 'Before: ' . date("m/d/Y H:i", $annotation->timestamp) . ' - ' . date("m/d/Y H:i", $annotation->timestamp + $timedelta);
      }
      else {
        $row[] = 'After';
        //$row[] = 'After: ' . date("m/d/Y H:i", $annotation->timestamp - $secinweek) . ' - ' . date("m/d/Y H:i", $annotation->timestamp + $timedelta - $secinweek);
      }
      $data[$i][] = $d['entrance']['entrances'];
      $data[$i][] = $s['attraction'];
      $data[$i][] = $s['engagement'];
      $data[$i][] = $d['entrance']['goalCompletionsAll'];
      $data[$i][] = $s['conversion'];
      $data[$i][] = $s['_all'];
      foreach ($col_decimals as $c => $cd) {
        $row[] = array(
          'data' => number_format($data[$i][$c], $cd),
          'class' => array('text-right'),
        );
      }

      $rows[] = $row;
    }

    $row = array(
      '<strong>Change</strong>',
    );
    $row2 = array(
      '% Change',
    );

    foreach ($col_decimals as $c => $cd) {
      $v = $data[1][$c] - $data[0][$c];
      $row[] = array(
        'data' => '<strong>' . ($v > 0 ? '+' : '') . number_format($v, $cd) . '</strong>',
        'class' => array('text-right'),
      );
      if ($data[0][$c] == 0) {
        $row2[] = array(
          'data' => '&infin;',
          'class' => array('text-right'),
        );
      }
      else {
        $v2 = 100 * ($v) / $data[0][$c];
        $row2[] = array(
          'data' => ($v > 0 ? '+' : '') . number_format($v2, 1) . '%',
          'class' => array('text-right'),
        );
      }

    }
    $rows[] = $row;
    $rows[] = $row2;

    $vars = array(
      'header' => $header,
      'rows' => $rows,
    );
    $body = Intel_Df::theme('table', $vars);

    $timeframe_options = intel_annotation_period_options();
    $period_hrs = $annotation->analytics_period / 3600;
    $timeframe_label = number_format(($annotation->analytics_period / 3600)) . ' hrs';
    if (isset($timeframe_options["$period_hrs"])) {
      $timeframe_label = $timeframe_options["$period_hrs"];
    }

    $footer .= Intel_Df::t('Timeframe') . ': ';
    $footer .= $timeframe_label;
    if (isset($annotation->data['analytics_timeframe'])) {
      $tf = $annotation->data['analytics_timeframe'];
      $footer .= ' (' . Intel_Df::t('Before') . ': ' . date("m/d/Y H:i", $tf[0][0] + $timezone_info['offset']) . ' - ' . date("m/d/Y H:i", $tf[0][1] + $timezone_info['offset']);
      $footer .= ', ' . Intel_Df::t('After') . ': '  . ' ' . date("m/d/Y H:i", $tf[1][0] + $timezone_info['offset']) . ' - ' . date("m/d/Y H:i", $tf[1][1] + $timezone_info['offset']) . ')';
    }
  }
  else {
    $footer .= Intel_Df::t('No analytics have been gathered on this annotation yet.');
  }

  $footer .= '<div>' . Intel_Df::l(Intel_Df::t('refresh data'), 'annotation/' . $annotation->aid . '/sync_ga') . '</div>';

  /*
  if (!empty($timezone_info['ga_timezone'])) {
    $footer .= '<div>' . Intel_Df::t('All displayed times based on %timezone timezone set in Google Analytics.', array(
        '%timezone' => $timezone_info['ga_timezone'],
      )) . '</div>';
  }
  else {
    $footer .= '<div>' . Intel_Df::t('All displayed times based on %timezone timezone.', array(
        '%timezone' => $timezone_info['timezone'],
      )) . '</div>';
  }
  */

  $build['analytics'] = array(
    '#theme' => 'intel_bootstrap_card',
    '#header' => Intel_Df::t('Analytics'),
    '#body' => $body,
    '#footer' => $footer,
  );

  return $build;


  //return $form;
  return $output;
}

function intel_admin_annotation_add_page() {

  Intel_Df::drupal_set_title(Intel_Df::t('Add new annotation'));
  //drupal_set_title(t('Add visitor attribute'));
  $form = Intel_Form::drupal_get_form('intel_admin_annotation_form');
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_annotation_edit_page($annotation) {
  Intel_Df::drupal_set_title(Intel_Df::t('Edit @title annotation', array('@title' => date("Y-m-d H:i", $annotation->timestamp))));
  $form = Intel_Form::drupal_get_form('intel_admin_annotation_form', $annotation);
  //return $form;


  return Intel_Df::render($form);
}



function intel_admin_annotation_form($form, &$form_state, $annotation = NULL, $view = 0) {

  $add = 0;
  if (empty($annotation)) {
    $annotation = intel_annotation_construct();
    $add = 1;
  }
  $form_state['add'] = $add;
  $form_state['annotation'] = $annotation;
  $timezone_info = $form_state['timezone_info'] = intel_get_timezone_info();

  $dtz = new DateTimeZone($timezone_info['ga_timezone']);
  $started = new DateTime(date('c', $annotation->started), $dtz);

  $name = 'started';
  $form[$name] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Implemented'),
    '#default_value' => date("Y-m-d H:i", $annotation->started + $timezone_info['ga_offset']) . ' ' . $timezone_info['ga_timezone_abv'],
    '#description' => Intel_Df::t('Date and time change was initiated.'),
  );
  if ($view) {
    $form[$name]['#type'] = 'item';
    $form[$name]['#markup'] = $form[$name]['#default_value'];
  }

  $name = 'type';
  $form[$name] = array(
    '#type' => 'textfield',
    '#title' => Intel_Df::t('Type'),
    '#default_value' => $annotation->type ? $annotation->type : 'custom',
    '#description' => Intel_Df::t('Classification of annotation.'),
  );
  if ($view) {
    $form[$name]['#type'] = 'item';
    $form[$name]['#markup'] = $form[$name]['#default_value'];
  }

  $name = 'message';
  $form[$name] = array(
    '#type' => 'textarea',
    '#title' => Intel_Df::t('Message'),
    '#default_value' => $annotation->message,
    '#description' => Intel_Df::t('Discription of the change.'),
  );
  if ($view) {
    $form[$name]['#type'] = 'item';
    $form[$name]['#markup'] = $form[$name]['#default_value'];
  }

  if (!$view) {
    $form['save'] = array(
      '#type' => 'submit',
      '#value' => $add ? Intel_Df::t('Add annotation') : Intel_Df::t('Save annotation'),
    );
  }

  return $form;
}

function intel_admin_annotation_form_validate(&$form, &$form_state) {
  $values = &$form_state['values'];

  $ts = strtotime($values['started']);

  if (!is_numeric($ts)) {
    $msg = Intel_Df::t('Timestamp is invalid. Please provide a timestamp in a valid format.');
    form_set_error('started', $msg);
  }
  else {
    $values['started'] = $ts;
  }
}

function intel_admin_annotation_form_submit(&$form, &$form_state) {
  $values = $form_state['values'];

  $annotation = $form_state['annotation'];

  foreach ($values as $k => $v) {
    if (isset($annotation->{$k})) {
      $annotation->{$k} = $v;
    }
  }

  $annotation->updated = REQUEST_TIME;

  intel_annotation_save($annotation);

  if (!empty($form_state['add'])) {
    $msg = Intel_Df::t('Intel annotation %title has been added.', array(
      '%title' => $annotation->timestamp,
    ));
  }
  else {
    $msg = Intel_Df::t('Intel annotation %title has been updated.', array(
      '%title' => $annotation->timestamp,
    ));
  }
  Intel_Df::drupal_set_message($msg);
  Intel_Df::drupal_goto('annotation/' . $annotation->aid);
}

function intel_admin_annotation_delete_page($event) {
  Intel_Df::drupal_set_title(Intel_DF::t('Are you sure you want to delete @title?', array('@title' => $event['title'])));
  $form = Intel_Form::drupal_get_form('intel_admin_annotation_delete_form', $event);
  //return $form;
  return Intel_Df::render($form);
}

function intel_admin_annotation_delete_form($form, &$form_state, $event) {
  $form_state['event'] = $event;
  $form['operation'] = array('#type' => 'hidden', '#value' => 'delete');
  $form['#submit'][] = 'intel_admin_annotation_delete_form_submit';
  $confirm_question = Intel_Df::t('Are you sure you want to delete the event %title?', array('%title' => $event['title']));
  return Intel_Form::confirm_form($form,
    $confirm_question,
    'admin/config/intel/settings/annotation/' . $event['key'] . '/edit',
    Intel_Df::t('This action cannot be undone.'),
    Intel_Df::t('Delete'),
    Intel_Df::t('Cancel'));
}

function intel_admin_annotation_delete_form_submit($form, &$form_state) {
  $event = $form_state['event'];
  $key = $event['key'];


  $events = get_option('intel_annotations_custom', array());
  unset($events[$key]);
  update_option('intel_annotations_custom', $events);

  $msg = Intel_Df::t('Intel event %title has been deleted.', array(
    '%title' => $event['title'],
  ));
  Intel_Df::drupal_set_message($msg);
  Intel_Df::drupal_goto('admin/config/intel/settings/annotation');
}

function intel_admin_annotation_sync_ga_page($annotation) {
  $period0 = $annotation->analytics_period;
  $available_period = intel_annotation_get_latest_available_period($annotation->started);
  $max_period = intel_annotation_get_max_period($annotation);
  $next_period = intel_get_next_available_period($annotation->started);

  if ($annotation->analytics_period >= $max_period) {
    $msg = Intel_Df::t('The analytics for this annotation are up to date with the max timeframe. No more updates are required.');
  }
  else {
    $options = array();
    $annotation = $annotation->controller->sync_ga($annotation, $options);

    if ($period0 == $annotation->analytics_period) {
      $msg = Intel_Df::t('New analytics data not available yet.');
    }
    else {
      $msg = Intel_Df::t('Annotation analytics have been updated.', array(
        '%title' => $annotation->label(),
      ));
    }

    if ((REQUEST_TIME - $annotation->started) < $max_period) {
      $next = ($annotation->started + $next_period - REQUEST_TIME) / 3600;
      if ($next < 48) {
        $msg .= ' ' . Intel_Df::t('The next scheduled update will be in %time hrs.', array(
            '%time' => number_format($next),
          ));
      }
      else {
        $msg .= ' ' . Intel_Df::t('The next scheduled update will be in %time days.', array(
            '%time' => number_format($next / 24, 1),
          ));
      }
    }
  }



  Intel_Df::drupal_set_message($msg);

  Intel_Df::drupal_goto($annotation->uri());

  return '';
}

function intel_annotation_display_time_offset() {
  $timezone_info = intel_get_timezone_info();

  return $timezone_info['ga_offset'];
}