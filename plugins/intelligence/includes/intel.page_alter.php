<?php
/**
 * @file
 * Support for adding intelligence to pages and processing form submissions
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_page_alter(&$page) {

  intel_load_include('includes/intel.ga');

  $user = intel_get_current_user();

  $uid = intel_get_user_id($user);

  $q = isset($_GET['q']) ? $_GET['q'] : '';

  $ldr = array();

  // Check IAPI level to prevent unsupported IAPI calls
  $api_level = intel_api_level();

  $parsed_url = intel_parse_cms_url();

  $ld = array();

  $modulePath = explode($parsed_url['hostpath'], INTEL_URL);
  $modulePath = substr($modulePath[1], 0, -1);
  $config = array(
    'debug' => intel_debug_mode(),
    // cmsHostpath, modulePath & apiPath are not standard io settings. They are used
    // exclusively by intel module js.
    'cmsHostpath' => $parsed_url['hostpath'],
    'modulePath' => $modulePath,
    // path to intel library files
    'libPath' => $modulePath . '/vendor/levelten/intel',
    'systemPath' => $q,
    'systemHost' => $parsed_url['host'],
    'systemBasepath' => $parsed_url['base_path'],
    'srl' => $q,
    'pageTitle' => '(not set)',
    'trackAnalytics' => 1, // this is set in intel_js_alter if ga script exists
    'trackAdhocCtas' => ($api_level == 'pro') ? 'track-cta' : '', // Check if CTA tracking is supported in IPAI level
    'trackAdhocEvents' => 'track-event',
    'trackForms' => array(),
    'trackRealtime' => (integer)get_option('intel_track_realtime', INTEL_TRACK_REALTIME_DEFAULT),
    'fetchGaRealtime' => (integer)get_option('intel_fetch_ga_realtime', 0),
    'isLandingpage' => 0,
    'scorings' => array(
      'events' => array(),
      'goals' => array(),
    ),
    'storage' => array(
      'page' => array(
	      'analytics' => array(),
	    ),
      'session' => array(
	      'analytics' => array(),
	    ),
      'visitor' => array(
	      'analytics' => array(),
	    ),
    ),
    'gaGoals' => array(),
    'eventDefs' => array(),
  );

  if (INTEL_PLATFORM == 'wp') {
    if (!empty($_GET['io-admin']) && Intel_Df::user_access('admin intel')) {
      wp_enqueue_style('intel_front_admin', INTEL_URL . 'css/intel.intel_script_admin.css');
      $config['admin'] = 1;
    }
  }

  $goals = intel_goal_load();

  // set scorings
  $scorings = intel_get_scorings('js_setting');
  foreach ($scorings as $k => $v) {
    if (substr($k, 0, 6) == 'event_') {
      $key = substr($k, 6);
      $config['scorings']['events'][$key] = $v;
    }
    else if (substr($k, 0, 5) == 'goal_') {
      $key = substr($k, 5);
      if (!empty($goals[$key])) {
        $config['scorings']['goals'][$goals[$key]['ga_id']] = $v;
      }
    }
    else {
      $config['scorings'][$k] = $v;
    }
  }

  foreach ($goals as $goal) {
    $config['gaGoals'][] = array(
      'id' => $goal['ga_id'],
      'name' => $goal['title'],
      'value' => $goal['value'],
    );
  }

  $config['contentSelector'] = get_option('intel_content_selector', '');

  if ($a = get_option('intel_domain_name', '')) {
    $config['cookieDomain'] = $a;
  }

  if (INTEL_PLATFORM == 'wp') {
    $path_is_admin = is_admin();
  }
  else {
    $path_is_admin = path_is_admin(current_path());
  }
  
  $ga_va = array();
  if ($uid) {
    if (intel_include_library_file('class.visitor.php')) {
      $ga_va = \LevelTen\Intel\ApiVisitor::extractCookieVa();
    }
  }  

  $entity = '';
  $entity_type = '';
  $intel_events = intel_get_intel_event_info();
  $page_events = array();
  $page_attrs = array();
  $visitor_attrs = array();

  intel_add_page_intel_push(array('set', "p.systemPath", $q));

  // determine entity from url
  if (INTEL_PLATFORM == 'wp') {
    if (is_single() || is_page()) {
      global $post;
      $entity = $post;
      $entity_type = 'post';
      $config['pageTitle'] = get_the_title( $post );
      $config['pageUri'] = ':post:' . $post->ID;
    }
  }
  else {
    // if entity not provided, lookup based on menu path
    if (arg(0) == 'node') {
      $entity = menu_get_object();
      $entity_type = 'node';
    }
    elseif (arg(0) == 'user') {
      $entity = menu_get_object('user');
      $entity_type = 'user';
    }
    $config['pageTitle'] = isset($entity->title) ? $entity->title : drupal_get_title();
    $config['pageUri'] = current_path();
  }

  if ($entity_type && !empty($entity)) {
    $attrs = intel_get_entity_intel_attributes($entity, $entity_type, 1);
    $page_attrs = $attrs['page'];
    $visitor_attrs = $attrs['visitor'];
    $page_events = $attrs['events'];
  }

  if ($path_is_admin) {
    $intent = 'a';
  }

  // check if page intent is landing page
  if (isset($page_attrs['pi']) && isset($page_attrs['pi']['l'])) {
    $config['isLandingpage'] = 1;
  }

  // set user role visitor attribute
  // only when user is logged in (otherwise page caching will cause errors)
  if ($uid) {
    if (INTEL_PLATFORM == 'wp') {
      // don't exclude on intel_demo_pages to allow testing
      if (!intel_is_demo_page()) {
        $tracking_exclude_roles = get_option('intel_tracking_exclude_role', intel_get_tracking_exclude_user_role_default());
        // WP roles do not have ids, so we create them
        $role_index = get_option('intel_role_index', array());
        if (empty($role_index)) {
          $role_index['anonymous'] = 0;
        }
        $cur = isset($ga_va['ur']) ? $ga_va['ur'] : array();
        if (!empty($user->roles) && is_array($user->roles)) {
          foreach ($user->roles AS $i => $role) {
            if (!empty($tracking_exclude_roles[$role])) {
              $config['trackAnalytics'] = 0;
            }
            if (!isset($role_index[$role])) {
              $role_index[$role] = count($role_index);
              update_option('intel_role_index', $role_index);
            }
            $id = $role_index[$role];
            if (!isset($cur[$id])) {
              if (!isset($visitor_attrs['ur'])) {
                $visitor_attrs['ur'] = array();
              }
              $visitor_attrs['ur'][$id] = '';
            }
          }
        }
      }
    }
    elseif (INTEL_PLATFORM == 'drupal') {
      $cur = isset($ga_va['ur']) ? $ga_va['ur'] : array();
      if (!empty($user->roles) && is_array($user->roles)) {
        foreach ($user->roles AS $i => $l) {
          // don't send anonymous role
          if ($i == 1) {
            continue;
          }
          if (!isset($cur[$i])) {
            if (!isset($visitor_attrs['ur'])) {
              $visitor_attrs['ur'] = array();
            }
            $visitor_attrs['ur'][$i] = '';
          }
        }
      }
    }
  }

  $page_attrs_info = intel_get_page_attribute_info();
  foreach ($page_attrs AS $key => $value) {
    if (isset($page_attrs_info[$key]['storage']) && is_array($page_attrs_info[$key]['storage'])) {
      foreach ($page_attrs_info[$key]['storage'] AS $namespace => $sv) {
        if (!isset($config['storage']['page'][$namespace])) {
          $config['storage']['page'][$namespace] = array();
        }
        $config['storage']['page'][$namespace][$key] = $sv;
      }
      // special exception for published age since it needs to be calculated in JS.
      if ($key == 'pd') {
        $config['storage']['page']['analytics']['pda'] = $page_attrs_info['pda']['storage']['analytics'];
      }
    }
    if (is_array($value)) {
      foreach ($value AS $key2 => $value2) {
        intel_add_page_intel_push(array('set', "pa.$key.$key2", $value2));
      }
    }
    else {
      intel_add_page_intel_push(array('set', "pa.$key", $value));
    }
  }

  $visitor_attrs_info = intel_get_visitor_attribute_info();
  foreach ($visitor_attrs AS $key => $value) {
    if (isset($visitor_attrs_info[$key]['storage'])) {
      foreach ($visitor_attrs_info[$key]['storage'] AS $namespace => $sv) {
        if (!isset($config['storage']['visitor'][$namespace])) {
          $config['storage']['visitor'][$namespace] = array();
        }
        $config['storage']['visitor'][$namespace][$key] = $sv;
      }
    }

    if (is_array($value)) {
      foreach ($value AS $key2 => $value2) {
        intel_add_page_intel_push(array('set', "va.$key.$key2", $value2));
      }
    }
    else {
      intel_add_page_intel_push(array('set', "va.$key", $value));
    }
  }

  // add events set to be applied to all pages
  $context = array(
    'parsed_url' => $parsed_url
  );

  $all_page_events = intel_get_enabled_intel_events($context);
  $page_events = array_merge($page_events, $all_page_events);

  foreach ($page_events AS $key => $value) {
    $l10i_event_action = intel_filter_event_for_push($value);
    if (!empty($value['js_setting']) || !empty($value['config'])) {
      $l10i_event_action['id'] = $value['key'];
      $config['eventDefs'][] = $l10i_event_action;
    }
    else {
      intel_add_page_intel_push(array('event', $l10i_event_action));
    }
  }

  if (intel_is_intel_script_enabled('linktracker')) {
    $link_infos = intel_get_link_type_info();
    foreach ($link_infos AS $key => $value) {
      $value['id'] = $key;
      $def = intel_filter_link_info_for_push($value);
      intel_add_page_intel_push(array('linktracker:setLinkTypeDef', $key, $def));
    }
  }

  intel_check_form_submission($page);
  
  // check form processing
  // this is a hack to exclude webform's submission redirect page
  $track_forms = array();
  if ($entity_type == 'node' && isset($entity->type) && ($entity->type == 'webform')) {
    // check if page is a webform submit validation
    if (!empty($_POST['form_id']) && (substr($_POST['form_id'], 0, 20) == 'webform_client_form_')) {
      $track_forms = 0;
    }
  }

  //if (isset($node->type) && ($node->type == 'enterprise_landingpage')) {
  //  $is_landingpage = 1;
  //}

  // call hook for modules to add page pushes
  intel_do_hook_action('intel_page_intel_pushes');

  $pushes = intel_get_flush_page_intel_pushes();
  $pushes_cookie = intel_get_flush_page_intel_pushes_cookie();

  // add page title and system path to any IntelEvent that is missing values
  /*
  if (isset($pushes['event']) && is_array($pushes['event'])) {
    foreach ($pushes['event'] AS $i => $push) {
      if (empty($push['action']) && empty($push['eventAction']) ) {
        //$pushes['event'][$i]['eventAction'] = $config['pageTitle'];
      }
      if (empty($push['label']) && empty($push['eventLabel'])) {
        //$pushes['event'][$i]['eventLabel'] = $_GET['q'];
      }
    }
  }
  */

  //watchdog('intel_page_alter_pushes', print_r($pushes, 1));

  $parsed_url = intel_parse_cms_url();
  $js = array(
    'intel' => array(
      'config' => $config,
      'pushes' => $pushes,
      'pushes_cookie' => $pushes_cookie,
    ),
    // TODO: move this to intel_disqus
    'disqus' => array(
      'callbacks' => array(
        'onNewComment' => array('_ioq.plugins.disqus.triggerComment'),
      ),
    ),
  );

  if ($ga_domain = get_option('intel_domain_name', '')) {
    $js['intel']['config']['cookieDomain'] = $ga_domain;
  }

  // determine if page should be tracked
  $track = 1;

  if (INTEL_PLATFORM == 'wp') {

  }
  else {
    // check page tracking settings in googleanaltyics module
    // Get page status code for visibility filtering.
    $id = variable_get('googleanalytics_account', '');
    $status = drupal_get_http_header('Status');
    $trackable_status_codes = array(
      '403 Forbidden',
      '404 Not Found',
    );
    $track = 1;
    if (!(_googleanalytics_visibility_pages() || in_array($status, $trackable_status_codes)) && _googleanalytics_visibility_user($user)) {
      $track = 0;
      // TODO: Intel GA tracking should track google_analytics module settings
      //$js['intel']['config']['track_analytics'] = 0;
    }
    if (path_is_admin(current_path())) {
      $track = 0;
    }
  }

  if (!$track) {
    $js['intel']['config']['track_forms'] = 0;
    $js['intel']['config']['track_adhoc_ctas'] = 0;
    $js['intel']['config']['track_adhoc_events'] = 0;
  }
  else {
    // add intel scripts to page
    if (INTEL_PLATFORM == 'wp') {
      // for WP, js scripts are enqueued in class-intel-tracker
    }
    else {
      $scripts = intel_get_intel_script_info();
      $enabled = variable_get('intel_intel_scripts_enabled', array());
      foreach ($scripts AS $key => $script) {
        if (!empty($enabled[$key]) || (!isset($enabled[$key]) && !empty($script['enabled']))) {
          drupal_add_js($script['path']);
        }
      }

      if (!empty($_GET['debug'])) {
        if ($_GET['debug'] == 'ie9') {
          $script = "http://ie.microsoft.com/testdrive/HTML5/CompatInspector/inspector.js";
          drupal_add_js($script, array('scope' => 'header', 'type' => 'external', 'weight' => -10, 'group' => JS_LIBRARY));
        }
      }

      // add js embed script
      $script = intel_get_js_embed('l10i', 'local');
      drupal_add_js($script, array('type' => 'inline', 'scope' => 'header', 'weight' => 0));
    }
  }
  
  //drupal_alter('intel_page_settings_js', $js, $page);
  intel_do_hook_alter('intel_page_settings_js', $js, $page);

  if (!empty($_GET['debug'])) {
    intel_d($js);//
  }
  intel()->add_js($js, 'setting');
}

function intel_get_entity_intel_attributes($entity = '', $entity_type = '', $include_events = 0) {
  global $user, $base_path;

  $attr_cache = &Intel_Df::drupal_static(__FUNCTION__, array());
  //$entity_info_cache = &Intel_Df::drupal_static(__FUNCTION__);  // two cached vars, seem to not work
  static $entity_info_cache;

  $visitor_attrs = array();
  $page_attrs = array();
  $page_ld = array();
  $page_events = array();

  // if entity not found, return empty attributes
  if (!$entity || !$entity_type) {
    return array(
      'page' => $page_attrs,
      'page_ld' => $page_ld,
      'visitor' => $visitor_attrs,
    );
  }

  $page_ld['oei:attributes'] = array();

  if (!isset($attr_cache[$entity_type])) {
    $attr_cache[$entity_type] = array();
  }

  if (!isset($entity_info_cache)) {
    $entity_info_cache = array();
  }

  if (!isset($entity_info_cache[$entity_type])) {
    $entity_info_cache[$entity_type] = intel()->entity_info($entity_type);
  }
  $entity_info = $entity_info_cache[$entity_type];

  $entity_id = 0;
  if (isset($entity_info['entity keys']['id']) && isset($entity->{$entity_info['entity keys']['id']})) {
    $entity_id = $entity->{$entity_info['entity keys']['id']};
  }

  if ($entity_id && isset($attr_cache[$entity_type][$entity_id])) {
    return $attr_cache[$entity_type][$entity_id];
  }

  $entity_attrs = array();

  $page_attr_info = intel_get_page_attribute_info();
  // resource type = entity_type
  $page_ld['site:entityType'] = $page_attrs['rt'] = $entity_type;
  // resource sub type = entity bundle
  if (isset($entity_info['entity keys']['bundle']) && isset($entity->{$entity_info['entity keys']['bundle']})) {
    $page_ld['site:entityBundle'] = $page_attrs['rt2'] = $entity->{$entity_info['entity keys']['bundle']};
    $page_attrs['rt2'] = str_replace('enterprise_', '', $page_attrs['rt2']); // trim enterprise namespace in Open Enterprise
  }
  // resource id = entity_id
  if (isset($entity_info['entity keys']['id']) && isset($entity->{$entity_info['entity keys']['id']})) {
    $page_ld['site:entityId'] = $page_attrs['rk'] = $entity->{$entity_info['entity keys']['id']};
    $entity_attrs = intel_entity_attr_load_by_params($entity_type, $page_attrs['rk']);

    if (is_array($entity_attrs)) {

      foreach ($entity_attrs AS $ea) {
        if (!isset($page_attr_info[$ea->attr_key])) {
          continue;
        }

        if ($page_attr_info[$ea->attr_key]['type'] == 'list' || $page_attr_info[$ea->attr_key]['type'] == 'vector') {
          $value_str = intel_index_encode($ea->attr_key);
          if (!isset($page_attrs[$ea->attr_key])) {
            $page_attrs[$ea->attr_key] = array();
            //$page_ld['oei:attributes'][$ea->attr_key] = array();
          }
          if (!empty($page_attr_info[$ea->attr_key]['encode']) && isset($ea->vsid)) {
            $index = intel_index_encode($ea->vsid);
          }
          else {
            $index = isset($ea->value_str) ? $ea->value_str : $ea->value;
          }
          if ($page_attr_info[$ea->attr_key]['type'] == 'vector') {
            $page_attrs[$ea->attr_key][$index] = !empty($ea->value_num) ? $ea->value_num : 0;
            //$page_ld['oei:attributes'][$ea->attr_key][$index] = !empty($ea->value_num) ? $ea->value_num : 0;
          }
          else {
            $page_attrs[$ea->attr_key][$index] = '';
            //$page_ld['oei:attributes'][$ea->attr_key][$index] = '';
          }
        }
        else {
          $page_attrs[$ea->attr_key] = $ea->value;
          //$page_ld['oei:attributes'][$ea->attr_key] = $ea->value;
          if (!empty($page_attr_info[$ea->attr_key]['encode']) && isset($ea->vsid)) {
            $page_attrs[$ea->attr_key] = intel_index_encode($ea->vsid);
            //$page_ld['oei:attributes'][$ea->attr_key] = intel_index_encode($ea->vsid);
          }
        }
      }
    }

    // create an empty entity_attr in case some attr needs to be saved
    $entity_attr = (object)array(
      'entity_type' => $page_attrs['rt'],
      'entity_id' => $page_attrs['rk'],
    );
  }
  // resource vid = entity vid
  if (isset($entity_info['entity keys']['revision']) && isset($entity->{$entity_info['entity keys']['revision']})) {
    //$page_attrs['rvi'] = $entity->{$entity_info['entity keys']['revision']};
    $page_ld['version'] = $entity->{$entity_info['entity keys']['revision']};
  }

  // resource table = entity label
  if (isset($entity_info['entity keys']['label']) && isset($entity->{$entity_info['entity keys']['label']})) {
    //$page_attrs['rtl'] = $entity->{$entity_info['entity keys']['label']};
    $page_ld['headline'] = $entity->{$entity_info['entity keys']['label']};
    $page_ld['name'] = $entity->{$entity_info['entity keys']['label']};
  }
  // resource language
  if (isset($entity_info['entity keys']['language']) && isset($entity->{$entity_info['entity keys']['language']}) && ($entity->{$entity_info['entity keys']['language']} != LANGUAGE_NONE)) {
    $page_attrs['lang'] = $entity->{$entity_info['entity keys']['language']};
    $page_ld['inLanguage'] = $entity->{$entity_info['entity keys']['language']};
  }

  // ? not sure if this is needed or enhances performance
  $created = IntelEntity::entity_get($entity_type, $entity, 'created');
  if (isset($created)) {
    $val = date('YmdHiw', $created);
    if (empty($page_attrs['pd']) || ($page_attrs['pd'] != $val)) {
      $page_attrs['pd'] = $val;
      $page_ld['datePublished'] = $val;
      /*
      if (isset($entity_attr)) {
        $entity_attr->attr_key = 'pd';
        $entity_attr->value = $val;
        intel_entity_attr_save($entity_attr);
      }
      */
    }
    /*
    $page_attrs['pd'] = $entity->created;
    $val = (int)date('w', $entity->created);

    // day of week
    if (!isset($page_attrs['pdw']) || ($page_attrs['pdw'] == $val)) {
      $page_attrs['pdw'] = $val;
      if (isset($entity_attr)) {
        $entity_attr->attr_key = 'pdw';
        $entity_attr->value = $val;
        intel_entity_attr_save($entity_attr);
      }
    }

    // hour of day
    $val = (int)date('Hi', $entity->created);
    if (!isset($page_attrs['pdt']) || ($page_attrs['pdt'] == $val)) {
      $page_attrs['pdt'] = $val;
      if (isset($entity_attr)) {
        $entity_attr->attr_key = 'pdt';
        $entity_attr->value = $val;
        intel_entity_attr_save($entity_attr);
      }
    }
    */
  }
  
  // Only load URI if entity has been created (has ID).
  if ($entity_id) {
    $uri = IntelEntity::entity_uri($entity_type, $entity);
    if (!empty($uri['id'])) {
      $page_attrs['ri'] = $uri['id'];
    }
    elseif (!empty($uri['path'])) {
      $page_attrs['rl'] = $uri['path'];
      $page_ld['@id'] = $base_path . $uri['path'];
      $page_ld['site:route'] = $uri['path'];
      //$page_attrs['url'] = url($page_attrs['rl']);
    }
  }
  
  if ($entity_type == 'node') {
    $entity_bundle = $entity->type;
    $page_title = $entity->title;

    $path_entity = 1;
  }
  elseif ($entity_type == 'post') {
    $entity_bundle = $entity->post_type;

    //$page_attrs['pt'] = $entity->created;
    //$page_attrs['et'] = 'user';
    $path_entity = 1;
  }
  elseif ($entity_type == 'user') {
    $entity_bundle = 'user';

    //$page_attrs['pt'] = $entity->created;
    //$page_attrs['et'] = 'user';
    $path_entity = 1;
  }

  if (empty($entity_bundle)) {
    //watchdog('intel-entity-attrs', print_r($entity, 1));
    return;
  }

  $page_entity_settings = array();
  if (!empty($entity_type) && !empty($entity_bundle)) {
    $page_entity_settings = get_option('intel_entity_settings_' . $entity_type . '__' . $entity_bundle, array());
  }

  $lang = !empty($entity->language) ? $entity->language : Intel_DF::LANGUAGE_NONE;
  //$fields_info = field_info_instances($entity_type, $entity_bundle);
  $fields_info = array();
  // track entity uid (e.g. node author)
  if (!isset($page_entity_settings['track_page_uid']) || $page_entity_settings['track_page_uid']) {
    $key = !empty($page_entity_settings['track_page_uid']) ? $page_entity_settings['track_page_uid'] : 'a';
    if ($key == 1) {
      $key = 'a';
    }
    $author_id = IntelEntity::entity_get($entity_type, $entity, 'author_id');
    $page_attrs[$key] = $author_id;
    //$page_ld['author'] = $base_path . "user/" . $entity->uid;

    $page_ld['author'] = array(
      '@id' => $base_path . ":user:" . $author_id,
      'site:entityType' => 'user',
      'site:entityId' => $author_id,
      //'site:route' => "user/" . $entity->uid,
    );
  }

  // track page intent
  $intent = INTEL_PAGE_INTENT_DEFAULT;


  if (!empty($page_entity_settings['page_intent'])) {
    $intent = $page_entity_settings['page_intent'];
  }
  if (INTEL_PLATFORM == 'drupal') {
    if (!empty($entity->field_page_intent[$lang][0]['value']) && ($entity->field_page_intent[$lang][0]['value'] != '_default')) {
      $intent = $entity->field_page_intent[$lang][0]['value'];
    }
  }
  if ($intent) {
    $page_attrs['pi'] = array(
      $intent => '',
    );
    $page_ld['oei:pageIntent'] = $intent;
  }

  $vocab_entity_settings = intel_get_entity_settings_multi('taxonomy');
//intel_d($vocab_entity_settings);
  // process vocabularies with global tracking enabled
  if (INTEL_PLATFORM == 'drupal') {
    foreach ($fields_info AS $key => $field) {
      if (isset($entity->{$key}[$lang][0]['tid'])) {
        // get field_info to get associated vocabulary
        $field_info = field_info_field($key);
        $vocab_name = $field_info['settings']['allowed_values'][0]['vocabulary'];
        // check if tracking is enabled at the content type level or on globally on the vocabulary
        if (!empty($page_entity_settings['track_term_fields'][$key]) || !empty($vocab_entity_settings[$vocab_name]['track_page_terms'])) {
          $page_attr_key = !empty($vocab_entity_settings[$vocab_name]['page_attribute']['key']) ? $vocab_entity_settings[$vocab_name]['page_attribute']['key'] : 't';
          $page_ld_key = "site:$vocab_name";
          foreach ($entity->{$key}[$lang] AS $t) {
            if (!isset($page_attrs[$page_attr_key])) {
              $page_attrs[$page_attr_key] = array();
              $page_ld[$page_ld_key] = array();
            }
            $page_attrs[$page_attr_key][$t['tid']] = '';
            $page_ld[$page_ld_key][] = (object) array(
              '@id' => $base_path . 'taxonomy/term/' . $t['tid'],
              'site:route' => 'taxonomy/term/' . $t['tid'],
              'site:entityId' => $t['tid'],
            );
            if (!empty($vocab_entity_settings[$vocab_name]['track_page_terms_visitor'])) {
              $visitor_attr_key = !empty($vocab_entity_settings[$vocab_name]['visitor_attribute']['key']) ? $vocab_entity_settings[$vocab_name]['visitor_attribute']['key'] : $page_attr_key;
              if (!isset($visitor_attrs[$visitor_attr_key])) {
                $visitor_attrs[$visitor_attr_key] = array();
              }
              $visitor_attrs[$visitor_attr_key][$t['tid']] = '=+1';
            }
          }
        }
      }
    }
  }
  elseif (INTEL_PLATFORM == 'wp') {
    $taxonomies = get_object_taxonomies( $entity );
    //$taxonomies = get_taxonomies();
    //intel_d($taxonomies);

    //intel_d($taxonomies);
    //intel_d($taxonomies);
    foreach ($taxonomies as $tax_type) {
      if (empty($vocab_entity_settings[$tax_type])) {
        continue;
      }
      $vocab_es = $vocab_entity_settings[$tax_type];
      if (!empty($vocab_es['page_attribute']['key'])) {
        $attr_key = $vocab_es['page_attribute']['key'];
        $terms = get_the_terms($entity, $tax_type);
        if (!is_array($terms)) {
          $terms = array();
        }
        // process demo terms
        if (!empty($entity->intel_demo['terms'])) {
          foreach ($entity->intel_demo['terms'] as $id) {
            $term = intel_demo_term_load($id);
            if (!empty($term->taxonomy) && $term->taxonomy == $tax_type) {
              $terms[] = $term;
            }
          }
        }
        foreach($terms as $term) {
          $page_attrs[$attr_key][$term->term_id] = '';
        }
      }
    }
  }
  //intel_d($page_attrs);

  // set custom page attributes if page_attribute_col field exists
  if (INTEL_PLATFORM == 'drupal') {
    if (!empty($entity->field_page_attribute_col[$lang]) && is_array($entity->field_page_attribute_col[$lang])) {
      foreach ($entity->field_page_attribute_col[$lang] AS $i => $e) {
        $collection = field_collection_item_load($e['value']);
        $keys = explode('.', $collection->field_page_attribute[$lang][0]['value']);
        $value = !empty($collection->field_page_attribute_value[$lang][0]['value']) ? $collection->field_page_attribute_value[$lang][0]['value'] : '';
        if (count($keys) == 1) {  // flag and scalar values
          $page_attrs[$keys[0]] = $value;
        }
        else {
          if (!isset($page_attrs[$keys[0]])) {
            $page_attrs[$keys[0]] = array();
          }
          $page_attrs[$keys[0]][$keys[1]] = $value;
        }
      }
    }

    // set custom visitor attributes if visitor_attribute_col field exists
    if (!empty($entity->field_visitor_attribute_col[$lang]) && is_array($entity->field_visitor_attribute_col[$lang])) {
      foreach ($entity->field_visitor_attribute_col[$lang] AS $i => $e) {
        $collection = field_collection_item_load($e['value']);
        $keys = explode('.', $collection->field_visitor_attribute[$lang][0]['value']);
        $value = !empty($collection->field_visitor_attribute_value[$lang][0]['value']) ? $collection->field_visitor_attribute_value[$lang][0]['value'] : '';
        if (count($keys) == 1) {  // flag and scalar values
          $visitor_attrs[$keys[0]] = $value;
        }
        else {
          if (!isset($visitor_attrs[$keys[0]])) {
            $visitor_attrs[$keys[0]] = array();
          }
          $visitor_attrs[$keys[0]][$keys[1]] = $value;
        }
      }
    }
  }
  elseif (INTEL_PLATFORM == 'wp') {

  }




  // set attached intel events if intel_event_col field exists
  // TODO get page events working

  if ($include_events) {
    if (!empty($entity->field_intel_event_col[$lang]) && is_array($entity->field_intel_event_col[$lang])) {
      $intel_event_info = intel_get_intel_event_info();
      foreach ($entity->field_intel_event_col[$lang] AS $i => $e) {
        $collection = field_collection_item_load($e['value']);
        $key = $collection->field_intel_event[$lang][0]['value'];
        $page_events[$key] = $intel_event_info[$key];

        if(!empty($collection->field_intel_event_value[$lang][0]['value'])) {
          $page_events[$key]['value'] = $collection->field_intel_event_value[$lang][0]['value'];
        }

      }
    }
  }

  $attrs = array(
    'page' => $page_attrs,
    'page_ld' => $page_ld,
    'visitor' => $visitor_attrs,
    'events' => $page_events,
  );

  // allow modules to add/alter attributes
  //drupal_alter('intel_entity_intel_attributes', $attrs, $entity, $entity_type);
  $attrs = apply_filters('intel_entity_intel_attributes_alter', $attrs, $entity, $entity_type);

  if ($entity_id) {
    $attr_cache[$entity_type][$entity_id] = $attrs;
  }

  return $attrs;
}

function intel_get_post_entity_attr_default($post = NULL) {
  if (!isset($post)) {
    global $post;
  }
  $entity_attr = array();
  // code inspired
  //Variable: Additional characters which will be considered as a 'word'
  $char_list = ''; /** MODIFY IF YOU LIKE.  Add characters inside the single quotes. **/
  //$char_list = '0123456789'; /** If you want to count numbers as 'words' **/
  //$char_list = '&@'; /** If you want count certain symbols as 'words' **/
  //intel_d($post->post_content);

  $content = do_shortcode($post->post_content );
  //intel_d($content);
  //Intel_Df::watchdog('content', $content);
  $ea = (object)array(
    'attr_key' => 'cw',
    'value' => str_word_count(strip_tags($content), 0, $char_list),
  );
  $entity_attr[] = $ea;
  $ea = (object)array(
    'attr_key' => 'ctw',
    'value' => str_word_count(strip_tags($post->post_title), 0, $char_list),
  );
  $entity_attr[] = $ea;
  $ea = (object)array(
    'attr_key' => 'ctc',
    'value' => strlen(strip_tags($post->post_title)),
  );
  $entity_attr[] = $ea;
  //$entity_attr['cw'] = str_word_count(strip_tags($content), 0, $char_list);
  //$entity_attr['ctw'] = str_word_count(strip_tags($post->post_title), 0, $char_list);
  //$entity_attr['ctc'] = strlen(strip_tags($post->post_title));
  return $entity_attr;
}

function intel_add_attrs_config($attrs, $type = 'page') {
  $attrs_info = ($type == 'visitor') ? intel_get_visitor_attribute_info() : intel_get_page_attribute_info();
  $var_abv = ($type == 'visitor') ? 'va' : 'pa';
  foreach ($attrs AS $key => $value) {
    if (isset($attrs_info[$key]['storage']) && is_array($attrs_info[$key]['storage'])) {
      foreach ($attrs_info[$key]['storage'] AS $namespace => $sv) {
        if (!isset($config['storage'][$type][$namespace])) {
          $config['storage'][$type][$namespace] = array();
        }
        $config['storage'][$type][$namespace][$key] = $sv;
      }
      // special exception for published age since it needs to be calculated in JS.
      if ($type == 'page' && $key == 'pd') {
        $config['storage'][$type]['analytics']['pda'] = $attrs_info['pda']['storage']['analytics'];
      }
    }
    if (is_array($value)) {
      foreach ($value AS $key2 => $value2) {
        intel_add_page_intel_push(array('set', "$var_abv.$key.$key2", $value2));
      }
    }
    else {
      intel_add_page_intel_push(array('set', "$var_abv.$key", $value));
    }
  }
}

function intel_add_page_intel_push($push, $index = '') {
  return intel_page_intel_pushes('add', $push, $index);
}

function intel_get_page_intel_pushes() {
  return intel_page_intel_pushes('get');
}

function intel_get_flush_page_intel_pushes() {
  return intel_page_intel_pushes('get_flush');
}

function intel_get_page_intel_pushes_cookie() {
  return intel_page_intel_pushes('get_cookie');
}

function intel_get_flush_page_intel_pushes_cookie() {
  return intel_page_intel_pushes('get_cookie_flush');
}

/**
 * Used to store intel pushes for redirects.
 * @return mixed
 */
function intel_save_flush_page_intel_pushes() {
  $push_storage = get_option('intel_save_push_storage', INTEL_SAVE_PUSH_STORAGE_DEFAULT);
  if ($push_storage == 'cookie') {
    return intel_page_intel_pushes('save_cookie_flush');
  }
  else {
    return intel_page_intel_pushes('save_db_flush');
  }
}

function intel_save_db_flush_page_intel_pushes() {
  return intel_page_intel_pushes('save_db_flush');
}

function intel_save_cookie_flush_page_intel_pushes() {
  return intel_page_intel_pushes('save_cookie_flush');
}


function intel_page_intel_pushes($action = 'get', $push = array(), $index = '') {

  if (!isset($_SESSION['intel_pushes'])) {
    $_SESSION['intel_pushes'] = array();
  }
  if (!isset($_SESSION['intel_pushes']['page_pushes'])) {
    $_SESSION['intel_pushes']['page_pushes'] = array();
  }
  if (!isset($_SESSION['intel_pushes']['page_pushes_cookie'])) {
    $_SESSION['intel_pushes']['page_pushes_cookie'] = array();
  }

  $data = &$_SESSION['intel_pushes']['page_pushes'];
  if (!empty($options['cookie'])) {
    $data = &$_SESSION['intel_pushes']['page_pushes_cookie'];
  }

  if ($action == 'add') {
    // if push is assoc array containing method key, use that key
    if (!empty($push['method'])) {
      $method = $push['method'];
      unset($push['method']);
    }
    else {
      $method = array_shift($push);
    }

    if (!isset($data[$method])) {
      $data[$method] = array();
    }

    $index = -1;
    // if method is passed with prefix, i.e. _.set (does set on ga base tracker)
    // get the actually method
    $method_elms = explode('.', $method);
    $method_elms_method = $method_elms[count($method_elms)-1];

    // direct ga push commands
    if ($method_elms[0] == 'ga') {
      if ($method_elms_method == 'set') {
        $index = $push[0];
        $value = $push[1];
      }
    }
    elseif ($method == 'set') {
      $index = $push[0];
      $value = $push[1];
    }
    elseif ($method == 'event') {
      $index = count($data[$method]);
      $value = $push[0];
    }
    elseif ($method == 'setUserId') {
      $value = $push[0];
    }
    else {
      // check if push uses command(def) format
      if (count($push) == 1 && is_array($push[0])) {
        $index = count($data[$method]);
        $value = $push[0];
      }
      else {
        $index = count($data[$method]);
        $value = $push;
      }

    }

    if ($index == -1) {
      $data[$method] = $value;
    }
    else {
      $data[$method][$index] = $value;
    }

  }

  $ret = $data;

  if ($action == 'save_flush' || $action == 'save_db_flush') {
    intel()->quick_session_cache();
    unset($_SESSION['intel_pushes']['page_pushes']);
  }
  elseif ($action == 'save_cookie_flush') {
    // format pushes into an array
    $pushes = array();

    foreach ($_SESSION['intel_pushes']['page_pushes'] as $k => $v) {
      $a = array('');
      $pushes[] = array($k, $v);
    }
    $json = json_encode($pushes);
    intel_setrawcookie('l10i_page_pushes', $json);
    unset($_SESSION['intel_pushes']['page_pushes']);
    unset($_SESSION['intel_pushes']['page_pushes_cookie']);
  }
  elseif ($action == 'get_flush') {
    unset($_SESSION['intel_pushes']['page_pushes']);
  }
  elseif ($action == 'get_cookie') {
    $ret = $_SESSION['intel_pushes']['page_pushes_cookie'];
  }
  elseif ($action == 'get_cookie_flush') {
    $ret = $_SESSION['intel_pushes']['page_pushes_cookie'];
    unset($_SESSION['intel_pushes']['page_pushes_cookie']);
  }

  /*
   * Unset session var if empty on flush
   */
  if ((substr($action, -5) == 'flush')
    && empty($_SESSION['intel_pushes']['page_pushes'])
    && empty($_SESSION['intel_pushes']['page_pushes_cookie'])
  ) {
    unset($_SESSION['intel_pushes']);
  }

  return $ret;
}

/**
 * Implements hook_wp_redirect
 */
/*
add_filter( 'wp_redirect', 'intel_wp_redirect_cache_page_pushes', 10, 2 );
function intel_wp_redirect_cache_page_pushes($location, $status) {
  // save the page flushes to cache
  intel_save_flush_page_intel_pushes();
  return $location;
}
*/

/**
 * Sanitizes event tracking data that is being put into javascript.
 */
function _intel_ga_event_sanitize_events($ga_events) {
  if (is_array($ga_events)) {
    foreach ($ga_events as $i => $event) {
      foreach ($event as $j => $e) {
        $ga_events[$i][$j] = filter_xss($e);
      }
    }
    return $ga_events;
  }
  else {
    return array();
  }
}

/**
 * Strips event metadata values not needed for page push
 * @param $event
 */
function intel_filter_event_for_push($event) {
  if (empty($event) || !is_array($event)) {
    return FALSE;
  }

  // translate from classic format to UA format
  $trans = array(
    //'category' => 'eventCategory', // automatically constructed
    'event_id' => 'eventId',
    'event_category' => 'eventCategory',
    'action' => 'eventAction',
    'label' => 'eventLabel',
    'value' => 'eventValue',
    'noninteraction' => 'nonInteraction',
    'selector' => 1,
    'selector_filter' => 'selectorFilter',
    'selector_not' => 'selectorNot',
    'on_event' => 'onEvent',
    'event' => 'onEvent',
    'on_selector' => 'onSelector',
    'on_data' => 'onData',
    'on_handler' => 'onHandler',
    'callback' => 'triggerCallback',
    'add_callback' => 'addCallback',
    'bind_callback' => 'bindCallback',
    'trigger_callback' => 'triggerCallback',
    'trigger_alter_callback' => 'triggerAlterCallback',
    'refresh_force' => 'refreshForce',
    'social_network' => 'socialNetwork',
    'social_action' => 'socialAction',
    'social_target' => 'socialTarget',
    'key' => 1,
    'eventId' => 1,
    'method' => 1,
    'eventCategory' => 1,
    'eventAction' => 1,
    'eventValue' => 1,
    'nonInteraction' => 1,
    'onEvent' => 1,
    'onSelector' => 1,
    'onData' => 1,
    'onHandler' => 1,
    'selectorFilter' => 1,
    'selectorNot' => 1,
    'refreshForce' => 1,
  );

  foreach ($event as $k => $v) {
    if (isset($trans[$k])) {
      $t = $trans[$k];

      if (empty($v)) {
        if ($k == 'value') {
          if ($v !== 0 && $v !== "0") {
            unset($event[$k]);
            continue;
          }
        }
        else {
          unset($event[$k]);
          continue;
        }

      }

      if (is_string($t)) {
        $event[$t] = $v;
        unset($event[$k]);
      }

    }
    else {
      unset($event[$k]);
    }

  }

  if (!empty($event['on_handler'])) {

  }

  if (isset($event['triggerCallback']) && !$event['triggerCallback']) {
    unset($event['triggerCallback']);
  }

  return $event;
}

function intel_trigger_intel_event($event) {
  // strip values not needed by javascript
  $event = intel_filter_event_for_push($event);

  intel_add_page_intel_push($event);
}

function intel_trigger_intel_event_by_name($event_name, $values = array()) {
  $event = intel_intel_event_load($event_name);
  if (empty($event)) {
    return;
  }
  if (!empty($event['valued_event'])) {
    $event['category'] .= '!';
  }
  if (empty($event['event'])) {
    $event['event'] = 'pageshow';
  }
  if (empty($event['selector'])) {
    $event['selector'] = 'body';
  }
  $values['method'] = '_addIntelEvent';
  // merge event values on $values
  $values += $event;
  return intel_trigger_intel_event($values);
}

/**
 * Strips event metadata values not needed for page push
 * @param $event
 */
function intel_filter_link_info_for_push($def) {
  if (empty($def) || !is_array($def)) {
    return FALSE;
  }

  // translate from classic format to UA format
  $trans = array(
    //'category' => 'eventCategory', // automatically constructed
    'click_mode' => 'clickMode',
    'click_value' => 'clickValue',
    'title' => 1,
    'track' => 1,
    'clickMode' => 1,
    'clickValue' => 1,
    'track_file_extension' => 'trackFileExtension',
    'trackFileExtension' => 1,
  );

  foreach ($def as $k => $v) {
    if (isset($trans[$k])) {
      $t = $trans[$k];

      if (is_string($t)) {
        $def[$t] = $v;
        unset($def[$k]);
      }
    }
    else {
      unset($def[$k]);
    }

  }

  return $def;
}

function intel_cache_busting_url($url, $force = FALSE) {
  $enable = &Intel_Df::drupal_static( __FUNCTION__ );

  if (!isset($enable)) {
    $enable = get_option('intel_cache_busting', INTEL_CACHE_BUSTING_DEFAULT);
  }
  if (!$enable && !$force) {
    return $url;
  }
  $parse_url = parse_url($url);
  if (!empty($parse_url['query'])) {
    $url .= '&iot=' . microtime();
  }
  else {
    $url .= '?iot=' . microtime();
  }
  return $url;
}
/*
function intel_print_var($var) {
  dsm($var);
}
*/

function intel_init() {
  // Need to use drupal_add_html_head() instead of drupal_add_js() to load monitor script above all js.
  if ($script = get_option('intel_js_monitor_script', '')) {
    drupal_add_html_head(
      array(
        '#type' => 'markup',
        '#markup' => "$script\n",
        '#weight' => -99,
      ), 
      'intel_js_monitor');
  }

  // hack to support dpm/dsm functions if devel not installed
  /*
  if (intel_debug_mode() && !module_exists('devel')) {
    if (!function_exists('dpm')) {
      function dpm($input) {
        print_r($input);//
      }
    }
    if (!function_exists('dsm')) {
      function dsm($input) {
        print_r($input);//
      }
    }
  }
  */

}

/**
 * Parse URL to return separated arguments in the current path.
 *
 * @return array $parsed_url
 */
function intel_parse_cms_url() {
  $parsed_url = &Intel_Df::drupal_static(__FUNCTION__);
  if (!empty($parse_url)) {
    return $parse_url;
  }

  //global $base_root, $base_path;
  $base_root = get_site_url();
  $base_path = '/';
  $url = $base_root . $base_path . '?';
  foreach ($_GET AS $key => $value) {
    if (!is_array($_GET[$key])) {
      $url .= '&' . $key . '=' . $value;
    }
  }
  $parsed_url = parse_url($url);
  $parsed_url['base_path'] = $base_path;
  $parsed_url['hostpath'] = $parsed_url['host'] . ((!empty($parsed_url['port'])) ? ':' . $parsed_url['port'] : '') . $base_path;

  return $parsed_url;
}

function intel_get_ga_js_embed($embed_ga_tracking_code = '') {
  if (!$embed_ga_tracking_code) {
    $embed_ga_tracking_code = get_option('intel_embed_ga_tracking_code', '');
  }
  $ga_profile_base = get_option('intel_ga_profile_base', array());
  $ga_tid_base = !empty($ga_profile_base['propertyId']) ? $ga_profile_base['propertyId'] : '';

  $script = '';
  $terminator = "\n";
  if ($embed_ga_tracking_code == 'gtag') {
    $script .= "<!-- Global site tag (gtag.js) - Google Analytics -->" . $terminator;
    $script .= "<script async src=\"https://www.googletagmanager.com/gtag/js?id=$ga_tid_base\"></script>" . $terminator;
    $script .= "<script>" . $terminator;
    $script .= "window.dataLayer = window.dataLayer || [];" . $terminator;
    $script .= "function gtag(){dataLayer.push(arguments);}" . $terminator;
    $script .= "gtag('js', new Date());" . $terminator;
    if ($ga_tid_base) {
      $script .= "gtag('config', '$ga_tid_base');" . $terminator;
    }
    $script .= "</script>" . $terminator;
  }
  elseif ($embed_ga_tracking_code == 'analytics') {
    $script .= "<!-- Google Analytics -->" . $terminator;
    $script .= "<script>" . $terminator;
    $script .= "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){" . $terminator;
    $script .= "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o)," . $terminator;
    $script .= "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)" . $terminator;
    $script .= "})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');" . $terminator;
    if ($ga_tid_base) {
      $script .= "ga('create', '$ga_tid_base', 'auto');" . $terminator;
      $script .= "ga('send', 'pageview');" . $terminator;
    }
    $script .= "</script>" . $terminator;
    $script .= "<!-- End Google Analytics -->" . $terminator;
  }

  return $script;
}

/**
 * Generates async embed code for Intel. The generated code is designed to be
 * used on Drupal generated pages and on static pages via copy and paste.
 *
 * @param string $type -
 *   l10i: only code to embed intel,
 *   ga: Google Analytics code using Google Analytics module settings
 *   combined: provides GA and Intel embed codes. Used for external pages
 * @param string $mode:
 *   null | internal: sets format for use on Drupal generated pages
 *   external: used to set formating for external page use
 * @param string $version: used to experiment with embed styles
 * @param string $terminator: specifies end of line terminator
 * @return string - embed code
 */
function intel_get_js_embed($type = 'l10i', $mode = 'external', $version = 'latest', $terminator = "") {
  $user = intel_get_current_user();

  $script = '';
  $l10i_ga_account = get_option('intel_ga_tid', '');
  // if l01i_ga_account not set, don't generate embed
  if (!$l10i_ga_account) {
    return $script;
  }
  $api_level = intel_api_level();

  $io_name = 'io';

  $debug = intel_debug_mode();
  if ($debug) {
    $terminator = "\n";
  }

  $l10i_domain_name = get_option('intel_domain_name', '');

  $l10i_ga_tracker_prefix = get_option('intel_ga_tracker_prefix', '');
  if (!$l10i_ga_tracker_prefix) {
    $l10i_ga_tracker_prefix = 'l10i';
  }

  $api_js_ver = trim(get_option('intel_l10iapi_js_ver', ''));
  if (!$api_js_ver) {
    $api_js_ver = INTEL_L10IAPI_JS_VER;
  }

  $js_embed_style = get_option('intel_l10iapi_js_embed_style', '');

  // set if api is installed locally with website, e.g. enterprise mode
  $api_local = 0;

  $wrap = ($mode == 'external') ? 1 : 0;

  if ($version == 'simple') {
    $wrap = 0;
    $script .= '<script>' . $terminator;
  }

  // generate GoA embed based on Google Analtyics module settings
  if (($type == 'ga') || ($type == 'combined')) {
    /*
    $query_string = '';
    $library_tracker_url = '.google-analytics.com/ga.js';
    $library_cache_url = 'http://www' . $library_tracker_url;
    if (get_option('googleanalytics_cache', 0) && $url = _googleanalytics_cache($library_cache_url)) {   
      $ga_src = 'ga.src = "' . $url . $query_string . '";';
    }
    else {
      $ga_src = "ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';";
    }
    $gaid = get_option('googleanalytics_account', '');
       
    $script .= "var _gaq = _gaq || [];" . $terminator;
    $script .= "_gaq.push(['_setAccount', '$gaid']);" . $terminator;
    if ($l10i_domain_name) {
      $script .= "_gaq.push(['_setDomainName', '$l10i_domain_name']);" . $terminator;
    }
    $script .= "_gaq.push(['_trackPageview']);" . $terminator;
    $script .= "(function() {" . $terminator;
    $script .= "var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;" . $terminator;
    $script .= $ga_src . $terminator;
    $script .= "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);" . $terminator;
    $script .= "})();" . $terminator;
    */
  }

  // generate Intel embed
  // Check if IAPI data access is available and alter io embed to enable
  // IAPI data syncing
  if ($api_level && (($type == 'l10i') || ($type == 'combined'))) {
    $library_path = intel()->libraries_get_path('LevelTen') . 'intel/scripts';
    $api_hostpath = intel_get_iapi_url();
    $a = explode('//', $api_hostpath);
    if (count($a) == 2) {
      $api_hostpath = $a[1];
    }
    // if api_hostpath is relative (begins with '/'), install is local
    if (substr($api_hostpath, 0 ,1) == '/') {
      $api_hostpath = $_SERVER['HTTP_HOST'] . $api_hostpath;
      $api_local = 1;
    }
    // verify compatible js_embed_style with api_level and $api_local
    if ($api_level == 'pro' || $api_level == 'basic') {
      if (!$js_embed_style) {
        $js_embed_style = 'property';
      }
      if (!$js_embed_style == 'property_dynsesinit' && $api_level == 'basic' ) {
        $js_embed_style = 'property';
      }
    }
    else {
      $js_embed_style = 'standard';
    }
    // if enterprise installation, use standard embed
    if ($api_local) {
      $js_embed_style = 'standard';
    }

    $sv = (get_option('intel_debug_mode', 0)) ? '' : '.min';
    //$script .= "// LevelTen Intelligence" . $terminator;
    //$script .= "var _l10iq = _l10iq || [];" . $terminator;
    if ($api_local) {
      $script .= 'var _l10iss = {"apiUrl": "' . $api_hostpath . '/", "apiLevel": "' . $api_level . '"};' . $terminator;
    }
    $l10iq_pushes = array();
    //$l10iq_pushes[] = array('_setAccount', $l10i_ga_account, $l10i_ga_tracker_prefix);
    $params = array();
    $params['name'] = $l10i_ga_tracker_prefix;
    if ($l10i_domain_name) {
      $params['cookieDomain'] =  $l10i_domain_name;
      //$l10iq_pushes[] = array('_setDomainName', $l10i_domain_name);
    }
    //if (!empty($user->uid)) {
    //  $params['userId'] =  '.';
    //}
    $l10iq_pushes[] = array('ga.create', $l10i_ga_account, 'auto', $params);

    //$ga_tid_base = get_option('intel_ga_tid_base', '');
    $sync_events_base = get_option('intel_sync_intel_events_base', '');
    if ($sync_events_base) {
      $ga_profile_base = get_option('intel_ga_profile_base', array());
      $ga_tid_base = !empty($ga_profile_base['propertyId']) ? $ga_profile_base['propertyId'] : '';
      $fields = array(
        'enhance' => 'base',
      );
      // if base ga profile is tracked via GTM, the name for the base profile tracker
      // should be gtm1 rather than an empty name.
      if (get_option('intel_tracker_is_gtm_base', '')) {
        $fields['name'] = 'gtm1';
      }
      $l10iq_pushes[] = array('addTracker', $ga_tid_base, $fields);
    }

    if (get_option('intel_tracking_anonymize_ip', 0)) {
      $l10iq_pushes[] = array('ga.set', 'anonymizeIp', true);
    }

    // trigger alter to enable other modules to add pushes
    intel_do_hook_alter('intel_js_embed_intel_push', $l10iq_pushes);
    //drupal_alter('intel_l10iq_pushes', $l10iq_pushes);

    if ($version == 'simple') {
      $script .= '</script>' . $terminator;
      $script .= '<script src="http://' . $api_hostpath . '/js/' . $api_js_ver . '/l10i' . $sv . '.js"></script>' . $terminator;
    }
    // default version (async)
    else {
      if ($s = trim(get_option('intel_custom_embed_script', ''))) {
        $script .= $s;
      } else {
        $l10ijs_propdir = '';
        $q_script = '';
        if ($js_embed_style != 'standard') {

          if (($api_level == 'pro') || ($api_level == 'basic')) {
            $l10ijs_propdir = '/p/' . $l10i_ga_account;
            //if ($js_embed_style != 'property') {
            if ($api_level == 'pro') {
              $q_script = "if(c.indexOf('l10i_s=')==-1){s='?t='+t}";
              //$q_script = "if(c.indexOf('l10i_s=')==-1){q='?t='+t}";
            }
          }
        }
        $l10ijs_path = '/js/' . $api_js_ver . '/l10i' . $sv . '.js';

        if (intel_is_no_api()) {
          $a = explode('//', get_site_url());
          $api_hostpath = $a[1];
          $purl = parse_url(plugin_dir_url(__DIR__));
          $l10ijs_path = $purl['path'] . 'js/l10i' . $sv . '.js';
          $l10ijs_propdir = '';
        }

        $script .= "(function(w,d,o,u,b,i,r,a,s,c,t){" . $terminator;
        $script .= "w['L10iObject']=r;" . $terminator;
        $script .= "t=1*new Date();" . $terminator;
        $script .= "w[r]=w[r]||function() {" . $terminator;
        $script .= "(w[r].q=w[r].q||[]).push(arguments);" . $terminator;
        $script .= "w[r].t=t" . $terminator;
        $script .= "}," . $terminator;
        $script .= "s='';" . $terminator;

        if ($l10ijs_propdir) {
          $script .= "a='l10i_bt=';" . $terminator;
          $script .= "d.cookie=a+t+';path=/';c=d.cookie;" . $terminator;
          $script .= "if(c&&c.indexOf(a)!=-1){u+=i;$q_script}" . $terminator;
        }
        $script .= "u+=b+s;" . $terminator;
        $script .= "a=d.createElement(o),b=d.getElementsByTagName(o)[0];a.async=1;a.src=u;b.parentNode.insertBefore(a,b)" . $terminator;
        $script .= "})(window,document,'script','//$api_hostpath','$l10ijs_path','$l10ijs_propdir','$io_name');" . $terminator;
        /*
        $script .= "d=q=c='';t=new Date().getTime();" . $terminator;
        $script .= "document.cookie='l10i_bt='+t+';path=/';c=document.cookie;if(c&&c.indexOf('l10i_bt=')!=-1){d='$l10ijs_propdir';$q_script}" . $terminator;
        $script .= "(function(i,s,o,g,r,a,m){" . $terminator;
        $script .= "i['L10iObject']=r;" . $terminator;
        $script .= "i[r]=i[r] || function() {" . $terminator;
        $script .= "(i[r].q=i[r].q||[]).push(arguments)" . $terminator;
        $script .= "}," . $terminator;
        $script .= "i[r].l=1*new Date();" . $terminator;
        $script .= "a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)" . $terminator;
        //$script .= "})(window,document,'script','//$api_hostpath'+d+'$l10ijs_path','_l10iq');" . $terminator;
        $script .= "})(window,document,'script','//$api_hostpath'+d+'$l10ijs_path','io');" . $terminator;
        */

        /*
        $script .= "(function() {" . $terminator;
        $script .= "var l10i=document.createElement('script'); l10i.type='text/javascript'; l10i.async=true;d=q=c='';t=new Date().getTime();" . $terminator;
        $script .= "document.cookie='l10i_bt='+t+';path=/';c=document.cookie;if(c&&c.indexOf('l10i_bt=')!=-1){d='$l10ijs_propdir';$q_script}" . $terminator;
        $script .= "l10i.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + '$api_hostpath'+d+'$l10ijs_path'+q;" . $terminator;
        $script .= "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(l10i, s);" . $terminator;
        $script .= "})();" . $terminator;
        */
      }
      /*
      else { // standard mode
        $fr = ($debug && 0) ? " + '?t=' + new Date().getTime()" : '';
        $script .= "(function() {" . $terminator;
        $script .= "var l10i = document.createElement('script'); l10i.type = 'text/javascript'; l10i.async = true;" . $terminator;
        $script .= "l10i.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + '$api_hostpath/js/$api_js_ver/l10i$sv.js'$fr;" . $terminator;
        $script .= "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(l10i, s);" . $terminator;
        $script .= "})();" . $terminator;
      }
      */
      if (!empty($l10iq_pushes) && is_array($l10iq_pushes)) {
        foreach ($l10iq_pushes AS $p) {
          $p = substr(json_encode($p),1);
          $p = substr($p, 0 ,-1);
          $script .= "$io_name(" . $p . ");" . $terminator;
        }
      }
    }
  }

  if ($wrap) {
    $script = '<script>' . $script . '</script>'; 
  }

  return $script;
}

function intel_js_alter(&$javascript) {
  $debug = intel_debug_mode();
  $options = array();

  // get intel options various modules have added to for intel
  if (!empty($javascript['settings']['data']) && is_array($javascript['settings']['data'])) {
    foreach ($javascript['settings']['data'] AS $i => $v) {
      if (!empty($v['intel'])) {
        $options[] = $v['intel'];
      }
    }
  }
  // search through javascript settings for main intel and google analytics settings
  $i = 0;
  $intel_i = -1;
  $ga_i = -1;
  while (isset($javascript[$i])) {
    if (substr($javascript[$i]['data'], 0, 10) == 'var _l10iq') {
      $intel_i = $i;
    }
    elseif (substr($javascript[$i]['data'], 0, 32) == '(function(w,d,o,u,b,i,r,a,s,c,t)') {
      $intel_i = $i;
    }
    //if (substr($javascript[$i]['data'], 0, 8) == 'var _gaq') {
    if (substr($javascript[$i]['data'], 0, 51) == '(function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]') {
      $ga_i = $i;
    }
    // if we have found both intel & ga js, exit loop
    if (($intel_i >= 0) && ($ga_i >= 0)) {
      break;
    }
    $i++;
  }

  if ($intel_i >= 0) {
    $options = drupal_array_merge_deep_array($options);
    // if google_analtyics script is added, turn on track_analtyics flag
    if ($ga_i == -1) {
      $options['config']['trackAnalytics'] = 0;
      //$javascript[$intel_i]['weight'] = $javascript[$ga_i]['weight'] + 1;
    }

    // embed new UA style calls using new format
    $pushstr = '';
    /*
    foreach ($options['pushes'] AS $method => $args) {
      // only move newer style calls withough leading _
      if (substr($method, 0 , 1) != '_') {
        $pushstr .= "oei('$method', " . json_encode($args) . ");\n";
        unset($options['pushes'][$method]);
      }
    }
    */
    //$json = drupal_json_encode($options);
    $io_name = 'io';
    $pushstr = $io_name . '("set", "config", ' . drupal_json_encode($options['config']) . ');' . "\n";
    //$str = '_l10iq.push(["set", "config", ' . drupal_json_encode($options['config']) . ']);' . "\n";
    if (isset($options['pushes']) && is_array($options['pushes'])) {
      foreach ($options['pushes'] as $cm => $push) {
        if (0 && $cm == 'setUserId') {
          $pushstr .= $io_name . '("' . $cm . '","' . $push[0][0];
          if (!empty($push[0][1])) {
            $pushstr .= '","' . $push[0][1];
          }
          $pushstr .= '");' . "\n";
        } else {
          $pushstr .= $io_name . '("' . $cm . '",' . drupal_json_encode($push) . ');' . "\n";
        }
        //$str .= '_l10iq.push(["' . $cm . '",' . drupal_json_encode($push) . ']);' . "\n";
      }
    }
    $javascript[$intel_i]['data'] .= $pushstr;
    //$javascript[$intel_i]['data'] = str_replace('_l10iq.push(["set",{}]);', $str, $javascript[$intel_i]['data']) . $pushstr;
    //$javascript[$intel_i]['data'] = str_replace('_l10iq.push(["set",{}]);', '_l10iq.push(["set",' . $json . ']);', $javascript[$intel_i]['data']) . $pushstr;
  }

  if ($debug && ($ga_i >= 0)) {
    if (get_option('intel_debug_ga_debug', 0)) {
      $javascript[$ga_i]['data'] = str_replace('/ga.js', '/u/ga_debug.js', $javascript[$ga_i]['data']);
    }
    $hitCallback = "if((typeof window._l10im=='object')&&(typeof window._l10im.markTime=='function')){_l10im.markTime('ga._trackPageview')}";
    $javascript[$ga_i]['data'] = str_replace('ga("send", "pageview")', 'ga("send", "pageview", {hitCallback: function () {' . $hitCallback . '}})', $javascript[$ga_i]['data']);
    $script = " if ((typeof _l10im=='object')&&(typeof _l10im.markTime=='function')){ga(function() { _l10im.markTime('ga.ready')})}";
    //$script = " if ((typeof _l10im == 'object') && (typeof _l10im.markTime == 'function')) { _gaq.push(function() { _l10im.markTime('ga._trackPageview'); }); }";
    $javascript[$ga_i]['data'] .= $script;
  }

}

/**************************
 * @param $page
 */
function intel_check_form_submission($page) {
  // TODO WP
  return;

  include_once INTEL_DIR . 'includes/class-intel-visitor.php';
  $vtk = IntelVisitor::extractVtk();

  $api_level = intel_api_level();

//$debug = 1;

  // data to be tracked via GA
  $track = intel_form_submission_track_defaults();
  // any url query elements that should be added to a redirect url
  $link_query = array();
  // form submission object
  $submission = intel_submission_construct();
  // intel settings associated with form
  $form_settings = array();
  // data about where the form was submitted
  $submit_context = array();
  // data about the CTA clicked to get to landing page
  $cta_context = array();
  // an array of fields to pass to intel's autofill form feature
  $autofill = array();
  $submission_data = array();
  $visitor = NULL;

  $vars = intel_form_submission_vars_default();

  // fire hook for modules to report if a form has been submitted by setting $submission->type
  foreach (module_implements('intel_form_submission_check') AS $module) {
    $function = $module . '_intel_form_submission_check';
    $function($submission, $track, $submit_context, $form_settings, $link_query);
  }

  if (!empty($submission->type)) {
    intel_process_form_submission($submission, $track, $submit_context, $form_settings, $link_query);
  }
}

function intel_form_submission_vars_default() {
  $vars = array();

  // form submission object
  $vars['submission'] = intel_submission_construct();
  // array of form data submitted keyed by field name
  $vars['submission_values'] = array();
  // data to be tracked via GA
  $vars['track'] = array();
  // array of properties to associated with visitor
  $vars['visitor_properties'] = array();
  // data about where the form was submitted
  $vars['submit_context'] = array();
  // data about the CTA clicked to get to landing page
  $vars['cta_context'] = array();
  // intel settings associated with form
  $vars['form_settings'] = array();
  // used to pass custom data to intel_process_form_submission hooks
  $vars['hook_context'] = array();
  // any url query elements that should be added to a redirect url
  $vars['link_query'] = array();
  return $vars;
}

/**
 * Processes form submission data. Three primary tasks are to format and save an
 * intel_submission, create or update the submitters intel_visitor entity and
 * format tracking responses for Google Analytics.
 *
 * @param $vars
 * (required) An associative array of essential data for processing form submission.
 * Elements are divided into two types, primary and additional.
 *
 * This function attempts to construct all elements with as little input as possible,
 * e.g. the primary data. The caller may provide additional information to fill
 * in data from other sources or as overrides using additional elements.
 *
 * Primary elements:
 * - 'submission': intel_submission entity object
 *   - 'type': (required) Type of form that was submitted. E.g. webform, hubspot, gravityform.
 *     This field is required to complete processing.
 *   - 'fid': (optional) Form bundle identifier for use with forms that allow creation of
 *     multiple variants. E.g. for webforms us node id, for gravityform use form_id
 *   - 'fsid': (optional) Form submission id of the record that stores the data.
 *   - 'form_title': (required) Human friendly name of the form that was submitted
 *   - 'form_uri': (optional) linkable reference to where the form can be viewed. *
 *   - 'submission_uri': (optional) linkable reference to where submission data
 *     can be viewed.
 * - 'track': The form submission intel event to trigger. If blank, no event is
 *   triggered. The array can either be custom created by setting each element or
 *   using the 'name' element to copy settings from intel event info.
 *   - 'name': (optional) The intel event id to trigger. If set, the intel event
 *     with this name will be merged into the track array.
 *   - 'category': (optional) Used to set GA event category if 'name' property not
 *     used.
 *   - 'value': (optional) Enables intel event default value to be overridden
 *     on a per submission basis.
 *
 * Additional elements:
 * - 'submission':
 *   - 'response_page_uri': uri of the thank you page. The page visitor is redirected
 *     to after the form is submitted. This property is a shortcut to submitting
 *     response_page_host and response_page_path properties. There is no need to
 *     set both.
 *   - 'response_page_uri': uri of thank you page
 *   - 'response_page_id': primary key for thank you page such as post id or nid
 *   - 'form_page_uri': uri of the page the form was submitted from. Note for embedded
 *     forms this is different from the 'form_uri'. This property is a shortcut to submitting
 *     form_page_host and form_page_path properties. There is no need to set both.
 *   - 'form_page_id': primary key for the page from was submitted from such as
 *     post id or nid
 *   - 'cta_page_uri': uri of the thank you page. The page visitor is redirected
 *     to after the form is submitted. This property is a shortcut to submitting
 *     response_page_host and response_page_path properties. There is no need to
 *     set both.
 *   - 'cta_page_id': primary key for thank you page such as post id or nid
 *   - 'submission': a constructed submission entity. Typical behavior is for the
 *     function to create a submission entity using primary form_XXX elements in vars.
 *     Any additional submission entity properties beyond primary elements should
 *     be set directly on this object.
 * - 'track': for additional settings see a tracking.
 * - 'visitor_properties': any visitor props to merge into visitor entity
 * - 'submit_context': array of meta data about where the form was submitted to
 *   configure Landing page conversion events.
 * - 'cta_context': array of meta data about the call to action that was clicked
 *   to get to the landing page to configure CTA conversion events.
 * - 'form_settings': place to store settings needed in submission_data hooks.
 *   the function ignores any data set in this property.
 *
 * @return string|void
 */
function intel_process_form_submission($vars) {
Intel_Df::watchdog('intel_process_form', 'vars', $vars);
  // set defaults
  $vars += intel_form_submission_vars_default();

  $submission = &$vars['submission'];
  $track = &$vars['track'];
  $submit_context = &$vars['submit_context'];
  $cta_context = &$vars['cta_context'];
  $form_settings = &$vars['form_settings'];
  $link_query = &$vars['link_query'];
  $visitor_properties = &$vars['visitor_properties'];

  // if no submission type set, exit
  if (empty($submission->type)) {
    return;
  }

  // construct form_uri if not set in vars
  if (empty($submission->form_uri)) {
    $submission->form_uri = ":{$submission->type}:";
    if (!empty($submission->fid)) {
      $submission->form_uri .= $submission->fid;
    }
  }

  // construct submission_uri, host, and path properties
  if (empty($submission->submission_uri)) {
    $submission->submission_uri = $submission->form_uri . ':' . $submission->fsid;
  }

  $api_level = intel_api_level();

  // check if form submission has been submitted before, e.g. a duplicate submission
  // this can only be done if there is a fsid
  if (!empty($submission->fsid)) {
    $vars2 = array(
      'type' => $submission->type,
      'fid' => $submission->fid,
      'fsid' => $submission->fsid,
    );
    $submission0 = intel_submission_load_by_vars($vars2);

    // TODO check this data for a while to assure uniqueness of fsids, then exit if previous submission exists.
    if (!empty($submission0->sid) && empty($_GET['debug']) && empty($debug)) {
      // form submission already created
      $vars2 = array(
        '!submission0' => print_r($submission0, 1),
        '!get' => print_r($_GET, 1),
        '!cookie' => print_r($_COOKIE, 1),
      );
      //watchdog('intel', "form already submitted on form submission. <br>\n submission0=!submission0<br>\n<br>\nget=!get<br>\n<br>\ncookie=!cookie", $vars2, WATCHDOG_DEBUG);
      return '';
    }
  }

  /* TODO: removed over GDPR concerns storing PII without export/delete
  if (isset($vars['submission_values'])) {
    $submission->data['submission_values'] = $vars['submission_values'];
  }
  if (isset($vars['submission_post'])) {
    $submission->data['submission_post'] = $vars['submission_post'];
  }
  */
  
  // load file for scoring data
  require_once INTEL_DIR . "includes/intel.ga.php";
  
  // load current user
  // check if vid is set on submission to load a specific visitor
  if (!empty($submission->vid)) {
    $visitor = intel_visitor_load_or_create((int)$submission->vid);
  }
  else {
    $visitor = intel_visitor_load_or_create('user');
  }

  if (empty($visitor->vtk) && !empty($_SESSION['intel_vid'])) {
    $visitor = intel_visitor_load_or_create($_SESSION['intel_vid']);
  }

  // Load visitor data if available in IAPI, skip otherwise
  if ($api_level == 'pro') {
    if (!empty($visitor->vtk)) {
      try {
        // don't load apiVisitor until conversion analytics is implemented
        if (INTEL_PLATFORM == 'drupal') {
          $visitor->apiVisitorLoad();
        }
      }
      catch (Exception $e) {
        // do nothing
      }
    }
  }

  // merge in any visitor properties set by the input
  $property_options = array(
    'source' => 'form',
  );
  if(!empty($visitor_properties) && is_array($visitor_properties)) {
    // if name not set, but givenName is, construct name from givenName & familyName
    if (empty($visitor_properties['data.name']) && !empty($visitor_properties['data.givenName'])) {
      $visitor_properties['data.name'] = $visitor_properties['data.givenName'];
      if (!empty($visitor_properties['data.familyName'])) {
        $visitor_properties['data.name'] .= ' ' . $visitor_properties['data.familyName'];
      }
    }

    foreach ($visitor_properties as $k => $fv) {
      $kt = $k;
      if (strpos($k, 'data.') === 0) {
        $kt = substr($k, 5);
      }
      $a = explode(':', $kt);
      $prop_name = $a[0];
      $var_name = !empty($a[1]) ? $a[1] : '@value';
      $value = $visitor->getProp($kt);
      $value[$var_name] = $fv;
      $visitor->setProp($prop_name, $value, $property_options);
    }
  }

  if (!empty($_GET['debug']) || !empty($debug)) {
    intel_d('$visitor=');//
    intel_d($visitor);//

    $api_data = $visitor->getApiVisitor();
    intel_d('$api_data=');//
    intel_d($api_data);//
    $error = $visitor->apiVisitorLoadError;
    intel_d("$error");//
  }
  //watchdog('intel_fs_visitor', print_r($visitor, 1));
  //watchdog('intel_fs_api_data', print_r($api_data, 1));
  
  $a = explode('//', get_home_url());
  $host = $a[1];

  // check thankyou page settings
  if (INTEL_PLATFORM == 'drupal') {
    $node = menu_get_object();
    if (!empty($node) && isset($node->field_track_submission[$node->language][0]['value'])) {
      $vars['intel_event_id'] = $node->field_track_submission[$node->language][0]['value'];
    }
    if (!empty($node) && isset($node->field_track_submission_value[$node->language][0]['value'])) {
      $vars['intel_event_id'] = $node->field_track_submission_value[$node->language][0]['value'];
    }
    $submission->response_page_path = url($_GET['q']);
    $submission->response_page_host = $host;
    if (!empty($node)) {
      $submission->response_page_id = $node->nid;
    }
  }

  $scorings = intel_get_scorings();

  $intel_events = intel_get_intel_event_info();

  // if vid not set on visitor, save to create vid
  // Only make IAPI request if vtk is available (level == pro)
  if ($api_level == 'pro') {
    // only save if at least one identifier exists
    if (empty($visitor->vid) && !empty($visitor->identifiers)) {
      $visitor->save();
    }

    if (!empty($visitor->vid)) {
      // update visitor attributes in analytics cookies
      // TODO check if this should be done here
      // va cookie is no longer supported in classic analytics
      // TODO: change to work with l10i_va cookie
      $va = $visitor->apiVisitor->extractCookieVa();
      if (isset($va)) {
        $visitor->setVar('data', 'analytics', '', $va);
      }

      $submission->vid = $visitor->vid;
    }
    else {
      $submission->vid = 0;
    }
  }

  // check if api vtk was loaded succesfully
  if (!empty($visitor->apiVisitor->vtk)) {

    // if submit context not set in vars argument, fetch it from api
    if (empty($submit_context)) {
      $fsi = $visitor->getVar('api_session', 'formSubmit', '_updated');
      if (empty($fsi)) {
        $fsi = $visitor->apiVisitor->getFlag('lfs');  // _updated is a better key for cross site forms
      }
      $submit_context = $visitor->getVar('api_session', 'formSubmit', $fsi);
    }
    if (!empty($submit_context)) {
      $referrer = !empty($submit_context['systemPath']) ? $submit_context['systemPath'] : $submit_context['location'];
      // strip query string
      $a = explode("?", $referrer);
      $referrer = $a[0];
      $lp_urlc = parse_url($submit_context['location']);
      $submission->form_page_host = $lp_urlc['host'];
      $submission->form_page_path = $lp_urlc['path'];

      if (!empty($submit_context['systemPath'])) {
        $a = explode('/', $submit_context['systemPath']);
        $lp_node = node_load($a[1]);
        $submission->form_page_id = !empty($lp_node->nid) ? $lp_node->nid : 0;
        $hook_context = array(
          'type' => 'form_submission',
          'form_settings' => $form_settings,
          'submit_context' => $submit_context,
          'cta_context' => $cta_context,
          'track' => $track,
          'submission' => $submission,
        );
        intel_execute_response_redirect_if_set($lp_node, $node, $link_query, $hook_context);
      }

      if (!empty($submit_context['isLandingpage'])) {
        $category = Intel_df::t('Landing page conversion') . '!';
        $track_conversion_value = (isset($scorings['landing_page_conversion'])) ? $scorings['landing_page_conversion'] : 0;      

        if (isset($lp_node->field_track_conversion)) {
          $track_conversion = $node->field_track_submission[$node->language][0]['value'];
          if (strpos($track_conversion, 'conversion') !== FALSE) {
            $category = Intel_df::t('Landing page conversion');
          }
          elseif (strpos($track_conversion, 'conversion!') === FALSE) {
            $category = '';
          }
        }
        if (isset($lp_node->field_track_conversion_value) && (trim($lp_node->field_track_conversion_value) == '')) {
          $track_conversion_value = $node->field_track_submission_value[$node->language][0]['value'];
        }

        if ($category) {
          $call = array(
            'eventCategory' => $category,
            'eventAction' => !empty($submit_context['pageTitle']) ? $submit_context['pageTitle'] : (!empty($lp_node->title) ? $lp_node->title : ''),
            'eventLabel' => $referrer,
            'eventValue' => $track_conversion_value,
            'nonInteraction' => FALSE,
          );
          if (!empty($submit_context['location'])) {
            $call['location'] = $submit_context['location'];
            if (!empty($submit_context['pageTitle'])) {
              $call['title'] = $submit_context['pageTitle'];
            }
            if (!empty($submit_context['customVars'])) {
              $call['customVars'] = $submit_context['customVars'];
            }
          }
          intel_add_page_intel_push(array('event', $call));
        }
      }
      //unset($visitor->session_data['form_submit'][$fsi]);
      
      // check if CTA was used to get there
      $cta_clicks = $visitor->getVar('api_session', 'ctaClick');
      $count = 0;
      if (!empty($cta_clicks) && is_array($cta_clicks)) {
        foreach ($cta_clicks AS $index => $click) {
          // filter "meta" elements
          if (substr($index, 0, 1) == '_') {
            continue;
          }
          $href_urlc = intel_parse_url($click['href'], $submit_context['location']);
//dsm("$referrer == {$href_urlc['system_path']} || ({$lp_urlc['host']} == {$href_urlc['host']} && {$lp_urlc['path']} == {$href_urlc['path']})");
          if ($referrer == $href_urlc['system_path'] || ($lp_urlc['host'] == $href_urlc['host'] && $lp_urlc['path'] == $href_urlc['path'])) {
            $cta_context = $click;
            $value = (isset($scorings['cta_conversion'])) ? $scorings['cta_conversion'] : 0;
            if (!empty($click['eventLabel']) && substr($click['eventLabel'], 0, 6) == 'block/') {
              // TODO make work with both blocks and beans
              $delta = substr($click['label'], 6);
              $submission->cta_id = $delta;
              $meta = cta_bean_meta_load($delta);
              if (isset($meta['data']['ga_event']['conversion']['value'])) {
                $value = $meta['data']['ga_event']['conversion']['value'];
              }
            }
            $call = array(
              'eventCategory' => Intel_Df::t('CTA conversion!'),
              'eventAction' => $click['eventAction'],
              'eventLabel' => $click['eventLabel'],
              'eventValue' => $value,
              'nonInteraction' => FALSE,
            );
            if (!empty($click['location'])) {
              $call['location'] = $click['location'];
              if (!empty($click['pageTitle'])) {
                $call['title'] = $click['pageTitle'];
              }
              if (!empty($click['customVars'])) {
                $call['customVars'] = $click['customVars'];
              }
            }
            intel_add_page_intel_push(array('event', $call));

            $urlc = parse_url($click['location']);
            $submission->cta_page_host = $urlc['host'];
            $submission->cta_page_path = $urlc['path'];
            if (!empty($click['systemPath'])) {
              $a = explode('/', $click['systemPath']);
              if (($a[0] == 'node') && !empty($a[1]) && is_numeric($a[1])) {
                $submission->cta_page_id = $a[1];
              }
            }
            break;
          }
          // only check last 20 CTA clicks
          if ($count++ > 20) {
            break;
          }
        }
      }
    }
  }

  // if submit_context not available in api, check redirect using cookie
  if (INTEL_PLATFORM == 'drupal') {
    if (intel_include_library_file('class.visitor.php')) {
      $loc0 = \LevelTen\Intel\ApiVisitor::getCookie('l10i_lf');
    }
    if (empty($submit_context['location']) && !empty($loc0)) {
      $loc_comps = intel_parse_url($loc0);
      if (!empty($loc_comps['system_path'])) {
        $a = explode('/', $loc_comps['system_path']);
        if (($a[0] == 'node') && is_numeric($a[1])) {
          $lp_node = node_load($a[1]);
          $hook_context = array(
            'type' => 'form_submission',
            'form_settings' => $form_settings,
            'submit_context' => $submit_context,
            'cta_context' => $cta_context,
            'track' => $track,
            'submission' => $submission,
          );
          intel_execute_response_redirect_if_set($lp_node, $node, $link_query, $hook_context);
        }
      }
    }
  }

  // set intel_event
  if (!empty($track['name']) && !empty($intel_events[$track['name']])) {
    $intel_event = $intel_events[$track['name']];
  }

  // verify value is numeric, cast to int or unset value
  if (isset($track['value'])) {
    if (is_numeric($track['value'])) {
      $track['value'] = intval($track['value']);
    }
    else {
      unset($track['value']);
    }
  }

  // note track['value'] logic for $track['value_contact_exists'] is below
  // with logic to determine if $visitor is a new contact

  // if value is not set in track, enable scorings value to override
  if (!isset($track['value']) && isset($track['name'])) {
    $k = 'event_' . $track['name'];
    if (!empty($intel_event['goal_name'])) {
      $k = 'goal_' . $intel_event['goal_name'];
    }

    if (isset($scorings[$k])) {
      $track['value'] = $scorings[$k];
    }
    else {
      $track['value'] = 0;
    }
  }

  // merge in intel_event info maintaining any input properties as overrides
  if (!empty($intel_event)) {
    $track += $intel_event;
  }

  if (empty($track['event_category']) && !empty($track['category'])) {
    $track['event_category'] = $track['category'];
  }

  if (!empty($track['valued_event']) && substr($track['event_category'], -1) != '!') {
    $track['event_category'] .= '!';
  }

  if (!isset($track['action'])) {
    $track['action'] = !empty($submission->form_title) ? $submission->form_title : '';
  }

  if (!isset($track['label']) && !empty($submission->form_uri)) {
    $track['label'] = !empty($submission->form_uri) ? $submission->form_uri : '';
  }

  if (!isset($track['oa'])) {
    $track['oa'] = array();
  }

  if (!isset($track['oa']['rc'])) {
    $track['oa']['rc'] = 'form';
  }
  if (!isset($track['oa']['rt'])) {
    $track['oa']['rt'] = $submission->type;
  }
  if (!isset($track['oa']['ri']) && !empty($submission->form_uri)) {
    $track['oa']['ri'] = $submission->form_uri;
  }
  if (!isset($track['oa']['rk']) && !empty($submission->fid)) {
    $track['oa']['rk'] = $submission->fid;
  }
  if (!isset($track['oa']['2rc'])) {
    $track['oa']['2rc'] = 'submission';
  }
  if (!isset($track['oa']['2ri']) && !empty($submission->submission_uri)) {
    $track['oa']['2ri'] = $submission->submission_uri;
  }
  if (!isset($track['oa']['2rk']) && !empty($submission->fsid)) {
    $track['oa']['2rk'] = $submission->fsid;
  }

  if (!empty($_GET['debug']) || !empty($debug)) {
    //intel_d('$node'); intel_d($node);//
    //intel_d('$scorings'); intel_d($scorings);//
    intel_d('$scorings'); intel_d($scorings);//
    intel_d('$intel_events'); intel_d($intel_events);//
    intel_d('$track'); intel_d($track);//
  }

  /*
  if ($track['category_id'] == 'form_submission') {
    $track['category'] = __('Form submission', 'intel');
    $track['value'] = ($track['value'] != '') ? $track['value'] : 0;
  }
  elseif (isset($intel_events['submission_' . $track['category_id']])) {
    $track['category'] = $intel_events['submission_' . $track['category_id']]['category'];
    $track['value'] = ($track['value'] != '') ? $track['value'] : $scorings[$track['category_id']];
  }
  elseif (empty($track['category'])) {
    $track['category'] = __('Form submission', 'intel') . '!';
    $track['value'] = ($track['value'] != '') ? $track['value'] : $scorings['form_submission'];
  }
  */
  
  if (empty($visitor->contact_created) && !empty($visitor->email)) {
    $visitor->setContactCreated(time());
  }
  // check if alternate value for contact exists is set
  elseif (isset($track['value_contact_exists'])) {
    $track['value'] = intval($track['value_contact_exists']);
  }
  $visitor->setLastActivity(time());
  
  // throw hook_intel_form_submission_data to enable modules to alter visitor, submission and track data
  $hook_data = array(
    'visitor' => &$visitor,
    'submission' => &$submission,
    'track' => &$track,
  );
  $hook_context = array(
    'type' => 'form_submit',
    'visitor' => &$visitor,
    'submission' => &$submission,
    'track' => &$track,
    'form_settings' => $form_settings,
    'submission_values' => isset($vars['submission_values']) ? $vars['submission_values'] : NULL,
    'submit_context' => $submit_context,
    'cta_context' => $cta_context,
    'hook_context' => isset($vars['hook_context']) ? $vars['hook_context'] : NULL,
  );
//Intel_Df::watchdog('intel_process_form_submissionhook_presave', 'hook_data', $hook_data);
  //$hook_data = apply_filters('intel_form_submission_data_presave', $hook_data, $hook_context);
  $hook_data = intel_do_hook('intel_form_submission_data_presave', $hook_data, $hook_context);

  // save submission object
  if (!empty($submission->vid)) {
//Intel_Df::watchdog('intel_process_form', 'submission', $submission);
    $sid = intel_submission_save($submission);
    if (empty($submission->sid)) {
      $submission->sid = $sid;
    }
  }

  // if submission path does not exist, set default
  if (empty($track['submission_path']) && !empty($sid)) {
    $track['submission_path'] = 'submission/' . $sid;
  }

  if (!empty($submission->sid)) {
    $track['oa']['3rk'] = $submission->sid;
    $track['oa']['3ri'] = ':intel_submission:' . $submission->sid;
  }

  // process any saved visitor attributes acquired from the form submission
  $va1 = $visitor->getVar('visitor', 'attributes', '', array());
  if (!empty($va1)) {
    $visitor_attrs_info = intel_get_visitor_attribute_info();
    foreach ($va1 AS $key => $value) {
      if (is_array($value)) {
        foreach ($value AS $key2 => $value2) {
          intel_add_page_intel_push(array('set', "va.$key.$key2", $value2));
        }
      }
      else {
        intel_add_page_intel_push(array('set', "va.$key", $value));
      }
    }
  }

  //$userId = IntelVisitor::extractUserId();

  //intel_set_ga_userid($visitor);
  /*
  if ($api_level == 'pro' && !empty($visitor->identifiers['vtk'][0])) {
    $vtk = $visitor->identifiers['vtk'][0];
    $data = array(
      'userId' => $vtk,
    );
    intel_add_page_intel_push(array('setUserId', $data));
    // if visitor already exists (with original vtk), clear out identifiers
    if ($vtk != intel()->vtk) {
      $visitor->deleteIdentifierValue('vtk', intel()->vtk);
      $visitor->deleteIdentifierValue('gacid', intel()->gacid);
    }
  }
  */

  // save visitor
  if (!empty($visitor->vid)) {
//Intel_Df::watchdog('intel_process_form', 'visitor', $visitor);
    intel_visitor_save($visitor);
  }

  // queue to sync visitor data
  // add delay to wait for ga data to populate
  intel_add_visitor_sync_request($visitor, 30 * 60);

  // enable other plugins to alter track
  $track = apply_filters('intel_form_submission_track_alter', $track, $hook_context);

  // create form submission intel event
  if (!empty($track['event_category'])) {
    $call = array(
      'eventCategory' => $track['event_category'],
      'eventAction' => $track['action'],
      'eventLabel' => $track['label'],
      'eventValue' => $track['value'],
      'nonInteraction' => FALSE,
      'oa' => $track['oa'],
    );

//Intel_Df::watchdog('intel_process_form', 'intel_push', $call);
    intel_add_page_intel_push(array('event', $call));
  }



  // add known attribute to visitor
  if (!empty($visitor->contact_created)) {
    intel_add_page_intel_push(array('set', "va.kn", ''));
  }

  /*
  if (module_exists('rules')) {
    rules_invoke_event('intel_form_submission', $submission->type, $submission->fid, $visitor);
  }
  */

  // temp code to track submission for testing
  if (intel_debug_mode()) {
    $vars = array(
      '!submit_context' => print_r($submit_context, 1),
      '!visitor' => print_r($visitor->getProperties(), 1),
      '!submission' => print_r($submission, 1),
      '!get' => print_r($_GET, 1),
      '!cookie' => print_r($_COOKIE, 1),
    );
    //watchdog('intel', "form submission on form submission. <br>\n visitor=!visitor <br>\n<br>\nsubmission=!submission <br>\n<br>\nsubmit_context=!submit_context <br>\n<br>\nget=!get <br>\n<br>\ncookie=!cookie", $vars, WATCHDOG_DEBUG);
  }
  
  if (!empty($_GET['debug']) || !empty($debug)) {
    intel_d('$submit_context='); intel_d($submit_context);//
    intel_d('$visitor='); intel_d($visitor);//
    intel_d('$submission='); intel_d($submission);//
    intel_d('tracking $call'); intel_d($call);//
  }

  // hook to allow other plugins to save data
  intel_do_hook_action('intel_form_submission_data_save', $hook_context);
  //do_action('intel_form_submission_data_save', $hook_context);
}

function intel_set_ga_userid($visitor) {
  //Intel_Df::watchdog('intel_set_ga_userid vtks', print_r($visitor->identifiers['vtk'], 1));
  if (intel_api_level() == 'pro' && !empty($visitor->identifiers['vtk'][0])) {
    $vtk = $visitor->identifiers['vtk'][0];
    $data = array(
      'userId' => $vtk,
    );
    //Intel_Df::watchdog('intel_set_ga_userid vtk', print_r($vtk, 1));
    intel_add_page_intel_push(array('setUserId', $data));
    // if visitor already exists (with original vtk), clear out identifiers
    if ($vtk != intel()->vtk) {
      $visitor->deleteIdentifierValue('vtk', intel()->vtk);
      // don't delete gacid, may be needed to update visitor attributes for
      // prior cids.
      //$visitor->deleteIdentifierValue('gacid', intel()->gacid);
    }
  }
}

/**
 * Determines of redirect has been set on
 * @param $lp_node - the landing page node
 * @param $menu_object - the object returned from get_menu_object, i.e. the
 * current node
 */
function intel_execute_response_redirect_if_set($lp_node, $menu_object = null, $link_query = array(), $hook_context = array()) {
  // check if redirect set
  if (isset($lp_node->language)) {
    $sys_path = '';
    $url = '';
    if (isset($lp_node->field_thankyou[$lp_node->language][0]['target_id'])) {
      $nid = $lp_node->field_thankyou[$lp_node->language][0]['target_id'];
      $sys_path = $url = 'node/' . $nid;
    }
    elseif (isset($lp_node->field_redirect_url[$lp_node->language][0]['url'])) {
      $url = trim($lp_node->field_redirect_url[$lp_node->language][0]['url']);
      $sys_path = drupal_get_normal_path($url);
    }

    // if url set and sys_path does not equal q, (on same page) do redirect.
    if ($url && ($sys_path != $_GET['q'])) {
      // cache cookies in Drupal session in case varnish or other caching is filtering
      // cookies
      intel_session_cookie_merge();
      $options = array('query' => $link_query);

      // allow modules to alter redirect
      $hook_context['lp_node'] = $lp_node;
      $hook_context['node'] = $menu_object;
      drupal_alter('intel_form_submission_redirect', $url, $options, $hook_context);
      drupal_goto($url, $options);
      exit;
    }
  }
}

function intel_session_cookie_merge() {
  $c0 = array();
  if (!empty($_SESSION['l10i_cookie']) && is_array($_SESSION['l10i_cookie'])) {
    $c0 = $_SESSION['l10i_cookie'];
  }
  $_SESSION['l10i_cookie'] = $_COOKIE + $c0;
}

function intel_setcookie($name, $value, $time = 0, $path = '/', $domain = '', $raw = 0) {
  if (!$domain) {
    $domain = get_option('intel_domain_name', '');
  }
  if (!$domain) {
    //$domain = '.';
  }

  //Intel_Df::watchdog('intel_setcookie', "$name\n$value\n$time\n$path\n$domain\n$raw");

  // the setcookie function encodes spaces to +, which are not decoded properly
  // the setrawcookie with the rawurlencode filter solves this problem.
  if ($raw) {
    setrawcookie($name, rawurlencode($value), $time, $path, $domain);
  }
  else {
    setcookie($name, $value, $time, $path, $domain);
  }
}

function intel_setrawcookie($name, $value, $time = 0, $path = '/', $domain = '') {
  intel_setcookie($name, $value, $time, $path, $domain, 1);
}

function intel_deletecookie($name) {
  if (isset($_COOKIE[$name])) {
    unset($_COOKIE[$name]);
  }
  intel_setcookie($name, '', time() - 999);
}

/*
add_action('init', 'intel_test_init');
function intel_test_init() {
  $a = array(
    array(
      'event',
      array(
        'eventCategory' => 'Test A',
        'eventAction' => 'Test',
      ),
    ),
  );
  $json = json_encode($a);
  //$json = str_replace(' ', '%20', $json);
  //$json = htmlentities($json);
  intel_setrawcookie('l10i_push', $json);
}
*/

/**
 * Syncs CMS user with visitor
 * @param $account
 */
function intel_visitor_sync_user($account) {

  if (INTEL_PLATFORM == 'drupal') {
    global $user;

    $uid = $user->uid;
    $email = $user->mail;
    $name = $account->name;
    // TODO build a more configurable version for blocking user roles
    // Don't create visitor with identifier merges for accounts where muliple people might login

    // Don't autocreate visitor for user 1
    if ($user->uid == 1) {
      return;
    }

    // Don't autocreate visitor for admininistrators
    if (is_array($user)) {
      foreach ($user->roles AS $role) {
        if ($role == 'administrator') {
          return;
        }
      }
    }

    // if account is currently logged in user, set $vtk
    // otherwise another user is saving user data
    $vtk = ($uid && ($user->uid == $account->uid)) ? IntelVisitor::extractVtk() : '';
  }
  elseif (INTEL_PLATFORM == 'wp') {
    $uid = $account->ID;
    $email = $account->user_email;
    $name = $account->display_name;
    $vtk = IntelVisitor::extractVtk();
  }



  // try loading by uid
  //$visitor = intel_visitor_load($uid, FALSE, 'uid');
  $visitor = intel_visitor_load_by_identifiers(array('uid' => $uid));

  if (!$visitor) {
    // try loading by email
    $visitor = intel_visitor_load_by_identifiers(array('email' => $email));
  }

  if (!$visitor && $vtk) {
    // try loading by vtk
    $visitor = intel_visitor_load($vtk, FALSE, 'vtk');
  }

  // if associated visitor not found, create new one
  if (!$visitor) {
    $visitor = intel_visitor_create();
  }

  // set identifiers
  $visitor->setIdentifier('uid', $uid);
  $visitor->setIdentifier('email', $email);
  if ($vtk) {
    $visitor->setIdentifier('vtk', $vtk);
    $visitor->last_activity = REQUEST_TIME;
  }
  if (empty($visitor->name)) {
    $visitor->setName($name);
  }
  $visitor->save();
}



// handle user login / registration
if (INTEL_PLATFORM == 'drupal') {

  /**
   * Implements hook_user_login()
   * @param $edit
   * @param $account
   */
// TODO: This causes problems with user session management.
// for some odd reason the intel_add_page_intel_push causes the user to be logged out
  function intel_user_login(&$edit, &$account) {
    return;


    $visitor = intel_visitor_load($account->uid, FALSE, 'uid');

    if (!$visitor) {
      intel_visitor_sync_user($account);
    }
    else {
      $visitor->last_activity = REQUEST_TIME;
      $visitor->save();
    }


    $call = array(
      'eventCategory' => 'User login',
      'eventAction' => 'User login form',
      'eventLabel' => 'user/' . $account->uid,
      'eventValue' => 0,
      'nonInteraction' => FALSE,
      'location' => 'user',
    );
    intel_add_page_intel_push(array('event', $call));

//$pushes = intel_get_page_intel_pushes();
//watchdog('intel_user_login', print_r($pushes, 1));
  }

  /**
   * Implements hook_user_insert()
   */
  function intel_user_insert(&$edit, $account, $category) {
    intel_visitor_sync_user($account);
  }

  /**
   * Implements hook_user_update()
   */
  function intel_user_update(&$edit, $account, $category) {
    intel_visitor_sync_user($account);
  }

  function intel_form_user_register_form_alter(&$form, &$form_state) {
    // add submit handler
    $form['#submit'][] = 'intel_user_register_form_submit';
  }

  function intel_user_register_form_submit(&$form, &$form_state) {
    // create form submission intel event
    $call = array(
      'eventCategory' => 'User registration',
      'eventAction' => 'User registration form',
      'eventLabel' => 'user',
      'eventValue' => 0,
      'nonInteraction' => FALSE,
      'location' => 'user',
    );
    intel_add_page_intel_push(array('event', $call));
    $pushes = intel_get_page_intel_pushes();
    //watchdog('intel_user_register_form_submit', print_r($pushes, 1));
  }
}
elseif (INTEL_PLATFORM == 'wp') {

  /**
   * Implements hook_wp_login()
   * @param $user_login
   * @param $account
   */
  function intel_user_login($user_login, $account) {
    //Intel_Df::watchdog('intel_user_login $user_login', print_r($user_login, 1));

    // TODO intentionally disabled till GA tracking on WP admin pages issue can
    // be resolved.
    if (empty($account->ID)) {
      return;
    }
    $api_level = intel_api_level();

    // only load/create visitor if vtk is available (pro) to sync with other data
    // sources
    if ($api_level == 'pro') {
      $visitor = intel_visitor_load_by_identifiers(array('uid' => $account->ID));

      if (!$visitor) {
        intel_visitor_sync_user($account);
      }
      else {
        $visitor->last_activity = REQUEST_TIME;
        $visitor->save();
      }

      // combine visitor sessions
      intel_set_ga_userid($visitor);
    }

    $event = intel_intel_event_load('wp_user_login');

    $call = intel_filter_event_for_push($event);
    $call['eventAction'] = Intel_Df::t('User login form');
    $call['eventLabel'] = ':user:' . $account->ID;

    intel_add_page_intel_push(array('event', $call));
  }
  // register hook_wp_login()
  add_action('wp_login', 'intel_user_login', 10, 2);
}


/**
 * Implements hook_comment_post
 * @param $comment_ID
 * @param $comment_approved
 */
add_action( 'comment_post', 'intel_comment_post', 10, 3 );
function intel_comment_post( $comment_ID, $comment_approved, $commentdata = array() ) {

  // don't save "visitors" that don't have a vtk. They are likely spam bots
  $vtk = IntelVisitor::extractVtk();
  if (empty($vtk)) {
    return;
  }
  // fetch comment
  $args = array(
    'comment_ID' => $comment_ID,
  );
  $commentdata = get_comment( $comment_ID );

  $vars = intel_form_submission_vars_default();

  $submission = &$vars['submission'];
  $track = &$vars['track'];

  $submission->type = 'wp_comment';
  $submission->fid = ''; //$commentdata->comment_post_ID;
  $submission->fsid = $commentdata->comment_ID;
  //$submission->form_uri = ':wp_comment:';
  $submission->form_page_uri = ':post:' . $commentdata->comment_post_ID;
  $submission->form_page_id = $commentdata->comment_post_ID;

  $track['name'] = 'wp_comment';
  $track['action'] = (strlen($commentdata->comment_content) > 80) ? substr($commentdata->comment_content, 0, 77) . '...' : $commentdata->comment_content;

  $vars['visitor_properties'] = array();
  if (!empty($commentdata->comment_author)) {
    $vars['visitor_properties']['data.name'] = $commentdata->comment_author;
  }
  if (!empty($commentdata->comment_author_email)) {
    $vars['visitor_properties']['data.email'] = $commentdata->comment_author_email;
  }

  intel_process_form_submission($vars);

  // save the page flushes to cache
  intel_save_flush_page_intel_pushes();
}

add_action( 'pre_get_posts', 'intel_pre_get_posts');
function intel_pre_get_posts($query) {
  // check for site search which uses the s query
  if (!empty($query->query['s'])) {
    //$event = intel()->intel_event_info('wp_search_form_submission');
    $event = intel()->intel_event_info('wp_search_result_click');
    if (!empty($event)) {
      $eventDef = intel_filter_event_for_push($event);
      intel_add_page_intel_push(array('event', $eventDef));
    }
  }
}