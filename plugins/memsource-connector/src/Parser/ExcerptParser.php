<?php

namespace Memsource\Parser;

use Memsource\Utils\LogUtils;

class ExcerptParser
{
    public function encode(string $content, \WP_Post $post): string
    {
        return $content .
            sprintf(
                '<div id="%s" class="memsource-post-attribute">%s<div class="memsource-post-attribute-end"></div></div>' . "\n",
                'excerpt',
                $post->post_excerpt
            );
    }

    /** @return string|null  */
    public function decode(&$content)
    {
        $pattern = '|<div id="excerpt" class="memsource-post-attribute">(.*)<div class="memsource-post-attribute-end"></div></div>|sm';
        $matchResult = preg_match($pattern, $content, $matches);
        //  Remove the tags
        $content = preg_replace($pattern, '', $content);

        return $matchResult ? $matches[1] : null;
    }
}
