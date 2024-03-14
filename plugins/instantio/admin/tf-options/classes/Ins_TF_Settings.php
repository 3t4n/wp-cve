<?php
// don't load directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Ins_TF_Settings' ) ) {
	class Ins_TF_Settings {

		public $option_id = null;
		public $option_title = null;
		public $option_icon = null;
		public $option_position = null;
		public $option_sections = array();

		public function __construct( $key, $params = array() ) {
			$this->option_id = $key;
			$this->option_title = ! empty( $params['title'] ) ? apply_filters( $key . '_title', $params['title'] ) : '';
			$this->option_icon = ! empty( $params['icon'] ) ? apply_filters( $key . '_icon', $params['icon'] ) : '';
			$this->option_position = ! empty( $params['position'] ) ? apply_filters( $key . '_position', $params['position'] ) : 5;
			$this->option_sections = ! empty( $params['sections'] ) ? apply_filters( $key . '_sections', $params['sections'] ) : array();

			// run only is admin panel options, avoid performance loss
			$this->pre_tabs = $this->pre_tabs( $this->option_sections );
			$this->pre_fields = $this->pre_fields( $this->option_sections );
			$this->pre_sections = $this->pre_sections( $this->option_sections );

			//options
			add_action( 'admin_menu', array( $this, 'instantio_tf_options' ) );

			//save options
			add_action( 'admin_init', array( $this, 'save_options' ) );

			//ajax save options
			add_action( 'wp_ajax_tf_options_save', array( $this, 'tf_ajax_save_options' ) );

			// constent defined
			if ( ! defined( 'TF_OPTION_ID' ) ) {
				define( 'TF_OPTION_ID', $this->option_id );
			}
		}



		public static function option( $key, $params = array() ) {
			return new self( $key, $params );
		}

		public function pre_tabs( $sections ) {

			$result = array();
			$parents = array();

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['parent'] ) ) {
					$parents[ $section['parent'] ][ $key ] = $section;
					unset( $sections[ $key ] );
				}
			}

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $key ) && ! empty( $parents[ $key ] ) ) {
					$section['sub_section'] = $parents[ $key ];
				}
				$result[ $key ] = $section;
			}

			return $result;
		}

		public function pre_fields( $sections ) {

			$result = array();

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						$result[] = $field;
					}
				}
			}

			return $result;
		}

		public function pre_sections( $sections ) {

			$result = array();

			foreach ( $this->pre_tabs as $tab ) {
				if ( ! empty( $tab['subs'] ) ) {
					foreach ( $tab['subs'] as $sub ) {
						$sub['ptitle'] = $tab['title'];
						$result[] = $sub;
					}
				}
				if ( empty( $tab['subs'] ) ) {
					$result[] = $tab;
				}
			}

			return $result;
		}

		/**
		 * Options Page menu
		 * @author Foysal
		 */
		public function instantio_tf_options() {
			add_menu_page(
				$this->option_title,
				$this->option_title,
				'manage_options',
				$this->option_id,
				array( $this, 'ins_admin_options_page' ),
				$this->option_icon,
				$this->option_position
			);

			//What's New submenu
			add_submenu_page(
				$this->option_id,
				__( 'Dashboard', 'instantio' ),
				__( 'Dashboard', 'instantio' ),
				'manage_options',
				'ins_dashboard',
				array( $this, 'ins_get_dashboard_callback' ),
			);


			//Setting submenu
			add_submenu_page(
				$this->option_id,
				__( 'Settings', 'instantio' ),
				__( 'Settings', 'instantio' ),
				'manage_options',
				$this->option_id . '#tab=general',
				array( $this, 'ins_admin_options_page' ),
			);

			//What's New submenu Update to pro
			if ( ! is_plugin_active( 'wooinstant/wooinstant.php' ) ) {

				add_submenu_page(
					$this->option_id,
					__( 'Upgrade to Pro', 'instantio' ),
					'<span style="color:#db5209; font-weight: 900;">' . __( "Upgrade to Pro", "instantio" ) . '</span>',
					'manage_options',
					'https://themefic.com/instantio/go/upgrade',

				);
			}

			// remove first submenu
			remove_submenu_page( $this->option_id, $this->option_id );

		}


		/**
		 * Page top header
		 * @author M Hemel Hasan
		 */
		function ins_admin_top_header() {
			?>
			<div class="tf-setting-top-bar">
				<div class="version">
					<img src="<?php echo INS_ADMIN_URL; ?>/tf-options/img/instanio-logo.png" alt="logo">
					<span>
						<?php echo INSTANTIO_VERSION; ?>
					</span>
				</div>
				<div class="other-document">
					<svg width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg"
						style="color: #DB5209;">
						<path
							d="M19.2106 0H6.57897C2.7895 0 0.263184 2.52632 0.263184 6.31579V13.8947C0.263184 17.6842 2.7895 20.2105 6.57897 20.2105V22.9011C6.57897 23.9116 7.70318 24.5179 8.53687 23.9495L14.1579 20.2105H19.2106C23 20.2105 25.5263 17.6842 25.5263 13.8947V6.31579C25.5263 2.52632 23 0 19.2106 0ZM12.8948 15.3726C12.3642 15.3726 11.9474 14.9432 11.9474 14.4253C11.9474 13.9074 12.3642 13.4779 12.8948 13.4779C13.4253 13.4779 13.8421 13.9074 13.8421 14.4253C13.8421 14.9432 13.4253 15.3726 12.8948 15.3726ZM14.4863 10.1305C13.9937 10.4589 13.8421 10.6737 13.8421 11.0274V11.2926C13.8421 11.8105 13.4127 12.24 12.8948 12.24C12.3769 12.24 11.9474 11.8105 11.9474 11.2926V11.0274C11.9474 9.56211 13.0211 8.84211 13.4253 8.56421C13.8927 8.24842 14.0442 8.03368 14.0442 7.70526C14.0442 7.07368 13.5263 6.55579 12.8948 6.55579C12.2632 6.55579 11.7453 7.07368 11.7453 7.70526C11.7453 8.22316 11.3158 8.65263 10.7979 8.65263C10.28 8.65263 9.85055 8.22316 9.85055 7.70526C9.85055 6.02526 11.2148 4.66105 12.8948 4.66105C14.5748 4.66105 15.939 6.02526 15.939 7.70526C15.939 9.14526 14.8779 9.86526 14.4863 10.1305Z"
							fill="#DB5209"></path>
					</svg>

					<div class="dropdown">
						<div class="list-item">
							<a href="https://portal.themefic.com/support/" target="_blank">
								<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path
										d="M10.0482 4.37109H4.30125C4.06778 4.37109 3.84329 4.38008 3.62778 4.40704C1.21225 4.6137 0 6.04238 0 8.6751V12.2693C0 15.8634 1.43674 16.5733 4.30125 16.5733H4.66044C4.85799 16.5733 5.1184 16.708 5.23514 16.8608L6.3127 18.2985C6.78862 18.9364 7.56087 18.9364 8.03679 18.2985L9.11435 16.8608C9.24904 16.6811 9.46456 16.5733 9.68905 16.5733H10.0482C12.6793 16.5733 14.107 15.3692 14.3136 12.9432C14.3405 12.7275 14.3495 12.5029 14.3495 12.2693V8.6751C14.3495 5.80876 12.9127 4.37109 10.0482 4.37109ZM4.04084 11.5594C3.53798 11.5594 3.14288 11.1551 3.14288 10.6609C3.14288 10.1667 3.54696 9.76233 4.04084 9.76233C4.53473 9.76233 4.93881 10.1667 4.93881 10.6609C4.93881 11.1551 4.53473 11.5594 4.04084 11.5594ZM7.17474 11.5594C6.67188 11.5594 6.27678 11.1551 6.27678 10.6609C6.27678 10.1667 6.68086 9.76233 7.17474 9.76233C7.66862 9.76233 8.07271 10.1667 8.07271 10.6609C8.07271 11.1551 7.6776 11.5594 7.17474 11.5594ZM10.3176 11.5594C9.81476 11.5594 9.41966 11.1551 9.41966 10.6609C9.41966 10.1667 9.82374 9.76233 10.3176 9.76233C10.8115 9.76233 11.2156 10.1667 11.2156 10.6609C11.2156 11.1551 10.8115 11.5594 10.3176 11.5594Z"
										fill="#DB5209"></path>
									<path
										d="M17.9423 5.08086V8.67502C17.9423 10.4721 17.3855 11.6941 16.272 12.368C16.0026 12.5298 15.6884 12.3141 15.6884 11.9996L15.6973 8.67502C15.6973 5.08086 13.641 3.0232 10.0491 3.0232L4.58048 3.03219C4.26619 3.03219 4.05067 2.7177 4.21231 2.44814C4.88578 1.33395 6.10702 0.776855 7.89398 0.776855H13.641C16.5055 0.776855 17.9423 2.21452 17.9423 5.08086Z"
										fill="#DB5209"></path>
								</svg>
								<span>
									<?php _e( "Need Help?", "instantio" ); ?>
								</span>
							</a>
							<a href="https://themefic.com/docs/instantio/" target="_blank">
								<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path
										d="M16.1896 7.57803H13.5902C11.4586 7.57803 9.72274 5.84103 9.72274 3.70803V1.10703C9.72274 0.612031 9.318 0.207031 8.82332 0.207031H5.00977C2.23956 0.207031 0 2.00703 0 5.22003V13.194C0 16.407 2.23956 18.207 5.00977 18.207H12.0792C14.8494 18.207 17.089 16.407 17.089 13.194V8.47803C17.089 7.98303 16.6843 7.57803 16.1896 7.57803ZM8.09478 14.382H4.4971C4.12834 14.382 3.82254 14.076 3.82254 13.707C3.82254 13.338 4.12834 13.032 4.4971 13.032H8.09478C8.46355 13.032 8.76935 13.338 8.76935 13.707C8.76935 14.076 8.46355 14.382 8.09478 14.382ZM9.89363 10.782H4.4971C4.12834 10.782 3.82254 10.476 3.82254 10.107C3.82254 9.73803 4.12834 9.43203 4.4971 9.43203H9.89363C10.2624 9.43203 10.5682 9.73803 10.5682 10.107C10.5682 10.476 10.2624 10.782 9.89363 10.782Z"
										fill="#DB5209"></path>
								</svg>
								<span>
									<?php _e( "Documentation", "instantio" ); ?>
								</span>

							</a>
							<a href="https://themefic.com/feature-request/" target="_blank">
								<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd"
										d="M13.5902 7.57803H16.1896C16.6843 7.57803 17.089 7.98303 17.089 8.47803V13.194C17.089 16.407 14.8494 18.207 12.0792 18.207H5.00977C2.23956 18.207 0 16.407 0 13.194V5.22003C0 2.00703 2.23956 0.207031 5.00977 0.207031H8.82332C9.318 0.207031 9.72274 0.612031 9.72274 1.10703V3.70803C9.72274 5.84103 11.4586 7.57803 13.5902 7.57803ZM11.9613 0.396012C11.5926 0.0270125 10.954 0.279013 10.954 0.792013V3.93301C10.954 5.24701 12.0693 6.33601 13.4274 6.33601C14.2818 6.34501 15.4689 6.34501 16.4852 6.34501H16.4854C16.998 6.34501 17.2679 5.74201 16.9081 5.38201C16.4894 4.96018 15.9637 4.42927 15.3988 3.85888L15.3932 3.85325L15.3913 3.85133L15.3905 3.8505L15.3902 3.85016C14.2096 2.65803 12.86 1.29526 11.9613 0.396012ZM3.0145 12.0732C3.0145 11.7456 3.28007 11.48 3.60768 11.48H5.32132V9.76639C5.32132 9.43879 5.58689 9.17321 5.9145 9.17321C6.2421 9.17321 6.50768 9.43879 6.50768 9.76639V11.48H8.22131C8.54892 11.48 8.8145 11.7456 8.8145 12.0732C8.8145 12.4008 8.54892 12.6664 8.22131 12.6664H6.50768V14.38C6.50768 14.7076 6.2421 14.9732 5.9145 14.9732C5.58689 14.9732 5.32132 14.7076 5.32132 14.38V12.6664H3.60768C3.28007 12.6664 3.0145 12.4008 3.0145 12.0732Z"
										fill="#DB5209"></path>
								</svg>
								<span>
									<?php _e( "Feature Request", "instantio" ); ?>
								</span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Dashboard tab pages 
		 * @author M Hemel Hasan
		 */
		public function ins_get_dashboard_callback() {
			include_once 'Ins_ChangeLog.php';
			?>
			<div class="tf-setting-dashboard">
				<?php echo $this->ins_admin_top_header(); ?>
				<div class="ins-dashboad-wrapper">
					<ul class="dashboad-tab">
						<li class="dashboad-tab-singel active">
							<span>
								<?php _e( "General", "instantio" ); ?>
							</span>
						</li>
						<li class="dashboad-tab-singel">
							<span>
								<?php _e( "Tutorial", "instantio" ); ?>
							</span>
						</li>
						<li class="dashboad-tab-singel">
							<span>
								<?php _e( "Pro", "instantio" ); ?>
							</span>
						</li>
						<li class="dashboad-tab-singel">
							<span>
								<?php _e( "FAQs", "instantio" ); ?>
							</span>
						</li>
						<li class="dashboad-tab-singel">
							<span>
								<?php _e( "What's New", "instantio" ); ?>
							</span>
						</li>
					</ul>

					<div class="dashboad-content-wrap">

						<div class="dashboad-content help-center active">
							<div class="tf-settings-help-center">
								<div class="tf-help-center-banner"
									style="background-image: url('<?php echo INS_ADMIN_URL ?>/tf-options/img/wizard/setup_wizard_bg.png')">
									<div class="tf-help-center-content">
										<h2>
											<?php _e( "Setup Wizard", "instantio" ); ?>
										</h2>
										<p>
											<?php _e( "Click the button below to run the setup wizard of Instantio. Your existing settings will be change.", "instantio" ); ?>
										</p>
										<a href="<?php echo esc_url( admin_url( 'admin.php?page=tf-setup-wizard' ) ) ?>"
											class="tf-admin-btn tf-btn-secondary">
											<?php _e( "Setup Wizard", "instantio" ); ?>
										</a>
									</div>
									<!-- <div class="tf-help-center-content-img">
										<img src="<?php // echo INS_ADMIN_URL?>/tf-options/img/wizard/setup_wizard_icon.svg" alt="image"/>
									</div> -->

								</div>

								<div class="tf-support-document">
									<div class="tf-single-support">
										<a href="https://themefic.com/docs/instantio/" target="_blank">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/wizard/tf-documents.svg' ?>"
												alt="Document">
											<h3>
												<?php _e( "Documentation", "instantio" ); ?>
											</h3>
											<p>
												<?php _e( "How the plugin works, what it can do, and how to use it.", "instantio" ); ?>
											</p>
											<span>
												<?php _e( "Read More", "instantio" ); ?>
											</span>
										</a>
									</div>
									<div class="tf-single-support">
										<a href="https://portal.themefic.com/support/" target="_blank">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/wizard/tf-mail.svg' ?>"
												alt="Document">
											<h3>
												<?php _e( "Email Support", "instantio" ); ?>
											</h3>
											<p>
												<?php _e( "As part of our overall support strategy to provide the best experience.", "instantio" ); ?>
											</p>
											<span>
												<?php _e( "Contact Us", "instantio" ); ?>
											</span>
										</a>
									</div>

									<div class="tf-single-support">
										<a href="https://www.youtube.com/playlist?list=PLY0rtvOwg0ykIvNBa8XI3SR7WEbdqqKoO"
											target="_blank">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/wizard/tf-tutorial.svg' ?>"
												alt="Document">
											<h3>
												<?php _e( "Video Tutorials", "instantio" ); ?>
											</h3>
											<p>
												<?php _e( "We allows you to get help in real-time, which can improve satisfaction.", "instantio" ); ?>
											</p>
											<span>
												<?php _e( "Watch Video", "instantio" ); ?>
											</span>
										</a>
									</div>
								</div>

								<?php
								$is_Pro_class = new Ins_TF_Options;
								$is_Pro_active = $is_Pro_class->is_ins_pro_active();

								if ( $is_Pro_active === false ) { ?>

									<div class="updatedtopro">
										<h4>
											<?php _e( "Upgrade to PRO", "instantio" ); ?>
										</h4>

										<p>
											<?php _e( "To provide amazing experience to your guests and sell more with less effort. Bonus: You can upgrade to our plans today and save 50% off.", "instantio" ); ?>
										</p>

										<a target="_blank" href="https://themefic.com/instantio/" class="btn-desh-primary">
											<?php _e( "Upgrade now", "instantio" ); ?>
										</a>
									</div>

								<?php } ?>


								<div class="request-features">
									<img src="<?php echo INS_ADMIN_URL ?>/tf-options/img/feature-selection.png" alt="image">
									<h4>
										<?php _e( "Have any thoughts or feature request?", "instantio" ); ?>
									</h4>
									<p>
										<?php _e( "We believe your feature request will make our website more user-friendly. Our dedicated team will review it and take it into consideration for future updates.", "instantio" ); ?>
									</p>
									<a target="_blank" href="https://themefic.com/feature-request/" class="btn-desh-primary-tran">
										<?php _e( "Submit request", "instantio" ); ?>
									</a>
								</div>
							</div>
						</div>

						<div class="dashboad-content tutorial">
							<div class="tutorial_wrapper">
								<div class="tutorial-heading">
									<h4>
										<?php _e( "Basic Tutorials", "instantio" ); ?>
									</h4>

									<a target="_blank"
										href="https://www.youtube.com/playlist?list=PLY0rtvOwg0ykIvNBa8XI3SR7WEbdqqKoO"
										class="btn view-all-btn">
										<?php _e( "View all", "instantio" ); ?>
									</a>
								</div>
								<div class="tutorial-body">
									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/tutorial-2.png' ?>"
												class="figure-img" alt="turorial" />
											<div class="play-button-overlap">
												<a target="_blank"
													href="https://www.youtube.com/watch?v=1biwrwu-Io8&list=PLY0rtvOwg0ykIvNBa8XI3SR7WEbdqqKoO&index=2">
													<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/play.png' ?>"
														alt="turorial" /></a>
											</div>
										</div>
										<figcaption class="figure-caption">
											<?php _e( "How to setup a Fast WooCommerce Checkout", "instantio" ); ?>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/tutorial-1.png' ?>"
												class="figure-img" alt="turorial" />
											<div class="play-button-overlap">
												<a target="_blank"
													href="https://www.youtube.com/watch?v=2RYjb-dZSlE&list=PLY0rtvOwg0ykIvNBa8XI3SR7WEbdqqKoO&index=1">
													<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/play.png' ?>"
														alt="turorial" /></a>
											</div>
										</div>
										<figcaption class="figure-caption">
											<?php _e( "How to Install / Update Instantio", "instantio" ); ?>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/tutorial.png' ?>"
												class="figure-img" alt="turorial" />
											<div class="play-button-overlap">
												<a target="_blank"
													href="https://www.youtube.com/watch?v=tW9iRCYASSs&list=PLY0rtvOwg0ykIvNBa8XI3SR7WEbdqqKoO&index=3">
													<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/play.png' ?>"
														alt="turorial" />
												</a>
											</div>
										</div>
										<figcaption class="figure-caption">
											<?php _e( "Instant Checkout for WooCommerce", "instantio" ); ?>
										</figcaption>
									</figure>
								</div>
							</div>
						</div>

						<div class="dashboad-content premium">
							<div class="premium_wrapper">
								<div class="premium-heading">
									<h4>
										<?php _e( "Pro Features", "instantio" ); ?>
									</h4>

									<a target="_blank" href="https://themefic.com/instantio/" class="btn view-all-btn">
										<?php _e( "View all", "instantio" ); ?>
									</a>
								</div>
								<div class="premium-body">
									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/pro/Side-CartCheckout-Pro-Multi-Step.jpg' ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php _e( "Side Cart + Side Checkout (Multi Step)", "instantio" ); ?>
											</h4>
											<p>
												<?php _e( "Customer will checkout from Same Window (Side drawer). The checkout process will be Multi-step (Cart -> Checkout, No Reload).", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://wpinstant.io/side-checkout/" class="btn-premium-fea">
												<?php _e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/pro/Side-CartCheckout-Pro-Single-Step.jpg' ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php _e( "Side Cart + Side Checkout (Single Step)", "instantio" ); ?>
											</h4>
											<p>
												<?php _e( "The checkout process will be Single-step. Cart & Checkout will be shown on the Same Window, No Page Reload.", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://wpinstant.io/side-checkout-single/"
												class="btn-premium-fea">
												<?php _e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/pro/Popup-CartCheckout-Pro-Multi-Step.jpg' ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php _e( "Popup Cart + Popup Checkout (Multi Step)", "instantio" ); ?>
											</h4>

											<p>
												<?php _e( "Customer will checkout from Same Window (Popup). The checkout process will be Multi-step (Cart -> Checkout)", "instantio" ); ?>
											</p>

											<a target="_blank" href="https://wpinstant.io/popup-checkout/" class="btn-premium-fea">
												<?php _e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/pro/Popup-CartCheckout-Pro-Single-Step.jpg' ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php _e( "Popup Cart + Popup Checkout (Single Step)", "instantio" ); ?>
											</h4>
											<p>
												<?php _e( "The checkout process will be Single-step Popup (Cart & Checkout on Same Window, No Page Reload).", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://wpinstant.io/popup-checkout-single/"
												class="btn-premium-fea">
												<?php _e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/pro/Up-Sell.jpg' ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php _e( "Upsell (Pro)", "instantio" ); ?>
											</h4>
											<p>
												<?php _e( "Instantio offer Ajax-based Upsell feature with which you can sell related or complementary products to a customer.", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://wpinstant.io/upsells/" class="btn-premium-fea">
												<?php _e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/pro/Cross-Sell.jpg' ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php _e( "Cross-sell (Pro)", "instantio" ); ?>
											</h4>
											<p>
												<?php _e( "With Instantio, you can also do Ajax based cross-sell by selling related or complementary products to a customer.", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://wpinstant.io/cross-sells/" class="btn-premium-fea">
												<?php _e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

									<figure class="figure">
										<div class="main-caption">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/pro/Dedicated-Mobile-Layout.png' ?>"
												class="figure-img" alt="turorial" />
										</div>
										<figcaption class="figure-caption">
											<h4>
												<?php _e( "Dedicated Mobile Layout", "instantio" ); ?>
											</h4>
											<p>
												<?php _e( "A dedicated mobile layout for smaller devices to make your checkout process much smoother for customers.", "instantio" ); ?>
											</p>
											<a target="_blank" href="https://wpinstant.io/mobile/" class="btn-premium-fea">
												<?php _e( "See Preview", "instantio" ); ?>
											</a>
										</figcaption>
									</figure>

								</div>
							</div>
						</div>

						<div class="dashboad-content faqs">
							<div class="faqs_wrapper">
								<div class="faqs-heading">
									<h4>
										<?php _e( "Frequently asked questions", "instantio" ); ?>
									</h4>
								</div>

								<div class="tf-accordion-wrapper">
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php _e( "1. What is WooCommerce One Page Checkout?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php _e( "WooCommerce One Page Checkout means converting the default multistep checkout for WooCommerce process into a single page Checkout. WordPress Plugins like Instantio offers such a solution.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php _e( "2. What is Direct Checkout for WooCommerce?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php _e( "WooCommerce Direct Checkout is a solution to reduce the steps of the default Woocommerce checkout process. Customers can skip the cart page and directly checkout woocommerce (go directly to the checkout page). This helps improving cart abandonment of a website. Our Plugin Instantio offers such a solution.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php _e( "3. How to install Instantio?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php _e( "See the installation link. <a traget='_blank' href='https://wordpress.org/plugins/instantio/#installation'>Install Link</a> ", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php _e( "4. Is the Free version fully free or there is a gap?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php _e( "Yes, Instantio is fully free which is available on WordPress.org. This free version will always be free. It also has a pro version with additional features which you can purchase from our official website.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php _e( "5. Is the free version supported?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php _e( "Yes, we fully support both the free and pro version. Please feel free to post questions or bug reports through our website, but for timely support, we recommend purchasing Pro version.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php _e( "5. Will I be able to edit WooCommerce checkout page with Instantio?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php _e( "Yes, Instantio allows you to edit WooCommerce checkout page to some extent. You can remove the cart page and make your customer directly go to the checkout page.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php _e( "6. Does Instantio allows WooCommerce one-click checkout?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php _e( "Yes, Instantio converts WooCommerce multistep checkout process into WooCommerce one click checkout.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php _e( "7. Will I be able to skip cart page on WooCommerce?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php _e( "Yes, Instantio allows you to skip cart page WooCommerce and make your customer directly go to the checkout page.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php _e( "8. Does Instantio allows WooCommerce Quick checkout?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php _e( "Yes, Instantio converts the default multistep WooCommerce checkout process into WooCommerce Quick checkout.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
									<div class="tf-accrodian-item">
										<div class="tf-single-faq">
											<div class="tf-faq-title">
												<i class="fas fa-angle-down"></i>
												<h4>
													<?php _e( "9. Does Instantio allows WooCommerce Express checkout?", "instantio" ); ?>
												</h4>
											</div>
											<div class="tf-faq-desc">
												<p>
													<?php _e( "Yes, Instantio converts the default multistep WooCommerce checkout process into WooCommerce Express checkout.", "instantio" ); ?>
												</p>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>

						<div class="dashboad-content whatnew">
							<div class="whatnew_wrapper">
								<div class="whatnew-heading">
									<h4>
										<?php _e( "All updates", "instantio" ); ?>
									</h4>
								</div>
								<div class="whatnew_updates">
									<?php
									if ( ! empty( $change ) ) {
										foreach ( $change as $key => $value ) { ?>

											<div class="whatnew_updates_card">
												<div class="cardleft_date_version">
													<div class="ins_cardleft_date">
														<?php echo $value['date']; ?>
													</div>
													<div class="ins_cardleft_version">
														<?php echo $value['version']; ?>
													</div>
												</div>

												<div class="cardright_changelog">
													<?php
													$changelogs = $value['changelog'];
													if ( ! empty( $changelogs ) ) {
														foreach ( $changelogs as $key => $values ) { ?>
															<ul class="ins_changelog_<?php echo $key ?>">
																<span>
																	<?php echo $key ?>
																</span>
																<?php foreach ( $values as $value ) { ?>
																	<li>
																		<?php echo $value ?>
																	</li>
																<?php } ?>
															</ul>
														<?php
														}
													} ?>
												</div>
											</div>

										<?php }
									} else { ?>

										<div class="whatnew_updates_card">
											<div class="chnagelog_not_found">
												<?php _e( "No change logs found. Please try again later. Maybe the changelog is being updated, it will come shortly.", "instantio" ); ?>
											</div>
										</div>

									<?php } ?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<?php
		}


		/**
		 * Options Page
		 * @author M Hemel Hasan
		 */
		public function ins_admin_options_page() {

			// Retrieve an existing value from the database.
			$tf_option_value = get_option( $this->option_id );
			$current_page_url = $this->get_current_page_url();
			$query_string = $this->get_query_string( $current_page_url );

			// Set default values.
			if ( empty( $tf_option_value ) ) {
				$tf_option_value = array();
			}


			$ajax_save_class = 'tf-ajax-save';

			if ( ! empty( $this->option_sections ) ) :
				?>
				<div class="tf-setting-dashboard">
					<div class="tf-option-wrapper tf-setting-wrapper">
						<form method="post" action="" class="tf-option-form <?php echo esc_attr( $ajax_save_class ) ?>"
							enctype="multipart/form-data">

							<!-- Body -->
							<div class="tf-option">
								<div class="tf-admin-tab tf-option-nav">
									<?php
									$section_count = 0;
									foreach ( $this->pre_tabs as $key => $section ) :
										$parent_tab_key = ! empty( $section['fields'] ) ? $key : array_key_first( $section['sub_section'] );
										?>
										<div
											class="tf-admin-tab-item<?php echo ! empty( $section['sub_section'] ) ? ' tf-has-submenu' : '' ?>">

											<a href="#<?php echo esc_attr( $parent_tab_key ); ?>"
												class="tf-tablinks <?php echo $section_count == 0 ? 'active' : ''; ?>"
												data-tab="<?php echo esc_attr( $parent_tab_key ) ?>">
												<?php echo ! empty( $section['icon'] ) ? '<span class="tf-sec-icon"><i class="' . esc_attr( $section['icon'] ) . '"></i></span>' : ''; ?>
												<?php echo $section['title']; ?>
											</a>

											<?php if ( ! empty( $section['sub_section'] ) ) : ?>
												<ul class="tf-submenu">
													<?php foreach ( $section['sub_section'] as $sub_key => $sub ) : ?>
														<li>
															<a href="#<?php echo esc_attr( $sub_key ); ?>"
																class="tf-tablinks <?php echo $section_count == 0 ? 'active' : ''; ?>"
																data-tab="<?php echo esc_attr( $sub_key ) ?>">
																<span class="tf-tablinks-inner">
																	<?php echo ! empty( $sub['icon'] ) ? '<span class="tf-sec-icon"><i class="' . esc_attr( $sub['icon'] ) . '"></i></span>' : ''; ?>
																	<?php echo $sub['title']; ?>
																</span>
															</a>
														</li>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>
										</div>
										<?php $section_count++; endforeach; ?>
								</div>

								<div class="tf-tab-wrapper">
									<div class="tf-mobile-setting">
										<a href="#" class="tf-mobile-tabs"><i class="fa-solid fa-bars"></i></a>
									</div>
									<?php
									$content_count = 0;
									foreach ( $this->option_sections as $key => $section ) : ?>
										<div id="<?php echo esc_attr( $key ) ?>"
											class="tf-tab-content <?php echo $content_count == 0 ? 'active' : ''; ?>">

											<?php
											if ( ! empty( $section['fields'] ) ) :
												foreach ( $section['fields'] as $field ) :

													$default = isset( $field['default'] ) ? $field['default'] : '';
													$value = isset( $tf_option_value[ $field['id'] ] ) ? $tf_option_value[ $field['id'] ] : $default;

													$tf_option = new Ins_TF_Options();
													$tf_option->field( $field, $value, $this->option_id );

												endforeach;
											endif; ?>

										</div>
										<?php $content_count++; endforeach; ?>

									<!-- Footer -->
									<div class="tf-option-footer">
										<button type="submit" class="tf-admin-btn tf-btn-secondary tf-submit-btn">
											<?php _e( 'Save', 'instantio' ); ?>
										</button>
									</div>



								</div>
							</div>
							<?php wp_nonce_field( 'tf_option_nonce_action', 'tf_option_nonce' ); ?>
						</form>
					</div>
				</div>
				<?php
			endif;
		}

		/**
		 * Save Options
		 * @author M Hemel Hasan
		 */
		public function save_options() {

			// Add nonce for security and authentication.
			$nonce_name = isset( $_POST['tf_option_nonce'] ) ? $_POST['tf_option_nonce'] : '';
			$nonce_action = 'tf_option_nonce_action';



			// Check if a nonce is set.
			if ( ! isset( $nonce_name ) ) {
				return;
			}

			// Check if a nonce is valid.
			if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
				return;
			}

			$tf_option_value = array();
			$option_request = ( ! empty( $_POST[ $this->option_id ] ) ) ? $_POST[ $this->option_id ] : array();
			if ( ! empty( $option_request ) && ! empty( $this->option_sections ) ) {
				foreach ( $this->option_sections as $section ) {
					if ( ! empty( $section['fields'] ) ) {

						foreach ( $section['fields'] as $field ) {

							if ( ! empty( $field['id'] ) ) {
								$data = isset( $option_request[ $field['id'] ] ) ? $option_request[ $field['id'] ] : '';
								$fieldClass = 'INS_' . $field['type'];
								if ( $fieldClass != 'INS_file' ) {
									$data = $fieldClass == 'INS_repeater' || $fieldClass == 'INS_map' ? serialize( $data ) : $data;
								}
								if ( isset( $_FILES ) && ! empty( $_FILES['file'] ) ) {
									$tf_upload_dir = wp_upload_dir();
									if ( ! empty( $tf_upload_dir['basedir'] ) ) {
										$tf_itinerary_fonts = $tf_upload_dir['basedir'] . '/itinerary-fonts';
										if ( ! file_exists( $tf_itinerary_fonts ) ) {
											wp_mkdir_p( $tf_itinerary_fonts );
										}
										$tf_fonts_extantions = array( 'application/octet-stream' );
										for ( $i = 0; $i < count( $_FILES['file']['name'] ); $i++ ) {
											if ( in_array( $_FILES['file']['type'][ $i ], $tf_fonts_extantions ) ) {
												$tf_font_filename = $_FILES['file']['name'][ $i ];
												move_uploaded_file( $_FILES['file']['tmp_name'][ $i ], $tf_itinerary_fonts . '/' . $tf_font_filename );
											}
										}
									}
								}

								if ( class_exists( $fieldClass ) ) {
									$_field = new $fieldClass( $field, $data, $this->option_id );
									$tf_option_value[ $field['id'] ] = $_field->sanitize();
								}

							}
						}
					}
				}
			}

			if ( ! empty( $tf_option_value ) ) {
				update_option( $this->option_id, $tf_option_value );
			} else {
				delete_option( $this->option_id );
			}
		}


		/*
		 * Ajax Save Options
		 * @author Foysal
		 */
		public function tf_ajax_save_options() {
			$response = [ 
				'status' => 'error',
				'message' => __( 'Something went wrong!', 'instantio' ),
			];

			if ( ! empty( $_POST['tf_option_nonce'] ) && wp_verify_nonce( $_POST['tf_option_nonce'], 'tf_option_nonce_action' ) ) {
				$this->save_options();
				$response = [ 
					'status' => 'success',
					'message' => __( 'Options saved successfully!', 'instantio' ),
				];
			}

			echo json_encode( $response );
			wp_die();
		}

		/*
		 * Get current page url
		 * @return string
		 * @author Foysal
		 */
		public function get_current_page_url() {
			$page_url = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

			return $page_url;
		}

		/*
		 * Get query string from url
		 * @return array
		 * @author Foysal
		 */
		public function get_query_string( $url ) {
			$url_parts = parse_url( $url );
			parse_str( $url_parts['query'], $query_string );

			return $query_string;
		}
	}
}
