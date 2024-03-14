<?php
/**
 * UpStream_Metaboxes_Clients
 *
 * @package UpStream
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Cmb2Grid\Grid\Cmb2Grid;
use UpStream\Traits\Singleton;

/**
 * Clients Metabox Class.
 *
 * @package     UpStream
 * @subpackage  Admin\Metaboxes
 * @author      UpStream <https://upstreamplugin.com>
 * @copyright   Copyright (c) 2018 UpStream Project Management
 * @license     GPL-3
 * @since       1.11.0
 * @final
 */
final class UpStream_Metaboxes_Clients {

	use Singleton;

	/**
	 * The post type where this metabox will be used.
	 *
	 * @since   1.11.0
	 * @access  protected
	 * @static
	 *
	 * @var     string
	 */
	protected static $post_type = 'client';

	/**
	 * String that represents the singular form of the post type's name.
	 *
	 * @since   1.11.0
	 * @access  protected
	 * @static
	 *
	 * @var     string
	 */
	protected static $post_type_label_singular = null;

	/**
	 * String that represents the plural form of the post type's name.
	 *
	 * @since   1.11.0
	 * @access  protected
	 * @static
	 *
	 * @var     string
	 */
	protected static $post_type_label_plural = null;

	/**
	 * Prefix used on form fields.
	 *
	 * @since   1.11.0
	 * @access  protected
	 * @static
	 *
	 * @var     string
	 */
	protected static $prefix = '_upstream_client_';

	/**
	 * Class constructor.
	 *
	 * @since   1.11.0
	 */
	public function __construct() {
		self::$post_type_label_singular = upstream_client_label();
		self::$post_type_label_plural   = upstream_client_label_plural();

		self::attach_hooks();

		// Enqueues the default ThickBox assets.
		add_thickbox();

		// Render all inner metaboxes.
		self::render_metaboxes();
	}

	/**
	 * Attach all hooks.
	 *
	 * @since   1.13.6
	 * @static
	 */
	public static function attach_hooks() {
		// Define all ajax endpoints.
		$ajax_endpoints_schema = array(
			'remove_user'             => 'remove_user',
			'fetch_unassigned_users'  => 'fetch_unassigned_users',
			'add_existent_users'      => 'add_existent_users',
			'migrate_legacy_user'     => 'migrateLegacyUser',
			'discard_legacy_user'     => 'discardLegacyUser',
			'fetch_user_permissions'  => 'fetch_user_permissions',
			'update_user_permissions' => 'update_user_permissions',
		);

		foreach ( $ajax_endpoints_schema as $endpoint => $callback_name ) {
			add_action( 'wp_ajax_upstream:client.' . $endpoint, array( __CLASS__, $callback_name ) );
		}
	}

	/**
	 * Render all inner-metaboxes.
	 *
	 * @since   1.11.0
	 * @access  private
	 * @static
	 */
	private static function render_metaboxes() {
		self::render_details_metabox();
		self::render_logo_metabox();

		$metaboxes_callbacks_list = array( 'create_users_metabox' );
		foreach ( $metaboxes_callbacks_list as $callback_name ) {
			add_action( 'add_meta_boxes', array( __CLASS__, $callback_name ) );
		}
	}

	/**
	 * Renders the Details metabox using CMB2.
	 *
	 * @since   1.11.0
	 * @static
	 */
	public static function render_details_metabox() {
		$metabox = new_cmb2_box(
			array(
				'id'           => self::$prefix . 'details',
				'title'        => '<span class="dashicons dashicons-admin-generic"></span>' . esc_html__( 'Details', 'upstream' ),
				'object_types' => array( self::$post_type ),
				'context'      => 'side',
				'priority'     => 'high',
			)
		);

		$phone_field = $metabox->add_field(
			array(
				'name' => __( 'Phone Number', 'upstream' ),
				'id'   => self::$prefix . 'phone',
				'type' => 'text',
			)
		);

		$website_field = $metabox->add_field(
			array(
				'name' => __( 'Website', 'upstream' ),
				'id'   => self::$prefix . 'website',
				'type' => 'text_url',
			)
		);

		$address_field = $metabox->add_field(
			array(
				'name' => __( 'Address', 'upstream' ),
				'id'   => self::$prefix . 'address',
				'type' => 'textarea_small',
			)
		);

		$fields = array();

		$fields = apply_filters( 'upstream_client_metabox_fields', $fields );
		ksort( $fields );

		// loop through ordered fields and add them to the group.
		if ( $fields ) {
			foreach ( $fields as $key => $value ) {
				$fields[ $key ] = $metabox->add_field( $value );
			}
		}

		$metabox_grid     = new Cmb2Grid( $metabox );
		$metabox_grid_row = $metabox_grid->addRow( array( $phone_field, $website_field, $address_field ) );
	}

	/**
	 * Renders Logo metabox using CMB2.
	 *
	 * @since   1.11.0
	 * @static
	 */
	public static function render_logo_metabox() {
		$metabox = new_cmb2_box(
			array(
				'id'           => self::$prefix . 'client_logo',
				'title'        => '<span class="dashicons dashicons-format-image"></span>' . esc_html__( 'Logo', 'upstream' ),
				'object_types' => array( self::$post_type ),
				'context'      => 'side',
				'priority'     => 'core',
			)
		);

		$metabox->add_field(
			array(
				'id'         => self::$prefix . 'logo',
				'type'       => 'file',
				'name'       => __( 'Image URL', 'upstream' ),
				'query_args' => array(
					'type' => 'image/*',
				),
			)
		);
	}

	/**
	 * Renders the users metabox.
	 * This is where all client users are listed.
	 *
	 * @since   1.11.0
	 * @static
	 */
	public static function render_users_metabox() {
		$client_id  = get_the_id();
		$users_list = (array) self::get_users_from_client( $client_id ); ?>

		<div class="upstream-row">
			<a
					id="add-existent-user"
					name="<?php echo esc_attr__( 'Add Existing Users', 'upstream' ); ?>"
					href="#TB_inline?width=600&height=300&inlineId=modal-add-existent-user"
					class="thickbox button"
			><?php echo esc_html__( 'Add Existing Users', 'upstream' ); ?></a>
		</div>
		<div class="upstream-row">
			<table id="table-users" class="wp-list-table widefat fixed striped posts upstream-table">
				<thead>
				<tr>
					<th><?php echo esc_html__( 'Name', 'upstream' ); ?></th>
					<th><?php echo esc_html__( 'Email', 'upstream' ); ?></th>
					<th><?php echo esc_html__( 'Assigned by', 'upstream' ); ?></th>
					<th class="text-center"><?php echo esc_html__( 'Assigned at', 'upstream' ); ?></th>
					<th class="text-center"><?php echo esc_html__( 'Remove?', 'upstream' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				if ( count( $users_list ) > 0 ) :
					$date_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );

					foreach ( $users_list as $user ) :
						$assigned_at = new DateTime( $user->assigned_at );
						?>
						<tr data-id="<?php echo esc_attr( $user->id ); ?>">
							<td>
								<a title="
								<?php
								echo esc_attr(
									sprintf(
										// translaroes: %s: user name.
										( "Managing %s's Permissions" ),
										$user->name
									)
								);
								?>
									"
									href="#TB_inline?width=600&height=425&inlineId=modal-user-permissions"
									class="thickbox"><?php echo esc_html( $user->name ); ?></a>
							</td>
							<td><?php echo esc_html( $user->email ); ?></td>
							<td><?php echo esc_html( $user->assigned_by ); ?></td>
							<td class="text-center"><?php echo esc_html( $assigned_at->format( $date_format ) ); ?></td>
							<td class="text-center">
								<a href="#" onclick="javascript:void(0);" class="up-u-color-red" data-remove-user>
									<span class="dashicons dashicons-trash"></span>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr data-empty>
						<td colspan="5"><?php echo esc_html__( 'There are no users assigned yet.', 'upstream' ); ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>

			<p>
				<span
						class="dashicons dashicons-info"></span> 
						<?php
						echo esc_html__(
							'Removing a user only means that they will no longer be associated with this client. Their WordPress account will not be deleted.',
							'upstream'
						);
						?>
			</p>
		</div>

		<?php
		self::render_user_permissions_modal();
		self::render_add_existent_user_modal();
	}

	/**
	 * Retrieve all Client Users associated with a given client.
	 *
	 * @since   1.11.0
	 * @access  private
	 * @static
	 *
	 * @param   int $client_id The reference id.
	 *
	 * @return  array
	 */
	private static function get_users_from_client( $client_id ) {
		if ( (int) $client_id <= 0 ) {
			return array();
		}

		// Let's cache all users basic info so we don't have to query each one of them later.
		$rowset = (array) get_users(
			array(
				'fields' => array( 'ID', 'display_name', 'user_login', 'user_email' ),
			)
		);

		// Create our users hash map.
		$users = array();
		foreach ( $rowset as $row ) {
			$users[ (int) $row->ID ] = array(
				'id'    => (int) $row->ID,
				'name'  => $row->display_name,
				'email' => $row->user_email,
			);
		}
		unset( $rowset );

		$client_users_list     = array();
		$client_users_ids_list = array();

		// Retrieve all client users.
		$meta = (array) get_post_meta( $client_id, '_upstream_new_client_users' );
		if ( ! empty( $meta ) ) {
			foreach ( $meta[0] as $client_user ) {
				if ( ! empty( $client_user ) && is_array( $client_user ) && isset( $users[ $client_user['user_id'] ] ) && ! in_array(
					$client_user['user_id'],
					$client_users_ids_list
				) ) {
					$user = $users[ $client_user['user_id'] ];

					$user['assigned_at'] = $client_user['assigned_at'];
					$user['assigned_by'] = $users[ $client_user['assigned_by'] ]['name'];

					$client_users_list[]     = (object) $user;
					$client_users_ids_list[] = $client_user['user_id'];
				}
			}
		}

		return $client_users_list;
	}

	/**
	 * Renders the modal's html which is used to manage a given Client User's permissions.
	 *
	 * @since   1.11.0
	 * @access  private
	 * @static
	 */
	private static function render_user_permissions_modal() {
		?>
		<div id="modal-user-permissions" style="display: none;">
			<div id="form-user-permissions">
				<div>
					<h3><?php echo esc_html__( "UpStream's Custom Permissions", 'upstream' ); ?></h3>
					<table class="wp-list-table widefat fixed striped posts upstream-table">
						<thead>
						<tr>
							<th class="text-center" style="width: 20px;">
								<input type="checkbox"/>
							</th>
							<th><?php echo esc_html__( 'Permission', 'upstream' ); ?></th>
						</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div>
					<div class="up-form-group">
						<button
								type="submit"
								class="button button-primary"
								data-label="<?php echo esc_attr__( 'Update Permissions', 'upstream' ); ?>"
								data-loading-label="<?php echo esc_attr__( 'Updating...', 'upstream' ); ?>"
						><?php echo esc_html__( 'Update Permissions', 'upstream' ); ?></button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Renders the modal's html which is used to associate existent client users with a client.
	 *
	 * @since   1.11.0
	 * @access  private
	 * @static
	 */
	private static function render_add_existent_user_modal() {
		?>
		<div id="modal-add-existent-user" style="display: none;">
			<div class="upstream-row">
				<p>
				<?php
				echo sprintf(
					// translators: %s: UpStream Client User.
					esc_html__(
						'These are all the users assigned with the role <code>%s</code> and not related to this client yet.',
						'upstream'
					),
					esc_html__( 'UpStream Client User', 'upstream' )
				);
				?>
					</p>
			</div>
			<div class="upstream-row">
				<table id="table-add-existent-users" class="wp-list-table widefat fixed striped posts upstream-table">
					<thead>
					<tr>
						<th class="text-center" style="width: 20px;">
							<input type="checkbox"/>
						</th>
						<th><?php echo esc_html__( 'Name', 'upstream' ); ?></th>
						<th><?php echo esc_html__( 'Email', 'upstream' ); ?></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td colspan="3"><?php echo esc_html__( 'No users found.', 'upstream' ); ?></td>
					</tr>
					</tbody>
				</table>
			</div>
			<div class="upstream-row submit"></div>
		</div>
		<?php
	}

	/**
	 * It defines the Users metabox.
	 *
	 * @since   1.11.0
	 * @static
	 */
	public static function create_users_metabox() {
		add_meta_box(
			self::$prefix . 'users',
			'<span class="dashicons dashicons-groups"></span>' . esc_html__( 'Users', 'upstream' ),
			array( __CLASS__, 'render_users_metabox' ),
			self::$post_type,
			'normal'
		);
	}

	/**
	 * Renders the Legacy Users metabox.
	 *
	 * @since   1.11.0
	 * @static
	 */
	public static function render_legacy_users_metabox() {
		$client_id = upstream_post_id();

		$legacy_users_errors = get_post_meta( $client_id, '_upstream_client_legacy_users_errors' )[0];
		$legacy_users_meta   = get_post_meta( $client_id, '_upstream_client_users' )[0];
		$legacy_users        = array();

		foreach ( $legacy_users_meta as $a ) {
			$legacy_users[ $a['id'] ] = $a;
		}

		unset( $legacy_users_meta );
		?>
		<div class="upstream-row">
			<p>
			<?php
			echo esc_html__(
				'The users listed below are those old <code>UpStream Client Users</code> that could not be automatically converted/migrated to <code>WordPress Users</code> by UpStream for some reason. More details on the Disclaimer metabox.',
				'upstream'
			);
			?>
				</p>
		</div>
		<div class="upstream-row">
			<table id="table-legacy-users" class="wp-list-table widefat fixed striped posts upstream-table">
				<thead>
				<tr>
					<th><?php echo esc_html__( 'First Name', 'upstream' ); ?></th>
					<th><?php echo esc_html__( 'Last Name', 'upstream' ); ?></th>
					<th><?php echo esc_html__( 'Email', 'upstream' ); ?></th>
					<th><?php echo esc_html__( 'Phone', 'upstream' ); ?></th>
					<th class="text-center"><?php echo esc_html__( 'Migrate?', 'upstream' ); ?></th>
					<th class="text-center"><?php echo esc_html__( 'Discard?', 'upstream' ); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ( $legacy_users_errors as $legacy_user_id => $legacy_user_error ) :
					$user            = $legacy_users[ $legacy_user_id ];
					$user_first_name = isset( $user['fname'] ) ? trim( $user['fname'] ) : '';
					$user_last_name  = isset( $user['lname'] ) ? trim( $user['lname'] ) : '';
					$user_email      = isset( $user['email'] ) ? trim( $user['email'] ) : '';
					$user_phone      = isset( $user['phone'] ) ? trim( $user['phone'] ) : '';

					switch ( $legacy_user_error ) {
						case 'ERR_EMAIL_NOT_AVAILABLE':
							$error_message = __(
								'This email address is already being used by another user.',
								'upstream'
							);
							break;
						case 'ERR_EMPTY_EMAIL':
							$error_message = __( 'Email addresses cannot be empty.', 'upstream' );
							break;
						case 'ERR_INVALID_EMAIL':
							$error_message = __( 'Invalid email address.', 'upstream' );
							break;
						default:
							$error_message = $legacy_user_error;
							break;
					}

					$empty_value_string = '<i>' . esc_html__( 'empty', 'upstream' ) . '</i>';
					?>
					<tr data-id="<?php echo esc_attr( $legacy_user_id ); ?>">
						<td data-column="fname"><?php echo ! empty( $user_first_name ) ? esc_html( $user_first_name ) : esc_html( $empty_value_string ); ?></td>
						<td data-column="lname"><?php echo ! empty( $user_last_name ) ? esc_html( $user_last_name ) : esc_html( $empty_value_string ); ?></td>
						<td data-column="email"><?php echo ! empty( $user_email ) ? esc_html( $user_email ) : esc_html( $empty_value_string ); ?></td>
						<td data-column="phone"><?php echo ! empty( $user_phone ) ? esc_html( $user_phone ) : esc_html( $empty_value_string ); ?></td>
						<td class="text-center">
							<a name="<?php echo esc_attr__( 'Migrating Client User', 'upstream' ); ?>"
								href="#TB_inline?width=350&height=400&inlineId=modal-migrate-user" class="thickbox"
								data-modal-identifier="user-migration">
								<span class="dashicons dashicons-plus-alt"></span>
							</a>
						</td>
						<td class="text-center">
							<a href="#" onclick="javascript:void(0);" class="up-u-color-red"
								data-action="legacyUser:discard">
								<span class="dashicons dashicons-trash"></span>
							</a>
						</td>
					</tr>
					<tr data-id="<?php echo esc_attr( $legacy_user_id ); ?>">
						<td colspan="7">
							<span class="dashicons dashicons-warning"></span>&nbsp;<?php echo esc_html( $error_message ); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Ajax endpoint responsible for removing Client Users from a given client.
	 *
	 * @since   1.11.0
	 * @throws \Exception Exception.
	 * @static
	 */
	public static function remove_user() {
		header( 'Content-Type: application/json' );

		check_ajax_referer( 'upstream_admin_client_form', 'nonce' );

		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$response  = array(
			'success' => false,
			'err'     => null,
		);

		try {
			if ( ! upstream_admin_permissions( 'edit_clients' ) ) {
				throw new \Exception( __( "You're not allowed to do this.", 'upstream' ) );
			}

			if ( empty( $post_data ) || ! isset( $post_data['client'] ) ) {
				throw new \Exception( __( 'Invalid request.', 'upstream' ) );
			}

			$client_id = absint( $post_data['client'] );
			if ( $client_id <= 0 ) {
				throw new \Exception( __( 'Invalid Client ID.', 'upstream' ) );
			}

			$user_id = absint( @$post_data['user'] );
			if ( $user_id <= 0 ) {
				throw new \Exception( __( 'Invalid User ID.', 'upstream' ) );
			}

			$client_users_meta_key = '_upstream_new_client_users';
			$meta                  = (array) get_post_meta( $client_id, $client_users_meta_key );

			if ( ! empty( $meta ) ) {
				$new_client_users_list = array();
				foreach ( $meta[0] as $client_user ) {
					if ( ! empty( $client_user ) && is_array( $client_user ) ) {
						if ( (int) $client_user['user_id'] !== $user_id ) {
							$new_client_users_list[] = $client_user;
						}
					}
				}

				update_post_meta( $client_id, $client_users_meta_key, $new_client_users_list );
			}

			$response['success'] = true;
		} catch ( \Exception $e ) {
			$response['err'] = esc_html( $e->getMessage() );
		}

		echo wp_json_encode( $response );

		wp_die();
	}

	/**
	 * Ajax endpoint responsible for fetching all Client Users that are not related to
	 * the given client.
	 *
	 * @since   1.11.0
	 * @throws \Exception Exception.
	 * @static
	 */
	public static function fetch_unassigned_users() {
		header( 'Content-Type: application/json' );

		check_ajax_referer( 'upstream_admin_client_form', 'nonce' );

		$response = array(
			'success' => false,
			'data'    => array(),
			'err'     => null,
		);

		try {
			if ( ! upstream_admin_permissions( 'edit_clients' ) ) {
				throw new \Exception( __( "You're not allowed to do this.", 'upstream' ) );
			}

			if ( empty( $_GET ) || ! isset( $_GET['client'] ) ) {
				throw new \Exception( __( 'Invalid request.', 'upstream' ) );
			}

			$client_id = absint( $_GET['client'] );
			if ( $client_id <= 0 ) {
				throw new \Exception( __( 'Invalid Client ID.', 'upstream' ) );
			}

			$client_users      = (array) self::get_users_from_client( $client_id );
			$exclude_these_ids = array( get_current_user_id() );
			if ( count( $client_users ) > 0 ) {
				foreach ( $client_users as $client_user ) {
					$exclude_these_ids[] = $client_user->id;
				}
			}

			$rowset = (array) get_users(
				array(
					'exclude'  => $exclude_these_ids,
					'role__in' => array( 'upstream_client_user' ),
					'orderby'  => 'ID',
				)
			);

			global $wp_roles;

			foreach ( $rowset as $row ) {
				$user = array(
					'id'       => $row->ID,
					'name'     => $row->display_name,
					'username' => $row->user_login,
					'email'    => $row->user_email,
				);

				$response['data'][] = $user;
			}

			$response['success'] = true;
		} catch ( \Exception $e ) {
			$response['err'] = esc_html( $e->getMessage() );
		}

		echo wp_json_encode( $response );

		wp_die();
	}

	/**
	 * Ajax endpoint responsible for associating existent Client Users to a given client.
	 *
	 * @since   1.11.0
	 * @throws \Exception Exception.
	 * @static
	 */
	public static function add_existent_users() {
		header( 'Content-Type: application/json' );

		check_ajax_referer( 'upstream_admin_client_form', 'nonce' );

		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$response  = array(
			'success' => false,
			'data'    => array(),
			'err'     => null,
		);

		try {
			if ( ! upstream_admin_permissions( 'edit_clients' ) ) {
				throw new \Exception( __( "You're not allowed to do this.", 'upstream' ) );
			}

			if ( empty( $post_data ) || ! isset( $post_data['client'] ) ) {
				throw new \Exception( __( 'Invalid request.', 'upstream' ) );
			}

			$client_id = absint( $post_data['client'] );
			if ( $client_id <= 0 ) {
				throw new \Exception( __( 'Invalid Client ID.', 'upstream' ) );
			}

			if ( ! isset( $post_data['users'] ) && empty( $post_data['users'] ) ) {
				throw new \Exception( __( 'Users IDs cannot be empty.', 'upstream' ) );
			}

			$current_user  = get_userdata( get_current_user_id() );
			$now_timestamp = time();
			$now           = gmdate( 'Y-m-d H:i:s', $now_timestamp );

			$client_users_meta_key = '_upstream_new_client_users';
			$client_users_list     = array_filter( (array) get_post_meta( $client_id, $client_users_meta_key, true ) );
			$client_new_users_list = array();
			$users_ids_list        = array();

			// sanitize each element.
			if ( is_array( $post_data['users'] ) ) {
				foreach ( $post_data['users'] as $u ) {
					$users_ids_list[] = absint( $u );
				}
			}

			foreach ( $users_ids_list as $user_id ) {
				$user_id = (int) $user_id;
				if ( $user_id > 0 ) {
					$client_users_list[] = array(
						'user_id'     => $user_id,
						'assigned_by' => $current_user->ID,
						'assigned_at' => $now,
					);
				}
			}

			foreach ( $client_users_list as $client_user ) {
				$client_user            = (array) $client_user;
				$client_user['user_id'] = (int) $client_user['user_id'];

				if ( ! isset( $client_new_users_list[ $client_user['user_id'] ] ) ) {
					$client_new_users_list[ $client_user['user_id'] ] = $client_user;
				}
			}
			update_post_meta( $client_id, $client_users_meta_key, array_values( $client_new_users_list ) );

			global $wpdb;

			$rowset = (array) get_users(
				array(
					'fields'  => array( 'ID', 'display_name', 'user_login', 'user_email' ),
					'include' => $users_ids_list,
				)
			);

			$assigned_at = upstream_format_date( $now );

			foreach ( $rowset as $user ) {
				$response['data'][] = array(
					'id'          => (int) $user->ID,
					'name'        => $user->display_name,
					'email'       => $user->user_email,
					'assigned_by' => $current_user->display_name,
					'assigned_at' => $assigned_at,
				);
			}

			$response['success'] = true;
		} catch ( \Exception $e ) {
			$response['err'] = esc_html( $e->getMessage() );
		}

		echo wp_json_encode( $response );

		wp_die();
	}

	/**
	 * Ajax endpoint responsible for fetching all permissions a given Client User might have.
	 *
	 * @since   1.11.0
	 * @throws \Exception Exception.
	 * @static
	 */
	public static function fetch_user_permissions() {
		header( 'Content-Type: application/json' );

		check_ajax_referer( 'upstream_admin_client_form', 'nonce' );

		$response = array(
			'success' => false,
			'data'    => array(),
			'err'     => null,
		);

		try {
			if ( ! upstream_admin_permissions( 'edit_clients' ) ) {
				throw new \Exception( __( "You're not allowed to do this.", 'upstream' ) );
			}

			if ( empty( $_GET ) || ! isset( $_GET['client'] ) || ! isset( $_GET['user'] ) ) {
				throw new \Exception( __( 'Invalid request.', 'upstream' ) );
			}

			$client_id = absint( $_GET['client'] );
			if ( $client_id <= 0 ) {
				throw new \Exception( __( 'Invalid Client ID.', 'upstream' ) );
			}

			$client_user_id = absint( $_GET['user'] );
			if ( $client_user_id <= 0 ) {
				throw new \Exception( __( 'Invalid User ID.', 'upstream' ) );
			}

			if ( ! upstream_do_client_user_belongs_to_client( $client_user_id, $client_id ) ) {
				throw new \Exception( __( 'This Client User is not associated with this Client.', 'upstream' ) );
			}

			$response['data'] = array_values( upstream_get_client_user_permissions( $client_user_id ) );

			$response['success'] = true;
		} catch ( \Exception $e ) {
			$response['err'] = esc_html( $e->getMessage() );
		}

		echo wp_json_encode( $response );

		wp_die();
	}

	/**
	 * Ajax endpoint responsible for updating a given Client User permissions.
	 *
	 * @since   1.11.0
	 * @throws \Exception Exception.
	 * @static
	 */
	public static function update_user_permissions() {
		header( 'Content-Type: application/json' );

		check_ajax_referer( 'upstream_admin_client_form', 'nonce' );

		$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();
		$response  = array(
			'success' => false,
			'err'     => null,
		);

		try {
			if ( ! upstream_admin_permissions( 'edit_clients' ) ) {
				throw new \Exception( __( "You're not allowed to do this.", 'upstream' ) );
			}

			if ( empty( $post_data ) || ! isset( $post_data['client'] ) ) {
				throw new \Exception( __( 'Invalid request.', 'upstream' ) );
			}

			$client_id = absint( $post_data['client'] );
			if ( $client_id <= 0 ) {
				throw new \Exception( __( 'Invalid Client ID.', 'upstream' ) );
			}

			$client_user_id = isset( $post_data['user'] ) ? absint( $post_data['user'] ) : 0;
			if ( $client_user_id <= 0 ) {
				throw new \Exception( __( 'Invalid User ID.', 'upstream' ) );
			}

			if ( ! upstream_do_client_user_belongs_to_client( $client_user_id, $client_id ) ) {
				throw new \Exception( __( 'This Client User is not associated with this Client.', 'upstream' ) );
			}

			$client_user = new \WP_User( $client_user_id );
			if ( array_search( 'upstream_client_user', $client_user->roles ) === false ) {
				throw new \Exception( __( "This user doesn't seem to be a valid Client User.", 'upstream' ) );
			}

			if ( isset( $post_data['permissions'] ) && ! empty( $post_data['permissions'] ) ) {

				if ( is_array( $post_data['permissions'] ) ) {
					$new_permissions = \array_map( 'sanitize_text_field', $post_data['permissions'] );
				} else {
					$new_permissions = array( sanitize_text_field( $post_data['permissions'] ) );
				}

				$permissions = upstream_get_client_users_permissions();

				$denied_permissions = (array) array_diff( array_keys( $permissions ), $new_permissions );
				foreach ( $denied_permissions as $permission_key ) {
					/* Make sure this is a valid permission. */
					if ( isset( $permissions[ $permission_key ] ) ) {
						$client_user->add_cap( $permission_key, false );
					}
				}

				foreach ( $new_permissions as $permission_key ) {
					/* Make sure this is a valid permission. */
					if ( isset( $permissions[ $permission_key ] ) ) {
						$client_user->add_cap( $permission_key, true );
					}
				}
			}

			$response['success'] = true;
		} catch ( \Exception $e ) {
			$response['err'] = $e->getMessage();
		}

		echo wp_json_encode( $response );

		wp_die();
	}

}
