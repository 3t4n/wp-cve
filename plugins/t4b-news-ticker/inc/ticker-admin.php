<?php
/*
 *  T4B News Ticker v1.2.9 - 23 November, 2023
 *  By @realwebcare - https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Sidebar */
add_action( 't4bnt_settings_content', 't4bnt_sidebar' );
if( !function_exists( 't4bnt_sidebar' ) ){
	function t4bnt_sidebar() { ?>
		<div id="t4bnt-sidebar" class="postbox-container">
			<div id="t4bntusage-features" class="t4bntusage-sidebar">
				<div class="t4bntusage-feature-header">
					<img src="<?php echo plugins_url('../assets/images/template-pro.png', __FILE__) ?>" alt="Rover">
				</div>
				<div class="t4bntusage-feature-body">
					<h3><?php esc_html_e('Premium Features', 't4b-news-ticker'); ?></h3>
					<div class="t4bnt"><?php esc_html_e('Premium version has been developed to present News Ticker more proficiently. Some of the most notable features are:', 't4b-news-ticker'); ?></div>
					<ul class="t4bntusage-list">
						<li><?php esc_html_e('Customization Made Effortless.', 't4b-news-ticker'); ?></li>
						<li><?php esc_html_e('7 impressive animation effects.', 't4b-news-ticker'); ?></li>
						<li><?php esc_html_e('Instant Creation with 12 Pre-made Designs.', 't4b-news-ticker'); ?></li>
						<li><?php esc_html_e('Continuous Scroll Without Interruption.', 't4b-news-ticker'); ?></li>
						<li><?php esc_html_e('Import/Export (Backup) news ticker.', 't4b-news-ticker'); ?></li>
						<li><?php esc_html_e('Make a copy of a ticker instantly.', 't4b-news-ticker'); ?></li>
						<li><?php esc_html_e('Choose ticker contents from multiple categories.', 't4b-news-ticker'); ?></li>
						<li><?php esc_html_e('RSS Feed and JSON Display.', 't4b-news-ticker'); ?></li>
						<li><?php esc_html_e('Fix the ticker at the top or bottom of the page.', 't4b-news-ticker'); ?></li>
						<li><?php esc_html_e('RTL (Right to Left) Language Support.', 't4b-news-ticker'); ?></li>
						<li><?php esc_html_e('Google Fonts and Font Awesome Support.', 't4b-news-ticker'); ?></li>
					</ul>
					<a href="https://www.realwebcare.com/demo/plugins?theme=t4b-news-ticker-pro" target="_blank"><?php esc_html_e('View Demo', 't4b-news-ticker'); ?></a>
				</div>
			</div>
			<div id="t4bntusage-shortcode" class="t4bntusage-sidebar">
				<h3><?php esc_html_e('Plugin Shortcode', 't4b-news-ticker'); ?></h3>
				<p><?php esc_html_e('To display a news ticker shortcode in a WordPress post or page, you need to access the post or page editor in the WordPress dashboard. Here\'s how:', 't4b-news-ticker'); ?></p>
				<ol>
					<li><?php esc_html_e('Go to Posts or Pages, depending on where you want to display the news ticker.', 't4b-news-ticker'); ?></li>
					<li><?php esc_html_e('Either create a new post or page, or edit an existing one.', 't4b-news-ticker'); ?></li>
					<li><?php esc_html_e('Switch to the visual editor if it\'s not already active.', 't4b-news-ticker'); ?></li>
					<li><?php esc_html_e('Locate the spot in the post or page where you want to display the news ticker.', 't4b-news-ticker'); ?></li>
					<li class="t4b-scode">
						<?php esc_html_e('Paste the following shortcode into the editor:', 't4b-news-ticker'); ?>
						<input id="t4bntShortcode" type="text" class="t4bnt-shortcode" name="t4bnt_shortcode" value="<?php echo esc_html('[t4b-ticker]'); ?>" onclick="t4bntFunction()" onmouseout="t4bntoutFunc()">
						<span class="copy-tooltip" id="t4bntTooltip"><?php esc_html_e('Click to Copy Shortcode!', 't4b-news-ticker'); ?></span>
					</li>
					<li><?php esc_html_e('Save or publish the post or page.', 't4b-news-ticker'); ?></li>
				</ol>
				<p><?php esc_html_e('Once you\'ve saved or published the post or page, the news ticker shortcode will be processed and the news ticker will be displayed on the front end of your site.', 't4b-news-ticker'); ?></p>
			</div>
			<div id="t4bntusage-info" class="t4bntusage-sidebar">
				<h3><?php esc_html_e('Plugin Info', 't4b-news-ticker'); ?></h3>
				<ul class="t4bntusage-list">
					<li><?php esc_html_e('Version: 1.2.9', 't4b-news-ticker'); ?></li>
					<li><?php esc_html_e('Scripts: PHP + CSS + JS', 't4b-news-ticker'); ?></li>
					<li><?php esc_html_e('Requires: Wordpress 5.4+', 't4b-news-ticker'); ?></li>
					<li><?php esc_html_e('First release: 29 December, 2014', 't4b-news-ticker'); ?></li>
					<li><?php esc_html_e('Last Update: 23 November, 2023', 't4b-news-ticker'); ?></li>
					<li><?php esc_html_e('By', 't4b-news-ticker'); ?>: <a href="https://www.realwebcare.com/" target="_blank"><?php esc_html_e('Realwebcare', 't4b-news-ticker'); ?></a><br/>
					<li><?php esc_html_e('Need Help', 't4b-news-ticker'); ?>? <a href="https://wordpress.org/support/plugin/t4b-news-ticker/" target="_blank"><?php esc_html_e('Support', 't4b-news-ticker'); ?></a><br/>
                    <li><?php esc_html_e('Like it? Please leave us a', 't4b-news-ticker'); ?> <a target="_blank" href="https://wordpress.org/support/plugin/t4b-news-ticker/reviews/?filter=5/#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a> <?php esc_html_e('rating. We highly appreciate your support!', 't4b-news-ticker'); ?><br/>
					<li><?php esc_html_e('Published under', 't4b-news-ticker'); ?>: <a href="http://www.gnu.org/licenses/gpl.txt"><?php esc_html_e('GNU General Public License', 't4b-news-ticker'); ?></a>
				</ul>
			</div>
		</div><?php
	}
}

/**
* Get the current time and set it as an option when the plugin is activated.
* @return null
*/
function t4bnt_set_activation_time(){
    $get_activation_time = strtotime("now");
    add_option('t4bnt_activation_time', $get_activation_time );
}

/**
* Check date on admin initiation and add to admin notice if it was over 7 days ago.
* @return null
*/
function t4bnt_check_installation_date() {
    $review_nt = "";
    $review_nt = get_option('t4bnt_review_nt');
 
    if (!$review_nt) {
        $install_date = get_option( 't4bnt_activation_time', 'default_value' );
        $past_date = strtotime( '-7 days' );

        if ($install_date !== 'default_value' && $install_date < $past_date) {
            add_action( 'admin_notices', 't4bnt_display_admin_notice' );
        } else {
            $get_activation_time = strtotime("now");
            add_option('t4bnt_activation_time', $get_activation_time );
        }
    }
}
add_action( 'admin_init', 't4bnt_check_installation_date' );

/**
* Display Admin Notice, asking for a review
**/
function t4bnt_display_admin_notice() {
    // WordPress global variable 
    global $pagenow;
    if (is_admin() && $pagenow === 'options-general.php' && isset($_GET['page']) && $_GET['page'] === 't4bnt-settings') {
        $dont_disturb = esc_url(admin_url('options-general.php?page=t4bnt-settings&review_nt=1'));
        $plugin_info = get_plugin_data(T4BNT_AUF, true, true);
        $reviewurl = esc_url('https://wordpress.org/support/plugin/' . sanitize_title($plugin_info['TextDomain']) . '/reviews/');

        printf(
            __('<div id="t4bnt-review" class="notice notice-success is-dismissible"><p>It\'s been 7 days since your last update or installation. Your feedback is crucial for our improvement. Please take a moment to share your thoughts by leaving a quick review.</p><div class="t4bnt-review-btn"><a href="%s" class="button button-primary" target="_blank">Leave a Review</a><a href="%s" class="t4bnt-grid-review-done button button-secondary">Already Left a Review</a></div></div>'),
            $reviewurl,
            $dont_disturb
        );
    }
}

/**
* remove the notice for the user if review already done or if the user does not want to
**/
function t4bnt_review_nt() {    
    if( isset( $_GET['review_nt'] ) && !empty( $_GET['review_nt'] ) ) {
        $review_nt = $_GET['review_nt'];
        if( $review_nt == 1 ) {
            add_option( 't4bnt_review_nt' , TRUE );
        }
    }
}
add_action( 'admin_init', 't4bnt_review_nt', 5 );

require_once ( T4BNT_PLUGIN_PATH . 'ticker-shortcode.php' );
require_once ( T4BNT_PLUGIN_PATH . 'class/t4bnt-class.settings-api.php' );
require_once ( T4BNT_PLUGIN_PATH . 'inc/ticker-settings.php' );
?>