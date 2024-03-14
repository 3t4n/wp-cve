<?php
/*
Plugin Name: EDD custom checkout fields
Description: Add custom fields to edd checkout form
Plugin URI: http://wp-master.ir
Author: Omid Shamlu
Author URI: http://wp-master.ir
Version: 1.4.1
License: GPL2
Text Domain: ecf
 */

/**
 * Changes:
 * 1.4.1  : fixed not working in new EDD version
 */
defined('ABSPATH') or die('No script kiddies please!');

$defs = array(
    'ecf_url' => plugin_dir_url(__FILE__),
    'ecf_dir' => plugin_dir_path(__FILE__),
);
foreach ($defs as $def_name => $def_val) {
    define($def_name, $def_val);
}


/**
 * wp-master.ir class to handle custom checkout fields for edd
 */
class wpm_edd_custom_fields
{
    private static $instance = null;
    public $fields = array();
    public $prefix = 'edd-';


    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct()
    {
        /**
         * is EDD active?
         */
        if (!function_exists('edd_is_test_mode')) {
            return;
        }

        load_plugin_textdomain('ecf', false, dirname(plugin_basename(__FILE__)) . '/languages');
        __('EDD custom checkout fields', 'ecf');
        __('Add custom fields to edd checkout form', 'ecf');
        /**
         * admin menu
         */
        add_action('admin_menu', array($this, '_admin_menu'));

    }


    public function _admin_menu()
    {
        add_submenu_page('edit.php?post_type=download', __('checkout fields', 'ecf'), __('checkout fields', 'ecf'), 'manage_options', 'edd-custom-fields', array($this, 'admin_menu'));
    }

    public function admin_menu()
    {
        require_once ecf_dir . 'class-fields-generator.php';
        ?>
        <div class="wrap">
            <?php
            ecf_fields_generator::get_instance('_ecf_custom_fields');
            ?>
        </div>
        <?php
    }

    public function make_fields($fields, $prefix = 'edd-')
    {
        if (empty($fields)) {
            return;
        }

        if (!empty($prefix)) {
            $this->prefix = $prefix;
        }
        $this->fields = $fields;
        add_action('edd_purchase_form_user_info_fields', array($this, 'wpm_edd_display_checkout_fields'));
        add_filter('edd_purchase_form_required_fields', array($this, 'wpm_edd_required_checkout_fields'));
        add_action('edd_checkout_error_checks', array($this, 'wpm_edd_validate_checkout_fields'), 10, 2);
        add_filter('edd_payment_meta', array($this, 'wpm_edd_store_custom_fields'));
        add_action('edd_payment_view_details', array($this, 'wpm_edd_view_order_details'), 999, 1);
        add_action('edd_payment_receipt_after', array($this, 'wpm_edd_view_order_details_td'), 999, 1);
        add_action('edd_add_email_tags', array($this, 'wpm_edd_add_sample_email_tag'), 999);
        add_action('edd_insert_payment', array($this, 'wpm_edd_insert_payment'), 10, 2);
        /**
         * Add fields to payment history tables
         */
        add_filter('edd_payments_table_columns', array($this, 'edd_payments_table_columns'), 10);
        add_filter('edd_payments_table_column', array($this, 'edd_payments_table_column'), 10, 3);

    }

    public function wpm_edd_display_checkout_fields()
    {
        $fields = $this->fields;
        foreach ($fields as $field) {
            extract($field);
            if (strpos($id, 'ecf_') === false) {
                $id = $id;
            } else {
                $id = 'edd_' . $this->prefix . $id;
            }
            $classes = [];
            if (isset($required) && (strpos($required, 'required') !== false)) {
                $classes[] = 'required';
            }
            if ($type == 'select') {
                $classes[] = 'edd-select';
            }

            $attr .= ' class="' . implode(' ', $classes) . ' edd-input edd-custom-checkout-fields edd-custom-checkout-fields-' . $type . '" ';

            echo '
                <p>
                <label class="edd-label" for="edd-' . $id . '">' . $title . $required_sign . ' </label>
                <span class="edd-description">' . (isset($desc) ? $desc : '') . '</span>';


            switch ($type) {
                /**
                 *  Fields
                 */
                case 'text':
                    echo '<input ' . $attr . ' value="' . (isset($value) ? $value : '') . '"  type="text" name="' . $id . '" id="' . $id . '">';
                    break;
                case 'multi_line_text':
                    echo '<textarea ' . $attr . ' type="text" name="' . $id . '" id="' . $id . '">' . (isset($value) ? $value : '') . '</textarea>';
                    break;
                case 'checkbox':
                    echo '<input ' . $attr . ' type="checkbox" name="' . $id . '" value="1" id="' . $id . '">';
                    break;
                case 'paragraph':
                    echo '<p class="ecf_full_centered ' . $id . '">' . $_ecf_custom_fields['paragraph_content'][$index] . '</p>';
                    break;
                case 'select':
                    $has_selected = false;
                    $has_selected_item = false;
                    if (isset($_GET['ecf_f' . $index]) && in_array($_GET['ecf_f' . $index], $_ecf_custom_fields['combobox_choices'][$index])) {
                        $has_selected = true;
                        $has_selected_item = htmlspecialchars($_GET['ecf_f' . $index]);
                    }
                    echo '<select ' . $attr . ' name="' . $id . '" id="' . $id . '">';
                    echo '<option>---' . __('select one', 'ecf') . '---</option>';
                    foreach ($_ecf_custom_fields['combobox_choices'][$index] as $combobox_choice) {
                        echo '<option ' . (($has_selected) ? selected($has_selected_item, $combobox_choice, false) : '') . ' value="' . $combobox_choice . '">' . $combobox_choice . '</option>';
                    }
                    echo '</select>';
                    break;
                default:
                    echo '<input ' . $attr . ' type="text" name="' . $id . '" id="' . $id . '">';

            }


            echo '
            </p>
            ';

        }

    }

    /**
     * Make phone number required
     * Add more required fields here if you need to
     */
    public function wpm_edd_required_checkout_fields($required_fields)
    {
        $fields = $this->fields;
        foreach ($fields as $field) {
            extract($field);
            if (isset($required) && $required == 1) {
                if (strpos($id, 'ecf_') === false) {
                    $id = $id;
                } else {
                    $id = 'edd_' . $this->prefix . $id;
                }
                $error = empty($error) ? __('Please check out this field', 'ecf') . ':' . $title : $error;

                $required_fields[$id] = array(
                    'error_id' => 'invalid_' . $id,
                    'error_message' => $error,
                );
            }
        }

        return $required_fields;
    }

    /**
     * Set error if phone number field is empty
     * You can do additional error checking here if required
     */
    public function wpm_edd_validate_checkout_fields($valid_data, $data)
    {
        $fields = $this->fields;
        $errors = array();

        foreach ($fields as $field) {
            extract($field);

            if (strpos($id, 'ecf_') === false) {
                $id = $id;
            } else {
                $id = 'edd_' . $this->prefix . $id;
            }
            $_submitted_name = $id;

            /**
             * Validation ** Required?
             */
            $required = false;
            if (isset($_ecf_custom_fields['required'][$index]) && $_ecf_custom_fields['required'][$index] == 1 && (isset($_ecf_custom_fields['disable'][$index]) && $_ecf_custom_fields['disable'][$index] != 1) && $_ecf_custom_fields['type'][$index] != 'paragraph') {
                $required = true;
            }


            if ($required) {
                if (!isset($_POST[$_submitted_name]) || empty($_POST[$_submitted_name])) {
                    $errors[$id] = __('Please Fill This Field:', 'ecf') . ' <i>' . $_ecf_custom_fields['name'][$index] . '</i>';
                }
            }

            /**
             * Validation ** Correct Data
             */
            if ($type == 'select' && isset($_POST[$_submitted_name])) {
                if (!in_array($_POST[$_submitted_name], $_ecf_custom_fields['combobox_choices'][$index])) {
                    $errors[$id] = __('Please Check This Field Value:', 'ecf') . ' <i>' . $_ecf_custom_fields['name'][$index] . '</i>';
                }
            }


        }
        if (!empty($errors)) {
            foreach ($errors as $field_id => $error) {
                edd_set_error('invalid_' . $field_id, $error);

            }

        }


    }

    /**
     * Store the custom field data into EDD's payment meta
     */
    public function wpm_edd_store_custom_fields($payment_meta)
    {
        if (did_action('edd_purchase')) {
            $fields = $this->fields;
            foreach ($fields as $field) {
                extract($field);
                if (strpos($id, 'ecf_') === false) {
                    $id = $id;
                } else {
                    $id = 'edd_' . $this->prefix . $id;
                }
                $payment_meta[$id] = isset($_POST[$id]) ? sanitize_text_field($_POST[$id]) : '';
            }
        }
        return $payment_meta;
    }


    /**
     * Add the fields to the "Payment Confirmation" page
     */
    public function wpm_edd_view_order_details_td($payment)
    {
        return $this->wpm_edd_view_order_details($payment, true);
    }

    /**
     * Add the fields to the "View Order Details" page
     */
    public function wpm_edd_view_order_details($payment, $td = null)
    {
        $payment_id = $payment;
        if (is_object($payment)) {
            $payment_id = $payment->ID;
        }
        $payment_meta = (edd_get_payment_meta($payment_id));

        $_payment_meta = get_post_meta($payment_id, '_wpm_edd_custom_fields');


        if ($td) {
            echo '
            <tr>
				<td colspan="2"><strong>' . __('Other Information', 'ecf') . '</strong></td>  
			</tr>';
        } else {
            echo '<strong>' . __('Other Information', 'ecf') . '</strong>';
            echo '<hr>';
        }


        $fields = $this->fields;
        foreach ($fields as $field) {
            extract($field);
            if ($type == 'paragraph') {
                continue;
            }
            if (strpos($id, 'ecf_') === false) {
                $id = $id;
            } else {
                $id = 'edd_' . $this->prefix . $id;
            }
            $_val = isset($payment_meta[$id]) ? $payment_meta[$id] : '';
            if (is_array($_payment_meta) && isset($_payment_meta[$id])) {
                $_val = empty($_payment_meta[$id]) ? '-' : $_payment_meta[$id];

            }
            if ($type == 'checkbox' && !empty($_val)) {
                $_val = '✅';

            }
            if (empty($_val)) {
                $_val = '-';
            }


            if ($td) {

                echo '
            			<tr>
				<td><strong>' . $title . ':</strong></td>
				<td>' . $_val . '</td>
			</tr>';
            } else {
                echo '
            <div class="column-container">
                <div class="column">
                    <strong>' . $title . ': </strong>
                    ' . $_val . '
                </div>
            </div>
            ';
            }


        }

    }

    public function wpm_edd_add_sample_email_tag()
    {
        $fields = $this->fields;

        try {
            foreach ($fields as $field) {
                extract($field);
                if (strpos($id, 'ecf_') === false) {
                    $id = $id;
                } else {
                    $id = 'edd_' . $this->prefix . $id;
                }
                edd_add_email_tag($id, $title, function ($payment_id) use ($id) {
                    $payment_data = edd_get_payment_meta($payment_id);
                    return $payment_data[$id];
                });
            }
        } catch (Exception $e) {
            //Nothing
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

    }

    /**
     * ensure data store while some plugins override added meta values
     */
    public function wpm_edd_insert_payment($payment_id, $payment_data)
    {
        $payment_meta = (edd_get_payment_meta($payment_id));
        $_payment_meta = array();
        $fields = $this->fields;
        foreach ($fields as $field) {
            extract($field);
            if (strpos($id, 'ecf_') === false) {
                $id = $id;
            } else {
                $id = 'edd_' . $this->prefix . $id;
            }
            if (isset($payment_meta[$id])) {
                $_payment_meta[$id] = $payment_meta[$id];
            }
        }
        if (!empty($_payment_meta)) {
            update_post_meta($payment_id, '_wpm_edd_custom_fields', $_payment_meta);
        }

    }

    public function edd_payments_table_columns($columns)
    {
        $fields = $this->fields;
        foreach ($fields as $field) {
            extract($field);
            if (strpos($id, 'ecf_') === false) {
                $id = $id;
            } else {
                $id = 'edd_' . $this->prefix . $id;
            }
            if ((isset($_ecf_custom_fields['show_admin'][$index]) && $_ecf_custom_fields['show_admin'][$index] == 1)) {
                $columns [$id] = $title;
            }
        }
        return $columns;
    }

    public function edd_payments_table_column($column_value, $payment_id, $column_name)
    {
        $payment_meta = (edd_get_payment_meta($payment_id));
        if (isset($payment_meta[$column_name])) return $payment_meta[$column_name];
        return $column_value;

    }

}

add_action('plugins_loaded', 'load_wpm_edd_custom_fields');
function load_wpm_edd_custom_fields()
{

    $ecf = wpm_edd_custom_fields::get_instance();
    $_ecf_custom_fields = get_option('_ecf');
    unset($_ecf_custom_fields['last_saved']);
    if (is_array($_ecf_custom_fields) && !empty($_ecf_custom_fields)) {
        $_fields = array();
        foreach ($_ecf_custom_fields['type'] as $index => $value) {
            if (empty($_ecf_custom_fields['name'][$index])) continue;
            $value = '';
            $readonly = '';
            $required = '';
            $required_sign = '';
            $placeholder = '';
            $attr = [];

            // default value for price
            if (isset($_ecf_custom_fields['default'][$index])) {
                $value = $_ecf_custom_fields['default'][$index];
            }

            // default value for text  /  multi line text
            if (($_ecf_custom_fields['type'][$index] == 'text' || $_ecf_custom_fields['type'][$index] == 'multi_line_text') && isset($_ecf_custom_fields['text_default'][$index])) {
                $value = $_ecf_custom_fields['text_default'][$index];
            }


            // readonly ?
            if (isset($_ecf_custom_fields['readonly']) && isset($_ecf_custom_fields['readonly'][$index]) && $_ecf_custom_fields['readonly'][$index] == 1) {
                $readonly = ' readonly="readonly" ';

            }

            //required ?
            if ((isset($_ecf_custom_fields['required'][$index]) && $_ecf_custom_fields['required'][$index] == 1)) {
                $required = ' required="required" ';
            }

            if (isset($_ecf_custom_fields['disable'][$index]) && $_ecf_custom_fields['disable'][$index] == 1) {
                continue;
            }

            // required star asterisk
            if ($_ecf_custom_fields['type'][$index] != 'paragraph') {
                if ($required) {
                    $required_sign = '<span class="edd-required-indicator" style="/*color:red;font-weigth:bold;*/">*</span>';
                } else {
                    $required_sign = ' <small style="color:green;font-weigth:bold;">' . __('Optional', 'ecf') . '</small>';
                }
            }

            $placeholder = 'placeholder="' . esc_attr($_ecf_custom_fields['name'][$index]) . '"';
            $attr[] = $readonly;
            $attr[] = $required;
            $attr[] = $placeholder;
            $attr = implode(' ', $attr);


            $_fields[] =
                array(
                    'id' => (isset($_ecf_custom_fields['f_id'][$index]) && !empty($_ecf_custom_fields['f_id'][$index])) ? $_ecf_custom_fields['f_id'][$index] : 'ecf_' . $index,
                    'title' => $_ecf_custom_fields['name'][$index],
                    'attr' => $attr,
                    'required_sign' => $required_sign,
                    'desc' => $_ecf_custom_fields['desc'][$index],
                    'type' => $_ecf_custom_fields['type'][$index], //text
                    'error' => '', //
                    '_ecf_custom_fields' => $_ecf_custom_fields, //
                    'index' => $index, //
                );


            if (!empty($_fields)) {
                $ecf->make_fields($_fields);
            }
        }
    }

}