<?php

namespace WC_BPost_Shipping;

use LogicException;
use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce as Adapter;
use WC_BPost_Shipping\Api\WC_BPost_Shipping_Api_Factory;
use WC_BPost_Shipping\Assets\WC_BPost_Shipping_Assets_Detector;
use WC_BPost_Shipping\Assets\WC_BPost_Shipping_Assets_Management;
use WC_BPost_Shipping\Assets\WC_BPost_Shipping_Assets_Resources;
use WC_BPost_Shipping\Label\WC_BPost_Shipping_Label_Path_Resolver;
use WC_BPost_Shipping\Label\WC_BPost_Shipping_Label_Retriever;
use WC_BPost_Shipping\Label\WC_BPost_Shipping_Label_Url_Generator;
use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Label;
use WC_BPost_Shipping_Logger;
use WC_BPost_Shipping_Logger_Handler;
use WC_BPost_Shipping_Meta_Type;
use WC_Logger;

class WC_Bpost_Shipping_Container {
	private static $objects = array();

	private static function get( $class_name ) {
		if ( empty( self::$objects[ $class_name ] ) ) {
			self::$objects[ $class_name ] = self::get_class_instance( $class_name );
		}

		return self::$objects[ $class_name ];
	}

	private static function get_class_instance( $class_name ) {
		switch ( $class_name ) {
			case Adapter::class:
				return Adapter::get_instance();

			case WC_BPost_Shipping_Options_Label::class:
				return new WC_BPost_Shipping_Options_Label( self::get_adapter() );

			case WC_BPost_Shipping_Label_Path_Resolver::class:
				return new WC_BPost_Shipping_Label_Path_Resolver( self::get( WC_BPost_Shipping_Options_Label::class ) );

			case WC_BPost_Shipping_Label_Url_Generator::class:
				return new WC_BPost_Shipping_Label_Url_Generator(
					self::get_adapter(),
					WC()
				);

			case WC_BPost_Shipping_Logger::class:
				$handler = new WC_BPost_Shipping_Logger_Handler( new WC_Logger() );

				/** @var WC_BPost_Shipping_Options_Label $options */
				$options = self::get( WC_BPost_Shipping_Options_Label::class );
				if ( ! $options->is_logs_debug_mode() ) {
					$handler->setLevel( \Monolog\Logger::NOTICE );
				}

				return new WC_BPost_Shipping_Logger( BPOST_PLUGIN_ID, array( $handler ) );

			case WC_BPost_Shipping_Api_Factory::class:
				return new WC_BPost_Shipping_Api_Factory(
					self::get( WC_BPost_Shipping_Options_Label::class ),
					self::get( WC_BPost_Shipping_Logger::class )
				);

			case WC_BPost_Shipping_Label_Retriever::class:
				return new WC_BPost_Shipping_Label_Retriever(
					self::get_adapter(),
					self::get( WC_BPost_Shipping_Api_Factory::class ),
					self::get( WC_BPost_Shipping_Label_Url_Generator::class ),
					self::get( WC_BPost_Shipping_Label_Path_Resolver::class ),
					self::get( WC_BPost_Shipping_Options_Label::class )
				);

			case WC_BPost_Shipping_Assets_Management::class:
				return new WC_BPost_Shipping_Assets_Management(
					new WC_BPost_Shipping_Assets_Detector( self::get_adapter() ),
					new WC_BPost_Shipping_Assets_Resources()
				);

			case WC_BPost_Shipping_Meta_Type::class:
				return new WC_BPost_Shipping_Meta_Type( self::get_adapter() );

		}

		throw new LogicException( sprintf( 'Class to load not found: "%s"', $class_name ) );
	}

	/**
	 * @return Adapter
	 */
	public static function get_adapter() {
		return self::get( Adapter::class );
	}

	/**
	 * @return WC_BPost_Shipping_Options_Label
	 */
	public static function get_options_label() {
		return self::get( WC_BPost_Shipping_Options_Label::class );
	}

	/**
	 * @return WC_BPost_Shipping_Label_Path_Resolver
	 */
	public static function get_label_resolver_path() {
		return self::get( WC_BPost_Shipping_Label_Path_Resolver::class );
	}

	/**
	 * @return WC_BPost_Shipping_Label_Url_Generator
	 */
	public static function get_label_url_generator() {
		return self::get( WC_BPost_Shipping_Label_Url_Generator::class );
	}

	/**
	 * @return WC_BPost_Shipping_Logger
	 */
	public static function get_logger() {
		return self::get( WC_BPost_Shipping_Logger::class );
	}

	/**
	 * @return WC_BPost_Shipping_Api_Factory
	 */
	public static function get_api_factory() {
		return self::get( WC_BPost_Shipping_Api_Factory::class );
	}

	/**
	 * @return WC_BPost_Shipping_Label_Retriever
	 */
	public static function get_label_retriever() {
		return self::get( WC_BPost_Shipping_Label_Retriever::class );
	}

	/**
	 * @return WC_BPost_Shipping_Assets_Management
	 */
	public static function get_assets_management() {
		return self::get( WC_BPost_Shipping_Assets_Management::class );
	}

	/**
	 * @return WC_BPost_Shipping_Meta_Type
	 */
	public static function get_meta_type() {
		return self::get( WC_BPost_Shipping_Meta_Type::class );
	}

}
