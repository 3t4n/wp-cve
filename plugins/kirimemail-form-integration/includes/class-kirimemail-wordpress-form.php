<?php if (!defined('ABSPATH')) {
    exit;
}

final class Kirimemail_Wordpress_Form
{
    const KIRIMEMAIL_PLUGIN_VERSION = '1.4.0';
    private static $_instance = null;

    /**
     * @var Kemail_Api
     */
    public $KIRIMEMAIL_WPFORM_API = null;

    /**
     * Main Kirimemail Instance.
     *
     * Ensures only one instance of Kirimemail is loaded or can be loaded.
     *
     * @return Kirimemail_Wordpress_Form - Main instance.
     * @see WC()
     * @since 2.1
     * @static
     */
    public static function instance()
    {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct()
    {
        $this->define_constant();
        $this->_include();
        $this->KIRIMEMAIL_WPFORM_API = new Kemail_Api();
        $this->add_menu();

        if (!is_admin()) {
            add_action('wp_head', array($this, 'custom_js'));
        }

        add_action('elementor/widgets/widgets_registered', function ($class) {
            require_once KE_PATH . '/includes/elementor-form.php';
            $new_classname = 'Elementor\Widget_Kirimemail';

            $class->register_widget_type(new $new_classname());
        });

        // contact form
        add_filter('wpcf7_editor_panels', function ($panels) {
            $panels['kirimemail'] = array(
                'title' => 'KIRIM.EMAIL',
                'callback' => array($this, 'contact_form')
            );

            return $panels;
        });

        add_filter('wpcf7_contact_form_properties', array($this, 'get_properties'), 99, 2);

        add_action('wpcf7_mail_sent', array($this, 'contact_form_submit'), 99);
    }

    public function __clone()
    {
        ke_wpform_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'Kirimemail_Wordpress_Form'), '1.0');
    }

    public function __wakeup()
    {
        ke_wpform_doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'Kirimemail_Wordpress_Form'), '1.0');
    }

    protected function define_constant()
    {
        $this->define('KIRIMEMAIL_WPFORM_ABSPATH', dirname(KIRIMEMAIL_WPFORM_PLUGIN_FILE) . '/');
        $this->define('KIRIMEMAIL_PLUGIN_VERSION', self::KIRIMEMAIL_PLUGIN_VERSION);
    }

    protected function _include()
    {
        include_once KIRIMEMAIL_WPFORM_ABSPATH . 'includes/class-kemail-autoloader.php';
        include_once KIRIMEMAIL_WPFORM_ABSPATH . 'includes/kemail-core-function.php';
        $loader = new Kemail_Wpform_Autoloader();
        $loader->autoload('Kemail_Tables');
        $loader->autoload('Kemail_Api', false);
        $loader->autoload('Kemail_Widget');
        $loader->autoload('Kemail_Tinymce_Editor_Ext');
        $loader->autoload('Kemail_Shortcode');
        $loader->autoload('Kemail_Metabox');
        $loader->autoload('Kemail_Post_Form', false);
    }

    public function add_menu()
    {
        global $pagenow;

        if (is_admin()) {
            add_action('admin_menu', array($this, 'admin_menu'));
            add_action('admin_notices', array($this, 'display_notice'));

            if (isset($_GET['page']) && $_GET['page'] == 'kirimemail-wordpress-form') {
                add_action('admin_enqueue_scripts', array(&$this, 'enqueue_scripts'));
            }

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                if (isset($_GET['get_form'])) {
                    $this->form_tinymce();
                }
                if (isset($_GET['save_object'])) {
                    $this->save_object();
                }
                if (isset($_GET['save_metabox'])) {
                    $this->save_metabox();
                }
                if (isset($_GET['get_form_listener'])) {
                    $this->form_result();
                }
                if (isset($_GET['get_select_option'])) {
                    $this->get_select_option();
                }
                if (isset($_GET['get_select_active_value'])) {
                    $this->get_select_active_value();
                }
                /*if (isset($_GET['get_form_widget'])) {
                    $this->KEMAIL_WPFORM_API->get_form_widget();
                }*/
            }
        }
    }

    public function display_notice()
    {
        global $hook_suffix;
        if ($hook_suffix == 'plugins.php' && !get_option('ke_wpform_api_username')) {
            load_view('notice', ['type' => 'plugins']);
        }
    }

    public function page()
    {
        if (!empty($_POST)) {
            self::save();
        }

        $form = $this->KIRIMEMAIL_WPFORM_API->get_form(array('raw_data' => true));

        if ($form == -1) {
            $page = 'no-account';
        } else if (is_array($form) && count($form) == 0) {
            $page = 'no-form';
        } else {
            $page = 'form';
        }

        $data = array(
            'logo' => get_asset('images/ke-logo.svg'),
            'alert' => get_asset('images/icon-alert.svg'),
            'blank' => get_asset('images/icon-blank.svg'),
            'form_popup' => get_asset('images/form-popup.svg'),
            'form_page' => get_asset('images/form-page.svg'),
            'form_widget' => get_asset('images/form-widget.svg'),
            'page' => $page,
            'active' => get_option('ke_form_active')
        );

        load_view('index', $data);
    }

    public function form_result()
    {
        $active = get_option('ke_form_active');

        $form = $this->KIRIMEMAIL_WPFORM_API->get_form(array('raw_data' => true, 'offset' => sanitize_text_field($_POST['start'])));
        $form_data = array();
        if (is_array($form['data']) && count($form['data']) > 0) {
            foreach ($form['data'] as $f) {
                $url = explode('/', $f['url']);
                $form_data[] = array(
                    'id' => $f['id'],
                    'name' => $f['name'],
                    'viewed' => $f['viewed'],
                    'submitted' => (empty($f['viewed']) ? 0 : round($f['submitted'] / $f['viewed'])),
                    /*'popup_checked' => isset($active['widget#' . end($url)]) ? 1 : 0,
                    'bar_checked' => isset($active['bar#' . end($url)]) ? 1 : 0,*/
                    'popup_checked' => (isset($active['widget']) && $active['widget'] == end($url)) ? 1 : 0,
                    'bar_checked' => (isset($active['bar']) && $active['bar'] == end($url)) ? 1 : 0,
                    'url' => end($url),
                );
            }
        }

        echo json_encode(array(
            'draw' => isset($_POST['draw']) ? esc_html($_POST['draw']) : 1,
            'recordsTotal' => $form['total'],
            'recordsFiltered' => $form['total'],
            'data' => $form_data
        ));

        die();
    }

    public function form_tinymce()
    {
        $form = $this->KIRIMEMAIL_WPFORM_API->get_form(array(
            'raw_data' => true,
            'search' => sanitize_text_field($_POST['search'])
        ));
        echo '<option value="">-- Dont Save --</option>';
        if (is_array($form['data']) && count($form['data']) > 0) {
            foreach ($form['data'] as $f) {
                echo '<option value="' . esc_attr($f['url']) . '">' . esc_html($f['name']) . '</option>';
            }
        }
        die();
    }

    public static function save()
    {
        if (isset($_POST['api_username'])) {
            update_option('ke_wpform_api_username', sanitize_user($_POST['api_username']));
        }

        if (isset($_POST['api_token'])) {
            update_option('ke_wpform_api_token', sanitize_text_field($_POST['api_token']));
        }
    }

    public function save_object()
    {
        $form = get_option('ke_form_active');
        if (empty($form)) {
            $form = array();
        }

        //$string = $_POST['object'] . '#' . $_POST['id'];
        if ($_POST['active'] == 'true') {
            $form[$_POST['object']] = sanitize_text_field($_POST['id']);
        } else {
            unset($form[$_POST['object']]);
        }

        update_option('ke_form_active', $form);

        echo _wp_specialchars($form,ENT_NOQUOTES,'UTF-8',true);
        die();
    }

    public function save_metabox()
    {
        header('Content-Type: application/json');
        $widget_selected = sanitize_text_field($_POST['widget_selected']);
        $bar_selected = sanitize_text_field($_POST['bar_selected']);
        $post_id = sanitize_text_field($_POST['post_id']);
        $post = get_post($post_id);
        $post_form = new Kemail_Post_Form();
        if (null !== $post) {
            $output = $post_form->save($post_id, array(
                'widget' => $widget_selected,
                'bar' => $bar_selected
            ));
            die(json_encode(array('status' => !is_bool($output) ? 'success' : 'error')));
        } else {
            die(json_encode(array('status' => 'error')));
        }
    }

    public function get_select_option()
    {
        $this->KIRIMEMAIL_WPFORM_API->get_form(array('search' => sanitize_text_field($_GET['term'])));
    }

    public function get_select_active_value()
    {

    }

    public function admin_menu()
    {
        add_menu_page(__('Kirim.Email Form Integration', 'Kirimemail_Wordpress_Form'),
            __('Kirim.Email Form Integration', 'Kirimemail_Wordpress_Form'),
            'activate_plugins',
            'kirimemail-wordpress-form',
            array($this, 'page'),
            get_asset('images/icon.png'));
    }

    // ASSET

    public function register_scripts()
    {
        wp_register_style('kirimemail-form', get_asset('css/style.css'), false, self::KIRIMEMAIL_PLUGIN_VERSION);
        wp_register_style('kirimemail-datatable', get_asset('css/jquery.dataTables.min.css'), false);
        wp_register_style('kirimemail-datatable-responsive', get_asset('css/responsive.dataTables.min.css'), 'kirimemail-datatable-responsive');
        wp_register_script('kirimemail-datatable', get_asset('js/jquery.dataTables.min.js'), 'jQuery', self::KIRIMEMAIL_PLUGIN_VERSION, true);
        wp_register_script('kirimemail-datatable-responsive', get_asset('js/responsive.dataTables.min.js'), 'kirimemail-datatable', self::KIRIMEMAIL_PLUGIN_VERSION, true);
        wp_register_script('kirimemail-form', get_asset('js/kirimemail-form.js'), 'jQuery', self::KIRIMEMAIL_PLUGIN_VERSION, true);
        wp_localize_script('kirimemail-form', 'kirimemail_wpform', array(
            'admin_url' => admin_url()
        ));
    }

    public function enqueue_scripts()
    {
        $this->register_scripts();
        wp_enqueue_style('kirimemail-form');
        wp_enqueue_style('kirimemail-datatable');
        wp_enqueue_style('kirimemail-datatable-responsive');
        wp_enqueue_script('jQuery');
        wp_enqueue_script('kirimemail-form');
        wp_enqueue_script('kirimemail-datatable');
        wp_enqueue_script('kirimemail-datatable-responsive');
    }

    // ASSET

    public function custom_js()
    {
        global $pagenow;
        $id = get_the_ID();
        $post_form = new Kemail_Post_Form();
        $active_form = $post_form->get($id);
        $form = get_option('ke_form_active');
        $widget = '';
        $bar = '';
        // Load generated script from our service. The script are generated on load process.
        if (!empty($form) && count($form) > 0) {
            if (isset($form['widget']) && empty($widget)) {
                wp_enqueue_script('kirimemail-widget', KIRIMEMAIL_APP_URL . 'service/widget/' . $form['widget']);
                //$widget = '<script type="text/javascript" src="' .KIRIMEMAIL_APP_URL . 'service/widget/' . $form['widget']  . '"></script>';
            }
            if (isset($form['bar']) && empty($bar)) {
                wp_enqueue_script('kirimemail-widget', KIRIMEMAIL_APP_URL . 'service/bar/' . $form['bar']);
                //$bar = '<script type="text/javascript" src="' . KIRIMEMAIL_APP_URL . 'service/bar/' . $form['bar'] . '"></script>';
            }
        }
        if (isset($active_form) && $active_form !== null) {
            $widget_data = json_decode($active_form->widget, false);
            $bar_data = json_decode($active_form->bar, false);
            if (!empty($widget_data)) {
                $widget_url = explode('/', $widget_data->url);
                //$widget = '<script type="text/javascript" src="' . KIRIMEMAIL_APP_URL . 'service/widget/' . end($widget_url) . '"></script>';
                wp_enqueue_script('kirimemail-widget', KIRIMEMAIL_APP_URL . 'service/widget/' . end($widget_url));
            }
            if (!empty($bar_data)) {
                $bar_url = explode('/', $bar_data->url);
                //$bar = '<script type="text/javascript" src="' . KIRIMEMAIL_APP_URL . 'service/bar/' . end($bar_url) . '"></script>';
                wp_enqueue_script('kirimemail-widget', KIRIMEMAIL_APP_URL . 'service/bar/' . end($bar_url));
            }
        }
        //return esc_html($widget . $bar);
    }

    // integration with contact form 7
    public function contact_form($post)
    {
        echo '<h2>' . __('KIRIM.EMAIL', 'Kirimemail_Wordpress_Form') . '</h2>';
        $form_value = $post->prop('kirimemail');
        $KEMAIL_WPFORM_API = new \Kemail_Api();
        $list_data = $KEMAIL_WPFORM_API->get_list();
        if (isset($list_data['data'])) {
            $lists = $list_data['data'];
            ?>
            <fieldset>
                <table class="form-table">
                    <tbody>

                    <tr>
                        <th scope="row">
                            <label
                                for="wpcf7-kirimemail[sync]"><?php echo __('Sync to KIRIM.EMAIL', 'Kirimemail_Wordpress_Form'); ?></label>
                        </th>
                        <td>
                            <select name="wpcf7-kirimemail[sync]">
                                <option
                                    value="0"<?php echo (isset($form_value['sync']) && $form_value['sync'] == 0) ? ' selected' : ''; ?>>
                                    No
                                </option>
                                <option
                                    value="1"<?php echo (isset($form_value['sync']) && $form_value['sync'] == 1) ? ' selected' : ''; ?>>
                                    Yes
                                </option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label
                                for="wpcf7-kirimemail[list]"><?php echo __('Select List', 'Kirimemail_Wordpress_Form'); ?></label>
                        </th>
                        <td>
                            <select name="wpcf7-kirimemail[list]">
                                <?php
                                foreach ($lists as $list) {
                                    ?>
                                    <option
                                        value="<?php echo $list['id']; ?>"<?php echo (isset($form_value['list']) && $form_value['list'] == $list['id']) ? ' selected' : ''; ?>><?php echo $list['name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </fieldset>
            <?php
        } else {
            echo __('Please connect your KIRIM.EMAIL account', 'Kirimemail_Wordpress_Form');
        }
    }

    public function get_properties($properties, $obj)
    {
        if ($_POST) {
            if (isset($_POST['wpcf7-kirimemail'])) {
                $properties['kirimemail'] = array();
                foreach ($_POST['wpcf7-kirimemail'] as $key => $value) {
                    $properties['kirimemail'][$key] = intval($value);
                }
            }
        } else {
            $post_meta = get_post_meta($obj->id(), '_kirimemail', true);
            if (!empty($post_meta)) {
                $properties['kirimemail'] = $post_meta;
            }
        }

        return $properties;
    }

    public function contact_form_submit($obj)
    {
        // if form is synced
        $prop = get_post_meta($obj->id(), '_kirimemail', true);

        if ($prop['sync'] == 0) {
            return;
        }

        // send to KE
        $is_email_field_exist = false;
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'email') !== false) {
                $is_email_field_exist = true;
                break;
            }
        }

        if ($is_email_field_exist) {
            $data = array(
                'lists' => array($prop['list']),
                'create_fields' => true
            );
            foreach ($_POST as $key => $value) {
                if (strpos($key, '_wpcf7') !== false) {
                    continue;
                }

                if (strpos($key, 'name') !== false) {
                    $data['full_name'] = sanitize_text_field($value);
                } else if (strpos($key, 'email') !== false) {
                    $data['email'] = sanitize_text_field($value);
                } else {
                    if (!isset($data['fields'])) {
                        $data['fields'] = array();
                    }
                    $key = str_replace(array('-', ' '), '_', $key);
                    $data['fields'][$key] = sanitize_text_field($value);
                }
            }

            $KEMAIL_WPFORM_API = new \Kemail_Api();
            $response = $KEMAIL_WPFORM_API->create_subscriber($data);
        }
    }

    /**
     * Define constant if not already set.
     *
     * @param string $name Constant name.
     * @param string|bool $value Constant value.
     */
    private function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }
}
