<script id="tmpl-aarambha-ds--activate__theme" type="text/template">
    <div class="activate--theme">

        <div class="swal2-icon swal2-{{data.icon}} swal2-icon-show" style="display: flex;">
            <div class="swal2-icon-content">i</div>
        </div>

        <div class="activate-theme--body">
            <h5><?php esc_html_e('Thank you for purchasing our super cool product. Wish you all the success.', 'aarambha-demo-sites'); ?></h5>
            <h4><?php echo wp_kses_post( 'Please activate your license key of {{ data.theme }} to access it’s all Demos / Templates.' ); ?></h4>

            <# if (data.activate.link) { #>
                <a href="{{data.activate.link}}" class="button-main button-large button-rounded">
                    {{ data.activate.label }}
                </a>
            <# } #>
        </div>
    </div>
</script>