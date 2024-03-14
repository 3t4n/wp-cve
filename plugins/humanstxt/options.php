<?php

if (! defined('ABSPATH')) {
    exit;
}

/**
 * URL to Humans TXT plugin folder.
 */
define('HUMANSTXT_PLUGIN_URL', plugin_dir_url(HUMANSTXT_PLUGIN_FILE));

/**
 * Humans TXT's "WordPress basename".
 */
define('HUMANSTXT_PLUGIN_BASENAME', plugin_basename(HUMANSTXT_PLUGIN_FILE));

/**
 * URL to Humans TXT options page.
 */
define('HUMANSTXT_OPTIONS_URL', admin_url('options-general.php?page=humanstxt'));

/**
 * URL to Humans TXT options page.
 * @since 1.1.0
 */
define('HUMANSTXT_REVISIONS_URL', add_query_arg(array('subpage' => 'revisions'), HUMANSTXT_OPTIONS_URL));

/**
 * Register plugin admin actions, filters and hooks.
 */
add_action('admin_init', 'humanstxt_admin_init');
add_action('admin_menu', 'humanstxt_admin_menu');
add_action('admin_notices', 'humanstxt_version_warning');
add_action('admin_print_styles', 'humanstxt_admin_print_styles');
add_action('admin_print_scripts', 'humanstxt_admin_print_scripts');
add_action('wp_ajax_humanstxt-preview', 'humanstxt_ajax_preview');
add_action('after_plugin_row_humans-txt/plugin.php', 'humanstxt_plugin_notice', 10, 3);
add_action('after_plugin_row_humans-dot-txt/humans-dot-txt.php', 'humanstxt_plugin_notice', 10, 3);
add_filter('plugin_action_links_'.HUMANSTXT_PLUGIN_BASENAME, 'humanstxt_actionlinks');
register_uninstall_hook(__FILE__, 'humanstxt_uninstall');

/**
 * Load plugin text-domain.
 */
humanstxt_load_textdomain();

/**
 * Enqueue style/script.
 */
function humanstxt_admin_print_styles()
{
    wp_enqueue_style('thickbox');
    wp_enqueue_style('humanstxt-options');
}

function humanstxt_admin_print_scripts()
{
    wp_enqueue_script('thickbox');
    wp_enqueue_script('humanstxt-options');
}

/**
 * Callback function for 'admin_init' action.
 * Registers the CSS and JavaScript file.
 * Calls humanstxt_update_options() if necessary.
 * Calls humanstxt_restore_revision() if necessary.
 * Calls humanstxt_import_file() if necessary.
 */
function humanstxt_admin_init()
{
    if (isset($_GET['page']) && $_GET['page'] == 'humanstxt') {

        // register css/js files
        wp_register_style('humanstxt-options', HUMANSTXT_PLUGIN_URL.'options.css', array(), HUMANSTXT_VERSION);
        wp_register_script('humanstxt-options', HUMANSTXT_PLUGIN_URL.'options.js', array('jquery', 'hoverIntent'), HUMANSTXT_VERSION);

        // update plugin options?
        if (isset($_POST['action']) && $_POST['action'] == 'update') {
            check_admin_referer('humanstxt-options');
            humanstxt_update_options();
        }

        // restore a revision?
        if (isset($_GET['action'], $_GET['revision']) && $_GET['action'] == 'restore') {
            check_admin_referer('restore-humanstxt_'.$_GET['revision']);
            humanstxt_restore_revision($_GET['revision']);
        }

        // import & rename physical humans.txt file?
        if (isset($_GET['action']) && $_GET['action'] == 'import-file') {
            check_admin_referer('import-humanstxt-file');
            humanstxt_import_file();
        }
    }
}

/**
 * Callback function if plugin is uninstalled.
 * Deletes all plugin options from the database.
 */
function humanstxt_uninstall()
{
    delete_option('humanstxt_options');
    delete_option('humanstxt_content');
    delete_option('humanstxt_revisions');
}

/**
 * Callback function for 'admin_notices' action.
 * Prints warning message if the current WP version is too old.
 *
 * @since 1.0.1
 */
function humanstxt_version_warning()
{
    if (!humanstxt_is_wp(HUMANSTXT_VERSION_REQUIRED)) {
        $updatelink = ' <a href="'.admin_url('update-core.php').'">'.sprintf(__('Please update your WordPress installation.', 'humanstxt')).'</a>';
        echo '<div id="humanstxt-warning" class="updated fade"><p><strong>'.sprintf(__('Humans TXT %1$s requires WordPress %2$s or higher.', 'humanstxt'), HUMANSTXT_VERSION, HUMANSTXT_VERSION_REQUIRED).'</strong>'.(current_user_can('update_core') ? $updatelink : '').'</p></div>';
    }
}

/**
 * Return TRUE if given $version is higher or equals the running
 * WordPress version. This function consideres pre-release versions,
 * such as 3.0.0-dev, as high as their final release counterparts (like 4.0.0).
 *
 * @since 1.2.0
 */
function humanstxt_is_wp($version)
{
    return version_compare(preg_replace('~[^0-9.]~', '', get_bloginfo('version')), $version, '>=');
}

/**
 * Callback function for 'admin_menu' action.
 * Registers the options page if the current user has access.
 */
function humanstxt_admin_menu()
{
    $roles = humanstxt_option('roles');
    array_unshift($roles, 'administrator'); // admins can always edit

    // loop through all roles that can edit the humans.txt and
    // add options page if the current user has one of the required roles
    foreach ($roles as $role) {
        if (current_user_can($role)) {
            $plugin_page = add_options_page(__('Humans TXT', 'humanstxt'), __('Humans TXT', 'humanstxt'), $role, 'humanstxt', 'humanstxt_options');
            break;
        }
    }

    // add contextual help menu
    if (isset($plugin_page)) {
        add_action('load-'.$plugin_page, 'humanstxt_contextual_help');
    }
}

/**
 * Callback function for 'plugin_action_links_{$plugin_file}' filter.
 * Adds a link to the plugin options page.
 *
 * @param array $actions
 * @return array $actions Hijacked actions.
 */
function humanstxt_actionlinks($actions)
{
    return array_merge(
        array('settings' => sprintf('<a href="%s">%s</a>', HUMANSTXT_OPTIONS_URL, /* translators: DO NOT TRANSLATE! */ __('Settings'))),
        $actions
    );
}

/**
 * Callback function for 'load-{$page_hook}' action.
 * Registers the contextual help menu.
 */
function humanstxt_contextual_help()
{
    $humanstxt = sprintf(
        '<p><strong>%s</strong> &mdash; %s</p>',
        __('What is the humans.txt?', 'humanstxt'),
        __("It's an initiative for knowing the people behind a website. It's a TXT file in the site root that contains information about the humans who have contributed to the website.", 'humanstxt')
    );
    $humanstxt .= sprintf(
        '<p><strong>%s</strong> &mdash; %s</p>',
        __('Who should I mention?', 'humanstxt'),
        __('Whoever you want to, provided they wish you to do so. You can mention the developer, the designer, the copywriter, the webmaster, the editor, ... anyone who contributed to the website.', 'humanstxt')
    );
    $humanstxt .= sprintf(
        '<p><strong>%s</strong> &mdash; %s</p>',
        __('How should I format it?', 'humanstxt'),
        __('However you want, just make sure humans can easily read it. For some inspiration check the humans.txt of <a href="http://humanstxt.org/humans.txt" rel="external">humanstxt.org</a> or <a href="http://html5boilerplate.com/humans.txt" rel="external">html5boilerplate.com</a>.', 'humanstxt')
    );

    $variables = __('Variables can be used to show dynamic content in your humans.txt file. You can show your visitors for example the amount of published posts, a list of activated plugins, the name of the current theme or the installed WordPress version. Hover your cursor over a variable to see a preview of it.', 'humanstxt');

    $more = '<p><strong>'. /* translators: DO NOT TRANSLATE! */ __('For more information:').'</strong></p>
		<p><a href="http://humanstxt.org/" rel="external">'.__('Humans TXT Website', 'humanstxt').'</a></p>
		<p><a href="http://wordpress.org/extend/plugins/humanstxt/" rel="external">'.__('Plugin Homepage', 'humanstxt').'</a></p>
		<p><a href="http://wordpress.org/tags/humanstxt" rel="external">'.__('Plugin Support Forum', 'humanstxt').'</a></p>
	';

    $screen = get_current_screen();

    if (humanstxt_is_wp('3.3')) {
        $variables = '<p>'.$variables.'</p>';
        $screen->add_help_tab(array('id' => 'help-humanstxt-file', 'title' => __('Humans TXT File', 'humanstxt'), 'content' => $humanstxt));
        $screen->add_help_tab(array('id' => 'help-humanstxt-vars', 'title' => __('Variables', 'humanstxt'), 'content' => $variables));
        $screen->set_help_sidebar($more);
    } else {
        $variables = sprintf('<p><strong>%s</strong> &mdash; %s</p>', __('Variables', 'humanstxt'), $variables);
        add_contextual_help($screen->id, $humanstxt.$variables.$more);
    }
}

/**
 * Updates the current content of the humans.txt file and
 * adds it as a new revision, if the current content doesn't
 * equal the given $content.
 *
 * @since 1.2.0
 *
 * @param string $content New content of the humans.txt file
 */
function humanstxt_update_content($content)
{
    if ($content != humanstxt_content()) {
        humanstxt_add_revision($content);
        update_option('humanstxt_content', $content);
    }
}

/**
 * Updates the plugin options and redirects to plugin options page.
 *
 * @global $humanstxt_options
 */
function humanstxt_update_options()
{
    global $humanstxt_options;

    // only update the admin-only options if current user is an admin
    if (current_user_can('administrator')) {
        $humanstxt_options['enabled'] = isset($_POST['humanstxt_enable']);
        $humanstxt_options['authortag'] = isset($_POST['humanstxt_authortag']);

        $humanstxt_options['roles'] = array();
        if (isset($_POST['humanstxt_roles']) && is_array($_POST['humanstxt_roles'])) {
            $humanstxt_options['roles'] = array_keys($_POST['humanstxt_roles']);
        }

        update_option('humanstxt_options', $humanstxt_options);
    }

    if (isset($_POST['humanstxt_content'])) {
        humanstxt_update_content(humanstxt_content_normalize(stripslashes($_POST['humanstxt_content'])));
    }

    wp_redirect(add_query_arg(array('settings-updated' => '1'), HUMANSTXT_OPTIONS_URL));
    exit;
}

/**
 * Restores the given $revision of the humans.txt, if revisions
 * aren't disabled. Redirects to the plugin options page afterwards.
 *
 * @since 1.1.0
 *
 * @param int $revision Revisons number (key)
 */
function humanstxt_restore_revision($revision)
{
    $revisions = humanstxt_revisions();

    if (!isset($revisions[$revision])) {
        return;
    }

    humanstxt_update_content($revisions[$revision]['content']);

    wp_redirect(add_query_arg(array('revision-restored' => '1'), HUMANSTXT_OPTIONS_URL));
    exit;
}

/**
 * This function tries to import the contents of the physical
 * humans.txt file and if successful rename it to humans.txt-{time}.bak,
 * so this plugin can work properly. Redirects to the plugin
 * options page afterwards.
 *
 * @since 1.2.0
 *
 * @global $wp_filesystem
 */
function humanstxt_import_file()
{
    global $wp_filesystem;

    $import = true;
    $file = ABSPATH.'humans.txt';

    if (!current_user_can('administrator')) {
        wp_die( /* translators: DO NOT TRANSLATE!! */ __('Cheatin&#8217; uh?'));
    }

    // don't bother requesting filesystem credentials
    if (get_filesystem_method() == 'direct') {
        if (!WP_Filesystem()) {
            $import = false;
        }

        if (!humanstxt_exists()) {
            $import = false;
        }

        if (!is_readable($file)) {
            $import = false;
        }

        if (($contents = $wp_filesystem->get_contents($file)) === false) {
            $import = false;
        }

        if (!preg_match('~\S~', $contents)) { // only white-space?
            $import = false;
        }

        if ($import) {

            // import content
            humanstxt_update_content(humanstxt_content_normalize($contents));

            // backup file, delete original
            if ($wp_filesystem->is_writable($file)) {
                $wp_filesystem->move($file, $file.'-'.time().'.bak', true);
            }

            if (humanstxt_exists()) {
                wp_redirect(add_query_arg(array('rename-failed' => '1'), HUMANSTXT_OPTIONS_URL));
            } else {
                wp_redirect(add_query_arg(array('file-imported' => '1'), HUMANSTXT_OPTIONS_URL));
            }

            exit;
        }
    }

    wp_redirect(add_query_arg(array('import-failed' => '1'), HUMANSTXT_OPTIONS_URL));
    exit;
}

/**
 * Callback function for 'after_plugin_row_{$plugin_file}' action.
 * Prints a warning message which suggests to deactivate other
 * humans.txt plugins to avoid conflicts.
 *
 * @param string $plugin_file WordPress plugin path
 * @param string $plugin_data Plugin informations
 * @param string $status Plugin context: mustuse, dropins, etc.
 */
function humanstxt_plugin_notice($plugin_file, $plugin_data, $status)
{
    if (is_plugin_active($plugin_file)) {
        echo '<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange"><div class="update-message">'.sprintf(__('Humans TXT includes the functionality of %1$s. Please deactivate %1$s to avoid plugin conflicts.', 'humanstxt'), '<em>'.$plugin_data['Name'].'</em>').'</div></td></tr>';
    }
}

/**
 * Returns an array with plugin rating and total votes from WordPress.org.
 *
 * @return array|false Plugin rating and total votes.
 */
function humanstxt_rating()
{
    $api = get_transient('humanstxt_plugin_information');

    // update cache?
    if ($api === false) {
        require_once ABSPATH.'wp-admin/includes/plugin-install.php';
        $api = plugins_api('plugin_information', array('slug' => 'humanstxt'));

        if (!is_wp_error($api)) {
            set_transient('humanstxt_plugin_information', $api, 60 * 10);
        }
    }

    // return plugin rating when available
    if (!is_wp_error($api) && isset($api->rating, $api->num_ratings)) {
        return array('rating' => $api->rating, 'votes' => $api->num_ratings);
    }

    return false;
}

/**
 * Callback function of 'wp_ajax_humanstxt-preview' action.
 * Shows a preview of the humans.txt file.
 *
 * @since 1.2.0
 */
function humanstxt_ajax_preview()
{
    if (isset($_GET['content']) && !empty($_GET['content'])) {
        echo '<pre>'.esc_html(apply_filters('humans_txt', $_GET['content'])).'</pre>';
    } else {
        echo /* translators: DO NOT TRANSLATE! */ __('An error has occurred. Please reload the page and try again.');
    }

    exit;
}

/**
 * Callback function registered with add_options_page().
 * Prints the requested page (options or revisions).
 */
function humanstxt_options()
{

    // show revisions page and are they activated?
    if (isset($_GET['subpage']) && $_GET['subpage'] == 'revisions' && humanstxt_revisions() !== false) {
        humanstxt_revisions_page();
    } else {
        humanstxt_options_page();
    }
}

/**
 * Prints the plugin options page.
 * @since 1.1.0
 */
function humanstxt_options_page()
{
    ?>
<div id="humanstxt" class="wrap<?php if (!humanstxt_is_wp('3.4')) : ?> not-wp34<?php endif; ?><?php if (!humanstxt_is_wp('3.2')) : ?> not-wp32<?php endif; ?>">

	<h1><?php _e('Humans TXT', 'humanstxt') ?></h1>

	<?php $faqlink = sprintf('<a href="%s">%s</a>', 'http://wordpress.org/extend/plugins/humanstxt/faq/', __('Please read the FAQ...', 'humanstxt')) ?>

	<?php if (isset($_GET['settings-updated'])) : ?>
		<div class="updated"><p><strong><?php /* translators: DO NOT TRANSLATE! */ _e('Settings saved.') ?></strong></p></div>
	<?php elseif (isset($_GET['revision-restored'])) : ?>
		<div class="updated"><p><strong><?php _e('Revision restored.', 'humanstxt') ?></strong></p></div>
	<?php elseif (isset($_GET['file-imported'])) : ?>
		<div class="updated"><p><strong><?php _e('Import successful. A backup of the original file has been created.', 'humanstxt') ?></strong></p></div>
	<?php elseif (isset($_GET['rename-failed'])) : ?>
		<div class="error"><p><strong><?php _e('Error: The content has been imported, but the original file could not be renamed.', 'humanstxt') ?></strong> <?php echo $faqlink ?></p></div>
	<?php elseif (isset($_GET['import-failed'])) : ?>
		<div class="error"><p><strong><?php _e('Error: Import failed.', 'humanstxt') ?></strong> <?php echo $faqlink ?></p></div>
	<?php endif; ?>

	<?php if (humanstxt_exists() && !isset($_GET['rename-failed'], $_GET['import-failed'])) : ?>
		<div class="error">
			<p>
				<strong><?php _e('Error: The site root already contains a physical humans.txt file.', 'humanstxt') ?></strong>
				<?php echo $faqlink ?>
				<?php if (current_user_can('administrator')) {
        printf( /* translators: Please read the FAQ... or try to ... */ __('or try to <a href="%s">import and rename</a> the physical humans.txt file.', 'humanstxt'), wp_nonce_url(add_query_arg(array('action' => 'import-file'), HUMANSTXT_OPTIONS_URL), 'import-humanstxt-file'));
    } ?>
			</p>
		</div>
	<?php elseif (get_option('permalink_structure') == '' && current_user_can('manage_options')) : ?>
		<div class="error"><p><strong><?php printf(__('Error: Please <a href="%s">update your permalink structure</a> to something other than the default.', 'humanstxt'), admin_url('options-permalink.php')) ?></strong> <?php echo $faqlink ?></p></div>
	<?php endif; ?>

	<form method="post" action="<?php echo HUMANSTXT_OPTIONS_URL ?>">

		<?php settings_fields('humanstxt') ?>

		<?php if (current_user_can('administrator')) : ?>

			<?php if (!defined('HUMANSTXT_METABOX')) {
        define('HUMANSTXT_METABOX', true);
    } ?>
			<?php if (HUMANSTXT_METABOX && ($rating = humanstxt_rating()) !== false) : ?>
				<div id="humanstxt-metabox" class="postbox humanstxt-box">
					<p class="text-rateit"><?php printf(__('If you like this plugin, why not <a href="%s" title="%s" rel="external">recommend it to others</a> by rating it?', 'humanstxt'), 'http://wordpress.org/support/view/plugin-reviews/humanstxt', __('Rate this plugin on WordPress.org', 'humanstxt')) ?></p>
					<div class="star-holder">
						<?php if (humanstxt_is_wp('3.4')) : ?>
							<div class="star star-rating" style="width: <?php echo esc_attr($rating['rating']) ?>px"></div>
						<?php else: ?>
							<?php $starimg = humanstxt_is_wp('3.2') ? admin_url('images/gray-star.png?v=20110615') : admin_url('images/star.gif') ?>
							<div class="star star-rating" style="width: <?php echo esc_attr($rating['rating']) ?>px"></div>
							<div class="star star5"><img src="<?php echo $starimg ?>" alt="<?php /* translators: DO NOT TRANSLATE! */ _e('5 stars') ?>" /></div>
							<div class="star star4"><img src="<?php echo $starimg ?>" alt="<?php /* translators: DO NOT TRANSLATE! */ _e('4 stars') ?>" /></div>
							<div class="star star3"><img src="<?php echo $starimg ?>" alt="<?php /* translators: DO NOT TRANSLATE! */ _e('3 stars') ?>" /></div>
							<div class="star star2"><img src="<?php echo $starimg ?>" alt="<?php /* translators: DO NOT TRANSLATE! */ _e('2 stars') ?>" /></div>
							<div class="star star1"><img src="<?php echo $starimg ?>" alt="<?php /* translators: DO NOT TRANSLATE! */ _e('1 star') ?>" /></div>
						<?php endif; ?>
					</div>
					<small class="text-votes"><?php printf( /* translators: DO NOT TRANSLATE! */ _n('(based on %s rating)', '(based on %s ratings)', $rating['votes']), number_format_i18n($rating['votes'])) ?></small>
				</div>
			<?php endif; ?>

			<h3><?php /* translators: DO NOT TRANSLATE! */ _e('Settings') ?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e('Humans TXT File', 'humanstxt') ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e('Humans TXT File', 'humanstxt') ?></span></legend>
							<label for="humanstxt_enable">
								<input name="humanstxt_enable" type="checkbox" id="humanstxt_enable" value="1" <?php checked(humanstxt_option('enabled')) ?> />
								<?php _e('Activate humans.txt file', 'humanstxt') ?>
							</label>
							<br />
							<label for="humanstxt_authortag" title="<?php esc_attr_e('Adds an <link rel="author"> tag to the site\'s <head> tag pointing to the humans.txt file.', 'humanstxt') ?>">
								<input name="humanstxt_authortag" type="checkbox" id="humanstxt_authortag" value="1" <?php checked(humanstxt_option('authortag')) ?> />
								<?php _e('Add an author link tag to the site', 'humanstxt') ?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Editing Permissions', 'humanstxt') ?></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e('Editing Permissions', 'humanstxt') ?></span></legend>
							<p><?php _e('Roles that can edit the content of the humans.txt file', 'humanstxt') ?>:</p>
							<?php
                                $humanstxt_roles = humanstxt_option('roles');
    $wordpress_roles = get_editable_roles();
    unset($wordpress_roles['subscriber']); ?>
							<?php foreach ($wordpress_roles as $role => $details) : ?>
								<?php $checked = ($role == 'administrator' || in_array($role, $humanstxt_roles)) ? 'checked="checked" ' : ''; ?>
								<?php $disabled = ($role == 'administrator') ? 'disabled="disabled" ' : ''; ?>
								<label for="humanstxt_role_<?php echo $role ?>">
									<input name="humanstxt_roles[<?php echo $role ?>]" type="checkbox" id="humanstxt_role_<?php echo $role ?>" value="1" <?php echo $checked ?><?php echo $disabled ?>/>
									<?php echo translate_user_role($details['name']); ?>
								</label>
								<br />
							<?php endforeach; ?>
						</fieldset>
					</td>
				</tr>
			</table>

			<p class="submit clear">
				<input type="submit" name="submit" class="button button-primary" value="<?php /* translators: DO NOT TRANSLATE! */ esc_attr_e('Save Changes') ?>" />
				<?php if (humanstxt_option('enabled')) : ?>
					<a href="<?php echo home_url('humans.txt') ?>" rel="external" class="button"><?php _e('View Humans TXT', 'humanstxt') ?></a>
				<?php endif; ?>
			</p>

		<?php endif; ?>

		<h3><?php _e('Humans TXT File', 'humanstxt') ?></h3>

		<div id="humanstxt-editor-wrap">
			<table class="form-table">
				<tr valign="top">
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php _e('Humans TXT File', 'humanstxt') ?></span></legend>
							<span class="description"><label for="humanstxt_content"><?php _e('If you need a little help with your humans.txt, try the "Help" button at the top right of this page.', 'humanstxt') ?></label></span>
							<textarea name="humanstxt_content" rows="25" cols="80" id="humanstxt_content" class="large-text code"><?php echo esc_textarea(humanstxt_content()) ?></textarea>
						</fieldset>
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" name="submit" class="button button-primary" value="<?php /* translators: DO NOT TRANSLATE! */ esc_attr_e('Save') ?>" />
				<a href="<?php echo esc_url(admin_url('admin-ajax.php?action=humanstxt-preview')) ?>" class="button button-preview hide-if-no-js" title="<?php /* translators: DO NOT TRANSLATE! */ _e('Preview') ?>"><?php /* translators: DO NOT TRANSLATE! */ _e('Preview') ?></a>
				<?php $revisions = humanstxt_revisions() ?>
				<?php if (count($revisions) > 1) : ?>
					<a href="<?php echo esc_url(HUMANSTXT_REVISIONS_URL) ?>" class="button"><?php _e('View Revisions', 'humanstxt') ?></a>
				<?php endif; ?>
			</p>
		</div>

		<?php
            $group_names = array(
                'wordpress' => /* translators: DO NOT TRANSLATE! */ __('WordPress'),
                'server' => __('Server', 'humanstxt'),
                'addons' => __('Themes & Plugins', 'humanstxt'),
                'misc' => __('Miscellaneous', 'humanstxt')
            );
    $valid_variables = humanstxt_valid_variables();
    foreach ($valid_variables as $variable) {
        if (isset($group_names[$variable[0]])) {
            $variable_groups[$variable[0]][] = $variable;
        }
    } ?>
		<?php if (!empty($variable_groups)) : ?>
			<div id="humanstxt-vars">
				<h4><?php _e('Variables', 'humanstxt') ?></h4>
				<ul>
					<?php foreach ($variable_groups as $group => $variables) : ?>
						<li>
							<h5><?php echo $group_names[$group] ?></h5>
							<ul class="hidden">
								<?php foreach ($variables as $variable) : ?>
									<?php $preview = !isset($variable[5]) || $variable[5] ? call_user_func($variable[3]) : /* translators: Preview: Not available... */ __('Not available...', 'humanstxt') ?>
									<li title="<?php echo esc_attr(sprintf( /* translators: %s: output preview of variable */ __('Preview: %s', 'humanstxt'), $preview)) ?>">
										<code>$<?php echo $variable[2]?>$</code>
										<?php if (isset($variable[4]) && !empty($variable[4])) : ?>
											&mdash; <?php echo $variable[4] ?>
										<?php endif; ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<div class="clear"></div>

		<h3><?php _e('Shortcode Usage', 'humanstxt') ?></h3>
		<p><?php printf(__('You can use the <code>[humanstxt]</code> shortcode to display the <em>humans.txt</em> file on a page or in a post. By default, all links, email addresses and Twitter account names will be converted into clickable links and email addresses will be encoded to block spam bots. <a href="%s" rel="external">Of course you can customize it...</a>', 'humanstxt'), 'http://wordpress.org/extend/plugins/humanstxt/other_notes/#Shortcode-Usage') ?></p>

	</form>
</div>
<?php
}

/**
 * Prints the plugin options revisions page.
 * @since 1.1.0
 */
function humanstxt_revisions_page()
{
    ?>
<div id="humanstxt-revisions" class="wrap<?php if (!humanstxt_is_wp('3.2')) : ?> not-wp32<?php endif; ?>">

  <?php if (! humanstxt_is_wp('3.7')) : ?>
	   <?php screen_icon() ?>
  <?php endif; ?>

	<h1><?php _e('Humans TXT', 'humanstxt') ?>: <?php _e('Revisions') ?></h1>

	<?php
    $revisions = humanstxt_revisions();
    krsort($revisions);
    $live_revision = max(array_keys($revisions));
    $show_revision = isset($_GET['revision']) && isset($revisions[$_GET['revision']]) ? intval($_GET['revision']) : false; ?>

	<?php if ($show_revision !== false) : ?>

		<h3><?php printf( /* translators: %s: revision date */ __('Revision created on %s', 'humanstxt'), date_i18n( /* translators: DO NOT TRANSLATE! */ _x('j F, Y @ G:i:s', 'revision date format'), $revisions[$show_revision]['date'])) ?></h3>
		<pre id="revision-preview" class="postbox"><?php echo esc_html($revisions[$show_revision]['content']) ?></pre>
		<p class="submit"><a href="<?php echo wp_nonce_url(add_query_arg(array('revision' => $show_revision, 'action' => 'restore'), HUMANSTXT_OPTIONS_URL), 'restore-humanstxt_'.$show_revision) ?>" class="button-primary"><?php _e('Restore Revision', 'humanstxt') ?></a></p>

	<?php elseif (isset($_GET['action'], $_GET['left'], $_GET['right']) && $_GET['action'] == 'compare' && isset($revisions[$_GET['left']], $revisions[$_GET['right']])) : ?>

		<?php if ($_GET['left'] == $_GET['right']) : ?>
			<div class="error"><p><?php _e('You cannot compare a revision to itself.', 'humanstxt') ?></p></div>
		<?php elseif (!($diff = wp_text_diff($revisions[$_GET['left']]['content'], $revisions[$_GET['right']]['content']))) : ?>
			<div class="error"><p><?php _e('These revisions are identical.') ?></p></div>
		<?php else : ?>

			<table class="form-table ie-fixed">
				<tr>
					<th class="th-full">
						<span class="alignleft"><?php printf(__('Older: %s'), date_i18n( /* translators: DO NOT TRANSLATE! */ _x('j F, Y @ G:i:s', 'revision date format'), $revisions[$_GET['left']]['date'])) ?></span>
						<span class="alignright"><?php printf(__('Newer: %s'), date_i18n( /* translators: DO NOT TRANSLATE! */ _x('j F, Y @ G:i:s', 'revision date format'), $revisions[$_GET['right']]['date'])) ?></span>
					</th>
				</tr>
				<tr>
					<td><div class="pre"><?php echo $diff; ?></div></td>
				</tr>
			</table>

			<br class="clear" />

		<?php endif; ?>

	<?php endif; ?>

	<h3><?php /* translators: DO NOT TRANSLATE! */ _e('Revisions') ?></h3>

	<form action="<?php echo admin_url('options-general.php') ?>" method="get">

		<div class="tablenav">
			<div class="alignleft">
				<input type="submit" class="button-secondary" value="<?php /* translators: DO NOT TRANSLATE! */ esc_attr_e('Compare Revisions') ?>" />
				<input type="hidden" name="page" value="humanstxt" />
				<input type="hidden" name="subpage" value="revisions" />
				<input type="hidden" name="action" value="compare" />
			</div>
		</div>

		<br class="clear" />

		<table class="widefat" cellspacing="0" id="humanstxt-revisions">
			<col />
			<col />
			<col style="width: 33%" />
			<col style="width: 33%" />
			<col style="width: 33%" />
			<thead>
				<tr>
					<th scope="col"><?php /* translators: DO NOT TRANSLATE! */ _ex('Old', 'revisions column name'); ?></th>
					<th scope="col"><?php /* translators: DO NOT TRANSLATE! */ _ex('New', 'revisions column name'); ?></th>
					<th scope="col"><?php /* translators: DO NOT TRANSLATE! */ _ex('Date Created', 'revisions column name') ?></th>
					<th scope="col"><?php /* translators: DO NOT TRANSLATE! */ _e('Author') ?></th>
					<th scope="col" class="action-links"><?php /* translators: DO NOT TRANSLATE! */ _e('Actions') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($revisions as $key => $revision) : ?>
					<?php
                        $left = isset($_GET['left']) && isset($revisions[$_GET['left']]) ? intval($_GET['left']) : (($show_revision === false) ? $live_revision - 1 : $show_revision);
    $right = isset($_GET['right']) && isset($revisions[$_GET['right']]) ? intval($_GET['right']) : $live_revision; ?>
					<tr<?php echo ($key === $show_revision) ? ' class="displayed-revision"' : '' ?>>
						<th scope="row"><input type="radio" name="left" value="<?php echo $key ?>"<?php checked($key == $left) ?> /></th>
						<th scope="row"><input type="radio" name="right" value="<?php echo $key ?>"<?php checked($key == $right) ?> /></th>
						<td>
							<?php $date = '<a href="'.esc_url(add_query_arg(array('revision' => $key), HUMANSTXT_REVISIONS_URL)).'">'.date_i18n(_x('j F, Y @ G:i', 'revision date format'), $revision['date']).'</a>'?>
							<?php printf($key == $live_revision ? /* translators: DO NOT TRANSLATE! */ __('%1$s [Current Revision]') : '%s', $date) ?>
						</td>
						<td>
							<?php if ($revision['user'] > 0) : ?>
								<?php echo get_the_author_meta('display_name', $revision['user']); ?>
							<?php endif; ?>
						</td>
						<td class="action-links">
							<?php if ($key != $live_revision) : ?>
								<a href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('revision' => $key, 'action' => 'restore'), HUMANSTXT_OPTIONS_URL), 'restore-humanstxt_'.$key)) ?>"><?php /* translators: DO NOT TRANSLATE! */ _e('Restore') ?></a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	</form>

	<p><?php printf( /* translators: %s: number of stored revisions */ __('WordPress is storing the last %s revisions of your <em>humans.txt</em> file.', 'humanstxt'), (int) apply_filters('humanstxt_max_revisions', HUMANSTXT_MAX_REVISIONS)) ?></p>

</div>
<?php
}
