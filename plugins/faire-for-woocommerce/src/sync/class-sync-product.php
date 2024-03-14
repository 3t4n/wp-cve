<?php
/**
 * Sync Product
 *
 * @package  FAIRE
 */

namespace Faire\Wc\Sync;

use Exception;
use Faire\Wc\Admin\Settings;
use Faire\Wc\Api\Product_Api;
use Faire\Wc\Utils;
use Faire\Wc\Sync\Sync_Product_Scheduler;
use Faire\Wc\Sync\Sync_Product_Unlinking;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sync Product class.
 */
class Sync_Product {

	/**
	 *  Instance of Faire\Wc\Api\Product_Api class.
	 *
	 * @var Product_Api
	 */
	protected $product_api;

	/**
	 * The settings.
	 *
	 * @var Settings
	 */
	protected Settings $settings;

	/**
	 * The scheduler.
	 *
	 * @var Sync_Product_Scheduler
	 */
	private Sync_Product_Scheduler $scheduler;

	/**
	 * Name of the Faire product ID meta field.
	 *
	 * @var string
	 */
	private string $meta_faire_product_id;

	/**
	 * Name of the Faire variant ID meta field.
	 *
	 * @var string
	 */
	private string $meta_faire_variant_id;

	/**
	 * Options and Meta Keys
	 */

	public const OPTION_FAIRE_PRODUCTS_LAST_SYNC_SUMMARY = '_faire_products_last_sync_summary';
	public const META_FAIRE_PRODUCT_SYNC_RESULT          = '_faire_product_sync_result';
	public const FAIRE_PRODUCT_COLUMN                    = 'faire_product';
	public const FAIRE_PRODUCT_LIFECYCLE_COLUMN          = 'faire_product_lifecycle';

	public const DEFAULT_FAIRE_LIFECYCLE_STATE = 'UNPUBLISHED';

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->product_api = new Product_Api();
		$this->settings    = new Settings();
		$this->scheduler   = new Sync_Product_Scheduler(
			array( $this, 'run_faire_product_scheduled_sync_event' ),
			$this->settings
		);

		$this->meta_faire_product_id = $this->settings->get_meta_faire_product_id();
		$this->meta_faire_variant_id = $this->settings->get_meta_faire_variant_id();

		// Product.
		add_action( 'woocommerce_new_product', array( $this, 'on_create_product' ), 10, 2 );
		add_action( 'woocommerce_update_product', array( $this, 'on_update_product' ), 10, 2 );
		add_action( 'before_delete_post', array( $this, 'on_delete_post_maybe_product' ), 10, 1 );
		add_action( 'wp_trash_post', array( $this, 'on_delete_post_maybe_product' ), 10, 1 );
		add_filter( 'woocommerce_duplicate_product_exclude_meta', array( $this, 'duplicate_exclude_meta' ), 10, 2 );

		// Adds custom  meta-boxes.
		add_action( 'admin_init', array( $this, 'add_product_log_metaboxes' ) );

		// Adds Faire custom columns.
		add_filter( 'manage_edit-product_columns', array( $this, 'add_faire_product_columns' ), 20 );
		add_action( 'manage_product_posts_custom_column', array( $this, 'show_faire_product_columns' ), 20, 2 );

		// Add Faire bulk actions.
		add_filter( 'bulk_actions-edit-product', array( $this, 'bulk_actions' ), 20, 1 );
		add_filter( 'handle_bulk_actions-edit-product', array( $this, 'handle_bulk_actions' ), 10, 3 );
		add_action( 'admin_notices', array( $this, 'bulk_actions_notice' ) );
		add_action( 'woocommerce_product_bulk_edit_start', array( $this, 'bulk_edit_fields' ) );
		add_action( 'woocommerce_product_bulk_edit_save', array( $this, 'bulk_edit_fields_save' ), 10, 1 );

		// Handles the Ajax call to sync products.
		add_action( 'wp_ajax_faire_products_manual_sync', array( $this, 'ajax_all_products_manual_sync' ) );
		add_action( 'wp_ajax_faire_single_product_manual_sync', array( $this, 'ajax_single_product_manual_sync' ) );

		// Stock related.
		add_action( 'woocommerce_variation_set_stock', array( $this, 'product_variation_set_stock' ), 10, 1 );
		add_action( 'woocommerce_product_set_stock', array( $this, 'product_simple_set_stock' ), 10, 1 );
		add_action( 'woocommerce_variation_set_stock_status', array( $this, 'product_variation_set_stock_status' ), 10, 3 );
		add_action( 'woocommerce_product_set_stock_status', array( $this, 'product_simple_set_stock_status' ), 10, 3 );

	}

	/**
	 * Adds page meta-boxes.
	 */
	public function add_product_log_metaboxes() {
		add_meta_box(
			'faire_product_log',
			'Faire Sync Log',
			array( $this, 'output_product_log_metabox' ),
			'product',
			'normal',
			'default'
		);
	}

	/**
	 * Adds Faire custom columns
	 */
	public function add_faire_product_columns( array $columns ): array {
		$updated_columns = array();

		foreach ( $columns as $key => $column ) {
			$updated_columns[ $key ] = $column;
			if ( 'sku' === $key ) {
				$updated_columns[ self::FAIRE_PRODUCT_LIFECYCLE_COLUMN ] =
					__( 'Faire State', 'faire-for-woocommerce' );
				$updated_columns[ self::FAIRE_PRODUCT_COLUMN ]           =
					__( 'Faire Product', 'faire-for-woocommerce' );
			}
		}
		return $updated_columns;
	}

	/**
	 * Display Faire product custom post columns
	 *
	 * @param string $column Colum name.
	 * @param int    $id     post ID.
	 */
	public function show_faire_product_columns( string $column, int $id ) {

		if ( self::FAIRE_PRODUCT_COLUMN === $column ) {

			$output_column = '';

			if ( ! $this->is_product_faire_sync_allowed( $id ) ) {

				$product = wc_get_product( $id );
				if ( $product && in_array( $product->get_type(), array( 'grouped', 'external' ) ) ) {
					$output_column = sprintf(
						__( 'This is a product type: %s.', 'faire-for-woocommerce' ),
						$product->get_type()
					);
				} else {
					$output_column = '-';
				}
			} else {

						  $faire_product_id      = $this->get_faire_product_id( $id );
						  $linking_error_message = $this->display_product_linking_error( $id );

				if ( $linking_error_message ) {
					if ( $faire_product_id ) {
							$output_column .= esc_html__( 'ID:', 'faire-for-woocommerce' ) . ' <span class="wc_faire_product_faire_id">' . esc_html( $faire_product_id ) . '</span><br>';
					}
					$output_column .= $linking_error_message;

				} else {

					if ( ! $faire_product_id ) {
									$faire_product_id = esc_html__( 'None', 'faire-for-woocommerce' );
					}

					$log_entries   = get_post_meta( $id, self::META_FAIRE_PRODUCT_SYNC_RESULT, false ); // get all.
					$last_sync_msg = esc_html__( 'None', 'faire-for-woocommerce' );
					if ( is_array( $log_entries ) ) {
						$last_entry = array_pop( $log_entries );
						if ( $last_entry && isset( $last_entry['success'] ) ) {
							$entry_result  = ( true === $last_entry['success'] ) ? esc_html__( 'Success', 'faire-for-woocommerce' ) : esc_html__( 'Failed', 'faire-for-woocommerce' );
							$last_sync_msg = $entry_result;
						}
					}

					$output_column .= esc_html__( 'ID:', 'faire-for-woocommerce' ) . ' <span class="wc_faire_product_faire_id">' . esc_html( $faire_product_id ) . '</span>';
					$output_column .= '<br>' . esc_html__( 'Last Sync:', 'faire-for-woocommerce' ) . ' <span class="wc_faire_product_last_sync_result">' . wp_kses_post( $last_sync_msg ) . '</span>';
					$output_column .= '<br><button data-product="' . esc_attr( (string) $id ) . '" class="button-secondary wc_faire_manual_product_sync" type="button" name="wc_faire_manual_product_sync" style="margin: 3px 0">' . esc_html__( 'Sync Now', 'faire-for-woocommerce' ) . '</button>';
				}
			}

			echo apply_filters( 'faire_wc_products_admin_column_sync', $output_column, $id );

		} elseif ( self::FAIRE_PRODUCT_LIFECYCLE_COLUMN === $column ) {

			if ( ! $this->is_product_faire_sync_allowed( $id ) ) {

				$output_column = '-';

			} else {

				$product                = wc_get_product( $id );
				$lifecycle_state_string = $this->get_lifecycle_state_for_status_translated( $product );

				$output_column = '<span>' . esc_html( $lifecycle_state_string ) . '</span>';

			}

			echo apply_filters( 'faire_wc_products_admin_column_lifecycle', $output_column, $id );
		}

	}

	/**
	 * Display a product linking error if any exists on a product
	 *
	 * @param int $product_id            A WooCommerce product id
	 *
	 * @return string                   An error code string or empty string
	 */
	public function display_product_linking_error( $product_id ) {

		$linking_error = get_post_meta( $product_id, '_faire_product_linking_error', true );
		if ( $linking_error === 'multiple_matches' ) {
			$linking_error_faire_id = get_post_meta( $product_id, '_faire_product_linking_error_faire_id', true );
			return sprintf(
				__( 'Product Linking: Cannot link with faire product %s because it matches multiple products.', 'faire-for-woocommerce' ),
				$linking_error_faire_id
			);
		} elseif ( $linking_error === 'nonmatching_options' ) {
			$linking_error_faire_id = get_post_meta( $product_id, '_faire_product_linking_error_faire_id', true );
			return sprintf(
				__( 'Product Linking: Cannot link with faire product %s due to mismatched option sets.', 'faire-for-woocommerce' ),
				$linking_error_faire_id
			);
		} elseif ( $linking_error === 'manual_link_variants' ) {
			$unmatched_variants = get_post_meta( $product_id, '_faire_product_unmatched_variants', true );
			if ( $unmatched_variants ) {
				$linking_error_faire_id = get_post_meta( $product_id, '_faire_product_linking_error_faire_id', true );
				return sprintf(
					__( 'Product Linking: Requires manual linking variations with faire product %s variants.', 'faire-for-woocommerce' ),
					$linking_error_faire_id
				);
			}
		}
		return '';
	}

	/**
	 * Checking faire linking error requires us to skip faire product sync
	 *
	 * @return bool
	 */
	public function skip_linking_error_sync_product( $product ) {
		if ( $product->get_type() === 'variation' ) {
			$id = $product->get_parent_id();
		} else {
			$id = $product->get_id();
		}
		return (bool) $this->display_product_linking_error( $id );
	}


	/**
	 * Adds bulk actions.
	 *
	 * @param array $actions List of bulk actions.
	 *
	 * @return array Updated list of bulk actions.
	 */
	public function bulk_actions( $actions ) {
		$actions['faire_unpublished']    = __( 'Change Faire lifecycle state to unpublished', 'faire-for-woocommerce' );
		$actions['faire_published']      = __( 'Change Faire lifecycle state to published', 'faire-for-woocommerce' );
		$actions['faire_draft']          = __( 'Change Faire lifecycle state to draft', 'faire-for-woocommerce' );
		$actions['faire_unlink_product'] = __( 'Unlink from Faire product', 'faire-for-woocommerce' );
		return $actions;
	}

	/**
	 * Handle WP Admin products bulk actions
	 */
	public function handle_bulk_actions( $redirect_to, $action, $post_ids ) {
		if ( ! in_array( $action, array( 'faire_unpublished', 'faire_published', 'faire_draft', 'faire_unlink_product' ), true ) ) {
			return $redirect_to;
		}

		$processed_ids        = array();
		$bulk_lifecycle_state = '';

		// Handle bulk lifecycle changes
		if ( in_array( $action, array( 'faire_unpublished', 'faire_published', 'faire_draft' ) ) ) {

			if ( 'faire_unpublished' === $action ) {
				$bulk_lifecycle_state = 'UNPUBLISHED';
			} elseif ( 'faire_published' === $action ) {
				$bulk_lifecycle_state = 'PUBLISHED';
			} elseif ( 'faire_draft' === $action ) {
				$bulk_lifecycle_state = 'DRAFT';
			}

			// Filter post ids for products that are allowed sync
			$bulk_post_ids = array();
			foreach ( $post_ids as $post_id ) {
				if ( $this->is_product_faire_sync_allowed( $post_id ) ) {
					$bulk_post_ids[] = $post_id;
				}
			}

			foreach ( $bulk_post_ids as $post_id ) {

				$product = wc_get_product( $post_id );

				if ( 'simple' === $product->get_type() ) {
					$product->update_meta_data( 'woocommerce_faire_product_lifecycle_state', $bulk_lifecycle_state );
					$product->save();
				} elseif ( 'variable' === $product->get_type() ) {
					// Save child variation lifecycle state.
					$variations = $product->get_children(); // use get_children instead of get_available_variations() so that all are returned.
					if ( $variations ) {
						foreach ( $variations as $variation_id ) {
							$variation = wc_get_product( $variation_id );
							if ( $variation ) {
								$variation->update_meta_data( 'woocommerce_faire_product_variation_lifecycle_state', $bulk_lifecycle_state );
								$variation->save();
							}
						}
					}
					// Save parent product lifecycle state.
					$product->update_meta_data( 'woocommerce_faire_product_lifecycle_state', $bulk_lifecycle_state );
					$product->save();
				}

				$processed_ids[] = $post_id;
			}
		} elseif ( 'faire_unlink_product' === $action ) { // Handle bulk unlink product

				  // Filter post ids for products that are allowed sync
				  $unlink_ids = array();
			foreach ( $post_ids as $post_id ) {
				if ( $this->is_product_faire_sync_allowed( $post_id ) ) {
					  $unlink_ids[] = $post_id;
				}
			}

			$unlinking    = new Sync_Product_Unlinking( $this->settings );
			$unlinked_ids = $unlinking->unlink_products( $unlink_ids );

			$processed_ids = $unlinked_ids;
		}

		$redirect_to = add_query_arg(
			array(
				'faire_bulk_product' => $action,
				'processed_count'    => count( $processed_ids ),
				// 'processed_ids' => implode( ',', $processed_ids ),
			),
			$redirect_to
		);

		return $redirect_to;
	}

	/**
	 * Show an admin message after bulk actions
	 *
	 * @return void
	 */
	public function bulk_actions_notice() {
		if ( empty( $_REQUEST['faire_bulk_product'] ) ) {
			return; // Exit.
		}

		$action = sanitize_text_field( wp_unslash( $_REQUEST['faire_bulk_product'] ) );
		$count  = empty( $_REQUEST['processed_count'] ) ? 0 : intval( wp_unslash( $_REQUEST['processed_count'] ) );

		$message = '';
		if ( 'faire_unpublished' === $action ) {
			// translators: %s single product, %s product count.
			$message = sprintf( _n( '%s product set Lifecycle State to Unpublished.', '%s products set Lifecycle State to Unpublished.', $count, 'faire-for-woocommerce' ), $count );
		} elseif ( 'faire_published' === $action ) {
			// translators: %s single product, %s product count.
			$message = sprintf( _n( '%s product set Lifecycle State to Published.', '%s products set Lifecycle State to Published.', $count, 'faire-for-woocommerce' ), $count );
		} elseif ( 'faire_draft' === $action ) {
			// translators: %s single product, %s product count.
			$message = sprintf( _n( '%s product set Lifecycle State to Draft.', '%s products set Lifecycle State to Draft.', $count, 'faire-for-woocommerce' ), $count );
		} elseif ( 'faire_unlink_product' === $action ) {
			// translators: %s single product, %s product count.
			$message = sprintf( _n( '%s products unlinked.', '%s products were unlinked.', $count, 'faire-for-woocommerce' ), $count );
		}
		$output = $message ? sprintf( '<div id="message" class="updated fade"><p>%s</p></div>', $message ) : '';
		echo wp_kses_post( $output );
	}

	public function bulk_edit_fields() {
		$types         = get_option( 'faire_taxonomy_types', array() );
		$types_options = array(
			'' => __( '— No change —', 'faire-for-woocommerce' ),
		);
		if ( $types ) {
			$types_options = array_merge( $types_options, $types );
		}
		?>
		<div class="inline-edit-group bulk_change_faire_product_type">
			<label class="alignleft">
				<span class="title"><?php esc_html_e( 'Taxonomy Type (Faire)', 'faire-for-woocommerce' ); ?></span>
				<span class="input-text-wrap">
					<select class="wc-enhanced-select" name="change_product_faire_taxonomy_type">
						<?php foreach ( $types_options as $type_id => $type_name ) : ?>
							<option value="<?php echo esc_attr( $type_id ); ?>"><?php echo esc_html( $type_name ); ?></option>
						<?php endforeach; ?>
					</select>
				</span>
			</label>
		</div>
			<?php
	}

	public function bulk_edit_fields_save( $product ) {
		if ( empty( $_REQUEST['change_product_faire_taxonomy_type'] ) ) {
			return;
		}
		$product_faire_taxonomy_type = sanitize_text_field( wp_unslash( $_REQUEST['change_product_faire_taxonomy_type'] ) );
		update_post_meta( $product->get_id(), 'woocommerce_faire_product_taxonomy_type', $product_faire_taxonomy_type );
	}

	/**
	 * Output Faire product custom meta-box.
	 */
	public function output_product_log_metabox() {
		global $post;

		$log_entries = get_post_meta( $post->ID, self::META_FAIRE_PRODUCT_SYNC_RESULT, false );
		if ( ! $log_entries ) {
			echo '<p>' . esc_html__( 'No sync history.', 'faire-for-woocommerce' ) . '</p>';
			return;
		}

		$log_entries = array_reverse( $log_entries );

		echo '<div class="wc-faire-product-synclog" style="max-height: 200px; overflow: scroll; padding: 0 3px;">';
		foreach ( $log_entries as $entry ) {
			$entry_color  = ( true === $entry['success'] ) ? 'green' : 'red';
			$entry_bullet = '<span style="color: ' . $entry_color . ' !important; font-size: 18px !important; line-height: 18px !important;">&bull;</span>';
			$entry_msg    = $entry['action'];
			$entry_msg   .= ( $entry['message'] ) ? ': ' . $entry['message'] : '';

			if ( $entry['timestamp'] >= strtotime( '-1 week' ) ) {
				// translators: %s time past.
				$entry_date = sprintf( esc_html__( '%s ago', 'textdomain' ), human_time_diff( $entry['timestamp'] ) );
			} else {
				$entry_date = gmdate( 'r', $entry['timestamp'] );
			}
			echo '<p>' . $entry_bullet . ' ' . esc_html( $entry_msg ) . ' --- <em> ' . esc_html( $entry_date ) . '</em></p>';
		}
		echo '</div>';
	}

	/**
	 * On event: new product
	 *
	 * @return void
	 */
	public function on_create_product( $id, $product ) {

		// Prevent duplicate action events.
		global $faire_wc_prevent_dups_product_updated_id;
		if (
			isset( $faire_wc_prevent_dups_product_updated_id ) &&
			in_array( $product->get_id(), $faire_wc_prevent_dups_product_updated_id, true )
			) {
			return;
		}

		$faire_wc_prevent_dups_product_updated_id[] = $product->get_id();

		// Conditions.
		if ( wp_doing_ajax() ) {
			return;
		}
		if ( ! in_array( $product->get_type(), array( 'simple', 'variable' ), true ) ) {
			return;
		}
		if ( ! $this->is_product_faire_sync_allowed( $id ) ) {
			return;
		}

		$sync_mode = $this->settings->get_product_sync_mode();

		if ( 'sync_scheduled' === $sync_mode || 'do_not_sync' === $sync_mode ) {

			$this->scheduler->add_product_faire_pending_sync( $id, 'create' );

		} elseif ( 'sync_live' === $sync_mode ) {

			// Sync soon (scheduled in Action Scheduler)
			// $thirty_min_in_sec = 30 * 60;
			// $job_id = $this->scheduler->add_product_single_sync_queue( $id, 'create', $thirty_min_in_sec );
		}
	}

	/**
	 * On event: update product
	 *
	 * @return void
	 */
	public function on_update_product( $id, $product ) {

		// Prevent duplicate action events.
		global $faire_wc_prevent_dups_product_updated_id;
		if (
			isset( $faire_wc_prevent_dups_product_updated_id ) &&
			in_array( $product->get_id(), $faire_wc_prevent_dups_product_updated_id, true )
		) {
			return;
		}

		$faire_wc_prevent_dups_product_updated_id[] = $product->get_id();

		// Conditions.
		if ( wp_doing_ajax() ) {
			return;
		}
		if ( ! in_array( $product->get_type(), array( 'simple', 'variable' ), true ) ) {
			return;
		}
		if ( ! $this->is_product_faire_sync_allowed( $id ) ) {
			return;
		}

		$sync_mode = $this->settings->get_product_sync_mode();
		if ( 'sync_scheduled' === $sync_mode || 'do_not_sync' === $sync_mode ) {

			$this->scheduler->add_product_faire_pending_sync( $id, 'update' );

		} elseif ( 'sync_live' === $sync_mode ) {

			// Sync soon (scheduled in Action Scheduler)
			// $thirty_min_in_sec = 30 * 60;
			// $job_id = $this->scheduler->add_product_single_sync_queue( $id, 'update', $thirty_min_in_sec );

		}
	}

	/**
	 * On event: when deleting post, handle delete if product
	 *
	 * @return void
	 */
	public function on_delete_post_maybe_product( $id ) {
		$post = get_post( $id );
		if ( ! $post || 'product' !== $post->post_type ) {
			return;
		}

		// Get product.
		$product = wc_get_product( $id );

		if ( $product->is_type( 'variation' ) ) {
			if ( ! $this->is_product_faire_sync_allowed( $product->get_parent_id() ) ) {
				return;
			}
			$this->on_delete_product_variation( $id, $product );
		} else {
			if ( ! $this->is_product_faire_sync_allowed( $id ) ) {
				return;
			}
			$this->on_delete_product( $id, $product );
		}

	}

	/**
	 * On event: delete product variation
	 *
	 * @return void
	 */
	public function on_delete_product( $id, $product ) {

		// Prevent duplicate action events.
		global $faire_wc_prevent_dups_product_deleted_id;
		if (
			isset( $faire_wc_prevent_dups_product_deleted_id ) &&
			in_array( $product->get_id(), $faire_wc_prevent_dups_product_deleted_id, true ) ) {
			return;
		}

		$faire_wc_prevent_dups_product_deleted_id[] = $product->get_id();

		// Conditions.
		if ( wp_doing_ajax() ) {
			return;
		}
		if ( ! in_array( $product->get_type(), array( 'simple', 'variable' ), true ) ) {
			return;
		}
		if ( ! $this->is_product_faire_sync_allowed( $id ) ) {
			return;
		}

		$sync_mode = $this->settings->get_product_sync_mode();
		if ( 'sync_scheduled' === $sync_mode || 'do_not_sync' === $sync_mode ) {

			$this->scheduler->add_product_faire_pending_sync( $id, 'delete' );

		} else {
			// Sync soon (scheduled in Action Scheduler)
			// $thirty_min_in_sec = 30 * 60;
			// $job_id = $this->scheduler->add_product_single_sync_queue( $id, 'delete', $thirty_min_in_sec );
		}
	}

	/**
	 * On event: delete product variation
	 *
	 * @return void
	 */
	public function on_delete_product_variation( $id, $product ) {

		// Prevent duplicate action events.
		global $faire_wc_prevent_dups_product_deleted_id;
		if (
			isset( $faire_wc_prevent_dups_product_deleted_id ) &&
			in_array( $product->get_id(), $faire_wc_prevent_dups_product_deleted_id, true )
		) {
			return;
		}
		if ( ! $this->is_product_faire_sync_allowed( $id ) ) {
			return;
		}

		$faire_wc_prevent_dups_product_deleted_id[] = $product->get_id();

		// Conditions.
		if ( wp_doing_ajax() ) {
			return;
		}
		if ( ! in_array( $product->get_type(), array( 'variation' ), true ) ) {
			return;
		}

		$sync_mode = $this->settings->get_product_sync_mode();
		if ( 'sync_scheduled' === $sync_mode || 'do_not_sync' === $sync_mode ) {

			// Important: do not delete parent. Add parent id to update queue since this is a child product.
			$this->scheduler->add_product_faire_pending_sync( $product->get_parent_id(), 'update' );

		} else {
			// Sync soon (scheduled in Action Scheduler)
			// $thirty_min_in_sec = 30 * 60;
			// $job_id = $this->scheduler->add_product_single_sync_queue( $id, 'delete', $thirty_min_in_sec );
		}
	}

	/**
	 * On event: run a single sync event
	 *
	 * @return void
	 */
	public function run_faire_product_scheduled_sync_event( $action_type, $id = null ) {

		// Get id from action type string.
		if ( stripos( $action_type, 'update_faire_product' ) !== false ) {
			$id          = ltrim( $action_type, 'update_faire_product_' );
			$action_type = 'update_faire_product';
		} elseif ( stripos( $action_type, 'create_faire_product' ) !== false ) {
			$id          = ltrim( $action_type, 'create_faire_product_' );
			$action_type = 'create_faire_product';
		} elseif ( stripos( $action_type, 'delete_faire_product' ) !== false ) {
			$id          = ltrim( $action_type, 'delete_faire_product_' );
			$action_type = 'delete_faire_product';
		}

		if (
			'update_faire_product' === $action_type ||
			'create_faire_product' === $action_type ||
			'delete_faire_product' === $action_type
		) {

			$results = array();

			$product = wc_get_product( $id );
			if ( ! $product ) {
				$this->log_summary_product_sync_result( $id, 'failure', 404 );
			} else {

				if ( 'create_faire_product' === $action_type ) {
					$results = $this->save_faire_product( $id, $product );
				} elseif ( 'update_faire_product' === $action_type ) {
					$results = $this->save_faire_product( $id, $product );
				} elseif ( 'delete_faire_product' === $action_type ) {
					$results = $this->save_faire_product_deleted( $id, $product );
				}

				// Prepare results for log.
				if ( $results ) {

					// Log each result to single product page log.
					foreach ( $results as $result ) {

						if ( isset( $result->error ) ) {
							$log_label = ( isset( $result->error->label ) ) ? $result->error->label : __( 'Error', 'faire-for-woocommerce' );
							$this->log_product_sync_result( $product->get_id(), false, $log_label, $result->error->message );
						} else {
							$log_label = ( isset( $result->success->label ) ) ? $result->success->label : __( 'Success', 'faire-for-woocommerce' );
							$this->log_product_sync_result( $product->get_id(), true, $log_label );
						}
					}

					// Log only last result into summary on plugin Settings.
					if ( isset( $result->error ) ) {
						$error_code = ( isset( $result->error->code ) ) ? $result->error->code : 0;
						$this->log_summary_product_sync_result( $product->get_id(), 'failure', $error_code );
					} else {
						$this->log_summary_product_sync_result( $product->get_id(), 'success' );
					}
				}
			}
		} elseif ( 'sync_pending_faire_products' === $action_type ) {
			$queued_count = $this->run_pending_product_sync();
		}
	}

	/**
	 * On event: run pending product sync
	 *
	 * @return array results
	 */
	public function run_pending_product_sync() {
		$queued_count = 0;

		$create_ids = $this->scheduler->get_product_faire_pending_sync( 'create' );
		$update_ids = $this->scheduler->get_product_faire_pending_sync( 'update' );
		$delete_ids = $this->scheduler->get_product_faire_pending_sync( 'delete' );

		if ( ! $create_ids && ! $update_ids && ! $delete_ids ) {
			return 0;
		}

		// Sync Summary log reset.
		$this->settings->save_products_last_sync_date( gmdate( 'c' ) );
		$this->empty_log_summary_product_sync_result();

		// Create.
		if ( $create_ids ) {
			foreach ( $create_ids as $id ) {
				// Queue create.
				$job_id = $this->scheduler->add_product_single_sync_queue( $id, 'create' );
				$this->scheduler->remove_product_faire_pending_sync( $id, 'create' ); // Remove from pending.
				$queued_count++;
			}
		}

		// Update.
		if ( $update_ids ) {
			foreach ( $update_ids as $id ) {
				// Queue update, skip if already queued in create.
				if ( ! in_array( $id, $create_ids, true ) ) {
					$job_id = $this->scheduler->add_product_single_sync_queue( $id, 'update' );
				}
				$this->scheduler->remove_product_faire_pending_sync( $id, 'update' );
				$queued_count++;
			}
		}

		// Delete.
		if ( $delete_ids ) {
			foreach ( $delete_ids as $id ) {
				// Queue delete.
				$job_id = $this->scheduler->add_product_single_sync_queue( $id, 'delete' );
				$this->scheduler->remove_product_faire_pending_sync( $id, 'delete' );
				$queued_count++;
			}
		}

		// Set summary log queued entry.
		$this->log_summary_product_sync_result( $queued_count, 'queued' );

		return $queued_count;
	}

	/**
	 * Log a product sync result
	 */
	public function log_product_sync_result( $id, $success, $action, $message = '', $timestamp = null ) {
		if ( ! $timestamp ) {
			$timestamp = time();
		}
		$entry = array(
			'success'   => $success,
			'action'    => $action,
			'message'   => $message,
			'timestamp' => $timestamp,
		);
		add_post_meta( $id, self::META_FAIRE_PRODUCT_SYNC_RESULT, $entry );
	}

	/**
	 * Log summaries to product sync results
	 */
	public function log_summary_product_sync_result( $value, $type, $code = '', $timestamp = null ) {
		$summary              = get_option( self::OPTION_FAIRE_PRODUCTS_LAST_SYNC_SUMMARY, array() );
		$summary              = is_array( $summary ) ? $summary : array();
		$summary['timestamp'] = $timestamp ? $timestamp : time();

		// Log types.
		switch ( $type ) {
			case 'queued':
				$summary['queued'] = $value;
				break;
			case 'success':
				$summary['success'][] = $value;
				break;
			case 'failure':
				$index                           = in_array( (int) $code, array( 429, 500 ), true ) ? (string) $code . '_' : '';
				$summary[ $index . 'failure' ][] = $value;
				break;
		}

		update_option( self::OPTION_FAIRE_PRODUCTS_LAST_SYNC_SUMMARY, $summary );

		// Update display.
		$this->set_log_summary_product_sync_result();
	}

	public function set_log_summary_product_sync_result() {
		$summary = get_option( self::OPTION_FAIRE_PRODUCTS_LAST_SYNC_SUMMARY, array() );
		$summary = is_array( $summary ) ? $summary : array();

		$results = array();

		if ( ! isset( $summary['timestamp'] ) ) {
			return;
		}

		$queued_count = ( isset( $summary['queued'] ) ) ? (int) $summary['queued'] : 0;
		$results[]    = Utils::create_import_result_entry(
			true,
			// translators: %d number of products.
			sprintf( __( 'Sync queued for %d products', 'faire-for-wordpress' ), $queued_count )
		);
		$results[] = Utils::create_import_result_entry(
			true,
			// translators: %s sync date.
			sprintf( __( 'Last sync at %s', 'faire-for-wordpress' ), gmdate( 'c', $summary['timestamp'] ) )
		);
		if ( isset( $summary['success'] ) ) {
			$results[] = Utils::create_import_result_entry(
				true,
				// translators: %d number of products success.
				sprintf( __( 'Product Success = %s', 'faire-for-wordpress' ), count( $summary['success'] ) )
			);
		}
		if ( isset( $summary['failure'] ) ) {
			$results[] = Utils::create_import_result_entry(
				true,
				// translators: %d number of products failed.
				sprintf( __( 'Product Failures = %s', 'faire-for-wordpress' ), count( $summary['failure'] ) )
			);
		}
		if ( isset( $summary['500_failure'] ) ) {
			$results[] = Utils::create_import_result_entry(
				true,
				// translators: %d number of products failed.
				sprintf( __( '500 Failures = %s', 'faire-for-wordpress' ), count( $summary['500_failure'] ) )
			);
		}
		if ( isset( $summary['429_failure'] ) ) {
			$results[] = Utils::create_import_result_entry(
				true,
				// translators: %d number of products failed.
				sprintf( __( '429 Failures = %s', 'faire-for-wordpress' ), count( $summary['429_failure'] ) )
			);
		}
		$this->settings->save_product_sync_results( $results );
	}

	public function empty_log_summary_product_sync_result() {
		update_option( self::OPTION_FAIRE_PRODUCTS_LAST_SYNC_SUMMARY, array() );

		// Update display.
		$this->settings->save_product_sync_results( array() );
	}


	/**
	 * Handles Ajax requests to sync Faire products.
	 */
	public function ajax_all_products_manual_sync() {
		// Check for nonce security.
		$nonce = isset( $_POST['nonce'] ) ?
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) :
			'';

		if (
			empty( $nonce ) ||
			! wp_verify_nonce( $nonce, 'faire_products_manual_sync' )
		) {
			wp_send_json_error(
				__( 'Product manual sync failed. Unauthorized request.', 'faire-for-woocommerce' ),
				401
			);
		}

		// Sync Summary log reset.
		$this->settings->save_products_last_sync_date( gmdate( 'c' ) );
		$this->empty_log_summary_product_sync_result();

		$sync_jobs       = array();
		$manual_sync_ids = array();

		// Sync each product.
		$args     = array(
			'status' => array( 'draft', 'pending', 'private', 'publish' ),
			'type'   => array( 'simple', 'variable' ),
			'return' => 'ids',
			'limit'  => -1,
		);
		$products = wc_get_products( $args );
		if ( $products ) {
			foreach ( $products as $id ) {
				if ( ! $this->is_product_faire_sync_allowed( $id ) ) {
					continue;
				}
				$job_id = $this->scheduler->add_product_single_sync_queue( $id, 'update' );
				if ( $job_id ) {
					$sync_jobs[]       = $job_id;
					$manual_sync_ids[] = $id;
				}
			}
		}

		// Prepare immediate feedback result in json response.
		$result = Utils::create_import_result_entry(
			true,
			sprintf(
				// translators: %d number of products.
				__( 'Sync queued for %d products.', 'faire-for-woocommerce' ),
				count( $manual_sync_ids ),
			)
		);

		$this->log_summary_product_sync_result( count( $manual_sync_ids ), 'queued' );

		wp_send_json_success( (array) $result['info'] );
	}

	/**
	 * Handles Ajax requests to sync a single Faire product
	 */
	public function ajax_single_product_manual_sync() {
		// Check for nonce security.
		$nonce = isset( $_POST['nonce'] ) ?
			sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) :
			'';

		if (
			empty( $nonce ) ||
			! wp_verify_nonce( $nonce, 'faire_single_product_manual_sync' )
		) {
			wp_send_json_error(
				__( 'Product manual sync failed. Unauthorized request.', 'faire-for-woocommerce' ),
				401
			);
		}

		$product_id = empty( $_POST['product_id'] ) ? 0 : intval( wp_unslash( $_POST['product_id'] ) );
		if ( ! $product_id ) {
			wp_send_json_error( array( 'result_string' => __( 'Failed', 'faire-for-woocommerce' ) ) );
		}
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			wp_send_json_error( array( 'result_string' => __( 'Failed', 'faire-for-woocommerce' ) ) );
		}
		if ( ! $this->is_product_faire_sync_allowed( $product_id ) ) {
			wp_send_json_error( array( 'result_string' => __( 'Failed', 'faire-for-woocommerce' ) ) );
		}

		$results = $this->save_faire_product( $product_id, $product );

		if ( $results ) {
			// Log each result to single product page log.
			foreach ( $results as $result ) {

				if ( isset( $result->error ) ) {
					$log_label = ( isset( $result->error->label ) ) ? $result->error->label : __( 'Error', 'faire-for-woocommerce' );
					$this->log_product_sync_result( $product->get_id(), false, $log_label, $result->error->message );
				} else {
					$log_label = ( isset( $result->success->label ) ) ? $result->success->label : __( 'Success', 'faire-for-woocommerce' );
					$this->log_product_sync_result( $product->get_id(), true, $log_label );
				}
			}

			// Log only last result into summary on plugin Settings.
			if ( isset( $result->error ) ) {
				$error_code = ( isset( $result->error->code ) ) ? $result->error->code : 0;
				$this->log_summary_product_sync_result( $product->get_id(), 'failure', $error_code );
			} else {
				$this->log_summary_product_sync_result( $product->get_id(), 'success' );
			}

			// Return ajax success response based on last result.
			if ( ! isset( $result->error ) ) {
				wp_send_json_success(
					array(
						'result_string'    => __( 'Success', 'faire-for-woocommerce' ),
						'faire_product_id' => $this->get_faire_product_id( $product->get_id() ),
					)
				);
			}
		}

		wp_send_json_error( array( 'result_string' => __( 'Failed', 'faire-for-woocommerce' ) ) );
	}


	/**
	 * Create new faire product
	 *
	 * @return object
	 */
	public function create_faire_product( $id, $product, $args = null ): object {

		if ( ! $product ) {
			$product = wc_get_product( $id );
		}

		if ( ! $args ) {
			$args = $this->set_product_args( $product, true );
		}

		if ( ! $args ) {
			$faire_result    = (object) array(
				'error' => (object) array(
					'code'    => '0',
					// translators: %s product ID.
					'message' => sprintf( __( 'Invalid product %s', 'faire-for-wordpress' ), $id ),
				),
			);
			$faire_results[] = $faire_result;
			return $faire_results;
		}

		try {

			// Create.
			$faire_product = $this->product_api->create_product( $args );

			// Save Faire product id (use update_post_meta to avoid recursion).
			$this->save_product_faire_ids( $faire_product, $product );

			return $faire_product;

		} catch ( Exception $e ) {

			$faire_error = (object) array(
				'error' => (object) array(
					'code'    => $e->getCode(),
					'message' => $e->getMessage(),
				),
			);
			return $faire_error;
		}
	}

	/**
	 * Update faire product
	 *
	 * @return object
	 */
	public function update_faire_product(
		int $id,
		$product = null,
		$args = null
	): object {

		if ( ! $product ) {
			$product = wc_get_product( $id );
		}

		if ( ! $args ) {
			$args = $this->set_product_args( $product );
		}

		if ( ! $args ) {
			$faire_result    = (object) array(
				'error' => (object) array(
					'code'    => '0',
					// translators: %s product ID.
					'message' => sprintf( __( 'Invalid product %s', 'faire-for-wordpress' ), $id ),
				),
			);
			$faire_results[] = $faire_result;
			return $faire_results;
		}

		$faire_product_id = $this->get_faire_product_id( $product->get_id() );

		try {

			// Update.
			$faire_product = $this->product_api->update_product( $faire_product_id, $args );

			// Save Faire product id (use update_post_meta to avoid recursion).
			$this->save_product_faire_ids( $faire_product, $product );

			return $faire_product;

		} catch ( Exception $e ) {

			$faire_error = (object) array(
				'error' => (object) array(
					'code'    => $e->getCode(),
					'message' => $e->getMessage(),
				),
			);
			return $faire_error;
		}
	}

	/**
	 * Save (create or update) faire product with failover to save as Draft
	 *
	 * @param int          $id      The product ID.
	 * @param ?\WC_Product $product The product.
	 *
	 * @return array
	 */
	public function save_faire_product(
		int $id,
		?\WC_Product $product = null
	): array {

		$faire_results = array();

		if ( ! $product ) {
			$product = wc_get_product( $id );
		}

		// If skip sync on this product due to linking error.
		if ( $this->skip_linking_error_sync_product( $product ) ) {
			$faire_result    = (object) array(
				'error' => (object) array(
					'code'    => '0',
					'message' => __( 'Product linking error. Skipping faire sync for this product', 'faire-for-wordpress' ),
				),
			);
			$faire_results[] = $faire_result;
			return $faire_results;
		}

		if ( ! $this->settings->is_sync_enabled() ) {
			$faire_result    = (object) array(
				'error' => (object) array(
					'code'    => '0',
					'message' => __( 'Product sync failed. Faire product sync disabled because locale and currency do not match WooCommerce.', 'faire-for-wordpress' ),
				),
			);
			$faire_results[] = $faire_result;
			return $faire_results;
		}

		if ( ! $this->is_product_faire_sync_allowed( $id ) ) {
			$faire_result    = (object) array(
				'error' => (object) array(
					'code'    => '0',
					'message' => __( 'Product sync failed. Faire product sync disallowed on this product.', 'faire-for-wordpress' ),
				),
			);
			$faire_results[] = $faire_result;
			return $faire_results;
		}

		$faire_product_id = $this->get_faire_product_id( $product->get_id() );

		$ignore_excluded_args = false;
		if ( ! $faire_product_id ) {
			$ignore_excluded_args = true; // On create, send all args.
		}

		$args = $this->set_product_args( $product, $ignore_excluded_args );
		if ( ! $args ) {
			$faire_result    = (object) array(
				'error' => (object) array(
					'code'    => '0',
					// translators: %s product ID.
					'message' => sprintf( __( 'Invalid product %s', 'faire-for-wordpress' ), $id ),
				),
			);
			$faire_results[] = $faire_result;
			return $faire_results;
		}

		if ( empty( $args['variants'] ) ) {
			$faire_result    = (object) array(
				'error' => (object) array(
					'code'    => '0',
					// translators: %s product ID.
					'message' => sprintf( __( 'Product id:%s has invalid attribute set and was skipped during product sync.', 'faire-for-wordpress' ), $id ),
				),
			);
			$faire_results[] = $faire_result;
			return $faire_results;
		}

		// Create or Update.
		if ( ! $faire_product_id ) {
			$faire_result = $this->create_faire_product( $id, $product, $args );
		} else {
			$faire_result = $this->update_faire_product( $id, $product, $args );
		}

		// Return result.
		if ( isset( $faire_result->error ) ) {

			// Prepare error label.
			if ( ! $faire_product_id ) {
				$log_msg_failed = __( 'Create product failed', 'faire-for-woocommerce' );
			} else {
				$log_msg_failed = __( 'Update product failed', 'faire-for-woocommerce' );
			}
			$faire_result->error->label = $log_msg_failed;
			$faire_results[]            = $faire_result;

			// Maybe re-attempt with override lifecycle_state as DRAFT.
			if ( 400 === (int) $faire_result->error->code && 'DRAFT' !== $args['lifecycle_state'] ) {

				// Adjust args to set to DRAFT.
				$args['lifecycle_state'] = 'DRAFT';
				if ( isset( $args['variants'] ) && $args['variants'] ) {
					foreach ( $args['variants'] as $k => $variant_args ) {
						$args['variants'][ $k ]['lifecycle_state'] = 'DRAFT';
					}
				}

				// Conditional error: check if description too long.
				$blnDescTruncated = false;
				if ( isset( $args['description'] ) && strlen( $args['description'] ) > 1000 ) {
					$args['description'] = substr( $args['description'], 0, 1000 );
					$blnDescTruncated    = true;
				}

				if ( ! $faire_product_id ) {
					$faire_result = $this->create_faire_product( $id, $product, $args );
				} else {
					$faire_result = $this->update_faire_product( $id, $product, $args );
				}

				// Prepare result.
				if ( isset( $faire_result->error ) ) {
					if ( ! $faire_product_id ) {
						$log_msg_failed = __( 'Create product as DRAFT failed', 'faire-for-woocommerce' );
					} else {
						$log_msg_failed = __( 'Update product as DRAFT failed', 'faire-for-woocommerce' );
					}
					$faire_result->error->label = $log_msg_failed;
					$faire_results[]            = $faire_result;
				} else {

					if ( $blnDescTruncated ) {
						$faire_desctruncated_result = (object) array(
							'error' => (object) array(
								'code'    => '0',
								'message' => __( 'Description was truncated after 1000 characters during save', 'faire-for-woocommerce' ),
							),
						);
						$faire_results[]            = $faire_desctruncated_result;
					}

					// Prepare result success.
					if ( ! $faire_product_id ) {
						$log_msg_success = __( 'Create product as DRAFT success', 'faire-for-woocommerce' );
					} else {
						$log_msg_success = __( 'Update product as DRAFT success', 'faire-for-woocommerce' );
					}
					$faire_success   = (object) array(
						'success' => (object) array(
							'label' => $log_msg_success,
						),
					);
					$faire_results[] = $faire_success;
				}
			}
		} else {

			// Prepare result success.
			if ( ! $faire_product_id ) {
				$log_msg_success = __( 'Create product success', 'faire-for-woocommerce' );
			} else {
				$log_msg_success = __( 'Update product success', 'faire-for-woocommerce' );
			}
			$faire_success   = (object) array(
				'success' => (object) array(
					'label' => $log_msg_success,
				),
			);
			$faire_results[] = $faire_success;
		}

		return $faire_results;
	}

	/**
	 * Delete faire product with failover to save as Draft
	 */
	public function save_faire_product_deleted( $id, $product = null ) {

		$faire_results = array();

		if ( ! $product ) {
			$product = wc_get_product( $id );
		}

		if ( ! $this->settings->is_sync_enabled() ) {
			$faire_result    = (object) array(
				'error' => (object) array(
					'code'    => '0',
					'message' => __( 'Delete product failed. Faire product sync disabled because locale and currency do not match WooCommerce.', 'faire-for-wordpress' ),
				),
			);
			$faire_results[] = $faire_result;
			return $faire_results;
		}

		if ( ! $this->is_product_faire_sync_allowed( $id ) ) {
			$faire_result    = (object) array(
				'error' => (object) array(
					'code'    => '0',
					'message' => __( 'Delete product failed. Faire product sync disallowed on this product.', 'faire-for-wordpress' ),
				),
			);
			$faire_results[] = $faire_result;
			return $faire_results;
		}

		// If skip sync on this product due to linking error
		if ( $this->skip_linking_error_sync_product( $product ) ) {
			$faire_result    = (object) array(
				'error' => (object) array(
					'code'    => '0',
					'message' => __( 'Product linking error. Skipping faire sync for this product', 'faire-for-wordpress' ),
				),
			);
			$faire_results[] = $faire_result;
			return $faire_results;
		}

		// Delete.
		$faire_result = $this->delete_faire_product( $id, $product );

		// Return result.
		if ( isset( $faire_result->error ) ) {

			// Prepare error label.
			$faire_result->error->label = __( 'Delete product failed', 'faire-for-woocommerce' );
			$faire_results[]            = $faire_result;

			// Maybe re-attempt with override lifecycle_state as DRAFT.
			if ( 400 === (int) $faire_result->error->code ) {

				$args                    = array();
				$args['lifecycle_state'] = 'DRAFT';
				if ( isset( $args['variants'] ) && $args['variants'] ) {
					foreach ( $args['variants'] as $k => $variant_args ) {
						$args['variants'][ $k ]['lifecycle_state'] = 'DRAFT';
					}
				}
				$faire_result = $this->update_faire_product( $id, $product, $args );

				// Prepare result.
				if ( isset( $faire_result->error ) ) {
					$faire_result->error->label = __( 'Delete product as DRAFT failed', 'faire-for-woocommerce' );
					$faire_results[]            = $faire_result;
				} else {
					// Prepare result success.
					$faire_success   = (object) array(
						'success' => (object) array(
							'label' => __( 'Delete product as DRAFT success', 'faire-for-woocommerce' ),
						),
					);
					$faire_results[] = $faire_success;
				}
			}
		} else {

			// Prepare result success.
			$faire_success   = (object) array(
				'success' => (object) array(
					'label' => __( 'Delete product success', 'faire-for-woocommerce' ),
				),
			);
			$faire_results[] = $faire_success;
		}

		return $faire_results;
	}

	/**
	 * Delete faire product
	 *
	 * @return object
	 */
	public function delete_faire_product(
		int $id,
		$product = null,
		$args = null,
		$force_delete = false
	): object {

		if ( ! $product ) {
			$product = wc_get_product( $id );
		}

		if ( ! $args ) {
			$args = $this->set_product_args( $product );
		}

		if ( ! $args ) {
			$faire_error = (object) array(
				'error' => (object) array(
					'code'    => '0',
					// translators: %s product ID.
					'message' => sprintf( __( 'Invalid product %s', 'faire-for-wordpress' ), $id ),
				),
			);
			return $faire_error;
		}

		// Override status since delete events are called before the post is deleted.
		// Don't delete on faire, set to UNPUBLISHED.
		$args['lifecycle_state'] = 'UNPUBLISHED';

		$faire_product_id = $this->get_faire_product_id( $product->get_id() );

		try {

			if ( $faire_product_id ) {
				if ( true === $force_delete ) {
					$faire_product = $this->product_api->delete_product( $faire_product_id );
				} else {
					$faire_product = $this->product_api->update_product( $faire_product_id, $args );
					$this->save_product_faire_ids( $faire_product, $product );
				}
			} else {
				$faire_error = (object) array(
					'error' => (object) array(
						'code'    => '0',
						'message' => 'No Faire Product ID associated with this product.',
					),
				);
				return $faire_error;
			}

			return $faire_product;

		} catch ( Exception $e ) {

			$faire_error = (object) array(
				'error' => (object) array(
					'code'    => $e->getCode(),
					'message' => $e->getMessage(),
				),
			);

			return $faire_error;
		}

	}

	/**
	 * Pre update faire product, remove non-existant variants at faire
	 *
	 * @return void
	 */
	public function pre_update_faire_product_cleanup( $product, $args ) {

		$faire_product_id = $this->get_faire_product_id( $product->get_id() );

		// Variant faire ids in args.
		$wc_variant_faire_ids = array();
		if ( isset( $args['variants'] ) && $args['variants'] ) {
			foreach ( $args['variants'] as $variant_args ) {
				if ( isset( $variant_args['id'] ) && $variant_args['id'] ) {
					$wc_variant_faire_ids[] = $variant_args['id'];
				}
			}
		}

		if ( empty( $wc_variant_faire_ids ) ) {
			return;
		}

		// Before product update, remove any variants at faire no longer existing on our WP product.
		$pre_update_faire_product = $this->product_api->get_product( $faire_product_id );
		if ( $pre_update_faire_product && isset( $pre_update_faire_product->variants ) ) {
			foreach ( $pre_update_faire_product->variants as $variant ) {
				if ( ! in_array( $variant->id, $wc_variant_faire_ids, true ) ) {
					$faire_result = $this->product_api->delete_product_variant( $faire_product_id, $variant->id );
				}
			}
		}
	}

	/**
	 * Sync a faire product to its matching wc product
	 *
	 * @return void
	 */
	public function update_product_from_faire_product( $product ) {

		$faire_product_id = $this->get_faire_product_id( $product->get_id() );

		if ( ! $faire_product_id ) {
			return;
		}

		try {

			$faire_product = $this->product_api->get_product( $faire_product_id );
			$this->save_product_faire_ids( $faire_product, $product );

			return $faire_product;

		} catch ( Exception $e ) {
			$faire_error = (object) array(
				'error' => (object) array(
					'code'    => $e->getCode(),
					'message' => $e->getMessage(),
				),
			);
			return $faire_error;
		}
	}

	/**
	 * Save a faire product to its matching wc product
	 *
	 * @return void
	 */
	public function save_product_faire_ids( $faire_product, $product ) {

		// Save Faire product id (use update_post_meta to avoid recursion).
		update_post_meta( $product->get_id(), $this->meta_faire_product_id, $faire_product->id );

		// Save Faire variant ids.
		if ( $faire_product->variants ) {
			if ( 'simple' === $product->get_type() ) {
				// Save faux variant id for simple products.
				foreach ( $faire_product->variants as $faire_variant ) {
					$faire_variant_ids = array( $faire_variant->id );
					update_post_meta( $product->get_id(), $this->meta_faire_variant_id, $faire_variant_ids );
				}
			} else {

				// Get available variation ids.
				$product_children_variation_ids = $product->get_children();

				// Gather all variant ids.
				$save_faire_variant_ids = array();
				foreach ( $faire_product->variants as $faire_variant ) {
					if ( ! isset( $faire_variant->idempotence_token ) ) {
						continue;
					}
					$variation_id = $this->get_wc_variation_id_from_idempotence_token( $faire_variant->idempotence_token );
					if ( $variation_id && in_array( $variation_id, $product_children_variation_ids ) ) {
						// Maybe save multiple faire variants to same wc variant.
						if ( strpos( $faire_variant->idempotence_token, '!' ) !== false ) {
							$option_hash = $this->get_wc_variation_option_hash_from_idempotence_token( $faire_variant->idempotence_token );
							$save_faire_variant_ids[ $variation_id ][ $option_hash ] = $faire_variant->id;
						} else {
							$save_faire_variant_ids[ $variation_id ][] = $faire_variant->id;
						}
					}
				}
				// Save to meta.
				foreach ( $save_faire_variant_ids as $variation_id => $faire_variant_id_array ) {
					if ( $variation_id && in_array( $variation_id, $product_children_variation_ids ) ) {
						$variation_product = new \WC_Product_Variation( $variation_id );
						if ( $variation_product ) {
							update_post_meta( $variation_product->get_id(), $this->meta_faire_variant_id, $faire_variant_id_array );
						}
					}
				}
			}
		}
	}

	/**
	 * Update faire inventory levels by Variant ids
	 *
	 * @param \WC_Product $product The variant.
	 *
	 * @return object
	 *   Variant inventory levels.
	 */
	public function update_faire_variant_inventory( \WC_Product $product ): object {

		$inventories_args = $this->set_product_inventories_args( $product );
		if ( ! $inventories_args ) { // Empty args, skip.
			$faire_error = (object) array(
				'error' => (object) array(
					'code'    => 0,
					// translators: %s product variant ID.
					'message' => sprintf( __( 'Invalid product variant %s', 'faire-for-wordpress' ), $product->get_id() ),
				),
			);
			return $faire_error;
		}

		$args                = array();
		$args['inventories'] = $inventories_args;

		try {
			$faire_response = $this->product_api->update_variants_inventories( $args );
			return $faire_response;
		} catch ( Exception $e ) {
			$faire_error = (object) array(
				'error' => (object) array(
					'code'    => $e->getCode(),
					'message' => $e->getMessage(),
				),
			);
			return $faire_error;
		}
	}

	/**
	 * On event: variation product set stock qty
	 *
	 * @param \WC_Product $product Variation product.
	 */
	public function product_variation_set_stock( \WC_Product $product ) {
		// Prevent duplicate action events.
		global $faire_wc_prevent_dups_stock_updated_id;

		if (
			isset( $faire_wc_prevent_dups_stock_updated_id ) &&
			in_array( $product->get_id(), $faire_wc_prevent_dups_stock_updated_id, true )
		) {
			return;
		}
		$faire_wc_prevent_dups_stock_updated_id[] = $product->get_id();

		if ( ! $this->is_product_faire_sync_allowed( $product->get_parent_id() ) ) {
			return;
		}

		if ( get_option( 'woocommerce_manage_stock' ) === 'yes' && $this->settings->get_inventory_sync_on_change() ) {

			// Check if have faire variant id (or Faux variant id if simple product).
			if ( $this->get_faire_variant_id( $product->get_id() ) ) {

				$result = $this->update_faire_variant_inventory( $product );

				if ( isset( $result->error ) ) {
					$this->log_product_sync_result( $product->get_parent_id(), false, __( 'Update variant inventory failed', 'faire-for-woocommerce' ), $result->error->message );
				} else {
					$this->log_product_sync_result( $product->get_parent_id(), true, __( 'Update variant inventory success', 'faire-for-woocommerce' ) );
				}
			}
		}
	}

	/**
	 * On event: simple product set stock qty.
	 *
	 * @param \WC_Product $product Variation product.
	 *
	 * @return void
	 */
	public function product_simple_set_stock( \WC_Product $product ) {
		// Prevent duplicate action events.
		global $faire_wc_prevent_dups_stock_updated_id;

		if (
			isset( $faire_wc_prevent_dups_stock_updated_id ) &&
			in_array( $product->get_id(), $faire_wc_prevent_dups_stock_updated_id, true )
		) {
			return;
		}
		$faire_wc_prevent_dups_stock_updated_id[] = $product->get_id();

		if ( ! $this->is_product_faire_sync_allowed( $product->get_id() ) ) {
			return;
		}

		if ( get_option( 'woocommerce_manage_stock' ) === 'yes' && $this->settings->get_inventory_sync_on_change() ) {
			// Check if have faire variant id (or Faux variant id if simple product).
			if ( $this->get_faire_variant_id( $product->get_id() ) ) {

				$result = $this->update_faire_variant_inventory( $product );

				if ( isset( $result->error ) ) {
					$this->log_product_sync_result( $product->get_id(), false, __( 'Update product inventory failed', 'faire-for-woocommerce' ), $result->error->message );
				} else {
					$this->log_product_sync_result( $product->get_id(), true, __( 'Update variant inventory success', 'faire-for-woocommerce' ) );
				}
			}
		}

	}

	/**
	 *
	 * @return void
	 */
	/**
	 * On event: variation product set stock status
	 *
	 * @param int         $id           Product ID.
	 * @param string      $stock_status Stock status.
	 * @param \WC_Product $product      The variation product.
	 */
	public function product_variation_set_stock_status( int $id, string $stock_status, \WC_Product $product ) {
		// Prevent duplicate action events.
		global $faire_wc_prevent_dups_stock_updated_id;

		if (
			isset( $faire_wc_prevent_dups_stock_updated_id ) &&
			in_array( $product->get_id(), $faire_wc_prevent_dups_stock_updated_id, true ) ) {
			return;
		}
		$faire_wc_prevent_dups_stock_updated_id[] = $product->get_id();

		if ( ! $this->is_product_faire_sync_allowed( $product->get_id() ) ) {
			return;
		}

		if ( get_option( 'woocommerce_manage_stock' ) === 'yes' && $this->settings->get_inventory_sync_on_change() ) {
			// Check for faire variant id (or Faux variant id if simple product).
			if ( $this->get_faire_variant_id( $product->get_id() ) ) {

				$result = $this->update_faire_variant_inventory( $product );

				if ( isset( $result->error ) ) {
					$this->log_product_sync_result( $product->get_parent_id(), false, __( 'Update variant stock status failed', 'faire-for-woocommerce' ), $result->error->message );
				} else {
					$this->log_product_sync_result( $product->get_parent_id(), true, __( 'Update variant stock status success', 'faire-for-woocommerce' ) );
				}
			}
		}
	}

	/**
	 * On event: simple product stock set stock status
	 *
	 * @return void
	 */
	public function product_simple_set_stock_status( $id, $stock_status, $product ) {

		// Prevent duplicate action events.
		global $faire_wc_prevent_dups_stock_updated_id;
		if (
			isset( $faire_wc_prevent_dups_stock_updated_id ) &&
			in_array( $product->get_id(), $faire_wc_prevent_dups_stock_updated_id, true )
		) {
			return;
		}
		$faire_wc_prevent_dups_stock_updated_id[] = $product->get_id();

		if ( ! $this->is_product_faire_sync_allowed( $product->get_parent_id() ) ) {
			return;
		}

		if ( get_option( 'woocommerce_manage_stock' ) === 'yes' && $this->settings->get_inventory_sync_on_change() ) {

			// Check if have faire variant id (or Faux variant id if simple product).
			if ( $this->get_faire_variant_id( $product->get_id() ) ) {

				$result = $this->update_faire_variant_inventory( $product );

				if ( isset( $result->error ) ) {
					$this->log_product_sync_result( $product->get_id(), false, __( 'Update product stock status failed', 'faire-for-woocommerce' ), $result->error->message );
				} else {
					$this->log_product_sync_result( $product->get_id(), true, __( 'Update product stock status success', 'faire-for-woocommerce' ) );
				}
			}
		}
	}

	/**
	 * Return a products variation attributes including all values.
	 *
	 * @param \WC_Product $product The product.
	 *
	 * @return array Variation attributes
	 */
	public function get_all_wc_product_variation_attributes( \WC_Product $product ) {
		global $wpdb;

		$variation_attributes = array();
		$attributes           = $product->get_attributes();

		if ( empty( $attributes ) ) {
			return array();
		}

		foreach ( $attributes as $attribute ) {
			if ( empty( $attribute['is_variation'] ) ) {
				continue;
			}

			// Set values.
			$values = $attribute['is_taxonomy'] ? wc_get_object_terms( $product->get_id(), $attribute['name'], 'name' ) : wc_get_text_attributes( $attribute['value'] );

			// Empty value indicates that all options for given attribute are available.
			if ( in_array( null, $values, true ) || in_array( '', $values, true ) || empty( $values ) ) {
				$values = $attribute['is_taxonomy'] ? wc_get_object_terms( $product->get_id(), $attribute['name'], 'name' ) : wc_get_text_attributes( $attribute['value'] );
				// Get custom attributes (non taxonomy) as defined.
			} elseif ( ! $attribute['is_taxonomy'] ) {
				$text_attributes          = wc_get_text_attributes( $attribute['value'] );
				$assigned_text_attributes = $values;
				$values                   = array();

				// Pre 2.4 handling where 'slugs' were saved instead of the full text attribute.
				if ( version_compare( get_post_meta( $product->get_id(), '_product_version', true ), '2.4.0', '<' ) ) {
					$assigned_text_attributes = array_map( 'sanitize_title', $assigned_text_attributes );
					foreach ( $text_attributes as $text_attribute ) {
						if ( in_array( sanitize_title( $text_attribute ), $assigned_text_attributes, true ) ) {
							$values[] = $text_attribute;
						}
					}
				} else {
					foreach ( $text_attributes as $text_attribute ) {
						if ( in_array( $text_attribute, $assigned_text_attributes, true ) ) {
							$values[] = $text_attribute;
						}
					}
				}
			}

			// Return attributes with key for accessing.
			$attribute_name_cleaned                          = wc_attribute_label( $attribute['name'] );
			$variation_attributes[ $attribute_name_cleaned ] = array(
				'attribute_object' => $attribute,
				'name'             => $attribute_name_cleaned,
				'values'           => array_unique( $values ),
			);

		}

		return $variation_attributes;
	}

	/**
	 * Build faire product args from a WooCommerce product, for use with creating and updating product events
	 *
	 * @param \WC_Product $product WC_Product.
	 * @param bool        $ignore_excluded_args Flag.
	 *
	 * @return array
	 */
	public function set_product_args( $product, $ignore_excluded_args = false ) {

		$args_option_sets      = array();
		$args_variants         = array();
		$args_preorder_details = null;
		$args_taxonomy_type    = null;

		// Types: simple, external, variable, variation, grouped.

		if ( in_array( $product->get_type(), array( 'grouped', 'variation', 'external' ), true ) ) {
			return;
		}

		if ( 'simple' === $product->get_type() ) {

			$variant_images   = array();
			$variant_image_id = $product->get_image_id();
			if ( $variant_image_id ) {
				$variant_images[]['url'] = wp_get_attachment_image_url( $variant_image_id, $this->settings->get_product_image_size() );
			}

			$args_variant_prices   = array();
			$args_variant_prices[] = array(
				'geo_constraint'  => $this->get_faire_product_geo_constraint( $product ),
				'wholesale_price' => array(
					'amount_minor' => $this->convert_price_to_cents( $this->get_faire_wholesale_price( $product ) ), // REQUIRED.
					'currency'     => $this->get_faire_product_currency( $product ),
				),
				'retail_price'    => array(
					'amount_minor' => $this->convert_price_to_cents( $this->get_faire_retail_price( $product ) ), // REQUIRED.
					'currency'     => $this->get_faire_product_currency( $product ),
				),
			);

			// At minimum faire requires one variant, so create from our product.
			$variant_data = array(
				'id'                 => $this->get_faire_variant_id( $product->get_id() ), // Get Faux Variant id saved on parent product.
				'idempotence_token'  => $this->get_variant_idempotence_token( $product->get_id() ), // REQUIRED.
				'lifecycle_state'    => $this->get_product_lifecycle_state( $product ),
				'sku'                => $product->get_sku(),
				'images'             => $variant_images,
				'tariff_code'        => $this->get_faire_tariff_code( $product ),
				'prices'             => $args_variant_prices,
				'available_quantity' => $this->get_faire_product_stock_quantity( $product ),
				'backordered_until'  => $this->get_faire_product_backordered_until( $product ), // Must be ISO 8601 date.
			);

			$measurements = $this->get_faire_product_measurements( $product );
			if ( false !== $measurements ) {
				$variant_data = array_merge( $variant_data, array( 'measurements' => $measurements ) );
			}

			$args_variants[] = $variant_data;

		} elseif ( 'variable' === $product->get_type() ) {

			/**
			 * @var \WC_Product_Variable $product
			 */
			// Option Sets, build with all attributes.
			$product_attributes = $this->get_all_wc_product_variation_attributes( $product );
			foreach ( $product_attributes as $attribute_key => $attribute_data ) {
				$args_option_sets[] = array(
					'name'   => wc_attribute_label( $attribute_key ),
					'values' => $attribute_data['values'],
				);
			}

			// Variations.
			$variations           = $product->get_children(); // use get_children instead of get_available_variations() so that all are returned.
			$available_attributes = array_change_key_case( $product->get_variation_attributes(), CASE_LOWER );
			if ( $variations ) {
				$args_variants = array();
				foreach ( $variations as $variation_id ) {

					/**
					 * @var \WC_Product_Variation $variation
					 */
					$variation = wc_get_product( $variation_id );
					if ( ! $variation instanceof \WC_Product_Variation ) {
						continue;
					}

					$variation_attributes = array_change_key_case( $variation->get_variation_attributes( false ), CASE_LOWER );
					foreach ( $variation_attributes as $attr_key => $attr_value ) {
						if ( ! isset( $available_attributes[ $attr_key ] )
						|| ! is_array( $available_attributes[ $attr_key ] )
						|| ! in_array( $attr_value, $available_attributes[ $attr_key ], true ) ) {
							Utils::create_import_result_entry(
								false,
								sprintf(
									// translators: %d number of products.
									__( 'Variation id:%1$d of Product id:%2$d has invalid attribute set and was skipped during product sync.', 'faire-for-woocommerce' ),
									$variation->get_id(),
									$product->get_id(),
								)
							);
							continue 2;
						}
					}

					$variant_images   = array();
					$variant_image_id = $variation->get_image_id();
					if ( $variant_image_id ) {
						$variant_images[] = array(
							'url' => wp_get_attachment_image_url( $variant_image_id, $this->settings->get_product_image_size() ),
						);
					}

					$args_variant_prices   = array();
					$args_variant_prices[] = array(
						'geo_constraint'  => $this->get_faire_product_geo_constraint( $variation ),
						'wholesale_price' => array(
							'amount_minor' => $this->convert_price_to_cents( $this->get_faire_wholesale_price( $variation ) ), // REQUIRED.
							'currency'     => $this->get_faire_product_currency( $variation ),
						),
						'retail_price'    => array(
							'amount_minor' => $this->convert_price_to_cents( $this->get_faire_retail_price( $variation ) ), // REQUIRED.
							'currency'     => $this->get_faire_product_currency( $product ),
						),
					);

					$args_variant = array(
						'id'                 => $this->get_faire_variant_id( $variation->get_id() ),
						'idempotence_token'  => $this->get_variant_idempotence_token( $variation->get_id() ), // REQUIRED.
						'lifecycle_state'    => $this->get_product_lifecycle_state( $variation ),
						'sku'                => $variation->get_sku(),
						'images'             => $variant_images,
						'tariff_code'        => $this->get_faire_tariff_code( $variation ),
						'prices'             => $args_variant_prices,
						'available_quantity' => $this->get_faire_product_stock_quantity( $variation ),
						'backordered_until'  => $this->get_faire_product_backordered_until( $variation ), // Must be ISO 8601 date.
					);

					$measurements = $this->get_faire_product_measurements( $variation );
					if ( false !== $measurements ) {
						$args_variant['measurements'] = $measurements;
					}

					// Variant Options.
					$variation_attributes = $variation->get_variation_attributes( false );

					// Options with "Any": Clone variant if has an attribute = "Any".
					$variant_options               = array();
					$variation_clone_options       = array();
					$variation_existing_attributes = array();
					foreach ( $variation_attributes as $attribute_name => $attribute_value ) {
						$attribute_label    = wc_attribute_label( $attribute_name, $variation );
						$_product_attribute = isset( $product_attributes[ $attribute_label ] ) ? $product_attributes[ $attribute_label ] : null;
						// Value is empty means "Any".
						if ( '' === $attribute_value ) {
							if ( $_product_attribute && $_product_attribute['values'] ) {
								foreach ( $_product_attribute['values'] as $_each_val ) {
									$variation_clone_options[ $attribute_label ][] = $_each_val;
								}
							}
						} else {
							// If taxonomy term, get term name.
							if ( $_product_attribute && $_product_attribute['attribute_object']['is_taxonomy'] ) {
								$taxonomy_name = $_product_attribute['attribute_object']['name'];
								$option_term   = get_term_by( 'slug', $attribute_value, $taxonomy_name );
								if ( $option_term ) {
									$attribute_value = $option_term->name;
								}
							}
							$variant_options[] = array(
								'name'  => $attribute_label,
								'value' => $attribute_value,
							);
							// Add to array of existing attributes.
							$variation_existing_attributes[ $attribute_label ] = $attribute_value;
						}
					}

					$args_variant['options'] = $variant_options;

					// Create all possible combinations using wc_array_cartesian.
					$possible_attributes = array_reverse( wc_array_cartesian( $variation_clone_options ) );
					$unexisting_options  = array();
					foreach ( $possible_attributes as $possible_attribute ) {
						// Allow any order if key/values -- do not use strict mode.
						if ( in_array( $possible_attribute, $variation_existing_attributes ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
							continue;
						}
						$unexisting_options[] = $possible_attribute;
					}

					// Create variant clones for none existing options.
					if ( $unexisting_options ) {
						foreach ( $unexisting_options as $unexisting_option ) {

							// Clone our existing variant args and merge unexisting option attributes.
							$add_args_variant = $args_variant;

							// Add unexisting option attributes.
							$unexisting_option_arg = array();
							foreach ( $unexisting_option as $option_name => $option_value ) {
								$unexisting_option_arg[] = array(
									'name'  => $option_name,
									'value' => $option_value,
								);
							}
							$options_merged              = array_merge( $add_args_variant['options'], $unexisting_option_arg );
							$add_args_variant['options'] = $options_merged;

							// Create unique hash for this option combination.
							$option_array_as_string = wp_json_encode( $options_merged );
							$option_hash            = hash( 'crc32', $option_array_as_string, false );

							// Update id and idempotence_token fields for our new variant.
							$add_args_variant['id']                = $this->get_faire_variant_id( $variation->get_id(), $option_hash );
							$add_args_variant['idempotence_token'] = $this->get_variant_idempotence_token( $variation->get_id(), $option_hash );

							// Add to array of variants to create.
							$args_variants[] = $add_args_variant;
						}
					} else {
						$args_variants[] = $args_variant;
					}
				}
			}
		}

		// Preorder fields.
		$is_preorderable = $this->get_faire_product_preorderable( $product );
		if ( $is_preorderable ) {
			$args_preorder_details = $this->get_faire_product_preorder_details( $product );
		}

		// Taxonomy type id.
		$faire_type_id = $this->get_faire_product_taxonomy_type( $product );
		if ( $faire_type_id ) {
			$args_taxonomy_type = array( 'id' => $faire_type_id );
		}

		// Images.
		$product_images   = array();
		$product_image_id = $product->get_image_id();
		if ( $product_image_id ) {
			$product_images[]['url'] = wp_get_attachment_image_url( $product_image_id, $this->settings->get_product_image_size() );
		}
		$product_image_ids = $product->get_gallery_image_ids();
		if ( $product_image_ids ) {
			foreach ( $product_image_ids as $image_id ) {
				$product_images[] = array(
					'url' => wp_get_attachment_image_url( $image_id, $this->settings->get_product_image_size() ),
				);
			}
		}

		$args = array(
			'idempotence_token'                => $this->get_idempotence_token( $product->get_id() ), // REQUIRED.
			// 'short_description' => $product->get_short_description().
			'short_description'                => '',
			'description'                      => apply_filters( 'faire_wc_product_description', wp_strip_all_tags( $product->get_description() ), $product ),
			'lifecycle_state'                  => $this->get_product_lifecycle_state( $product ),
			'name'                             => $product->get_name(), // REQUIRED.
			'unit_multiplier'                  => $this->get_faire_product_unit_multiplier( $product ), // REQUIRED FOR PUBLISHED.
			'minimum_order_quantity'           => $this->get_faire_product_minimum_order_quantity( $product ),
			'per_style_minimum_order_quantity' => $this->get_faire_product_per_style_minimum_order_quantity( $product ),
			'images'                           => $product_images, // REQUIRED FOR PUBLISHED.
			'preorderable'                     => ( $is_preorderable ) ? true : false,
			'preorder_details'                 => $args_preorder_details,
			'taxonomy_type'                    => $args_taxonomy_type,
			'allow_sales_when_out_of_stock'    => $this->get_faire_product_allow_sales_when_out_of_stock( $product ),
		);

		// Check if variants and option sets have values so we don't pass empty args (API rejects empty variants).
		if ( $args_option_sets ) {
			$args['variant_option_sets'] = $args_option_sets;
		}
		if ( $args_variants ) {
			$args['variants'] = $args_variants;
		}

		// Excluded fields: remove any fields excluded from overwrite.
		if ( false === $ignore_excluded_args ) {
			$excluded_args = $this->get_excluded_product_sync_args();
			if ( $excluded_args ) {
				// Excluded parent fields.
				foreach ( $args as $key => $arg ) {
					if ( 'variants' === $key ) {
						continue;
					}
					if ( in_array( 'product.' . $key, $excluded_args, true ) ) {
						unset( $args[ $key ] );
					}
				}
				// Excluded variant field.
				if ( isset( $args['variants'] ) && is_array( $args['variants'] ) ) {
					foreach ( $args['variants'] as $v_i => $variant ) {
						foreach ( $variant as $key => $arg ) {
							if ( in_array( 'variant.' . $key, $excluded_args, true ) ) {
								// New variants, ie with no faire variant ids, should not have fields excluded.
								if ( ! isset( $variant['id'] ) || ! $variant['id'] ) {
									continue;
								}
								unset( $args['variants'][ $v_i ][ $key ] );
							}
						}
					}
				}
			}
		}

		return apply_filters( 'faire_wc_product_set_product_args', $args, $product );
	}

	/**
	 * Build faire product inventory related args from a WooCommerce product, for use updating inventory available quantities (stock levels).
	 *
	 * @param \WC_Product $product WC_Product.
	 *
	 * @return array
	 */
	public function set_product_inventories_args( $product ) {

		$args = array();

		// Types: simple, external, variable, variation, grouped.
		if ( 'simple' === $product->get_type() ) {

			// Update faux variation from parent product.
			$args[] = array(
				'product_variant_id' => $this->get_faire_variant_id( $product->get_id() ), // Get Faux Variant id saved on parent product.
				'current_quantity'   => $this->get_faire_product_stock_quantity( $product ), // REQUIRED.
				'discontinued'       => apply_filters( 'faire_wc_product_is_discontinued', false, $product ),
				'backordered_until'  => $this->get_faire_product_backordered_until( $product ), // Must be ISO 8601 date.
			);

		} elseif ( 'variable' === $product->get_type() ) {

			// Get all variations.
			$variations = $product->get_children(); // use get_children instead of get_available_variations() so that all are returned.
			if ( $variations ) {
				foreach ( $variations as $variation_id ) {
					// Get variation product.
					$variation = wc_get_product( $variation_id );
					if ( ! $variation ) {
						continue;
					}

					$faire_variant_ids = $this->get_all_faire_variant_ids( $variation->get_id() );
					if ( is_array( $faire_variant_ids ) && $faire_variant_ids ) {
						foreach ( $faire_variant_ids as $faire_variant_id ) {
							$args[] = array(
								'product_variant_id' => $faire_variant_id, // REQUIRED.
								'current_quantity'   => $this->get_faire_product_stock_quantity( $variation ), // REQUIRED.
								'discontinued'       => apply_filters( 'faire_wc_product_is_discontinued', false, $variation ),
								'backordered_until'  => $this->get_faire_product_backordered_until( $variation ), // Must be ISO 8601 date.
							);
						}
					}
				}
			}
		} elseif ( 'variation' === $product->get_type() ) {

			// Set variation stock.
			$faire_variant_ids = $this->get_all_faire_variant_ids( $product->get_id() );
			if ( is_array( $faire_variant_ids ) && $faire_variant_ids ) {
				foreach ( $faire_variant_ids as $faire_variant_id ) {
					$args[] = array(
						'product_variant_id' => $faire_variant_id, // REQUIRED.
						'current_quantity'   => $this->get_faire_product_stock_quantity( $product ), // REQUIRED.
						'discontinued'       => apply_filters( 'faire_wc_product_is_discontinued', false, $product ),
						'backordered_until'  => $this->get_faire_product_backordered_until( $product ), // Must be ISO 8601 date.
					);
				}
			}
		} elseif ( 'grouped' === $product->get_type() ) {
			// Ignore grouped, as each product is individually managed.
			return;
		} elseif ( 'external' === $product->get_type() ) {
			// Ignore external, as these are offsite products for sale.
			return;
		}

		return apply_filters( 'faire_wc_product_set_product_inventories_args', $args, $product );
	}

	/**
	 * Get faire idempotence_token for wc product id
	 *
	 * @param int $product_id WC_Product ID.
	 *
	 * @return string
	 */
	public function get_idempotence_token( $product_id ) {
		return sprintf( '%s_wc_%s', $this->generateRandomString(), (string) $product_id );
	}

	/**
	 * Get product id from faire product idempotence_token.
	 *
	 * @param string $idempotence_token Faire product idempotence_token.
	 *
	 * @return int|null
	 */
	public function get_wc_product_id_from_idempotence_token( $idempotence_token ) {
		if ( false !== stripos( $idempotence_token, '_wc_' ) ) {
			$parts      = explode( '_wc_', $idempotence_token );
			$product_id = isset( $parts[1] ) ? $parts[1] : null; // Random bits in first part, id in 2nd
			if ( null !== $product_id && is_numeric( $product_id ) ) {
				return (int) $product_id;
			}
		}
		return null;
	}

	/**
	 * Get faire variant idempotence_token for wc product variant id.
	 *
	 * @return string
	 */
	public function get_variant_idempotence_token( $product_id, $index_key = '' ) {
		if ( '' !== $index_key ) {
			return sprintf( '%s_wcv_%s!%s', $this->generateRandomString(), (string) $product_id, (string) $index_key );
		}
		return sprintf( '%s_wcv_%s', $this->generateRandomString(), (string) $product_id );
	}

	/**
	 * Get wc variation product id from faire variant idempotence_token
	 *
	 * @return string
	 */
	public function get_wc_variation_id_from_idempotence_token( $idempotence_token ) {
		// If idempotence_token has separator for hashes, remove hash
		if ( strpos( $idempotence_token, '!' ) !== false ) {
			$hash_parts        = explode( '!', $idempotence_token );
			$idempotence_token = $hash_parts[0]; // variation id is in 1st array item
		}
		// Find variation id in idempotence_token
		if ( false !== stripos( $idempotence_token, '_wcv_' ) ) {
			$parts        = explode( '_wcv_', $idempotence_token );
			$variation_id = isset( $parts[1] ) ? $parts[1] : null; // Random bits in first part, id in 2nd
			if ( null !== $variation_id && is_numeric( $variation_id ) ) {
				return (int) $variation_id;
			}
		}
		return null;
	}

	/**
	 * Get wc variation clone key from faire variant idempotence_token
	 *
	 * @return string
	 */
	public function get_wc_variation_option_hash_from_idempotence_token( $idempotence_token ) {
			// If idempotence_token has separator for hashes, find hash at end.
		if ( strpos( $idempotence_token, '!' ) !== false ) {
			$token_parts = explode( '!', $idempotence_token );
			$option_hash = $token_parts[1]; // hash is in 2nd array item.
			return $option_hash;
		}
		return null;
	}

	/**
	 * Get faire product id for wc product
	 *
	 * @return string
	 */
	public function get_faire_product_id( $product_id ) {
		return (string) get_post_meta( $product_id, $this->meta_faire_product_id, true );
	}

	/**
	 * Get a single faire variant id for wc product variant by index key or
	 *
	 * @return string|array
	 */
	public function get_faire_variant_id( $product_id, $option_hash = '' ) {
		$faire_variant_ids = get_post_meta( $product_id, $this->meta_faire_variant_id, true );
		if ( is_array( $faire_variant_ids ) ) {
			if ( '' !== $option_hash ) {
				return ( isset( $faire_variant_ids[ $option_hash ] ) ) ? $faire_variant_ids[ $option_hash ] : null;
			} else {
				return reset( $faire_variant_ids ); // first item in array.
			}
		}
		return $faire_variant_ids;
	}

	/**
	 * Get array of all faire variant id for wc product variant
	 *
	 * @return string|array
	 */
	public function get_all_faire_variant_ids( $product_id ) {
		$faire_variant_ids = get_post_meta( $product_id, $this->meta_faire_variant_id, true );
		return $faire_variant_ids;
	}

	/**
	 * Get excluded sync faire product args, interpreting args to remove for setting values.
	 *
	 * @return array
	 */
	public function get_excluded_product_sync_args() {

		$excluded_setting = $this->settings->get_product_sync_exclude_fields();
		$excluded_args    = array();
		if ( is_array( $excluded_setting ) ) {
			foreach ( $excluded_setting as $field ) {
				if ( 'product.preorder_fields' === $field ) { // remove all preorder related fields.
					$excluded_args[] = 'product.preorderable';
					$excluded_args[] = 'product.preorder_details';
				} elseif ( 'product.lifecycle_state' === $field ) { // remove product and variant lifecycle state fields.
					$excluded_args[] = 'product.lifecycle_state';
					$excluded_args[] = 'variant.lifecycle_state';
				} else {
					$excluded_args[] = $field;
				}
			}
		}

		return apply_filters( 'faire_wc_product_excluded_product_sync_args', $excluded_args, $excluded_setting );
	}

	/**
	 * Get faire product stock quantity for wc product or variation
	 *
	 * @return string
	 */
	public function get_faire_product_stock_quantity( $product ) {

		// Null == no faire inventory management.
		$qty_if_stock_managed = ( $product->get_manage_stock() ) ? $product->get_stock_quantity() : null;

		return apply_filters( 'faire_wc_product_stock_quantity', $qty_if_stock_managed, $product );
	}

	/**
	 * Get faire product case unit quantity for wc product or variation
	 *
	 * @return string
	 */
	public function get_faire_product_unit_multiplier( $product ) {
		$unit_multiplier = (string) get_post_meta( $product->get_id(), 'woocommerce_faire_product_unit_multiplier', true );
		$unit_multiplier = $unit_multiplier ? $unit_multiplier : 1;
		return apply_filters( 'faire_wc_product_unit_multiplier', $unit_multiplier, $product );
	}

	/**
	 * Get faire product minimum order quantity for wc product or variation
	 *
	 * @return string
	 */
	public function get_faire_product_minimum_order_quantity( $product ) {
		$minimum_order_quantity = (string) get_post_meta( $product->get_id(), 'woocommerce_faire_product_minimum_order_quantity', true );
		$minimum_order_quantity = $minimum_order_quantity ? $minimum_order_quantity : 1;
		return apply_filters( 'faire_wc_product_minimum_order_quantity', $minimum_order_quantity, $product );
	}

	/**
	 * Get faire product per style minimum order quantity for wc product or variation
	 *
	 * @return string
	 */
	public function get_faire_product_per_style_minimum_order_quantity( $product ) {
		$per_style_minimum_order_quantity = (string) get_post_meta( $product->get_id(), 'woocommerce_faire_product_per_style_minimum_order_quantity', true );
		$per_style_minimum_order_quantity = $per_style_minimum_order_quantity ? $per_style_minimum_order_quantity : 0;
		return apply_filters( 'faire_wc_product_per_style_minimum_order_quantity', $per_style_minimum_order_quantity, $product );
	}

	/**
	 * Get faire product currency for wc product or variation
	 *
	 * @return string
	 */
	public function get_faire_product_currency( $product ) {
		$currency = get_woocommerce_currency();
		return apply_filters( 'faire_wc_product_currency', $currency, $product );
	}

	/**
	 * Get faire product price geo constraint country or country_group for wc product or variation
	 *
	 * @return string
	 */
	public function get_faire_product_geo_constraint( $product ) {
		$geo_constraint = $this->settings->get_faire_geo_constraint();
		return apply_filters( 'faire_wc_product_price_geo_constraint', $geo_constraint, $product );
	}

	/**
	 * Get faire product stock status for wc product or variation
	 *
	 * @return string
	 */
	public function get_faire_product_allow_sales_when_out_of_stock( $product ) {

		$allow_sales_when_out_of_stock = null;
		if ( 'instock' === $product->get_stock_status() ) {
			$allow_sales_when_out_of_stock = true;
		} elseif ( 'outofstock' === $product->get_stock_status() ) {
			$allow_sales_when_out_of_stock = false;
		} elseif ( 'onbackorder' === $product->get_stock_status() ) {
			$allow_sales_when_out_of_stock = true;
		}

		return apply_filters( 'faire_wc_product_allow_sales_when_out_of_stock', $allow_sales_when_out_of_stock, $product );
	}

	/**
	 * Get faire product wholesale price for wc product or variation
	 *
	 * @param \WC_Product $product WC_Product.
	 *
	 * @return string
	 */
	public function get_faire_wholesale_price( $product ) {

		$wholesale_price = $product->get_price();

		if ( 'wholesale_percentage' === $this->settings->get_product_pricing_policy() ) {
			if ( 'variation' === $product->get_type() ) {
				$wholesale_price = get_post_meta( $product->get_id(), 'woocommerce_faire_product_variation_wholesale_price', true );
			} else {
				$wholesale_price = get_post_meta( $product->get_id(), 'woocommerce_faire_product_wholesale_price', true );
			}

			// If empty, use global setting to calculate wholesale price.
			if ( ! $wholesale_price ) {
				$retail                       = (float) $product->get_price();
				$product_wholesale_percentage = $this->settings->get_product_wholesale_percentage();
				if ( $retail && $retail > 0 && $product_wholesale_percentage && $product_wholesale_percentage > 0 ) {
					$wholesale_price = $retail * ( $product_wholesale_percentage / 100 );
					$wholesale_price = round( ( $retail * ( $product_wholesale_percentage / 100 ) ), 2 );
				}
			}
		}

		return apply_filters( 'faire_wc_product_wholesale_price', $wholesale_price, $product );
	}

	/**
	 * Get faire product retail price for wc product or variation
	 *
	 * @param \WC_Product $product WC_Product.
	 *
	 * @return string
	 */
	public function get_faire_retail_price( $product ) {

		if ( 'wholesale_percentage' === $this->settings->get_product_pricing_policy() ) {
			$retail_price = $product->get_price();
		} else {
			if ( 'variation' === $product->get_type() ) {
				$retail_price = get_post_meta( $product->get_id(), 'woocommerce_faire_product_variation_retail_price', true );
			} else {
				$retail_price = get_post_meta( $product->get_id(), 'woocommerce_faire_product_retail_price', true );
			}

			// If empty, use global setting to calculate retail price.
			if ( ! $retail_price ) {
				$wholesale                    = (float) $product->get_price();
				$product_wholesale_multiplier = $this->settings->get_product_wholesale_multiplier();
				if ( $wholesale && $wholesale > 0 && $product_wholesale_multiplier && $product_wholesale_multiplier > 0 ) {
					$retail_price = round( ( $wholesale * $product_wholesale_multiplier ), 2 );
				}
			}
		}

		return apply_filters( 'faire_wc_product_retail_price', $retail_price, $product );
	}

	/**
	 * Get faire product trariff code for wc product or variation
	 *
	 * @return string
	 */
	public function get_faire_tariff_code( $product ) {

		$tariff_code = '';
		if ( 'variation' === $product->get_type() ) {
			$tariff_code = get_post_meta( $product->get_id(), 'woocommerce_faire_product_variation_tariff_code', true );
		} else {
			$tariff_code = get_post_meta( $product->get_id(), 'woocommerce_faire_product_tariff_code', true );
		}

		return apply_filters( 'faire_wc_product_tariff_code', $tariff_code, $product );
	}

	/**
	 * Get faire product preorderable for wc product
	 *
	 * @return string
	 */
	public function get_faire_product_preorderable( $product ) {
		$allow_preorder = (string) get_post_meta( $product->get_id(), 'woocommerce_faire_product_allow_preorder', true );
		$preorderable   = 'allow' === $allow_preorder;

		return apply_filters( 'faire_wc_product_preorderable', $preorderable, $product );
	}

	/**
	 * Get faire product preorder details for wc product
	 *
	 * @return string
	 */
	public function get_faire_product_preorder_details( $product ) {
		$preorder_details = array(
			'order_by_date'                  => (string) get_post_meta( $product->get_id(), 'woocommerce_faire_product_order_by_date', true ),
			'keep_active_past_order_by_date' => (bool) get_post_meta( $product->get_id(), 'woocommerce_faire_product_keep_active_past_order_by_date', true ),
			'expected_ship_date'             => (string) get_post_meta( $product->get_id(), 'woocommerce_faire_product_expected_ship_date', true ),
			'expected_ship_window_end_date'  => (string) get_post_meta( $product->get_id(), 'woocommerce_faire_product_expected_ship_window_date', true ),
		);
		return apply_filters( 'faire_wc_product_preorder_details', $preorder_details, $product );
	}

	/**
	 * Get faire product taxonomy type for wc product
	 *
	 * @param \WC_Product $product A Product.
	 *
	 * @return string
	 */
	public function get_faire_product_taxonomy_type( \WC_Product $product ) {
		$taxonomy_type = (string) get_post_meta( $product->get_id(), 'woocommerce_faire_product_taxonomy_type', true );
		return apply_filters( 'faire_wc_product_taxonomy_type', $taxonomy_type, $product );
	}

	/**
	 * Get faire product backordered until
	 *
	 * @param \WC_Product $product A Product.
	 *
	 * @return string
	 */
	public function get_faire_product_backordered_until( \WC_Product $product ) {

		$backordered_until = null;
		// TODO: Backordered until.
		/*
		if ( $product->get_stock_status() == 'onbackorder' ) {
			$backordered_until = apply_filters('faire_wc_product_backordered_until_default', date('c', strtotime('+5 years')), $product);
		}
		*/

		return apply_filters( 'faire_wc_product_backordered_until', $backordered_until, $product );
	}

	/**
	 * Add measurement data.
	 *
	 * @param  \WC_Product $product WC Product.
	 *
	 * @return object|false
	 */
	private function get_faire_product_measurements( $product ) {
		$wc_mass_unit     = get_option( 'woocommerce_weight_unit', 'kg' );
		$wc_distance_unit = get_option( 'woocommerce_dimension_unit', 'cm' );
		$measurements     = (object) array();

		$mass_unit_map_woocommerce_to_faire     = array(
			'g'   => 'GRAMS',
			'kg'  => 'KILOGRAMS',
			'oz'  => 'OUNCES',
			'lbs' => 'POUNDS',
		);
		$distance_unit_map_woocommerce_to_faire = array(
			'cm' => 'CENTIMETERS',
			'in' => 'INCHES',
			'ft' => 'FEET',
			'mm' => 'MILLIMETERS',
			'm'  => 'METERS',
			'yd' => 'YARDS',
		);

		if ( $product->get_weight() && isset( $mass_unit_map_woocommerce_to_faire[ $wc_mass_unit ] ) ) {
			$measurements->mass_unit = $mass_unit_map_woocommerce_to_faire[ $wc_mass_unit ];
			$measurements->weight    = $product->get_weight();
		}

		if (
			( $product->get_length() || $product->get_width() || $product->get_height() ) &&
			isset( $distance_unit_map_woocommerce_to_faire[ $wc_distance_unit ] )
		) {
			$measurements->distance_unit = $distance_unit_map_woocommerce_to_faire[ $wc_distance_unit ];
		}

		if ( $product->get_length() && isset( $measurements->distance_unit ) ) {
			$measurements->length = $product->get_length();
		}

		if ( $product->get_width() && isset( $measurements->distance_unit ) ) {
			$measurements->width = $product->get_width();
		}

		if ( $product->get_height() && isset( $measurements->distance_unit ) ) {
			$measurements->height = $product->get_height();
		}

		if ( ! ( isset( $measurements->mass_unit ) || isset( $measurements->distance_unit ) ) ) {
			$measurements = false;
		}

		return apply_filters( 'faire_wc_product_measurements', $measurements, $product );
	}

	/**
	 * Determine faire product state from product status
	 *
	 * @return string
	 */
	public function get_product_lifecycle_state( $product ) {

		$lifecycle_state = null;
		if ( $product->get_type() === 'variation' ) {
			$lifecycle_state = get_post_meta( $product->get_id(), 'woocommerce_faire_product_variation_lifecycle_state', true );
		} else {
			$lifecycle_state = get_post_meta( $product->get_id(), 'woocommerce_faire_product_lifecycle_state', true );
		}

		// If meta is empty.
		if ( empty( $lifecycle_state ) ) {
			$lifecycle_state = apply_filters( 'faire_wc_product_lifecycle_state_default', self::DEFAULT_FAIRE_LIFECYCLE_STATE, $product );
		}

		return apply_filters( 'faire_wc_product_lifecycle_state', $lifecycle_state, $product );
	}

	/**
	 * Translates faire product state.
	 *
	 * @param \WC_Product $product The product.
	 *
	 * @return string faire product state.
	 */
	public function get_lifecycle_state_for_status_translated( \WC_Product $product ): string {
		$product_state_translations = array(
			'PUBLISHED'   => __( 'PUBLISHED', 'faire-for-woocommerce' ),
			'DELETED'     => __( 'DELETED', 'faire-for-woocommerce' ),
			'UNPUBLISHED' => __( 'UNPUBLISHED', 'faire-for-woocommerce' ),
			'DRAFT'       => __( 'DRAFT', 'faire-for-woocommerce' ),
		);

		return $product_state_translations[ $this->get_product_lifecycle_state( $product ) ];
	}

	/**
	 * Convert a dollar price to cents.
	 *
	 * @param float|string $price Price in dollars.
	 *
	 * @return float Price in cents.
	 */
	public function convert_price_to_cents( $price ): float {
		$price       = '' === $price ? 0 : $price;
		$price_cents = round( $price * 100 );
		return ( $price > 0 ) ? absint( $price_cents ) : 0;
	}

	/**
	 * Generate a random string
	 *
	 * @return string
	 */
	public function generateRandomString( $len = 12 ) {
		$bytes = '';
		try {
			if ( function_exists( 'random_bytes' ) ) {
				$bytes = random_bytes( ceil( $len / 2 ) );
			} elseif ( function_exists( 'openssl_random_pseudo_bytes' ) ) {
				$bytes = openssl_random_pseudo_bytes( ceil( $len / 2 ) );
			}
		} catch ( Exception $e ) {
			error_log( 'faire exception - ' . $e->getMessage() );
		}
		if ( ! $bytes ) {
			error_log( 'faire exception - no random string generated' );
		}
		return substr( bin2hex( $bytes ), 0, $len );
	}

	/**
	 * Exclude faire meta keys when WC duplicating product
	 *
	 * @param array $exclude_meta Meta keys to exclude.
	 * @param array $existing_meta_keys Existing meta keys on product.
	 *
	 * @return array
	 */
	public function duplicate_exclude_meta( $exclude_meta, $existing_meta_keys ) {
		$exclude_meta[] = $this->meta_faire_product_id;
		$exclude_meta[] = $this->meta_faire_variant_id;
		$exclude_meta[] = self::META_FAIRE_PRODUCT_SYNC_RESULT;
		$exclude_meta[] = '_faire_product_linking_error';
		$exclude_meta[] = '_faire_product_linking_error_faire_id';
		$exclude_meta[] = '_faire_product_unmatched_variants';
		return $exclude_meta;
	}

	/**
	 * Exclude certain WC products from syncing. Also can be used by other classes to filter certain WC products that should not be synced.
	 *
	 * @param int $id Product id.
	 *
	 * @return bool
	 */
	public function is_product_faire_sync_allowed( $id ) {
		$allowed = true;
		$product = wc_get_product( $id );
		if ( $product && in_array( $product->get_type(), array( 'grouped', 'external' ) ) ) {
			$allowed = false;
		}
		return apply_filters( 'faire_wc_product_is_sync_allowed', $allowed, $id );
	}

}
