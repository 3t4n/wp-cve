<?php
namespace Tussendoor\OpenRDW\Front;

use Tussendoor\OpenRDW\Includes\VehiclePlateInformationAPI;
use Tussendoor\OpenRDW\Includes\VehiclePlateInformationFields;

/**
 * The public-facing functionality of the plugin.
 *
 * @see       http://www.tussendoor.nl
 * @since      2.0.0
 *
 */
class VehiclePlateInformationFront
{

    public $dot_config;

    /**
     * The ID of this plugin.
     *
     * @since    2.0.0
     * @var string $open_rdw_kenteken_voertuiginformatie    The ID of this plugin.
     */
    private $open_rdw_kenteken_voertuiginformatie;

    /**
     * The version of this plugin.
     *
     * @since    2.0.0
     * @var string $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since      2.0.0
     * @param string $open_rdw_kenteken_voertuiginformatie The name of the plugin.
     * @param string $version                              The version of this plugin.
     */
    public function __construct($open_rdw_kenteken_voertuiginformatie, $version)
    {
        global $dot_config;
        $this->dot_config = $dot_config;

        $this->open_rdw_kenteken_voertuiginformatie = $open_rdw_kenteken_voertuiginformatie;
        $this->version = $version;

        add_shortcode('open_rdw_check', [$this, 'shortcode_check']);
        add_shortcode('open_rdw_quform', [$this, 'shortcode_quform']);

        add_action('wp_ajax_get_open_rdw_data', [$this, 'ajax_check' ]);
        add_action('wp_ajax_nopriv_get_open_rdw_data', [$this, 'ajax_check' ]);
    }

    /**
     * Our ajax callback function which is responsible for
     * responding a JSON string containing all the vehicle information.
     *
     * @since   2.0.0
     */
    public function ajax_check()
    {
        $data = ['errors' => true];

        if (isset($_POST['kenteken']) && $_POST['kenteken'] != '') {
            $api = new VehiclePlateInformationAPI();

            $kenteken = $api->clean_license(sanitize_text_field($_POST['kenteken']));
            $data['result'] = $api->get_info($kenteken);

            if ($data['result'] != null) {
                $data['errors'] = false;
            }
        }

        header('Content-type: application/json');
        wp_send_json_success($data);
    }

    public function shortcode_quform($fields)
    {
        $license_key = array_search('kenteken', $fields);
        if ($license_key) {
            $license = $license_key;
            unset($fields[$license_key]);
            $data = [
                'license'   => $license,
                'fields'    => array_flip($fields),
                'url'       => admin_url('admin-ajax.php'),
                'images'    => [
                    'loading' => $this->dot_config['plugin.asset_url'] . 'images/front/ajax-loader.gif',
                    'warning' => $this->dot_config['plugin.asset_url'] . 'images/front/warning-icon.png',
                    'success' => $this->dot_config['plugin.asset_url'] . 'images/front/accepted-icon.png'
                ]
            ];

            wp_register_script('open_rdw_quform', $this->dot_config['plugin.asset_url'] . 'js/front/open-rdw-kenteken-voertuiginformatie-public.js');
            wp_localize_script('open_rdw_quform', 'ajax', $data);
            wp_enqueue_script('open_rdw_quform');
        }
    }

    /**
     * Responsible for handling our shortcode post data and returning
     * the HTML code to our front-end user.
     *
     * @since     2.0.0
     * @param  array $arguments All the fields that are set in the shortcode.
     * @return HTML  output
     */
    public function shortcode_check($arguments)
    {
        $args['widget_id'] = $this->open_rdw_kenteken_voertuiginformatie;
        $settings['checkedfields'] = $arguments;
        $settings['allfields'] = VehiclePlateInformationFields::fields();

        if (isset($_POST['Open_RDW_kenteken_voertuiginformatie']) && $_POST['Open_RDW_kenteken_voertuiginformatie'] != '') {
            $api = new VehiclePlateInformationAPI();
            $kenteken = $api->clean_license(sanitize_text_field($_POST['Open_RDW_kenteken_voertuiginformatie']));
            $kentekeninfo = $api->get_info($kenteken);
        }

        if (empty($settings['class'])) {
            $settings['class'] = 'empty';
        }

        if (empty($settings['title'])) {
            $settings['title'] = '';
        }

        ob_start();
        include $this->dot_config['plugin.view'].'front/vehicle-plate-information-form.php';
        return ob_get_clean();
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->open_rdw_kenteken_voertuiginformatie, $this->dot_config['plugin.asset_url'] . 'css/front/open-rdw-kenteken-voertuiginformatie-public.css', [], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->open_rdw_kenteken_voertuiginformatie, $this->dot_config['plugin.asset_url'] . 'js/front/open-rdw-kenteken-voertuiginformatie-public.js', [ 'jquery' ], $this->version, false);

        /**
         * Localize admin-ajax.php so we can make ajax calls on front-end
         */
        wp_localize_script($this->open_rdw_kenteken_voertuiginformatie, 'ajax', [ 'ajax_url' => admin_url('admin-ajax.php') ]);
    }
}
