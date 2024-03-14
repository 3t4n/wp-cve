<?php
/**
 * Internal Links Manager
 * Copyright (C) 2021 webraketen GmbH
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You can read the GNU General Public License here: <https://www.gnu.org/licenses/>.
 * For questions related to this program contact post@webraketen-media.de
 */

namespace SeoAutomatedLinkBuilding;

use html_changer\HtmlChanger;

use DOMXPath;

class TextConverter {

    public static $PLACEHOLDER = 'internallinksmanager6a99c575-';

    /**
     * @var string
     */
    private $startText;

    /**
     * @var string
     */
    private $text;

    /**
     * @var array
     */
    private $counts = [];

    /**
     * @var array
     */
    private $links = [];

    /**
     * TextConverter constructor.
     * @param $text
     */
    public function __construct($text)
    {
        $this->startText = $text;
        $this->text = $text;
    }

    /**
     * Add links to html
     *
     * @param array $links
     * @return $this
     */
    public function addLinks(array $links)
    {
        $search = [];
        /**
         * @var Link $link
         */
        foreach ($links as $link) {
            if($link->num === 0) {
                continue;
            }
            foreach($link->getKeywords() as $keyword) {
                $options = [
                    'caseInsensitive' => !$link->case_sensitive,
                    'wordBoundary' => !$link->partly_match,
                    'value' => $link,
                    'group' => $link->id,
                    'maxCount' => $link->num,
                    'priority' => $link->priority,
                ];
                $trimmedKeyword = trim($keyword);
                if(empty($trimmedKeyword)) {
                    // do not add empty keywords
                    continue;
                }
                // first add original keyword
                $search[$trimmedKeyword] = $options;

                // escape keyword
                $escapedKeyword = esc_html($trimmedKeyword);
                $search[$escapedKeyword] = $options;

                // "detexturize" keyword
                $escapedKeyword2 = preg_replace('/’/', "'", $trimmedKeyword);
                // texturize keyword with wordpress function
                $escapedKeyword2 = wptexturize($escapedKeyword2);
                $search[$escapedKeyword2] = $options;

                $escapedKeyword3 = $trimmedKeyword;
                $search[$escapedKeyword3] = $options;
            }
        }
        $settings = Settings::get();
        $htmlChanger = new HtmlChanger($this->text, [
            'search' => $search,
            'ignore' => array_merge([
                    'a', // links
                    'h1', 'h2', 'h3', 'h4', 'h5', 'h6', // headlines
                ],
                $settings['exclude']
            )
        ]);

        $htmlChanger->replace(function($text, Link $link) {
            return $link->getReplaceString($text);
        });

        $this->text = $htmlChanger->html();

        // fluent interface
        return $this;
    }

    /**
     * Get transformed text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text The text that should be replaced
     * @param Link   $link
     * @return string
     */
    private function replaceText($text, Link $link)
    {
        return $link->getReplaceString($text);
    }

}
