<div class="alignleft actions">
    <span style="display: inline-block;line-height: 2;margin-right: 6px;margin-bottom: 3px;"><?php esc_html_e( 'Export as', 'totalcontest' ); ?></span>
    <a style="display: inline-block;margin: 0;" href="<?php echo add_query_arg( [ 'export' => 'csv' ] ) ?>" class="button">CSV</a>
    <a style="display: inline-block;margin: 0;" href="<?php echo add_query_arg( [ 'export' => 'json' ] ) ?>" class="button">JSON</a>
    <a style="display: inline-block;margin: 0;" href="<?php echo add_query_arg( [ 'export' => 'html' ] ) ?>" class="button">HTML</a>
</div>
