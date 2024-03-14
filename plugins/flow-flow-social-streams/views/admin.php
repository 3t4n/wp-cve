<?php if ( ! defined( 'WPINC' ) ) die;
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */

/**
 * @var array $context
 * @var \flow\db\LADBManager $dbm
 */
$dbm = $context['db_manager'];
$plugin_directory = $this->context['plugin_url'] . $this->context['plugin_dir_name'];
if (!$dbm->canCreateCssFolder()){
	echo '<p class="ff-error" xmlns="http://www.w3.org/1999/html">Error: Plugin cannot create folder <strong>wp-content/resources/flow-flow/css</strong>, please add permissions or create this folder manually.</p>';
}

// testing error logging
// error_log( 'SOME ERROR' . PHP_EOL , 3, FF_LOG_FILE_DEST );

?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=345260089015373";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<div id="fade-overlay" class="loading">
	<div id="waiting-posts">
		<h1>Pulling the posts for your feed</h1>
		<p>Please wait until the fetching process is complete. Usually it's quick but it can take up to 2 min. If it takes longer or fails we recommend to ask for <a href="https://social-streams.com/contact/" target="_blank">our support</a> </p>
	</div>
    <div class="anim-loader-wrapper"><div class="anim-loader"></div></div>

</div>

<div id="ff-popup-banner" class=""><div>Getting data for feed 'eagle' in background...<i class="flaticon-pinterest"></i></div><span class="banner-close">Close</span></div>
<!-- @TODO: Provide markup for your options page here. -->
<form id="flow_flow_form" method="post" class="<?php echo $context['boosts'] ? 'ff-b' : '' ; ?>" action="<?php echo $context['form-action']; ?>" enctype="multipart/form-data">
	<script id="flow_flow_script">
        var _ajaxurl = '<?php echo $context['admin_url']; ?>';
        var plugin_url = '<?php echo $plugin_directory; ?>';
        var server_time = '<?php echo time() ; ?>';
        var plugin_ver = '<?php echo $context['version'] ; ?>';
        var boostsActivated = '<?php echo $context['boosts'] ; ?>';
        var boosts_server_url = '<?php echo FF_BOOST_SERVER ; ?>';
        <?php if (isset($context['js-vars'])) echo $context['js-vars'];?>
	</script>
	<?php
		settings_fields('ff_opts');
		if (isset($context['hidden-inputs'])) echo $context['hidden-inputs'];
	?>
	<div class="wrapper">
		<?php
			if (FF_USE_WP) {
                echo '<h2>' . $context['admin_page_title'] . ($context['slug'] == 'flow-flow' ? ' v. ' : ' Feed Gallery v. ' ) . $context['version'] . ' <a href="' . $context['faq_url'] . '" target="_blank">Docs & FAQ</a></h2>';

                echo '<div id="ff-cats">';
                if (FF_USE_WP) {
                    wp_dropdown_categories();
                }
                echo '</div>';
            }
		?>
		<ul class="section-tabs">
			<?php
				/** @var la\core\tabs\LATab $tab */
				foreach ( $context['tabs'] as $tab ) {
					echo '<li id="'.$tab->id().'"><span class="ff-border-anim"></span><i class="'.$tab->flaticon().'"></i> <span>'.$tab->title().'</span></li>';
				}
				if (isset($context['buttons-after-tabs'])) echo $context['buttons-after-tabs'];
			?>
		</ul>
		<div class="section-contents">
			<?php
				/** @var la\core\tabs\LATab $tab*/
				foreach ( $context['tabs'] as $tab ) {
					$tab->includeOnce($context);
				}
			?>
		</div>
	</div>

	<?php if (!FF_USE_WP):?>
		<div id="ff-footer">
			<div class="width-wrapper">
				<div class="ff-table">
					<div class="ff-cell">
						Flow-Flow Social Hub plugin<br>
						<?php if (defined( 'FF_PLUGIN_VER' )) echo 'Version: ' . FF_PLUGIN_VER; ?><br>
						Made by <a href="http://looks-awesome.com/">Looks Awesome</a>
					</div>
					<div class="ff-cell">
						<h1>HOT TOPICS</h1>
						<a href="http://flow-php.looks-awesome.com/docs/Getting_Started/First_Steps_After_Installation">How to add stream on page</a><br>
						<a href="http://flow-php.looks-awesome.com/docs/Getting_Started/First_Steps_After_Installation#refresh">How to refresh my streams</a><br>
						<a href="http://flow-php.looks-awesome.com/docs/Social_Networks_Auth/Authenticate_with_Facebook">How to authorize Facebook</a><br>
						<a href="">Frequently asked questions</a>
					</div>
					<div class="ff-cell">
						<h1>CONTACT US</h1>
						<a href="https://social-streams.com/contact/">Support request</a><br>
						<a href="http://looks-awesome.com/">Looks Awesome site</a><br>
						<a href="https://twitter.com/looks_awesooome">Twitter</a><br>
						<a href="https://www.facebook.com/looksawesooome">Facebook</a>
					</div>
				</div>
			</div>
		</div>
	<?php endif?>
</form>
<div class="cd-popup" role="alert">
    <div class="cd-popup-container">
        <p>Are you sure you want to delete this element?</p>
        <ul class="cd-buttons">
            <li><a href="#0" id="cd-button-no">No</a></li>
            <li><a href="#0" id="cd-button-yes">Yes</a></li>
        </ul>
        <a href="#0" class="cd-popup-close img-replace">Close</a>
    </div> <!-- cd-popup-container -->
</div> <!-- cd-popup -->

<script>jQuery(document).trigger('html_ready')</script>

<div id="debug-info">
<?php

if ( defined( 'DB_CHARSET' ) ) echo 'Database charset = ' . DB_CHARSET . '<br>';
echo 'Date, time, timezone = '. date_default_timezone_get() . ' ' . date('d.m.Y H:i:s', time()) . '<br>';
if ( defined( 'PHP_VERSION' ) ) echo 'PHP version = ' . PHP_VERSION . '<br>';
echo 'upload_max_filesize = '.ini_get('upload_max_filesize') . '<br>';
echo 'arg_separator.output = '.ini_get('arg_separator.output') . '<br>';
if ( defined( 'OPENSSL_VERSION_TEXT' ) ) echo 'OpenSSL version  = '.OPENSSL_VERSION_TEXT . '<br>';
if(extension_loaded('curl')){
    $curlv = curl_version();
    echo 'CURL version = ' . $curlv["version"] . '<br>';
}
else {
    echo 'CURL is not loaded<br>';
}
if(extension_loaded('gd')){
    echo 'GD is loaded<br>';
}
else {
    echo 'GD is not loaded<br>';
}
if (extension_loaded('mbstring')){
    echo 'mbstring is loaded<br>';
} else {
    echo 'mbstring is not loaded<br>';
}
echo 'safe_mode = ' . ini_get('safe_mode') . '<br>';
echo 'allow_url_fopen = ' . ini_get('allow_url_fopen') . '<br>';
echo 'curl.cainfo = ' . ini_get('curl.cainfo') . '<br>';
echo 'open_basedir = ' . ini_get('open_basedir');
?>
</div>
