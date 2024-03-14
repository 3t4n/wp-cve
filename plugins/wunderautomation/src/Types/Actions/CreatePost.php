<?php

namespace WunderAuto\Types\Actions;

use WunderAuto\Types\Internal\Action;

/**
 * Class CreatePost
 */
class CreatePost extends BaseAction
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->title       = __('Create post ', 'wunderauto');
        $this->description = __('Create a new post', 'wunderauto');
        $this->group       = 'WordPress';

        $this->addProvidedObject(
            'newpost',
            'post',
            'The newly created post'
        );
    }

    /**
     * @param Action $config
     *
     * @return void
     */
    public function sanitizeConfig($config)
    {
        parent::sanitizeConfig($config);
        $config->sanitizeObjectProp($config->value, 'postType', 'key');
        $config->sanitizeObjectProp($config->value, 'postStatus', 'key');

        $config->sanitizeObjectProp($config->value, 'title', 'text');
        $config->sanitizeObjectProp($config->value, 'name', 'text');
        $config->sanitizeObjectProp($config->value, 'content', 'textarea');

        $config->sanitizeObjectProp($config->value, 'owner', 'text');
        $config->sanitizeObjectProp($config->value, 'commentStatus', 'key');
        $config->sanitizeObjectProp($config->value, 'pingStatus', 'key');
    }

    /**
     * @return bool
     */
    public function doAction()
    {
        $postType      = $this->getResolved('value.postType');
        $postStatus    = $this->getResolved('value.postStatus');
        $postTitle     = $this->getResolved('value.title');
        $postName      = $this->getResolved('value.name');
        $postContent   = $this->getResolved('value.content');
        $owner         = $this->getResolved('value.owner');
        $parent        = $this->getResolved('value.parent');
        $commentStatus = $this->getResolved('value.commentStatus');
        $pingStatus    = $this->getResolved('value.pingStatus');

        if (strlen($postType) == 0 || strlen($postStatus) == 0) {
            return false;
        }

        if (!is_numeric($owner)) {
            $user  = get_user_by('login', $owner);
            $owner = 0;
            if ($user) {
                $owner = $user->ID;
            }
        }

        if (strlen($postName) == 0) {
            $postName = null;
        }

        if (empty($parent)) {
            $parent = 0;
        }

        if (!is_numeric($parent)) {
            $parentPost = get_page_by_path($parent, OBJECT, $postType);
            if (is_array($parentPost)) {
                $parentPost = reset($parentPost);
            }
            if ($parentPost instanceof \WP_Post) {
                $parent = $parentPost->ID;
            }
        }

        $args   = [
            'post_author'    => (int)$owner,
            'post_content'   => $postContent,
            'post_title'     => $postTitle,
            'post_name'      => $postName,
            'post_status'    => $postStatus,
            'post_type'      => $postType,
            'post_parent'    => $parent,
            'comment_status' => $commentStatus,
            'ping_status'    => $pingStatus,
        ];
        $postId = wp_insert_post($args);

        if (!($postId instanceof \WP_Error)) {
            $newPost = get_post($postId);
        } else {
            $args    = array_merge($args, [
                'ID'     => -99,
                'filter' => 'raw',
            ]);
            $newPost = wa_wp_new_post($args);
        }

        $this->resolver->addObject('newpost', 'post', $newPost);
        return !is_wp_error($postId);
    }
}
