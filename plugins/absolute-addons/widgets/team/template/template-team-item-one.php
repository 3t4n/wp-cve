<?php
/**
 * Template Style One for Team
 *
 * @package AbsoluteAddons
 * @var $settings
 */
defined( 'ABSPATH' ) || exit;
?>

<div class="absp-team-item">
	<div class="holder">
		<div class="pic">
			<div class="placeholder-img">
				<img class="member_image" src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>" alt="<?php echo esc_html( $settings['team_member_name'] ) ?>">
			</div>
		</div>
		<div class="data">
			<h2 <?php $this->print_render_attribute_string( 'team_member_name' ); ?> class="title"><?php echo esc_html( $settings['team_member_name'] ); ?></h2>
			<h3 <?php $this->print_render_attribute_string( 'team_member_designation' ); ?> class="designation"><?php echo esc_html( $settings['team_member_designation'] ); ?></h3>
			<?php if ( 'true' === $settings['separator_enable'] ) : ?>
				<hr class="separator">
			<?php endif; ?>
			<div <?php $this->print_render_attribute_string( 'team_member_about' ); ?> class="about">
				<?php echo wp_kses_post( $settings['team_member_about'] ); ?>
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
		</div>
	</div>
</div>
<!-- team-item -->
