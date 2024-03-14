<?php

namespace Modular\ConnectorDependencies;

use Modular\ConnectorDependencies\Ares\Framework\Foundation\Application;
/**
 * Get the available container instance.
 *
 * @param string|null $abstract
 * @param array $parameters
 *
 * @return mixed|Application
 *
 * @copyright Taylor Otwell
 * @link      https://github.com/laravel/framework/blob/8.x/src/Illuminate/Foundation/helpers.php
 * @internal
 */
function app(?string $abstract = null, array $parameters = [])
{
    if (\is_null($abstract)) {
        return Application::getInstance();
    }
    return Application::getInstance()->make($abstract, $parameters);
}
if (!\function_exists('Modular\\ConnectorDependencies\\get_layout')) {
    /**
     * Get layout from database
     *
     * @param string $name
     *
     * @return mixed|null
     * @internal
     */
    function get_layout(string $name)
    {
        $query = new \WP_Query(['post_type' => 'ares_layouts', 'post_status' => 'publish', 's' => $name]);
        if ($query->found_posts == 0) {
            return null;
        }
        $layout = $query->post;
        $content = \shortcode_unautop(\trim($layout->post_content));
        //Usar esto para el post_content
        if (\strpos($content, '[vc_row') !== \false) {
            // In WordPress 4.9 post content wrapped with <p>...</p>
            // and shortcode_unautop() not remove it - do it manual
            if (\strpos($content, '<p>[vc_row') !== \false || \strpos($content, '<p>[vc_section') !== \false) {
                $content = \str_replace(['<p>[vc_row', '[/vc_row]</p>', '<p>[vc_section', '[/vc_section]</p>'], ['[vc_row', '[/vc_row]', '[vc_section', '[/vc_section]'], $content);
            }
        }
        $content = \apply_filters('the_content', $content);
        return \do_shortcode(\str_replace(['{{Y}}', '{Y}'], \date('Y'), $content));
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\__trans')) {
    /**
     * @param string $text
     * @param string $domain
     *
     * @return string
     * @internal
     */
    function __trans(string $text, string $domain = 'default') : string
    {
        \do_action('wpml_register_single_string', $domain, $text, $text);
        return \translate($text, $domain);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\display_video_player')) {
    /**
     * Process URL video to display video player
     *
     * @param string|null $video
     *
     * @return string|null
     * @internal
     */
    function display_video_player(?string $video)
    {
        global $wp_embed;
        if (!empty($video)) {
            return $wp_embed->run_shortcode('[embed]' . $video . '[/embed]');
        }
        return null;
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\get_customizer_image')) {
    /**
     * @param string $setting
     * @param string $image_size
     *
     * @return string
     * @internal
     */
    function get_customizer_image(string $setting, string $image_size)
    {
        return \wp_get_attachment_image(\attachment_url_to_postid(\str_ireplace('/', '/', \get_theme_mod($setting))), $image_size, \true);
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\remove_filters_with_method_name')) {
    /**
     * Remove function with method_name nad hook_name
     *
     * @param string $hook_name
     * @param string $method_name
     * @param int $priority
     *
     * @return false
     *
     * @link https://wordpress.stackexchange.com/questions/304859/remove-action-from-a-plugin-class
     * @internal
     */
    function remove_filters_with_method_name(string $hookName, string $methodName, int $priority = 10) : bool
    {
        global $wp_filter;
        if (!isset($wp_filter[$hookName][$priority]) || !\is_array($wp_filter[$hookName][$priority])) {
            return \false;
        }
        $removed = \false;
        foreach ($wp_filter[$hookName][$priority] as $uniqueId => $filterArray) {
            if (!isset($filterArray['function']) || !\is_array($filterArray['function'])) {
                continue;
            }
            [$object, $method] = $filterArray['function'];
            if (!\is_object($object) || $method !== $methodName) {
                continue;
            }
            if ($wp_filter[$hookName] instanceof \WP_Hook) {
                unset($wp_filter[$hookName]->callbacks[$priority][$uniqueId]);
            } else {
                unset($wp_filter[$hookName][$priority][$uniqueId]);
            }
            $removed = \true;
        }
        return $removed;
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\numeric_pagination')) {
    /**
     * Generate pagination
     *
     * @param $wp_query
     * @return string|null
     * @internal
     */
    function numeric_pagination($wp_query)
    {
        /** Stop execution if there's only 1 page */
        if ($wp_query->max_num_pages <= 1) {
            return null;
        }
        $paged = \get_query_var('paged') ? \absint(\get_query_var('paged')) : 1;
        $max = \intval($wp_query->max_num_pages);
        /** Add current page to the array */
        if ($paged >= 1) {
            $links[] = $paged;
        }
        /** Add the pages around the current page to the array */
        if ($paged >= 3) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }
        if ($paged + 2 <= $max) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }
        $next_link = \next_posts($max, \false);
        $previous_link = \get_previous_posts_link();
        $class = $next_link ? 'has_next' : '';
        $class .= $previous_link ? ' has_previous' : '';
        $html = \sprintf('<div class="uq-blog-grid-paginator"><nav class="uq-blog-grid-paginator-nav" aria-label="' . \__('Navigations', 'modular_domain') . '"><ul class="pagination %s">', $class);
        /** Link to first page, plus ellipses if necessary */
        if (!\in_array(1, $links)) {
            $class = 1 == $paged ? 'active' : '';
            $html .= \sprintf('<li class="page-item"><a class="page-link %s" href="%s">%s</a></li>', $class, \esc_url(\get_pagenum_link(1)), '1');
            if (!\in_array(2, $links)) {
                $html .= '<li class="page-item">…</li>';
            }
        }
        /** Link to current page, plus 2 pages in either direction if necessary */
        \sort($links);
        foreach ((array) $links as $link) {
            $class = $paged == $link ? 'active' : '';
            $html .= \sprintf('<li class="page-item"><a class="page-link %s" href="%s">%s</a></li>', $class, \esc_url(\get_pagenum_link($link)), $link);
        }
        /** Link to last page, plus ellipses if necessary */
        if (!\in_array($max, $links)) {
            if (!\in_array($max - 1, $links)) {
                $html .= '<li class="page-item">…</li>';
            }
            $class = $paged == $max ? 'active' : '';
            $html .= \sprintf('<li class="page-item"><a class="page-link %s" href="%s">%s</a></li> ', $class, \esc_url(\get_pagenum_link($max)), $max);
            $html .= \sprintf('<li class="page-item"><a class="page-link %s" href="%s"><span class="icon icon-arrow-right"></span></a></li> ', $class, $next_link);
        }
        $html .= '</ul></nav></div>';
        return $html;
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\the_custom_logo_footer')) {
    /**
     * Set the image from the customizer to the footer`s logo
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal
     */
    function the_custom_logo_footer()
    {
        $attachment_id = \attachment_url_to_postid(\str_ireplace('\\/', '/', \get_theme_mod('footer_custom_logo_controls')));
        $url = \get_home_url();
        $image = \wp_get_attachment_image($attachment_id, 'full', \false, ['class' => 'image', 'itemprop' => 'logo', 'itemscope' => 'itemscope', 'role' => 'presentation', 'aria-hidden' => 'true']);
        return view('items.logo', \compact('url', 'image'));
    }
}
