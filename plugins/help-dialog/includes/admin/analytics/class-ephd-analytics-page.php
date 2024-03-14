<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display analytics
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Analytics_Page {

	private $global_config;
	private $global_specs;
    private $widgets_config;
	private $message = [];
    private $filters = [];
    private $filters_options = [];

	const TIMEFRAME_INTERVALS = array(
		'last_30_days' => [ 'today - 30 days', 'now' ],
		'this_week'    => [ 'monday this week', 'now' ],
		'last_week'    => [ 'monday this week - 1 week', 'monday this week' ],
		'this_month'   => [ 'first day of this month', 'now' ],
		'last_month'   => [ 'first day of this month - 1 month', 'first day of this month' ],
		'this_year'    => [ 'first day of january this year', 'now' ]
	);

	/**
	 * Set post filters data
	 */
    private function set_filters_post_data() {
	    $this->filters['location'] = EPHD_Utilities::post( 'ephd_admin_analytics_filters_location' );

	    $timeframe = EPHD_Utilities::post( 'ephd_admin_analytics_filters_timeframe', 'last_30_days' );
	    $this->filters['timeframe'] = $timeframe;
	    $this->filters['timeframe_from'] = date( 'Y-m-d', strtotime( self::TIMEFRAME_INTERVALS[$timeframe][0] ) );
	    $this->filters['timeframe_to'] = date( 'Y-m-d', strtotime( self::TIMEFRAME_INTERVALS[$timeframe][1] ) );
    }

	/**
	 * Set post filters list options
	 */
	private function set_filters_options() {

		$this->filters_options['location'] = $this->get_location_filter_options();

		$this->filters_options['timeframe'] = array(
			'last_30_days' => __( 'Last 30 days', 'help-dialog' ),
			'this_week'    => __( 'This Week', 'help-dialog' ),
			'last_week'    => __( 'Last Week', 'help-dialog' ),
			'this_month'   => __( 'This Month', 'help-dialog' ),
			'last_month'   => __( 'Last Month', 'help-dialog' ),
			'this_year'    => __( 'This Year', 'help-dialog' )
        );
	}

	/**
	 * Display analytics page with toolbar and content.
	 */
	public function display_page() {

		// retrieve global Help Dialog configuration
		$this->global_config = ephd_get_instance()->global_config_obj->get_config( true );
		if ( is_wp_error( $this->global_config ) ) {
			EPHD_HTML_Admin::display_config_error_page( $this->global_config );
			return;
		}

		// retrieve widgets Help Dialog configuration
		$this->widgets_config = ephd_get_instance()->widgets_config_obj->get_config( true );
		if ( is_wp_error( $this->widgets_config ) ) {
			EPHD_HTML_Admin::display_config_error_page( $this->widgets_config );
			return;
		}

		// retrieve config specs
		$this->global_specs = EPHD_Config_Specs::get_fields_specification( EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME );

        // init filters post data
		$this->set_filters_post_data();

		// init filters options
		$this->set_filters_options();

		$admin_page_views = $this->get_regular_views_config();

        EPHD_HTML_Admin::admin_page_css_missing_message( true );    ?>

		<!-- Admin Page Wrap -->
		<div id="ephd-admin-page-wrap">

			<div class="ephd-analytics-page-container">				<?php

				/**
				 * ADMIN HEADER (HD logo and list of HDs dropdown)
				 */
				EPHD_HTML_Admin::admin_header();

				/**
				 * ADMIN TOOLBAR
				 */
				EPHD_HTML_Admin::admin_toolbar( $admin_page_views );

				/**
				 *  ERROR LOGS NOTIFICATION
				 */
                // do not show for now self::error_log_notification();

				/**
				 * LIST OF SETTINGS IN TABS
				 */
				EPHD_HTML_Admin::admin_settings_tab_content( $admin_page_views, 'ephd-config-wrapper' );    ?>

				<div class="ephd-bottom-notice-message fadeOutDown"></div>

			</div>

		</div>	    <?php

		/**
		 * Show any notifications
		 */
		foreach ( $this->message as $class => $message ) {
			echo  EPHD_HTML_Forms::notification_box_bottom( $message, '', $class );
		}
	}

	/**
	 * Get HTML for Global Analytics Configuration box on Settings tab
	 *
	 * @return false|string
	 */
	private static function settings_tab_global_configuration_box() {
		ob_start();

        /* NOTE: comment out impressions on/off code for now
		self::display_checkbox_toggle_html( $this->global_config['analytic_count_launcher_impression'], $this->global_specs['analytic_count_launcher_impression'] );
        */
		return ob_get_clean();
	}

	/**
	 * Get HTML for Excluded User Roles box on Settings tab
	 *
	 * @param $excluded_roles
	 * @return false|string
	 */
	private static function settings_tab_excluded_user_roles_box( $excluded_roles ) {
		ob_start();

		$editable_roles = [];
		foreach ( get_editable_roles() as $role_name => $role_info ) {
			$editable_roles[$role_name] = $role_info['name'];
		}

		EPHD_HTML_Elements::checkboxes_multi_select( array(
				'label'             => __( 'Exclude specific user roles from analytics', 'help-dialog' ),
				'name'              => 'analytic_excluded_roles',
				'options'           => array_merge( $editable_roles ),
				'value'             => $excluded_roles,
				'main_tag'          => 'div',
				'input_class'       => '',
				'input_group_class' => 'ephd-admin__checkboxes-multiselect',
			)
		);

		return ob_get_clean();
	}

	/**
	 * Show actions row for Settings tab
	 *
	 * @return false|string
	 */
	private static function settings_tab_actions_row() {

		ob_start();		?>

		<div class="ephd-admin__list-actions-row"><?php
			EPHD_HTML_Elements::submit_button_v2( __( 'Save Settings', 'help-dialog' ), 'ephd_hd_save_settings_btn', 'ephd__hdl__action__save_order', '', true, '', 'ephd-success-btn' );    ?>
		</div>      <?php

		return ob_get_clean();
	}

	/**
	 * Show actions row for Analytics tabs
	 * Currently displays dropdown of timeframes and post/page filter and post/page link
	 * array $filter_boxes
	 *
	 * @param array $filter_boxes
	 * @return false|string
	 */
	private function get_top_actions_html( $filter_boxes=[] ) {

		ob_start(); ?>
		<div class="ephd-admin__list-actions-row--left ephd-ap-filters-wrap"> <?php

            // Filters form
            if ( ! empty( array_intersect( ['timeframe', 'location'], $filter_boxes ) ) ) {
                $this->get_analytics_filters_form( $filter_boxes );
            }

            // Location post/page link
		    if ( in_array( 'location', $filter_boxes ) ) {
				$this->get_analytics_location_link_form();
		    }   ?>

        </div>  <?php

		return ob_get_clean();
	}

	/**
     * Get filters form
     *
	 * @param $filter_boxes
	 */
    private function get_analytics_filters_form( $filter_boxes ) {  ?>
	    <form class="ephd-ap-filters" action="" method="POST">  <?php

                // Timeframe Filter
                if ( in_array( 'timeframe', $filter_boxes ) ) {
	                EPHD_HTML_Elements::dropdown(
		                array(
			                'name'          => 'ephd_admin_analytics_filters_timeframe',
			                'input_class'   => 'ephd_admin_analytics_filters',
			                'value'         => $this->filters['timeframe'],
			                'label'         => __( 'Timeframe', 'help-dialog' ),
			                'options'       => $this->filters_options['timeframe'],
		                )
	                );
                }

	            // Post / Page locations filter - Show filter if we have any location with data
			    if ( in_array( 'location', $filter_boxes ) && ! empty( $this->filters_options['location'] ) ) {
                    EPHD_HTML_Elements::dropdown(
	                    array(
		                    'name'          => 'ephd_admin_analytics_filters_location',
		                    'input_class'   => 'ephd_admin_analytics_filters',
		                    'tooltip_title' => __( 'Page Selection', 'help-dialog' ),
		                    'tooltip_body'  => __( 'Pages with low traffic might not have any analytics data recorded for the time interval chosen. ' .
		                                           'These pages will not show in the list. Choose a longer time interval to list more pages.', 'help-dialog' ),
		                    'value'         => $this->filters['location'],
		                    'label'         => __( 'Location', 'help-dialog' ),
		                    'options'       => array( '' => __( 'Select Page', 'help-dialog' ) ) + $this->filters_options['location'],
	                    )
                    );
			    }   ?>
        </form> <?php
    }

	/**
	 * Get location page/post link
	 */
	private function get_analytics_location_link_form() {

		$location = $this->filters['location'];
		if ( ! is_numeric( $location ) || $location < 0 ) {
			return;
		}

        // Get page/post title and link url
		if ( $location == EPHD_Config_Specs::HOME_PAGE ) {
            $title = __( 'Home Page', 'help-dialog' );
            $href = home_url();
		} else {
			$title = get_the_title( $location );
			$href = get_permalink( $location );
		}

		if ( ! empty( $href ) ) {   ?>
            <div class="ephd-ap-filters-location-link">
                <a href="<?php echo esc_attr( $href ); ?>" target="_blank">
                    <?php echo esc_html__( 'Open', 'help-dialog' ) . ' ' . esc_html( $title ); ?>
                    <span class="ephdfa ephdfa-external-link"></span>
                </a>
            </div>  <?php
		}
	}

	/**
     * Get pages / posts list for location filter
     *
	 * @return array
	 */
    private function get_location_filter_options() {

	    $locations = array();

	    // retrieve locations from analytics data
	    $db = new EPHD_Analytics_DB();
	    $locations_obj = $db->get_custom_table_grouped_rows_by_where_clause( 'analytics', 'page_id', 'obj_id', $this->filters['timeframe_from'], $this->filters['timeframe_to'] );
	    if ( is_wp_error( $locations_obj ) ) {
		    return [];
	    }

	    // retrieve locations from widgets configs
	    $widgets_config = ephd_get_instance()->widgets_config_obj->get_config();
	    foreach ( $widgets_config as $widget ) {
			// TODO: do something with new include/exclude option
            // TODO: Should we add empty posts from CPTs? I'm not sure because the list might be too big
		    $widget_locations_ids = array_merge( $widget['location_pages_list'], $widget['location_posts_list'] );

            // marge widget locations with analytics locations
            foreach ( $widget_locations_ids as $widget_location_ids ) {
                if ( ! isset( $locations_obj[$widget_location_ids] ) ) {
	                $locations_obj[$widget_location_ids] = new stdClass();
	                $locations_obj[$widget_location_ids]->obj_id = $widget_location_ids;
	                $locations_obj[$widget_location_ids]->times = 0;
                }
            }
	    }

	    // Add post titles
	    $locations_obj = $db->add_post_titles( $locations_obj );

	    // Sort alphabetically by title
	    uasort( $locations_obj, function ( $a, $b ) {
		    return strcasecmp( $a->title, $b->title );
	    });

        // prepare an array for the EPHD_HTML_Elements::dropdown()
        foreach ( $locations_obj as $location_id => $location_data ) {
	        $locations[$location_id] = array(
		        'label' => $location_data->title . ' ' . ( empty( $location_data->times ) ? __( '(no data)', 'help-dialog' ) : '' ),
		        'class' => empty( $location_data->times ) ? 'ephd-input-empty' : '',
                'times' => $location_data->times,
            );
        }

        return $locations;
    }

	/**
	 * Get HTML for Help Dialog Analytics boxes
	 *
	 * @param $boxes_list_config
	 * @return array
	 */
	private static function get_analytics_tab_boxes_list( $boxes_list_config ) {

		$boxes = array();

		// Add pro info box

		foreach ( $boxes_list_config as $box ) {

            if ( ! is_array( $box ) ) {
                continue;
            }

			switch ( $box['type'] ) {
				case 'table' :
					$html = self::get_table_results_html( $box );
					break;
				case 'single' :
					$html = self::get_single_results_html( $box );
					break;
				case 'multiple' :
				case 'multiple-col-2' :
					$html = self::get_multiple_results_html( $box );
					break;
				default :
					$html = '';
					break;
			}

            // Add header paragraph above box
            if ( isset( $box['box_intro'] ) ) {
	            $boxes[] = [
		            'class' => 'ephd-ap-boxes-separator',
                    'html'  => '<h2>' . $box['box_intro'] . '</h2>',
                ];
            }

			$boxes[] = [
				'class'         => 'ephd-ap-results-box ' . $box['css_class'],
				'title'         => $box['title'],
				'tooltip_title' => $box['tooltip_title'],
				'tooltip_desc'  => $box['tooltip_desc'],
				'tooltip_args'  => isset( $box['tooltip_args'] ) ? $box['tooltip_args'] : [],
				'html'          => $html
			];
		}

        // Add introduction paragraph above all boxes
        if ( isset( $boxes_list_config['tab_intro'] ) ) {
	        array_unshift( $boxes, [ 'html'  => $boxes_list_config['tab_intro'] ] );
        }

		return $boxes;
	}

	/**
	 * Get analytics boxes list config
	 *
     *  Class Options   css_class   ephd-ap-results--ctr             Used for CTR Tables
     *                              ephd-ap-results--total-ctr       Used for Total CTR Tables
     *                              ephd-ap-results--views           Used for Views only Tables
     *                              ephd-ap-results--count           Used for Count only Tables
     *
	 * @return array
	 */
	private function get_analytics_tab_boxes_list_config() {

		// Get core analytics results
		$analytics_db = new EPHD_Analytics_DB();
		$total_invocations    = $analytics_db->get_total_invocations_count();
		$total_searches       = $analytics_db->get_total_searches_count();
		$total_contact_opened = $analytics_db->get_total_contact_opened_count();

		// Get pro analytics results
		$results = apply_filters( 'ephd_admin_analytics_results', [] );

        // wrap parameters for location link (link to Per Page tab)
		$location_link_cells_wrap = array(
			'title' => array(
				'class' => 'ephd-ap-cell-location-link',
				'data'  => ['obj_id']
            )
        );

		return [

			// OVERVIEW
			'overview'   => [
				'tab_intro' => __( 'Overview analytics show basic user interaction with the Help Dialog. It shows totals rather than per-page analytics.', 'help-dialog' ),
				[
					'type'          => 'single',
					'css_class'     => 'ephd-ap-results--single',
					'title'         => __( 'Opened', 'help-dialog' ),
					'tooltip_title' => __( 'Opened', 'help-dialog' ),
					'tooltip_desc'  => __( 'Number of times the Help Dialog was opened.', 'help-dialog' ),
					'results'       => $total_invocations,
					'is_pro'        => false
				],
				[
					'type'          => 'single',
					'css_class'     => 'ephd-ap-results--single',
					'title'         => __( 'Searches', 'help-dialog' ),
					'tooltip_title' => __( 'Searches', 'help-dialog' ),
					'tooltip_desc'  => __( 'Number of times a user searched in the Help Dialog.', 'help-dialog' ),
					'results'       => $total_searches,
					'is_pro'        => false
				],
				[
					'type'          => 'single',
					'css_class'     => 'ephd-ap-results--single',
					'title'         => __( 'Contact Form Opened', 'help-dialog' ),
					'tooltip_title' => __( 'Contact Form Opened', 'help-dialog' ),
					'tooltip_desc'  => __( 'Number of times the Contact Form was opened.', 'help-dialog' ),
					'results'       => $total_contact_opened,
					'is_pro'        => false
				],
			],

			// REACH
			'reach'      => [
				//'tab_intro' => __( 'Reach analytics shows initial user interaction with the Help Dialog. Did user open the Help Dialog and which pages did users interact with the most and least? By default, admins, editors, and authors have their interactions with Help Dialog excluded from analytics.', 'help-dialog' ),
				'tab_intro' => __( 'Reach analytics show user interaction with three areas of the Help Dialog: FAQs, search, and contact form.', 'help-dialog' ),
				[
					'type'          => 'multiple-col-2',
					'css_class'     => 'ephd-ap-results--multiple-col-2',
					'title'         => __( 'Help Dialog Total CTR', 'help-dialog' ),
					'tooltip_title' => __( 'Number of Clicks to Open the Help Dialog.', 'help-dialog' ),
					'tooltip_desc'  => sprintf( esc_html__( '%sCTR%s measures the usage of the Help Dialog in relation to page views. %sView:%s When a page is loaded for longer than 5 seconds, a view is recorded. %sClick:%s When the Help Dialog Launcher Icon is clicked, a click is recorded.', 'help-dialog' ),
										'<strong>', '</strong>', '<br><br><strong>', '</strong>', '<br><br><strong>', '</strong>' ) . '<hr/>' .
					                    esc_html__( 'Formula', 'help-dialog' ) . ': ' . esc_html__( 'Click-through Rate = Views / launcher clicks', 'help-dialog' ) . ' <a href="https://www.helpdialog.com/documentation/reach-analytics/#articleTOC_1" target="_blank">' . esc_html__( 'Learn More', 'help-dialog' ) . '</a>.',
					// FUTURE TODO example 'tooltip_args'  => [ 'link_url' => '#', 'link_text' => __( 'Example Button', 'help-dialog' ) ], // TODO example button
					'results'       => isset( $results['click_through_rate'] ) ? $results['click_through_rate'] : [],
                    'columns'         => array(
	                    'views'  => __( 'Total Views', 'help-dialog' ),
	                    'clicks' => __( 'Total Clicks', 'help-dialog' ),
	                    'ctr'    => __( 'CTR', 'help-dialog' ),
                    )
				],
				[
					'type'          => 'multiple',
					'css_class'     => 'ephd-ap-results--multiple-row-1',
					'title'         => __( 'All Clicks', 'help-dialog' ),
					'tooltip_title' => __( 'Overall Clicks', 'help-dialog' ),
					'tooltip_desc'  => esc_html__( 'Total number of times a user clicked to launch the Help Dialog, to search, or to submit the Contact form.', 'help-dialog' ) . ' <a href="' . esc_url( Echo_Help_Dialog::$plugin_url . 'img/analytics/all-clicks.png' ) . '" target="_blank">' . esc_html__( 'Learn More', 'help-dialog' ) . '</a>.',
					'results'       => isset( $results['all_click_totals'] ) ? $results['all_click_totals'] : [],
					'columns'       => array(
						'launcher' => __( 'Launcher', 'help-dialog' ),
						'search'   => __( 'Search', 'help-dialog' ),
						'contact'  => __( 'Contact Form', 'help-dialog' ),
					),
                    'icons'          => array(
	                    'launcher' => 'ephdfa ephdfa-comments-o',
	                    'search'   => 'ephdfa ephdfa-search',
	                    'contact'  => 'ephdfa ephdfa-envelope-square',
                    )
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--ctr',
					'title'         => __( 'Most Engaged Help Dialogs by CTR', 'help-dialog' ),
					'tooltip_title' => __( 'On Which Pages Did Users Most Often Open the Help Dialog?', 'help-dialog' ),
					//'tooltip_desc'  => __( 'These pages are either too complex to understand, or they are of great interest to the user who is looking for more information.', 'help-dialog' ) . ' <a href="https://www.helpdialog.com/documentation/reach-analytics/#articleTOC_2" target="_blank">' . __( 'Learn More', 'help-dialog' ) . '</a>.',
					'tooltip_desc'  => esc_html__( 'These pages are either too complex to understand, or they are of great interest to the user who is looking for more information.', 'help-dialog' ) .
										' <a href="https://www.helpdialog.com/documentation/reach-analytics/#articleTOC_2" target="_blank">' . esc_html__( 'Learn More', 'help-dialog' ) . '</a><hr/>' .
					                   sprintf( esc_html__( '%sCTR%s measures the usage of the Help Dialog in relation to page views. %sView:%s When a page is loaded for longer than 5 seconds, a view is recorded. %sClick:%s When the Help Dialog Launcher Icon is clicked, a click is recorded.', 'help-dialog' ),
						                   '<strong>', '</strong>', '<br><br><strong>', '</strong>', '<br><br><strong>', '</strong>' ) . '<hr/>' .
					                   esc_html__( 'Formula', 'help-dialog' ) . ': ' . esc_html__( 'Click-through Rate = Views / launcher clicks', 'help-dialog' ) . ' <a href="https://www.helpdialog.com/documentation/reach-analytics/#articleTOC_1" target="_blank">' . esc_html__( 'Learn More', 'help-dialog' ) . '</a>.',
					'results'       => isset( $results['most_engagement_by_pages'] ) ? $results['most_engagement_by_pages'] : [],
					'cells_wrap'    => $location_link_cells_wrap,
					'columns'       => array(
						'title'  => __( 'Top Pages', 'help-dialog' ),
						'views'  => __( 'Views', 'help-dialog' ),
						'clicks' => __( 'Clicks', 'help-dialog' ),
						'ctr'    => __( 'CTR', 'help-dialog' ),
					),

				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--ctr',
					'title'         => __( 'Least Engaged Help Dialogs by CTR', 'help-dialog' ),
					'tooltip_title' => __( 'On Which Pages Did Users Least Often Open the Help Dialog?', 'help-dialog' ),
					'tooltip_desc'  => esc_html__( 'These pages are either too simple or not interesting enough for user to ask about.', 'help-dialog' ) . ' <a href="https://www.helpdialog.com/documentation/reach-analytics/#articleTOC_3" target="_blank">' . esc_html__( 'Learn More', 'help-dialog' ) . '</a><hr/>' .
				                       sprintf( esc_html__( '%sCTR%s measures the usage of the Help Dialog in relation to page views. %sView:%s When a page is loaded for longer than 5 seconds, a view is recorded. %sClick:%s When the Help Dialog Launcher Icon is clicked, a click is recorded.', 'help-dialog' ),
				                       '<strong>', '</strong>', '<br><br><strong>', '</strong>', '<br><br><strong>', '</strong>' ) . '<hr/>' .
				                       esc_html__( 'Formula', 'help-dialog' ) . ': ' . esc_html__( 'Click-through Rate = Views / launcher clicks', 'help-dialog' ) . ' <a href="https://www.helpdialog.com/documentation/reach-analytics/#articleTOC_1" target="_blank">' . esc_html__( 'Learn More', 'help-dialog' ) . '</a>.',
					'results'       => isset( $results['least_engagement_by_pages'] ) ? $results['least_engagement_by_pages'] : [],
					'cells_wrap'    => $location_link_cells_wrap,
					'columns'       => array(
						'title'  => __( 'Top Pages', 'help-dialog' ),
						'views'  => __( 'Views', 'help-dialog' ),
						'clicks' => __( 'Clicks', 'help-dialog' ),
						'ctr'    => __( 'CTR', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Searches Initiated', 'help-dialog' ),
					'tooltip_title' => __( 'On Which Pages Did Users Search the Most?', 'help-dialog' ),
					'tooltip_desc'  => __( 'Pages that have the most searches indicate that the shown FAQs in the Help Dialog are not sufficient or not relevant to help users with their questions.', 'help-dialog' ) . ' <a href="' . esc_url( Echo_Help_Dialog::$plugin_url . 'img/analytics/searches-initiated.png' ) . '" target="_blank">' . __( 'Learn More', 'help-dialog' ) . '</a>.',
					'results'       => isset( $results['searches_count_by_page'] ) ? $results['searches_count_by_page'] : [],
					'cells_wrap'    => $location_link_cells_wrap,
					'columns'       => array(
						'title' => __( 'Top Pages', 'help-dialog' ),
						'times' => __( 'Searches', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Contact Form Views', 'help-dialog' ),
					'tooltip_title' => __( 'On Which Pages Did Users Look at the Contact Form?', 'help-dialog' ),
					'tooltip_desc'  => esc_html__( 'Pages on which users most often opened the contact form.', 'help-dialog' ) . ' <a href="' . esc_url( Echo_Help_Dialog::$plugin_url . 'img/analytics/contact-form-views.png' ) . '" target="_blank">' . esc_html__( 'Learn More', 'help-dialog' ) . '</a>.',
					'results'       => isset( $results['contact_opened_by_page'] ) ? $results['contact_opened_by_page'] : [],
					'cells_wrap'    => $location_link_cells_wrap,
					'columns'       => array(
						'title' => __( 'Top Pages', 'help-dialog' ),
						'times' => __( 'Opened', 'help-dialog' ),
					),
				],
			],

			// ENGAGEMENT
			/* 'engagement' => [

			], */

			// CONTENT
			'content'    => [
				'tab_intro' => __( 'Content analytics is showing popularity of specific questions, articles and searches.', 'help-dialog' ),
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--ctr',
					'title'         => __( 'Most Helpful Page FAQs', 'help-dialog' ),
					'tooltip_title' => __( 'Pages with Most Helpful FAQs', 'help-dialog' ),
					'tooltip_desc'  => esc_html__( 'These pages have good FAQs that help users to better understand the topic or support their interest.', 'help-dialog' ) . '<hr/>' .
										sprintf( esc_html__( '%sCTR%s measures the usage of the FAQs in relation to Help Dialog views. %sView:%s When the Help Dialog is opened, a view is recorded. %sClick:%s When FAQ is clicked, a click is recorded.', 'help-dialog' ),
										'<strong>', '</strong>', '<br><br><strong>', '</strong>', '<br><br><strong>', '</strong>' ) . '<hr/>' .
										esc_html__( 'Formula', 'help-dialog' ) . ': ' . esc_html__( 'Click-through Rate = Views / FAQ clicks', 'help-dialog' ) . ' <a href="https://www.helpdialog.com/documentation/reach-analytics/#articleTOC_1" target="_blank">' . esc_html__( 'Learn More', 'help-dialog' ) . '</a>.',
					'results'       => isset( $results['most_helpful_faqs_by_pages'] ) ? $results['most_helpful_faqs_by_pages'] : [],
					'cells_wrap'    => $location_link_cells_wrap,
					'columns'       => array(
						'title'  => __( 'Top Pages', 'help-dialog' ),
						'views'  => __( 'Views', 'help-dialog' ),
						'clicks' => __( 'Clicks', 'help-dialog' ),
						'ctr'    => __( 'CTR', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--ctr',
					'title'         => __( 'Least Helpful Page FAQs', 'help-dialog' ),
					'tooltip_title' => __( 'Pages with Least Helpful FAQs', 'help-dialog' ),
					'tooltip_desc'  => esc_html__( 'These pages have poor FAQs that do not help users to better understand the topic or answer their questions.', 'help-dialog' ) . '<hr/>' .
										sprintf( esc_html__( '%sCTR%s measures the usage of the FAQs in relation to Help Dialog views. %sView:%s When the Help Dialog is opened, a view is recorded. %sClick:%s When FAQ is clicked, a click is recorded.', 'help-dialog' ),
										'<strong>', '</strong>', '<br><br><strong>', '</strong>', '<br><br><strong>', '</strong>' ) . '<hr/>' .
										esc_html__( 'Formula', 'help-dialog' ) . ': ' . esc_html__( 'Click-through Rate = Views / FAQ clicks', 'help-dialog' ) . ' <a href="https://www.helpdialog.com/documentation/reach-analytics/#articleTOC_1" target="_blank">' . esc_html__( 'Learn More', 'help-dialog' ) . '</a>.',
					'results'       => isset( $results['least_helpful_faqs_by_pages'] ) ? $results['least_helpful_faqs_by_pages'] : [],
					'cells_wrap'    => $location_link_cells_wrap,
					'columns'       => array(
						'title'  => __( 'Top Pages', 'help-dialog' ),
						'views'  => __( 'Views', 'help-dialog' ),
						'clicks' => __( 'Clicks', 'help-dialog' ),
						'ctr'    => __( 'CTR', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Most Popular Questions', 'help-dialog' ),
					'tooltip_title' => __( 'Which Questions Are Users Looking for the Most?', 'help-dialog' ),
					'tooltip_desc'  => __( 'These questions suggest that the pages are missing information or the topic is not well explained.', 'help-dialog' ),
					'results'       => isset( $results['most_popular_questions'] ) ? $results['most_popular_questions'] : [],
					'columns'       => array(
						'title' => __( 'Questions', 'help-dialog' ),
						'times' => __( 'Clicks', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Least Popular Questions', 'help-dialog' ),
					'tooltip_title' => __( 'Which Questions Are Users Looking for the Least?', 'help-dialog' ),
					'tooltip_desc'  => __( 'On these pages, the questions are either not relevant or are already convered by the page content.', 'help-dialog' ),
					'results'       => isset( $results['least_popular_questions'] ) ? $results['least_popular_questions'] : [],
					'columns'       => array(
						'title' => __( 'Questions', 'help-dialog' ),
						'times' => __( 'Clicks', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Most Popular Posts', 'help-dialog' ),
					'tooltip_title' => __( 'Which Posts Are Users Looking for the Most?', 'help-dialog' ),
					'tooltip_desc'  => __( 'These posts suggest that the FAQs and Articles are not sufficient and the pages are missing information or the topic is not well explained.', 'help-dialog' ) . '<hr/>' .
									   __( 'When a post is searched for and then clicked on, a click is recorded.', 'help-dialog' ),
					'empty_message' => '',
					'results'       => isset( $results['most_popular_posts'] ) ? $results['most_popular_posts'] : [],
					'columns'       => array(
						'title' => __( 'Posts', 'help-dialog' ),
						'times' => __( 'Clicks', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Least Popular Posts', 'help-dialog' ),
					'tooltip_title' => __( 'Which Posts Are Users Looking for the Least?', 'help-dialog' ),
					'tooltip_desc'  => __( 'On these pages, both FAQs and Articles are either not relevant or are already covered by the page content.', 'help-dialog' ),
					'empty_message' => '',
					'results'       => isset( $results['least_popular_posts'] ) ? $results['least_popular_posts'] : [],
					'columns'       => array(
						'title' => __( 'Posts', 'help-dialog' ),
						'times' => __( 'Clicks', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Most Popular KB Articles', 'help-dialog' ),
					'tooltip_title' => __( 'Which Articles Are Users Looking for the Most?', 'help-dialog' ),
					'tooltip_desc'  => __( 'These articles suggest that the FAQs and Articles are not sufficient and the pages are missing information or the topic is not well explained.', 'help-dialog' ) . '<hr/>' .
									   __( 'When an article is searched for and then clicked on, a click is recorded.', 'help-dialog' ),
					'empty_message' => $this->is_search_type_active( 'kb' ) === false ? __( 'KB search disabled', 'help-dialog' )  : '',
					'results'       => isset( $results['most_popular_articles'] ) ? $results['most_popular_articles'] : [],
					'columns'       => array(
						'title' => __( 'Articles', 'help-dialog' ),
						'times' => __( 'Clicks', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Least Popular KB Articles', 'help-dialog' ),
					'tooltip_title' => __( 'Which Articles Are Users Looking for the Least?', 'help-dialog' ),
					'tooltip_desc'  => __( 'On these pages, both FAQs and Articles are either not relevant or are already covered by the page content.', 'help-dialog' ),
					'empty_message' => $this->is_search_type_active( 'kb' ) === false ? __( 'KB search disabled', 'help-dialog' )  : '',
					'results'       => isset( $results['least_popular_articles'] ) ? $results['least_popular_articles'] : [],
					'columns'       => array(
						'title' => __( 'Articles', 'help-dialog' ),
						'times' => __( 'Clicks', 'help-dialog' ),
					),
				],
			],

			// SEARCH
			'search'    => [
				'tab_intro' => __( 'Search analytics list details about popular keywords and missing search results.', 'help-dialog' ),
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Searches Initiated', 'help-dialog' ),
					'tooltip_title' => __( 'On Which Pages Did Users Search the Most?', 'help-dialog' ),
					'tooltip_desc'  => __( 'Pages that have the most searches indicate that the shown FAQs in the Help Dialog are not sufficient or not relevant to help users with their questions.', 'help-dialog' ),
					'results'       => isset( $results['searches_count_by_page'] ) ? $results['searches_count_by_page'] : [],
					'cells_wrap'    => $location_link_cells_wrap,
					'columns'       => array(
						'title' => __( 'Top Pages', 'help-dialog' ),
						'times' => __( 'Searches', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--count',
					'title'         => __( 'Top Searched Keywords', 'help-dialog' ),
					'tooltip_title' => __( 'Keywords Users Search for The Most', 'help-dialog' ),
					'tooltip_desc'  => __( 'These keywords are popular and should have good matching content.', 'help-dialog' ),
					'results'       => isset( $results['all_frequent_searches'] ) ? $results['all_frequent_searches'] : [],
					'columns'       => array(
						'title' => __( 'Keywords', 'help-dialog' ),
						'times' => __( 'Count', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--count',
					'title'         => __( 'Search Results With No FAQs Found', 'help-dialog' ),
					'tooltip_title' => __( 'Keywords Users Searched For That Did Not Have Matching FAQs.', 'help-dialog' ),
					'tooltip_desc'  => __( 'These keywords have no matching content for user to get answers.', 'help-dialog' ),
					'results'       => isset( $results['not_found_frequent_faqs_searches'] ) ? $results['not_found_frequent_faqs_searches'] : [],
					'columns'       => array(
						'title' => __( 'Keywords', 'help-dialog' ),
						'times' => __( 'Count', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--count',
					'title'         => __( 'Search Results With No Articles Found', 'help-dialog' ),
					'tooltip_title' => __( 'Keywords Users Searched For That Did Not Have Matching Articles.', 'help-dialog' ),
					'tooltip_desc'  => __( 'These keywords have no matching content for user to get answers.', 'help-dialog' ),
                    'empty_message' => $this->is_search_type_active( 'kb' ) === false ? __( 'KB search disabled', 'help-dialog' )  : '',
					'results'       => isset( $results['not_found_frequent_articles_searches'] ) ? $results['not_found_frequent_articles_searches'] : [],
					'columns'       => array(
						'title' => __( 'Keywords', 'help-dialog' ),
						'times' => __( 'Count', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--count',
					'title'         => __( 'Search Results With No Posts Found', 'help-dialog' ),
					'tooltip_title' => __( 'Keywords Users Searched For That Did Not Have Matching Posts.', 'help-dialog' ),
					'tooltip_desc'  => __( 'These keywords have no matching content for user to get answers.', 'help-dialog' ),
					'empty_message' => $this->is_search_type_active( 'posts' ) === false ? __( 'Posts search disabled', 'help-dialog' )  : '',
					'results'       => isset( $results['not_found_frequent_posts_searches'] ) ? $results['not_found_frequent_posts_searches'] : [],
					'columns'       => array(
						'title' => __( 'Keywords', 'help-dialog' ),
						'times' => __( 'Count', 'help-dialog' ),
					),
				],
			],

            // Per Page Analytics
			'per_page'    => [
			    'tab_intro' => __( 'Shown are analytics about a specific page.', 'help-dialog' ),
				[
					'type'          => 'multiple-col-2',
					'css_class'     => 'ephd-ap-results--multiple-col-2',
					'title'         => __( 'Help Dialog CTR', 'help-dialog' ),
					'tooltip_title' => __( 'Number of Clicks to Open the Help Dialog.', 'help-dialog' ),
					'tooltip_desc'  => sprintf( esc_html__( '%sCTR%s measures the usage of the Help Dialog in relation to page views. %sView:%s When a page is loaded for longer than 5 seconds, a view is recorded. %sClick:%s When the Help Dialog Launcher Icon is clicked, a click is recorded.', 'help-dialog' ),
										'<strong>', '</strong>', '<br><br><strong>', '</strong>', '<br><br><strong>', '</strong>' ) . '<hr/>' .
										esc_html__( 'Formula', 'help-dialog' ) . ': ' . esc_html__( 'Click-through Rate = Views / launcher clicks', 'help-dialog' ) . ' <a href="https://www.helpdialog.com/documentation/reach-analytics/#articleTOC_1" target="_blank">' . esc_html__( 'Learn More', 'help-dialog' ) . '</a>.',
					'results'       => isset( $results['click_through_rate_by_location'] ) ? $results['click_through_rate_by_location'] : [],
					'columns'       => array(
						'views'  => __( 'Total Views', 'help-dialog' ),
						'clicks' => __( 'Total Clicks', 'help-dialog' ),
						'ctr'    => __( 'CTR', 'help-dialog' ),
					)
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Most Popular Questions', 'help-dialog' ),
					'tooltip_title' => __( 'Which Questions Are Users Looking for the Most?', 'help-dialog' ),
					'tooltip_desc'  => __( 'These questions suggest that the pages are missing information or the topic is not well explained.', 'help-dialog' ),
					'results'       => isset( $results['most_popular_questions_by_location'] ) ? $results['most_popular_questions_by_location'] : [],
					'columns'       => array(
						'title' => __( 'Questions', 'help-dialog' ),
						'times' => __( 'Clicks', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Most Popular Articles', 'help-dialog' ),
					'tooltip_title' => __( 'Which Articles Are Users Looking for the Most?', 'help-dialog' ),
					'tooltip_desc'  => __( 'These articles suggest that the FAQs and Articles are not sufficient and the pages are missing information or the topic is not well explained.', 'help-dialog' ),
					'empty_message' => $this->is_search_type_active( 'kb' ) === false ? __( 'KB search disabled', 'help-dialog' )  : '',
					'results'       => isset( $results['most_popular_articles_by_location'] ) ? $results['most_popular_articles_by_location'] : [],
					'columns'       => array(
						'title' => __( 'Articles', 'help-dialog' ),
						'times' => __( 'Clicks', 'help-dialog' ),
					),
				],

				// Searches
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--count',
					'box_intro'     => __( 'Searches', 'help-dialog' ),
					'title'         => __( 'Top Searched Keywords', 'help-dialog' ),
					'tooltip_title' => __( 'Keywords Users Search for The Most', 'help-dialog' ),
					'tooltip_desc'  => __( 'These keywords are popular and should have good matching content.', 'help-dialog' ),
					'results'       => isset( $results['all_frequent_searches_by_location'] ) ? $results['all_frequent_searches_by_location'] : [],
					'columns'       => array(
						'title' => __( 'Keywords', 'help-dialog' ),
						'times' => __( 'Count', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--count',
					'title'         => __( 'Searched Keywords Without Search Results', 'help-dialog' ),
					'tooltip_title' => __( 'Keywords Users Searched For That Did Not Have Matching Content', 'help-dialog' ),
					'tooltip_desc'  => __( 'These keywords have no matching content for a user to get answers.', 'help-dialog' ),
					'results'       => isset( $results['not_found_frequent_searches_by_location'] ) ? $results['not_found_frequent_searches_by_location'] : [],
					'columns'       => array(
						'title' => __( 'Keywords', 'help-dialog' ),
						'times' => __( 'Count', 'help-dialog' ),
					),
				],

                // Incomplete Searches
                /* TODO FUTURE
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'box_intro'     => __( 'Incomplete Searches', 'help-dialog' ),
					'title'         => __( 'Keywords With No Matches', 'help-dialog' ),
					'tooltip_title' => __( 'TODO', 'help-dialog' ),
					'tooltip_desc'  => __( 'TODO', 'help-dialog' ),
					'results'       => [], // TODO calculate results
					'columns'       => array(
						'title'       => __( 'Keywords', 'help-dialog' ),
						'times'       => __( 'Count', 'help-dialog' ),
						'hd_matching' => __( '# of matching Questions', 'help-dialog' ),
						'kb_matching' => __( '# of matching Articles', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Searched Questions Not in Initial FAQs', 'help-dialog' ),
					'tooltip_title' => __( 'TODO', 'help-dialog' ),
					'tooltip_desc'  => __( 'TODO', 'help-dialog' ),
					'results'       => [], // TODO calculate results
					'columns'       => array(
						'title'       => __( 'Questions', 'help-dialog' ),
						'times'       => __( 'Clicks', 'help-dialog' ),
					),
				],
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Top Keywords With Unused Search Results', 'help-dialog' ),
					'tooltip_title' => __( 'TODO', 'help-dialog' ),
					'tooltip_desc'  => __( 'TODO', 'help-dialog' ),
					'results'       => [], // TODO calculate results
					'columns'       => array(
						'title'       => __( 'Keywords', 'help-dialog' ),
						'times'       => __( 'Count', 'help-dialog' ),
					),
				],
                */

                // Contact Form
				[
					'type'          => 'multiple-col-2',
					'css_class'     => 'ephd-ap-results--multiple-col-2',
					'box_intro'     => __( 'Contact Form', 'help-dialog' ),
					'title'         => __( 'Contact Form CTR', 'help-dialog' ),
					'tooltip_title' => __( 'Click Through Rate', 'help-dialog' ),
					'tooltip_desc'  => esc_html__( 'Rate of submissions versus views by a user on the Contact form.', 'help-dialog' ) . '<hr/>' .
										sprintf( esc_html__( '%sCTR%s measures the usage of the Contact form in relation to Contact Tab views. %sView:%s When Contact tab is opened, a view is recorded. %sSubmissions:%s When the Contact form is submitted, a submissions is recorded.', 'help-dialog' ),
										'<strong>', '</strong>', '<br><br><strong>', '</strong>', '<br><br><strong>', '</strong>' ) . '<hr/>' .
										esc_html__( 'Formula', 'help-dialog' ) . ': ' . esc_html__( 'Click-through Rate = Views / Submissions', 'help-dialog' ) . ' <a href="https://www.helpdialog.com/documentation/reach-analytics/#articleTOC_1" target="_blank">' . esc_html__( 'Learn More', 'help-dialog' ) . '</a>.',
					'results'       => isset( $results['contact_form_ctr_by_location'] ) ? $results['contact_form_ctr_by_location'] : [],
					'columns'       => array(
						'views'  => __( 'Views', 'help-dialog' ),
						'clicks' => __( 'Submissions', 'help-dialog' ),
						'ctr'    => __( 'CTR', 'help-dialog' ),
					),
				],
                /* TODO FUTURE
				[
					'type'          => 'table',
					'css_class'     => 'ephd-ap-results--views',
					'title'         => __( 'Contact Form Submissions', 'help-dialog' ),
					'tooltip_title' => __( 'TODO', 'help-dialog' ),
					'tooltip_desc'  => __( 'TODO', 'help-dialog' ),
					'results'       => [], // TODO calculate results
					'columns'       => array(
						'date'    => __( 'Date', 'help-dialog' ),
						'subject' => __( 'subject', 'help-dialog' ),
						'link'    => __( 'Link', 'help-dialog' ),
					),
				],
                */
		    ],
		];
	}

	/**
	 * Display analytics single value results
	 *
	 * @param $args
	 * @return false|string
	 */
	private static function get_single_results_html( $args ) {

		$args['is_pro'] = isset( $args['is_pro'] ) ? $args['is_pro'] : true;

        ob_start(); ?>
        <div class="ephd-ap-widget">
            <div class="ephd-ap-widget-content">    <?php
				if ( $args['is_pro'] && ! EPHD_Utilities::is_help_dialog_pro_enabled() ) {    ?>
                    <div class="ephd-ap-widget-pro-message">
                        <div class="ephd-ap-widget-pro-message__icon ephdfa ephdfa-star"></div>
                        <div class="ephd-ap-widget-pro-message__text">This is a PRO Feature</div>
                        <div class="ephd-ap-widget-pro-message__link"><a href="https://www.helpdialog.com/help-dialog-pro/" target="_blank"><?php esc_html_e( 'Upgrade', 'help-dialog' ); ?></a></div>
                    </div>  <?php
				} else {
                    if ( empty( $args['results'] ) ) {    ?>
                        <div class="ephd-ap-widget-nodata-message">
                            <img class="ephd-hd-no-results__img" alt="<?php esc_attr_e( 'No FAQs Defined', 'help-dialog' ); ?>" src="<?php echo esc_url( Echo_Help_Dialog::$plugin_url . 'img/no-faqs-defined.jpeg' ); ?>" />
                            <div class="ephd-hd-no-results__title"><?php echo empty( $args['empty_message'] ) ? esc_html__( 'No data recorded yet for the selected time frame', 'help-dialog' ) : esc_html( $args['empty_message'] ); ?></div>
                        </div>  <?php
                    } else {    ?>
	                    <div class="ephd-ap-widget-count">  <?php
		                    echo esc_html( $args['results'] );  ?>
	                    </div>  <?php
                    }
				}   ?>
            </div>
        </div>  <?php

		return ob_get_clean();
	}

	/**
	 * Display analytics multiple values results
	 *
	 * @param $args
	 * @return false|string
	 */
	private static function get_multiple_results_html( $args ) {
		$args['is_pro'] = isset( $args['is_pro'] ) ? $args['is_pro'] : true;
		ob_start(); ?>

        <div class="ephd-ap-widget">
            <div class="ephd-ap-widget-content">    <?php
				if ( $args['is_pro'] && ! EPHD_Utilities::is_help_dialog_pro_enabled() ) {    ?>
                    <div class="ephd-ap-widget-pro-message">
                        <div class="ephd-ap-widget-pro-message__icon ephdfa ephdfa-star"></div>
                        <div class="ephd-ap-widget-pro-message__text">This is a PRO Feature</div>
                        <div class="ephd-ap-widget-pro-message__link"><a href="https://www.helpdialog.com/help-dialog-pro" target="_blank"><?php esc_html_e( 'Upgrade', 'help-dialog' ); ?></a></div>
                    </div>  <?php
				} else {
                    if ( ! empty( $args['results'] ) && ! empty( $args['columns'] ) ) {
	                    $main_cell_key = ( $args['type'] == 'multiple-col-2' ) ? array_key_first( $args['results'] ) : '';   ?>
                        <div class="ephd-ap-widget-multi-cells">
                            <div class="ephd-ap-widget-multi-cells__col">   <?php
		                        foreach ( $args['results'] as $cell_key => $result ) {
			                        if ( $cell_key === $main_cell_key ) {
				                        continue;
			                        }   ?>
                                    <div class="ephd-ap-widget-multi-cells__cell">  <?php
                                        if ( ! empty( $args['icons'][$cell_key] ) ) {   ?>
                                            <div class="ephd-ap-widget-multi-cells__icon">
                                                <span class="<?php echo esc_html( $args['icons'][$cell_key] ); ?>"></span>
                                            </div>  <?php
                                        }   ?>
                                        <div class="ephd-ap-widget-multi-cells__title"> <?php
                                            echo esc_html( $args['columns'][$cell_key] ); ?>
                                        </div>
                                        <div class="ephd-ap-widget-multi-cells__value"> <?php
                                            echo esc_html( $result );   ?>
                                        </div>
                                    </div>    <?php
    		                    }   ?>
                            </div>  <?php
                            if ( ! empty( $main_cell_key ) ) {  ?>
                                <div class="ephd-ap-widget-multi-cells__col">
                                    <div class="ephd-ap-widget-multi-cells__cell">
                                        <div class="ephd-ap-widget-multi-cells__title"> <?php
	                                        echo esc_html( $args['columns'][$main_cell_key] ); ?>
                                        </div>
                                        <div class="ephd-ap-widget-multi-cells__value"> <?php
	                                        echo esc_html( $args['results'][$main_cell_key] . '%' );   ?>
                                        </div>
                                    </div>
                                </div>  <?php
                            }   ?>
                        </div>  <?php
                    } else {    ?>
                        <div class="ephd-ap-widget-nodata-message">
                            <img class="ephd-hd-no-results__img" alt="<?php esc_attr_e( 'No FAQs Defined', 'help-dialog' ); ?>" src="<?php echo esc_url( Echo_Help_Dialog::$plugin_url . 'img/no-faqs-defined.jpeg' ); ?>" />
                            <div class="ephd-hd-no-results__title"><?php echo empty( $args['empty_message'] ) ? esc_html__( 'No data recorded yet for the selected time frame', 'help-dialog' ) : esc_html( $args['empty_message'] ); ?></div>
                        </div>  <?php
                    }
				}   ?>
            </div>
        </div>  <?php

		return ob_get_clean();
	}

	/**
	 * Display analytics table results
	 *
	 * @param $args
	 * @return false|string
	 */
	private static function get_table_results_html( $args ) {

		$args['is_pro'] = isset( $args['is_pro'] ) ? $args['is_pro'] : true;
		$items_limit = empty( $args['limit'] ) ? 6 : $args['limit'];
		$list_of_items = empty( $args['results'] ) ? [] : $args['results'];
		$items_count = count( $list_of_items );

        // Add cell value wrapper with data attributes
        if ( ! empty( $args['cells_wrap'] ) && ! empty( $items_count ) ) {
	        $list_of_items = self::add_wrapper_to_table_cell( $list_of_items, $args['cells_wrap'] );
        }

        // Add '%' to ctr result
        foreach ( $list_of_items as $key => $row ) {
            if ( isset( $row->ctr ) ) {
	            $row->ctr .= '%';
            }
        }

		$short_list_of_items = array_slice( $list_of_items, 0, $items_limit ); // limit list of items

		$handler = new EPHD_HTML_Forms();

		ob_start(); ?>
        <div class="ephd-ap-widget ephd-ap-widget-items-list">
            <div class="ephd-ap-widget-content"> <?php
	            if ( $args['is_pro'] && ! EPHD_Utilities::is_help_dialog_pro_enabled() ) {    ?>
                    <div class="ephd-ap-widget-pro-message">
                        <div class="ephd-ap-widget-pro-message__icon ephdfa ephdfa-star"></div>
                        <div class="ephd-ap-widget-pro-message__text">This is a PRO Feature</div>
                        <div class="ephd-ap-widget-pro-message__link"><a href="https://www.helpdialog.com/help-dialog-pro" target="_blank"><?php esc_html_e( 'Upgrade', 'help-dialog' ); ?></a></div>
                    </div>  <?php
	            } else {
                    if ( empty( $list_of_items ) ) {    ?>
                        <div class="ephd-ap-widget-nodata-message">
                            <img class="ephd-hd-no-results__img" alt="<?php esc_attr_e( 'No FAQs Defined', 'help-dialog' ); ?>" src="<?php echo esc_url( Echo_Help_Dialog::$plugin_url . 'img/no-faqs-defined.jpeg' ); ?>" />
                            <div class="ephd-hd-no-results__title"><?php echo empty( $args['empty_message'] ) ? esc_html__( 'No data recorded yet for the selected time frame', 'help-dialog' ) : esc_html( $args['empty_message'] ); ?></div>
                        </div>  <?php
                    } else {
	                    echo $handler->get_html_table( $short_list_of_items, $items_count, '', $args['columns'], [], [], 'ephd-admin__open-details-popup' );
	                    // add hidden popup if items_count more than items_limit
	                    if ( $items_count > $items_limit ) {
		                    $full_table = $handler->get_html_table( $list_of_items, $items_count, '', $args['columns'], [], [], '' );
		                    self::analytics_details_popup( $args['title'], $full_table );
	                    }
                    }
	            }   ?>
            </div>
        </div>  <?php

		return ob_get_clean();
	}

	/**
	 * Add wrapper with class and data attributes to table cell value
	 *
	 * @param $list_of_items
	 * @param $cells_wrap_data - example: array( 'pages' => ['class' => 'ephd-class-name', 'data'  => ['page_id']] ). See get_analytics_tab_boxes_list_config: cells_wrap
	 *
	 * @return array
	 */
	private static function add_wrapper_to_table_cell( $list_of_items, $cells_wrap_data=[] ) {

        if ( empty( $cells_wrap_data ) ) {
            return $list_of_items;
        }

		foreach ( $list_of_items as $item ) {

            foreach ( $cells_wrap_data as $cell_name => $cell_attrs ) {

                // is cell name exist in $list_of_items
                if ( ! isset( $item->{$cell_name} ) ) {
                    continue;
                }

                // set defaults for cell html attributes
	            $cell_attrs['data'] = isset( $cell_attrs['data'] ) ? $cell_attrs['data'] : [];
	            $cell_attrs['class'] = isset( $cell_attrs['class'] ) ? $cell_attrs['class'] : '';

	            $data_html = '';

                foreach ( $cell_attrs['data'] as $data_name ) {

	                // is data attr name exist in $list_of_items
	                if ( ! isset( $item->{$data_name} ) ) {
		                continue;
	                }
	                $data_html .= 'data-' . $data_name .'="' . esc_attr( $item->{$data_name} ) . '" ';
                }

                $item->{$cell_name} = '<span class="' . esc_attr( $cell_attrs['class'] ) . '" ' . $data_html . '>' . wp_strip_all_tags( $item->{$cell_name} ) . '</span>';
            }
		}

		return $list_of_items;
	}

	/**
     * Check is search type active in any of the widgets
     *
	 * @param $type
	 *
	 * @return bool|null
	 */
	private function is_search_type_active( $type ) {

		if ( ! in_array( $type, ['kb', 'posts'] ) ) {
			return null;
		}

		$is_search_type_active = false;
		foreach ( $this->widgets_config as $widget ) {

            // is search method exist
            if ( ! isset( $widget["search_{$type}"] ) ) {
                continue;
            }

			// is published widget
			if ( $widget['widget_status'] != 'published' ) {
				continue;
			}

			if ( $widget["search_{$type}"] != 'off') {
				$is_search_type_active = true;
				break;
			}
		}
		return $is_search_type_active;
	}

	/**
	 * Analytics Details Popup - user can only click 'OK' button
	 *
	 * @param string $title
	 * @param string $body
	 * @param string $accept_label
	 */
	private static function analytics_details_popup( $title='', $body='', $accept_label='' ) { ?>

        <div class="ephd-ap-details-popup">

            <!---- Header ---->
            <div class="ephd-ap-details-popup__header">
                <h4><?php echo esc_html( $title ); ?></h4>
            </div>

            <!---- Body ---->
            <div class="ephd-ap-details-popup__body">
				<?php echo EPHD_Utilities::admin_ui_wp_kses( $body ); ?>
            </div>

            <!---- Footer ---->
            <div class="ephd-ap-details-popup__footer">
                <div class="ephd-ap-details-popup__footer__accept">
					<span class="ephd-ap-details-popup__accept-btn">
						<?php echo empty( $accept_label ) ? esc_html__( 'OK', 'help-dialog' ) : esc_html( $accept_label ); ?>
					</span>
                </div>
            </div>

        </div>

        <div class="ephd-ap-details-popup__overlay"></div>      <?php
	}

	/**
     * Show warning notification box if error logs exist
	 */
    private static function error_log_notification() {

	    $error_logs_count = self::get_error_logs_count();
        if ( empty( $error_logs_count ) ) {
            return;
        }  ?>

        <div id="ephd-admin-page-wrap" class="ephd-admin-page-wrap--config-error"> <?php
	        EPHD_HTML_Forms::notification_box_top(
		        array(
			        'type'  => 'warning',
			        'title' => __( 'Error Logs', 'help-dialog' ),
			        'desc'  => __( "There are {$error_logs_count} error logs in the last 7 days", 'help-dialog' )
		        )
	        );  ?>
        </div>  <?php
    }

	/**
     * Get count of error logs
     *
	 * @param $days
	 *
	 * @return false|int
	 */
    private static function get_error_logs_count( $days = 7 ) {

	    $logs = EPHD_Logging::get_logs();
        if ( empty( $logs ) ) {
            return false;
        }
        // Get all logs
        if ( empty( $days ) ) {
            return count($logs);
        }

	    // Get logs for the last x days
	    $logs_count = 0;
        foreach ( $logs as $log ) {
	        if ( strtotime( $log['date'] ) >= strtotime( "now - {$days} days" ) ) {
		        $logs_count++;
	        }
        }

        return $logs_count;
    }

	/**
	 * Get configuration array for regular views
	 *
	 * @return array
	 */
	private function get_regular_views_config() {

		$core_views = [];

		$boxes_list_config = $this->get_analytics_tab_boxes_list_config();

		/**
		 * View: Overview
		 */
		$core_views[] = EPHD_Utilities::is_help_dialog_pro_enabled() ? [] : [
			// Shared
			'active' => true,
			'list_key' => 'hd-stats-overview',

			// Top Panel Item
			'label_text' => __( 'Overview', 'help-dialog' ),
			'icon_class' => 'ep_font_icon_data_report',

			'list_top_actions_html' => '<div></div>',

			// Boxes List
			'boxes_list' => self::get_analytics_tab_boxes_list( $boxes_list_config['overview'] ),

		];
		/**
		 * View: Reach
		 */
		$core_views[] = [
			// Shared
			'active' => true,
			'list_key' => 'hd-stats-reach',

			// Top Panel Item
			'label_text' => __( 'Reach', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-users',

			'list_top_actions_html' => $this->get_top_actions_html( ['timeframe'] ),

			// Boxes List
			'boxes_list' => self::get_analytics_tab_boxes_list( $boxes_list_config['reach'] ),
		];

		/**
		 * View: Content
		 */
		$core_views[] = [
			// Shared
			'active' => false,
			'list_key' => 'hd-stats-content',

			// Top Panel Item
			'label_text' => __( 'Content', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-th-large',

			'list_top_actions_html' => $this->get_top_actions_html( ['timeframe'] ),

			// Boxes List
			'boxes_list' => self::get_analytics_tab_boxes_list( $boxes_list_config['content'] ),
		];
		/**
		 * View: Search
		 */
		$core_views[] = [
			// Shared
			'active' => false,
			'list_key' => 'hd-stats-search',

			// Top Panel Item
			'label_text' => __( 'Search', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-search',

			'list_top_actions_html' => $this->get_top_actions_html( ['timeframe'] ),

			// Boxes List
			'boxes_list' => self::get_analytics_tab_boxes_list( $boxes_list_config['search'] ),
		];

		/**
		 * View: Per Page
		 */
        // Show boxes if location selected

        if ( ! empty( $this->filters_options['location'][$this->filters['location']]['times'] ) ) {
	        $per_page_boxes_list = self::get_analytics_tab_boxes_list( $boxes_list_config['per_page'] );

        // Show notification if no location selected or if there are no pages in the location list
        } else {
	        $notification_desc = $this->filters['location'] == ''
                ? __( 'Please select a page', 'help-dialog' )
                : __( 'No analytics data were found for the chosen time interval', 'help-dialog' );

	        $per_page_boxes_list = array( [
                'class' => 'ephd-ap-notification-box',
                'html'  => EPHD_HTML_Forms::notification_box_middle( array(
                    'type' => 'error-no-icon',
                    'desc' => $notification_desc,
                ), true ),
            ] );
        }

		$core_views[] = [
			// Shared
			'active' => false,
			'list_key' => 'hd-stats-per-page',

			// Top Panel Item
			'label_text' => __( 'Per Page', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-file-text',

			'list_top_actions_html' => $this->get_top_actions_html( ['timeframe', 'location'] ),

			// Boxes List
			'boxes_list' => $per_page_boxes_list,
		];

		/**
		 * View: Settings
		 */
		$core_views[] = array(

			// Shared
			'active' => true,
			'list_key' => 'hd-stats-settings',

			// Top Panel Item
			'label_text' => __( 'Settings', 'help-dialog' ),
			'icon_class' => 'ephdfa ephdfa-cogs',

			// Boxes List
			'list_top_actions_html' => self::settings_tab_actions_row(),
			'boxes_list' => array(

				// Box: Global Analytics Configuration
				/* array(
					'title' => __( 'Analytics Configuration', 'help-dialog' ),
					'html'  => self::settings_tab_global_configuration_box(),
				), */

				// Box: Excluded User Roles
				array(
					'title' => $this->global_specs['analytic_excluded_roles']['label'],
					'html'  => self::settings_tab_excluded_user_roles_box( $this->global_config['analytic_excluded_roles'] ),
				),
			),
		);

		return $core_views;
	}
}
