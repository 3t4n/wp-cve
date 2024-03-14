<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;

defined( 'FSPL_MODAL' ) or exit;

function wp_enqueue_accounts_plurk_css()
{
	wp_register_style( 'fsp-accounts-plurk', Pages::asset( 'Accounts', 'css/fsp-accounts-plurk.css' ) );
	wp_enqueue_style( 'fsp-accounts-plurk' );
}

add_action( 'admin_print_styles', 'FSPoster\App\Pages\Accounts\Views\wp_enqueue_accounts_plurk_css' );
do_action( 'admin_print_styles' );
?>
<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fas fa-parking"></i>
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
	<div class="fsp-plurk-steps">
		<div class="fsp-form-group fsp-plurk-step" data-step="1">
			<input id="plurkRequestToken" type="hidden" value="<?php echo esc_html( $fsp_params['data']['request_token']['token'] ) ?>">
			<input id="plurkRequestTokenSecret" type="hidden" value="<?php echo esc_html( $fsp_params['data']['request_token']['secret'] ) ?>">
			<input id="fspModalPlurkAuthLink" type="hidden" value="<?php echo esc_html( $fsp_params['data']['link'] ) ?>">
			<button type="button" id="fspGetAccessToken" class="fsp-button fsp-plurk-button">
				<?php echo esc_html__( 'GET ACCESS', 'fs-poster' ); ?>
			</button>
		</div>
		<div class="fsp-form-group fsp-plurk-step" data-step="2">
			<?php echo esc_html__( 'When the authorization has completed, copy the verification code', 'fs-poster' ); ?>
		</div>
		<div class="fsp-form-group fsp-plurk-step" data-step="3">
			<label><?php echo esc_html__( 'Verification code', 'fs-poster' ); ?></label>
			<input type="text" id="plurkVerifier" class="fsp-form-input" placeholder="<?php echo esc_html__( 'Paste the copied verifier here', 'fs-poster' ); ?>">
		</div>
	</div>
</div>
<div class="fsp-modal-footer">
	<button class="fsp-button fsp-is-gray" data-modal-close="true"><?php echo esc_html__( 'Cancel', 'fs-poster' ); ?></button>
	<button id="fspModalAddButton" class="fsp-button"><?php echo esc_html__( 'ADD', 'fs-poster' ); ?></button>
</div>

<?php
function wp_enqueue_accounts_plurk_js()
{
	wp_register_script( 'fsp-accounts-plurk', Pages::asset( 'Accounts', 'js/fsp-accounts-plurk.js' ) );
	wp_enqueue_script( 'fsp-accounts-plurk' );
}

add_action( 'admin_print_scripts', 'FSPoster\App\Pages\Accounts\Views\wp_enqueue_accounts_plurk_js' );
do_action( 'admin_print_scripts' ); ?>