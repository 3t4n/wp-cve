<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsBase;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Base;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Utils\NoticeUtilsTrait;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsBase\SettingsUtilsTrait;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsBase\SettingsFormTrait;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsBase\SettingsSubmitTrait;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields\Field;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\SettingsFields\RepeaterField;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Utils\GeneralUtilsTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings Class
 */
abstract class Settings extends Base {

	use GeneralUtilsTrait, NoticeUtilsTrait, SettingsUtilsTrait, SettingsSubmitTrait, SettingsFormTrait;

	/**
	 * Settings ID.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Settings Key.
	 *
	 * @var string
	 */
	protected $settings_key;

	/**
	 * Settings
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 * Settings Fields Object.
	 *
	 * @var array
	 */
	protected $settings_fields;

	/**
	 * Default Settings
	 *
	 * @var array
	 */
	protected $default_settings = array();

	/**
	 * Default Settings Fields
	 *
	 * @var array
	 */
	protected $default_settings_fields = array();

	/**
	 * Allow Direct Submit.
	 *
	 * @var boolean
	 */
	protected $allow_direct_submit = true;

	/**
	 * Allow AJAX Save.
	 *
	 * @var boolean
	 */
	protected $allow_ajax_submit = false;

	/**
	 * User Cap to save.
	 *
	 * @var string
	 */
	protected $cap = 'administrator';

	/**
	 * Is the Settings autoloaded.
	 *
	 * @var boolean
	 */
	protected $autoload = false;

	/**
	 * Is WooCommerce Settings.
	 *
	 * @var boolean
	 */
	protected $is_woocommerce = false;

	/**
	 * Settings Nonce.
	 *
	 * @var string
	 */
	protected $nonce;

	/**
	 * Tab Key.
	 *
	 * @var string
	 */
	protected $tab_key = 'tab';

	/**
	 * Settings Fields.
	 *
	 * @var array
	 */
	protected $fields = array();

	/**
	 * Is CPT settings.
	 *
	 * @var boolean
	 */
	protected $is_cpt = false;

	/**
	 * Settings Constructor.
	 */
	public function __construct() {
		$this->setup();
		$this->base_hooks();
	}

	/**
	 * Init Settings.
	 *
	 * @return object
	 */
	public static function init() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Hooks function.
	 *
	 * @return void
	 */
	public function base_hooks() {
		if ( $this->is_cpt ) {
			add_action( 'save_post_' . $this->is_cpt, array( $this, 'submit_save_cpt_settings' ) );
		} else {
			add_action( $this->id . '-form-submit', array( $this, 'submit_save_settings' ) );
		}

		if ( $this->allow_ajax_submit ) {
			add_action( 'wp_ajax_' . $this->id, array( $this, 'ajax_save_settings' ) );
		}

		if ( $this->is_woocommerce ) {
			add_action( $this->id . '-form-close-submit-fields', array( $this, 'woo_submit_fields' ) );
		}

		if ( method_exists( $this, 'hooks' ) ) {
			$this->hooks();
		}

		add_action( 'wp_ajax_' . $this->id . '-' . '-get-repeater-item', array( $this, 'ajax_get_repeater_item' ) );
		add_action( 'wp_ajax_' . self::$plugin_info['name'] . '-' . $this->id . '-select-products-search', array( $this, 'ajax_select_products_search' ) );

	}

	/**
	 * AJAX Get Repeater Row HTML.
	 *
	 * @return void
	 */
	public function ajax_get_repeater_item() {
		if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['nonce'] ), $this->id . '-settings-nonce' ) ) {
			$key        = ! empty( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
			$index      = ! empty( $_POST['index'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['index'] ) ) ) : 0;
			$rule_group = $this->get_default_repeater_field( $key, $index );
			$this->ajax_response( '', 'success', 200, 'get-rule-group', array( 'rule_group' => $rule_group ) );
		}
		$this->expired_response();
	}

	/**
	 * Ajax Select Products Search.
	 *
	 * @return void
	 */
	public function ajax_select_products_search() {
		if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['nonce'] ), $this->id . '-settings-nonce' ) ) {
			$term            = ! empty( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';
			$exclude         = ! empty( $_POST['exclude'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['exclude'] ) ) : array();
			$result          = array();
			$data_store      = \WC_Data_Store::load( 'product' );
			$ids             = $data_store->search_products( $term, '', true, false, absint( apply_filters( 'woocommerce_json_search_limit', 30 ) ), array(), $exclude );
			$product_objects = array_filter( array_map( 'wc_get_product', $ids ), 'wc_products_array_filter_readable' );
			$products        = array();

			foreach ( $product_objects as $product_object ) {
				$formatted_name = is_a( $product_object, '\WC_Product_variation' ) ? ( '#' . $product_object->get_id() . ' [' . $product_object->get_name() . '] ' . ( $product_object->get_sku() ? ' (' . $product_object->get_sku() . ')' : '' ) ) : $product_object->get_formatted_name();
				$products[]     = array(
					'id'    => $product_object->get_id(),
					'title' => rawurldecode( $formatted_name ),
					'url'   => get_permalink( $product_object->get_id() ),
				);
			}

			$result = $products;

			wp_send_json( $result );
		}
		$this->expired_response();
	}

	/**
	 * Setup Settings.
	 *
	 * @return void
	 */
	public function setup() {
		$this->prepare();
		$this->after_prepare();
	}

	/**
	 * After Preparing Settings.
	 *
	 * @return void
	 */
	private function after_prepare() {
		$this->prepare_default_settings();
		$this->settings_key = $this->id . '-settings-key';
		$this->settings     = $this->get_settings();
		$this->nonce        = wp_create_nonce( $this->id . '-settings-nonce' );
	}

	/**
	 * Get Settings Assets.
	 *
	 * @return array
	 */
	public function get_settings_assets() {
		return 	array(
			'type'   => 'js',
			'handle' => self::$plugin_info['name'] . '-settings-actions',
			'url'    => self::$plugin_info['url'] . 'assets/libs/settings.min.js',
			'localized' => array(
				'name' => 'gpls_core_settings_actions',
				'data' => array(
					'prefix'  => self::$plugin_info['classes_prefix'],
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'labels'  => array(
						'remove_item' => sprintf( esc_html__( 'This item will be removed, proceed?', '%s' ), self::$plugin_info['name'] ),
						'search'      => sprintf( esc_html__( 'Search...', '%s' ), self::$plugin_info['name'] )
					),
					'actions' => array(
						'repeater_item_added' => esc_html__( $this->id . '-repeater-item-added' ),
						'search_woo_products' => self::$plugin_info['name'] . '-' . $this->id . '-select-products-search',
						'search_woo_taxs'     => 'woocommerce_json_search_taxonomy_terms',
					),
					'nonces' => array(
						'settings_nonce'           => wp_create_nonce( $this->id . '-settings-nonce' ),
						'search_woo_taxs_nonce'    => wp_create_nonce( 'search-taxonomy-terms' ),
					),
				)
			)
		);
	}

	/**
	 * Set ID and Settings Fields.
	 *
	 * @return void
	 */
	abstract protected function prepare();

	/**
	 * Get Default Settings.
	 *
	 * @return array
	 */
	protected function get_default_settings() {
		return $this->default_settings;
	}

	/**
	 * Prepare Default settings.
	 *
	 * @return void
	 */
	protected function prepare_default_settings() {
		$prepared_settings        = array();
		$prepared_settings_fields = array();

		foreach ( $this->fields as $tab_name => &$sections ) {
			foreach ( $sections as $section_name => &$section_settings ) {
				if ( ! empty( $section_settings['settings_list'] ) ) {
					foreach ( $section_settings['settings_list'] as $setting_name => &$setting_arr ) {
						$prepared_settings[ $setting_name ]                   = $setting_arr['value'];
						$prepared_settings_fields[ $setting_name ]            = $setting_arr;
						$prepared_settings_fields[ $setting_name ]['base_id'] = $this->id;
						$prepared_settings_fields[ $setting_name ]['key']     = $setting_name;
						$prepared_settings_fields[ $setting_name ]['filter']  = $setting_name;

						// Repeater Field.
						if ( 'repeater' === $setting_arr['type'] ) {
							foreach ( $setting_arr['default_subitem'] as $repeater_field_name => &$repeater_field_arr ) {
								$repeater_field_arr['filter'] = $setting_name . '-' . $repeater_field_name;
							}
						}
					}
				}
			}
		}

		$this->default_settings        = $prepared_settings;
		$this->default_settings_fields = $prepared_settings_fields;
	}

	/**
	 * Get Option Settings.
	 *
	 * @param string $main_key
	 * @return array
	 */
	private function get_option_settings() {
		return (array) maybe_unserialize( get_option( $this->settings_key, $this->default_settings ) );
	}

	/**
	 * Get CPT Settings.
	 *
	 * @param int    $post_id
	 * @param string $main_key
	 * @return array
	 */
	private function get_cpt_settings( $post_id = null ) {
		if ( is_null( $post_id ) ) {
			global $post_id;
		}

		if ( ! $post_id ) {
			return array();
		}

		return get_post_meta( $post_id, $this->settings_key, true );
	}

	/**
	 * Get Settings Values.
	 *
	 * @return array|string
	 */
	public function get_settings( $main_key = null, $post_id = null ) {
		if ( $this->is_cpt ) {
			$settings = $this->get_cpt_settings( $post_id );
		} else {
			$settings = $this->get_option_settings();
		}
		if ( $settings ) {
			$settings = array_replace_recursive( $this->default_settings, $settings );
		} else {
			$settings = $this->default_settings;
		}

		// Handle sub-fields.
		foreach ( $this->default_settings_fields as $field_name => $field_arr ) {
			if ( ! empty( $field_arr['default_subitem'] ) ) {
				foreach ( $settings[ $field_name ] as $index => $subfield ) {
					$settings[ $field_name ][ $index ] = array_merge( $field_arr['default_subitem'], $subfield );
				}
			}
		}

		if ( ! is_null( $main_key ) ) {
			if ( ! isset( $settings[ $main_key ] ) ) {
				return false;
			}
			return $settings[ $main_key ];
		}
		return $settings;
	}
}
