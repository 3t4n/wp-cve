<?php
/**
 * @file
 * Administration of visitors
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */


function intel_visitor_admin_settings_form($form, $form_state) {
  $form['intel_visitor_default_image_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Default visitor picture'),
    '#default_value' => variable_get('intel_visitor_default_image_path', ''),
    '#description' => t('URL of picture to display for visitors with no custom picture. Leave blank for none.'),
  );

  $form['intel_visitor_field_map'] = array(
    '#type' => 'fieldset',
    '#title' => t('Visitor attribute map'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#tree' => TRUE,
  );

  $fis = field_info_instances('intel_visitor', 'intel_visitor');
  $fbs = field_info_fields();

  $settings = variable_get('intel_visitor_field_map', array());

  $vp_info = intel_get_visitor_property_info_all();
  dsm($vp_info);
  $prop_options = array();
  foreach ($vp_info as $key => $def) {
    if (isset($def['field_type'])) {
      foreach ($def['field_type'] as $type) {
        if (!isset($prop_options[$type])) {
          $prop_options[$type] = array();
        }
        foreach ($def['variables'] as $vkey => $vv) {
          $prop_options[$type][$key . ':' . $vkey] = $def['title'];
          if ($vkey != '@value') {
            $prop_options[$type][$key . ':' . $vkey] .= ": " . $vkey;
          }
        }
      }
    }
  }
  //dsm($prop_options);

  $tax_options = array(
    '' => '- ' . t('None (Do sync data)') . ' -',
    '_set' => t('Sync field with visitor attributes'),
  );

  foreach ($fis as $field_name => $fi) {
    $fb = $fbs[$field_name];
    if ($fb['type'] == 'taxonomy_term_reference' && isset($fb['settings']['allowed_values'][0])) {
      $vocab = $fb['settings']['allowed_values'][0]['vocabulary'];
      $entity_settings = variable_get('intel_entity_settings_taxonomy__' . $fb['settings']['allowed_values'][0]['vocabulary'], array());
      if (!empty($entity_settings['visitor_attribute']['key'])) {
        $fields['tax'][$field_name] = array(
          'type' => $fb['type'],
          'label' => $fi['label'],
          'vocab_name' => $vocab,
          'options' => $tax_options,
        );

        $form['intel_visitor_field_map'][$field_name] = array(
          '#type' => 'select',
          '#title' => t('Field: %field (Taxonomy vocabulary %vocab)', array(
              '%field' => $fi['label'],
              '%vocab' => $vocab,
            )
          ),
          '#description' => t('Select if you want to sync the Drupal entity field with the visitor attribute data.'),
          '#options' => $tax_options,
          '#default_value' => isset($settings[$field_name]) ? $settings[$field_name] : '',
        );
      }
    }
    elseif ($fb['type'] == 'text') {
      $options = array();
      $options['email'] = t('Email');
      $options['telephone'] = t('Telephone');
      //$options['name:full'] = t('Full name');
      $options['givenName:@value'] = t('First name');
      $options['name:last'] = t('Last name');
      $options['facebook:url'] = t('Facebook (url)');
      $options['twitter:username'] = t('Twitter (username)');
      $options['twitter:url'] = t('Twitter (url)');
      $options['linkedin:url'] = t('LinkedIn (url)');
      $options['website:url'] = t('Website');

      $form['intel_visitor_field_map'][$field_name] = array(
        '#type' => 'select',
        '#title' => t('Field: %field', array(
            '%field' => $fi['label'],
          )
        ),
        '#description' => t('Select if you want to sync the Drupal entity field with the visitor attribute data.'),
        '#options' => $prop_options['text'],
        //'#options' => $options,
        '#default_value' => isset($settings[$field_name]) ? $settings[$field_name] : '',
      );
    }
  }




  $form['intel_sync_visitordata_fullcontact'] = array(
    '#type' => 'checkbox',
    '#title' => t('Sync Full Contact data'),
    '#default_value' => variable_get('intel_sync_visitordata_fullcontact', 0),
    '#description' => t('Check to enable syncing data via FullContact API. Only available for pro accounts.'),
  );
  return system_settings_form($form);
}

/**
 * Provides a wrapper on the edit form to add a new entity.
 */
function intel_visitor_add() {
  // Create a basic entity structure to be used and passed to the validation
  // and submission functions.
  $entity = entity_get_controller('intel_visitor')->create();
  $form = drupal_get_form('intel_visitor_edit_form', $entity);
  return Intel_Df::render($form);
}

/**
 * Form function to create an intel_contact entity.
 *
 * The pattern is:
 * - Set up the form for the data that is specific to your
 *   entity: the columns of your base table.
 * - Call on the Field API to pull in the form elements
 *   for fields attached to the entity.
 */
function intel_visitor_edit_form($form, &$form_state, $entity) {
  /*

  $wrapper = entity_metadata_wrapper('intel_visitor', $entity);
dsm($wrapper);
  $gid = 1;
  $new_ref = entity_load('dwyr_franchise', array($gid));
  $new_ref = $new_ref[$gid];
dsm($new_ref);
  $refs = $wrapper->og_group_ref->value();
dsm($refs);
  if (!$refs) {
    $refs = array();
  }
  $refs[] = $new_ref;
dsm($refs);
  $wrapper->og_group_ref->set($refs);
  $wrapper->save();
  */



  $form['entity'] = array(
    '#type' => 'value',
    '#value' => $entity,
  );
  $account = FALSE;
  if (isset($entity->uid)) {
    $account = user_load($entity->uid);
  }
  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Name'),
    '#required' => TRUE,
    '#default_value' => $entity->name,
  );
  
  $form['user_name'] = array(
    '#type' => 'textfield',
    '#title' => t('User'),
    '#required' => FALSE,
    '#autocomplete_path' => 'user/autocomplete',
    '#default_value' => isset($account->name) ? $account->name: '',
  );
    
  $form['email'] = array(
    '#type' => 'textfield',
    '#title' => t('Email'),
    //'#required' => TRUE,
    '#default_value' => $entity->email,
  );

  $form['phone'] = array(
    '#type' => 'textfield',
    '#title' => t('Phone number'),
    //'#required' => TRUE,
    '#default_value' => $entity->phone,
  );

  field_attach_form('intel_visitor', $entity, $form, $form_state);

  $form['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => t('Advanced'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  if (empty($entity->vtk)) {
    $form['advanced']['vtk'] = array(
      '#type' => 'textfield',
      '#title' => t('Tracking visitor token'),
      '#default_value' => $entity->vtk,
    );
  }
  else {
    $form['advanced']['vtk'] = array(
      '#type' => 'item',
      '#title' => t('Tracking visitor token'),
      '#markup' => $entity->vtk,
    );
  }

  $form['advanced']['intel_sync_visitordata_fullcontact'] = array(
    '#type' => 'checkbox',
    '#title' => t('Sync Full Contact data'),
    '#default_value' => $entity->getVar('data', 'settings', 'sync_visitordata.fullcontact', INTEL_SYNC_VISITORDATA_FULLCONTACT_DEFAULT),
    '#description' => t('Check to enable syncing data via FullContact API. Only available for pro accounts.'),
  );

  $form['actions'] = array(
    '#type' => 'actions',
    '#weight' => 100,
  );
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );

  $form['actions']['delete'] = array(
    '#type' => 'submit',
    '#value' => t('Delete'),
    '#submit' => array('intel_visitor_delete_submit'),
  );

  return $form;
}


/**
 * Validation handler for intel_contact_add_form form.
 * We pass things straight through to the Field API to handle validation
 * of the attached fields.
 */
function intel_visitor_edit_form_validate($form, &$form_state) {
  field_attach_form_validate('intel_visitor', $form_state['values']['entity'], $form, $form_state);
}


/**
 * Form submit handler: submits basic_add_form information
 */
function intel_visitor_edit_form_submit($form, &$form_state) {
  $entity = $form_state['values']['entity'];
  $values = $form_state['values'];

  $account = user_load_by_name($form_state['values']['user_name']);
  $email = '';
  $phone = '';
  $vtk = '';
  if (!empty($account->uid)) {
    $entity->setIdentifier('uid', $account->uid);
    $email = $account->mail;
  }

  if (!empty($form_state['values']['email'])) {
    $email = $form_state['values']['email'];
  }
  if ($email) {
    $entity->setIdentifier('email', $email);
  }

  if (!empty($form_state['values']['phone'])) {
    $phone = $form_state['values']['phone'];
  }
  if ($phone) {
    $entity->setIdentifier('phone', $phone);
  }
  if (!empty($form_state['values']['vtk'])) {
    $vtk = $form_state['values']['vtk'];
  }
  if ($vtk) {
    $entity->setIdentifier('vtk', $vtk);
  }

  if (intel_api_access() && isset($form_state['values']['intel_sync_visitordata_fullcontact']))  {
    $entity->setVar('data', 'settings', 'sync_visitordata.fullcontact', !empty($form_state['values']['intel_sync_visitordata_fullcontact']));
  }

  field_attach_submit('intel_visitor', $entity, $form, $form_state);

  $field_map = variable_get('intel_visitor_field_map', array());

  $prop_options = array(
    'source' => 'form',
  );


  $merge_data = array();
  foreach ($field_map as $field_name => $v) {
    if ($v == '_set') {
      $fib = field_info_field($field_name);
      if (empty($fib['settings']['allowed_values'][0]['vocabulary'])) {
        continue;
      }
      $vocab_name = $fib['settings']['allowed_values'][0]['vocabulary'];
      $entity_settings = variable_get('intel_entity_settings_taxonomy__' . $vocab_name, array());
      if (!empty($entity_settings['visitor_attribute']['key'])) {
        $key = 'attributes.' . $entity_settings['visitor_attribute']['key'];
        $var = array();
        foreach ($values[$field_name][LANGUAGE_NONE] as $i => $vv) {
          if (!empty($vv['tid'])) {
            $var[$vv['tid']] = '';
          }
        }
        // TODO: keep this to just storage place
        //$entity->setVar('visitor', 'attributes', $entity_settings['visitor_attribute']['key'], $var);
        $entity->setVar('api_visitor', 'attributes', $entity_settings['visitor_attribute']['key'], $var);
      }
    }
    else {
      list($key, $elem) = explode(':', $v);

      if (!isset($merge_data[$key])) {
        $merge_data[$key] = array();
      }

      $value = '';
      if (!empty($values[$field_name][LANGUAGE_NONE][0])) {
        $value = trim($values[$field_name][LANGUAGE_NONE][0]['value']);
      }

      // don't add value if it is blank
      if ($value) {
        $merge_data[$key][$elem] = $value;
      }
    }
  }
dsm($merge_data);
  foreach ($merge_data AS $prop_name => $values) {
    $entity->setProp($prop_name, $values, $prop_options);
  }


  if (!empty($form_state['values']['name'])) {
    $entity->setName($form_state['values']['name']);
  }

  dsm($field_map);

  $entity = intel_visitor_save($entity);
  $form_state['redirect'] = $entity->uri();
}

function intel_visitor_set_data_from_fields(&$visitor, $prop_options = array()) {
  $field_map = variable_get('intel_visitor_field_map', array());
  foreach ($field_map as $field_name => $v) {
    if ($v == '_set') {
      $fib = field_info_field($field_name);
      if (empty($fib['settings']['allowed_values'][0]['vocabulary'])) {
        continue;
      }
      $vocab_name = $fib['settings']['allowed_values'][0]['vocabulary'];
      $entity_settings = variable_get('intel_entity_settings_taxonomy__' . $vocab_name, array());
      if (!empty($entity_settings['visitor_attribute']['key'])) {
        $key = 'attributes.' . $entity_settings['visitor_attribute']['key'];
        $var = array();
        foreach ($visitor->{$field_name}[LANGUAGE_NONE] as $i => $vv) {
          if (!empty($vv['tid'])) {
            $var[$vv['tid']] = '';
          }
        }
        // TODO: keep this to just storage place
        //$entity->setVar('visitor', 'attributes', $entity_settings['visitor_attribute']['key'], $var);
        $visitor->setVar('api_visitor', 'attributes', $entity_settings['visitor_attribute']['key'], $var);
      }
    }
    else {
      list($key, $elem) = explode(':', $v);

      $value = '';
      if (!empty($visitor->{$field_name}[LANGUAGE_NONE][0]['value'])) {
        $value = trim($visitor->{$field_name}[LANGUAGE_NONE][0]['value']);
      }

      // don't add value if it is blank
      if ($value) {
        if (!isset($merge_data[$key])) {
          $merge_data[$key] = array();
        }
        $merge_data[$key][$elem] = $value;
      }
    }
  }

  foreach ($merge_data AS $prop_name => $values) {
    $visitor->setProp($prop_name, $values, $prop_options);
  }

  return $visitor;
}

function intel_visitor_set_fields_from_data($visitor) {

}



/**
 * Button submit function: handle the 'Delete' button on the node form.
 */
function intel_visitor_edit_delete_submit($form, &$form_state) {
  $entity = $form_state['values']['entity'];
  $destination = array();
  if (isset($_GET['destination'])) {
    $destination = drupal_get_destination();
    unset($_GET['destination']);
  }
  $form_state['redirect'] = array($entity->uri() . '/delete', array('query' => $destination));
}

function intel_visitor_delete_confirm_form($form, &$form_state, $entity) {
  Intel_Df::drupal_set_title(Intel_Df::t('Are you sure you want to delete visitor @title?', array('@title' => $entity->label())));
  $form['entity'] = array(
    '#type' => 'value',
    '#value' => $entity,
  );
  $form['operation'] = array('#type' => 'hidden', '#value' => 'delete');
  $form['#submit'][] = 'intel_visitor_delete_confirm_form_submit';
  $confirm_question = Intel_Df::t('Are you sure you want to delete visitor @name?', array('@name' => $entity->label()));
  return Intel_Form::confirm_form($form,
    $confirm_question,
    'admin/content',
    Intel_Df::t('This action cannot be undone.'),
    Intel_Df::t('Delete'),
    Intel_Df::t('Cancel'));
}

/**
 * Form deletion handler.
 *
 */
function intel_visitor_delete_confirm_form_submit($form , &$form_state ) {
  $entity = $form_state['values']['entity'];
  intel_visitor_delete($entity);
  drupal_set_message(t('The visitor %name (ID %id) has been deleted',
      array('%name' => $entity->label(), '%id' => $entity->vid))
  );
  $form_state['redirect'] = 'admin/people/contacts';
}

function intel_visitor_page($entity) {

  // add fontawesome for icons
  //wp_enqueue_style( 'intel_fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' );

  $view_mode = 'full';
  $langcode = 'UND';
  if (intel()->is_debug()) {
    $entity->apiVisitorLoad();
    intel_d($entity);//
    intel_d($entity->data);//
    intel_d($entity->ext_data);//
  }

  if (!empty($entity->vid)) {
    $synced = $entity->getSynced();
    if (empty($synced)) {
      $l_options = Intel_Df::l_options_add_destination(Intel_Df::current_path());
      $msg = Intel_Df::t('Visitor data has not yet been fully synced. !link.', array(
        '!link' => Intel_Df::l(Intel_Df::t('Click here to sync'), "visitor/{$entity->vid}/sync", $l_options),
      ));
      Intel_Df::drupal_set_message($msg, 'warning');
    }
    elseif (
      !empty($entity->data['analytics_visits']['_lasthit'])
      && (($synced - $entity->data['analytics_visits']['_lasthit']) < 1800)
    ) {
      $msg = '';
      if ((time() - $entity->data['analytics_visits']['_lasthit']) < 1800) {
        $msg .= Intel_Df::t('The last hit retrieved from Google Analytics was only !time minutes ago.', array(
          '!time' => floor((time() - $entity->data['analytics_visits']['_lasthit'])/60),
        ));
        $msg .= ' ' . Intel_Df::t('It is recommended to allow up to 30 minutes for Google Analytics to fully prepare analytics data before sync.');
      }
      else {
        $msg .= Intel_Df::t('The last sync of Google Analytics data was !time minutes after the last processed hit.', array(
          '!time' => floor(($synced - $entity->data['analytics_visits']['_lasthit'])/60),
        ));
        $msg .= ' ' . Intel_Df::t('You may want to re-sync Google Analytics data to assure all processed data has been fetched.');
      }

      $l_options = Intel_Df::l_options_add_destination(Intel_Df::current_path());
      $l_options = Intel_Df::l_options_add_query(array('processes' => 'ga'), $l_options);
      $msg .= ' ' . Intel_Df::t('!link.', array(
        '!link' => Intel_Df::l(Intel_Df::t('Click here to sync the latest Google Analytics data for this visitor'), "visitor/{$entity->vid}/sync", $l_options),
      ));
      Intel_Df::drupal_set_message($msg, 'warning');
    }
  }
  else {
    $msg = Intel_Df::t('Visitor is anonymous.');
    $msg .= ' ' . Intel_Df::t('Once the visitor does an identifying event such as a form submission they will become a known contact.');
    Intel_Df::drupal_set_message($msg, 'warning');
  }

  intel()->set_page_title('Visitor profile');

  if (is_string($entity->data)) {
    $entity->data = unserialize($entity->data);
  }

  if (is_string($entity->ext_data)) {
    $entity->ext_data = unserialize($entity->ext_data);
  }

  if (!isset($langcode)) {
    //$langcode = $GLOBALS['language_content']->language;
  }

  // Retrieve all profile fields and attach to $entity->content.
  IntelVisitor::build_content($entity);

  $build = $entity->content;
  $build = array(
    'elements' => $entity->content,
    'view_mode' => $view_mode,
  );

  $output = Intel_Df::theme('intel_visitor_profile', $build);
  return $output;
}

function intel_visitor_export_page($visitor) {
  if (isset($visitor->identifiers)) {
    // remove internal id
    if (isset($visitor->identifiers['vid'])) {
      unset($visitor->identifiers['vid']);
    }
  }
  if (isset($visitor->data)) {
    if (isset($visitor->data['syncStatus'])) {
      unset($visitor->data['syncStatus']);
    }
  }
  if (isset($visitor->apiVisitor)) {
    unset($visitor->apiVisitor);
  }
  if (isset($visitor->apiPerson)) {
    unset($visitor->apiPerson);
  }
  // remove internal id
  unset($visitor->vid);

  // redundant info
  unset($visitor->vtkid);
  unset($visitor->vtkc);

  // remove apiLevel
  unset($visitor->apiLevel);

  $data = array();
  $data['intel_visitor'] = $visitor;

  return '<pre>' . wp_json_encode($data, JSON_PRETTY_PRINT) . '</pre>';
}

function intel_visitor_tab_scorecard($visitor) {
  if (!intel_api_access()) {
    $output = intel_set_api_access_error($vars = array());
    return $output;
  }
  require_once drupal_get_path('module', 'intel') . "/reports/intel.report_scorecard.php";
 
  if (is_string($id) && is_numeric($id) && strlen($id) < 8) {
    $id = (int)$id;
  }
  $visitor = intel_visitor_load($id);
  
  if ($visitor->getVar('ext', 'ga') && !$visitor->getVar('data', 'environment')) {
    require_once drupal_get_path('module', 'intel') . "/includes/intel.visitor_sync.php";
    require_once drupal_get_path('module', 'intel') . "/includes/intel.ga.php";
    intel_ga_sync_visitordata($visitor);
    drupal_set_message(t('Google Analytics data resynced.'));
  }
  
  drupal_set_title(t('Visitor scorecard: @title', array('@title' => $visitor->getLabel())), PASS_THROUGH);
 
  $filters = array(
   'visitor' => 'visitorid:' . $visitor->vtkid,
  );
 
  $output = '';
  $output .= intel_scorecard_report($filters, 'visitor'); 
 
  return $output;
}


function intel_visitor_tab_clickstream($visitor) {
  if (!intel_api_access()) {
    $output = intel_set_api_access_error($vars = array());
    return $output;
  }
  
  // TODO re-org clickstream report
  $file = drupal_get_path('module', 'intel') . "/reports/intel.report_visitor_clickstream.php";
  if (file_exists($file)) {
    require_once $file;
  }
  else {
    drupal_set_message(t('Clickstream report not found. Comming soon.'));
    return '';
  }

  if ($visitor->getVar('ext', 'ga') && !$visitor->getVar('data', 'environment')) {
    require_once drupal_get_path('module', 'intel') . "/includes/intel.visitor_sync.php";
    require_once drupal_get_path('module', 'intel') . "/includes/intel.ga.php";
    intel_ga_sync_visitordata($visitor);
    drupal_set_message(t('Google Analytics data resynced.'));
  }
  
  drupal_set_title(t('Clickstream: @title', array('@title' => $visitor->label())), PASS_THROUGH);
 
  $output = '';
  $output .= intel_visitor_clickstream_report_page('-', '-', '-', $visitor);
  
  return $output;
}

function intel_visitor_tab_submissions($visitorid) {
  require_once './' . drupal_get_path('module', 'intel') . "/admin/intel.admin_submission.php";

  $visitor = intel_visitor_load_by_visitorid($visitorid, array());  
  
  $visitor_name = (!empty($visitor->name)) ? $visitor->name : $visitorid;
  drupal_set_title(t('Clickstream: @title', array('@type' => $visitor_name, '@title' => $visitor_name)), PASS_THROUGH);
  
  $output = '';
  $output .= intel_visitor_clickstream($visitorid);
  
  return $output;  
}


function intel_admin_people_contacts() {
  include_once INTEL_DIR . 'includes/class-intel-visitor-list-table.php';
  $output = '';

  $wp_list_table = new Intel_Visitor_List_Table();
  $pagenum = $wp_list_table->get_pagenum();
  $title = esc_html__('Users');
  //$parent_file = 'users.php';

  add_screen_option( 'per_page' );

  get_current_screen()->set_screen_reader_content( array(
    'heading_views'      => esc_html__( 'Filter users list' ),
    'heading_pagination' => esc_html__( 'Users list navigation' ),
    'heading_list'       => esc_html__( 'Users list' ),
  ) );

  if ( !empty($_GET['_wp_http_referer']) ) {
    wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce'), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
    exit;
  }

  if ( $wp_list_table->current_action() && ! empty( $_REQUEST['users'] ) ) {
    $userids = $_REQUEST['users'];
    $sendback = wp_get_referer();

    /** This action is documented in wp-admin/edit-comments.php */
    $sendback = apply_filters( 'handle_bulk_actions-' . get_current_screen()->id, $sendback, $wp_list_table->current_action(), $userids );

    wp_safe_redirect( $sendback );
    exit;
  }

  $wp_list_table->prepare_items();
  $total_pages = $wp_list_table->get_pagination_arg( 'total_pages' );
  if ( $pagenum > $total_pages && $total_pages > 0 ) {
    wp_redirect( add_query_arg( 'paged', $total_pages ) );
    exit;
  }

  $search_string = '';
  if (!empty($_REQUEST['s'])) {
    $_REQUEST['s'] = sanitize_text_field($_REQUEST['s']);
    $search_string = Intel_Df::t('Search results for &#8220;@s&#8221;', array(
      '@s' => $_REQUEST['s'],
    ));
  }
  $vars = array(
    'list_table' => $wp_list_table,
    'search_box' => array(
      esc_html__( 'Search Contacts', 'intel' ),
      'intel_visitor'
    ),
    'search_string' => esc_html($search_string),
    'view' => 1,
  );
  $output = Intel_Df::theme('list_table', $vars);

  return $output;

  $output = array();

  // Check API level and prevent visitor add/edit if IAPI will not authorize
  // visitor data
  $api_level = intel_api_level();
  if ($api_level != 'pro') {
    $vars = array(
      'message' => t('The Pro version of Intel is required to create contacts.'),
    );
    intel_set_api_access_error($vars);
  }

  
  $header = array();
  $header[] = array(
    'data' => t('Name'),
    'type' => 'property',
    'specifier' => 'name',
  );
  $header[] = array(
    'data' => t('Email'),
  );
  if ($api_level == 'pro') {
    $header[] = array(
      'data' => t('Location'),
    );
  }
  $header[] = array(
    'data' => t('Contact created'),
    'type' => 'property',
    'specifier' => 'contact_created',
  );
  $header[] = array(
    'data' => t('Last activity'),
    'type' => 'property',
    'specifier' => 'last_activity',
    'sort' => 'desc',
  );
  $header[] = array(
    'data' => t('Operations'),
  );

  // load contacts using EntityFieldQuery
  // see: https://drupal.org/node/916776
  $query = new EntityFieldQuery();

  $query->entityCondition('entity_type', 'intel_visitor');
  $query->propertyCondition('contact_created', 0, '>');
  $query->pager(50);
  $query->tableSort($header);
  $query->addTag('intel_admin_people_contacts');

  drupal_alter('intel_admin_people_contacts_query', $query);

  $result = $query->execute();

  $visitors = array();
  if (isset($result['intel_visitor'])) {
    $visitors = intel_visitor_load_multiple(array_keys($result['intel_visitor']));
  }

  $rows = array();
  foreach ($visitors AS $visitor) {
    $row = array();
    $row[] = $visitor->label_link();
    $row[] = $visitor->email;
    if ($api_level == 'pro') {
      $row[] = $visitor->location();
    }
    $row[] = ((REQUEST_TIME - $visitor->contact_created) > 172800) ? format_date($visitor->contact_created, 'short') : format_interval(REQUEST_TIME  - $visitor->contact_created) . ' ' . t('ago');
    $row[] = ((REQUEST_TIME  - $visitor->last_activity) > 172800) ? format_date($visitor->last_activity, 'short') : format_interval(REQUEST_TIME  - $visitor->last_activity) . ' ' . t('ago');
    $ops = '';
    $ops .= l(t('Clickstream'), $visitor->uri() . '/clickstream');
    $hubspot_profile_url = $visitor->getVar('ext', 'hubspot', 'profile_url');
    if (!empty($hubspot_profile_url)) {
      $ops .= ' | ' . l(t('HubSpot'), $visitor->uri() . '/hubspot');
    }
    $row[] = $ops;
    $rows[] = $row;
  }

  $output['contacts'] = array(
    '#header' => $header,
    '#rows' => $rows,
    '#empty' => t('No contacts were returned'),
    '#theme' => 'table',
  );
  if (!empty($result['intel_visitor'])) {
    $output['pager']['#markup'] = theme('pager', $query->pager);
  }
  return $output;
}



function intel_admin_people_visitors() {
  $output = array();

  $api_level = variable_get('intel_api_level', '');

  $header = array(
    array(
      'data' => t('Visitor id'),
      'type' => 'property',
      'specifier' => 'vid',
    ),
    array(
      'data' => t('Location'),
    ),
    array(
      'data' => t('Last updated'),
      'type' => 'property',
      'specifier' => 'updated',
    ),
    array(
      'data' => t('Last activity'),
      'type' => 'property',
      'specifier' => 'last_activity',
      'sort' => 'desc',
    ),
    array(
      'data' => t('Operations'),
    ),
  );


  // load contacts using EntityFieldQuery
  // see: https://drupal.org/node/916776
  $query = new EntityFieldQuery();

  $query->entityCondition('entity_type', 'intel_visitor');
  $query->pager(50);
  $query->tableSort($header);
  $query->addTag('intel_admin_people_visitors');

  drupal_alter('intel_admin_people_visitors_query', $query);

  $result = $query->execute();

  $visitors = array();
  if (isset($result['intel_visitor'])) {
    $visitors = intel_visitor_load_multiple(array_keys($result['intel_visitor']));
  }

  $rows = array();
  foreach ($visitors AS $visitor) {
    $row = array();
    $row[] = $visitor->label_link();
    if ($api_level == 'pro') {
      $row[] = $visitor->location();
    }
    $row[] = ((REQUEST_TIME  - $visitor->updated) > 172800) ? format_date($visitor->updated, 'short') : format_interval(REQUEST_TIME  - $visitor->updated) . ' ' . t('ago');
    $row[] = ((REQUEST_TIME  - $visitor->last_activity) > 172800) ? format_date($visitor->last_activity, 'short') : format_interval(REQUEST_TIME  - $visitor->last_activity) . ' ' . t('ago');
    $ops = '';
    $ops .= l(t('Clickstream'), $visitor->uri() . '/clickstream');
    $row[] = $ops;
    $rows[] = $row;
  }
  $output = array();
  $output['contacts'] = array(
    '#header' => $header,
    '#rows' => $rows,
    '#empty' => t('No contacts were returned'),
    '#theme' => 'table',
  );
  if (!empty($result['intel_visitor'])) {
    $output['pager']['#markup'] = theme('pager', $query->pager);
  }
  return $output;
}
