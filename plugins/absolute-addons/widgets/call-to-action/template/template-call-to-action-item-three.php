<?php
/**
 * Template Style Three for Call To Action
 *
 * @package AbsoluteAddons
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="c2a-box">
	<?php if ( ! empty( $settings['c2a_box_image'] ) ) {?>
		<div class="c2a-box-inner">
			<div class="c2a-box-img">
			<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 401.58 317.58" style="enable-background:new 0 0 401.58 317.58;" xml:space="preserve">
				<style type="text/css">.c2a_svg_class{clip-path:url(#c2a_svg_id2);}</style>
				<g>
					<g>
						<g>
							<g>
								<defs>
									<path id="c2a_svg_id" d="M221.9,39.29c-33.49,21.38-50.01,10.29-86.95,5.6C56.7,34.95,15.1,84.3,2.92,139.29 S15.71,263.5,95.21,262.26c43.63-0.68,77.38-10,95.65,23.6s99.27,53.8,142.91-9.33s9.13-110.99,53.79-156.8 S368.48-68.15,221.9,39.29z"/>
								</defs>
								<clipPath id="c2a_svg_id2">
									<use xlink:href="#c2a_svg_id"  style="overflow:visible;"/>
								</clipPath>
								<g id="p5msi1_14_" class="c2a_svg_class">
									<image style="overflow:visible;" width="2125" height="1700" id="p5msi1_15_" xlink:href="<?php echo esc_url( $settings['c2a_box_image']['url'] ); ?>" transform="matrix(0.1933 0 0 0.194 -5.3267 -13.3118)"></image>
								</g>
							</g>
						</g>
					</g>
				</g>
			</svg>
			</div>
		</div>
	<?php } ?>
	<div class="c2a-box-inner">
		<div class="c2a-box-content">
			<?php if ( ! empty( $settings['c2a_box_sub_title'] ) ) { ?>
			<span <?php $this->print_render_attribute_string( 'c2a_box_sub_title' ); ?>><?php absp_render_title( $settings['c2a_box_sub_title'] ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $settings['c2a_box_title'] ) ) {?>
			<h2 <?php $this->print_render_attribute_string( 'c2a_box_title' ); ?> ><?php absp_render_title( $settings['c2a_box_title'] ); ?></h2>
			<?php } ?>
			<?php $this->render_c2a_button( $settings ); ?>
		</div>
	</div>
</div>
