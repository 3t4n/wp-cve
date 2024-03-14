<?php
	/**
	 * Template Style Twenty two for Team
	 *
	 * @package AbsoluteAddons
	 */
	defined( 'ABSPATH' ) || exit;
?>
<div class="absp-team-item">
	<div class="absp-team-inner <?php echo esc_html( $settings['image_position_change_style_21']);?>">
		<div class="image-area" >
			<img src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>" alt=" <?php echo esc_html( $settings['team_member_name'] ); ?>  ">
		</div>
		<div class="content-area-wrapper">
			<div class="content-area">
				<h2 <?php $this->print_render_attribute_string( 'team_member_name' ); ?> ><?php  absp_render_title($settings['team_member_name']); ?></h2>
				<h4 <?php $this->print_render_attribute_string( 'team_member_designation' ); ?> ><?php echo esc_html($settings['team_member_designation']); ?></h4>

			</div>
			<div class="triangle">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 48">
					<g id="Layer_2" data-name="Layer 2">
						<g id="Hover">
							<polygon class="style-22-indicator-border" points="0 24 28 48 28 0 0 24"/>
							<polygon class="style-22-indicator" points="8 24 28 41.5 28 6.5 8 24"/>
						</g>
					</g>
				</svg>
			</div>
			<div class="social-area">
				<div class="social-icons">
					<ul>
						<?php foreach ( $settings['team_member_social_media'] as $social_media ) :?>
							<li class="elementor-repeater-item-<?php echo esc_attr( $social_media['_id'] ); ?>">
								<a href="<?php echo esc_url( $social_media['team_member_social_icon_url'] ); ?>">
									<i class=" <?php echo esc_attr( $social_media['team_member_social_icon']['value'] );?>" aria-hidden="true"></i>
								</a>
							</li>
						<?php endforeach;?>
					</ul>
				</div>
			</div>
		</div>

	</div>
</div>
