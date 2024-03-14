<?php

namespace FSPoster\App\Pages\Base\Views;

use FSPoster\App\Providers\Pages;
use FSPoster\App\Providers\Helper;

defined( 'ABSPATH' ) or exit;

function wp_enqueue_metabox_js ()
{
	wp_register_script( 'fsp-metabox', Pages::asset( 'Base', 'js/fsp-metabox.js' ) );
	wp_enqueue_script( 'fsp-metabox' );
}

function wp_enqueue_metabox_css ()
{
	wp_register_style( 'fsp-metabox', Pages::asset( 'Base', 'css/fsp-metabox.css' ) );
	wp_enqueue_style( 'fsp-metabox' );
}
add_action( 'admin_print_styles', 'FSPoster\App\Pages\Base\Views\wp_enqueue_metabox_css' );
add_action( 'admin_print_scripts', 'FSPoster\App\Pages\Base\Views\wp_enqueue_metabox_js' );

do_action( 'admin_print_styles' );
do_action( 'admin_print_scripts' );
?>
<div class="fsp-metabox <?php echo $fsp_params[ 'minified' ] === TRUE ? 'fsp-is-mini' : 'fsp-card'; ?>">
	<div class="fsp-card-body">
		<div class="fsp-form-toggle-group">
			<label><?php echo esc_html__( 'Share', 'fs-poster' ); ?></label>
			<div class="fsp-toggle">
				<input type="hidden" name="share_checked" value="off">
				<input type="checkbox" name="share_checked" class="fsp-toggle-checkbox" id="fspMetaboxShare" <?php echo $fsp_params[ 'share_checkbox' ] ? 'checked' : ''; ?>>
				<label class="fsp-toggle-label" for="fspMetaboxShare"></label>
			</div>
		</div>
		<div id="fspMetaboxShareContainer">
			<div class="fsp-metabox-tabs">
				<div data-tab="all" class="fsp-metabox-tab fsp-is-active fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show all accounts', 'fs-poster' ); ?>">
					<i class="fas fa-grip-horizontal"></i>
				</div>
				<div data-tab="fb" class="fsp-metabox-tab fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Facebook accounts', 'fs-poster' ); ?>">
					<i class="fab fa-facebook-f"></i>
				</div>
				<div data-tab="linkedin" class="fsp-metabox-tab fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Linkedin accounts', 'fs-poster' ); ?>">
					<i class="fab fa-linkedin-in"></i>
				</div>
				<div data-tab="vk" class="fsp-metabox-tab fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only VKontakte accounts', 'fs-poster' ); ?>">
					<i class="fab fa-vk"></i>
				</div>
				<div data-tab="reddit" class="fsp-metabox-tab fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Reddit accounts', 'fs-poster' ); ?>">
					<i class="fab fa-reddit-alien"></i>
				</div>
				<div data-tab="tumblr" class="fsp-metabox-tab fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Tumblr accounts', 'fs-poster' ); ?>">
					<i class="fab fa-tumblr"></i>
				</div>
				<div data-tab="ok" class="fsp-metabox-tab fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Odnoklassniki accounts', 'fs-poster' ); ?>">
					<i class="fab fa-odnoklassniki"></i>
				</div>
				<div data-tab="plurk" class="fsp-metabox-tab fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Plurk accounts', 'fs-poster' ); ?>">
					<i class="fas fa-parking"></i>
				</div>
				<div data-tab="telegram" class="fsp-metabox-tab fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Telegram accounts', 'fs-poster' ); ?>">
					<i class="fab fa-telegram-plane"></i>
				</div>
				<div data-tab="fsp" class="fsp-metabox-tab fsp-crowned fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show all groups', 'fs-poster' ); ?>">
					<i class="fas fa-object-group"></i>
				</div>
				<div data-tab="instagram" class="fsp-metabox-tab fsp-crowned fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Instagram accounts', 'fs-poster' ); ?>">
					<i class="fab fa-instagram"></i>
				</div>
				<div data-tab="pinterest" class="fsp-metabox-tab fsp-crowned fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Pinterest accounts', 'fs-poster' ); ?>">
					<i class="fab fa-pinterest-p"></i>
				</div>
				<div data-tab="google_b" class="fsp-metabox-tab fsp-crowned fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Google My Business accounts', 'fs-poster' ); ?>">
					<i class="fab fa-google"></i>
				</div>
				<div data-tab="blogger" class="fsp-metabox-tab fsp-tooltip fsp-crowned fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Blogger accounts', 'fs-poster' ); ?>">
					<i class="fab fa-blogger"></i>
				</div>
				<div data-tab="medium" class="fsp-metabox-tab fsp-crowned fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only Medium accounts', 'fs-poster' ); ?>">
					<i class="fab fa-medium-m"></i>
				</div>
				<div data-tab="wordpress" class="fsp-metabox-tab fsp-crowned fsp-tooltip fsp-temp-tooltip" data-title="<?php echo esc_html__( 'Show only WordPress websites', 'fs-poster' ); ?>">
					<i class="fab fa-wordpress-simple"></i>
				</div>
			</div>
			<div class="fsp-metabox-accounts">
				<div class="fsp-metabox-accounts-empty">
					<?php echo esc_html__( 'Please select an account', 'fs-poster' ); ?>
				</div>
				<?php foreach ( $fsp_params[ 'active_nodes' ] as $node_info )
				{
					$coverPhoto = Helper::profilePic( $node_info );

					$sn_names = [
						'fb'        => esc_html__( 'FB', 'fs-poster' ),
						'fsp'       => esc_html__( 'FSP', 'fs-poster' ),
						'instagram' => esc_html__( 'Instagram', 'fs-poster' ),
						'linkedin'  => esc_html__( 'Linkedin', 'fs-poster' ),
						'vk'        => esc_html__( 'VK', 'fs-poster' ),
						'pinterest' => esc_html__( 'Pinterest', 'fs-poster' ),
						'reddit'    => esc_html__( 'Reddit', 'fs-poster' ),
						'tumblr'    => esc_html__( 'Tumblr', 'fs-poster' ),
						'ok'        => esc_html__( 'OK', 'fs-poster' ),
						'plurk'     => esc_html__( 'Plurk', 'fs-poster' ),
						'google_b'  => esc_html__( 'GMB', 'fs-poster' ),
						'blogger'   => esc_html__( 'Blogger', 'fs-poster' ),
						'telegram'  => esc_html__( 'Telegram', 'fs-poster' ),
						'medium'    => esc_html__( 'Medium', 'fs-poster' ),
						'wordpress' => esc_html__( 'WordPress', 'fs-poster' )
					];
					$driver   = $sn_names[ $node_info[ 'driver' ] ];
					$node_type = isset($node_info['node_type']) ? $node_info['node_type'] : 'account';
					?>

					<div data-driver="<?php echo esc_html( $node_info[ 'driver' ] ); ?>" class="fsp-metabox-account">
						<input type="hidden" name="share_on_nodes[]" value="<?php echo esc_html( $node_info[ 'driver' ] . ':' . $node_type . ':' . $node_info[ 'id' ] . ':' . 'no' . ':' . '' ); ?>">
						<div class="fsp-metabox-account-image">
							<img src="<?php echo esc_html( $coverPhoto ); ?>" onerror="FSPoster.no_photo( this );">
						</div>
						<div class="fsp-metabox-account-label">
							<a target="_blank" href="<?php echo Helper::profileLink( $node_info ); ?>" class="fsp-metabox-account-text">
								<?php echo esc_html( $node_info[ 'name' ] ); ?>
							</a>
							<div class="fsp-metabox-account-subtext">
								<?php echo esc_html( $driver ); ?>&nbsp;>&nbsp;<?php echo esc_html( $node_type ); ?>&nbsp;<?php echo empty( $titleText ) ? '' : '<i class="fas fa-filter fsp-tooltip" data-title="' . esc_html( $titleText ) . '" ></i>'; ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<div id="fspMetaboxCustomMessages" class="fsp-metabox-custom-messages">
				<input type="hidden" name="is_fsp_request" value="true">
				<div data-driver="fb">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Facebook post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="4" maxlength="3000" name="fs_post_text_message_fb">{title} {link}</textarea>
				</div>
				<div data-driver="linkedin">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize LinkedIn post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="4" maxlength="3000" name="fs_post_text_message_linkedin">{title} {link}</textarea>
				</div>
				<div data-driver="vk">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize VKontakte post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="4" maxlength="3000" name="fs_post_text_message_vk">{title} {link}</textarea>
				</div>
				<div data-driver="telegram">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Telegram post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="4" maxlength="3000" name="fs_post_text_message_telegram">{title} {link}</textarea>
				</div>
				<div data-driver="pinterest">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Pinterest post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="4" maxlength="3000" name="fs_post_text_message_pinterest">{title} {link}</textarea>
				</div>
				<div data-driver="reddit">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Reddit post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="4" maxlength="3000" name="fs_post_text_message_reddit">{title} {link}</textarea>
				</div>
				<div data-driver="tumblr">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Tumblr post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="4" maxlength="3000" name="fs_post_text_message_tumblr">{title} {link}</textarea>
				</div>
				<div data-driver="ok">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Odnoklassniki post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="4" maxlength="3000" name="fs_post_text_message_ok">{title} {link}</textarea>
				</div>
				<div data-driver="plurk">
					<div class="fsp-metabox-custom-message-label">
						<i class="fas fa-chevron-down"></i>&nbsp;<?php echo esc_html__( 'Customize Plurk post message', 'fs-poster' ); ?>
					</div>
					<textarea class="fsp-form-textarea" rows="4" maxlength="3000" name="fs_post_text_message_plurk">{title} {link}</textarea>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	( function ( $ ) {
		let doc = $( document );

		doc.ready( function () {
			<?php if ( ! defined( 'FSPL_NOT_CHECK_SP' ) && isset( $fsp_params[ 'check_not_sended_feeds' ] ) && $fsp_params[ 'check_not_sended_feeds' ][ 'cc' ] > 0 ) { ?>
			FSPoster.loadModal( 'share_feeds', { 'post_id': '<?php echo (int) $fsp_params[ 'post_id' ]; ?>' }, true );
			<?php } ?>

			<?php if ( get_post_status() != 'publish' ) { ?>
			if ( $( '.block-editor__container' ).length )
			{
				let alreadyShared = false;

				doc.on( 'click', '.editor-post-publish-button', function () {
					setTimeout( function () {
						let isChecked = $( '#fspMetaboxShare' ).is( ':checked' );
						let isSaved = window.location.href.match( /post\.php\?post=([0-9]+)/ );

						if ( ! alreadyShared && isChecked && isSaved && isSaved[ 1 ] )
						{
							FSPoster.ajax( 'check_post_is_published', { 'id': isSaved[ 1 ] }, function ( result ) {
								if ( result[ 'post_status' ] === '2' )
								{
									FSPoster.loadModal( 'share_feeds', {
										'post_id': isSaved[ 1 ],
										'dont_reload': '1'
									}, true );

									alreadyShared = true;
								}
							}, true, null );
						}
					}, 2000 );
				} );
			}
			<?php } ?>
		} );
	} )( jQuery );
</script>
