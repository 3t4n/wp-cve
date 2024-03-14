<?php $_poll = gdpol_get_poll(); ?>

<input type="hidden" name="gdpol[form]" value="edit"/>
<input type="hidden" name="gdpol[poll][id]" value="<?php echo $_poll->id; ?>"/>

<fieldset class="bbp-form gdpol-topic-poll-form">
    <legend><?php _e( 'Edit Topic Poll', 'gd-topic-polls' ); ?></legend>

    <div id="gdpol-poll-status" class="gdpol-field gdpol-field-checkbox">
        <label>
            <input<?php echo $_poll->status == 'enable' ? ' checked="checked"' : ''; ?> class="gdpol-poll-status" type="checkbox" name="gdpol[poll][status]" value="enable"/>
            <span><?php _e( 'Poll is enabled', 'gd-topic-polls' ); ?></span>
        </label>
        <p class="gdpol-description">
			<?php _e( 'If you choose to disable poll, poll definition and voting data will remain safe, and you can enable it or modify again later.', 'gd-topic-polls' ); ?>
        </p>
    </div>

	<?php bbp_get_template_part( 'gdpol-poll', 'form' ); ?>
</fieldset>
