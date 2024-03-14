<?php

namespace WPDesk\FlexibleWishlist\Settings\Group;

use WPDesk\FlexibleWishlist\Settings\Option\TextAddedItemOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextAddItemOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveBackOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveTitleOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextArchiveUrlOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCopyItemOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCreateIdeaButtonOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCreateIdeaInputOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCreateWishlistButtonOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCreateWishlistInputOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextDefaultWishlistTitleOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextNotLoggedInUserOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextPopupTitleOption;

/**
 * {@inheritdoc}
 */
class TextGroup implements Group {

	/**
	 * {@inheritdoc}
	 */
	public function get_label(): string {
		return __( 'Text settings', 'flexible-wishlist' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_fields(): array {
		return [
			new TextArchiveUrlOption(),
			new TextArchiveTitleOption(),
			new TextArchiveBackOption(),
			new TextDefaultWishlistTitleOption(),
			new TextCreateWishlistInputOption(),
			new TextCreateWishlistButtonOption(),
			new TextCreateIdeaInputOption(),
			new TextCreateIdeaButtonOption(),
			new TextAddItemOption(),
			new TextPopupTitleOption(),
			new TextCopyItemOption(),
			new TextAddedItemOption(),
			new TextNotLoggedInUserOption(),
		];
	}
}
