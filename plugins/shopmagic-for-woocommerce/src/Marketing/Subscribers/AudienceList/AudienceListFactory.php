<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Subscribers\AudienceList;

use WPDesk\ShopMagic\Components\Database\Abstraction\EntityNotFound;
use WPDesk\ShopMagic\Helper\ParameterBag;
use WPDesk\ShopMagic\Helper\PostMetaBag;

class AudienceListFactory {

	public function with_post( \WP_Post $post, ?ParameterBag $meta = null ): AudienceList {
		$list = new AudienceList( $post->ID );

		if ( ! $list->exists() ) {
			throw new EntityNotFound(
				esc_html__(
					'Audience list must exist. Trying to create list for ID 0.',
					'shopmagic-for-woocommerce'
				)
			);
		}

		$list->set_name( $post->post_title );
		$list->set_status( $post->post_status ?: AudienceList::STATUS_DRAFT );

		if ( $meta === null ) {
			$meta = new PostMetaBag( $post->ID );
		}

		$list->set_type( $meta->get( 'type', AudienceList::TYPE_OPTIN ) );
		$list->set_checkout_available( $meta->getBoolean( 'checkout_available' ) );
		$list->set_checkout_label( $meta->get( 'checkout_label', '' ) );
		$list->set_checkout_description( $meta->get( 'checkout_description', '' ) );

		if ( $meta->has( '_form_shortcode' ) ) {
			$form     = new NewsletterForm();
			$raw_form = $meta->all( '_form_shortcode' );
			$form->set_agreement( $raw_form['agreement'] ?? '' );
			$form->set_show_name( filter_var( $raw_form['name'], \FILTER_VALIDATE_BOOLEAN ) );
			$form->set_show_labels( filter_var( $raw_form['labels'], \FILTER_VALIDATE_BOOLEAN ) );
			$form->set_double_opt_in( filter_var( $raw_form['double_optin'], \FILTER_VALIDATE_BOOLEAN ) );
			$list->set_newsletter_form( $form );
		}

		if ( $meta->has( 'lang' ) ) {
			$list->set_language( $meta->get( 'lang' ) );
		}

		return $list;
	}

}
