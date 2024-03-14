<?php 

/*--------------------------------------------------------------
 *				Add Sub-Menu Admin Style
 *-------------------------------------------------------------*/

function jeweltheme_posts_sort_styles()
{
	$screen = get_current_screen();
	
	if($screen->post_type == 'faq')
	{
		wp_enqueue_style( 'sort-stylesheet', plugin_dir_url( __FILE__ ) . 'css/sort-stylesheet.css', array(), false, false );		
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script( 'sort-script', plugin_dir_url( __FILE__ ) .  'js/sort-script.js' , array(), false, true );
	}	

}

add_action( 'admin_print_styles', 'jeweltheme_posts_sort_styles' );


/*--------------------------------------------------------------
 *					Add Submenu for all Post Types
 *-------------------------------------------------------------*/

//FAQ Submenu
function jeweltheme_faq_sort_posts(){
    add_submenu_page('edit.php?post_type=faq', 'Sort FAQ', 'Sort FAQ', 'edit_posts', basename(__FILE__), 'jeweltheme_faq_posts_sort_callback');
}

add_action('admin_menu' , 'jeweltheme_faq_sort_posts');


function jeweltheme_faq_posts_sort_callback(){

	$faq = new WP_Query('post_type=faq&posts_per_page=-1&orderby=menu_order&order=ASC');
?>
	<div class="wrap <?php if ( !jltmaf_accordion()->can_use_premium_code() ) { echo 'jltmaf-disabled'; }?>">
		<?php if ( !jltmaf_accordion()->can_use_premium_code() ) { ?>
			<span class="jltmaf-pro-badge eicon-pro-icon"></span>
		<?php } ?>

		<h3>Sort FAQ<img src="<?php echo home_url(); ?>/wp-admin/images/loading.gif" id="loading-animation" /></h3>
		<ul id="slide-list">
			<?php if($faq->have_posts()): ?>
				<?php while ( $faq->have_posts() ){ $faq->the_post(); ?>
					<li id="<?php the_id(); ?>"><?php the_title(); ?></li>			
				<?php } ?>
			<?php else: ?>
				<li>There is no FAQ was Created !!!</li>		
			<?php endif; ?>
		</ul>
	</div>
<?php
	if ( !jltmaf_accordion()->can_use_premium_code() ) {
		echo '<span class="jltmaf-pro-feature"> Upgrade to  <a href="' . jltmaf_accordion()->get_upgrade_url() . '">Pro Version</a> unlock this feature.</span>';
	}
}



/*--------------------------------------------------------------
 *				Ajax Call-back
 *-------------------------------------------------------------*/

function jeweltheme_portfolio_posts_sort_order()
{
	global $wpdb; // WordPress database class

	$order = explode(',', $_POST['order']);
	$counter = 0;
	
	foreach ($order as $slide_id) {
		$wpdb->update($wpdb->posts, array( 'menu_order' => $counter ), array( 'ID' => $slide_id) );
		$counter++;
	}
	die(1);
}

add_action('wp_ajax_team_sort', 'jeweltheme_portfolio_posts_sort_order');