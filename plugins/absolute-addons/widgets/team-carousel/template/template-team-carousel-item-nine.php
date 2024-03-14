<?php
/**
 * Template Style Nine for Team Carousel
 *
 * @package AbsoluteAddons
 * @var $member
 * @var $image
 * @var $job_title
 * @var $index
 */
?>
<!-- single-team-carousel-item -->
<div class="swiper-slide">
	<div class="absp-team-carousel-item">
		<div class="absp-team-carousel-image">
			<?php if ( $image ) : ?>
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 151.42 151.62">
					<defs>
						<style>.cls-1 {fill: #F0F0E6;}.cls-2 {clip-path: url(#clip-path);}</style>
						<clipPath id="clip-path" transform="translate(10.8 10.35)">
							<path class="cls-1" d="M125.06,95.48V43.6a17.68,17.68,0,0,0-8.83-15.3L71.38,2.37a17.71,17.71,0,0,0-17.7,0L8.83,28.3A17.68,17.68,0,0,0,0,43.6V95.48a17.69,17.69,0,0,0,8.83,15.31l44.85,25.93a17.71,17.71,0,0,0,17.7,0l44.85-25.93A17.69,17.69,0,0,0,125.06,95.48Z"></path>
						</clipPath>
					</defs>
					<g id="Layer_2" data-name="Layer 2">
						<g id="Layer_1-2" data-name="Layer 1">
							<g class="cls-2">
								<image width="753" height="754" transform="scale(0.2)" xlink:href="<?php echo esc_url( $image ); ?>"></image>
							</g>
						</g>
					</g>
				</svg>
			<?php endif; ?>
		</div>
		<?php if ( $member['job_title'] ) : ?>
			<div <?php $this->print_render_attribute_string( $job_title ); ?>><?php absp_render_title( $member['job_title'] ); ?></div>
		<?php endif; ?>
		<div class="absp-team-carousel-border">
			<svg xmlns="http://www.w3.org/2000/svg" width="110" height="4" viewBox="0 0 110 4">
				<g id="Group_33" data-name="Group 33" transform="translate(-7704.915 -9059.597)">
					<path id="Path_5" data-name="Path 5" d="M7706.115,9062.128c.857,0,.943.294.943.294a5.95,5.95,0,0,1-.686.235c-.257.059.429.234,1.2.176s1.543.235,3.086.235a13.49,13.49,0,0,0,3.6-.353,65.241,65.241,0,0,1,7.459-.294c.6-.059.514.235.514.235h-2.057c-1.458,0-1.972.059-1.972.234s4.372.06,9.087.117,7.716.177,8.744.294a15.664,15.664,0,0,0,3,.235c.429-.117,33.434-.294,33.434-.294s1.115-.117,3.686-.176,4.8,0,5.4,0,0,0,.686.234.6,0,1.715.06.6,0,3.686,0,5.658-.06,6.172-.118a.643.643,0,0,1,.686.353s6.516-.059,6.344-.118,0,0,2.658-.176a30.784,30.784,0,0,1,4.029-.059,15.1,15.1,0,0,1,1.543.118c.343.059,1.115.294,1.115.059s1.371-.235,1.886-.235a5.329,5.329,0,0,0,1.886-.588c.069-.047.1-.067.094-.075l.763.017c.343-.058-.343-.353-.343-.353a2.689,2.689,0,0,0-1.2-.234c-.772,0-1.458-.117-.772-.353s-.085-.47-.085-.47-2.572,0-4.8-.059-3,.176-3.086.059,0,0,.514-.118a40.346,40.346,0,0,0-4.115-.176s-2.058.059-2.572.059-.257-.353-.772-.353-6.686-.176-7.8-.176-1.8-.117-6.687-.117-3.515-.235-4.715-.412-17.06,0-18.26-.058,1.029-.294-2.057-.118-14.231.353-15.089.353-1.628,0-2.914-.059-.515,0-.686.235-1.886.234-7.373.234-8.744.176-10.116.06a6.233,6.233,0,0,0-2.143.116l-.343.06a37.342,37.342,0,0,1-3.772.234c-1.372,0-3.686.06-3.686.06l-.172.234s.858,0-2.057.234a16.3,16.3,0,0,1-4.029,0l-.772.529A2.9,2.9,0,0,0,7706.115,9062.128Zm107.891.394a3.144,3.144,0,0,1-.73.018C7812.882,9062.506,7813.451,9062.511,7814.006,9062.521Zm-8.874-2.155a3.7,3.7,0,0,0,.857.118h5.915c1.029-.294-2.229-.354-4.715-.354S7805.132,9060.366,7805.132,9060.366ZM7717,9062.951a4.935,4.935,0,0,0-1.715.176c-.343.117.6.294.6.294l1.458-.118S7717.517,9063.068,7717,9062.951Z" fill="#ff6450"/>
				</g>
			</svg>
		</div>
		<?php if ( $member['title'] ) : ?>
			<div class="absp-team-carousel-title">
				<h2><?php absp_render_title( $member['title'] ); ?></h2>
			</div>
		<?php endif; ?>
		<?php $this->render_member_contact( $member ) ?>
		<?php $this->render_short_bio( $member ) ?>
		<?php $this->render_button( $member ) ?>
		<?php $this->render_links( $member ) ?>
	</div>
</div>
