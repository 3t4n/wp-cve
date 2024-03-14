<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


class Blockspare_Setup
{
    public $name;
    public $type;
    public $dismiss_url;
    public $temporary_dismiss_url;
    public $pricing_url;
    public $current_user_id;

    /**
     * The constructor.
     *
     * @param string $name Notice Name.
     * @param string $type Notice type.
     * @param string $dismiss_url Notice permanent dismiss URL.
     * @param string $temporary_dismiss_url Notice temporary dismiss URL.
     *
     * @since 1.4.7
     *
     */
    public function __construct($name, $type, $dismiss_url, $temporary_dismiss_url)
    {
        $this->name = $name;
        $this->type = $type;
        $this->dismiss_url = $dismiss_url;
        $this->temporary_dismiss_url = $temporary_dismiss_url;
        $this->pricing_url = 'https://www.blockspare.com/pricing/';
        $this->current_user_id = get_current_user_id();

        // Notice markup.
        add_action('admin_notices', array($this, 'notice'));

        $this->dismiss_notice();
        $this->dismiss_notice_temporary();
    }

    public function notice()
    {

        $current_screen = get_current_screen();
        if ( $current_screen->id !=='dashboard' && $current_screen->id !== 'themes' && $current_screen->id !=='plugins' ) {
            
			return;
		}
        
        if (!$this->is_dismiss_notice()) {
            
            $this->notice_markup();
        }
    }

    private function is_dismiss_notice()
    {
        return apply_filters('blockspare_' . $this->name . '_notice_dismiss', true);
    }

    public function notice_markup()
    {
        echo '';
    }

    /**
     * Hide a notice if the GET variable is set.
     */
    public function dismiss_notice()
    {
        if (isset($_GET['blockspare_setup_notice_dismiss']) && isset($_GET['_blockspare_setup_notice_dismiss_nonce'])) { // WPCS: input var ok.
            if (!wp_verify_nonce(wp_unslash($_GET['_blockspare_setup_notice_dismiss_nonce']), 'blockspare_setup_notice_dismiss_nonce')) { // phpcs:ignore WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
                wp_die(__('Action failed. Please refresh the page and retry.', 'blockspare')); // WPCS: xss ok.
            }

            if (!current_user_can('publish_posts')) {
                wp_die(__('Cheatin&#8217; huh?', 'blockspare')); // WPCS: xss ok.
            }

            $dismiss_notice = sanitize_text_field(wp_unslash($_GET['blockspare_setup_notice_dismiss']));

            // Hide.
            if ($dismiss_notice === $_GET['blockspare_setup_notice_dismiss']) {
                add_user_meta(get_current_user_id(), 'blockspare_' . $dismiss_notice . '_notice_dismiss', 'yes', true);
            }
        }
    }

    public function dismiss_notice_temporary()
    {
        if (isset($_GET['blockspare_setup_notice_dismiss_temporary']) && isset($_GET['_blockspare_setup_notice_dismiss_temporary_nonce'])) { // WPCS: input var ok.
            if (!wp_verify_nonce(wp_unslash($_GET['_blockspare_setup_notice_dismiss_temporary_nonce']), 'blockspare_setup_notice_dismiss_temporary_nonce')) { // phpcs:ignore WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
                wp_die(__('Action failed. Please refresh the page and retry.', 'blockspare')); // WPCS: xss ok.
            }

            if (!current_user_can('publish_posts')) {
                wp_die(__('Cheatin&#8217; huh?', 'blockspare')); // WPCS: xss ok.
            }

            $dismiss_notice = sanitize_text_field(wp_unslash($_GET['blockspare_setup_notice_dismiss_temporary']));

            // Hide.
            if ($dismiss_notice === $_GET['blockspare_setup_notice_dismiss_temporary']) {
                add_user_meta(get_current_user_id(), 'blockspare_' . $dismiss_notice . '_notice_dismiss_temporary', 'yes', true);
            }
        }
    }
}


class Blockspare_Setup_Notice extends Blockspare_Setup {

    public function __construct() {
        if ( ! current_user_can( 'publish_posts' ) ) {
            return;
        }

        $dismiss_url = wp_nonce_url(
            add_query_arg( 'blockspare_setup_notice_dismiss', 'setup', admin_url() ),
            'blockspare_setup_notice_dismiss_nonce',
            '_blockspare_setup_notice_dismiss_nonce'
        );

        $temporary_dismiss_url = wp_nonce_url(
            add_query_arg( 'blockspare_setup_notice_dismiss_temporary', 'setup', admin_url() ),
            'blockspare_setup_notice_dismiss_temporary_nonce',
            '_blockspare_setup_notice_dismiss_temporary_nonce'
        );

        parent::__construct( 'setup', 'info', $dismiss_url, $temporary_dismiss_url );

        $this->set_notice_time();

        $this->set_temporary_dismiss_notice_time();

        $this->set_dismiss_notice();
    }

    private function set_notice_time() {
        if ( ! get_option( 'blockspare_setup_notice_start_time' ) ) {
            update_option( 'blockspare_setup_notice_start_time', time() );
        }
    }

    private function set_temporary_dismiss_notice_time() {
        if ( isset( $_GET['blockspare_setup_notice_dismiss_temporary'] ) && 'setup' === $_GET['blockspare_setup_notice_dismiss_temporary'] ) {
            update_user_meta( $this->current_user_id, 'blockspare_setup_notice_dismiss_temporary_start_time', time() );
        }
    }

    public function set_dismiss_notice() {

        /**
         * Do not show notice if:
         *
         * 1. It has not been 5 days since the plugin is activated.
         * 2. If the user has ignored the message partially for 2 days.
         * 3. Dismiss always if clicked on 'Dismiss' button.
         */
        if ( get_option( 'blockspare_setup_notice_start_time' ) > strtotime( '-1 minute' )
            || get_user_meta( get_current_user_id(), 'blockspare_setup_notice_dismiss', true )
            || get_user_meta( get_current_user_id(), 'blockspare_setup_notice_dismiss_temporary_start_time', true ) > strtotime( '-2 days' )
        ) {
            add_filter( 'blockspare_setup_notice_dismiss', '__return_true' );
        } else {
            add_filter( 'blockspare_setup_notice_dismiss', '__return_false' );
        }
    }

    public function notice_markup() {
        if ( is_admin() ) {
            if( ! function_exists('get_plugin_data') ){
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            $plugin_data = get_plugin_data( BLOCKSPARE_BASE_FILE );       
            
        }

        ?>
        <div class="notice notice-success blockspare-notice-setup" >
            <div class="bs-welcome-panel-content">
                <div class="bs-welcome-panel-header">
                    <div class="blockspare-notice-info">
                        <div class='bs-welcome-panel-header-logo'>
                            <svg fill="url(#blockspare-gradient)" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" class="blockspare-svg blockspare-svg-blockspare" aria-hidden="true" focusable="false"><g class="blockspare-logo"><g><path class="st0" d="M1.6,9.2v21.4l18.3-9.2L20.2,0L1.6,9.2z M16.9,19.8L4.9,26V10.9l12-6.1V19.8z"></path><polygon id="XMLID_3_" class="st1" points="19.9,21.4 16.9,23 26,27.7 13.8,33.8 4.9,29 1.6,30.7 13.9,36.9 32.4,27.7"></polygon></g><g><polygon id="XMLID_2_" class="st0" points="23,1.5 23,19.8 32.4,24.6 32.4,9.4 35.3,10.9 35.3,29 38.4,30.7 38.4,9.2 29.4,4.8  29.2,19.7 26,18.4 26,3.1"></polygon><polygon id="XMLID_1_" class="st1" points="17,38.4 19.9,40 38.4,30.7 35.3,29"></polygon></g></g></svg>
                            <div class="blockspare-version"><?php esc_html_e('Blockspare', 'blockspare'); ?> <span><?php echo $plugin_data['Version'];?></span></div>
                        </div>
                        <h2 class="blockspare-notice-title"><?php esc_html_e('Effortless Site Creation in Minutes','blockspare'); ?></h2>
                        <p class="blockspare-notice-description"><?php esc_html_e('Expert Templates from our Design Library - Import, Customize, Publish with Ease!', 'blockspare'); ?></p>  
                        <div class='blockspare-notice-buttons'>
                            <a  href="<?php echo admin_url('post-new.php?post_type=page&blockspare_show_intro=true')?>" target="_blank" class="blockspare-notice-button blockspare-notice-button-primary"><span class="dashicons dashicons-plus"></span> <?php esc_html_e('Get Started', 'blockspare'); ?></a> 
                            <a  href="<?php echo admin_url('admin.php?page=blockspare')?>" class="blockspare-notice-button blockspare-notice-button-secondary"><span class="dashicons dashicons-welcome-learn-more"></span> <?php esc_html_e('Starter Templates', 'blockspare'); ?></a> 
                        </div>                      
                    </div>
                    <div class="bs-welcome-panel-header-image">
                        <img class='blockspare-notice-image' src='<?php echo BLOCKSPARE_PLUGIN_URL ."admin/assets/images/notice-background.webp";?>' height="200" width="200"/>
                    </div>
                </div>
                <div class='bs-welcome-panel-column-container'>
                        <div class='bs-welcome-panel-column'>
                            <div class='bs-welcome-panel-column-icon'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 60 60">
                                    <path d="M5 60h34a3 3 0 0 0 3-3v-5.171A3.006 3.006 0 0 0 44 49V36.6a3.278 3.278 0 0 0 .3-.243 3.164 3.164 0 0 0 .9-2.578 2.208 2.208 0 0 0 .814-.51c4.454-4.453 10.208-12.031 13.242-16.151a3.838 3.838 0 0 0-5.367-5.367c-2.509 1.848-6.3 4.7-9.885 7.661V3a3 3 0 0 0-3-3H5a5.006 5.006 0 0 0-5 5v50a5.006 5.006 0 0 0 5 5Zm33.388-23.584a3.318 3.318 0 0 1-.956 2.485c-1.885 1.884-4.17 2.441-7.852 1.9a12.564 12.564 0 0 0 1.2-3.8 6.528 6.528 0 0 1 1.32-3.433 3.261 3.261 0 0 1 2.485-.956 3.9 3.9 0 0 1 2.607 1.143l.054.054a3.9 3.9 0 0 1 1.142 2.607Zm-2.233-6.525a1.2 1.2 0 0 1 0-1.68 1.188 1.188 0 0 1 1.67 0l5.057 5.057a1.181 1.181 0 0 1 0 1.67 1.227 1.227 0 0 1-1.679 0ZM55.07 13.359a1.838 1.838 0 0 1 2.571 2.571c-2.415 3.28-8.522 11.4-13.045 15.922a.215.215 0 0 1-.3 0l-5.147-5.147a.213.213 0 0 1 0-.3c4.522-4.524 12.641-10.631 15.921-13.046ZM42 3v18.09c-1.566 1.342-3.037 2.671-4.266 3.9a2.181 2.181 0 0 0-.541.921 3.14 3.14 0 0 0-2.948 4.729 5.273 5.273 0 0 0-3.56 1.514 8.152 8.152 0 0 0-1.865 4.46 10.088 10.088 0 0 1-1.072 3.359 1.8 1.8 0 0 0-.09 1.7 1.885 1.885 0 0 0 1.423 1.073 19.294 19.294 0 0 0 2.948.258 9.152 9.152 0 0 0 6.817-2.692 5.25 5.25 0 0 0 1.511-3.512 3.166 3.166 0 0 0 1.643.478V49a1 1 0 0 1-1 1H6V2h35a1 1 0 0 1 1 1ZM4 2.184V50.1a5 5 0 0 0-2 .923V5a3 3 0 0 1 2-2.816Zm-1.128 50.7A3.018 3.018 0 0 1 5 52h35v5a1 1 0 0 1-1 1H5a3 3 0 0 1-2.128-5.118Z" data-original="#000000"/>
                                    <path d="M11 21h26a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2H11a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2Zm0-12h26v10H11Zm18 47h5a1 1 0 0 0 0-2h-5a1 1 0 0 0 0 2ZM9 56h16a1 1 0 0 0 0-2H9a1 1 0 0 0 0 2Zm22-31H17a1 1 0 0 0 0 2h14a1 1 0 0 0 0-2Zm-3 5h-8a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2Z" data-original="#000000"/>
                                </svg>
                            </div>
                            <div class='bs-welcome-panel-column-content'>
                                <h3><?php esc_html_e('Launch Your Site in a Few Clicks!','blockspare'); ?></h3>
                                
                                
                                <a href="<?php echo admin_url('post-new.php?post_type=page&blockspare_show_intro=true')?>" target="_blank" class="blockspare-notice-link"><?php esc_html_e('Try Now','blockspare'); ?></a>
                            </div>
                        </div>

                        <div class='bs-welcome-panel-column'>
                            <div class='bs-welcome-panel-column-icon'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 64 64">
                                    <path d="M59.44 6.83a3.067 3.067 0 0 0-2.66-2.5c-2.61-.31-5.29-.26-7.95.1C39.54 5.65 30.95 11 25.48 18.71l-4.42-1.44c-1.69-.55-3.58.03-4.71 1.43l-4.69 5.83c-1.77 2.08-.99 5.56 1.55 6.6l6.49 2.84c-1.74 1.58-3.04 3.59-3.87 5.11-.39.72-.33 1.58.15 2.24.6.85 1.66.96 2.6.69-.66 1.55-1.26 3.13-1.79 4.77a2.072 2.072 0 0 0 2.74 2.57c1.61-.63 3.15-1.33 4.65-2.08-.4 1.36.48 2.92 1.98 2.9.38 0 .77-.11 1.11-.33 1.47-.92 3.39-2.35 4.85-4.18l3.24 6.31c1.19 2.47 4.71 3.04 6.68 1.14l5.53-5.04c1.33-1.21 1.78-3.14 1.13-4.79l-1.71-4.33c9.47-7.55 14.62-20.28 12.42-32.13zm-1.97.35c.65 3.68.59 7.48-.12 11.15-4.1-3.46-8.21-7.33-11.92-11.21 3.14-.81 6.4-1.14 9.64-.94.85.12 2.19-.12 2.41 1zM12.75 27.8c-.17-.71 0-1.44.46-2.02l4.69-5.83c.61-.76 1.62-1.07 2.53-.78l3.86 1.25a35.241 35.241 0 0 0-4.6 11.36L14 29.29c-.63-.28-1.09-.82-1.25-1.49zm25.86 14.04c-1.76.81-3.6 1.45-5.48 1.91l-11.59-10.9c.39-2.16 1.03-4.27 1.85-6.3l15.92 14.97c-.23.11-.46.21-.7.32zm-12.39 6.31-.12-.08c.14-1.14.64-3.99-1.34-3.33-1.94 1.01-3.88 1.94-5.96 2.75l-.1-.1c.68-2.11 1.49-4.12 2.38-6.11.54-2.02-2.27-1.35-3.41-1.13l-.09-.11c.78-1.43 2.01-3.34 3.62-4.74l9.52 8.95c-1.3 1.7-3.12 3.05-4.5 3.92zm20.02-1.56-5.53 5.04c-1.05 1.01-2.92.74-3.56-.58l-2.84-5.53c2.46-.69 4.86-1.62 7.11-2.83 1.36-.74 2.68-1.56 3.95-2.46L46.86 44c.35.88.1 1.92-.62 2.58zm2.79-12.22a32.682 32.682 0 0 1-7.85 6.17L24.25 24.63C28.01 16.8 35.14 10.5 43.29 7.78c4.16 4.43 8.86 8.84 13.53 12.72-1.38 5.12-4.06 9.91-7.8 13.88z" data-original="#000000"/>
                                    <path d="M41.39 15.66c-9.85.6-9.4 14.92.46 14.91 9.85-.6 9.4-14.92-.46-14.91zm4.21 11.19c-5.06 5.12-12.76-2.11-7.95-7.48 5.05-5.12 12.75 2.1 7.95 7.48zM22.69 52.69l-5.16 5.49c-.89.95.56 2.32 1.46 1.37l5.16-5.49c.89-.95-.56-2.32-1.46-1.37zM11.65 43.9a.996.996 0 0 0-1.41.04l-5.9 6.27a.996.996 0 1 0 1.45 1.37l5.9-6.27c.38-.4.36-1.04-.04-1.41zm1.12 8.34-5.16 5.49c-.61.6-.11 1.71.73 1.69.27 0 .53-.11.73-.31l5.16-5.49c.89-.96-.55-2.32-1.46-1.37zm11.54-19.51 2.91 2.74c.95.89 2.32-.55 1.37-1.46l-2.91-2.74c-.95-.89-2.32.55-1.37 1.46zm6.55 6.17c.95.89 2.32-.55 1.37-1.46l-1.46-1.37c-.95-.89-2.32.55-1.37 1.46l1.46 1.37zm1.95 1.72a.7.7 0 0 0 .09.17l.12.15c.38.39 1.04.39 1.42 0 .09-.1.17-.2.21-.32.57-1.81-2.42-1.8-1.84 0z" data-original="#000000"/>
                                </svg>
                            </div>
                            <div class='bs-welcome-panel-column-content'>
                                <h3><?php esc_html_e('Elevate Your Site Instantly!','blockspare'); ?></h3>
                                
                                <a href="https://blockspare.com/" target="_blank" class="blockspare-notice-link"><?php esc_html_e('Explore More','blockspare'); ?></a>
                            </div>
                        </div>

                        <div class='bs-welcome-panel-column'>
                            <div class='bs-welcome-panel-column-icon'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">
                                    <path d="M179.15 453.63H25.46C11.42 453.63 0 442.21 0 428.18v-324.9c0-14.04 11.42-25.46 25.46-25.46H159.7c3.31 0 6 2.69 6 6 0 3.32-2.69 6-6 6H25.46c-7.42 0-13.46 6.04-13.46 13.46v324.9c0 7.41 6.04 13.45 13.46 13.45h153.69c7.42 0 13.46-6.04 13.46-13.46 0-3.31 2.68-6 6-6 3.31 0 6 2.69 6 6 0 14.04-11.42 25.46-25.46 25.46z" data-original="#000000"/>
                                    <path d="M332.85 434.17h-153.7c-14.03 0-25.45-11.41-25.45-25.45V83.82c0-14.03 11.42-25.45 25.45-25.45h153.7c14.03 0 25.45 11.42 25.45 25.45v324.9c0 14.04-11.42 25.45-25.45 25.45zm-153.7-363.8c-7.42 0-13.45 6.04-13.45 13.45v324.9c0 7.42 6.03 13.46 13.45 13.46h153.7c7.41 0 13.45-6.04 13.45-13.46V83.82c0-7.41-6.04-13.45-13.45-13.45z" data-original="#000000"/>
                                    <path d="M486.54 453.63H332.85c-14.04 0-25.46-11.42-25.46-25.46 0-3.31 2.69-5.99 6-5.99 3.32 0 6 2.68 6 5.99 0 7.42 6.04 13.46 13.46 13.46h153.69c7.42 0 13.46-6.04 13.46-13.46V103.28c0-7.42-6.04-13.46-13.46-13.46H352.3c-3.31 0-6-2.68-6-6 0-3.31 2.69-6 6-6h134.24c14.04 0 25.46 11.42 25.46 25.46v324.9c0 14.03-11.42 25.45-25.46 25.45zM82.85 226.05c-16.99 0-30.81-13.82-30.81-30.81 0-3.31 2.69-6 6-6 3.32 0 6 2.69 6 6 0 10.37 8.44 18.81 18.81 18.81s18.81-8.44 18.81-18.81-8.44-18.8-18.81-18.8c-16.99 0-30.81-13.82-30.81-30.81 0-16.98 13.82-30.8 30.81-30.8s30.81 13.82 30.81 30.8c0 3.32-2.69 6-6 6-3.32 0-6-2.68-6-6 0-10.37-8.44-18.8-18.81-18.8s-18.81 8.43-18.81 18.8 8.44 18.81 18.81 18.81c16.99 0 30.81 13.82 30.81 30.8 0 16.99-13.82 30.81-30.81 30.81zm42.32 28.35H40.54c-3.32 0-6-2.69-6-6 0-3.32 2.68-6 6-6h84.63c3.31 0 6 2.68 6 6 0 3.31-2.69 6-6 6zm0 55.45H40.54c-3.32 0-6-2.69-6-6 0-3.32 2.68-6 6-6h84.63c3.31 0 6 2.68 6 6 0 3.31-2.69 6-6 6zm0 55.44H40.54c-3.32 0-6-2.68-6-6 0-3.31 2.68-6 6-6h84.63c3.31 0 6 2.69 6 6 0 3.32-2.69 6-6 6zm3.89 51.34H36.64c-3.31 0-6-2.69-6-6v-22.86c0-3.32 2.69-6 6-6h92.42c3.31 0 6 2.68 6 6v22.86c0 3.31-2.69 6-6 6zm-86.41-12h80.41v-10.86H42.65z" data-original="#000000"/><path d="M429.15 226.05c-16.99 0-30.81-13.82-30.81-30.81 0-3.31 2.69-6 6-6s6 2.69 6 6c0 10.37 8.44 18.81 18.81 18.81s18.8-8.44 18.8-18.81-8.43-18.8-18.8-18.8c-16.99 0-30.81-13.82-30.81-30.81 0-16.98 13.82-30.8 30.81-30.8 16.98 0 30.8 13.82 30.8 30.8 0 3.32-2.68 6-6 6-3.31 0-6-2.68-6-6 0-10.37-8.43-18.8-18.8-18.8s-18.81 8.43-18.81 18.8 8.44 18.81 18.81 18.81c16.98 0 30.8 13.82 30.8 30.8 0 16.99-13.82 30.81-30.8 30.81zm42.31 28.41h-84.63c-3.31 0-6-2.69-6-6s2.69-6 6-6h84.63c3.32 0 6 2.69 6 6s-2.68 6-6 6zm0 55.45h-84.63c-3.31 0-6-2.69-6-6 0-3.32 2.69-6 6-6h84.63c3.32 0 6 2.68 6 6 0 3.31-2.68 6-6 6zm0 55.44h-84.63c-3.31 0-6-2.68-6-6 0-3.31 2.69-6 6-6h84.63c3.32 0 6 2.69 6 6 0 3.32-2.68 6-6 6zm3.89 51.28h-92.41c-3.31 0-6-2.69-6-6v-22.86c0-3.32 2.69-6 6-6h92.41c3.32 0 6 2.68 6 6v22.86c0 3.31-2.68 6-6 6zm-86.41-12h80.41v-10.86h-80.41zM256 201.2c-16.99 0-30.81-13.81-30.81-30.8 0-3.31 2.69-6 6-6 3.32 0 6 2.69 6 6 0 10.37 8.44 18.8 18.81 18.8s18.81-8.43 18.81-18.8-8.44-18.81-18.81-18.81c-16.99 0-30.81-13.82-30.81-30.8 0-16.99 13.82-30.81 30.81-30.81s30.81 13.82 30.81 30.81c0 3.31-2.69 6-6 6-3.32 0-6-2.69-6-6 0-10.37-8.44-18.81-18.81-18.81s-18.81 8.44-18.81 18.81 8.44 18.8 18.81 18.8c16.99 0 30.81 13.82 30.81 30.81s-13.82 30.8-30.81 30.8zm42.31 33.8h-84.62c-3.32 0-6-2.68-6-6a5.99 5.99 0 0 1 6-5.99h84.62c3.32 0 6 2.68 6 5.99 0 3.32-2.68 6-6 6zm0 55.45h-84.62c-3.32 0-6-2.68-6-6 0-3.31 2.68-6 6-6h84.62c3.32 0 6 2.69 6 6 0 3.32-2.68 6-6 6zm0 55.45h-84.62c-3.32 0-6-2.69-6-6s2.68-6 6-6h84.62c3.32 0 6 2.69 6 6s-2.68 6-6 6zm3.89 56.66h-92.41c-3.31 0-6-2.69-6-6V373.7c0-3.31 2.69-6 6-6h92.41c3.32 0 6 2.69 6 6v22.86c0 3.32-2.68 6-6 6zm-86.41-12h80.41V379.7h-80.41z" data-original="#000000"/>
                                </svg>
                            </div>
                            <div class='bs-welcome-panel-column-content'>
                                <h3><?php esc_html_e('Effortless Website Crafting Continues!','blockspare'); ?></h3>
                                
                                <a href="<?php echo esc_url('https://www.blockspare.com/pricing/')?>" target="_blank" class="blockspare-notice-link"><?php esc_html_e('Upgrade Now','blockspare'); ?></a>
                            </div>
                        </div>    
                </div>
            </div>
            <a class="blockspare-notice-dismiss notice-dismiss" href="<?php echo esc_url( $this->dismiss_url ); ?>"></a>
        </div> <!-- /blockspare-notice -->
        <?php
    }
}

new Blockspare_Setup_Notice();
