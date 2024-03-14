<script type="text/template" id="tmpl-aarambha-ds--content__choose">
    <div class="aarambha-ds--demo__choose">
        <h5><?php esc_html_e('What would you like to import?', 'aarambha-demo-sites'); ?></h5>

        <form action="#" class="aarambha-ds--content__form">
            <div class="content--chose">

                <div class="content-block chosen">
                    <div class="inner-block">
                        <label for="complete">
                            <input type="radio" name="contentType" id="complete" value="complete" checked class="import-selector">
                            <?php esc_html_e('Complete Demo', 'aarambha-demo-sites'); ?>
                        </label>
                    </div>
                    
                </div>
            </div>
        </form>

        <div class="content-selector--note hidden">
            <h6><?php esc_html_e('We suggest you to import Complete Demo.', 'aarambha-demo-sites'); ?></h6>
        </div>

    </div>
</script>