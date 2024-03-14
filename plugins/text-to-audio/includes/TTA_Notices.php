<?php

namespace TTA;


/**
 * Class Chalan_Review_Reminder
 */
class TTA_Notices {

	private $active_pluin_name = '';

	public function __construct() {
		$this->notifications_load_hooks();
	}

	/**
	 * Load all Notifications hooks.
	 */
	public function notifications_load_hooks() {
		if (in_array(admin_url(basename($_SERVER['REQUEST_URI'])), [ admin_url('index.php') , admin_url('plugins.php'), admin_url('update-core.php'), \admin_url('plugin-install.php'), \admin_url('admin.php?page=text-to-audio')] ) )  {
			add_action( 'admin_notices', [ $this, 'tta_review_notice' ] );
			add_action( 'admin_notices', [ $this, 'tta_translation_request' ] );
		}

		$plugins = [
                'gtranslate/gtranslate.php' => [
					'callback' => 'plugin_compatible_notice_callback',
					'name' => 'GTranslate',
				],
                'sitepress-multilingual-cms/sitepress.php' => [
					'callback' => 'plugin_compatible_notice_callback',
					'name' => 'WPML Multilingual CMS',
				],
				'tts-multilingual' => [
					'callback' => 'plugin_compatible_notice_callback',
					'name' => 'WPML Multilingual CMS And GTranslate',
				],
        ];

		if(!function_exists('is_plugin_active')) {
            require_once \ABSPATH . 'wp-admin/includes/pluin.php';
        }

        if(!is_pro_active()){
			foreach ( $plugins as $plugin_name =>  $data ){
				if(is_plugin_active($plugin_name )) {
					$this->active_pluin_name    = sprintf( '<b>%s</b>', esc_html__( $data['name'], \TEXT_TO_AUDIO_TEXT_DOMAIN ) );

					add_action( 'admin_notices', [ $this, $data['callback'] ] );
					break;
				}else if( $plugin_name == 'tts-multilingual') {
					$this->active_pluin_name    = sprintf( '<b>%s</b>', esc_html__( $data['name'], \TEXT_TO_AUDIO_TEXT_DOMAIN ) );
					add_action( 'admin_notices', [ $this, $data['callback'] ] );
				}
        	}
		}

		add_action('wp_ajax_tta_save_review_notice', [ $this, 'tta_save_review_notice' ] );
		add_action('wp_ajax_tta_hide_notice', [ $this, 'tta_hide_notice' ] );
	}


	public function plugin_compatible_notice_callback() {
		$wpml_and_gtranslate_notice_displaid = \get_option('wpml_and_gtranslate_notice_displaid', false);
		if('WPML Multilingual CMS And GTranslate' == \strip_tags($this->active_pluin_name) && !$wpml_and_gtranslate_notice_displaid) {
			delete_option('tta_plugin_compatible_notice_next_show_time');
			delete_user_meta(\get_current_user_id(), 'tta_plugin_compatible_notice_dismissed');
			update_option('tta_plugin_compatible_notice_next_show_time', 12);
			\update_option('wpml_and_gtranslate_notice_displaid', true);
		}

		$pluginName    = sprintf( '<b>%s</b>', esc_html__( 'Text To Speech TTS', \TEXT_TO_AUDIO_TEXT_DOMAIN ) );
		$ProPluginName    = sprintf( '<b>%s</b>', esc_html__( 'Text To Speech TTS Pro', \TEXT_TO_AUDIO_TEXT_DOMAIN ) );

		$has_notice    = false;
		$user_id       = get_current_user_id();
		$next_timestamp = get_option( 'tta_plugin_compatible_notice_next_show_time' );
		$review_notice_dismissed = get_user_meta($user_id, 'tta_plugin_compatible_notice_dismissed', true);
		$nonce         = wp_create_nonce( 'tta_notice_nonce' );
		if ( ! empty($next_timestamp) ) {
			if ( ( time() > $next_timestamp ) ) {
				$show_notice = true;
			}else {
				$show_notice = false;
			}
		} else {
			if ( isset($review_notice_dismissed) && ! empty($review_notice_dismissed) ) {
				$show_notice = false;
			}else {
				$show_notice = true;
			}
		}
		// translation Notice.
		if ( $show_notice ) {
			$has_notice = true;
			$learn_more = '<a href="http://atlasaidev.com/text-to-speech-pro/" target="_blank" style="color:blue">Learn more</a>'

			?>
            <div class="tta-notice notice notice-info is-dismissible" dir="<?php echo tta_is_rtl() ? 'ltr' : 'auto'?>" data-which="compitable" data-nonce="<?php echo esc_attr( $nonce ); ?>">
                <p><?php
					printf(
						esc_html__( '%6$s %2$s %3$s %4$s plugin is compitable with  %5$s . %7$s', \TEXT_TO_AUDIO_TEXT_DOMAIN ),
						$pluginName, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<div class="tta-review-notice-logo"></div>',
						'<br/>',
						$this->active_pluin_name, //phpcs:ignore
						$ProPluginName, //phpcs:ignore
                        "<h3>$pluginName</h3>", //phpcs:ignore
						"$learn_more" //phpcs:ignore
					);
					?></p>
                <p>
                    <a class="button button-primary" data-response="compitable" href="https://atlasaidev.com/text-to-speech/" target="_blank"><?php esc_html_e( 'Buy Now', \TEXT_TO_AUDIO_TEXT_DOMAIN ); ?></a>
                </p>
            </div>

			<?php
		}

		if ( true == $has_notice ) {
			add_action( 'admin_print_footer_scripts', function() use ( $nonce ) {
				?>
                <script>
                    (function($){
                        "use strict";
                        $(document)
                            .on('click', '.tta-notice a.button', function (e) {
                                e.preventDefault();
                                // noinspection ES6ConvertVarToLetConst
                                let self = $(this);
                                self.closest(".tta-notice").slideUp( 200, 'linear' );

                                let  tta_notice = self.closest('.tta-notice'), which = tta_notice.attr('data-which');
                                								console.log( which)

								if(wp.ajax) {
									wp.ajax.post( 'tta_hide_notice', { _wpnonce: '<?php echo esc_attr( $nonce ); ?>', which: which } );
								}
                                let notice = self.attr('data-response');

                                if ( 'compitable' === notice ) {
                                    window.open('http://atlasaidev.com/text-to-speech-pro/', '_blank');
                                }
                            })

                            .on('click', '.tta-notice .notice-dismiss', function (e) {
                                e.preventDefault();
								
                                // noinspection ES6ConvertVarToLetConst
                                var self = $(this), tta_notice = self.closest('.tta-notice'), which = tta_notice.attr('data-which');
                                if(wp.ajax) {
									wp.ajax.post( 'tta_hide_notice', { _wpnonce: '<?php echo esc_attr( $nonce ); ?>', which: which } );
								}
                            });

						<?php if ( tta_is_rtl() ) { ?>
                        setTimeout(function () {
                            $('.notice-dismiss').css('left', '97%');
                        },100)
						<?php } ?>
                    })(jQuery)
                </script><?php
			}, 99 );
		}
	}


	/**
	 * Translation notice action.
	 */
	public function tta_translation_request() {

        //     delete_option('tta_translation_notice_next_show_time');
        //     delete_user_meta('1', 'tta_translation_notice_dismissed');
        //  update_option('tta_translation_notice_next_show_time', 12);

		$pluginName    = sprintf( '<b>%s</b>', esc_html__( 'Text To Speech TTS', \TEXT_TO_AUDIO_TEXT_DOMAIN ) );
		$has_notice    = false;
		$user_id       = get_current_user_id();
		$next_timestamp = get_option( 'tta_translation_notice_next_show_time' );
		$review_notice_dismissed = get_user_meta($user_id, 'tta_translation_notice_dismissed', true);
		$nonce         = wp_create_nonce( 'tta_notice_nonce' );
		if ( ! empty($next_timestamp) ) {
			if ( ( time() > $next_timestamp ) ) {
				$show_notice = true;
			}else {
				$show_notice = false;
			}
		} else {
			if ( isset($review_notice_dismissed) && ! empty($review_notice_dismissed) ) {
				$show_notice = false;
			}else {
				$show_notice = true;
			}
		}
		// translation Notice.
		if ( $show_notice ) {
			$has_notice = true;
			$languages = tta_get_default_languages();
			global $locale;
			$language = isset ( $languages[ $locale ] ) ? $languages[ $locale ] : "";
			$language_string = $language ? ' in <b>'. $language .'</b>.' : '.';
			$contact_link = '<a href="http://atlasaidev.com/contact-us/" target="_blank" style="color:blue">here</a>'
			?>
            <div class="tta-notice notice notice-info is-dismissible" dir="<?php echo tta_is_rtl() ? 'ltr' : 'auto'?>" data-which="translate" data-nonce="<?php echo esc_attr( $nonce ); ?>">
                <p><?php
					printf(
						esc_html__( '%6$s %2$s  We are looking for people to translate this plugin%4$s If you can help we would love to heare from you and please contact with us %5$s, we will guide you. %3$s Thanks for using %1$s.', \TEXT_TO_AUDIO_TEXT_DOMAIN ),
						$pluginName, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<div class="tta-review-notice-logo"></div>',
						'<br/>',
						$language_string, //phpcs:ignore
						$contact_link, //phpcs:ignore
                        "<h3>$pluginName</h3>", //phpcs:ignore
					);
					?></p>
                <p>
                    <a class="button button-primary" data-response="translate" href="#" target="_blank"><?php esc_html_e( 'Translate Here', \TEXT_TO_AUDIO_TEXT_DOMAIN ); ?></a>
                </p>
            </div>

			<?php
		}

		if ( true == $has_notice ) {
			add_action( 'admin_print_footer_scripts', function() use ( $nonce ) {
				?>
                <script>
                    (function($){
                        "use strict";
                        $(document)
                            .on('click', '.tta-notice a.button', function (e) {
                                e.preventDefault();
                                // noinspection ES6ConvertVarToLetConst
                                let self = $(this);
                                self.closest(".tta-notice").slideUp( 200, 'linear' );

                                let  tta_notice = self.closest('.tta-notice'), which = tta_notice.attr('data-which');
                                								console.log( which)
								if(wp.ajax) {
									wp.ajax.post( 'tta_hide_notice', { _wpnonce: '<?php echo esc_attr( $nonce ); ?>', which: which } );
								}
                                let notice = self.attr('data-response');

                                if ( 'translate' === notice ) {
                                    window.open('https://translate.wordpress.org/projects/wp-plugins/text-to-audio/', '_blank');
                                }
                            })

                            .on('click', '.tta-notice .notice-dismiss', function (e) {
                                e.preventDefault();
								
                                // noinspection ES6ConvertVarToLetConst
                                var self = $(this), tta_notice = self.closest('.tta-notice'), which = tta_notice.attr('data-which');
								console.log( which)
                                wp.ajax.post( 'tta_hide_notice', { _wpnonce: '<?php echo esc_attr( $nonce ); ?>', which: which } );
                            });

						<?php if ( tta_is_rtl() ) { ?>
                        setTimeout(function () {
                            $('.notice-dismiss').css('left', '97%');
                        },100)
						<?php } ?>
                    })(jQuery)
                </script><?php
			}, 99 );
		}
	}

	/**
	 * Review notice action.
	 */
	public function tta_review_notice() {

        //     delete_option('tta_review_notice_next_show_time');
        //     delete_user_meta('1', 'tta_review_notice_dismissed');
        //  update_option('tta_review_notice_next_show_time', 12);

		$pluginName    = sprintf( '<b>%s</b>', esc_html__( 'Text To Speech TTS', \TEXT_TO_AUDIO_TEXT_DOMAIN ) );
		$has_notice    = false;
		$user_id       = get_current_user_id();
		$next_timestamp = get_option('tta_review_notice_next_show_time');
		$review_notice_dismissed = get_user_meta( $user_id, 'tta_review_notice_dismissed', true );
		$nonce         = wp_create_nonce( 'tta_notice_nonce' );

		if ( ! empty($next_timestamp) ) {
			if ( ( time() > $next_timestamp ) ) {
				$show_notice = true;
			}else {
				$show_notice = false;
			}
		} else {
			if ( isset($review_notice_dismissed) && ! empty($review_notice_dismissed) ) {
				$show_notice = false;
			}else {
				$show_notice = true;
			}
		}
		// Review Notice.
		if ( $show_notice ) {
			$has_notice = true;
			?>
            <div class="tta-notice notice notice-info is-dismissible" style="line-height:1.5;" data-which="rating" data-nonce="<?php echo esc_attr( $nonce ); ?>">
                <p><?php
					printf(
					/* translators: 1: plugin name,2: Slightly Smiling Face (Emoji), 3: line break 'br' tag */
						esc_html__( '%5$s %3$s %2$s We have spent countless hours developing this free plugin for you, and we would really appreciate it if you drop us a quick rating. Your opinion matters a lot to us.%4$s It helps us to get better. Thanks for using %1$s.', \TEXT_TO_AUDIO_TEXT_DOMAIN ),
						$pluginName, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<span style="font-size: 16px;">&#128516</span>',
						'<div class="tta-review-notice-logo"></div>',
						'<br>',
                        "<h3>$pluginName</h3>", //phpcs:ignore
					);
					?></p>
                <p>
                    <a class="button button-primary" data-response="given" href="#" target="_blank"><?php esc_html_e( 'Review Now', \TEXT_TO_AUDIO_TEXT_DOMAIN ); ?></a>
                    <a class="button button-secondary" data-response="later" href="#"><?php esc_html_e( 'Remind Me Later', \TEXT_TO_AUDIO_TEXT_DOMAIN ); ?></a>
                    <a class="button button-secondary" data-response="done" href="#" target="_blank"><?php esc_html_e( 'Already Done!', \TEXT_TO_AUDIO_TEXT_DOMAIN ); ?></a>
                    <a class="button button-secondary" data-response="never" href="#"><?php esc_html_e( 'Never Ask Again', \TEXT_TO_AUDIO_TEXT_DOMAIN ); ?></a>
                </p>
            </div>
			<?php
		}

		if ( true === $has_notice ) {
			add_action( 'admin_print_footer_scripts', function() use ( $nonce ) {
				?>
                <script>
                    (function($){
                        "use strict";
                        $(document)
                            .on('click', '.tta-notice a.button', function (e) {
                                e.preventDefault();
                                // noinspection ES6ConvertVarToLetConst
                                var self = $(this), notice = self.attr('data-response');
                                if ( 'given' === notice ) {
                                    window.open('https://wordpress.org/support/plugin/text-to-audio/reviews/?rate=5#new-post', '_blank');
                                }
                                console.log(self)
                                self.closest(".tta-notice").slideUp( 200, 'linear' );
                                wp.ajax.post( 'tta_save_review_notice', { _ajax_nonce: '<?php echo esc_attr( $nonce ); ?>', notice: notice } );
                            })
                            .on('click', '.tta-notice .notice-dismiss', function (e) {
                                e.preventDefault();
                                // noinspection ES6ConvertVarToLetConst
                                var self = $(this), tta_notice = self.closest('.tta-notice'), which = tta_notice.attr('data-which');
                                wp.ajax.post( 'tta_hide_notice', { _wpnonce: '<?php echo esc_attr( $nonce ); ?>', which: which } );
                            });
                    })(jQuery)
                </script><?php
			}, 99 );
		}

	}


		/**
	 * Black friday implementation.
	 */
	public function tta_free_promotion_notice() {

    //    delete_user_meta( 1, 'tta_promotion_notice_dismissed');

		$image_url = TTA_PLUGIN_URL . 'admin/images/halloween-spacial.jpg';
		// $image_url = TTA_PLUGIN_URL . 'admin/images/halloween-banner-22.png';
		
		$pluginName    = sprintf( '<b>%s</b>', esc_html( 'Challan' ) );
		$user_id       = get_current_user_id();
		$review_notice_dismissed = get_user_meta($user_id, 'tta_promotion_notice_dismissed', true);
		$nonce         = wp_create_nonce( 'tta_notice_nonce' );

        if ( isset($review_notice_dismissed) && ! empty($review_notice_dismissed) ) {
            $show_notice = false;
        }else {
            $show_notice = true;
        }

		if ( $show_notice ) {
			?>
            <div class="tta-notice notice notice-info is-dismissible price_update" style="line-height:1.5;" data-which="promotion_close" data-nonce="<?php echo esc_attr( $nonce ); ?>">
                <p><?php
					printf(
					/* translators: 1: plugin name,2: Slightly Smiling Face (Emoji), 3: line break 'br' tag */
						'<a class="tta_promotion_notice" href="http://atlasaidev.com/text-to-speech-pro/" target="_blank"><img  src="'.$image_url.'" alt="text_to_speech_Free_Price"></a>', //phpcs:ignore
						$pluginName, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<span style="font-size: 16px;">&#128516</span>',
						'<div class="tta-review-notice-logo"></div>',
						'<br>'
					);
					?></p>
            </div>
			<?php

			if ( $show_notice ) {
				add_action( 'admin_print_footer_scripts', function() use ( $nonce ) {
					?>
                    <script>
                        (function($){
                            "use strict";
                            $(document)
                                .on('click', '.tta-notice .notice-dismiss', function (e) {
                                    e.preventDefault();
                                    // noinspection ES6ConvertVarToLetConst
                                    var self = $(this), tta_notice = self.closest('.tta-notice'), which = tta_notice.attr('data-which');
                                    console.log(tta_notice.attr('data-which'))
                                    wp.ajax.post( 'tta_hide_notice', { _wpnonce: '<?php echo esc_attr( $nonce ); ?>', which: which } );
                                });
                        })(jQuery)
                    </script><?php
				}, 99 );
			}
		}

	}

	/**
	 * Show Review request admin notice
	 * @return string
	 */
	public function tta_save_review_notice() {
		check_ajax_referer( 'tta_notice_nonce' );
		$user_id = get_current_user_id();
		update_option('review_test', 'review');
		$review_actions = [ 'later', 'never', 'done', 'given' ];
		if ( isset( $_POST['notice'] ) && ! empty( $_POST['notice'] ) && in_array( $_POST['notice'], $review_actions ) ) {
			$value  = [
				'review_notice' => sanitize_text_field( $_POST['notice'] ), //phpcs:ignore
				'updated_at'    => time(),
			];
			if ( 'never' === $_POST['notice'] || 'done' === $_POST['notice'] || 'given' === $_POST['notice'] ) {

				add_user_meta( $user_id, 'tta_review_notice_dismissed', true, true );

				update_option( 'tta_review_notice_next_show_time', 0 );

			}elseif ( 'later' == $_POST['notice'] ) {

				add_user_meta( $user_id, 'tta_review_notice_dismissed', true, true );

				update_option( 'tta_review_notice_next_show_time', time() + ( DAY_IN_SECONDS * 30 ) );
			}
			update_option( 'tta_review_notice', $value );
			wp_send_json_success( $value );
			wp_die();
		}
		wp_send_json_error( esc_html__( 'Invalid Request.', \TEXT_TO_AUDIO_TEXT_DOMAIN ) );
		wp_die();
	}
	/**
	 * Ajax Action For Hiding Compatibility Notices
	 */
	public function tta_hide_notice() {
		check_ajax_referer( 'tta_notice_nonce' );
		
		$notices = [  'compitable', 'rating',  'translate', 'promotion_close',  ];
		if ( isset( $_REQUEST['which'] ) && ! empty( $_REQUEST['which'] ) && in_array( $_REQUEST['which'], $notices ) ) {
			$user_id = get_current_user_id();

			if ( 'rating' == $_REQUEST['which'] ) {
				$updated_user_meta = update_user_meta( $user_id, 'tta_review_notice_dismissed', true, true );
				update_option( 'tta_review_notice_next_show_time', time() + ( DAY_IN_SECONDS * 30 ) );
			}elseif ( 'translate' == $_REQUEST['which'] ) {
				update_option( 'tta_translation_notice_next_show_time', time() + ( DAY_IN_SECONDS * 30 )  );
				$updated_user_meta = update_user_meta($user_id, 'tta_translation_notice_dismissed', true, true);
			}elseif ( 'writable' == $_REQUEST['which'] ) {
				update_option( 'tta_folder_writable_notice_next_show_time', time() + ( DAY_IN_SECONDS * 30 )  );
				$updated_user_meta = update_user_meta($user_id, 'tta_folder_writable_notice_dismissed', true, true);
			}elseif ( 'promotion_close' == $_REQUEST['which'] ) {
				$updated_user_meta = update_user_meta($user_id, 'tta_promotion_notice_dismissed', true, true);
			}elseif ( 'compitable' == $_REQUEST['which'] ) {
				update_option( 'tta_plugin_compatible_notice_next_show_time', time() + ( DAY_IN_SECONDS * 30 )  );
				$updated_user_meta = update_user_meta($user_id, 'tta_plugin_compatible_notice_dismissed', true, true);
			}

			if ( isset($updated_user_meta ) && $updated_user_meta ) {
				wp_send_json_success( esc_html__( 'Request Successful.', \TEXT_TO_AUDIO_TEXT_DOMAIN ) );
			}else {
				wp_send_json_error( esc_html__( 'Something is wrong.', \TEXT_TO_AUDIO_TEXT_DOMAIN ) );
			}
			wp_die();
		}

		wp_send_json_error( esc_html__( 'Invalid Request.', \TEXT_TO_AUDIO_TEXT_DOMAIN ) );
		wp_die();
	}

}
