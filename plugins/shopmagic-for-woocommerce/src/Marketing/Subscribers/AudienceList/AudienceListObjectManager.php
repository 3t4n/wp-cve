<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers\AudienceList;

use WPDesk\ShopMagic\Components\Database\Abstraction\PostObjectManager;

/**
 * @extends PostObjectManager<AudienceList>
 */
class AudienceListObjectManager extends PostObjectManager {

	public function do_save( object $item ): bool {
		if ( $item->get_id() !== null ) {
			wp_update_post( [
				'ID'          => $item->get_id(),
				'post_title'  => $item->get_name(),
				'post_status' => $item->get_status(),
			] );
		} else {
			$id = wp_insert_post(
				[
					'post_title'   => $item->get_name(),
					'post_content' => '',
					'post_type'    => CommunicationListPostType::TYPE,
					'post_status'  => $item->get_status(),
				]
			);
			$item->set_id( $id );
		}

		$persistence = new CommunicationListPersistence( $item->get_id() );
		$persistence->set( 'type', $item->get_type() );
		$persistence->set( 'checkout_available', $item->is_checkout_available() );
		$persistence->set( 'checkout_description', $item->get_checkout_description() );
		$persistence->set( 'checkout_label', $item->get_checkout_label() );
		$persistence->set( '_form_shortcode', $item->get_newsletter_form()->to_array() );
		$persistence->set( 'lang', $item->get_language() );

		return true;
	}

	public function can_handle( object $item ): bool {
		return $item instanceof AudienceList;
	}

	/**
	 * @param AudienceList|object $item
	 *
	 * @return bool
	 */
	public function do_delete( object $item ): bool {
		if ( $item->get_status() === 'trash' ) {
			return (bool) wp_delete_post( $item->get_id() );
		} else {
			return (bool) wp_trash_post( $item->get_id() );
		}
	}

	protected function expected_post_type(): ?string {
		return CommunicationListPostType::TYPE;
	}
}
