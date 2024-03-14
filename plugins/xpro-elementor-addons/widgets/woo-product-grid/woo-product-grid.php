<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 0.1.8
 */
class Woo_Product_Grid extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 *
	 * @return string Widget name.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-woo-product-grid';
	}

	/**
	 * Get widget title.
	 *
	 *
	 * @return string Widget title.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Woo Product Grid', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 *
	 * @return string Widget icon.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-post-grid xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'woo', 'product', 'grid', 'blog', 'posts', 'query' );
	}

	/**
	 * Retrieve the list of style the widget depended on.
	 *
	 * Used to set style dependencies required to run the widget.
	 *
	 * @return array Widget style dependencies.
	 * @since 0.1.8
	 *
	 * @access public
	 *
	 */
	public function get_style_depends() {

		return array( 'cubeportfolio', 'animate' );
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 0.1.8
	 *
	 * @access public
	 *
	 */
	public function get_script_depends() {
		return array( 'cubeportfolio', 'wc-add-to-cart-variation' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 0.1.8
	 * @access protected
	 */
	protected function register_controls() {
		$post_types['product']        = __( 'Product', 'xpro-elementor-addons' );
		$post_types['by_id']          = __( 'Manual Selection', 'xpro-elementor-addons' );
		$post_types['source_dynamic'] = __( 'Dynamic', 'xpro-elementor-addons' );

		$taxonomies = get_taxonomies( array(), 'objects' );

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		//notice
		if ( ! class_exists( '\WooCommerce' ) ) {
			$this->add_control(
				'woo_missing_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
					/* translators: %s: Title */
						__( 'Looks like %1$s is missing in your site. Please click on the link below and install/activate %1$s. Make sure to refresh this page after installation or activation.', 'xpro-elementor-addons' ),
						'<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) )
						. '" target="_blank" rel="noopener">Woocommerce Plugin</a>'
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
				)
			);

			$this->add_control(
				'woo_install',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '" target="_blank" rel="noopener">Click to install or activate Woocommerce Plugin</a>',
				)
			);
			$this->end_controls_section();

			return;
		}

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'Layout', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => array(
					'1'  => __( 'Layout 1', 'xpro-elementor-addons' ),
					'2'  => __( 'Layout 2', 'xpro-elementor-addons' ),
					'3'  => __( 'Layout 3', 'xpro-elementor-addons' ),
					'4'  => __( 'Layout 4', 'xpro-elementor-addons' ),
					'5'  => __( 'Layout 5', 'xpro-elementor-addons' ),
					'6'  => __( 'Layout 6', 'xpro-elementor-addons' ),
					'7'  => __( 'Layout 7', 'xpro-elementor-addons' ),
					'8'  => __( 'Layout 8', 'xpro-elementor-addons' ),
					'9'  => __( 'Layout 9', 'xpro-elementor-addons' ),
					'10' => __( 'Layout 10', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_responsive_control(
			'column_grid',
			array(
				'label'              => __( 'Columns', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'desktop_default'    => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => array(
					'1' => __( '1', 'xpro-elementor-addons' ),
					'2' => __( '2', 'xpro-elementor-addons' ),
					'3' => __( '3', 'xpro-elementor-addons' ),
					'4' => __( '4', 'xpro-elementor-addons' ),
					'5' => __( '5', 'xpro-elementor-addons' ),
					'6' => __( '6', 'xpro-elementor-addons' ),
				),
				'render_type'        => 'template',
				'frontend_available' => true,
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'thumbnail',
				'exclude' => array( 'custom' ),
				'default' => 'medium',
			)
		);

		//show category
		$this->add_control(
			'show_category',
			array(
				'label'        => __( 'Show Category', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		//show title
		$this->add_control(
			'show_title',
			array(
				'label'        => __( 'Show Title', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		//show content
		$this->add_control(
			'show_content',
			array(
				'label'        => __( 'Show Description', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		//content length
		$this->add_control(
			'content_length',
			array(
				'label'     => __( 'Description Length', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 500,
				'step'      => 5,
				'default'   => 10,
				'condition' => array(
					'show_content' => 'yes',
				),
			)
		);

		//show rating
		$this->add_control(
			'show_rating',
			array(
				'label'        => __( 'Show Rating', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		//show price
		$this->add_control(
			'show_price',
			array(
				'label'        => __( 'Show Price', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		//show qv actions
		$this->add_control(
			'show_qv_action',
			array(
				'label'        => __( 'Show Actions', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		//show qv button
		$this->add_control(
			'show_qv_icon',
			array(
				'label'        => __( 'Show Quick View Icon', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_qv_action' => 'yes',
				),
			)
		);

		//show qv button
		$this->add_control(
			'show_cart_icon',
			array(
				'label'        => __( 'Show Cart Icon', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_qv_action' => 'yes',
				),
			)
		);

		//show cta button
		$this->add_control(
			'show_cta',
			array(
				'label'        => __( 'Show CTA Button', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->end_controls_section();

		//Query
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'post_type',
			array(
				'label'   => __( 'Source', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $post_types,
				'default' => key( $post_types ),
			)
		);

		$this->add_control(
			'posts_ids',
			array(
				'label'       => __( 'Search & Select', 'xpro-elementor-addons' ),
				'type'        => 'xpro-select',
				'options'     => xpro_elementor_get_query_post_list(),
				'label_block' => true,
				'multiple'    => true,
				'source_name' => 'post_type',
				'source_type' => 'any',
				'condition'   => array(
					'post_type' => 'by_id',
				),
			)
		);

		$this->add_control(
			'authors',
			array(
				'label'       => __( 'Author', 'xpro-elementor-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'default'     => array(),
				'options'     => xpro_elementor_get_authors_list(),
				'condition'   => array(
					'post_type!' => array( 'by_id', 'source_dynamic' ),
				),
			)
		);

		$this->add_control(
			'terms',
			array(
				'label'     => __( 'Term', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_taxonomies(),
				'default'   => '',
				'condition' => array(
					'post_type' => array( 'source_dynamic' ),
				),
			)
		);

		foreach ( $taxonomies as $taxonomy => $object ) {
			if ( ! isset( $object->object_type[0] ) || ! in_array( $object->object_type[0], array_keys( $post_types ), true ) ) {
				continue;
			}

			$this->add_control(
				$taxonomy . '_ids',
				array(
					'label'       => $object->label,
					'type'        => 'xpro-select',
					'label_block' => true,
					'multiple'    => true,
					'source_name' => 'taxonomy',
					'source_type' => $taxonomy,
					'condition'   => array(
						'post_type' => $object->object_type,
					),
				)
			);
		}

		$this->add_control(
			'post__not_in',
			array(
				'label'       => __( 'Exclude', 'xpro-elementor-addons' ),
				'type'        => 'xpro-select',
				'label_block' => true,
				'multiple'    => true,
				'source_name' => 'post_type',
				'source_type' => 'any',
				'condition'   => array(
					'post_type!' => array( 'by_id', 'source_dynamic' ),
				),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'     => __( 'Per Page', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 3,
				'condition' => array(
					'post_type!' => array( 'source_dynamic' ),
				),
			)
		);

		$this->add_control(
			'offset',
			array(
				'label'     => __( 'Offset', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'condition' => array(
					'orderby!'         => 'rand',
					'show_pagination!' => 'yes',
				),
			)
		);

		$this->add_control(
			'filter_by',
			array(
				'label'     => __( 'Filter By', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''             => __( 'None', 'xpro-elementor-addons' ),
					'featured'     => __( 'Featured', 'xpro-elementor-addons' ),
					'sale'         => __( 'Sale', 'xpro-elementor-addons' ),
					'top_rated'    => __( 'Top Rated', 'xpro-elementor-addons' ),
					'best_selling' => __( 'Best Selling', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'post_type!' => array( 'by_id', 'source_dynamic' ),
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'Order By', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => xpro_elementor_get_post_orderby_options(),
				'default' => 'date',

			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'Order', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'asc'  => 'Ascending',
					'desc' => 'Descending',
				),
				'default' => 'desc',

			)
		);

		$this->add_control(
			'post_only_image',
			array(
				'label'        => __( 'Post With Image', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		//quick view
		$this->start_controls_section(
			'qv_section',
			array(
				'label'     => __( 'Quick View', 'xpro-elementor-addons' ),
				'condition' => array(
					'show_qv_action' => 'yes',
					'show_qv_icon'   => 'yes',
				),
			)
		);

		$this->add_control(
			'qv_layout',
			array(
				'label'     => __( 'Layout', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => array(
					'1' => __( 'Layout 1', 'xpro-elementor-addons' ),
					'2' => __( 'Layout 2', 'xpro-elementor-addons' ),
					'3' => __( 'Layout 3', 'xpro-elementor-addons' ),
					'4' => __( 'Layout 4', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'show_qv_action' => 'yes',
				),
			)
		);

		$this->add_control(
			'qv_animation',
			array(
				'label'              => esc_html__( 'Animation', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::ANIMATION,
				'render_type'        => 'template',
				'frontend_available' => true,
				'default'            => 'fadeInUp',
				'condition'          => array(
					'show_qv_action' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		//badges
		$this->start_controls_section(
			'badges_section',
			array(
				'label' => __( 'Badges', 'xpro-elementor-addons' ),
			)
		);

		//show badges
		$this->add_control(
			'show_badges',
			array(
				'label'        => __( 'Show Badges', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		//badges style
		$this->add_responsive_control(
			'woo_badges_style',
			array(
				'label'                => __( 'Style', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::SELECT,
				'options'              => array(
					'square' => __( 'Square', 'xpro-elementor-addons' ),
					'circle' => __( 'Circle', 'xpro-elementor-addons' ),
				),
				'default'              => 'square',
				'condition'            => array(
					'show_badges' => 'yes',
				),

				'selectors_dictionary' => array(
					'circle' => '    width: 50px;height: 50px;display: flex;justify-content: center;align-items: center; border-radius: 100%',
				),

				'selectors'            => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-sale-flash-btn, 
					{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn
					' => '{{VALUE}};',
				),

			)
		);

		//badges direction
		$this->add_responsive_control(
			'badges_direction',
			array(
				'label'                => __( 'Direction', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::SELECT,
				'options'              => array(
					'column' => __( 'Column', 'xpro-elementor-addons' ),
					'row'    => __( 'Row', 'xpro-elementor-addons' ),
				),
				'default'              => 'column',
				'condition'            => array(
					'show_badges' => 'yes',
				),

				'selectors_dictionary' => array(
					'row' => 'display: flex; flex-direction: row; justify-content: stretch; align-items: baseline;',
				),

				'selectors'            => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-product-grid-badges-innner-wrapper' => '{{VALUE}};',
				),

			)
		);

		//sale badge type
		$this->add_responsive_control(
			'sale_badge_type',
			array(
				'label'     => __( 'Sale Badge Type', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'text'       => __( 'Text', 'xpro-elementor-addons' ),
					'percentage' => __( 'Percentage', 'xpro-elementor-addons' ),
				),
				'default'   => 'text',
				'condition' => array(
					'show_badges' => 'yes',
				),
			)
		);

		//show sale badge
		$this->add_control(
			'show_sale_badge',
			array(
				'label'        => __( 'Show Sale Badge', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
				'condition'    => array(
					'show_badges' => 'yes',
				),
			)
		);

		//show featured badge
		$this->add_control(
			'show_featured_badge',
			array(
				'label'        => __( 'Show Featured Badge', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'show_badges' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		//Pagination
		$this->start_controls_section(
			'section_pagination',
			array(
				'label' => __( 'Pagination', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'show_pagination',
			array(
				'label'        => __( 'Show Pagination', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'prev_label',
			array(
				'label'     => __( 'Prev Label', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Prev', 'xpro-elementor-addons' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'show_pagination' => 'yes',
				),
			)
		);

		$this->add_control(
			'next_label',
			array(
				'label'     => __( 'Next Label', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Next', 'xpro-elementor-addons' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'show_pagination' => 'yes',
				),
			)
		);

		$this->add_control(
			'arrow',
			array(
				'label'     => __( 'Arrows Type', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'fas fa-arrow-left'          => __( 'Arrow', 'xpro-elementor-addons' ),
					'fas fa-angle-left'          => __( 'Angle', 'xpro-elementor-addons' ),
					'fas fa-angle-double-left'   => __( 'Double Angle', 'xpro-elementor-addons' ),
					'fas fa-chevron-left'        => __( 'Chevron', 'xpro-elementor-addons' ),
					'fas fa-chevron-circle-left' => __( 'Chevron Circle', 'xpro-elementor-addons' ),
					'fas fa-caret-left'          => __( 'Caret', 'xpro-elementor-addons' ),
					'xi xi-long-arrow-left'      => __( 'Long Arrow', 'xpro-elementor-addons' ),
					'fas fa-arrow-circle-left'   => __( 'Arrow Circle', 'xpro-elementor-addons' ),
				),
				'default'   => 'fas fa-arrow-left',
				'condition' => array(
					'show_pagination' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		//Styling Tab
		$this->start_controls_section(
			'section_general_style',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'              => __( 'Image Height', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px', 'vh', '%' ),
				'range'              => array(
					'px' => array(
						'min' => 10,
						'max' => 1200,
					),
					'vh' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
				'selectors'          => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-img' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'object-fit',
			array(
				'label'     => __( 'Object Fit', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'image_height[size]!' => '',
				),
				'options'   => array(
					''        => __( 'Default', 'xpro-elementor-addons' ),
					'fill'    => __( 'Fill', 'xpro-elementor-addons' ),
					'cover'   => __( 'Cover', 'xpro-elementor-addons' ),
					'contain' => __( 'Contain', 'xpro-elementor-addons' ),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'space_between',
			array(
				'label'              => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'default'            => array(
					'size' => 15,
				),
				'tablet_default'     => array(
					'size' => 15,
				),
				'mobile_default'     => array(
					'size' => 15,
				),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 500,
					),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'item_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-item',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'item_box_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-item',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'item_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-item',
			)
		);

		$this->add_responsive_control(
			'item_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-item' => 'overflow:hidden; border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'overlay_style_tabs'
		);

		$this->start_controls_tab(
			'overlay_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'     => __( 'Overlay Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-img-section::after' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'overlay_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'overlay_hover_color',
			array(
				'label'     => __( 'Overlay Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-img:hover .xpro-woo-product-img-section::after' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		//Content
		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => __( 'Content', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'                => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class'         => 'xpro-product-grid-align%s-',

				'selectors_dictionary' => array(
					'left'   => 'justify-content: left; align-items: center; text-align:left;',
					'center' => 'justify-content: center; align-items: center; text-align:center;',
					'right'  => 'justify-content: right; align-items: center; text-align:right',
				),

				'selectors'            => array(
					'{{WRAPPER}} .xpro-woo-product-grid-content-sec, {{WRAPPER}} .xpro-woo-product-grid-star-rating-wrapper' => '{{VALUE}};',
				),

			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'content_background',
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .xpro-woo-product-grid-content-sec',
				'condition' => array(
					'layout!' => array( '5' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'content_box_shadow',
				'label'     => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec',
				'condition' => array(
					'layout!' => array( '5' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'content_border',
				'selector'  => '{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec',
				'condition' => array(
					'layout!' => array( '5' ),
				),
			)
		);

		$this->add_responsive_control(
			'content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout!' => array( '5' ),
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		//category
		// ---
		$this->add_control(
			'category_title',
			array(
				'label'     => __( 'Category', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_category' => array( 'yes' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'category_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-product-grid-wrapper .xpro_elementor_category_term_name',
				'condition' => array(
					'show_category' => array( 'yes' ),
				),
			)
		);

		$this->add_control(
			'category_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro_elementor_category_term_name' => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-woo-product-grid-category-wrapper::before'               => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_category' => array( 'yes' ),
				),
			)
		);

		$this->add_control(
			'category_hover_color',
			array(
				'label'     => __( 'Hover Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro_elementor_category_term_name:hover'                                                 => 'color: {{VALUE}}',
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro_elementor_category_term_name:hover .xpro-woo-product-grid-category-wrapper::before' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_category' => array( 'yes' ),
				),
			)
		);

		$this->add_responsive_control(
			'category_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-category-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_category' => array( 'yes' ),
				),
			)
		);
		// ---

		// ---
		$this->add_control(
			'heading_title',
			array(
				'label'     => __( 'Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_title' => array( 'yes' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-title',
				'condition' => array(
					'show_title' => array( 'yes' ),
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-title' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_title' => array( 'yes' ),
				),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => __( 'Hover Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-title:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_title' => array( 'yes' ),
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_title' => array( 'yes' ),
				),
			)
		);
		// ---

		$this->add_control(
			'desc_excerpt',
			array(
				'label'     => __( 'Description', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_content' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'description_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt',
				'condition' => array(
					'show_content' => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_content' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'excerpt_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_content' => 'yes',
				),
			)
		);

		//rating
		// ---
		$this->add_control(
			'rating_title',
			array(
				'label'     => __( 'Rating', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_rating' => 'yes',
				),
			)
		);

		//rating size
		$this->add_responsive_control(
			'rating_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-star-rating-wrapper .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'show_rating' => 'yes',
				),
			)
		);

		$this->add_control(
			'rating_front_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .star-rating span::before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'rating_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .star-rating::before' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_rating' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'rating_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-star-rating-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_rating' => 'yes',
				),
			)
		);
		// ---

		//price
		$this->add_control(
			'price_title',
			array(
				'label'     => __( 'Price', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_price' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'price_typography',
				'label'     => __( 'Price Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-woo-product-grid-price-wrapper .price',
				'condition' => array(
					'show_price' => 'yes',
				),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Price Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-woo-product-grid-price-wrapper .price' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_price' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'space_between_price',
			array(
				'label'      => __( 'Space Between Sale Price', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-woo-product-grid-price-wrapper ins' => 'padding-left: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'show_price' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'sale_typography',
				'label'     => __( 'Sale Typography', 'xpro-elementor-addons' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .xpro-woo-product-grid-price-wrapper del .woocommerce-Price-amount',
				'condition' => array(
					'show_price' => 'yes',
				),
			)
		);

		$this->add_control(
			'sale_price_color',
			array(
				'label'     => __( 'Sale Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-woo-product-grid-price-wrapper del, {{WRAPPER}} .xpro-woo-product-grid-price-wrapper del .woocommerce-Price-amount' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_price' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'price_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-wrapper .xpro-woo-product-grid-price-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_price' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'qv_action_style',
			array(
				'label'     => __( 'Actions', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_qv_action' => 'yes',
				),
			)
		);

		//icons size
		$this->add_responsive_control(
			'icons_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-cta-btn i,
					{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-qv-cart-btn .button::before' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'icons_bg_size',
			array(
				'label'      => esc_html__( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hv-qv-btn.xpro-cta-btn,
					 {{WRAPPER}} .xpro-hv-cart-btn.xpro-cta-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icons_space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-hv-cta-section' => 'grid-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'actions_style_tabs'
		);

		$this->start_controls_tab(
			'actions_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'qv_icons_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-cta-btn i,
					{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-qv-cart-btn .button::before
					' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'qv_icons_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-cta-btn',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'actions_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'qv_icons_hcolor',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-cta-btn:hover i,
					{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-qv-cart-btn:hover .button::before
					' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'qv_icons_hbackground',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-cta-btn:hover',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'qv_icons_btns_border',
				'selector' => '{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-cta-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'qv_icons_btns_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-cta-btn',
			)
		);

		$this->add_responsive_control(
			'qv_icons_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-cta-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		//margin
		$this->add_responsive_control(
			'qv_icons_btn_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-product-grid-hv-cta-section .xpro-cta-btn' => 'Margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'qv_style',
			array(
				'label'     => __( 'Popup Content', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_qv_action' => 'yes',
					'show_qv_icon'   => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'qv_main_content_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .xpro-qv-popup-inner',
			)
		);

		//overlay color
		$this->add_control(
			'qv_overlay_color',
			array(
				'label'     => __( 'Overlay Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-qv-popup-overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'qv_main_content_border',
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .xpro-qv-popup-inner',
			)
		);

		$this->add_responsive_control(
			'qv_main_content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-qv-popup-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_main_content_padding',
			array(
				'label'      => __( 'Content Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-woo-qv-content-sec' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_main_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-qv-popup-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// ---

		$this->add_control(
			'qv_sku_title',
			array(
				'label'     => __( 'SKU', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'qv_meta_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .sku_wrapper',
			)
		);

		$this->add_control(
			'qv_sku_color',
			array(
				'label'     => __( 'Heading Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .sku_wrapper' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'qv_sku_title_color',
			array(
				'label'     => __( 'Title Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .sku_wrapper .sku' => 'color: {{VALUE}};',
				),
			)
		);

		// ----
		// Taxonomy

		$this->add_control(
			'qv_tax_title',
			array(
				'label'     => __( 'Taxonomy', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'qv_tax_typography',
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .product_meta .posted_in',
			)
		);

		$this->add_control(
			'qv_tax_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .product_meta .posted_in' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'qv_tax_link_color',
			array(
				'label'     => __( 'Link Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .product_meta .posted_in a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'qv_tax_link_hv_color',
			array(
				'label'     => __( 'Link Hover', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .product_meta .posted_in a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'qv_tax_seprator_color',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-woo-qv-content-sec .sku_wrapper' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_seprator_size',
			array(
				'label'      => __( 'Separator Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
					'vh' => array(
						'min' => 0,
						'max' => 20,
					),
					'%'  => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-woo-qv-content-sec .sku_wrapper' => 'border-width: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'qv_sku_background',
				'label'    => esc_html__( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .product_meta',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'qv_meta_border',
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .product_meta',
			)
		);

		$this->add_responsive_control(
			'qv_meta_link_padding',
			array(
				'label'      => __( 'Link Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .product_meta .posted_in a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_meta_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .product_meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_meta_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .product_meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		// ---

		// ---
		$this->add_control(
			'qv_heading_title',
			array(
				'label'     => __( 'Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'qv_title_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .product_title',
			)
		);

		$this->add_control(
			'qv_title_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .product_title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'qv_title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .product_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		// ---

		$this->add_control(
			'qv_desc_style',
			array(
				'label'     => __( 'Description', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'qv_description_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .woocommerce-product-details__short-description',
			)
		);

		$this->add_control(
			'qv_desc_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .woocommerce-product-details__short-description' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'qv_desc_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .woocommerce-product-details__short-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_desc_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .woocommerce-product-details__short-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		//rating
		// ---
		$this->add_control(
			'qv_rating_title',
			array(
				'label'     => __( 'Rating', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'qv_rating_txt_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .woocommerce-review-link' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'qv_rating_txt_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .woocommerce-review-link',
			)
		);

		//rating size
		$this->add_responsive_control(
			'qv_rating_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'qv_rating_front_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .star-rating span::before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'qv_rating_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .star-rating::before' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'qv_rating_txt_margin',
			array(
				'label'      => __( 'Text Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .woocommerce-review-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_rating_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .woocommerce-product-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		//price
		$this->add_control(
			'qv_price_title',
			array(
				'label'     => __( 'Price', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'qv_price_typography',
				'label'    => __( 'Price Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .price',
			)
		);

		$this->add_control(
			'qv_price_color',
			array(
				'label'     => __( 'Price Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .price' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_space_between_price',
			array(
				'label'      => __( 'Space Between Sale Price', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .price ins' => 'padding-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'qv_sale_typography',
				'label'     => __( 'Sale Typography', 'xpro-elementor-addons' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .xpro-qv-main-wrapper .price del .woocommerce-Price-amount',
			)
		);

		$this->add_control(
			'qv_sale_price_color',
			array(
				'label'     => __( 'Sale Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .price del .woocommerce-Price-amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_price_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'qv_section_button_style',
			array(
				'label'     => __( 'Popup Buttons', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_qv_action' => 'yes',
					'show_qv_icon'   => 'yes',
				),
			)
		);

		$this->add_control(
			'qv_close_title',
			array(
				'label'     => __( 'Close Button', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'qv_close_icon_size',
			array(
				'label'      => __( 'Icon Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
					'vh' => array(
						'min' => 0,
						'max' => 50,
					),
					'%'  => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-woo-qv-cross i' => 'font-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'qv_close_icon_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-woo-qv-cross i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'qv_close_icon_hv_color',
			array(
				'label'     => __( 'Icon Hover Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-woo-qv-cross i:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'qv_close_icon_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .xpro-woo-qv-cross',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'qv_close_icon_border',
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .xpro-woo-qv-cross',
			)
		);

		$this->add_responsive_control(
			'qv_close_icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-woo-qv-cross' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_close_icon_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-woo-qv-cross' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_close_icon_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .xpro-woo-qv-cross' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'qv_quantity_btn_input_heading',
			array(
				'label'     => __( 'Quantity Buttons Input', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'qv_quantity_btn_input_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-popup-wrapper input[type="number"]' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'qv_quantity_btn_input_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-woo-qv-content-sec .quantity'          => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .xpro-qv-popup-wrapper input[type="number"]' => 'background-color: transparent',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'qv_quantity_btn_input_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-woo-qv-content-sec .quantity',
			)
		);

		$this->add_control(
			'qv_quantity_btn_input_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-woo-qv-content-sec .quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'qv_btn_heading',
			array(
				'label'     => __( 'Button', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'qv_button_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button',
			)
		);

		$this->start_controls_tabs(
			'qv_button_style_tabs'
		);

		$this->start_controls_tab(
			'qv_button_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'qv_button_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'qv_button_bg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'qv_button_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'qv_button_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'qv_button_hcolor',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button:hover,{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button:focus' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'qv_button_hbg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button:hover,{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button:focus',
			)
		);

		$this->add_control(
			'qv_button_hborder',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button:hover,{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'qv_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_button_item_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'qv_button_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-qv-main-wrapper .single_add_to_cart_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'variations_styles',
			array(
				'label' => esc_html__( 'Popup Variations', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'variation_tabs' );

		$this->start_controls_tab(
			'variation_label_tab',
			array(
				'label' => esc_html__( 'Label', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variation_label_typography',
				'label'    => esc_html__( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .variations label, {{WRAPPER}} .variations select',
			)
		);

		$this->add_control(
			'variation_label_color',
			array(
				'label'     => esc_html__( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .variations label,{{WRAPPER}} .variations select' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'variation_inline_label_space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .variations td.value .xpro_swatches'         => 'grid-gap: {{SIZE}}{{UNIT}}; display: flex; flex-wrap: wrap',
					'{{WRAPPER}} .variations td.value .xpro_swatches .swatch' => 'margin-right: 0;',
				),
			)
		);

		$this->add_control(
			'variation_label_display_style',
			array(
				'label'                => esc_html__( 'Display Style', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'row',
				'options'              => array(
					'row'    => array(
						'title' => __( 'Inline', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'column' => array(
						'title' => __( 'Block', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'selectors_dictionary' => array(
					'row'    => 'flex-direction: row; display:flex; align-items: center',
					'column' => 'flex-direction: column;display:flex;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .variations tr' => '{{VALUE}}',
				),
			)
		);

		$this->add_control(
			'variation_inline_label_width',
			array(
				'label'      => esc_html__( 'Label Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .variations th.label' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .variations td.value' => 'width: 100%;',
				),
				'condition'  => array(
					'variation_label_display_style' => 'row',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_description_tab',
			array(
				'label'     => esc_html__( 'Description', 'xpro-elementor-addons' ),
				'condition' => array(
					'show_variation_description' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variation_description_typography',
				'label'    => esc_html__( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-variation-description',
				'exclude'  => array( 'font_family', 'font_style', 'text_decoration' ),
			)
		);

		$this->add_control(
			'variation_description_color',
			array(
				'label'     => esc_html__( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#666666',
				'selectors' => array(
					'{{WRAPPER}} .woocommerce-variation-description p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'variation_description_margin',
			array(
				'label'      => esc_html__( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-variation-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_price_tab',
			array(
				'label' => esc_html__( 'Price', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variation_price_typography',
				'label'    => esc_html__( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} :is(.price, .price del, .price ins )',
				'exclude'  => array( 'text_transform' ),
			)
		);

		$this->add_control(
			'variation_price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} :is(.price, .price del, .price ins )' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_sale_price_color',
			array(
				'label'     => esc_html__( 'Sale Price Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .price ins .amount' => 'background: transparent; color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_price_discount_badge_color',
			array(
				'label'     => esc_html__( 'Discount Badge Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} .xpro-badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_price_discount_badge_bg_color',
			array(
				'label'     => esc_html__( 'Discount Badge Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .xpro-badge' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'variation_price_discount_badge_font_size',
			array(
				'label'      => esc_html__( 'Badge Font Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-badge' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'variation_price_margin',
			array(
				'label'      => esc_html__( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .woocommerce-variation-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; display: block;',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'variation_dropdown',
			array(
				'label'     => esc_html__( 'Dropdown', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'variation_dropdown_color',
			array(
				'label'     => esc_html__( 'Dropdown Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .variations select' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'variation_dropdown_border',
				'label'    => esc_html__( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .variations select',
			)
		);

		$this->add_control(
			'variation_dropdown_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .variations select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'variation_dropdown_border_border!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'variation_item_margin',
			array(
				'label'      => esc_html__( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .variations tr' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'variation_swatches_styles',
			array(
				'label' => esc_html__( 'Popup Swatches', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'variation_swatch_tabs' );

		$this->start_controls_tab(
			'variation_swatch_color_tab',
			array(
				'label' => esc_html__( 'Color', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'variation_swatch_color_width',
			array(
				'label'      => esc_html__( 'Swatch Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch.swatch_color' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'variation_swatch_color_height',
			array(
				'label'      => esc_html__( 'Swatch Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch.swatch_color' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'variation_swatch_color_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch_color' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'variation_swatch_color_label_border',
				'label'     => esc_html__( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro_swatches .swatch_color',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'variation_swatch_color_selected_label_border',
			array(
				'label'     => esc_html__( 'Selected Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro_swatches .swatch_color.selected' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_swatch_image_tab',
			array(
				'label' => esc_html__( 'Image', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'variation_swatch_image_width',
			array(
				'label'      => esc_html__( 'Swatch Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch.swatch_image' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'variation_swatch_image_height',
			array(
				'label'      => esc_html__( 'Swatch Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 150,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch.swatch_image' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'variation_swatch_image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch_image' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'variation_swatch_image_label_border',
				'label'     => esc_html__( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro_swatches .swatch_image',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'variation_swatch_image_selected_label_border',
			array(
				'label'     => esc_html__( 'Selected Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro_swatches .swatch_image.selected' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'variation_swatch_label_tab',
			array(
				'label' => esc_html__( 'Label', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'variation_swatch_label_typography',
				'label'    => esc_html__( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro_swatches .swatch_label',
			)
		);

		$this->add_control(
			'variation_swatch_label_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro_swatches .swatch_label' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'variation_swatch_label_background_color',
			array(
				'label'     => esc_html__( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro_swatches .swatch_label' => 'background-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'variation_swatch_label_selected_label_border',
			array(
				'label'     => esc_html__( 'Selected Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro_swatches .swatch_label.selected' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'variation_swatch_label_label_border',
				'label'     => esc_html__( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro_swatches .swatch_label',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'variation_swatch_label_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch_label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'variation_swatch_label_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro_swatches .swatch_label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'badges_style',
			array(
				'label'     => __( 'Badges', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_badges' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badges_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-badges-btn',
			)
		);

		$this->add_responsive_control(
			'badges_bg_size',
			array(
				'label'              => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px', 'vh', '%' ),
				'range'              => array(
					'px' => array(
						'min' => 10,
						'max' => 1200,
					),
					'vh' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
				'selectors'          => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-sale-flash-btn, {{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				),
				'condition'          => array(
					'woo_badges_style' => 'circle',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'badges_btn_shadow',
				'label'     => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-badges-btn',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'badges_btn_border',
				'selector' => '{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-badges-btn',
			)
		);

		$this->add_responsive_control(
			'badges_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-badges-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badges_btn_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-badges-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'badges_btn_margin',
			array(
				'label'      => __( 'Buttons Spacing', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-product-grid-badges-wrapper' => 'Margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'sale_badge_heading',
			array(
				'label'     => __( 'Sale', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_sale_badge' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'sale_btn_background',
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-sale-flash-btn',
				'condition' => array(
					'show_sale_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'sale_btn_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-sale-flash-btn' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_sale_badge' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'sale_btn_margin',
			array(
				'label'      => __( 'Spacing', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-sale-flash-btn' => 'Margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_sale_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_badge_heading',
			array(
				'label'     => __( 'Featured', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_featured_badge' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'featured_btn_background',
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn',
				'condition' => array(
					'show_featured_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'featured_btn_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_featured_badge' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'featured_btn_margin',
			array(
				'label'      => __( 'Spacing', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn' => 'Margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_featured_badge' => 'yes',
				),
			)
		);

		$this->add_control(
			'out_stock_heading',
			array(
				'label'     => __( 'Out Of Stock', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'out_stock_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-out-of-stock-btn',
			)
		);

		$this->add_control(
			'out_stock_btn_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-out-of-stock-btn' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'out_stock_margin',
			array(
				'label'      => __( 'Spacing', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-woo-product-grid-item .xpro-woo-out-of-stock-btn' => 'Margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => __( 'Button', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_cta' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button',
			)
		);

		$this->start_controls_tabs(
			'button_style_tabs'
		);

		$this->start_controls_tab(
			'button_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_bg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'button_hcolor',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button:hover,{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button:focus' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_hbg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button:hover,{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button:focus',
			)
		);

		$this->add_control(
			'button_hborder',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button:hover,{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_item_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-woo-product-grid-add-to-cart-btn .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'     => __( 'Pagination', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_pagination' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_alignment',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-post-pagination' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'pagination_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-post-pagination .page-numbers',
			)
		);

		$this->add_responsive_control(
			'pagination_space_between',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-post-pagination' => 'grid-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'pagination_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-elementor-post-pagination .page-numbers',
			)
		);

		$this->add_responsive_control(
			'pagination_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-post-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-post-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-elementor-post-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'pagination_style_tabs'
		);

		$this->start_controls_tab(
			'pagination_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-post-pagination .page-numbers' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-post-pagination .page-numbers' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_hover_tab',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'pagination_hover_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-post-pagination .page-numbers:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_bg_hover_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-post-pagination .page-numbers:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_active_tab',
			array(
				'label' => __( 'Active', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'pagination_active_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-post-pagination .page-numbers.current' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'pagination_bg_arctive_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-post-pagination .page-numbers.current' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Get a list of Taxonomy
	 *
	 * @return array
	 */
	public static function get_taxonomies() {
		$list     = xpro_elementor_get_taxonomies( array( 'public' => true ), 'object', true );
		$list[''] = __( 'None', 'xpro-elementor-addons' );
		return $list;
	}

	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.1.8
	 * @access protected
	 */
	protected function render() {

		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		global $product;

		$get_all_product = array(
			'orderby'     => 'date',
			'numberposts' => - 1,
			'order'       => 'ASC',
			'return'      => 'ids',
			'status'      => 'publish',
		);

		$get_all_product_ids = wc_get_products( $get_all_product );

		if ( empty( $product ) && Plugin::$instance->editor->is_edit_mode() && ( empty( $get_all_product_ids ) ) ) {
			?>
			<div class="xpro-alert xpro-alert-warning" role="alert">
				<span class="xpro-alert-title">
					<?php esc_html_e( 'Products Not Found', 'xpro-elementor-addons' ); ?>
				</span>
				<span class="xpro-alert-description">
					<?php esc_html_e( 'You dont have any product please add some products first. This text will disappear after closing the editor mode.', 'xpro-elementor-addons' ); ?>
				</span>
			</div>
			<?php
			return;
		}

		?>

		<div class="xpro-product-grid-wrapper xpro-woo-product-grid-layout-<?php echo esc_attr( $settings['layout'] ); ?>">

			<?php

			$args = xpro_elementor_get_query_args( $settings );
			$args = xpro_elementor_get_dynamic_args( $settings, $args );

			if ( 'source_dynamic' === $settings['post_type'] && ( 'xpro-themer' === get_post_type() || 'xpro_content' === get_post_type() ) ) {
				$document     = Plugin::instance()->documents->get_doc_or_auto_save( get_the_ID() );
				$dynamic_args = $document->get_document_query_args();
				if ( empty( $dynamic_args ) ) {
					$dynamic_args['post_type']      = 'post';
					$dynamic_args['posts_per_page'] = get_option( 'posts_per_page' );
				}
				$args = array_merge( $args, $dynamic_args );
			}

			$found_posts = 0;
			$paged       = 1;

			if ( 'yes' === $settings['show_pagination'] ) {
				$args['offset'] = '';
				if ( get_query_var( 'paged' ) ) {
					$paged = get_query_var( 'paged' );
				} elseif ( get_query_var( 'page' ) ) {
					$paged = get_query_var( 'page' );
				}
			}

			$args['paged'] = $paged;

			if ( 'sale' === $settings['filter_by'] ) {

				$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
			} elseif ( 'featured' === $settings['filter_by'] ) {

				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => 'featured',
				);
			} elseif ( 'top_rated' === $settings['filter_by'] ) {
				$args['meta_key']   = '_wc_average_rating';
				$args['orderby']    = 'meta_value_num';
				$args['meta_query'] = WC()->query->get_meta_query();
				$args['tax_query']  = WC()->query->get_tax_query();
			} elseif ( 'best_selling' === $settings['filter_by'] ) {
				$args['meta_key'] = 'total_sales';
				$args['order']    = 'DESC';
				$args['orderby']  = 'meta_value_num';
			}

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {

				$found_posts      = $query->found_posts;
				$max_page         = ceil( $found_posts / absint( $args['posts_per_page'] ) );
				$args['max_page'] = $max_page;
				?>

				<div class="xpro-woo-product-grid-main cbp">
					<?php
					while ( $query->have_posts() ) {
						$query->the_post();

						require XPRO_ELEMENTOR_ADDONS_WIDGET . 'woo-product-grid/layout/frontend.php';
					}
					?>
				</div>

				<?php

				if ( $found_posts > $args['posts_per_page'] && 'yes' === $settings['show_pagination'] ) {

					$prev_icon_class = $settings['arrow'];
					$next_icon_class = str_replace( 'left', 'right', $settings['arrow'] );

					$prev_text = '<i class="' . $prev_icon_class . '"></i><span class="xpro-elementor-post-pagination-prev-text">' . $settings['prev_label'] . '</span>';
					$next_text = '<span class="xpro-elementor-post-pagination-next-text">' . $settings['next_label'] . '</span><i class="' . $next_icon_class . '"></i>';

					$paginate_args = array(
						'type'      => 'array',
						'current'   => max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) ),
						'total'     => $query->max_num_pages,
						'prev_next' => true,
						'prev_text' => $prev_text,
						'next_text' => $next_text,
					);

					if ( is_singular() && ! is_front_page() ) {
						global $wp_rewrite;
						if ( $wp_rewrite->using_permalinks() ) {
							$paginate_args['format'] = user_trailingslashit( 'page%#%', 'single_paged' ); // Change Occurs For Fixing Pagination Issue.
						} else {
							$paginate_args['format'] = '?page=%#%';
						}
					}

					$links = paginate_links( $paginate_args );
					?>

					<nav class="xpro-elementor-post-pagination" role="navigation" aria-label="<?php esc_attr_e( 'Pagination', 'xpro-elementor-addons' ); ?>">
						<?php echo implode( PHP_EOL, $links ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</nav>
					<?php
				}

				wp_reset_postdata();
			} else {
				if ( Plugin::$instance->editor->is_edit_mode() ) {
					?>
					<p class="xpro-alert xpro-alert-warning">
						<span class="xpro-alert-title"><?php echo esc_html( 'No Product Found!' ); ?></span>
						<span class="xpro-alert-description"><?php echo esc_html( 'Sorry, but nothing matched your selection. Please try again with some different keywords.' ); ?></span>
					</p>
					<?php
				}
			}
			?>
		</div>

		<!-- quick view main wrapper -->
		<div class="xpro-qv-main-wrapper">
			<div class="xpro-qv-inner-wrapper xpro-qv-layouts xpro-qv-layout-<?php echo esc_attr( $settings['qv_layout'] ); ?>">
				<!-- quick view loader  -->
				<div class="xpro-qv-loader-wrapper xpro-qv-preloader-layout">
					<div class="xpro-qv-preloader">
						<div class="xpro-qv-preloader-box">
							<div class="xpro-qv-loader-spinner spinner-1"></div>
							<div class="xpro-qv-loader-spinner spinner-2"></div>
							<div class="xpro-qv-loader-spinner spinner-3"></div>
						</div>
					</div>
				</div>
				<!-- quick view loader end -->

				<!-- quick view -->
				<div class="xpro-qv-popup-overlay"></div>
				<div class="xpro-qv-popup-wrapper">
					<div class="xpro-qv-popup-inner animated <?php echo esc_attr( $settings['qv_animation'] ); ?>">
						<div id="xpro_elementor_fetch_qv_data" class="xpro-fetch-qv-cls"></div>
					</div>
				</div>
				<!-- quick view end -->
			</div>
		</div>
		<!-- quick view main wrapper end -->
		<?php

	}
}
