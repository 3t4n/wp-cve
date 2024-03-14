<script type="text/template" id="tmpl-aarambha-ds--import__complete">
    <div class="aarambha-ds--import__action action--complete">

        <div class="swal2-icon swal2-success swal2-icon-show" style="display: flex;">
            <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
            <span class="swal2-success-line-tip"></span>
            <span class="swal2-success-line-long"></span>
            <div class="swal2-success-ring"></div>
            <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
            <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
        </div>

        <div class="complete--info">
            <h4><?php esc_html_e('Import Complete', 'aarambha-demo-sites'); ?></h4>
        </div>

        <div class="d-flex justify-between">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button-outline button-medium button-rounded">
                <?php esc_html_e('View Demo', 'aarambha-demo-sites'); ?>
            </a>

            <a href="<?php echo esc_url(admin_url('/customize.php')); ?>" class="button-dark button-medium button-rounded">
                <?php esc_html_e('Customize', 'aarambha-demo-sites'); ?>
            </a>
        </div>

        <!-- <div class="d-flex justify-center mt-40">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button-main button-rounded button-block">
                <?php esc_html_e('Upgrade to Pro', 'aarambha-demo-sites'); ?>
            </a>
        </div> -->

    </div>
</script>