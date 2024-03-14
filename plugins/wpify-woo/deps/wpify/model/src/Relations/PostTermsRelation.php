<?php

namespace WpifyWooDeps\Wpify\Model\Relations;

use WpifyWooDeps\Wpify\Model\Interfaces\PostModelInterface;
use WpifyWooDeps\Wpify\Model\Interfaces\PostRepositoryInterface;
use WpifyWooDeps\Wpify\Model\Interfaces\RelationInterface;
use WpifyWooDeps\Wpify\Model\Interfaces\TermRepositoryInterface;
class PostTermsRelation implements RelationInterface
{
    /** @var PostModelInterface */
    public $model;
    /** @var string */
    public $key;
    /** @var TermRepositoryInterface */
    public $term_repository;
    /** @var PostRepositoryInterface */
    public $post_repository;
    /**
     * PostCategoriesRelation constructor.
     *
     * @param PostModelInterface $model
     * @param string $key
     * @param TermRepositoryInterface $term_repository
     * @param PostRepositoryInterface $post_repository
     */
    public function __construct(PostModelInterface $model, string $key, TermRepositoryInterface $term_repository, PostRepositoryInterface $post_repository)
    {
        $this->model = $model;
        $this->key = $key;
        $this->term_repository = $term_repository;
        $this->post_repository = $post_repository;
    }
    public function fetch()
    {
        return $this->term_repository->terms_of_post($this->model->id);
    }
    public function assign()
    {
        return $this->post_repository->assign_post_to_term($this->model, $this->model->{$this->key});
    }
}
