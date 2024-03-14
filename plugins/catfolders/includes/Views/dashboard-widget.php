<?php
use CatFolders\Models\OptionModel;
use CatFolders\Models\FolderModel;

defined( 'ABSPATH' ) || exit;

$option                  = OptionModel::get_option();
$totalAttachment         = intval( FolderModel::get_all_counter() );
$assignAttachmentCounter = FolderModel::get_folders_counter();
$countFolder             = FolderModel::getFolderCount();

$totalAssignedAttachment = 0;

if ( ! is_null( $assignAttachmentCounter ) ) {
	$totalAssignedAttachment = array_reduce(
		$assignAttachmentCounter,
		function ( $total, $item ) {
			$total += intval( $item->counter );
			return $total;
		},
		0
	);
}

$percent = $totalAttachment > 0 ? round( $totalAssignedAttachment / $totalAttachment * 100 ) : 0;

$folderMode = ( '1' === $option['userrestriction'] ) ? __( 'Personal Folders', 'catfolders' ) : __( 'Common Folders', 'catfolders' );

?>

<div class="catf-overview-body">
	<table class="catf-overview-table">
		<tbody>
			<tr>
				<td><?php echo esc_html_e( 'Folder mode', 'catfolders' ); ?></td>
				<td><?php echo esc_html( $folderMode ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html_e( 'Your folders', 'catfolders' ); ?></td>
				<td><?php echo esc_html( $countFolder ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html_e( 'All files', 'catfolders' ); ?></td>
				<td><?php echo esc_html( $totalAttachment ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html_e( 'Organized', 'catfolders' ); ?></td>
				<td>
					<p class="catf-process-bar-label">
						<span><?php echo esc_html( $totalAssignedAttachment ); ?>/<?php echo esc_html( $totalAttachment ); ?></span>
						<span><?php echo esc_html( $percent ); ?>%</span>
					</p>
					<p class="catf-process-bar"><span style="width: <?php echo esc_attr( $percent ); ?>%"></span></p>
				</td>
			</tr>
		</tbody>
	</table>
	<a href="<?php echo esc_url( admin_url( '/upload.php' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Get Organized', 'catfolders' ); ?></a>
</div>
<div class="catf-overview-footer">
	<a href="https://wpmediafolders.com/docs/support/changelogs/" target="_blank" rel="noopener noreferrer" class="catf-changelog"><?php esc_html_e( 'Changelog', 'catfolders' ); ?> <span aria-hidden="true" class="dashicons dashicons-external"></span></a>
	|
	<a href="https://wpmediafolders.com/contact/" target="_blank" class="catf-support"><?php esc_html_e( 'Support', 'catfolders' ); ?> <span aria-hidden="true" class="dashicons dashicons-external"></span></a>
	|
	<a href="https://wpmediafolders.com/?utm_source=catfolders-lite&utm_medium=dashboard" target="_blank" rel="noopener noreferrer" class="catf-gopro"><?php esc_html_e( 'Go Pro', 'catfolders' ); ?> <span aria-hidden="true" class="dashicons dashicons-external"></span></a>
</div>

<style>
.catf-overview-body {
  margin: -11px -12px 15px -12px;
  padding-bottom: 15px;
  border-bottom: 1px solid #f0f0f1;
  text-align: center;
}
.catf-overview-table {
  width: 100%;
  border-spacing: 0px;
  text-align: left;
  margin-bottom: 15px;
}
.catf-overview-table tr td {
  padding: 10px 12px;
}
.catf-overview-table tr td p {
  margin: 0;
}
.catf-overview-table tr:nth-child(2n) td {
  background: #f6f6f7;
}
.catf-process-bar-label {
  display: flex;
  justify-content: space-between;
  font-size: 10px;
}
.catf-process-bar {
  width: 100%;
  height: 9px;
  background: #b8c7da;
  border-radius: 10px;
  position: relative;
  overflow: hidden;
}
.catf-process-bar span {
  height: 100%;
  position: absolute;
  width: 0;
  background: #2271b1;
  transition: 1s;
  border-radius: 10px;
  left: 0;
  top: 0;
}
.catf-overview-footer a {
  text-decoration: none;
  display: inline-flex;
  align-items: flex-end;
  gap: 2px;
}
.catf-overview-footer .catf-gopro {
  color: #52c41a;
  font-weight: bold;
}
.catf-overview-footer .dashicons {
  font-size: 17px;
  width: 17px;
  height: 17px;
}
</style>
