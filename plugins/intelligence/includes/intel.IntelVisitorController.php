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
 * @package    Intl
 * @subpackage Intl/includes
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
 * @package    Intl
 * @subpackage Intl/includes
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class IntelVisitorController extends IntelEntityController  {

	public $idType = '';

	/*
	public function __construct($entityClass = 'intel_visitor') {
		parent::__construct($entityClass);
	}
	*/

	public function setIdType($idType) {
		if ($idType) {
			$this->idType = $idType;
		}
	}

	public function getIdType() {
		return $this->idType;
		//return $this->idKey;
	}

	public static function determineIdType($id) {
		$idType = 'vid';
		if (!$id || ($id == 'user')) {
			$idType = 'vtk';
		}
		elseif (is_string($id) && strlen($id) >= 20) {
			if (strlen($id) == 20) {
				$idType = 'vtkid';
			}
			else {
				$idType = 'vtk';
			}
		}
		return $idType;
	}


	/**
	 * Create and return a new intel_visitor entity.
	 */
	public function create(array $values = array()) {
		$values['data'] = array();
		$values['ext_data'] = array();
		$values['identifiers'] = array();
		$values['properties'] = array();

		if (isset($values['id'])) {
			$vtk = '';
			$gacid = '';
			if ($values['id'] == 'user') {
				$vtk = IntelVisitor::extractVtk();
				$gacid = IntelVisitor::extractCid();

				if ($uid = get_current_user_id()) {
					$values['uid'] = $uid;
					$values['identifiers']['uid'] = array();
					$values['identifiers']['uid'][] = $uid;
				}
			}
			elseif (is_string($values['id']) && strlen($values['id']) >= 20) {
				$vtk = $values['id'];
			}

			if ($vtk) {
				$values['vtk'] = $vtk;
				$values['identifiers']['vtk'] = array();
				$values['identifiers']['vtk'][] = $vtk;
			}
			if ($gacid) {
				$values['gacid'] = $gacid;
				$values['identifiers']['gacid'] = array();
				$values['identifiers']['gacid'][] = $gacid;
			}
      if ($vtk && $gacid) {
        if (empty($values['data']['gacid_log'])) {
          $values['data']['gacid_log'] = array();
        }
        if (empty($values['data']['gacid_log'][$gacid])) {
          $values['data']['gacid_log'][".$gacid"] = array();
        }
        $values['data']['gacid_log'][".$gacid"]['_created'] = REQUEST_TIME;
        $values['data']['gacid_log'][".$gacid"]['gacid'] = $gacid;
        $values['data']['gacid_log'][".$gacid"]['vtk'] = $vtk;
      }
		}


		$entity = parent::create($values);
		$entity->created = time();

		return $entity;
	}

	/**
	 * Saves the custom fields using drupal_write_record()
	 */
	public function save($entity) {
		if (!isset($entity->data)) {
			$entity->data_updated = 0;
			$entity->data = array();
		}
		if (!isset($entity->ext_data)) {
			$entity->ext_updated = 0;
			$entity->ext_data = array();
		}

		// If our entity has no eid, then we need to give it a
		// time of creation.
		if (empty($entity->vid)) {
			$entity->created = time();
		}

		if (empty($entity->contact_created) && !empty($entity->email)) {
			$entity->contact_created = time();
		}

		$entity = parent::save($entity);

		$this->saveIdentifiers($entity);

		return $entity;
	}

	public function load($ids, $conditions = array(), $reset = FALSE) {
		global $wpdb;

		if (!is_array($ids)) {
			$ids = array($ids);
		}

		$idType = '';
		if ($this->idType) {
		  $idType = $this->idType;
		}
		else {
			foreach ($ids as $k => $id) {
				// if id = user, load the current user using the vtk cookie value
				if (!$id || ($id == 'user')) {
					intel_include_library_file('class.visitor.php');
					$ids[$k] = \LevelTen\Intel\ApiVisitor::extractVtk();
					$idType = 'vtk';
				}
				// check if id is vtk or vtkid
				elseif (is_string($id) && (strlen($id) >= 20)) {
					if (strlen($id) == 20) {
						$idType = 'vtkid';
					}
					else {
						$idType = 'vtk';
					}
				}
			}
			if (!$idType) {
				$idType = 'vid';
			}
		}

		$sql = "
		  SELECT e.*
		  FROM {$wpdb->prefix}{$this->base_table} AS e
		";
		$data = array();
		$id_cnt = count($ids);
		$id_placeholder = is_numeric($ids[0]) ? '%d' : '%s';
		$id_placeholders = array_fill(0, $id_cnt, $id_placeholder);
		$ids_query = implode(', ', $id_placeholders);
		if ($idType == 'vid') {
			//$ids_query = implode(', ', $ids);
			$sql .= "WHERE {$this->key_id} IN ( $ids_query )";
			$data = $ids;
		}
		elseif ($idType == 'vtkid') {
			$sql .= "INNER JOIN {$wpdb->prefix}intel_visitor_identifier AS i ON e.vid = i.vid\n";
			$sql .= "WHERE i.type = 'vtk' AND ( ";
			$cnt = 0;
			foreach ($ids as $id) {
				if ($cnt) {
					$sql .= " OR ";
				}
				$cnt++;
				$sql .= "i.value LIKE %s";
				$data[] = $id . '%';
			}
			$sql .= ' )';
		}
		elseif ($idType == 'vtk') {
			//$ids_query = "'" . implode("', ", $ids) . "'";
			$sql .= "INNER JOIN {$wpdb->prefix}intel_visitor_identifier AS i ON e.vid = i.vid\n";
			$sql .= "WHERE i.type = '$idType' AND i.value IN ( $ids_query )";
			$data = $ids;
		}

		$results = $wpdb->get_results( $wpdb->prepare($sql, $data) );

		$entities = array();
		if (empty($results[0])) {
			return $entities;
		}

		foreach ($results as $row) {
			$entity = new $this->entity_class((array)$row, $this);
			$entities[$entity->id] = $entity;
		}

		return $entities;
	}

	public function loadOne($id, $conditions = array(), $reset = FALSE) {
		$entities = self::load(array($id));
		if (empty($entities)) {
			return FALSE;
		}
		return array_shift($entities);
	}

	function loadByIdentifiers($identifiers, $reset = FALSE) {
		global $wpdb;

		$sql = "
		  SELECT DISTINCT vid
		  FROM {$wpdb->prefix}intel_visitor_identifier AS i
		  WHERE
		";
		$cnt = 0;
		$data = array();
		foreach ($identifiers AS $type => $value) {
			if ($cnt) {
				$sql .= ' OR ';
			}
			$sql .= "(i.type = %s AND i.value = %s)\n";
			$data[] = $type;
			$data[] = $value;
		}

		$results = $wpdb->get_results( $wpdb->prepare($sql, $data) );

		if (empty($results[0]->vid)) {
			return FALSE;
		}
		$ids = array();
		foreach ($results as $row) {
			$ids[] = $row->vid;
		}
		return self::load($ids);
	}

	public function loadIdentifiers($entity, $type = '') {
		global $wpdb;

		$identifiers = array();
		$data = array();
		$sql = "
		  SELECT *
		  FROM {$wpdb->prefix}intel_visitor_identifier AS i
		  WHERE i.vid = %d
		";
		$data[] = $entity->vid;
		if ($type) {
			$sql .= " AND i.type = %s";
			$data[] = $type;
		}

		$results = $wpdb->get_results( $wpdb->prepare($sql, $data) );

		foreach ($results as $row) {
			if (!isset($identifiers[$row->type])) {
				$identifiers[$row->type] = array();
			}
			$identifiers[$row->type][] = $row->value;
		}

		// add vid to identifiers if not already attached
		if (!isset($identifiers['vid']) || array_search($entity->vid, $identifiers['vid']) === FALSE) {
			if (!isset($identifiers['vid'])) {
				$identifiers['vid'] = array();
			}
			$identifiers['vid'][] = $entity->vid;
		}
		return $identifiers;
	}

	public function saveIdentifiers($entity) {
		global $wpdb;

		$existing = $this->loadIdentifiers($entity);
		if ($entity->identifiers == $existing) {
			return FALSE;
		}

		$this->deleteIdentifiers($entity->vid);
		$format = array(
			'%d',
			'%s',
			'%s',
			'%s',
		);
		foreach ($entity->identifiers AS $type => $values) {
			// don't save vid identifier
			if ($type == 'vid') {
				continue;
			}
			foreach ($values AS $delta => $value) {
				$data = array(
					'vid' => $entity->vid,
					'type' => $type,
					'delta' => $delta,
					'value' => $value,
				);
				$wpdb->insert($wpdb->prefix . 'intel_visitor_identifier', $data, $format );
				/*
				$query = db_insert('intel_visitor_identifier')
					->fields($fields);
				$query->execute();
				*/
			}
		}
		return $entity->identifiers;
	}

	public function deleteIdentifiers($vid) {
		global $wpdb;
		$wpdb->delete($wpdb->prefix . 'intel_visitor_identifier', array('vid' => $vid), array( '%d'));
	}

	/**
	 * Delete a single entity.
	 *
	 * Really a convenience function for delete_multiple().
	 */
	public function deleteOne($vid) {
		// hack to solve issue which entity api calling delete with $vid as array
		parent::deleteOne($vid);
		self::deleteIdentifiers($vid);
	}

	/**
	 * Delete one or more intel_visitor entities.
	 *
	 * Deletion is unfortunately not supported in the base
	 * DrupalDefaultEntityController class.
	 *
	 * @param $ids
	 *   An array of entity IDs or a single numeric ID.
	 */
	public function delete($vids) {
		if (!is_array($vids)) {
			$vids = array($vids);
		}
		// delete intel_visitor records
		parent::delete($vids);
		foreach ($vids as $vid) {
			self::deleteIdentifiers($vid);
		}
	}
}
