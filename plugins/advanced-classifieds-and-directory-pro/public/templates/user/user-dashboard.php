<?php

/**
 * User Dashboard.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp acadp-user-dashboard">
	<div class="acadp-wrapper acadp-flex acadp-flex-col acadp-gap-6">
		<div class="acadp-header acadp-flex acadp-flex-wrap acadp-gap-6 acadp-items-start">
			<?php echo get_avatar( $userid ); ?>

			<div class="acadp-meta">
				<div class="acadp-text-lg acadp-font-bold">
					<?php echo esc_html( $user->display_name ); ?>
				</div>

				<?php echo esc_html( $user->description ); ?>

				<?php 
				$listing_settings = get_option( 'acadp_listing_settings' );
				$registration_settings = get_option( 'acadp_registration_settings' );
				$page_settings = get_option( 'acadp_page_settings' );
				
				$links = array();					
				
				if ( acadp_current_user_can('edit_acadp_listings') && $page_settings['listing_form'] > 0 ) {
					$links[] = sprintf(
						'<a href="%s">%s</a>',
						esc_url( get_permalink( $page_settings['listing_form'] ) ),
						esc_html__( 'Add New Listing', 'advanced-classifieds-and-directory-pro' )
					);
				}
				
				if ( acadp_current_user_can('edit_acadp_listings') && $page_settings['manage_listings'] > 0 ) {
					$links[] = sprintf(
						'<a href="%s">%s</a>',
						esc_url( get_permalink( $page_settings['manage_listings'] ) ),
						esc_html( get_the_title( $page_settings['manage_listings'] ) )
					);
				}
				
				if ( ! empty( $listing_settings['has_favourites'] ) && $page_settings['favourite_listings'] > 0 ) {
					$links[] = sprintf(
						'<a href="%s">%s</a>',
						esc_url( get_permalink( $page_settings['favourite_listings'] ) ),
						esc_html( get_the_title( $page_settings['favourite_listings'] ) )
					);
				}
				
				if ( acadp_current_user_can('edit_acadp_listings') && $page_settings['payment_history'] > 0 ) {
					$links[] = sprintf(
						'<a href="%s">%s</a>',
						esc_url( get_permalink( $page_settings['payment_history'] ) ),
						esc_html( get_the_title( $page_settings['payment_history'] ) )
					);
				}
				
				if ( ! empty( $registration_settings['engine'] ) && 'acadp' == $registration_settings['engine'] && $page_settings['user_account'] > 0 ) {
					$links[] = sprintf(
						'<a href="%s">%s</a>',
						esc_url( get_permalink( $page_settings['user_account'] ) ),
						esc_html__( 'User Account', 'advanced-classifieds-and-directory-pro' )
					);
				}
				
				echo '<div class="acadp-nav-links acadp-flex acadp-flex-wrap acadp-gap-2">';
				echo implode( '<span class="acadp-text-muted acadp-text-sm">/</span>', $links );
				echo '</div>'; 
				?>
			</div>
		</div>
		
		<?php if ( acadp_current_user_can('edit_acadp_listings') ) : ?>
			<div class="acadp-grid acadp-grid-cols-1 acadp-gap-6 md:acadp-grid-cols-2">
				<div class="acadp-flex acadp-flex-col acadp-items-center acadp-justify-center acadp-rounded acadp-shadow acadp-p-6">
					<div class="acadp-text-xl">
						<?php esc_html_e( 'Total Listings', 'advanced-classifieds-and-directory-pro' ); ?>
					</div>
					<div>
						<?php echo esc_html( acadp_get_user_total_listings() ); ?>
					</div>
				</div>

				<div class="acadp-flex acadp-flex-col acadp-items-center acadp-justify-center acadp-rounded acadp-shadow acadp-p-6">
					<div class="acadp-text-xl">
						<?php esc_html_e( 'Active Listings', 'advanced-classifieds-and-directory-pro' ); ?>
					</div>
					<div>
						<?php echo esc_html( acadp_get_user_total_active_listings() ); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
