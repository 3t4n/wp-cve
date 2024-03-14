<?php
/*
Plugin Name: WP Open Graph Meta
Plugin URI: http://omaxis.de/wordpress-plugins/wp-open-graph-meta/
Description: Adds Facebook Open Graph Meta Elements to blog posts / pages to avoid no thumbnail, wrong title / description issue etc. It is compatible with the WordPress SEO plugins "wpSEO" and "All in One SEO Pack". Happy with it? Please consider a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZJP89T2XTNC5G" title="Donation via PayPal" target="_blank">donation</a> for development and support time. Thanks :)
Version: 1.1
Author: Sven Haselböck
*/

class de_omaxis_wp_open_graph_meta
{
    /**
     * Nimmt die Daten der verschiedenen Meta-Elemente auf, um diese gebündelt
     * in einer Funktion ausgeben zu können.
     *
     * @var array
     */
    protected $_metas = array();


    /**
     * Konstruktur
     */
    public function __construct()
    {
        if (is_admin()) {
            // Konfiguration via WP-Admin folgt
        } else {
            add_action('wp_head', array($this, 'add_elements'));
        }
    }

    /**
     * Erzeugt die elevanten Meta-Elemente für die Seite / den Artikel
     */
    public function add_elements()
    {
        if (is_singular()) {
            the_post();

            $this->_metas['og:title'] = $this->_get_title();
            $this->_metas['og:type'] = is_single() ? 'article' : 'website';
            $this->_metas['og:url'] = get_permalink();

            $this->_metas['og:description'] = $this->_get_description();
            $this->_metas['og:site_name'] = strip_tags(get_bloginfo('name'));
            $this->_metas['og:locale'] = strtolower(str_replace('-', '_', get_bloginfo('language')));

            $this->_add_image();
            $this->_add_post_tags();

            $this->_output();

            rewind_posts();
        }
    }

    /**
     * Gibt den Title für das Meta-Element zurück. Wenn der Title via wpSEO
     * oder All in One SEO Pack gesetzt worden ist, wird dieser bevorzugt
     *
     * @return null|string
     */
    protected function _get_title()
    {
        $title = null;

        if (class_exists('wpSEO_Base')) {
            $title = trim(get_post_meta(get_the_ID(), '_wpseo_edit_title', true));
        } else if (function_exists('aiosp_meta')) {
            $title = trim(get_post_meta(get_the_ID(), '_aioseop_title', true));
        }

        return empty($title) ? get_the_title() : $title;
    }

    /**
     * Gibt die Description für das Meta-Element zurück. Wenn die Description
     * via wpSEO oder All in One SEO Pack gesetzt worden ist, wird diese
     * bevorzugt
     *
     * @return mixed|string
     */
    protected function _get_description()
    {
        $description = null;

        if (class_exists('wpSEO_Base')) {
            $description = trim(get_post_meta(get_the_ID(), '_wpseo_edit_description', true));
        } else if (function_exists('aiosp_meta')) {
            $description = trim(get_post_meta(get_the_ID(), '_aioseop_description', true));
        }

        return empty($description) ? strip_tags(get_the_excerpt()) : $description;
    }

    /**
     * Fügt, wenn gesetzt, ein Artikelbild als Meta-Element ein.
     */
    protected function _add_image()
    {
        if (has_post_thumbnail()) {
            $this->_metas['og:image'] = wp_get_attachment_url(get_post_thumbnail_id());
        }
    }

    /**
     * Fügt, wenn gesetzt, bei einem Artikel die Schlagwörter als Meta-Element
     * ein
     */
    protected function _add_post_tags()
    {
        if (is_single()) {
            // Zeigt Warnungen an, auch wenn das Datumsformat in ISO 8601
            // übergeben wird. Fehler aktuell nicht nachvollziehbar.

            // $this->_metas['article:published_time'] = get_the_date('Y-m-d');
            // $this->_metas['article:modified_time'] = get_the_modified_date('c');

            $tags = get_the_tags();

            if (is_array($tags) && count($tags) > 0) {
                foreach ($tags as $tag) {
                    $this->_metas['article:tag'][] = $tag->name;
                }
            }
        }
    }

    /**
     * Gibt die erzeugten Meta-Elemente im Head-Bereich der Seite aus
     */
    protected function _output()
    {
        foreach ($this->_metas as $property => $content) {
            $content = is_array($content) ? $content : array($content);

            foreach ($content as $content_single) {
                echo '<meta property="' . $property . '" content="' . esc_attr(trim($content_single)) . '" />' . "\n";
            }
        }
    }
}

$de_omaxis_wp_open_graph_meta = new de_omaxis_wp_open_graph_meta();