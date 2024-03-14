<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since      4.0.2
 * Description: Conversios Onboarding page, It's call while active the plugin
 */
if (class_exists('Conversios_Header') === FALSE) {
	class Conversios_Header extends TVC_Admin_Helper
	{
		// Site Url.
		protected $site_url;

		// Conversios site Url.
		protected $conversios_site_url;

		// Subcription Data.
		protected $subscription_data;

		// Plan id.
		protected $plan_id = 1;

		/** Contruct for Hook */
		public function __construct()
		{
			$this->site_url = "admin.php?page=";
			$this->conversios_site_url = $this->get_conversios_site_url();
			$this->subscription_data = $this->get_user_subscription_data();
			if (isset($this->subscription_data->plan_id) === TRUE && !in_array($this->subscription_data->plan_id, array("1"))) {
				$this->plan_id = $this->subscription_data->plan_id;
			}

			add_action('add_conversios_header', [$this, 'before_start_header']);
			add_action('add_conversios_header', array($this, 'header_notices'));
			add_action('add_conversios_header', [$this, 'header_menu']);
		} //end __construct()


		/**
		 * before start header section
		 *
		 * @since    4.1.4
		 * @return void
		 */
		public function before_start_header()
		{
?>
			<div>
			<?php
		}

		/**
		 * header notices section
		 *
		 * @since    4.1.4
		 */
		public function header_notices()
		{
			?>
				<!--- Promotion box start -->
				<div id="conversioshead_notice" class="promobandtop">
					<div class="container-fluid fixedcontainer_conversios">
						<div class="row">
							<div class="promoleft">
								<div class="promobandmsg">
									<?php esc_html_e("Supercharge your ecommerce business with advanced integrations.", "enhanced-e-commerce-for-woocommerce-store"); ?>
									<br>
									<?php esc_html_e("Own your GTM container, Conversions APIs and create quality product feed for more sales.", "enhanced-e-commerce-for-woocommerce-store"); ?>
								</div>
							</div>
							<div class="promoright">
								<div class="prmoupgrdbtn">
									<a target="_blank" href="<?php echo esc_url($this->get_conv_pro_link_adv("top_bar", "all_page", "", "linkonly")); ?>" class="upgradebtn"><?php esc_html_e("Level Up Today!", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--- Promotion box end -->
			<?php
			echo esc_attr($this->call_tvc_site_verified_and_domain_claim());
		}

		/**
		 * header section
		 *
		 * @since    4.1.4
		 */
		public function conversios_header()
		{
			$plan_name = esc_html__("Free Plan", "enhanced-e-commerce-for-woocommerce-store");
			if (isset($this->subscription_data->plan_name) && !in_array($this->subscription_data->plan_id, array("1"))) {
				$plan_name = $this->subscription_data->plan_name;
			}
			?>
				<!-- header start -->
				<header class="header">
					<div class="hedertop">
						<div class="row align-items-center">
							<div class="hdrtpleft">
								<div class="brandlogo">
									<a target="_blank" href="<?php echo esc_url($this->conversios_site_url); ?>"><img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logo.png'); ?>" alt="" /></a>
								</div>
								<div class="hdrcntcbx">
									<?php printf("%s <span><a href=\"mailto:info@conversios.io\">info@conversios.io</a></span>", esc_html_e("For any query, contact us on", "enhanced-e-commerce-for-woocommerce-store")); ?>
								</div>
							</div>
							<div class="hdrtpright">
								<div class="hustleplanbtn">
									<a href="<?php echo esc_url($this->site_url . 'conversios-account'); ?>"><button class="cvrs-btn greenbtn">
											<?php echo esc_attr($plan_name); ?>
										</button></a>
								</div>
							</div>
							<div class="hdrcntcbx mblhdrcntcbx">
								<?php printf("%s <span><a href=\"tel:+1 (415) 968-6313\">+1 (415) 968-6313</a></span>", esc_html_e("For any query, contact us at", "enhanced-e-commerce-for-woocommerce-store")); ?>
							</div>
						</div>
					</div>
				</header>
				<!-- header end -->
				<?php
			}

			/* add active tab class */
			protected function is_active_menu($page = "")
			{
				if ($page !== "" && isset($_GET['page']) === TRUE && sanitize_text_field($_GET['page']) === $page) {
					return "dark";
				}

				return "secondary";
			}
			public function conversios_menu_list()
			{
				$conversios_menu_arr  = array();
				if (is_plugin_active_for_network('woocommerce/woocommerce.php') || in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
					if (!function_exists('is_plugin_active_for_network')) {
						require_once(ABSPATH . '/wp-admin/includes/woocommerce.php');
					}
					if (CONV_APP_ID == 1) {
						$conversios_menu_arr  = array(
							"conversios" => array(
								"page" => "conversios",
								"title" => "Dashboard"
							),
							"conversios-analytics-reports" => array(
								"page" => "conversios-analytics-reports",
								"title" => "Reports & Insights"
							),
							"conversios-google-analytics" => array(
								"page" => "conversios-google-analytics",
								"title" => "Pixels & Analytics"
							),
							"conversios-google-shopping-feed" => array(
								"page" => "conversios-google-shopping-feed&tab=feed_list",
								"title" => "Product Feed"
							),
							"conversios-pmax" => array(
								"page" => "conversios-pmax",
								"title" => "Campaign Management"
							),
							"conversios-pricings" => array(
								"page" => "conversios-pricings",
								"title" => "Free Vs Pro"
							),
						);
					} else {
						$conversios_menu_arr  = array(
							"conversios" => array(
								"page" => "conversios",
								"title" => "Dashboard"
							),
							"conversios-google-shopping-feed" => array(
								"page" => "conversios-google-shopping-feed&tab=feed_list",
								"title" => "Product Feed"
							),
							"conversios-pricings" => array(
								"page" => "conversios-pricings",
								"title" => "Free Vs Pro"
							),
						);
					}
				} else {
					$conversios_menu_arr  = array(
						"conversios" => array(
							"page" => "conversios",
							"title" => "Dashboard"
						),
						"conversios-google-analytics" => array(
							"page" => "conversios-google-analytics",
							"title" => "Pixels & Analytics"
						),
						"conversios-pricings" => array(
							"page" => "conversios-pricings",
							"title" => "Free Vs Pro"
						),
					);
				}


				return apply_filters('conversios_menu_list', $conversios_menu_arr, $conversios_menu_arr);
			}
			/**
			 * header menu section
			 *
			 * @since    4.1.4
			 */
			public function header_menu()
			{
				$menu_list = $this->conversios_menu_list();
				if (!empty($menu_list)) {
				?>
					<header id="conversioshead" class="border-bottom bg-white">
						<div class="container-fluid col-12 p-0">
							<nav class="navbar navbar-expand-lg navbar-light bg-light ps-4" style="height:40px;">
								<div class="container-fluid fixedcontainer_conversios">
									<a class="navbar-brand link-dark fs-16 fw-400">
										<img style="width: 150px;" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logo.png'); ?>" />
									</a>
									<div class="collapse navbar-collapse" id="navbarSupportedContent">
										<ul class="navbar-nav me-auto mb-lg-0">
											<?php
											foreach ($menu_list as $key => $value) {
												if (isset($value['title']) && $value['title']) {
													$is_active = $this->is_active_menu($key);
													$active = $is_active != 'secondary' ? 'rich-blue' : '';
													$menu_url = "#";
													if (isset($value['page']) && $value['page'] != "#") {
														$menu_url = $this->site_url . $value['page'];
													}
													$is_parent_menu = "";
													$is_parent_menu_link = "";
													if (isset($value['sub_menus']) && !empty($value['sub_menus'])) {
														$is_parent_menu = "dropdown";
													}
											?>
													<li class="nav-item fs-14 mt-1 fw-400 <?php echo esc_attr($active); ?> <?php echo esc_attr($is_parent_menu); ?>">
														<?php if ($is_parent_menu == "") { ?>
															<a class="nav-link text-<?php esc_attr($is_active); ?> " aria-current="page" href="<?php echo esc_url($menu_url); ?>">
																<?php echo esc_attr($value['title']); ?>
															</a>
														<?php } else { ?>
															<a class="new-badge nav-link dropdown-toggle text-<?php esc_attr($is_active); ?> " id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
																<?php echo esc_attr($value['title']); ?>
															</a>
															<ul class="dropdown-menu fs-14 fw-400" aria-labelledby="navbarDropdown">
																<?php
																foreach ($value['sub_menus'] as $sub_key => $sub_value) {
																	$sub_menu_url = $this->site_url . $sub_value['page'];
																?>
																	<li>
																		<a class="dropdown-item" href="<?php echo esc_url($sub_menu_url); ?>">
																			<?php echo esc_attr($sub_value['title']); ?>
																		</a>
																	</li>
																<?php }
																?>
															</ul>
														<?php } ?>

													</li>
											<?php
												}
											} ?>
										</ul>
										<div class="d-flex">
											<?php
											$plan_name = esc_html__("Free Plan", "enhanced-e-commerce-for-woocommerce-store");
											$type = 'warning';
											?>
											<a target="_blank" class="fs-12 fw-400 me-4 px-2 py-0 conv-link-blue fw-bold" href="<?php echo esc_url('https://www.conversios.io/docs-category/woocommerce-2/?utm_source=in_app&utm_medium=top_menu&utm_campaign=help_center'); ?>">
												<u><?php esc_html_e("Help Center", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
											</a>
											<button type="button" class="btn btn-<?php echo esc_attr($type) ?> rounded-pill fs-12 fw-400 me-4 px-2 py-0" data-bs-toggle="modal" data-bs-target="#convLicenceInfoMod">
												<?php echo esc_attr($plan_name) ?>
											</button>
										</div>
									</div>
								</div>
							</nav>
						</div>
					</header>
					<div id="loadingbar_blue_header" class="progress-materializecss d-none ps-2 pe-2" style="width:100%">
						<div class="indeterminate"></div>
					</div>

	<?php
				}
			}
		}
	}
	new Conversios_Header();
