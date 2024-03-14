<?php

namespace luckywp\glossary\front\widgets;

use luckywp\glossary\core\base\Widget;
use luckywp\glossary\core\Core;
use luckywp\glossary\core\helpers\StringHelper;
use luckywp\glossary\plugin\Term;

class TermsArchive extends Widget
{

    public function run()
    {
        // Посты
        $posts = get_posts([
            'post_type' => Term::POST_TYPE,
            'posts_per_page' => 30,
        ]);

        // Термины
        $terms = [];
        foreach ($posts as $post) {
            $term = new Term($post);
            $terms[$term->name] = $term;
            foreach ($term->synonyms as $synonym) {
                $terms[$synonym] = $term;
            }
        }
        ksort($terms, SORT_STRING | SORT_FLAG_CASE);

        // По буквам
        $termsByLetter = [];
        foreach ($terms as $name => $term) {
            $l = mb_substr($name, 0, 1);
            $l = StringHelper::strtoupper($l);
            if (!isset($termsByLetter[$l])) {
                $termsByLetter[$l] = [];
            }
            $termsByLetter[$l][$name] = $term;
        }

        // Колонки
        $termsByColumn = [];
        $completedLines = 0;
        $lines = 0;
        $column = 0;
        $prevColumn = 0;
        $countLines = count($terms) + count($termsByLetter) * 2;
        foreach ($termsByLetter as $letter => $terms) {
            if (($lines == 0) || ($column != $prevColumn)) {
                $termsByColumn[$column] = [];
                $prevColumn = $column;
            }
            $termsByColumn[$column][$letter] = $terms;
            $lines += count($terms) + 2;
            if (($lines == $countLines) || ($lines >= ($completedLines + floor(($countLines - $completedLines) / (2 - $column))))) {
                $completedLines = $lines;
                $column++;
            }
        }

        // Вывод
        return Core::$plugin->front->render('archive-term', [
            'countColumns' => 2,
            'terms' => $terms,
            'termsByLetter' => $termsByLetter,
            'termsByColumn' => $termsByColumn,
        ]);
    }
}
