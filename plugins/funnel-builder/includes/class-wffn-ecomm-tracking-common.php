<?php
/**
 * Class WFFN_Ecom_Tracking_Common
 */
if ( ! class_exists( 'WFFN_Ecomm_Tracking_Common' ) ) {
	class WFFN_Ecomm_Tracking_Common {
		public $api_events = [];
		public $gtag_rendered = false;
        public $admin_general_settings=null;

		public function __construct() {
			add_action( 'wp_head', array( $this, 'render' ), 90 );
			$this->admin_general_settings = BWF_Admin_General_Settings::get_instance();
		}

		public function should_render() {
			return apply_filters( 'wffn_allow_tracking', true, $this );
		}

		public function render() {

			if ( $this->should_render() ) {
				$this->render_pint();
				$this->render_fb();
				$this->render_ga();
				$this->render_gad();
				$this->render_snapchat();
				$this->render_tiktok();
				$this->maybe_render_conv_api();
			}
		}

		public function get_advanced_pixel_data( $type ) {
                if( 'fb' === $type ){
	                return WFFN_Common::pixel_advanced_matching_data();
                }
                if( 'tiktok' === $type ){
	                return WFFN_Common::tiktok_advanced_matching_data();
                }

                return array();

		}

		public function render_pint() {
			if ( false !== $this->is_pint_pixel() ) {
				$get_each_pixel_id = explode( ',', $this->is_pint_pixel() );
				?>
                <!-- Pinterest Pixel Base Code -->
                <script type="text/javascript">
                    var wffn_shouldRender = 1;
					<?php do_action( 'wffn_allow_tracking_inline_js' ); ?>

                    if (1 === wffn_shouldRender) {
                        !function (e) {
                            if (!window.pintrk) {
                                window.pintrk = function () {
                                    window.pintrk.queue.push(
                                        Array.prototype.slice.call(arguments))
                                };
                                var
                                    n = window.pintrk;
                                n.queue = [], n.version = "3.0";
                                var
                                    t = document.createElement("script");
                                t.async = !0, t.src = e;
                                var
                                    r = document.getElementsByTagName("script")[0];
                                r.parentNode.insertBefore(t, r)
                            }
                        }("https://s.pinimg.com/ct/core.js");

					<?php foreach ( $get_each_pixel_id as $id ) { ?>
                        pintrk('load', '<?php echo esc_js( $id ) ?>');
						<?php if ( $this->should_render_view( 'pint' ) ) { ?>
                        pintrk('page');
						<?php } ?>
					<?php } ?>
                    }
                </script>
				<?php foreach ( $get_each_pixel_id as $id ) { ?>
                    <noscript>
                        <img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/v3/?tid=<?php echo esc_attr( $id ); ?>&noscript=1"/>
                    </noscript>
				<?php } ?>


                <!-- End Pinterest Pixel Base Code -->
                <script type="text/javascript">
                    if (1 === wffn_shouldRender) {
					<?php $this->maybe_track_custom_steps_event_pint(); ?>
						<?php $this->maybe_print_pint_ecomm(); ?>
                    }
                </script>
				<?php
			}
		}

		public function render_fb() {
			if ( false !== $this->is_fb_pixel() ) {
				$fb_advanced_pixel_data = $this->get_advanced_pixel_data( 'fb' );
                ?>
				<!-- Facebook Analytics Script Added By WooFunnels -->
				<script type="text/javascript">
					var wffn_shouldRender = 1;
					<?php do_action( 'wffn_allow_tracking_inline_js' ); ?>
                    if (1 === wffn_shouldRender) {
                        !function (f, b, e, v, n, t, s) {
                            if (f.fbq) return;
                            n = f.fbq = function () {
                                n.callMethod ?
                                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                            };
                            if (!f._fbq) f._fbq = n;
                            n.push = n;
                            n.loaded = !0;
                            n.version = '2.0';
                            n.queue = [];
                            t = b.createElement(e);
                            t.async = !0;
                            t.src = v;
                            s = b.getElementsByTagName(e)[0];
                            s.parentNode.insertBefore(t, s)
                        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
					<?php

					$get_all_fb_pixel  = $this->is_fb_pixel();
					$get_each_pixel_id = explode( ',', $get_all_fb_pixel );
					if ( is_array( $get_each_pixel_id ) && count( $get_each_pixel_id ) > 0 ) {
						foreach ( $get_each_pixel_id as $pixel_id ) {
							?>
							<?php if ( true === $this->is_fb_advanced_tracking_on() && count( $fb_advanced_pixel_data ) > 0 ) { ?>
                        fbq('init', '<?php echo esc_js( trim( $pixel_id ) ); ?>', <?php echo wp_json_encode( $fb_advanced_pixel_data ); ?>);
						<?php } else { ?>
                        fbq('init', '<?php echo esc_js( trim( $pixel_id ) ); ?>');
						<?php } ?>
							<?php
						}
						?>
						<?php $this->render_fb_view(); ?>
						<?php $this->maybe_track_custom_steps_event( 'fb' ); ?>
						<?php $this->maybe_print_fb_script(); ?>
						<?php
					}
					?>
                    }

                </script>
				<?php
			}
		}

		public function do_track_gad_purchase() {

			$do_track_gad_purchase = $this->admin_general_settings->get_option( 'is_gad_purchase_event' );
			if ( is_array( $do_track_gad_purchase ) && count( $do_track_gad_purchase ) > 0 && 'yes' === $do_track_gad_purchase[0] ) {
				return true;
			}

			return false;
		}

		public function maybe_print_fb_script() {
			echo '';
		}

		public function maybe_print_gtag_script( $k, $code, $label ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			echo '';
		}

		public function is_fb_enable_content_on() {
			$is_fb_enable_content_on = $this->admin_general_settings->get_option( 'is_fb_enable_content' );
			if ( is_array( $is_fb_enable_content_on ) && count( $is_fb_enable_content_on ) > 0 && 'yes' === $is_fb_enable_content_on[0] ) {
				return true;
			}
		}

		public function is_fb_advanced_tracking_on() {
			$is_fb_advanced_tracking_on = $this->admin_general_settings->get_option( 'is_fb_advanced_event' );
			if ( is_array( $is_fb_advanced_tracking_on ) && count( $is_fb_advanced_tracking_on ) > 0 && 'yes' === $is_fb_advanced_tracking_on[0] ) {
				return true;
			}
		}

		public function get_woo_product_content_id( $product_id, $service = 'pixel' ) {
			$prefix            = '';
			$suffix            = '';
			$content_id_format = '';

			if ( ( 'pixel' === $service ) && ( true === $this->is_fb_enable_content_on() ) ) {
				$prefix            = $this->admin_general_settings->get_option( $service . '_content_id_prefix' );
				$suffix            = $this->admin_general_settings->get_option( $service . '_content_id_suffix' );
				$content_id_format = $this->admin_general_settings->get_option( $service . '_content_id_type' );
			}

			if ( 'pixel' !== $service ) {
				$prefix            = $this->admin_general_settings->get_option( $service . '_content_id_prefix' );
				$suffix            = $this->admin_general_settings->get_option( $service . '_content_id_suffix' );
				$content_id_format = $this->admin_general_settings->get_option( $service . '_content_id_type' );
			}

			if ( $content_id_format === 'product_sku' ) {
				$content_id = get_post_meta( $product_id, '_sku', true );
			} else {
				$content_id = $product_id;
			}
			$content_id = apply_filters( 'wffn_get_product_content_id', $content_id, $product_id );

			$value = $prefix . $content_id . $suffix;

			return ( $value );

		}

		public function should_render_view( $type ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
			return false;
		}

		public function should_render_lead( $type ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
			return false;
		}

		public function do_treat_variable_as_simple( $mode = 'pixel' ) {

			$do_treat_variable_as_simple = $this->admin_general_settings->get_option( $mode . '_variable_as_simple' );

			if ( ( 'pixel' === $mode ) && ( true !== $this->is_fb_enable_content_on() ) ) {
				return false;
			}

			if ( 1 === absint( $do_treat_variable_as_simple ) ) {
				return true;
			}

			return false;
		}

		/**
		 * maybe render script to fire fb pixel view event
		 */
		public function render_fb_view() {
			if ( $this->should_render_view( 'fb' ) ) {
				?>
                var wffnEvents = [];
                var wffn_ev_view_fb_event_id = Math.random().toString(36).substring(2, 15);
                fbq('track', 'PageView',(typeof wffnAddTrafficParamsToEvent !== "undefined")?wffnAddTrafficParamsToEvent({} ):{},{'eventID': 'PageView_'+wffn_ev_view_fb_event_id});
                wffnEvents.push({event: 'PageView', 'event_id': 'PageView_'+wffn_ev_view_fb_event_id});
				<?php

			}
		}

		public function is_fb_pixel() {

			$steps = WFFN_Core()->data->get_current_step();
			$key   = $this->admin_general_settings->get_option( 'fb_pixel_key' );

			if ( is_array( $steps ) && isset( $steps['id'] ) && get_post( $steps['id'] ) instanceof WP_Post ) {
				$setting = WFFN_Common::maybe_override_tracking( $steps['id'] );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['fb_pixel_key'] ) && ! empty( $setting['fb_pixel_key'] ) ) ? $setting['fb_pixel_key'] : $key;
				}
			}

			$get_pixel_key = apply_filters( 'bwf_fb_pixel_ids', $key );

			return empty( $get_pixel_key ) ? false : $get_pixel_key;
		}

		public function is_pint_pixel() {

			$steps = WFFN_Core()->data->get_current_step();
			$key   = $this->admin_general_settings->get_option( 'pint_key' );

			if ( is_array( $steps ) && isset( $steps['id'] ) && get_post( $steps['id'] ) instanceof WP_Post ) {
				$setting = WFFN_Common::maybe_override_tracking( $steps['id'] );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['pint_key'] ) && ! empty( $setting['pint_key'] ) ) ? $setting['pint_key'] : $key;
				}
			}

			$get_pixel_key = apply_filters( 'bwf_fb_pint_ids', $key );

			return empty( $get_pixel_key ) ? false : $get_pixel_key;
		}


		/**
		 * render google analytics core script to load framework
		 */
		public function render_ga() {
			$get_tracking_code = $this->ga_code();
			if ( false === $get_tracking_code ) {
				return;
			}

			$get_tracking_code = explode( ",", $get_tracking_code );

			if ( ( $this->should_render_lead( 'ga' ) || $this->should_render_view( 'ga' ) ) && is_array( $get_tracking_code ) && count( $get_tracking_code ) > 0 ) {
				?>
                <!-- Google Analytics Script Added By WooFunnels 22-->
                <script type="text/javascript">
                    var wffn_shouldRender = 1;
					<?php do_action( 'wffn_allow_tracking_inline_js' ) ?>
                    if (1 === wffn_shouldRender) {
					<?php if ( false === $this->gtag_rendered ) {
						$this->load_gtag( $get_tracking_code[0] );
					}
					if ( true === $this->should_render_view( 'ga' ) ) {
						foreach ( $get_tracking_code as $k => $code ) {
							echo "gtag('config', '" . esc_js( trim( $code ) ) . "');";
							$label = false;
							esc_js( $this->render_gtag_custom_event( $code, $label, 'ga' ) );
							esc_js( $this->maybe_print_gtag_script( $k, $code, $label, $this->do_track_ga_purchase() ) ); //phpcs:ignore
						}
					}
					?>
                    }
                </script>

				<?php
			}
		}

		public function do_track_ga_purchase() {
			$do_track_ga_purchase = $this->admin_general_settings->get_option( 'is_ga_purchase_event' );
			if ( is_array( $do_track_ga_purchase ) && count( $do_track_ga_purchase ) > 0 && 'yes' === $do_track_ga_purchase[0] ) {
				return true;
			}

			return false;
		}

		public function is_enable_custom_event_ga() {
			$is_ga_custom_events = $this->admin_general_settings->get_option( 'is_ga_custom_events' );

			if ( '1' === $is_ga_custom_events ) {
				return true;
			}

			return false;
		}

		public function is_enable_custom_event_gad() {
			$is_ga_custom_events = $this->admin_general_settings->get_option( 'is_gad_custom_events' );

			if ( '1' === $is_ga_custom_events ) {
				return true;
			}

			return false;
		}

		/**
		 * maybe render script to fire fb pixel view event
		 */
		public function render_gtag_custom_event( $code, $label, $mode ) {
			if ( ( ( $mode === 'ga' && $this->is_enable_custom_event_ga() ) || ( $mode === 'gad' && $this->is_enable_custom_event_gad() ) ) ) {
				?>
                gtag('event','<?php echo esc_attr( $this->get_custom_event_name() ); ?>',{send_to: '<?php echo esc_attr( $code ); ?>'});
				<?php
			}
		}


		public function load_gtag( $id ) {
			?>
            (function (window, document, src) {
            var a = document.createElement('script'),
            m = document.getElementsByTagName('script')[0];
            a.defer = 1;
            a.src = src;
            m.parentNode.insertBefore(a, m);
            })(window, document, '//www.googletagmanager.com/gtag/js?id=<?php echo esc_js( trim( $id ) ); ?>');

            window.dataLayer = window.dataLayer || [];
            window.gtag = window.gtag || function gtag() {
            dataLayer.push(arguments);
            };

            gtag('js', new Date());
			<?php
			$this->gtag_rendered = true;
		}

		/**
		 * render google analytics core script to load framework
		 */
		public function render_gad() {
			$get_tracking_code = $this->gad_code();
			if ( false === $get_tracking_code ) {
				return;
			}

			$get_tracking_code = explode( ",", $get_tracking_code );

			if ( ( $this->should_render_lead( 'gad' ) || $this->should_render_view( 'gad' ) ) && is_array( $get_tracking_code ) && count( $get_tracking_code ) > 0 ) {
				?>
                <script type="text/javascript">
                    var wffn_shouldRender = 1;
					<?php do_action( 'wffn_allow_tracking_inline_js' ); ?>
                    if (1 === wffn_shouldRender) {
					<?php
					if ( false === $this->gtag_rendered ) {
						$this->load_gtag( $get_tracking_code[0] );

					}
					if ( true === $this->should_render_view( 'gad' ) ) {
						foreach ( $get_tracking_code as $k => $code ) {
							echo "gtag('config', '" . esc_js( trim( $code ) ) . "');";
							echo "gtag('event', 'page_view', {send_to: '" . esc_js( trim( $code ) ) . "'});";
							$label = false;
							if ( false !== $this->gad_purchase_label() ) {
								$gad_labels = explode( ",", $this->gad_purchase_label() );
								$label      = isset( $gad_labels[ $k ] ) ? $gad_labels[ $k ] : $gad_labels[0];
							}

							esc_js( $this->render_gtag_custom_event( $code, $label, 'gad' ) );
							esc_js( $this->maybe_print_gtag_script( $k, $code, $label, $this->do_track_gad_purchase(), true ) ); //phpcs:ignore

						}
					}

					?>
                    }
                </script>

				<?php
			}
		}

		/**
		 * render snapchat analytics core script to load framework
		 */
		public function render_snapchat() {
			$get_tracking_code = $this->snapchat_code();
			if ( false === $get_tracking_code ) {
				return;
			}

			$get_tracking_code = explode( ",", $get_tracking_code );

			if ( ( $this->should_render_view( 'snapchat' ) ) && is_array( $get_tracking_code ) && count( $get_tracking_code ) > 0 ) {


				$get_each_pixel_id = explode( ',', $this->snapchat_code() );
				?>
                <!-- snapchat Pixel Base Code -->
                <script type="text/javascript">
                    var wffn_shouldRender = 1;
					<?php do_action( 'wffn_allow_tracking_inline_js' ); ?>
                    if (1 === wffn_shouldRender) {
                        (function (win, doc, sdk_url) {
                            if (win.snaptr) {
                                return;
                            }

                            var tr = win.snaptr = function () {
                                tr.handleRequest ? tr.handleRequest.apply(tr, arguments) : tr.queue.push(arguments);
                            };
                            tr.queue = [];
                            var s = 'script';
                            var new_script_section = doc.createElement(s);
                            new_script_section.async = !0;
                            new_script_section.src = sdk_url;
                            var insert_pos = doc.getElementsByTagName(s)[0];
                            insert_pos.parentNode.insertBefore(new_script_section, insert_pos);
                        })(window, document, 'https://sc-static.net/scevent.min.js');

					<?php foreach ( $get_each_pixel_id as $id ) {

						$email = $this->get_user_email();
						if ( ! empty( $email ) ) {
							?>

                        snaptr('init', '<?php echo esc_js( $id ); ?>', {
                            integration: 'woocommerce',
                            user_email: '<?php echo esc_attr( $email ); ?>'
                        });
						<?php
						} else {
							?>

                        snaptr('init', '<?php echo esc_js( $id ); ?>', {
                            integration: 'woocommerce'
                        });
						<?php
						}
					} ?>
                    }

                </script>
                <script type="text/javascript">
                    if (1 === wffn_shouldRender) {
					<?php if ( $this->should_render_view( 'snapchat' ) ) { ?>
                        snaptr('track', 'PAGE_VIEW');
						<?php } ?>
						<?php esc_js( $this->maybe_print_snapchat_ecomm() ); ?> //phpcs:ignore
                    }
                </script>

				<?php
			}
		}

		public function tiktok_code() {

			global $post;
			$step_id = 0;
			if ( $post instanceof WP_Post ) {
				$step_id = $post->ID;
			}
			$key = $this->admin_general_settings->get_option( 'tiktok_pixel' );

			if ( $step_id > 0 && get_post( $step_id ) instanceof WP_Post ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['tiktok_pixel'] ) && ! empty( $setting['tiktok_pixel'] ) ) ? $setting['tiktok_pixel'] : $key;
				}
			}

			$get_ga_key = apply_filters( 'bwf_tiktok_pixel', $key );

			return empty( $get_ga_key ) ? false : $get_ga_key;
		}

		/**
		 * render script to load facebook pixel core js
		 */
		public function render_tiktok() {

			if ( $this->tiktok_code() ) {
				$get_each_pixel_id = explode( ',', $this->tiktok_code() );
				$advanced_pixel_data = $this->get_advanced_pixel_data('tiktok' );

				?>
                <!-- Tiktok Pixel Base Code -->
                <script type="text/javascript">
                    var wffn_shouldRender = 1;
					<?php do_action( 'wffn_allow_tracking_inline_js' ); ?>
                    if (1 === wffn_shouldRender) {

                        !function (w, d, t) {
                            w.TiktokAnalyticsObject = t;
                            var ttq = w[t] = w[t] || [];
                            ttq.methods = ["page", "track", "identify", "instances", "debug", "on", "off", "once", "ready", "alias", "group", "enableCookie", "disableCookie"];
                            ttq.setAndDefer = function (t, e) {
                                t[e] = function () {
                                    t.push([e].concat(Array.prototype.slice.call(arguments, 0)))
                                }
                            };
                            for (var i = 0; i < ttq.methods.length; i++)
                                ttq.setAndDefer(ttq, ttq.methods[i]);
                            ttq.instance = function (t) {
                                for (var e = ttq._i[t] || [], n = 0; n < ttq.methods.length; n++) ttq.setAndDefer(e, ttq.methods[n]);
                                return e
                            };
                            ttq.load = function (e, n) {
                                var i = "https://analytics.tiktok.com/i18n/pixel/events.js";
                                ttq._i = ttq._i || {}, ttq._i[e] = [], ttq._i[e]._u = i, ttq._t = ttq._t || {}, ttq._t[e] = +new Date, ttq._o = ttq._o || {}, ttq._o[e] = n || {};
                                var o = document.createElement("script");
                                o.type = "text/javascript", o.async = !0, o.src = i + "?sdkid=" + e + "&lib=" + t;
                                var a = document.getElementsByTagName("script")[0];
                                a.parentNode.insertBefore(o, a)
                            };


                        }(window, document, 'ttq');

					<?php foreach ( $get_each_pixel_id as $id ) { ?>

						ttq.load('<?php echo esc_js( $id ) ?>');
						<?php if ( count( $advanced_pixel_data ) > 0 ) { ?>
                        ttq.instance('<?php echo $id; ?>' ).identify(<?php echo wp_json_encode($advanced_pixel_data); ?>);
					    <?php } ?>
						<?php if ( $this->should_render_view( 'tiktok' ) ) { ?>
						    ttq.page();
						<?php } ?>
						<?php } ?>
                    }

                </script>

				<?php if ( $this->do_track_tiktok() || $this->do_track_cp_tiktok() ) { ?>
                    <!-- END Tiktok Pixel Base Code -->
                    <script type="text/javascript">
                    if (1 === wffn_shouldRender) {
                        setTimeout(function () {
                            <?php foreach ( $get_each_pixel_id as $id ) {
                                esc_js( $this->maybe_print_tiktok_ecomm( $id, $this->do_track_tiktok(), $this->do_track_cp_tiktok() ) );
                            } ?>
                        }, 1200);
                    }
                    </script>
				<?php
				}
			}
		}

		public function do_track_tiktok() {
			return false;
		}

		public function do_track_cp_tiktok() {
			return false;
		}

		public function maybe_print_tiktok_ecomm( $id, $purchase = false, $complete_payment = false ) {  //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter
			echo '';
		}

		/**
		 * Maybe print google analytics javascript
		 * @see WFFN_Ecomm_Tracking_Common::render_ga();
		 */
		public function maybe_print_ga_script() {
			echo '';
		}

		public function ga_code() {

			global $post;
			$step_id = 0;
			if ( $post instanceof WP_Post ) {
				$step_id = $post->ID;
			}

			$key = $this->admin_general_settings->get_option( 'ga_key' );

			if ( $step_id > 0 && get_post( $step_id ) instanceof WP_Post ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['ga_key'] ) && ! empty( $setting['ga_key'] ) ) ? $setting['ga_key'] : $key;
				}
			}

			$get_ga_key = apply_filters( 'bwf_ga_key', $key );

			return empty( $get_ga_key ) ? false : $get_ga_key;
		}

		public function gad_purchase_label() {

			global $post;
			$step_id = 0;
			if ( $post instanceof WP_Post ) {
				$step_id = $post->ID;
			}
			$key = $this->admin_general_settings->get_option( 'gad_conversion_label' );

			if ( $step_id > 0 && get_post( $step_id ) instanceof WP_Post ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['gad_conversion_label'] ) && ! empty( $setting['gad_conversion_label'] ) ) ? $setting['gad_conversion_label'] : $key;
				}
			}

			$get_gad_conversion_label = apply_filters( 'bwf_get_conversion_label', $key );

			return empty( $get_gad_conversion_label ) ? false : $get_gad_conversion_label;
		}

		public function gad_code() {
			global $post;
			$step_id = 0;
			if ( $post instanceof WP_Post ) {
				$step_id = $post->ID;
			}
			$key = $this->admin_general_settings->get_option( 'gad_key' );

			if ( $step_id > 0 && get_post( $step_id ) instanceof WP_Post ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['gad_key'] ) && ! empty( $setting['gad_key'] ) ) ? $setting['gad_key'] : $key;
				}
			}

			$get_ga_key = apply_filters( 'bwf_gad_key', $key );

			return empty( $get_ga_key ) ? false : $get_ga_key;
		}


		public function snapchat_code() {
			global $post;
			$step_id = 0;
			if ( $post instanceof WP_Post ) {
				$step_id = $post->ID;
			}
			$key = $this->admin_general_settings->get_option( 'snapchat_pixel' );

			if ( $step_id > 0 && get_post( $step_id ) instanceof WP_Post ) {
				$setting = WFFN_Common::maybe_override_tracking( $step_id );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['snapchat_pixel'] ) && ! empty( $setting['snapchat_pixel'] ) ) ? $setting['snapchat_pixel'] : $key;
				}
			}

			$get_ga_key = apply_filters( 'bwf_snapchat_pixel', $key );

			return empty( $get_ga_key ) ? false : $get_ga_key;
		}

		public function get_event_id( $event ) {
			return $event . "_" . time();
		}

		public function getRequestUri( $is_ajax = false ) {
			$request_uri = null;
			if ( true === $is_ajax ) {
				$current_step = WFFN_Core()->data->get_current_step();
				if ( is_array( $current_step ) && count( $current_step ) > 0 ) {
					return get_permalink( $current_step['id'] );
				}
			}
			if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
				$start       = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://";
				$request_uri = $start . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //phpcs:ignore
			}

			return $request_uri;
		}

		public function getEventRequestUri() {
			$request_uri = "";
			if ( ! empty( $_SERVER['REQUEST_URI'] ) ) {
				$request_uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; //phpcs:ignore
			}

			return $request_uri;
		}


		/**
		 * Is conversion API enabled from global settings
		 * @return bool
		 */
		public function is_conversion_api() {
			$is_conversion_api = $this->admin_general_settings->get_option( 'is_fb_purchase_conversion_api' );
			if ( is_array( $is_conversion_api ) && count( $is_conversion_api ) > 0 && 'yes' === $is_conversion_api[0] ) {
				return true;
			}

			return false;
		}

		/**
		 * Render a JS to fire async ajax calls to fire further events
		 */
		public function maybe_render_conv_api( $is_ajax = false ) {
			/**
			 * Special handling for the order received page
			 */
			if ( $this->is_conversion_api() ) {
				foreach ( $this->api_events as $event ) {
					$this->fire_conv_api_event( $event, $is_ajax );
				}
			}
		}

		public function get_conversion_api_access_token() {

			$steps = WFFN_Core()->data->get_current_step();
			$key   = $this->admin_general_settings->get_option( 'conversion_api_access_token' );

			if ( is_array( $steps ) && isset( $steps['id'] ) && get_post( $steps['id'] ) instanceof WP_Post ) {
				$setting = WFFN_Common::maybe_override_tracking( $steps['id'] );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['conversion_api_access_token'] ) && ! empty( $setting['conversion_api_access_token'] ) ) ? $setting['conversion_api_access_token'] : $key;
				}
			}

			$get_pixel_key = apply_filters( 'bwf_conversion_api_access_token', $key );

			return empty( $get_pixel_key ) ? false : $get_pixel_key;
		}

		public function get_conversion_api_test_event_code() {

			$steps = WFFN_Core()->data->get_current_step();
			$key   = $this->admin_general_settings->get_option( 'conversion_api_test_event_code' );

			if ( is_array( $steps ) && isset( $steps['id'] ) && get_post( $steps['id'] ) instanceof WP_Post ) {
				$setting = WFFN_Common::maybe_override_tracking( $steps['id'] );
				if ( is_array( $setting ) ) {
					$key = ( isset( $setting['conversion_api_test_event_code'] ) && ! empty( $setting['conversion_api_test_event_code'] ) ) ? $setting['conversion_api_test_event_code'] : $key;
				}
			}

			$get_pixel_key = apply_filters( 'bwf_conversion_api_test_event_code', $key );

			return empty( $get_pixel_key ) ? false : $get_pixel_key;
		}

		/**
		 * Ajax callback modal method to handle firing of multiple events in conv api
		 *
		 * @param string $type event name
		 *
		 * @return null
		 */
		public function fire_conv_api_event( $event, $is_ajax = false ) {
			$type              = $event['event'];
			$event_id          = $event['event_id'];
			$get_all_fb_pixel  = $this->is_fb_pixel();
			$get_each_pixel_id = explode( ',', $get_all_fb_pixel );

			if ( is_array( $get_each_pixel_id ) && count( $get_each_pixel_id ) > 0 ) {

				foreach ( $get_each_pixel_id as $key => $pixel_id ) {


					$access_token = $this->get_conversion_api_access_token();
					$access_token = explode( ',', $access_token );

					if ( is_array( $access_token ) && count( $access_token ) > 0 ) {
						if ( isset( $access_token[ $key ] ) ) {

							BWF_Facebook_Sdk_Factory::setup( trim( $pixel_id ), trim( $access_token[ $key ] ) );
						}
					}

					$get_test      = $this->get_conversion_api_test_event_code();
					$get_test      = explode( ',', $get_test );
					$is_test_event = $this->admin_general_settings->get_option( 'is_fb_conv_enable_test' );
					if ( is_array( $is_test_event ) && count( $is_test_event ) > 0 && $is_test_event[0] === 'yes' && is_array( $get_test ) && count( $get_test ) > 0 ) {
						if ( isset( $get_test[ $key ] ) && ! empty( $get_test[ $key ] ) ) {
							BWF_Facebook_Sdk_Factory::set_test( trim( $get_test[ $key ] ) );
						}
					}

					BWF_Facebook_Sdk_Factory::set_partner( 'woofunnels' );
					$instance = BWF_Facebook_Sdk_Factory::create();
					if ( is_null( $instance ) ) {
						return null;
					}

					switch ( $type ) {
						case 'PageView':
							$instance->set_event_id( $event_id );
							$instance->set_user_data( $this->get_user_data( $type ) );
							$instance->set_event_source_url( $this->getRequestUri( $is_ajax ) );
							$instance->set_event_data( 'PageView', [ $event_id ] );
							if ( isset( $event['data'] ) && isset( $event['data'] ) ) {
								$instance->set_event_data( 'PageView', $event['data'] );

							} else {
								$instance->set_event_data( 'PageView', [] );

							}
							break;
						case 'Purchase':
							$instance->set_event_source_url( $this->getRequestUri( $is_ajax ) );
							$instance->set_event_id( $event_id );
							$instance->set_user_data( $this->get_user_data( $type ) );
							$instance->set_event_data( 'Purchase', $this->get_purchase_params() );
							break;
						case 'trackCustom':
							$instance->set_event_id( $event_id );
							$instance->set_user_data( $this->get_user_data( $type ) );
							$instance->set_event_source_url( $this->getRequestUri( $is_ajax ) );
							$getEventName   = $this->admin_general_settings->get_option( 'general_event_name' );
							$getEventparams = $this->get_generic_event_params_for_conv_api();
							$instance->set_event_data( $getEventName, $getEventparams );
							break;
						default:
							$instance->set_event_id( $event_id );
							$instance->set_user_data( $this->get_user_data( $type ) );
							$instance->set_event_source_url( $this->getRequestUri( $is_ajax ) );

							if ( isset( $event['data'] ) && isset( $event['data'] ) ) {
								$instance->set_event_data( $type, $event['data'] );

							} else {
								$instance->set_event_data( $type, [] );

							}
					}


					$response = $instance->execute();
					if ( $type === 'Purchase' || $type === 'AddToCart' ) {
						$this->maybe_insert_log( '----Facebook conversion API-----------' . print_r( $response, true ) ); //phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r

					}


				}
			}
		}
		/************************************ Conversion API related methods starts here ***************************/
		/**
		 * Maybe insert logs for the conversion API
		 *
		 * @param string $content
		 */
		public function maybe_insert_log( $content ) {

			if ( $this->is_enabled_log() ) {
				wc_get_logger()->log( 'info', $content, array( 'source' => 'bwf_facebook_conversion_api' ) );
			}
		}

		/**
		 * Check if logs are enabled or not for the conversion API
		 * @return bool
		 */
		public function is_enabled_log() {
			$is_conversion_api_log = $this->admin_general_settings->get_option( 'is_fb_conversion_api_log' );
			if ( is_array( $is_conversion_api_log ) && count( $is_conversion_api_log ) > 0 && 'yes' === $is_conversion_api_log[0] ) {
				return true;
			}

			return false;
		}

		/**
		 * Get current hour in the format supported by Facebook
		 * @return string string
		 */
		public function getHour() {
			$array = [
				'00-01',
				'01-02',
				'02-03',
				'03-04',
				'04-05',
				'05-06',
				'06-07',
				'07-08',
				'08-09',
				'09-10',
				'10-11',
				'11-12',
				'12-13',
				'13-14',
				'14-15',
				'15-16',
				'16-17',
				'17-18',
				'18-19',
				'19-20',
				'20-21',
				'21-22',
				'22-23',
				'23-24'
			];

			return $array[ gmdate( "G" ) ];

		}

		public function get_user_data( $type ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
			$user_data                      = WFFN_Common::pixel_advanced_matching_data();
			$user_data['client_ip_address'] = wffn_get_ip_address();
			$user_data['client_user_agent'] = wffn_get_user_agent();
			if ( isset( $_COOKIE['_fbp'] ) && ! empty( $_COOKIE['_fbp'] ) ) {
				$user_data['_fbp'] = wffn_clean( $_COOKIE['_fbp'] ); //phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
			}
			if ( isset( $_COOKIE['_fbc'] ) && ! empty( $_COOKIE['_fbc'] ) ) {
				$user_data['_fbc'] = wffn_clean( $_COOKIE['_fbc'] ); //phpcs:ignore WordPressVIPMinimum.Variables.RestrictedVariables.cache_constraints___COOKIE
			} elseif ( isset( $_COOKIE['wffn_fbclid'] ) && isset( $_COOKIE['wffn_flt'] ) && ! empty( $_COOKIE['wffn_fbclid'] ) ) {
				$user_data['_fbc'] = 'fb.1.' . strtotime( wffn_clean( $_COOKIE['wffn_flt'] ) ) . '.' . wffn_clean( $_COOKIE['wffn_fbclid'] );
			}

			return $user_data;
		}

		public function maybe_ecomm_events( $events ) {

			$this->api_events = $events;
			$this->maybe_render_conv_api( true );
		}


		public function maybe_track_custom_steps_event() {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

			if ( $this->should_render() && $this->is_enable_custom_event() ) {

				?>
                if(typeof wffnEvents === 'undefined'){
                var wffnEvents = [];
                }
                var wffn_ev_custom_fb_event_id = Math.random().toString(36).substring(2, 15);
                fbq('trackCustom', '<?php echo esc_attr( $this->get_custom_event_name() ); ?>', (typeof wffnAddTrafficParamsToEvent !== "undefined")?wffnAddTrafficParamsToEvent(<?php echo $this->get_custom_event_params(); ?>):<?php echo $this->get_custom_event_params(); ?>,{'eventID': '<?php echo esc_attr( $this->get_custom_event_name() ); ?>_'+wffn_ev_custom_fb_event_id}); <?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                wffnEvents.push({event: '<?php echo esc_attr( $this->get_custom_event_name() ); ?>', 'event_id': '<?php echo esc_attr( $this->get_custom_event_name() ); ?>_'+wffn_ev_custom_fb_event_id});
				<?php

			}
		}

		public function maybe_track_custom_steps_event_pint() {//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

			if ( $this->should_render() && $this->is_enable_custom_event_pint() ) {

				?>
                pintrk('track', '<?php echo esc_attr( $this->get_custom_event_name() ); ?>', (typeof wffnAddTrafficParamsToEvent !== "undefined")?wffnAddTrafficParamsToEvent(<?php echo $this->get_custom_event_params(); ?>):<?php echo $this->get_custom_event_params(); ?>);  <?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php

			}
		}

		public function is_enable_custom_event() {
			$is_fb_custom_events = $this->admin_general_settings->get_option( 'is_fb_custom_events' );

			if ( '1' === $is_fb_custom_events ) {
				return true;
			}

			return false;
		}

		public function is_enable_custom_event_pint() {
			$is_pint_custom_events = $this->admin_general_settings->get_option( 'is_pint_custom_events' );

			if ( '1' === $is_pint_custom_events ) {
				return true;
			}

			return false;
		}

		public function get_custom_event_name() {
			return 'WooFunnels_Sales';
		}

		public function get_custom_event_params() {
			return wp_json_encode( [] );
		}

		public function get_user_email() {
			$current_user = wp_get_current_user();

			// not logged in
			if ( empty( $current_user ) || $current_user->ID === 0 ) {
				return '';
			}

			return $current_user->user_email;
		}

		/**
		 * Maybe print google analytics javascript
		 * @see WFFN_Ecomm_Tracking::render_ga();
		 * @see WFFN_Ecomm_Tracking::render_gad();
		 */
		public function maybe_print_pint_ecomm() { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

		}

		/**
		 * Maybe print google analytics javascript
		 * @see WFFN_Ecomm_Tracking::render_ga();
		 * @see WFFN_Ecomm_Tracking::render_gad();
		 */
		public function maybe_print_snapchat_ecomm() { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter

		}

	}
}

