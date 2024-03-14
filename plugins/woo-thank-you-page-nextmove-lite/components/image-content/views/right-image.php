<?php
defined( 'ABSPATH' ) || exit;

$source_left     = ! empty( $this->data->img_r_source ) ? $this->data->img_r_source : '';
$left_image_link = ! empty( $this->data->img_r_link ) ? $this->data->img_r_link : 'javascript:void(0)';
$content         = $this->data->editor;
$ratio           = $this->data->img_cont_ratio;
$left_class      = 'xlwcty_50';
$right_class     = 'xlwcty_50';
if ( $ratio == '33_66' ) {
	$left_class  = 'xlwcty_33';
	$right_class = 'xlwcty_66 xlwcty_left_space';
}
if ( $ratio == '66_33' ) {
	$left_class  = 'xlwcty_66';
	$right_class = 'xlwcty_33 xlwcty_left_space';
}
if ( $source_left == '' ) {
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
    <div class="xlwcty_imgBox_w xlwcty_clearfix">
		<?php
		if ( $content != '' ) {
			?>
            <div class="xlwcty_content <?php echo $left_class; ?>" data-style="left">
				<?php
				echo apply_filters( 'xlwcty_the_content', $content );
				?>
            </div>
			<?php
		}
		?>
        <div class="xlwcty_content xlwcty_center <?php echo $right_class; ?>" data-style="right">
			<?php
			echo sprintf( "<p><a href='%s' class='xlwcty_content_block_image_link'><img src='%s' class='xlwcty_content_block_image'/></a></p>", $left_image_link, $source_left );
			?>
        </div>
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
