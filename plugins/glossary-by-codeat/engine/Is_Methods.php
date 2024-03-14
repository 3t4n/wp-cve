<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */
namespace Glossary\Engine;

use  Glossary\Engine ;
/**
 * Plugin Name Is Methods
 */
class Is_Methods extends Engine\Base
{
    /**
     * Initialize class.
     *
     * @since 2.0
     */
    public function __construct()
    {
        parent::initialize();
    }
    
    /**
     * We can inject Glossary
     *
     * @return bool
     */
    public function is_page_type_to_check()
    {
        return !$this->is_head() && ($this->is_feed() || $this->is_singular() || $this->is_home() || $this->is_category() || $this->is_tag() || $this->is_arc_glossary() || $this->is_tax_glossary() || $this->is_yoast());
    }
    
    /**
     * What type of request is this?
     *
     * @since 2.0
     * @param  string $type admin, ajax, cron, cli, amp or frontend.
     * @return bool
     */
    public function request( string $type )
    {
        switch ( $type ) {
            case 'backend':
                return $this->is_admin_backend();
            case 'ajax':
                return $this->is_ajax();
            case 'installing_wp':
                return $this->is_installing_wp();
            case 'rest':
                return $this->is_rest();
            case 'cron':
                return $this->is_cron();
            case 'frontend':
                return $this->is_frontend();
            case 'amp':
                return $this->is_amp();
            case 'cli':
                return $this->is_cli();
            default:
                \_doing_it_wrong( __METHOD__, \esc_html( \sprintf( 'Unknown request type: %s', $type ) ), '2.0.0' );
                return false;
        }
    }
    
    /**
     * Is installing WP
     *
     * @return bool
     */
    public function is_installing_wp()
    {
        return \defined( 'WP_INSTALLING' );
    }
    
    /**
     * Is admin
     *
     * @return bool
     */
    public function is_admin_backend()
    {
        return \is_user_logged_in() && \is_admin();
    }
    
    /**
     * Is ajax
     *
     * @return bool
     */
    public function is_ajax()
    {
        return \function_exists( 'wp_doing_ajax' ) && \wp_doing_ajax() || \defined( 'DOING_AJAX' );
    }
    
    /**
     * Is rest
     *
     * @return bool
     */
    public function is_rest()
    {
        if ( \defined( 'REST_REQUEST' ) && REST_REQUEST || isset( $_GET['rest_route'] ) && \strpos( wp_unslash( $_GET['rest_route'] ), '/', 0 ) === 0 ) {
            // phpcs:ignore
            return true;
        }
        global  $wp_rewrite ;
        
        if ( $wp_rewrite === null ) {
            $wp_rewrite = new \WP_Rewrite();
            // phpcs:ignore
        }
        
        $current_path = \trim( (string) \parse_url( (string) \add_query_arg( array() ), PHP_URL_PATH ), '/' ) . '/';
        // phpcs:ignore
        $rest_path = \trim( (string) \parse_url( (string) \get_rest_url(), PHP_URL_PATH ), '/' ) . '/';
        // phpcs:ignore
        return \strpos( $current_path, $rest_path ) === 0;
    }
    
    /**
     * Is cron
     *
     * @return bool
     */
    public function is_cron()
    {
        return \function_exists( 'wp_doing_cron' ) && \wp_doing_cron() || \defined( 'DOING_CRON' );
    }
    
    /**
     * Is frontend
     *
     * @return bool
     */
    public function is_frontend()
    {
        return (!$this->is_admin_backend() || !$this->is_ajax()) && !$this->is_cron() && !$this->is_rest();
    }
    
    /**
     * Is AMP
     *
     * @return bool
     */
    public function is_amp()
    {
        return \function_exists( 'amp_is_request' ) && \amp_is_request();
    }
    
    /**
     * Is cli
     *
     * @return bool
     */
    public function is_cli()
    {
        return \defined( 'WP_CLI' ) && WP_CLI || \php_sapi_name() === 'cli';
    }
    
    /**
     * If the most parent hook is the head block the check
     *
     * @return bool
     */
    public function is_head()
    {
        global  $wp_current_filter ;
        return 'wp_head' === $wp_current_filter[0];
    }
    
    /**
     * Check the settings and if is a single page
     *
     * @return bool
     */
    public function is_singular()
    {
        if ( isset( $this->settings['posttypes'] ) && \is_singular( $this->settings['posttypes'] ) ) {
            return !('on' === \get_post_meta( \get_queried_object_id(), GT_SETTINGS . '_disable', true ));
        }
        return false;
    }
    
    /**
     * Check the settings and if is the home page
     *
     * @return bool
     */
    public function is_home()
    {
        return isset( $this->settings['is'] ) && \in_array( 'home', $this->settings['is'], true ) && \is_home();
    }
    
    /**
     * Check the settings and if is a category page
     *
     * @return bool
     */
    public function is_category()
    {
        return isset( $this->settings['is'] ) && \in_array( 'category', $this->settings['is'], true ) && \is_category();
    }
    
    /**
     * Check the settings and if is tag
     *
     * @return bool
     */
    public function is_tag()
    {
        return isset( $this->settings['is'] ) && \in_array( 'tag', $this->settings['is'], true ) && \is_tag();
    }
    
    /**
     * Check the settings and if is an archive glossary
     *
     * @return bool
     */
    public function is_arc_glossary()
    {
        return isset( $this->settings['is'] ) && \in_array( 'arc_glossary', $this->settings['is'], true ) && \is_post_type_archive( 'glossary' );
    }
    
    /**
     * Check the settings and if is a tax glossary page
     *
     * @return bool
     */
    public function is_tax_glossary()
    {
        return isset( $this->settings['is'] ) && \in_array( 'tax_glossary', $this->settings['is'], true ) && \is_tax( 'glossary-cat' );
    }
    
    /**
     * Check the settings and if is a feed page
     *
     * @return bool
     */
    public function is_feed()
    {
        return false;
    }
    
    /**
     * Check if it is Yoast link watcher
     *
     * @return bool
     */
    public function is_yoast()
    {
        return (\is_admin() || \wp_doing_ajax()) && defined( 'WPSEO_FILE' ) && \get_the_ID() !== false && !isset( $_GET['revision'] );
        // phpcs:ignore
    }
    
    /**
     * No AJAX request by Gutenberg
     *
     * @return bool
     */
    public function not_admin_ajax()
    {
        return !$this->request( 'ajax' ) && !$this->request( 'backend' );
    }
    
    /**
     * Compare the hash version of all the text processed with the one in input
     *
     * @param string $text Text to compare.
     * @return bool
     */
    public function is_already_parsed( string $text )
    {
        if ( empty($text) ) {
            return true;
        }
        return false !== \strpos( $text, '"glossary-' ) && false !== \strpos( $text, ' glossary-' );
    }

}