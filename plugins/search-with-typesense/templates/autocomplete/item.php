<script type="text/html" id="tmpl-cm-autocomplete">
    <div class="aa-ItemWrapper">
        <div class="aa-ItemContent">
            <div class="aa-ItemIcon aa-ItemIcon--alignTop">
                <# if ( data.document.post_thumbnail !== '' && data.document.post_thumbnail !== undefined ) { #>
                <img
                        src="{{{data.document.post_thumbnail}}}"
                        alt="{{data.document.post_title}}"
                        width="40"
                        height="40"
                />
                <# } else { #>
                <img
                        src="<?php echo esc_url( plugins_url( 'assets/placeholder.jpg', CODEMANAS_TYPESENSE_FILE_PATH ) ); ?>"
                        alt="{{data.document.post_title}}"
                        width="40"
                        height="40"
                />
                <# } #>

            </div>
            <div class="aa-ItemContentBody">
                <div class="aa-ItemContentTitle">
                    {{{data.formatted.post_title}}}
                </div>
                <div class="aa-ItemContentDescription">
                    {{data.formatted.sliced_content}}
                </div>
            </div>

        </div>
    </div>
</script>