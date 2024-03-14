<?php 

defined( 'ABSPATH' ) or die();

if ( class_exists( 'WP_Customize_Control' ) ) :
class guardian_Customizer_slider_fields extends WP_Customize_Control {

	public function render_content() {
		?>
		<div class="wl_agm slider_block">
			<label>
				<span class="customize-control-title"><?php esc_html_e( $this->label,WL_COMPANION_DOMAIN ); ?></span>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php esc_html_e( $this->description ,WL_COMPANION_DOMAIN); ?></span>
				<?php endif; ?>
			</label>
			<form id="wl-ext-form-slider" method="post">
				<div id="input_fields_wrap-slider">
					<?php 
					if ( ! empty ( get_theme_mod( 'guardian_slider_data') ) )  {
						$name_arr = unserialize(get_theme_mod( 'guardian_slider_data'));
						foreach ( $name_arr as $key => $value ) {
						?>
							<div class="wl-dynamic-fields">
								<div class="form-group">
									<label for="title" class="col-form-label wl-txt-label"><?php esc_html_e ( 'Title', WL_COMPANION_DOMAIN ); ?></label>
									<input type="text" class="form-control" id="slider_name-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="slider_name-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e ( 'Enter Name', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['slider_name'] ) ) { esc_attr_e($value['slider_name'],WL_COMPANION_DOMAIN); } ?>" >
								</div>
								<div class="form-group">
									<label for="slider_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e ( 'Description', WL_COMPANION_DOMAIN ); ?></label>
									<textarea class="form-control" rows="5" id="slider_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="slider_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e( 'Description', WL_COMPANION_DOMAIN ); ?>"><?php if ( ! empty ( $value['slider_desc'] ) ) { echo esc_textarea($value['slider_desc']); } ?></textarea>
								</div>
								<div class="form-group">
									<?php if ( ! empty ( $value['slider_image'] ) ) { ?>
										<img class="wl-upload-img-tag" src="<?php echo esc_url($value['slider_image']); ?>"><br>
									<?php }?>
									<label for="slider_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e ( 'Slide Image', WL_COMPANION_DOMAIN ); ?></label>
									<input type="text" name="slider_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" id="slider_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="form-control slider_image" value="<?php if ( ! empty ( $value['slider_image'] ) ) { esc_attr_e($value['slider_image'],WL_COMPANION_DOMAIN); } ?>" >
									<input type="button" name="upload-btn" class="button-secondary button upload_image_btn upload_slider_c" id="upload_slider-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" value="Upload">
								</div>
								<div class="form-group">
									<label for="slider_btn_txt" class="col-form-label wl-txt-label"><?php esc_html_e ( 'Button text', WL_COMPANION_DOMAIN ); ?></label>
									<input type="text" class="form-control" id="slider_btn_txt-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="slider_btn_txt-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e ( 'View More', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['slider_text'] ) ) { esc_attr_e($value['slider_text'],WL_COMPANION_DOMAIN); } ?>" >
								</div>
								<div class="form-group">
									<label for="slider_btn_link" class="col-form-label wl-txt-label"><?php esc_html_e ( 'Button Link', WL_COMPANION_DOMAIN ); ?></label>
									<input type="text" class="form-control" id="slider_btn_link-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="slider_btn_link-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e ( 'https://example.com', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['slider_link'] ) ) { esc_attr_e($value['slider_link'],WL_COMPANION_DOMAIN); } ?>" >
								</div>
								<a href="#" class="btn btn-danger btn-sm remove_field"><?php esc_html_e( 'Remove', WL_COMPANION_DOMAIN ); ?></a>
							</div>
						<?php
						}
					}
					?>
				</div>
				<button class='btn btn-success add_field_button' id="add_field_button-slider"><?php esc_html_e( 'Add Slide', WL_COMPANION_DOMAIN ); ?></button></br>
				<button type="button" class="btn btn-success add_field_button wl-companion-submit-btn" id="wl-ext-submit-slider"><?php esc_html_e( 'Save', WL_COMPANION_DOMAIN ); ?></button>
			</form>
		</div>
		<?php
	}
}
endif;

?>