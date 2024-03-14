<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Integration\Mailchimp;

use ShopMagicVendor\WPDesk\Forms\Field;
use ShopMagicVendor\WPDesk\Persistence\Adapter\WordPress\WordpressOptionsContainer;
use ShopMagicVendor\WPDesk\Persistence\PersistentContainer;
use WPDesk\ShopMagic\Admin\Settings\FieldSettingsTab;
use WPDesk\ShopMagic\FormField\Field\CheckboxField;
use WPDesk\ShopMagic\FormField\Field\InputTextField;
use WPDesk\ShopMagic\FormField\Field\Paragraph;
use WPDesk\ShopMagic\FormField\Field\SelectField;

final class Settings extends FieldSettingsTab {

	/** @var MailchimpApi */
	private $api;

	public function __construct( MailchimpApi $api ) {
		$this->api = $api;
	}

	public static function get_tab_slug(): string {
		return 'mailchimp';
	}

	public static function get_settings_persistence(): PersistentContainer {
		return new WordpressOptionsContainer();
	}

	public function get_tab_name(): string {
		return __( 'Mailchimp', 'shopmagic-for-woocommerce' );
	}

	/** @return Field[] */
	public function get_fields(): array {
		return [
			( new InputTextField() )
				->set_label( __( 'API Key', 'shopmagic-for-woocommerce' ) )
				->set_description_tip(
					__(
						'Insert your API key here which you can create and get from your Mailchimp settings.',
						'shopmagic-for-woocommerce'
					)
				)
				->set_name( 'wc_settings_tab_mailchimp_api_key' ),

			( new SelectField() )
				->set_label( __( 'List', 'shopmagic-for-woocommerce' ) )
				->set_description_tip(
					__(
						'The DEFAULT MailChimp List names to which you want to add clients.',
						'shopmagic-for-woocommerce'
					)
				)
				->set_options( $this->api->get_all_lists_options() )
				->set_name( 'wc_settings_tab_mailchimp_list_id' ),

			( new CheckboxField() )
				->set_label( __( 'Double opt-in', 'shopmagic-for-woocommerce' ) )
				->set_description(
					__(
						'Send customers an opt-in confirmation email when they subscribe. (Unchecking may be against Mailchimp policy.)',
						'shopmagic-for-woocommerce'
					)
				)
				->set_default_value( CheckboxField::VALUE_TRUE )
				->set_name( 'wc_settings_tab_mailchimp_double_optin' ),

			( new InputTextField() )
				->set_label( __( 'Tags', 'shopmagic-for-woocommerce' ) )
				->set_description_tip(
					__(
						'A single text field for seller to include tags (comma separated) to be added to mailchimp upon checkout.',
						'shopmagic-for-woocommerce'
					)
				)
				->set_name( 'wc_settings_tab_mailchimp_tags' ),

			( new Paragraph() )
				->set_description(
					__(
						'Send additional information to Mailchimp list',
						'shopmagic-for-woocommerce'
					)
				),

			( new CheckboxField() )
				->set_label( __( 'Last name', 'shopmagic-for-woocommerce' ) )
				->set_name( 'wc_settings_tab_mailchimp_info_lname' ),
			( new CheckboxField() )
				->set_label( __( 'Address', 'shopmagic-for-woocommerce' ) )
				->set_name( 'wc_settings_tab_mailchimp_info_address' ),
			( new CheckboxField() )
				->set_label( __( 'City', 'shopmagic-for-woocommerce' ) )
				->set_name( 'wc_settings_tab_mailchimp_info_city' ),
			( new CheckboxField() )
				->set_label( __( 'State', 'shopmagic-for-woocommerce' ) )
				->set_name( 'wc_settings_tab_mailchimp_info_state' ),
			( new CheckboxField() )
				->set_label( __( 'Country', 'shopmagic-for-woocommerce' ) )
				->set_name( 'wc_settings_tab_mailchimp_info_country' ),
		];
	}

}
