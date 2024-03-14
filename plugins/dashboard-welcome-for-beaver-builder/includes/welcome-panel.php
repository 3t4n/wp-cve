<div id="bb-dashboard-welcome">
    <?php if ( ! current_user_can( 'edit_theme_options' ) ) { ?>
        <a class="welcome-panel-close" href="<?php echo admin_url('welcome=0'); ?>"><?php _e('Dismiss'); ?></a>
    <?php } ?>
    <?php self::render_template(); ?>
</div>

<?php if ( ! current_user_can( 'edit_theme_options' ) ) { ?>
<script type="text/javascript" id="bb-dashboard-welcome-js">
    ;(function($) {
        $(document).ready(function() {
            $('<div id="welcome-panel" class="welcome-panel"></div>').insertBefore('#dashboard-widgets-wrap').append($('#bb-dashboard-welcome'));
        });
    })(jQuery);
</script>
<?php } ?>
