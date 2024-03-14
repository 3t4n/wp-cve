<?php
/**
 * Section title.
 *
 * This template can be overridden by copying it to yourtheme/team-free/templates/section-title.php
 *
 * @package team-free
 * @subpackage team-free\Frontend\templates
 * @since 2.1.0
 */

?>
<h2 class="sptp-section-title">
	<?php do_action( 'sp_team_before_section_title' ); ?>
		<span><?php echo wp_kses_post( $title ); ?></span>
	<?php do_action( 'sp_team_after_section_title' ); ?>
</h2>
