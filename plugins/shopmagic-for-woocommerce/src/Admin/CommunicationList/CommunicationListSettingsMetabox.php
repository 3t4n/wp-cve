<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\CommunicationList;

use ShopMagicVendor\WPDesk\Forms\Field\SelectField;
use ShopMagicVendor\WPDesk\Forms\Field\WyswigField;
use WPDesk\ShopMagic\Admin\Form\FieldsCollection;
use WPDesk\ShopMagic\FormField\Field\CheckboxField;
use WPDesk\ShopMagic\FormField\Field\InputTextField;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceList;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\CommunicationListPersistence;

class CommunicationListSettingsMetabox {

	public function get_fields(): FieldsCollection {
		return new FieldsCollection(
			[
				( new SelectField() )
					->set_default_value( AudienceList::TYPE_OPTIN )
					->set_name( CommunicationListPersistence::FIELD_TYPE_KEY )
					->set_label( __( 'List type', 'shopmagic-for-woocommerce' ) )
					->set_options(
						[
							AudienceList::TYPE_OPTIN  => __( 'Opt-in', 'shopmagic-for-woocommerce' ),
							AudienceList::TYPE_OPTOUT => __( 'Opt-out', 'shopmagic-for-woocommerce' ),
						]
					)
					->set_description(
						esc_html__( 'Opt-in communication requires customer consent. Opt-out communication (not recommended) is sent until the customer opts out.',
							'shopmagic-for-woocommerce' ) .
						sprintf( ' <a href="https://docs.shopmagic.app/" target="_blank">%s</a> &rarr;',
							esc_html__( 'Learn more', 'shopmagic-for-woocommerce' ) )
					),
				( new CheckboxField() )
					->set_name( CommunicationListPersistence::FIELD_CHECKOUT_AVAILABLE_KEY )
					->set_label( __( 'Opt-in checkbox', 'shopmagic-for-woocommerce' ) )
					->set_description( __( 'You may choose to show the checkbox in checkout.',
						'shopmagic-for-woocommerce' ) ),
				( new InputTextField() )
					->set_name( CommunicationListPersistence::FIELD_CHECKBOX_LABEL_KEY )
					->set_label( __( 'Checkbox label', 'shopmagic-for-woocommerce' ) )
					->set_description( __( 'The checkbox will always be available in the Communication preferences page to let the customers opt-out.',
						'shopmagic-for-woocommerce' ) )
					->set_required(),
				( new WyswigField() )
					->set_type( 'textarea' )
					->set_name( CommunicationListPersistence::FIELD_CHECKBOX_DESCRIPTION_KEY )
					->set_label( __( 'Checkbox description', 'shopmagic-for-woocommerce' ) ),

			]
		);
	}

}
