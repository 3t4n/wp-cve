<?php

/**
 * Various utility functions
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Core_Utilities {

	/**
	 * Retrieve user IP address if possible.
	 *
	 * @return string
	 */
	public static function get_ip_address() {

		$ip_params = array( 'HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' );
		foreach ( $ip_params as $ip_param ) {
			if ( ! empty($_SERVER[$ip_param]) ) {
				foreach ( explode( ',', $_SERVER[$ip_param] ) as $ip ) {
					$ip = trim( $ip );

					// validate IP address
					if ( filter_var( $ip, FILTER_VALIDATE_IP ) !== false ) {
						return esc_attr( $ip );
					}
				}
			}
		}

		return '';
	}

	/**
	 * Return url of a random page where the widget will be shown.
	 * $widget = object from config array ephd_get_instance()->widgets_config_obj->get_config()
	 *
	 * @param $widget
	 * @return false|string
	 */
	public static function get_first_widget_page_url( $widget ) {

		if ( empty( $widget ) ) {
			return false;
		}

		$is_include = $widget['location_page_filtering'] == 'include';

		// check include pages
		if ( $is_include ) {

			if ( in_array( EPHD_Config_Specs::HOME_PAGE, $widget['location_pages_list'] ) ) {
				return home_url();
			}

			$types = ['page', 'post', 'cpt'];

			// check if there is some included page/post/cpt
			foreach ( $types as $type ) {

				if ( empty( $widget['location_' . $type . 's_list'] ) ) {
					continue;
				}

				foreach ( $widget['location_' . $type . 's_list'] as $page_id ) {

					$post = get_post( $page_id );

					if ( ! self::is_language_first_page( $post, $widget ) ) {
						continue;
					}

					if ( in_array( $post->post_status, [ 'private', 'publish', 'draft' ] ) && empty( $post->post_mime_type ) ) {
						return get_the_permalink( $post );
					}
				}
			}

			return false;
		}

		// check excluded pages
		$post_types = EPHD_Utilities::get_post_type_labels( $widget['location_cpts_list'], EPHD_Utilities::get_cpts_whitelist() );
		if ( ! empty( $post_types ) ) {
			$post_types = array_keys( $post_types );
		}

		$post_types[] = 'page';
		$post_types[] = 'post';

		$exclude = array_merge( $widget['location_pages_list'], $widget['location_posts_list'] );

		// check home page
		if ( in_array( EPHD_Config_Specs::HOME_PAGE, $widget['location_pages_list'] ) ) {
			$exclude[] = get_option('page_on_front');
		}

		$pages = get_posts([
				'post_type' => $post_types,
				//'numberposts' => 1,
				'exclude' => implode( ',', $exclude )
		]);

		$first_language_page = false;
		foreach( $pages as $page ) {
			if ( self::is_language_first_page( $page, $widget ) ) {
				$first_language_page = $page;
				break;
			}
		}

		return empty( $first_language_page ) ? false : get_the_permalink( $first_language_page );
	}

	private static function is_language_first_page( $post, $widget ) {

		if ( empty( $post ) ) {
			return false;
		}

		$multilang_plugin = EPHD_Multilang_Utilities::get_multilang_plugin_name();
		if ( empty( $multilang_plugin ) || $widget['location_language_filtering'] == 'all' ) {
			return true;
		}

		$post_lang = EPHD_Multilang_Utilities::get_post_language( $post->ID );

		if ( $widget['location_language_filtering'] == $post_lang ) {
			return true;
		}

		return false;
	}

	public static function is_help_dialog_admin_page( $request_page ) {
		return in_array( $request_page, ['ephd-help-dialog', 'ephd-help-dialog-advanced-config', 'ephd-help-dialog-widgets', 'ephd-help-dialog-faqs', 'ephd-help-dialog-contact-form', 'ephd-plugin-analytics'] );
	}

	/**
	 * Get link to an admin page
	 *
	 * @param $url_param
	 * @param $label_text
	 * @param bool $target_blank
	 * @param string $css_class
	 * @return string
	 */
	public static function get_admin_page_link( $url_param, $label_text, $target_blank=true, $css_class='' ) {
		return '<a class="ephd-hd__wizard-link ' .$css_class. '" href="' . esc_url( admin_url( '/admin.php' . ( empty($url_param) ? '' : '?' ) . $url_param ) ) . '"' . ( empty( $target_blank ) ? '' : ' target="_blank"' ) . '>' . wp_kses_post( $label_text ) . '</a>';
	}

	/**
	 * Show WordPress Editor for user to edit Question and Answer
	 *
	 * @param $widget_id
	 */
	public static function display_wp_editor( $widget_id ) {

		$languages = EPHD_Multilang_Utilities::get_languages_data();    ?>

		<div class="ephd-fp__wp-editor">

		<div class="ephd-fp__wp-editor__overlay"></div>

		<!-- WP Editor Form -->
		<form id="ephd-fp__article-form" class="<?php echo $languages ? 'ephd-fp__article-form--multilang' : ''; ?>"><?php

			if ( $languages && count( $languages ) > 1 ) { ?>
				<div class="ephd-fp__wp-editor__languages"><?php
					foreach ( $languages as $language ) { ?>
						<div class="ephd-fp__wp-editor__language ephd-fp__wp-editor__language-<?php echo esc_attr( $language['slug'] ); ?>" data-slug="<?php echo esc_attr( $language['slug'] ); ?>">

							<div class="ephd-fp__wp-editor__language__flag"> <img src="<?php echo esc_url( $language['flag_url'] ); ?>"></div>
							<div class="ephd-fp__wp-editor__language__text"><?php echo esc_attr( $language['name'] ); ?></div>

						</div>  <?php
					}       ?>
				</div>  <?php
			}   ?>

			<input type="hidden" id="widget_id" name="widget_id" value="<?php echo esc_attr( $widget_id ); ?>">
			<input type="hidden" id="question_id" name="question_id" placeholder="<?php esc_attr_e( 'Question', 'help-dialog' ); ?>">   <?php
			EPHD_HTML_Elements::submit_button_v2( __( 'AI Help', 'help-dialog' ), '', 'ephd__wp_editor__open-ai-help-sidebar', '', '', '', 'ephd__wp_editor__ai-help-sidebar-btn-open' );   ?>
			<div class="ephd-fp__wp-editor__question">
				<h4><?php esc_html_e( 'Question', 'help-dialog' ); ?></h4>
				<div class="ephd-fp__wp-editor__question__input-container">
                    <input type="text" id="ephd-fp__wp-editor__question-title" name="ephd-fp__wp-editor__question-title" required maxlength="200">
					<div class="ephd-characters_left"><span class="ephd-characters_left-title"><?php esc_html_e( 'Character Limit', 'help-dialog' ); ?></span><span class="ephd-characters_left-counter">200</span>/<span>200</span></div>
                </div>
            </div>
			<div class="ephd-fp__wp-editor__answer">
				<h4><?php esc_html_e( 'Answer', 'help-dialog' ); ?></h4><?php
				wp_editor( '', 'ephd-fp__wp-editor', array( 'media_buttons' => false ) ); ?>
				<div class="ephd-characters_left"><span class="ephd-characters_left-title"><?php esc_html_e( 'Character Limit', 'help-dialog' ); ?></span><span class="ephd-characters_left-counter">1500</span>/<span>1500</span></div>
			</div>
			<div class="ephd-fp__wp-editor__buttons">				<?php
				EPHD_HTML_Elements::submit_button_v2( __( 'Save', 'help-dialog' ), 'ephd_save_question_data', 'ephd__help_editor__action__save', '', true, '', 'ephd-success-btn' );
				EPHD_HTML_Elements::submit_button_v2( __( 'Cancel', 'help-dialog' ), '', 'ephd__help_editor__action__cancel', '', '', '', 'ephd-error-btn' );				?>
			</div>
		</form> <?php

		// AI Help Sidebar
		EPHD_AI_Help_Sidebar::display_ai_help_sidebar();    ?>

		</div><?php
	}

	/**
	 * Return sales page for given plugin
	 *
	 * @param $plugin_name
	 * @return String
	 */
	public static function get_plugin_sales_page( $plugin_name ) {
		switch( $plugin_name ) {
			case 'pro':
				return 'https://www.helpdialog.com/help-dialog-pro/';
		}

		return '';
	}

	/**
	 * Search if the page have widget
	 *
	 * @param $key
	 * @param $post_type
	 * @param $only_include
	 * @return array|null
	 */
	public static function get_widget_by_page( $key, $post_type, $only_include = false ) {

		// get all defined Widgets - default Widget returned if there are no defined Widgets yet or error occurred
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config();

		// sort widgets by include/exclude option: include is more important
		uasort( $widgets_config, function( $a, $b ){
			if ( empty( $a['location_page_filtering'] ) || empty( $b['location_page_filtering'] ) ) {
				return 0;
			}

			if ( $a['location_page_filtering'] == $b['location_page_filtering'] ) {
				return 0;
			}

			return $a['location_page_filtering'] == 'include' ? -1 : 1;
		});

		$matching_widget = null;

		$multilang_plugin = EPHD_Multilang_Utilities::get_multilang_plugin_name();
		$current_language = empty( $multilang_plugin ) ? null : EPHD_Multilang_Utilities::get_current_language();

		foreach ( $widgets_config as $widget ) {

			// check Widget language only if multilanguage plugin is available and Widget is set to certain language
			if ( ! empty( $current_language ) && $widget['location_language_filtering'] != 'all' && $current_language != $widget['location_language_filtering'] ) {
				continue;
			}

			if ( $widget['location_page_filtering'] == 'include' && in_array( $key, $widget['location_' . $post_type . 's_list'] ) ) {
				$matching_widget = $widget;
				break;
			}

			if ( $only_include ) {
				continue;
			}

			if ( $widget['location_page_filtering'] == 'exclude' && ! in_array( $key, $widget['location_' . $post_type . 's_list'] ) ) {
				$matching_widget = $widget;
				break;
			}
		}

		// did we find matching post or page
		if ( empty( $matching_widget ) || ! is_array( $matching_widget ) ) {
			return null;
		}

		return $matching_widget;
	}
} 