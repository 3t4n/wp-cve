<?php

/**
 * Class ScriptlessSocialSharingHelp
 * @package   ScriptlessSocialSharing
 * @copyright 2016-2019 Robin Cornett
 */
class ScriptlessSocialSharingSettingsHelp {

	/**
	 * Help tab for settings screen
	 *
	 * @since 1.0.0
	 */
	public function help() {

		$screen    = get_current_screen();
		$help_tabs = $this->define_tabs();
		if ( ! $help_tabs ) {
			return;
		}
		foreach ( $help_tabs as $tab ) {
			$screen->add_help_tab( $tab );
		}
	}

	/**
	 * Define the help tabs.
	 * @return array
	 */
	protected function define_tabs() {
		return array(
			array(
				'id'      => 'scriptlesssocialsharing_icons-help',
				'title'   => __( 'Icon Settings', 'scriptless-social-sharing' ),
				'content' => $this->icons(),
			),
			array(
				'id'      => 'scriptlesssocialsharing_styles-help',
				'title'   => __( 'Style Settings', 'scriptless-social-sharing' ),
				'content' => $this->styles(),
			),
			array(
				'id'      => 'scriptlesssocialsharing_buttons-help',
				'title'   => __( 'Button Settings', 'scriptless-social-sharing' ),
				'content' => $this->heading() . $this->buttons() . $this->button_order(),
			),
			array(
				'id'      => 'scriptlesssocialsharing_types-help',
				'title'   => __( 'Content Types', 'scriptless-social-sharing' ),
				'content' => $this->content_types(),
			),
			array(
				'id'      => 'scriptlesssocialsharing_networks-help',
				'title'   => __( 'Network Settings', 'scriptless-social-sharing' ),
				'content' => $this->twitter() . $this->email(),
			),
		);
	}

	/**
	 * Description for the Icons tab/settings.
	 * @return string
	 */
	protected function icons() {
		$help  = '<h3>' . __( 'Icons', 'scriptless-social-sharing' ) . '</h3>';
		$help .= '<p>' . __( 'Select how you want to handle social media icons. You can use the plugin SVG icons, the Font Awesome web font, or use your own custom icons. Selecting SVG or webfont will load the appropriate plugin style. To define your own styles completely, select the custom icons.', 'scriptless-social-sharing' ) . '</p>';
		$help .= '<p>' . __( 'Additionally, choose whether you want the buttons to show 1) icons only; 2) icons + text, but hide the text on small screens; 3) icons + text on all screens; or 4) text labels only.', 'scriptless-social-sharing' ) . '</p>';

		return $help;
	}

	/**
	 * Description for the styles tab.
	 * @return string
	 */
	protected function styles() {
		$help  = '<h3>' . __( 'Styles', 'scriptless-social-sharing' ) . '</h3>';
		$help .= '<p>' . __( 'Optionally, have the plugin load the main stylesheet to handle the button layouts and colors and/or Font Awesome (the font itself). The second option is if you want to use the web font of Font Awesome and are not already loading it.', 'scriptless-social-sharing' ) . '</p>';
		$help .= '<p>' . __( 'You can use as much or as little of the plugin styles as you like. For example, if your site already loads Font Awesome, don\'t load it again here.', 'scriptless-social-sharing' ) . '</p>';
		$help .= '<p>' . __( 'Note that the button styles options--container padding, container width, and button padding--will take effect only if the main stylesheet is enabled.', 'scriptless-social-sharing' ) . '</p>';
		$help .= '<p>' . __( 'The buttons are output with CSS for either Flexbox (new default) or as table (old option). The default is for them to span the width of the content space, but you can set it to automatically be just the size of the buttons instead. Note that on sites with many buttons and not much space, the table CSS option may result in buttons that overflow the content area assigned to them. Not sure which option is best? I would recommend flexbox.', 'scriptless-social-sharing' ) . '</p>';

		return $help;
	}

	/**
	 * Description for the heading setting.
	 * @return string
	 */
	protected function heading() {

		$help  = '<h3>' . __( 'Heading', 'scriptless-social-sharing' ) . '</h3>';
		$help .= '<p>' . __( 'This is the heading above the sharing buttons.', 'scriptless-social-sharing' ) . '</p>';
		return $help;
	}

	/**
	 * Description for the social network buttons.
	 * @return string
	 */
	protected function buttons() {
		$help  = '<h3>' . __( 'Sharing Buttons', 'scriptless-social-sharing' ) . '</h3>';
		$help .= '<p>' . __( 'Pick which social network buttons you would like to show. Custom buttons can be added via a filter.', 'scriptless-social-sharing' ) . '</p>';

		return $help;
	}

	/**
	 * Description for the button order.
	 * @return string
	 */
	protected function button_order() {
		$help  = '<h3>' . __( 'Button Order', 'scriptless-social-sharing' ) . '</h3>';
		$help .= '<p>' . __( 'Buttons can be reordered either by changing the number input values, or by dragging and dropping the buttons. If the number input values are changed, drag/drop functionality will be disabled until the settings have been saved.', 'scriptless-social-sharing' ) . '</p>';

		return $help;
	}

	/**
	 * Description for the content types setting.
	 * @return string
	 */
	protected function content_types() {
		$help  = '<p>' . __( 'By default, sharing buttons are added only to posts, but you can add them to any custom content types on your site. For each content type to which you plan to add sharing buttons via code, select manual placement.', 'scriptless-social-sharing' ) . '</p>';
		$help .= '<p>' . __( 'If you want to place sharing buttons manually via the shortcode, you can do that regardless of what location settings are checked or not.', 'scriptless-social-sharing' ) . '</p>';
		if ( 'genesis' === get_template() ) {
			$help .= '<p>' . __( 'Since you are using a Genesis theme, you can have the plugin use Genesis hooks instead of the default filters for placing the buttons.', 'scriptless-social-sharing' ) . '</p>';
		}

		return $help;
	}

	/**
	 * Description for the twitter handle setting.
	 * @return string
	 */
	protected function twitter() {

		$help  = '<h3>' . __( 'Twitter Handle', 'scriptless-social-sharing' ) . '</h3>';
		$help .= '<p>' . __( 'The Twitter username you want to be credited for each tweet/post.', 'scriptless-social-sharing' ) . '</p>';
		$help .= '<p>' . __( 'Do not include the @ -- just the user name.', 'scriptless-social-sharing' ) . '</p>';

		return $help;
	}

	/**
	 * Description for the email subject setting.
	 * @return string
	 */
	protected function email() {

		$help  = '<h3>' . __( 'Email Subject', 'scriptless-social-sharing' ) . '</h3>';
		$help .= '<p>' . __( 'The post/page title will be added to the subject.', 'scriptless-social-sharing' ) . '</p>';
		$help .= '<h3>' . __( 'Email Content', 'scriptless-social-sharing' ) . '</h3>';
		$help .= '<p>' . __( 'Keep this simple--whatever you put here is added to your email button markup. The link to the post will be added at the end of the email content.', 'scriptless-social-sharing' ) . '</p>';

		return $help;
	}
}
