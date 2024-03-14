<?php

/**
 * Widget logic class, includes form excludes front end display
 */
namespace WidgetForEventbriteAPI\Includes;

use  stdClass ;
use  WP_Widget ;
use  ActionScheduler_Store ;
class EventBrite_API_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array(
            'classname'                   => 'widget_eventbrite_events widget',
            'description'                 => __( 'An advanced widget that calls the Eventbrite API plugin to allow you to display your forthcoming events' ),
            'customize_selective_refresh' => true,
        );
        $control_ops = array(
            'width'  => 400,
            'height' => 350,
        );
        $this->utilities = new Utilities();
        parent::__construct(
            'eventbrite-events',
            __( 'Widget for Eventbrite' ),
            $widget_ops,
            $control_ops
        );
        $this->alt_option_name = 'widget_eventbrite_events';
    }
    
    public static function default_args()
    {
        //@TODO think about refactor into one
        /** @var \Freemius $wfea_fs Freemius global object. */
        global  $wfea_fs ;
        $defaults = array(
            'title'                  => esc_attr__( 'Upcoming Events', 'widget-for-eventbrite-api' ),
            'title_url'              => '',
            'limit'                  => 5,
            'excerpt'                => false,
            'length'                 => 10,
            'date'                   => true,
            'readmore'               => false,
            'readmore_text'          => __( 'Read More &raquo;', 'widget-for-eventbrite-api' ),
            'booknow'                => false,
            'booknow_text'           => __( 'Book Now &raquo;', 'widget-for-eventbrite-api' ),
            'thumb'                  => true,
            'thumb_width'            => 300,
            'thumb_default'          => 'https://dummyimage.com/300x200/f0f0f0/ccc',
            'thumb_align'            => 'eaw-aligncenter',
            'cssid'                  => '',
            'css_class'              => '',
            'before'                 => '',
            'after'                  => '',
            'layout'                 => '1',
            'newtab'                 => false,
            'tickets'                => false,
            'long_description'       => false,
            'long_description_modal' => false,
            'status_live'            => true,
            'status_live'            => true,
            'status_ended'           => false,
            'status_started'         => false,
            'deduplicate'            => true,
            'order_by'               => 'start_asc',
        );
        // Allow plugins/themes developer to filter the default arguments.
        return apply_filters( 'eawp_default_args', $defaults );
    }
    
    /**
     * Outputs the settings form for the EventBrite widget.
     *
     */
    public function form( $instance )
    {
        /** @var \Freemius $wfea_fs Freemius global object. */
        global  $wfea_fs ;
        // Merge the user-selected arguments with the defaults.
        $instance = wp_parse_args( (array) $instance, self::default_args() );
        // Extract the array to allow easy use of variables.
        extract( $instance );
        
        if ( $wfea_fs->is_trial() ) {
            ?>
            <div class="notice inline notice-info notice-alt"><p>
					<?php 
            echo  esc_html__( 'You are in the Free trial:', 'widget-for-eventbrite-api' ) ;
            ?>
                    &nbsp;<a href="<?php 
            echo  esc_url( $wfea_fs->get_upgrade_url() ) ;
            ?>">
						<?php 
            echo  esc_html__( 'Upgrade to Pro', 'widget-for-eventbrite-api' ) ;
            ?>
                    </a>&nbsp;
					<?php 
            echo  esc_html__( 'to keep benefits', 'widget-for-eventbrite-api' ) ;
            ?>
                </p>
            </div>
		<?php 
        } elseif ( $wfea_fs->is_free_plan() ) {
            ?>
            <div class="notice inline notice-info notice-alt"><p>
					<?php 
            echo  esc_html__( 'Upgrade to Pro.', 'widget-for-eventbrite-api' ) ;
            ?>
                    &nbsp;
                    <a href="<?php 
            echo  esc_url( $wfea_fs->get_upgrade_url() ) ;
            ?>">
						<?php 
            echo  esc_html__( 'FREE 14 day trial.', 'widget-for-eventbrite-api' ) ;
            ?>
                    </a>&nbsp;
					<?php 
            echo  esc_html__( 'Lots of great features.', 'widget-for-eventbrite-api' ) ;
            ?>
                </p>
            </div>
		<?php 
        }
        
        ?>


        <div class="eaw-columns-2">
            <p>
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'title' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'Title', 'widget-for-eventbrite-api' );
        ?>
                </label>
                <input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'title' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'title' ) ) ;
        ?>" type="text"
                       value="<?php 
        echo  esc_attr( $instance['title'] ) ;
        ?>"/>
            </p>

            <p>
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'title_url' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'Title URL', 'widget-for-eventbrite-api' );
        ?>
                </label>
                <input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'title_url' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'title_url' ) ) ;
        ?>" type="text"
                       value="<?php 
        echo  esc_url( $instance['title_url'] ) ;
        ?>"/>
            </p>

            <p>
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'cssid' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'CSS ID', 'widget-for-eventbrite-api' );
        ?>
                </label>
                <input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'cssid' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'cssid' ) ) ;
        ?>" type="text"
                       value="<?php 
        echo  esc_attr( $instance['cssid'] ) ;
        ?>"/>
            </p>

            <p>
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'css_class' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'CSS Class', 'widget-for-eventbrite-api' );
        ?>
                </label>
                <input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'css_class' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'css_class' ) ) ;
        ?>" type="text"
                       value="<?php 
        echo  esc_attr( $instance['css_class'] ) ;
        ?>"/>
            </p>

            <p>
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'before' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'HTML or text before the recent posts', 'widget-for-eventbrite-api' );
        ?>
                </label>
                <textarea class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'before' ) ) ;
        ?>"
                          name="<?php 
        echo  esc_attr( $this->get_field_name( 'before' ) ) ;
        ?>"
                          sc_attr(
                          rows="5"><?php 
        echo  wp_kses_post( stripslashes( $instance['before'] ) ) ;
        ?></textarea>
            </p>

            <p>
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'after' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'HTML or text after the recent posts', 'widget-for-eventbrite-api' );
        ?>
                </label>
                <textarea class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'after' ) ) ;
        ?>"
                          name="<?php 
        echo  esc_attr( $this->get_field_name( 'after' ) ) ;
        ?>"
                          rows="5"><?php 
        echo  wp_kses_post( stripslashes( $instance['after'] ) ) ;
        ?></textarea>
            </p>


            <p>
                <input id="<?php 
        echo  esc_attr( $this->get_field_id( 'booknow' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'booknow' ) ) ;
        ?>"
                       type="checkbox" <?php 
        checked( $instance['booknow'] );
        ?> />
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'booknow' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'Display Book Now Button', 'widget-for-eventbrite-api' );
        ?>
                </label>
            </p>
			<?php 
        ?>


            <p>
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'booknow_text' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'Book Now Text', 'widget-for-eventbrite-api' );
        ?>
                </label>
                <input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'booknow_text' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'booknow_text' ) ) ;
        ?>" type="text"
                       value="<?php 
        echo  esc_attr( $instance['booknow_text'] ) ;
        ?>"/>
            </p>
        </div>

        <div class="eaw-columns-2 eaw-column-last">
			<?php 
        ?>

            <p>
                <input class="checkbox" type="checkbox" <?php 
        checked( $instance['newtab'], 1 );
        ?>
                       id="<?php 
        echo  esc_attr( $this->get_field_id( 'newtab' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'newtab' ) ) ;
        ?>"/>
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'newtab' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'Open Eventbrite in a new tab', 'widget-for-eventbrite-api' );
        ?>
                </label>
            </p>


            <p>
                <input id="<?php 
        echo  esc_attr( $this->get_field_id( 'date' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'date' ) ) ;
        ?>"
                       type="checkbox" <?php 
        checked( $instance['date'] );
        ?> />
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'date' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'Display Date / Time', 'widget-for-eventbrite-api' );
        ?>
                </label>
            </p>


            <p>
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'limit' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'Number of posts to show', 'widget-for-eventbrite-api' );
        ?>
                </label>
                <input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'limit' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'limit' ) ) ;
        ?>" type="number" step="1"
                       min="-1"
                       value="<?php 
        echo  (int) $instance['limit'] ;
        ?>"/>
            </p>

			<?php 
        
        if ( current_theme_supports( 'post-thumbnails' ) ) {
            ?>

                <p>
                    <input id="<?php 
            echo  esc_attr( $this->get_field_id( 'thumb' ) ) ;
            ?>"
                           name="<?php 
            echo  esc_attr( $this->get_field_name( 'thumb' ) ) ;
            ?>"
                           type="checkbox" <?php 
            checked( $instance['thumb'] );
            ?> />
                    <label for="<?php 
            echo  esc_attr( $this->get_field_id( 'thumb' ) ) ;
            ?>">
						<?php 
            esc_html_e( 'Display Thumbnail', 'widget-for-eventbrite-api' );
            ?>
                    </label>
                </p>

                <p>
                    <label class="eaw-block" for="<?php 
            echo  esc_attr( $this->get_field_id( 'thumb_width' ) ) ;
            ?>">
						<?php 
            esc_html_e( 'Thumbnail (width,align)', 'widget-for-eventbrite-api' );
            ?>
                    </label>
                    <input class="small-input" id="<?php 
            echo  esc_attr( $this->get_field_id( 'thumb_width' ) ) ;
            ?>"
                           name="<?php 
            echo  esc_attr( $this->get_field_name( 'thumb_width' ) ) ;
            ?>" type="number"
                           step="1" min="0"
                           value="<?php 
            echo  (int) $instance['thumb_width'] ;
            ?>"/>
                    <select class="small-input" id="<?php 
            echo  esc_attr( $this->get_field_id( 'thumb_align' ) ) ;
            ?>"
                            name="<?php 
            echo  esc_attr( $this->get_field_name( 'thumb_align' ) ) ;
            ?>">
                        <option value="eaw-alignleft" <?php 
            selected( $instance['thumb_align'], 'eaw-alignleft' );
            ?>><?php 
            esc_html_e( 'Left', 'widget-for-eventbrite-api' );
            ?></option>
                        <option value="eaw-alignright" <?php 
            selected( $instance['thumb_align'], 'eaw-alignright' );
            ?>><?php 
            esc_html_e( 'Right', 'widget-for-eventbrite-api' );
            ?></option>
                        <option value="eaw-aligncenter" <?php 
            selected( $instance['thumb_align'], 'eaw-aligncenter' );
            ?>><?php 
            esc_html_e( 'Center', 'widget-for-eventbrite-api' );
            ?></option>
                    </select>
                </p>

                <p>
                    <label for="<?php 
            echo  esc_attr( $this->get_field_id( 'thumb_default' ) ) ;
            ?>">
						<?php 
            esc_html_e( 'Default Thumbnail', 'widget-for-eventbrite-api' );
            ?>
                    </label>
                    <input class="widefat" id="<?php 
            echo  esc_attr( $this->get_field_id( 'thumb_default' ) ) ;
            ?>"
                           name="<?php 
            echo  esc_attr( $this->get_field_name( 'thumb_default' ) ) ;
            ?>" type="text"
                           value="<?php 
            echo  esc_attr( $instance['thumb_default'] ) ;
            ?>"/>
                    <small><?php 
            esc_html_e( 'Leave it blank to disable.', 'widget-for-eventbrite-api' );
            ?></small>
                </p>

			<?php 
        }
        
        ?>

            <p>
                <input id="<?php 
        echo  esc_attr( $this->get_field_id( 'excerpt' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'excerpt' ) ) ;
        ?>"
                       type="checkbox" <?php 
        checked( $instance['excerpt'] );
        ?> />
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'excerpt' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'Display Event Description', 'widget-for-eventbrite-api' );
        ?>
                </label>
            </p>

            <p>
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'length' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'Description Length', 'widget-for-eventbrite-api' );
        ?>
                </label>
                <input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'length' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'length' ) ) ;
        ?>" type="number" step="1"
                       min="0"
                       value="<?php 
        echo  (int) $instance['length'] ;
        ?>"/>
            </p>

            <p>
                <input id="<?php 
        echo  esc_attr( $this->get_field_id( 'readmore' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'readmore' ) ) ;
        ?>"
                       type="checkbox" <?php 
        checked( $instance['readmore'] );
        ?> />
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'readmore' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'Display Readmore', 'widget-for-eventbrite-api' );
        ?>
                </label>
            </p>

            <p>
                <label for="<?php 
        echo  esc_attr( $this->get_field_id( 'readmore_text' ) ) ;
        ?>">
					<?php 
        esc_html_e( 'Readmore Text', 'widget-for-eventbrite-api' );
        ?>
                </label>
                <input class="widefat" id="<?php 
        echo  esc_attr( $this->get_field_id( 'readmore_text' ) ) ;
        ?>"
                       name="<?php 
        echo  esc_attr( $this->get_field_name( 'readmore_text' ) ) ;
        ?>" type="text"
                       value="<?php 
        echo  esc_attr( $instance['readmore_text'] ) ;
        ?>"/>
            </p>


        </div>

        <div class="clear"></div>


		<?php 
    }
    
    /**
     * Handles updating the settings for the current EventBrite widget instance.
     *
     */
    public function update( $new_instance, $old_instance )
    {
        /** @var \Freemius $wfea_fs Freemius global object. */
        global  $wfea_fs ;
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['title_url'] = esc_url_raw( $new_instance['title_url'] );
        $instance['number'] = ( isset( $new_instance['number'] ) ? (int) $new_instance['number'] : 0 );
        $instance['excerpt'] = ( isset( $new_instance['excerpt'] ) ? (bool) $new_instance['excerpt'] : false );
        $instance['length'] = intval( $new_instance['length'] );
        $instance['date'] = ( isset( $new_instance['date'] ) ? (bool) $new_instance['date'] : false );
        $instance['readmore'] = ( isset( $new_instance['readmore'] ) ? (bool) $new_instance['readmore'] : false );
        $instance['readmore_text'] = sanitize_text_field( $new_instance['readmore_text'] );
        $instance['booknow'] = ( isset( $new_instance['booknow'] ) ? (bool) $new_instance['booknow'] : false );
        $instance['booknow_text'] = sanitize_text_field( $new_instance['booknow_text'] );
        $instance['limit'] = intval( $new_instance['limit'] );
        $instance['thumb'] = ( isset( $new_instance['thumb'] ) ? (bool) $new_instance['thumb'] : false );
        $instance['thumb_width'] = intval( $new_instance['thumb_width'] );
        $instance['thumb_default'] = esc_url_raw( $new_instance['thumb_default'] );
        $instance['thumb_align'] = esc_attr( $new_instance['thumb_align'] );
        $instance['cssid'] = sanitize_html_class( $new_instance['cssid'] );
        $instance['css_class'] = sanitize_html_class( $new_instance['css_class'] );
        $instance['newtab'] = ( isset( $new_instance['newtab'] ) ? (bool) $new_instance['newtab'] : false );
        $instance['tickets'] = ( isset( $new_instance['tickets'] ) ? (bool) $new_instance['tickets'] : false );
        $instance['order_by'] = ( isset( $new_instance['order_by'] ) ? sanitize_text_field( $new_instance['order_by'] ) : 'start_asc' );
        $instance['status_live'] = ( isset( $new_instance['status_live'] ) ? (bool) $new_instance['status_live'] : false );
        $instance['status_ended'] = ( isset( $new_instance['status_ended'] ) ? (bool) $new_instance['status_ended'] : false );
        $instance['status_started'] = ( isset( $new_instance['status_started'] ) ? (bool) $new_instance['status_started'] : false );
        if ( false === $instance['status_live'] && false === $instance['status_ended'] && false === $instance['status_started'] ) {
            $instance['status_live'];
        }
        
        if ( current_user_can( 'unfiltered_html' ) ) {
            $instance['before'] = $new_instance['before'];
        } else {
            $instance['before'] = wp_kses_post( $new_instance['before'] );
        }
        
        
        if ( current_user_can( 'unfiltered_html' ) ) {
            $instance['after'] = $new_instance['after'];
        } else {
            $instance['after'] = wp_kses_post( $new_instance['after'] );
        }
        
        return $instance;
    }
    
    /**
     * Outputs the content for the current EventBrite events widget instance.
     *
     */
    public function widget( $args, $instance )
    {
        /** @var \Freemius $wfea_fs Freemius global object. */
        global  $wfea_fs ;
        extract( $args );
        // Merge the input arguments and the defaults.
        $instance = wp_parse_args( (array) $instance, $this->default_args() );
        // Query arguments.
        $query = array(
            'nopaging' => true,
            'limit'    => $instance['limit'],
        );
        $query['order_by'] = $instance['order_by'];
        $status = array();
        if ( $instance['status_live'] ) {
            $status[] = 'live';
        }
        if ( $instance['status_ended'] ) {
            $status[] = 'ended';
        }
        if ( $instance['status_started'] ) {
            $status[] = 'started';
        }
        $query['status'] = implode( ',', $status );
        if ( empty($query['status']) ) {
            $query['status'] = 'live';
        }
        global  $wfea_instance_counter ;
        $wfea_instance_counter++;
        // Allow plugins/themes developer to filter the default query.
        $query = apply_filters( 'eawp_default_query_arguments', $query );
        // Perform the query.
        $events = new Eventbrite_Query( $query );
        $html = '';
        
        if ( is_wp_error( $events->api_results ) ) {
            
            if ( current_user_can( 'manage_options' ) ) {
                $error_string = $this->utilities->get_api_error_string( $events->api_results );
                $html .= $admin_msg . '<div class="wfea error">' . $error_string . '</div>';
            }
        
        } else {
            $template = 'layout_widget';
            $theme = wp_get_theme();
            $template_loader = new Template_Loader();
            $template_loader->set_template_data( array(
                'template_loader' => $template_loader,
                'events'          => $events,
                'args'            => $instance,
                'template'        => strtolower( $theme->template ),
                'plugin_name'     => 'widget-for-eventbrite-api',
                'utilities'       => $this->utilities,
                'unique_id'       => uniqid(),
                'instance'        => $wfea_instance_counter,
                'event'           => new stdClass(),
            ) );
            ob_start();
            $template_loader->get_template_part( $template );
            $html .= ob_get_clean();
        }
        
        $recent = wp_kses_post( $instance['before'] ) . apply_filters( 'eawp_markup', $html ) . wp_kses_post( $instance['after'] );
        // Restore original Post Data.
        wp_reset_postdata();
        // Allow devs to hook in stuff after the loop.
        do_action( 'eawp_after_loop' );
        // Return the  posts markup.
        
        if ( $recent ) {
            // Output the theme's $before_widget wrapper.
            echo  wp_kses_post( $before_widget ) ;
            // If both title and title url is not empty, display it.
            
            if ( !empty($instance['title_url']) && !empty($instance['title']) ) {
                echo  wp_kses_post( $before_title . '<a href="' . esc_url( $instance['title_url'] ) . '" title="' . esc_attr( $instance['title'] ) . '">' . apply_filters(
                    'widget_title',
                    $instance['title'],
                    $instance,
                    $this->id_base
                ) . '</a>' . $after_title ) ;
                // If the title not empty, display it.
            } elseif ( !empty($instance['title']) ) {
                echo  wp_kses_post( $before_title . apply_filters(
                    'widget_title',
                    $instance['title'],
                    $instance,
                    $this->id_base
                ) . $after_title ) ;
            }
            
            // Get the recent posts query.
            echo  wp_kses_post( $recent ) ;
            // Close the theme's widget wrapper.
            echo  wp_kses_post( $after_widget ) ;
        }
    
    }

}