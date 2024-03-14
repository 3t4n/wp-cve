<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WP_School_Calendar_Getting_Started {

    private static $_instance = NULL;

    /**
     * Initialize all variables, filters and actions
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 100 );
    }

    /**
     * retrieve singleton class instance
     * @return instance reference to plugin
     */
    public static function instance() {
        if ( NULL === self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * Add Tools menu page
     * 
     * @since 1.0
     */
    public function admin_menu() {
        add_submenu_page( 'edit.php?post_type=school_calendar', __( 'Getting Started', 'wp-school-calendar' ), __( 'Getting Started', 'wp-school-calendar' ), 'manage_options', 'wpsc-getting-started', array( $this, 'admin_page' ) );
    }
    
    /**
     * Display admin page
     * 
     * @since 1.0
     */
    public function admin_page() {
        ?>
        <div class="wrap wpsc-getting-started">
            <h1><?php echo esc_html__( 'Welcome to WP School Calendar', 'wp-school-calendar' );?></h1>
            
            <div class="wpsc-desc"><?php echo esc_html__( 'Thank you for using WP School Calendar, a powerful and easy-to-use WordPress plugin to create school calendar. Our goal is to help you create beautiful responsive school calendar for your school website in minutes without hiring a graphic designer.', 'wp-school-calendar' );?></div>
            
            <div class="wpsc-container">
            
                <div class="wpsc-sidebar">
                    <div class="wpsc-widget">
                        <h2><?php echo esc_html__( 'Live Demo', 'wp-school-calendar' ); ?></h2>
                        <p><?php echo esc_html__( 'Check out the WP School Calendar live demo to see the powerful features of WP School Calendar.', 'wp-school-calendar' ) ?></p>
                        <p><a class="wpsc-button" href="https://app.sorsawo.com/wpsc/" target="_blank"><?php echo esc_html__( 'See Live Demo', 'wp-school-calendar' ) ?></a></p>
                    </div>                    
                    <div class="wpsc-widget">
                        <h2><?php echo esc_html__( 'Documentation', 'wp-school-calendar' ); ?></h2>
                        <p><?php echo esc_html__( 'Check out the WP School Calendar documentation to learn how to create your first school calendar.', 'wp-school-calendar' ) ?></p>
                        <p><a class="wpsc-button" href="https://sorsawo.com/en/docs/wordpress-school-calendar/" target="_blank"><?php echo esc_html__( 'See Documentation', 'wp-school-calendar' ) ?></a></p>
                    </div>
                </div>

                <div class="wpsc-content"><div class="wpsc-content-inner">

                <h2><?php echo __( 'What is WP School Calendar', 'wp-school-calendar' ) ?></h2>
                        
                <p><img class="full-width" src="<?php echo WPSC_PLUGIN_URL . 'assets/images/wp-school-calendar.png' ?>"></p>

                <p><?php echo __( 'WP School Calendar is simple and responsive school calendar plugin for WordPress. You can use this plugin to create school calendar automatically and show it on your school website.', 'wp-school-calendar' ) ?></p>

                <p><?php echo __( 'It is very easy to use WP School Calendar. You only need to add important dates on the calendar when the activities or events will be held in the school, such as:', 'wp-school-calendar' ) ?></p>
                
                <ul>
                    <li><?php echo __( 'Registration for new students.', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Orientation period for new students.', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'First and last day of courses.', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Mid and final term examination.', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Results publication date.', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'School holiday period.', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Or other important dates.', 'wp-school-calendar' ) ?></li>
                </ul>
                
                <p><?php echo __( 'Then you can display the calendar on your WordPress page using shortcode or Gutenberg block.', 'wp-school-calendar' ) ?></p>

                <h2><?php echo __( 'Why WP School Calendar', 'wp-school-calendar' ) ?></h2>

                <p><?php echo __( 'Over the years, we found that creating the school calendar is very difficult and not everyone can do that because they don\'t have skill in graphic designing. You have to hiring a graphic designer to create school calendar for your school website. So we started to develop WP School Calendar with simple goal to take the pain out of creating school calendar and make it easy.', 'wp-school-calendar' ) ?></p>

                <h2><?php echo __( 'Features', 'wp-school-calendar' ) ?></h2>

                <p><?php echo __( 'Here are main features of WP School Calendar:', 'wp-school-calendar' ) ?></p>

                <ul>
                    <li><?php echo __( 'Supports single and multiple day important date', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Categorized important dates', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Custom colors for each important dates', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Customize start / end of the school calendar', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Ability to create multiple school calendar', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Various options for month format', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Various options for weekday format', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Various options for date format', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Various options for calendar themes (Pro Version)', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Ability to create recurring important date (Pro Version)', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Ability to show tooltip on important dates (Pro Version)', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Ability to show past and upcoming school calendars (Pro Version)', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Ability to export school calendar into PDF format (Pro Version)', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Ability to export school calendar to iCalendar format (Pro Version)', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Ability to add school calendar to Google Calendar (Pro Version)', 'wp-school-calendar' ) ?></li>
                    <li><?php echo __( 'Custom language text support and includes POT file for further customization', 'wp-school-calendar' ) ?></li>
                </ul>
                
                <h2><?php echo __( 'Create Calendar', 'wp-school-calendar' ) ?></h2>
                
                <p><?php printf( __( 'First, go to <a href="%s">School Calendar &gt; Calendars</a>. This page will show the list of calendar on your WordPress site. To create new calendar, click Add New button.', 'wp-school-calendar' ), admin_url( 'edit.php?post_type=school_calendar' ) ) ?></p>
                
                <p><img class="full-width" src="<?php echo WPSC_PLUGIN_URL . 'assets/images/builder.png' ?>"></p>
                
                <p><?php echo __( 'Next, you can customize the appearance of your calendar using the calendar builder.', 'wp-school-calendar' ) ?></p>
                
                <h2><?php echo __( 'Display Calendar on Your Site', 'wp-school-calendar' ) ?></h2>
                
                <p><?php echo __( 'To begin, you will need to create a new WordPress page or edit an existing one. Once, you have opened the editor, you can add a new block by clicking the + (plus) icon in the upper left corner.', 'wp-school-calendar' ) ?></p>
                
                <p><img src="<?php echo WPSC_PLUGIN_URL . 'assets/images/gutenberg-plus.png' ?>"></p>

                <p><?php echo __( 'Once you have clicked this icon, a menu of block options will display. To locate the WP School Calendar block, you can search WP School Calendar or open the Widgets category. Then click the block named WP School Calendar.', 'wp-school-calendar' ) ?></p>
                
                <p><img src="<?php echo WPSC_PLUGIN_URL . 'assets/images/calendar-block.png' ?>"></p>
                
                <p><?php echo __( 'This will add the WP School Calendar block to the editor screen. Choose the calendar from the dropdown menu. Publish the page and visit your website to see your calendar in action.', 'wp-school-calendar' ) ?></p>
                
                <p><?php echo __( 'You can also use shortcode to display calendar on your site. All you will need to do is add <code>[wp_school_calendar id="YOUR_CALENDAR_ID"]</code> to any page or post.', 'wp-school-calendar' ) ?></p>
                
                <p><?php printf( __( 'The next step, go to <a href="%s">School Calendar &gt; Important Dates</a> to add some important dates to your calendar.', 'wp-school-calendar' ), admin_url( 'edit.php?post_type=important_date' ) ) ?></p>
                
                <h2><?php echo __( 'Frequently Asked Questions', 'wp-school-calendar' ) ?></h2>

                <p><?php echo __( 'Do you have a question about WP School Calendar? See the list below for our most frequently asked questions. If your question is not listed here, then please contact us.', 'wp-school-calendar' ) ?></p>

                <h3><?php echo __( 'Who should use WP School Calendar?', 'wp-school-calendar' ) ?></h3>

                <p><?php echo __( 'WP School Calendar is perfect for education websites. If you want to create a school or university website, then you need to use WP School Calendar.', 'wp-school-calendar' ) ?></p>

                <h3><?php echo __( 'What\'s required to use WP School Calendar?', 'wp-school-calendar' ) ?></h3>

                <p><?php echo __( 'WP School Calendar is a WordPress Plugin. In order to use WP School Calendar, you must have a self-hosted WordPress site. That\'s all.', 'wp-school-calendar' ) ?></p>

                <h3><?php echo __( 'Do I need coding skills to use WP School Calendar?', 'wp-school-calendar' ) ?></h3>

                <p><?php echo __( 'Absolutely not. You can create and manage school calendar without any coding knowledge.', 'wp-school-calendar' ) ?></p>

                <h3><?php echo __( 'Will WP School Calendar slow down my website?', 'wp-school-calendar' ) ?></h3>

                <p><?php echo __( 'Absolutely not. WP School Calendar is carefully built with performance in mind. We have developed everything with best practices and modern standards to ensure things run smooth and fast.', 'wp-school-calendar' ) ?></p>

                <h3><?php echo __( 'Does WP School Calendar work on non-WordPress sites?', 'wp-school-calendar' ) ?></h3>

                <p><?php echo __( 'No. WP School Calendar is a WordPress plugin, so it will NOT work on sites that do not use WordPress. Additionally, WP School Calendar is not compatible with the WordPress.com platform. You must be using a self-hosted version of WordPress to utilize WP School Calendar.', 'wp-school-calendar' ) ?></p>

                <h3><?php echo __( 'Can I use WP School Calendar on client sites?', 'wp-school-calendar' ) ?></h3>

                <p><?php echo __( 'Absolutely. You can use WP School Calendar Pro on your client sites. You can purchase Single Site License for each client sites or Multi-Site License for some clients.', 'wp-school-calendar' ) ?></p>

                <h3><?php echo __( 'Do your plugin work with mobile devices?', 'wp-school-calendar' ) ?></h3>

                <p><?php echo __( 'WP School Calendar display beautifully on mobile devices. The plugin is specifically designed to be "mobile responsive", allowing your plugin to automatically adapt to any mobile device on the market.', 'wp-school-calendar' ) ?></p>

                <h3><?php echo __( 'Can your plugin be used in different languages?', 'wp-school-calendar' ) ?></h3>

                <p><?php echo __( 'Yes. WP School Calendar has full translation and localization support via the "wp-school-calendar" textdomain. All .mo and .po translation files should go into the languages folder in the base of the plugin.', 'wp-school-calendar' ) ?></p>

                <h3><?php echo __( 'Do you provide Demo Site?', 'wp-school-calendar' ) ?></h3>

                <p><?php printf( __( 'Yes. You can look the demo page <a href="%s" target="_blank">here</a>.', 'wp-school-calendar' ), 'https://app.sorsawo.com/wpsc/' ) ?></p>

                </div></div>
            </div>
        </div>
        <?php
    }
}

WP_School_Calendar_Getting_Started::instance();
