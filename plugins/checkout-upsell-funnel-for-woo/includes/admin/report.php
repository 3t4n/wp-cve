<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Admin_Report {
	protected $start_date, $end_date;

	public function __construct() {
		$this->start_date = date( 'Y-m-d', strtotime( '-30 days' ) );
		$this->end_date   = date( 'Y-m-d' );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 30 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), PHP_INT_MAX );
		add_action( 'woocommerce_delete_order_refund', array( $this, 'delete_order_info' ), 10, 1 );
		add_action( 'woocommerce_delete_order', array( $this, 'delete_order_info' ), 10, 1 );
	}

	public function delete_order_info( $id ) {
		VICUFFW_CHECKOUT_UPSELL_FUNNEL_Report_Table::delete( 'order_id', $id, '%d' );
	}

	public function admin_menu() {
		add_submenu_page(
			'checkout-upsell-funnel-for-woo',
			esc_html__( 'Report', 'checkout-upsell-funnel-for-woo' ),
			esc_html__( 'Report', 'checkout-upsell-funnel-for-woo' ),
			'manage_options',
			'checkout-upsell-funnel-for-woo-report',
			array( $this, 'settings_callback' )
		);
	}

	public function settings_callback() {
		$start_date   = isset( $_REQUEST['start_date'] ) ? urldecode( sanitize_text_field($_REQUEST['start_date'] )) : $this->start_date;
		$end_date     = isset( $_REQUEST['end_date'] ) ? urldecode( sanitize_text_field($_REQUEST['end_date'] )) : $this->end_date;
		$order_status = isset( $_REQUEST['viwcuf_order_status'] ) ? array_map( 'sanitize_text_field', $_REQUEST['viwcuf_order_status'] ) : array();
		$woo_status   = wc_get_order_statuses();
		$type         = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : '';
		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'Reports', 'checkout-upsell-funnel-for-woo' ) ?></h2>
            <div class="vi-ui secondary pointing menu">
                <a class="item <?php echo esc_attr(! $type ?  'active'  : ''); ?>"
                   href="<?php echo esc_attr( esc_url( admin_url( 'admin.php?page=checkout-upsell-funnel-for-woo-report' ) ) ) ?>">
					<?php esc_html_e( 'Orders', 'checkout-upsell-funnel-for-woo' ) ?></a>
                <a class="item <?php echo esc_attr($type === 'products' ?  'active'  : ''); ?>"
                   href="<?php echo esc_attr( esc_url( admin_url( 'admin.php?page=checkout-upsell-funnel-for-woo-report&type=products' ) ) ) ?>">
					<?php esc_html_e( 'Products', 'checkout-upsell-funnel-for-woo' ) ?></a>
            </div>
            <form class="vi-ui form" method="post">
				<?php wp_nonce_field( '_viwcuf_report_action', '_viwcuf_report' ); ?>
                <div class="inline fields viwcuf-before-chart">
                    <div class="two field viwcuf_edit_date_field viwcuf_edit_date_field_start">
                        <label><?php esc_html_e( 'From', 'checkout-upsell-funnel-for-woo' ) ?></label>
                        <input type="date" name="start_date" class="start_date" max="<?php echo esc_attr( $this->end_date ); ?>"
                               value="<?php echo esc_attr( $start_date ) ?>"/>
                    </div>
                    <div class="two field viwcuf_edit_date_field viwcuf_edit_date_field_end">
                        <label><?php esc_html_e( 'To', 'checkout-upsell-funnel-for-woo' ) ?></label>
                        <input type="date" name="end_date" class="end_date" min="<?php echo esc_attr( $start_date ? $start_date  : ''); ?>" max="<?php echo esc_attr( $this->end_date ); ?>"
                               value="<?php echo esc_attr( $end_date ) ?>"/>
                    </div>
                    <div class="four field viwcuf_order_status_field">
                        <select name="viwcuf_order_status[]" id="viwcuf_order_status" class="vi-ui fluid dropdown viwcuf_order_status" multiple>
                            <option value="">
								<?php esc_html_e( 'Choose order status', 'checkout-upsell-funnel-for-woo' ); ?>
                            </option>
							<?php
							foreach ( $woo_status as $k => $status ) {
								echo sprintf( '<option value="%s" %s >%s</option>', esc_attr( $k ), esc_attr(in_array( $k, $order_status ) ?  'selected="selected"'  : ''), esc_html( $status ) );
							}
							?>
                        </select>
                    </div>
                    <div class="two field">
                        <input type="submit" value="<?php esc_html_e( 'VIEW', 'checkout-upsell-funnel-for-woo' ) ?>"
                               class="button button-primary viwcuf_report_submit"/>
                    </div>
                </div>
				<?php
				$product_id = isset( $_GET['product_id'] ) ? sanitize_text_field( $_GET['product_id'] ) : '';
				if ( $product_id && $type === 'products' ) {
					echo sprintf( '<div class="thirteen wide field viwcuf-product-title">%s</div>', get_the_title( $product_id ) );
				}
				?>
                <div class="thirteen wide field viwcuf-chart">
                    <canvas id="myChart"></canvas>
                </div>
				<?php
				if ( $type === 'products' ) {
					?>
                    <div class="thirteen wide field">
                        <table class="form-table viwcuf-report-product-table">
                            <thead>
                            <tr>
                                <th rowspan="2">
									<?php esc_html_e( 'Product Title', 'checkout-upsell-funnel-for-woo' ); ?>
                                </th>
                                <th colspan="5">
									<?php esc_html_e( 'Item Sold', 'checkout-upsell-funnel-for-woo' ); ?>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <label><?php esc_html_e( 'Total Sold', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </td>
                                <td>
                                    <label><?php esc_html_e( 'Upsell Funnel', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </td>
                                <td>
                                    <label><?php esc_html_e( 'Order Bump', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </td>
                                <td>
                                    <label><?php esc_html_e( 'Customer', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </td>
                                <td>
                                    <label><?php esc_html_e( 'Guest', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							$order_ids     = $this->get_orders();
							$products_data = $this->get_products( $order_ids );
							if ( $products_data && is_array( $products_data ) && count( $products_data ) ) {
								foreach ( $products_data as $id => $data ) {
									?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo esc_attr( esc_url( admin_url( 'admin.php?page=checkout-upsell-funnel-for-woo-report&type=products&product_id=' . $id ) ) ); ?>">
												<?php echo esc_html( get_post_field( 'post_title', $id ) ) ?>
                                            </a>
                                        </td>
                                        <td>
											<?php echo esc_html( $data['total_sold'] ?? 0 ); ?>
                                        </td>
                                        <td>
											<?php echo esc_html( $data['total_us'] ?? 0 ); ?>
                                        </td>
                                        <td>
											<?php echo esc_html( $data['total_ob'] ?? 0 ); ?>
                                        </td>
                                        <td>
											<?php echo esc_html( $data['total_customer'] ?? 0 ); ?>
                                        </td>
                                        <td>
											<?php echo esc_html( $data['total_guest'] ?? 0 ); ?>
                                        </td>
                                    </tr>
									<?php
								}
							}
							?>
                            </tbody>
                        </table>
                    </div>
					<?php
				}
				?>
            </form>
        </div>
		<?php
	}

	public function admin_enqueue_scripts() {
		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
		if ( $page !== 'checkout-upsell-funnel-for-woo-report' ) {
			return;
		}
		$type  = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : '';
		$admin = 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_Admin_Settings';
		$admin::remove_other_script();
		$admin::enqueue_style(
			array( 'vi-wcuf-admin-report', 'semantic-ui-form', 'semantic-ui-dropdown', 'semantic-ui-icon', 'semantic-ui-menu', 'transition' ),
			array( 'admin-report.css', 'form.min.css', 'dropdown.min.css', 'icon.min.css', 'menu.min.css', 'transition.min.css' )
		);
		$admin::enqueue_script(
			array( 'vi-wcuf-admin-report', 'semantic-ui-dropdown', 'chart', 'transition' ),
			array( 'admin-report.js', 'dropdown.min.js', 'chart.min.js', 'transition.min.js', )
		);
		$args      = array(
			'type'         => $type,
			'chart_labels' => $this->get_chart_labels(),
		);
		$order_ids = $this->get_orders();
		switch ( $type ) {
			case 'customer':
				//
				break;
			case 'products':
				$product_id = isset( $_GET['product_id'] ) ? sanitize_text_field( $_GET['product_id'] ) : '';
				if ( ! $product_id ) {
					break;
				}
				$args = array_merge( $args, array(
					'product_id'     => $product_id,
					'us_label'       => esc_html__( 'Upsell Funnel', 'checkout-upsell-funnel-for-woo' ),
					'us_data'        => array(),
					'ob_label'       => esc_html__( 'Order Bumps', 'checkout-upsell-funnel-for-woo' ),
					'ob_data'        => array(),
					'customer_label' => esc_html__( 'Customer', 'checkout-upsell-funnel-for-woo' ),
					'customer_data'  => array(),
					'guest_label'    => esc_html__( 'Guest', 'checkout-upsell-funnel-for-woo' ),
					'guest_data'     => array(),
				) );
				if ( $order_ids && is_array( $order_ids ) && count( $order_ids ) ) {
					$us_data = $ob_data = $customer_data = $guest_data = array();
					foreach ( $order_ids as $i => $ids ) {
						$data            = $this->get_product_by_id( $ids, intval( $product_id ) );
						$us_data[]       = $data['upsell'] ?? 0;
						$ob_data[]       = $data['order_bump'] ?? 0;
						$customer_data[] = $data['customer'] ?? 0;
						$guest_data[]    = $data['guest'] ?? 0;
					}
					$args['us_data']       = $us_data;
					$args['ob_data']       = $ob_data;
					$args['customer_data'] = $customer_data;
					$args['guest_data']    = $guest_data;
				}
				break;
			default:
				$args = array_merge( $args, array(
					'currency'       => get_woocommerce_currency(),
					'decimal'        => wc_get_price_decimals(),
					'order_label'    => esc_html__( 'Total orders', 'checkout-upsell-funnel-for-woo' ),
					'order_data'     => array(),
					'us_order_label' => esc_html__( 'Upsell Funnel', 'checkout-upsell-funnel-for-woo' ),
					'us_order_data'  => array(),
					'ob_order_label' => esc_html__( 'Order Bumps', 'checkout-upsell-funnel-for-woo' ),
					'ob_order_data'  => array(),
				) );
				if ( $order_ids && is_array( $order_ids ) && count( $order_ids ) ) {
					$order_data = $us_order_data = $ob_order_data = array();
					foreach ( $order_ids as $i => $ids ) {
						$data            = $this->get_orders_total( $ids );
						$order_data[]    = $data['order'] ?? 0;
						$us_order_data[] = $data['us_order'] ?? 0;
						$ob_order_data[] = $data['ob_order'] ?? 0;
					}
					$args['order_data']    = $order_data;
					$args['us_order_data'] = $us_order_data;
					$args['ob_order_data'] = $ob_order_data;
				}
		}
		wp_localize_script( 'vi-wcuf-admin-report', 'viwcuf_admin_report', $args );
	}

	public function get_product_by_id( $order_ids = array(), $product_id=0 ) {
		if ( empty( $order_ids ) || ! $product_id ) {
			return array();
		}
		$result = array(
			'upsell'     => 0,
			'order_bump' => 0,
			'customer'   => 0,
			'guest'      => 0,
		);
		foreach ( $order_ids as $id ) {
			if ( empty( $wcuf_order = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Report_Table::get_row_by_order_id( $id ) ) ) {
				continue;
			}
			$order      = wc_get_order( $id );
			$line_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
			foreach ( $line_items as $item_id => $item_data ) {
				if ( $product_id !== $item_data['product_id'] ) {
					continue;
				}
				$ob_info = wc_get_order_item_meta( $item_id, '_vi_wcuf_ob_info', true );
				$us_info = wc_get_order_item_meta( $item_id, '_vi_wcuf_us_info', true );
				if ( $ob_info || $us_info ) {
					$result['upsell']     += $us_info ? $item_data['quantity'] : 0;
					$result['order_bump'] += $ob_info ? $item_data['quantity'] : 0;
					$result['customer']   += ! empty( $wcuf_order['customer_id'] ) ? $item_data['quantity'] : 0;
					$result['guest']      += empty( $wcuf_order['customer_id'] ) ? $item_data['quantity'] : 0;
				}
			}
		}

		return $result;
	}

	public function get_products( $order_ids = array() ) {
		if ( empty( $order_ids ) ) {
			return false;
		}
		$result = array();
		foreach ( $order_ids as $ids ) {
			if ( ! is_array( $ids ) || empty( $ids ) ) {
				continue;
			}
			foreach ( $ids as $id ) {
				if ( empty( $wcuf_order = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Report_Table::get_row_by_order_id( $id ) ) ) {
					continue;
				}
				$order      = wc_get_order( $id );
				$line_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
				foreach ( $line_items as $item_id => $item_data ) {
					$ob_info = wc_get_order_item_meta( $item_id, '_vi_wcuf_ob_info', true );
					$us_info = wc_get_order_item_meta( $item_id, '_vi_wcuf_us_info', true );
					if ( $ob_info || $us_info ) {
						$product_id = $item_data['product_id'];
						if ( array_key_exists( $product_id, $result ) ) {
							$result[ $product_id ]['total_sold']     += $item_data['quantity'];
							$result[ $product_id ]['total_us']       += $us_info ? $item_data['quantity'] : 0;
							$result[ $product_id ]['total_ob']       += $ob_info ? $item_data['quantity'] : 0;
							$result[ $product_id ]['total_customer'] += ! empty( $wcuf_order['customer_id'] ) ? $item_data['quantity'] : 0;
							$result[ $product_id ]['total_guest']    += empty( $wcuf_order['customer_id'] ) ? $item_data['quantity'] : 0;
						} else {
							$result[ $product_id ] = array(
								'total_sold'     => $item_data['quantity'],
								'total_us'       => $us_info ? $item_data['quantity'] : 0,
								'total_ob'       => $ob_info ? $item_data['quantity'] : 0,
								'total_customer' => ! empty( $wcuf_order['customer_id'] ) ? $item_data['quantity'] : 0,
								'total_guest'    => empty( $wcuf_order['customer_id'] ) ? $item_data['quantity'] : 0,
							);
						}
					}
				}
			}
		}

		return $result;
	}

	private function get_orders_total( $order_ids = array() ) {
		if ( empty( $order_ids ) ) {
			return array();
		}
		$order_total = $ob_order_total = $us_order_total = 0;
		foreach ( $order_ids as $id ) {
			$order       = wc_get_order( $id );
			$order_total += $order->get_total();
			$wcuf_order  = VICUFFW_CHECKOUT_UPSELL_FUNNEL_Report_Table::get_row_by_order_id( $id );
			if ( empty( $wcuf_order ) ) {
				continue;
			}
			$line_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
			foreach ( $line_items as $item_id => $item_data ) {
				if ( wc_get_order_item_meta( $item_id, '_vi_wcuf_ob_info', true ) ) {
					$ob_order_total += $item_data->get_total();
				}
				if ( wc_get_order_item_meta( $item_id, '_vi_wcuf_us_info', true ) ) {
					$us_order_total += $item_data->get_total();
				}
			}
		}

		return $result = array(
			'order'    => $order_total,
			'ob_order' => $ob_order_total,
			'us_order' => $us_order_total,
		);
	}

	private function get_orders() {
		$order_ids = array();
		$start_date         = isset( $_POST['start_date'] ) ? urldecode( sanitize_text_field($_POST['start_date'] )) : $this->start_date;
		$end_date           = isset( $_POST['end_date'] ) ? urldecode( sanitize_text_field($_POST['end_date'] )) : $this->end_date;
		$order_status       = isset( $_POST['viwcuf_order_status'] ) ? array_map( 'sanitize_text_field', $_POST['viwcuf_order_status'] ) : array_keys( wc_get_order_statuses() );
		$args               = array(
			'post_status'    => $order_status,
			'posts_per_page' => - 1,
			'return' => 'ids'
		);
		if ($start_date){
			$args['date_after'] = $start_date . ' 00:00:00';
		}
		if ($end_date){
			$args['date_before'] = $end_date . ' 00:00:00';
		}
		$tmp          = wc_get_orders( $args );
		if ( $tmp ) {
			foreach ($tmp as $order_id){
				$order                    = wc_get_order( $order_id );
				if (!$order){
					continue;
				}
				$order_date               = strtotime( $order->get_date_created()->date_i18n() );
				$order_ids[ $order_date ] = array_merge( array( $order_id ), $order_ids[ $order_date ] ?? array() );
			}
		}
		$start_date_t = strtotime( $start_date . ' 00:00:00 ' );
		$end_date_t   = strtotime( $end_date . ' 23:59:59 ' );
		for ( $i = $start_date_t; $i < $end_date_t; $i += 86400 ) {
			$order_ids[ $i ] = $order_ids[ $i ] ?? array();
		}
		ksort( $order_ids );

		return $order_ids;
	}

	public function get_chart_labels() {
		$start_date = isset( $_POST['start_date'] ) ? urldecode( sanitize_text_field($_POST['start_date'] )) : $this->start_date;
		$end_date   = isset( $_POST['end_date'] ) ? urldecode( sanitize_text_field($_POST['end_date'] )) : $this->end_date;
		if ( ! $start_date || ! $end_date ) {
			return array();
		}
		$labels       = array();
		$date_format  = get_option( 'date_format' );
		$start_date_t = strtotime( $start_date . ' 00:00:00 ' );
		$end_date_t   = strtotime( $end_date . ' 23:59:59 ' );
		for ( $i = $start_date_t; $i < $end_date_t; $i += 86400 ) {
			$labels[] = date( $date_format, $i );
		}

		return $labels;
	}
}