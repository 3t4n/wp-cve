<?php
/**
 * Template Style Nine for Team
 *
 * @package AbsoluteAddons
 * @var $settings
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
	<div class="absp-team-thumb">
		<figure>
			<img src="<?php echo esc_url( $settings['team_member_image']['url'] ); ?>" alt="<?php echo esc_html( $settings['team_member_name'] ); ?>">
		</figure>
	</div>

	<div class="absp-team-info">
		<h3 class="absp-team-title"><?php absp_widget_title_kses( $settings['team_member_name'] ); ?></h3>
		<span class="absp-team-designation"><?php absp_widget_title_kses( $settings['team_member_designation'] ); ?></span>
		<div class="absp-team-content">
			<?php echo wp_kses( $settings['team_member_about'] ,  ''); ?>
		</div>
		<a href="<?php echo esc_url( $settings['team_member_button_url']['url'] ); ?>" class="absp-team-btn"><?php echo esc_html( $settings['team_member_button_text'] ); ?></a>
	</div>
</div>
