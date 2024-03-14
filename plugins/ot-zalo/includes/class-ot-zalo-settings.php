<?php

class OT_Zalo_Settings {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		add_options_page( __( 'OT Zalo Settings', 'ot-zalo' ), __( 'OT Zalo', 'ot-zalo' ), 'manage_options', 'ot-zalo', array(
			$this,
			'create_admin_page',
		) );
	}

	public function create_admin_page() {

		$this->options = get_option( 'ot_zalo' );

		$share_position = isset( $this->options[ 'share_position' ] ) ? esc_attr( $this->options[ 'share_position' ] ) : 'before';
		$share_layout   = isset( $this->options[ 'share_layout' ] ) ? esc_attr( $this->options[ 'share_layout' ] ) : 1;
		$share_color    = isset( $this->options[ 'share_color' ] ) ? esc_attr( $this->options[ 'share_color' ] ) : 'blue';

		$enable_chat  = isset( $this->options[ 'zalo_chat_enable' ] ) ? 'on' : '';
		$enable_share = isset( $this->options[ 'zalo_share_enable' ] ) ? 'on' : '';

		$post_types = array(
			'post'    => 'Post',
			'page'    => 'Page',
			'product' => 'Product',
		);

		$share_post_types = isset( $this->options[ 'zalo_share_enable_post_type' ] ) ? $this->options[ 'zalo_share_enable_post_type' ] : array();

		?>
		<div class="wrap">
			<h1><?php _e( 'OT Zalo Settings', 'ot-zalo' ); ?></h1>

			<form method="post" action="options.php">
				<?php settings_fields( 'ot_zalo' ); ?>
				<?php do_settings_sections( 'ot_zalo' ); ?>
				<h3><?php _e( 'Zalo Chat Widget', 'ot-zalo' ) ?></h3>
				<table class="form-table">

					<tr valign="top">
						<th scope="row"><?php _e( 'Zalo Official Account ID', 'ot-zalo' ); ?></th>
						<td>
							<input type="text" name="ot_zalo[zalo_oaid]" class="regular-text"
							       value="<?php echo isset( $this->options[ 'zalo_oaid' ] ) ? esc_attr( $this->options[ 'zalo_oaid' ] ) : ''; ?>"/>
							<p>
								<a href="http://developers.zalo.me/oa/openapi/manage/oas"
								   target="_blank"><?php _e( 'Get Zalo official account ID' ); ?></a>
							</p>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Enable Zalo Chat Widget', 'ot-zalo' ); ?></th>
						<td>
							<input <?php checked( esc_attr( $enable_chat ), 'on', true ); ?> type="checkbox"
							                                                                 class="checkbox"
							                                                                 name="ot_zalo[zalo_chat_enable]"/>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Welcome Message', 'ot-zalo' ); ?></th>
						<td>
							<input type="text" name="ot_zalo[zalo_wm]" class="regular-text"
							       value="<?php echo isset( $this->options[ 'zalo_wm' ] ) ? esc_attr( $this->options[ 'zalo_wm' ] ) : ''; ?>"/>
						</td>
					</tr>

				</table>

				<h3><?php _e( 'Zalo Share Button', 'ot-zalo' ) ?></h3>
				<table class="form-table">

					<tr valign="top">
						<th scope="row"><?php _e( 'Enable Zalo Share Button', 'ot-zalo' ); ?></th>
						<td>
							<input <?php checked( esc_attr( $enable_share ), 'on', true ); ?> type="checkbox"
							                                                                  class="checkbox"
							                                                                  name="ot_zalo[zalo_share_enable]"/>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Enable Zalo Share Button On Single', 'ot-zalo' ); ?></th>
						<td>

							<?php

							foreach ( $post_types as $key => $item ) :

								$checked = in_array( $key, $share_post_types ) ? 'checked="checked"' : '';

								?>

								<input type="checkbox" id="zalo_share_<?php echo $key; ?>"
								       name="ot_zalo[zalo_share_enable_post_type][]"
								       value="<?php echo $key; ?>" <?php echo $checked; ?>>
								<label for="zalo_share_<?php echo $key; ?>"><?php echo $item; ?></label>

							<?php endforeach; ?>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Zalo icon position', 'ot-zalo' ); ?></th>
						<td>
							<select class="regular-text" name="ot_zalo[share_position]">
								<option
									value="before" <?php selected( $share_position, 'before' ); ?>><?php _e( 'Before Content', 'ot-zalo' ); ?></option>
								<option
									value="after" <?php selected( $share_position, 'after' ); ?>><?php _e( 'After Content', 'ot-zalo' ); ?></option>
							</select>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row"><?php _e( 'Zalo Share Layout', 'ot-zalo' ); ?></th>
						<td>
							<select class="regular-text" name="ot_zalo[share_layout]">
								<option
									value="1" <?php selected( $share_layout, '1' ); ?>><?php _e( 'Layout 1', 'ot-zalo' ); ?></option>
								<option
									value="2" <?php selected( $share_layout, '2' ); ?>><?php _e( 'Layout 2', 'ot-zalo' ); ?></option>
								<option
									value="3" <?php selected( $share_layout, '3' ); ?>><?php _e( 'Layout 3', 'ot-zalo' ); ?></option>
								<option
									value="4" <?php selected( $share_layout, '4' ); ?>><?php _e( 'Layout 4', 'ot-zalo' ); ?></option>
								<option
									value="5" <?php selected( $share_layout, '5' ); ?>><?php _e( 'Layout 5', 'ot-zalo' ); ?></option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Zalo Share Color', 'ot-zalo' ); ?></th>
						<td>
							<select class="regular-text" name="ot_zalo[share_color]">
								<option
									value="blue" <?php selected( $share_color, 'blue' ); ?>><?php _e( 'Xanh', 'ot-zalo' ); ?></option>
								<option
									value="white" <?php selected( $share_color, 'white' ); ?>><?php _e( 'Trắng', 'ot-zalo' ); ?></option>
							</select>
						</td>
					</tr>

				</table>

				<?php submit_button(); ?>

			</form>
		</div>
		<?php
	}

	public function page_init() {
		register_setting( 'ot_zalo', 'ot_zalo', array( $this, 'sanitize' ) );
	}

	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input[ 'zalo_oaid' ] ) ) {
			$new_input[ 'zalo_oaid' ] = sanitize_text_field( $input[ 'zalo_oaid' ] );
		}

		if ( isset( $input[ 'zalo_chat_enable' ] ) ) {
			$new_input[ 'zalo_chat_enable' ] = sanitize_text_field( $input[ 'zalo_chat_enable' ] );
		}

		if ( isset( $input[ 'zalo_wm' ] ) ) {
			$new_input[ 'zalo_wm' ] = sanitize_text_field( $input[ 'zalo_wm' ] );
		}

		if ( isset( $input[ 'zalo_share_enable' ] ) ) {
			$new_input[ 'zalo_share_enable' ] = sanitize_text_field( $input[ 'zalo_share_enable' ] );
		}

		if ( isset( $input[ 'zalo_share_enable_post_type' ] ) ) {
			$new_input[ 'zalo_share_enable_post_type' ] = $input[ 'zalo_share_enable_post_type' ];
		}

		if ( isset( $input[ 'share_position' ] ) ) {
			$new_input[ 'share_position' ] = sanitize_text_field( $input[ 'share_position' ] );
		}

		if ( isset( $input[ 'share_layout' ] ) ) {
			$new_input[ 'share_layout' ] = sanitize_text_field( $input[ 'share_layout' ] );
		}

		if ( isset( $input[ 'share_color' ] ) ) {
			$new_input[ 'share_color' ] = sanitize_text_field( $input[ 'share_color' ] );
		}

		return $new_input;
	}
}

if ( is_admin() ) {
	new OT_Zalo_Settings();
}