<?php
	/**
	 * Add New
	 * Add new cursor to the page
	 * php version 7.2
	 *
	 * @category   Plugin
	 * @package    Wp_Custom_Cursors
	 * @subpackage Wp_Custom_Cursors/includes
	 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
	 * @license    GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
	 * @link       https://hamidrezasepehr.com/
	 * @since      2.1.0
	 */

	// Form initialization.
	$cursor_type_value            = 'shape';
	$cursor_shape_value           = 1;
	$default_cursor_value         = 1;
	$color_value                  = '#000000';
	$width_value                  = 30;
	$blending_mode_value          = 'normal';
	$hide_tablet_value            = 'on';
	$hide_mobile_value            = 'on';
	$hide_admin_value             = 'on';
	$activate_on_value            = 0;
	$selector_type_value          = null;
	$selector_data_value          = null;
	$hover_trigger_link_value     = 1;
	$hover_trigger_button_value   = 1;
	$hover_trigger_custom_value   = 0;
	$hover_trigger_selector_value = '';
	$hover_cursor_type_value      = 'default';
	$hover_cursor_value           = 1;
	$hover_cursor_text            = 'View';
	$hover_cursor_icon            = esc_url( plugins_url( '../img/icons/hover-cursor-icon.svg', __FILE__ ) );
	$hover_bg_color_value         = '#ff3c38';
	$hover_cursor_width_value     = 100;

	// Edit cursor.
if ( isset( $_GET['edit_row'] ) ) {
	$edit_row = intval( sanitize_text_field( wp_unslash( $_GET['edit_row'] ) ) );
	if ( check_admin_referer( 'edit-added-cursor' . $edit_row, 'wpcc_edit_nonce' ) ) {
		global $wpdb;
		$tablename = $wpdb->prefix . 'added_cursors';
		$query     = $wpdb->prepare( 'SELECT * from %i WHERE cursor_id = %d', array( $tablename, $edit_row ) );
		$cursor    = $wpdb->get_row( $query );
		if ( $cursor ) {
			$cursor_type_value    = $cursor->cursor_type;
			$cursor_shape_value   = $cursor->cursor_shape;
			$default_cursor_value = $cursor->default_cursor;
			$color_value          = $cursor->color;
			$width_value          = $cursor->width;
			$blending_mode_value  = $cursor->blending_mode;
			$hide_tablet_value    = $cursor->hide_tablet;
			$hide_mobile_value    = $cursor->hide_mobile;
			$hide_admin_value     = $cursor->hide_admin;
			$activate_on_value    = $cursor->activate_on;
			$selector_type_value  = $cursor->selector_type;
			$selector_data_value  = $cursor->selector_data;
		} else {
			unset( $_GET['edit_row'] ); }
	}
}
?>
	<div class="mt-3">
		<div class="row">
			<div class="col-md-8">
				<div class="card bg-light rounded-3 p-0">
					<div class="card-body p-0">
						<!-- Form -->
						<form action="#" method="post" id="add_new_form">
							<!-- Step 1: Select Cursor -->
							<fieldset>
								<legend class="pb-2 mb-0 pt-3 px-4">
									<div class="d-flex align-items-center">
										<i class="ri-cursor-fill ri-lg"></i>
										<div class="ms-2">
											<div class="lead fw-normal"><?php echo esc_html__( 'Cursor Type', 'wpcustom-cursors' ); ?></div>
											<div class="title-normal text-muted mt-2"><?php echo esc_html__( 'Select a pre-made cursor or', 'wpcustom-cursors' ); ?> <a href="<?php menu_page_url( 'wpcc_cursor_maker', true ); ?>" class="text-decoration-none"><?php echo esc_html__( 'create', 'wpcustom-cursors' ); ?></a> <?php echo esc_html__( 'your custom one.', 'wpcustom-cursors' ); ?></div>
										</div>
									</div>
								</legend>
								<!-- Progress Bar -->
								<div class="progressbar">
									<div class="progress-complete"></div>
								</div>

								<!-- Cursors list-->
								<div class="px-4">
									<div class="cursors-list" style="margin-left: -2px; margin-right: -2px;">
										<!-- Cursor 1 -->
										<input type="radio" class="btn-check" id="shape-1" autocomplete="off" name="cursor_shape" value="1" <?php checked( $cursor_shape_value, '1' ); ?>>
										<label for="shape-1"><img src="<?php echo esc_url( plugins_url( '../img/cursors/1.svg', __FILE__ ) ); ?>" alt="<?php echo esc_html__( 'Cursor 1', 'wpcustom-cursors' ); ?>" class="shape-img" /></label>
										<!-- End Cursor 1 -->

										<!-- Cursor 2 -->
										<input type="radio" class="btn-check" id="shape-2" autocomplete="off" name="cursor_shape" value="2" <?php checked( $cursor_shape_value, '2' ); ?>>
										<label for="shape-2"><img src="<?php echo esc_url( plugins_url( '../img/cursors/2.svg', __FILE__ ) ); ?>" alt="<?php echo esc_html__( 'Cursor 2', 'wpcustom-cursors' ); ?>" class="shape-img" /></label>
										<!-- End Cursor 2 -->

										<!-- Cursor 3 -->
										<input type="radio" class="btn-check" id="shape-3" autocomplete="off" name="cursor_shape" value="3" <?php checked( $cursor_shape_value, '3' ); ?>>
										<label for="shape-3"><img src="<?php echo esc_url( plugins_url( '../img/cursors/3.svg', __FILE__ ) ); ?>" alt="<?php echo esc_html__( 'Cursor 3', 'wpcustom-cursors' ); ?>" class="shape-img" /></label>
										<!-- End Cursor 3 -->

										<!-- Cursor 4 -->
										<input type="radio" class="btn-check" id="shape-4" autocomplete="off" name="cursor_shape" value="4" <?php checked( $cursor_shape_value, '4' ); ?>>
										<label for="shape-4"><img src="<?php echo esc_url( plugins_url( '../img/cursors/4.svg', __FILE__ ) ); ?>" alt="<?php echo esc_html__( 'Cursor 4', 'wpcustom-cursors' ); ?>" class="shape-img" /></label>
										<!-- End Cursor 4 -->

										<!-- Cursor 5 -->
										<input type="radio" class="btn-check" id="shape-5" autocomplete="off" name="cursor_shape" value="5" <?php checked( $cursor_shape_value, '5' ); ?>>
										<label for="shape-5"><img src="<?php echo esc_url( plugins_url( '../img/cursors/5.svg', __FILE__ ) ); ?>" alt="<?php echo esc_html__( 'Cursor 5', 'wpcustom-cursors' ); ?>" class="shape-img" /></label>
										<!-- End Cursor 5 -->

										<!-- Cursor 6 -->
										<input type="radio" class="btn-check" id="shape-6" autocomplete="off" name="cursor_shape" value="6" <?php checked( $cursor_shape_value, '6' ); ?>>
										<label for="shape-6"><img src="<?php echo esc_url( plugins_url( '../img/cursors/6.svg', __FILE__ ) ); ?>" alt="<?php echo esc_html__( 'Cursor 6', 'wpcustom-cursors' ); ?>" class="shape-img" /></label>
										<!-- End Cursor 6 -->

										<!-- Cursor 7 -->
										<input type="radio" class="btn-check" id="shape-7" autocomplete="off" name="cursor_shape" value="7" <?php checked( $cursor_shape_value, '7' ); ?>>
										<label for="shape-7"><img src="<?php echo esc_url( plugins_url( '../img/cursors/7.svg', __FILE__ ) ); ?>" alt="<?php echo esc_html__( 'Cursor 7', 'wpcustom-cursors' ); ?>" class="shape-img" /></label>
										<!-- End Cursor 7 -->

										<!-- Cursor 8 -->
										<input type="radio" class="btn-check" id="shape-8" autocomplete="off" name="cursor_shape" value="8" <?php checked( $cursor_shape_value, '8' ); ?>>
										<label for="shape-8"><img src="<?php echo esc_url( plugins_url( '../img/cursors/8.svg', __FILE__ ) ); ?>" alt="<?php echo esc_html__( 'Cursor 8', 'wpcustom-cursors' ); ?>" class="shape-img" /></label>
										<!-- End Cursor 8 -->
										<?php
										global $wpdb;
										$tablename      = $wpdb->prefix . 'created_cursors';
										$prepared_query = $wpdb->prepare( 'SELECT * FROM %i', $tablename );
										$cursors        = $wpdb->get_results( $prepared_query );
										if ( $cursors ) {
											foreach ( $cursors as $cursor ) {
												$stripped    = stripslashes( $cursor->cursor_options );
												$decoded     = json_decode( $stripped, false );
												$cursor_type = ( 'text' === $cursor->cursor_type ) ? $decoded->normal_text_type : $cursor->cursor_type;
												?>
												<input type="radio" data-type="<?php echo esc_attr( $cursor_type ); ?>" data-id="<?php echo esc_attr( $cursor->cursor_id ); ?>" class="btn-check" id="created-shape-<?php echo esc_attr( $cursor->cursor_id ); ?>" autocomplete="off" name="cursor_shape" value="created-<?php echo esc_attr( $cursor->cursor_id ); ?>" <?php checked( $cursor_shape_value, 'created-' . $cursor->cursor_id ); ?>>
												<?php
												switch ( $cursor->cursor_type ) {
													case 'shape':
														?>
														<label for="created-shape-<?php echo esc_attr( $cursor->cursor_id ); ?>" class="created-cursor-label" style="--fe-width: <?php echo esc_attr( $decoded->fe_width ); ?>px; --fe-height: <?php echo esc_attr( $decoded->fe_height ); ?>px; --fe-color: <?php echo esc_attr( $decoded->fe_color ); ?>; --fe-radius: <?php echo esc_attr( $decoded->fe_radius ); ?>px; --fe-border: <?php echo esc_attr( $decoded->fe_border_width ); ?>px; --fe-border-color: <?php echo esc_attr( $decoded->fe_border_color ); ?>; --fe-blending: <?php echo esc_attr( $decoded->fe_blending ); ?>; --fe-zindex: <?php echo esc_attr( $decoded->fe_zindex ); ?>; --se-width: <?php echo esc_attr( $decoded->se_width ); ?>px; --se-height: <?php echo esc_attr( $decoded->se_height ); ?>px; --se-color: <?php echo esc_attr( $decoded->se_color ); ?>; --se-radius: <?php echo esc_attr( $decoded->se_radius ); ?>px; --se-border: <?php echo esc_attr( $decoded->se_border_width ); ?>px; --se-border-color: <?php echo esc_attr( $decoded->se_border_color ); ?>; --se-blending: <?php echo esc_attr( $decoded->se_blending ); ?>; --se-zindex: <?php echo esc_attr( $decoded->se_zindex ); ?>;"><div class="cursor-el1" ></div><div class="cursor-el2"></div>
															<div class="action-icons">
																<?php
																$bare_url     = menu_page_url( 'wpcc_cursor_maker', false );
																$id           = intval( $cursor->cursor_id );
																$base_url     = $bare_url . '&edit_row=' . $id;
																$complete_url = wp_nonce_url( $base_url, 'edit-created-cursor' . $id, 'wpcc_edit_created_nonce' );
																?>
																<a href="<?php echo esc_url( $complete_url ); ?>" title="<?php echo esc_html__( 'Edit Created Cursor', 'wpcustom-cursors' ); ?>" class="edit-icon"><i class="ri-pencil-line ri-lg"></i></a>
																<button type="submit" name="delete_created" title="<?php echo esc_html__( 'Delete Created Cursor', 'wpcustom-cursors' ); ?>" class="delete-icon" value="<?php echo esc_attr( $cursor->cursor_id ); ?>"><i class="ri-close-fill ri-lg"></i></button>
																<?php wp_nonce_field( 'wpcc_delete_created_cursor', 'wpcc_delete_created_nonce' ); ?>
															</div>
														</label>
														<?php
														break;
													case 'image':
														?>
														<label for="created-shape-<?php echo esc_attr( $cursor->cursor_id ); ?>" class="created-cursor-label image" style="--width: <?php echo esc_attr( $decoded->width ); ?>px; 
																							<?php
																							if ( $decoded->background != 'off' ) {
																								echo '--padding: ' . esc_attr( $decoded->padding ) . 'px';}
																							?>
														; --color: <?php echo esc_attr( $decoded->color ); ?>; --radius: <?php echo esc_attr( $decoded->radius ); ?>px; --blending: <?php echo esc_attr( $decoded->blending ); ?>;"><div class="img-wrapper"><img src="<?php echo esc_url( $decoded->image_url ); ?>" class="img-fluid" /></div>
															<div class="action-icons">
																<?php
																$bare_url     = menu_page_url( 'wpcc_cursor_maker', false );
																$id           = intval( $cursor->cursor_id );
																$base_url     = $bare_url . '&edit_row=' . $id;
																$complete_url = wp_nonce_url( $base_url, 'edit-created-cursor' . $id, 'wpcc_edit_created_nonce' );
																?>
																<a href="<?php echo esc_url( $complete_url ); ?>" title="<?php echo esc_html__( 'Edit Created Cursor', 'wpcustom-cursors' ); ?>" class="edit-icon"><i class="ri-pencil-line ri-lg"></i></a>
																<button type="submit" name="delete_created" title="<?php echo esc_html__( 'Delete Created Cursor', 'wpcustom-cursors' ); ?>" class="delete-icon" value="<?php echo esc_html( $cursor->cursor_id ); ?>"><i class="ri-close-fill ri-lg"></i></button>
																<?php wp_nonce_field( 'wpcc_delete_created_cursor', 'wpcc_delete_created_nonce' ); ?>
															</div>
														</label>
														<?php
														break;
													case 'text':
														if ( 'horizontal' === $decoded->normal_text_type ) {
															?>
															<label for="created-shape-<?php echo esc_attr( $cursor->cursor_id ); ?>" class="created-cursor-label horizontal" style="--bg-color: <?php echo esc_attr( $decoded->hr_bgcolor ); ?>; --hr-width: <?php echo esc_attr( $decoded->hr_width ); ?>px; --hr-transfom: <?php echo esc_attr( $decoded->hr_transform ); ?>; --hr-weight: <?php echo esc_attr( $decoded->hr_weight ); ?>; --hr-color: <?php echo esc_attr( $decoded->hr_color ); ?>; --hr-size: <?php echo esc_attr( $decoded->hr_size ); ?>px;--hr-spacing: <?php echo esc_attr( $decoded->hr_spacing ); ?>px; --hr-duration: <?php echo esc_attr( $decoded->hr_duration ); ?>ms; --hr-timing: <?php echo esc_attr( $decoded->hr_timing ); ?>; --hr-radius: <?php echo esc_attr( $decoded->hr_radius ); ?>px;--hr-padding: <?php echo esc_attr( $decoded->hr_padding ); ?>s; ">
																	<div class="hr-text"><?php echo esc_html( $decoded->hr_text ); ?></div>
																	<div class="action-icons">
																		<?php
																		$bare_url     = menu_page_url( 'wpcc_cursor_maker', false );
																		$id           = intval( $cursor->cursor_id );
																		$base_url     = $bare_url . '&edit_row=' . $id;
																		$complete_url = wp_nonce_url( $base_url, 'edit-created-cursor' . $id, 'wpcc_edit_created_nonce' );
																		?>
																		<a href="<?php echo esc_url( $complete_url ); ?>" title="<?php echo esc_html__( 'Edit Created Cursor', 'wpcustom-cursors' ); ?>" class="edit-icon"><i class="ri-pencil-line ri-lg"></i></a>
																		<button type="submit" name="delete_created" title="<?php echo esc_html__( 'Delete Created Cursor', 'wpcustom-cursors' ); ?>" class="delete-icon" value="<?php echo esc_html( $cursor->cursor_id ); ?>"><i class="ri-close-fill ri-lg"></i></button>
																		<?php wp_nonce_field( 'wpcc_delete_created_cursor', 'wpcc_delete_created_nonce' ); ?>
																	</div>
																</label>
															<?php
														} else {
															?>
															<label for="created-shape-<?php echo esc_attr( $cursor->cursor_id ); ?>" class="created-cursor-label text" style="--dot-fill: <?php echo esc_attr( $decoded->dot_color ); ?>; --text-width: <?php echo esc_attr( $decoded->width ); ?>px; --text-transfom: <?php echo esc_attr( $decoded->text_transform ); ?>; --font-weight: <?php echo esc_attr( $decoded->font_weight ); ?>; --text-color: <?php echo esc_attr( $decoded->text_color ); ?>; --font-size: <?php echo esc_attr( $decoded->font_size ); ?>px;--word-spacing: <?php echo esc_attr( $decoded->word_spacing ); ?>px;--animation-name: <?php echo esc_attr( $decoded->animation ); ?>;--animation-duration: <?php echo esc_attr( $decoded->animation_duration ); ?>s; --dot-width: <?php echo esc_attr( $decoded->dot_width ); ?>px;"><svg viewBox="0 0 500 500" id="svg_node"><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="textcircle" fill="none"></path><text dy="25" id="svg_text_cursor"><textPath xlink:href="#textcircle" id="textpath"><?php echo esc_html( $decoded->text ); ?></textPath></text><circle cx="250" cy="250" r="<?php echo esc_attr( $decoded->dot_width ); ?>" id="svg_circle_node"/></svg>
																<div class="action-icons">
																	<?php
																	$bare_url     = menu_page_url( 'wpcc_cursor_maker', false );
																	$id           = intval( $cursor->cursor_id );
																	$base_url     = $bare_url . '&edit_row=' . $id;
																	$complete_url = wp_nonce_url( $base_url, 'edit-created-cursor' . $id, 'wpcc_edit_created_nonce' );
																	?>
																	<a href="<?php echo esc_url( $complete_url ); ?>" title="<?php echo esc_html__( 'Edit Created Cursor', 'wpcustom-cursors' ); ?>" class="edit-icon"><i class="ri-pencil-line ri-lg"></i></a>
																	<button type="submit" name="delete_created" title="<?php echo esc_html__( 'Delete Created Cursor', 'wpcustom-cursors' ); ?>" class="delete-icon" value="<?php echo esc_attr( $cursor->cursor_id ); ?>"><i class="ri-close-fill ri-lg"></i></button>
																	<?php wp_nonce_field( 'wpcc_delete_created_cursor', 'wpcc_delete_created_nonce' ); ?>
																</div>
															</label>
															<?php
														}
														break;
												}
											}
										}
										?>
									</div>
									<input type="hidden" value="<?php echo esc_attr( $cursor_type_value ); ?>" id="cursor_type_input" name="cursor_type">
								</div>
							</fieldset>

							<!-- Step 2: Cursor Options -->
							<fieldset>
								<legend class="pb-2 mb-0 pt-3 px-4">
									<div class="d-flex align-items-center">
										<i class="ri-tools-line ri-lg"></i>
										<div class="ms-2">
											<div class="lead fw-normal"><?php echo esc_html__( 'Cursor Options', 'wpcustom-cursors' ); ?></div>
											<div class="title-normal text-muted"><?php echo esc_html__( 'Set the options for the cursor:', 'wpcustom-cursors' ); ?></div>
										</div>
									</div>
								</legend>
								<!-- Progress Bar -->
								<div class="progressbar">
									<div class="progress-complete"></div>
								</div>

								<div class="px-4">
									<!-- Show Default Cursor -->
									<label class="toggler-wrapper mt-2 style-4"> 
										<span class="toggler-label"><?php echo esc_html__( 'Show Default Cursor?', 'wpcustom-cursors' ); ?></span>
										<input type="checkbox" name="default_cursor" id="default_cursor" value="1" <?php checked( $default_cursor_value, '1' ); ?>>
										<div class="toggler-slider">
											<div class="toggler-knob"></div>
										</div>
									</label>

									<div class="row bg-white rounded-2 py-3 my-3" id="shape_cursor_options">
										<div class="col-md-6">
											<!-- Cursor Color -->
											<div class="title-normal mt-3">
												<?php echo esc_html__( 'Cursor Color:', 'wpcustom-cursors' ); ?>
											</div>
											<div class="color_select form-group mt-2">
												<label class="w-100">
													<input type='text' class="form-control basic wp-custom-cursor-color-picker" id="cursor_color" name="color" value="<?php echo esc_attr( $color_value ); ?>">
												</label>
											</div>
										</div>
										<div class="col-md-6">
											<!-- Cursor Size -->
											<label for="cursor_size_input" class="title-normal mt-3"><?php echo esc_html__( 'Cursor Size:', 'wpcustom-cursors' ); ?></label>
											<div class="d-flex align-items-center mt-2">
												<input type="range" class="form-range me-2" min="1" max="500" id="cursor_size_range" value="<?php echo esc_attr( $width_value ); ?>">
												<input type="number" min="1" max="500" id="cursor_size_input" class="number-input" name='width' value="<?php echo esc_attr( $width_value ); ?>">
												<span class="ms-2 small"><?php echo esc_html__( 'PX', 'wpcustom-cursors' ); ?></span>
											</div>
										</div>
										<div class="col-md-6">
											<!-- Blending Mode Select -->
											<div class="form-group blending-selector">
												<label for="blending_mode" class="title-normal mt-3"><?php echo esc_html__( 'Blending Mode:', 'wpcustom-cursors' ); ?></label>
												<select class="form-control mt-2" id="blending_mode" name='blending_mode'>
													<option value="normal" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'normal' );}
													?>
													><?php echo esc_html__( 'Normal', 'wpcustom-cursors' ); ?></option>
													<option value="multiply" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'multiply' );}
													?>
													><?php echo esc_html__( 'Multiply', 'wpcustom-cursors' ); ?></option>
													<option value="screen" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'screen' );}
													?>
													><?php echo esc_html__( 'Screen', 'wpcustom-cursors' ); ?></option>
													<option value="overlay" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'overlay' );}
													?>
													><?php echo esc_html__( 'Overlay', 'wpcustom-cursors' ); ?></option>
													<option value="darken" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'darken' );}
													?>
													><?php echo esc_html__( 'Darken', 'wpcustom-cursors' ); ?></option>
													<option value="lighten" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'lighten' );}
													?>
													><?php echo esc_html__( 'Lighten', 'wpcustom-cursors' ); ?></option>
													<option value="color-dodge" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'color-dodge' );}
													?>
													><?php echo esc_html__( 'Color Dodge', 'wpcustom-cursors' ); ?></option>
													<option value="color-burn" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'color-burn' );}
													?>
													><?php echo esc_html__( 'Color Burn', 'wpcustom-cursors' ); ?></option>
													<option value="hard-light" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'hard-light' );}
													?>
													><?php echo esc_html__( 'Hard Light', 'wpcustom-cursors' ); ?></option>
													<option value="soft-light" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'soft-light' );}
													?>
													><?php echo esc_html__( 'Soft Light', 'wpcustom-cursors' ); ?></option>
													<option value="difference" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'difference' );}
													?>
													><?php echo esc_html__( 'Difference', 'wpcustom-cursors' ); ?></option>
													<option value="exclusion" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'exclusion' );}
													?>
													><?php echo esc_html__( 'Exclusion', 'wpcustom-cursors' ); ?></option>
													<option value="hue" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'hue' );}
													?>
													><?php echo esc_html__( 'Hue', 'wpcustom-cursors' ); ?></option>
													<option value="saturation" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'saturation' );}
													?>
													><?php echo esc_html__( 'Saturation', 'wpcustom-cursors' ); ?></option>
													<option value="color" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'color' );}
													?>
													><?php echo esc_html__( 'Color', 'wpcustom-cursors' ); ?></option>
													<option value="luminosity" 
													<?php
													if ( isset( $blending_mode_value ) ) {
														selected( $blending_mode_value, 'luminosity' );}
													?>
													><?php echo esc_html__( 'Luminosity', 'wpcustom-cursors' ); ?></option>
												</select>
											</div>
										</div>
									</div>

									<!-- Hide Custom Cursor On Tablet -->
									<label class="toggler-wrapper mt-3 style-4"> 
										<span class="toggler-label"><?php echo esc_html__( 'Hide Custom Cursor On Tablet', 'wpcustom-cursors' ); ?></span>
										<input type="checkbox" id="hide_tablet" name="hide_tablet" value="on" <?php checked( $hide_tablet_value, 'on' ); ?>>
										<div class="toggler-slider">
											<div class="toggler-knob"></div>
										</div>
									</label>

									<!-- Hide Custom Cursor On Mobile -->
									<label class="toggler-wrapper mt-3 style-4"> 
										<span class="toggler-label"><?php echo esc_html__( 'Hide Custom Cursor On Mobile', 'wpcustom-cursors' ); ?></span>
										<input type="checkbox" id="hide_mobile" name="hide_mobile" value="on" <?php checked( $hide_mobile_value, 'on' ); ?>>
										<div class="toggler-slider">
											<div class="toggler-knob"></div>
										</div>
									</label>

									<!-- Hide Custom Cursor in Admin Panel -->
									<label class="toggler-wrapper mt-3 style-4"> 
										<span class="toggler-label"><?php echo esc_html__( 'Hide Custom Cursor For Admin Panel', 'wpcustom-cursors' ); ?></span>
										<input type="checkbox" id="hide_admin" name="hide_admin" value="on" <?php checked( $hide_admin_value, 'on' ); ?>>
										<div class="toggler-slider">
											<div class="toggler-knob"></div>
										</div>
									</label>
								</div>
							</fieldset>

							<!-- Step 4: Activation -->
							<fieldset>
								<legend class="pb-2 mb-0 pt-3 px-4">
									<div class="d-flex align-items-center">
										<i class="ri-check-double-fill ri-lg"></i>
										<div class="ms-2">
											<div class="lead fw-normal"><?php echo esc_html__( 'Activation', 'wpcustom-cursors' ); ?></div>
											<div class="title-normal text-muted"><?php echo esc_html__( 'Add custom cursor to your page:', 'wpcustom-cursors' ); ?></div>
										</div>
									</div>
								</legend>
								<!-- Progress Bar -->
								<div class="progressbar">
									<div class="progress-complete"></div>
								</div>

								<div class="px-4">
									<div class="btn-group" role="group" aria-label="<?php echo esc_html__( 'Activate On', 'wpcustom-cursors' ); ?>">
										<div id="activate_on_page">
											<input type="radio" class="btn-check" name="activate_on" value="0" id="activate_on_body" autocomplete="off" <?php checked( $activate_on_value, '0' ); ?>>
											<label class="btn btn-outline-dark btn-sm" for="activate_on_body"><i class="ri-window-fill"></i> <?php echo esc_html__( 'Entire Website', 'wpcustom-cursors' ); ?></label>
										</div>
										<div id="activate_on_section">
											<input type="radio" class="btn-check" name="activate_on" value="1" id="activate_on_element" autocomplete="off" <?php checked( $activate_on_value, '1' ); ?>>
											<label class="btn btn-outline-dark btn-sm" for="activate_on_element"><i class="ri-picture-in-picture-line"></i> <?php echo esc_html__( 'A Section', 'wpcustom-cursors' ); ?></label>
										</div>
									</div>
									<!-- End Activate On -->

									<!-- Element Selector Group -->
									<div id="select_element_group" style="display: none;">
										<div class="input-group selector-group mt-3">
											<select class="form-select" name="selector_type" id="selector_type" aria-label="<?php echo esc_attr__( 'Selector Type', 'wpcustom-cursors' ); ?>">
												<option value="tag" 
												<?php
												if ( $selector_type_value ) {
													selected( $selector_type_value, 'tag' );}
												?>
												><?php echo esc_html__( 'Tag', 'wpcustom-cursors' ); ?></option>
												<option value="class" 
												<?php
												if ( $selector_type_value ) {
													selected( $selector_type_value, 'class' );}
												?>
												><?php echo esc_html__( 'Class', 'wpcustom-cursors' ); ?></option>
												<option value="id" 
												<?php
												if ( $selector_type_value ) {
													selected( $selector_type_value, 'id' );}
												?>
												><?php echo esc_html__( 'ID', 'wpcustom-cursors' ); ?></option>
												<option value="attribute" 
												<?php
												if ( $selector_type_value ) {
													selected( $selector_type_value, 'attribute' );}
												?>
												><?php echo esc_html__( 'Attribute', 'wpcustom-cursors' ); ?></option>
											</select>
											<input type='text' placeholder="<?php echo esc_html__( 'Selector', 'wpcustom-cursors' ); ?>" class="form-control rounded-right " name='selector_data' id="selector_data" value="<?php echo esc_attr( $selector_data_value ); ?>" aria-label="<?php echo esc_html__( 'Selector Query', 'wpcustom-cursors' ); ?>" aria-describedby="selector_type">
										</div>

										<small class="text-muted fw-light"><?php echo esc_html__( 'All elements selected with above criteria would have the custom cursor.', 'wpcustom-cursors' ); ?></small>
										<!-- End Element Selector Group -->
									</div>

									<!-- Submit Button -->
									<div>
									<?php
									if ( isset( $_GET['edit_row'] ) ) {
										?>
											<input type="hidden" name="update_id" value="<?php echo intval( esc_attr( sanitize_text_field( $_GET['edit_row'] ) ) ); ?>">
											<button type="submit" name="update" class="btn btn-success mt-4 d-flex align-items-center">
											<?php echo esc_html__( 'Update Cursor', 'wpcustom-cursors' ); ?>
												<i class="ri-pencil-line ms-2"></i>
											</button>
											<?php
									} else {
										?>
											<button type="submit" name="add" class="btn btn-primary mt-4 d-flex align-items-center">
											<?php echo esc_html__( 'Save Cursor', 'wpcustom-cursors' ); ?>
												<i class="ri-checkbox-circle-fill ms-2"></i>
											</button>
											<?php
									}
									?>
									</div>
								</div>
							</fieldset>
							<?php wp_nonce_field( 'wpcc_add_new_cursor', 'wpcc_add_new_nonce' ); ?>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card bg-light rounded-3 p-0 position-sticky top">
					<div class="card-body p-0">
						<!-- Preview -->
						<div class="bg-white rounded-2">
							<div id="wt-preview" class="
							<?php
							if ( 0 === intval( $default_cursor_value ) ) {
								echo esc_attr( 'no-cursor' ); }
							?>
							">
								<div class="preview-inner  p-4 ">
									<div class="font-weight-bold mb-3"><?php echo esc_html__( 'Preview:', 'wpcustom-cursors' ); ?></div>
									<div class="d-flex align-items-center">
										<button class="btn btn-danger"><?php echo esc_html__( 'Button', 'wpcustom-cursors' ); ?></button>
										<input type="text" class="form-control mx-2" placeholder="<?php esc_attr__( 'Input', 'wpcustom-cursors' ); ?>">
										<a href="javascript:void(0);"><?php echo esc_html__( 'Link', 'wpcustom-cursors' ); ?></a>
										
									</div>
									<div class="position-relative">
										<img src="<?php echo esc_url( plugins_url( '../img/preview-image.jpg', __FILE__ ) ); ?>" alt="<?php echo esc_attr__( 'Test blending mode option on image', 'wpcustom-cursors' ); ?>" class="img-fluid mt-2 rounded" />
										<small class="credit"><?php echo esc_html__( 'Photo Credit: Unsplash', 'wpcustom-cursors' ); ?></small>
									</div>
								</div>
							</div>
						</div>
						<!-- End Preview -->
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="toast-container position-fixed bottom-0 end-0 p-3">
			<div id="cursor_toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
			<div class="toast-header">
					<i class="ri-cursor-line lh-1 fs-5 me-2"></i>
					<strong class="me-auto"><?php echo esc_html__( 'Cursor Removed', 'wpcustom-cursors' ); ?></strong>
					<small><?php echo esc_html__( 'Just Now', 'wpcustom-cursors' ); ?></small>
					<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body">
			<?php echo esc_html__( 'The cursor was permanently deleted.', 'wpcustom-cursors' ); ?>
			</div>
			</div>
	</div>