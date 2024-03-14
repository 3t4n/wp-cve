<script type="text/template" id="tmpl-aarambha-ds--step__importing">
    <div class="aarambha-ds--import__action">

        {{{ data.loading }}}

        <h4><?php esc_html_e(
                'Please wait while the content is being imported!',
                'aarambha-demo-sites'
            );
            ?></h4>

        <span class="sub-header"><?php esc_html_e('Please wait while the demo site is being installed.This process may take upto 10 minutes to complete. Please do not refresh or close this page.', 'aarambha-demo-sites'); ?></span>

    </div>
</script>

<script type="text/template" id="tmpl-aarambha-ds--step__button">
    <div class="aarambha-ds--import__event">
        <div class="import-progress--bar">
           {{ data.prepare }}
        </div>
    </div>
</script>