<?php

class ThemeRain_Fonts_Admin {

	public function __construct() {
		add_filter( 'upload_mimes', array( $this, 'allow_mimes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'register_options_page' ) );
	}

	public function allow_mimes( $mimes ) {
		$mimes['woff2'] = 'application/x-font-woff2';
		return $mimes;
	}

	public function admin_enqueue_scripts( $page ) {
		if ( 'appearance_page_themerain-fonts' === $page ) {
			wp_enqueue_media();
			wp_enqueue_style( 'trc-fonts', TRC_ASSETS_URL . '/css/fonts.css' );
			wp_enqueue_script( 'trc-fonts', TRC_ASSETS_URL . '/js/fonts.js', array( 'jquery' ), false, true );
		}
	}

	public function register_options_page() {
		add_theme_page( 'Custom Fonts', 'Custom Fonts', 'manage_options', 'themerain-fonts', array( $this, 'settings_page' ) );
	}

	public function settings_page() {
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : null;
		?>

		<div class="wrap">
			<h1><?php esc_html_e( 'Custom Fonts', 'themerain' ); ?></h1>

			<nav class="nav-tab-wrapper">
				<a class="nav-tab <?php if ( null === $tab ) { echo 'nav-tab-active'; } ?>" href="?page=themerain-fonts"><?php esc_html_e( 'Custom Fonts', 'themerain' ); ?></a>
				<a class="nav-tab <?php if ( 'adobe-fonts' === $tab ) { echo 'nav-tab-active'; } ?>" href="?page=themerain-fonts&tab=adobe-fonts"><?php esc_html_e( 'Adobe Fonts', 'themerain' ); ?></a>
			</nav>

			<?php
			switch ( $tab ) {
				case 'adobe-fonts' :
					$this->adobe_fonts_settings();
					break;

				default :
					$this->custom_fonts_settings();
					break;
			}
			?>
		</div>
		<?php
	}

	private function custom_fonts_settings() {
		$this->save_custom_fonts();

		$custom_fonts_id = get_option( 'themerain_custom_fonts_counter', 1 );

		$params = array(
			'name'   => '',
			'src'    => '',
			'weight' => '400',
			'style'  => 'normal'
		);

		$action = 'new';

		if ( isset( $_GET['_wpnonce'] ) && isset( $_GET['action'] ) && wp_verify_nonce( $_GET['_wpnonce'] ) ) {
			$action = sanitize_title( $_GET['action'] );

			if ( isset( $_GET['themerain_custom_fonts_id'] ) && 'edit' === $action ) {
				$custom_fonts_id = sanitize_key( $_GET['themerain_custom_fonts_id'] );
			}
		}

		if ( 'edit' === $action ) {
			$font_list = get_option( 'themerain_custom_fonts' );

			if ( isset( $font_list[ $custom_fonts_id ] ) ) {
				$params = array_merge( (array) $params, (array) $font_list[ $custom_fonts_id ] );
			}
		}

		if ( 'delete' === $action ) {
			$this->delete_font();
		}

		$custom_fonts_link = admin_url( 'themes.php?page=themerain-fonts' );
		?>

		<form method="post" action="<?php echo esc_url( $custom_fonts_link ); ?>">
			<div class="themerain-fonts">
				<div>
					<h3><?php esc_html_e( 'Instructions', 'themerain' ); ?></h3>
					<p><?php esc_html_e( 'Upload your webfont in woff2 format. All fonts will be listed under Appearance &rarr; Customize &rarr; Typography.', 'themerain' ); ?></p>

					<hr>

					<?php if ( 'edit' === $action ) : ?>
						<h3><?php esc_html_e( 'Edit Font', 'themerain' ); ?> <small><a href="<?php echo esc_url( $custom_fonts_link ); ?>"><?php esc_html_e( 'cancel ', 'themerain' ); ?></a></small></h3>
					<?php else : ?>
						<h3><?php esc_html_e( 'Add Font', 'themerain' ); ?></h3>
					<?php endif; ?>

					<p class="themerain-field">
						<label for="themerain_custom_fonts_name"><?php esc_html_e( 'Name', 'themerain' ); ?></label>
						<input id="themerain_custom_fonts_name" name="themerain_custom_fonts_name" type="text" aria-required="true" value="<?php echo esc_attr( stripslashes( $params['name'] ) ); ?>" />
					</p>

					<p class="upload-font-container">
						<label><?php esc_html_e( 'Font .woff2', 'themerain' ); ?></label>
						<input id="themerain_custom_fonts_src" name="themerain_custom_fonts_src" class="filename" type="text" placeholder="<?php esc_html_e( 'Upload the font\'s woff2 file or enter the URL.', 'themerain' ); ?>" value="<?php echo esc_url( $params['src'] ); ?>">
						<a class="upload-font-link button" href="#"><?php esc_html_e( 'Upload', 'themerain' ); ?></a>
					</p>

					<p>
						<label for="themerain_custom_fonts_weight"><?php esc_html_e( 'Weight', 'themerain' ); ?></label>
						<select id="themerain_custom_fonts_weight" name="themerain_custom_fonts_weight">
							<option value="100" <?php selected( '100', $params['weight'] ); ?>><?php esc_html_e( '100', 'themerain' ); ?></option>
							<option value="200" <?php selected( '200', $params['weight'] ); ?>><?php esc_html_e( '200', 'themerain' ); ?></option>
							<option value="300" <?php selected( '300', $params['weight'] ); ?>><?php esc_html_e( '300', 'themerain' ); ?></option>
							<option value="400" <?php selected( '400', $params['weight'] ); ?>><?php esc_html_e( '400 (regular)', 'themerain' ); ?></option>
							<option value="500" <?php selected( '500', $params['weight'] ); ?>><?php esc_html_e( '500', 'themerain' ); ?></option>
							<option value="600" <?php selected( '600', $params['weight'] ); ?>><?php esc_html_e( '600', 'themerain' ); ?></option>
							<option value="700" <?php selected( '700', $params['weight'] ); ?>><?php esc_html_e( '700', 'themerain' ); ?></option>
							<option value="800" <?php selected( '800', $params['weight'] ); ?>><?php esc_html_e( '800', 'themerain' ); ?></option>
							<option value="900" <?php selected( '900', $params['weight'] ); ?>><?php esc_html_e( '900', 'themerain' ); ?></option>
						</select>
					</p>

					<p>
						<label for="themerain_custom_fonts_style"><?php esc_html_e( 'Style', 'themerain' ); ?></label>
						<select id="themerain_custom_fonts_style" name="themerain_custom_fonts_style">
							<option value="normal" <?php selected( 'normal', $params['style'] ); ?>><?php esc_html_e( 'normal', 'themerain' ); ?></option>
							<option value="italic" <?php selected( 'italic', $params['style'] ); ?>><?php esc_html_e( 'italic', 'themerain' ); ?></option>
						</select>
					</p>

					<input name="themerain_custom_fonts_id" type="hidden" value="<?php echo esc_html( $custom_fonts_id ); ?>" />

					<?php wp_nonce_field(); ?>

					<?php if ( 'edit' === $action ) : ?>
						<p><input class="button button-primary" name="edit_settings" type="submit" value="<?php esc_html_e( 'Save Change', 'themerain' ); ?>" /></p>
					<?php else : ?>
						<p><input class="button button-primary" name="save_settings" type="submit" value="<?php esc_html_e( 'Add Font', 'themerain' ); ?>" /></p>
					<?php endif; ?>
				</div>

				<div>
					<h3><?php esc_html_e( 'Uploaded Fonts', 'themerain' ); ?></h3>

					<table class="wrap wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<td scope="col" class="manage-column"><?php esc_html_e( 'Name', 'themerain' ); ?></td>
								<td scope="col" class="manage-column"><?php esc_html_e( 'Font Weight', 'themerain' ); ?></td>
								<td scope="col" class="manage-column"><?php esc_html_e( 'Font Style', 'themerain' ); ?></td>
							</tr>
						</thead>
						<tbody id="the-list">
							<?php
							$families = get_option( 'themerain_custom_fonts', array() );

							if ( is_array( $families ) && $families ) {
								$families_keys = array_keys( $families );

								foreach ( $families as $key => $row ) {
									$families_value[ $key ] = $row['name'];
									$families_name[ $key ]  = $row['weight'];
									$families_order[ $key ] = $row['style'];
								}
								array_multisort( $families_value, SORT_DESC, $families_order, SORT_DESC, $families_name, SORT_ASC, $families, $families_keys );

								$families = array_combine( $families_keys, $families );
							}

							if ( $families ) {
								foreach ( $families as $key => $family ) {
									$edit_link   = add_query_arg( array( '_wpnonce' => wp_create_nonce(), 'themerain_custom_fonts_id' => $key, 'action' => 'edit' ), $custom_fonts_link );
									$delete_link = add_query_arg( array( '_wpnonce' => wp_create_nonce(), 'themerain_custom_fonts_id' => $key, 'action' => 'delete' ), $custom_fonts_link );
									?>

									<tr>
										<td scope="col" class="manage-column">
											<a class="row-title" href="<?php echo esc_url( $edit_link ); ?>"><?php echo esc_html( $family['name'] ); ?></a>

											<div class="row-actions">
												<span class="edit">
													<a href="<?php echo esc_url( $edit_link ); ?>" role="button"><?php esc_html_e( 'Edit', 'themerain' ); ?></a> |
												</span>
												<span class="delete">
													<a href="<?php echo esc_url( $delete_link ); ?>" class="themerain-fonts-delete" role="button"><?php esc_html_e( 'Delete', 'themerain' ); ?></a>
												</span>
											</div>
										</td>
										<td scope="col" class="manage-column"><?php echo esc_html( $family['weight'] ); ?></td>
										<td scope="col" class="manage-column"><?php echo esc_html( $family['style'] ); ?></td>
									</tr>
									<?php
								}
							} else {
								?>
								<tr>
									<td scope="col" colspan="3"><?php esc_html_e( 'No fonts found. Add new fonts with the form to the left.', 'themerain' ); ?></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</form>
		<?php
	}

	protected function delete_font() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'] ) ) {
			return;
		}

		if ( ! isset( $_GET['themerain_custom_fonts_id'] ) ) {
			return;
		}

		$id        = sanitize_key( $_GET['themerain_custom_fonts_id'] );
		$font_list = (array) get_option( 'themerain_custom_fonts', array() );

		if ( isset( $font_list[ $id ] ) ) {
			if ( isset( $font_list[ $id ]['src'] ) ) {
				wp_delete_attachment( $font_list[ $id ]['src'], true );
			}

			unset( $font_list[ $id ] );

			update_option( 'themerain_custom_fonts', $font_list );

			printf( '<div id="message" class="updated fade"><p><strong>%s</strong></p></div>', esc_html__( 'Font successfully deleted.', 'themerain' ) );
		}
	}

	protected function save_custom_fonts() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'] ) ) {
			return;
		}

		if ( ! isset( $_POST['themerain_custom_fonts_id'] ) ) {
			return;
		}

		if ( isset( $_POST['save_settings'] ) ) {
			update_option( 'themerain_custom_fonts_counter', intval( get_option( 'themerain_custom_fonts_counter', 1 ) ) + 1 );
		}

		$id = sanitize_key( $_POST['themerain_custom_fonts_id'] );

		$font_list = (array) get_option( 'themerain_custom_fonts', array() );

		$font_list[ $id ] = array();

		if ( isset( $_POST['themerain_custom_fonts_name'] ) ) {
			$font_list[ $id ]['name'] = sanitize_text_field( $_POST['themerain_custom_fonts_name'] );
		}
		if ( isset( $_POST['themerain_custom_fonts_src'] ) ) {
			$font_list[ $id ]['src'] = esc_url( $_POST['themerain_custom_fonts_src'] );
		}
		if ( isset( $_POST['themerain_custom_fonts_weight'] ) ) {
			$font_list[ $id ]['weight'] = sanitize_text_field( $_POST['themerain_custom_fonts_weight'] );
		}
		if ( isset( $_POST['themerain_custom_fonts_style'] ) ) {
			$font_list[ $id ]['style'] = sanitize_text_field( $_POST['themerain_custom_fonts_style'] );
		}

		update_option( 'themerain_custom_fonts', $font_list );

		printf( '<div id="message" class="updated fade"><p><strong>%s</strong></p></div>', 'Settings saved.' );
	}

	protected function adobe_fonts_settings() {
		$this->save_adobe_fonts();

		$adobe_fonts      = get_option( 'themerain_adobe_fonts' );
		$adobe_fonts_id   = ( isset( $adobe_fonts['id'] ) ) ? $adobe_fonts['id'] : '';
		$adobe_fonts_list = self::get_adobe_fonts_list( $adobe_fonts_id );
		?>

		<div class="themerain-fonts">
			<div>
				<h3><?php esc_html_e( 'Instructions', 'themerain' ); ?></h3>
				<p><?php esc_html_e( 'You can get the Project ID', 'themerain' ); ?> <a href="https://fonts.adobe.com/my_fonts?browse_mode=all#web_projects-section" target="_blank"><?php esc_html_e( 'here', 'themerain' ); ?></a> <?php esc_html_e( 'from your Adobe Fonts account. Project ID can be found next to the kit names.', 'themerain' ); ?></p>
				<p><?php esc_html_e( 'Once you add your Project ID, all the fonts will be listed under Appearance &rarr; Customize &rarr; Typography.', 'themerain' ); ?></p>

				<hr>

				<form method="post">
					<p>
						<label for="themerain_adobe_fonts_id"><?php esc_html_e( 'Project ID', 'themerain' ); ?></label>
						<input name="themerain_adobe_fonts_id" id="themerain_adobe_fonts_id" type="text" value="<?php echo esc_attr( get_option( 'themerain_adobe_fonts_id' ) ); ?>">
					</p>
					<?php wp_nonce_field(); ?>
					<input class="button button-primary" name="save_settings" type="submit" value="Save" />
				</form>
			</div>

			<div>
				<h3><?php esc_html_e( 'Available Fonts', 'themerain' ); ?></h3>
				<table class="wrap wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<td><?php esc_html_e( 'Name', 'themerain' ); ?></td>
							<td><?php esc_html_e( 'Font Weight', 'themerain' ); ?></td>
							<td><?php esc_html_e( 'Font Style', 'themerain' ); ?></td>
						</tr>
					</thead>

					<tbody>
						<?php
						if ( $adobe_fonts_list ) {
							foreach ( $adobe_fonts_list as $family ) {
								if ( $family['variations'] ) {
									foreach ( $family['variations'] as $variant ) {
										?>
										<tr>
											<td><?php echo esc_html( $family['name'] ); ?></td>
											<td><?php echo esc_html( $variant['w'] ); ?></td>
											<td><?php echo esc_html( $variant['w'] ); ?></td>
										</tr>
										<?php
									}
								}
							}
						} else {
							?>
							<tr>
								<td colspan="3"><?php esc_html_e( 'No fonts found.', 'themerain' ); ?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}

	protected function save_adobe_fonts() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'] ) ) {
			return;
		}

		if ( isset( $_POST['save_settings'] ) ) {
			if ( isset( $_POST['themerain_adobe_fonts_id'] ) ) {
				$option         = array();
				$id             = sanitize_text_field( $_POST['themerain_adobe_fonts_id'] );
				$option['id']   = $id;
				$option['list'] = self::get_adobe_fonts_list( $id );

				update_option( 'themerain_adobe_fonts', $option );
			}

			printf( '<div id="message" class="updated fade"><p><strong>%s</strong></p></div>', esc_html__( 'Settings saved.', 'themerain' ) );
		}
	}

	/**
	 * Adobe fonts details.
	 */
	public static function get_adobe_fonts_list( $project_id ) {
		$typekit_info = array();
		$typekit_uri  = 'https://typekit.com/api/v1/json/kits/' . $project_id . '/published';
		$response     = wp_remote_get( $typekit_uri, [ 'timeout' => 30 ] );

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
			return $typekit_info;
		}

		$data     = json_decode( wp_remote_retrieve_body( $response ), true );
		$families = $data['kit']['families'];

		foreach ( $families as $family ) {
			$family_name = $family['name'];
	
			$typekit_info[ $family_name ] = array(
				'name'       => $family_name,
				'variations' => array(),
			);
	
			foreach ( $family['variations'] as $variation ) {
				switch ( $variation ) {
					case 'n1' :
						$style = [ 'w' => '100', 's' => 'normal' ];
						break;

					case 'i1' :
						$style = [ 'w' => '100', 's' => 'italic' ];
						break;

					case 'n2' :
						$style = [ 'w' => '200', 's' => 'normal' ];
						break;

					case 'i2' :
						$style = [ 'w' => '200', 's' => 'italic' ];
						break;
					
					case 'n3' :
						$style = [ 'w' => '300', 's' => 'normal' ];
						break;

					case 'i3' :
						$style = [ 'w' => '300', 's' => 'italic' ];
						break;

					case 'n4' :
						$style = [ 'w' => '400', 's' => 'normal' ];
						break;

					case 'i4' :
						$style = [ 'w' => '400', 's' => 'italic' ];
						break;
					
					case 'n5' :
						$style = [ 'w' => '500', 's' => 'normal' ];
						break;

					case 'i5' :
						$style = [ 'w' => '500', 's' => 'italic' ];
						break;
					
					case 'n6' :
						$style = [ 'w' => '600', 's' => 'normal' ];
						break;

					case 'i6' :
						$style = [ 'w' => '600', 's' => 'italic' ];
						break;

					case 'n7' :
						$style = [ 'w' => '700', 's' => 'normal' ];
						break;

					case 'i7' :
						$style = [ 'w' => '700', 's' => 'italic' ];
						break;

					case 'n8' :
						$style = [ 'w' => '800', 's' => 'normal' ];
						break;

					case 'i8' :
						$style = [ 'w' => '800', 's' => 'italic' ];
						break;

					case 'n9' :
						$style = [ 'w' => '900', 's' => 'normal' ];
						break;

					case 'i9' :
						$style = [ 'w' => '900', 's' => 'italic' ];
						break;
				}

				$typekit_info[ $family_name ]['variations'][] = $style;
			}
		}

		return $typekit_info;
	}

}

new ThemeRain_Fonts_Admin();
