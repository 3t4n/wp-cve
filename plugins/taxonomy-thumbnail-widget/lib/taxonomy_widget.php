<?php
add_action('widgets_init', create_function('', 'return register_widget("TaxonomyTermList");'));

/**
 * Adds TaxonomyTermList widget.
 **/
class TaxonomyTermList extends WP_Widget
{
    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'TaxonomyTermList', __('Taxonomy Term List', 'taxonomymanager'),
            array('description' => __('Taxonomy term list with thumbnail', 'taxonomymanager'),)
        );
    }

    /**
     * Front-end display of widget.
     **/
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        $catArgs = array(
            'taxonomy' => $instance['chooseTax'],
            'hide_empty' => ($instance['hideEmpty'] == 'hide') ? true : false,
            'exclude' => $instance['excludeTerm'],
            'number' => 0,
        );
        $categoryList = get_terms($catArgs);
        if (is_array($categoryList) && (count($categoryList) > 0)) {
            $taxTermData = "<ul class='tax-term-container in_sidebar'>";
            foreach ($categoryList as $cat) {
                $termid = intval($cat->term_id);
                $taxonomyName = wp_kses_data($cat->taxonomy);
                $termname = wp_kses_data($cat->slug);
                if ($taxonomyName == 'product_cat') {
                    $image_id = get_woocommerce_term_meta($termid, 'thumbnail_id', true);
                } else {
                    $image_id = get_term_meta($termid, 'taxonomy_thumb_id', true);
                }
                $thumbnailSrc = wp_get_attachment_image_src($image_id, 'full');
                $taxonomyName = $cat->taxonomy;
                $term_Name = $cat->slug;
                $getTermLink = get_term_link($termid, $taxonomyName);

                if (class_exists('WooCommerce')) {
                    $wootaxonomies = wc_get_attribute_taxonomies();
                    if (is_array($wootaxonomies)) {
                        foreach ($wootaxonomies as $wootax) {
                            $taxname = wc_attribute_taxonomy_name($wootax->attribute_name);
                            if ($taxonomyName == $taxname) {
                                global $post;
                                $optionData = get_option('ttw_manager_settings');
                                $postid = $optionData['ttw_woo_attribute_page'];
                                $pageLink = get_permalink($postid);
                                $queryData = base64_encode(json_encode(
                                    array(
                                        'wooAttrTax' => $taxonomyName,
                                        'wooAttrTrm' => $term_Name,
                                        'wooAttrID' => $termid,
                                    )
                                ));
                                $getTermLink = add_query_arg(array('ttwAttr' => urlencode($queryData)), $pageLink);
                            }
                        }
                    }
                }
                $thumbnailSrc = ($thumbnailSrc == '') ? TTWTHUMB_URL : $thumbnailSrc[0];
                $taxTermData .= '<li class="tax-item term-' . $termid . '"><a href="' . $getTermLink . '" title="' . $termname . '"><div class="img-container"><img src="' . $thumbnailSrc . '"></div><div class="data-container"><span class="tax-title">' . $termname . '</span></div></a></li>';
            }
            $taxTermData .= '</ul>';
        }
        echo $taxTermData;
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     **/
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('', 'taxonomymanager');
        $hideEmpty = !empty($instance['hideEmpty']) ? $instance['hideEmpty'] : '';
        $excludeTerm = !empty($instance['excludeTerm']) ? $instance['excludeTerm'] : '';
        $chooseTax = !empty($instance['chooseTax']) ? $instance['chooseTax'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e(esc_attr('Title:')); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <?php
            $getTaxData = get_option('ttw_manager_settings');
            if (!empty($getTaxData)) {
                ?>
                <label for="<?php echo esc_attr($this->get_field_id('chooseTax')); ?>"><?php _e(esc_attr('Choose Taxonomy:')); ?></label>
                <?php
                $taxArrayData = $getTaxData['ttw_selected_taxonomies'];
                if (is_array($taxArrayData)) {
                    $selectedTax = esc_attr($chooseTax);
                    ?>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('chooseTax')); ?>"
                        name="<?php echo esc_attr($this->get_field_name('chooseTax')); ?>">
                    <?php
                    foreach ($taxArrayData as $tax) {
                        ?>
                        <option value="<?php echo $tax; ?>" <?php if ($selectedTax == $tax) {
                            echo 'selected="selected"';
                        } ?>><?php echo $tax; ?></option>
                        <?php
                    }
                    ?></select><?php
                }
            } else {
                echo '<em>Please select <a href="' . admin_url('options-general.php?page=ttw_manager') . '" title="TTW Settings">Taxonomies<a> first</em>';
            }
            ?>
        </p>


        <p>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('hideEmpty')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('hideEmpty')); ?>" type="checkbox"
                   value="hide" <?php if ($hideEmpty == 'hide') {
                echo 'checked';
            } ?> >
            <label for="<?php echo esc_attr($this->get_field_id('hideEmpty')); ?>"><?php _e(esc_attr('Hide Empty')); ?></label>
        </p>
        <p>
        <hr/>
        <label for="<?php echo esc_attr($this->get_field_id('excludeTerm')); ?>">
            <?php _e(esc_attr('Exclude Terms')); ?>
        </label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id('excludeTerm')); ?>"
               name="<?php echo esc_attr($this->get_field_name('excludeTerm')); ?>" type="text"
               value="<?php echo $excludeTerm; ?>">
        <br/>
        <small>(input comma seprated term id's)</small>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     **/
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['chooseTax'] = (!empty($new_instance['chooseTax'])) ? strip_tags($new_instance['chooseTax']) : '';
        $instance['hideEmpty'] = (!empty($new_instance['hideEmpty'])) ? strip_tags($new_instance['hideEmpty']) : '';
        $instance['excludeTerm'] = (!empty($new_instance['excludeTerm'])) ? strip_tags($new_instance['excludeTerm']) : '';
        return $instance;
    }
}

?>