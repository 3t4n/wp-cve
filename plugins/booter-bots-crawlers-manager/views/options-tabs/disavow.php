<?php
$lang = explode( '-', get_bloginfo( 'language' ) )[0];
$downloaded_at = get_transient( 'booter_disavow_list_downloaded_at' );
?>

<p>
    <?php esc_html_e( 'In most cases, search engines can evaluate which links to your site are trusted based on the linking website\'s reputation.', 'booter' ); ?><br>
    <?php esc_html_e( 'But sometimes you would like to make search engines disregard links from specific websites that have a high number of spammy links, artificial links, or low quality links pointing to your website.', 'booter' ); ?><br>
    <?php esc_html_e( 'Disavowing links allows you to do that in case you are currently experiencing such an attack or want to defend yourself in advance.', 'booter' ); ?><br>
    <?php esc_html_e( 'To do this, follow these steps:', 'booter' ); ?>
</p>
<ol>
   <li><?php esc_html_e( 'Download the provided list file', 'booter' ); ?></li>
   <li>
       <?php esc_html_e( 'Go to the appropriate disavow links page:', 'booter' ); ?>
       <a href="https://www.google.com/webmasters/tools/disavow-links?hl=<?php echo urlencode( $lang ); ?>&siteUrl=<?php echo urlencode( esc_url_raw( site_url() ) ); ?>" target="_blank" rel="nofollow noopener">
		   <?php esc_html_e( 'Google Search Console', 'booter' ); ?>
       </a>,
       <a href="https://www.bing.com/webmaster/help/how-to-disavow-links-0c56a26f#<?php echo urlencode( $lang ); ?>" target="_blank" rel="nofollow noopener">
		   <?php esc_html_e( 'Bing Webmaster Tools', 'booter' ); ?>
       </a>
   </li>
    <li><?php esc_html_e( 'Select your website', 'booter' ); ?></li>
    <li><?php esc_html_e( 'Click Deny Links', 'booter' ); ?></li>
    <li><?php esc_html_e( 'Select the file you downloaded and submit it', 'booter' ); ?></li>
</ol>
<p>
    <?php esc_html_e( '*It may take several weeks for search engines to process the information you\'ve uploaded.', 'booter' ); ?>
</p>

<div>
    <a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=booter_download_disavow_list&_wpnonce=<?php echo wp_create_nonce( 'download-disavow' ); ?>" target="_blank" class="button button-info" style="vertical-align: middle;">
        <span class="dashicons dashicons-download" aria-hidden="true"></span>
		<?php esc_html_e( 'Download Disavow List', 'booter' ); ?>
    </a>

    <span class="badge" style="margin: 0 4px; <?php echo false === $downloaded_at ? '' : 'background-color: #4AAE9B; color: #fff;'; ?>">
        <?php if ( false === $downloaded_at ) : ?>
            <?php esc_html_e( 'The File was not yet downloaded', 'booter' ); ?>
        <?php else : ?>
            <?php printf( esc_html__( 'The file was downloaded at %s', 'booter' ), date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $downloaded_at ) ); ?>
        <?php endif; ?>
    </span>
</div>
