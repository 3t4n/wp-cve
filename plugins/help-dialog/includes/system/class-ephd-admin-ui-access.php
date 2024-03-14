<?php
/**
 * Admin UI Access
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Admin_UI_Access {

	// Contributor capability
	const EPHD_WP_CONTRIBUTOR_CAPABILITY = 'edit_posts';

	// Author capability
	const EPHD_WP_AUTHOR_CAPABILITY = 'publish_posts';

	// Editor capability
	const EPHD_WP_EDITOR_CAPABILITY = 'manage_categories';

	// Admin capability
	const EPHD_ADMIN_CAPABILITY = 'manage_options';

	// Allowed Contexts list
	const ADMIN_UI_CONTEXTS = array(
		'admin_ephd_access_admin_pages_read',
	);

	/**
	 * Get capability for a certain context based on settings;
	 * If a few contexts are passed, then return Editor capability if any of the contexts is allowed for Editor (used to set capability for a tab that contains multiple contexts)
	 *
	 * @param $contexts
	 *
	 * @return string
	 */
	public static function get_context_required_capability( $contexts ) {

		if ( ! is_array( $contexts ) ) {
			$contexts = [$contexts];
		}

		$global_config = ephd_get_instance()->global_config_obj->get_config();
		$specs = EPHD_Config_Specs::get_fields_specification( EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME );

		// CAPABILITY LEVEL 1: check in settings if any of the contexts has capability 'Author'
		foreach ( $contexts as $context ) {

			// skip context that is used internally and which is not listed in the specs
			if ( ! isset( $global_config[$context] ) ) {
				continue;
			}

			// access has to be one of the allowed levels
			if ( $global_config[$context] == self::get_author_capability() && self::is_context_allowed( $global_config[$context], $specs, $context ) ) {
				return self::get_author_capability();
			}
		}

		// CAPABILITY LEVEL 2: check in settings if any of the contexts has capability 'Editor'
		foreach ( $contexts as $context ) {

			// skip context that is used internally and which is not listed in the specs
			if ( ! isset( $global_config[$context] ) ) {
				continue;
			}

			// access has to be one of the allowed levels
			if ( $global_config[$context] == self::get_editor_capability() && self::is_context_allowed( $global_config[$context], $specs, $context ) ) {
				return self::get_editor_capability();
			}
		}

		// HIGHEST CAPABILITY LEVEL: 'Admin'
		return self::get_admin_capability();
	}

	/**
	 * Check if the current user is allowed to access the given context
	 *
	 * @param $capability
	 * @param $specs
	 * @param $context
	 *
	 * @return bool
	 */
	private static function is_context_allowed( $capability, $specs, $context ) {

		$allowed_access_list = [];
		foreach ( $specs[$context]['allowed_access'] as $ix => $allowed_access ) {

			if ( $allowed_access == self::EPHD_WP_AUTHOR_CAPABILITY ) {
				$allowed_access_list[] = self::get_author_capability();
			} else if ( $allowed_access == self::EPHD_WP_EDITOR_CAPABILITY ) {
				$allowed_access_list[] = self::get_editor_capability();
			}
		}

		return in_array( $capability, $allowed_access_list );
	}

	/**
	 * Return true if given capability is in allowed capabilities list
	 *
	 * @param $capability
	 *
	 * @return bool
	 */
	private static function is_capability_in_allowed_list( $capability ) {
		return in_array( $capability, [ self::get_contributor_capability(), self::get_author_capability(), self::get_editor_capability(), self::get_admin_capability() ] );
	}

	/**
	 * Return actual capability for Contributor users
	 *
	 * @return string
	 * @noinspection PhpUnusedParameterInspection*/
	public static function get_contributor_capability() {
		return self::EPHD_WP_CONTRIBUTOR_CAPABILITY;
	}

	/**
	 * Return actual capability for Author users
	 *
	 * @return string
	 * @noinspection PhpUnusedParameterInspection*/
	public static function get_author_capability() {
		return self::EPHD_WP_AUTHOR_CAPABILITY;
	}

	/**
	 * Return actual capability for Editor users
	 *
	 * @return string
	 * @noinspection PhpUnusedParameterInspection
	 */
	public static function get_editor_capability() {
		return self::EPHD_WP_EDITOR_CAPABILITY;
	}

	/**
	 * Return actual capability for Admin users
	 *
	 * @return string
	 */
	public static function get_admin_capability() {
		return self::EPHD_ADMIN_CAPABILITY;
	}

	/**
	 * Get configuration array for Access Control settings boxes
	 *
	 * @param $global_config
	 * @return array
	 */
	public static function get_access_box( $global_config ) {

		$boxes_config = [];
		$specs = EPHD_Config_Specs::get_fields_specification( EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME );

		// Box: Admin Pages
		$box_config =
			array(
				'title' => $specs['admin_ephd_access_admin_pages_read']['label'],
				'html' => self::radio_buttons_vertical_access_control( array(
					'name'              => 'admin_ephd_access_admin_pages_read',
					'radio_class'       => 'ephd-admin__radio-button-wrap',
					'input_group_class' => 'ephd-input-group',
					'return_html'       => true,
					'options'       => self::get_access_control_options( true ),
					'value'             => self::is_capability_in_allowed_list( $global_config['admin_ephd_access_admin_pages_read'] )
						? $global_config['admin_ephd_access_admin_pages_read']
						: self::get_admin_capability(),
                ) ) );

		return $box_config;
	}

	/**
	 * Get options list for Access Control settings
	 *
	 * @param false $include_author
	 *
	 * @return array
	 */
	private static function get_access_control_options( $include_author=false ) {

		$access_control_ptions = [];

		if ( $include_author ) {
			$access_control_ptions[self::EPHD_WP_AUTHOR_CAPABILITY] = self::get_admins_distinct_box() . self::get_editors_distinct_box() . self::get_authors_distinct_box() . self::get_users_with_capability_distinct_box( self::EPHD_WP_AUTHOR_CAPABILITY );
		}

		$access_control_ptions[self::EPHD_WP_EDITOR_CAPABILITY] = self::get_admins_distinct_box() . self::get_editors_distinct_box() . self::get_users_with_capability_distinct_box( self::EPHD_WP_EDITOR_CAPABILITY );
		$access_control_ptions[self::EPHD_ADMIN_CAPABILITY]     = self::get_admins_distinct_box();

		return $access_control_ptions;
	}

	/**
	 * Handle saving of all options for Access Control feature
     *
     * @param $global_config
	 */
	public static function save_access_control( $global_config ) {

		// wp_die if nonce invalid or user does not have correct permission
		EPHD_Utilities::ajax_verify_nonce_and_admin_permission_or_error_die();

		// retrieve contexts and save
		$specs = EPHD_Config_Specs::get_fields_specification( EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME );
		foreach ( self::ADMIN_UI_CONTEXTS as $context ) {

			// skip context that is used internally and which is not listed in the specs
			if ( ! isset( $specs[$context] ) ) {
				continue;
			}

			// retrieve option value
			$context_value = EPHD_Utilities::post( $context, self::get_admin_capability() );

			// make sure we save value that is within certain capabilities list or set admin capability by default
			if ( ! self::is_capability_in_allowed_list( $context_value ) ) {
				$context_value = self::get_admin_capability();
			}

			// access has to be higher than default
			if ( empty( $specs[$context]['allowed_access'] ) || ( ! self::is_context_allowed( $context_value, $specs, $context ) && $context_value != self::get_admin_capability() ) ) {
				EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 417 ) );
			}

			// update option or die with error
			$global_config = ephd_get_instance()->global_config_obj->set_value( $context, $context_value );
			if ( is_wp_error( $global_config ) ) {
				EPHD_Utilities::ajax_show_error_die( EPHD_Utilities::report_generic_error( 416 ) );
			}
		}

		// we are done here
		// EPHD_Utilities::ajax_show_info_die( esc_html__( 'Configuration saved', 'echo-knowledge-base' ) );
        return $global_config;
	}
	private static function get_admins_distinct_box() {
		return sprintf( __( '%sAdmins%s', 'help-dialog' ), '<span class="ephd-admin__distinct-box ephd-admin__distinct-box--high">', '</span>' );
	}

	private static function get_editors_distinct_box() {
		return sprintf( __( '%sEditors%s', 'help-dialog' ), '<span class="ephd-admin__distinct-box ephd-admin__distinct-box--middle">', '</span>' );
	}

	private static function get_authors_distinct_box() {
		return sprintf( __( '%sAuthors%s', 'help-dialog' ), '<span class="ephd-admin__distinct-box ephd-admin__distinct-box--low">', '</span>' );
	}

	private static function get_users_with_capability_distinct_box( $capability ) {
		return sprintf( __( '%susers with "%s" capability%s', 'help-dialog' ), '<span class="ephd-admin__distinct-box ephd-admin__distinct-box--lowest">', $capability, '</span>' );
	}

	/**
	 * Renders several HTML radio buttons in a column
	 *
	 * @param array $args
	 *
	 * @return false|string
	 */
	private static function radio_buttons_vertical_access_control( $args = array() ) {

		$defaults = array(
			'id'                => 'radio',
			'name'              => 'radio-buttons',
			'data'              => array()
		);
		$args = EPHD_HTML_Elements::add_defaults( $args, $defaults );
		$id =  esc_attr( $args['name'] );
		$ix = 0;

		$data_escaped = '';
		foreach ( $args['data'] as $key => $value ) {
			$data_escaped .= 'data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
		}

		if ( ! empty($args['data']['example_image']) ) {
			$args['input_group_class'] =  $args['input_group_class'] . ' ephd-wizard-radio-btn-vertical-example ';
		}

		ob_start();		?>

		<div class="config-input-group <?php echo esc_attr( $args['input_group_class'] ); ?>" id="<?php echo $id; ?>_group">		<?php

			if ( ! empty($args['data']['example_image']) ) {
				echo '<div class="ephd-wizard-radio-btn-vertical-example__icon ephdfa ephdfa-eye"></div>';
			}

			if ( ! empty($args['label']) ) {     ?>
				<span class="main_label <?php echo esc_attr( $args['main_label_class'] ); ?>">
					<?php echo esc_html( $args['label'] ); ?>
				</span>            <?php
			}                       ?>

			<div class="radio-buttons-vertical <?php echo esc_attr( $args['input_class'] ); ?>" id="<?php echo $id; ?>">
				<ul>	                <?php

					foreach( $args['options'] as $key => $label ) {
						$checked = checked( $key, $args['value'], false );		                ?>

						<li class="<?php echo esc_attr( $args['radio_class'] ); ?>">			                <?php

							$checked_class ='';
							if ( $args['value'] == $key ) {
								$checked_class = 'checked-radio';
							} ?>

							<div class="input_container config-col-1 <?php echo $checked_class; ?>">
								<input type="radio"
								       name="<?php echo esc_attr( $args['name'] ); ?>"
								       id="<?php echo $id . $ix; ?>"
								       value="<?php echo esc_attr( $key ); ?>"					                <?php
								echo $data_escaped . ' ' . $checked; ?> />
							</div>
							<label class="<?php echo esc_attr( $args['label_class'] ); ?> config-col-10" for="<?php echo $id . $ix; ?>">
								<?php echo wp_kses_post( $label ); ?>
							</label>
						</li>		                <?php

						$ix++;
					} //foreach	                ?>

				</ul>

			</div>

		</div>        <?php

		return ob_get_clean();
	}
}
