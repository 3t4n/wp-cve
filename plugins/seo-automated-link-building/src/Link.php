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


use wp_activerecord\ActiveRecord;

/**
 * @property integer id
 * @property string title
 * @property string titleattr
 * @property string keywords
 * @property string url
 * @property integer num
 * @property boolean nofollow
 * @property boolean notitle
 * @property boolean active
 * @property boolean partly_match
 * @property boolean case_sensitive
 * @property string target
 * @property integer priority
 */
class Link extends ActiveRecord
{
    protected static $table_name = 'seo_automated_link_building';

    protected static $casts = [
        'nofollow' => 'boolean',
        'notitle' => 'boolean',
        'active' => 'boolean',
        'partly_match' => 'boolean',
        'case_sensitive' => 'boolean',
        'num' => 'int',
        'priority' => 'int',
    ];

    public function getKeywords()
    {
        $keywords = json_decode($this->keywords, false, 512, JSON_UNESCAPED_UNICODE);
        if(!is_array($keywords)) {
            $keywords = [];
        }
        return $keywords;
    }

    public function getReplacePattern()
    {
        $keywordsInDatabase = json_decode($this->keywords, false, 512, JSON_UNESCAPED_UNICODE);
        if(!is_array($keywordsInDatabase)) {
            $keywordsInDatabase = [];
        }
        $keywords = [];

        // add html entities versions
        foreach ($keywordsInDatabase as $keyword) {
            $trimmedKeyword = $keyword;
            if(empty($trimmedKeyword)) {
                // do not add empty keywords
                continue;
            }
            // first add original keyword
            $keywords[] = $trimmedKeyword;

            // escape keyword and compare with original
            $escapedKeyword = esc_html($trimmedKeyword);
            if($escapedKeyword !== $trimmedKeyword) {
                // add escaped keyword to list
                $keywords[] = $escapedKeyword;
            }

            // texturize keyword with wordpress function and compare with original
            $escapedKeyword2 = wptexturize($trimmedKeyword);
            if($escapedKeyword2 !== $trimmedKeyword && $escapedKeyword2 !== $escapedKeyword) {
                // add escaped keyword to list
                $keywords[] = $escapedKeyword2;
            }
        }

        // create regex strings out of keywords
        $self = $this;
        $keywords = array_map(function($str) use($self) {
            $quoted = preg_quote($str, '/');
            if($self->partly_match) {
                return $quoted;
            }
            return '(?:(?<!\w))' . $quoted . '(?:(?!\w))';
        }, $keywords);

        return '/' . join('|', $keywords) . '/ui';
    }

    public function getReplaceString($match)
    {
        $url = $this->url;
        $title = $this->titleattr;

        if($this->page_id) {
            $post = get_post($this->page_id);
            if($post) {
                $url = get_permalink($post);
                if(empty($title)) {
                    $title = $post->post_title;
                }
            }
        }

        $dataHash = "internallinksmanager029f6b8e52c";
        $attrs = [
            'href' => $url,
            "data-$dataHash" => $this->id,
        ];

        if(!$this->notitle) {
            $attrs['title'] = $title;
            if(empty($attrs['title'])) {
                $attrs['title'] = $this->title;
            }
        }

        if($this->nofollow) {
            $attrs['rel'] = 'nofollow';
        }

        if($this->target === '_blank') {
            $attrs['target'] = '_blank';
            if(array_key_exists('rel', $attrs)) {
                $attrs['rel'] .= ' noopener';
            } else {
                $attrs['rel'] = 'noopener';
            }
        }

        // generate html string out of attributes
        $attrsFlat = [];
        foreach ($attrs as $name => $value) {
            $encodedValue = htmlspecialchars($value);
            $attrsFlat[] = "$name=\"$encodedValue\"";
        }
        $attrsStr = join(' ', $attrsFlat);

        return "<a $attrsStr>$match</a>";
    }
}
