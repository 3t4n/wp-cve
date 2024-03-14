<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Better_List_Holder extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'list-holder';
	}
	//script depend
	public function get_script_depends() { return [ 'better-el-addons']; }

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'BETTER List Holder', 'elementor-hello-world' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-text-height';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		// start of the Content tab section
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Heading Title
		$this->add_control(
			'better_list_holder_title',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Main title' ),
			]
		);
		// Heading Title
		$this->add_control(
			'better_list_holder_btn',
			[
				'label' => esc_html__( 'Button text', 'better-el-addons' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Related items' ),
			]
		);


		$this->add_control(
			'item_list',
			[
				'label' => __( 'Item List', 'better_plg' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'title' => 'Title',
						'tag' => 'Tag',
						'price' => '$39',
					],
					[
						'title' => 'Title',
						'tag' => 'Tag',
						'price' => '$39',
					],
					[
						'title' => 'Title',
						'tag' => 'Tag',
						'price' => '$39',
					],
				],
				'fields' => [
					[
						'name' => 'title',
						'label' => __( 'Title', 'better_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Title', 'better_plg' ),
					],
					
					[
						'name' => 'tag',
						'label' => __( 'Tag', 'better_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Tag', 'better_plg' ),
					],
					[
						'name' => 'image',
						'label' => __( 'Client Image', 'better_plg' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'name' => 'price',
						'label' => __( 'Price', 'better_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Price..', 'better_plg' ),
					],
					[
						'name' => 'link',
						'label' => __( 'Link', 'better_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Link..', 'better_plg' ),
					],
				],
				'title_field' => '{{ title }}',
			]
		);
		$this->end_controls_section();
		// end of the Content tab section

	}

	/**
	 * Render about us widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
	    // get our input from the widget settings.
	    $settings = $this->get_settings_for_display();
	?>
	    <div class="better-list-sider">
	        <h4><?php echo esc_html($settings['better_list_holder_title']); ?></h4>
	        <div class="bea-related-dropdown">
	            <div class="bea-related-btn">
	                <span><?php echo esc_html($settings['better_list_holder_btn']); ?></span>
	            </div>
	        </div>
	        <div class="bea-purchase-btn"></div>
	        <div class="bea-side-panel">
	            <div class="bea-side-list-holder">
	                <div class="bea-side-list">
	                    <div class="bea-side-list-inner">
	                        <?php foreach ($settings['item_list'] as $index => $item) : ?>
	                            <a href="<?php echo esc_url($item['link']['url']); ?>" target="_blank">
	                                <div class="item">
	                                    <div class="item-img">
	                                        <img src="<?php echo esc_url($item['image']['url']); ?>" alt="img">
	                                    </div>
	                                    <span class="item-name"><?php echo esc_html($item['title']); ?></span>
	                                    <span class="item-tag"><?php echo esc_html($item['tag']); ?></span>
	                                    <span class="item-price"><?php echo esc_html($item['price']); ?></span>
	                                </div>
	                            </a>
	                        <?php endforeach; ?>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	<?php
	}

}