<?php
/**
 * Template Style Four for Team
 *
 * @package AbsoluteAddons
 * @var $settings
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
?>
<div class="absp-team-item">
	<div class="absp-team-thumb">
		<figure>
			<img src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['team_member_name'] ); ?>">
		</figure>
	</div>
	<div class="absp-team-info">
		<h3 class="absp-team-title"><?php absp_widget_title_kses( $settings['team_member_name'] ); ?></h3>
		<span class="absp-team-designation"><?php absp_render_content( $settings['team_member_designation'] ); ?></span>
		<div class="absp-team-content">
			<p><?php absp_render_content_no_pe( $settings['team_member_about'] )?></p>
		</div>
		<ul class="absp-team-social">
			<?php foreach ( $settings['team_member_social_media'] as $social_media ) : ?>
				<li class="elementor-repeater-item-<?php echo esc_attr( $social_media['_id'] ); ?>">
					<a href="<?php echo esc_url( $social_media['team_member_social_icon_url'] ); ?>">
						<i class="<?php echo esc_attr( $social_media['team_member_social_icon']['value'] ); ?>" aria-hidden="true"></i>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
