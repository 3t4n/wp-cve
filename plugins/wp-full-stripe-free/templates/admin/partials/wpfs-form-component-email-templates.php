<?php
/** @var $view MM_WPFS_Admin_FormView */
/** @var $form */
/** @var $data */
?>
<div class="wpfs-form__cols wpfs-form__cols--templates">
    <div class="wpfs-form__col">
        <div class="wpfs-list-title">
            <?php esc_html_e('Available email types:', 'wp-full-stripe-admin'); ?>
        </div>
        <div id="wpfs-templates-container" class="wpfs-list wpfs-list--sm wpfs-list--status"></div>
    </div>
    <div class="wpfs-form__col">
        <div id="wpfs-template-details-container" class="wpfs-form-block"></div>
    </div>
</div>
<input id="<?php $view->emailTemplates()->id(); ?>" name="<?php $view->emailTemplates()->name(); ?>" value="" <?php $view->emailTemplates()->attributes(); ?>>
<script type="text/javascript">
    var wpfsEmailTemplates = <?php echo json_encode($data->emailTemplates); ?>;
</script>
<script type="text/template" id="wpfs-email-template">
    <div class="wpfs-list__text">
        <div class="wpfs-list__title"><%= typeLabel %></div>
        <div class="wpfs-status-bullet <% if (enabled === true) { %> wpfs-status-bullet--green <% } else { %> wpfs-status-bullet--red <% } %> wpfs-list__bullet">
            <% if (enabled === true) { %><?php esc_html_e('Enabled', 'wp-full-stripe-admin'); ?><% } else { %><?php esc_html_e('Disabled', 'wp-full-stripe-admin'); ?><% } %>
        </div>
    </div>
</script>
<script type="text/template" id="wpfs-email-template-details">
    <div class="wpfs-form-block__title"><%= typeLabel %></div>
    <div>
        <p><%= typeDescription %></p>
    </div>
    <div class="wpfs-form-group">
        <label class="wpfs-toggler">
            <span><?php esc_html_e('Disabled', 'wp-full-stripe-admin'); ?></span>
            <input type="checkbox" id="wpfs-send-email-toggle" name="wpfs-send-email-toggle" <% if (enabled === true) { %>checked<% } %>>
            <span class="wpfs-toggler__switcher"></span>
            <span><?php esc_html_e('Enabled', 'wp-full-stripe-admin'); ?></span>
        </label>
    </div>
</script>