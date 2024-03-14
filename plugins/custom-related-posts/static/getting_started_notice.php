<div class="updated crp_notice">
    <div class="crp_notice_dismiss">
        <a href="<?php echo esc_url( add_query_arg( array('crp_hide_new_notice' => wp_create_nonce( 'crp_hide_new_notice' ) ) ) ); ?>"> <?php _e( 'Hide this message', 'custom-related-posts' ); ?></a>
    </div>
    <h3>Hi there!</h3>
    <p>It looks like you're new to <strong>Custom Related Posts</strong>. Please check out our <a href="<?php echo admin_url( 'edit.php?post_type=' . CRP_POST_TYPE . '&page=crp_faq&sub=getting_started' ); ?>"><strong>Getting Started page</strong>!</a></p>
</div>