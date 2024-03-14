<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    wugrat
 * @subpackage wugrat/public
 * @author     wupo
 */
class Wugrat_Public {

	private $plugin_name;
	private $version;
    private $taxonomy_group = 'wugrat_group';
    private $option_key_group_order = 'wugrat_group_order';
	private $option_group_order = '';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
	}

    /**
     * -
     *
     * @since    1.0.0
     */
    function wugrat_display_product_attributes( $product_attributes, $product ) {
        global $wpdb;

        $product_attributes = array();
        $attributes = array_filter($product->get_attributes(), 'wc_attributes_array_filter_visible');
        $attributes_singles = $attributes;


        // ****************
        // Group attributes
        // ****************

        // Check if attributes exists in groups and prepare all necessary data
	    $attributes_keys = array_keys($attributes);

	    foreach ($attributes_keys as $index=>$attribute_key) {
		    if (substr($attribute_key, 0, 3) !== 'pa_') {
			    unset($attributes_keys[$index]);
		    }
	    }

	    if (!empty($attributes_keys)) {
		    $attributes_name = implode(',|', $attributes_keys);
		    $attributes_name = $attributes_name.',';
	    } else {
		    $attributes_name = '';
	    }

        if (!empty($attributes_name)) {
            //TODO: refactor to method
	        $query = $wpdb->prepare("SELECT term_id, children FROM $wpdb->term_taxonomy WHERE taxonomy = %s AND children rlike %s", array($this->taxonomy_group, $attributes_name));
	        $attribute_groups = $wpdb->get_results($query);

	        $this->option_group_order = explode(',', get_option($this->option_key_group_order), -1);
            usort($attribute_groups, array( $this, 'group_table_sorting_compare' ));
        } else {
	        $attribute_groups = '';
        }

        // Organize groups and get attributes
        if (!empty($attribute_groups)) {
            foreach ($attribute_groups as $attribute_group) {
                $groupifier = rand(0, 9999);
                $attribute_group_term = get_term($attribute_group->term_id);

                $product_attribute_group = array();
                $child_attribute_names = explode(',', $attribute_group->children);
                foreach ($child_attribute_names as $child_attribute_name) {
                    if (array_key_exists($child_attribute_name, $attributes)) {
                        $product_attribute_group[$child_attribute_name] = $attributes[$child_attribute_name];
                        unset($attributes_singles[$child_attribute_name]);
                    }
                }

                foreach ($product_attribute_group as $attribute) {
                    $values = array();

                    if ($attribute->is_taxonomy()) {
                        $attribute_taxonomy = $attribute->get_taxonomy_object();
                        $attribute_values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'all'));

                        foreach ($attribute_values as $attribute_value) {
                            $value_name = esc_html($attribute_value->name);

                            if ($attribute_taxonomy->attribute_public) {
                                $values[] = '<a href="' . esc_url(get_term_link($attribute_value->term_id, $attribute->get_name())) . '" rel="tag">' . $value_name . '</a>';
                            } else {
                                $values[] = $value_name;
                            }
                        }
                    } else {
                        $values = $attribute->get_options();

                        foreach ($values as &$value) {
                            $value = make_clickable(esc_html($value));
                        }
                    }

                    $product_attributes['attribute_'.$groupifier.'_' . sanitize_title_with_dashes($attribute->get_name())] = array(
                        'label' => $attribute_group_term->name." - ".wc_attribute_label($attribute->get_name()),
                        'value' => apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values),
                    );
                }
            }
        }


        // *******************************
        // Dimension and weight attributes
        // *******************************
        $display_dimensions = apply_filters('wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions());

        if ($display_dimensions && $product->has_weight()) {
            $product_attributes['weight'] = array(
                'label' => __('Weight', 'woocommerce'),
                'value' => wc_format_weight($product->get_weight()),
            );
        }

        if ($display_dimensions && $product->has_dimensions()) {
            $product_attributes['dimensions'] = array(
                'label' => __('Dimensions', 'woocommerce'),
                'value' => wc_format_dimensions($product->get_dimensions(false)),
            );
        }


        // *****************
        // Single attributes
        // *****************
        foreach ($attributes_singles as $attribute) {
            $values = array();
            $groupifier = rand(0, 9999);

            if ($attribute->is_taxonomy()) {
                $attribute_taxonomy = $attribute->get_taxonomy_object();
                $attribute_values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'all'));

                foreach ($attribute_values as $attribute_value) {
                    $value_name = esc_html($attribute_value->name);

                    if ($attribute_taxonomy->attribute_public) {
                        $values[] = '<a href="' . esc_url(get_term_link($attribute_value->term_id, $attribute->get_name())) . '" rel="tag">' . $value_name . '</a>';
                    } else {
                        $values[] = $value_name;
                    }
                }
            } else {
                $values = $attribute->get_options();

                foreach ($values as &$value) {
                    $value = make_clickable(esc_html($value));
                }
            }

            $product_attributes['attribute_'.$groupifier.'_' . sanitize_title_with_dashes($attribute->get_name())] = array(
                'label' => wc_attribute_label($attribute->get_name()),
                'value' => apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values),
            );
        }

        //wupo_log('Product attributes', $product_attributes);

        return $product_attributes;
    }

    /**
     * -
     *
     * @since    1.0.0
     */
    function group_table_sorting_compare($a, $b) {
        $option_group_order = explode(',', get_option($this->option_key_group_order), -1);

        if ($a == $b) {
            return 0;
        }

        $cmpa = array_search($a->term_id, $this->option_group_order);
        $cmpb = array_search($b->term_id, $this->option_group_order);

        return ($cmpa > $cmpb) ? 1 : -1;
    }
}