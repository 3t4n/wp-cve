<?php

namespace luckywp\glossary\admin\widgets\termSynonymsMetabox;

use luckywp\glossary\admin\forms\TermSynonymsForm;
use luckywp\glossary\core\base\Widget;
use luckywp\glossary\plugin\Term;
use WP_Post;

class TermSynonymsMetabox extends Widget
{

    /**
     * @var WP_Post
     */
    public $post;

    public function run()
    {
        $term = new Term($this->post);
        return $this->render('box', [
            'term' => $term,
            'model' => new TermSynonymsForm($term),
        ]);
    }
}
