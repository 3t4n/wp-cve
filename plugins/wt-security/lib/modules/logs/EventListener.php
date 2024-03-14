<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}
/**
 * WebTotem event listener class for Wordpress.
 */
class WebTotemEventListener{

    /**
     * Write to the log notifying that an attempt to login into the
     * administration panel was successful.
     *
     * @param  string $title
     *   User account involved in the transaction.
     * @return void
     */
    public static function hookLoginSuccess($name = '') {
        $name = empty($name) ? __('Unknown', 'wtotem') : $name;
        $title = sprintf(__('User authentication succeeded: %s', 'wtotem'), $name);
        self::addEventLog(1, 'User authentication succeeded', $title, '', $name);
    }

    /**
     * Write to the log notifying that an attempt to login into the
     * administration panel failed.
     *
     * @param  string $name
     *   The name of the user account involved in the transaction.
     * @return void
     */
    public static function hookLoginFailure($name = '') {
        $name = empty($name) ? __('Unknown', 'wtotem') : sanitize_user($name, true);
        $title = sprintf(__('User authentication failed: %s', 'wtotem'), $name);

        self::addEventLog(2, 'User authentication failed', $title, '', $name);
    }


    /**
     * Write to the log notifying that a new user account was created.
     *
     * @param  int $id
     *   The identifier of the new user account created.
     * @return void
     */
    public static function hookUserRegister($id = 0) {
        $name = __('unknown', 'wtotem');
        $email = __('unknown', 'wtotem');
        $roles = 'none';
        $data = get_userdata($id);

        if ($data) {
            $name = $data->user_login;
            $email = $data->user_email;
            $roles = @implode(', ', $data->roles);
        }

        $title = __('User account created', 'wtotem');
        $description = sprintf( __('ID: %s; name: %s; email: %s; roles: %s', 'wtotem'),
            $id,
            $name,
            $email,
            $roles
        );
        self::addEventLog(1, 'User account created', $title, $description);
    }

    /**
     * Write to the log notifying that a user account was deleted.
     *
     * @param int      $id
     *   ID of the user to delete.
     * @param int|null $reassign
     *   ID of the user to reassign posts and links to.
     * @param WP_User  $user
     *   WP_User object of the user to delete.
     *
     * @return void
     */
    public static function hookUserDelete($id, $reassign, $user) {
        self::addEventLog(3, 'User account deleted', sprintf(__('User account deleted; ID: %d, name: %s', 'wtotem'), $id, $user->user_login));
    }

    /**
     * Write to the log notifying that a user was edited.
     * @param int $id
     *   The identifier of the edited user account
     * @param object
     *   $old_user_data Object containing user's data prior to update.
     */
    public static function hookProfileUpdate($id = 0, $old_user_data = false) {

        $data = get_userdata($id);

        $old_data = WebTotem::convertObjectToArray($old_user_data);
        $new_data = WebTotem::convertObjectToArray($data);
        $diff_data = array_diff($old_data['data'], $new_data['data']);

        $title = __('User account edited', 'wtotem');

        $description = __('User account: ','wtotem') . $old_data['data']['user_login'].'; <br>';
        $diff_roles = array_diff($old_data['roles'], $new_data['roles']);
        if($diff_roles){
            $description .= 'New roles: ' . @implode(', ', $new_data['roles']) . ', ';
            $description .= 'Old roles: ' . @implode(', ', $old_data['roles']) . '; <br> ';
        }

        foreach ($diff_data as $key => $value){
            if(in_array($key, ['user_activation_key', 'user_pass']) ){
                $description .= $key .': '. __('has been changed','wtotem').' ; <br>';
                continue;
            }

            $value = is_array($value) ?  @implode(', ', $value) : $value;
            $value = $value ?: 'none';
            $new_value = $new_data['data'][$key] ?: 'none';

            $description .= 'New ' . $key .': ' . $new_value .', ';
            $description .= 'Old ' . $key .': ' . $value .'; <br>';
        }

        self::addEventLog(1, 'User account edited', $title, $description, $old_data['data']['user_login']);

    }

    /**
     * Detects usage of the password reset form.
     *
     * @return void
     */
    public static function hookLoginFormResetpass($name = '') {
        $name = empty($name) ? 'unknown' : $name;
        self::addEventLog(2,'Attempt to reset password', sprintf(__('Attempt to reset password: %s', 'wtotem'), $name));
    }

    /**
     * Write to the log notifying that an attempt to retrieve the password
     * of an user account was tried.
     *
     * @param  string $name
     *   The name of the user account involved in the transaction.
     *
     * @return void
     */
    public static function hookRetrievePassword($name = '') {
        $name = empty($name) ? 'unknown' : $name;
        self::addEventLog(3, 'Password retrieval attempt', sprintf(__('Password retrieval attempt: %s', 'wtotem'), $name),'', $name);
    }

    /**
     * Send and alert notifying that a user was added to a blog.
     *
     * @param int $user_id
     *   User ID.
     * @param string $role
     *   User role.
     * @param int $blog_id
     *   Blog ID.
     */
    public static function hookAddUserToBlog($user_id, $role, $blog_id) {

        $name = __('unknown', 'wtotem');
        $data = get_userdata($user_id);

        if ($data) {
            $name = $data->user_login;
        }

        $event = 'User added to website';
        $title = __('User added to website', 'wtotem');
        $description = sprintf( __('blog: %s; name: %s;', 'wtotem'),
            str_replace(['http://', 'https://'], '', get_home_url($blog_id)),
            $name
        );

        self::addEventLog(2, $event, $title, $description);
    }

    /**
     * Send and alert notifying that a user was removed from a blog.
     *
     * @param int $user_id
     *   User ID.
     * @param int $blog_id
     *   Blog ID.
     */
    public static function hookRemoveUserFromBlog($user_id, $blog_id) {
        $name = __('unknown', 'wtotem');
        $data = get_userdata($user_id);

        if ($data) {
            $name = $data->user_login;
        }

        $event = 'User removed from website';
        $title = __('User removed from website', 'wtotem');
        $description = sprintf( __('blog: %s; name: %s;', 'wtotem'),
            str_replace(['http://', 'https://'], '', get_home_url($blog_id)),
            $name
        );

        self::addEventLog(3, $event, $title, $description);
    }

    /**
     * Write to the log notifying that a new link was added to the bookmarks.
     *
     * @param  int $id
     *    Identifier of the new link created;
     *
     * @return void
     */
    public static function hookLinkAdd($id = 0) {
        $name = __('unknown', 'wtotem');
        $target = '_none';
        $url = 'undefined/url';
        $data = get_bookmark($id);

        if ($data) {
            $name = $data->link_name;
            $target = $data->link_target;
            $url = $data->link_url;
        }

        $event = 'Bookmark link added';
        $title = sprintf(__('Bookmark link added; ID: %s; name: %s; url: %s; target: %s', 'wtotem'), $id, $name, $url, $target);

        self::addEventLog(1, $event, $title);
    }

    /**
     * Write to the log notifying that a new link was added to the bookmarks.
     *
     * @param  int $id
     *   Identifier of the new link created;
     *
     * @return void
     */
    public static function hookLinkEdit($id = 0) {
        $name = __('unknown', 'wtotem');
        $target = '_none';
        $url = 'undefined/url';
        $data = get_bookmark($id);

        if ($data) {
            $name = $data->link_name;
            $target = $data->link_target;
            $url = $data->link_url;
        }
        $event = 'Bookmark link edited';
        $title  = sprintf(__('Bookmark link edited; ID: %s; name: %s; url: %s; target: %s', 'wtotem'), $id, $name, $url, $target);

        self::addEventLog(2, $event, $title);
    }

    /**
     * Write to the log notifying that a category was created.
     *
     * @param  int $id
     *   The identifier of the category created.
     *
     * @return void
     */
    public static function hookCategoryCreate($id = 0){
        $name = ( is_int($id) ? get_cat_name($id) : __('Unknown', 'wtotem') );

        $event = 'Category created';
        $title = sprintf(__('Category created; ID: %s; name: %s', 'wtotem'), $id, $name);

        self::addEventLog(1, $event, $title);
    }

    /**
     * Detects when a post is created or updated.
     *
     * @param  int $id
     *   The identifier of the post or page published.
     *
     * @return void
     */
    public static function hookPublishPost($id = 0) {
        self::hookPublish($id);
    }

    /**
     * Write to the log notifying that a post or page is created or updated.
     *
     * @param  int $id
     *   The identifier of the post or page published.
     *
     * @return void
     */
    private static function hookPublish($id = 0) {
        $name = __('Unknown', 'wtotem');
        $p_type = __('Publication', 'wtotem');
        $_action = __('published', 'wtotem');
        $action = 'published';
        $data = get_post($id);
        $status = 2;

        if ($data) {
            $name = WebTotem::escape($data->post_title);
            $p_type = WebTotem::escape(ucwords($data->post_type));
            $_action = __('updated', 'wtotem');
            $action = 'updated';

            /* new records have the same creation and modification dates */
            if ($data->post_date === $data->post_modified) {
                $_action = __('published', 'wtotem');
                $action = 'published';
                $status = 1;
            }

        }
        $event = sprintf('Publication was %s', $action);
        $title = sprintf(__('%s was %s; ID: %s; name: %s', 'wtotem'), $p_type, $_action, intval($id), $name);

        self::addEventLog($status, $event, $title);
    }

    /**
     * Sends an alert for transitions between post statuses.
     *
     * @param string $new
     *   New post status.
     * @param string $old
     *   Old post status.
     * @param mixed $post
     *   Post data.
     *
     * @return bool
     *
     * @throws Exception
     */
    public static function hookPostStatus($new = '', $old = '', $post = null) {
        if (!property_exists($post, 'ID')) {
            return WebTotem::throwException('Ignore corrupted post data');
        }

        /* ignore; the same */
        if ($old === $new) {
            return WebTotem::throwException('Skip events for equal transitions');
        }

        $post_type = 'post'; /* either post or page */

        if (property_exists($post, 'post_type')) {
            $post_type = $post->post_type;
        }

        $pieces = [];
        $post_type = ucwords($post_type);

        $pieces[] = sprintf(__('ID: %s', 'wtotem'), WebTotem::escape($post->ID));
        $pieces[] = sprintf(__('Old status: %s', 'wtotem'), WebTotem::escape($old));
        $pieces[] = sprintf(__('New status: %s', 'wtotem'), WebTotem::escape($new));

        if (property_exists($post, 'post_title')) {
            $pieces[] = sprintf(__('Title: %s', 'wtotem'), WebTotem::escape($post->post_title));
        }

        $event = 'Post status has been changed';
        $title = sprintf(__('%s status has been changed', 'wtotem'), WebTotem::escape($post_type));

        $description = "Details:\x20";
        $description .= implode(',', $pieces);

        self::addEventLog(2, $event, $title, $description);
    }

    /**
     * Detects when a post is created or updated via XML-RPC.
     *
     * @param  int $id The identifier of the post or page published.
     * @return void
     */
    public static function hookPublishPostXMLRPC($id = 0) {
        self::hookPublish($id);
    }

    /**
     * Write to the log notifying that a post was deleted.
     *
     * @param  int $id
     *   The identifier of the post deleted.
     * @return void
     */
    public static function hookPostDelete($id = 0) {
        $pieces = [];
        $data = WebTotemCache::getdata('post_' . $id, 'post_delete');
        $data = $data['data'] ?? ['id' => $id];

        foreach ($data as $keyname => $value) {
            $pieces[] = sprintf('Post %s: %s', $keyname, $value);
        }

        WebTotemCache::deleteData('post_' . $id, 'post_delete');

        $event = 'Post deleted';
        $title = __('Post deleted', 'wtotem');
        $description = implode(', ', $pieces);

        self::addEventLog(3, $event, $title, $description);
    }

    /**
     *
     * @param  int $id
     *   The identifier of the post deleted.
     *
     * @return void
     */
    public static function hookPostBeforeDelete($id = 0) {
        $post = get_post($id);

        if (!$post) { return; }

        $data['id'] = $post->ID;
        $data['author'] = $post->post_author;
        $data['type'] = $post->post_type;
        $data['status'] = $post->post_status;
        $data['inserted'] = $post->post_date;
        $data['modified'] = $post->post_modified;
        $data['guid'] = $post->guid;
        $data['title'] = empty($post->post_title) ? '(empty)' : $post->post_title;

        WebTotemCache::setData(['post_' . $id => $data], 'post_delete');
    }


    /**
     * Write to the log notifying that a post was moved to the trash.
     *
     * @param  int $id
     *    The identifier of the trashed post.
     *
     * @return void
     */
    public static function hookPostTrash($id = 0) {
        $name = __('Unknown', 'wtotem');
        $status = 'none';
        $data = get_post($id);

        if ($data) {
            $name = $data->post_title;
            $status = $data->post_status;
        }

        $event = 'Post moved to trash';
        $title = sprintf(__('Post moved to trash; ID: %s; name: %s; status: %s', 'wtotem'), $id, $name, $status);

        self::addEventLog(2, $event, $title);
    }

    /**
     * Detects when a page is created or updated.
     *
     * @param  int $id
     *    The identifier of the post or page published.
     *
     * @return void
     */
    public static function hookPublishPage($id = 0) {
        self::hookPublish($id);
    }

    /**
     * Write to the log that an attachment was added to a post.
     *
     * @param  int $id
     *    The post identifier.
     *
     * @return void
     */
    public static function hookAttachmentAdd($id = 0) {
        $name = 'unknown';
        $mime_type = 'unknown';
        $data = get_post($id);

        if ($data) {
            $id = $data->ID;
            $name = $data->post_title;
            $mime_type = $data->post_mime_type;
        }

        $event = 'Media file added';
        $title = sprintf(__('Media file added; ID: %s; name: %s; type: %s', 'wtotem'), $id, $name, $mime_type);

        self::addEventLog(1, $event, $title);
    }

    /**
     * Sends an alert with information about a plugin that has been activated.
     *
     * @param  string $plugin
     *   Name of the plugin.
     * @param  string $network_activation
     *   Whether the activation was global or not.
     *
     * @return void
     */
    public static function hookPluginActivate($plugin = '', $network_activation = '') {
        self::hookPluginChanges('activated', __('activated', 'wtotem'), 1, $plugin, $network_activation);
    }

    /**
     * Sends an alert with information about a plugin that has been deactivated.
     *
     * @param  string $plugin
     *   Name of the plugin.
     * @param  string $network_activation
     *   Whether the deactivation was global or not.
     *
     * @return void
     */
    public static function hookPluginDeactivate($plugin = '', $network_activation = '') {
        self::hookPluginChanges('deactivated', __('deactivated', 'wtotem'),2, $plugin, $network_activation);
    }

    /**
     * Detects whether a plugin has been activated or deactivated.
     *
     * @param  string $action
     *   Activated or deactivated.
     * @param  string $plugin
     *   Short name of the plugin file.
     * @param  string $network
     *   Whether the action is global or not.
     *
     * @return void
     */
    private static function hookPluginChanges($action, $_action, $status, $plugin = '', $network = '')
    {
        $filename = WP_PLUGIN_DIR . '/' . $plugin;

        if (!file_exists($filename)) {
            return;
        }

        $info = get_plugin_data($filename);
        $name = __('Unknown', 'wtotem');
        $version = '0.0.0';

        if (!empty($info['Name'])) {
            $name = WebTotem::escape($info['Name']);
        }

        if (!empty($info['Version'])) {
            $version = WebTotem::escape($info['Version']);
        }

        $event = 'Plugin ' . $action;
        $title = sprintf(__('Plugin %s', 'wtotem'), $action);
        $description = sprintf(
            '%s (v%s; %s%s)',
            $name,
            $version,
            WebTotem::escape($plugin),
            ($network ? '; network' : '')
        );

        self::addEventLog($status, $event, $title, $description);
    }

    /**
     * Write to the log notifying that the theme of the site was changed.
     *
     * @param  string $name
     *    The name of the new theme selected to used through out the site.
     *
     * @return void
     */
    public static function hookThemeSwitch($name = '') {
        $name = empty($name) ? 'unknown' : $name;

        $event = 'Theme activated';
        $title = sprintf(__('Theme activated: %s', 'wtotem'), $name);

        self::addEventLog(2, $event, $title);
    }

    /**
     * Detects when the core files are updated.
     *
     * @return void
     */
    public static function hookCoreUpdate() {
        // WordPress update request.
        if (current_user_can('update_core')
            && @preg_match('/^(do-core-upgrade|do-core-reinstall)$/', WebTotemRequest::get('action'))
            && WebTotemRequest::post('upgrade')
        ) {
            $title = sprintf(__('WordPress updated to version: %s', 'wtotem'), WebTotemRequest::post('version'));
            self::addEventLog(1, 'WordPress updated to version', $title);
        }
    }

    /**
     * Detects changes in the website settings.
     *
     * @return void
     */
    public static function hookOptionsManagement() {
        /* detect any Wordpress settings modification */
        if (current_user_can('manage_options') && WebTotemOption::checkOptionsNonce()) {
            /* compare settings in the database with the modified ones */
            $options_changed = WebTotemOption::whatOptionsWereChanged($_POST);
            $options_changed_str = '';
            $options_changed_count = 0;

            /* determine which options were modified */
            foreach ($options_changed['original'] as $option_name => $option_value) {
                $options_changed_count += 1;
                $options_changed_str .= sprintf(
                    __("The value of the option <b>%s</b> was changed from <b>'%s'</b> to <b>'%s'</b>.<br>\n", 'wtotem'),
                    WebTotem::escape($option_name),
                    WebTotem::escape($option_value),
                    WebTotem::escape($options_changed['changed'][ $option_name ])
                );
            }

            /* identify the origin of the request */
            $option_page = $_POST['option_page'] ?? 'options';
            $page_referer = __('Common', 'wtotem');

            switch ($option_page) {
                case 'options':
                    $page_referer = __('Global', 'wtotem');
                    break;

                case 'discussion':
                case 'general':
                case 'media':
                case 'permalink':
                case 'reading':
                case 'writing':
                    $page_referer = ucwords($option_page);
                    break;
            }

            if ($options_changed_count) {
                $description = sprintf(__('%s settings changed', 'wtotem'), $page_referer) . "<br>\n" . $options_changed_str;
                $event = 'Settings changed';
                $title = __('Settings changed', 'wtotem');
                self::addEventLog(2, $event, $title, $description);
            }
        }
    }

    /**
     * Detects when a theme is automatically or manually updated.
     *
     * @return void
     */
    public static function hookThemeUpdate() {
        // Theme update request.
        if (current_user_can('update_themes')
            && @preg_match('/^(upgrade-theme|do-theme-upgrade|update-theme)$/', WebTotemRequest::post('action'))
        ) {

            if(WebTotemRequest::get('slug')){
                $themes[] = WebTotemRequest::post('slug');
            } else {
                $themes = WebTotemRequest::post('checked');
            }
            $items_affected = [];

            foreach ((array) $themes as $theme) {
                $theme_info = wp_get_theme($theme);
                $theme_name = ucwords($theme);
                $theme_version = '0.0';

                if ($theme_info->exists()) {
                    $theme_name = $theme_info->get('Name');
                    $theme_version = $theme_info->get('Version');
                }

                $items_affected[] = sprintf(
                    '%s (v%s; %s)',
                    WebTotem::escape($theme_name),
                    WebTotem::escape($theme_version),
                    WebTotem::escape($theme)
                );
            }

            // Report updated themes at once.
            if (is_array($items_affected) && !empty($items_affected)) {
                if (count($items_affected) > 1) {
                    $title = __('Themes updated: (multiple entries): ', 'wtotem');
                } else {
                    $title = __('Theme updated:', 'wtotem');
                }

                $event = 'Themes updated';
                $description = "\x20" . implode(', <br>', $items_affected);

                self::addEventLog(2, $event, $title, $description);
            }
        }
    }

    /**
     * Detects when the theme editor is used.
     *
     * @return void
     */
    public static function hookThemeEditor() {
        // Theme editor request.
        if (current_user_can('edit_themes')
            && @preg_match('/^(update|edit-theme-plugin-file)$/', WebTotemRequest::getOrPost('action'))
            && !empty(WebTotemRequest::getOrPost('file'))
            && ( strpos(WebTotemRequest::getOrPost('_wp_http_referer'), 'theme-editor.php') !== false
            || strpos($_SERVER['SCRIPT_NAME'], 'theme-editor.php') !== false )
        ) {
            $theme_name = WebTotemRequest::getOrPost('theme');
            $filename = WebTotemRequest::getOrPost('file');
            $event = 'Theme editor used';
            $title = sprintf(__('Theme editor used in: %s/%s', 'wtotem'), WebTotem::escape($theme_name), WebTotem::escape($filename));

            self::addEventLog(2, $event, $title);
        }
    }

    /**
     * Detects when a theme is installed.
     *
     * @return void
     */
    public static function hookThemeInstall() {
        // Theme installation request.
        if (current_user_can('install_themes')
            && @preg_match('/^install-theme$/', WebTotemRequest::getOrPost('action'))
        ) {
            $theme = WebTotemRequest::get('theme') ?: WebTotemRequest::post('slug');

            $theme = $theme ?: __('Unknown', 'wtotem');

            $event = 'Theme installed';
            $title = sprintf(__('Theme installed: %s', 'wtotem'), WebTotem::escape($theme));

            self::addEventLog(1, $event, $title);
        }
    }


    /**
     * Detects when a theme is deleted.
     *
     * @return void
     */
    public static function hookThemeDelete() {

        // Theme deletion request.
        if (current_user_can('delete_themes')
            && (( WebTotemRequest::getOrPost('action') == 'delete'
            && WebTotemRequest::getOrPost('stylesheet'))
            || WebTotemRequest::post('action') == 'delete-theme' )
        ) {
            $theme = WebTotemRequest::getOrPost('stylesheet');

            if(!$theme){
                $theme = WebTotemRequest::post('slug');
                $theme_info = wp_get_theme($theme);
                $theme = ucwords($theme);

                if ($theme_info->exists()) {
                    $theme = $theme_info->get('Name');
                }
            }

            $theme = $theme ?: __('Unknown', 'wtotem');

            $event = 'Theme deleted';
            $title = sprintf(__('Theme deleted: %s', 'wtotem'), WebTotem::escape($theme));

            self::addEventLog(3, $event, $title);
        }
    }

    /**
     * Detects when a plugin is deleted.
     *
     * @return void
     */
    public static function hookPluginDelete() {
        // Plugin deletion request.

        if (current_user_can('delete_plugins')
            && ((WebTotemRequest::getOrPost('action') == 'delete-selected' && WebTotemRequest::post('verify-delete') == 1)
            || WebTotemRequest::getOrPost('action') == 'delete-plugin')
        ) {
            $plugin_list = WebTotemRequest::getOrPost('checked');
            if(!$plugin_list){
                $plugin_list[] = WebTotemRequest::getOrPost('plugin');
            }
            $items_affected = [];

            foreach ((array) $plugin_list as $plugin) {
                $plugin_path = WP_PLUGIN_DIR . '/' . $plugin;

                if (!file_exists($plugin_path)) {
                    continue;
                }

                $plugin_info = get_plugin_data($plugin_path);

                if (!empty($plugin_info['Name'])
                    && !empty($plugin_info['Version'])
                ) {
                    $items_affected[] = sprintf(
                        '%s (v%s; %s)',
                        WebTotem::escape($plugin_info['Name']),
                        WebTotem::escape($plugin_info['Version']),
                        WebTotem::escape($plugin)
                    );
                }
            }

            // Report deleted plugins at once.
            if (!empty($items_affected)) {
                if (count($items_affected) > 1) {
                    $title = __('Plugins deleted: (multiple entries):', 'wtotem');
                } else {
                    $title = __('Plugin deleted:', 'wtotem');
                }

                $event = 'Plugins deleted';
                $description = "\x20" . @implode(',<br>', $items_affected);

                self::addEventLog(3, $event, $title, $description);
            }
        }
    }

    /**
     * Detects when the plugin editor is used.
     *
     * @return void
     */
    public static function hookPluginEditor() {

        // Plugin editor request.
        if (current_user_can('edit_plugins')
            && @preg_match('/^(update|edit-theme-plugin-file)$/', WebTotemRequest::getOrPost('action'))
            && !empty(WebTotemRequest::post('file'))
            && (strpos(WebTotemRequest::post('_wp_http_referer'), 'plugin-editor.php') !== false
            || strpos($_SERVER['SCRIPT_NAME'], 'plugin-editor.php') !== false )
        ) {
            $filename = WebTotemRequest::post('file');
            $event = 'Plugin editor used';
            $title = sprintf(__('Plugin editor used in: %s', 'wtotem'), WebTotem::escape($filename));

            self::addEventLog(2, $event, $title);
        }
    }

    /**
     * Detects when a plugin is uploaded or installed.
     *
     * @return void
     */
    public static function hookPluginInstall() {
        // Plugin installation request.

        $action = WebTotemRequest::getOrPost('action');
        if (current_user_can('install_plugins')
            && ($action == 'install-plugin' OR $action == 'upload-plugin')
        ) {
            $plugin = WebTotemRequest::getOrPost('plugin') ?: WebTotemRequest::getOrPost('slug');

            if (isset($_FILES['pluginzip']) && !$plugin) {
                $plugin = $_FILES['pluginzip']['name'];
            }

            $plugin = $plugin ?: __('Unknown', 'wtotem');
            $event = 'Plugin installed';
            $title = sprintf(__('Plugin installed: %s', 'wtotem'), WebTotem::escape($plugin));

            self::addEventLog(1, $event, $title);
        }
    }

    /**
     * Detects when a plugin is updated or upgraded.
     *
     * @return void
     */
    public static function hookPluginUpdate() {

        $update_actions = '/^(upgrade-plugin|do-plugin-upgrade|update-selected|update-plugin)$/';

        if (!current_user_can('update_plugins')) {
            return;
        }

        if (@preg_match($update_actions, WebTotemRequest::getOrPost('action'))
            || @preg_match($update_actions, WebTotemRequest::getOrPost('action2'))
        ) {

            $plugin_list = [];
            $items_affected = [];

            if (WebTotemRequest::getOrPost('plugin')) {
                $plugin_list[] = WebTotemRequest::getOrPost('plugin');
            } elseif (isset($_POST['checked'])
                && is_array($_POST['checked'])
                && !empty($_POST['checked'])
            ) {
                $plugin_list = WebTotemRequest::post('checked');
            }

            foreach ($plugin_list as $plugin) {
                $plugin_info = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);

                if (!empty($plugin_info['Name'])
                    && !empty($plugin_info['Version'])
                ) {
                    $items_affected[] = sprintf(
                        '%s (v%s; %s)',
                        WebTotem::escape($plugin_info['Name']),
                        WebTotem::escape($plugin_info['Version']),
                        WebTotem::escape($plugin)
                    );
                }
            }

            // Report updated plugins at once.
            if (!empty($items_affected)) {
                if (count($items_affected) > 1) {
                    $title = __('Plugins updated: (multiple entries):', 'wtotem');
                } else {
                    $title = __('Plugin updated:', 'wtotem');
                }
                $event = 'Plugins updated';
                $description = "\x20" . @implode(',', $items_affected);

                self::addEventLog(2, $event, $title, $description);
            }
        }
    }

    /**
     * Detects when a widget is added.
     *
     * @return void
     */
    public static function hookWidgetAdd() {
        self::hookWidgetChanges();
    }

    /**
     * Detects when a widget is deleted.
     *
     * @return void
     */
    public static function hookWidgetDelete() {
        self::hookWidgetChanges();
    }

    /**
     * Detects when a widget is added.
     *
     * @return void
     */
    private static function hookWidgetChanges() {

        // Widget addition or deletion.
        if (current_user_can('edit_theme_options')
            && WebTotemRequest::post('action') == 'save-widget'
            && WebTotemRequest::post('id_base') !== false
            && WebTotemRequest::post('sidebar') !== false
        ) {
            if (WebTotemRequest::post('delete_widget')) {
                $_action = __('deleted', 'wtotem');
                $action = 'deleted';
                $action_text = 'deleted from';
                $status = 3;
            } else {
                $_action = __('added', 'wtotem');
                $action = 'added';
                $action_text = 'added to';
                $status = 1;
            }

            $event = 'Widget ' . $action;
            $title = sprintf(__('Widget %s ', 'wtotem'), $_action);

            $description = sprintf(
                _('%s (%s) %s %s (#%d; size %dx%d)', 'wtotem'),
                WebTotemRequest::post('id_base'),
                WebTotemRequest::post('widget-id'),
                $action_text,
                WebTotemRequest::post('sidebar'),
                WebTotemRequest::post('widget_number'),
                WebTotemRequest::post('widget-width'),
                WebTotemRequest::post('widget-height')
            );

            self::addEventLog($status, $event, $title, $description);
        }
    }

    /**
     * Adds an event to the log.
     *
     * @param  int $status
     *   The importance of the event.
     * @param  string $event
     *   The explanation of the event.
     * @param  string $description
     *   Additional description.
     * @return void
     */
    private static function addEventLog($status, $event, $title, $description = '', $username = null) {
        if (!function_exists('wp_get_current_user')) {
            return;
        }

        $user = wp_get_current_user();
        $remote_ip = WebTotem::getUserIP();
        $username = $username ?: false;

        if ($user instanceof WP_User
            && !empty($user->user_login)
        ) {
            $username = sprintf("\x20%s", $user->user_login);
        }

        $status = intval($status);
        $status_name = 'notice';
        $statuses = array(
            /* 0 */ 'debug',
            /* 1 */ 'notice',
            /* 2 */ 'warning',
            /* 3 */ 'critical',
        );

        if (isset($statuses[$status])) {
            $status_name = $statuses[$status];
        }

         WebTotemDB::setData([
            'created_at' => date("Y-m-d H:i:s"),
            'user_name' => $username,
            'status' => $status_name,
            'event' => $event,
            'title' => $title,
            'description' => $description,
            'ip' => $remote_ip,
            'viewed' => 0,
        ], 'audit_logs');

    }

}
