<?php
namespace Elementor;

class Widget_Hotspots extends Widget_Base {

	public function get_name() {
		return 'ep-hotspots';
	}

	public function get_title() {
		return __( 'Hotspots Plus!', 'elements-plus' );
	}

	public function get_icon() {
		return 'ep-icon ep-icon-cta';
	}

	public function get_categories() {
		return [ 'elements-plus' ];
	}

	public function get_script_depends() {
		return [ 'tipso', 'ep-hotspots' ];
	}

	public function get_style_depends() {
		return [ 'tipso', 'ep-hotspots' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Hotspots Plus!', 'elements-plus' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label'   => __( 'Choose Image', 'elements-plus' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'thumb',
				'label'   => __( 'Image Size', 'elements-plus' ),
				'default' => 'full',
				'exclude' => [ 'custom' ],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'marker_type', [
				'label'   => __( 'Type', 'elements-plus' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'label',
				'options' => [
					'label' => __( 'Label', 'elements-plus' ),
					'icon'  => __( 'Icon', 'elements-plus' ),
					'empty' => __( 'Empty', 'elements-plus' ),
				],
			]
		);

		$repeater->add_control(
			'marker_label', [
				'label'       => __( 'Label', 'elements-plus' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Hotspot Label', 'elements-plus' ),
				'default'     => __( '+', 'elements-plus' ),
				'condition'   => [
					'marker_type' => 'label',
				],
			]
		);

		$repeater->add_control(
			'marker_icon_fa5', [
				'label'     => __( 'Icon', 'elements-plus' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'marker_icon',
				'default'   => [
					'value'   => 'fas fa-plus',
					'library' => 'solid',
				],
				'condition' => [
					'marker_type' => 'icon',
				],
			]
		);

		$repeater->add_control(
			'marker_position_x', [
				'label'      => __( 'Hotspot position on the X axis', 'elements-plus' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 0.1,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 0,
				],
			]
		);

		$repeater->add_control(
			'marker_position_y', [
				'label'      => __( 'Hotspot position on the Y axis', 'elements-plus' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 0.1,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 0,
				],
			]
		);

		$repeater->add_control(
			'show_tooltip', [
				'label'        => __( 'Show tooltip', 'elements-plus' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'elements-plus' ),
				'label_off'    => __( 'Hide', 'elements-plus' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$repeater->add_control(
			'tooltip_title', [
				'label'       => __( 'Tooltip title', 'elements-plus' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => __( 'Tooltip title', 'elements-plus' ),
				'default'     => '',
				'condition'   => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'tooltip_text', [
				'label'       => __( 'Tooltip text', 'elements-plus' ),
				'type'        => \Elementor\Controls_Manager::WYSIWYG,
				'default'     => __( 'This is a nice tooltip!', 'elements-plus' ),
				'placeholder' => __( 'Type your tooltip text', 'elements-plus' ),
				'condition'   => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'tooltip_position', [
				'label'     => __( 'Tooltip Position', 'elements-plus' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'options'   => [
					'top'    => __( 'Top', 'elements-plus' ),
					'bottom' => __( 'Bottom', 'elements-plus' ),
					'left'   => __( 'Left', 'elements-plus' ),
					'right'  => __( 'Right', 'elements-plus' ),
				],
				'default'   => 'top',
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'markers',
			[
				'label'  => __( 'Hotspot', 'elements-plus' ),
				'type'   => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'marker_styles',
			[
				'label' => __( 'Hotspot Styles', 'elements-plus' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .ep-marker' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'marker_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .ep-marker',
			]
		);

		$this->add_control(
			'marker_bg_color',
			[
				'label'     => __( 'Background color', 'elements-plus' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .ep-marker' => 'background-color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'marker_size',
			[
				'label'      => __( 'Hotspot size', 'elements-plus' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 250,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .ep-map-item' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};font-size: {{SIZE}}{{UNIT}};padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'popup_styles',
			[
				'label' => __( 'Tooltip Styles', 'elements-plus' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'popup_width',
			[
				'label'      => __( 'Width', 'elements-plus' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 30,
						'max' => 720,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 200,
				],
			]
		);

		$this->add_control(
			'popup_bg_color',
			[
				'label'   => __( 'Background color', 'elements-plus' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => '#FFF',
				'scheme'  => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'popup_text_color',
			[
				'label'   => __( 'Text color', 'elements-plus' ),
				'type'    => \Elementor\Controls_Manager::COLOR,
				'default' => '#000',
				'scheme'  => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_3,
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings  = $this->get_settings();
		$image_url = wp_get_attachment_image_src( $settings['image']['id'], $settings['thumb_size'] )[0];
		$markers   = $settings['markers'];

		?>
		<div class="ep-map">

		<?php
		foreach ( $markers as $marker ) {
			$migrated = isset( $settings['__fa4_migrated']['marker_icon_fa5'] );
			$is_new   = empty( $marker['marker_icon'] );
			?>
				<div class="ep-map-item ep-map-item-<?php echo esc_attr( $marker['_id'] ); ?>"
				style="left:<?php echo intval( $marker['marker_position_x']['size'] ); ?>%;top:<?php echo intval( $marker['marker_position_y']['size'] ); ?>%;"
				data-show-tooltip = "<?php echo esc_attr( $marker['show_tooltip'] ); ?>"
				data-tipso-content='<div class="ep-tipso-content"><?php echo esc_attr( wp_kses_post( $marker['tooltip_text'] ) ); ?></div>'
				data-tipso-background="<?php echo esc_attr( $settings['popup_bg_color'] ); ?>"
				data-tipso-color="<?php echo esc_attr( $settings['popup_text_color'] ); ?>"
				data-tipso-titleColor="<?php echo esc_attr( $settings['popup_text_color'] ); ?>"
				data-tipso-titleBackground="<?php echo esc_attr( $settings['popup_bg_color'] ); ?>"
				data-tipso-titleContent='<h3 class="ep-tipso-title"><?php echo esc_html( $marker['tooltip_title'] ); ?></h3>'
				data-tipso-width="<?php echo esc_attr( $settings['popup_width']['size'] ); ?>"
				data-tipso-position="<?php echo wp_kses_post( $marker['tooltip_position'] ); ?>">

					<span
					class="ep-marker ep-marker-<?php echo esc_attr( $marker['_id'] ); ?>"
					>
						<span class="ep-marker-inner">
							<?php
							if ( 'label' === $marker['marker_type'] ) {
								echo esc_html( $marker['marker_label'] );
							} elseif ( 'icon' === $marker['marker_type'] ) {
								if ( $is_new || $migrated ) {
									Icons_Manager::render_icon( $marker['marker_icon_fa5'], [ 'aria-hidden' => 'true' ] );
								} else {
									echo '<i class="' . esc_attr( $marker['marker_icon'] ) . '" aria-hidden="true"></i>';
								}
							}
							?>
						</span>
					</span>
				</div>
			<?php
		}
		?>

			<img src="<?php echo esc_url_raw( $image_url ); ?>" />
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<#
		var image = {
			id: settings.image.id,
			url: settings.image.url,
			size: settings.thumb_size,
			model: view.getEditModel()
		};
		var image_url = elementor.imagesManager.getImageUrl( image );
		#>

		<div class="ep-map">
			<# _.each( settings.markers, function( marker ) { #>
				<# var iconHTML = elementor.helpers.renderIcon( view, marker.marker_icon_fa5, { 'aria-hidden': true }, 'i' , 'object' ); #>
				<div class="ep-map-item ep-map-item-{{ marker._id }}" style="left:{{marker.marker_position_x.size}}%;top:{{marker.marker_position_y.size}}%;"
				data-show-tooltip = "{{marker.show_tooltip}}"
				data-tipso-content="{{ marker.tooltip_text }}"
				data-tipso-background="{{settings.popup_bg_color}}"
				data-tipso-color="{{settings.popup_text_color}}"
				data-tipso-titleColor="{{settings.popup_text_color}}"
				data-tipso-titleBackground="{{settings.popup_bg_color}}"
				data-tipso-titleContent="<h3>{{marker.tooltip_title}}</h3>"
				data-tipso-width="{{settings.popup_width.size}}"
				data-tipso-position="{{marker.tooltip_position}}">
					<span class="ep-marker ep-marker-{{ marker._id }}">
						<span class="ep-marker-inner">
							<# if ( 'label' === marker.marker_type ) { #>
								{{marker.marker_label}}
							<# } else if ( 'icon' === marker.marker_type ) { #>
								<# if ( iconHTML.rendered && ! marker.icon ) { #>
									{{{ iconHTML.value }}}
								<# } else { #>
									<i class="{{ marker.icon }}" aria-hidden="true"></i>
								<# } #>
							<# } #>
						</span>
							</span>

				</div>
			<# } ); #>

			<# if ( image.url != '' ) { #>
				<img src="{{{ image_url }}}">
			<# } #>
		</div>
		<?php
	}

}

add_action(
	'elementor/widgets/register',
	function ( $widgets_manager ) {
		$widgets_manager->register( new Widget_Hotspots() );
	}
);
