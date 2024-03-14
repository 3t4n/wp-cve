<?php
/**
 * Template Style Seven for Team
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
			<img src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>" alt="<?php echo esc_html( $settings['team_member_name'] ); ?>">
		</figure>
	</div>

	<div class="absp-team-info">
		<h3 class="absp-team-title"><?php echo esc_html( $settings['team_member_name'] ); ?></h3>
		<span class="absp-team-designation"><?php echo esc_html( $settings['team_member_designation'] ); ?></span>
		<ul class="absp-team-social">
			<?php foreach ( $settings['team_member_social_media'] as $social_media ) : ?>
				<li class="elementor-repeater-item-<?php echo esc_attr( $social_media['_id'] ); ?>">
					<a href="<?php echo esc_url( $social_media['team_member_social_icon_url'] ); ?>">
						<i class="<?php echo esc_attr( $social_media['team_member_social_icon']['value'] ); ?>"></i>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
