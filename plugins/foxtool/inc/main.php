<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options;
# add font Awesome
function foxtool_fontawe_assets(){
	global $foxtool_options;
	if (isset($foxtool_options['main-add1'])){
	wp_enqueue_style( 'foxtool-icon', FOXTOOL_URL . 'font/css/all.css', array(), FOXTOOL_VERSION);
	}
}
add_action('wp_enqueue_scripts', 'foxtool_fontawe_assets');
# add font Google
function foxtool_Google_font(){
global $foxtool_options;
ob_start();
if(!empty($foxtool_options['main-font1'])){ 
$font = $foxtool_options['main-font1'];
} else {
$font =	'Default';
}
$fontFamilies = [
    'Default' => '',
    'Arial' => 'Arial, Helvetica, sans-serif',
    'Oswald' => "'Oswald', sans-serif",
    'Nunito' => "'Nunito', sans-serif",
    'Josefin Sans' => "'Josefin Sans', sans-serif",
    'Montserrat' => "'Montserrat', sans-serif",
    'Roboto Condensed' => "'Roboto Condensed', sans-serif",
    'Open Sans' => "'Open Sans', sans-serif",
    'Raleway' => "'Raleway', sans-serif",
    'Playfair Display' => "'Playfair Display', sans-serif",
    'Inter' => "'Inter', sans-serif",
    'Lora' => "'Lora', sans-serif",
    'Quicksand' => "'Quicksand', sans-serif",
    'Kanit' => "'Kanit', sans-serif",
    'Comfortaa' => "'Comfortaa', sans-serif",
    'Prompt' => "'Prompt', sans-serif",
    'IBM Plex Serif' => "'IBM Plex Serif', sans-serif",
    'Spectral' => "'Spectral', sans-serif",
    'Philosopher' => "'Philosopher', sans-serif",
    'Taviraj' => "'Taviraj', sans-serif",
    'Readex Pro' => "'Readex Pro', sans-serif",
    'Anybody' => "'Anybody', sans-serif",
];
$fontLinks = [
    'Oswald' => 'https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap',
    'Nunito' => 'https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
    'Josefin Sans' => 'https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap',
    'Montserrat' => 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
    'Roboto Condensed' => 'https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap',
    'Open Sans' => 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap',
    'Raleway' => 'https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap',
    'Playfair Display' => 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
    'Inter' => 'https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap',
    'Lora' => 'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap',
    'Quicksand' => 'https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap',
    'Kanit' => 'https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
    'Comfortaa' => 'https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap',
    'Prompt' => 'https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
    'IBM Plex Serif' => 'https://fonts.googleapis.com/css2?family=IBM+Plex+Serif:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap',
    'Spectral' => 'https://fonts.googleapis.com/css2?family=Spectral:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap',
    'Philosopher' => 'https://fonts.googleapis.com/css2?family=Philosopher:ital,wght@0,400;0,700;1,400;1,700&display=swap',
    'Taviraj' => 'https://fonts.googleapis.com/css2?family=Taviraj:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
    'Readex Pro' => 'https://fonts.googleapis.com/css2?family=Readex+Pro:wght@200;300;400;500;600;700&display=swap',
    'Anybody' => 'https://fonts.googleapis.com/css2?family=Anybody:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
];
$fontFamily = isset($fontFamilies[$font]) ? $fontFamilies[$font] : '';
$fontLink = isset($fontLinks[$font]) ? $fontLinks[$font] : '';
if ($font !== 'Default') {
    if ($fontLink !== '') {
        ?>
        <script>
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = '<?php echo $fontLink; ?>';
            document.head.appendChild(link);
        </script>
        <?php
    }
    if ($fontFamily !== '') {
        ?>
        <style>
		body, body button, body input, body textarea, body select, body h1, body h2, body h3, body h4, body h5, body h6, body div, body span, body a, body p{ 
			font-family: <?php echo $fontFamily; ?> !important; 
		}
		</style>
        <?php
    }
}
echo ob_get_clean();    
}
add_action('wp_footer', 'foxtool_Google_font');
# them hieu ung cho trang web như noel
function foxtool_add_hover_style(){
	global $foxtool_options;
	if(isset($foxtool_options['main-hover1']) && $foxtool_options['main-hover1'] == 'Snow1'){
	wp_enqueue_script( 'hover', FOXTOOL_URL . 'link/hover/hover-style-1.js', array(), FOXTOOL_VERSION);
	}
	
	if(isset($foxtool_options['main-hover1']) && $foxtool_options['main-hover1'] == 'Snow2'){
	wp_enqueue_script( 'hover', FOXTOOL_URL . 'link/hover/hover-style-2.js', array(), FOXTOOL_VERSION);
	}
	
	if(isset($foxtool_options['main-hover1']) && $foxtool_options['main-hover1'] == 'Lunar1'){
	wp_enqueue_script( 'hover', FOXTOOL_URL . 'link/hover/hover-style-lunar-1.js', array(), FOXTOOL_VERSION);
	}
	
	if(isset($foxtool_options['main-hover1']) && $foxtool_options['main-hover1'] == 'Lunar2'){
	wp_enqueue_script( 'hover', FOXTOOL_URL . 'link/hover/hover-style-lunar-2.js', array(), FOXTOOL_VERSION);
	}
} 
add_action('wp_footer', 'foxtool_add_hover_style');
# che do darkmode
if (isset($foxtool_options['main-mode1'])){
function foxtool_darkmode_assets() {
	global $foxtool_options;
	// cau hinh vi tri
	$here = isset($foxtool_options['main-mode11']) && $foxtool_options['main-mode11'] == 'Right' ? 'right' : 'left';
	if(!empty($foxtool_options['main-mode12']) && $foxtool_options['main-mode12'] < 300){
		$bot = $foxtool_options['main-mode12']. 'px';
	} elseif (!empty($foxtool_options['main-mode12']) && $foxtool_options['main-mode12'] >= 300){
		$bot = '50%';
	} else {
		$bot = '30px';
	}
	$lef = !empty($foxtool_options['main-mode13']) ? $foxtool_options['main-mode13']. 'px' : '30px';
	
	if(isset($foxtool_options['main-mode10']) && $foxtool_options['main-mode10'] == 'Dark2'){
	wp_enqueue_script('Darkmode1', FOXTOOL_URL . 'link/darkmode/darkmode1.js', array(), FOXTOOL_VERSION, true);
	wp_enqueue_script('Darkmode2', FOXTOOL_URL . 'link/darkmode/darkmode2.js', array(), FOXTOOL_VERSION, true);
    ?>
	<style id='darkmodetg-inline-css'>
	html{opacity:1}html.dmtg-fade{opacity:0;background:#282828}.darkmode--activated embed,.darkmode--activated iframe,.darkmode--activated img,.darkmode--activated video{filter:invert(100%)}.darkmode--activated embed:fullscreen,.darkmode--activated iframe:fullscreen,.darkmode--activated video:fullscreen{filter:invert(0%)}.darkmode--activated [style*="background-image: url"],.darkmode--activated [style*="background-image:url"]{filter:invert(100%)}.darkmode--activated .wp-block-cover[style*="background-image: url"] .wp-block-cover[style*="background-image: url"],.darkmode--activated .wp-block-cover[style*="background-image: url"] .wp-block-cover[style*="background-image:url"],.darkmode--activated .wp-block-cover[style*="background-image: url"] embed,.darkmode--activated .wp-block-cover[style*="background-image: url"] figure[class*=wp-duotone-],.darkmode--activated .wp-block-cover[style*="background-image: url"] iframe,.darkmode--activated .wp-block-cover[style*="background-image: url"] img,.darkmode--activated .wp-block-cover[style*="background-image: url"] video,.darkmode--activated .wp-block-cover[style*="background-image:url"] .wp-block-cover[style*="background-image: url"],.darkmode--activated .wp-block-cover[style*="background-image:url"] .wp-block-cover[style*="background-image:url"],.darkmode--activated .wp-block-cover[style*="background-image:url"] embed,.darkmode--activated .wp-block-cover[style*="background-image:url"] figure[class*=wp-duotone-],.darkmode--activated .wp-block-cover[style*="background-image:url"] iframe,.darkmode--activated .wp-block-cover[style*="background-image:url"] img,.darkmode--activated .wp-block-cover[style*="background-image:url"] video{filter:invert(0)}.darkmode--activated figure[class*=wp-duotone-]{filter:invert(1)}body.custom-background.darkmode--activated .darkmode-background{background:#fff;mix-blend-mode:difference}.darkmode--activated .dmt-filter-1{filter:invert(1)!important}.darkmode--activated .dmt-filter-0{filter:invert(0)!important}
	</style>
    <script>
        var darkmodetg = {
            "config": {
                "bottom": "<?php echo $bot; ?>",
                "<?php echo $here; ?>": "<?php echo $lef; ?>",
                "width": "40px",
                "height": "40px",
                "borderRadius": "44px",
                "fontSize": "16px",
                "time": "0.3s",
                "backgroundColor": "transparent",
                "buttonColorDark": "#333333",
                "buttonColorLight": "#fff",
                "buttonColorTDark": "#ffffff",
                "buttonColorTLight": "#000000",
                "saveInCookies": "1",
                "fixFlick": "1",
                "label": "<img src='<?php echo FOXTOOL_URL . 'img/darkmode.svg'; ?>' />",
                "autoMatchOsTheme": false,
                "buttonAriaLabel": "<?php _e('Dark Mode', 'foxtool'); ?>",
                "overrideStyles": ""
            }
        };
    </script>
    <?php
	} else {
	wp_enqueue_script('Darkmode1', FOXTOOL_URL . 'link/darkmode/darkmode1.min.js', array(), FOXTOOL_VERSION, true);
	wp_enqueue_script('Darkmode2', FOXTOOL_URL . 'link/darkmode/darkmode2.min.js', array(), FOXTOOL_VERSION, true);
	?>
	<div class="wp-dark-mode-switcher ft-darkmode">
		<img alt="Dark mode" src='<?php echo FOXTOOL_URL . 'img/darkmode.svg'; ?>' />
    </div>
	<style>
		.ft-darkmode{
			position: fixed;
			z-index: 10150;
			<?php  echo$here; ?>: <?php echo $lef; ?>;
			bottom: <?php echo $bot; ?>;
			width:40px;
			height:40px;
			background:#444;
			border-radius:100%;
		}
		.ft-darkmode:hover{opacity:0.6;}
		.ft-darkmode img{
			width:100%;
		}
	</style>
    <script>
	var wpDarkMode = {
    "config": {"brightness": 100, "contrast": 90, "sepia": 10},
	"enable_preset": "",
    "customize_colors": "",
    "colors": {"bg": "#000", "text": "#dfdedb", "link": "#e58c17"},
    "enable_frontend": "1",
    "enable_backend": "",
    "enable_os_mode": "1",
    "excludes": "rs-fullwidth-wrap, .mejs-container, ._channels-container",
    "includes": "",
    "is_excluded": "",
    "remember_darkmode": "",
    "default_mode": "",
    "keyboard_shortcut": "1",
    "url_parameter": "",
    "images": "",
    "videos": "",
    "is_pro_active": "",
    "is_ultimate_active": "",
    "pro_version": "0",
    "is_elementor_editor": "",
    "is_block_editor": "",
	"frontend_mode": "", 
	};
	</script>
    <?php
	}
}
add_action('wp_footer', 'foxtool_darkmode_assets');
}
# tao api custom post type post and product
if (isset($foxtool_options['main-search1'])){
// xóa post khoi json neu xoa
function foxtool_delete_search_auto_when_delete_post($post_id) {
    $upload_dir = wp_upload_dir();
    $file_path = $upload_dir['basedir'] . '/json/data-search.json';
    $existing_data = array();
    if (file_exists($file_path)) {
        $existing_data = json_decode(file_get_contents($file_path), true);
        foreach ($existing_data as $key => $item) {
            if ($item['ID'] == $post_id) {
                unset($existing_data[$key]);
                break; 
            }
        }
        // Reset array keys
        $existing_data = array_values($existing_data);
        file_put_contents($file_path, json_encode($existing_data));
    }
}
add_action('delete_post', 'foxtool_delete_search_auto_when_delete_post');
// them post vào json
function foxtool_add_search_auto_whenpublish($post_id ) {
        global $foxtool_options;
        $post = get_post($post_id);
        $type = get_post_type($post->ID);
        if (isset($foxtool_options['main-search-posttype'])) {
            if(count($foxtool_options['main-search-posttype'])>0){
                $allowed_post_types = $foxtool_options['main-search-posttype'];
                if (in_array($type, $allowed_post_types)) {
                    $filed = array(
                    'ID',
                    'title',
                    'url',
                    'thum',
					'price',
                    'taxonomy'
                );
                    $item = array('type' => $type);
                    foreach ($filed as $field) {
                        switch ($field) {
                            case 'ID':
                                $item[$field] = $post->ID;
                            break;
                            case 'title':
                                $item[$field] = get_the_title($post->ID);
                                break;
                            case 'url':
                                $item[$field] = get_permalink($post->ID);
                                break;
                            case 'thum':
                                $item[$field] = get_the_post_thumbnail_url($post->ID);
                                break;
                            case 'price':
                                if ($type === 'product') {
                                    if (function_exists('wc_get_product')) {
                                        $product = wc_get_product($post->ID);
                                        $item[$field] = wc_price($product->get_price());
                                    }
                                }
                                break;
                            case 'taxonomy':
                                if ($post->post_type == 'product') {
                                    $taxonomy_terms = wp_get_post_terms($post->ID, 'product_cat');
                                    if ($taxonomy_terms && !is_wp_error($taxonomy_terms)) {
                                        $first_term = reset($taxonomy_terms);
                                        $item[$field] = $first_term->name;
                                    }
                                } else {
                                    $object_taxonomies = get_object_taxonomies($post->post_type);
                                    foreach ($object_taxonomies as $taxonomy_name) {
                                        $taxonomy_terms = get_the_terms($post->ID, $taxonomy_name);
                                        if ($taxonomy_terms && !is_wp_error($taxonomy_terms)) {
                                            $first_term = reset($taxonomy_terms);
                                            $item[$field] = $first_term->name;
                                            break;
                                        }
                                    }
                                }
                                break;
                        }
                    }
                    $newitem[$post->ID] = $item;
                    $upload_dir = wp_upload_dir();
                    $json_dir = $upload_dir['basedir'] . '/json';
                    if (!is_dir($json_dir)) {
                        mkdir($json_dir);
                    }
                    $file_path = $json_dir . '/data-search.json';
                    $existing_data = array();
                    if (file_exists($file_path)) {
                        $existing_data = json_decode(file_get_contents($file_path), true);
                    }
                    $merged_data = foxtool_merged_array($existing_data,$newitem);
                    file_put_contents($file_path, json_encode($merged_data));
                }
            }
        }
}
// Thêm hook cho từng loại post type
if(isset($foxtool_options['main-search-posttype'])){
	$main_search_post_types = $foxtool_options['main-search-posttype'];
	foreach ($main_search_post_types as $post_type) {
		$hook_name = 'publish_' . $post_type;
		add_action($hook_name, 'foxtool_add_search_auto_whenpublish');
	}
}
// lay name tu custom post type
function foxtool_post_type_name($post_type_slug) {
    $post_type_object = get_post_type_object($post_type_slug);
    if ($post_type_object) {
        $post_type_name = $post_type_object->labels->singular_name;
        return $post_type_name; 
    } 
}
// tao mang json
function foxtool_search($page = 1, $posts_per_page = 2000) {
    global $foxtool_options;
    if (isset($foxtool_options['main-search-posttype'])) {
        if(count($foxtool_options['main-search-posttype'])>0){
            foreach ($foxtool_options['main-search-posttype'] as $key => $type) {
                $post_types[$type] = array(
                        'type' => $type,
                        'fields' => array(
                            'ID',
                            'title',
                            'url',
                            'thum',
                            'price',
                            'taxonomy'
                        )
                );
            }
        }  
    } 
    $args = array(
        'numberposts' => $posts_per_page,
        'offset'      => ($page - 1) * $posts_per_page,
        'post_type'   => array_keys($post_types),
    );
    $posts = get_posts($args);
    $results = array();
    foreach ($posts as $post) {
        $post_type = $post->post_type;
        if (isset($post_types[$post_type])) {
            $type_info = $post_types[$post_type];
            $type = $type_info['type'];
            $item = array('type' => $type);
            foreach ($type_info['fields'] as $field) {
                switch ($field) {
                    case 'ID':
                        $item[$field] = $post->ID;
                        break;
                    case 'title':
                        $item[$field] = $post->post_title;
                        break;
                    case 'url':
                        $item[$field] = get_permalink($post->ID);
                        break;
                    case 'thum':
                        $item[$field] = get_the_post_thumbnail_url($post->ID);
                        break;
                    case 'price':
                        if ($type === 'product') {
                            if (function_exists('wc_get_product')) {
                                $product = wc_get_product($post->ID);
                                $item[$field] = wc_price($product->get_price());
                            }
                        }
                        break;
                    case 'taxonomy':
                        if ($post->post_type == 'product') {
                            $taxonomy_terms = wp_get_post_terms($post->ID, 'product_cat');
                            if ($taxonomy_terms && !is_wp_error($taxonomy_terms)) {
                                $first_term = reset($taxonomy_terms);
                                $item[$field] = $first_term->name;
                            }
                        } else {
                            $object_taxonomies = get_object_taxonomies($post->post_type);
                            foreach ($object_taxonomies as $taxonomy_name) {
                                $taxonomy_terms = get_the_terms($post->ID, $taxonomy_name);
                                if ($taxonomy_terms && !is_wp_error($taxonomy_terms)) {
                                    $first_term = reset($taxonomy_terms);
                                    $item[$field] = $first_term->name;
                                    break;
                                }
                            }
                        }
                        break;
                }
            }
            $results[$post->ID] = $item;
        }
    }
    return $results;
}
// ajax tao file json 
function foxtool_json_file_callback(){
	global $foxtool_options;
    check_ajax_referer('foxtool_search_get', 'security');
	if (!current_user_can('manage_options')){
        wp_die(__('Insufficient permissions', 'foxtool'));
    }
    $page = $_POST['page'];
    $data =  foxtool_search($page);
    if (empty($data)) {
        echo json_encode(array('page' => -1));
        wp_die();
    }
    $upload_dir = wp_upload_dir();
    $json_dir = $upload_dir['basedir'] . '/json';
    if (!is_dir($json_dir)) {
        mkdir($json_dir);
    }
    $file_path = $json_dir . '/data-search.json';
    $existing_data = array();
    if (file_exists($file_path)) {
        $existing_data = json_decode(file_get_contents($file_path), true);
    }
    // Xóa các custom post type không tồn tại trong main-search-posttype
    if (isset($foxtool_options['main-search-posttype']) && count($foxtool_options['main-search-posttype']) > 0) {
        $allowed_post_types = $foxtool_options['main-search-posttype'];
        foreach ($existing_data as $key => $item) {
            if (!in_array($item['type'], $allowed_post_types)) {
                unset($existing_data[$key]);
            }
        }
        $existing_data = array_values($existing_data);
    }
    $merged_data = foxtool_merged_array($existing_data, $data);
    file_put_contents($file_path, json_encode($merged_data));
    $count = count($merged_data);
    echo json_encode(array('page' =>$page+1,'count'=>$count));   
    wp_die();
}
add_action('wp_ajax_foxtool_json_get', 'foxtool_json_file_callback');
// ajax xoa thư mục json
function foxtool_delete_json_folder_callback() {
    check_ajax_referer('foxtool_search_del', 'security');
    if (!current_user_can('manage_options')){
        wp_die(__('Insufficient permissions', 'foxtool'));
    }
    $upload_dir = wp_upload_dir();
    $json_dir = $upload_dir['basedir'] . '/json';
    if (is_dir($json_dir)) {
        $files = glob("$json_dir/*");
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        rmdir($json_dir);
    }
    wp_die();
}
add_action('wp_ajax_foxtool_json_del', 'foxtool_delete_json_folder_callback');
// xu ly du lieu json
function foxtool_merged_array($existing_data, $data) {
    $merged_data = $existing_data;
    foreach ($data as $new_item) {
        $found = false;
        foreach ($merged_data as &$existing_item) {
            if ($existing_item['ID'] == $new_item['ID']) {
                $existing_item = $new_item;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $merged_data[] = $new_item;
        }
    }
    return array_values($merged_data);
}
// duong dan toi json trong plugin
function foxtool_search_url(){
    $upload_dir = wp_upload_dir();
    $json_dir = $upload_dir['basedir'] . '/json';
    $json_file = $json_dir . '/data-search.json';
    if (file_exists($json_file)) {
        $absolute_url = $upload_dir['baseurl'] . '/json/data-search.json';
        $relative_url = wp_make_link_relative($absolute_url);
        return $relative_url;
    } 
}
// dua vao website
function foxtool_search_footer(){ 
	global $foxtool_options;
	$limit = !empty($foxtool_options['main-search-c1']) ? $foxtool_options['main-search-c1'] : 10;
	?>
	<div class="ft-search" id="ft-search" style="display:none">
		<div class="ft-sbox">
			<span id="ft-sclose" onclick="ftnone(event, 'ft-search')">&#215;</span>
			<form class="ft-sform" action="<?php bloginfo('url'); ?>">
			<?php 
            if (isset($foxtool_options['main-search-posttype']) && in_array('product', $foxtool_options['main-search-posttype'])) {
				echo '<input type="hidden" name="post_type" value="product">';
			}
			?>
			<input type="text" id="ft-sinput" placeholder="<?php _e('Enter keywords to search', 'foxtool'); ?>" name="s" value="" maxlength="50" required="required">
			<button id="ft-ssumit" type="submit"><?php _e('SEARCH', 'foxtool'); ?></button>
			</form>
			<ul id="ft-show"></ul>
		</div>
	</div>
	<script>
	jQuery(document).ready(function($){
        $('input[name="s"]').on('input', function() {
			var searchText = $(this).val(); 
			$("#ft-search").css("display", "block"); 
			$('#ft-sinput').val(searchText); 
			$('#ft-sinput').trigger('keyup');
			if ($('.mfp-close').length > 0) {
			  $('.mfp-close').click();
			}
			$("#ft-sinput").focus();
		});
		$('#ft-sinput').on('input', function() {
			var searchText = $(this).val();
			$('input[name="s"]').val(searchText); 
			$(this).trigger('keyup');
		});
		var debounceTimer;
		$('#ft-sinput').on('keyup', function(){
			var searchText = $(this).val();
			clearTimeout(debounceTimer);
			debounceTimer = setTimeout(function() {
				if(searchText.length >= 1) {
					fetch('<?php echo foxtool_search_url(); ?>?search=' + searchText)
					.then(response => response.json())
					.then(data => {
						displayResults(data, searchText);
					})
					.catch(error => {
						console.error('Error fetching data:', error);
					});
				} else {
					$('#ft-show').empty(); 
					$('#ft-show').removeClass('ft-showbg');
				}
			}, 100); 
		});
		function removeDiacritics(str) {
			return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
		}
		function displayResults(data, searchText) {
			$('#ft-show').empty();
			var hasResults = false;
			<?php 
			if(isset($foxtool_options['main-search-posttype'])){
				$main_search_post_types = $foxtool_options['main-search-posttype'];
				foreach ($main_search_post_types as $id) {
					echo "var ". $id ."Results = '';var ". $id ."Count = 0;";
				}
			}
			?>
			var postLimit = <?php echo $limit; ?>; 
			if (data && data.length > 0) {
				$('#ft-show').addClass('ft-showbg');
				$.each(data, function (index, item) {
					var title = item.title;
					var normalizedTitle = removeDiacritics(title);
					var normalizedSearchText = removeDiacritics(searchText.toLowerCase());
					var regex = new RegExp(normalizedSearchText.replace(/\s+/g, '.*'), 'i');
					if (regex.test(normalizedTitle)) {
						var textmau = highlightSearchText(title, searchText);
						var type = item.type;
						var url = item.url;
						var thum = item.thum;
						var pri = item.price;
						var taxo = item.taxonomy;
						var itemHTML;
						if (!pri) {
							pri = "";
						}
						if (!taxo) {
							taxo = "";
						}
						if (thum) {
							itemHTML = '<li class="ft-ssp"><a href="' + url + '"><img src="' + thum + '"></a><a href="' + url + '"><span class="ft-ssap-tit">' + textmau + '</span><span class="ft-ssap-cm">'+ taxo +'</span><span class="ft-ssap-pri">' + pri + '</span></a></li>';
						} else {
							itemHTML = '<li class="ft-sspno"><a href="' + url + '"><span class="ft-ssap-tit">' + textmau + '</span><span class="ft-ssap-cm">'+ taxo +'</span><span class="ft-ssap-pri">' + pri + '</span></a></li>';
						}
						<?php 
						if(isset($foxtool_options['main-search-posttype'])){
							$main_search_post_types = $foxtool_options['main-search-posttype'];
							$firstCondition = true;
							foreach ($main_search_post_types as $id) {
								if($firstCondition) {
									echo "if (type === '". $id ."' && ". $id ."Count < postLimit) {
										". $id ."Results += itemHTML;
										". $id ."Count++;
										hasResults = true;
									}";
									$firstCondition = false;
								} else {
									echo "else if (type === '". $id ."' && ". $id ."Count < postLimit) {
										". $id ."Results += itemHTML;
										". $id ."Count++;
										hasResults = true;
									}";
								}
							}
						}
						?>
					}
				});
			}
			<?php 
			if(isset($foxtool_options['main-search-posttype'])){
				$main_search_post_types = $foxtool_options['main-search-posttype'];
				if (in_array('product', $main_search_post_types)) {
					unset($main_search_post_types[array_search('product', $main_search_post_types)]);
					array_unshift($main_search_post_types, 'product');
				}
				foreach ($main_search_post_types as $id) {
					echo 'if ('. $id .'Results){$(\'#ft-show\').append(\'<li class="ft-stit">'. foxtool_post_type_name($id) .'</li>\' + '. $id .'Results);}';
				}
			}
			?>
			if (!hasResults) {
				$('#ft-show').append('<li><?php _e("No results found", "foxtool"); ?></li>');
			}
		}
		function highlightSearchText(text, searchText){
			var regex = new RegExp(searchText.replace(/\s+/g, '|'), 'gi'); 
			return text.replace(regex, function(match){
				return '<span class="ft-sselec">' + match + '</span>';
			});
		}
	});
	</script>
	<?php if(!empty($foxtool_options['main-search-c2']) || !empty($foxtool_options['main-search-c3']) || !empty($foxtool_options['main-search-c4'])) { 
	$box = !empty($foxtool_options['main-search-c2']) ? '--bgboxcolor:'. $foxtool_options['main-search-c2'] .';' : NULL;
	$bor = !empty($foxtool_options['main-search-c3']) ? '--bgborcolor:'. $foxtool_options['main-search-c3'] .';' : NULL;
	$tex = !empty($foxtool_options['main-search-c4']) ? '--bgtexcolor:'. $foxtool_options['main-search-c4'] .';' : NULL;
	echo '<style>:root{'. $box . $bor . $tex .'}</style>';
	}	
}
add_action('wp_footer', 'foxtool_search_footer');
// add css js search web
function foxtool_enqueue_search(){
	wp_enqueue_style('search-css', FOXTOOL_URL . 'link/search/search.css', array(), FOXTOOL_VERSION);
}
add_action('wp_enqueue_scripts', 'foxtool_enqueue_search');
}


