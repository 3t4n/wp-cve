<div class="wrap wphr wphr-hr-audit-log">

    <h2><?php _e( 'Audit Log', 'wphr' ); ?></h2>

    <div id="wphr-audit-log-table-wrap">

        <div class="list-table-inner">

            <form method="get">
                <input type="hidden" name="page" value="wphr-audit-log">
                <?php
                $audit_log = new \WPHR\HR_MANAGER\Admin\Auditlog_List_Table();
                $audit_log->prepare_items();
                $audit_log->views();

                $audit_log->display();
                ?>
            </form>

        </div><!-- .list-table-inner -->
    </div><!-- .list-table-wrap -->

</div>
