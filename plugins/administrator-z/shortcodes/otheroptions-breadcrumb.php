<?php 
use Adminz\Admin\Adminz as Adminz;
final class adminz_breadcrumb{
    public $breadcumnb_exist = false;
    public function __construct(){

    }
    public function init(){        
        $this->add_uxbuilder_element();
        $this->add_shortcode();
    }

    function add_uxbuilder_element(){
        add_action('ux_builder_setup', function(){
            $options   = array(
                'name'      => __('Breadcrumb'),
                'category'  => Adminz::get_adminz_menu_title(),        
                'info'      => '{{ text }}',
                'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'woo_breadcrumbs' . '.svg',
                'options' => array(
                    
                ),
            );

            $other_plugins = [
                'type' => 'select',
                'heading' => 'Use Integrations plugin',
                'default' => '',
                'options' => [
                    '' => Adminz::get_adminz_menu_title()
                ]
            ];

            if(class_exists('WPSEO_Admin_Init')){
                $other_plugins['options']['wpseo'] = 'Yoast SEO';
            }
            if(class_exists('RankMath')){
                $other_plugins['options']['rankmath'] = 'RankMath';
            }
            
            $options['options']['integrations'] = $other_plugins;
            $options['options']['seperator']= array(
                'type'       => 'textfield',                
                'heading'    => 'Seperator',
                'default'    => '/',
                'conditions' => 'integrations == ""',
            );

            $options['options']['hide_current_item']= array(
                'type'       => 'checkbox',                
                'heading'    => 'Hide current_item',
                'default'    => '',
                'conditions' => 'integrations == ""',
            );

            $options['options']['post_title_options'] = array(
                'type' => 'group',
                'heading' => __( 'Title' ),
                    'options' => array(
                        'size' => array(
                            'type' => 'select',
                            'heading' => 'Font Size',
                            'default' => '',                            
                            'options' => require( get_template_directory().'/inc/builder/shortcodes/values/sizes.php' )
                        ),
                        'style' => array(
                            'type' => 'radio-buttons',
                            'heading' => 'Font Style',
                            'default' => 'uppercase',
                            'options' => array(
                                'normal'   => array( 'title' => 'Abc'),
                                'uppercase' => array( 'title' => 'ABC'),
                            )
                    ),
                )
            );
            $options['options']['advanced_options'] = require( get_template_directory().'/inc/builder/shortcodes/commons/advanced.php');
            add_ux_builder_shortcode('adminz_breadcrumb', $options);
        });
    }

    function add_shortcode(){
        add_shortcode('adminz_breadcrumb',function ($atts) {
            if(!$this->breadcumnb_exist){
                extract(shortcode_atts(array(
                    '_id'=>"admz_".rand(),
                    'seperator'     =>'/',
                    'hide_current_item' => '',
                    'style'=> 'normal',
                    'size'=> '',
                    'class'=> 'vietnamtutor-breadcrumb',
                    'visibility'=> '',
                    'integrations'=>''
                ), $atts));   
                ob_start();    

                // Define
                global $post;
                $custom_taxonomy = apply_filters('adminz_breadcrumb_custom_taxonomy',''); // If you have custom taxonomy place it here

                $classes = 'breadcrumbs';
                if($style){
                    $classes .= " ".$style;
                }
                if($size){
                    $classes .= " is-".$size;
                }
                if($class){
                    $classes .= " ".$class;
                }
                if($visibility){
                    $classes .= " ".$visibility;
                }
                $defaults = array(
                    'seperator' => $seperator,
                    'id' => $_id,
                    'classes' => $classes,
                    'home_title' => get_the_title(get_option('page_on_front'))
                );
                $sep = '<li class="seperator separator">' . esc_html($defaults['seperator']) . '</li>';
                $position = 1;
                // Start the breadcrumb with a link to your homepage
                echo '<div class="' . do_shortcode($defaults['classes']) . '">';


                    if(!class_exists('RankMath') and ($integrations == 'rankmath')){
                        $integrations = 'rankmath';
                    }
                    if(!class_exists('WPSEO_Admin_Init') and ($integrations == 'wpseo')){
                        $integrations = 'wpseo';
                    }        
                    if($integrations=="wpseo"){
                        echo do_shortcode( '[wpseo_breadcrumb]' );
                    }
                    else if ($integrations=="rankmath"){
                        echo do_shortcode('[rank_math_breadcrumb] ');
                    }
                    else{
                        echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList" id="' . do_shortcode($defaults['id']) . '">';
                        do_action('adminz_breadcrumb_before_items');
                        // Creating home link
                        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item"><a itemprop="item" href="' . get_home_url() . '"><span itemprop="name">' . esc_html($defaults['home_title']) . '</span></a><meta itemprop="position" content="'.do_shortcode($position).'" /></li>' . apply_filters('the_title',$sep);
                        $position++;
                        if (is_single()) {
                            // Get posts type
                            $post_type = get_post_type();

                            // If post type is not post
                            if ($post_type != 'post') {
                                $post_type_object = get_post_type_object($post_type);
                                $post_type_link = get_post_type_archive_link($post_type);
                                if($post_type_object){
                                    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item item-cat"><a itemprop="item" href="' . do_shortcode($post_type_link) . '"><span itemprop="name">' . do_shortcode($post_type_object->labels->name) . '</span></a><meta itemprop="position" content="'.do_shortcode($position).'" /></li>' . apply_filters('the_title',$sep);
                                }
                                $position++;
                            }

                            // Get categories
                            $category = get_the_category($post->ID);

                            // If category not empty
                            if (!empty($category)) {
                                // Arrange category parent to child
                                $category_values = array_values($category);
                                $get_last_category = end($category_values);
                                // $get_last_category    = $category[count($category) - 1];
                                $get_parent_category = rtrim(get_category_parents($get_last_category->term_id, true, '+'), '+');
                                $cat_parent = explode('+', $get_parent_category);

                                // Store category in $display_category
                                $display_category = '';
                                foreach ($cat_parent as $p) {
                                    preg_match('#<a href="(.*)">(.*)</a>#', $p, $matches);
                                    $display_category .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item item-cat"><a itemscope itemtype="https://schema.org/WebPage" itemprop="item" itemid="'.$matches[1].'" href="'.$matches[1].'"><span itemprop="name">' . $matches[2] . '</span></a><meta itemprop="position" content="'.$position.'" /></li>' . $sep;
                                    $position++;
                                }
                            }

                            // If it's a custom post type within a custom taxonomy
                            $taxonomy_exists = taxonomy_exists($custom_taxonomy);

                            if (empty($get_last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
                                $taxonomy_terms = get_the_terms($post->ID, $custom_taxonomy);
                                $cat_id = $taxonomy_terms[0]->term_id;
                                $cat_link = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                                $cat_name = $taxonomy_terms[0]->name;
                            }

                            // Check if the post is in a category
                            if (!empty($get_last_category)) {
                                echo do_shortcode($display_category);
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item item-current"><span itemprop="name">' . get_the_title() . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            } else if (!empty($cat_id)) {
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item item-cat"><a itemprop="item" href="' . do_shortcode($cat_link) . '"><span itemprop="name">' . do_shortcode($cat_name) . '</span></a><meta itemprop="position" content="'.do_shortcode($position).'" /></li>' . apply_filters('the_title',$sep);
                                $position++;
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-current item"><span itemprop="name">' . get_the_title() . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            } else {
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-current item"><span itemprop="name">' . get_the_title() . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            }
                        } else if (is_archive()) {
                            if (is_tax()) {
                                // Get posts type
                                $post_type = get_post_type();

                                // If post type is not post
                                if ($post_type != 'post') {
                                    $post_type_object = get_post_type_object($post_type);
                                    $post_type_link = get_post_type_archive_link($post_type);
                                    if($post_type_object){
                                        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item item-cat item-custom-post-type-' . do_shortcode($post_type) . '"><a itemprop="item" href="' . do_shortcode($post_type_link) . '"><span itemprop="name">' . do_shortcode($post_type_object->labels->name) . '</span></a><meta itemprop="position" content="'.do_shortcode($position).'" /></li>' . apply_filters('the_title',$sep);
                                    }
                                    
                                    $position++;
                                }

                                $custom_tax_name = get_queried_object()->name;
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item item-current"><span itemprop="name">' . do_shortcode($custom_tax_name) . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            } else if (is_category()) {
                                $parent = get_queried_object()->category_parent;

                                if ($parent !== 0) {
                                    $parent_category = get_category($parent);
                                    $category_link = get_category_link($parent);
                                    if($parent_category){
                                        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item"><a itemprop="item" href="' . esc_url($category_link) . '"><span itemprop="name">' . do_shortcode($parent_category->name) . '</span></a><meta itemprop="position" content="'.do_shortcode($position).'" /></li>' . apply_filters('the_title',$sep);
                                    }
                                    $position++;
                                }

                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item item-current"><span itemprop="name">' . single_cat_title('', false) . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            } else if (is_tag()) {
                                // Get tag information
                                // $term_id = get_query_var('tag_id');
                                // $taxonomy = 'post_tag';
                                // $args = 'include=' . $term_id;
                                // $terms = get_terms($taxonomy, $args);
                                // $get_term_name = $terms[0]->name;
                                $get_term_name = get_queried_object()->name;

                                // Display the tag name
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-current item"><span itemprop="name">' . do_shortcode($get_term_name) . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            } else if (is_day()) {
                                // Day archive

                                // Year link
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-year item"><a itemprop="item" href="' . get_year_link(get_the_time('Y')) . '"><span itemprop="name">' . get_the_time('Y') . ' Archives</span></a><meta itemprop="position" content="'.do_shortcode($position).'" /></li>' . apply_filters('the_title',$sep);
                                $position++;

                                // Month link
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-month item"><a itemprop="item" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '"><span itemprop="name">' . get_the_time('M') . ' Archives</span></a><meta itemprop="position" content="'.do_shortcode($position).'" /></li>' . apply_filters('the_title',$sep);
                                $position++;

                                // Day display
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-current item"><span itemprop="name">' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            } else if (is_month()) {
                                // Month archive

                                // Year link
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-year item"><a itemprop="item" href="' . get_year_link(get_the_time('Y')) . '"><span itemprop="name">' . get_the_time('Y') . ' Archives</span></a><meta itemprop="position" content="'.do_shortcode($position).'" /></li>' . apply_filters('the_title',$sep);
                                $position++;

                                // Month Display
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-month item-current item"><span itemprop="name">' . get_the_time('M') . ' Archives</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            } else if (is_year()) {
                                // Year Display
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-year item-current item"><span itemprop="name">' . get_the_time('Y') . ' Archives</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            } else if (is_author()) {
                                // Auhor archive

                                // Get the author information
                                global $author;
                                $userdata = get_userdata($author);

                                // Display author name
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-current item"><span itemprop="name">' . 'Author: ' . do_shortcode($userdata->display_name) . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            } else {
                                $post_type_archive_title = post_type_archive_title('',false);
                                if(function_exists('is_shop')){
                                    if(is_shop()){
                                        $post_type_archive_title = get_the_title(get_option( 'woocommerce_shop_page_id' ));
                                    }
                                }
                                echo '
                                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item item-current">
                                    <span itemprop="name">' . $post_type_archive_title . '</span>
                                    <meta itemprop="position" content="'.do_shortcode($position).'" />
                                </li>';
                                $position++;
                            }
                        } else if (is_page()) {
                            // Standard page
                            if ($post->post_parent) {
                                // If child page, get parents
                                $anc = get_post_ancestors($post->ID);

                                // Get parents in the right order
                                $anc = array_reverse($anc);

                                // Parent page loop
                                if (!isset($parents)) $parents = null;
                                foreach ($anc as $ancestor) {
                                    $parents .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-parent item"><a itemprop="item" href="' . get_permalink($ancestor) . '"><span itemprop="name">' . get_the_title($ancestor) . '</span></a><meta itemprop="position" content="'.$position.'" /></li>' . $sep;
                                    $position++;
                                }

                                // Display parent pages
                                echo do_shortcode($parents);
                                // Current page
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-current item"><span itemprop="name">' . get_the_title() . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            } else {
                                // Just display current page if not parents
                                echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-current item"><span itemprop="name">' . get_the_title() . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                                $position++;
                            }
                        } else if (is_search()) {
                            // Search results page
                            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-current item"><span itemprop="name">Search results for: ' . get_search_query() . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                            $position++;
                        } else if (is_404()) {
                            // 404 page
                            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-current item"><span itemprop="name">' . 'Error 404' . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                            $position++;
                        } else if (is_home()){
                            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="item-current item"><span itemprop="name">' . get_the_title(get_option('page_for_posts', true) ) . '</span><meta itemprop="position" content="'.do_shortcode($position).'" /></li>';
                        }
                            
                        echo '</ol>';
                    }
                echo '</div> ';
                ?>
                <style type="text/css"> 
                    #<?php echo do_shortcode($_id); ?> {display: flex; 
                align-items: center; margin: 0; padding: 0; list-style: none; flex-wrap: wrap; line-height:1.6; } 
                    #<?php echo do_shortcode($_id); ?> li {margin: 0px; } 
                    #<?php echo do_shortcode($_id); ?> li.seperator{margin-right: 5px; margin-left: 5px; } 
                    #<?php echo do_shortcode($_id); ?> .item {text-decoration: none; } 
                    
                    <?php if($hide_current_item == "true"): ?>
                        #<?php echo do_shortcode($_id); ?> .item-current {display: none} 
                    <?php endif; ?>
                </style> 
                <?php
                $this->breadcumnb_exist = true;
                return apply_filters('adminz_output_debug',ob_get_clean());
            }
        });
    }
    

    
}
$bz = new adminz_breadcrumb;
$bz->init();