<?php defined( 'ABSPATH' ) or die( "You can't access this file directly." ); ?>
<div id="hide-archive-label">
	<div class="content-wrapper">
		<div class="header">
			<h2><?php esc_html_e( 'Settings', 'hide-archive-label' ); ?></h2>
		</div> <!-- .Header -->
		<div class="content">
			<?php if ( isset( $_GET['settings-updated'] ) ) { ?>
			<div id="message" class="notice updated fade">
				<p><strong><?php esc_html_e( 'Plugin Options Saved.', 'hide-archive-label' ); ?></strong></p>
			</div>
			<?php } ?>
			<?php
				// Use nonce for verification.
				wp_nonce_field( HAL_BASENAME, 'hide_archive_label_nonce' );
			?>
			<div id="hide_archive_label_main" class="hide-archive-label-main">
				<form method="post" action="options.php">
					<?php settings_fields( 'hide-archive-label-group' ); ?>
					<?php
					$settings = $this->hide_archive_label_get_options();
					?>
					<div class="option-container">
						<table class="form-table" bgcolor="white">
							<tbody>
								<tr>
									<th>
									<?php esc_html_e( 'Hide By', 'hide-archive-label' ); ?>
									</th>
									<td>
										<select name="hide_archive_label_options[remove]" id="hide_archive_label_options[remove]">
										<option value="remove_accessibly" <?php echo selected( $settings['remove'], 'remove_accessibly', false ); ?> ><?php esc_html_e( 'Remove Accessibly', 'hide-archive-label' ); ?></option>
										<option value="remove" <?php echo selected( $settings['remove'], 'remove', false ); ?> ><?php esc_html_e( 'Remove', 'hide-archive-label' ); ?></option>
									</select>
									<span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'Whether you want to hide it with CSS only or you remove it from the Page/Code.', 'hide-archive-label' ); ?>"></span>
									</td>
								</tr>
								<?php
								$labels = $this->archive_label_options();
								foreach ( $labels as $key => $label ) :
									?>
								<tr>
									<th scope="row"><?php echo $label; ?></th>
									<td>
										<div class="module-header <?php echo $settings[ $key ] ? 'active' : 'inactive'; ?>">
											<div class="switch">
												<input type="checkbox" id="hide_archive_label_options[<?php echo $key; ?>]" 
												value="1"
												name="hide_archive_label_options[<?php echo $key; ?>]" class="shm-input-switch" rel="<?php echo $key; ?>" <?php checked( 1, $settings[ $key ] ); ?> >
												<label for="hide_archive_label_options[<?php echo $key; ?>]"></label>
											</div>
										</div>
									</td>
								</tr>
									<?php
								endforeach;
								?>

								<tr>
									<th scope="row"><?php esc_html_e( 'Reset All Options', 'hide-archive-label' ); ?></th>
									<td>
										<div class="module-header">
											<div class="switch">
												<input type="checkbox" id="hide_archive_label_options[reset]" name="hide_archive_label_options[reset]" class="shm-input-switch" rel="reset">
												<label for="hide_archive_label_options[reset]"></label><span class="dashicons dashicons-info tooltip" title="<?php esc_html_e( 'Checking option will set all values to their defaults', 'hide-archive-label' ); ?>"></span>
											</div>
										</div>

									</td>
								</tr>
							</tbody>
						</table>
						<?php submit_button( esc_html__( 'Save Changes', 'hide-archive-label' ) ); ?>
					</div><!-- .option-container -->
				</form>
			</div><!-- sticky_main -->
		</div><!-- .content -->
	</div><!-- .content-wrapper -->
</div><!---hide-archive-label-->
