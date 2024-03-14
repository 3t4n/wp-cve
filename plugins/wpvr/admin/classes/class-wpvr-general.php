<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Responsible for managing General tab content
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/admin/classes
 */

class WPVR_General extends WPVR_Tour_setting {

    /**
     * Instance of WPVR_Advanced_control class
     * 
     * @var object
     * @since 8.0.0
     */
    private $advanced_control;

    /**
     * Instance of WPVR_Basic_Setting class
     * 
     * @var object
     * @since 8.0.0
     */
    private $basic_setting;

    /**
     * Instance of WPVR_Control_Button class
     * 
     * @var object
     * @since 8.0.0
     */
    private $control_button;

    function __construct()
    {
        $this->advanced_control = WPVR_Advanced_Control::get_instance();

        $this->control_button   = WPVR_Control_Button::get_instance();

        $this->basic_setting = new WPVR_Basic_Setting();

    }

    /**
     * Render General Tab Content 
     * @param mixed $preview
     * @param mixed $previewtext
     * @param mixed $autoload
     * @param mixed $control
     * @param mixed $postdata
     * @param mixed $autorotation
     * @param mixed $autorotationinactivedelay
     * @param mixed $autorotationstopdelay
     * 
     * @return void
     */
    public function render_setting($postdata)
    {
        ob_start();
        ?>

        <!-- start inner tab -->
        <div class="general-inner-tab">
            <!-- start inner nav -->
            <?php WPVR_Meta_Field::render_general_inner_navigation() ?>
            <!-- end inner nav -->

            <!-- start inner tab content -->
            <div class="inner-nav-content">

                <?php $this->basic_setting->render_basic_setting($postdata); ?>

                <?php WPVR_Advanced_Control::render($postdata); ?>

                <?php WPVR_Control_Button::render($postdata); ?>

            </div>
            <!-- end inner tab content -->

            <!-- Embed Iframe -->
            <?php if (apply_filters('is_wpvr_embed_addon_premium', false)) { $post = get_post(); $id = $post->ID;?>

                <div class="wpvr-use-shortcode">
                    <h4 class="area-title"><?php echo __('Using this Tour','wpvr');?></h4>

                    <div class="wpvr-shortcode-wrapper">

                        <div class="wpvr-single-shortcode gutenberg">

                            <span class="shortcode-title"><?php echo __('To Embed on External Page:','wpvr')?></span>

                            <div class="field-wapper">

                                <span><?php echo __('Use the iframe below to share this tour on an external page.','wpvr')?></span><br>
                                <span style="color:red;">
                                    <?php echo __('Note: WooCommerce &amp; Fluent Forms hotspots will not be supported on embedded tours.','wpvr') ?>
                                </span>

                                <div class="wpvr-shortcode-field">
                                    <p class="copycode">&lt;iframe src="<?= home_url() ?>/?embed_page=<?= $id ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen width="100%" height="400">&lt;/iframe&gt;</p>
                                </div>

                            </div>
                            <!-- .field-wrapper end -->

                        </div>
                        <!-- wpvr-single-shortcode gutenberg end -->
                        <div class="wpvr-embaded-share-wrapper">
                            <div class="wpvr-social-share-area">

                                <h4><?php echo __('Share your tour','wpvr')?></h4>

                                <div class="wpvr-social-share-btn-area">

                                    <div class="single-settings autoload">
                                        <span> <?php echo __('Enable Social Media Share','wpvr')?>: </span>
                                        <span class="wpvr-switcher">
                                            <input id="wpvr_social_share" class="vr-switcher-check" name="wpvr_social_share" type="checkbox" value="<?php echo WPVR_Helper::is_enable_social_share($postdata) ?>" <?php echo WPVR_Helper::is_enable_social_share($postdata) == 'on' ? 'checked' : '' ?> >
                                            <label for="wpvr_social_share"></label>
                                        </span>
                                    </div>
                                    <div class="wpvr-share-buttons-container">
                                        <div class="share-list">
                                            <?php WPVR_Helper::social_media_share_links_display(home_url().'/?embed_page='. $id); ?>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="wpvr-qrcode-area">

                            <h4><?php echo __('Create a tour use this QR code','wpvr')?></h4>

                            <div class="wpvr-qrcode-btn-area">
                                <div class="wpvr-qrcode-btn-section">
                                    <div id="qrcode" class="wpvr-qrcode"></div>
                                    <button id="downloadBtn" class="wpvr-download-btn"><?php echo __('Download QR Code','wpvr') ?></button>
                                </div>

                            </div>

                        </div>
                        </div>
                        <!-- .wpvr-qrcode-area end -->
                    </div>
                    <!-- wpvr-shortcode-wrapper end  -->

                </div>
                <!-- wpvr-use-shortcode end  -->

                <script>
                    jQuery(document).ready(function($) {
                        // Generate QR code
                        var qr = new QRCode(document.getElementById("qrcode"), {
                            text: "<?= home_url() ?>/?embed_page=<?= $id ?>",// Replace with your desired URL or data
                            width: 128,
                            height: 128
                        });

                        // Handle download button click
                        $("#downloadBtn").on("click", function(e) {
                            e.preventDefault(); // Prevent the default behavior of the button

                            // Convert the QR code to a data URL
                            var dataURL = $("#qrcode canvas")[0].toDataURL("image/jpeg");

                            // Create a download link dynamically
                            var downloadLink = document.createElement("a");
                            downloadLink.href = dataURL;
                            downloadLink.download = "qrcode.jpg";

                            // Append the link to the body and trigger a click event
                            document.body.appendChild(downloadLink);
                            downloadLink.click();

                            // Remove the link from the body
                            document.body.removeChild(downloadLink);

                        });
                    });



                </script>
            <?php } ?>
            <!-- End Embed Iframe -->
        </div>
        <!-- end inner tab -->

        <?php
        ob_end_flush();
    }

}