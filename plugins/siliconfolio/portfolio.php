<?php // Template Name: Portfolio Template ?>
<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php  get_header();?>
<div>
<?php if ((get_post_meta($post->ID, 'st_sf_ps', 1)!='creative') && get_post_meta($post->ID, 'st_sf_ps', 1)!='modern') {?>
<div class="potfolio_container_holder">
<div class="st_sf_page_holder st_sf_without_sidebar">
	<?php $portfolio_layout = get_post_meta($post->ID, 'port_layout', 1);
	if($portfolio_layout == 'Random Thumbnails With Spaces'){$portfolio_layout_class='r_t_w_s';}
	elseif($portfolio_layout == 'Random Thumbnails Without Spaces'){$portfolio_layout_class='r_t_wo_s';}
	elseif($portfolio_layout == 'Square Thumbnails Without Spaces'){$portfolio_layout_class='s_t_wo_s';}
	elseif($portfolio_layout == 'Square Thumbnails With Spaces'){$portfolio_layout_class='s_t_w_s';}
	elseif($portfolio_layout == '4 Square Thumbnails Without Spaces'){$portfolio_layout_class='f_s_t_wo_s';}
	elseif($portfolio_layout == '4 Square Thumbnails With Spaces'){$portfolio_layout_class='f_s_t_w_s';}
	elseif($portfolio_layout == 'Half Thumbnails With Spaces'){$portfolio_layout_class='h_t_w_s';}
	elseif($portfolio_layout == 'Half Thumbnails Without Spaces'){$portfolio_layout_class='h_t_wo_s';}
	?>
	<div class="st_sf_<?php echo $portfolio_layout_class;?>">

    <?php if (get_post_meta($post->ID, 'port_page', 1) == 'Top') { if ( have_posts() ) : while ( have_posts() ) : the_post();  the_content();  endwhile; endif;}};?>
   
    

    
	<?php if (get_post_meta($post->ID, 'port_filters', 1) == 'Yes') {?>
    <div class="st_sf_port_filter_holder">
        <div class="st_sf_port_filter" id="filters"> 
            <ul class="st_sf_list_cats">
				<?php $categories = get_categories(array('type' => 'portfolio', 'taxonomy' => 'portfolio-category')); 
				echo "<li class='cat-item'><a href='#' data-filter='*' class='filter_button'>All Works</a></li>";
				foreach($categories as $category) {
				$group = $category->slug;
				echo "<li><a href='#' data-filter='.$group' class='filter_button'>".$category->cat_name."</a></li>";
				}?> 
            </ul>
    	</div>
    </div>
    <?php }; ?>
    	
	<?php if ($portfolio_layout == 'Random Thumbnails Without Spaces'
    || $portfolio_layout == 'Square Thumbnails Without Spaces'
    || $portfolio_layout == '4 Square Thumbnails Without Spaces'
    || $portfolio_layout == 'Half Thumbnails Without Spaces')
    {$portfolio_layout_extra_class ='st_sf_wall';}else{$portfolio_layout_extra_class ='';}?>
    
    <div class="row st_sf_port_container <?php echo $portfolio_layout_extra_class?>">
    <?php include_once("framework/loop.php");?>
    </div>
    <?php wp_reset_query(); ?>
	
	<?php if (get_post_meta($post->ID, 'port_load_more', 1) == 'Yes') {?>
    <div class="st_sf_load_more_holder">
        <?php
			if(get_post_meta($post->ID, 'st_sf_tag', 1) =="All"){
			$count_posts = wp_count_posts('portfolio');
			$published_posts = $count_posts->publish;
			}else{
				$taxonomy = "portfolio-tags"; // can be category, post_tag, or custom taxonomy name
				// Using Term Slug
				$term_name = get_post_meta($post->ID, 'st_sf_tag', 1);
				$term = get_term_by('name', $term_name, $taxonomy);
				
				// Fetch the count
				$published_posts = $term->count;
			};
		?>
        
        <div class="st_sf_lmc_holder">
            <span>
                <span class="st_sf_counts"><span id="st_sf_masorny_posts_per_page"><?php echo esc_attr(get_post_meta($post->ID, 'port-count', true)); ?></span> / <span id="st_sf_max_masorny_posts"><?php echo esc_attr($published_posts);?></span></span>
                <a id="load_more_port_masorny_posts" data-tag="<?php echo get_post_meta($post->ID, 'st_sf_tag', 1)?>" data-offset="<?php echo esc_attr(get_post_meta($post->ID, 'port-count', true)); ?>" data-layout-mode="<?php echo esc_attr(get_post_meta($post->ID, 'port_layout', 1)) ?>" data-load-posts_count="<?php echo (get_post_meta($post->ID, 'port-load_count', true)); ?>"  class="st_sf_load_more"><span><?php _e("Load More ", "orangeidea");?></span></a>
            </span>
        </div>
        <?php };?>
    </div>
    
    <?php if (get_post_meta($post->ID, 'port_page', 1) == 'Bottom') {?>
    <div style="margin-top:40px;">
    <?php
    if ( have_posts() ) : while ( have_posts() ) : the_post();
    the_content(); 
    endwhile; endif;?>
    </div>
    <?php }; ?>
    </div>
  </div>  
    <!--<a class="st_sf_full_btn" href="#">View our entire WordPress theme collection →</a>-->
   </div>

</div>
<?php get_footer();?>