<?php 

defined( 'ABSPATH' ) or die();
require_once( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/helpers/wl-companion-helper.php' );

if ( class_exists( 'WP_Customize_Control' ) ) :
class nineteen_Customizer_client_fields extends WP_Customize_Control {

	public function render_content() {
		?>
		<div class="wl_agm client_block">
			<label>
				<span class="customize-control-title"><?php esc_html_e( $this->label ,WL_COMPANION_DOMAIN); ?></span>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php esc_html_e( $this->description,WL_COMPANION_DOMAIN); ?></span>
				<?php endif; ?>
			</label>
			<form id="wl-ext-form-client" method="post">
				<div id="input_fields_wrap-client">
					<?php 
							if ( ! empty ( get_theme_mod( 'nineteen_client_data') ) )  {
								$name_arr = unserialize(get_theme_mod( 'nineteen_client_data'));
								foreach ( $name_arr as $key => $value ) {
								?>
									<div class="wl-dynamic-fields" id="wl-dynamic-fields-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>">
										<div class="form-group">
											<label for="client_name-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e( 'Client Name', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" class="form-control" id="client_name-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="client_name-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_attr_e ( 'Enter Name', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['client_name'] ) ) { esc_html_e( $value['client_name'],WL_COMPANION_DOMAIN ); } ?>">
										</div>
										<div class="form-group">
											<?php if ( ! empty ( $value['client_image'] ) ) { ?>
												<img class="wl-upload-img-tag" src="<?php echo esc_url( $value['client_image']); ?>"><br>
											<?php } ?>
											<label for="client_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e( 'Client Logo', WL_COMPANION_DOMAIN );?></label>
											<input type="text" name="client_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" id="client_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="form-control client_image" value="<?php if ( ! empty ( $value['client_image'] ) ) {  esc_attr_e( $value['client_image'] ,WL_COMPANION_DOMAIN); } ?>">
											<input type="button" name="upload-btn" class="button-secondary button upload_client_c" id="upload_client-<?php esc_attr_e( $i,WL_COMPANION_DOMAIN); ?>" value="Upload">
										</div>
										<a href="#" class="btn btn-danger btn-sm remove_field"><?php esc_html_e( 'Remove', WL_COMPANION_DOMAIN ); ?></a>
									</div>
								<?php
								}
							}
					?>
					
				</div>
				<button class='btn btn-success add_field_button' id="add_field_button-client"><?php esc_html_e( 'Add Client', WL_COMPANION_DOMAIN ); ?></button></br>
				<button type="button" class="btn btn-success add_field_button wl-companion-submit-btn" id="wl-ext-submit-client"><?php esc_html_e( 'Save', WL_COMPANION_DOMAIN ); ?></button>
			</form>
		</div>
		<?php
	}
}
endif;
?>