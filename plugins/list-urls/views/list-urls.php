<form action="" method="post">
<h2><?php _e('Download the list of this site urls','wp-listurls');?></h2>
<p>Click the button below to download a csv file or all the URLs on your site. Please note if your site is very large it might take a while to generate the CSV.</p>
    <div>
        <fieldset><legend class="screen-reader-text"><span>Options</span></legend><label for="include_draft">
        <input name="include_draft" type="checkbox" id="include_draft" value="1">
        Include draft and unpublished?</label>
        </fieldset>
        <p>
            <input class="button" id="getUrls" name="wp_list_urls_download" type="submit" value="Download CSV">
        </p>
    </div>
    <?php wp_nonce_field( 'wp_list_urls_download_action' );?>
</form>