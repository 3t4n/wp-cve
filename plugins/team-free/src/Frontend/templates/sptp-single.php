<?php
/**
 * Single view template for member.
 *
 * @package team-free
 * @subpackage team-free\Frontend\templates
 * @since 2.1.0
 */

?>
<?php
if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
	?>
	<?php wp_head(); ?>
	<div class="wp-site-blocks">
		<header class="wp-block-template-part site-header">
			<?php block_header_area(); ?>
		</header>
	<?php
} else {
	get_header();
}
	// Start the loop.
while ( have_posts() ) :
	the_post();
	$member_info   = get_post_meta( get_the_ID(), '_sptp_add_member', true );
	$sptp_settings = get_option( '_sptp_settings' );

	$show_name            = true;
	$show_img             = true;
	$show_desc            = true;
	$show_position        = true;
	$show_social_profiles = true;
	if ( isset( $sptp_settings['detail_page_fields'] ) ) {
		$detail_fields        = $sptp_settings['detail_page_fields'];
		$show_name            = isset( $detail_fields['name_switch'] ) ? $detail_fields['name_switch'] : true;
		$show_img             = isset( $detail_fields['image_switch'] ) ? $detail_fields['image_switch'] : true;
		$show_desc            = isset( $detail_fields['bio_switch'] ) ? $detail_fields['bio_switch'] : true;
		$show_position        = isset( $detail_fields['job_position_switch'] ) ? $detail_fields['job_position_switch'] : true;
		$show_social_profiles = isset( $detail_fields['social_switch'] ) ? $detail_fields['social_switch'] : true;
	}
	?>
	<div id="post-sptp-<?php the_ID(); ?>" <?php post_class( 'sptp-single-post' ); ?>>
		<div class="sptp-list-style">
		<?php if ( $show_img && has_post_thumbnail() ) { ?>
			<div class="sptp-member-avatar-area">
				<?php
					the_post_thumbnail();
				?>
			</div><!-- .post-thumbnail -->
		<?php } ?>
			<div class="sptp-info">
				<?php if ( $show_name ) { ?>
				<div class="sptp-member-name">
					<?php
						$title_tag = apply_filters( 'sptp_member_name_tag_for_single_member_page', 'h2' );
					?>
					<<?php echo esc_html( $title_tag ); ?> class="sptp-member-name-title"><?php the_title(); ?> </<?php echo esc_html( $title_tag ); ?>>
				</div>
					<?php
				} if ( $show_position ) {
					$member_job_title = isset( $member_info['sptp_job_title'] ) ? $member_info['sptp_job_title'] : '';
					if ( $member_job_title ) {
						?>
					<div class="sptp-member-profession">
						<h4 class="sptp-jop-title"><?php echo esc_html( $member_info['sptp_job_title'] ); ?></h4>
					</div>
						<?php
					}
				} if ( $show_social_profiles ) {
					$member_socials = isset( $member_info['sptp_member_social'] ) ? $member_info['sptp_member_social'] : 0;
					if ( $member_socials ) {
						?>
					<div class="sptp-member-social rounded">
						<ul>
						<?php
						foreach ( $member_socials as $social ) :
							if ( $social['social_group'] ) :
								$social_link = $social['social_link'];
								if ( preg_match( '#^https?://#i', $social_link ) ) {
									$social_link = $social_link;
								} elseif ( preg_match( '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $social_link ) ) {
									$social_link = 'mailto:' . $social_link;
								} else {
									$social_link = 'http://' . $social_link;
								}
								?>
							<li>
								<a class="<?php echo 'sptp-' . esc_html( $social['social_group'] ); ?>" href="<?php echo esc_html( $social_link ); ?>" target="_blank">
								<i class="<?php echo 'fa fa-' . esc_html( $social['social_group'] ); ?>"></i>
								</a>
							</li>
								<?php
							endif;
							endforeach;
						?>
						</ul>
					</div>
						<?php
					}
				}
				?>
				</div>
			</div>
		<?php if ( $show_desc ) { ?>
			<div class="sptp-content">
				<?php the_content(); ?>
			</div>
		<?php } ?>
	</div>

		<?php
endwhile;

if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
	?>
		<footer class="wp-block-template-part site-footer">
			<?php block_footer_area(); ?>
		</footer>
	</div>
	<?php
	wp_footer();
} else {
	get_footer();
}
