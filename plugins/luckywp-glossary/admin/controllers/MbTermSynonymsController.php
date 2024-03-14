<?php

namespace luckywp\glossary\admin\controllers;

use luckywp\glossary\admin\forms\TermSynonymsForm;
use luckywp\glossary\core\admin\AdminController;
use luckywp\glossary\core\Core;
use luckywp\glossary\plugin\Term;

class MbTermSynonymsController extends AdminController
{

    public function init()
    {
        parent::init();
        add_action('save_post', [$this, 'save']);
    }

    /**
     * @param int $postId
     */
    public function save($postId)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (current_user_can('edit_posts') && TermSynonymsForm::verifyNonce()) {
            $post = get_post($postId);
            if ($post->post_type == Term::POST_TYPE) {
                $term = new Term($post);
                $model = new TermSynonymsForm($term);
                if ($model->load(Core::$plugin->request->post()) && $model->validate()) {
                    $term->synonyms = $model->getSynonyms();
                }
            }
        }
    }
}
