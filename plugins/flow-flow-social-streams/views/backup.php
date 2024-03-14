<?php if ( ! defined( 'WPINC' ) ) die;
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @var array $context
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
$export = '';
$backups = $context['backups'];
if ($context['boosts']){
	/** @var \flow\db\LADBManager $dbm */
	$dbm = $context['db_manager'];
	$token = $dbm->getToken();
	$export = '. <a class="ff-pseudo-link" href="https://api.flowflowapp.com/api/v1/flow-flow/export?&token=' . $token . '" id="ff-download-posts-btn">Download stored posts as CSV</a>';
}
?>
<div class="section-content" data-tab="backup-tab">
	<div class="section" id="backup-settings">
		<h1 class="desc-following">Snapshots management</h1>
		<p class="desc">Save and restore plugin data from specific point of time<?php print $export; ?></p>
		<table id="backups">
			<thead><tr><th>Snapshot Date</th><th>Version</th><th>Actions</th></tr></thead>
			<tbody>

			<?php
			if (isset($backups)) {
				$count = count($backups);
				foreach ($backups as $backup) {
					$description = trim($backup->creation_time . ' ' . $backup->description);
					$version = $backup->version;
					if ($backup->outdated){
						$action = '<span class="admin-button grey-button delete_backup">Delete snapshot</span>';
					}
					else $action = '<span class="admin-button grey-button delete_backup">Delete snapshot</span><span class="space"></span><span class="admin-button grey-button restore_backup">Restore from this point</span>';
					echo '<tr backup-id="' . $backup->id . '"><td>' . $description . '</td><td>' . $version . '</td><td>' . $action . '</td></tr>';
				}
				if ($count == 0) {
					echo '<tr><td colspan="3">Please make at least one snapshot</td></tr>';
				}
			} else {
				echo '<tr><td colspan="3">Please deactivate/activate plugin to initialize snapshot database. Required only once.</td></tr>';
			}
			?>
			</tbody>
		</table>

        <span class="ff-icon-lock"></span> <span class='admin-button green-button create_backup'>Create new database snapshot</span> <div class="desc hint-block hint-block-pro"><span class="hint-link">Upgrade to unlock</span><div class="hint hint-pro"><h1>PREMIUM FEATURE</h1>To access this and many other premium features please activate <a href="#addons-tab">BOOST subscription</a> or make one‑time purchase of <a href="http://goo.gl/g7XQzu" target="_blank">PRO version</a>.<br>Check out comparison table of all versions <a target="_blank" href="https://social-streams.com/flow#pricing">here</a>.</div></div>
	</div>
	<?php
		/** @noinspection PhpIncludeInspection */
		include($context['root']  . 'views/footer.php');
	?>

</div>
