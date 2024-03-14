<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// init process for registering our button
add_action('init', 'ccpuz_wpse72394_shortcode_button_init');

function ccpuz_wpse72394_shortcode_button_init() {
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log(">ccpuz_wpse72394_shortcode_button_init");
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
        return;
    add_filter("mce_external_plugins", "ccpuz_wpse72394_register_tinymce_plugin", 99);

    add_action('admin_print_scripts', 'my_admin_header_scripts');

    add_filter('mce_buttons', 'ccpuz_wpse72394_add_tinymce_button');
}

if (defined('CCPUZ_DEBUG'))
    ccpuz_log("<ccpuz_wpse72394_shortcode_button_init");

//This callback registers our plug-in
function ccpuz_wpse72394_register_tinymce_plugin($plugin_array) {
    global $post;
    if ($post == NULL)
        return $plugin_array;

    $plugin_array['ccpuz_wpse72394_button'] = plugins_url('/shortcode.js', __FILE__);
    return $plugin_array;
}

function my_admin_header_scripts() {
    global $post;
    if ($post == NULL)
        return;

    echo '<script type="text/javascript">var ccpuz_wpse72394_button_ajax_url = "' . admin_url('admin-ajax.php') . '"; var ccpuz_post_id = "' . $post->ID . '"</script>';
}

//This callback adds our button to the toolbar
function ccpuz_wpse72394_add_tinymce_button($buttons) {
    //Add the button ID to the $button array
    $buttons[] = "ccpuz_wpse72394_button";
    return $buttons;
}

add_filter('wp_head', 'ccpuz_add_cf');

function ccpuz_add_cf($content) {
    global $post;

    if (is_single() || is_page()) {
//	echo '
//
//	';
    } else {
        return $content;
    }
}

// Get form
add_action('wp_ajax_ccpuz_get_crossword_mce_from', 'ccpuz_get_crossword_mce_from');

function ccpuz_get_crossword_mce_from() {
    load_template(__DIR__ . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR . "add_crossword.php");
}

// save form
add_action('wp_ajax_ccpuz_save_crossword_mce_from', 'ccpuz_save_crossword_mce_from');

function ccpuz_save_crossword_mce_from() {
    global $current_user;
    $post_id = isset($_POST['post_id']) ? $_POST['post_id'] : 0;
    $post_type = get_post_type($post_id);
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log(">ccpuz_save_crossword_mce_from id = $post_id, post_type = $post_type");

    $should_be = 0;
    if (substr_count(get_post($post_id)->post_content, '[crossword]') > 0) {
        $should_be = 1;
    }

    if (defined('CCPUZ_DEBUG'))
        ccpuz_log("1.ccpuz_save_crossword_mce_from id = $post_id, post_type = $post_type, should_be = $should_be");

    if (isset($_POST['crossword_method'])) {
        update_post_meta($post_id, 'crossword_method', $_POST['crossword_method']);
    }

    if (isset($_POST['crossword_method']) && $_POST['crossword_method'] == 'url') {
        $url = isset($_POST['ccpuz_url_upload_field']) ? filter_var($_POST['ccpuz_url_upload_field'], FILTER_SANITIZE_URL) : '';
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            if (preg_match('#(crossword|crossword\-puzzle|crosswordpuzzle|crucigrama|cruciverb|karebulmaca|kruiswoordraadsel|motscroise|palavrascruzadas|pussel).info#', $_POST['ccpuz_url_upload_field'])) {
                //var_dump(file_get_contents( $_POST['ccpuz_file_upload_field'] ));
                $response = wp_remote_get($_POST['ccpuz_url_upload_field']);
                $file_source = wp_remote_retrieve_body($response);
                if (defined('CCPUZ_DEBUG'))
                    ccpuz_log("2.ccpuz_save_crossword_mce_from id = $post_id, size = " . strlen($file_source) . "\n" . $_POST['ccpuz_url_upload_field']);

                preg_match('/src="[^"]+"/i', $file_source, $arr);

                $res_command = get_string_between($file_source, '$(function(){', '})');

                $id = get_string_between($res_command, '$("', '")');
                $res_command = str_replace($id, '#CrosswordCompilerPuz', $res_command);
                $res_command = preg_replace('/$\("#[^"]+"\)/i', '$("#SSS")', $res_command);
                $res_command = preg_replace('/ROOTIMAGES: "[^"]+"/i', 'ROOTIMAGES: ""', $res_command);
                $res_command = preg_replace('/PROGRESS:[^,]+"/i', '', $res_command);

                //var_Dump( $res_command );

                $res_command = str_replace('ROOTIMAGES: "', 'ROOTIMAGES: "' . plugins_url('inc/CrosswordCompilerApp/CrosswordImages/', __FILE__), $res_command);
                $res_command = str_replace('\\', '\\\\', $res_command);

                foreach ($arr as $single) {
                    if (substr_count($single, '_xml.js') > 0) {
                        $js_url = 'http://crossword.info' . str_replace('src=', '', str_replace('"', '', $single));
                        $upload_dir = wp_upload_dir();
                        $filename = sanitize_file_name(basename($js_url));

                        if (wp_mkdir_p($upload_dir['path'])) {
                            $file = $upload_dir['path'] . '/' . $filename;
                            $url = $upload_dir['url'] . '/' . $filename;
                        } else {
                            $file = $upload_dir['basedir'] . '/' . $filename;
                            $url = $upload_dir['baseurl'] . '/' . $filename;
                        }

                        $response = wp_remote_get($js_url);
                        $image_data = wp_remote_retrieve_body($response);
                        if (defined('CCPUZ_DEBUG'))
                            ccpuz_log("3.ccpuz_save_crossword_mce_from id = $post_id, size = " . strlen($image_data) . "\n" . $js_url . "\n" . $file);
                        @file_put_contents($file, $image_data);
                    }
                }
            }

            if ($response == null) {
                if (defined('CCPUZ_DEBUG'))
                    ccpuz_log("<ccpuz_save_crossword_mce_from ERROR #1");
                echo 'Oops, something wrong: must give full crossword.info puzzle URL.', 'Error: Something went wrong.';
                exit;
            }

            if (!isset($url) && !get_post_meta($post_id, 'ccpuz_url_upload_field', true) && $should_be == 1) {
                if (defined('CCPUZ_DEBUG'))
                    ccpuz_log("<ccpuz_save_crossword_mce_from ERROR #2");
                echo 'Oops, something wrong: must give full crossword.info puzzle URL.', 'Error: Something went wrong.';
                exit;
            }

            ### adding custom fields
            $str_parent = ' jQuery(".entry-content").attr( "class", "entry-content puzzle"); ';
            ccpuz_applet();
            update_post_meta($post_id, 'ccpuz_js_url', $url);
            update_post_meta($post_id, 'ccpuz_js_run', '<script>jQuery(document).ready(function($) { ' . $str_parent . ' ' . $res_command . ' });</script>');

            update_post_meta($post_id, 'ccpuz_url_upload_field', $_POST['ccpuz_url_upload_field']);
        } else {
            if (defined('CCPUZ_DEBUG'))
                ccpuz_log("<ccpuz_save_crossword_mce_from ERROR #3");
            echo 'Oops, Please input url! ', 'Error: Something went wrong.';
            exit;
        }
    }

    if (isset($_POST['crossword_method']) && $_POST['crossword_method'] == 'local') {
        if (defined('CCPUZ_DEBUG'))
            ccpuz_log("4.ccpuz_save_crossword_mce_from post_id = $post_id\n" . print_r($_FILES, true));

        if ($_FILES["ccpuz_html_file"]["tmp_name"]) {
            $file_source = file_get_contents($_FILES["ccpuz_html_file"]["tmp_name"]);
            $res_command = get_string_between($file_source, '$(function(){', '})');
            $res_command = preg_replace('/ROOTIMAGES: "[^"]+"/i', 'ROOTIMAGES: ""', $res_command);
            $res_command = str_replace('ROOTIMAGES: "', 'ROOTIMAGES: "' . plugins_url('inc/CrosswordCompilerApp/CrosswordImages/', __FILE__), $res_command);
            $str_parent = ' $(".entry-content").attr( "class", "entry-content puzzle"); ';
            if (!$res_command) {
                echo 'Oops, Something wrong with html file.', 'Error: Something wrong with puzzle html file.';
                exit;
            }
            update_post_meta($post_id, 'ccpuz_js_run', '<script>jQuery(document).ready(function($) { ' . $str_parent . ' ' . $res_command . ' });</script>');
            if (defined('CCPUZ_DEBUG'))
                ccpuz_log("5.ccpuz_save_crossword_mce_from post_id = $post_id\n" . '<script>jQuery(document).ready(function($) { ' . $str_parent . ' ' . $res_command . ' });</script>');
        } else {

            if (get_post_meta($post_id, 'ccpuz_js_run', true) == '' && $should_be == 1) {
                if (defined('CCPUZ_DEBUG'))
                    ccpuz_log("<ccpuz_save_crossword_mce_from ERROR #4");

                echo 'Oops, Add HTML file exported by Crossword Compiler.', 'Error: Something went wrong.';
                exit;
            }
        }
        if ($_FILES["ccpuz_js_file"]["name"]) {
            $upload_dir = wp_upload_dir();
            $filename = sanitize_file_name($_FILES["ccpuz_js_file"]["name"]);
            if (wp_mkdir_p($upload_dir['path'])) {
                $file = $upload_dir['path'] . '/' . $filename;
                $url = $upload_dir['url'] . '/' . $filename;
            } else {
                $file = $upload_dir['basedir'] . '/' . $filename;
                $url = $upload_dir['baseurl'] . '/' . $filename;
            }
            $image_data = @file_get_contents($_FILES["ccpuz_js_file"]["tmp_name"]);
            @file_put_contents($file, $image_data);
            if (defined('CCPUZ_DEBUG'))
                ccpuz_log("6.ccpuz_save_crossword_mce_from post_id = $post_id size = " . strlen($image_data) . "\n" . $url . "\n" . $file);
            ccpuz_applet();
            update_post_meta($post_id, 'ccpuz_js_url', $url);
        } else {
            if (get_post_meta($post_id, 'ccpuz_js_url', true) == '' && $should_be == 1) {
                if (defined('CCPUZ_DEBUG'))
                    ccpuz_log("<ccpuz_save_crossword_mce_from ERROR #5");

                echo 'Oops, Add puzzle .js file exported by Crossword Compiler.', 'Error: Something went wrong.';
                exit;
            }
        }
    }
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log("<ccpuz_save_crossword_mce_from post_id = $post_id OK");
    if (isset($_REQUEST["editor"]) && ($_REQUEST["editor"] == "gutenberg")) {
        echo do_shortcode('[crossword id="' . $post_id . '"]');
    } else {
        echo "1";
    }
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log("<<ccpuz_save_crossword_mce_from post_id = $post_id OK");
    exit;
}

function column_block_crossword_editor_assets() {
    global $post;
    $post_id = !!$post && property_exists($post, "ID") ? (int) $post->ID : 0;
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log(">column_block_crossword_editor_assets post_id = $post_id");
    if (!is_admin() || wp_script_is('gutenberg-crossword-block-script', "enqueued")) {
        if (defined('CCPUZ_DEBUG'))
            ccpuz_log("<column_block_crossword_editor_assets post_id = $post_id is_admin=" . (int) is_admin());
        return;
    }
    // Scripts.    
    wp_enqueue_script(
            'gutenberg-crossword-block-script', // Handle.
            plugins_url('modules/js/gutenberg-crossword.js', dirname(__FILE__)), // Block.js: We register the block here.
            array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-element'), // Dependencies, defined above.
            CCPUZ_VERSION
    );
    wp_localize_script('gutenberg-crossword-block-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'plugin_url' => CCPUZ_URL,
        'post_id' => $post_id
    ));
    // Styles.
    wp_enqueue_style(
            'gutenberg-crossword-block-editor-style', // Handle.
            plugins_url('modules/css/gutenberg-crossword-editor.css', dirname(__FILE__)), // Block editor CSS.
            array('wp-edit-blocks'), // Dependency to include the CSS after it.
            CCPUZ_VERSION
    );
}

// End function column_block_cgb_editor_assets().
// Hook: Editor assets.
add_action('enqueue_block_editor_assets', 'column_block_crossword_editor_assets');
add_action('enqueue_block_assets', 'column_block_crossword_editor_assets');

/**
 * Proper way to enqueue scripts and styles
 */
function crossword_custom_scripts() {
    wp_enqueue_style('crossword-custom', plugins_url('modules/css/custom.css', dirname(__FILE__)), null, CCPUZ_VERSION);
    wp_enqueue_script('crossword-custom', plugins_url('modules/js/custom.js', dirname(__FILE__)), array('jquery'), CCPUZ_VERSION, false);
}

add_action('wp_enqueue_scripts', 'crossword_custom_scripts');

function ccpuz_preview_local() {
    $post_id = (int) $_REQUEST["id"];
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log(">ccpuz_preview_local post_id = $post_id");
//if (defined('CCPUZ_DEBUG')) ccpuz_log(">ccpuz_preview_local \n".print_r($_FILES, true));     
    ob_clean();
    $js_url = get_post_meta($post_id, 'ccpuz_js_url', true);
    $js_run = get_post_meta($post_id, 'ccpuz_js_run', true);

    ccpuz_applet();
    $filename = 'crosswordCompiler.js';
    $upload_dir = wp_upload_dir();
    $js_compiler_url = $upload_dir['baseurl'] . '/ccpuz/' . $filename;
    $jquery_url = site_url() . "/wp-includes/js/jquery/jquery.min.js";

    $js_url_1 = plugins_url('modules/css/custom.css', dirname(__FILE__));
    $css_url_1 = plugins_url('modules/js/custom.js', dirname(__FILE__));

    $js_url_2 = plugins_url('/inc/CrosswordCompilerApp/raphael.js', __FILE__);

    $res = "<html><head>\n\n"
            . '<link rel="stylesheet" href="' . $css_url_1 . '" media="all"/>' . "\n"
            . '<script src = "' . $jquery_url . '"></script>' . "\n"
            . '<script src = "' . $js_url_1 . '"></script>' . "\n"
            . '<script src = "' . $js_url_2 . '"></script>' . "\n"
            . '<script src = "' . $js_compiler_url . '"></script>' . "\n"
            . '<script src="' . $js_url . '"></script>' . "\n"
            . $js_run . "\n"
            . '</head>'
            . '<body style="overflow: hidden;"><div class="entry-content" style="overflow: hidden;"><p class="wp-block-crossword-crossword-block"></p><div id="CrosswordCompilerPuz" style="overflow: hidden;"></div><p></p><p></p></div></body></html>' . "\n\n";
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log("<ccpuz_preview_local \n\n\n" . $res . "\n\n\n");
    echo $res;
    exit;
    /*
      if (defined('CCPUZ_DEBUG')) ccpuz_log(">ccpuz_preview_local \n".print_r($_FILES, true));
      if ((count($_FILES) < 2) || !array_key_exists("html_file", $_FILES) || !array_key_exists("js_file", $_FILES)){
      if (defined('CCPUZ_DEBUG')) ccpuz_log("<ccpuz_preview_local ERROR!\n".print_r($_FILES, true));
      die ("Invalid params!");
      }
      ob_clean();
      $html_data = @file_get_contents( $_FILES["html_file"]["tmp_name"] );
      $js_data = @file_get_contents( $_FILES["js_file"]["tmp_name"] );
      if (defined('CCPUZ_DEBUG')) ccpuz_log("1.ccpuz_preview_local size_html = ".strlen($html_data).", size js = ".strlen($js_data));

      $res_command = get_string_between( $html_data, '$(function(){', '})' );
      $res_command = preg_replace( '/ROOTIMAGES: "[^"]+"/i', 'ROOTIMAGES: ""', $res_command );
      $res_command = str_replace( 'ROOTIMAGES: "', 'ROOTIMAGES: "'.plugins_url( 'inc/CrosswordCompilerApp/CrosswordImages/', __FILE__ ), $res_command );
      $str_parent = ' jQuery(".entry-content").attr( "class", "entry-content puzzle"); ';

      $js_run = '<script>jQuery(document).ready(function($) { '.$str_parent.' '.$res_command.' });</script>';

      ccpuz_applet();
      $filename ='crosswordCompiler.js';
      $upload_dir = wp_upload_dir();
      $js_compiler_url = $upload_dir['baseurl'] .'/ccpuz/'.$filename ;
      $jquery_url = site_url()."/wp-includes/js/jquery/jquery.min.js";

      $js_url_1 = plugins_url( 'modules/css/custom.css', dirname(__FILE__) );
      $css_url_1 = plugins_url( 'modules/js/custom.js', dirname(__FILE__) );

      $js_url_2 = plugins_url('/inc/CrosswordCompilerApp/raphael.js', __FILE__);

      $res = "<html><head>\n\n".'<link rel="stylesheet" href="'.$css_url_1.'" media="all"/><script src = "'.$jquery_url.'"></script>'."\n".'<script src = "'.$js_url_1.'"></script>'."\n".'<script src = "'.$js_url_2.'"></script>'."\n".'<script src = "'.$js_compiler_url.'"></script>'."\n".'<script>'.$js_data.'</script>'."\n".$js_run."\n".'</head><body><div class="entry-content"><div id="CrosswordCompilerPuz"></div></div></body></html>'."\n\n";
      if (defined('CCPUZ_DEBUG')) ccpuz_log("<ccpuz_preview_local \n\n\n".$res."\n\n\n");
      echo $res;
      exit;
     * 
     */
}

add_action('wp_ajax_ccpuz_preview_local', 'ccpuz_preview_local');

function ccpuz_preview_shortcode() {
    //$res = do_shortcode('[crossword id="'.$_REQUEST["id"].'"]');
    die($res);
}

add_action('wp_ajax_ccpuz_preview_shortcode', 'ccpuz_preview_shortcode');


