<?php
defined( 'ABSPATH' ) || exit;
$order_show_images          = isset( $this->data['order_details_img'] ) ? $this->data['order_details_img'] : true; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$order_show_images          = wffn_string_to_bool( $order_show_images );
$order_details_heading      = isset( $this->data['order_details_heading'] ) ? $this->data['order_details_heading'] : __( 'Order Details', 'woocommerce' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$show_images_class          = ( $order_show_images ) ? 'wfty_show_images' : 'wfty_hide_images';
$price                      = 12;
$subscription_price         = 7.50;
$order_subscription_heading = ( isset( $this->data['order_subscription_heading'] ) && ! empty( $this->data['order_subscription_heading'] ) ) ? $this->data['order_subscription_heading'] : esc_html__( 'Subscription', 'woocommerce-subscription' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable

$section_order                      = apply_filters( 'wffn_thank_you_order_details_section_order', array(
	'order_details',
	'downloads',
	'subscriptions'
) );

foreach ( $section_order as $item ) {

	switch ( $item ) {
		case 'order_details':
			?>
            <div class="wfty_box wfty_order_details">
                <div class="wfty-order-details-heading wfty_title"><?php echo esc_html( $order_details_heading ); ?></div>
                <div class="wfty_pro_list_cont <?php echo esc_attr( $show_images_class ) ?>">
                    <div class="wfty_pro_list wfty_clearfix">
                        <div class="wfty_leftDiv wfty_clearfix">
							<?php if ( $order_show_images ) { ?>
                                <div class="wfty_p_img">
                                    <a href="javascript:void(0);">
                                        <img height="100" width="100" class="attachment-shop_thumbnail size-shop_thumbnail" src="<?php echo esc_url( WC()->plugin_url() ) ?>/assets/images/placeholder.png">
                                    </a>
                                </div>
							<?php } ?>

                            <div class="wfty_p_name">
                                <a href="javascript:void(0);">
                                    <span class="wfty_t"><?php esc_html_e( 'Test Product', 'funnel-builder' ); ?></span>
                                </a>
                                <span class="wfty_quantity_value_box">
									<span class="multiply">x</span>
									<?php echo ( $order_show_images ) ? '1' : '<span class="qty">1</span>'; ?>
								</span>
                                <div class="wfty_info">
                                    <ul class="wc-item-meta">
                                        <li><strong class="wc-item-meta-label">Color: </strong>
                                            <p>Blue</p></li>
                                        <li><strong class="wc-item-meta-label">Size: </strong>
                                            <p>Large</p></li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <div class="wfty_rightDiv"><?php echo wp_kses_post( wc_price( $price ) ); ?></div>
                        <div class="wfty-clearfix"></div>
                    </div>
                    <table>
                        <tfoot>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                            <td><?php echo wp_kses_post( wc_price( $price ) ); ?></td>
                        </tr>

						<?php
						$shipping_option = get_option( 'woocommerce_ship_to_countries' );
						if ( 'disabled' !== $shipping_option ) {
							$price += 3;
							?>
                            <tr>
                                <th scope="row"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
                                <td>
									<span class="woocommerce-Price-amount amount"><?php echo wp_kses_post( wc_price( 3 ) ); ?>
                                </td>
                            </tr>
						<?php } ?>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Payment method', 'woocommerce' ); ?></th>
                            <td><?php esc_html_e( 'Credit card', 'woocommerce' ); ?></td>
                        </tr>
                        <tr>
                            <th scope="row"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                            <td><?php echo wp_kses_post( wc_price( $price ) ); ?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
			<?php
			break;

		case 'subscriptions':
			?>
            <div class="wfty_box wfty_subscription">

                <div class="wfty_title"><?php echo esc_html( $order_subscription_heading ); ?></div>
                <table class="shop_table shop_table_responsive my_account_orders">
                    <thead>
                    <tr>
                        <th class="order-number wfty_left"><span class="nobr"><?php esc_html_e( 'Subscription', 'woocommerce-subscriptions' ); ?></span></th>
                        <th class="order-status wfty_center "><span class="nobr"><?php echo esc_html_x( 'Next Payment', 'table heading', 'woocommerce-subscriptions' ); ?></span></th>
                        <th class="order-total wfty_center "><span class="nobr"><?php echo esc_html_x( 'Total', 'table heading', 'woocommerce-subscriptions' ); ?></span></th>
                        <th class="order-total wfty_center "><span class="nobr"></span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="order">
                        <td data-title="Subscription" class="subscription-id order-number wfty_left">
                            <a href="javascript:void(0);"><strong>#1234</strong></a>
                            <small>(<?php esc_html_e( 'Active', 'woocommerce' ); ?>)</small>
                        </td>
                        <td data-title="Next Payment" class="subscription-next-payment order-date wfty_center "> <?php echo esc_html( sprintf( __( 'In %s days', 'woocommerce-subscriptions' ), 30 ) ); ?></td>
                        <td data-title="Total" class="subscription-total order-total wfty_center "> <?php echo wp_kses_post( wc_price( $subscription_price ) ); ?>
                            / <?php echo esc_html_e( 'month', 'woocommerce-subscriptions' ); ?>
                        </td>
                        <td data-title="Action" class="subscription-actions order-actions wfty_center">
                            <a href="javascript:void(0);" class="button view"><?php echo esc_html_x( 'View', 'view a subscription', 'woocommerce-subscriptions' ); ?></a>
                        </td>
                    </tr>
                    </tbody>
                </table>
				<?php do_action( 'woocommerce_subscription_after_related_subscriptions_table' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
				?>
            </div>
			<?php
			break;

		case 'downloads':
			$data_settings = $this->data; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
			$order_downloads_btn_text = ( isset( $this->data['order_downloads_btn_text'] ) && ! empty( $this->data['order_downloads_btn_text'] ) ) ? $this->data['order_downloads_btn_text'] : esc_html__( 'Download', 'woocommerce' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
			$order_download_heading = ( isset( $this->data['order_download_heading'] ) && ! empty( $this->data['order_download_heading'] ) ) ? $this->data['order_download_heading'] : esc_html__( 'Downloads', 'woocommerce' ); //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
			add_filter( 'woocommerce_account_downloads_columns', function ( $array ) use ($data_settings) {
				if ( isset( $array['download-remaining'] ) && 'false' === $data_settings['order_downloads_show_file_downloads'] ) {
					unset( $array['download-remaining'] );
				}
				if ( isset( $array['download-expires'] ) && 'false' === $data_settings['order_downloads_show_file_expiry'] ) {
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
            <div class="wfty_box wfty_order_download">
				<?php echo '<div class="wfty_title">' . esc_html( $order_download_heading ) . '</div>'; ?>
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
					$downloads = array(
						array(
							'access_expires'      => gmdate( 'Y-m-d H:i:s', strtotime( '+10 days' ) ),
							'download_id'         => "c86887de-03b4-44a5-b073-1c5463ebd7d7",
							'download_name'       => 'Your_file_name.pdf',
							'download_url'        => 'javascript:void(0);',
							'downloads_remaining' => '10',
						)
					);
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
												echo '<a href="javascript:void(0);">' . esc_html( $order_downloads_btn_text ) . '</a>';
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

                </table>
            </div>
			<?php
			break;
	}
}

?>
