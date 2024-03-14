<?php
/**
 * Plugin Name: LH User Taxonomies
 * Plugin URI: https://lhero.org/portfolio/lh-user-taxonomies/
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com/
 * Description: Simplify the process of adding support for custom taxonomies for Users. Just use `register_taxonomy` and everything else is taken care of. 
 * Text Domain: lh_uts
 * Version:	1.61
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('LH_User_Taxonomies_plugin')) {

class LH_User_Taxonomies_plugin {
    
private static $instance;



private static $taxonomies	= array();

static function return_plugin_namespace(){

    return 'lh_uts';

    } 


static function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
	

	 /**
     * This is our way into manipulating registered taxonomies
     * It’s fired at the end of the register_taxonomy function
     *
     * @param String $taxonomy  – The name of the taxonomy being registered
     * @param String $object    – The object type the taxonomy is for; We only care if this is “user”
     * @param Array $args   – The user supplied + default arguments for registering the taxonomy
     */
    public function registered_taxonomy( $taxonomy, $object, $args ) {
        global $wp_taxonomies;
        
        //print_r($wp_taxonomies);
 
        // Only modify user taxonomies, everything else can stay as is
        if ( ( is_string( $object ) and ( 'user' === $object ) ) or ( is_array( $object ) and in_array( 'user', $object, true ) ) ) {
 
            // We’re given an array, but expected to work with an object later on
            $args   = (object) $args;
 
            // Register any hooks/filters that rely on knowing the taxonomy now
            add_filter( 'manage_edit-' . $taxonomy . '_columns', array( $this, 'set_user_column' ) );
            add_action( 'manage_' . $taxonomy . '_custom_column', array( $this, 'set_user_column_values' ), 10, 3 );
 

            self::$taxonomies[ $taxonomy ] = $args;
        }
    }
    
    
    
public function fix_count_callback( $args, $taxonomy, $object_type ) {
    
if ( ( is_string( $object_type) && ( 'user' === $object_type ) ) or ( is_array( $object_type ) && in_array( 'user', $object_type, true ) ) ) {




if ( empty( $args['update_count_callback'] ) ) {
                $args['update_count_callback'] = array( $this, 'update_count' );
            }
            



    }
    
    


    return $args;

}
	
	/**
	 * We need to manually update the number of users for a taxonomy term
	 * 
	 * @see	_update_post_term_count()
	 * @param Array $terms		- List of Term taxonomy IDs
	 * @param Object $taxonomy	- Current taxonomy object of terms
	 */
	public function update_count($terms, $taxonomy) {
		global $wpdb;
		
		foreach((array) $terms as $term) {
			$count	= $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->users WHERE $wpdb->term_relationships.object_id = $wpdb->users.ID and $wpdb->term_relationships.term_taxonomy_id = %d", $term));
			
			do_action('edit_term_taxonomy', $term, $taxonomy);
			$wpdb->update($wpdb->term_taxonomy, compact('count'), array('term_taxonomy_id'=>$term));
			do_action('edited_term_taxonomy', $term, $taxonomy);
		}
	}
	
	/**
	 * Add each of the taxonomies to the Users menu
	 * They will behave in the same was as post taxonomies under the Posts menu item
	 * Taxonomies will appear in alphabetical order
	 */
	public function admin_menu() {
		// Put the taxonomies in alphabetical order
		$taxonomies	= self::$taxonomies;
		ksort($taxonomies);
		
		foreach($taxonomies as $key=>$taxonomy) {

if ($taxonomy->show_in_menu !== false){
			add_users_page(
				$taxonomy->labels->menu_name, 
				$taxonomy->labels->menu_name, 
				$taxonomy->cap->manage_terms, 
				"edit-tags.php?taxonomy={$key}"
			);

}
		}
	}
	
	/**
	 * Fix a bug with highlighting the parent menu item
	 * By default, when on the edit taxonomy page for a user taxonomy, the Posts tab is highlighted
	 * This will correct that bug
	 */
	function parent_menu($parent = '') {
		global $pagenow;
		
		// If we're editing one of the user taxonomies
		// We must be within the users menu, so highlight that
		if(!empty($_GET['taxonomy']) && $pagenow == 'edit-tags.php' && isset(self::$taxonomies[$_GET['taxonomy']])) {
			$parent	= 'users.php';
		}
		
	if(!empty($_GET['taxonomy']) && $pagenow == 'term.php' && isset(self::$taxonomies[$_GET['taxonomy']])) {
			$parent	= 'users.php';
		}
		
		return $parent;
	}
	
	/**
	 * Correct the column names for user taxonomies
	 * Need to replace "Posts" with "Users"
	 */
	public function set_user_column($columns) {
		unset($columns['posts']);
		$columns['users']	= __('Users');
		return $columns;
	}
	
	/**
	 * Set values for custom columns in user taxonomies
	 */
	public function set_user_column_values($display, $column, $term_id) {
		if('users' === $column) {
			$term	= get_term($term_id, $_REQUEST['taxonomy']);
			echo $term->count;
		}
	}
	


	private function buildTree( array &$elements, $parentId = 0 ) {
	$branch = array();
		foreach ($elements as $key=>$element) {
			if ($element->parent == $parentId) {
				$children = $this->buildTree($elements, $element->term_id);
					if ($children) {
						$element->children = $children;
						}
				$branch[$element->term_id] = $element;
			unset($elements[$element->$key]);
			}
			}
		return $branch;
	}
	

	private function renderTree( $elements, $stack, $user, $key, $input = 'checkbox' ) {
		foreach ( $elements as $element ) {
			?>
			<div>
				<input type="<?php echo $input ?>" name="<?php echo $key?>[]" id="<?php echo "{$key}-{$element->slug}"?>" value="<?php echo $element->slug?>" <?php 
				if ($user->ID){
					if (in_array($element->slug, $stack)) {
						echo "checked=\"checked\"";
					}
				}
				?> />
				<label for="<?php echo "{$key}-{$element->slug}"?>"><?php echo $element->name ?></label>
				<?php if( isset( $element->children ) ) {
					?><div style="padding-left: 24px;"><?php
						$this->renderTree( $element->children, $stack, $user, $key, $input );
					?></div><?php
				}
			?></div><?php
	    }
	}
	/**
	 * Add the taxonomies to the user view/edit screen
	 * 
	 * @param Object $user	- The user of the view/edit screen
	 */
	public function user_profile($user) {
		
		// Using output buffering as we need to make sure we have something before outputting the header
		// But we can't rely on the number of taxonomies, as capabilities may vary
		ob_start();
		
		foreach(self::$taxonomies as $key=>$taxonomy){
			// Check the current user can assign terms for this taxonomy
			if(current_user_can($taxonomy->cap->assign_terms) && ($taxonomy->show_ui !== false)){
			// Get all the terms in this taxonomy
			$terms		= get_terms($key, array('hide_empty'=>false));
			$stack 		= wp_list_pluck( wp_get_object_terms( $user->ID, $key ), 'slug' );
			$input_type = ( isset($taxonomy->single_value) && ($taxonomy->single_value == true) ) ? 'radio' : 'checkbox' ; 
			?>

				<table class="form-table">
					<tr>
						<th><label for=""><?php _e("Select {$taxonomy->labels->singular_name}")?></label></th>
						<td>
							<?php if(!empty($terms)): ?>

								<?php $this->renderTree( $this->buildTree( $terms ), $stack, $user, $key, $input_type ); ?>

							<?php else:?>
								<?php _e("There are no {$taxonomy->name} available.")?>
							<?php endif?>
						</td>
					</tr>
				</table>
<input type="hidden" id="<?php echo self::return_plugin_namespace().'-'.$key.'-backend_edit'; ?>" name="<?php echo self::return_plugin_namespace().'-'.$key.'-backend_edit'; ?>" value="1" />
		<?php }
		  }
		// Taxonomies
		// Output the above if we have anything, with a heading
		$output	= ob_get_clean();
		if(!empty($output)) {
			echo '<h3>', __('Taxonomies', self::return_plugin_namespace()), '</h3>';
			echo $output;
		}
	}
	
	/**
	 * Save the custom user taxonomies when saving a users profile
	 * 
	 * @param Integer $user_id	- The ID of the user to update
	 */
    public function save_profile($user_id) {
		foreach(self::$taxonomies as $key=>$taxonomy) {
			// Check the current user can edit this user and assign terms for this taxonomy
			

			if(current_user_can('edit_user', $user_id) && current_user_can($taxonomy->cap->assign_terms) && isset($_POST[self::return_plugin_namespace().'-'.$key.'-backend_edit'])){
			    
			    if (isset($_POST[$key])){

					if (is_array($_POST[$key])){
						$term = $_POST[$key];
						wp_set_object_terms($user_id, $term, $key, false);
					} else {
						$term	= esc_attr($_POST[$key]);
						wp_set_object_terms($user_id, array($term), $key, false);
					}
					
			    } else {
			        
			        wp_delete_object_term_relationships( $user_id, $key );
			        
			        
			    }
				// Save the data
			clean_object_term_cache($user_id, $key);

}
		}
	}
	
	/**
	 * Usernames can't match any of our user taxonomies
	 * As otherwise it will cause a URL conflict
	 * This method prevents that happening
	 */
	public function restrict_username($username) {
		if(isset(self::$taxonomies[$username])) return '';
		
		return $username;
	}
	/**
	 * Add columns for columns with
	 * show_admin_column
	 */
	public function add_user_taxonomy_column_to_users($columns) {
$args=array(
  'object_type' => array('user'),
'show_admin_column' => true
);
$taxonomies = get_taxonomies( $args, "objects");
foreach ($taxonomies as $taxonomy) {
$columns[$taxonomy->name] = $taxonomy->labels->name;
}
    return $columns;
}
	/**
	 * Just a private function to
	 * populate column content
	 */
	private function lh_user_taxonomies_get_user_taxonomies($user, $taxonomy, $page = null) {
$terms = wp_get_object_terms( $user, $taxonomy);
		if(empty($terms)) { return false; }
		$in = array();
		foreach($terms as $term) {
			$href = empty($page) ? add_query_arg(array($taxonomy => $term->slug), admin_url('users.php')) : add_query_arg(array('user-group' => $term->slug), $page);
			$in[] = sprintf('%s%s%s', '<a href="'.$href.'" title="'.esc_attr($term->description).'">', $term->name, '</a>');
		}
	  	return implode('', $in);
	}


/**
 * Get terms for a user and a taxonomy
 *
 * @since 0.1.0
 *
 * @param  mixed  $user
 * @param  int    $taxonomy
 *
 * @return boolean
 */
private function get_terms_for_user( $user = false, $taxonomy = '' ) {

	// Verify user ID
	$user_id = is_object( $user )
		? $user->ID
		: absint( $user );

	// Bail if empty
	if ( empty( $user_id ) ) {
		return false;
	}

	// Return user terms
	return wp_get_object_terms( $user_id, $taxonomy, array(
		'fields' => 'all_with_object_id'
	) );
}

private function set_terms_for_user( $user_id, $taxonomy, $terms = array(), $bulk = false ) {

	// Get the taxonomy
	$tax = get_taxonomy( $taxonomy );

	// Make sure the current user can edit the user and assign terms before proceeding.
	if ( ! current_user_can( 'edit_user', $user_id ) && current_user_can( $tax->cap->assign_terms ) ) {
		return false;
	}

	if ( empty( $terms ) && empty( $bulk ) ) {
		$terms = isset( $_POST[ $taxonomy ] )
			? $_POST[ $taxonomy ]
			: null;
	}

	// Delete all user terms
	if ( is_null( $terms ) || empty( $terms ) ) {
		wp_delete_object_term_relationships( $user_id, $taxonomy );

	// Set the terms
	} else {
		$_terms = array_map( 'sanitize_key', $terms );

		// Sets the terms for the user
		wp_set_object_terms( $user_id, $_terms, $taxonomy, false );
	}

	// Clean the cache
	clean_object_term_cache( $user_id, $taxonomy );
}


	/**
	 * Add the column content
	 * 
	 */
	public function add_users_taxonomy_column_content($value, $column_name, $user_id) {
if (taxonomy_exists($column_name)) {
return $this->lh_user_taxonomies_get_user_taxonomies($user_id,$column_name);
} else {
    return $value;
}
}
	/**
	 * Alters the User query
	 * to return a different list based on query vars on users.php
	 */
	public function user_query($Query = '') {
		global $pagenow,$wpdb;
if ( $pagenow == 'users.php' ){
$args=array(
  'object_type' => array('user'),
'show_admin_column' => true
);
$taxonomies = get_taxonomies( $args, "objects");
foreach ($taxonomies as $taxonomy) {
if(!empty($_GET[$taxonomy->name])) {
$term = get_term_by('slug', esc_attr($_GET[$taxonomy->name]), $taxonomy->name);
$new_ids = get_objects_in_term($term->term_id, $taxonomy->name);
if (!isset($ids) || empty($ids)){  
$ids = $new_ids;  
} else {   
$ids = array_intersect($ids, $new_ids);
}
}
}
if ( isset( $ids ) ){  
$ids = implode(',', wp_parse_id_list( $ids ) );
$Query->query_where .= " AND $wpdb->users.ID IN ($ids)";
}
}		
	
}

/**
	 * Handle bulk editing of users
	 *
	 */
	public function bulk_edit_action() {

		// Action if it is a bulk edit request


//need to fix this nonce and name are same

if (isset($_POST[self::return_plugin_namespace()."-bulk_edit-taxonomy"])){



if (wp_verify_nonce($_POST[self::return_plugin_namespace()."-bulk_edit-nonce"], self::return_plugin_namespace()."-bulk_edit-nonce" )){

$taxonomy = $_POST[self::return_plugin_namespace()."-bulk_edit-taxonomy"];




		// Setup the empty users array
		$users = array();

		// Get an array of users from the string
		parse_str( urldecode( $_POST[ $taxonomy . '-bulk_users_to_action'] ), $users );



		if ( empty( $users['users'] ) ) {
			return;
		}

		$users    = $users['users'];


$action   = strstr( $_POST[self::return_plugin_namespace()."-bulk_edit-action"], '-', true );
$term     = str_replace( $action, '', $_POST[self::return_plugin_namespace()."-bulk_edit-action"] );

foreach ( $users as $user ) {



if ( current_user_can( 'edit_user', $user ) ) {




			// Get term slugs of user for this taxonomy
			$terms = $this->get_terms_for_user( $user, $taxonomy);

			$update_terms = wp_list_pluck( $terms, 'slug' );


// Adding
			if ( 'add' === $action ) {
				if ( ! in_array( $term, $update_terms ) ) {
					$update_terms[] = $term;
				}

			// Removing
			} elseif ( 'remove' === $action ) {
				$index = array_search( $term, $update_terms );
				if ( isset( $update_terms[ $index ] ) ) {
					unset( $update_terms[ $index ] );
				}
			}

			// Delete all groups if they're empty
			if ( empty( $update_terms ) ) {
				$update_terms = null;
			}

			// Update terms for users
			if ( $update_terms !== $terms ) {


				$this->set_terms_for_user( $user, $taxonomy, $update_terms, true );
			}




}
}

		// Success
		wp_safe_redirect( admin_url( 'users.php' ) );
		die;

}

}


	}


	/**
	 * Output the bulk edit markup where show_admin_column is true
	 *
	 *
	 * @param   type  $views
	 * @return  type
	 */
	public function bulk_edit( $views = array() ) {

		// Bail if user cannot edit other users
		if ( ! current_user_can( 'list_users' ) ) {
			return $views;
		}



		// Get taxonomies
$args=array(
  'object_type' => array('user'),
'show_admin_column' => true
);
$taxonomies = get_taxonomies( $args, "objects");



foreach ($taxonomies as $taxonomy){

$terms = get_terms( $taxonomy->name, array('hide_empty' => false ) ); 


?>


		<form method="post" class="user-tax-form">
			<fieldset class="alignleft">
				<legend class="screen-reader-text"><?php esc_html_e( 'Update Groups', self::return_plugin_namespace() ); ?></legend>

<input name="<?php echo esc_attr( $taxonomy->name ); ?>-bulk_users_to_action" value="" type="hidden" id="<?php echo esc_attr( $taxonomy->name ); ?>-bulk_users_to_action" />

				<label for="<?php echo esc_attr( $taxonomy->name ); ?>-select" class="screen-reader-text">
					<?php echo esc_html( $taxonomy->labels->name ); ?>
				</label>

<select class="tax-picker" name="<?php echo esc_attr( self::return_plugin_namespace() ); ?>-bulk_edit-action" id="<?php echo esc_attr( self::return_plugin_namespace() ); ?>-<?php echo esc_attr( $taxonomy->name ); ?>-bulk_edit-action" required="required">
					<option value=""><?php printf( esc_html__( '%s Bulk Update', self::return_plugin_namespace() ), $taxonomy->labels->name ); ?></option>

					<optgroup label="<?php esc_html_e( 'Add', self::return_plugin_namespace() ); ?>">

						<?php foreach ( $terms as $term ) : ?>

							<option value="add-<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>

						<?php endforeach; ?>

					</optgroup>



					<optgroup label="<?php esc_html_e( 'Remove', self::return_plugin_namespace() ); ?>">

						<?php foreach ( $terms as $term ) : ?>

							<option value="remove-<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>

						<?php endforeach; ?>

					</optgroup>

				</select>

<input id="<?php echo self::return_plugin_namespace();  ?>-<?php echo $taxonomy->name;  ?>-bulk_edit-nonce" name="<?php  echo self::return_plugin_namespace();  ?>-bulk_edit-nonce" value="<?php echo wp_create_nonce(self::return_plugin_namespace()."-bulk_edit-nonce"); ?>" type="hidden" />
<input id="<?php echo self::return_plugin_namespace();  ?>-<?php echo $taxonomy->name;  ?>-bulk_edit-taxonomy" name="<?php  echo self::return_plugin_namespace();  ?>-bulk_edit-taxonomy" value="<?php echo $taxonomy->name; ?>" type="hidden" />

				<?php submit_button( esc_html__( 'Apply' ), 'action', $taxonomy->name . '-submit', false ); ?>

			</fieldset>
		</form>

		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				$( '.tablenav.bottom' ).remove();
				$( '.wrap' ).append( $( '.user-tax-form' ) );
				$( '.wrap' ).on( 'submit', '.user-tax-form', function() {
					var users = $( '.wp-list-table.users .check-column input:checked' ).serialize();
					$( '#<?php echo esc_attr( $taxonomy->name ); ?>-bulk_users_to_action' ).val( users );
				} );
			} );
		</script>

		<?php
}




		return $views;
	}
	
// Modify the column contet to link to user listing
public function modify_column_content_start( $content, $column_name, $term_id ){
    
    if ( 'users' == $column_name ) {
        
        ob_start();
        
    }
    
    return $content;
    
    
}
	
	
// Modify the column contet to link to user listing
public function modify_column_content_finish( $content, $column_name, $term_id ){
    
    
    
    if ( 'users' == $column_name ) {
        
                $output = ob_get_contents();
                ob_end_clean();
                
                $output = $output.$content;
                
$screen = get_current_screen();

                
if (is_numeric(intval($output)) && (intval($output) > 0) && ($screen->base == 'edit-tags' || $screen->base == 'term')){

$mytax = get_taxonomy($screen->taxonomy);
$the_term = get_term_by( 'id', $term_id, $screen->taxonomy);

return '<a href="'.add_query_arg($mytax->name, $the_term->slug,admin_url('users.php')).'">'.$output.'</a>';

} else {
    
$content = $output;   
    
}
    }
	return $content;
}
	
public function run_on_admin_init(){
$args=array(
  'object_type' => array('user'),
  'show_admin_column' => true,
);
$taxonomies = get_taxonomies( $args, 'names');    
    
foreach ( $taxonomies as $taxonomy ) {
    

add_filter('manage_'.$taxonomy.'_custom_column', array($this,'modify_column_content_start'), 1, 3);

add_filter('manage_'.$taxonomy.'_custom_column', array($this,'modify_column_content_finish'), 200, 3);
    
}
    
    
    
}

public function fix_term_count($old_user_id, $reassign, $old_user_object ){
    
    $args=array(
  'object_type' => array('user'),
);
$taxonomies = get_taxonomies( $args, "names");

foreach ( $taxonomies as $taxonomy ) {
    
$the_effected_terms = wp_get_object_terms( $old_user_id, $taxonomy );

foreach ( $the_effected_terms as $the_effected_term ) {
    
    $this->update_count(array($the_effected_term->term_id), $taxonomy);
    
    
}

    
}
    
    
}
	
	public function plugin_init(){
	    
	    		// Taxonomies
		add_action('registered_taxonomy', array($this, 'registered_taxonomy'), 1000, 3);
		
		//fix the update count callback
		add_filter( 'register_taxonomy_args', array($this, 'fix_count_callback'), 10, 3 );
		
		// Menus
		add_action('admin_menu', array($this, 'admin_menu'));
		add_filter('parent_file', array($this, 'parent_menu'));
		
		// User Profiles
		add_action('show_user_profile',	array($this, 'user_profile'));
		add_action('edit_user_profile',	array($this, 'user_profile'));
		add_action('personal_options_update', array($this, 'save_profile'));
		add_action('edit_user_profile_update', array($this, 'save_profile'));
		add_action('user_register', array($this, 'save_profile'));
		
		//restrict certain usernames to prevent query var collisions
	    add_filter('sanitize_user', array($this, 'restrict_username'));
		
		//add and populate user taxonomies and terms in the users listsing
		add_filter('manage_users_columns', array($this, 'add_user_taxonomy_column_to_users'));
		add_action('manage_users_custom_column', array($this, 'add_users_taxonomy_column_content'), 10, 3);
        add_action('pre_user_query', array($this, 'user_query'));

		// Bulk edit
		add_filter( 'views_users', array( $this, 'bulk_edit') );
		add_action( 'admin_init',  array( $this, 'bulk_edit_action' ) );
		
		//run some other hooks on admin_init
		add_action( 'admin_init', array($this,'run_on_admin_init'));
		
		//fix the term count when a user is deleted
	    add_action( 'deleted_user',  array( $this, 'fix_term_count' ), 10, 3);
	    
	    
	}
	
    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }


	/**
	 * Register all the hooks and filters we can in advance
	 * Some will need to be registered later on, as they require knowledge of the taxonomy name
	 */
	public function __construct() {
	    
	    //run whatever on plugins loaded 
        add_action( 'plugins_loaded', array($this,'plugin_init'));

	}




}

$lh_user_taxonomies_instance = LH_User_Taxonomies_plugin::get_instance();

}
