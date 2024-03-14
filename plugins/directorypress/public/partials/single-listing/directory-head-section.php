<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/
global $DIRECTORYPRESS_ADIMN_SETTINGS, $wpdb;
$cover_id = get_post_meta($listing->post->ID, '_attached_image_cover', true);
//if($listing->cover_image){
	$cover_src = wp_get_attachment_image_src($cover_id, 'full');
	$cover_url = $cover_src[0];
//}
$clogo = get_post_meta($listing->post->ID, '_attached_image_clogo', true);
if($clogo){
	$clogo_src = wp_get_attachment_image_src($clogo, 'full');
	$clogo_url = $clogo_src[0];
	
}else{
	$clogo_url = DIRECTORYPRESS_RESOURCES_URL .'images/no-thumbnail.jpg';
}
$head_section_bg = (isset($cover_url) && !empty($cover_url))? ('background-image:url('. esc_url($cover_url) .');'):'background-color:#8893b9;';
$field_ids = $wpdb->get_results('SELECT id, type, slug, options FROM '.$wpdb->prefix.'directorypress_fields');
?>
<div class="directorypress-listing-directory-head-section" style="<?php echo $head_section_bg; // phpcs: ok ?> min-height:400px;"></div>
<div class="directorypress-directory-head-section-content-wrapper">
	<div class="directorypress-directory-head-section-content-wrapper-inner clearfix">
		<div class="container clearix">
			<div class="directorypress-single-listing-company-logo"><img src="<?php echo esc_url($clogo_url); ?>" alt="" /></div>
			<div class="directorypress-directory-head-section-content clearfix">
				<div class="directorypress-directory-head-section-content-top clearfix">
					<div class="single-listing-directory-ratting_contact clearfix">
						<div class="single-listing-contact">
							<?php 
								foreach( $field_ids as $field_id ) {
									if($field_id->type == 'text' && $field_id->slug == 'phone'){
											echo '<div class="single-filed-phone">';
												$singlefield_id = $field_id->id;	
												$listing->display_content_field($singlefield_id);
											echo '</div>';
									}
								}
							?>
						</div>
					</div>
					<div class="single-listing-directory-btns clearfix">
						<?php if(isset($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_ratings_addon']) && $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_ratings_addon']): ?>
							<div class="single-listing-rating">
								<span class="rating-numbers"><?php echo get_average_listing_rating($listing->post->ID); ?></span>
								<span class="rating-stars"><?php display_average_listing_rating($listing->post->ID); ?></span>
							</div>
						<?php endif; ?>
						<ul class="clearfix">
							<li><?php do_action('single-listing-share', $listing, true, 2); ?></li>
							<li><?php do_action('single-listing-review-button', $listing, true, 2); ?></li>
							<li><?php do_action('single-listing-booking-button', $listing, true, 2); ?></li>
						</ul>
					</div>
				</div>
				<div class="row directorypress-directory-head-section-content-bottom clearfix">
					<div class="col-md-8 col-sm-8 col-xs-12">
						<?php do_action('directorypress-breadcrumb', $listing, $hash); ?>
						<?php do_action('single-listing-title', $listing); ?>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<?php do_action('directorypress-business-hours', $listing); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="head-section-bottom-area clearfix">
			<div class="container clearix">
		
					<div class="listing-metas-single clearfix">		
						<?php do_action('single-listing-date-published', $listing); ?>		
						<?php do_action('single-listing-views', $listing); ?>
						<?php do_action('single-listing-id', $listing); ?>
					</div>
						<div class="single-listing-btns clearfix">
							<ul>
								<?php if ( is_user_logged_in() ): ?>
									<li><?php do_action('directorypress-edit-listing-button', $listing->post->ID, true, 2); ?></li>
								<?php endif; ?>
								
								<?php do_action('directorypress_listing_buttons_list_pre', $listing->post->ID, true, 2); ?>
								<li><?php do_action('single-listing-report', $listing, true, 2); ?></li>
								<li><?php do_action('single-listing-pdf', $listing, true, 2); ?></li>
								<li><?php do_action('single-listing-print', $listing, true, 2); ?></li>
								<li><?php do_action('single-listing-bookmark', $listing, true, 2); ?></li>
								<?php do_action('directorypress_listing_buttons_list_post', $listing->post->ID, true, 2); ?>
							</ul>
						</div>
			</div>
		</div>
	</div>
</div>


 