<?php
namespace QuadLayers\QLWAPP\Entities;

use QuadLayers\WP_Orm\Entity\SingleEntity;
use QuadLayers\QLWAPP\Services\Entity_Options;

class Display extends SingleEntity {
	public $devices;
	public $entries;
	public $taxonomies;
	public $target;


	public function __construct() {

		$entity_options = Entity_Options::instance();

		$args = $entity_options->get_args();

		$this->devices    = $args['devices'];
		$this->entries    = $args['entries'];
		$this->taxonomies = $args['taxonomies'];
		$this->target     = $args['target'];
	}
}
