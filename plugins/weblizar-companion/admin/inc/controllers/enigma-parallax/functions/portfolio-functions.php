<?php 

defined( 'ABSPATH' ) or die();

if ( class_exists( 'WP_Customize_Control' ) ) :
class enigma_Customizer_portfolio_fields extends WP_Customize_Control {

	public function render_content() {
		?>
		<div class="wl_agm portfolio_block">
			<label>
				<span class="customize-control-title"><?php esc_html_e( $this->label,WL_COMPANION_DOMAIN ); ?></span>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php esc_html_e( $this->description,WL_COMPANION_DOMAIN ); ?></span>
				<?php endif; ?>
			</label>
			<form id="wl-ext-form-portfolio" method="post">
				<div id="input_fields_wrap-portfolio">
					<?php 
							if ( ! empty ( get_theme_mod( 'enigma_portfolio_data') ) )  {
								$name_arr = unserialize(get_theme_mod( 'enigma_portfolio_data'));
								foreach ( $name_arr as $key => $value ) {
								?>
									<div class="wl-dynamic-fields">
										<div class="form-group">
											<label for="portfolio_name-<?php  esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e( 'Portfolio Name', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" class="form-control" id="portfolio_name-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="portfolio_name-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" placeholder="<?php esc_html_e( 'Enter Name', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['portfolio_name'] ) ) { esc_attr_e($value['portfolio_name'],WL_COMPANION_DOMAIN); } ?>">
										</div>
										<div class="form-group">
											<?php if ( ! empty ( $value['portfolio_image'] ) ) { ?>
												<img class="wl-upload-img-tag" src="<?php echo esc_url($value['portfolio_image']); ?>"><br>
											<?php }?>
											<label for="portfolio_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="col-form-label wl-txt-label"><?php esc_html_e( 'Portfolio Image', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" name="portfolio_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" id="portfolio_image-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" class="form-control portfolio_image" value="<?php if ( ! empty ( $value['portfolio_image'] ) ) { esc_attr_e($value['portfolio_image'],WL_COMPANION_DOMAIN); } ?>">
											<input type="button" name="upload-btn" class="button-secondary button upload_image_btn upload_portfolio_c" id="upload_portfolio-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" value="Upload">
										</div>
										<div class="form-group">
											<label for="portfolio_link" class="col-form-label wl-txt-label"><?php esc_html_e( 'Link', WL_COMPANION_DOMAIN ); ?></label>
											<input type="text" class="form-control" id="portfolio_link-<?php esc_attr_e($key,WL_COMPANION_DOMAIN); ?>" name="portfolio_link-0" placeholder="<?php esc_html_e( 'View', WL_COMPANION_DOMAIN ); ?>" value="<?php if ( ! empty ( $value['portfolio_link'] ) ) { esc_attr_e($value['portfolio_link']); } ?>">
										</div>
										<a href="#" class="btn btn-danger btn-sm remove_field"><?php esc_html_e( 'Remove', WL_COMPANION_DOMAIN ); ?></a>
									</div>
								<?php
								}
							}
					?>
				</div>
				<button class='btn btn-success add_field_button' id="add_field_button-portfolio"><?php esc_html_e( 'Add Portfolio', WL_COMPANION_DOMAIN ); ?></button></br>
				<button type="button" class="btn btn-success add_field_button wl-companion-submit-btn" id="wl-ext-submit-portfolio"><?php esc_html_e( 'Save', WL_COMPANION_DOMAIN ); ?></button>
			</form>
		</div>
		<?php
	}
}
endif;

?>