<?php

use luckywp\glossary\core\Core;
use luckywp\glossary\plugin\Term;

add_filter('the_content', 'lwpgls_content_synonyms');

function lwpgls_content_synonyms($content)
{
    if (lwpgls_is_single()) {
        $synonyms = (new Term(get_post()))->synonyms;
        if ($synonyms) {
            $content = Core::$plugin->front->render('synonyms', [
                    'synonyms' => $synonyms,
                ]) . $content;
        }
    }
    return $content;
}
