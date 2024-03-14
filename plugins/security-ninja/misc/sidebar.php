<?php

namespace WPSecurityNinja\Plugin;

// this is an include only WP file
if ( !defined( 'ABSPATH' ) ) {
    die;
}
global  $secnin_fs ;
?>


<div class="secnin_content_cell" id="sidebar-container">
<?php 

if ( !$secnin_fs->is_registered() && !$secnin_fs->is_pending_activation() && !is_multisite() ) {
    ?>
	<div class="sidebarsection feature">
	<h3><span class="dashicons dashicons-warning"></span> <?php 
    esc_html_e( 'Never miss an important update', 'security-ninja' );
    ?></h3>
	<p>
	<?php 
    esc_html_e( 'Opt-in to our security and feature updates notifications, and non-sensitive diagnostic tracking.', 'security-ninja' );
    ?>
	</p>
	<p><a href="#" class="secninfs-reset-activation button button-primary button-hero"><?php 
    esc_html_e( 'Click here to opt in.', 'security-ninja' );
    ?></a></p>

	<p><small><a href="<?php 
    echo  esc_url( wf_sn::generate_sn_web_link( 'sidebar_link', '/docs/installation-and-usage/share-data-privacy/' ) ) ;
    ?>" target="_blank" rel="noopener"><?php 
    _e( 'What data is tracked?', 'security-ninja' );
    ?></a></small></p>
	</div>
	<?php 
}

?>
<div class="sidebarsection feature">
<h3><span class="dashicons dashicons-info"></span> Plugin help</h3>
<ul class="linklist">
<?php 
global  $secnin_fs ;
?>

<li><a href="https://wordpress.org/support/plugin/security-ninja/" target="_blank" rel="noopener">Support Forum</a></li>
</ul>
</div>

<?php 

if ( function_exists( 'secnin_fs' ) ) {
    $display_promotion = true;
    
    if ( $display_promotion ) {
        ?>
		<div class="snupgradebox sidebarsection feature">
		<h3><span class="dashicons dashicons-star-filled"></span> Security Ninja Pro <span class="dashicons dashicons-star-filled"></span></h3>
		<ul class="checkmarks">
		<li><strong>Install Wizard</strong> - get protected in minutes.</li>
		<li><strong>Firewall Protection</strong> - Protect your website from suspicious visitors.</li>
		<li><strong>Spam Protection</strong> - The firewall blocks known spammers.</li>
		<li><strong>Login Protection</strong> - Stop repeated failed logins.</li>
		<li><strong>Country Blocking</strong> - Block entire countries.</li>
		<li><strong>Core Scanner</strong> - Detect infected WordPress core files.</li>
		<li><strong>Plugin Validation</strong> - Check plugins have not been modified with malware.</li>
		<li><strong>Malware Scanner</strong> - Find and remove suspicious files.</li>
		<li><strong>Auto Fixer</strong> - Fix many security issues with a few clicks.</li>
		<li><strong>Events Logger</strong> - Audit log - Know who did what on your website</li>
		<li><strong>Premium Support</strong> - From the people who developed the plugin</li>
		<li><strong>Support the developers :-)</strong></li>
		</ul>
		<p><strong>Try the Pro version free for 30 days!</strong></p>
		<a href="<?php 
        echo  esc_url( wf_sn::generate_sn_web_link( 'sidebar_link', '/pricing/' ) ) ;
        ?>" class="button button-primary trial-button" target="_blank" rel="noopener"><?php 
        echo  'Get started' ;
        ?></a>
		<div class="wrap-collabsible">
		<input id="collapsible-payment-details" class="toggle" type="checkbox">
		<label for="collapsible-payment-details" class="lbl-toggle">Click to see details</label>
		<div class="collapsible-content">
		<div class="content-inner">
		
		<ul class="salenotices">
		<li>We ask for your payment information to reduce fraud and provide a seamless subscription experience.</li>
		<li>CANCEL ANYTIME before the trial ends to avoid being charged.</li>
		<li>We will send you an email reminder BEFORE your trial ends.</li>
		<li>We accept Visa, Mastercard, American Express and PayPal.</li>
		<li>Upgrade, downgrade or cancel any time.</li>
		<li>Bulk discounts for more websites.</li>
		</ul>
		<p><a href="<?php 
        echo  esc_url( wf_sn::generate_sn_web_link( 'sidebar_link', '/pricing/' ) ) ;
        ?>" target="_blank" class="button button-primary" rel="noopener">Read more about the Pro version</a></p>

		</div>
		</div>
		</div>

		</div><!-- .snupgradebox -->
		<?php 
    }

}

?>
	<div class="sidebarsection feature">
		<h3><span class="dashicons dashicons-welcome-learn-more"></span> Learn more</h3>
		<ul class="linklist">
			<li><a href="<?php 
echo  esc_url( wf_sn::generate_sn_web_link( 'sidebar_link', '/security-tests/' ) ) ;
?>" target="_blank" rel="noopener">About the tests</a></li>
			<li><a href="<?php 
echo  esc_url( wf_sn::generate_sn_web_link( 'sidebar_link', '/why-is-insignificant-small-site-attacked-by-hackers/' ) ) ;
?>" target="_blank" rel="noopener">Even small sites are attacked by hackers</a></li>
			<li><a href="<?php 
echo  esc_url( wf_sn::generate_sn_web_link( 'sidebar_link', '/wordpress-beginner-mistakes/' ) ) ;
?>" target="_blank" rel="noopener">New to WordPress? avoid these beginner mistakes</a></li>
			<li><a href="<?php 
echo  esc_url( wf_sn::generate_sn_web_link( 'sidebar_link', '/your-guide-to-wordpress-password-and-username-security/' ) ) ;
?>" target="_blank" rel="noopener">Guide to Password and Username Security</a></li>
			<li><a href="<?php 
echo  esc_url( wf_sn::generate_sn_web_link( 'sidebar_link', '/signs-wordpress-site-is-hacked/' ) ) ;
?>" target="_blank" rel="noopener">Signs that your site has been hacked</a></li>

			<li><a href="#" class="secninfs-reset-activation"><?php 
esc_html_e( 'Reset Account', 'security-ninja' );
?></a></li>

			<?php 
?>
		</ul>
	</div><!-- .sidebarsection -->
	<div>
		<input type="hidden" id="wfsn-secninfs-reset-activation-nonce" value="<?php 
echo  esc_attr( wp_create_nonce( 'wf_sn_reset_activation' ) ) ;
?>">
	</div>
</div><!-- #sidebar-container -->
<?php 