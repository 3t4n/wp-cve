<?php
/**
 * Process variables for user-profile.tpl.php.
 *
 * The $variables array contains the following arguments:
 * - $account
 *
 * @see user-profile.tpl.php
 */

function template_process_intel_page(&$variables) {
  if (empty($variables['class'])) {
    $variables['class'] = '';
  }
  if (!empty($_GET['q'])) {
    $q = str_replace('/', '--', $_GET['q']);
    $variables['class'] .= ' ' . Intel_Df::drupal_clean_css_identifier($q);
  }
  if (empty($variables['title'])) {
    $variables['title'] = intel()->get_page_title();
  }
}

function template_process_intel_navbar(&$variables) {
  $base_path = $variables['base_path'];
  $navs = array(
    'nav' => 'tree',
    'nav_right' => 'tree2',
  );

  foreach ($navs as $k => $kk) {
    $variables[$k] = '';
    $variables[$k] .= ($k == 'nav_right') ? '<ul class="nav navbar-nav navbar-right">' : '<ul class="nav navbar-nav">';
    foreach ($variables[$kk] as $key =>$item) {
      if (!isset($item['#info'])) {
        continue;
      }
      $info = $item['#info'];
      $class = !empty($info['active']) ? 'active' : '';
      $dropdown = 0;
      $link_text = $info['title'];
      $link_options = array();
      if (count($item) > 1) {
        $dropdown = 1;
        $link_text .= ' <span class="caret"></span>';
        $class .= ' dropdown';
        $link_options['html'] = TRUE;
        $link_options['attributes'] = array(
          //'data-toggle' => 'dropdown',
          'role' => 'button',
          'aria-haspopup' => 'true',
          'aria-expanded' => 'false',
          'class' => array(
            //'dropdown-toggle'
          ),
        );
        if (!empty($info['active'])) {
          $link_options['attributes']['data-toggle'] = 'dropdown';
          $link_options['attributes']['class'][] = 'dropdown-toggle';
        }
      }
      $variables[$k] .= "<li class=\"$class\">";
      if (!empty($_GET['report_params'])) {
        $link_options['query'] = array(
          'report_params' => $_GET['report_params']
        );
      }
      $variables[$k] .= Intel_Df::l($link_text, $base_path . '/' . $key, $link_options);
      if ($dropdown) {
        $variables[$k] .= '<ul class="dropdown-menu">';
        foreach ($item as $skey => $sitem) {
          if (substr($skey, 0, 1) == '#') {
            continue;
          }
          $sinfo = $sitem['#info'];
          $slink_options = array();
          if (!empty($_GET['report_params'])) {
            $slink_options['query'] = array(
              'report_params' => $_GET['report_params']
            );
          }
          $class = !empty($sinfo['active']) ? 'active' : '';
          $variables[$k] .= "<li class=\"$class\">";
          $variables[$k] .= Intel_Df::l($sinfo['title'], $base_path . '/' . $key . '/' . $skey, $slink_options);
          $variables[$k] .= "</li>\n";
        }
        $variables[$k] .= '</ul>';
      }
      $variables[$k] .= "</li>\n";
    }
    $variables[$k] .= "</ul>\n";
  }
}

function template_process_intel_shortcode(&$variables) {
  if (!isset($variables['class'])) {
    $variables['class'] = '';
  }
  if (!empty($_GET['q'])) {
    $q = str_replace('/', '--', $_GET['q']);
    $variables['class'] .= ' ' . Intel_Df::drupal_clean_css_identifier($q);
  }
  if (!isset($variables['messages'])) {
    $variables['messages'] = Intel_Df::drupal_get_messages();
  }

}

function template_process_intel_bootstrap_card(&$variables) {

}

function template_process_intel_visitor_profile(&$variables) {
  //$visitor = $variables['elements']['#visitor'];

  $defs = array(
    'attributes' => array(),
  );
  $variables += $defs;
  // Helpful $user_profile variable for templates.
  foreach (Intel_Df::element_children($variables['elements']) as $key) {
    $region = 'main';
    if (!empty($variables['elements'][$key]['#region'])) {
      $region = $variables['elements'][$key]['#region'];
    }
    if (!isset($variables[$region])) {
      $variables[$region] = array();
    }
    if ($region == 'header') {
       $variables[$key] = $variables['elements'][$key];
    }
    else {
      $variables[$region][$key] = $variables['elements'][$key];
    }
  }
  $variables['attributes'] = Intel_Df::drupal_attributes($variables['attributes']);
  // Preprocess fields.
  //field_attach_preprocess('intel_visitor', $visitor, $variables['elements'], $variables);
}

/**
 * Process variables for user-profile-item.tpl.php.
 *
 * The $variables array contains the following arguments:
 * - $element
 *
 * @see user-profile-item.tpl.php
 */
function template_preprocess_intel_visitor_profile_item(&$variables) {
  /*
  $variables['title'] = $variables['element']['#title'];
  $variables['value'] = $variables['element']['#markup'];
  $variables['attributes'] = '';
  if (isset($variables['element']['#attributes'])) {
    $variables['attributes'] = drupal_attributes($variables['element']['#attributes']);
  }
  */
}

function template_process_intel_visitor_profile_item_list(&$variables) {
  $variables['attributes'] = Intel_Df::drupal_attributes($variables['attributes']);
  /*
  $variables['title'] = $variables['element']['#title'];
  $variables['value'] = $variables['element']['#markup'];
  $variables['attributes'] = '';
  if (isset($variables['element']['#attributes'])) {
    $variables['attributes'] = drupal_attributes($variables['element']['#attributes']);
  }
  */
}

function template_preprocess_intel_visitor_profile_block(&$variables) {
  /*
  if (isset($variables['element'])) {
    $variables['title'] = isset($variables['element']['#title']) ? $variables['element']['#title'] : '';
    $variables['markup'] = isset($variables['element']['#markup']) ? $variables['element']['#markup'] : '';
  }
  $variables['attributes'] = '';
  if (!isset($variables['element']['#attributes'])) {
    $variables['element']['#attributes'] = array();
  }
  if (!isset($variables['element']['#attributes']['class'])) {
    $variables['element']['#attributes']['class'] = array();
  }
  $variables['element']['#attributes']['class'][] = 'profile-block';
  
  if (isset($variables['element']['#attributes'])) {
    $variables['attributes'] = Intel_Df::drupal_attributes($variables['element']['#attributes']);
  }

  */
  $variables += array(
    'header' => NULL,
    'body' => '',
    'footer' => NULL,
  );
  // translate visitor_profile_block vars into bootstrap card
  if (!empty($variables['title'])) {
    $variables['body'] .= '<h3 class="card-header">' . $variables['title'] . '</h3>';
  }
  if (!empty($variables['markup'])) {
    $variables['body'] .= '<div class="card-block">' . $variables['markup'] . '</div>';
  }
}

/**
 * Process variables for user-picture.tpl.php.
 *
 * The $variables array contains the following arguments:
 * - $account: A user, node or comment object with 'name', 'uid' and 'picture'
 *   fields.
 *
 * @see user-picture.tpl.php
 */
function template_preprocess_intel_visitor_picture(&$variables) {
  $variables['picture'] = '';
  $entity = $variables['entity'];
  $image_variables = isset($variables['image_variables']) ?  $variables['image_variables'] : array();
  $avatar_options = isset($variables['avatar_options']) ?  $variables['avatar_options'] : array();

  $filepath = get_option('intel_visitor_default_image_path', '');
  if (empty($filepath)) {
    $filepath = get_option('user_picture_default', '');

    //$filepath = get_avatar_url( mixed $id_or_email, array $args = null )
  }
  if (isset($entity->data['image']) && !empty($entity->data['image']['url'])) {
    $filepath = $entity->data['image']['url'];
  }
  else {
    $id = isset($avatar_options['id']) ? $avatar_options['id'] : $entity->getEmail();
    $filepath = get_avatar_url( $id, $avatar_options);
  }

  if (!empty($filepath)) {
    $alt = Intel_Df::t("@user's picture", array('@user' => $entity->name));
    $image_variables['path'] = $filepath;
    if (!isset($image_variables['alt'])) {
      $image_variables['alt'] = $alt;
    };
    if (!isset($image_variables['title'])) {
      $image_variables['title'] = $alt;
    };
    if (!isset($image_variables['attributes'])) {
      $image_variables['attributes'] = array();
    };
    if (!isset($image_variables['attributes']['class'])) {
      $image_variables['attributes']['class'] = array();
    };
    $image_variables['attributes']['class'][] = 'img-responsive';
    $image_variables['attributes']['class'][] = 'center-block';

    // If the image does not have a valid Drupal scheme (for eg. HTTP),
    // don't load image styles.
    if (FALSE && module_exists('image') && file_valid_uri($filepath) && $style = get_option('user_picture_style', '')) {
      $image_variables['style_name'] = $style;
      $variables['picture'] = Intel_Df::theme('image_style', $image_variables);
    }
    else {
      $variables['picture'] = Intel_Df::theme('image', $image_variables);
    }
    // TODO set correct user_access permission
    if (FALSE && !empty($entity->vid) && user_access('access user profiles')) {
      $attributes = array('attributes' => array('title' => Intel_Df::t('View user profile.')), 'html' => TRUE);
      $variables['picture'] = Intel_Df::l($variables['picture'], $entity->uri(), $attributes);
    }
  }
}

function intel_visitor_format_profile_item($title, $value, $attributes = array()) {
  $item = array(
    'title' => esc_html($title),
    'value' => esc_html($value),
  );
  if (!empty($attributes)) {
    $item['attributes'] = $attributes;
  }
  return Intel_Df::theme('intel_visitor_profile_item', $item);
}

function intel_visitor_render_profile_items($items) {
  $output = '';
  $output = '';
  foreach ($items as $item) {
    //$output .= Intel_Df::render($item);
    $output .= $item;
  }
  return $output;
}

function template_preprocess_intel_visitor_social_links (&$variables) {

  static $fa_embed;
  if (empty($fa_embed)) {
    wp_enqueue_style( 'intel_fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    $fa_embed = 1;
  }

  $entity = $variables['entity'];
  $properties = $entity->data;
  $property_info = intel()->visitor_property_info();

  $variables['markup'] = '';
  foreach ($property_info AS $prop_name => $info) {

    if (!isset($info['category']) || ($info['category'] != 'social')) {
      continue;
    }
    //intel_d($info);
    if (empty($info['icon']) && empty($info['icon_class'])) {
      continue;
    }
    $a = explode('.', $prop_name);
    $scope = $a[0];
    $namespace = $a[1];
    if (empty($properties[$namespace]) || empty($properties[$namespace]['url'])) {
      continue;
    }
    $prop = $properties[$namespace];
    $alt = Intel_Df::t('@title profile (source: @source)',
      array(
        '@title' => $info['title'],
        '@source' => !empty($prop['_source']) ? $prop['_source'] : Intel_Df::t('(not set)'),
      )
    );
    $upload_dir = wp_upload_dir();
    $upload_baseurl = $upload_dir['baseurl'] . '/';
    if (!empty($info['icon_class'])) {
      $img = '<i class="' . $info['icon_class'] . ' social-icon" aria-hidden="true"></i>';
    }
    elseif (!empty($info['icon'])) {
      $iv = array(
        'path' => $upload_baseurl . $info['icon'],
        'alt' => $alt,
        'title' => $alt,
      );
      $img = Intel_Df::theme('image', $iv);
    }


    $options = array(
      'html' => true,
      'attributes' => array(
        'target' => $namespace,
      ),
    );
    
    $vars = array(
      'link' => Intel_Df::l($img, $prop['url'], $options),
      'class' => preg_replace('/[^\x{002D}\x{0030}-\x{0039}\x{0041}-\x{005A}\x{005F}\x{0061}-\x{007A}\x{00A1}-\x{FFFF}]/u', '', $namespace),
    );
    
    $variables['markup'] .= Intel_Df::theme('intel_visitor_social_link', $vars);
  }
}

function intel_format_location_name($location, $format = 'country') {
  $out = '';
  if ($format == 'city, state, country') {
    $out = !empty($location['city']) ? $location['city'] : t('(not set)') . ', ';
    $out .= ', ' . !empty($location['region']) ? esc_html($location['region']) : t('(not set)');
    $out .= ', ' . !empty($location['country']) ? esc_html($location['country']) : t('(not set)');
  }
  elseif ($format == 'map') {
    $out = !empty($location['city']) ? esc_html($location['city']) : '(not set)';
    $out .= ', ' . (!empty($location['region']) ? esc_html($location['region']) : t('(not set)'));
    if (isset($location['metro']) && ($location['metro'] != '(not set)')) {
      $out .= ' (' . $location['metro'] . ')';
    }
    $out .= "<br />\n" . (!empty($location['country']) ? esc_html($location['country']) : t('(not set)'));
  }
  else {
    $out = !empty($location['country']) ? esc_html($location['country']) : t('(not set)');
  }
  return $out;
}

function theme_intel_location(&$variables) {
  $output = '';
  $location = array();
  if (!empty($variables['entity'])) {
    $entity = $variables['entity'];
    $location = $entity->getVar('data', 'location');
  }
  elseif (!empty($variables['location'])) {
    $location = $variables['location'];
  }

  if (empty($location)) {
    return;
  }

  $location['name'] = $location['city'] . ', ' . $location['region']  . ', ' . $location['country'];
  $location['name'] = esc_html(esc_html($location['name']));
  $vars = array(
    'locations' => array($location),
  );
  $output .= Intel_Df::theme('intel_map', $vars);
  $output .= '<div class="card-block">' . intel_format_location_name($location, 'map') . '</div>';
  return $output;
}



function template_process_intel_location_block(&$variables) {
  $variables['markup'] = Intel_Df::theme('intel_location', $variables);
  if (empty($variables['markup'])) {
    // remove title to clear block
    $variables['title'] = '';
    return;
  }
  if (empty($variables['title'])) {
    $variables['title'] = Intel_Df::t('Location');
  }
  $variables['no_card_block'] = 1;
  if (!isset($variables['attributes']['class'])) {
    $variables['attributes']['class'] = array();
  }
  $variables['attributes']['class'][] = 'card';
  $variables['attributes']['class'][] = 'location-map';
  $variables['attributes'] = Intel_Df::drupal_attributes($variables['attributes']);
}

function x_template_process_intel_visitor_location(&$variables) {
  $variables['title'] = '';
  $variables['markup'] = '';
  if (!empty($variables['entity'])) {
    $entity = $variables['entity'];
    $location = $entity->getVar('data', 'location');
  }
  elseif (!empty($variables['location'])) {
    $location = $variables['location'];
  }

  if (empty($location)) {
    return;
  }
  
  $variables['title'] = Intel_Df::t('Location');
  $location['name'] = $location['city'] . ', ' . $location['region']  . ', ' . $location['country']; 
  $vars = array(
    'locations' => array($location),
  );

  $map = Intel_Df::theme('intel_map', $vars);

  $map .= '<div class="card-block">' . intel_format_location_name($location, 'map') . '</div>';
  $variables['markup'] = $map;
  $variables['no_card_block'] = 1;
  if (!isset($variables['attributes']['class'])) {
    $variables['attributes']['class'] = array();
  }
  $variables['attributes']['class'][] = 'card';
  $variables['attributes']['class'][] = 'location-map';
  $variables['attributes'] = Intel_Df::drupal_attributes($variables['attributes']);
}

function template_preprocess_intel_map(&$variables) {
  $locations = $variables['locations'];
  $gmap_apikey = get_option('intel_gmap_apikey', '');

  static $map_index;
  if (!isset($map_index)) {
    $map_index = 0;
    if (!empty($_GET['return_type']) && ($_GET['return_type'] == 'json')) {
    }
    else {
      wp_enqueue_script( 'intel_googleapis_map', 'https://maps.googleapis.com/maps/api/js?v=3.exp&key=' . $gmap_apikey . '&callback=_intel_googleapi_map_init');
    }
  }

  $variables['map_index'] = $map_index++;
  $variables['map_options'] = array();
  $variables['map_options']['zoom'] = 4;
  $variables['map_options']['center'] = array(
    'lat' => 0, 
    'lon' => 0,
  );
  $variables['locations'] = array();

  foreach ($locations AS $loc) {
    if (!isset($loc['lat']) && isset($loc['latitude'])) {
      $loc['lat'] = $loc['latitude'];
    }
    if (!isset($loc['lon']) && isset($loc['longitude'])) {
      $loc['lon'] = $loc['longitude'];
    }
    $variables['locations'][] = array(
      'lat' => esc_html($loc['lat']),
      'lon' => esc_html($loc['lon']),
      'title' => esc_html($loc['name']),
    );
    //$vars['locations_json'] .= "[" . $loc['lat'] . ", " . $loc['lon'] . ", " . "'" . (isset($loc['name']) ? $loc['name'] : '') . "'],\n";
    $variables['map_options']['center']['lat'] = $loc['lat']; 
    $variables['map_options']['center']['lon'] = $loc['lon'];
  }
}

function template_preprocess_intel_visitor_browser_environment(&$variables) {
  $variables['title'] = '';
  $variables['markup'] = '';
  $entity = $variables['entity'];
  $env = $entity->getVar('data', 'environment');
  if (empty($env)) {
    return;
  }
  $variables['title'] = Intel_Df::t('Browser environment');
  $items = array();
  $items[] = intel_visitor_format_profile_item(Intel_Df::t('Browser'), $env['browser'] . " v" . $env['browserVersion']);
  $items[] = intel_visitor_format_profile_item(Intel_Df::t('OS'), $env['operatingSystem'] . " " . $env['operatingSystemVersion']);
  if (!empty($env['mobileDeviceInfo'])) {
    $items[] = intel_visitor_format_profile_item(Intel_Df::t('Mobile device'), $env['mobileDeviceInfo']);
  }
  $items[] = intel_visitor_format_profile_item(Intel_Df::t('Screen'), $env['screenResolution']);
  $items[] = intel_visitor_format_profile_item(Intel_Df::t('Language'), $env['language']);

  $variables['markup'] = intel_visitor_render_profile_items($items);  
}

/**
 * Process variables for intel-visitor-browser-env.tpl.php.
 *
 * @see user-picture.tpl.php
 */
function template_preprocess_intel_visitor_bio(&$variables) {
  //$variables['markup'] = $variables['entity']->getVar('data', 'bio', 'summary');
  $variables['markup'] = $variables['entity']->getVar('data', 'description', '@value');

  if (strlen($variables['markup']) > 260) {
    $variables['markup'] = substr($variables['markup'], 0, 260);
    $variables['markup'] = preg_replace('/\W\w+\s*(\W*)$/', '$1', $variables['markup']) . ' ...';

  }
  $variables['markup'] = esc_html($variables['markup']);
  //$variables['markup'] = '<span class="lead">' . $variables['markup'] . '</span>';
}

/**
 * Process variables for user-picture.tpl.php.
 *
 * The $variables array contains the following arguments:
 * - $account: A user, node or comment object with 'name', 'uid' and 'picture'
 *   fields.
 *
 * @see user-picture.tpl.php
 */
function template_preprocess_intel_visitor_browser_location(&$variables) {
  $variables['intel_visitor_browser_location'] = '';
  $entity = $variables['entity'];
}

function theme_intel_visit_hits_table(&$variables) {
  $output = '';

  $hits = $variables['hits'];

  $header = array(
    __('Time', 'intel'),
    __('Page/Event', 'intel'),
    array(
      'data' => __('Value', 'intel'),
      'class' => array('numeric-cell'),
    )
  );

  //uasort($hits, '_intel_sort_by_rtime');

  uasort($hits, '_intel_sort_ga_hits');

  // make sure pageviews are fired first
  $weight = 0;
  foreach ($hits as $i => $hit) {
    $hits[$i]['weight'] = $weight += 2;
    if ($hit['type'] == 'pageview') {
      $hits[$i]['weight']--;
    }
  }

  usort($hits, '_intel_sort_by_weight');

  $pageviews = array();
  $pageviews_i = array();
  foreach ($hits as $i => $hit) {
    if ($hit['type'] == 'pageview') {
      $pageviews_i[$hit['hostpath']] = count($pageviews);
      $a = array(
        'time' => $hit['time'],
        'pageview' => $hit,
        'hits' => array(),
      );
      $pageviews[] = $a;
    }
    else if ($hit['type'] == 'event') {
      $pageview_i = $pageviews_i[$hit['hostpath']];
      if (isset($pageviews[$pageview_i]['hits'])) {
        $pageviews[$pageview_i]['hits'][] = $hit;
      }
    }
  }

  // pass sorted hits back for clickstream timeline
  $variables['hit_pageviews'] = $pageviews;

  $scorings = intel_get_scorings();
  $rows = array();
  $start_time = 0;
  $stick = 0;
  $pageview_count = 0;
  foreach ($pageviews as $i => $pageview) {
    $ts = $pageview['time'];
    if (!$start_time) {
      $start_time = $ts;
    }
    $time = $ts - $start_time;
    $tf = ($time > 3600) ? 'H:i:s' : 'i:s';
    $row = array(
      'class' => array('page'),
    );
    $value = 0;
    $scorings_type = $pageview_count ? 'additional_pages' : 'entrance';
    if (!empty($scorings[$scorings_type])) {
      $value = $scorings[$scorings_type];
    }
    $row['data'] = array(
      array(
        'class' => array('col-time'),
        'data' => '+' . date($tf, $time),
      ),
      array(
        'class' => array('col-title'),
        'data' => Intel_Df::l($pageview['pageview']['pageTitle'], '//' . $pageview['pageview']['hostname'] . $pageview['pageview']['pagePath']),
      ),
      array(
        'class' => array('col-value numeric-cell'),
        'data' => $value ? intel_event_value_format($value, array('mode' => 'valued')) : '-',
        //'data' => (!empty($pageview['pageview']['value'])) ? intel_event_value_format($pageview['pageview']['value'], array('mode' => 'valued')) : '-',
      ),
    );
    $pageview_count++;
    $rows[] = $row;
    if ($i == 1 && !$stick && !empty($scorings['stick'])) {
      $stick++;
      $row = array(
        'class' => array('event', 'valued-event'),
      );
      $row['data'] = array(
        array(
          'class' => array('col-time'),
          'data' => '+' . date($tf, $time),
        ),
        array(
          'class' => array('col-title'),
          'data' => '[' . Intel_Df::t('Stick') . ']: ' . Intel_Df::t('Page hit'),
        ),
        array(
          'class' => array('col-value numeric-cell'),
          'data' => intel_event_value_format($scorings['stick'], array('mode' => 'valued')),
        ),
      );
      $rows[] = $row;
    }
    foreach ($pageview['hits'] as $j => $hit) {
      $ts = $hit['time'];
      $time = $ts - $start_time;
      $tf = ($time > 3600) ? 'H:i:s' : 'i:s';
      $row = array(
        'class' => array('event'),
      );
      $row['class'][] = (!empty($hit['eventMode']) ? $hit['eventMode'] : 'standard')  . '-event';
      $value = !empty($hit['eventValue']) ? $hit['eventValue'] : '-';
      if (!empty($hit['eventMode'])) {
        $value = intel_event_value_format($hit['eventValue'], array('mode' => 'valued'));
      }
      $row['data'] = array(
        array(
          'class' => array('col-time'),
          'data' => '+' . date($tf, $time),
        ),
        array(
          'class' => array('col-title'),
          'data' => $hit['eventCategory'] . ': ' . $hit['eventAction'],
        ),
        array(
          'class' => array('col-value numeric-cell'),
          'data' => $value,
        ),
      );
      if (!isset($_GET['standard_events']) || $_GET['standard_events']) {
        $rows[] = $row;
      }
      if (!empty($hit['eventMode']) && !$stick && !empty($scorings['stick'])) {
        $stick++;
        $row = array(
          'class' => array('event', 'valued-event'),
        );
        $row['data'] = array(
          array(
            'class' => array('col-time'),
            'data' => '+' . date($tf, $time),
          ),
          array(
            'class' => array('col-title'),
            'data' => '[' . Intel_Df::t('Stick') . ']: ' . Intel_Df::t('Event'),
          ),
          array(
            'class' => array('col-value numeric-cell'),
            'data' => intel_event_value_format($scorings['stick'], array('mode' => 'valued')),
          ),
        );
        $rows[] = $row;
      }
    }
  }


  $vars = array(
    'header' => $header,
    'rows' => $rows,
    'attributes' => array(
      'class' => array(
        'visit-steps'
      )
    ),
  );
  return Intel_Df::theme('table', $vars);
}

function theme_intel_visit_steps_table(&$variables) {
  $output = '';

  $steps = $variables['steps'];

  $header = array(
    __('Time', 'intel'),
    __('Page/Event', 'intel'),
    __('Value', 'intel'),
  );
  // preprocess to assign events to correct pages
  $last_page_ts = 0;
  $last_page_path = '';
  foreach ($steps as $i => $step) {
    $ts = $step['time'];
    if ($step['type'] == 'page') {
      $last_page_ts = $ts;
      $last_page_path = $step['pagePath'];
    }
    if ($step['type'] == 'event') {
      if ($ts == $last_page_ts) {
        if ($last_page_path != $step['pagePath']) {
          $steps[$i]['time'] -= 1;
        }
        if (substr($step['category'], 0, 16) == 'Form submission:') {
          $steps[$i]['time'] -= 1;
        }
      }
    }
  }

  uasort($steps, '_intel_sort_session_steps');

  $rows = array();
  $start_time = 0;
  $last_page_ts = 0;
  $last_step_ts = 0;
  foreach ($steps as $i => $step) {
    $ts = $step['time'];
    if (!$start_time) {
      $start_time = $ts;
    }
    $row = array();
    $event_rows = array();
    $time = $ts - $start_time;
    $tf = ($time > 3600) ? 'H:i:s' : 'i:s';
    if ($step['type'] == 'page') {
      if (!empty($step['pageviews'])) {
        $row = array(
          'class' => array('page'),
        );
        $row['data'] = array(
          '+' . date($tf, $time),
          Intel_Df::l($step['pageTitle'], '//' . $step['hostname'] . $step['pagePath']),
          (!empty($step['value'])) ? intel_event_value_format($step['value'], array('mode' => 'valued')) : '-',
        );
        $rows[] = $row;
        $last_step_ts = $last_page_ts = $ts;
      }
    }
    elseif ($step['type'] == 'event') {
      $class = 'event';
      //if (substr($step['category'], -1) == '!') {
      if ($step['mode'] == 'valued') {
        $class .= ' valued-event';
      }
      elseif ($step['mode'] == 'goal') {
        $class .= ' goal-event';
      }
      else {
        $class .= ' standard-event';
      }
      $row = array(
        'class' => array($class),
      );
      $row['data'] = array(
        ($ts == $last_step_ts) ? '"' : '+' . date($tf, $time),
        '<span class="' . $class . '">' . $step['category'] . ': ' . $step['action'] . '</span>',
        (!empty($step['value'])) ? intel_event_value_format($step['value'], array('mode' => $step['mode'])) : '-',
      );
      $rows[] = $row;
      $last_step_ts = $ts;
    }
    if (!empty($event_rows)) {
      $rows = array_merge($rows, $event_rows);
    }
  }
  $vars = array(
    'header' => $header,
    'rows' => $rows,
    'attributes' => array(
      'class' => array(
        'visit-steps'
      )
    ),
  );
  return Intel_Df::theme('table', $vars);
}

function template_preprocess_intel_visitor_visits_table(&$variables) {
  $vdata = $variables['entity']->data;
  $variables['title'] = '';
  $variables['markup'] = '';
  // generate visits table
  if (!empty($vdata['analytics_visits'])) {
    $rows = array();
    uasort($vdata['analytics_visits'], function ($a, $b) {
        if (!isset($a['time']) || !isset($b['time'])) {
          return 1;
        }
        return ($a['time'] < $b['time']) ? 1 : -1;
      }
    );
    foreach ($vdata['analytics_visits'] AS $i => $visit) {
      if (substr($i, 0, 1) == '_') {
        continue;
      }
      $medium = isset($visit['trafficsource']['medium']) ? esc_html($visit['trafficsource']['medium']) : Intel_Df::t('(not set)');
      $source = isset($visit['trafficsource']['source']) ? esc_html($visit['trafficsource']['source']) : Intel_Df::t('(not set)');
      $score = !empty($visit['score']) ? $visit['score'] : 0;
      $rows[] = array(
        isset($visit['time']) ? Intel_Df::format_date($visit['time'], 'medium') : Intel_Df::t('(not set)'),
        $medium . ' / ' . $source,
        $visit['entrance']['pageviews'],
        number_format($score, 2),
        Intel_Df::l(Intel_Df::t('view'), $variables['entity']->uri() . '/clickstream', array('query' => array('visit-ts' => $visit['time']))),
      );
    }
    if (count($rows)) {
      $tvars = array();
      $tvars['rows'] = $rows;
      $tvars['header'] = array(
        Intel_Df::t('Visit time'),
        Intel_Df::t('Traffic source'),
        Intel_Df::t('Pageviews'),
        Intel_Df::t('Value'),
        Intel_Df::t('Ops'),
      );
      $table = Intel_Df::theme('table', $tvars);
      $variables['title'] = Intel_Df::t('Recent site visits') . ((count($rows) > 9) ? ' (last 10)' : '');
      $variables['markup'] = $table;
    }
  }
}

function theme_intel_property_browser_environment($variables) {
  if (!empty($variables['entity'])) {
    $entity = $variables['entity'];
    $env = $entity->getVar('data', 'environment');
  } elseif (!empty($variables['environment'])) {
    $env = $variables['environment'];
  }

  if (empty($env)) {
    return;
  }
  $items = array();
  $items[] = intel_visitor_format_profile_item(Intel_Df::t('Browser'), Intel_Df::check_plain($env['browser']) . " v" . Intel_Df::check_plain($env['browserVersion']));
  $items[] = intel_visitor_format_profile_item(Intel_Df::t('OS'), Intel_Df::check_plain($env['operatingSystem']) . " " . Intel_Df::check_plain($env['operatingSystemVersion']));
  if (!empty($env['mobileDeviceInfo'])) {
    $items[] = intel_visitor_format_profile_item(Intel_Df::t('Mobile device'), Intel_Df::check_plain($env['mobileDeviceInfo']));
  }
  $items[] = intel_visitor_format_profile_item(Intel_Df::t('Screen'), Intel_Df::check_plain($env['screenResolution']));
  $items[] = intel_visitor_format_profile_item(Intel_Df::t('Language'), Intel_Df::check_plain($env['language']));

  $output = Intel_Df::theme('intel_visitor_profile_item_list', array('items' => $items));
  return $output;
}

function template_process_intel_browser_environment_block(&$variables) {
  $variables['markup'] = Intel_Df::theme('intel_browser_environment', $variables);
  if (empty($variables['markup'])) {
    // remove title to clear block
    $variables['title'] = '';
    return;
  }
  if (empty($variables['title'])) {
    $variables['title'] = Intel_Df::t('Browser environment');
  }
  if (!isset($variables['attributes']['class'])) {
    $variables['attributes']['class'] = array();
  }
  $variables['attributes']['class'][] = 'card';
  $variables['attributes']['class'][] = 'browser-environment';
  $variables['attributes'] = Intel_Df::drupal_attributes($variables['attributes']);
}

function theme_intel_property_trafficsource($variables) {
  $output = '';
  if (!empty($variables['entity'])) {
    $entity = $variables['entity'];
    $source = $entity->getVar('data', 'analytics_session.trafficsource');
    if (isset($source[0])) {
      $source = $source[0];
    }
  }
  elseif (!empty($variables['trafficsource'])) {
    $source = $variables['trafficsource'];
  }
  else {
    $source = array();
  }

  $items = array();
  $tldata_link = '';
  //$tlreferrer = '';
  $ref_alts = array(
    'http://google.com/search?q=(not provided)' => 'http://google.com/search',
    'http://facebook.com' => 'http://www.facebook.com'
  );

  if (empty($source['campaign'])) {
    $source['campaign'] = '(not set)';
  }
  if (empty($source['medium'])) {
    $source['medium'] = '(not set)';
  }
  if (empty($source['source'])) {
    $source['source'] = '(not set)';
  }
  if (empty($source['socialNetwork'])) {
    $source['socialNetwork'] = '(not set)';
  }
  if ($source['campaign'] != '(not set)') {
    $items[] = intel_visitor_format_profile_item(Intel_Df::t("Campaign"), $source['campaign']);
    $items[] = intel_visitor_format_profile_item(Intel_Df::t("Source / Medium"), $source['source'] . ' / ' . $source['medium']);
    //$items[] = intel_visitor_format_profile_item(Intel_Df::t("Medium"), $source['medium']);
    if (isset($source['keyword'])) {
      $items[] = intel_visitor_format_profile_item(Intel_Df::t("Keyword"), $source['keyword']);
    }
    if (isset($source['adContent'])) {
      $items[] = intel_visitor_format_profile_item(Intel_Df::t("Content"), $source['adContent']);
    }
  }
  else {
    if ($source['medium'] == '(none)') {
      $items[] = intel_visitor_format_profile_item(Intel_Df::t("Source"), $source['source']);
    }
    elseif ($source['medium'] == 'referral') {
      $items[] = intel_visitor_format_profile_item(Intel_Df::t("Medium"), $source['medium']);
    }
    else {
      $items[] = intel_visitor_format_profile_item(Intel_Df::t("Source / Medium"), $source['source'] . ' / ' . $source['medium']);
      //$items[] = intel_visitor_format_profile_item(Intel_Df::t("Source"), $source['medium'] . ' / ' . $source['source']);
    }
    if ($source['medium'] != '(none)') {
      if ($source['medium'] == 'organic') {
        $keyword = $source['keyword'];
        if ($source['source'] == 'google') {
          $keyword = Intel_Df::l($keyword, 'http://google.com/search?q=' . $keyword, array('attributes' => array('target' => $source['source'])));
        }
        $items[] = intel_visitor_format_profile_item(Intel_Df::t("Keyword"), $keyword);
      }
      if ($source['medium'] == 'referral') {
        $url = $source['source'] . $source['referralPath'];
        $l = Intel_Df::l($url, "http://" . $url, array('attributes' => array('target' => $source['source'])));
        $item[] = intel_visitor_format_profile_item(Intel_Df::t("Path"), $l);
      }
      if ($source['socialNetwork'] != '(not set)') {
        $item[] = intel_visitor_format_profile_item(Intel_Df::t("Social network"), $source['socialNetwork']);
      }
      if ($source['campaign'] != '(not set)') {
        $item[] = intel_visitor_format_profile_item(Intel_Df::t("Campaign"), $source['campaign']);
      }
    }
  }


  return Intel_Df::theme('intel_visitor_profile_item_list', array('items' => $items));
}

function template_process_intel_trafficsource_block(&$variables) {
  $variables['markup'] = Intel_Df::theme('intel_trafficsource', $variables);
  if (empty($variables['title'])) {
    $variables['title'] = Intel_Df::t('Traffic source');
  }
  if (!isset($variables['attributes']['class'])) {
    $variables['attributes']['class'] = array();
  }
  $variables['attributes']['class'][] = 'card';
  $variables['attributes']['class'][] = 'trafficsource';
  $variables['attributes'] = Intel_Df::drupal_attributes($variables['attributes']);
}

function theme_wp_screen(&$vars) {
  global $intel_wp_screen;

  $screen = get_current_screen();

  $intel_wp_screen = (object) array(
    'id' => !empty($vars['screen_id']) ? $vars['screen_id'] : $screen->id,
    'variables' => $vars,
  );

  $output = '';
  $class = 'wrap intel-wp-screen';
  if (!empty($vars['class'])) {
    $class .= ' ' . implode(' ', $vars['class']);
  }

  $output .= '<div class="' . $class . '">';

  if (isset($vars['title'])) {
    $output .= '<h1>' . $vars['title'] . '</h1>';
  }

  if (isset($vars['content'])) {
    $output .= $vars['content'];
  }

  $output .= '</div>';

  return $output;
}

function template_preprocess_wp_notice(&$vars) {
  $type = !empty($vars['type']) ? $vars['type'] : 'error';
  $class = array(
    'intel-notice',
    'notice',
    'notice-' . $type,
  );
  if (!empty($vars['inline'])) {
    $class[] = "inline";
  }

  if (!empty($vars['class']) && is_array($vars['class'])) {
    $class = array_merge($class, $vars['class']);
  }

  $vars['class'] = $class;
}

function theme_wp_notice(&$vars) {
  $notice_class = implode(' ', $vars['class']);

  $output = '';
  $output .= '<div class="' . $notice_class . '">';
  $output .= '<p>';
  $output .= '<strong>' . __('Notice:') . '</strong> ';
  $output .= $vars['message'];
  $output .= '</p>';
  $output .= '</div>';

  return $output;
}

function theme_wp_welcome_panel(&$vars) {
  $output = '';
  $class = 'welcome-panel intel-wp-welcome-panel';
  if (!empty($vars['class'])) {
    $class .= ' ' . implode(' ', $vars['class']);
  }
  $output .= '<div id="welcome-panel" class="' . $class . '">';

  if (isset($vars['panel_header'])) {
    $output .= $vars['panel_header'];
  }

  $output .= '<div class="welcome-panel-content">';

  if (isset($vars['title'])) {
    $output .= '<h2 class="title">' . $vars['title'] . '</h2>';
  }

  if (isset($vars['description'])) {
    $output .= '<div class="about-description">' . $vars['description'] . '</div>';
  }

  if (isset($vars['body'])) {
    $output .= '<div class="welcome-panel-column-container">';
    if (is_array($vars['body'])) {
      foreach ($vars['body'] as $cnt => $content) {
        $class = 'welcome-panel-column-container';
        if ($cnt == (count($content) - 1)) {
          $class .= ' welcome-panel-last';
        }
        $output .= '<div class="' . $class . '">';
        $output .= $content;
        $output .= '</div>'; // end div.welcome-panel-column-container
      }
    }
    else {
      $output .= $vars['body'];
    }

    $output .= '</div>'; // end div.welcome-panel-column-container

  }

  $output .= '</div>'; // end div.welcome-panel-content

  if (isset($vars['panel_footer'])) {
    $output .= $vars['panel_footer'];
  }

  $output .= '</div>'; // end div#welcome-panel

  return $output;
}