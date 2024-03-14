<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\CommunicationList;

use ShopMagicVendor\WPDesk\Forms\Field\CheckboxField;
use ShopMagicVendor\WPDesk\Forms\Field\TextAreaField;
use ShopMagicVendor\WPDesk\Forms\FieldProvider;

class FormShortcodeMetabox implements FieldProvider {
	/** @var string */
	public const PARAMS_META = '_form_shortcode';

	public function get_fields(): array {
		return [
			( new CheckboxField() )
				->set_default_value( '1' )
				->set_label( 'Name' )
				->set_name( 'name' ),
			( new CheckboxField() )
				->set_default_value( '1' )
				->set_label( 'Labels' )
				->set_name( 'labels' ),
			( new CheckboxField() )
				->set_default_value( '0' )
				->set_label( 'Double opt-in' )
				->set_name( 'double_optin' ),
			( new TextAreaField() )
				->set_label( 'Add marketing agreement' )
				->set_name( 'agreement' ),
		];
	}
}
