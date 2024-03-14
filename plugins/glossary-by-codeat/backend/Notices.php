<?php

/**
 * Plugin_name
 *
 * @package   Plugin_name
 * @author  Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */
namespace Glossary\Backend;

use  Glossary\Engine ;
use  I18n_Notice_WordPressOrg ;
use  WPDesk\Notice\Notice ;
use  WPDesk\Notice\PermanentDismissibleNotice ;
/**
 * Everything that involves notification on the WordPress dashboard
 */
class Notices extends Engine\Base
{
    /**
     * Initialize the class
     *
     * @return bool
     */
    public function initialize()
    {
        if ( !parent::initialize() ) {
            return false;
        }
        \add_action( 'init', array( $this, 'load_notices' ) );
        return true;
    }
    
    /**
     * Enqueue the various notices
     *
     * @return void
     */
    public function load_notices()
    {
        $this->content_excerpt_empty();
        $this->content_has_list_shortcode();
        if ( \defined( 'ACF' ) ) {
            $this->dismissable( \__( 'This website has the Advanced Custom Fields plugin that is <a href="https://docs.codeat.co/glossary/faq/#can-i-use-glossary-with-acf">supported by the Glossary plugin</a>.', GT_TEXTDOMAIN ), '_pro_acf', 'updated' );
        }
        if ( \is_array( $this->settings ) && !isset( $this->settings['archive'] ) ) {
            
            if ( isset( $this->settings['slug'] ) && !\is_null( \get_page_by_path( $this->settings['slug'], OBJECT ) ) ) {
                $page = \get_page_by_path( $this->settings['slug'], OBJECT );
                new Notice(
                    \sprintf(
                    /* translators: the link to the documentation */
                    \__( 'Hey, we noticed that one of your pages is using the same slug as the Glossary plugin Archive post type. This can create a conflict. To fix it, <a href="http://docs.codeat.co/glossary/advanced-settings/#disable-archives-in-the-frontend">disable the archive in the frontend</a> or change the <a href="%s">slug of your page</a>.', GT_TEXTDOMAIN ),
                    \get_edit_post_link( $page->ID )
                ),
                    'error',
                    false,
                    10,
                    array(),
                    true
                );
            }
        
        }
        $this->alerts_by_libraries();
    }
    
    /**
     * Alert after few days to suggest to contribute to the localization if it is incomplete
     * on translate.wordpress.org, the filter enables to remove globally.
     *
     * @return void
     */
    public function alerts_by_libraries()
    {
        if ( \is_multisite() ) {
            $this->dismissable( \__( 'Hey, we noticed that you are in a multi-site network. Glossary now supports WordPress multi-site feature!<br>Please, read our <a href="http://docs.codeat.co/glossary/faq/#are-you-compatible-with-wordpress-multisite">documentation</a>.', GT_TEXTDOMAIN ), '_pro_multisite', 'updated' );
        }
        new \WP_Review_Me( array(
            'days_after' => 15,
            'type'       => 'plugin',
            'slug'       => GT_TEXTDOMAIN,
            'rating'     => 5,
            'message'    => \__( 'Hey! It\'s been a little while that you\'ve been using Glossary for WordPress. You might not realize it, but user reviews are such a great help to us. We would be so grateful if you could take a minute to leave a review on WordPress.org.<br>Many thanks in advance :)<br>', GT_TEXTDOMAIN ),
            'link_label' => \__( 'Click here to review', GT_TEXTDOMAIN ),
        ) );
        $builder = new \Page_Madness_Detector();
        // phpcs:ignore
        
        if ( $builder->has_entropy() ) {
            $alert = \__( 'We have discovered a page builder on your website that may cause issues if used in conjunction with the glossary. Therefore, we recommend trying the free version initially and reviewing the <a href="https://docs.codeat.co/glossary/faq/#do-you-support-visual-composer" target="_blank">documentation</a> before submitting any support requests.', GT_TEXTDOMAIN );
            $this->dismissable( $alert, '_visualbuilder', 'warning' );
        }
        
        if ( !\apply_filters( $this->default_parameters['filter_prefix'] . '_alert_localization', true ) ) {
            return;
        }
        new I18n_Notice_WordPressOrg( array(
            'textdomain'  => GT_TEXTDOMAIN,
            'plugin_name' => GT_NAME,
            'hook'        => 'admin_notices',
        ), true );
    }
    
    /**
     * Wrapper to simplify the alerts
     *
     * @param string $message The text.
     * @param string $key The key.
     * @param string $type The warning type.
     * @return void
     */
    public function dismissable( $message, $key, $type )
    {
        \wpdesk_init_wp_notice_ajax_handler();
        \wpdesk_permanent_dismissible_wp_notice( $message, GT_TEXTDOMAIN . $key, $type );
        new PermanentDismissibleNotice(
            $message,
            GT_TEXTDOMAIN . $key,
            $type,
            10,
            array(),
            true
        );
    }
    
    /**
     * Get the post object from backend
     *
     * @return \WP_Post|False|null
     */
    public function get_post()
    {
        if ( !isset( $_GET['post'] ) ) {
            // phpcs:ignore
            return false;
        }
        $post_id = \intval( \wp_unslash( $_GET['post'] ) );
        // phpcs:ignore
        if ( !empty($_GET['post']) ) {
            // phpcs:ignore
            return \get_post( $post_id );
        }
        return false;
    }
    
    /**
     * Check if the Term's content or excerpt are empty
     *
     * @return bool
     */
    public function content_excerpt_empty()
    {
        $post = $this->get_post();
        if ( !\is_object( $post ) ) {
            return false;
        }
        if ( $post->post_type !== 'glossary' || !empty(\trim( $post->post_content )) || !empty(\trim( $post->post_excerpt )) ) {
            return false;
        }
        new Notice(
            \__( 'The content and the excerpt of this term are both empty, this will generate empty Tooltips!', GT_TEXTDOMAIN ),
            'error',
            false,
            10,
            array(),
            true
        );
        return true;
    }
    
    /**
     * Check if the Page content has the list shortcode
     *
     * @return bool
     */
    public function content_has_list_shortcode()
    {
        $post = $this->get_post();
        if ( !\is_object( $post ) ) {
            return false;
        }
        if ( $post->post_type !== 'page' || !\has_shortcode( $post->post_content, 'glossary-list' ) ) {
            return false;
        }
        new Notice(
            \sprintf(
            /* translators: it will insert automatically the link to the settings page */
            \__( 'The Glossary shortcode list is used in this page and has a daily cache, you need to <a href="%s" target="_blank">manually purge it</a> to see new terms without waiting!', GT_TEXTDOMAIN ),
            \admin_url() . 'edit.php?post_type=glossary&page=glossary#tabs-shortcodes'
        ),
            'warning',
            false,
            10,
            array(),
            true
        );
        return true;
    }

}