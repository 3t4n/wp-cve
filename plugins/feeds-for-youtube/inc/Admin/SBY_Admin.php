<?php

namespace SmashBalloon\YouTubeFeed\Admin;

use SmashBalloon\YouTubeFeed\SBY_Display_Elements;
use SmashBalloon\YouTubeFeed\SBY_GDPR_Integrations;

class SBY_Admin extends SBY_Admin_Abstract {

	public function additional_settings_init() {
		$text_domain = SBY_TEXT_DOMAIN;

		$defaults = sby_settings_defaults();

		$args = array(
			'name' => 'num',
			'default' => $defaults['num'],
			'section' => 'sbspf_layout',
			'callback' => 'text',
			'min' => 1,
			'max' => 50,
			'size' => 4,
			'title' => __( 'Number of Videos', $text_domain ),
			'additional' => '<span class="sby_note">' . __( 'Number of videos to show initially.', $text_domain ) . '</span>',
			'shortcode' => array(
				'key' => 'num',
				'example' => 5,
				'description' => __( 'The number of videos in the feed', $text_domain ),
				'display_section' => 'layout'
			)
		);
		$this->add_settings_field( $args );

		$select_options = array(
			array(
				'label' => 'px',
				'value' => 'px'
			),
			array(
				'label' => '%',
				'value' => '%'
			)
		);
		$args = array(
			'name' => 'itemspacing',
			'default' => $defaults['itemspacing'],
			'section' => 'sbspf_layout',
			'callback' => 'text',
			'min' => 0,
			'size' => 4,
			'title' => __( 'Spacing between videos', $text_domain ),
			'shortcode' => array(
				'key' => 'itemspacing',
				'example' => '5px',
				'description' => __( 'The spacing/padding around the videos in the feed. Any number with a unit like "px" or "em".', $text_domain ),
				'display_section' => 'layout'
			),
			'select_name' => 'itemspacingunit',
			'select_options' => $select_options,
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'Info Display', $text_domain ),
			'id' => 'sbspf_info_display',
			'tab' => 'customize',
			'save_after' => 'true'
		);
		$this->add_settings_section( $args );

		$select_options = array(
			array(
				'label' => __( 'Below video thumbnail', $text_domain ),
				'value' => 'below'
			),
			array(
				'label' => __( 'Next to video thumbnail', $text_domain ),
				'value' => 'side'
			)
		);
		$args = array(
			'name' => 'infoposition',
			'default' => 'below',
			'section' => 'sbspf_info_display',
			'callback' => 'select',
			'title' => __( 'Position', $text_domain ),
			'shortcode' => array(
				'key' => 'infoposition',
				'example' => 'side',
				'description' => __( 'Where the information (title, description, stats) will display. eg.', $text_domain ) . ' below, side, none',
				'display_section' => 'customize'
			),
			'options' => $select_options,
		);
		$this->add_settings_field( $args );

		$api_key_not_entered = empty( $this->settings['api_key'] ) ? ' sby_api_key_needed' : false;

		$include_options = array(
			array(
				'label' => __( 'Play Icon', $text_domain ),
				'value' => 'icon',
                'class' => false
			),
			array(
				'label' => __( 'Title', $text_domain ),
				'value' => 'title',
				'class' => false
			),
			array(
				'label' => __( 'User Name', $text_domain ),
				'value' => 'user',
				'class' => false
			),
			array(
				'label' => __( 'Views', $text_domain ),
				'value' => 'views',
				'class' => $api_key_not_entered
			),
			array(
				'label' => __( 'Date', $text_domain ),
				'value' => 'date',
				'class' => false
			),
			array(
				'label' => __( 'Live Stream Countdown (when applies)', $text_domain ),
				'value' => 'countdown',
				'class' => false
			),
			array(
				'label' => __( 'Stats (like and comment counts)', $text_domain ),
				'value' => 'stats',
				'class' => $api_key_not_entered
			),
			array(
				'label' => __( 'Description', $text_domain ),
				'value' => 'description',
				'class' => false
			),
		);
		$args = array(
			'name' => 'include',
			'default' => $defaults['include'],
			'section' => 'sbspf_info_display',
			'callback' => 'multi_checkbox',
			'title' => __( 'Show/Hide', $text_domain ),
			'shortcode' => array(
				'key' => 'include',
				'example' => '"title, description, date"',
				'description' => __( 'Comma separated list of what video information (title, description, stats) will display in the feed. eg.', $text_domain ) . ' title, description ',
				'display_section' => 'customize'
			),
			'select_options' => $include_options,
		);
		$this->add_settings_field( $args );

		$include_options = array(
			array(
				'label' => __( 'Title', $text_domain ),
				'value' => 'title',
				'class' => false
			),
			array(
				'label' => __( 'User Name', $text_domain ),
				'value' => 'user',
				'class' => false
			),
			array(
				'label' => __( 'Views', $text_domain ),
				'value' => 'views',
				'class' => $api_key_not_entered
			),
			array(
				'label' => __( 'Date', $text_domain ),
				'value' => 'date',
				'class' => false
			),
			array(
				'label' => __( 'Live Stream Countdown (when applies)', $text_domain ),
				'value' => 'countdown',
				'class' => false
			),
			array(
				'label' => __( 'Description', $text_domain ),
				'value' => 'description',
				'class' => false
			),
			array(
				'label' => __( 'Stats (like and comment counts)', $text_domain ),
				'value' => 'stats',
				'class' => $api_key_not_entered
			),
		);
		$args = array(
			'name' => 'hoverinclude',
			'default' => $defaults['hoverinclude'],
			'section' => 'sbspf_info_display',
			'callback' => 'multi_checkbox',
			'title' => __( 'Hover Show/Hide', $text_domain ),
			'shortcode' => array(
				'key' => 'hoverinclude',
				'example' => '"title, stats, date"',
				'description' => __( 'Comma separated list of what video information (title, description, stats) will display when hovering over the video thumbnail. eg.', $text_domain ) . ' title, stats ',
				'display_section' => 'customize'
			),
			'select_options' => $include_options,
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'descriptionlength',
			'default' => $defaults['descriptionlength'],
			'section' => 'sbspf_info_display',
			'callback' => 'text',
			'min' => 5,
			'max' => 1000,
			'size' => 4,
			'title' => __( 'Description Character Length', $text_domain ),
			'shortcode' => array(
				'key' => 'descriptionlength',
				'example' => 300,
				'description' => __( 'Maximum length of the description', $text_domain ),
				'display_section' => 'customize'
			)
		);
		$this->add_settings_field( $args );

		$select_options = array(
			array(
				'label' => __( 'inherit', $text_domain ),
				'value' => 'inherit'
			),
			array(
				'label' => __( '20px', $text_domain ),
				'value' => '20px'
			),
			array(
				'label' => __( '18px', $text_domain ),
				'value' => '18px'
			),
			array(
				'label' => __( '16px', $text_domain ),
				'value' => '16px'
			),
			array(
				'label' => __( '15px', $text_domain ),
				'value' => '15px'
			),
			array(
				'label' => __( '14px', $text_domain ),
				'value' => '14px'
			),
			array(
				'label' => __( '13px', $text_domain ),
				'value' => '13px'
			),
			array(
				'label' => __( '12px', $text_domain ),
				'value' => '12px'
			),
		);
		$args = array(
			'name' => 'descriptiontextsize',
			'default' => '13px',
			'section' => 'sbspf_info_display',
			'callback' => 'select',
			'title' => __( 'Description Text Size', $text_domain ),
			'shortcode' => array(
				'key' => 'descriptiontextsize',
				'example' => 'inherit',
				'description' => __( 'Size of description text, size of other text will be relative to this size.', $text_domain ) . ' 13px, 14px, inherit',
				'display_section' => 'customize'
			),
			'tooltip_info' => __( 'Size of video description text, size of other text in the info display will be relative to this size.', $text_domain ),
			'options' => $select_options,
		);
		$this->add_settings_field( $args );

		$full_date = SBY_Display_Elements::full_date( strtotime( 'July 25th, 5:30 pm' ), array( 'dateformat' => '0', 'customdate' => '' ), $include_time = true );
		$date_format_options = array(
			array(
				'label' => sprintf( __( 'WordPress Default (%s)', $text_domain ), $full_date ),
				'value' => '0'
			),
			array(
				'label' => __( 'July 25th, 5:30 pm', $text_domain ),
				'value' => '1'
			),
			array(
				'label' => __( 'July 25th', $text_domain ),
				'value' => '2'
			),
            array(
				'label' => __( 'Mon July 25th', $text_domain ),
				'value' => '3'
			),
            array(
				'label' => __( 'Monday July 25th', $text_domain ),
				'value' => '4'
			),
			array(
				'label' => __( 'Mon Jul 25th, 2020', $text_domain ),
				'value' => '5'
			),
			array(
				'label' => __( 'Monday July 25th, 2020 - 5:30 pm', $text_domain ),
				'value' => '6'
			),
			array(
				'label' => __( '07.25.20', $text_domain ),
				'value' => '7'
			),
			array(
				'label' => __( '07.25.20 - 17:30', $text_domain ),
				'value' => '8'
			),
			array(
				'label' => __( '07/25/20', $text_domain ),
				'value' => '9'
			),
			array(
				'label' => __( '25.07.20', $text_domain ),
				'value' => '10'
			),
			array(
				'label' => __( '25/07/20', $text_domain ),
				'value' => '11'
			),
			array(
				'label' => __( '25th July 2020, 17:30', $text_domain ),
				'value' => '12'
			),
			array(
				'label' => __( 'Custom (Enter Below)', $text_domain ),
				'value' => 'custom'
			)
        );
		$args = array(
			'name' => 'dateformat',
			'default' => '',
			'section' => 'sbspf_info_display',
			'date_formats' => $date_format_options,
			'callback' => 'date_format',
			'title' => __( 'Date Format', $text_domain )
		);
		$this->add_settings_field( $args );
		$this->add_false_field( 'userelative', 'customize' );
		$this->add_false_field( 'disablecdn', 'customize' );

		$args = array(
			'title' => __( 'Info Text/Translations', $text_domain ),
			'id' => 'sbspf_info_text',
			'tab' => 'customize',
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'viewstext',
			'default' => __( 'views', $text_domain ),
			'section' => 'sbspf_info_text',
			'callback' => 'text',
			'title' => __( '"Views" Text', $text_domain ),
			'shortcode' => array(
				'key' => 'viewstext',
				'example' => '"times viewed"',
				'description' => __( 'The text that appears after the number of views.', $text_domain ),
				'display_section' => 'text'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'agotext',
			'default' => __( 'ago', $text_domain ),
			'section' => 'sbspf_info_text',
			'callback' => 'text',
			'title' => __( '"Ago" Text', $text_domain ),
			'shortcode' => array(
				'key' => 'agotext',
				'example' => '"prior"',
				'description' => __( 'The text that appears after relative times in the past.', $text_domain ),
				'display_section' => 'text'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'beforedatetext',
			'default' => __( 'Streaming live', $text_domain ),
			'section' => 'sbspf_info_text',
			'callback' => 'text',
			'title' => __( 'Before Date Text', $text_domain ),
			'shortcode' => array(
				'key' => 'beforedatetext',
				'example' => '"Watch Live"',
				'description' => __( 'The text that appears before live stream dates.', $text_domain ),
				'display_section' => 'text'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'beforestreamtimetext',
			'default' => __( 'Streaming live in', $text_domain ),
			'section' => 'sbspf_info_text',
			'callback' => 'text',
			'title' => __( 'Before Live Stream Text', $text_domain ),
			'shortcode' => array(
				'key' => 'beforestreamtimetext',
				'example' => '"Starting in"',
				'description' => __( 'The text that appears before relative live stream times.', $text_domain ),
				'display_section' => 'text'
			)
		);
		$this->add_settings_field( $args );
		
		$args = array(
			'name' => 'minutetext',
			'default' => __( 'minute', $text_domain ),
			'section' => 'sbspf_info_text',
			'callback' => 'text',
			'title' => __( '"Minute" text', $text_domain ),
			'shortcode' => array(
				'key' => 'minutetext',
				'example' => '"minuto"',
				'description' => __( 'Translation for singular "minute".', $text_domain ),
				'display_section' => 'text'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'minutestext',
			'default' => __( 'minute', $text_domain ),
			'section' => 'sbspf_info_text',
			'callback' => 'text',
			'title' => __( '"Minutes" text', $text_domain ),
			'shortcode' => array(
				'key' => 'minutestext',
				'example' => '"minuten"',
				'description' => __( 'Translation for plural "minutes".', $text_domain ),
				'display_section' => 'text'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'hourstext',
			'default' => __( 'hours', $text_domain ),
			'section' => 'sbspf_info_text',
			'callback' => 'text',
			'title' => __( '"Hours" text', $text_domain ),
			'shortcode' => array(
				'key' => 'hourstext',
				'example' => '"minuten"',
				'description' => __( 'Translation for "hours".', $text_domain ),
				'display_section' => 'text'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'watchnowtext',
			'default' => __( 'Watch Now', $text_domain ),
			'section' => 'sbspf_info_text',
			'callback' => 'text',
			'title' => __( '"Watch Now" Text', $text_domain ),
			'shortcode' => array(
				'key' => 'watchnowtext',
				'example' => '"Now Playing"',
				'description' => __( 'The text that appears when video is currently streaming live.', $text_domain ),
				'display_section' => 'text'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'thousandstext',
			'default' => __( 'K', $text_domain ),
			'section' => 'sbspf_info_text',
			'callback' => 'text',
			'title' => __( '"Thousands" text', $text_domain ),
			'shortcode' => array(
				'key' => 'thousandstext',
				'example' => '" thousand"',
				'description' => __( 'Text after statistics if over 1 thousand.', $text_domain ),
				'display_section' => 'text'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'millionstext',
			'default' => __( 'M', $text_domain ),
			'section' => 'sbspf_info_text',
			'callback' => 'text',
			'title' => __( '"Millions" text', $text_domain ),
			'shortcode' => array(
				'key' => 'millionstext',
				'example' => '" million"',
				'description' => __( 'Text after statistics if over 1 million.', $text_domain ),
				'display_section' => 'text'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'Header', $text_domain ),
			'id' => 'sbspf_header',
			'tab' => 'customize',
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'showheader',
			'section' => 'sbspf_header',
			'callback' => 'checkbox',
			'title' => __( 'Show Header', $text_domain ),
			'default' => true,
			'shortcode' => array(
				'key' => 'showheader',
				'example' => 'false',
				'description' => __( 'Include a header for this feed.', $text_domain ),
				'display_section' => 'header'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'showdescription',
			'section' => 'sbspf_header',
			'callback' => 'checkbox',
			'title' => __( 'Show Channel Description', $text_domain ),
			'default' => true,
			'shortcode' => array(
				'key' => 'showdescription',
				'example' => 'false',
				'description' => __( 'Include the channel description in the header.', $text_domain ),
				'display_section' => 'header'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'showsubscribers',
			'section' => 'sbspf_header',
			'callback' => 'checkbox',
			'title' => __( 'Show Subscribers', $text_domain ),
			'default' => true,
			'shortcode' => array(
				'key' => 'showsubscribers',
				'example' => 'false',
				'description' => __( 'Include the number of subscribers in the header.', $text_domain ),
				'display_section' => 'header'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'subscriberstext',
			'default' => __( 'subscribers', $text_domain ),
			'section' => 'sbspf_header',
			'callback' => 'text',
			'title' => __( '"Subscribers" Text', $text_domain ),
			'shortcode' => array(
				'key' => 'subscriberstext',
				'example' => '"followers"',
				'description' => __( 'The text that appears after the number of subscribers.', $text_domain ),
				'display_section' => 'header'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( '"Load More" Button', $text_domain ),
			'id' => 'sbspf_loadmore',
			'tab' => 'customize',
			'save_after' => 'true'
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'showbutton',
			'section' => 'sbspf_loadmore',
			'callback' => 'checkbox',
			'title' => __( 'Show "Load More" Button', $text_domain ),
			'default' => true,
			'shortcode' => array(
				'key' => 'showbutton',
				'example' => 'false',
				'description' => __( 'Include a "Load More" button at the bottom of the feed to load more videos.', $text_domain ),
				'display_section' => 'button'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'buttoncolor',
			'default' => '',
			'section' => 'sbspf_loadmore',
			'callback' => 'color',
			'title' => __( 'Button Background Color', $text_domain ),
			'shortcode' => array(
				'key' => 'buttoncolor',
				'example' => '#0f0',
				'description' => __( 'Background color for the "Load More" button. Any hex color code.', $text_domain ),
				'display_section' => 'button'
			),
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'buttontextcolor',
			'default' => '',
			'section' => 'sbspf_loadmore',
			'callback' => 'color',
			'title' => __( 'Button Text Color', $text_domain ),
			'shortcode' => array(
				'key' => 'buttontextcolor',
				'example' => '#00f',
				'description' => __( 'Text color for the "Load More" button. Any hex color code.', $text_domain ),
				'display_section' => 'button'
			),
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'buttontext',
			'default' => __( 'Load More...', $text_domain ),
			'section' => 'sbspf_loadmore',
			'callback' => 'text',
			'title' => __( 'Button Text', $text_domain ),
			'shortcode' => array(
				'key' => 'buttontext',
				'example' => '"More Videos"',
				'description' => __( 'The text that appers on the "Load More" button.', $text_domain ),
				'display_section' => 'button'
			)
		);
		$this->add_settings_field( $args );

		/* Subscribe button */
		$args = array(
			'title' => __( '"Subscribe" Button', $text_domain ),
			'id' => 'sbspf_subscribe',
			'tab' => 'customize',
			'save_after' => true
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'showsubscribe',
			'section' => 'sbspf_subscribe',
			'callback' => 'checkbox',
			'title' => __( 'Show "Subscribe" Button', $text_domain ),
			'default' => true,
			'shortcode' => array(
				'key' => 'showsubscribe',
				'example' => 'false',
				'description' => __( 'Include a "Subscribe" button at the bottom of the feed to load more videos.', $text_domain ),
				'display_section' => 'subscribe'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'subscribecolor',
			'default' => '',
			'section' => 'sbspf_subscribe',
			'callback' => 'color',
			'title' => __( 'Subscribe Background Color', $text_domain ),
			'shortcode' => array(
				'key' => 'subscribecolor',
				'example' => '#0f0',
				'description' => __( 'Background color for the "Subscribe" button. Any hex color code.', $text_domain ),
				'display_section' => 'subscribe'
			),
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'subscribetextcolor',
			'default' => '',
			'section' => 'sbspf_subscribe',
			'callback' => 'color',
			'title' => __( 'Subscribe Text Color', $text_domain ),
			'shortcode' => array(
				'key' => 'subscribetextcolor',
				'example' => '#00f',
				'description' => __( 'Text color for the "Subscribe" button. Any hex color code.', $text_domain ),
				'display_section' => 'subscribe'
			),
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'subscribetext',
			'default' => __( 'Subscribe', $text_domain ),
			'section' => 'sbspf_subscribe',
			'callback' => 'text',
			'title' => __( 'Subscribe Text', $text_domain ),
			'shortcode' => array(
				'key' => 'subscribetext',
				'example' => '"Subscribe to My Channel"',
				'description' => __( 'The text that appers on the "Subscribe" button.', $text_domain ),
				'display_section' => 'subscribe'
			)
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'Video Experience', $text_domain ),
			'id' => 'sbspf_experience',
			'tab' => 'customize',
			'save_after' => 'true'
		);
		$this->add_settings_section( $args );

		$select_options = array(
			array(
				'label' => '9:16',
				'value' => '9:16'
			),
			array(
				'label' => '3:4',
				'value' => '3:4'
			),
		);
		$args = array(
			'name' => 'playerratio',
			'default' => '9:16',
			'section' => 'sbspf_experience',
			'callback' => 'select',
			'title' => __( 'Player Size Ratio', $text_domain ),
			'shortcode' => array(
				'key' => 'playerratio',
				'example' => '9:16',
				'description' => __( 'Player height relative to width e.g.', $text_domain ) . ' 9:16, 3:4',
				'display_section' => 'experience'
			),
			'options' => $select_options,
			'tooltip_info' => __( 'A 9:16 ratio does not leave room for video title and playback tools while a 3:4 ratio does.', $text_domain )
		);
		$this->add_settings_field( $args );

		$select_options = array(
			array(
				'label' => __( 'Play when clicked', $text_domain ),
				'value' => 'onclick'
			),
			array(
				'label' => 'Play automatically (desktop only)',
				'value' => 'automatically'
			)
		);
		$args = array(
			'name' => 'playvideo',
			'default' => 'onclick',
			'section' => 'sbspf_experience',
			'callback' => 'select',
			'title' => __( 'When does video play?', $text_domain ),
			'shortcode' => array(
				'key' => 'playvideo',
				'example' => 'onclick',
				'description' => __( 'What the user needs to do to play a video. eg.', $text_domain ) . ' onclick, automatically',
				'display_section' => 'experience'
			),
			'options' => $select_options,
			'tooltip_info' => __( 'List layout will not play automatically. Choose whether to play the video automatically in the player or wait until the user clicks the play button after the video is loaded.', $text_domain )
		);
		$this->add_settings_field( $args );

		$cta_options = array(
			array(
				'label' => __( 'Related Videos', SBY_TEXT_DOMAIN ),
				'slug' => 'related',
				'note' => __( 'Display video thumbnails from the feed that play on your site when clicked.', SBY_TEXT_DOMAIN )
			),
			array(
				'label' => 'Custom Link',
				'slug' => 'link',
				'note' => __( 'Display a button link to a custom URL.', SBY_TEXT_DOMAIN ),
				'options' => array(
					array(
						'name' => 'instructions',
						'callback' => 'instructions',
						'instructions' => __( 'To set a link for each video individually, add the link and button text in the video description on YouTube in this format:', SBY_TEXT_DOMAIN ) . '<br><br><code>{Link: Button Text https://my-site.com/buy-now/my-product/}</code>',
						'label' => __( 'Custom link for each video', SBY_TEXT_DOMAIN ),
					),
					array(
						'name' => 'url',
						'callback' => 'text',
						'label' => __( 'Default Link', SBY_TEXT_DOMAIN ),
						'class' => 'large-text',
						'default' => '',
						'shortcode' => array(
							'example' => 'https://my-site.com/buy-now/my-product/',
							'description' => __( 'URL for viewer to visit for the call to action.', $text_domain ),
						)
					),
					array(
						'name' => 'opentype',
						'callback' => 'select',
						'options' => array(
							array(
								'label' => __( 'Same window', SBY_TEXT_DOMAIN ),
								'value' => 'same'
							),
							array(
								'label' => __( 'New window', SBY_TEXT_DOMAIN ),
								'value' => 'newwindow'
							)
						),
						'label' => __( 'Link Open Type', SBY_TEXT_DOMAIN ),
						'default' => 'same',
						'shortcode' => array(
							'example' => 'newwindow',
							'description' => __( 'Whether to open the page in a new window or the same window.', $text_domain ),
						)
					),
					array(
						'name' => 'text',
						'callback' => 'text',
						'label' => __( 'Default Button Text', SBY_TEXT_DOMAIN ),
						'default' => __( 'Learn More', SBY_TEXT_DOMAIN ),
						'shortcode' => array(
							'example' => 'Buy Now',
							'description' => __( 'Text that appears on the call-to-action button.', $text_domain ),
						)
					),
					array(
						'name' => 'color',
						'default' => '',
						'callback' => 'color',
						'label' => __( 'Button Background Color', SBY_TEXT_DOMAIN ),
						'shortcode' => array(
							'example' => '#0f0',
							'description' => __( 'Button background. Turns opaque on hover.', $text_domain ),
						)
					),
					array(
						'name' => 'textcolor',
						'default' => '',
						'callback' => 'color',
						'label' => __( 'Button Text Color', SBY_TEXT_DOMAIN ),
						'shortcode' => array(
							'example' => '#0f0',
							'description' => __( 'Color of the text on the call-to-action-button', $text_domain ),
						)
					)
				)
			),
			array(
				'label' => __( 'YouTube Default', SBY_TEXT_DOMAIN ),
				'slug' => 'default',
				'note' => __( 'YouTube suggested videos from your channel that play on YouTube when clicked.', SBY_TEXT_DOMAIN )
			),
		);

		$args = array(
			'name' => 'cta',
			'default' => 'related',
			'section' => 'sbspf_experience',
			'callback' => 'sub_option',
			'sub_options' => $cta_options,
			'title' => __( 'Call to Action', $text_domain ),
			'before' => '<p style="margin-bottom: 10px">' . __( 'What the user sees when a video pauses or ends.', $text_domain ) . '</p>',
			'shortcode' => array(
				'key' => 'cta',
				'example' => 'link',
				'description' => __( 'What the user sees when a video pauses or ends. eg.', $text_domain ) . ' related, link',
				'display_section' => 'experience'
			),
			'tooltip_info' => __( 'Choose what will happen after a video is paused or completes.', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'Moderation', $text_domain ),
			'id' => 'sbspf_moderation',
			'tab' => 'customize',
			'save_after' => 'true'
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'includewords',
			'default' => '',
			'section' => 'sbspf_moderation',
			'callback' => 'text',
			'class' => 'large-text',
			'title' => __( 'Show videos containing these words or hashtags', $text_domain ),
			'shortcode' => array(
				'key' => 'includewords',
				'example' => '#filter',
				'description' => __( 'Show videos that have specific text in the title or description.', $text_domain ),
				'display_section' => 'customize'
			),
			'additional' => __( '"includewords" separate multiple words with commas, include "#" for hashtags', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'excludewords',
			'default' => '',
			'section' => 'sbspf_moderation',
			'callback' => 'text',
			'class' => 'large-text',
			'title' => __( 'Remove videos containing these words or hashtags', $text_domain ),
			'shortcode' => array(
				'key' => 'excludewords',
				'example' => '#filter',
				'description' => __( 'Remove videos that have specific text in the title or description.', $text_domain ),
				'display_section' => 'customize'
			),
			'additional' => __( '"excludewords" separate multiple words with commas, include "#" for hashtags', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'hidevideos',
			'default' => '',
			'section' => 'sbspf_moderation',
			'callback' => 'textarea',
			'title' => __( 'Hide Specific Videos', $text_domain ),
			'options' => $select_options,
			'tooltip_info' => __( 'Separate IDs with commas.', $text_domain ) . '<a class="sbspf_tooltip_link" href="JavaScript:void(0);">'.$this->default_tooltip_text().'</a>
            <p class="sbspf_tooltip sbspf_more_info">' . __( 'These are the specific ID numbers associated with a video or with a post. You can find the ID of a video by viewing the video on YouTube and copy/pasting the ID number from the end of the URL. ex. <code>https://www.youtube.com/watch?v=<span class="sbspf-highlight">Ij1KvL8eN</span></code>', $text_domain ) . '</p>'
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'Custom Code Snippets', $text_domain ),
			'id' => 'sbspf_custom_snippets',
			'tab' => 'customize'
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'custom_css',
			'default' => '',
			'section' => 'sbspf_custom_snippets',
			'callback' => 'textarea',
			'title' => __( 'Custom CSS', $text_domain ),
			'options' => $select_options,
			'tooltip_info' => __( 'Enter your own custom CSS in the box below', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'custom_js',
			'default' => '',
			'section' => 'sbspf_custom_snippets',
			'callback' => 'textarea',
			'title' => __( 'Custom JavaScript', $text_domain ),
			'options' => $select_options,
			'tooltip_info' => __( 'Enter your own custom JavaScript/jQuery in the box below', $text_domain ),
			'note' => __( 'Note: Custom JavaScript reruns every time more videos are loaded into the feed', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'title' => __( 'GDPR', $text_domain ),
			'id' => 'sbspf_gdpr',
			'tab' => 'customize',
			'save_after' => 'true'
		);
		$this->add_settings_section( $args );

		$this->add_settings_field( array(
			'name' => 'gdpr',
			'title' => __( 'Enable GDPR Settings', $text_domain ),
			'callback'  => 'gdpr', // name of the function that outputs the html
			'section' => 'sbspf_gdpr', // matches the section name
		));

		$args = array(
			'title' => __( 'Advanced', $text_domain ),
			'id' => 'sbspf_advanced',
			'tab' => 'customize',
			'save_after' => 'true'
		);
		$this->add_settings_section( $args );

		$args = array(
			'name' => 'preserve_settings',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Preserve settings when plugin is removed', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'When removing the plugin your settings are automatically erased. Checking this box will prevent any settings from being deleted. This means that you can uninstall and reinstall the plugin without losing your settings.', $text_domain )
		);
		$this->add_settings_field( $args );

		$select_options = array(
			array(
				'label' => __( 'Background', $text_domain ),
				'value' => 'background'
			),
			array(
				'label' => __( 'Page', $text_domain ),
				'value' => 'page'
			),
			array(
				'label' => __( 'None', $text_domain ),
				'value' => 'none'
			)
		);
		$additional = '<input id="sby-clear-cache" class="button-secondary sbspf-button-action" data-sby-action="sby_delete_wp_posts" data-sby-confirm="'.esc_attr( 'This will permanently delete all YouTube posts from the wp_posts table and the related data in the postmeta table. Existing feeds will only have 15 or fewer videos available initially. Continue?', $text_domain ).'" style="margin-top: 1px;" type="submit" value="'.esc_attr( 'Clear YouTube Posts', $text_domain ).'">';
		$args = array(
			'name' => 'storage_process',
			'default' => '',
			'section' => 'sbspf_advanced',
			'callback' => 'select',
			'title' => __( 'Local storage process', $text_domain ),
			'options' => $select_options,
			'additional' => $additional,
			'tooltip_info' => __( 'To preserve your feeds and videos even if the YouTube API is unavailable, a record of each video is added to the wp_posts table in the WordPress database. Please note that changing this setting to "none" will limit the number of posts available in the feed to 15 or less.', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'ajaxtheme',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Are you using an AJAX theme?', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'When navigating your site, if your theme uses Ajax to load content into your pages (meaning your page doesn\'t refresh) then check this setting. If you\'re not sure then it\'s best to leave this setting unchecked while checking with your theme author, otherwise checking it may cause a problem.', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'ajax_post_load',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Load initial posts with AJAX', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'Initial videos will be loaded using AJAX instead of added to the page directly. If you use page caching, this will allow the feed to update according to the "Check for new videos every" setting on the "Configure" tab.', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'customtemplates',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Enable Custom Templates', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'The default HTML for the feed can be replaced with custom templates added to your theme\'s folder. Enable this setting to use these templates. See <a href="https://smashballoon.com/youtube-custom-templates/" target="_blank">this guide</a>', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'eagerload',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Load Iframes on Page Load', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'To optimize the performance of your site and feeds, the plugin loads iframes only after a visitor interacts with the feed. Enabling this setting will cause YouTube player iframes to load when the page loads. Some features may work differently when this is enabled.', $text_domain )
		);
		$this->add_settings_field( $args );

		$args = array(
			'name' => 'enqueue_js_in_head',
			'section' => 'sbspf_advanced',
			'callback' => 'checkbox',
			'title' => __( 'Enqueue JS file in head', $text_domain ),
			'default' => false,
			'tooltip_info' => __( 'Check this box if you\'d like to enqueue the JavaScript file for the plugin in the head instead of the footer.', $text_domain )
		);
		$this->add_settings_field( $args );
	}

	public function instructions( $args ) {
	    ?>
        <div class="sbspf_instructions_wrap">
            <?php echo $args['instructions']?>
        </div>
        <?php
    }

	public function cache( $args ) {
		$social_network = $this->vars->social_network();
		$type_selected = isset( $this->settings['caching_type'] ) ? $this->settings['caching_type'] : 'page';
		$caching_time = isset( $this->settings['caching_time'] ) ? $this->settings['caching_time'] : 1;
		$cache_time_unit_selected = isset( $this->settings['caching_time_unit'] ) ? $this->settings['caching_time_unit'] : 'hours';
		$cache_cron_interval_selected = isset( $this->settings['cache_cron_interval'] ) ? $this->settings['cache_cron_interval'] : '';
		$cache_cron_time = isset( $this->settings['cache_cron_time'] ) ? $this->settings['cache_cron_time'] : '';
		$cache_cron_am_pm = isset( $this->settings['cache_cron_am_pm'] ) ? $this->settings['cache_cron_am_pm'] : '';
		?>
        <div class="sbspf_cache_settings_wrap">
            <div class="sbspf_row">
                <input type="radio" name="<?php echo $this->option_name.'[caching_type]'; ?>" class="sbspf_caching_type_input" id="sbspf_caching_type_page" value="page"<?php if ( $type_selected === 'page' ) echo ' checked'?>>
                <label class="sbspf_radio_label" for="sbspf_caching_type_page"><?php _e ( 'When the page loads', $this->vars->text_domain() ); ?></label>
                <a class="sbspf_tooltip_link" href="JavaScript:void(0);" style="position: relative; top: 2px;"><?php echo $this->default_tooltip_text(); ?></a>
                <p class="sbspf_tooltip sbspf_more_info"><?php echo sprintf( __( "Your %s data is temporarily cached by the plugin in your WordPress database. There are two ways that you can set the plugin to check for new data:<br><br>
                <b>1. When the page loads</b><br>Selecting this option means that when the cache expires then the plugin will check %s for new posts the next time that the feed is loaded. You can choose how long this data should be cached for. If you set the time to 60 minutes then the plugin will clear the cached data after that length of time, and the next time the page is viewed it will check for new data. <b>Tip:</b> If you're experiencing an issue with the plugin not updating automatically then try enabling the setting labeled <b>'Cron Clear Cache'</b> which is located on the 'Customize' tab.<br><br>
                <b>2. In the background</b><br>Selecting this option means that the plugin will check for new data in the background so that the feed is updated behind the scenes. You can select at what time and how often the plugin should check for new data using the settings below. <b>Please note</b> that the plugin will initially check for data from YouTube when the page first loads, but then after that will check in the background on the schedule selected - unless the cache is cleared.", $this->vars->text_domain() ), $social_network, $social_network ); ?>
                </p>
            </div>
            <div class="sbspf_row sbspf-caching-page-options" style="display: none;">
				<?php _e ( 'Every', $this->vars->text_domain() ); ?>:
                <input name="<?php echo $this->option_name.'[caching_time]'; ?>" type="text" value="<?php echo esc_attr( $caching_time ); ?>" size="4">
                <select name="<?php echo $this->option_name.'[caching_time_unit]'; ?>">
                    <option value="minutes"<?php if ( $cache_time_unit_selected === 'minutes' ) echo ' selected'?>><?php _e ( 'Minutes', $this->vars->text_domain() ); ?></option>
                    <option value="hours"<?php if ( $cache_time_unit_selected === 'hours' ) echo ' selected'?>><?php _e ( 'Hours', $this->vars->text_domain() ); ?></option>
                    <option value="days"<?php if ( $cache_time_unit_selected === 'days' ) echo ' selected'?>><?php _e ( 'Days', $this->vars->text_domain() ); ?></option>
                </select>
                <a class="sbspf_tooltip_link" href="JavaScript:void(0);"><?php _e ( 'What does this mean?', $this->vars->text_domain() ); ?></a>
                <p class="sbspf_tooltip sbspf_more_info"><?php echo sprintf( __("Your %s posts are temporarily cached by the plugin in your WordPress database. You can choose how long the posts should be cached for. If you set the time to 1 hour then the plugin will clear the cache after that length of time and check %s for posts again.", $this->vars->text_domain() ), $social_network, $social_network ); ?></p>
            </div>

            <div class="sbspf_row">
                <input type="radio" name="<?php echo $this->option_name.'[caching_type]'; ?>" id="sbspf_caching_type_cron" class="sbspf_caching_type_input" value="background" <?php if ( $type_selected === 'background' ) echo ' checked'?>>
                <label class="sbspf_radio_label" for="sbspf_caching_type_cron"><?php _e ( 'In the background', $this->vars->text_domain() ); ?></label>
            </div>
            <div class="sbspf_row sbspf-caching-cron-options" style="display: block;">

                <select name="<?php echo $this->option_name.'[cache_cron_interval]'; ?>" id="sbspf_cache_cron_interval">
                    <option value="30mins"<?php if ( $cache_cron_interval_selected === '30mins' ) echo ' selected'?>><?php _e ( 'Every 30 minutes', $this->vars->text_domain() ); ?></option>
                    <option value="1hour"<?php if ( $cache_cron_interval_selected === '1hour' ) echo ' selected'?>><?php _e ( 'Every hour', $this->vars->text_domain() ); ?></option>
                    <option value="12hours"<?php if ( $cache_cron_interval_selected === '12hours' ) echo ' selected'?>><?php _e ( 'Every 12 hours', $this->vars->text_domain() ); ?></option>
                    <option value="24hours"<?php if ( $cache_cron_interval_selected === '24hours' ) echo ' selected'?>><?php _e ( 'Every 24 hours', $this->vars->text_domain() ); ?></option>
                </select>

                <div id="sbspf-caching-time-settings" style="">
					<?php _e ( 'at', $this->vars->text_domain() ); ?>
                    <select name="<?php echo $this->option_name.'[cache_cron_time]'; ?>" style="width: 80px">
                        <option value="1"<?php if ( (int)$cache_cron_time === 1 ) echo ' selected'?>>1:00</option>
                        <option value="2"<?php if ( (int)$cache_cron_time === 2 ) echo ' selected'?>>2:00</option>
                        <option value="3"<?php if ( (int)$cache_cron_time === 3 ) echo ' selected'?>>3:00</option>
                        <option value="4"<?php if ( (int)$cache_cron_time === 4 ) echo ' selected'?>>4:00</option>
                        <option value="5"<?php if ( (int)$cache_cron_time === 5 ) echo ' selected'?>>5:00</option>
                        <option value="6"<?php if ( (int)$cache_cron_time === 6 ) echo ' selected'?>>6:00</option>
                        <option value="7"<?php if ( (int)$cache_cron_time === 7 ) echo ' selected'?>>7:00</option>
                        <option value="8"<?php if ( (int)$cache_cron_time === 8 ) echo ' selected'?>>8:00</option>
                        <option value="9"<?php if ( (int)$cache_cron_time === 9 ) echo ' selected'?>>9:00</option>
                        <option value="10"<?php if ( (int)$cache_cron_time === 10 ) echo ' selected'?>>10:00</option>
                        <option value="11"<?php if ( (int)$cache_cron_time === 11 ) echo ' selected'?>>11:00</option>
                        <option value="0"<?php if ( (int)$cache_cron_time === 0 ) echo ' selected'?>>12:00</option>
                    </select>

                    <select name="<?php echo $this->option_name.'[cache_cron_am_pm]'; ?>" style="width: 50px">
                        <option value="am"<?php if ( $cache_cron_am_pm === 'am' ) echo ' selected'?>><?php _e ( 'AM', $this->vars->text_domain() ); ?></option>
                        <option value="pm"<?php if ( $cache_cron_am_pm === 'pm' ) echo ' selected'?>><?php _e ( 'PM', $this->vars->text_domain() ); ?></option>
                    </select>
                </div>

				<?php
				if ( wp_next_scheduled( 'sby_feed_update' ) ) {
					$time_format = get_option( 'time_format' );
					if ( ! $time_format ) {
						$time_format = 'g:i a';
					}
					//
					$schedule = wp_get_schedule( 'sby_feed_update' );
					if ( $schedule == '30mins' ) $schedule = __( 'every 30 minutes', $this->vars->text_domain() );
					if ( $schedule == 'twicedaily' ) $schedule = __( 'every 12 hours', $this->vars->text_domain() );
					$sbspf_next_cron_event = wp_next_scheduled( 'sby_feed_update' );
					echo '<p class="sbspf-caching-sched-notice"><span><b>' . __( 'Next check', $this->vars->text_domain() ) . ': ' . date( $time_format, $sbspf_next_cron_event + sby_get_utc_offset() ) . ' (' . $schedule . ')</b> - ' . __( 'Note: Saving the settings on this page will clear the cache and reset this schedule', $this->vars->text_domain() ) . '</span></p>';
				} else {
					echo '<p style="font-size: 11px; color: #666;">' . __( 'Nothing currently scheduled', $this->vars->text_domain() ) . '</p>';
				}
				?>
            </div>
        </div>
		<?php
	}

	public function gdpr( $args ) {
		$gdpr = ( isset( $this->settings['gdpr'] ) ) ? $this->settings['gdpr'] : 'auto';
		$select_options = array(
			array(
				'label' => __( 'Automatic', 'youtube-feed' ),
				'value' => 'auto'
			),
			array(
				'label' => __( 'Yes', 'youtube-feed' ),
				'value' => 'yes'
			),
			array(
				'label' => __( 'No', 'youtube-feed' ),
				'value' => 'no'
			)
		)
		?>
		<?php
		$gdpr_list = "<ul class='sby-list'>
                            	<li>" . __('YouTube Player API will not be loaded.', 'youtube-feed') . "</li>
                            	<li>" . __('Thumbnail images for videos will be displayed instead of the actual video.', 'youtube-feed') . "</li>
                            	<li>" . __('To view videos, visitors will click on links to view the video on youtube.com.', 'youtube-feed') . "</li>
                            </ul>";
		?>
        <div>
            <select name="<?php echo $this->option_name.'[gdpr]'; ?>" id="sbspf_gdpr_setting">
				<?php foreach ( $select_options as $select_option ) :
					$selected = $select_option['value'] === $gdpr ? ' selected' : '';
					?>
                    <option value="<?php echo esc_attr( $select_option['value'] ); ?>"<?php echo $selected; ?> ><?php echo esc_html( $select_option['label'] ); ?></option>
				<?php endforeach; ?>
            </select>
            <a class="sbspf_tooltip_link" href="JavaScript:void(0);"><?php echo $this->default_tooltip_text(); ?></a>
            <div class="sbspf_tooltip sbspf_more_info gdpr_tooltip">

                <p><span><?php _e("Yes", 'youtube-feed' ); ?>:</span> <?php _e("Enabling this setting prevents all videos and external code from loading on your website. To accommodate this, some features of the plugin will be disabled or limited.", 'youtube-feed' ); ?> <a href="JavaScript:void(0);" class="sbspf_show_gdpr_list"><?php _e( 'What will be limited?', 'youtube-feed' ); ?></a></p>

				<?php echo "<div class='sbspf_gdpr_list'>" . $gdpr_list . '</div>'; ?>


                <p><span><?php _e("No", 'youtube-feed' ); ?>:</span> <?php _e("The plugin will still make some requests to display and play videos directly from YouTube.", 'youtube-feed' ); ?></p>


                <p><span><?php _e("Automatic", 'youtube-feed' ); ?>:</span> <?php echo sprintf( __( 'The plugin will only videos if consent has been given by one of these integrated %s', 'youtube-feed' ), '<a href="https://smashballoon.com/doc/gdpr-plugin-list/?youtube" target="_blank" rel="noopener">' . __( 'GDPR cookie plugins', 'youtube-feed' ) . '</a>' ); ?></p>

                <p><?php echo sprintf( __( '%s to learn more about GDPR compliance in the Feeds for YouTube plugin.', 'youtube-feed' ), '<a href="https://smashballoon.com/doc/feeds-for-youtube-gdpr-compliance/?youtube" target="_blank" rel="noopener">'. __( 'Click here', 'youtube-feed' ).'</a>' ); ?></p>
            </div>
        </div>

        <div id="sbspf_images_options" class="sbspf_box">
            <div class="sbspf_box_setting">
                    <?php
                    $checked = isset( $this->settings['disablecdn'] ) && $this->settings['disablecdn'] ? ' checked' : false;
                    ?>
                    <input name="<?php echo $this->option_name.'[disablecdn]'; ?>" id="sbspf_disablecdn" class="sbspf_single_checkbox" type="checkbox"<?php echo $checked; ?>>
                    <label for="sbspf_disablecdn"><?php _e("Block CDN Images", 'youtube-feed' ); ?></label>
                    <a class="sbspf_tooltip_link" href="JavaScript:void(0);"><?php echo $this->default_tooltip_text(); ?></a>
                    <div class="sbspf_tooltip sbspf_more_info">
	                    <?php _e("Images in the feed are loaded from the YouTube CDN. If you want to avoid these images being loaded until consent is given then enabling this setting will show a blank placeholder image instead.", 'youtube-feed' ); ?>
                    </div>
            </div>
        </div>

		<?php if ( ! SBY_GDPR_Integrations::gdpr_tests_successful( isset( $_GET['retest'] ) ) ) :
			$errors = SBY_GDPR_Integrations::gdpr_tests_error_message();
			?>
            <div class="sbspf_box sbspf_gdpr_error">
                <div class="sbspf_box_setting">
                    <p>
                        <strong><?php _e( 'Error:', 'youtube-feed' ); ?></strong> <?php _e("Due to a configuration issue on your web server, the GDPR setting is unable to be enabled. Please see below for more information.", 'youtube-feed' ); ?></p>
                    <p>
						<?php echo $errors; ?>
                    </p>
                </div>
            </div>
		<?php else: ?>

            <div class="sbspf_gdpr_auto">
				<?php if ( SBY_GDPR_Integrations::gdpr_plugins_active() ) :
					$active_plugin = SBY_GDPR_Integrations::gdpr_plugins_active();
					?>
                    <div class="sbspf_gdpr_plugin_active">
                        <div class="sbspf_active">
                            <p>
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-check-circle fa-w-16 fa-2x"><path fill="currentColor" d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z" class=""></path></svg>
                                <b><?php echo sprintf( __( '%s detected', 'youtube-feed' ), $active_plugin ); ?></b>
                                <br />
								<?php _e( 'Some Feeds for YouTube features will be limited for visitors to ensure GDPR compliance until they give consent.', 'youtube-feed' ); ?>
                                <a href="JavaScript:void(0);" class="sbspf_show_gdpr_list"><?php _e( 'What will be limited?', 'youtube-feed' ); ?></a>
                            </p>
							<?php echo "<div class='sbspf_gdpr_list'>" . $gdpr_list . '</div>'; ?>
                        </div>

                    </div>
				<?php else: ?>
                    <div class="sbspf_box">
                        <div class="sbspf_box_setting">
                            <p><?php _e( 'No GDPR consent plugin detected. Install a compatible <a href="https://smashballoon.com/doc/gdpr-plugin-list/?youtube" target="_blank">GDPR consent plugin</a>, or manually enable the setting above to display a GDPR compliant version of the feed to all visitors.', 'youtube-feed' ); ?></p>
                        </div>
                    </div>
				<?php endif; ?>
            </div>

            <div class="sbspf_box sbspf_gdpr_yes">
                <div class="sbspf_box_setting">
                    <p><?php _e( "No requests will be made to third-party websites. To accommodate this, some features of the plugin will be limited:", 'youtube-feed' ); ?></p>
					<?php echo $gdpr_list; ?>
                </div>
            </div>

            <div class="sbspf_box sbspf_gdpr_no">
                <div class="sbspf_box_setting">
                    <p><?php _e( "The plugin will function as normal and load images and videos directly from YouTube.", 'youtube-feed' ); ?></p>
                </div>
            </div>

		<?php endif;
	}

	public function search_query_string( $args ){
	    $checked = $this->settings['usecustomsearch'] ? ' checked' : '';
	    $custom_search_string = $this->settings['customsearch'];
	    ?>
        <div class="sbspf_row" style="min-height: 29px;">
            <div class="sbspf_col sbspf_one">&nbsp;
            </div>
            <div class="sbspf_col sbspf_two sbspf_custom_search_wrap">
                <input id="sbspf_usecustomsearch" type="checkbox" name="sby_settings[usecustomsearch]"<?php echo $checked; ?>><label for="sbspf_usecustomsearch">use a custom search</label> <a href="https://smashballoon.com/youtube-feed/custom-search-guide/" target="_blank" rel="noopener">Custom Search Guide</a>
                <div id="sbspf_usecustomsearch_reveal">
                    <label>Custom Search</label><br>
                    <textarea name="sby_settings[customsearch]" id="sbspf_customsearch" type="text" style="width: 100%;"><?php echo esc_attr( $custom_search_string ); ?></textarea>
                </div>
            </div>

        </div>
    <?php
    }

    public function live_options( $args ) {
	    $checked = $this->settings['showpast'] ? ' checked' : '';
	    ?>
        <div class="sbspf_row" style="min-height: 29px;">
            <div class="sbspf_col sbspf_one">&nbsp;
            </div>
            <div class="sbspf_col sbspf_two sbspf_live_options_wrap sbspf_onselect_reveal">
                <input id="sbspf_showpast" type="checkbox" name="sby_settings[showpast]"<?php echo $checked; ?>><label for="sbspf_showpast"><?php _e( 'Show past live streams', 'youtube-feed' ); ?></label>
            </div>

        </div>
        <?php
    }

    public function sub_option( $args ) {
	    $value = isset( $this->settings[ $args['name'] ] ) ? $this->settings[ $args['name'] ] : 'related';

	    $cta_options = $args['sub_options'];
	    ?>
	    <?php if ( ! empty( $args['before'] ) ) {
	        echo $args['before'];
        }?>

                <div class="sbspf_sub_options">
			<?php foreach ( $cta_options as $sub_option ) : ?>
                <div class="sbspf_sub_option_cell">
                    <input class="sbspf_sub_option_type" id="sbspf_sub_option_type_<?php echo esc_attr( $sub_option['slug'] ); ?>" name="<?php echo $this->option_name.'['.$args['name'].']'; ?>" type="radio" value="<?php echo esc_attr( $sub_option['slug'] ); ?>"<?php if ( $sub_option['slug'] === $value ) echo ' checked'?>><label for="sbspf_sub_option_type_<?php echo esc_attr( $sub_option['slug'] ); ?>"><span class="sbspf_label"><?php echo $sub_option['label']; ?></span></label>
                </div>
			<?php endforeach; ?>

            <div class="sbspf_box_setting">
				<?php if ( isset( $cta_options ) ) : foreach ( $cta_options as $sub_option ) : ?>
                    <div class="sbspf_sub_option_settings sbspf_sub_option_type_<?php echo esc_attr( $sub_option['slug'] ); ?>">

                        <div class="sbspf_sub_option_setting">
							<?php echo sby_admin_icon( 'info-circle', 'sbspf_small_svg' ); ?>&nbsp;&nbsp;&nbsp;<span class="sbspf_note" style="margin-left: 0;"><?php echo $sub_option['note']; ?></span>
                        </div>
						<?php if ( ! empty( $sub_option['options'] ) ) : ?>
                            <?php foreach ( $sub_option['options'] as $option ) :
                                $option['name'] = $sub_option['slug'].$option['name'];
                                ?>
                                <div class="sbspf_sub_option_setting">
                                    <?php if ( $option['callback'] !== 'checkbox' ) :
                                        if ( isset( $option['shortcode'] ) ) : ?>
                                            <label title="<?php echo __( 'Click for shortcode option', $this->vars->text_domain() ); ?>"><?php echo $option['label']; ?></label><code class="sbspf_shortcode"> <?php echo $option['name'] . "\n"; ?>
                                                Eg: <?php echo $option['name']; ?>=<?php echo $option['shortcode']['example']; ?></code><br>
                                        <?php else: ?>
                                            <label><?php echo $option['label']; ?></label><br>
                                        <?php endif; ?>
                                    <?php else:
                                        $option['shortcode_example'] = $option['shortcode']['example'];
                                        $option['has_shortcode'] = true;
                                    endif; ?>
                                    <?php call_user_func_array( array( $this, $option['callback'] ), array( $option ) ); ?>

                                </div>

                            <?php endforeach; ?>
						<?php endif; ?>

                    </div>

				<?php endforeach; endif; ?>
            </div>
        </div>
<?php
    }

    public function date_format( $args ) {
	    $checkbox_args = array(
	        'name' => 'userelative',
		    'callback' => 'checkbox',
		    'label' => __( 'Use relative times when less than 2 days', SBY_TEXT_DOMAIN ),
		    'default' => true,
		    //'shortcode_example' => 'false',
		    //'has_shortcode' => '1',
		    'tooltip_info' => __( 'For times that are within 2 days of the video playing time, relative times are displayed rather than the date.  e.g. "5 hours ago"', SBY_TEXT_DOMAIN )
	    );
	    ?>
        <div class="sbspf_sub_option_setting">
        <?php
	    $this->checkbox( $checkbox_args );
        ?>
        </div>
        <div class="sbspf_sub_option_setting sbspf_box_setting">
            <label><?php _e( 'Full Date Format', SBY_TEXT_DOMAIN ); ?></label><code class="sbspf_shortcode" style="display: none; float: none; position: relative; max-width: 300px"> dateformat Eg: dateformat="F j, Y g:i a"</code><br>
	    <?php
	    $args['options'] = $args['date_formats'];
	    $this->select( $args );
	    $value = isset( $this->settings['customdate'] ) ? stripslashes( $this->settings['customdate'] ) : '';
	    ?>

        </div>
        <div class="sbspf_sub_option_setting sby_customdate_wrap">
            <label><?php _e( 'Custom Format', SBY_TEXT_DOMAIN ); ?></label><br>
            <input name="sby_settings[customdate]" id="sby_settings_customdate" type="text" placeholder="F j, Y g:i a" value="<?php echo esc_attr( $value ); ?>"><a href="https://smashballoon.com/youtube-feed/docs/date/" class="sbspf-external-link sbspf_note" target="_blank"><?php _e( 'Examples', SBY_TEXT_DOMAIN ); ?></a>
        </div>
	    <?php
    }

	public function get_connected_accounts() {
		global $sby_settings;

		if ( isset( $sby_settings['connected_accounts'] ) ) {
			return $sby_settings['connected_accounts'];
		}
		return array();
	}

	public function access_token_listener() {
		if ( isset( $_GET['page'], $_GET['sby_access_token'] ) && 
			( $_GET['page'] === 'youtube-feed-settings' || $_GET['page'] === 'sby-feed-builder' ) 
		) {
			sby_attempt_connection();
		}
	}

	public static function connect_account( $args ) {
		sby_update_or_connect_account( $args );
	}

	public function after_create_menues() {
		remove_menu_page( 'edit.php?post_type=' . SBY_CPT );
	}
}
