<?php
namespace GPLSCore\GPLS_PLUGIN_WGR;
use GPLSCore\GPLS_PLUGIN_WGR\GIF_Base;
?>

<div class="row gif-from-images-container w-100">
<?php $core->review_notice( '' ); ?>
	<div class="col-md-12 w-100 select-imgs-wrapper">
		<div class="wp-media-buttons mx-auto">
			<h3 class="mb-2"><?php esc_html_e( 'Select the images', 'wp-gif-editor' ); ?></h3>
			<button id="insert-media-button" class="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-open-gallery-btn' ); ?> button">
				<span class="wp-media-butons-icon"></span>
				<?php esc_html_e( 'Media Gallery', 'wp-gif-editor' ); ?>
			</button>
		</div>
	</div>
	<div class="col-md-12">
		<div class="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-gif-frames-sortable' ); ?> gif-frames-wrapper m-5 p-2">
		<div class="img-item-clone d-none position-relative align-top">
				<ul class="actions d-flex flex-row align-items-end">
					<li class="action d-flex grapper-handle frame-sort"><span class="dashicons dashicons-menu-alt3"></span></li>
					<li class="action d-flex frame-remove" data-id=""><span class="dashicons dashicons-no"></span></li>
				</ul>
				<img src="" alt="">
				<div class="delay-box d-flex flex-row align-items-center mt-4 position-absolute">
					<span style="font-size:13px;" class="frame-delay-label"><?php esc_html_e( 'Delay', 'wp-gif-editor' ); ?> <?php esc_html_e( ' ( Pro )' ); ?></span>
					<input disabled="disabled" type="number" class="frame-delay bg-light" value="0">
					<small class="ms-1"><?php esc_html_e( 'sec' ); ?></small>
				</div>
			</div>
		</div>
		<div class="gif-options">
			<ul>
				<li class="row align-items-center">
					<div class="col-sm-3 col-form-label">
						<label for="<?php echo esc_attr( $plugin_info['name'] . '-gif-speed' ); ?>">
							<?php esc_html_e( 'Animation speed [ in milliseconds ]', 'wp-gif-editor' ); ?>
							<span style="display: block; font-size:12px;"><?php echo esc_html( '[ 10 -> 0.1 second | 50 -> 0.5 second | 100 -> 1 second ] etc..' ); ?></span>
						</label>
					</div>
					<div class="col-sm-9">
						<input type="number" value="100" id="<?php echo esc_attr( $plugin_info['name'] . '-gif-speed' ); ?>" name="<?php echo esc_attr( $plugin_info['name'] . '-gif-speed' ); ?>">
					</div>
				</li>
				<li class="row">
					<div class="col-sm-3 col-form-label">
						<label for="<?php echo esc_attr( $plugin_info['name'] . '-gif-loop' ); ?>">
							<?php esc_html_e( 'GIF Loops [ 0 for infinite ] ( Pro )', 'wp-gif-editor' ); ?>
						</label>
					</div>
					<div class="col-sm-9">
						<input disabled="disabled" class="bg-light" type="number" value="0" id="<?php echo esc_attr( $plugin_info['name'] . '-gif-loop' ); ?>" name="<?php echo esc_attr( $plugin_info['name'] . '-gif-loop' ); ?>">
						<?php $core->pro_btn( '', 'Premium' ); ?>
					</div>
				</li>
				<li class="row">
					<div class="col-sm-3 col-form-label">
						<label for="<?php echo esc_attr( $plugin_info['name'] . '-gif-loop' ); ?>">
							<?php esc_html_e( 'GIF Dimension', 'wp-gif-editor' ); ?>
						</label>
					</div>
					<div class="col-sm-9">
						<select name="<?php echo esc_attr( $plugin_info['name'] . '-gif-dimension' ); ?>" class="<?php echo esc_attr( $plugin_info['classes_prefix'] . '-gif-dimension' ); ?>" id="<?php echo esc_attr( $plugin_info['name'] . '-gif-dimension' ); ?>">
							<option  value="first"><?php esc_html_e( 'Equal to first frame', 'wp-gif-editor' ); ?></option>
							<option disabled="disabled" class="bg-light" value="custom"><?php esc_html_e( 'Custom GIF Size ( Pro )', 'wp-gif-editor' ); ?></option>
						</select>
					</div>
				</li>
			</ul>
		</div>
		<!-- GIF Preview section -->
		<div class="gif-preview">
			<button class="button gif-preview-btn disabled" disabled="disabled"><?php esc_html_e( 'Preview GIF', 'wp-gif-editor' ); ?></button>
			<span class="spinner"></span>
		</div>
		<!-- GIF Result Section -->
		<div class="gif-result m-5 p-5 d-none">
			<div class="wrapper text-center">
				<img src="" alt="" class="border img-thubmnail gif-img">
			</div>
			<!-- GIF Save Options -->
			<div class="gif-save-options my-5">
				<h3><?php esc_html_e( 'Save Options', 'wp-gif-editor' ); ?></h3>
				<ul class="p-3">
					<li class="row my-3">
						<div class="col-md-3">
							<label for="<?php echo esc_attr( $plugin_info['name'] . '-gif-save-title' ); ?>">
								<?php esc_html_e( 'GIF Title', 'wp-gif-editor' ); ?>
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="gif-save-title regular-text" id="<?php echo esc_attr( $plugin_info['name'] . '-gif-save-title' ); ?>" name="<?php echo esc_attr( $plugin_info['name'] . '-gif-save-title' ); ?>" >
						</div>
					</li>
				</ul>
				<!-- GIF Save result Section -->
				<div class="gif-save">
					<button class="button gif-save-btn"><?php esc_html_e( 'Save GIF', 'wp-gif-editor' ); ?></button>
					<span class="spinner"></span>
				</div>
			</div>
		</div>
		<!-- Saved GIF Holder -->
		<div class="m-5 p-5 d-none gif-icon-box-container"></div>
	</div>
</div>
