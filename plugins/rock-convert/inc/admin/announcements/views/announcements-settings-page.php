<?php
/**
 * Announcements Settings Page
 *
 * @package Rock_Convert
 */

use Rock_Convert\Inc\Admin\Utils;

$ann_nonce = wp_create_nonce( 'announcements_nonce' );

$default = array(
	'activated'      => 0,
	'text'           => 'Frase de efeito aqui!',
	'btn'            => 'Saiba mais',
	'link'           => esc_url( get_bloginfo( 'url' ) ),
	'position'       => 'top',
	'visibility'     => 'post',
	'bg_color'       => '#f5f4ef',
	'text_color'     => '#1f1e1d',
	'btn_color'      => '#263473',
	'btn_text_color' => '#ffffff',
);

$settings = get_option( 'rock_convert_announcement_settings', $default );

$activated      = Utils::getArrayValue( $settings, 'activated' );
$text           = Utils::getArrayValue( $settings, 'text', null, $default['text'] );
$btn            = Utils::getArrayValue( $settings, 'btn', null, $default['btn'] );
$ann_link       = Utils::getArrayValue( $settings, 'link' );
$position       = Utils::getArrayValue( $settings, 'position', null, $default['position'] );
$visibility     = Utils::getArrayValue( $settings, 'visibility', null, $default['visibility'] );
$excluded_urls  = Utils::sanitize_array( $settings['urls'] );
$bg_color       = Utils::getArrayValue( $settings, 'bg_color', null, $default['bg_color'] );
$text_color     = Utils::getArrayValue( $settings, 'text_color', null, $default['text_color'] );
$btn_color      = Utils::getArrayValue( $settings, 'btn_color', null, $default['btn_color'] );
$btn_text_color = Utils::getArrayValue( $settings, 'btn_text_color', null, $default['btn_text_color'] );

$success_saved = isset( $_GET['success'] ) ? sanitize_text_field( wp_unslash( $_GET['success'] ) ) : null;// phpcs:ignore WordPress.Security.NonceVerification

?>
<?php if ( $success_saved ) { ?>
	<div class="notice notice-success is-dismissible">
		<p><?php esc_html_e( 'Sucesso', 'rock-convert' ); ?>!
			<strong style="color: #ca4a1f;">
				<?php esc_html_e( 'Lembre-se de limpar o cache', 'rock-convert' ); ?>
			</strong>
			<?php
				esc_html_e(
					'caso utilize algum plugin de
                    performance como W3 Total Cache, WP Optimizer, etc...',
					'rock-convert'
				);
			?>
			</p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text">Dismiss this notice.</span>
		</button>
	</div>
<?php } ?>

<div class="wrap rconvert_announcement_bar_page">
	<h2><?php esc_html_e( 'Barra de anúncios', 'rock-convert' ); ?></h2>

	<h4><?php esc_html_e( 'Configurações para a barra de anúncios', 'rock-convert' ); ?></h4>

	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<input type="hidden" name="action" value="<?php echo esc_attr( 'rock_convert_announcements_save_form' ); ?>">
		<input type="hidden" name="announcements_nonce" value="<?php echo esc_attr( $ann_nonce ); ?>"/>
		<?php require_once 'announcements-form-description.php'; ?>
		<?php require_once 'announcements-form-style.php'; ?>
		<?php require_once 'announcements-form-visibility.php'; ?>
	</form>
</div>

<div class="clearfix" style="display: block;clear: both;"></div>
