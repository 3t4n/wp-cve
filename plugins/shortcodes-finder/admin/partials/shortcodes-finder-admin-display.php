<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 * @package    shortcodes-finder
 * @subpackage shortcodes-finder/admin/partials
 * @author     Scribit <wordpress@scribit.it>
 */

																					  

define('SHORTCODES_FINDER_MAX_CONTENT_CHAR', 100);

error_reporting(E_ERROR);

/**
 * Handle main admin page
 *
 * @since    1.2.9
 */
function sf_admin_page_handler() {

	if (isset($_GET['subpage'])){
		$_GET['subpage'] = esc_attr($_GET['subpage']);
		if ($_GET['subpage'] == 'find_content' || $_GET['subpage'] == 'test_shortcode' || $_GET['subpage'] == 'find_unused' ||
						$_GET['subpage'] == 'about' || $_GET['subpage'] == 'settings' || $_GET['subpage'] == 'test' || $_GET['subpage'] == 'documentation'
		) {
			$current_page = $_GET['subpage'];
		}
		else {
			$current_page = 'find_shortcode';
		}
    } else {
        $current_page = 'find_shortcode';
    } ?>
	<div class="wrap shortcodes-finder shortcodes-finder-<?= $current_page ?>">
		<span class="clearfix shortcodes-finder-title">
			<span class="shortcodes-finder-logo"><img src="<?= plugins_url('../images/logo.png', __FILE__) ?>"></span>
			<h1><?= __('Shortcodes Finder', 'shortcodes-finder') ?></h1>
		</span>

		<h2 class="nav-tab-wrapper">
			<a href="tools.php?page=<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>" class="nav-tab <?= ($current_page == 'find_shortcode') ? 'nav-tab-active' : '' ?>">
				<span class="dashicons dashicons-search" aria-hidden="true"></span><?= __('Find by Shortcode', 'shortcodes-finder') ?>
			</a>
			<a href="tools.php?page=<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>&subpage=find_content" class="nav-tab <?= ($current_page == 'find_content') ? 'nav-tab-active' : '' ?>">
				<span class="dashicons dashicons-search" aria-hidden="true"></span><?= __('Find by Content', 'shortcodes-finder') ?>
			</a>
			<a href="tools.php?page=<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>&subpage=find_unused" class="nav-tab <?= ($current_page == 'find_unused') ? 'nav-tab-active' : '' ?>">
				<span class="dashicons dashicons-search" aria-hidden="true"></span><?= __('Find unused Shortcodes', 'shortcodes-finder') ?>
			</a>
			<a style="color:#7A7" href="tools.php?page=<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>&subpage=documentation" class="nav-tab <?= ($current_page == 'documentation') ? 'nav-tab-active' : '' ?>">
				<span class="dashicons dashicons-media-document" aria-hidden="true"></span><?= __('Documentation', 'shortcodes-finder') ?>
			</a>
			<a style="color:#C88" href="tools.php?page=<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>&subpage=settings" class="nav-tab <?= ($current_page == 'settings') ? 'nav-tab-active' : '' ?>">
				<span class="dashicons dashicons-admin-settings" aria-hidden="true"></span><?= __('Settings', 'shortcodes-finder') ?>
			</a>
			<a style="color:#88C" href="tools.php?page=<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>&subpage=about" class="nav-tab <?= ($current_page == 'about') ? 'nav-tab-active' : '' ?>">
				<span class="dashicons dashicons-info" aria-hidden="true"></span><?= __('About', 'shortcodes-finder') ?>
			</a>
		</h2>
		<div class="shortcodes-finder-tab-content"><?php
        switch ($current_page) {
            case 'find_shortcode':
                sf_admin_page_find_shortcode_handler();
                break;

            case 'find_content':
                sf_admin_page_find_content_handler();
                break;

            case 'find_unused':
                sf_admin_page_find_unused_handler();
                break;

            case 'documentation':
                sf_admin_page_shortcodes_documentation_handler();
                break;

            case 'test_shortcode':
                sf_admin_page_test_shortcode_handler();
                break;

            case 'settings':
                sf_admin_page_settings_handler();
                break;

            case 'about':
                sf_admin_page_about_handler();
                break;
        } ?>

		</div>
	</div><?php
}

##############################

/**
 * Handle find shortcode by shortcode admin page
 *
 * @since    1.2.9
 */
function sf_admin_page_find_shortcode_handler() {

    include_once plugin_dir_path(__FILE__) . '../../includes/shortcodes-finder-utils.php';

	?><div><?= __('Find all occurrences of each shortcodes in your site.', 'shortcodes-finder') ?>
		<div class="shortcodes-finder-tooltip tooltip-help">
			<span class="tooltiptext minwidth"><?= __('Note: The process may be slow if you search all shortcodes in a website with a lot of contents.', 'shortcodes-finder') ?></span>
		</div>
	</div>

	<?php
    $shortcodes = array();
    $shortcode_tags_ordered = sf_get_shortcodes_ordered(); ?>

	<form class="find_shortcode_form" method="get">
		<input type="hidden" name="page" value="<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>">
		<p>
			<label for="shortcode_to_search"><?= __('Shortcode to search:', 'shortcodes-finder') ?></label>
			<select name="shortcode_to_search" id="shortcode_to_search">
				<option <?= (!isset($_GET['shortcode_to_search'])) ? 'selected="selected"' : '' ?> value="-1">&mdash; <?= __('All') ?> &mdash;</option>

				<?php foreach ($shortcode_tags_ordered as $shortcode_tag => $function) : ?>
					<option <?= (isset($_GET['shortcode_to_search']) && $_GET['shortcode_to_search'] == $shortcode_tag) ? 'selected="selected"' : '' ?> ><?= $shortcode_tag ?></option>
				<?php endforeach ?>
			</select>

			<label for="include_not_published" style="margin: 0 15px">
				<input type="checkbox" name="include_not_published" id="include_not_published" <?= (isset($_GET['include_not_published']) && (esc_attr($_GET['include_not_published']) == 'on')) ? 'checked' : '' ?>>
				<?= __('Include not published contents', 'shortcodes-finder') ?>
				<span class="shortcodes-finder-tooltip tooltip-help">
					<span class="tooltiptext minwidth"><?= __('The plugin will search in drafts, future, pending and private contents.', 'shortcodes-finder') ?></span>
				</span>
			</label>

			<?php if (is_multisite()) : ?>
				<label for="search_in_multisite" style="margin: 0 15px">
					<?php
					$disabled = true;
					if (!is_plugin_active_for_network('shortcodes-finder/shortcodes-finder.php')) {
						$hint = __('To enable multisite network search plugin should be actived on the network.', 'shortcodes-finder');
					}
					elseif (!is_main_site()) {
						$hint = sprintf(
							__('To search in all the network go in the <a href="%s">main site Shortcodes Finder page</a>.', 'shortcodes-finder'),
							network_site_url('wp-admin/tools.php?page=shortcodes_finder')
						);
					}
					else {
						$disabled = false;
						$hint = __('The plugin will search in all multisite network contents.', 'shortcodes-finder');
					}
					?>

					<input type="checkbox" name="search_in_multisite" id="search_in_multisite" <?= (!$disabled && isset($_GET['search_in_multisite']) && (esc_attr($_GET['search_in_multisite']) == 'on')) ? 'checked' : '' ?> <?= $disabled ? 'disabled' : '' ?>>
					<?php echo __('Search the whole multisite network', 'shortcodes-finder') ?>
					<span class="shortcodes-finder-tooltip tooltip-help">
						<span class="tooltiptext minwidth"><?php echo $hint ?></span>
					</span>
				</label>
			<?php endif ?>

			<input type="submit" class="button" value="<?php echo __('Search') ?>">
		</p>
	</form>

	<?php
   if (isset($_GET['shortcode_to_search'])) {
		$shortcode_to_search = ($_GET['shortcode_to_search'] != -1) ? esc_attr($_GET['shortcode_to_search']) : '';
		$include_not_published = (isset($_GET['include_not_published']) && (esc_attr($_GET['include_not_published']) == 'on'));
		$search_in_multisite = (is_multisite() && isset($_GET['search_in_multisite']) && (esc_attr($_GET['search_in_multisite']) == 'on'));

		if ($search_in_multisite){
			global $wpdb;
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				sf_get_shortcodes_in_content('page', __('Page'), $shortcodes, $shortcode_to_search, $include_not_published);	// $shortcodes array passed by reference
				sf_get_shortcodes_in_content('post', __('Post'), $shortcodes, $shortcode_to_search, $include_not_published);

				$types = get_post_types(array( 'public' => true, '_builtin' => false ));
				if (is_array($types)) {
					foreach ($types as $type) {
						sf_get_shortcodes_in_content($type, '', $shortcodes, $shortcode_to_search, $include_not_published);
					}
				}
			}
			restore_current_blog();
		}
		else{
			sf_get_shortcodes_in_content('page', __('Page'), $shortcodes, $shortcode_to_search, $include_not_published);	// $shortcodes array passed by reference
			sf_get_shortcodes_in_content('post', __('Post'), $shortcodes, $shortcode_to_search, $include_not_published);

			$types = get_post_types(array( 'public' => true, '_builtin' => false ));
			if (is_array($types)) {
				foreach ($types as $type) {
					sf_get_shortcodes_in_content($type, '', $shortcodes, $shortcode_to_search, $include_not_published);
				}
			}
		}

        if (count($shortcodes) == 0) : ?>

			<h3><?= __('No shortcode uses found', 'shortcodes-finder') ?>.</h3>

		<?php else :
        if (is_array($shortcodes)) {

			if ( $shortcode_to_search == '' ) {
				?><h3><?= __('Shortcodes uses found', 'shortcodes-finder') ?>:</h3><?php
			}
			else {
				?><h3><?= __('Shortcode uses found', 'shortcodes-finder') ?>:</h3><?php
			}

			$all_shortcodes = sf_get_shortcodes_ordered( true ); // Load origins

			foreach ($shortcodes as $shortcode_name => $shortcode_uses) : ?>

				<?php if ($shortcode_to_search == '') : ?>
					<div class="shortcode_accordion">
						<button class="shortcode_accordion_button">
							<span class="shortcode_counter"><?= count($shortcode_uses) ?></span> <?= $shortcode_name ?>
							<span class="shortcode_source" style="float:right"><?= is_array($all_shortcodes[$shortcode_name]) ? $all_shortcodes[$shortcode_name]['object'] : '' ?></span>
						</button>
				<?php else: ?>
					<div class="shortcodes_search_by_shortcode_result">
				<?php endif ?>

  					<div class="shortcode_accordion_panel">
  						<?php foreach ($shortcode_uses as $shortcode_use) : ?>
  							<div class="shortcode_use shortcode_use_status_<?= $shortcode_use['post']['status'] ?>">
								<span class="shortcode_use_buttons float_right">
									<a href="tools.php?page=<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>&subpage=documentation&object_to_search=<?= $all_shortcodes[$shortcode_name]['tag'] ?>#<?= $shortcode_name ?>">
										<span title="<?= __('Find documentation', 'shortcodes-finder') ?>" class="dashicons dashicons-media-document" aria-hidden="true"></span>
									</a>
  									<a target="_blank" href="<?php
										$url = 'tools.php?page='. SHORTCODES_FINDER_PLUGIN_SLUG .'&subpage=test_shortcode' .
											'&shortcode_test_shortcode=' . $shortcode_name .
											'&shortcode_test_parameters=' . urlencode($shortcode_use['params_raw']) .
											'&shortcode_test_content=' . urlencode($shortcode_use['content']);
										echo $url; ?>">
										<span title="<?= __('Test Shortcode', 'shortcodes-finder') ?>" class="dashicons dashicons-editor-code" aria-hidden="true"></span>
									</a>
								</span>
  								<p class="shortcode_code"><?php
                             if (strlen($shortcode_use['content']) > SHORTCODES_FINDER_MAX_CONTENT_CHAR) {
                                 echo '[' . $shortcode_use['name'] . $shortcode_use['params_raw'] . ']' . substr(sf_html_to_text($shortcode_use['content']), 0, SHORTCODES_FINDER_MAX_CONTENT_CHAR) . '...[/' . $shortcode_use['name'] . ']';
                             } else {
                                 echo $shortcode_use['code'];
                             } ?></p>
                        <?= $search_in_multisite ? $shortcode_use['post']['site_name'] .' - ' : '' ?>
  								<b><?= $shortcode_use['post']['type'] ?>: </b><a href="<?= $shortcode_use['post']['permalink'] ?>"><?= $shortcode_use['post']['title'] ?></a>
								<a href="<?= $shortcode_use['post']['edit_post_link'] ?>">
									<span title="<?= __('Edit content', 'shortcodes-finder') ?>" class="dashicons dashicons-edit-page" aria-hidden="true"></span>
								</a>
  							</div>
  						<?php endforeach; ?>
  					</div>
  				</div>

  			   <?php endforeach;
        }
        endif;
    }
}

##############################

/**
 * Handle find shortcode by content admin page
 *
 * @since    1.2.9
 */
function sf_admin_page_find_content_handler() { ?>

	<div>
		<?= __('Find all shortcodes in your site, divided by posts, pages and other custom types.', 'shortcodes-finder') ?>
	</div>

	<form class="find_shortcode_form" method="post">
		<input type="hidden" name="page" value="<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>">
		<input type="hidden" name="subpage" value="<?= esc_attr($_GET['subpage']) ?>">

		<p>
			<label><?= __('Search shortcodes into:', 'shortcodes-finder') ?></label>
			<button name="search_into_content" value="post" class="button <?= ($_POST['search_into_content'] == 'post') ? 'active' : '' ?>" type="submit"><?= __('Posts') ?></button>
			<button name="search_into_content" value="page" class="button <?= ($_POST['search_into_content'] == 'page') ? 'active' : '' ?>" type="submit"><?= __('Pages') ?></button>

			<?php
			$args = array(
				'public'   => true,
				'_builtin' => false
			);
			$types = get_post_types($args, 'objects');

			foreach ($types as $type) { ?>
				<button name="search_into_content" value="<?= $type->name ?>" class="button <?= ($_POST['search_into_content'] == $type->name) ? 'active' : '' ?>" type="submit"><?= $type->label ?></button>
			<?php } ?>

			<label for="include_not_published" style="margin: 0 15px">
				<input type="checkbox" name="include_not_published" id="include_not_published" <?= (isset($_POST['include_not_published']) && (esc_attr($_POST['include_not_published']) == 'on')) ? 'checked' : '' ?>>
				<?= __('Include not published contents', 'shortcodes-finder') ?>
				<span class="shortcodes-finder-tooltip tooltip-help">
					<span class="tooltiptext minwidth"><?= __('The plugin will search in drafts, future, pending and private contents.', 'shortcodes-finder') ?></span>
				</span>
			</label>
		</p>

		<div class="shortcodes-finder-progress">
			<div class="progress-bar">
				<div class="progress-label">
					<?= __('Progress', 'shortcodes-finder') ?>
					<span class="progress-label-value">0</span> %
				</div>
			</div>
		</div>
	</form>

	<div class="shortcodes_result shortcodes_search_by_content_result"></div>

<?php }

/**
 * Search shorcodes in a list of contents and print_it
 *
 * @since    1.2.9
 */
function sf_print_contents_shortcodes($posts) {
	
	include_once plugin_dir_path(__FILE__) . '../../includes/shortcodes-finder-utils.php';

    $shortcodes = sf_get_shortcodes_by_post($posts);

    if (count($shortcodes) > 0) :

		$all_shortcodes = sf_get_shortcodes_ordered( true ); // Load origins

        foreach ($shortcodes as $post_id => $shortcode_uses) : ?>

			<div class="shortcode_accordion">
				<button class="shortcode_accordion_button"><?= '<span class="shortcode_counter">' . count($shortcode_uses) . '</span>' . $shortcode_uses[0]['post']['title'] ?></button>
				<div class="shortcode_accordion_panel">

					<?php foreach ($shortcode_uses as $shortcode_use) : ?>
						<div class="shortcode_use shortcode_use_status_<?= $shortcode_use['post']['status'] ?>">
							<span class="shortcode_use_buttons float_right">
								<a href="tools.php?page=<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>&subpage=documentation&object_to_search=<?= $all_shortcodes[$shortcode_use['name']]['tag'] ?>#<?= $shortcode_use['name'] ?>">
									<span title="<?= __('Find documentation', 'shortcodes-finder') ?>" class="dashicons dashicons-media-document" aria-hidden="true"></span>
								</a>
								<a target="_blank" href="<?php
									$url = 'tools.php?page='. SHORTCODES_FINDER_PLUGIN_SLUG .'&subpage=test_shortcode' .
										'&shortcode_test_shortcode=' . $shortcode_use['name'] .
										'&shortcode_test_parameters=' . urlencode($shortcode_use['params_raw']) .
										'&shortcode_test_content=' . urlencode($shortcode_use['content']);
									echo $url; ?>">
									<span title="<?= __('Test Shortcode', 'shortcodes-finder') ?>" class="dashicons dashicons-editor-code" aria-hidden="true"></span>
								</a>
							</span>
							<p class="shortcode_code"><?php
                                if (strlen($shortcode_use['content']) > SHORTCODES_FINDER_MAX_CONTENT_CHAR) {
                                    echo '[' . $shortcode_use['name'] . $shortcode_use['params_raw'] . ']' . substr(sf_html_to_text($shortcode_use['content']), 0, SHORTCODES_FINDER_MAX_CONTENT_CHAR) . '...[/' . $shortcode_use['name'] . ']';
                                } else {
                                    echo $shortcode_use['code'];
                                } ?></p>
							<a href="<?= $shortcode_use['post']['permalink'] ?>"><?= $shortcode_use['post']['title'] ?></a>
							<a href="<?= $shortcode_use['post']['edit_post_link'] ?>">
								<span title="<?= __('Edit content', 'shortcodes-finder') ?>" class="dashicons dashicons-edit-page" aria-hidden="true"></span>
							</a>
						</div>
					<?php endforeach; ?>

				</div>
			</div>

		<?php endforeach;
    endif;
}

##############################

/**
 * Handle find unused shortcode admin page
 *
 * @since    1.2.9
 */
function sf_admin_page_find_unused_handler() {

    ?><div>
		<?= __('Find the shortcodes in your site, coming from deleted or deactivated plugins and themes.', 'shortcodes-finder') ?>
		<div class="shortcodes-finder-tooltip tooltip-help">
			<span class="tooltiptext minwidth"><?= __('Note: Results may be incomplete or may contain false positives.', 'shortcodes-finder') ?></span>
		</div>
	</div>

	<form class="find_shortcode_form" method="post">
		<input type="hidden" name="page" value="<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>">
		<input type="hidden" name="subpage" value="<?= esc_attr($_GET['subpage']) ?>">

		<p>
			<label><?= __('Search unused shortcodes into:', 'shortcodes-finder') ?></label>
			<button name="search_into_content" value="post" class="button <?= ($_POST['search_into_content'] == 'post') ? 'active' : '' ?>" type="submit"><?= __('Posts') ?></button>
			<button name="search_into_content" value="page" class="button <?= ($_POST['search_into_content'] == 'page') ? 'active' : '' ?>" type="submit"><?= __('Pages') ?></button>

			<?php
			$args = array(
				'public'   => true,
				'_builtin' => false
			);
			$types = get_post_types($args, 'objects');

			foreach ($types as $type) { ?>
				<button name="search_into_content" value="<?= $type->name ?>" class="button <?= ($_POST['search_into_content'] == $type->name) ? 'active' : '' ?>" type="submit"><?= $type->label ?></button>
			<?php } ?>

			<label for="include_not_published" style="margin: 0 15px">
				<input type="checkbox" name="include_not_published" id="include_not_published" <?= (isset($_POST['include_not_published']) && (esc_attr($_POST['include_not_published']) == 'on')) ? 'checked' : '' ?>>
				<?= __('Include not published contents', 'shortcodes-finder') ?>
				<span class="shortcodes-finder-tooltip tooltip-help">
					<span class="tooltiptext minwidth"><?= __('The plugin will search in drafts, future, pending and private contents.', 'shortcodes-finder') ?></span>
				</span>
			</label>
		</p>

		<div class="shortcodes-finder-progress">
			<div class="progress-bar">
				<div class="progress-label">
					<?= __('Progress', 'shortcodes-finder') ?>
					<span class="progress-label-value">0</span> %
				</div>
			</div>
		</div>
	</form>

	<div class="shortcodes_result shortcodes_search_unused_result"></div>

<?php }

/**
 * Search unused shorcodes in a list of contents and return it in JSON format.
 *
 * @since    1.2.10
 */
function sf_get_unused_shortcodes($posts) {

    include_once plugin_dir_path(__FILE__) . '../../includes/shortcodes-finder-utils.php';
	
	$shortcodes = sf_get_shortcodes_unused_by_post($posts);

    $shortcodes_final = array();
	if (is_array($shortcodes))
		foreach ($shortcodes as $shortcode_name => $shortcode_uses) {
			$pos = strpos($shortcode_name, ' ');
			if ($pos > 0) {
				$shortcode_name = substr($shortcode_name, 0, $pos);
			}
			foreach ($shortcode_uses as $shortcode_use) {
				if (strlen($shortcode_use['content']) > SHORTCODES_FINDER_MAX_CONTENT_CHAR) {
					$shortcode_use['code'] = '[' . $shortcode_use['name'] . $shortcode_use['params_raw'] . ']' .
						substr(sf_html_to_text($shortcode_use['content']), 0, SHORTCODES_FINDER_MAX_CONTENT_CHAR) . '...[/' . $shortcode_use['name'] . ']';
				} else {
					$shortcode_use['code'] = $shortcode_use['code'];
				}

				$shortcodes_final[$shortcode_name][] = $shortcode_use;

				// Useful system variables to pass to javascript (must starts with ~)
				$shortcodes_final['~img_edit_post~'] = '<span title="'. __('Edit content', 'shortcodes-finder') .'" class="dashicons dashicons-edit-page" aria-hidden="true"></span>';
			}
		}

    echo json_encode($shortcodes_final);
}

##############################

/**
 * Handle test shortcode admin page
 *
 * @since    1.2.9
 */
function sf_admin_page_test_shortcode_handler() {
	
	include_once plugin_dir_path(__FILE__) . '../../includes/shortcodes-finder-utils.php';

    $shortcode_tags_ordered = sf_get_shortcodes_ordered();

    if (isset($_GET['shortcode_test_shortcode'])) {
        $shortcode = esc_attr($_GET['shortcode_test_shortcode']);
        $parameters = (strlen($_GET['shortcode_test_parameters']) > 0) ? stripslashes(urldecode(esc_attr($_GET['shortcode_test_parameters']))) : '';
        $content = (strlen($_GET['shortcode_test_content']) > 0) ? stripslashes(urldecode(esc_attr($_GET['shortcode_test_content']))) : '';
    } else {
        $parameters = '';
        $content = '';
    } ?>
	<div>
		<?= __('All the shortcodes provided by your installed themes and plugins are listed here. Select one of these and, if expected, insert some options or content texts.', 'shortcodes-finder') ?>
	</div>
	<form>
		<input type="hidden" name="page" value="<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>" />
		<input type="hidden" name="subpage" value="test_shortcode" />
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="shortcode_test_shortcode"><?= __('Select a Shortcode', 'shortcodes-finder') ?></label></th>
					<td><select id="shortcode_test_shortcode" name="shortcode_test_shortcode">
						<?php if (is_array($shortcode_tags_ordered))
								foreach ($shortcode_tags_ordered as $shortcode_tag => $function) : ?>
									<option <?= (isset($shortcode) && $shortcode == $shortcode_tag) ? 'selected="selected"' : '' ?> ><?= $shortcode_tag ?></option>
								<?php endforeach ?>
					</select></td>
				</tr>
				<tr>
					<th scope="row"><label for="shortcode_test_parameters"><?= __('Insert parameters string (optional)', 'shortcodes-finder') ?></label></th>
					<td><textarea rows="5" id="shortcode_test_parameters" name="shortcode_test_parameters"><?php echo $parameters ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="shortcode_test_content"><?= __('Insert content (optional)', 'shortcodes-finder') ?></label></th>
					<td><textarea rows="5" id="shortcode_test_content" name="shortcode_test_content"><?php echo $content ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"></th>
					<td><input type="submit" class="button button-primary" value="<?= __('Test it', 'shortcodes-finder') ?>" /></td>
				</tr>


				<?php if (isset($shortcode)) : ?>
					<tr><td colspan="2"><hr/></td></tr>
					<tr>
						<th scope="row"><?= __('Shortcode code', 'shortcodes-finder') ?></th>
						<td class="shortcode_test_code shortcodes-finder-tooltip">
							<div onclick="copyContentToClipboard(this, '<?= __('Shortcode copied to clipboard', 'shortcodes-finder') ?>')">
							<?php
								if (strlen($_GET['shortcode_test_content']) > 0) {
									$code = '[' . $shortcode . ' ' . $parameters . ']' . $content . '[/' . $shortcode . ']';
								} else {
									$code = '[' . $shortcode . ' ' . $parameters . ']';
								}
								echo $code;
							?>
							</div>
							<span class="tooltiptext"><?= __('Copy to clipboard', 'shortcodes-finder') ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><?= __('Test result', 'shortcodes-finder') ?></th>
						<td class="shortcode_test_result"><?php echo do_shortcode($code); ?></td>
					</tr>
				<?php endif ?>
			</tbody>
		</table>
	</form>

<?php }

##############################

/**
 * Handle about admin page
 *
 * @since    1.2.9
 */
function sf_admin_page_settings_handler() {
	
	include_once plugin_dir_path(__FILE__) . '../../includes/shortcodes-finder-utils.php';

    $shortcodes = sf_get_shortcodes_ordered();

    if (isset($_POST['save'])) {
        $res = true;
        $nonce = sanitize_text_field(wp_unslash($_POST['nonce']));

        $sf_settings_disable_existing_shortcodes =
          isset($_POST['sf_settings_disable_existing_shortcodes']) && (esc_attr($_POST['sf_settings_disable_existing_shortcodes']) == 'on') ? true : false;
        $sf_settings_disable_unused =
          isset($_POST['sf_settings_disable_unused']) && (esc_attr($_POST['sf_settings_disable_unused']) == 'on') ? true : false;

        $sf_settings_disabled_shortcodes = array();
        if ($sf_settings_disable_existing_shortcodes && isset($_POST['sf_settings_disabled_shortcodes']) && is_array($_POST['sf_settings_disabled_shortcodes'])) {
            foreach ($_POST['sf_settings_disabled_shortcodes'] as $shortcode => $value) {
                // Server side check on passed values (shortcode tag name).
                if (isset($shortcodes[$shortcode])) {
                    $sf_settings_disabled_shortcodes[] = $shortcode;
                }
            }
        }

        if (wp_verify_nonce($nonce, 'shortcodes-finder-settings-save')) {
            update_option(
                SHORTCODES_FINDER_OPTION_DISABLE_UNUSED,
                $sf_settings_disable_unused,
                true
			);

            update_option(
                SHORTCODES_FINDER_OPTION_DISABLED_SHORTCODES,
                $sf_settings_disabled_shortcodes,
                true
			);
        }
		else {
            $res = false;
        } ?>

		<div id="setting-error-settings_updated" class="<?= $res ? '' : 'error' ?> updated settings-error notice is-dismissible">
			<p><strong><?= $res ? __('Settings saved.', 'shortcodes-finder') : __('Saving Error.', 'shortcodes-finder') ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?= __('Dismiss this notice.', 'shortcodes-finder') ?></span>
			</button>
		</div>
	<?php }

	else {
        $nonce = wp_create_nonce('shortcodes-finder-settings-save');

        $sf_settings_disable_unused = get_option(SHORTCODES_FINDER_OPTION_DISABLE_UNUSED, false);
        $sf_settings_disabled_shortcodes = get_option(SHORTCODES_FINDER_OPTION_DISABLED_SHORTCODES);
    } ?>

	<h3><?= __('Settings', 'shortcodes-finder') ?></h3>

	<form method="post" novalidate="novalidate">
		<input type="hidden" name="nonce" value="<?php echo esc_attr($nonce) ?>" />
		<table class="form-table" role="presentation">
			<tbody>
			<tr>
					<th scope="row">
				<label for="sf_settings_disable_unused">
				  <input id="sf_settings_disable_unused" name="sf_settings_disable_unused" type="checkbox"
					<?= ($sf_settings_disable_unused ? 'checked' : '') ?>>
				  <?= __('Disable unused/orphan shortcodes', 'shortcodes-finder') ?>
				</label>
					</th>
					<td>
						<p class="description">
				  <?= __('Remove shortcode tags coming from deleted or deactivated plugins and themes.', 'shortcodes-finder') ?>
				</p>
			  </td>
			</tr>

			<tr>
					<th scope="row">
				<label for="sf_settings_disable_existing_shortcodes">
				  <input id="sf_settings_disable_existing_shortcodes" name="sf_settings_disable_existing_shortcodes" type="checkbox"
					<?= ((is_array($sf_settings_disabled_shortcodes) && count($sf_settings_disabled_shortcodes)) ? 'checked' : '') ?>>
				  <?= __('Disable existing shortcodes', 'shortcodes-finder') ?>
				</label>
					</th>
					<td>
      				<p class="description" style="margin-bottom:.5em">
      				  <?= __('Remove tags and contents from selected shortcodes.', 'shortcodes-finder') ?>
      				</p>
      				<div id="sf_settings_existing_shortcodes">
      					<fieldset><?php
      					  if (is_array($shortcodes)) {
      						  foreach ($shortcodes as $shortcode_tag => $function) { ?>
      					        <label for="sf_settings_disable_<?= $shortcode_tag ?>">
      									<input name="sf_settings_disabled_shortcodes[<?= $shortcode_tag ?>]" id="sf_settings_disable_<?= $shortcode_tag ?>" type="checkbox"
      						            <?= (is_array($sf_settings_disabled_shortcodes) && in_array($shortcode_tag, $sf_settings_disabled_shortcodes) ? 'checked' : '') ?>>
      									<?= strtoupper($shortcode_tag) ?>
      					        </label><br/>
      					<?php }
      					} ?></fieldset>
      				</div>
					</td>
				</tr>
		  </tbody>
		</table>

		<p class="submit">
		  <input type="submit" name="save" class="button button-primary" value="<?= __('Save settings', 'shortcodes-finder') ?>">
		</p>
	</form>

<?php }

##############################

/**
 * Handle find about admin page
 *
 * @since    1.2.9
 */
function sf_admin_page_about_handler() { ?>

	<table id="shortcodes_finder_about_support">
		<tr>
			<td class="scribit_support_description"><?= __('If you like our plugin please feel free to give us 5 stars :)', 'shortcodes-finder') ?></td>
			<td><a target="_blank" class="button button-primary scribit_support_button" rel="nofollow" href="https://wordpress.org/support/plugin/shortcodes-finder/reviews/">
				<span style="color:#CFC" class="dashicons dashicons-star-filled" aria-hidden="true"></span><?= __('WRITE A PLUGIN REVIEW', 'shortcodes-finder') ?>
			</a></td>
		</tr>

		<tr>
			<td class="scribit_support_description"><?= __('If you want to help us to improve our service please Donate a coffe', 'shortcodes-finder') ?></td>
			<td><a target="_blank" class="button button-primary scribit_support_button" rel="nofollow" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=riccardosormani@gmail.com&item_name=Shortcodes Finder Wordpress plugin donation&no_note=0">
				<span style="color:#FC9" class="dashicons dashicons-coffee" aria-hidden="true"></span><?= __('DONATE WITH PAYPAL', 'shortcodes-finder') ?>
			</a></td>
		</tr>

		<tr>
			<td class="scribit_support_description"><?= __('If you want some information about our Company', 'shortcodes-finder') ?></td>
			<td><a target="_blank" class="button button-primary scribit_support_button" href="mailto:wordpress@scribit.it">
				<span style="color:#DDD" class="dashicons dashicons-email" aria-hidden="true"></span><?= __('CONTACT US', 'shortcodes-finder') ?>
			</a></td>
		</tr>
	</table>

	<br/><hr/>

	<h4><?php echo esc_html__('Try other Scribit plugins:', 'shortcodes-finder') ?></h4>
	<div class="wp-list-table widefat plugin-install">
		<div class="scribit_plugins">

			<?php $plugin_slug = 'proofreading'; ?>
			<div class="plugin-card plugin-card-<?php echo $plugin_slug ?>">
				<div class="plugin-card-top">
					<div class="name column-name">
						<h3><a href="
							<?php if ( is_multisite() ) : ?>
								<?php echo esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin='. $plugin_slug ) ) ?>
							<?php else : ?>
								<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin='. $plugin_slug ) ) ?>
							<?php endif ?>
						">Proofreading<img src="https://ps.w.org/<?php echo $plugin_slug ?>/assets/icon-256x256.png" class="plugin-icon"></a></h3>
					</div>
					<div class="action-links">
						<ul class="plugin-action-buttons">
							<?php if ( class_exists('Proofreading_Admin') ) : ?>
								<li><button type="button" class="button button-disabled" disabled="disabled"><?php echo esc_html__( 'Active', 'shortcodes-finder') ?></button></li>
							<?php else: ?>
								<li><a href="
									<?php if ( is_multisite() ) : ?>
										<?php echo esc_url( network_admin_url( 'plugin-install.php?s='. $plugin_slug .'+scribit&tab=search&type=term' ) ) ?>
									<?php else : ?>
										<?php echo esc_url( admin_url( 'plugin-install.php?s='. $plugin_slug .'+scribit&tab=search&type=term' ) ) ?>
									<?php endif ?>
								" class="button button-primary"><?php echo esc_html__( 'Install') ?></a></li>
							<?php endif; ?>
							<li><a href="
								<?php if ( is_multisite() ) : ?>
									<?php echo esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin='. $plugin_slug ) ) ?>
								<?php else : ?>
									<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin='. $plugin_slug ) ) ?>
								<?php endif ?>
							" class="thickbox open-plugin-details-modal"><?php echo esc_html__('More Details') ?></a></li>
						</ul>
					</div>
					<div class="desc column-description">
						<p><?php echo esc_html__('Proofreading plugin allows you to improve the quality of your posts, pages and all your WordPress website.', 'shortcodes-finder') ?></p>
						<p><?php echo esc_html__('It gives you the possibility to check the correction of the texts inserted into posts, pages and drafts in less than a second!', 'shortcodes-finder') ?></p>
						<p><?php echo esc_html__('18 languages supported.', 'shortcodes-finder') ?></p>
					</div>
				</div>
			</div>

			<?php $plugin_slug = 'random'; ?>
			<div class="plugin-card plugin-card-<?php echo $plugin_slug ?>">
				<div class="plugin-card-top">
					<div class="name column-name">
						<h3><a href="
							<?php if ( is_multisite() ) : ?>
								<?php echo esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin='. $plugin_slug ) ) ?>
							<?php else : ?>
								<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin='. $plugin_slug ) ) ?>
							<?php endif ?>
							">Random<img src="https://ps.w.org/<?php echo $plugin_slug ?>/assets/icon-256x256.png" class="plugin-icon"></a>
						</h3>
					</div>
					<div class="action-links">
						<ul class="plugin-action-buttons">
							<?php if ( class_exists('Random_Admin') ) : ?>
								<li><button type="button" class="button button-disabled" disabled="disabled"><?php echo esc_html__( 'Active', 'shortcodes-finder') ?></button></li>
							<?php else: ?>
								<li>
									<a href="
									<?php if ( is_multisite() ) : ?>
										<?php echo esc_url( network_admin_url( 'plugin-install.php?s='. $plugin_slug .'+scribit&tab=search&type=term' ) ) ?>
									<?php else : ?>
										<?php echo esc_url( admin_url( 'plugin-install.php?s='. $plugin_slug .'+scribit&tab=search&type=term' ) ) ?>
									<?php endif; ?>
									" class="button button-primary"><?php echo esc_html__( 'Install') ?></a>
								</li>
							<?php endif; ?>
							<li><a href="
								<?php if ( is_multisite() ) : ?>
									<?php echo esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin='. $plugin_slug ) ) ?>
								<?php else : ?>
									<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin='. $plugin_slug ) ) ?>
								<?php endif ?>
							" class="thickbox open-plugin-details-modal"><?php echo esc_html__('More Details') ?></a></li>
						</ul>
					</div>
					<div class="desc column-description">
						<p><?php echo esc_html__('Insert into your WordPress website one or more random contents coming from your posts. The source contents can be pages, posts or custom post types.', 'shortcodes-finder') ?></p>
						<p><?php echo __('You can display different informations:<ul>
						   <li>A list of post titles</li>
						   <li>One or more full contents or excerpts</li>
						   <li>Raw URLs to posts permalink</li></ul>', 'shortcodes-finder') ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }

##############################

/**
 * Handle shortcodes documentation admin page.
 *
 * @since    1.4.0
 */
function sf_admin_page_shortcodes_documentation_handler() {
	
	include_once plugin_dir_path(__FILE__) . '../../includes/shortcodes-finder-utils.php';

	// Form options
	$object_to_search = (isset($_GET['object_to_search']) && $_GET['object_to_search'] != -1) ? esc_attr($_GET['object_to_search']) : '';
	$load_attributes = (isset($_GET['load_attributes']) && esc_attr($_GET['load_attributes']) == 'on');

	// Get shortcodes: Load origins. Don't load attributes. Don't filter objects.
	$all_shortcodes = sf_get_shortcodes_ordered( true );
	$all_objects = sf_get_objects_with_shortcodes( $all_shortcodes );

	if (count($all_shortcodes) == 0) { ?>

		<p><?= __('No shortcodes found', 'shortcodes-finder') ?></p>

	<?php } else { ?>

		<div><?= __('Get documentation about shortcodes in your website.', 'shortcodes-finder') ?></div>

		<form class="find_shortcode_form" method="get">
			<input type="hidden" name="page" value="<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>">
			<input type="hidden" name="subpage" value="<?= esc_attr($_GET['subpage']) ?>">

			<p>
				<label for="object_to_search"><?= __('Select shortcode origin:', 'shortcodes-finder') ?></label>
				<select name="object_to_search" id="object_to_search">
					<option <?= ( $object_to_search == '' ) ? 'selected="selected"' : '' ?> value="-1">&mdash; <?= __('All') ?> &mdash;</option>

					<?php foreach ($all_objects as $object_tag => $object_detail ) : ?>
						<option value="<?= $object_tag ?>" <?= ($object_to_search == $object_tag) ? 'selected="selected"' : '' ?>><?= $object_detail ?></option>
					<?php endforeach ?>
				</select>

				<input type="submit" class="button" value="<?= __('Search') ?>">
			</p>
			<p class="shortcodes-finder_note">
				<input type="checkbox" name="load_attributes" id="load_attributes" <?= $load_attributes ? 'checked' : '' ?>>
				<label for="load_attributes"><?= __('Load shortcode parameters (may cause errors based on the development of individual plugins/themes)', 'shortcodes-finder') ?></label>
				<span class="shortcodes-finder-tooltip tooltip-help">
					<span class="tooltiptext minwidth"><?= __('Note: Parameters are available only for shortcode sources using <i>shortcode_atts</i> function.', 'shortcodes-finder') ?></span>
				</span>
			</p>
		</form>

		<?php
		if (isset($_GET['object_to_search'])) {

			// Get shortcodes: Load origins. Load attributes based on checkbox. Filter object based on select input.
			$all_shortcodes = sf_get_shortcodes_ordered( true, $load_attributes, $object_to_search );

			?><h3><?= __('Shortcodes found:', 'shortcodes-finder') ?></h3><?php

			foreach ($all_objects as $object_tag => $object_detail ) {

				if ( $object_to_search == '' ) { ?>
					<div class="shortcode_accordion">
						<button class="shortcode_accordion_button">
							<?= $object_detail ?>
						</button>
				<?php } elseif ( $object_to_search == $object_tag ) { ?>
					<div class="shortcodes_search_by_shortcode_result">
				<?php } else {
						continue;
				      } ?>
						<div class="shortcode_accordion_panel">
							<?php foreach ($all_shortcodes as $shortcode_name => $shortcode_origin) {

								$test_url = 'tools.php?page='. SHORTCODES_FINDER_PLUGIN_SLUG .'&subpage=test_shortcode' . '&shortcode_test_shortcode=' . $shortcode_name;

								$shortcode_params = $shortcode_params_list = '';
								if ( is_array( $shortcode_origin['attributes'] ) ) {
									foreach ( $shortcode_origin['attributes'] as $attr_name => $attr_value ) {
										$shortcode_params .= ' '. $attr_name .'="'. $attr_value .'"';
										$shortcode_params_list .= '<b>'. $attr_name .'</b> = '. $attr_value .'<br/>';
									}
								}

								if ( $shortcode_params !== '' ) {
									$test_url .= '&shortcode_test_parameters=' . urlencode( $shortcode_params );
								}
								$google = 'https://www.google.com/search?q=' . urlencode( 'Wordpress shortcode "'. $shortcode_name .'" '. $object_detail );

								if ( $object_tag == $shortcode_origin['tag'] ) { ?>
									<div class="shortcode_use">
										<span class="shortcode_use_buttons float_right">
											<a href="tools.php?page=<?= SHORTCODES_FINDER_PLUGIN_SLUG ?>&shortcode_to_search=<?= $shortcode_name ?>">
												<span title="<?= __('Find in contents', 'shortcodes-finder') ?>" class="dashicons dashicons-search" aria-hidden="true"></span>
											</a>
											<a target="_blank" href="<?= $test_url ?>">
												<span title="<?= __('Test Shortcode', 'shortcodes-finder') ?>" class="dashicons dashicons-editor-code" aria-hidden="true"></span>
											</a>
		  									<a href="<?= $google ?>" target="_blank">
												<span title="<?= __('Find documentation', 'shortcodes-finder') ?>" class="dashicons dashicons-admin-site" aria-hidden="true"></span>
											</a>
										</span>
										<a name="<?= $shortcode_name ?>" id="<?= $shortcode_name ?>" class="shortcode-bookmark"></a>
										<h3><?= $shortcode_name ?></h3>
										<?= (strlen($shortcode_params_list)) ? '<div class="shortcode_parameters">'. rtrim($shortcode_params_list, '<br/>') .'</div>': '' ?>
										<p class="shortcode_code">
											<?= '<b>' . __('Source file', 'shortcodes-finder') . '</b>: ' . $shortcode_origin['file'] . ': ' . $shortcode_origin['line'] ?><br/>
											<?= sf_get_shortcode_callback_definition( $shortcode_origin ) ?><br/>
										</p>
									</div>
								<?php
								}
  							} ?>
  						</div>
					</div>
				<?php
			}
		}
	}
}

/**
 * Giving a shortcode source returns default configuration for shortcode options.
 *
 * @since    1.4.0
 */
function sf_get_shortcode_attributes( $shortcode_origin ) {

	$atts = $shortcode_origin['attributes'];
	$out = '';

	if ( is_array( $atts ) ) {
		foreach ($atts as $attr_name => $attr_value ) {
			$out .= ' ' . $attr_name . '="' . $attr_value . '"';
		}
	}

	return $out;
}

/**
 * Get pretty callback definition for shortcode.
 *
 * @since    1.4.0
 */
function sf_get_shortcode_callback_definition( $shortcode_origin ) {

	$out = '<b>';

	switch ( $shortcode_origin['callback-type'] ) {
		case 'class':
			$out .= __('Class', 'shortcodes-finder');
			break;
		case 'function':
			$out .= __('Function', 'shortcodes-finder');
			break;
		default:
			$out .= __('Error', 'shortcodes-finder');
	}

	$out .= '</b>: ' . $shortcode_origin['callback-name'];

	return $out;
}
