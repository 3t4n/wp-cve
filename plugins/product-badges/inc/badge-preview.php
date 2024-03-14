<?php

if ( ! defined( 'LION_BADGES_VERSION' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

?>
<div id="badge-preview-container">
	<div id="badge-preview-inner">
		<div id="<?php echo esc_attr( $badge_shape ); ?>" class="badge-preview-shape" style="<?php echo esc_attr( $shape_inline_css ); ?>">
			<div id="badge-preview-text" style="<?php echo esc_attr( $text_inline_css ); ?>"><?php echo esc_attr( $badge_text ); ?></div>
		</div>
	</div>
</div>