<?php

namespace WP_VGWORT;

/**
 * Class that handles the edit metaboxes
 *
 * adds pixel management function in edit post in classic editor mode.
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Metabox {
	/**
	 * @var object holds plugin reference
	 */
	private object $plugin;

	/**
	 * constructor > add hooks
	 */
	public function __construct( object &$plugin ) {
        $this->plugin = $plugin;
		if ( ! self::is_gutenberg_active() ) {
			$this->add_hooks();
		}
	}

	/**
	 * Add the Actions for displaying and saving the metis metabox
	 *
	 * @return void
	 */
	private function add_hooks(): void {
		// add the metabox to edit pixel data in edit screens
		add_action( 'add_meta_boxes', [ $this, 'add_metis_metabox' ] );
		// when post save is triggered, save metis metabox data wp post meta (should be called before the admin class save_post)
		add_action( 'save_post', [ $this, 'save_metis_metabox' ], 5, 3 );
		// load needed js
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_script' ] );
		// register wp ajax action for manually assigning a pixel
		add_action( 'wp_ajax_wp_metis_metabox_manual_assign_pixel', [ $this, 'manual_assign_pixel_action' ] );
		// register wp ajax action for checking validity & ownership of a pixel
		add_action( 'wp_ajax_wp_metis_metabox_check_validity_and_ownership', [
			$this,
			'is_valid_and_ownership_check'
		] );
		add_action( 'wp_ajax_wp_metis_metabox_get_posts_count', [ $this, 'get_assigned_posts_count_ajax' ] );
	}

	/**
	 * Adds the metabox
	 *
	 * @return void
	 */
	public function add_metis_metabox(): void {
		add_meta_box(
			'wp_metis_edit_metabox',
			esc_html__( 'VG WORT Zählmarke', 'vgw-metis' ),
			[ $this, 'add_metis_metabox_html' ],
			null,
			'side',
			'high'
		);

	}

	/**
	 * renders the edit metis metabox form
	 *
	 * @param object $post the whole post object
	 *
	 * @return void
	 */
	public function add_metis_metabox_html( object $post ): void {
		// new post / page or edit existing?
		$isNew = $post->post_title == '' && $post->content == '' && $post->post_name == '' && $post->post_status == 'auto-draft';
		// information about the current screen (or maybe use $post->post_type?)
		$screen = get_current_screen();
		// auto add setting for current page
		$auto_add_setting = 'no';
		// set auto add setting accoirding to post type, extend for custom post types (if there will be a setting for custom post types)
		switch ( $screen->post_type ) {
			case 'page':
				$auto_add_setting = get_option( 'wp_metis_pixel_auto_add_pages', Common::AUTO_ADD_PAGES_DEFAULT );
				break;
			case 'post':
				$auto_add_setting = get_option( 'wp_metis_pixel_auto_add_posts', Common::AUTO_ADD_POSTS_DEFAULT );
				break;
		}

		$text_length = 0;
		$text_type   = Common::TEXT_TYPE_DEFAULT;
		$public_id   = '-';
		$private_id  = '-';
		$pixel       = null;

		// not on a new post / page > get text_length, text type from wp post meta and pixel data from metis db
		if ( ! $isNew ) {
			$text_length = get_post_meta( $post->ID, '_metis_text_length', true );
			$text_type   = get_post_meta( $post->ID, '_metis_text_type', true );
			$pixel       = Services::get_pixel_for_post( $post->ID, false );
		}

		if ( $pixel ) {
			$public_id  = $pixel->get_public_identification_id();
			$private_id = $pixel->get_private_identification_id();
		}

		$posts_count = DB_Pixels::get_assigned_posts_count( $public_id );

		?>
        <div class="wp_metis_metabox <?php echo $isNew ? 'new' : 'edit'; ?>">
			<?php if ( $isNew ) : ?>
                <p class="wp_metis_metabox_auto_add">
                    <label><?php esc_html_e( 'Zählmarke automatisch zuweisen', 'vgw-metis' ); ?></label>
                    <input type="radio" name="wp_metis_metabox_auto_add" id="wp_metis_metabox_auto_add_yes"
                           value="yes" <?php checked( 'yes', $auto_add_setting ); ?>>
                    <label for="wp_metis_metabox_auto_add_yes"><?php esc_html_e( 'Ja' ); ?></label>
                    <input type="radio" name="wp_metis_metabox_auto_add" id="wp_metis_metabox_auto_add_no"
                           value="no" <?php checked( 'no', $auto_add_setting ); ?>>
                    <label for="wp_metis_metabox_auto_add_no"><?php esc_html_e( 'Nein' ); ?></label>
                </p>
			<?php endif; ?>

			<?php if ( ! $isNew && $pixel && $pixel->active ) : ?>
                <p class="wp_metis_metabox_public_id">
                    <label><?php esc_html_e( 'Öffentlicher Identifikationscode', 'vgw-metis' ) ?></label>
					<?php esc_html_e( $public_id ); ?>
                </p>
			<?php endif; ?>

			<?php if ( ! $isNew && $pixel && $pixel->active ) : ?>
                <p class="wp_metis_metabox_private_id">
                    <label><?php esc_html_e( 'Privater Identifikationscode', 'vgw-metis' ) ?></label>
					<?php esc_html_e( $private_id ); ?>
                </p>
			<?php endif; ?>

			<?php if ( ! $isNew && $pixel && $pixel->active ) : ?>
                <p class="wp_metis_metabox_char_count">
                    <label><?php esc_html_e( 'Zeichenanzahl', 'vgw-metis' ) ?></label>
                    <span class="metis_char_count"><?php esc_html_e( $text_length ); ?>
                </p>
			<?php endif; ?>

            <p class="wp_metis_metabox_text_type">
                <label><?php esc_html_e( 'Art des Textes', 'vgw-metis' ) ?></label>
                <input type="radio" name="wp_metis_metabox_text_type" id="wp_metis_metabox_text_type_lyric"
                       value="<?php echo esc_attr( Common::TEXT_TYPE_LYRIC ); ?>" <?php checked( Common::TEXT_TYPE_LYRIC, $text_type ); ?>>
                <label for="wp_metis_metabox_text_type_lyric"><?php esc_html_e( 'Lyrik', 'vgw-metis' ) ?></label>
                <input type="radio" name="wp_metis_metabox_text_type" id="wp_metis_metabox_text_type_default"
                       value="<?php echo esc_attr( Common::TEXT_TYPE_DEFAULT ); ?>" <?php checked( Common::TEXT_TYPE_DEFAULT, $text_type ); ?>>
                <label for="wp_metis_metabox_text_type_default"><?php esc_html_e( 'anderer Text', 'vgw-metis' ); ?></label>
            </p>
            <hr/>
			<?php if ( ! $isNew && $pixel && $pixel->active && $pixel->public_identification_id ) : ?>
                <p class="wp_metis_metabox_action_remove">
                    <button
                            id="wp_metis_metabox_pixel_action_remove"
                            class="button button-secondary"
                            type="submit"
                            form="post"
                            formaction="post.php?wp_metis_metabox_action=remove_pixel"
                    >
						<?php esc_html_e( 'Zählmarke entfernen', 'vgw-metis' ) ?>
                    </button>
                </p>
			<?php endif; ?>

			<?php if ( ! $isNew && ( ! $pixel || ! $pixel->active ) ) : ?>
                <p class="wp_metis_metabox_action_assign">
                    <button
                            id="wp_metis_metabox_pixel_action_assign"
                            class="button button-secondary"
                            type="submit"
                            form="post"
                            formaction="post.php?wp_metis_metabox_action=assign_pixel"
                    >
						<?php esc_html_e( 'Zählmarke automatisch zuweisen', 'vgw-metis' ) ?>
                    </button>
                </p>
			<?php endif; ?>

			<?php if ( ! $isNew ) : ?>
                <p class="wp_metis_metabox_action_manual_assign">
                    <button
                            type="button"
                            class="button button-secondary"
                            id="wp_metis_metabox_pixel_action_manual_assign"
                            data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_metis_metabox_nonce' ) ) ?>"
                            data-post-id="<?php echo esc_attr( $post->ID ); ?>"
                            data-current-public-identification-id="<?php echo esc_attr( $public_id ); ?>"
                            data-posts-count="<?php echo esc_attr( $posts_count ); ?>"
                    >
						<?php esc_html_e( 'Zählmarke manuell zuweisen', 'vgw-metis' ) ?>
                    </button>
                </p>
			<?php endif; ?>

        </div>
		<?php
	}

	/**
	 * load the corresponding script
	 *
	 * @return void
	 */
	public function enqueue_script(): void {
		// enqueue script
		wp_enqueue_script( 'wp_metis_metabox_classic_editor_script', plugin_dir_url( __FILE__ ) . '../admin/js/classic-editor.js', [ 'jquery' ] );
		wp_localize_script(
			'wp_metis_metabox_classic_editor_script',
			'wp_metis_metabox_obj',
			[
				'enter_pixel_message'          => esc_html__( 'Bitte geben Sie den öffentliche Identifikations-ID der Zählmarke ein', 'vgw-metis' ),
				'confirm_disable_message'      => esc_html__( 'Die bereits mit dem Eintrag verknüpfte Zählmarke wird sofort ungültig und kann nicht mehr verwendet werden. Allfällige Zählungen über diese Zählmarke gehen dabei verloren! Sind Sie sich sicher, dass Sie die neue Zählmarke trotzdem einfügen möchten?', 'vgw-metis' ),
				'ajax_url'                     => admin_url( 'admin-ajax.php' ),
				'yes'                          => esc_html__( 'Ja', 'vgw-metis' ),
				'no'                           => esc_html__( 'Nein', 'vgw-metis' ),
				'error_inserting_pixel'        => esc_html__( 'Fehler! API konnte neue Zählmarke nicht einfügen.', 'vgw-metis' ),
				'error_general'                => esc_html__( 'Ein Fehler ist aufgetreten!', 'vgw-metis' ),
				'error_has_same_post_id'       => esc_html__( 'Fehler! Zählmarke ist hier bereits zugewiesen!', 'vgw-metis' ),
				'error_assign_to_post_failed'  => esc_html__( 'Fehler beim zuweisen der Zählmarke', 'vgw-metis' ),
				'error_remove_pixel_from_post' => esc_html__( 'Fehler beim entfernen der bisherigen Zählmarke!', 'vgw-metis' ),
				'error_disable_pixel'          => esc_html__( 'Fehler beim ungültig setzen der bisherigen Zählmarke!', 'vgw-metis' ),
				'success'                      => esc_html__( 'Manuelle Zuweisung erfolgreich!', 'vgw-metis' ),
				'error_new_pixel_is_disabled'  => esc_html__( 'Fehler: Die neue Zählmarke ist ungültig.', 'vgw-metis' ),
				'status_valid'                 => Common::API_STATE_VALID,
				'status_not_valid'             => Common::API_STATE_NOT_VALID,
				'status_not_found'             => Common::API_STATE_NOT_FOUND,
				'status_not_owner'             => Common::API_STATE_NOT_OWNER,
				'error_is_valid_and_ownership' => esc_html__( 'Fehler: API Aufruf zur Prüfung der Zählmarke ist fehlgeschlagen!', 'vgw-metis' ),
				'status_not_found_message'     => esc_html__( 'Fehler: Zählmarke wurde nicht gefunden', 'vgw-metis' ),
				'status_not_valid_message'     => esc_html__( 'Fehler: Ungültiges Zählmarken-Format', 'vgw-metis' ),
				'not_own_pixel_confirmation'   => esc_html__( 'Es handelt sich nicht um Ihre eigene Zählmarke, möchten Sie diese trotzdem hinzufügen?', 'vgw-metis' ),
				'error_get_posts_count'        => esc_html__( 'Fehler: Anzahl der Beiträge dieser Zählmarke konnte nicht gefunden werden.', 'vgw-metis' )
			]
		);
	}


	/**
	 * save action that gets triggered on post save to save our metabox data to wp post meta
	 *
	 * @param int $post_id the post_id the metabox data is saved for
	 *
	 * @return void
	 */
	public function save_metis_metabox( int $post_id ): void {
		// remove action
		if ( array_key_exists( 'wp_metis_metabox_action', $_GET ) && $_GET['wp_metis_metabox_action'] == 'remove_pixel' ) {

			if ( Services::unassign_pixel_from_post( $post_id ) ) {
				// display success
				// TODO AJAX Success Message
			} else {
				// display error
				// TODO AJAX Error Message
			}
		}

		// do we need to assign a free pixel?
		if (
			( array_key_exists( 'wp_metis_metabox_action', $_GET ) && $_GET['wp_metis_metabox_action'] === 'assign_pixel' ) ||
			( array_key_exists( 'wp_metis_metabox_auto_add', $_POST ) && $_POST['wp_metis_metabox_auto_add'] === 'yes' )
		) {
			// guarantee that we have free pixels

			$order_result = Services::order_pixels_if_needed();

            if($order_result === false) {
                // TODO add error notice if order pixels fails
            }

			$post = get_post( $post_id );

			// dont assign on auto save / revisions
			if ( ( $post->post_status === 'publish' || $post->post_status === 'draft' ) && $post->post_type !== 'revision' ) {
				if ( Services::assign_pixel_to_post( $post_id ) ) {
					// display success
					// TODO AJAX Success Message
				} else {
					// display error
					// TODO AJAX Error Message
				}
			}
		}

		// save text type
		if ( array_key_exists( 'wp_metis_metabox_text_type', $_POST ) &&
		     in_array( strtolower( $_POST['wp_metis_metabox_text_type'] ), array(
				     Common::TEXT_TYPE_DEFAULT,
				     Common::TEXT_TYPE_LYRIC
			     )
		)) {
			update_post_meta( (int) $post_id, '_metis_text_type', sanitize_key( $_POST['wp_metis_metabox_text_type'] ) );
		}
	}

	/**
	 * The WP Ajax action for manually assigning a pixel
	 *
	 * @return void
	 */
	public function manual_assign_pixel_action(): void {
		// wp nonce check
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'wp_metis_metabox_nonce' ) ) {
			wp_die( 'WP Nonce incorrect' );
		}

		// make sure we can only call this from ajax request
		if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
			// get and sanitize post_id
			$post_id = (int) sanitize_key( $_REQUEST['post_id'] );
			// get and sanitize new public identification id
			$public_identification_id = sanitize_key( $_REQUEST['public_identification_id'] );

			// get current pixel (if one)
			$current_pixel = DB_Pixels::get_pixel_by_post_id( $post_id );
			// get new pixel from out db (if one)
			$new_pixel = DB_Pixels::get_pixel_by_public_identification_id( $public_identification_id );

			// check if the new pixel is already in our db
			if ( $new_pixel ) {
				// new pixel is already in db, but is it disabled?
				if ( $new_pixel->disabled ) {
					echo 'error-new-pixel-is-disabled';
					wp_die();
				}

				// no attached post id or post id different from the current one?
				if ( ! $new_pixel->post_id || $new_pixel->post_id != $post_id ) {
					// remove post association current from pixel, if we have one
					if ( $current_pixel && $current_pixel->public_identification_id && ! DB_Pixels::remove_pixel_from_post( $current_pixel->public_identification_id, $post_id ) ) {
						echo 'error-remove-pixel-from-post';
						wp_die();
					}

					// disable current pixel
					// Todo instead of 5 params > Object for get all pixels search

					if ( $current_pixel && $current_pixel->public_identification_id ) {
						$current_pixel_posts = DB_Pixels::get_all_pixels( null, null, null, $current_pixel->public_identification_id );
						$count               = $current_pixel_posts ? count( $current_pixel_posts ) : 0;
						// disable current pixel only when new pixel has no attached posts
						if ( $count === 0 ) {
							if ( ! DB_Pixels::disable_pixel( $current_pixel->public_identification_id ) ) {
								echo 'error-disable-pixel';
								wp_die();
							}
						}
					}

					// finally assign new pixel to post
					if ( DB_Pixels::assign_pixel_to_post( $public_identification_id, $post_id ) ) {
						echo 'success';
					} else {
						echo 'error-assign-to-post-failed';
					}
				} else {
					// we know it has the same post id > error
					echo 'error-has-same-post-id';
				}
				wp_die();
			} else {
				// remove post association from current pixel (if one)
				if ( $current_pixel && $current_pixel->public_identification_id && ! DB_Pixels::remove_pixel_from_post( $current_pixel->public_identification_id, $post_id ) ) {
					echo 'error-remove-pixel-from-post';
					wp_die();
				}

				if ( $current_pixel && $current_pixel->public_identification_id ) {
					$current_pixel_posts = DB_Pixels::get_all_pixels( null, null, null, $current_pixel->public_identification_id );
					$count               = $current_pixel_posts ? count( $current_pixel_posts ) : 0;
					// disable current pixel only when new pixel has no attached posts
					if ( $count === 0 ) {
						if ( ! DB_Pixels::disable_pixel( $current_pixel->public_identification_id ) ) {
							echo 'error-disable-pixel';
							wp_die();
						}
					}
				}

				// add new pixel
				if ( ! Services::insert_one_manual_pixel( $public_identification_id ) ) {
					echo 'error-inserting-pixel';
					wp_die();
				}

				// assign new pixel to post
				if ( DB_Pixels::assign_pixel_to_post( $public_identification_id, $post_id ) ) {
					echo 'success';
				} else {
					echo 'error-assign-to-post-failed';
				}

				wp_die();
			}
		}
		wp_die();
	}

	/**
	 * check if a pixel is valid and check ownership through api, used in WP AJAX
	 *
	 * @return void
	 */
	public static function is_valid_and_ownership_check(): void {
		// wp nonce check
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], "wp_metis_metabox_nonce" ) ) {
			exit( "WP Nonce incorrect" );
		}

		// make sure we can only call this from ajax request
		if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
			// make sure the pid is set
			if ( isset( $_REQUEST['public_identification_id'] ) ) {

				// check validity and ownership via api
				$result = Services::is_valid_and_ownership_check( sanitize_key( $_REQUEST['public_identification_id'] ) );

				// if we get a result, echo the status
				if ( $result ) {
					esc_html_e( $result );
				}

				wp_die();
			}
		}

		// general error
		echo 'error-is-valid-and-ownership';
		wp_die();
	}

	/**
	 * Check if Block Editor is active
	 *
	 * @return bool
	 */
	public static function is_gutenberg_active(): bool {
		// Gutenberg plugin is installed and activated.
		$gutenberg = ! ( false === has_filter( 'replace_editor', 'gutenberg_init' ) );

		// Block editor since 5.0.
		$block_editor = version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' );

		if ( ! $gutenberg && ! $block_editor ) {
			return false;
		}

		if ( self::is_classic_editor_plugin_active() ) {
			$editor_option       = get_option( 'classic-editor-replace' );
			$block_editor_active = array( 'no-replace', 'block' );

			return in_array( $editor_option, $block_editor_active, true );
		}

		return true;
	}

	/**
	 * Check if Classic Editor plugin is active.
	 *
	 * @return bool
	 */
	public static function is_classic_editor_plugin_active(): bool {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
			return true;
		}

		return false;
	}

	// todo mark all functions that return values for ajax requests with special naming schema
}