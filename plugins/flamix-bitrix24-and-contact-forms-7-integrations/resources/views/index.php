<?php

use Flamix\Plugin\WP\Markup;
use Flamix\Plugin\WP\Recommendations;
use FlamixLocal\CF7\Settings\Setting;

echo Markup::adminMessage(__('Install the Lead interceptor interceptor <a href="' . Setting::PLUGIN_URL . '" target="_blank">module in Bitrix24</a>!', FLAMIX_BITRIX24_CF7_CODE));
?>
<div class="wrap">
    <h2><?php _e(Setting::PLUGIN_TITLE, FLAMIX_BITRIX24_CF7_CODE); ?></h2>

    <form method="post" action="options.php">
        <?php settings_fields(Setting::getOptionName('group')); ?>
        <table class="form-table">
            <?php Markup::markup_input(Setting::getOptionName('lead_domain'), [
                'value' => Setting::getOption('lead_domain'),
                'label' => __('Bitrix24 Portal Domain', FLAMIX_BITRIX24_CF7_CODE),
                'placeholder' => 'company.bitrix24.com',
                'description' => __('Your Bitrix24 portal domain', FLAMIX_BITRIX24_CF7_CODE),
            ]); ?>

            <?php Markup::markup_input(Setting::getOptionName('lead_api'), [
                'value' => Setting::getOption('lead_api'),
                'label' => __('Flamix Plugin Secret Key', FLAMIX_BITRIX24_CF7_CODE),
                'placeholder' => 'xxxxxx.....xxxxx',
                'description' => __('Your Flamix Secret KEY (Do not confuse with License Key). Read FAQ <a href="https://flamix.solutions/about/contacts.php#FAQ" target="_blank">Where can I get the secret integration key</a>', FLAMIX_BITRIX24_CF7_CODE),
            ]); ?>

            <?php Markup::markup_input(Setting::getOptionName('lead_backup_email'), [
                'value' => Setting::getOption('lead_backup_email'),
                'label' => 'Backup mailbox',
                'placeholder' => 'backup@mail.com',
                'description' => 'When an error occurs, send a message to this mail',
            ]); ?>
        </table>

        <input type="submit" class="button-primary" value="<?php _e('Save', FLAMIX_BITRIX24_CF7_CODE); ?>"/>
    </form>

    <table style="width: 95%;">
        <tr class="form-field">
            <td style="width: 20%; vertical-align: top;"><?php include 'configuration.php'; ?></td>
            <td style="width: 30%; vertical-align: top;"><?php include 'recommendations.php'; ?></td>
            <td style="width: 30%; vertical-align: top;"><h2>How its works</h2><iframe width="560" height="315" src="https://www.youtube.com/embed/goZAdrh_gHM" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></td>
            <td style="width: 20%; vertical-align: top; text-align: right;"><?php echo Recommendations::banner(FLAMIX_BITRIX24_CF7_CODE); ?></td>
        </tr>
    </table>
</div>