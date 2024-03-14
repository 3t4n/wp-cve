<?php
/**
 * Main Admin Class
 * Enqueue styles and scripts and create admin pages
 * php version 7.2
 *
 * @category   Plugin
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/admin
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 * @link       https://hamidrezasepehr.com/
 * @since      1.0.0
 * @license    GPLv2 or later (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 */

/**
 * Wp_Custom_Cursors_Admin
 *
 * @package    Wp_Custom_Cursors
 * @subpackage Wp_Custom_Cursors/admin
 * @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
 */
class Wp_Custom_Cursors_Admin {

	/**
	 * Variable to hold plugin name
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * Variable to hold plugin version
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Function constructor
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Enqueue styles function
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		$screen = get_current_screen();
		$base   = $screen->base;
		$pages  = array( 'wp_custom_cursors', 'wpcc_add_new', 'wpcc_cursor_maker', 'wpcc_tuts' );
		foreach ( $pages as $page ) {
			$pos = strripos( $base, $page );
			if ( ! ( false === $pos ) ) {
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-custom-cursors-admin.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'bootstrapcss', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'remixicon', plugin_dir_url( __FILE__ ) . 'fonts/remixicon.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'spectrum', plugin_dir_url( __FILE__ ) . 'css/spectrum.min.css', array(), $this->version, 'all' );
			}
		}
	}

	/**
	 * Enqueue scripts function
	 *
	 * @since 1.1.0
	 */
	public function enqueue_scripts() {
		global $wpdb;
		$tablename      = $wpdb->prefix . 'created_cursors';
		$prepared_query = $wpdb->prepare( 'SELECT * FROM %i', $tablename );
		$cursors        = $wpdb->get_results( $prepared_query, ARRAY_A );
		$cursors_array  = array();
		foreach ( $cursors as $cursor ) {
			$stripped                 = stripslashes( $cursor['cursor_options'] );
			$decoded                  = json_decode( $stripped, false );
			$cursor['cursor_options'] = $decoded;
			$stripped_hover           = stripslashes( $cursor['hover_cursors'] );
			$decoded_hover            = json_decode( $stripped_hover, false );
			$cursor['hover_cursors']  = $decoded_hover;
			array_push( $cursors_array, $cursor );
		}
		$image_path           = array( plugins_url( 'img', __FILE__ ) );
		$screen               = get_current_screen();
		$base                 = $screen->base;
		$pages                = array( 'wp_custom_cursors', 'wpcc_add_new', 'wpcc_cursor_maker' );
		$add_new_pos          = strripos( $base, $pages[1] );
		$cursor_maker_pos     = strripos( $base, $pages[2] );
		$wp_custom_cursor_pos = strripos( $base, $pages[0] );
		$i10n_strings         = array();
		array_push( $i10n_strings, esc_html__( 'Hover cursor for this selector already exists! Try changing the selector.', 'wpcustom-cursors' ) );
		array_push( $i10n_strings, esc_html__( 'Background Color: ', 'wpcustom-cursors' ) );
		array_push( $i10n_strings, esc_html__( 'Width: ', 'wpcustom-cursors' ) );
		array_push( $i10n_strings, esc_html__( 'Activates On: ', 'wpcustom-cursors' ) );

		if ( ! ( false === $wp_custom_cursor_pos ) ) {
			wp_enqueue_script( 'bootstrapjs', plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js', array(), $this->version, 'all' );
		}
		if ( ! ( false === $add_new_pos ) ) {
			wp_enqueue_script( 'bootstrapjs', plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js', array(), $this->version, 'all' );
			wp_enqueue_script( 'interactjs', plugin_dir_url( __FILE__ ) . 'js/interact.min.js', array(), $this->version, 'all' );
			wp_enqueue_script( 'spectrum', plugin_dir_url( __FILE__ ) . 'js/spectrum.min.js', array(), $this->version, 'all' );
			wp_enqueue_script( 'formtowizard', plugin_dir_url( __FILE__ ) . 'js/jquery.formtowizard.js', array(), $this->version, 'all' );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-custom-cursors-admin.js', array(), $this->version, 'all' );
			wp_localize_script( $this->plugin_name, 'wpcc_image_path', $image_path );
			wp_enqueue_media();
			wp_localize_script( $this->plugin_name, 'cursors', $cursors_array );
			wp_localize_script( $this->plugin_name, 'strings', $i10n_strings );
		}
		if ( ! ( false === $cursor_maker_pos ) ) {
			wp_enqueue_script( 'bootstrapjs', plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js', array(), $this->version, 'all' );
			wp_localize_script( $this->plugin_name, 'wpcc_image_path', $image_path );
			wp_enqueue_media();
			wp_enqueue_script( 'interactjs', plugin_dir_url( __FILE__ ) . 'js/interact.min.js', array(), $this->version, 'all' );
			wp_enqueue_script( 'spectrum', plugin_dir_url( __FILE__ ) . 'js/spectrum.min.js', array(), $this->version, 'all' );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-custom-cursors-make-cursor.js', array(), $this->version, 'all' );
			$hovers;
			if ( isset( $_GET['edit_row'] ) ) {
				$row_id = intval( sanitize_text_field( wp_unslash( $_GET['edit_row'] ) ) );
				foreach ( $cursors as $cursor ) {
					if ( $cursor['cursor_id'] == $row_id ) {
						$stripped = stripslashes( $cursor['hover_cursors'] );
						$decoded  = json_decode( $stripped, false );
						$hovers   = $decoded;
					}
				}
				wp_localize_script( $this->plugin_name, 'hovers', $hovers );
			}
		}
	}

	/**
	 * Add admin menu
	 *
	 * @since 1.0.0
	 */
	public function wp_custom_cursors_add_admin_menu() {
		add_menu_page( esc_html__( 'WP Custom Cursors', 'wpcustom-cursors' ), esc_html__( 'Custom Cursor', 'wpcustom-cursors' ), 'manage_options', 'wp_custom_cursors', 'wpcc_render_main_page', 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMjEuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTI7IiB4bWw6c3BhY2U9InByZXNlcnZlIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiI+CjxnPgoJPHBhdGggZmlsbD0iI2EwYTVhYSIgZD0iTTUwMi45LDMwNC44MzRMMTg4LjQ4MSwxNjguNzc5Yy01LjY0LTIuMzg4LTEyLjE3My0xLjE0My0xNi41MDksMy4xOTNzLTUuNTk2LDEwLjg2OS0zLjE5MywxNi41MDlsMTM2LjA1NSwzMTQuNDIyICAgYzIuMzg4LDUuNTUyLDcuODM3LDkuMDk3LDEzLjc5OSw5LjA5N2MwLjQzOSwwLDAuODk0LTAuMDE1LDEuMzYyLTAuMDU5YzYuNDc1LTAuNTg2LDExLjgzNi01LjI4OCwxMy4yNzEtMTEuNjMxbDMxLjU1My0xMzUuNDkxICAgbDEzNS40ODgtMzEuNTUzYzYuMzQzLTEuNDM2LDExLjA0NS02Ljc5NywxMS42MzEtMTMuMjcxUzUwOC44NzcsMzA3LjM5Nyw1MDIuOSwzMDQuODM0eiIvPgoJPHBhdGggZmlsbD0iI2EwYTVhYSIgZD0iTTE5NSwxMjBjOC4yOTEsMCwxNS02LjcwOSwxNS0xNVYxNWMwLTguMjkxLTYuNzA5LTE1LTE1LTE1cy0xNSw2LjcwOS0xNSwxNXY5MEMxODAsMTEzLjI5MSwxODYuNzA5LDEyMCwxOTUsMTIweiIvPgoJPHBhdGggZmlsbD0iI2EwYTVhYSIgZD0iTTEyMC43NjIsMjQ4LjAyN2wtNjMuNjQ3LDYzLjY0N2MtNS44NTksNS44NTktNS44NTksMTUuMzUyLDAsMjEuMjExYzUuODYsNS44NiwxNS4zNTEsNS44NiwyMS4yMTEsMGw2My42NDctNjMuNjQ3ICAgYzUuODU5LTUuODU5LDUuODU5LTE1LjM1MiwwLTIxLjIxMVMxMjYuNjIxLDI0Mi4xNjgsMTIwLjc2MiwyNDguMDI3eiIvPgoJPHBhdGggZmlsbD0iI2EwYTVhYSIgZD0iTTI2OS4yMzgsMTQxLjk3M2w2My42NDctNjMuNjQ3YzUuODU5LTUuODU5LDUuODU5LTE1LjM1MiwwLTIxLjIxMXMtMTUuMzUyLTUuODU5LTIxLjIxMSwwbC02My42NDcsNjMuNjQ3ICAgYy01Ljg1OSw1Ljg1OS01Ljg1OSwxNS4zNTIsMCwyMS4yMTFDMjUzLjg4NywxNDcuODMyLDI2My4zNzksMTQ3LjgzMiwyNjkuMjM4LDE0MS45NzN6Ii8+Cgk8cGF0aCBmaWxsPSIjYTBhNWFhIiBkPSJNNzguMzI1LDU3LjExNGMtNS44NTktNS44NTktMTUuMzUyLTUuODU5LTIxLjIxMSwwcy01Ljg1OSwxNS4zNTIsMCwyMS4yMTFsNjMuNjQ3LDYzLjY0N2M1Ljg2LDUuODYsMTUuMzUxLDUuODYsMjEuMjExLDAgICBjNS44NTktNS44NTksNS44NTktMTUuMzUyLDAtMjEuMjExTDc4LjMyNSw1Ny4xMTR6Ii8+Cgk8cGF0aCBmaWxsPSIjYTBhNWFhIiBkPSJNMTIwLDE5NWMwLTguMjkxLTYuNzA5LTE1LTE1LTE1SDE1Yy04LjI5MSwwLTE1LDYuNzA5LTE1LDE1czYuNzA5LDE1LDE1LDE1aDkwQzExMy4yOTEsMjEwLDEyMCwyMDMuMjkxLDEyMCwxOTV6Ii8+CjwvZz4KCgoKCgoKCgoKCgoKCgoKPC9zdmc+Cg==' );
		add_submenu_page( 'wp_custom_cursors', esc_html__( 'Add New Cursor', 'wpcustom-cursors' ), esc_html__( 'Add New Cursor', 'wpcustom-cursors' ), 'manage_options', 'wpcc_add_new', 'wpcc_render_add_new_page' );
		add_submenu_page( 'wp_custom_cursors', esc_html__( 'Cursor Maker', 'wpcustom-cursors' ), esc_html__( 'Cursor Maker', 'wpcustom-cursors' ), 'manage_options', 'wpcc_cursor_maker', 'wpcc_render_cursor_maker_page' );
		add_submenu_page( 'wp_custom_cursors', esc_html__( 'Tutorials', 'wpcustom-cursors' ), esc_html__( 'Tutorials', 'wpcustom-cursors' ), 'manage_options', 'wpcc_tuts', 'wpcc_render_tuts_page' );
		/**
		 * Render admin page
		 *
		 * @since 1.0.0
		 */
		function wpcc_render_main_page() {
			?>
			<div class="wt-page mt-3 me-4">
			<?php include_once 'partials/wp-custom-cursors-header.php'; ?>
				<!-- Body -->
				<div class="wt-body">
			<?php
			global $wpdb;
			$tablename      = $wpdb->prefix . 'added_cursors';
			$prepared_query = $wpdb->prepare( 'SELECT * FROM %i', $tablename );
			$cursors        = $wpdb->get_results( $prepared_query );
			if ( $cursors ) {
				?>
						<div class="card bg-light">
							<div class="card-body">
								<div class="row">
									<div class="col">
										<h3 class="h5 mb-3"><?php echo esc_html__( 'Active Cursors:', 'wpcustom-cursors' ); ?></h3>
									</div>
								</div>
				<?php
				foreach ( $cursors as $cursor ) {
					?>

								<div class="row align-items-center mt-3 shadow py-3 rounded-4">
									<div class="col-md-2 position-relative text-center">
					<?php
					switch ( $cursor->cursor_type ) {
						case 'shape':
							if ( str_contains( $cursor->cursor_shape, 'created' ) ) {
								$id = intval( substr( $cursor->cursor_shape, 8 ) );
								global $wpdb;
								$tablename      = $wpdb->prefix . 'created_cursors';
								$prepared_query = $wpdb->prepare( 'SELECT * FROM %i WHERE cursor_id = %d', array( $tablename, $id ) );
								$created_cursor = $wpdb->get_row( $prepared_query );
								$stripped       = stripslashes( $created_cursor->cursor_options );
								$decoded        = json_decode( $stripped, false );
								?>
																				<label class="created-cursor-label" style="--fe-width: <?php echo esc_attr( $decoded->fe_width ); ?>px; --fe-height: <?php echo esc_attr( $decoded->fe_height ); ?>px; --fe-color: <?php echo esc_attr( $decoded->fe_color ); ?>; --fe-radius: <?php echo esc_attr( $decoded->fe_radius ); ?>px; --fe-border: <?php echo esc_attr( $decoded->fe_border_width ); ?>px; --fe-border-color: <?php echo esc_attr( $decoded->fe_border_color ); ?>; --fe-blending: <?php echo esc_attr( $decoded->fe_blending ); ?>; --fe-zindex: <?php echo esc_attr( $decoded->fe_zindex ); ?>; --se-width: <?php echo esc_attr( $decoded->se_width ); ?>px; --se-height: <?php echo esc_attr( $decoded->se_height ); ?>px; --se-color: <?php echo esc_attr( $decoded->se_color ); ?>; --se-radius: <?php echo esc_attr( $decoded->se_radius ); ?>px; --se-border: <?php echo esc_attr( $decoded->se_border_width ); ?>px; --se-border-color: <?php echo esc_attr( $decoded->se_border_color ); ?>; --se-blending: <?php echo esc_attr( $decoded->se_blending ); ?>; --se-zindex: <?php echo esc_attr( $decoded->se_zindex ); ?>;"><div class="cursor-el1" ></div><div class="cursor-el2"></div>
																				</label>
									<?php
							} else {
								?>
																				<img src="<?php echo esc_url( plugins_url( 'img/cursors/' . $cursor->cursor_shape . '.svg', __FILE__ ) ); ?>" alt="
																							<?php
																								echo esc_html__( 'Cursor Shape ', 'wpcustom-cursors' );
																								echo esc_attr( $cursor->cursor_shape );
																							?>
																				" class="list-shape-image" />
									<?php
							}
							break;
						case 'image':
							if ( str_contains( $cursor->cursor_shape, 'created' ) ) {
								$id = intval( substr( $cursor->cursor_shape, 8 ) );
								global $wpdb;
								$tablename      = $wpdb->prefix . 'created_cursors';
								$prepared_query = $wpdb->prepare( 'SELECT * FROM %i WHERE cursor_id = %d', array( $tablename, $id ) );
								$created_cursor = $wpdb->get_row( $prepared_query );
								$stripped       = stripslashes( $created_cursor->cursor_options );
								$decoded        = json_decode( $stripped, false );
								?>
																				<label class="created-cursor-label image" style="--width: <?php echo esc_attr( $decoded->width ); ?>px; --background: <?php echo esc_attr( $decoded->background ); ?>px; --color: <?php echo esc_attr( $decoded->color ); ?>; --radius: <?php echo esc_attr( $decoded->radius ); ?>px; --padding: <?php echo esc_attr( $decoded->padding ); ?>px; --blending: <?php echo esc_attr( $decoded->blending ); ?>; --click-point-x: <?php echo esc_attr( $click_point_x ); ?>%; --click-point-y: <?php echo esc_attr( $click_point_y ); ?>%;"><div class="img-wrapper"><img src="<?php echo esc_url( $decoded->image_url ); ?>" class="img-fluid" /></div>
																				</label>
									<?php
							} else {
								?>
																			<img src="<?php echo esc_url( $cursor->cursor_image ); ?>" alt="<?php echo esc_html__( 'Cursor Image', 'wpcustom-cursors' ); ?>" class="list-cursor-image" />
									<?php
							}
							break;
						case 'text':
							if ( str_contains( $cursor->cursor_shape, 'created' ) ) {
								$id = intval( substr( $cursor->cursor_shape, 8 ) );
								global $wpdb;
								$tablename      = $wpdb->prefix . 'created_cursors';
								$prepared_query = $wpdb->prepare( 'SELECT * FROM %i WHERE cursor_id = %d', array( $tablename, $id ) );
								$created_cursor = $wpdb->get_row( $prepared_query );
								$stripped       = stripslashes( $created_cursor->cursor_options );
								$decoded        = json_decode( $stripped, false );
								?>
																				<label class="created-cursor-label text" style="--dot-fill: <?php echo esc_attr( $decoded->dot_color ); ?>; --text-width: <?php echo esc_attr( $decoded->width ); ?>px; --text-transfom: <?php echo esc_attr( $decoded->text_transform ); ?>; --font-weight: <?php echo esc_attr( $decoded->font_weight ); ?>; --text-color: <?php echo esc_attr( $decoded->text_color ); ?>; --font-size: <?php echo esc_attr( $decoded->font_size ); ?>px;--word-spacing: <?php echo esc_attr( $decoded->word_spacing ); ?>px;--animation-name: <?php echo esc_attr( $decoded->animation ); ?>;--animation-duration: <?php echo esc_attr( $decoded->animation_duration ); ?>s; --dot-width: <?php echo esc_attr( $decoded->dot_width ); ?>px;"><svg viewBox="0 0 500 500" id="svg_node"><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="textcircle" fill="none"></path><text dy="25" id="svg_text_cursor"><textPath xlink:href="#textcircle" id="textpath"><?php echo esc_html( $decoded->text ); ?></textPath></text><circle cx="250" cy="250" r="<?php echo esc_attr( $decoded->dot_width ); ?>" id="svg_circle_node"/></svg>
																				</label>
									<?php
							} else {
								?>
																				<label class="created-cursor-label text" style="--width: <?php echo esc_attr( $cursor->width ); ?>px;--text-color: <?php echo esc_attr( $cursor->color ); ?>;"><svg viewBox="0 0 500 500" id="svg_node"><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="textcircle" fill="none"></path><text dy="25" id="svg_text_cursor"><textPath xlink:href="#textcircle" id="textpath"><?php echo esc_html( $cursor->cursor_text ); ?></textPath></text></svg>
																				</label>
									<?php
							}
							break;
						case 'horizontal':
							if ( str_contains( $cursor->cursor_shape, 'created' ) ) {
								$id = intval( substr( $cursor->cursor_shape, 8 ) );
								global $wpdb;
								$tablename      = $wpdb->prefix . 'created_cursors';
								$prepared_query = $wpdb->prepare( 'SELECT * FROM %i WHERE cursor_id = %d', array( $tablename, $id ) );
								$created_cursor = $wpdb->get_row( $prepared_query );
								$stripped       = stripslashes( $created_cursor->cursor_options );
								$decoded        = json_decode( $stripped, false );
								?>
																				<label class="created-cursor-label horizontal" style="--bg-color: <?php echo esc_attr( $decoded->hr_bgcolor ); ?>; --hr-width: <?php echo esc_attr( $decoded->hr_width ); ?>px; --hr-transfom: <?php echo esc_attr( $decoded->hr_transform ); ?>; --hr-weight: <?php echo esc_attr( $decoded->hr_weight ); ?>; --hr-color: <?php echo esc_attr( $decoded->hr_color ); ?>; --hr-size: <?php echo esc_attr( $decoded->hr_size ); ?>px;--hr-spacing: <?php echo esc_attr( $decoded->hr_spacing ); ?>px;--hr-radius: <?php echo esc_attr( $decoded->hr_radius ); ?>px;--hr-padding: <?php echo esc_attr( $decoded->hr_padding ); ?>s; ">
																						<div class="hr-text"><?php echo esc_html( $decoded->hr_text ); ?></div>
																				</label>
									<?php
							}
							break;
					}
					?>
									</div>
									<div class="col-md-2">
								<div>
						<?php
						echo esc_html( ucfirst( $cursor->cursor_type ) );
						?>
														</div>
														<div>
					<?php
					switch ( $cursor->cursor_type ) {
						case 'shape':
							echo esc_html__( 'Color:', 'wpcustom-cursors' );
							if ( str_contains( $cursor->cursor_shape, 'created' ) ) {
									$id = intval( substr( $cursor->cursor_shape, 8 ) );
									global $wpdb;
									$tablename      = $wpdb->prefix . 'created_cursors';
									$prepared_query = $wpdb->prepare( 'SELECT * FROM %i WHERE cursor_id = %d', array( $tablename, $id ) );
									$created_cursor = $wpdb->get_row( $prepared_query );
									$stripped       = stripslashes( $created_cursor->cursor_options );
									$decoded        = json_decode( $stripped, false );
								?>
												<div class="color-dot" style="background-color: <?php echo esc_html( $decoded->fe_color ); ?>"></div>
												<div class="color-dot" style="background-color: <?php echo esc_html( $decoded->se_color ); ?>"></div>
									<?php
							} else {
								?>
												<div class="color-dot" style="background-color: <?php echo esc_html( $cursor->color ); ?>"></div>
								<?php
							}
							break;
						case 'image':
							echo esc_html__( 'Width: ', 'wpcustom-cursors' );
							if ( str_contains( $cursor->cursor_shape, 'created' ) ) {
								$id = intval( substr( $cursor->cursor_shape, 8 ) );
								global $wpdb;
								$tablename      = $wpdb->prefix . 'created_cursors';
								$prepared_query = $wpdb->prepare( 'SELECT * FROM %i WHERE cursor_id = %d', array( $tablename, $id ) );
								$created_cursor = $wpdb->get_row( $prepared_query );
								$stripped       = stripslashes( $created_cursor->cursor_options );
								$decoded        = json_decode( $stripped, false );
								?>
												<span class="small"><?php echo esc_html( $decoded->width ); ?>px</span>
								<?php
							} else {
								?>
												<span class="small"><?php echo esc_html( $cursor->width ); ?>px</span>
								<?php
							}
							break;
						case 'text':
							$id = intval( substr( $cursor->cursor_shape, 8 ) );
							global $wpdb;
							$tablename      = $wpdb->prefix . 'created_cursors';
							$prepared_query = $wpdb->prepare( 'SELECT * FROM %i WHERE cursor_id = %d', array( $tablename, $id ) );
							$created_cursor = $wpdb->get_row( $prepared_query );
							$stripped       = stripslashes( $created_cursor->cursor_options );
							$decoded        = json_decode( $stripped, false );
							echo esc_html__( 'Color:', 'wpcustom-cursors' );
							?>
											<div class="color-dot" style="background-color: <?php echo esc_html( $decoded->text_color ); ?>"></div>
							<?php
							break;
						case 'horizontal':
							$id = intval( substr( $cursor->cursor_shape, 8 ) );
							global $wpdb;
							$tablename      = $wpdb->prefix . 'created_cursors';
							$prepared_query = $wpdb->prepare( 'SELECT * FROM %i WHERE cursor_id = %d', array( $tablename, $id ) );
							$created_cursor = $wpdb->get_row( $prepared_query );
							$stripped       = stripslashes( $created_cursor->cursor_options );
							$decoded        = json_decode( $stripped, false );
							echo esc_html__( 'Color:', 'wpcustom-cursors' );
							?>
											<div class="color-dot" style="background-color: <?php echo esc_html( $decoded->hr_bgcolor ); ?>"></div>
							<?php
							break;
					}
					?>
														</div>
													</div>
													<div class="col-md-2">
														<div><?php echo esc_html__( 'Activate On:', 'wpcustom-cursors' ); ?></div>
					<?php
					if ( 0 === intval( $cursor->activate_on ) ) {
						echo esc_html__( 'Body', 'wpcustom-cursors' );
					} else {
						switch ( $cursor->selector_type ) {
							case 'tag':
								echo '&lt;' . esc_html( $cursor->selector_data ) . '&gt;';
								break;
							case 'class':
								echo '.' . esc_html( $cursor->selector_data );
								break;
							case 'id':
								echo '#' . esc_html( $cursor->selector_data );
								break;
							case 'attribute':
								echo '[' . esc_html( $cursor->selector_data ) . ']';
								break;
							default:
								echo esc_html__( 'No data!', 'wpcustom-cursors' );
								break;
						}
					}
					?>
													</div>
													<div class="col-md-6 text-end">
					<?php
					$bare_url     = menu_page_url( 'wpcc_add_new', false );
					$id           = intval( $cursor->cursor_id );
					$base_url     = $bare_url . '&edit_row=' . $id;
					$complete_url = wp_nonce_url( $base_url, 'edit-added-cursor' . $id, 'wpcc_edit_nonce' );
					?>
														<a href="<?php echo esc_url( $complete_url ); ?>" title="<?php echo esc_html__( 'Edit Cursor', 'wpcustom-cursors' ); ?>" class="wpcc-icon"><i class="ri-pencil-line ri-lg"></i></a>
														<form action="" class="d-inline-block" method="post">
															<input type="hidden" name="delete_row" value="<?php echo esc_html( $cursor->cursor_id ); ?>">
															<button type="submit" name="delete" title="<?php echo esc_html__( 'Delete Cursor', 'wpcustom-cursors' ); ?>" class="wpcc-icon"><i class="ri-close-fill ri-lg"></i></button>
					<?php wp_nonce_field( 'wpcc_delete_cursor', 'wpcc_delete_nonce' ); ?>
														</form>
													</div>
												</div>
					<?php
				}
				?>
										</div>
									</div>
				<?php
			} else {
				?>
									<div class="container mt-3">
										<div class="row">
											<div class="card bg-light py-3 rounded-3">
												<div class="card-body">
													<div class="row justify-content-center text-center">
														<div class="col-md-6">
															<img src="<?php echo esc_url( plugins_url( 'img/icons/no-cursor.svg', __FILE__ ) ); ?>" alt="<?php echo esc_html__( 'Add you first cursor!', 'wpcustom-cursors' ); ?>" class="img-fluid w-50" />
														</div>
													</div>
													<div class="row justify-content-center">
														<div class="col-md-12 text-center">
															<div class="mt-3 fw-light">
																<h3 class="text-body h4"><?php echo esc_html__( 'Welcome to WP Custom Cursors!', 'wpcustom-cursors' ); ?></h3>
																<div class="text-body-secondary"><?php echo esc_html__( 'Elevate your website\'s engagement with unique and personalized cursors!', 'wpcustom-cursors' ); ?></div>
																<div class="d-flex justify-content-center mb-3 mt-4 gap-4">
																	<a href="<?php menu_page_url( 'wpcc_add_new', true ); ?>" class="text-decoration-none fw-normal link-unstyled">	
																		<div class="d-inline-flex align-items-center bg-white p-4 rounded-3 button-box">
																			<i class="ri-cursor-fill ri-2x text-brand"></i> <span class="text-body-secondary ps-2"><?php echo esc_html__( 'Use Available Cursors', 'wpcustom-cursors' ); ?></span>
																		</div>
																	</a>
																	<a href="<?php menu_page_url( 'wpcc_cursor_maker', true ); ?>" class="text-decoration-none fw-normal link-unstyled">
																		<div class="d-inline-flex align-items-center bg-white p-4 rounded-3 button-box">
																			<i class="ri-tools-fill ri-2x text-brand"></i> <span class="text-body-secondary ps-2"><?php echo esc_html__( 'Create Your Own Cursor', 'wpcustom-cursors' ); ?></span>
																		</div>
																	</a>
																	<a href="<?php menu_page_url( 'wpcc_tuts', true ); ?>" class="text-decoration-none fw-normal link-unstyled">
																		<div class="d-inline-flex align-items-center bg-white p-4 rounded-3 button-box">
																			<i class="ri-play-circle-line ri-2x text-brand"></i> <span class="text-body-secondary ps-2"><?php echo esc_html__( 'Watch Tutorials', 'wpcustom-cursors' ); ?></span>
																		</div>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
				<?php
			}
			?>
				</div>
				<!-- End Body -->
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
			<?php
		}
		/**
		 * Render add new page
		 *
		 * @since 1.0.0
		 */
		function wpcc_render_add_new_page() {
			?>
			<div class="wt-page mt-3 me-4">
			<?php include_once 'partials/wp-custom-cursors-header.php'; ?>

				<!-- Body -->
				<div class="wt-body">
			<?php include_once 'partials/wp-custom-cursors-add-new.php'; ?>
				</div>
				<!-- End Body -->
			</div>
			<?php
		}
		/**
		 * Render cursor maker page
		 *
		 * @since 1.0.0
		 */
		function wpcc_render_cursor_maker_page() {
			?>
			<div class="wt-page mt-3 me-4">
			<?php include_once 'partials/wp-custom-cursors-header.php'; ?>
				<div class="wt-body">
			<?php include_once 'partials/wp-custom-cursors-cursor-maker.php'; ?>
				</div>
			</div>
			<?php
		}
		/**
		 * Render tutorials page
		 *
		 * @since 1.0.0
		 */
		function wpcc_render_tuts_page() {
			?>
			<div class="wt-page mt-3 me-4">
			<?php include_once 'partials/wp-custom-cursors-header.php'; ?>
				<div class="wt-body">
			<?php include_once 'partials/wp-custom-cursors-tuts.php'; ?>
				</div>
			</div>
			<?php
		}
	}
	/**
	 * Add plugin settings link
	 *
	 * @param string $links Link to the plugin settings.
	 * @since 2.2.4
	 */
	public function add_plugin_settings_link( $links ) {
		$links[] = '<a href="' .
		admin_url( 'admin.php?page=wp_custom_cursors' ) .
		'">' . esc_html__( 'Settings' ) . '</a>';
		return $links;
	}
	/**
	 * Cursor create/remove/update functions
	 *
	 * @since 3.0
	 */
	public function crud_cursor() {
		if ( isset( $_POST['add'] ) && check_admin_referer( 'wpcc_add_new_cursor', 'wpcc_add_new_nonce' ) ) {
			global $wpdb;
			$tablename      = $wpdb->prefix . 'added_cursors';
			$cursor_type    = isset( $_POST['cursor_type'] ) ? sanitize_text_field( wp_unslash( $_POST['cursor_type'] ) ) : 'shape';
			$cursor_shape   = isset( $_POST['cursor_shape'] ) ? sanitize_text_field( wp_unslash( $_POST['cursor_shape'] ) ) : 1;
			$default_cursor = isset( $_POST['default_cursor'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['default_cursor'] ) ) ) : 0;
			$activate_on    = isset( $_POST['activate_on'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['activate_on'] ) ) ) : 0;
			$selector_type  = isset( $_POST['selector_type'] ) ? sanitize_text_field( wp_unslash( $_POST['selector_type'] ) ) : 'tag';
			$selector_data  = isset( $_POST['selector_data'] ) ? sanitize_text_field( wp_unslash( $_POST['selector_data'] ) ) : 'body';
			if ( '' === $selector_data ) {
				$selector_data = 'body';
			}
			$color = isset( $_POST['color'] ) ? sanitize_text_field( wp_unslash( $_POST['color'] ) ) : 'black';
			$width = isset( $_POST['width'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['width'] ) ) ) : 30;
			if ( 0 === $width ) {
				$width = 30;
			}
			$blending_mode = isset( $_POST['blending_mode'] ) ? sanitize_text_field( wp_unslash( $_POST['blending_mode'] ) ) : 'normal';
			$hide_tablet   = isset( $_POST['hide_tablet'] ) ? sanitize_text_field( wp_unslash( $_POST['hide_tablet'] ) ) : 'off';
			$hide_mobile   = isset( $_POST['hide_mobile'] ) ? sanitize_text_field( wp_unslash( $_POST['hide_mobile'] ) ) : 'off';
			$hide_admin    = isset( $_POST['hide_admin'] ) ? sanitize_text_field( wp_unslash( $_POST['hide_admin'] ) ) : 'off';
			$success       = $wpdb->insert(
				$tablename,
				array(
					'cursor_type'    => $cursor_type,
					'cursor_shape'   => $cursor_shape,
					'default_cursor' => $default_cursor,
					'color'          => $color,
					'width'          => $width,
					'blending_mode'  => $blending_mode,
					'hide_tablet'    => $hide_tablet,
					'hide_mobile'    => $hide_mobile,
					'hide_admin'     => $hide_admin,
					'activate_on'    => $activate_on,
					'selector_type'  => $selector_type,
					'selector_data'  => $selector_data,
				),
				array( '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
			);
			if ( false !== $success ) {
				if ( wp_safe_redirect( admin_url( 'admin.php?page=wp_custom_cursors' ) ) ) {
					exit;
				}
			} else {
				echo esc_html(
					'<div class="container">
						<div class="row">
							<div class="col">
								<div class="alert alert-warning" role="alert">
								  ' . esc_html__( 'The cursor was not added!', 'wpcustom-cursors' ) . '
								</div>
							</div>
						</div>
					 </div>'
				);
			}
		}
		if ( isset( $_POST['update'] ) && check_admin_referer( 'wpcc_add_new_cursor', 'wpcc_add_new_nonce' ) ) {
			global $wpdb;
			$tablename      = $wpdb->prefix . 'added_cursors';
			$cursor_type    = isset( $_POST['cursor_type'] ) ? sanitize_text_field( wp_unslash( $_POST['cursor_type'] ) ) : 'shape';
			$cursor_shape   = isset( $_POST['cursor_shape'] ) ? sanitize_text_field( wp_unslash( $_POST['cursor_shape'] ) ) : 1;
			$default_cursor = isset( $_POST['default_cursor'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['default_cursor'] ) ) ) : 0;
			$activate_on    = isset( $_POST['activate_on'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['activate_on'] ) ) ) : 0;
			$selector_type  = isset( $_POST['selector_type'] ) ? sanitize_text_field( wp_unslash( $_POST['selector_type'] ) ) : 'tag';
			$selector_data  = isset( $_POST['selector_data'] ) ? sanitize_text_field( wp_unslash( $_POST['selector_data'] ) ) : 'body';
			if ( '' === $selector_data ) {
				$selector_data = 'body';
			}
			$color = isset( $_POST['color'] ) ? sanitize_text_field( wp_unslash( $_POST['color'] ) ) : 'black';
			$width = isset( $_POST['width'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['width'] ) ) ) : 30;
			if ( 0 === $width ) {
				$width = 30;
			}
			$blending_mode = isset( $_POST['blending_mode'] ) ? sanitize_text_field( wp_unslash( $_POST['blending_mode'] ) ) : 'normal';
			$hide_tablet   = isset( $_POST['hide_tablet'] ) ? sanitize_text_field( wp_unslash( $_POST['hide_tablet'] ) ) : 'off';
			$hide_mobile   = isset( $_POST['hide_mobile'] ) ? sanitize_text_field( wp_unslash( $_POST['hide_mobile'] ) ) : 'off';
			$hide_admin    = isset( $_POST['hide_admin'] ) ? sanitize_text_field( wp_unslash( $_POST['hide_admin'] ) ) : 'off';
			$row_id        = isset( $_POST['update_id'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['update_id'] ) ) ) : null;
			$success       = $wpdb->update(
				$tablename,
				array(
					'cursor_type'    => $cursor_type,
					'cursor_shape'   => $cursor_shape,
					'default_cursor' => $default_cursor,
					'color'          => $color,
					'width'          => $width,
					'blending_mode'  => $blending_mode,
					'hide_tablet'    => $hide_tablet,
					'hide_mobile'    => $hide_mobile,
					'hide_admin'     => $hide_admin,
					'activate_on'    => $activate_on,
					'selector_type'  => $selector_type,
					'selector_data'  => $selector_data,
				),
				array( 'cursor_id' => $row_id ),
				array( '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s' )
			);

			if ( false !== $success ) {
				if ( wp_safe_redirect( admin_url( 'admin.php?page=wp_custom_cursors' ) ) ) {
					exit;
				}
			} else {
				echo esc_html(
					'<div class="container">
						<div class="row">
							<div class="col">
								<div class="alert alert-warning" role="alert">
								  ' . esc_html__( 'The cursor was not updated!', 'wpcustom-cursors' ) . '
								</div>
							</div>
						</div>
					 </div>'
				);
			}
		}
		if ( isset( $_POST['delete'] ) && check_admin_referer( 'wpcc_delete_cursor', 'wpcc_delete_nonce' ) ) {
			global $wpdb;
			$tablename  = $wpdb->prefix . 'added_cursors';
			$delete_row = isset( $_POST['delete_row'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['delete_row'] ) ) ) : null;
			$sql        = $wpdb->prepare( 'DELETE from %i WHERE cursor_id = %d', array( $tablename, $delete_row ) );
			$deleted    = $wpdb->query( $sql );
			if ( $deleted ) {
				?>
				<script>
					(function(){
						window.addEventListener('DOMContentLoaded', function(event) {
							window.addEventListener('load', function(event) {
								const cursorToast = document.getElementById('cursor_toast');
								if (cursorToast) {
										const toast = new bootstrap.Toast(cursorToast)
									toast.show();
								}
							});
						});
					})();
				</script>
				<?php
			} else {
				echo esc_html(
					'<div class="container">
						<div class="row">
							<div class="col">
								<div class="alert alert-warning" role="alert">
								  ' . esc_html__( 'The cursor was not deleted!', 'wpcustom-cursors' ) . '
								</div>
							</div>
						</div>
					 </div>'
				);
			}
		}
		if ( isset( $_POST['create'] ) && check_admin_referer( 'wpcc_create_cursor', 'wpcc_create_nonce' ) ) {
			global $wpdb;
			$tablename      = $wpdb->prefix . 'created_cursors';
			$cursor_type    = isset( $_POST['cursor_type'] ) ? sanitize_text_field( wp_unslash( $_POST['cursor_type'] ) ) : 'shape';
			$cursor_options = isset( $_POST['cursor_options'] ) ? sanitize_text_field( wp_unslash( $_POST['cursor_options'] ) ) : '';
			$hover_cursors  = isset( $_POST['hover_cursors'] ) ? sanitize_text_field( wp_unslash( $_POST['hover_cursors'] ) ) : '';
			$success        = $wpdb->insert(
				$tablename,
				array(
					'cursor_type'    => $cursor_type,
					'cursor_options' => $cursor_options,
					'hover_cursors'  => $hover_cursors,
				),
				array( '%s', '%s', '%s' )
			);

			if ( false !== $success ) {
				if ( wp_safe_redirect( admin_url( 'admin.php?page=wpcc_add_new' ) ) ) {
					exit;
				}
			} else {
				echo esc_html(
					'<div class="container">
						<div class="row">
							<div class="col">
								<div class="alert alert-warning" role="alert">
								  ' . esc_html__( 'Could not create the cursor!', 'wpcustom-cursors' ) . '
								</div>
							</div>
						</div>
					 </div>'
				);
			}
		}
		if ( isset( $_POST['update_created'] ) && check_admin_referer( 'wpcc_create_cursor', 'wpcc_create_nonce' ) ) {
			global $wpdb;
			$tablename      = $wpdb->prefix . 'created_cursors';
			$cursor_type    = isset( $_POST['cursor_type'] ) ? sanitize_text_field( wp_unslash( $_POST['cursor_type'] ) ) : 'shape';
			$cursor_options = isset( $_POST['cursor_options'] ) ? sanitize_text_field( wp_unslash( $_POST['cursor_options'] ) ) : '';
			$hover_cursors  = isset( $_POST['hover_cursors'] ) ? sanitize_text_field( wp_unslash( $_POST['hover_cursors'] ) ) : '';
			$row_id         = isset( $_POST['update_id'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['update_id'] ) ) ) : null;
			$success        = $wpdb->update(
				$tablename,
				array(
					'cursor_type'    => $cursor_type,
					'cursor_options' => $cursor_options,
					'hover_cursors'  => $hover_cursors,
				),
				array( 'cursor_id' => $row_id ),
				array( '%s', '%s', '%s' )
			);
			if ( false !== $success ) {
				if ( wp_safe_redirect( admin_url( 'admin.php?page=wpcc_add_new' ) ) ) {
					exit;
				}
			} else {
				echo esc_html(
					'<div class="container">
						<div class="row">
							<div class="col">
								<div class="alert alert-warning" role="alert">
								  ' . esc_html__( 'Could not update the cursor!', 'wpcustom-cursors' ) . '
								</div>
							</div>
						</div>
					 </div>'
				);
			}
		}
		if ( isset( $_POST['delete_created'] ) && check_admin_referer( 'wpcc_delete_created_cursor', 'wpcc_delete_created_nonce' ) ) {
			global $wpdb;
			$tablename   = $wpdb->prefix . 'created_cursors';
			$delete_row  = isset( $_POST['delete_created'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['delete_created'] ) ) ) : null;
			$sql         = $wpdb->prepare( 'DELETE from %i WHERE cursor_id = %d', array( $tablename, $delete_row ) );
			$deleted     = $wpdb->query( $sql );
			$added_table = $wpdb->prefix . 'added_cursors';
			$check_shape = 'created-' . $delete_row;
			$added_sql   = $wpdb->get_results( $wpdb->prepare( 'SELECT * from %i WHERE cursor_shape = %s', array( $added_table, $check_shape ) ) );
			if ( $added_sql ) {
				$prepare_added_cursor = $wpdb->prepare( 'DELETE from %i WHERE cursor_shape = %s', array( $added_table, $check_shape ) );
				$deleted_added_cursor = $wpdb->query( $prepare_added_cursor );
			}
			if ( $deleted ) {
				?>
				<script>
					(function(){
						window.addEventListener('DOMContentLoaded', function(event) {
							window.addEventListener('load', function(event) {
								const cursorToast = document.getElementById('cursor_toast');
								if (cursorToast) {
										const toast = new bootstrap.Toast(cursorToast)
									toast.show();
								}
							});
						});
					})();
				</script>
				<?php
			} else {
				echo esc_html(
					'<div class="container">
						<div class="row">
							<div class="col">
								<div class="alert alert-warning" role="alert">
								  ' . esc_html__( 'Could not remove the cursor!', 'wpcustom-cursors' ) . '
								</div>
							</div>
						</div>
					 </div>'
				);
			}
		}
	}
}
