<?php
/**
 * @file
 * Support for syncing visitor data across various data sources
 *
 * @author Tom McCracken <tomm@getlevelten.com>
 */



function x_intel_sync_visitordata($visitor, $request = array()) {
  if (!empty($_GET['debug'])) {
    dpm('visitor0');//
    dpm($visitor);//
  }
  Intel_Df::watchdog('intel_sync_visitordata: vtk', implode(', ', $visitor->identifiers['vtk']));
  $l10api_synced = FALSE;

  // don't sync if data is not visitor available in IAPI
  $api_level = intel_api_level();
  if ($api_level != 'pro') {
    $visitor->setSyncProcessStatus('na', 1);
    return $visitor;
  }

  // sync google analytics data
  $visitor = intel_ga_sync_visitordata($visitor);

  // if email is set, run l10iapi sync
  if ($visitor->getEmail()) {
    $visitor = intel_l10iapi_sync_visitordata($visitor);
    $l10api_synced = TRUE;
  }

  // initial data gathering stage
  $visitor = apply_filters('intel_sync_visitor', $visitor, $request);

  // alter initial data gathering
  $visitor = apply_filters('intel_sync_visitor_alter', $visitor, $request);

  // data save stage
  $visitor = apply_filters('intel_sync_visitor_save', $visitor, $request);

  if (!$l10api_synced && $visitor->getEmail()) {
    $visitor = intel_l10iapi_sync_visitordata($visitor);
  }

  return $visitor;
}



/**
 * Adds visitors to DrupalQueue
 */
function intel_queue_sync_visitor_requests() {
  // get existing sync requests
  $items = get_option('intel_sync_visitor_requests', array());
  //$msg = Intel_Df::t('requests') . '0: ' . count($items);

  // add them to the DrupalQueue
  if (count($items)) {
    $queue = IntelQueue::get('intel_sync_visitor_requests');
    $c = 0;
    $limit = 20;
    foreach ($items AS $i => $item) {
      if ($item->run_after < time()) {
        if (isset($item->vtk)) {
          //$msg .= ($c ? ', ' : '') . substr($item->vtk, 0, 10);
        }
        $queue->createItem($item, $item->vtk);
        // remove items from request variable. If sync fails they will be re-added
        // in intel_sync_visitor_request_worker
        unset($items[$i]);
        // limit number of visitors processed in a single cron run
        if ($c++ >= $limit) {
          break;
        }
      }
    }
    update_option('intel_sync_visitor_requests', $items);
  }
  //$msg .= "<br>\n" . Intel_Df::t('requests') . '1: ' . count($items);
  //Intel_Df::watchdog('intel_queue_sync_visitor', $msg);
}

// alternate way to work sync_visitor_request queue
function intel_work_sync_visitor_request_queue($limit = 10, $runtime = 60) {

  $end = time() + $runtime;
  $queue = IntelQueue::get('intel_sync_visitor_requests');
  $items = array();
  $i = 0;
  while (($i < $limit) && (time() < $end) && ($item = $queue->claimItem())) {
    if (!empty($_GET['debug'])) {
      dpm('syncing from queue:');//
      dpm($item);//
    }
    $items[] = intel_sync_visitor_request_worker($item->data);
    $queue->deleteItem($item);
    $i++;
  }
  return $items;
}

add_filter( 'intel_sync_visitor', 'intel_intel_sync_visitor', 5, 2);
function intel_intel_sync_visitor(IntelVisitor $visitor, $options = array()) {
  $visitor = intel_ga_sync_visitor($visitor, $options);
  //$visitor = intel_l10iapi_sync_visitordata($visitor, $options);

  return $visitor;
}

//add_filter( 'intel_sync_visitor', 'intel_ga_sync_visitordata', 5, 2);
/**
 * Implements intel_sync_visitordata
 * @param unknown_type $visitor
 */
function intel_ga_sync_visitor($visitor, $options = array()) {
//$args = func_get_args();
//Intel_Df::watchdog('intel_ga_sync_visitordata', 'args', $args);
  if (!empty($options['processes']) && !in_array('ga', $options['processes'])) {
    return $visitor;
  }

  intel_load_include('includes/intel.ga');

  $vtkids = array();

  if (!empty($visitor->identifiers['vtk']) && is_array($visitor->identifiers['vtk'])) {
    foreach ($visitor->identifiers['vtk'] AS $vtk) {
      $vtkids[] = substr($vtk, 0, 20);
    }
  }

  // if no vtkids, return true to unset future requests
  if (empty($vtkids)) {
    $visitor->setSyncProcessStatus('ga', 1);
    return $visitor;
  }

  $gadata = intel_fetch_analytics_visitor_meta_data($vtkids);
  if (!empty($_GET['debug'])) {
    dpm('gadata'); dpm($gadata);//
  }
  if (empty($gadata)) {
    $visitor->setSyncProcessStatus('ga', 0);
    return $visitor;
  }
  if (isset($gadata['location'])) {
    $addthis_geo = $visitor->getVar('api_visitor', 'addthis', 'geo');
    if ($addthis_geo) {
      $gadata['location']['country_code'] = isset($addthis_geo['country']) ? $addthis_geo['country'] : '';
      $gadata['location']['region_code'] = isset($addthis_geo['region']) ? $addthis_geo['region'] : '';
      $gadata['location']['zip'] = isset($addthis_geo['zip']) ? $addthis_geo['zip'] : '';
    }
  }

  $gadata['visits']['_lasthit'] = !empty($gadata['lasthit']) ? $gadata['lasthit'] : 0;

  $visitor->setVar('data', 'location', $gadata['location']);
  $visitor->setVar('data', 'environment', $gadata['environment']);
  $visitor->setVar('data', 'analytics_visits', $gadata['visits']);
  $visitor->setVar('ext', 'ga', $gadata);
  $visitor->merge();

  $visitor->setSyncProcessStatus('ga', 1);
  return $visitor;
}

function intel_calculate_distance($lat1, $lon1, $lat2, $lon2, $miles = TRUE) {
  $pi80 = M_PI / 180;
  $lat1 *= $pi80;
  $lon1 *= $pi80;
  $lat2 *= $pi80;
  $lon2 *= $pi80;

  $r = 6372.797; // mean radius of Earth in km
  $dlat = $lat2 - $lat1;
  $dlon = $lon2 - $lon1;
  $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
  $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
  $km = $r * $c;

  return ($miles ? ($km * 0.621371192) : $km);
}

function intel_sync_fullcontact_page($vtk) {
  $visitor = intel_visitor_load($vtk, 1);
  $status = intel_l10iapi_sync_visitordata($visitor);

  $output = Intel_Df::t('status:') . ' ' . $status;

  return $output;
}

//add_filter( 'intel_sync_visitor', 'intel_l10iapi_sync_visitordata', 10, 2);
function intel_l10iapi_sync_visitordata($visitor, $options = array()) {
  if (!empty($options['processes']) && !in_array('l10iapi', $options['processes'])) {
    return $visitor;
  }
  $status = 1;

  if (
    intel_api_level('pro')
    && get_option('intel_sync_visitordata_fullcontact', INTEL_SYNC_VISITORDATA_FULLCONTACT_DEFAULT)
    && $visitor->getVar('data', 'settings', 'sync_visitordata.fullcontact', INTEL_SYNC_VISITORDATA_FULLCONTACT_DEFAULT)
    && $visitor->getEmail()
  ) {
    $person = $visitor->apiPersonLoad();
    $fc_data = $visitor->getVar('api_person_fullcontact');

    if (!empty($_GET['debug'])) {
      intel_d('FullContact person:'); intel_d($fc_data);//
    }

    if (empty($fc_data)) {
      $fc_data = array(
        'status' => 0,
      );
    }

    if ($fc_data['status'] == 200) {
      intel_sync_fullcontact_visitordata($visitor, $fc_data);
      $visitor->setVar('ext', 'fullcontact', $fc_data);
      $visitor->merge();
      $visitor->clearSyncResult('l10iapi');
    }
    else {
      $sync_result = $visitor->getSyncResult('l10iapi');
      // if more than three attempts, just mark as synced
      if (count($sync_result) >= 2) {
        $visitor->clearSyncResult('l10iapi');
        $status = 1;
      }
      // if status is 202, (queuing request for search), set status=0 to re queue
      elseif($fc_data['status'] == 202) {
        $visitor->addSyncResult('l10iapi', $fc_data['message'], $fc_data['status']);
        $status = 0;
      }
      else {
        $visitor->addSyncResult('l10iapi', $fc_data['message'], $fc_data['status']);
      }
    }
  }
  $visitor->setSyncProcessStatus('l10iapi', $status);
  return $visitor;
}

function intel_sync_fullcontact_visitordata(&$visitor, $data) {
  if (empty($data['status']) || ($data['status'] != 200)) {
    return TRUE;
  }
  // only use data if likelihood of match is above threshold
  if ($data['likelihood'] < .80) {
    return TRUE;
  }
  $prop_options = array(
    'source' => 'fullcontact',
  );
  $bio = '';
  $klout = '';
  if (!empty($data['photos']) && is_array($data['photos'])) {
    foreach ($data['photos'] AS $i => $v) {
      if (!empty($v['isPrimary'])) {
        $visitor->setProp('image', $v, $prop_options);
      }
    }
  }
  if (!empty($data['socialProfiles']) && is_array($data['socialProfiles'])) {
    foreach ($data['socialProfiles'] AS $i => $v) {
      if (!empty($v['bio'])) {
        $v['description'] = $v['bio'];
      }
      if ($v['type'] == 'aboutme') {
        $visitor->setProp('aboutme', $v, $prop_options);
      }
      elseif ($v['type'] == 'facebook') {
        $visitor->setProp('facebook', $v, $prop_options);
      }
      elseif ($v['type'] == 'flickr') {
        $visitor->setProp('flickr', $v, $prop_options);
      }
      elseif ($v['type'] == 'foursquare') {
        $visitor->setProp('foursquare', $v, $prop_options);
      }
      elseif ($v['type'] == 'friendfeed') {
        $visitor->setProp('friendfeed', $v, $prop_options);
      }
      elseif ($v['type'] == 'googleplus') {
        $visitor->setProp('googleplus', $v, $prop_options);
      }
      elseif ($v['type'] == 'googleprofile') {
        $visitor->setProp('googleprofile', $v, $prop_options);
      }
      elseif ($v['type'] == 'gravatar') {
        $visitor->setProp('gravatar', $v, $prop_options);
      }
      elseif ($v['type'] == 'klout') {
        //intel_visitor_property_set($visitor, 'data.klout', $v, $prop_options);
        $klout = $v;
      }
      elseif ($v['type'] == 'lanyrd') {
        $visitor->setProp('lanyrd', $v, $prop_options);
      }
      elseif ($v['type'] == 'linkedin') {
        $visitor->setProp('linkedin', $v, $prop_options);
        if (!empty($v['bio'])) {
          $bio = $v['bio'];
        }
      }
      elseif ($v['type'] == 'myspace') {
        $visitor->setProp('myspace', $v, $prop_options);
      }
      elseif ($v['type'] == 'picasa') {
        $visitor->setProp('picasa', $v, $prop_options);
      }
      elseif ($v['type'] == 'tumblr') {
        $visitor->setProp('tumblr', $v, $prop_options);
      }
      elseif ($v['type'] == 'twitter') {
        $visitor->setProp('twitter', $v, $prop_options);
        if (!empty($v['bio'])) {
          $bio = $v['bio'];
        }
      }
      elseif ($v['type'] == 'vimeo') {
        $visitor->setProp('vimeo', $v, $prop_options);
      }
      elseif ($v['type'] == 'yahoo') {
        $visitor->setProp('yahoo', $v, $prop_options);
      }
      elseif ($v['type'] == 'youtube') {
        $visitor->setProp('youtube', $v, $prop_options);
      }
    }
  }
  if (is_array($klout) && !empty($klout['id'])) {
    $klout['topics'] = array();
    if (isset($data['digitalFootprint']) && isset($data['digitalFootprint']['scores']) && is_array($data['digitalFootprint']['scores'])) {
      foreach ($data['digitalFootprint']['scores'] AS $i => $v) {
        if ($v['provider'] == 'klout') {
          $klout['score'] = $v['value'];
        }
      }
    }
    if (isset($data['digitalFootprint']) && isset($data['digitalFootprint']['topics']) && is_array($data['digitalFootprint']['topics'])) {
      foreach ($data['digitalFootprint']['topics'] AS $i => $v) {
        if ($v['provider'] == 'klout') {
          $klout['topics'][] = $v['value'];
        }
      }
    }
    $visitor->setProp('klout', $klout, $prop_options);
  }
  if (!empty($data['organizations']) && is_array($data['organizations'])) {
    foreach ($data['organizations'] as $org) {
      if (!empty($org['isPrimary'])) {
        if (!empty($org['name'])) {
          $visitor->setProp('organization', $v, $prop_options);
        }
        if (!empty($org['title'])) {
          $visitor->setProp('jobTitle', $v, $prop_options);
        }
      }
    }
  }
  if (!empty($bio)) {
    $v = array(
      '@value' => $bio,
    );
    $visitor->setProp('description', $v, $prop_options);
  }

}

add_filter( 'intel_sync_visitor_alter', 'intel_sync_visitor_alter' );
function intel_sync_visitor_alter($visitor) {

  return $visitor;
}