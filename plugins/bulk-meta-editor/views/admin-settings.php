<div class="wrap">
    <h1>Bulk Meta Editor</h1>
    <?php echo ($this->isYoastActive()) ? '<p style="color: #13AE4B; font-weight: 600">Yoast SEO Plugin detected</p>' : '<p style="color: #C80004; font-weight: 600">Yoast SEO Plugin not detected</p>'; ?>
    <p>Start batch processing by simply uploading a csv file. See guide on <a href="https://ariesdajay.com/guide-bulk-meta-editor/" target="_blank">how to fill out</a> the csv file. <span style="font-weight: bold;">Plugin currently supports Yoast SEO plugin.</span> Love my plugin? Consider giving it a review or you can donate to support its development. Upgrade to PRO. <a href="https://ariesdajay.com/bulk-meta-editor/" target="_blank">Learn more about the PRO features.</a></p>
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" enctype="multipart/form-data">
        <input id='bulk_seo_fixer_file_upload' name='file_upload' type='file' accept='.csv' />    
        <input type="hidden" name="action" value="arva_submit">
        <?php wp_nonce_field(); ?>
        <?php submit_button('Bulk Process'); ?>
    </form>
    <form action="https://www.paypal.com/donate" method="post" target="_blank">
        <input type="hidden" name="hosted_button_id" value="G65DTT264P86C" />
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
        <img alt="" border="0" src="https://www.paypal.com/en_PH/i/scr/pixel.gif" width="1" height="1" />
    </form>
</div>