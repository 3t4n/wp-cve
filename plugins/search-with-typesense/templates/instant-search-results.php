<?php
/*
 * tmpl-cmswt-Result-itemTemplate--[post-type-slug]
 * for different templates for different post types add the post type slug instead of [post-type-slug] as the id
 * example tmpl-cm-typesense-shortcode-page-search-results or tmpl-cm-typesense-shortcode-book-search-results
 */
?>
<script type="text/html" id="tmpl-cmswt-Result-itemTemplate--default">
    <# if(data.taxonomy === undefined){ #>
    <div class="hit-header">
        <# var imageHTML = '';
        if(data.post_thumbnail_html !== undefined && data.post_thumbnail_html !== ''){
        imageHTML = data.post_thumbnail_html
        }else if(data.post_thumbnail !== undefined && data.post_thumbnail !== ''){
        imageHTML = `<img src="${data.post_thumbnail}"
                          alt="${data.post_title}"
                          class="ais-Hit-itemImage"
        />`
        }
        else{
        imageHTML = `<img src="<?php echo esc_url( plugins_url( '/assets/images/placeholder-300x300.jpg', CODEMANAS_TYPESENSE_FILE_PATH ) ) ?>"
                          alt="${data.post_title}"
                          class="ais-Hit-itemImage"
        />`
        }
        #>
        <# if(imageHTML !== ''){ #>
        <a href="{{{data._highlightResult.permalink.value}}}" class="hit-header--link" rel="nofollow noopener">{{{imageHTML}}}</a>
        <# } #>
    </div>
    <# } #>
    <div class="hit-content">
        <# if(data._highlightResult.permalink !== undefined ) { #>
        <a href="{{{data._highlightResult.permalink.value}}}" rel="nofollow noopener"><h5 class="title">
                {{{data.formatted.post_title}}}</h5></a>
        <# } #>
        <# if( data.post_type === 'post' ) { #>
        <div class="hit-meta">
            <span class="posted-on">
                <time datetime="">{{data.formatted.postedDate}}</time>
            </span>
            <# if ( Object.keys(data.formatted.cats).length > 0 ) { #>
            <div class="hit-cats">
                <# for ( let key in data.formatted.cats ) { #>
                <div class="hit-cat"><a href="{{{data.formatted.cats[key]}}}">{{{key}}}</a>,</div>
                <# } #>
            </div>
            <# } #>
        </div>
        <# } #>
        <div class="hit-description">{{{data.formatted.post_content}}}</div>
        <div class="hit-link">
            <a href="{{data.permalink}}"><?php _e( 'Read More...', 'search-with-typesense' ); ?></a>
        </div>
    </div>
</script>