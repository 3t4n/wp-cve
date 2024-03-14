<?php
/**
 * Template Style Twelve for Team
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;
?>

<div class="absp-team">
	<div class="absp-team-inner">
		<div class="image-area">
			<img class="member_image" src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['team_member_first_name'] ); ?>">
		</div>
		<div class="info-area">
			<div class="data-wrapper">
				<div class="data">
					<span class="designation"><?php echo esc_html( $settings['team_member_designation'] ); ?></span>
					<div class="firstname"><?php echo esc_html( $settings['team_member_first_name'] ); ?></div>
					<div class="lastname"><?php echo esc_html( $settings['team_member_last_name'] ); ?></div>
				</div>
				<?php if ( $settings['team_member_social_media'] ) : ?>
					<div class="social_icons">
						<ul>
							<?php foreach ( $settings['team_member_social_media'] as $social_media ) : ?>
								<li class="elementor-repeater-item-<?php echo esc_attr( $social_media['_id'] ); ?> ">
									<a href="<?php echo esc_url( $social_media['team_member_social_icon_url'] ); ?>">
										<i class="<?php echo esc_attr( $social_media['team_member_social_icon']['value'] ); ?>" aria-hidden="true"></i>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
