<form action="<?php echo admin_url('admin.php?page=the-tribal-plugin#settings');?>" method="post" class="dashboard-form">
    <div class="mb-3">
        <label class="form-label">Auto Or Manual publish of posts?</label>
        <select class="form-select" aria-label="Default select example" name="ttt_publish_post">
            <option value="manual" <?php echo ($publishPosts=='manual') ? 'selected':'';?>>Manual</option>
            <option value="auto" <?php echo ($publishPosts=='auto') ? 'selected':'';?>>Auto</option>
        </select>
        <div id="apiHelp" class="ttt-form-text">
            <div class="container-ttt-content">
                <div class="row">
                    <div class="col-md-1">AUTO: </div>
                    <div class="col-md-11">
                        All Posts will be automatically set to go LIVE on their Schedule dates. No user-interaction required
                    </div>
                </div>
                    <div class="row">
                    <div class="col-md-1">MANUAL: </div>
                    <div class="col-md-11">
                        All Posts will be marked as DRAFTS ready for you to tweak before marking LIVE. User-interaction required.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="mb-3">
        <label class="form-label">Select Default Author</label>
        <select class="form-select" aria-label="Default select author" name="ttt_post_author">
            <?php foreach($users as $user) : ?>
                <option value="<?php echo $user->ID;?>" <?php echo ($defaultAuthor == $user->ID) ? 'selected':'';?>>
                    <?php esc_html_e($user->display_name);?>
                </option>
            <?php endforeach; ?>
        </select>
        <div id="apiHelp" class="ttt-form-text">
            <div class="container-ttt-content">
                Choose the default Author you want all the automatically imported posts to be assigned against.
            </div>
        </div>
    </div>
    <hr>
    <input type="hidden" name="action" value="ttt_update_dashboard_user">
    <?php wp_nonce_field( 'ttt_client_update_plugin_' . get_current_user_id() ); ?>
    <button type="submit" class="btn btn-primary">SAVE SETTINGS</button>
</form>