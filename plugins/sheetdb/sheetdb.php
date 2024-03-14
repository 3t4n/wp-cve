<?php

/**
 * @package SheetDB
 */
/*
Plugin name: SheetDB
Description: The SheetDB wordpress plugin allows you to easily add content from Google Spreadsheet to your wordpress site.
Version: 1.2.3
Author: SheetDB
Author URI: https://sheetdb.io/
Plugin URI: https://wordpress.org/plugins/sheetdb/
License: GPLv2 or later
Text domain: sheetdb
*/

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

defined('ABSPATH') or die();

// Main Plugin Class
if (!class_exists('WordpressSheetDB')) {
    class WordpressSheetDB
    {
        public function __construct()
        {
            add_shortcode('sheetdb', [$this, 'sheetdb_shortcode']);
            add_shortcode('sheetdb-slot', [$this, 'sheetdb_slot_shortcode']);

            add_action('wp_footer', array($this, 'enqueueAssets'));
        }

        public function enqueueAssets()
        {
            wp_enqueue_script('sheetdb-js', plugins_url('assets/js/sheetdb-handlebars-1.2.4.js', __FILE__));
        }

        public function sheetdb_shortcode($atts, $content)
        {
            isset($atts['url']) ? $url = $atts['url'] : $url = null;
            isset($atts['element']) ? $element = $atts['element'] : $element = "div";

            isset($atts['save']) ? $save = $atts['save'] : $save = null;
            isset($atts['sheet']) ? $sheet = $atts['sheet'] : $sheet = null;
            isset($atts['limit']) ? $limit = $atts['limit'] : $limit = null;
            isset($atts['offset']) ? $offset = $atts['offset'] : $offset = null;
            isset($atts['search']) ? $search = $atts['search'] : $search = null;
            isset($atts['search-mode']) ? $searchMode = $atts['search-mode'] : $searchMode = null;
            isset($atts['sort-by']) ? $sortBy = $atts['sort-by'] : $sortBy = null;
            isset($atts['sort-order']) ? $sortOrder = $atts['sort-order'] : $sortOrder = null;
            isset($atts['sort-method']) ? $sortMethod = $atts['sort-method'] : $sortMethod = null;
            isset($atts['sort-date-format']) ? $sortDateFormat = $atts['sort-date-format'] : $sortDateFormat = null;
            isset($atts['lazy-loading']) ? $lazy = true : $lazy = false;

            if (!$url) return 'Use URL attribute with the SheetDB shortcode.';

            $additionalCode = $this->makeAdditionalCode($sheet, $limit, $offset, $search, $searchMode, $sortBy, $sortOrder, $sortMethod, $sortDateFormat, $save, $lazy);

            return "<{$element} data-sheetdb-url=\"{$url}\"{$additionalCode}>{$content}</{$element}>";
        }

        public function sheetdb_slot_shortcode($atts, $content)
        {
            isset($atts['slot']) ? $slot = $atts['slot'] : $slot = null;
            isset($atts['element']) ? $element = $atts['element'] : $element = "div";

            return "<{$element} data-sheetdb-slot=\"{$slot}\">{$content}</{$element}>";
        }

        private function makeAdditionalCode($sheet, $limit, $offset, $search, $searchMode, $sortBy, $sortOrder, $sortMethod, $sortDateFormat, $save, $lazy)
        {
            $additionalCode = '';
            if ($sheet) {
                $additionalCode .= ' data-sheetdb-sheet="' . $sheet . '"';
            }
            if ($limit) {
                $additionalCode .= ' data-sheetdb-limit="' . $limit . '"';
            }
            if ($offset) {
                $additionalCode .= ' data-sheetdb-offset="' . $offset . '"';
            }
            if ($search) {
                $additionalCode .= ' data-sheetdb-search="' . $search . '"';
            }
            if ($searchMode) {
                $additionalCode .= ' data-sheetdb-search-mode="' . $searchMode . '"';
            }
            if ($sortBy) {
                $additionalCode .= ' data-sheetdb-sort-by="' . $sortBy . '"';
            }
            if ($sortOrder) {
                $additionalCode .= ' data-sheetdb-sort-order="' . $sortOrder . '"';
            }
            if ($sortMethod) {
                $additionalCode .= ' data-sheetdb-sort-method="' . $sortMethod . '"';
            }
            if ($sortDateFormat) {
                $additionalCode .= ' data-sheetdb-sort-date-format="' . $sortDateFormat . '"';
            }
            if ($save) {
                $additionalCode .= ' data-sheetdb-save="' . $save . '"';
            }
            if ($lazy) {
                $additionalCode .= ' lazy-loading="true"';
            }

            return $additionalCode;
        }
    }
}

$wordpressSheetDB = new WordpressSheetDB;
