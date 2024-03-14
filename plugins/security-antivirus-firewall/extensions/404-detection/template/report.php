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
<?php _e('Total 404 Error Requests', 'wptsaf_security'); ?>: <?php echo $rowsAmount; ?>

<?php _e('Current 404 Error Requests', 'wptsaf_security'); ?>: <?php echo $rowsAmountForPeriod; ?>
<?php if ($rows) : ?>


<?php _e('Incorrect Uri', 'wptsaf_security'); ?>

--------------------------------------------------
<?php foreach ($rows as $row) : ?>
<?php echo $row['uri']; ?>

<?php endforeach; ?>
<?php if (10 < $rowsAmountForPeriod) : ?>
...
<?php endif; ?>
<?php endif; ?>