<?php


class BMGradient_Text extends \Elementor\Widget_Base {

    public function get_title() {
		return esc_html__( 'Gradient Text', 'gradient-text-widget' );
	}

    public function get_icon() {
		return 'eicon-t-letter';
	}

    public function get_custom_help_url() {
		return 'https://blocksmarket.net/widgets/gradient-text-widget-for-elementor/';
	}

    public function get_categories() {
		return [ 'blocks-market' ];
	}

    protected function register_controls() {

        // Content Tab Start

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'gradient-text-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Text', 'gradient-text-widget' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter your text', 'gradient-text-widget' ),
				'default' => esc_html__( 'Gradient Text', 'gradient-text-widget' ),
			
			]
		);


        $this->add_control(
			'alignment',
			[
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'label' => esc_html__( 'Alignment', 'gradient-text-widget' ),
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'gradient-text-widget' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'gradient-text-widget' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'gradient-text-widget' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .gradient-text' => 'text-align: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'html_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'gradient-text-widget' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => [
					'h1' => esc_html__( 'Default', 'gradient-text-widget' ),
					'h1' => esc_html__( 'H1', 'gradient-text-widget' ),
					'h2'  => esc_html__( 'H2', 'gradient-text-widget' ),
					'h3' => esc_html__( 'H3', 'gradient-text-widget' ),
					'h4' => esc_html__( 'H4', 'gradient-text-widget' ),
					'h5' => esc_html__( 'H5', 'gradient-text-widget' ),
					'h6' => esc_html__( 'H6', 'gradient-text-widget' ),
					'div' => esc_html__( 'div', 'gradient-text-widget' ),
					'span' => esc_html__( 'span', 'gradient-text-widget' ),
					'p' => esc_html__( 'p', 'gradient-text-widget' ),
				],
			]
		);

		$this->add_control(
			'website_link',
			[
				'label' => esc_html__( 'Link', 'gradient-text-widget' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'gradient-text-widget' ),
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					// 'custom_attributes' => '',
				],
				'label_block' => true,
			]
		);


		$this->end_controls_section();

        // Content Tab End



        // Style Tab Start

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'gradient-text-widget' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => ['gradient'],
				'default' => '#f00',
				'selector' => '{{WRAPPER}} .gradient-text',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .gradient-text',
			]
		);

		$this->end_controls_section();

        // Style Tab End

	}

    
    
	public function get_name() {
		return 'gradient_heading';
	}
    


    protected function render() {
		$settings = $this->get_settings_for_display();
	
	?>

		<?php if($settings['website_link']['url']): ?>

            <a <?php echo esc_url($this->get_render_attribute_string( 'website_link' )); ?>>
                <<?php echo esc_html($settings['html_tag']); ?> class="gradient-text">
                    <?php echo esc_html($settings['title']); ?>
                </<?php echo esc_html($settings['html_tag']); ?>>
            </a>

		<?php else: ?>

            <<?php echo esc_html($settings['html_tag']); ?> class="gradient-text">
                <?php echo esc_html($settings['title']); ?>
            </<?php echo esc_html($settings['html_tag']); ?>>
		
		<?php endif; ?>
	<?php
    
	}

	protected function content_template() {
		?>
		<# if (settings['website_link']['url']) {  #>

		<{{{ settings.html_tag }}} class="gradient-text">
			{{{ settings.title }}}
		</{{{ settings.html_tag }}}>

		<# } else { #>

		<a href="{{ settings.website_link.url }}">
		<{{{ settings.html_tag }}} class="gradient-text">
			{{{ settings.title }}}
		</{{{ settings.html_tag }}}>
		</a>
		
		<# } #>
		
		<?php
	}

}