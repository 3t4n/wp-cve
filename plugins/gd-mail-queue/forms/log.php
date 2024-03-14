<?php

if (!defined('ABSPATH')) { exit; }

include(GDMAQ_PATH.'forms/shared/top.php');

$_operation = isset($_GET['operation']) && !empty($_GET['operation']) ? d4p_sanitize_slug($_GET['operation']) : '';

if (!in_array($_operation, array('mail', 'queue'))) {
    $_operation = '';
}

?>

    <div class="d4p-content-right d4p-content-full">
        <form method="get" action="">
            <input type="hidden" name="page" value="gd-mail-queue-log" />
            <input type="hidden" value="getback" name="gdmaq_handler" />
            <input type="hidden" value="<?php echo esc_attr($_operation); ?>" name="operation" />

            <?php

            require_once(GDMAQ_PATH.'core/grids/log.php');

            $_grid = new gdmaq_emails_log();
            $_grid->prepare_items();
            $_grid->views();
            $_grid->search_box(__("Search", "gd-mail-queue"), 'log');
            $_grid->display();

            ?>
        </form>
    </div>

<?php

include(GDMAQ_PATH.'forms/shared/bottom.php');
include(GDMAQ_PATH.'forms/dialogs/log.php');
