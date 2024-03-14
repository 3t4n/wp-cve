<?php

require_once plugin_dir_path(dirname(__FILE__)) . './admin/class-appointy-appointment-scheduler-ajax.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       Appointy.com
 * @since      3.0.1
 *
 * @package    Appointy_appointment_scheduler
 * @subpackage Appointy_appointment_scheduler/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Appointy_appointment_scheduler
 * @subpackage Appointy_appointment_scheduler/admin
 * @author     Appointy <lav@appointy.com>
 * @author     Appointy <shikhar.v@appointy.com>
 */
class Appointy_appointment_scheduler_Admin
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
    private $appointy_setting;
    private $settings;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @param object $helper The helper functions required
     * @since    3.0.1
     */
    public function __construct($plugin_name, $version, $helper)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->helper = $helper;
        $this->settings = new AppointySettings($this->helper);
        $this->setup();
        $this->settings->ParseFromSettingString($this->helper->get_iframe_val());
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    3.0.1
     */
    public function enqueue_styles()
    {
        // wp_enqueue_style($this->plugin_name . "admin-css", plugin_dir_url(__FILE__) . 'css/appointy-appointment-scheduler-admin.css');
        // wp_enqueue_style($this->plugin_name . "bootstrap-css", plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
        wp_enqueue_style($this->plugin_name . "font-file", plugin_dir_url(__FILE__) . 'css/font-css.css');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name . "popper", 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', array(), $this->version, 'true');
        wp_enqueue_script($this->plugin_name . "bootstrap", 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', array(), $this->version, 'true');
        wp_enqueue_script($this->plugin_name . "custom", plugin_dir_url(__FILE__) . 'js/appointy-appointment-scheduler-admin.js', array("jquery"), $this->version, 'true');
        wp_localize_script('my_ajax', 'MyAjax', array('ajaxurl' => admin_url('admin-ajax.php')));

    }

    /**
     * Adds admin menu and loads the admin page
     *
     * @since    3.0.1
     */
    public function appointy_calendar_config_page()
    {
        if (function_exists('add_menu_page')) {
            add_menu_page('Appointy Calendar', 'Appointy Calendar', 'manage_options', __FILE__, array($this, 'appointy_calendar_main_page'));
        }
    }

    function appointy_calendar_main_page()
    {

        // check for appointy calendar table else create it
        if (!$this->helper->appointy_calendar_installed()) {
            $this->helper->set_appointy_installed($this->helper->appointy_calendar_install());
        }

        if (!$this->helper->get_appointy_installed()) {
            echo "PLUGIN NOT CORRECTLY INSTALLED, PLEASE CHECK ALL INSTALL PROCEDURE!";
            return;
        }
        ?>

        <!--  content of admin menu page  -->
        
        <link type="text/css" rel="stylesheet" href="<?php echo esc_url(plugins_url('css/bootstrap.min.css', __FILE__)) ?>"/>

        <link type="text/css" rel="stylesheet" href="<?php echo esc_url(plugins_url('css/appointy-appointment-scheduler-admin.css', __FILE__)) ?>"/>


        <div class="container">

            <?php

            $this->setup();

            if (isset($_GET["uninstall"]) and $_GET["uninstall"] == "true") {
                $this->uninstall_plugin();
                return;
            }

            // Check for language in full page code
            $sel_lang_option_value = $this->settings->lang;
            // reset
            $page_url = esc_url_raw($_SERVER["PHP_SELF"] . "?page=appointy-appointment-scheduler%2Fadmin%2Fclass-appointy-appointment-scheduler-admin.php"); // sanitize url
			$uninstall_url = $page_url."&uninstall=true";


            function startsWith($string, $startString)
            {
                $len = strlen($startString);
                return (substr($string, 0, $len) === $startString);
            }

            //print($this->settings->GetUserNameFromUrl());
            ?>
            <script> flag = <?php echo ($this->settings->IsAppointySetupFinished() == 1) ? "false" : "true" ?>; </script>
            <script> var showConfigurationPage = <?php echo ($this->settings->GetUserNameFromUrl() != "demo") ? "true" : "false" ?>;</script>
            <div class="page--logo-bar col-lg-12 col-md-12 col-sm-12 text-center">
                <a href="javascript:void(0)"><?php echo '<img src="' . esc_url(plugins_url('img/logo.png', __FILE__)) . '" >' ?></a>
            </div>
            <div id="signup-page">
                <span id="pluginSetupSection">
            <div class="page--header text-center col-lg-12 col-md-12 col-sm-12">
                <h1>The oldest, most reliable, and highly rated<br><u> Appointment Scheduling and Booking Solution</u>
                </h1>
                <p class="text-center">
                <h2>All-in-one online scheduling software - Trusted by 1,30,000+ businesses around the globe.<br>Multiple
                    platforms, 100's of reviews. One VERDICT</h2></p>
            </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                        <?php echo '<img src="' . esc_url(plugins_url('img/captura.png', __FILE__)) . '" >' ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                        <?php echo '<img src="' . esc_url(plugins_url('img/crowd.png', __FILE__)) . '" >' ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                        <?php echo '<img src="' . esc_url(plugins_url('img/merchant.png', __FILE__)) . '" >' ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12 text-center">
                        <?php echo '<img src="' . esc_url(plugins_url('img/google.png', __FILE__)) . '" >' ?>
                    </div>
                </div>

                <div class="row mr-tp--60">
                    <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                        <!--<a
                           href="https://qa-business.appointy.com/account/register?isgadget=2&utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-plugin"
                           target="_blank"
                           class="button fill--gredient">Setup Plugin</a>-->
                        <a   type="button" class="button fill--gredient" href="https://business.appointy.com/account/register?isgadget=2&utm_source=wordpress&utm_medium=btn_plugin_signup&utm_campaign=wp-plugin"
                           target="_blank" >Setup Plugin</a>
                        <p style="font-size:14px;"><b>You can also request <i>free setup assistance</i> from our <u>24x7
                                    support desk</u> once you create your widget by clicking the button above.</b></p>
                        <h2>Already have an Appointy account? <a href="#"
                                                                 class="us--link"
                                                                 id="click-to-setup-page"> Click here</a></h2>
                    </div>
                </div>

                    <!-- Modal -->
                  <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                        <div class="modal-body" >
                          <p id="signupFram"></p>
                        </div>

                      </div>

                    </div>
                  </div>
                <div class="col-lg-12 col-md-12 col-sm-12 text-center mt-4">
                    <div class="video--box">
                        <?php echo '<img src="' . esc_url(plugins_url('img/website.gif', __FILE__)) . '" >' ?>
                    </div>


                </div>
            </div>
                 </span>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12" id="plugin-setup-page">

                <div class="form-box mr-tp--60" id="formPage">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="alert alert-warning" style="display: none;" id="signup-setup-warning">
                                <strong>Warning!</strong> Thanks for Signing Up with Appointy. Please <a href="https://business.appointy.com" target="_blank"> click this link</a> for a quick account setup.
                            </div>
                            <label for=" appointy-url-input" class="mt-3 ">Your Appointy URL</label>
                            <div class="  input-group mb-3 ">
                                <textarea disabled="true" type="text" name="code"
                                          class="form-control"
                                          id="AppointyUrlInput"><?php echo esc_url($this->settings->url) ?></textarea>
                                <div class="input-group-append">
                                    <button class="btn btn-link us--link" type="button" id="changeButton"><?php echo($this->settings->url == "" ? "Add Appointy URL" : "Change") ?>
                                    </button>
                                </div>


                            </div>

                            <label class="mt-3">How do you want to display the booking widget on your
                                website?</label>
                            <div class="row">
                                <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12">
                                    <div class="form-group mt-1 ">
                                        <select class="form-control" id="bookingWidgetSelect"
                                                onchange="UpdateAdvanceUrl();">
                                            <option value="website"  <?php echo($this->settings->widget == "website" ? "selected" : "") ?> >
                                                Sitewide popup triggered by a button
                                            </option>
                                            <option value="booking-page" <?php echo($this->settings->widget == "booking-page" ? "selected" : "") ?> >
                                                Embeded calendar on a webpage
                                            </option>

                                        </select>
                                    </div>
                                    <div class="col-12 " id="heightWidthInput">
                                       
                                        <div class="row my-3 ">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Max width</span>
                                                </div>
                                                <input type="number" id="max-width" aria-label="Max width"
                                                       class="form-control" onchange="UpdateAdvanceUrl();"
                                                       onkeyup="UpdateAdvanceUrl();" value="<?php echo($this->settings->GetValue($this->settings->maxWidth)); ?>">
                                                <select class="form-control" style="margin:0; height:38px" id="maxWidthUnit"
                                                onchange="UpdateAdvanceUrl();">
                                                    <option value="px" <?php echo($this->settings->GetUnit($this->settings->maxWidth) == "px" ? "selected" : "") ?> >
                                                        px
                                                    </option>
                                                    <option value="%" <?php echo($this->settings->GetUnit($this->settings->maxWidth) == "%" ? "selected" : "") ?> >
                                                        %
                                                    </option>

                                                </select>
                                               
                                            </div>

                                        </div>

                                        <div class="row my-3">
                                            <div class="input-group">
                                                
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Max height</span>
                                                </div>
                                                <input type="number" id="max-height" aria-label="Max height"
                                                       class="form-control" onchange="UpdateAdvanceUrl();"
                                                       onkeyup="UpdateAdvanceUrl();" value="<?php echo($this->settings->GetValue($this->settings->maxHeight)); ?>">

                                                <select class="form-control" style="margin:0; height:38px" id="maxHeightUnit"
                                                onchange="UpdateAdvanceUrl();">
                                                    <option value="px" <?php echo($this->settings->GetUnit($this->settings->maxHeight) == "px" ? "selected" : "") ?> >
                                                        px
                                                    </option>
                                                    <option value="%" <?php echo($this->settings->GetUnit($this->settings->maxHeight) == "%" ? "selected" : "") ?> >
                                                        %
                                                    </option>

                                                </select>
                                            </div>

                                        </div>


                                    </div>
                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12">
                                    <div class="text-center justify-content-center">
                                        <?php echo '<img id="websiteGif" srcurl="' . esc_url(plugins_url('', __FILE__)) . '" src="' . esc_url(plugins_url('img/website.gif', __FILE__)) . '" height="150px" >' ?>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-12 mt-2">
                                    <div class="my-3" id="instruction-sec">
                                        <h3 class="mb-0">Setup Instructions for webpage embedded calendar</h3>
                                        <p>To embed the appointment calendar on any wordpress page/post please follow the instructions below:</p>
                                        <ol>
                                        <li>Go to the page/post where you want to embed the appointment calendar</li>
                                        <li>Paste shortcode <strong>{APPOINTY}</strong> as normal text in the editor. Please also include {} brackets.</li>
                                        <li>Publish/Update the page.</li>
                                        <li>Your appointment calendar will be embedded in the page.</li>
                                        </ol>
                                    </div>
                                    <div class="accordion" id="accordionExample">
                                        <div id="headingOne">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link pl-0" type="button"
                                                        data-toggle="collapse"
                                                        data-target="#collapseOne" aria-expanded="true"
                                                        aria-controls="collapseOne">
                                                    Advanced Iframe Settings
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                             data-parent="#accordionExample">
                                            <textarea id="iframeCodeArea" type="text" name="code"
                                                      class="form-control"><?php echo esc_url($this->settings->setting_url) ?></textarea>
                                            <div class="d-flex justify-content-center">

                                                <input class="button-medium-outline" id="form-submit" type="submit"
                                                       name="set"
                                                       value="Update"/>

                                            </div>

                                            
											<div class="alert alert-info" style="display:none" id="successfullyMsg" role="alert">
Settings updated successfully
</div>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush" style="    margin-top: 36px;">
                                        <li class="list-group-item  pl-0">
                                            <h3>Check Appointments</h3>
                                            <p><a  href="https://business.appointy.com/app/myspace?itm_source=wp_plugin_dashboard&itm_medium=link_click_here_appointments&itm_campaign=wp_plugin_dashborard_engagement&itm_term=default&itm_content=appointments" target="_blank" class="card-link"  >Click here </a> to see your
                                                appointments
                                            </p>
                                        </li>

                                        <li class="list-group-item  pl-0">
                                            <h3>Manage Plugin Settings</h3>
                                            <p><a href="https://business.appointy.com/app/settings/staff?itm_source=wp_plugin_dashboard&itm_medium=link_click_here_settings&itm_campaign=wp_plugin_dashborard_engagement&itm_term=default&itm_content=settings" target="_blank" class="card-link">Click here </a> to update services,
                                                staff,
                                                work hours or
                                                other settings</p>
                                        </li>
                                        <li class="list-group-item  pl-0">
                                            <div class="btm-reset">
                                               <div class="col-lg-6 col-md-12 col-sm-12 p-0">
                                                    <h3>Need Help?</h3>
                                                    <p><a href="https://business.appointy.com/app/myspace?itm_source=wp_plugin_dashboard&itm_medium=link_click_here_help&itm_campaign=wp_plugin_dashborard_engagement&itm_term=default&itm_content=help" target="_blank" class="card-link">Click here </a> and initiate live chat.
                                                        We
                                                        offer 24x7 setup
                                                        assistance</p>
                                               </div>
                                               <div class="col-lg-6 col-md-12 col-sm-12 p-0">
                                                    <h3>Reset</h3>
                                                    <p><a href="<?php echo esc_url($uninstall_url)?>" class="card-link">Click here </a> to reset the Plugin.</p>
                                               </div>
                                            </div>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div id="float-clear"></div>

        <?php

    }

    function setup()
    {
        global $wpdb;

        // check if database table exists
        $d1 = $wpdb->get_var($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "appointy_calendar limit 1", array()));
        if ($d1 === null) {

            // create table
            $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "appointy_calendar (code) VALUES (%s)", $this->helper->appointy_get_demo_url()));

        } else {

            // select code from table
            $this->helper->set_iframe_val($wpdb->get_var($wpdb->prepare("SELECT code AS code FROM " . $wpdb->prefix . "appointy_calendar LIMIT 1", array())));

            // update code or iFrameVal
            if (strpos($this->helper->get_iframe_val(), "http://") !== false) {
                $newCodeHttps = str_replace("http://", "https://", $this->helper->get_iframe_val());
                $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "appointy_caledar SET code = %s", $newCodeHttps));
                $this->helper->set_iframe_val(str_replace("\\", "", $newCodeHttps));
            }
        }
    }

    function uninstall_plugin()
    {
        global $wpdb;

        $wpdb->query($wpdb->prepare("DROP TABLE IF EXISTS " . $wpdb->prefix . "appointy_calendar", array()));

        delete_option('appointy_calendar_privileges'); //Removing option from database...

        $installed = $this->helper->appointy_calendar_installed();

        if (!$installed) {
            echo "PLUGIN UNINSTALLED. NOW DE-ACTIVATE PLUGIN.<br />";
            echo " <a href=plugins.php>CLICK HERE</a>";
            return;
        } else {
            echo "PROBLEMS WITH UNINSTALL FUNCTION.";
        }
    }
}