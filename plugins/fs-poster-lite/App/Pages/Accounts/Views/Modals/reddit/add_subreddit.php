<?php

namespace FSPoster\App\Pages\Accounts\Views;

use FSPoster\App\Providers\Pages;

defined( 'FSPL_MODAL' ) or exit;

function wp_enqueue_accounts_subreddit_js()
{
	wp_register_script( 'fsp-accounts-subreddit', Pages::asset( 'Accounts', 'js/fsp-accounts-subreddit.js' ) );
	wp_enqueue_script( 'fsp-accounts-subreddit' );
}

add_action( 'admin_print_scripts', 'FSPoster\App\Pages\Accounts\Views\wp_enqueue_accounts_subreddit_js' );
do_action( 'admin_print_scripts' );
?>
<div class="fsp-modal-header">
	<div class="fsp-modal-title">
		<div class="fsp-modal-title-icon">
			<i class="fab fa-reddit-alien"></i>
		</div>
		<div class="fsp-modal-title-text">
			<?php echo esc_html__( 'Add a subreddit', 'fs-poster' ); ?>
		</div>
	</div>
	<div class="fsp-modal-close" data-modal-close="true">
		<i class="fas fa-times"></i>
	</div>
</div>
<div class="fsp-modal-body">
	<div class="fsp-modal-step">
		<input type="hidden" id="fspAccountID" value="<?php echo esc_html( $fsp_params[ 'accountId' ] ); ?>">
		<div class="fsp-form-group">
			<label><?php echo esc_html__( 'Select a subreddit', 'fs-poster' ); ?></label>
			<select class="fsp-form-select" id="fspSubredditSelector"></select>
		</div>
		<div id="fspFlairSelectorContainer" class="fsp-form-group fsp-hide">
			<label><?php echo esc_html__( 'Select flair', 'fs-poster' ); ?></label>
			<select class="fsp-form-select" id="fspFlairSelector"></select>
		</div>
	</div>
</div>
<div class="fsp-modal-footer">
	<button class="fsp-button fsp-is-gray" data-modal-close="true"><?php echo esc_html__( 'Cancel', 'fs-poster' ); ?></button>
	<button id="fspModalAddSubredditButton" class="fsp-button"><?php echo esc_html__( 'GET ACCESS', 'fs-poster' ); ?></button>
</div>
