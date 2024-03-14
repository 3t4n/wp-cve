<?php
/**
 * Da_Post
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Softfirm <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developer.wordpress.org/themes/template-files-section/post-template-files/
 */
namespace Definitive_Addons_Elementor\Elements;
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use Elementor\Utils;
use \Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

/**
 * Da_Post
 *
 * @category Definitive,element,elementor,widget,addons
 * @package  Definitive_Addons_Elementor
 * @author   Display Name <contacts@softfirm.net>
 * @license  GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     https://developer.wordpress.org/themes/template-files-section/post-template-files/
 */
class Da_Post
{
    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct()
    {
    
    }
    
    /**
     * Get portfolio content
     *
     * @param string $post_order_by       post order by date or time
     * @param string $post_orders         post order ascending or descending 
     * @param string $no_of_column        number of column in grid
     * @param string $number_of_post      number of post in grid
     * @param string $column_gap          gap between columns
     * @param string $category_selection  one or more categories can be selected
     * @param string $enable_excerpt      excerpt can be enabled or disabled
     * @param string $hvr_style           image over style can be selected
     * @param string $category_exclude    categories can be excluded
     * @param string $post_grid_align     alignment of post in column
     * @param string $post_text_align     alignment of post text in column
     * @param string $enable_meta_content meta can be enabled or disabled
     * @param string $read_more_text      enter your read more text
     * @param string $enable_post_title   post title can be enabled or disabled
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return string of html.
     */
    public static function dafe_get_portfolio_post($post_order_by,$post_orders,$no_of_column,$number_of_post,
        $column_gap,$category_selection,$enable_excerpt,$hvr_style,$category_exclude,$post_grid_align,
        $post_text_align,$enable_meta_content,$read_more_text,$enable_post_title
    ) {
            
        $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        
        $loop = new \WP_Query(
            array(
            'post_type' => 'post',
            'cat' =>$category_selection,
            'posts_per_page' =>$number_of_post,
            'orderby' =>$post_order_by,
            'order' =>$post_orders,
            'post_status' => 'publish',
            'category__not_in'=>$category_exclude,
                    
            )
        );
    
        ?>
<div class="dafe-widget-portfolio-wrap">    
    
     <ul class="isotope-list nl_grid_row col_gap_<?php echo esc_attr($column_gap); ?>">
         
        <?php while ($loop->have_posts()) : $loop->the_post(); ?>
        
            <?php   
                                 
            $termsArray = get_the_terms($loop->ID, 'category');  //Get the terms for this particular item
            $termsString = ""; 
            foreach ( $termsArray as $term ) { 
                $termsString .= $term->slug.' '; 
            }
                         
            ?>


    <li class="dafe-portfolio-item <?php echo esc_attr($termsString); ?> item no_of_col_<?php echo esc_attr($no_of_column); ?> col_padd_margin">
            
      <div class="dafe-widget-portfolio-entry">
        
        <div class="dafe-widget-portfolio-media">

            <?php the_post_thumbnail($loop->ID,  array('title' => '')); ?>
  
            <div class="dafe-widget-portfolio-txt <?php echo esc_attr($hvr_style); ?>">
    
                <div class="dafe-portfolio-inner-content">    
    
                    <h4 class="dafe-portfolio-title">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>">
            <?php the_title(); ?>
                        </a>
                    </h4>
    
                
                        <a class="more-link" title="<?php the_title_attribute();?>" href="<?php the_permalink(); ?>">
            <?php echo esc_html($read_more_text); ?>
                        </a>

                
                </div>
            </div> 
        </div>
        
            <?php if ('yes' === $enable_post_title ) : ?>
        <h4 class="portfolio-title-down"><a href="<?php the_permalink(); ?>"  title="<?php the_title_attribute();?>">
                <?php the_title(); ?></a>
        </h4>
            <?php endif; ?>
        
            <?php if ('yes' === $enable_meta_content ) : ?>
        <div class="dafe-post-content-area">
        <!-- To display meta of blog post -->
                <?php	    
                if ('post' === get_post_type() ) : ?>
            <div class="dafe-post-entry-meta">
            <!-- Meta function calling -->
                    <?php Reuse::dafe_posted_on(); ?>
            </div><!-- .entry-meta -->
                    <?php
                endif; ?>
        <div class="dafe-post-entry-content">
        <!-- to show excerpt or full text of posts -->
                <?php 
                if (($enable_excerpt == 'yes')) {
                
                    the_excerpt();
                    
                } else {
                                    
                    the_content(
                        sprintf( 
                            __('Continue reading%s', 'definitive-addons-for-elementor'), 
                            '<span class="screen-reader-text">  '.get_the_title().'</span>' 
                        ) 
                    );

                }
                ?>                
        </div> <!-- end .entry-content -->
      
          <footer class="entry-footer">
                <?php if ('post' === get_post_type() ) {
                    $get_tags = get_the_tag_list('', esc_html__(', ', 'definitive-addons-for-elementor'));
                    if ($get_tags ) {
                        printf(/* translators: tag link*/ '<div class="tags-links">' . esc_html__('Tagged %1$s', 'definitive-addons-for-elementor') . '</div>', wp_kses_post($get_tags)); // WPCS: XSS OK.
                    }
                }
                ?>
        
        </footer><!-- .entry-footer -->
    </div>
    
            <?php endif; ?>
    </div>
    </li> <!-- Here  -->
    

<!-- end loop -->
            <?php
        endwhile;?>
</ul>
</div>
        <?php
        // Reset Post Data
        wp_reset_postdata(); 
    }
    
        /**
         * Get post categories with image
         *
         * @param array $category_data post categories
         *
         * @since Definitive Addons for Elementor 1.5.0
         *
         * @return string of html.
         */ 
    public static function dafe_get_cat_box($category_data )
    {
        
        $cat_link = get_category_link($category_data);
          $cat_name = get_term($category_data);
          $thumb_id = get_term_meta($category_data, 'thumbnail_id', true);
                            
          $product_image_url = wp_get_attachment_image_src($thumb_id, 'product-category-image');
                            
        if ($product_image_url) {
                            
            $product1_image_url = $product_image_url[0]; 
                            
        } else {
                                
            $product1_image_url = DAFE_URI . '/css/dummy-image.jpg';
        }
                            
        if (!empty($cat_name)) {
            $cats_name = $cat_name->name;
        } ?>
                            
                            <div class="product-category-box">
           <?php if ($product1_image_url ) { ?>
                                
                                
                                    <a  class="product-category-box-link" href="<?php echo esc_url($cat_link); ?>">
                                    <img  src="<?php echo esc_url($product1_image_url); ?>" alt="<?php echo esc_attr($cats_name); ?>" />
                                    
                                    </a>
                                
           <?php } ?>
                                <div class="product-category-box-text">
                                    
                                    <h5 class="product-cat-box-title"><a title="<?php echo esc_attr($cats_name); ?>" 
                                    href="<?php echo esc_url($cat_link); ?>">
            <?php echo esc_html($cats_name); ?></a>
                                    </h5>
                                    
                                    <div class="box-product-count">
                                    <a href="<?php echo esc_url($cat_link); ?>">
            <?php 
            if (!empty($cat_name)) {
                $number_of_prod = $cat_name->count;
            }
            if (!empty($number_of_prod)) { 
                echo absint($number_of_prod); 
            } ?> 
            <?php esc_html_e('Products', 'definitive-addons-for-elementor'); ?></a>
                                    </div>
                                </div>
                            </div> 
                            
         <?php 
    
        
    }
    
    /**
     * Get post categories with link
     *
     * @param array $cat_icon category icon
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return string of html.
     */ 
    public static function dafe_get_cat_name_link($cat_icon = [])
    {
        
         $cat_link = $cats_name = '';
         $catag = [];
        if ($cat_icon['cat_type'] == 'post') {
                        
            if (!empty($cat_icon['cat_selection'])) {
                $cat_link = get_category_link($cat_icon['cat_selection']);
                $cat_name = get_term($cat_icon['cat_selection']);
                if (!empty($cat_name)) {
                    $cats_name = $cat_name->name;
                }
            }
            return $catag = [$cats_name,$cat_link];
        }
                        
        if ($cat_icon['cat_type'] == 'product') {
            if (!empty($cat_icon['pcat_selection'])) {
                $cat_link = get_category_link($cat_icon['pcat_selection']);
                $cat_name = get_term($cat_icon['pcat_selection']);
                if (!empty($cat_name)) {
                    $cats_name = $cat_name->name;
                }
            }
            return $catag = [$cats_name,$cat_link];
        }
                        
    }
    
    /**
     * Get category list
     *
     * @param array $cat_icon       post category icons
     * @param array $post_name_link post category name
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return string of html.
     */ 
    public static function dafe_get_cat_list($cat_icon = [],$post_name_link = [])
    {
        $output = '';
        if ($cat_icon['cat_selection'] || $cat_icon['pcat_selection']) : 
                        
            $output = '<a href="'.$post_name_link[1].'" title="'.$post_name_link[0].'">';
            $output .='<span class="cat-name">'.$post_name_link[0].'</span>';
            $output .='</a>';        
             
        endif;
             
        return $output;
    
    }
    
    /**
     * Get post slider content
     *
     * @param string $ovl_width          width of post overlay
     * @param string $post_order_by      post order by date or time
     * @param string $post_orders        post order ascending or descending 
     * @param string $number_of_post     number of post in slider
     * @param string $category_selection one or more categories can be selected
     * @param string $slidesToShows      how many slides can be shown in a page
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return string of html.
     */
    public static function dafe_get_post_slider($ovl_width,$post_order_by,$post_orders,$number_of_post,
        $category_selection,$slidesToShows
    ) {
        
        
        $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        
        $loop = new \WP_Query(
            array(
            'post_type' => 'post',
            'cat' =>$category_selection,
            'posts_per_page' => $number_of_post,
                    
            'orderby' =>$post_order_by,
            'order' =>$post_orders,
                    
    
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1
            )
        );
            
        $id = uniqid();
        if ($loop->have_posts() ) : while( $loop->have_posts() ): $loop->the_post(); 
    
                if (has_post_thumbnail()) {
                    $slide1_src = get_the_post_thumbnail_url();
                } else {
                    $slide1_src = DAFE_URI . '/css/dummy-image.jpg';     
                }
                ?>
            <div id="<?php echo esc_attr($id); ?>" class="feature_slider_entry">
        <img src="<?php echo esc_url($slide1_src); ?>" alt="<?php the_title_attribute();?>" title="<?php the_title_attribute();?>" />
        
            <div class="da_feature_slide_border_abs <?php echo esc_attr($ovl_width); ?>">
                
                    <div class="da_feature_post_cta" >
                        <div class="da_slider-category"><?php the_category(); ?></div>
        
        
                            <a  title="<?php the_title_attribute();?>" href="<?php the_permalink(); ?>" target="_self">
                <?php	if ($slidesToShows == 1) {  ?>
                                <h3  class="da-slide-feature-title"><span><?php the_title(); ?></span></h3>
                            
                <?php } else {  ?>
                                <h4  class="da-slide-feature-title"><span><?php the_title(); ?></span></h4>
                            
                <?php	}  ?>
                            </a>
                        <div class="da-featured-slider-meta">
                <?php Reuse::dafe_posted_on(); ?>
                        </div>
            
                        
    
                    </div>
                
            </div> 
        </div>
    
    
                <?php         
            
        endwhile; 
        endif; 
        
    }
    
    /**
     * Get popular post template
     *
     * @param string $category_selection  one or more categories can be selected
     * @param string $popular_post_number number of post
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return string of html.
     */ 
    
    public static function dafe_get_popular_post_template($category_selection,$popular_post_number)
    {
        ?>
    <div class="nl_grid_row da-popular-post popular-post-grid col_gap_30">

    
        <?php
        $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
            
    
        $loop = new \WP_Query(
            array(
            'post_type' => 'post',
            'cat' =>wp_kses_post($category_selection), 
            'posts_per_page' =>intval($popular_post_number),
                    
            'orderby' => 'comment_count',
            'order' => 'desc',
                    
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1
            )
        );
    
            $col_count = 0;
            $col_no = 2; 
            
    
        if($loop->have_posts() ) : while( $loop->have_posts() ): $loop->the_post();  ?>
    
                <?php $col_count++;  ?>
                
            <div class="nl-blog-entry no_of_col_<?php echo esc_attr($col_no);  ?> col_no_<?php echo esc_attr($col_count);  ?> col_padd_margin" >

                <div class="blog-pop_border_style">
    
                        
                <?php if (has_post_thumbnail()) {
                    $src = get_the_post_thumbnail_url();
                } else {
                    $src = DAFE_URI . '/css/dummy-image.jpg';
                }
                ?>
                                <div  class="pop_post_thumbnail">
                        
                <?php  if (!empty($src)) { ?>
                                
                                
                                    <a href="<?php the_permalink(); ?>" target="_self">
                                        <img src="<?php echo esc_url($src) ?>" alt="<?php the_title_attribute(); ?>" />
                                    </a>
                                
                <?php    }  ?>
                                
                            
                                </div>
    
                            <div class="blog-pop-cta">
                            <div class="pop-title">
                            <a href="<?php the_permalink(); ?>" target="_self">
                            <h3 class="blog-title"><?php the_title(); ?></h3>
                            </a>
                            </div>
                            
                            </div>
                            
                            <div class="pop-txt">
                            <div class="pop-inner">
                            
                            
                            <span class="pop-cat">
                <?php echo wp_kses_post(get_the_category_list());?>
                            </span>
                        
                            
                            <span class="pop-date">
                <?php Reuse::dafe_posted_date(); ?>
                            </span>
                            
                            
                            <span class="pop-byline">
                <?php Reuse::dafe_posted_byline(); ?>
                            </span>
                            
                            </div>
                        </div>
        
                        
                </div>
            </div> <!--  end single post -->
    
    
                <?php         
            
                if ($col_count == $col_no) {
                    $col_count = '0';
                }    

        endwhile; ?>
        </div>
             
            <?php 
        

        endif; 
        wp_reset_postdata(); 
    }
    
    /**
     * Get post grid template
     *
     * @param string $post_order_by       post order by date or time
     * @param string $post_orders         post order ascending or descending 
     * @param string $no_of_column        number of column in grid
     * @param string $number_of_post      number of post in grid
     * @param string $category_selection  one or more categories can be selected
     * @param string $enable_excerpt      excerpt can be enabled or disabled
     * @param string $style               post style can be selected
     * @param string $hide_date           post date can be enabled or disabled
     * @param string $category_exclude    categories can be excluded
     * @param string $post_grid_align     alignment of post in column
     * @param string $post_text_align     alignment of post text in column
     * @param string $hide_date_thumbnail hide date on thumbnail
     *
     * @since Definitive Addons for Elementor 1.5.0
     *
     * @return string of html.
     */
    
    public static function dafe_get_post_grid_template($post_order_by,$post_orders,$no_of_column,$number_of_post,$category_selection,
        $enable_excerpt,$style,$hide_date,$category_exclude,$post_grid_align,$post_text_align,$hide_date_thumbnail
    ) {
    
        if ($hide_date == 'yes') {
            $hide_date = 'yes';
        } else {
            $hide_date = 'no';
        }
        if ($hide_date_thumbnail == 'yes') {
            $hide_date_thumbnail = 'yes';
        } else {
            $hide_date_thumbnail = 'no';
        }
    
        ?>
     
      
        <div class="da_grid_row ms-post-grid col_gap_30">
    
        <?php
        $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        
        $loop = new \WP_Query(
            array(
            'post_type' => 'post',
            'cat' =>$category_selection,
            'posts_per_page' =>$number_of_post,
            'orderby' =>$post_order_by,
            'order' =>$post_orders,
            'post_status' => 'publish',
            'category__not_in'=>$category_exclude,
                    
            )
        );
        
        $col_count = 0;
    
        if($loop->have_posts() ) : 
        
            if (($no_of_column == '2a' || $no_of_column == '3a')) {
                $col_count = -1;
            } else {
                $col_count = 0;
            }
        
            if ($no_of_column == '2a') {
                $col_no = 2;
            } elseif ($no_of_column == '3a') {
                $col_no = 3;
            } elseif ($no_of_column == '1a') {
                $col_no = 1;
            } else {
                $col_no = $no_of_column;
            }
            
            if ($style == 'style2') {
                $col_no = 1;
            }
            if ($no_of_column == '1a') {
                $style = 'style2';
            }
        
            while( $loop->have_posts() ): 
    
                $col_count++;  
                $fstyles = '';
                
                if ($col_count == 0) {
                    $fstyles = 'margins';
                } else {
                    $fstyles = '';
                }
    
                if (($col_count != 0) ) {
                
                    ?>
                
    <div class="<?php echo esc_attr($style);  ?> nl-blog-entry no_of_col_<?php echo esc_attr($col_no);  ?> col_no_<?php echo esc_attr($col_count);  ?> col_padd_margin" >
                
                <?php } ?>
                
        <div class="da_home_blog_border_style <?php echo esc_attr($fstyles);  ?> <?php echo esc_attr($style);  ?> clear">
                 <?php
                    if (is_home() && ! is_front_page() ) :
                        ?>
                <header>
                    <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                </header>
                        <?php
                    endif;

                    $loop->the_post(); 
                    $hcstyle = '';
                    if ($style == 'style2') {
                        if (has_post_thumbnail()) {
    
                            $hcstyle .= "thumb";

                        } else {
                            $hcstyle .= "w_thumb";    
                        }
                    }
                    ?>
    <div class="da-post-thumbnail <?php echo esc_attr($hcstyle); ?>">

                <?php	
                if (has_post_thumbnail()) {
                
                    ?>
                <div class="da-post-thumbnail-img">
                    <?php the_post_thumbnail('full'); ?>
                <div class="da-entry-date-abs <?php echo esc_attr($hide_date_thumbnail); ?>">
                    <?php the_time(get_option('date_format')); ?>
            </div>    
                </div>
            
                <?php } ?>

    </div>
    
    <div class="da-header-content <?php echo esc_attr($hcstyle); ?>">
    <header class="entry-header">

        <div class="title-meta <?php echo esc_attr($post_grid_align); ?>">
         <!-- To display categories on the top of the post title -->
                 <?php echo wp_kses_post(get_the_category_list());?>
        
        <!-- To display titles of blog post -->
                <?php
        
                if (is_single() ) {
                    the_title('<h1 class="da-entry-title">', '</h1>');
                } elseif (( is_home() || is_front_page()) && ($col_no == '2'|| $col_no == '3' || $style ='style2')) {
                    the_title('<h4 class="da-entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h4>');
        
                } else {
                    the_title('<h2 class="da-entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        
                }

                // To display meta of blog post -->    
                if ('post' === get_post_type() ) : ?>
            <div class="da-entry-meta <?php echo esc_attr($hide_date); ?> <?php echo esc_attr($post_grid_align); ?>">
            <!-- Meta function calling -->
                                <?php Reuse::dafe_posted_on(); ?>
            </div><!-- .entry-meta -->
                            <?php
                endif; ?>
        </div>
    </header><!-- .entry-header -->

<!-- Content Area  -->
    <div class="da-entry-content <?php echo esc_attr($style); ?> <?php echo esc_attr($post_grid_align); ?> <?php echo esc_attr($post_text_align); ?>">
        <!-- to show excerpt or full text of posts -->
                <?php 
                if (($enable_excerpt == 'yes')) {
                
                    the_excerpt();
                    
                } else {
                                    
                    the_content(
                        sprintf( 
                            __('Continue reading%s', 'definitive-addons-for-elementor'), 
                            '<span class="screen-reader-text">  '.get_the_title().'</span>' 
                        ) 
                    );

                }
                ?>                
      </div> <!-- end .entry-content -->
    </div> <!-- end .header-content -->

    <footer class="entry-footer">
                <?php if ('post' === get_post_type() ) {
                    $get_tags = get_the_tag_list('', esc_html__(', ', 'definitive-addons-for-elementor'));
                    if ($get_tags ) {
                        printf(/* translators: tag link*/ '<div class="tags-links">' . esc_html__('Tagged %1$s', 'definitive-addons-for-elementor') . '</div>', wp_kses_post($get_tags)); // WPCS: XSS OK.
                    }
                }
                ?>
        
    </footer><!-- .entry-footer -->
    
                </div> 
                <?php	if (($col_count != 0)) {
                    ?>
            </div> 
                <?php } ?>
            
                <?php
                if ($col_count == $col_no) {
                       $col_count = '0';
                }
            endwhile;
            
            ?>
        </div>     
            
            <?php 
     else :
         esc_html_e('Post is not exist', 'definitive-addons-for-elementor');
     endif;
     wp_reset_postdata();
      
    }
    

}



$da_post = new Da_Post();
