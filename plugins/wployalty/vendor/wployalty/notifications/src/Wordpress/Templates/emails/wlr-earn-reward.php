<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */
defined('ABSPATH') OR die;
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php esc_attr_e('Congrats! You have earned reward','wp-loyalty-rules') ?></title>
</head>
<body style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
<div style="background: #f2f5f7;padding: 0px;margin: auto;padding-top: 50px;padding-bottom: 50px;">
    <div style="margin: 0px auto;max-width: 600px;background: #fff;padding: 32px;border-top: 4px solid #3439a2;">
        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size: 0px;width: 100%;background: #fff;" align="center" border="0">
            <tbody>
            <tr>
                <td style="text-align: center;vertical-align: top;direction: ltr;font-size: 0px;padding: 0px;">
                    <div style="margin:0px auto;max-width:600px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" style="font-size: 0px;width: 100%;" align="center" border="0">
                            <tbody>
                            <tr>
                                <td style="text-align: center;vertical-align: top;direction: ltr;font-size: 0px;padding: 0px;">
                                    <div style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                            <tbody>
                                            <tr>
                                                <td style="word-wrap: break-word;padding: 0px;" align="left">
                                                    {wlr_earn_reward_mail_content}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

