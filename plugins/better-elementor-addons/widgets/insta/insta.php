<?php
namespace BetterWidgets\Widgets;

use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.1.0
 */
class Better_Insta extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'better-insta';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Insta Images', 'better-el-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-instagram-post';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
	
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Logo Settings', 'better-el-addons' ),
			]
		);

        $this->add_control(
			'imgbox_list',
			[
				'label' => __( 'Image-box List', 'better-el-addons' ),
				'type' => Controls_Manager::REPEATER,
                'default' => [
					[
						'image' => Utils::get_placeholder_image_src(),
					],
				],
				'fields' => [
                    [
                        'name' => 'image',
                        'label' => __( 'Image', 'better-el-addons' ),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
						'name' => 'image_link',
						'label' => __( 'Image Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Link', 'better-el-addons' ),
					],
                ],
                'title_field' => '{{ name }}',
            ]
        );

        $this->add_control(
			'text',
            [
                'label' => __( 'Button text', 'better-el-addons' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __( 'Text', 'better-el-addons' ),
                'default' => __( 'Follow Us', 'better-el-addons' ),
            ]
        );

        $this->add_control(
			'link',
            [
                'label' => __( 'Button Link', 'better-el-addons' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'placeholder' => __( 'Link', 'better-el-addons' ),
            ]
        );

		$this->end_controls_section();
		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
	    $settings = $this->get_settings(); ?>
	    
	    <div class="better-insta">
	        <div class="container-fluid flex">
	            
	            <?php foreach ($settings['imgbox_list'] as $index => $item) : ?>
	                <div class="img">
	                    <a href="<?php echo esc_url($item['image_link']['url']); ?>">
	                        <img src="<?php echo esc_url($item['image']['url']); ?>" alt="">
	                    </a>
	                    <i class="fab fa-instagram"></i>
	                </div>
	            <?php endforeach; ?>

	            <div class="follow">
	                <a href="<?php echo esc_url($settings['link']['url']); ?>" class="better-btn-skew btn-color btn-bg">
	                    <span><?php echo esc_html($settings['text']); ?></span>
	                    <i></i>
	                </a>
	            </div>

	        </div>
	    </div>
	    
	<?php }


	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function content_template() { }
}


