<?php 

defined( 'ABSPATH' ) or die();

if ( class_exists( 'WP_Customize_Control' ) ) :
class travelogged_Customizer_destination_fields_new extends WP_Customize_Control {

	public function render_content() {
		?>
		<div class="wl_agm destination_block">
			<label>
				<span class="customize-control-title"><?php esc_html_e( $this->label ,WL_COMPANION_DOMAIN); ?></span>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php esc_html_e( $this->description,WL_COMPANION_DOMAIN ); ?></span>
				<?php endif; ?>
			</label>
			<form id="wl-ext-form-destination" method="post">
				<div id="input_fields_wrap-destination">
					<?php 
							if ( ! empty ( get_theme_mod( 'travelogged_destination_data') ) )  {
								$name_arr = unserialize(get_theme_mod( 'travelogged_destination_data'));
								foreach ( $name_arr as $key => $value ) {
								?>
									<div class="wl-dynamic-fields">
										<div class="form-group">
											<label for="title" class="col-form-label wl-txt-label"><?php esc_html_e ( 'Destiantion Name', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" class="form-control" id="desti_name-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="desti_name-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e( 'Enter Name', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['desti_name'] ) ) {  esc_attr_e($value['desti_name'],WL_COMPANION_DOMAIN); } ?>" >
										</div>
										<div class="form-group">
											<label for="desti_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e( 'Destination Description', WL_COMPANION_DOMAIN ); ?></label>
											<textarea class="form-control" rows="5" id="desti_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="desti_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="Description"><?php if ( ! empty ( $value['desti_desc'] ) ) { echo esc_textarea($value['desti_desc']); } ?></textarea>
										</div>
										<div class="form-group">
											<?php if ( ! empty ( $value['desti_image'] ) ) { ?>
												<img class="wl-upload-img-tag" src="<?php echo esc_url($value['desti_image']); ?>"><br>
											<?php }?>
											<label for="desti_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e( 'Destination Picture', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" name="desti_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" id="desti_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="form-control desti_image" value="<?php if ( ! empty ( $value['desti_image'] ) ) { esc_attr_e($value['desti_image'],WL_COMPANION_DOMAIN); } ?>" >
											<input type="button" name="upload-btn" class="button-secondary button upload_image_btn upload_desti_c" id="upload_desti-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" value="Upload">
										</div>
										<div class="form-group">
											<label for="ratings" class="col-form-label wl-txt-label"><?php esc_html_e( 'Ratings', WL_COMPANION_DOMAIN ); ?></label>
											<select class="form-control" id="ratings-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="ratings-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" required>
												<option value="1" <?php selected( $value['ratings'], '1', 'selected' ); ?>>1</option>
												<option value="2" <?php selected( $value['ratings'], '2', 'selected' ); ?>>2</option>
												<option value="3" <?php selected( $value['ratings'], '3', 'selected' ); ?>>3</option>
												<option value="4" <?php selected( $value['ratings'], '4', 'selected' ); ?>>4</option>
												<option value="5" <?php selected( $value['ratings'], '5', 'selected' ); ?>>5</option>
											</select>
										</div>
										<div class="form-group">
											<label for="desti_duration" class="col-form-label wl-txt-label"><?php esc_html_e( 'Package Duration', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" class="form-control" id="desti_duration-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="desti_duration-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e( '4 Days-5 Nights', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['desti_duration'] ) ) { esc_attr_e($value['desti_duration'],WL_COMPANION_DOMAIN); } ?>" >
										</div>
										<div class="form-group">
											<label for="btn_text" class="col-form-label wl-txt-label"><?php esc_html_e( 'Button text', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" class="form-control" id="btn_text-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="btn_text-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e ( 'https://example.com', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['btn_text'] ) ) { esc_attr_e($value['btn_text'],WL_COMPANION_DOMAIN); } ?>" >
										</div>
										<div class="form-group">
											<label for="btn_link" class="col-form-label wl-txt-label"><?php esc_html_e( 'Button Link', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" class="form-control" id="btn_link-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="btn_link-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e( 'https://example.com', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['btn_link'] ) ) { esc_attr_e($value['btn_link'],WL_COMPANION_DOMAIN); } ?>" >
										</div>
										<a href="#" class="btn btn-danger btn-sm remove_field"><?php esc_html_e( 'Remove', WL_COMPANION_DOMAIN ); ?></a>
									</div>
								<?php
								}
							}
					?>
				</div>
				<button class='btn btn-success add_field_button' id="add_field_button-destination"><?php esc_html_e( 'Add Destination', WL_COMPANION_DOMAIN ); ?></button></br>
				<button type="button" class="btn btn-success add_field_button wl-companion-submit-btn" id="wl-ext-submit-destination"><?php esc_html_e( 'Save', WL_COMPANION_DOMAIN ); ?></button>
			</form>
		</div>
	<?php
	}
}
endif;

?>