<?php
namespace LoMa;

/**
 * Class Zippy
 * @package LoMa
 */
class ZippyCore
{
    const DATA_FILE = 'info.dat';

    /**
     * Zippy constructor.
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('admin_init', [$this, 'adminInit'], 99);
        add_action('admin_bar_menu', [$this, 'adminBarMenu'], 77);
        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('admin_head', [$this, 'adminHead']);
        add_action('admin_enqueue_scripts', [$this, 'adminEnqueueScripts']);
        add_action('admin_footer', [$this, 'adminFooter']);
        add_action('plugins_loaded', [$this, 'pluginsLoaded']);
    }

    /**
     * Retrieve a list of system post meta keys - which should not be archived
     * @since 1.0.0
     * @return array An array of post meta keys
     */
    private static function getProtectedMetaKeys()
    {
        return apply_filters('zippy-protected-meta-keys', [
            '_edit_last',
            '_edit_lock',
            '_revision-control'
        ]);
    }

    /**
     * Append a link to zip post to the posts page
     *
     * @since 1.0.0
     *
     * @param array $actions An array of action links for each post
     * @param \WP_Post $post WP_Post object for the current post
     * @return array An array of action links for each post
     */
    public function rowActions($actions, $post)
    {
        if (current_user_can('read')) {
            $url = add_query_arg(['__action' => 'zippy-zip', 'post_id' => $post->ID]);
            $actions['zippy_zip'] = '<a href="' . esc_url($url) . '">' . __('Archive (Zippy)', 'zippy') . '</a>';
        }

        return $actions;
    }

    /**
     * Bulk action to zip posts
     *
     * @since 1.1.0
     * @internal
     *
     * @param array $actions An array of the available bulk actions.
     * @return array An array of the available bulk actions.
     */
    public function bulkActionsEditPost($actions)
    {
        $actions['zippy_zip'] = __('Archive (Zippy)', 'zippy');

        return $actions;
    }

    /**
     * Process bulk action to zip posts
     *
     * @since 1.1.0
     * @internal
     *
     * @param string $redirectTo The redirect URL.
     * @param string $doAction The action being taken.
     * @param array $postIds The items to take the action on.
     * @return string The redirect URL.
     */
    public function handleBulkActionsEditPost($redirectTo, $doAction, $postIds)
    {
        if ($doAction === 'zippy_zip') {

            $path = self::zipPosts($postIds);

            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename=' . (empty($_SERVER['HTTP_HOST']) ? 'www' : $_SERVER['HTTP_HOST']) . '-' . microtime(true) . '.zip');
            header('Content-Length: ' . filesize($path));

            readfile($path);
            exit;
        }

        return $redirectTo;
    }

    /**
     * @param $content
     * @return mixed
     */
    private static function unserializeData($content)
    {
        if (!is_serialized($content)) {
            return $content;
        }

        try {

            $data = @unserialize($content, [
                'allowed_classes' => [
                    \WP_Post::class,
                    \WP_User::class,
                    \WP_Term::class,
                    \WP_Error::class,
                    \WP_Locale::class,
                    \WP_Block::class,
                    \WP_Comment::class,
                    \WP_Role::class,
                    \WP_Roles::class,
                    \WP_Site::class,
                    \WP_Taxonomy::class,
                    \WP_Widget::class,
                ]
            ]);

            if ($data instanceof \__PHP_Incomplete_Class) {
                return [];
            }

            return $data;

        } catch (\Exception $e) {
        }

        return [];
    }

    /**
     * Check for the plugin actions
     * @since 1.0.0
     * @internal
     */
    public function adminInit()
    {
        if (!current_user_can('edit_pages')) {
            return;
        }

        foreach (get_post_types() as $postType) {
            add_filter($postType . '_row_actions', [$this, 'rowActions'], 10, 2);
            add_filter('bulk_actions-edit-' . $postType, [$this, 'bulkActionsEditPost']);
            add_filter('handle_bulk_actions-edit-' . $postType, [$this, 'handleBulkActionsEditPost'], 10, 3);
        }

        if (isset($_REQUEST['__action']) && $_REQUEST['__action'] === 'zippy-zip') {

            $postId = isset($_REQUEST['post_id']) ? (int) $_REQUEST['post_id'] : 0;

            if ($postId > 0) {

                $path = self::zipPost($postId);
                $fileName = sanitize_file_name(get_post($postId)->post_title) . '.zip';

                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename=' . (empty($_SERVER['HTTP_HOST']) ? 'www' : $_SERVER['HTTP_HOST']) . '-' . $fileName);
                header('Content-Length: ' . filesize($path));

                readfile($path);
                exit;
            }

            wp_die(__('Please select at least one post to archive', 'zippy'));
        }

        if (isset($_REQUEST['__action']) && $_REQUEST['__action'] === 'zippy-unzip') {

            if (!class_exists('ZipArchive')) {
                $this->adminNotice(__('Zip functionality is not available on the server!', 'zippy'), 'error');
                return;
            }

            $options = [
                'customPostType'        => isset($_REQUEST['customPT']) ? $_REQUEST['customPT'] : '',
                'replaceExists'         => isset($_REQUEST['replaceExists']) && $_REQUEST['replaceExists'] === 'on',
                'importTaxonomies'      => $_REQUEST['taxonomies'] ?? [],
                'importOtherTaxonomies' => isset($_REQUEST['otherTaxonomies']) && $_REQUEST['otherTaxonomies'] === 'on',
                'importMedia'           => isset($_REQUEST['media']) && $_REQUEST['media'] === 'on',
                'importMeta'            => isset($_REQUEST['meta']) && $_REQUEST['meta'] === 'on'
            ];

            $files = [];

            if (isset($_FILES['zippyFile']['size']) && is_array($_FILES['zippyFile']['size'])) {
                for ($i = 0, $iMax = count($_FILES['zippyFile']['size']); $i < $iMax; $i++) {
                    $files[] = [
                        'error'    => $_FILES['zippyFile']['error'][$i],
                        'name'     => $_FILES['zippyFile']['name'][$i],
                        'size'     => $_FILES['zippyFile']['size'][$i],
                        'tmp_name' => $_FILES['zippyFile']['tmp_name'][$i],
                        'type'     => $_FILES['zippyFile']['type'][$i]
                    ];
                }
            }

            foreach ($files as $file) {

                if (empty($file['name'])) {
                    $this->adminNotice(__('Please select an archive to extract articles.', 'zippy'), 'error');
                    continue;
                }

                if (pathinfo($file['name'], PATHINFO_EXTENSION) !== 'zip') {
                    $this->adminNotice(sprintf(__('Can not unzip file %s: not zip archive.', 'zippy'), $file['name']), 'error');
                    continue;
                }

                $uploadedFile = wp_handle_upload($file, ['test_form' => false]);

                if (isset($uploadedFile['file'])) {

                    $result = self::unzipPosts($uploadedFile['file'], $options);

                    if (!empty($result['errors'])) {
                        $this->adminNotice(implode('<br />', $result['errors']), 'error');
                    }

                    $notices = [];

                    foreach ($result['posts'] as $post) {
                        $notices[] = sprintf(__('Article "%s" has been unzipped!', 'zippy'), '<a href="' . get_edit_post_link($post->ID) . '">' . $post->post_title . '</a>');
                    }

                    if (!empty($notices)) {
                        $this->adminNotice(implode('<br />', $notices), 'success');
                    }

                    unlink($uploadedFile['file']);
                }
            }
        }
    }

    /**
     * Fix slashes in the path
     *
     * @since 1.0.0
     *
     * @param string $path Path
     * @return string Fixed path
     */
    private static function fixPath($path)
    {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Archive single post
     *
     * @since 1.0.0
     *
     * @param int $postId Post Id to be archived
     * @return string Path to the archive
     */
    public static function zipPost($postId)
    {
        return self::zipPosts([$postId]);
    }

    /**
     * Archive multiple posts
     *
     * @since 1.1.0
     *
     * @param array $postIds Post Ids to be archived
     * @return string Path to the archive
     */
    public static function zipPosts($postIds)
    {
        if (!class_exists('ZipArchive')) {
            wp_die(__('Zip functionality is not available on the server!', 'zippy'));
        }

        $posts = [];

        foreach ($postIds as $postId) {

            $post = get_post($postId);

            if (!($post instanceof \WP_Post)) {
                wp_die(sprintf(__('Can not read the post with the ID %d. Process terminated.', 'zippy'), $postId));
            }

            $posts[] = $post;
        }

        $path = tempnam(sys_get_temp_dir(), 'zippy');

        if (!file_exists($path)) {
            wp_die(__('Can not create the archive', 'zippy'));
        }

        $postsData = [];

        /** @var \WP_Post $post */
        foreach ($posts as $post) {

            $data = self::getPostCompleteData($post);

            if (!$data) {
                wp_die(sprintf(__('Can not read the post with the ID %d. Process terminated.', 'zippy'), $post->ID));
            }

            $postsData[$post->ID] = $data;
        }

        $zip = new \ZipArchive();
        $res = $zip->open($path, \ZipArchive::OVERWRITE);

        if ($res !== true) {
            wp_die(sprintf(__('Can not create the archive. Error code: %s', 'zippy'), $res));
        }

        if (!$zip->addFromString(self::DATA_FILE, serialize($postsData))) {
            wp_die(__('Can not create the archive', 'zippy'));
        }

        foreach ($postsData as $data) {

            foreach ($data['attachments'] as $attachment) {

                $attachmentPath = get_attached_file($attachment->ID);

                if (is_readable($attachmentPath) && !empty($attachment->rurl)) {
                    $zip->addFile($attachmentPath, $attachment->rurl);
                }
            }

            foreach ($data['images'] as $image) {

                $image = self::fixPath($image);
                $imagePath = self::fixPath(wp_upload_dir()['basedir']) . DIRECTORY_SEPARATOR . $image;

                if (is_readable($imagePath) && !empty($image)) {
                    $zip->addFile($imagePath, str_replace('\\', '/', $image));
                }
            }
        }

        if (!$zip->close()) {
            wp_die(__('Can not close the archive', 'zippy'));
        }

        return $path;
    }

    /**
     * Retrieve a complete list of all data to be archived
     *
     * @since 1.0.0
     *
     * @param \WP_Post $post Post object
     * @return array|false Post data as array of the different figures or false on failure
     */
    private static function getPostCompleteData($post)
    {
        // get post meta
        $postMeta = get_post_custom($post->ID);
        $protectedMetaKeys = array_diff(self::getProtectedMetaKeys(), ['_post_image_id']);

        foreach ($postMeta as $key => $value) {
            if (in_array($key, $protectedMetaKeys)) {
                unset($postMeta[$key]);
            } else {
                $postMeta[$key] = is_array($value) && !empty($value) ? $value[0] : '';
            }
        }

        // get post taxonomies
        $postTaxonomies = [];
        $taxonomies = apply_filters('zippy-taxonomies', get_object_taxonomies($post->post_type), $post);

        foreach ($taxonomies as $taxonomy) {
            $postTaxonomies[$taxonomy] = wp_get_object_terms($post->ID, $taxonomy, ['orderby' => 'term_order']);
        }

        // get post attachments
        $attachments = [];

        $args = [
            'post_type'      => 'attachment',
            'post_status'    => 'any',
            'posts_per_page' => -1,
            'post_parent'    => $post->ID
        ];

        foreach (get_posts($args) as $p) {
            $attachments[$p->ID] = $p;
        }

        // get post featured image
        $thumbnailId = get_post_thumbnail_id($post->ID);

        if ($thumbnailId > 0) {
            if (isset($attachments[$thumbnailId])) {
                $attachments[$thumbnailId]->isFeaturedImage = true;
            } else {

                /** @var object $thumbnailPost */
                $thumbnailPost = get_post($thumbnailId);

                if ($thumbnailPost instanceof \WP_Post) {
                    $thumbnailPost->isFeaturedImage = true;
                    $attachments[$thumbnailId] = $thumbnailPost;
                }
            }
        }

        $imagesPath = str_replace(ABSPATH, '', wp_upload_dir()['basedir'] . '/');

        // get images from the contents
        $images = [];
        ini_set('allow_url_fopen', 'on');
        $sources = $post->post_content . $post->post_excerpt . maybe_serialize($postMeta);

        if (
            preg_match_all('/(' . str_replace('/', '\/', $imagesPath) . '\S+\.(?:jpg|png|gif|jpeg))/i', $sources, $matches)
            && count($matches) > 0
        ) {
            foreach ($matches[1] as $match) {
                if (file_exists(ABSPATH . $match) && !in_array($match, $images)) {
                    $images[] = $match;
                }
            }
        }

        // special case: ID of attachment saved in the custom field
        $postMetaAttachments = [];
        $zippyPostMetaAttachmentKeys = apply_filters('zippy-get-post-meta-attachments', [], $post); // E.g. ['key1', 'key2', 'mainMetaKey' => ['key1', 'key2']]

        foreach ($postMeta as $key => $value) {

            if (is_numeric($value) && $value > 0 && in_array($key, $zippyPostMetaAttachmentKeys)) {

                $pmPost = get_post($value);

                if (!($pmPost instanceof \WP_Post)) {
                    continue;
                }

                if (!isset($attachments[$pmPost->ID])) {
                    $attachments[$pmPost->ID] = $pmPost;
                }

                $postMetaAttachments[$key] = $value;

            } elseif (isset($zippyPostMetaAttachmentKeys[$key])) {

                $data = maybe_unserialize($value);

                if (is_array($data) && !empty($data)) {
                    $postMetaAttachments[$key] = self::getAttachmentsFromCustomFields($data, $zippyPostMetaAttachmentKeys[$key], $attachments);
                }
            }
        }

        // skip attachments from the images
        foreach ($attachments as $attachment) {
            foreach ($images as $k => $image) {
                if (strpos($attachment->guid, $image) !== false) {
                    unset($images[$k]);
                }
            }
        }

        $findAttachment = static function ($url) {

            global $wpdb;

            $id = (int) $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid LIKE '%%%s'", esc_sql($url)));

            if ($id > 0) {

                $p = get_post($id);

                if (!empty($p) && !is_wp_error($p)) {
                    return $p;
                }
            }

            return false;
        };

        // if image is the attachment - convert it to the post object
        foreach ($images as $key => $image) {
            if (($attachment = $findAttachment($image)) != false) {

                if (!isset($attachments[$attachment->ID])) {
                    $attachments[$attachment->ID] = $attachment;
                }

                unset($images[$key]);
            }
        }

        $metaKeysToCheck = array_merge(['_thumbnail_id'], self::arrayFlatten($zippyPostMetaAttachmentKeys));

        // format attachments
        foreach ($attachments as $key => $attachment) {

            $file = get_attached_file($attachment->ID);

            if (!file_exists($file)) {
                unset($attachments[$key]);
                // remove keys that have relations to attachments which not exist
                $postMeta = self::removeUnusedAttachmentsIdsFromPostMeta($key, $postMeta, $metaKeysToCheck);
                $postMetaAttachments = self::removeUnusedAttachmentsIdsFromPostMeta($key, $postMetaAttachments, $metaKeysToCheck);
                continue;
            }

            // generate relative image path
            // always use normal slashes!
            $attachments[$key]->rurl = str_replace([ABSPATH . $imagesPath, '\\'], ['', '/'], $file);

            // get attachments meta
            foreach (get_post_meta($attachment->ID) as $metaKey => $value) {

                if (!isset($attachments[$key]->meta)) {
                    $attachments[$key]->meta = [];
                }

                $attachments[$key]->meta[$metaKey] = $value;
            }
        }

        // strip uploads folder from the images path
        foreach ($images as $k => $image) {
            $images[$k] = str_replace($imagesPath, '', $image);
        }

        // get post author
        if ($post->post_author > 0 && current_user_can('edit_users')) {
            $author = get_userdata($post->post_author);

            if ($author instanceof \WP_User) {
                $post->post_author = [
                    'user_email'    => $author->data->user_email,
                    'user_login'    => $author->data->user_login,
                    'user_nicename' => $author->data->user_nicename,
                ];
            }

        } else {
            $post->post_author = [];
        }

        return apply_filters('zippy-data', [
            'post'            => $post,
            'post_meta'       => $postMeta,
            'post_taxonomies' => $postTaxonomies,
            'attachments'     => $attachments,
            'images'          => array_values($images),
            'pma'             => self::arrayFilter($postMetaAttachments),
            'site_url'        => site_url(),
            'timestamp'       => time()
        ]);
    }

    /**
     * Replace URL's in the variable
     *
     * @since 1.0.0
     *
     * @param string|array|object $content
     * @param string $convertFromUrl
     * @return string|array|object
     */
    private static function replaceURLs($content, $convertFromUrl)
    {
        if (!empty($content)) {
            if (is_array($content)) {
                foreach ($content as $key => $value) {
                    $content[$key] = self::replaceURLs($value, $convertFromUrl);
                }
            } elseif (is_object($content)) {

                $vars = get_object_vars($content);

                foreach ($vars as $key => $data) {
                    $content->{$key} = self::replaceURLs($data, $convertFromUrl);
                }
            } elseif (is_string($content)) {
                $content = str_replace($convertFromUrl, get_site_url(), $content);
            }
        }

        return $content;
    }

    /**
     * Retrieve new value for attachment
     *
     * @since 1.5.5
     *
     * @param int|array $data
     * @param array $processedAttachments
     * @return int|array
     */
    private static function getNewAttachmentsIds($data, $processedAttachments)
    {
        if (!empty($data)) {

            if (is_numeric($data) && isset($processedAttachments[$data])) {
                $data = $processedAttachments[$data];
            } elseif (is_array($data) && !empty($data)) {

                foreach ($data as $key => $value) {
                    $data[$key] = self::getNewAttachmentsIds($value, $processedAttachments);
                }
            }
        }

        return $data;
    }

    /**
     * Retrieve id of attachments saved in the custom fields
     *
     * @since 1.5.5
     *
     * @param array $postMeta
     * @param array $keys
     * @param array $attachments
     * @return array
     */
    private static function getAttachmentsFromCustomFields($postMeta, $keys, &$attachments)
    {
        if (!empty($postMeta)) {
            foreach ($postMeta as $key => $value) {
                if (is_array($value)) {
                    $postMeta[$key] = self::getAttachmentsFromCustomFields($value, $keys, $attachments);
                } else {
                    if (!in_array($key, $keys)) {
                        unset($postMeta[$key]);
                    } else {
                        if (is_numeric($value) && $value > 0) {

                            $pmPost = get_post($value);

                            if (!($pmPost instanceof \WP_Post)) {
                                $postMeta[$key] = '';
                                continue;
                            }

                            if (!isset($attachments[$pmPost->ID])) {
                                $attachments[$pmPost->ID] = $pmPost;
                            }
                        }
                    }
                }
            }
        }

        return $postMeta;
    }

    /**
     * Remove ids of attachments witch not exist from post meta
     *
     * @since 1.5.5
     *
     * @param int $search
     * @param array $array
     * @param array $keys
     * @return array
     */
    private static function removeUnusedAttachmentsIdsFromPostMeta($search, $array, $keys)
    {
        if (is_array($array) && !empty($array)) {

            foreach ($array as $key => $value) {

                if ($value == $search && in_array($key, $keys)) {
                    unset($array[$key]);
                } elseif (is_array($value) && !empty($value)) {
                    $array[$key] = self::removeUnusedAttachmentsIdsFromPostMeta($search, $value, $keys);
                } elseif (is_array($data = maybe_unserialize($value))) {
                    $array[$key] = @serialize(self::removeUnusedAttachmentsIdsFromPostMeta($search, $data, $keys));
                }
            }
        }

        return $array;
    }

    /**
     * Convert multidimensional array to single dimensional array
     *
     * @since 1.5.5
     *
     * @param array $array
     * @return array
     */
    private static function arrayFlatten($array)
    {
        if (!is_array($array)) {
            return [];
        }

        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::arrayFlatten($value));
            } else {
                $result[] = $value;
            }

            if (!is_numeric($key)) {
                $result[] = $key;
            }
        }

        return $result;
    }

    /**
     * Remove empty elements from multi/single dimensional array
     *
     * @since 1.5.5
     *
     * @param array $array
     * @return array
     */
    private static function arrayFilter($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::arrayFilter($value);
            }
        }

        return array_filter($array);
    }

    /**
     * Create terms and assign them to the post
     *
     * @since 1.0.0
     *
     * @param int $postId Post Id
     * @param array $terms Terms to be created and assigned
     * @param string $taxonomy Taxonomy name
     * @return bool
     */
    private static function createAndAssignTerms($postId, $terms, $taxonomy)
    {
        $created = false;
        $existTerms = [];

        foreach ($terms as $term) {

            // try to find term by slug
            $existTerm = get_term_by('slug', $term->slug, $taxonomy, ARRAY_N, $term->filter);

            if ($existTerm) {

                if (!is_wp_error($existTerm)) {
                    $existTerms[] = $term->slug;
                }

            } else { // if can't find - crate new term

                // be sure that parent in this project is the same as on from which we transferred post (compare parent slugs)
                if ($term->parent > 0) {

                    $parent = get_term_by('slug', $term->parent_slug, $taxonomy);

                    if (
                        is_wp_error($parent)
                        || !isset ($parent->parent)
                        || $parent->term_id !== $term->parent
                    ) {
                        $term->parent = 0;
                    }
                }

                // create term
                $newTerm = wp_insert_term($term->name, $taxonomy, [
                    'description' => $term->description,
                    'slug'        => $term->slug,
                    'parent'      => $term->parent
                ]);

                if (!is_wp_error($newTerm)) {
                    $existTerms[] = $term->slug;
                }
            }
        }

        if (!empty($existTerms) && $r = wp_set_object_terms($postId, $existTerms, $taxonomy)) {
            $created = !is_wp_error($r);
        }

        return $created;
    }

    /**
     * Unzip the posts
     *
     * @since 1.1.0
     * @global \wpdb $wpdb
     *
     * @param string $pathToArchive Path to the archive
     * @param array $options Unzip options
     * @param string $deprecated Deprecated argument
     * @return array Result array
     */
    public static function unzipPosts($pathToArchive, $options = [], $deprecated = '')
    {
        $defaultTaxonomies = ['category', 'post_tag'];

        $options = array_merge([
            'customPostType'        => '',
            'replaceExists'         => true,
            'importTaxonomies'      => $defaultTaxonomies,
            'importOtherTaxonomies' => true,
            'importMedia'           => true,
            'importMeta'            => true
        ], $options);

        global $wpdb;

        $result = ['errors' => [], 'posts' => []];
        $zip = null;
        $postRelations = [];

        try {

            set_time_limit(10 * MINUTE_IN_SECONDS);

            if (!file_exists($pathToArchive)) {
                throw new \Exception(__('File not exists', 'zippy'));
            }

            $zip = new \ZipArchive();
            $res = $zip->open($pathToArchive);

            if ($res !== true) {
                throw new \Exception(
                    sprintf(__('Can not open the archive. Error code: %s', 'zippy'), $res)
                );
            }

            $extractTo = WP_CONTENT_DIR . DIRECTORY_SEPARATOR;

            if ($zip->extractTo($extractTo, self::DATA_FILE)) {

                $content = file_get_contents($extractTo . self::DATA_FILE);

                if (file_exists($extractTo . self::DATA_FILE)) {
                    unlink($extractTo . self::DATA_FILE);
                }

            } else {
                throw new \Exception(__('Can not read the archive content', 'zippy'));
            }

            if (!$content) {
                throw new \Exception(__('File is empty', 'zippy'));
            }

            $content = self::unserializeData($content);

            if (empty($content)) {
                throw new \Exception(__('Incorrect data in the file', 'zippy'));
            }

            // back support of the version 1.0.X
            $postsData = isset($content['post']) ? [$content] : array_values($content);

            foreach ($postsData as $data) {

                /** @var \WP_Post $post */
                $post = $data['post'];

                if (empty($options['customPostType'])) {
                    $postType = post_type_exists($post->post_type) ? $post->post_type : '';
                } else {
                    $postType = $options['customPostType'];
                }

                // validate post type
                if (empty($postType)) {
                    $result['errors'][] = sprintf(__('Post type "%s" not found for the post "%s"', 'zippy'), esc_html($post->post_type), esc_html($post->post_title));
                    continue;
                }

                $archiveBaseUrl = $data['site_url'];

                // IMPORT POST ////////////////////////////////////////////////////////////////////////////////////////

                // post data
                $newPostData = [
                    'post_name'      => $post->post_name,
                    'menu_order'     => $post->menu_order,
                    'comment_status' => esc_attr($post->comment_status),
                    'post_content'   => self::replaceURLs($post->post_content, $archiveBaseUrl),
                    'post_excerpt'   => self::replaceURLs($post->post_excerpt, $archiveBaseUrl),
                    'post_mime_type' => $post->post_mime_type,
                    'post_password'  => $post->post_password,
                    'post_status'    => esc_attr($post->post_status),
                    'post_title'     => self::replaceURLs($post->post_title, $archiveBaseUrl),
                    'post_type'      => esc_attr($postType),
                    'post_date'      => esc_attr($post->post_date),
                    'guid'           => self::replaceURLs($post->guid, $archiveBaseUrl)
                ];

                $matchedPost = false;
                $related = $wpdb->get_row($wpdb->prepare("SELECT ID, post_name, guid 
                                                          FROM $wpdb->posts 
                                                          WHERE post_name = '%s' 
                                                            AND post_type = '%s' 
                                                            AND post_status NOT IN ('inherit', 'revision') 
                                                          LIMIT 1", $post->post_name, $postType));

                if ($related && !is_wp_error($related)) {

                    if ($options['replaceExists']) {

                        $matchedPost = get_post($related->ID);

                        if ($matchedPost instanceof \WP_Post) {
                            $newPostData['ID'] = $matchedPost->ID;
                            $newPostData['post_status'] = $matchedPost->post_status;
                        } else {
                            $matchedPost = false;
                        }

                    } else {

                        if ($related->post_name === $post->post_name) {
                            unset($newPostData['post_name']);
                        }

                        if ($related->guid === $post->guid) {
                            unset($newPostData['guid']);
                        }
                    }
                }

                // post author
                $authorId = 0;

                if (!empty($post->post_author)) {
                    $author = $post->post_author;
                    $user = get_user_by('email', $author['user_email'] ?? '');

                    if (!$user) {

                        $user = get_user_by('login', $author['user_login'] ?? '');

                        if (!$user) {

                            $user = get_user_by('slug', $author['user_nicename'] ?? '');

                            if (!$user) {
                                $authorId = (int) $wpdb->get_var("SELECT u.ID FROM $wpdb->users as u WHERE (SELECT um.meta_value FROM $wpdb->usermeta as um WHERE um.user_id = u.ID AND um.meta_key = 'wp_user_level') >= 8 LIMIT 1");
                            } elseif ($matchedPost) {
                                $authorId = $matchedPost->post_author;
                            } else {
                                $authorId = $user->ID;
                            }
                        } else {
                            $authorId = $user->ID;
                        }
                    } else {
                        $authorId = $user->ID;
                    }

                    if ($authorId > 0) {
                        $newPostData['post_author'] = $authorId;
                    }
                }

                // parent post
                if ($post->post_parent > 0) {
                    $newPostData['post_parent'] = in_array($post->post_parent, $postRelations)
                        ? array_search($post->post_parent, $postRelations)
                        : $post->post_parent;
                }

                // save post
                $newPostData = apply_filters('zippy-unzip-post', $newPostData, $content);

                if ($matchedPost) {
                    $postId = wp_update_post($newPostData);
                } else {
                    $postId = wp_insert_post($newPostData);
                }

                if (is_wp_error($postId) || $postId < 1) {
                    $result['errors'][] = sprintf(__('Can not unzip the post "%s"', 'zippy'), $newPostData['post_title']);
                    continue;
                }

                $postRelations[$postId] = $post->ID;

                $result['posts'][] = (object) ['ID' => $postId, 'post_title' => $newPostData['post_title']];

                // IMPORT META ////////////////////////////////////////////////////////////////////////////////////////

                if ($options['importMeta'] && !empty($data['post_meta'])) {

                    // delete old meta
                    if ($matchedPost) {

                        $protectedMetaKeys = self::getProtectedMetaKeys();

                        foreach (get_post_meta($postId) as $metaKey => $value) {
                            if (!in_array($metaKey, $protectedMetaKeys)) {
                                delete_post_meta($postId, $metaKey);
                            }
                        }
                    }

                    // insert new meta
                    $meta = apply_filters('zippy-unzip-meta', stripslashes_deep($data['post_meta']), $content);

                    foreach ($meta as $metaKey => $metaValue) {
                        update_post_meta(
                            $postId,
                            $metaKey,
                            self::replaceURLs(self::unserializeData($metaValue), $archiveBaseUrl)
                        );
                    }
                }

                // IMPORT TAXONOMIES //////////////////////////////////////////////////////////////////////////////////

                if (
                    (!empty($options['importTaxonomies']) || $options['importOtherTaxonomies'])
                    && !empty($data['post_taxonomies'])
                ) {

                    // get taxonomies
                    $taxonomies = apply_filters('zippy-unzip-taxonomies', $data['post_taxonomies'], $content);
                    $availableTaxonomies = get_object_taxonomies($postType);

                    // delete old terms
                    if ($matchedPost) {
                        wp_delete_object_term_relationships($postId, $availableTaxonomies);
                    }

                    // insert new terms
                    if (!empty($availableTaxonomies)) {
                        foreach ($taxonomies as $taxonomyName => $taxonomyData) {
                            $canBeImported = in_array($taxonomyName, $options['importTaxonomies'])
                                             || ($options['importOtherTaxonomies'] && !in_array($taxonomyName, $defaultTaxonomies));

                            if ($canBeImported && in_array($taxonomyName, $availableTaxonomies)) {
                                self::createAndAssignTerms($postId, $taxonomyData, $taxonomyName);
                            }
                        }
                    }
                }

                // IMPORT ATTACHMENTS /////////////////////////////////////////////////////////////////////////////////

                if ($options['importMedia']) {

                    $processedAttachments = [];
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'tif', 'ico', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods', 'odp', 'zip', 'rar', '7z', 'tar', 'gz', 'mp3', 'wav', 'mp4', 'avi', 'mov', 'wmv', 'mpg', 'mpeg', 'webm', 'ogg', 'ogv', 'm4v', '3gp', '3g2', 'txt', 'rtf'];

                    if (!empty($data['attachments'])) {

                        require_once ABSPATH . 'wp-admin/includes/image.php';
                        require_once ABSPATH . 'wp-admin/includes/file.php';
                        require_once ABSPATH . 'wp-admin/includes/media.php';

                        $attachments = apply_filters('zippy-unzip-attachments', $data['attachments'], $content);
                        $uploadsDir = self::fixPath(wp_upload_dir()['basedir']);

                        /** @var \WP_Post $attachment */
                        foreach ($attachments as $attachment) {

                            // extract file
                            $attachmentPath = $attachment->rurl;
                            $file = $uploadsDir . DIRECTORY_SEPARATOR . self::fixPath($attachmentPath);
                            $fileInfo = pathinfo($file);

                            if (!in_array(strtolower($fileInfo['extension']), $allowedExtensions)) {
                                continue;
                            }

                            if ($options['replaceExists'] || !file_exists($file)) {
                                $zip->extractTo($uploadsDir, $attachmentPath);
                            }

                            $newGuid = self::replaceURLs($attachment->guid, $archiveBaseUrl);

                            // try to find the attachment in the database
                            $attachmentId = 0;
                            $updateMeta = $options['replaceExists'];
                            $matchedAttachmentId = (int) $wpdb->get_var($wpdb->prepare("SELECT ID 
                                                                                        FROM $wpdb->posts 
                                                                                        WHERE post_type = 'attachment' 
                                                                                          AND post_date = '%s' 
                                                                                          AND guid = '%s'", $attachment->post_date, $newGuid));

                            if (!is_wp_error($matchedAttachmentId) && $matchedAttachmentId > 0) {
                                $attachmentId = $matchedAttachmentId;
                            } else {
                                $updateMeta = true;
                            }

                            // attachment not found. create a new one
                            if ($attachmentId === 0) {

                                $attachmentData = array_merge((array) $attachment, [
                                    'post_author' => $authorId,
                                    'post_parent' => $postId,
                                    'guid'        => $newGuid
                                ]);

                                unset(
                                    $attachmentData['ID'],
                                    $attachmentData['post_date_gmt'],
                                    $attachmentData['isFeaturedImage'],
                                    $attachmentData['comment_count']
                                );

                                // insert attachment to WP posts
                                $attachmentId = wp_insert_post($attachmentData);
                                $attachmentId = is_wp_error($attachmentId) ? 0 : (int) $attachmentId;
                            }

                            // update attachment meta
                            if ($attachmentId > 0 && $updateMeta) {
                                update_attached_file($attachmentId, $attachment->rurl);
                                wp_update_attachment_metadata(
                                    $attachmentId,
                                    wp_generate_attachment_metadata($attachmentId, get_attached_file($attachmentId))
                                );
                            }

                            // set image as featured if needed
                            if ($attachmentId > 0 && !empty($attachment->isFeaturedImage)) {
                                set_post_thumbnail($postId, $attachmentId);
                            }

                            $processedAttachments[$attachment->ID] = $attachmentId;
                        }
                    }

                    // IMPORT IMAGES /////////////////////////////////////////////////////////////////////////////////

                    if (!empty($data['images'])) {

                        $images = apply_filters('zippy-unzip-images', $data['images'], $content);

                        foreach ($images as $image) {

                            $fileInfo = pathinfo($image);

                            if (in_array(strtolower($fileInfo['extension']), $dangerousExtensions)) {
                                continue;
                            }

                            $path = self::fixPath(wp_upload_dir()['basedir']);

                            if ($options['replaceExists'] || !file_exists($path . DIRECTORY_SEPARATOR . $image)) {
                                $zip->extractTo($path, $image);
                            }
                        }
                    }

                    // UPDATE POST META THAT REPRESENT IMAGES ///////////////////////////////////////////////////////

                    if (!empty($data['pma'])) {
                        foreach ($data['pma'] as $metaKey => $metaValue) {

                            $newMetaValue = self::getNewAttachmentsIds($metaValue, $processedAttachments);

                            if (json_encode($newMetaValue) !== json_encode($metaValue)) {
                                if (is_numeric($newMetaValue)) {
                                    update_post_meta($postId, $metaKey, $newMetaValue);
                                } elseif (is_array($newMetaValue)) {

                                    $meta = get_post_meta($postId, $metaKey, true);

                                    if (is_array($meta)) {
                                        update_post_meta($postId, $metaKey, array_replace_recursive($meta, $newMetaValue));
                                    }
                                }
                            }
                        }
                    }
                }

                // FINALIZE /////////////////////////////////////////////////////////////////////////////////////

                clean_post_cache($postId);
            }

        } catch (\Exception $e) {
            $result['errors'] = [$e->getMessage()];
        }

        if ($zip !== null) {
            $zip->close();
        }

        return $result;
    }

    /**
     * Unzip the post
     *
     * @since 1.0.0
     * @deprecated use self::unzipPosts()
     *
     * @global \wpdb $wpdb
     * @param string $pathToArchive Path to the archive
     * @param bool $replaceExists Whether to replace exists post with the same name/slug. Default false.
     * @return array Result array
     */
    public static function unzipPost($pathToArchive, $replaceExists = false)
    {
        _deprecated_function(__FUNCTION__, '1.2.0', 'unzipPosts');

        return self::unzipPosts($pathToArchive, [
            'replaceExists' => $replaceExists
        ]);
    }

    /**
     * Customize the admin notice
     *
     * @since 1.0.0
     *
     * @param string|array $message Message text
     * @param string $type Message type
     */
    private function adminNotice($message, $type)
    {
        add_action('admin_notices', static function () use ($message, $type) {
            $htmlMessages = is_array($message) ? $message : [$message];
            ?>
            <div class="notice notice-<?php echo esc_attr($type); ?> is-dismissible">
                <?php foreach ($htmlMessages as $html) { ?>
                    <p><?php echo wp_kses_post($html); ?></p>
                <?php } ?>
            </div>
            <?php
        });
    }

    /**
     * Add "zippy" to the admin toolbar
     *
     * @since 1.0.0
     * @internal
     *
     * @param \WP_Admin_Bar $wp_admin_bar
     */
    public function adminBarMenu($wp_admin_bar)
    {
        if (apply_filters('zippy_show_in_admin_bar', true) && current_user_can('edit_pages')) {
            $wp_admin_bar->add_node([
                'id'    => 'zippy',
                'href'  => admin_url('#TB_inline?inlineId=zippyModal'),
                'title' => '<span class="unzippy ab-icon"></span><span class="unzippy ab-label">Zippy</span>'
            ]);
        }
    }

    /**
     * Add "zippy" to the navigation menu
     * @since 1.4.0
     * @internal
     */
    public function adminMenu()
    {
        if (apply_filters('zippy_show_in_tools_menu', true)) {
            add_management_page(
                __('Extract (Zippy)', 'zippy'),
                'Zippy',
                'edit_pages',
                'zippy',
                [$this, 'addMenu']
            );
        }
    }

    /**
     * @since XXX
     * @internal
     */
    public function addMenu()
    {
        if (!current_user_can('edit_pages')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        echo '<div class="wrap">';
        echo '<h1>' . __('Extract (Zippy)', 'zippy') . '</h1>';
        echo $this->getZippyForm();
        echo '</div>';
    }

    /**
     * Design improvements
     * @since 1.0.0
     * @internal
     */
    public function adminHead()
    {
        echo '<style type="text/css">.unzippy.ab-icon:before { content: "\f501"; top: 3px; }</style>';
    }

    /**
     * Include scripts
     * @since 1.0.0
     * @internal
     */
    public function adminEnqueueScripts()
    {
        add_thickbox();
        wp_enqueue_script('zippy', plugin_dir_url(__FILE__) . 'zippy.js', ['jquery-ui-dialog'], '1.0', true);
    }

    /**
     * @since 1.4.0
     * @return string
     */
    private function getZippyForm()
    {
        $pts = [
            '' => __('Auto-detect', 'zippy')
        ];

        foreach (get_post_types(['public' => true]) as $pt) {
            if ($pt !== 'attachment') {
                $pts[$pt] = get_post_type_object($pt)->label;
            }
        }

        $ptList = '';

        foreach ($pts as $key => $item) {
            $ptList .= '<option value="' . esc_attr($key) . '">' . esc_html($item) . '</option>';
        }

        return '<form action="" method="post" enctype="multipart/form-data" autocomplete="off">' .
               '<h3>' . __('Select zip archive to extract', 'zippy') . '</h3>' .
               '<p><input type="file" name="zippyFile[]" multiple /></p>' .
               '<p><label for="zippyCustomPT">' . __('Change post type to:', 'zippy') . '<br /><select name="customPT" id="zippyCustomPT">' . $ptList . '</select></label></p>' .
               '<div><label for="zippyRepChk"><input type="checkbox" name="replaceExists" id="zippyRepChk" checked="checked" /> ' . __('Search & override articles that have the same name or slug.', 'zippy') . '</label></div>' .
               '<div><label for="zippyCategories"><input id="zippyCategories" type="checkbox" name="taxonomies[]" value="category" checked="checked" /> ' . __('Import categories', 'zippy') . '</label></div>' .
               '<div><label for="zippyTerms"><input id="zippyTerms" type="checkbox" name="taxonomies[]" value="post_tag" checked="checked" /> ' . __('Import terms', 'zippy') . '</label></div>' .
               '<div><label for="zippyTaxonomies"><input id="zippyTaxonomies" type="checkbox" name="otherTaxonomies" checked="checked" /> ' . __('Import other taxonomies', 'zippy') . '</label></div>' .
               '<div><label for="zippyMedia"><input id="zippyMedia" type="checkbox" name="media" checked="checked" /> ' . __('Import media, attachments', 'zippy') . '</label></div>' .
               '<div><label for="zippyMeta"><input id="zippyMeta" type="checkbox" name="meta" checked="checked" /> ' . __('Import meta-data, custom fields', 'zippy') . '</label></div>' .
               '<input type="hidden" name="__action" value="zippy-unzip" />' .
               '<p><input type="submit" name="submit" class="button button-primary" value="' . __('Import', 'zippy') . '" /></p>' .
               '</form>';
    }

    /**
     * Add the dialogue to unzip the file
     * @since 1.0.0
     * @internal
     */
    public function adminFooter()
    {
        echo '<div id="zippyModal" style="display: none;">' . $this->getZippyForm() . '</div>';
    }

    /**
     * Load translations
     * @since 1.1.6
     * @internal
     */
    public function pluginsLoaded()
    {
        load_plugin_textdomain('zippy', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}