<?php
/*
 *  Responsive Portfolio Image Gallery 1.2
 *  By @realwebcare - https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
//manage the columns of the "portfolio" post type
function rcpig_manage_columns_for_portfolio($columns) {
	//remove columns
	unset($columns['date']);
	//add new columns
	$columns['portfolio_featured_image'] = __('Portfolio Featured Image','rcpig');
	$columns['date'] = __('Date','rcpig');
	return $columns;
}
add_action('manage_rcpig_posts_columns','rcpig_manage_columns_for_portfolio');
//Populate custom columns for "portfolio" post type
function rcpig_populate_portfolio_columns($column,$post_id) {
	//featured image column
	if($column == 'portfolio_featured_image') {
		//if this portfolio has a featured image
		if(has_post_thumbnail($post_id)) {
			$portfolio_featured_image = get_the_post_thumbnail($post_id,array(100,100));
			echo $portfolio_featured_image;
		} else {
			echo __('This portfolio has no featured image','rcpig');
		}
	}
}
add_action('manage_rcpig_posts_custom_column','rcpig_populate_portfolio_columns',10,2);
if ( ! function_exists('rcpig_include_categories') ) {
	// Include selected categories form portfolio.
	function rcpig_include_categories() {
		$terms = $category_link = array();
		$rcpig_post_type = 'rcpig';
		$taxonomy_objects = get_object_taxonomies( $rcpig_post_type, 'objects' );
		if( isset($taxonomy_objects) && !empty($taxonomy_objects) ){
		 	$rcpig_taxonomy = 'rcpig-category';
		    $terms = get_terms($rcpig_taxonomy);
		    foreach ( $terms as $term ) {
		        $category_link[$term->term_id] = $term->name . ' (' . $term->count . ')';
		    }                                         
	    }
	    return $category_link;
	}
}
/* Sidebar */
add_action( 'rcpig_settings_content', 'rcpig_sidebar' );
if( !function_exists( 'rcpig_sidebar' ) ){
	function rcpig_sidebar() { ?>
		<div id="rcpig-sidebar" class="postbox-container">
			<div id="rcpigusage-shortcode" class="rcpigusage-sidebar">
				<h3><?php _e('Plugin Shortcode', 'rcpig'); ?></h3>
				<input type="text" class="rcpig-shortcode" value="[rcpig-gallery]" />
			</div>
			<div id="rcpigusage-premium" class="rcpigusage-sidebar">
            	<h3><?php _e('Code Usage Instruction', 'rcpig'); ?></h3>
                <div class="rcpig">
                    <p>Put the below shortcode in your blog posts/pages, where you want to show the Portfolio Gallery:<br><br>
                    	<code>&#60;&#63;php echo do_shortcode&#40;&#39;&#91;rcpig-gallery&#93;&#39;&#41;&#59; &#63;&#62;</code>
                    </p>
                    <p class="rcpig-review"><?php _e('Like it? Please leave us a rating. We highly appreciate your support!', 'rcpig'); ?><a target="_blank" href="https://wordpress.org/support/plugin/responsive-portfolio-image-gallery/reviews/?filter=5/#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a></p>
                </div>
			</div>
			<div id="rcpigusage-features" class="rcpigusage-sidebar">
				<h3><?php _e('Premium Features', 'rcpig'); ?></h3>
				<div class="ccwrpt"><?php _e('Premium version has been developed to present Portfolio Gallery more proficiently. Some of the most notable features are:', 'rcpig'); ?></div>
				<ul class="rcpigusage-list">
					<li><?php _e('Unlimited portfolios by different categories.', 'rcpig'); ?></li>
					<li><?php _e('Import/Export (Backup) portfolio settings.', 'rcpig'); ?></li>
					<li><?php _e('Make a copy of a portfolio instantly.', 'rcpig'); ?></li>
					<li><?php _e('Video support for YouTube and Vimeo.', 'rcpig'); ?></li>
					<li><?php _e('Unique title &amp; description for each carousel images.', 'rcpig'); ?></li>
					<li><?php _e('Adjust expanding preview opening speed.', 'rcpig'); ?></li>
					<li><?php _e('Support for multiple buttons.', 'rcpig'); ?></li>
					<li><?php _e('Google font support.', 'rcpig'); ?></li>
					<li><?php _e('and lots more...', 'rcpig'); ?></li>
				</ul>
				<a class="button button-primary" href="http://code.realwebcare.com/item/responsive-portfolio-image-gallery-pro/" target="_blank"><?php _e('View Premium', 'rcpig'); ?></a>
			</div>
			<div id="rcpigusage-info" class="rcpigusage-sidebar">
				<h3><?php _e('Plugin Info', 'rcpig'); ?></h3>
				<ul class="rcpigusage-list">
					<li><?php _e('Version: 1.2', 'rcpig'); ?></li>
					<li><?php _e('Scripts: PHP + CSS + JS', 'rcpig'); ?></li>
					<li><?php _e('Requires: Wordpress 5.4+', 'rcpig'); ?></li>
					<li><?php _e('First release: 9 August, 2016', 'rcpig'); ?></li>
					<li><?php _e('Last Update: 30 April, 2022', 'rcpig'); ?></li>
					<li><?php _e('By', 'rcpig'); ?>: <a href="https://www.realwebcare.com/" target="_blank"><?php _e('Realwebcare', 'rcpig'); ?></a></li>
					<li><?php _e('Need Help', 'rcpig'); ?>? <a href="https://wordpress.org/support/plugin/responsive-portfolio-image-gallery/" target="_blank">Support</a></li>
                    <li><?php _e('Like it? Please leave us a', 'rcpig'); ?> <a target="_blank" href="https://wordpress.org/support/plugin/responsive-portfolio-image-gallery/reviews/?filter=5/#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a> <?php _e('rating. We highly appreciate your support!', 'rcpig'); ?></li>
					<li><?php _e('Published under', 'rcpig'); ?>: <a href="http://www.gnu.org/licenses/gpl.txt"><?php _e('GNU General Public License', 'rcpig'); ?></a></li>
				</ul>
			</div>
		</div><?php
	}
}
?>