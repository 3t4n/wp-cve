<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Export extends Base_Model {
	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * @return null|Export
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param \WP_REST_Request $request
	 */
	public function execute( \WP_REST_Request $request ) {
		$data = $request->get_params();

		$template = Model_Templates::instance()->getById( $data['id'] );

		if ( $template ) {
			$pack = @json_decode( $template->pack );

			$templateItems             = new \stdClass();
			$templateItems->zone       = new \stdClass();
			$templateItems->containers = new \stdClass();
			$templateItems->columns    = new \stdClass();
			$templateItems->elements   = new \stdClass();
			$templateItems->group      = new \stdClass();
			$templateItems->fonts      = new \stdClass();

			if ( isset( $pack->containers ) ) {
				foreach ( $pack->containers as $container ) {
					$templateItems->containers->{$container->uuid} = $container;
				}
			}

			if ( isset( $pack->columns ) ) {
				foreach ( $pack->columns as $column ) {
					$templateItems->columns->{$column->uuid} = $column;
				}
			}

			if ( isset( $pack->elements ) ) {
				foreach ( $pack->elements as $element ) {
					$templateItems->elements->{$element->uuid} = $element;
				}
			}

			$templateItems->zone->{$pack->zone->uuid}  = $pack->zone;
			$templateItems->group->{$pack->zone->uuid} = $pack->group;

			if ( isset( $pack->fonts ) ) {
				$templateItems->fonts = $pack->fonts;
			}

			$pack = [
				'zones'      => $templateItems->zone,
				'containers' => $templateItems->containers,
				'columns'    => $templateItems->columns,
				'elements'   => $templateItems->elements,
				'groups'     => $templateItems->group,
				'fonts'      => $templateItems->fonts,
				'url_root'   => site_url()
			];

			return $this->response( self::STATUS_OK, [ json_encode( $pack ) ] );
		}

		return $this->response( self::STATUS_FAILED );
	}

	public function cleanExport( \WP_REST_Request $request ) {
		$data = $request->get_params();

		if ( ! isset( $data['zones'] ) ||
		     ! isset( $data['containers'] ) ||
		     ! isset( $data['columns'] ) ||
		     ! isset( $data['elements'] ) ||
		     ! isset( $data['groups'] ) ||
		     ! isset( $data['fonts'] ) ) {
			return $this->response( self::STATUS_FAILED );
		}

		$exportData             = new \stdClass();
		$exportData->zones      = new \stdClass();
		$exportData->containers = new \stdClass();
		$exportData->columns    = new \stdClass();
		$exportData->elements   = new \stdClass();
		$exportData->groups     = new \stdClass();
		$exportData->fonts      = new \stdClass();

		foreach ( @json_decode( $data['zones'] ) as $zone ) {
			$exportData->zones->{$zone->uuid} = $zone;
		}

		foreach ( @json_decode( $data['containers'] ) as $container ) {
			$exportData->containers->{$container->uuid} = $this->clearTrash( $container );
		}

		foreach ( @json_decode( $data['columns'] ) as $column ) {
			$exportData->columns->{$column->uuid} = $this->clearTrash( $column );
		}

		foreach ( @json_decode( $data['elements'] ) as $element ) {
			$exportData->elements->{$element->uuid} = $this->clearTrash( $element );
		}

		foreach ( @json_decode( $data['groups'] ) as $uuid => $group ) {
			$exportData->groups->{$uuid} = $group;
		}

		$exportData->fonts = @json_decode( $data['fonts'] );

		$pack = [
			'zones'      => $exportData->zones,
			'containers' => $exportData->containers,
			'columns'    => $exportData->columns,
			'elements'   => $exportData->elements,
			'groups'     => $exportData->groups,
			'fonts'      => $exportData->fonts,
			'url_root'   => site_url()
		];

		return $this->response( self::STATUS_OK, [ json_encode( $pack ) ] );
	}

}
