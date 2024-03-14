<?php 

namespace VENDOR\RECAPTCHA_GDPR_COMPLIANT;

defined( 'ABSPATH' ) or die( 'Are you ok?' );

/**
 * Class Stamp: Each instance of that class is intended to hold the Stamp and all checks arount it
 * 
 */
class Stamp
{
    /** Holding the instance of this class */
    public static $instance;

    /** String that holds the spam-information */
    private $plugin_spam;

    /** Get an instance of the class
     * 
     */
    public static function getInstance()
    {
        require_once dirname( __FILE__ ) . '/class-option.php';

        if ( ! self::$instance instanceof self ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** Constructor of the class
     */
    public function __construct()
    {
        $referrer_without_protocol = null;
        $posted_site = null;
        if( array_key_exists( 'HTTP_REFERER', $_SERVER ) )
            $referrer_without_protocol = preg_replace( '/^(https?:\/\/)/i', '', $_SERVER[ 'HTTP_REFERER' ] );
        if( array_key_exists( 'REQUEST_URI', $_SERVER ) && array_key_exists( 'HTTP_HOST', $_SERVER ) )
            $posted_site = $_SERVER['HTTP_HOST'] . preg_replace( '/^(https?:\/\/)/i', '', $_SERVER[ 'REQUEST_URI' ] );
        //Check whether the IP is whitelisted
        $ip_whitelisted = false;
        $client_ip = $this->get_client_ip();
        $lines = preg_split('/\r\n|\n|\r/', get_option( Option::POW_IP_WHITELIST ), -1, PREG_SPLIT_NO_EMPTY);
        if ( count( $lines ) > 0 ){
            foreach ($lines as $line) {
                $client_ip == trim ( $line ) ? $ip_whitelisted = true : null;
            }
        }

        //Check whether the site is whitelisted
        $site_whitelisted = false;
        $lines = preg_split('/\r\n|\n|\r/', get_option( Option::POW_SITE_WHITELIST ), -1, PREG_SPLIT_NO_EMPTY);
        if ( count( $lines ) > 0 ){
            foreach ($lines as $line) {
                if ( is_string( $posted_site ) && is_string( trim ( $line ) ) && strpos( $posted_site, trim ( $line ) ) === 0 ){
                    $site_whitelisted = true;
                }
            }
        }
        if( is_string( $posted_site ) && 
            ( strpos( $posted_site, '/wp-admin/admin-ajax.php?action=elementor_1_elementor_updater' ) 
              || strpos( $posted_site, '/wp-cron.php' )
              || strpos( $posted_site, '/?wordfence_syncAttackData=' )
            )
        ){
            $site_whitelisted = true;
        }

        //Check whether a rest route is used and whether it shall be processed
        $is_restroute = false;
        if( ! get_option( Option::POW_APPLY_REST ) ){
            if( isset( $_SERVER['HTTP_HOST'] ) ){
                $current_domain = $_SERVER['HTTP_HOST'] . '/?rest_route=';
                $domain_without_protocol = preg_replace( '/^(https?:\/\/)/i', '', $current_domain );
                if ( is_string( $referrer_without_protocol ) && is_string( $domain_without_protocol ) && strpos( $referrer_without_protocol, $domain_without_protocol ) === 0 ) {
                    $is_restroute = true;
                }
            }
        }
        
        $logged_out = array_key_exists( 'loggedout', $_REQUEST ) ? $_REQUEST[ 'loggedout' ] : false;
        $interim_login = array_key_exists( 'interim-login', $_REQUEST ) ? $_REQUEST[ 'interim-login' ] : false;
        $ajax = defined('DOING_AJAX') && DOING_AJAX;
        $action = isset( $_REQUEST[ 'action' ] ) ? sanitize_text_field( $_REQUEST[ 'action' ] ) : '';

        function checkArrays( $arrayOfArrays, $comparisonArray ) {
            if( $comparisonArray ){
                // Sort the keys of the comparison array
                ksort( $comparisonArray );
            
                // Iterate through the array of arrays and compare keys
                foreach ( $arrayOfArrays as $innerArray ) {
                    if( $innerArray ){
                        // Sort the keys of the current inner array
                        ksort( $innerArray );
                        
                        // Check if all keys from $comparisonArray are present in the current inner array
                        $keysMatch = $comparisonArray === $innerArray;

                        // If keys match, return true
                        if ( $keysMatch ) {
                            return true;
                        }
                    }
                }
            }
            // If no matching keys are found, return false
            return false;
        }

        $this->saveForAnalysis();

        //If the client, the site, the rest-route, or the action is whitelisted, stop the further processing
        if( ! $ip_whitelisted 
            && ! $site_whitelisted 
            && ! $is_restroute 
            && ( 
                    ( is_string( $referrer_without_protocol ) && strpos( $referrer_without_protocol, '/wp-admin/' ) == false )
                    || ! is_string( $referrer_without_protocol )
                    || $logged_out 
                    || $interim_login 
            ) 
        ) {
            //Ajax-calls get a different treatment
            if( $ajax ){
                //Post-requests from the plugin
                if( ! in_array( $action, [ 'get_stamp', 'check_stamp' ] ) ){
                    // If explicit mode, only explicitly listed actions shall be allowed that are listed on the explicit actions list
                    if( get_option( Option::POW_EXPLICIT_MODE ) ){
                        if( $this->checkExplicitActions() ){
                            add_action( 'init', [ $this, 'run' ] );
                            return $this->check_submit( null, $_POST, 'ajax-call', $action, $ajax );
                        }
                    // If default mode, all actions will be allowed if they are not listed on the exclusion-list
                    }else{
                        $excluded = false;
                        $lines = preg_split('/\r\n|\n|\r/', get_option( Option::POW_ACTION_WHITELIST ), -1, PREG_SPLIT_NO_EMPTY);
                        if ( count( $lines ) > 0 ){
                            foreach ($lines as $line) {
                                if( fnmatch( trim ( $line ), $action ) ){
                                    $excluded = true;
                                    break;
                                }
                            }
                        }

                        function wildcard_match_array( $action, $array ) {
                            foreach ( $array as $pattern ) {
                                if ( fnmatch( $pattern, $action ) ) {
                                    return true; // If any item matches, return true
                                }
                            }
                            return false; // If no match is found in the entire array, return false
                        }

                        $exclusion_array = array(
                            'wordfence_*', // Wordfence
                            'tcb_editor_ajax', 'tve_dash_front_ajax', 'tie_save_image_content', 'tie_save_image_file', // Thrive
                            'woocommerce_*', 'wp_1_wc_privacy_cleanup', 'as_async_request_queue_runner', // WooCommerce
                            'wp_mail_smtp_*', 'health-check-email-domain_check_test', 'wp_async_request', // wp-mail-smtp
                            'wp_optimize_ajax', 'save-widget', 'update-widget', 'updraft_*', // wp-optimize
                            'wpforms_*', // wpforms
                            'wps-limit-login-unlock', 'wpslimitlogin_rated', // wps-limit-login
                            'wpcf7-update-welcome-panel', // contact form 7
                            'forminator_*', 'wpmudev_notices_action', // Forminator
                            'et_fb_*', 'et_pb_*', // Divi
                            'heartbeat', // WordPress
                            'shield_action', // Shield
                        );

                        //Array to exclude specific values from used matching-patterns
                        $exclusion_from_pattern = array(
                            'wpforms_submit', 'forminator_submit_form_*',
                        );

                        if( ! $excluded && 
                            ( wildcard_match_array( $action, $exclusion_from_pattern )
                                || ! wildcard_match_array( $action, $exclusion_array )
                            ) 
                        ){
                            add_action( 'init', [ $this, 'run' ] );
                            return $this->check_submit( null, $_POST, 'ajax-call', $action, $ajax );
                        }
                    }
                }else{
                    add_action( 'init', [ $this, 'run' ] );
                }
            }else{
                //Do not apply if login shall not be blocked and it is a login
                if( ! ( ! get_option( Option::POW_BLOCK_LOGIN ) && isset( $_REQUEST[ 'wp-submit' ] ) ) ){
                    //Do not apply if the request is a wordfence_syncAttackData-Request from Wordfence
                    if ( ! ( isset( $_POST[ 'wordfence_syncAttackData' ] ) && count( $_POST ) === 1 ) ) {
                        add_action( 'init', [ $this, 'run' ] );
                        $patternFound = $this->checkExistingPatterns();
                        $actionFound = $this->checkExplicitActions();
                        //WooCommerce
                        if( 
                            (
                                isset( $_POST[ 'update_cart' ] ) && isset( $_POST[ 'cart' ] ) && isset( $_POST[ 'woocommerce-cart-nonce' ] ) 
                            ) || (
                                isset( $_REQUEST[ 'wc-ajax' ] ) && $_REQUEST[ 'wc-ajax' ] == 'checkout'
                            ) 
                            || $patternFound
                            || $actionFound
                        ){
                            return $this->check_submit( null, $_POST, 'specific call' );
                        }
                    }
                }
            }
        }
    }

    private function checkExplicitActions(){
        if( get_option( Option::POW_EXPLICIT_MODE ) ){
            $action = isset( $_REQUEST[ 'action' ] ) ? sanitize_text_field( $_REQUEST[ 'action' ] ) : '';
            if( $action ){
                $lines = preg_split('/\r\n|\n|\r/', get_option( Option::POW_EXPLICIT_ACTION ), -1, PREG_SPLIT_NO_EMPTY);
                if ( count( $lines ) > 0 ){
                    foreach ($lines as $line) {
                        if( $action == trim ( $line ) //explicitly listed ajax-action
                            || isset( $_REQUEST[ $action ] ) //explicitly listed post-attribute
                        ){
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    private function saveForAnalysis(){
        $ajax = defined('DOING_AJAX') && DOING_AJAX;
        $action = isset( $_REQUEST[ 'action' ] ) ? sanitize_text_field( $_REQUEST[ 'action' ] ) : '';
        $client_ip = $this->get_client_ip();

        $excluded_patterns_for_analysis = [
            [ "page" => "gdpr_pow_options", "action" => "update", "option_page" => "gdpr_pow_header_section" ]
        ];

        $pattern_listed = false;
        foreach( $excluded_patterns_for_analysis as $existing_pattern ){
            if( $existing_pattern ){
                $pattern_listed = Option::compareJSONObjects( $existing_pattern, $_REQUEST, true );
                if( $pattern_listed )
                    break;
            }
        }

        $excluded_actions_for_analysis = [ 'render_messages', 'render_message', 'delete_message', 'heartbeat', 'save_pattern', 'check_stamp', 'save_list_parameter', 'save_whitelist_parameter', 'change_message_type' ];
        if( isset( $_SERVER['REQUEST_METHOD'] )
            && $_SERVER['REQUEST_METHOD'] === 'POST'
            && get_option( Option::POW_ANALYSIS_MODE )
            && (
                    ! $ajax && ! $pattern_listed
                    || $ajax && ! in_array( $action, $excluded_actions_for_analysis )
            )
        ){
            $this->save_message( $_REQUEST, $action, $ajax, 4, $this->hash_Values( $client_ip ) );
        }
    }

    private function checkExistingPatterns(){
        $patternFound = false;
        //Specific posts as proprietary ajax calls
        $existing_pattern = get_option( Option::POW_PARAMETER_PATTERN );
        if(
            $existing_pattern && get_option( Option::POW_EXPLICIT_MODE ) 
        ){
            $existing_lines_pattern = preg_split( "/\r\n|\n|\r/", $existing_pattern );

            // Iterate through the array, convert each field to JSON, and update the array
            foreach ($existing_lines_pattern as &$line) {
                // Trim the line to remove any extra spaces or newline characters
                $line = trim( $line );
                // Decode the line from JSON to an array
                $line = json_decode( $line ); // Passing true makes it return an associative array

                if ( $this->checkPattern( $line, $_REQUEST ) )
                    $patternFound = true;
            }
        }
        return $patternFound;
    }

    private function checkPattern( $a, $b ) {
        if( !$a || !$b )
            return false;
        if ( is_object( $b ) ) {
            $b = get_object_vars( $b );
        }
        if ( is_object( $a ) ) {
            $a = get_object_vars( $a );
        }
        foreach ( $a as $key => $value ) {
            // Wenn der Wert in $a ein weiteres assoziatives Array ist, rekursiv überprüfen
            if ( is_array( $value ) || is_object( $value ) ) {
                if ( ! isset( $b[ $key ] ) || ! $this->checkPattern( $value, $b[ $key ] ) ) {
                    return false;
                }
            }else{
                if ( $value !== null ) {
                    // Wenn der Wert in $a nicht null ist, überprüfe, ob der Schlüssel-Wert-Paar in $b existiert
                    if ( ! isset( $b[ $key ] ) || $a[ $key ] !== $b[ $key ] ) {
                        return false;
                    }
                } else {
                    // Wenn der Wert in $a null ist, überprüfe, ob der Schlüssel in $b existiert
                    if ( ! isset( $b[ $key ] ) ) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /** When the plugin is run
     */
    public function run( $form_builder = null )
    {
        add_action( 'wp_ajax_nopriv_get_stamp', [ $this, 'get_stamp_call' ] );
        add_action( 'wp_ajax_nopriv_check_stamp', [ $this, 'check_stamp' ] );
        add_action( 'wp_ajax_get_stamp', [ $this, 'get_stamp_call' ] );
        add_action( 'wp_ajax_check_stamp', [ $this, 'check_stamp'] );
        $ajax = defined('DOING_AJAX') && DOING_AJAX;
        if( ! $ajax ){
            add_action( 'wp_head', [ $this, 'add_script_to_header' ], 10 );
            if( get_option( Option::POW_BLOCK_LOGIN ) ){
                add_action( 'login_head', [ $this, 'add_script_to_header' ], 10 );
                add_action( 'wp_signon', [ $this, 'pre_process_login'], 1, 1 );
                add_action( 'wp_authenticate_user', [ $this, 'pre_process_login'], 1, 1 );
                add_action( 'check_passwords',[ $this, 'pre_process_login' ], 1, 1 );
                add_action( 'password_reset',[ $this, 'pre_process_login' ], 1, 1 );
            }
            if( ! get_option( Option::POW_EXPLICIT_MODE ) ){
                add_action( 'preprocess_comment', [ $this, 'pre_process_submission'] );
                add_action( 'parse_request',[ $this, 'pre_process_submission' ], 1, 1 );
                add_action( 'register_post',[ $this, 'pre_process_submission' ], 1, 1 );
            }
        }
    }

    public function pre_process_login( $wp = null ){
        return $this->pre_process_submission( $wp, 'login' );
    }

    /** Pre-Process-variables */
    public function pre_process_submission( $wp = null, $origin = null ){
        
        $patternFound = false;
        $actionFound = false;
        
        if( ! $origin === 'login' ){
            $this->saveForAnalysis();
            $patternFound = $this->checkExistingPatterns();
            $actionFound = $this->checkExplicitActions();
        }

        if( ! is_admin() && ( $patternFound || $actionFound || $origin === 'login' ) ){
            $fields = $_POST;
            // During loading, Wordpress is calling the server with a post request containing the time. We don't want to catch that
            if( $fields ){
                return $this->check_submit( $wp, $fields );
            }else{
                return $wp;
            }
        }else{
            return $wp;
        }
    }


    /** Adding a script to the header of each page. 
     * It is not enqeued as within the script domain related variables 
     * from backend have to be set dynamically
    */
    public function add_script_to_header(){
        //Adds div on top-level that is displayed each when PoW is done in order to prevent the user from clicking the submit button
        ?>
        <script>
        var gdpr_compliant_recaptcha_stamp = '<?php echo $this->get_stamp(); ?>';
        var gdpr_compliant_recaptcha_nonce = null;
        var gdpr_compliant_recaptcha = {
            stampLoaded : false,
            // Create an array to store override functions
            originalFetches : [],
            originalXhrOpens : [],
            originalXhrSends : [],
            originalFetch : window.fetch,
            abortController : new AbortController(),
            originalXhrOpen : XMLHttpRequest.prototype.open,
            originalXhrSend : XMLHttpRequest.prototype.send,

            // Function to check if a string is a valid JSON
            isValidJson : function( str ) {
                try {
                    JSON.parse( str );
                    return true;
                } catch ( error ) {
                    return false;
                }
            },

            // Function to handle fetch response
            handleFetchResponse: function (input, init) {
                // Store method and URL
                var method = (init && init.method) ? init.method.toUpperCase() : 'GET';
                var url = input;
                gdpr_compliant_recaptcha.originalFetches.forEach(overrideFunction => {
                            overrideFunction.apply(this, arguments);
                });
                // Bind the original fetch function to the window object
                var originalFetchBound = gdpr_compliant_recaptcha.originalFetch.bind(window);
                try{
                    // Call the original fetch method
                    //return gdpr_compliant_recaptcha.originalFetch.apply(this, arguments).then(function (response) {
                    return originalFetchBound(input, init).then(function (response) {
                        var clonedResponse = response.clone();
                        // Check for an error response
                        if (response.ok && method === 'POST') {
                            // Parse the response JSON
                            return response.text().then(function (responseData) {
                                var data = responseData;
                                if (gdpr_compliant_recaptcha.isValidJson(responseData)) {
                                    data = JSON.parse(responseData);
                                }
                                // Check if the gdpr_error_message parameter is present
                                if (data.data && data.data.gdpr_error_message) {
                                    gdpr_compliant_recaptcha.displayErrorMessage(data.data.gdpr_error_message);
                                    gdpr_compliant_recaptcha.abortController.abort();
                                    return Promise.reject(new Error('Request aborted'));
                                }
                                // Return the original response for non-error cases
                                return clonedResponse;
                            });
                        }
                        return clonedResponse;
                    });
                } catch (error) {
                    // Return a resolved promise in case of an error
                    return Promise.resolve();
                }
            },

            // Full implementation of SHA265 hashing algorithm.
            sha256 : function( ascii ) {
                function rightRotate( value, amount ) {
                    return ( value>>>amount ) | ( value<<(32 - amount ) );
                }

                var mathPow = Math.pow;
                var maxWord = mathPow( 2, 32 );
                var lengthProperty = 'length';

                // Used as a counter across the whole file
                var i, j;
                var result = '';

                var words = [];
                var asciiBitLength = ascii[ lengthProperty ] * 8;

                // Caching results is optional - remove/add slash from front of this line to toggle.
                // Initial hash value: first 32 bits of the fractional parts of the square roots of the first 8 primes
                // (we actually calculate the first 64, but extra values are just ignored).
                var hash = this.sha256.h = this.sha256.h || [];

                // Round constants: First 32 bits of the fractional parts of the cube roots of the first 64 primes.
                var k = this.sha256.k = this.sha256.k || [];
                var primeCounter = k[ lengthProperty ];

                var isComposite = {};
                for ( var candidate = 2; primeCounter < 64; candidate++ ) {
                    if ( ! isComposite[ candidate ] ) {
                        for ( i = 0; i < 313; i += candidate ) {
                            isComposite[ i ] = candidate;
                        }
                        hash[ primeCounter ] = ( mathPow( candidate, 0.5 ) * maxWord ) | 0;
                        k[ primeCounter++ ] = ( mathPow( candidate, 1 / 3 ) * maxWord ) | 0;
                    }
                }

                // Append Ƈ' bit (plus zero padding).
                ascii += '\x80';

                // More zero padding
                while ( ascii[ lengthProperty ] % 64 - 56 ){
                ascii += '\x00';
                }

                for ( i = 0, max = ascii[ lengthProperty ]; i < max; i++ ) {
                    j = ascii.charCodeAt( i );

                    // ASCII check: only accept characters in range 0-255
                    if ( j >> 8 ) {
                    return;
                    }
                    words[ i >> 2 ] |= j << ( ( 3 - i ) % 4 ) * 8;
                }
                words[ words[ lengthProperty ] ] = ( ( asciiBitLength / maxWord ) | 0 );
                words[ words[ lengthProperty ] ] = ( asciiBitLength );

                // process each chunk
                for ( j = 0, max = words[ lengthProperty ]; j < max; ) {

                    // The message is expanded into 64 words as part of the iteration
                    var w = words.slice( j, j += 16 );
                    var oldHash = hash;

                    // This is now the undefinedworking hash, often labelled as variables a...g
                    // (we have to truncate as well, otherwise extra entries at the end accumulate.
                    hash = hash.slice( 0, 8 );

                    for ( i = 0; i < 64; i++ ) {
                        var i2 = i + j;

                        // Expand the message into 64 words
                        var w15 = w[ i - 15 ], w2 = w[ i - 2 ];

                        // Iterate
                        var a = hash[ 0 ], e = hash[ 4 ];
                        var temp1 = hash[ 7 ]
                            + ( rightRotate( e, 6 ) ^ rightRotate( e, 11 ) ^ rightRotate( e, 25 ) ) // S1
                            + ( ( e&hash[ 5 ] ) ^ ( ( ~e ) &hash[ 6 ] ) ) // ch
                            + k[i]
                            // Expand the message schedule if needed
                            + ( w[ i ] = ( i < 16 ) ? w[ i ] : (
                                    w[ i - 16 ]
                                    + ( rightRotate( w15, 7 ) ^ rightRotate( w15, 18 ) ^ ( w15 >>> 3 ) ) // s0
                                    + w[ i - 7 ]
                                    + ( rightRotate( w2, 17 ) ^ rightRotate( w2, 19 ) ^ ( w2 >>> 10 ) ) // s1
                                ) | 0
                            );

                        // This is only used once, so *could* be moved below, but it only saves 4 bytes and makes things unreadble:
                        var temp2 = ( rightRotate( a, 2 ) ^ rightRotate( a, 13 ) ^ rightRotate( a, 22 ) ) // S0
                            + ( ( a&hash[ 1 ] )^( a&hash[ 2 ] )^( hash[ 1 ]&hash[ 2 ] ) ); // maj

                            // We don't bother trimming off the extra ones,
                            // they're harmless as long as we're truncating when we do the slice().
                        hash = [ ( temp1 + temp2 )|0 ].concat( hash );
                        hash[ 4 ] = ( hash[ 4 ] + temp1 ) | 0;
                    }

                    for ( i = 0; i < 8; i++ ) {
                        hash[ i ] = ( hash[ i ] + oldHash[ i ] ) | 0;
                    }
                }

                for ( i = 0; i < 8; i++ ) {
                    for ( j = 3; j + 1; j-- ) {
                        var b = ( hash[ i ]>>( j * 8 ) ) & 255;
                        result += ( ( b < 16 ) ? 0 : '' ) + b.toString( 16 );
                    }
                }
                return result;
            },

            // Replace with your desired hash function.
            hashFunc : function( x ) {
                return this.sha256( x );
            },

            // Convert hex char to binary string.
            hexInBin : function( x ) {
                var ret = '';
                switch( x.toUpperCase() ) {
                    case '0':
                    return '0000';
                    break;
                    case '1':
                    return '0001';
                    break;
                    case '2':
                    return '0010';
                    break;
                    case '3':
                    return '0011';
                    break;
                    case '4':
                    return '0100';
                    break;
                    case '5':
                    return '0101';
                    break;
                    case '6':
                    return '0110';
                    break;
                    case '7':
                    return '0111';
                    break;
                    case '8':
                    return '1000';
                    break;
                    case '9':
                    return '1001';
                    break;
                    case 'A':
                    return '1010';
                    break;
                    case 'B':
                    return '1011';
                    break;
                    case 'C':
                    return '1100';
                    break;
                    case 'D':
                    return '1101';
                    break;
                    case 'E':
                    return '1110';
                    break;
                    case 'F':
                    return '1111';
                    break;
                    default :
                    return '0000';
                }
            },

            // Gets the leading number of bits from the string.
            extractBits : function( hexString, numBits ) {
                var bitString = '';
                var numChars = Math.ceil( numBits / 4 );
                for ( var i = 0; i < numChars; i++ ){
                    bitString = bitString + '' + this.hexInBin( hexString.charAt( i ) );
                }

                bitString = bitString.substr( 0, numBits );
                return bitString;
            },

            // Check if a given nonce is a solution for this stamp and difficulty
            // the $difficulty number of leading bits must all be 0 to have a valid solution.
            checkNonce : function( difficulty, stamp, nonce ) {
                var colHash = this.hashFunc( stamp + nonce );
                var checkBits = this.extractBits( colHash, difficulty );
                return ( checkBits == 0 );
            },

            sleep : function( ms ) {
                return new Promise( resolve => setTimeout( resolve, ms ) );
            },

            // Iterate through as many nonces as it takes to find one that gives us a solution hash at the target difficulty.
            findHash : async function() {
                var hashStamp = gdpr_compliant_recaptcha_stamp;
                var hashDifficulty = '<?php echo get_option( Option::POW_DIFFICULTY ); ?>';

                var nonce = 1;

                while( ! this.checkNonce( hashDifficulty, hashStamp, nonce ) ) {
                    nonce++;
                    if ( nonce % 10000 == 0 ) {
                        let remaining = Math.round( ( Math.pow( 2, hashDifficulty ) - nonce ) / 10000 );
                        // Don't peg the CPU and prevent the browser from rendering these updates
                        //await this.sleep( 100 );
                    }
                }
                gdpr_compliant_recaptcha_nonce = nonce;
                
                fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=check_stamp&hashStamp=' + hashStamp + '&hashDifficulty=' + hashDifficulty + '&hashNonce=' + nonce
                })
                .then(function (response) {
                });

                return true;
            },
            
            initCaptcha : function(){
                fetch('<?php echo admin_url( 'admin-ajax.php' ); ?>?action=get_stamp', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                })
                .then(function (response) {
                    return response.json();
                })
                .then(function (response) {
                    gdpr_compliant_recaptcha_stamp = response.stamp;
                    gdpr_compliant_recaptcha.findHash();
                });

            },

            // Function to display a nice-looking error message
            displayErrorMessage : function(message) {
                // Create a div for the error message
                var errorMessageElement = document.createElement('div');
                errorMessageElement.className = 'error-message';
                errorMessageElement.textContent = message;

                // Style the error message
                errorMessageElement.style.position = 'fixed';
                errorMessageElement.style.top = '50%';
                errorMessageElement.style.left = '50%';
                errorMessageElement.style.transform = 'translate(-50%, -50%)';
                errorMessageElement.style.background = '#ff3333';
                errorMessageElement.style.color = '#ffffff';
                errorMessageElement.style.padding = '15px';
                errorMessageElement.style.borderRadius = '10px';
                errorMessageElement.style.zIndex = '1000';

                // Append the error message to the body
                document.body.appendChild(errorMessageElement);

                // Remove the error message after a delay (e.g., 5 seconds)
                setTimeout(function () {
                    errorMessageElement.remove();
                }, 5000);
            },

            addFirstStamp : function(e){
                if( ! gdpr_compliant_recaptcha.stampLoaded){
                    gdpr_compliant_recaptcha.stampLoaded = true;
                    gdpr_compliant_recaptcha.initCaptcha();
                    let forms = document.querySelectorAll('form');
                    //This is important to mark password fields. They shall not be posted to the inbox
                    function convertStringToNestedObject(str) {
                        var keys = str.match(/[^\[\]]+|\[[^\[\]]+\]/g); // Extrahiere Wörter und eckige Klammern
                        var obj = {};
                        var tempObj = obj;

                        for (var i = 0; i < keys.length; i++) {
                            var key = keys[i];

                            // Wenn die eckigen Klammern vorhanden sind
                            if (key.startsWith('[') && key.endsWith(']')) {
                                key = key.substring(1, key.length - 1); // Entferne eckige Klammern
                            }

                            tempObj[key] = (i === keys.length - 1) ? null : {};
                            tempObj = tempObj[key];
                        }

                        return obj;
                    }
                    forms.forEach(form => {
                        let passwordInputs = form.querySelectorAll("input[type='password']");
                        let hashPWFields = [];
                        passwordInputs.forEach(input => {
                            hashPWFields.push(convertStringToNestedObject(input.getAttribute('name')));
                        });
                        
                        if (hashPWFields.length !== 0) {
                            let hashPWFieldsInput = document.createElement('input');
                            hashPWFieldsInput.type = 'hidden';
                            hashPWFieldsInput.classList.add('hashPWFields');
                            hashPWFieldsInput.name = 'hashPWFields';
                            hashPWFieldsInput.value = btoa(JSON.stringify(hashPWFields));//btoa(hashPWFields);
                            form.prepend(hashPWFieldsInput);
                        }
                    });
                    
                    // Override open method to store method and URL
                    XMLHttpRequest.prototype.open = function (method, url) {
                        this._method = method;
                        this._url = url;
                        return gdpr_compliant_recaptcha.originalXhrOpen.apply(this, arguments);
                    };

                    // Override send method to set up onreadystatechange dynamically
                    XMLHttpRequest.prototype.send = function (data) {
                        var self = this;

                        function handleReadyStateChange() {
                            if (self.readyState === 4 && self._method === 'POST') {
                                // Check for an error response
                                if (self.status >= 200 || self.status < 300) {
                                    var responseData = self.responseText;
                                    if(gdpr_compliant_recaptcha.isValidJson(self.responseText)){
                                        // Parse the response JSON
                                        responseData = JSON.parse(self.responseText);
                                    }
                                    // Check if the gdpr_error_message parameter is present
                                    if (!responseData.success && responseData.data && responseData.data.gdpr_error_message) {
                                        // Show an error message
                                        gdpr_compliant_recaptcha.displayErrorMessage(responseData.data.gdpr_error_message);
                                        gdpr_compliant_recaptcha.abortController.abort();
                                        return null;
                                    }
                                }
                            }
                            // Call the original onreadystatechange function
                            if (self._originalOnReadyStateChange) {
                                self._originalOnReadyStateChange.apply(self, arguments);
                            }
                        }

                        // Set up onreadystatechange dynamically
                        if (!this._originalOnReadyStateChange) {
                            this._originalOnReadyStateChange = this.onreadystatechange;
                            this.onreadystatechange = handleReadyStateChange;
                        }

                        // Call each override function in order
                        gdpr_compliant_recaptcha.originalXhrSends.forEach(overrideFunction => {
                            overrideFunction.apply(this, arguments);
                        });

                        result = gdpr_compliant_recaptcha.originalXhrSend.apply(this, arguments);
                        if (result instanceof Promise){
                            return result.then(function() {});
                        }else{
                            return result;
                        }
                    };

                    // Override window.fetch globally
                    window.fetch = gdpr_compliant_recaptcha.handleFetchResponse;

                    setInterval( gdpr_compliant_recaptcha.initCaptcha, <?php echo esc_js( get_option( Option::POW_TIME_WINDOW ) ); ?> * 60000 );
                }
            }
        }
        window.addEventListener( 'load', function gdpr_compliant_recaptcha_load () {
            document.addEventListener( 'keydown', gdpr_compliant_recaptcha.addFirstStamp, { once : true } );
            document.addEventListener( 'mousemove', gdpr_compliant_recaptcha.addFirstStamp, { once : true } );
            document.addEventListener( 'scroll', gdpr_compliant_recaptcha.addFirstStamp, { once : true } );
            document.addEventListener( 'click', gdpr_compliant_recaptcha.addFirstStamp, { once : true } );
        } );
        </script>
        <?php
    }

    /** For Filter hooks
     * 
     */
    public function spam_check( $gdpr_fields ){
        return $this->check_submit( null, $gdpr_fields, 'hook' );
    }

    /** Check whether a valid stamp and nonce are given
     * 
     */
    public function check_submit( $wp = null, $gdpr_fields = null, $form_builder = '', $action = null, $ajax = null ){

        $hook_name = current_filter(); 
        $this->plugin_spam = false;

        // Process the spam check
        if ( ! ( $this->check_request( ) ) ){
            $this->print_debug_information( "Classified as spam" );
            $this->plugin_spam = true;
        }
        
        // Process the spam simulation
        if ( get_option( Option::POW_SIMULATE_SPAM ) && $hook_name !== 'wp_authenticate_user' && $hook_name !== 'wp_signon' ){
            $this->print_debug_information( "Spam simulated" );
            $this->plugin_spam = true;
        }

        // If message shall be saved before flagging
        if( ( get_option( Option::POW_SAVE_SPAM ) && ! get_option( Option::POW_FLAG_SAVE ) && $this->plugin_spam
            || get_option( Option::POW_SAVE_CLEAN ) && ! $this->plugin_spam )
        ){
            $this->save_message( $gdpr_fields, $action, $ajax, null, $this->hash_Values( $this->get_client_ip() ) );
            $this->print_debug_information( "Message saved" );
        }

        if( $gdpr_fields ){
            // If message shall be flagged and message is spam
            if( get_option( Option::POW_FLAG_SPAM ) && $this->plugin_spam ){
                // Line by line for prefixes
                $lines = preg_split('/\r\n|\n|\r/', get_option( Option::POW_FLAG_SUFFIXES ), -1, PREG_SPLIT_NO_EMPTY);
                if ( count( $lines ) > 0 ){
                    function &accessObjectOrArray(&$object, $path) {
                        $pathSegments = explode('->', $path);
                        $currentObject = &$object;
                        foreach ($pathSegments as $segment) {
                            // If the segment is a numeric key, convert it to an integer
                            $segment = is_numeric($segment) ? (int) $segment : $segment;
                    
                            if (is_array($currentObject) && array_key_exists($segment, $currentObject)) {
                                // If the segment is a valid key in the array, move to the next level
                                $currentObject = &$currentObject[$segment];
                            } elseif (is_object($currentObject) && property_exists($currentObject, $segment)) {
                                // If the segment is a valid property in the object, move to the next level
                                $currentObject = &$currentObject->$segment;
                            } else {
                                return null;
                            }
                        }
                        // Modify the value by adding the prefix
                        return $currentObject;
                    }
                    foreach ($lines as $line) {
                        // Add the prefix to the respective posted technical field in order to flag this message as spam
                        $args =  explode(":", $line);
                        if ( count( $args ) == 2 ){
                            $field_link = &accessObjectOrArray( $gdpr_fields, html_entity_decode( $args[ 0 ] ) );
                            $post_link = &accessObjectOrArray( $_POST, html_entity_decode( $args[ 0 ] ) );
                            if ( isset( $field_link ) ){
                                $prefix = htmlspecialchars( $args[1], ENT_QUOTES, 'UTF-8' );
                                //$prefix = filter_var( $args[ 1 ], FILTER_SANITIZE_STRING );
                                $post_link = $prefix . $post_link;
                                $field_link = $prefix . $field_link;
                            }
                        }
                    }
                    $this->print_debug_information( "Fields flagged" );
                }
                // Line by line for new fields
                $lines = preg_split('/\r\n|\n|\r/', get_option( Option::POW_FLAG_TAGS ), -1, PREG_SPLIT_NO_EMPTY);
                if ( count( $lines ) > 0 ){
                    foreach ($lines as $line) {
                        // Add the new field to the _POST array in order to flag this message as spam
                        $args =  explode(":", $line);
                        if ( count( $args ) == 2 ){
                            
                            $field = htmlspecialchars( $args[0], ENT_QUOTES, 'UTF-8' );
                            $value = htmlspecialchars( $args[1], ENT_QUOTES, 'UTF-8' );
                            //$field = filter_var( $args[ 0 ], FILTER_SANITIZE_STRING );
                            //$value = filter_var( $args[ 1 ], FILTER_SANITIZE_STRING );
                            $_POST[ $field ] = $value;
                            $gdpr_fields[ $field ] = $value;
                        }
                    }
                    $this->print_debug_information( "New flag fields added" );
                }
            }

        }

        // If message shall be saved after flagging
        if( get_option( Option::POW_SAVE_SPAM ) 
            && get_option( Option::POW_FLAG_SAVE ) 
            && $this->plugin_spam
        ){
            $this->save_message( $gdpr_fields, $action, $ajax, null, $this->hash_Values( $this->get_client_ip() ) );
            $this->print_debug_information( "Message saved" );
        }

        // If the spam check is called by a filter
        if( $form_builder == 'hook' ){
            $return_value = [
                'isSpam' => $this->plugin_spam,
                'blockSpam' => get_option( Option::POW_BLOCK ),
                'fields' => $gdpr_fields
            ];
            return $return_value;
        }

        // If spam shall be blocked and message is spam
        if ( get_option( Option::POW_BLOCK ) && $this->plugin_spam ){
            $error_message = get_option( Option::POW_ERROR_MESSAGE );
            if ( ! $error_message )
                $error_message = __( 'Your message has been classified as spam! If you are a human, we are very sorry. Please give us notice via email.', 'gdpr-compliant-recaptcha-for-all-forms' );
            // block spam
            /*if ( $action && $action == 'forminator_submit_form_custom-forms' ){
                $response = array(
                    'success' => false,
                    'data' => array(
                        'message' => $error_message,
                        'success' => false,
                        'notice' => 'error',
                    ),
                );
                header('Content-Type: application/json');
                echo( json_encode( $response, JSON_PRETTY_PRINT ) );
                exit;
            }else if( $action && $action == 'tve_api_form_submit' ){
                $response = array(
                    'error' => $error_message,
                    'field' => 'captcha',
                );
                header('Content-Type: application/json');
                echo( json_encode( $response, JSON_PRETTY_PRINT ) );
                exit;
            }else if( $action && $action == 'wpforms_submit' ){
                $response = array(
                    'success' => false,
                    'data' => array(
                        'errors' => array(
                            'general' => array(
                                'header' => '<div class="wpforms-error-container" role="alert">' .
                                            '<span class="wpforms-hidden" aria-hidden="false">Formular-Fehlermeldung</span>' .
                                            '<p>' .
                                            $error_message .
                                            '</p>' .
                                            '</div>'
                            )
                        )
                    )
                );
                header('Content-Type: application/json');
                echo( json_encode( $response, JSON_PRETTY_PRINT ) );
                exit;
            }else if( isset( $_REQUEST[ '_wpcf7' ] ) ){ //contact form 7
                $response = array(
                    'status' => 'spam',
                    'message' => $error_message
                );
                header('Content-Type: application/json');
                echo( json_encode( $response ) );
                exit;
            }else if( isset( $_REQUEST[ 'sib_form_action' ] ) ){ //brevo forms
                if( $_REQUEST[ 'sib_form_action' ] == 'subscribe_form_submit' ){
                    $response = array(
                        'status' => 'gcaptchaFail',
                        'msg' => $error_message
                    );
                    header('Content-Type: application/json');
                    echo( json_encode( $response ) );
                    exit;
                }
            }else if( $action && $action == 'elementor_pro_forms_send_form' ){ //elementor forms pro
                $response = array(
                    'success' => false,
                    'data' => array(
                        'message'=> $error_message,
                        'errors' => array(
                            '' => '',
                        ),
                        'data' => array(),
                    ),
                );
                header('Content-Type: application/json');
                echo( json_encode( $response, JSON_PRETTY_PRINT ) );
                exit;
            }else if( $action && $action == 'fluentform_submit' ){ //FLUENT FORMS
                $response = array(
                    'success' => false,
                    'result' => array(
                        'message' => $error_message,
                    ),
                    'error' => $error_message,
                );
                header('Content-Type: application/json');
                echo( json_encode( $response ) );
                exit;
            }else if( $action && $action == 'wpdAddComment' ){ //wpDiscuz
                echo( $error_message );
                exit;
            }else*/ if( ( isset( $_SERVER[ 'HTTP_ACCEPT' ] ) && stripos( $_SERVER[ 'HTTP_ACCEPT' ], 'application/json' ) !== false ) || $ajax || isset( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) ){ //unknown editor but is ajax
                $response = array(
                    'gdpr_error_message' => $error_message,
                );
                wp_send_json_error( $response );
                exit;
            }else{
                echo $error_message;
                exit;
            }
        }

        // Let wordpress do further processing
        return $wp;

    }

    /** Transforms an array into a string-represantation */

    function generate_paths( $my_id , $data, $current_path, $forbiddenFields, $forbiddenKey, $query, $first, $customTitles , $title ) {
        $values = [];
        $forbidden = false;

        foreach ( $data as $key => $value ) {
            $path = $current_path . ( $current_path ? "->" : "" ) . $key;
            if ( is_array( $value ) || is_object( $value ) ) {
                if( $forbiddenFields ){
                    if( array_key_exists( $forbiddenKey, $forbiddenFields ) ){
                        $forbiddenFields = $forbiddenFields[ $forbiddenKey ];
                        $forbiddenKey = $key;
                    } else {
                        $forbiddenFields = null;
                        $forbiddenKey = null;
                    }
                }
                // Recurse into nested arrays/objects
                $nested_values = $this->generate_paths( $my_id, $value, $path, $forbiddenFields, $forbiddenKey, $query, $first, $customTitles, $title );
                // Merge the nested values with the current values array
                $values = array_merge( $values, $nested_values[ 0 ] );
                $query = $nested_values[ 1 ];
                $title = $nested_values[ 2 ];
                $first = $nested_values[ 3 ];
                $forbidden = $nested_values[ 4 ];
            } else {               
                if( $first ){
                    $first = false;
                }else{
                    $query.=",";
                }
                $query.= "(%d, %s, %s, %d)";
                if ( isset( $customTitles[ htmlentities( $path ) ] ) ){
                    $title .= $value.' | ';
                }
                if( $forbiddenFields ){
                    $forbidden = true;
                } else {
                    $forbidden = false;
                }
                // Add the path and the corresponding value to the values array alternately
                $values[] = $my_id;
                $values[] = $path;
                $values[] = $value;
                $values[] = true;
            }
        }
    
        return [ $values, $query, $title, $first, $forbidden ];
    }

    /** Recursive search like array_walk_recursive, but with depth-control */
    function recursiveSearch($array, $parameter, $depth = 0, $maxDepth = 3, &$found = false) {
        if ($depth > $maxDepth) {
            return;
        }
    
        foreach ($array as $key => $value) {
            if ($key === $parameter) {
                $found = true;
                return;
            }
    
            if ( is_array( $value ) || is_object( $value ) ) {
                $this->recursiveSearch($value, $parameter, $depth + 1, $maxDepth, $found);
            }
        }
    }

    /** Save a message
     * 
     */
    public function save_message( $fields, $action, $ajax, $message_type, $ip ){
        //Check for WooCommerce shopping carts and whether they shall be saved
        if ( get_option( Option::POW_SAVE_CART )
            || ! ( 
                isset( $fields[ 'add-to-cart' ] ) 
                || ( 
                    isset( $fields[ 'update_cart' ] ) 
                    && isset( $fields[ 'woocommerce-cart-nonce' ] )
                )
            )
        ) {
            $posted_site = null;
            if( array_key_exists( 'REQUEST_URI', $_SERVER ) && array_key_exists( 'HTTP_HOST', $_SERVER ) )
                $posted_site = $_SERVER['HTTP_HOST'] . preg_replace( '/^(https?:\/\/)/i', '', $_SERVER[ 'REQUEST_URI' ] );
            $forbiddenFields = [];
            if( isset( $fields[ 'hashPWFields' ] ) ){
                $decodedValues = json_decode( base64_decode( $fields[ 'hashPWFields' ] ), true );
                foreach( $decodedValues as $decodedValue ){
                    foreach ( $decodedValue as $forbiddenKey => $forbiddenField ) {
                        $forbiddenFields[ $forbiddenKey ] = $forbiddenField;
                    }
                }
            }
            global $wpdb;			
            $wpdb->query('START TRANSACTION');
            if ( ! $message_type ){
                if( $this->plugin_spam ){
                    $message_type = 2;
                }else{
                    $message_type = 1;
                }
            }

            //Set the customizable title for the message headers on the message page
            $customTitles = null;
            $lines = preg_split('/\r\n|\n|\r/', get_option( Option::POW_MESSAGE_HEADS ), -1, PREG_SPLIT_NO_EMPTY );
            if ( count( $lines ) > 0 ){
                foreach ( $lines as $line ) {
                    $value = wp_kses_post( $line  );
                    $customTitles[ $value ] = $value;
                }
            }
            $table = $wpdb->prefix.'recaptcha_gdpr_message_rgm';
            $data = array(
                            'rgm_type' => $message_type,
                            'rgm_date' => current_time( 'mysql' ),
                            'rgm_ajax' => $ajax,
                            'rgm_action' => $action,
                            'rgm_ip' => $ip,
                            'rgm_site' => $posted_site,
                        );
            $format = array( '%d','%s', '%d', '%s', '%s', '%s' );
            $wpdb->insert( $table, $data, $format );
            $my_id = $wpdb->insert_id;

            $query = "INSERT INTO ".$wpdb->prefix."recaptcha_gdpr_details_rgd (
                                                            rgm_id,
                                                            rgd_attribute,
                                                            rgd_value,
                                                            rgm_posted
                                                            )
                    VALUES 
                    ";
            $technical_fields = [];
            $values = [];
            $title='';
            $first = true;
            // Remove the protocol (http:// or https://) from the referring URL
            $referrer_without_protocol = null;
            if( array_key_exists( 'HTTP_REFERER', $_SERVER ) )
                $referrer_without_protocol = preg_replace('/^(https?:\/\/)/i', '', $_SERVER['HTTP_REFERER']);
            $technical_fields[ 'from_site' ] = $referrer_without_protocol;
            $technical_fields[ 'post_on_site' ] = $posted_site;
            $technical_fields[ 'is_ajax' ] = $ajax ? __( 'true', 'gdpr-compliant-recaptcha-for-all-forms' ) : __( 'false', 'gdpr-compliant-recaptcha-for-all-forms' );
            if( $action ){
                $technical_fields[ 'action' ] = $action;
            }
            if( get_option( Option::POW_SAVE_IP ) ){
                $technical_fields[ 'IP adress' ] = $this->get_client_ip();
            }

            foreach ( $fields as $key => $value ) {
                if ( is_array( $value ) || is_object( $value ) ) {
                    $nested_values = $this->generate_paths( $my_id, $value, $key, $forbiddenFields, $key, $query, $first, $customTitles, $title );
                    $forbidden = $nested_values[ 4 ];
                    if( $forbidden ){
                        continue;
                    }
                    $values = array_merge( $values, $nested_values[ 0 ] );
                    $query = $nested_values[ 1 ];
                    $title = $nested_values[ 2 ];
                    $first = $nested_values[ 3 ];
                }else{  
                    $forbidden = false;
                    $this->recursiveSearch( $forbiddenFields, $key, 1, 1, $forbidden );
                    if( $forbidden ){
                        continue;
                    }
                    $values[] = $my_id;
                    $values[] = $key;
                    $values[] = $value;
                    $values[] = true;
                    if ( isset( $customTitles[ $key ] ) ){
                        $title .= $value.' | ';
                    }
                    if( $first ){
                        $first = false;
                    }else{
                        $query.=",";
                    }
                    $query.= "(%d, %s, %s, %d)";
                }
            }

            foreach ( $technical_fields as $key => $value ) {
                $values[] = $my_id;
                $values[] = $key;
                $values[] = $value;
                $values[] = false;
                if( $first ){
                    $first = false;
                }else{
                    $query.=",";
                }
                $query.= "(%d, %s, %s, %d)";
            }

            if( $title !== '' ){
                $title = substr( $title, 0, -3 );
            }
            $wpdb->update($table, [ 'rgm_title'=>$title ], array( 'rgm_id'=>$my_id ) );

            $wpdb->query( 
                $wpdb->prepare( $query, $values )
            );
            $wpdb->query('COMMIT');
        }
    }

    /** Function to get a stamp that can be invoked via ajax
     * 
     */
    public function get_stamp_call() {

        // stamp = hash of user ip . salt value
        $stamp = $this->get_stamp();
        
        $array_result = array(
            'stamp' => $stamp,
        );
        // Make your array as json
        wp_send_json( $array_result );

        // Don't forget to stop execution afterward.
        wp_die();

    }

    /** Function to generate a stamp
     * 
     */
    public function get_stamp() {
        $ip = $this->get_client_ip();
        $stamp = $this->hash_Values( $ip . get_option( Option::POW_SALT ) );
        return $stamp;
    }

    /** Attempt to determine the client's IP address
     * 
     */
    private function get_client_ip() {
        $client = "";
        if ( getenv( 'HTTP_CLIENT_IP' ) )
            $client = getenv( 'HTTP_CLIENT_IP' );
        elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) )
            $client =  getenv( 'HTTP_X_FORWARDED_FOR' );
        elseif ( getenv( 'HTTP_X_FORWARDED' ) )
            $client =  getenv( 'HTTP_X_FORWARDED' );
        elseif ( getenv( 'HTTP_FORWARDED_FOR' ) )
            $client =  getenv( 'HTTP_FORWARDED_FOR' );
        elseif ( getenv( 'HTTP_FORWARDED' ) )
            $client =  getenv( 'HTTP_FORWARDED' );
        elseif ( getenv( 'REMOTE_ADDR' ) )
            $client =  getenv( 'REMOTE_ADDR' );

        //$client.= getenv( 'HTTP_USER_AGENT' );
        return $client;
    }

    /** Drop in your desired hash function here
     * 
     */
    private function hash_Values( $x ) {
        return hash( 'sha256', $x, false );
    }

    public function check_request() {
        global $wpdb;

        $versuche = 0;
        while ($versuche < 5) { // Schleife für 3 Sekunden (30 * 100ms)
            $rows = $wpdb->get_results(
                $wpdb->prepare("
                        SELECT 1 as valid
                          FROM " . $wpdb->prefix . "recaptcha_gdpr_stamp_rgs
                         WHERE rgs_ip = %s", $this->hash_Values( $this->get_client_ip() )
                )
            );
            $valid = 0;
            foreach ( $rows as $row ) {
                $valid = $row->valid;
            }
            if ( ! $valid ){
                $versuche++;
                usleep(1000000); // Pause für 100ms
            } else {
                break;
            } 
        }
        return $valid;
    
    }

    /** Check validity, expiration, and difficulty target for a stamp
     * 
     */
    public function check_stamp() {
        $fields = $_POST;
        //The validation of the hashStamp and the nonce is what this whole function is about.
        //The stamp is used to determine whether a valid hash and a valid nonce is given.
        //If either or are crap, we know that the input was manipulated.
        $stamp = preg_replace('/[^a-zA-Z0-9]/', '', $fields['hashStamp']);
        $nonce = $client_difficulty = '';

        // If the difficulty level is not of type int, it has been manipulated and thus remains empty.
        // This will cause the input to be classified as spam
        if( ctype_digit( $fields[ 'hashDifficulty' ] ) ){
            $client_difficulty = filter_var( $fields[ 'hashDifficulty' ], FILTER_SANITIZE_NUMBER_INT );
        }

        // The same holds for the nonce
        if( ctype_digit( $fields[ 'hashNonce' ] ) ){
            $nonce = filter_var( $fields[ 'hashNonce' ], FILTER_SANITIZE_NUMBER_INT );
        }
        
        $this->print_debug_information( "stamp: $stamp" );
        $this->print_debug_information( "difficulty: $client_difficulty" );
        $this->print_debug_information( "nonce: $nonce" );

        $this->print_debug_information( "difficulty comparison: $client_difficulty vs " . get_option( Option::POW_DIFFICULTY ) );
        if ( $client_difficulty != get_option( Option::POW_DIFFICULTY ) ) wp_die();//return false;

        $expectedLength = strlen( $this->hash_Values( uniqid() ) );
        if ( strlen( $stamp ) != $expectedLength ){
            $this->print_debug_information( "stamp size: " . strlen( $stamp ) . " expected: $expectedLength" );
            wp_die();//return false;
        }

        if ( $this->validate_stamp( $stamp ) ) {
            $this->print_debug_information( "Stamp is correct" );
        } else {
            $this->print_debug_information( "Stamp is incorrect" );
            //return false;
            wp_die();
        }

        // check the actual PoW
        if ( $this->check_proof_of_work( get_option( Option::POW_DIFFICULTY ), $stamp, $nonce ) ) {
            $this->print_debug_information( "Difficulty target met." );
        } else {
            $this->print_debug_information( "Difficulty target was not met." );
            //return false;
            wp_die();
        }

        global $wpdb;
        $hashedIP = $this->hash_Values( $this->get_client_ip() );
        // Delete all lines which are older than the predefined time limit + 2 minutes buffer time
        // ... or which are available for the current IP already
        $wpdb->query(
            $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "recaptcha_gdpr_stamp_rgs
                            WHERE rgs_time < NOW() - INTERVAL %d MINUTE", get_option(Option::POW_TIME_WINDOW) + 2
            )
        );
        // Insert the information about the successful spam-check (stamp + hashed IP-adress )
        // The hashed IP-adress doesn't need salt, as a reverse-engineering like using password-lists is impossible
        $wpdb->query(
            $wpdb->prepare( "INSERT INTO ".$wpdb->prefix."recaptcha_gdpr_stamp_rgs (
                                rgs_ip,
                                rgs_stamp,
                                rgs_time
                             )
                             VALUES(%s, %s, NOW())
                            ", $hashedIP, $stamp
            )
        );
        // Don't forget to stop execution afterwards.
        wp_die();
    }

    /** Check whether the stamp was manipulated
     *  
     */
    private function validate_stamp( $a_stamp ) {
        $ip = $this->get_client_ip();
        $validated = false;
        // gen hash for ip & salt
        if ( $a_stamp === $this->hash_Values( $ip . get_option( Option::POW_SALT ) ) ) {
            $validated = true;
        }

        $this->print_debug_information( "stamp expired" );
        return $validated;
    }

    /** check that the hash of the stamp + nonce meets the difficulty target
     *  
     */
    private function check_proof_of_work( $difficulty, $stamp, $nonce ) {
        // get hash of $stamp & $nonce
        $this->print_debug_information( "checking $difficulty bits of work" );
        $work = $this->hash_Values( $stamp . $nonce );

        $leadingBits = $this->hc_ExtractBits( $work, $difficulty );

        $this->print_debug_information( "checking $leadingBits leading bits of $work for difficulty $difficulty match" );

        // if the leading bits are all 0, the difficulty target was met
        return ( strlen( $leadingBits ) > 0 && intval( $leadingBits ) === 0 );
    }

    /** Uncomment the echo statement to get debug info printed to the browser
     *  
     */
    private function print_debug_information( $x ) {
        //echo "<pre>$x</pre>\n";
    }

    /** Get the first num_bits of data from this string
     *  
     */
    private function hc_ExtractBits( $hex_string, $num_bits ) {
        $bit_string = "";
        $num_chars = ceil( $num_bits / 4 );
        for( $i = 0; $i < $num_chars; $i++ )
            $bit_string .= str_pad( base_convert( $hex_string[ $i ], 16, 2 ), 4, "0", STR_PAD_LEFT ); // convert hex to binary and left pad with 0s

        $this->print_debug_information( "requested $num_bits bits from $hex_string, returned $bit_string as " . substr( $bit_string, 0, $num_bits ) );
        return substr( $bit_string, 0, $num_bits );
    }

}

?>