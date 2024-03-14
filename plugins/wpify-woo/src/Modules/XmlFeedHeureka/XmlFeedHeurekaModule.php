<?php

namespace WpifyWoo\Modules\XmlFeedHeureka;

use WpifyWoo\Abstracts\AbstractModule;

class XmlFeedHeurekaModule extends AbstractModule {
	/**
	 * @var Feed
	 */
	private $feed;

	private $temp_categories = [];

	public function __construct( Feed $feed ) {
		parent::__construct();
		$this->feed = $feed;
	}

	public function setup() {
		add_filter( 'wpify_woo_settings_' . $this->id(), array( $this, 'settings' ) );
		add_action( 'admin_init', [ $this, 'handle_actions' ] );
		add_filter( 'woocommerce_product_data_tabs', [ $this, 'add_product_tabs' ] );
		add_action( 'woocommerce_product_data_panels', [ $this, 'add_product_tabs_content' ] );
		add_action( 'woocommerce_process_product_meta', [ $this, 'save_custom_fields' ] );
		add_action( 'woocommerce_product_after_variable_attributes', [ $this, 'add_custom_variations_fields' ], 10, 3 );
		add_action( 'woocommerce_save_product_variation', [ $this, 'save_custom_variation_fields' ], 10, 2 );
	}

	/**
	 * Set the module ID.
	 * @return string
	 */
	public function id(): string {
		return 'xml_feed_heureka';
	}

	function add_product_tabs( $tabs ) {
		$tabs['additional_info'] = [
				'label'    => __( 'Heureka XML', 'wpify-woo' ),
				'target'   => 'wpify_woo_heureka_xml',
				'priority' => 200,
		];

		return $tabs;
	}

	public function add_product_tabs_content() { ?>
		<div id="wpify_woo_heureka_xml" class="panel woocommerce_options_panel hidden"><?php

		woocommerce_wp_text_input( [
				'id'    => '_wpify_woo_heureka_product_name',
				'label' => __( 'Heureka Product name', 'wpify-woo' ),
		] );
		woocommerce_wp_text_input( [
				'id'    => '_wpify_woo_heureka_product',
				'label' => __( 'Heureka Product', 'wpify-woo' ),
		] );
		woocommerce_wp_text_input( [
				'id'    => '_wpify_woo_heureka_category',
				'label' => __( 'Heureka Category', 'wpify-woo' ),
		] );

		?></div><?php
	}

	public function add_custom_variations_fields( $loop, $variation_data, $variation ) {
		echo '<div class="options_group form-row form-row-full">';

		woocommerce_wp_text_input(
				array(
						'id'    => '_wpify_woo_heureka_product_name[' . $variation->ID . ']',
						'label' => __( 'Heureka Product name', 'wpify-woo' ),
						'value' => get_post_meta( $variation->ID, '_wpify_woo_heureka_product_name', true ),
				)
		);
		woocommerce_wp_text_input(
				array(
						'id'    => '_wpify_woo_heureka_product[' . $variation->ID . ']',
						'label' => __( 'Heureka Product', 'wpify-woo' ),
						'value' => get_post_meta( $variation->ID, '_wpify_woo_heureka_product', true ),
				)
		);
		woocommerce_wp_text_input(
				array(
						'id'    => '_wpify_woo_heureka_category[' . $variation->ID . ']',
						'label' => __( 'Heureka Category', 'wpify-woo' ),
						'value' => get_post_meta( $variation->ID, '_wpify_woo_heureka_category', true ),
				)
		);
		echo '</div>';
	}

	function save_custom_variation_fields( $post_id ) {
		update_post_meta( $post_id, '_wpify_woo_heureka_product_name', sanitize_text_field( $_POST['_wpify_woo_heureka_product_name'][ $post_id ] ) );
		update_post_meta( $post_id, '_wpify_woo_heureka_product', sanitize_text_field( $_POST['_wpify_woo_heureka_product'][ $post_id ] ) );
		update_post_meta( $post_id, '_wpify_woo_heureka_category', sanitize_text_field( $_POST['_wpify_woo_heureka_category'][ $post_id ] ) );
	}

	public function save_custom_fields( $post_id ) {
		$product = wc_get_product( $post_id );
		$product->update_meta_data( '_wpify_woo_heureka_product_name', sanitize_text_field( $_POST['_wpify_woo_heureka_product_name'] ) );
		$product->update_meta_data( '_wpify_woo_heureka_product', sanitize_text_field( $_POST['_wpify_woo_heureka_product'] ) );
		$product->update_meta_data( '_wpify_woo_heureka_category', sanitize_text_field( $_POST['_wpify_woo_heureka_category'] ) );
		$product->save();
	}

	/**
	 * Set the module ID.
	 * @return string
	 */
	public function name() {
		return __( 'XML Feed Heureka', 'wpify-woo' );
	}

	/**
	 * Add settings
	 * @return array[] Settings.
	 */
	public function settings(): array {
		$settings = array(
				array(
						'id'            => 'delivery',
						'type'          => 'text',
						'label'         => __( 'Delivery time', 'wpify-woo' ),
						'desc'          => __( 'Enter 0 for instock, 1-3 for 3 days, 4-7 for one week, 8-14 for two weeks, 15-30 for one month, 31 and more for month and more.', 'wpify-woo' ),
						'default' => '0',
				),
				array(
						'id'            => 'delivery_out_of_stock',
						'type'          => 'text',
						'label'         => __( 'Delivery time for out of stock items', 'wpify-woo' ),
						'desc'          => __( 'Enter 0 for instock, 1-3 for 3 days, 4-7 for one week, 8-14 for two weeks, 15-30 for one month, 31 and more for month and more.', 'wpify-woo' ),
						'default' => '0',
				),
				array(
						'id'    => 'exclude_outofstock',
						'type'  => 'switch',
						'label' => __( 'Exclude out of stock items', 'wpify-woo' ),
						'desc'  => __( 'Check to exclude out of stock items.', 'wpify-woo' ),
				),
				array(
						'id'    => 'item_id_custom_field',
						'type'  => 'text',
						'label' => __( 'ITEM_ID custom field', 'wpify-woo' ),
						'desc'  => __( 'Product ID is used as default value for ITEM_ID. Enter custom field key if you want to use custom field value instead.', 'wpify-woo' ),
				),
				array(
						'id'    => 'ean_custom_field',
						'type'  => 'text',
						'label' => __( 'EAN custom field', 'wpify-woo' ),
						'desc'  => __( 'SKU is used as default value for EAN. Enter custom field key if you want to use custom field value instead.', 'wpify-woo' ),
				),
		);

		$settings[] = array(
				'id'    => 'delivery_methods_title',
				'type'  => 'title',
				'label' => __( 'Delivery methods', 'wpify-woo' ),
				'desc'  => __( 'Select the delivery methods and prices', 'wpify-woo' ),
		);

		$settings[] = array(
				'id'      => 'delivery_methods',
				'type'    => 'group',
				'label'   => __( 'Delivery methods', 'wpify-woo' ),
				'multi'   => true,
				'min'     => 0,
				'buttons' => array(
						'add'    => __( 'Add method', 'wpify-woo' ),
						'remove' => __( 'Remove method', 'wpify-woo' ),
				),
				'items'   => array(
						array(
								'id'      => 'method',
								'type'    => 'select',
								'label'   => __( 'Delivery method', 'wpify-woo' ),
								'desc'    => __( 'Select delivery method', 'wpify-woo' ),
								'options' => array( $this, 'get_heureka_delivery_methods_select' ),
						),
						array(
								'id'    => 'price',
								'type'  => 'text',
								'label' => __( 'Price', 'wpify-woo' ),
								'desc'  => __( 'Enter price for delivery.', 'wpify-woo' ),
						),
						array(
								'id'    => 'price_cod',
								'type'  => 'text',
								'label' => __( 'Price COD', 'wpify-woo' ),
								'desc'  => __( 'Enter price for delivery with COD.', 'wpify-woo' ),
						),
				),
		);

		$settings[] = array(
				'id'    => 'map_categories_title',
				'type'  => 'title',
				'label' => __( 'Map categories', 'wpify-woo' ),
				'desc'  => __( 'Map WooCommerce categories to Heureka categories', 'wpify-woo' ),
		);

		$settings[] = array(
				'id'            => 'categories_languages',
				'type'          => 'multiselect',
				'multi'         => true,
				'desc'          => __( 'Select languages to show in the select bellow. Please make sure to save settings to show all the selected languages.', 'wpify-woo' ),
				'label'         => __( 'Categories languages', 'wpify-woo' ),
				'options'       => array(
						array(
								'label' => 'CZ',
								'value' => 'cz',
						),
						array(
								'label' => 'SK',
								'value' => 'sk',
						),
				),
				'default' => array(),
		);


		$settings[] = array(
				'id'    => 'update_categories_button',
				'type'  => 'button',
				'desc'  => __( 'Click to update the Heureka categories.', 'wpify-woo' ),
				'label' => __( 'Update Heureka categories', 'wpify-woo' ),
				'url'   => add_query_arg( array( 'wpify-woo-action' => 'update-heureka-categories' ), $this->get_settings_url() ),
		);

		$categories = get_terms( apply_filters( 'wpify_heureka_categories_assignment', array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) ) );

		if ( ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
				$settings[] = array(
						'id'      => 'heureka_category_' . $category->term_id,
						'label'   => sprintf( __( 'Heureka category for %s', 'wpify-woo' ), $category->name ),
						'type'    => 'select',
						'options' => array( $this, 'get_heureka_categories_list' ),
						'list_id' => 'wpify_woo_heureka_category',
						'async'   => true,
				);
			}
		}

		$settings[] = array(
				'id'             => 'generate_button',
				'type'           => 'generate_feed',
				'desc'           => sprintf(
						__( 'Click to regenerate feed. Make sure to save the settings before generating the feed.<br/>The feed will be available at <a href="%1$s" target="_blank"><code style="-webkit-user-select: all;user-select: all;">%1$s</code></a>.<br/>You can also setup cron job to <code style="-webkit-user-select: all;user-select: all;">%2$s</code> to regenerate the feed automatically.', 'wpify-woo' ),
						$this->feed->get_xml_url(),
						$this->plugin->get_api_manager()->get_rest_url() . '/feed/generate/heureka'
				),
				'label'          => __( 'Generate feed', 'wpify-woo' ),
				'feed_chunk_url' => $this->plugin->get_api_manager()->get_rest_url() . '/feed/chunk-generate/heureka',
		);

		return $settings;
	}

	public function get_heureka_delivery_methods_select() {
		$select = [];

		foreach ( $this->get_heureka_delivery_methods() as $id => $label ) {
			$select[] = [
					'value' => $id,
					'label' => $label,
			];
		}

		return $select;
	}

	public function get_heureka_delivery_methods() {
		return [
				'CESKA_POSTA'                      => 'Česká pošta - Balík Do ruky',
				'CESKA_POSTA_NAPOSTU_DEPOTAPI'     => 'Česká pošta - Balík Na poštu',
				'CESKA_POSTA_DOPORUCENA_ZASILKA'   => 'Česká pošta - Doporučená zásilka',
				'CSAD_LOGISTIK_OSTRAVA'            => 'ČSAD Logistik Ostrava',
				'DPD'                              => 'DPD (nejedná se o DPD ParcelShop)',
				'DPD_PICKUP'                       => 'DPD Pickup',
				'DHL'                              => 'DHL',
				'DSV'                              => 'DSV',
				'FOFR'                             => 'FOFR',
				'GEBRUDER_WEISS'                   => 'Gebrüder Weiss',
				'GEIS'                             => 'Geis (nejedná se o Geis Point)',
				'GLS'                              => 'GLS',
				'GLS_PARCELSHOP'                   => 'GLS parcel shop',
				'HDS'                              => 'HDS',
				'PPL'                              => 'PPL',
				'PPL_PARCELSHOP'                   => 'PPL parcel shop',
				'SEEGMULLER'                       => 'Seegmuller',
				'TNT'                              => 'TNT',
				'UPS'                              => 'UPS',
				'FEDEX'                            => 'FEDEX',
				'RABEN_LOGISTICS'                  => 'Raben Logistics',
				'ZASILKOVNA'                       => 'Zásilkovna',
				'ZASILKOVNA_NA_ADRESU'             => 'Zásilkovna na adresu (CZ)',
				'ZASIELKOVNA_NA_ADRESU'            => 'Zásielkovňa na adresu (SK)',
				'BALIKOVNA_DEPOTAPI'               => 'Balíkovna',
				'WEDO'                             => 'WeDo (IN TIME)',
				'ULOZENKA'                         => 'Uloženka by WeDo',
				'SLOVENSKA_POSTA'                  => 'Slovenská pošta - Balík na adresu',
				'SLOVENSKA_POSTA_NAPOSTU_DEPOTAPI' => 'Slovenská pošta - Balík na poštu',
				'EXPRES_KURIER'                    => 'Expres Kuriér',
				'INTIME'                           => 'InTime',
				'REMAX'                            => 'ReMax Courier Service',
				'TOPTRANS'                         => 'TOPTRANS',
				'SDS'                              => 'SDS',
				'SPS'                              => 'SPS',
				'SPS_PARCELSHOP'                   => 'SPS parcel shop',
				'123KURIER'                        => '123KURIER',
				'PALETEXPRESS'                     => 'PaletExpress',
				'RHENUS_LOGISTICS'                 => 'Rhenus Logistics',
				'VLASTNI_PREPRAVA'                 => 'Vlastní přeprava (CZ)',
				'VLASTNA_PREPRAVA'                 => 'Vlastná preprava (SK)',
		];
	}

	public function get_heureka_categories( $lang = '' ) {
		$categories_languages = $this->get_setting( 'categories_languages', true );

		if ( empty( $categories_languages ) || $lang === 'cz' || $lang === 'cs' ||
			 ( count( $categories_languages ) === 1 && $categories_languages[0] === 'cz' )
		) {
			return get_option( 'wpify_woo_heureka_xml_categories', [] );
		}

		if ( $lang ) {
			return get_option( 'wpify_woo_heureka_xml_categories_' . $lang, [] );
		}

		$categories = [];

		foreach ( $categories_languages as $language ) {
			if ( $language === 'cz' ) {
				$categories = array_merge( $categories, get_option( 'wpify_woo_heureka_xml_categories', [] ) );
			} else {
				$categories = array_merge( $categories, get_option( 'wpify_woo_heureka_xml_categories_' . $language, [] ) );
			}
		}

		return $categories;
	}

	private function categories_to_select( $categories = array() ) {
		$select = array();

		foreach ( $categories as $id => $category ) {
			$select[] = array(
					'label' => $category['category_fullname'] ?: $category['category_name'],
					'value' => strval( $category['category_id'] ),
			);
		}

		return $select;
	}

	public function handle_actions() {
		if ( isset( $_GET['wpify-woo-action'] ) && 'update-heureka-categories' === $_GET['wpify-woo-action'] ) {
			$this->update_heureka_categories();
		}
	}

	public function update_heureka_categories() {
		$xmls = array(
				array(
						'lang'        => 'cz',
						'url'         => 'https://www.heureka.cz/direct/xml-export/shops/heureka-sekce.xml',
						'option_name' => 'wpify_woo_heureka_xml_categories',
				),
				array(
						'lang'        => 'sk',
						'url'         => 'https://www.heureka.sk/direct/xml-export/shops/heureka-sekce.xml',
						'option_name' => 'wpify_woo_heureka_xml_categories_sk',
				),
		);

		foreach ( $xmls as $xml ) {
			$this->temp_categories = [];
			$response_xml_data     = file_get_contents( $xml['url'] );
			if ( $response_xml_data === false ) {
				// Try CURL
				$response_xml_data = $this->file_get_contents_curl( $xml['url'] );
			}
			if ( ! $response_xml_data ) {
				wp_die( __( 'Downloading of the categories XML failed, please contact your hosting provider.', 'wpify-woo' ) );
			}

			$feed = simplexml_load_string( $response_xml_data );
			foreach ( $feed->CATEGORY as $first_level ) {
				$id                                                = (string) $first_level->CATEGORY_ID;
				$name                                              = (string) $first_level->CATEGORY_NAME;
				$this->temp_categories[ $id ]['category_id']       = $id;
				$this->temp_categories[ $id ]['category_name']     = $name;
				$this->temp_categories[ $id ]['category_fullname'] = '';
				$this->build_categories( $first_level->CATEGORY, $id );
			}
			update_option( $xml['option_name'], $this->temp_categories );
		}
	}

	/**
	 * Helper to get file with CURL instead of file_get_contents
	 *
	 * @param $url
	 *
	 * @return bool|string
	 */
	public function file_get_contents_curl( $url ) {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );

		$data = curl_exec( $ch );
		curl_close( $ch );

		return $data;
	}

	public function build_categories( $data, $category_id ) {
		if ( ! empty( $data ) ) {
			foreach ( $data as $item ) {
				$item_id       = (string) $item->CATEGORY_ID;
				$item_name     = (string) $item->CATEGORY_NAME;
				$item_fullname = (string) $item->CATEGORY_FULLNAME;

				if ( ! empty( $item_fullname ) ) {
					$this->temp_categories[ $item_id ]['category_id']       = $item_id;
					$this->temp_categories[ $item_id ]['category_name']     = $item_name;
					$this->temp_categories[ $item_id ]['category_fullname'] = $item_fullname;
				}

				$this->build_categories( $item->CATEGORY, $category_id );
			}
		}
	}

	/**
	 * @return Feed
	 */
	public function get_feed(): Feed {
		return $this->feed;
	}

	/**
	 * @param array $list
	 * @param array $params
	 */
	public function get_heureka_categories_list( $params ) {
		$categories = $this->get_heureka_categories();
		$search     = sanitize_title( $params['search'] );
		$search_ids = is_array( $params['value'] )
				? array_map( 'strval', $params['value'] )
				: array( strval( $params['value'] ) );

		if ( ! empty( $search_ids ) ) {
			foreach ( $categories as $category ) {
				if ( in_array( strval( $category['category_id'] ), $search_ids ) ) {
					$list[] = $category;
				}
			}
		}

		if ( ! empty( $search ) ) {
			foreach ( $categories as $category ) {
				if (
						( strpos( sanitize_title( $category['category_name'] ), $search ) !== false
						  || strpos( sanitize_title( $category['category_fullname'] ), $search ) !== false
						)
						&& ! in_array( strval( $category['category_id'] ), $search_ids )
				) {
					$list[] = $category;
				}
			}
		}

		if ( empty( $search ) && empty( $search_ids ) ) {
			$list = $categories;
		}

		return array_values(
				$this->categories_to_select(
						array_slice( $list, 0, 50 )
				)
		);
	}
}
