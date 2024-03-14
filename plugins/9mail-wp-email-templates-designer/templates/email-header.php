<?php defined( 'ABSPATH' ) || exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo esc_html( get_bloginfo( 'name', 'display' ) ); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width">

    <!--[if !mso]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <![endif]-->

    <!--[if (mso 16)]>
    <style type="text/css">
        a {
            text-decoration: none
        }

        span {
            vertical-align: middle
        }

    </style>
    <![endif]-->

    <style type="text/css">
        #outlook a {
            padding: 0
        }

        a {
            text-decoration: none;
            word-break: break-word
        }

        body {
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: 0;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
            vertical-align: middle;
            background-color: transparent;
            max-width: 100%
        }

        p {
            display: block;
            margin: 0;
            line-height: inherit
        }

        div.emtmpl-responsive {
            display: inline-block
        }

        small {
            display: block;
            font-size: 13px
        }

        #emtmpl-transferred-content small {
            display: inline
        }

        #emtmpl-transferred-content td {
            vertical-align: top
        }

        table {
            font-family: Helvetica, Arial, sans-serif
        }

        [custom_style]
    </style>

    <!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

    <!--[if mso | IE]>
    <style type="text/css">
        .emtmpl-responsive {
            width: 100% !important;
        }
    </style>
    <![endif]-->

    <style type="text/css">

        @media only screen and (max-width: <?php echo esc_attr($responsive);?>px) {
            a {
                text-decoration: none
            }

            td {
                overflow: hidden
            }

            img {
                padding-bottom: 10px
            }

            .emtmpl-responsive, .emtmpl-responsive table {
                width: 100% !important;
                min-width: 100%
            }

            .emtmpl-button-responsive {
                width: 100% !important;
                min-width: 100%
            }

            table.emtmpl-no-full-width-on-mobile {
                min-width: 0 !important;
                width: auto !important
            }

            #emtmpl-transferred-content img {
                width: 100% !important
            }

            .emtmpl-responsive-padding {
                padding: 0 !important
            }

            .emtmpl-mobile-hidden {
                display: none !important
            }

            .emtmpl-responsive-center, .emtmpl-responsive-center p {
                text-align: center !important
            }

            .emtmpl-mobile-50 {
                width: 50% !important
            }

            .emtmpl-center-on-mobile p {
                text-align: center !important
            }

            #body_content {
                min-width: 100% !important;
            }
        }

        <?php echo wp_kses_post( apply_filters('emtmpl_after_render_style','') )?>
    </style>

</head>

<body vlink="#FFFFFF" <?php echo $direction == 'rtl' ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

<div id="wrapper" style="box-sizing:border-box;padding:0;margin:0;">
    <table border="0" cellpadding="0" cellspacing="0" height="100%" align="center" width="100%" style="margin: 0;<?php echo esc_attr( $bg_style ); ?>">
        <tbody>
        <tr>
            <td style="padding: 20px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" height="100%" align="center"
                       class="emtmpl-wrapper" style="font-size: 15px; margin: 0 auto; padding: 0; border-collapse: collapse;">
                    <tbody>
                    <tr>
                        <td align="center" valign="top" id="body_content" style="min-width: 600px">
                            <div class="emtmpl-responsive-min-width">
