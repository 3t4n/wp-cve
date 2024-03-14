<?php

class BWFAN_unsubscribe {

	private static $ins = null;
	protected $settings = null;
	protected $recipient = '';
	protected $uid = '';
	protected $unsubscribe_lists = [];
	protected $unsubscribe_all = false;

	/** @var WooFunnels_Contact */
	protected $contact = '';

	/** @var BWFCRM_Contact */
	protected $crm_contact = '';
	protected $one_click = 0;

	public function __construct() {
		add_action( 'bwfan_db_1_0_tables_created', array( $this, 'create_unsubscribe_sample_page' ) );
		add_action( 'wp', array( $this, 'unsubscribe_page_non_crawlable' ) );

		/** Shortcodes for unsubscribe */
		add_shortcode( 'bwfan_unsubscribe_button', array( $this, 'bwfan_unsubscribe_button' ) );
		add_shortcode( 'wfan_unsubscribe_button', array( $this, 'bwfan_unsubscribe_button' ) );
		add_shortcode( 'bwfan_subscriber_recipient', array( $this, 'bwfan_subscriber_recipient' ) );
		add_shortcode( 'wfan_contact_email', array( $this, 'bwfan_subscriber_recipient' ) );
		add_shortcode( 'bwfan_subscriber_name', array( $this, 'bwfan_subscriber_name' ) );
		add_shortcode( 'wfan_contact_name', array( $this, 'bwfan_subscriber_name' ) );
		add_shortcode( 'wfan_contact_firstname', array( $this, 'bwfan_subscriber_firstname' ) );
		add_shortcode( 'wfan_contact_lastname', array( $this, 'bwfan_subscriber_lastname' ) );

		/** Admin page selection call */
		add_action( 'wp_ajax_bwfan_select_unsubscribe_page', array( $this, 'bwfan_select_unsubscribe_page' ) );

		/** Front ajax call */
		add_action( 'wp_ajax_bwfan_unsubscribe_user', array( $this, 'bwfan_unsubscribe_user' ) );
		add_action( 'wp_ajax_nopriv_bwfan_unsubscribe_user', array( $this, 'bwfan_unsubscribe_user' ) );
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function create_unsubscribe_sample_page() {
		$global_settings = get_option( 'bwfan_global_settings', array() );

		if ( isset( $global_settings['bwfan_unsubscribe_page'] ) && intval( $global_settings['bwfan_unsubscribe_page'] ) > 0 ) {
			return;
		}

		$content  = sprintf( __( "Hi %s \n\nHelp us to improve your experience with us through better communication. Please adjust your preferences for email %s. \n\n%s.", 'wp-marketing-automations' ), '[bwfan_subscriber_name]', '[bwfan_subscriber_recipient]', '[bwfan_unsubscribe_button label="Update my preference"]' );
		$new_page = array(
			'post_title'   => __( 'Let\'s Keep In Touch', 'wp-marketing-automations' ),
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		);
		$post_id  = wp_insert_post( $new_page );

		$global_settings['bwfan_unsubscribe_page'] = strval( $post_id );
		update_option( 'bwfan_global_settings', $global_settings, true );
	}

	/**
	 * Adding noindex, nofollow meta tag for unsubscribe page
	 * Set data for execution
	 *
	 * @return void
	 */
	public function unsubscribe_page_non_crawlable() {
		$global_settings     = $this->get_global_settings();
		$unsubscribe_page_id = isset( $global_settings['bwfan_unsubscribe_page'] ) ? $global_settings['bwfan_unsubscribe_page'] : 0;
		if ( empty( $unsubscribe_page_id ) || ! is_page( $unsubscribe_page_id ) ) {
			return;
		}

		$one_click = filter_input( INPUT_POST, 'List-Unsubscribe' );
		if ( 'One-Click' === $one_click ) {
			$this->bwfan_unsubscribe_user( true );

			return;
		}
		$one_click = filter_input( INPUT_GET, 'List-Unsubscribe' );
		if ( 'One-Click' === $one_click ) {
			$this->one_click = 1;
		}

		/** Set no cache header */
		BWFAN_Common::nocache_headers();

		add_action( 'wp_head', function () {
			echo "\n<meta name='robots' content='noindex,nofollow' />\n";
			if ( 0 === $this->one_click ) {
				return;
			}
			echo "<script>window.bwfan_unsubscribe_preference = 'one_click';</script>";
		} );
	}

	public function bwfan_unsubscribe_button( $attrs ) {
		$attr = shortcode_atts( array(
			'label' => __( 'Update my preference', 'wp-marketing-automations' ),
		), $attrs );

		ob_start();
		echo "<style type='text/css'>
			a#bwfan_unsubscribe {
			    text-shadow: none;
			    display: inline-block;
			    padding: 15px 20px;
			    cursor: pointer;
			    text-decoration: none !important;
			}
			.bwfan_loading{opacity: 1!important;position: relative;color: rgba(255,255,255,.05)!important;pointer-events: none!important;}
			.bwfan_loading::-moz-selection {color: rgba(255, 255, 255, .05) !important;}
			.bwfan_loading::selection {color: rgba(255, 255, 255, .05) !important;}
			.bwfan_loading:after {animation: bwfan_spin 500ms infinite linear;border: 2px solid #fff;border-radius: 50%;border-right-color: transparent !important;border-top-color: transparent !important;content: '';display: block;width: 16px;height: 16px;top: 50%;left: 50%;margin-top: -8px;margin-left: -8px;position: absolute;}
			@keyframes bwfan_spin { 0% {transform: rotate(0deg)}100% {transform: rotate(360deg)}}
		</style>";

		echo '<form id="bwfan_unsubscribe_fields">';
		do_action( 'bwfan_print_custom_data', $this->contact );
		$this->print_unsubscribe_lists();

		echo '<a id="bwfan_unsubscribe" class="button-primary button" href="#">' . esc_html__( $attr['label'] ) . '</a>';
		$aid = filter_input( INPUT_GET, 'automation_id', FILTER_SANITIZE_NUMBER_INT );
		if ( ! empty( $aid ) ) {
			echo '<input type="hidden" id="bwfan_automation_id" value="' . esc_attr__( $aid ) . '" name="automation_id">';
		}

		$bid = filter_input( INPUT_GET, 'bid', FILTER_SANITIZE_NUMBER_INT );
		$bid = empty( $bid ) ? filter_input( INPUT_GET, 'broadcast_id', FILTER_SANITIZE_NUMBER_INT ) : $bid;
		if ( ! empty( $bid ) ) {
			echo '<input type="hidden" id="bwfan_broadcast_id" value="' . esc_attr__( $bid ) . '" name="broadcast_id">';
		}

		$fid = filter_input( INPUT_GET, 'fid', FILTER_SANITIZE_NUMBER_INT );
		$fid = empty( $fid ) ? filter_input( INPUT_GET, 'form_feed_id', FILTER_SANITIZE_NUMBER_INT ) : $fid;
		if ( ! empty( $fid ) ) {
			echo '<input type="hidden" id="bwfan_form_feed_id" value="' . esc_attr__( $fid ) . '" name="form_feed_id">';
		}

		$uid = htmlspecialchars( filter_input( INPUT_GET, 'uid' ) );
		if ( empty( $uid ) && ! empty( $this->uid ) ) {
			$uid = $this->uid;
		}
		if ( ! empty( $uid ) ) {
			echo '<input type="hidden" id="bwfan_form_uid_id" value="' . esc_attr__( $uid ) . '" name="uid">';
		}

		echo '<input type="hidden" id="bwfan_one_click" value="' . esc_attr__( $this->one_click ) . '" name="one_click">';

		$sid = filter_input( INPUT_GET, 'sid', FILTER_SANITIZE_NUMBER_INT );
		if ( ! empty( $sid ) ) {
			echo '<input type="hidden" id="bwfan_sid" value="' . esc_attr__( $sid ) . '">';
		}

		echo '<input type="hidden" id="bwfan_unsubscribe_nonce" value="' . esc_attr( wp_create_nonce( 'bwfan-unsubscribe-nonce' ) ) . '" name="bwfan_unsubscribe_nonce">';
		echo '</form>';

		return ob_get_clean();
	}

	public function bwfan_subscriber_recipient( $attrs ) {
		$attr = shortcode_atts( array(
			'fallback' => 'john@example.com',
		), $attrs );

		$this->set_data();

		$subscriber_details = $this->get_subscriber_details();

		$mode = 1;

		/** check if the mode 2 there then pass phone number instead of email */
		if ( isset( $_GET['mode'] ) && 2 === absint( $_GET['mode'] ) ) {
			$mode = 2;
		}

		if ( false !== $subscriber_details && 1 === absint( $mode ) && isset( $subscriber_details['subscriber_email'] ) ) { // WordPress.CSRF.NonceVerification.NoNonceVerification
			$attr['fallback'] = sanitize_text_field( $subscriber_details['subscriber_email'] ); // WordPress.CSRF.NonceVerification.NoNonceVerification
		} elseif ( false !== $subscriber_details && isset( $subscriber_details['subscriber_phone'] ) ) {
			$attr['fallback'] = sanitize_text_field( $subscriber_details['subscriber_phone'] );
		}

		return '<span id="bwfan_unsubscribe_recipient">' . $attr['fallback'] . '</span>';
	}

	public function bwfan_subscriber_name( $attrs ) {
		$attr = shortcode_atts( array(
			'fallback' => 'John',
		), $attrs );

		$this->set_data();

		$subscriber_details = $this->get_subscriber_details();
		if ( false !== $subscriber_details && isset( $subscriber_details['subscriber_name'] ) ) { // WordPress.CSRF.NonceVerification.NoNonceVerification
			$attr['fallback'] = sanitize_text_field( $subscriber_details['subscriber_name'] ); // WordPress.CSRF.NonceVerification.NoNonceVerification
		}

		return '<span id="bwfan_unsubscribe_name">' . $attr['fallback'] . '</span>';
	}

	public function bwfan_subscriber_firstname( $attrs ) {
		$attr = shortcode_atts( array(
			'fallback' => 'John',
		), $attrs );

		$this->set_data();

		$subscriber_details = $this->get_subscriber_details();
		if ( false !== $subscriber_details && isset( $subscriber_details['subscriber_firstname'] ) ) { // WordPress.CSRF.NonceVerification.NoNonceVerification
			$attr['fallback'] = sanitize_text_field( $subscriber_details['subscriber_firstname'] ); // WordPress.CSRF.NonceVerification.NoNonceVerification
		}

		return '<span id="bwfan_unsubscribe_name">' . $attr['fallback'] . '</span>';
	}

	public function bwfan_subscriber_lastname( $attrs ) {
		$attr = shortcode_atts( array(
			'fallback' => 'Doe',
		), $attrs );

		$this->set_data();

		$subscriber_details = $this->get_subscriber_details();
		if ( false !== $subscriber_details && isset( $subscriber_details['subscriber_lastname'] ) ) { // WordPress.CSRF.NonceVerification.NoNonceVerification
			$attr['fallback'] = sanitize_text_field( $subscriber_details['subscriber_lastname'] ); // WordPress.CSRF.NonceVerification.NoNonceVerification
		}

		return '<span id="bwfan_unsubscribe_name">' . $attr['fallback'] . '</span>';
	}

	public function print_unsubscribe_lists() {
		/** If admin screen, return */
		if ( is_admin() ) {
			return false;
		}

		$this->set_data();

		if ( ! bwfan_is_autonami_pro_active() ) {
			$this->only_unsubscribe_from_all_lists_html();

			return false;
		}

		$settings = $this->get_global_settings();

		/** One click unsubscribe call via get request */
		if ( 1 === $this->one_click ) {
			$this->only_unsubscribe_from_all_lists_html();

			return false;
		}

		$enabled = isset( $settings['bwfan_unsubscribe_lists_enable'] ) ? $settings['bwfan_unsubscribe_lists_enable'] : 0;
		if ( 0 === absint( $enabled ) ) {
			$this->only_unsubscribe_from_all_lists_html();

			return false;
		}

		$lists = isset( $settings['bwfan_unsubscribe_public_lists'] ) ? $settings['bwfan_unsubscribe_public_lists'] : [];
		if ( empty( $lists ) || ! is_array( $lists ) ) {
			$this->only_unsubscribe_from_all_lists_html();

			return false;
		}

		if ( ! $this->contact instanceof WooFunnels_Contact || 0 === $this->contact->get_id() ) {
			$this->only_unsubscribe_from_all_lists_html();

			return false;
		}

		$lists            = array_map( 'absint', $lists );
		$is_unsubscribed  = false;
		$subscribed_lists = array();

		if ( $this->crm_contact instanceof BWFCRM_Contact ) {
			/** Is Unsubscribed Flag */
			$is_unsubscribed = BWFCRM_Contact::$DISPLAY_STATUS_UNSUBSCRIBED === $this->crm_contact->get_display_status();

			$contact_lists    = $this->get_contact_lists();
			$subscribed_lists = $contact_lists['subscribed'];
			$contact_lists    = $contact_lists['all'];

			/** Show contact their subscribed public lists only */
			$visibility = isset( $settings['bwfan_unsubscribe_lists_visibility'] ) ? $settings['bwfan_unsubscribe_lists_visibility'] : 0;
			if ( 1 === intval( $visibility ) || true === $visibility || 'true' === $visibility ) {
				/** Common lists from public lists and contact lists */
				$lists = array_values( array_intersect( $contact_lists, $lists ) );
			}

			if ( empty( $lists ) ) {
				$this->only_unsubscribe_from_all_lists_html( $this->crm_contact );

				return false;
			}
		}

		$lists = BWFCRM_Lists::get_lists( $lists );

		usort( $lists, function ( $l1, $l2 ) {
			return strcmp( strtolower( $l1['name'] ), strtolower( $l2['name'] ) );
		} );
		$this->unsubscribe_lists_html( $lists, $subscribed_lists, $is_unsubscribed );

		return true;
	}

	public function only_unsubscribe_from_all_lists_html( $contact = false ) {
		if ( false === $contact && $this->crm_contact instanceof BWFCRM_Contact ) {
			$contact = $this->crm_contact;
		}

		/** In case Pro is active and Contact is valid */
		if ( bwfan_is_autonami_pro_active() && class_exists( 'BWFCRM_Contact' ) && $contact instanceof BWFCRM_Contact ) {
			$is_unsubscribed = ( BWFCRM_Contact::$DISPLAY_STATUS_UNSUBSCRIBED === $contact->get_display_status() );

			$this->unsubscribe_lists_html( array(), array(), $is_unsubscribed );

			return;
		}

		/** If Pro is not active OR Contact is not valid */
		$is_unsubscribed = false;
		if ( $this->contact instanceof WooFunnels_Contact ) {
			$data = array(
				'recipient' => array( $this->contact->get_email(), $this->contact->get_contact_no() ),
			);

			$unsubscribed_rows = BWFAN_Model_Message_Unsubscribe::get_message_unsubscribe_row( $data, false );
			if ( 0 < count( $unsubscribed_rows ) ) {
				$is_unsubscribed = true;
			}
		}

		$this->unsubscribe_lists_html( array(), array(), $is_unsubscribed );
	}

	public function unsubscribe_lists_html( $lists = array(), $subscribed_lists = array(), $is_unsubscribed = false ) {
		$settings    = $this->get_global_settings();
		$label       = isset( $settings['bwfan_unsubscribe_from_all_label'] ) && ! empty( $settings['bwfan_unsubscribe_from_all_label'] ) ? $settings['bwfan_unsubscribe_from_all_label'] : __( '"Unsubscribe From All" Label', 'wp-marketing-automations' );
		$description = isset( $settings['bwfan_unsubscribe_from_all_description'] ) ? $settings['bwfan_unsubscribe_from_all_description'] : '';
		?>
        <style>
            .bwfan-unsubscribe-single-list {
                border-bottom: 1px solid #aaa;
                padding: 20px;
            }

            .bwfan-unsubscribe-single-list:last-child {
                border: none;
                padding: 20px;
            }

            .bwfan-unsubscribe-single-list p {
                margin-top: 3px;
                margin-bottom: 0;
            }

            .bwfan-unsubscribe-single-list label {
                margin-left: 10px;
            }

            p.bwfan-unsubscribe-list-description {
                font-size: 14px;
            }

            .bwfan-unsubscribe-lists {
                margin-bottom: 30px;
            }

            .bwfan-unsubscribe-from-all-lists label {
                font-size: 16px;
                font-weight: 500;
            }

            .bwfan_response {
                margin-top: 12px;
            }
        </style>
        <div class="bwfan-unsubscribe-lists" id="bwfan-unsubscribe-lists">
			<?php
			foreach ( $lists as $list ) {
				$is_checked = in_array( absint( $list['ID'] ), $subscribed_lists ) && ! $is_unsubscribed;
				?>
                <div class="bwfan-unsubscribe-single-list">
                    <div class="bwfan-unsubscribe-list-checkbox">
                        <input
                            id="bwfan-list-<?php echo $list['ID']; ?>"
                            type="checkbox"
                            value="<?php echo $list['ID']; ?>"
							<?php echo $is_checked ? 'checked="checked"' : ''; ?>
                        />
                        <label for="bwfan-list-<?php echo $list['ID']; ?>"><?php echo $list['name']; ?></label>
                    </div>
					<?php if ( isset( $list['description'] ) ) : ?>
                        <p class="bwfan-unsubscribe-list-description"><?php echo $list['description']; ?></p>
					<?php endif; ?>
                </div>
				<?php
			}
			?>
            <!-- Global Unsubscription option -->
            <div class="bwfan-unsubscribe-single-list bwfan-unsubscribe-from-all-lists">
                <div class="bwfan-unsubscribe-list-checkbox">
                    <input id="bwfan-list-unsubscribe-all" type="checkbox" value="unsubscribe_all" <?php echo $is_unsubscribed ? 'checked="checked"' : ''; ?> />
                    <label for="bwfan-list-unsubscribe-all"><?php echo $label; ?></label>
                </div>
				<?php if ( ! empty( $description ) ) : ?>
                    <p class="bwfan-unsubscribe-list-description"><?php echo $description; ?></p>
				<?php endif; ?>
            </div>
        </div>
		<?php
	}

	public function bwfan_select_unsubscribe_page() {
		BWFAN_Common::nocache_headers();
		global $wpdb;
		$term    = isset( $_POST['search_term']['term'] ) ? sanitize_text_field( $_POST['search_term']['term'] ) : ''; // WordPress.CSRF.NonceVerification.NoNonceVerification
		$v2      = isset( $_POST['fromApp'] ) && $_POST['fromApp'] ? true : false;
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID,post_title FROM {$wpdb->prefix}posts WHERE post_title LIKE %s and post_type = %s and post_status =%s", '%' . $term . '%', 'page', 'publish' ) );
		if ( empty( $results ) || ! is_array( $results ) ) {
			wp_send_json( array(
				'results' => [],
			) );
		}

		$response = array();
		foreach ( $results as $result ) {
			if ( $v2 ) {
				$response[] = array(
					'key'   => $result->ID,
					'value' => $result->post_title,
				);
			} else {
				$response[] = array(
					'id'    => $result->ID,
					'text'  => $result->post_title,
					'value' => $result->ID,
					'label' => $result->post_title,
				);
			}
		}

		wp_send_json( array(
			'results' => $response,
		) );
	}

	public function bwfan_unsubscribe_user( $post = false ) {
		BWFAN_Common::nocache_headers();
		/** Security check */
		$nonce = ( isset( $_POST['_nonce'] ) ) ? sanitize_text_field( $_POST['_nonce'] ) : ''; //phpcs:ignore WordPress.Security.NonceVerification
		if ( false === $post && ! wp_verify_nonce( $nonce, 'bwfan-unsubscribe-nonce' ) ) {
			$this->return_message( 7 );
		}

		/** Set contact data */
		$this->set_data();

		/** If data is not present then return */
		if ( empty( $this->contact ) ) {
			$this->return_message( 1 );
		}

		do_action( 'bwfan_save_custom_field_data', $this->contact );

		$one_click = filter_input( INPUT_POST, 'one_click' );
		if ( true === $post || 1 === intval( $one_click ) ) {
			$this->mark_unsubscribe();

			return;
		}

		if ( false === $this->is_lists_display_active() ) {
			/** Just Unsubscribe or resubscribe only */
			$this->maybe_subscribe_or_unsubscribe();

			/** Will return from the function itself */
		}

		/** Maybe complete unsubscribe - all case */
		if ( true === $this->unsubscribe_all ) {
			/** remove list from contact */
			$this->unsubscribe_all_lists();

			/** Will return from the function itself */
			return;
		}

		/** Not an all case */
		$this->handle_unsubscribe_lists();
	}

	/**
	 * Set contact data from the query arguments
	 *
	 * @return void
	 */
	protected function set_data() {
		if ( ! empty( $this->contact ) ) {
			return;
		}

		$uid = filter_input( INPUT_POST, 'uid' );
		$uid = empty( $uid ) ? filter_input( INPUT_GET, 'uid' ) : $uid;
		$uid = sanitize_text_field( $uid );

		/** If none available then return */
		if ( empty( $uid ) ) {
			if ( ! is_user_logged_in() ) {
				return;
			}
			$this->get_logged_in_contact();
			if ( ! empty( $this->uid ) ) {
				$uid = $this->uid;
			}
		}

		if ( empty( $uid ) ) {
			return;
		}

		$contact = new WooFunnels_Contact( '', '', '', '', $uid );
		if ( $contact instanceof WooFunnels_Contact && $contact->get_id() > 0 ) {
			$this->contact   = $contact;
			$this->recipient = $contact->get_email();
			if ( class_exists( 'BWFCRM_Contact' ) ) {
				$crm_contact = new BWFCRM_Contact( $contact );
				if ( $crm_contact->is_contact_exists() ) {
					$this->crm_contact = $crm_contact;
				}
			}
		}

		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			return;
		}

		/** Set up unsubscribe lists */
		$lists = filter_input( INPUT_POST, 'unsubscribe_lists' );
		if ( ! empty( $lists ) ) {
			$lists = stripslashes_deep( $lists );
			$lists = json_decode( $lists, true );
		}

		if ( ! empty( $lists ) ) {
			$lists = array_map( 'sanitize_text_field', $lists );
			$lists = array_map( 'strval', $lists );
		}

		$this->unsubscribe_all = ! empty( $lists ) && in_array( 'all', $lists, true ) ? true : false;

		if ( true === $this->unsubscribe_all ) {
			$lists = array_diff( $lists, [ 'all' ] );
			sort( $lists );
		}

		$this->unsubscribe_lists = $lists;
	}

	/**
	 * Set global settings and return
	 *
	 * @return mixed|void
	 */
	protected function get_global_settings() {
		if ( is_null( $this->settings ) ) {
			$this->settings = BWFAN_Common::get_global_settings();
		}

		return $this->settings;
	}

	/**
	 * Check if lists visibility enabled
	 * @return bool
	 */
	protected function is_lists_display_active() {
		/** checking for autonami pro plugin */
		if ( ! bwfan_is_autonami_pro_active() ) {
			return false;
		}

		$settings = $this->get_global_settings();
		$active   = ( isset( $settings['bwfan_unsubscribe_lists_enable'] ) && true === $settings['bwfan_unsubscribe_lists_enable'] ) ? true : false;

		return $active;
	}

	/**
	 * Subscribe or Unsubscribe contact
	 *
	 * @return void
	 */
	protected function maybe_subscribe_or_unsubscribe() {
		if ( true === $this->unsubscribe_all ) {
			$this->mark_unsubscribe();

			return;
		}
		$this->mark_subscribe();
	}

	/**
	 * Unsubscribe contact
	 * If assigned public lists then un-assign
	 *
	 * @return void
	 */
	public function unsubscribe_all_lists() {
		$contact = $this->crm_contact;

		$lists            = $this->get_contact_lists();
		$subscribed_lists = $lists['subscribed'];

		$visible_lists = $this->get_visible_lists();

		$lists_to_unsub = array_values( array_intersect( $visible_lists, $subscribed_lists ) );
		sort( $lists_to_unsub );

		if ( ! empty( $lists_to_unsub ) ) {
			$contact->remove_lists( $lists_to_unsub );
		}

		$contact->contact->set_last_modified( current_time( 'mysql', 1 ) );
		if ( method_exists( $contact, 'save' ) ) {
			$contact->save();
		} else {
			$contact->contact->save();
		}

		/** Unsubscribe from lists which are unchecked, but are assigned to contact */
		$this->update_unassigned_lists_field( $lists_to_unsub );

		/** Mark unsubscribe */
		$this->mark_unsubscribe();
	}

	/**
	 * Subscribe contact if not subscribed
	 * Assign or Un-assign lists
	 *
	 * @return void
	 */
	public function handle_unsubscribe_lists() {
		$unsubscribe_lists = $this->unsubscribe_lists;
		$visible_lists     = $this->get_visible_lists();

		/**
		 * Assign lists which are not checked and already not assigned
		 */
		$lists_to_sub = array_diff( $visible_lists, $unsubscribe_lists );
		sort( $lists_to_sub );
		if ( is_array( $lists_to_sub ) && count( $lists_to_sub ) > 0 ) {
			$assigned_list = [];
			foreach ( $lists_to_sub as $list ) {
				$assigned_list[] = array( 'id' => $list, 'value' => '' );
			}
			$this->crm_contact->add_lists( $assigned_list );
			$this->crm_contact->save();
		}

		/** Maybe subscribe the contact */
		if ( BWFCRM_Contact::$DISPLAY_STATUS_UNSUBSCRIBED === $this->crm_contact->get_display_status() ) {
			$this->crm_contact->resubscribe();
		}

		$contact_lists   = $this->get_contact_lists();
		$subscribe_lists = $contact_lists['subscribed'];


		$lists_to_unsub = array_values( array_intersect( $unsubscribe_lists, $subscribe_lists ) );
		sort( $lists_to_unsub );

		if ( ! empty( $lists_to_unsub ) ) {
			$this->crm_contact->remove_lists( $lists_to_unsub );
		}

		$this->crm_contact->contact->set_last_modified( current_time( 'mysql', 1 ) );
		$this->crm_contact->save();

		/** Unsubscribe from lists which are unchecked, but are assigned to contact */
		$this->update_unassigned_lists_field( $lists_to_unsub );

		$this->return_message( 3 );
	}

	/**
	 * Mark contact unsubscribe
	 *
	 * @return void
	 */
	protected function mark_unsubscribe() {
		global $wpdb;

		$automation_id = filter_input( INPUT_POST, 'automation_id', FILTER_SANITIZE_NUMBER_INT );
		$broadcast_id  = filter_input( INPUT_POST, 'broadcast_id', FILTER_SANITIZE_NUMBER_INT );
		$form_feed_id  = filter_input( INPUT_POST, 'form_feed_id', FILTER_SANITIZE_NUMBER_INT );
		$sid           = filter_input( INPUT_POST, 'sid', FILTER_SANITIZE_NUMBER_INT );

		$automation_id = empty( $automation_id ) ? filter_input( INPUT_GET, 'automation_id', FILTER_SANITIZE_NUMBER_INT ) : $automation_id;
		$broadcast_id  = empty( $broadcast_id ) ? filter_input( INPUT_GET, 'broadcast_id', FILTER_SANITIZE_NUMBER_INT ) : $broadcast_id;
		$form_feed_id  = empty( $form_feed_id ) ? filter_input( INPUT_GET, 'form_feed_id', FILTER_SANITIZE_NUMBER_INT ) : $form_feed_id;
		$sid           = empty( $sid ) ? filter_input( INPUT_GET, 'sid', FILTER_SANITIZE_NUMBER_INT ) : $sid;
		$sid           = empty( $sid ) ? 0 : $sid;

		if ( false !== filter_var( $this->recipient, FILTER_VALIDATE_EMAIL ) ) {
			$mode = 1;
		} elseif ( is_numeric( $this->recipient ) ) {
			$mode = 2;
		} else {
			$this->return_message( 1 );
		}

		/**
		 * Checking if recipient already added to unsubscribe table
		 */
		$where         = "WHERE `recipient` = '" . sanitize_text_field( $this->recipient ) . "' and `mode` = '" . $mode . "'";
		$unsubscribers = $wpdb->get_var( "SELECT ID FROM {$wpdb->prefix}bwfan_message_unsubscribe $where ORDER BY ID DESC LIMIT 0,1 " );//phpcs:ignore WordPress.DB.PreparedSQL
		if ( $unsubscribers > 0 ) {
			$this->return_message( 6 );
		}

		/** Manual (Single Sending) */
		$c_type = 3;
		if ( ! empty( $automation_id ) ) {
			$c_type = 1;
		} elseif ( ! empty( $broadcast_id ) ) {
			$c_type = 2;
		} elseif ( ! empty( $form_feed_id ) ) {
			$c_type = 4;
		}

		$oid = 0;
		if ( ! empty( $automation_id ) ) {
			$oid = absint( $automation_id );
		} elseif ( ! empty( $broadcast_id ) ) {
			$oid = absint( $broadcast_id );
		} elseif ( ! empty( $form_feed_id ) ) {
			$oid = absint( $form_feed_id );
		}

		$insert_data = array(
			'recipient'     => $this->recipient,
			'c_date'        => current_time( 'mysql' ),
			'mode'          => $mode,
			'automation_id' => $oid,
			'c_type'        => $c_type,
			'sid'           => $sid,
		);

		BWFAN_Model_Message_Unsubscribe::insert( $insert_data );

		/** hook when any contact unsubscribed  */
		do_action( 'bwfcrm_after_contact_unsubscribed', array( $insert_data ) );

		$this->return_message( 2 );
	}

	/**
	 * Mark contact subscribe
	 *
	 * @return void
	 */
	protected function mark_subscribe() {
		global $wpdb;

		if ( bwfan_is_autonami_pro_active() ) {
			$this->crm_contact->resubscribe();

			$this->return_message( 5 );
		}

		$mode = 1;
		if ( false !== filter_var( $this->recipient, FILTER_VALIDATE_EMAIL ) ) {
			$mode = 1;
		} elseif ( is_numeric( $this->recipient ) ) {
			$mode = 2;
		} else {
			$this->return_message( 1 );
		}

		$where         = "WHERE `recipient` = '" . sanitize_text_field( $this->recipient ) . "' and `mode` = '" . $mode . "'";
		$unsubscribers = $wpdb->get_results( "SELECT ID,recipient FROM {$wpdb->prefix}bwfan_message_unsubscribe $where ORDER BY ID DESC", ARRAY_A );//phpcs:ignore WordPress.DB.PreparedSQL
		if ( ! empty( $unsubscribers ) ) {
			foreach ( $unsubscribers as $unsubscriber ) {
				$id = $unsubscriber['ID'];
				BWFAN_Model_Message_Unsubscribe::delete( $id );
			}

			$this->return_message( 5 );
		}

		$this->return_message( 6 );
	}

	/**
	 * Get contact subscribed and unsubscribed lists
	 *
	 * @return array
	 */
	protected function get_contact_lists() {
		/** Get Unsubscribed Lists */
		$unsubscribed_lists = $this->crm_contact->get_field_by_slug( 'unsubscribed-lists' );
		$unsubscribed_lists = ( 'null' === $unsubscribed_lists || empty( $unsubscribed_lists ) ) ? array() : json_decode( $unsubscribed_lists, true );
		$unsubscribed_lists = array_map( 'strval', $unsubscribed_lists );

		/** Get Contact Lists (Include Unsubscribed Lists) */
		$subscribed_lists = $this->crm_contact->get_lists();
		$subscribed_lists = array_map( 'strval', $subscribed_lists );
		$contact_lists    = array_values( array_merge( $subscribed_lists, $unsubscribed_lists ) );

		return array(
			'subscribed'   => $subscribed_lists,
			'unsubscribed' => $unsubscribed_lists,
			'all'          => $contact_lists,
		);
	}

	/**
	 * Get public lists for display
	 *
	 * @return array|mixed
	 */
	protected function get_visible_lists() {
		$settings     = $this->get_global_settings();
		$public_lists = ! empty( $settings['bwfan_unsubscribe_public_lists'] ) ? $settings['bwfan_unsubscribe_public_lists'] : [];
		$visibility   = isset( $settings['bwfan_unsubscribe_lists_visibility'] ) ? $settings['bwfan_unsubscribe_lists_visibility'] : 0;

		if ( 1 !== absint( $visibility ) ) {
			return $public_lists;
		}

		/** Get Contact List */
		$contact_lists    = $this->get_contact_lists();
		$subscribed_lists = $contact_lists['subscribed'];

		/** Public Lists for Contact will consists of only which contact has been added to */
		$public_lists = array_values( array_intersect( $subscribed_lists, $public_lists ) );

		return $public_lists;
	}

	/**
	 * Get subscriber details using uid
	 *
	 * @return array|false
	 */
	protected function get_subscriber_details() {
		if ( empty( $this->contact ) ) {
			return false;
		}

		$contact_details                         = [];
		$contact_details['subscriber_email']     = $this->contact->get_email();
		$contact_details['subscriber_phone']     = $this->contact->get_contact_no();
		$contact_details['subscriber_name']      = ucwords( $this->contact->get_f_name() . ' ' . $this->contact->get_l_name() );
		$contact_details['subscriber_firstname'] = ucwords( $this->contact->get_f_name() );
		$contact_details['subscriber_lastname']  = ucwords( $this->contact->get_l_name() );

		return $contact_details;
	}

	public function get_logged_in_contact() {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		$contact = new WooFunnels_Contact( get_current_user_id() );
		if ( $contact instanceof WooFunnels_Contact && $contact->get_id() > 0 ) {
			$this->contact   = $contact;
			$this->uid       = $contact->get_uid();
			$this->recipient = $contact->get_email();
		}
	}

	/**
	 * Update contact unassigned list field
	 *
	 * @param $lists_to_unsub
	 *
	 * @return void
	 */
	protected function update_unassigned_lists_field( $lists_to_unsub ) {
		if ( empty( $lists_to_unsub ) ) {
			return;
		}
		$lists_to_unsub = array_map( 'intval', $lists_to_unsub );

		$current_unsub_lists = $this->crm_contact->get_field_by_slug( 'unsubscribed-lists' );
		$current_unsub_lists = ( 'null' === $current_unsub_lists || empty( $current_unsub_lists ) ) ? [] : json_decode( $current_unsub_lists, true );

		$current_unsub_lists = array_merge( $current_unsub_lists, $lists_to_unsub );
		$current_unsub_lists = array_unique( $current_unsub_lists );
		sort( $current_unsub_lists );

		$current_unsub_lists = array_map( 'intval', $current_unsub_lists );

		$this->crm_contact->set_field_by_slug( 'unsubscribed-lists', wp_json_encode( $current_unsub_lists ) );
		$this->crm_contact->save_fields();
	}

	/**
	 * Return messages compiled
	 *
	 * @param $type
	 *
	 * @return void
	 */
	protected function return_message( $type = 1 ) {
		if ( 1 === absint( $type ) ) {
			wp_send_json( array(
				'success' => 0,
				'message' => __( 'Sorry! We are unable to update preferences as no contact found.', 'wp-marketing-automations' ),
			) );
		}
		if ( in_array( intval( $type ), array( 2, 3, 4, 5, 6 ) ) ) {
			$global_settings = $this->get_global_settings();
			wp_send_json( array(
				'success' => 1,
				'message' => $global_settings['bwfan_unsubscribe_data_success'],
			) );
		}
		if ( 7 === absint( $type ) ) {
			wp_send_json( array(
				'success' => 0,
				'message' => __( 'Security check failed', 'wp-marketing-automations' ),
			) );
		}
	}

}

BWFAN_unsubscribe::get_instance();