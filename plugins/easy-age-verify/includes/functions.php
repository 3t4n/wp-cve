<?php

/**
 * Define the supporting functions.
 *
 * @since   0.2.6
 *
 * @package Easy_Age_Verify\Functions
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
function evav_load_textdomain()
{
    load_plugin_textdomain( 'easy-age-verify', false, plugin_basename( dirname( __FILE__ ) ) . '/includes/languages' );
}

add_action( 'plugins_loaded', 'emav_load_textdomain' );
/**
 * Prints the minimum age.
 *
 * @return void
 * @see   evav_get_minimum_age();
 *
 * @since 0.1.0
 */
function evav_minimum_age()
{
    echo  evav_get_minimum_age() ;
}

/**
 * Get the minimum age to view restricted content.
 *
 * @return int $minimum_age The minimum age to view restricted content.
 * @since 0.1.0
 *
 */
function evav_get_minimum_age()
{
    $minimum_age = get_option( '_evav_minimum_age', 21 );
    /**
     * Filter the minimum age.
     *
     * @param int $minimum_age The minimum age to view restricted content.
     *
     * @since 0.1.0
     *
     */
    $minimum_age = apply_filters( 'evav_minimum_age', $minimum_age );
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
 */
function evav_get_visitor_age( $year, $month, $day )
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
function evav_get_cookie_duration()
{
    $cookie_duration = get_option( '_evav_cookie_duration', 720 );
    /**
     * Filter the cookie duration.
     *
     * @param int $cookie_duration The cookie duration.
     *
     * @since 0.1.0
     *
     */
    $cookie_duration = (int) apply_filters( 'evav_cookie_duration', $cookie_duration );
    return $cookie_duration;
}

/**
 * Determines whether only certain content should be restricted.
 *
 * @return bool $only_content_restricted Whether the restriction is content-specific or site-wide.
 * @since 0.2.0
 *
 */
function evav_only_content_restricted()
{
    $only_content_restricted = ( 'content' == get_option( '_evav_require_for' ) ? true : false );
    /**
     * Filter whether the restriction is content-specific or site-wide.
     *
     * @param bool $only_content_restricted
     *
     * @since 0.2.0
     *
     */
    $only_content_restricted = apply_filters( 'evav_only_content_restricted', $only_content_restricted );
    return (bool) $only_content_restricted;
}

/**
 * Determines if a certain piece of content is restricted.
 *
 * @return bool $is_restricted Whether a certain piece of content is restricted.
 * @since 0.2.0
 *
 */
function evav_content_is_restricted( $id = null )
{
    if ( is_null( $id ) ) {
        $id = get_the_ID();
    }
    $is_restricted = ( 1 == get_post_meta( $id, '_evav_needs_verify', true ) ? true : false );
    /**
     * Filter whether this content should be restricted.
     *
     * @param bool $is_restricted Whether this content should be restricted.
     * @param int  $id            The content's ID.
     *
     * @since 0.2.6
     *
     */
    $is_restricted = apply_filters( 'evav_is_restricted', $is_restricted, $id );
    return $is_restricted;
}

/**
 * This is the very important function that determines if a given visitor
 * needs to be verified before viewing the site. You can filter this if you like.
 *
 * @return bool
 * @since 0.1
 */
function evav_needs_verification()
{
    // Assume the visitor needs to be verified
    $return = true;
    // If the site is restricted on a per-content basis, let 'em through
    
    if ( evav_only_content_restricted() ) {
        $return = false;
        // If the content being viewed is restricted, throw up the form
        if ( is_singular() && evav_content_is_restricted() ) {
            $return = true;
        }
    }
    
    // Check that the form was at least submitted. This lets visitors through that have cookies disabled.
    $nonce = ( isset( $_REQUEST['eav-age-verified'] ) ? $_REQUEST['eav-age-verified'] : '' );
    if ( wp_verify_nonce( $nonce, 'eav-age-verified' ) ) {
        $return = false;
    }
    // If not logged in Admins
    if ( get_option( '_evav_always_verify', 'admin-only' ) == 'admin-only' && !current_user_can( 'manage_options' ) ) {
        $return = false;
    }
    // If logged in users are exempt, and the visitor is logged in, let 'em through
    if ( get_option( '_evav_always_verify', 'guests' ) == 'guests' && is_user_logged_in() ) {
        $return = false;
    }
    // If logged in users are exempt, and the visitor is logged in, let 'em through
    if ( get_option( '_evav_always_verify', 'disabled' ) == 'disabled' ) {
        $return = false;
    }
    // Or, if there is a valid cookie let 'em through
    if ( isset( $_COOKIE['eav-age-verified'] ) || is_user_logged_in() ) {
        $return = false;
    }
    return (bool) apply_filters( 'evav_needs_verification', $return );
}

/***********************************************************/
/******************** Display Functions ********************/
/***********************************************************/
/**
 * Returns the form's input type, based on the settings.
 * You can filter this if you like.
 *
 * @return string
 * @since 0.1
 */
function evav_get_input_type()
{
    return apply_filters( 'evav_input_type', get_option( '_evav_input_type', 'dropdowns' ) );
}

/**
 * Echoes the actual form
 *
 * @since 0.1
 * @echo  string
 */
function evav_verify_form()
{
    echo  evav_get_verify_form() ;
}

/**
 * Adds the user's WP role to the Body CSS Class for Testing Mode
 *
 * @since 1.1
 * @echo  string
 */
add_filter( 'body_class', 'evav_output_role_body_class' );
function evav_output_role_body_class( $classes )
{
    $classes[] = evav_get_user_role();
    // Add verify option setting in body CSS to bypass JS ajax call
    array_push( $classes, 'evav-' . get_option( '_evav_always_verify' ) );
    // Add AJAX option check, add to BODY Class if set to True, use in JS to test and perform AJAX call, otherwise bypass AJAX call
    array_push( $classes, get_option( '_evav_ajax_check' ) );
    return $classes;
}

function evav_get_user_role()
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
function evav_get_verify_form()
{
    global  $evav_fs ;
    //$input_type = evav_get_input_type();
    // Easy Age Verify Option
    //	$option = (int) evav_age_verify_option();
    $option = evav_age_verify_option();
    switch ( $option ) {
        case 'adult':
            $optionID = 2;
            break;
        case 'alcohol':
            $optionID = 1;
            break;
        case 'vape':
            $optionID = 1;
            break;
        default:
            $optionID = 1;
            break;
    }
    // Button Array Labels
    $age_confirm_btn_arr = array( '', 'Yes I am of legal age', 'I am 18 or older [Enter Site]' );
    $age_btn_arr = array( '', 'No I am under age', 'I am under 18' );
    // Selected Button Label Option
    $age_confirm_btn_label = $age_confirm_btn_arr[$optionID];
    $age_btn_label = $age_btn_arr[$optionID];
    
    if ( $option == 'adult' ) {
        $confirm_btn_style = 'style="background-color:#2dc937; width:290px;max-width:95%;"';
        $not_confirm_btn_style = 'style="background-color:#cc3232; width:290px;max-width:95%;"';
    } else {
        $confirm_btn_style = 'style="background-color:#2dc937;"';
        $not_confirm_btn_style = 'style="background-color:#cc3232;"';
    }
    
    // Default error text
    $no_error_text = 'Sorry, you must be of legal age to enter this website.';
    $submit_button_label = apply_filters( 'evav_form_submit_label', __( 'Enter Site &raquo;', 'easy-age-verify' ) );
    $form = '';
    $form .= '<form id="evav_verify_form" action="' . esc_url( home_url( '/' ) ) . '" method="post">';
    // This IF will be executed only if the user in a trial mode or have a valid license.
    
    if ( $evav_fs->can_use_premium_code() ) {
        $form .= '<div style="display: none; color: ' . evav_getContrastYIQ( ltrim( evav_get_overlay_color(), '#' ) ) . ' !important;" class="evav-error">' . $no_error_text . '</div>';
    } else {
        $form .= '<div style="display: none;" class="evav-error">' . $no_error_text . '</div>';
    }
    
    do_action( 'evav_form_before_inputs' );
    $form .= '<input type="hidden" name="evav_verify_confirm" id="evav_verify_confirm" value="" />';
    $form .= '<div class="evav_buttons"><input type="button" name="evav_confirm_age" id="evav_confirm_age" value="' . $age_confirm_btn_label . '" ' . $confirm_btn_style . ' />';
    $form .= '<div class="evav_buttons_sep"></div>';
    $form .= '<input type="button" name="evav_not_confirm_age" id="evav_not_confirm_age" value="' . $age_btn_label . '" ' . $not_confirm_btn_style . '></div>';
    do_action( 'evav_form_after_inputs' );
    //$form .= '<input type="submit" name="evav_verify" id="evav_verify" value="' . esc_attr( $submit_button_label ) . '" /></p>';
    $form .= '</form>';
    return apply_filters( 'evav_verify_form', $form );
}

/**
 * Returns the overlay background color
 * You can filter this if you like.
 *
 * @return string
 * @since 0.1
 */
function evav_get_overlay_color()
{
    global  $evav_fs ;
    // This IF will be executed only if the user in a trial mode or have a valid license.
    
    if ( $evav_fs->can_use_premium_code() ) {
        if ( get_option( '_evav_overlay_color' ) ) {
            $color = get_option( '_evav_overlay_color' );
        }
    } else {
        $color = '#000000';
    }
    
    return apply_filters( 'evav_overlay_color', $color );
}

/***********************************************************/
/*************** User Registration Functions ***************/
/***********************************************************/
/**
 * Determines whether or not users need to verify their age before
 * registering for the site. You can filter this if you like.
 * evav_post_verify_user_age
 * @return bool
 * @since 0.1
 */
add_action( 'wp_ajax_nopriv_evav_post_verify_user_age', 'evav_user_age_verify' );
add_action( 'wp_ajax_evav_post_verify_user_age', 'evav_user_age_verify' );
function evav_user_age_verify()
{
    $is_verified = false;
    $error = 1;
    // Catch-all in case something goes wrong
    $error_msg_arr = array( '', 'Sorry, you must be of legal age to enter this website.' );
    //	$optionID = (int) evav_age_verify_option();
    $optionID = 1;
    error_log( $optionID );
    
    if ( isset( $_POST['verifyConfirm'] ) && (int) $_POST['verifyConfirm'] == 1 ) {
        $is_verified = true;
    } else {
        echo  $error_msg_arr[$optionID] ;
        exit;
    }
    
    $is_verified = apply_filters( 'evav_passed_verify', $is_verified );
    
    if ( $is_verified == true ) {
        do_action( 'evav_was_verified' );
        
        if ( isset( $_POST['evav_verify_remember'] ) ) {
            $cookie_duration = time() + evav_get_cookie_duration() * 60;
        } else {
            $cookie_duration = 0;
        }
        
        echo  'verified' ;
        wp_die();
    } else {
        do_action( 'evav_was_not_verified' );
        echo  $error_msg_arr[$optionID] ;
        wp_die();
    }

}

// No longer using token, remove these sections:
// Token action
//add_action( 'wp_ajax_nopriv_evav_security', 'evav_security' );
//add_action( 'wp_ajax_evav_security', 'evav_security' );
// Token Validation
function evav_security()
{
    global  $evav_fs ;
    $data = '';
    ?>
    <script type="text/javascript">
        Cookies.set('eav-age-verified', 1, { <?php 
    echo  $data ;
    ?> path: location.pathname , secure: true });
        jQuery('body').attr('style','');
        jQuery('#evav-overlay-wrap').hide();
    </script>
<?php 
}

/**
 * Get current options
 */
add_action( 'wp_ajax_nopriv_evav_get_status', 'evav_get_status' );
add_action( 'wp_ajax_evav_get_status', 'evav_get_status' );
function evav_get_status()
{
    echo  get_option( '_evav_always_verify' ) ;
    wp_die();
}

function evav_confirmation_required()
{
    
    if ( get_option( '_evav_membership', 1 ) == 1 ) {
        $return = true;
    } else {
        $return = false;
    }
    
    return (bool) apply_filters( 'evav_confirmation_required', $return );
}

/**
 * Adds a checkbox to the default WordPress registration form for
 * users to verify their ages. You can filter the text if you like.
 *
 * @since 0.1
 * @echo  string
 */
function evav_register_form()
{
    $text = '<p class="easy-age-verify"><label for="_evav_confirm_age"><input type="checkbox" name="_evav_confirm_age" id="_evav_confirm_age" value="1" /> ';
    $text .= esc_html( sprintf( apply_filters( 'evav_registration_text', __( 'Please verify you are 18 years or older to enter.', 'easy-age-verify' ) ), evav_get_minimum_age() ) );
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
function evav_register_check( $login, $email, $errors )
{
    if ( !isset( $_POST['_evav_confirm_age'] ) ) {
        $errors->add( 'empty_age_confirm', '<strong>ERROR</strong>: ' . apply_filters( 'evav_registration_error', __( 'Please confirm your age', 'easy-age-verify' ) ) );
    }
}

function evav_upgrade_url( $params = array() )
{
    $defaults = array(
        'checkout'      => 'true',
        'plan_id'       => 5712,
        'plan_name'     => 'premium',
        'billing_cycle' => 'annual',
        'licenses'      => 1,
    );
    $params = wp_parse_args( $params, $defaults );
    return add_query_arg( $params, evav_fs()->get_upgrade_url() );
}

function evav_display_upgrade_features()
{
    $contents = '<table class="form-table evav-premium-features">
		<tr class="evav-premiumHead">
			<th class="evav-preBanner" scope="column" colspan=2>
				<h1>Unlock Premium Features</h1>
			</th>
		</tr>
		<tr><td colspan=2><center><b>3 Months Free with Annual Plan</b></center></td></tr>';
    foreach ( evav_premium_features() as $feature => $desc ) {
        $contents .= '<tr>
				<th class="evav-preBanner" width="30%" scope="column"><span class="dashicons dashicons-yes evav-premium"></span><span class="evav-premium-feature">' . $feature . '</span></th>
					<td width="70%" scope="column"><em>' . $desc . '</em>
				</th>
			</tr>';
    }
    $contents .= '<tr>
			<th style="text-align: center; padding: 20px 0;" scope="column" colspan="2"><a class="evav-btnBuy" href="' . esc_url( evav_upgrade_url() ) . '">Upgrade Now</a>
			</th></tr>';
    if ( !evav_fs()->is_trial() ) {
        $contents .= '<tr>
			<th style="text-align: center; padding-bottom: 20px;" scope="column" colspan="2"><a class="evav-trialLink" href="' . esc_url( '/wp-admin/admin.php?trial=true&page=easy-age-verify-pricing' ) . '">' . __( 'Start 14-Day Free Trial', 'easy-age-verify' ) . '</a><span style="font-weight: 400;">' . __( '(risk free, no credit card)', 'easy-age-verify' ) . '</span></th></tr>';
    }
    $contents .= '</table>';
    return $contents;
}

function evav_premium_features()
{
    $features = array(
        __( 'Customizable', 'easy-marijuana-age-verify' )      => __( 'Free-form text option', 'easy-marijuana-age-verify' ),
        __( 'Translation Ready', 'easy-marijuana-age-verify' ) => __( 'Self translate to any language', 'easy-marijuana-age-verify' ),
        __( 'Brand It', 'easy-marijuana-age-verify' )          => __( 'Your logo and colors', 'easy-marijuana-age-verify' ),
        __( 'Design Background', 'easy-marijuana-age-verify' ) => __( 'Set transparency and color', 'easy-marijuana-age-verify' ),
        __( 'Welcome Message', 'easy-marijuana-age-verify' )   => __( 'Add a welcome message', 'easy-marijuana-age-verify' ),
        __( 'Remember Visitors', 'easy-marijuana-age-verify' ) => __( '"Remember me" checkbox', 'easy-marijuana-age-verify' ),
        __( 'Premium Support', 'easy-marijuana-age-verify' )   => __( 'World-class email support from the U.S.', 'easy-marijuana-age-verify' ),
    );
    return $features;
}

/**
 * The message for current plugin users.
 */
function evav_fs_custom_connect_message_on_update(
    $message,
    $user_first_name,
    $plugin_title,
    $user_login,
    $site_link,
    $freemius_link
)
{
    return sprintf(
        __( 'Hey %1$s' ) . ',<br>' . __( 'Opt-in to help us improve %2$s! Some usage data will be sent to our platform Freemius.', 'easy-age-verify' ),
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
function evav_freemius_new_message(
    $message,
    $user_first_name,
    $plugin_title,
    $user_login,
    $site_link,
    $freemius_link
)
{
    return sprintf(
        __( 'hey-x' ) . '<br>' . __( 'In order to enjoy all of the features, functionality and enable a free trial of premium version, %2$s wants to connect usage data to our platform Freemius.', 'easy-age-verify' ),
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
 * - WP_LANG_DIR/plugins/easy-age-verify-LOCALE.mo
 *
 * Example:
 * - WP_LANG_DIR/plugins/easy-age-verify-pt_PT.mo
 */
function evav_load_plugin_textdomain()
{
    $locale = apply_filters( 'plugin_locale', get_locale(), 'easy-age-verify' );
    load_textdomain( 'easy-age-verify', WP_LANG_DIR . '/plugins/easy-age-verify-' . $locale . '.mo' );
    load_plugin_textdomain( 'easy-age-verify', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

/**
 * @return mixed|void
 */
function evav_age_verify_option()
{
    return get_option( '_evav_adult_type' );
}

/**
 * Echoes the overlay disclaimer, which lives below the form.
 *
 * @since 0.1
 * @echo  string
 */
function evav_the_disclaimer()
{
    echo  evav_get_the_disclaimer() ;
}

/**
 * Returns the overlay disclaimer, which lives below the form.
 * You can filter this if you like.
 *
 * @return string|false
 * @since 0.1
 */
function evav_get_the_disclaimer()
{
    $desc = apply_filters( 'evav_disclaimer', get_option( '_evav_disclaimer', __( 'Please verify your age', 'easy-age-verify' ) ) );
    
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
function evav_the_heading()
{
    echo  evav_get_the_heading() ;
}

/**
 * Returns the overlay heading. You can filter this if you like.
 *
 * @return string
 * @since 0.1
 */
function evav_get_the_heading()
{
    return sprintf( apply_filters( 'evav_heading', get_option( '_evav_heading', __( 'You must be 21 years old to visit this site.', 'easy-age-verify' ) ) ), evav_get_minimum_age() );
}

function evav_settings_print_field_input( &$field, $i )
{
    return sprintf( '<span style="margin-left: 23px;font-style: italic;">%s</span>', $field['name'] . ' (upgrade to unlock) - ' ) . evav_settings_print_premium_field_images( $field );
}

function evav_settings_print_field_images( &$field )
{
    $freeImage = sprintf( '<a href="https://5starplugins.com/wp-content/uploads/%s" class="thickbox">%s</a>', $field['images']['free'], 'Screenshot Preview' );
    $premiumImage = sprintf( '<a href="https://5starplugins.com/wp-content/uploads/%s" class="thickbox">%s</a>', $field['images']['premium'], 'Premium Version' );
    return sprintf( '%s | %s', $freeImage, $premiumImage );
}

function evav_settings_print_premium_field_images( &$field )
{
    $premiumImage = sprintf( '<a href="https://5starplugins.com/wp-content/uploads/%s" class="thickbox">%s</a>', $field['images']['premium'], 'Screenshot Preview' );
    return sprintf( '%s', $premiumImage );
}

/**
 * Prints small label description
 *
 * @param $string
 *
 * @return string
 */
function evav_small( $string )
{
    return sprintf( '<br><small>%s</small>', $string );
}

/**
 * Calculates text color based on background color, chooses white or black
 *
 * @since 0.1
 * @echo  string
 */
function evav_getContrastYIQ( $hexcolor )
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
function evav_print_header()
{
    global  $evav_fs ;
    $custom_header_text = evav_get_the_heading();
    $heading_text_arr = array( '', $custom_header_text, 'Please verify your age to enter.' );
    
    if ( $custom_header_text !== $heading_text_arr[2] ) {
        $title = esc_html( $custom_header_text );
    } else {
        $title = $heading_text_arr[2];
    }
    
    printf( '<h2 style="color:' . evav_getContrastYIQ( ltrim( evav_get_overlay_color(), '#' ) ) . ';">%s</h2>', $title );
}

function evav_print_disclaimer()
{
    $message = evav_get_the_disclaimer();
    $disclaimer = str_replace( "\r\n", '<br>', trim( $message ) );
    printf( '<div class="disclaimer"><p style="color:' . evav_getContrastYIQ( ltrim( evav_get_overlay_color(), '#' ) ) . ';">%s</p></div>', html_entity_decode( $disclaimer ) );
}

add_filter( 'evav_before_form', 'evav_print_header', 4 );
//add_filter( 'evav_after_form', 'evav_print_powerby', 5 );
add_filter( 'evav_after_form', 'evav_print_disclaimer', 6 );