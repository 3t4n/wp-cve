<?php 

use Adminz\Admin\Adminz as Adminz;
use Adminz\Helper\ADMINZ_Helper_Flatsome_Portfolio as ADMINZ_Helper_Flatsome_Portfolio;
use Adminz\Admin\ADMINZ_Flatsome as ADMINZ_Flatsome;

// builder
add_action('ux_builder_setup', function (){
    $repeater_col_spacing = 'normal';
    $repeater_columns = '4';
    $repeater_type = 'slider'; 
    $default_text_align = "left";
    $repeater_col_spacing = "normal";   
    $options = array(
    'portfolio_meta' => array(
        'type' => 'group',
        'heading' => __( 'Appearance' ),
        'options' => array(
            'style' => array(
                'type' => 'select',
                'heading' => __( 'Style' ),
                'default' => 'bounce',
                'options' => require( get_template_directory().'/inc/builder/shortcodes/values/box-layouts.php' )
            ),

            'lightbox' => array(
                'type' => 'radio-buttons',
                'heading' => __('Lightbox'),
                'default' => '',
                'options' => array(
                    ''  => array( 'title' => 'Off'),
                    'true'  => array( 'title' => 'On'),
                ),
            ),

            'lightbox_image_size' => array(
                'type'       => 'select',
                'heading'    => __( 'Lightbox Image Size' ),
                'conditions' => 'lightbox == "true"',
                'default'    => '',
                'options'    => array(
                    ''          => 'Default',
                    'large'     => 'Large',
                    'medium'    => 'Medium',
                    'thumbnail' => 'Thumbnail',
                    'original'  => 'Original',
                )
            ),

            'posts_per_page' => array(
                'type' => 'textfield',
                'heading' => 'Posts per page',
                //'conditions' => 'ids == ""',
                'default' => get_option('posts_per_page'),
            ),
            'offset' => array(
                'type' => 'textfield',
                'heading' => 'Offset',
                //'conditions' => 'ids == ""',
                'default' => '',
            ),

            'orderby' => array(
                'type' => 'select',
                'heading' => __( 'Order By' ),
                'default' => 'menu_order',
                //'conditions' => 'ids == ""',
                'options' => array(
                    'title' => 'Title',
                    'name' => 'Name',
                    'date' => 'Date',
                    'menu_order' => 'Menu Order',
                )
            ),
            'order' => array(
                'type' => 'select',
                'heading' => __( 'Order' ),
                //'conditions' => 'ids == ""',
                'default' => 'desc',
                'options' => array(
                  'desc' => 'DESC',
                  'asc' => 'ASC',
                )
            ),  
            'is_ajax' => array(
                'type' => 'radio-buttons',
                'heading' => __('Load Ajax'),
                'default' => 'true',
                'options' => array(
                    'false'  => array( 'title' => 'Off'),
                    'true'  => array( 'title' => 'On'),
                ),
            ),      
            'ajax_type' => array(
                'type' => 'select',
                'heading' => __( 'Ajax type' ),
                'conditions' => 'is_ajax == "true" && (type == "row")',
                'default' => 'pagination',
                'options' => array(
                    'pagination' => 'Pagination',
                    'loadmore' => 'Load More',
                ),
                'description'=> 'Load more only for type Row'
            ), 
            'loadmore_text' => array(
                'type' => 'textfield',
                'heading' => 'Load more text',
                'conditions' => 'ajax_type == "loadmore"',
                'default' => __("Load more"),
            ),     
        ),
    ),
    'portfolio_tax' => array(
        'type' => 'group',
        'heading' => __( 'Filter Taxonomies' ),
        'options' => array(
            'cat' => array(
                'type' => 'select',
                'heading' => 'Fixed Category',
                //'conditions' => 'ids == ""',            
                'param_name' => 'slug',
                'config' => array(
                    'multiple'=> true,
                    'placeholder' => 'Select..',
                    'termSelect' => array(
                        'post_type' => 'featured_item',
                        'taxonomies' => 'featured_item_category'
                    ),
                )
            ),
            'tag' => array(
                'type' => 'select',
                'heading' => 'Fixed tag',
                //'conditions' => 'ids == ""',            
                'param_name' => 'slug',
                'config' => array(
                    'multiple'=> true,
                    'placeholder' => 'Select..',
                    'termSelect' => array(
                        'post_type' => 'featured_item',
                        'taxonomies' => 'featured_item_tag'
                    ),
                )
            )
        )
    ),
    'portfolio_metakey' => array(
        'type' => 'group',
        'heading' => __( 'Filter Meta Value' ),
        'options' => array()
    ),
    'layout_options' => require( get_template_directory().'/inc/builder/shortcodes/commons/repeater-options.php' ),
    'layout_options_slider' => require( get_template_directory().'/inc/builder/shortcodes/commons/repeater-slider.php' ),
    );

    $custom_tax = ADMINZ_Helper_Flatsome_Portfolio::get_featured_custom_tax(['featured_item_category','featured_item_tag']);
    if(!empty($custom_tax) and is_array($custom_tax)){
        foreach ($custom_tax as $key => $value) {
            $options['portfolio_tax']['options'][$key] = array(
                'type' => 'select',
                'heading' => "Fixed ". $value,
                'default'=>'',
                //'conditions' => 'ids == ""',
                'config' => array(
                    'placeholder' => 'Select..',
                    'termSelect' => array(
                        'post_type' => 'featured_item',
                        'taxonomies' => $key
                    ),
                )
            );
        }        
    }

    $meta_key_builder = ADMINZ_Helper_Flatsome_Portfolio::get_list_meta_key_builder('featured_item');
    $list_all = $meta_key_builder['list_all'];
    $list_metakey = $meta_key_builder['list_metakey'];

    if(!empty($list_metakey)){
        $options['portfolio_metakey']['options']['fixed_metakey'] = [
            'type' => 'select',
            'heading' => "Fixed Meta key",
            //'conditions' => 'ids == ""',
            'options'=> $list_metakey
        ];
    }
    if(!empty($list_all)){
        foreach($list_all as $metakey=> $values){            
            $options['portfolio_metakey']['options']['fixed_metakey_'.$metakey] = [
                'type' => 'select',
                'heading' => "Fixed: ".$metakey,
                'conditions' => 'fixed_metakey == "'.$metakey.'"',
                'options'=> $values
            ];
        }            
    }
    $box_styles = require( get_template_directory().'/inc/builder/shortcodes/commons/box-styles.php' );
    $options = array_merge($options, $box_styles);

    $advanced = array('advanced_options' => require( get_template_directory().'/inc/builder/shortcodes/commons/advanced.php'));
    $options = array_merge($options, $advanced);

    add_ux_builder_shortcode('adminz_flatsome_portfolios_search_result', array(
        'name'      => "Custom ". ADMINZ_Helper_Flatsome_Portfolio::$customname,
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'portfolio' . '.svg',
        'scripts' => array(
            'flatsome-masonry-js' => get_template_directory_uri() .'/assets/libs/packery.pkgd.min.js',
            'flatsome-isotope-js' => get_template_directory_uri() .'/assets/libs/isotope.pkgd.min.js',
        ),
        'info'      => '{{ id }}',
        'options' => $options
    ));
});




// content shortcode
add_shortcode('adminz_flatsome_portfolios_search_result', 'adminz_flatsome_portfolios_search_result_func');
function adminz_flatsome_portfolios_search_result_func($atts, $content = null, $tag = '' ) {
    $default_atts = array(
        'filter' => '',
        'filter_nav' => 'line-grow',
        'filter_align' => 'center',
        '_id' => 'portfolio-'.rand(),
        'link' => '',
        'class' => '',
        'visibility' => '',
        'orderby' => 'menu_order',
        'order' => '',
        'offset' => '',
        'exclude' => '',
        'number'  => '999',
        'ids' => '',
        'cat' => '',
        'tag'=> '',
        'lightbox' => '',
        'lightbox_image_size' => 'original',
        'posts_per_page'=> get_option('posts_per_page'),
        'is_ajax'=> 'true',
        'ajax_type'=> 'pagination',
        'loadmore_text'=> __("Load more"),
        // Layout
        'style' => '',
        'columns' => '4',
        'columns__sm' => '',
        'columns__md' => '',
        'col_spacing' => 'normal',
        'type' => 'slider', // slider, row, masonery, grid
        'width' => '',
        'grid' => '1',
        'grid_height' => '600px',
        'grid_height__md' => '500px',
        'grid_height__sm' => '400px',
        'slider_nav_style' => 'reveal',
        'slider_nav_position' => '',
        'slider_nav_color' => '',
        'slider_bullets' => 'false',
        'slider_arrows' => 'true',
        'auto_slide' => 'false',
        'infinitive' => 'true',
        'depth' => '',
        'depth_hover' => '',

         // Box styles
        'animate' => '',
        'text_pos' => '',
        'text_padding' => '',
        'text_bg' => '',
        'text_color' => '',
        'text_hover' => '',
        'text_align' => 'left',
        'text_size' => '',
        'image_size' => 'medium',
        'image_mask' => '',
        'image_width' => '',
        'image_radius' => '',
        'image_height' => '100%',
        'image_hover' => '',
        'image_hover_alt' => '',
        'image_overlay' => '',

        // Deprecated
        'height' => '',
    );
    ob_start();    


    $custom_tax = ADMINZ_Helper_Flatsome_Portfolio::get_featured_custom_tax(['featured_item_category','featured_item_tag']);
    if(!empty($custom_tax) and is_array($custom_tax)){
        foreach ($custom_tax as $key => $value) {
            $default_atts[$key] = "";
        }        
    }

    $meta_key_builder = ADMINZ_Helper_Flatsome_Portfolio::get_list_meta_key_builder('featured_item');
    $list_all = $meta_key_builder['list_all'];
    $list_metakey = $meta_key_builder['list_metakey'];
    if(!empty($list_metakey)){
        $default_atts['fixed_metakey'] = '';
    }
    if(!empty($list_all)){
        foreach($list_all as $metakey=> $values){
            $default_atts['fixed_metakey_'.$metakey] = '';
        }            
    }
    

    extract(shortcode_atts($default_atts, $atts));

    $taxonomies = get_object_taxonomies( 'featured_item', 'objects' );    
    $tax_arr = [];
    if(!empty($taxonomies) and is_array($taxonomies)){
        foreach ($taxonomies as $key => $value) {
            $tax_arr[] = $key;
        }
    }
    $meta_keys = ADMINZ_Flatsome::adminz_get_all_meta_keys('featured_item');
    $key_arr = [];
    if(!empty($meta_keys) and is_array($meta_keys)){
        foreach ($meta_keys as $value) {
            if($value){
                $key_arr[] = $value;
            }            
        }
    }  
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    if(wp_doing_ajax()){
        if(isset($atts['page'])){
            $paged = $atts['page'];
        }
    }
    $args = [
        'post_type'=> ['featured_item'],    
        'posts_per_page' => get_option( 'posts_per_page' ),
        'paged' => $paged,
        'tax_query' => [
            'relation'=> 'AND',            
        ],
        'meta_query' => [
            'relation'=> 'AND',            
        ],
    ];    
    if(!empty($_GET) and is_array($_GET)){        
        foreach ($_GET as $key => $value) {
            if(in_array($key,$tax_arr)){
                $args['tax_query'][] = [
                    'taxonomy' => $key,
                    'field' => 'slug',
                    'terms' => explode(",",$value),
                    'include_children' => true,
                    'operator' => 'IN'
                ];
            }
            if(in_array($key,$key_arr)){
                $args['meta_query'][] = [
                    'key' => $key,
                    'value' => $value,
                    'type' => 'CHAR',                                                    
                    'compare' => '='
                ];
            }
            if($key == "search"){
                $args['s'] = $value;
            }
        }
    }   
    if($order){
        $args['order'] = $order;
    }
    if($orderby){
        $args['orderby'] = $orderby;
    }
    if($offset){
        $args['offset'] = $offset;
    }
    if($posts_per_page){
        $args['posts_per_page'] = $posts_per_page;
    }
    if($cat){
        $args['tax_query'][] = [
            'taxonomy' => "featured_item_category",
            'field' => 'id',
            'terms' => explode(",",$cat),
            'include_children' => true,
            'operator' => 'IN'
        ];
    }
    if($tag){
        $args['tax_query'][] = [
            'taxonomy' => "featured_item_tag",
            'field' => 'id',
            'terms' => explode(",",$tag),
            'include_children' => true,
            'operator' => 'IN'
        ];
    }
    if(!empty($custom_tax) and is_array($custom_tax)){
        foreach ($custom_tax as $key => $value) {
            
            if($$key){
                $args['tax_query'][] = [
                    'taxonomy' => $key,
                    'field' => 'id',
                    'terms' => explode(",",$$key),
                    'include_children' => true,
                    'operator' => 'IN'
                ];
            }
        }        
    }
    if(isset($fixed_metakey)){
        $metavalue = isset(${'fixed_metakey__'.$fixed_metakey})? ${'fixed_metakey__'.$fixed_metakey} : false;
        if($metavalue){
            $args['meta_query'][] = [
                'key'=> $fixed_metakey,
                'value'=>$metavalue,
                'compare'=> '='
            ];
        }
    }

    if($height && !$image_height) $image_height = $height;

    // Get Default Theme style
    if(!$style) $style = flatsome_option('portfolio_style');

    // Fix old
    if($tag == 'featured_items_slider') $type = 'slider';

    // Set Classes.
    $wrapper_class = array( 'portfolio-element-wrapper', 'has-filtering', 'relative' );
    $classes_box   = array( 'portfolio-box', 'box', 'has-hover' );
    $classes_image = array();
    $classes_text  = array( 'box-text' );

    // Fix Grid type
    if($type == 'grid'){
    $columns = 0;
    $current_grid = 0;
    // $grid = flatsome_get_grid($grid);
    $grid = apply_filters('adminz_flatsome_get_grid',flatsome_get_grid($grid),$grid);
    $grid_total = count($grid);
    flatsome_get_grid_height($grid_height, $_id);
    }

    // Wrapper classes.
    if ( $visibility ) $wrapper_class[] = $visibility;

    // Set box style
    if($style) $classes_box[] = 'box-'.$style;
    if($style == 'overlay') $classes_box[] = 'dark';
    if($style == 'shade') $classes_box[] = 'dark';
    if($style == 'badge') $classes_box[] = 'hover-dark';
    if($text_pos) $classes_box[] = 'box-text-'.$text_pos;
    if($style == 'overlay' && !$image_overlay) $image_overlay = true;

    // Set image styles
    if($image_hover)  $classes_image[] = 'image-'.$image_hover;
    if($image_hover_alt)  $classes_image[] = 'image-'.$image_hover_alt;
    if($image_height)  $classes_image[] = 'image-cover';

    // Text classes
    if($text_hover) $classes_text[] = 'show-on-hover hover-'.$text_hover;
    if($text_align) $classes_text[] = 'text-'.$text_align;
    if($text_size) $classes_text[] = 'is-'.$text_size;
    if($text_color == 'dark') $classes_text[] = 'dark';

    $css_col = array(
    array( 'attribute' => 'border-radius', 'value' => $image_radius, 'unit' => '%'),
    );

    $css_args_img = array(
    array( 'attribute' => 'border-radius', 'value' => $image_radius, 'unit' => '%'),
    array( 'attribute' => 'width', 'value' => $image_width, 'unit' => '%' ),
    );

    $css_image_height = array(
    array( 'attribute' => 'padding-top', 'value' => $image_height),
    );

    $css_args = array(
        array( 'attribute' => 'background-color', 'value' => $text_bg ),
        array( 'attribute' => 'padding', 'value' => $text_padding ),
    );


    if($animate) {$animate = 'data-animate="'.$animate.'"';}


    if(!wp_doing_ajax()):
    echo '<div id="' . esc_attr($_id) . '" class="' . implode( ' ', $wrapper_class ) . '">';
    endif;



        // Add filter
        if($filter && $filter != 'disabled' && empty($cat) && $type !== 'grid' && $type !== 'slider' && $type !== 'full-slider'){
        // TODO: Get categories for filtering.
        wp_enqueue_script('flatsome-isotope-js');
        ?>
        <div class="container mb-half">
            <ul class="nav nav-<?php echo esc_attr($filter);?> nav-<?php echo esc_attr($filter_align) ;?> nav-<?php echo esc_attr($filter_nav);?> nav-uppercase filter-nav">
            <li class="active"><a href="#" data-filter="*"><?php echo __('All','administrator-z'); ?></a></li>
            <?php
              $tax_terms = get_terms('featured_item_category');
              foreach ($tax_terms as $key => $value) {
                 ?><li><a href="#" data-filter="[data-terms*='<?php echo "&quot;" . esc_attr($value->name) . "&quot;"; ?>']"><?php echo esc_attr($value->name); ?></a></li><?php
              }
            ?>
            </ul>
        </div>
        <?php
        } else{
          $filter = false;
        }

        // Repeater options
        $repeater['id'] = $_id;
        $repeater['tag'] = $tag;
        $repeater['type'] = $type;
        $repeater['style'] = $style;
        $repeater['class'] = $class . "flatsome-repeater";
        $repeater['visibility'] = $visibility;
        $repeater['slider_style'] = $slider_nav_style;
        $repeater['slider_nav_color'] = $slider_nav_color;
        $repeater['slider_nav_position'] = $slider_nav_position;
        $repeater['slider_bullets'] = $slider_bullets;
        $repeater['auto_slide'] = $auto_slide;
        $repeater['infinitive'] = $infinitive;
        $repeater['row_spacing'] = $col_spacing;
        $repeater['row_width'] = $width;
        $repeater['columns'] = $columns;
        $repeater['columns__sm'] = $columns__sm;
        $repeater['columns__md'] = $columns__md;
        $repeater['depth'] = $depth;
        $repeater['depth_hover'] = $depth_hover;
        $repeater['filter'] = $filter;

        $the_query = new WP_Query($args);



        // Get repeater structure 

        // nếu là đang ajax và  ajax_type là loadmore thì ko gọi repeater
        if(!(wp_doing_ajax() and $ajax_type == 'loadmore')){
            get_flatsome_repeater_start($repeater);
        }


        if ( $the_query->have_posts()) { ?>
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <?php require( __DIR__.'/inc/single-portfolio.php'); ?>
            <?php endwhile; 
        }


        if(!(wp_doing_ajax() and $ajax_type == 'loadmore')){
            get_flatsome_repeater_end($repeater);
        }
        


        wp_reset_postdata();


        if($is_ajax == 'true'):

            if($ajax_type == 'pagination'){
                $htmlshortcode_start  = "[adminz_flatsome_portfolios_search_result";
                foreach ((array)$atts as $key => $value) {
                    $htmlshortcode_start .= " ". $key ."='".$value."'";
                }
                // paged - copied from flatsome/ inc/ structure/ structure-posts.php
                $prev_arrow = is_rtl() ? get_flatsome_icon('icon-angle-right') : get_flatsome_icon('icon-angle-left');
                $next_arrow = is_rtl() ? get_flatsome_icon('icon-angle-left') : get_flatsome_icon('icon-angle-right');


                $total = $the_query->max_num_pages; 

                if( $total > 1 )  {
                    $current_page = $the_query->query['paged'];
                    $pages = paginate_links(array(
                        'current'       => max( 1, $current_page ),
                        'total'         => $total,
                        'mid_size'      => 3,
                        'type'          => 'array',
                        'prev_text'     => $prev_arrow,
                        'next_text'     => $next_arrow,
                    ) );
                    if( is_array( $pages ) ) {            
                        echo '<ul class="page-numbers nav-pagination links text-center adminz-page-numbers">';
                        foreach ( $pages as $key => $page ) {
                            $page = str_replace("page-numbers","page-number",$page);
                            $page = str_replace("<a", '<a data-shortcode-start="'.$htmlshortcode_start.'"',$page);
                            echo "<li>".do_shortcode($page)."</li>";
                        }
                       echo '</ul>';
                    }
                }
            }

            if($type = 'row' and $ajax_type == 'loadmore' and !wp_doing_ajax()){
                ?>
                <div class="row row-small">
                    <div class="col text-center">
                        <button  
                            <?php
                                if($the_query->query['paged'] == $the_query->max_num_pages){
                                    echo 'disabled';
                                }
                            ?>
                            data-paged='<?php echo $the_query->query['paged'] ?>'
                            data-max_num_pages='<?php echo $the_query->max_num_pages ?>'
                            data-atts='<?php echo json_encode($atts) ?>'
                            data-query_args='<?php echo json_encode($the_query->query) ?>'
                            class="loadmore_btn button primary is-outline is-large mb-0">
                            <?php echo esc_attr($loadmore_text); ?>
                        </button>
                        <script type="text/javascript">
                            jQuery(document).ready(function($){
                                $("body").on("click",".loadmore_btn",function(){
                                    jQuery.ajax({
                                        type : "post",
                                        dataType : "json",
                                        url : '<?php echo admin_url('admin-ajax.php'); ?>',
                                        data : {
                                            action: "a_f_p_s_r_action_loadmore",
                                            query_args: JSON.parse($(this).attr("data-query_args")),
                                            atts: JSON.parse($(this).attr('data-atts')),
                                            paged: $(this).attr('data-paged')
                                        },
                                        context: this,
                                        beforeSend: function(){
                                            $(this).addClass("processing");
                                            $(this).attr("disabled","disabled");
                                        },
                                        success: function(response) {
                                            $(this).removeClass("processing");
                                            
                                            if(response.data){
                                                console.log(response.data);
                                                $(this).closest(".portfolio-element-wrapper").find(".flatsome-repeater").append(response.data.html);
                                                $(this).attr("data-paged", response.data.paged);
                                                if(response.data.html){
                                                    $(this).removeAttr("disabled");
                                                }
                                                if(response.data.paged == $(this).attr("data-max_num_pages")){
                                                    $(this).attr("disabled","disabled");
                                                }
                                            }
                                        },
                                        error: function( jqXHR, textStatus, errorThrown ){
                                            console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
                                        }
                                    });
                                });
                            });
                            
                        </script>
                    </div>
                </div>
                <?php
            }
        endif;

    if(!wp_doing_ajax()):
    echo '</div>';
    endif;
    ?>
    
    <?php if(!wp_doing_ajax()): ?>
        <?php if($is_ajax == 'true'): ?>
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    $("body").on("click","#<?php echo $_id; ?> .adminz-page-numbers a",function(){
                        let shortcode_start = $(this).attr("data-shortcode-start");
                        //get page 
                        let current_page = parseInt($(this).closest(".adminz-page-numbers").find(".current").html());
                        let page = $(this).html();

                        if($(this).hasClass("prev")){
                            page = current_page - 1;
                        }
                        if($(this).hasClass("next")){
                            page = current_page +1;
                        }

                        let final_shortcode = shortcode_start + " page='"+page+"'" + ']';
                        let atag = $(this);
                        let wrapper = atag.closest('.portfolio-element-wrapper');
                        $.ajax({
                            type : "post",
                            dataType : "json",
                            url : '<?php echo admin_url('admin-ajax.php'); ?>',
                            data : {
                                action: "a_f_p_s_r_action",
                                shortcode: final_shortcode
                            },
                            context: this,
                            beforeSend: function(){
                                wrapper.append('<div class="loading-spin dark large centered"></div>');
                            },
                            success: function(response) {
                                if(response.data){
                                    wrapper.empty();
                                    wrapper.prepend(response.data);
                                    wrapper.find(".loading-spin").remove();
                                    // call slider 
                                    wrapper.find(".slider").each(function(){
                                        let option = $(this).attr('data-flickity-options');
                                        $(this).flickity(JSON.parse(option));
                                    });
                                }
                                //console.log(response.data);
                            },
                            error: function( jqXHR, textStatus, errorThrown ){
                                console.log( 'Administrator Z: The following error occured: ' + textStatus, errorThrown );
                            }
                        });
                        return false;
                    });
                });
            </script>
        <?php endif; ?>
    <?php endif; ?>
    <?php
    return ob_get_clean();
};
add_action( 'wp_ajax_a_f_p_s_r_action', 'adminz_flatsome_portfolios_search_result_call_ajax' );
add_action( 'wp_ajax_nopriv_a_f_p_s_r_action', 'adminz_flatsome_portfolios_search_result_call_ajax' );
function adminz_flatsome_portfolios_search_result_call_ajax(){
    ob_start();
    echo do_shortcode(str_replace("\'", '"', sanitize_text_field($_POST['shortcode'])));
    $html = ob_get_clean();
    wp_send_json_success($html);
    wp_die();
}

add_action( 'wp_ajax_a_f_p_s_r_action_loadmore', 'adminz_flatsome_portfolios_search_result_call_ajax_loadmore' );
add_action( 'wp_ajax_nopriv_a_f_p_s_r_action_loadmore', 'adminz_flatsome_portfolios_search_result_call_ajax_loadmore' );
function adminz_flatsome_portfolios_search_result_call_ajax_loadmore(){
    $return = [];

    $atts = $_POST['atts'];
    $atts['page'] = $_POST['paged'] +1;
    // echo "<pre>";print_r($atts);echo "</pre>";die;
    ob_start();
    echo adminz_flatsome_portfolios_search_result_func($atts);
    $return['html'] = trim(ob_get_clean());
    // die;
    if(!$return['html']){
        $atts['page'] -=1;
    }
    $return['paged'] = $atts['page'];
    wp_send_json_success($return);
    wp_die();
}

