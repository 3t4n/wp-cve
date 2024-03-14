<?php

use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\front\Toc;

/**
 * @param array $items
 * @param bool $echo
 * @return string|null
 */
function lwptoc_items($items, $echo = true)
{
    $html = '';
    if ($items) {
        switch (Core::$plugin->settings->getListMarkupTags()) {
            case 'ul':
                $containerTag = 'ul';
                $itemTag = 'li';
                break;

            case 'ol':
                $containerTag = 'ol';
                $itemTag = 'li';
                break;

            case 'div':
            default:
                $containerTag = 'div';
                $itemTag = 'div';
        }

        Toc::$currentOutputDepth++;
        $html = Core::$plugin->front->render('items', [
            'items' => $items,
            'depth' => Toc::$currentOutputDepth,
            'containerTag' => $containerTag,
            'itemTag' => $itemTag,
        ]);
        Toc::$currentOutputDepth--;
    }
    if ($echo) {
        echo $html;
        return null;
    }
    return $html;
}
