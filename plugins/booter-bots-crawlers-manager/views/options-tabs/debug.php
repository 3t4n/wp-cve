<p class="notice notice-info">
	<?php esc_html_e( 'Log all block events with the rule which caused it and help you find rules causing false-positives.', 'booter' ); ?><br>
</p>

<table class="form-table">
    <tr valign="top">
        <td colspan="2">
            <button type="submit" class="button" name="clear_debug_log">
                <span class="dashicons dashicons-trash" aria-hidden="true"></span>
                <?php esc_html_e( 'Clear Log', 'booter' ); ?>
            </button>
        </td>
    </tr>
</table>

<textarea rows="30" style="width: 100%; direction: ltr; text-align: left;" readonly autocomplete="off"><?php echo htmlentities( \Upress\Booter\Logger::get_latest_logs() ); ?></textarea>
