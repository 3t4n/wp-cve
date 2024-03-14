<?php

namespace WpifyWooDeps\Wpify\Model;

use WpifyWooDeps\Wpify\Model\Abstracts\AbstractTermModel;
use WpifyWooDeps\Wpify\Model\Interfaces\PostModelInterface;
use WpifyWooDeps\Wpify\Model\Relations\TermPostsRelation;
class PostTag extends AbstractTermModel
{
    /** @var PostModelInterface */
    public $posts;
    protected function posts_relation() : TermPostsRelation
    {
        return new TermPostsRelation($this, 'posts', $this->model_repository()->get_post_repository());
    }
}
