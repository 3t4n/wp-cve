<?php

namespace WpifyWoo\Modules\SklikRetargeting;

use WC_Product;
use WpifyWoo\Abstracts\AbstractModule;
use WpifyWooFeeds\Feeds\Zbozi\Settings as ZboziFeedSettings;

class SklikRetargetingModule extends AbstractModule {
	/**
	 * @return void
	 */
	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_filter( 'wp_footer', array( $this, 'render_code' ), 20, 2 );
	}

	/**
	 * Module ID
	 * @return string
	 */
	public function id(): string {
		return 'sklik_retargeting';
	}

	/**
	 * Module name
	 * @return string
	 */
	public function name(): string {
		return __( 'Sklik retargeting', 'wpify-woo' );
	}

	/**
	 * Module settings
	 * @return array[]
	 */
	public function settings(): array {
		$settings = array(
				array(
						'id'    => 'rtg_id',
						'type'  => 'text',
						'label' => __( 'Identifier retargeting', 'wpify-woo' ),
						'desc'  => __( 'Enter your unique identifier for the <code>rtgId</code> that can be found in Sklik interface', 'wpify-woo' ),
				),
				array(
						'type'  => 'title',
						'label' => __( 'Marketing cookie', 'wpify-woo' ),
						'desc'  => __( 'You need consent from the visitor for marketing cookies. If you don`t enter the name and value of the marketing cookie the Seznam will process the data as if consent had been given.', 'wpify-woo' ),
				),
				array(
						'id'    => 'cookie_name',
						'type'  => 'text',
						'label' => __( 'Marketing cookie name', 'wpify-woo' ),
						'desc'  => __( 'Enter the name of the cookie that represents the agreed marketing cookies. For example, in the case of using the "Complianz" plugin, this is <code>cmplz_marketing</code>.', 'wpify-woo' ),
				),
				array(
						'id'    => 'cookie_value',
						'type'  => 'text',
						'label' => __( 'Marketing cookie value', 'wpify-woo' ),
						'desc'  => __( 'Enter the value of the cookie that represents the agreed marketing cookies. For example, in the case of using the "Complianz" plugin, this is <code>allow</code>.', 'wpify-woo' ),
				),
				array(
						'type'  => 'title',
						'label' => __( 'Advanced data', 'wpify-woo' ),
						'desc'  => __( 'Advanced data are optional parameters that help to better target advertising.' ),
				),
				array(
						'id'    => 'item_id',
						'type'  => 'toggle',
						'label' => __( 'Add E-shop offer identifier', 'wpify-woo' ),
						'desc'  => sprintf( __( 'Check if <code>itemId</code> should be added to the code. More information about this parameter can be found in <a href="%1$s" target="_blank">Sklik Help</a>.', 'wpify-woo' ), 'https://napoveda.sklik.cz/cileni/retargeting/pokrocily-retargetingovy-kod/pokrocile-nastaveni-rtg-kodu-item_id/' ),
				),
				array(
						'id'    => 'custom_item_id',
						'type'  => 'text',
						'label' => __( 'Custom E-shop offer identifier', 'wpify-woo' ),
						'desc'  => __( 'Enter the key of the custom field of product you want to use for the <code>itemId</code> parameter. The product ID is used by default.', 'wpify-woo' ),
				),
		);

		if ( function_exists( 'wpify_woo_feeds_container' ) ) {
			$settings[] = array(
					'id'    => 'feed_category',
					'type'  => 'toggle',
					'label' => __( 'Use category identifier from Wpify Woo Feed', 'wpify-woo' ),
					'desc'  => __( 'Check if you want use category identifier from Wpify Woo Feeds plugin.', 'wpify-woo' ),
			);
		} else {
			$notice     = sprintf( __( 'If you want add automatically category identifier from feed settings. Install and use <a href="%s" target="_blank">Wpify Woo Feeds</a> plugin.', 'wpify-woo' ), __( 'https://wpify.io/product/wpify-woo-feeds/', 'wpify-woo' ) );
			$settings[] = array(
					'id'      => 'wpify_feed_notice',
					'type'    => 'html',
					'title'   => __( 'Use category identifier from Wpify Woo Feed', 'wpify-woo' ),
					'content' => sprintf( '<div class="notice notice-warning"><p>%s</p></div>', $notice ),
			);
		}

		$settings[] = array(
				'id'    => 'custom_category',
				'type'  => 'text',
				'label' => __( 'Custom category identifier', 'wpify-woo' ),
				'desc'  => sprintf( __( 'Enter the meta data key of category you use to fill in the category for the Zbozi.cz XML feed. More information about <code>category</code> parameter can be found in <a href="%1$s" target="_blank">Sklik Help</a>.', 'wpify-woo' ), 'https://napoveda.sklik.cz/cileni/retargeting/pokrocily-retargetingovy-kod/pokrocile-nastaveni-rtg-kodu-category/' ),
		);

		return $settings;
	}

	/**
	 * Render retargeting code in wp footer
	 */
	public function render_code() {
		if ( ! $this->get_setting( 'rtg_id' ) || apply_filters( 'wpify_woo_sklik_retargeting_render_code', true ) === false ) {
			return;
		}

		?>
		<!-- Sklik retargeting -->
		<script type="text/javascript" src="https://c.seznam.cz/js/rc.js"></script>
		<script>
			var retargetingConf = {
				<?php $parameters = $this->get_parameters();
				foreach ( $parameters as $key => $parameter ) {
					echo $key . ': ' . $parameter . ', ';
				}
				?> };
			if (window.rc && window.rc.retargetingHit) {
				window.rc.retargetingHit(retargetingConf);
			}
		</script>
		<?php
	}

	/**
	 * Get parameters for retargeting code
	 */
	public function get_parameters() {
		$parameters = array(
				'rtgId' => esc_attr( $this->get_setting( 'rtg_id' ) ),
		);

		$item_id = $this->get_setting( 'item_id' );
		if ( $item_id && is_product() ) {
			$parameters['itemId']   = '"' . $this->get_item_ids() . '"';
			$parameters['pageType'] = '"offerdetail"';
		}

		if ( is_product_category() ) {
			$category = '';
			$feed_category = $this->get_setting( 'feed_category' );
			if ( $feed_category && function_exists( 'wpify_woo_feeds_container' ) ) {
				$category = wpify_woo_feeds_container()->get( ZboziFeedSettings::CLASS )->get_option( 'category_' . get_the_id()) ;
			}

			$custom_category = $this->get_setting( 'custom_category' );
			if ( empty( $category ) && $custom_category ) {
				$category = get_term_meta( get_the_ID(), $custom_category, true );
			}

			if ( $category ) {
				$parameters['category'] = '"' . $category . '"';
				$parameters['pageType'] = '"category"';
			}
		}

		$cookie_name  = $this->get_setting( 'cookie_name' );
		$cookie_value = $this->get_setting( 'cookie_value' );
		if ( $cookie_name && $cookie_value ) {
			$parameters['consent'] = 'document.cookie.includes("' . $cookie_name . '=' . $cookie_value . '") ? 1 : 0';
		}

		return apply_filters( 'wpify_woo_sklik_retargeting_parameters', $parameters );
	}

	public function get_item_ids(): string {
		/** @var $product WC_Product */
		$product        = wc_get_product( get_the_ID() );
		$custom_item_id = $this->get_setting( 'custom_item_id' );

		$array_of_ids = array(
				$custom_item_id ? $product->get_meta()[ $custom_item_id ] : get_the_ID(),
		);

		if ( $product->is_type( "variable" ) ) {
			foreach ( $product->get_children() as $child_id ) {
				$variation = wc_get_product( $child_id );

				if ( ! $variation || ! $variation->exists() ) {
					continue;
				}

				if ( $custom_item_id ) {
					$array_of_ids[] = $variation->get_meta()[ $custom_item_id ];
				} else {
					$array_of_ids[] = $child_id;
				}
			}
		}

		return implode( ', ', $array_of_ids );
	}

}
