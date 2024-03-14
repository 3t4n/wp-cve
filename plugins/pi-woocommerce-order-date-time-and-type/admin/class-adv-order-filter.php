<?php

if(!class_exists('pisol_dtt_adv_order_filter')){
	class pisol_dtt_adv_order_filter{

		function __construct(){

			add_action( 'restrict_manage_posts', [$this,'pickup_location_filter'] );
			add_action( 'woocommerce_order_list_table_restrict_manage_orders', [$this,'pickup_location_filterHPOS'] ); //hpos

			add_action( 'pre_get_posts', [$this,'process_admin_shop_order_language_filter']);
			add_filter( 'woocommerce_shop_order_list_table_prepare_items_query_args', array($this, 'process_admin_shop_order_language_filter_hpos')  ); //hpos
		}

		function filterFields(){
			$domain    = 'woocommerce';

				$type   = isset($_GET['filter_delivery_type']) ? sanitize_text_field($_GET['filter_delivery_type']) : '';

				echo '<select name="filter_delivery_type">
				<option value="">' . __('Filter By Delivery type', 'pisol-dtt') . '</option>
				<option value="pickup" '.("pickup" === $type ? ' selected="selected" ' : '').'>' . __('Pickup', 'pisol-dtt') . '</option>
				<option value="delivery" '.("delivery" === $type ? ' selected="selected" ' : '').'>' . __('Delivery', 'pisol-dtt') . '</option>';
				echo '</select>';

				$from_date   = isset($_GET['filter_delivery_from_date'])? sanitize_text_field($_GET['filter_delivery_from_date']) : '';

				echo '<input type="text" name="filter_delivery_from_date" value="'.esc_attr($from_date).'" placeholder="From date" readonly/>';

				$to_date   = isset($_GET['filter_delivery_to_date'])? sanitize_text_field($_GET['filter_delivery_to_date']) : '';

				echo '<input type="text" name="filter_delivery_to_date" value="'.esc_attr($to_date).'" placeholder="Up to date" readonly/>';
		}

		function pickup_location_filter(){
			global $pagenow, $post_type;

			if( 'shop_order' === $post_type && 'edit.php' === $pagenow ) {
				$this->filterFields();
			}
		}

		function pickup_location_filterHPOS(){
			$this->filterFields();
		}

		function process_admin_shop_order_language_filter( $query ) {
			global $pagenow;

			if ( $query->is_admin && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'shop_order' ) {

				$meta_query = $query->get( 'meta_query' ); // Get the current "meta query"

				if(empty($meta_query) && !is_array($meta_query)) $meta_query = [];

				$meta_query['relation'] = 'AND';

				if(isset( $_GET['filter_delivery_type'] ) 
				&& $_GET['filter_delivery_type'] != '' ){
					$meta_query[] = array( // Add to "meta query"
						'key' => 'pi_delivery_type',
						'value'    => sanitize_text_field( $_GET['filter_delivery_type'] ),
						'compare' => '='
					);
				}

				if(isset( $_GET['filter_delivery_from_date'] ) 
				&& $_GET['filter_delivery_from_date'] != '' ){
					$meta_query[] = array( // Add to "meta query"
						'key' => 'pi_system_delivery_date',
						'value'    => sanitize_text_field( $_GET['filter_delivery_from_date'] ),
						'compare' => '>=',
						'type' => 'DATE'
					);
				}

				if(isset( $_GET['filter_delivery_to_date'] ) 
				&& $_GET['filter_delivery_to_date'] != '' ){
					$meta_query[] = array( // Add to "meta query"
						'key' => 'pi_system_delivery_date',
						'value'    => sanitize_text_field( $_GET['filter_delivery_to_date'] ),
						'compare' => '<=',
						'type' => 'DATE'
					);
				}

				


				$query->set( 'meta_query', $meta_query ); 

			}
		}

		function process_admin_shop_order_language_filter_hpos( $args ) {
			if( ! is_admin() ) return $args;

			$meta_query['relation'] = 'AND';

			if(isset( $_GET['filter_delivery_type'] ) 
			&& $_GET['filter_delivery_type'] != '' ){
				$meta_query[] = array( // Add to "meta query"
					'key' => 'pi_delivery_type',
					'value'    => sanitize_text_field( $_GET['filter_delivery_type'] ),
					'compare' => '='
				);
			}

			if(isset( $_GET['filter_delivery_from_date'] ) 
			&& $_GET['filter_delivery_from_date'] != '' ){
				$meta_query[] = array( // Add to "meta query"
					'key' => 'pi_system_delivery_date',
					'value'    => sanitize_text_field( $_GET['filter_delivery_from_date'] ),
					'compare' => '>=',
					'type' => 'DATE'
				);
			}

			if(isset( $_GET['filter_delivery_to_date'] ) 
			&& $_GET['filter_delivery_to_date'] != '' ){
				$meta_query[] = array( // Add to "meta query"
					'key' => 'pi_system_delivery_date',
					'value'    => sanitize_text_field( $_GET['filter_delivery_to_date'] ),
					'compare' => '<=',
					'type' => 'DATE'
				);
			}

			$args['meta_query'] = $meta_query;

			return $args;
		}
	}
    new pisol_dtt_adv_order_filter();
}