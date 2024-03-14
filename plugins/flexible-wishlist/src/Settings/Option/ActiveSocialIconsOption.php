<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class ActiveSocialIconsOption extends OptionBase {

	const FIELD_NAME = 'social_icons_active';

	/**
	 * {@inheritdoc}
	 */
	public function get_name(): string {
		return self::FIELD_NAME;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_label(): string {
		return __( 'Active social media icons', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_type(): string {
		return OptionBase::FIELD_TYPE_MULTI_CHECKBOX;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_value() {
		return [
			'facebook',
			'twitter',
			'pinterest',
			'whatsapp',
			'email',
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_options(): array {
		return [
			'facebook'  => __( 'Facebook', 'flexible-wishlist' ),
			'twitter'   => __( 'Twitter', 'flexible-wishlist' ),
			'pinterest' => __( 'Pinterest', 'flexible-wishlist' ),
			'whatsapp'  => __( 'WhatsApp', 'flexible-wishlist' ),
			'email'     => __( 'E-mail', 'flexible-wishlist' ),
		];
	}
}
