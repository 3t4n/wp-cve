<?php
/**
 * Plugin Name: Responsive Testimonials
 * Plugin URI: https://wpdarko.com/responsive-testimonials/
 * Description: A responsive, clean and easy way to display testimonials. Create new testimonials, add authors and their jobs and copy-paste the shortcode into any post/page. Find help and information on our <a href="https://wpdarko.com/support">support site</a>. This free version is NOT limited and does not contain any ad. Check out the <a href='https://wpdarko.com/responsive-testimonials/'>PRO version</a> for more great features.
 * Version: 1.3
 * Author: WP Darko
 * Author URI: https://wpdarko.com
 * Text Domain: responsive-testimonials
 * Domain Path: /lang/
 * License: GPL2
 */


// Loading text domain
add_action( 'plugins_loaded', 'ttml_load_plugin_textdomain' );
function ttml_load_plugin_textdomain() {
  load_plugin_textdomain( 'responsive-testimonials', FALSE, basename( dirname( __FILE__ ) ) . '/lang/' );
}


/* Check for the PRO version */
add_action( 'admin_init', 'ttml_free_pro_check' );
function ttml_free_pro_check() {
    if (is_plugin_active('responsive-testimonials-pro/ttml_pro.php')) {

        function my_admin_notice(){
        echo '<div class="updated">
                <p><strong>PRO</strong> version is activated.</p>
              </div>';
        }
        add_action('admin_notices', 'my_admin_notice');

        deactivate_plugins(__FILE__);
    }
}


/* Enqueue styles & scripts */
add_action( 'wp_enqueue_scripts', 'add_ttml_scripts' );
function add_ttml_scripts() {
	wp_enqueue_style( 'ttml', plugins_url('css/ttml_custom_style.min.css', __FILE__));
}


/* Enqueue admin styles */
add_action( 'admin_enqueue_scripts', 'add_admin_ttml_style' );
function add_admin_ttml_style() {
    global $post_type;
    if( 'ttml' == $post_type ) {
	    wp_enqueue_style( 'ttml', plugins_url('css/admin_de_style.min.css', __FILE__));
        wp_enqueue_script( 'ttml', plugins_url('js/ttml_admin.min.js', __FILE__), array( 'jquery' ));
    }
}


// Register Tabs post type
add_action( 'init', 'register_ttml_type' );
function register_ttml_type() {
	$labels = array(
		'name'               => __( 'Testimonial sets', 'responsive-testimonials' ),
		'singular_name'      => __( 'Testimonial', 'responsive-testimonials' ),
		'menu_name'          => __( 'Testimonial sets', 'responsive-testimonials' ),
		'name_admin_bar'     => __( 'Testimonial', 'responsive-testimonials' ),
		'add_new'            => __( 'Add New', 'responsive-testimonials' ),
		'add_new_item'       => __( 'Add New Testimonial set', 'responsive-testimonials' ),
		'new_item'           => __( 'New Testimonial set', 'responsive-testimonials' ),
		'edit_item'          => __( 'Edit Testimonial set', 'responsive-testimonials' ),
		'view_item'          => __( 'View Testimonial set', 'responsive-testimonials' ),
		'all_items'          => __( 'All Testimonial sets', 'responsive-testimonials' ),
		'search_items'       => __( 'Search Testimonial sets', 'responsive-testimonials' ),
		'not_found'          => __( 'No Testimonial set found.', 'responsive-testimonials' ),
		'not_found_in_trash' => __( 'No Testimonial set found in Trash.', 'responsive-testimonials' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
        'show_in_admin_bar'  => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => array( 'title' ),
        'menu_icon'          => 'dashicons-plus'
	);
	register_post_type( 'ttml', $args );
}


// Customize update messages
add_filter( 'post_updated_messages', 'ttml_updated_messages' );
function ttml_updated_messages( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );
	$messages['ttml'] = array(
		1  => __( 'Testimonial set updated.', 'responsive-testimonials' ),
		4  => __( 'Testimonial set updated.', 'responsive-testimonials' ),
		6  => __( 'Testimonial set published.', 'responsive-testimonials' ),
		7  => __( 'Testimonial set saved.', 'responsive-testimonials' ),
		10 => __( 'Testimonial set draft updated.', 'responsive-testimonials' )
	);

	if ( $post_type_object->publicly_queryable ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( '', '', '' );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( '', '', '' );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}
	return $messages;
}


// Add the metabox class (CMB2)
if ( file_exists( dirname( __FILE__ ) . '/inc/cmb2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/inc/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/inc/CMB2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/inc/CMB2/init.php';
}


// Registering Testimonials metaboxes
add_action( 'cmb2_init', 'ttml_register_group_metabox' );
require_once('inc/ttml-metaboxes.php');

//Shortcode columns
add_action( 'manage_ttml_posts_custom_column' , 'dkttml_custom_columns', 10, 2 );
add_filter('manage_ttml_posts_columns' , 'add_ttml_columns');
function dkttml_custom_columns( $column, $post_id ) {
    switch ( $column ) {
	case 'shortcode' :
		global $post;
		$slug = '' ;
		$slug = $post->post_name;
    	$shortcode = '<span style="display:inline-block;border:solid 2px lightgray; background:white; padding:0 8px; font-size:13px; line-height:25px; vertical-align:middle;">[ttml name="'.$slug.'"]</span>';
	    echo $shortcode;
	    break;
    }
}
function add_ttml_columns($columns) {return array_merge($columns, array('shortcode' => 'Shortcode'));}


//ttml shortcode
function ttml_sc($atts) {
	extract(shortcode_atts(array(
		"name" => ''
	), $atts));

    global $post;
    $args = array('post_type' => 'ttml', 'name' => $name);
    $custom_posts = get_posts($args);
    foreach($custom_posts as $post) : setup_postdata($post);

	  $testimonials = get_post_meta( get_the_id(), '_ttml_head', true );

    //fetching testimonial options
    $ttml_text_align = get_post_meta( $post->ID, '_ttml_text_align', true );
    $ttml_color = get_post_meta( $post->ID, '_ttml_color', true );

    $ttml_a_size = get_post_meta( $post->ID, '_ttml_author_size', true );
    $ttml_j_size = get_post_meta( $post->ID, '_ttml_job_size', true );
    $ttml_h_size = get_post_meta( $post->ID, '_ttml_heading_size', true );
    $ttml_t_size = get_post_meta( $post->ID, '_ttml_text_size', true );

    //generating the layout options
    $ttml_layout = get_post_meta( $post->ID, '_ttml_layout', true );
    if ($ttml_layout == 'tb2') {$ttml_columns = 2; $ttml_ly = 'default';}
    if ($ttml_layout == 'tb3') {$ttml_columns = 3; $ttml_ly = 'default';}
    if ($ttml_layout == 'tr2') {$ttml_columns = 2; $ttml_ly = 'list';}

    //generating the color options
    $ttml_author_bg = get_post_meta( $post->ID, '_ttml_author_bg', true );
    if ($ttml_author_bg == 'transparent'){$author_bg = 'background: transparent;'; $text_color_job = 'color:'.$ttml_color.' !important;'; $text_color = 'color:#333 !important;';}
    if ($ttml_author_bg == 'whitesmoke'){$author_bg = 'background: whitesmoke;'; $text_color_job = 'color:'.$ttml_color.' !important;'; $text_color = 'color:#333 !important;';}

    // Forcing original fonts?
    $original_font = get_post_meta( $post->ID, '_ttml_original_font', true );
    if ($original_font == true){
        $ori_f = 'ttml_ori_f';
    } else {
        $ori_f = '';
    }
    $output = '';
    $output .= '<div class="ttml ttml_'.$name.'">';
    $output .= '<div class="ttml_'.$ttml_columns.'_columns ttml_'.$ttml_ly.'_layout '.$ori_f.'">';
    $output .= '
        <div class="ttml_wrap ttml_picture_80">
                ';

                $i = 0;
                foreach ($testimonials as $key => $testimonial) {

                    if($i%$ttml_columns == 0) {
                        if($i > 0) {
                            $output .= "</div>";
                            $output .= '<div class="clearer"></div>';
                        } // close div if it's not the first

                        $output .= "<div class='ttml_container'>";
                    }

                    //If as columns (default layout)
                    if ($ttml_ly == 'default'){

                        $output .= '<div class="ttml_testimonial">';

                            $output .= '<div class="ttml_textblock">';
                                if (!empty($testimonial['_ttml_photo'])){
                                    $output .= '<div class="ttml_photo_box" style="width:80px; height:80px; float:left; margin-right:20px;"><img src="'.$testimonial['_ttml_photo'].'" alt="'.$testimonial['_ttml_author'].'"/></div>';
                                }
                                $output .= '<div class="ttml_author_block" style="padding: 4px 15px 10px; '.$author_bg.'">';
                                    $output .= '<p class="ttml_author" style="text-align:left; '.$text_color.'  font-size:'.$ttml_a_size.'px;">'.$testimonial['_ttml_author'].'</p>';
                                    $output .= '<p class="ttml_job" style="text-align:left; '.$text_color_job.' font-size:'.$ttml_j_size.'px;">'.$testimonial['_ttml_job'].'</p>';
                                $output .= '</div>';

                                $output .= '<div class="ttml_text" style="font-size:'.$ttml_t_size.'px; text-align:'.$ttml_text_align.';">'.$testimonial['_ttml_text'].'</div>';

                            //clsing text block
                            $output .= '</div>';

                        //closing testimonial
                        $output .= '</div>';

                    //If as list
                    } elseif ($ttml_ly == 'list') {

                        $output .= '<div class="ttml_testimonial">';

                            $output .= '<div class="ttml_textblock">';

                                $output .= '<div class="ttml_author_block" style="text-align:left; padding:10px 15px; '.$author_bg.'">';
                                    $output .= '<span class="ttml_author" style="'.$text_color.' font-size:'.$ttml_a_size.'px;">'.$testimonial['_ttml_author'].' </span> ';
                                    $output .= ' <span class="ttml_job" style="'.$text_color_job.' font-size:'.$ttml_j_size.'px; float:right;">'.$testimonial['_ttml_job'].'</span>';
                                $output .= '</div>';

                                if (!empty($testimonial['_ttml_photo'])){
                                    $output .= '<div style="float:left !important; padding: 0 14px 0px 0; width:80px; height:80px;" class="ttml_photo_box"><img src="'.$testimonial['_ttml_photo'].'" alt="'.$testimonial['_ttml_author'].'"/></div>';
                                }

                                $output .= '<div class="ttml_text" style="text-align:left; font-size:'.$ttml_t_size.'px;">'.$testimonial['_ttml_text'].'</div>';

                            //closing text block
                            $output .= '</div><div style="clear:both;"></div>';

                        //closing testimonial
                        $output .= '</div>';

                    }
                    $i++;
                } //closing foreach

    $output .= '</div>'; //closing container
    $output .= '</div>'; //closing wrap
    $output .= '</div>'; //closing column number
    $output .= '</div><div style="clear:both;"></div>'; //closing master (ttml)

    endforeach; wp_reset_query();

    return $output;

} //end of shortcode function

add_shortcode("ttml", "ttml_sc");
?>
