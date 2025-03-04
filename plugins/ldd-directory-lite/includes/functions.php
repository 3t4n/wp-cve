<?php
/**
 * General functionality
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * Pull an array of directory post IDs for use in updating all the information.
 */
function ldl_get_all_IDs() {
    global $wpdb;

    $query = sprintf("
					SELECT ID
					FROM `%s`
					WHERE post_type = '%s'
						AND post_status NOT IN ( 'auto-draft', 'inherit' )
				", $wpdb->posts, LDDLITE_POST_TYPE);

    return $wpdb->get_col($query);
}


/**
 * Wrapper to collect post meta for a listing
 *
 * @param $id
 * @param $field
 */
function ldl_has_meta($key) {
    return ldl_get_meta($key) ? true : false;
}


/**
 * Wrapper to collect post meta for a listing
 *
 * @param $id
 * @param $field
 */
function ldl_get_meta($key) {

    $post_id = get_the_ID();

    if (!$post_id) {
        return false;
    }

    return get_metadata('post', $post_id, ldl_pfx($key), true);
}


/**
 * Appends the plugin prefix to key/field identifiers to maintain readability in other functions
 *
 * @param string $key The field identifier to be prefixed
 *
 * @return string A prefixed string
 */
function ldl_pfx($key) {
    $prefixed = '_' . trim(LDDLITE_PFX, '_') . '_';

    return $prefixed . $key;
}


function ldl_get_listing_meta($id) {

    if (!is_int($id) || LDDLITE_POST_TYPE != get_post_type($id))
        return false;

    $defaults = array(
        'address_one' => '',
        'address_two' => '',
        'city'        => '',
        'subdivision' => '',
        'post_code'   => '',
        'address'     => '',
        'geocode'     => '',
        'website'     => '',
        'email'       => '',
        'phone'       => '',
    );

    $meta['address_one'] = get_post_meta($id, ldl_pfx('address_one'), 1);
    $meta['address_two'] = get_post_meta($id, ldl_pfx('address_two'), 1);
    $meta['city'] = get_post_meta($id, ldl_pfx('city'), 1);
    $meta['subdivision'] = get_post_meta($id, ldl_pfx('subdivision'), 1);
    $meta['post_code'] = get_post_meta($id, ldl_pfx('post_code'), 1);

    $address = '';
    $geocode = '';

    foreach ($meta as $key => $value) {
        if ('address_two' != $key && empty($value)) {
            $address = false;
            break;
        }
    }

    if (false !== $address) {

        $address = '<i class="fa fa-map-marker"></i>  ' . $meta['address_one'];
        if (!empty($meta['address_two']))
            $address .= '<br>' . $meta['address_two'];
        $address .= ',<br>' . $meta['city'] . ', ' . $meta['subdivision'] . ' ' . $meta['post_code'];

        $geocode = urlencode(str_replace('<br>', ' ', $address));

    } else {
        $address = '';
    }

    $meta['address'] = $address;
    $meta['geocode'] = $geocode;

    $website = get_post_meta($id, ldl_pfx('url_website'), 1);
    if ($website)
        $meta['website'] = apply_filters('lddlite_listing_website', sprintf('<a href="%1$s"><i class="fa fa-link"></i>  %1$s</a>', esc_url($website)));

    $meta['email'] = get_post_meta($id, ldl_pfx('contact_email'), 1);
    $meta['phone'] = get_post_meta($id, ldl_pfx('contact_phone'), 1);

    $meta = wp_parse_args($meta, $defaults);

    return $meta;

}


function ldl_sanitize_twitter($url) {

    if (empty($url))
        return;

    $url = preg_replace('/[^A-Za-z0-9\/:.]/', '', $url);

    if (strpos($url, '/') !== false)
        $url = substr($url, strrpos($url, '/') + 1);

    $url = 'https://twitter.com/' . $url;

    return $url;
}


/**
 * Alias for wp_mail that sets headers for us.
 *
 * @since 1.3.13
 *
 * @param string $to      Email address this message is going to
 * @param string $subject Email subject
 * @param string $message Email contents
 * @param string $headers Optional, default is managed internally.
 */
function ldl_mail($to, $subject, $message, $headers = '') {

    $from_name = ldl()->get_option('email_from_name', get_bloginfo('name'));
    $from_email = ldl()->get_option('email_from_address', get_bloginfo('admin_email'));

    // If we're not passing any headers, default to our internal from address
    if (empty($headers)) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= sprintf(__('From: %s <%s>', 'ldd-directory-lite'), $from_name, $from_email) . "\r\n";
    }

    ob_start();
    wp_mail($to, $subject, $message, $headers);
    ob_end_clean();

}

/**
 * Output Functions
 * =====================================================================================================================
 */

/**
 * Replaces the protocol with HTTPS
 *
 * @since 0.5.3
 *
 * @param string $url The URL
 *
 * @return string The modified URL
 */
function ldl_force_scheme($url, $scheme = 'https') {

    if (0 !== strpos($url, 'http'))
        $url = esc_url_raw($url);

    return set_url_scheme($url, $scheme);
}

function get_term_post_count( $taxonomy = 'category', $term = '', $args = [] )
{
	// Lets first validate and sanitize our parameters, on failure, just return false
	if ( !$term )
		return false;

	if ( $term !== 'all' ) {
		if ( !is_array( $term ) ) {
			$term = filter_var(       $term, FILTER_VALIDATE_INT );
		} else {
			$term = filter_var_array( $term, FILTER_VALIDATE_INT );
		}
	}

	if ( $taxonomy !== 'category' ) {
		$taxonomy = filter_var( $taxonomy, FILTER_SANITIZE_STRING );
		if ( !taxonomy_exists( $taxonomy ) )
			return false;
	}

	if ( $args ) {
		if ( !is_array )
			return false;
	}

	// Now that we have come this far, lets continue and wrap it up
	// Set our default args
	$defaults = [
		'posts_per_page' => 1,
		'fields'         => 'ids'
	];

	if ( $term !== 'all' ) {
		$defaults['tax_query'] = [
			[
				'taxonomy' => $taxonomy,
				'terms'    => $term
			]
		];
	}
	$combined_args = wp_parse_args( $args, $defaults );
	$q = new WP_Query( $combined_args );

	// Return the post count
	return $q->found_posts;
}


/********* TinyMCE Email Button ***********/
add_action( 'after_setup_theme', 'ldd_theme_setup' );

if ( ! function_exists( 'ldd_theme_setup' ) ) {
   function ldd_theme_setup() {

       add_action( 'init', 'ldd_buttons' );

   }
}

if ( ! function_exists( 'ldd_buttons' ) ) {
    function ldd_buttons() {
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }
 
        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }
        if ( !isset($_GET['post_type']) || $_GET['post_type'] !="directory_listings" ) {
            return;
        }
 
        add_filter( 'mce_external_plugins', 'ldd_add_buttons' );
        add_filter( 'mce_buttons', 'ldd_register_buttons' );
    }
}
 
if ( ! function_exists( 'ldd_add_buttons' ) ) {
    function ldd_add_buttons( $plugin_array ) {
        $plugin_array['mybutton'] = plugins_url( '../public/js/tinymce_buttons.js', __FILE__ );//get_template_directory_uri().'/assets/tinymce_buttons.js';
        return $plugin_array;
    }
}
 
if ( ! function_exists( 'ldd_register_buttons' ) ) {
    function ldd_register_buttons( $buttons ) {
        array_push( $buttons, 'mybutton' );
        return $buttons;
    }
}
 
add_action ( 'after_wp_tiny_mce', 'ldd_tinymce_extra_vars' );
 
if ( !function_exists( 'ldd_tinymce_extra_vars' ) ) {
	function ldd_tinymce_extra_vars() { ?>
		<script type="text/javascript">
			var tinyMCE_object = <?php echo json_encode(
				array(
					'button_name' => esc_html__('{...}', 'lddslug'),
                    'button_title' => esc_html__('Available shortcode', 'lddslug'),
					'image_title' => esc_html__('Image', 'lddslug'),
					'image_button_title' => esc_html__('Upload image', 'lddslug'),
				)
				);
			?>;
		</script><?php
	}
}
/********* TinyMCE Email Button End ***********/

/*
*Adding styles to admin area
*/

add_action('admin_head', 'ldd_my_custom_fonts');

function ldd_my_custom_fonts() {
	?>
  <style>
    .mce-i-main:before{}
        .mce-menu-item-normal.mce-active {background:#fff !important; color:#333  !important}
        .mce-menu-item-normal.mce-active .mce-text{color:#333  !important}
        .ldd_pl_noimage{border: 1px dashed #333;
            width: 350px;
            padding: 8px;
            font-weight: bold;}

  </style>
  <?php
}

/* for admin script*/
function ldd_add_placeholderjs($hook){
    if($hook !="lddlite-settings")
    return; 

    wp_enqueue_script('lddlite-placeholder');
}
add_action( 'admin_enqueue_scripts', 'ldd_add_placeholderjs' );


/*
* Delete placeholder image from diecoty settings
*/
function ldd_delet_placeholder()
{
	if(isset($_GET['ldd_act'])){
        if(ldl()->get_option('ldd_placeholder_image')){
		
            if($_GET['ldd_act'] == "del_ph"){
                
                
                $opt_array = get_option('lddlite_settings'); 
                
                $arr2 = $opt_array['ldd_placeholder_image']='';
                
                
                if(update_option('lddlite_settings',$opt_array)){
                    wp_redirect(admin_url()."edit.php?post_type=directory_listings&page=lddlite-settings&tab=appearance");
                    exit;
                }
                
            }
        }
    }
}
add_action('admin_init','ldd_delet_placeholder');

if ( ! function_exists( 'slug_masonry_init' )) :
function slug_masonry_init() { 
?>
<script>
    //set the container that Masonry will be inside of in a var
	
   
</script>
<?php }
//add to wp_footer
add_action( 'wp_footer', 'slug_masonry_init' );
endif; // ! slug_masonry_init exists