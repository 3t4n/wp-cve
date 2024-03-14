<?php
class WP_School_Calendar_Widget_Important_Dates extends WP_Widget {
    
    protected $defaults;
    
    /**
     * Sets up a new Upcoming Important Date widget instance.
     * 
     * @since 1.0
     */
    function __construct() {
        $this->defaults = array(
            'title'              => __( 'Dates to Remember', 'wp-school-calendar' ),
            'num_important_date' => 5,
            'calendar'           => '',
            'groups'             => array(),
            'categories'         => array(),
            'include_no_groups'  => true,
            'date_format'        => 'medium',
            'show_year'          => false,
            'calendar_page'      => '',
            'custom_url'         => '',
        );

        $widget_slug = 'widget-wpsc-upcoming-important-dates';

        $widget_ops = array(
            'classname'   => $widget_slug,
            'description' => esc_html_x( 'Display important dates.', 'Widget', 'wp-school-calendar' ),
        );

        $control_ops = array(
            'id_base' => $widget_slug,
        );

        parent::__construct( $widget_slug, esc_html_x( 'WPSC - Important Dates', 'Widget', 'wp-school-calendar' ), $widget_ops, $control_ops );
    }
    
    /**
     * Outputs the content for the current Upcoming Important Date widget instance.
     * 
     * @since 1.0
     * 
     * @global WP_Locale $wp_locale WP_Locale object
     * @param array $args       Widget arguments
     * @param array $instance   Widget instance
     */
    function widget( $args, $instance ) {
        $instance = wp_parse_args( ( array ) $instance, $this->defaults );
        
        $important_date_args = array(
            'start_date'     => date( 'Y-m-d' ),
            'end_date'       => date( 'Y-m-d', strtotime( '1year' ) ),
            'posts_per_page' => $instance['num_important_date']
        );
        
        if ( '' !== $instance['calendar'] ) {
            $calendar_id = intval( $instance['calendar'] );
            $calendar = wpsc_get_calendar( $calendar_id );
        
            if ( isset( $calendar['groups'] ) ) {
                $important_date_args['groups'] = $calendar['groups'];
            }

            if ( isset( $calendar['include_no_groups'] ) ) {
                $important_date_args['include_no_groups'] = $calendar['include_no_groups'];
            }

            if ( isset( $calendar['categories'] ) ) {
                $important_date_args['categories'] = $calendar['categories'];
            }
        }
        
        if ( isset( $instance['groups'] ) && is_array( $instance['groups'] ) && count( $instance['groups'] ) > 0 ) {
            $important_date_args['groups'] = $instance['groups'];
            
            if ( isset( $instance['include_no_groups'] ) ) {
                $include_no_groups = $instance['include_no_groups'] ? 'Y' : 'N';
                $important_date_args['include_no_groups'] = $include_no_groups;
            }
        }
        
        if ( isset( $instance['categories'] ) && is_array( $instance['categories'] ) && count( $instance['categories'] ) > 0 ) {
            $important_date_args['categories'] = $instance['categories'];
        }
        
        $upcoming_important_dates = wpsc_get_important_dates( $important_date_args );

        echo $args['before_widget'];

        if ( !empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        if ( empty( $upcoming_important_dates ) ) {
            echo '<p>', esc_html__( 'No Important Dates', 'wp-school-calendar' ), '</p>';
        } else {
            echo '<ul>';
            
            global $wp_locale;
            
            $show_year = $instance['show_year'] ? 'Y' : 'N';
            
            foreach ( $upcoming_important_dates as $important_date ) {
                $date_string = wpsc_format_date( $important_date['start_date'], $important_date['end_date'], $instance['date_format'], $show_year );
                
                printf( '<li class="">' );
                printf( '<div class="wpsc-upcoming-important-date-date">%s</div>', $date_string );
                printf( '<div class="wpsc-upcoming-important-date-title">%s</div>', wpsc_normalize_special_character_for_html( $important_date['important_date_title'] ) );
                echo '</li>';
            }
            
            echo '</ul>';
            
            if ( '' !== $instance['custom_url']  ) {
                printf( '<div class="wpsc-upcoming-important-date-more"><a href="%s">%s</a></div>', $instance['custom_url'], __( 'Show Calendar', 'wp-school-calendar' ) );
            } elseif ( '' !== $instance['calendar_page'] ) {
                printf( '<div class="wpsc-upcoming-important-date-more"><a href="%s">%s</a></div>', get_permalink( $instance['calendar_page'] ), __( 'Show Calendar', 'wp-school-calendar' ) );
            }
        }
        
        echo $args['after_widget'];
    }
    
    /**
     * Handles updating settings for the current Upcoming Important Date widget instance.
     * 
     * @since 1.0
     * 
     * @param array $new_instance   New widget instance
     * @param array $old_instance   Old widget instance
     * @return array New widget instance
     */
    function update( $new_instance, $old_instance ) {
        $new_instance['title']              = wp_strip_all_tags( $new_instance['title'] );
        $new_instance['num_important_date'] = absint( $new_instance['num_important_date'] );
        $new_instance['calendar']           = empty( $new_instance['calendar'] ) ? '' : absint( $new_instance['calendar'] );
        $new_instance['categories']         = empty( $new_instance['categories'] ) ? array() : $new_instance['categories'];
        $new_instance['groups']             = empty( $new_instance['groups'] ) ? array() : $new_instance['groups'];
        $new_instance['include_no_groups']  = isset( $new_instance['include_no_groups'] ) ? '1' : false;
        $new_instance['date_format']        = $new_instance['date_format'];
        $new_instance['show_year']          = isset( $new_instance['show_year'] ) ? '1' : false;
        $new_instance['calendar_page']      = absint( $new_instance['calendar_page'] );
        $new_instance['custom_url']         = empty( $new_instance['custom_url'] ) ? '' : sanitize_url( $new_instance['custom_url'] );

        return $new_instance;
    }
    
    /**
     * Outputs the settings form for the Upcoming Important Date widget.
     * 
     * @since 1.0
     * 
     * @param array $instance Array of widget instance
     */
    function form( $instance ) {
        $instance = wp_parse_args( ( array ) $instance, $this->defaults );
        
        $available_calendars  = wpsc_get_calendars();
        $available_groups     = wpsc_get_groups();
        $available_categories = wpsc_get_categories();
        $date_format_options  = wpsc_get_date_format_options();
        ?>
        <div class="wpsc-widget-important-date-form">
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php esc_html( _ex( 'Title:', 'Widget', 'wp-school-calendar' ) ); ?>
            </label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'num_events' ); ?>">
                <?php esc_html( _ex( 'Number of Events:', 'Widget', 'wp-school-calendar' ) ); ?>
            </label>
            <input type="number" id="<?php echo $this->get_field_id( 'num_events' ); ?>" name="<?php echo $this->get_field_name( 'num_important_date' ); ?>" value="<?php echo esc_attr( $instance['num_important_date'] ); ?>" class="widefat"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'calendar' ); ?>">
                <?php esc_html( _ex( 'Calendar:', 'Widget', 'wp-school-calendar' ) ); ?>
            </label>
            <select id="<?php echo $this->get_field_id( 'calendar' ); ?>" name="<?php echo $this->get_field_name( 'calendar' ); ?>">
                <option value=""><?php echo __( 'Select Calendar', 'wp-school-calendar' ) ?></option>
                <?php foreach ( $available_calendars as $calendar ): ?>
                <option value="<?php echo esc_attr( $calendar['calendar_id'] ) ?>"<?php selected( $calendar['calendar_id'], $instance['calendar'] ) ?>><?php echo esc_html( $calendar['name'] ) ?></option>
                <?php endforeach ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'groups' ); ?>">
                <?php esc_html( _ex( 'Groups:', 'Widget', 'wp-school-calendar' ) ); ?>
            </label>
            <select multiple="multiple" id="<?php echo $this->get_field_id( 'groups' ); ?>" name="<?php echo $this->get_field_name( 'groups[]' ); ?>" class="wpsc-select">
                <?php foreach ( $available_groups as $group ): ?>
                <option value="<?php echo esc_attr( $group['group_id'] ) ?>"<?php selected( in_array( $group['group_id'], $instance['groups'] ) ) ?>><?php echo esc_html( $group['name'] ) ?></option>
                <?php endforeach ?>
            </select>
        </p>
        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id( 'include_no_groups' ); ?>"
					name="<?php echo $this->get_field_name( 'include_no_groups' ); ?>" <?php checked( '1', $instance['include_no_groups'] ); ?>>
			<label for="<?php echo $this->get_field_id( 'include_no_groups' ); ?>"><?php esc_html( _ex( 'Include No Group Important Date', 'Widget', 'wp-school-calendar' ) ); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'categories' ); ?>">
                <?php esc_html( _ex( 'Categories:', 'Widget', 'wp-school-calendar' ) ); ?>
            </label>
            <select multiple="multiple" id="<?php echo $this->get_field_id( 'categories' ); ?>" name="<?php echo $this->get_field_name( 'categories[]' ); ?>">
                <?php foreach ( $available_categories as $category ): ?>
                <option value="<?php echo esc_attr( $category['category_id'] ) ?>"<?php selected( in_array( $category['category_id'], $instance['categories'] ) ) ?>><?php echo esc_html( $category['name'] ) ?></option>
                <?php endforeach ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'date_format' ); ?>">
                <?php esc_html( _ex( 'Date Format:', 'Widget', 'wp-school-calendar' ) ); ?>
            </label>
            <select id="<?php echo $this->get_field_id( 'date_format' ); ?>" name="<?php echo $this->get_field_name( 'date_format' ); ?>" class="widefat">
                <?php foreach ( $date_format_options as $key => $name ): ?>
                <option value="<?php echo $key ?>"<?php selected( $key, $instance['date_format'] ) ?>><?php echo $name ?></option>
                <?php endforeach ?>
            </select>
        </p>
        <p>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_year' ); ?>"
					name="<?php echo $this->get_field_name( 'show_year' ); ?>" <?php checked( '1', $instance['show_year'] ); ?>>
			<label for="<?php echo $this->get_field_id( 'show_year' ); ?>"><?php esc_html( _ex( 'Show Year', 'Widget', 'wp-school-calendar' ) ); ?></label>
		</p>
        <p>
            <label for="<?php echo $this->get_field_id( 'num_events' ); ?>">
                <?php esc_html( _ex( 'Calendar Page:', 'Widget', 'wp-school-calendar' ) ); ?>
            </label>
            <?php wp_dropdown_pages( array( 'show_option_none' => __( 'Use Custom URL', 'wp-school-calendar' ), 'name' => $this->get_field_name( 'calendar_page' ), 'id' => $this->get_field_id( 'calendar_page' ), 'selected' => $instance['calendar_page'] ) ) ?>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'custom_url' ); ?>">
                <?php esc_html( _ex( 'Custom URL:', 'Widget', 'wp-school-calendar' ) ); ?>
            </label>
            <input type="text" id="<?php echo $this->get_field_id( 'custom_url' ); ?>" name="<?php echo $this->get_field_name( 'custom_url' ); ?>" value="<?php echo esc_attr( $instance['custom_url'] ); ?>" class="widefat"/>
        </p>
        </div>
        <?php
    }
}

/**
 * Register widgets
 * 
 * @since 1.0
 */
function wpsc_register_widgets() {
    register_widget( 'WP_School_Calendar_Widget_Important_Dates' );
}

add_action( 'widgets_init', 'wpsc_register_widgets' );
