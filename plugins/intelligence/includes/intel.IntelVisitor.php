<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intel
 * @subpackage Intel/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Intel
 * @subpackage Intel/includes
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class IntelVisitor extends IntelEntity  {

	// holds the apiVisitor data
	public $apiVisitor;
	// holds apiPerson data
	public $apiPerson;
  // visitor identifiers
	public $identifiers = array();

	protected $intel;
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	/**
	 * Override constructor to set entity type.
	 */
	public function __construct(array $values = array(), $controller) {
		$this->intel = intel();

    parent::__construct($values, $controller);

		// process values identifiers
		if (!empty($values['identifiers'])) {
			$this->identifiers = $values['identifiers'];
		}
		if (!empty($this->identifiers['vtk'])) {
			$this->vtk = $this->identifiers['vtk'][0];
		}
		if (!empty($this->identifiers['gacid'])) {
			$this->gacid = $this->identifiers['gacid'][0];
		}

    if (empty($this->vtk) && !empty($this->vtkid)) {
      $this->vtk = $this->vtkid . $this->vtkc;
    }
    if (empty($this->vtkid) && !empty($this->vtk)) {
      $this->vtkid = substr($this->vtk, 0, 20);
      $this->vtkc = substr($this->vtk, 20);
    }

		//if (!isset($this->data['syncStatus'])) {
		//	$this->data['syncStatus'] = $this->syncStatusConstruct();
		//}

		if (!empty($this->vid)) {
			$this->completeLoad();
		}

		// Set for checks if visitor data is available in IAPI
		$this->apiLevel = intel_api_level();

		$apiClientProps = intel_get_ApiClientProps();

		intel_include_library_file('class.visitor.php');
		$this->apiVisitor = new \LevelTen\Intel\ApiVisitor($this->vtk, $apiClientProps);

		intel_include_library_file('class.person.php');
		$this->apiPerson = new \LevelTen\Intel\ApiPerson(array('email' => $this->getEmail()), $apiClientProps);

		if (class_exists('\Kint\Parser\BlacklistPlugin')) {
      Kint\Parser\BlacklistPlugin::$shallow_blacklist[] = 'Intel';
    }
	}

	public function completeLoad() {
		$this->attachIdentifiers($this->controller->loadIdentifiers($this));
		if (empty($this->vtk) && !empty($this->identifiers['vtk'][0])) {
			$this->setVtk($this->identifiers['vtk'][0]);
		}
	}

	public function setIdType($idType) {
		if ($idType) {
			$this->idType = $idType;
		}
	}

	public function getIdType() {
		return $this->idType;
		//return $this->idKey;
	}

	public function getApiClientProps() {
		return intel_get_ApiClientProps();
	}

	public function apiVisitorLoad($params = array()) {
		if ($this->apiLevel != 'pro') {
			return FALSE;
		}
		if (!isset($this->vtk)) {
			$this->apiVisitorLoad_error = new Exception(__('No vtk is set.', 'intel'));
			return FALSE;
		}
		if (!isset($this->apiVisitor)) {
      intel_include_library_file('class.visitor.php');
			$apiClientProps = $this->getApiClientProps();
			$this->apiVisitor = new \LevelTen\Intel\ApiVisitor($this->vtk, $apiClientProps);
		}
		try {
			$this->apiVisitor->load($params);
		}
		catch (Exception $e) {
			$this->apiVisitorLoadError = $e;
			//throw new Exception('Unable to load api visitor: ' . $e);
		}
		return TRUE;
	}

	public function apiPersonLoad($params = array()) {
		if ($this->apiLevel != 'pro') {
			return FALSE;
		}
    $email = $this->getEmail();
		if (!$email) {
			$this->apiPersonLoad_error = new Exception(__('No email is set.', 'intel'));
			return FALSE;
		}
		if (!isset($this->apiPerson)) {
      intel_include_library_file('class.visitor.php');
			$apiClientProps = $this->getApiClientProps();
			$this->apiPerson = new \LevelTen\Intel\ApiPerson(array('email' => $email), $apiClientProps);
		}

		$this->apiPerson->setEmail($email);
		try {
			$obj = $this->apiPerson->load($params);
		}
		catch (Exception $e) {
			$this->apiPersonLoadError = $e;
			$obj = FALSE;
			//throw new Exception('Unable to load api visitor: ' . $e);
		}
		return $obj;
	}

	public function merge() {
		$this->save();
	}

	public function name() {
		return self::label();
	}

	public function label() {
		if ($this->name) {
			return $this->name;
		}
		else {
			$id = !empty($this->vtk) ? '(' . substr($this->vtk, 0, 10) . ')' : '';
			return 'anon ' . $id;
		}
	}

	public function identifier() {
		if (!empty($this->vid)) {
			return $this->vid;
		}
		elseif (!empty($this->vtkid)) {
			return $this->vtkid;
		}
		elseif (!empty($this->vtk)) {
			return $this->vtk;
		}
		return '';
	}

	public function uri() {
		return 'visitor/' . $this->identifier();
	}

	public function label_link($options = array()) {
		return Intel_Df::l($this->label(), $this->uri(), $options);
	}

	public function getProperties() {
		$props = (object) get_object_vars($this);
		if (is_string($props->data)) {
			$props->data = unserialize($props->data);
		}
		if (is_string($props->ext_data)) {
			$props->ext_data = unserialize($props->ext_data);
		}
		return $props;
	}

	public function getApiVisitor() {
		return $this->apiVisitor->getVisitor();
	}

	public function getVar($scope, $namespace = '', $keys = '', $default = null) {
		$a = explode('_', $scope);
		if ($a[0] == 'api') {
			if ($this->apiLevel != 'pro') {
				return $default;
			}
			if ($a[1] == 'person') {
				return $this->apiPerson->getVar($a[2], $namespace, $keys, $default);
			}
			else {
				return $this->apiVisitor->getVar($a[1], $namespace, $keys, $default);
			}
		}
		if ($scope == 'ext') {
			$data = $this->ext_data;
		}
		else {
			$data = $this->data;
		}

		if (is_string($data)) {
			$data = unserialize($data);
		}
		if (empty($data[$namespace])) {
			return $default;
		}
		$data = $data[$namespace];
    intel_include_library_file("libs/class.intel_data.php");
		return \LevelTen\Intel\IntelData::getVar($data, $keys, $default);
	}

	public function updateData($namespace, $value, $keys = '') {
		$this->setVar('data', $namespace, $keys, $value);
		$this->data_updated = REQUEST_TIME;
	}

	public function updateExt($namespace, $value, $keys = '') {
		$this->setVar('ext', $namespace, $keys, $value);
		$this->ext_updated = REQUEST_TIME;
	}

	public function setVar($scope, $namespace, $keys, $value = null) {

		$a = explode('_', $scope);
		if ($a[0] == 'api') {
			if ($this->apiLevel != 'pro') {
				return FALSE;
			}
			if ($a[1] == 'person') {
				return $this->apiPerson->getVar($a[2], $namespace, $keys);
			}
			else {
				return $this->apiVisitor->getVar($a[1], $namespace, $keys);
			}
		}
		// check if three arg pattern
		$args = func_get_args();
		if (count($args) == 3) {
			$value = $keys;
			$keys = $namespace;
		}
		else {
			$keys = $namespace . (($keys) ? '.' . $keys : '');
		}
		if ($scope == 'ext') {
			$data = $this->ext_data;
		}
		else {
			$data = $this->data;
		}
		if (is_string($data)) {
			$data = unserialize($data);
		}
    intel_include_library_file("libs/class.intel_data.php");
		$data = \LevelTen\Intel\IntelData::setVar($data, $keys, $value);
		if ($scope == 'ext') {
			$this->ext_data = $data;
			$this->ext_updated = $this->intel->request_time;
		}
		else {
			$this->data = $data;
			$this->data_updated = $this->intel->request_time;
		}
		return TRUE;
	}

	public function getProp($prop_name, $construct = 0) {
		//if (strpos($prop_name, '.') === FALSE) {
		//	$prop_name = 'data.' . $prop_name;
		//}
		$val = $this->getVar('data', $prop_name);
		if (empty($val)) {
			if ($construct) {
				return intel_get_visitor_property_construct($prop_name);
			}
			else {
				return NULL;
			}
		}
		return $val;
	}

	/**
	 * Sets a visitor property.
	 * @param $prop_name
	 * @param $values array of values matching property info variables
	 * @param $options
	 * @return bool
	 */
	public function setProp($prop_name, $values, $options) {
		$a = explode('.', $prop_name);
		if (count($a) == 2) {
			$scope = $a[0];
			$namespace = $a[1];
		}
		else {
			$scope = 'data';
			$namespace = $a[0];
		}

		$prop_info = $this->intel->visitor_property_info("$scope.$namespace");

		if (empty($prop_info)) {
			return FALSE;
		}
		$var = $this->getVar($scope, $namespace);

		foreach ($prop_info['variables'] AS $key => $default) {
			if (isset($values[$key])) {
				$var[$key] = $values[$key];
			}
		}
		if (!empty($options['source'])) {
			$var['_source'] = $options['source'];
		}
		$var['_updated'] = $this->intel->request_time;

		if (isset($prop_info['process callbacks'])) {
			$funcs = $prop_info['process callbacks'];
			if (!is_array($funcs)) {
				$funcs = array($funcs);
			}
			foreach ($funcs AS $func) {
        if (is_array($func) && (count($func) == 2)) {
          if (is_object($func[0])) {
            $func[0]->{$func[1]}($var, $prop_info, $this);
          }
          elseif (is_string($func[0]) && class_exists($func[0])) {
            call_user_func("{$func[0]}::{$func[1]}", $var, $prop_info, $this);
            //call_user_func_array("{$func[0]}::{$func[1]}", array($var, $prop_info, $this));
          }
        }
        else {
          //$func($var, $prop_info, $this);
					call_user_func($func, $var, $prop_info, $this);
        }

			}
		}

		// TODO prop history management
		/*
    $prop0 = $this->getProp($prop_name);

    $pkey = $prop_info['key'];

    if ($pkey) {
      // prop already set as primary
      if ($prop0[$pkey] == $prop[$pkey]) {
        return;
      }
    }
    */

		$this->setVar($scope, $namespace, '', $var);

    // process with special identifiers
    if ($prop_name == 'email') {
      if (empty($this->email) && !empty($values['@value'])) {
        $this->setIdentifier('email', $values['@value']);
        if (empty($this->contact_created)) {
          $this->setContactCreated($this->intel->request_time);
        }
      }
    }
    elseif (($prop_name == 'phone' || $prop_name == 'telephone') && !empty($values['@value']) ) {
      if (empty($this->phone)) {
        $this->setIdentifier('phone', $values['@value']);
        if (empty($this->contact_created)) {
          $this->setContactCreated($this->intel->request_time);
        }
      }
    }
    elseif ($prop_name == 'name' && !empty($values['@value'])) {
      if (empty($this->name)) {
        $this->setName($values['@value']);
      }
    }
	}
	/**
	 * TODO manage aliases
	 * @param $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

  public function getEmail($delta = '') {
    if (is_integer($delta)) {
      return !empty($this->identifiers['email'][$delta]) ? $this->identifiers['email'][$delta] : FALSE;
    }
    if (!empty($this->email)) {
      return $this->email;
    }
    if (!empty($this->identifiers['email'][0])) {
      return $this->identifiers['email'][0];
    }
    return FALSE;
  }

	public function setName($name) {
		$this->name = $name;
	}

	public function setUid($uid) {
		$this->uid = $uid;
	}

	public function setEid($eid) {
		$this->eid = $eid;
	}

	public function setVtk($vtk) {
		$this->vtk = $vtk;
		$this->vtkid = substr($this->vtk, 0, 20);
		$this->vtkc = substr($this->vtk, 20);
	}

	public function setUserId($userId) {
		$this->userId = $userId;
	}

	public function setContactCreated($time = null) {
		$this->contact_created = isset($time) ? $time : $this->intel->request_time;
	}

	public function setLastActivity($time = null) {
		$this->last_activity = isset($time) ? $time : $this->intel->request_time;
	}

	public static function extractVtk() {
		//Intel::includeLibraryFile('class.visitor.php');
    intel_include_library_file('class.visitor.php');
		return \LevelTen\Intel\ApiVisitor::extractVtk();
	}

	public static function extractUserId() {
    intel_include_library_file('class.visitor.php');
		return \LevelTen\Intel\ApiVisitor::extractUserId();
	}

	public static function extractCid() {
    intel_include_library_file('class.visitor.php');
		return \LevelTen\Intel\ApiVisitor::extractCid();
	}

	/**
	 * TODO build this to be more robust
	 * @param $type
	 * @param $value
	 */
	public function filterIdentifier($type, $value) {
		if ($type == 'email') {
			return filter_var($value, FILTER_VALIDATE_EMAIL);
		}
		return $value;
	}

  public function attachIdentifiers($identifiers) {
    $this->identifiers = $identifiers;
  }

	public function setIdentifier($type, $value, $is_primary = TRUE, $mergeDuplicate = TRUE) {
		if (!$value = $this->filterIdentifier($type, $value)) {
			return;
		}

		if (!isset($this->identifiers[$type])) {
			$this->identifiers[$type] = array();
		}
		// check if visitor with same identifier exists.
		// if so, merge duplicate visitor into this.
    if ($mergeDuplicate) {
			$idents = array(
				$type => $value,
			);
			$dup = intel_visitor_load_by_identifiers($idents);
			if (!empty($dup) && ($dup->vid != $this->vid)) {
				$merge_dir = '';
				$this_vid = $this->vid;
				$dup_vid = $dup->vid;
				$this->mergeDupVisitor($dup, $merge_dir);
        // if this has vid remove is_new status;
        if ($this->vid && !empty($this->is_new)) {
          unset($this->is_new);
        }
				// if dup has different vid, delete it and make sure merged data is saved
				if ($merge_dir) {
					if ($merge_dir == 'into_this') {
						intel_visitor_delete($dup_vid);
					}
					elseif ($merge_dir == 'into_dup') {
						intel_visitor_delete($this_vid);
					}
					$this->save();
				}
			}
		}

		// see if identifier already exists
		$existing_i = array_search($value, $this->identifiers[$type]);
		if ($is_primary && ($existing_i !== 0)) {
			if ($existing_i !== FALSE) {
				// remove existing element (it will be added at index 0)
				unset($this->identifiers[$type][$existing_i]);
				// reindex array
				$this->identifiers[$type] = array_values($this->identifiers[$type]);
			}
			array_unshift($this->identifiers[$type], $value);
			$this->$type = $value;
		}
		// if not primary and id does not already exists, add to end
		elseif ($existing_i === FALSE) {
			$this->identifiers[$type][] = $value;
		}
	}

	public function getIdentifiers($type) {
		return isset($this->identifiers[$type]) ? $this->identifiers[$type] : array();
	}

	public function deleteIdentifierValue($type, $value) {
		if (empty($this->identifiers[$type])) {
			return;
		}
		$ids = array();
		foreach ($this->identifiers[$type] as $i => $v) {
			if ($value != $v) {
				$ids[] = $v;
			}
		}
		$this->identifiers[$type] = $ids;
	}

	public function clearIdentifierType($type) {
		$this->identifiers[$type] = array();
	}

	public function mergeDupVisitor($dup, &$merge_dir = '') {
		// if this vid is not set, inherit the dup vid
		$to_vid = 0;
		$from_vid = 0;
		$merge_identifiers = array();
		// current visitor has not been saved yet
		if (empty($this->vid)) {
			$this->vid = $dup->vid;
			$merge_identifiers = $this->identifiers;
			$this->name = $dup->name;
			$this->identifiers = $dup->identifiers;
			$this->data = Intel_Df::drupal_array_merge_deep($dup->data, $this->data);
			$this->ext_data = Intel_Df::drupal_array_merge_deep($dup->ext_data, $this->ext_data);
		}
		// otherwise add dup vid for identifier processing
		else {
			// merge into first created vid
			if ($dup->vid < $this->vid) {
				$merge_dir = 'into_dup';
				$to_vid = $dup->vid;
				$from_vid = $this->vid;
				$merge_identifiers = $this->identifiers;
				$this->vid = $dup->vid;
				$this->name = $dup->name;
				$this->identifiers = $dup->identifiers;
				$this->data = Intel_Df::drupal_array_merge_deep($dup->data, $this->data);
				$this->ext_data = Intel_Df::drupal_array_merge_deep($dup->ext_data, $this->ext_data);
			}
			else {
				$merge_dir = 'into_this';
				$to_vid = $this->vid;
				$from_vid = $dup->vid;
				$merge_identifiers = $dup->identifiers;
				$this->data = Intel_Df::drupal_array_merge_deep($this->data, $dup->data);
				$this->ext_data = Intel_Df::drupal_array_merge_deep($this->ext_data, $dup->ext_data);
			}
		}

		// add unique identifiers from dup
		foreach ($merge_identifiers AS $type => $values) {
			foreach ($values AS $i => $value) {
				$existing_i = FALSE;
				if (!isset($this->identifiers[$type])) {
					$this->identifiers[$type] = array();
				}
				else {
					$existing_i = array_search($value, $this->identifiers[$type]);
				}
				if ($existing_i === FALSE) {
					$this->identifiers[$type][] = $value;
				}
			}
		}

		if (!$this->created || ($dup->created < $this->created)) {
			$this->created = $dup->created;
		}
		if (!$this->contact_created || ($dup->contact_created < $this->contact_created)) {
			$this->contact_created = $dup->contact_created;
		}
		if ($dup->updated > $this->updated) {
			$this->updated = $dup->updated;
		}
		if ($dup->last_activity > $this->last_activity) {
			$this->last_activity = $dup->last_activity;
		}
		if ($dup->data_updated > $this->data_updated) {
			$this->data_updated = $dup->data_updated;
		}
		if ($dup->ext_updated  > $this->ext_updated ) {
			$this->ext_updated = $dup->ext_updated;
		}

		// update form submission vids
		if ($from_vid) {
			global $wpdb;
			$data = array(
				'vid' => $to_vid,
			);
			$where = array(
				'vid' => $from_vid,
			);
			$wpdb->update($wpdb->prefix . 'intel_submission', $data, $where );
			// TODO WP
			/*
			// update form submission vids
			$query = db_update('intel_submission')
				->fields(array('vid' => $to_vid))
				->condition('vid', $from_vid)
				->execute();

			// update phone call vids
			$query = db_update('intel_phonecall')
				->fields(array('vid' => $to_vid))
				->condition('vid', $from_vid)
				->execute();
			*/
		}
	}



	public function location($format = 'country') {
		$location = $this->getVar('data', 'location');
		$out = '';
		if ($format == 'city, state, country') {
			$out = !empty($location['city']) ? $location['city'] : t('(not set)') . ', ';
			$out .= ', ' . !empty($location['region']) ? $location['region'] : t('(not set)');
			$out .= ', ' . !empty($location['country']) ? $location['country'] : t('(not set)');
		}
		elseif ($format == 'map') {
			$out = !empty($location['city']) ? $location['city'] : '(not set)';
			$out .= ', ' . (!empty($location['region']) ? $location['region'] : t('(not set)'));
			if (isset($location['metro']) && ($location['metro'] != '(not set)')) {
				$out .= ' (' . $location['metro'] . ')';
			}
			$out .= "<br />\n" . (!empty($location['country']) ? $location['country'] : t('(not set)'));
		}
		else {
			$out = !empty($location['country']) ? $location['country'] : t('(not set)');
		}
		return $out;
	}

  public static function build_content(&$entity, $view_mode = 'full', $langcode = NULL) {
    if (!isset($langcode)) {
      //$langcode = $GLOBALS['language_content']->language;
    }

    // Remove previously built content, if exists.
    $entity->content = array();

    self::build_profile_content_elements($entity);

    // Allow modules to change the view mode.
    $context = array(
      'entity_type' => 'intel_visitor',
      'entity' => $entity,
      'langcode' => $langcode,
    );
    // TODO implement hooks
    //drupal_alter('entity_view_mode', $view_mode, $context);

    // Build fields content.
    //field_attach_prepare_view('intel_visitor', array($entity->vid => $entity), $view_mode, $langcode);
    //entity_prepare_view('intel_visitor', array($entity->vid => $entity), $langcode);
    //$entity->content['fields'] = field_attach_view('intel_visitor', $entity, $view_mode, $langcode);


    // Populate $entity->content with a render() array.
    //module_invoke_all('intel_visitor_view', $entity, $view_mode, $langcode);
    //module_invoke_all('entity_view', $entity, 'intel_visitor', $view_mode, $langcode);

    // Make sure the current view mode is stored if no module has already
    // populated the related key.
    $entity->content += array('#view_mode' => $view_mode);
  }

  public static function build_profile_content_elements($entity) {
    $weight = 0;


    $entity->content['title'] = array(
      '#markup' => $entity->label(),
      '#region' => 'header',
    );
    $entity->content['subtitle'] = array(
      '#markup' => '', //$entity->getEmail(),
      '#region' => 'header',
    );
		$vars = array(
			'entity' => $entity,
			'image_variables' => array(
				'width' => 160,
				'height' => 160,
			),
			'avatar_options' => array(
				'size' => 160,
			),
		);
    $entity->content['picture'] = array(
      '#markup' => Intel_Df::theme('intel_visitor_picture', $vars),
      '#region' => 'header',
    );

    $entity->content['header_content'] = array(
      '#region' => 'header',
    );
    $entity->content['header_content']['social_links'] = array(
      '#markup' => Intel_Df::theme('intel_visitor_social_links', array('entity' => $entity)),
    );
    $entity->content['header_content']['description'] = array(
      '#markup' => Intel_Df::theme('intel_visitor_bio', array('entity' => $entity)),
    );

    $entity->content['location'] = array(
      '#markup' => Intel_Df::theme('intel_location_block',
				array(
					'title' => Intel_Df::t('Location'),
				  'entity' => $entity,
				)
			),
      '#region' => 'sidebar',
      '#weight' => $weight++,
    );
    $entity->content['browser_environment'] = array(
      '#markup' => Intel_Df::theme('intel_browser_environment_block',
				array(
					'title' => Intel_Df::t('Browser environment'),
				  'entity' => $entity,
			  )
			),
      '#region' => 'sidebar',
      '#weight' => $weight++,
    );

		if (intel_is_extended()) {
			$prop_info = intel()->visitor_property_info();
			$items = array();
			foreach ($prop_info as $k => $info) {
				$kt = $k;
				if (substr($k, 0, 5) == 'data.') {
					$kt = substr($k, 5);
				}

				$vars = array();
				$value = $entity->getProp($kt);
				if (!empty($value)) {
					$vars = array(
						'info' => $info,
						'value' => $value,
					);
					$ivars = array(
						'title' => $info['title'],
						'value' => Intel_Df::theme('intel_visitor_property', $vars),
					);
					$items[] = Intel_Df::theme('intel_visitor_profile_item', $ivars);
				}
			}
			if (count($items)) {
				$markup = '';
				foreach ($items AS $item) {
					$markup .= $item;
				}
				$entity->content['intel_visitor_properties'] = array(
					//'#markup' => Intel_Df::theme('intel_visitor_profile_block', array('title' => __('Properties', 'intel'), 'markup' => $markup)),
					'#markup' => Intel_Df::theme('intel_visitor_profile_block', array('title' => __('Properties', 'intel'), 'markup' => $markup)),
					'#weight' => $weight++,
				);
			}
		}

    $entity->content['visit_table'] = array(
      '#markup' => Intel_Df::theme('intel_visitor_visits_table', array('entity' => $entity, 'no_margin' => 1)),
      '#weight' => $weight++,
    );

    // TODO: clean this up and put into themeing functions

    $vdata = $entity->data;

		$items = array();

    $emailclicks = 0;
    if (0 && get_option('intel_track_emailclicks', INTL_TRACK_EMAILCLICKS_DEFAULT)) {
      $filter = array(
        'conditions' => array(
          array('c.vid', $entity->identifiers['vid'], 'IN'),
        ),
      );
      $result = intel_load_filtered_emailclick_result($filter);
      $rows = array();
      $calls = array();
      $clicks = array();
      while ($row = $result->fetchObject()) {
        $clicks[$row->cid] = $row;
      }
      if (!empty($clicks)) {
        uasort($clicks, function ($a, $b) {
          return ($a->clicked < $b->clicked) ? 1 : -1;
        }
        );
      }

      // TODO: move mailchimp info processing to hook and process in mailchimp module

      $email_info = array();
      foreach ($clicks AS $row) {
        $emaildesc = $row->eid;
        $ops = array();
        $ops[] = l(t('meta'), 'emailclick/' . $row->cid);
        $title = 'NA';

        if ($row->type == 'mailchimp') {
          if (!isset($email_info['mailchimp'])) {
            $email_info['mailchimp'] = array();
          }
          if (!isset($email_info['mailchimp'][$row->eid])) {
            $campaigns = intel_mailchimp_api_campaigns_list_by_campaign_id($row->eid);
            if (isset($campaigns[$row->eid])) {
              $email_info['mailchimp'][$row->eid] = $campaigns[$row->eid];
            }
          }
          if (isset($email_info['mailchimp'][$row->eid]['archive_url_long'])) {
            $link_options = array(
              'attributes' => array(
                'target' => 'mailchimp',
              ),
            );
            $emaildesc = Intel_Df::l( $campaigns[$row->eid]['title'], $campaigns[$row->eid]['archive_url_long'], $link_options);
          }
        }

        $row = array(
          format_date($row->clicked, 'medium'),
          $row->type,
          $emaildesc,
          implode(' ', $ops),
        );
        $rows[] = $row;
      }
      if (count($rows)) {
        $tvars = array();
        $tvars['rows'] = $rows;
        $emailclicks = count($rows);
        $tvars['header'] = array(
          t('Clicked'),
          t('Type'),
          t('Email'),
          t('Ops'),
        );
        $table = theme('table', $tvars);
        $entity->content['emailclicks_table'] = array(
          '#markup' => theme('intel_visitor_profile_block', array('title' => t('Email clicks'), 'markup' => $table)),
          '#weight' => $weight++,
        );
      }
    }

    $phonecalls = 0;
    if (0 && get_option('intel_track_phonecalls', INTL_TRACK_PHONECALLS_DEFAULT)) {
      $filter = array(
        'conditions' => array(
          array('c.vid', $entity->identifiers['vid'], 'IN'),
        ),
      );
      $result = intel_load_filtered_phonecall_result($filter);
      $rows = array();
      $calls = array();
      while ($row = $result->fetchObject()) {
        $calls[$row->cid] = $row;
      }
      uasort($calls, function ($a, $b) {
        return ($a->initiated < $b->initiated) ? 1 : -1;
      }
      );
      foreach ($calls AS $row) {
        $ops = array();
        $ops[] = l(t('meta'), 'phonecall/' . $row->cid);
        $title = 'NA';
        $rows[] = array(
          format_date($row->initiated, 'medium'),
          $row->type,
          $row->to_num,
          $row->from_num,
          implode(' ', $ops),
        );
      }
      if (count($rows)) {
        $tvars = array();
        $tvars['rows'] = $rows;
        $phonecalls = count($rows);
        $tvars['header'] = array(
          t('Call date'),
          t('Type'),
          t('To'),
          t('From'),
          t('Ops'),
        );
        $table = theme('table', $tvars);
        $entity->content['phonecalls_table'] = array(
          '#markup' => theme('intel_visitor_profile_block', array('title' => t('Phone calls'), 'markup' => $table)),
          '#weight' => $weight++,
        );
      }
    }

    $form_submissions = 0;
		$vars = array(
			'vid' => $entity->get_id(),
		);

		//$form_type_info = array();
		//$form_type_info = apply_filters('intel_form_type_info', $form_type_info);

		$system_info = intel()->system_info();

		$form_type_info = intel()->form_type_info();

		$form_type_form_info = intel()->form_type_form_info();

		$subs = intel()->get_entity_controller('intel_submission')->loadByVars($vars);

		if (!empty($subs) && is_array($subs)) {
			uasort($subs, function ($a, $b) {
				return ($a->submitted < $b->submitted) ? 1 : -1;
			});
		}
		else {
			$subs = array();
		}

		$field_values = array();
    foreach ($subs AS $row) {
      $ops = array();
      //$ops[] = Intel_Df::l(__('meta', 'intel'), 'submission/' . $row->sid);
      $title = 'NA';
			$form_type = $row->type;



			if (!empty($form_type_info[$row->type]['title'])) {
				$form_type = $form_type_info[$row->type]['title'];
			}

			if (!empty($form_type_form_info[$row->type][$row->fid]['title'])) {
				$title = $form_type_form_info[$row->type][$row->fid]['title'];
			}

		  if (!empty($form_type_info[$row->type]['submission_data_callback'])) {
				$sub_data = call_user_func($form_type_info[$row->type]['submission_data_callback'], $row->fid, $row->fsid);
				if (!empty($form_type_info[$row->type]['title'])) {
					$form_type = $form_type_info[$row->type]['title'];
				}
				if (!empty($sub_data['form_title'])) {
					$title = $sub_data['form_title'];
				}
				if (!empty($sub_data['submission_data_url'])) {
					$ops[] = Intel_Df::l(__('data', 'intel'), $sub_data['submission_data_url']);
				}
				if (!empty($sub_data['field_values'])) {
					foreach ($sub_data['field_values'] as $k => $v) {
						$field_values[$k] = array(
							'title' => !empty($sub_data['field_titles'][$k]) ? $sub_data['field_titles'][$k] : $k,
							'value' => $v,
						);
					}
				}

			}
		  elseif ($row->type == 'intel_form') {
				if (!empty($row->fid)) {
					$title = ucwords(str_replace('_', ' ', $row->fid));
				}
			}
      elseif ($row->type == 'disqus_comment') {
        $title = t('Comment');
        $a = explode('#', substr($row->details_url, 1));
        $options = array(
          'fragment' => isset($a[1]) ? $a[1] : '',
        );
        $ops[] = l(t('data'), $a[0], $options);
      }
      elseif ($row->type == 'hubspot') {
        $form_name = intel_hubspot_get_form_name($row->fid);
        if ($form_name) {
          $title = $form_name;
        }
      }
			else {

			}
      $rows[] = array(
        Intel_Df::format_date($row->submitted, 'medium'),
        $form_type,
        $title,
        implode(' ', $ops),
      );

    }

    if (!empty($rows) && count($rows)) {
      $tvars = array();
      $tvars['rows'] = $rows;
      $form_submissions = count($rows);
      $tvars['header'] = array(
        __('Submission date', 'intel'),
				__('Type', 'intel'),
				__('Form', 'intel'),
				__('Ops', 'intel'),
      );
      $table = Intel_Df::theme('table', $tvars);
      $entity->content['submissions_table'] = array(
        '#markup' => Intel_Df::theme('intel_visitor_profile_block', array('title' => __('Form submissions', 'intel'), 'markup' => $table, 'no_margin' => 1)),
        '#weight' => $weight++,
      );

      if (count($field_values)) {
        $markup = '';
        foreach ($field_values AS $fv) {
          $markup .= Intel_Df::theme('intel_visitor_profile_item', $fv);
        }
        $entity->content['submissions_fields_table'] = array(
          '#markup' => Intel_Df::theme('intel_visitor_profile_block', array('title' => __('Form submission values', 'intel'), 'markup' => $markup)),
          '#weight' => $weight++,
        );
      }
    }



    $stats = array();
    if (!empty($vdata['analytics_visits'])) {
      $visits = $vdata['analytics_visits']['_totals'];
      if (!empty($visits['score'])) {
        $stats[] = array(
          'value' => number_format($visits['score'], 2),
          'title' => __('value score', 'intel'),
          'class' => 'score',
        );
      }
      if (isset($visits['entrance']['entrances'])) {
        $stats[] = array(
          'value' => number_format($visits['entrance']['entrances']),
          'title' => __('visits', 'intel'),
          'class' => 'visits',
        );
      }
      if (isset($visits['entrance']['pageviews'])) {
        $stats[] = array(
          'value' => number_format($visits['entrance']['pageviews']),
          'title' => __('page views', 'intel'),
        );
      }
      if (isset($visits['entrance']['timeOnSite'])) {
        $value = ($visits['entrance']['timeOnSite'] > 3600) ? Date('G:m:s', $visits['entrance']['timeOnSite']) : Date('m:s', $visits['entrance']['timeOnSite']);
        $stats[] = array(
          'value' => $value,
          'title' => __('time on site', 'intel'),
        );
      }
    }
    $stats[] = array(
      'value' => $form_submissions,
      'title' => __('form submissions', 'intel'),
    );

    if (get_option('intel_track_emailclicks', INTEL_TRACK_EMAILCLICKS_DEFAULT)) {
      $stats[] = array(
        'value' => $emailclicks,
        'title' => __('email clicks', 'intel'),
      );
    }

    if (get_option('intel_track_phonecalls', INTEL_TRACK_PHONECALLS_DEFAULT)) {
      $stats[] = array(
        'value' => $phonecalls,
        'title' => __('phone calls', 'intel'),
      );
    }

    if (isset($vdata['klout']) && isset($vdata['klout']['score'])) {
      $stats[] = array(
        'value' => number_format($vdata['klout']['score']),
        'title' => __('Klout score', 'intel'),
      );
    }
    if (isset($vdata['twitter']) && isset($vdata['twitter']['followers'])) {
      $stats[] = array(
        'value' => number_format($vdata['twitter']['followers']),
        'title' => __('Twitter followers', 'intel'),
      );
    }
    $markup = '';
    foreach ($stats AS $stat) {
      $markup .= Intel_Df::theme('intel_visitor_summary_item', $stat);
    }
    $entity->content['summary'] = array(
      '#markup' => $markup,
      '#region' => 'summary',
    );
  }

	public function __get($name) {
		// unserialize data if needed
		if (($name == 'data') && (is_string($this->data))) {
			$this->data = unserialize($this->data);
		}
		elseif (($name == 'ext_data') && (is_string($this->ext_data))) {
			$this->ext_data = unserialize($this->ext_data);
		}
		// return property if exists
		if (isset($this->$name)) {
			return $this->$name;
		}
		return null;
	}

	public function __isset($name) {
		$v = $this->__get($name);
		return isset($v);
	}

	public function __set($name, $value) {
		return $this->$name = $value;
	}

	public function __unset($name) {
		if (isset($this->$name)) {
			unset($this->$name);
		}
	}

	public function __call($method, $args) {
		if (method_exists($this->apiVisitor, $method)) {
			return $this->apiVisitor->$method($args);
		}
	}

	public function __toString() {
		return $this->identifier();
	}
}
