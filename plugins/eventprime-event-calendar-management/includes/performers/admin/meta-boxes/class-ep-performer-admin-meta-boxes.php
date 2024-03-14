<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class for admin Event meta boxes
 */
class EventM_Performer_Admin_Meta_Boxes {

	/**
	 * Is meta boxes saved once?
	 *
	 * @var boolean
	 */
	private static $ep_saved_meta_boxes = false;

    /**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_meta_box_scripts' ) );
		add_action( 'add_meta_boxes', array( $this, 'ep_performer_remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'ep_performer_register_meta_boxes' ), 1 );
        add_action( 'save_post', array( $this, 'ep_save_meta_boxes' ), 1, 2 );
		add_filter( 'manage_em_performer_posts_columns', array( $this,'ep_performer_posts_columns' ), 1 );
		add_action( 'manage_em_performer_posts_custom_column', array( $this,'ep_performer_posts_custom_columns' ), 1, 2 );
	}

    /**
     * Enqueue meta box scripts
     */

    public function enqueue_admin_meta_box_scripts() {
	    wp_enqueue_media();
		wp_register_style(
            'em-performer-meta-box-css',
            EP_BASE_URL . '/includes/performers/assets/css/em-admin-performer-metabox-custom.css',
            false, EVENTPRIME_VERSION
        );

	    wp_register_script(
		    'em-performer-meta-box-js',
		    EP_BASE_URL . '/includes/performers/assets/js/em-admin-performer-metabox-custom.js',
		    array( 'jquery' ), EVENTPRIME_VERSION
        );

		wp_localize_script(
            'em-performer-meta-box-js', 
            'em_performer_meta_box_object', 
            array(
				'max_field_warning' => esc_html__( 'Maximum limit reached for adding', 'eventprime-event-calendar-management' )
				//'remove_label' => esc_html__( 'Remove', 'eventprime-event-calendar-management' ),
            )
        );
		wp_enqueue_style( 'ep-toast-css' );
        wp_enqueue_script( 'ep-toast-js' );
        wp_enqueue_script( 'ep-toast-message-js' );
    }

    /**
	 * Register meta box for performer
	 */
	public function ep_performer_register_meta_boxes() {
		$performers_text = ep_global_settings_button_title( 'Performers' );
		$performer_text = ep_global_settings_button_title( 'Performer' );
		add_meta_box(
			'ep_performer_register_meta_boxes',
			esc_html__( $performers_text . ' Settings', 'eventprime-event-calendar-management' ),
			array( $this, 'ep_add_performer_setting_box' ),
			'em_performer', 'normal', 'high'
		);

		add_meta_box( 
			'ep_performer-gallery-images', 
			__( $performer_text . ' gallery', 'eventprime-event-calendar-management' ), 
			array( $this, 'ep_add_performer_gallery_box' ),
			'em_performer', 'side', 'low' 
		);
	}

    /**
	 * Add performer setting details
	 *
	 * @param $post
	 */
	public function ep_add_performer_setting_box( $post ): void {
		if( $post->post_type == 'em_performer' ) {
			wp_enqueue_style( 'em-performer-meta-box-css' );
			wp_enqueue_script( 'em-performer-meta-box-js' );

			wp_nonce_field( 'ep_save_performer_data', 'ep_performer_meta_nonce' );

			include_once __DIR__ .'/views/meta-box-panel-html.php';
		}
	}

    /**
	 * Return tabs data
	 *
	 * @return array
	 */
	private static function get_ep_performer_meta_tabs() {
		$tabs = apply_filters(
			'ep_performer_meta_tabs',
			array(
				'settings'     => array(
					'label'    => esc_html__( 'Settings', 'eventprime-event-calendar-management' ),
					'target'   => 'ep_performer_settings_data',
					'class'    => array( 'ep_performer_settings' ),
					'priority' => 10,
				),
				'personal'     => array(
					'label'    => esc_html__( 'Personal Information', 'eventprime-event-calendar-management' ),
					'target'   => 'ep_performer_personal_data',
					'class'    => array( 'ep_performer_personal_info' ),
					'priority' => 20,
				),
				'social'     => array(
					'label'    => esc_html__( 'Social Information', 'eventprime-event-calendar-management' ),
					'target'   => 'ep_performer_social_data',
					'class'    => array( 'ep_performer_social_info' ),
					'priority' => 30,
				),
			)
		);

		// Sort tabs based on priority.
		//uasort( $tabs, array( __CLASS__, 'event_data_tabs_sort' ) );

		return $tabs;
	}

    /**
	 * Show the tab contents
	 */
	private static function ep_performer_tab_content() {
		global $post;

		include __DIR__ .'/views/meta-box-settings-panel-html.php';
        include __DIR__ .'/views/meta-box-personal-panel-html.php';
        include __DIR__ .'/views/meta-box-social-panel-html.php';
	}

    /**
     * Save performers data
     * 
     * @param int 	 $post_id Post ID.
     * @param object $post Post object.
     */
    public function ep_save_meta_boxes( $post_id, $post ) {
		$post_id = absint( $post_id );

		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) || self::$ep_saved_meta_boxes ) {
			return;
		}

		// Dont' save meta boxes for revisions or autosaves.
		if( defined('DOING_AUTOSAVE') and DOING_AUTOSAVE ) {
			return false;
		}

		// Check the nonce.
		if ( empty( $_POST['ep_performer_meta_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['ep_performer_meta_nonce'] ), 'ep_save_performer_data' ) ) {
			return;
		}

		// Check the post being saved == the $post_id to prevent triggering this call for other save_post events.
		if ( empty( $_POST['post_ID'] ) || absint( $_POST['post_ID'] ) !== $post_id ) {
			return;
		}

		// Check user has permission to edit.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		self::$ep_saved_meta_boxes = true;
                $error = false;
		$em_type = isset( $_POST['em_type'] ) ? sanitize_text_field( $_POST['em_type'] ) : '';
		$em_role = isset( $_POST['em_role'] ) ? sanitize_text_field( $_POST['em_role'] ) : '';
		$em_display_front = isset( $_POST['em_display_front'] ) ? 1 : 0;
		$em_is_featured = isset( $_POST['em_is_featured'] ) && !empty($_POST['em_is_featured'] ) ? 1 : 0;
		$em_social_links = $em_performer_phones = $em_performer_emails = $em_performer_websites = array();
		if( isset( $_POST['em_social_links'] ) && count( $_POST['em_social_links'] ) > 0 ) {
			foreach( $_POST['em_social_links'] as $social_key =>  $social_links ) {
				if( !empty( $social_links ) ) {
					$em_social_links[ $social_key ] = sanitize_url( $social_links );
				}
			}
		}

		if( isset( $_POST['em_performer_phones'] ) && count( $_POST['em_performer_phones'] ) > 0 ) {
			foreach( $_POST['em_performer_phones'] as $phone) {
				if( !empty( $phone ) ) {
					$phone_no = is_valid_phone( sanitize_text_field( $phone ) );
					if( $phone_no ) {
						$em_performer_phones[] = $phone;
					}
				}
			}
		}

		if( isset( $_POST['em_performer_emails'] ) && count( $_POST['em_performer_emails'] ) > 0 ) {
			foreach( $_POST['em_performer_emails'] as $email) {
				if( !empty( $email ) ) {
					$email = sanitize_email( $email );
					if( ! empty( $email ) && filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
						$em_performer_emails[] = $email;
					}
				}
			}
		}

		if( isset( $_POST['em_performer_websites'] ) && count( $_POST['em_performer_websites'] ) > 0 ) {
			foreach( $_POST['em_performer_websites'] as $website) {
				if( !empty( $website ) ) {
					$site_url_valid = is_valid_site_url( sanitize_text_field( $website ) );
					if($site_url_valid){
						$em_performer_websites[] = sanitize_url( $website );
					}
				}
			}
		}

		$em_performer_gallery = isset( $_POST['em_performer_gallery'] ) ? explode(',', sanitize_text_field( $_POST['em_performer_gallery']) ) : array();
		$performer_post_status = ( ! empty( $post->post_status ) && $post->post_status !== 'draft' ) ? $post->post_status : 'publish';
		update_post_meta( $post_id, 'em_type', $em_type );
		update_post_meta( $post_id, 'em_role', $em_role );
		update_post_meta( $post_id, 'em_display_front', $em_display_front );
		update_post_meta( $post_id, 'em_is_featured', $em_is_featured );
		update_post_meta( $post_id, 'em_social_links', $em_social_links );
		update_post_meta( $post_id, 'em_performer_phones', $em_performer_phones );
		update_post_meta( $post_id, 'em_performer_emails', $em_performer_emails );
		update_post_meta( $post_id, 'em_performer_websites', $em_performer_websites );
		update_post_meta( $post_id, 'em_performer_gallery', $em_performer_gallery );
		update_post_meta( $post_id, 'em_created_by', $post->post_author );
		if ( ! metadata_exists( 'post', $post_id, 'em_status' ) ) {
			update_post_meta( $post_id, 'em_status', 1 );
		}
		
		//publish the performer
		$performer_post = array(
			'ID'          => $post_id,
			'post_type'   => EM_PERFORMER_POST_TYPE,
			'post_status' => $performer_post_status,
		);
		  // Update the post into the database
		wp_update_post( $performer_post );

		do_action( 'ep_after_save_performer_data', $post_id, $post );
    }

	/**
	 * Remove default meta boxes
	 */
	public function ep_performer_remove_meta_boxes() {
		remove_meta_box( 'postexcerpt', 'em_performer', 'normal' );
		remove_meta_box( 'commentsdiv', 'em_performer', 'normal' );
		remove_meta_box( 'commentstatusdiv', 'em_performer', 'side' );
		remove_meta_box( 'commentstatusdiv', 'em_performer', 'normal' );
		remove_meta_box( 'postcustom', 'em_performer', 'normal' );
		remove_meta_box( 'pageparentdiv', 'em_performer', 'side' );
	}

	/**
	 * Add performer gallery meta box
	 */
	public function ep_add_performer_gallery_box() {
		global $post;
		$performers_text = ep_global_settings_button_title( 'Performers' );
		$performer_text = ep_global_settings_button_title( 'Performer' );
		$em_performer_gallery = get_post_meta( $post->ID, 'em_performer_gallery', true );
		if( empty( $em_performer_gallery ) ) {
			$em_performer_gallery = array();
		}
		if( ! empty( $em_performer_gallery ) && ! is_array( $em_performer_gallery ) ) {
			$em_performer_gallery = explode( ',', $em_performer_gallery );
		}?>
		<div id="ep_performer_gallery_container">
			<ul class="ep_gallery_images ep-d-flex ep-align-items-center ep-content-left">
				<?php
				$attachments         = array_filter( $em_performer_gallery );
				$update_meta         = false;
				$updated_gallery_ids = array();

				if ( ! empty( $attachments ) ) {
					foreach ( $attachments as $attachment_id ) {
						$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );
						// if attachment is empty skip.
						if ( empty( $attachment ) ) {
							$update_meta = true;
							continue;
						}
						?>
						<li class="ep-gal-img" data-attachment_id="<?php echo esc_attr( $attachment_id ); ?>">
							<?php echo $attachment; ?>
							<div class="ep-gal-img-delete"><span class="em-performer-gallery-remove dashicons dashicons-trash"></span></div>
						</li>
						<?php
						// rebuild ids to be saved.
						$updated_gallery_ids[] = $attachment_id;
					}

					// need to update product meta to set new gallery ids
					if ( $update_meta ) {
						update_post_meta( $post->ID, 'em_performer_gallery', implode( ',', $updated_gallery_ids ) );
					}
				}?>
			</ul>

			<input type="hidden" id="em_performer_gallery" name="em_performer_gallery" value="<?php echo esc_attr( implode( ',', $updated_gallery_ids ) ); ?>" />

		</div>
		<p class="ep_add_performer_gallery hide-if-no-js">
			<a href="#" 
				data-choose="<?php esc_attr_e( 'Add images to '.strtolower( $performer_text ).' gallery', 'eventprime-event-calendar-management' ); ?>" 
				data-update="<?php esc_attr_e( 'Add to gallery', 'eventprime-event-calendar-management' ); ?>" 
				data-delete="<?php esc_attr_e( 'Delete image', 'eventprime-event-calendar-management' ); ?>" 
				data-text="<?php esc_attr_e( 'Delete', 'eventprime-event-calendar-management' ); ?>"
			>
				<?php esc_html_e( 'Add '.strtolower( $performer_text ).' gallery images', 'eventprime-event-calendar-management' ); ?>
			</a>
		</p><?php
	}

	/*
	* Adding Performer role in List Column
	*/
	public function ep_performer_posts_columns($defaults){
		$offset = 2;
		$performer_column = array(
			'ep_perfomer_role' => esc_html__( 'Role', 'eventprime-event-calendar-management' ),
		);
		return array_merge(array_slice($defaults, 0, $offset), $performer_column, array_slice($defaults, $offset, null));
	}
		
	public function ep_performer_posts_custom_columns($column_name, $post_id){
		if($column_name == 'ep_perfomer_role'){
			$role = get_post_meta( $post_id, 'em_role', true );
			if( ! empty( $role ) ){
				echo esc_html( $role );
			} else{
				echo '---';
			}
		}
	}    

}

new EventM_Performer_Admin_Meta_Boxes();