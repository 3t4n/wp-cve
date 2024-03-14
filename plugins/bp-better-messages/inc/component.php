<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Component Class.
 *
 * @since 1.0.0
 */
class Better_Messages_Component extends BP_Component
{

    public $was_redirected;

    public static function instance()
    {

        // Store the instance locally to avoid private static replication
        static $instance = null;

        // Only run these methods if they haven't been run previously
        if ( null === $instance ) {
            $instance = new Better_Messages_Component;
        }

        // Always return the instance
        return $instance;

        // The last metroid is in captivity. The galaxy is at peace.
    }

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::start(
            'bp_better_messages_tab',
            __( 'Messages', 'bp-better-messages' ),
            '',
            array(
                'adminbar_myaccount_order' => 50
            )
        );

        $this->setup_hooks();

    }

    /**
     * Set some hooks to maximize BuddyPress integration.
     *
     * @since 1.0.0
     */
    public function setup_hooks()
    {
        add_action( 'bp_ready', array( $this, 'remove_standard_tab' ) );

        add_filter('bp_get_canonical_url', array( $this, 'override_canonical_url' ), 20, 2 );
        add_filter('bp_nouveau_get_nav_link', array( $this, 'override_message_tab_url'), 20, 3 );
    }


    public function remove_standard_tab()
    {
        global $bp;
        $bp->members->nav->delete_nav( 'messages' );
    }

    public function override_message_tab_url($link, $nav_item, $displayed_nav)
    {
        if( isset($nav_item['component_id']) && $nav_item['component_id'] === 'bp_better_messages_tab' ){
            return Better_Messages()->functions->get_link( bp_displayed_user_id() );
        }

        return $link;
    }

    public function override_canonical_url( $canonical_url, $args )
    {
        if( Better_Messages()->settings['chatPage'] !== '0' ) return $canonical_url;

        $requested_url  = bp_get_requested_url();
        $slug = Better_Messages()->settings['bpProfileSlug'];
        if( basename( $requested_url ) === $slug ){
            return $requested_url;
        }

        return $canonical_url;
    }

    /**
     * Set up component global variables.
     *
     * @since 1.0.0
     */
    public function setup_globals( $args = array() )
    {
        $slug = Better_Messages()->settings['bpProfileSlug'];

        // All globals for component.
        $args = array(
            'slug'          => $slug,
            'has_directory' => false
        );

        parent::setup_globals( $args );

        // Was the user redirected from WP Admin ?
        $this->was_redirected = false;
    }


    /**
     * Set up the component entries in the WordPress Admin Bar.
     *
     * @since 1.3
     */
    public function setup_admin_bar( $wp_admin_nav = array() )
    {
        // Menus for logged in user
        if ( ! is_user_logged_in() ) return;

        $messages_total = Better_Messages()->functions->get_total_threads_for_user( Better_Messages()->functions->get_current_user_id(), 'unread' );
        $class = ( 0 === $messages_total ) ? 'no-count' : '';

        $title = sprintf( _x( 'Messages <span class="%s bp-better-messages-unread count">%s</span>', 'Messages list sub nav', 'bp-better-messages' ), esc_attr( $class ), bp_core_number_format( $messages_total ) );

        $wp_admin_nav[] = array(
            'parent' => buddypress()->my_account_menu_id,
            'id'     => 'bp-messages-' . $this->id,
            'title'  => $title,
            'href'   => Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() )
        );

        $wp_admin_nav[] = array(
            'parent' => 'bp-messages-' . $this->id,
            'id'     => 'bp-messages-' . $this->id . '-threads',
            'title'  => __( 'Conversations', 'bp-better-messages' ),
            'href'   => Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() )
        );

        if( Better_Messages()->settings['disableFavoriteMessages'] === '0' ) {
            $href = Better_Messages()->functions->add_hash_arg('favorited', [], Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() ));

            $wp_admin_nav[] = array(
                'parent' => 'bp-messages-' . $this->id,
                'id' => 'bp-messages-' . $this->id . '-starred',
                'title' => __('Starred', 'bp-better-messages'),
                'href' => $href
            );
        }

        if( Better_Messages()->settings['disableNewThread'] === '0' || current_user_can('manage_options') ) {
            $href = Better_Messages()->functions->add_hash_arg('new-conversation', [], Better_Messages()->functions->get_link( Better_Messages()->functions->get_current_user_id() ));

            $wp_admin_nav[] = array(
                'parent' => 'bp-messages-' . $this->id,
                'id' => 'bp-messages-' . $this->id . '-new-message',
                'title' => __('New Conversation', 'bp-better-messages'),
                'href' => $href
            );
        }

        parent::setup_admin_bar( $wp_admin_nav );
    }

    /**
     * Set up component navigation.
     *
     * @since 1.0.0
     */
    public function setup_nav( $main_nav = array(), $sub_nav = array() )
    {
        if ( ! bm_bp_is_active( 'messages' ) ) return false;

        if( bp_is_my_profile() ) {
            $messages_total = Better_Messages()->functions->get_total_threads_for_user(Better_Messages()->functions->get_current_user_id(), 'unread');
            $class = (0 === $messages_total || $messages_total == false) ? 'no-count' : 'count';
            $nave = sprintf(_x('Messages <span class="%s">%s</span>', 'Messages list sub nav', 'bp-better-messages'), esc_attr($class), bp_core_number_format($messages_total));
        } else {
            $nave = _x('Messages', 'Messages list sub nav', 'bp-better-messages');
        }

        $slug = Better_Messages()->settings['bpProfileSlug'];

        $main_nav = array(
            'name'                    => $nave,
            'slug'                    => $this->slug,
            'position'                => 50,
            'screen_function'         => array( $this, 'set_screen' ),
            'user_has_access'         => bp_is_my_profile(),
            'default_subnav_slug'     => $slug,
            'item_css_id'             => $this->id,
            'show_for_displayed_user' => false
        );

        parent::setup_nav( $main_nav, $sub_nav );
    }

    /**
     * Set the BuddyPress screen for the requested actions
     *
     * @since 1.0.0
     */
    public function set_screen()
    {
        // Allow plugins to do things there..
        do_action( 'bp_better_messages_screen' );
        // Prepare the template part.
        add_action( 'bp_template_content', array( $this, 'content' ), 20 );

        // Load the template
        bp_core_load_template( 'members/single/plugins' );
    }

    /**
     * Output the Comments page content
     *
     * @since 1.0.0
     */
    public function content()
    {
        if( function_exists('bp_is_user') && bp_is_user() ) {
            echo Better_Messages()->functions->get_page();
        } else {
            echo Better_Messages()->functions->get_page( true );
        }
    }

    /**
     * Figure out if the user was redirected from the WP Admin
     *
     * @since 1.0.0
     */
    public function was_redirected( $prevent_access )
    {
        // Catch this, true means the user is about to be redirected
        $this->was_redirected = $prevent_access;

        return $prevent_access;
    }
}

function Better_Messages_Tab()
{
    return Better_Messages_Component::instance();
}

if( ! function_exists('BP_Better_Messages_Tab') ) {
    function BP_Better_Messages_Tab()
    {
        return Better_Messages_Tab();
    }
}
