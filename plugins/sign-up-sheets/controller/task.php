<?php
/**
 * Task Controller
 */

namespace FDSUS\Controller;

use FDSUS\Model\Task as TaskModel;

class Task extends PostTypeBase
{
    public function __construct()
    {
        $this->postType = TaskModel::POST_TYPE;

        add_action('init', array(&$this, 'addPostType'), 0);

        parent::__construct();
    }

    /**
     * Add custom post type
     */
    public function addPostType()
    {
        $args = array(
            'labels' => $this->getPostTypeLabels(TaskModel::getName(true), TaskModel::getName()),
            'hierarchical'        => false,
            'supports'            => array(
                'title',
                'editor',
                'author'
            ),
            'public'              => true,
            'show_ui'             => false,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'has_archive'         => TaskModel::getBaseSlug(),
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => array('slug' => TaskModel::getBaseSlug()),
            'capability_type'     => TaskModel::POST_TYPE,
            'capabilities'        => $this->getAddCapsArray(TaskModel::POST_TYPE),
        );
        register_post_type(TaskModel::POST_TYPE, $args);
    }
}
