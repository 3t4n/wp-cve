<?php $_poll = gdpol_get_poll(); ?>

<input type="hidden" name="gdpol[form]" value="new"/>
<input type="hidden" name="gdpol[poll][id]" value="<?php echo $_poll->id; ?>"/>

<fieldset class="bbp-form gdpol-topic-poll-form">
    <legend><?php _e( 'Create New Topic Poll', 'gd-topic-polls' ); ?></legend>

    <div id="gdpol-poll-status" class="gdpol-field gdpol-field-checkbox">
        <label>
            <input<?php echo $_poll->status == 'enable' ? ' checked="checked"' : ''; ?> class="gdpol-poll-status" type="checkbox" name="gdpol[poll][status]" value="enable"/>
            <span><?php _e( 'Add poll to this topic', 'gd-topic-polls' ); ?></span>
        </label>
    </div>

	<?php bbp_get_template_part( 'gdpol-poll', 'form' ); ?>
</fieldset>
