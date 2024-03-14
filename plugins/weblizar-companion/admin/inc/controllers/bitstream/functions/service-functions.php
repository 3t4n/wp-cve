<?php

defined('ABSPATH') or die();
require_once(WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/helpers/wl-companion-helper.php');

if (class_exists('WP_Customize_Control')) :
class bitstream_Customizer_service_fields extends WP_Customize_Control
{
    public function render_content()
    {
        ?>
		<div class="wl_agm service_block">
			<label>
				<span class="customize-control-title"><?php  esc_html_e($this->label,WL_COMPANION_DOMAIN); ?></span>
				<?php if (! empty($this->description)) : ?>
				<span class="description customize-control-description"><?php esc_html_e($this->description,WL_COMPANION_DOMAIN); ?></span>
				<?php endif; ?>
			</label>
			
			<form id="wl-ext-form-service" method="post">
				<div id="input_fields_wrap-service">
					<?php
                    if (! empty(get_theme_mod('bitstream_service_data'))) {
                        $name_arr = unserialize(get_theme_mod('bitstream_service_data'));
                        foreach ($name_arr as $key => $value) {
                            ?>
						<div class="wl-dynamic-fields">
							<div class="form-group">
								<label for="title" class="col-form-label wl-txt-label"><?php esc_html_e('Service Title', WL_COMPANION_DOMAIN); ?></label>
								<input type="text" class="form-control" id="service_title-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="service_title-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="Enter title" value="<?php if (! empty($value['service_name'])) {
                                esc_attr($value['service_name'],WL_COMPANION_DOMAIN);
                            } ?>">
							</div>
							<div class="form-group">
								<?php if (! empty($value['service_image'])) { ?>
									<img class="wl-upload-img-tag" src="<?php esc_attr_e($value['service_image'],WL_COMPANION_DOMAIN); ?>"><br>
								<?php } ?>
								<label for="service_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e('Slide Image', WL_COMPANION_DOMAIN); ?></label>
								<input type="text" name="service_image-<?php  esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" id="service_image-<?php  esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="form-control service_image" value="<?php if (! empty($value['service_image'])) {
                                echo esc_url($value['service_image']);
                            } ?>" >
								<input type="button" name="upload-btn" class="button-secondary button upload_image_btn upload_service_c" id="upload_service-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" value="Upload">
							</div>
							<div class="form-group">
								<label for="service_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e('Service Description', WL_COMPANION_DOMAIN); ?></label>
								<textarea class="form-control" rows="5" id="service_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="service_desc-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="Description"><?php if (! empty($value['service_desc'])) {
                                echo esc_textarea($value['service_desc']);
                            } ?></textarea>
							</div>
							<a href="#" class="btn btn-danger btn-sm remove_field"><?php esc_html_e('Remove', WL_COMPANION_DOMAIN); ?></a>
						</div>
						<?php
                        }
                    } ?>
				</div>
				<button class='btn btn-success add_field_button' id="add_field_button-service"><?php esc_html_e('Add Services', WL_COMPANION_DOMAIN); ?></button></br>
				<button type="button" class="btn btn-success add_field_button wl-companion-submit-btn" id="wl-ext-submit-service"><?php esc_html_e('Save', WL_COMPANION_DOMAIN); ?></button>
			</form>
		</div>
		<?php
    }
}
endif;
?>