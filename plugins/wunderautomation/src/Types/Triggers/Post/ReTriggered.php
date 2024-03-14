<?php

namespace WunderAuto\Types\Triggers\Post;

use WunderAuto\Types\Triggers\BaseReTrigger;

/**
 * Class ReTriggered
 */
class ReTriggered extends BaseReTrigger
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->addProvidedObject(
            'post',
            'post',
            __('The post object', 'wunderauto')
        );

        $this->addProvidedObject(
            'user',
            'user',
            __('The owner of the post', 'wunderauto')
        );
    }

    /**
     * @param \WP_Post $post
     *
     * @return array<string, object>|false
     */
    public function getObjects($post)
    {
        if (in_array($post->ID, $this->triggeredIds)) {
            return false;
        }
        $this->triggeredIds[] = $post->ID;

        $user = get_user_by('id', $post->post_author);
        if (!($user instanceof \WP_User)) {
            $user = wa_empty_wp_user();
        }
        return $this->getResolverObjects(['post' => $post, 'user' => $user]);
    }
}
