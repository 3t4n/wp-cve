<?php
/**
 * Add metabox options for portfolio
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2023 averta
*/

function auxpfo_metabox_fields_portfolio_metadata(){

    $model            = new Auxin_Metabox_Model();
    $model->id        = 'portfolio-metadata';
    $model->title     = __('Portfolio Information', 'auxin-portfolio');
    $model->css_class = 'aux-portfolio-metadata-tab';
    $model->fields    = array(

        // array(
        //     'title'         => __('Featured Image', 'auxin-portfolio' ),
        //     'description'   => __('Specifies the main cover image for this portfolio.'),
        //     'id'            => '_thumbnail_id',
        //     'type'          => 'image',
        //     'default'       => ''
        // ),
        array(
            'title'         => __('Featured Image (Secondary)', 'auxin-portfolio' ),
            'description'   => __('Specifies the secondary cover image for this portfolio.'),
            'id'            => '_thumbnail_id2',
            'type'          => 'image',
            'default'       => ''
        ),
        array(
            'title'         => __('Hide featured image.', 'auxin-portfolio'),
            'description'   => __('Enable this option to remove the featured image from single page of this portfolio. Useful when you intent to display images that vary from the featured image.', 'auxin-portfolio'),
            'id'            => '_no_feature_image_in_single',
            'type'          => 'switch',
            'default'       => '0'
        ),

        array(
            'title'         => __('Overview', 'auxin-portfolio'),
            'description'   => __('Specifies a short description about the project.', 'auxin-portfolio'),
            'id'            => '_overview',
            'type'          => 'editor',
            'default'       => ''
        ),

        array(
            'title'         => __('Overview Title', 'auxin-portfolio'),
            'description'   => __('Specifies an optional title for project overview.', 'auxin-portfolio'),
            'id'            => '_overview_title',
            'type'          => 'text',
            'default'       => ''
        ),

        array(
            'title'         => __('Overview Alignment', 'auxin-portfolio'),
            'description'   => __('Specifies alignment for the project overview and corresponding information.', 'auxin-portfolio'),
            'id'            => '_overview_info_alignment',
            'type'          => 'radio-image',
            'default'       => 'default',
            'choices'       => array(
                'default' => array(
                    'label'     => __('Default', 'auxin-portfolio'),
                    'css_class' => 'axiAdminIcon-default',
                ),
                'left' => array(
                    'label'     => __('Left', 'auxin-portfolio'),
                    'css_class' => 'axiAdminIcon-text-align-left'
                ),
                'center' => array(
                    'label'     => __('Center', 'auxin-portfolio'),
                    'css_class' => 'axiAdminIcon-text-align-center'
                )
            )
        ),

        array(
            'title'         => __('Info Layout', 'auxin-portfolio'),
            'description'   => __('Specifies the alignment of metadata column. (Default: "right" for LTR websites and "left" for RTL ones)', 'auxin-portfolio'),
            'id'            => '_side_info_pos',
            'id_deprecated' => 'page_layout',
            'type'          => 'radio-image',
            'default'       => 'default',
            'choices'       => array(
                'default'   => array(
                    'label' => __('Default', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/default4.svg'
                ),
                'right'   => array(
                    'label' => __('Info on Right', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/portfolio-single-classic.svg'
                ),
                'left'   => array(
                    'label' => __('Info on Left', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/portfolio-single-classic-left-algin.svg'
                ),
                'top'   => array(
                    'label' => __('Info on Top', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/portfolio-single-info-on-top.svg'
                ),
                'top-reverse'   => array(
                    'label' => __('Info on Top - Direction reverse', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/portfolio-single-info-on-top-direction-reverse.svg'
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
            )
        ),

        array(
            'title'         => __('Sticky Side Area', 'auxin-portfolio'),
            'description'   => __('Enable it to stick the side area on page while scrolling.', 'auxin-portfolio'),
            'id'            => '_sticky_sidebar',
            'type'          => 'select',
            'default'       => 'default',
            'choices'       => array(
                'default'   => __('Theme Default', 'auxin-portfolio'),
                'yes'   => __('Yes', 'auxin-portfolio'),
                'no'   =>  __('No', 'auxin-portfolio'),
            ),
            'dependency'    => array(
                array(
                    'id' => '_side_info_pos',
                    'value' => array( 'right', 'left' )
                )
            ),
        ),

        array(
            'title'   => __('Info Box Background Color', 'auxin-portfolio'),
            'description'   => __('Specifies the color of info box.', 'auxin-portfolio'),
            'id'            => '_side_info_color',
            'type'          => 'color',
            'dependency'    => array(
                array(
                    'id' => '_side_info_pos',
                    'value' => array( 'right', 'left' )
                )
            )
        ),

        array(
            'title'   => __('Info Box Font Color', 'auxin-portfolio'),
            'description'   => __('Specifies the color of font at info box.', 'auxin-portfolio'),
            'id'            => '_side_info_font_color',
            'type'          => 'select',
            'default'       => 'dark',
            'dependency'    => array(
                array(
                    'id' => '_side_info_pos',
                    'value' => array( 'right', 'left' )
                )
            ),
            'choices'       => array(
                ''          => __( 'Select Info Box Style', 'auxin-portfolio' ),
                'dark'      => __( 'Dark', 'auxin-portfolio' ),
                'light'     => __( 'Light', 'auxin-portfolio' )
            )
        ),
        // @TODO: we should add this in future
        // array(
        //     'title'       => __('Content Style', 'auxin-portfolio'),
        //     'description' => __('You can reduce the width of text lines and increase the readability of context in single portfolio of portfolio (does not affect the width of media).', 'auxin-portfolio'),
        //     'id'          => 'portfolio_single_content_style',
        //     'section'     => 'portfolio-setting-section-single',
        //     'dependency'  => array(),
        //     'choices'     => array(
        //         'default'   => array(
        //             'label' => __('Default', 'auxin-portfolio'),
        //             'image' => AUXIN_URL . 'images/visual-select/default4.svg'
        //         ),
        //         'simple'  => array(
        //             'label'  => __( 'Normal' , 'auxin-portfolio'),
        //             'image' => AUXIN_URL . 'images/visual-select/content-normal.svg'
        //         ),
        //         'narrow' => array(
        //             'label'  => __( 'Narrow Content' , 'auxin-portfolio'),
        //             'image' => AUXIN_URL . 'images/visual-select/content-less.svg'
        //         )
        //     ),
        //     'transport' => 'postMessage',
        //     'post_js'   => '$(".single-portfolio .aux-primary .hentry").toggleClass( "aux-narrow-context", "narrow" == to );',
        //     'default'   => 'simple',
        //     'type'      => 'radio-image'
        // ),

        array(
            'title'       => __( 'Display Next & Previous portfolios', 'auxin-portfolio' ),
            'description' => __( 'Enable it to display links to next and previous portfolios on single portfolio page.' ),
            'id'          => '_show_next_prev_nav',
            'dependency'  => '',
            'type'          => 'select',
            'default'       => 'default',
            'choices'       => array(
                'default'   => __('Theme Default', 'auxin-portfolio'),
                'yes'   => __('Yes', 'auxin-portfolio'),
                'no'   =>  __('No', 'auxin-portfolio'),
            )
        ),

        array(
            'title'       => __('Skin for Next & Previous Links', 'auxin-portfolio'),
            'description' => __('Specifies the skin for next and previous navigation block.', 'auxin-portfolio'),
            'id'          => '_next_prev_nav_skin',
            'dependency'  => array(
                array(
                     'id'      => '_show_next_prev_nav',
                     'value'   => array('default', 'yes'),
                     'operator'=> ''
                )
            ),
            'choices'     => array(
                'default'   => array(
                    'label' => __('Default', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/default4.svg'
                ),
                'classic-title'    => array(
                    'label' => __('Classic Project Navigation', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/post-navigation-6.svg'
                ),
                'classic'    => array(
                    'label' => __('Classic Project Navigation Without Title', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/post-navigation-6.svg'
                ),
                'minimal' => array(
                    'label'     => __('Minimal (default)', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/post-navigation-1.svg'
                ),
                'thumb-arrow' => array(
                    'label'     => __('Thumbnail with Arrow', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/post-navigation-2.svg'
                ),
                'thumb-no-arrow' => array(
                    'label'     => __('Thumbnail without Arrow', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/post-navigation-3.svg'
                ),
                'boxed-image' => array(
                    'label'     => __('Navigation with Light Background', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/post-navigation-4.svg'
                ),
                'boxed-image-dark' => array(
                    'label'     => __('Navigation with Dark Background', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/post-navigation-5.svg'
                ),
                'thumb-arrow-sticky' => array(
                    'label'     => __('Sticky Thumbnail with Arrow', 'auxin-portfolio'),
                    'image' => AUXIN_URL . 'images/visual-select/post-navigation-6.svg'
                ),
                'modern'    => array(
                    'label'             => __('Modern', 'auxin-portfolio'),
                    'image'             => AUXIN_URL . 'images/visual-select/post-navigation-2.svg'
                ),
            ),
            'type'          => 'radio-image',
            'default'       => 'default'
        ),
        
        array(
            'title'         => __('Next & Previous Button Link', 'auxin-portfolio'),
            'description'   => __('Specifies the link of button in next and previous navigation. leave it blank to use default portfolio archive link', 'auxin-portfolio'),
            'id'            => '_next_prev_nav_btn_link',
            'dependency'  => array(
                array(
                    'id'      => '_show_next_prev_nav',
                    'value'   => array('default', 'yes'),
                    'operator'=> ''
                ),
                array(
                    'id'      => '_next_prev_nav_skin',
                    'value'   => array('modern', 'classic', 'classic-title'),
                    'operator'=> ''
               )
            ),
            'type'          => 'text',
            'default'       => '',
        ),

        array(
            'title'         => __('Display Portfolio Meta Info', 'auxin-portfolio'),
            'description'   => __('Enable this option to display extra inormation about this portfolio.', 'auxin-portfolio'),
            'id'            => '_show_side_info_meta',
            'type'          => 'select',
            'default'       => 'default',
            'choices'       => array(
                'default'   => __('Theme Default', 'auxin-portfolio'),
                'yes'       => __('Yes', 'auxin-portfolio'),
                'no'        => __('No', 'auxin-portfolio'),
            )
        ),

        array(
            'title'         => __('Display Single Portfolio Categories', 'auxin-portfolio'),
            'description'   => __('Specifies whether to display catetory section or not.', 'auxin-portfolio'),
            'id'            => '_side_info_dicplay_cat',
            'type'          => 'select',
            'default'       => 'default',
            'choices'       => array(
                'default'   => __('Theme Default', 'auxin-portfolio'),
                'yes'       => __('Yes', 'auxin-portfolio'),
                'no'        => __('No', 'auxin-portfolio'),
            ),
            'dependency'  => array(
                array(
                     'id'      => '_show_side_info_meta',
                     'value'   => array('yes', 'default'),
                     'operator'=> '=='
                )
            )
        ),

        array(
            'title'         => __('Display Single Portfolio Tags', 'auxin-portfolio'),
            'description'   => __('Specifies whether to display tag section or not.', 'auxin-portfolio'),
            'id'            => '_side_info_dicplay_tag',
            'type'          => 'select',
            'default'       => 'default',
            'choices'       => array(
                'default'   => __('Theme Default', 'auxin-portfolio'),
                'yes'   => __('Yes', 'auxin-portfolio'),
                'no'   =>  __('No' , 'auxin-portfolio'),
            ),
            'dependency'  => array(
                array(
                     'id'      => '_show_side_info_meta',
                     'value'   => array('yes', 'default'),
                     'operator'=> '=='
                )
            )
        ),

        array(
            'title'         => __('URL for Launch Button', 'auxin-portfolio'),
            'description'   => __('Specifies an URL for action button in order to lunch the project\'s webpage. Leave it empty if you don`t need Lunch Project Button.', 'auxin-portfolio'),
            'id'            => '_lunch_button_url',
            'type'          => 'text',
            'default'       => '',
            'dependency'  => array(
                array(
                     'id'      => '_show_side_info_meta',
                     'value'   => array('yes', 'default'),
                     'operator'=> '=='
                )
            )
        )

    );

    // Generate the custom metadata fields base on defined fields in theme options
    $metafields = json_decode( auxin_get_option( 'portfolio_metadata_list_1' ), true );
    if( is_array( $metafields ) ){
        foreach ( $metafields as $metadata_info ) {
            if( ! empty( $metadata_info['id'] ) ){
                $model->fields[] = array(
                    'title'         => $metadata_info['value'],
                    'description'   => '',
                    'id'            => '_auxin_meta_' . $metadata_info['id'],
                    'type'          => 'text',
                    'default'       => '',
                    'dependency'  => array(
                        array(
                             'id'      => '_show_side_info_meta',
                             'value'   => array('yes', 'default'),
                             'operator'=> '=='
                        )
                    )
                );
            }
        }
    }

    return $model;
}
