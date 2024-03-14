<?php
add_action( 'siteorigin_panels_before_content', 'rsc_maybe_hide_site_origin_content_before', 99, 3 );
function rsc_check_if_site_origin_page_should_be_restricted( $return_message = false, $page_id = false ) {

    $rsc_content_availability = get_post_meta( $page_id, '_rsc_content_availability', true );

    if ( empty( $rsc_content_availability ) ) {
        $rsc_content_availability = 'everyone';
    }

    $rsc_content_availability = apply_filters( 'rsc_content_availability', $rsc_content_availability, $page_id );

    if ( $rsc_content_availability !== 'everyone' ) {
        $value_array = get_post_meta( $page_id );
        $value_array[ 'id' ] = $page_id;

        if ( ! Restricted_Content::can_access( $value_array ) ) {
            $message = do_shortcode( '[RSC id="' . $page_id . '" type="' . $rsc_content_availability . '"]' );
            if ( $return_message ) {
                return $message;
            } else {
                return true;
            }
        } else { // Current user CAN access the content so we don't need to restrict it
            return false;
        }
    } else { // Content availability is set to everyone so we don't need to restrict the content of the page
        return false;
    }

}

function rsc_maybe_hide_site_origin_content_before( $content, $panels_data, $post_id ) {
    ob_start();
}

add_action( 'siteorigin_panels_after_content', 'rsc_maybe_hide_site_origin_content_after', 99, 3 );
function rsc_maybe_hide_site_origin_content_after( $content, $panels_data, $post_id ) {
    $content = ob_get_clean();
    $message = rsc_check_if_site_origin_page_should_be_restricted( true, $post_id );
    echo ( false !== $message ) ? $message : $content;
}
?>
