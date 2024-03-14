<div class="d4p-group d4p-dashboard-card d4p-card-double d4p-dashboard-status d4p-dashboard-card-no-footer">
    <h3><?php esc_html_e( 'Plugin Status', 'gd-topic-polls' ); ?></h3>
    <div class="d4p-group-inner">
        <div>
			<?php if ( gdpol_settings()->get( 'global_enabled' ) ) { ?>
                <span class="d4p-card-badge d4p-badge-right d4p-badge-ok"><i class="d4p-icon d4p-ui-check"></i><?php esc_html_e( 'OK', 'gd-topic-polls' ); ?></span>
                <div class="d4p-status-message"><?php esc_html_e( 'Everything appears to be in order.', 'gd-topic-polls' ); ?></div>
			<?php } else { ?>
                <span class="d4p-card-badge d4p-badge-right d4p-badge-error"><i class="d4p-icon d4p-ui-warning"></i><?php esc_html_e( 'Error', 'gd-topic-polls' ); ?></span>
                <div class="d4p-status-message"><?php esc_html_e( 'Topic Polls integration has been disabled.', 'gd-topic-polls' ); ?></div>
			<?php } ?>
        </div>
    </div>
</div>