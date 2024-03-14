<?php
class YOP_Poll_Admin {
	private $templates;
	private static $date_format, $time_format, $old_version = null;
	private static $polls_list;
	private static $votes_list;
	private static $logs_list;
	private static $bans_list;
	public function __construct() {
		global $pagenow;
		self::$date_format = get_option( 'date_format' );
		self::$time_format = get_option( 'time_format' );
		self::$old_version = get_option( 'yop_poll_old_version' );
		if ( true === is_admin() ) {
            add_filter( 'admin_title', array( &$this, 'change_page_title' ) );
			add_filter( 'clean_url', array( &$this, 'clean_recaptcha_url' ) );
			add_filter( 'set-screen-option', array( &$this, 'save_screen_option' ), 10, 3 );
            add_action( 'admin_menu', array( &$this, 'build_admin_menu' ) );
            add_action( 'plugins_loaded', array( &$this, 'verify_update' ) );
			add_action( 'plugins_loaded', array( $this, 'load_translations' ) );
            add_action( 'admin_enqueue_scripts', array( &$this, 'load_dependencies' ), 1000 );
			add_action( 'set_logged_in_cookie', array( $this, 'update_login_cookie' ) );
            add_action( 'wp_ajax_create_yop_poll', array( &$this, 'create_poll' ) );
            add_action( 'wp_ajax_update_yop_poll', array( &$this, 'update_poll' ) );
            add_action( 'wp_ajax_delete_single_yop_poll', array( &$this, 'delete_single_poll' ) );
            add_action( 'wp_ajax_delete_bulk_yop_poll', array( &$this, 'delete_bulk_poll' ) );
            add_action( 'wp_ajax_clone_single_yop_poll', array( &$this, 'clone_single_poll' ) );
            add_action( 'wp_ajax_clone_bulk_yop_poll', array( &$this, 'clone_bulk_poll' ) );
			add_action( 'wp_ajax_reset_single_yop_poll', array( &$this, 'reset_single_poll' ) );
            add_action( 'wp_ajax_reset_bulk_yop_poll', array( &$this, 'reset_bulk_poll' ) );
            add_action( 'wp_ajax_create_yop_poll_ban', array( &$this, 'create_ban' ) );
            add_action( 'wp_ajax_delete_yop_poll_ban', array( &$this, 'delete_single_ban' ) );
            add_action( 'wp_ajax_update_yop_poll_ban', array( &$this, 'update_ban' ) );
            add_action( 'wp_ajax_delete_bulk_yop_poll_ban', array( &$this, 'delete_bulk_ban' ) );
            add_action( 'wp_ajax_delete_yop_poll_log', array( &$this, 'delete_single_log' ) );
            add_action( 'wp_ajax_get_yop_poll_log_details', array( &$this, 'get_log_details' ) );
            add_action( 'wp_ajax_yop_poll_delete_logs_bulk', array( &$this, 'delete_bulk_log' ) );
            add_action( 'wp_ajax_yop_poll_is_user_logged_in', array( &$this, 'is_user_logged_in' ) );
            add_action( 'wp_ajax_yop_poll_record_vote', array( &$this, 'record_vote' ) );
			add_action( 'wp_ajax_yop_poll_record_wordpress_vote', array( &$this, 'record_wordpress_vote' ) );
			add_action( 'wp_ajax_yop_poll_get_poll_for_frontend', array( &$this, 'create_poll_for_frontend' ) );
            add_action( 'wp_ajax_get_yop_poll_votes_customs', array( &$this, 'get_yop_poll_votes_customs' ) );
            add_action( 'wp_ajax_yop-poll-get-vote-details', array( &$this, 'get_vote_details' ) );
            add_action( 'wp_ajax_yop_poll_delete_vote', array( &$this, 'delete_single_vote' ) );
            add_action( 'wp_ajax_yop_poll_delete_votes_bulk', array( &$this, 'delete_bulk_votes' ) );
			add_action( 'wp_ajax_yop_poll_save_settings', array( &$this, 'save_settings' ) );
			add_action( 'wp_ajax_yop_poll-add-votes-manually', array( &$this, 'add_votes_manually' ) );
			add_action( 'wp_ajax_yop_poll_stop_showing_guide', array( &$this, 'stop_showing_guide' ) );
			add_action( 'wp_ajax_yop_poll_send_guide', array( &$this, 'send_guide' ) );
			add_action( 'wp_ajax_yop_poll_send_deactivation_feedback', array( &$this, 'send_deactivation_feedback' ) );
			add_action( 'wp_ajax_yop_poll_login_user', array( &$this, 'login_user' ) );
			if ( 'plugins.php' === $pagenow ) {
				add_action( 'admin_footer', array( $this, 'add_deactivate_elements' ) );
			}
			if ( self::$old_version ) {
				if ( false !== strpos( self::$old_version, '4.' ) ) {
					add_action( 'wp_ajax_yop_ajax_migrate', array( 'ClassYopPollImporter4x', 'yop_ajax_import' ) );
				} elseif ( false !== strpos( self::$old_version, '5.' ) ) {
					add_action( 'wp_ajax_yop_ajax_migrate', array( 'ClassYopPollImporter5x', 'yop_ajax_import' ) );
				}
			}
			add_action( 'wp_ajax_nopriv_yop_poll_is_user_logged_in', array( &$this, 'is_user_logged_in' ) );
			add_action( 'wp_ajax_nopriv_yop_poll_record_vote', array( &$this, 'record_vote' ) );
			add_action( 'wp_ajax_nopriv_yop_poll_record_wordpress_vote', array( &$this, 'record_wordpress_vote' ) );
			add_action( 'wp_ajax_nopriv_yop_poll_get_poll_for_frontend', array( &$this, 'create_poll_for_frontend' ) );
			add_action( 'wp_ajax_nopriv_yop_poll_login_user', array( &$this, 'login_user' ) );
		}
		Yop_Poll_DbSchema::initialize_tables_names();
	}
	public function set_admin_footer() {
		return 'Please rate YOP Poll <a href="https://wordpress.org/support/plugin/yop-poll/reviews/?filter=5#new-post" target="_blank">★★★★★</a> on <a href="https://wordpress.org/support/plugin/yop-poll/reviews/?filter=5#new-post" target="_blank">WordPress.org</a> to help us spread the word. Thank you from the YOP Team!';
	}
	public function add_screen_options_for_view_polls() {
		$option = 'per_page';
		$args = array(
			'label' => esc_html__( 'Number of polls per page', 'yop-poll' ),
			'default' => 10,
			'option' => 'polls_per_page',
		);
		add_screen_option( $option, $args );
		self::$polls_list = new YOP_Poll_Polls_List();
	}
	public function add_screen_options_for_view_votes() {
		$_poll_id = isset( $_REQUEST['poll_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['poll_id'] ) ) : '';
		$option = 'per_page';
		$args = array(
			'label' => esc_html__( 'Number of votes per page', 'yop-poll' ),
			'default' => 10,
			'option' => 'votes_per_page',
		);
		add_screen_option( $option, $args );
		self::$votes_list = new YOP_Poll_Votes_List( $_poll_id );
	}
	public function add_screen_options_for_view_logs() {
		$option = 'per_page';
		$args = array(
			'label' => esc_html__( 'Number of records per page', 'yop-poll' ),
			'default' => 10,
			'option' => 'logs_per_page',
		);
		add_screen_option( $option, $args );
		self::$logs_list = new YOP_Poll_Logs_List();
	}
	public function add_screen_options_for_view_bans() {
		$option = 'per_page';
		$args = array(
			'label' => esc_html__( 'Number of records per page', 'yop-poll' ),
			'default' => 10,
			'option' => 'bans_per_page',
		);
		add_screen_option( $option, $args );
		self::$bans_list = new YOP_Poll_Bans_List();
	}
	public function save_screen_option( $status, $option, $value ) {
		return $value;
	}
	public function add_deactivate_elements() {
		?>
		<div id="yop-poll-deactivate-modal-wrapper">
		    <div id="yop-poll-deactivate-modal">
				<a href="#" id="yop-poll-deactivate-close">
					<span class="dashicons dashicons-no-alt"></span>
				</a>
		    	<form action="" method="post">
		    		<!-- Modal header -->
		    		<div id="yop-poll-deactivate-header">
		    			<img src="<?php echo esc_url( YOP_POLL_URL ) . '/admin/assets/images/yop-poll-admin-menu-icon16.png'; ?>">
						<?php esc_html_e( 'Quick Feedback', 'yop-poll' ); ?>
		    		</div>
		    		<!-- Modal inner -->
		    		<div id="yop-poll-deactivate-inner">
			    	    <h3><?php echo esc_html_e( "We're sorry to see you go.", 'yop-poll' ); ?></h3>
			            <p><strong><?php esc_html_e( 'If you have a moment, please share why you are deactivating YOP Poll:', 'yop-poll' ); ?></strong></p>

			    	    <ul>

			    	    	<li>
								<label>
									<input type="radio" name="yop-poll_disable_reason" value="technical-issue" />
									<strong><?php esc_html_e( "I couldn't get the plugin to work", 'yop-poll' ); ?></strong>
									<p><?php esc_html_e( 'Please describe the issues below. This will help us test and solve these problems in a timely manner.', 'yop-poll' ); ?></p>
									<textarea name="yop-poll_deactivate_details[]" placeholder="<?php esc_html_e( 'Type the issues here...', 'yop-poll' ); ?>"></textarea>
								</label>
							</li>

			                <li>
			                	<label>
			                		<input type="radio" name="yop-poll_disable_reason" value="missing-feature" />
			                		<strong><?php esc_html_e( 'Missing features I need', 'yop-poll' ); ?></strong>
									<p><?php esc_html_e( 'Please describe the feature you need. This will help us prioritize our tasks and work on the most requested features.', 'yop-poll' ); ?></p>
									<textarea name="yop-poll_deactivate_details[]" placeholder="<?php esc_html_e( 'Type the missing features here...', 'yop-poll' ); ?>"></textarea>
								</label>
							</li>

							<li>
								<label>
									<input type="radio" name="yop-poll_disable_reason" value="other" />
									<strong><?php esc_html_e( 'Other reason', 'yop-poll' ); ?></strong>
			    			  		<p><?php esc_html_e( 'We are continuously improving YOP Poll and your feedback is extremely important to us. Please let us know how we can improve the plugin.', 'yop-poll' ); ?></p>
			    			  		<textarea name="yop-poll_deactivate_details[]" placeholder="<?php esc_html_e( 'Type your feedback here...', 'yop-poll' ); ?>"></textarea>
			    			  	</label>
			    			</li>

			    	    </ul>

			    	</div>

			    	<!-- Modal footer -->
		    	    <div id="yop-poll-deactivate-footer">
			    	    <input id="yop-poll-feedback-submit" class="button button-primary" type="submit" name="yop-poll-feedback-submit" value="<?php esc_html_e( 'Submit & Deactivate', 'yop-poll' ); ?>" />
			    	    <a id="yop-poll-deactivate-without-feedback" href="#"><?php esc_html_e( 'Skip and Deactivate', 'yop-poll' ); ?></a>
			    	</div>

			    	<!-- Token -->
			    	<?php wp_nonce_field( 'yop-poll_deactivation', 'yop-poll_deactivate_token', false ); ?>

		    	</form>
		    </div>
		</div>
		<style>
			#yop-poll-deactivate-modal-wrapper { display: none; z-index: 9999; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,.35);  }
			#yop-poll-deactivate-modal { z-index: 10000; position: fixed; top: 7.5%; left: 50%; background: #fff; border-radius: 4px; max-width: 600px; margin-left: -300px; width: 100%; }

			#yop-poll-deactivate-header { padding: 20px 0 15px 0; border-bottom: 1px solid rgba( 200, 215, 225, 0.5 ); text-align: left; font-size: 20px; }
			#yop-poll-deactivate-header img { max-width: 140px; height: auto; margin-left: 20px;}
			#yop-poll-deactivate-modal #yop-poll-deactivate-close { float:right; margin-right: 5px; margin-top: 5px; font-size: 14px; color: #000; text-decoration: none;}

			#yop-poll-deactivate-inner { padding: 20px; }

			#yop-poll-deactivate-inner > h3 { margin-top: 0; margin-bottom: 0; }
			#yop-poll-deactivate-inner > p { margin-top: 5px; margin-bottom: 20px; opacity: 0.8; }

			#yop-poll-deactivate-modal ul { margin: 0; }
			#yop-poll-deactivate-modal li { margin: 10px 0; transition: opacity 0.2s ease-in-out; }
			#yop-poll-deactivate-modal li label { display: block; padding: 15px; border-radius: 5px; background: rgba( 200, 215, 225, 0.2 ); }
			#yop-poll-deactivate-modal li:last-of-type { margin-bottom: 0; }
			#yop-poll-deactivate-modal li:hover { opacity: 1 !important; }
			#yop-poll-deactivate-modal li.yop-poll-inactive { opacity: 0.7; }

			#yop-poll-deactivate-modal ul p { display: none; margin: 5px 0 0 24px; opacity: 0.8; }

			#yop-poll-deactivate-modal textarea,
			#yop-poll-deactivate-modal input[type="text"] { display:none; width: 100%; }
			#yop-poll-deactivate-modal textarea { margin-left: 24px; margin-top: 10px; min-height: 65px; width: calc( 100% - 24px ); font-size: 13px; padding: 6px 11px; }
			#yop-poll-deactivate-modal #yop-poll-deactivate-without-feedback { float: right; line-height: 30px; color: #a4afb7; }

			#yop-poll-deactivate-footer { border-top: 1px solid rgba( 200, 215, 225, 0.5 ); background: rgba( 200, 215, 225, 0.15 ); padding: 20px; }

		</style>
		<script>
		jQuery( function( $ ) {
			$( document ).on( 'click', '.wp-admin.plugins-php tr[data-slug="yop-poll"] .row-actions .deactivate a', function( e ) {
				e.preventDefault();  
				$( '#yop-poll-deactivate-modal-wrapper' ).show();
			});
			$(document).on( 'click', '#yop-poll-deactivate-modal form input[type="radio"]', function () {
				$( this ).closest( 'ul' ).find( 'input[type="text"], textarea, p' ).hide();
				$( this ).closest( 'ul' ).children( 'li' ).removeClass( 'yop-poll-inactive yop-poll-active' );
				$( this ).closest( 'li' ).siblings( 'li' ).addClass( 'yop-poll-inactive' );
				$( this ).closest( 'li' ).addClass( 'yop-poll-active' );
				$( this ).closest( 'li' ).find( 'input[type="text"], textarea, p' ).show().focus();
				$( '#yop-poll-feedback-submit' ).attr( 'disabled', false );
			});
			$( document ).on( 'click', '#yop-poll-feedback-submit', function( e ) {
				e.preventDefault();
				$('#yop-poll-deactivate-modal-wrapper').hide();
				$.ajax({
					type 	 : 'POST',
					url 	 : ajaxurl,
					dataType : 'json',
					data 	 : {
						action : 'yop_poll_send_deactivation_feedback',
						_token : $( '#yop-poll_deactivate_token' ).val(),
						data   : $( '#yop-poll-deactivate-modal form' ).serialize()
					},
					complete : function( MLHttpRequest, textStatus, errorThrown ) {
						$( '#yop-poll-deactivate-modal' ).remove();
						window.location.href = $( '.wp-admin.plugins-php tr[data-slug="yop-poll"] .row-actions .deactivate a' ).attr( 'href' );   
					}
				});

			});
			$( document ).on( 'click', '#yop-poll-deactivate-without-feedback', function( e ) {
				e.preventDefault();
				$('#yop-poll-deactivate-modal-wrapper').hide();        
				$('#yop-poll-deactivate-modal-wrapper').remove();
				window.location.href = $('.wp-admin.plugins-php tr[data-slug="yop-poll"] .row-actions .deactivate a').attr('href');
			});
			$(document).on( 'click', '#yop-poll-deactivate-close', function( e ) {
				e.preventDefault();
				$( '#yop-poll-deactivate-modal-wrapper' ).hide();
			});
		});
		</script>
		<?php
	}
	public function clean_recaptcha_url( $url ) {
		if ( false !== strstr( $url, 'recaptcha/api.js' ) ) {
			$url = str_replace( '&#038;', '&', $url );
		}
		return $url;
	}
	public function verify_update() {
        $installed_version = get_option( 'yop_poll_version' );
        if ( $installed_version ) {
            if ( true === version_compare( $installed_version, '6.0.0', '<' ) ) {
                $maintenance = new YOP_POLL_Maintenance();
                $maintenance->activate( false );
            }
            if ( true === version_compare( $installed_version, '6.0.4', '<' ) ) {
                $maintenance  = new YOP_POLL_Maintenance();
                $maintenance->update_to_version_6_0_4();
			}
			if ( true === version_compare( $installed_version, '6.0.5', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_0_5();
			}
			if ( true === version_compare( $installed_version, '6.0.6', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_0_6();
			}
			if ( true === version_compare( $installed_version, '6.0.7', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_0_7();
			}
			if ( true === version_compare( $installed_version, '6.0.8', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_0_8();
			}
			if ( true === version_compare( $installed_version, '6.0.9', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_0_9();
			}
			if ( true === version_compare( $installed_version, '6.1.0', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_1_0();
			}
			if ( true === version_compare( $installed_version, '6.1.1', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_1_1();
			}
			if ( true === version_compare( $installed_version, '6.1.2', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_1_2();
			}
			if ( true === version_compare( $installed_version, '6.1.4', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_1_4();
			}
			if ( true === version_compare( $installed_version, '6.1.5', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_1_5();
			}
			if ( true === version_compare( $installed_version, '6.1.6', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_1_6();
			}
			if ( true === version_compare( $installed_version, '6.1.7', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_1_7();
			}
			if ( true === version_compare( $installed_version, '6.1.8', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_1_8();
			}
			if ( true === version_compare( $installed_version, '6.1.9', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_1_9();
			}
			if ( true === version_compare( $installed_version, '6.2.0', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_2_0();
			}
			if ( true === version_compare( $installed_version, '6.2.1', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_2_1();
			}
			if ( true === version_compare( $installed_version, '6.2.2', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_2_2();
			}
			if ( true === version_compare( $installed_version, '6.2.3', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_2_3();
			}
			if ( true === version_compare( $installed_version, '6.2.4', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_2_4();
			}
			if ( true === version_compare( $installed_version, '6.2.5', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_2_5();
			}
			if ( true === version_compare( $installed_version, '6.2.6', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_2_6();
			}
			if ( true === version_compare( $installed_version, '6.2.7', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_2_7();
			}
			if ( true === version_compare( $installed_version, '6.2.8', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_2_8();
			}
			if ( true === version_compare( $installed_version, '6.2.9', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_2_9();
			}
			if ( true === version_compare( $installed_version, '6.3.0', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_3_0();
			}
			if ( true === version_compare( $installed_version, '6.3.1', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_3_1();
			}
			if ( true === version_compare( $installed_version, '6.3.2', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_3_2();
			}
			if ( true === version_compare( $installed_version, '6.3.3', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_3_3();
			}
			if ( true === version_compare( $installed_version, '6.3.4', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_3_4();
			}
			if ( true === version_compare( $installed_version, '6.3.5', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_3_5();
			}
			if ( true === version_compare( $installed_version, '6.3.6', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_3_6();
			}
			if ( true === version_compare( $installed_version, '6.3.7', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_3_7();
			}
			if ( true === version_compare( $installed_version, '6.3.8', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_3_8();
			}
			if ( true === version_compare( $installed_version, '6.3.9', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_3_9();
			}
			if ( true === version_compare( $installed_version, '6.4.0', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_4_0();
			}
			if ( true === version_compare( $installed_version, '6.4.1', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_4_1();
			}
			if ( true === version_compare( $installed_version, '6.4.2', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_4_2();
			}
			if ( true === version_compare( $installed_version, '6.4.3', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_4_3();
			}
			if ( true === version_compare( $installed_version, '6.4.4', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_4_4();
			}
			if ( true === version_compare( $installed_version, '6.4.5', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_4_5();
			}
			if ( true === version_compare( $installed_version, '6.4.6', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_4_6();
			}
			if ( true === version_compare( $installed_version, '6.4.7', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_4_7();
			}
			if ( true === version_compare( $installed_version, '6.4.8', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_4_8();
			}
			if ( true === version_compare( $installed_version, '6.4.9', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_4_9();
			}
			if ( true === version_compare( $installed_version, '6.5.0', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_0();
			}
			if ( true === version_compare( $installed_version, '6.5.1', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_1();
			}
			if ( true === version_compare( $installed_version, '6.5.2', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_2();
			}
			if ( true === version_compare( $installed_version, '6.5.21', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_21();
			}
			if ( true === version_compare( $installed_version, '6.5.22', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_22();
			}
			if ( true === version_compare( $installed_version, '6.5.23', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_23();
			}
			if ( true === version_compare( $installed_version, '6.5.24', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_24();
			}
			if ( true === version_compare( $installed_version, '6.5.25', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_25();
			}
			if ( true === version_compare( $installed_version, '6.5.26', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_26();
			}
			if ( true === version_compare( $installed_version, '6.5.27', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_27();
			}
			if ( true === version_compare( $installed_version, '6.5.28', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_28();
			}
			if ( true === version_compare( $installed_version, '6.5.29', '<' ) ) {
				$maintenance  = new YOP_POLL_Maintenance();
				$maintenance->update_to_version_6_5_29();
			}
        }
	}
	public function load_translations() {
		$result = load_plugin_textdomain( 'yop-poll', false, YOP_POLL_DIR . '/languages' );
	}
	public function update_login_cookie( $logged_in_cookie ) {
		$_COOKIE[ LOGGED_IN_COOKIE ] = $logged_in_cookie;
	}
	public function is_user_logged_in() {
		if ( true === is_user_logged_in() ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
		die();
	}
	public function build_admin_menu() {
		if ( function_exists( 'add_menu_page' ) ) {
			$page = add_menu_page(
				__( 'Yop Poll', 'yop-poll' ),
				__( 'Yop Poll', 'yop-poll' ),
				'yop_poll_results_own',
				'yop-polls',
				array(
					$this,
					'manage_polls',
				),
				YOP_POLL_URL . 'admin/assets/images/yop-poll-admin-menu-icon16.png',
				'26.6'
			);
			if ( function_exists( 'add_submenu_page' ) ) {
				$subpage = add_submenu_page(
					'yop-polls',
					__( 'All Polls', 'yop-poll' ),
					__( 'All Polls', 'yop-poll' ),
					'yop_poll_results_own',
					'yop-polls',
					array(
						$this,
						'manage_polls',
					)
				);
				if ( $subpage ) {
					$votes_obj = YOP_Poll_Votes::get_instance();
					add_action( 'load-' . $subpage, array( $votes_obj, 'send_votes_to_download' ) );
					$_action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
					switch ( $_action ) {
						case 'results': {
							break;
						}
						case 'view-votes': {
							add_action( 'load-' . $subpage, array( $this, 'add_screen_options_for_view_votes' ) );
							break;
						}
						default: {
							add_action( 'load-' . $subpage, array( $this, 'add_screen_options_for_view_polls' ) );
							break;
						}
					}
				}
				$subpage = add_submenu_page(
					'yop-polls',
					__( 'Add New', 'yop-poll' ),
					__( 'Add New', 'yop-poll' ),
					'yop_poll_add',
					'yop-poll-add-poll',
					array(
						$this,
						'add_new_poll',
					)
				);
				$subpage_bans = add_submenu_page(
					'yop-polls',
					__( 'Bans', 'yop-poll' ),
					__( 'Bans', 'yop-poll' ),
					'yop_poll_results_own',
					'yop-poll-bans',
					array(
						$this,
						'manage_bans',
					)
				);
				if ( $subpage_bans ) {
					add_action( 'load-' . $subpage_bans, array( $this, 'add_screen_options_for_view_bans' ) );
				}
				$subpage_logs = add_submenu_page(
					'yop-polls',
					__( 'Logs', 'yop-poll' ),
					__( 'Logs', 'yop-poll' ),
					'yop_poll_results_own',
					'yop-poll-logs',
					array(
						$this,
						'manage_logs',
					)
				);
				if ( $subpage_logs ) {
					$logs_obj = YOP_Poll_Logs::get_instance();
					add_action( 'load-' . $subpage_logs, array( $logs_obj, 'send_logs_to_download' ) );
					add_action( 'load-' . $subpage_logs, array( $this, 'add_screen_options_for_view_logs' ) );
				}
                $subpage = add_submenu_page(
                    'yop-polls',
                    esc_html__( 'Settings', 'yop-poll' ),
                    esc_html__( 'Settings', 'yop-poll' ),
                    'yop_poll_results_own',
                    'yop-poll-settings',
                    array(
                        $this,
                        'manage_settings',
                    )
                );
				if ( self::$old_version ) {
					$subpage = add_submenu_page(
						'yop-polls',
						__( 'Migrate old records', 'yop-poll' ),
						__( 'Migrate old records', 'yop-poll' ),
						'yop_poll_results_own',
						'yop-poll-migrate',
						array(
							$this,
							'migrate_old_tables',
						)
					);
				}
				$subpage = add_submenu_page(
                    'yop-polls',
                    esc_html__( 'Upgrade to Pro', 'yop-poll' ),
                    esc_html__( 'Upgrade to Pro', 'yop-poll' ),
                    'yop_poll_results_own',
                    'yop-poll-upgrade-to-pro',
                    array(
                        $this,
                        'show_upgrade_to_pro',
                    )
                );
			}
		}
	}
	public function load_dependencies() {
	    $yop_poll_pages = array(
	        'yop-polls',
            'yop-poll-add-poll',
            'yop-poll-bans',
            'yop-poll-logs',
            'yop-poll-settings',
			'yop-poll-migrate',
			'yop-poll-upgrade-to-pro',
		);
	    if ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $yop_poll_pages ) ) {
            $this->load_styles();
			$this->load_scripts();
			add_filter( 'admin_footer_text', array( $this, 'set_admin_footer' ) );
        }
	}
	public function load_scripts() {
        $plugin_settings = YOP_Poll_Settings::get_all_settings();
        if ( false !== $plugin_settings ) {
            $plugin_settings_decoded = unserialize( $plugin_settings );
        }
        //include jquery by default
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'tiny_mce' );
        wp_enqueue_script(
			'jquery-ui-core',
			array(
				'jquery',
			)
		);
        wp_enqueue_script(
			'jquery-ui-datepicker',
			array(
				'jquery',
			)
		);
        wp_enqueue_script(
			'jquery-ui-sortable',
			array(
				'jquery-ui-core',
			)
		);
        wp_enqueue_script(
			'jquery-ui-draggable',
			array(
				'jquery-ui-core',
			)
		);
		wp_enqueue_script(
			'jquery-ui-droppable',
			array(
				'jquery-ui-core',
			)
		);
		if ( true === YOP_POLL_TEST_MODE ) {
			$plugin_admin_js_file = 'admin-' . YOP_POLL_VERSION . '.js';

		} else {
			$plugin_admin_js_file = 'admin-' . YOP_POLL_VERSION . '.min.js';
		}
		wp_enqueue_script(
			'yop',
			YOP_POLL_URL . 'admin/assets/js/' . $plugin_admin_js_file,
			array(
				'jquery',
				'jquery-ui-sortable',
				'jquery-ui-dialog',
				'jquery-ui-datepicker',
			),
			false,
			true
		);
        /* add reCaptcha if enabled */
        if (
            ( true === isset( $plugin_settings_decoded['integrations']['reCaptcha']['enabled'] ) ) &&
            ( 'yes' === $plugin_settings_decoded['integrations']['reCaptcha']['enabled'] ) &&
            ( true === isset( $plugin_settings_decoded['integrations']['reCaptcha']['site-key'] ) ) &&
            ( '' !== $plugin_settings_decoded['integrations']['reCaptcha']['site-key'] ) &&
            ( true === isset( $plugin_settings_decoded['integrations']['reCaptcha']['secret-key'] ) ) &&
            ( '' !== $plugin_settings_decoded['integrations']['reCaptcha']['secret-key'] )
        ) {
            $args = array(
                'render' => 'explicit',
                'onload' => 'YOPPollOnLoadRecaptcha',
            );
            wp_register_script( 'yop-reCaptcha', add_query_arg( $args, 'https://www.google.com/recaptcha/api.js' ), '', null );
            wp_enqueue_script( 'yop-reCaptcha' );
        }
        /* done adding reCaptcha */
        if ( true === isset( $plugin_settings_decoded['messages']['captcha']['accessibility-description'] ) ) {
            $captcha_accessibility_description = str_replace( '[STRONG]', '<strong>', esc_html( $plugin_settings_decoded['messages']['captcha']['accessibility-description'] ) );
            $captcha_accessibility_description = str_replace( '[/STRONG]', '</strong>', $captcha_accessibility_description );
        } else {
            $captcha_accessibility_description = '';
        }
        if ( true === isset( $plugin_settings_decoded['messages']['captcha']['explanation'] ) ) {
            $captcha_explanation = str_replace( '[STRONG]', '<strong>', esc_html( $plugin_settings_decoded['messages']['captcha']['explanation'] ) );
            $captcha_explanation = str_replace( '[/STRONG]', '</strong>', $captcha_explanation );
        } else {
            $captcha_explanation = '';
        }
		wp_localize_script(
			'yop',
			'objectL10n',
			array(
				'yopPollParams' => array(
               		'appUrl' => YOP_POLL_URL,
                	'dateFormat' => self::$date_format,
                	'timeFormat' => self::$time_format,
                	'timeNow' => time(),
               		'votingEnded' => isset( $plugin_settings_decoded['messages']['voting']['poll-ended'] ) ? esc_html( $plugin_settings_decoded['messages']['voting']['poll-ended'] ) : '',
                	'votingNotStarted' => isset( $plugin_settings_decoded['messages']['voting']['poll-not-started'] ) ? esc_html( $plugin_settings_decoded['messages']['voting']['poll-not-started'] ) : '',
                	'newCustomFieldText' => esc_html__( 'New Custom Field', 'yop-poll' ),
                	'deleteTitle'  => esc_html__( 'Warning', 'yop-poll' ),
                	'deletePollMessage' => esc_html__( 'Are you sure you want to delete this poll?', 'yop-poll' ),
                	'deleteBulkPollsSingleMessage' => esc_html__( 'Are you sure you want to delete this poll?', 'yop-poll' ),
                	'deleteBulkPollsMultiMessage' => esc_html__( 'Are you sure you want to delete these polls?', 'yop-poll' ),
                	'clonePollMessage' => esc_html__( 'Are you sure you want to clone this poll?', 'yop-poll' ),
                	'cloneBulkPollsSingleMessage' => esc_html__( 'Are you sure you want to clone this poll?', 'yop-poll' ),
                	'cloneBulkPollsMultiMessage' => esc_html__( 'Are you sure you want to clone these polls?', 'yop-poll' ),
					'resetPollMessage' => esc_html__( 'Are you sure you want to reset votes for this poll?', 'yop-poll' ),
					'resetBulkPollsSingleMessage' => esc_html__( 'Are you sure you want to reset votes for this poll?', 'yop-poll' ),
					'resetBulkPollsMultiMessage' => esc_html__( 'Are you sure you want to reset votes for these polls?', 'yop-poll' ),
					'noBulkActionSelected' => esc_html__( 'No bulk action selected', 'yop-poll' ),
					'noPollsSelectedForBulk' => esc_html__( 'No polls selected', 'yop-poll' ),
					'noBansSelectedForBulk' => esc_html__( 'No bans selected', 'yop-poll' ),
					'noLogsSelectedForBulk' => esc_html__( 'No logs selected', 'yop-poll' ),
					'noVotesSelectedForBulk' => esc_html__( 'No votes selected', 'yop-poll' ),
					'deleteBulkBansSingleMessage' => esc_html__( 'Are you sure you want to delete this ban?', 'yop-poll' ),
					'deleteBulkBansMultiMessage' => esc_html__( 'Are you sure you want to delete these bans?', 'yop-poll' ),
					'deleteBulkLogsSingleMessage' => esc_html__( 'Are you sure you want to delete this log?', 'yop-poll' ),
					'deleteBulkLogsMultiMessage' => esc_html__( 'Are you sure you want to delete these logs?', 'yop-poll' ),
					'deleteBulkVotesSingleMessage' => esc_html__( 'Are you sure you want to delete this vote?', 'yop-poll' ),
					'deleteBulkVotessMultiMessage' => esc_html__( 'Are you sure you want to delete these votes?', 'yop-poll' ),
					'deleteAnswerMessage' => esc_html__( 'Are you sure you want to delete this answer?', 'yop-poll' ),
					'deleteAnswerNotAllowedMessage' => esc_html__( 'Answer can\'t be deleted. At least one answer is required!', 'yop-poll' ),
					'deleteCustomFieldMessage' => esc_html__( 'Are you sure you want to delete this custom field?', 'yop-poll' ),
					'deleteCancelLabel' => esc_html__( 'Cancel', 'yop-poll' ),
					'deleteOkLabel' => esc_html__( 'Ok', 'yop-poll' ),
					'noTemplateSelectedLabel' => esc_html__( 'Before generating the preview a template is required', 'yop-poll' ),
					'noSkinSelectedLabel' => esc_html__( 'Before generating the preview a skin is required', 'yop-poll' ),
					'noNumberOfColumnsDefined' => esc_html__( 'Number of columns is missing', 'yop-poll' ),
					'numberOfColumnsTooBig' => esc_html__( 'Too many columns. Max 12 allowed', 'yop-poll' ),
					'selectHelperText' => esc_html__( 'Click to select', 'yop-poll' ),
					'publishDateImmediately' => esc_html__( 'Publish immediately', 'yop-poll' ),
					'publishDateSchedule' => esc_html__( 'Schedule for', 'yop-poll' ),
					'copyToClipboardSuccess' => esc_html__( 'Code Copied To Clipboard', 'yop-poll' ),
					'copyToClipboardError' => array(
						'press' => esc_html__( 'Press', 'yop-poll' ),
						'copy' => esc_html__( ' to copy', 'yop-poll' ),
						'noSupport' => esc_html__( 'No Support', 'yop-poll' ),
					),
					'elementAdded' => esc_html__( 'Element added', 'yop-poll' ),
					'captchaParams' => array(
						'imgPath' => YOP_POLL_URL . 'public/assets/img/',
						'url' => YOP_POLL_URL . 'app.php',
						'accessibilityAlt' => isset( $plugin_settings_decoded['messages']['captcha']['accessibility-alt'] ) ? esc_html( $plugin_settings_decoded['messages']['captcha']['accessibility-alt'] ) : '',
						'accessibilityTitle' => isset( $plugin_settings_decoded['messages']['captcha']['accessibility-alt'] ) ? esc_html( $plugin_settings_decoded['messages']['captcha']['accessibility-title'] ) : '',
						'accessibilityDescription' => $captcha_accessibility_description,
						'explanation' => $captcha_explanation,
						'refreshAlt' => isset( $plugin_settings_decoded['messages']['captcha']['refresh-alt'] ) ? esc_html( $plugin_settings_decoded['messages']['captcha']['refresh-alt'] ) : '',
						'refreshTitle' => isset( $plugin_settings_decoded['messages']['captcha']['refresh-title'] ) ? esc_html( $plugin_settings_decoded['messages']['captcha']['refresh-title'] ) : '',
					),
					'previewParams' => array(
						'pollPreviewTitle' => esc_html__( 'Poll Preview', 'yop-poll' ),
						'choosePreviewText' => esc_html__( 'Show preview for', 'yop-poll' ),
						'votingText' => esc_html__( 'Voting', 'yop-poll' ),
						'resultsText' => esc_html__( 'Results', 'yop-poll' ),
						'numberOfVotesSingular' => isset( $plugin_settings_decoded['messages']['results']['single-vote'] ) ? esc_html( $plugin_settings_decoded['messages']['results']['single-vote'] ) : '',
						'numberOfVotesPlural' => isset( $plugin_settings_decoded['messages']['results']['multiple-votes'] ) ? esc_html( $plugin_settings_decoded['messages']['results']['multiple-votes'] ) : '',
						'numberOfAnswerSingular' => isset( $plugin_settings_decoded['messages']['results']['single-answer'] ) ? esc_html( $plugin_settings_decoded['messages']['results']['single-answer'] ) : '',
						'numberOfAnswersPlural' => isset( $plugin_settings_decoded['messages']['results']['multiple-answers'] ) ? esc_html( $plugin_settings_decoded['messages']['results']['multiple-answers'] ) : '',
						'annonymousVoteText' => isset( $plugin_settings_decoded['messages']['buttons']['anonymous'] ) ? esc_html( $plugin_settings_decoded['messages']['buttons']['anonymous'] ) : '',
						'wordpressVoteText' => isset( $plugin_settings_decoded['messages']['buttons']['wordpress'] ) ? esc_html( $plugin_settings_decoded['messages']['buttons']['wordpress'] ) : '',
						'facebookVoteText' => isset( $plugin_settings_decoded['messages']['buttons']['facebook'] ) ? esc_html( $plugin_settings_decoded['messages']['buttons']['facebook'] ) : '',
						'googleVoteText' => isset( $plugin_settings_decoded['messages']['buttons']['google'] ) ? esc_html( $plugin_settings_decoded['messages']['buttons']['google'] ) : '',
					),
					'saveParams' => array(
						'noTemplateSelected' => esc_html__( 'Template is missing', 'yop-poll' ),
						'noSkinSelected' => esc_html__( 'Skin is missing', 'yop-poll' ),
						'generalErrorMessage' => esc_html__( ' is missing', 'yop-poll' ),
						'noPollName' => esc_html__( 'Poll name is missing', 'yop-poll' ),
						'noQuestion' => esc_html__( 'Question Text is missing', 'yop-poll' ),
						'noAnswerText' => esc_html__( 'Answer Text is missing', 'yop-poll' ),
						'noAnswerLink' => esc_html__( 'Answer Link is missing', 'yop-poll' ),
						'noAnswerEmbed' => esc_html__( 'Answer Embed is missing', 'yop-poll' ),
						'noOtherLabel' => esc_html__( 'Label for Other is missing', 'yop-poll' ),
						'noMinAnswers' => esc_html__( 'Minimum answers is missing', 'yop-poll' ),
						'noMaxAnswers' => esc_html__( 'Maximum answers is missing', 'yop-poll' ),
						'noCustomFieldName' => esc_html__( 'Custom Field Name is missing', 'yop-poll' ),
						'noStartDate' => esc_html__( 'Poll Start Date is missing', 'yop-poll' ),
						'noEndDate' => esc_html__( 'Poll End Date is missing', 'yop-poll' ),
						'noCustomDate' => esc_html__( 'Custom Date for displaying results is missing', 'yop-poll' ),
						'noShowResultsMoment' => esc_html__( 'Show Results Time is missing', 'yop-poll' ),
						'noShowResultsTo' => esc_html__( 'Show Results To is missing', 'yop-poll' ),
						'noVoteAsWordpress' => esc_html__( 'Vote As WordPress User is missing', 'yop-poll' ),
					),
					'saveBanParams' => array(
						'noBanFor' => esc_html__( 'Ban For is missing', 'yop-poll' ),
						'noBanValue' => esc_html__( 'Ban Value is missing', 'yop-poll' ),
					),
					'deleteBanMessage' => esc_html__( 'Are you sure you want to delete this ban?', 'yop-poll' ),
					'deleteLogMessage' => esc_html__( 'Are you sure you want to delete this log?', 'yop-poll' ),
					'viewLogDetailsQuestionText' => esc_html__( 'Question', 'yop-poll' ),
					'viewLogDetailsAnswerText' => esc_html__( 'Answer', 'yop-poll' ),
					'showLogDetailsLinkText' => esc_html__( 'View Details', 'yop-poll' ),
					'hideLogDetailsLinkText' => esc_html__( 'Hide Details', 'yop-poll' ),
					'numberOfVotesText'      => esc_html__( 'Number of Votes', 'yop-poll' ),
					'resultsParams' => array(
						'singleVote' => esc_html__( 'vote', 'yop-poll' ),
						'multipleVotes' => esc_html__( 'votes', 'yop-poll' ),
					),
					'importOld' => array(
						'gdprEnabledContinue' => esc_html__( 'Got It. Continue with the migration', 'yop-poll' ),
						'gdprEnabledStop' => esc_html__( 'Hold On. I want to change settings', 'yop-poll' ),
						'gdprEnabledGeneral' => esc_html__( 'Please review your settings before continue', 'yop-poll' ),
						'gdprEnabledChoice' => esc_html__( 'Your selection', 'yop-poll' ),
						'gdprEnabledMigrateAsIs' => esc_html__( 'This setting will migrate all data from previous version without any anonymization', 'yop-poll' ),
						'gdprEnabledAnonymizeIp' => esc_html__( 'This setting will migrate all data from previous version but ips will be anonymized', 'yop-poll' ),
						'gdprEnabledNoStore' => esc_html__( 'This setting will migrate everything except ip addresses. ', 'yop-poll' ),
						'response' => esc_html__( 'Response:', 'yop-poll' ),
						'allDone' => esc_html__( 'All done.', 'yop-poll' ),
						'importStarted' => esc_html__( 'Migration started', 'yop-poll' ),
					),
				),
			)
		);
	}
	public function load_styles() {
		wp_enqueue_style( 'yop-admin', YOP_POLL_URL . 'admin/assets/css/admin-' . YOP_POLL_VERSION . '.css' );
		wp_enqueue_style( 'yop-public', YOP_POLL_URL . 'public/assets/css/yop-poll-public-' . YOP_POLL_VERSION . '.css' );
	}
	public function change_page_title( $title ) {
		$_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
		$_action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		switch ( $_page ) {
			case 'yop-polls':{
				switch ( $_action ) {
					case 'edit': {
						$title = esc_html__( 'Edit Poll', 'yop-poll' );
						break;
					}
					case 'view-results': {
						$title = esc_html__( 'View Poll Results', 'yop-poll' );
						break;
					}
					default: {
						$title = esc_html__( 'All Polls', 'yop-poll' );
						break;
					}
				}
				break;
			}
			case 'yop-poll-logs': {
				switch ( $_action ) {
					default: {
						$title = esc_html__( 'View Logs', 'yop-poll' );
						break;
					}
				}
				break;
			}
			case 'yop-poll-bans': {
				switch ( $_action ) {
					case 'add': {
						$title = esc_html__( 'Add Ban', 'yop-poll' );
						break;
					}
					case 'edit': {
						$title = esc_html__( 'Edit Ban', 'yop-poll' );
						break;
					}
					default: {
						$title = esc_html__( 'All Bans', 'yop-poll' );
						break;
					}
				}
				break;
			}
		}
		return $title;
	}
	public function manage_polls() {
		$_action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		$poll_id = isset( $_GET['poll_id'] ) ? sanitize_text_field( wp_unslash( $_GET['poll_id'] ) ) : '';
		switch ( $_action ) {
			case 'edit': {
				$this->show_edit_poll( $poll_id );
				break;
			}
			case 'delete': {
				$this->delete_poll( $poll_id );
				break;
			}
			case 'view-results': {
				$this->display_results( $poll_id );
				break;
			}
            case 'results': {
                $this->build_results( $poll_id );
                break;
            }
            case 'view-votes': {
                $this->display_votes( $poll_id );
                break;
            }
			default: {
				$this->show_polls();
				break;
			}
		}
	}
	public function show_polls() {
		if ( current_user_can( 'yop_poll_results_own' ) ) {
			$show_guide = YOP_Poll_Settings::get_show_guide();
			$template = YOP_POLL_PATH . 'admin/views/polls/view.php';
			$add_new_link = add_query_arg(
				array(
					'page' => 'yop-poll-add-poll',
				),
				admin_url( 'admin.php' )
			);
			$template = YOP_POLL_PATH . 'admin/views/polls/view.php';
			echo YOP_Poll_View::render(
				$template,
				array(
					'polls_list' => self::$polls_list,
					'add_new_link' => $add_new_link,
					'date_format' => self::$date_format,
					'time_format' => self::$time_format,
					'show_guide' => $show_guide,
				)
			);
		}
		return true;
	}
	public function add_new_poll() {
		if ( current_user_can( 'yop_poll_add' ) ) {
			$template = YOP_POLL_PATH . 'admin/views/polls/add/main.php';
			$templates = YOP_Poll_Templates::get_templates();
			$skins = YOP_Poll_Skins::get_skins();
			$allowed_tags_for_templates_and_skins = YOP_Poll_Polls::get_allowed_tags_for_templates_and_skins();
			echo YOP_Poll_View::render(
				$template,
				array(
					'allowed_tags' => $allowed_tags_for_templates_and_skins,
					'templates' => $templates,
					'skins' => $skins,
					'notifications' => YOP_Poll_Settings::get_notifications(),
					'integrations' => YOP_Poll_Settings::get_integrations(),
					'date_format' => self::$date_format,
				)
			);
		}
	}
	public function show_edit_poll( $poll_id ) {
		if ( 0 < intval( $poll_id ) ) {
			$current_user = wp_get_current_user();
			$poll_owner = YOP_Poll_Polls::get_owner( $poll_id );
			if (
				( ( $poll_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_edit_own' ) ) ) ||
				( ( $poll_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_edit_others' ) ) )
			) {
				$poll = YOP_Poll_Polls::get_poll_for_admin( $poll_id );
				if ( false !== $poll ) {
					$template = YOP_POLL_PATH . 'admin/views/polls/edit/main.php';
					$templates = YOP_Poll_Templates::get_templates();
					$skins = YOP_Poll_Skins::get_skins();
					$allowed_tags_for_templates_and_skins = YOP_Poll_Polls::get_allowed_tags_for_templates_and_skins();
					echo YOP_Poll_View::render(
						$template,
						array(
							'allowed_tags' => $allowed_tags_for_templates_and_skins,
							'poll' => $poll,
							'templates' => $templates,
							'skins' => $skins,
							'integrations' => YOP_Poll_Settings::get_integrations(),
							'date_format' => self::$date_format,
						)
					);
				} else {
					esc_html_e( 'You don\'t have sufficient permissions to access this page', 'yop-poll' );
				}
			}
		}
	}
	public function create_poll() {
		if ( current_user_can( 'yop_poll_add' ) && check_ajax_referer( 'yop-poll-add-poll', '_token', false ) ) {
			if ( true === isset( $_POST['poll'] ) ) {
				$poll_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
					json_decode(
						wp_unslash(
							$_POST['poll']
						)
					)
				);
				$result = YOP_Poll_Polls::add( $poll_sanitized );
				if ( true === $result['success'] ) {
					wp_send_json_success(
						array(
							'success' => true,
							'message' => esc_html__( 'Poll successfully added', 'yop-poll' ),
							'pollId' => $result['poll_id'],
						)
					);
				} else {
					wp_send_json_error( $result['error'] );
				}
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function update_poll() {
		$current_user = wp_get_current_user();
		$poll_received = isset( $_POST['poll'] ) ? json_decode( wp_unslash( $_POST['poll'] ) ) : array();
		$poll_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object( $poll_received );
		$poll_owner = YOP_Poll_Polls::get_owner( $poll_sanitized->id );
		if ( check_ajax_referer( 'yop-poll-edit-poll', '_token', false ) ) {
			if (
				( ( $poll_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_edit_own' ) ) ) ||
				( ( $poll_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_edit_others' ) ) )
			) {
				$result = YOP_Poll_Polls::update( $poll_sanitized );
				if ( true === $result['success'] ) {
					wp_send_json_success(
						array(
							'success' => true,
							'message' => esc_html__( 'Poll successfully updated', 'yop-poll' ),
							'newElements' => $result['new-elements'],
							'newSubElements' => $result['new-subelements'],
						)
					);
				} else {
					wp_send_json_error( $result['error'] );
				}
			} else {
				wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function delete_single_poll() {
		if ( check_ajax_referer( 'yop-poll-view-polls', '_token', false ) || check_ajax_referer( 'yop-poll-edit-poll', '_token', false ) ) {
			if ( isset( $_POST['poll_id'] ) && ( 0 < intval( $_POST['poll_id'] ) ) ) {
				$poll_id = sanitize_text_field( wp_unslash( $_POST['poll_id'] ) );
				$current_user = wp_get_current_user();
				$poll_owner = YOP_Poll_Polls::get_owner( $poll_id );
				if (
					( ( $poll_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_delete_own' ) ) ) ||
					( ( $poll_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_delete_others' ) ) )
				) {
					$result = YOP_Poll_Polls::delete( $poll_id );
					if ( true === $result['success'] ) {
						wp_send_json_success( esc_html__( 'Poll successfully deleted', 'yop-poll' ) );
					} else {
						wp_send_json_error( $result['error'] );
					}
				} else {
					wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
				}
			} else {
				wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function delete_bulk_poll() {
		if ( check_ajax_referer( 'yop-poll-bulk-polls', '_token', false ) ) {
			$current_user = wp_get_current_user();
			$polls_received = isset( $_POST['polls'] ) ? json_decode( wp_unslash( $_POST['polls'] ) ) : array();
			$polls_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
				$polls_received
			);
			$success = 0;
			foreach ( $polls_sanitized as $poll ) {
				$poll_owner = YOP_Poll_Polls::get_owner( $poll );
				if (
					( ( $poll_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_delete_own' ) ) ) ||
					( ( $poll_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_delete_others' ) ) )
				) {
					$result = YOP_Poll_Polls::delete( $poll );
					if ( true === $result['success'] ) {
						$success++;
					} else {
						$success--;
					}
				} else {
					$success--;
				}
			}
			if ( $success === intval( count( $polls_sanitized ) ) ) {
				wp_send_json_success(
					_n(
						'Poll successfully deleted',
						'Polls successfully deleted',
						count( $polls_sanitized ),
						'yop-poll'
					)
				);
			} else {
				wp_send_json_error(
					_n(
						'Error deleting poll',
						'Error deleting polls',
						count( $polls_sanitized ),
						'yop-poll'
					)
				);
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function clone_single_poll() {
		if ( check_ajax_referer( 'yop-poll-view-polls', '_token', false ) ) {
			if ( isset( $_POST['poll_id'] ) && ( 0 < intval( $_POST['poll_id'] ) ) ) {
				if ( current_user_can( 'yop_poll_add' ) ) {
					$result = YOP_Poll_Polls::clone_poll( sanitize_text_field( wp_unslash( $_POST['poll_id'] ) ) );
					if ( true === $result['success'] ) {
						wp_send_json_success( esc_html__( 'Poll successfully cloned', 'yop-poll' ) );
					} else {
						wp_send_json_error( $result['error'] );
					}
				} else {
					wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
				}
			} else {
				wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function clone_bulk_poll() {
		if ( check_ajax_referer( 'yop-poll-bulk-polls', '_token', false ) ) {
			$polls_received = isset( $_POST['polls'] ) ? json_decode( wp_unslash( $_POST['polls'] ) ) : array();
			$polls_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
				$polls_received
			);
			$success = 0;
			foreach ( $polls_sanitized as $poll ) {
				if ( current_user_can( 'yop_poll_add' ) ) {
					$result = YOP_Poll_Polls::clone_poll( $poll );
					if ( true === $result['success'] ) {
						$success++;
					} else {
						$success--;
					}
				} else {
					$success--;
				}
			}
			if ( $success === intval( count( $polls_sanitized ) ) ) {
				wp_send_json_success(
					_n(
						'Poll successfully cloned',
						'Polls successfully cloned',
						count( $polls_sanitized ),
						'yop-poll'
					)
				);
			} else {
				wp_send_json_error(
					_n(
						'Error cloning poll',
						'Error cloning polls',
						count( $polls_sanitized ),
						'yop-poll'
					)
				);
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function reset_single_poll() {
		if ( check_ajax_referer( 'yop-poll-view-polls', '_token', false ) ) {
			$poll_received = isset( $_POST['poll_id'] ) ? sanitize_text_field( wp_unslash( $_POST['poll_id'] ) ) : '';
			if ( '' !== $poll_received ) {
				$result = YOP_Poll_Polls::reset_poll( $poll_received );
				if ( true === $result['success'] ) {
					wp_send_json_success( esc_html__( 'Votes successfully reset', 'yop-poll' ) );
				} else {
					wp_send_json_error( esc_html__( 'Error resetting votes', 'yop-poll' ) );
				}
			} else {
				wp_send_json_error( esc_html__( 'You are not allowed to perform this actionn', 'yop-poll' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function reset_bulk_poll() {
		if ( check_ajax_referer( 'yop-poll-bulk-polls', '_token', false ) ) {
			$polls_received = isset( $_POST['polls'] ) ? json_decode( wp_unslash( $_POST['polls'] ) ) : array();
			$polls_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
				$polls_received
			);
			$success = 0;
			foreach ( $polls_sanitized as $poll ) {
				if ( current_user_can( 'yop_poll_add' ) ) {
					$result = YOP_Poll_Polls::reset_poll( $poll );
					if ( true === $result['success'] ) {
						$success++;
					} else {
						$success--;
					}
				} else {
					$success--;
				}
			}
			if ( $success === intval( count( $polls_sanitized ) ) ) {
				wp_send_json_success( esc_html__( 'Votes successfully reset', 'yop-poll' ) );
			} else {
				wp_send_json_error( esc_html__( 'Error resetting votes', 'yop-poll' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function display_results( $poll_id ) {
		if ( current_user_can( 'yop_poll_results_own' ) ) {
			$template = YOP_POLL_PATH . 'admin/views/results/view.php';
			$poll = YOP_Poll_Polls::get_poll_for_admin( $poll_id );
			echo YOP_Poll_View::render(
				$template,
				array(
					'poll' => $poll,
				)
			);
		}
	}
	public function build_results( $poll_id ) {
        if ( current_user_can( 'yop_poll_results_own' ) ) {
            $params['q'] = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
            $params['order_by'] = isset( $_GET['order_by'] ) ? sanitize_text_field( wp_unslash( $_GET['order_by'] ) ) : '';
            $params['sort_order'] = isset( $_GET['sort_order'] ) ? sanitize_text_field( wp_unslash( $_GET['sort_order'] ) ) : 'asc';
            $params['page_no'] = isset( $_GET['page_no'] ) ? sanitize_text_field( wp_unslash( $_GET['page_no'] ) ) : '1';
            $template = YOP_POLL_PATH . 'admin/views/results/view.php';
            $poll = YOP_Poll_Polls::get_poll_for_admin( $poll_id );
            if ( $poll ) {
                $voters = YOP_Poll_Votes::get_poll_voters_sorted( $poll_id );
                $limit = 10;
                $page = 1;
                $offset = 0;
                $cf_string = '';
                $cf_hidden = '';
                $cf_total_pages = 0;
                $customs_count = 0;
                $total_votes_per_question = array();
                $total_voters_per_question = array();
                $votes_count = $GLOBALS['wpdb']->get_var(
					$GLOBALS['wpdb']->prepare(
						"SELECT COUNT(*) FROM `{$GLOBALS['wpdb']->yop_poll_votes}` WHERE `poll_id` = %d AND `status` = 'active'",
						array( $poll_id )
					)
				);
                $total_pages = ceil( $votes_count / $limit );
                $query  = "SELECT * FROM `{$GLOBALS['wpdb']->yop_poll_votes}` WHERE `poll_id` = %d AND `status` = 'active' limit $offset, $limit";
                $votes = $GLOBALS['wpdb']->get_results(
					$GLOBALS['wpdb']->prepare(
						$query,
						array( $poll_id )
					)
				);

                $all_votes_query = "SELECT * FROM `{$GLOBALS['wpdb']->yop_poll_votes}` WHERE `poll_id` = %d AND `status` = 'active'";
                $all_votes = $GLOBALS['wpdb']->get_results(
					$GLOBALS['wpdb']->prepare(
						$all_votes_query,
						array( $poll_id )
					)
				);

                $other_answers = array();
                foreach ( $all_votes as $av ) {
                    $vote_data = unserialize( $av->vote_data );
					$user_type = $av->user_type;
                    foreach ( $vote_data['elements'] as $ave ) {
                        $question_aswers = array();
                        if ( 'question' === $ave['type'] ) {
                            foreach ( $ave['data'] as $answers ) {
                                if ( 0 == $answers['id'] ) {
                                    $question_aswers[] = $answers['data'];
                                }
                            }
                            if ( isset( $total_votes_per_question[$ave['id']] ) ) {
                                $total_votes_per_question[$ave['id']]++;
                            } else {
                                $total_votes_per_question[$ave['id']] = 1;
                            }
                            if ( isset( $total_voters_per_question[$ave['id']][$user_type] ) ) {
                                $total_voters_per_question[$ave['id']][$user_type]++;
                            } else {
                                $total_voters_per_question[$ave['id']][$user_type] = 1;
                            }
                            $other_answers[] = array(
								'question_id' => $ave['id'],
								'other_answers' => $question_aswers,
							);
                        }
                    }
                }
                $other_answers = YOP_Poll_Helper::group_other_answers( $other_answers );
                if ( count( $votes ) > 0 ) {
                    $cf_hidden .= '<input type="hidden" name="cf_total_pages" id="cf-total-pages" value="' . esc_attr( $total_pages ) . '">';
                    $cf_hidden .= '<input type="hidden" name="cf_page" id="cf-page" value="' . esc_attr( $page ) . '">';
                    foreach ( $votes as $vote ) {
                        $vote_data = unserialize( $vote->vote_data );
                        $custom_fields = array();
                        foreach ( $vote_data['elements'] as $vde ) {
                            if ( 'custom-field' === $vde['type'] ) {
                                $custom_fields[] = array(
									'id' => $vde['id'],
									'data' => isset( $vde['data'][0] ) ? $vde['data'][0] : '',
								);
                                $customs_count++;
                            }
                        }
                        if ( count( $custom_fields ) > 0 ) {
                            $cf_total_pages = ceil( count( $custom_fields ) / $limit );
                            $cf_string .= '<tr>';
                            foreach ( $custom_fields as $cf ) {
                                $cf_string .= '<td>' . esc_html( $cf['data'] ) . '</td>';
                            }
                            $cf_string .= '</tr>';
                        } else {
                            $cf_total_pages = 0;
                        }
                    }
                }
                echo YOP_Poll_View::render(
                    $template,
                    array(
                        'params' => $params,
                        'poll' => $poll,
                        'total_votes' => $votes_count,
                        'total_pages' => $total_pages,
                        'voters' => $voters,
                        'cf_string' => $cf_string,
                        'cf_hidden' => $cf_hidden,
                        'cf_total_pages' => $cf_total_pages,
                        'other_answers' => $other_answers,
                        'total_votes_per_question' => $total_votes_per_question,
                        'total_voters_per_question' => $total_voters_per_question,
                    )
                );
            } else {
                $error = esc_html__( 'Invalid poll', 'yop-poll' );
                $template = YOP_POLL_PATH . 'admin/views/general/error.php';
                echo YOP_Poll_View::render(
                    $template,
                    array(
                        'error' => $error,
                    )
                );
            }
        }
    }
    public function display_votes( $poll_id ) {
        if ( current_user_can( 'yop_poll_results_own' ) ) {
            $template = YOP_POLL_PATH . 'admin/views/results/votes.php';
            $poll = YOP_Poll_Polls::get_poll_for_admin( $poll_id );
            if ( $poll ) {
				if ( true === isset( $_REQUEST['s'] ) ) {
					$_search_term = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
				} else {
					$_search_term = '';
				}
                echo YOP_Poll_View::render(
                    $template,
                    array(
                        'poll' => $poll,
                        'votes_list' => self::$votes_list,
                        'date_format' => self::$date_format,
                        'time_format' => self::$time_format,
						'search_term' => $_search_term,
                    )
                );
            } else {
                $error = esc_html__( 'Invalid poll', 'yop-poll' );
                $template = YOP_POLL_PATH . 'admin/views/general/error.php';
                echo YOP_Poll_View::render(
                    $template,
                    array(
                        'error' => $error,
                    )
                );
            }
        }
    }
    public function get_yop_poll_votes_customs() {
        if ( check_ajax_referer( 'yop-poll-get-vote-customs', '_token', false ) ) {
            $limit = 10;
            if ( isset( $_POST['page'] ) && '' !== $_POST['page'] ) {
                $page = sanitize_text_field( wp_unslash( $_POST['page'] ) );
                $offset = $limit * ( $page - 1 );
            } else {
                $page = 1;
                $offset = 0;
            }
			$poll_id = isset( $_POST['poll_id'] ) ? sanitize_text_field( wp_unslash( $_POST['poll_id'] ) ) : '';
            $votes = YOP_Poll_Votes::get_vote_by_poll( $poll_id, $limit, $offset );
            $cf_string = '';
            if ( count( $votes ) > 0 ) {
                foreach ( $votes as $vote ) {
                    $vote_data = unserialize( $vote->vote_data );
                    $custom_fields = array();
                    foreach ( $vote_data['elements'] as $vde ) {
                        if ( 'custom-field' === $vde['type'] ) {
                            $custom_fields[] = array(
								'id' => $vde['id'],
								'data' => isset( $vde['data'][0] ) ? $vde['data'][0] : '',
							);
                        }
                    }
                    if ( count( $custom_fields ) > 0 ) {
                        $cf_string .= '<tr>';
                        foreach ( $custom_fields as $cf ) {
                            $cf_string .= '<td>' . $cf['data'] . '</td>';
                        }
                        $cf_string .= '</tr>';
                    }
                }
                wp_send_json_success( $cf_string );
            } else {
                wp_send_json_success( $cf_string );
            }
        } else {
            wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
        }
    }
	public function manage_support() {
		$template = YOP_POLL_PATH . 'admin/views/support/view.php';
		echo YOP_Poll_View::render( $template );
	}
	public function migrate_old_tables() {
		$template = YOP_POLL_PATH . 'admin/views/general/migrate-old-tables.php';
		echo YOP_Poll_View::render( $template );
	}
	public function manage_logs() {
		if ( current_user_can( 'yop_poll_add' ) ) {
			$template = YOP_POLL_PATH . 'admin/views/logs/view.php';
			$search_term = isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '';
			echo YOP_Poll_View::render(
				$template,
				array(
					'logs' => self::$logs_list,
					'search_term' => $search_term,
					'date_format' => self::$date_format,
					'time_format' => self::$time_format,
				)
			);
		}
	}
	public function get_log_details() {
        if ( check_ajax_referer( 'yop-poll-view-log-details', '_token', false ) ) {
            if ( isset( $_POST['log_id'] ) && ( 0 < intval( $_POST['log_id'] ) ) ) {
				$log_id = sanitize_text_field( wp_unslash( $_POST['log_id'] ) );
                $log_owner = YOP_Poll_Logs::get_owner( $log_id );
				$current_user = wp_get_current_user();
                if ( $log_owner == $current_user->ID ) {
                    $results = YOP_Poll_Logs::get_log_details( $log_id );
                    $details_string = '';
                    foreach ( $results as $res ) {
                        if ( 'custom-field' === $res['question'] ) {
                            $details_string .= '<div>' . esc_html__( 'Custom Field', 'yop-poll' ) . ': ' . $res['caption'];
                            $details_string .= '<div style="padding-left: 10px;">' . esc_html__( 'Answer', 'yop-poll' ) . ': ' .
                                $res['answers'][0]['answer_value'] . '</div>';
                        } else {
                            $details_string .= '<div>' . esc_html__( 'Question', 'yop-poll' ) . ': ' . $res['question'];
                            foreach ( $res['answers'] as $ra ) {
                                $details_string .= '<div style="padding-left: 10px;">' . esc_html__( 'Answer', 'yop-poll' ) . ': ' . $ra['answer_value'] . '</div>';
                            }
                        }
                        $details_string .= '</div>';
                    }
                    wp_send_json_success(
						array(
							'details' => $details_string,
						)
					);
                } else {
                    wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
                }
            } else {
                wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
            }
        } else {
            wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
        }
    }
	public function manage_bans() {
		$_action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		switch ( $_action ) {
			case 'add': {
				$this->show_add_ban();
				break;
			}
			case 'edit': {
				$ban_id = isset( $_GET['ban_id'] ) ? sanitize_text_field( wp_unslash( $_GET['ban_id'] ) ) : '';
				$this->show_edit_ban( $ban_id );
				break;
			}
			default: {
				$this->show_bans();
				break;
			}
		}
	}
	public function show_bans() {
		if ( current_user_can( 'yop_poll_add' ) ) {
			$template = YOP_POLL_PATH . 'admin/views/bans/view.php';
			echo YOP_Poll_View::render(
				$template,
				array(
					'bans' => self::$bans_list,
					'date_format' => self::$date_format,
					'time_format' => self::$time_format,
				)
			);
		}
	}
	public function show_add_ban() {
		if ( current_user_can( 'yop_poll_add' ) ) {
			$polls = YOP_Poll_Polls::get_names();
			$template = YOP_POLL_PATH . 'admin/views/bans/add.php';
			echo YOP_Poll_View::render(
				$template,
				array(
					'polls' => $polls,
				)
			);
		}
	}
	public function create_ban() {
		if ( current_user_can( 'yop_poll_add' ) && check_ajax_referer( 'yop-poll-add-ban', '_token', false ) ) {
			$ban_received = isset( $_POST['ban'] ) ? json_decode( wp_unslash( $_POST['ban'] ) ) : array();
			$ban_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
				$ban_received
			);
			$result = YOP_Poll_Bans::add( $ban_sanitized );
			if ( true === $result['success'] ) {
				wp_send_json_success( esc_html__( 'Ban successfully added', 'yop-poll' ) );
			} else {
				wp_send_json_error( $result['error'] );
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function show_edit_ban( $ban_id ) {
		if ( 0 < intval( $ban_id ) ) {
			$current_user = wp_get_current_user();
			$ban_owner = YOP_Poll_Bans::get_owner( $ban_id );
			if (
				( ( $ban_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_edit_own' ) ) ) ||
				( ( $ban_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_edit_others' ) ) )
			) {
				$ban = YOP_Poll_Bans::get_ban( $ban_id );
				if ( false !== $ban ) {
					$polls = YOP_Poll_Polls::get_names();
					$template = YOP_POLL_PATH . 'admin/views/bans/edit.php';
					echo YOP_Poll_View::render(
						$template,
						array(
							'ban' => $ban['ban'],
							'polls' => $polls,
						)
					);
				} else {
					esc_html_e( 'You don\'t have sufficient permissions to access this page', 'yop-poll' );
				}
			}
		}
	}
	public function delete_single_ban() {
		if ( check_ajax_referer( 'yop-poll-delete-ban', '_token', false ) ) {
			if ( isset( $_POST['ban_id'] ) && ( 0 < intval( $_POST['ban_id'] ) ) ) {
				$ban_id = sanitize_text_field( wp_unslash( $_POST['ban_id'] ) );
				$current_user = wp_get_current_user();
				$ban_owner = YOP_Poll_Bans::get_owner( $ban_id );
				if (
					( ( $ban_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_delete_own' ) ) ) ||
					( ( $ban_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_delete_others' ) ) )
				) {
					$result = YOP_Poll_Bans::delete( $ban_id );
					if ( true === $result['success'] ) {
						wp_send_json_success( esc_html__( 'Ban successfully deleted', 'yop-poll' ) );
					} else {
						wp_send_json_error( $result['error'] );
					}
				} else {
					wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
				}
			} else {
				wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function update_ban() {
		$ban_received = isset( $_POST['ban'] ) ? json_decode( wp_unslash( $_POST['ban'] ) ) : array();
		$ban_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
			$ban_received
		);
		$ban_owner = YOP_Poll_Bans::get_owner( $ban_sanitized->ban->id );
		$current_user = wp_get_current_user();
		if ( check_ajax_referer( 'yop-poll-edit-ban', '_token', false ) ) {
			if (
				( ( $ban_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_edit_own' ) ) ) ||
				( ( $ban_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_edit_others' ) ) )
			) {
				$result = YOP_Poll_Bans::update( $ban_sanitized );
				if ( true === $result['success'] ) {
					wp_send_json_success( esc_html__( 'Ban successfully updated', 'yop-poll' ) );
				} else {
					wp_send_json_error( $result['error'] );
				}
			} else {
				wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function delete_bulk_ban() {
		if ( check_ajax_referer( 'yop-poll-bulk-bans', '_token', false ) ) {
			$bans_received = isset( $_POST['bans'] ) ? json_decode( wp_unslash( $_POST['bans'] ) ) : array();
			$bans_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
				$bans_received
			);
			$success = 0;
			$current_user = wp_get_current_user();
			foreach ( $bans_sanitized as $ban ) {
				$ban_owner = YOP_Poll_Bans::get_owner( $ban );
				if (
					( ( $ban_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_delete_own' ) ) ) ||
					( ( $ban_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_delete_others' ) ) )
				) {
					$result = YOP_Poll_Bans::delete( $ban );
					if ( true === $result['success'] ) {
						$success++;
					} else {
						$success--;
					}
				} else {
					$success--;
				}
			}
			if ( intval( count( $bans_sanitized ) ) === $success ) {
				wp_send_json_success(
					_n(
						'Ban successfully deleted',
						'Bans successfully deleted',
						count( $bans_sanitized ),
						'yop-poll'
					)
				);
			} else {
				wp_send_json_error(
					_n(
						'Error deleting ban',
						'Error deleting bans',
						count( $bans_sanitized ),
						'yop-poll'
					)
				);
			}
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
		}
	}
	public function record_vote() {
		$vote_data_received = isset( $_POST['data'] ) ? json_decode( wp_unslash( $_POST['data'] ) ) : array();
		$vote_data_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
			$vote_data_received
		);
		if ( isset( $vote_data_sanitized->pollId ) && ( 0 < intval( $vote_data_sanitized->pollId ) ) ) {
			if ( check_ajax_referer( 'yop-poll-vote-' . $vote_data_sanitized->pollId, '_token', false ) ) {
				$result = YOP_Poll_Votes::add( $vote_data_sanitized );
				if ( true === $result['success'] ) {
					wp_send_json_success( esc_html__( 'Vote Recorded', 'yop-poll' ) );
				} else {
					wp_send_json_error( $result['error'] );
				}
			} else {
				wp_send_json_error( esc_html__( 'Invalid data 1', 'yop-poll' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'Invalid data 2', 'yop-poll' ) );
		}
	}
	public function record_wordpress_vote() {
		if ( isset( $_GET['poll_id'] ) && ( 0 < intval( $_GET['poll_id'] ) ) ) {
			$template = YOP_POLL_PATH . 'admin/views/general/addnewwordpressvote.php';
			echo YOP_Poll_View::render(
				$template,
				array(
					'poll_id' => sanitize_text_field( wp_unslash( $_GET['poll_id'] ) ),
				)
			);
		} else {
			echo 'no go';
		}
		wp_die();
	}
	public function get_vote_details() {
        if ( check_ajax_referer( 'yop-poll-view-vote-details', '_token', false ) ) {
            if ( isset( $_POST['voteid'] ) && ( intval( $_POST['voteid'] ) > 0 ) ) {
				$vote_id = sanitize_text_field( wp_unslash( $_POST['voteid'] ) );
                $results = YOP_Poll_Votes::get_vote_details( $vote_id );
                $details_string = '';
                foreach ( $results as $res ) {
                    if ( 'custom-field' === $res['question'] ) {
                        $details_string .= '<div>' . esc_html__( 'Custom Field', 'yop-poll' ) . ': ' . $res['caption'];
                        $details_string .= '<div style="padding-left: 10px;">' . esc_html__( 'Answer', 'yop-poll' ) . ': ' .
                            esc_html( $res['answers'][0]['answer_value'] ) . '</div>';
                    } else {
                        $details_string .= '<div>' . esc_html__( 'Question', 'yop-poll' ) . ': ' . $res['question'];
                        foreach ( $res['answers'] as $ra ) {
                            $details_string .= '<div style="padding-left: 10px;">' . esc_html__( 'Answer', 'yop-poll' ) . ': ' . esc_html( $ra['answer_value'] ) . '</div>';
                        }
                    }
                    $details_string .= '</div>';
                    }
                    wp_send_json_success(
						array(
							'details' => $details_string,
						)
					);
            } else {
                wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
            }
        } else {
            wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
        }
    }
    public function delete_single_vote() {
        if ( check_ajax_referer( 'yop-poll-delete-vote', '_token', false ) ) {
            $poll_id = isset( $_POST['poll_id'] ) ? sanitize_text_field( wp_unslash( $_POST['poll_id'] ) ) : '';
            $vote_id = isset( $_POST['vote_id'] ) ? sanitize_text_field( wp_unslash( $_POST['vote_id'] ) ) : '';
            $success = 0;
            $current_user = wp_get_current_user();
            $vote_owner = YOP_Poll_Votes::get_owner( $vote_id );
            if (
                ( ( $vote_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_delete_own' ) ) ) ||
                ( ( $vote_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_delete_others' ) ) )
            ) {
                if ( $vote_id > 0 ) {
                    $result = YOP_Poll_Votes::delete_vote( $vote_id, $poll_id );
                    if ( true === $result ) {
                        wp_send_json_success( esc_html__( 'Vote successfully deleted', 'yop-poll' ) );
                    } else {
                        wp_send_json_error( esc_html__( 'Error deleting vote', 'yop-poll' ) );
                    }
                }
            } else {
                wp_send_json_error( esc_html__( 'Error deleting vote', 'yop-poll' ) );
            }
        } else {
            wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
        }
    }
    public function delete_bulk_votes() {
        if ( check_ajax_referer( 'yop-poll-bulk-votes', '_token', false ) ) {
            $votes_received = isset( $_POST['votes'] ) ? json_decode( wp_unslash( $_POST['votes'] ) ) : array();
			$votes_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
				$votes_received
			);
            $poll_id = isset( $_POST['poll_id'] ) ? sanitize_text_field( wp_unslash( $_POST['poll_id'] ) ) : '';
            $success = 0;
            $current_user = wp_get_current_user();
            foreach ( $votes_sanitized as $vote ) {
                $vote_owner = YOP_Poll_Votes::get_owner( $vote );
                if (
                    ( ( $vote_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_delete_own' ) ) ) ||
                    ( ( $vote_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_delete_others' ) ) )
                ) {
                    $votes_to_be_deleted[] = $vote;
                    if ( count( $votes_to_be_deleted ) > 0 ) {
                        $result = YOP_Poll_Votes::delete_vote( $vote, $poll_id );
                        if ( true === $result ) {
                            $success++;
                        } else {
                            $success--;
                        }
                    }
                } else {
                    $success--;
                }
            }
            if ( $success === intval( count( $votes_sanitized ) ) ) {
                wp_send_json_success(
					_n(
                        'Vote successfully deleted',
                        'Votes successfully deleted',
                        count( $votes_sanitized ),
                        'yop-poll'
					)
                );
            } else {
                wp_send_json_error(
					_n(
                        'Error deleting vote',
                        'Error deleting votes',
                        count( $votes_sanitized ),
                        'yop-poll'
					)
                );
            }
        } else {
            wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
        }
    }
    public function delete_single_log() {
        if ( check_ajax_referer( 'yop-poll-delete-log', '_token', false ) ) {
            if ( isset( $_POST['log_id'] ) && ( 0 < intval( $_POST['log_id'] ) ) ) {
				$log_id = sanitize_text_field( wp_unslash( $_POST['log_id'] ) );
                $log_owner = YOP_Poll_Logs::get_owner( $log_id );
				$current_user = wp_get_current_user();
                if (
                    ( ( $log_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_delete_own' ) ) ) ||
                    ( ( $log_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_delete_others' ) ) )
                ) {
                    $result = YOP_Poll_Logs::delete( $log_id );
                    if ( true === $result['success'] ) {
                        wp_send_json_success( esc_html__( 'Log successfully deleted', 'yop-poll' ) );
                    } else {
                        wp_send_json_error( $result['error'] );
                    }
                } else {
                    wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
                }
            } else {
                wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
            }
        } else {
            wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
        }
    }
    public function delete_bulk_log() {
        if ( check_ajax_referer( 'yop-poll-bulk-logs', '_token', false ) ) {
            $logs_received = isset( $_POST['logs'] ) ? json_decode( wp_unslash( $_POST['logs'] ) ) : array();
			$logs_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
				$logs_received
			);
            $success = 0;
			$current_user = wp_get_current_user();
            foreach ( $logs_sanitized as $log ) {
                $log_owner = YOP_Poll_Logs::get_owner( $log );
                if (
                    ( ( $log_owner === $current_user->ID ) && ( current_user_can( 'yop_poll_delete_own' ) ) ) ||
                    ( ( $log_owner !== $current_user->ID ) && ( current_user_can( 'yop_poll_delete_others' ) ) )
                ) {
                    $result = YOP_Poll_Logs::delete( $log );
                    if ( true === $result['success'] ) {
                        $success++;
                    } else {
                        $success--;
                    }
                } else {
                    $success--;
                }
            }
            if ( $success === intval( count( $logs_sanitized ) ) ) {
                wp_send_json_success(
					_n(
                        'Log successfully deleted',
                        'Logs successfully deleted',
                        count( $logs_sanitized ),
                        'yop-poll'
					)
                );
            } else {
                wp_send_json_error(
					_n(
                        'Error deleting log',
                        'Error deleting logs',
                        count( $logs_sanitized ),
                        'yop-poll'
					)
                );
            }
        } else {
            wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
        }
    }
	public function manage_settings() {
        $unserialized_settings = array();
        if ( current_user_can( 'yop_poll_add' ) ) {
            $template = YOP_POLL_PATH . 'admin/views/settings/view.php';
            $yop_poll_settings = get_option( 'yop_poll_settings' );
            if ( $yop_poll_settings ) {
                $unserialized_settings = unserialize( $yop_poll_settings );
            }
            echo YOP_Poll_View::render(
				$template,
				array( 'settings' => $unserialized_settings )
			);
        }
    }
	public function save_settings() {
        if ( current_user_can( 'yop_poll_add' ) ) {
            if ( check_ajax_referer( 'yop-poll-update-settings', '_token', false ) ) {
				$settings_received = isset( $_POST['settings'] ) ? json_decode( wp_unslash( $_POST['settings'] ) ) : array();
				$settings_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
					$settings_received
				);
                $result = YOP_Poll_Settings::save_settings( $settings_sanitized );
                if ( true === $result['success'] ) {
                    wp_send_json_success( esc_html__( 'Settings updated', 'yop-poll' ) );
                } else {
                    wp_send_json_error( $result['error'] );
                }
            } else {
                wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
            }
        } else {
            wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
        }
	}
	public function add_votes_manually() {
		if ( isset( $_POST['id'] ) && ( 0 < intval( $_POST['id'] ) ) ) {
			if ( check_ajax_referer( 'yop-poll-add-votes-manually', '_token', false ) ) {
				$poll_id = intval( sanitize_text_field( wp_unslash( $_POST['id'] ) ) );
				$votes_data_received = isset( $_POST['data'] ) ? json_decode( wp_unslash( $_POST['data'] ) ) : array();
				$votes_data_sanitized = YOP_Poll_Helper::sanitize_text_or_array_or_object(
					$votes_data_received
				);
				$result = YOP_Poll_Polls::add_votes_manually( $poll_id, $votes_data_sanitized );
				if ( true === $result['success'] ) {
					wp_send_json_success( esc_html__( 'Votes Succesfully Added', 'yop-poll' ) );
				} else {
					wp_send_json_error( $result['error'] );
				}
			} else {
				wp_send_json_error( esc_html__( 'Invalid data 1', 'yop-poll' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'Invalid data 2', 'yop-poll' ) );
		}
	}
	public function create_poll_for_frontend() {
		if ( ( true === isset( $_POST['poll_id'] ) ) && ( '' !== $_POST['poll_id'] ) ) {
			$params = array();
			$poll_id = sanitize_text_field( wp_unslash( $_POST['poll_id'] ) );
			$params['tracking_id'] = isset( $_POST['tracking_id'] ) ? sanitize_text_field( wp_unslash( $_POST['tracking_id'] ) ) : '';
			$params['page_id'] = isset( $_POST['page_id'] ) ? sanitize_text_field( wp_unslash( $_POST['page_id'] ) ) : '';
			$params['show_results'] = isset( $_POST['show_results'] ) ? sanitize_text_field( wp_unslash( $_POST['show_results'] ) ) : '';
			$poll_for_output = YOP_Poll_Public::generate_poll_for_ajax( $poll_id, $params );
			if ( false !== $poll_for_output ) {
				wp_send_json_success( $poll_for_output );
			} else {
				wp_send_json_error( esc_html__( 'Error generating poll', 'yop-poll' ) );
				wp_die();
			}
		}
	}
	public function stop_showing_guide() {
		YOP_Poll_Settings::update_show_guide( 'no' );
		wp_send_json_success( esc_html__( 'Setting Updated', 'yop-poll' ) );
	}
	public function send_guide() {
		$user_input = isset( $_POST['input'] ) ? sanitize_text_field( wp_unslash( $_POST['input'] ) ) : '';
		$url = 'https://admin.yoppoll.com/';
        $request_string = array(
            'body'       => array(
                'action'  => 'send-guide',
                'input' => $user_input,
            ),
            'user-agent' => 'WordPress/' . YOP_POLL_VERSION . ';',
        );
        $result = wp_remote_post( $url, $request_string );
        if ( ! is_wp_error( $result ) && ( 200 === $result['response']['code'] ) ) {
            $response = unserialize( $result['body'] );
        } else {
            $response = null;
		}
		YOP_Poll_Settings::update_show_guide( 'no' );
		wp_send_json_success( esc_html__( 'Guide Sent', 'yop-poll' ) );
	}
	public function show_upgrade_to_pro() {
		$upgrade_page = rand( 1, 2 );
		if ( 1 === $upgrade_page ) {
			$template = YOP_POLL_PATH . 'admin/views/general/upgrade-page.php';
		} else {
			$template = YOP_POLL_PATH . 'admin/views/general/upgrade-page-blue.php';
		}
		echo YOP_Poll_View::render(
			$template,
			array(
				'link' => menu_page_url( 'yop-polls', false ),
			)
		);
	}
	public function send_deactivation_feedback() {
		$_token = isset( $_POST['_token'] ) ? sanitize_text_field( wp_unslash( $_POST['_token'] ) ) : '';
		if ( empty( $_token ) || ! wp_verify_nonce( $_token, 'yop-poll_deactivation' ) ) {
			wp_die( 0 );
		}
		if ( isset( $_POST['data'] ) ) {
			$data = sanitize_text_field( wp_unslash( $_POST['data'] ) );
	        parse_str( $data, $form_data );
		}
		$subject = 'YOP Poll Deactivation Notification';
		$message = '';
		if ( isset( $form_data['yop-poll_disable_reason'] ) ) {
			$message .= 'Reason: ' . sanitize_text_field( $form_data['yop-poll_disable_reason'] );
		}
	    if ( isset( $form_data['yop-poll_deactivate_details'] ) ) {
	        $message .= "\n\r";
	        $message .= 'Message: ' . sanitize_text_field( implode( '', $form_data['yop-poll_deactivate_details'] ) );
	    } else {
			$message = 'No extra details given';
		}
		$email_headers = array(
			'From: Wordpress Deactivation Notice <deactivate@yop-poll.com>',
			'Content-Type: text/plain',
        );
	    $success = wp_mail( array( 'noreply@yop-poll.com' ), $subject, $message, $email_headers );
		wp_die();
	}
	public function login_user() {
		$new_tokens = array();
		$username = isset( $_POST['username'] ) ? $_POST['username'] : '';
		$password = isset( $_POST['password'] ) ? $_POST['password'] : '';
		$_token = isset( $_POST['_token'] ) ? sanitize_text_field( wp_unslash( $_POST['_token'] ) ) : '';
		$poll_id = isset( $_POST['pollId'] ) ? sanitize_text_field( wp_unslash( $_POST['pollId'] ) ) : '';
		$polls_on_page = isset( $_POST['pollsOnPage'] ) ? YOP_Poll_Helper::sanitize_text_or_array_or_object( $_POST['pollsOnPage'] ) : array();
		if (
			( '' !== $username ) &&
			( '' !== $password ) &&
			( '' !== $_token ) &&
			( '' !== $poll_id )
		) {
			if ( check_ajax_referer( 'yop-poll-vote-' . $poll_id, '_token', false ) ) {
				$login = wp_signon(
					array(
						'user_login' => $username,
						'user_password' => $password,
						'remember' => false
					),
					''
				);
				if ( is_wp_error( $login ) ){
					echo wp_send_json_error(
						array(
							'loggedin' => false, 
							'message' => esc_html__( 'Wrong username/email or password.' ),
						)
					);
				} else {
					wp_set_current_user( $login->ID );
					foreach ( $polls_on_page as $poll_on_page ) {
						$new_tokens[] = array(
							'id' => $poll_on_page,
							'token' => wp_create_nonce( 'yop-poll-vote-' . $poll_on_page ),
						);
					}
					wp_send_json_success(
						array(
							'loggedin' => true,
							'_token' => wp_create_nonce( 'yop-poll-vote-' . $poll_id ),
							'message '=> esc_html__( 'Login successful' ),
							'tokens' => $new_tokens,
						)
					);
				}
			} else {
				wp_send_json_error( esc_html__( 'Invalid data', 'yop-poll' ) );
			}
		} else {
			wp_send_json_error( esc_html__( 'Invalid data', 'yop-poll' ) );
		}
	}
}
