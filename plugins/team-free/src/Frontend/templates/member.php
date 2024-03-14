<?php
/**
 * Single member.
 *
 * This template can be overridden by copying it to yourtheme/team-free/templates/member.php
 *
 * @package team-free
 * @subpackage team-free\Frontend\templates
 * @since 2.1.0
 */

use ShapedPlugin\WPTeam\Frontend\Helper;

$image_alt                                   = get_post_meta( get_post_thumbnail_id( $member->ID ), '_wp_attachment_image_alt', true );
$member_info                                 = get_post_meta( $member->ID, '_sptp_add_member', true );
$member_id                                   = $member->ID;
$image_size                                  = isset( $settings['image_size'] ) ? $settings['image_size'] : 'medium';
$member_image_attr                           = Helper::get_sptp_member_image_attr( $member, $image_size );
$conditional_tag_before_image                = '';
$conditional_tag_before_content              = '';
$conditional_before_content_last_closing_tag = '';
if ( 'left_img_right_content' === $position || 'left_content_right_img' === $position || 'content_over_image' === $position ) {
	$img_class                                   = ( ! empty( $member_image_attr ) && ( $style_members['image_switch'] ) && $image_on_off ) ? ' image' : '';
	$content_class                               = ( empty( $member_image_attr['src'] ) || ( $style_members['image_switch'] ) || ! $image_on_off ) ? '' : ' no-image ';
	$content_class                              .= $image_shape;
	$conditional_tag_before_image                = '<div class="' . $img_class . '">';
	$conditional_tag_before_content              = '</div><div class="content ' . $content_class . '">';
	$conditional_before_content_last_closing_tag = '</div>';
}
?>
<div class="sptp-member <?php echo esc_html( $border_bg_around_member_class ) . esc_html( $position_class ); ?>">
	<?php
	echo wp_kses_post( $conditional_tag_before_image );
		Helper::member_image( $member_image_attr, $settings, $member, $generator_id, $layout_preset, $image_alt );
	echo wp_kses_post( $conditional_tag_before_content );
		Helper::member_name( $member, $show_member_name );
		Helper::member_job_title( $member_info, $show_member_position, $member_id );
		Helper::member_description( $member_info, $show_member_bio, $member, $biography_type );
		Helper::member_social( $member_info, $show_member_social, $social_icon_shape, $sptp_no_follow, $member_id );
	echo wp_kses_post( $conditional_before_content_last_closing_tag );
	?>
</div><!-- .sptp-member -->
