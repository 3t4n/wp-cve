<?php

namespace PlxPortal\Admin;

abstract class MetaBox
{
    protected $id;
    protected $title;
    protected $post_type;
    protected $context;
    protected $priority;
    protected $add_auth;

    public function __construct(
        string $id,
        string $title,
        string $post_type,
        string $context,
        string $priority,
        callable $auth
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->post_type = $post_type;
        $this->context = $context;
        $this->priority = $priority;
        $this->add_auth = $auth;

        add_action('add_meta_boxes', array($this, 'add'));
    }

    public function add()
    {
        if (call_user_func($this->add_auth)) {
            add_meta_box(
                $this->id,
                $this->title,
                array($this, 'render'),
                $this->post_type,
                $this->context,
                $this->priority,
            );
        }
    }

    abstract public function render();
}
