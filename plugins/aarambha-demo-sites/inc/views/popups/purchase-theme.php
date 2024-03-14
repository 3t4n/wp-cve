<script id="tmpl-aarambha-ds--purchase__theme" type="text/template">
    <div class="purchase--theme">

        <div class="swal2-icon swal2-{{data.icon}} swal2-icon-show" style="display: flex;">
            <div class="swal2-icon-content">i</div>
        </div>

        <div class="purhcase-theme--body">
            <h5><?php esc_html_e('Thank you for being our valued customer. Your support and trust are much appreciated.', 'aarambha-demo-sites'); ?></h5>
            <h4><?php esc_html_e('To access all Premium Demos / Templates, please Upgrade to Pro.', 'aarambha-demo-sites'); ?></h4>

            <# if (data.purchase.link) { #>
                <a href="{{data.purchase.link}}" class="button-main button-large button-rounded" target="_blank">
                    {{ data.purchase.label }}
                </a>
            <# } #>
        </div>
    </div>
</script>