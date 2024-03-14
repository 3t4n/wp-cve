<?php
global $rsc_is_shop_page;
$rsc_is_shop_page = false;

add_action( 'woocommerce_before_main_content', 'rsc_get_current_page_id' );
function rsc_get_current_page_id() {
    global $rsc_is_shop_page;
    $rsc_is_shop_page = is_shop();
}

function rsc_check_if_shop_page_should_be_restricted( $return_message = false ) {

  global $rsc_is_shop_page;
    $shop_page_id = get_option( 'woocommerce_shop_page_id' );

    if ( $rsc_is_shop_page ) { // We're on the shop page

        $rsc_content_availability = get_post_meta( $shop_page_id, '_rsc_content_availability', true );

        if ( empty( $rsc_content_availability ) ) {
            $rsc_content_availability = 'everyone';
        }

        $rsc_content_availability = apply_filters( 'rsc_content_availability', $rsc_content_availability, $shop_page_id );

        if ( $rsc_content_availability !== 'everyone' ) {
            $value_array = get_post_meta( $shop_page_id );
            $value_array[ 'id' ] = $shop_page_id;

            if ( ! Restricted_Content::can_access( $value_array ) ) {
                $message = do_shortcode( '[RSC id="' . $shop_page_id . '" type="' . $rsc_content_availability . '"]' );
                return ( $return_message ) ? $message : true;

            } else { // Current user CAN access the content so we don't need to restrict it
                return false;
            }

        } else { // Content availability is set to everyone so we don't need to restrict the content of the page
            return false;
        }

    } else {
        return false;
    }
}

add_action( 'woocommerce_before_shop_loop', 'rsc_maybe_hide_main_shop_content_before' );
function rsc_maybe_hide_main_shop_content_before() {
    ob_start();
}

add_action( 'woocommerce_after_shop_loop', 'rsc_maybe_hide_main_shop_content_after' );
function rsc_maybe_hide_main_shop_content_after() {
    $content = ob_get_clean();
    $message = rsc_check_if_shop_page_should_be_restricted( true );
    echo ( false !== $message ) ? $message : $content;
}

?>
