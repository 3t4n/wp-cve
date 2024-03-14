<?php
/**
 * Template Style Thirteen for Team
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
	<div class="absp-team-inner">
		<div class="image-area">
			<div class="image-wrapper">
				<img class="member_image" src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>" alt="<?php echo esc_html( $settings['team_member_name'] ) ?>">
			</div>
			<?php if ( $settings['team_member_social_media'] ) : ?>
				<div class="social_icons">
					<ul>
						<?php foreach ( $settings['team_member_social_media'] as $social_media ) : ?>
							<li class="elementor-repeater-item-<?php echo esc_attr( $social_media['_id'] ); ?> ">
								<a href="<?php echo esc_url( $social_media['team_member_social_icon_url'] ) ?>">
									<i class="<?php echo esc_attr( $social_media['team_member_social_icon']['value'] ); ?>" aria-hidden="true"></i>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<div class="overlay-bg"></div>
		</div>
		<div class="data">
			<div <?php $this->print_render_attribute_string( 'team_member_name' ); ?> class="title"><?php echo esc_html( $settings['team_member_name'] ); ?></div>
			<h3 <?php $this->print_render_attribute_string( 'team_member_designation' ); ?> class="designation"><?php echo esc_html( $settings['team_member_designation'] ); ?></h3>
		</div>
	</div>
</div>
<!-- team-item -->
