<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Features tab on the Need Help? screen
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Need_Help_Features {

	/**
	 * Get configuration array for Features page view
	 * @return array
	 */
	public static function get_page_view_config() {

		return array(

			// Shared
			'active' => true,
			'list_key' => 'features',

			// Top Panel Item
			'label_text' => __( 'Features', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-puzzle-piece',

			// Secondary Views
			'secondary' => self::features_tab(),

			// List footer HTML
			'list_footer_html' => self::features_tab_footer(),
		);
	}

	/**
	 * Get HTML for single feature box
	 *
	 * @param $feature
	 * @return false|string
	 */
	private static function get_feature_box( $feature ) {

		// apply defaults
		$feature = array_merge( [
			'plugin'    => 'core',
			'category'  => '',
			'icon'      => '',
			'name'      => '',
			'desc'      => '',
			'config'    => '',
			'view'      => '',
			'docs'      => '',
			'video'     => '',
		], $feature );

		switch( $feature['category'] )  {
			case 'design':
				$icon = 'ephdfa ephdfa-paint-brush';
				break;
			case 'article-features':
				$icon = 'ephdfa ephdfa-newspaper-o';
				break;
			case 'search':
				$icon = 'ephdfa ephdfa-search';
				break;
			case 'widgets':
				$icon = 'ephdfa ephdfa-list-alt';
				break;
			case 'compatibility':
				$icon = 'ephdfa ephdfa-handshake-o';
				break;
			case 'help-dialog':
				$icon = 'ephdfa ephdfa-comments-o';
				break;
			case 'advanced':
				$icon = 'ephdfa ephdfa-rocket';
				break;
			default:
				$icon = 'ephdfa ephdfa-clock-o';
		}

		ob_start();     ?>

		<div class="ephd-nh__feature-container__col ephd-nh__feature__icon-col"><span class="<?php echo empty( $feature['icon'] ) ? esc_attr( $icon ) : esc_attr( $feature['icon'] ); ?>"></span></div>

		<div class="ephd-nh__feature-container__col ephd-nh__feature__content-col">
			<h3 class="ephd-nh__feature-name<?php echo $feature['plugin'] != 'core' && $feature['plugin'] != 'crel' && empty( $feature['active_status'] ) ? ' ephd-nh__feature-name--pro' : ''; ?>"><?php echo esc_html( $feature['name'] ); ?></h3>   <?php

			// Optional description
			if ( ! empty( $feature['desc'] ) ) {   ?>
				<div class="ephd-nh__feature-desc"><?php echo esc_html( $feature['desc'] ); ?></div><?php
			}

			// Links    ?>
			<div class="ephd-nh__feature-links">  <?php

				if ( ! empty( $feature['custom'] ) ) {
					echo wp_kses_post( $feature['custom'] );
				}

				// Link to Configure - show only if feature is enabled
				if ( ! empty( $feature['config'] ) && self::is_feature_enabled( $feature ) ) {  ?>
					<a class="ephd-nh__feature-link" href="<?php echo esc_url( $feature['config'] ); ?>" target="_blank"><span><?php esc_html_e( 'Configure', 'help-dialog' ); ?></span></a>    <?php
				}

				// Link to View - show only if feature is enabled
				if ( ! empty( $feature['view'] ) && self::is_feature_enabled( $feature ) ) {  ?>
					<a class="ephd-nh__feature-link" href="<?php echo esc_url( $feature['view'] ); ?>" target="_blank"><span><?php esc_html_e( 'View', 'help-dialog' ); ?></span></a>    <?php
				}

				// Link to Documentation
				if ( ! empty( $feature['docs'] ) ) {    ?>
					<a class="ephd-nh__feature-link" href="<?php echo esc_url( $feature['docs'] ); ?>" target="_blank"><span><?php esc_html_e( 'Docs', 'help-dialog' ); ?></span></a>    <?php
				}

				// Link to Video Tutorial
				if ( ! empty( $feature['video'] ) ) {  ?>
					<a class="ephd-nh__feature-link" href="<?php echo esc_url( $feature['video'] ); ?>" target="_blank"><span><?php esc_html_e( 'Video Tutorial', 'help-dialog' ); ?></span></a>    <?php
				}

				// if plugin is not enabled, then show Learn More
				if ( ! EPHD_Utilities::is_plugin_enabled( $feature['plugin'] ) ) {
					$plugin_url = EPHD_Core_Utilities::get_plugin_sales_page( $feature['plugin'] ); ?>
					<a class="ephd-nh__feature-link" href="<?php echo esc_url( $plugin_url ); ?>" target="_blank"><span><?php _e( 'Learn More', 'help-dialog' ); ?></span></a>    <?php
				}				?>

			</div>

		</div>

        <div class="ephd-nh__feature-container__col ephd-nh__feature__status-col">    <?php

			// Plugin is enabled
			if ( $feature['active_status'] ) {    ?>
				<span class="ephd-nh__feature-status ephd-nh__feature--installed">
                    <span class="ephdfa ephdfa-check"></span>
                </span> <?php
			// Plugin is not enabled
			} else {
                echo '<a class="ephd-nh__feature-status ephd-nh__feature--disabled ephd-success-btn" href="' . EPHD_Core_Utilities::get_plugin_sales_page( $feature['plugin'] ) . '" target="_blank"><span>' . __( 'Upgrade', 'help-dialog' ) . '</span></a>';
			}   ?>

		</div>  <?php

		return ob_get_clean();
	}

	/**
	 * Get configuration array for all features
	 *
	 * Installed - if core OR ( if 'PRO' + add-on active )
	 * Upgrade - if 'PRO' + add-on inactive
	 * On/Off - if 'switch' available AND ( if core OR if 'PRO' + add-on active )
	 * 'PRO' if not core
	 *
	 * @return array[]
	 */
	private static function get_features_config() {

		// is the current user has the admin capability
		$is_admin = current_user_can( EPHD_Admin_UI_Access::EPHD_ADMIN_CAPABILITY );

		return [
			[
				'category'  => 'help-dialog',
				'name'      => __( 'Search FAQs and Knowledge Base Articles', 'help-dialog' ),
				'desc'      => __( 'Users can search FAQs and a specific knowledge base for articles from the Help Dialog widget.', 'help-dialog' ),
				'config'    => admin_url( 'admin.php?page=ephd-help-dialog-widgets' ),
				'docs'      => 'https://www.helpdialog.com/documentation/searching-content/',
			],
			[
				'category'  => 'help-dialog',
				'name'      => __( 'Contact Form', 'help-dialog' ),
				'desc'      => __( 'Users can submit a contact form to ask questions or get more help from the Help Dialog window.', 'help-dialog' ),
				'config'    => $is_admin ? admin_url( 'admin.php?page=ephd-help-dialog-contact-form' ) : '',
				'docs'      => $is_admin ? 'https://www.helpdialog.com/documentation/contact-form-overview/' : '',
			],
			[
				'category'  => 'help-dialog',
				'name'      => __( 'Analytics', 'help-dialog' ),
				'desc'      => __( 'Basic analytics about the number of times Help Dialog was invoked and the number of user searches and submitted requests.', 'help-dialog' ),
				'view'      => $is_admin ? admin_url( 'admin.php?page=ephd-plugin-analytics' ) : '',
				'docs'      => 'https://www.helpdialog.com/documentation/engagement-analytics/',
			],
			[
				'category'  => 'help-dialog',
				'name'      => __( 'Bots Protection', 'help-dialog' ),
				'desc'      => __( 'The contact form is protected from submissions by bots.', 'help-dialog' ),
			],
			[
				'plugin'    => 'pro',
				'category'  => 'help-dialog',
				'name'      => __( 'Full Analytics', 'help-dialog' ),
				'desc'      => __( 'Extensive analytics about user interaction with the Help Dialog including FAQs, search and contact form.', 'help-dialog' ),
				'view'      => admin_url( 'admin.php?page=ephd-plugin-analytics' ),
				'docs'      => 'https://www.helpdialog.com/documentation/analytics-overview/',
			],
			[
				'category'  => 'help-dialog',
				'name'      => __( 'AI Content Writing', 'help-dialog' ),
				'desc'      => __( 'Get help with writing your FAQs using ChatGPT like AI. Check your spelling and grammar, generate variations on questions and choose the best one and generate answers to questions.', 'help-dialog' ),
			],
			[
				'category'  => 'design',
				'name'      => __( 'Pre-made Color Designs', 'help-dialog' ),
				'desc'      => __( 'Choose from pre-made designs with a variety of colors. You can customize the colors set you choose.', 'help-dialog' ),
				'config'    => admin_url( 'admin.php?page=ephd-help-dialog-widgets' ),
			],
			[
				'category'  => 'compatibility',
				'name'      => __( 'RTL (Right-To-Left) Styling', 'help-dialog' ),
				'desc'      => __( 'This Help Dialog fully supports RTL CSS files for both admin screens and frontend pages.', 'help-dialog' ),
			],
			[
				'category'  => 'compatibility',
				'name'      => __( 'Multisite Compatible', 'help-dialog' ),
				'desc'      => __( 'Help Dialog works with the WordPress multi-site feature.', 'help-dialog' ),
			],
			[
				'category'  => 'compatibility',
				'name'      => __( 'Multi-language Support', 'help-dialog' ),
				'desc'      => __( 'Change or translate any text label on the front-end using any of 12 translated languages.', 'help-dialog' ),
			],
			[
				'category'  => 'compatibility',
				'name'      => __( 'WCAG accessibility', 'help-dialog' ),
				'desc'      => __( 'Complies with basic WCAG accessibility for people with disabilities, including blindness.', 'help-dialog' ),
			],
		];
	}

	/**
	 * Get configuration for feature categories
	 *
	 * @return array[]
	 */
	private static function get_categories_config() {
		return [
			[
				'name'  => 'help-dialog',
				'title' => __( 'Overview', 'help-dialog' ),
				'icon'  => 'ephdfa ephdfa-comments-o',
			],
			[
				'name'  => 'design',
				'title' => __( 'HD Design', 'help-dialog' ),
				'icon'  => 'ephdfa ephdfa-paint-brush',
			],
			[
				'name'  => 'compatibility',
				'title' => __( 'Compatibility', 'help-dialog' ),
				'icon'  => 'ephdfa ephdfa-handshake-o',
			],
			/* [
				'name'  => 'advanced',
				'title' => __( 'Advanced', 'help-dialog' ),
				'icon'  => 'ephdfa ephdfa-rocket',
			], */
		];
	}

	/**
	 * Get configuration array for Features tab
	 *
	 * @return array
	 */
	private static function features_tab() {

		$features_tab = array();

		$features_list = self::get_features_config();

		// List categories - secondary tabs
		$first_tab = true;
		$categories_list = self::get_categories_config();
		foreach ( $categories_list as $category ) {

			$features_tab[] = array(

				'active' => $first_tab,

				// Shared
				'list_key' => strtolower( $category['name'] ),

				// Secondary Panel Item
				'label_text' => $category['title'],
				'icon_class' => $category['icon'],

				// Secondary Boxes List
				'boxes_list' => self::features_category_boxes_list( $features_list, $category['name'] ),
			);

			$first_tab = false;
		}

		return $features_tab;
	}

	/**
	 * Get configuration for boxes list in Features category tab
	 *
	 * @param $features_list
	 * @param $category_name
	 * @return array
	 */
	private static function features_category_boxes_list( $features_list, $category_name ) {

		$features = array();

		foreach ( $features_list as $feature ) {

			$feature = array_merge( [
				'plugin'    => 'core',
				'category'  => '',
				'icon'      => '',
				'name'      => '',
				'desc'      => '',
				'config'    => '',
				'view'      => '',
				'docs'      => '',
				'video'     => '',
			], $feature );
			
			$feature['active_status'] = EPHD_Utilities::is_plugin_enabled( $feature['plugin'] );

			// Filter features by category
			if ( $feature['category'] != $category_name ) {
				continue;
			}

			$features[] = array(
				'class' => 'ephd-nh__feature-container',
				'html'  => self::get_feature_box( $feature ),
			);
		}

		return $features;
	}

	/**
	 * Get footer HTML for Features tab
	 *
	 * @return false|string
	 */
	private static function features_tab_footer() {
		ob_start();     ?>
		<span><?php esc_html_e( 'Cannot find a feature?', 'help-dialog' ); ?></span>
		<a href="https://www.helpdialog.com/contact-us/feature-request/" class="ephd-hd__wizard-link" target="_blank"><?php esc_html_e( 'Contact us', 'help-dialog' ); ?></a>   <?php
		return ob_get_clean();
	}

	/**
	 * Check whether the current feature is enabled
	 *
	 * @param $feature
	 * @return bool
	 */
	private static function is_feature_enabled( $feature ) {
		return $feature['plugin'] != 'pro' || ( $feature['plugin'] == 'pro' && EPHD_Utilities::is_help_dialog_pro_enabled() );
	}
}
