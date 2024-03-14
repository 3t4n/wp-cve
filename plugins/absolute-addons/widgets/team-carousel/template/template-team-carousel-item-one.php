<?php
/**
 * Template Style One for Team Carousel
 *
 * @package AbsoluteAddons
 * @var $member
 * @var string $image
 * @var $job_title
 * @var $index
 *
 */
?>
<!-- single-team-carousel-item -->
<div class="swiper-slide">
	<div class="absp-team-carousel-item">
		<?php if ( $image ) : ?>
			<div class="absp-team-carousel-image">
				<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $member['title'] ); ?>">
			</div>
		<?php endif; ?>

		<?php if ( $member['title'] ) : ?>
			<div class="absp-team-carousel-title">
				<h2><?php absp_render_title( $member['title'] ); ?></h2>
			</div>
		<?php endif; ?>

		<?php if ( $member['job_title'] ) : ?>
			<div <?php $this->print_render_attribute_string( $job_title ) ?>>
				<?php absp_render_title( $member['job_title'] ); ?>
			</div>
		<?php endif; ?>
		<?php $this->render_member_contact( $member ) ?>
		<?php $this->render_short_bio( $member ) ?>
		<?php $this->render_button( $member ) ?>
		<?php $this->render_links( $member ); ?>
	</div>
</div>
