<?php

/**
 * HTML Elements for admin pages excluding boxes
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_HTML_Admin {

	/**
	 * Show Admin Header
	 *
	 * @param $args
	 * @param $content_type
	 */
	public static function admin_header( $content_type='header', $args=[] ) {  ?>

		<!-- Admin Header -->
		<div class="ephd-admin__header">
			<div class="ephd-admin__section-wrap ephd-admin__section-wrap__header">   <?php

				switch ( $content_type ) {
					case 'header':
					default:
						echo self::admin_header_content( $args );
						break;
					case 'logo':
						echo self::get_admin_header_logo();
						break;
				}  ?>

			</div>
		</div>  <?php
	}

	/**
	 * Show content of Admin Header for Help Dialog
	 *
	 * @param $args
	 * @return string
	 */
	private static function admin_header_content( $args=[] ) {

		ob_start();

		echo self::get_admin_header_logo(); ?>

        <div class="ephd-admin__header__controls-wrap">
	        <?php echo empty( $args['controls_html'] ) ? '' : wp_kses( $args['controls_html'], EPHD_Utilities::get_admin_ui_extended_html_tags() ); ?>
            <a class="ephd-suggest_feature_link" href="https://www.helpdialog.com/contact-us/feature-request/" target="_blank">
				<?php esc_html_e( 'Suggest a Feature', 'help-dialog' ); ?>
                <span class="ephdfa ephdfa-external-link"></span>
            </a>
        </div>  <?php

		$result = ob_get_clean();

		return empty( $result ) ? '' : $result;
	}

	/**
	 * Fill missing fields in single admin page view configuration array with default values
	 *
	 * @param $page_view
	 * @return array
	 */
	private static function admin_page_view_fill_missing_with_default( $page_view ){

		// Do not fill empty or not valid array
		if ( empty( $page_view ) || ! is_array( $page_view ) ) {
			return $page_view;
		}

		// Default page view
		$default_page_view = array(

			// Shared
			'active'                    => false,
			'minimum_required_capability' => EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ),
			'list_id'                   => '',
			'list_key'                  => '',

			// Top Panel Item
			'label_text'                => '',
			'main_class'                => '',
			'label_class'               => '',
			'icon_class'                => '',

			// Secondary Panel Items
			'secondary'                 => array(),

			// Boxes List
			'list_top_actions_html'     => '',
			'top_actions_minimum_required_capability' => EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ),
			'list_bottom_actions_html'  => '',
			'bottom_actions_minimum_required_capability' => EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ),
			'boxes_list'                => array(),

			// List footer HTML
			'list_footer_html'          => '',
		);

		// Default secondary view
		$default_secondary = array(

			// Shared
			'list_key'                  => '',
			'active'                    => false,
			'minimum_required_capability' => EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ),

			// Secondary Panel Item
			'label_text'                => '',
			'main_class'                => '',
			'label_class'               => '',
			'icon_class'                => '',

			// Secondary Boxes List
			'list_top_actions_html'     => '',
			'top_actions_minimum_required_capability' => EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ),
			'list_bottom_actions_html'  => '',
			'bottom_actions_minimum_required_capability' => EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ),
			'boxes_list'                => array(),
		);

		// Default box
		$default_box = array(
			'minimum_required_capability' => EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ),
			'icon_class'    => '',
			'class'         => '',
			'title'         => '',
			'description'   => '',
			'html'          => '',
			'return_html'   => false,
			'extra_tags'    => [],
		);

		// Set default view
		$page_view = array_merge( $default_page_view, $page_view );

		// Set default boxes
		foreach ( $page_view['boxes_list'] as $box_index => $box_content ) {

			// Do not fill empty or not valid array
			if ( empty( $page_view['boxes_list'][$box_index] ) || ! is_array( $page_view['boxes_list'][$box_index] ) ) {
				continue;
			}

			$page_view['boxes_list'][$box_index] = array_merge( $default_box, $box_content );
		}

		// Set default secondary views
		foreach ( $page_view['secondary'] as $secondary_index => $secondary_content ) {

			// Do not fill empty or not valid array
			if ( empty( $page_view['secondary'][$secondary_index] ) || ! is_array( $page_view['secondary'][$secondary_index] ) ) {
				continue;
			}

			$page_view['secondary'][$secondary_index] = array_merge( $default_secondary, $secondary_content );

			// Set default boxes
			foreach ( $page_view['secondary'][$secondary_index]['boxes_list'] as $box_index => $box_content ) {

				// Do not fill empty or not valid array
				if ( empty(  $page_view['secondary'][$secondary_index]['boxes_list'][$box_index] ) || ! is_array(  $page_view['secondary'][$secondary_index]['boxes_list'][$box_index] ) ) {
					continue;
				}

				$page_view['secondary'][$secondary_index]['boxes_list'][$box_index] = array_merge( $default_box, $box_content );
			}
		}

		return $page_view;
	}

	/**
	 * Show Admin Toolbar
	 *
	 * @param $admin_page_views
	 */
	public static function admin_toolbar( $admin_page_views ) {     ?>

		<!-- Admin Top Panel -->
		<div class="ephd-admin__top-panel">
			<div class="ephd-admin__section-wrap ephd-admin__section-wrap__top-panel">      <?php

				foreach( $admin_page_views as $page_view ) {

					// Optionally we can have null in $page_view, make sure we handle it correctly
					if ( empty( $page_view ) || ! is_array( $page_view ) ) {
						continue;
					}

					// Fill missing fields in admin page view configuration array with default values
					$page_view = self::admin_page_view_fill_missing_with_default( $page_view );

					// Do not render toolbar tab if the user does not have permission
					if ( ! current_user_can( $page_view['minimum_required_capability'] ) ) {
						continue;
					}   ?>

					<div class="ephd-admin__top-panel__item ephd-admin__top-panel__item--<?php echo esc_attr( $page_view['list_key'] );
						echo empty( $page_view['secondary'] ) ? '' : ' ephd-admin__top-panel__item--parent ';
						echo esc_attr( $page_view['main_class'] ); ?>"
					    <?php echo empty( $page_view['list_id'] ) ? '' : ' id="' . esc_attr( $page_view['list_id'] ) . '"'; ?> data-target="<?php echo esc_attr( $page_view['list_key'] ); ?>">
						<div class="ephd-admin__top-panel__icon ephd-admin__top-panel__icon--<?php echo esc_attr( $page_view['list_key'] ); ?> <?php echo esc_attr( $page_view['icon_class'] ); ?>"></div>
						<p class="ephd-admin__top-panel__label ephd-admin__boxes-list__label--<?php echo esc_attr( $page_view['list_key'] ); ?>"><?php echo wp_kses_post( $page_view['label_text'] ); ?></p>
					</div> <?php
				}       ?>

			</div>
		</div>  <?php
	}

	/**
	 * Display admin second-level tabs below toolbar
	 *
	 * @param $admin_page_views
	 */
	public static function admin_secondary_tabs( $admin_page_views ) {  ?>

		<!-- Admin Secondary Panels List -->
		<div class="ephd-admin__secondary-panels-list">
			<div class="ephd-admin__section-wrap ephd-admin__section-wrap__secondary-panel">  <?php

				foreach ( $admin_page_views as $page_view ) {

					// Optionally we can have null in $page_view, make sure we handle it correctly
					if ( empty( $page_view ) || ! is_array( $page_view ) ) {
						continue;
					}

					// Optionally we can have empty in $page_view['secondary'], make sure we handle it correctly
					if ( empty( $page_view['secondary'] ) || ! is_array( $page_view['secondary'] ) ) {
						continue;
					}

					// Fill missing fields in admin page view configuration array with default values
					$page_view = self::admin_page_view_fill_missing_with_default( $page_view );

					// Do not render toolbar tab if the user does not have permission
					if ( ! current_user_can( $page_view['minimum_required_capability'] ) ) {
						continue;
					}   ?>

					<!-- Admin Secondary Panel -->
					<div id="ephd-admin__secondary-panel__<?php echo esc_attr( $page_view['list_key'] ); ?>" class="ephd-admin__secondary-panel">  <?php

						foreach ( $page_view['secondary'] as $secondary ) {

							// Optionally we can have empty in $secondary, make sure we handle it correctly
							if ( empty( $secondary ) || ! is_array( $secondary ) ) {
								continue;
							}

							// Do not render secondary toolbar tab if the user does not have permission
							if ( ! current_user_can( $secondary['minimum_required_capability'] ) ) {
								continue;
							}       ?>

							<div class="ephd-admin__secondary-panel__item ephd-admin__secondary-panel__<?php echo esc_attr( $secondary['list_key'] ); ?> <?php
								echo ( $secondary['active'] ? 'ephd-admin__secondary-panel__item--active' : '' );
								echo esc_attr( $secondary['main_class'] ); ?>" data-target="<?php echo esc_attr( $page_view['list_key'] ) . '__' .esc_attr( $secondary['list_key'] ); ?>">     <?php

								// Optional icon for secondary panel item
								if ( ! empty( $secondary['icon_class'] ) ) {        ?>
									<span class="ephd-admin__secondary-panel__icon <?php echo esc_attr( $secondary['icon_class'] ); ?>"></span>     <?php
								}       ?>

								<p class="ephd-admin__secondary-panel__label ephd-admin__secondary-panel__<?php echo esc_attr( $secondary['list_key'] ); ?>__label"><?php echo wp_kses_post( $secondary['label_text'] ); ?></p>
							</div>  <?php

						}   ?>
					</div>  <?php

				}   ?>

			</div>
		</div>  <?php
	}

	/**
	 * Show list of settings for each setting in a tab
	 *
	 * @param $admin_page_views
	 * @param string $content_class
	 */
	public static function admin_settings_tab_content( $admin_page_views, $content_class='' ) {    ?>

		<!-- Admin Content -->
		<div class="ephd-admin__content <?php echo esc_attr( $content_class ); ?>"> <?php

			echo '<div class="ephd-admin__boxes-list-container">';
			foreach ( $admin_page_views as $page_view ) {

				// Optionally we can have null in $page_view, make sure we handle it correctly
				if ( empty( $page_view ) || ! is_array( $page_view ) ) {
					continue;
				}

				// Fill missing fields in admin page view configuration array with default values
				$page_view = self::admin_page_view_fill_missing_with_default( $page_view );

				// Do not render view if the user does not have permission
				if ( ! current_user_can( $page_view['minimum_required_capability'] ) ) {
					continue;
				}   ?>

				<!-- Admin Boxes List -->
				<div id="ephd-admin__boxes-list__<?php echo esc_attr( $page_view['list_key'] ); ?>" class="ephd-admin__boxes-list">     <?php

					// List body
					self::admin_setting_boxes_for_tab( $page_view );

					// Optional list footer
					if ( ! empty( $page_view['list_footer_html'] ) ) {   ?>
							<div class="ephd-admin__section-wrap ephd-admin__section-wrap__<?php echo esc_attr( $page_view['list_key'] ); ?>">
								<div class="ephd-admin__boxes-list__footer"><?php echo wp_kses_post( $page_view['list_footer_html'] ); ?></div>
						</div>      <?php
					}   ?>

				</div><?php
			}
			echo '</div>'; ?>
		</div><?php
	}

	/**
	 * Show single List of Settings Boxes for Admin Page
	 *
	 * @param $page_view
	 */
	private static function admin_setting_boxes_for_tab( $page_view ) {

		// Boxes List for view without secondary panel
		if ( empty( $page_view['secondary'] ) ) {

			// Make sure we can handle empty boxes list correctly
			if ( empty( $page_view['boxes_list'] ) || ! is_array( $page_view['boxes_list'] ) ) {
				return;
			}   ?>

			<!-- Admin Section Wrap -->
			<div class="ephd-admin__section-wrap ephd-admin__section-wrap__<?php echo esc_attr( $page_view['list_key'] ); ?>">  <?php

				self::admin_settings_display_boxes_list( $page_view );   ?>

			</div>      <?php

		// Boxes List for view with secondary tabs
		} else {

			// Secondary Lists of Boxes
			foreach ( $page_view['secondary'] as $secondary ) {

				// Make sure we can handle empty boxes list correctly
				if ( empty( $secondary['boxes_list'] ) || ! is_array( $secondary['boxes_list'] ) ) {
					continue;
				}   ?>

				<!-- Admin Section Wrap -->
				<div class="ephd-setting-box-container ephd-setting-box-container-type-<?php echo esc_attr( $page_view['list_key'] ); ?>">

					<!-- Secondary Boxes List -->
					<div id="ephd-setting-box__list-<?php echo esc_attr( $page_view['list_key'] ) . '__' . esc_attr( $secondary['list_key'] ); ?>"
					     class="ephd-setting-box__list <?php echo ( $secondary['active'] ? 'ephd-setting-box__list--active' : '' ); ?>">   <?php

						self::admin_settings_display_boxes_list( $secondary );   ?>

					</div>

				</div>  <?php
			}
		}
	}

	/**
	 * Display boxes list for admin settings
	 *
	 * @param $page_view
	 */
	private static function admin_settings_display_boxes_list( $page_view ) {

		// Optional buttons row displayed at the top of the boxes list
		if ( ! empty( $page_view['list_top_actions_html'] ) && current_user_can( $page_view['top_actions_minimum_required_capability'] ) ) {
			echo $page_view['list_top_actions_html'];
		}

		// Admin Boxes with configuration
		foreach ( $page_view['boxes_list'] as $box_options ) {

			// Do not render empty or not valid array
			if ( empty( $box_options ) || ! is_array( $box_options ) ) {
				continue;
			}

			// Do not render box if the user does not have permission
			if ( ! current_user_can( $box_options['minimum_required_capability'] ) ) {
				continue;
			}

			EPHD_HTML_Forms::admin_settings_box( $box_options );
		}

		// Optional buttons row displayed at the bottom of the boxes list
		if ( ! empty( $page_view['list_bottom_actions_html'] ) && current_user_can( $page_view['top_actions_minimum_required_capability'] )) {
			echo $page_view['list_bottom_actions_html'];
		}
	}

	/**
	 * Get logo container for the admin header
	 *
	 * @return string
	 */
	public static function get_admin_header_logo() {

		ob_start();     ?>

		<!-- Help Dialog Logo -->
		<div class="ephd-admin__header__logo-wrap">
			<div class="ephd__desc__logo">
				<span class="ephd__desc__logo__icon">   <?php
					echo self::get_hd_icon_html();   ?>
				</span>
			</div>
			<div class="ephd__desc__name"><?php esc_html_e( 'Help Dialog', 'help-dialog' ); ?></div>
		</div>  <?php

		$result = ob_get_clean();

		return empty( $result ) ? '' : $result;
	}


	/********************************************************************************
	 *
	 *                                   VARIOUS
	 *
	 ********************************************************************************/

	/**
	 * We need to add this HTML to admin page to catch WP admin JS functionality
	 *
	 * @param false $include_no_css_message
	 */
	public static function admin_page_css_missing_message( $include_no_css_message=false ) {  ?>

		<!-- This is to catch WP JS garbage -->
		<div class="wrap ephd-wp-admin">
			<h1></h1>
		</div>
		<div class=""></div>  <?php

		if ( $include_no_css_message ) {    ?>
			<!-- This is for cases of CSS incorrect loading -->
			<h1 style="color: red; line-height: 1.2em; background-color: #eaeaea; border: solid 1px #ddd; padding: 20px;" class="ephd-css-working-hide-message">
				<?php _e( 'Please reload the page to refresh CSS styles. That should correctly render the page. This issue is typically caused by timeout or other plugins blocking CSS.' .
				          'If that does not help, contact us for help.', 'help-dialog' ); ?></h1>   <?php
		}
	}

	/**
	 * Display modal form in admin area for user to submit an error to support. For example Setup Wizard/Editor encounters error.
	 */
	public static function display_report_admin_error_form() {

		$current_user = wp_get_current_user();      ?>

		<!-- Submit Error Form -->
		<div class="ephd-admin__error-form__container" style="display:none!important;">
			<div class="ephd-admin__error-form__wrap">
				<div class="ephd-admin__scroll-container">
					<div class="ephd-admin__white-box">

						<h4 class="ephd-admin__error-form__title"></h4>
						<div class="ephd-admin__error-form__desc"></div>

						<form id="ephd-admin__error-form" method="post">				<?php

							EPHD_HTML_Admin::nonce();				?>

							<input type="hidden" name="action" value="ephd_report_admin_error" />
							<div class="ephd-admin__error-form__body">

								<label for="ephd-admin__error-form__first-name"><?php esc_html_e( 'Name', 'help-dialog' ); ?>*</label>
								<input name="first_name" type="text" value="<?php echo esc_attr( $current_user->display_name ); ?>" required  id="ephd-admin__error-form__first-name">

								<label for="ephd-admin__error-form__email"><?php esc_html_e( 'Email', 'help-dialog' ); ?>*</label>
								<input name="email" type="email" value="<?php echo esc_attr( $current_user->user_email ); ?>" required id="ephd-admin__error-form__email">

								<label for="ephd-admin__error-form__message"><?php esc_html_e( 'Error Details', 'help-dialog' ); ?>*</label>
								<textarea name="admin_error" class="admin_error" required id="ephd-admin__error-form__message"></textarea>

								<div class="ephd-admin__error-form__btn-wrap">
									<input type="submit" name="submit_error" value="<?php esc_attr_e( 'Submit', 'help-dialog' ); ?>" class="ephd-admin__error-form__btn ephd-admin__error-form__btn-submit">
									<span class="ephd-admin__error-form__btn ephd-admin__error-form__btn-cancel"><?php esc_html_e( 'Cancel', 'help-dialog' ); ?></span>
								</div>

								<div class="ephd-admin__error-form__response"></div>
							</div>
						</form>

						<div class="ephd-close-notice ephdfa ephdfa-window-close"></div>

					</div>
				</div>
			</div>
		</div>      <?php
	}

	/**
	 * Display or return HTML input for wpnonce
	 *
	 * @param false $return_html
	 *
	 * @return false|string|void
	 */
	public static function nonce( $return_html=false ) {

		if ( $return_html ) {
			ob_start();
		}   ?>

		<input type="hidden" name="_wpnonce_ephd_ajax_action" value="<?php echo wp_create_nonce( '_wpnonce_ephd_ajax_action' ); ?>">	<?php

		if ( $return_html ) {
			return ob_get_clean();
		}
	}

	/**
	 * Return HTML for preview box (used on Widgets, FAQs)
	 *
	 * @param $item
	 * @param $args
	 *
	 * @return false|string
	 */
	public static function get_item_preview_box( $item, $args ) {

		$defaults = array(
			'key'                   => '',
			'sub_items_list'        => [],
			'sub_items_title'       => '',
			'sub_item_icon'         => '',
			'sub_item_icon_html'    => '',
			'use_sub_item_link'     => false,
			'icon_html'             => '',
			'bottom_items_list'     => [],
			'bottom_items_title'    => '',
			'status'                => '',
			'no_sub_items_text'     => '',
			'limit_sub_items'       => true,
		);

		$args = array_merge( $defaults, $args );

		$max_show_sub_items = 5;

		ob_start(); ?>

		<!-- Header -->
		<div class="ephd-admin__item-preview__header">
			<h4 class="ephd-admin__item-preview__title">    <?php
				echo wp_kses_post( $args['icon_html'] );    ?>
				<span class="ephd-admin__item-preview__title-text"><?php echo esc_html( $item[$args['key'] . '_name'] ); ?></span>  <?php
				if ( ! empty( $args['status'] ) ) {    ?>
					<span class="ephd-admin__item-preview__status"><?php echo esc_html( $args['status'] ); ?></span>  <?php
				}   ?>
			</h4>
		</div>

		<!-- Content -->
		<div class="ephd-admin__item-preview__content">     <?php

			if ( ! empty( $args['sub_items_title'] ) ) {    ?>
				<!-- Sub Items Title -->
				<p class="ephd-admin__item-preview__sub-items-title"><?php echo esc_html( $args['sub_items_title'] ); ?></p>   <?php
			}   ?>

			<!-- Sub Items List -->
			<ul class="ephd-admin__item-preview__sub-items-list<?php echo empty( $args['sub_items_list'] ) ? ' ephd-admin__item-preview__sub-items-list--empty' : '';
					echo empty( $args['sub_item_icon'] ) ? '' : ' ephd-admin__item-preview__sub-items-list--icons'; ?>">  <?php

				if ( empty( $args['sub_items_list'] ) ) {   ?>
					<li class="ephd-admin__item-preview__no-sub-item"><?php echo esc_html( $args['no_sub_items_text'] ); ?></li>    <?php
				}

				$sub_items_shown = 0;
				foreach ( $args['sub_items_list'] as $sub_item ) {
					$sub_items_shown++;
					if ( $args['limit_sub_items'] && $sub_items_shown > $max_show_sub_items ) {
						break;
					}  ?>
					<li class="ephd-admin__item-preview__sub-item">     <?php
						if ( ! empty( $args['sub_item_icon'] ) ) {  ?>
							<i class="<?php echo esc_attr( $args['sub_item_icon'] ); ?> ephd-admin__item-preview__sub-item-icon"></i>   <?php
						}
						if ( ! empty( $args['sub_item_icon_html'] ) ) {
							echo wp_kses_post( $args['sub_item_icon_html'] );
						}
						if ( $args['use_sub_item_link'] ) {
							$sub_item_url = empty( $sub_item->ID ) ? home_url() : get_permalink( $sub_item->ID );   ?>
							<a href="<?php echo esc_url( $sub_item_url ); ?>" class="ephd-admin__item-preview__sub-item-link" target="_blank"><?php echo esc_html( $sub_item->post_title ); ?></a>   <?php
						} else {    ?>
							<span class="ephd-admin__item-preview__sub-item-text"><?php echo esc_html( $sub_item->question ); ?></span>   <?php
						}   ?>
					</li>    <?php
				}   ?>

			</ul>  <?php

			if ( $args['limit_sub_items'] && $sub_items_shown > $max_show_sub_items ) {    ?>
				<div class="ephd-admin__item-preview__sub-items-actions">
					<a class="ephd-admin__item-preview__sub-items-btn ephd-admin__item-preview__sub-items-btn--more" data-id="<?php echo esc_attr( $item['widget_id'] ); ?>"><?php esc_html_e( 'View more', 'help-dialog' ); ?></a>
				</div>      <?php
			}       ?>

		</div>

		<div class="ephd-admin__item-preview__footer">

			<!-- Bottom Items -->
			<div class="ephd-admin__item-preview__bottom-list">     <?php

				if ( ! empty( $args['bottom_items_title'] ) ) {  ?>
					<!-- Bottom Items Title -->
					<p class="ephd-admin__item-preview__bottom-items-title"><?php echo esc_html( $args['bottom_items_title'] ); ?>:</p>     <?php
				}

				if ( ! empty( $args['bottom_items_list'] ) ) {  ?>
					<!-- Bottom Items List -->
					<ul class="ephd-admin__item-preview__bottom-items-list">    <?php
						for ( $i = 0; $i < count( $args['bottom_items_list'] ); $i++ ) {  ?>
							<li class="ephd-admin__item-preview__bottom-item"><?php echo esc_html( $args['bottom_items_list'][$i] ) . ( $i < count( $args['bottom_items_list'] ) - 1 ? ', ' : '' ); ?></li>    <?php
						}   ?>
					</ul>   <?php
				}   ?>

			</div>

			<!-- Actions -->
			<div class="ephd-admin__item-preview__actions">
				<input type="button" value="<?php esc_attr_e( 'Edit', 'help-dialog' ); ?>" class="ephd_edit_item ephd-primary-btn" data-id="<?php echo esc_attr( $item['widget_id'] ); ?>">
			</div>

		</div>      <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Widget preview box
	 *
	 * @param $widget
	 * @param $args
	 *
	 * @return false|string
	 */
	public static function get_widget_preview_box( $widget, $args ) {

		$defaults = array(
			'key'                   => '',
			'sub_items_list'        => [],
			'sub_items_title'       => [],
			'status'                => '',
			'no_sub_items_text'     => '',
			'limit_sub_items'       => true,
		);

		$args = array_merge( $defaults, $args );

		$items_limit = 3;   // 11;

		ob_start(); ?>

        <!-- Header -->
        <div class="ephd-admin__widget-preview__header">

	        <!-- Title -->
            <h4 class="ephd-admin__widget-preview__title">
	            <span class="ephd-admin__widget-preview__title-label"><?php echo esc_html__( 'Widget Name', 'help-dialog' ) . ':'; ?></span>
                <span class="ephd-admin__widget-preview__title-text"><?php echo esc_html( $widget['widget_name'] ); ?></span>
            </h4>

	        <div class="ephd-admin__widget-preview__view-details">
		        <span class="ephdfa ephdfa-info-circle ephd-admin__widget-preview__view-details-icon"></span>
		        <span class="ephd-admin__widget-preview__view-details-text"><?php esc_html_e( 'View Details', 'help-dialog' ); ?></span>
	        </div><?php
			if ( !empty( $args['preview_url'] ) ) { ?>
				<div class="ephd-admin__widget-preview__view-frontend">
					<a href="<?php echo esc_url( $args['preview_url'] ); ?>" target="_blank">
						<?php esc_html_e( 'View on frontend', 'help-dialog' ); ?>
						<span class="ephdfa ephdfa-external-link"></span>
					</a>
				</div><?php
			} ?>

	        <!-- Actions -->
	        <div class="ephd-admin__widget-preview__actions">
		        <input type="button" value="<?php esc_attr_e( 'Edit', 'help-dialog' ); ?>" class="ephd_edit_widget ephd-primary-btn" data-id="<?php echo esc_attr( $widget['widget_id'] ); ?>">
		        <input type="button" value="<?php esc_attr_e( 'Copy Widget', 'help-dialog' ); ?>" class="ephd_copy_widget ephd-success-btn" data-id="<?php echo esc_attr( $widget['widget_id'] ); ?>">
	        </div>
        </div>

        <!-- Content -->
        <div class="ephd-admin__widget-preview__content">
	        <div class="ephd-admin__widget-preview__content-wrap">

		        <!-- Status -->
		        <div class="ephd-admin__widget-preview__content-status">
			        <span class="ephd-admin__widget-preview__status ephd--status-<?php echo esc_html( strtolower( $args['status'] ) ); ?>"><?php echo esc_html( $args['status'] ); ?></span>
		        </div>

		        <!-- Locations -->
		        <div class="ephd-admin__widget-preview__content-list ephd-admin__widget-preview__content-list--locations"> <?php

					$count = 0;
					foreach ( $args['locations_list'] as $group ) {
						$count += count( $group );
					}

			        if ( $count > 0 ) {
				        echo self::get_widget_preview_locations_list( $args, $items_limit );
			        }

			        if ( empty( $count ) ) {
				        EPHD_HTML_Forms::notification_box_middle( array(
					        'type' => 'error-no-icon',
					        'desc' => $args['no_locations_text'],
				        ));
			        }

			        if ( $count > $items_limit ) {   ?>
				        <div class="ephd-admin__widget-preview__sub-items-actions">
					        <a class="ephd-admin__widget-preview__sub-items-btn ephd-admin__widget-preview__sub-items-btn--more"><?php esc_html_e( 'View all', 'help-dialog' ); ?></a>
				        </div>  <?php
				        self::widget_details_popup( esc_html( $widget['widget_name'] ), self::get_widget_preview_locations_list( $args ) );
			        }   ?>
		        </div>

		        <!-- Questions -->
		        <div class="ephd-admin__widget-preview__content-list ephd-admin__widget-preview__content-list--faqs"> <?php

			        if ( count( $args['faqs_list'] ) > 0 ) {
				        echo self::get_widget_preview_faqs_list( $args, $items_limit );
			        }

			        if ( empty( $args['faqs_list'] ) ) {
				        EPHD_HTML_Forms::notification_box_middle( array(
					        'type' => 'error-no-icon',
					        'desc' => $args['no_faqs_text'],
				        ));
			        }

			        if ( count( $args['faqs_list'] ) > $items_limit ) {   ?>
				        <div class="ephd-admin__widget-preview__sub-items-actions">
					        <a class="ephd-admin__widget-preview__sub-items-btn ephd-admin__widget-preview__sub-items-btn--more"><?php esc_html_e( 'View all', 'help-dialog' ); ?></a>
				        </div>  <?php
				        self::widget_details_popup( esc_html( $widget['widget_name'] ), self::get_widget_preview_faqs_list( $args ) );
			        }   ?>
		        </div>

	        </div>
        </div>  <?php

		return ob_get_clean();
	}

	/**
	 * Return HTML for Widget preview Locations list
	 *
	 * @param $args
	 * @param $items_limit
	 *
	 * @return false|string
	 */
	private static function get_widget_preview_locations_list( $args, $items_limit=false ) {
		ob_start();

		foreach ( $args['locations_list'] as $sub_items_key => $sub_items ) {
			// Limit display items
			if ( false !== $items_limit && empty( $items_limit ) ) {
				break;
			}
			if ( ! empty( $args['locations_title'][$sub_items_key] ) && ! empty( $sub_items ) ) {    ?>
                <!-- Sub Items Title -->
                <p class="ephd-admin__widget-preview__sub-items-title"><?php echo esc_html( $args['locations_title'][$sub_items_key] ); ?></p>   <?php
			}   ?>

            <!-- Sub Items List -->
            <ul class="ephd-admin__widget-preview__sub-items-list<?php echo empty( $sub_items ) ? ' ephd-admin__item-preview__sub-items-list--empty' : ''; ?>">  <?php

				foreach ( $sub_items as $sub_item ) {
					// Limit display items
					if ( false !== $items_limit && empty( $items_limit-- ) ) {
						break 2;
					}   ?>
                    <li class="ephd-admin__widget-preview__sub-item">     <?php
						$location_title = strlen( $sub_item->post_title ) > 40 ? substr( $sub_item->post_title, 0, 40 ) . '...' : $sub_item->post_title;
						if ( empty( $sub_item->url ) ) {    ?>
                            <span class="ephd-admin__widget-preview__sub-item-text"><?php echo esc_html( $location_title ); ?></span>   <?php
						} else {    ?>
                            <a href="<?php echo esc_url( $sub_item->url ); ?>" class="ephd-admin__widget-preview__sub-item-link" target="_blank"><?php echo esc_html( $location_title ); ?></a>   <?php
						}   ?>
                    </li>    <?php
				}   ?>
            </ul>   <?php
		}
		return ob_get_clean();
	}

	/**
	 * Return HTML for Widget preview FAQs list
	 *
	 * @param $args
	 * @param $items_limit
	 *
	 * @return false|string
	 */
	private static function get_widget_preview_faqs_list( $args, $items_limit=false ) {
		ob_start();

		// Limit display items
		if ( ! empty( $args['faqs_title'] ) && ! empty( $args['faqs_list'] ) ) {    ?>
			<!-- Sub Items Title -->
			<p class="ephd-admin__widget-preview__sub-items-title"><?php echo esc_html( $args['faqs_title'] ); ?></p>   <?php
		}   ?>

		<!-- Sub Items List -->
		<ul class="ephd-admin__widget-preview__sub-items-list<?php echo empty( $args['faqs_list'] ) ? ' ephd-admin__item-preview__sub-items-list--empty' : ''; ?>">  <?php

			foreach ( $args['faqs_list'] as $sub_item ) {
				// Limit display items
				if ( false !== $items_limit && empty( $items_limit-- ) ) {
					break;
				}
				$location_title = strlen( $sub_item->question ) > 40 ? substr( $sub_item->question, 0, 40 ) . '...' : $sub_item->question;  ?>
				<li class="ephd-admin__widget-preview__sub-item">
					<span class="ephd-admin__widget-preview__sub-item-text"><?php echo esc_html( $location_title ); ?></span>
				</li>    <?php
			}   ?>
		</ul>   <?php

		return ob_get_clean();
	}

	/**
	 * Widget Details Popup - user can only click 'OK' button
	 *
	 * @param string $title
	 * @param string $body
	 * @param string $accept_label
	 */
	public static function widget_details_popup( $title='', $body='', $accept_label='' ) { ?>

        <div class="ephd-admin__widget-details-popup">

            <!---- Header ---->
            <div class="ephd-admin__widget-details-popup__header">
                <h4><?php echo esc_html( $title ); ?></h4>
            </div>

            <!---- Body ---->
            <div class="ephd-admin__widget-details-popup__body">
				<?php echo EPHD_Utilities::admin_ui_wp_kses( $body ); ?>
            </div>

            <!---- Footer ---->
            <div class="ephd-admin__widget-details-popup__footer">
                <div class="ephd-admin__widget-details-popup__footer__accept">
					<span class="ephd-admin__widget-details-popup__accept-btn">
						<?php echo empty( $accept_label ) ? esc_html__( 'OK', 'help-dialog' ) : esc_html( $accept_label ); ?>
					</span>
                </div>
            </div>

        </div>

        <div class="ephd-admin__widget-details-popup__overlay"></div>      <?php
	}

	/**
	 * Display tabs for admin form
	 *
	 * @param $tabs_config
	 */
	public static function display_admin_form_tabs( $tabs_config ) {    ?>

		<!-- TABS WRAP-->
		<div class="ephd-admin__form-tabs-wrap">    <?php
			foreach ( $tabs_config as $group_key => $group_config ) {  ?>

				<!-- TABS GROUP --> <?php
				if ( ! empty( $group_config['label'] ) ) {   ?>
					<h4 class="ephd-admin__form-tabs-title"><?php echo esc_html( $group_config['label'] ); ?></h4>  <?php
				}   ?>
				<div class="ephd-admin__form-tabs ephd-admin__form-tabs--<?php echo esc_attr( $group_key ); ?>">    <?php
					foreach ( $group_config['tabs'] as $tab ) {

						$data_escaped = '';
						if ( ! empty( $tab['data'] ) ) {
							foreach ( $tab['data'] as $key => $value ) {
								$data_escaped .= 'data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
							}
						}   ?>

						<div class="ephd-admin__form-tab<?php echo $tab['active'] ? ' ephd-admin__form-tab--active' : ''; ?>" data-target="<?php echo esc_attr( $tab['key'] ); ?>" <?php echo $data_escaped; ?>>
							<i class="<?php echo esc_attr( $tab['icon'] ); ?> ephd-admin__form-tab-icon"></i>
							<span class="ephd-admin__form-tab-title"><?php echo esc_html( $tab['title'] ); ?></span>
						</div>  <?php
					}   ?>
				</div>  <?php
			}   ?>
		</div>

		<!-- TAB CONTENTS -->
		<div class="ephd-admin__form-tab-contents">     <?php
			foreach ( $tabs_config as $group_config ) {
				foreach ( $group_config['tabs'] as $tab ) {  ?>
					<div class="ephd-admin__form-tab-wrap ephd-admin__form-tab-wrap--<?php echo esc_attr( $tab['key'] ); echo $tab['active'] ? ' ephd-admin__form-tab-wrap--active' : ''; ?>">  <?php

						foreach ( $tab['contents'] as $content ) {  ?>

							<div class="ephd-admin__form-tab-content">

								<div class="ephd-admin__form-tab-content-title">    <?php
									echo esc_html( $content['title'] );     ?>
								</div>  <?php

								if ( ! empty( $content['desc'] ) ) {   ?>
									<div class="ephd-admin__form-tab-content-desc">
										<span class="ephd-admin__form-tab-content-desc__text"><?php echo esc_html( $content['desc'] ); ?></span>    <?php
										if ( ! empty( $content['read_more_url'] ) ) {   ?>
											<a class="ephd-admin__form-tab-content-desc__link" href="<?php echo esc_url( $content['read_more_url'] ); ?>" target="_blank"><?php echo esc_html( $content['read_more_text'] ); ?></a> <?php
										}   ?>
									</div>   <?php
								}   ?>

								<div class="ephd-admin__form-tab-content-body">     <?php
									echo wp_kses( $content['body_html'], EPHD_Utilities::get_admin_ui_extended_html_tags() );   ?>
								</div>
							</div>  <?php
						}   ?>

					</div>  <?php
				}
			}   ?>
		</div>  <?php
	}

	/**
	 * Return HTML of welcome message for admin pages
	 *
	 * @param $icon_img_url
	 * @param $title
	 * @param $message
	 *
	 * @return false|string
	 */
	public static function get_welcome_message( $icon_img_url, $title, $message ) {
		ob_start();     ?>
		<div class="ephd__welcome-message">
			<div class="ephd__welcome-message__header">
				<img class="ephd__welcome-message__icon" src="<?php echo esc_url( Echo_Help_Dialog::$plugin_url . $icon_img_url ); ?>" alt="">
			</div>
			<div class="ephd__welcome-message__body">
				<h4 class="ephd__welcome-message__title"><?php echo esc_html( $title ); ?></h4>
				<p class="ephd__welcome-message__text"><?php echo esc_html( $message ); ?></p>
			</div>
		</div>  <?php
		return ob_get_clean();
	}

	/**
	 * Generic admin page to display message on configuration error
	 *
	 * @param WP_Error $wp_error
	 */
	public static function display_config_error_page( $wp_error ) {

		$wp_error = $wp_error->get_error_message(); ?>

		<div id="ephd-admin-page-wrap" class="ephd-admin-page-wrap--config-error">  <?php

			EPHD_HTML_Forms::notification_box_middle( [
				'id'    => 'ephd-load-config-error-notification',
				'type'  => 'error',
				'title' => __( 'Cannot load configuration.', 'help-dialog' ) . ( empty( $wp_error ) ? '' : ' (' . $wp_error . ')' ),
				'html'  => current_user_can( 'manage_options' )
					? esc_html__( 'Do you want to reset the Help Dialog settings? All settings will revert to default.', 'help-dialog' ) . '<br /><br />' . EPHD_Delete_HD::get_reset_config_button() . '<br /></br />' . EPHD_Utilities::contact_us_for_support()
					: EPHD_Utilities::contact_us_for_support()
			] );    ?>

		</div>  <?php
	}

	/**
	 * Return HTML for Help Dialog icon
	 *
	 * @param $css_class
	 *
	 * @return false|string
	 */
	public static function get_hd_icon_html( $css_class='' ) {
		ob_start(); ?>
		<span class="ep-help-dialog-icon <?php echo esc_attr( $css_class ); ?>">
			<span class="ep_font_icon_help_dialog"></span>
			<span class="ep_font_icon_help_dialog-background"></span>
		</span> <?php
		return ob_get_clean();
	}

	/**
	 * Display header for admin form
	 *
	 * @param $header_config
	 */
	public static function display_admin_form_header( $header_config ) {
		$title_desc =  isset( $header_config['title_desc'] ) ? $header_config['title_desc'] : 'Widget Settings';
		$desc =  isset( $header_config['desc'] ) ? $header_config['desc'] : 'Widget Settings';    ?>

		<!-- Admin Form Header -->
		<div class="ephd-admin__form__header">
			<div class="ephd-admin__form-title">
				<span class="ephd-admin__form-title-icon">
					<img src="<?php echo esc_url( Echo_Help_Dialog::$plugin_url . 'img/edit-hd-icon.png' ); ?>" alt="">
				</span>
				<div class="ephd-admin__form-text-container">
					<span class="ephd-admin__form-title-text"><?php echo esc_html( $title_desc ); ?><?php echo esc_html( $header_config['title'] ); ?></span>
					<span class="ephd-admin__form-desc"><?php echo esc_html( $desc ); ?></span>
				</div>
			</div><?php
			if ( !empty( $header_config['preview_url'] ) ) { ?>
				<div class="ephd-admin__frontend-link">
					<a href="<?php echo esc_url( $header_config['preview_url'] ); ?>" target="_blank">
						<?php esc_html_e( 'View on frontend', 'help-dialog' ); ?>
						<span class="ephdfa ephdfa-external-link"></span>
					</a>
				</div><?php
			} ?>
			<div class="ephd-admin__form-actions"><?php echo EPHD_Utilities::admin_ui_wp_kses( $header_config['actions_html'] ); ?></div>
		</div>  <?php
	}
}
