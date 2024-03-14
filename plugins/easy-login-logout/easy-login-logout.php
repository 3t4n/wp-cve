<?php
/**
 * Plugin Name:  Easy Login Logout
 * Plugin URI:   https://github.com/ashrafulsarkar/easy-login-logout
 * Description:  Easy Login Logout Menus is the perfect plugin for websites which have login user or logout user.
 * Version:      1.0.1
 * Author:       Ashraful Sarkar
 * Author URI:   https://ashrafulsarkar.com
 * License:      GNU General Public License v2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  easy-llout
 * Domain Path: /languages/
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
class EasyLoginLogout {

	public function __construct() {}

	/**
	 * init()
	 */
	public function init() {
		add_action( 'plugin_loaded', array( $this, 'easylogin_textdomain_load') );
        add_action( 'admin_enqueue_scripts', array($this, 'easylogin_admin_asset') );
        add_action( 'admin_head-nav-menus.php', array($this, 'easylogin_register_metaboxes') );
        add_action( 'wp_nav_menu_item_custom_fields', array($this, 'easylogin_custom_fields'), 10, 2 );
        add_action( 'wp_update_nav_menu_item', array($this, 'easylogin_nav_update'), 10, 2 );
        add_action( 'wp_get_nav_menu_items', array($this, 'easylogin_menu_remove') );
        add_filter( 'wp_setup_nav_menu_item', array($this, 'easylogin_menu_type_label') );
	}

	/**
     * Text Domain Load
     */
    public function easylogin_textdomain_load(){
        load_plugin_textdomain('easy-llout',false, plugin_dir_path(__FILE__).'/languages');
    }

	/**
	 * admin assets load
	 */
	public function easylogin_admin_asset() {
		wp_enqueue_script('easylogin_js', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), null, true);
	}

	/**
	 * register menu metabox
	 */
	public function easylogin_register_metaboxes() {
		add_meta_box('easy_links_menus', __('Easy Links', 'easy-llout'), array($this,'nav_menu_metabox'), 'nav-menus', 'side', 'default');
	}

	/**
	 * callback function
	 */
	public function nav_menu_metabox($object) {
		global $_nav_menu_placeholder, $nav_menu_selected_id;
		$registration_disabled = '1' !== get_option('users_can_register');
		if ($registration_disabled) {
			$elems = array(
				'56' => __('Log In', 'easy-llout'),
				'57' => __('Log Out', 'easy-llout'),
				'59' => __('Profile', 'easy-llout'),
				'60' => __('User Name', 'easy-llout')
			);
		} else {
			$elems = array(
				'56' => __('Log In', 'easy-llout'),
				'57' => __('Log Out', 'easy-llout'),
				'58' => __('Register', 'easy-llout'),
				'59' => __('Profile', 'easy-llout'),
				'60' => __('User Name', 'easy-llout')
			);
		}

		$elems_obj = array();
		foreach ($elems as $value => $title) {
			$elems_obj[$title]              = new easyLloutItems();
			$elems_obj[$title]->object_id	= esc_attr($value);
			$elems_obj[$title]->title		= esc_attr($title);
		}
		$walker = new Walker_Nav_Menu_Checklist(array());
	?>
		<div id="user-menus-div" class="user-menus">
			<div id="tabs-panel-user-menus-all" class="tabs-panel tabs-panel-active">
				<?php $registration_disabled = '1' !== get_option('users_can_register'); ?>
				<?php if ($registration_disabled) : ?>
					<small>
						<span class="dashicons dashicons-info"></span>
						<?php printf(__('Registration is %scurrently disabled%s on your site.', 'easy-llout'), '<a href="' . admin_url('options-general.php') . '">', '</a>'); ?>
					</small>
				<?php endif; ?>
				<ul id="user-menus-checklist-all" class="categorychecklist form-no-clear">
					<?php echo walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $elems_obj), 0, (object) array('walker' => $walker)); ?>
				</ul>
				<p class="button-controls" data-items-type="user-menus-div">
					<span class="list-controls hide-if-no-js">
						<input type="checkbox" id="tabs-panel-posttype-wl-login" class="select-all">
						<label for="tabs-panel-posttype-wl-login"><?php _e('Select All', 'easy-llout'); ?></label>
					</span>
					<span class="add-to-menu">
						<input type="submit" <?php wp_nav_menu_disabled_check($nav_menu_selected_id); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-user-menus-menu-item" id="submit-user-menus-div" />
						<span class="spinner"></span>
					</span>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * custom fields
	 */
	function easylogin_custom_fields($item_id, $item) {
		wp_nonce_field('custom_menu_meta_nonce', '_custom_menu_meta_nonce_name');
		$elems = array('56', '57', '58', '59', '60');
		if (isset($item->object, $item->object_id) && 'easy-llout' != $item->object && !in_array($item->object_id, $elems)) {
			$which_users_options = array(
				''           => __('Everyone', 'easy-llout'),
				'logged_in'  => __('Log In User', 'easy-llout'),
				'logged_out' => __('Log Out User', 'easy-llout'),
			);
	
			$custom_menu_meta = get_post_meta($item_id, '_custom_menu_meta', true);
		?>
			<p class="field-custom_menu_meta description-wide" style="margin: 5px 0;">
				<span class="description"><?php _e("Who can see this link?", 'easy-llout'); ?></span>
				<br />
				<select name="easy_nav_item_options[<?php esc_html_e($item->ID); ?>]" id="jp_nav_item_options-which_users_<?php esc_html_e($item->ID); ?>" class="widefat">
					<?php foreach ($which_users_options as $option => $label) : ?>
						<option value="<?php esc_attr_e($option); ?>" <?php selected($option, esc_html($custom_menu_meta)); ?>>
							<?php echo esc_html($label); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
	
		<?php
		}
		/**
		 * Redicect Url
		 */
		$elems_re = array('56', '57');
		if (isset($item->object, $item->object_id) && 'easy-llout' == $item->object && in_array($item->object_id, $elems_re)) {
			$redirect_types = array(
				'current' => __('Current Page', 'easy-llout'),
				'home'    => __('Home Page', 'easy-llout'),
				'custom'  => __('Custom URL', 'easy-llout'),
			);
	
			$custom_redirect_meta = get_post_meta($item_id, '_custom_redirect_meta', true);
			$custom_redirect_url = get_post_meta($item_id, '_custom_redirect_url', true);
		?>
			<p class="nav_item_options-redirect_type description-wide" style="margin: 5px 0;">
	
				<span class="description"><?php _e("Where should users be taken afterwards?", 'easy-llout'); ?></span>
				<br />
				<select name="easy_redirect_meta_options[<?php esc_html_e($item->ID); ?>]" id="easy_redirect_meta_users_<?php esc_html_e($item->ID); ?>" class="widefat">
					<?php foreach ($redirect_types as $re_option => $label) : ?>
						<option value="<?php esc_attr_e($re_option); ?>" <?php selected($re_option, esc_html($custom_redirect_meta)); ?>>
							<?php echo esc_html($label); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<p class="nav_item_options-redirect_url description  description-wide" style="display: <?php if ($custom_redirect_meta == 'custom') { esc_html_e('block'); } else { esc_html_e('none'); } ?> ;">
				<label for="easy_nav_item_options-redirect_url-<?php esc_html_e($item->ID); ?>">
					<?php _e('Enter a url user should be redirected to', 'easy-llout'); ?><br />
					<input type="text" name="easy_redirect_url_options[<?php esc_html_e($item->ID); ?>]" id="easy_nav_item_options-redirect_url-<?php esc_html_e($item->ID); ?>" value="<?php esc_attr_e($custom_redirect_url); ?>" class="widefat  code" />
				</label>
			</p>
	<?php
		}
	}

	/**
	 * Custom fields Updates 
	 */
	public function easylogin_nav_update($menu_id, $menu_item_db_id){

		//nonce check
		if (!isset($_POST['_custom_menu_meta_nonce_name']) || !wp_verify_nonce($_POST['_custom_menu_meta_nonce_name'], 'custom_menu_meta_nonce')) {
			return $menu_id;
		}

		//nav menu option check and save
		if (isset($_POST['easy_nav_item_options'][$menu_item_db_id])) {
			$sanitized_data = sanitize_text_field($_POST['easy_nav_item_options'][$menu_item_db_id]);
			update_post_meta($menu_item_db_id, '_custom_menu_meta', $sanitized_data);
		} else {
			delete_post_meta($menu_item_db_id, '_custom_menu_meta');
		}

		// Redirect option Data
		if (isset($_POST['easy_redirect_meta_options'][$menu_item_db_id])) {
			$sanitized_data = sanitize_text_field($_POST['easy_redirect_meta_options'][$menu_item_db_id]);
			update_post_meta($menu_item_db_id, '_custom_redirect_meta', $sanitized_data);
		} else {
			delete_post_meta($menu_item_db_id, '_custom_redirect_meta');
		}

		//Redirect Url
		if (isset($_POST['easy_redirect_url_options'][$menu_item_db_id])) {
			$sanitized_data = sanitize_text_field($_POST['easy_redirect_url_options'][$menu_item_db_id]);
			update_post_meta($menu_item_db_id, '_custom_redirect_url', $sanitized_data);
		} else {
			delete_post_meta($menu_item_db_id, '_custom_redirect_url');
		}
	}

	/**
	 * menu remove
	 */
	public function easylogin_menu_remove($items) {
		$hide_items = [];
		if (!is_admin()) {
			if (!is_user_logged_in()) {
				foreach ($items as $keys => $item) {
					$custom_menu_meta = get_post_meta($item->ID, '_custom_menu_meta', true);
					if ($custom_menu_meta == 'logged_in') {
						$hide_items[$keys] = $item->ID;
					}
					$elems = array('57', '59', '60');
					if (isset($item->object, $item->object_id) && 'easy-llout' == $item->object && in_array($item->object_id, $elems)) {
						$hide_items[$keys] = $item->ID;
					}
				}
			} else {
				foreach ($items as $keys => $item) {
					$custom_menu_meta = get_post_meta($item->ID, '_custom_menu_meta', true);
					if ($custom_menu_meta == 'logged_out') {
						$hide_items[$keys] = $item->ID;
					}
					$elems = array('56', '58');
					if (isset($item->object, $item->object_id) && 'easy-llout' == $item->object && in_array($item->object_id, $elems)) {
						$hide_items[$keys] = $item->ID;
					}
				}
			}
		}
		foreach ($hide_items as $key => $value) {
			unset($items[$key]);
		}
		return $items;
	}

	/**
	 * 
	 */
	public function easylogin_menu_type_label($menu_item) {

		if (!is_admin()) {
			$custom_redirect_meta = get_post_meta($menu_item->ID, '_custom_redirect_meta', true);
			$custom_redirect_url = get_post_meta($menu_item->ID, '_custom_redirect_url', true);
	
			$redirect_url = '';
			$menu_item->redirect_url = '';
			if ($custom_redirect_meta == 'current') {
				$redirect_url = get_the_permalink();
			} elseif ($custom_redirect_meta == 'home') {
				$redirect_url = home_url();
			} elseif ($custom_redirect_meta == 'custom') {
				$redirect_url = esc_url($custom_redirect_url);
			}
		}
	
		$elems = array('56', '57', '58', '59', '60');
		if (isset($menu_item->object, $menu_item->object_id) && 'easy-llout' == $menu_item->object && in_array($menu_item->object_id, $elems)) {
			$menu_item->type_label = __('Easy Links', 'easy-llout');
			if (!is_admin()) {
				switch ($menu_item->object_id) {
					case '56':
						$url       = apply_filters('easylogin_menu_login', wp_login_url($redirect_url));
						$menu_item->url = esc_url($url);
						break;
					case '57':
						$url       = apply_filters('easylogin_menu_logout', wp_logout_url($redirect_url));
						$menu_item->url = esc_url($url);
						break;
	
					case '58':
						$url       = apply_filters('easylogin_menu_register', wp_registration_url());
						$menu_item->url = esc_url($url);
						break;
	
					case '59':
						$url       = apply_filters( 'easylogin_menu_profile', $this->easylogin_menu_profile_link() );
						$menu_item->url = esc_url($url);
						break;
	
					case '60':
						$current_user = wp_get_current_user();
						$url       = apply_filters('easylogin_menu_profile', $this->easylogin_menu_profile_link() );
						$username     = apply_filters('easylogin_menu_username', $current_user->display_name);
						$menu_item->url = esc_url($url);
						$menu_item->title  = esc_html($username);
						break;
				}
			}
		}
	
		return $menu_item;
	}

	/**
	 * callback Function
	 */
	public function easylogin_menu_profile_link() {
		if (function_exists('bp_core_get_user_domain')) {
			$url = bp_core_get_user_domain(get_current_user_id());
		} else if (function_exists('bbp_get_user_profile_url')) {
			$url = bbp_get_user_profile_url(get_current_user_id());
		} else if (class_exists('WooCommerce')) {
			$url = get_permalink(get_option('woocommerce_myaccount_page_id'));
		} else {
			$url = get_edit_user_link();
		}
		return $url;
	}
}
/**
 * easyLloutItems Class
 */
class easyLloutItems {
	public $db_id = 0;
	public $object = 'easy-llout';
	public $object_id;
	public $menu_item_parent = 0;
	public $type = 'page';
	public $title;
	public $url;
	public $target = '';
	public $attr_title = '';
	public $classes = array();
	public $xfn = '';
}

/**
 * easyloginlogout Function
 */
function easyloginlogout(){
	$easyloginlogout = new EasyLoginLogout();
	$easyloginlogout->init();
}
add_action('init','easyloginlogout');