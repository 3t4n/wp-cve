<?php
namespace Hurrytimer;

use Hurrytimer\Utils\Helpers;

?>

<div class="wrap hurryt-plugin-settings">
    <h2>Settings</h2>
    <form method="post" action="options.php">
        <?php
        settings_fields("hurrytimer_settings");
        do_settings_sections("hurrytimer_settings");
        submit_button();
        ?>
    </form>
    <button type="button" class="button button-default hurrytResetAllEvergreenCampaigns  hurryt-block"
            data-cookie-prefix="<?php echo Cookie_Detection::COOKIE_PREFIX ?>"
            data-url="<?php echo Helpers::createResetAllEvergreenCampaignsUrl('admin') ?>">Reset all evergreen
        campaigns for me...
    </button>
    <br>
    <button type="button" class="button button-default hurrytResetAllEvergreenCampaigns hurryt-block"
            data-cookie-prefix="<?php echo Cookie_Detection::COOKIE_PREFIX ?>"
            data-url="<?php echo Helpers::createResetAllEvergreenCampaignsUrl('all') ?>">Reset all evergreen
        campaigns for all visitors...
    </button>
</div>