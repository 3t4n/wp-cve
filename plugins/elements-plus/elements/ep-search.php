<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_Search extends Widget_Base {

		public function get_name() {
			return 'ep-search';
		}

		public function get_title() {
			return __( 'Search Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-search';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		protected function register_controls() {
			$this->start_controls_section(
				'section_title',
				[
					'label' => __( 'Search Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'post_type',
				[
					'label'   => __( 'Post Type', 'elements-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'post',
					'options' => \Elementor\ep_search_post_types(),
				]
			);

			foreach ( \Elementor\ep_search_post_types() as $post_type => $label ) {
				$options = \Elementor\ep_search_post_type_taxonomies_options( $post_type );

				if ( 1 === count( $options ) ) {
					continue;
				}

				$this->add_control(
					$post_type . '_taxonomies',
					[
						'label'     => __( 'Taxonomy', 'elements-plus' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => '',
						'options'   => $options,
						'condition' => array( 'post_type' => $post_type ),
					]
				);

			}

			$this->add_control(
				'num_posts',
				[
					'label'   => __( 'Number of posts', 'elements-plus' ),
					'type'    => Controls_Manager::NUMBER,
					'min'     => 1,
					'max'     => 20,
					'step'    => 1,
					'default' => 5,
				]
			);

			$this->add_control(
				'hr_1',
				[
					'type'  => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);

			$this->add_control(
				'show_button',
				[
					'label'        => __( 'Submit Button', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'elements-plus' ),
					'label_off'    => __( 'Hide', 'elements-plus' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);

			$this->add_control(
				'dropdown_text',
				[
					'label'       => __( 'Dropdown text', 'elements-plus' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Search all items', 'elements-plus' ),
					'placeholder' => __( 'Dropdown text', 'elements-plus' ),
				]
			);

			$this->add_control(
				'button_text',
				[
					'label'       => __( 'Button Text', 'elements-plus' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Search', 'elements-plus' ),
					'placeholder' => __( 'Search Button Text', 'elements-plus' ),
				]
			);

			$this->add_control(
				'placeholder_text',
				[
					'label'       => __( 'Input Placeholder Text', 'elements-plus' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'What are you looking for?', 'elements-plus' ),
					'placeholder' => __( 'Search Box Placeholder Text', 'elements-plus' ),
				]
			);

			$this->add_control(
				'hr_2',
				[
					'type'  => Controls_Manager::DIVIDER,
					'style' => 'thick',
				]
			);

			$this->add_control(
				'show_title',
				[
					'label'        => __( 'Show Post Title', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'elements-plus' ),
					'label_off'    => __( 'Hide', 'elements-plus' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);

			$this->add_control(
				'show_excerpt',
				[
					'label'        => __( 'Show Excerpt', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'elements-plus' ),
					'label_off'    => __( 'Hide', 'elements-plus' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);

			$this->add_control(
				'show_thumb',
				[
					'label'        => __( 'Show Post Thumbnail', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'elements-plus' ),
					'label_off'    => __( 'Hide', 'elements-plus' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				]
			);

			$this->add_control(
				'num_words',
				[
					'label'   => __( 'Excerpt Length (number of words)', 'elements-plus' ),
					'type'    => Controls_Manager::NUMBER,
					'min'     => 1,
					'max'     => 1000,
					'step'    => 1,
					'default' => 55,
				]
			);

			$this->add_control(
				'all_results_text',
				[
					'label'       => __( 'See All Results Text', 'elements-plus' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'See All Results', 'elements-plus' ),
					'placeholder' => __( 'See All Results Text', 'elements-plus' ),
				]
			);

			$this->add_control(
				'no_results_text',
				[
					'label'       => __( 'No Results Text', 'elements-plus' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'No Items Found', 'elements-plus' ),
					'placeholder' => __( 'No Results Text', 'elements-plus' ),
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'input_styles',
				[
					'label' => __( 'Input Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'input_typography',
					'label'    => __( 'Typography', 'elements-plus' ),
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .element-search-input',
				]
			);

			$this->add_control(
				'input_text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => [
						'{{WRAPPER}} .element-search-input' => 'color: {{VALUE}};',
					],
					'scheme'    => [
						'type'  => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_3,
					],
				]
			);

			$this->add_control(
				'input_background_color',
				[
					'label'     => __( 'Background Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_4,
					],
					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} .element-search-input' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'input_box_shadow',
					'selector' => '{{WRAPPER}} .element-search-input',
				]
			);

			$this->add_responsive_control(
				'input_padding',
				[
					'label'      => __( 'Padding', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .element-search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'input_margin',
				[
					'label'      => __( 'Margin', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .element-search-input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'input_border',
					'selector' => '{{WRAPPER}} .element-search-input',
				]
			);

			$this->add_control(
				'input_element_height',
				[
					'label'      => __( 'Element Height', 'elements-plus' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range'      => [
						'px' => [
							'min'  => 10,
							'max'  => 1000,
							'step' => 1,
						],
					],
					'default'    => [
						'unit' => 'px',
						'size' => 40,
					],
					'selectors'  => [
						'{{WRAPPER}} .element-search-form' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'button_styles',
				[
					'label' => __( 'Button Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'button_typography',
					'label'    => __( 'Typography', 'elements-plus' ),
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .element-search-btn',
				]
			);

			$this->add_control(
				'button_text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => [
						'{{WRAPPER}} .element-search-btn' => 'color: {{VALUE}};',
					],
					'scheme'    => [
						'type'  => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_3,
					],
				]
			);

			$this->add_control(
				'button_background_color',
				[
					'label'     => __( 'Background Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_4,
					],
					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} .element-search-btn' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'button_box_shadow',
					'selector' => '{{WRAPPER}} .element-search-btn',
				]
			);

			$this->add_responsive_control(
				'button_padding',
				[
					'label'      => __( 'Padding', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .element-search-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'button_margin',
				[
					'label'      => __( 'Margin', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .element-search-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'button_border',
					'selector' => '{{WRAPPER}} .element-search-btn',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'dropdown_styles',
				[
					'label' => __( 'Dropdown Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'dropdown_typography',
					'label'    => __( 'Typography', 'elements-plus' ),
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .element-search-category-select',
				]
			);

			$this->add_control(
				'dropdown_text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => [
						'{{WRAPPER}} .element-search-category-select' => 'color: {{VALUE}};',
					],
					'scheme'    => [
						'type'  => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_3,
					],
				]
			);

			$this->add_control(
				'dropdown_background_color',
				[
					'label'     => __( 'Background Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_4,
					],
					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} .element-search-category-select' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'dropdown_box_shadow',
					'selector' => '{{WRAPPER}} .element-search-category-select',
				]
			);

			$this->add_responsive_control(
				'dropdown_padding',
				[
					'label'      => __( 'Padding', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .element-search-category-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'dropdown_margin',
				[
					'label'      => __( 'Margin', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .element-search-category-select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'dropdown_border',
					'selector' => '{{WRAPPER}} .element-search-category-select',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'results_wrapper_styles',
				[
					'label' => __( 'Results Wrapper Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'results_wrapper_background_color',
				[
					'label'     => __( 'Background Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_4,
					],
					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} .element-search-results' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'results_wrapper_box_shadow',
					'selector' => '{{WRAPPER}} .element-search-results',
				]
			);

			$this->add_responsive_control(
				'results_wrapper_padding',
				[
					'label'      => __( 'Padding', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .element-search-results' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'results_wrapper_border',
					'selector' => '{{WRAPPER}} .element-search-results',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'results_title_styles',
				[
					'label' => __( 'Results Title Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'results_title_typography',
					'label'    => __( 'Typography', 'elements-plus' ),
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .element-search-results-item-title',
				]
			);

			$this->add_control(
				'results_title_text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => [
						'{{WRAPPER}} .element-search-results-item-title' => 'color: {{VALUE}};',
					],
					'scheme'    => [
						'type'  => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_3,
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'results_excerpt_styles',
				[
					'label' => __( 'Results Excerpt Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'results_excerpt_typography',
					'label'    => __( 'Typography', 'elements-plus' ),
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .element-search-results-item-excerpt',
				]
			);

			$this->add_control(
				'results_excerpt_text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => [
						'{{WRAPPER}} .element-search-results-item-excerpt' => 'color: {{VALUE}};',
					],
					'scheme'    => [
						'type'  => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_3,
					],
				]
			);

			$this->end_controls_section();

		}

		protected function render() {
			$settings         = $this->get_settings();
			$post_type        = $settings['post_type'];
			$taxonomy         = isset( $settings[ $post_type . '_taxonomies' ] ) ? $settings[ $post_type . '_taxonomies' ] : false;
			$num_posts        = $settings['num_posts'];
			$show_button      = $settings['show_button'];
			$button_text      = $settings['button_text'];
			$placeholder_text = $settings['placeholder_text'];
			$show_title       = $settings['show_title'];
			$show_excerpt     = $settings['show_excerpt'];
			$show_thumb       = $settings['show_thumb'];
			$dropdown_text    = $settings['dropdown_text'];
			$all_results_text = $settings['all_results_text'];
			$no_results_text  = $settings['no_results_text'];
			$num_words        = $settings['num_words'];

		?>
			<div class="element-search-form-wrap">
				<form id="element-search-form-<?php echo esc_attr( $this->get_id() ); ?>" class="element-search-form form-ajax-enabled" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" data-no-results="<?php echo esc_attr( $no_results_text ); ?>" data-all-results="<?php echo esc_attr( $all_results_text ); ?>">

					<?php if ( $taxonomy ) : ?>
						<label for="element-search-name" class="sr-only" >
							<?php esc_html_e( 'Term name', 'elements-plus' ); ?>
						</label>
						<?php
							\wp_dropdown_categories( array(
								'taxonomy'          => $taxonomy,
								'show_option_none'  => esc_html( $dropdown_text ),
								'option_none_value' => '',
								'value_field'       => 'slug',
								'hide_empty'        => 1,
								'echo'              => 1,
								'hierarchical'      => 1,
								'name'              => 'term',
								'id'                => 'element-search-category-name',
								'class'             => 'element-search-category-select',
							) );
						?>
					<?php endif; ?>

					<div class="element-search-input-wrap">
						<label for="element-search-input" class="sr-only">
							<?php esc_html_e( 'Search text', 'elements-plus' ); ?>
						</label>
						<input
							type="text"
							class="element-search-input"
							id="element-search-input"
							placeholder="<?php echo esc_attr( $placeholder_text ); ?>"
							name="s"
							autocomplete="off"
						/>

						<span class="element-search-spinner"></span>
						<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ); ?>" />
						<input type="hidden" name="taxonomy" value="<?php echo esc_attr( $taxonomy ); ?>" />
						<input type="hidden" name="num_posts" value="<?php echo intval( $num_posts ); ?>" />
						<input type="hidden" name="num_words" value="<?php echo intval( $num_words ); ?>" />
					</div>
					<?php if ( 'yes' === $show_button ) : ?>
						<button type="submit" class="element-search-btn">
							<span><?php echo esc_html( $button_text ); ?></span>
						</button>
					<?php endif; ?>

					<ul class="element-search-results">
						<li class="element-search-results-item">
							<a href="">
								<?php if ( 'yes' === $show_thumb ) : ?>
									<div class="element-search-results-item-thumb">
										<img src="" alt="<?php esc_attr_e( 'Search result item thumbnail' ); ?>">
									</div>
								<?php endif; ?>
								<div class="element-search-results-item-content">
									<?php if ( 'yes' === $show_title ) : ?>
										<p class="element-search-results-item-title"></p>
									<?php endif; ?>
									<?php if ( 'yes' === $show_excerpt ) : ?>
										<p class="element-search-results-item-excerpt"></p>
									<?php endif; ?>
								</div>
							</a>
						</li>
					</ul>
				</form>
			</div>
		<?php
		}

		protected function content_template() {}

	}

	add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
		$widgets_manager->register( new Widget_Search() );
	} );

	add_action( 'wp_ajax_elements_plus_search', 'Elementor\elements_plus_ajax_search' );
	add_action( 'wp_ajax_nopriv_elements_plus_search', 'Elementor\elements_plus_ajax_search' );
	function elements_plus_ajax_search() {
		$s         = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : ''; // Input var okay.
		$cat       = isset( $_GET['term'] ) ? sanitize_title_for_query( wp_unslash( $_GET['term'] ) ) : false; // Input var okay.
		$post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : 'post'; // Input var okay.
		$taxonomy  = isset( $_GET['post_taxonomy'] ) ? sanitize_text_field( wp_unslash( $_GET['post_taxonomy'] ) ) : ''; // Input var okay.
		$num_posts = isset( $_GET['num_posts'] ) ? intval( $_GET['num_posts'] ) : 5; // Input var okay.
		$num_words = isset( $_GET['num_words'] ) ? intval( $_GET['num_words'] ) : 55; // Input var okay.

		if ( 'any' === $post_type ) {
			$post_type = ep_search_post_types();
			unset( $post_type['any'] );
			$post_type = array_keys( $post_type );
		}

		if ( mb_strlen( $s ) < 3 ) {
			$response = array(
				'error'  => true,
				'errors' => array( 'Search term too short', 'elements-plus' ),
				'data'   => array(),
			);

			wp_send_json( $response );
		}

		$q_args = array(
			'post_type'           => $post_type,
			'posts_per_page'      => $num_posts,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			's'                   => $s,
		);

		$tax_args = array();

		if ( ! empty( $cat ) ) {
			$tax_args = array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => array( $cat ),
				),
			);
		}

		if ( ! empty( $tax_args ) ) {
			$q_args['tax_query'] = $tax_args;
		}

		$q = new \WP_Query( $q_args );

		$response = array(
			'error'  => false,
			'errors' => array(),
			'data'   => array(),
		);

		while ( $q->have_posts() ) {
			$q->the_post();

			$result = array(
				'title'   => html_entity_decode( get_the_title() ),
				'url'     => get_permalink(),
				'excerpt' => html_entity_decode( wp_trim_words( get_the_excerpt(), $num_words ) ),
				'image'   => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
				'found'   => $q->found_posts,
			);

			$response['data'][] = $result;
		}
		wp_reset_postdata();

		wp_send_json( $response );
	}

	function ep_search_post_types() {
		$post_types_any = array(
			'any' => __( 'All Post Types', 'elements-plus' ),
		);

		$post_types = get_post_types( array(
			'public'            => true,
			'show_in_nav_menus' => true,
		), 'object' );

		$excluded_post_types = apply_filters( 'ep_search_excluded_post_types', array( 'elementor_library', 'attachment' ) );

		foreach ( $excluded_post_types as $excluded_post_type ) {
			unset( $post_types[ $excluded_post_type ] );
		}

		$post_types = wp_list_pluck( $post_types, 'label', 'name' );

		$post_types = array_merge( $post_types_any, $post_types );

		return $post_types;
	}

	function ep_search_post_type_taxonomies_options( $post_type ) {

		$taxonomies       = get_object_taxonomies( $post_type, 'objects' );
		$taxonomy_options = array(
			'' => __( 'All Taxonomies', 'elements-plus' ),
		);

		foreach ( $taxonomies as $taxonomy ) {
			if ( false === $taxonomy->show_ui ) {
				continue;
			}

			$taxonomy_options[ $taxonomy->name ] = $taxonomy->labels->singular_name;
		}

		return $taxonomy_options;
	}
