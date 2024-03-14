<?php
get_header();
echo "<div class='blogsqode_main_head'>";
/* Start the Loop */
$with_sidebar = esc_attr(get_option('blogsqode_singleblog_layout'));
if($with_sidebar == 'with_sidebar'){
	echo "<div class='blogsqode-single-post with-sidebar' >";
} else {
	echo "<div class='blogsqode-single-post'>";	
}
while ( have_posts() ) :
	the_post();
	$template_path = dirname(__FILE__);
	include $template_path.'/template-parts/content-single.php';

	if(esc_attr(get_option('blogsqode_single_pagination_allow'))==='Unable'){
		$prev_post = get_previous_post();
		$next_post = get_next_post();
		$next_post_date = ($next_post)?get_the_date('',$next_post->ID):'';
		$next_thumnail = ($next_post)?get_the_post_thumbnail_url($next_post->ID):'';
		$prev_thumbnail = ($prev_post)?get_the_post_thumbnail_url($prev_post->ID):'';
		$prev_post_date = ($prev_post)?get_the_date('',$prev_post->ID):'';

		$single_layout = esc_attr(get_option('blogsqode_singlepage_layout'));

		if($single_layout === '1'){
			$nexthtml = '<p class="single-layout-1-navigation meta-nav">' . esc_html__( 'Next post', 'blogsqode' )   . '</p><div class="next-prev-post"><div class="pagination-title-date"><h3 class="post-title">'.esc_html__("%title", "blogsqode").'</h3><p class="blogsqode-post-date">'.esc_html($next_post_date).'</p></div><div class="blog-single-pagination-wrap post-next" style="background-image: url('.esc_url($next_thumnail, 'blogsqode').');"></div> </div>';

			$prevhtml = '<p class="single-layout-1-navigation meta-nav">' . esc_html__( 'Previous post', 'blogsqode' )  .  '</p><div class="next-prev-post"><div class="blog-single-pagination-wrap" style="background-image: url('.esc_url($prev_thumbnail, 'blogsqode').');"></div><div class="pagination-title-date"><h3 class="post-title">'.esc_html__("%title", "blogsqode").'</h3><p class="blogsqode-post-date">'.esc_html($prev_post_date).'</p></div> </div>';
		}  
		the_post_navigation(
			array(
				'next_text' => $nexthtml,
				'prev_text' => $prevhtml,
			)
		);
	}

	// If comments are open or there is at least one comment, load up the comment template.

	if ( ( comments_open() || get_comments_number() ) && esc_attr(get_option('blogsqode_single_postcomment_allow'))==='Unable') {
		?> <h2 class="blogsqode-comment-title"><?php echo esc_html__('Post Comments:', 'blogsqode'); ?></h2><?php
		comments_template();
	}


endwhile; // End of the loop.
echo "</div>";
if(esc_attr(get_option('blogsqode_singleblog_layout'))==='with_sidebar'){ 
	echo "<div id='blogsqode_sidebar'>";
	get_sidebar();
	echo "</div>";
}

$singleDarkmode = strtolower(get_option("blogsqode_single_dark_mode"));
?>
<script type="text/javascript">
	"use strict";
	jQuery("body").addClass('dark-mode-<?php echo esc_js($singleDarkmode); ?>');
	jQuery('body').addClass('single-blogsqode-layout-<?php echo esc_js($single_layout); ?>');
	
</script>
<?php
echo "</div>";
get_footer();
