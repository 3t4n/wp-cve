<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;

defined( 'FSPL_MODAL' ) or exit;
?>

<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fab fa-telegram-plane"></i>
		</div>
		<div class="fsp-modal-title-text">
			<?php echo esc_html__( 'Add a bot', 'fs-poster' ); ?>
		</div>
	</div>
	<div class="fsp-modal-close" data-modal-close="true">
		<i class="fas fa-times"></i>
	</div>
</div>
<div class="fsp-modal-body">
	<div class="fsp-modal-step">
		<div class="fsp-form-group">
			<label class="fsp-is-jb">
				<?php echo esc_html__( 'Bot token', 'fs-poster' ); ?>
				<a href="https://www.fs-poster.com/documentation/fs-poster-auto-publish-wordpress-posts-to-telegram" target="_blank" class="fsp-tooltip" data-title="<?php echo esc_html__( 'How to?', 'fs-poster' ); ?>">
					<i class="far fa-question-circle"></i>
				</a>
			</label>
			<div class="fsp-form-input-has-icon">
				<i class="fas fa-robot"></i>
				<input id="fspBotToken" autocomplete="off" class="fsp-form-input" placeholder="<?php echo esc_html__( 'Bot token', 'fs-poster' ); ?>">
			</div>
		</div>
	</div>
</div>
<div class="fsp-modal-footer">
	<button class="fsp-button fsp-is-gray" data-modal-close="true"><?php echo esc_html__( 'Cancel', 'fs-poster' ); ?></button>
	<button id="fspModalAddButton" class="fsp-button"><?php echo esc_html__( 'ADD', 'fs-poster' ); ?></button>
</div>
<?php
function wp_enqueue_accounts_telegram_js()
{
	wp_register_script( 'fsp-accounts-telegram', Pages::asset( 'Accounts', 'js/fsp-accounts-telegram.js' ) );
	wp_enqueue_script( 'fsp-accounts-telegram' );
}

add_action( 'admin_print_scripts', 'FSPoster\App\Pages\Accounts\Views\wp_enqueue_accounts_telegram_js' );
do_action( 'admin_print_scripts' );
?>