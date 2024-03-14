<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
/**
 * @var $this WFACP_Template_Common
 */


$rbox_border_type = '';
if ( isset( $data['advance_setting']['rbox_border_type'] ) && $data['advance_setting']['rbox_border_type'] != '' ) {
	$rbox_border_type = $data['advance_setting']['rbox_border_type'];
}


?>


<!--   Below Form Section -->
<div class="<?php echo $section_key . ' ' . $rbox_border_type; ?> div_wrap_sec wfacp_html_widget">
	<?php
	if ( isset( $data['data'] ) ) {
		$content = apply_filters( 'wfacp_the_content', $data['data'] );
		echo sprintf( '<div class="content_wrap">%s</div>', $content );
	}

	?>

</div>
<!--   Below Form Section Closed-->
