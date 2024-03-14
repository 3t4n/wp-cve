<?php

/**
 * Support view for the plugin
 *
 * @link       http://www.webfactoryltd.com
 * @since      1.0
 */

if (!defined('WPINC')) {
	die;
}

?>

<div class="signals-tile" id="support">
	<div class="signals-tile-body">
		<div class="signals-tile-title"><?php esc_attr_e( 'SUPPORT', 'minimal-coming-soon-maintenance-mode' ); ?></div>
		<p>Report issues to our support system and we will get back to you ASAP. If our support did a great job please <a target="_blank" href="https://wordpress.org/support/plugin/minimal-coming-soon-maintenance-mode/reviews/#new-post" title="Let others know how you like the plugin">rate the plugin ★★★★★</a>. Thank you!</p>

		<div class="signals-section-content signals-support-form">

<div id="support-hero">
<p>Our average response time is 1.5h! <b>85% of tickets are resolved within 1 hour!</b> No ticket is left unanswered.
If something is not working, don't think twice;</p><p><a class="button" href="https://wordpress.org/support/plugin/minimal-coming-soon-maintenance-mode/#new-post" target="_blank">OPEN A SUPPORT TICKET NOW</a></p></div>


      <div class="signals-form-group" style="border-bottom: none; padding-bottom: 0;">
        <label for="" class="signals-strong">Site Information</label>
<?php
    $theme = wp_get_theme();
    echo '<p>WordPress version: <code>' . esc_attr(get_bloginfo('version')) . '</code><br>';
    echo 'MM Version: <code>' . esc_attr(csmm_get_plugin_version()) . '</code><br>';
    echo 'PHP Version: <code>' . esc_attr(PHP_VERSION) . '</code><br>';
    echo 'Site URL: <code>' . esc_url(get_bloginfo('url')) . '</code><br>';
    echo 'WordPress URL: <code>' . esc_url(get_bloginfo('wpurl')) . '</code><br>';
    echo 'Theme: <code>' . esc_html($theme->get('Name')) . ' v' . esc_attr($theme->get('Version')) . '</code><br>';
    echo '</p>';
?>
    <p class="signals-form-help-block">Please include the info above when opening a support ticket. Our support agents need it to provide faster &amp; better help.</p>
      </div>

		</div>
	</div>
</div><!-- #support -->
