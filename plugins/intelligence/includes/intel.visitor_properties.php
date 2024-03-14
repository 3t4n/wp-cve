<?php
/**
 * @file
 * Support for syncing visitor data across various data sources
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_get_itemprop_context($prop_name) {
  $ip_info = _intel_intel_itemprop_info();

  $context = array();

  // add namespaces
  //$context['site'] = 'http://' . $_SERVER['HTTP_HOST'];
  $context['io'] = 'http://' . $_SERVER['HTTP_HOST'] . $GLOBALS['base_path'] . 'intel/context/';
  $context['xsd'] = 'http://www.w3.org/2001/XMLSchema#';

  // add aliases
  $context['type'] = '@type';
  $context['url'] = '@id';

  if (empty($ip_info[$prop_name])) {
    return $context;
  }

  $classes = array();
  $props = array();

  $context = _intel_build_context($prop_name, $context, $ip_info);

  // minimize context
  foreach ($context as $pn => $pi) {
    $a = array();
    if (is_object($pi)) {
      $a = get_object_vars($pi);
    }
    elseif (is_array($pi)) {
      $a = $pi;
    }
    if (!empty($a)) {
      if (isset($a['@id']) && count($a) == 1) {
        $context[$pn] = $a['@id'];
      }
    }
  }

  // convert arrays into objects recursivly for compatability with json-ld lib
  $a = json_encode($context);
  $context = json_decode($a);

  return $context;

}

function _intel_build_context($prop_name, $context, $itemprop_info) {
  if (empty($itemprop_info[$prop_name]['property'])) {
    return $context;
  }
  if (!empty($itemprop_info[$prop_name]['context']) && is_array($itemprop_info[$prop_name]['context'])) {
    $context = array_merge($context, $itemprop_info[$prop_name]['context']);
  }
  foreach ($itemprop_info[$prop_name]['property'] as $pn => $pi) {
    if (!isset($context[$pn])) {
      $context[$pn] = array();
    }
    if ($pi == '' && !empty($itemprop_info[$prop_name]['default_property_ns'])) {
      $pi = $itemprop_info[$prop_name]['default_property_ns'] . ':' . $pn;
    }
    if (is_string($pi)) {
      $pi = (object) array(
        '@id' => $pi,
      );
    }
    if (!is_scalar($pi)) {
      $context[$pn] = Intel_Df::drupal_array_merge_deep($context[$pn], $pi);
    }
  }
  if (!empty($itemprop_info[$prop_name]['parent'])) {
    $context = _intel_build_context($itemprop_info[$prop_name]['parent'], $context, $itemprop_info);
  }
  return $context;
}

function intel_get_itemprop_form_options($prop_name, $field_type = 'entity:text') {
  $ip_info = _intel_intel_itemprop_info();

  $form_options = array();

  if (empty($ip_info[$prop_name])) {
    return $form_options;
  }

  $options = array(
    'field_type' => $field_type,
  );

  $form_options = _intel_build_form_options($prop_name, $form_options, $ip_info, $options);

  return $form_options;

}

function _intel_build_form_options($prop_name, $form_options, $itemprop_info, $options) {
  if (empty($itemprop_info[$prop_name]['property'])) {
    return $form_options;
  }

  foreach ($itemprop_info[$prop_name]['property'] as $pn => $pi) {
    if (empty($pi['field_type']) || !is_array($pi['field_type']) ||!in_array($options['field_type'], $pi['field_type'])) {
      continue;
    }
    $key = 'data.property.' . $pn;
    $label = $pn;
    $form_options[$key] = $label;
  }
  if (!empty($itemprop_info[$prop_name]['parent'])) {
    $form_options = _intel_build_form_options($itemprop_info[$prop_name]['parent'], $form_options, $itemprop_info, $options);
  }
  return $form_options;
}

/**
 * If no expctedType, assume Text
 */
function _intel_intel_itemprop_info() {
  $prop = array();

  $text_field = array(
    '#fieldType' => array(
      'text'
    ),
    '#webformComponentType' => array(
      'text'
    ),
  );

  // Data types
  $prop['Text'] = array(
    'data_type' => 'String',
  );

  $prop['URL'] = array(
    'data_type' => 'Url',
  );

  $prop['Date'] = array(
    'data_type' => 'Date',
  );

  $prop['Thing'] = array(
    '@id' => 'io:Thing',
    'type' => 'object',
    'description' => Intel_Df::t('A thing.'),
    'context' => array(
      'schema' => 'http://schema.org/',
    ),
    'default_property_ns' => 'schema',
    'property' => array(
      'description' => '',
      'image' => '',
      'name' => '',
      'url' => '',
    ),
  );

  $prop['Person'] = array(
    '@id' => 'io:Person',
    'type' => 'object',
    'description' => Intel_Df::t('A person, know (contact) or unknown (visitor)'),
    'parent' => 'Thing',
    'context' => array(
      'foaf' => 'http://xmlns.com/foaf/0.1/',
      'schema' => 'http://schema.org/',
      'vcard' => 'http://www.w3.org/2006/vcard/ns#',
    ),
    'default_property_ns' => 'schema',
    'property' => array(
      'address' => "vcard:address",
      'birthday' => (object) array(
        '@id' => 'http://schema.org/birthDate',
        '@type' => 'xsd:date',
      ),
      'email' => '',
      'familyName' => '',
      'gender' => '',
      'givenName' => '',
      'honorificPrefix' => '',
      'honorificSuffix' => '',
      'jobTitle' => '',
      'nickname' => 'foaf:nick',
      'telephone' => '',
      'facebook' => '',
    ),
  );

  $prop['SocialProfile'] = array(
    '@id' => 'io:SocialProfile',
    'type' => 'object',
    'parent' => 'Thing',
    'description' => Intel_Df::t('A person, know (contact) or unknown (visitor)'),
    'default_property_ns' => 'schema',
    'property' => array(
      'username' => 'io:username',
    ),
  );

  $prop['facebook'] = array(
    '@id' => 'io:facebook',
    'expected_type' => array(
      'Url',
      'SocialProfile'
    ),
  );

  $prop['Visitor'] = array(
    'context' => 'io:Visitor',
    'label' => Intel_Df::t('Visitor'),
    'description' => Intel_Df::t('A person, know (contact) or unknown (visitor)'),
    'parent' => 'Person',
    'default_property_ns' => 'io',
    'property' => array(
      'vtk' => '',
    ),
  );

  $prop['name'] = array(
    '@id' => 'schema:name',
    '@type' => 'Text',
    'description' => Intel_Df::t('Name of the item'),
    'field_type' => array(
      'entity:text',
      'webform:text',
    ),
  );

  $prop['vtk'] = array(
    'context' => (object) array(
      '@id' => 'io:vtk',
      '@type' => 'xsd:string'
    ),
    'description' => Intel_Df::t('A hash token to identify site visitors'),
  );

  return $prop;

  $prop['URL'] = array(

  );

  $prop['Thing'] = array();
  $prop['Thing']['name'] = array(
    '#description' => Intel_Df::t('Name of the item'),
  );
  $prop['Thing']['description'] = array(
    '#description' => Intel_Df::t('A description of the item.'),
  );
  $prop['Thing']['image'] = array(
    '#description' => Intel_Df::t('An image of the item.'),
    '#expectedType' => array(
      'URL',
      'ImageObject'
    ),
  );
  $prop['Person'] = array(
    '#inherits' => array(
      'Thing'
    ),
  );

  return $prop;
}

function _intel_intel_visitor_property_info($prop) {
  $text_type = array(
    'field_type' => array(
      'text',
    ),
    'webform_component_type' => array(
      'text'
    )
  );

  $prop['data.name'] = array(
    'title' => Intel_Df::t('Name'),
    //'description' => Intel_Df::t('Name'),
    'category' => 'identity',
    'variables' => array(
      '@value' => NULL,
    ),
    '@context' => 'http://schema.org/name',
  ) + $text_type;

  $prop['data.description'] = array(
    'title' => Intel_Df::t('Description'),
    'category' => 'identity',
    'variables' => array(
      '@value' => NULL,
    ),
    '@context' => 'http://schema.org/description',
  ) + $text_type;

  $prop['data.image'] = array(
    'title' => Intel_Df::t('Image'),
    'category' => 'identity',
    'variables' => array(
      'url' => NULL,
      'alt' => NULL,
      'title' => NULL,
    ),
    '@context' => 'http://schema.org/image',
  ) + $text_type;

  // identity properties
  $prop['data.vtk'] = array(
    'title' => Intel_Df::t('Visitor token'),
    'category' => 'identity',
    'variables' => array(
      'vtk' => NULL,
      'vtkid' => NULL,
      'vtkc' => NULL,
    ),    
  );

  $prop['data.age'] = array(
    'title' => Intel_Df::t('Age'),
    'category' => 'identity',
    'variables' => array(
      '@value' => NULL,
      'rangeLow' => NULL,
      'rangeHigh' => NULL,
    ),
  ) + $text_type;

  $prop['data.birthDate'] = array(
    'title' => Intel_Df::t('Birth date'),
    'category' => 'identity',
    'variables' => array(
      '@value' => NULL,
    ),
    '@context' => (object) array(
      "@id" => "http://schema.org/birthDate",
      "@type" => "xsd:date",
    )
  ) + $text_type;

  $prop['data.gender'] = array(
    'title' => Intel_Df::t('Gender'),
    'category' => 'identity',
    'variables' => array(
      '@value' => NULL,
    ),
    '@context' => 'http://schema.org/gender',
  ) + $text_type;

  $prop['data.givenName'] = array(
    'title' => Intel_Df::t('Given (first) name'),
    'category' => 'identity',
    'variables' => array(
      '@value' => NULL,
    ),
    '@context' => 'http://schema.org/givenName',
  ) + $text_type;

  $prop['data.familyName'] = array(
    'title' => Intel_Df::t('Family (last) name'),
    'category' => 'identity',
    'variables' => array(
      '@value' => NULL,
    ),
    '@context' => 'http://schema.org/familyName',
  ) + $text_type;

/*
  $prop['data.name'] = array(
    'title' => Intel_Df::t('Name'),
    'category' => 'identity',
    'variables' => array(
      'full' => NULL,
      'first' => NULL,
      'last' => NULL,
      'title' => NULL,
      'given' => NULL,
      'middle' => NULL,
      'family' => NULL,
      'generational' => NULL,
      'credentials' => NULL,
    ),
  );
*/

  // contact points
  $prop['data.email'] = array(
    'title' => Intel_Df::t('Email'),
    'category' => 'contact',
    'variables' => array(
      '@value' => NULL,
    ),
    '@context' => 'http://schema.org/email',
  ) + $text_type;

  $prop['data.telephone'] = array(
    'title' => Intel_Df::t('Telephone'),
    'category' => 'contact',
    'variables' => array(
      '@value' => NULL,
    ),
    '@context' => 'http://schema.org/telephone',
  ) + $text_type;

  $prop['data.url'] = array(
    'title' => Intel_Df::t('URL (website)'),
    'category' => 'contact',
    'variables' => array(
      'url' => NULL,
    ),
    '@context' => 'http://schema.org/url',
  );

  // organization
  // social properties
  $prop['data.worksFor'] = array(
    'title' => Intel_Df::t('Works for'),
    'category' => 'organization',
    'variables' => array(
      '@value' => NULL,
    ),
    '@context' => 'http://schema.org/worksFor',
  ) + $text_type;

  $prop['data.jobTitle'] = array(
    'title' => Intel_Df::t('Job Title'),
    'category' => 'organization',
    'variables' => array(
      '@value' => NULL,
    ),
    '@context' => 'http://schema.org/jobTitle',
  ) + $text_type;
  
  // social properties
  $prop['data.aboutme'] = array(
    'title' => Intel_Df::t('About.me'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
    ),
  );

  $prop['data.facebook'] = array(
    'title' => Intel_Df::t('Facebook'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'id' => NULL,
      'username' => NULL,
    ),
    //'icon_class' => 'fa fa-facebook-square',
  );
  $prop['data.flickr'] = array(
    'title' => Intel_Df::t('Flickr'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'id' => NULL,
      'username' => NULL,
    ),
    //'icon_class' => 'fa fa-flickr',
  );
  $prop['data.friendfeed'] = array(
    'title' => Intel_Df::t('Friendfeed'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'id' => NULL,
      'username' => NULL,
    ),
  );
  $prop['data.foursquare'] = array(
    'title' => Intel_Df::t('Foursquare'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'id' => NULL,
    ),
    //'icon_class' => 'fa fa-foursquare',
  );
  $prop['data.googleplus'] = array(
    'title' => Intel_Df::t('Google+'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'id' => NULL,
    ),
    //'icon_class' => 'fa fa-google-plus-square',
  );
  $prop['data.googleprofile'] = array(
    'title' => Intel_Df::t('Google Profile'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'id' => NULL,
    ),
  );
  $prop['data.gravatar'] = array(
    'title' => Intel_Df::t('Gravatar'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'username' => NULL,
    ),
  );

  $prop['data.klout'] = array(
    'title' => Intel_Df::t('Klout'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'id' => NULL,
      'username' => NULL,
      'score' => NULL,
      'topics' => array(),
    ),
  );
  $prop['data.lanyrd'] = array(
    'title' => Intel_Df::t('Lanyrd'),
    'variables' => array(
      'url' => NULL,
    ),
  );
  $prop['data.linkedin'] = array(
    'title' => Intel_Df::t('LinkedIn'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
    ),
  );
  $prop['data.myspace'] = array(
    'title' => Intel_Df::t('Myspace'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
    ),
  );
  $prop['data.picasa'] = array(
    'title' => Intel_Df::t('Picasa'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
    ),
  );
  $prop['data.quora'] = array(
    'title' => Intel_Df::t('Quora'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'username' => NULL,
    ),
  );
  $prop['data.tumblr'] = array(
    'title' => Intel_Df::t('tumblr'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'username' => NULL,
    ),
  );
  $prop['data.twitter'] = array(
    'title' => Intel_Df::t('Twitter'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'username' => NULL,
      'followers' => NULL,
      'following' => NULL,
    ),
    'key' => 'username',
    'process callbacks' => array('intel_visitor_property_twitter_process'),
  );
  $prop['data.vimeo'] = array(
    'title' => Intel_Df::t('Vimeo'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
    ),
  );
  $prop['data.yahoo'] = array(
    'title' => Intel_Df::t('Yahoo!'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
      'username' => NULL,
    ),
  );
  $prop['data.youtube'] = array(
    'title' => Intel_Df::t('YouTube'),
    'category' => 'social',
    'variables' => array(
      'url' => NULL,
    ),
  );

  $prop = intel_add_icons_to_visitor_properties($prop);

  $attr_info = intel_get_attribute_info();

  if (is_array($attr_info)) {
    foreach ($attr_info as $k => $v) {
      if (!empty($v['source_type']) && $v['source_type'] == 'taxonomy') {
        $prop['data.analytics.' . $k] = array(
          'title' => $v['title'],
          'category' => Intel_Df::t('attributes'),
          'source_type' => !empty($v['source_type']) ? $v['source_type'] : '',
          'variables' => array(
            '_key' => NULL,
          ),
        );
      }
      /*
      if (!empty($v['prop']) && $v['prop']) {
        $prop['data.' . $v['prop']] = array(
          'title' => $v['title'],
          'category' => Intel_Df::t('attributes'),
          'source_type' => !empty($v['source_type']) ? $v['source_type'] : '',
          'variables' => array(
            '@value' => NULL,
          ),
        );
      }
      */
    }
  }

  return $prop;
}

function intel_visitor_property_select_options() {
  $prop_info = intel_get_visitor_property_info_all();
  $options = array();
  $options[''] = ' - ' . Intel_Df::t('None') . '';
  foreach ($prop_info AS $ns => $pi) {
    foreach ($pi['variables'] AS $key => $default) {
      $options["$ns:$key"] = $pi['title'] . " ($key)";
    }
  }
  return $options;
}

function intel_add_icons_to_visitor_properties($props) {
  $available = array(
    'data.facebook' => 'facebook',
    'data.flickr' => 'flickr',
    'data.foursquare' => 'foursquare',
    'data.friendfeed' => 'friendfeed',
    'data.googleplus' => 'googleplus',
    'data.googleprofile' => 'googleprofile',
    'data.gravatar' => 'gravatar',  
    'data.klout' => 'klout',  
    'data.lanyrd' => 'lanyrd',
    'data.linkedin' => 'linkedin',
    'data.myspace' => 'myspace',
    'data.picasa' => 'picasa',
    'data.tumblr' => 'tumblr',
    'data.twitter' => 'twitter',    
    'data.vimeo' => 'vimeo',
    'data.yahoo' => 'yahoo',
    'data.youtube' => 'youtube',
  );
  //$available = array(
  //  'data.facebook' => 'facebook',
  //);
  $intel_icon_urls = get_option('intel_icon_urls', array());
  $icon_base = intel_get_icon_params();
  $save_icon_urls = 0;
  foreach ($props AS $id => $value) {
    // check if prop does not have icon
    if (!isset($available[$id])) {
      continue;
    }

    $icon_name = $available[$id];
    if (empty($intel_icon_urls[$icon_name])) {
      $icon = clone $icon_base;
      $icon->name = $icon_name;
      if ($icon = intel_create_icon($icon)) {
        $intel_icon_urls[$icon_name] = $icon->url;
        $save_icon_urls = 1;
      }
    }
    if (!empty($intel_icon_urls[$icon_name])) {
      $props[$id]['icon'] = $intel_icon_urls[$icon_name];
    }
  }
  if ($save_icon_urls) {
    update_option('intel_icon_urls', $intel_icon_urls);
  }
  return $props;
}

function intel_create_icon($icon) {
  // TODO: performance problem with this function on some servers, assuming
  // if don't have permissions to write files
  if (!intel_is_api_level('pro')) {
    return '';
  }
  $url = '';
  $api_params = get_option('intel_l10iapi_custom_params', array());
  $apiClientProps = array(
    'apiUrl' => intel_get_iapi_url() . '/',
    'apiConnector' => get_option('intel_l10iapi_connector', ''),
  );

  if (!intel_include_library_file('class.apiclient.php')) {
    return FALSE;
  }
  $apiclient = new \LevelTen\Intel\ApiClient($apiClientProps);
  $ret = array();
  $params = (array)$icon;
  $params['tid'] = get_option('intel_ga_tid', '');
  $data = array(
    'apikey' => get_option('intel_apikey', '')
  );
  try {
    $ret = (array) $apiclient->getJSON('icon/load', $params, $data);
  }
  catch (Exception $e) {
    //drupal_set_message($e->getMessage(), 'warning');
  }

  if (empty($ret['icon'])) {
    return FALSE;
  }
  // if api returns an object, convert to array
  if (is_object($ret['icon'])) {
    $ret['icon'] = (array) $ret['icon'];
  }
  if (!isset($ret['icon']['url'])) {
    return FALSE;
  }

  $file = $ret['icon']['url'];

  $ext = 'png';

  $uri = "intel/icons/{$icon->set}/{$icon->style}/{$icon->size}";

  $uploaddir = wp_upload_dir();
  $basedir = $uploaddir['basedir'] . '/';
  if (!file_exists($basedir . $uri)) {
    mkdir($basedir . $uri, 0777, true);
  }
  $dest = $uri . "/{$icon->name}.$ext";

  $uploadfile = $basedir . $dest;

  $contents= file_get_contents($file);
  $savefile = fopen($uploadfile, 'w');
  fwrite($savefile, $contents);
  fclose($savefile);

  $icon->url = $dest;
  return $icon;
}

function intel_get_icon_params() {
  $params = (object)array(
    'set' => 'fullcontact',
    'style' => 'default',
    'size' => 32,
  );
  return $params;
}

function intel_visitor_property_twitter_process(&$prop, $prop_info, $visitor) {
  $key = (!empty($prop['username'])) ? $prop['username'] : '';
  if (empty($prop['url'])) {
    $prop['url'] = 'http://twitter.com/' . $prop['username'];
  }
}

/**
 * groups flat level properties into multi dimensional array
 * @param $values
 */
function intel_visitor_property_group($values) {
  $grouped = array();
  foreach ($values AS $coded => $value) {
    $codes = explode(':', $coded);
    $prop_name = $codes[0];
    $elem = $codes[1];
    $index = isset($codes[2]) ? $codes[2] : 0;

    if (!isset($grouped[$prop_name])) {
      $grouped[$prop_name] = array();
    }
    if (!isset($grouped[$prop_name][$index])) {
      $grouped[$prop_name][$index] = array();
    }
    $grouped[$prop_name][$index] = $value;
  }
  return $grouped;
}

function intel_theme_visitor_property($variables) {
  $out = '';
  $info = $variables['info'];
  $value = $variables['value'];

  if (count($info['variables']) > 1) {
    foreach ($info['variables'] as $name => $v) {
      if (!empty($value[$name])) {
        $v = $value[$name];
        if (is_array($v)) {
          $v = implode(', ', $v);
        }
        if ($name == 'url') {
          $v= Intel_Df::l($v, $v);
        }
        $out .= '<dt>' . $name . '</dt><dd>' . $v . '</dd>';
      }
    }
  }
  else {
    if (!empty($value['@value'])) {
      $out .= $value['@value'];
    }
  }
  return $out;
}

add_filter('intel_visitor_property_webform_info', '_intel_visitor_property_webform_info');
function _intel_visitor_property_webform_info($info) {
  $info = array();

  $info['data.name'] = array();

  $info['data.givenName'] = array();

  $info['data.familyName'] = array();

  $info['data.email'] = array(
    'field_type' => 'email',
  );

  $info['data.telephone'] = array(
    'field_type' => 'telephone',
  );

  $info['data.url'] = array();

  $info['data.description'] = array();

  $info['data.worksFor'] = array();

  $info['data.jobTitle'] = array();

  $info['data.age'] = array();

  $info['data.birthDate'] = array();

  $into['data.gender'] = array();

  return $info;
}

class Intel_ItemProp {

  public $info;
  public $name;
  protected $data;

  public function __construct($name, $values = array()) {
    $this->name = $name;
    $this->info = intel()->visitor_property_info($name);
    foreach ($this->info['variables'] as $k => $v) {
      $this->data[$k] = isset($values[$k]) ? $values[$k] : $v;
    }
  }

  public function toString() {
    return $this->data['@value'];
  }


}

