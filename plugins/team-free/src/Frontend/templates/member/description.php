<?php
/**
 * Member Description.
 *
 * This template can be overridden by copying it to yourtheme/team-free/templates/member/description.php
 *
 * @package team-free
 * @subpackage team-free\Frontend\templates\member
 * @since 2.1.0
 */

?>
<div class="sptp-member-desc">
		<?php
		do_action( 'sp_team_before_member_description' );
			echo wp_kses( $description, $allowed_html );
		do_action( 'sp_team_after_member_description' );
		?>
</div>
