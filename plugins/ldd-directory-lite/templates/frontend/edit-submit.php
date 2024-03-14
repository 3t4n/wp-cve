<?php
/*
* File version: 2
*/
?>
<div class="container-fluid">
    <div class="row bump-down-more">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary"><?php esc_html_e('Update Listing', 'ldd-directory-lite'); ?></button>
            <a href="<?php echo remove_query_arg(array('id', 'edit')); ?>" class="btn btn-default" role="button"><?php esc_html_e('Cancel', 'ldd-directory-lite'); ?></a>
        </div>
    </div>
</div>