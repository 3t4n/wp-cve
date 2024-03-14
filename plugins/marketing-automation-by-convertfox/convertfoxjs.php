<!-- start Gist JS code -->
<script>
    (function(d,h,w){var gist=w.gist=w.gist||[];gist.methods=['trackPageView','identify','track','setAppId'];gist.factory=function(t){return function(){var e=Array.prototype.slice.call(arguments);e.unshift(t);gist.push(e);return gist;}};for(var i=0;i<gist.methods.length;i++){var c=gist.methods[i];gist[c]=gist.factory(c)}s=d.createElement('script'),s.src="https://widget.getgist.com",s.async=!0,e=d.getElementsByTagName(h)[0],e.appendChild(s),s.addEventListener('load',function(e){},!1),gist.setAppId("<?php echo $settings['project_id']; ?>"),gist.trackPageView()})(document,'head',window);
</script>
<!-- end Gist JS code -->

<?php
	// Check Messenger Visibility
	$hide_on_front_page = is_front_page() && isset($settings['messenger_visibility_front_page']) && $settings['messenger_visibility_front_page'];
	$hide_on_pages = is_page() && isset($settings['messenger_visibility_pages']) && $settings['messenger_visibility_pages'];
	$hide_on_blog_home = is_home() && isset($settings['messenger_visibility_blog_home']) && $settings['messenger_visibility_blog_home'];
	$hide_on_posts = is_single() && isset($settings['messenger_visibility_posts']) && $settings['messenger_visibility_posts'];
	$hide_on_archives = is_archive() && isset($settings['messenger_visibility_archives']) && $settings['messenger_visibility_archives'];

	$current_user = wp_get_current_user();
	$sanitized_email = sanitize_email($current_user->user_email);

	if ( 0 != $current_user->ID && $settings['identify_users'] ) {
?>
<!-- This code is for identifying WordPress users in Gist -->
<script>
var gist_props = {
    'email': <?php echo "\"" . $sanitized_email . "\""; ?>,
    'name': <?php echo "\"" . $current_user->user_firstname . " " . $current_user->user_lastname . "\""; ?>,
    'username': <?php echo "\"" . sanitize_text_field($current_user->user_login) . "\""; ?>,
    'role': <?php echo "\"" . sanitize_text_field($current_user->roles[0]) . "\""; ?>
};

<?php
if ( $settings['identity_verify_users'] ) {
    $gist_user_hash = hash_hmac('sha256',
        $current_user->ID,
        $settings['identity_secret_key']
    );
?>
gist_props.user_hash = "<?php echo $gist_user_hash; ?>";
<?php } ?>

// pass user info to the gist.identify method
gist.identify(<?php echo $current_user->ID; ?>, gist_props);
</script>
<?php } ?>

<?php if( $hide_on_front_page || $hide_on_pages || $hide_on_blog_home || $hide_on_posts || $hide_on_archives ) { ?>
<style>
	/* Hide Messenger bubble using CSS */
	#gist-app {
	    display: none!important;
	}
</style>

<script type="text/javascript">
	document.addEventListener('gistReady', function () {
	    gist.chat('hide');
	});
</script>
<?php } ?>