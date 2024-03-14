<?php

/**
 * Define the supporting functions.
 *
 * @since   0.2.6
 *
 * @package Easy_Marijuana_Age_Verify\Functions
 */
// Don't allow this file to be accessed directly.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * Localization
 *
 * @since 0.1.0
 */
function emav_load_textdomain()
{
    load_plugin_textdomain( 'easy-marijuana-age-verify', false, plugin_basename( dirname( __FILE__ ) ) . '/includes/languages' );
}

add_action( 'plugins_loaded', 'emav_load_textdomain' );
/**
 * Prints the minimum age.
 *
 * @return void
 * @see   emav_get_minimum_age();
 *
 * @since 0.1.0
 */
function emav_minimum_age()
{
    echo  emav_get_minimum_age() ;
}

/**
 * Get the minimum age to view restricted content.
 *
 * @return int $minimum_age The minimum age to view restricted content.
 * @since 0.1.0
 *
 */
function emav_get_minimum_age()
{
    $minimum_age = get_option( '_emav_minimum_age', 21 );
    /**
     * Filter the minimum age.
     *
     * @param int $minimum_age The minimum age to view restricted content.
     *
     * @since 0.1.0
     *
     */
    $minimum_age = apply_filters( 'emav_minimum_age', $minimum_age );
    return (int) $minimum_age;
}

/**
 * Get the visitor's age based on input.
 *
 * @param string $year  The visitor's birth year.
 * @param string $month The visitor's birth month.
 * @param        $day   $day   The visitor's birth day.
 *
 * @return int $age The calculated age.
 * @since 0.1.5
 *
 * @throws Exception
 */
function emav_get_visitor_age( $year, $month, $day )
{
    $age = 0;
    $birthday = new DateTime( $year . '-' . $month . '-' . $day );
    $phpversion = phpversion();
    
    if ( $phpversion >= '5.3' ) {
        $current = new DateTime( current_time( 'mysql' ) );
        $age = $birthday->diff( $current );
        $age = $age->format( '%y' );
    } else {
        list( $year, $month, $day ) = explode( '-', $birthday->format( 'Y-m-d' ) );
        $year_diff = date_i18n( 'Y' ) - $year;
        $month_diff = date_i18n( 'm' ) - $month;
        $day_diff = date_i18n( 'd' ) - $day;
        
        if ( $month_diff < 0 ) {
            $year_diff--;
        } elseif ( $month_diff == 0 && $day_diff < 0 ) {
            $year_diff--;
        }
        
        $age = $year_diff;
    }
    
    return (int) $age;
}

/**
 * Get the cookie duration.
 *
 * This lets us know how long to keep a visitor's
 * verified cookie.
 *
 * @return int $cookie_duration The cookie duration.
 * @since 0.1.0
 *
 */
function emav_get_cookie_duration()
{
    $cookie_duration = get_option( '_emav_cookie_duration', 720 );
    /**
     * Filter the cookie duration.
     *
     * @param int $cookie_duration The cookie duration.
     *
     * @since 0.1.0
     *
     */
    $cookie_duration = (int) apply_filters( 'emav_cookie_duration', $cookie_duration );
    return $cookie_duration;
}

/**
 * Determines whether only certain content should be restricted.
 *
 * @return bool $only_content_restricted Whether the restriction is content-specific or site-wide.
 * @since 0.2.0
 *
 */
function emav_only_content_restricted()
{
    $only_content_restricted = ( 'content' == get_option( '_emav_require_for' ) ? true : false );
    /**
     * Filter whether the restriction is content-specific or site-wide.
     *
     * @param bool $only_content_restricted
     *
     * @since 0.2.0
     *
     */
    $only_content_restricted = apply_filters( 'emav_only_content_restricted', $only_content_restricted );
    return (bool) $only_content_restricted;
}

/**
 * Determines if a certain piece of content is restricted.
 *
 * @return bool $is_restricted Whether a certain piece of content is restricted.
 * @since 0.2.0
 *
 */
function emav_content_is_restricted( $id = null )
{
    if ( is_null( $id ) ) {
        $id = get_the_ID();
    }
    $is_restricted = ( 1 == get_post_meta( $id, '_emav_needs_verify', true ) ? true : false );
    /**
     * Filter whether this content should be restricted.
     *
     * @param bool $is_restricted Whether this content should be restricted.
     * @param int  $id            The content's ID.
     *
     * @since 0.2.6
     *
     */
    $is_restricted = apply_filters( 'emav_is_restricted', $is_restricted, $id );
    return $is_restricted;
}

/**
 * This is the very important function that determines if a given visitor
 * needs to be verified before viewing the site. You can filter this if you like.
 *
 * @return bool
 * @since 0.1
 */
function emav_needs_verification()
{
    // Assume the visitor needs to be verified
    $return = true;
    // If the site is restricted on a per-content basis, let 'em through
    
    if ( emav_only_content_restricted() ) {
        $return = false;
        // If the content being viewed is restricted, throw up the form
        if ( is_singular() && emav_content_is_restricted() ) {
            $return = true;
        }
    }
    
    // Check that the form was at least submitted. This lets visitors through that have cookies disabled.
    $nonce = ( isset( $_REQUEST['emav-age-verified'] ) ? $_REQUEST['emav-age-verified'] : '' );
    if ( wp_verify_nonce( $nonce, 'emav-age-verified' ) ) {
        $return = false;
    }
    // If not logged in Admins
    if ( get_option( '_emav_always_verify', 'admin-only' ) == 'admin-only' && !current_user_can( 'manage_options' ) ) {
        $return = false;
    }
    // If logged in users are exempt, and the visitor is logged in, let 'em through
    if ( get_option( '_emav_always_verify', 'guests' ) == 'guests' && is_user_logged_in() ) {
        $return = false;
    }
    // If logged in users are exempt, and the visitor is logged in, let 'em through
    if ( get_option( '_emav_always_verify', 'disabled' ) == 'disabled' ) {
        $return = false;
    }
    // Or, if there is a valid cookie let 'em through
    if ( isset( $_COOKIE['emav-age-verified'] ) || is_user_logged_in() ) {
        return (bool) apply_filters( 'emav_needs_verification', false );
    }
    return $return;
}

/***********************************************************
 ******************** Display Functions ********************
 ***********************************************************/
/**
 * Returns the form's input type, based on the settings.
 * You can filter this if you like.
 *
 * @return string
 * @since 0.1
 */
function emav_get_input_type()
{
    return apply_filters( 'emav_input_type', get_option( '_emav_input_type', 'dropdowns' ) );
}

/**
 * Echoes the actual form
 *
 * @since 0.1
 * @echo  string
 */
function emav_verify_form()
{
    echo  emav_get_verify_form() ;
}

/**
 * Adds the user's WP role to the Body CSS Class for Testing Mode
 *
 * @since 1.1
 * @echo  string
 */
add_filter( 'body_class', 'emav_output_role_body_class' );
function emav_output_role_body_class( $classes )
{
    $classes[] = emav_get_user_role();
    // Add verify option setting in body CSS to bypass JS ajax call
    array_push( $classes, 'emav-' . get_option( '_emav_always_verify' ) );
    // Add AJAX option check, add to BODY Class if set to True, use in JS to test and perform AJAX call, otherwise bypass AJAX call
    array_push( $classes, get_option( '_emav_ajax_check' ) );
    return $classes;
}

function emav_get_user_role()
{
    global  $current_user ;
    $user_roles = $current_user->roles;
    $user_roles_list = implode( " ", $user_roles );
    return $user_roles_list;
}

/**
 * Returns the all-important verification form.
 * You can filter this if you like.
 *
 * @return string
 * @since 0.1
 */
function emav_get_verify_form()
{
    global  $emav_fs ;
    //$input_type = emav_get_input_type();
    // Marijuana Age Verify Option
    $option = (int) emav_age_verify_option();
    switch ( $option ) {
        case 6:
            $optionID = 5;
            break;
        case 5:
            $optionID = 4;
            break;
        default:
            $optionID = $option;
            break;
    }
    // Button Array Labels
    
    if ( function_exists( 'emav_get_custom_agreebutton_text' ) ) {
        $age_confirm_btn_arr = array(
            '',
            'I am 21 or older',
            'I am 19 or older',
            'I am 18 or older',
            'Yes',
            emav_get_custom_agreebutton_text()
        );
    } else {
        // Button Array Labels
        $age_confirm_btn_arr = array(
            '',
            'I am 21 or older',
            'I am 19 or older',
            'I am 18 or older',
            'Yes',
            'I am 21 or older'
        );
    }
    
    
    if ( function_exists( 'emav_get_custom_disagreebutton_text' ) ) {
        $age_btn_arr = array(
            '',
            'I am under 21',
            'I am under 19',
            'I am under 18',
            'No',
            emav_get_custom_disagreebutton_text()
        );
    } else {
        $age_btn_arr = array(
            '',
            'I am under 21',
            'I am under 19',
            'I am under 18',
            'No',
            'I am under 21'
        );
    }
    
    // Default error text, just in case.
    $no_error_text = __( 'Sorry, you must be of legal age to enter this website.' );
    // Pull in option's error code
    $no_error_text = emav_get_error_text();
    // Selected Button Label Option
    $age_confirm_btn_label = $age_confirm_btn_arr[$optionID];
    $age_btn_label = $age_btn_arr[$optionID];
    $confirm_btn_style = 'style="background-color:#2dc937;"';
    $not_confirm_btn_style = 'style="background-color:#cc3232;"';
    $submit_button_label = apply_filters( 'emav_form_submit_label', __( 'Enter Site &raquo;', 'easy-marijuana-age-verify' ) );
    $form = '';
    $form .= '<form id="emav_verify_form" action="' . esc_url( home_url( '/' ) ) . '" method="post">';
    // This IF will be executed only if the user in a trial mode or have a valid license.
    
    if ( $emav_fs->can_use_premium_code() ) {
        $form .= '<div style="display: none; color:' . emav_getContrastYIQ( ltrim( emav_get_overlay_color(), '#' ) ) . ' !important;" class="emav-error">' . $no_error_text . '</div>';
    } else {
        $form .= '<div style="display: none;" class="emav-error">' . $no_error_text . '</div>';
    }
    
    do_action( 'emav_form_before_inputs' );
    $form .= '<input type="hidden" name="emav_verify_confirm" id="emav_verify_confirm" value="" />';
    $form .= '<div class="emav_buttons"><input type="button" name="confirm_age" id="emav_confirm_age" value="' . $age_confirm_btn_label . '" ' . $confirm_btn_style . ' />';
    $form .= '<div class="emav_buttons_sep"></div>';
    $form .= '<input type="button" name="not_confirm_age" id="emav_not_confirm_age" value="' . $age_btn_label . '" ' . $not_confirm_btn_style . '></div>';
    do_action( 'emav_form_after_inputs' );
    $form .= '</form>';
    return apply_filters( 'emav_verify_form', $form );
}

function emav_get_error_text()
{
    $optionID = 0;
    // Catch-all in case something goes wrong
    $error_msg_arr = array(
        __( 'Sorry, you must be of legal age to enter this website.' ),
        __( 'Sorry, you must be 21 or over to enter this website.' ),
        __( 'Sorry, you must be 19 or over to enter this website.' ),
        __( 'Sorry, you must be 18 or over to enter this website.' ),
        __( 'Sorry, you must be 18 years or over valid medical marijuana patient to enter this website.' ),
        __( 'Sorry, you must be 21 or over, or 18 years or over valid medical marijuana patient, to enter this website.' ),
        __( '' )
    );
    $optionID = (int) emav_age_verify_option();
    return $error_msg_arr[$optionID];
}

/**
 * Returns the overlay background color
 * You can filter this if you like.
 *
 * @return string
 * @since 0.1
 */
function emav_get_overlay_color()
{
    global  $emav_fs ;
    // This IF will be executed only if the user in a trial mode or have a valid license.
    
    if ( $emav_fs->can_use_premium_code() ) {
        if ( get_option( '_emav_overlay_color' ) ) {
            $color = get_option( '_emav_overlay_color' );
        }
    } else {
        $color = '#000000';
    }
    
    return apply_filters( 'emav_overlay_color', $color );
}

/***********************************************************/
/*************** User Registration Functions ***************/
/***********************************************************/
/**
 * Determines whether or not users need to verify their age before
 * registering for the site. You can filter this if you like.
 *
 * @return bool
 * @since 0.1
 */
//add_action( 'wp_ajax_nopriv_emav_user_age_verify', 'emav_user_age_verify' );
//add_action( 'wp_ajax_emav_user_age_verify', 'emav_user_age_verify' );
function emav_user_age_verify()
{
    $is_verified = false;
    $error = 1;
    // Catch-all in case something goes wrong
    $error_msg_arr = array(
        '',
        'Sorry, you must be of legal age to enter this website.',
        'Sorry, you must be 21 or over to enter this website.',
        'Sorry, you must be 19 or over to enter this website.',
        'Sorry, you must be 18 or over to enter this website.',
        'Sorry, you must be an 18 years or over valid medical marijuana patient to enter this website.',
        'Sorry, you must be 21 or over, or an 18 years or over valid medical marijuana patient, to enter this website.'
    );
    $optionID = (int) emav_age_verify_option();
    error_log( $optionID );
    
    if ( isset( $_POST['verifyConfirm'] ) && (int) $_POST['verifyConfirm'] == 1 ) {
        $is_verified = true;
    } else {
        echo  $error_msg_arr[$optionID] ;
        exit;
    }
    
    $is_verified = apply_filters( 'emav_passed_verify', $is_verified );
    
    if ( $is_verified == true ) {
        do_action( 'emav_was_verified' );
        
        if ( isset( $_POST['emav_verify_remember'] ) ) {
            $cookie_duration = time() + emav_get_cookie_duration() * 60;
        } else {
            $cookie_duration = 0;
        }
        
        echo  'verified' ;
        wp_die();
    } else {
        do_action( 'emav_was_not_verified' );
        echo  $error_msg_arr[$optionID] ;
        wp_die();
    }

}

/**
 * Get current options
 */
add_action( 'wp_ajax_nopriv_emav_get_status', 'emav_get_status' );
add_action( 'wp_ajax_emav_get_status', 'emav_get_status' );
function emav_get_status()
{
    echo  get_option( '_emav_always_verify' ) ;
    wp_die();
}

function emav_confirmation_required()
{
    
    if ( get_option( '_emav_membership', 1 ) == 1 ) {
        $return = true;
    } else {
        $return = false;
    }
    
    return (bool) apply_filters( 'emav_confirmation_required', $return );
}

/**
 * Adds a checkbox to the default WordPress registration form for
 * users to verify their ages. You can filter the text if you like.
 *
 * @since 0.1
 * @echo  string
 */
function emav_register_form()
{
    $text = '<p class="easy-marijuana-age-verify"><label for="_emav_confirm_age"><input type="checkbox" name="_emav_confirm_age" id="_emav_confirm_age" value="1" /> ';
    $text .= esc_html( sprintf( apply_filters( 'emav_registration_text', __( 'I am at least 21 years old', 'easy-marijuana-age-verify' ) ), emav_get_minimum_age() ) );
    $text .= '</label></p><br />';
    echo  $text ;
}

/**
 * Make sure the user checked the box when registering.
 * If not, print an error. You can filter the error's text if you like.
 *
 * @return bool
 * @since 0.1
 */
function emav_register_check( $login, $email, $errors )
{
    if ( !isset( $_POST['_emav_confirm_age'] ) ) {
        $errors->add( 'empty_age_confirm', '<strong>ERROR</strong>: ' . apply_filters( 'emav_registration_error', __( 'Please confirm your age', 'easy-marijuana-age-verify' ) ) );
    }
}

function emav_upgrade_url( $params = array() )
{
    $defaults = array(
        'checkout'      => 'true',
        'plan_id'       => 4609,
        'plan_name'     => 'premium',
        'billing_cycle' => 'annual',
        'licenses'      => 1,
    );
    $params = wp_parse_args( $params, $defaults );
    return add_query_arg( $params, emav_fs()->get_upgrade_url() );
}

function emav_display_upgrade_features()
{
    $contents = '<table class="form-table emav-premium-features">
		<tr class="emav-premiumHead">
			<th class="emav-preBanner" scope="column" colspan=2>
				<h1>Unlock Premium Features</h1>
			</th>
		</tr>
		<tr><td colspan=2><center><b>2 Months Free with Annual Plan</b></center></td></tr>';
    foreach ( emav_premium_features() as $feature => $desc ) {
        $contents .= '<tr>
				<th class="emav-preBanner" width="30%" scope="column"><span class="dashicons dashicons-yes emav-premium"></span><span class="emav-premium-feature">' . $feature . '</span></th>
					<td width="70%" scope="column"><em>' . $desc . '</em>
				</th>
			</tr>';
    }
    $contents .= '<tr>
			<th style="text-align: center; padding-bottom: 20px;" scope="column" colspan="2"><a class="emav-btnBuy" href="' . esc_url( emav_upgrade_url() ) . '">Upgrade Now</a>
			</th>
		</tr>';
    
    if ( !emav_fs()->is_trial() ) {
        $contents .= '<tr><th style="text-align: center; padding-bottom: 20px;" scope="column" colspan="2"><a class="emav-trialLink" href="' . esc_url( '/wp-admin/admin.php?trial=true&page=easy-marijuana-age-verify-pricing' ) . '">' . __( 'Start 14-Day Free Trial', 'easy-marijuana-age-verify' ) . '</a><span style="font-weight: 400;">' . __( '(risk free, no credit card)', 'easy-marijuana-age-verify' ) . '</span></th></tr>';
    } else {
        $contents .= '<tr>
			<th style="text-align: center; padding-bottom: 20px;" scope="column" colspan="2">(On Free Trial Now)
			</th></tr>';
    }
    
    $contents .= '</table>';
    return $contents;
}

function emav_premium_features()
{
    $features = array(
        __( 'Customizable', 'easy-marijuana-age-verify' )      => __( 'Free-form text option', 'easy-marijuana-age-verify' ),
        __( 'Translation Ready', 'easy-marijuana-age-verify' ) => __( 'Self translate to any language', 'easy-marijuana-age-verify' ),
        __( 'Brand It', 'easy-marijuana-age-verify' )          => __( 'Your logo and colors', 'easy-marijuana-age-verify' ),
        __( 'Design Background', 'easy-marijuana-age-verify' ) => __( 'Set transparency and color', 'easy-marijuana-age-verify' ),
        __( 'Welcome Message', 'easy-marijuana-age-verify' )   => __( 'Add a welcome message', 'easy-marijuana-age-verify' ),
        __( 'Remember Visitors', 'easy-marijuana-age-verify' ) => __( '"Remember me" checkbox', 'easy-marijuana-age-verify' ),
        __( 'Med/Rec Combo', 'easy-marijuana-age-verify' )     => __( '18+ med with 21+ rec', 'easy-marijuana-age-verify' ),
        __( 'Premium Support', 'easy-marijuana-age-verify' )   => __( 'World-class email support from the U.S.', 'easy-marijuana-age-verify' ),
    );
    return $features;
}

/**
 * The message for current plugin users.
 */
function emav_fs_custom_connect_message_on_update(
    $message,
    $user_first_name,
    $plugin_title,
    $user_login,
    $site_link,
    $freemius_link
)
{
    return sprintf(
        __( 'Hey %1$s' ) . '<br>' . __( 'To enjoy all of the features of this plugin and future updates, Five Star Plugins needs to connect %4$s to Freemius.', 'easy-marijuana-age-verify' ),
        $user_first_name,
        '<b>' . $plugin_title . '</b>',
        '<b>' . $user_login . '</b>',
        $site_link,
        $freemius_link
    );
}

/**
 * The message for new plugin users.
 */
function emav_freemius_new_message(
    $message,
    $user_first_name,
    $plugin_title,
    $user_login,
    $site_link,
    $freemius_link
)
{
    return sprintf(
        __( 'Hey %1$s' ) . '<br>' . __( 'To enjoy all of the features of this plugin and future updates, Five Star Plugins needs to connect %4$s to Freemius.', 'easy-marijuana-age-verify' ),
        $user_first_name,
        '<b>' . $plugin_title . '</b>',
        '<b>' . $user_login . '</b>',
        $site_link,
        $freemius_link
    );
}

/**
 * Load Localization files.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales are found in:
 * - WP_LANG_DIR/plugins/easy-marijuana-age-verify-LOCALE.mo
 *
 * Example:
 * - WP_LANG_DIR/plugins/easy-marijuana-age-verify-pt_PT.mo
 */
function emav_load_plugin_textdomain()
{
    $locale = apply_filters( 'plugin_locale', get_locale(), 'easy-marijuana-age-verify' );
    load_textdomain( 'easy-marijuana-age-verify', WP_LANG_DIR . '/plugins/easy-marijuana-age-verify-' . $locale . '.mo' );
    load_plugin_textdomain( 'easy-marijuana-age-verify', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * @return mixed|void
 */
function emav_age_verify_option()
{
    return get_option( '_emav_user_age_verify_option' );
}

/**
 * Echoes the overlay disclaimer, which lives below the form.
 *
 * @since 0.1
 * @echo  string
 */
function emav_the_disclaimer()
{
    echo  emav_get_the_disclaimer() ;
}

/**
 * Returns the overlay disclaimer, which lives below the form.
 * You can filter this if you like.
 *
 * @return string|false
 * @since 0.1
 */
function emav_get_the_disclaimer()
{
    $desc = apply_filters( 'emav_disclaimer', get_option( '_emav_disclaimer' ) );
    
    if ( !empty($desc) ) {
        return $desc;
    } else {
        return false;
    }

}

/**
 * Echoes the overlay heading
 *
 * @since 0.1
 * @echo  string
 */
//function emav_the_age_header() {
//	echo emav_get_the_age_header();
//}
/**
 * Returns the overlay heading. You can filter this if you like.
 *
 * @return string
 * @since 0.1
 */
//function emav_get_the_age_header() {
//	return sprintf( apply_filters( 'emav_age_header', get_option( '_emav_age_header', __( 'You must be 21 years old to visit this site.', 'easy-marijuana-age-verify' ) ) ),
//		emav_get_minimum_age() );
//}
/**
 * Prints small label description
 *
 * @param $string
 *
 * @return string
 */
function emav_small( $string )
{
    return sprintf( '<br><small>%s</small>', $string );
}

//add_action( 'wp_ajax_nopriv_emav_post_verify_user_age', 'post_verify_user_age' );
//add_action( 'wp_ajax_post_emav_verify_user_age', 'post_verify_user_age' );
//add_action('admin_print_scripts','emav_age_header_default_text_ajax');
//function emav_age_header_default_text_ajax (){
//	printf( '<script>var emav_header_default_text = %s;</script>',
//		json_encode( emav_age_header_default_text() ) );
//}
/**
 * Calculates text color based on background color, chooses white or black
 *
 * @since 0.1
 * @echo  string
 */
function emav_getContrastYIQ( $hexcolor )
{
    $r = hexdec( substr( $hexcolor, 0, 2 ) );
    $g = hexdec( substr( $hexcolor, 2, 2 ) );
    $b = hexdec( substr( $hexcolor, 4, 2 ) );
    $yiq = ($r * 299 + $g * 587 + $b * 114) / 1000;
    return ( $yiq >= 128 ? 'black' : 'white' );
}

/**
 * Prints Form Header
 */
function emav_print_header()
{
    
    if ( function_exists( 'emav_get_custom_age_text' ) ) {
        $heading_text_arr = array(
            '',
            'Please verify your age to enter.',
            'Please verify your age to enter.',
            'Please verify your age to enter.',
            'Are you a 18+ valid medical marijuana patient?',
            'Are you 21+ or a 18+ valid medical marijuana patient?',
            emav_get_custom_age_text()
        );
    } else {
        $heading_text_arr = array(
            '',
            'Please verify your age to enter.',
            'Please verify your age to enter.',
            'Please verify your age to enter.',
            'Are you a 18+ valid medical marijuana patient?',
            'Are you 21+ or a 18+ valid medical marijuana patient?',
            'Please verify your age to enter.'
        );
    }
    
    
    if ( emav_age_verify_option() < 1 || emav_age_verify_option() > 6 ) {
        $emav_title = $heading_text_arr[1];
    } else {
        $emav_title = $heading_text_arr[emav_age_verify_option()];
    }
    
    printf( '<h2 style="color:' . emav_getContrastYIQ( ltrim( emav_get_overlay_color(), '#' ) ) . ';">%s</h2>', $emav_title );
}

function emav_print_disclaimer()
{
    $message = emav_get_the_disclaimer();
    $disclaimer = str_replace( "\r\n", '<br>', trim( $message ) );
    printf( '<div class="disclaimer"><p  style="color:' . emav_getContrastYIQ( ltrim( emav_get_overlay_color(), '#' ) ) . ';">%s</p></div>', html_entity_decode( $disclaimer ) );
}

add_filter( 'emav_before_form', 'emav_print_header', 4 );
add_filter( 'emav_after_form', 'emav_print_disclaimer', 6 );