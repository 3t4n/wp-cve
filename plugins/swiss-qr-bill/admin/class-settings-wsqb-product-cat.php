<?php

/**
 * Add extra checkbox setting option to product category page
 *
 * @since      1.0.0
 *
 * @package    WC_Swiss_Qr_Bill
 * @subpackage WC_Swiss_Qr_Bill/admin
 */
class WSQB_Settings_Product_Cat {

    public function __construct() {

        add_action('product_cat_add_form_fields', array($this, 'add_settings'), 80, 1);
        add_action('product_cat_edit_form_fields', array($this, 'edit_settings'), 80, 2);

        add_action('created_product_cat', array($this, 'save_settings'), 80, 2);
        add_action('edited_product_cat', array($this, 'save_settings'), 80, 2);
    }

    /**
     * Add checkbox field for add
     * @param $taxonomy
     */
    public function add_settings( $taxonomy ) {
        ?>
        <div class="form-field term-group">
            <label for="wsqb_activate_gateway">
                <?php _e('Activate Swiss QR bill payments', 'swiss-qr-bill'); ?>
                <input type="checkbox" id="wsqb_activate_gateway" name="wsqb_activate_gateway" checked value="yes"/>
                <?php wp_nonce_field('wsqb_activate_gateway_nonce', 'wsqb_activate_gateway_nonce'); ?>
            </label>
        </div>

        <?php
    }

    /**
     * Edit checkbox field for product category
     * @param $term
     * @param $taxonomy
     */
    function edit_settings( $term, $taxonomy ) {
        $show_category = get_term_meta($term->term_id, 'wsqb_activate_gateway', true);
        ?>

        <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="wsqb_activate_gateway"><?php _e('Activate Swiss QR bill payments', 'swiss-qr-bill'); ?></label>
        </th>
        <td>
            <input type="checkbox" id="wsqb_activate_gateway" name="wsqb_activate_gateway"
                   value="yes" <?php echo $show_category == '' ? 'checked' : ($show_category ? checked($show_category, 'yes') : ''); ?>/>
            <?php wp_nonce_field('wsqb_activate_gateway_nonce', 'wsqb_activate_gateway_nonce'); ?>
        </td>

        </tr><?php
    }

    /**
     * Save term meta
     * @param $term_id
     * @param $tag_id
     */
    function save_settings( $term_id, $tag_id ) {
        if ( !isset($_POST['wsqb_activate_gateway_nonce'])
            || !wp_verify_nonce($_POST['wsqb_activate_gateway_nonce'], 'wsqb_activate_gateway_nonce')
        ) {
            return false;
        }
        if ( isset($_POST['wsqb_activate_gateway']) ) {
            update_term_meta($term_id, 'wsqb_activate_gateway', 'yes');
        } else {
            update_term_meta($term_id, 'wsqb_activate_gateway', 'no');
        }
    }
}
