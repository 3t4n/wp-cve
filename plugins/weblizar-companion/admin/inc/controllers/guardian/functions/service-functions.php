<?php 

defined( 'ABSPATH' ) or die();
require_once( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/helpers/wl-companion-helper.php' );

if ( class_exists( 'WP_Customize_Control' ) ) :
class guardian_Customizer_service_fields extends WP_Customize_Control {

	public function render_content() {
		?>
		<div class="wl_agm service_block">
			<label>
				<span class="customize-control-title"><?php esc_html_e( $this->label ,WL_COMPANION_DOMAIN); ?></span>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php esc_html_e( $this->description,WL_COMPANION_DOMAIN ); ?></span>
				<?php endif; ?>
			</label>
			
			<form id="wl-ext-form-service" method="post">
				<div id="input_fields_wrap-service">
					<?php 
					if ( ! empty ( get_theme_mod( 'guardian_service_data') ) )  {
						$name_arr = unserialize(get_theme_mod( 'guardian_service_data'));
						foreach ( $name_arr as $key => $value ) {
						?>
						<div class="wl-dynamic-fields">
							<div class="form-group">
								<label for="title" class="col-form-label wl-txt-label"><?php esc_html_e ( 'Service Title', WL_COMPANION_DOMAIN ); ?></label>
								<input type="text" class="form-control" id="service_title-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="service_title-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="Enter title" value="<?php if ( ! empty ( $value['service_name'] ) ) { esc_attr_e($value['service_name'],WL_COMPANION_DOMAIN); } ?>">
							</div>
							<div class="form-group">
								<label for="service_icon" class="col-form-label wl-txt-label"><?php esc_html_e( 'Service Icon', WL_COMPANION_DOMAIN ); ?></label>
								<input data-placement="bottomRight" id="service_icon-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="service_icon-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="form-control icp icp-auto-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?> service_icon" value="<?php if ( ! empty ( $value['service_icon'] ) ) { esc_attr_e($value['service_icon'],WL_COMPANION_DOMAIN); } ?>" type="text"/>
								<span class="input-group-addon">
									<?php if ( ! empty ( $value['service_icon'] ) ) { ?>
										<i class="<?php esc_attr_e($value['service_icon'],WL_COMPANION_DOMAIN); ?>"></i>
									<?php } ?>
								</span>
								<script type="text/javascript">
									jQuery(document).ready(function ($) {
										jQuery('#service_icon-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>').iconpicker({
												inline: true,
											});
									});
								</script>
							</div>
							<div class="form-group">
								<label for="link" class="col-form-label wl-txt-label"><?php esc_html_e ( 'Service Link', WL_COMPANION_DOMAIN ); ?></label>
								<input type="text" class="form-control" id="service_link-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="service_link-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="Enter Link" value="<?php if ( ! empty ( $value['service_link'] ) ) { esc_attr_e($value['service_link'],WL_COMPANION_DOMAIN); } ?>">
							</div>

							<div class="form-group">
								<label for="service_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e ( 'Service Description', WL_COMPANION_DOMAIN ); ?></label>
								<textarea class="form-control" rows="5" id="service_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="service_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="Description"><?php if ( ! empty ( $value['service_desc'] ) ) { echo esc_textarea($value['service_desc']); } ?></textarea>
							</div>
							<a href="#" class="btn btn-danger btn-sm remove_field"><?php esc_html_e( 'Remove', WL_COMPANION_DOMAIN ); ?></a>
						</div>
						<?php
						}
					}
					?>
				</div>
				<button class='btn btn-success add_field_button' id="add_field_button-service"><?php esc_html_e( 'Add Services', WL_COMPANION_DOMAIN ); ?></button></br>
				<button type="button" class="btn btn-success add_field_button wl-companion-submit-btn" id="wl-ext-submit-service"><?php esc_html_e( 'Save', WL_COMPANION_DOMAIN ); ?></button>
			</form>
		</div>
		<?php
	}
}
endif;
?>