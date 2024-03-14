<?php

/**
 * Created
 * User: alan
 * Date: 04/04/18
 * Time: 13:45
 */
namespace WidgetForEventbriteAPI\Admin;

use  WidgetForEventbriteAPI\Includes\Eventbrite_Manager ;
class Admin_Settings extends Admin_Pages
{
    protected  $settings_page ;
    protected  $settings_page_id = 'settings_page_widget-for-eventbrite-api-settings' ;
    protected  $option_group = 'widget-for-eventbrite-api' ;
    protected  $settings_title ;
    /**
     * Settings constructor.
     *
     * @param string $plugin_name
     * @param string $version plugin version.
     * @param \Freemius $freemius Freemius SDK.
     */
    public function __construct( $plugin_name, $version, $freemius )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->freemius = $freemius;
        $this->settings_title = esc_html__( 'Display Eventbrite Events Settings', 'widget-for-eventbrite-api' );
        parent::__construct();
    }
    
    public static function option_defaults( $option )
    {
        switch ( $option ) {
            case 'widget-for-eventbrite-api-settings':
                return array(
                    'cache_clear'    => 0,
                    'cache_duration' => 86400,
                    'plugin-css'     => 1,
                    'background_api' => 0,
                    'key'            => '',
                    'webhook'        => '',
                );
            default:
                return false;
        }
    }
    
    public function add_meta_boxes()
    {
        add_meta_box(
            'info',
            /* Meta Box ID */
            esc_html__( 'Information', 'widget-for-eventbrite-api' ),
            /* Title */
            array( $this, 'meta_box_1' ),
            /* Function Callback */
            $this->settings_page_id,
            /* Screen: Our Settings Page */
            'normal',
            /* Context */
            'default'
        );
        add_meta_box(
            'api',
            /* Meta Box ID */
            esc_html__( 'Eventbrite API Key', 'widget-for-eventbrite-api' ),
            /* Title */
            array( $this, 'meta_box_4' ),
            /* Function Callback */
            $this->settings_page_id,
            /* Screen: Our Settings Page */
            'normal',
            /* Context */
            'default'
        );
        add_meta_box(
            'cache',
            /* Meta Box ID */
            esc_html__( 'Manage Cache', 'widget-for-eventbrite-api' ),
            /* Title */
            array( $this, 'meta_box_2' ),
            /* Function Callback */
            $this->settings_page_id,
            /* Screen: Our Settings Page */
            'normal',
            /* Context */
            'default'
        );
        $meta_box_webhooks = 'meta_box_webhooks';
        add_meta_box(
            'webhooks',
            /* Meta Box ID */
            esc_html__( 'Webhook Notifications ( optional )', 'widget-for-eventbrite-api' ),
            /* Title */
            array( $this, $meta_box_webhooks ),
            /* Function Callback */
            $this->settings_page_id,
            /* Screen: Our Settings Page */
            'normal',
            /* Context */
            'default'
        );
        add_meta_box(
            'Styles',
            /* Meta Box ID */
            esc_html__( 'Style Settings', 'widget-for-eventbrite-api' ),
            /* Title */
            array( $this, 'meta_box_5' ),
            /* Function Callback */
            $this->settings_page_id,
            /* Screen: Our Settings Page */
            'normal',
            /* Context */
            'default'
        );
        add_meta_box(
            'shortcode',
            /* Meta Box ID */
            esc_html__( 'Shortcode Syntax', 'widget-for-eventbrite-api' ),
            /* Title */
            array( $this, 'meta_box_3' ),
            /* Function Callback */
            $this->settings_page_id,
            /* Screen: Our Settings Page */
            'normal',
            /* Context */
            'default'
        );
        add_meta_box(
            'demodiv',
            /* Meta Box ID */
            esc_html__( 'Shortcode Builder', 'widget-for-eventbrite-api' ),
            /* Title */
            array( $this, 'demo_meta_box' ),
            /* Function Callback */
            $this->settings_page_id,
            /* Screen: Our Settings Page */
            'side',
            /* Context */
            'high'
        );
    }
    
    /**
     * @param $settings
     *
     * @return array
     */
    public function clear_cache( $settings )
    {
        $eventbrite_manager = new Eventbrite_Manager();
        $eventbrite_manager->flush_transients( 'eventbrite' );
        $settings['cache_clear'] = 0;
        add_settings_error(
            'wfea-cache',
            esc_attr( 'cache_cleared' ),
            esc_html__( 'The Plugin Cache has been reset - If you have a CDN or Host Cache or Caching Plugin you may need to clear those caches manually', 'widget-for-eventbrite-api' ),
            'updated'
        );
        return array( $eventbrite_manager, $settings );
    }
    
    public function delete_options()
    {
        update_option( 'widget-for-eventbrite-api-settings', self::option_defaults( 'widget-for-eventbrite-api-settings' ) );
    }
    
    public function demo_meta_box()
    {
        ?>
        <div id="demopost" class="submitbox">

            <div id="major-demo-actions">

                <div id="demo-action">
					<?php 
        $this->display_demo_button();
        ?>
                </div>

            </div>
        </div>

		<?php 
    }
    
    public function meta_box_1()
    {
        $infomsg = '<p>' . sprintf( __( '<p>Welcome. To use this plugin add the widget to your website</p><p>For more detailed setup instructions visit <a target= "_blank" href="https://fullworksplugins.com/docs/display-eventbrite-events-in-wordpress/installation-display-eventbrite-events-in-wordpress/" >the knowledgebase.</a></p>
            <p>Support for the <strong>free</strong> version is provided <a class="button-secondary" href="https://wordpress.org/support/plugin/widget-for-eventbrite-api" target="_blank">here on WordPress.org.</a></p>
			<p>Get a FREE trial of the Pro version - <a href="%1$s">click here</a> 
			<h2>Pro Version Benefits</h2>
			<ul style="list-style-type:disc;list-style-position: inside;">
			    <li>14 day free trial</li>
			    <li>Keep visitors on your site, with integrated checkout popup</li>
			    <li>Show the full event details in a popup too</li>
				<li>Let your users see your events on full pages and post</li>
				<li>Show your events off on many different layouts</li>
				<li>Need a calendar layout? We have one of those</li>
				<li>Like a grid layout? We have one of those too</li>
				<li>Full page? of course - why not browse the <a href="https://fullworksplugins.com/products/widget-for-eventbrite/eventbrite-shortcode-demo/" target="_blank">demo shortcode builder page</a> to see powerful options</li>
				<li>Want to show off invite only events? the pro version can</li>
				<li>Do you have lots of events and would like to filter them down? The shortcode has sophisticated filters</li>
			</ul>
			</p>
			', 'widget-for-eventbrite-api' ), $this->freemius->get_trial_url() );
        echo  wp_kses_post( $infomsg ) ;
    }
    
    public function meta_box_2()
    {
        $options = get_option( 'widget-for-eventbrite-api-settings' );
        if ( !isset( $options['cache_clear'] ) ) {
            $options['cache_clear'] = 0;
        }
        if ( !isset( $options['cache_duration'] ) ) {
            $options['cache_duration'] = 86400;
        }
        $units = array(
            array( 604800, esc_html__( '1 Week', 'widget-for-eventbrite-api' ) ),
            array( 172800, esc_html__( '2 Days', 'widget-for-eventbrite-api' ) ),
            array( 86400, esc_html__( '1 Day - recommended', 'widget-for-eventbrite-api' ) ),
            array( 43200, esc_html__( '12 hours', 'widget-for-eventbrite-api' ) ),
            array( 14400, esc_html__( '4 hours', 'widget-for-eventbrite-api' ) ),
            array( 3600, esc_html__( '1 hour ', 'widget-for-eventbrite-api' ) ),
            array( 1800, esc_html__( '30 Minutes', 'widget-for-eventbrite-api' ) ),
            array( 900, esc_html__( '15 Minutes - use on low volume sites only', 'widget-for-eventbrite-api' ) ),
            array( 60, esc_html__( 'No Cache - use for development & testing only ', 'widget-for-eventbrite-api' ) )
        );
        ?>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php 
        esc_html_e( 'Clear Cache', 'widget-for-eventbrite-api' );
        ?></th>
                <td>
                    <label for="widget-for-eventbrite-api-settings[cache_clear]"><input
                                type="checkbox"
                                name="widget-for-eventbrite-api-settings[cache_clear]"
                                id="widget-for-eventbrite-api-settings[cache_clear]"
                                value="1"
							<?php 
        checked( '1', $options['cache_clear'] );
        ?>>
						<?php 
        esc_html_e( 'Tick and [save] to clear', 'widget-for-eventbrite-api' );
        ?></label>
                    <p>
                        <span
                                class="description"><?php 
        esc_html_e( 'Clear the cache now, use for testing or if you have published or changed events and you want to refresh now', 'widget-for-eventbrite-api' );
        ?></span>
                    </p>
					<?php 
        ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p>
						<?php 
        $infomsg = esc_html__( 'The following features are only available on the pro plan or trial', 'widget-for-eventbrite-api' );
        $disabled = ' disabled="disabled" ';
        echo  wp_kses_post( $infomsg ) ;
        ?></p>
            </tr>
            <tr valign="top" class="alternate">
                <th scope="row"><?php 
        esc_html_e( 'Cache Duration', 'widget-for-eventbrite-api' );
        ?></th>
                <td>
                    <select <?php 
        echo  esc_attr( $disabled ) ;
        ?>
                            name="widget-for-eventbrite-api-settings[cache_duration]"
                            id="widget-for-eventbrite-api-settings[cache_duration]"
                            class="small-text">
						<?php 
        foreach ( $units as $unit ) {
            ?>
                            <option value="<?php 
            echo  (int) $unit[0] ;
            ?>"
								<?php 
            echo  ( $options['cache_duration'] == $unit[0] ? " selected" : "" ) ;
            ?>><?php 
            echo  esc_html( $unit[1] ) ;
            ?></option>
						<?php 
        }
        ?>
                    </select>
                    <p>
                        <span
                                class="description"><?php 
        printf( esc_html__( 'Set the cache period for the Eventbrite API. Read %1$sthis article%2$s before changing the default of once per day', 'widget-for-eventbrite-api' ), '<a href="https://fullworksplugins.com/docs/display-eventbrite-events-in-wordpress/usage/understanding-the-cache/" target="_blank">', '</a>' );
        ?></span>
                    </p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php 
        esc_html_e( 'Background API Processing', 'widget-for-eventbrite-api' );
        ?></th>
                <td>
                    <label for="widget-for-eventbrite-api-settings[background_api]"><input <?php 
        echo  esc_attr( $disabled ) ;
        ?>
                                type="checkbox"
                                name="widget-for-eventbrite-api-settings[background_api]"
                                id="widget-for-eventbrite-api-settings[background_api]"
                                value="1"
							<?php 
        checked( '1', $options['background_api'] );
        ?>>
						<?php 
        esc_html_e( 'Leave ticked for background processing', 'widget-for-eventbrite-api' );
        ?>
                    </label>
                    <p>
                        <span
                                class="description"><?php 
        esc_html_e( 'Background API processing is useful when you have many hundreds of events or using advanced pro features like long description that create many API calls', 'widget-for-eventbrite-api' );
        ?></span>
                    </p>
                </td>
            </tr>


            </tbody>
        </table>
		<?php 
    }
    
    public function meta_box_3()
    {
        $infomsg = '<p>' . sprintf( __( 'Shortcode usage e.g. [wfea] [wfea limit=10 ] etc.<br>
		See your own events on the shortcode builder site to select your desired options
<a href="https://fullworksplugins.com/docs/display-eventbrite-events-in-wordpress/usage/using-the-shortcode/#free" target="_blank">all free options detailed here</a>
<br><br>
Additional shortcode options are available in the  paid for version<br><br>
			Get a FREE trial of the Pro version - <a href="%1$s">click here</a> 
			<h2>Pro Version Benefits</h2>
			<ul style="list-style-type:disc;list-style-position: inside;">
				<li>14 day free trial</li>
				<li>Keep visitors on your site, with integrated checkout popup</li>
				<li>Let your users see your events on full pages and post</li>
				<li>Show your events off on layouts including styles for Divi, Genesis and WP default themes</li>
				<li>Need a calendar layout? We have one of those</li>
				<li>Like a grid or card layout? We have one of those too</li>
				<li>Are you and Eventbrite Music Promoter, we have all the extras like door times</li>
				<li>Full page? of course - why not browse the <a href="https://fullworksplugins.com/products/widget-for-eventbrite/eventbrite-shortcode-demo/" target="_blank">demo shortcode builder page</a> to see the powerful options</li>
				<li>Want to show off invite only events? the pro version can</li>
				<li>Do you have lots of events and would like to filter them down? The shortcode has sophisticated filters</li>
				<li>Need to see your events during development or quickly? The pro version has cache management</li>		
			</ul>
			Available when you go pro - layouts like  [wfea layout="cal" ] or [wfea layout="grid" popup=true ] etc - see  <a href="https://fullworksplugins.com/docs/display-eventbrite-events-in-wordpress/usage/using-the-shotcode/" target="_blank"> this page</a> for many optional arguments
			', 'widget-for-eventbrite-api' ), $this->freemius->get_trial_url() );
        echo  wp_kses_post( $infomsg ) ;
        $this->display_demo_button();
    }
    
    public function meta_box_4()
    {
        $options = get_option( 'widget-for-eventbrite-api-settings', array(
            'key'     => '',
            'webhook' => '',
        ) );
        ?>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php 
        esc_html_e( 'Your API Key - Private Token', 'widget-for-eventbrite-api' );
        ?></th>
                <td>
                    <input type="password" name="widget-for-eventbrite-api-settings[key]"
                           id="widget-for-eventbrite-api-settings-api-key"
                           class="regular-text required"
                           required
                           value="<?php 
        echo  esc_attr( $options['key'] ) ;
        ?>"
                    >
                    <p class="api-key-status"></p>
                    <p class="api-key-result"></p>
                    <p>
                        <span
                                class="description"><?php 
        esc_html_e( 'The key is required to connect to Eventbrite', 'widget-for-eventbrite-api' );
        ?>
                             - <a href="https://www.eventbrite.co.uk/platform/api-keys/"
                                  target="_blank"><?php 
        esc_html_e( 'Click here to get your free API Key from Eventbrite', 'widget-for-eventbrite-api' );
        ?></a></span>
                    </p>
                </td>
            </tr>

            </tbody>
        </table>
		<?php 
    }
    
    public function meta_box_5()
    {
        $options = get_option( 'widget-for-eventbrite-api-settings' );
        if ( !isset( $options['plugin-css'] ) ) {
            $options['plugin-css'] = 1;
        }
        ?>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php 
        esc_html_e( 'Output Default CSS', 'widget-for-eventbrite-api' );
        ?></th>
                <td>
                    <label for="widget-for-eventbrite-api-settings[plugin-css]"><input type="checkbox"
                                                                                       name="widget-for-eventbrite-api-settings[plugin-css]"
                                                                                       id="widget-for-eventbrite-api-settings[plugin-css]"
                                                                                       value="1"
							<?php 
        checked( '1', $options['plugin-css'] );
        ?>>
						<?php 
        esc_html_e( 'Enable this option to output the default CSS. Disable it if you plan to create your own CSS in a child theme or customizer additional CSS.', 'widget_for_eventbrite_api' );
        ?>
                    </label>
					<?php 
        
        if ( !$this->freemius->can_use_premium_code() ) {
            ?>
                        <p>
                        <span
                                class="description"><?php 
            esc_html_e( 'Need help with styling to your theme? Upgrade to a premium plan to request personal CSS support', 'widget-for-eventbrite-api' );
            ?>
                            <a class="button-secondary"
                               href="<?php 
            echo  esc_url( $this->freemius->get_upgrade_url() ) ;
            ?>"><?php 
            esc_html_e( 'Upgrade now', 'widget-for-eventbrite-api' );
            ?></a>
                        </span>
                        </p>
					<?php 
        }
        
        ?>
                </td>
            </tr>
            </tbody>
        </table>

		<?php 
    }
    
    public function meta_box_webhooks()
    {
        ?>
        <table class="form-table">
            <tbody>
            <tr>
                <td colspan="100">
                    <p>
						<?php 
        esc_html_e( '[Optional] you can set up WebHooks to notify this website that an event has changed, this will speed up changes appearing', 'widget_for_eventbrite_api' );
        ?>
                    </p>
                    <p>
                        <span
                                class="description"><?php 
        esc_html_e( 'Upgrade to a premium plan to enable WebHook handling', 'widget-for-eventbrite-api' );
        ?>
                            <a class="button-secondary"
                               href="<?php 
        echo  esc_url( $this->freemius->get_upgrade_url() ) ;
        ?>"><?php 
        esc_html_e( 'Upgrade now', 'widget-for-eventbrite-api' );
        ?></a>
                        </span>
                    </p>

                </td>
            </tr>
            </tbody>
        </table>
		<?php 
    }
    
    public function register_settings()
    {
        /* Register our setting. */
        register_setting(
            $this->option_group,
            /* Option Group */
            'widget-for-eventbrite-api-settings',
            /* Option Name */
            array( $this, 'sanitize_settings_1' )
        );
        /* Add settings menu page */
        $this->settings_page = add_submenu_page(
            'widget-for-eventbrite-api',
            'Settings',
            /* Page Title */
            'Settings',
            /* Menu Title */
            'manage_options',
            /* Capability */
            'widget-for-eventbrite-api',
            /* Page Slug */
            array( $this, 'settings_page' )
        );
        register_setting(
            $this->option_group,
            /* Option Group */
            "{$this->option_group}-reset",
            /* Option Name */
            array( $this, 'reset_sanitize' )
        );
    }
    
    public function sanitize_settings_1( $settings )
    {
        if ( empty($settings) ) {
            return $settings;
        }
        $options = get_option( 'widget-for-eventbrite-api-settings' );
        
        if ( !isset( $settings['plugin-css'] ) ) {
            $settings['plugin-css'] = 0;
            // always set checkboxes of they dont exist
        }
        
        
        if ( !isset( $settings['cache_duration'] ) ) {
            $settings['cache_duration'] = 86400;
            // always set if they dont exist
        }
        
        
        if ( !isset( $settings['cache_clear'] ) ) {
            $settings['cache_clear'] = 0;
            // always set if they dont exist
        }
        
        $settings['background_api'] = 0;
        if ( 1 == $settings['cache_clear'] ) {
            list( $display_eventbrite, $settings ) = $this->clear_cache( $settings );
        }
        if ( isset( $settings['cache_duration'] ) ) {
            $settings['cache_duration'] = (int) $settings['cache_duration'];
        }
        flush_rewrite_rules();
        $options = get_option( 'widget-for-eventbrite-api-settings' );
        if ( isset( $settings['key'] ) ) {
            
            if ( empty($settings['key'] || empty($options['key'])) ) {
                add_settings_error(
                    'wfea-api-key',
                    'wfea-api-key',
                    esc_html__( 'An API Private Token is required', 'fullworks-security' ),
                    'error'
                );
                $settings['key'] = $options['key'];
                return $settings;
            } elseif ( $settings['key'] != $options['key'] ) {
                // not empty and changed
                // flush transients
                list( $display_eventbrite, $settings ) = $this->clear_cache( $settings );
                // test the new key
                $organizations = $display_eventbrite->request(
                    'organizations',
                    array(
                    'token' => $settings['key'],
                ),
                    false,
                    true
                );
                
                if ( is_wp_error( $organizations ) ) {
                    $msg = $organizations->get_error_message();
                    
                    if ( is_array( $msg ) ) {
                        $text = json_decode( $msg['body'] );
                        $msg = $text->error_description;
                        if ( 'INVALID_AUTH' == $text->error ) {
                            $msg .= esc_html__( ' : instructions on how to find your key are <a href="https://fullworksplugins.com/docs/display-eventbrite-events-in-wordpress/installation-display-eventbrite-events-in-wordpress/connect-to-eventbrite/" target="_blank">here</a> (note a change of Eventbrite password requires a new key)', 'widget-for-eventbrite-api' );
                        }
                        add_settings_error(
                            'wfea-api-key-fail',
                            'wfea-api-key-fail',
                            sprintf( esc_html__( 'Something failed with the key: %1$s', 'fullworks-security' ), $msg ),
                            'error'
                        );
                        $settings['key'] = $options['key'];
                    }
                    
                    
                    if ( is_wp_error( $msg ) ) {
                        $msg = $organizations->get_error_message();
                        if ( !is_string( $msg ) ) {
                            $msg = sprintf( esc_html__( 'A very unexpected error with the API call to check the key happened: %1$s', 'fullworks-security' ), print_r( $msg, true ) );
                        }
                        add_settings_error(
                            'wfea-api-key-fail',
                            'wfea-api-key-fail',
                            sprintf( esc_html__( 'Something failed with the key: %1$s', 'fullworks-security' ), $msg ),
                            'error'
                        );
                        $settings['key'] = $options['key'];
                    }
                    
                    return $settings;
                }
            
            }
        
        }
        $settings['key'] = sanitize_text_field( $settings['key'] );
        return $settings;
    }
    
    private function display_demo_button()
    {
        $plan = 'Free&layout=widget';
        $layout = 'widget';
        $options = get_option( 'widget-for-eventbrite-api-settings' );
        ?>
        <button class="button button-primary"
                onclick="wfea_demo_form()"><?php 
        esc_html_e( 'Go to Shortcode Builder', 'widget-for-eventbrite-api' );
        ?></button>
        <script>
            function wfea_demo_form() {
                document.body.innerHTML += '<form id="dynForm" action="https://fullworksplugins.com/products/widget-for-eventbrite/eventbrite-shortcode-demo" method="post" target="_blank"><input type="hidden" name="layout" value="<?php 
        echo  esc_attr( $layout ) ;
        ?>"><input type="hidden" name="plan" value="<?php 
        echo  esc_attr( $plan ) ;
        ?>"><input type="hidden" name="apikey" value="<?php 
        echo  esc_attr( $options['key'] ) ;
        ?>"><input type="hidden" name="useapi" value="Own API Private Token"></form>';
                document.getElementById("dynForm").submit();
            }
        </script><?php 
    }

}