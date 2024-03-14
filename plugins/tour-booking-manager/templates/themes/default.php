<?php
	// Template Name: Default Theme
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	$ttbm_post_id = $ttbm_post_id ?? get_the_id();
	$tour_id=$tour_id??TTBM_Function::post_id_multi_language($ttbm_post_id);
?>
	<div class="ttbm_default_theme">
		<div class='mpStyle ttbm_wraper'>
			<div class="ttbm_container">
				<div class="ttbm_details_page">
					<?php do_action( 'ttbm_details_title' ); ?>
					<?php do_action( 'ttbm_details_title_after', $ttbm_post_id ); ?>
					<?php include( TTBM_Function::template_path( 'layout/location.php' ) ); ?>
					<div class="ttbm_content_area">
						<div class="ttbm_content__left">
							<?php do_action( 'ttbm_slider' ); ?>
							<?php do_action( 'ttbm_short_details' ); ?>
							<?php do_action( 'ttbm_registration_before', $ttbm_post_id ); ?>
							<?php include( TTBM_Function::template_path( 'ticket/registration.php' ) ); ?>
							<?php include( TTBM_Function::template_path( 'ticket/particular_item_area.php' ) ); ?>
							<?php do_action( 'ttbm_description' ); ?>
							<?php do_action( 'ttbm_hiphop_place' ); ?>
							<?php do_action( 'ttbm_day_wise_details' ); ?>
							<?php do_action( 'ttbm_faq' ); ?>
						</div>
						<div class="ttbm_content__right shadow_one">
							<?php do_action( 'ttbm_include_feature' ); ?>
							<?php do_action( 'ttbm_exclude_service' ); ?>
							<?php do_action( 'ttbm_activity' ); ?>
							<?php //do_action( 'ttbm_hotel_list' ); ?>
							<?php do_action( 'ttbm_location_map', $ttbm_post_id ); ?>
							<?php do_action( 'ttbm_why_choose_us' ); ?>
							<?php do_action( 'ttbm_get_a_question' ); ?>
							<?php do_action( 'ttbm_tour_guide' ); ?>
							<?php do_action( 'ttbm_dynamic_sidebar', $ttbm_post_id ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php do_action( 'ttbm_single_tour_after' ); ?>
	</div>
<?php do_action( 'ttbm_related_tour' ); ?>