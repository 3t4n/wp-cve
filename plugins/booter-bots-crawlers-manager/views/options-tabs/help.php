<toggle-panel start-open>
    <template slot="title">
        <span class="dashicons dashicons-sos" aria-hidden="true"></span>
        <?php esc_html_e( 'Instructions for use in case of damage treatment', 'booter' ); ?>
    </template>
    <p>
		<?php esc_html_e( 'This is recommended only if you think there are a high number of spammy links, artificial links, or low quality links pointing to your website', 'booter' ); ?><br>
		<?php esc_html_e( 'and the links have caused manual action against your website, or there is a high probability that it will happen in the future (due to paid links or other link fraud that violate search engine quality guidelines).', 'booter' ); ?>
    </p>
    <ol>
        <li><?php esc_html_e( 'Activate the plugin.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Enable the 404 error log option.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Set the access rate limit.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Make sure that you do not disallow the links you want to be removed in the robots.txt file, you should allow search engines to attempt to retrieve the page and fail.', 'booter' ); ?></li>
        <li>
			<?php echo wp_kses_post(
				printf(
					__( 'Download the <a%s>disavow list</a> (spam domains or links that we want search engines to ignore when they see links to our website on them) created by Booter and submit it to search engines. (It\'s important to protect all variations of the URL that lead to the content you want to not index, with www, https, or without.)', 'booter' ),
					' href="#booter-disavow" aria-controls="booter-disavow" class="js-booter-tab"'
				)
			); ?>
        </li>
        <li><?php esc_html_e( 'Watch the 404 log, try to find common parts in the URLs that repeats most often.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Enter the common parts to the "reject links" page, and ensure the rejection code is 410.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Clear the 404 error log.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Repeat the process once every few hours until the 404 error log remains blank.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Check the status of your website\'s index coverage every few days.', 'booter' ); ?></li>
    </ol>
</toggle-panel>


<toggle-panel start-open>
    <template slot="title">
        <span class="dashicons dashicons-shield" aria-hidden="true"></span>
	    <?php esc_html_e( 'Instructions for routine maintenance and defense', 'booter' ); ?>
    </template>
    <p>
		<?php esc_html_e( 'This is recommended if your site has successfully remedied links from spam websites, or there are no artificial or low-quality links pointing to your website.', 'booter' ); ?><br>
    </p>
    <ol>
        <li><?php esc_html_e( 'Activate the plugin.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Enable the 404 error log option.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Disable the access rate limit (to not waste server resources).', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Submit a list disavowed backlinks and/or domains to search engines, this will request the search engine to not index the link if it is coming from one of the links or domains provided in the list.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Enable the robots.txt management, Set a crawl rate other than the default, and include the rejected links in the disallowed list.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Check the status of your website\'s index coverage every few days.', 'booter' ); ?></li>
    </ol>
</toggle-panel>


<toggle-panel start-open>
    <template slot="title">
        <span class="dashicons dashicons-search" aria-hidden="true"></span>
	    <?php esc_html_e( 'Hide search results within a single day', 'booter' ); ?>
    </template>
    <p>
		<?php esc_html_e( 'The URL removal tool allows you to temporarily hide (up to 90 days) pages from search engine results for websites you own.', 'booter' ); ?><br>
		<?php esc_html_e( 'It\'s important to remember that hiding the page does not imply removing it from the search index, it will still be indexed and crawled, they just won\'t be shown in the search results.', 'booter' ); ?><br>
		<?php esc_html_e( 'Search engines will continue to crawl the URL, so it\'s important to "reject" the URL with Booter. If the hiding period will end without rejecting the URL this page will appear again in search results.', 'booter' ); ?><br>
    </p>
    <ol>
        <li>
            <a href="https://www.google.com/webmasters/tools/url-removal" target="_blank" rel="nofollow noopener">
				<?php esc_html_e( 'Open the URL removal tool.', 'booter' ); ?>
            </a>
        </li>
        <li><?php esc_html_e( 'Click Hide temporarily.', 'booter' ); ?></li>
        <li>
			<?php esc_html_e( 'Enter the relative path for the URL you want to hide.', 'booter' ); ?>
			<?php echo esc_html__( 'For example:', 'booter' ); ?><br>
            <code>/public_html</code>
            <code>/index.php?</code>
            <code>/test</code>
        </li>
        <li><?php esc_html_e( 'Click continue.', 'booter' ); ?></li>
        <li><?php esc_html_e( 'Choose clear cache and temporarily hide all URLs starting with...', 'booter' ); ?></li>
        <li><?php esc_html_e( 'To permanently remove a URL, reject the URL with Booter.', 'booter' ); ?></li>
    </ol>
</toggle-panel>
