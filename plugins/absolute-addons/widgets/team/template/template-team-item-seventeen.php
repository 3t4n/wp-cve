<?php
/**
 * Template Style Seventeen for Team
 *
 * @package AbsoluteAddons
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="absp-team-item">
	<div class="absp-team-wrapper">
		<div class="image-area">
			<img class="member_image" src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['team_member_name'] ) ?>">
		</div>
		<div class="data-area">
			<h4 style="<?php echo esc_attr( $settings['designation_alignment_style_five'] ); ?>:0" <?php $this->print_render_attribute_string( 'team_member_designation' ); ?>><?php echo esc_html($settings['team_member_designation']); ?></h4>
			<h2 <?php $this->print_render_attribute_string( 'team_member_name' ); ?>><?php echo esc_html( $settings['team_member_name'] ); ?></h2>
			<div class="social-icons">
				<ul>
					<?php foreach ( $settings['team_member_social_media'] as $social_media ) :?>
						<li class="elementor-repeater-item-<?php echo esc_attr( $social_media['_id'] ); ?>">
							<a href="<?php echo esc_url( $social_media['team_member_social_icon_url'] ) ?>">
								<i class="<?php echo esc_attr( $social_media['team_member_social_icon']['value'] );?>" aria-hidden="true"></i>
							</a>
						</li>
					<?php endforeach;?>
				</ul>
			</div>
		</div>
	</div>
</div>
