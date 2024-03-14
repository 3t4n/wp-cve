<?php

namespace WunderAuto\Types\Triggers\Post;

use WunderAuto\Types\Triggers\BaseTrigger;
use WunderAuto\Types\Internal\WorkflowState;
use WunderAuto\Types\Workflow;

/**
 * Class Saved
 */
class Saved extends BaseTrigger
{
    /**
     * Keep track of posts ids that had post meta updated
     *
     * @var array<int, int>
     */
    private $detectedPostmetaPosts = [];

    /**
     * Keep track of if we also registered for post meta updates
     *
     * @var bool
     */
    private $registeredForPostMeta = false;

    /**
     * When we're detecting post meta changes we need to ignore certain keys
     *
     * @var array<int, string>
     */
    private $ignoredMetaKeys = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->title       = __('Post saved', 'wunderauto');
        $this->group       = __('Posts', 'wunderauto');
        $this->description = __(
            'This trigger fires when a post object (post, page, custom post type etc.) is saved. ' .
            'Note! WordPress stores a lot of data as posts which means this trigger will fire very ' .
            'often. Combine this with strict filters so that the actions only run when you expect them to. ' .
            'Also see the "Post Updated" trigger if you need to trigger on changes to post meta',
            'wunderauto'
        );

        $this->addProvidedObject(
            'post',
            'post',
            __('The post that was saved', 'wunderauto')
        );

        $this->addProvidedObject(
            'user',
            'user',
            __('The owner of the post', 'wunderauto')
        );

        $this->defaultValue = (object)[
            'detectPostMeta' => false,
        ];
    }

    /**
     * Register our hooks with WordPress
     *
     * @return void
     */
    public function registerHooks()
    {
        if (!$this->registered) {
            add_action('save_post', [$this, 'savePost'], 20, 2);
            $this->registered = true;
        }

        if (!$this->registeredForPostMeta && !is_null($this->workflow)) {
            /** @var WorkflowState $state */
            $state = $this->workflow->getState();
            if (isset($state->trigger->value->detectPostMeta) && $state->trigger->value->detectPostMeta) {
                add_action('update_post_meta', [$this, 'detectPostmetaUpdate'], 20, 4);

                $this->ignoredMetaKeys = ['_edit_lock', '_edit_last'];

                /**
                 * Tell WunderAutmation which post meta keys to ignore when detecing post meta updates
                 *
                 * @param array $ignoredMetaKeys Array of keys to ignore
                 */
                $this->ignoredMetaKeys = apply_filters(
                    'wunderauto/triggers/postsaved/ignoredmetakeys',
                    $this->ignoredMetaKeys
                );

                $this->registeredForPostMeta = true;
            }
        }
    }

    /**
     * Detect an update to postmeta
     *
     * @param int    $metaId
     * @param int    $postId
     * @param string $metaKey
     * @param mixed  $metaValue
     *
     * @return void
     */
    public function detectPostmetaUpdate($metaId, $postId, $metaKey, $metaValue)
    {
        // Bail out quickly if it's an ignored key
        if (in_array($metaKey, $this->ignoredMetaKeys)) {
            return;
        }

        $this->detectedPostmetaPosts[] = $postId;
        if (count($this->detectedPostmetaPosts) === 1) {
            add_action('shutdown', [$this, 'handleUpdatedPosts']);
        }
    }

    /**
     * Fire this trigger for any posts that had meta data updates during this
     * request.
     *
     * @return void
     */
    public function handleUpdatedPosts()
    {
        $this->detectedPostmetaPosts = array_unique($this->detectedPostmetaPosts);
        foreach ($this->detectedPostmetaPosts as $postId) {
            // If this post has already triggered, skip it
            if (in_array($postId, $this->triggeredIds)) {
                continue;
            }
            $this->triggeredIds[] = $postId;

            $post = get_post($postId);
            if (!$post instanceof \WP_Post) {
                continue;
            }

            if ($post->post_type == 'revision') {
                continue;
            }

            if ($post->post_status == 'auto-draft') {
                return;
            }

            $user = get_user_by('id', $post->post_author);
            $this->doTrigger(['post' => $post, 'user' => $user], ['postMetaDetected' => true]);
        }
    }

    /**
     * Check if a workflow should run based on the arguments supplied by this
     * trigger at runtime.
     *
     * @param Workflow             $workflow
     * @param array<string, mixed> $triggerArgs
     *
     * @return bool
     */
    public function runThisWorkflow($workflow, $triggerArgs)
    {
        // If it's a normal post save we always return true
        if (empty($triggerArgs['postMetaDetected']) || $triggerArgs['postMetaDetected'] == false) {
            return true;
        }

        /** @var WorkflowState $state */
        $state = $workflow->getState();
        if (isset($state->trigger->value->detectPostMeta) && $state->trigger->value->detectPostMeta) {
            return $triggerArgs['postMetaDetected'] == true;
        }

        return false;
    }

    /**
     * Handler for the save_post action
     *
     * @param int      $postId
     * @param \WP_Post $post
     *
     * @return void
     */
    public function savePost($postId, $post)
    {
        if ($post->post_type == 'revision') {
            return;
        }

        if ($post->post_status == 'auto-draft') {
            return;
        }

        /**
         * Try to figure out if this is the request comes from the block editor
         * and this is a the first of two requests for the same save event
         */
        $referer = wp_get_referer();
        $needle  = "/post.php?post={$post->ID}&action=edit";
        $isRest  = defined('REST_REQUEST') && REST_REQUEST;

        if (strpos((string)$referer, $needle) !== false && $isRest) {
            return;
        }

        if (in_array($post->ID, $this->triggeredIds)) {
            return;
        }

        $this->triggeredIds[] = $post->ID;
        $user                 = get_user_by('id', $post->post_author);
        $this->doTrigger(['post' => $post, 'user' => $user]);
    }
}
