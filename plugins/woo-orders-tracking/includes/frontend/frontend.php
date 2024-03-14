<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_ORDERS_TRACKING_FRONTEND_FRONTEND {
	protected static $settings;
	protected static $query_tracking;
	protected static $tracking_info;

	public function __construct() {
		self::$settings      = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
		self::$tracking_info = '';
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'widgets_init', array( $this, 'register_example_widget' ) );
		add_action( 'init', array( $this, 'shortcode_init' ) );
		add_filter( 'content_pagination', array( $this, 'maybe_add_shortcode_to_page_content' ), 10, 2 );
	}

	/**
	 * Append [vi_wot_form_track_order] shortcode to the tracking page content so that no need to use the_content filter which usually causes conflict with page builder
	 *
	 * @param $pages
	 * @param $post
	 *
	 * @return mixed
	 */
	public function maybe_add_shortcode_to_page_content( $pages, $post ) {
		if ( count( $pages ) ) {
			$service_tracking_page = self::$settings->get_params( 'service_tracking_page' );
			if ( $post && $post->ID == $service_tracking_page ) {
				if ( false === strpos( $post->post_content, '[vi_wot_form_track_order]' ) ) {
					$pages[0] .= '<!-- wp:shortcode -->
[vi_wot_form_track_order]
<!-- /wp:shortcode -->';
				}
			}
		}

		return $pages;
	}

	public function wp_enqueue_scripts() {
		if ( $this->is_tracking_page() ) {
			if ( is_customize_preview() ) {
				self::$tracking_info = do_shortcode( '[vi_wot_track_order_timeline tracking_code = "customize_preview" preview="true"]' );
			} elseif ( self::$settings->get_params( 'service_carrier_enable' ) ) {
				ob_start();
				$tracking_code = isset( $_GET['tracking_id'] ) ? sanitize_text_field( $_GET['tracking_id'] ) : '';
				?>
                <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-container' ) ); ?>"
                     data-tracking_code="<?php echo esc_attr( $tracking_code ) ?>">
					<?php
					if ( isset( $_GET['woo_orders_tracking_nonce'] ) && wp_verify_nonce( $_GET['woo_orders_tracking_nonce'], 'woo_orders_tracking_nonce_action' ) ) {
						echo do_shortcode( "[vi_wot_track_order_timeline tracking_code = {$tracking_code}]" );
					} else {
						?>
                        <div class="vi-woo-orders-tracking-message-empty-nonce"><?php echo apply_filters( 'woo_orders_tracking_empty_nonce_message', esc_html__( 'Please click button Track to track your order.', 'woo-orders-tracking' ) ); ?></div>
						<?php
					}

					?>
                </div>
				<?php
				self::$tracking_info = ob_get_clean();
			}
			if ( ! wp_style_is( 'vi-wot-frontend-shortcode-track-order-icons' ) ) {
				wp_enqueue_style( 'vi-wot-frontend-shortcode-track-order-icons', VI_WOO_ORDERS_TRACKING_CSS . 'woo-orders-tracking-icons.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
			}
			wp_enqueue_style( 'vi-wot-frontend-shortcode-track-order-css', VI_WOO_ORDERS_TRACKING_CSS . 'frontend-shortcode-track-order.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
			wp_enqueue_style( 'vi-wot-frontend-shortcode-track-order-icon', VI_WOO_ORDERS_TRACKING_CSS . 'frontend-shipment-icon.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
			$css = '';
			//general
			$css .= $this->add_inline_style(
				array(
					'timeline_track_info_title_alignment',
					'timeline_track_info_title_color',
					'timeline_track_info_title_font_size',
				),
				'.woo-orders-tracking-shortcode-timeline-wrap .woo-orders-tracking-shortcode-timeline-title',
				array(
					'text-align',
					'color',
					'font-size',
				), array(
					'',
					'',
					'px'
				)
			);
			$css .= $this->add_inline_style(
				array(
					'timeline_track_info_status_color',
				),
				'.woo-orders-tracking-shortcode-timeline-wrap .woo-orders-tracking-shortcode-timeline-status-wrap',
				array(
					'color',
				), array(
				'',
			) );
			$css .= $this->add_inline_style(
				array(
					'timeline_track_info_status_background_delivered',
				),
				'.woo-orders-tracking-shortcode-timeline-wrap .woo-orders-tracking-shortcode-timeline-status-wrap.woo-orders-tracking-shortcode-timeline-status-delivered',
				array(
					'background-color',
				), array(
				'',
			) );
			$css .= $this->add_inline_style(
				array(
					'timeline_track_info_status_background_pickup',
				),
				'.woo-orders-tracking-shortcode-timeline-wrap .woo-orders-tracking-shortcode-timeline-status-wrap.woo-orders-tracking-shortcode-timeline-status-pickup',
				array(
					'background-color',
				), array(
				'',
			) );
			$css .= $this->add_inline_style(
				array(
					'timeline_track_info_status_background_transit',
				),
				'.woo-orders-tracking-shortcode-timeline-wrap .woo-orders-tracking-shortcode-timeline-status-wrap.woo-orders-tracking-shortcode-timeline-status-transit',
				array(
					'background-color',
				), array(
				'',
			) );
			$css .= $this->add_inline_style(
				array(
					'timeline_track_info_status_background_pending',
				),
				'.woo-orders-tracking-shortcode-timeline-wrap .woo-orders-tracking-shortcode-timeline-status-wrap.woo-orders-tracking-shortcode-timeline-status-pending',
				array(
					'background-color',
				), array(
				'',
			) );
			$css .= $this->add_inline_style(
				array(
					'timeline_track_info_status_background_alert',
				),
				'.woo-orders-tracking-shortcode-timeline-wrap .woo-orders-tracking-shortcode-timeline-status-wrap.woo-orders-tracking-shortcode-timeline-status-alert',
				array(
					'background-color',
				), array(
				'',
			) );
			/*
			 * template one
			 */
			if ( self::$settings->get_params( 'timeline_track_info_template' ) === '1' ) {
				$css .= $this->add_inline_style(
					array(
						'icon_delivered_color',
					),
					'.woo-orders-tracking-shortcode-timeline-wrap.woo-orders-tracking-shortcode-timeline-wrap-template-one
.woo-orders-tracking-shortcode-timeline-events-wrap
.woo-orders-tracking-shortcode-timeline-event
.woo-orders-tracking-shortcode-timeline-icon-delivered i:before',
					array(
						'color',
					),
					array(
						'',
					),
					array(
						'timeline_track_info_template_one',
					) );
				$css .= $this->add_inline_style(
					array(
						'icon_delivered_color',
					),
					'.woo-orders-tracking-shortcode-timeline-wrap.woo-orders-tracking-shortcode-timeline-wrap-template-one
.woo-orders-tracking-shortcode-timeline-events-wrap
.woo-orders-tracking-shortcode-timeline-event
.woo-orders-tracking-shortcode-timeline-icon-delivered svg circle',
					array(
						'fill',
					), array(
					''
				),
					array(
						'timeline_track_info_template_one'
					)
				);

				$css .= $this->add_inline_style(
					array(
						'icon_pickup_color',
					),
					'.woo-orders-tracking-shortcode-timeline-wrap.woo-orders-tracking-shortcode-timeline-wrap-template-one
.woo-orders-tracking-shortcode-timeline-events-wrap
.woo-orders-tracking-shortcode-timeline-event
.woo-orders-tracking-shortcode-timeline-icon-pickup i:before',
					array(
						'color',
					),
					array(
						''
					),
					array(
						'timeline_track_info_template_one'
					)
				);

				$css .= $this->add_inline_style(
					array(
						'icon_pickup_background',
					),
					'.woo-orders-tracking-shortcode-timeline-wrap.woo-orders-tracking-shortcode-timeline-wrap-template-one
.woo-orders-tracking-shortcode-timeline-events-wrap
.woo-orders-tracking-shortcode-timeline-event
.woo-orders-tracking-shortcode-timeline-icon-pickup ',
					array(
						'background-color',
					),
					array(
						'',
					),
					array(
						'timeline_track_info_template_one'
					) );

				$css .= $this->add_inline_style(
					array(
						'icon_transit_color',
					),
					'.woo-orders-tracking-shortcode-timeline-wrap.woo-orders-tracking-shortcode-timeline-wrap-template-one
.woo-orders-tracking-shortcode-timeline-events-wrap
.woo-orders-tracking-shortcode-timeline-event
.woo-orders-tracking-shortcode-timeline-icon-transit i:before',
					array(
						'color',
					),
					array(
						'',
					),
					array(
						'timeline_track_info_template_one'
					) );

				$css .= $this->add_inline_style(
					array(
						'icon_transit_background',
					),
					'.woo-orders-tracking-shortcode-timeline-wrap.woo-orders-tracking-shortcode-timeline-wrap-template-one
.woo-orders-tracking-shortcode-timeline-events-wrap
.woo-orders-tracking-shortcode-timeline-event
.woo-orders-tracking-shortcode-timeline-icon-transit ',
					array(
						'background-color',
					),
					array(
						'',
					),
					array(
						'timeline_track_info_template_one'
					) );
			}
			$css .= self::$settings->get_params( 'custom_css' );
			wp_add_inline_style( 'vi-wot-frontend-shortcode-track-order-css', $css );
		}
	}

	public function shortcode_form_track_order( $atts ) {
		$arr                   = shortcode_atts( array(
			'preview' => '',
		), $atts );
		$service_tracking_page = self::$settings->get_params( 'service_tracking_page' );
		if ( $service_tracking_page && $service_tracking_page_url = get_the_permalink( $service_tracking_page ) && ! wp_script_is( 'vi-wot-frontend-shortcode-form-search-js' ) ) {
			if ( ! wp_style_is( 'vi-wot-frontend-shortcode-track-order-icons' ) ) {
				wp_enqueue_style( 'vi-wot-frontend-shortcode-track-order-icons', VI_WOO_ORDERS_TRACKING_CSS . 'woo-orders-tracking-icons.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
			}
			wp_enqueue_style( 'vi-wot-frontend-shortcode-form-search-css', VI_WOO_ORDERS_TRACKING_CSS . 'frontend-shortcode-form-search.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
			$inline_css = $this->add_inline_style( 'tracking_form_button_track_color', '.vi-woo-orders-tracking-form-search .vi-woo-orders-tracking-form-row .vi-woo-orders-tracking-form-search-tracking-number-btnclick', 'color', '' );
			$inline_css .= $this->add_inline_style( 'tracking_form_button_track_bg_color', '.vi-woo-orders-tracking-form-search .vi-woo-orders-tracking-form-row .vi-woo-orders-tracking-form-search-tracking-number-btnclick', 'background-color', '' );
			wp_add_inline_style( 'vi-wot-frontend-shortcode-form-search-css', $inline_css );
			wp_enqueue_script( 'vi-wot-frontend-shortcode-form-search-js', VI_WOO_ORDERS_TRACKING_JS . 'frontend-shortcode-form-search.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
			wp_localize_script( 'vi-wot-frontend-shortcode-form-search-js', 'vi_wot_frontend_form_search',
				array(
					'ajax_url'         => admin_url( 'admin-ajax.php' ),
					'track_order_url'  => $service_tracking_page_url,
					'error_empty_text' => esc_html__( 'Please enter your order info to track', 'woo-orders-tracking' ),
					'is_preview'       => $arr['preview'],
				) );
		}
		ob_start();
		?>
        <form action="<?php echo esc_url( get_the_permalink( self::$settings->get_params( 'service_tracking_page' ) ) ) ?>"
              method="get"
              class="vi-woo-orders-tracking-form-search">
			<?php
			if ( ! get_option( 'permalink_structure' ) ) {
				?>
                <input type="hidden" name="page_id"
                       value="<?php echo esc_attr( isset( $_GET['page_id'] ) ? sanitize_text_field( $_GET['page_id'] ) : '' ) ?>">
				<?php
			}
			wp_nonce_field( 'woo_orders_tracking_nonce_action', 'woo_orders_tracking_nonce', false );
			$input_html = '';
			ob_start();
			?>
            <div class="vi-woo-orders-tracking-form-row">
                <input type="search"
                       id="vi-woo-orders-tracking-form-search-tracking-number"
                       class="vi-woo-orders-tracking-form-search-tracking-number"
                       placeholder="<?php esc_html_e( 'Tracking number(*required)', 'woo-orders-tracking' ) ?>"
                       name="tracking_id"
                       autocomplete="off"
                       value="<?php echo esc_attr( isset( $_GET['tracking_id'] ) ? sanitize_text_field( $_GET['tracking_id'] ) : '' ) ?>">
                <button type="submit"
                        class="vi-woo-orders-tracking-form-search-tracking-number-btnclick woo_orders_tracking_icons-search-1"><?php esc_html_e( 'Track', 'woo-orders-tracking' ) ?></button>
            </div>
			<?php
			$input_html .= ob_get_clean();
			?>
            <div class="vi-woo-orders-tracking-form-inputs vi-woo-orders-tracking-form-inputs-1">
				<?php
				echo VI_WOO_ORDERS_TRACKING_DATA::wp_kses_post( $input_html );
				?>
            </div>
            <div class="vi-woo-orders-tracking-form-message vi-woo-orders-tracking-hidden">
				<?php esc_html_e( 'Please enter your tracking number to track.', 'woo-orders-tracking' ); ?>
            </div>
        </form>
		<?php
		echo self::$tracking_info;

		return ob_get_clean();
	}

	public function register_example_widget() {
		register_widget( 'VI_WOO_ORDERS_TRACKING_WIDGET' );
	}

	protected function is_tracking_page() {
		$service_tracking_page = self::$settings->get_params( 'service_tracking_page' );
		$return                = false;
		if ( $service_tracking_page ) {
			$return = is_page( $service_tracking_page );
		}

		return $return;
	}

	public function track_order_page_content( $content ) {
		$content = str_replace( '{vi_wot_track_order_timeline}', ent2ncr( self::$tracking_info ), $content );

		return $content;
	}

	/**
	 * @param $name
	 * @param bool $set_name
	 *
	 * @return string
	 */
	public static function set( $name, $set_name = false ) {
		return VI_WOO_ORDERS_TRACKING_DATA::set( $name, $set_name );
	}

	/**
	 *
	 */
	public function shortcode_init() {
		add_shortcode( 'vi_wot_form_track_order', array( $this, 'shortcode_form_track_order' ) );
		add_shortcode( 'vi_wot_track_order_timeline', array( $this, 'shortcode_track_order_timeline' ) );
	}

	public function shortcode_track_order_timeline( $atts ) {
		$arr           = shortcode_atts( array(
			'tracking_code' => '',
			'preview'       => '',
		), $atts );
		$tracking_code = $arr['tracking_code'];
		if ( $tracking_code === 'customize_preview' && $arr['preview'] === "true" ) {
			return $this->get_template( 'customize', 'require' );
		}

		return $this->get_template( 'shortcode_timeline', 'function', $tracking_code );
	}

	private static function get_datetime_format() {
		$date_format = self::$settings->get_params( 'timeline_track_info_date_format' );
		$time_format = self::$settings->get_params( 'timeline_track_info_time_format' );

		return $date_format . ' ' . $time_format;
	}

	public static function display_timeline( $data, $tracking_code ) {
		$sort_event   = self::$settings->get_params( 'timeline_track_info_sort_event' );
		$template     = self::$settings->get_params( 'timeline_track_info_template' );
		$title        = self::$settings->get_params( 'timeline_track_info_title' );
		$status_text  = self::$settings->get_status_text_by_service_carrier( $data['status'] );
		$status       = VI_WOO_ORDERS_TRACKING_DATA::convert_status( $data['status'] );
		$track_info   = apply_filters( 'woo_orders_tracking_timeline_track_info', $data['tracking'], $tracking_code, $status );
		$carrier_name = $data['carrier_name'];
		$title        = str_replace(
			array(
				'{carrier_name}',
				'{tracking_number}',
			),
			array(
				$carrier_name,
				strtoupper( $tracking_code )
			),
			$title
		);
		if ( is_array( $track_info ) && $track_info_count = count( $track_info ) ) {
			if ( $sort_event === 'oldest_to_most_recent' ) {
				krsort( $track_info );
				$track_info = array_values( $track_info );
			}
			$template_class = '';
			$timeline_html  = '';
			switch ( $template ) {
				case '1':
					$template_class = 'template-one';
					$timeline_html  = self::get_timeline_html_1( $track_info, $sort_event, $template );
					break;
				case '2':
					$template_class = 'template-two';
					$timeline_html  = self::get_timeline_html_2( $track_info );
					break;
				default:
			}
			?>
            <div class="<?php echo esc_attr( self::set( array(
				'shortcode-timeline-wrap-' . $template_class,
				'shortcode-timeline-wrap-' . $sort_event,
				'shortcode-timeline-wrap'
			) ) ) ?>">
				<?php
				if ( $title ) {
					?>
                    <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-title' ) ) ?>">
                        <span><?php echo esc_html( $title ) ?></span>
                    </div>
					<?php
				}
				?>
                <div class="<?php echo esc_attr( self::set( array(
					'shortcode-timeline-status-wrap',
					'shortcode-timeline-status-' . $status
				) ) ) ?>">
					<?php echo esc_html( $status_text ); ?>
                </div>
				<?php
				if ( ! empty( $data['modified_at'] ) ) {
					?>
                    <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-last-update' ) ) ?>">
						<?php
						if ( $status !== 'delivered' && ! empty( $data['est_delivery_date'] ) && strtotime( $data['est_delivery_date'] ) > time() ) {
							?>
                            <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-estimated-delivery-date' ) ) ?>">
								<?php esc_html_e( 'Estimated Delivery Date: ', 'woo-orders-tracking' ) ?>
                                <span><?php echo esc_html( self::format_datetime( $data['est_delivery_date'] ) ) ?></span>
                            </div>
							<?php
						}
						?>
                        <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-last-update-text' ) ) ?>"><?php esc_html_e( 'Last Updated: ', 'woo-orders-tracking' ) ?>
                            <span><?php echo esc_html( self::format_datetime( $data['modified_at'] ) ) ?></span>
                        </div>
                    </div>
					<?php
				}
				echo apply_filters( 'woo_orders_tracking_timeline_html', $timeline_html, $status, $tracking_code, $carrier_name, $track_info );
				?>
            </div>
			<?php
		} else {
			self::tracking_not_available_message();
		}
	}

	/**
	 * @param $date
	 *
	 * @return false|string
	 * @throws Exception
	 */
	public static function format_datetime( $date ) {
		$datetime_format = self::get_datetime_format();
		if ( self::$settings->get_params( 'timeline_track_info_datetime_format_locale' ) ) {
			$date = new WC_DateTime( $date );

			return $date->date_i18n( $datetime_format );
		} else {
			return date_format( date_create( $date ), $datetime_format );
		}
	}

	public static function get_timeline_html_1( $track_info, $sort_event, $template ) {
		ob_start();
		$track_info_count = count( $track_info );
		$event_no         = $sort_event === 'oldest_to_most_recent' ? 1 : $track_info_count;
		?>
        <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-events-wrap' ) ); ?>">
			<?php
			for ( $i = 0; $i < $track_info_count; $i ++ ) {
				$event_status = VI_WOO_ORDERS_TRACKING_DATA::convert_status( $track_info[ $i ]['status'] );
				$description  = empty( $track_info[ $i ]['translated_description'] ) ? $track_info[ $i ]['description'] : $track_info[ $i ]['translated_description']
				?>
                <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-event' ) ) ?>">
                    <div class="<?php echo esc_attr( self::set( array(
						'shortcode-timeline-icon',
						'shortcode-timeline-icon-' . $event_status
					) ) ) ?>"
                         title="<?php echo esc_attr( self::$settings->get_status_text_by_service_carrier( $track_info[ $i ]['status'] ) ) ?>">
						<?php
						echo wp_kses_post( self::get_icon_status( $event_status, $template ) );
						?>
                    </div>
                    <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-event-content-wrap' ) ) ?>">
                        <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-event-content' ) ) ?>">
                            <span class="<?php echo esc_attr( self::set( 'shortcode-timeline-event-des' ) ) ?>">
                                <?php echo esc_html( "$event_no. {$description}" ) ?>
                            </span>
                            <div>
                                <span class="<?php echo esc_attr( self::set( 'shortcode-timeline-event-location' ) ) ?>">
                                    <?php echo esc_html( trim( $track_info[ $i ]['location'], ' ' ) ) ?>
                                </span>
                                <span class="<?php echo esc_attr( self::set( 'shortcode-timeline-event-time' ) ) ?>">
                                    <?php echo esc_html( self::format_datetime( $track_info[ $i ]['time'] ) ); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
				if ( $sort_event === 'oldest_to_most_recent' ) {
					$event_no ++;
				} else {
					$event_no --;
				}
			}
			?>
        </div>
		<?php
		return ob_get_clean();
	}

	public static function get_timeline_html_2( $track_info ) {
		ob_start();
		$group_event      = '';
		$track_info_count = count( $track_info );
		?>
        <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-events-wrap' ) ); ?>">
			<?php
			for ( $i = 0; $i < count( $track_info ); $i ++ ) {
				ob_start();
				$event_status = VI_WOO_ORDERS_TRACKING_DATA::convert_status( $track_info[ $i ]['status'] );
				$description  = empty( $track_info[ $i ]['translated_description'] ) ? $track_info[ $i ]['description'] : $track_info[ $i ]['translated_description']
				?>
                <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-event' ) ) ?>">
                    <div class="<?php echo esc_attr( self::set( array(
						'shortcode-timeline-icon',
						'shortcode-timeline-icon-' . $event_status
					) ) ) ?>">
                    </div>
                    <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-event-content-wrap' ) ) ?>">
                        <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-event-content-date' ) ) ?>">
							<?php
							echo esc_html( self::format_datetime( $track_info[ $i ]['time'] ) )
							?>
                        </div>
                        <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-event-content-des-wrap' ) ) ?>">
                            <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-event-content-des' ) ) ?>">
								<?php echo esc_html( $description ) ?>
                            </div>
                            <div class="<?php echo esc_attr( self::set( 'shortcode-timeline-event-location' ) ) ?>">
								<?php echo esc_html( trim( $track_info[ $i ]['location'], ' ' ) ) ?>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
				$group_event .= ob_get_clean();
				if ( $i < $track_info_count - 1 ) {
					if ( strtotime( date( 'Y-m-d', strtotime( $track_info[ $i ]['time'] ) ) ) !== strtotime( date( 'Y-m-d', strtotime( $track_info[ $i + 1 ]['time'] ) ) ) ) {
						?>
                        <div class="woo-orders-tracking-shortcode-timeline-events-group"><?php echo wp_kses_post( $group_event ) ?></div>
						<?php
						$group_event = '';
					}
				} else {
					?>
                    <div class="woo-orders-tracking-shortcode-timeline-events-group"><?php echo wp_kses_post( $group_event ) ?></div>
					<?php
					$group_event = '';
				}
			}
			?>
        </div>
		<?php
		return ob_get_clean();
	}

	public static function get_shipping_country_by_order_id( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return '';
		}
		$shipping_country =$order->get_shipping_country() ?: $order->get_billing_country();
		return $shipping_country;
	}

	/**
	 * @param $tracking_code
	 *
	 * @throws Exception
	 */
	public function shortcode_timeline( $tracking_code ) {
		if ( ! $tracking_code ) {
			?>
            <div class="vi-woo-orders-tracking-message-empty-nonce"><?php echo apply_filters( 'woo_orders_tracking_empty_data_message', esc_html__( 'Please enter your tracking number to track your order.', 'woo-orders-tracking' ) ) ?></div>
			<?php
		} else {
			self::$query_tracking = VI_WOO_ORDERS_TRACKING_DATA::search_order_item_by_tracking_number( $tracking_code );
			if ( count( self::$query_tracking ) ) {
//					$order_ids            = array_column( self::$query_tracking, 'order_id' );
				$service_carrier_type = self::$settings->get_params( 'service_carrier_type' );
				$found_tracking       = false;
				if ( $service_carrier_type === 'trackingmore' ) {
					$tracking_from_db       = VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::get_rows_by_tracking_number_carrier_pairs( array_column( self::$query_tracking, 'tracking_number_carrier_pair' ) );
					$tracking_from_db_count = count( $tracking_from_db );
					if ( $tracking_from_db_count > 0 ) {
						$tracking_from_db = $tracking_from_db[0];
						$tracking_code    = $tracking_from_db['tracking_number'];
						$carrier          = self::$settings->get_shipping_carrier_by_slug( $tracking_from_db['carrier_id'] );
						$carrier_name     = $tracking_from_db['carrier_name'];
						if ( is_array( $carrier ) && count( $carrier ) ) {
							$carrier_name = $carrier['name'];
						}

						$this->process_tracking_from_db_trackingmore( $tracking_from_db, $tracking_code, $service_carrier_type, $found_tracking );

					} else if ( self::$settings->get_params( 'service_add_tracking_if_not_exist' ) ) {
						$current_tracking = self::$query_tracking[0];
						if ( ! $tracking_code ) {
							$tracking_code = $current_tracking['tracking_number'];
						}
						$tracking_from_db                          = VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::get_cols();
						$tracking_from_db['order_id']              = $current_tracking['order_id'];
						$tracking_from_db['tracking_number']       = $tracking_code;
						$item_tracking_data                        = $current_tracking['meta_value'];
						$tracking_from_db['shipping_country_code'] = self::get_shipping_country_by_order_id( $current_tracking['order_id'] );
						if ( $item_tracking_data ) {
							$item_tracking_data             = vi_wot_json_decode( $item_tracking_data );
							$current_tracking_data          = array_pop( $item_tracking_data );
							$carrier_name                   = $current_tracking_data['carrier_name'];
							$carrier_slug                   = $current_tracking_data['carrier_slug'];
							$tracking_from_db['carrier_id'] = $carrier_slug;
							$carrier                        = self::$settings->get_shipping_carrier_by_slug( $carrier_slug );
							if ( is_array( $carrier ) && count( $carrier ) ) {
								$carrier_name       = $carrier['name'];
								$tracking_more_slug = empty( $carrier['tracking_more_slug'] ) ? VI_WOO_ORDERS_TRACKING_TRACKINGMORE::get_carrier_slug_by_name( $carrier_name ) : $carrier['tracking_more_slug'];
								if ( ! empty( $tracking_more_slug ) ) {
									$service_carrier_api_key = self::$settings->get_params( 'service_carrier_api_key' );
									if ( $service_carrier_api_key ) {
										$trackingMore = new VI_WOO_ORDERS_TRACKING_TRACKINGMORE( $service_carrier_api_key );
										$track_data   = $trackingMore->create_tracking( $tracking_code, $tracking_more_slug, $current_tracking['order_id'] );
										$status       = '';
										$track_info   = '';
										$description  = '';
										if ( $track_data['status'] === 'success' ) {
											$status = $track_data['data']['status'];
											VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::insert( $current_tracking['order_id'], $tracking_code, $status, $carrier_slug, $carrier_name, $tracking_from_db['shipping_country_code'], $track_info, '' );
										} else {
											if ( $track_data['code'] === 4016 ) {
												/*Tracking exists*/
												$track_data = $trackingMore->get_tracking( $tracking_code, $tracking_more_slug );
												if ( $track_data['status'] === 'success' ) {
													if ( count( $track_data['data'] ) ) {
														$tracking                             = $track_data['data'];
														$track_info                           = vi_wot_json_encode( $track_data['data'] );
														$last_event                           = array_shift( $track_data['data'] );
														$status                               = $last_event['status'];
														$description                          = $last_event['description'];
														$current_tracking_data['status']      = $last_event['status'];
														$current_tracking_data['last_update'] = time();
														$found_tracking                       = true;
														self::display_timeline( array(
															'status'            => $status,
															'tracking'          => $tracking,
															'last_event'        => $last_event,
															'carrier_name'      => $carrier_name,
															'est_delivery_date' => '',
															'modified_at'       => date( 'Y-m-d H:i:s' ),
															'order_id'          => $tracking_from_db['order_id'],
														), $tracking_code );
														$convert_status = VI_WOO_ORDERS_TRACKING_DATA::convert_status( $last_event['status'] );
														if ( $convert_status !== VI_WOO_ORDERS_TRACKING_DATA::convert_status( $tracking_from_db['status'] ) || $track_info !== $tracking_from_db['track_info'] ) {
//																$tracking_change = 1;
															VI_WOO_ORDERS_TRACKING_ADMIN_ORDERS_TRACK_INFO::update_order_items_tracking_status( $tracking_code, $tracking_from_db['carrier_id'], $last_event['status'] );
														}
													}
												}
												VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::insert( $current_tracking['order_id'], $tracking_code, $status, $carrier_slug, $carrier_name, $tracking_from_db['shipping_country_code'], $track_info, $description );
											}
										}
									}
								}
							}
						}
					}
					if ( ! $found_tracking ) {
						self::tracking_not_available_message();
					}
				}
			} else {
				self::get_not_found_text();
			}
		}
	}

	/**
	 * @param $tracking_from_db
	 * @param $tracking_code
	 * @param $service_carrier_type
	 * @param $found_tracking
	 *
	 * @throws Exception
	 */
	public function process_tracking_from_db_trackingmore( $tracking_from_db, $tracking_code, $service_carrier_type, &$found_tracking ) {
		$now            = time();
		$found_tracking = true;
		if ( VI_WOO_ORDERS_TRACKING_DATA::convert_status( $tracking_from_db['status'] ) === 'delivered' && $tracking_from_db['track_info'] ) {
			$track_info   = vi_wot_json_decode( $tracking_from_db['track_info'] );
			$carrier_name = $tracking_from_db['carrier_id'];
			$carrier      = self::$settings->get_shipping_carrier_by_slug( $tracking_from_db['carrier_id'] );
			if ( is_array( $carrier ) && count( $carrier ) ) {
				$carrier_name = $carrier['name'];
			}
			self::display_timeline( array(
				'status'            => $tracking_from_db['status'],
				'tracking'          => $track_info,
				'last_event'        => $tracking_from_db['last_event'],
				'carrier_name'      => $carrier_name,
				'est_delivery_date' => '',
				'modified_at'       => $tracking_from_db['modified_at'],
				'order_id'          => $tracking_from_db['order_id'],
			), $tracking_code );
		} else {
			$modified_at = $tracking_from_db['modified_at'];
			if ( ( $now - strtotime( $modified_at ) ) > self::$settings->get_cache_request_time() ) {
				$service_carrier_api_key = self::$settings->get_params( 'service_carrier_api_key' );
				if ( $service_carrier_api_key ) {
					$carrier_id = $tracking_from_db['carrier_id'];
					$carrier    = self::$settings->get_shipping_carrier_by_slug( $carrier_id );
					if ( is_array( $carrier ) && count( $carrier ) ) {
						$carrier_name       = $carrier['name'];
						$tracking_more_slug = empty( $carrier['tracking_more_slug'] ) ? VI_WOO_ORDERS_TRACKING_TRACKINGMORE::get_carrier_slug_by_name( $carrier_name ) : $carrier['tracking_more_slug'];
						if ( ! empty( $tracking_more_slug ) ) {
							$shipping_country_code = isset( $tracking_from_db['shipping_country_code'] ) ? $tracking_from_db['shipping_country_code'] : '';
							if ( ! $shipping_country_code ) {
								$shipping_country_code = self::get_shipping_country_by_order_id( $tracking_from_db['order_id'] );
							}
							$trackingMore = new VI_WOO_ORDERS_TRACKING_TRACKINGMORE( $service_carrier_api_key );
							$track_data   = $trackingMore->get_tracking( $tracking_code, $tracking_more_slug );
							if ( $track_data['status'] === 'success' ) {
								if ( count( $track_data['data'] ) ) {
									$tracking    = $track_data['data'];
									$track_info  = vi_wot_json_encode( $track_data['data'] );
									$last_event  = array_shift( $track_data['data'] );
									$status      = $last_event['status'];
									$description = $last_event['description'];
									VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::update_by_tracking_number( $tracking_code, $status, $carrier_id, false, $shipping_country_code, $track_info, $description );
									self::display_timeline( array(
										'status'            => $status,
										'tracking'          => $tracking,
										'last_event'        => $last_event,
										'carrier_name'      => $carrier_name,
										'est_delivery_date' => '',
										'modified_at'       => date( 'Y-m-d H:i:s' ),
										'order_id'          => $tracking_from_db['order_id'],
									), $tracking_code );
									$convert_status = VI_WOO_ORDERS_TRACKING_DATA::convert_status( $last_event['status'] );
									if ( $convert_status !== VI_WOO_ORDERS_TRACKING_DATA::convert_status( $tracking_from_db['status'] ) || $track_info !== $tracking_from_db['track_info'] ) {
//										$tracking_change = 1;
										VI_WOO_ORDERS_TRACKING_ADMIN_ORDERS_TRACK_INFO::update_order_items_tracking_status( $tracking_code, $tracking_from_db['carrier_id'], $last_event['status'] );
									}
								} else {
									VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::update( $tracking_from_db['id'], '', false, false, false, false, false, false );
									$found_tracking = false;
								}
							} else {
								if ( ( $track_data['code'] == 4017 || $track_data['code'] === 4031 ) && self::$settings->get_params( 'service_add_tracking_if_not_exist' ) ) {
									$trackingMore->create_tracking( $tracking_code, $tracking_more_slug, $tracking_from_db['order_id'] );
								}
								VI_WOO_ORDERS_TRACKING_TRACKINGMORE_TABLE::update( $tracking_from_db['id'], '', false, false, false, false, false, false );
								$found_tracking = false;
							}
						} else {
							$found_tracking = false;
						}
					} else {
						$found_tracking = false;
					}
				} else {
					$found_tracking = false;
				}
			} elseif ( $tracking_from_db['track_info'] ) {
				$track_info   = vi_wot_json_decode( $tracking_from_db['track_info'] );
				$carrier_name = $tracking_from_db['carrier_id'];
				$carrier      = self::$settings->get_shipping_carrier_by_slug( $tracking_from_db['carrier_id'] );
				if ( is_array( $carrier ) && count( $carrier ) ) {
					$carrier_name = $carrier['name'];
				}
				self::display_timeline( array(
					'status'            => $tracking_from_db['status'],
					'tracking'          => $track_info,
					'last_event'        => $tracking_from_db['last_event'],
					'carrier_name'      => $carrier_name,
					'est_delivery_date' => '',
					'modified_at'       => $tracking_from_db['modified_at'],
					'order_id'          => $tracking_from_db['order_id'],
				), $tracking_code );
			} else {
				self::tracking_not_available_message();
			}
		}
	}

	/**
	 * What to do when a real tracking number does not receive any track info from tracking service
	 */
	public static function tracking_not_available_message() {
		?>
        <p><?php esc_html_e( 'Tracking data is not available now. Please come back later. Thank you.', 'woo-orders-tracking' ); ?></p>
		<?php
	}

	/**
	 * Message when a tracking number is not found in the system
	 */
	public static function get_not_found_text() {
		if ( empty( $_GET['tracking_id'] ) ) {
			?>
            <p><?php esc_html_e( 'No tracking number found', 'woo-orders-tracking' ) ?></p>
			<?php
		} else {
			?>
            <p><?php esc_html_e( 'Tracking number is expired or not found in existing orders.', 'woo-orders-tracking' ) ?></p>
			<?php
		}
	}

	private static function get_icon_status_delivered( $setting_icon ) {
		$icons = VI_WOO_ORDERS_TRACKING_DATA::get_delivered_icons();

		return isset( $icons[ $setting_icon ] ) ? "<i class='{$icons[$setting_icon]}'></i>" : '';
	}

	private static function get_icon_status_pickup( $setting_icon ) {
		$icons = VI_WOO_ORDERS_TRACKING_DATA::get_pickup_icons();

		return isset( $icons[ $setting_icon ] ) ? "<i class='{$icons[$setting_icon]}'></i>" : '';
	}

	private static function get_icon_status_transit( $setting_icon ) {
		$icons = VI_WOO_ORDERS_TRACKING_DATA::get_transit_icons();

		return isset( $icons[ $setting_icon ] ) ? "<i class='{$icons[$setting_icon]}'></i>" : '';
	}

	public static function get_default_icon() {
		return '<span class="woo-orders-tracking-icon-default"></span>';
	}

	public static function get_icon_status( $status, $template, $icon = '' ) {
		$settings = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
		$result   = '';
		if ( $template === '1' ) {
			switch ( $status ) {
				case 'delivered':
					if ( ! $icon ) {
						$icon = $settings->get_params( 'timeline_track_info_template_one', 'icon_delivered' );
					}
					$result = self::get_icon_status_delivered( $icon );
					break;
				case 'pickup':
					if ( ! $icon ) {
						$icon = $settings->get_params( 'timeline_track_info_template_one', 'icon_pickup' );
					}
					$result = self::get_icon_status_pickup( $icon );
					break;
				case 'transit':
					if ( ! $icon ) {
						$icon = $settings->get_params( 'timeline_track_info_template_one', 'icon_transit' );
					}
					$result = self::get_icon_status_transit( $icon );
					break;
				case 'alert':
					$result = '<span class="woo_orders_tracking_icons-warning"></span>';
					break;
				default:
					$result = self::get_default_icon();
			}
		}

		return $result;
	}

	/**
	 * @param $name
	 * @param $type
	 * @param string $tracking_code
	 *
	 * @return string
	 */
	protected function get_template( $name, $type, $tracking_code = '' ) {
		ob_start();
		if ( $type === 'require' ) {
			require_once VI_WOO_ORDERS_TRACKING_TEMPLATES . $name . '.php';
		} elseif ( $type === 'function' ) {
			$this->$name( $tracking_code );
		}
		$html = ob_get_clean();

		return ent2ncr( $html );
	}

	private function add_inline_style( $name, $element, $style, $suffix = '', $type = array(), $echo = false ) {
		$return = $element . '{';
		if ( is_array( $name ) && count( $name ) ) {
			foreach ( $name as $key => $value ) {
				$t      = isset( $type[ $key ] ) ? $type[ $key ] : '';
				$return .= $style[ $key ] . ':' . ( $t ? self::$settings->get_params( $t, $name[ $key ] ) : self::$settings->get_params( $name[ $key ] ) ) . $suffix[ $key ] . ';';
			}
		} else {
			$return .= $style . ':' . self::$settings->get_params( $name ) . $suffix . ';';
		}
		$return .= '}';
		if ( $echo ) {
			echo wp_kses( $return, VI_WOO_ORDERS_TRACKING_DATA::extend_post_allowed_style_html() );
		}

		return $return;
	}
}