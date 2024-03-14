<?php
namespace Codexpert\Restrict_Elementor_Widgets;

use Codexpert\Plugin\Base;
use Codexpert\Plugin\Metabox;
use Elementor\Plugin as Elementor_Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\Documents_Manager;
use Codexpert\Restrict_Elementor_Widgets\Controls\Control_Time;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Admin
 * @author Codexpert <hello@codexpert.io>
 */
class Admin extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->server	= $this->plugin['server'];
		$this->version	= $this->plugin['Version'];
	}

	/**
	 * Internationalization
	 */
	public function i18n() {
		load_plugin_textdomain( 'restrict-elementor-widgets', false, REW_DIR . '/languages/' );
	}

	public function install() {
		/**
		 * Schedule an event to sync help docs
		 */
		if ( !wp_next_scheduled ( 'restrict-elementor-widgets_daily' )) {
		    wp_schedule_event( time(), 'daily', 'restrict-elementor-widgets_daily' );
		}

		$this->sync_docs();
	}

	/**
	 * Daily events
	 */
	public function sync_docs() {
		/**
		 * Sync blog posts from https://codexpert.io
		 *
		 * @since 1.0
		 */
	    $_posts = 'https://codexpert.io/wp-json/wp/v2/posts/';
	    if( !is_wp_error( $_posts_data = wp_remote_get( $_posts ) ) ) {
	        update_option( 'codexpert-blog-json', json_decode( $_posts_data['body'], true ) );
	    }

		/**
		 * Sync docs from https://help.codexpert.io
		 *
		 * @since 1.0
		 */
	    $_docs = "https://help.codexpert.io/wp-json/wp/v2/docs/?parent={$this->plugin['doc_id']}&per_page=20";
	    if( !is_wp_error( $_docs_data = wp_remote_get( $_docs ) ) ) {
	        update_option( 'restrict-elementor-widgets-docs-json', json_decode( $_docs_data['body'], true ) );
	    }
	}

	/**
	 * Adds a widget in /wp-admin/index.php page
	 *
	 * @since 1.0
	 */
	public function dashboard_widget() {
		wp_add_dashboard_widget( 'cx-overview', __( 'Latest From Our Blog', 'cx-plugin' ), [ $this, 'callback_dashboard_widget' ] );

		// Move our widget to top.
		global $wp_meta_boxes;

		$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		$ours = [
			'cx-overview' => $dashboard['cx-overview'],
		];

		$wp_meta_boxes['dashboard']['normal']['core'] = array_merge( $ours, $dashboard );
	}

	/**
	 * Call back for dashboard widget in /wp-admin/
	 *
	 * @see dashboard_widget()
	 *
	 * @since 1.0
	 */
	public function callback_dashboard_widget() {
		$posts = get_option( 'codexpert-blog-json', [] );
		
		$utm = [ 'utm_source' => 'client-site', 'utm_medium' => 'plugin', 'utm_campaign' => 'dashboard-blog' ];

		if( count( $posts ) > 0 ) :
		
		$posts = array_slice( $posts, 0, 5 );

		echo '<ul id="cx-posts-wrapper">';
		
		foreach ( $posts as $post ) {

			$post_link = add_query_arg( $utm, $post['link'] );
			echo "
			<li>
				<a href='{$post_link}' target='_blank'><span aria-hidden='true' class='cx-post-title-icon dashicons dashicons-external'></span> <span class='cx-post-title'>{$post['title']['rendered']}</span></a>
				" . wpautop( wp_trim_words( $post['content']['rendered'], 10 ) ) . "
			</li>";
		}
		
		echo '</ul>';
		endif; // count( $posts ) > 0

		$_links = apply_filters( 'cx-overview_links', [
			'products'	=> [
				'url'		=> add_query_arg( $utm, 'https://codexpert.io/blog/' ),
				'label'		=> __( 'Our Blog', 'cx-plugin' ),
				'target'	=> '_blank',
			],
			'hire'	=> [
				'url'		=> add_query_arg( $utm, 'https://codexpert.io/hire/' ),
				'label'		=> __( 'Hire Us', 'cx-plugin' ),
				'target'	=> '_blank',
			],
		] );

		$footer_links = [];
		foreach ( $_links as $id => $link ) {
			$_has_icon = ( $link['target'] == '_blank' ) ? '<span class="screen-reader-text">' . __( '(opens in a new tab)', 'cx-plugin' ) . '</span><span aria-hidden="true" class="dashicons dashicons-external"></span>' : '';

			$footer_links[] = "<a href='{$link['url']}' target='{$link['target']}'>{$link['label']}{$_has_icon}</a>";
		}

		echo '<p class="community-events-footer">' . implode( ' | ', $footer_links ) . '</p>';
	}
	
	/**
	 * Enqueue JavaScripts and stylesheets
	 */
	public function enqueue_scripts() {
		$min = defined( 'REW_DEBUG' ) && REW_DEBUG ? '' : '.min';
		
		wp_enqueue_style( $this->slug, plugins_url( "/assets/css/admin{$min}.css", REW ), '', $this->version, 'all' );

		wp_enqueue_script( $this->slug, plugins_url( "/assets/js/admin{$min}.js", REW ), [ 'jquery' ], $this->version, true );
	}

	public function action_links( $links ) {
		$this->admin_url = admin_url( 'admin.php' );

		$new_links = [
			'settings'	=> sprintf( '<a href="%1$s">' . __( 'Settings', 'restrict-elementor-widgets' ) . '</a>', add_query_arg( 'page', $this->slug, $this->admin_url ) )
		];
		
		return array_merge( $new_links, $links );
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		
		$utm = [ 'utm_source' => 'dashboard', 'utm_medium' => 'plugins', 'utm_campaign' => 'help-btn' ];
		$support_url = rew_has_pro() ? 'https://help.codexpert.io/?utm_source=dashboard&utm_medium=settings&utm_campaign=helpbtn' : 'https://wordpress.org/support/plugin/restrict-elementor-widgets/';

		if ( $this->plugin['basename'] === $plugin_file ) {
			$plugin_meta['help'] = '<a href="' . $support_url . '" target="_blank" class="cx-help">' . __( 'Help', 'restrict-elementor-widgets' ) . '</a>';
		}

		return $plugin_meta;
	}

	public function footer_text( $text ) {
		if( get_current_screen()->parent_base != $this->slug ) return $text;

		return sprintf( __( 'If you like <strong>%1$s</strong>, please <a href="%2$s" target="_blank">leave us a %3$s rating</a> on WordPress.org! It\'d motivate and inspire us to make the plugin even better!', 'restrict-elementor-widgets' ), $this->name, "https://wordpress.org/support/plugin/{$this->slug}/reviews/?filter=5#new-post", '⭐⭐⭐⭐⭐' );
	}

	/**
	 * Registers additional controls for widgets
	 *
	 * @since 1.0
	 */
	public function register_controls() {
		
		include_once( REW_DIR . '/controls/time.php' );
        $time_input = new Control_Time;

        Elementor_Plugin::instance()->controls_manager->register_control( 'time', new Control_Time );
	}

	/**
	 * Adds a new control in the editor
	 *
	 * @since 1.1
	 */
	public function register_control_section( $element ) {
		

		$element->start_controls_section(
			'rew_control_section',
			[
				'tab' => Controls_Manager::TAB_ADVANCED,
				'label' => __( 'Restrict Content', 'restrict-elementor-widgets' ),
			]
		);
		$element->end_controls_section();
	}

	public function control_actions( $element, $args ) {

		$element->add_control(
			'rew_enable_restriction',
			[
				'label'        => __( 'Enable Restriction', 'restrict-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'restrict-elementor-widgets' ),
				'label_off'    => __( 'No', 'restrict-elementor-widgets' ),
				'return_value' => 'yes',
			]
		);

		$element->add_control(
			'rew_show_content_to',
			[
				'type'			=> Controls_Manager::SELECT2,
				'label'			=> __( 'Show Content to', 'restrict-elementor-widgets' ),
				'description'	=> __( 'Visibility rules. Multiple rules can be set.', 'restrict-elementor-widgets' ),
				'options' 		=> rew_show_content_to(),
				'multiple' 		=> true,
				'label_block' 	=> true,
				'condition'   	=> [
					'rew_enable_restriction'  => 'yes'
				],
			]
		);

		$element->add_control(
			'rew_show_content_to_roles',
			[
				'type'			=> Controls_Manager::SELECT2,
				'label'			=> __( 'Select User Role', 'restrict-elementor-widgets' ),
				'description'	=> __( 'Select roles you want to show the content to', 'restrict-elementor-widgets' ),
				'options' 		=> rew_show_content_to_roles(),
				'separator' 	=> 'before',
				'multiple' 		=> true,
				'label_block' 	=> true,
				'condition'   	=> [
					'rew_enable_restriction'  	=> 'yes',
					'rew_show_content_to'  		=> 'role-wise'
				],
			]
		);

		$element->add_control(
			'rew_user_ids',
			[
				'type'			=> Controls_Manager::TEXT,
				'label'			=> __( 'User ID\'s', 'restrict-elementor-widgets' ),
				'description'	=> __( 'Input user IDs you want to show the content to. Separate each ID with comma delimeter.', 'restrict-elementor-widgets' ),
				'separator' 	=> 'before',
				'condition'   	=> [
					'rew_enable_restriction'  	=> 'yes',
					'rew_show_content_to'  		=> 'user-wise'
				],
			]
		);

		$element->add_control(
			'rew_date_time_type',
			[
				'type'			=> Controls_Manager::SELECT,
				'label'			=> __( 'Select Time Type', 'restrict-elementor-widgets' ),
				'description'	=> __( 'Show an alternative of your main content', 'restrict-elementor-widgets' ),
				'options' => [
					'daily' => __( 'Everyday', 'restrict-elementor-widgets' ),
					'date'	=> __( 'Specific Date', 'restrict-elementor-widgets' ),
					'day' 	=> __( 'Specific Day', 'restrict-elementor-widgets' ),
				],
				'separator' 	=> 'before',				
				'condition'   	=> [
					'rew_enable_restriction'  	=> 'yes',
					'rew_show_content_to'  		=> 'date-time'
				],
			]
		);

		$element->add_control(
			'rew_day_list',
			[
				'type'			=> Controls_Manager::SELECT2,
				'label'			=> __( 'Select Days', 'restrict-elementor-widgets' ),
				'description'	=> __( 'Select the days you want to show the content on', 'restrict-elementor-widgets' ),
				'options' 		=> [
					'sat' => __( 'Saturday', 'restrict-elementor-widgets' ),
					'sun' => __( 'Sunday', 'restrict-elementor-widgets' ),
					'mon' => __( 'Monday', 'restrict-elementor-widgets' ),
					'tue' => __( 'Tuesday', 'restrict-elementor-widgets' ),
					'wed' => __( 'Wednesday', 'restrict-elementor-widgets' ),
					'thu' => __( 'Thursday', 'restrict-elementor-widgets' ),
					'fri' => __( 'Friday', 'restrict-elementor-widgets' )
				],
				'multiple' 		=> true,
				'label_block' 	=> true,				
				'condition'   	=> [
					'rew_enable_restriction'  	=> 'yes',
					'rew_show_content_to'  		=> 'date-time',
					'rew_date_time_type'  		=> 'day'
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'rew_dates', [
				'label' => __( '', 'restrict-elementor-widgets' ),				
				'type' => Controls_Manager::DATE_TIME,
				'picker_options' => [ 'enableTime' => false ],
				'default' => date( 'Y-m-d', strtotime( 'yesterday' ) ),
				'label_block' => true,
			]
		);

		$element->add_control(
			'rew_date_list',
			[
				'label' => __( 'Choose Dates', 'restrict-elementor-widgets' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'rew_dates' => date( 'Y-m-d', strtotime( 'yesterday' ) ),
					],
				],				
				'condition'   	=> [
					'rew_enable_restriction'  	=> 'yes',
					'rew_show_content_to'  		=> 'date-time',
					'rew_date_time_type'  		=> 'date'
				],
				'title_field' => '{{{ rew_dates }}}',
			]
		);

		$element->add_control(
			'rewdt_specific_time',
			[
				'label'        => __( 'Show at specific times?', 'restrict-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'restrict-elementor-widgets' ),
				'label_off'    => __( 'No', 'restrict-elementor-widgets' ),
				'return_value' => 'yes',			
				'separator' 	=> 'before',			
				'condition'   	=> [
					'rew_enable_restriction'  	=> 'yes',
					'rew_show_content_to'  		=> 'date-time',
				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'rew_time_title',
			[
				'label' => __( 'Name', 'restrict-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Time #1', 'restrict-elementor-widgets' )
			]
		);

		$repeater->add_control(
			'rew_due_time_from',
			[
				'label' => __( 'Time From', 'restrict-elementor-widgets' ),
				'type' => 'time',				
			]
		);

		$repeater->add_control(
			'rew_due_time_to',
			[
				'label' => __( 'Time To', 'restrict-elementor-widgets' ),
				'type' => 'time',
			]
		);

		$element->add_control(
			'rew_time_list',
			[
				'label' => __( 'Choose Times', 'restrict-elementor-widgets' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'rew_time_title' => __( 'Monring', 'restrict-elementor-widgets' ),
						'rew_due_time_from' => '06:00',
						'rew_due_time_to' => '11:00'
					],
					[
						'rew_time_title' => __( 'Time #2', 'restrict-elementor-widgets' ),
						'rew_due_time_from' => '18:00',
						'rew_due_time_to' => '22:00'
					],
				],				
				'condition'   	=> [
					'rew_enable_restriction'  	=> 'yes',
					'rew_show_content_to'  		=> 'date-time',
					'rewdt_specific_time'  	=> 'yes'
				],
				'title_field' => '{{{ rew_time_title }}}',
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'rew_query_key',
			[
				'label' => __( 'Key', 'restrict-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'key', 'restrict-elementor-widgets' )
			]
		);

		$repeater->add_control(
			'rew_query_value',
			[
				'label' => __( 'Value', 'restrict-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,				
				'default' => __( 'value', 'restrict-elementor-widgets' )			
			]
		);

		$element->add_control(
			'rew_query_relation',
			[
				'type'			=> Controls_Manager::SELECT,
				'label'			=> __( 'Query Relation', 'restrict-elementor-widgets' ),
				'description'	=> __( 'If AND is selected, all condition pairs need to match. If OR is selected, matching any of them would show it.', 'restrict-elementor-widgets' ),
				'options' => [
					'AND' 	=> __( 'AND', 'restrict-elementor-widgets' ),
					'OR'	=> __( 'OR', 'restrict-elementor-widgets' )
				],
				'separator' 	=> 'before',				
				'condition'   	=> [
					'rew_enable_restriction'  	=> 'yes',
					'rew_show_content_to'  		=> 'query-string'
				],
			]
		);

		$element->add_control(
			'rew_query_list',
			[
				'label' => __( 'Queries', 'restrict-elementor-widgets' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'rew_query_key'		=> __( 'key1', 'restrict-elementor-widgets' ),
						'rew_query_value'	=> 'value1'
					],
					[
						'rew_query_key'		=> __( 'key2', 'restrict-elementor-widgets' ),
						'rew_query_value'	=> 'value2'
					],
				],				
				'condition'   	=> [
					'rew_enable_restriction'  	=> 'yes',
					'rew_show_content_to'  		=> 'query-string'
				],
				'title_field' => '{{{ rew_query_key }}}',
			]
		);

		do_action( 'rew_before_message_control', $element );

		$element->add_control(
			'rew_show_message',
			[
				'type'			=> Controls_Manager::SELECT,
				'label'			=> __( 'Show Alternate Content', 'restrict-elementor-widgets' ),
				'description'	=> __( 'Show an alternative of your main content', 'restrict-elementor-widgets' ),
				'options' => [
					'nomessage' => __( 'Nothing', 'restrict-elementor-widgets' ),
					'text' 		=> __( 'Plain Text', 'restrict-elementor-widgets' ),
					'template' 	=> __( 'Template', 'restrict-elementor-widgets' )
				],
				'default'		=> 'plaintext',
				'separator' 	=> 'before',
				'condition'   	=> [
					'rew_enable_restriction'  => 'yes'
				],
			]
		);

		$element->add_control(
			'rew_message_text',
			[
				'type'			=> Controls_Manager::WYSIWYG,
				'label'			=> __( 'Your Message', 'restrict-elementor-widgets' ),
				'description'	=> __( 'This message will be shown as an alternate of your content', 'restrict-elementor-widgets' ),
				'default'      	=> __( 'This content is restricted!', 'restrict-elementor-widgets' ),
				'condition'   => [
					'rew_enable_restriction'  	=> 'yes',
					'rew_show_message'  		=> 'text'
				],
			]
		);

		$element->add_control(
			'rew_message_template',
			[
				'type'			=> Controls_Manager::SELECT,
				'label'			=> __( 'Choose a <strong>Section</strong> template', 'restrict-elementor-widgets' ),
				'description'	=> __( 'This template will be shown as an alternate of your main content', 'restrict-elementor-widgets' ),
				'options' 		=> rew_get_message_templates(),
				'label_block' 	=> true,
				'condition'   => [
					'rew_enable_restriction'  	=> 'yes',
					'rew_show_message'  		=> 'template'
				],
			]
		);
	}
}