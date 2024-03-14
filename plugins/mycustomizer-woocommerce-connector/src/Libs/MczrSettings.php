<?php

namespace MyCustomizer\WooCommerce\Connector\Libs;

use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;

MczrAccess::isAuthorized();

class MczrSettings {

	const SETTING_OPTION_PREFIX = 'mczrSetting';
	const SETTING_OPTION_FIELDS = array(
		'authorizationKey',
		'iframeWidth',
		'iframeHeight',
		'iframeHook',
		'iframeHookPriority',
		'productCss',
		'brand',
		'shopId',
		'apiToken',
	);

	public function getAll( $key = null ) {
		$return = array();
		foreach ( self::SETTING_OPTION_FIELDS as $fieldName ) :
			$return[ $fieldName ] = $this->get( $fieldName );
		endforeach;

		return $return;
	}

	public function get( $name, $default = false ) {
		if ( ! \is_string( $name ) ) {
			throw new \Exception( 'Invalid name parameter' );
		}
		return \get_option( $this->getFullName( $name ), $default );
	}

	public function update( array $options ) {
		foreach ( $options as $key => $option ) :
			$this->updateOne( $key, $option );
		endforeach;

		return $this;
	}

	public function updateOne( $name, $value ) {
		$fullName = $this->getFullName( $name );
		return \update_option( $fullName, sanitize_text_field( $value ) );
	}

	private function getFullName( $name ) {
		return self::SETTING_OPTION_PREFIX . \ucfirst( $name );
	}
}
