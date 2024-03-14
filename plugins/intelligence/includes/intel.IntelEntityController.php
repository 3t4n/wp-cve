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
class IntelEntityController {

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	public $entity_type;
	public $entity_class;
	public $entity_info;
	public $base_table;
	public $key_id;
	public $fields;
	public $use_base_prefix;


	public function __construct($entityType, $entity_info) {
		$this->entity_type = $entityType;
		$this->entity_info = $entity_info;
		$this->entity_class = !empty($entity_info['entity class']) ? $entity_info['entity class'] : 'IntelEntity';
		$this->base_table = $entity_info['base table'];

		$this->fields = $entity_info['fields'];
		$this->key_id = $entity_info['entity keys']['id'];
    $this->use_base_prefix = FALSE;
	}

	public function create(array $values = array()) {
    $values += array('is_new' => TRUE);
		if (class_exists($this->entity_class)) {
			$entity = new $this->entity_class($values, $this);
		}
		else {
			$entity = new IntelEntity($values, $this);
		}

		return $entity;
	}

  /**
   * Implements EntityAPIControllerInterface.
   *
   * @return
   *   A serialized string in JSON format suitable for the import() method.
   */
  public function export($entity, $prefix = '') {
    $vars = get_object_vars($entity);
    unset($vars['is_new']);
    return entity_var_json_export($vars, $prefix);
  }

  /**
   * Export a variable in pretty formatted JSON.
   */
  function entity_var_json_export($var, $prefix = '') {
    if (is_array($var) && $var) {
      // Defines whether we use a JSON array or object.
      $use_array = ($var == array_values($var));
      $output = $use_array ? "[" : "{";

      foreach ($var as $key => $value) {
        if ($use_array) {
          $values[] = entity_var_json_export($value, '  ');
        }
        else {
          $values[] = entity_var_json_export((string) $key, '  ') . ' : ' . entity_var_json_export($value, '  ');
        }
      }
      // Use several lines for long content. However for objects with a single
      // entry keep the key in the first line.
      if (strlen($content = implode(', ', $values)) > 70 && ($use_array || count($values) > 1)) {
        $output .= "\n  " . implode(",\n  ", $values) . "\n";
      }
      elseif (strpos($content, "\n") !== FALSE) {
        $output .= " " . $content . "\n";
      }
      else {
        $output .= " " . $content . ' ';
      }
      $output .= $use_array ? ']' : '}';
    }
    else {
      //$output = Intel_Df::drupal_json_encode($var);
      $output = json_encode($var);
    }

    if ($prefix) {
      $output = str_replace("\n", "\n$prefix", $output);
    }
    return $output;
  }

	public function get_fields() {
		return $this->fields;
	}

	public function get_key_id() {
		return $this->key_id;
	}

	public function save($entity) {
		global $wpdb;

		$data = array();
		$format = array();
		$data = $this->fields;

		if (empty($entity->{$this->key_id})) {
			array_shift($data);
		}
		$i = 0;
		foreach ($data as $k => $v) {
			$data[$k] = isset($entity->{$k}) ? $entity->{$k} : $v;
			$format[$i] = '%s';
			if (is_array($v) || is_object($v)) {
				$data[$k] = serialize($data[$k]);
			}
			elseif(is_integer($v)) {
				$format[$i] = '%d';
			}
			elseif (is_float($v)) {
				$format[$i] = '%f';
			}
			$i++;
		}

		$wpdb_prefix = ($this->use_base_prefix) ? $wpdb->base_prefix : $wpdb->prefix;

		if (empty($data[$this->key_id])) {
			$wpdb->insert($wpdb_prefix . $this->base_table, $data, $format );
			$entity->{$this->key_id} = $wpdb->insert_id;
		}
		else {
			$wpdb->replace($wpdb_prefix . $this->base_table, $data, $format);
		}
		return $entity;
	}

	public function load($ids, $conditions = array(), $reset = FALSE) {
		global $wpdb;

		if (!is_array($ids)) {
			$ids = array($ids);
		}
		$ids_query = implode(', ', $ids);
		$data = $ids;
		$id_cnt = count($ids);
		$id_placeholder = is_numeric($ids[0]) ? '%d' : '%s';
		$id_placeholders = array_fill(0, $id_cnt, $id_placeholder);
		$ids_query = implode(', ', $id_placeholders);

    $wpdb_prefix = ($this->use_base_prefix) ? $wpdb->base_prefix : $wpdb->prefix;

		$sql = "
		  SELECT *
		  FROM {$wpdb_prefix}{$this->base_table}
		  WHERE {$this->key_id} IN ( $ids_query )
		";

		$results = $wpdb->get_results( $wpdb->prepare($sql, $data) );

		if (empty($results[0])) {
			return FALSE;
		}
		$entities = array();
		foreach ($results as $row) {
			$entity = new $this->entity_class((array)$row, $this);
			$entities[$entity->id] = $entity;
		}
		return $entities;
	}

	public function loadOne($id, $conditions = array(), $reset = FALSE) {
		$entities = self::load(array($id), $conditions, $reset);
		if (empty($entities)) {
			return FALSE;
		}
		return array_shift($entities);
	}

	public function loadByVars($vars, $select_options = array(), $construct_entity = 1) {
		global $wpdb;

    $wpdb_prefix = ($this->use_base_prefix) ? $wpdb->base_prefix : $wpdb->prefix;

		$sql = "
		  SELECT *
		  FROM {$wpdb_prefix}{$this->base_table}
		";
		if (!empty($vars)) {
			$sql .= "\nWHERE\n";
			$where_cnt = 0;
			$data = array();
			foreach ($vars as $k => $v) {
				if ($where_cnt) {
					$sql .= ' AND ';
				}
				$where_cnt++;
				if (is_array($v)) {
					if (count($v) == 3) {
						$d0 = $d1 = (is_string($v[1])) ? '"' : '';
						$sql .= $v[0] . ' ' . $v[2] . ' ';
						if (strtolower($v[2]) == 'in') {
							if (!is_array($v[1])) {
								$v[1] = array($v[1]);
							}
							$placeholder = is_numeric($v[1]) ? '%d' : '%s';
							$placeholders = array_fill(0, count($v[1]), $placeholder);
							$vars_query = implode(', ', $placeholders);
							$sql .= "( $vars_query )";
							$data = array_merge($data, $v[1]);
						}
						else {
							$sql .= ((is_string($v[1])) ? '%s' : '%d');
						  $data[] = $v[1];
						}
					}
					elseif (count($v) == 2) {
						$sql .= $v[0] . ' = ' . ((is_string($v[1])) ? '%s' : '%d') . "\n";
						$data[] = $v[1];
					}
				}
				else {
					$sql .= "$k = " . ((is_string($v)) ? '%s' : '%d') . "\n";
					$data[] = $v;
				}
			}
		}

		$results = $wpdb->get_results( $wpdb->prepare($sql, $data) );

		if (empty($results[0])) {
			return FALSE;
		}
		if (!$construct_entity) {
			return $results;
		}

		$entities = array();
		foreach ($results as $row) {
			$entity = new $this->entity_class((array)$row, $this);
			$entities[$row->{$this->key_id}] = $entity;
		}
		return $entities;
	}

  public function loadByFilter($filter = array(), $options = array(), $header = array(), $limit = 100, $offset = NULL) {
    global $wpdb;

    $wpdb_prefix = ($this->use_base_prefix) ? $wpdb->base_prefix : $wpdb->prefix;

    $sql = "
		  SELECT *
		  FROM {$wpdb_prefix}{$this->base_table}
		";
    $data = array();

    if (!empty($filter['where'])) {
      $where = $this->processWhere($filter['where'], $data);
      if ($where) {
        $sql .= "\n";
        $sql .= "WHERE\n";
        $sql .= $where;
      }
    }
    if (!empty($filter['conditions'])) {
      foreach ($filter['conditions'] AS $condition) {
        if (count($condition) == 3) {
          $query->condition($condition[0], $condition[1], $condition[2]);
        }
        else {
          $query->condition($condition[0], $condition[1]);
        }
      }
    }

    if ($options['order_by']) {
      $sql .= "\n";
      $sql .= "ORDER BY {$options['order_by']}\n";
    }

    $sql .= "\n";
    $sql .= "LIMIT %d";
    $data[] = $limit;
    if ($offset) {
      $sql .= " OFFSET %d";
      $data[] = $offset;
    }

    $results = $wpdb->get_results( $wpdb->prepare($sql, $data) );

    return $results;
  }

	public function processWhere($vars, &$data = array()) {
	  $sql = '';
    if (!empty($vars)) {
      $where_cnt = 0;
      foreach ($vars as $k => $v) {
        if ($where_cnt) {
          $sql .= ' AND ';
        }
        $where_cnt++;
        if (is_array($v)) {
          if (count($v) == 3) {
            $d0 = $d1 = (is_string($v[1])) ? '"' : '';
            $sql .= $v[0] . ' ' . $v[2] . ' ';
            if (strtolower($v[2]) == 'in') {
              if (!is_array($v[1])) {
                $v[1] = array($v[1]);
              }
              $placeholder = is_numeric($v[1]) ? '%d' : '%s';
              $placeholders = array_fill(0, count($v[1]), $placeholder);
              $vars_query = implode(', ', $placeholders);
              $sql .= "( $vars_query )";
              $data = array_merge($data, $v[1]);
            }
            else {
              $sql .= ((is_string($v[1])) ? '%s' : '%d');
              $data[] = $v[1];
            }
          }
          elseif (count($v) == 2) {
            $sql .= $v[0] . ' = ' . ((is_string($v[1])) ? '%s' : '%d') . "\n";
            $data[] = $v[1];
          }
        }
        else {
          $sql .= "$k = " . ((is_string($v)) ? '%s' : '%d') . "\n";
          $data[] = $v;
        }
      }
    }

	  return $sql;
  }

  public function deleteOne($id) {
		global $wpdb;

    $wpdb_prefix = ($this->use_base_prefix) ? $wpdb->base_prefix : $wpdb->prefix;

		$wpdb->delete( $wpdb_prefix . $this->base_table, array( $this->key_id => $id ) );
  }

	public function delete($ids) {
		if (!is_array($ids)) {
			$ids = array($ids);
		}
		foreach ($ids as $id) {
			self::deleteOne($id);
		}
	}

  public function set_use_base_prefix($value) {
    $this->use_base_prefix = $value;
  }

  public static function syncData($entity, $options = array()) {
		if (!empty($_GET['debug'])) {
			intel_d('entity0');//
			intel_d($entity);//
		}

		$entity_type = $entity->entity_type;
		if (strpos($entity_type, 'intel_') === 0) {
			$entity_type = substr($entity_type, 6);
		}

		// initial data gathering stage
		$entity = apply_filters('intel_sync_' . $entity_type, $entity, $options);

		// alter initial data gathering
		$entity = apply_filters('intel_sync_' . $entity_type . '_alter', $entity, $options);

		// data presave stage
		$entity = apply_filters('intel_sync_' . $entity_type . '_presave', $entity, $options);

		$statuses = $entity->getSyncProcessStatus();
		$synced = intel()->time();
		foreach ($statuses as $k => $v) {
			if (!$v) {
				$synced = 0;
				break;
			}
		}
		$entity->setSynced($synced);

		$entity->save();

		// data save stage
		do_action('intel_sync_' . $entity_type . '_save', $entity, $options);

		return $entity;
	}

	public static function get_class_from_entity_type($entity_type) {
		return str_replace(' ', '_', (ucwords( str_replace('_', ' ', $entity_type) ) ));
	}


}
