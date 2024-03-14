<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Admin_Dashboard_Widget {

    public function __construct() {
        add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );
        add_action( 'admin_head', array( $this, 'admin_head_css' ) );
    }

    public function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'tochatbe_log_dashboard_widget',
            'TOCHAT.BE Analytics',
            array( $this, 'dashboard_analytics' )
        );
    }

    public function dashboard_analytics() {
        ?>
        <img class="tochatbe-dashboard-widget-logo" src="<?php echo TOCHATBE_PLUGIN_URL . 'assets/images/logo-full.png' ?>" alt="//" width="140">
        <p>Contacts you have generated with the WhatsApp Widget. <br>Click <a href="<?php echo admin_url( 'admin.php?page=to-chat-be-whatsapp_click-log' ); ?>">here</a> to see full click log.</p>
        <hr>
        <div class="tochatbe-dashboard-widget">
            <div>
                <p>Today</p><span><?php echo esc_html( TOCHATBE_Log::get_total_day_click() ); ?></span>
            </div>
            <div>
                <p>Last Week</p><span><?php echo esc_html( TOCHATBE_Log::get_this_week_click() ); ?></span>
            </div>
            <div>
                <p>Last Month</p><span><?php echo esc_html( TOCHATBE_Log::get_this_month_click() ); ?></span>
            </div>
            <div>
                <p>Total</p><span><?php echo esc_html( TOCHATBE_Log::get_total_click() ); ?></span>
            </div>
        </div>
        <hr>
        <p>Learn to sell with <a href="https://tochat.be/click-to-chat/whatsapp-academy/" target="_blank">WhatsApp Academy</a></p>
        <p>Make your <a href="https://tochat.be/click-to-chat/2020/01/25/how-the-whatsapp-plugin-works-in-wordpress/" target="_blank">WhatsApp Widget Amazing</a></p>
        <?php
    }

    public function admin_head_css() {
        $current_Screen = get_current_screen();
        
        if ( 'dashboard' !== $current_Screen->id ) {
            return;
        }

        ?>
        <style>
            .tochatbe-dashboard-widget {
                display: flex;
            }

            .tochatbe-dashboard-widget > div {
                width: 25%;
                border-right: 1px solid #ccc;
            }

            .tochatbe-dashboard-widget > div:last-child {
                border-right: 1px solid transparent;
            }

            .tochatbe-dashboard-widget > div > p {
                font-weight: 700;
                text-align: center;
            }

            .tochatbe-dashboard-widget > div > span{
                display: block;
                text-align: center;
            }

            .tochatbe-dashboard-widget-logo {
                display: block;
                margin: 0 auto;
            }
        </style>
        <?php
    }

}

new TOCHATBE_Admin_Dashboard_Widget;