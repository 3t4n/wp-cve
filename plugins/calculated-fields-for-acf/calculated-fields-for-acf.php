<?php

/**
 * @since             1.0.0
 * @package           Calculated fields for ACF
 *
 * @wordpress-plugin
 * Plugin Name:       Calculated fields for ACF
 * Plugin URI:        https://www.wundermatics.com/product/calculated-fields-for-acf
 * Description:       Simple field math for Advanced Custom Fields
 * Version:           1.3.2
 * Author:            Wundermatics
 * Author URI:        https://wundermatics.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       calculated-fields-for-acf
 * Domain Path:       /languages
 */

$calculated_fields_for_acf_version = '1.3.2';
$dependencies = [];

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
$evaluator = null;
$fields = null;

add_action('admin_enqueue_scripts', 'calculated_fields_admin_scripts');
add_action('acf/enqueue_scripts', 'calculated_fields_admin_scripts');
add_action('acf/render_field', 'calculated_fields_render_field');

add_action('plugins_loaded', 'calculated_fields_i18n');
add_action('wp_ajax_calculated_field_update', 'calculated_field_ajax_update', 10);
add_action('wp_ajax_nopriv_calculated_field_update', 'calculated_field_ajax_update', 10);

add_filter('acf/update_value', 'calculated_fields_update_value', 10, 3);
add_action('acf/render_field_settings', 'calculated_fields_render_field_settings', 10, 1);
add_action('acf/render_fields', 'calculated_fields_render', 11, 2);

/**
 * Renders extra input fields on ACF edit screen
 *
 * @param $field
 */
function calculated_fields_render_field_settings($field)
{
    acf_render_field_setting(
        $field,
        [
            'label'         => __('Formula', 'calculated-fields-for-acf'),
            'instructions'  => __(
                'Simple math expression. Refer to other fields using their field name',
                'calculated-fields-for-acf'
            ),
            'name'          => 'formula',
            'type'          => 'text',
            'ui'            => 1,
        ],
        true
    );

    acf_render_field_setting(
        $field,
        [
            'label'         => __('Number format', 'calculated-fields-for-acf'),
            'instructions'  => __(
                'Formula field output format',
                'calculated-fields-for-acf'
            ),
            'name'          => 'calculated_format',
            'type'          => 'text',
            'ui'            => 1,
        ],
        true
    );

    acf_render_field_setting(
        $field,
        [
            'label'         => __('Blank if zero', 'calculated-fields-for-acf'),
            'instructions'  => __(
                'Set the field to blank if the formula calcluation return zero',
                'calculated-fields-for-acf'
            ),
            'name'          => 'blank_if_zero',
            'type'          => 'true_false',
            'ui'            => 1,
        ],
        true
    );

    acf_render_field_setting(
        $field,
        [
            'label'         => __('Read only', 'calculated-fields-for-acf'),
            'instructions'  => __('Make this field read only', 'calculated-fields-for-acf'),
            'name'          => 'readonly',
            'type'          => 'true_false',
            'ui'            => 1,
            'layout'        =>  'horizontal',
        ]
    );
}

function calculated_fields_render_field($field)
{
    if ($field['_name'] === 'calculated_format') {
        echo '<p>';
        _e('Optionally format the output using PHP numeric formatting syntax.', '');
        echo '<br><a target="_blank" href="https://www.wundermatics.com/docs/cfacf-output-formatting/?utm_source=dashboard&utm_medium=settings&utm_campaign=installed_users">';
        _e('Read more about formatting rules here');
        echo "</a></p>";
    }
}

/**
 * Load text domain
 */
function calculated_fields_i18n()
{
    load_plugin_textdomain('calculated-fields-for-acf');
}

/**
 * Called from ACF save_post action. Once per field.
 *
 * @param $value
 * @param $postId
 * @param $field
 * @return |null
 */
function calculated_fields_update_value($value, $postId, $field)
{
    global $evaluator, $fields;

    if (strpos($postId, 'block_') === 0) {
        return $value;
    }

    if (isset($field['formula']) && strlen($field['formula']) > 0) {
        if (is_null($evaluator)) {
            $fields = new CalculatedFields\Fields();
            $fields->init();
            $evaluator = new CalculatedFields\Evaluator();
            $evaluator->init($fields->getFields());
        }

        $value = $evaluator->getField($field);
    }
    return $value;
}

/**
 * Called when a change in a dependency field is detected on the
 * client side. Recalculates all fields and returns an array
 * of fields with a changed value.
 */
function calculated_field_ajax_update()
{
    $fields = new CalculatedFields\Fields();
    $fields->ajaxInit();
    $evaluator = new CalculatedFields\Evaluator();
    $evaluator->init($fields->getFields());

    // Send back an array of fieldId and value for changed fields
    echo json_encode($evaluator->getUpdatedFields());
    wp_die();
}

/**
 * Enqueue our javascript
 */
function calculated_fields_admin_scripts()
{
    global $calculated_fields_for_acf_version;
    $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    wp_enqueue_script(
        'calculated-fields',
        plugin_dir_url(__FILE__) . "admin/assets/calculated-fields-for-acf{$min}.js",
        [],
        $calculated_fields_for_acf_version,
        true
    );
}

/**
 * Called when an ACF field group is rendered. Examines the fields
 * and sends array of dependency fields to the client side via
 * localize_script
 *
 * @param $renderedFields
 * @param $postId
 */
function calculated_fields_render($renderedFields, $postId)
{
    global $dependencies;

    $fields = new \CalculatedFields\Fields();
    $newDependencies = array_merge($dependencies, $fields->getDependantFields($renderedFields));
    $data = [
        'dependencies' => $newDependencies,
        'ajaxurl' => admin_url('admin-ajax.php'),
    ];

    calculated_fields_admin_scripts();
    wp_localize_script('calculated-fields', 'CalculatedFields', $data);

    global $wp_scripts;
    $output = $wp_scripts->get_data('calculated-fields', 'data');

    if (isset($_POST['action']) && $_POST['action'] === 'acf/ajax/fetch-block') {
        if (count($newDependencies) !== count($dependencies)) {
            global $wp_scripts;
            $wp_scripts->
            $output = $wp_scripts->get_data('calculated-fields', 'data');
            printf(
                "<script type='text/javascript'>\n%s\n%s\n</script>\n",
                esc_js($output),
                "initDependencies();"
            );
        }
    }

    $dependencies = $newDependencies;
}
