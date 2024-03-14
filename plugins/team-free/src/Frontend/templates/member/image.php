<?php
/**
 * Member image
 *
 * This template can be overridden by copying it to yourtheme/team-free/templates/member/image.php
 *
 * @package team-free
 * @subpackage team-free\Frontend\templates\member
 * @since 2.1.0
 */

$member_image_tag        = $link_detail ? 'a' : 'div';
$single_page_link        = $link_detail ? ' href=' . $anchor_tag_param['href'] . '' : '';
$single_page_link_target = $link_detail ? ' target=' . $new_page_target . ' ' : '';
?>
<<?php echo esc_html( $member_image_tag ); ?> class='sptp-member-avatar' <?php echo esc_attr( $single_page_link ) . esc_attr( $single_page_link_target ) . esc_attr( $nofollow_link_text ); ?>>
	<span class="sptp-member-avatar-img <?php echo esc_html( $image_shape ) . ' ' . esc_html( $image_zoom ); ?>">
		<img src="<?php echo esc_attr( $member_image_attr['src'] ); ?>" alt="<?php echo ( $image_alt ) ? esc_attr( $image_alt ) : esc_attr( get_the_title( $generator_id ) ); ?>" width="<?php echo esc_attr( $member_image_attr['width'] ); ?>" height="<?php echo esc_attr( $member_image_attr['height'] ); ?>">
	</span>
</<?php echo esc_html( $member_image_tag ); ?>>
