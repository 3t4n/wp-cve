<?php

namespace WC_BPost_Shipping\Label;


use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Controller\WC_BPost_Shipping_Controller_Base;

class WC_BPost_Shipping_Label_Meta_Box_Controller extends WC_BPost_Shipping_Controller_Base {
	/** @var WC_BPost_Shipping_Label_Attachment */
	private $label_attachment;

	/**
	 * WC_BPost_Shipping_Label_Meta_Box_Controller constructor.
	 *
	 * @param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param WC_BPost_Shipping_Label_Attachment $label_attachment
	 */
	public function __construct(
		WC_BPost_Shipping_Adapter_Woocommerce $adapter,
		WC_BPost_Shipping_Label_Attachment $label_attachment
	) {
		parent::__construct( $adapter );
		$this->label_attachment = $label_attachment;
	}

	/**
	 * This function provides a contract to use to load a template using controller.
	 */
	public function load_template() {
		$has_attachment = $this->label_attachment->has_attachment();

		$has_bpost_order = $this->has_bpost_order();

		$url = $this->label_attachment->get_generate_url();

		if ( ! $has_bpost_order ) {
			$caption = 'Label not available because there is not bpost order';
		} elseif ( $has_attachment ) {
			$caption = sprintf(
				bpost__( 'Retrieved the %s' ),
				$this->adapter->date_i18n(
					get_option( 'date_format' ),
					$this->label_attachment->get_retrieved_date()->getTimestamp()
				)
			);
		} else {
			$caption = bpost__( 'Retrieve it from bpost' );
		}

		$this->get_template(
			'label/order-details-meta.php',
			array(
				'has_bpost_order' => $has_bpost_order,
				'attachment_url'  => $url,
				'caption'         => $caption,
				'track_url'       => $this->get_tracking_url(),
			)
		);
	}

	private function has_bpost_order(): bool {
		return ! empty( $this->label_attachment->get_order_reference() );
	}

	private function get_tracking_url(): string {
		if ( ! $this->has_bpost_order() ) {
			return '';
		}

		return sprintf(
			'https://track.bpost.cloud/btr/web/#/search?itemCode=%s&postalCode=%s',
			$this->label_attachment->get_order_reference(),
			$this->label_attachment->get_shipping_postal_code()
		);
	}
}
