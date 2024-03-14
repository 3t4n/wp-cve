<?php
defined( 'ABSPATH' ) || exit;

include_once OLIVER_POS_ABSPATH . 'includes/models/class-pos-bridge-product.php';
use bridge_models\Pos_Bridge_Product as Product;
/**
 *
 */

class Pos_Bridge_Product {

    private $pos_bridge_product;

    function __construct() {
        $this->pos_bridge_product 		= new Product();
    }

    public function oliver_pos_products( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['page'] ) && isset( $parameters['per_page'] ) ) {
            $product_data = $this->pos_bridge_product->oliver_pos_get_paged_products( sanitize_text_field( $parameters['page'] ), sanitize_text_field( $parameters['per_page'] ) );
        } else {
            $product_data = $this->pos_bridge_product->oliver_pos_get_paged_products( sanitize_text_field( 1 ), sanitize_text_field( 10 ) );
        }
        return $product_data;
    }

    public function oliver_pos_product( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['id'] ) || !empty( $parameters['id'] ) ) {
            $id = sanitize_text_field( $parameters['id'] );
            $product_data = $this->pos_bridge_product->oliver_pos_get_product_data( $id );
            return $product_data;
        }
        return array();
    }

    public function oliver_pos_variation_product( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['variation_id'] ) || !empty( $parameters['variation_id'] ) ) {
            $id = sanitize_text_field( $parameters['variation_id'] );
            $product_data = $this->pos_bridge_product->oliver_pos_get_variation_product_data( $id );
            return $product_data;
        }
        return array();
    }

    public function oliver_pos_get_remainig_products( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['remaining'] ) && !empty( $parameters['remaining'] ) ) {
            $product_data = $this->pos_bridge_product->oliver_pos_get_remainig_products( sanitize_text_field( $parameters['remaining'] ) );
            return $product_data;
        }
        return array();
    }

    /**
     * Get id and inventory of all products.
     *
     * @since 2.1.3.2
     * @return array Returns products array.
     */
    public function oliver_pos_get_products_stock_quantity() {
        return $this->pos_bridge_product->oliver_pos_get_products_stock_quantity();
    }

    /**
     * Get id,title and price of all products.
     * @since 2.1.3.2
     * @return array Returns products array.
     */
    public function oliver_pos_get_products_price_with_title() {
        return $this->pos_bridge_product->oliver_pos_get_products_price_with_title();
    }

    /**
     * Get product id and their child id.
     *
     * @since 2.3.5.1
     * @return array Returns products array.
     */
    public function oliver_pos_get_products_id_and_child_id() {
        return $this->pos_bridge_product->oliver_pos_get_products_id_and_child_id();
    }


    public function oliver_pos_update_oliver_inventory( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['product_id'] ) && !empty( $parameters['product_id'] ) ) {
            $product_id = sanitize_text_field( $parameters['product_id'] );
            $variation_id = sanitize_text_field( $parameters['variation_id'] );
            $quantity = sanitize_text_field( $parameters['quantity'] );
	        $warehouse_id = sanitize_text_field( $parameters['warehouse_id'] );

            $product_data = $this->pos_bridge_product->oliver_pos_update_oliver_inventory($product_id, $variation_id, $quantity, $warehouse_id);
            return $product_data;
        }
        return array();
    }

    /**
     * Fire while product CSV import
     *
     * @since 2.1.3.4
     * @param object $product
     * @param array $data
     * @return array Returns $product array..
     */
    public function oliver_pos_imported_product_listener( $product, $data ) {
        oliver_log("Start imported product listener trigger");
        if ( isset($data) && ! empty($data['id']) && $data['id'] > 0 ) {
            $product_id = is_numeric($data['id']) ? $data['id'] : (integer) $data['id'];

            oliver_log("imported product id = ".$product_id);

            if ( ! get_post_status($product_id) ) {
                oliver_log("Fire Create product trigger");
                //$this->product_sync_dotnet($product_id, esc_url_raw(ASP_TRIGGER_CREATE_PRODUCT));
                $this->oliver_pos_post_product_data_to_dotnet($product_id, esc_url_raw(ASP_TRIGGER_CREATE_PRODUCT));
            } else {
                oliver_log("Fire Update product trigger");
                //$this->product_sync_dotnet($product_id, esc_url_raw(ASP_TRIGGER_UPDATE_PRODUCT));
                $this->oliver_pos_post_product_data_to_dotnet($product_id, esc_url_raw(ASP_TRIGGER_UPDATE_PRODUCT));
            }
        }
        oliver_log("Stop imported product listener trigger");
    }
    //Since version 2.3.8.1
    //WooCommerce Add this feature from WooCommerce 3.x
    public function oliver_pos_product_update_listener( $product_id ) {
	    oliver_log("Start update product trigger");
	    $method = esc_url_raw( ASP_TRIGGER_UPDATE_PRODUCT );
	    oliver_log("End update product trigger");
	    if ( !empty($method) ) {
	        $this->oliver_pos_post_product_data_to_dotnet( $product_id, $method );
	    }
    }
    //Since version 2.3.8.1
    //Remove product update trigger functionality from this listener because from now we are using product update listener
    public function oliver_pos_product_listener( $post_id, $post, $update ) {
        $method = '';
        if ( get_post_status( $post_id ) == 'trash') {
            oliver_log("Start delete product trigger");

            $method = esc_url_raw( ASP_TRIGGER_REMOVE_PRODUCT );

            oliver_log("End delete product trigger");
            $this->oliver_pos_product_sync_dotnet( $post_id, $method );

        } else {
            if (get_the_time( 'Y-m-d H:i', $post_id ) === get_the_modified_time( 'Y-m-d H:i', $post_id )) {
                if ( in_array( get_post_status($post_id), array('publish', 'private'))) {
                    oliver_log("Start create product trigger");
                    $method = esc_url_raw( ASP_TRIGGER_CREATE_PRODUCT );
                    oliver_log("End create product trigger");
                    //$this->product_sync_dotnet( $post_id, $method );
                    $this->oliver_pos_post_product_data_to_dotnet( $post_id, $method );
                }
            }
            /*else {
            oliver_log("Start update product trigger");
                $method = esc_url_raw( ASP_TRIGGER_UPDATE_PRODUCT );
            oliver_log("End update product trigger");
        }*/
        }
    }

    /**
     * Fire while create duplicate product
     * @since 2.3.2.1
     * @param object $duplicate duplicate product object
     * @param object $product Original product object
     * @return void Call create product api for duplicate product
     */
    public function oliver_pos_duplicate_product_listener( $duplicate, $product ) {
        if (is_object($duplicate)) {
            $duplicate_id = $duplicate->get_id();

            if ( in_array( get_post_status($duplicate_id), array('publish', 'private'))) {
                oliver_log("Start duplicate product trigger");
                $method = esc_url_raw( ASP_TRIGGER_CREATE_PRODUCT );
                //$this->oliver_pos_product_sync_dotnet( $duplicate_id, $method );
                $this->oliver_pos_post_product_data_to_dotnet( $duplicate_id, $method );
                oliver_log("End duplicate product trigger");
            }
        }
    }

    public function oliver_pos_trigger_save_product_variation( $variation_id ) {
        oliver_log("Start save variation product trigger");
        $parent_id = wp_get_post_parent_id( $variation_id );
        $this->oliver_pos_product_sync_dotnet( $parent_id, esc_url_raw( ASP_TRIGGER_VARIATION_CREATE_PRODUCT ) );
        oliver_log("End save variation product trigger");
    }

    public function oliver_pos_trigger_update_product_variation( $variation_id ) {
        oliver_log("Start update variation product trigger");
        $this->oliver_pos_product_sync_dotnet( $variation_id, esc_url_raw( ASP_TRIGGER_VARIATION_UPDATE_PRODUCT ) );
        oliver_log("End update variation product trigger");
    }

    //Since update version 2.3.9.0
    //Modify this function for get-counts api
    public static function oliver_pos_product_count() {
        global $wpdb;
        $query = "SELECT ID FROM $wpdb->posts WHERE  post_type = %s AND post_status = %s ";
	    $count_child_product = count($wpdb->get_results($wpdb->prepare( $query, 'product_variation', 'publish')));
        $count_parent_product = count( get_posts( array('post_type' => 'product', 'post_status' => array('publish','private'), 'fields' => 'ids', 'posts_per_page' => '-1') ) );

        $total_product = ($count_child_product+$count_parent_product);
        return (int) $total_product;
    }

    /**
     * Get parent products count.
     * @since 2.3.3.1
     * @return int Returns count of parent products.
     */
    public static function oliver_pos_parent_product_count() {
        return ((int) wp_count_posts("product")->publish + (int) wp_count_posts("product")->private);
    }

    /**
     * Get all type products count.
     * @since 2.3.3.3
     * @param string $type Product type by default null
     * @return int Returns count of all type products.
     */
    //Since version 2.3.8.1
    //Modify this function for get-products-count api
    public static function oliver_pos_get_products_count( $type = null ) {
        $data = array();
        $wc_get_product_types = wc_get_product_types();
        if (is_plugin_active( 'woocommerce-bookings/woocommerce-bookings.php' )) {
			$wc_get_product_types = array_merge($wc_get_product_types, array("booking"=>"booking product"));
        }
        foreach ($wc_get_product_types as $key => $value) {
            $simple_count = count(wc_get_products(array(
                'status' => array( 'private', 'publish' ),
                'type' => $key,
                'limit' => -1
            )));
            if(!empty($simple_count))
            {
                $data[$key] = $simple_count;
            }
        }
        return is_null($type) ? $data : reset($data);
    }

    private function oliver_pos_product_sync_dotnet( $post_id, $method ) {
        $udid = ASP_DOT_NET_UDID;
        $url = "{$method}?udid={$udid}&wpid={$post_id}";
        wp_remote_get( esc_url_raw($url), array(
            'timeout'   => 0.01,
            'blocking'  => false,
            'sslverify' => false,
            'headers' => array(
	            'Authorization' => AUTHORIZATION,
            ),
        ));
    }
    /**
     * post product details.
     *
     * @since 2.3.8.8
     * @param int product id and post method
     */
    private function oliver_pos_post_product_data_to_dotnet( $product_id, $post_method ) {
        $product_data = $this->pos_bridge_product->oliver_pos_get_product_data( $product_id );
        wp_remote_post( esc_url_raw( $post_method ), array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => AUTHORIZATION,
            ),
            'body' => json_encode($product_data),
        ) );
    }
	/**
	 * Create product.
	 * @since 2.4.0.6
	 * @param int product post method
	 */
	public function oliver_pos_create_product( $request_data ) {
		if ( !empty( $request_data ) ) {
			$parameters = $request_data->get_params();
			$product_data = $this->pos_bridge_product->oliver_pos_create_product( $parameters );
			return $product_data;
		}
		return oliver_pos_api_response('Empty product data', -1);
	}
	/**
	 * Update Product.
	 * @since 2.4.0.6
	 * @param int product post method
	 */
	public function oliver_pos_update_product( $request_data ) {
		if ( !empty( $request_data ) ) {
			$parameters = $request_data->get_params();
			$product_data = $this->pos_bridge_product->oliver_pos_update_product( $parameters );
			return $product_data;
		}
		return oliver_pos_api_response('Empty product data', -1);
	}
	/**
	 * Delete Product.
	 * @since 2.4.0.6
	 * @param int product post method
	 */
	public function oliver_pos_delete_product( $request_data ) {
		$parameters = $request_data->get_params();
		if ( isset( $parameters['id'] ) && !empty( $parameters['id'] ) ) {
			$id = sanitize_text_field( $parameters['id'] );
			$product_delete = $this->pos_bridge_product->oliver_pos_delete_product( $id );
			return $product_delete;
		}
		return oliver_pos_api_response('Empty product ID', -1);
	}
	/**
	 * Get product id and its backorder value.
	 * @since 2.4.1.3
	 * @return array.
	 */
	public function oliver_pos_products_backorder( $request_data ) {
		$parameters = $request_data->get_params();
		if ( isset( $parameters['page'] ) && isset( $parameters['per_page'] ) ) {
			$product_data = $this->pos_bridge_product->oliver_pos_products_backorder( sanitize_text_field( $parameters['page'] ), sanitize_text_field( $parameters['per_page'] ) );
		} else {
			$product_data = $this->pos_bridge_product->oliver_pos_products_backorder( sanitize_text_field( 10 ), sanitize_text_field( 1 ) );
		}
		return $product_data;
	}
	/**
	 *  @since 2.4.0.5
	 *  To add oliver pos product visibility
	 *
	 */
	public function oliver_pos_product_data_visibility( ) {
		global $post, $thepostid, $product_object;
		if ( 'product' !== $post->post_type ) {
			return;
		}
		$thepostid          = $post->ID;
		$show_data = get_post_meta( $thepostid, 'visibility_oliver_pos', true );
		if( $show_data == 1 )
		{
			$data = 'Hide';
		} else{
			$data = 'Show';
		}
		?>
		<div class="misc-pub-section" id="oliver-pos-visibility" >
			<?php esc_html_e( 'Visibility oliver pos :', 'woocommerce' ); ?>
			<strong id="oliver-visibility-display">
				<?php
				echo $data;
				?>
			</strong>
			<a href="#oliver-pos-visibility" class="edit-oliver-pos-visibility hide-if-no-js"><?php esc_html_e( 'Edit', 'woocommerce' ); ?></a>
			<div id="oliver-pos-visibility-select" class="hide-if-js">
				</br>
				<input type="radio" id="contactChoice1" name="oliver_show" value="0" <?php if( $show_data == 0 ) { echo "checked";  } elseif( empty( $show_data ) ) { echo "checked"; } ?> >
				<label for="contactChoice1">Show</label>
				<input type="radio" id="contactChoice2" name="oliver_show" value="1" <?php if( $show_data == 1 ) { echo "checked";  } ?> >
				<label for="contactChoice2" >Hide</label>
				<p>
					<a href="#oliver-pos-visibility" class="save-post-visibility hide-if-no-js button"><?php esc_html_e( 'OK', 'woocommerce' ); ?></a>
					<a href="#oliver-pos-visibility" class="cancel-post-visibility hide-if-no-js"><?php esc_html_e( 'Cancel', 'woocommerce' ); ?></a>
				</p>
			</div>
		</div>
		<?php
	}
	public function oliver_pos_save_woocommerce_product_visibility( $post_id )
	{
		$product = wc_get_product( $post_id );
		$oliver_show = isset($_POST['oliver_show']) ? $_POST['oliver_show'] : '';
		$product->update_meta_data( 'visibility_oliver_pos', sanitize_text_field( $oliver_show ) , true);
		$product->save();
	}
	/**
	 * Show inventory for Warehouse Product for Simple woocommerce Product.
	 */
	public function oliver_pos_show_warehouse_simple_inventory() {
		global $post;
		global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '_warehouse_%' AND post_id = %d";
	    $results = $wpdb->get_results($wpdb->prepare( $query, $post->ID) );
		if(!empty($results)) { ?>
            <p class=" form-field _backorders_field">
                <label for="_backorders">Oliver Product Inventory</label>
            </p>
			<?php
			foreach ( $results as $result ) {
				$oliver_warehouse       = $result->meta_key;
				$oliver_warehouse       = explode( '_', $oliver_warehouse );
				$oliver_warehouse_value = $result->meta_value;
                $query_ware = "SELECT name, isdefault FROM {$wpdb->prefix}pos_warehouse WHERE oliver_warehouseid = %d";
				$data_warehouse = $wpdb->get_results($wpdb->prepare( $query_ware, $oliver_warehouse['2']) );
				if ( $data_warehouse ) {
					if ( $data_warehouse[0]->isdefault == 1 ) {
						continue;
					}
					$oliver_warehouse_value =  @unserialize( $oliver_warehouse_value );
					echo '<div class="product_custom_field">';
					woocommerce_wp_text_input(
						array(
							'id'                => '_warehouse_' . $oliver_warehouse['2'],
							'label'             => __( $data_warehouse[0]->name . '( quantity )', 'woocommerce' ),
							'desc_tip'          => 'true',
							'description'       => 'You can update only from HUB/Register.',
							'custom_attributes' => array( 'readonly' => 'readonly' ),
						)
					);
					echo '</div>';
				}
			}
		}
	}
	/**
	 * Show inventory for Warehouse Product for variable  woocommerce Product.
	 */
	public function oliver_pos_show_warehouse_variable_inventory( $loop, $variation_data, $variation_post ) {
		global $post;
		global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '_warehouse_%' AND post_id = %d";
	    $results = $wpdb->get_results($wpdb->prepare( $query, $variation_post->ID) );
		
		if(!empty($results)) { ?>
            <p class=" form-field _backorders_field">
                <label for="_backorders">Oliver Product Inventory</label>
            </p>
			<?php
			foreach ( $results as $result ) {
				$oliver_warehouse       =  $result->meta_key;
				$oliver_warehouse       =  explode( '_', $oliver_warehouse );
				$oliver_warehouse_value =  $result->meta_value;
				$warehouseid            = $oliver_warehouse['2'];
                $query_ware = "SELECT name, isdefault FROM {$wpdb->prefix}pos_warehouse WHERE oliver_warehouseid = %d";
	            $data_warehouse = $wpdb->get_results($wpdb->prepare( $query_ware, $warehouseid) );

				if ( $data_warehouse ) {
					if ( $data_warehouse[0]->isdefault == 1 ) {
						continue;
					}
					echo '<div class="product_custom_field">';
					woocommerce_wp_text_input(
						array(
							'id'                => "_warehouse_{$loop}_$warehouseid",
							'name'              => "_warehouse_{$loop}_$warehouseid",
							'label'             => __( $data_warehouse[0]->name . '( quantity )', 'woocommerce' ),
							'desc_tip'          => 'true',
							'value'             => $oliver_warehouse_value,
							'type'              => 'number',
							'custom_attributes' => array('readonly' => 'readonly'),
							'description'       => 'You can update only from HUB/Register.',
						)
					);
					echo '</div>';
				}
			}
		}
	}
	/**
	 *Variations: Save warehouse inventory value from admin variation options
	 */
    public function oliver_pos_save_warehouse_variation_inventory( $variation_id, $i ) {
	    global $post;
	    global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '_warehouse_%' AND post_id = %d";
	    $results = $wpdb->get_results($wpdb->prepare( $query, $variation_id) );
	    foreach ( $results as $result ) {
		    $oliver_warehouse =  $result->meta_key;
		    $oliver_warehouse =  explode( '_', $oliver_warehouse );
		    $oliver_warehouse_value =  $result->meta_value;
		    $oliver_warehouse_value =  @unserialize( $oliver_warehouse_value );
		    $location_id = $oliver_warehouse['2'];
		    if( isset( $_POST['_warehouse_' . $i . '_' . $location_id] ) ) {
			    update_post_meta( $variation_id, '_warehouse_' . $oliver_warehouse['2'] , sanitize_text_field( $_POST['_warehouse_' . $i . '_' . $location_id] ) );
		    }
	    }
    }
	/**
	 *Simple: Save warehouse inventory value from admin
	 */
	function oliver_pos_save_warehouse_simple_inventory( $product ) {
		global $post;
		global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key LIKE '_warehouse_%' AND post_id = %d";
	    $results = $wpdb->get_results($wpdb->prepare( $query, $post->ID) );
		foreach ( $results as $result ) {
			$oliver_warehouse =  $result->meta_key;
			$oliver_warehouse =  explode("_",$oliver_warehouse);
			$warehouseName    = $oliver_warehouse['2'];
			if ( isset( $_POST['_warehouse_' . $warehouseName] ) ) {
				$product->update_meta_data( '_warehouse_' . $warehouseName, sanitize_text_field( $_POST['_warehouse_' . $warehouseName] ) );
			}
		}
	}
}