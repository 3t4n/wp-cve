<?php

class Zwt_wp_linkpreviewer_Shortcode
{
    const STYLE_MAX_WIDTH = "max-width:";
    const ATTR_SHOW_IMG = 'showImg';
    const ATTR_SHOW_TITLE = 'showTitle';
    const ATTR_SHOW_DESCRIPTION = 'showDescription';
    const ATTR_TARGET = 'target';
    const ATTR_DESC_MAX_CHARS = 'descMaxChars';
    const ATTR_TITLE_MAX_CHARS = 'titleMaxChars';
    const ATTR_LAYOUT = 'layout';
    const ATTR_HASH_MD_5 = 'hash_md5';
    const ATTR_URL_HOST = 'urlHost';
    const ATTR_URL = 'url';
    const ATTR_TITLE = 'title';
    const ATTR_DESCRIPTION = 'description';
    const ATTR_HAS_IMG_FULL = 'has_img_full';
    const ATTR_HAS_IMG_COMPACT = 'has_img_compact';

    public function handle_short_code($atts)
    {
        $atts = shortcode_atts(array(
            // normalize atts
            Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_URL => $this->getOption(Zwt_wp_linkpreviewer_Constants::$KEY_LAYOUT),
            Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_LAYOUT => $this->getOption(Zwt_wp_linkpreviewer_Constants::$KEY_LAYOUT),
            Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_NO_IMG => $this->hasAtt($atts, Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_NO_IMG),
            Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_NO_TITLE => $this->hasAtt($atts, Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_NO_TITLE),
            Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_NO_DESC => $this->hasAtt($atts, Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_NO_DESC),
            Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_TARGET => $this->getOption(Zwt_wp_linkpreviewer_Constants::$KEY_TARGET),
            Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_MAX_TITLE_CHARS => $this->getOption(Zwt_wp_linkpreviewer_Constants::$KEY_MAX_TITLE_CHARS),
            Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_MAX_DESC_CHARS => $this->getOption(Zwt_wp_linkpreviewer_Constants::$KEY_MAX_DESC_CHARS),
        ), $atts);
        $url = esc_url($atts[Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_URL]);
        if ($url) {
            return $this->handleUrl($url, $atts);
        }
        return null;
    }

    private function getOption($key)
    {
        if ($key == Zwt_wp_linkpreviewer_Constants::$KEY_MAX_TITLE_CHARS ||
            $key == Zwt_wp_linkpreviewer_Constants::$KEY_MAX_DESC_CHARS ||
            $key == Zwt_wp_linkpreviewer_Constants::$KEY_LAYOUT ||
            $key == Zwt_wp_linkpreviewer_Constants::$KEY_TARGET ||
            $key == Zwt_wp_linkpreviewer_Constants::$KEY_ENABLED) {
            return Zwt_wp_linkpreviewer_Utils::getOptionValue($key);
        } else {
            die("Option $key undefined!");
        }
    }

    private function handleUrl($url, $atts)
    {
        if (!$this->getOption(Zwt_wp_linkpreviewer_Constants::$KEY_ENABLED)) {
            return $this->render_plugin_not_enabled($url);
        }
        $urlFetcher = new Zwt_wp_linkpreviewer_URL_Fetcher();
        $fetchResult = $urlFetcher->maybeFetchUrl($url);
        if (!$fetchResult) {
            return null;
        } else {
            $normalized_atts = $this->normalizeAtts($atts);
            if ($normalized_atts[Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_LAYOUT] == Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_LAYOUT_FULL) {
                return $this->render_layout_full($fetchResult, $normalized_atts);
            } else if ($normalized_atts[Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_LAYOUT] == Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_LAYOUT_COMPACT) {
                return $this->render_layout_compact($fetchResult, $normalized_atts);
            } else {
                die("Layout " . $normalized_atts[Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_LAYOUT] . " undefined!");
            }
        }
    }

    private function render_plugin_not_enabled($url)
    {
        $text_not_enabled = sprintf(Zwt_wp_linkpreviewer_Constants::$PLUGIN_NOT_ENABLED,
                Zwt_wp_linkpreviewer_Constants::$TEXT_PLUGIN_NAME,
                "Link: " . Zwt_wp_linkpreviewer_Utils::wrap_anchor($url, $url, "", $this->getOption(Zwt_wp_linkpreviewer_Constants::$KEY_TARGET))) . "<br>" . $this->render_previewby();
        return "<div class='zwt-wp-lnk-prev-disabled'>$text_not_enabled </div>";
    }

    private function render_layout_full($dbResult, $atts)
    {
        $normalizedFetchResult = $this->normalizeFetchResult($dbResult, $atts);
        $img_max_width = self::STYLE_MAX_WIDTH . Zwt_wp_linkpreviewer_Constants::$FETCH_IMG_FULL_SIZE . "px;";
        $render_img = $atts[self::ATTR_SHOW_IMG] && $normalizedFetchResult[self::ATTR_HAS_IMG_FULL];
        return "<div class='zwt-wp-lnk-prev full' style='$img_max_width'>" .
            (($render_img) ? Zwt_wp_linkpreviewer_Utils::render_img_html($normalizedFetchResult[self::ATTR_HASH_MD_5], $normalizedFetchResult[self::ATTR_URL], $atts[self::ATTR_TARGET], true) : Zwt_wp_linkpreviewer_Utils::render_empty_img_html($normalizedFetchResult[self::ATTR_URL], $atts[self::ATTR_TARGET])) .
            "  <div class='zwt-wp-lnk-prev-texts'>" .
            "      <div>" . Zwt_wp_linkpreviewer_Utils::wrap_anchor($normalizedFetchResult[self::ATTR_URL], $normalizedFetchResult[self::ATTR_URL_HOST], "zwt-wp-lnk-prev-url-host", $atts[self::ATTR_TARGET]) . "</div>" .
            "      " . ($atts[self::ATTR_SHOW_TITLE] ? Zwt_wp_linkpreviewer_Utils::wrap_anchor($normalizedFetchResult[self::ATTR_URL], $normalizedFetchResult[self::ATTR_TITLE], "url zwt-wp-lnk-prev-title", $atts[self::ATTR_TARGET]) : "") .
            "     " . ($atts[self::ATTR_SHOW_DESCRIPTION] ? "<div class='zwt-wp-lnk-prev-desc'>" . $normalizedFetchResult[self::ATTR_DESCRIPTION] . "</div>" : "") .
            "     " . $this->render_previewby() .
            "  </div>" .
            "</div>";
    }

    private function render_layout_compact($dbResult, $atts)
    {
        $normalizedFetchResult = $this->normalizeFetchResult($dbResult, $atts);
        $img_max_width = self::STYLE_MAX_WIDTH . Zwt_wp_linkpreviewer_Constants::$FETCH_IMG_FULL_SIZE . "px;";
        $render_img = $atts[self::ATTR_SHOW_IMG] && $normalizedFetchResult[self::ATTR_HAS_IMG_COMPACT];
        return
            "<div class='zwt-wp-lnk-prev compact' style='$img_max_width'>" .
            (($render_img) ? $this->render_img_compact_html($atts[self::ATTR_SHOW_IMG] && $normalizedFetchResult[self::ATTR_HAS_IMG_COMPACT], $normalizedFetchResult[self::ATTR_HASH_MD_5], $normalizedFetchResult[self::ATTR_URL], $atts[self::ATTR_TARGET]) : $this->render_empty_img_compact_html($atts[self::ATTR_SHOW_IMG], $normalizedFetchResult[self::ATTR_URL], $atts[self::ATTR_TARGET])) .
            "     <div class='zwt-wp-lnk-prev-texts'>" .
            "            <div>" . Zwt_wp_linkpreviewer_Utils::wrap_anchor($normalizedFetchResult[self::ATTR_URL], $normalizedFetchResult[self::ATTR_URL_HOST], "zwt-wp-lnk-prev-url-host", $atts[self::ATTR_TARGET]) . "</div>" .
            "        " . ($atts[self::ATTR_SHOW_TITLE] ? Zwt_wp_linkpreviewer_Utils::wrap_anchor($normalizedFetchResult[self::ATTR_URL], $normalizedFetchResult[self::ATTR_TITLE], "url zwt-wp-lnk-prev-title", $atts[self::ATTR_TARGET]) : "") .
            "        " . ($atts[self::ATTR_SHOW_DESCRIPTION] ? "<div class='zwt-wp-lnk-prev-desc'>" . $normalizedFetchResult[self::ATTR_DESCRIPTION] . "</div>" : "") .
            "        " . $this->render_previewby() .
            "     </div>" .
            "</div>";
    }

    private function render_img_compact_html($showImg, $hash_md5, $url, $target)
    {
        if ($showImg) {
            $img_max_width = self::STYLE_MAX_WIDTH . Zwt_wp_linkpreviewer_Constants::$FETCH_IMG_COMPACT_SIZE . "px;";
            return "<div class='zwt-wp-lnk-prev-img-container' style='$img_max_width'>" .
                Zwt_wp_linkpreviewer_Utils::render_img_html($hash_md5, $url, $target, false) .
                "</div>";
        } else {
            return "";
        }
    }

    private function render_empty_img_compact_html($showImg, $url, $target)
    {
        if ($showImg) {
            $img_max_width = self::STYLE_MAX_WIDTH . Zwt_wp_linkpreviewer_Constants::$FETCH_IMG_COMPACT_SIZE . "px;";
            return "<div class='zwt-wp-lnk-prev-img-container' style='$img_max_width'>" .
                Zwt_wp_linkpreviewer_Utils::render_empty_img_html($url, $target) .
                "</div>";
        } else {
            return "";
        }
    }

    private function render_previewby()
    {
        $result = Zwt_wp_linkpreviewer_Utils::getOptionValue(Zwt_wp_linkpreviewer_Constants::$KEY_SHOW_PREVIEWBY, Zwt_wp_linkpreviewer_Constants::$OPTION_DEFAULT_SHOW_PREVIEWBY);
        if ($result) {
            return "<div class='zwt-wp-lnk-prev-credits'>" . Zwt_wp_linkpreviewer_Utils::link_preview_by() . "</div>";
        }
        return "";
    }


    private function hasAtt($atts, $att)
    {
        return in_array($att, $atts);
    }

    private function normalizeAtts($atts)
    {
        return array(
            self::ATTR_LAYOUT => $this->sanitizeLayout($atts[Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_LAYOUT]),
            self::ATTR_TITLE_MAX_CHARS => $this->sanitizeInteger($atts[Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_MAX_TITLE_CHARS], $this->getOption(Zwt_wp_linkpreviewer_Constants::$KEY_MAX_TITLE_CHARS)),
            self::ATTR_DESC_MAX_CHARS => $this->sanitizeInteger($atts[Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_MAX_DESC_CHARS], $this->getOption(Zwt_wp_linkpreviewer_Constants::$KEY_MAX_DESC_CHARS)),
            self::ATTR_TARGET => $this->sanitizeTarget($atts[Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_TARGET]),
            self::ATTR_SHOW_IMG => !$atts[Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_NO_IMG],
            self::ATTR_SHOW_TITLE => !$atts[Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_NO_TITLE],
            self::ATTR_SHOW_DESCRIPTION => !$atts[Zwt_wp_linkpreviewer_Constants::$TAG_ATTR_NO_DESC]
        );
    }

    private function normalizeFetchResult($fetchResult, $atts)
    {
        return array(
            self::ATTR_URL => $fetchResult->url,
            self::ATTR_URL_HOST => $fetchResult->urlHost,
            self::ATTR_HASH_MD_5 => $fetchResult->hashMd5,
            self::ATTR_TITLE => Zwt_wp_linkpreviewer_Utils::trim_text($fetchResult->title, $atts[self::ATTR_TITLE_MAX_CHARS]),
            self::ATTR_DESCRIPTION => Zwt_wp_linkpreviewer_Utils::trim_text($fetchResult->description, $atts[self::ATTR_DESC_MAX_CHARS]),
            self::ATTR_HAS_IMG_FULL => $fetchResult->hasImgFull,
            self::ATTR_HAS_IMG_COMPACT => $fetchResult->hasImgCompact
        );
    }

    private function sanitizeInteger($integer, $default)
    {
        $intVal = intval($integer);
        return $intVal ? $intVal : $default;
    }

    private function sanitizeLayout($layout)
    {
        return ($layout == "full" || $layout == "compact") ? $layout : $this->getOption(Zwt_wp_linkpreviewer_Constants::$KEY_LAYOUT);
    }

    private function sanitizeTarget($target)
    {
        return ($target == "_blank" || $target == "_self" || $target == "_parent" || $target == "_top") ? $target : "";
    }


}
