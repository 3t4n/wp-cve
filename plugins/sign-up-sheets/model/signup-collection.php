<?php
/**
 * Signup Collection
 */

namespace FDSUS\Model;

use FDSUS\Id;
use FDSUS\Model\Signup as SignupModel;
use FDSUS\Model\Task as TaskModel;
use WP_Query;
use WP_Post;

if (Id::isPro()) {
    class SignupCollectionParent extends Pro\SignupCollection {}
} else {
    class SignupCollectionParent extends Base {}
}

class SignupCollection extends SignupCollectionParent
{
    /** @var WP_POST[]|SignupModel[] */
    public $posts = array();

    /** @var WP_Query */
    protected $query;

    /** @var TaskModel */
    protected $task;

    /**
     * Constructor
     *
     * @param TaskModel|int   $task
     * @param array $args for get_posts()
     *
     * @return self
     */
    public function __construct($task = null, $args = array())
    {
        if (!empty($task) && $task instanceof TaskModel) {
            $this->task = $task;
        }
        parent::__construct();

        return $this;
    }

    /**
     * Get posts and convert to custom object
     *
     * @param array          $args for get_posts()
     * @param TaskModel|null $task
     *
     * @return array|void
     */
    private function init($args, $task = null)
    {
        if (!empty($task) && $task instanceof TaskModel) {
            $this->task = $task;
        }
        $this->query = new WP_Query;
        $this->posts = $this->query->query($args);
        $this->convertToCustomObjects();
    }

    /**
     * Get by task id
     *
     * @param int|TaskModel $task
     * @param array         $args for get_posts()
     *
     * @return SignupModel[]
     */
    public function getByTask($task = 0, $args = array())
    {
        if (empty($task)) {
            return $this->posts;
        }

        $taskId = ($task instanceof TaskModel) ? $task->ID : $task;
        $taskObject = ($task instanceof TaskModel) ? $task : null;

        $defaults = array(
            'posts_per_page'   => -1,
            'post_type'        => SignupModel::POST_TYPE,
            'post_parent'      => $taskId,
            'post_status'      => 'publish',
            'suppress_filters' => true,
            'orderby'          => 'date, ID',
            'order'            => 'ASC',
        );
        $args = wp_parse_args($args, $defaults);
        $this->init($args, $taskObject);

        return $this->posts;
    }

    /**
     * Get by task id
     *
     * @param string $email
     * @param array  $args for get_posts()
     *
     * @return SignupModel[]
     */
    public function getByEmail($email = '', $args = array())
    {
        if (empty($email)) {
            return $this->posts;
        }

        $defaults = array(
            'posts_per_page'   => -1,
            'post_type'        => SignupModel::POST_TYPE,
            'post_status'      => 'publish',
            'suppress_filters' => true,
            'orderby'          => 'date, ID',
            'order'            => 'ASC',
            'meta_query' => array(
                array(
                    'key'     => 'dlssus_email',
                    'value'   => $email
                ),
            ),
        );
        $args = wp_parse_args($args, $defaults);
        $this->init($args);

        return $this->posts;
    }

    /**
     * Get signups by user
     *
     * @param int $userId
     *
     * @return SignupModel[]
     */
    public function getByUser($userId)
    {
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => SignupModel::POST_TYPE,
            'post_status'      => 'publish',
            'suppress_filters' => true,
            'meta_query'       => array(
                array(
                    'key'     => 'dlssus_user_id',
                    'value'   => (int)$userId,
                    'compare' => '='
                )

            ),
        );
        $query = new WP_Query;
        $this->posts = $query->query($args);
        $this->convertToCustomObjects();

        return $this->posts;
    }

    /**
     * @return int
     */
    public function getMaxNumPages()
    {
        return $this->query->max_num_pages;
    }

    /**
     * Convert standard WP_Post to custom object
     */
    public function convertToCustomObjects()
    {
        // If we already know the task object, send it to the signup object
        $task = (isset($this->task) && $this->task instanceof TaskModel) ? $this->task : null;

        /**
         * @var int     $key
         * @var WP_Post $post
         */
        foreach ($this->posts as $key => $post) {
            $this->posts[$key] = new SignupModel($post, $task);
        }
    }
}
