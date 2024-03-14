<?php
namespace Auxin\Plugin\Portfolio\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Files\CSS\Post;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Recent_Portfolios_Tile_Carousel' widget.
 *
 * Elementor widget that displays an 'Recent_Portfolios_Tile_Carousel' with lightbox.
 *
 * @since 1.0.0
 */
class Recent_Portfolios_Grid_Carousel extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Recent_Portfolios_Tile_Carousel' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_recent_portfolios_grid_carousel';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Recent_Portfolios_Tile_Carousel' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Grid Carousel Portfolios', 'auxin-portfolio' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Recent_Portfolios_Tile_Carousel' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-posts-carousel auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Recent_Portfolios_Tile_Carousel' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-portfolio' );
    }

    /**
     * Retrieve the terms in a given taxonomy or list of taxonomies.
     *
     * Retrieve 'Recent_Portfolios_Tile_Carousel' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_terms() {
        // Get terms
        $terms = get_terms(
            array(
                'taxonomy'   => 'portfolio-cat',
                'orderby'    => 'count',
                'hide_empty' => true
            )
        );

        // Then create a list
        $list  = array( ' ' => __('All Categories', 'auxin-portfolio' ) ) ;

        if ( ! is_wp_error( $terms ) && is_array( $terms ) ){
            foreach ( $terms as $key => $value ) {
                $list[$value->term_id] = $value->name;
            }
        }

        return $list;
    }

    /**
     * Register 'Recent_Portfolios_Tile_Carousel' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        /*-------------------------------------------------------------------*/
        /*  Layout TAB
        /*-------------------------------------------------------------------*/

        /*  Layout Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'layout_section',
            array(
                'label' => __('Layout', 'auxin-portfolio' ),
                'tab'   => Controls_Manager::TAB_LAYOUT
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'          => __( 'Columns', 'auxin-portfolio' ),
                'type'           => Controls_Manager::SELECT,
                'default'        => '4',
                'tablet_default' => 'inherit',
                'mobile_default' => '1',
                'options'        => array(
                    'inherit' => __( 'Inherited from larger', 'auxin-portfolio' ),
                    '1'       => '1',
                    '2'       => '2',
                    '3'       => '3',
                    '4'       => '4',
                    '5'       => '5',
                    '6'       => '6'
                ),
                'frontend_available' => true
            )
        );

        $this->add_control(
            'show_title',
            array(
                'label'        => __('Display title', 'auxin-portfolio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-portfolio' ),
                'label_off'    => __( 'Off', 'auxin-portfolio' ),
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'show_info',
            array(
                'label'        => __('Display Info','auxin-portfolio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-portfolio' ),
                'label_off'    => __( 'Off', 'auxin-portfolio' ),
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'display_like',
            array(
                'label'        => __('Display like button', 'auxin-portfolio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-portfolio' ),
                'label_off'    => __( 'Off', 'auxin-portfolio' ),
                'default'      => 'no'
            )
        );

        $this->add_control(
            'preloadable',
            array(
                'label'        => __('Preload image','auxin-portfolio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-portfolio' ),
                'label_off'    => __( 'Off', 'auxin-portfolio' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'preload_preview',
            array(
                'label'        => __('While loading image display','auxin-portfolio' ),
                'label_block'  => true,
                'type'         => Controls_Manager::SELECT,
                'options'      => auxin_get_preloadable_previews(),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => array(
                    'preloadable' => 'yes'
                )
            )
        );

        $this->add_control(
            'preload_bgcolor',
            array(
                'label'     => __( 'Placeholder color while loading image', 'auxin-portfolio' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'preloadable'     => 'yes',
                    'preload_preview' => array('simple-spinner', 'simple-spinner-light', 'simple-spinner-dark')
                )
            )
        );

        $this->add_control(
            'deeplink',
            array(
                'label'        => __('Deeplink', 'auxin-portfolio' ),
                'description'  => __('Enables the deeplink feature, it updates URL based on page and filter status.', 'auxin-portfolio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-portfolio' ),
                'label_off'    => __( 'Off', 'auxin-portfolio' ),
                'default'      => 'no'
            )
        );

        $this->add_control(
            'deeplink_slug',
            array(
                'label'       => __('Deeplink slug', 'auxin-portfolio' ),
                'description' => __('Specifies the deeplink slug value in address bar.', 'auxin-portfolio' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'condition'   => array(
                    'deeplink' => 'yes'
                )
            )
        );

        $this->end_controls_section();

        /*  Carousel Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'carousel_section',
            array(
                'label' => __('Carousel', 'auxin-portfolio' ),
                'tab'   => Controls_Manager::TAB_LAYOUT
            )
        );

        $this->add_control(
            'carousel_navigation_control',
            array(
                'label'       => __('Navigation control', 'auxin-portfolio'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'bullets',
                'options'     => array(
                    ''        => __('None', 'auxin-portfolio'),
                    'arrows'  => __('Arrows', 'auxin-portfolio'),
                    'bullets' => __('Bullets', 'auxin-portfolio'),
                    'text'    => __('Text', 'auxin-portfolio'),
                )
            )
        );

        $this->add_control(
            'carousel_navigation_control_text_next',
            array(
                'label'       => __('Next Button', 'auxin-portfolio'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Next', 'auxin-portfolio') ,
                'condition'   => array(
                    'carousel_navigation_control' => 'text'
                )
            )
        );

        $this->add_control(
            'carousel_navigation_control_text_prev',
            array(
                'label'       => __('Previous Button', 'auxin-portfolio'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Prev', 'auxin-portfolio'),
                'condition'   => array(
                    'carousel_navigation_control' => 'text'
                )
            )
        );

        $this->add_control(
            'button_style',
            array(
                'label'       => __('Button Navigation Style','auxin-portfolio' ),
                'type'        => 'aux-visual-select',
                'default'     => 'pattern-1',
                'options'     => array(
                    'pattern-1'            => array(
                        'label' => __('Pattern 1', 'auxin-portfolio' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-normal.svg'
                    ),
                    'pattern-2' => array(
                        'label' => __('Pattern 2', 'auxin-portfolio' ),
                        'image' => AUXIN_URL . 'images/visual-select/button-curved.svg'
                    )
                ),
                'condition'   => array(
                    'carousel_navigation_control' => array( 'arrows' ),
                )
            )
        );

        $this->add_control(
            'carousel_navigation',
            array(
                'label'       => __('Navigation type', 'auxin-portfolio'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'peritem',
                'options'     => array(
                   'peritem' => __('Move per column', 'auxin-portfolio'),
                   'perpage' => __('Move per page', 'auxin-portfolio'),
                   'scroll'  => __('Smooth scroll', 'auxin-portfolio')
                )
            )
        );

        $this->add_control(
            'carousel_loop',
            array(
                'label'        => __('Loop navigation','auxin-portfolio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-portfolio' ),
                'label_off'    => __( 'Off', 'auxin-portfolio' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'carousel_autoplay',
            array(
                'label'        => __('Autoplay carousel','auxin-portfolio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-portfolio' ),
                'label_off'    => __( 'Off', 'auxin-portfolio' ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'carousel_autoplay_delay',
            array(
                'label'       => __( 'Autoplay delay', 'auxin-portfolio' ),
                'description' => __('Specifies the delay between auto-forwarding in seconds.', 'auxin-portfolio' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '2'
            )
        );

        $this->end_controls_section();

        /*  Transition Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'transition_section',
            array(
                'label'     => __('Transition', 'auxin-portfolio' ),
                'tab'       => Controls_Manager::TAB_SETTINGS
            )
        );

        $this->add_control(
            'reveal_transition_duration',
            array(
                'label'       => __('Transition duration on reveal','auxin-portfolio' ),
                'description' => __('The transition duration while the element is going to be displayed (milliseconds).'),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '600'
            )
        );

        $this->add_control(
            'reveal_between_delay',
            array(
                'label'       => __('Delay between reveal','auxin-portfolio' ),
                'description' => __('The delay between each sequence of revealing transitions (milliseconds).'),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '60'
            )
        );

        $this->add_control(
            'hide_transition_duration',
            array(
                'label'       => __('Transition duration on hide','auxin-portfolio' ),
                'description' => __('The transition duration while the element is going to be hidden (milliseconds).'),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '600'
            )
        );

        $this->add_control(
            'hide_between_delay',
            array(
                'label'       => __('Delay between hide','auxin-portfolio' ),
                'description' => __('The delay between each sequence of hiding transitions (milliseconds).'),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '30'
            )
        );

        $this->end_controls_section();

        /*-------------------------------------------------------------------*/
        /*  Content TAB
        /*-------------------------------------------------------------------*/

        /*  Query Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'query_section',
            array(
                'label'      => __('Query', 'auxin-portfolio' ),
            )
        );

        $this->add_control(
            'cat',
            array(
                'label'       => __('Categories', 'auxin-portfolio'),
                'description' => __('Specifies a category that you want to show posts from it.', 'auxin-portfolio' ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_terms(),
                'default'     => array( ' ' ),
            )
        );

        $this->add_control(
            'num',
            array(
                'label'       => __('Number of portfolios to show in per page', 'auxin-portfolio'),
                'description' => __('Leave it empty to show all items', 'auxin-portfolio'),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '5',
                'min'         => 1,
                'step'        => 1
            )
        );

        $this->add_control(
            'exclude_without_media',
            array(
                'label'        => __('Exclude portfolios without media','auxin-portfolio' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', 'auxin-portfolio' ),
                'label_off'    => __( 'Off', 'auxin-portfolio' ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'order_by',
            array(
                'label'       => __('Order by', 'auxin-portfolio'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'date',
                'options'     => array(
                    'date'            => __('Date', 'auxin-portfolio'),
                    'menu_order date' => __('Menu Order', 'auxin-portfolio'),
                    'title'           => __('Title', 'auxin-portfolio'),
                    'ID'              => __('ID', 'auxin-portfolio'),
                    'rand'            => __('Random', 'auxin-portfolio'),
                    'comment_count'   => __('Comments', 'auxin-portfolio'),
                    'modified'        => __('Date Modified', 'auxin-portfolio'),
                    'author'          => __('Author', 'auxin-portfolio'),
                    'post__in'        => __('Inserted Post IDs', 'auxin-portfolio')
                ),
            )
        );

        $this->add_control(
            'order',
            array(
                'label'       => __('Order', 'auxin-portfolio'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'DESC',
                'options'     => array(
                    'DESC'          => __('Descending', 'auxin-portfolio'),
                    'ASC'           => __('Ascending', 'auxin-portfolio'),
                ),
            )
        );

        $this->add_control(
            'only_posts__in',
            array(
                'label'       => __('Only portfolios','auxin-portfolio' ),
                'description' => __('If you intend to display ONLY specific portfolios, you should specify the portfolios here. You have to insert the post IDs that are separated by comma (eg. 53,34,87,25).', 'auxin-portfolio' ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'include',
            array(
                'label'       => __('Include portfolios','auxin-portfolio' ),
                'description' => __('If you intend to include additional portfolios, you should specify the portfolios here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-portfolio' ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'exclude',
            array(
                'label'       => __('Exclude portfolios','auxin-portfolio' ),
                'description' => __('If you intend to exclude specific portfolios from result, you should specify the portfolios here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-portfolio' ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'offset',
            array(
                'label'       => __('Start offset','auxin-portfolio' ),
                'description' => __('Number of portfolios to display or pass over.', 'auxin-portfolio' ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => ''
            )
        );

        $this->end_controls_section();

        /*-------------------------------------------------------------------*/
        /*  Style TAB
        /*-------------------------------------------------------------------*/

        /*  Image Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'image_style_section',
            array(
                'label'     => __( 'Image', 'auxin-portfolio' ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'space',
            array(
                'label'       => __('Space', 'auxin-portfolio' ),
                'description' => __('Specifies horizontal space between items (pixel).', 'auxin-portfolio' ),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '30',
                'min'         => 0,
                'step'        => 1
            )
        );

        $this->add_control(
            'image_aspect_ratio',
            array(
                'label'       => __('Image aspect ratio', 'auxin-portfolio'),
                'type'        => Controls_Manager::SELECT,
                'default'     => '0.75',
                'options'     => array(
                    '0.75'   => __('Horizontal 4:3'     , 'auxin-portfolio'),
                    '0.56'   => __('Horizontal 16:9',   'auxin-portfolio'),
                    '1.00'   => __('Square     1:1'     , 'auxin-portfolio'),
                    '1.33'   => __('Vertical   3:4'     , 'auxin-portfolio'),
                    'custom' => __('Custom'    ,        'auxin-portfolio'),
                )
            )
        );

        $this->add_control(
            'image_aspect_ratio_custom',
            array(
                'label'       => __('Custom image aspect ratio', 'auxin-portfolio' ),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => 1,
                'min'         => 0,
                'step'        => 0.5,
                'condition'  => array(
                    'image_aspect_ratio' => 'custom'
                )
            )
        );

        $this->add_control(
            'item_style',
            array(
                'label'       => __('Hover Type','auxin-portfolio' ),
                'description' => __('Move your mouse over each item in order to preview the hover effect.','auxin-portfolio' ),
                'style_items' => 'max-width:48%; min-height:90px;',
                'type'        => 'aux-visual-select',
                'options'     => array(
                    'classic'       => array(
                        'label'     => __('No animation' , 'auxin-portfolio'),
                        'image'     => AUXPFO_ADMIN_URL . '/assets/images/ClassicLightbox.png'
                    ),
                    'classic-lightbox'     => array(
                        'label'     => __('Classic with lightbox style 1' , 'auxin-portfolio'),
                        'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/ClassicLightbox1.webm webm'
                    ),
                    'classic-lightbox-boxed'       => array(
                        'label'     => __('Classic with lightbox style 2' , 'auxin-portfolio'),
                        'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/ClassicLightbox2.webm webm'
                    ),
                    'overlay'       => array(
                        'label'     => __('Overlay title style 1', 'auxin-portfolio'),
                        'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitle1.webm webm'
                    ),
                    'overlay-boxed' => array(
                        'label'     => __('Overlay title style 2', 'auxin-portfolio'),
                        'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitle2.webm webm'
                    ),
                    'overlay-lightbox' => array(
                        'label'     => __('Overlay title with lightbox style 1', 'auxin-portfolio'),
                        'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitleLightbox1.webm webm'
                    ),
                    'overlay-lightbox-boxed' => array(
                        'label'     => __('Overlay title with lightbox style 2', 'auxin-portfolio'),
                        'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitleLightbox2.webm webm'
                    )
                ),
                'default'  => 'classic'
            )
        );

        $this->add_control(
            'img_border_radius',
            array(
                'label'      => __( 'Border Radius', 'auxin-portfolio' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-media-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                )
            )
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'item_hover_style',
                'label' => __( 'Background', 'auxin-portfolio' ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .entry-media::after',
            )
        );

        $this->end_controls_section();


        /*  Title Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'      => __( 'Title', 'auxin-portfolio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'condition'  => array(
                    'show_title' => 'yes'
                )
            )
        );

        // Title heading

        $this->start_controls_tabs( 'title_colors' );

        $this->start_controls_tab(
            'title_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-portfolio' )
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => __( 'Color', 'auxin-portfolio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-header .entry-title a' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-portfolio' )
            )
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label' => __( 'Color', 'auxin-portfolio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-header .entry-title a:hover' => 'color:{{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'title_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .entry-header .entry-title a'
            )
        );

        $this->add_responsive_control(
            'title_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-portfolio' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .entry-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();


        /*  Categories Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'info_style_section',
            array(
                'label'      => __( 'Info Meta', 'auxin-portfolio' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'condition'  => array(
                    'show_info' => 'yes'
                )
            )
        );

        $this->start_controls_tabs( 'info_colors' );

        $this->start_controls_tab(
            'info_color_normal',
            array(
                'label'     => __( 'Normal' , 'auxin-portfolio' ),
            )
        );

        $this->add_control(
            'info_color',
            array(
                'label'     => __( 'Color', 'auxin-portfolio' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-tax a' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'info_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-portfolio' ),
            )
        );

        $this->add_control(
            'info_hover_color',
            array(
                'label' => __( 'Color', 'auxin-portfolio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .entry-tax a:hover' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'info_typography',
                'scheme'    => Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .entry-tax'
            )
        );

        $this->add_responsive_control(
            'info_margin_bottom',
            array(
                'label' => __( 'Bottom space', 'auxin-portfolio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .entry-tax' => 'margin-bottom: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'info_spacing_between',
            array(
                'label' => __( 'Space between metas', 'auxin-portfolio' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 30
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .entry-tax a:after' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        /*  Image Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'carousel_style_section',
            array(
                'label'     => __( 'Carousel', 'auxin-portfolio' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition'    => array(
                    'carousel_navigation_control' => 'text'
                )
            )
        );

        $this->start_controls_tabs( 'carousel_colors' );

        $this->start_controls_tab(
            'carousel_color_normal',
            array(
                'label' => __( 'Normal' , 'auxin-portfolio' )
            )
        );

        $this->add_control(
            'carousel_color',
            array(
                'label' => __( 'Color', 'auxin-portfolio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-mc-arrows .aux-text-arrow' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'carousel_color_hover',
            array(
                'label' => __( 'Hover' , 'auxin-portfolio' )
            )
        );

        $this->add_control(
            'carousel_hover_color',
            array(
                'label' => __( 'Color', 'auxin-portfolio' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-mc-arrows .aux-text-arrow:hover' => 'color:{{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'carousel_typography',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-mc-arrows .aux-text-arrow'
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render image box widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        $args = array(
            // Layout section
            'desktop_cnum'                => $settings['columns'],
            'tablet_cnum'                 => $settings['columns_tablet'],
            'phone_cnum'                  => $settings['columns_mobile'],
            'show_title'                  => $settings['show_title'],
            'display_like'                => $settings['display_like'],
            'show_info'                   => $settings['show_info'],
            'preloadable'                 => $settings['preloadable'],
            'preload_preview'             => $settings['preload_preview'],
            'preload_bgcolor'             => $settings['preload_bgcolor'],
            'deeplink'                    => $settings['deeplink'],
            'deeplink_slug'               => $settings['deeplink_slug'],

            // Carousel section
            'carousel_navigation'                   => $settings['carousel_navigation'],
            'button_style'                          => $settings['button_style'],
            'carousel_navigation_control'           => $settings['carousel_navigation_control'],
            'carousel_loop'                         => $settings['carousel_loop'],
            'carousel_autoplay'                     => $settings['carousel_autoplay'],
            'carousel_autoplay_delay'               => $settings['carousel_autoplay_delay'],
            'carousel_navigation_control_text_next' => $settings['carousel_navigation_control_text_next'],
            'carousel_navigation_control_text_prev' => $settings['carousel_navigation_control_text_prev'],

            'reveal_transition_duration'  => auxin_get_control_size( $settings['reveal_transition_duration'] ),
            'reveal_between_delay'        => auxin_get_control_size( $settings['reveal_between_delay'] ),
            'hide_transition_duration'    => auxin_get_control_size( $settings['hide_transition_duration'] ),
            'hide_between_delay'          => auxin_get_control_size( $settings['hide_between_delay'] ),

            // Query section
            'cat'                         => $settings['cat'],
            'num'                         => $settings['num'],
            'only_posts__in'              => $settings['only_posts__in'],
            'include'                     => $settings['include'],
            'exclude'                     => $settings['exclude'],
            'offset'                      => $settings['offset'],
            'order_by'                    => $settings['order_by'],
            'order'                       => $settings['order'],
            'exclude_without_media'       => $settings['exclude_without_media'],

            'image_aspect_ratio'          => $settings['image_aspect_ratio'] !== 'custom' ? $settings['image_aspect_ratio'] : $settings['image_aspect_ratio_custom'] ,
            'space'                       => auxin_get_control_size( $settings['space'] ),
            'item_style'                  => $settings['item_style'],

        );

        echo auxin_widget_recent_portfolios_grid_carousel_callback( $args );
    }

}
