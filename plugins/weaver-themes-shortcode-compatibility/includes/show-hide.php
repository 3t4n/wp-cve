<?php
// ===============  [show_hide_if] ===================
function wvr_compat_show_hide_if($args = '', $text, $show) {
    extract(shortcode_atts(array(
        'device'    => 'default',       // desktop, mobile, smalltablet, phone, all
	    'logged_in' => 'default',       // true or false
        'not_post_id' => 'default',     // comma separated list of post IDs (includes pages, too)
        'post_id'   => 'default',       // comma separated list
        'user_can'  => 'default'        // http://codex.wordpress.org/Function_Reference/current_user_can
    ), $args));

    $valid_device = array('default','desktop','mobile','smalltablet','phone','all');

    if ( !in_array( $device, $valid_device )) {
        return '<br /><strong>Error with [hide/show_if]: <u>' . $device . '</u> not valid for <em>device</em> parameter.</strong><br />';

    }
    if ( $logged_in == 'default' ) {            // **** logged_in
        $logged_in = true;
    } else {
        $is_true = is_user_logged_in();
        $logged_in = ( $logged_in == 'true' || $logged_in == '1' ) ? $is_true : !$is_true;
    }

    if ( $not_post_id == 'default') {                 // **** pages
        $not_post_id = true;
    } else {
        $list = explode(',', str_replace(' ', '', $not_post_id));
        $not_post_id = !in_array( get_the_ID(), $list );
    }

    if ( $post_id == 'default') {                 // **** pages
        $post_id = true;
    } else {
        $list = explode(',', str_replace(' ', '', $post_id));
        $post_id = in_array( get_the_ID(), $list );
    }

    if ( $user_can == 'default') {              // **** user_can
        $user_can = true;
    } else {
        $user_can = current_user_can( strtolower( $user_can) );
    }

    $x = true;
    if ( $x == 'default') {
        $x = true;
    } else {
        $x = $show;
    }

    $all_true = $logged_in && $not_post_id && $post_id && $user_can;    // all true except device

    if ( !$all_true ) {                         // device irrelevant
        // $text .= '* ALL TRUE FAILED *';
        if ( !$show )
            return do_shortcode( $text );       // hide fails, so show it
        else
            return '';                          // show fails, so hide it

    } elseif ( $device == 'default') {          // so all other conditions passed, see if specified device
        // $text .= '* ALL TRUE, DEVICE DEFAULT *';
        if ( $show )
            return do_shortcode( $text );
        else
            return '';
    } else {
        // $text .= '* ALL TRUE, DEPENDS ON DEVICE *';
        if ( $show ) {
            $GLOBALS['wvrx_sc_show_hide'] = strtolower('show-' . $device);  // for [extra_menu]
        } else {
            $GLOBALS['wvrx_sc_show_hide'] = strtolower('hide-' . $device);
        }
        $ret = '<div class="wvr-' . $GLOBALS['wvrx_sc_show_hide'] . '">' . do_shortcode($text) . '</div>';
        unset( $GLOBALS['wvrx_sc_show_hide'] );
        return $ret;
    }
    return '';
}

?>
