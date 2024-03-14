<?php

class Qcopd_info_page
{

    function __construct()
    {
        add_action('admin_menu', array($this, 'qcichart_info_menu'));
    }

    function qcichart_info_menu()
    {
        add_menu_page(
            esc_html('iChart'),
            esc_html('iChart'),
            'manage_options',
            'qcopd_ichart_info_page',
            array(
                $this,
                'qcld_ichart_info_page_content'
            ),
            'dashicons-chart-bar',
            80
        );
        add_submenu_page(
            'qcopd_ichart_info_page',
            esc_html('Help'),
            esc_html('Help'),
            'manage_options',
            'qcopd_ichart_info_page',
            array(
                $this,
                'qcld_ichart_info_page_content'
            ),
            
        );
    }

    function qcld_ichart_info_page_content()
    {
        ?>
        <div class="wrap">

            <div id="poststuff">

                <div id="poststuff">

                    <div id="post-body" class="metabox-holder columns-2">

                        <div id="post-body-content" class="support-block-info" style="position: relative;">

                            <div class="clear"></div>

                            <h3><?php esc_html_e('Welcome to the iChart ! You are'); ?> <strong><?php esc_html_e('awesome'); ?></strong>, <?php esc_html_e('by the way'); ?> <img
                                        draggable="false" class="emoji" alt="🙂" src="<?php echo qcld_ichart_img_url1; ?>/1f642.svg"></h3>
                            <h3 class="ichart-plugin-title"><?php esc_html_e('Getting Started'); ?></h3>

                            <p><?php esc_html_e('iChart supports creating and building Pie Chart, Bar chart, Line Chart, Polar Area Chart,
                                Radar Chart, and Doughnut Chart that are optimized to address your WordPress data
                                visualization needs. Visualize your data now more easily than ever with iChart chart
                                builder!'); ?></p>

                            <h3><?php esc_html_e('With that in mind you should start with the following simple steps.'); ?></h3>
                            <ol class="ichart-start-steps">

                                <li><p><?php esc_html_e('Go to any Page or Post Editor'); ?></p>

                                </li>
                                <li><p><?php esc_html_e('You will find a Shortcode Generator for iChart on the Right Side of your
                                     Browser named as'); ?> <strong><?php esc_html_e('"Shortcode Generator for iChart"'); ?></strong></p>

                                </li>
                                <li><p> <?php esc_html_e('Now go to a page or post where you want to display the directory. On the right
                                        sidebar you will see a'); ?> <strong><?php esc_html_e('ShortCode Generator'); ?></strong>  <?php esc_html_e('block. Click the button and a Popup LightBox will appear with all the options that you can select. Enter
                                        information for the releavent fields. Then Click'); ?> <strong><?php esc_html_e('Generate Shortcode'); ?></strong>
                                        <?php esc_html_e('button. Shortcode will be generated. Simply'); ?> <strong><?php esc_html_e('copy paste'); ?></strong> <?php esc_html_e('that to a location on your page where you want the'); ?> <strong><?php esc_html_e('directory to show up'); ?></strong>.
                                    </p>
                                    <p style="margin-left: 30px;"><img src="<?php echo qcld_ichart_img_url1; ?>/chart-popup.png"></p>

                                </li>
                                <li><p><?php esc_html_e('You can also find a Shortcode Generator Block on Guttenberg Editor and Tinymce
                                        Editor. Just add a new block in Gutenberg and Search for'); ?> <strong><?php esc_html_e('"iChart Shortcode Maker"'); ?></strong>.</p>
                                    <p style="margin-left: 30px;"><img src="<?php echo qcld_ichart_img_url1; ?>/gutenberg-block.png"></p>

                                </li>
                                <li><p><?php esc_html_e('That’s it! The above steps are for the basic
                                        usages. If you had any specific questions about how something works, do not hesitate to
                                        contact us from the'); ?> <a href="#support"><?php esc_html_e('Support Section'); ?></a>. <img
                                                draggable="false"
                                                class="emoji"
                                                alt="🙂"
                                                src="<?php echo qcld_ichart_img_url1; ?>/1f642.svg">
                                    </p></li>
                            </ol>


                            <h3><?php esc_html_e('Shortcode Generator'); ?></h3>
                            <p><?php esc_html_e('We encourage you to use the ShortCode generator found in the sidebar of your page/post
                                editor screen.'); ?></p>
                            <img src="<?php echo qcld_ichart_img_url1; ?>/shortcode-generator.png" alt="">
                            <img src="<?php echo qcld_ichart_img_url1; ?>/shortcode-modal.png" alt="">


                            <div>
                                <h3 class="ichart-plugin-title"><?php esc_html_e('Pro version Features'); ?></h3>
                                <p><?php esc_html_e('Here are some important features of the Pro Version. You can'); ?> <a
                                            style="text-decoration: none;" href="<?php echo esc_url('https://www.quantumcloud.com/products/iChart/'); ?>" target="_blank"><?php esc_html_e('Upgrade to Pro'); ?></a> <?php esc_html_e('to get those:'); ?></p>
                                <ol class="ichart-feature-list">
                                    <li><?php esc_html_e('Build Multiple type of Charts such as Pie Chart, Bar chart, Line Chart, Doughnut
                                        Chart, Polar Area Chart'); ?></li>
                                    <li><?php esc_html_e('Support jQuery Datatable'); ?></li>
                                    <li><?php esc_html_e('Support Multiple Datasets'); ?></li>
                                    <li><?php esc_html_e('Customize Background Colors for each Datasets'); ?></li>
                                    <li><?php esc_html_e('Three Positions to Display Chart informations Top, Bottom, and Right of the
                                        Chart'); ?></li>
                                    <li><?php esc_html_e('Supports Links for each data.'); ?></li>
                                    <li><?php esc_html_e('Give the option to hide Chart Information and show only the Chart'); ?></li>
                                    <li><?php esc_html_e('Support Custom Text to Show after Tooltip and information'); ?></li>
                                    <li><?php esc_html_e('Custom CSS to add your own style'); ?></li>
                                    <li><?php esc_html_e('Fully customizable control over Typography'); ?></li>
                                    <li><?php esc_html_e('Customizable Width, Text Color, Font Size, Background Color, Border etc.'); ?></li>
                                    <li><?php esc_html_e('Fully control over Show and Hide Horizontal and Vertical Gridlines'); ?></li>
                                    <li><?php esc_html_e('Import/Export Chart Data'); ?></li>
                                    <li><?php esc_html_e('Powerful short code Generator for both Gutenberg and Classic Editor'); ?></li>
                                    <li><?php esc_html_e('Live Chart Preview on Admin after Save'); ?></li>
                                </ol>
                            </div>


                            <div style="padding: 15px 10px; border: 1px solid #ccc; text-align: center; margin-top: 20px;">
                                <?php esc_html_e('Crafted By:'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com'); ?>" target="_blank"><?php esc_html_e('Web Design
                                    Company'); ?></a> - <?php esc_html_e('QuantumCloud'); ?>
                            </div>

                        </div>
                        <!-- /post-body-content -->


                    </div>
                    <!-- /post-body-->

                </div>
                <!-- /poststuff -->

            </div>

        </div>
        <!-- /poststuff -->


        <?php
    }
}

new Qcopd_info_page;