<?php
defined( 'ABSPATH' ) or die();
require_once( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/helpers/wl-companion-helper.php' );

$theme_name      = wl_companion_helper::wl_get_theme_name();
$free_theme_data = get_option( 'free_theme_data' );

if ( empty ( $free_theme_data ) ) {
	$class = 'disabled';
} else {
	$class = '';
} ?>
	<div class="enigma-import-export">
		<div class="jumbotron">
			<h3><?php esc_html_e( 'Import Your Free Version Theme Data To Pro Version Theme.', WL_COMPANION_DOMAIN ); ?></h3>
			<p class="import_caption">
				<?php esc_html_e( 'Just click the below button to import data. If your free version theme data detected then it will be imported to pro version.', WL_COMPANION_DOMAIN ); ?>
			</p>
			<p>
				<form action="" method="post" accept-charset="utf-8">
					<textarea name="result_data_export" rows="5" style="width: 99%;"></textarea>
					<input class="btn btn-import-enigma" type="submit" name="import_submit" value="Import Data">
				</form>
			</p>
		</div>
	</div>
<?php
if ( isset( $_REQUEST['import_submit'] ) ) {
	$data   = sanitize_text_field( $_POST['result_data_export'] );
	$result = wl_companion_helper::wl_get_import_data( $data );
	if ( $result == true ) {
		echo wp_kses_post( '<p class="import_successfully">'.esc_html__( "Your Free Version Theme Data is imported successfully into your Pro Version Theme.!", WL_COMPANION_DOMAIN ).'</p>' );
	} else {
		echo wp_kses_post( '<p class="import_failed">'.esc_html__( "Your Free Version Theme Data is not imported successfully into your Pro Version Theme.!", WL_COMPANION_DOMAIN ).'</p>' );
	}
}