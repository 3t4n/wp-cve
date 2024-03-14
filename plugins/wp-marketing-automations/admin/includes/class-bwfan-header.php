<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
#[AllowDynamicProperties]
final class BWFAN_Header {

	public $data = array();

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {
		$this->data['back_link']                            = '';
		$this->data['level_1_navigation_active']            = '';
		$this->data['level_2_title']                        = '';
		$this->data['level_2_post_title']                   = '';
		$this->data['level_2_right_wrap_type']              = 'menu';
		$this->data['level_2_right_side_navigation']        = array();
		$this->data['level_2_navigation_pos']               = 'left';
		$this->data['level_2_right_side_navigation_active'] = '';
		$this->data['level_2_right_html']                   = '';
	}

	public static function level_2_navigation_single_automation( $automation_id ) {
		if ( ! bwfan_is_autonami_pro_active() ) {
			return array(
				array(
					'workflow' => array(
						'name' => __( 'Workflow', 'wp-marketing-automations' ),
						'link' => admin_url( "admin.php?page=autonami-automations&edit=$automation_id" ),
					)
				)
			);
		}
		$single_automation_data = array(
			'workflow'   => array(
				'name' => __( 'Workflow', 'wp-marketing-automations' ),
				'link' => admin_url( "admin.php?page=autonami-automations&edit=$automation_id" ),
			),
			'engagement' => array(
				'name' => __( 'Engagements', 'wp-marketing-automations' ),
				'link' => admin_url( "admin.php?page=autonami&path=/automation-v1/$automation_id/engagements" ),
			),
		);

		if ( bwfan_is_woocommerce_active() ) {
			$single_automation_data['orders'] = array(
				'name' => __( 'Orders', 'wp-marketing-automations' ),
				'link' => admin_url( "admin.php?page=autonami&path=/automation-v1/$automation_id/orders" ),
			);
		}

		return $single_automation_data;
	}

	public function set_back_link( $enable = 0, $link = '' ) {
		if ( 0 === $enable || empty( $link ) ) {
			return;
		}
		$this->data['back_link'] = $link;
	}

	public function set_level_2_title( $title = '' ) {
		if ( empty( $title ) ) {
			return;
		}
		$this->data['level_2_title'] = $title;
	}

	public function set_level_2_post_title( $html = '' ) {
		if ( empty( $html ) ) {
			return;
		}
		$this->data['level_2_post_title'] = $html;
	}

	public function set_level_2_side_type( $type = 'menu' ) {
		if ( empty( $type ) ) {
			return;
		}
		$this->data['level_2_right_wrap_type'] = $type;
	}

	public function set_level_2_side_navigation( $navigation = array() ) {
		if ( empty( $navigation ) ) {
			return;
		}
		$this->data['level_2_right_side_navigation'] = $navigation;
	}

	public function set_level_2_navigation_pos( $positions = 'left' ) {
		if ( empty( $positions ) ) {
			return;
		}
		$this->data['level_2_navigation_pos'] = $positions;
	}

	public function set_level_1_navigation_active( $active = '' ) {
		if ( empty( $active ) ) {
			return;
		}
		$this->data['level_1_navigation_active'] = $active;
	}

	public function set_level_2_side_navigation_active( $active = '' ) {
		if ( empty( $active ) ) {
			return;
		}
		$this->data['level_2_right_side_navigation_active'] = $active;
	}

	public function set_level_2_right_html( $html = '' ) {
		if ( empty( $html ) ) {
			return;
		}
		$this->data['level_2_right_html'] = $html;
	}

	/**
	 * For React Menu Render
	 */
	public function get_render_data() {
		return array(
			'logo'               => esc_url( plugin_dir_url( BWFAN_PLUGIN_FILE ) . 'woofunnels/assets/img/menu/funnelkit-logo.svg' ),
			'logo_link'          => admin_url( 'admin.php?page=autonami' ),
			'left_nav'           => self::left_navigation(),
			'right_nav'          => self::right_navigation(),
			'contacts_nav'       => self::level_2_navigation_contacts(),
			'broadcasts_nav'     => self::level_2_navigation_broadcasts(),
			'reports_nav'        => self::level_2_navigation_analytics(),
			'carts_nav'          => self::level_2_navigation_carts_react(),
			'automation_nav'     => self::level_2_navigation_automations(),
			'automationv2_nav'   => self::level_2_navigation_new_automation(),
			'settings_nav'       => self::level_2_navigation_settings_react(),
			'connectors_nav'     => self::level_2_navigation_connectors(),
			'templates_nav'      => self::level_2_navigation_templates(),
			'links_triggers_nav' => self::level_2_navigation_links_triggers(),
			'bulk_actions_nav'   => self::level_2_navigation_bulk_actions(),
			'forms_nav'          => self::level_2_navigation_forms(),
			'data'               => $this->data,
			'pluginDir'          => BWFAN_PLUGIN_URL,
		);
	}

	public static function left_navigation() {
		$left_nav_data = array(
			'dashboard'  => array(
				'name' => __( 'Dashboard', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami' ),
			),
			'contacts'   => array(
				'name' => __( 'Contacts', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/contacts' ),
			),
			'carts'      => array(
				'name' => __( 'Carts', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/carts/recoverable' ),
			),
			'campaigns'  => array(
				'name'         => __( 'Campaigns', 'wp-marketing-automations' ),
				'isExpandable' => true,
				'items'        => array(
					'automations-v2' => array(
						'name' => "Automations",
						'link' => admin_url( 'admin.php?page=autonami&path=/automations' ),
					),
					'automations-v1' => array(
						'name' => 'Automations <span>Legacy</span>',
						'link' => admin_url( 'admin.php?page=autonami&path=/automations-v1' ),
					),
					'broadcasts'     => array(
						'name' => __( 'Broadcasts', 'wp-marketing-automations' ),
						'link' => admin_url( 'admin.php?page=autonami&path=/broadcasts/email' ),
					),
					'templates'      => array(
						'name' => __( 'Templates', 'wp-marketing-automations' ),
						'link' => admin_url( 'admin.php?page=autonami&path=/templates' ),
					),
				),
			),
			'tools'      => array(
				'name'         => __( 'Tools', 'wp-marketing-automations' ),
				'isExpandable' => true,
				'items'        => self::get_tools_menu(),
			),
			'analytics'  => array(
				'name'         => __( 'Analytics', 'wp-marketing-automations' ),
				'isExpandable' => true,
				'items'        => self::get_analytics_items(),
			),
			'connectors' => array(
				'name' => 'Connectors',
				'link' => admin_url( 'admin.php?page=autonami&path=/connectors' ),
			),
		);

		if ( ! bwfan_is_woocommerce_active() ) {
			unset( $left_nav_data['carts'] );
		}
		if ( ! BWFAN_Common::is_automation_v1_active() ) {
			unset( $left_nav_data['campaigns']['items']['automations-v1'] );
		}

		return $left_nav_data;
	}

	/**
	 * Returns tools menu
	 *
	 * @return array[]
	 */
	public static function get_tools_menu() {
		$tool_menu = array(
			'forms'         => array(
				'name' => __( 'Forms', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/forms' ),
			),
			'link-triggers' => array(
				'name' => __( 'Link Triggers', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/link-triggers' ),
			)
		);

		if ( ! get_option( 'bwfan_smtp_recommend', false ) ) {
			$tool_menu['mail-setup'] = array(
				'name' => __( 'Email Setup', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/mail-setup' ),
			);
		}

		return $tool_menu;
	}

	/**
	 * Returns filtered tabs
	 *
	 * @return array[]
	 */
	public static function get_analytics_items() {
		$items = [];
		if ( false !== BWFAN_Plugin_Dependency::woocommerce_active_check() ) {
			$items['analytics-carts'] = array(
				'name' => __( 'Carts', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/analytics' ),
			);
		}
		$items['analytics-contacts'] = array(
			'name' => __( 'Contacts', 'wp-marketing-automations' ),
			'link' => admin_url( 'admin.php?page=autonami&path=/analytics/contacts' ),
		);
		$items['analytics-emails']   = array(
			'name' => __( 'Emails', 'wp-marketing-automations' ),
			'link' => admin_url( 'admin.php?page=autonami&path=/analytics/emails' ),
		);

		if ( method_exists( 'BWFCRM_Common', 'get_sms_provider_slug' ) && ! empty( BWFCRM_Common::get_sms_provider_slug() ) ) {
			$items['analytics-sms'] = array(
				'name' => __( 'SMS', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/analytics/sms' ),
			);
		}
		$items['analytics-engagement'] = array(
			'name' => __( 'Engagement', 'wp-marketing-automations' ),
			'link' => admin_url( 'admin.php?page=autonami&path=/analytics/engagement' ),
		);

		return apply_filters( 'bwfan_autonami_analytics_l1_tabs', $items );
	}

	public static function right_navigation() {

		return array(
			'community' => array(
				'name'   => __( 'Join our community', 'wp-marketing-automations' ),
				'desc'   => __( 'Meet the other users', 'wp-marketing-automations' ),
				'icon'   => 'community',
				'link'   => 'https://www.facebook.com/groups/233743063908243/',
				'target' => '_blank',
			),
			'support'   => array(
				'name'   => __( 'Get Help', 'wp-marketing-automations' ),
				'desc'   => __( 'Contact support team', 'wp-marketing-automations' ),
				'icon'   => 'support',
				'link'   => self::prepare_web_url( 'https://funnelkit.com/support' ),
				'target' => '_blank',
			),
			'help'      => array(
				'name'   => __( 'Read Docs', 'wp-marketing-automations' ),
				'desc'   => __( 'Get help along the way', 'wp-marketing-automations' ),
				'icon'   => 'help',
				'link'   => self::prepare_web_url( 'https://funnelkit.com/docs/autonami-2/' ),
				'target' => '_blank',
			),
		);
	}

	private static function prepare_web_url( $link ) {
		return add_query_arg( array(
			'utm_source'   => 'WordPress',
			'utm_medium'   => 'Header+Menu',
			'utm_campaign' => 'Lite+Plugin',
		), $link );
	}

	public static function level_2_navigation_contacts() {
		return array(
			'contacts'         => array(
				'name' => __( 'All', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/contacts' ),
			),
			'manage_audiences' => array(
				'name' => __( 'Audiences', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/manage/audiences' ),
			),
			'manage_fields'    => array(
				'name' => __( 'Fields', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/manage/fields' ),
			),
			'manage_lists'     => array(
				'name' => __( 'Lists', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/manage/lists' ),
			),
			'manage_tags'      => array(
				'name' => __( 'Tags', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/manage/tags' ),
			),
			'bulk_actions'     => array(
				'name' => __( 'Bulk Actions', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/bulk-actions' ),
			),
		);
	}

	public static function level_2_navigation_broadcasts() {
		$broadcast_nav = array(
			'email' => array(
				'name' => __( 'Email', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/broadcasts/email' ),
			),
			'sms'   => array(
				'name' => __( 'SMS', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/broadcasts/sms' ),
			),
		);

		if ( bwfan_is_autonami_pro_active() && BWFCRM_Core()->conversation->is_whatsapp_service_available() ) {
			$broadcast_nav['whatsapp'] = array(
				'name' => __( 'WhatsApp', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/broadcasts/whatsapp' ),
			);
		}

		return $broadcast_nav;
	}

	public static function level_2_navigation_analytics() {
		$analytics_nav_data = $direct_mail_analytics_nav_data = $sms_analytics = array();

		if ( bwfan_is_woocommerce_active() ) {
			$analytics_nav_data['carts-analytics'] = array(
				'name' => __( 'Carts', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/analytics/carts' ),
			);
		}

		if ( apply_filters( 'bwfan_show_direct_mail_analytics', false ) && bwfan_is_autonami_pro_active() ) {
			$direct_mail_analytics_nav_data = array(
				'direct-emails-analytics' => array(
					'name'         => __( 'Direct Emails', 'wp-marketing-automations' ),
					'link'         => admin_url( 'admin.php?page=autonami&path=/analytics/direct-emails' ),
					'isProFeature' => true,
					'showOnClick'  => true,
				)
			);
		}

		if ( method_exists( 'BWFCRM_Common', 'get_sms_provider_slug' ) && ! empty( BWFCRM_Common::get_sms_provider_slug() ) ) {
			$sms_analytics = array(
				'sms-analytics' => array(
					'name'         => __( 'SMS', 'wp-marketing-automations' ),
					'link'         => admin_url( 'admin.php?page=autonami&path=/analytics/sms' ),
					'isProFeature' => true,
					'showOnClick'  => true,
				)
			);
		}

		$analytics_nav_data = array_merge( $analytics_nav_data, array(
			'contacts-analytics' => array(
				'name'         => __( 'Contacts', 'wp-marketing-automations' ),
				'link'         => admin_url( 'admin.php?page=autonami&path=/analytics/contacts' ),
				'isProFeature' => true,
				'showOnClick'  => true,
			),
			'emails-analytics'   => array(
				'name'         => __( 'Emails', 'wp-marketing-automations' ),
				'link'         => admin_url( 'admin.php?page=autonami&path=/analytics/emails' ),
				'isProFeature' => true,
				'showOnClick'  => true,
			),
		), $sms_analytics, $direct_mail_analytics_nav_data, array(
			'engagement-analytics' => array(
				'name'         => __( 'Engagement', 'wp-marketing-automations' ),
				'link'         => admin_url( 'admin.php?page=autonami&path=/analytics/engagement' ),
				'isProFeature' => true,
				'showOnClick'  => true,
			),
		) );

		return apply_filters( 'bwfan_autonami_analytics_l2_tabs', $analytics_nav_data );
	}

	public static function level_2_navigation_carts_react() {
		if ( ! bwfan_is_woocommerce_active() ) {
			return array();
		}

		return array(
			'recoverable' => array(
				'name' => __( 'Recoverable', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/carts/recoverable' ),
			),
			'recovered'   => array(
				'name' => __( 'Recovered', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/carts/recovered' ),
			),
			'lost'        => array(
				'name' => __( 'Lost', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/carts/lost' ),
			),
		);
	}

	public static function level_2_navigation_automations() {
		$automations_tabs = array(
			'automations'  => array(
				'name' => 'Automations',
				'link' => admin_url( 'admin.php?page=autonami&path=/automations-v1' ),
			),
			'task-history' => array(
				'name' => __( 'Task History', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/automations-v1/task-history' ),
			),
		);

		return $automations_tabs;
	}

	public static function level_2_navigation_new_automation() {
		return array(
			'all'      => array(
				'name' => __( 'All', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/automations/all' ),
			),
			'active'   => array(
				'name' => __( 'Active', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/automations/active' ),
			),
			'inactive' => array(
				'name' => __( 'Inactive', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/automations/inactive' ),
			),
		);
	}

	public static function level_2_navigation_settings_react() {
		return array(
			'general'       => array(
				'name' => __( 'General', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/settings/general' ),
			),
			'unsubscribers' => array(
				'name' => __( 'Unsubscribers', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/settings/unsubscribers' ),
			),
			'tools'         => array(
				'name' => __( 'Tools', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/settings/tools' ),
			),
			'logs'          => array(
				'name' => __( 'Logs', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/settings/logs' ),
			),
		);
	}

	public static function level_2_navigation_connectors() {
		return array(
			'all'      => array(
				'name' => __( 'All', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/connectors' ),
			),
			'active'   => array(
				'name' => __( 'Active', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/connectors/active' ),
			),
			'inactive' => array(
				'name' => __( 'Inactive', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/connectors/inactive' ),
			),
		);
	}

	public static function level_2_navigation_templates() {
		return array(
			'templates' => array(
				'name' => __( 'Email', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/templates' ),
			),
		);
	}

	public static function level_2_navigation_links_triggers() {
		return array(
			'all'      => array(
				'name' => __( 'All', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/link-triggers/all' ),
			),
			'active'   => array(
				'name' => __( 'Active', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/link-triggers/active' ),
			),
			'inactive' => array(
				'name' => __( 'Inactive', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/link-triggers/inactive' ),
			),
		);
	}

	public static function level_2_navigation_bulk_actions() {
		return array(
			'all'       => array(
				'name' => __( 'All', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/bulk-actions' ),
			),
			'ongoing'   => array(
				'name' => __( 'Ongoing', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/bulk-actions/ongoing' ),
			),
			'completed' => array(
				'name' => __( 'Completed', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/bulk-actions/completed' ),
			),
			'paused'    => array(
				'name' => __( 'Paused', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/bulk-actions/paused' ),
			),
		);
	}

	public static function level_2_navigation_forms() {
		return array(
			'all'      => array(
				'name' => __( 'All', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/forms/all' ),
			),
			'active'   => array(
				'name' => __( 'Active', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/forms/active' ),
			),
			'inactive' => array(
				'name' => __( 'Inactive', 'wp-marketing-automations' ),
				'link' => admin_url( 'admin.php?page=autonami&path=/forms/inactive' ),
			),
		);
	}

	public function render( $automation_migrated = false ) {
		ob_start();
		?>
        <style>
            div#wpcontent {
                padding: 0;
                overflow-x: hidden !important;
                min-height: calc(100vh - 32px);
            }
        </style>
        <div class="bwfan_header bwfan-php">
            <div class="bwfan_header_l1">
                <div class="bwfan_header_l">
                    <div>
                        <a class="bwf-breadcrumb-svg-icon" href="<?php echo admin_url( 'admin.php?page=autonami' ); ?>">
                            <img src="<?php echo esc_url( plugin_dir_url( BWFAN_PLUGIN_FILE ) . 'woofunnels/assets/img/menu/funnelkit-logo.svg' ); ?>"/>
                        </a>
                    </div>
                    <div class="bwfan_navigation">
						<?php
						$navigation = self::left_navigation();
						$this->output_navigation( $navigation, 'automations' );
						?>
                    </div>
                </div>
                <div class="bwfan_header_r">
                    <div class="bwfan_navigation bwf-header-ellipses-wrap">
                        <a href="<?php echo admin_url( 'admin.php?page=autonami&path=/settings' ); ?>" class="bwf-link-setting" title="Settings" data-link-type="bwf-crm">
                            <svg style="width: 24px; height: 24px; max-height: initial;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                <rect width="24" height="24" fill="white"></rect>
                                <path d="M12.0124 16.1667C9.71484 16.1667 7.84564 14.2975 7.84564 12C7.84564 9.70248 9.71484 7.83328 12.0124 7.83328C14.3097 7.83328 16.1789 9.70248 16.1789 12C16.1789 14.2975 14.3097 16.1667 12.0124 16.1667ZM12.0124 9.08328C10.4039 9.08328 9.09564 10.3917 9.09564 12C9.09564 13.6083 10.4039 14.9167 12.0124 14.9167C13.6206 14.9167 14.9289 13.6083 14.9289 12C14.9289 10.3917 13.6206 9.08328 12.0124 9.08328Z" fill="#353030"></path>
                                <path d="M13.3982 22H10.6264C9.95976 22 9.37062 21.5267 9.22566 20.8742L8.87486 19.3141C8.30311 19.0717 7.75807 18.7551 7.24644 18.3684L5.72728 18.8483C5.07557 19.0508 4.36726 18.7741 4.04057 18.1908L2.65904 15.8017C2.32807 15.21 2.43977 14.4817 2.92652 14.0233L4.10389 12.9409C4.06819 12.6233 4.05064 12.3084 4.05064 12C4.05064 11.6916 4.06819 11.3767 4.10313 11.0591L2.93232 9.98172C2.43977 9.51831 2.32731 8.79001 2.65477 8.2059L4.04484 5.80173C4.36726 5.22495 5.07725 4.9509 5.72483 5.15002L7.24644 5.63083C7.75807 5.24417 8.30311 4.92755 8.87486 4.68494L9.22642 3.12335C9.37062 2.47333 9.95976 2 10.6264 2H13.3982C14.0648 2 14.654 2.47333 14.7989 3.12579L15.1497 4.68585C15.7215 4.92831 16.2665 5.24493 16.7781 5.63159L18.2973 5.1517C18.9507 4.9509 19.6573 5.22586 19.984 5.8092L21.3657 8.19827C21.6965 8.79001 21.5848 9.51831 21.0981 9.97668L19.9207 11.0591C19.9556 11.3767 19.9732 11.6925 19.9732 12C19.9732 12.3075 19.9556 12.6233 19.9207 12.9409L21.0923 14.0175C21.0939 14.0192 21.0956 14.0209 21.0981 14.0226C21.5848 14.4808 21.6973 15.2092 21.3698 15.7933L19.9797 18.1975C19.6573 18.7741 18.949 19.05 18.299 18.8483L16.7772 18.3675C16.2656 18.7542 15.7206 19.0708 15.149 19.3134L14.7973 20.875C14.654 21.5267 14.0648 22 13.3982 22ZM7.374 17.0491C7.51728 17.0491 7.65812 17.0983 7.77149 17.1917C8.344 17.6625 8.96809 18.0258 9.62895 18.2692C9.82807 18.3425 9.97563 18.5117 10.0223 18.7183L10.4465 20.6017C10.4656 20.6884 10.5414 20.75 10.6273 20.75H13.3989C13.4848 20.75 13.5607 20.6884 13.579 20.6034L14.0039 18.7183C14.0506 18.5117 14.1982 18.3425 14.3973 18.2692C15.0573 18.0258 15.6823 17.6625 16.2548 17.1917C16.4182 17.0566 16.6381 17.0133 16.8407 17.0775L18.6748 17.6567C18.7622 17.6842 18.8556 17.6517 18.8956 17.58L20.2857 15.1758C20.3273 15.1017 20.3122 15 20.2457 14.935L18.839 13.6417C18.6864 13.5016 18.6132 13.2941 18.644 13.0892C18.6989 12.7201 18.7265 12.3534 18.7265 11.9992C18.7265 11.6449 18.6989 11.2783 18.644 10.9091C18.6132 10.7042 18.6864 10.4975 18.839 10.3566L20.2489 9.05994C20.3122 9.00089 20.3273 8.89743 20.2814 8.81503L18.8997 6.42581C18.8549 6.34662 18.7598 6.31503 18.6731 6.3425L16.8415 6.92081C16.6398 6.98505 16.4199 6.94171 16.2557 6.80667C15.6832 6.33578 15.0589 5.97247 14.3981 5.7291C14.199 5.65585 14.0514 5.48663 14.0049 5.28003L13.5807 3.39664C13.5598 3.31165 13.4839 3.25 13.3982 3.25H10.6264C10.5407 3.25 10.4648 3.31165 10.4465 3.39664L10.0214 5.28171C9.97486 5.48831 9.82731 5.65662 9.62818 5.73077C8.96809 5.97415 8.34309 6.33746 7.77149 6.80835C7.60731 6.94339 7.3856 6.98581 7.18571 6.92249L5.35145 6.34326C5.2657 6.31671 5.17063 6.3483 5.13066 6.42001L3.74058 8.82327C3.69892 8.89835 3.71403 9.00165 3.78315 9.06665L5.18818 10.3575C5.34062 10.4975 5.41401 10.705 5.38319 10.9101C5.32811 11.2792 5.30064 11.6458 5.30064 12C5.30064 12.3542 5.32811 12.7208 5.38319 13.0899C5.41401 13.295 5.34062 13.5016 5.18818 13.6425L3.77812 14.9391C3.71479 14.9984 3.69984 15.1017 3.74562 15.1842L5.1273 17.5733C5.1714 17.6525 5.26646 17.6833 5.35389 17.6567L7.18571 17.0783C7.24736 17.0583 7.31068 17.0491 7.374 17.0491Z" fill="#353030"></path>
                            </svg>
                        </a>
						<?php
						$navigation = self::right_navigation();
						$this->outputEllipsisMenu( $navigation, $this->data['level_1_navigation_active'] );
						?>
                    </div>
                </div>
            </div>
			<?php
			if ( ! $automation_migrated ) {
				echo '<div class="bwf-migration-header-notification">
                        <svg width="24" height="12" viewBox="0 0 24 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M14.3348 10.915C14.9768 9.53459 15.331 8.05295 15.3786 6.54899H18.4612C19.0109 6.54899 19.5188 6.2756 19.7937 5.83181C20.0683 5.38782 20.0683 4.84102 19.7937 4.39724C19.5188 3.95345 19.0109 3.68006 18.4612 3.68006H12.765L13.2795 3.38035C13.7501 3.106 14.0316 2.62185 14.0217 2.1045C14.0118 1.58709 13.7116 1.11258 13.2309 0.854169C12.7502 0.595767 12.1593 0.591345 11.6742 0.842641L4.46585 4.5769C4.36798 4.62743 4.3074 4.7233 4.3074 4.82743C4.3074 4.83818 4.30802 4.84875 4.30926 4.85932C4.51118 6.55441 4.51118 8.26503 4.30926 9.96007C4.29648 10.0667 4.34861 10.171 4.44442 10.2304C6.31962 11.3962 9.34967 11.9706 9.47784 11.9944C9.49762 11.9981 9.51781 12 9.53821 12H12.8472C13.6487 12 14.0325 11.4853 14.3348 10.915ZM9.56984 11.4264C9.2649 11.3666 6.63396 10.8323 4.94152 9.84675C5.12057 8.23085 5.12098 6.60201 4.94276 4.98611L11.9734 1.34427C12.2695 1.19076 12.6302 1.19345 12.9239 1.35118C13.2175 1.50891 13.4007 1.79863 13.4068 2.11465C13.4128 2.43049 13.2408 2.72619 12.9535 2.8937L11.5294 3.72366C11.4132 3.79168 11.3588 3.92289 11.3967 4.04604C11.4346 4.169 11.5551 4.25373 11.6924 4.25373H18.4614C18.7912 4.25373 19.096 4.4178 19.2608 4.68408C19.4256 4.95036 19.4256 5.2785 19.2608 5.54478C19.096 5.81106 18.7912 5.97513 18.4614 5.97513H15.0769C14.9078 5.97513 14.7705 6.10231 14.7691 6.25985C14.7606 7.77896 14.4227 9.28056 13.7762 10.6748C13.478 11.2366 13.2435 11.4264 12.8476 11.4264L9.56984 11.4264ZM2.7692 11.4264H0.923059C0.678488 11.426 0.443804 11.3353 0.270737 11.1739C0.0976691 11.0125 0.000411425 10.7937 0 10.5657V4.25354C0.000412111 4.02549 0.0976638 3.80667 0.270737 3.6453C0.44381 3.48392 0.678499 3.39323 0.923059 3.39285H2.7692C3.00903 3.39323 3.23939 3.48046 3.41165 3.63607C3.5839 3.79169 3.68446 4.00379 3.69208 4.22725C4.07161 6.33405 4.07161 8.48501 3.69208 10.592C3.68445 10.8154 3.5839 11.0275 3.41165 11.1832C3.2394 11.3388 3.00905 11.426 2.7692 11.4264ZM21.5386 5.40146V4.82759H24V5.40146H21.5386ZM20.6788 3.10467L22.6057 1.67625L22.9885 2.12543L21.0616 3.55385L20.6788 3.10467ZM20.6788 7.12438L21.0616 6.67521L22.9885 8.10343L22.6057 8.5528L20.6788 7.12438ZM19.3888 7.86537L19.8897 10.1126L19.287 10.2292L18.7861 7.98199L19.3888 7.86537ZM18.7861 2.24721L19.287 0L19.8897 0.116809L19.3888 2.36402L18.7861 2.24721Z" fill="#ECA717"></path>
                            <path d="M14.3348 10.915C14.9768 9.53459 15.331 8.05295 15.3786 6.54899H18.4612C19.0109 6.54899 19.5188 6.2756 19.7937 5.83181C20.0683 5.38782 20.0683 4.84102 19.7937 4.39724C19.5188 3.95345 19.0109 3.68006 18.4612 3.68006H12.765L13.2795 3.38035C13.7501 3.106 14.0316 2.62185 14.0217 2.1045C14.0118 1.58709 13.7116 1.11258 13.2309 0.854169C12.7502 0.595767 12.1593 0.591345 11.6742 0.842641L4.46585 4.5769C4.36798 4.62743 4.3074 4.7233 4.3074 4.82743C4.3074 4.83818 4.30802 4.84875 4.30926 4.85932C4.51118 6.55441 4.51118 8.26503 4.30926 9.96007C4.29648 10.0667 4.34861 10.171 4.44442 10.2304C6.31962 11.3962 9.34967 11.9706 9.47784 11.9944C9.49762 11.9981 9.51781 12 9.53821 12H12.8472C13.6487 12 14.0325 11.4853 14.3348 10.915ZM9.56984 11.4264C9.2649 11.3666 6.63396 10.8323 4.94152 9.84675C5.12057 8.23085 5.12098 6.60201 4.94276 4.98611L11.9734 1.34427C12.2695 1.19076 12.6302 1.19345 12.9239 1.35118C13.2175 1.50891 13.4007 1.79863 13.4068 2.11465C13.4128 2.43049 13.2408 2.72619 12.9535 2.8937L11.5294 3.72366C11.4132 3.79168 11.3588 3.92289 11.3967 4.04604C11.4346 4.169 11.5551 4.25373 11.6924 4.25373H18.4614C18.7912 4.25373 19.096 4.4178 19.2608 4.68408C19.4256 4.95036 19.4256 5.2785 19.2608 5.54478C19.096 5.81106 18.7912 5.97513 18.4614 5.97513H15.0769C14.9078 5.97513 14.7705 6.10231 14.7691 6.25985C14.7606 7.77896 14.4227 9.28056 13.7762 10.6748C13.478 11.2366 13.2435 11.4264 12.8476 11.4264L9.56984 11.4264ZM2.7692 11.4264H0.923059C0.678488 11.426 0.443804 11.3353 0.270737 11.1739C0.0976691 11.0125 0.000411425 10.7937 0 10.5657V4.25354C0.000412111 4.02549 0.0976638 3.80667 0.270737 3.6453C0.44381 3.48392 0.678499 3.39323 0.923059 3.39285H2.7692C3.00903 3.39323 3.23939 3.48046 3.41165 3.63607C3.5839 3.79169 3.68446 4.00379 3.69208 4.22725C4.07161 6.33405 4.07161 8.48501 3.69208 10.592C3.68445 10.8154 3.5839 11.0275 3.41165 11.1832C3.2394 11.3388 3.00905 11.426 2.7692 11.4264ZM21.5386 5.40146V4.82759H24V5.40146H21.5386ZM20.6788 3.10467L22.6057 1.67625L22.9885 2.12543L21.0616 3.55385L20.6788 3.10467ZM20.6788 7.12438L21.0616 6.67521L22.9885 8.10343L22.6057 8.5528L20.6788 7.12438ZM19.3888 7.86537L19.8897 10.1126L19.287 10.2292L18.7861 7.98199L19.3888 7.86537ZM18.7861 2.24721L19.287 0L19.8897 0.116809L19.3888 2.36402L18.7861 2.24721Z" stroke="#ECA717"></path>
                        </svg>
                        <div class="bwf-migration-header-message">' . __( 'This automation is built using an older version of Automation Builder. Try Next-Gen Automation Builder.', 'wp-marketing-automations' ) . '</div>
                        <a class="bwf-migration-header-button"  href="https://funnelkit.com/docs/autonami-2/automations/migrate-from-older-version/?utm_source=WordPress&utm_medium=Automation+Nextgen+Migrate&utm_campaign=Lite+Plugin" target="_blank">
                            ' . __( 'Learn about Migrating', 'wp-marketing-automations' ) . '
                        </a>
                        <a class="bwf-migration-header-button bwf-info" id="bwf-migration-header-button" href="#" data-izimodal-open="#modal-autonami-migrator-modal" data-izimodal-title="Migrate Automation" data-izimodal-transitionin="comingIn">
                            ' . __( 'Confirm Migration', 'wp-marketing-automations' ) . '
                        </a>
                    </div>';
			}
			?>
            <div class="bwfan_page_header"> Automations</div>
            <div class="bwfan_header_l2 bwfan_header_l2_single">
                <div class="bwfan_header_l">
					<?php
					if ( ! empty( $this->data['back_link'] ) ) {
						echo '<span class="bwfan_header_l2_back"><a href="' . $this->data['back_link'] . '">All Automations</a></span>';
					}
					echo '<span class="bwfan_header_l2_wrap">';
					if ( ! empty( $this->data['level_2_title'] ) ) {
						echo '<span id="bwfan_automation_name">' . $this->data['level_2_title'] . '</span>';
					}
					if ( ! empty( $this->data['level_2_post_title'] ) ) {
						echo $this->data['level_2_post_title'];
					}
					echo '</span>';
					if ( ( 'menu' === $this->data['level_2_right_wrap_type'] || 'both' === $this->data['level_2_right_wrap_type'] ) && 'right' !== $this->data['level_2_navigation_pos'] ) {
						$navigation = $this->data['level_2_right_side_navigation'];
						$active     = $this->data['level_2_right_side_navigation_active'];

						echo '<div class="bwfan_navigation">';
						$this->output_navigation( $navigation, $active );
						echo '</div>';
					}
					?>
                </div>
            </div>
            <div class="bwfan_header_l2">
                <div class="bwfan_header_l">
					<?php
					if ( 'right' === $this->data['level_2_navigation_pos'] ) {
						$navigation = $this->data['level_2_right_side_navigation'];
						$active     = $this->data['level_2_right_side_navigation_active'];

						echo '<div class="bwfan_navigation">';
						$this->output_navigation( $navigation, 'workflow' );
						echo '</div>';
					}
					?>
                </div>
                <div class="bwfan_header_r">
					<?php
					if ( ( 'html' === $this->data['level_2_right_wrap_type'] || 'both' === $this->data['level_2_right_wrap_type'] ) && ! empty( $this->data['level_2_right_html'] ) ) {
						echo $this->data['level_2_right_html'];
					}
					?>
                </div>
            </div>

        </div>
		<?php
		return ob_get_clean();
	}

	public function output_navigation( $navigation, $active_slug = '' ) {
		if ( ! is_array( $navigation ) || 0 === count( $navigation ) ) {
			return;
		}
		foreach ( $navigation as $key => $item ) {
			if ( isset( $item['isExpandable'] ) && $item['isExpandable'] == true ) {
				$dropdown_data = '';
				$active        = false;
				if ( isset( $item['items'] ) && ! empty( $item['items'] ) ) {
					$dropdown_data = '<span class="bwf-hover-suheader-menu">';
					foreach ( $item['items'] as $key => $data ) {
						$class         = $active_slug == $key ? 'bwfan_navigation_active' : '';
						$dropdown_data .= '<a href="' . esc_url( $data['link'] ) . '" class="' . $class . '" data-link-type="bwf-crm">' . $data['name'] . '</a>';

						if ( $active_slug == $key ) {
							$active = true;
						}
					}
					$dropdown_data .= '</span>';
				}
				$mainclass = $active ? 'bwfan_navigation_active' : '';
				echo '<span class="bwf-hover-submenu bwf-hover-submenu-php">
                    <span class="' . $mainclass . '">' . $item['name'] . '</span>
                    <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                        <path d="M12,14.071L8.179,10.25c-0.414-0.414-1.086-0.414-1.5,0l0,0c-0.414,0.414-0.414,1.086,0,1.5l4.614,4.614 c0.391,0.391,1.024,0.391,1.414,0l4.614-4.614c0.414-0.414,0.414-1.086,0-1.5v0c-0.414-0.414-1.086-0.414-1.5,0L12,14.071z"></path>
                    </svg> ' . $dropdown_data . '
                </span>';
			} else {
				$active = ( ! empty( $active_slug ) && $key === $active_slug ) ? 'bwfan_navigation_active' : '';

				$icon = ( isset( $item['icon'] ) && ! empty( $item['icon'] ) ) ? wp_remote_retrieve_body( wp_remote_get( esc_url( plugin_dir_url( BWFAN_PLUGIN_FILE ) . 'admin/assets/img/menu/' . $item['icon'] . '.svg' ) ) ) : '';

				$target = ( isset( $item['target'] ) && ! empty( $item['target'] ) ) ? ' target="' . $item['target'] . '"' : '';

				$item_link = isset( $item['link'] ) ? $item['link'] : '#';
				$item_name = isset( $item['name'] ) ? $item['name'] : '';

				echo '<span><a href="' . $item_link . '" class="' . $active . '"' . $target . '>' . $icon . $item_name . '</a></span>';
			}

		}
		$active = null;
	}

	public function outputEllipsisMenu( $navigation, $active_slug = '' ) {
		if ( ! is_array( $navigation ) || 0 === count( $navigation ) ) {
			return;
		}
		?>
        <div class="bwf-ellipsis-menu bwf-ellipsis--alter-alter">
            <div class="components-dropdown">
                <button type="button" title="Quick Actions" aria-expanded="false" class="components-button bwf-ellipsis-menu__toggle has-text has-icon">
                    <svg width="24" height="24" viewBox="4 4 20 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xlink="http://www.w3.org/1999/xlink">
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="4.-Automation-Task-Splitup-and-Help-Icon" transform="translate(-1369.000000, -15.000000)">
                                <g id="Group-6" transform="translate(1370.000000, 16.000000)">
                                    <circle id="Oval" stroke="#FFFFFF" cx="13.5" cy="13.5" r="13.5"></circle>
                                    <g id="icons8-help" transform="translate(5.000000, 5.000000)" fill="#202B31" fill-rule="nonzero" opacity="0.74">
                                        <path d="M8.5,0 C3.81312805,0 0,3.81313145 0,8.5 C0,13.1868685 3.81312805,17 8.5,17 C13.1868719,17 17,13.1868685 17,8.5 C17,3.81313145 13.1868719,0 8.5,0 Z M8.5,1.275 C12.4978114,1.275 15.725,4.50219117 15.725,8.5 C15.725,12.4978088 12.4978114,15.725 8.5,15.725 C4.50218862,15.725 1.275,12.4978088 1.275,8.5 C1.275,4.50219117 4.50218862,1.275 8.5,1.275 Z M8.5,3.825 C7.09925737,3.825 5.95,4.97425737 5.95,6.375 L5.95,6.5875 C5.94674857,6.8174054 6.06753925,7.03125187 6.26611894,7.14715342 C6.46469863,7.26305497 6.71030137,7.26305497 6.90888106,7.14715342 C7.10746075,7.03125187 7.22825143,6.8174054 7.225,6.5875 L7.225,6.375 C7.225,5.66349262 7.78849262,5.1 8.5,5.1 C9.21150737,5.1 9.775,5.66349262 9.775,6.375 C9.775,7.36988632 9.516328,7.49455157 9.06279307,7.8508788 C8.8360254,8.02904262 8.5389997,8.23446085 8.28500992,8.58051752 C8.03101972,8.9265742 7.8625,9.40896535 7.8625,9.9875 C7.85924857,10.2174054 7.98003925,10.4312519 8.17861894,10.5471534 C8.37719863,10.663055 8.62280137,10.663055 8.82138106,10.5471534 C9.01996075,10.4312519 9.14075143,10.2174054 9.1375,9.9875 C9.1375,9.62487215 9.20804277,9.47757905 9.31264632,9.3350587 C9.4172503,9.19253792 9.5983496,9.05109665 9.84970692,8.85361317 C10.352422,8.45864665 11.05,7.71761367 11.05,6.375 C11.05,4.97425737 9.90074262,3.825 8.5,3.825 Z M8.5,11.9 C8.03055796,11.9 7.65,12.280558 7.65,12.75 C7.65,13.219442 8.03055796,13.6 8.5,13.6 C8.96944204,13.6 9.35,13.219442 9.35,12.75 C9.35,12.280558 8.96944204,11.9 8.5,11.9 Z" id="Shape"></path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </button>
                <div tabindex="-1">
                    <div class="components-popover components-dropdown__content bwf-ellipsis-menu__popover components-animate__appear is-from-top is-from-right is-without-arrow" data-x-axis="left" data-y-axis="bottom">
                        <div class="components-popover__content" tabindex="-1">
                            <div style="position: relative;">
                                <div role="menu" aria-orientation="vertical" class="bwf-ellipsis-menu__content">
									<?php
									foreach ( $navigation as $key => $item ) {
										?>
                                        <a href="<?php echo $item['link']; ?>" target="<?php echo $item['target']; ?>" role="menuitem" tabindex="0" class="bwf-ellipsis-menu__item">
                                            <div class="components-flex css-1ahbsz-Flex eboqfv50">
                                                <div class="components-flex__item css-1s295sp-Item eboqfv51">
													<?php
													$icon = ( isset( $item['icon'] ) && ! empty( $item['icon'] ) ) ? wp_remote_retrieve_body( wp_remote_get( esc_url( plugin_dir_url( BWFAN_PLUGIN_FILE ) . 'admin/assets/img/menu/' . $item['icon'] . '.svg' ) ) ) : '';
													echo $icon;
													?>
                                                </div>
                                                <div class="components-flex__block css-yr442k-Item-Block eboqfv52">
                                                    <div class="bwf_display_block">
                                                        <div class="menu-item-title"><?php echo $item['name']; ?></div>
                                                        <div class="menu-item-desc"><?php echo $item['desc']; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
										<?php
									}
									?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
}
