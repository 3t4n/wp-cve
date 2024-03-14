<?php

/**
 * Plugin Name:       Taxonomy Thumbnail and Widget
 * Plugin URI:        https://profiles.wordpress.org/sunilkumarthz/
 * Description:       Taxonomy Thumbnail and Widget plugin is used for add thumbnail option for inbuilt and custom taxonomy terms  and access them with shortcode and widget
 * Version:           1.5.0
 * Author:            Sunil Kumar Sharma
 * Author URI:        https://www.linkedin.com/in/sunilkumarthz/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') or die('No script kiddies please!');
/**
 ** Class for manage thumbnail for taxonomies
 **/
if (!class_exists('TaxonomyManagmentSystem')) {
    class TaxonomyManagmentSystem
    {
        public function __construct()
        {
            define('TTW_NAME', 'Taxonomy Thumbnail And Widget');
            define('TTW_VERSION', '1.1.0');
            define('TTWTHUMB_URL', plugin_dir_url(__FILE__) . 'img/placeholder.png');
            define('TTWFILE_PATH', plugin_dir_path(__FILE__));
            define('TTWFILE_URL', plugin_dir_url(__FILE__));
            define('TTW_ICON', plugin_dir_url(__FILE__) . 'img/ttwsetting.png');

            /**
             * Taxonomy Thumbnail and Widget Plugin backend functionality
             **/
            include('' . sprintf(TTWFILE_PATH . 'lib/%s', 'taxonomymanager.php') . '');
            /**
             * Taxonomy Thumbnail and Widget Plugin widget functionality
             **/
            include('' . sprintf(TTWFILE_PATH . 'lib/%s', 'taxonomy_widget.php') . '');
            /**
             * Taxonomy Thumbnail and Widget Plugin Shortcode functionality
             **/
            include('' . sprintf(TTWFILE_PATH . 'lib/%s', 'ttw_shortcode.php') . '');
            /**
             * Taxonomy Thumbnail and Widget Plugin global functions
             **/
            include('' . sprintf(TTWFILE_PATH . 'lib/%s', 'ttw_globaluse.php') . '');
        }

        /**
         ** Initialize the class and start calling our hooks and filters
         **/
        public function init()
        {
            add_action('wp_enqueue_scripts', array($this, 'taxonomymanager_enqueue_style'));
            add_action('admin_enqueue_scripts', array($this, 'load_taxonomymanager_wp_admin_style'));
            $getTaxData = get_option('ttw_manager_settings');
            if (!empty($getTaxData)) :
                $taxArrayData = @$getTaxData['ttw_selected_taxonomies'];
                if (($ttwkey = array_search('product_cat', $taxArrayData)) !== false) {
                    unset($taxArrayData[$ttwkey]);
                }
                if (is_array($taxArrayData)) {
                    foreach ($taxArrayData as $tax) {
                        add_filter('manage_' . trim($tax) . '_custom_column', array($this, 'ttw_manage_taxonomy_columns_data'), 15, 3);
                        add_filter('manage_edit-' . trim($tax) . '_columns', array($this, 'ttw_taxonomy_columns_list'));
                        add_action('' . trim($tax) . '_add_form_fields', array($this, 'ttw_add_category_image'), 10, 2);
                        add_action('created_' . trim($tax) . '', array($this, 'ttw_save_category_image'), 10, 2);
                        add_action('' . trim($tax) . '_edit_form_fields', array($this, 'ttw_update_category_image'), 10, 2);
                        add_action('edited_' . trim($tax) . '', array($this, 'ttw_updated_category_image'), 10, 2);
                    }
                }
            endif;
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'ttw_add_action_links'));
        }

        /**
         ** Add TTW Settings link
         **/
        public function ttw_add_action_links($links)
        {
            $mylinks = array('<a href="' . admin_url('options-general.php?page=ttw_manager') . '">TTW Settings</a>',);
            return array_merge($links, $mylinks);
        }

        /***
         **  Enqueue Scripts / styles for manage taxonomy front end widget layout
         ***/
        public function taxonomymanager_enqueue_style()
        {
            wp_enqueue_style('taxonomymanager', sprintf(TTWFILE_URL . 'css/%s', 'taxonomymanager.css'), false);
        }

        /***
         **  Enqueue Script for manage taxonomy backend
         ***/
        public function load_taxonomymanager_wp_admin_style()
        {
            if (function_exists('wp_enqueue_media')) {
                wp_enqueue_media();
            } else {
                wp_enqueue_style('thickbox');
                wp_enqueue_script('media-upload');
                wp_enqueue_script('thickbox');
            }
            wp_enqueue_style('taxonomymanager', sprintf(TTWFILE_URL . 'css/%s', 'taxonomymanager_admin.css'), true);
            wp_enqueue_style('multiple-select', sprintf(TTWFILE_URL . 'css/%s', 'multiple-select.css'), true);
            wp_enqueue_script('multiple-select', sprintf(TTWFILE_URL . 'js/%s', 'multiple-select.js'), false, array(), true);
            wp_enqueue_script('taxonomy-manager', sprintf(TTWFILE_URL . 'js/%s', 'taxonomymanager.js'), false, array(), true);
        }

        /***
         ** add taxonomy column for thumbnail
         ***/
        public function ttw_taxonomy_columns_list($original_columns)
        {
            $new_columns = $original_columns;
            array_splice($new_columns, 1);
            $new_columns['taxonomy_image'] = esc_html__('Image', 'taxonomymanager');
            return array_merge($new_columns, $original_columns);
        }

        /***
         ** Manage taxonomy thumbnail column
         ***/
        public function ttw_manage_taxonomy_columns_data($row, $column_name, $term_id)
        {
            global $taxonomy;
            if ('taxonomy_image' == $column_name) {
                $image_id = get_term_meta($term_id, 'taxonomy_thumb_id', true);
                $thumbnail = wp_get_attachment_image($image_id, array(100, 100));
                if ($thumbnail == '') {
                    return $row . "<img src='" . TTWTHUMB_URL . "' width='100px' />";
                } else {
                    return $row . $thumbnail;
                }
            }
        }

        /***
         ** Add a form field in the new taxonomy page
         ***/
        public function ttw_add_category_image($taxonomy)
        { ?>
            <div class="form-field term-group">
                <label for="taxonomy_thumb_id"><?php _e('Image', 'taxonomymanager'); ?></label>
                <input type="hidden" id="taxonomy_thumb_id" name="taxonomy_thumb_id" class="custom_media_url" value="">
                <div id="taxonomy-image-wrapper"></div>
                <p>
                    <input type="button" class="button button-secondary taxman_tax_media_button" id="taxman_tax_media_button" name="taxman_tax_media_button" value="<?php _e('Add Image', 'taxonomymanager'); ?>" />
                    <input type="button" class="button button-secondary taxman_tax_media_remove" id="taxman_tax_media_remove" name="taxman_tax_media_remove" value="<?php _e('Remove Image', 'taxonomymanager'); ?>" />
                </p>
            </div>
        <?php
        }

        /***
         ** Save the form field
         ***/
        public function ttw_save_category_image($term_id, $tt_id)
        {
            if (isset($_POST['taxonomy_thumb_id']) && '' !== intval($_POST['taxonomy_thumb_id'])) {
                $image = intval($_POST['taxonomy_thumb_id']);
                add_term_meta($term_id, 'taxonomy_thumb_id', $image, true);
            }
        }

        /***
         ** Edit the form field
         ***/
        public function ttw_update_category_image($term, $taxonomy)
        { ?>
            <tr class="form-field term-group-wrap">
                <th scope="row">
                    <label for="taxonomy_thumb_id"><?php _e('Image', 'taxonomymanager'); ?></label>
                </th>
                <td>
                    <?php $image_id = get_term_meta($term->term_id, 'taxonomy_thumb_id', true); ?>
                    <input type="hidden" id="taxonomy_thumb_id" name="taxonomy_thumb_id" value="<?php echo $image_id; ?>">
                    <div id="taxonomy-image-wrapper">
                        <?php if ($image_id) { ?>
                            <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                        <?php } ?>
                    </div>
                    <p>
                        <input type="button" class="button button-secondary taxman_tax_media_button" id="taxman_tax_media_button" name="taxman_tax_media_button" value="<?php _e('Add Image', 'taxonomymanager'); ?>" />
                        <input type="button" class="button button-secondary taxman_tax_media_remove" id="taxman_tax_media_remove" name="taxman_tax_media_remove" value="<?php _e('Remove Image', 'taxonomymanager'); ?>" />
                    </p>
                </td>
            </tr>
<?php
        }

        /***
         ** Update the form field value
         **/
        public function ttw_updated_category_image($term_id, $tt_id)
        {
            if (isset($_POST['taxonomy_thumb_id']) && '' !== intval($_POST['taxonomy_thumb_id'])) {
                $image = intval($_POST['taxonomy_thumb_id']);
                update_term_meta($term_id, 'taxonomy_thumb_id', $image);
            } else {
                update_term_meta($term_id, 'taxonomy_thumb_id', '');
            }
        }
    }

    $ttwSystem = new TaxonomyManagmentSystem();
    $ttwSystem->init();
}
