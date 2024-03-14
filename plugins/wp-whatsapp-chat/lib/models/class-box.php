<?php
namespace QuadLayers\QLWAPP\Models;

use QuadLayers\QLWAPP\Entities\Box as Box_Entity;

use QuadLayers\WP_Orm\Builder\SingleRepositoryBuilder;

class Box {

	protected static $instance;
	protected $repository;

	public function __construct() {
		add_filter( 'sanitize_option_qlwapp_box', 'wp_unslash' );
		$builder = ( new SingleRepositoryBuilder() )
		->setTable( 'qlwapp_box' )
		->setEntity( Box_Entity::class );

		$this->repository = $builder->getRepository();
	}

	public function get_table() {
		return $this->repository->getTable();
	}

	public function get() {
		$entity = $this->repository->find();
		$result = null;

		if ( $entity ) {
			$result = $entity->getProperties();
		} else {
			$admin  = new Box_Entity();
			$result = $admin->getProperties();
		}

		if ( ! is_admin() ) {
			$result['header'] = qlwapp_replacements_vars( $result['header'] );
			$result['footer'] = qlwapp_replacements_vars( $result['footer'] );
		}

		return $result;
	}

	public function delete_all() {
		return $this->repository->delete();
	}

	public function save( $data ) {
		$entity = $this->repository->create( $this->sanitize( $data ) );

		if ( $entity ) {
			return true;
		}
	}

	public function sanitize( $settings ) {
		if ( isset( $settings['header'] ) ) {
			$settings['header'] = wp_kses_post( $settings['header'] );
		}
		if ( isset( $settings['auto_open'] ) ) {
			$settings['auto_open'] = wp_kses_post( $settings['auto_open'] );
		}
		if ( isset( $settings['lazy_load'] ) ) {
			$settings['lazy_load'] = wp_kses_post( $settings['lazy_load'] );
		}
		if ( isset( $settings['auto_delay_open'] ) ) {
			$settings['auto_delay_open'] = wp_kses_post( $settings['auto_delay_open'] );
		}
		if ( isset( $settings['footer'] ) ) {
			$settings['footer'] = wp_kses_post( $settings['footer'] );
		}

		return $settings;
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
