<?php
defined( 'ABSPATH' ) || exit;

use GPLSCore\GPLS_PLUGIN_AVFSTW\AvifSupport;

$core          = $args['core'];
$plugin_info   = $args['plugin_info'];
$template_page = $args['template_page'];
$settings      = AvifSupport::get_settings();
?>

<div class="container-fluid">
	<div class="container">
		<?php
		if ( ! $template_page::is_type_supported( 'avif' ) ) :
			?>
			<div class="notice notice-error avif-reqs py-2 px-3">
				<span><?php printf( esc_html__( 'AVIF requires %1$s compiled with AVIF support OR %2$s at least. please contact your hosting support regarding that.', 'avif-support' ), '<strong>GD </strong>', '<strong>ImageMagick V 7.0.25</strong>' ); ?></span>
			</div>
			<div class="notice notice-error avif-reqs py-2 px-3">
				<span><?php esc_html_e( 'AVIF image will be uploaded, but sub-sizes will not be generated', 'avif-support' ); ?></span>
			</div>
		<?php endif; ?>
		<ul class="list-group">
			<!-- PHP Version -->
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6 border-end">
						<span class="item-key"><?php esc_html_e( 'PHP Version', 'avif-support' ); ?></span>
					</div>
					<div class="col-md-6 text-end">
						<span class="item-value">
							<?php echo esc_html( phpversion() ); ?>
						</span>
					</div>
				</div>
			</li>
			<!-- GD Version -->
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6 border-end">
						<span class="item-key"><?php esc_html_e( 'GD Version', 'avif-support' ); ?></span>
					</div>
					<div class="col-md-6 text-end">
						<span class="item-value text-w-bold">
							<?php
							$is_gd_enabled = $template_page::is_gd_enabled();
							if ( ! $is_gd_enabled ) {
								$template_page::install_and_version_icon( 'red' );
							} else {
								$template_page::install_and_version_icon( $template_page::is_type_supported( 'avif', 'gd' ) ? 'green' : 'red', $template_page->get_gd_version() );
							}
							?>
						</span>
					</div>
				</div>
			</li>
			<!-- Imagick Version -->
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6 border-end">
						<span class="item-key"><?php esc_html_e( 'ImageMagick Version', 'avif-support' ); ?></span>
					</div>
					<div class="col-md-6 text-end">
						<span class="item-value text-w-bold">
							<?php
							$is_imagick_enabled = $template_page::is_imagick_enabled();
							if ( $is_imagick_enabled && $template_page->get_imagick_version() ) {
								$template_page::install_and_version_icon( $template_page::is_type_supported( 'avif', 'imagick' ) ? 'green' : 'red', $template_page->get_imagick_version() );

							} else {
								$template_page::install_and_version_icon( 'red' );
							}
							?>
						</span>
					</div>
				</div>
			</li>
			<!-- AVIF Support -->
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-6 border-end">
						<span class="item-key"><?php esc_html_e( 'AVIF Support', 'avif-support' ); ?></span>
					</div>
					<div class="col-md-6 text-end">
						<span class="item-value">
							<?php
							$is_avif_supported = $template_page::is_type_supported( 'avif' );
							$template_page::install_and_version_icon( $is_avif_supported ? 'green' : 'red', ( ! $is_avif_supported ? 'Not ' : '' ) . 'Supported' );
							?>
						</span>
					</div>
				</div>
			</li>
		</ul>
	</div>

	<div class="avif-settings bg-white p-5 my-5 position-relative">
		<?php $template_page::loader_html( $plugin_info['prefix'] ); ?>
		<h5 class="mb-5 p-3 bg-light"><?php esc_html_e( 'General settings', 'avif-support' ); ?><span><?php $core->new_keyword( 'New', false ); ?></span></h5>
		<!-- Quality -->
		<div class="mb-3">
			<label for="default-avif-lib" class="form-label"><?php esc_html_e( 'Default quality', 'avif-support' ); ?></label>
			<input type="number" class="form-control avif-quality" value="<?php echo esc_attr( absint( $settings['quality'] ) ); ?>">
			<small><?php echo esc_html( '1-100' ); ?></small>
			<small><?php esc_html_e( 'select the default quality for processing and creating sub-sizes of AVIF images. default is 82' ); ?></small>
		</div>
		<!-- Speed -->
		<div class="mb-3">
			<label for="default-avif-lib" class="form-label"><?php esc_html_e( 'Speed ( GD only )', 'avif-support' ); ?></label>
			<input type="number" class="form-control avif-speed" value="<?php echo esc_attr( absint( $settings['speed'] ) ); ?>">
			<small><?php esc_html_e( '( 0: slow - smaller image ) - ( 10: fast - larger image ). default is 6' ); ?></small>
		</div>
		<button class=" mt-3 button button-primary <?php echo esc_attr( $plugin_info['prefix'] . '-save-settings' ); ?>"><?php esc_html_e( 'Save', 'avif-support' ); ?></button>
	</div>

	<div class="recommended-plugins" style="margin-top:80px;">
		<?php $core->recommended_plugins(); ?>
	</div>

	<div style="margin-top:100px;">
		<?php $core->plugins_sidebar(); ?>
	</div>
</div>
