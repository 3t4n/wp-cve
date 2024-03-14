<?php
/**
 * Template Style Fifteen for Team
 *
 * @package AbsoluteAddons
 */

defined('ABSPATH') || exit;

?>
<div class="absp-team-item">
	<div class="holder">
		<div class="pic">
				<div class="placeholder">
					<img class="member_image" src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>" alt="<?php echo esc_html($settings['team_member_name']) ?>">
				</div>
			<?php if ( $settings['team_member_social_media'] ) : ?>
				<div class="social_icons">
					<ul>
						<?php foreach ( $settings['team_member_social_media'] as $social_media ) : ?>
							<li class="elementor-repeater-item-<?php echo esc_attr( $social_media['_id'] ); ?> ">
								<a href="<?php echo esc_url($social_media['team_member_social_icon_url']) ?>">
									<i class="social_circle <?php echo esc_attr($social_media['team_member_social_icon']['value']); ?>" aria-hidden="true"></i>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
		<div class="data">
			<div class="title">
				<h2 <?php $this->print_render_attribute_string('team_member_name'); ?>><?php absp_render_title( $settings['team_member_name'] ); ?></h2>
			</div>
			<div class="designation">
				<h4 <?php $this->print_render_attribute_string('team_member_designation'); ?> ><?php echo esc_html( $settings['team_member_designation'] ); ?></h4>
			</div>

		</div>
	</div>
</div>
