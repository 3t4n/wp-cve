<?php
/**
 * Listing of All WebStories.
 *
 * Template Class
 *
 * @package  all-stories
 */

$count = 0;
global $wpdb;
if ( isset( $_POST['mwc_get_all_stories_field'] ) &&
		wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mwc_get_all_stories_field'] ) ), 'mwc_get_all_stories_action' ) ) {
	if ( isset( $_POST['mws_admin_search'] ) ) {
		$search_parameter  = sanitize_text_field( wp_unslash( $_POST['mws_admin_search'] ) );
		$get_posts = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = %s && post_status = %s && post_title like %s ORDER BY ID DESC", 'webstories', 'publish', '%' . $search_parameter . '%' ) );
		$count = count( $get_posts );
	}
} else {
		$get_posts  = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'webstories' && post_status = 'publish' ORDER BY ID DESC" );
		$count = count( $get_posts );
}
$get_total_path   = dirname( plugin_dir_url( __FILE__ ) );
$img_bg_url = $get_total_path . '/assets/img/new-logo.png';
$dot = $get_total_path . '/assets/img/dot.png';
global $wp_scripts;
$wp_scripts->queue = array();
global $wp_styles;
$wp_styles->queue = array();
wp_enqueue_style( 'mws_select2_css' );
wp_enqueue_style( 'mws_context_css' );
wp_enqueue_style( 'mws_admin_card_css' );
wp_enqueue_style( 'mws_admin_boostrap_min_css' );
wp_enqueue_script( 'mws_jquery' );
wp_enqueue_script( 'mws_select2_js' );
wp_enqueue_script( 'mws_admin_bundle_min_js' );
wp_enqueue_script( 'mws_context_js' );
wp_enqueue_script( 'mws_initiailze_js' );
wp_enqueue_script( 'mws_custom_admin_stories_js' );
wp_enqueue_script( 'mws_clipboard_js' );
?>
<div class="container mt-4 ">
  <div class="row">
	<div class="col-md-3 "> 
		  <div class="row">
			<div class="col-md-3 ">
				<a class="mws_admin_stories_listing_logo" target="_blank" href="https://app.hellowoofy.com/">
					<img src="<?php echo esc_attr( $img_bg_url ); ?>" style="width:180px; padding-top: 34px;">
				</a>
			</div>
		 </div>
	</div>
	<div  class="col-md-9  " style="padding-top: 30px;"> 
		<div class="row  ">
		  <div class="col-md-6  ">
			<h2 >All Web Stories</h2>
		  </div>
		  <div class="col-md-6 " >
			<div class="text-right;">
			  <form method="post">
				<?php wp_nonce_field( 'mwc_get_all_stories_action', 'mwc_get_all_stories_field' ); ?>
				<input type="search" placeholder="search" class="mws_admin_search" name="mws_admin_search" value="<?php echo !empty($search_parameter) ? $search_parameter : '' ?>">
				<input type="submit" name="mws_btn_admin_search"  class="mws_btn_admin_search" value="Search" >
			  </form>
			</div>
		  </div>
		  <div class="col-md-12">
			<hr> 
			<h6>Viewing all <span class="mws_story_count"><?php echo esc_html( $count ); ?></span> webstories</h6>
		  </div>
		</div>  


		
		<div class="row">
				  <?php
					foreach ( $get_posts as $key => $get_post ) {
						$get_post_id        = $get_post->ID;
						$get_title          = $get_post->post_title;
						$description    = $get_post->post_content;
						$permalink      = $get_post->guid;
						$story_meta     = get_post_meta( $get_post_id, 'story_meta', true );
						$slider_logo    = $story_meta['publisher-logo-src'];
						$slider_potrait = $story_meta['poster-portrait-src'];
						$class          = 'max-' . $get_post_id;
						?>
					<div class="col-md-4 col-sm-4 col-6  p-4  <?php echo esc_html( $class ); ?>">
						<?php
							$token = wp_nonce_field( 'mws_context_menu', 'mws_context_menu' );
						?>


						  <div class="mws_admin_entry-point-card-container  " >
							  <img src="<?php echo esc_attr( $slider_potrait ); ?>" class="mws_admin_entry-point-card-img" alt="A cat">
							  <div class="mws_admin_author-container">
								<div class="mws_admin_logo-container">
								  <div class="mws_admin_logo-ring"></div>
								  <img class="mws_admin_entry-point-card-logo" src="<?php echo esc_attr( $slider_logo ); ?>" alt="Publisher logo">
								</div>
								<span class="mws_admin_entry-point-card-subtitle"><?php echo esc_html( $get_title ); ?> </span>
							  </div>
							  <div class="mws_admin_card_headline_cont">
								<p class="mws_admin_entry-point-card-headline">
									<input type="hidden" name="mws_hidden_src"  class="mws_hidden_src" value="<?php echo  !empty($permalink) ? $permalink : '' ?>">
									<a  class="mws_admin_action"   
									data-id="<?php echo esc_html( $get_post_id ); ?>"
										>
										<img src="<?php echo esc_html( $dot ); ?>">
									</a>
								</p> 
							
								
							  </div>
							

						  </div>
					</div>
						<?php
					}
					?>
		</div>
	</div>
  </div>
</div>