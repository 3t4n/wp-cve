<div class="row">
    <label for="desig-title"><?php _e( 'Role', 'wphr' ); ?> <span class="required">*</span></label>
    <span class="field">
        <input type="text" id="desig-title" name="title" value="" required="required">
    </span>
</div>

<div class="row">
    <label for="desig-desc"><?php _e( 'Description', 'wphr' ); ?></label>
    <span class="field">
        <textarea name="desig-desc" id="desig-desc" rows="6" cols="25" placeholder="<?php _e( 'Optional', 'wphr' ); ?>"></textarea>
    </span>
</div>

<?php wp_nonce_field( 'wphr-new-desig' ); ?>
<input type="hidden" name="action" id="desig-action" value="wphr-hr-new-desig">
<input type="hidden" name="desig_id" id="desig-id" value="0">
