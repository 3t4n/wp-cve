<?php
/**
 * Member name
 *
 * This template can be overridden by copying it to yourtheme/team-free/templates/member/name.php
 *
 * @package team-free
 * @subpackage team-free\Frontend\templates\member
 * @since 2.1.0
 */

$title_tag = apply_filters( 'sptp_member_name_tag', 'h2' );
?>
<div class="sptp-member-name">
	<?php do_action( 'sp_team_before_member_name' ); ?>
	<<?php echo esc_html( $title_tag ); ?> class='sptp-member-name-title'><?php echo wp_kses_post( $member->post_title ); ?></<?php echo esc_html( $title_tag ); ?>>
	<?php do_action( 'sp_team_after_member_name' ); ?>
</div>
