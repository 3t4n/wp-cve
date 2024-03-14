<?php
/**
 * Google Web Story Slider In Modal.
 *
 * This file display the google webstrory slider.
 *
 * @link       https://maxenius.com/
 * @since      1.0.3
 *
 * @package    Max_web_story
 * @subpackage Max_web_story/public/assets/partials
 */

$total_path   = plugin_dir_url( __FILE__ );
$img_bg_url   = dirname( $total_path ) . '/public/assets/img/1.png';
$sidebar_logo  = dirname( $total_path ) . '/assets/img/new-logo.png';
$position     = get_option( 'mws_select_position' );
$check_enable = get_option( 'mws_enable' );


global $post;
$post_slug = $post->post_name;
if ( is_front_page() ) {
	$post_slug = 'home';
}
$get_selected_pages = get_option( 'mws_select_page' );
if ( ! empty( $get_selected_pages ) && ! empty( $check_enable ) ) {
	if ( in_array( $post_slug, $get_selected_pages ) ) {
		wp_enqueue_style( 'mws_pubilc_card_css' );
		wp_enqueue_script( 'mws_publc_main_js' );
	}
}
if ( 'right' == $position ) {
	$icon_pos = 'right:30px;';
} else {
	$icon_pos = 'left:30px;';
}

$attachment_id = get_option( 'mws_default_webstory_icon' );
global $wpdb;
$table_name    = $wpdb->prefix . 'posts';
$img = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}posts WHERE ID = %d", $attachment_id ) );

if ( empty( $img ) ) {
	$img = $img_bg_url;
} else {
	$img = $img->guid;
}
if ( ! empty( $check_enable ) && ! empty( $get_selected_pages ) ) {
	?>
<div class="container">
	
	<!-- Button to Open the Modal -->
	<a data-toggle="modal" data-target="#myModal"  id="mwsBtn"  style="<?php echo esc_html( $icon_pos ); ?>"> 
	  <img src="<?php echo esc_html( $img ); ?>" style='width:200px;'>
	</a>
	

	<div id="mwsModal" class="mws_modal">                           
		<!-- Modal content -->
		<div class="mws_modal-content ">
			<span class="mwsClose">&times;</span>
			<div class="mws_modal_content_cont ">
				<div class="mws_modal_content_lft" >
					<a  href="https://app.hellowoofy.com/">
						<h4 class="mwx_power_by">Powered By</h4>
						<img src="<?php echo esc_html( $sidebar_logo ); ?>" >
					</a>
				</div>
				<div class="mws_modal_content_rght"  >
					  <div class="mws_main_cont" > 
							<?php
								$defaults     = array(
									'numberposts'      => 5,
									'category'         => 0,
									'orderby'          => 'date',
									'order'            => 'DESC',
									'include'          => array(),
									'exclude'          => array(),
									'meta_key'         => '',
									'meta_value'       => '',
									'post_type'        => 'webstories',
									'suppress_filters' => true,
								);
								$parsed_args  = wp_parse_args( $defaults );
								if ( empty( $parsed_args['post_status'] ) ) {
									$parsed_args['post_status'] = ( 'attachment' === $parsed_args['post_type'] ) ? 'inherit' : 'publish';
								}
								$get_posts = new WP_Query();
								$get_posts = $get_posts->query( $parsed_args );
								foreach ( $get_posts as $key => $get_post ) {
									$get_post_id    = $get_post->ID;
									$get_title      = $get_post->post_title;
									$description    = $get_post->post_content;
									$permalink      = $get_post->guid;
									$story_meta     = get_post_meta( $get_post_id, 'story_meta', true );
									$slider_logo    = $story_meta['publisher-logo-src'];
									$slider_potrait = $story_meta['poster-portrait-src'];
									?>
									<div class="mws_single_story_cont" >
										<a href="<?php echo esc_html( $permalink ); ?>" >
											<div class="mws_entry-point-card-container  " >
												<img src="<?php echo esc_html( $slider_potrait ); ?>" class="mws_entry-point-card-img" alt="A cat">
												<div class="mws_author-container">
												  <div class="mws_logo-container">
													<div class="mws_logo-ring"></div>
													<img class="mws_entry-point-card-logo" src="<?php echo esc_html( $slider_logo ); ?>" alt="Publisher logo">
												  </div>
												  <span class="mws_entry-point-card-subtitle"><?php echo esc_html( $get_title ); ?> </span>
												</div>
												<div class="mws_card-headline-container">
												  <span class="mws_entry-point-card-headline"><?php echo esc_html( $description ); ?></span>
												</div>
											</div>
										</a>
									</div>
									<?php
								}
								?>
								
					</div>
				</div>
			</div>
		</div>
  </div>
</div>
<?php } ?>
