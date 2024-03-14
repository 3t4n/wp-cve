<?php
/**
 * Member Phone
 *
 * This template can be overridden by copying it to yourtheme/team-free/templates/member/job_title.php
 *
 * @package team-free
 * @subpackage team-free\Frontend\templates\member
 * @since 2.1.0
 */

?>
<div class="sptp-member-profession">
	<?php do_action( 'sp_team_before_member_job_title' ); ?>
		<h4 class="sptp-jop-title"><?php echo esc_html( $member_info['sptp_job_title'] ); ?></h4>
	<?php do_action( 'sp_team_after_member_job_title' ); ?>
</div>
