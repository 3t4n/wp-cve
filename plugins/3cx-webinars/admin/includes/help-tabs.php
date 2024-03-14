<?php

class WP3CXW_Help_Tabs {

	private $screen;

	public function __construct( WP_Screen $screen ) {
		$this->screen = $screen;
	}

	public function set_help_tabs( $type ) {
		switch ( $type ) {
			case 'list':
				$this->screen->add_help_tab( array(
					'id' => 'list_overview',
					'title' => __( 'Overview', '3cx-webinar' ),
					'content' => $this->content( 'list_overview' ) ) );

				$this->screen->add_help_tab( array(
					'id' => 'list_available_actions',
					'title' => __( 'Available Actions', '3cx-webinar' ),
					'content' => $this->content( 'list_available_actions' ) ) );

				$this->sidebar();

				return;
			case 'edit':
				$this->screen->add_help_tab( array(
					'id' => 'edit_overview',
					'title' => __( 'Overview', '3cx-webinar' ),
					'content' => $this->content( 'edit_overview' ) ) );

				$this->sidebar();

				return;
		}
	}

	private function content( $name ) {
		$content = array();

		$content['list_overview'] = '<p>' . __( "On this screen, you can manage Webinar forms provided by 3CX Webinars. You can manage an unlimited number of Webinar forms. Each Webinar form has a unique ID and 3CX Webinar shortcode ([3cx-webinar ...]). To insert a Webinar form into a post or a text widget, insert the shortcode into the target.", '3cx-webinar' ) . '</p>';

		$content['list_available_actions'] = '<p>' . __( "Hovering over a row in the Webinar forms list will display action links that allow you to manage your Webinar form. You can perform the following actions:", '3cx-webinar' ) . '</p>';
		$content['list_available_actions'] .= '<p>' . __( "<strong>Edit</strong> - Navigates to the editing screen for that Webinar form. You can also reach that screen by clicking on the Webinar form title.", '3cx-webinar' ) . '</p>';
		$content['list_available_actions'] .= '<p>' . __( "<strong>Duplicate</strong> - Clones that Webinar form. A cloned Webinar form inherits all content from the original, but has a different ID.", '3cx-webinar' ) . '</p>';

		$content['edit_overview'] = '<p>' . __( "On this screen, you can edit a Webinar form. A Webinar form includes following components:", '3cx-webinar' ) . '</p>';
		$content['edit_overview'] .= '<p>' . __( "<strong>Title</strong> is the title of a Webinar form. This title is only used for labeling a Webinar form, and can be edited.", '3cx-webinar' ) . '</p>';
		$content['edit_overview'] .= '<p>' . __( "<strong>Configuration</strong> is the section where you enter your 3CX PBX configuration. Look at PBX Management Console into WebMeeting section to find your parameters.", '3cx-webinar' ) . '</p>';

		if ( ! empty( $content[$name] ) ) {
			return $content[$name];
		}
	}

	public function sidebar() {
		$content = '<p><strong>' . __( 'For more information:', '3cx-webinar' ) . '</strong></p>';
		$content .= '<p>' . wp3cxw_link('https://www.3cx.com/phone-system/video-conferencing/', __( '3CX WebMeeting', '3cx-webinar' ) ) . '</p>';
		$content .= '<p>' . wp3cxw_link('https://www.3cx.com/community/forums/video-conferencing/', __( 'Support Forum', '3cx-webinar' ) ) . '</p>';
		$this->screen->set_help_sidebar( $content );
	}
}
