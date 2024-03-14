<?php
/**
 * @file
 * Administration of submission data
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_submission_page($submission) {
  
  //$submission = intel_submission_load($sid);
  $visitor = intel_visitor_load((int)$submission->vid);
  //if (!empty($submission->visitorid)) {
    //$visitor = intel_visitor_load_by_visitorid($submission->visitorid);
  //}
  if (!empty($_GET['debug'])) {
    dpm($submission);//
  }
  $type = str_replace('_', ' ', $submission->type);
  drupal_set_title(t('@type submission', array('@type' => $type)), PASS_THROUGH);  
  $output = '';
  
  $form = array();
  
  // TODO encapsulate this back into hook in module or add field to store in submission table
  $link = '';
  if ($submission->type == 'webform') {
    $link = l(t('View submission details'), "node/{$submission->fid}/submission/{$submission->fsid}");
  }
  elseif ($submission->type == 'disqus_comment') {
    $link = l(t('View comment'), substr($submission->form_page_path, 1), array( 'fragment' => "comment-{$submission->fsid}"));
  }
  
  if ($link) {
    $form['data']['photo'] = array(
      '#type' => 'markup',
      '#markup' => $link . "<br>\n<br>\n",
    );
  }
  
  $form['submitted_by'] = array(
    '#type' => 'item',
    '#title' => t('Submitted by'),
    '#markup' => l($visitor->label(), $visitor->uri()),
  );
  
  $form['submitted_at'] = array(
    '#type' => 'item',
    '#title' => t('Submitted at'),
    '#markup' => date("Y-m-d H:i", $submission->submitted),
  ); 

  $url = "http://" . $submission->response_page_host . $submission->response_page_path;
  if ($submission->response_page_id) {
    $url = 'node/' . $submission->response_page_id;
  }
  $form['reponse_page'] = array(
      '#type' => 'item',
      '#title' => t('Response page'),
      '#markup' => l(url($url), $url),
  );
  
  if ($submission->form_page_path) {
    $url = "http://" . $submission->form_page_host . $submission->form_page_path;
    if ($submission->form_page_id) {
      $url = 'node/' . $submission->form_page_id;
    }
    $markup = l(url($url), $url);
  }
  else {
    $markup = '(not set)';
  }  
  $form['form_page'] = array(
    '#type' => 'item',
    '#title' => t('Form page'),
    '#markup' => $markup,
  );

  if ($submission->cta_page_path) {
    $url = "http://" . $submission->cta_page_host . $submission->cta_page_path;
    if ($submission->cta_page_id) {
      $url = 'node/' . $submission->cta_page_id;
    }
    else if ($submission->cta_page_host == $_SERVER['HTTP_HOST']) {
      $url = str_replace($GLOBALS['base_path'], '', $submission->cta_page_path);
    }
    $markup = l(url($url), $url);
  }
  else {
    $markup = '(not set)';
  }  
  $form['cta_page'] = array(
    '#type' => 'item',
    '#title' => t('CTA page'),
    '#markup' => $markup,
  ); 

  if ($submission->cta_id) {
    $url = 'block/' . $submission->cta_id;
    $markup = l(url($url), $url);
  }
  else {
    $markup = '(not set)';
  }  
  $form['cta'] = array(
    '#type' => 'item',
    '#title' => t('CTA clicked'),
    '#markup' => $markup,
  );   
  
  $output = render($form);  
  
  return $output;
}

function intel_submission_profile_page($submission, $options = array()) {
  if (empty($submission)) {
    return Intel_Df::t('Submission entry not found.');
  }

  if (empty($options['view_mode'])) {
    $options['view_mode'] = 'full';
  }

  if (!empty($_GET['view_mode'])) {
    $options['view_mode'] = $_GET['view_mode'];
  }

  if (!empty($_GET['embedded'])) {
    $options['embedded'] = $_GET['embedded'];
  }

  if (!empty($_GET['current_path'])) {
    $options['current_path'] = $_GET['current_path'];
  }




  $synced = $submission->getSynced();
  /*
  intel_d($synced);
  intel_d(time() - $synced);
  if (!empty($submission->data['analytics_session']['_lasthit'])) {
    intel_d($synced - $submission->data['analytics_session']['_lasthit']);
  }
  */
  if (empty($synced) || !empty($_GET['refresh']) || !empty($options['refresh'])) {
    $submission->syncData();
  }
  $current_path = !empty($options['current_path']) ? $options['current_path'] : Intel_Df::current_path();
  if (empty($submission->data['analytics_session']['_lasthit'])) {

    $l_options = Intel_Df::l_options_add_destination($current_path);
    $msg = Intel_Df::t('Submission session data has not yet been fully synced. !link.', array(
      '!link' => Intel_Df::l(Intel_Df::t('Click here to sync'), "submission/{$submission->get_id()}/sync", $l_options),
    ));
    Intel_Df::drupal_set_message($msg, 'warning');
  }
  elseif (
    !empty($submission->data['analytics_session']['_lasthit'])
    && (($synced - $submission->data['analytics_session']['_lasthit']) < 1800)
  ) {
    $msg = '';
    if ((time() - $submission->data['analytics_session']['_lasthit']) < 1800) {
      $msg .= Intel_Df::t('The last hit retrieved from Google Analytics was only !time minutes ago.', array(
        '!time' => floor((time() - $submission->data['analytics_session']['_lasthit'])/60),
      ));
      $msg .= ' ' . Intel_Df::t('It is recommended to allow up to 30 minutes for Google Analytics to fully prepare analytics data before sync.');
    }
    else {
      $msg .= Intel_Df::t('The last sync of Google Analytics data was !time minutes after the last processed hit.', array(
        '!time' => floor(($synced - $submission->data['analytics_session']['_lasthit'])/60),
      ));
      $msg .= ' ' . Intel_Df::t('You may want to re-sync Google Analytics data to assure all processed data has been fetched.');
    }

    $l_options = Intel_Df::l_options_add_destination($current_path);
    $l_options = Intel_Df::l_options_add_query(array('processes' => 'ga'), $l_options);
    $msg .= ' ' . Intel_Df::t('!link.', array(
        '!link' => Intel_Df::l(Intel_Df::t('Click here to sync the latest Google Analytics data for this session'), "submission/{$submission->vid}/sync", $l_options),
      ));
    Intel_Df::drupal_set_message($msg, 'warning');


  }

  /*
  $s = $submission->getSynced();
  if (!$submission->getSynced() || !empty($_GET['refresh']) || !empty($options['refresh'])) {
    $submission->syncData();
  }
  */
  $submission->build_content($submission);
  $visitor = intel()->get_entity_controller('intel_visitor')->loadOne($submission->vid);
  $visitor->build_content($visitor);

  //d($visitor->content);
  $build = $visitor->content;
  foreach ($build as $k => $v) {
    if (empty($v['#region']) || ($v['#region'] == 'sidebar')) {
      unset($build[$k]);
    }
  }
  $build = array(
    'elements' => $build,
    'view_mode' => 'half',
  );
  $profile_out = Intel_Df::theme('intel_visitor_profile', $build);

  $steps_table = '';
  if (isset($submission->data['analytics_session']['steps']) && is_array($submission->data['analytics_session']['steps'])) {
    $steps_table = Intel_Df::theme('intel_visit_steps_table', array('steps' => $submission->data['analytics_session']['steps']));
  }


  // TODO: refactor in to template
  $output = '';
  $output .= '<div class="bootstrap-wrapper intel-wrapper">';
  $output .= '<div class="intel-content submission-profile half">';
  if (!empty($options['embedded'])) {
    $messages = Intel_Df::drupal_get_messages();
    $output .= Intel_Df::theme('intel_messages', array('messages' => $messages));
    $output .= '<div class="action-links" style="margin-bottom: .5em; margin-left: .5em;">';
    $output .= Intel_Df::l(Intel_Df::t('Visitor Profile'), 'visitor/' . $submission->vid);
    $output .= ' | ' . Intel_Df::l(Intel_Df::t('Clickstream'), 'visitor/' . $submission->vid . '/clickstream');
    $output .= '</div>';

  }
  $output .= '<h4 class="card-header">' . Intel_Df::t('Visitor summary') . '</h4>';
  $output .= $profile_out;
  $output .= '<div class="card-deck-wrapper m-b-1">';
  $output .= '<div class="card-deck">';
  if (!empty($submission->data['analytics_session']['trafficsource'])) {
    $output .= Intel_Df::theme('intel_trafficsource_block', array('trafficsource' => $submission->data['analytics_session']['trafficsource']));
  }
  $output .= Intel_Df::theme('intel_location_block', array('entity' => $submission));
  $output .= Intel_Df::theme('intel_browser_environment_block', array('entity' => $submission));
  $output .= '</div>';
  $output .= '</div>';
  $output .= Intel_Df::theme('intel_visitor_profile_block', array('title' => Intel_Df::t('Visit chronology'), 'markup' => $steps_table, 'no_margin' => 1));;
  $output .= '</div>';
  $output .= '</div>';

  if (!empty($_GET['return_type']) && ($_GET['return_type'] == 'json')) {
    $response = array(
      'report' => $output,
    );
    wp_send_json($response, 200);
  }

  return $output;
}

function intel_sync_submissiondata_page($submission) {

  $output = '';

  $submission->syncData();
  $statuses = $submission->getSyncProcessStatus();
  foreach ($statuses AS $k => $v) {
    $output .= "$k: $v<br>\n";
  }



  if (!empty($_GET['destination'])) {
    Intel_Df::drupal_set_message(Intel_Df::t('Submission data synced.', array(
      '@output' => '',
    )));
    Intel_Df::drupal_goto($_GET['destination']);
    exit;
  }

  return $output;
}

function intel_admin_people_submissions($filter = array()) {
  $output = "";
  $api_level = variable_get('intel_api_level', '');
  $header = array();
  $header[] = array(
    'data' => t('Name'),
    'field' => 'v.name',
  );
  if ($api_level == 'pro') {
    $header[] = array(
      'data' => t('Location'),
    );
  }
  $header[] = array(
      'data' => t('Submitted'),
      'field' => 's.submitted',
      'sort' => 'desc',
  );
  $header[] = array(
    'data' => t('Type'),
    'field' => 's.type',
  );
  $header[] = array(
    'data' => t('Form'),
    //'field' => 's.type',
  );
  $header[] = array(
    'data' => t('Operations'),
  );

  if (empty($filter['conditions'])) {
    $filter['conditions'] = array(
      array('s.vid', 0, '!='),
    );
  }
  $options = array();
  $result = intel_submission_load_filtered($filter, $options, $header, 50);

  $hs_portal_id = variable_get('hubspot_portalid', '');
  
  $webforms = array();

  $submissions = array();
  $vids = array();
  while ($r = $result->fetchObject()) {
    $vids[$r->vid] = $r->vid;
    $submissions[$r->sid] = $r;
  }
  $vids = array_values($vids);
  $visitors = intel_visitor_load_multiple($vids);

  $rows = array();
  foreach ($submissions AS $sid => $submission) {

    // if visitor doesn't exist (anymore) skip
    if (empty($visitors[$submission->vid])) {
      continue;
    }
    $visitor = $visitors[$submission->vid];

    $row = array();
    $ops = l(t('meta'), 'submission/' . $submission->sid);
    $row[] = $visitor->label_link();
    if ($api_level == 'pro') {      
      $row[] = $visitor->location();
    }
    $row[] = ((REQUEST_TIME - $submission->submitted) > 604800) ? format_date($submission->submitted, 'short') : format_interval(REQUEST_TIME - $submission->submitted) . t(' ago');
    $row[] = $submission->type;
    if ($submission->type == 'webform') {
      if (!isset($webforms[$submission->fid])) {
        $webform[$submission->fid] = node_load($submission->fid);
      }
      $row[] = l($webform[$submission->fid]->title, 'node/' . $submission->fid);
      $ops .= ' | ' . l(t('submission'), 'node/' . $submission->fid . '/submission/' . $submission->fsid);
    }
    else if ($submission->type == 'disqus_comment') {
      $a = explode('#', substr($submission->details_url, 1));
      $options = array(
        'fragment' => isset($a[1]) ? $a[1] : '',
      );
      $row[] = l(t('Comment'), $a[0], $options);
      $ops .= ' | ' . l(t('comment'), $a[0], $options);
    }
    else if ($submission->type == 'hubspot') {
      $form_name = intel_hubspot_get_form_name($submission->fid);
      $row[] = ($form_name) ? $form_name : 'NA';
    }
    else {
      $row[] = 'NA';
    }
    $row[] = $ops;
    $rows[] = $row;
  }

  $vars = array(
    'header' => $header, 
    'rows' => $rows, 
  );
  $output .= theme('table', $vars);
  //pager_default_initialize($total, 1, $element = 0);
  $output .= theme('pager');

  return $output;
}