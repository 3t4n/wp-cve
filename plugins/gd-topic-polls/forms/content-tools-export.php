<?php

use function Dev4Press\v43\Functions\panel;

?>
<div class="d4p-content">
    <div class="d4p-group d4p-group-information d4p-group-export">
        <h3><?php esc_html_e( 'Important Information', 'gd-topic-polls' ); ?></h3>
        <div class="d4p-group-inner">
            <p><?php esc_html_e( 'With this tool you export plugin settings into plain text file (JSON serialized content). Do not modify export file! Making changes to export file will make it unusable.', 'gd-topic-polls' ); ?></p>
            <p><?php esc_html_e( 'This tool doesn\'t export the topic polls and voting data. Topic polls are stored as posts, and votes are saved in the separate database table. The votes are connected to the posts table and they can\'t be exported separately.', 'gd-topic-polls' ); ?></p>
        </div>
    </div>

	<?php panel()->include_accessibility_control(); ?>
</div>
