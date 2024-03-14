<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
function foxtool_backup_options_page() {
	$foxtool_settings = get_option('foxtool_settings');
	ob_start(); 
	?>
	<div class="wrap ft-wrap">
	  <div class="ft-box">
		<div class="ft-menu">
			<div class="ft-logo"><?php foxtool_logo(); ?></div>
			<button class="sotab sotab-select" onclick="fttab(event, 'tab1')"><i class="fa-regular fa-gear"></i> <?php _e('FOXTOOL', 'foxtool'); ?></button>
			<button class="sotab" onclick="fttab(event, 'tab2')"><i class="fa-regular fa-gear"></i> <?php _e('CODE', 'foxtool'); ?></button>
		</div>
		<div class="ft-main">
			<?php
			// foxtool tool
			if (isset($_POST['foxtool_import_tool']) && !empty($_POST['foxtool_backup_tool'])) {
				$imported_config = json_decode(base64_decode(stripslashes($_POST['foxtool_backup_tool'])), true);
				if ($imported_config && is_array($imported_config)) {
					if (get_option('foxtool_settings') === false) {
						add_option('foxtool_settings', $imported_config);
					} else {
						update_option('foxtool_settings', $imported_config);
					}
					echo '<div class="ft-updated">' . __('New configuration has been successfully added', 'foxtool') . '</div>';
				} else {
					echo '<div class="ft-updated">' . __('Invalid data', 'foxtool') . '</div>';
				}
			}
			// foxtool code
			if (isset($_POST['foxtool_import_code']) && !empty($_POST['foxtool_backup_code'])) {
				$imported_config = json_decode(base64_decode(stripslashes($_POST['foxtool_backup_code'])), true);
				if ($imported_config && is_array($imported_config)) {
					if (get_option('foxtool_code_settings') === false) {
						add_option('foxtool_code_settings', $imported_config);
					} else {
						update_option('foxtool_code_settings', $imported_config);
					}
					echo '<div class="ft-updated">' . __('New configuration has been successfully added', 'foxtool') . '</div>';
				} else {
					echo '<div class="ft-updated">' . __('Invalid data', 'foxtool') . '</div>';
				}
			}
            ?>
			
			<!-- Xuất nhập tool -->
			<div class="sotab-box ftbox" id="tab1">
			<form method="post" action="<?php echo menu_page_url('foxtool-backup-options', false); ?>">
			<h2><?php _e('EXPORT & IMPORT DATA FOXTOOL', 'foxtool'); ?></h2>
			<div class="ft-card">
			  <h3><i class="fa-regular fa-download"></i> <?php _e('Foxtool configuration data export box', 'foxtool') ?></h3>
				<p>
				<textarea style="height:250px" class="ft-code-textarea"><?php echo esc_textarea(base64_encode(json_encode(get_option('foxtool_settings')))); ?></textarea>
				</p>
			  <h3><i class="fa-regular fa-upload"></i> <?php _e('Foxtool configuration data import box', 'foxtool') ?></h3>
				<p>
				<textarea style="height:250px" class="ft-code-textarea" name="foxtool_backup_tool" placeholder="<?php _e('Enter data here', 'foxtool'); ?>"></textarea>
				</p>
			</div>
			<div class="ft-submit">
				<button type="submit" name="foxtool_import_tool"><i class="fa-regular fa-file-import"></i> <?php _e('IMPORT FOXTOOL DATA', 'foxtool'); ?></button>
			</div>
			</form>
			</div>
			<!-- Xuất nhập code -->
			<div class="sotab-box ftbox" id="tab2" style="display:none">
			<form method="post" action="<?php echo menu_page_url('foxtool-backup-options', false); ?>">
			<h2><?php _e('EXPORT & IMPORT CODE DATA', 'foxtool'); ?></h2>
			<div class="ft-card">
			  <h3><i class="fa-regular fa-download"></i> <?php _e('Code configuration data export box', 'foxtool') ?></h3>
				<p>
				<textarea style="height:250px" class="ft-code-textarea"><?php echo esc_textarea(base64_encode(json_encode(get_option('foxtool_code_settings')))); ?></textarea>
				</p>
			  <h3><i class="fa-regular fa-upload"></i> <?php _e('Code configuration data import box', 'foxtool') ?></h3>
				<p>
				<textarea style="height:250px" class="ft-code-textarea" name="foxtool_backup_code" placeholder="<?php _e('Enter data here', 'foxtool'); ?>"></textarea>
				</p>
			</div>
			<div class="ft-submit">
				<button type="submit" name="foxtool_import_code"><i class="fa-regular fa-file-import"></i> <?php _e('IMPORT CODE DATA', 'foxtool'); ?></button>
			</div>
			</form>
			</div>
	
		</div>
	  </div>
	  <div class="ft-sidebar">
		<?php include( FOXTOOL_DIR . 'main/page/ft-aff.php'); ?>
	  </div>
	</div>
	<?php
	// style foxtool
	require_once( FOXTOOL_DIR . 'main/style.php');
	echo ob_get_clean();
}
function foxtool_backup_options_link() {
	add_submenu_page ('foxtool-options', 'Backup', 'Backup', 'manage_options', 'foxtool-backup-options', 'foxtool_backup_options_page');
}
add_action('admin_menu', 'foxtool_backup_options_link');


