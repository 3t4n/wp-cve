<?php
/**
* Plugin Name: SiliconFolio
* Plugin URI: https://siliconthemes.com/siliconfolio-free-portfolio-plugin-for-wordpress/
* Description: Did you ever want to create a portfolio that will showcase your work or products? Didn't you have a single clue on where to start? Well, it's your lucky day! Silicon Themes offers a FREE, yet powerful solution to allow you to create compelling portfolios that will fascinate your website visitors. 
* Version: 1.1.7
* Author: siliconthemes
* Author URI: https://siliconthemes.com/
* License: 
*/

/* Register activation hook. */
register_activation_hook( __FILE__, 'siliconthemes_activation_hook' );

/**
 * Runs only when the plugin is activated.
 * @since 0.1.0
 */
function siliconthemes_activation_hook() {

	/* Create transient data */
	set_transient( 'fx-admin-notice-example', true, 5 );
}


/* Add admin notice */
add_action( 'admin_notices', 'siliconthemes_notice' );


/**
 * Admin Notice on Activation.
 * @since 0.1.0
 */
function siliconthemes_notice(){

	/* Check transient */
	if( get_transient( 'fx-admin-notice-example' ) ){
		?>
		<div class="updated notice is-dismissible">
			<p>Thank you for using <strong>SiliconFolio</strong> plugin. <a target="_blank" href="https://siliconthemes.com/siliconfolio-free-portfolio-plugin-for-wordpress/">How to start working with your portfolio?</a></p>
		</div>
		<?php
		delete_transient( 'fx-admin-notice-example' );
	}
}




add_filter( 'plugin_row_meta', 'siliconfolio_row_meta', 10, 2 );

function siliconfolio_row_meta( $links, $file ) {

	if ( strpos( $file, 'siliconfolio.php' ) !== false ) {
		$new_links = array(
				'overview' => '<a style="background: url('.plugin_dir_url( __FILE__ ).'/framework/img/arr.png); padding-left:20px; background-position:left bottom; background-repeat: no-repeat;" href="https://siliconthemes.com/siliconfolio-free-portfolio-plugin-for-wordpress/" target="_blank">Overview</a>',
				'doc' => '<a style="background: url('.plugin_dir_url( __FILE__ ).'/framework/img/arr.png); padding-left:20px; background-position:left bottom; background-repeat: no-repeat;" href="https://siliconthemes.com/siliconfolio-free-portfolio-plugin-for-wordpress/#how_to_start" target="_blank">How to start?</a>',
				'demo' => '<a style="background: url('.plugin_dir_url( __FILE__ ).'/framework/img/arr.png); padding-left:20px; background-position:left bottom; background-repeat: no-repeat;" href="https://demo.siliconthemes.com/siliconfolio/" target="_blank">Live Demo</a>',
				'questions' => '<a style="background: url('.plugin_dir_url( __FILE__ ).'/framework/img/arr.png); padding-left:20px; background-position:left bottom; background-repeat: no-repeat;" href="https://siliconthemes.com/siliconfolio-free-portfolio-plugin-for-wordpress/#comments" target="_blank">Have a questions? Ask in comments section!</a>',
				'donate' => '<a style="background: url('.plugin_dir_url( __FILE__ ).'/framework/img/arr.png); padding-left:20px; background-position:left bottom; background-repeat: no-repeat;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=A3G4HYE7HLBW2" target="_blank">Donate</a>'
				);
				
		
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_filter( 'template_include', 'include_template_function', 1 );
function include_template_function( $template_path ) {
    if ( get_post_type() == 'portfolio' ) {
        if ( is_single() ) {

                $template_path = dirname( __FILE__ ) . '/single-portfolio.php';
        }
    }
    return $template_path;
}


/* ------------------------------------------------------------------------ */
/* Plugin Scripts */
/* ------------------------------------------------------------------------ */
add_action('wp_enqueue_scripts', 'st_sf_plugin_scripts');
if ( !function_exists( 'st_sf_plugin_scripts' ) ) {
	function st_sf_plugin_scripts() {
		wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ).'framework/css/bootstrap.min.css' );
		wp_enqueue_style( 'Siliconfolio', plugin_dir_url( __FILE__ ).'framework/css/style.css' );
		wp_enqueue_script('st_sf_custom_plugin',plugin_dir_url( __FILE__ ).'framework/js/custom_plugin.js',  array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script('st_sf_Waitimages', plugin_dir_url( __FILE__ ).'framework/js/jquery.waitforimages.js',  array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script('st_sf_isotope', plugin_dir_url( __FILE__ ).'framework/js/isotope.pkgd.min.js',  array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script('st_sf_imagesloaded', plugin_dir_url( __FILE__ ).'framework/js/imagesloaded.js',  array( 'jquery' ), '1.0.0', true );
		$st_sf_theme_plugin = array( 
			'theme_url' => plugin_dir_url( __FILE__ ),
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		);
    	wp_localize_script( 'st_sf_custom_plugin', 'st_sf_theme_plugin', $st_sf_theme_plugin );
	}    
}

function st_sf__load_custom_wp_admin_style() {
	wp_register_style( 'qoon_custom_wp_admin_css',  plugin_dir_url( __FILE__ ) . '/framework/css/admin.css', false, '1.0.0' );
	wp_enqueue_style( 'qoon_custom_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'st_sf__load_custom_wp_admin_style' );


/* ------------------------------------------------------------------------ */
/* Portfolio Post Type.  */
/* ------------------------------------------------------------------------ */



//Create Post Formats
add_action( 'init', 'st_sf_portfolio' );
function st_sf_portfolio() {
	register_post_type( 'portfolio',
		array(
			'labels' => array(
				'name' => __( 'Portfolio', 'siliconfolio' ),
				'singular_name' => __( 'Portfolio', 'siliconfolio' ),
				'new_item' => __( 'Add New portfolio', 'siliconfolio' ),
				'add_new_item' => __( 'Add New portfolio', 'siliconfolio' )
			),
			'public' => true,
			'has_archive' => false,
			'supports' => array( 'comments', 'editor', 'excerpt', 'thumbnail', 'title' ),
			'capability_type' => 'post',
			'show_ui' => true,
			'publicly_queryable' => true,
			'rewrite' => array('slug' => 'portfolio'),
		)
	);
}


function st_sf_portfolio_taxonomies() {
	// Portfolio Categories	
	
	$labels = array(
		'add_new_item' => 'Add New Category',
		'all_items' => 'All Categories' ,
		'edit_item' => 'Edit Category' , 
		'name' => 'Portfolio Categories', 'taxonomy general name' ,
		'new_item_name' => 'New Genre Category' ,
		'menu_name' => 'Categories' ,
		'parent_item' => 'Parent Category' ,
		'parent_item_colon' => 'Parent Category:',
		'singular_name' => 'Portfolio Category', 'taxonomy singular name' ,
		'search_items' =>  'Search Categories' ,
		'update_item' => 'Update Category' ,
	);
	register_taxonomy( 'portfolio-category', array( 'portfolio' ), array(
		'hierarchical' => true,
		'labels' => $labels,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'portfolio/category' ),
		'show_ui' => true,
	));
	
	
	// Portfolio Tags	
	
	$labels = array(
		'add_new_item' => 'Add New Tag' ,
		'all_items' => 'All Tags' ,
		'edit_item' => 'Edit Tag' , 
		'menu_name' => 'Portfolio Tags' ,
		'name' => 'Portfolio Tags', 'taxonomy general name' ,
		'new_item_name' => 'New Genre Tag' ,
		'parent_item' => 'Parent Tag' ,
		'parent_item_colon' => 'Parent Tag:' ,
		'singular_name' =>  'Portfolio Tag', 'taxonomy singular name' ,
		'search_items' =>   'Search Tags' ,
		'update_item' => 'Update Tag' ,
	);
	register_taxonomy( 'portfolio-tags', array( 'portfolio' ), array(
		'hierarchical' => true,
		'labels' => $labels,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'portfolio/tag' ),
		'show_ui' => true,
	));
	
		
}

add_action( 'init', 'st_sf_portfolio_taxonomies', 0 );




class SiliconFolioPageTemplater {
	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;
	/**
	 * The array of templates that this plugin tracks.
	 */
	protected $templates;
	/**
	 * Returns an instance of this class. 
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new SiliconFolioPageTemplater();
		} 
		return self::$instance;
	} 
	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {
		$this->templates = array();
		// Add a filter to the attributes metabox to inject template into the cache.
        	if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) { // 4.6 and older
            		add_filter(
                		'page_attributes_dropdown_pages_args',
                		array( $this, 'register_project_templates' )
            		);
        	} else { // Add a filter to the wp 4.7 version attributes metabox
            		add_filter(
                		'theme_page_templates', array( $this, 'add_new_template' )
            		);
        	}
		// Add a filter to the save post to inject out template into the page cache
		add_filter(
			'wp_insert_post_data', 
			array( $this, 'register_project_templates' ) 
		);
		// Add a filter to the template include to determine if the page has our 
		// template assigned and return it's path
		add_filter(
			'template_include', 
			array( $this, 'view_project_template') 
		);
		// Add your templates to this array.
		$this->templates = array(
			'portfolio.php' => 'Portfolio',
		);
			
	} 
	/**
     	 * Adds our template to the page dropdown for v4.7+
     	 *
     	 */
    	public function add_new_template( $posts_templates ) {
        	$posts_templates = array_merge( $posts_templates, $this->templates );
        	return $posts_templates;
    	}
	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 *
	 */
	public function register_project_templates( $atts ) {
		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
		// Retrieve the cache list. 
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		} 
		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');
		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );
		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );
		return $atts;
	} 
	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_project_template( $template ) {
		
		// Get global post
		global $post;
		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}
		// Return default template if we don't have a custom one defined
		if ( !isset( $this->templates[get_post_meta( 
			$post->ID, '_wp_page_template', true 
		)] ) ) {
			return $template;
		} 
		$file = plugin_dir_path(__FILE__). get_post_meta( 
			$post->ID, '_wp_page_template', true
		);
		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}
		// Return template
		return $template;
	}
} 
add_action( 'plugins_loaded', array( 'SiliconFolioPageTemplater', 'get_instance' ) );

/* ------------------------------------------------------------------------ */
/* Extra Fields.  */
/* ------------------------------------------------------------------------ */
add_action('admin_init', 'extra_fields_plugins', 1);
function extra_fields_plugins() {
	add_meta_box( 'extra_fields_plugin', 'Additional settings', 'extra_fields_for_portfolio', 'portfolio', 'normal', 'high'  );
	add_meta_box( 'extra_fields_plugin', 'Portfolio settings', 'extra_fields_for_pages_plugin', 'page', 'normal', 'high'  );
}

function extra_fields_for_portfolio( $post ){
?>
	
    
    
    
    <h4>Background color on hover</h4>
    <input type="text" name="extra[port-bg]" value="<?php echo get_post_meta($post->ID, 'port-bg', true); ?>" />
    <h4>Text color on hover</h4>
    <input type="text" name="extra[port-text-color]" value="<?php echo get_post_meta($post->ID, 'port-text-color', true); ?>" />
    <h4>Thumbnail size</h4>
    <select name="extra[st_sf_th]">
    <?php $st_sf_thumb_array = array(
		'1' => 'portfolio-squre',
		'2' => 'portfolio-squrex2',
		'3' => 'portfolio-wide',
		'4' => 'portfolio-long'
		);?>
    <?php foreach ($st_sf_thumb_array as $val){ ?>
    <option <?php if ($val == get_post_meta($post->ID, 'st_sf_th', 1)) { echo 'selected';} ?> value="<?php echo esc_attr($val) ?>"><?php echo esc_attr($val) ?></option>
	<?php } ?>
    </select>
<?php };


add_filter('manage_portfolio_posts_columns', 'portfolio_posts_columns_id', 5);
add_action('manage_portfolio_posts_custom_column', 'portfolio_posts_custom_id_columns', 5, 2);
add_filter('manage_portfolio_pages_columns', 'portfolio_posts_columns_id', 5);
add_action('manage_portfolio_pages_custom_column', 'portfolio_posts_custom_id_columns', 5, 2);

function portfolio_posts_columns_id($defaults){
    $defaults['wps_post_id'] = __('ID');
    return $defaults;
}
function portfolio_posts_custom_id_columns($column_name, $id){
        if($column_name === 'wps_post_id'){
                echo $id;
    }
}

add_filter('manage_portfolio_posts_columns', 'portfolio_posts_columns', 1);
add_action('manage_portfolio_posts_custom_column', 'portfolio_posts_custom_columns', 5, 2);

function portfolio_posts_columns($defaults){
    $defaults['riv_post_thumbs'] = __('Thumbs');
    return $defaults;
}

function portfolio_posts_custom_columns($column_name, $id){
        if($column_name === 'riv_post_thumbs'){
        echo the_post_thumbnail( 'mini' );
    }
};



function extra_fields_for_pages_plugin( $post ){
?>
    <div style="padding:20px; border:1px solid #eaeaea; background:#f6f6f6; margin:20px;">
    <h2>Fot Portfolio Templates</h2>
    <hr>
    
    <h4>Show posts from (Use TAGS)</h4>
    <?php $tags = get_categories('taxonomy=portfolio-tags&orderby=name'); ?>
    <select name="extra[st_sf_tag]">
        <option <?php if ("All" == get_post_meta($post->ID, 'st_sf_tag', 1)) { echo 'selected';} ?> value="All">All</option>
        <?php
        foreach ( $tags as $val ) {  ?>
        <option <?php if ($val->name == get_post_meta($post->ID, 'st_sf_tag', 1)) { echo 'selected';} ?> value="<?php echo esc_attr($val->name) ?>"><?php echo esc_attr($val->name) ?></option>
        <?php } ?>
    </select>
    
    
    <div id="st_sf_p_standard">
	
    <h4>Page Content Position?</h4>
    <select style="width:50%;" name="extra[port_page]">
    <?php
    $st_sf_port_page = array (
    "top"  => array("name" => "Top"),
    "bottom"  => array("name" => "Bottom"),
    );
    ?>
    <?php foreach ($st_sf_port_page as $val){ ?>
    <option <?php if ($val['name'] == get_post_meta($post->ID, 'port_page', 1)) { echo 'selected';} ?> value="<?php echo esc_attr($val['name']) ?>"><?php echo esc_attr($val['name']) ?></option>
    <?php } ?>
    </select>
    <h4>Portfolio Layout</h4>
    <select style="width:50%;" name="extra[port_layout]">
    <?php
    $st_sf_port_lay = array (
    "rtws"  => array("name" => "Random Thumbnails With Spaces"),
    "rtwos"  => array("name" => "Random Thumbnails Without Spaces"),
	"sqws"  => array("name" => "Square Thumbnails With Spaces"),
	"sqwos"  => array("name" => "Square Thumbnails Without Spaces"),
	"fsqws"  => array("name" => "4 Square Thumbnails With Spaces"),
	"fsqwos"  => array("name" => "4 Square Thumbnails Without Spaces"),
	"htwos"  => array("name" => "Half Thumbnails Without Spaces"),
	"htws"  => array("name" => "Half Thumbnails With Spaces"),
    );
    ?>
    <?php foreach ($st_sf_port_lay as $val){ ?>
    <option <?php if ($val['name'] == get_post_meta($post->ID, 'port_layout', 1)) { echo 'selected';} ?> value="<?php echo esc_attr($val['name']) ?>"><?php echo esc_attr($val['name']) ?></option>
    <?php } ?>
    </select>
    <h4>How many posts to show?</h4>
    <input type="text" name="extra[port-count]" value="<?php echo esc_attr(get_post_meta($post->ID, 'port-count', true)); ?>" />
	<h4>Show "Load More"?</h4>
    <select style="width:50%;" name="extra[port_load_more]">
    <?php
    $st_sf_port_load_more = array (
    "yes"  => array("name" => "Yes"),
    "no"  => array("name" => "No"),
    );
    ?>
    <?php foreach ($st_sf_port_load_more as $val){ ?>
    <option <?php if ($val['name'] == get_post_meta($post->ID, 'port_load_more', 1)) { echo 'selected';} ?> value="<?php echo esc_attr($val['name']) ?>"><?php echo esc_attr($val['name']) ?></option>
    <?php } ?>
    </select>
    <h4>Show Filters?</h4>
    <select style="width:50%;" name="extra[port_filters]">
    <?php
    $st_sf_port_filters = array (
    "yes"  => array("name" => "Yes"),
    "no"  => array("name" => "No"),
    );
    ?>
    <?php foreach ($st_sf_port_filters as $val){ ?>
    <option <?php if ($val['name'] == get_post_meta($post->ID, 'port_filters', 1)) { echo 'selected';} ?> value="<?php echo esc_attr($val['name']) ?>"><?php echo esc_attr($val['name']) ?></option>
    <?php } ?>
    </select>
    <h4>How many posts to load on button click?</h4>
    <input type="text" name="extra[port-load_count]" value="<?php echo esc_attr(get_post_meta($post->ID, 'port-load_count', true)); ?>" />
    </div>
    </div>
<?php }
//Save Extra Fields
add_action('save_post', 'extra_fields_update', 0);


function extra_fields_update( $post_id ){
	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; 
	if ( !current_user_can('edit_post', $post_id) ) return false; 
	if( !isset($_POST['extra']) ) return false;	

	
	$_POST['extra'] = array_map('trim', $_POST['extra']);
	foreach( $_POST['extra'] as $key=>$value ){
		if( empty($value) )	delete_post_meta($post_id, $key);
		update_post_meta($post_id, $key, $value);
	}
	return $post_id;
}
include plugin_dir_path( __FILE__ ) . 'framework/ajax-portfolio.php';

/*this is an promotion link for our plugin website, if you don't like it just remove it, this link doesn't hurm your website, so don't worry about this, thank you for your understanding*/
function si_after_content(){echo '<div class="siliconthemes"><div style="height:7px; line-height: 3.7em !important; overflow: hidden;">/ Free Portfolio Plugin for WordPress by <a href="https://siliconthemes.com/">Silicon Themes</a>.</div></div>';}

?>