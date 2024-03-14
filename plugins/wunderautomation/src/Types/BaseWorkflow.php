<?php

namespace WunderAuto\Types;

use WunderAuto\Types\Internal\ReTriggerState;
use WunderAuto\Types\Internal\WorkflowState;

/**
 * Class Composite
 */
class BaseWorkflow
{
    /**
     * @var bool
     */
    protected $readState = false;

    /**
     * @var int
     */
    protected $postId;

    /**
     * @var ReTriggerState|WorkflowState
     */
    protected $state;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $settingsKey = '';

    /**
     * @var string
     */
    protected $settingsClass = '';

    /**
     * @var string
     */
    protected $postType = '';

    /**
     * @var bool
     */
    private $updatedViaCore = false;

    /**
     * @param int|null $postId
     */
    public function __construct($postId = null)
    {
        $this->postId = (int)$postId;
    }

    /**
     * Getter for ReTrigger state
     *
     * Read and parse state from WordPress post meta
     *
     * @return WorkflowState|ReTriggerState
     */
    public function getState()
    {
        if (!$this->readState) {
            $this->readState = true;

            // Read from meta table
            $savedState = get_post_meta($this->postId, $this->settingsKey, true);
            $savedState === '' ? (object)[] : $savedState;
            $this->setState($savedState);

            $post = get_post($this->postId);
            if ($post instanceof \WP_Post) {
                $this->state->name = $post->post_title;
            }

            $active              = get_post_meta($this->postId, 'active', true);
            $this->state->active = (bool)($active === 'active');

            $guid              = get_post_meta($this->postId, 'guid', true);
            $this->state->guid = !empty($guid) ? $guid : $this->state->guid;

            if ($this->state->newGuidCreated()) {
                update_post_meta($this->postId, 'guid', $this->state->guid);
            }
        }

        return $this->state;
    }

    /**
     * Set state via a Json object
     *
     * @param \stdClass $state
     *
     * @return void
     */
    public function setState($state)
    {
        /** @var WorkflowState|ReTriggerState $class */
        $class       = $this->settingsClass;
        $this->state = new $class($state);
    }

    /**
     * Save workflow to posts and postmeta
     *
     * @return bool
     */
    public function save()
    {
        if ($this->postId === 0) {
            $postArgs = [
                'post_status' => 'publish',
                'post_title'  => $this->state->name,
                'post_type'   => $this->postType,
            ];

            $postId = wp_insert_post($postArgs, false, false);
            if ($postId instanceof \WP_Error || $postId === 0) {
                return false;
            }
            $this->postId = $postId;
        } elseif (!$this->updatedViaCore) {
            $postArgs = [
                'ID'          => $this->postId,
                'post_status' => 'publish',
                'post_title'  => $this->state->name,
            ];
            wp_update_post($postArgs, false, false);
        }

        update_post_meta($this->postId, $this->settingsKey, $this->state->toObject(true));
        update_post_meta($this->postId, 'guid', $this->state->guid);
        update_post_meta(
            $this->postId,
            'active',
            $this->state->active ? 'active' : 'disabled'
        );

        return true;
    }

    /**
     * @param \WP_Post $post
     *
     * @return void
     */
    public function parsePosted($post)
    {
        if (isset($_POST['active'])) {
            $newValue            = sanitize_key($_POST['active']) === 'active' ? 'active' : 'disabled';
            $this->state->active = $newValue === 'active';
        }

        if ($post->post_status == 'draft') {
            wp_update_post(['ID' => $post->ID, 'post_status' => 'publish']);
        }

        $this->updatedViaCore = true;
    }

    /**
     * Getter for internal ID
     *
     * @return int|null
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Getter for active property
     *
     * @return bool
     */
    public function isActive()
    {
        return isset($this->state->active) ? $this->state->active : false;
    }

    /**
     * Getter for internal version
     *
     * @return int
     */
    public function getVersion()
    {
        return isset($this->state->version) ? (int)$this->state->version : 0;
    }

    /**
     * Getter for ReTrigger name (title)
     *
     * @return string
     */
    public function getName()
    {
        return isset($this->state->name) ? $this->state->name : '';
    }

    /**
     * Retrieve json from request data
     *
     * @param string $paramName
     *
     * @return \stdClass|null
     */
    protected function getPostedJson($paramName)
    {
        $rawJson = $_POST[$paramName];
        $decoded = json_decode(stripslashes($rawJson));
        return $decoded === false ? null : $decoded;
    }
}
