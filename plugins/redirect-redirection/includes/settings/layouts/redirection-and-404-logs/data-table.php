<?php
if (!defined("ABSPATH")) {
    exit();
}
?>
<div class="redirect-content__table-wrap">
    <div class="redirect-table">
        <table class="redirect-table">
            <thead>
                <tr>
                    <th><?php esc_html_e("URL visitors tried to access", "redirect-redirection"); ?></th>
                    <th><?php esc_html_e("URL where they landed", "redirect-redirection"); ?></th>
                    <th><?php esc_html_e("Date & Time", "redirect-redirection"); ?></th>
                    <th><?php esc_html_e("Type", "redirect-redirection"); ?></th>
                    <th><?php esc_html_e("Count", "redirect-redirection"); ?></th>
                    <th>
                        <div class="redirect-table__cell-select">
                            <span><?php esc_html_e("Show", "redirect-redirection"); ?></span>
                            <?php
                            $selected = isset(IrrPRedirection::$REDIRECTION_LOGS_FILTER["selectedId"]) ? (int) IrrPRedirection::$REDIRECTION_LOGS_FILTER["selectedId"] : "false";
                            IRRPHelper::customDropdown("redirection_logs_filter", IrrPRedirection::$REDIRECTION_LOGS_FILTER, $selected);
                            ?>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="ir-redirect-table-tbody">
                <?php echo $this->helper->buildLogsHtml($logs); ?>
            </tbody>
        </table>

        <?php $paginationClass = ($countPages > 1) ? "" : " ir-hidden"; ?>
        <div class="custom-pagination <?php esc_attr_e($paginationClass); ?>">
            <?php $this->helper->buildPaginationHtml($countLogs, $countPages, 0, "log"); ?>
        </div>
    </div>

</div>