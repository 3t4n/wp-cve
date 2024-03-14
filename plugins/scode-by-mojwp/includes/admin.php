<?php

if (!function_exists('add_action')) {
	echo 'Hi there! I\'m just a plugin, not much I can do when called directly.';
	exit;
}

add_action('admin_enqueue_scripts',	'backend_enqueue_scripts_and_styles');
function backend_enqueue_scripts_and_styles($hook_suffix) {
	if ($hook_suffix == 'toplevel_page_scode' || $hook_suffix == 'scode_page_scode_support') {
		wp_enqueue_style('scode_font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(), false);
		wp_enqueue_script('jquery-ui-position');
		wp_enqueue_script('scode_backend_js', SCODE_PLUGIN_URL.'assets/js/jquery.scode.js', array('jquery'), false, true);
		
	}
	
	wp_enqueue_style('scode_backend_style', SCODE_PLUGIN_URL.'assets/css/admin-style.css', array(), false);
}

add_action('admin_menu', 'register_scode_page');
function register_scode_page() {
	add_menu_page(__('Shortcodes sCode', 'scode'), 'sCode', 'manage_options', 'scode', 'scode_html', 'dashicons-list-view', 110);
	add_submenu_page('scode', __('My shortcodes', 'scode'), __('My shortcodes', 'scode'), 'manage_options', 'scode', 'scode_html');
	add_submenu_page('scode', 'sCode — '.__('Instruction', 'scode'), __('Instruction', 'scode'), 'manage_options', 'scode_support', 'scode_support_html');
}

add_filter('wp_default_editor', function() { return 'html'; });

function scode_support_html() {
	?>
	<div class="wrap instruction">
		<h2><?php _e('Instruction', 'scode'); ?></h2>
		
		<?php if (get_locale() == 'ru_RU') { ?>
			<p>Вы создаете новый шорткод. После этого можете вставить его в любую часть статьи или сайта, и он выведет значение, которое вы ему задали (цена, картинка, реклама, скрипт).</p>
			<h3>Видео как пользоваться плагином</h3>
			<p><iframe width="560" height="315" src="https://www.youtube.com/embed/qjalH2gEsp8?rel=0" frameborder="0" allowfullscreen></iframe></p>
			<h3>Как создать новый шорткод?</h3>
			<p>Чтобы создать новый шорткод, перейдите на вкладку плагина «Мои шорткоды».</p>
			<p>Теперь вам нужно «Добавить новый шорткод»</p>
			<img src="<?php echo SCODE_PLUGIN_URL . '/assets/images/add-rus-1.jpg';?>" alt="Добаляем новый шорткод" />
			<p>В появившемся окне заполняем поля</p>
			<img src="<?php echo SCODE_PLUGIN_URL . '/assets/images/add-rus-2.jpg';?>" alt="Заполняем поля" />
			<p>Поздравляю! Вы создали шорткод, который уже можете использовать на сайте.</p>
			
			<div class="autor_plugin">
				<h3>Разработчики</h3>
                <div>
                    <img src="http://1.gravatar.com/avatar/75ca97921a2a840bdc60a9b66c363d9b?s=50&d=mm&r=g" alt="" />
                    <a href="https://wpshop.ru/" target="_blank">WPShop.ru</a><br/>Разработка<br/>PHP & WP Developer
                </div>
				<div>
					<img src="https://www.gravatar.com/avatar/5480336fef49a6c9a0c15beea7771941?d=mm&s=50&r=G" alt="" />
                    <a href="http://mojwp.ru" target="_blank">Виталик mojWP</a><br/>Автор<br/>SEO, HTML/CSS
				</div>
				<div>
					<img src="https://www.gravatar.com/avatar/afcaa467847bce7547124f48e0b46115?d=mm&s=50&r=G" alt="" />
                    Николай Бирулин<br/>Разработка<br/>PHP & WP Developer
				</div>
			</div>
			
		<?php } else { ?>
		
			<p>You create a new shortcode. Then you can paste it into any part of the article or website and it will show the given value (price, image, advertising, script).</p>
			<h3>How to create a new shortcode?</h3>
			<p>To create a new shortcode, click the tab of the plugin "My shortcodes".</p>
			<p>Now you need to "Add a new shortcode"</p>
			<img src="<?php echo SCODE_PLUGIN_URL . '/assets/images/add-eng-1.jpg';?>" alt="" />
			<p>You should fill in the fields in the window that appears</p>
			<img src="<?php echo SCODE_PLUGIN_URL . '/assets/images/add-eng-2.jpg';?>" alt="" />
			<p>Congratulations! You've created a shortcode that can be used on the website.</p>
			
			<div class="autor_plugin">
				<h3>Development</h3>
				<div>
					<img src="https://www.gravatar.com/avatar/5480336fef49a6c9a0c15beea7771941?d=mm&s=50&r=G" alt="Vitalik" /><a href="http://mojwp.ru" target="_blank">Vitalik mojWP</a><br/>Author<br/>SEO, HTML/CSS
				</div>
				<div>
					<img src="https://www.gravatar.com/avatar/afcaa467847bce7547124f48e0b46115?d=mm&s=50&r=G" alt="Vitalik" /><a href="http://wpskills.ru/" target="_blank">Nicolay Birulin</a><br/>Developer<br/>PHP & WP Developer
				</div>
			</div>
		<?php } ?>
	</div>
	<?php
}

function scode_html() {
	if (isset($_GET['group_filter']))
		$shortcodes = scode_get_shortcodes($_GET['group_filter']);
	else
		$shortcodes = scode_get_shortcodes();
	
	$groups = scode_get_groups();
?>
	<div class="wrap">
		<h2><?php _e('Shortcodes sCode', 'scode'); ?></h2>
		<a href="#addShortcodeModal" id="addShortcodeModalButton" class="add-new-shortcode open-modal-button"><?php _e('Add new shortcode', 'scode'); ?></a>
		<div class="tablenav top">
			<div class="alignleft actions">
				<form action="/wp-admin/admin.php?page=scode" method="get">
					<input type="hidden" name="page" value="scode">
					<label class="screen-reader-text" for="selectGroup"><?php _e('Group Filter', 'scode'); ?></label>
					<select name="group_filter" id="selectGroup">
						<option value="0"><?php _e('All groups', 'scode'); ?></option>
						<?php foreach ($groups as $group) {
							if (isset($_GET['group_filter'])) { 
								$g = $_GET['group_filter'];
								if ($g == $group['group_id']) $selected = ' selected'; else $selected = '';
							} else $selected = '';
							
							if ($group['count'] != 0) echo '<option class="level-0" value="'.$group['group_id'].'"'.$selected.'>'.$group['group_name'].'</option>';
						} ?>
					</select>
					<input type="submit" id="post-query-submit" class="button" value="<?php _e('Group Filter', 'scode'); ?>">
					
				</form>
			</div>
			<div class="tablenav-pages one-page"><span class="displaying-num"><?=scode_get_plural_form(count($shortcodes));?></span></div>	
			<br class="clear">
		</div>
		<table id="scode-shortcodes-table" class="wp-list-table widefat fixed posts">
			<thead>
				<tr>
					<th scope="col" id="title" class="manage-column"><?php _e('Shortcode', 'scode'); ?></th>
					<th scope="col" id="categories" class="manage-column"><?php _e('Description', 'scode'); ?></th>
					<th scope="col" id="tags" class="manage-column"><?php _e('Value', 'scode'); ?></th>
					<th scope="col" id="author" class="manage-column"><?php _e('Group', 'scode'); ?></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th scope="col" id="title" class="manage-column"><?php _e('Shortcode', 'scode'); ?></th>
					<th scope="col" id="categories" class="manage-column"><?php _e('Description', 'scode'); ?></th>
					<th scope="col" id="tags" class="manage-column"><?php _e('Value', 'scode'); ?></th>
					<th scope="col" id="author" class="manage-column"><?php _e('Group', 'scode'); ?></th>
				</tr>
			</tfoot>

			<tbody id="the-list scode-shortcodes-list">
				<input type="hidden" name="nonce_edit" value="<?php echo wp_create_nonce('nonce_edit'); ?>" autocomplete="off" />
				<input type="hidden" name="nonce_del" value="<?php echo wp_create_nonce('nonce_del'); ?>" autocomplete="off" />
				<input type="hidden" name="nonce_view" value="<?php echo wp_create_nonce('nonce_view'); ?>" autocomplete="off" />
				<?php if (!empty($shortcodes)) { ?>
					<?php $nr = 0; foreach ($shortcodes as $shortcode) { ?>
					<tr id="post-<?=$shortcode['shortcode_id'];?>"<?=($nr % 2 == 0) ? ' class="alternate"' : '';?>>
						<td class="post-title page-title column-title">
							<div class="row-title">[<?=$shortcode['code'];?>]</div>
							<div class="row-actions">
								<span class="edit"><a href="#editShortcodeModal" data-shortcode-id="<?=$shortcode['shortcode_id'];?>" class="submitedit" title="<?php _e('Edit this shortcode', 'scode'); ?>"><?php _e('Edit', 'scode'); ?></a> | </span><span class="trash"><a class="submitdelete" data-shortcode-id="<?=$shortcode['shortcode_id'];?>" title="<?php _e('Remove shortcode', 'scode'); ?>" href="#"><?php _e('Remove', 'scode'); ?></a></span>
							</div>
						</td>
						<td class="author column-author"><?=stripslashes($shortcode['description']);?></td>
						<td class="categories column-categories"><?=stripslashes(esc_html($shortcode['value']));?></td>
						<td class="tags column-tags"><?=scode_get_group_name($shortcode['group_id'], $groups);?></td>
					</tr>
					<?php $nr++; } ?>
				<?php } else { ?>
					<tr class="no-items"><td class="colspanchange" colspan="4"><?php _e('Shortcodes not found.', 'scode'); ?></td></tr>
				<?php } ?>
			</tbody>
		</table>

        <?php
        if ( in_array( get_locale(), [ 'ru_RU', 'uk', 'kk', 'bel' ] ) ) {
            echo '<p>';
            echo '★ <a href="https://wpshop.ru/?utm_source=plugin&utm_medium=scode&utm_campaign=bottom" target="_blank" rel="noopener noreferrer">WPShop.ru</a> — премиум шаблоны и плагины &nbsp; ';
            echo '★ <a href="https://wpdetect.ru/?utm_source=plugin&utm_medium=scode&utm_campaign=bottom" target="_blank" rel="noopener noreferrer">WPDetect.ru</a> — определитель шаблона и плагинов &nbsp; ';
            echo '★ <a href="https://wpaudit.ru/?utm_source=plugin&utm_medium=scode&utm_campaign=bottom" target="_blank" rel="noopener noreferrer">WPAudit.ru</a> — бесплатный аудит сайта ';
            echo '</p>';
        }
        ?>
		
		<div id="addShortcodeModal" class="modal">
			<div class="modal-content">
				<div class="modal-header"><h4><?php _e('Add shortcode', 'scode'); ?></h4><a href="#" class="modal-close-button"><i class="fa fa-times"></i></a></div>
				
				<div class="modal-body">
					<div class="form-group">
						<label for="newShortcode"><?php _e('Shortcode', 'scode'); ?>:</label>
						<input type="text" class="form-control" id="newShortcode" name="shortcode" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="newShortcodeValue"><?php _e('Value', 'scode'); ?>:</label>
						<?php 
							$args = array(
								'wpautop' => 0,
								'media_buttons' => 1,
								'textarea_name' => 'value',
								'textarea_rows' => 10,
								'teeny'         => 1,
								'tinymce'       => 1,
								'quicktags'     => array(
													'id' => 'newShortcodeValue',
													'buttons' => 'strong,em,link,img,ul,ol,li'
													),
							);
							wp_editor( '', 'newShortcodeValue', $args ); ?>
					</div>
					<?php if (!empty($groups)) { ?>
					<div class="form-row">
						<div class="form-group-6">
							<label for="newShortcodeNewGroup"><?php _e('New Group', 'scode'); ?>:</label>
							<input type="text" class="form-control" id="newShortcodeNewGroup" name="newGroup" autocomplete="off">
						</div>
						<div class="form-group-6">
							<label for="newShortcodeAvailableGroup"><?php _e('or existing', 'scode'); ?>:</label>
							<select class="form-control" id="newShortcodeAvailableGroup" name="group" autocomplete="off">
								<option value=""><?php _e('Select a group', 'scode'); ?></option>
								<?php foreach ($groups as $group) { ?>
									<?php if ($group['count'] != 0) { ?><option value="<?=$group['group_id'];?>"><?=$group['group_name'];?></option><?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<?php } else { ?>
					<div class="form-group">
						<label for="newShortcodeNewGroup"><?php _e('New Group', 'scode'); ?>:</label>
						<input type="text" class="form-control" id="newShortcodeNewGroup" name="newGroup" autocomplete="off">
					</div>
					<?php } ?>
					<div class="form-group">
						<label for="newShortcodeDescription"><?php _e('Description', 'scode'); ?>:</label>
						<textarea class="form-control" id="newShortcodeDescription" name="description" rows="2" autocomplete="off"></textarea>
					</div>
					
					<p id="addResult"></p>
				</div>
				
				<div class="modal-footer">
					<i class="fa fa-refresh fa-spin hidden progress-icon" id="addShortcodeProgress"></i>
					<button id="addNewShortcodeButton" class="button button-primary" data-add-nonce="<?php echo wp_create_nonce('addNewShortcode'); ?>" autocomplete="off"><?php _e('Add!', 'scode'); ?></button>
				</div>
			</div>
		</div>
		
		<div id="editShortcodeModal" class="modal">
			<div class="modal-content">
				<div class="modal-header"><h4><?php _e('Editing shortcode', 'scode'); ?></h4><a href="#" class="modal-close-button"><i class="fa fa-times"></i></a></div>
				
				<div class="modal-body">
					<div id="viewShortcodeProgressContainer"><i class="fa fa-refresh fa-spin view-progress-icon" id="viewShortcodeProgress"></i></div>
					<div id="editable" class="hidden">
						<div class="form-group">
							<label for="editableShortcode"><?php _e('Shortcode', 'scode'); ?>:</label>
							<input type="text" class="form-control" id="editableShortcode" name="shortcode" autocomplete="off">
						</div>
						<div class="form-group">
							<label for="editableShortcodeValue"><?php _e('Value', 'scode'); ?>:</label>
							<?php 
							$args = array(
								'wpautop' => 0,
								'media_buttons' => 1,
								'textarea_name' => 'value',
								'textarea_rows' => 10,
								'teeny'         => 1,
								'tinymce'       => 1,
								'quicktags'     => array(
													'id' => 'editableShortcodeValue',
													'buttons' => 'strong,em,link,img,ul,ol,li'
													),
							);
							wp_editor( '', 'editableShortcodeValue', $args ); ?>
						</div>
						<div class="form-row">
							<div class="form-group-6">
								<label for="editableShortcodeNewGroup"><?php _e('New Group', 'scode'); ?>:</label>
								<input type="text" class="form-control" id="editableShortcodeNewGroup" name="newGroup" autocomplete="off">
							</div>
							<div class="form-group-6">
								<label for="editableShortcodeAvailableGroup"><?php _e('or existing', 'scode'); ?>:</label>
								<select class="form-control" id="editableShortcodeAvailableGroup" name="group" autocomplete="off">
									<?php foreach ($groups as $group) { ?>
									<?php if ($group['count'] != 0) { ?><option value="<?=$group['group_id'];?>"><?=$group['group_name'];?></option><?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="editableShortcodeDescription"><?php _e('Description', 'scode'); ?>:</label>
							<textarea class="form-control" id="editableShortcodeDescription" name="description" rows="2" autocomplete="off"></textarea>
						</div>
					</div>
					<p id="editResult"></p>
				</div>
				
				<div class="modal-footer">
					<i class="fa fa-refresh fa-spin hidden progress-icon" id="editShortcodeProgress"></i>
					<button id="editShortcodeButton" class="button button-primary" data-shortcode-id="" data-edit-nonce="" autocomplete="off"><?php _e('Edit!', 'scode'); ?></button>
				</div>
			</div>
		</div>
	</div>
<?php } ?>