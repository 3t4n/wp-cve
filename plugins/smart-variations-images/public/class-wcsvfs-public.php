<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.smart-variations.com
 * @since      1.0.0
 *
 * @package    Wcsvfs
 * @subpackage Wcsvfs/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wcsvfs
 * @subpackage Wcsvfs/public
 * @author     David Rosendo <david@rosendo.pt>
 */
class Wcsvfs_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The single instance of the class
     *
     * @var Wcsvfs_Public
     */
    protected static $instance = null;

    /**
     * Main instance
     *
     * @return Wcsvfs_Public
     */
    public static function instance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wcsvfs_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wcsvfs_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wcsvfs-public' . SMART_SCRIPT_DEBUG . '.css', array(), $this->version, 'all');
    }

    /**
     * Filter function to add swatches bellow the default selector
     *
     * @param $html
     * @param $args
     *
     * @return string
     */
    public function get_swatch_html($html, $args)
    {
        $swatch_types = WC_SVFS()->types;
        $attr = WC_SVFS()->get_tax_attribute($args['attribute']);
        // Return if this is normal attribute
        if (empty($attr)) {
            return $html;
        }

        if (!array_key_exists($attr->attribute_type, $swatch_types)) {
            return $html;
        }

        $options = $args['options'];
        $product = $args['product'];
        $attribute = $args['attribute'];
        $class = "variation-selector variation-select-{$attr->attribute_type}";
        $swatches = '';
        if (empty($options) && !empty($product) && !empty($attribute)) {
            $attributes = $product->get_variation_attributes();
            $options = $attributes[$attribute];
        }

        if (array_key_exists($attr->attribute_type, $swatch_types)) {
            if (!empty($options) && $product && taxonomy_exists($attribute)) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms($product->get_id(), $attribute, array('fields' => 'all'));

                foreach ($terms as $term) {
                    if (in_array($term->slug, $options)) {
                        $swatches .= apply_filters('wcsvfs_swatch_html', '', $term, $attr->attribute_name, $attr->attribute_type, $args);
                    }
                }
            }

            if (!empty($swatches)) {
                $class .= ' wcsvfs-hidden';
 
                $swatches = '<div class="wcsvfs-swatches wcsvfs-swatches-' . $attr->attribute_type . '" data-attribute_name="attribute_' . esc_attr($attribute) . '">' . $swatches . '</div>';
                $html = '<div class="' . esc_attr($class) . '">' . $html . '</div>' . $swatches;
            }
        }

        return $html;
    }

    /**
     * Print HTML of a single swatch
     *
     * @param $html
     * @param $term
     * @param $type
     * @param $args
     *
     * @return string
     */
    public function swatch_html($html, $term, $tax, $type, $args = false, $image = '')
    {
        $selected = '';
        if (isset($_GET['filter_' . $tax]) && $_GET['filter_' . $tax] != '') {
            $taxonomies = explode(',', $_GET['filter_' . $tax]);

            $selected = (in_array($term->slug, $taxonomies)) ? 'selected' : '';
        }
        if ($args) {
            $selected = (sanitize_title($args['selected']) == $term->slug) ? 'selected' : '';
        }

        $name = esc_html(apply_filters('woocommerce_variation_option_name', $term->name));

        if ($image) {
            $image = json_encode($image);
        }

        switch ($type) {
            case 'color':
                $color = get_term_meta($term->term_id, 'color', true);
                list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
                $html = sprintf(
                    '<span data-svfs=\'%s\' class="swatch swatch-color swatch-%s %s" style="background-color:%s;color:%s;" title="%s" data-value="%s">%s</span>',
                    $image,
                    esc_attr($term->slug),
                    $selected,
                    esc_attr($color),
                    "rgba($r,$g,$b,0.5)",
                    esc_attr($name),
                    esc_attr($term->slug),
                    $name
                );
                break;

            case 'image':
                $image = get_term_meta($term->term_id, 'image', true);
                $image = $image ? wp_get_attachment_image_src($image) : '';
                $image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
                $html = sprintf(
                    '<span data-svfs=\'%s\' class="swatch swatch-image swatch-%s %s" title="%s" data-value="%s"><img src="%s" alt="%s"></span>',
                    $image,
                    esc_attr($term->slug),
                    $selected,
                    esc_attr($name),
                    esc_attr($term->slug),
                    esc_url($image),
                    esc_attr($name)
                );
                break;

            case 'label':
                $label = get_term_meta($term->term_id, 'label', true);
                $label = $label ? $label : $name;
                $html = sprintf(
                    '<span data-svfs=\'%s\' class="swatch swatch-label swatch-%s %s" title="%s" data-value="%s">%s</span>',
                    $image,
                    esc_attr($term->slug),
                    $selected,
                    esc_attr($name),
                    esc_attr($term->slug),
                    esc_html($label)
                );
                break;
        }

        return $html;
    }

}
