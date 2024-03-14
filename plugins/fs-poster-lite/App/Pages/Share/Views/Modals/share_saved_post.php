<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;

defined( 'FSPL_MODAL' ) or exit;

function wp_enqueue_share_popup_css()
{
	wp_register_style( 'fsp-share-popup', Pages::asset( 'Share', 'css/fsp-share-popup.css' ) );
	wp_enqueue_style( 'fsp-share-popup' );
}

add_action( 'admin_print_styles', 'FSPoster\App\Pages\Accounts\Views\wp_enqueue_share_popup_css' );
do_action( 'admin_print_styles' );

function wp_enqueue_share_popup_js()
{
	wp_register_script( 'fsp-share-popup', Pages::asset( 'Share', 'js/fsp-share-popup.js' ) );
	wp_enqueue_script( 'fsp-share-popup' );
}

add_action( 'admin_print_scripts', 'FSPoster\App\Pages\Accounts\Views\wp_enqueue_share_popup_js' );
do_action( 'admin_print_scripts' );
?>
<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fas fa-share"></i>
		</div>
		<div class="fsp-modal-title-text">
			<?php echo esc_html__( 'Share', 'fs-poster' ); ?>
		</div>
	</div>
	<div class="fsp-modal-close" data-modal-close="true">
		<i class="fas fa-times"></i>
	</div>
</div>
<div class="fsp-modal-body">
	<div class="fsp-form-group">
		<?php
		$post_id = (int) $fsp_params[ 'parameters' ][ 'post_id' ];
		define( 'FSPL_NOT_CHECK_SP', 'true' );

		Pages::controller( 'Base', 'MetaBox', 'post_meta_box', [
			'post_id'          => $post_id,
			'minified_metabox' => TRUE
		] );
		?>
	</div>
</div>
<div class="fsp-modal-footer">
	<button class="fsp-button fsp-is-gray" data-modal-close="true"><?php echo esc_html__( 'CANCEL', 'fs-poster' ); ?></button>
	<button class="fsp-button share_btn"><?php echo esc_html__( 'SHARE', 'fs-poster' ); ?></button>
</div>

<script>
	FSPObject.postID = '<?php echo (int) $fsp_params[ 'parameters' ][ 'post_id' ]; ?>';
</script>
