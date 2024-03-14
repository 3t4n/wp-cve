<?php
/**
 * Adds and controls pointers for contextual help/tutorials
 *
 * @package UpStream
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UpStream_Admin_Pointers Class.
 */
class UpStream_Admin_Pointers {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'admin_notices', array( $this, 'first_project' ) );
		add_filter( 'admin_notices', array( $this, 'check_addons' ) );
		add_filter( 'upstream_admin_pointers-project', array( $this, 'register_pointers' ) );
		add_filter( 'upstream_admin_pointers-edit-project', array( $this, 'register_signup' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_pointers' ) );
	}

	/**
	 * Register Signup
	 *
	 * @return void
	 */
	public function register_signup() {
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}

		global $current_user;

		$content  = '<p id="upstream-pointer-signup-box">';
		$content .= __( 'Sign up for our UpStream Project Management tips and tricks newsletter and get the UpStream Customizer extension for free!', 'upstream' ) . '<br /><br />';
		$content .= '<label>';
		$content .= '<b>' . __( 'Email Address:', 'upstream' ) . '</b>';
		$content .= '<br />';
		$content .= '<input type="text" id="upstream-pointer-email" value="' . esc_attr( $current_user->user_email ) . '" />';
		$content .= '</label>';
		$content .= '&nbsp; <a id="upstream-pointer-b1" onclick="return window.upstream_signup(this);" data-nonce="' . wp_create_nonce( 'upstream_signup' ) . '" class="button button-primary">Sign up</a>';
		$content .= '</p>';

		$pointers = array(
			'signup-newsletter-email3' => array(
				'target'  => '.wp-heading-inline',
				'options' => array(
					'content'  => '<h3>' . __( 'Get a FREE UpStream extension!' ) . '</h3>' . $content,
					'position' => array(
						'edge'  => 'top',
						'align' => 'top',
					),
				),
			),

		);

		return $pointers;
	}

	/**
	 * First Project
	 *
	 * @return void
	 */
	public function first_project() {
		// Make sure First Steps tutorial are not shown to Client Users first time they enter a project.
		$user = wp_get_current_user();
		if ( count( array_intersect( (array) $user->roles, array( 'administrator', 'upstream_manager' ) ) ) === 0 &&
			! current_user_can( 'edit_projects' )
		) {
			return;
		}

		// Get dismissed pointers. Shows whether we have done this or not already.
		$dismissed = explode( ',', (string) get_user_meta( $user->ID, 'dismissed_wp_pointers', true ) );
		if ( in_array( 'upstream_title', $dismissed ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'project' === $screen->id ) {
			$class    = 'notice notice-success is-dismissible';
			$message  = '<strong>' . __( 'Important!', 'upstream' ) . '</strong><br>';
			$message .= __(
				'As this is your first project, we have included a walkthrough guide.',
				'upstream'
			) . '<br>';
			$message .= __(
				'We <strong>strongly recommend</strong> that you take the time to follow it. ',
				'upstream'
			);
			$message .= __(
				'There is important info in the guide and it does not take too long.',
				'upstream'
			) . '<br>';
			$message .= '<small>' . __( '(you won\'t see this message or the guide again)', 'upstream' ) . '</small>';

			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
		}
	}

	/**
	 * Check Addons
	 *
	 * @return void
	 */
	public function check_addons() {
		$user = wp_get_current_user();
		if ( ! is_admin() || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		// only check if it is set.
		if ( isset( $_GET['upstream_dismiss_ver'] ) ) {
			update_user_meta( get_current_user_id(), 'upstream_hide_incompat_notices_' . UPSTREAM_VERSION, 'yes' );
		}

		if ( get_user_meta( get_current_user_id(), 'upstream_hide_incompat_notices_' . UPSTREAM_VERSION, true ) === 'yes' ) {
			return;
		}

		$addon_problems = array();
		global $upstream_addon_requirements;
		$required_addon_versions = $upstream_addon_requirements;

		foreach ( $required_addon_versions as $r ) {

			$pd = null;
			if ( @is_plugin_active( $r[0] ) ) {
				$pd = @get_plugin_data( WP_PLUGIN_DIR . '/' . $r[0] );
			} elseif ( @is_plugin_active( strtolower( $r[0] ) ) ) {
				$pd = @get_plugin_data( WP_PLUGIN_DIR . '/' . strtolower( $r[0] ) );
			}

			if ( $pd && ! empty( $pd['Name'] ) ) {

				$current_version = $pd['Version'];
				$reqd_version    = $r[1];

				if ( version_compare( $current_version, $reqd_version ) < 0 ) {
					$addon_problems[] = __( '- The installed version of ', 'upstream' ) .
						'<b style="font-weight: bold">' . $pd['Name'] . '</b>' . __( ' is ', 'upstream' ) . '<b style="font-weight: bold">' . $current_version . '</b>' .
						__( '. This version of UpStream requires version ', 'upstream' ) .
						'<b style="font-weight: bold">' . $reqd_version . '</b>' . __( ' or later.', 'upstream' );
				}
			}
		}

		if ( count( $addon_problems ) > 0 ) {
			$class    = 'notice';
			$message  = '<strong style="font-weight:bold;font-size:2em;color:#f00">' . __( 'Important!', 'upstream' ) . '</strong><br>';
			$message .= __(
				'This version of UpStream is not compatible with the following addon versions:',
				'upstream'
			) . '<ul><li>';
			$message .= implode( '</li><li>', $addon_problems );
			$message .= '</li></ul>For help with this message, <a href="https://upstreamplugin.com/docs/updating-upstream-addons/">click here</a>.';
			$message .= '<br><br><a href="?upstream_dismiss_ver=1">' . __( 'Do not show this message again for this version', 'upstream' ) . '</a>';

			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
		}
	}

	/**
	 * Pointers for creating a Project.
	 *
	 * @param  mixed $pointers Pointers.
	 */
	public function register_pointers( $pointers ) {
		// These pointers will chain - they will not be shown at once.
		$pointers = array(
			'upstream_title'         => array(
				'target'       => '#title',
				'next'         => 'upstream_status',
				'next_trigger' => array(
					'target' => '#title',
					'event'  => 'input',
				),
				'options'      => array(
					'content'  => '<h3>' . sprintf(
						// translators: %s: Project label.
						__( '%s Name', 'upstream' ),
						upstream_project_label()
					) . '</h3>' .
								'<p>' . sprintf(
									// translators: %s: Client label.
									__(
										'This is a required field and will be what your %s see on the frontend.',
										'upstream'
									),
									upstream_client_label()
								) . '</p>',
					'position' => array(
						'edge'  => 'top',
						'align' => 'left',
					),
				),
			),
			'upstream_status'        => array(
				'target'       => '#_upstream_project_status',
				'next'         => 'upstream_owner',
				'next_trigger' => array(
					'target' => '#_upstream_project_status',
					'event'  => 'change blur click',
				),
				'options'      => array(
					'content'  => '<h3>' . sprintf(
						// translators: %s: Status label.
						__( '%s Status', 'upstream' ),
						upstream_project_label()
					) . '</h3>' .
								'<p>' . sprintf(
									// translators: %s: Project label.
									__( 'Choose a status for this %s.', 'upstream' ),
									upstream_project_label()
								) . '</p>' .
								'<p>' . sprintf(
									__( 'Statuses are set within the UpStream Settings.', 'upstream' ),
									upstream_project_label()
								) . '</p>',
					'position' => array(
						'edge'  => 'right',
						'align' => 'left',
					),
				),
			),
			'upstream_owner'         => array(
				'target'       => '#_upstream_project_owner',
				'next'         => 'upstream_client',
				'next_trigger' => array(
					'target' => '#_upstream_project_owner',
					'event'  => 'change blur click',
				),
				'options'      => array(
					'content'  => '<h3>' . sprintf(
						// translators: %s: Project label.
						__( '%s Owner', 'upstream' ),
						upstream_project_label()
					) . '</h3>' .
					'<p>' . sprintf(
						// translators: %s: Project label.
						__( 'Choose the owner of this %s.', 'upstream' ),
						upstream_project_label()
					) . '</p>' .
					'<p>' . __(
						'Every user who has the Role of UpStream Manager, UpStream User or Administrator appears in this dropdown.',
						'upstream'
					) . '</p>' .
					'<p>' . sprintf(
						// translators: %s: Project label.
						__(
							'The selected owner will have full access and control of everything within this %s, regardless of their role.',
							'upstream'
						),
						upstream_project_label()
					) . '</p>',
					'position' => array(
						'edge'  => 'right',
						'align' => 'left',
					),
				),
			),
			'upstream_client'        => array(
				'target'       => '#_upstream_project_client',
				'next'         => 'upstream_client_users',
				'next_trigger' => array(
					'target' => '#_upstream_project_client',
					'event'  => 'change blur click',
				),
				'options'      => array(
					'content'  => '<h3>' . sprintf(
						'%s %s',
						upstream_project_label(),
						upstream_client_label()
					) . '</h3>' .
								'<p>' . sprintf(
									// translators: %1$s: Client label.
									// translators: %2$s: Project label.
									__( 'Choose the %1$s of this %2$s.', 'upstream' ),
									upstream_client_label(),
									upstream_project_label()
								) . '</p>' .
								'<p>' . sprintf(
									// translators: %s: Client label.
									__(
										'If there are no %s here, you need to add one first by clicking on <strong>New Client</strong> in the sidebar.',
										'upstream'
									),
									upstream_client_label_plural()
								) . '</p>',
					'position' => array(
						'edge'  => 'right',
						'align' => 'left',
					),
				),
			),
			'upstream_client_users'  => array(
				'target'       => '.cmb2-id--upstream-project-client-users',
				'next'         => 'upstream_project_start',
				'next_trigger' => array(
					'target' => '.cmb2-id--upstream-project-client-users',
					'event'  => 'change blur click',
				),
				'options'      => array(
					'content'  => '<h3>' . sprintf(
						// translators: %s: Client label.
						__( '%s Users', 'upstream' ),
						upstream_client_label()
					) . '</h3>' .
					'<p>' . sprintf(
						// translators: %1$s: Client label.
						// translators: %2$s: Project label.
						__(
							'Tick the %1$s Users who will have access to this %2$s.',
							'upstream'
						),
						upstream_client_label(),
						upstream_project_label()
					) . '</p>' .
					'<p>' . sprintf(
						// translators: %s: Project label.
						__(
							'The selected Users can then login using their email address and the password that is set within the %s.',
							'upstream'
						),
						upstream_client_label()
					) . '</p>' .
					'<p>' . sprintf(
						// translators: %1$s: Client label plural.
						// translators: %2$s: Client label.
						__(
							'If there are no %1$s Users here, you need to add one first by editing your %2$s',
							'upstream'
						),
						upstream_client_label_plural(),
						upstream_client_label()
					) . '</p>',
					'position' => array(
						'edge'  => 'right',
						'align' => 'left',
					),
				),
			),
			'upstream_project_start' => array(
				'target'       => '#_upstream_project_start',
				'next'         => 'upstream_milestones',
				'next_trigger' => array(
					'target' => '#_upstream_project_end',
					'event'  => 'change blur click',
				),
				'options'      => array(
					'content'  => '<h3>' . sprintf(
						// translators: %s: Project label.
						__( '%s Dates', 'upstream' ),
						upstream_project_label()
					) . '</h3>' .
					'<p>' . sprintf(
						// translators: %s: Project label.
						__(
							'Add the projected start and finish dates for this %s.',
							'upstream'
						),
						upstream_project_label()
					) . '</p>',
					'position' => array(
						'edge'  => 'right',
						'align' => 'left',
					),
				),
			),
			'upstream_milestones'    => array(
				'target'  => '#_upstream_project_milestones',
				'options' => array(
					'content'  => '<h3>' . sprintf( '%s', upstream_milestone_label_plural() ) . '</h3>' .
								'<p>' . sprintf(
									// translators: %s: upstream_milestone_label_plural.
									__( 'You can now start to add your %s.', 'upstream' ),
									upstream_milestone_label_plural()
								) . '</p>' .
								'<p>' . sprintf(
									// translators: %1$s: upstream_milestone_label_plural.
									// translators: %2$s: upstream_project_label.
									// translators: %3$s: upstream_milestone_label_plural.
									// translators: %4$s: upstream_task_label_plural.
									__(
										'Once you\'ve added your %1$s, you should now Publish/Update the %2$s. This ensures that all %3$s will be available within the %4$s.',
										'upstream'
									),
									upstream_milestone_label_plural(),
									upstream_project_label(),
									upstream_milestone_label_plural(),
									upstream_task_label_plural()
								) . '</p>' .
								'<p>' . sprintf(
									// translators: %s: upstream_milestone_label_plural.
									__(
										'If there are no %s in the dropdown, add them by editing the <strong>UpStream Settings</strong>.',
										'upstream'
									),
									upstream_milestone_label_plural()
								) . '</p>',
					'position' => array(
						'edge'  => 'bottom',
						'align' => 'top',
					),
				),
			),

		);

		return $pointers;
	}

	/**
	 * Enqueue pointers and add script to page.
	 *
	 * @param array $pointers Pointers.
	 */
	public function enqueue_pointers( $pointers ) {
		$screen   = get_current_screen();
		$screen_id = $screen->id;

		// Get pointers for this screen.
		$pointers = apply_filters( 'upstream_admin_pointers-' . $screen_id, array() );

		if ( ! $pointers || ! is_array( $pointers ) ) {
			return;
		}

		// Get dismissed pointers.
		$dismissed      = explode(
			',',
			(string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true )
		);
		$valid_pointers = array();

		// Check pointers and remove dismissed ones.
		foreach ( $pointers as $pointer_id => $pointer ) {

			// Sanity check.
			if ( in_array(
				$pointer_id,
				$dismissed
			) || empty( $pointer ) || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) ) {
				continue;
			}

			$pointer['pointer_id'] = $pointer_id;

			// Add the pointer to $valid_pointers array.
			$valid_pointers['pointers'][ $pointer_id ] = $pointer;
		}

		// No valid pointers? Stop here.
		if ( empty( $valid_pointers ) ) {
			return;
		}

		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );

		$valid_pointers = wp_json_encode( $valid_pointers );

		$return = wp_add_inline_script(
			'wp-pointer',
			"
            jQuery( function( $ ) {
                var wc_pointers = {$valid_pointers};

                setTimeout( init_wc_pointers, 2000 );

                function init_wc_pointers() {
                    $.each( wc_pointers.pointers, function( i ) {
                        show_wc_pointer( i );
                        return false;
                    });
                }

                function show_wc_pointer( id ) {
                    var pointer = wc_pointers.pointers[ id ];

                    var options = $.extend( pointer.options, {
                        close: function() {
                            $.post( ajaxurl, {
                                pointer: pointer.pointer_id,
                                action: 'dismiss-wp-pointer'
                            });
                            if ( pointer.next ) {
                                show_wc_pointer( pointer.next );
                            }
                        }
                    } );
                    var this_pointer = $( pointer.target ).pointer( options );
                    this_pointer.pointer( 'open' );

                    if ( pointer.next_trigger ) {
                        $( pointer.next_trigger.target ).on( pointer.next_trigger.event, function() {
                            setTimeout( function() { this_pointer.pointer( 'close' ); }, 400 );
                        });
                    }
                }
            });
        "
		);
	}
}

new UpStream_Admin_Pointers();
