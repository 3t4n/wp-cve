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
<div class="x_title">
	<h2>
		<?php wpToolsSAFHelperClass::getCheckIcon(1);  ?>
		<?php echo $title; ?>
		&nbsp;
		<button class="btn btn-default btn-xs" type="button"
		        data-action="action=wptsaf_security&extension=<?php echo 'sys'; ?>tem-log&method=settings">
		<?php echo __('Settings', 'wptsaf_security'); ?>
		</button>
	</h2>
	<div class="clearfix"></div>
</div>

<div class="x_content">
	<p>
		<?php echo $description; ?>
	</p>

	<table class="table table-hover">
		<thead>
		<tr>
			<?php foreach ($rowHeader as $title) : ?>
				<th>
					<?php echo $title; ?>
				</th>
			<?php endforeach; ?>
		</tr>
		</thead>
		<tbody>
			<?php $columns = array_keys($rowHeader); ?>
			<?php foreach ($rows as $row) : ?>
				<tr data-action="action=wptsaf_security&extension=<?php echo 'sys'; ?>tem-log&method=logRow&args[id]=<?php echo $row['id']; ?>">
					<?php foreach ($columns as $column) : ?>
						<td><?php echo $row[$column]; ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<p>
		<button class="btn btn-info col-md-6 col-sm-6 col-md-offset-3" type="button"
		        data-action="action=wptsaf_security&extension=<?php echo 'sys'; ?>tem-log&method=log">
			<?php echo __('Detailed log', 'wptsaf_security'); ?>
		</button>
	</p>
</div>
