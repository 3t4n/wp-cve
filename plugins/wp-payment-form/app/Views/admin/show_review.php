<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Imagetoolbar" content="No" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.0/css/lightgallery.min.css" integrity="sha512-F2E+YYE1gkt0T5TVajAslgDfTEUQKtlu4ralVq78ViNxhKXQLrgQLLie8u1tVdG2vWnB3ute4hcdbiBtvJQh0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.0/css/lightgallery-bundle.min.css" integrity="sha512-nUqPe0+ak577sKSMThGcKJauRI7ENhKC2FQAOOmdyCYSrUh0GnwLsZNYqwilpMmplN+3nO3zso8CWUgu33BDag==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <title><?php esc_html_e('Preview Form', 'wp-payment-form') ?></title>
    <?php
    wp_head();
    ?>
    <style type="text/css">
        .wpf_preview_title {
            display: inline-block;
            font-weight: bold;
            color: black !important;
        }

        .wpf_preview_title ul {
            list-style: none;
        }

        .wpf_preview_title ul li {
            display: inline-block;
            padding: 5px 12px;
            margin: 0;
        }

        .wpf_preview_action {
            display: inline-block;
            background: #dedede;
            color: #545454;
            border-radius: 4px;
            padding: 0px 8px;
            margin: 5px 0px;
            height: 30px;
        }

        .wpf_preview_body {
            padding: 40px 0px 40px 0px;
            width: 100%;
            background-color: #dedede;
            min-height: 85vh;
        }

        .wpf_preview_header {
            top: 0px;
            left: 0;
            right: 0px;
            padding: 0px 20px 0px 0px;
            background-color: #ebedee;
            color: black;
            max-height: 60px;
            font-size: 18px;
        }

        .wpf_preview_footer {
            display: block;
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px 0px;
        }

        html.wpf_go_full {
            padding-top: 0;
        }

        .wpf_go_full body {
            background: white;
        }

        .wpf_go_full .wpf_preview_body {
            background: white;
        }

        .wpf_go_full .wpf_preview_body #wpf_preview_top {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0;
            background: #feffff;
            color: #596075;
            box-shadow: 0px 5px 5px 6px #e6e6e6;
        }

        .wpf_preview_container_action {
            margin: 10px;
        }

        .wpf_preview_container_action span {
            font-size: 28px;
            color: #605858;
            cursor: pointer;
        }

        .wpf_hide {
            display: none;
        }

        .wpf_go_full .wpf_preview_footer {
            display: none;
        }

        @media only screen and (max-width: 522px) {
            .wpf_preview_footer {
                padding: 30px;
                font-size: 15px;
            }

            .wpf_preview_title {
                font-size: 15px;
            }

            .wpf_preview_action {
                font-size: 15px;
            }
        }
/* Step form css start */
        #wpf_svg_form_time {
            height: 15px;
            max-width: 80%;
            margin: 40px auto 20px;
            display: block;
        }

        #wpf_svg_form_time circle,
        #wpf_svg_form_time rect {
            fill: white;
        }

        .wpf_step_button {
            background: #ffffff;
            border-radius: 5px;
            padding: 10px 15px;
            display: inline-block;
            margin-right: 10px;
            font-weight: bold;
            color: #ff7518;
            cursor: pointer;
            box-shadow: 0px 1px 2px rgb(0 0 0 / 30%);
        }

        .wpf_step_disabled {
            display: none;
        }
        .wpf_step_section {
            padding: 2rem 0 !important;
        }


        /* Step Form */
        .step-form {
            display: flex;
            justify-content: center;
            text-align: center;
            overflow-x: auto;
        }
        .step-form.justify-start {
            justify-content: flex-start;
        }
        .step-form::-webkit-scrollbar {
            height: 4px;
            border-radius: 10px;
        }
        .step-form::-webkit-scrollbar-thumb {
            height: 4px;
            background: #ffd8c7;
            border-radius: 10px;
        }
        .step-form::-webkit-scrollbar-track {
            height: 4px;
            background: #e4e4e4;
            border-radius: 10px;
        }
        .step-form .step-form-item {
            flex-basis: 16.6667%;
            min-width: 130px;
            margin-bottom: 12px;
        }
        .step-form .step-form-item .step-form-item-content h2 {
            margin: 10px 0 0 0;
            font-size: 22px;
            padding-bottom: 3px;
            color:#d2d2d2
        }
        .step-form .step-form-item .step-form-item-content p {
            font-size: 14px;
            line-height: 20px;
            font-weight: 400;
            margin: 0;
        }
        .step-form .step-form-item .step-form-item-header {
            position: relative;
        }
        .step-form .step-form-item .step-form-item-header:before {
            content: '';
            width: 100%;
            height: 2px;
            position: absolute;
            left: 0;
            top: 50%;
            margin-top: -1px;
            background: #d2d2d2;
        }
        .step-form .step-form-item .step-form-item-header .number {
            position: relative;
            z-index: 1;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 24px;
            height: 24px;
            font-size: 14px;
            background: #fff;
            transition: .15s ease-out;  
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid #d2d2d2;
            font-weight: 500;
            color: #6b6b6b;
        }
        .step-form .step-form-item.success .step-form-item-header .number {
            border-color: #ff7518;
            color: #ff7518;
        }
        .step-form .step-form-item.success .step-form-item-content h2 {
            color: #ff7518;
        }
        .step-form .step-form-item.success .step-form-item-header:before {
            background: #ff7518;
        }
        .step-form .step-form-item.active .step-form-item-header .number {
            border-color: #596075;
            color: #596075;
        }
        .step-form .step-form-item.active .step-form-item-content h2 {
            color: #596075;
        }
        .step-form .step-form-item.active .step-form-item-header:before {
            background: #596075;
        }

    </style>
</head>

<body>
    <div id="wpf_preview_top">
        <div class="wpf_preview_header">
            <div class="wpf_preview_title">
                <ul>
                    <li class="wpf_form_name">
                        <?php echo intval($form->ID) . ' - ' . esc_attr($form->post_title) . ' ( Preview )';  ?>
                    </li>
                    <li>
                        <a href="<?php echo admin_url('admin.php?page=wppayform.php#/edit-form/' . intval($form_id) . '/form-builder') ?>">Edit Fields</a>
                    </li>
                </ul>
            </div>
            <div class="wpf_preview_action_block" style="float: right;display: flex;">
                <div class="wpf_preview_action">
                    [wppayform id="<?php echo intval($form_id); ?>"]
                </div>
                <div class="wpf_preview_container_action">
                    <span class=" wpf_hide wpf-preview-expand dashicons dashicons-editor-expand"></span>
                    <span class="wpf-preview-contrast dashicons dashicons-editor-contract"></span>
                </div>
            </div>

        </div>
        <div class="wpf_preview_body">
            <div class="wpf_form_preview_wrapper">
                <?php echo do_shortcode('[wppayform id="' . intval($form_id) . '"]'); ?>
            </div>
        </div>
        <div class="wpf_preview_footer">
            <p>You are seeing preview version of Paymattic. This form is only accessible for Admin users. Other users
                may not access this page. To use this for in a page please use the following shortcode: [wppayform
                id='<?php echo intval($form_id); ?>']</p>
        </div>
    </div>
    <?php
    wp_footer();
    ?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var status = window.localStorage.getItem('wpf_full_screen_preview');
            if (status == 'no') {
                jQuery('html').toggleClass('wpf_go_full');
                $('.wpf-preview-contrast').toggleClass("wpf_hide");
                $('.wpf-preview-expand').toggleClass("wpf_hide");
            }

            $('.wpf-preview-contrast').on('click', function() {
                jQuery('html').toggleClass('wpf_go_full');
                $(this).toggleClass("wpf_hide")
                $('.wpf-preview-expand').toggleClass("wpf_hide");
                window.localStorage.setItem('wpf_full_screen_preview', 'no');
            });

            $('.wpf-preview-expand').on('click', function() {
                jQuery('html').toggleClass('wpf_go_full');
                $(this).toggleClass("wpf_hide")
                $('.wpf-preview-contrast').toggleClass("wpf_hide");
                window.localStorage.setItem('wpf_full_screen_preview', 'yes');
            });
        });
    </script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.0/lightgallery.min.js" integrity="sha512-pG+XpUdyBtp28FzjpaIaj72KYvZ87ZbmB3iytDK5+WFVyun8r5LJ2x1/Jy/KHdtzUXA0CUVhEnG+Isy1jVJAbA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.0/plugins/thumbnail/lg-thumbnail.umd.min.js" integrity="sha512-hdzLQVAURjMzysJVkWaKWA2nD+V6CcBx6wH0aWytFnlmgIdTx/n5rDWoruSvK6ghnPaeIgwKuUESlpUhat2X+Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.0/plugins/zoom/lg-zoom.umd.min.js" integrity="sha512-++PKcAnn9Qf7G3Eu1WUmSR44yHOIH77vnAdLA70w9/PoECvbVzcW6lrrKR2xyfe4iYMbknCx5NSVQEBPl7pYPg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            lightGallery(document.getElementById('lightgallery'), {
                plugins: [lgZoom, lgThumbnail],
                licenseKey: 'your_license_key',
                speed: 500,
                // ... other settings
            });
        </script>
</body>

</html>