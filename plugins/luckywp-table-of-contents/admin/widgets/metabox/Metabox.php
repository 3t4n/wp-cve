<?php

namespace luckywp\tableOfContents\admin\widgets\metabox;

use luckywp\tableOfContents\core\base\Widget;
use luckywp\tableOfContents\plugin\PostSettings;
use WP_Post;

class Metabox extends Widget
{

    /**
     * @var WP_Post
     */
    public $post;

    public function run()
    {
        return $this->render('box', [
            'post' => $this->post,
            'settings' => new PostSettings($this->post->ID),
        ]);
    }
}
