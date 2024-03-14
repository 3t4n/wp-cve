<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options;
# bật editor classic
if (isset($foxtool_options['tool-edit1'])){
add_filter('use_block_editor_for_post', '__return_false');
}
# them chuc nang cho classic
if (isset($foxtool_options['tool-edit11'])){
function foxtool_mce_editor_buttons( $buttons ) {
    array_unshift( $buttons, 'fontselect' );
	array_unshift( $buttons, 'fontsizeselect' );
	array_push( $buttons, 'separator', 'table' );
    return $buttons;
}
add_filter( 'mce_buttons_2', 'foxtool_mce_editor_buttons' );
function foxtool_add_the_table_plugin( $plugins ) {
    $plugin_url = FOXTOOL_URL . 'link/tinyMCE/table/plugin.min.js';
    $plugins['table'] = $plugin_url;
    return $plugins;
}
add_filter( 'mce_external_plugins', 'foxtool_add_the_table_plugin' );
}
# them nut add classic vao phan quan lý bài viết và trang
if (isset($foxtool_options['tool-edit12']) && !isset($foxtool_options['tool-edit1'])){
function foxtool_add_classic_editor( $actions, $post){
	if ( 'trash' === $post->post_status || ! post_type_supports( $post->post_type, 'editor' ) ) {
		return $actions;
	}
	$edit_url = get_edit_post_link( $post->ID, 'raw' );
	if ( ! $edit_url ) {
		return $actions;
	}
	if ($post->post_type == 'page' || $post->post_type == 'post') {
		$edit_url = add_query_arg( 'open-classic', '', $edit_url );
		$title       = _draft_or_post_title( $post->ID );
		$edit_action = array(
			'classic' => sprintf(
				'<a href="%s" aria-label="%s">%s</a>',
				esc_url( $edit_url ),
				esc_attr( sprintf(__('Classic editing', 'foxtool'), $title) ),
				sprintf(__('Editor classic', 'foxtool')),
			),
		);
		$edit_offset = array_search( 'edit', array_keys( $actions ), true );
		array_splice( $actions, $edit_offset + 1, 0, $edit_action );
	}
	return $actions;
}
add_filter( 'page_row_actions', 'foxtool_add_classic_editor', 15, 2 );
add_filter( 'post_row_actions', 'foxtool_add_classic_editor', 15, 2 );
// nut classic o quan ly bai viet trang
function foxtool_addbutton_classic() {
    global $pagenow;
    if (($pagenow === 'edit.php' && !isset($_GET['post_type'])) || ($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'page') || ($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'post')){
		if($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'page'){
			$new_post_url = admin_url('post-new.php?post_type=page&open-classic');
		} else {
		$new_post_url = admin_url('post-new.php?open-classic');
		}
        echo '<script>
            jQuery(document).ready(function($) {
                var newButton = \'<a href="'. $new_post_url .'" class="page-title-action" style="margin-left:10px;">'. __('Classic Editor', 'foxtool') .'</a>\';
                $(".wrap h1").append(newButton);
            });
        </script>';
    }
}
add_action('admin_footer', 'foxtool_addbutton_classic');
// chuyen qua classic
if ( isset( $_GET['open-classic'] )) {
	add_filter( 'use_block_editor_for_post_type', '__return_false', 100 );
}
// thêm open-class sau khi luu
function foxtool_add_open_classic_query_arg() {
	if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }
    $post_id = isset($_POST['post_ID']) ? $_POST['post_ID'] : false;
    if ($post_id) {
        $post_type = get_post_type($post_id);
        if ($post_type === 'post' || $post_type === 'page') {
			$edit_post_url = admin_url("post.php?post=$post_id&action=edit");
            $redirect_url = add_query_arg('open-classic', '1', $edit_post_url);
            wp_redirect($redirect_url);
            exit;
        }
    }
}
add_action('save_post', 'foxtool_add_open_classic_query_arg');
}
# bật widget classic
if (isset($foxtool_options['tool-widget1'])){
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );
}
# chuyển link 404 về trang chủ
if (isset($foxtool_options['tool-mana1'])){
function foxtool_redirect_404_to_home() {
    if (is_404()) {
        wp_redirect(home_url());
        exit();
    }
}
add_action('template_redirect', 'foxtool_redirect_404_to_home');
}
# chăn copy nội dung khoa tat ca cac phim
function foxtool_lockcop_scripts() {
  global $foxtool_options;
  if (!is_admin() && isset($foxtool_options['tool-mana2'])){
  wp_enqueue_script( 'lockcop', FOXTOOL_URL . 'link/lockcop.js', array(), FOXTOOL_VERSION);
  wp_enqueue_style( 'lockcop', FOXTOOL_URL . 'link/lockcop.css', array(), FOXTOOL_VERSION);
  }
}
add_action( 'wp_enqueue_scripts', 'foxtool_lockcop_scripts' );
# tắt những công cụ không cần thiết
function foxtool_remove_appwp_admin(){
	global $foxtool_options;
	if (isset($foxtool_options['tool-hiden1'])){
		remove_menu_page( 'index.php' );
	}
	if (isset($foxtool_options['tool-hiden2'])){
		remove_menu_page( 'edit.php' );
	}
	if (isset($foxtool_options['tool-hiden3'])){
		remove_menu_page( 'edit.php?post_type=page' );
	}
	if (isset($foxtool_options['tool-hiden4'])){
		remove_menu_page( 'edit-comments.php' );
	}
	if (isset($foxtool_options['tool-hiden5'])){
		remove_menu_page( 'upload.php' );
	}
	if (isset($foxtool_options['tool-hiden6'])){
		remove_menu_page( 'themes.php' );
	}
	if (isset($foxtool_options['tool-hiden7'])){
		remove_menu_page( 'plugins.php' );
	}
	if (isset($foxtool_options['tool-hiden8'])){
		remove_menu_page( 'users.php' );
	}
	if (isset($foxtool_options['tool-hiden9'])){
		remove_menu_page( 'tools.php' );
	}
	if (isset($foxtool_options['tool-hiden10'])){
		remove_menu_page( 'options-general.php' );
	}
}
add_action( 'admin_menu', 'foxtool_remove_appwp_admin', 999);
# tắt tự động cập nhật
if (isset($foxtool_options['tool-upload1'])){
	add_filter('auto_update_core', '__return_false');
}
if (isset($foxtool_options['tool-upload2'])){
	add_filter('auto_update_translation', '__return_false');
}
if (isset($foxtool_options['tool-upload3'])){
	add_filter('auto_update_theme', '__return_false');
}
if (isset($foxtool_options['tool-upload4'])){
	add_filter('auto_update_plugin', '__return_false');
}

# thêm tiny editor vao description
if ( isset($foxtool_options['tool-mana3'])){
function foxtool_tiny_description($tag){
    ?>
    <table class="form-table">
        <tr class="form-field">
            <th scope="row" valign="top"><label for="description"></label></th>
            <td>
                <?php
                    $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description' );
                    wp_editor(wp_kses_post($tag->description , ENT_QUOTES, 'UTF-8'), 'foxtool_tiny_description', $settings);
                ?>
                <br />
                <span class="description"></span>
            </td>
        </tr>
    </table>
    <?php
}
add_filter('category_edit_form_fields', 'foxtool_tiny_description');
add_filter('product_cat_edit_form_fields', 'foxtool_tiny_description');
// xoa mac dinh
function foxtool_remove_default_category_description(){
    global $current_screen;
    if ($current_screen->taxonomy == 'category' || $current_screen->taxonomy == 'product_cat') {
    echo '<style>textarea#description{display:none}</style>';
    }
}
add_action('admin_head', 'foxtool_remove_default_category_description');
// xoa loc html khi luu
remove_filter('pre_term_description', 'wp_filter_kses');
remove_filter('term_description', 'wp_kses_data');
}
# code chuyen den 503 khi bao tri
if (isset($foxtool_options['tool-dev1'])){
function foxtool_redirect_to_503() {
	global $foxtool_options;
    $link = !empty($foxtool_options['custom-chan11']) ? '/'. $foxtool_options['custom-chan11'] : NULL;
    if (!is_admin() && $_SERVER['REQUEST_URI'] !== '/wp-admin' && $_SERVER['REQUEST_URI'] !== $link && !current_user_can('manage_options')) { ?>
        <script>window.location.replace('<?php echo home_url('/foxtool-503'); ?>');</script>
	<?php
	exit;
    }
}
add_action('wp_head', 'foxtool_redirect_to_503');
// tao page foxtool 503
function foxtool_custom_page_routing() {
    if (strpos($_SERVER['REQUEST_URI'], '/foxtool-503') !== false) {
		include(FOXTOOL_DIR . 'page/foxtool-503.php');
        exit;
    }
}
add_action('template_redirect', 'foxtool_custom_page_routing');
}