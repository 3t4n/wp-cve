<?php



use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;
use Elementor\Icons_Manager;




class wpsection_wps_team_Widget extends \Elementor\Widget_Base
{


	public function get_name()
	{
		return 'wpsection_wps_team';
	}

	public function get_title()
	{
		return __('Team', 'wpsection');
	}

	public function get_icon()
	{
		return 'eicon-person';
	}

	public function get_keywords()
	{
		return ['wpsection', 'team'];
	}

	public function get_categories()
	{
		return ['wpsection_category'];
	}


	protected function register_controls()
	{
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__('Team', 'wpsection'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'style',
			[
				'label'   => esc_html__('Choose Different Style', 'wpsection'),
				'label_block' => true,
				'type'    => Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => array(
					'style1' => esc_html__('Choose Style 1', 'wpsection'),
					'style2' => esc_html__('Choose Style 2', 'wpsection'),
					'style3' => esc_html__('Choose Style 3', 'wpsection'),
					'style4' => esc_html__('Choose Style 4', 'wpsection'),
				),
			]
		);
        $this->add_control(
            'sec_class',
            [
                'label'   => esc_html__('Choose Different Style', 'wpsection'),
                'label_block' => true,
                'type'    => Controls_Manager::SELECT,
                'default' => '3',
                'options' => array(
                    '1' => esc_html__('Style 1', 'wpsection'),
                    '2' => esc_html__('Style 2', 'wpsection'),
                    '3' => esc_html__('Style 3', 'wpsection'),
                    '4' => esc_html__('Style 4', 'wpsection'),
                    '5' => esc_html__('Style 5', 'wpsection'),
                    '6' => esc_html__('Style 6', 'wpsection'),


                ),
            ]
        );
		$this->add_control(
			'image',
			[
				'label' => __('Image', 'rashid'),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'style',
							'operator' => '==',
							'value'    => 'style1',
						),
						array(
							'name'     => 'style',
							'operator' => '==',
							'value'    => 'style2',
						),
						array(
							'name'     => 'style',
							'operator' => '==',
							'value'    => 'style3',
						),
						array(
							'name'     => 'style',
							'operator' => '==',
							'value'    => 'style4',
						),
					)
				),
				'type' => Controls_Manager::MEDIA,
				'default' => ['url' => Utils::get_placeholder_image_src(),],
			]
		);
		$this->add_control(
			'title',
			[
				'label'       => __('Name', 'rashid'),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __('Enter your title', 'rashid'),
				'default' => 'Esther Howard',
			]
		);
		$this->add_control(
			'subtitle',
			[
				'label'       => __('Designation', 'rashid'),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => __('Enter your Sub title', 'rashid'),
				'default' => 'Manager',
			]
		);


		$this->add_control(
			'tel_no',
			[
				'label'       => esc_html__('Tel: Number', 'wpsection'),
				'placeholder' => esc_html__('+1401201203', 'wpsection'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'style',
							'operator' => '==',
							'value'    => 'style2',
						),
					)
				),
			]
		);

		$this->add_control(
			'email',
			[
				'label'       => esc_html__('Enter Email Address', 'wpsection'),
				'placeholder' => esc_html__('your@site.com', 'wpsection'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'style',
							'operator' => '==',
							'value'    => 'style2',
						),
					)
				),
			]

		);

		$this->add_control(
			'rating',
			[
				'label'   => esc_html__('Rating', 'wpsection'),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0'    => esc_html__('No Rating', 'wpsection'),
					'1'   => esc_html__('1', 'wpsection'),
					'1.5' => esc_html__('1.5', 'wpsection'),
					'2'   => esc_html__('2', 'wpsection'),
					'2.5' => esc_html__('2.5', 'wpsection'),
					'3'   => esc_html__('3', 'wpsection'),
					'3.5' => esc_html__('3.5', 'wpsection'),
					'4'   => esc_html__('4', 'wpsection'),
					'4.5' => esc_html__('4.5', 'wpsection'),
					'5'   => esc_html__('5', 'wpsection'),
				],
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'style',
							'operator' => '==',
							'value'    => 'style4',
						),
					)
				),
			]
		);

		$this->add_control(
			'review_ctext',
			[
				'label'       => esc_html__('Review Count Text', 'wpsection'),
				'placeholder' => esc_html__('455+ Review', 'wpsection'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'style',
							'operator' => '==',
							'value'    => 'style4',
						),
					)
				),
			]

		);

		$this->add_control(
			'experience',
			[
				'label'       => esc_html__('Experience', 'wpsection'),
				'placeholder' => esc_html__('12+ Years', 'wpsection'),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'style',
							'operator' => '==',
							'value'    => 'style4',
						),
					)
				),
			]

		);

		$this->add_control(
			'_content',
			[
				'label' => esc_html__('Short Description', 'wpsection'),
				'type'  => Controls_Manager::TEXTAREA,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'style',
							'operator' => '==',
							'value'    => 'style2',
						),
					)
				),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'social_link_title',
			[
				'label'   => esc_html__('Title', 'wpsection'),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Facebook',
			]
		);

		$repeater->add_control(
			'social_link',
			[
				'label'   => esc_html__('Link', 'wpsection'),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__('#', 'wpsection'),
			]
		);

		$repeater->add_control(
			'social_share_icon',
			[
				'label'   => esc_html__('Choose Icon', 'wpsection'),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'social_icon',
				'default' => [
					'value' => 'fab fa-facebook-f',
					'library' => 'fa-solid',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'content_section',
			[
				'label' => __('Team Icon', 'wpsection'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'repeat',
			[
				'type' => Controls_Manager::REPEATER,
				'seperator' => 'before',
				'default' =>
				[
					['block_title' => esc_html__('Add ICON', 'wpsection')],
				],
				'fields' =>

				[
					'block_icons' =>
					[
						'name' => 'block_icons',
						'label' => esc_html__('Enter The icons', 'rashid'),
						'type' => Controls_Manager::ICONS,
						'default' => [
                            'value' => 'fas fa-facebook-f',
                            'library' => 'solid',
                        ],
					],
					'block_btnlink' =>
					[
						'name' => 'block_btnlink',
						'label' => __('Button Url', 'rashid'),
						'type' => Controls_Manager::URL,
						'placeholder' => __('https://your-link.com', 'rashid'),
						'show_external' => true,
						'default' => [
							'url' => '',
							'is_external' => true,
							'nofollow' => true,
						],
					],
				]
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__('Style', 'wpsection'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'primary_color',
			[
				'label' => __('primary_color', 'rashid'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpsection-team-5 .wpsection-team-img:after, {{WRAPPER}} .wpsection-team-5 .wpsection-team-social > li > a,
					 {{WRAPPER}} .wpsection-team-6:before, {{WRAPPER}} .wpsection-team-6:after' => 'background-color: {{VALUE}};',
				],
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'style',
							'operator' => '==',
							'value'    => 'style3',
						),
					)
				),
			]
		);
		$this->add_control(
			'secondary_color',
			[
				'label' => __('Color', 'rashid'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'divider_color',
			[
				'label' => __('Color', 'rashid'),
				'type' => \Elementor\Controls_Manager::COLOR,

				'selectors' => [
					'{{WRAPPER}} .wpsection-team .wpsection-team-title-wrap:before' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'style' => ['1'],
				],
			],
		);

		$this->add_control(
			'contact_color',
			[
				'label'     => esc_html__('Email/Phone Color', 'wpsection'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpsection-team .wpsection-team-contact > a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'style' => ['1'],
				],
			]
		);

		$this->add_control(
			'overlay_bg',
			[
				'label'     => esc_html__('Overlay Background', 'wpsection'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpsection-team .wpsection-team-img:before' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'style' => ['1'],
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__('Background', 'wpsection'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .wpsection-team-3:before',
				'condition' => [
					'style' => ['3'],
				],
			]
		);

		$this->add_control(
			'social_primary',
			[
				'label'     => esc_html__('Social Primary', 'wpsection'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpsection-team-social > li > a' => 'color: {{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}} .wpsection-team-social > li > a:hover' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'style' => ['1'],
				],
			]
		);

		$this->add_control(
			'social_color',
			[
				'label'     => esc_html__('Social Color', 'wpsection'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpsection-team-social > li > a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'style' => ['2'],
				],
			]
		);

		$this->add_control(
			'social_hover_color',
			[
				'label'     => esc_html__('Social Hover Color', 'wpsection'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpsection-team-social > li > a:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'style' => ['2'],
				],
			]
		);

		$this->add_control(
			'social_secondary',
			[
				'label'     => esc_html__('Social Secondary', 'wpsection'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpsection-team-social > li > a:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'style' => ['1'],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__('Name', 'wpsection'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__('Color', 'wpsection'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpsection-team .wpsection-team-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography_title',
				'selector' => '{{WRAPPER}} .wpsection-team .wpsection-team-name',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__('Margin', 'wpsection'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .wpsection-team .wpsection-team-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_designation_style',
			[
				'label' => esc_html__('Job Title', 'wpsection'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'designation_color',
			[
				'label'     => esc_html__('Color', 'wpsection'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpsection-team .wpsection-team-designation' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography_designation',
				'selector' => '{{WRAPPER}} .wpsection-team .wpsection-team-designation',
			]
		);

		$this->add_responsive_control(
			'designation_margin',
			[
				'label'      => esc_html__('Margin', 'wpsection'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .wpsection-team .wpsection-team-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_description_style',
			[
				'label' => esc_html__('Description', 'wpsection'),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => '1'
				]
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__('Color', 'wpsection'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpsection-team .wpsection-team-short-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography_desc',
				'selector' => '{{WRAPPER}} .wpsection-team .wpsection-team-short-desc',
			]
		);

		$this->add_responsive_control(
			'desc_margin',
			[
				'label'      => esc_html__('Margin', 'wpsection'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .wpsection-team .wpsection-team-short-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		$allowed_tags = wp_kses_allowed_html('post');

?>

		<?php if ('style1' === $settings['style']) : ?>
			<div id="wpsection-team" class="wpsection-team wpsection-team-<?php echo esc_attr($settings['sec_class']); ?>">

				<div class="wpsection-team-img">
					<?php if (esc_url($settings['image']['id'])) : ?>
						<img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" />
					<?php else : ?>
						<div class="noimage"></div>
					<?php endif; ?>
				</div>

				<div class="wpsection-team-info">
					<h3 class="wpsection-team-name"><?php echo $settings['title']; ?></h3>
					<span class="wpsection-team-designation"><?php echo $settings['subtitle']; ?></span>
					<div class="wpsection-team-social">
						<!-- <?php foreach ($settings['repeat'] as $item) : ?>
						<li>
							<a href="<?php echo esc_url($item['block_btnlink']['url']); ?>">
								<i class="<?php echo str_replace("fa ", "fab ", esc_attr($item['block_icons'])); ?>"></i>
							</a>
						</li>
					<?php endforeach; ?> -->

						<?php foreach ($settings['repeat'] as $item) : ?>
							<li>
								<a href="<?php echo esc_url($item['block_btnlink']['url']); ?>">
									<?php if (is_string($item['block_icons'])) : ?>
										<i class="<?php echo str_replace("fa ", "fa", esc_attr($item['block_icons'])); ?>"></i>
									<?php endif; ?>
								</a>
							</li>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ('style2' === $settings['style']) : ?>

			<div class="wpsection-team wpsection-team-<?php echo esc_attr($settings['sec_class']); ?>">
				<?php if (esc_url($settings['image']['id'])) : ?>
					<div class="wpsection-team-img">
						<?php if (esc_url($settings['image']['id'])) : ?>
							<img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" />
						<?php else : ?>
							<div class="noimage"></div>
						<?php endif; ?>

						<div class="wpsection-team-social">
							<?php foreach ($settings['repeat'] as $item) : ?>
								<li>
									<a href="<?php echo esc_url($item['block_btnlink']['url']); ?>">
										<?php if (is_string($item['block_icons'])) : ?>
											<i class="<?php echo str_replace("fa ", "fa", esc_attr($item['block_icons'])); ?>"></i>
										<?php endif; ?>
									</a>
								</li>
							<?php endforeach; ?>
						</div>

					</div>
				<?php endif; ?>

				<div class="wpsection-team-info">
					<div class="wpsection-team-title-wrap">
						<h3 class="wpsection-team-name"><?php echo $settings['title']; ?></h3>
						<span class="wpsection-team-designation"><?php echo $settings['subtitle']; ?></span>
					</div>
					<div class="wpsection-team-contact">
						<a href="#"><?php echo $settings['tel_no']; ?></a>
						<a href="#"><?php echo $settings['email']; ?></a>
					</div>
					<div class="wpsection-team-short-desc"><?php echo $settings['_content']; ?></div>
				</div>
			</div>
		<?php endif; ?>



		<?php if ('style3' === $settings['style']) : ?>


			<div id="wpsection-team" class="wpsection-team wpsection-team-<?php echo esc_attr($settings['sec_class']); ?>">
				<div class="wpsection-team-img">
				</div>
				<div class="wpsection-team-info">
					<h3 class="wpsection-team-name"><?php echo $settings['title']; ?></h3>
					<span class="wpsection-team-designation"><?php echo $settings['subtitle']; ?></span>
					<div class="wpsection-team-social">
						<?php foreach ($settings['repeat'] as $item) : ?>
							<li>
								<a href="<?php echo esc_url($item['block_btnlink']['url']); ?>">
									<?php if (is_string($item['block_icons'])) : ?>
										<i class="<?php echo str_replace("fa ", "fa", esc_attr($item['block_icons'])); ?>"></i>
									<?php endif; ?>
								</a>
							</li>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ('style4' === $settings['style']) : ?>
			<div id="wpsection-team" class="wpsection-team wpsection-team-<?php echo esc_attr($settings['sec_class']); ?>">
				<div class="wpsection-team-img">
					<?php if (esc_url($settings['image']['id'])) : ?>
						<img src="<?php echo wp_get_attachment_url($settings['image']['id']); ?>" />
					<?php else : ?>
						<div class="noimage"></div>
					<?php endif; ?>
				</div>
				<div class="wpsection-team-info">
					<h3 class="wpsection-team-name"><?php echo $settings['title']; ?></h3>
					<span class="wpsection-team-designation"><?php echo $settings['subtitle']; ?></span>

					<div class="wpsection-team-rating">
						<?php if ('rat1' === $settings['rating']) : ?>
							<i class="fa fa-star"></i>
						<?php endif; ?>
						<?php if ('rat2' === $settings['rating']) : ?>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
						<?php endif; ?>
						<?php if ('rat3' === $settings['rating']) : ?>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
						<?php endif; ?>
						<?php if ('rat4' === $settings['rating']) : ?>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
						<?php endif; ?>
						<?php if ('rat5' === $settings['rating']) : ?>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
						<?php endif; ?>
						<span class="wpsection-team-rating-count"><?php echo $settings['review_ctext']; ?></span>
					</div>
					<div class="wpsection-team-experience"><?php echo $settings['experience']; ?></div>
					<div class="wpsection-team-social">
						<?php foreach ($settings['repeat'] as $item) : ?>
							<li>
								<a href="<?php echo esc_url($item['block_btnlink']['url']); ?>">
									<?php if (is_string($item['block_icons'])) : ?>
										<i class="<?php echo str_replace("fa ", "fa", esc_attr($item['block_icons'])); ?>"></i>
									<?php endif; ?>
								</a>
							</li>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
<?php
	}
}


Plugin::instance()->widgets_manager->register(new \wpsection_wps_team_Widget());
