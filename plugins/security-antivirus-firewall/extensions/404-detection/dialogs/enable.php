<?php 
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

?><div class="row detection-404-enable-dialog">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2><?php echo $extensionTitle; ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="alert alert-info alert-dismissible fade in" >
					<h2>
						<?php echo $extensionTitle . ' ' . __('is enabled', 'security-antivirus-firewall'); ?>
					</h2>
				</div>
				<br />
				<div>
					<?php _e('Please make sure that .htaccess contains this settings:', 'security-antivirus-firewall'); ?>
				</div>
				<br/>
<?php if (is_multisite()) : ?>
<textarea rows="17">
	# BEGIN SAF: 404 Detection
	&lt;IfModule mod_rewrite.c&gt;
	    RewriteEngine On
	    RewriteBase /

	    RewriteCond %{REQUEST_FILENAME} -d
	    RewriteCond %{REQUEST_FILENAME}/index\.php !-f
	    RewriteCond %{REQUEST_FILENAME}/index\.html !-f
	    RewriteRule . index.php [L]

	    RewriteCond %{REQUEST_FILENAME} !-d
	    RewriteCond %{REQUEST_FILENAME} !-f
	    RewriteRule . index.php [L]
	&lt;/IfModule&gt;
	# END SAF: 404 Detection
</textarea>
<?php else : ?>
<textarea rows="12">
	# BEGIN SAF: 404 Detection
	&lt;IfModule mod_rewrite.c&gt;
		RewriteEngine On
		RewriteBase /
		RewriteCond %{REQUEST_FILENAME} -d
		RewriteCond %{REQUEST_FILENAME}/index\.php !-f
		RewriteCond %{REQUEST_FILENAME}/index\.html !-f
		RewriteRule . index.php [L]
	&lt;/IfModule&gt;
	# END SAF: 404 Detection
</textarea>
<?php endif; ?>
				<div class="ln_solid"></div>
				<div class="buttons">
					<button class="btn btn-default pull-right btn-popup-close">
						<?php _e('Close', 'security-antivirus-firewall'); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
