<?php
/*
Plugin Name: Restaurant Menu Manager
Plugin URI: http://sabza.org/restaurant-menu-manager-plugin-wordpress/
Description: Allows you to create restaurant menu.
Version: 1.0.5
Author: Noumaan Yaqoob
Author URI: http://www.sabza.org
Text Domain: restaurant-menu-manager
Domain Path: /languages
*/


/*
* Load translation files 
*/

function rm_menu_init() {
 
 load_plugin_textdomain( 'restaurant-menu-manager', false, basename( dirname( __FILE__ ) ) . '/languages/'  );
}
add_action('plugins_loaded', 'rm_menu_init');

/* 
* Custom post types and taxonomies for Restaurant Menu
*/

/* Register Custom Post Type for Restaurant Menu Entries */

function menu_entries_post_type() { 

	$labels = array(
		'name'                => _x( 'Menu Entries', 'Post Type General Name', 'restaurant-menu-manager' ),
		'singular_name'       => _x( 'Menu Entry', 'Post Type Singular Name', 'restaurant-menu-manager' ),
		'menu_name'           => __( 'Restaurant Menu', 'restaurant-menu-manager' ),
		'parent_item_colon'   => __( 'Parent Restaurant Menu Entry', 'restaurant-menu-manager' ),
		'all_items'           => __( 'All Restaurant Menu Entries', 'restaurant-menu-manager' ),
		'view_item'           => __( 'View Restaurant Menu Entry', 'restaurant-menu-manager' ),
		'add_new_item'        => __( 'Add new Restaurant Menu Entry', 'restaurant-menu-manager' ),
		'add_new'             => __( 'New Restaurant Menu Entry', 'restaurant-menu-manager' ),
		'edit_item'           => __( 'Edit Restaurant Menu Entry', 'restaurant-menu-manager' ),
		'update_item'         => __( 'Update Restaurant Menu Entry', 'restaurant-menu-manager' ),
		'search_items'        => __( 'Search Restaurant Menu Entries', 'restaurant-menu-manager' ),
		'not_found'           => __( 'No Restaurant Menu Entries Found', 'restaurant-menu-manager' ),
		'not_found_in_trash'  => __( 'No Restaurant Menu Entries found in trash', 'restaurant-menu-manager' ),
	);
	$rewrite = array(
		'slug'                => 'menu-item',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => __( 'restaurant-menu-entry', 'restaurant-menu-manager' ),
		'description'         => __( 'Items or entries in your restaurant menu', 'restaurant-menu-manager' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'comments', 'revisions', 'excerpt', 'custom-fields' ),
		'taxonomies'          => array( 'rm-menu-type' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	register_post_type( 'rm-menu-entry', $args );
} 
add_action( 'init', 'menu_entries_post_type' ,0);

// Custom taxonomy for Menu Types

if ( ! function_exists('menu_type_taxonomy') ) {


function menu_type_taxonomy()  {

	$labels = array(
		'name'                       => _x( 'Menu Types', 'Taxonomy General Name', 'restaurant-menu-manager' ),
		'singular_name'              => _x( 'Menu Type', 'Taxonomy Singular Name', 'restaurant-menu-manager' ),
		'menu_name'                  => __( 'Menu Type', 'restaurant-menu-manager' ),
		'all_items'                  => __( 'Menu Types', 'restaurant-menu-manager' ),
		'parent_item'                => __( 'Parent Menu Type', 'restaurant-menu-manager' ),
		'parent_item_colon'          => __( 'Parent Menu Type:', 'restaurant-menu-manager' ),
		'new_item_name'              => __( 'New Menu Type', 'restaurant-menu-manager' ),
		'add_new_item'               => __( 'Add New Menu Type', 'restaurant-menu-manager' ),
		'edit_item'                  => __( 'Edit Menu Type', 'restaurant-menu-manager' ),
		'update_item'                => __( 'Update Menu Type', 'restaurant-menu-manager' ),
		'separate_items_with_commas' => __( 'Separate Menu Type with commas', 'restaurant-menu-manager' ),
		'search_items'               => __( 'Search Menu Types', 'restaurant-menu-manager' ),
		'add_or_remove_items'        => __( 'Add or remove Menu Types', 'restaurant-menu-manager' ),
		'choose_from_most_used'      => __( 'Choose from the most used Menu Types', 'restaurant-menu-manager' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'rm-menu-type', 'rm-menu-entry', $args );

}
add_action( 'init', 'menu_type_taxonomy', 0 );



// Custom Taxonomy for entry tags

if ( ! function_exists('custom_taxonomy_entry_types') ) {

function custom_taxonomy_entry_tags()  {

	$labels = array(
		'name'                       => _x( 'Entry Tags', 'Taxonomy General Name', 'restaurant-menu-manager' ),
		'singular_name'              => _x( 'Entry Tag', 'Taxonomy Singular Name', 'restaurant-menu-manager' ),
		'menu_name'                  => __( 'Entry Tags', 'restaurant-menu-manager' ),
		'all_items'                  => __( 'All Entry Tags', 'restaurant-menu-manager' ),
		'parent_item'                => __( 'Parent Entry Tags', 'restaurant-menu-manager' ),
		'parent_item_colon'          => __( 'Parent Entry Tags: ', 'restaurant-menu-manager' ),
		'new_item_name'              => __( 'New Entry Tags Name', 'restaurant-menu-manager' ),
		'add_new_item'               => __( 'Add New Entry Tag', 'restaurant-menu-manager' ),
		'edit_item'                  => __( 'Edit Entry Tag', 'restaurant-menu-manager' ),
		'update_item'                => __( 'Update Entry Tag', 'restaurant-menu-manager' ),
		'separate_items_with_commas' => __( 'Separate Entry Tags with commas e.g. spicy, vegetarian, sugar-free', 'restaurant-menu-manager' ),
		'search_items'               => __( 'Search Entry Tags', 'restaurant-menu-manager' ),
		'add_or_remove_items'        => __( 'Add or remove Entry Tags', 'restaurant-menu-manager' ),
		'choose_from_most_used'      => __( 'Choose from the most used Entry Tags', 'restaurant-menu-manager' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'rm-entry-tags', 'rm-menu-entry', $args );

}
add_action( 'init', 'custom_taxonomy_entry_tags', 0 );

}


}

// Create custom meta field for Entry Price

add_action( 'load-post.php', 'rm_menu_entry_meta_boxes_setup' );
add_action( 'load-post-new.php', 'rm_menu_entry_meta_boxes_setup' );

function rm_menu_entry_meta_boxes_setup() {
	add_action( 'add_meta_boxes', 'rm_add_menu_entry_meta_boxes' );
	add_action( 'save_post', 'rm_save_menu_entry_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function rm_add_menu_entry_meta_boxes() {

	add_meta_box(
		'rm-menu-entry-price',
		esc_html__( 'Entry Price', 'restaurant-menu-manager' ),
		'rm_menu_entry_meta_box',
		'rm-menu-entry',
		'side',
		'default'					
	);
}

/* Display the post meta box. */
function rm_menu_entry_meta_box( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'rm_menu_entry_nonce' ); ?>

	<p>
		<label for="rm-menu-entry-price"><?php _e( "Menu Entry Price.", 'restaurant-menu-manager' ); ?></label>
		<br />
		<input class="widefat" type="text" name="rm-menu-entry-price" id="rm-menu-entry-price" value="<?php echo esc_attr( get_post_meta( $object->ID, 'rm_menu_entry_price', true ) ); ?>" size="30" />
	</p>
<?php }


/* Save the meta box's post metadata. */
function rm_save_menu_entry_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['rm_menu_entry_nonce'] ) || !wp_verify_nonce( $_POST['rm_menu_entry_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value = ( isset( $_POST['rm-menu-entry-price'] ) ? sanitize_text_field( $_POST['rm-menu-entry-price'] ) : '' );

	/* Get the meta key. */
	$meta_key = 'rm_menu_entry_price';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
}

// Hide Custom Fields Box 

function rm_hide_custom_fields() { 
remove_meta_box( 'postcustom','rm-menu-entry','normal' ); 
}
add_action( 'admin_menu', 'rm_hide_custom_fields' ); 

// Menu icons for Restaurant Menu Custom Post Type


add_action( 'admin_head', 'rm_cpt_icons' );
function rm_cpt_icons() {

$version = get_bloginfo('version');

if ($version < 3.8) 

$iconurl = plugins_url('/images/rm-menu-icon.png', __FILE__);
    ?>
    <style type="text/css" media="screen">
        #menu-posts-rm-menu-entry .wp-menu-image {
            background: url(<?php echo $iconurl; ?>) no-repeat 6px -17px !important;
        }
        #menu-posts-rm-menu-entry:hover .wp-menu-image, #menu-posts-rm-menu-entry.wp-has-current-submenu .wp-menu-image {
            background-position:6px 7px!important;
        }
    </style>
<?php } 

// Function to display entry price from custom meta field

function display_entry_price() { 
	
$entry_price = get_post_meta( get_the_ID() , 'rm_menu_entry_price', true );
	
		if ( !empty( $entry_price ) )
			$price[] = sanitize_text_field( $entry_price );
			return  $price[0]; 
			} 
			

/*
*
* This part of the code displays menu on the front end.
* There are three types of display a simple list like WordPress posts, jQuery accordion, jQuery tab.
* Menu entries are grouped under Menu Types
*
*/ 
			
function rm_list_menu($atts, $content = null) {
ob_start();
extract( shortcode_atts( array(
		'display' => '',
	), $atts ) ); 

/* Load Stylesheet */

wp_enqueue_style('restaurant-menu-css', plugins_url('/restaurant-menu-css.css',__FILE__));
	
/* If no display type is set, then show the menu in simple list */

if (!is_array ($atts) ) : 

$menu_types = get_terms( 'rm-menu-type');

foreach ( $menu_types as $menu_type ) {
$args = array(
	'post_type' => 'rm-menu-entry',
	'nopaging'	=> true,
	'tax_query' => array(
		array(
			'taxonomy' => 'rm-menu-type',
			'field' => 'slug',
			'terms' => $menu_type 
		)
	)
);


?>
<h2 class="menu-type"><?php echo $menu_type->name; ?></h2>
<div>
<?php $the_query = new WP_Query( $args );       
if ( $the_query->have_posts() ) : 
wp_enqueue_style( 'restaurant-menu-screen', plugins_url('restaurant-menu-screen.css', __FILE__) );

if ( ! function_exists('new_excerpt_more') ) {
	function new_excerpt_more( $more ) {
	$moretext = __('Learn More...', 'restaurant-menu-manager' );
	return ' <a class="learn-more" href="'. get_permalink( get_the_ID() ) . '"> ' . $moretext .'</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );
}

 while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	
    <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Learn more about: ', 'restaurant-menu-manager' ); ?><?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
	<?php 
	if ( has_post_thumbnail() ) { 
	?>
	<div class="menu-entry-thumbnail">
	<?php the_post_thumbnail('thumbnail'); ?>
	</div>
	<?php } 
	?>
	
	<div class="menu-entry-excerpt"><?php the_excerpt(); ?></div>
	<div class="entry-tags">
	
	<?php	
	the_terms( $the_query->ID, 'Entry Tags: ',  ' / ' ); ?> 
	</div>
	<div class="menu-entry-meta"><span class="price-text"><?php _e('Price:', 'restaurant-menu-manager'); ?></span>
	<?php 	echo display_entry_price(); 	?>
	</div>
  <?php endwhile; ?>
	</div>
  

  <?php wp_reset_postdata(); ?>

<?php else:  ?>
  <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; 
	   
    } // ends for each loop 
	

// if display is set for Accordion
	
elseif ($atts['display'] == 'accordion') : 

// load jQuery UI Accordion Scripts

wp_register_script('rm-jquery-accordion', plugins_url('/rm-jquery-accordion.js', __FILE__ ), array('jquery-ui-accordion'), '', true);
wp_enqueue_script('rm-jquery-accordion');


?>
<div id="accordion">

<?php
// First we will query terms in taxonomy menu type
// then for each menu type we will fetch entries

$menu_types = get_terms( 'rm-menu-type');

foreach ( $menu_types as $menu_type ) {
$args = array(
	'post_type' => 'rm-menu-entry',
	'nopaging'	=> true,
	'tax_query' => array(
		array(
			'taxonomy' => 'rm-menu-type',
			'field' => 'slug',
			'terms' => $menu_type 
		)
	)
);


?>
<h2 class="menu-type"><?php echo $menu_type->name; ?></h2>
<div>
<?php $the_query = new WP_Query( $args );       
if ( $the_query->have_posts() ) : 
wp_enqueue_style( 'restaurant-menu-screen', plugins_url('restaurant-menu-screen.css', __FILE__) );

if ( ! function_exists('new_excerpt_more') ) {
	function new_excerpt_more( $more ) {
	$moretext = __('Learn More...', 'restaurant-menu-manager' );
	return ' <a class="learn-more" href="'. get_permalink( get_the_ID() ) . '"> ' . $moretext .'</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );
}

 while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	
    <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Learn more about: ', 'restaurant-menu-manager' ); ?><?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
	<?php 
	if ( has_post_thumbnail() ) { 
	?>
	<div class="menu-entry-thumbnail">
	<?php the_post_thumbnail('thumbnail'); ?>
	</div>
	<?php } ?>

	<div class="menu-entry-excerpt"><?php the_excerpt(); ?></div>
	<div class="entry-tags">
	<?php	the_terms( $the_query->ID, 'rm-entry-tags', 'Entry Tags: ', ' / ' ); ?> 
	</div>
	<div class="menu-entry-meta"><span class="price-text"><?php _e('Price:', 'restaurant-menu-manager'); ?></span>
	<?php 	echo display_entry_price(); 	?>
	</div>
  <?php endwhile; ?>
	</div>
  

  <?php wp_reset_postdata(); ?>

<?php else:  ?>
  <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; 
	   
    } // ends for each loop
	
	?>
	 </div>
	 <?php

	 
// If display is set for Tabs
	 
elseif ($atts['display'] == 'tabs') : 

wp_register_script('rm-jquery-tabs', plugins_url('/rm-jquery-tabs.js', __FILE__ ), array('jquery-ui-tabs'), '', true);
wp_enqueue_script('rm-jquery-tabs');

 
// First we will query terms in taxonomy menu type
// then for each menu type we will fetch entries
?>
<div id="tabs">

<ul>

<?php
$menu_types = get_terms( 'rm-menu-type');

//list tabs first
$tab_count = 1; 
foreach ( $menu_types as $menu_type ) {
$args = array(
	'post_type' => 'rm-menu-entry',
	'nopaging'	=> true,
	'tax_query' => array(
		array(
			'taxonomy' => 'rm-menu-type',
			'field' => 'slug',
			'terms' => $menu_type 
		)
	)
);


?>
<li><a href="#tabs-<?php echo $tab_count;?>"><?php echo $menu_type->name; ?></a></li>
<?php 
$tab_count++;
} 
?>
</ul>

<?php
$tab_count = 1;
foreach ( $menu_types as $menu_type ) {
$args = array(
	'post_type' => 'rm-menu-entry',
	'nopaging'	=> true,
	'tax_query' => array(
		array(
			'taxonomy' => 'rm-menu-type',
			'field' => 'slug',
			'terms' => $menu_type 
		)
	)
);
?>

<div id="tabs-<?php echo $tab_count;?>">

<?php $the_query = new WP_Query( $args );       
if ( $the_query->have_posts() ) : 

if ( ! function_exists('new_excerpt_more') ) {
	function new_excerpt_more( $more ) {
	$moretext = __('Learn More...', 'restaurant-menu-manager' );
	return ' <a class="learn-more" href="'. get_permalink( get_the_ID() ) . '"> ' . $moretext .'</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );
}

 while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	
    <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Learn more about: ', 'restaurant-menu-manager' ); ?><?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
	<?php 
	if ( has_post_thumbnail() ) { 
	?>
	<div class="menu-entry-thumbnail">
	<?php the_post_thumbnail('thumbnail'); ?>
	</div>
	<?php }  ?>
	
	<div class="menu-entry-excerpt"><?php the_excerpt(); ?></div>
	<div class="entry-tags">
	<?php	the_terms( $the_query->ID, 'rm-entry-tags', 'Entry Tags: ' , ' / ' ); ?> 
	</div>
	<div class="menu-entry-meta"><span class="price-text"><?php _e('Price:', 'restaurant-menu-manager'); ?></span>
	<?php 	echo display_entry_price(); 	?>
	</div>
  <?php endwhile; ?>
	</div>
  

  <?php wp_reset_postdata(); ?>

<?php else:  ?>
  <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif; 
	$tab_count++;   
    } ?>
	</div>
<?php



endif;  
return ob_get_clean();
}

add_shortcode('rm-menu', 'rm_list_menu'); 

/*
* Filter content to display display entry price on a single menu item 
* 
*/ 

add_filter( 'the_content', 'rm_single_item_content', 20 );

function rm_single_item_content( $content ) {
	
    if ( 'rm-menu-entry' == get_post_type(get_the_ID() ) AND is_single() ) :
	wp_enqueue_style('restaurant-menu-css', plugins_url('/restaurant-menu-css.css',__FILE__));
	$price = __('Price:', 'rm-menu-text-domain');
	$content .= sprintf(
            '<div class="menu-entry-meta"><span class="price-text">%1$s %2$s</span></div> ', $price, display_entry_price(), 'restaurant-menu-manager',
            $content
        );
		endif;
    return $content;

}

?>