<?php
/*
Plugin Name: List Category Posts with Pagination
Plugin URI: http://w3grip.com/wordpress/list-category-posts-with-pagination-plugin/
Description: List Category Posts with Pagination allows you to list posts from a category into a post or page with pagination using the [mycatlist] shortcode. This shortcode accepts a category id so attribute would be "cat=Category_ID", the order would be default according to post date, and the number of posts will dispaly according to pagination option. Usage: [mycatlist cat=Category_ID].
Version: 1.0
Author: Mukesh patel.
Author URI: http://w3grip.com
*/

function admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/pagination.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}
add_action('wp_head','admin_register_head');

function mukesh_pagination($pages = '', $range = 3)
{   /*  pagination for post pages*/
     $showitems = ($range * 2)+1;  
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
		 echo $pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }  
 
     if(1 != $pages)
     {
         echo "<div class=\"w3grip_pagination\"><span>Page ".$paged." of ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; First</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a>";
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>Last &raquo;</a>";
         echo "</div>\n";
     }
}


function cat_post_limit($limit) {
	global $paged, $myOffset;
	//echo $paged;
	if (empty($paged)) {
			$paged = 1;
	}
	$postperpage = intval(get_option('posts_per_page'));
	$pgstrt = ((intval($paged) -1) * $postperpage) . ', ';
	$limit = 'LIMIT '.$pgstrt.$postperpage;
	return $limit;
} 


 
 


function mycatlist_func($atts, $content = null){
$atts=shortcode_atts(array('cat' => '0'), $atts);
			$catid=$atts['cat'];
		

			add_filter('post_limits', 'cat_post_limit');
			global $myOffset;
			
			$myOffset = 1;
			$temp = $wp_query;
			$wp_query= null;
			$wp_query = new WP_Query();
		
			$wp_query->query('cat='.$catid.'&offset='.$myOffset.'&posts_per_page='.intval(get_option('posts_per_page')).'&paged='.$paged);
			$pages= $wp_query->max_num_pages;
			
			ob_start(); ?>
			<h2> <?php echo  get_cat_name( $catid ); ?> </h2>
			<ul>
			<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
			 <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php  the_title() ?>"><?php the_title(); ?></a></li>
			<?php endwhile; ?>
			
			</ul>
			<div class="navigation"><?=mukesh_pagination($pages)?></div>
			<?php 
			
			
			$myoutput = ob_get_contents();
			ob_end_clean();
			
			 $wp_query = null; $wp_query = $temp;
			 remove_filter('post_limits', 'cat_post_limit');
			  
			return $myoutput;
 } 
	

 	
 add_shortcode('mycatlist', 'mycatlist_func'); 