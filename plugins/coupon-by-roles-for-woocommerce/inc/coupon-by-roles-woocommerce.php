<?php
class Coupon_By_Roles_WC {
	/**
  * Constructor for the class
  *
  * @param empty
  * @return mixed
  *
  */
	public function __construct() {
		add_action( 'woocommerce_coupon_options_usage_restriction', array($this, 'add_coupon_user_roles_list'), 10, 0 );
		add_action( 'woocommerce_coupon_options_save', array($this, 'save_coupon_by_roles'));
		add_filter( 'woocommerce_coupon_is_valid', array($this, 'coupon_by_roles_validate'), 10, 2);

		//Add roles column for coupon on admin
		add_filter( 'manage_edit-shop_coupon_columns', array( $this, 'add_roles_fields' ), 10, 1 );
		add_action( 'manage_shop_coupon_posts_custom_column' , array( $this, 'coupon_by_roles_display_roles') );

	}

	/**
  * Adds user roles in the coupon data
  *
  * @param empty
  * @return html
  *
  */
	public function add_coupon_user_roles_list() {
	global $wp_roles;
    
    global $post;
	  $id = get_the_ID();
	 
    $coupon_roles = array();
    
    if( $id ) {
    	$coupon_roles = get_post_meta($id, 'coupon_allowed_roles');
    }

    $user_roles = wp_roles()->get_names();
    ob_start();
    ?>
    <p class="form-field">
   		<label for="coupon_allowed_roles"><?php _e( 'Allowed Roles', 'coupon_by_roles_wc' ); ?></label>
    	<select id="coupon_allowed_roles" name="coupon_allowed_roles[]" style="width: 50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Any Role', 'woocommerce' ); ?>">
    	<?php
    		if( is_array($user_roles) && !empty($user_roles) ) {
    			foreach( $user_roles as $key => $user_role ) :
						if( !empty($coupon_roles) 
    					&& isset($coupon_roles[0]) 
    					&& in_array($key, $coupon_roles[0]) ) :
    					echo '<option value="' . $key . '" selected>' . $user_role . '</option>';
    				else :
    					echo '<option value="' . $key . '">' . $user_role . '</option>';
    				endif;
    			endforeach;
    		}
    	?>
    	</select>
    		<?php echo wc_help_tip( __( 'User with these roles can use this coupon. Leave empty if no roles required.', 'coupon_by_roles_wc' ) ); ?>
    </p>
    <?php
    echo ob_get_clean();
	}

	/**
  * Save coupon by roles in the post meta
  *
  * @param post_id
  * @return boolean
  *
  */
	public function save_coupon_by_roles($post_id) {
		$coupon_roles = array();

		if(empty($_POST['coupon_allowed_roles'])){
			$coupon_roles =NULL;
		}
		elseif( isset($_POST['coupon_allowed_roles']) ) {
			$post_data = $_POST['coupon_allowed_roles'];

			if( is_array($post_data) && !empty($post_data) ) {
				foreach( $post_data as $key => $post_data_input ) {
					array_push($coupon_roles, sanitize_text_field($post_data_input) );
				}
				//if user select role then update the post meta table
			}
			
		}
		update_post_meta( $post_id, 'coupon_allowed_roles', $coupon_roles );

	}

	/**
  * Validate Coupon
  *
  * @param $valid
  * @param $coupon
  * @return boolean
  *
  */
	public function coupon_by_roles_validate($valid, $coupon) {
		$cond = true;

		if( $valid ) {
			$coupon_id = wc_get_coupon_id_by_code( $coupon->get_code() );

			if( $coupon_id ) {
				$coupon_assigned_roles = get_post_meta($coupon_id, 'coupon_allowed_roles');
				$get_user = wp_get_current_user();
				$user_roles = ( array ) $get_user->roles;


				//if role field is not empty
				if(is_array($coupon_assigned_roles) && !empty($coupon_assigned_roles) && isset($coupon_assigned_roles[0]) ) {
					if(empty($user_roles)){
						$cond = false;
					}
					elseif(is_array($user_roles) && !empty($user_roles) ){
						$matched_roles = array_intersect($coupon_assigned_roles[0], $user_roles);

						if( count($matched_roles) > 0 ) {
							$cond = true;
						}
						else{
							$cond = false;
						}
					}
					
				
				}
			}
		}
		return $cond;
	}


	/**
  * Add User Role Column in the coupon
  *
  * @param $columns
  * @return array of columns
  *
  */
	public function add_roles_fields($columns) {
		$columns['user_coupon_roles'] = __('User Roles', 'coupon_by_roles_wc');
    return $columns;
	}

	/**
  * Show roles assigned to the coupon
  *
  * @param $column
  * @return mixed
  *
  */
	public function coupon_by_roles_display_roles($column) {
		global $post;
		$id = $post->ID;

		if( $column === 'user_coupon_roles' ) {
			echo $this->get_coupon_assigned_user_roles($id);
		}
	}

	/**
  * Get roles separated by comma for the coupon id
  *
  * @param coupon id
  * @return string
  *
  */
	public function get_coupon_assigned_user_roles($id) {
		if( empty($id) )
			return;

		$user_roles = get_post_meta($id, 'coupon_allowed_roles');
		$roles_arr = array();

		if( is_array($user_roles) 
			&& !empty($user_roles) 
			&& isset($user_roles[0]) ) {
			foreach ($user_roles[0] as $key => $role) {
				array_push($roles_arr, ucfirst($role));
			}
		}

		if( !empty($roles_arr) ){
			return implode(', ', $roles_arr);
		}
	}

}