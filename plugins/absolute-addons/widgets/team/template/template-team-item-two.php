<?php
/**
 * Template Style Two for Team
 *
 * @package AbsoluteAddons
 * @var  $settings
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
?>
<!--Team Start-->
<div class="absp-team-item">
	<div class="hover-single-box">
		<div class="hover-thumb">
			<img class="member_image" src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>"	 alt="<?php echo esc_html( $settings['team_member_first_name'] ); ?>">
		</div>
		<div class="hover-content">
			<div class="name-area">
				<span> <?php echo esc_html( $settings['team_member_first_name'] ); ?></span>
				<h1><?php echo esc_html( $settings['team_member_last_name'] ); ?></h1>
			</div>
			<a class="position"><?php echo esc_html( $settings['team_member_designation'] ); ?></a>
		</div>
		<div class="single-box">
			<div class="image-part">
				<div class="img-thumb">
					<img class="member_image" src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>" alt="<?php echo esc_html( $settings['team_member_first_name'] . ' ' . $settings['team_member_last_name'] ); ?>">
				</div>
				<div class="img-meta">
					<div class="name-area">
						<h5><?php echo esc_html( $settings['team_member_first_name'] ); ?></h5>
						<h2><?php echo esc_html( $settings['team_member_last_name'] ); ?></h2>
					</div>
					<span><?php echo esc_html( $settings['team_member_designation'] ); ?></span>
				</div>
			</div>
			<?php if ( 'yes' == $settings['separator_enable_two'] ) : ?>
				<hr class="border-bottom">
			<?php endif; ?>
			<div class="image-para">
				<h5><?php echo esc_html( $settings['team_member_about_label'] ); ?></h5>
				<div class="desc">
					<?php echo wp_kses_post( $settings['team_member_about'] ); ?>
				</div>
			</div>
			<?php if ( 'yes' == $settings['separator_enable_two'] ) : ?>
				<hr class="border-bottom">
			<?php endif; ?>
			<div class="social-link">
				<h5><?php echo esc_html( $settings['team_member_social_profile_title'] ); ?></h5>
				<nav>
					<ul>
						<?php foreach ( $settings['team_member_social_media'] as $social_media ) : ?>
							<li class="elementor-repeater-item-<?php echo esc_attr( $social_media['_id'] ); ?> ">
								<a href="<?php echo esc_url( $social_media['team_member_social_icon_url'] ); ?>" class="social-icon">
									<i class=" <?php echo esc_attr( $social_media['team_member_social_icon']['value'] ); ?>" aria-hidden="true"></i>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</div>
