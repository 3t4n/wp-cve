<?php
defined( 'ABSPATH' ) || exit;

$source_left      = ! empty( $this->data->img_l_source ) ? $this->data->img_l_source : '';
$source_right     = ! empty( $this->data->img_r_source ) ? $this->data->img_r_source : '';
$left_image_link  = ! empty( $this->data->img_l_link ) ? $this->data->img_l_link : 'javascript:void(0)';
$right_image_link = ! empty( $this->data->img_r_link ) ? $this->data->img_r_link : 'javascript:void(0)';

if ( $source_left == '' && $source_right == '' ) {
	XLWCTY_Core()->public->add_header_logs( sprintf( '%s - %s', $this->get_component_property( 'title' ), __( 'Data not set', 'woo-thank-you-page-nextmove-lite' ) ) );

	return '';
}
XLWCTY_Core()->public->add_header_logs( sprintf( '%s - %s', $this->get_component_property( 'title' ), __( 'On', 'woo-thank-you-page-nextmove-lite' ) ) );
?>
<div class="xlwcty_Box xlwcty_imgBox <?php echo 'xlwcty_imgBox_1'; ?>">
    <div class="xlwcty_title"><?php echo XLWCTY_Common::maype_parse_merge_tags( $this->data->heading ); ?></div>
	<?php
	$desc_class = '';
	if ( ! empty( $this->data->desc_alignment ) ) {
		$desc_class = ' class="xlwcty_' . $this->data->desc_alignment . '"';
	}
	echo $this->data->desc ? '<div' . $desc_class . '>' . apply_filters( 'xlwcty_the_content', $this->data->desc ) . '</div>' : '';
	?>
    <div class="xlwcty_imgBox_w xlwcty_imgBox_2cw xlwcty_clearfix">
		<?php
		if ( $source_left != '' ) {
			?>
            <div class="xlwcty_content xlwcty_center xlwcty_50" data-style="left">
				<?php
				echo sprintf( "<a href='%s' class='xlwcty_content_block_image_link'><img src='%s' class='xlwcty_content_block_image'/></a>", $left_image_link, $source_left );
				?>
            </div>
			<?php
		}
		if ( $source_right != '' ) {
			?>
            <div class="xlwcty_content xlwcty_center xlwcty_50" data-style="right">
				<?php
				echo sprintf( "<a href='%s' class='xlwcty_content_block_image_link'><img src='%s' class='xlwcty_content_block_image'/></a>", $right_image_link, $source_right );
				?>
            </div>
			<?php
		}
		?>
    </div>
	<?php
	if ( $this->data->show_btn == 'yes' && $this->data->btn_text != '' ) {
		$btn_link = ! empty( $this->data->btn_link ) != '' ? $this->data->btn_link : 'javascript:void(0)';
		?>
        <div class="xlwcty_clear_20"></div>
        <div class="xlwcty_clearfix xlwcty_center">
            <a href="<?php echo XLWCTY_Common::maype_parse_merge_tags( $btn_link ); ?>" class="xlwcty_btn">
				<?php echo XLWCTY_Common::maype_parse_merge_tags( $this->data->btn_text ); ?>
            </a>
        </div>
		<?php
	}
	?>
</div>
