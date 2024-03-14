<?php
class FlatsomeTitleCategory {

    public function __construct()
    {
        wp_enqueue_style( 'flatsome-title-category', MINO_FLATSOME_TITLE_CATEGORY_ASSETS . 'css/mino-flatsome-title-with-category.css', array(), '1.0.0', 'all' );
        add_shortcode('title_with_cat', array($this, 'title_with_cat_shortcode'));
        add_action('ux_builder_setup', array($this, 'add_element_ux_builder'));
        register_activation_hook(__FILE__, array($this, 'plugin_activate'));
        register_deactivation_hook(__FILE__, array($this, 'plugin_deactivate'));
    }

    public function add_element_ux_builder()
    {
        add_ux_builder_shortcode('title_with_cat', array(
            'name'      => __('Title With Category'),
            'category'  => __('Content'),
            'info'      => '{{ text }}',
            'wrap'      => false,
            'options' => array(
                'title_cat_ids' => array(
                    'type' => 'select',
                    'heading' => 'Categories',
                    'param_name' => 'ids',
                    'config' => array(
                        'multiple' => true,
                        'placeholder' => 'Select...',
                        'termSelect' => array(
                            'post_type' => 'product_cat',
                            'taxonomies' => 'product_cat'
                        )
                    )
                ),
                'style' => array(
                    'type'    => 'select',
                    'heading' => 'Style',
                    'default' => 'normal',
                    'options' => array(
                        'normal'      => 'Normal',
                        'center'      => 'Center',
                        'bold'        => 'Left Bold',
                        'bold-center' => 'Center Bold',
                    ),
                ),
                'text' => array(
                    'type'       => 'textfield',
                    'heading'    => 'Title',
                    'default'    => 'Lorem ipsum dolor sit amet...',
                    'auto_focus' => true,
                ),
                'tag_name' => array(
                    'type'    => 'select',
                    'heading' => 'Tag',
                    'default' => 'h3',
                    'options' => array(
                        'h1' => 'H1',
                        'h2' => 'H2',
                        'h3' => 'H3',
                        'h4' => 'H4',
                    ),
                ),
                'color' => array(
                    'type'     => 'colorpicker',
                    'heading'  => __( 'Color' ),
                    'alpha'    => true,
                    'format'   => 'rgb',
                    'position' => 'bottom right',
                ),
                'width' => array(
                    'type'    => 'scrubfield',
                    'heading' => __( 'Width' ),
                    'default' => '',
                    'min'     => 0,
                    'max'     => 1200,
                    'step'    => 5,
                ),
                'margin_top' => array(
                    'type'        => 'scrubfield',
                    'heading'     => __( 'Margin Top' ),
                    'default'     => '',
                    'placeholder' => __( '0px' ),
                    'min'         => - 100,
                    'max'         => 300,
                    'step'        => 1,
                ),
                'margin_bottom' => array(
                    'type'        => 'scrubfield',
                    'heading'     => __( 'Margin Bottom' ),
                    'default'     => '',
                    'placeholder' => __( '0px' ),
                    'min'         => - 100,
                    'max'         => 300,
                    'step'        => 1,
                ),
                'size' => array(
                    'type'    => 'slider',
                    'heading' => __( 'Size' ),
                    'default' => 100,
                    'unit'    => '%',
                    'min'     => 20,
                    'max'     => 300,
                    'step'    => 1,
                ),
                'link_text' => array(
                    'type'    => 'textfield',
                    'heading' => 'Link Text',
                    'default' => '',
                ),
                'link' => array(
                    'type'    => 'textfield',
                    'heading' => 'Link',
                    'default' => '',
                )
            )
        ));
    }

    public function title_with_cat_shortcode( $atts, $content = null )
    {
        extract( shortcode_atts( array(
            '_id' => 'title-'.rand(),
            'class' => '',
            'visibility' => '',
            'text' => 'Lorem ipsum dolor sit amet...',
            'tag_name' => 'h3',
            'sub_text' => '',
            'style' => 'normal',
            'size' => '100',
            'link' => '',
            'link_text' => '',
            'target' => '',
            'margin_top' => '',
            'margin_bottom' => '',
            'letter_case' => '',
            'color' => '',
            'width' => '',
            'icon' => '',
        ), $atts ) );
        $classes = array('container', 'section-title-container');
        if ($class) $classes[] = $class;
        if ($visibility) $classes[] = $visibility;
        $classes = implode(' ', $classes);
        $link_output = '';
        if ($link) $link_output = '<a href="'.$link.'" target="'.$target.'">'.$link_text.get_flatsome_icon('icon-angle-right').'</a>';
        $small_text = '';
        if ($sub_text) $small_text = '<small class="sub-title">'.$atts['sub_text'].'</small>';
        if ($icon) $icon = get_flatsome_icon($icon);
        if ($style == 'bold_center') $style = 'bold-center';
        $css_args = array(
            array( 'attribute' => 'margin-top', 'value' => $margin_top),
            array( 'attribute' => 'margin-bottom', 'value' => $margin_bottom),
        );
        if ($width) {
            $css_args[] = array( 'attribute' => 'max-width', 'value' => $width);
        }
        $css_args_title = array();
        if ($size !== '100'){
            $css_args_title[] = array( 'attribute' => 'font-size', 'value' => $size, 'unit' => '%');
        }
        if ($color){
            $css_args_title[] = array( 'attribute' => 'color', 'value' => $color);
        }
        if (isset( $atts[ 'title_cat_ids' ] ) ) {
            $ids = explode( ',', $atts[ 'title_cat_ids' ] );
            $ids = array_map( 'trim', $ids );
            $parent = '';
            $orderby = 'include';
        } else {
            $ids = array();
        }
        $args = array(
            'taxonomy' => 'product_cat',
            'include'    => $ids,
            'pad_counts' => true,
            'child_of'   => 0,
        );
        $product_categories = get_terms( $args );
        $title_html_show_cat = '';
        if ($product_categories ) {
            foreach ( $product_categories as $category ) {
                $term_link = get_term_link( $category );
                $thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );
                if ($thumbnail_id ) {
                    $image = wp_get_attachment_image_src( $thumbnail_id, $thumbnail_size);
                    $image = $image[0];
                } else {
                    $image = wc_placeholder_img_src();
                }
                $title_html_show_cat .= '<li class="title_cats"><a href="'.$term_link.'">'.$category->name.'</a></li>';
            }
        }
        return '<div class="'.$classes.'" '.get_shortcode_inline_css($css_args).'><'. $tag_name . ' class="section-title section-title-'.$style.'"><b></b><span class="section-title-main" '.get_shortcode_inline_css($css_args_title).'>'.$icon.$text.$small_text.'</span>
        <span class="title-show-cats">'.$title_html_show_cat.'</span><b></b>'.$link_output.'</' . $tag_name .'></div><!-- .section-title -->';
    }


    public function plugin_activate()
    {

    }

    public function plugin_deactivate()
    {

    }
}
