<?php
/**
 * Plugin Name: GMO Showtime
 * Plugin URI: https://cloud.z.com/jp/en/wp/themes-plugins#plugins
 * Description: GMO Showtime slider plugin gives cool effects to the slider in a snap. The control screen is simple, for anyone to easily use. Express user's originality with fully customizable link and color as well as 16 slider effects in 6 different layouts,
 * Version:     1.6
 * Author:      Z.com by GMO
 * Author URI:  https://cloud.z.com/jp/en/wp/
 * License:     GPLv2
 * Text Domain: gmoshowtime
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2015 Z.com by GMO (https://cloud.z.com/jp/en/wp/)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

require_once(dirname(__FILE__).'/includes/pan-pan-pan.php');

define('GMOSHOWTIME_URL',  plugins_url('', __FILE__));
define('GMOSHOWTIME_PATH', dirname(__FILE__));

$gmoshowtime = new GMOShowtime();
$gmoshowtime->init();

function showtime($atts = array()) {
    global $gmoshowtime;
    if (get_option('gmoshowtime-maintenance', 1)) {
        if (get_header_image()) {
            return sprintf(
                '<img src="%s" height="%d" width="%d" alt="" />',
                get_header_image(),
                get_custom_header()->height,
                get_custom_header()->width
            );
        } else {
            return;
        }
    }

    $page_types = $gmoshowtime->get_page_types();
    foreach (get_option('gmoshowtime-page-types', array_keys($page_types)) as $page_type) {
        if (isset($page_types[$page_type]) && is_array($page_types[$page_type]['callback'])) {
            foreach ($page_types[$page_type]['callback'] as $callback) {
                if (call_user_func($callback)) {
                    echo $gmoshowtime->get_slider_contents($atts);
                    break;
                }
            }
        }
    }
}

class GMOShowtime {

private $version = '';
private $langs   = '';
private $transitions = array(
    'sliceDown',
    'sliceDownLeft',
    'sliceUp',
    'sliceUpLeft',
    'sliceUpDown',
    'sliceUpDownLeft',
    'fold',
    'fade',
    'random',
    'slideInRight',
    'slideInLeft',
    'boxRandom',
    'boxRain',
    'boxRainReverse',
    'boxRainGrow',
    'boxRainGrowReverse',
);
private $default_image_size = 'gmoshowtime-image';
private $default_transition = 'fade';
private $default_background_color = '#0058ae';
private $default_text_color = '#ffffff';

function __construct()
{
    $data = get_file_data(
        __FILE__,
        array('ver' => 'Version', 'langs' => 'Domain Path')
    );
    $this->version = $data['ver'];
    $this->langs   = $data['langs'];
}

public function init()
{
    add_action('plugins_loaded', array($this, 'plugins_loaded'));
}

public function plugins_loaded()
{
    load_plugin_textdomain(
        'gmoshowtime',
        false,
        dirname(plugin_basename(__FILE__)).$this->langs
    );
	add_image_size( 'gmoshowtime-image-full',  1200, 487, true );
	add_image_size( 'gmoshowtime-image-medium',  730, 487, true );

    add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
    add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    add_action('admin_menu', array($this, 'admin_menu'));
    add_action('admin_init', array($this, 'admin_init'));
    add_action('admin_print_footer_scripts', array($this, 'admin_print_footer_scripts'), 9999);

    add_shortcode('showtime', array($this, 'showtime'));

    // add copy to new carousel interface
    add_action(
        'wp_before_admin_bar_render',
        array($this, 'wp_before_admin_bar_render')
    );
}

public function wp_before_admin_bar_render() {
    global $wp_admin_bar;
    global $pagenow;

    if (is_single() || is_page()) {
        $wp_admin_bar->add_menu(array(
            'id' => 'new_carousel',
            'title' => __('Copy to a new carousel', 'gmoshowtime'),
            'href' => admin_url('post-new.php?post_type=gmo-showtime&slide_id='.get_the_ID())
        ));
    } elseif ($pagenow === 'post.php' && isset($_GET['action']) && $_GET['action'] === 'edit') {
        if (get_post_type() !== 'gmo-showtime') {
            $wp_admin_bar->add_menu(array(
                'id' => 'new_carousel',
                'title' => __('Copy to a new carousel', 'gmoshowtime'),
                'href' => admin_url('post-new.php?post_type=gmo-showtime&slide_id='.get_the_ID())
            ));
        }
    }
}

public function showtime($atts)
{
    if (get_option('gmoshowtime-maintenance', 1)) {
        if (get_header_image()) {
            return sprintf(
                '<img src="%s" height="%s" width="%s" alt="" />',
                get_header_image(),
                get_custom_header()->height,
                get_custom_header()->width
            );
        } else {
            return;
        }
    }

    return $this->get_slider_contents($atts);
}

public function get_slider_contents($atts = array())
{
    global $post;
    extract( shortcode_atts( array(
        'columns'     => $this->get_default_columns(),
        'transition' => get_option('gmoshowtime-transition', $this->get_default_transition()),
        'show_title' => $this->get_default_show_title(),
        'image_size' => 'gmoshowtime-image-full',
        'images'      => array(),
    ), $atts ) );

	$class = get_option('gmoshowtime-css-class', $this->get_default_css_class());

	$style = "background-color:".$this->get_background_color().";color:". $this->get_text_color().";";

	if ( $class == "left-photo-right" || $class == "right-photo-left" ) {
		$image_size = 'gmoshowtime-image-medium';
	}
    if (!count($images)) {
        $args = array(
            "post_type"             => "gmo-showtime",
            "posts_per_page"        => -1,
            "post_status"           => 'publish',
            "orderby"               => 'menu_order',
            "order"                 => 'ASC',
            "ignore_sticky_posts"   => 1,
        );
        $posts = get_posts($args);

        foreach ($posts as $post) {
            setup_postdata( $post );
            $thumb = get_the_post_thumbnail($post->ID, $image_size);
            $image = preg_replace("/.*src=[\"\'](.+?)[\"\'].*/", "$1", $thumb);
            $images[] = array(
                'link'  => get_post_meta($post->ID, '_slide_link', true),
                'image' => $image,
                'title' => get_the_title(),
                'content' => get_the_excerpt()
            );
        }
        wp_reset_postdata();
    }
    $html = '';
    if ( !empty($images)) {

    $html .= "\n<!-- Start GMO Showtime-->\n";
    $html .= "<div id=\"gmo-show-time\" class=\"slider-wrapper theme-default\">\n";
    $html .= sprintf(
        '<div class="%s" style="%s"><div class="slider-box"><div class="showtime nivoSlider" data-columns="%d" data-transition="%s" data-show_title="%d">',
        esc_attr( $class ),
        esc_attr( $style ),
        $columns,
        $transition,
        $show_title
    );

    $template = $this->get_slide_template();

    foreach ($images as $img) {
        if (!$img['image']) {
            continue;
        }

		if ( $class == "left-photo-right" || $class == "right-photo-left" ) {
			$img['title'] = mb_strimwidth($img['title'], 0, apply_filters( "gmoshowtime_title_lr_length" , 60), "...", "UTF-8");
			$img['content'] = mb_strimwidth($img['content'], 0, apply_filters("gmoshowtime_content_lr_length", 148), "...", "UTF-8");
		} else {
			$img['title'] = mb_strimwidth($img['title'], 0, apply_filters( "gmoshowtime_title_ov_length" , 60), "...", "UTF-8");
			$img['content'] = mb_strimwidth($img['content'], 0, apply_filters("gmoshowtime_content_ov_length", 280), "...", "UTF-8");
		}

        $slide = $template;
        $slide = str_replace("%title%", $img['title'], $slide);
        $slide = str_replace("%content%", esc_html($img['content']), $slide);
        $slide = str_replace("%link%", esc_url($img['link']), $slide);
        $slide = str_replace("%image%", esc_url($img['image']), $slide);
        $html .= $slide;
    }

    $html .= '</div></div></div>';
    $html .= '</div>';
    $html .= "\n<!-- End GMO Showtime-->\n";
    }
    return $html;
}

public function admin_init()
{
    if (isset($_POST['gmoshowtime']) && $_POST['gmoshowtime']){
        if (check_admin_referer('gmoshowtime', 'gmoshowtime')){
            if (isset($_POST['transition']) && in_array($_POST['transition'], $this->get_transitions())) {
                update_option('gmoshowtime-transition', $_POST['transition']);
            } else {
                update_option('gmoshowtime-transition', $this->get_default_transition());
            }
            if (isset($_POST['max-pages']) && intval($_POST['max-pages'])) {
                update_option('gmoshowtime-max-pages', $_POST['max-pages']);
            } else {
                update_option('gmoshowtime-max-pages', 4);
            }
            if (isset($_POST['page-types']) && is_array($_POST['page-types'])) {
                update_option('gmoshowtime-page-types', $_POST['page-types']);
            } else {
                update_option('gmoshowtime-page-types', array());
            }
            if (isset($_POST['css-class']) && in_array($_POST['css-class'], array_keys($this->get_css_classes()))) {
                update_option('gmoshowtime-css-class', $_POST['css-class']);
            } else {
                update_option('gmoshowtime-css-class', $this->get_default_css_class());
            }
            if (isset($_POST['image-size'])
                    && in_array($_POST['image-size'], array_keys($this->list_image_sizes()))) {
                update_option('gmoshowtime-image-size', $_POST['image-size']);
            } else {
                update_option('gmoshowtime-image-size', $this->get_default_image_size());
            }
            if (isset($_POST['apply-gallery']) && $_POST['apply-gallery']) {
                update_option('gmoshowtime-apply-gallery', 1);
            } else {
                update_option('gmoshowtime-apply-gallery', 0);
            }
            if (isset($_POST['maintenance']) && intval($_POST['maintenance'])) {
                update_option('gmoshowtime-maintenance', 1);
            } else {
                update_option('gmoshowtime-maintenance', 0);
            }
			if(isset($_POST['background-color']) && $_POST['background-color'] && $_POST['background-color'] != $this->default_background_color) {
				update_option('gmoshowtime-background-color', $_POST['background-color']);
			}

			if(isset($_POST['text-color']) && $_POST['text-color'] && $_POST['text-color'] != $this->default_text_color) {
				update_option('gmoshowtime-text-color', $_POST['text-color']);
			}

			wp_redirect('options-general.php?page=gmoshowtime');
        }
    }

    global $pagenow;

    if ($pagenow === 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'gmo-showtime') {
        if (isset($_GET['slide_id']) && intval($_GET['slide_id'])) {
            $post = get_post(intval($_GET['slide_id']));
            $post_id = wp_insert_post(array(
                'post_type'  => 'gmo-showtime',
                'post_title' => $post->post_title,
                'post_excerpt' => $post->post_excerpt,
            ));
            update_post_meta($post_id, '_slide_link', get_permalink($post->ID));
            $post_thumbnail = get_post_meta($post->ID, '_thumbnail_id', true);
            if (!wp_is_post_revision($post_id)) {
                if ($post_thumbnail) {
                    update_post_meta($post_id, '_thumbnail_id', $post_thumbnail);
                }
            }
            wp_redirect(admin_url(sprintf('post.php?post=%d&action=edit', $post_id)));
        }
    }
}

public function admin_menu()
{
    add_options_page(
        __('GMO Showtime', 'gmoshowtime'),
        __('GMO Showtime', 'gmoshowtime'),
        'publish_posts',
        'gmoshowtime',
        array($this, 'options_page')
    );
}

public function options_page()
{
    require_once(dirname(__FILE__).'/includes/admin.php');
}

public function admin_enqueue_scripts()
{
    if (isset($_GET['page']) && $_GET['page'] === 'gmoshowtime') {

		wp_enqueue_style(
			'google-fonts',
			'http://fonts.googleapis.com/css?family=Open+Sans',
			array(),
			$this->version
		);
        wp_enqueue_style(
            'admin-gmoshowtime-style',
            plugins_url('css/admin-gmo-showtime.min.css', __FILE__),
			array( 'google-fonts' ),
            $this->version,
            'all'
        );

        wp_enqueue_style(
            'gmoshowtime-style',
            plugins_url('css/gmo-showtime.min.css', __FILE__),
            array( 'admin-gmoshowtime-style' ),
            $this->version,
            'all'
        );

        wp_enqueue_style('showtime-gmo-admin-plugin',plugins_url('css/gmo-admin-plugin.css', __FILE__));

        wp_enqueue_script(
            'admin-gmoshowtime-script',
            plugins_url('js/admin-gmo-showtime.min.js', __FILE__),
            array('jquery'),
            $this->version,
            true
        );

		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );

		wp_enqueue_script(
            'admin-gmoshowtime-colorpicker-script',
            plugins_url('js/admin-gmo-showtime-colorpicker.js', __FILE__),
            array('jquery'),
            $this->version,
            true
        );
    }
}

public function admin_print_footer_scripts()
{

}

public function wp_enqueue_scripts()
{
	wp_enqueue_style(
		'google-fonts',
		'http://fonts.googleapis.com/css?family=Open+Sans',
		array(),
		$this->version
	);

    wp_enqueue_style(
        'gmo-showtime-style',
        plugins_url('css/gmo-showtime.min.css', __FILE__),
        array( 'google-fonts' ),
        $this->version,
        'all'
    );

    wp_enqueue_script(
        'gmo-showtime-script',
        plugins_url('js/gmo-showtime.min.js', __FILE__),
        array('jquery'),
        $this->version,
        true
    );
}

public function get_page_types()
{
    return apply_filters("gmoshowtime_page_types", array(
        'home' => array(
            'caption'  => 'Home',
            'callback' => array('is_home', 'is_front_page'),
        ),
        'singular' => array(
            'caption'  => 'Posts and Pages',
            'callback' => array('is_singular'),
        ),
        'archive' => array(
            'caption'  => 'Archive',
            'callback' => array('is_archive'),
        ),
    ));
}

public function get_background_color()
{
	if(get_option('gmoshowtime-background-color')) {
		return get_option('gmoshowtime-background-color');
	} else {
		return $this->default_background_color;
	}

}

public function get_text_color()
{
	if(get_option('gmoshowtime-text-color')) {
		return get_option('gmoshowtime-text-color');
	} else {
		return $this->default_text_color;
	}

}

private function get_default_transition()
{
    return apply_filters('gmoshowtime_default_transition', $this->default_transition);
}

private function get_default_image_size()
{
    return apply_filters('gmoshowtime_default_image_size', $this->default_image_size);
}

private function get_slide_template()
{
    $template = "<a href=\"%link%\" class=\"slide\"><img src=\"%image%\" title=\"&nbsp;\" data-title=\"%title%\" data-content=\"%content%\"></a>";
    return apply_filters("gmoshowtime_slide_template", $template);
}

private function get_css_classes()
{
    $css_classes = array(
        'top-left'         => __('Top-Left', 'gmoshowtime'),
        'top-right'        => __('Top-Right', 'gmoshowtime'),
        'bottom-left'      => __('Bottom-Left', 'gmoshowtime'),
        'bottom-right'     => __('Bottom-Right', 'gmoshowtime'),
        'left-photo-right' => __('Left', 'gmoshowtime'),
        'right-photo-left' => __('Right', 'gmoshowtime'),
    );

    return apply_filters('gmoshowtime-css-classes', $css_classes);
}

private function get_default_css_class()
{
    // see get_css_classes()
    return apply_filters("gmoshowtime_default_css_class", "top-left");
}

private function get_default_show_title()
{
    return apply_filters('gmoshowtime_default_show_title', 1);
}

private function get_default_columns()
{
    return apply_filters('get_default_columns', 1);
}

private function get_preview_contents()
{
	$style = "background-color:".$this->get_background_color().";color:". $this->get_text_color().";";
    echo "<div class=\"slider-wrapper theme-default\">\n";
    printf(
        '<div class="%s" style="%s"><div class="slider-box"><div class="showtime nivoSlider" data-columns="%d" data-transition="%s" data-show_title="%d">',
        esc_attr(get_option('gmoshowtime-css-class', $this->get_default_css_class())),
        esc_attr( $style ),
        $this->get_default_columns(),
        get_option('gmoshowtime-transition', $this->get_default_transition()),
        $this->get_default_show_title()
    );
    $template = $this->get_slide_template();

    for ($i=0; $i<20; $i++) {
        if ($i % 2) {
            $img = plugins_url('img/blue.png', __FILE__);
        } else {
            $img = plugins_url('img/orange.png', __FILE__);
        }
        $n = $i + 1;
        $html = $template;
        $html = str_replace(
            "%css_class%",
            esc_attr(get_option('gmoshowtime-css-class', $this->get_default_css_class())),
            $html
        );
        $html = str_replace("%title%", 'Page '.$n, $html);
        $html = str_replace("%content%", 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', $html);
        $html = str_replace("%link%", '#', $html);
        $html = str_replace("%image%", $img, $html);
        echo $html;
    }

    echo '</div></div></div>';
    echo '</div>';
}

private function get_transitions()
{
    return apply_filters(
        'gmoshowtime_transtions',
        $this->transitions
    );
}

private function list_image_sizes()
{
    global $_wp_additional_image_sizes;
    $sizes = array();
    foreach (get_intermediate_image_sizes() as $s) {
        $sizes[$s] = array(0, 0);
        if (in_array($s, array('thumbnail', 'medium', 'large'))) {
            $sizes[$s][0] = get_option($s . '_size_w');
            $sizes[$s][1] = get_option($s . '_size_h');
        } else {
            if (isset($_wp_additional_image_sizes) && isset($_wp_additional_image_sizes[$s])) {
                $sizes[ $s ] = array(
                    $_wp_additional_image_sizes[$s]['width'],
                    $_wp_additional_image_sizes[$s]['height'],
                );
            }
        }
    }

    return $sizes;
}


} // end TestPlugin

// EOF

// add filter for GMOFontAgent
if ( class_exists('GMOFontAgent') ) {
	add_filter("gmofontagent_default_tags", "gmoshowtime_gmofontagent_default_tags");
	function gmoshowtime_gmofontagent_default_tags($tags){
		$tags['.nivo-caption'] = 'GMO Showtime-caption';
		return $tags;
	}
}
