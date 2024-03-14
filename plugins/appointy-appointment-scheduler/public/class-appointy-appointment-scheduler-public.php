<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       Appointy.com
 * @since      3.0.1
 *
 * @package    Appointy_appointment_scheduler
 * @subpackage Appointy_appointment_scheduler/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Appointy_appointment_scheduler
 * @subpackage Appointy_appointment_scheduler/public
 * @author     Appointy <lav@appointy.com>
 * @author     Appointy <shikhar.v@appointy.com>
 */
require_once "client-side-widget.php";

class Appointy_appointment_scheduler_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    3.0.1
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    3.0.1
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Helper functions required for data needed by admin plugin
     *
     * @since   3.0.1
     * @var     object $helper The helper functions required
     */
    private $helper;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @param object $helper The helper functions required
     * @since    3.0.1
     */
    public function __construct($plugin_name, $version, $helper)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->helper = $helper;
    }

    public function appointy_content($content)
    {
        if (preg_match('{APPOINTY}', $content)) {
            $content = str_replace('{APPOINTY}', $this->appointy(), $content);
        }
        return $content;
    }

    function appointy()
    {
        $str = '';
        if (!$this->helper->appointy_calendar_installed())
            $this->helper->set_appointy_installed($this->helper->appointy_calendar_install());

        if (!$this->helper->get_appointy_installed()) {
            echo "PLUGIN NOT CORRECTLY INSTALLED, PLEASE CHECK ALL INSTALL PROCEDURE!";
            return;
        }

        $code = $this->helper->get_appointy_code();

        if (strpos($code, "?") === false) {
            $code = $code . "?isgadget=1";
        }else {
            $code = $code . "&isgadget=1";
        }


        ?>

        <?php
        $str .= '<div class="wrap">';
        if ($code === null) {
            $str .= '<h4>You don\'t have appointy Calendar, please set code in Settings menu.</h4>';
        } else {
            if (strpos($code, "booking.appointy.com") === false) {
 $str .= "
                <center>
                    <div id=\"CalendarDiv\">
                        <iframe src=" . esc_url($code) . " width=100% height=550px scrolling=auto frameborder=0></iframe>                        
                        " . $this->helper->get_poweredby() . "
                    </div>
                </center>
            ";
 }
 else{
 $str .= "
                <center>
                    <div id=\"CalendarDiv\">
                        
                        <iframe id='appointy-iframe' class='no-border' src='" . esc_url($code) ."&autoheight=1' scrolling='no' width='100%' frameborder='0' ></iframe>
                                <script>
                                        (function() {
                                            const ifrm = document.getElementById('appointy-iframe');
                                            window.addEventListener('message', function (e) {
                                                const d = e.data || {};
                                                if (d.type === 'height') {
                                                    ifrm.style.height = d.data + 'px';
                                                }
                                                if (d.type === 'scroll') {                    
                                                    ifrm.scrollIntoView();
                                                }
                                            });
                                        })();
                                    </script>
                        
                        " . $this->helper->get_poweredby() . "
                    </div>
                </center>
            ";
 }
            
        }
        ?>
        <?php
        $str .= '</div>';

        return $str;
    }

    public function appointy_widget_init()
    {
        if (!function_exists('wp_register_sidebar_widget'))
            return;
        wp_register_sidebar_widget(
            '109',        // your unique widget id
            'Appointy',          // widget name
            array($this, 'appointy_widget_calender'),  // callback function
            array(                  // options
                'description' => 'It places a cool schedule now button on the sidebar of your website.'
            ));
        add_action("wp_footer", "OldAppointyWidget");
    }

    function appointy_widget_calender($args)
    {
        echo $args['before_widget'];
        echo $args['before_title'] . 'Schedule Now' . $args['after_title'];
        echo '<a href="' . esc_url($this->appointy_widget_url()) . "/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-plugin" . '" target="_blank"><img src="' . esc_url(plugins_url('img/scheduleme.png', __FILE__)) . '" alt="Schedule Me" border="0" /></a>';
        echo $args['after_widget'];
    }

    function appointy_widget_url()
    {
        if (!$this->helper->appointy_calendar_installed()) {
            echo "PLUGIN NOT CORRECTLY INSTALLED, PLEASE CHECK ALL INSTALL PROCEDURE!";
            return;
        }

        $code = $this->helper->get_appointy_code();
        return $this->appointy_get_booking_url($code);
    }

    function appointy_get_booking_url($codeURL)
    {
        $bookingURL = preg_match("/https?:\/\/(.*).com/", $codeURL, $matches);
        if ($bookingURL = true) {
            $bookingURL = htmlentities($matches['0']);
        }
        return $bookingURL;
    }
}
