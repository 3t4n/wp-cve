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
class IntelLogController extends IntelEntityController  {

	public function __construct($entityType, $entity_info) {
		parent::__construct($entityType, $entity_info);

		// if network installed, use the base table
    parent::set_use_base_prefix(intel()->is_network_active);
	}

  public function save($entity) {

    $entity->type = substr($entity->type, 0, 64);
	  $entity->link = substr($entity->link, 0, 255);
    $entity->hostname = substr($entity->hostname, 0, 128);

    parent::save($entity);

    return $entity;
  }
}
