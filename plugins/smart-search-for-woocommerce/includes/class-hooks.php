<?php
/**
 * Searchanise hooks
 *
 * @package Searchanise/Hooks
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise hooks class
 */
class Hooks extends Abstract_Extension {

	const HOOKS = array(
		'woocommerce_process_product_meta',
		'woocommerce_update_product',
		'woocommerce_update_product_variation',
		'woocommerce_new_product_variation',
		'trashed_post',
		'untrashed_post',
		'save_post',
		'comment_post',
		'deleted_review_meta',
		'transition_comment_status',
		'wp_update_comment_count',
		'edited_product_cat',
		'create_product_cat',
		'delete_product_cat',
		'edited_product_tag',
		'create_product_tag',
		'delete_product_tag',
		'pre_delete_term',
		'woocommerce_attribute_added',
		'woocommerce_attribute_updated',
		'woocommerce_attribute_deleted',
		'edited_terms',
		'delete_term',
		'woocommerce_order_status_changed',
		'update_option',
		'woocommerce_product_import_before_import',
		'woocommerce_new_order',
		'woocommerce_update_order',
		'woocommerce_before_delete_order_item',
		'woocommerce_delete_order',
	);

	const FILTERS = array(
		'pre_trash_post',
		'woocommerce_product_data_store_cpt_get_products_query',
		'searchanise_async_memory_limit',
	);

	const INDEXATION_SETTINGS = array(
		'woocommerce_calc_taxes',
		'woocommerce_prices_include_tax',
		'woocommerce_tax_display_shop',
		'woocommerce_manage_stock',
		'woocommerce_hide_out_of_stock_items',
		'cptui_taxonomies',
		'WPLANG',
		'gmt_offset',
	);

	/**
	 * Updated categories
	 *
	 * @var array
	 */
	private $product_cat_ids  = null;

	/**
	 * Updated tags
	 *
	 * @var array
	 */
	private $product_tag_ids = null;

	/**
	 * Hooks constructor
	 */
	public function __construct() {
		// Should be inited first.
		register_activation_hook( SE_ABSPATH . DIRECTORY_SEPARATOR . 'woocommerce-searchanise.php', array( $this, 'activateAddon' ) );
		register_deactivation_hook( SE_ABSPATH . DIRECTORY_SEPARATOR . 'woocommerce-searchanise.php', array( $this, 'deactivateAddon' ) );

		add_action(
			'plugins_loaded',
			function () {
				parent::__construct();
			}
		);
	}

	/**
	 * Returns if module active
	 *
	 * @return bool
	 */
	public function is_active() {
		return Api::get_instance()->get_module_status() == 'Y';
	}

	/**
	 * Returns hooks list
	 *
	 * @return array
	 */
	protected function get_hooks() {
		return self::HOOKS;
	}

	/**
	 * Returns filter list
	 *
	 * @return array
	 */
	protected function get_filters() {
		return self::FILTERS;
	}

	/************************************
	 * Activate / Deactivate hooks
	 ***********************************/

	/**
	 * Set the activation hook for a plugin.
	 *
	 * @since 2.0.0
	 */
	public function activateAddon() {
		global $wp_version;

		if ( ! is_multisite() ) {
			// Unregister old tasks if exist.
			Cron::unregister();
			Installer::install();

			// If addon already was installed, run import.
			if ( ! Api::get_instance()->check_auto_install() ) {
				Api::get_instance()->set_is_need_reindexation( true );
			}

			// Register searchanise info page.
			add_rewrite_rule( '^searchanise/info', 'index.php?is_searchanise_page=1&post_type=page', 'top' );
			flush_rewrite_rules();
		}

		if ( version_compare( $wp_version, Api::MIN_WORDPRESS_VERSION ) < 0 ) {
			Api::get_instance()->add_admin_notitice(
				/* translators: version */
				sprintf( esc_html__( 'Plugin is compatible with Wordpress version %1$s or higher. Plugin may work incorrectly. Please upgrade your Wordpress to %2$s version or highter', 'woocommerce-searchanise' ), Api::MIN_WORDPRESS_VERSION, Api::MIN_WORDPRESS_VERSION ),
				'error'
			);
		}

		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, Api::MIN_WOOCOMMERCE_VERSION ) < 0 ) {
			Api::get_instance()->add_admin_notitice(
				/* translators: version */
				sprintf( esc_html__( 'The plugin is compatible with WooCommerce version %1$s or higher. The plugin may work incorrectly. Please upgrade your WooCommerce to %2$s version or highter', 'woocommerce-searchanise' ), Api::MIN_WOOCOMMERCE_VERSION, Api::MIN_WOOCOMMERCE_VERSION ),
				'error'
			);
		}
	}

	/**
	 * Set the deactivation hook for a plugin.
	 *
	 * @since 2.0.0
	 */
	public function deactivateAddon() {
		Queue::get_instance()->clear_actions();
		$engines = Api::get_instance()->get_engines();

		foreach ( $engines as $engine ) {
			Api::get_instance()->addon_status_request( Api::ADDON_STATUS_DISABLED, $engine['lang_code'] );
		}

		Installer::uninstall();
		Cron::unregister();
	}

	/************************************
	 * Products hooks
	 ***********************************/

	 /**
	  * Save Product Meta Boxes.
	  * TODO: Need refactored, since this hooks doesn't exist in new WC docs.
	  *
	  * @param int $product_id Product id.
	  */
	public function processProductMeta( $product_id ) {
		$this->addProductToQueue( $product_id );
	}

	/**
	 * Update product
	 *
	 * @param int $product_id Product id.
	 */
	public function updateProduct( $product_id ) {
		$this->addProductToQueue( $product_id );
	}

	/**
	 * Update product variantion
	 *
	 * @param int $product_id Product id.
	 */
	public function updateProductVariation( $product_id ) {
		$this->addProductToQueue( $product_id );
	}

	/**
	 * Create new product variation
	 *
	 * @param int $variation_id Variation id.
	 *
	 * @since WC 3.0.0
	 */
	public function newProductVariation( $variation_id ) {
		$variation = get_post( $variation_id );

		if ( $variation && $variation->parent_id ) {
			$this->addProductToQueue( $variation->parent_id );
		}
	}

	/**
	 * Delete product action
	 *
	 * @param mixed $product_id Product id.
	 */
	private function deleteProduct( $product_id ) {
		Queue::get_instance()->add_action_delete_products( $product_id );
	}

	/**
	 * WooCommerce hooks fires before import starts
	 *
	 * @param mixed $paresed_data Parsed data.
	 */
	public function productImportBeforeImport( $paresed_data ) {
		static $started = false;

		if ( false == $started ) {
			// There is not end import hook in woocommerce.
			// So, end import is script ending.
			register_shutdown_function(
				function ( $lang_code ) {
					Queue::get_instance()->insert_data(
						array(
							'data'      => Queue::NO_DATA,
							'action'    => Queue::UPDATE_ATTRIBUTES,
							'lang_code' => $lang_code,
						)
					);
				},
				Api::get_instance()->get_locale()
			);

			$started = true;
		}
	}

	/**
	 * Get product parent ids (for grouped products)
	 *
	 * @param WC_Product $product Product.
	 *
	 * @return array
	 */
	private function getProductParentIds( $product ) {
		$parents = get_posts(
			array(
				'post_type'   => 'product',
				'meta_query'  => array(
					array(
						'key'     => '_children',
						'value'   => 'i:' . $product->get_id() . ';',
						'compare' => 'LIKE',
					),
				),
				'fields' => 'ids', // THIS LINE FILTERS THE SELECT SQL.
			)
		);

		return $parents;
	}

	/************************************
	 * Pages Hooks
	 ***********************************/

	/**
	 * Update page action
	 *
	 * @param int $page_id Page id.
	 */
	private function updatePage( $page_id ) {
		$page = get_post( $page_id );
		$excluded_pages = array_merge(
			Async::get_instance()->get_excluded_pages(),
			array(
				Api::get_instance()->get_system_setting( 'search_result_page' ),
			)
		);

		if ( ! in_array( $page->post_name, $excluded_pages ) ) {
			Queue::get_instance()->add_action_update_pages( $page_id );
		}
	}

	/**
	 * Delete page action
	 *
	 * @param int $page_id Page id.
	 */
	private function deletePage( $page_id ) {
		$page = get_post( $page_id );
		$excluded_pages = array_merge(
			Async::get_instance()->get_excluded_pages(),
			array(
				Api::get_instance()->get_system_setting( 'search_result_page' ),
			)
		);

		if ( ! in_array( $page->post_name, $excluded_pages ) ) {
			Queue::get_instance()->add_action_delete_pages( $page_id );
		}
	}

	/**
	 * Fires once a post has been saved.
	 *
	 * @since 1.5.0
	 *
	 * @param int     $post_ID Post ID.
	 * @param WP_Post $post    Post object.
	 * @param bool    $update  Whether this is an existing post being updated or not.
	 */
	public function savePost( $post_ID, $post, $update ) {
		if ( $post instanceof \WP_Post && in_array( $post->post_type, Async::get_post_types() ) ) {
			if ( 'publish' == $post->post_status ) {
				// Page is visible.
				$this->updatePage( $post->ID );
			} else {
				// Not visible, Hidden, Password protected and etc.
				$this->deletePage( $post->ID );
			}
		}
	}

	/************************************
	 * Categories Hooks
	 ***********************************/

	/**
	 * Returns assigned products list for category
	 *
	 * @param int $category_id Category id.
	 *
	 * @return array
	 */
	private function getProductIdsByCategoryId( $category_id ) {
		$product_ids = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => array( 'draft', 'pending', 'private', 'publish' ),
				'posts_per_page' => -1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $category_id,
						'operator' => 'IN',
					),
				),
				'fields'           => 'ids', // THIS LINE FILTERS THE SELECT SQL.
			)
		);

		return array_unique( $product_ids );
	}

	/**
	 * Fires after a term for a specific taxonomy has been updated, and the term
	 * cache has been cleaned.
	 *
	 * The dynamic portion of the hook name, `$taxonomy`, refers to the taxonomy slug.
	 *
	 * @since 2.3.0
	 *
	 * @param int $term_id Term Id.
	 */
	public function editedProductCat( $term_id ) {
		if ( ! $this->checkProductCat( $term_id ) ) {
			return;
		}

		Queue::get_instance()->add_action_update_category( $term_id );
	}

	/**
	 * Fires after a new term is created for a specific taxonomy.
	 *
	 * The dynamic portion of the hook name, `$taxonomy`, refers
	 * to the slug of the taxonomy the term was created for.
	 *
	 * @since 2.3.0
	 *
	 * @param int $term_id Term ID.
	 */
	public function createProductCat( $term_id ) {
		if ( ! $this->checkProductCat( $term_id ) ) {
			return;
		}

		Queue::get_instance()->add_action_update_category( $term_id );
	}

	/**
	 * Fires after a term in a specific taxonomy is deleted.
	 *
	 * The dynamic portion of the hook name, `$taxonomy`, refers to the specific
	 * taxonomy the term belonged to.
	 *
	 * @since 2.3.0
	 * @since 4.5.0 Introduced the `$object_ids` argument.
	 *
	 * @param int   $term         Term ID.
	 * @param int   $tt_id        Term taxonomy ID.
	 * @param mixed $deleted_term Copy of the already-deleted term, in the form specified
	 *                            by the parent function. WP_Error otherwise.
	 */
	public function deleteProductCat( $term, $tt_id, $deleted_term ) {
		Queue::get_instance()->add_action_delete_categories( $term );

		// Update related products.
		if ( ! empty( $this->product_cat_ids ) ) {
			Queue::get_instance()->add_action_update_products( $this->product_cat_ids );
			$this->product_cat_ids = null;
		}
	}

	/**
	 * Check product category
	 *
	 * @param int $term_id Category_id.
	 * @return boolean
	 */
	private function checkProductCat( $term_id ) {
		$cat = get_term( $term_id, 'product_cat' );

		if ( $cat instanceof \WP_Term && ! in_array( $cat->slug, Async::get_instance()->get_excluded_categories() ) ) {
			return true;
		}

		return false;
	}

	/************************************
	 * Product tags
	 ***********************************/

	/**
	 * Fires after a new term is created for a specific taxonomy.
	 *
	 * The dynamic portion of the hook name, `$taxonomy`, refers
	 * to the slug of the taxonomy the term was created for.
	 *
	 * @since 2.3.0
	 *
	 * @param int $term_id Term ID.
	 */
	public function createProductTag( $term_id ) {
		// New product tag, nothing to do.
	}

	/**
	 * Fires after a term for a specific taxonomy has been updated, and the term
	 * cache has been cleaned.
	 *
	 * The dynamic portion of the hook name, `$taxonomy`, refers to the taxonomy slug.
	 *
	 * @since 2.3.0
	 *
	 * @param int $term_id Term ID.
	 * @param int $tt_id   Term taxonomy ID.
	 */
	public function editedProductTag( $term_id, $tt_id ) {
		$product_ids = $this->getProductIdsByTagId( $term_id );

		if ( ! empty( $product_ids ) ) {
			$this->updateProduct( $product_ids );
		}
	}

	/**
	 * Fires after a term in a specific taxonomy is deleted.
	 *
	 * The dynamic portion of the hook name, `$taxonomy`, refers to the specific
	 * taxonomy the term belonged to.
	 *
	 * @since 2.3.0
	 * @since 4.5.0 Introduced the `$object_ids` argument.
	 *
	 * @param int   $term         Term ID.
	 * @param int   $tt_id        Term taxonomy ID.
	 * @param mixed $deleted_term Copy of the already-deleted term, in the form specified
	 *                            by the parent function. WP_Error otherwise.
	 */
	public function deleteProductTag( $term, $tt_id, $deleted_term ) {
		$product_ids = $this->getProductIdsByTagId( $term );

		// Update related products if set.
		if ( ! empty( $this->product_tag_ids ) ) {
			$this->updateProduct( $this->product_tag_ids );
			$this->product_tag_ids = null;
		}
	}

	/**
	 * Return related product ids by tag id
	 *
	 * @param int $product_tag_id Product tag id.
	 *
	 * @return array
	 */
	private function getProductIdsByTagId( $product_tag_id ) {
		$product_ids = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => array( 'draft', 'pending', 'private', 'publish' ),
				'posts_per_page' => -1,
				'tax_query'      => array(
					array(
						'taxonomy' => 'product_tag',
						'field'    => 'term_id',
						'terms'    => $product_tag_id,
						'operator' => 'IN',
					),
				),
				'fields'           => 'ids', // THIS LINE FILTERS THE SELECT SQL.
			)
		);

		return array_unique( $product_ids );
	}

	/************************************
	 * Attributes Hooks
	 ***********************************/

	/**
	 * Attribute added.
	 *
	 * @param int   $id   Added attribute ID.
	 * @param array $data Attribute data.
	 */
	public function attributeAdded( $id, $data ) {
		Queue::get_instance()->add_action_update_attributes();
	}

	/**
	 * Attribute updated.
	 *
	 * @param int    $id        Added attribute ID.
	 * @param array  $data      Attribute data.
	 * @param string $old_slug  Attribute old name.
	 */
	public function attributeUpdated( $id, $data, $old_slug ) {
		$product_ids = $this->getProductIdsByTaxonomy( wc_attribute_taxonomy_name( $data['attribute_name'] ) );

		if ( ! empty( $product_ids ) ) {
			$this->addProductToQueue( $product_ids );
		}

		Queue::get_instance()->add_action_update_attributes();
	}

	/**
	 * Returns product ids by product taxonomy
	 *
	 * @param string $taxonomy Taxonomy.
	 *
	 * @return array
	 */
	private function getProductIdsByTaxonomy( $taxonomy ) {
		global $wpdb;

		if ( empty( $taxonomy ) ) {
			return array();
		}

		// Update products including this attribute.
		$product_ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT(ID) FROM {$wpdb->prefix}posts AS p
				LEFT JOIN {$wpdb->prefix}term_relationships AS tr ON tr.object_id = p.ID
				LEFT JOIN {$wpdb->prefix}term_taxonomy AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
				WHERE p.post_type = %s AND tt.taxonomy = %s",
				'product',
				$taxonomy
			)
		);

		/**
		 * Returns product ids by product taxonomy
		 *
		 * @since 1.0.0
		 */
		return (array) apply_filters( 'se_wc_get_product_ids_by_taxonomy', $product_ids, $taxonomy );
	}

	/**
	 * After deleting an attribute.
	 *
	 * @param int    $id       Attribute ID.
	 * @param string $name     Attribute name.
	 * @param string $taxonomy Attribute taxonomy name.
	 */
	public function attributeDeleted( $id, $name, $taxonomy ) {
		Queue::get_instance()->add_action_delete_facets( $name );
	}

	/**
	 * Fires immediately after the given terms are edited.
	 *
	 * @since 2.9.0
	 *
	 * @param int    $term_id  Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	public function editedTerms( $term_id, $taxonomy ) {
		$attribute_id = wc_attribute_taxonomy_id_by_name( $taxonomy );

		if ( ! empty( $attribute_id ) ) {
			$product_ids = $this->getProductIdsByTaxonomy( $taxonomy );

			if ( ! empty( $product_ids ) ) {
				$this->addProductToQueue( $product_ids );
			}
		}
	}

	/**
	 * Fires after a term is deleted from the database and the cache is cleaned.
	 *
	 * @since 2.5.0
	 * @since 4.5.0 Introduced the `$object_ids` argument.
	 *
	 * @param int    $term         Term ID.
	 * @param int    $tt_id        Term taxonomy ID.
	 * @param string $taxonomy     Taxonomy slug.
	 * @param mixed  $deleted_term Copy of the already-deleted term, in the form specified
	 *                             by the parent function. WP_Error otherwise.
	 * @param array  $object_ids   List of term object IDs.
	 */
	public function deleteTerm( $term, $tt_id, $taxonomy, $deleted_term, $object_ids ) {
		$attribute_id = wc_attribute_taxonomy_id_by_name( $taxonomy );

		if ( ! empty( $attribute_id ) ) {
			if ( isset( $object_ids ) ) {
				// Update related products for WP 4.5.0+.
				$this->addProductToQueue( $object_ids );

			} else {
				// Update related products for WP lower than 4.5.0.
				$product_ids = $this->getProductIdsByTaxonomy( $taxonomy );

				if ( ! empty( $product_ids ) ) {
					$this->addProductToQueue( $product_ids );
				}
			}
		}
	}

	/************************************
	 * Reviews Hooks
	 ***********************************/

	/**
	 * Fires immediately after a comment is inserted into the database.
	 *
	 * @since 1.2.0
	 * @since 4.5.0 The `$commentdata` parameter was added.
	 *
	 * @param int        $comment_id       The comment ID.
	 * @param int|string $comment_approved 1 if the comment is approved, 0 if not, 'spam' if spam.
	 */
	public function commentPost( $comment_id, $comment_approved ) {
		$commentdata = get_comment( $comment_id, ARRAY_A );

		if (
			! empty( $commentdata['comment_post_ID'] )
			&& 'review' == $commentdata['comment_type']
			&& 1 === $comment_approved ) {
			$this->addProductToQueue( $commentdata['comment_post_ID'] );
		}
	}

	/**
	 * Fires when the comment status is in transition.
	 *
	 * @since 2.7.0
	 *
	 * @param int|string $new_status The new comment status.
	 * @param int|string $old_status The old comment status.
	 * @param object     $comment    The comment data.
	 */
	public function transitionCommentStatus( $new_status, $old_status, $comment ) {
		if ( $comment && 'review' == $comment->comment_type ) {
			$this->addProductToQueue( $comment->comment_post_ID );
		}
	}

	/**
	 * Fires immediately after a post's comment count is updated in the database.
	 *
	 * @since 2.3.0
	 *
	 * @param int $post_id Post ID.
	 * @param int $new     The new comment count.
	 * @param int $old     The old comment count.
	 */
	public function updateCommentCount( $post_id, $new, $old ) {
		$post = get_post( $post_id );

		if ( $post && 'product' == $post->post_type ) {
			$this->addProductToQueue( $post->ID );
		}
	}

	/**
	 * Fires immediately after deleting metadata of a specific type.
	 *
	 * The dynamic portion of the hook name, `$meta_type`, refers to the meta
	 * object type (comment, post, term, or user).
	 *
	 * @since 2.9.0
	 *
	 * @param array  $meta_ids    An array of deleted metadata entry IDs.
	 * @param int    $object_id   Object ID.
	 * @param string $meta_key    Meta key.
	 * @param mixed  $_meta_value Meta value.
	 */
	public function deletedReviewMeta( $meta_ids, $object_id, $meta_key, $_meta_value ) {
		$this->addProductToQueue( $object_id );
	}

	/************************************
	 * Sale data hooks
	 ***********************************/

	/**
	 * Changed order status
	 *
	 * @param int    $order_id    Order id.
	 * @param string $status_from From status.
	 * @param string $status_to   To status.
	 */
	public function orderStatusChanged( $order_id, $status_from, $status_to ) {
		$this->updateSalesData( $order_id );
	}

	/**
	 * Update sales data for order
	 *
	 * @param int $order_id Order id.
	 */
	private function updateSalesData( $order_id ) {
		$order = wc_get_order( $order_id );

		if (
			$order &&
			$order instanceof \WC_Order &&
			$order instanceof \WC_Order_Refund
		) {
			$product_ids = array();

			foreach ( $order->get_items() as $item_id => $item ) {
				$data = $item->get_data();

				if ( ! empty( $data['product_id'] ) ) {
					$product_ids[] = $data['product_id'];
				}

				$this->addProductToQueue( $product_ids );
			}
		}
	}

	/************************************
	 * Orders Hooks
	 ***********************************/

	/**
	 * New order hook
	 *
	 * @param int $order_id Order id.
	 */
	public function newOrder( $order_id ) {
		$this->updateOrder( $order_id );
	}

	/**
	 * Update order by id
	 *
	 * @param int $order_id Order id.
	 */
	public function updateOrder( $order_id ) {
		if ( ! Api::get_instance()->import_also_bought_products() ) {
			return;
		}

		$order = wc_get_order( $order_id );

		if (
			$order &&
			$order instanceof \WC_Order &&
			$order instanceof \WC_Order_Refund
		) {
			/**
			 * Order item
			 *
			 * @var WC_Order_Item
			 */
			$items = $order->get_items();

			$order_product_ids = array();
			foreach ( $items as $item ) {
				$order_product_ids[] = $item->get_product_id();
			}

			if ( ! empty( $order_product_ids ) ) {
				$this->addProductToQueue( $order_product_ids );
			}
		}
	}

	/**
	 * Actions before delete order item
	 *
	 * @param int $order_item_id Order item id.
	 */
	public function beforeDeleteOrderItem( $order_item_id ) {
		global $wpdb;

		if ( ! Api::get_instance()->import_also_bought_products() ) {
			return;
		}

		$product_ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT
				meta_value AS product_id
			FROM {$wpdb->prefix}woocommerce_order_itemmeta
			WHERE order_item_id = %d AND meta_key = %s",
				$order_item_id,
				'_product_id'
			)
		);

		if ( ! empty( $product_ids ) ) {
			$this->addProductToQueue( $product_ids );
		}
	}

	/**
	 * Delete order action
	 *
	 * @param int $order_id Order id.
	 */
	public function deleteOrder( $order_id ) {
		if ( ! empty( $order_id ) && Api::get_instance()->import_also_bought_products() ) {
			// Order was deleted to trash.
			$this->updateOrder( $order_id );
		}
	}

	/************************************
	 * Setting hooks
	 ***********************************/

	/**
	 * Fires immediately before an option value is updated.
	 *
	 * @since 2.9.0
	 *
	 * @param string $option_id Name of the option to update.
	 * @param mixed  $old_value The old option value.
	 * @param mixed  $value     The new option value.
	 */
	public function updateOption( $option_id, $old_value, $value ) {
		static $need_reindexation = false;

		if (
			in_array( $option_id, self::INDEXATION_SETTINGS )
			&& $old_value != $value
			&& ! $need_reindexation ) {
			if ( in_array( $option_id, array( 'WPLANG', 'gmt_offset' ) ) ) {
				Api::get_instance()->set_is_need_reindexation( true );
				return;
			}

			if ( 'cptui_taxonomies' == $option_id ) {
				foreach ( $old_value as $old ) {
					Queue::get_instance()->add_action_delete_facets( $old['name'] );
				}

				foreach ( $value as $val ) {
					$product_ids = $this->getProductIdsByTaxonomy( $val['name'] );

					if ( ! empty( $product_ids ) ) {
						$this->addProductToQueue( $product_ids );
					}
				}

				Queue::get_instance()->add_action_update_attributes();
				return;
			}

			Api::get_instance()->add_admin_notitice(
				sprintf(
					/* translators: link */
					__( 'Catalog should be re-indexed to display correct data in storefront. Please <a href="%s">force re-indexation</a>', 'woocommerce-searchanise' ),
					Api::get_instance()->get_admin_url( 'reindex' )
				),
				'warning'
			);
			$need_reindexation = true;
		}
	}

	/************************************
	 * Common hooks and functions
	 ***********************************/

	/**
	 * Fires when deleting a term, before any modifications are made to posts or terms.
	 *
	 * @since 4.1.0
	 *
	 * @param int    $term     Term ID.
	 * @param string $taxonomy Taxonomy Name.
	 */
	public function preDeleteTerm( $term, $taxonomy ) {
		if ( 'product_tag' == $taxonomy ) {
			$this->product_tag_ids = $this->getProductIdsByTagId( $term );
		}

		if ( 'product_cat' == $taxonomy ) {
			$this->product_cat_ids = $this->getProductIdsByCategoryId( $term );
		}
	}

	/**
	 * Filters whether a post trashing should take place.
	 *
	 * @since 4.9.0
	 *
	 * @param bool    $check Whether to go forward with trashing.
	 * @param WP_Post $post  Post object.
	 */
	public function preTrashPost( $check, $post ) {
		// Disable Search page trashing.
		if (
			Api::get_instance()->is_result_widget_enabled( Api::get_instance()->get_locale() )
			&& ! empty( $post )
			&& Installer::create_search_results_page() == $post->ID
		) {
			$check = false;
		}

		return $check;
	}

	/**
	 * Attempts to raise the PHP memory limit for memory intensive processes.
	 *
	 * Only allows raising the existing limit and prevents lowering it.
	 *
	 * @since 4.6.0
	 *
	 * @param int|string $filtered_limit Maximum memory limit to allocate for images.
	 *                                   Default `WP_MAX_MEMORY_LIMIT` or the original
	 *                                   php.ini `memory_limit`, whichever is higher.
	 *                                   Accepts an integer (bytes), or a shorthand string
	 *                                   notation, such as '256M'.
	 * @return int|string|false The limit that was set or false on failure.
	 */
	public function searchaniseAsyncMemoryLimit( $filtered_limit ) {
		$async_memory_limit = Api::get_instance()->get_async_memory_limit();

		return wp_convert_hr_to_bytes( $filtered_limit ) > wp_convert_hr_to_bytes( $async_memory_limit )
			? $filtered_limit
			: $async_memory_limit;
	}

	/**
	 * Fires after a post is sent to the trash.
	 *
	 * @since 2.9.0
	 *
	 * @param int $post_id Post ID.
	 */
	public function trashedPost( $post_id ) {
		$post = get_post( $post_id );

		if ( $post instanceof \WP_Post ) {
			switch ( $post->post_type ) {
				case 'product':
					$this->deleteProduct( $post->ID );
					break;
				case 'page':
				case 'post':
					if ( Api::get_instance()->is_result_widget_enabled( Api::get_instance()->get_locale() ) && Installer::create_search_results_page() == $post->ID ) {
						wp_untrash_post( $post->ID );
						Api::get_instance()->add_admin_notitice(
							/* translators: title */
							sprintf( __( 'Page "%s" is used to display Searchanise search results and cannot be deleted. Page was restored from trash.', 'woocommerce-searchanise' ), $post->post_title ),
							'warning'
						);
					} elseif ( in_array( $post->post_type, Async::get_post_types() ) ) {
						$this->deletePage( $post->ID );
					}
					break;
				case 'product_variation':
					$this->updateProduct( $post->parent_id );
					break;
				case 'shop_order':
					$this->updateSalesData( $post->ID );
					break;
			}
		}
	}

	/**
	 * Fires after a post is restored from the trash.
	 *
	 * @since 2.9.0
	 *
	 * @param int $post_id Post ID.
	 */
	public function untrashedPost( $post_id ) {
		$post = get_post( $post_id );

		if ( $post ) {
			switch ( $post->post_type ) {
				case 'product':
					$this->updateProduct( $post->ID );
					break;
				case 'page':
				case 'post':
					if ( in_array( $post->post_type, Async::get_post_types() ) ) {
						$this->updatePage( $post->ID );
					}
					break;
				case 'product_variation':
					$this->updateProduct( $post->parent_id );
					break;
				case 'shop_order':
					$this->updateSalesData( $post->ID );
					break;
			}
		}
	}

	/**
	 * Adds products to Searchanise queue
	 *
	 * @param mixed $product_id  Product identifier or product lists.
	 */
	private function addProductToQueue( $product_id ) {
		if ( empty( $product_id ) ) {
			return;
		}

		$product_ids = is_array( $product_id ) ? $product_id : array( $product_id );
		$products = wc_get_products(
			array(
				'include' => $product_ids,
				'status'  => array(),
				'limit'   => -1,
				'for_searchanise' => true,
			)
		);

		if ( ! empty( $products ) ) {
			$to_update = array();

			foreach ( $products as $product ) {
				$parent_ids = array();
				// WooCommerce versions 2.x.
				if ( $product->get_parent_id() ) {
					$parent_ids[] = $product->get_parent_id();
				}

				// WooCommerce versions 3.x.
				$grouped_parent_ids = $this->getProductParentIds( $product );
				if ( ! empty( $grouped_parent_ids ) ) {
					$parent_ids = array_merge( $parent_ids, $grouped_parent_ids );
				}

				$to_update = array_merge( $to_update, $parent_ids, array( $product->get_id() ) );
			}

			Queue::get_instance()->add_action_update_products( array_unique( $to_update ) );
		}
	}

	/**
	 * Modifies query args built by get_wp_query_args() inside wc_get_products()
	 *
	 * @param array                     $wp_query_args WP_Query's args to modify.
	 * @param array                     $query_vars WC_Product_Query's query variables used to build $wp_query_args.
	 * @param WC_Product_Data_Store_CPT $object Object to work with product custom post type.
	 *
	 * @return array
	 */
	public function productDataStoreCptGetProductsQuery( $wp_query_args, $query_vars, $object ) {
		$fix_empty_product_type = isset( $wp_query_args['for_searchanise'] ) && $wp_query_args['for_searchanise'];
		$is_product_post_type = 'product' == $wp_query_args['post_type'];

		if ( $fix_empty_product_type && $is_product_post_type ) {
			foreach ( $wp_query_args['tax_query'] as $index => $tax_query_part ) {
				$not_product_variation = (
					'product_type' == $tax_query_part['taxonomy']
					&& 'slug' == $tax_query_part['field']
					&& ! empty( $tax_query_part['terms'] )
				);

				if ( $not_product_variation ) {
					$wp_query_args['tax_query'][ $index ] = array(
						'relation' => 'OR',
						$tax_query_part,
						array(
							'taxonomy' => 'product_type',
							'field'    => 'id',
							'operator' => 'NOT EXISTS',
						),
					);
				}
			}
		}

		return $wp_query_args;
	}
}
