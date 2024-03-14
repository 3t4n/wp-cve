<?php

use function Dev4Press\v43\Functions\panel;

?>

    <a href="<?php echo admin_url( 'admin.php?page=gd-topic-polls-votes' ); ?>" class="button-primary">
        <i aria-hidden="true" class="fa fa-file-text-o fa-fw"></i>
		<?php esc_html_e( 'Poll Votes log', 'gd-topic-polls' ); ?>
    </a>
    <a href="<?php echo admin_url( 'admin.php?page=gd-topic-polls-settings' ); ?>" class="button-secondary" style="float: right;">
        <i aria-hidden="true" class="fa fa-cogs fa-fw"></i>
		<?php esc_html_e( 'Settings', 'gd-topic-polls' ); ?>
    </a>

    <input type="hidden" name="page" value="gd-topic-polls-polls"/>
    <input type="hidden" value="getback" name="gdpol_handler"/>

<?php

$_grid = panel()->get_table_object();
$_grid->prepare_table();
$_grid->prepare_items();
$_grid->display();

include( GDPOL_PATH . 'forms/dialogs/polls.php' );
