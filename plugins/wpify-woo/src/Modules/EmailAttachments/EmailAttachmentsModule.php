<?php

namespace WpifyWoo\Modules\EmailAttachments;

use WC_Order;
use WC_Order_Item_Product;
use WC_Product;
use WpifyWoo\Abstracts\AbstractModule;

class EmailAttachmentsModule extends AbstractModule {

	/**
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_filter( 'woocommerce_email_attachments', array( $this, 'add_attachments_to_emails' ), 10, 3 );
		add_action( 'init', [ $this, 'product_attachments_metabox' ] );
	}

	function id() {
		return 'email_attachments';
	}

	public function product_attachments_metabox() {
		$this->plugin->get_wcf()->create_product_options(
			[
				'tab'           => array(
					'id'       => 'email_attachments',
					'label'    => __( 'Attachments', 'wpify-woo' ),
					'priority' => 100,
				),
				'init_priority' => 10,
				'items'         => [ $this->attachments_settings() ],

			]
		);
	}

	/**
	 * @return array[]
	 */
	public function settings(): array {
		return [ $this->attachments_settings() ];
	}

	public function attachments_settings() {
		return array(
			'id'    => 'email_attachments',
			'type'  => 'multi_group',
			'label' => __( 'Email attachments', 'wpify-woo' ),
			'items' => [
				[
					'id'    => 'attachments',
					'label' => __( 'Attachments', 'wpify-woo' ),
					'type'  => 'multi_attachment',
				],
				[
					'id'    => 'custom_fields',
					'label' => __( 'Custom fields', 'wpify-woo' ),
					'type'  => 'multi_group',
					'items' => [
						[
							'id'    => 'custom_field',
							'label' => __( 'Custom field', 'wpify-woo' ),
							'desc'  => __( 'Enter order custom field, where the path the file is stored.', 'wpify-woo' ),
							'type'  => 'text',
						],
					],
				],
				[
					'id'      => 'email',
					'label'   => __( 'Attach to emails', 'wpify-woo' ),
					'type'    => 'multi_select',
					'options' => function () {
						return array_values( array_map( function ( $item ) {
							return [
								'value' => $item->id,
								'label' => $item->title . ' - ' . esc_html( $item->is_customer_email() ? __( 'Customer', 'woocommerce' ) : $item->get_recipient() ),
							];
						}, WC()->mailer()->get_emails() ) );
					},
				],
				[
					'id'      => 'enabled_countries',
					'label'   => __( 'Enabled countries', 'wpify-woo' ),
					'desc'    => __( 'Select the countries for which the attachment should be added. Leave empty for all.', 'wpify-woo' ),
					'type'    => 'multi_select',
					'options' => function () {
						$countries = [];
						foreach ( WC()->countries->get_allowed_countries() as $key => $val ) {
							$countries[] = [
								'label' => $val,
								'value' => $key,
							];
						}

						return $countries;
					},
				],
			],
		);
	}


	public function add_attachments_to_emails( $attachments, $email_id, $data ) {
		if ( ! is_a( $data, WC_Order::class ) ) {
			return $attachments;
		}

		// Global emails
		$country     = $data->get_shipping_country() ?: $data->get_billing_country();
		$items       = $this->get_setting( 'email_attachments' ) ?: [];
		$attachments = array_merge( $attachments, $this->add_attachments( $items, $email_id, $country ) );

		// Product emails.
		foreach ( $data->get_items() as $item ) {
			/**  @var $item WC_Order_Item_Product */
			if ( ! is_a( $item, WC_Order_Item_Product::class ) || ! is_a( $item->get_product(), WC_Product::class ) ) {
				continue;
			}

			$items = $item->get_product()->get_meta( 'email_attachments' );

			if ( empty( $items ) ) {
				continue;
			}

			$attachments = array_merge( $attachments, $this->add_attachments( $items, $email_id, $country ) );
		}

		return array_unique( $attachments );
	}

	public function add_attachments( $items, $email_id, $country ) {
		$attachments = [];
		foreach ( $items as $item ) {
			if ( ! in_array( $email_id, $item['email'] ) ) {
				continue;
			}

			$enabled_countries = ! empty( $item['enabled_countries'] ) ? $item['enabled_countries'] : array_keys( WC()->countries->get_allowed_countries() );
			if ( ! in_array( $country, $enabled_countries ) ) {
				continue;
			}

			if ( ! empty( $item['attachments'] ) ) {
				foreach ( $item['attachments'] as $attachment_id ) {
					$file = get_attached_file( $attachment_id );
					if ( $file ) {
						$attachments[] = get_attached_file( $attachment_id );
					}
				}
			}

			if ( ! empty( $item['custom_fields'] ) ) {
				foreach ( $item['custom_fields'] as $field ) {
					if ( file_exists( $field['custom_field'] ) ) {
						$attachments[] = get_attached_file( $field['custom_field'] );
					}
				}
			}
		}

		return $attachments;
	}

	public function name() {
		return __( 'Email attachments', 'wpify-woo' );
	}
}
