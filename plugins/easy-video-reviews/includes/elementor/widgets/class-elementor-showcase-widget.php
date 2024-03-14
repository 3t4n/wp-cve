<?php

/**
 * Easy Video Reviews - Elementor Widget Class for Showcase
 * Elementor Widget Class for Showcase
 *
 * @package EasyVideoReviews
 */

 namespace EasyVideoReviews\Elementor\Widget;

// Exit if accessed directly.
defined('ABSPATH') || exit(1);

/**
 * Elementor Widget Class for Showcase
 */

if ( ! class_exists( __NAMESPACE__ . '/Showcase') ) {

	/**
	 * Elementor Widget Class for Showcase
	 */
	class Showcase extends \Elementor\Widget_Base {


		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Get widget name.
		 *
		 * Retrieve Elementor widget name.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return string Widget name.
		 */
		public function get_name() {
			return 'evr-showcase';
		}

		/**
		 * Get widget title.
		 *
		 * Retrieve Elementor widget title.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return string Widget title.
		 */
		public function get_title() {
			return __('Easy Video Reviews Showcase', 'easy-video-reviews');
		}

		/**
		 * Get widget icon.
		 *
		 * Retrieve Elementor widget icon.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return string Widget icon.
		 */
		public function get_icon() {
			return 'evr-logo-icon';
		}

		/**
		 * Get widget categories.
		 *
		 * Retrieve the list of categories the Elementor widget belongs to.
		 *
		 * Used to determine where to display the widget in the editor.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return array Widget categories.
		 */
		public function get_categories() {
			return [ 'general' ];
		}

		/**
		 * Get review galeeries
		 *
		 * @return array
		 */
		public function get_galleries() {
			$galleries = get_option('evr_gallaries', []);
			return $galleries;
		}

		/**
		 * Register Elementor widget controls.
		 *
		 * Adds different input fields to allow the user to change and customize the widget settings.
		 *
		 * @since 1.0.0
		 * @access protected
		 */
		protected function register_controls() {

			$evr_galleries = $this->get_galleries();
			$option = $this->option();

			$all_evr_gallery = [];
			foreach ( $evr_galleries as $gallery ) {
				$all_evr_gallery[ (string) $gallery['id'] ] = $gallery['name'];
			}

			$default = $evr_galleries && is_array( $all_evr_gallery ) && count( $all_evr_gallery ) > 0 ? $option->array_first_key($all_evr_gallery) : 'No Gallery Created';

			// Add 'all' option with $all_evr_gallery.
			$show_gallery = $all_evr_gallery;

			$this->start_controls_section(
				'folders_section',
				[
					'label' => esc_html__('Select Gallery', 'easy-video-reviews'),
				]
			);

			$this->add_control(
				'review_galleries',
				[
					'label'    => esc_html__('Review Gallery', 'easy-video-reviews'),
					'type'     => 'select',
					'options'  => $show_gallery,
					'default'  => $default,
					'multiple' => true,
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_content',
				[
					'label' => esc_html__('Showcase', 'easy-video-reviews'),
				]
			);

			// showcase.
			$this->add_control(
				'view',
				[
					'label'   => esc_html__('Showcase View', 'easy-video-reviews'),
					'type'    => 'choose',
					'options' => [
						'grid'   => [
							'title' => esc_html__('Grid View', 'easy-video-reviews'),
							'icon'  => 'dashicons dashicons-editor-table',
						],
						'slider' => [
							'title' => esc_html__('Carousel View', 'easy-video-reviews'),
							'icon'  => 'dashicons dashicons-slides',
						],
					],
					'default' => 'grid',
				]
			);

			$this->add_control(
				'limit',
				[
					'label'   => esc_html__('Video Limit', 'easy-video-reviews'),
					'type'    => 'number',
					'default' => 10,
					'min'     => 1,
					'max'     => 99,
					'condition' => [ 'view' => 'grid' ],
				]
			);

			// columns.
			$this->add_responsive_control(
				'column',
				[
					'label'           => esc_html__('Columns', 'easy-video-reviews'),
					'type'            => 'number',
					'min'             => 1,
					'max'             => 12,
					'desktop_default' => 4,
					'tablet_default'  => 3,
					'mobile_default'  => 2,
					'condition' => [ 'view' => 'grid' ],
				]
			);

			$this->add_control(
				'grid_pagination',
				[
					'label'   => esc_html__('Grid Pagination', 'easy-video-reviews'),
					'type'    => 'switcher',
					'default' => true,
					'condition' => [ 'view' => 'grid' ],
				]
			);

			// $this->add_control(
			//  'rounded',
			//  [
			//      'label'   => esc_html__('Rounded Corners', 'easy-video-reviews'),
			//      'type'    => 'switcher',
			//      'default' => true,
			//  ]
			// );

			$this->add_control(
				'date',
				[
					'label'   => esc_html__('Show Date', 'easy-video-reviews'),
					'type'    => 'switcher',
					'default' => false,
				]
			);

			$this->end_controls_section();

			// $this->start_controls_section(
			//  'carousel_section',
			//  [
			//      'label'     => esc_html__('Carousel', 'easy-video-reviews'),
			//      'condition' => [ 'view' => 'slider' ],
			//  ]
			// );

			// $this->add_control(
			//  'autoplay',
			//  [
			//      'label'   => esc_html__('Enable Autoplay', 'easy-video-reviews'),
			//      'type'    => 'switcher',
			//      'default' => true,
			//  ]
			// );

			// $this->add_control(
			//  'delay',
			//  [
			//      'label'     => esc_html__('Autoplay Delay', 'easy-video-reviews'),
			//      'type'      => 'number',
			//      'default'   => 4000,
			//      'min'       => 0,
			//      'condition' => [ 'autoplay' => 'yes' ],
			//  ]
			// );

			// $this->add_control(
			//  'pagination',
			//  [
			//      'label'   => esc_html__('Enable Pagination', 'easy-video-reviews'),
			//      'type'    => 'switcher',
			//      'default' => false,
			//  ]
			// );

			// $this->add_control(
			//  'navigation',
			//  [
			//      'label'   => esc_html__('Enable Navigation', 'easy-video-reviews'),
			//      'type'    => 'switcher',
			//      'default' => false,
			//  ]
			// );

			// $this->add_control(
			//  'scrollbar',
			//  [
			//      'label'   => esc_html__('Enable Scrollbar', 'easy-video-reviews'),
			//      'type'    => 'switcher',
			//      'default' => false,
			//  ]
			// );

			//$this->end_controls_section();
		}

		/**
		 * Render widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @param array $settings Widget settings.
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render_button_admin( $settings ) {
			$button = '[evr-button align="' . esc_attr($settings['button_alignment']) . '" size="' . esc_attr($settings['button_size']['size']) . '" color="' . esc_html($settings['button_text_color']) . '" background="' . esc_html($settings['button_bg_color']) . '"]' . esc_html($settings['button_label']) . '[/evr-button]';

			echo wp_kses_post(do_shortcode($button));
		}

		/**
		 * Render widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @param array $settings Widget settings.
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render_grid_admin( $settings ) {
			?>
			<div class="evr-reviews relative grid grid-cols-<?php echo esc_html( isset( $settings['column'] ) ? $settings['column'] : 3 ); ?> md:grid-cols-<?php echo esc_html($settings['column']); ?> sm:grid-cols-<?php echo esc_html( isset( $settings['column'] ) ? $settings['column'] : 3  ); ?> gap-<?php echo esc_html(isset( $settings['gap_mobile'] ) ? $settings['gap_mobile'] : 5 ); ?> md:gap-<?php echo esc_html($settings['gap']); ?> sm:gap-<?php echo esc_html(isset($settings['gap_tablet']) ? $settings['gap_tablet'] : 5); ?> md:gap-<?php echo esc_html(isset($settings['gap_tablet']) ? $settings['gap_tablet'] : 5); ?> lg:gap-<?php echo esc_html($settings['gap']); ?>">

				<?php
				for ( $i = 0; $i < 6; $i++ ) {
					$image_id = abs(( $i % 3 ) + 1);
					?>
					<!-- single  -->
					<div class="swiper-slide evr-review">
						<div class="bg-gray-50 overflow-hidden flex flex-col items-center justify-center h-full <?php echo $settings['rounded'] ? 'rounded-md' : ''; ?>">
							<div class="relative ">
								<div class="z-50 w-full h-full cursor-pointer rounded-sm">
									<video class="z-50 h-full w-full cursor-pointer rounded-sm" poster="<?php echo esc_url(EASY_VIDEO_REVIEWS_PUBLIC); ?>/images/person/<?php echo esc_html($image_id); ?>.jpg" src=""></video>
								</div>
								<div class="absolute top-0 left-0 w-full h-full flex items-center justify-center z-50 evr-review-overlay">
									<a data-play="" class="evr-review-play cursor-pointer opacity-50 hover:opacity-70 transition duration-150 text-white" style="color: white">
										<svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-20 w-20" viewBox="0 0 16 16">.
											<path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"></path>
										</svg>
									</a>
									<a style="display:none" data-stop="" class="evr-review-stop cursor-pointer opacity-50 hover:opacity-70 transition duration-150 text-white">
										<svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-20 w-20" viewBox="0 0 16 16">.
											<path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z"></path>
										</svg>
									</a>
								</div>
							</div>

							<?php if ( $settings['date'] ) : ?>
								<div class="w-full bg-gray-100 py-3 px-4 text-gray-400 text-sm flex items-center justify-between">
									<span>3 days ago</span>
									<div class="relative">
										<div></div>
									</div>
								</div>
							<?php endif; ?>

						</div>
					</div>
				<?php } ?>

				<!-- single ends  -->
			</div>
			<?php
		}

		/**
		 * Render widget output on the frontend.
		 *
		 * Written in PHP and used to generate the final HTML.
		 *
		 * @param array $settings Widget settings.
		 * @since 1.0.0
		 * @access protected
		 */
		protected function render_slider_admin( $settings ) {
			?>
			<div class="evr-reviews evr-reviews-swiper relative grid grid-cols-<?php echo esc_html( isset( $settings['column_mobile'] ) ? $settings['column_mobile'] : 3 ); ?> md:grid-cols-<?php echo esc_html($settings['column']); ?> sm:grid-cols-<?php echo esc_html( isset( $settings['column_tablet'] ) ? $settings['column_tablet'] : 3 ); ?> gap-<?php echo esc_html( isset( $settings['gap_mobile'] ) ? $settings['gap_mobile'] : 5 ); ?> md:gap-<?php echo esc_html($settings['gap']); ?> sm:gap-<?php echo esc_html( isset( $settings['gap_tablet'] ) ? $settings['gap_tablet'] : 5 ); ?> md:gap-<?php echo esc_html( isset( $settings['gap_tablet'] ) ? $settings['gap_tablet'] : 5 ); ?> lg:gap-<?php echo esc_html($settings['gap']); ?>">
				<div class="swiper-container">

					<!-- Additional required wrapper -->
					<div class="swiper-wrapper">

						<?php
						for ( $i = 0; $i < 6; $i++ ) {
							$image_id = abs(( $i % 3 ) + 1);
							?>
							<!-- single  -->
							<div class="swiper-slide evr-review">
								<div class="bg-gray-50 overflow-hidden flex flex-col items-center justify-center h-full <?php echo $settings['rounded'] ? 'rounded-md' : ''; ?>">
									<div class="relative ">
										<div class="z-50 w-full h-full cursor-pointer rounded-sm">
											<video class="z-50 h-full w-full cursor-pointer rounded-sm" poster="<?php echo esc_url(EASY_VIDEO_REVIEWS_PUBLIC); ?>/images/person/<?php echo esc_html($image_id); ?>.jpg" src=""></video>
										</div>
										<div class="absolute top-0 left-0 w-full h-full flex items-center justify-center z-50 evr-review-overlay">
											<a data-play="" class="evr-review-play cursor-pointer opacity-50 hover:opacity-70 transition duration-150 text-white" style="color: white">
												<svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-20 w-20" viewBox="0 0 16 16">.
													<path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"></path>
												</svg>
											</a>
											<a style="display:none" data-stop="" class="evr-review-stop cursor-pointer opacity-50 hover:opacity-70 transition duration-150 text-white">
												<svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-20 w-20" viewBox="0 0 16 16">.
													<path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z"></path>
												</svg>
											</a>
										</div>
									</div>

									<?php if ( $settings['date'] ) : ?>
										<div class="w-full bg-gray-100 py-3 px-4 text-gray-400 text-sm flex items-center justify-between">
											<span>3 days ago</span>
											<div class="relative">
												<div></div>
											</div>
										</div>
									<?php endif; ?>

								</div>
							</div>
						<?php } ?>

					</div>

					<!-- If we need pagination -->
					<div class="swiper-pagination"></div>

					<!-- If we need navigation buttons -->
					<div class="swiper-button-prev"></div>
					<div class="swiper-button-next"></div>

					<!-- If we need scrollbar -->
					<div class="swiper-scrollbar"></div>

				</div>
			</div>

			<script>
				(function() {
					let swiperConfig = {
						// autoHeight: true,.
						// centeredSlides: true,.
						navigation: true ? {
							nextEl: ".swiper-button-next",
							prevEl: ".swiper-button-prev",
						} : false,
						observeParents: true,
						parallax: true,
						slidesPerView: '<?php echo esc_html($settings['column']); ?>',
						loop: true,
						autoplay: true,
						simulateTouch: true,
						spaceBetween: Math.ceil(<?php echo esc_html($settings['gap']); ?>),
						pagination: true ? {
							el: ".swiper-pagination",
							clickable: true,
						} : false,
						autoplay: true ? {
							delay: 1000,
							disableOnInteraction: false,
						} : false,
						speed: 1000,
						freeMode: true,
						keyboardControl: true,
						// preventClicks: true,.
						// preventClicksPropagation: true,.
						scrollbar: true ? true : false,
						keyboard: {
							enabled: true,
							onlyInViewport: false,
						},
						breakpoints: {
							// when window width is >= 320px.
							0: {
								slidesPerView: <?php echo esc_html( isset( $settings['column_mobile'] ) ? $settings['column_mobile'] : $settings['column'] ); ?>,
								spaceBetween: <?php echo esc_html( isset( $settings['gap_mobile'] ) ? $settings['gap_mobile'] : $settings['gap'] ); ?>,
							},
							120: {
								slidesPerView: <?php echo esc_html( isset( $settings['column_mobile'] ) ? $settings['column_mobile'] : $settings['column'] ); ?>,
								spaceBetween: Math.ceil(<?php echo esc_html( isset( $settings['gap_mobile'] ) ? $settings['gap_mobile'] : $settings['gap'] ); ?> * 5),
							},
							640: {
								slidesPerView: <?php echo esc_html( isset( $settings['column_tablet'] ) ? $settings['column_tablet'] : $settings['column'] ); ?>,
								spaceBetween: Math.ceil(<?php echo esc_html( isset( $settings['gap_tablet'] ) ? $settings['gap_tablet'] : $settings['gap'] ); ?> * 5),
							},
							992: {
								slidesPerView: <?php echo esc_html( isset( $settings['column'] ) ? $settings['column'] : $settings['column'] ); ?>,
								spaceBetween: Math.ceil(<?php echo esc_html( isset( $settings['gap'] ) ? $settings['gap'] : $settings['gap'] ); ?> * 5),
							},
						},
					};

					setTimeout(function() {
						new Swiper(document.querySelector('.evr-reviews-swiper'), swiperConfig);
					}, 1000)
				})()
			</script>
			<?php
		}


		/**
		 * Render Reviews Grid.
		 *
		 * @param array $settings The widget settings.
		 * @return void
		 */
		protected function render_admin( $settings ) {
			switch ( $settings['view'] ) {

				case 'slider':
					$this->render_slider_admin($settings);
					break;
				case 'grid':
				default:
					$this->render_grid_admin($settings);
					break;
			}
		}

		/**
		 * Render Frontend.
		 *
		 * @param array $settings The widget settings.
		 * @return mixed
		 */
		protected function render_frontend( $settings ) {
			$showcase_attrs = [
				'view'           => esc_attr( isset( $settings['view'] ) ? $settings['view'] : 'grid' ),
				'reviewsids'     => esc_attr( $settings['reviewsids'] ),
				'columns'        => esc_attr( isset( $settings['column']) ? $settings['column'] : 3 ),
				'columns_tablet' => esc_attr( isset( $settings['column_tablet']) ? $settings['column_tablet'] : 2 ),
				'columns_mobile' => esc_attr( isset( $settings['column_mobile']) ? $settings['column_mobile'] : 1 ),
				'gap'            => esc_attr( isset( $settings['gap']) ? $settings['gap'] : 5 ),
				'gap_tablet'     => esc_attr( isset( $settings['gap_tablet']) ? $settings['gap_tablet'] : 5 ),
				'gap_mobile'     => esc_attr( isset( $settings['gap_mobile']) ? $settings['gap_mobile'] : 5 ),
				'limit'          => esc_attr( isset( $settings['limit']) ? $settings['limit'] : 9 ),
				'order'          => esc_attr( isset( $settings['order']) ? $settings['order'] : 'desc' ),
				'autoplay'       => 'yes' === esc_attr( isset( $settings['autoplay']) ? $settings['autoplay'] : 'yes' ),
				'delay'          => esc_attr( isset( $settings['delay']) ? $settings['delay'] : 4000 ),
				'pagination'     => 'yes' === esc_attr( isset( $settings['pagination']) ? $settings['pagination'] : 'yes' ),
				'navigation'     => 'yes' === esc_attr( isset( $settings['navigation']) ? $settings['navigation'] : 'yes' ),
				'scrollbar'      => 'yes' === esc_attr( isset( $settings['scrollbar']) ? $settings['scrollbar'] : 'yes' ),
				'date'           => 'yes' === esc_attr( isset( $settings['date']) ? $settings['date'] : 'yes' ),
				'grid_pagination' => esc_attr( isset( $settings['grid_pagination']) && ! empty($settings['grid_pagination']) ? '1' : '0' ),
				'rounded'        => 'yes' === esc_attr( isset( $settings['rounded']) ? $settings['rounded'] : 'yes' ),
				'folder'         => '',
			];

			$rendered_showcase_attrs = '';

			foreach ( $showcase_attrs as $key => $value ) {
				$rendered_showcase_attrs .= " {$key}=\"{$value}\"";
			}

			$showcase = do_shortcode("[reviews {$rendered_showcase_attrs}][/reviews]");

			$output = $showcase;

			echo wp_kses_post($output);
		}


		/**
		 * Render Reviews Grid.
		 *
		 * @return void
		 */
		protected function render() {
			$settings = $this->get_settings_for_display();

			$current_gallery = $settings['review_galleries'];

			$galleries = $this->get_galleries();

			if ( count($galleries) === 0 ) {
				return;
			}

			$config = array_filter($galleries, function ( $gallery ) use( $current_gallery ) {
				return $gallery['id'] === $current_gallery;
			});

			//var_dump($galleries);
			//var_dump($config);

			$current_gallery_config = array_values($config)[0];

			//var_dump($current_gallery_config);
			//var_dump($settings['grid_pagination']);

			$settings['reviewsids'] = implode( ',', $current_gallery_config['reviews'] );

			if ( is_admin() ) {
				?>
					<div class="msg-verify bg-[#FCE5DB] px-2.5 py-2.5">
					<p class="text-base text-[#754B39] text-center mb-[0px]">To create Gallery please go to <strong><?php echo esc_html('Dashboard'); ?></strong> -> <strong><?php echo esc_html('Easy Video Review'); ?> -> <strong><?php echo esc_html('Gallery'); ?></p>
					</div>
				<?php
				$this->render_admin($settings);
			} else {
				$this->render_frontend($settings);
			}
		}
	}
}
