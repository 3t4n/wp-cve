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

?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel saf_blue_panel">
			<div class="x_title">
				<h2><?php _e('Get Benefits of Premium Security', 'wptsaf_security'); ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<p class="h5_small"><?php _e('Even more safe with premium security tools from S.A.F. ', 'wptsaf_security'); ?></p>
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
						<h4> <i class="fa fa-check-circle"></i> <?php _e('Advanced Firewall functions', 'wptsaf_security'); ?></h4>
						<h4> <i class="fa fa-check-circle"></i> <?php _e('More advanced Antivirus issues details', 'wptsaf_security'); ?></h4>
						<h4> <i class="fa fa-check-circle"></i> <?php _e('Advanced IP\'s manager', 'wptsaf_security'); ?></h4>
						<h4> <i class="fa fa-check-circle"></i> <?php _e('Flexible ban events management', 'wptsaf_security'); ?></h4>
						<h4> <i class="fa fa-check-circle"></i> <?php _e('Advanced bruteforce behaviour manager', 'wptsaf_security'); ?></h4>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<h4> <i class="fa fa-check-circle"></i> <?php _e('Attackers fast IP\'s ban function', 'wptsaf_security'); ?></h4>
						<h4> <i class="fa fa-check-circle"></i> <?php _e('Advanced admin notification functionality', 'wptsaf_security'); ?></h4>
						<h4> <i class="fa fa-check-circle"></i> <?php _e('More flexibility security reports', 'wptsaf_security'); ?></h4>
						<h4> <i class="fa fa-check-circle"></i> <?php _e('Permanent IP\'s ban function', 'wptsaf_security'); ?></h4>
						<h4> <i class="fa fa-check-circle"></i> <?php _e('and much more...', 'wptsaf_security'); ?></h4>
					</div>
				</div>
				<br />
				<p class="text-center">
						<a href="http://wptools.co/links/saf-pro" target="_blank" class="btn btn-default btn-lg">&nbsp;&nbsp;&nbsp;&nbsp; 
							<?php _e('GET PREMIUM FUNCTIONS', 'wptsaf_security'); ?> 
						&nbsp;&nbsp;&nbsp;&nbsp;</a>
				<?php if(WPTSAF_ACCESS_OFFER) { ?>
						<a href="http://wptools.co/links/saf-premiumfree" target="_blank" class="btn btn-success ">
							<?php _e('Get Premium version for FREE', 'wptsaf_security'); ?>
						</a>
				<?php } ?>
				</p>
			</div>
		</div>
	</div>
</div>
