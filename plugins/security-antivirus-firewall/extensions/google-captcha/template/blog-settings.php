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

$id = 'detail-log-' . mt_rand();
?>
<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2><?php echo $extensionTitle . ': ' . __('Blogs', 'wptsaf_security'); ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<?php if ($limitMessage) : ?>
					<div class="limit-message alert alert-warning">
						<?php echo $limitMessage; ?>
					</div>
				<?php endif; ?>

				<div class="wrapper-log">
					<table id="<?php echo $id; ?>" class="log table table-striped table-bordered">
						<thead>
						<tr>
							<th><?php _e('Blog', 'wptsaf_security'); ?></th>
							<th><?php _e('Actions', 'wptsaf_security'); ?></th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($rows as $row) : ?>
							<tr>
								<td><?php echo get_site_url($row['blog_id']); ?></td>
								<td>
									<button class="btn btn-success btn-xs"
									        data-action="action=wptsaf_security&extension=google-captcha&method=blogSettingsRowEdit&args[id]=<?php echo $row['id']; ?>">
										<?php _e('Settings', 'wptsaf_security'); ?>
									</button>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	(function ($) {
		$('#<?php echo $id; ?>').dataTable( {
			sDom: '<"top"<"dataTables_group_block"lp>>rt<"bottom"fip>',
			"pageLength": 50,
			"lengthMenu": [50, 100, 200],
			ordering: false,
			searching: false,
			responsive: true
		});
	})(jQuery);
</script>
