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
?>
<table class="log table table-striped table-bordered">
	<thead>
		<tr>
			<?php foreach ($header as $title) : ?>
				<th>
					<?php echo __($title, 'wptsaf_security'); ?>
				</th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<?php $columns = array_keys($header); ?>
		<?php foreach ($rows as $row) : ?>
			<tr>
				<?php foreach ($columns as $column) : ?>
					<td class="pre"><?php echo $row[$column]; ?></td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
