<?php

use function Dev4Press\v43\Functions\panel;

?>
<div class="d4p-content">
    <div class="d4p-group d4p-group-information">
        <h3><?php esc_html_e( 'Important Information', 'gd-topic-polls' ); ?></h3>
        <div class="d4p-group-inner">
			<?php esc_html_e( 'This tool can remove plugin settings saved in the WordPress options table added by the plugin and you can remove polls votes table and all logged data.', 'gd-topic-polls' ); ?>
            <br/><br/>
			<?php esc_html_e( 'Removal of polls from WordPress posts and postmeta tables is not possible using these tools. To remove polls use Polls panel.', 'gd-topic-polls' ); ?>
            <br/><br/>
			<?php esc_html_e( 'Deletion operations are not reversible, and it is highly recommended to create database backup before proceeding with this tool.', 'gd-topic-polls' ); ?>
			<?php esc_html_e( 'If you choose to remove plugin settings, once that is done, all settings will be reinitialized to default values if you choose to leave plugin active.', 'gd-topic-polls' ); ?>
        </div>
    </div>

    <div class="d4p-group d4p-group-tools">
        <h3><?php esc_html_e( 'Remove plugin settings', 'gd-topic-polls' ); ?></h3>
        <div class="d4p-group-inner">
            <label>
                <input type="checkbox" class="widefat" name="gdpoltools[remove][settings]" value="on"/> <?php esc_html_e( 'Main Settings', 'gd-topic-polls' ); ?>
            </label>
            <label>
                <input type="checkbox" class="widefat" name="gdpoltools[remove][objects]" value="on"/> <?php esc_html_e( 'Objects Settings', 'gd-topic-polls' ); ?>
            </label>
        </div>
    </div>

    <div class="d4p-group d4p-group-tools">
        <h3><?php esc_html_e( 'Remove database data and tables', 'gd-topic-polls' ); ?></h3>
        <div class="d4p-group-inner">
            <p style="font-weight: bold"><?php esc_html_e( 'This will remove all votes you might have for the polls.', 'gd-topic-polls' ); ?></p>
            <label>
                <input type="checkbox" class="widefat" name="gdpoltools[remove][drop]" value="on"/> <?php esc_html_e( 'Remove plugins database table and all data in them', 'gd-topic-polls' ); ?>
            </label>
            <label>
                <input type="checkbox" class="widefat" name="gdpoltools[remove][truncate]" value="on"/> <?php esc_html_e( 'Remove all data from database table', 'gd-topic-polls' ); ?>
            </label><br/>
            <hr/>
            <p><?php esc_html_e( 'Database tables that will be affected', 'gd-topic-polls' ); ?>:</p>
            <ul style="list-style: inside disc;">
                <li><?php echo gdpol_db()->votes; ?></li>
            </ul>
        </div>
    </div>

    <div class="d4p-group d4p-group-tools">
        <h3><?php esc_html_e( 'Disable Plugin', 'gd-topic-polls' ); ?></h3>
        <div class="d4p-group-inner">
            <label>
                <input type="checkbox" class="widefat" name="gdpoltools[remove][disable]" value="on"/> <?php esc_html_e( 'Disable plugin', 'gd-topic-polls' ); ?>
            </label>
        </div>
    </div>

	<?php panel()->include_accessibility_control(); ?>
</div>
