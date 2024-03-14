<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://chillichalli.com
 * @since      1.0.4
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/public
 */
use  Mailchimp\Mailchimp as mailchimp ;
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/public
 * @author     Christopher Graham <support@chillichalli.com>
 */
class Card_Oracle_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    0.5.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    0.5.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    0.13.0
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    0.27.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Card_Oracle_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Card_Oracle_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name,
            CARD_ORACLE_CSS_URL,
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.1.3
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Card_Oracle_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Card_Oracle_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/card-oracle-public.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }
    
    /**
     * Undocumented function TODO CDG - Is this required.
     *
     * @return void
     */
    public function card_oracle_display_order()
    {
        global  $co_logs ;
        // Regular PayPal IPN.
        co_debug_log( var_export( $_POST ) );
        
        if ( isset( $_POST['action'] ) && 'co_order' === strtolower( $_GET['action'] ) ) {
            co_debug_log( 'paypal-standard.php:card_oracle_display_order: start: POST: ' . wp_json_encode( $_POST, JSON_PRETTY_PRINT ) );
            
            if ( isset( $_POST['item_number'] ) && isset( $_POST['txn_id'] ) && 'Completed' === get_post_meta( $_POST['item_number'], 'payment_status', true ) ) {
                $transaction_id = get_post_meta( $_POST['item_number'], CO_IPN_TXN_ID, true );
                echo  apply_filters( 'the_content', get_the_content( null, false, $_POST['item_number'] ) ) ;
            }
            
            co_debug_log( 'paypal-standard.php:card_oracle_display_order: end' );
        }
    
    }
    
    /**
     * Card Oracle sends an email with the reading via ajax
     *
     * @since  1.1.3
     * @return void
     */
    public function card_oracle_send_reading_email()
    {
        global  $co_logs ;
        $list_id = '';
        co_debug_log( 'class-card-oracle-public.php:card_oracle_send_reading_email: start' );
        // Check the nonce.
        
        if ( !isset( $_POST['security'] ) || !wp_verify_nonce( sanitize_key( $_POST['security'] ), 'card-oracle-send-reader-email' ) ) {
            co_debug_log( 'class-card-oracle-public.php:card_oracle_send_reading_email: end: invalid nonce' );
            wp_die();
        }
        
        
        if ( isset( $_POST['email'] ) ) {
            $to_email = sanitize_email( wp_unslash( $_POST['email'] ) );
            // Get the email address from the POST.
            $subject = esc_html__( 'Your Reading', 'card-oracle' );
            // Set the email subject.
            co_debug_log( 'class-card-oracle-public.php:card_oracle_send_reading_email: $_POST email: to_email [' . $to_email . '], subject [' . $subject . ']' );
            // Create the headers. Add From name and address if options are set.
            $headers = card_oracle_get_from_email_address() . "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n";
            // @codingStandardsIgnoreLine
            // If the emailcontent is set and the transient exists then send the email.
            
            if ( isset( $_POST['emailcontent'] ) ) {
                $allowed_html = card_oracle_allowed_html();
                $email_body = rawurldecode( wp_kses( wp_unslash( $_POST['emailcontent'] ), $allowed_html ) );
                co_debug_log( 'class-card-oracle-public.php:card_oracle_send_reading_email: headers [' . $headers . '], to_email [' . $to_email . '], subject [' . $subject . '], email_body [' . $email_body . ']' );
                $result = wp_mail(
                    $to_email,
                    $subject,
                    $email_body,
                    $headers
                );
                co_debug_log( 'class-card-oracle-public.php:card_oracle_send_reading_email: wp_mail result [' . $result . ']' );
                $email_success = ( get_option( 'card_oracle_email_success' ) ? get_option( 'card_oracle_email_success' ) : esc_html__( 'Your email has been sent. Please make sure to check your spam folder.', 'card-oracle' ) );
                wp_send_json_success( $email_success, 'readingsend' );
            }
        
        }
        
        co_debug_log( 'class-card-oracle-public.php:card_oracle_send_reading_email: end' );
        wp_die();
    }
    
    /**
     * Card Oracle shortcode to display reading
     *
     * @since 1.1.6
     * @param string $atts This is the ID of the Reading.
     * @return string html for display.
     */
    public function display_card_oracle_card_of_day( $atts )
    {
        $daily_card = get_transient( CARD_ORACLE_DAILY_CARD );
        
        if ( false === $daily_card ) {
            // Can not change the Transient Time unless you delete the _transient_timeout_ option for the transient.
            delete_option( '_transient_timeout_' . CARD_ORACLE_DAILY_CARD );
            $daily_card = '';
            // If the reading id is not set return.
            
            if ( empty($atts['id']) ) {
                $daily_card .= '<p>';
                $daily_card .= esc_html__( 'This Reading is missing. Please contact the site administrator.', 'card-oracle' );
                $daily_card .= '<p>';
                return $daily_card;
            }
            
            $reading_id = $atts['id'];
            $card_ids = get_cards_for_reading( $reading_id );
            
            if ( empty($card_ids) ) {
                $daily_card .= '<p>';
                $daily_card .= esc_html__( 'This Daily Card is missing. Please contact the site administrator.', 'card-oracle' );
                $daily_card .= '<p>';
                return $daily_card;
            }
            
            $index = gmdate( 'z' ) % max( count( $card_ids ), 1 );
            $card_of_day = get_post( $card_ids[$index] );
            $image = '<img src="' . wp_get_attachment_url( get_post_thumbnail_id( $card_of_day ) ) . '" loading="lazy">';
            $footer = get_post_meta( $reading_id, 'footer_text', true );
            
            if ( isset( $atts['email'] ) ) {
                $daily_card .= '<table class="card-oracle-table"><thead>';
                $daily_card .= '<tr><th colspan="2"><h1>' . $card_of_day->post_title . '</h1></th></tr>';
                $daily_card .= '</thead><tbody>';
                $daily_card .= '<tr><td width="200" valign="top">' . $image . '</td><td style="vertical-align:top">' . apply_filters( 'the_content', $card_of_day->post_content ) . '</td></tr>';
                if ( !empty($footer) ) {
                    $daily_card .= '<tr colspan="2"><td>' . $footer . '</td></tr>';
                }
                
                if ( get_option( 'card_oracle_powered_by' ) ) {
                    $powered_link = '<a href="https://chillichalli.com/card-oracle">ChilliChalli.com</a>';
                    /* Translators: %s is a website URL */
                    $powered = sprintf( __( 'Create your own reading using Tarot Card Oracle! Go to %s', 'card-oracle' ), $powered_link );
                    $daily_card .= '<tr colspan="2"><td>' . $powered . '</td></tr>';
                }
                
                $daily_card .= '</tbody></table>';
            } else {
                $daily_card .= '<div class="cotd-wrapper alignwide">
					<div class="cotd-header">' . $card_of_day->post_title . '</div>
					<div class="cotd-main">' . $card_of_day->post_content . '</div>';
                $daily_card .= '<div class="cotd-aside">' . $image . '</div>';
                $daily_card .= card_oracle_display_footer( $footer );
                $daily_card .= '</div>';
            }
            
            // Time until midnight.
            $midnight_time = strtotime( 'tomorrow' ) - time();
            set_transient( CARD_ORACLE_DAILY_CARD, $daily_card, $midnight_time );
        }
        
        return $daily_card;
    }
    
    // End display_card_oracle_card_of_day.
    /**
     * Card Oracle shortcode to display random card
     *
     * @since 1.1.6
     * @param array $atts Shortcode attributes.
     * @return string HTML for display.
     */
    public function display_card_oracle_random_card( $atts )
    {
        $random_card = get_transient( CARD_ORACLE_RANDOM_CARD );
        
        if ( false === $random_card ) {
            delete_transient( CARD_ORACLE_RANDOM_CARD );
            $random_card = '';
            
            if ( empty($atts['id']) ) {
                $random_card .= '<p>' . esc_html__( 'This Reading is missing. Please contact the site administrator.', 'card-oracle' ) . '</p>';
                return $random_card;
            }
            
            $reading_id = $atts['id'];
            $card_ids = get_cards_for_reading( $reading_id );
            
            if ( empty($card_ids) ) {
                $random_card .= '<p>' . esc_html__( 'This Random Card is missing. Please contact the site administrator.', 'card-oracle' ) . '</p>';
                return $random_card;
            }
            
            $card_count = count( $card_ids ) - 1;
            $card_of_day = get_post( $card_ids[wp_rand( 0, $card_count )] );
            $image = '<img src="' . wp_get_attachment_url( get_post_thumbnail_id( $card_of_day ) ) . '" loading="lazy">';
            $footer = get_post_meta( $reading_id, 'footer_text', true );
            $random_card .= '<div class="cotd-wrapper alignwide"><div class="cotd-header">' . $card_of_day->post_title . '</div><div class="cotd-main">' . $card_of_day->post_content . '</div><div class="cotd-aside">' . $image . '</div>';
            $random_card .= card_oracle_display_footer( $footer );
            $random_card .= '</div>';
            $days = (int) get_option( CARD_ORACLE_RANDOM_DAYS, 0 );
            $time = ( 0 === $days ? 600 : $days * DAY_IN_SECONDS );
            set_transient( CARD_ORACLE_RANDOM_CARD, $random_card, $time );
        }
        
        return $random_card;
    }
    
    /**
     * Card Oracle shortcode to display reading
     *
     * @since 1.1.3
     * @param string $atts This is the ID of the Reading.
     * @return string html for display.
     */
    public function display_card_oracle_set( $atts )
    {
        global  $payment_options ;
        $description_html = '';
        $page_display = '';
        $paid_display = '';
        $paid_reading = false;
        // If the id is not set return.
        if ( empty($atts['id']) ) {
            return;
        }
        $reading_id = $atts['id'];
        
        if ( isset( $_GET['payment'] ) && 'paid' === $_GET['payment'] ) {
            if ( isset( $_GET['id'] ) && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'display-reading_' . $_GET['id'] ) ) {
                return apply_filters( 'the_content', get_the_content( null, false, wp_unslash( $_GET['id'] ) ) );
            }
            $redirect = esc_url( remove_query_arg( array( 'payment', 'id', '_wpnonce' ) ) );
            wp_safe_redirect( $redirect );
        }
        
        // Start output buffering.
        ob_start();
        // Check that the reading exists, if not return.
        
        if ( 'publish' !== get_post_status( $reading_id ) ) {
            echo  '<p>' ;
            esc_html_e( 'This Reading is missing. Please contact the site administrator.', 'card-oracle' );
            echo  '<p>' ;
            return ob_get_clean();
        }
        
        // Get all the presentation layout settings.
        $presentation_layouts = get_presentation_layouts();
        // Get the presentation layout for this reading. If it is not set then set it to standard.
        $presentation = ( get_post_meta( $reading_id, CO_PRESENTATION_LAYOUT, true ) ? get_post_meta( $reading_id, CO_PRESENTATION_LAYOUT, true ) : 'standard' );
        // Get all the positions for this reading.
        $positions = get_positions_for_reading( $reading_id );
        // Get the number of positions for this reading type.
        $positions_count = count( $positions );
        // Get the key of the Presentation array to be used to get the filename.
        $key = array_search( $presentation, array_column( $presentation_layouts, 'uid' ), true );
        // Get the filename from the Presentation array in get_presentation_layouts function.
        $filename = $presentation_layouts[$key]['file'];
        // Include the specific Presentation Layout file.
        include_once $filename;
        // Get the output buffer contents.
        $page_display = ob_get_clean();
        // Create the Order post if required.
        
        if ( $paid_reading ) {
            co_debug_log( 'Description HTML [' . $page_display . ']' );
            $order_data = array(
                'post_content' => $description_html,
                'meta_input'   => array(
                CO_AMOUNT       => $payment_options['price'],
                CO_ORDER_STATUS => esc_html__( 'New', 'card-oracle' ),
            ),
            );
            $payment_options['item_number'] = card_oracle_insert_order( $order_data );
            co_debug_log( 'item number [' . $payment_options['item_number'] . '], amount [' . $payment_options['price'] . '], item_name [' . $payment_options['item_name'] . ']' );
            // Add the sales text to the page.
            $sale_text = get_post_meta( $reading_id, CO_SALES_TEXT, true );
            if ( $sale_text ) {
                $paid_display .= '<div class="card-oracle-sales-text"><p>' . $sale_text . '</p></div>';
            }
            if ( isset( $payment_options['shortcode'] ) ) {
                $paid_display .= do_shortcode( $payment_options['shortcode'] );
            }
            return $paid_display;
        }
        
        return $page_display;
    }

}
// End Class Card_Oracle_Public.