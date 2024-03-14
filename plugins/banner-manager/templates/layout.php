<div class="wrap">
    <div class="icon32" id="icon-tools"><br></div>

    <?php $this->output('title') ?>

    <?php if(isset($message)): ?>
    <div class="updated settings-error" id="setting-error-settings_updated">
    <p><strong><?php echo $message; ?></strong></p></div>
    <?php endif;?>

    <?php $this->output('content') ?>

</div>

<div class="clear"></div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    jQuery(".delete").click(function(){
        var href = jQuery(this).attr('href');
        var answer = confirm("<?php _e('Do you like delete this?', 'banner-manager');?>")
        if(answer)
        {
            window.location = href;
        }
        return false;
    });
});
</script>
