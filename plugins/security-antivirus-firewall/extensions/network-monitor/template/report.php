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

echo $extensionTitle; ?>

==================================================
<?php _e('Attacks Detected', 'wptsaf_security'); ?>: <?php echo $logRowsAmount; ?>

<?php _e('Current Attacks Detected', 'wptsaf_security'); ?>: <?php echo $logRowsAmountForPeriod; ?>
<?php if ($logRows) : ?>

<?php _e('ip address', 'wptsaf_security'); ?>

--------------------------------------------------
<?php foreach ($logRows as $logRow) : ?>
<?php echo $logRow['ip']; ?>

<?php endforeach; ?>
<?php if (10 < $logRowsAmountForPeriod) : ?>
...
<?php endif; ?>
<?php endif; ?>


<?php _e('IP Ban/Deny Manager', 'wptsaf_security'); ?>:
<?php _e('Total Banned', 'wptsaf_security'); ?>: <?php echo $managerRowsAmount; ?>

<?php _e('Current Banned', 'wptsaf_security'); ?>: <?php echo $managerRowsAmountForPeriod; ?>
<?php if ($managerRows) : ?>

<?php _e('ip address', 'wptsaf_security'); ?>

--------------------------------------------------
<?php foreach ($managerRows as $managerRow) : ?>
<?php echo $managerRow['ip']; ?>

<?php endforeach; ?>
<?php if (10 < $managerRowsAmountForPeriod) : ?>
	...
<?php endif; ?>
<?php endif; ?>
