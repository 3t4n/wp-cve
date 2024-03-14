<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer\MarketingList;

use WPDesk\ShopMagic\Admin\CommunicationList\FormShortcodeMetabox;
use WPDesk\ShopMagic\Api\Normalizer\Denormalizer;
use WPDesk\ShopMagic\Api\Normalizer\InvalidArgumentException;
use WPDesk\ShopMagic\Api\Normalizer\Normalizer;
use WPDesk\ShopMagic\Components\UrlGenerator\RestUrlGenerator;
use WPDesk\ShopMagic\Helper\PostMetaBag;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceList;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\NewsletterForm;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriberObjectRepository;

/**
 * @implements Normalizer<AudienceList>
 * @implements Denormalizer<AudienceList>
 */
class MarketingListNormalizer implements Normalizer, Denormalizer {

	/** @var SubscriberObjectRepository */
	private $subscribers_repository;

	/** @var RestUrlGenerator */
	private $url_generator;

	public function __construct( SubscriberObjectRepository $subscribers_repository, RestUrlGenerator $url_generator ) {
		$this->subscribers_repository = $subscribers_repository;
		$this->url_generator          = $url_generator;
	}

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( AudienceList::class, $object );
		}

		$meta = new PostMetaBag( $object->get_id() );

		$shortcode = null;

		if ( $object->get_type() === 'opt_in' ) {
			$shortcode = array_merge( [
				'name'         => true,
				'labels'       => true,
				'double_optin' => false,
				'agreement'    => '',
			],
				$meta->has( FormShortcodeMetabox::PARAMS_META )
					? $meta->get( FormShortcodeMetabox::PARAMS_META )
					: []
			);
		}

		return [
			'id'               => $object->get_id(),
			'name'             => $object->get_name(),
			'status'           => $object->get_status(),
			'type'             => $object->get_type(),
			'shortcode'        => $shortcode,
			'checkout'         => [
				'checkout_available'   => $object->is_checkout_available(),
				'checkout_label'       => $object->get_checkout_label(),
				'checkout_description' => $object->get_checkout_description(),
				'type'                 => $object->get_type(),
			],
			'language'         => $object->get_language(),
			'subscribersCount' => $this->subscribers_repository->get_count(
				[
					'list_id' => $object->get_id(),
					'active'  => 1,
				]
			),
			'_links'           => [
				'subscribers' => [
					'href' => $this->url_generator->generate( '/lists/' . $object->get_id() . '/subscribers' ),
				],
			],
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof AudienceList;
	}

	public function denormalize( array $payload ): object {
		$list = new AudienceList();
		$list->set_id( isset( $payload['id'] ) ? (int) $payload['id'] : null );
		$list->set_name( sanitize_text_field( $payload['name'] ) );
		if ( isset( $payload['status'] ) && $payload['status'] === 'publish' ) {
			$list->set_status( 'publish' );
		} else {
			$list->set_status( 'draft' );
		}
		$list->set_type( $payload['checkout']['type'] ?? AudienceList::TYPE_OPTIN );
		$list->set_checkout_available( $payload['checkout']['checkout_available'] );
		$list->set_checkout_label( $payload['checkout']['checkout_label'] );
		$list->set_checkout_description( $payload['checkout']['checkout_description'] );

		if ( isset( $payload['shortcode'] ) ) {
			$form = new NewsletterForm();
			$form->set_agreement( $payload['shortcode']['agreement'] );
			$form->set_show_name( $payload['shortcode']['name'] );
			$form->set_show_labels( $payload['shortcode']['labels'] );
			$form->set_double_opt_in( $payload['shortcode']['double_optin'] );
			$list->set_newsletter_form( $form );
		}

		if ( isset( $payload['language'] ) ) {
			$list->set_language( $payload['language'] );
		}

		return $list;
	}

	public function supports_denormalization( array $data ): bool {
		return true;
	}
}
