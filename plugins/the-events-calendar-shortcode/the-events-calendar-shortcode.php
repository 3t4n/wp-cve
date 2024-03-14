<?php
/***
 Plugin Name: The Events Calendar Shortcode & Block
 Plugin URI: https://eventcalendarnewsletter.com/the-events-calendar-shortcode/
 Description: An addon to add shortcode and new editor block functionality for The Events Calendar Plugin by StellarWP (formerly Modern Tribe)
 Version: 2.8.5
 Author: Event Calendar Newsletter
 Author URI: https://eventcalendarnewsletter.com/the-events-calendar-shortcode
 Contributors: brianhogg
 License: GPL2 or later
 License URI: http://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: the-events-calendar-shortcode
 Requires at least: 6.2
 Requires PHP: 7.4
 */

// Avoid direct calls to this file
if ( !defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if ( ! defined( 'TECS_CORE_PLUGIN_FILE' ) ) {
    define( 'TECS_CORE_PLUGIN_FILE', __FILE__ );
    define( 'TECS_CORE_PLUGIN_DIR', __DIR__ );
}

include_once dirname( TECS_CORE_PLUGIN_FILE ) . '/block/init.php';
include_once dirname( TECS_CORE_PLUGIN_FILE ) . '/includes/ajax-endpoints.php';
include_once dirname( TECS_CORE_PLUGIN_FILE ) . '/includes/notices/rating.php';
include_once dirname( TECS_CORE_PLUGIN_FILE ) . '/admin/class-getting-started.php';

if ( ! function_exists( 'tecs_get_capability' ) ) {
    function tecs_get_capability() {
        return apply_filters( 'ecs_admin_page_capability', 'edit_published_posts' );
    }
}

/*
 * Events calendar shortcode addon main class
 *
 * @package events-calendar-shortcode
 * @author Brian Hogg
 */

if ( ! class_exists( 'Events_Calendar_Shortcode' ) ) {
    class Events_Calendar_Shortcode {

        /**
                 * Current version of the plugin.
                 *
                 * @since 1.0.0
                 */
        const VERSION = '2.8.5';

        private $admin_page = null;

        const MENU_SLUG = 'ecs-admin';

        /**
         * Constructor. Hooks all interactions to initialize the class.
         *
         * @since 1.0.0
         * @see	 add_shortcode()
         */
        public function __construct() {
            add_action( 'plugins_loaded', [ $this, 'verify_tec_installed' ], 2 );
            add_action( 'admin_menu', [ $this, 'add_menu_page' ], 1000 );
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'add_action_links' ] );
            add_shortcode( 'ecs-list-events', [ $this, 'ecs_fetch_events' ] );
            add_filter( 'ecs_ending_output', [ $this, 'add_event_schema_json' ], 10, 3 );
            add_filter( 'ecs_ending_output', [ $this, 'add_ecs_link' ], 10, 3 );
            add_action( 'plugins_loaded', [ $this, 'load_languages' ] );
            add_action( 'admin_menu', [ $this, 'add_meta_box_tribe_events' ], 99 );
        }

        public function add_meta_box_tribe_events() {
            global $pagenow;

            if ( defined( 'TECS_VERSION' ) ) {
                return;
            }

            if ( ! current_user_can( 'install_plugins' ) ) {
                return;
            }

            if ( ! class_exists( 'Tribe__Events__Main' ) ) {
                return;
            }

            if ( $pagenow === 'post-new.php' ) {
                return;
            }

            add_meta_box(
                'tecs_info',
                esc_html__( 'Shortcode &amp; Block', 'the-events-calendar-shortcode' ),
                [ $this, 'render_meta_box_tribe_events' ],
                Tribe__Events__Main::POSTTYPE,
                'side',
                'low'
            );
        }

        public function render_meta_box_tribe_events() {
            ?>
            <p>Show this and other events using either the block, or a shortcode on a page or post:</p>

            <code style="background-color: rgb(31 41 55); color: white; padding: 5px;">[ecs-list-events]</code>

            <p>Want more designs, and more options to promote your events?</p>

            <p><a target="_blank" style="color: white; display: block; background-color: #EB6924; text-decoration: none; padding: 6px; text-align: center;" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-edit-event&utm_content=metabox">Check out The Events Calendar Shortcode &amp; Block Pro</a></p>

            <?php
        }

        // END __construct()

        public function load_languages() {
            if ( function_exists( 'tecsp_load_textdomain' ) ) {
                return;
            }
            load_plugin_textdomain( 'the-events-calendar-shortcode', false, plugin_basename( __DIR__ ) . '/languages' );
        }

        public function verify_tec_installed() {
            if ( ! class_exists( 'Tribe__Events__Main' ) or ! defined( 'Tribe__Events__Main::VERSION' ) ) {
                add_action( 'admin_notices', [ $this, 'show_tec_not_installed_message' ] );
            }
        }

        public function show_tec_not_installed_message() {
            if ( current_user_can( 'activate_plugins' ) ) {
                $url = 'plugin-install.php?s=the+events+calendar&tab=search&type=term';
                $title = __( 'The Events Calendar', 'tribe-events-ical-importer' );
                echo '<div class="error"><p>' . sprintf( esc_html( __( 'To begin using %s, please install the latest version of %s%s%s and add an event.', 'the-events-calendar-shortcode' ) ), 'The Events Calendar Shortcode', '<a href="' . esc_url( admin_url( $url ) ) . '" title="' . esc_attr( $title ) . '">', 'The Events Calendar', '</a>' ) . '</p></div>';
            }
        }

        public function add_menu_page() {
            if ( ! class_exists( 'Tribe__Settings' ) ) {
                return;
            }

            $page_title = $menu_title = esc_html__( 'Shortcode & Block', 'the-events-calendar-shortcode' );
            $capability = apply_filters( 'ecs_admin_page_capability', 'edit_published_posts' );

            $this->admin_page = add_submenu_page( 'edit.php?post_type=tribe_events', $page_title, $menu_title, $capability, self::MENU_SLUG, [ $this, 'do_menu_page' ] );

            add_action( 'admin_print_styles-' . $this->admin_page, [ $this, 'enqueue' ] );
            add_action( 'admin_print_styles', [ $this, 'enqueue_submenu_style' ] );
        }

        public function enqueue() {
            wp_enqueue_style( 'tecs-getting-started', plugins_url( 'static/css/admin.css', TECS_CORE_PLUGIN_FILE ), [], Events_Calendar_Shortcode::VERSION, 'all' );
            wp_enqueue_style( 'tecs-ecs-admin', plugins_url( 'static/css/ecs.css', TECS_CORE_PLUGIN_FILE ), [], Events_Calendar_Shortcode::VERSION, 'all' );

            wp_enqueue_script( 'ecs-admin-js', plugins_url( 'static/ecs-admin.min.js', __FILE__ ), [], self::VERSION );
        }

        /**
         * Function to add a small CSS file to add some colour to the Shortcode submenu item
         */
        public function enqueue_submenu_style() {
            wp_enqueue_style( 'ecs-submenu-css', plugins_url( 'static/ecs-submenu.css', __FILE__ ), [], self::VERSION );
        }

        public function do_menu_page() {
            include __DIR__ . '/templates/admin-page.php';
        }

        public function add_action_links( $links ) {
            $mylinks = [];

            if ( class_exists( 'Tribe__Settings' ) and current_user_can( 'edit_published_posts' ) ) {
                $mylinks[] = '<a href="' . admin_url( 'edit.php?post_type=tribe_events&page=ecs-admin' ) . '">' . esc_html__( 'Settings', 'the-events-calendar-shortcode' ) . '</a>';
            }
            $mylinks[] = '<a target="_blank" style="color:#3db634; font-weight: bold;" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode/?utm_source=plugin-list&utm_medium=upgrade-link&utm_campaign=plugin-list&utm_content=action-link">' . esc_html__( 'Upgrade', 'the-events-calendar-shortcode' ) . '</a>';

            return array_merge( $links, $mylinks );
        }

        /**
         * Fetch and return required events.
         *
         * @param array $atts shortcode attributes
         *
         * @return string shortcode output
         */
        public function ecs_fetch_events( $atts ) {
            /*
             * Check if events calendar plugin method exists
             */
            if ( !function_exists( 'tribe_get_events' ) ) {
                return '';
            }

            global $post, $ecs_last_event_count;
            $output = '';

            $atts = shortcode_atts( apply_filters( 'ecs_shortcode_atts', [
                'cat' => '',
                'month' => '',
                'limit' => 5,
                'eventdetails' => 'true',
                'time' => null,
                'past' => null,
                'venue' => 'false',
                'author' => null,
                'schema' => 'true',
                'message' => 'There are no upcoming %1$s.',
                'key' => 'End Date',
                'order' => 'ASC',
                'orderby' => 'startdate',
                'viewall' => 'false',
                'excerpt' => 'false',
                'thumb' => 'false',
                'thumbsize' => '',
                'thumbwidth' => '',
                'thumbheight' => '',
                'contentorder' => apply_filters( 'ecs_default_contentorder', 'title, thumbnail, excerpt, date, venue', $atts ),
                'event_tax' => '',
            ], $atts ), $atts, 'ecs-list-events' );

            // Category
            if ( $atts['cat'] ) {
                if ( strpos( $atts['cat'], ',' ) !== false ) {
                    $atts['cats'] = explode( ',', $atts['cat'] );
                    $atts['cats'] = array_map( 'trim', $atts['cats'] );
                } else {
                    $atts['cats'] = [ trim( $atts['cat'] ) ];
                }

                $atts['event_tax'] = [
                    'relation' => 'OR',
                ];

                foreach ( $atts['cats'] as $cat ) {
                    $atts['event_tax'][] = [
                        'relation' => 'OR',
                        [
                            'taxonomy' => 'tribe_events_cat',
                            'field' => 'name',
                            'terms' => $cat,
                        ],
                        [
                            'taxonomy' => 'tribe_events_cat',
                            'field' => 'slug',
                            'terms' => $cat,
                        ],
                    ];
                }
            }

            // Past Event
            $meta_date_compare = '>=';
            $meta_date_date = current_time( 'Y-m-d H:i:s' );

            if ( $atts['time'] == 'past' || !empty( $atts['past'] ) ) {
                $meta_date_compare = '<';
            }

            // Key, used in filtering events by date
            if ( str_replace( ' ', '', trim( strtolower( $atts['key'] ) ) ) == 'startdate' ) {
                $atts['key'] = '_EventStartDate';
            } else {
                $atts['key'] = '_EventEndDate';
            }

            // Orderby
            if ( str_replace( ' ', '', trim( strtolower( $atts['orderby'] ) ) ) == 'enddate' ) {
                $atts['orderby'] = '_EventEndDate';
            } elseif ( trim( strtolower( $atts['orderby'] ) ) == 'title' ) {
                $atts['orderby'] = 'title';
            } else {
                $atts['orderby'] = '_EventStartDate';
            }

            // Date
            $atts['meta_date'] = [
                [
                    'key' => $atts['key'],
                    'value' => $meta_date_date,
                    'compare' => $meta_date_compare,
                    'type' => 'DATETIME',
                ],
            ];

            // Specific Month
            if ( 'current' == $atts['month'] ) {
                $atts['month'] = current_time( 'Y-m' );
            }

            if ( 'next' == $atts['month'] ) {
                $now = current_datetime();
                $atts['month'] = $now->modify( 'first day of this month' )->modify( 'first day of next month' )->format( 'Y-m' );
            }

            if ( $atts['month'] ) {
                $month_array = explode( '-', $atts['month'] );

                $month_yearstr = $month_array[0];
                $month_monthstr = $month_array[1];
                $month_startdate = date( 'Y-m-d', strtotime( $month_yearstr . '-' . $month_monthstr . '-01' ) );
                $month_enddate = date( 'Y-m-01', strtotime( '+1 month', strtotime( $month_startdate ) ) );

                $atts['meta_date'] = [
                    'relation' => 'AND',
                    [
                        'key' => $atts['key'],
                        'value' => $month_startdate,
                        'compare' => '>=',
                        'type' => 'DATETIME',
                    ],
                    [
                        'key' => $atts['key'],
                        'value' => $month_enddate,
                        'compare' => '<',
                        'type' => 'DATETIME',
                    ],
                ];
            }

            $atts = apply_filters( 'ecs_atts_pre_query', $atts, $meta_date_date, $meta_date_compare );
            $args = apply_filters( 'ecs_get_events_args', [
                'post_status' => 'publish',
                'hide_upcoming' => true,
                'posts_per_page' => $atts['limit'],
                'tax_query' => $atts['event_tax'],
                // Likely want to revamp this logic and separate the ordering from the date filtering
                'meta_key' => ( ( trim( $atts['orderby'] ) and 'title' != $atts['orderby'] ) ? $atts['orderby'] : $atts['key'] ),
                'orderby' => ( $atts['orderby'] == 'title' ? 'title' : 'event_date' ),
                'author' => $atts['author'],
                'order' => $atts['order'],
                'meta_query' => apply_filters( 'ecs_get_meta_query', [ $atts['meta_date'] ], $atts, $meta_date_date, $meta_date_compare ),
            ], $atts, $meta_date_date, $meta_date_compare );

            $posts = tribe_get_events( $args );
            $posts = apply_filters( 'ecs_filter_events_after_get', $posts, $atts );

            $ecs_last_event_count = 0;

            if ( $posts or apply_filters( 'ecs_always_show', false, $atts ) ) {
                $output = apply_filters( 'ecs_beginning_output', $output, $posts, $atts );
                $output .= apply_filters( 'ecs_start_tag', '<ul class="ecs-event-list">', $atts, count( (array) $posts ) );
                $atts['contentorder'] = explode( ',', $atts['contentorder'] );

                foreach ( (array) $posts as $post_index => $post ) {
                    setup_postdata( $post );
                    $event_output = '';

                    if ( apply_filters( 'ecs_skip_event', false, $atts, $post ) ) {
                        continue;
                    }
                    $ecs_last_event_count++;
                    $category_slugs = [];
                    $category_list = get_the_terms( $post, 'tribe_events_cat' );
                    $featured_class = ( get_post_meta( get_the_ID(), '_tribe_featured', true ) ? ' ecs-featured-event' : '' );

                    if ( is_array( $category_list ) ) {
                        foreach ( (array) $category_list as $category ) {
                            $category_slugs[] = ' ' . $category->slug . '_ecs_category';
                        }
                    }
                    $event_output .= apply_filters( 'ecs_event_start_tag', '<li class="ecs-event' . implode( '', $category_slugs ) . $featured_class . apply_filters( 'ecs_event_classes', '', $atts, $post ) . '">', $atts, $post );

                    // Put Values into $event_output
                    foreach ( apply_filters( 'ecs_event_contentorder', $atts['contentorder'], $atts, $post ) as $contentorder ) {
                        switch ( trim( $contentorder ) ) {
                            case 'title':
                                $event_output .= apply_filters( 'ecs_event_title_tag_start', '<h4 class="entry-title summary">', $atts, $post ) .
                                                 apply_filters( 'ecs_event_list_title_link_start', '<a href="' . tribe_get_event_link() . '" rel="bookmark">', $atts, $post ) . apply_filters( 'ecs_event_list_title', get_the_title(), $atts, $post ) . apply_filters( 'ecs_event_list_title_link_end', '</a>', $atts, $post ) .
                                                 apply_filters( 'ecs_event_title_tag_end', '</h4>', $atts, $post );
                                break;

                            case 'thumbnail':
                                if ( self::isValid( $atts['thumb'] ) ) {
                                    $thumbWidth = is_numeric( $atts['thumbwidth'] ) ? $atts['thumbwidth'] : '';
                                    $thumbHeight = is_numeric( $atts['thumbheight'] ) ? $atts['thumbheight'] : '';

                                    if ( !empty( $thumbWidth ) && !empty( $thumbHeight ) ) {
                                        $event_output .= apply_filters( 'ecs_event_thumbnail', get_the_post_thumbnail( get_the_ID(), apply_filters( 'ecs_event_thumbnail_size', [ $thumbWidth, $thumbHeight ], $atts, $post ) ), $atts, $post );
                                    } else {
                                        if ( $thumb = get_the_post_thumbnail( get_the_ID(), apply_filters( 'ecs_event_thumbnail_size', ( trim( $atts['thumbsize'] ) ? trim( $atts['thumbsize'] ) : 'medium' ), $atts, $post ) ) ) {
                                            $event_output .= apply_filters( 'ecs_event_thumbnail_link_start', '<a href="' . tribe_get_event_link() . '">', $atts, $post );
                                            $event_output .= apply_filters( 'ecs_event_thumbnail', $thumb, $atts, $post );
                                            $event_output .= apply_filters( 'ecs_event_thumbnail_link_end', '</a>', $atts, $post );
                                        }
                                    }
                                }
                                break;

                            case 'excerpt':
                                if ( self::isValid( $atts['excerpt'] ) ) {
                                    $excerptLength = is_numeric( $atts['excerpt'] ) ? intval( $atts['excerpt'] ) : 100;
                                    $event_output .= apply_filters( 'ecs_event_excerpt_tag_start', '<p class="ecs-excerpt">', $atts, $post ) .
                                                     apply_filters( 'ecs_event_excerpt', self::get_excerpt( $excerptLength ), $atts, $post, $excerptLength ) .
                                                     apply_filters( 'ecs_event_excerpt_tag_end', '</p>', $atts, $post );
                                }
                                break;

                            case 'date':
                                if ( self::isValid( $atts['eventdetails'] ) ) {
                                    $event_output .= apply_filters( 'ecs_event_date_tag_start', '<span class="duration time">', $atts, $post ) .
                                                     apply_filters( 'ecs_event_list_details', tribe_events_event_schedule_details(), $atts, $post ) .
                                                     apply_filters( 'ecs_event_date_tag_end', '</span>', $atts, $post );
                                }
                                break;

                            case 'venue':
                                if ( self::isValid( $atts['venue'] ) and function_exists( 'tribe_has_venue' ) and tribe_has_venue() ) {
                                    $event_output .= apply_filters( 'ecs_event_venue_tag_start', '<span class="duration venue">', $atts, $post ) .
                                                     apply_filters( 'ecs_event_venue_at_tag_start', '<em> ', $atts, $post ) .
                                                     apply_filters( 'ecs_event_venue_at_text', __( 'at', 'the-events-calendar-shortcode' ), $atts, $post ) .
                                                     apply_filters( 'ecs_event_venue_at_tag_end', ' </em>', $atts, $post ) .
                                                     apply_filters( 'ecs_event_list_venue', tribe_get_venue(), $atts, $post ) .
                                                     apply_filters( 'ecs_event_venue_tag_end', '</span>', $atts, $post );
                                }
                                break;

                            case 'date_thumb':
                                if ( self::isValid( $atts['eventdetails'] ) ) {
                                    $event_output .= apply_filters( 'ecs_event_date_thumb', '<div class="date_thumb"><div class="month">' . tribe_get_start_date( null, false, 'M' ) . '</div><div class="day">' . tribe_get_start_date( null, false, 'j' ) . '</div></div>', $atts, $post );
                                }
                                break;
                            default:
                                $event_output .= apply_filters( 'ecs_event_list_output_custom_' . strtolower( trim( $contentorder ) ), '', $atts, $post );
                        }
                    }
                    $event_output .= apply_filters( 'ecs_event_end_tag', '</li>', $atts, $post );
                    $output .= apply_filters( 'ecs_single_event_output', $event_output, $atts, $post, $post_index, $posts );
                }
                $output .= apply_filters( 'ecs_end_tag', '</ul>', $atts );
                $output = apply_filters( 'ecs_ending_output', $output, $posts, $atts );

                if ( self::isValid( $atts['viewall'] ) ) {
                    $output .= apply_filters( 'ecs_view_all_events_tag_start', '<div class="ecs-view-all-events"><span class="ecs-all-events">', $atts ) .
                               '<a href="' . apply_filters( 'ecs_event_list_viewall_link', tribe_get_events_link(), $atts ) . '" rel="bookmark">' . apply_filters( 'ecs_view_all_events_text', sprintf( __( 'View all %s', 'the-events-calendar' ), tribe_get_event_label_plural_lowercase() ), $atts ) . '</a>';
                    $output .= apply_filters( 'ecs_view_all_events_tag_end', '</span></div>' );
                }
            } else {
                $output .= '<div class="ecs-no-events">' . apply_filters( 'ecs_no_events_found_message', sprintf( _x( $atts['message'], 'A message to indicate there are no upcoming events.', 'the-events-calendar' ), tribe_get_event_label_plural_lowercase() ), $atts ) . '</div>';
            }

            wp_reset_postdata();

            return $output;
        }

        public function add_ecs_link( $output, $posts, $atts ) {
            if ( ! apply_filters( 'ecs_show_upgrades', true ) ) {
                return $output;
            }
            $output .= "<!--\n Event listing powered by The Events Calendar Shortcode\n https://eventcalendarnewsletter.com/the-events-calendar-shortcode/ \n-->";

            if ( ! get_option( 'ecs-show-link', false ) ) {
                return $output;
            }
            $output .= '<p class="ecs-powered-by-link">';
            $output .= sprintf( esc_html__( 'Event listing powered by %sThe Events Calendar Shortcode%s', 'the-events-calendar-shortcode' ), '<a target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode/?utm_source=footer&utm_campaign=powered-by-link">', '</a>' );
            $output .= '</p>';

            return $output;
        }

        public function add_event_schema_json( $output, $posts, $atts ) {
            if ( self::isValid( $atts['schema'] ) and $posts and class_exists( 'Tribe__Events__JSON_LD__Event' ) and ( ! defined( 'DOING_AJAX' ) or ! DOING_AJAX ) ) {
                $output .= Tribe__Events__JSON_LD__Event::instance()->get_markup( $posts );
            }

            return $output;
        }

        /**
         * Checks if the plugin attribute is valid
         *
         * @since 1.0.5
         *
         * @param string $prop
         *
         * @return bool
         */
        public static function isValid( $prop ) {
            return  $prop !== 'false';
        }

        /**
         * Fetch and trims the excerpt to specified length
         *
         * @param int    $limit  Characters to show
         * @param string $source content or excerpt
         *
         * @return string
         */
        public static function get_excerpt( $limit, $source = null ) {
            $excerpt = get_the_excerpt();

            if ( $source == 'content' ) {
                $excerpt = get_the_content();
            }

            $excerpt = preg_replace( " (\[.*?\])", '', $excerpt );
            $excerpt = strip_tags( strip_shortcodes( $excerpt ) );
            $excerpt = trim( preg_replace( '/\s+/', ' ', $excerpt ) );

            if ( strlen( $excerpt ) > $limit ) {
                $excerpt = substr( $excerpt, 0, $limit );
                $excerpt .= '...';
            }

            return $excerpt;
        }
    }
}

/*
 * Instantiate the main class
 *
 * @since 1.0.0
 * @access public
 *
 * @var	object	$events_calendar_shortcode holds the instantiated class {@uses Events_Calendar_Shortcode}
 */
global $events_calendar_shortcode;
$events_calendar_shortcode = new Events_Calendar_Shortcode();

if ( ! defined( 'TECS_VERSION' ) ) {
    if ( ! class_exists( 'TECS_Plugin_Usage_Tracker' ) ) {
        require_once __DIR__ . '/tracking/class-plugin-usage-tracker.php';
    }

    if ( ! function_exists( 'tecs_start_plugin_tracking' ) ) {
        function tecs_start_plugin_tracking() {
            $wisdom = new TECS_Plugin_Usage_Tracker(
                __FILE__,
                ( defined( 'TRACK_TEST' ) && TRACK_TEST ) ? 'http://track.test/' : 'https://track.eventcalendarnewsletter.com/',
                [],
                true,
                true,
                2
            );
        }
        tecs_start_plugin_tracking();
    }
}
