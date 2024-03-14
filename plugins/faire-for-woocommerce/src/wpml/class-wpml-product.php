<?php

/**
 * WPML WCML Products.
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Wpml;

use Faire\Wc\Admin\Settings;
use Faire\Wc\Admin\Product\Product as WC_Product;

class WPML_Product {

	/**
	 * Instance of Faire\Wc\Admin\Settings class.
	 *
	 * @var Settings
	 */
	private Settings $settings;

	/**
	 * Array of languages enabled for faire sync
	 *
	 * @var array<string>
	 */
	private array $languages;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		$this->settings  = new Settings();
		$this->languages = $this->enabled_languages();

		// Adds disable and readonly attribute to product fields.
		add_action( 'admin_footer', array( $this, 'lock_product_fields' ), 11 );

		// Adds disable and readonly attribute to product variation fields on ajax load of variation.
		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'lock_variation_fields_ajax_load' ), 11 );

		// Overrides Products admin columns.
		add_filter( 'faire_wc_products_admin_column_sync', array( $this, 'column_sync' ), 10, 2 );
		add_filter( 'faire_wc_products_admin_column_lifecycle', array( $this, 'column_lifecycle' ), 10, 2 );

		// Hook to disallow faire sync on translatead products.
		add_filter( 'faire_wc_product_is_sync_allowed', array( $this, 'is_sync_allowed' ), 10, 2 );

		// Hook to exclude translated products durring product linking.
		add_filter( 'faire_wc_get_product_ids_by_sku', array( $this, 'filter_linking_product_ids_by_sku' ), 10 );
	}

	/**
	 * Sets enabled faire sync languages.
	 *
	 * @return array<string>
	 */
	public function enabled_languages() {
		$languages = array();
		if ( $this->settings->get_brand_locale() ) {
			$languages[] = explode( '_', str_replace( '-', '_', $this->settings->get_brand_locale() ) )[0];
		}
		return apply_filters( 'faire_wc_wpml_enabled_languages', $languages );
	}

	/**
	 * Checks a product if enabled for faire sync.
	 *
	 * @param int $id Product id.
	 * @return bool
	 */
	public function is_faire_enabled_on_product( $id ) {
		$post_lang = apply_filters( 'wpml_post_language_details', null, $id );
		$locale    = isset( $post_lang['locale'] ) ? $post_lang['locale'] : get_locale();
		$language  = explode( '_', str_replace( '-', '_', $locale ) )[0];
		$enabled   = ( $locale && $this->is_faire_enabled_lang( $language ) ) ? true : false;

		return apply_filters( 'faire_wc_wpml_enabled_product', $enabled, $id );
	}

	/**
	 * Checks a language code if enable for faire sync
	 *
	 * @param string $language_code Two letter language code
	 * @return bool
	 */
	public function is_faire_enabled_lang( $language_code ) {
		return ( $language_code && $this->languages && in_array( $language_code, $this->languages, true ) ) ? true : false;
	}

	/**
	 * Lock user from editing faire product fields using js.
	 * Based loosely on WPML functions.
	 *
	 * @return void
	 */
	public function lock_product_fields() {
		global $pagenow;

		$product_id       = 0;
		$new_product_lang = '';
		if ( $pagenow === 'post.php' && isset( $_GET['post'] ) && 'product' === get_post_type( $_GET['post'] ) ) {
			$product_id = $_GET['post'];
		} elseif ( $pagenow === 'post-new.php' && isset( $_GET['trid'] ) && 'product' === get_post_type( $_GET['trid'] ) ) {
			$new_product_lang = isset( $_GET['lang'] ) ? $_GET['lang'] : '';
		} elseif ( isset( $_POST['product_id'] ) ) {
			$product_id = $_POST['product_id'];
		}

		// Disable fields if not enabled language.
		$disable_fields = false;
		if ( $product_id ) {
			if ( ! $this->is_faire_enabled_on_product( $product_id ) && 'auto-draft' !== get_post_status( $product_id ) ) {
				$disable_fields = true;
			}
		} elseif ( $new_product_lang ) {
			if ( ! $this->is_faire_enabled_lang( $new_product_lang ) ) {
				$disable_fields = true;
			}
		}

		// Lock fields if disabled.
		if ( true === $disable_fields ) :
			?>
				<script type="text/javascript">
					jQuery('[name^="<?php echo esc_attr( WC_Product::PRODUCT_FIELDS_PREFIX ); ?>"]').each(function() {
						if (jQuery(this).is('select') && !jQuery(this).prop('disabled')) {
							jQuery(this).prop('disabled', true);
							jQuery(this).prop('readonly', true);
							jQuery(this).after(jQuery('.wcml_lock_img').clone().removeClass('wcml_lock_img').show());
						} else if (!jQuery(this).prop('readonly')) {
							jQuery(this).prop('readonly', true);
							jQuery(this).after(jQuery('.wcml_lock_img').clone().removeClass('wcml_lock_img').show());
						}
					});
				</script>
			<?php
		endif;
	}

	/**
	 * Lock user from editing faire product fields using js.
	 * Based loosely on WPML functions.
	 *
	 * @return void
	 */
	public function lock_variation_fields_ajax_load() {

		// Get the product ID.
		$product_id = 0;
		if ( isset( $_GET['post'] ) && 'product' === get_post_type( $_GET['post'] ) ) {
			$product_id = $_GET['post'];
		} elseif ( isset( $_POST['action'], $_POST['product_id'] ) && 'woocommerce_load_variations' === $_POST['action'] ) {
			$product_id = $_POST['product_id'];
		} elseif ( isset( $_POST['action'], $_POST['post_id'] ) && 'woocommerce_add_variation' === $_POST['action'] ) {
			$product_id = $_POST['post_id'];
		}

		// Disable fields if not enabled language.
		$disable_fields = false;
		if ( $product_id ) {
			if ( ! $this->is_faire_enabled_on_product( $product_id ) && 'auto-draft' !== get_post_status( $product_id ) ) {
				$disable_fields = true;
			}
		}

		// Lock fields if disabled.
		if ( true === $disable_fields ) {
			?>
				<script type="text/javascript">
					jQuery('[name^="<?php echo esc_attr( WC_Product::PRODUCT_FIELDS_PREFIX ); ?>"]').each(function() {
						if (jQuery(this).is('select') && !jQuery(this).prop('disabled')) {
							jQuery(this).prop('disabled', true);
							jQuery(this).prop('readonly', true);
							jQuery(this).after(jQuery('.wcml_lock_img').clone().removeClass('wcml_lock_img').show());
						} else if (!jQuery(this).prop('readonly')) {
							jQuery(this).prop('readonly', true);
							jQuery(this).after(jQuery('.wcml_lock_img').clone().removeClass('wcml_lock_img').show());
						}
					});
				</script>
			<?php
		}
	}
	/**
	 * Output sync column
	 *
	 * @param  string $output
	 * @param  int $id
	 *
	 * @return string
	 */
	public function column_sync( $output, $id ) {
		if ( $id && ! $this->is_faire_enabled_on_product( $id ) ) {
			$output = __( 'This is a translation of a product.', 'faire-for-woocommerce' );
		}
		return $output;
	}

	/**
	 * Output lifecycle column
	 *
	 * @param  string $output
	 * @param  int $id
	 *
	 * @return string
	 */
	public function column_lifecycle( $output, $id ) {
		if ( $id && ! $this->is_faire_enabled_on_product( $id ) ) {
			$output = '-';
		}
		return $output;
	}

	/**
	 * Disallow Product sync on disabled languages
	 *
	 * @param  bool $allowed
	 * @param  int $id
	 *
	 * @return bool
	 */
	public function is_sync_allowed( $allowed, $id ) {
		if ( $id && ! $this->is_faire_enabled_on_product( $id ) ) {
			return false;
		}
		return $allowed;
	}

	/**
	 * During Product linking, filter products for enabled languages
	 *
	 * @param  array<int> $ids Array with Product IDs
	 *
	 * @return array<int>
	 */
	public function filter_linking_product_ids_by_sku( $ids ) {

		$filtered_ids = array();
		foreach ( $ids as $id ) {
			if ( $id && $this->is_faire_enabled_on_product( $id ) ) {
				$filtered_ids[] = $id;
			}
		}

		return $filtered_ids;
	}

}
