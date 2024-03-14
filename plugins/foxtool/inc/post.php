<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } 
global $foxtool_options;
# Code tự động lưu ảnh vào lưu trữ của bạn
if(isset($foxtool_options['post-up1'])){
    class foxtool_save_images_hots {
        function __construct() {
            add_filter('content_save_pre', array($this, 'foxtool_post_save_images'));
        }
        function foxtool_post_save_images($content) {
            global $post;
            if ($post && isset($post->ID)) {
                $post_id = $post->ID;
                $post_status = get_post_status($post_id);
                if ($post_status == 'publish' || $post_status == 'draft' || $post_status == 'pending') {
                    set_time_limit(240);
                    $preg = preg_match_all('/<img.*?src="(.*?)"/', stripslashes($content), $matches);
                    if ($preg) {
                        foreach ($matches[1] as $image_url) {
                            if (empty($image_url)) continue;
                            $pos = strpos($image_url, $_SERVER['HTTP_HOST']);

                            if ($pos === false) {
                                $res = $this->foxtool_fill_save_images($image_url, $post_id);
								if ($res !== null) {
									$url = wp_get_attachment_url($res); 
									$content = str_replace($image_url, $url, $content);
								}

                            }
                        }
                    }
                }
            }
            remove_filter('content_save_pre', array($this, 'foxtool_post_save_images'));
            return $content;
        }
        function foxtool_fill_save_images($image_url, $post_id) {
			$file = file_get_contents($image_url);
			$post = get_post($post_id);
			if ($post) {
				$posttitle = $post->post_title;
				$postname = sanitize_title($posttitle);
				$url_parts = parse_url($image_url);
				$image_path = pathinfo($url_parts['path']);
				$file_extension = $image_path['extension'];
				// Thêm 4 ký tự ngẫu nhiên vào cuối post ID
				$random_suffix = wp_generate_password(4, false);
				$im_name = "$postname-$random_suffix.$file_extension";
				$res = wp_upload_bits($im_name, '', $file);
				$attach_id = $this->foxtool_insert_attachment($res['file'], $post_id);
				return $attach_id;
			}

			// Xử lý khi không có giá trị $post
			return null;
		}


        function foxtool_insert_attachment($file, $id) {
            $dirs = wp_upload_dir();
            $filetype = wp_check_filetype($file);
            $attachment = array(
                'guid' => $dirs['baseurl'] . '/' . _wp_relative_upload_path($file),
                'post_mime_type' => $filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($file)),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment($attachment, $file, $id);
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
            wp_update_attachment_metadata($attach_id, $attach_data);
            return $attach_id;
        }
    }
    new foxtool_save_images_hots();
}
# Xóa bài viết sẽ xóa luôn hình ảnh đính kèm trong post story land
if(isset($foxtool_options['post-del1'])){
function fox_delete_all_attached_media( $post_id ) {
		// xoa anh dai dien
		$thumbnail_id = get_post_thumbnail_id($post_id);
		if ($thumbnail_id) {
			wp_delete_attachment($thumbnail_id, true);
		}	
		// xoa anh dinh kem	
		$attachments = get_attached_media( '', $post_id );
		foreach ($attachments as $attachment) {
			wp_delete_attachment( $attachment->ID, 'true' );
		}
}
add_action( 'before_delete_post', 'fox_delete_all_attached_media' );
}
# anh kich thuoc goc khi them vao bai viet
if(isset($foxtool_options['post-imgsize1'])){
	update_option('image_default_size', 'full');
} else {
	update_option('image_default_size', 'medium');

}
# tự động lấy ảnh đầu tiên bài viết thế vào làm ảnh đại diện
if(isset($foxtool_options['post-thum1'])){
// lay anh thu id goc tu anh thu nho
function foxtool_from_thumbnail_url($thumbnail_url) {
    global $wpdb;
    $original_id = attachment_url_to_postid($thumbnail_url);
    $filename = wp_basename($thumbnail_url);
    $thumbnail_filename = preg_replace('/-\d+x\d+(\.\w+)$/', '$1', $filename);
    $query = $wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_wp_attached_file' AND meta_value LIKE %s", '%' . $thumbnail_filename . '%');
    $attachment_id = $wpdb->get_var($query);
    return $attachment_id ? $attachment_id : $original_id;
}
// xu ly
function foxtool_auto_featured_image($post_id) {
	global $foxtool_options;
	$imgdua = !empty($foxtool_options['post-thum11']) ? $foxtool_options['post-thum11'] : null;
    $post = get_post($post_id);
    if ($post && !has_post_thumbnail($post->ID)) {
        $first_img = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		if (!empty($matches[1][0])) {
			$first_img = $matches[1][0];
			$default_image_id = foxtool_from_thumbnail_url($first_img);
			set_post_thumbnail($post->ID, $default_image_id);
		}
		else {
            $default_image_url = $imgdua;
            $default_image_id = attachment_url_to_postid($default_image_url);
            if ($default_image_id) {
                set_post_thumbnail($post->ID, $default_image_id);
            }
        }
    }
}
add_action('publish_post', 'foxtool_auto_featured_image');
}
# chuc nang nhan ban bai viet va trang
if(isset($foxtool_options['post-dup1'])){
function duplicate_post() {
    $nonce = sanitize_text_field($_REQUEST['nonce'] ?? '');
    $post_id = intval($_REQUEST['post'] ?? 0);
    if (empty($nonce) || empty($post_id)) {
        wp_die(__('Invalid request state', 'foxtool'));
    }
    if (!wp_verify_nonce($nonce, 'duplicate-page-' . $post_id)) {
        wp_die(__('Security check failed', 'foxtool'));
    }
    $current_user_id = get_current_user_id();
    $post = get_post($post_id);
    if (current_user_can('manage_options') || current_user_can('edit_others_posts') ||
        (current_user_can('edit_posts') && $current_user_id == $post->post_author)) {
        $this->duplicate_edit_post($post_id);
    } elseif (current_user_can('contributor') && $current_user_id == $post->post_author) {
        $this->duplicate_edit_post($post_id, 'pending');
    } else {
        wp_die(__('Modifying settings is not allowed', 'foxtool'));
    }
}
add_action('admin_action_duplicate_as_draft', 'duplicate_post');
// Tạo liên kết nhân bản
function foxtool_quick_duplicate_post_button($actions, $post) {
    if ($post->post_type == 'post' || $post->post_type == 'page') {
        $actions['duplicate'] = sprintf(
            '<a href="%s" title="%s" rel="permalink">%s</a>',
            esc_url(wp_nonce_url(admin_url('admin.php?action=duplicate_post&post=' . $post->ID), 'duplicate-post_' . $post->ID)),
            esc_attr__('Duplicate content', 'foxtool'),
            __('Duplicate', 'foxtool')
        );
    }
    return $actions;
}
add_filter('post_row_actions', 'foxtool_quick_duplicate_post_button', 10, 2);
add_filter('page_row_actions', 'foxtool_quick_duplicate_post_button', 10, 2);
// Tạo chức năng nhân bản nhanh
function foxtool_quick_duplicate_post_action() {
    if (isset($_GET['action']) && $_GET['action'] == 'duplicate_post' && isset($_GET['post'])) {
        $post_id = absint($_GET['post']);
        check_admin_referer('duplicate-post_' . $post_id);
        $post = get_post($post_id);
        if ($post) {
            $new_post = array(
                'post_title'     => $post->post_title . ' ' . __('(duplicate)', 'foxtool'),
                'post_status'    => 'draft',
                'post_type'      => $post->post_type,
                'comment_status' => $post->comment_status,
                'ping_status'    => $post->ping_status,
                'post_content'   => wp_slash($post->post_content),
                'post_excerpt'   => $post->post_excerpt,
                'post_parent'    => $post->post_parent,
                'post_password'  => $post->post_password,
                'to_ping'        => $post->to_ping,
                'menu_order'     => $post->menu_order,
            );
            $new_post_id = wp_insert_post($new_post);
            if (is_wp_error($new_post_id)) {
                wp_die(__($new_post_id->get_error_message()));
            }
            $taxonomies = array_map('sanitize_text_field', get_object_taxonomies($post->post_type));
            if (!empty($taxonomies) && is_array($taxonomies)) {
                foreach ($taxonomies as $taxonomy) {
                    $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                    wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
                }
            }
            $post_meta_keys = get_post_custom_keys($post_id);
            if (!empty($post_meta_keys)) {
                foreach ($post_meta_keys as $meta_key) {
                    $meta_values = get_post_custom_values($meta_key, $post_id);

                    foreach ($meta_values as $meta_value) {
                        $meta_value = maybe_unserialize($meta_value);
                        update_post_meta($new_post_id, $meta_key, wp_slash($meta_value));
                    }
                }
            }
            if ($post->post_type == 'post') {
                wp_redirect(esc_url_raw(admin_url('post.php?action=edit')));
            } else {
				wp_redirect(esc_url_raw(admin_url('edit.php?post_type=page')));
            }
            exit;
        }
    }
}
add_action('admin_action_duplicate_post', 'foxtool_quick_duplicate_post_action');
}
# Xóa slug category cha khỏi đường dẫn
if(isset($foxtool_options['post-link1'])){
function foxtool_no_category_parents($url, $term, $taxonomy) {
    if ($taxonomy == 'category') {
        $term_nicename = $term->slug;
        $url = trailingslashit(get_option('home')) . user_trailingslashit($term_nicename, 'category');
        $url = str_replace('/category/', '/', $url); // Loại bỏ "/category" khỏi URL
    }
    return $url;
}
add_filter('term_link', 'foxtool_no_category_parents', 1000, 3);

function foxtool_no_category_parents_rewrite_rules($flash = false) {
    $terms = get_terms(array(
        'taxonomy' => 'category',
        'post_type' => 'post',
        'hide_empty' => false,
    ));
    if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $term_slug = $term->slug;
            $new_term_slug = str_replace('category/', '', $term_slug); // Loại bỏ "category/" khỏi slug
            add_rewrite_rule($new_term_slug . '/?$', 'index.php?category_name=' . $term_slug, 'top');
            add_rewrite_rule($new_term_slug . '/page/([0-9]{1,})/?$', 'index.php?category_name=' . $term_slug . '&paged=$matches[1]', 'top');
            add_rewrite_rule($new_term_slug . '/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?category_name=' . $term_slug . '&feed=$matches[1]', 'top');
        }
    }
    if ($flash == true)
        flush_rewrite_rules(false);
}
add_action('init', 'foxtool_no_category_parents_rewrite_rules');
// chuyen huong có category sang không có
function redirect_old_category_urls() {
    if (is_category() && strpos($_SERVER['REQUEST_URI'], '/category/') !== false) {
        $new_url = preg_replace('/\/category\//', '/', $_SERVER['REQUEST_URI'], 1);
        wp_redirect(home_url($new_url), 301); // 301 redirect for permanent move
        exit();
    }
}
add_action('template_redirect', 'redirect_old_category_urls');
function foxtool_new_category_edit_success() {
    foxtool_no_category_parents_rewrite_rules(true);
}
add_action('created_category', 'foxtool_new_category_edit_success');
add_action('edited_category', 'foxtool_new_category_edit_success');
add_action('delete_category', 'foxtool_new_category_edit_success');
}
# Xóa slug tag khỏi đường dẫn
if(isset($foxtool_options['post-link2'])){
function foxtool_post_tag_permalink( $url, $term, $taxonomy ){
    switch ($taxonomy):
        case 'post_tag':
            $taxonomy_slug = 'tag';
            if(strpos($url, $taxonomy_slug) === FALSE) break;
            $url = str_replace('/' . $taxonomy_slug, '', $url);
            break;
    endswitch;
    return $url;
}
add_filter( 'term_link', 'foxtool_post_tag_permalink', 10, 3 );
// rewrite rules
function foxtool_post_tag_rewrite_rules($flash = false) {
    $terms = get_terms( array(
        'taxonomy' => 'post_tag',
        'post_type' => 'post',
        'hide_empty' => false,
    ));
    if($terms && !is_wp_error($terms)){
        $siteurl = esc_url(home_url('/'));
        foreach ($terms as $term){
            $term_slug = $term->slug;
            $baseterm = str_replace($siteurl,'',get_term_link($term->term_id,'post_tag'));
            add_rewrite_rule($baseterm.'?$','index.php?tag='.$term_slug,'top');
            add_rewrite_rule($baseterm.'page/([0-9]{1,})/?$', 'index.php?tag='.$term_slug.'&paged=$matches[1]','top');
            add_rewrite_rule($baseterm.'(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?tag='.$term_slug.'&feed=$matches[1]','top');
        }
    }
    if ($flash == true)
        flush_rewrite_rules(false);
}
add_action('init', 'foxtool_post_tag_rewrite_rules');
// chuyen huong tag sang khong tag
function redirect_old_post_tag_urls() {
    if (is_tag() && strpos($_SERVER['REQUEST_URI'], '/tag/') !== false) {
        $new_url = preg_replace('/\/tag\//', '/', $_SERVER['REQUEST_URI'], 1);
        wp_redirect(home_url($new_url), 301); // 301 redirect for permanent move
        exit();
    }
}
add_action('template_redirect', 'redirect_old_post_tag_urls');
// sửa lỗi khi tạo mới tag bị 404
function foxtool_new_post_tag_edit_success( $term_id, $taxonomy ) {
    foxtool_post_tag_rewrite_rules(true);
}
add_action( 'created_post_tag', 'foxtool_new_post_tag_edit_success', 10, 2 );
}
# thêm .html cho page
if(isset($foxtool_options['post-html1'])){
function foxtool_change_page_permalink() {
    global $wp_rewrite;
    if ( strstr($wp_rewrite->get_page_permastruct(), '.html') != '.html' )
    $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
}
add_action('init', 'foxtool_change_page_permalink', -1);
}


# Thêm mô tả cho hình ảnh khi tải lên
if(isset($foxtool_options['post-alt1'])){
function foxtool_add_description_to_media($attachment_ID) {
    $post = get_post($attachment_ID);
    if ($post->post_type === 'attachment' && empty(get_post_meta($attachment_ID, '_wp_attachment_image_alt', true))) {
        $post_title = get_the_title($post->post_parent);
        update_post_meta($attachment_ID, '_wp_attachment_image_alt', $post_title);
    }
}
add_action('add_attachment', 'foxtool_add_description_to_media');
}
# thêm nofollow và _blank cho đường dẫn bên ngoài ở bài viết
if(isset($foxtool_options['post-out1'])){
function foxtool_target_blank_to_nofollow_and_external($text) {
    preg_match_all('/<a[^>]+>/i', $text, $matches);
    foreach ($matches[0] as $link) {
        if (strpos($link, 'href=') !== false) {
            preg_match('/href=("|\')([^"\']+)("|\')/i', $link, $hrefMatches);
            $url = isset($hrefMatches[2]) ? $hrefMatches[2] : '';
            if (filter_var($url, FILTER_VALIDATE_URL) && strpos($url, home_url()) === false) {
                preg_match_all('/([a-zA-Z\-]+)="([^"]*)"/', $link, $attributeMatches, PREG_SET_ORDER);
                $attributes = array();
                foreach ($attributeMatches as $attributeMatch) {
                    $attributes[$attributeMatch[1]] = $attributeMatch[0];
                }
                $attributes['rel'] = 'rel="nofollow noopener sponsored"';
                $attributes['target'] = 'target="_blank"';
                $modified_link = '<a ' . implode(' ', $attributes) . '>';
                $text = str_replace($link, $modified_link, $text);
            }
        }
    }
    return $text;
}
add_filter('the_content', 'foxtool_target_blank_to_nofollow_and_external', 13);
}
# su dung shortcode title
if(isset($foxtool_options['post-other1'])){
    add_filter( 'the_title', 'do_shortcode' );
}
# hien thị bai viet vua chinh sua dau tien
if(isset($foxtool_options['post-other2'])){
function foxtool_orderby_modified_posts( $query ) {
    if( $query->is_main_query() && !is_admin() ) {
	if ( $query->is_home() || $query->is_category() || $query->is_tag() ) {
            $query->set( 'orderby', 'modified' );
            $query->set( 'order', 'desc' );
	}
    }
}
add_action( 'pre_get_posts', 'foxtool_orderby_modified_posts' );
}
# ẩn các bài viết có id chuyen muc khỏi trang chu
if(isset($foxtool_options['post-hiden1'])){
function foxtool_categories_hiden_home($query){
	global $foxtool_options;
    if ($query->is_home() && $query->is_main_query() && !empty($foxtool_options['post-hiden11'])) {
        $id_cate = $foxtool_options['post-hiden11'];
        $id_cate_hiden = explode(',', $id_cate);
        $query->set('category__not_in', $id_cate_hiden);
    }
}
add_action('pre_get_posts', 'foxtool_categories_hiden_home');
}
# show ảnh trong bài viết hoặc trang
if (isset($foxtool_options['post-fancy1'])){
// add js css fancybox
function foxtool_enqueue_fancybox(){
	if (is_single()){
	wp_enqueue_script( 'fancybox', FOXTOOL_URL . '/link/fancybox/fancybox.js', array(), FOXTOOL_VERSION);
	wp_enqueue_style('fancybox', FOXTOOL_URL . '/link/fancybox/fancybox.css', array(), FOXTOOL_VERSION);
	}
}
add_action('wp_enqueue_scripts', 'foxtool_enqueue_fancybox');
// add script vao footer post
function foxtool_slide_script(){
	global $foxtool_options;
	if (is_single()){ 
		if(isset($foxtool_options['post-fancy11'])){
		?>
		<script>
		jQuery(document).ready(function(){
			jQuery(".fancybox img").each(function(){
				var a;
				if (jQuery(this).attr("data-src")) {
					// Nếu có thuộc tính data-src, sử dụng giá trị của data-src
					a = jQuery(this).attr("data-src");
				} else {
					// Nếu không có thuộc tính data-src, sử dụng giá trị của src
					a = jQuery(this).attr("src");
				}
				jQuery(this).wrap('<a data-src="'+a+'" data-fancybox="gallery"></a>');
			});
			Fancybox.bind('[data-fancybox]', {});
		});
		</script>
		<?php } else {echo '<script>jQuery(document).ready(function(){Fancybox.bind(".fancybox img");});</script>';}
	}
}
add_action('wp_footer', 'foxtool_slide_script');
// add div vao content
function foxtool_slide_addiv( $content ) {
    return '<div class="fancybox">'. $content .'</div>';
}
add_filter( 'the_content', 'foxtool_slide_addiv' );
}










