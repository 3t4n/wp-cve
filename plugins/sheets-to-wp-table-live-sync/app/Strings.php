<?php
/**
 * Implementing Internationalize supports for the plugin.
 *
 * @since 2.12.15
 * @package SWPTLS
 */

namespace SWPTLS;

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for registering strings.
 *
 * @since 2.12.15
 * @package SWPTLS
 */
class Strings {
	/**
	 * Returns the strings.
	 *
	 * @return array
	 */
	public static function get() {
		return [
			/**
			 * Dashboard.
			 */
			'db-title' => __( 'Create beautiful tables from Google Sheets', 'sheetstowptable' ),
			'new-tables'  => __( 'Create new table', 'sheetstowptable' ),
			'tables-created'  => __( 'Tables created', 'sheetstowptable' ),
			'loading'  => __( 'Loading...', 'sheetstowptable' ),

			'no-tables-have-been-created-yet'  => __( 'No tables have been created yet', 'sheetstowptable' ),
			'tables-will-be-appeared-here-once-you-create-them'  => __( 'Tables will be appeared here once you create them', 'sheetstowptable' ),

			'create-new-table'  => __( 'Create new table', 'sheetstowptable' ),
			'need-help'  => __( 'Need help?', 'sheetstowptable' ),
			'watch-now'  => __( 'Watch Now', 'sheetstowptable' ),
			'search-tb'  => __( 'Search tables', 'sheetstowptable' ),

			/**
			 * Tooltip
			 */

			'tooltip-0'  => __( 'This title will be shown on the top of your table', 'sheetstowptable' ),
			'tooltip-1'  => __( 'Copy the URL of your Google Sheet from your browser and paste it in the box below. Make sure your sheet is public.', 'sheetstowptable' ),
			'tooltip-2'  => __( 'Copy the Google Sheet URL from your browser and paste it here to create table. Changing this URL will change your table.', 'sheetstowptable' ),
			'tooltip-3'  => __( 'If this is enabled, the box showing number of entries will be hidden for the viewers', 'sheetstowptable' ),
			'tooltip-4'  => __( 'If enabled, the search box will be hidden for viewers', 'sheetstowptable' ),
			'tooltip-5'  => __( 'Enable this option to hide the table title for the viewers', 'sheetstowptable' ),
			'tooltip-6'  => __( 'If checked, the sorting option will be disabled for viewers when they view the table', 'sheetstowptable' ),
			'tooltip-7'  => __( 'If enabled the entry info showing number of current entries out of all the entries will be hidden', 'sheetstowptable' ),
			'tooltip-8'  => __( 'Enable this to hide the pagination for viewers', 'sheetstowptable' ),
			'tooltip-9'  => __( 'Display multiple tables using tabs. Just like your google sheets', 'sheetstowptable' ),
			'tooltip-10'  => __( 'Title of the tab group', 'sheetstowptable' ),
			'tooltip-11'  => __( 'Enter the title for this tab', 'sheetstowptable' ),
			'tooltip-12'  => __( 'Select the table which will be shown in this tab from the dropdown below', 'sheetstowptable' ),
			'tooltip-13'  => __( 'Enable this to hide the selected columns on mobile', 'sheetstowptable' ),
			'tooltip-14'  => __( 'Enable this to hide the selected column on desktop', 'sheetstowptable' ),
			'tooltip-15'  => __( 'This is the list of the hidden columns. Removing a column from this list will make them visible again', 'sheetstowptable' ),
			'tooltip-16'  => __( 'This is the list of the hidden rows. Removing a row from this list will make them visible again', 'sheetstowptable' ),
			'tooltip-17'  => __( 'This is the list of the hidden cells. Removing a cell from this list will make them visible again', 'sheetstowptable' ),
			'tooltip-18'  => __( 'Use direct google sheet embed link with text content, More flexiable and easy to use', 'sheetstowptable' ),
			'tooltip-19'  => __( 'Use old way [  ] pair to generate link', 'sheetstowptable' ),
			'tooltip-20'  => __( 'Copy the URL of your Google Sheet from your browser and paste it in the box below. Make sure your sheet is public..', 'sheetstowptable' ),
			'tooltip-21'  => __( 'Copy the URL of your Google Sheet and paste it here.', 'sheetstowptable' ),
			'tooltip-22'  => __( 'Allows the users to download the table in the format that you select below', 'sheetstowptable' ),
			'tooltip-23'  => __( 'For the links on the table you can decide what will happen when a link is clicked', 'sheetstowptable' ),
			'tooltip-24'  => __( 'The link opens in the same tab where the table is', 'sheetstowptable' ),
			'tooltip-25'  => __( 'The link will be opened on a new tab', 'sheetstowptable' ),
			'tooltip-26'  => __( "Allow 'Smart links' to load multiple embeed links with text from Google Sheet. To use this makesure 'Smart link' support selected from 'link support mechanism' in 'Settings' menu. However, if there is no embeed links in the Sheet or your'e using pretty link format, then there is no need to use it, which will reduces loading time and makes the table load faster.", 'sheetstowptable' ),
			'tooltip-27'  => __( 'Allow images to be loaded from sheets. You can use it to import images if you have them in Google Sheets. However, if there is no image in the Sheet, there is no need to use it, which will reduces loading time and makes the table load faster.', 'sheetstowptable' ),
			'tooltip-28'  => __( 'If enabled the table will load faster for the viewers', 'sheetstowptable' ),
			'tooltip-29'  => __( 'Choose how the table cell will look', 'sheetstowptable' ),
			'tooltip-30'  => __( 'Cell will expand according to the content', 'sheetstowptable' ),
			'tooltip-31'  => __( 'Cell will be wrapped according to the content', 'sheetstowptable' ),
			'tooltip-32'  => __( 'Choose how the table will look on devices with small screens', 'sheetstowptable' ),
			'tooltip-33'  => __( 'Let the browser decide the behavior of the responsiveness', 'sheetstowptable' ),
			'tooltip-34'  => __( 'The table rows will be collapse on  each other in one column', 'sheetstowptable' ),
			'tooltip-35'  => __( 'A horizontal scrollbar will appear for the users to scroll the table left and right', 'sheetstowptable' ),
			'tooltip-36'  => __( "Enable this feature to showcase your Sheet's merged cells seamlessly on the WordPress frontend table.", 'sheetstowptable' ),
			'tooltip-37'  => __( 'Select the number of rows to show per page', 'sheetstowptable' ),
			'tooltip-38'  => __( 'Select the table height. If the table height is lower there will be a vertical scrollbar to scroll through the rows', 'sheetstowptable' ),
			'tooltip-39'  => __( "Quickly change your table's look and feel using themes", 'sheetstowptable' ),
			'tooltip-40'  => __( 'Enable this feature to import colors and text styles directly from a Google Sheet, instead of using the predefined theme above', 'sheetstowptable' ),
			'tooltip-41'  => __( 'If this is checked, the tab group title will not be visible in the front end', 'sheetstowptable' ),
			'tooltip-42'  => __( 'Choose where you want to show the tab', 'sheetstowptable' ),
			'tooltip-43'  => __( 'The tabs will be shown first and the table will be shown after it', 'sheetstowptable' ),
			'tooltip-44'  => __( 'The table will be shown first and the tab will be shown after it', 'sheetstowptable' ),

			'tooltip-45'  => __( 'Set the cursor behavior on your table', 'sheetstowptable' ),
			'tooltip-46'  => __( 'You can easily highlight text for copy-paste', 'sheetstowptable' ),
			'tooltip-47'  => __( 'You can effortlessly move the table horizontally (left-right).', 'sheetstowptable' ),

			/**
			 * AddNewTable.
			 */
			'add-new-table'  => __( 'Add new table', 'sheetstowptable' ),

			/**
			 * App.
			 */
			'please-activate-your-license'  => __( 'Please activate your license', 'sheetstowptable' ),
			'activate'  => __( 'Activate', 'sheetstowptable' ),

			/**
			 * ChangesLog.
			 */
			'db-headway'  => __( 'What’s new?', 'sheetstowptable' ),

			/**
			 * CreateTab.
			 */
			'save-changes'  => __( 'Save changes', 'sheetstowptable' ),
			'tab-settings'  => __( 'Tab settings', 'sheetstowptable' ),
			'managing-tabs'  => __( 'Managing tabs', 'sheetstowptable' ),
			'table-settings'  => __( 'Table Settings', 'sheetstowptable' ),

			/**
			 * CreateTable.
			 */
			'create-table'  => __( 'Create table', 'sheetstowptable' ),
			'on-free-plan-tables-can'  => __( 'On free plan tables can be created from the first sheet tab only. ', 'sheetstowptable' ),
			'get-pro'  => __( 'Get Pro ', 'sheetstowptable' ),
			'pro'  => __( 'PRO', 'sheetstowptable' ),
			'to-create-table-from-any-tab'  => __( 'to create table from any tab of your Google SpreadSheet.', 'sheetstowptable' ),
			'copy-the-url'  => __( 'Copy the URL of your Google Sheet and paste it here.', 'sheetstowptable' ),
			'google-sheet-url'  => __( 'Add Google Sheet URL', 'sheetstowptable' ),
			'creating-new-table'  => __( 'Creating new table', 'sheetstowptable' ),

			/**
			 * CtaAdd.
			 */
			'create-wooCommerce-product'  => __( 'Create WooCommerce product table easily!', 'sheetstowptable' ),

			'install-stock-sync-for-wooCommerce'  => __( 'Install Stock Sync for WooCommerce', 'sheetstowptable' ),

			'and sync your store products'  => __( 'and sync your store products to Google Sheets. Use the synced Google Sheet to create table. It’s easy!', 'sheetstowptable' ),

			'install-now'  => __( 'Install now', 'sheetstowptable' ),

			/**
			 * DataSource.
			 */
			'table-title'  => __( 'Table title', 'sheetstowptable' ),
			'please-reduce-title-to-save'  => __( 'Please reduce title to save.', 'sheetstowptable' ),
			'gsu'  => __( 'Google Sheet URL', 'sheetstowptable' ),

			/**
			 * DisplaySettings
			 */
			'table-top-elements'  => __( 'Table top elements', 'sheetstowptable' ),
			'hide-ent'  => __( 'Hide entries', 'sheetstowptable' ),
			'hide-search-box'  => __( 'Hide search box', 'sheetstowptable' ),
			'swap'  => __( 'Swap', 'sheetstowptable' ),
			'hide-title'  => __( 'Hide title', 'sheetstowptable' ),
			'disable-sorting'  => __( 'Disable sorting', 'sheetstowptable' ),
			'table-bottom-ele'  => __( 'Table bottom elements', 'sheetstowptable' ),
			'hide-entry-info'  => __( 'Hide entry info', 'sheetstowptable' ),
			'hide-pagi'  => __( 'Hide pagination', 'sheetstowptable' ),

			/**
			 * Documentation.
			 */
			'doc-1'  => __( 'How to Add Line Breaks in a WP Table?', 'sheetstowptable' ),
			'doc-1-ans'  => __( "To incorporate a line break within a WordPress table, simply employ the `<span class='date-format'><code> &lt;br&gt; </code></span>` tag within the content of a specific cell.", 'sheetstowptable' ),

			'doc-2'  => __('What date format can I use?', 'sheetstowptable'),
			'doc-2-ans'  => __("When working with date formats in your table, you have various options to choose from. Avoid using a <span class='date-format'><code>comma separator</code></span>, but consider formats such as <br> e.g. <span class='date-format'><code>2005-06-07</code> | <code>2005/06/07</code> | <code>2005 06 07</code> | <code>June 7- 2005</code> | <code>June 7 2005</code> | <code>7th June 2005</code></span>",'sheetstowptable'),

			'doc-3'  => __('Which one is better for link support?', 'sheetstowptable'),
			'doc-3-ans'  => __(
				"For enhanced link functionality, it's advisable to utilize <span class='date-format'><code>Smart Link</code></span> over the <span class='date-format'><code>Pretty Link</code></span>. Smart Link is known for its robust and user-friendly features.",'sheetstowptable'),

			'doc-4'  => __('Why my table design breaks?', 'sheetstowptable'),
			'doc-4-ans'  => __(
				"If you encounter design issues in your table, it may be due to the use of  <span class='date-format'><code>multiline content </code></span>within a single cell in your Google spreadsheets. To address this, insert the <span class='date-format'><code> &lt;br&gt; </code></span> tag within the text of the specific cell to create multiline breaks. Additionally, using comma-separated date formats can disrupt the table cell layout.",'sheetstowptable'),

			'doc-5'  => __('Why my table taking too long to load?', 'sheetstowptable'),
			'doc-5-ans'  => __(
				"If your table is taking too long to load, it may be due to the presence of a substantial amount of data in Google Sheets, along with images and links. If your sheet doesn't contain images or links, consider disabling <span class='date-format'><code>links and Image support</code></span> within the <span class='date-format'><code>Utility</code></span> settings of Table Customization to expedite table loading. Utilizing the cache feature can also improve loading times.",'sheetstowptable'),

			'doc-6'  => __('How long table data can take to update if Cache is activated?', 'sheetstowptable'),
			'doc-6-ans'  => __(
				"When the cache feature is active, updates to your table data are not reliant on your browser's cache. Changes made in your sheet may take approximately 15-40 seconds to reflect. To view the latest updates, simply refresh the page after this timeframe.",'sheetstowptable'),

			'doc-7'  => __('How do I find tutorials about using the plugin?', 'sheetstowptable'),
			'doc-7-ans'  => __(
				"Please visit our <a target='_blank' href='https://www.youtube.com/playlist?list=PLd6WEu38CQSyY-1rzShSfsHn4ZVmiGNLP'>YouTube playlist</a>, where we post tutorials on how you can use different features of the plugin.",'sheetstowptable'),

			// Documentation other text.
			'documentation'  => __( 'Documentation', 'sheetstowptable' ),
			'vt'  => __( 'Video Tutorial', 'sheetstowptable' ),
			'prof-need-help'  => __( 'Get professional help via our ticketing system', 'sheetstowptable' ),
			'faq'  => __( 'Frequently Asked Question', 'sheetstowptable' ),
			'get-plugin'  => __( 'Get the most out of the plugin. Go Pro!', 'sheetstowptable' ),
			'unlock-all'  => __( 'Unlock all features', 'sheetstowptable' ),
			'link-supp'  => __( 'Link Support to import links from sheet', 'sheetstowptable' ),
			'pre-built-sheet-style'  => __( 'Pre-built amazing table styles where Each styles is different from one another', 'sheetstowptable' ),
			'hide-row-based-on'  => __( 'Hide your google sheet table rows based on your custom row selection', 'sheetstowptable' ),
			'unlimited-fetch-from-gs'  => __( 'Unlimited Row Sync and fetch unlimited rows from Google spreadsheet', 'sheetstowptable' ),

			/**
			 * EditTab.
			 */
			'shortcode'  => __( 'Shortcode', 'sheetstowptable' ),
			'manage-tab'  => __( 'Managing tabs', 'sheetstowptable' ),

			/**
			 * EditTable
			 */
			'export-json'  => __( 'Export as JSON', 'sheetstowptable' ),
			'export-csv'  => __( 'Export as CSV', 'sheetstowptable' ),
			'export-pdf'  => __( 'Export as PDF', 'sheetstowptable' ),
			'export-excel'  => __( 'Export as Excel', 'sheetstowptable' ),
			'print'  => __( 'Print Table', 'sheetstowptable' ),
			'copy'  => __( 'Copy to Clipboard', 'sheetstowptable' ),

			'live-sync-is-limited'  => __( 'Live sync is limited to 50 rows.', 'sheetstowptable' ),
			'upgrade-pro'  => __( 'Upgrade to Pro', 'sheetstowptable' ),
			'for-showing-full'  => __( 'for showing full google sheet.', 'sheetstowptable' ),
			'data-source'  => __( 'Data source', 'sheetstowptable' ),
			'display-settings'  => __( 'Display settings', 'sheetstowptable' ),
			'tc'  => __( 'Table customization', 'sheetstowptable' ),
			'hide-row-col'  => __( 'Hide Rows/Column', 'sheetstowptable' ),
			'save'  => __( 'Save', 'sheetstowptable' ),
			'lp'  => __( 'Loading Preview...', 'sheetstowptable' ),

			/**
			 * Header
			 */
			'dashboard'  => __( 'Dashboard', 'sheetstowptable' ),
			'get-unlimited-access'  => __( 'Get unlimited access', 'sheetstowptable' ),

			/**
			 * ManageTabs
			 */

			 'CTF'  => __( 'Create tables first to manage tabs', 'sheetstowptable' ),
			'manage-tab-is-not-available'  => __( 'Manage tab is not available because you don’t have any tables yet. Please create tables first', 'sheetstowptable' ),
			'display-multiple'  => __( 'Display multiple tables using tabs.', 'sheetstowptable' ),
			'manage-new-tabs'  => __( 'Manage new tabs', 'sheetstowptable' ),
			'tabs-created'  => __( 'Tabs created', 'sheetstowptable' ),
			'no-tabs-found'  => __( 'No tabs found with the key', 'sheetstowptable' ),

			/**
			 * ManagingTabs
			 */
			'select-table-for-tab'  => __( 'Select table for this tab.', 'sheetstowptable' ),
			'tab-title'  => __( 'Tab Title', 'sheetstowptable' ),
			'add-tab'  => __( 'Add tab', 'sheetstowptable' ),
			'tab-group-title'  => __( 'Tab group title', 'sheetstowptable' ),

			/**
			 * Promo
			 */
			'lts-data'  => __( 'Let’s bring meaning to your data', 'sheetstowptable' ),
			'create-beautifully-designed-tables'  => __( 'Create beautifully designed tables from Google sheets and customize as you need.', 'sheetstowptable' ),

			/**
			 * Recommendation
			 */
			'other-prodct'  => __( 'Our Other Products', 'sheetstowptable' ),
			'remarkable-product'  => __( 'Experience remarkable WordPress products with a new level of power, beauty, and human-centered designs. Think you know WordPress products? Think Deeper!', 'sheetstowptable' ),

			/**
			 * RowSettings
			 */
			'hidden-cell'  => __( 'Hidden cells', 'sheetstowptable' ),
			'hide-cell'  => __( 'Hide Cells', 'sheetstowptable' ),
			'hidden-row'  => __( 'Hidden rows', 'sheetstowptable' ),
			'hide-row'  => __( 'Hide Rows', 'sheetstowptable' ),
			'hidden-column'  => __( 'Hidden Columns', 'sheetstowptable' ),
			'hide-column'  => __( 'Hide Columns', 'sheetstowptable' ),
			'hide-desktop'  => __( 'Hide on Desktop', 'sheetstowptable' ),
			'hide-mobile'  => __( 'Hide on Mobile', 'sheetstowptable' ),
			'click-on-the-cells'  => __( 'Click on the cells on the table below that you want to hide', 'sheetstowptable' ),
			'click-on-the-rows'  => __( 'Click on the rows on the table below that you want to hide', 'sheetstowptable' ),
			'click-on-the-col'  => __( 'Click on the column on the table below that you want to hide', 'sheetstowptable' ),

			/**
			 * Settings
			 */
			'new'  => __( 'New', 'sheetstowptable' ),
			'save-settings'  => __( 'Save settings', 'sheetstowptable' ),
			'custom-css'  => __( 'Custom CSS', 'sheetstowptable' ),
			'recommended'  => __( 'Recommended', 'sheetstowptable' ),
			'with-pretty-link'  => __( 'With Pretty link', 'sheetstowptable' ),
			'with-smart-link'  => __( 'With Smart link', 'sheetstowptable' ),
			'asynchronous-loading'  => __( 'Asynchronous loading', 'sheetstowptable' ),

			'async-content'  => __( 'Enable this feature to load the table faster. The table will load in the frontend after loading all content with a pre-loader. If this feature is disabled then the table will load with the reloading of browser every time.', 'sheetstowptable' ),

			'choose-link-support'  => __( 'Choose your link support mechanism in WP Tables', 'sheetstowptable' ),
			'write-own-css'  => __( 'Write your own custom CSS to design the table or the page itself. Activate the Pro extension to enable custom CSS option', 'sheetstowptable' ),
			'performance'  => __( 'Performance', 'sheetstowptable' ),

			'script-content'  => __( 'Choose how you want to load your table scripts', 'sheetstowptable' ),
			'global-loading'  => __( 'Global loading', 'sheetstowptable' ),
			'global-loading-details'  => __( 'Load the scripts on all the pages and posts in your website.', 'sheetstowptable' ),
			'optimized-loading'  => __( 'Optimized laoding', 'sheetstowptable' ),
			'optimized-loading-details'  => __( 'Load scripts only on the relevant pages/posts in your website where the table is added.', 'sheetstowptable' ),

			/**
			 * SupportModel
			 */
			'WPPOOL'  => __( 'WPPOOL', 'sheetstowptable' ),
			'powered-by'  => __( 'Powered by', 'sheetstowptable' ),
			'default-mail'  => __( 'Default Email App', 'sheetstowptable' ),
			'open-default-mail'  => __( 'Open your default email app', 'sheetstowptable' ),
			'copy-content'  => __( 'Copy email address to your clipboard', 'sheetstowptable' ),
			'yahoo'  => __( 'Yahoo', 'sheetstowptable' ),
			'yahoo-content'  => __( 'Open Yahoo in browser', 'sheetstowptable' ),
			'outlook'  => __( 'Outlook', 'sheetstowptable' ),
			'outlook-content'  => __( 'Open Outlook in browser', 'sheetstowptable' ),
			'gmail'  => __( 'Gmail', 'sheetstowptable' ),
			'gmail-content'  => __( 'Open Gmail in browser', 'sheetstowptable' ),
			'support-modal-title'  => __( 'Select the convenient option to connect with us', 'sheetstowptable' ),

			/**
			 * TabItem
			 */
			'Delete'  => __( 'Delete', 'sheetstowptable' ),
			'tab-group-delete'  => __( 'This Tab Group will be deleted. It will not appear on your website anymore, if you used it somewhere', 'sheetstowptable' ),
			'Cancel'  => __( 'Cancel', 'sheetstowptable' ),
			'confirmation-delete'  => __( 'Are you sure to delete this Tab Group?', 'sheetstowptable' ),

			/**
			 * TableCustomization
			 */

			'Utility'  => __( 'Utility', 'sheetstowptable' ),
			'Style'  => __( 'Style', 'sheetstowptable' ),
			'Layout'  => __( 'Layout', 'sheetstowptable' ),
			'Theme'  => __( 'Theme', 'sheetstowptable' ),
			'let-export'  => __( 'Let user export table', 'sheetstowptable' ),
			'Excel'  => __( 'Excel', 'sheetstowptable' ),
			'JSON'  => __( 'JSON', 'sheetstowptable' ),
			'Copy'  => __( 'Copy', 'sheetstowptable' ),
			'CSV'  => __( 'CSV', 'sheetstowptable' ),
			'PDF'  => __( 'PDF', 'sheetstowptable' ),
			'Print'  => __( 'Print', 'sheetstowptable' ),
			'link-behave'  => __( 'Link redirection behaviour', 'sheetstowptable' ),
			'open-ct-window'  => __( 'Opens in current window', 'sheetstowptable' ),
			'open-new-window'  => __( 'Opens in a new window', 'sheetstowptable' ),

			'cursor-behavior'  => __( 'Cursor behavior inside the table', 'sheetstowptable' ),
			'highlight-and-copy'  => __( 'Text selection mode', 'sheetstowptable' ),
			'left-to-right'  => __( 'Table navigation mode (left-right)', 'sheetstowptable' ),

			'import-links'  => __( 'Import links from sheet', 'sheetstowptable' ),
			'import-image'  => __( 'Import images from sheet', 'sheetstowptable' ),
			'cache-table'  => __( 'Cache table for faster loading time', 'sheetstowptable' ),
			'cell-formatting'  => __( 'Cell formatting style', 'sheetstowptable' ),
			'expanded'  => __( 'Expanded', 'sheetstowptable' ),
			'wrapped'  => __( 'Wrapped', 'sheetstowptable' ),
			'responsive-style'  => __( 'Responsive style', 'sheetstowptable' ),
			'default'  => __( 'Default', 'sheetstowptable' ),
			'collapsible-style'  => __( 'Collapsible Style', 'sheetstowptable' ),
			'scrollable-style'  => __( 'Scrollable Style', 'sheetstowptable' ),
			'row-per-page'  => __( 'Rows to show per page', 'sheetstowptable' ),
			'All'  => __( 'All', 'sheetstowptable' ),
			'100'  => __( '100', 'sheetstowptable' ),//phpcs:ignore
			'50'  => __( '50', 'sheetstowptable' ),//phpcs:ignore
			'25'  => __( '25', 'sheetstowptable' ),//phpcs:ignore
			'15'  => __( '15', 'sheetstowptable' ),//phpcs:ignore
			'10'  => __( '10', 'sheetstowptable' ),//phpcs:ignore
			'5'  => __( '5', 'sheetstowptable' ),//phpcs:ignore
			'1'  => __( '1', 'sheetstowptable' ),//phpcs:ignore
			'-1'  => __( '-1', 'sheetstowptable' ),//phpcs:ignore

			'table-height'  => __( 'Table height', 'sheetstowptable' ),

			'default-height'  => __( 'Default height', 'sheetstowptable' ),
			'400px'  => __( '400px', 'sheetstowptable' ),
			'500px'  => __( '500px', 'sheetstowptable' ),
			'600px'  => __( '600px', 'sheetstowptable' ),
			'700px'  => __( '700px', 'sheetstowptable' ),
			'800px'  => __( '800px', 'sheetstowptable' ),
			'900px'  => __( '900px', 'sheetstowptable' ),
			'1000px'  => __( '1000px', 'sheetstowptable' ),
			'select-theme'  => __( 'Select theme', 'sheetstowptable' ),

			'import-color-from-sheet'  => __( 'Import colors and text styles from sheet', 'sheetstowptable' ),
			'hover-style'  => __( 'Hover Style', 'sheetstowptable' ),
			'unlock'  => __( 'Unlock', 'sheetstowptable' ),
			'colored-column'  => __( 'Colored Column', 'sheetstowptable' ),
			'Taliwind-Style'  => __( 'Taliwind Style', 'sheetstowptable' ),
			'Dark-Table'  => __( 'Dark Table', 'sheetstowptable' ),
			'Stripped-Table'  => __( 'Stripped Table', 'sheetstowptable' ),
			'Default-Style'  => __( 'Default Style', 'sheetstowptable' ),

			'merge-cells'  => __( 'Merge cells', 'sheetstowptable' ),
			'beta'  => __( 'Beta', 'sheetstowptable' ),
			'merge-cells-notice'  => __( 'Sorting feature can’t be used when your sheet contains vertically merged cells and the Merge Cells feature is active. To use sorting feature, please disable the Merge Cells feature. To get Merge Cell toggle, go to Table Customization > Style > Merge Cells.', 'sheetstowptable' ),

			/**
			 * Admin menus
			 */
			'Dashboard'  => __( 'Dashboard', 'sheetstowptable' ),
			'manage-tab-submenu'  => __( 'Manage Tab', 'sheetstowptable' ),
			'Settings'  => __( 'Settings', 'sheetstowptable' ),
			'get-started'  => __( 'Get Started', 'sheetstowptable' ),
			'recommended-plugins'  => __( 'Recommended Plugins', 'sheetstowptable' ),

			/**
			 * Data Table
			 */
			// 'filtering'  => __( 'Show _MENU_ entries', 'sheetstowptable' ),
			'filtering_show'  => __( 'Show', 'sheetstowptable' ),
			'filtering_entries'  => __( 'entries', 'sheetstowptable' ),
			'search'  => __( 'Search:', 'sheetstowptable' ),
			// 'dataTables_info'  => __( 'Showing _START_ to _END_ of _TOTAL_ entries', 'sheetstowptable' ),
			'dataTables_info_showing'  => __( 'Showing', 'sheetstowptable' ),
			'dataTables_info_to'  => __( 'to', 'sheetstowptable' ),
			'dataTables_info_of'  => __( 'of', 'sheetstowptable' ),
			'first'  => __( 'First', 'sheetstowptable' ),
			'previous'  => __( 'Previous', 'sheetstowptable' ),
			'next'  => __( 'Next', 'sheetstowptable' ),
			'last'  => __( 'last', 'sheetstowptable' ),
			'data-empty-notice'  => __( 'No matching records found', 'sheetstowptable' ),

			/**
			 * TableItem
			 */
			'copy-shortcode'  => __( 'Copy Shortcode', 'sheetstowptable' ),
			'are-you-sure-to-delete'  => __( 'Are you sure to delete the table? ', 'sheetstowptable' ),
			'confirmation-about-to-delete'  => __( 'You are about to delete the table. This will permanently delete the table(s)', 'sheetstowptable' ),

			/**
			 * Merge
			 */
			'merge-alert'  => __( 'Vertical merge found!', 'sheetstowptable' ),
			'merge-info'  => __( "Please make sure to keep the sorting feature disabled. Google sheet doesn't filter over a range containing vertical merges.", 'sheetstowptable' ),
			'merge-confirm'  => __( 'OK', 'sheetstowptable' ),
			'active-merge-condition-alert'  => __( 'Your sheet contains vertically merged cells, so you can not use the sorting feature. Sorting may break the table design and format. Please ensure that you have enabled either the vertical merge cell feature or the sorting feature, or confirm that your sheet has no vertical merges. If your table design still breaks even with sorting disabled, consider reloading the sheet or clicking save again to reflect the necessary fixes. To get the sorting feature visit Display settings > Disable sorting', 'sheetstowptable' ),

			/**
			 * TabSettings
			 */
			'After-the-table'  => __( 'After the table', 'sheetstowptable' ),
			'Before-the-table'  => __( 'Before the table', 'sheetstowptable' ),
			'Tab-position'  => __( 'Tab position', 'sheetstowptable' ),
			'hide-grp-title'  => __( 'Hide tab group title', 'sheetstowptable' ),

			/**
			 * TabsList
			 */
			'do-not-have-table'  => __( 'Manage tab is not available because you don’t have any tables yet. Please create tables first', 'sheetstowptable' ),
			'create-table-to-manage'  => __( 'Create tables first to manage tabs', 'sheetstowptable' ),
			'tab-groups-will-appear-here'  => __( 'Tab groups will appear here once you create them', 'sheetstowptable' ),
			'no-tab-grp-created'  => __( 'No tab groups have been created yet', 'sheetstowptable' ),

		];
	}
}
