<div>
    <input type="radio" id="r8_tsm_active" name="r8_tsm_active" value="active"<?php if ( $active !== 'inactive' ) { echo ' checked="checked"'; } ?>>
    <label for="r8_tsm_active">Active</label>
</div>
<div>
    <input type="radio" id="r8_tsm_inactive" name="r8_tsm_active" value="inactive"<?php if ( $active === 'inactive' ) { echo ' checked="checked"'; } ?>>
    <label for="r8_tsm_inactive">Inactive <?php echo $scheduled_status; ?></label>
</div>
