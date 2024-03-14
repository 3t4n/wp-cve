<?php
/**
 * Plugin Name: WP Create Multiple Posts & Pages
 * Plugin URI:  https://wordpress.org/plugins/wp-create-multiple-posts-pages/
 * Description: Create Multiple Wordpress Posts & Pages At Once With a Single Click.
 * Author:      Sajjad Hossain Sagor
 * Author URI:  http://profiles.wordpress.org/sajjad67
 * Version:     1.0.6
 * License:     GPL
 * Text Domain: wp-create-multiple-posts-pages
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

// ---------------------------------------------------------
// Define Plugin Folders Path
// ---------------------------------------------------------
define( "WPCMP_PLUGIN_PATH", plugin_dir_path( __FILE__ ) );

define( "WPCMP_PLUGIN_URL", plugin_dir_url( __FILE__ ) );

/**
 * Main Class
 */
class WP_CREATE_MULTI_POSTS_PAGES
{
    /**
     * Load Plugin Files & Add Hooks
     */
    public function __construct()
    {
    	add_action( 'init', array( $this, 'init' ) );

    	add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    }

    /**
    * 
    */
    public function init()
    {
    	add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
    }

    /**
    * Add Plugin Settings page to wp menu dashboard
    */
    public function add_menu_page()
    {
		add_menu_page( 'Multiple Posts', 'Multiple Posts', 'manage_options' , 'wp-add-multiple-posts', array( $this, 'wp_add_multiple_posts' ), 'dashicons-menu' );
    }

    /**
    * Multi Post Creating Form
    */
    public function wp_add_multiple_posts()
    {
		$post_types = $this->get_custom_post_types();

		require_once WPCMP_PLUGIN_PATH . 'includes/settings.php'; ?>

		<div class="wrap">
			<h2>Create Multiple Posts</h2>
			<?php if ( isset( $wpcmp_show_message ) ) : ?>
				<div class="notice notice-<?php echo $wpcmp_show_message; ?> is-dismissible posts_create_success_failure_message">
					<p><?php echo $wpcmp_message; ?></p>
				</div>
				<?php $i = 0; ?>
				<div class="notice notice-success is-dismissible" style="padding-right: 10px;">
					<h2>Created Posts :</h2>
					<div class="container-fluid">
						<?php foreach ( $created_posts as $id ) : $i++; ?>
							<div class="row" style="border-bottom: 1px solid lightgrey;">
							  	<p class="col-md-11" style="line-height: 28px;"><?php echo esc_html( get_the_title( $id ) ); ?></p>
							  		
							  	<p class="col-md-1" style="text-align: right;"><a href="<?php echo get_the_permalink($id); ?>" class='button' style="margin-right: 10px;">View</a><a href="<?php echo get_edit_post_link( $id ); ?>" class='button'>Edit</a></p>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

			<?php endif; ?>

			<form action="" method="post">
	    		<div class="form-group">
		    		<p class="col-form-label insert_note">Insert Posts Title One Per Line...</p>
					<textarea class="form-control new_line_bg new_line_number" name="wpcmp_posts_titles" id="wpcmp_posts_titles" cols="30" rows="8"></textarea>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col">
		    				<p class="col-form-label insert_note">Post Type</p>
							<select name="wpcmp_new_post_type" id="wpcmp_new_post_type" class="form-control" disabled>
								<?php foreach ( $post_types as $post_type ) : ?>
									<option value="<?php echo $post_type; ?>"><?php echo ucfirst( $post_type ) ?></option>
								<?php endforeach ?>
							</select>
						</div>
						<div class="col">
			    			<p class="col-form-label insert_note">Post Status</p>
					      	<select name="wpcmp_new_post_status" id="wpcmp_new_post_status" class="form-control" disabled>
					      		<!-- <option value="" disabled selected style="display:none"></option> -->
					      		<option value="publish">Publish</option>
					      		<option value="draft">Draft</option>
					      		<option value="pending">Pending</option>
					      		<option value="private">Private</option>
					      	</select>
					    </div>

					    <div class="col">
			    			<p class="col-form-label insert_note">Post Author</p>
					      	<select name="wpcmp_new_post_author" id="wpcmp_new_post_author" class="form-control" disabled>
					      		<!-- <option value="" disabled selected style="display:none"></option> -->
					      		<?php

					      			$users = get_users( 'who=authors' );
					      			
					      			foreach ( $users as $user ) {
									   
									   echo '<option value="'.$user->ID.'">'.ucwords( str_replace( "_", " ", $user->user_login ) ).'</option>';
									}
					      		?>
					      	</select>
					    </div>

					    <div class="col">
			    			<p class="col-form-label insert_note">Post Category (<small>Posts Only)</small></p>
					      	<select name="wpcmp_new_post_category[]" id="wpcmp_new_post_category" class="form-control" multiple disabled>
					      		<?php
					      			foreach ( get_categories() as $category ) {
									   
									   echo '<option value="'.$category->term_id.'">'.ucwords( str_replace( "_", " ", $category->cat_name ) ).'</option>';
									}
					      		?>
					      	</select>
					    </div>
		  			</div>
				</div>
				<?php wp_nonce_field( 'wpcmp_create_posts', 'wpcmp_nonce' ); ?>

				<button type="submit" class="button" name="wpcmp_submit_for_create_posts" id="wpcmp_submit_for_create_posts" disabled>Add Posts</button>
			</form>
		</div>

	<?php }

    /**
    * Enqueue JS & CSS Files
    */
    public function admin_enqueue_scripts()
    {
		wp_enqueue_style ( 'wpcmp_bootstrap_css', WPCMP_PLUGIN_URL . '/assets/css/bootstrap.css', false );
		
		wp_enqueue_style ( 'wpcmpselect2_css',    WPCMP_PLUGIN_URL . 'assets/css/select2.min.css', true );
		
		wp_enqueue_style ( 'wpcmp_style_css', 	  WPCMP_PLUGIN_URL . '/assets/css/style.css', false );
		
		wp_enqueue_script( 'wpcmpselect2_js', 	  WPCMP_PLUGIN_URL . 'assets/js/select2.min.js', true );
		
		wp_enqueue_script( 'wpcmp_popper_js',     WPCMP_PLUGIN_URL . '/assets/js/popper.min.js', true );
		
		wp_enqueue_script( 'wpcmp_bootstrap_js',  WPCMP_PLUGIN_URL . '/assets/js/bootstrap.min.js', array( 'jquery', 'wpcmp_popper_js' ), true );
		
		wp_enqueue_script( 'wpcmp_linedtextarea', WPCMP_PLUGIN_URL . '/assets/js/linedtextarea.js', array( 'jquery' ), true );
		
		wp_enqueue_script( 'wpcmp_script', 		  WPCMP_PLUGIN_URL . '/assets/js/script.js', array( 'jquery' ), true );
	}

	/**
    * Get All Custom Post Types Registered By Others
    */
	function get_custom_post_types()
	{
		$all_post_types = array( 'post', 'page' );

		$args = array(
	       'public'   => true,
	       '_builtin' => false,
	    );

	    $output = 'names'; // names or objects, note names is the default
	    
	    $operator = 'and'; // 'and' or 'or'

	    $post_types = get_post_types( $args, $output, $operator );

	    if ( ! empty( $post_types ) )
	    {
	    	$all_post_types = array_merge( $all_post_types, $post_types );
	    }
	    
	    return $all_post_types;
	}
}

$WP_CREATE_MULTI_POSTS_PAGES = new WP_CREATE_MULTI_POSTS_PAGES();
