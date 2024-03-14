<?php
    /** @var $view MM_WPFS_Admin_FormView */
    /** @var $form */
    /** @var $data */
?>
<div class="wpfs-form-group">
    <label for="" class="wpfs-form-label"><?php $view->redirectType()->label(); ?></label>
    <div class="wpfs-form-check-list">
        <div class="wpfs-form-check">
            <?php $options = $view->redirectType()->options(); ?>
            <input id="<?php $options[0]->id(); ?>" name="<?php $options[0]->name(); ?>" <?php $options[0]->attributes(); ?> value="<?php $options[0]->value(); ?>" <?php echo $form->redirectOnSuccess === '0'  ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[0]->id(); ?>"><?php $options[0]->label(); ?></label>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[1]->id(); ?>" name="<?php $options[1]->name(); ?>" <?php $options[1]->attributes(); ?> value="<?php $options[1]->value(); ?>" <?php echo $form->redirectOnSuccess === '1' && $form->redirectToPageOrPost === '1'   ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[1]->id(); ?>"><?php $options[1]->label(); ?></label>
            <div class="wpfs-form-check__control">
                <div class="wpfs-ui wpfs-form-select">
                    <select id="<?php $view->redirectPagePostId()->id(); ?>" name="<?php $view->redirectPagePostId()->name(); ?>" <?php $view->redirectPagePostId()->attributes(); ?>>
                        <?php
                        foreach ( $data->thankYouPages as $page ) {
                        ?>
                            <option value="<?php echo $page->id; ?>" <?php echo $page->id == $form->redirectPostID ? 'selected': ''; ?>><?php echo $page->title; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="wpfs-form-help">
                    <?php esc_html_e( 'Customize the Thank you page with placeholder tokens.', 'wp-full-stripe-admin' ); ?>
                    <a class="wpfs-btn wpfs-btn-link" href="https://support.paymentsplugin.com/article/4-how-to-create-a-thank-you-page#customize-your-page-with-dynamic-content" target="_blank"><?php esc_html_e( 'Learn more', 'wp-full-stripe-admin' ); ?></a>
                </div>
            </div>
        </div>
        <div class="wpfs-form-check">
            <input id="<?php $options[2]->id(); ?>" name="<?php $options[2]->name(); ?>" <?php $options[2]->attributes(); ?> value="<?php $options[2]->value(); ?>" <?php echo $form->redirectOnSuccess === '1' && $form->redirectToPageOrPost === '0'   ? 'checked' : ''; ?>>
            <label class="wpfs-form-check-label" for="<?php $options[2]->id(); ?>"><?php $options[2]->label(); ?></label>
            <div class="wpfs-form-check__control">
                <input id="<?php $view->redirectUrl()->id(); ?>" name="<?php $view->redirectUrl()->name(); ?>" <?php $view->redirectUrl()->attributes(); ?> value="<?php echo $form->redirectUrl !== 'http://' ? $form->redirectUrl : ''; ?>" placeholder="<?php $view->redirectUrl()->placeholder(); ?>">
            </div>
        </div>
    </div>
</div>
