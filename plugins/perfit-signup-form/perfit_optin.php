<?php
/*
Plugin Name: Perfit Optin
Plugin URI: https://docs.myperfit.com/integraciones/wordpress-plugin
Description: Plugin para suscribir contactos desde tu sitio WordPress a tus listas de Perfit Email Marketing.
Author: Perfit dev team
Version: 2.0.1
Author URI: https://www.myperfit.com
*/

include(dirname(__FILE__) . '/includes/loader.php');

// Saco el primer caracter '#' para que no pinche ¯\_(ツ)_/¯
$colors = array(
    'button-bg' => substr(get_option('perfit-optin-button-bg', '#00AEE8'),1),
    'button-text' => substr(get_option('perfit-optin-button-text', '#FFFFFF'),1),
    'form-bg' => substr(get_option('perfit-optin-form-bg', '#FFFFFF'),1),
    'form-text' => substr(get_option('perfit-optin-form-text', '#696969'),1)
);

wp_enqueue_style('perfit-optin-default',  add_query_arg($colors, plugins_url('/css/perfit-optin.css.php', __FILE__)));

/*
 * Process login action
 */
if ($_POST['login'] == 1) {
    $perfit->apiKey($_POST["apiKey"]);
    $response = $perfit->optins->params(array('fields' => 'subscriptions'))->limit(1)->get();
    $_SESSION["account"] = $perfit->account();
    if (!$response->success) {
        $error = $response->error->userMessage;
        $_SESSION['error'] = $error;
    } else {
        add_option("api_key_perfit", $_POST["apiKey"]);
        $_SESSION['account'] = $return->data->account;
    }
}

/*
 * Delete api key
 */
if ($_POST['reset'] == 1) {
    delete_option("api_key_perfit");
}

/*
 * Process colors action
 */
if ($_POST['colors'] == 1) {
    update_option("perfit-optin-button-bg", $_POST["button-bg"]);
    update_option("perfit-optin-button-text", $_POST["button-text"]);
    update_option("perfit-optin-form-bg", $_POST["form-bg"]);
    update_option("perfit-optin-form-text", $_POST["form-text"]);
}

function perfit_admin()
{
    global $perfitConfig;
}

function perfit_list()
{
    global $perfitConfig, $perfit;
    include('optin_list.php');
}


function perfit_admin_actions()
{
    $favicon = plugin_dir_url(__FILE__) . '/images/menu-icon.png';
    add_menu_page("Perfit Optin", "Perfit Optin", 9, 'perfit_optin', "perfit_list", $favicon);
}

add_action('admin_menu', 'perfit_admin_actions');


add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );

function mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/perfit-optin.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

/*******************************************/
/************* Widget section **************/
/*******************************************/

class perfit_optin_widget extends WP_Widget
{

    // constructor
    function perfit_optin_widget()
    {
        global $perfit;
        $this->perfit = $perfit;

        load_plugin_textdomain('perfit');

        parent::__construct(
            'perfit_widget',
            __('Perfit - Formulario Optin', 'perfit'),
            array('description' => __('Formulario de suscripción conectado con Perfit Email Marketing.', 'perfit'))
        );

    }

    // widget form creation
    function form($instance)
    {

        global $error;

        $optinModes = array(
            'inline:' => 'En línea',
            'button:' => 'Botón',
            'popup:once' => 'Pop-Up (mostrar sólo una vez)',
            'popup:always' => 'Pop-Up (mostrar hasta lograr subscripción)',
        );

        $optin_id = ($instance) ? esc_attr($instance['optin_id']) : 0;

        $optin_mode = ($instance) ? esc_attr($instance['optin_mode']) : reset($optinModes);

        if (!$this->perfit->apiKey()) {

            include(dirname(__FILE__) . '/tpl/widget_login.php');
            unset($_SESSION['error']);
        } else {
            // Load optins
            $optins = $this->perfit->optins->limit(1000)->get();


            include(dirname(__FILE__) . '/tpl/widget.php');
        }

    }

    // widget update
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['optin_id'] = strip_tags($new_instance['optin_id']);
        $instance['optin_mode'] = strip_tags($new_instance['optin_mode']);
        return $instance;
    }

    // widget display
    function widget($args, $instance)
    {

        $title = apply_filters('widget_title', $instance['title']);

        echo $args['before_widget'];
        if (!empty($instance['optin_id'])) {

            list($account, $id) = explode(':', $instance['optin_id']);
            list($mode_type, $mode_mode) = explode(':', $instance['optin_mode']);

            $mode = ' data-type="' . $mode_type . '" ';

            if ($mode_mode) {
                $mode .= ' data-mode="' . $mode_mode . '" ';
            }

            $tpl = file_get_contents(dirname(__FILE__) . '/tpl/shortcode.php');
            $tpl = str_replace(array('%%OPTIN%%', '%%ACCOUNT%%', '%%MODE%%'), array($id, $account, $mode), $tpl);

            echo $tpl;
        }
        echo $args['after_widget'];

    }
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("perfit_optin_widget");'));
