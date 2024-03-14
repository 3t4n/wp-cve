<?php 

defined( 'ABSPATH' ) or die();

if ( class_exists( 'WP_Customize_Control' ) ) :
class nineteen_Customizer_team_fields extends WP_Customize_Control {

	public function render_content() {
		?>
		<div class="wl_agm team_block">
			<label>
				<span class="customize-control-title"><?php esc_html_e( $this->label ,WL_COMPANION_DOMAIN); ?></span>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php esc_html_e( $this->description,WL_COMPANION_DOMAIN); ?></span>
				<?php endif; ?>
			</label>
			<form id="wl-ext-form-team" method="post">
				<div id="input_fields_wrap-team">
					<?php 
							if ( ! empty ( get_theme_mod( 'nineteen_team_data') ) )  {
								$name_arr = unserialize(get_theme_mod( 'nineteen_team_data'));
								foreach ( $name_arr as $key => $value ) {
								?>
									<div class="wl-dynamic-fields">
										<div class="form-group">
											<label for="title" class="col-form-label wl-txt-label"><?php esc_html_e ( 'Team Member Name', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" class="form-control" id="team_name-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="team_name-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e ( 'Enter Name', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['team_name'] ) ) { esc_attr_e($value['team_name'],WL_COMPANION_DOMAIN); } ?>" >
										</div>
										<div class="form-group">
											<label for="designation" class="col-form-label wl-txt-label"><?php esc_html_e( 'Team Member Designation', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" class="form-control" id="team_designation-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="team_designation-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e( 'Enter Designation', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['team_designation'] ) ) {  esc_attr_e($value['team_designation'],WL_COMPANION_DOMAIN); } ?>">
										</div>
										<div class="form-group">
											<label for="team_fb_link" class="col-form-label wl-txt-label"><?php esc_html_e( 'Button Link', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" class="form-control" id="team_fb_link-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="team_fb_link-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e ( 'https://example.com', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['fbk_link'] ) ) { esc_attr_e($value['fbk_link'],WL_COMPANION_DOMAIN); } ?>" >
										</div>
										<div class="form-group">
											<?php if ( ! empty ( $value['team_image'] ) ) { ?>
												<img class="wl-upload-img-tag" src="<?php echo esc_url($value['team_image']); ?>"><br>
											<?php }?>
											<label for="team_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e( 'Profile Picture', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" name="team_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" id="team_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="form-control team_image" value="<?php if ( ! empty ( $value['team_image'] ) ) { esc_attr_e($value['team_image'],WL_COMPANION_DOMAIN); } ?>" >
											<input type="button" name="upload-btn" class="button-secondary button upload_image_btn upload_team_c" id="upload_team-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" value="Upload">
										</div>
										<a href="#" class="btn btn-danger btn-sm remove_field"><?php esc_html_e( 'Remove', WL_COMPANION_DOMAIN ); ?></a>
									</div>
								<?php
								}
							}
							?>
						</div>
				<button class='btn btn-success add_field_button' id="add_field_button-team"><?php esc_html_e( 'Add Team Member', WL_COMPANION_DOMAIN ); ?></button></br>
				<button type="button" class="btn btn-success add_field_button wl-companion-submit-btn" id="wl-ext-submit-team"><?php esc_html_e( 'Save', WL_COMPANION_DOMAIN ); ?></button>
			</form>
		</div>
		<?php
	}

}
endif;

?>