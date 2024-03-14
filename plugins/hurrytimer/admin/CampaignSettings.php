<?php

namespace Hurrytimer;

/**
 * The admin posts metaboxes.
 *
 * @link       http://nabillemsieh.com
 * @since      1.0.0
 *
 * @package    Hurrytimer
 * @subpackage Hurrytimer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hurrytimer
 * @subpackage Hurrytimer/admin
 * @author     Nabil Lemsieh <contact@nabillemsieh.com>
 */
class CampaignSettings
{
    public function __construct()
    {
        $this->intializeHooks();
    }

    private function intializeHooks()
    {

        // Save compaign settings.
        add_action( 'save_post_hurrytimer_countdown', [ $this, 'save_settings' ], 10, 3 );

        // Custom publish metabox.
        add_action( 'post_submitbox_misc_actions', [ $this, 'post_publish_metabox' ] );

        // Edit placeholder for headline.
        add_filter( 'enter_title_here', [ $this, 'change_countdown_title' ], 10 );

        // Edit messages.
        add_filter( 'post_updated_messages', [ $this, 'custom_updated_messages' ], 10 );

        // Campaign settings metabox.
        add_filter( 'add_meta_boxes', [ $this, 'add_settings_metabox_template' ], 10 );

        add_action( "updated_post_meta", [ $this, 'maybe_reset_running_countdown' ], 10, 4 );
        add_action( 'edit_form_before_permalink', [ $this, 'show_headline_moved_notice' ] );
    }

    function show_headline_moved_notice( $post )
    {
        if ( !$post || $post->post_type !== HURRYT_POST_TYPE ) {
            return;
        }

        if( get_option('hurryt_headline_moved_notice_dismissed') ){
            return;
        }

        if(isset($_COOKIE['hurryt_headline_moved_notice_dismissed'])){
            return;
        }
        if ( !filter_var( apply_filters( 'hurryt_show_headline_moved_notice', true ), FILTER_VALIDATE_BOOLEAN ) ) {
            return;
        }

        if ( !Installer::get_instance()->has_upgraded_from_2_2_28_or_prior() ) {
            return;
        }

        ?>
        <p class="description" style="margin-top:5px;font-style:normal; background:white; border: 1px solid #ccd0d4;;padding:10px;border-left: 1px solid #00a0d2;border-left-width: 4px;box-shadow: 0 1px 1px rgb(0 0 0 / 4%);">
        The headline can now be edited under <a href="#" class="hurryt-open-hl-tab">Appearance → Elements → Headline</a>. Use the input box above to add the name of the campaign instead.
        <br>
        <button  type="button" class="button-primary" style="margin-top:5px;" id="hurryt-dismiss-headline-moved-notice">OK, got it</button>
        </p>
        <?php
    }


    /**
     * Edit notices messages.
     *
     * @param array $messages
     *
     * @return array
     */
    public function custom_updated_messages( $messages )
    {
        global $post;
        $messages[ HURRYT_POST_TYPE ] = array(
            0 => '',
            1 => __( 'Campaign updated.', 'hurrytimer' ),
            2 => '',
            3 => '',
            4 => __( 'Campaign updated.', 'hurrytimer' ),
            5 => '',
            6 => __( 'Campaign published.', 'hurrytimer' ),
            7 => __( 'Campaign saved.', 'hurrytimer' ),
            8 => __( 'Campaign submitted.', 'hurrytimer' ),
            9 => sprintf(
                __(
                    'Campaign scheduled for: <strong>%1$s</strong>.',
                    'hurrytimer'
                ),
                date_i18n(__( 'M j, Y @ G:i', 'hurrytimer' ),strtotime( $post->post_date ))
            ),
            10 => __( 'Campaign draft updated.', 'hurrytimer' ),
        );

        return $messages;
    }

    /**
     * If time is edited, reset compaign.
     *
     * @param int $meta_id
     * @param int $object_id
     * @param string $meta_key
     * @param string $meta_value
     *
     * @return void
     */
    public function maybe_reset_running_countdown(
        $meta_id,
        $object_id,
        $meta_key,
        $meta_value
    ) {
        if ( !in_array( $meta_key, [ 'duration' ] ) ) {
            return;
        }

        // (new EvergreenCampaign($object_id))->reset();
    }

    public function maybe_delete_running_countdown( $post_id )
    {
        //Evergreen_Countdown::reset($post_id);
    }

    /**
     * Edit title placeholder.
     *
     * @param string $title
     *
     * @return string
     */
    public function change_countdown_title( $title )
    {
        global $post;
        if ( $post && $post->post_type === HURRYT_POST_TYPE ) {
            return __(
                'Campaign name (optional)',
                "hurrytimer"
            );
        }

        return $title;
    }

    /**
     * Custom publish metabox.
     *
     * @return void
     */
    public function post_publish_metabox()
    {
        global $post_id, $post;
        if ( $post->post_type !== HURRYT_POST_TYPE ) {
            return;
        }

        if ( Utils\Helpers::isNewPost( $post_id ) ) {
            return;
        }
        $isActive = Utils\Helpers::isPostActive( $post_id );
        $publich_date = Utils\Helpers::format_date( $post->post_date );
        $deactivateUrl = Utils\Helpers::deactivateUrl( $post_id );
        $activateUrl = Utils\Helpers::activateUrl( $post_id );

        include HURRYT_DIR . '/admin/templates/post-publish-metabox.php';

    }

    /**
     * Save compaign settings
     *
     * @param int $post_id
     *
     * @return void
     */

    public function save_settings( $post_id )
    {
        if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || (!isset($_POST['post_ID']) || $post_id != $_POST['post_ID'])
        || (!current_user_can('edit_post', $post_id))

        ) {
            return;
        }

        $countdown = new Campaign( $post_id );
        $countdown->storeSettings( $_POST );
    }

    public function add_settings_metabox_template( $post_type )
    {
        global $post_id;
        if ( $post_type != HURRYT_POST_TYPE ) {
            return;
        }
        remove_meta_box( 'wpseo_meta', HURRYT_POST_TYPE, 'normal' );
        remove_meta_box( 'slugdiv', HURRYT_POST_TYPE, 'normal' );
        remove_meta_box( 'eg-meta-box', HURRYT_POST_TYPE, 'normal' );

        add_meta_box(
            'hurrytimer-settings',
            __( 'Settings <a  href="#" class="hurryt-fullscreen hidden"></a>',
                'hurrytimer' ),
            array( $this, 'settingsMetaboxTemplate' ),
            $post_type,
            'normal',
            'high'
        );

        if ( $post_id ) {
            add_meta_box(
                'hrytmr-countdown__shortcode',
                __( 'Shortcode', 'hurrytimer' ),
                array( $this, 'shortcode_metabox_template' ),
                $post_type,
                'side',
                'high'
            );
        }
    }

    public function shortcode_metabox_template()
    {
        global $post_id;
        $shortcode = "[hurrytimer id='{$post_id}']";
        include HURRYT_DIR . '/admin/templates/campaign-shortcode.php';

    }

    /**
     * Campaign settings template.
     *
     * @return void
     */
    public function settingsMetaboxTemplate()
    {
        global $post_id;
        $campaign = new Campaign( $post_id );
        $campaign->loadSettings();
        $resetCampaignAllVisitorsUrl = $this->createResetEvergreenCampaign( 'all' );
        $resetCampaignCurrentAdminUrl = $this->createResetEvergreenCampaign( 'admin' );
        $products = $this->getWcProductsNames( $campaign->wcProductsSelection,
            $campaign->wcProductsSelectionType );
        include HURRYT_DIR . '/admin/templates/campaign-settings.php';

    }

    public function createResetEvergreenCampaign( $scope = 'admin' )
    {
        global $post_id;

        $action = 'reset-evergreen-compaign';

        return wp_nonce_url(
            add_query_arg(
                array(
                    'hurryt-action' => $action,
                    'hurryt-scope' => $scope,
                    'postid' => $post_id,
                    'post' => $post_id,
                    'action' => 'edit',
                ),
                admin_url( 'post.php' )
            ),
            $action
        );
    }

    /**
     * @param array $ids
     * @param       $selection
     *
     * @return array
     */
    public function getWcProductsNames( $ids, $selection )
    {
        $result = [];
        if ( empty( $ids ) ) {
            return $result;
        }

        switch ( $selection ) {
            case C::WC_PS_TYPE_ALL:
                break;
            case C::WC_PS_TYPE_INCLUDE_PRODUCTS:
            case C::WC_PS_TYPE_EXCLUDE_PRODUCTS:
                $args = [
                    'post_type' => 'product',
                    'post__in' => $ids,
                    'post_status' => 'any',
                    'posts_per_page' => -1
                ];
                $products = get_posts( $args );
                foreach ( $products as $product ) {
                    $result[] = [
                        'id' => $product->ID,
                        'text' => $product->post_title,
                    ];
                }
                break;
            case C::WC_PS_TYPE_INCLUDE_CATEGORIES:
            case C::WC_PS_TYPE_EXCLUDE_CATEGORIES:
                $ids = array_map( 'intval', $ids );
                $args = [
                    'taxonomy' => 'product_cat',
                    'term_ids' => $ids,
                    'hide_empty' => false,
                ];

                $categories = get_terms( $args );

                $categories = array_filter( $categories, function (
                    $_category
                ) use ( $ids ) {
                    return in_array( $_category->term_id, $ids );
                } );
                foreach ( $categories as $category ) {
                    $result[] = [
                        'id' => $category->term_id,
                        'text' => $category->name,
                    ];
                }
                break;
            default:
                break;
        }

        return $result;
    }

}
