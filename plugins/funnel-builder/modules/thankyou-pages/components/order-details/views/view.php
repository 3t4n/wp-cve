<?php
defined( 'ABSPATH' ) || exit;
$order_show_images     = isset( $this->data['order_details_img'] ) ? $this->data['order_details_img'] : true; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$order_show_images     = wffn_string_to_bool( $order_show_images ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$order_details_heading = isset( $this->data['order_details_heading'] ) ? $this->data['order_details_heading'] : __( 'Order Details', 'woocommerce' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

$section_order = apply_filters( 'wffn_thank_you_order_details_section_order', array(
	'order_details',
	'downloads',
	'subscriptions',
) );

foreach ( $section_order as $item_section ) {

	do_action( 'wffn_start_' . $item_section . '_table' );
	switch ( $item_section ) {
		case 'order_details':
			?>
            <div class="wfty_box wfty_order_details">
                <div class="wfty-order-details-heading wfty_title"><?php echo esc_html( $order_details_heading ); ?></div>

				<?php
				$order_status = $this->order->has_status( 'failed' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				$pay_url      = $this->order->get_checkout_payment_url(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				if ( $order_status ) {
					?>
                    <div class="wfty-order-notice">
                        <p class="wfty-notice-text"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
                        <p class="wfty-notice-actions">
                            <a href="<?php echo esc_url( $pay_url ); ?>" class="wfty_n_btn"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
							<?php if ( is_user_logged_in() ) : ?>
                                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="wfty_n_btn"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
							<?php endif; ?>
                        </p>
						<?php do_action( 'wfty_subscription_notice', $this->order ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable ?>
                    </div>
					<?php
				}
				?>

				<?php
				$order_ids   = array();
				$order_ids[] = $this->order->get_id(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				$get_meta = BWF_WC_Compatibility::get_order_meta($this->order,'_wfty_sibling_order');
				if ( ( is_array( $get_meta ) && ! empty( $get_meta ) ) ) {
					foreach ( $get_meta as $meta_id ) {
						$order_ids[] = $meta_id;
					}
				}

				foreach ( $order_ids as $orderId ) {
					$order_obj = apply_filters( 'wfty_maybe_update_order', wc_get_order( $orderId ) );
					if ( $order_obj->get_items() && is_array( $order_obj->get_items() ) && count( $order_obj->get_items() ) ) {
						$order_items        = $order_obj->get_items();
						$show_purchase_note = $order_obj->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
						$show_images_class  = ( true === $order_show_images ) ? 'wfty_show_images' : 'wfty_hide_images';
						?>
                        <div class="wfty_pro_list_cont <?php echo esc_attr( $show_images_class ); ?>">
							<?php
							foreach ( $order_items as $item_id => $item ) {
								$product = apply_filters( 'woocommerce_order_item_product', $item->get_product(), $item );
								if ( ! $product instanceof WC_Product ) {
									continue;
								}
								$is_visible        = $product && $product->is_visible();
								$purchase_note     = $product ? $product->get_purchase_note() : '';
								$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? esc_url( $product->get_permalink( $item ) ) : '', $item, $order_obj );
								?>
                                <div class="wfty_pro_list wfty_clearfix">
                                    <div class="wfty_leftDiv wfty_clearfix">
										<?php if ( true === $order_show_images ) { ?>
                                            <div class="wfty_p_img">
												<?php
												$thumbnail = ( $product ) ? $product->get_image( 'shop_thumbnail' ) : '';
												if ( ! $product_permalink ) {
													echo wp_kses_post( $thumbnail );
												} else {
													printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
												}
												?>
                                            </div>
										<?php } ?>
                                        <div class="wfty_p_name">
											<?php
											$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order_obj );
											$quantity          = '<span class="wfty_quantity_value_box"><span class="multiply">x</span>';
											$quantity          .= ( $order_show_images ) ? $item->get_quantity() : '<span class="qty">' . esc_html( $item->get_quantity() ) . '</span>';
											$quantity          .= '</span>';
											echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s"><span class="wfty_t">%s</span></a>', esc_url( $product_permalink ), $item->get_name() ) : $item->get_name(), $item, $is_visible ) );
											echo wp_kses_post( $quantity );
											echo '<div class="wfty_info">';
											do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order_obj, false );
											wc_display_item_meta( $item );
											wc_display_item_downloads( $item );

											do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order_obj, false );
											echo '</div>';
											?>
                                        </div>

                                    </div>
                                    <div class="wfty_rightDiv"><?php echo wp_kses_post( $order_obj->get_formatted_line_subtotal( $item ) ); ?></div>
                                    <div class="wfty-clearfix"></div>
									<?php if ( end( $order_items )->get_id() !== $item_id ) { ?>
                                        <hr class="wfty-hr"/>
									<?php } ?>
                                </div>
								<?php if ( $show_purchase_note && $purchase_note ) : ?>
                                    <div class="wfty_leftDiv wfty_clearfix">
                                        <div class="wfty_p_name"><?php echo wp_kses_post( wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ) ); ?></div>
                                    </div>
								<?php
								endif;

							}
							do_action( 'woocommerce_order_items_table', $order_obj );
							?>
                            <table>
                                <tfoot>
								<?php
								$item_total      = $order_obj->get_order_item_totals();
								$shipping_option = get_option( 'woocommerce_ship_to_countries' );
								if ( 'disabled' === $shipping_option ) {
									unset( $item_total['shipping'] );
								}

								if ( isset( $item_total['order_total'] ) ) {
									$total = $item_total['order_total'];
									unset( $item_total['order_total'] );
									$item_total['order_total'] = $total;
								}
								foreach ( $item_total as $total ) {
									?>
                                    <tr>
                                        <th scope="row"><?php echo esc_html( str_replace( ':', '', $total['label'] ) ); ?></th>
                                        <td><?php echo wp_kses_post( $total['value'] ); ?></td>
                                    </tr>
									<?php
								}
								?>
                                </tfoot>
                            </table>
                        </div>
					<?php }
				}
				$payment_method = $this->order->get_payment_method(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				do_action( "woocommerce_thankyou_{$payment_method}", $this->order->get_id() ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				?>
            </div>
			<?php
			break;

		case 'subscriptions':
			$order_ids   = array();
			$order_ids[] = $this->order->get_id(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
			foreach ( $order_ids as $orderId ) {
				$order_obj = apply_filters( 'wfty_maybe_update_order', wc_get_order( $orderId ) );
			}
			do_action( 'wfty_woocommerce_order_subscription', $order_obj );
			break;

		case 'downloads':
			$show_downloads = false;
			if ( $this->order instanceof WC_Order ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				$downloads      = $this->order->get_downloadable_items(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				$show_downloads = $this->order->has_downloadable_item() && $this->order->is_download_permitted(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
			}
			if ( $show_downloads ) {
				$order_downloads_btn_text = ( isset( $this->data['order_downloads_btn_text'] ) && ! empty( $this->data['order_downloads_btn_text'] ) ) ? $this->data['order_downloads_btn_text'] : esc_html__( 'Download', 'woocommerce' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				$order_download_heading   = ( isset( $this->data['order_download_heading'] ) && ! empty( $this->data['order_download_heading'] ) ) ? $this->data['order_download_heading'] : esc_html__( 'Downloads', 'woocommerce' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

				echo '<div class="wfty_box wfty_order_download" >';
				echo '<div class="wfty_title">' . esc_html( $order_download_heading ) . '</div>';
				add_filter( 'woocommerce_account_downloads_columns', function ( $array ) {
					if ( isset( $array['download-remaining'] ) && 'false' === $this->data['order_downloads_show_file_downloads'] ) {
						unset( $array['download-remaining'] );
					}
					if ( isset( $array['download-expires'] ) && 'false' === $this->data['order_downloads_show_file_expiry'] ) {
						unset( $array['download-expires'] );
					}

					return $array;
				}, 999 );

				add_filter( 'woocommerce_account_downloads_columns', function ( $array ) {
					if ( isset( $array['download-product'] ) ) {
						$array['download-product'] = __( 'File', 'woocommerce' );
					}
					if ( isset( $array['download-file'] ) ) {
						$array['download-file'] = '';
					}

					return $array;
				}, 999 );

				?>
                <table class="shop_table shop_table_responsive wfty_order_downloads">
                    <thead>
                    <tr>
						<?php foreach ( wc_get_account_downloads_columns() as $column_id => $column_name ) :
							?>
                            <th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
						<?php endforeach; ?>
                    </tr>
                    </thead>

					<?php
					$order_ids   = array();
					$order_ids[] = $this->order->get_id(); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
					$get_meta = BWF_WC_Compatibility::get_order_meta($this->order,'_wfty_sibling_order'); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

					if ( ( is_array( $get_meta ) && ! empty( $get_meta ) ) ) {
						foreach ( $get_meta as $meta_id ) {
							$order_ids[] = $meta_id;
						}
					}

					foreach ( $order_ids as $orderId ) {
						$order_obj = apply_filters( 'wfty_maybe_update_order', wc_get_order( $orderId ) );
						$downloads = $order_obj->get_downloadable_items();
						foreach ( $downloads as $download ) : ?>
                            <tr>
								<?php foreach ( wc_get_account_downloads_columns() as $column_id => $column_name ) : ?>
                                    <td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
										<?php
										if ( has_action( 'woocommerce_account_downloads_column_' . $column_id ) ) {
											do_action( 'woocommerce_account_downloads_column_' . $column_id, $download );
										} else {
											switch ( $column_id ) {
												case 'download-product':
													echo esc_html( $download['download_name'] );
													break;
												case 'download-file':
													echo '<a href="' . esc_url( $download['download_url'] ) . '" class="button">' . esc_html( $order_downloads_btn_text ) . '</a>';
													break;
												case 'download-remaining':
													echo is_numeric( $download['downloads_remaining'] ) ? esc_html( $download['downloads_remaining'] ) : esc_html__( '&infin;', 'woocommerce' );
													break;
												case 'download-expires':
													if ( ! empty( $download['access_expires'] ) ) {
														echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) );
													} else {
														esc_html_e( 'Never', 'woocommerce' );
													}
													break;
											}
										}
										?>
                                    </td>
								<?php endforeach; ?>
                            </tr>
						<?php endforeach; ?>

					<?php } ?>

                </table>
                </div>
				<?php
			}
			break;
	}
	do_action( 'wffn_end_' . $item_section . '_table' );

}

$args = isset( $this->data ) ? $this->data : []; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
do_action( 'wfty_woocommerce_order_details_after_order_table', $order_obj, $args );