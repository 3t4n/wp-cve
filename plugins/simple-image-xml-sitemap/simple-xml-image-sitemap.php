<?php
/*
 * Plugin Name: Simple Image XML Sitemap
 * Description: The Simple Image XML Sitemap plugin will generate an XML Sitemap for all images. Open the <a href="tools.php?page=simple-xml-image-sitemap">Tools</a> page to create your image sitemap.
 * Author: blapps
 * Version: 3.4
 * Tested up to: 6.3
 * Text Domain: simple-xml-image-sitemap
 * Domain Path: /languages
*/

function sixs_load_plugin_textdomain()
{
    load_plugin_textdomain('simple-xml-image-sitemap', FALSE, basename(dirname(__FILE__)) . '/languages/');
}

add_action('plugins_loaded', 'sixs_load_plugin_textdomain');

add_action('admin_menu', 'sixs_image_sitemap_page');
function sixs_image_sitemap_page()
{
    if (function_exists('add_submenu_page')) {
        add_submenu_page(
            'tools.php',
            __('Simple Image XML Sitemap'),
            __('Simple Image XML Sitemap'),
            'manage_options',
            'simple-xml-image-sitemap',
            'sixs_image_sitemap_generate'
        );
    }
}

function sixs_seo_plugin_check()
{
    $pluginList = get_option('active_plugins');
    $plugin = 'simple-seo-criteria-check/simple-seo-criteria-check.php';
    if (in_array($plugin, $pluginList)) {
        return 1;
    }
    return 0;
}


function sixs_plugin_action_links($links, $file)
{
    if ($file == plugin_basename(dirname(__FILE__) . '/simple-xml-image-sitemap.php')) {
        $links[] = '<a href="' . admin_url('tools.php?page=simple-xml-image-sitemap') . '">' . __('Generate Image Sitemap', 'simple-xml-image-sitemap') . '</a>';
        $links[] = '<a href="' . admin_url('options-general.php?page=plugin_sixs') . '">' . __('Go to Settings', 'simple-xml-image-sitemap') . '</a>';
    }
    return $links;
}

add_filter('plugin_action_links', 'sixs_plugin_action_links', 10, 2);

require('sixs_functions.php');

function sixs_is_file_writable($filename)
{
    if (!is_writable($filename)) {
        if (!@chmod($filename, 0666)) {
            $dirname = dirname($filename);
            if (!is_writable($dirname)) {
                if (!@chmod($dirname, 0666)) {
                    return false;
                }
            }
        }
    }
    return true;
}

function sixs_escape_xml_entities($xml)
{
    return str_replace(
        array('&', '<', '>', '\'', '"'),
        array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'),
        $xml
    );
}

function sixs_image_sitemap_generate()
{

    echo '<div class="wrap">';

    echo '<h2>Simple XML Image Sitemap</h2>';
    echo '<p>' . __('Image sitemaps can be used to inform search engines about the images on your website.', 'simple-xml-image-sitemap') . '</p>';
    echo '<p>' . __('You can create or re-create the sitemap file by clicking the following button.', 'simple-xml-image-sitemap') . '</p>';

    if (sixs_seo_plugin_check()) {
        echo '<p><div style="color:red">' . __('Here you can <a href="tools.php?page=sscc_images">check images for meta information</a> for XML Image Sitemap', 'simple-xml-image-sitemap') . '</div></p>';
    } else {
        echo '<p><div style="color:red">' . __('We recommend to use the Plugin <a href="https://de.wordpress.org/plugins/simple-seo-criteria-check/" target="_Blank">Simple SEO Criteria Checklist</a> to check completeness of image meta information for XML Image Sitemap', 'simple-xml-image-sitemap') . '</div></p>';
    }

    echo '<form method="post" action="">';
    echo '<input type="submit" class="button-primary" name="submit" value="' . __('Generate Image Sitemap', 'simple-xml-image-sitemap') . '" />';
    wp_nonce_field('sixs_image_sitemap_generate', 'nonce');
    echo '</form>';

    echo '<p>' . __('After the sitemap has been created, you can submit it using', 'simple-xml-image-sitemap') . ' <a href="https://search.google.com/search-console" target="_blank">Google Search Console</a></p>';

    echo '<p><a href="options-general.php?page=plugin_sixs">' . __('Settings for XML Image Sitemap', 'simple-xml-image-sitemap') . '</a></p>';

    echo '</div>';


    if (isset($_POST['submit'])) {
        if (wp_verify_nonce($_POST['nonce'], 'sixs_image_sitemap_generate')) {

            switch (sixs_image_sitemap_create()) {
                case -1:
                    // An error occurred
                    echo '<div class="notice notice-error"><h2>' . __('Error') . '</h2>';
                    echo '<p>' . __('The image sitemap could not be saved to your web root folder.', 'simple-xml-image-sitemap') . '</p>';
                    echo '<p>' . __('Please make sure the folder has the appropriate permissions.', 'simple-xml-image-sitemap') . '</p>';
                    echo '<p>' . __('If you are not able to change those permissions, contact your web host.', 'simple-xml-image-sitemap') . '</p>';
                    echo '</div>';
                    break;
                case 0:
                    // Nothing to do
                    echo '<div class="notice notice-error"><h2>' . __('Nothing to display', 'simple-xml-image-sitemap') . '</h2>';
                    echo '<p>' . __('No image sitemap was saved, because no images could be found.', 'simple-xml-image-sitemap') . '</p>';
                    echo '</div>';
                    break;
                default:
                    // A bunch of images were written to the sitemap file
                    // 20170620 - HVV: Changed acc to https://wordpress.org/support/topic/bug-found-and-used-editor-to-fix/
                    $url = parse_url(get_bloginfo('url'));
                    $url = $url['scheme'] . '://' . $url['host'] . '/sitemap-images.xml';
                    echo '<div class="notice notice-success">';
                    echo '<h2>' . __('The image sitemap was generated successfully.', 'simple-xml-image-sitemap') . '</h2>';
                    echo '<p>' . __('You can find the file here:', 'simple-xml-image-sitemap') . ' <a href="' . $url . '" target="_blank">' . $url . '</a></p>';
                    echo '<p>' . __('You can let Google know about the updated sitemap:', 'simple-xml-image-sitemap') . ' <a href="http://www.google.com/webmasters/sitemaps/ping?sitemap=' . urlencode($url) . '" target="_blank">' . __('Ping Google') . '</a></p>';
                    echo '</div>';
            }
            exit;
        }
    }
}


function sixs_image_sitemap_create()
{
    global $wpdb;
    //$posts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type<>'revision' AND post_status IN ('publish','inherit') order by post_date DESC");

    //$post_type = ["post", "page", "attachment"];
    $post_status = ["publish", "inherit"];

    $include_post_types = get_option('sixs_posttypes_option_name');

    if (!empty($include_post_types)) {
        array_push($include_post_types, 'attachment');
        $include_post_types = array_unique($include_post_types);
    } else {
        $include_post_types = array("post", "page", "attachment");
    }


    //var_dump($include_post_types);

    //$prepare_posts = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type IN (%s) AND post_status IN (%s) order by post_date DESC", $post_type1, $post_status);


    $prepare_posts = $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type IN ('".implode("','",$include_post_types)."') AND post_status IN ('".implode("','",$post_status)."') order by post_date DESC");


    $posts = $wpdb->get_results($prepare_posts);
    //echo $prepare_posts;

    $prepare_thumbs = $wpdb->prepare("SELECT post_name, post_mime_type, guid, post_excerpt, post_title, post_parent FROM wp_posts WHERE post_type IN (%s)", 'attachment');
    $thumbs = $wpdb->get_results($prepare_thumbs);

    if (empty($posts) && empty($thumbs)) {
        // Nothing much to do
        return 0;
    } else {
        $images = array();
        foreach ($posts as $post) {
            if ($post->post_type == 'attachment') {
                if ($post->post_parent != 0) {
                    $images[$post->post_parent][$post->guid] = 1;
                }
            }
        }
        foreach ($thumbs as $post) {
            if (isset($post->ID)) {
            $images[$post->ID][$post->guid] = 1;
            }
        }

        if (count($images) == 0) {
            // Looked promising but no cigar after all
            return 0;
        } else {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";


            foreach ($images as $k => $v) {
                unset($imgurls);
                foreach (array_keys($v) as $imgurl) {
                    if (is_ssl()) {
                        $imgurl = str_replace('http://', 'https://', $imgurl);
                    } else {
                        $imgurl = str_replace('https://', 'http://', $imgurl);
                    }
                    $imgurls[$imgurl] = 1;
                }
                $permalink = get_permalink($k);
                $posttype = get_post_status($k);

                if (!empty($permalink)) {
                    $img = '';

                    $imgcounter = 0;

                    foreach (array_keys($imgurls) as $imgurl) {

                        //echo $imgurl . '<br>';

                        $guids = $wpdb->get_results("SELECT id, post_name, post_mime_type, guid, post_excerpt, post_title, post_content, post_parent
									FROM $wpdb->posts
										 WHERE guid LIKE '" . $imgurl . "' order by post_date DESC"); //" and post_title not in (" . $blacklist . ")";


                        foreach ($guids as $guid) {

                            //   var_dump($guid);

                            $skipimages = ["skopje", "pinterest"];
                            $imgpath = $guid->guid;

                            foreach ($skipimages as $skipimg) {
                                if (strpos($imgpath, $skipimg)) {
                                    //  continue;
                                }
                            }

                            $current_caption_1 = get_option('sixs_caption_1_option_name');
                            $current_caption_2 = get_option('sixs_caption_2_option_name');
                            $current_title_1 = get_option('sixs_title_1_option_name');
                            $current_title_2 = get_option('sixs_title_2_option_name');


                            switch ($current_caption_1[0]) {
                                case 'content':
                                    $img_caption = $guid->post_content;
                                    break;
                                case 'excerpt':
                                    $img_caption = $guid->post_excerpt;
                                    break;
                                case 'title':
                                    $img_caption = $guid->post_title;
                                    break;
                                case 'description':
                                    $img_caption = $guid->post_description;
                                    break;
                                case 'alt_tag':
                                    $img_caption = get_post_meta($guid->id, '_wp_attachment_image_alt', TRUE);
                                    break;
                                default:
                                    $img_caption = $guid->post_excerpt;
                            }

                            if (empty($img_caption)) {
                                switch ($current_caption_2) {
                                    case 'content':
                                        $img_caption = $guid->post_content;
                                        break;
                                    case 'excerpt':
                                        $img_caption = $guid->post_excerpt;
                                        break;
                                    case 'title':
                                        $img_caption = $guid->post_title;
                                        break;
                                    case 'description':
                                        $img_caption = $guid->post_description;
                                        break;
                                    case 'alt_tag':
                                        $img_caption = get_post_meta($guid->id, '_wp_attachment_image_alt', TRUE);
                                        break;
                                    default:
                                        $img_caption = $guid->post_excerpt;
                                }
                            }

                            switch ($current_title_1[0]) {
                                case 'content':
                                    $img_title = $guid->post_content;
                                    break;
                                case 'excerpt':
                                    $img_title = $guid->post_excerpt;
                                    break;
                                case 'title':
                                    $img_title = $guid->post_title;
                                    break;
                                case 'description':
                                    $img_title = $guid->post_description;
                                    break;
                                case 'alt_tag':
                                    $img_title = get_post_meta($guid->id, '_wp_attachment_image_alt', TRUE);
                                    break;
                                default:
                                    $img_title = $guid->post_content;
                            }

                            if (empty($img_title)) {
                                switch ($current_title_2) {
                                    case 'content':
                                        $img_title = $guid->post_content;
                                        break;
                                    case 'excerpt':
                                        $img_title = $guid->post_excerpt;
                                        break;
                                    case 'title':
                                        $img_title = $guid->post_title;
                                        break;
                                    case 'description':
                                        $img_title = $guid->post_description;
                                        break;
                                    case 'alt_tag':
                                        $img_title = get_post_meta($guid->id, '_wp_attachment_image_alt', TRUE);
                                        break;
                                    default:
                                        $img_title = $guid->post_content;
                                }
                            }


                            $meta_img_url = get_post_meta($guid->id, '_wp_attached_file', true);

                            $img .= '       <image:image>' . "\n";
                            $img .= '       	   <image:loc>' . content_url() . '/uploads/' . $meta_img_url . '</image:loc>' . "\n";
                            $img .= '       	   <image:caption>' . esc_html($img_caption) . '</image:caption>' . "\n"; // text below picture
                            $img .= '        	   <image:title>' . esc_html($img_title) . '</image:title>' . "\n"; // meta data image title
                            $img .= '       </image:image>' . "\n";

                            $imgcounter++;

                        }

                    }

                    if (($posttype == 'publish') && ($imgcounter > 0)) {
                        $xml .= "<url>\n";
                        $xml .= "	<loc>" . sixs_escape_xml_entities($permalink) . "</loc>\n";
                        $xml .= $img;
                        $xml .= "</url>\n";
                    }
                }
            }
            $xml .= "</urlset>";
        }
    }

    $image_sitemap_url = $_SERVER["DOCUMENT_ROOT"] . '/sitemap-images.xml';
    if (sixs_is_file_writable($_SERVER["DOCUMENT_ROOT"]) || sixs_is_file_writable($image_sitemap_url)) {
        if (file_put_contents($image_sitemap_url, $xml)) {
            return count($images);
        }
    }

    // Return an error
    return -1;
}

$autosave = get_option('sixs_xml_autosave_option_name');
if ($autosave) {
    // if Image Sitemap has once been created - automatically update with post save button
    if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/sitemap-images.xml')) {
        add_action('save_post', 'sixs_image_sitemap_create');
    }
}

