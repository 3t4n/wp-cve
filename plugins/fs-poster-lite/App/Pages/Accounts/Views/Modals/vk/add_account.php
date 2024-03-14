<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;

defined( 'FSPL_MODAL' ) or exit;

function wp_enqueue_accounts_vk_css()
{
	wp_register_style( 'fsp-accounts-vk', Pages::asset( 'Accounts', 'css/fsp-accounts-vk.css' ) );
	wp_enqueue_style( 'fsp-accounts-vk' );
}

add_action( 'admin_print_styles', 'FSPoster\App\Pages\Accounts\Views\wp_enqueue_accounts_vk_css' );
do_action( 'admin_print_styles' );
?>
<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fab fa-vk"></i>
		</div>
		<div class="fsp-modal-title-text">
			<?php echo esc_html__( 'Add an account', 'fs-poster' ); ?>
		</div>
	</div>
	<div class="fsp-modal-close" data-modal-close="true">
		<i class="fas fa-times"></i>
	</div>
</div>
<div class="fsp-modal-body">
	<div class="fsp-vk-steps">
		<div class="fsp-form-group fsp-vk-step" data-step="1">
			<input type="hidden" id="vk_app" value="<?php echo esc_html( $fsp_params[ 'app' ] ); ?>">
			<button type="button" id="fspGetAccessToken" class="fsp-button fsp-vk-button">
				<?php echo esc_html__( 'GET ACCESS', 'fs-poster' ); ?>
			</button>
		</div>
		<div class="fsp-form-group fsp-vk-step" data-step="2">
			<?php echo esc_html__( 'When the authorization has completed, copy the URL', 'fs-poster' ); ?>
		</div>
		<div class="fsp-form-group fsp-vk-step" data-step="3">
			<label><?php echo esc_html__( 'URL', 'fs-poster' ); ?></label>
			<textarea id="fspAccessToken" class="fsp-form-textarea" placeholder="<?php echo esc_html__( 'Paste the copied URL here', 'fs-poster' ); ?>"></textarea>
		</div>
	</div>
</div>
<div class="fsp-modal-footer">
	<button class="fsp-button fsp-is-gray" data-modal-close="true"><?php echo esc_html__( 'Cancel', 'fs-poster' ); ?></button>
	<button id="fspModalAddButton" class="fsp-button"><?php echo esc_html__( 'ADD', 'fs-poster' ); ?></button>
</div>
<?php
function wp_enqueue_accounts_vk_js()
{
	wp_register_script( 'fsp-accounts-vk', Pages::asset( 'Accounts', 'js/fsp-accounts-vk.js' ) );
	wp_enqueue_script( 'fsp-accounts-vk' );
}

add_action( 'admin_print_scripts', 'FSPoster\App\Pages\Accounts\Views\wp_enqueue_accounts_vk_js' );
do_action( 'admin_print_scripts' );
?>