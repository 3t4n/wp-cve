<?php

namespace WPDesk\FlexibleWishlist\Settings\Option;

/**
 * {@inheritdoc}
 */
class MenuSelectedOption extends OptionBase {

	const FIELD_NAME = 'menu_selected';

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
		return __( 'Decide where to display the My wishlists item', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_description() {
		return sprintf(
		/* translators: %1$s: open anchor tag, %2$s: close anchor tag */
			__( 'You can edit the settings of these menus in the %1$sAppearance > Menus%2$s tab.', 'flexible-wishlist' ),
			'<a href="' . admin_url( 'nav-menus.php' ) . '">',
			'</a>'
		);
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
	public function get_options(): array {
		$nav_menus = wp_get_nav_menus();
		$values    = [];
		foreach ( $nav_menus as $nav_menu ) {
			$values[ $nav_menu->term_id ] = $nav_menu->name;
		}
		return $values;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_disabled_options(): array {
		$user_menus = [];
		foreach ( $this->get_options() as $menu_id => $menu_label ) {
			if ( $this->is_used_menu( $menu_id ) ) {
				$user_menus[] = $menu_id;
			}
		}

		return $user_menus;
	}

	private function is_used_menu( int $menu_id ): bool {
		$menu_items = get_objects_in_term( $menu_id, 'nav_menu' );
		if ( is_wp_error( $menu_items ) ) {
			return false;
		}

		foreach ( $menu_items as $menu_item_id ) {
			if ( get_post_meta( (int) $menu_item_id, '_menu_item_url', true ) === '#flexible-wishlist' ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param string[] $raw_value .
	 *
	 * @return string[]
	 */
	public function parse_value( $raw_value ): array {
		return array_unique(
			array_merge( $raw_value, $this->get_disabled_options() )
		);
	}
}
