<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\Settings;

use ShopMagicVendor\WPDesk\Persistence\PersistentContainer;
use WPDesk\ShopMagic\Admin\Form\Fields\FreeModuleField;
use WPDesk\ShopMagic\Admin\Form\Fields\InternalModuleField;

class ModulesSettings extends FieldSettingsTab {

	public static function get_tab_slug(): string {
		return 'modules';
	}

	public static function get_settings_persistence(): PersistentContainer {
		return new ModulesInfoContainer( parent::get_settings_persistence() );
	}

	public function get_fields(): array {
		return [
			( new FreeModuleField() )
				->set_description( esc_html__( 'Allows saving customer details on a partial WooCommerce purchase and send abandoned cart emails.',
					'shopmagic-for-woocommerce' ) )
				->set_label( 'ShopMagic Abandoned Carts' )
				->set_name( 'shopmagic-abandoned-carts/shopmagic-abandoned-carts.php' )
				->set_plugin_slug( 'shopmagic-abandoned-carts' ),
			( new FreeModuleField() )
				->set_description( esc_html__( 'Send free WooCommerce SMS and text messages to your customers.',
					'shopmagic-for-woocommerce' ) )
				->set_label( 'ShopMagic for Twilio' )
				->set_name( 'shopmagic-for-twilio/shopmagic-for-twilio.php' )
				->set_plugin_slug( 'shopmagic-for-twilio' ),
			( new FreeModuleField() )
				->set_description( esc_html__( 'Allows creating WooCommerce automations based on Contact Form 7 submissions.',
					'shopmagic-for-woocommerce' ) )
				->set_label( 'ShopMagic for Contact Form 7' )
				->set_name( 'shopmagic-for-contact-form-7/shopmagic-for-contact-form-7.php' )
				->set_plugin_slug( 'shopmagic-for-contact-form-7' ),
			( new FreeModuleField() )
				->set_description( esc_html__( 'Integrate your WooCommerce store with the most popular Spreadsheets service for free.', 'shopmagic-for-woocommerce' ) )
				->set_label( 'ShopMagic for Google Sheets' )
				->set_name( 'shopmagic-for-google-sheets/shopmagic-for-google-sheets.php' )
				->set_plugin_slug( 'shopmagic-for-google-sheets' ),
			( new InternalModuleField() )
				->set_description( esc_html__( 'Enable multilingual support', 'shopmagic-for-woocommerce' ) )
				->set_label( 'Multilingual' )
				->set_name( 'multilingual-module' ),
		];
	}

	public function get_tab_name(): string {
		return __( 'Modules', 'shopmagic-for-woocommerce' );
	}
}
