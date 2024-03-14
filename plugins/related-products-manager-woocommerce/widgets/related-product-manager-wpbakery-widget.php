<?php
/*
 * Display Related Product Visual Composer Elements
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
function related_product_manager_shortcode($atts, $content = null, $shortcode_handle = ' ') {

    $default_atts = array(
        'rpmw_columns' => 3,
        'rpmw_number_of_product' => 6,
        'rpmw_heading'=> ' ',
        'rpmw_show_heading' => 'true',
        'rpmw_heading_html_tag' => 'h2',
        'rpmw_heading_color'=>'#000',
        'rpmw_heading_aligment'=> 'left',
        'rpmw_heading_spacing' => '20px',
        'rpmw_layout_image_border_radius'=>'20px',
        'rpmw_layout_image_overlay'=> ' ',
        'rpmw_show_sale_badge' => 'true',
        'rpmw_sale_badge' => 'Sale!',
        'rpmw_sale_color' => '#fff',
        'rpmw_sale_background_color'=>'#0274be',
        'rpmw_sale_border_radius' => '50',
        'rpmw_show_category' => 'true',
        'rpmw_show_tag' =>'true',
        'rpmw_category_color' => '#000',
        'rpmw_tag_color' => '#000',
        'rpmw_category_hover_color' =>'#0274be',
        'rpmw_tag_hover_color' => '#0274be',
        'rpmw_category_spacing' =>' ',
        'rpmw_tag_spacing' => '10px',
        'rpmw_category_aligment' =>'left',
        'rpmw_category_font_size' =>'12px',
        'rpmw_tag_aligment' =>'left',
        'rpmw_tag_font_size' =>'12px',
        'rpmw_product_name_color' =>'#000',
        'rpmw_product_name_hover_color' =>'#0274be',
        'rpmw_product_name_aligment'=> 'left',
        'rpmw_product_name_spacing' => '10px',
        'rpmw_product_name_font_size' =>'15px',
        'rpmw_product_price_color' => '#000',
        'rpmw_product_price_hover_color' =>'#0274be',
        'rpmw_product_price_aligment'=> 'left',
        'rpmw_product_price_spacing' => ' ',
        'rpmw_product_price_font_size' =>'15px',
        'rpmw_rating_star' =>'10px',
        'rpmw_rating_star_color' => ' ',
        'rpmw_rating_star_unmarked_color'=> ' ',
        'rpmw_rating_star_spacing_left'=>' ',
        'rpmw_rating_star_spacing_bottom'=>'10px',
        'rpmw_text_button'=>'Add to cart',
        'rpmw_button_text_color' => '#fff',
        'rpmw_button_background_color' => '#0274be',
        'rpmw_button_background_hover_color' => '#000',
        'rpmw_button_text_hover_color' =>'#fff',
        'rpmw_button_border_radius' => ' ',
        'rpmw_button_aligment' => 'left',
        'rpmw_button_url' => ' ',
        'rpmw_product_order_by' => 'post_id',
        'rpmw_product_sort_by' => 'asc',
        'rpmw_grid_gap' => ' ',
        'rpmw_rows_gap' => ' ',
        'rpmw_grid_gap' => '30',
        'rpmw_rows_gap' => '35',
        'rpmw_design_options' =>' ',
        'rpmw_rating_aligment' => ' ',
        'multiple'=>' ',
        'category' =>' ',
        'exclude_product_categories'=>false,
        'rpmw_sale_size' => ' ',
    );

    $atts = shortcode_atts($default_atts, $atts);
    extract($atts);
    $content = wpb_js_remove_wpautop($content, true);
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $rpmw_design_options, ' ' ));
    ob_start();
 
    /**
     * Render Related Product widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
        global $product;
         $args = array(
            'post_type' => 'product',
            'posts_per_page' => $rpmw_number_of_product,
            'post_status' => 'publish',
            'columns' => $rpmw_columns,
            'orderby' => $rpmw_product_order_by,
            'order' => $rpmw_product_sort_by,
            'hide_empty' =>false,
            'taxonomy'     => 'product_cat',     
       );?>
        <!-- Related Product for ordrby -->
       <?php switch ($rpmw_product_order_by){
        case 'price':
             $args = array(
                'post_type'      => 'product',
                'orderby'        => 'meta_value_num',
                'order'          => $rpmw_product_sort_by,
                'meta_key'       => '_price'
                );
            break;
        case 'price-desc':
            $args = array(
                'post_type'      => 'product',
                'orderby'        => 'meta_value_num',
                'order'          => $rpmw_product_sort_by,
                'meta_key'       => '_price'
                );
            break;
        case 'rating':
             $args = array(
                'post_type'      => 'product',
                'orderby'        => 'meta_value_num',
                'order'          => $rpmw_product_sort_by,
                'meta_key'       => '_wc_average_rating'
                );
            break;
        }
        // Related Product post per page
        if ( ! empty( $rpmw_number_of_product ) ) {
                $args['posts_per_page'] = $rpmw_number_of_product;
        }
        // Related Product column 
        if ( ! empty( $rpmw_columns ) ) {
                $args['columns'] = $rpmw_columns;
        }
        // Related Product Exclude category
        if (isset($exclude_product_categories) && !empty($exclude_product_categories)) {
            $product_cats = 'NOT IN';
        } else {
            $product_cats = 'IN';
        }
        // Related Product Display Category
        $tax_slugs = array();
        if(!empty($category)){
                foreach (explode(",", $category) as $tax_id) {
                    $tax_cat = get_term_by('id', $tax_id, 'product_cat', 'ARRAY_A');
                    $tax_slugs[] = $tax_cat['slug']; 
                }
            } 
        if (isset($tax_slugs) && !empty($tax_slugs)) {
            // Display All Product with Category 
            $args['tax_query'][] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $tax_slugs,
                    'operator' => $product_cats,
                ),
            );
        }

       $the_query = new \WP_Query($args);   
        if ( $the_query->have_posts() ) {
            if ($rpmw_show_heading == 'true') {
                $tag = $rpmw_heading_html_tag;?>
                <<?php echo $tag ?> class="related-product-heading" style="color: <?php echo $rpmw_heading_color; ?>; text-align: <?php echo $rpmw_heading_aligment; ?>; margin-bottom: <?php echo $rpmw_heading_spacing; ?>;">
                <?php echo $rpmw_heading; ?>
                </<?php echo $tag; ?>>
            <?php } ?>
            <div class="vc_grid-container-wrapper vc_clearfix <?php echo $css_class; ?>" style='<?php $rpmw_design_options ?>' >
                    <div class="related-products vc_grid-container vc_product-grid-gap product_border_radius related-products_contanair-<?php echo esc_html($rpmw_columns);?>" >
                        <?php while ($the_query->have_posts()) : $the_query->the_post(); global $product; global $post; ?> 
                            <div class="related-products_contanair product-container"> 
                                <div class="related-products_img product_thumbnail"> <?php   
                                    if ($rpmw_show_sale_badge == 'true') {?>
                                    <div class="related-product-sale-price">
                                    <?php
                                        if( $product->is_on_sale() ) {?>
                                        <span class="onsale"><?php
                                                echo $rpmw_sale_badge;      
                                        }?></span>
                                    </div>
                                <?php } ?>                                      
                                    <?php if ( has_post_thumbnail() ) {?> 
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?></a>
                                    <?php } ?>
                            </div>                             
                            <div class="related-products_contant" ><?php
                                if ($rpmw_show_category == 'true') {?>
                                <div class="related-product-category">
                                    <?php echo wc_get_product_category_list( $post->ID );?>
                                </div> <?php
                                    } 
                                if ($rpmw_show_tag == 'true') {?>
                                    <div class="related-product-tag" style="color: <?php echo $rpmw_tag_color; ?>;">
                                        <?php echo wc_get_product_tag_list( $post->ID );?>
                                    </div>
                                    <?php
                                } ?>
                                <h4 class="related_product_title"><?php the_title(); ?></h4>    
                                <div class="related_product_star_rating">
                                    <?php if ($average = $product->get_average_rating()) : ?>
                                    <?php echo '<div class="star-rating " title="'.sprintf(__( 'Rated %s out of 5', 'woocommerce' ), $average).'"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'woocommerce' ).'</span></div>'; ?>
                                    <?php endif; ?>
                                </div>       
                                <div class="related-price">
                                    <?php echo $product->get_price_html(); ?>
                                </div>
                                <div class="view-btn">
                                    <a class="button" href="<?php the_permalink(); ?>"><?php
                                     echo $rpmw_text_button; ?></a>
                                </div>
                            </div>   
                        </div> 
                        <?php
                    endwhile;
                    wp_reset_postdata();?>
                    <?php
        }
                echo '</div>';
            echo '</div>';  
            ?>  

   <!-- Wb-Bakery css -->
    <style>
    .related-products.vc_grid-container.vc_product-grid-gap {
        grid-column-gap: <?php echo $rpmw_grid_gap . 'px !important'; ?>;
        grid-row-gap: <?php echo $rpmw_rows_gap . 'px'; ?>;
        display: grid;
        grid-column-gap: 20px;
    }
    .related-products_img.product_thumbnail a img {
        border-radius: <?php echo $rpmw_layout_image_border_radius . 'px'; ?>;
        opacity: 0.9 ;
    }
    .related-products_img.product_thumbnail {
        background-color: <?php echo $rpmw_layout_image_overlay ?>;
        border-radius: <?php echo $rpmw_layout_image_border_radius . 'px'; ?>;
    }
    .related-product-sale-price span.onsale {
        color: <?php echo $rpmw_sale_color ?>;
        background-color : <?php echo $rpmw_sale_background_color ?>;
        border-radius: <?php echo $rpmw_sale_border_radius . 'px'; ?>;
    }
    .related-product-category {
        margin-bottom:<?php echo $rpmw_category_spacing ?> ;
        text-align:<?php echo $rpmw_category_aligment ?> ;
        font-size:<?php echo $rpmw_category_font_size ?> ;
    }
    .related-product-tag {
        margin-bottom:<?php echo $rpmw_tag_spacing ?> ;
        text-align:<?php echo $rpmw_tag_aligment ?> ;
        font-size:<?php echo $rpmw_tag_font_size ?> ;
    }
    .related-product-category a {
        color : <?php echo $rpmw_category_color; ?>;
    }
    .related-product-tag a {
        color : <?php echo $rpmw_tag_color; ?>;
    }
    .related-product-category a:hover {
        color : <?php echo $rpmw_category_hover_color; ?>;
    }
    .related-product-tag a:hover{
        color : <?php echo $rpmw_tag_hover_color; ?>;
    }
    h4.related_product_title {
        color:<?php echo $rpmw_product_name_color; ?>;
        margin-bottom:<?php echo $rpmw_product_name_spacing ?> ;
        text-align:<?php echo $rpmw_product_name_aligment ?> ;
        font-size:<?php echo $rpmw_product_name_font_size ?> ;
    }
    h4.related_product_title:hover {
        color : <?php echo $rpmw_product_name_hover_color; ?>;
    }
    .related-price {
        color:<?php echo $rpmw_product_price_color; ?>;
        margin-bottom:<?php echo $rpmw_product_price_spacing ?> ;
        text-align:<?php echo $rpmw_product_price_aligment ?> ;
        font-size:<?php echo $rpmw_product_price_font_size ?> ;
    }
    .related-price:hover {
        color : <?php echo $rpmw_product_price_hover_color; ?>;
    }
    .related_product_star_rating {
        justify-content:<?php echo $rpmw_rating_aligment; ?>;
    }
    .related_product_star_rating .star-rating {
        font-size: <?php echo $rpmw_rating_star; ?>;
        margin-right:<?php echo $rpmw_rating_star_spacing_left; ?>;
        margin-left:<?php echo $rpmw_rating_star_spacing_left; ?>;
        margin-bottom:<?php echo $rpmw_rating_star_spacing_bottom; ?>;
    }
    .related_product_star_rating .star-rating ::before {
        color:<?php echo $rpmw_rating_star_color; ?>;
    }
    .related_product_star_rating .star-rating::before {
        color:<?php echo $rpmw_rating_star_unmarked_color; ?>;
    }
    .view-btn a {
        <?php echo $rpmw_button_url;?>
    }
    .view-btn a.button {
        color:<?php echo $rpmw_button_text_color; ?>;
        background-color : <?php echo $rpmw_button_background_color ?>;
        border-radius: <?php echo $rpmw_button_border_radius . 'px'; ?>;
    }  
    .view-btn a.button:hover {
        color:<?php echo $rpmw_button_text_hover_color; ?>;
        background-color : <?php echo $rpmw_button_background_hover_color ?>; 
    }
    .view-btn {
        text-align:<?php echo $rpmw_button_aligment ?> ;
    
    }
    .related-product-sale-price span.onsale{
        font-size:<?php echo $rpmw_sale_size; ?>;
    }
    </style>
<?php
    return ob_get_clean();
}

add_shortcode('related_product_manager_card_layout', 'related_product_manager_shortcode');

// Related Product dropdown for category and tag shortcode 

vc_add_shortcode_param( 'dropdown_multi', 'dropdown_multi_settings_field' );
function dropdown_multi_settings_field( $param, $value ) {
   $param_line = ' ';
   $param_line .= '<select multiple name="'. esc_attr( $param['param_name'] ).'" class="wpb_vc_param_value wpb-input wpb-select '. esc_attr( $param['param_name'] ).' '. esc_attr($param['type']).'">';
   foreach ( $param['value'] as $text_val => $val ) {
       if ( is_numeric($text_val) && (is_string($val) || is_numeric($val)) ) {
                    $text_val = $val;
                }
                $text_val = __($text_val, RPMW_TEXTDOMAIN);
                $selected = ' ';

                if(!is_array($value)) {
                    $param_value_arr = explode(',',$value);
                } else {
                    $param_value_arr = $value;
                }

                if ($value!==' ' && in_array($val, $param_value_arr)) {
                    $selected = ' selected="selected"';
                }
                $param_line .= '<option class="'.$val.'" value="'.$val.'"'.$selected.'>'.$text_val.'</option>';
            }
   $param_line .= '</select>';

   return  $param_line;
}

//Select category for dropdown

$link_category = array();
$link_cats = get_terms( 'product_cat' );
if ( is_array( $link_cats ) && ! empty( $link_cats ) ) {
	foreach ( $link_cats as $link_cat ) {
		if ( is_object( $link_cat ) && isset( $link_cat->name, $link_cat->term_id ) ) {
			$link_category[ $link_cat->name ] = $link_cat->term_id;
		}
	}
}

/*
* How many product columns
*/
$rpmw_columns = array(
   __('1', RPMW_TEXTDOMAIN) => '1',
   __('2', RPMW_TEXTDOMAIN) => '2',
   __('3', RPMW_TEXTDOMAIN) => '3',
   __('4', RPMW_TEXTDOMAIN) => '4',
   __('6', RPMW_TEXTDOMAIN) => '6'
);

/*
 * Title HTML Tag
 */
$post_title_html_tag = array(
    __('H1', RPMW_TEXTDOMAIN) => 'h1',
    __('H2', RPMW_TEXTDOMAIN) => 'h2',
    __('H3', RPMW_TEXTDOMAIN) => 'h3',
    __('H4', RPMW_TEXTDOMAIN) => 'h4',
    __('H5', RPMW_TEXTDOMAIN) => 'h5',
    __('H6', RPMW_TEXTDOMAIN) => 'h6',
    __('div', RPMW_TEXTDOMAIN) => 'div',
    __('span', RPMW_TEXTDOMAIN) => 'span',
    __('p', RPMW_TEXTDOMAIN) => 'p'
);
/*
 * Order By
 */
$product_order_by = array(
    __('Id', RPMW_TEXTDOMAIN) => 'post_id',
    __('Date', RPMW_TEXTDOMAIN) => 'post_date',
    __('Title', RPMW_TEXTDOMAIN) => 'post_title',
    __('Random', RPMW_TEXTDOMAIN) => 'rand',
    __('Price', RPMW_TEXTDOMAIN) => 'price',
    __('Rating', RPMW_TEXTDOMAIN) => 'rating', 
    __( 'Modified',  RPMW_TEXTDOMAIN ) => 'modified', 
);

/*
 * Sort By
 */
$product_sort_by = array(
    __('ASC', RPMW_TEXTDOMAIN) => 'asc',
    __('DESC', RPMW_TEXTDOMAIN) => 'desc',
);

/*
 * Related product Visual Composer Elements
 */
$related_product_fields = array(
    array(
        'type' => 'checkbox',
        'heading' => esc_html__('Show Heading', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_show_heading',
        'value' => __('true', RPMW_TEXTDOMAIN),
        'admin_label' => true,
        'std' => 'true',
        'description' => __('Check/Uncheck to show/hide the title.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'textfield',
        'heading' => esc_html__('Heading', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_heading',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'admin_label' => true,
        'description' => esc_html__('Enter heading.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown',
        'heading' => esc_html__('Title HTML Tag', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_heading_html_tag',
        'value' => $post_title_html_tag,
        'std' => 'h2',
        'group' => 'Heading Style',
        'admin_label' => true,
        'description' => esc_html__('Select title HTML tag.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Alignment',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_heading_aligment',
        'value' => array(
          __( 'Left',  RPMW_TEXTDOMAIN  ) => 'left',
          __( 'Center',  RPMW_TEXTDOMAIN  ) => 'center',
          __( 'Right',  RPMW_TEXTDOMAIN  ) => 'right',
        ),
        'std' => 'left',
        'group' => 'Heading Style',
        "description" => __( "Select Alignment.", RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Spacing For Heading ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_heading_spacing',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Heading Style',
        'description' => __('Set the Heading bottom spacing eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'textfield',
        'heading' => esc_html__('Display Number of Product', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_number_of_product',
        'value' => __('6', RPMW_TEXTDOMAIN),
        'admin_label' => true,
        'description' => esc_html__('Enter number of product. e.g. 6.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown',
        'heading' => esc_html__('Columns', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_columns',
        'value' => $rpmw_columns,
        'std' => '3',
        'admin_label' => true,
        'description' => esc_html__('Select product columns.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown',
        'heading' => esc_html__('Order By', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_product_order_by',
        'value' => $product_order_by,
        'admin_label' => true,
        'description' => esc_html__('Select order by.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown',
        'heading' => esc_html__('Sort By', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_product_sort_by',
        'value' => $product_sort_by,
        'admin_label' => true,
        'description' => esc_html__('Select sort by.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__('Show Category', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_show_category',
        'value' => __('true', RPMW_TEXTDOMAIN),
        'admin_label' => true,
        'std' => 'true',
        'description' => __('Check/Uncheck to show/hide the title.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__('Show Tag', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_show_tag',
        'value' => __('true', RPMW_TEXTDOMAIN),
        'admin_label' => true,
        'std' => 'true',
        'description' => __('Check/Uncheck to show/hide the title.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__('Show Sale Badge', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_show_sale_badge',
        'value' => __('true', RPMW_TEXTDOMAIN),
        'admin_label' => true,
        'std' => 'true',
        'description' => __('Check/Uncheck to show/hide the title.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'textfield',
        'heading' => esc_html__('Sale Badge Title', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_sale_badge',
        'value' => __('Sale!', RPMW_TEXTDOMAIN),
        'admin_label' => true,
        'description' => esc_html__('Enter Sale Badge Title.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown_multi',
        'heading' => __( 'Select Category', RPMW_TEXTDOMAIN ),
        'param_name' => 'category',
        'value' => $link_category,
        'admin_label' => true,
        'description' => __('Please select the categories you would like to display for your product. You can select multiple categories too (ctrl + click on PC and command + click on Mac).', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'checkbox',
        'heading' => esc_html__('Exclude Above Categories', RPMW_TEXTDOMAIN),
        'param_name' => 'exclude_product_categories',
        'value' => __('false', RPMW_TEXTDOMAIN),
        'description' => __('Exclude Above Categories.', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Button Text ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_text_button',
        'value' => __(' ', RPMW_TEXTDOMAIN),
    ), 
    array(
        'type' => 'textfield',
        'heading' => esc_html__('Grid Gap', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_grid_gap',
        'value' => __('30', RPMW_TEXTDOMAIN),
        'group' => 'Layout',
        'description' => esc_html__('Set layout grid gap e.g. 30', RPMW_TEXTDOMAIN)

    ),
    array(
        'type' => 'textfield',
        'heading' => esc_html__('Rows Gap', RPMW_TEXTDOMAIN),
        'param_name' => 'rpmw_rows_gap',
        'value' => __('35', RPMW_TEXTDOMAIN),
        'group' => 'Layout',
        'description' => esc_html__('Set layout row gap e.g. 35', RPMW_TEXTDOMAIN)
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Heading Text Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_heading_color",
        "value" => '#e74c3c', 
        'group' => 'Heading Style',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Border Radius',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_layout_image_border_radius',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Image',
        'description' => esc_html__('Set layout Image border radius', RPMW_TEXTDOMAIN)
    ),
    array(  
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Image Overlay', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_layout_image_overlay",
        "value" => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Image',
        "description" => __( "Set overlay for layout Image", RPMW_TEXTDOMAIN )
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Sale Badge Text Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_sale_color",
        "value" => '#e74c3c', 
        'group' => 'Sale Badge Style',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Sale Badge Background Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_sale_background_color",
        "value" => '#e74c3c', 
        'group' => 'Sale Badge Style',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Sale Badge Size ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_sale_size',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Sale Badge Style',
        'description' => __('Set the sale size eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    
    array(
        'type' => 'textfield',
        'heading' => __( 'Border Radius',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_sale_border_radius',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Sale Badge Style',
        'description' => esc_html__('Set Sale Badge border radius', RPMW_TEXTDOMAIN)
    ),
    array(
        "type" => "label",
        "param_name" => "rpmw_category_label",
        "class" => ' ',
        "heading" => esc_html__('Product Category', RPMW_TEXTDOMAIN),
        "value" => __(' ', RPMW_TEXTDOMAIN), 
        'group' => 'Content',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
        
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Category Text Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_category_color",
        "value" => '#e74c3c', 
        'group' => 'Content',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Category Text Hover Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_category_hover_color",
        "value" => '#e74c3c', 
        'group' => 'Content',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Category Font Size ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_category_font_size',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Content',
        'description' => __('Set the font size eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Select Category Alignment',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_category_aligment',
        'value' => array(
          __( 'Left',  RPMW_TEXTDOMAIN  ) => 'left',
          __( 'Center',  RPMW_TEXTDOMAIN  ) => 'center',
          __( 'Right',  RPMW_TEXTDOMAIN  ) => 'right',
        ),
        'std' => 'left',
        'group' => 'Content',
        "description" => __( "Select Alignment For Category.", RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Spacing For Category ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_category_spacing',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Content',
        'description' => __('Set the category title bottom spacing eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    array(
        "type" => "label",
        "class" => ' ',
        "param_name" => "rpmw_tag_label",
        "heading" => esc_html__('Product Tag', RPMW_TEXTDOMAIN),
        "value" => __(' ', RPMW_TEXTDOMAIN), 
        'group' => 'Content',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ), 
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Tag Text Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_tag_color",
        "value" => '#e74c3c', 
        'group' => 'Content',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Tag Text Hover Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_tag_hover_color",
        "value" => '#e74c3c', 
        'group' => 'Content',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Tag Font Size ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_tag_font_size',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Content',
        'description' => __('Set the font size eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Select Tag Alignment',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_tag_aligment',
        'value' => array(
          __( 'Left',  RPMW_TEXTDOMAIN  ) => 'left',
          __( 'Center',  RPMW_TEXTDOMAIN  ) => 'center',
          __( 'Right',  RPMW_TEXTDOMAIN  ) => 'right',
        ),
        'std' => 'left',
        'group' => 'Content',
        "description" => __( "Select Alignment For Tag.", RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Spacing For Tag ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_tag_spacing',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Content',
        'description' => __('Set the tag title bottom spacing eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    array(
        "type" => "label",
        "class" => ' ',
        "param_name" => "rpmw_product_name_label",
        "heading" => esc_html__('Product Name', RPMW_TEXTDOMAIN),
        "value" => __(' ', RPMW_TEXTDOMAIN), 
        'group' => 'Content',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ), 
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Product Name Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_product_name_color",
        "value" => '#e74c3c', 
        'group' => 'Content',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Product Name Hover Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_product_name_hover_color",
        "value" => '#e74c3c', 
        'group' => 'Content',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Product Name Font Size ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_product_name_font_size',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Content',
        'description' => __('Set the font size eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Product Name Alignment',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_product_name_aligment',
        'value' => array(
          __( 'Left',  RPMW_TEXTDOMAIN  ) => 'left',
          __( 'Center',  RPMW_TEXTDOMAIN  ) => 'center',
          __( 'Right',  RPMW_TEXTDOMAIN  ) => 'right',
        ),
        'std' => 'left',
        'group' => 'Content',
        "description" => __( "Select Alignment For Tag.", RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Spacing For Product Name ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_product_name_spacing',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Content',
        'description' => __('Set the Product Name bottom spacing eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    array(
        "type" => "label",
        "class" => ' ',
        "param_name" => "rpmw_product_price_label",
        "heading" => esc_html__('Product Price', RPMW_TEXTDOMAIN),
        "value" => __(' ', RPMW_TEXTDOMAIN), 
        'group' => 'Content',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ), 
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Product Price Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_product_price_color",
        "value" => '#e74c3c', 
        'group' => 'Content',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Product Price Hover Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_product_price_hover_color",
        "value" => '#e74c3c', 
        'group' => 'Content',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Product Price Font Size ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_product_price_font_size',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Content',
        'description' => __('Set the font size eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Product Price Alignment',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_product_price_aligment',
        'value' => array(
          __( 'Left',  RPMW_TEXTDOMAIN  ) => 'left',
          __( 'Center',  RPMW_TEXTDOMAIN  ) => 'center',
          __( 'Right',  RPMW_TEXTDOMAIN  ) => 'right',
        ),
        'std' => 'left',
        'group' => 'Content',
        "description" => __( "Select Alignment For Tag.", RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Spacing For Product Price ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_product_price_spacing',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Content',
        'description' => __('Set the Product Price bottom spacing eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Rating Star Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_rating_star_color",
        "value" => '#e74c3c', 
        'group' => 'Rating Star Style',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Rating Star Unmarked Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_rating_star_unmarked_color",
        "value" => '#e74c3c', 
        'group' => 'Rating Star Style',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Rating Star Size ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_rating_star',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Rating Star Style',
        'description' => __('Set the size eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Select Alignment',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_rating_aligment',
        'value' => array(
          __( 'Left',  RPMW_TEXTDOMAIN  ) => 'left',
          __( 'Center',  RPMW_TEXTDOMAIN  ) => 'center',
          __( 'Right',  RPMW_TEXTDOMAIN  ) => 'flex-end',
        ),
        'std' => 'center',
        'group' => 'Rating Star Style',
        "description" => __( "Select Alignment.", RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Spacing Bottom ',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_rating_star_spacing_bottom',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Rating Star Style',
        'description' => __('Set the Rating Star Bottom spacing eg. 25px or 1.5em ', RPMW_TEXTDOMAIN)
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Text Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_button_text_color",
        "value" => '#e74c3c', 
        'group' => 'Button Style',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Background Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_button_background_color",
        "value" => '#e74c3c', 
        'group' => 'Button Style',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Text Hover Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_button_text_hover_color",
        "value" => '#e74c3c', 
        'group' => 'Button Style',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        "type" => "colorpicker",
        "class" => ' ',
        "heading" => esc_html__('Background Hover Color', RPMW_TEXTDOMAIN),
        "param_name" => "rpmw_button_background_hover_color",
        "value" => '#e74c3c', 
        'group' => 'Button Style',
        'edit_field_class' => 'vc_col-sm-6',
        "description" => __( ' ', RPMW_TEXTDOMAIN )
    ),
    array(
        'type' => 'textfield',
        'heading' => __( 'Border Radius',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_button_border_radius',
        'value' => __(' ', RPMW_TEXTDOMAIN),
        'group' => 'Button Style',
        'description' => esc_html__('Set border radius', RPMW_TEXTDOMAIN)
    ),
    array(
        'type' => 'dropdown',
        'heading' => __( 'Alignment',  RPMW_TEXTDOMAIN ),
        'param_name' => 'rpmw_button_aligment',
        'value' => array(
          __( 'Left',  RPMW_TEXTDOMAIN  ) => 'left',
          __( 'Center',  RPMW_TEXTDOMAIN  ) => 'center',
          __( 'Right',  RPMW_TEXTDOMAIN  ) => 'right',
        ),
        'std' => 'left',
        'group' => 'Button Style',
    ),
    array(  
        "type" => "css_editor",
        "class" => ' ',
        "heading" => __( "Field Label", RPMW_TEXTDOMAIN ),
        "param_name" => "rpmw_design_options",
        "value" => ' ', 
        'group' => 'Design Options',
        "description" => __( "Enter description.", RPMW_TEXTDOMAIN )
    ),
    
);
 
/*
 * Params
 */
$params = array(
    'name' => esc_html__('Related Product Manager Layout', RPMW_TEXTDOMAIN),
    'description' => esc_html__('Display Related Product Manager Layout.', RPMW_TEXTDOMAIN),
    'base' => 'related_product_manager_card_layout',
    'class' => 'cewb_element_wrapper',
    'controls' => 'full',
    'icon' => ' ',
    'category' => esc_html__('Related Product Manager', RPMW_TEXTDOMAIN),
    'show_settings_on_create' => true,
    'params' => $related_product_fields
);
vc_map($params);
