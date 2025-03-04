<?php
/**
 * Feedback mail template.
 *
 * @var $data - fields data.
 *
 * This template can be overridden by copying it to yourtheme/docspress/feedback-mail.php.
 *
 * @author  nK
 * @package docspress/Templates
 *
 * @version 1.0.0
 */

?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>
        <?php echo esc_html( get_the_title() ); ?>
    </title>
    <style>
    /* -------------------------------------
        GLOBAL RESETS
    ------------------------------------- */

    /*All the styling goes here*/

    img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%;
    }

    body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
    }

    table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
        font-family: sans-serif;
        font-size: 14px;
        vertical-align: top;
    }

    /* -------------------------------------
        BODY & CONTAINER
    ------------------------------------- */

    .body {
        background-color: #f6f6f6;
        width: 100%;
    }

    /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
    .container {
        display: block;
        margin: 0 auto !important;
        /* makes it centered */
        max-width: 580px;
        padding: 10px;
        width: 580px;
    }

    /* This should also be a block element, so that it will fill 100% of the .container */
    .content {
        box-sizing: border-box;
        display: block;
        margin: 0 auto;
        max-width: 580px;
        padding: 10px;
    }

    /* -------------------------------------
        HEADER, FOOTER, MAIN
    ------------------------------------- */
    .header {
        clear: both;
        margin-bottom: 10px;
        text-align: center;
        width: 100%;
    }
    .header td {
        color: #000;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
    }

    .main {
        background: #ffffff;
        border-radius: 3px;
        width: 100%;
    }

    .wrapper {
        box-sizing: border-box;
        padding: 20px;
    }

    .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
    }

    .footer {
        clear: both;
        margin-top: 10px;
        text-align: center;
        width: 100%;
    }
    .footer td,
    .footer p,
    .footer span,
    .footer a {
        color: #999999;
        font-size: 12px;
        text-align: center;
    }

    /* -------------------------------------
        TYPOGRAPHY
    ------------------------------------- */
    h1,
    h2,
    h3,
    h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        margin-bottom: 30px;
    }

    h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize;
    }

    p,
    ul,
    ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        margin-bottom: 15px;
        padding-left: 0;
    }
    p li,
    ul li,
    ol li {
        list-style-position: inside;
        margin-left: 5px;
    }

    a {
        color: #3498db;
        text-decoration: underline;
    }

    /* -------------------------------------
        ALL FIELDS OUTPUT
    ------------------------------------- */
    .field-row-label {
        color: #000000;
        padding-bottom: 3px;
        word-break: break-all;
    }
    .field-row-value {
        padding-top: 3px;
        padding-bottom: 20px;
        word-break: break-all;
    }

    /* -------------------------------------
        OTHER STYLES THAT MIGHT BE USEFUL
    ------------------------------------- */
    .last {
        margin-bottom: 0;
    }

    .first {
        margin-top: 0;
    }

    .align-center {
        text-align: center;
    }

    .align-right {
        text-align: right;
    }

    .align-left {
        text-align: left;
    }

    .clear {
        clear: both;
    }

    .mt0 {
        margin-top: 0;
    }

    .mb0 {
        margin-bottom: 0;
    }

    hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        margin: 20px 0;
    }

    /* -------------------------------------
        RESPONSIVE AND MOBILE FRIENDLY STYLES
    ------------------------------------- */
    @media only screen and (max-width: 620px) {
        table[class=body] h1 {
            font-size: 28px !important;
            margin-bottom: 10px !important;
        }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
            font-size: 16px !important;
        }
        table[class=body] .wrapper,
        table[class=body] .article {
            padding: 10px !important;
        }
        table[class=body] .content {
            padding: 0 !important;
        }
        table[class=body] .container {
            padding: 0 !important;
            width: 100% !important;
        }
        table[class=body] .main {
            border-left-width: 0 !important;
            border-radius: 0 !important;
            border-right-width: 0 !important;
        }
        table[class=body] .img-responsive {
            height: auto !important;
            max-width: 100% !important;
            width: auto !important;
        }
    }

    /* -------------------------------------
        PRESERVE THESE STYLES IN THE HEAD
    ------------------------------------- */
    @media all {
        .ExternalClass {
            width: 100%;
        }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }
        #MessageViewBody a {
            color: inherit;
            text-decoration: none;
            font-size: inherit;
            font-family: inherit;
            font-weight: inherit;
            line-height: inherit;
        }
    }

    </style>
</head>
<body class="">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
        <div class="content">

            <!-- START HEADER -->
            <div class="header">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                <tr>
                <td class="content-block">
                    <?php
                    echo '<span style="color: ' . esc_attr( 'negative' === $data['feedback_type'] ? '#F24040' : '#3f9c53' ) . ';">';
                    echo 'negative' === $data['feedback_type'] ? esc_html__( '😡 Negative Feedback', 'docspress' ) : esc_html__( '😀 Positive Feedback', 'docspress' );
                    echo '</span>';
                    ?>
                </td>
                </tr>
            </table>
            </div>
            <!-- END HEADER -->

            <!-- START CENTERED WHITE CONTAINER -->
            <table role="presentation" class="main">

                <!-- START MAIN CONTENT AREA -->
                <tr>
                    <td class="wrapper">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                        <td>
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="field-row"><tbody>
                                <tr><td class="field-row-label">
                                    <strong>
                                        <?php echo esc_html__( 'Article', 'docspress' ); ?>
                                    </strong>
                                </td></tr>
                                <tr><td class="field-row-value">
                                    <?php
                                    echo esc_html( get_the_title( $data['post'] ) );
                                    echo '<br/><a href="' . esc_url( get_permalink( $data['post'] ) ) . '">' . esc_url( get_permalink( $data['post'] ) ) . '</a>';
                                    ?>
                                </td></tr>
                            </tbody></table>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="field-row"><tbody>
                                <tr><td class="field-row-label">
                                    <strong>
                                        <?php echo esc_html__( 'From', 'docspress' ); ?>
                                    </strong>
                                </td></tr>
                                <tr><td class="field-row-value">
                                    <?php
                                    // translators: %1$s - user name.
                                    // translators: %2$s - user IP address.
                                    echo sprintf( esc_html__( '%1$s (IP: %2$s)', 'docspress' ), esc_html( $data['from'] ), esc_html( $data['ip_address'] ) );
                                    ?>
                                </td></tr>
                            </tbody></table>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="field-row"><tbody>
                                <tr><td class="field-row-label">
                                    <strong>
                                        <?php echo esc_html__( 'Suggestion', 'docspress' ); ?>
                                    </strong>
                                </td></tr>
                                <tr><td class="field-row-value">
                                    <?php
                                    // phpcs:ignore
                                    echo $data['suggestion'];
                                    ?>
                                </td></tr>
                            </tbody></table>
                        </td>
                        </tr>
                    </table>
                    </td>
                </tr>
                <!-- END MAIN CONTENT AREA -->

            </table>
            <!-- END CENTERED WHITE CONTAINER -->

            <!-- START FOOTER -->
            <div class="footer">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                <tr>
                <td class="content-block">
                    <?php echo esc_html__( 'This e-mail was sent from a suggestion form on one of your DocsPress articles.', 'docspress' ); ?>
                </td>
                </tr>
            </table>
            </div>
            <!-- END FOOTER -->

        </div>
        </td>
        <td>&nbsp;</td>
    </tr>
    </table>
</body>
</html>
