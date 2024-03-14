<?php
/**
 * Template Style twenty Four for Team
 *
 * @package AbsoluteAddons

 */

defined( 'ABSPATH' ) || exit;

$unique_id = wp_unique_id();

?>
<div class="absp-team-item">
	<div class="absp-team-wrapper">
		<div class="image-area">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 406.46 407"><defs><style>.cls-1{fill:none;}.cls-2{clip-path:url(#clip-path);}</style><clipPath id="clip-path" transform="translate(39.4 26.75)"><path class="cls-1" d="M0,103.56V271.42a19.64,19.64,0,0,0,9.81,17l145.38,83.93a19.64,19.64,0,0,0,19.62,0l145.38-83.93a19.64,19.64,0,0,0,9.81-17V103.56a19.64,19.64,0,0,0-9.81-17L174.81,2.63a19.59,19.59,0,0,0-19.62,0L9.81,86.56A19.64,19.64,0,0,0,0,103.56Z"/></clipPath></defs><title>Asset 1abb</title><g id="Layer_2" data-name="Layer 2"><g id="Contents"><g class="cls-2"><image width="753" height="754" transform="scale(0.54)" xlink:href="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>"/></g></g></g></svg>
		</div>
		<div class="data-area">
			<h4 style="<?php echo esc_attr( $settings['designation_alignment_style_five'] ); ?>:0;" <?php $this->print_render_attribute_string( 'team_member_designation' ); ?>><?php echo esc_html($settings['team_member_designation']); ?></h4>
			<div class="separator_area">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 110 4"><g id="Layer_2" data-name="Layer 2"><g id="Contents"><path class="separator" d="M1.2,2.53c.86,0,.94.3.94.3a6.86,6.86,0,0,1-.68.23c-.26.06.43.24,1.2.18s1.54.23,3.08.23a13.49,13.49,0,0,0,3.6-.35,63.12,63.12,0,0,1,7.46-.29c.6-.06.52.23.52.23H15.26c-1.46,0-2,.06-2,.24s4.37.05,9.09.11,7.71.18,8.74.3a16.1,16.1,0,0,0,3,.23c.43-.12,33.43-.29,33.43-.29s1.12-.12,3.69-.18,4.8,0,5.4,0,0,0,.69.24.6,0,1.71.06.6,0,3.69,0,5.66-.06,6.17-.12a.65.65,0,0,1,.69.35s6.51-.06,6.34-.12,0,0,2.66-.17a30.08,30.08,0,0,1,4-.06,15,15,0,0,1,1.54.12c.34,0,1.11.29,1.11,0s1.38-.23,1.89-.23A5.26,5.26,0,0,0,109.05,3l.09-.07h.76c.35-.06-.34-.35-.34-.35a2.74,2.74,0,0,0-1.2-.23c-.77,0-1.46-.12-.77-.36s-.09-.47-.09-.47-2.57,0-4.8-.06-3,.18-3.08.06,0,0,.51-.11A39.23,39.23,0,0,0,96,1.24S94,1.3,93.44,1.3,93.19,1,92.67,1,86,.77,84.87.77,83.07.65,78.18.65,74.67.42,73.47.24,56.41.24,55.21.18s1-.29-2.06-.12S38.92.42,38.06.42s-1.63,0-2.91-.06-.52,0-.69.23-1.88.24-7.37.24S18.35,1,17,.89A6.28,6.28,0,0,0,14.83,1l-.34.06a36.41,36.41,0,0,1-3.77.24C9.34,1.3,7,1.36,7,1.36l-.17.23s.86,0-2.06.24a16.71,16.71,0,0,1-4,0L0,2.36A3,3,0,0,0,1.2,2.53Z"/></g></g></svg>
			</div>

			<h2 <?php $this->print_render_attribute_string( 'team_member_name' ); ?>><?php echo esc_html( $settings['team_member_name'] ); ?></h2>
			<div class="social-icons">
				<ul>
					<?php foreach ( $settings['team_member_social_media'] as $social_media ) :?>
						<li class="elementor-repeater-item-<?php echo esc_attr( $social_media['_id'] ) ;?> "  >
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
