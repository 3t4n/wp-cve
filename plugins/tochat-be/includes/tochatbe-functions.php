<?php
defined( 'ABSPATH' ) || exit;

function tochatbe_get_option( $option, $section, $default = '' ) {
    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }

    return $default;
}

function tochatbe_appearance_option( $option, $default = '' ) {
    return tochatbe_get_option( $option, 'tochatbe_appearance_settings', $default );
}

function tochatbe_basic_option( $option, $default = '' ) {
    return tochatbe_get_option( $option, 'tochatbe_basic_settings', $default );
}

function tochatbe_google_analytics_option( $option, $default = '' ) {
    return tochatbe_get_option( $option, 'tochatbe_google_analytics_settings', $default );
}

function tochatbe_facebook_analytics_option( $option, $default = '' ) {
    return tochatbe_get_option( $option, 'tochatbe_facebook_analytics_settings', $default );
}

function tochatbe_woo_order_button_option( $option, $default = '' ) {
    return tochatbe_get_option( $option, 'tochatbe_woo_order_button_settings', $default );
}

function tochatbe_get_schedule_option( $day, $result ) {
    $schedule = tochatbe_basic_option( 'schedule' );

    if ( ! isset( $schedule[$day] ) ) {
        return false;
    }
    if ( ! isset( $schedule[$day][$result] ) ) {
        return false;
    }

    return $schedule[$day][$result];
}

function tochatbe_get_filter_by_pages_option( $option ) {
    $filter = tochatbe_basic_option( 'filter_by_pages' );

    if ( ! isset( $filter[$option] ) ) {
        return false;
    }

    return $filter[$option];
}

function tochatbe_gdpr_option( $option, $default = '' ) {
    return tochatbe_get_option( $option, 'tochatbe_gdpr_settings', $default );
}

function tochatbe_type_and_chat_option( $option, $default = '' ) {
    return tochatbe_get_option( $option, 'tochatbe_type_and_chat_settings', $default );
}

function tochatbe_just_whatsapp_icon_option( $option, $default = '' ) {
    return tochatbe_get_option( $option, 'tochatbe_just_whatsapp_icon_settings', $default );
}

function tochatbe_gdpr_check() {
    $policy_page_id     = tochatbe_gdpr_option( 'privacy_page' );
    $policy_page_title  = get_the_title( $policy_page_id );
    $policy_page_url    = get_the_permalink( $policy_page_id );
    $message            = str_replace( 
        '{policy_page}', 
        sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $policy_page_url ), esc_html( $policy_page_title ) ), 
        esc_textarea( tochatbe_gdpr_option( 'message' ) ) 
    );

    if ( 'yes' === tochatbe_gdpr_option( 'status' ) ) {
        ?>
            <div class="tochatbe-gdpr">
                <label for="tochatbe-gdpr-checkbox">
                    <input type="checkbox" id="tochatbe-gdpr-checkbox"> <?php echo $message //WPCS: XSS ok. ?>
                </label>
            </div>
        <?php
    }
}

function tochatbe_get_current_url() {
    global $wp;
    
    return home_url( add_query_arg( array(), $wp->request ) );
}

function tochatbe_page_dropdown( $args = array() ) {
    $html   = '';
    $multiple = ( isset( $args['multiple'] ) ) ? 'multiple' : '';
    $name = ( isset( $args['name'] ) ) ? 'name="' . esc_html( $args['name'] ) . '"' : '';
    $class = ( isset( $args['class'] ) ) ? 'class="' . esc_html( $args['class'] ) . '"' : '';
    
    $query = new WP_Query(
        array(
            'post_type'         => 'page',
            'posts_per_page'    => -1,
        )
    );

    $html .= "<select $class $name $multiple>" . PHP_EOL;
    
    if ( $query->have_posts() ) {
        while( $query->have_posts() ) { $query->the_post();
            $selected = '';
            if ( $multiple && isset( $args['selected'] ) && is_array( $args['selected'] ) ) {
                $selected = in_array( get_the_ID(), $args['selected'] ) ? 'selected' : '';
            }

            $html .= '<option value="' . get_the_ID() . '" ' . $selected . '>' . get_the_title() . '</option>' . PHP_EOL;
        }

        wp_reset_postdata();
    }

    $html .= '</select>' . PHP_EOL;

    echo $html;
}

/**
 * Get WooCommerce order statuses.
 *
 * @return array|false Array of statuses, false if WC is not active.
 */
function tochatbe_get_woo_order_statuses() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return false;
    }

    return wc_get_order_statuses();
}