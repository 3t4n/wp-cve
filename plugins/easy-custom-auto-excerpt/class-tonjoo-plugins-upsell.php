<?php
if ( ! class_exists( 'Tonjoo_Plugins_Upsell' ) ) {
	/**
	 * version: 0.2
	 */
	class Tonjoo_Plugins_Upsell {

		/**
		 * API url
		 *
		 * @var string
		 */
		public $api_url;

		/**
		 * Current plugin path
		 *
		 * @var string
		 */
		public $current;

		/**
		 * Constructor
		 *
		 * @param string $current Current plugin path.
		 */
		public function __construct( $current = '' ) {
			$this->api_url = 'https://tonjoostudio.com/upsells/';
			$this->current = $current;
		}

		/**
		 * Fetch plugin data from server
		 *
		 * @return array Plugin data.
		 */
		private function get_plugins_data() {
			$data = array();
			if ( false === ( $data = get_transient( 'tonjoo_plugins' ) ) ) {
				$response = wp_remote_get( $this->api_url );
				if ( is_wp_error( $response ) ) {
					return $data;
				}
				$body = wp_remote_retrieve_body( $response );
				if ( is_wp_error( $body ) ) {
					return $data;
				}
				$data = json_decode( $body );
				set_transient( 'tonjoo_plugins', $data, 60 * 60 * 24 );
			}
			return $data;
		}

		/**
		 * Generate data
		 *
		 * @return array Output data.
		 */
		private function generate_data() {
			$data = $this->get_plugins_data();
			$output = array();
			if ( ! empty( $data ) ) {
				foreach ( $data as $d ) {
					$temp = new stdClass();
					$temp->name = $d->name;
					$temp->type = $d->type;
					$temp->is_featured = $d->is_featured;
					$temp->image = $d->image;
					$temp->installed = false;
					$temp->button_primary = false;
					$temp->button_secondary = false;
					$temp->learn_more = false;

					if ( isset( $d->free ) && isset( $d->pro ) ) {
						$plugin_type = 'both';
					} elseif ( isset( $d->free ) ) {
						$plugin_type = 'only_free';
					} elseif ( isset( $d->pro ) ) {
						$plugin_type = 'only_pro';
					} else {
						continue;
					}

					if ( 'plugin' === $d->type ) {

						if ( $d->require_woocommerce && ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
							continue;
						}

						$temp->button_primary = array(
							'text'  => __( 'Get Now' ),
							'url'   => $d->pro->url,
						);
						if ( ! empty( $d->main_url ) ) {
							$temp->learn_more = array(
								'text'  => __( 'Learn More' ),
								'url'   => $d->main_url,
							);
						}

						if ( 'both' === $plugin_type ) {
							if ( is_plugin_active( $d->pro->name ) ) {
								$temp->installed = true;
							} elseif ( is_plugin_active( $d->free->name ) ) {
								$temp->name = $temp->name . ' PRO';
								$temp->button_primary = array(
									'text'  => __( 'Upgrade Now' ),
									'url'   => $d->pro->url,
								);
							} else {
								$temp->button_secondary = array(
									'text'  => __( 'Get Free Version' ),
									'url'   => $d->free->url,
								);
								$temp->button_primary = array(
									'text'  => __( 'Get Pro Version' ),
									'url'   => $d->pro->url,
								);
							}
						} elseif ( 'only_pro' === $plugin_type ) {
							if ( is_plugin_active( $d->pro->name ) ) {
								$temp->installed = true;
							}
						} elseif ( 'only_free' === $plugin_type ) {
							if ( is_plugin_active( $d->free->name ) ) {
								$temp->installed = true;
							}
						}
					} elseif ( 'theme' === $d->type ) {

						$temp->button_primary = array(
							'text'  => __( 'Get Theme' ),
							'url'   => ! empty( $d->pro->url ) ? $d->pro->url : $d->free->url,
						);
						if ( ! empty( $d->main_url ) ) {
							$temp->learn_more = array(
								'text'  => __( 'Live Preview' ),
								'url'   => $d->main_url,
							);
						}
					}
					$temp->description = $d->description;
					array_push( $output, $temp );
				}
			}
			return $output;
		}

		/**
		 * Render upsell page
		 */
		public function render() {
			$data = $this->generate_data();
			if ( ! empty( $data ) ) {
				?>
				<style>
					#tonjoo-upsell *,
					#tonjoo-upsell *:before,
					#tonjoo-upsell *:after{
						box-sizing:border-box;
						-webkit-box-sizing:border-box;
						-moz-box-sizing:border-box
					}
					#tonjoo-upsell .plugin-card .desc {
						margin-right: 0;
					}
					#tonjoo-upsell .plugin-card .name {
						margin-right: 0;
					}
					#tonjoo-upsell .plugin-card-top {
						padding-bottom: 20px;
						min-height: 168px;
					}
					#tonjoo-upsell .plugin-card-top .column-description p {
						margin-bottom: 0;
					}
					#tonjoo-upsell .plugin-card-bottom {
						text-align: right;
					}
					#tonjoo-upsell .plugin-card-bottom a.button-primary {
						margin-left: 6px;
					}
					#tonjoo-upsell .column-name a {
						text-decoration: none;
					}
					#tonjoo-upsell .column-name .type {
						text-transform: capitalize;
					}
					#tonjoo-upsell .float-left {
						float: left;
						text-decoration: none;
						display: inline-block;
						line-height: 24px;
					}
					#tonjoo-upsell .float-left:hover {
						text-decoration: underline;
					}
				</style>
				<div id="tonjoo-upsell">
					<?php foreach ( $data as $d ) : ?>
						<?php if ( ! $d->installed ) : ?>
							<div class="plugin-card">
								<div class="plugin-card-top">
									<div class="name column-name">
										<div class="type"><strong><?php echo esc_html( $d->type ); ?></strong></div>
										<h3>
											<a href="<?php echo esc_html( $d->button_primary['url'] ); ?>?utm_source=wp_<?php echo esc_attr( $this->current ); ?>&utm_medium=onboarding_upsell&utm_campaign=upsell" target="_blank">
												<?php echo esc_html( $d->name ); ?>
												<img src="<?php echo esc_url( $d->image ); ?>" alt="" class="plugin-icon">
											</a>
										</h3>
									</div>
									<div class="desc column-description">
										<p><?php echo esc_html( $d->description ); ?></p>
									</div>
								</div>
								<div class="plugin-card-bottom">
									<?php if ( $d->learn_more ) : ?>
										<a href="<?php echo esc_url( $d->learn_more['url'] ); ?>?utm_source=wp_<?php echo esc_attr( $this->current ); ?>&utm_medium=onboarding_upsell&utm_campaign=upsell" target="_blank" class="float-left">
											<?php echo esc_html( $d->learn_more['text'] ); ?>
										</a>
									<?php endif; ?>
									<?php if ( $d->button_secondary ) : ?>
										<a href="<?php echo esc_url( $d->button_secondary['url'] ); ?>?utm_source=wp_<?php echo esc_attr( $this->current ); ?>&utm_medium=onboarding_upsell&utm_campaign=upsell" target="_blank" class="button">
											<?php echo esc_html( $d->button_secondary['text'] ); ?>
										</a>
									<?php endif; ?>
									<?php if ( $d->button_primary ) : ?>
										<a href="<?php echo esc_url( $d->button_primary['url'] ); ?>?utm_source=wp_<?php echo esc_attr( $this->current ); ?>&utm_medium=onboarding_upsell&utm_campaign=upsell" target="_blank" class="button-primary">
											<?php echo esc_html( $d->button_primary['text'] ); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php
			}
		}

	}
}
