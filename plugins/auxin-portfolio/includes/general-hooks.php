<?php
/**
 * General WordPress Hooks
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2023 averta
 */



/**
 * Outputs theme options for portfolio
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2023 averta
 */
function auxin_define_portfolio_theme_options( $fields_sections_list ){

    $options  = $fields_sections_list['fields'  ];
    $sections = $fields_sections_list['sections'];

    /* ---------------------------------------------------------------------------------------------------
        Portfolio Section
    --------------------------------------------------------------------------------------------------- */

    // Portfolio section ==================================================================

    $sections[] = array(
        'id'          => 'portfolio-section',
        'parent'      => '', // section parent's id
        'title'       => __( 'Portfolio', 'auxin-portfolio'),
        'description' => __( 'Portfolio Setting', 'auxin-portfolio'),
        'icon'        => 'axicon-doc'
    );

    // Sub section - Portfolio Single Page -------------------------------

    $sections[] = array(
        'id'           => 'portfolio-section-single',
        'parent'       => 'portfolio-section', // section parent's id
        'title'        => __( 'Single Portfolio', 'auxin-portfolio'),
        'description'  => __( 'Preview a Single Portfolio Page', 'auxin-portfolio'),
        'preview_link' => auxin_get_last_post_permalink( array( 'post_type' => 'portfolio' ) )
    );


    $options[] = array(
        'title'       => __('Single Portfolio Template', 'auxin-portfolio'),
        'description' => __('Specifies single portfolio template.', 'auxin-portfolio'),
        'id'          => 'portfolio_single_side_pos',
        'section'     => 'portfolio-section-single',
        'dependency'  => array(),
        'choices'     => array(
            'right'     => array(
                'label' => __('Info on Right', 'auxin-portfolio'),
                'image' => AUXIN_URL . 'images/visual-select/portfolio-single-classic.svg'
            ),
            'left'   => array(
                'label' => __('Info on Left', 'auxin-portfolio'),
                'image' => AUXIN_URL . 'images/visual-select/portfolio-single-classic-left-algin.svg'
            ),
            'top'   => array(
                'label' => __('Info on Top', 'auxin-portfolio'),
                'image' => AUXIN_URL . 'images/visual-select/portfolio-single-info-on-top-direction-reverse.svg'
            ),
            'top-reverse'   => array(
                'label' => __('Info on Top - Direction reverse', 'auxin-portfolio'),
                'image' => AUXIN_URL . 'images/visual-select/portfolio-single-info-on-top.svg'
            ),
            'top-down'   => array(
                'label' => __('Info on Top - Metadata Below', 'auxin-portfolio'),
                'image' => AUXIN_URL . 'images/visual-select/portfolio-single-info-on-top-metadata-on-blow.svg'
            ),
            'bottom'   => array(
                'label' => __('Info on Bottom', 'auxin-portfolio'),
                'image' => AUXIN_URL . 'images/visual-select/portfolio-single-wide.svg'
            ),
            'bottom-reverse'   => array(
                'label' => __('Info on Bottom - Direction reverse', 'auxin-portfolio'),
                'image' => AUXIN_URL . 'images/visual-select/portfolio-single-wide2.svg'
            ),
            'bottom-down'   => array(
                'label' => __('Info on Bottom - Metadata Below', 'auxin-portfolio'),
                'image' => AUXIN_URL . 'images/visual-select/portfolio-single-info-on-bottom-metadata-blow.svg'
            )
        ),
        'default'     => 'right',
        'type'        => 'radio-image',
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.single-portfolio .aux-single .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/entry/single', 'portfolio');
            }
        )
    );

    $options[] = array(
        'title'       => __( 'Custom Max Width', 'auxin-portfolio' ),
        'description' => __( 'Specifies the maximum width of website.', 'auxin-portfolio' ),
        'id'          => 'portfolio_max_width_layout',
        'section'     => 'portfolio-section-single',
        'type'        => 'select',
        'transport'   => 'postMessage',
        'dependency'  => array(),
        'choices'     => array(
            ''      => __( 'Default Site Max Width', 'auxin-portfolio' ),
            'nd'    => __( '1000 Pixels', 'auxin-portfolio' ),
            'hd'    => __( '1200 Pixels', 'auxin-portfolio' ),
            'xhd'   => __( '1400 Pixels', 'auxin-portfolio' ),
            's-fhd' => __( '1600 Pixels', 'auxin-portfolio' ),
            'fhd'   => __( '1900 Pixels', 'auxin-portfolio' )
        ),
        'post_js'   => '$( "body.single-portfolio" ).removeClass( "aux-nd aux-hd aux-xhd aux-s-fhd aux-fhd" ).addClass( "aux-" + to ); $(window).trigger("resize");',
        'default'   => ''
    );

    $options[] = array(
        'title'       => __('Overview Alignment', 'auxin-portfolio'),
        'description' => __('Specifies alignment for the project overview and corresponding information.', 'auxin-portfolio'),
        'id'          => 'portfolios_overview_info_alignment',
        'section'     => 'portfolio-section-single',
        'dependency'  => array(),
        'type'        => 'radio-image',
        'default'     => 'default',
        'choices'     => array(
            'left' => array(
                'label'     => __('Left', 'auxin-portfolio'),
                'css_class' => 'axiAdminIcon-text-align-left'
            ),
            'center' => array(
                'label'     => __('Center', 'auxin-portfolio'),
                'css_class' => 'axiAdminIcon-text-align-center'
            )
        ),
        'post_js'     => '$(".single-portfolio main.aux-single .aux-primary .type-portfolio").alterClass( "aux-text-align-*", "aux-text-align-" + to );',
    );

    $options[] = array(
        'title'       => __( 'Image Size', 'auxin-portfolio' ),
        'description' => __( 'Select size of featured image', 'auxin-portfolio' ),
        'id'          => 'portfolio_single_image_size',
        'section'     => 'portfolio-section-single',
        'transport'   => 'refresh',
        'type'        => 'select',
        'choices'     => array(
            ''              => __( 'Default', 'auxin-portfolio' ),
            'medium'        => __( 'Medium', 'auxin-portfolio' ),
            'medium_large'  => __( 'Medium Large', 'auxin-portfolio'),
            'large'         => __( 'Large', 'auxin-portfolio'),
            'full'          => __( 'Original', 'auxin-portfolio'),
        ),
        'default'     => '',
    );

    $options[] =    array(
        'title'       => __('Display Portfolio Meta Info', 'auxin-portfolio'),
        'description' => __('Enable this option to display extra inormation on portfolio single page.', 'auxin-portfolio'),
        'id'          => 'portfolio_single_display_side_info_meta',
        'section'     => 'portfolio-section-single',
        'dependency'  => '',
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.single-portfolio .aux-single .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/entry/single', 'portfolio');
            }
        ),
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] =    array(
        'title'       => __('Display Single Portfolio Categories', 'auxin-portfolio'),
        'description' => __( 'Enable it to display category section in single portfolio.'),
        'id'          => 'portfolio_single_display_category',
        'section'     => 'portfolio-section-single',
        'dependency'  => '',
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.single-portfolio .aux-single .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/entry/single', 'portfolio');
            }
        ),
        'default'     => '0',
        'type'        => 'switch'
    );

    $options[] =    array(
        'title'       => __('Display Single Portfolio Tags', 'auxin-portfolio'),
        'description' => __( 'Enable it to display Tag section in single portfolio.'),
        'id'          => 'portfolio_single_display_tag',
        'section'     => 'portfolio-section-single',
        'dependency'  => '',
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.single-portfolio .aux-single .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/entry/single', 'portfolio');
            }
        ),
        'default'     => '0',
        'type'        => 'switch'
    );

    $options[] =    array(
        'title'       => __('Sticky Side Area', 'auxin-portfolio'),
        'description' => __( 'Enable it to stick the side area on page while scrolling..'),
        'id'          => 'portfolio_single_sticky_sidebar',
        'section'     => 'portfolio-section-single',
        'dependency'  => array(
            array(
                 'id'      => 'portfolio_single_side_pos',
                 'value'   => array('right', 'left'),
                 'operator'=> ''
            )
        ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.single-portfolio .aux-single .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/entry/single', 'portfolio');
            }
        ),
        'default'     => '0',
        'type'        => 'switch'
    );



    $options[] = array(
        'title'       => __( 'Display Next & Previous portfolios', 'auxin-portfolio' ),
        'description' => __( 'Enable it to display links to next and previous portfolios on single portfolio page.' ),
        'id'          => 'show_portfolio_single_next_prev_nav',
        'section'     => 'portfolio-section-single',
        'dependency'  => '',
        'transport'   => 'refresh',
        // 'post_js'     => '$(".single .aux-next-prev-posts").toggle( to );',
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] =    array(
        'title'       => __('Display share/action bar', 'auxin-portfolio'),
        'description' => __( 'Enable it to display the section for share and like button.'),
        'id'          => 'show_portfolio_single_share_like_section',
        'section'     => 'portfolio-section-single',
        'dependency'  => '',
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.single-portfolio .aux-single .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/entry/single', 'portfolio');
            }
        ),
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] =    array(
        'title'       => __('Display share button', 'auxin-portfolio'),
        'description' => __( 'Enable it to display the share button.'),
        'id'          => 'show_portfolio_single_share',
        'section'     => 'portfolio-section-single',
        'dependency'  => '',
        'transport'   => 'postMessage',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_single_share_like_section',
                 'value'   => '1'
            )
        ),
        'partial'     => array(
            'selector'              => '.single-portfolio .aux-single .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/entry/single', 'portfolio');
            }
        ),
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __( 'Share Type', 'auxin-portfolio' ),
        'description' => __( 'Enable it to display text instead of icon.', 'auxin-portfolio' ),
        'id'          => 'portfolio_single_share_button_type',
        'section'     => 'portfolio-section-single',
        'transport'   => 'postMessage',
        'type'        => 'select',
        'choices'     => array(
            'icon'    => __( 'Icon', 'auxin-portfolio' ),
            'text'    => __( 'Text', 'auxin-portfolio' )
        ),
        'dependency'  => array(
            array(
                'id'      => 'show_portfolio_single_share_like_section',
                'value'   => array('1'),
                'operator'=> ''
            ),
            array(
                'id'      => 'show_portfolio_single_share',
                'value'   => array('1'),
                'operator'=> ''
            )
        ),
        'default'     => 'icon',
    );

    $options[] = array(
        'title'       => __( 'Share Button Icon', 'auxin-portfolio' ),
        'id'          => 'portfolio_single_share_button_icon',
        'section'     => 'portfolio-section-single',
        'transport'   => 'refresh',
        'type'        => 'icon',
        'default'     => 'auxicon-share',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_single_share_like_section',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'show_portfolio_single_share',
                'value'   => array('1'),
                'operator'=> ''
            ),
            array(
                'id'      => 'portfolio_single_share_button_type',
                'value'   => array('icon'),
                'operator'=> ''
            )
        )
    );

    $options[] = array(
        'title'         => __( 'Icon Color', 'auxin-portfolio' ),
        'description'   => __( 'Share icon color','auxin-portfolio' ),
        'id'            => 'portfolio_single_share_button_icon_color',
        'section'       => 'portfolio-section-single',
        'transport'     => 'postMessage',
        'type'          => 'color',
        'selectors'     => '.single-portfolio .aux-single-portfolio-share span::before',
        'placeholder'   => 'color:{{VALUE}};',
        'default'       => '',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_single_share_like_section',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'show_portfolio_single_share',
                'value'   => array('1'),
                'operator'=> ''
            ),
            array(
                'id'      => 'portfolio_single_share_button_type',
                'value'   => array('icon'),
                'operator'=> ''
            )
        )
    );

    $options[] = array(
        'title'         => __( 'Icon Hover Color', 'auxin-portfolio' ),
        'description'   => __( 'Share icon hover color','auxin-portfolio' ),
        'id'            => 'portfolio_single_share_button_icon_hover_color',
        'section'       => 'portfolio-section-single',
        'transport'     => 'postMessage',
        'type'          => 'color',
        'selectors'     => '.single-portfolio .aux-single-portfolio-share span:hover::before',
        'placeholder'   => 'color:{{VALUE}};',
        'default'       => '',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_single_share_like_section',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'show_portfolio_single_share',
                'value'   => array('1'),
                'operator'=> ''
            ),
            array(
                'id'      => 'portfolio_single_share_button_type',
                'value'   => array('icon'),
                'operator'=> ''
            )
        )
    );

    $options[] = array(
        'title'       => __( 'Share Button Icon Size', 'auxin-portfolio' ),
        'id'          => 'portfolio_single_share_button_icon_size',
        'section'     => 'portfolio-section-single',
        'transport'   => 'postMessage',
        'type'        => 'text',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_single_share_like_section',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'show_portfolio_single_share',
                'value'   => array('1'),
                'operator'=> ''
            ),
            array(
                'id'      => 'portfolio_single_share_button_type',
                'value'   => array('icon'),
                'operator'=> ''
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = esc_attr( auxin_get_option( 'portfolio_single_share_button_icon_size' ) );
            }
            if( ! is_numeric( $value ) ){
                $value = 10;
            }
            return $value ? ".single-portfolio .aux-single-portfolio-share span::before { font-size:{$value}px; }" : '';
        }
    );

    $options[] = array(
        'title'          => __( 'Share Button Margin', 'auxin-portfolio' ),
        'id'             => 'portfolio_single_share_button_margin',
        'section'        => 'portfolio-section-single',
        'type'           => 'responsive_dimensions',
        'selectors'      => '.single-portfolio .aux-single-portfolio-share',
        'transport'      => 'postMessage',
        'placeholder'    => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_single_share_like_section',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'show_portfolio_single_share',
                'value'   => array('1'),
                'operator'=> ''
            ),
            array(
                'id'      => 'portfolio_single_share_button_type',
                'value'   => array('icon'),
                'operator'=> ''
            )
        ),
    );

    if ( class_exists( 'wp_ulike' ) ) {
        $options[] =    array(
            'title'       => __('Display like button', 'auxin-portfolio'),
            'description' => __( 'Enable it to display the like button. Please note that you should have "ulike" plugin installed for this feature.'),
            'id'          => 'show_portfolio_single_like',
            'section'     => 'portfolio-section-single',
            'dependency'  => '',
            'transport'   => 'postMessage',
            'dependency'  => array(
                array(
                    'id'      => 'show_portfolio_single_share_like_section',
                    'value'   => '1'
                )
            ),
            'partial'     => array(
                'selector'              => '.single-portfolio .aux-single .content',
                'container_inclusive'   => false,
                'render_callback'       => function(){
                    auxpfo_get_template_part( 'theme-parts/entry/single', 'portfolio');
                }
            ),
            'default'     => '1',
            'type'        => 'switch'
        );

        $options[] = array(
            'title'       => __( 'Like Type', 'auxin-portfolio' ),
            'description' => __( 'Enable it to display text instead of icon.', 'auxin-portfolio' ),
            'id'          => 'portfolio_single_like_button_type',
            'section'     => 'portfolio-section-single',
            'transport'   => 'postMessage',
            'type'        => 'select',
            'choices'     => array(
                'icon'  => __( 'Icon', 'auxin-portfolio' ),
                'text'  => __( 'Text', 'auxin-portfolio' )
            ),
            'dependency'  => array(
                array(
                    'id'      => 'show_portfolio_single_share_like_section',
                    'value'   => array('1'),
                    'operator'=> ''
                ),
                array(
                    'id'      => 'show_portfolio_single_like',
                    'value'   => array('1'),
                    'operator'=> ''
                )
            ),
            'default'     => 'icon',
        );

        $options[] =    array(
            'title'       => __('Show "likes" label ', 'auxin-portfolio'),
            'description' => __( 'Enable to show "Likes" label in front of like icon after clicking on it.', 'auxin-portfolio' ),
            'id'          => 'show_portfolio_single_like_label',
            'section'     => 'portfolio-section-single',
            'dependency'  => '',
            'transport'   => 'postMessage',
            'dependency'  => array(
                array(
                    'id'      => 'show_portfolio_single_share_like_section',
                    'value'   => '1'
                ),
                array(
                    'id'      => 'show_portfolio_single_like',
                    'value'   => array('1'),
                    'operator'=> ''
                ),
                array(
                    'id'      => 'portfolio_single_like_button_type',
                    'value'   => array('icon'),
                    'operator'=> ''
                )
            ),
            'default'     => '1',
            'type'        => 'switch'
        );

        $options[] = array(
            'title'       => __( 'Like Icon', 'auxin-portfolio' ),
            'id'          => 'portfolio_single_like_icon',
            'section'     => 'portfolio-section-single',
            'transport'   => 'refresh',
            'type'        => 'icon',
            'default'     => 'auxicon-heart-2',
            'dependency'  => array(
                array(
                     'id'      => 'show_portfolio_single_share_like_section',
                     'value'   => array('1'),
                     'operator'=> ''
                ),
                array(
                    'id'      => 'show_portfolio_single_like',
                    'value'   => array('1'),
                    'operator'=> ''
                ),
                array(
                    'id'      => 'portfolio_single_like_button_type',
                    'value'   => array('icon'),
                    'operator'=> ''
                )
            )
        );

        $options[] = array(
            'title'         => __( 'Icon Liked Color', 'auxin-portfolio' ),
            'description'   => __( 'Like icon color','auxin-portfolio' ),
            'id'            => 'portfolio_single_like_icon_color',
            'section'       => 'portfolio-section-single',
            'transport'     => 'postMessage',
            'type'          => 'color',
            'selectors'     => '.single-portfolio .wp_ulike_is_liked button::before, .single-portfolio .wp_ulike_is_unliked.wp_ulike_is_liked button::before, .single-portfolio .wp_ulike_is_not_liked.wp_ulike_is_liked button::before',
            'placeholder'   => 'color:{{VALUE}};',
            'default'       => '',
            'dependency'  => array(
                array(
                     'id'      => 'show_portfolio_single_share_like_section',
                     'value'   => array('1'),
                     'operator'=> ''
                ),
                array(
                    'id'      => 'show_portfolio_single_like',
                    'value'   => array('1'),
                    'operator'=> ''
                ),
                array(
                    'id'      => 'portfolio_single_like_button_type',
                    'value'   => array('icon'),
                    'operator'=> ''
                )
            )
        );

        $options[] = array(
            'title'         => __( 'Icon Not Liked Color', 'auxin-portfolio' ),
            'description'   => __( 'Like icon color','auxin-portfolio' ),
            'id'            => 'portfolio_single_not_like_icon_color',
            'section'       => 'portfolio-section-single',
            'transport'     => 'postMessage',
            'type'          => 'color',
            'selectors'     => '.single-portfolio .wp_ulike_is_unliked button::before, .single-portfolio .wp_ulike_is_not_liked button:before ',
            'placeholder'   => 'color:{{VALUE}};',
            'default'       => '',
            'dependency'  => array(
                array(
                     'id'      => 'show_portfolio_single_share_like_section',
                     'value'   => array('1'),
                     'operator'=> ''
                ),
                array(
                    'id'      => 'show_portfolio_single_like',
                    'value'   => array('1'),
                    'operator'=> ''
                ),
                array(
                    'id'      => 'portfolio_single_like_button_type',
                    'value'   => array('icon'),
                    'operator'=> ''
                )
            )
        );

        $options[] = array(
            'title'         => __( 'Icon Hover Color', 'auxin-portfolio' ),
            'description'   => __( 'Like icon hover color','auxin-portfolio' ),
            'id'            => 'portfolio_single_like_icon_hover_color',
            'section'       => 'portfolio-section-single',
            'transport'     => 'postMessage',
            'type'          => 'color',
            'selectors'     => '.single-portfolio .wp_ulike_general_class button:hover::before',
            'placeholder'   => 'color:{{VALUE}};',
            'default'       => '',
            'dependency'  => array(
                array(
                     'id'      => 'show_portfolio_single_share_like_section',
                     'value'   => array('1'),
                     'operator'=> ''
                ),
                array(
                    'id'      => 'show_portfolio_single_like',
                    'value'   => array('1'),
                    'operator'=> ''
                ),
                array(
                    'id'      => 'portfolio_single_like_button_type',
                    'value'   => array('icon'),
                    'operator'=> ''
                )
            )
        );

        $options[] = array(
            'title'       => __( 'Like Button Icon Size', 'auxin-portfolio' ),
            'id'          => 'portfolio_single_like_icon_size',
            'section'     => 'portfolio-section-single',
            'transport'   => 'postMessage',
            'type'        => 'text',
            'dependency'  => array(
                array(
                     'id'      => 'show_portfolio_single_share_like_section',
                     'value'   => array('1'),
                     'operator'=> ''
                ),
                array(
                    'id'      => 'show_portfolio_single_like',
                    'value'   => array('1'),
                    'operator'=> ''
                ),
                array(
                    'id'      => 'portfolio_single_like_button_type',
                    'value'   => array('icon'),
                    'operator'=> ''
                )
            ),
            'style_callback' => function( $value = null ){
                if( ! $value ){
                    $value = esc_attr( auxin_get_option( 'portfolio_single_like_icon_size' ) );
                }
                if( ! is_numeric( $value ) ){
                    $value = 10;
                }
                return $value ? ".single-portfolio .wp_ulike_general_class button::before { font-size:{$value}px; }" : '';
            }
        );

        $options[] = array(
            'title'          => __( 'Like Button Margin', 'auxin-portfolio' ),
            'id'             => 'portfolio_single_like_margin',
            'section'        => 'portfolio-section-single',
            'type'           => 'responsive_dimensions',
            'selectors'      => '.single-portfolio .wp_ulike_general_class button',
            'transport'      => 'postMessage',
            'placeholder'    => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
            'dependency'  => array(
                array(
                     'id'      => 'show_portfolio_single_share_like_section',
                     'value'   => array('1'),
                     'operator'=> ''
                ),
                array(
                    'id'      => 'show_portfolio_single_like',
                    'value'   => array('1'),
                    'operator'=> ''
                ),
                array(
                    'id'      => 'portfolio_single_like_button_type',
                    'value'   => array('icon'),
                    'operator'=> ''
                )
            ),
        );
    }

    $options[] =    array(
        'title'       => __('Skin for Next & Previous Links', 'auxin-portfolio'),
        'description' => __('Specifies the skin for next and previous navigation block.', 'auxin-portfolio'),
        'id'          => 'portfolio_single_next_prev_nav_skin',
        'section'     => 'portfolio-section-single',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_single_next_prev_nav',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'   => 'refresh',
        'choices'     => array(
            'minimal'       => array(
                'label'     => __('Minimal (default)', 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/post-navigation-1.svg'
            ),
            'classic-title'    => array(
                'label'             => __('Classic Project Navigation', 'auxin-portfolio'),
                'image'             => AUXIN_URL . 'images/visual-select/post-navigation-6.svg'
            ),
            'classic'    => array(
                'label'             => __('Classic Project Navigation Without Title', 'auxin-portfolio'),
                'image'             => AUXIN_URL . 'images/visual-select/post-navigation-6.svg'
            ),
            'thumb-arrow'   => array(
                'label'     => __('Thumbnail with Arrow', 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/post-navigation-2.svg'
            ),
            'thumb-no-arrow'        => array(
                'label'             => __('Thumbnail without Arrow', 'auxin-portfolio'),
                'image'             => AUXIN_URL . 'images/visual-select/post-navigation-3.svg'
            ),
            'boxed-image'           => array(
                'label'             => __('Navigation with Light Background', 'auxin-portfolio'),
                'image'             => AUXIN_URL . 'images/visual-select/post-navigation-4.svg'
            ),
            'boxed-image-dark'      => array(
                'label'             => __('Navigation with Dark Background', 'auxin-portfolio'),
                'image'             => AUXIN_URL . 'images/visual-select/post-navigation-5.svg'
            ),
            'thumb-arrow-sticky'    => array(
                'label'             => __('Sticky Thumbnail with Arrow', 'auxin-portfolio'),
                'image'             => AUXIN_URL . 'images/visual-select/post-navigation-6.svg'
            ),
            'modern'    => array(
                'label'             => __('Modern', 'auxin-portfolio'),
                'image'             => AUXIN_URL . 'images/visual-select/post-navigation-2.svg'
            )
        ),
        'type'       => 'radio-image',
        'default'    => 'minimal'
    );

    $options[] =    array(
        'title'       => __('Next Portfolio Label', 'auxin-portfolio'),
        'description' => __('Specifies the word after next and previous navigation.', 'auxin-portfolio'),
        'id'          => 'portfolio_single_next_nav_label',
        'section'     => 'portfolio-section-single',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_single_next_prev_nav',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
        ),
        'transport'  => 'postMessage',
        'post_js'     => '$(".single-portfolio .aux-next-prev-posts .np-next-section .np-nav-text").text( to );',
        'type'       => 'text',
        'default'    => 'Next Portfolio'
    );

    $options[] =    array(
        'title'       => __('Previous Portfolio Label', 'auxin-portfolio'),
        'description' => __('Specifies the word after next and previous navigation.', 'auxin-portfolio'),
        'id'          => 'portfolio_single_prev_nav_label',
        'section'     => 'portfolio-section-single',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_single_next_prev_nav',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
        ),
        'transport'  => 'postMessage',
        'post_js'     => '$(".single-portfolio .aux-next-prev-posts .np-prev-section .np-nav-text").text( to );',
        'type'       => 'text',
        'default'    => 'Previous Portfolio'
    );

    $options[] =    array(
        'title'       => __('Next & Previous Button Link', 'auxin-portfolio'),
        'description' => __('Specifies the link of button in next and previous navigation. leave it blank to use default portfolio archive link', 'auxin-portfolio'),
        'id'          => 'portfolio_single_next_prev_nav_btn_link',
        'section'     => 'portfolio-section-single',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_single_next_prev_nav',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'portfolio_single_next_prev_nav_skin',
                'value'   => array('modern', 'classic', 'classic-title'),
                'operator'=> ''
           )
        ),
        'transport'  => 'refresh',
        'type'       => 'text',
        'default'    => '',
    );


    // Sub section - Portfolio Single Page -------------------------------

    $sections[] = array(
        'id'           => 'portfolio-section-single-titlebar',
        'parent'       => 'portfolio-section', // section parent's id
        'title'        => __( 'Portfolio Title', 'auxin-portfolio' ),
        'description'  => __( 'Preview a Single Portfolio Page', 'auxin-portfolio'),
        'preview_link' => auxin_get_last_post_permalink( array( 'post_type' => 'portfolio' ) )
    );

    $options[] = array(
        'title'         => __( 'Display Title Bar Section', 'auxin-portfolio' ),
        'description'   => __( 'Enable it to show the title section.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_show',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'switch',
        'default'       => '0'
    );

    $options[] = array(
        'title'         => __( 'Layout presets', 'auxin-portfolio' ),
        'description'   => '',
        'id'            => 'portfolio_title_bar_preset',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'radio-image',
        'default'       => 'normal_title_1',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
        'choices'       => array(
            'normal_title_1' => array(
                'label'   => __( 'Default', 'auxin-portfolio' ),
                'image'   => AUXIN_URL . 'images/visual-select/titlebar-style-4.svg',
                'presets' => array(
                    'portfolio_title_bar_content_width_type'      => 'boxed',
                    'portfolio_title_bar_content_section_height'  => 'auto',
                    'portfolio_title_bar_heading_bordered'        => 0,
                    'portfolio_title_bar_heading_boxed'           => 0,
                    'portfolio_title_bar_meta_enabled'            => 0,
                    'portfolio_title_bar_bread_enabled'           => 1,
                    'portfolio_title_bar_bread_bordered'          => 0,
                    'portfolio_title_bar_bread_sep_style'         => 'arrow',
                    'portfolio_title_bar_text_align'              => 'left',
                    'portfolio_title_bar_vertical_align'          => 'top',
                    'portfolio_title_bar_scroll_arrow'            => 'none',
                    'portfolio_title_bar_color_style'             => 'dark',
                    'portfolio_title_bar_overlay_color'           => ''
                )
            ),
            'normal_bg_light_1' => array(
                'label'   => __( 'Title bar with light overlay which is aligned center', 'auxin-portfolio' ),
                'image'   => AUXIN_URL . 'images/visual-select/titlebar-style-1.svg',
                'presets' => array(
                    'portfolio_title_bar_content_width_type'      => 'boxed',
                    'portfolio_title_bar_content_section_height'  => 'auto',
                    'portfolio_title_bar_heading_bordered'        => 0,
                    'portfolio_title_bar_heading_boxed'           => 0,
                    'portfolio_title_bar_bread_enabled'           => 1,
                    'portfolio_title_bar_bread_bordered'          => 0,
                    'portfolio_title_bar_bread_sep_style'         => 'arrow',
                    'portfolio_title_bar_text_align'              => 'center',
                    'portfolio_title_bar_vertical_align'          => 'top',
                    'portfolio_title_bar_scroll_arrow'            => 'none',
                    'portfolio_title_bar_color_style'             => 'dark',
                    'portfolio_title_bar_overlay_color'           => ''
                )
            ),
            'full_bg_light_1' => array(
                'label'   => __( 'Fullscreen title bar with light overlay on background', 'auxin-portfolio' ),
                'image'   => AUXIN_URL . 'images/visual-select/titlebar-style-2.svg',
                'presets' => array(
                    'portfolio_title_bar_content_width_type'      => 'boxed',
                    'portfolio_title_bar_content_section_height'  => 'full',
                    'portfolio_title_bar_heading_bordered'        => 0,
                    'portfolio_title_bar_heading_boxed'           => 0,
                    'portfolio_title_bar_bread_enabled'           => 1,
                    'portfolio_title_bar_bread_bordered'          => 1,
                    'portfolio_title_bar_bread_sep_style'         => 'slash',
                    'portfolio_title_bar_text_align'              => 'center',
                    'portfolio_title_bar_vertical_align'          => 'middle',
                    'portfolio_title_bar_scroll_arrow'            => 'round',
                    'portfolio_title_bar_color_style'             => 'dark',
                    'portfolio_title_bar_overlay_color'           => 'rgba(255,255,255,0.50)'
                )
            ),
            'full_bg_dark_1' => array(
                'label'   => __( 'Fullscreen title bar with dark overlay on background', 'auxin-portfolio' ),
                'image'   => AUXIN_URL . 'images/visual-select/titlebar-style-3.svg',
                'presets' => array(
                    'portfolio_title_bar_content_width_type'      => 'boxed',
                    'portfolio_title_bar_content_section_height'  => 'full',
                    'portfolio_title_bar_heading_bordered'        => 0,
                    'portfolio_title_bar_heading_boxed'           => 0,
                    'portfolio_title_bar_bread_enabled'           => 1,
                    'portfolio_title_bar_bread_bordered'          => 0,
                    'portfolio_title_bar_bread_sep_style'         => 'slash',
                    'portfolio_title_bar_text_align'              => 'center',
                    'portfolio_title_bar_vertical_align'          => 'middle',
                    'portfolio_title_bar_scroll_arrow'            => 'round',
                    'portfolio_title_bar_color_style'             => 'light',
                    'portfolio_title_bar_overlay_color'           => 'rgba(0,0,0,0.6)'
                )
            ),
            'full_bg_dark_2' => array(
                'label'   => __( 'Fullscreen title bar with border around the title', 'auxin-portfolio' ),
                'image'   => AUXIN_URL . 'images/visual-select/titlebar-style-6.svg',
                'presets' => array(
                    'portfolio_title_bar_content_width_type'      => 'boxed',
                    'portfolio_title_bar_content_section_height'  => 'full',
                    'portfolio_title_bar_heading_bordered'        => 1,
                    'portfolio_title_bar_heading_boxed'           => 0,
                    'portfolio_title_bar_bread_enabled'           => 0,
                    'portfolio_title_bar_bread_bordered'          => 1,
                    'portfolio_title_bar_bread_sep_style'         => 'slash',
                    'portfolio_title_bar_text_align'              => 'center',
                    'portfolio_title_bar_vertical_align'          => 'middle',
                    'portfolio_title_bar_scroll_arrow'            => 'round',
                    'portfolio_title_bar_color_style'             => 'dark',
                    'portfolio_title_bar_overlay_color'           => 'rgba(250,250,250,0.3)'
                )
            ),
            'full_bg_dark_3' => array(
                'label'   => __( 'Fullscreen title bar with dark box around the title', 'auxin-portfolio' ),
                'image'   => AUXIN_URL . 'images/visual-select/titlebar-style-7.svg',
                'presets' => array(
                    'portfolio_title_bar_content_width_type'      => 'boxed',
                    'portfolio_title_bar_content_section_height'  => 'full',
                    'portfolio_title_bar_heading_bordered'        => 0,
                    'portfolio_title_bar_heading_boxed'           => 1,
                    'portfolio_title_bar_bread_enabled'           => 0,
                    'portfolio_title_bar_bread_bordered'          => 0,
                    'portfolio_title_bar_bread_sep_style'         => 'slash',
                    'portfolio_title_bar_text_align'              => 'center',
                    'portfolio_title_bar_vertical_align'          => 'middle',
                    'portfolio_title_bar_scroll_arrow'            => 'round',
                    'portfolio_title_bar_color_style'             => 'light',
                    'portfolio_title_bar_overlay_color'           => 'rgba(0,0,0,0.5)'
                )
            ),
            'normal_bg_dark_1' => array(
                'label'   => __( 'Title aligned left with dark overlay on background', 'auxin-portfolio' ),
                'image'   => AUXIN_URL . 'images/visual-select/titlebar-style-5.svg',
                'presets' => array(
                    'portfolio_title_bar_content_width_type'      => 'boxed',
                    'portfolio_title_bar_content_section_height'  => 'auto',
                    'portfolio_title_bar_heading_bordered'        => 0,
                    'portfolio_title_bar_heading_boxed'           => 0,
                    'portfolio_title_bar_bread_enabled'           => 1,
                    'portfolio_title_bar_bread_bordered'          => 0,
                    'portfolio_title_bar_bread_sep_style'         => 'gt',
                    'portfolio_title_bar_text_align'              => 'left',
                    'portfolio_title_bar_vertical_align'          => 'bottom',
                    'portfolio_title_bar_scroll_arrow'            => 'none',
                    'portfolio_title_bar_color_style'             => 'light',
                    'portfolio_title_bar_overlay_color'           => 'rgba(0,0,0,0.3)'
                )
            ),
            'full_bg_dark_4' => array(
                'label'   => __( 'Tile overlaps the title area section and is aligned center', 'auxin-portfolio' ),
                'image'   => AUXIN_URL . 'images/visual-select/titlebar-style-8.svg',
                'presets' => array(
                    'portfolio_title_bar_content_width_type'      => 'boxed',
                    'portfolio_title_bar_content_section_height'  => 'auto',
                    'portfolio_title_bar_heading_bordered'        => 0,
                    'portfolio_title_bar_heading_boxed'           => 1,
                    'portfolio_title_bar_bread_enabled'           => 1,
                    'portfolio_title_bar_bread_bordered'          => 1,
                    'portfolio_title_bar_bread_sep_style'         => 'gt',
                    'portfolio_title_bar_text_align'              => 'center',
                    'portfolio_title_bar_vertical_align'          => 'bottom-overlap',
                    'portfolio_title_bar_scroll_arrow'            => 'none',
                    'portfolio_title_bar_color_style'             => 'light',
                    'portfolio_title_bar_overlay_color'           => 'rgba(0,0,0,0.5)'
                )
            )
        )
    );

    $options[] = array(
        'title'         => __( 'Enable advanced setting', 'auxin-portfolio' ),
        'description'   => __( 'Enable it to customize preset layouts.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_enable_customize',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'switch',
        'default'       => '0',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
    );

    $options[] = array(
        'title'         => __( 'Content Width', 'auxin-portfolio' ),
        'description'   => '',
        'id'            => 'portfolio_title_bar_content_width_type',
        'section'       => 'portfolio-section-single-titlebar',
        'type'          => 'radio-image',
        'default'       => 'boxed',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
        'choices'       => array(
            'boxed' => array(
                'label'     => __( 'Boxed', 'auxin-portfolio' ),
                'css_class' => 'axiAdminIcon-content-boxed',
            ),
            'semi-full' => array(
                'label'     => __( 'Full Width Content with Space on Sides', 'auxin-portfolio' ),
                'css_class' => 'axiAdminIcon-content-full-with-spaces'
            ),
            'full' => array(
                'label'     => __( 'Full Width Content', 'auxin-portfolio' ),
                'css_class' => 'axiAdminIcon-content-full'
            )
        ),
        'transport' => 'postMessage',
        'post_js'   => '$(".page-title-section .page-header").alterClass( "aux-*-container", "aux-"+ to +"-container" );'
    );

    $options[] = array(
        'title'         => __( 'Title Section Height', 'auxin-portfolio' ),
        'description'   => '',
        'id'            => 'portfolio_title_bar_content_section_height',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'select',
        'default'       => 'auto',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
        'choices'       => array(
            'auto'  => __( 'Auto Height', 'auxin-portfolio' ),
            'full'  => __( 'Full Height', 'auxin-portfolio' )
        )
    );

    $options[] = array(
        'title'         => __( 'Vertical Position', 'auxin-portfolio' ),
        'description'   => __( 'Specifies vertical alignment of title and subtitle.', 'auxin-portfolio' ) . "<br/>".
                           __( 'Note: Parallax feature in not available for "Bottom Overlap" vertical mode.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_vertical_align',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'select',
        'default'       => '',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
        'choices'            => array(
            'top'            => __( 'Top'    , 'auxin-portfolio' ),
            'middle'         => __( 'Middle' , 'auxin-portfolio' ),
            'bottom'         => __( 'Bottom' , 'auxin-portfolio' ),
            'bottom-overlap' => __( 'Bottom Overlap', 'auxin-portfolio' )
        )
    );

    $options[] = array(
        'title'         => __( 'Scroll Down Arrow', 'auxin-portfolio' ),
        'description'   => __( 'This option only applies if section height is "Full Height".', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_scroll_arrow',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'radio-image',
        'default'       => '',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_content_section_height',
                 'value'   => 'full',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_vertical_align',
                 'value'   => array('top', 'middle', 'bottom'),
                 'operator'=> '=='
            )
        ),
        'choices'       => array(
            'none' => array(
                'label'     => __( 'None', 'auxin-portfolio' ),
                'css_class' => 'axiAdminIcon-none'
            ),
            'round' => array(
                'label'     => __( 'Round', 'auxin-portfolio' ),
                'css_class' => 'axiAdminIcon-scroll-down-arrow-outline'
            )
        )
    );

    $options[] = array(
        'title'         => __( 'Display Titles', 'auxin-portfolio' ),
        'description'   => __( 'Enable it to display title/subtitle in title section.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_title_show',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'switch',
        'default'       => '1',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
    );

    $options[] = array(
        'title'         => __( 'Border for Heading', 'auxin-portfolio' ),
        'description'   => __( 'Enable it to display a border around the title and subtitle area.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_heading_bordered',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'switch',
        'default'       => '0',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_title_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
    );

    $options[] = array(
        'title'         => __( 'Boxed Title', 'auxin-portfolio' ),
        'description'   => __( 'Enable it to wrap the title and subtitle in a box with background color.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_heading_boxed',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'switch',
        'default'       => '0',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_title_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
    );

    $options[] = array(
        'title'         => __( 'Title Box Custom Color', 'auxin-portfolio' ),
        'description'   => __( 'Specifies a custom background color for the box around the title and subtitle.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_heading_bg_color',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'color',
        'selectors'     => ' ',
        'default'       => '',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_title_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_heading_boxed',
                 'value'   => '1',
                 'operator'=> '=='
            )
        )
    );

    $options[] = array(
        'title'         => __( 'Display Post Meta', 'auxin-portfolio' ),
        'description'   => __( 'Enable it to display post meta information on title section.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_meta_enabled',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'switch',
        'default'       => '0',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
    );

    $options[] = array(
        'title'         => __( 'Display Breadcrumb', 'auxin-portfolio' ),
        'description'   => __( 'Enable it to display breadcrumb on title section.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_bread_enabled',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'switch',
        'default'       => '1',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
    );

    $options[] = array(
        'title'         => __( 'Border for Breadcrumb', 'auxin-portfolio' ),
        'description'   => __( 'Enable it to display border around breadcrumb.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_bread_bordered',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'switch',
        'default'       => '0',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_bread_enabled',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
    );

    $options[] = array(
        'title'       => __( 'Breadcrumb Separator Icon', 'auxin-portfolio' ),
        'description' => '',
        'id'          => 'portfolio_title_bar_bread_sep_style',
        'section'     => 'portfolio-section-single-titlebar',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_bread_enabled',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
        'default'     => 'auxicon-chevron-right-1',
        'transport'   => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'        => 'icon'
    );

    $options[] = array(
        'title'         => __( 'Text Align', 'auxin-portfolio' ),
        'description'   => '',
        'id'            => 'portfolio_title_bar_text_align',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'radio-image',
        'default'       => 'left',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
        'choices'       => array(
            'left' => array(
                'label'     => __( 'Left', 'auxin-portfolio' ),
                'css_class' => 'axiAdminIcon-text-align-left',
            ),
            'center' => array(
                'label'     => __( 'Center', 'auxin-portfolio' ),
                'css_class' => 'axiAdminIcon-text-align-center'
            ),
            'right' => array(
                'label'     => __( 'Right', 'auxin-portfolio' ),
                'css_class' => 'axiAdminIcon-text-align-right'
            )
        )
    );

    $options[] = array(
        'title'         => __( 'Overlay Color', 'auxin-portfolio' ),
        'description'   => __( 'The color that overlay on the background. Please note that color should have transparency.','auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_overlay_color',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'color',
        'selectors'     => ' ',
        'default'       => '',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        )
    );

    $options[] = array(
        'title'         => __( 'Overlay Pattern', 'auxin-portfolio' ),
        'description'   => '',
        'id'            => 'portfolio_title_bar_overlay_pattern',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'radio-image',
        'default'       => 'none',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
        'choices'       => array(
            'none' => array(
                'label'     => __( 'None', 'auxin-portfolio' ),
                'css_class' => 'axiAdminIcon-none'
            ),
            'hash' => array(
                'label'     => __( 'Hash', 'auxin-portfolio' ),
                'css_class' => 'axiAdminIcon-pattern',
            )
        )
    );

    $options[] = array(
        'title'         => __( 'Overlay Pattern Opacity', 'auxin-portfolio' ),
        'description'   => '',
        'id'            => 'portfolio_title_bar_overlay_pattern_opacity',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'type'          => 'text',
        'default'       => '0.5',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_overlay_pattern',
                 'value'   => array('hash'),
                 'operator'=> '=='
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = esc_attr( auxin_get_option( 'portfolio_title_bar_overlay_pattern_opacity' ) );
            }
            if( ! is_numeric( $value ) || (float) $value > 1 ){
                $value = 1;
            }
            return $value ? ".single-portfolio .aux-overlay-bg-hash::before { opacity:$value; }" : '';
        }
    );

    $options[] = array(
        'title'         => __( 'Color Mode', 'auxin-portfolio' ),
        'description'   => '',
        'id'            => 'portfolio_title_bar_color_style',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'select',
        'default'       => 'dark',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
        'choices'       => array(
            'dark'  => __( 'Dark', 'auxin-portfolio' ),
            'light' => __( 'Light', 'auxin-portfolio' )
        )
    );

    ////////////////////////////////////////////////////////////////////////////////////////

    $options[] = array(
        'title'         => __( 'Enable Title Background', 'auxin-portfolio' ),
        'description'   => __( 'Enable it to display custom background for title section.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_bg_show',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'switch',
        'default'       => '0',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            )
        )
    );

    $options[] = array(
        'title'         => __( 'Enable Parallax Effect', 'auxin-portfolio' ),
        'description'   => __( 'Enable it to have parallax background effect on this section.', 'auxin-portfolio' )."<br />".
                           __( 'Note: Parallax feature in not available for "Bottom Overlap" mode for "Vertical Position" option.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_bg_parallax',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'switch',
        'default'       => '0',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_bg_show',
                 'value'   => '1',
                 'operator'=> '=='
            )
        )
    );

    $options[] = array(
        'title'         => __( 'Background Color', 'auxin-portfolio' ),
        'description'   => __( 'Specifies a background color for title bar.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_bg_color',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'color',
        'selectors'     => ' ',
        'default'       => '',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_bg_show',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),

    );

    $options[] = array(
        'title'         => __( 'Background Size', 'auxin-portfolio' ),
        'description'   => __( 'Specifies the background size.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_bg_size',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'radio-image',
        'default'       => '',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_bg_show',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),
        'choices' => array(
            'auto' => array(
                'label'       => __( 'Auto', 'auxin-portfolio' ),
                'css_class'   => 'axiAdminIcon-bg-size-1',
            ),
            'contain' => array(
                'label'       => __( 'Contain', 'auxin-portfolio' ),
                'css_class'   => 'axiAdminIcon-bg-size-2',
            ),
            'cover' => array(
                'label'       => __( 'Cover', 'auxin-portfolio' ),
                'css_class'   => 'axiAdminIcon-bg-size-3',
            )
        ),

    );

    $options[] = array(
        'title'         => __( 'Background Image', 'auxin-portfolio' ),
        'description'   => __( 'Specifies a background image for title bar.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_bg_image',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'image',
        'default'       => '',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_bg_show',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),

    );

    $options[] = array(
        'title'         => __( 'Background Video MP4', 'auxin-portfolio' ),
        'description'   => __( 'You can upload custom video for title background</br>Note: if you set custom image, default image backgrounds will be ignored.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_bg_video_mp4',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'video',
        'default'       => '',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_bg_show',
                 'value'   => '1',
                 'operator'=> '=='
            )
        )

    );

    $options[] = array(
        'title'         => __( 'Background Video Ogg', 'auxin-portfolio' ),
        'description'   => __( 'You can upload custom video for title background</br>Note: if you set custom image, default image backgrounds will be ignored.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_bg_video_ogg',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'video',
        'default'       => '',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_bg_show',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),

    );

    $options[] = array(
        'title'         => __( 'Background Video WebM', 'auxin-portfolio' ),
        'description'   => __( 'You can upload custom video for title background</br>Note: if you set custom image, default image backgrounds will be ignored.', 'auxin-portfolio' ),
        'id'            => 'portfolio_title_bar_bg_video_webm',
        'section'       => 'portfolio-section-single-titlebar',
        'transport'     => 'postMessage',
        'partial'       => array(
            'selector'              => '.single-portfolio .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'type'          => 'video',
        'default'       => '',
        'dependency'    => array(
            array(
                 'id'      => 'portfolio_title_bar_enable_customize',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_show',
                 'value'   => '1',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'portfolio_title_bar_bg_show',
                 'value'   => '1',
                 'operator'=> '=='
            )
        ),

    );


    // Sub section - related portfolios section -------------------------------

    $sections[] = array(
        'id'          => 'portfolio-section-single-related',
        'parent'      => 'portfolio-section', // section parent's id
        'title'       => __( 'Related Portfolios', 'auxin-portfolio'),
        'description' => __( 'Setting for Related Portfolios Section in Single Page', 'auxin-portfolio')
    );


    $options[] = array(
        'title'       => __( 'Display Related Portfolios', 'auxin-portfolio' ),
        'description' => __( 'Enable it to display related portfolios section on single portfolio page.' ),
        'id'          => 'show_portfolio_related_posts',
        'section'     => 'portfolio-section-single-related',
        'dependency'  => '',
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-portfolio .aux-widget-related-posts, .single-portfolio .aux-related-btn-more").toggle( to );',
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'          => __( 'Title Typography', 'auxin-portfolio' ),
        'id'             => 'portfolio_related_posts_title_typography',
        'section'        => 'portfolio-section-single-related',
        'type'           => 'group_typography',
        'selectors'      => '.single-portfolio .aux-widget-related-posts .hentry .entry-title a',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'      => 'postMessage',
    );

    $options[] = array(
        'title'       => __('Label of Related Section', 'auxin-portfolio'),
        'description' => __('Specifies the label of related items section.', 'auxin-portfolio'),
        'id'          => 'portfolio_related_posts_label',
        'section'     => 'portfolio-section-single-related',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-portfolio .aux-widget-related-posts > .widget-title").html( to );',
        'default'     => __( 'Related Projects/Works', 'auxin-portfolio' ),
        'type'        => 'text'
    );

    $options[] = array(
        'title'          => __( 'Label Typography', 'auxin-portfolio' ),
        'id'             => 'portfolio_related_posts_label_typography',
        'section'        => 'portfolio-section-single-related',
        'type'           => 'group_typography',
        'selectors'      => '.single-portfolio .aux-widget-related-posts .widget-title',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'      => 'postMessage',
    );


    $options[] = array(
        'title'       => __( 'Image aspect ratio', 'auxin-portfolio' ),
        'description' => '',
        'id'          => 'portfolio_related_image_aspect_ratio',
        'section'     => 'portfolio-section-single-related',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'type'        => 'select',
        'choices'     => array(
            '0.75'          => __( 'Horizontal 4:3' , 'auxin-portfolio' ),
            '0.56'          => __( 'Horizontal 16:9', 'auxin-portfolio' ),
            '1.00'          => __( 'Square 1:1'     , 'auxin-portfolio' ),
            '1.33'          => __( 'Vertical 3:4'   , 'auxin-portfolio' )
        ),
        'transport'   => 'refresh',
        'default'     => '0.56',
    );

    $options[] =    array(
        'title'       => __('Related Items Type', 'auxin-portfolio'),
        'description' => __('Specifies the appearance type for related portfolio element.', 'auxin-portfolio'),
        'id'          => 'portfolio_related_posts_preview_mode',
        'section'     => 'portfolio-section-single-related',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'   => 'refresh',
        'choices'     => array(
            'grid'      => 'Grid',
            'carousel'  => 'Carousel'
        ),
        'type'        => 'select',
        'default'     => 'grid'
    );

    $options[] =    array(
        'title'       => __('Number of Columns', 'auxin-portfolio'),
        'description' => '',
        'id'          => 'portfolio_related_posts_column_number',
        'section'     => 'portfolio-section-single-related',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'   => 'refresh',
        'type'        => 'select',
        'choices'     => array(
                    '2'  => '2', '3' => '3', '4' => '4',
        ),
        'default'     => '3'
    );

    $options[] =    array(
        'title'       => __('Align Center', 'auxin-portfolio'),
        'description' => __( 'Enable it to make related portfolios section text center.'),
        'id'          => 'portfolio_related_posts_align_center',
        'section'     => 'portfolio-section-single-related',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-portfolio .aux-widget-related-posts").toggleClass( "aux-text-align-center", to );',
        'default'     => '0',
        'type'        => 'switch'
    );

    $options[] =    array(
        'title'       => __('Full Width Related Section', 'auxin-portfolio'),
        'description' => __( 'Enable it to make related portfolios section full width.' ),
        'id'          => 'portfolio_related_posts_full_width',
        'section'     => 'portfolio-section-single-related',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-portfolio .aux-widget-related-posts").closest(".aux-container").toggleClass( "aux-fold", ! to );',
        'default'     => '0',
        'type'        => 'switch'
    );

    $options[] =    array(
        'title'       => __('Snap Related Items', 'auxin-portfolio'),
        'description' => __( 'Enable it to remove space between related portfolio items.' ),
        'id'          => 'portfolio_related_posts_snap_items',
        'section'     => 'portfolio-section-single-related',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'   => 'refresh',
        // 'post_js'     => '$(".single-portfolio .aux-widget-related-posts > .aux-row").toggleClass( "aux-no-gutter", to );',
        'default'     => '0',
        'type'        => 'switch'
    );

    $options[] =    array(
        'title'       => __('Display Portfolio Categories', 'auxin-portfolio'),
        'description' => __( 'Enable it to display the categories of each portfolio item in related portfolios section.'),
        'id'          => 'portfolio_related_posts_display_taxonomies',
        'section'     => 'portfolio-section-single-related',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'   => 'refresh',
        // 'post_js'     => '$(".single-portfolio .aux-widget-related-posts .entry-tax").toggle( to );',
        'default'     => '0',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'          => __( 'Category Terms Typography', 'auxin-portfolio' ),
        'id'             => 'portfolio_related_posts_terms_typography',
        'section'        => 'portfolio-section-single-related',
        'type'           => 'group_typography',
        'selectors'      => '.single-portfolio .aux-widget-related-posts .hentry .entry-tax a',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'portfolio_related_posts_display_taxonomies',
                'value'   => array('1'),
                'operator'=> ''
           )
        ),
        'transport'      => 'postMessage',
    );

    $options[] =    array(
        'title'       => __('Display The Button Under Related Items', 'auxin-portfolio'),
        'description' => __('You can specific to show the button under related items', 'auxin-portfolio'),
        'id'          => 'portfolio_single_all_related_items_btn_display',
        'section'     => 'portfolio-section-single-related',
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-portfolio .aux-related-btn-more").toggle( to );',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'type'        => 'switch',
        'default'     => '0'
    );

    $options[] =    array(
        'title'       => __('Link the Button Under Related Items To', 'auxin-portfolio'),
        'description' => __('Whether to display a button bellow related items section in order to direct visitors to portfolio archive page or not. You can link the button to the portfolio archive page or a custom page, or hide the button.', 'auxin-portfolio'),
        'id'          => 'portfolio_single_all_related_items_url_type',
        'section'     => 'portfolio-section-single-related',
        'transport'   => 'refresh',
        'choices'     => array(
            'hide'    => __( 'Hide it', 'auxin-portfolio' ),
            'archive' => __( 'Archive page', 'auxin-portfolio' ),
            'custom'  => __( 'Custom URL', 'auxin-portfolio' ),
        ),
        'dependency'  => array(
            array(
                 'id'       => 'portfolio_single_all_related_items_btn_display',
                 'value'    => array('1'),
            ),
            array(
                'id'      => 'show_portfolio_related_posts',
                'value'   => array('1'),
                'operator'=> ''
            )
        ),
        'type'        => 'select',
        'default'     => 'archive'
    );

    $options[] =    array(
        'title'       => __('Custom Link for Related Items Button', 'auxin-portfolio'),
        'description' => __('A custom link for the button under related items section.', 'auxin-portfolio'),
        'id'          => 'portfolio_single_all_related_items_btn_url',
        'section'     => 'portfolio-section-single-related',
        'dependency'  => array(
            array(
                 'id'       => 'portfolio_single_all_related_items_url_type',
                 'value'    => array('custom'),
                 'operator' => ''
            ),
            array(
                'id'       => 'portfolio_single_all_related_items_btn_display',
                'value'    => array('1'),
            ),
            array(
                'id'      => 'show_portfolio_related_posts',
                'value'   => array('1'),
                'operator'=> ''
            )
        ),
        'transport'   => 'refresh',
        'type'        => 'text',
        'default'     => ''
    );

    $options[] =    array(
        'title'       => __('Custom label for Related Items Button', 'auxin-portfolio'),
        'description' => __('A custom label for the button under related items section.', 'auxin-portfolio'),
        'id'          => 'portfolio_single_all_related_items_btn_label',
        'section'     => 'portfolio-section-single-related',
        'dependency'  => array(
            array(
                 'id'       => 'portfolio_single_all_related_items_url_type',
                 'value'    => array('custom', 'archive'),
                 'operator' => ''
            ),
            array(
                'id'       => 'portfolio_single_all_related_items_btn_display',
                'value'    => array('1'),
            ),
            array(
                'id'      => 'show_portfolio_related_posts',
                'value'   => array('1'),
                'operator'=> ''
            )
        ),
        'transport'   => 'refresh',
        'type'        => 'text',
        'default'     => __( "Browse All Projects", 'auxin-portfolio' )
    );

    $options[] = array(
        'title'          => __( 'Button Typography', 'auxin-portfolio' ),
        'id'             => 'portfolio_related_posts_button_typography',
        'section'        => 'portfolio-section-single-related',
        'type'           => 'group_typography',
        'selectors'      => '.single-portfolio .aux-related-container-more .aux-related-btn-more',
        'dependency'  => array(
            array(
                 'id'      => 'show_portfolio_related_posts',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
            array(
                'id'      => 'portfolio_single_all_related_items_btn_display',
                'value'   => array('1'),
                'operator'=> ''
           )
        ),
        'transport'      => 'postMessage',
    );

    $options[] = array(
        'title'       => __( 'Button Background', 'auxin-portfolio' ),
        'id'          => 'portfolio_related_posts_button_bg',
        'section'     => 'portfolio-section-single-related',
        'transport'      => 'postMessage',
        'selectors'   => array(
            '.single-portfolio .aux-related-container-more .aux-related-btn-more' => 'background-image:{{VALUE}};'
        ),
        'default'   => '',
        'type'      => 'gradient'
    );

    /*$options[] = array( 'title'     => __('View All button link', 'auxin-portfolio'),
                        'description'   => __('Specifies a link for "view all" button to portfolio listing page (the button that comes at the end of latest from portfolio element ) ', 'auxin-portfolio'),
                        'id'        => 'portfolio_view_all_btn_link',
                        'section'   => 'portfolio-section-single',
                        'dependency'=> array(),
                        'default'   => home_url(),
                        'type'      => 'text' );*/



    // Sub section - portfolio Archive Page -------------------------------

    $sections[] = array(
        'id'           => 'portfolio-section-archive',
        'parent'       => 'portfolio-section', // section parent's id
        'title'        => __( 'Portfolio Page', 'auxin-portfolio'),
        'description'  => __( 'Preview Portfolio Page', 'auxin-portfolio'),
        'preview_link' => auxin_get_post_type_archive_shortlink('portfolio')
    );

    $options[] =    array(
        'title'       => __( 'Custom Page For Archive', 'auxin-portfolio' ),
        'description' => __( 'Enable this option to select custom page for archive page', 'auxin-portfolio' ),
        'id'          => 'portfolio_show_custom_archive_link',
        'section'     => 'portfolio-section-archive',
        'transport'   => 'postMessage',
        'type'        => 'switch',
        'default'     => '0'
    );

    $options[] = array(
        'title'       => __( 'Select Page', 'auxin-portfolio' ),
        'id'          => 'portfolio_custom_archive_link',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_show_custom_archive_link',
                'value'   => '1',
                'operator'=> '=='
            )
        ),
        'type'        => 'select',
        'choices'     => auxin_list_pages(),
        'transport'   => 'postMessage'
    );

    $options[] = array(
        'title'       => __('Portfolio Template', 'auxin-portfolio'),
        'description' => __('Choose your portfolio template.', 'auxin-portfolio'),
        'id'          => 'portfolio_index_template_type',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'choices'     => array(
             // default template
            'grid-1'        => array(
                'label'     => __('Grid' , 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/portfolio-grid.svg'
            ),
            'masonry-1'     => array(
                'label'     => __('Masonry' , 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/portfolio-masonry.svg'
            ),
            'tiles-1'       => array(
                'label'     => __('Tiles' , 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/blog-layout-11.svg'
            ),
            'land-1'        => array(
                'label'     => __('Land', 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/blog-layout-10.svg'
            )
        ),
        'type'         => 'radio-image',
        'default'      => 'grid-1'
    );

     $options[] = array(
        'title'       => __('Image Aspect Ratio', 'auxin-portfolio'),
        'description' => '',
        'id'          => 'portfolio_image_aspect_ratio',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_index_template_type',
                'value'   => array('grid-1'),
                'operator'=> '=='
            )
        ),
        'type'        => 'select',
        'choices'     => array(
            '0.75'          => __('Horizontal 4:3' , 'auxin-portfolio'),
            '0.56'          => __('Horizontal 16:9', 'auxin-portfolio'),
            '1.00'          => __('Square 1:1'     , 'auxin-portfolio'),
            '1.33'          => __('Vertical 3:4'   , 'auxin-portfolio')
        ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.post-type-archive-portfolio .aux-archive .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/loop', 'portfolio' );
            }
        ),
        'default'     => '0.56',
    );

    $options[] = array(
        'title'       => __('Portfolio Hover Type', 'auxin-portfolio'),
        'description' => __('Hover over images to see the animation.', 'auxin-portfolio'),
        'id'          => 'portfolio_archive_grid_item_type',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
                array(
                    'id'      => 'portfolio_index_template_type',
                    'value'   => array('grid-1', 'masonry-1'),
                    'operator'=> '=='
                )
        ),
        'type'        => 'radio-image',
        'choices'     => array(
             // default template
            'classic'       => array(
                'label'     => __('No animation' , 'auxin-portfolio'),
                'image'     => AUXPFO_ADMIN_URL . '/assets/images/ClassicLightbox.png',
                'css_class' => 'aux-small-height'
            ),
            'classic-lightbox'     => array(
                'label'     => __('Classic with lightbox style 1' , 'auxin-portfolio'),
                'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/ClassicLightbox1.webm webm',
                'css_class' => 'aux-small-height'
            ),
            'classic-lightbox-boxed'       => array(
                'label'     => __('Classic with lightbox style 2' , 'auxin-portfolio'),
                'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/ClassicLightbox2.webm webm',
                'css_class' => 'aux-small-height'
            ),
            'overlay'       => array(
                'label'     => __('Overlay title style 1', 'auxin-portfolio'),
                'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitle1.webm webm',
                'css_class' => 'aux-small-height'
            ),
            'overlay-boxed' => array(
                'label'     => __('Overlay title style 2', 'auxin-portfolio'),
                'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitle2.webm webm',
                'css_class' => 'aux-small-height'
            ),
            'overlay-lightbox' => array(
                'label'     => __('Overlay title with lightbox style 1', 'auxin-portfolio'),
                'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitleLightbox1.webm webm',
                'css_class' => 'aux-small-height'
            ),
            'overlay-lightbox-boxed' => array(
                'label'     => __('Overlay title with lightbox style 2', 'auxin-portfolio'),
                'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitleLightbox2.webm webm',
                'css_class' => 'aux-small-height'
            )
        ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.post-type-archive-portfolio .aux-archive .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/loop', 'portfolio' );
            }
        ),
        'default'     => 'classic',
    );

    $options[] = array(
        'title'       => __('Tile Portfolio Item Type', 'auxin-portfolio'),
        'description' => __('Hover over images to see the animation.', 'auxin-portfolio'),
        'id'          => 'portfolio_archive_tile_item_type',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
               'id'      => 'portfolio_index_template_type',
               'value'   => array('tiles-1'),
               'operator'=> '=='
            )
        ),
        'type'        => 'radio-image',
        'choices'     => array(
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
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.post-type-archive-portfolio .aux-archive .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/loop', 'portfolio' );
            }
        ),
        'default'     => 'overlay',
    );

    $options[] = array(
        'title'       => __('Space', 'auxin-portfolio'),
        'description' => __('Specifies horizontal space between items (pixel).', 'auxin-portfolio'),
        'id'          => 'portfolio_archive_grid_space',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
                array(
                    'id'      => 'portfolio_index_template_type',
                    'value'   => array('grid-1', 'masonry-1'),
                    'operator'=> '=='
                )
        ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.post-type-archive-portfolio .aux-archive .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/loop', 'portfolio' );
            }
        ),
        'default'     => '30',
        'type'        => 'text'
    );



    $options[] = array(
        'title'       => __('Number of Columns', 'auxin-portfolio'),
        'description' => '',
        'id'          => 'portfolio_archive_column_number',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_index_template_type',
                'value'   => array('grid-1', 'masonry-1'),
                'operator'=> '=='
            )
        ),
        'type'        => 'select',
        'choices'     => array(
                    '1'  => '1', '2' => '2', '3' => '3',
                    '4'  => '4', '5' => '5', '6' => '6'
                ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.post-type-archive-portfolio .aux-archive .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/loop', 'portfolio' );
            }
        ),
        'default'     => '4',
    );

    $options[] = array(
        'title'       => __( 'Custom Max Width', 'auxin-portfolio' ),
        'description' => __( 'Specifies the maximum width of website.', 'auxin-portfolio' ),
        'id'          => 'portfolio_archive_max_width_layout',
        'section'     => 'portfolio-section-archive',
        'type'        => 'select',
        'transport'   => 'postMessage',
        'dependency'  => array(),
        'choices'     => array(
            ''      => __( 'Default Site Max Width', 'auxin-portfolio' ),
            'nd'    => __( '1000 Pixels', 'auxin-portfolio' ),
            'hd'    => __( '1200 Pixels', 'auxin-portfolio' ),
            'xhd'   => __( '1400 Pixels', 'auxin-portfolio' ),
            's-fhd' => __( '1600 Pixels', 'auxin-portfolio' ),
            'fhd'   => __( '1900 Pixels', 'auxin-portfolio' )
        ),
        'post_js'   => '$( "body.post-type-archive-portfolio" ).removeClass( "aux-nd aux-hd aux-xhd aux-s-fhd aux-fhd" ).addClass( "aux-" + to ); $(window).trigger("resize");',
        'default'   => ''
    );

    $options[] = array(
        'title'       => __('Number of Columns in Tablet', 'auxin-portfolio'),
        'description' => '',
        'id'          => 'portfolio_archive_column_number_tablet',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_index_template_type',
                'value'   => array('grid-1', 'masonry-1'),
                'operator'=> '=='
            )
        ),
        'type'        => 'select',
        'choices'     => array(
            'inherit' => 'Inherited from larger',
            '1'  => '1', '2' => '2', '3' => '3',
            '4'  => '4', '5' => '5', '6' => '6'
        ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.post-type-archive-portfolio .aux-archive .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/loop', 'portfolio' );
            }
        ),
        'default'     => 'inherit',
    );

    $options[] = array(
        'title'       => __('Number of Columns in Mobile', 'auxin-portfolio'),
        'description' => '',
        'id'          => 'portfolio_archive_column_number_mobile',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_index_template_type',
                'value'   => array('grid-1', 'masonry-1'),
                'operator'=> '=='
            )
        ),
        'type'        => 'select',
        'choices'     => array(
                    '1' => '1' , '2' => '2', '3' => '3'
                ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.post-type-archive-portfolio .aux-archive .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/loop', 'portfolio' );
            }
        ),
        'default'     => '1',
    );

    if ( auxin_is_plugin_active( 'wp-ulike/wp-ulike.php')){
        $options[] = array(
            'title'       => __('Display Like Button', 'auxin-portfolio'),
            'description' => sprintf(__('Enable it to display %s like button%s on portfolio portfolios. Please note WP Ulike plugin needs to be activaited to use this option.', 'auxin-portfolio'), '<strong>', '</strong>'),
            'id'          => 'show_portfolio_archive_like_button',
            'section'     => 'portfolio-section-archive',
            'dependency'  => array(
                array(
                    'id'      => 'portfolio_index_template_type',
                    'value'   => array('tiles-1'),
                    'operator'=> '!='
                )
            ),
            'transport'   => 'postMessage',
            'partial'     => array(
                'selector'              => '.post-type-archive-portfolio .aux-archive .content',
                'container_inclusive'   => false,
                'render_callback'       => function(){
                    auxpfo_get_template_part( 'theme-parts/loop', 'portfolio' );
                }
            ),
            'default'     => '1',
            'type'        => 'switch'
        );
    }

    $options[] = array(
        'title'       => __('Enable Entry Box Coloring', 'auxin-portfolio'),
        'description' => __( 'Specifies the border/background color for entry box.', 'auxin-portfolio' ),
        'id'          => 'show_portfolio_entry_box_colors',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_archive_grid_item_type',
                'value'   => array( 'classic', 'classic-lightbox', 'classic-lightbox-boxed' ),
                'operator'=> '=='
            ),
            array(
                'id'      => 'portfolio_index_template_type',
                'value'   => array('grid-1', 'masonry-1'),
                'operator'=> '=='
            )
        ),
        'transport'   => 'postMessage',
        'default'     => '0',
        'post_js'     => '$(".aux-widget-recent-portfolios .type-portfolio").toggleClass( "aux-entry-boxed", 1 == to );',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __( 'Entry Box Background Color', 'auxin-portfolio' ),
        'id'          => 'portfolio_classic_entry_box_background_color',
        'description' => __( 'Specifies the background color for entry box.', 'auxin-portfolio' ),
        'section'     => 'portfolio-section-archive',
        'type'        => 'color',
        'selectors'   => '.post-type-archive-portfolio .aux-entry-boxed .entry-main',
        'placeholder' => 'background-color:{{VALUE}};',
        'dependency'  => array(
            array(
                'id'      => 'show_portfolio_entry_box_colors',
                'value'   => 1,
                'operator'=> '=='
            ),
            array(
                'id'      => 'portfolio_archive_grid_item_type',
                'value'   => array( 'classic', 'classic-lightbox', 'classic-lightbox-boxed' ),
                'operator'=> '=='
            ),
            array(
                'id'      => 'portfolio_index_template_type',
                'value'   => array('grid-1', 'masonry-1'),
                'operator'=> '=='
            )
        ),
        'transport' => 'postMessage',
        'default'   => '#FFFFFF'
    );

    $options[] = array(
        'title'       => __( 'Entry Box Border Color', 'auxin-portfolio' ),
        'id'          => 'portfolio_classic_entry_box_border_color',
        'description' => __( 'Specifies the border color for entry box.', 'auxin-portfolio' ),
        'section'     => 'portfolio-section-archive',
        'type'        => 'color',
        'selectors'   => '.post-type-archive-portfolio .aux-entry-boxed .entry-main',
        'placeholder' => 'border-color:{{VALUE}} !important;',
        'dependency'  => array(
            array(
                'id'      => 'show_portfolio_entry_box_colors',
                'value'   => 1,
                'operator'=> '=='
            ),
            array(
                'id'      => 'portfolio_archive_grid_item_type',
                'value'   => array( 'classic', 'classic-lightbox', 'classic-lightbox-boxed' ),
                'operator'=> '=='
            ),
            array(
                'id'      => 'portfolio_index_template_type',
                'value'   => array('grid-1', 'masonry-1'),
                'operator'=> '=='
            )
        ),
        'transport' => 'postMessage',
        'default'   => '#EAEAEA'
    );

    $options[] = array(
        'title'       => __('Enable Entry Box Coloring', 'auxin-portfolio'),
        'description' => __( 'Specifies the border/background color for entry box.', 'auxin-portfolio' ),
        'id'          => 'show_portfolio_land_side_entry_box_colors',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_index_template_type',
                'value'   => array('land-1'),
                'operator'=> '=='
            )
        ),
        'transport'   => 'postMessage',
        'default'     => '0',
        'post_js'     => '$(".aux-portfolio-land").toggleClass( "aux-item-land", 1 == to );',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __( 'Entry Box Background Color', 'auxin-portfolio' ),
        'id'          => 'portfolio_land_side_background_color',
        'description' => __( 'Specifies the background color for entry box.', 'auxin-portfolio' ),
        'section'     => 'portfolio-section-archive',
        'type'        => 'color',
        'selectors'   => '.post-type-archive-portfolio .aux-item-land .aux-land-side',
        'placeholder' => 'background-color:{{VALUE}};',
        'dependency'  => array(
            array(
                'id'      => 'show_portfolio_land_side_entry_box_colors',
                'value'   => 1,
                'operator'=> '=='
            ),
            array(
                'id'      => 'portfolio_index_template_type',
                'value'   => array('land-1'),
                'operator'=> '=='
            )
        ),
        'transport' => 'postMessage',
        'default'   => '#FFFFFF'
    );

    $options[] = array(
        'title'       => __( 'Entry Box Border Color', 'auxin-portfolio' ),
        'id'          => 'portfolio_land_side_border_color',
        'description' => __( 'Specifies the border color for entry box.', 'auxin-portfolio' ),
        'section'     => 'portfolio-section-archive',
        'type'        => 'color',
        'selectors'   => '.post-type-archive-portfolio .aux-item-land .aux-land-side',
        'placeholder' => 'border-color:{{VALUE}} !important;',
        'dependency'  => array(
            array(
                'id'      => 'show_portfolio_land_side_entry_box_colors',
                'value'   => 1,
                'operator'=> '=='
            ),
            array(
                'id'      => 'portfolio_index_template_type',
                'value'   => array('land-1'),
                'operator'=> '=='
            )
        ),
        'transport' => 'postMessage',
        'default'   => '#EAEAEA'
    );

    $options[] = array(
        'title'       => __('Portfolio Sidebar Position', 'auxin-portfolio'),
        'description' => __('Specifies the position of sidebar on portfolio page.', 'auxin-portfolio'),
        'id'          => 'portfolio_index_sidebar_position',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(),
        'choices'     => array(
            'no-sidebar'            => array(
                'label'             => __('No Sidebar', 'auxin-portfolio'),
                'css_class'         => 'axiAdminIcon-sidebar-none'
            ),
            'right-sidebar'         => array(
                'label'             => __('Right Sidebar', 'auxin-portfolio'),
                'css_class'         => 'axiAdminIcon-sidebar-right'
            ),
            'left-sidebar'          => array(
                'label'             => __('Left Sidebar' , 'auxin-portfolio'),
                'css_class'         => 'axiAdminIcon-sidebar-left'
            ),
            'left2-sidebar'         => array(
                'label'             => __('Left Left Sidebar' , 'auxin-portfolio'),
                'css_class'         => 'axiAdminIcon-sidebar-left-left'
            ),
            'right2-sidebar'        => array(
                'label'             => __('Right Right Sidebar' , 'auxin-portfolio'),
                'css_class'         => 'axiAdminIcon-sidebar-right-right'
            ),
            'left-right-sidebar'    => array(
                'label'             => __('Left Right Sidebar' , 'auxin-portfolio'),
                'css_class'         => 'axiAdminIcon-sidebar-left-right'
            ),
            'right-left-sidebar'    => array(
                'label'             => __('Right Left Sidebar' , 'auxin-portfolio'),
                'css_class'         => 'axiAdminIcon-sidebar-left-right'
            )
        ),
        'dependency'  => array(),
        'post_js'     => '$(".blog .aux-archive, main.aux-home").alterClass( "*-sidebar", to );',
        'type'        => 'radio-image',
        'transport'   => 'refresh',
        'default'     => 'no-sidebar'
    );

    $options[] = array(
        'title'       => __('Portfolio Sidebar Style', 'auxin-portfolio'),
        'description' => __('Specifies the style of sidebar on portfolio page.', 'auxin-portfolio'),
        'id'          => 'portfolio_index_sidebar_decoration',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                 'id'      => 'portfolio_index_sidebar_position',
                 'value'   => 'no-sidebar',
                 'operator'=> '!='
            )
        ),
        'transport'   => 'postMessage',
        'post_js'     => '$(".aux-archive").alterClass( "aux-sidebar-style-*", "aux-sidebar-style-" + to );',
        'choices'     => array(
            'simple'        => array(
                'label'     => __('Simple' , 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/sidebar-style-1.svg'
            ),
            'border'        => array(
                'label'     => __('Bordered Sidebar' , 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/sidebar-style-2.svg'
            ),
            'overlap'       => array(
                'label'     => __('Overlap Background' , 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/sidebar-style-3.svg'
            )
        ),
        'type'       => 'radio-image',
        'default'    => 'border'
    );

     $options[] = array(
        'title'       => __('Number of Portfolios Per Page', 'auxin-portfolio'),
        'description' => __('Specifies the number of portfolios items to show on each page.', 'auxin-portfolio'),
        'id'          => 'portfolio_archive_items_perpage',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.post-type-archive-portfolio .aux-archive .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/loop', 'portfolio' );
            }
        ),
        'default'     => '12',
        'type'        => 'text'
    );


    $options[] = array(
        'title'       => __('Display Title Bar?', 'auxin-portfolio'),
        'description' => __('Specifies whether to display the title bar at top of portfolio archive page or not.', 'auxin-portfolio'),
        'id'          => 'portfolio_archive_titlebar_enabled',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(),
        'partial'     => array(
            'selector'              => 'body.archive .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'transport'   => 'postMessage',
        'default'     => '1',
        'type'        => 'switch'
    );


    $options[] = array(
        'title'       => __('Display Breadcrumb?', 'auxin-portfolio'),
        'description' => __('Specifies whether to display the breadcrumb in title bar of portfolio archive page or not.', 'auxin-portfolio'),
        'id'          => 'portfolio_archive_titlebar_breadcrumb_enabled',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_archive_titlebar_enabled',
                'value'   => '1'
            )
        ),
        'partial'       => array(
            'selector'              => 'body.archive .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'transport'   => 'postMessage',
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __('Display Title?', 'auxin-portfolio'),
        'description' => __('Specifies whether to display the title in title bar of portfolio archive page or not.', 'auxin-portfolio'),
        'id'          => 'portfolio_archive_titlebar_title_enabled',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_archive_titlebar_enabled',
                'value'   => '1'
            )
        ),
        'partial'       => array(
            'selector'              => 'body.archive .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'transport'   => 'postMessage',
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __('Custom Title', 'auxin-portfolio'),
        'description' => '',
        'id'          => 'portfolio_archive_titlebar_title_context',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_archive_titlebar_enabled',
                'value'   => '1'
            )
        ),
        'partial'       => array(
            'selector'              => 'body.archive .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'transport'   => 'postMessage',
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Custom Breadcrumb Label', 'auxin-portfolio'),
        'description' => '',
        'id'          => 'portfolio_archive_breadcrumb_label',
        'section'     => 'portfolio-section-archive',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_archive_titlebar_enabled',
                'value'   => '1'
            )
        ),
        'partial'       => array(
            'selector'              => 'body.archive .aux-customizer-page-title-container',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxin_the_main_title_section( array( 'has_helper_wrapper' => false ) );
            }
        ),
        'transport'   => 'postMessage',
        'default'     => __('Portfolio', 'auxin-portfolio'),
        'type'        => 'text'
    );

    // Sub section - Portfolio Taxonomy Page -------------------------------

    $sections[] = array(
        'id'          => 'portfolio-section-taxonomy',
        'parent'      => 'portfolio-section', // section parent's id
        'title'       => __( 'Portfolio Category & tag', 'auxin-portfolio'),
        'description' => __( 'Portfolio Category & tag page Setting', 'auxin-portfolio')
    );

    $options[] = array(
        'title'       => __('Taxonomy Page Template', 'auxin-portfolio'),
        'description' => 'Choose your category & tag page template.',
        'id'          => 'portfolio_taxonomy_template_type',
        'section'     => 'portfolio-section-taxonomy',
        'dependency'  => array(),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.aux-archive.aux-tax .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/tax', 'portfolio' );
            }
        ),
        'choices'     => array(
             // default template
            'grid-1'             => array(
                'label'     => __('Grid' , 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/portfolio-grid.svg'
            ),
            'masonry-1'             => array(
                'label'     => __('Masonry' , 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/portfolio-masonry.svg'
            ),
            'tiles-1'             => array(
                'label'     => __('Tiles' , 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/blog-layout-11.svg'
            ),
            'land-1'       => array(
                'label'     => __('Land', 'auxin-portfolio'),
                'image'     => AUXIN_URL . 'images/visual-select/blog-layout-10.svg'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'grid-1'
    );

    $options[] = array(
        'title'       => __('Image Aspect Ratio', 'auxin-portfolio'),
        'description' => '',
        'id'          => 'portfolio_taxonomy_image_aspect_ratio',
        'section'     => 'portfolio-section-taxonomy',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_taxonomy_template_type',
                'value'   => array('grid-1'),
                'operator'=> '=='
            )
        ),
        'type'        => 'select',
        'choices'     => array(
            '0.75'          => __('Horizontal 4:3' , 'auxin-portfolio'),
            '0.56'          => __('Horizontal 16:9', 'auxin-portfolio'),
            '1.00'          => __('Square 1:1'     , 'auxin-portfolio'),
            '1.33'          => __('Vertical 3:4'   , 'auxin-portfolio')
        ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.aux-archive.aux-tax .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/tax', 'portfolio' );
            }
        ),
        'default'     => '0.56',
    );

     $options[] = array(
        'title'       => __('Portfolio Hover Type', 'auxin-portfolio'),
        'description' => __('Hover over images to see the animation.', 'auxin-portfolio'),
        'id'          => 'portfolio_taxonomy_grid_item_type',
        'section'     => 'portfolio-section-taxonomy',
        'dependency'  => array(
                array(
                    'id'      => 'portfolio_taxonomy_template_type',
                    'value'   => array('grid-1', 'masonry-1'),
                    'operator'=> '=='
                )
        ),
        'type'        => 'radio-image',
        'choices'     => array(
             // default template
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
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.aux-archive.aux-tax .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/tax', 'portfolio' );
            }
        ),
        'default'     => 'classic',
    );

     $options[] = array(
        'title'       => __('Tile Portfolio Item Type', 'auxin-portfolio'),
        'description' => __('Hover over images to see the animation.', 'auxin-portfolio'),
        'id'          => 'portfolio_taxonomy_tile_item_type',
        'section'     => 'portfolio-section-taxonomy',
        'dependency'  => array(
            array(
               'id'      => 'portfolio_taxonomy_template_type',
               'value'   => array('tiles-1'),
               'operator'=> '=='
            )
        ),
        'type'        => 'radio-image',
        'choices'     => array(
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
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.aux-archive.aux-tax .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/tax', 'portfolio' );
            }
        ),
        'default'     => 'overlay',
    );

    $options[] = array(
        'title'       => __('Space', 'auxin-portfolio'),
        'description' => __('Specifies horizontal space between items (pixel).', 'auxin-portfolio'),
        'id'          => 'portfolio_taxonomy_grid_space',
        'section'     => 'portfolio-section-taxonomy',
        'dependency'  => array(
                array(
                    'id'      => 'portfolio_taxonomy_template_type',
                    'value'   => array('grid-1', 'masonry-1'),
                    'operator'=> '=='
                )
        ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.aux-archive.aux-tax .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/tax', 'portfolio' );
            }
        ),
        'default'     => '30',
        'type'        => 'text'
    );

     $options[] = array(
        'title'       => __('Number of Columns', 'auxin-portfolio'),
        'description' => '',
        'id'          => 'portfolio_taxonomy_column_number',
        'section'     => 'portfolio-section-taxonomy',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_taxonomy_template_type',
                'value'   => array('grid-1', 'masonry-1'),
                'operator'=> '=='
            )
        ),
        'type'        => 'select',
        'choices'     => array(
                    '1'  => '1', '2' => '2', '3' => '3',
                    '4'  => '4', '5' => '5', '6' => '6'
                ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.aux-archive.aux-tax .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/tax', 'portfolio' );
            }
        ),
        'default'     => '4',
    );

      $options[] = array(
        'title'       => __('Number of Columns in Tablet', 'auxin-portfolio'),
        'description' => '',
        'id'          => 'portfolio_taxonomy_column_number_tablet',
        'section'     => 'portfolio-section-taxonomy',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_taxonomy_template_type',
                'value'   => array('grid-1', 'masonry-1'),
                'operator'=> '=='
            )
        ),
        'type'        => 'select',
        'choices'     => array(
            'inherit' => 'Inherited from larger',
            '1'  => '1', '2' => '2', '3' => '3',
            '4'  => '4', '5' => '5', '6' => '6'
        ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.aux-archive.aux-tax .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/tax', 'portfolio' );
            }
        ),
        'default'     => 'inherit',
    );

    $options[] = array(
        'title'       => __('Number of Columns in Mobile', 'auxin-portfolio'),
        'description' => '',
        'id'          => 'portfolio_taxonomy_column_number_mobile',
        'section'     => 'portfolio-section-taxonomy',
        'dependency'  => array(
            array(
                'id'      => 'portfolio_taxonomy_template_type',
                'value'   => array('grid-1', 'masonry-1'),
                'operator'=> '=='
            )
        ),
        'type'        => 'select',
        'choices'     => array(
                    '1' => '1' , '2' => '2', '3' => '3'
                ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.aux-archive.aux-tax .content',
            'container_inclusive'   => false,
            'render_callback'       => function(){
                auxpfo_get_template_part( 'theme-parts/tax', 'portfolio' );
            }
        ),
        'default'     => '1',
    );

    if ( auxin_is_plugin_active( 'wp-ulike/wp-ulike.php')){
        $options[] = array(
            'title'       => __('Display Like Button', 'auxin-portfolio'),
            'description' => sprintf(__('Enable it to display %s like button%s on portfolio portfolios. Please note WP Ulike plugin needs to be activaited to use this option.', 'auxin-portfolio'), '<strong>', '</strong>'),
            'id'          => 'show_portfolio_taxonomy_like_button',
            'section'     => 'portfolio-section-taxonomy',
            'dependency'  => array(
                array(
                    'id'      => 'portfolio_taxonomy_template_type',
                    'value'   => array('tiles-1'),
                    'operator'=> '!='
                ),

        ),
            'transport'   => 'postMessage',
            'partial'     => array(
                'selector'              => '.aux-archive.aux-tax .content',
                'container_inclusive'   => false,
                'render_callback'       => function(){
                    auxpfo_get_template_part( 'theme-parts/tax', 'portfolio' );
                }
            ),
            'default'     => '1',
            'type'        => 'switch'
        );
    }

    $options[] = array(
        'title'       => __('Taxonomy Page Sidebar Position', 'auxin-portfolio'),
        'description' => 'Specifies the position of sidebar on category & tag page.',
        'id'          => 'portfolio_taxonomy_sidebar_position',
        'section'     => 'portfolio-section-taxonomy',
        'dependency'  => array(),
        'post_js'     => '$(".archive.tag main, .archive.tax-portfolio-cat main").alterClass( "*-sidebar", to );',
        'choices'     => array(
            'no-sidebar' => array(
                'label'  => __('No Sidebar', 'auxin-portfolio'),
                'css_class' => 'axiAdminIcon-sidebar-none'
            ),
            'right-sidebar' => array(
                'label'  => __('Right Sidebar', 'auxin-portfolio'),
                'css_class' => 'axiAdminIcon-sidebar-right'
            ),
            'left-sidebar' => array(
                'label'  => __('Left Sidebar' , 'auxin-portfolio'),
                'css_class' => 'axiAdminIcon-sidebar-left'
            ),
            'left2-sidebar' => array(
                'label'  => __('Left Left Sidebar' , 'auxin-portfolio'),
                'css_class' => 'axiAdminIcon-sidebar-left-left'
            ),
            'right2-sidebar' => array(
                'label'  => __('Right Right Sidebar' , 'auxin-portfolio'),
                'css_class' => 'axiAdminIcon-sidebar-right-right'
            ),
            'left-right-sidebar' => array(
                'label'  => __('Left Right Sidebar' , 'auxin-portfolio'),
                'css_class' => 'axiAdminIcon-sidebar-left-right'
            ),
            'right-left-sidebar' => array(
                'label'  => __('Right Left Sidebar' , 'auxin-portfolio'),
                'css_class' => 'axiAdminIcon-sidebar-left-right'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'right-sidebar'
    );

    $options[] = array(
        'title'       => __('Sidebar Style', 'auxin-portfolio'),
        'description' => __('Specifies the style of sidebar on category & tag page.', 'auxin-portfolio'),
        'id'          => 'portfolio_taxonomy_archive_sidebar_decoration',
        'section'     => 'portfolio-section-taxonomy',
        'dependency'  => array(
            array(
                 'id'      => 'portfolio_taxonomy_sidebar_position',
                 'value'   => 'no-sidebar',
                 'operator'=> '!='
            )
        ),
        'post_js'    => '$(".archive.tag main, .archive.tax-portfolio-cat main").alterClass( "aux-sidebar-style-*", "aux-sidebar-style-" + to );',
        'choices'    => array(
            'simple' => array(
                'label'  => __('Simple' , 'auxin-portfolio'),
                'image' => AUXIN_URL . 'images/visual-select/sidebar-style-1.svg'
            ),
            'border' => array(
                'label'  => __('Bordered Sidebar' , 'auxin-portfolio'),
                'image' => AUXIN_URL . 'images/visual-select/sidebar-style-2.svg'
            ),
            'overlap' => array(
                'label'  => __('Overlap Background' , 'auxin-portfolio'),
                'image' => AUXIN_URL . 'images/visual-select/sidebar-style-3.svg'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'border'
    );

    // -------------------------------------------------------------------------

    $sections[] = array(
        'id'          => 'portfolio-section-metadata',
        'parent'      => 'portfolio-section', // section parent's id
        'title'       => __( 'Portfolio MetaData', 'auxin-portfolio'),
        'description' => __( 'Portfolio MetaData Setting', 'auxin-portfolio')
    );

    $options[] = array(
        'title'       => __('Label for Launch Project Button', 'auxin-portfolio'),
        'description' => __('Specify a label for launch project button.', 'auxin-portfolio'),
        'id'          => 'portfolio_metadata_launch_label',
        'section'     => 'portfolio-section-metadata',
        'dependency'  => array(),
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-portfolio .entry-meta-data-container .aux-cta-button").html( to );',
        'type'        => 'text',
        'default'     => __( 'Launch Project', 'auxin-portfolio' )
    );

    $options[] = array(
        'title'       => __('Portfolio MetaDatas', 'auxin-portfolio'),
        'description' => __('Specify the number of fields and the label of each one for portfolio metadatas', 'auxin-portfolio'),
        'id'          => 'portfolio_metadata_list_1',
        'section'     => 'portfolio-section-metadata',
        'dependency'  => array(),
        'transport'   => 'post_js',
        'choices'     => array(
            'url'               => __( 'Project URL', 'auxin-portfolio' ),
            'client'            => __( 'Client', 'auxin-portfolio' ),
            'release_date'      => __( 'Release Date', 'auxin-portfolio' ),
            'author'            => __( 'Author', 'auxin-portfolio' ),
            'aux_custom_meta1'  => __( 'Custom Field 1', 'auxin-portfolio' ),
            'aux_custom_meta2'  => __( 'Custom Field 2', 'auxin-portfolio' ),
            'aux_custom_meta3'  => __( 'Custom Field 3', 'auxin-portfolio' ),
            'aux_custom_meta4'  => __( 'Custom Field 4', 'auxin-portfolio' ),
            'aux_custom_meta5'  => __( 'Custom Field 5', 'auxin-portfolio' ),
            'aux_custom_meta6'  => __( 'Custom Field 6', 'auxin-portfolio' ),
            'aux_custom_meta7'  => __( 'Custom Field 7', 'auxin-portfolio' ),
            'aux_custom_meta8'  => __( 'Custom Field 8', 'auxin-portfolio' ),
            'aux_custom_meta9'  => __( 'Custom Field 9', 'auxin-portfolio' ),
            'aux_custom_meta10' => __( 'Custom Field 10', 'auxin-portfolio' ),
            'aux_custom_meta11' => __( 'Custom Field 11', 'auxin-portfolio' ),
            'aux_custom_meta12' => __( 'Custom Field 12', 'auxin-portfolio' )
        ),
        'type'          => 'sortable-input',
        'default'       => '[{"id":"url", "label":"Project URL", "value":"Project URL"},{"id":"client", "label":"Client", "value":"Client"},{"id":"release_date", "label":"Release Date", "value":"Release Date"}]'
    );

    // -------------------------------------------------------------------------

    $sections[] = array(
        'id'          => 'portfolio-section-single-appearance',
        'parent'      => 'portfolio-section', // section parent's id
        'title'       => __( 'Single Portfolio Appearance', 'auxin-portfolio'),
        'description' => __( 'Single Portfolio Appearance', 'auxin-portfolio')
    );

    $options[] = array(
        'title'          => __( 'Content', 'auxin-portfolio' ),
        'id'             => 'single_portfolio_content_typography',
        'section'        => 'portfolio-section-single-appearance',
        'type'           => 'group_typography',
        'selectors'      => '.aux-single .type-portfolio .entry-content',
        'transport'      => 'postMessage',
    );

    $options[] = array(
        'title'          => __( 'Overview Title', 'auxin-portfolio' ),
        'id'             => 'single_portfolio_overview_title_typography',
        'section'        => 'portfolio-section-single-appearance',
        'type'           => 'group_typography',
        'selectors'      => '.aux-single .type-portfolio .entry-side-title > h1',
        'transport'      => 'postMessage',
    );

    $options[] = array(
        'title'          => __( 'Overview Content', 'auxin-portfolio' ),
        'id'             => 'single_portfolio_overview_content_typography',
        'section'        => 'portfolio-section-single-appearance',
        'type'           => 'group_typography',
        'selectors'      => '.aux-single .type-portfolio .entry-side-overview',
        'transport'      => 'postMessage',
    );

    $options[] = array(
        'title'          => __( 'Meta', 'auxin-portfolio' ),
        'id'             => 'single_portfolio_meta_typography',
        'section'        => 'portfolio-section-single-appearance',
        'type'           => 'group_typography',
        'selectors'      => '.aux-single .type-portfolio .entry-meta-data dt',
        'transport'      => 'postMessage',
    );

    $options[] = array(
        'title'          => __( 'Meta Terms', 'auxin-portfolio' ),
        'id'             => 'single_portfolio_meta_terms_typography',
        'section'        => 'portfolio-section-single-appearance',
        'type'           => 'group_typography',
        'selectors'      => '.aux-single .type-portfolio .entry-meta-data dd, .aux-single .type-portfolio .entry-meta-data .entry-tax > a',
        'transport'      => 'postMessage',
    );

    $options[] = array(
        'title'          => __( 'Launch Button', 'auxin-portfolio' ),
        'id'             => 'single_portfolio_lunch_btn_typography',
        'section'        => 'portfolio-section-single-appearance',
        'type'           => 'group_typography',
        'selectors'      => '.aux-single .type-portfolio .entry-meta-data .aux-button',
        'transport'      => 'postMessage',
    );

    $options[] = array(
        'title'       => __( 'Launch Button Background', 'auxin-portfolio' ),
        'id'          => 'single_portfolio_lunch_btn_bg',
        'section'     => 'portfolio-section-single-appearance',
        'transport'      => 'postMessage',
        'selectors'   => array(
            '.aux-single .type-portfolio .entry-meta-data .aux-button' => 'background-image:{{VALUE}};'
        ),
        'default'   => '',
        'type'      => 'gradient'
    );

    return array( 'fields' => $options, 'sections' => $sections );
}

add_filter( 'auxin_defined_option_fields_sections', 'auxin_define_portfolio_theme_options', 13, 1 );



/**
 * Embed the
 *
 * @return [type] [description]
 */
function auxpfo_init_portfolio_post_type_and_metafields(){

    // abort if phlox theme is not enabled
    if( ! defined('AUXIN_VERSION') ){
        return;
    }

    $post_type      = 'portfolio';
    $all_post_types = auxin_get_possible_post_types(true);

    // check if the post type is allowed
    if( ! empty( $all_post_types[ $post_type ] ) && $all_post_types[ $post_type ] ){

        // Initiate the post type
        include AUXPFO_INC_DIR   . '/classes/class-auxpfo-post-type-portfolio.php';

        $portfolio_instance = new Auxpfo_Post_Type_Portfolio();
        $portfolio_instance->register();

        if( is_admin() ){
            $metabox_args['post_type']     = $post_type;
            $metabox_args['hub_id']        = 'axi_meta_hub_portfolio';
            $metabox_args['hub_title']     = __('Portfolio Options', 'auxin-portfolio' );
            $metabox_args['to_post_types'] = array( $post_type );

            auxin_maybe_render_metabox_hub_for_post_type( $metabox_args );
        }
    }

}
add_action( 'init', 'auxpfo_init_portfolio_post_type_and_metafields' );


/**
 * WP ULike Customization
 *
 */

if ( auxin_is_plugin_active( 'wp-ulike/wp-ulike.php' ) ) {

    function auxpfo_respond_for_liked_data( $value, $id ) {
        if( auxin_is_true( auxin_get_option( 'show_portfolio_single_like_label' ) ) ){
            return __( 'Likes', 'auxin-portfolio' ) . ' (' . $value . ')' ;
        } else {
            return $value;
        }

    }
    add_filter( 'wp_ulike_respond_for_liked_data' ,     'auxpfo_respond_for_liked_data',    10 ,    2 );
    add_filter( 'wp_ulike_respond_for_not_liked_data' , 'auxpfo_respond_for_liked_data',    10 ,    2 );
    add_filter( 'wp_ulike_respond_for_unliked_data' ,   'auxpfo_respond_for_liked_data',    10 ,    2 );


    // function auxpfo_respond_for_unliked_data( $value, $id ) {

    //     if( get_post_type( $id ) === 'portfolio' ){
    //         return __( 'Unlike', 'auxin-portfolio' ) . ' (' . $value . ')' ;
    //     } else {
    //         return $value;
    //     }

    // }
    // add_filter( 'wp_ulike_respond_for_unliked_data' ,   'auxpfo_respond_for_unliked_data',  10 ,    2 );

}

// Portfolio single ------------------------------------------------------------

function auxpfo_change_like_icon ( $args ) {
    $like_class = ( 'icon' == $like_type = auxin_get_option( 'portfolio_single_like_button_type', 'icon' ) ) ? ' aux-icon ' . auxin_get_option( 'portfolio_single_like_icon', 'auxicon-heart-2' ) : 'aux-has-text';

    $args['button_class'] .= ' ' . $like_class;
    if ( $like_type == 'text' ) {
        $args['button_type'] = 'text';
        $args['button_text'] = __( 'Like', 'auxin-portfolio' );
    } else {
        $args['button_type'] = 'image';
    }
    return $args;
}

/**
 * Adding share and like buttons to single portfolio actions section
 *
 * @return string
 */
function auxpfo_add_single_portfolio_actions( $show_like_btn, $show_share_btn ){

    if( function_exists( 'wp_ulike' ) && $show_like_btn ){
        add_filter( 'wp_ulike_add_templates_args', 'auxpfo_change_like_icon', 1, 1 );
        wp_ulike( 'get', array( 'style' => 'wpulike-heart', 'wrapper_class' => 'aux-wpulike aux-wpulike-portfolio' ) );
        remove_filter( 'wp_ulike_add_templates_args', 'auxpfo_change_like_icon', 1 );
    }
    if( $show_share_btn ) {
    ?>
        <?php $share_icon = auxin_get_option( 'portfolio_single_share_button_icon', 'auxicon-share' ) ; ?>
         <div class="aux-single-portfolio-share">
             <div class="aux-tooltip-socials aux-tooltip-dark aux-socials aux-icon-left aux-medium">
                 <?php if ( auxin_get_option( 'portfolio_single_share_button_type', 'icon' ) == 'icon' ) { ?>
                    <span class="aux-icon <?php echo esc_attr( $share_icon );?>"></span>
                 <?php } ?>
                 <span class="aux-text"><?php _e( 'Share', 'auxin-portfolio' ); ?></span>
             </div>
         </div>
    <?php
    }

}
add_action( 'auxin_single_portfolio_actions', 'auxpfo_add_single_portfolio_actions', 10, 2 );


/**
 * Making the portfolio overview filterable
 *
 * @param  string $overview The portfolio overview
 * @return string
 */
function auxpfo_add_single_portfolio_overview( $overview ){
    echo do_shortcode( $overview );
}
add_action( 'auxin_single_portfolio_overview', 'auxpfo_add_single_portfolio_overview', 10, 2 );



/**
 * Add related portfolio items to portfolio single page
 *
 * @return string
 */
function auxpfo_single_portfolio_related_items( $post ){

    // get display_related option
    if( 'default' == $display_related = auxin_get_post_meta( $post, '_display_related', 'default' ) ) {
        $display_related = auxin_get_option( 'show_portfolio_related_posts', true );
    }

    if( auxin_is_true( $display_related ) || is_customize_preview() ){

        // get title_label option
        if( 'default' == $related_title_label = auxin_get_post_meta( $post, '_related_posts_label', 'default' ) ) {
            $related_title_label = auxin_get_option( 'portfolio_related_posts_label', __( 'Related Projects', 'auxin-portfolio' ) );
        }

        // get desktop_cnum option
        if( 'default' == $desktop_cnum = auxin_get_post_meta( $post, '_related_posts_column_number', 'default' ) ) {
            $desktop_cnum = auxin_get_option( 'portfolio_related_posts_column_number', true );
        }

        // get preview_mode option
        if( 'default' == $preview_mode = auxin_get_post_meta( $post, '_related_posts_preview_mode', 'default' ) ) {
            $preview_mode = auxin_get_option( 'portfolio_related_posts_preview_mode', true );
        }

        // get alignment option
        if( 'default' == $do_align_center = auxin_get_post_meta( $post, '_related_posts_align_center', 'default' ) ) {
            $do_align_center = auxin_get_option( 'portfolio_related_posts_align_center', true );
        }

        // get display_categories option
        if( 'default' == $display_categories = auxin_get_post_meta( $post, '_related_posts_display_taxonomies', 'default' ) ) {
            $display_categories = auxin_get_option( 'portfolio_related_posts_display_taxonomies', true );
        }
        $display_categories = auxin_is_true( $display_categories )? true: false;


        // set arguments
        $defaults = array(
            'title'                       => $related_title_label,
            'desktop_cnum'                => $desktop_cnum,
            'preview_mode'                => $preview_mode,
            'extra_classes'               => auxin_is_true( $do_align_center ) ? 'aux-text-align-center': '',
            'display_categories'          => $display_categories,
            'exclude'                     => $post->ID,
            'container_start_tag'         => '<div class="aux-container aux-related-container aux-fold">',
        );
        echo auxpfo_get_portfolio_related_posts( $defaults );
    }

}



/**
 *  Adds a button under related items which links to more related items
 *
 * @param  object $post The post object
 * @return void
 */
function auxpfo_single_portfolio_show_all_portfolios( $post ){

    $btn_display = auxin_get_post_meta( $post, '_related_posts_all_items_btn_display', 'default' ) ;
    $btn_display = 'default' === $btn_display ?  auxin_get_option( "portfolio_single_all_related_items_btn_display", "1" )  : $btn_display ;

    $btn_url_type = auxin_get_post_meta( $post, '_related_posts_all_items_url_type', 'default' ) ;
    $btn_url_type = 'default' === $btn_url_type ?  auxin_get_option( "portfolio_single_all_related_items_url_type", 'hide' )  : $btn_url_type ;


    if( "custom" ===  $btn_url_type ){

        $portfolio_link = auxin_get_post_meta( $post, '_related_posts_all_items_url_type_custom', '' ) ;
        $portfolio_link = empty( $portfolio_link ) ? auxin_get_option( "portfolio_single_all_related_items_btn_url", "" ) : $portfolio_link ;

    } else {
        $portfolio_link  = get_post_type_archive_link( "portfolio" );
    }

    $portfolio_label = auxin_get_post_meta( $post, '_related_posts_all_items_btn_label', '' ) ;
    $portfolio_label = empty( $portfolio_label ) ? auxin_get_option( "portfolio_single_all_related_items_btn_label", "" ) : $portfolio_label ;

    if( ! empty( $portfolio_label ) && auxin_is_true( $btn_display ) ){
?>
    <div class="aux-container aux-related-container-more aux-fold">
        <a href="<?php echo esc_url( $portfolio_link ); ?>" class="aux-button aux-cta-button aux-shamrock aux-exlarge aux-curve aux-related-btn-more" target="_blank">
            <span class="aux-overlay"></span>
            <span class="aux-text"><?php echo esc_attr( $portfolio_label ); ?></span>
        </a>
    </div>
<?php
    }
}


/**
 *  Add Related Portfolios to Wrappers Based on Our Conditions
 *
 * @return void
 */

 function auxpfo_related_portfolios_location(){

    if ( is_single() ){

        global $post;

        $sticky_sidebar = auxin_get_post_meta( $post, '_sticky_sidebar', 'default' );
        $sticky_sidebar = 'default' === $sticky_sidebar ? auxin_get_option( 'portfolio_single_sticky_sidebar', false ) : $sticky_sidebar;
        $info_layout_bg = auxin_get_post_meta( $post, '_side_info_color' );

        if ( auxin_is_true( $sticky_sidebar ) || ! empty( $info_layout_bg ) ) {
            add_action( 'auxin_portfolio_single_after_article_primary', 'auxpfo_single_portfolio_related_items' );
            add_action( 'auxin_portfolio_single_after_article_primary', 'auxpfo_single_portfolio_show_all_portfolios' );
        } else {
            add_action( 'auxin_portfolio_single_after_content_primary', 'auxpfo_single_portfolio_related_items' );
            add_action( 'auxin_portfolio_single_after_content_primary', 'auxpfo_single_portfolio_show_all_portfolios' );
        }

    }

}

add_action( 'wp', 'auxpfo_related_portfolios_location');
/**
 * Changes the default portfolio layout to "no-sidebar"
 *
 * @return string   Portfolio single page layout
 */
function auxpfo_single_portfolio_no_sidebar( $layout, $post ){
    if( "portfolio" == get_post_type( $post ) && !is_post_type_archive( 'portfolio' ) && !is_tax( [ 'portfolio-cat', 'portfolio-tag'] ) ) {
        return "no-sidebar";
    }
    return $layout;
}
add_filter( "auxin_get_page_sidebar_pos", "auxpfo_single_portfolio_no_sidebar", 10, 2 );

/**
 * Exclude the posts without media in portfolio archive page query
 *
 * @return Void
 */
function auxpfo_exclude_posts_without_media( $query ){

    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'portfolio' ) && ! in_array( auxin_get_option( 'portfolio_index_template_type', 'grid-1'), array( 'land-1' ) ) ) {

        $meta_query = array(
                         array(
                            'key'     => '_thumbnail_id',
                            'value'   => '',
                            'compare' => '!='
                        ),
                    );

        $query->set( 'meta_query', $meta_query );
    }

}
add_action('pre_get_posts','auxpfo_exclude_posts_without_media');

/** 
 * Add portfolio active post type
 *
 * @param  array $active_post_types  The list of allowed post types
 * @return array
 */
function auxpfo_allow_portfolio_active_post_types( $active_post_types ){
    $active_post_types['portfolio'] = true;

    return $active_post_types;
}
add_filter( 'auxin_active_post_types', 'auxpfo_allow_portfolio_active_post_types' );
