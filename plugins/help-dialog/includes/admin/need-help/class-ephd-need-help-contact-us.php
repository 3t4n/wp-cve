<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display Contact Us tab on the Need Help? screen
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Need_Help_Contact_Us {

	/**
	 * Get configuration array for Contact Us page view
	 *
	 * @return array
	 */
	public static function get_page_view_config() {

		return array(

			// Shared
			'active' => true,
			'list_key' => 'contact-us',

			// Top Panel Item
			'label_text' => __( 'Contact Us', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-envelope-o',

			// Boxes List
			'boxes_list' => array(

				// Box: Contact Us
				array(
					'html' => self::contact_us_tab(),
				),
			),
		);
	}

	/**
	 * Get content for Contact Us tab
	 *
	 * @return false|string
	 */
	private static function contact_us_tab() {

		ob_start();     ?>

		<div class="ephd-nh__contact-us-container">

			<div class="ephd-nh__section-container ephd-nh__section--three-col-cta">

				<div class="ephd-nh__section__header-container">
					<h3 class="ephd-nh__header__title"><?php esc_html_e( 'We Are Here to Help', 'help-dialog' ); ?></h3>
				</div>

				<div class="ephd-nh__section__body-container"> <?php

					EPHD_HTML_Forms::call_to_action_box( array(
						'container_class'   => '',
						'style' => 'style-2',
						'icon_class'    => 'ephdfa-rocket',
						'title'         => __( 'Cannot Find a Feature?', 'help-dialog' ),
						'content'       => '<p>' . __( 'We can help you find it or add it to our road map if it is missing.', 'help-dialog' ) . '</p>',
						'btn_text'      => __( 'Ask About a Feature', 'help-dialog' ),
						'btn_url'       => 'https://www.helpdialog.com/contact-us/feature-request/',
						'btn_target'    => '__blank',
					) );

					EPHD_HTML_Forms::call_to_action_box( array(
						'container_class'   => '',
						'style' => 'style-2',
						'icon_class'    => 'ephdfa-life-ring',
						'title'         => __( 'Submit an Issue', 'help-dialog' ),
						'content'       => '<p>' . __( 'Submit a technical support question for something that is not working correctly.', 'help-dialog' ) . '</p>
										<p>' . __( 'We usually reply within an hour.', 'help-dialog' ) . '</p>',
						'btn_text'      => __( 'Contact Our Support', 'help-dialog' ),
						'btn_url'       => 'https://www.helpdialog.com/technical-support/',
						'btn_target'    => '__blank',
					) );

					EPHD_HTML_Forms::call_to_action_box( array(
						'container_class'   => '',
						'style' => 'style-2',
						'icon_class'    => 'ephdfa-comments-o',
						'title'         => __( 'General and Pre-Sale Questions', 'help-dialog' ),
						'content'       => '<p>' . __( 'Do you have a pre-sale question, and do you need some clarification?', 'help-dialog' ) . '</p>',
						'btn_text'      => __( 'Ask a Question', 'help-dialog' ),
						'btn_url'       => 'https://www.helpdialog.com/contact-us/pre-sale-and-general-questions/',
						'btn_target'    => '__blank',
					) );		?>
				</div>
			</div>
		</div>		<?php

		return ob_get_clean();
	}
}
