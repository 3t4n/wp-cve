<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Attire\Blocks\Util;

if (!class_exists('ATBS_Table_Of_Content')) {


    /**
     * Class ATBS_Table_Of_Content.
     */
    class ATBS_Table_Of_Content
    {


        /**
         * Member Variable
         * @var instance
         */
        private static $instance;


        /**
         *  Initiator
         */
        public static function get_instance()
        {
            if (!isset(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Constructor
         */
        public function __construct()
        {
            add_action('init', array($this, 'register_table_of_contents'));
            add_action('save_post', array($this, 'delete_toc_meta'), 10, 3);
            add_filter('render_block_data', array($this, 'update_toc_title'));
        }

        /**
         * Update TOC tile if old title is set.
         * @param $parsed_block
         * @return array
         */
        public function update_toc_title($parsed_block)
        {

            if ('attire-blocks/table-of-contents' === $parsed_block['blockName'] && !isset($parsed_block['attrs']['headingTitle'])) {

                $content = $parsed_block['innerHTML'];
                $matches = array();

                preg_match('/<div class=\"atbs-toc__title\">([^`]*?)<\/div>/', $content, $matches);

                if (!empty($matches[1])) {
                    $parsed_block['attrs']['headingTitle'] = $matches[1];
                }
            }

            return $parsed_block;
        }

        /**
         * Delete TOC meta.
         * @param $post_id
         * @param $post
         * @param $update
         */
        public function delete_toc_meta($post_id, $post, $update)
        {
            delete_post_meta($post_id, '_atbs_toc_options');
        }

        /**
         * Extracts heading content, id, and level from the given post content.
         * @param $content
         * @return array|array[]|void[]
         */
        public function table_of_contents_get_headings_from_content($content)
        {

            /* phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase */
            // Disabled because of PHP DOMDocument and DOMXPath APIs using camelCase.

            // Create a document to load the post content into.
            $doc = new DOMDocument('1.0', 'UTF-8');

            // Enable user error handling for the HTML parsing. HTML5 elements aren't
            // supported (as of PHP 7.4) and There's no way to guarantee that the markup
            // is valid anyway, so we're just going to ignore all errors in parsing.
            // Nested heading elements will still be parsed.
            // The lack of HTML5 support is a libxml2 issue:
            // https://bugzilla.gnome.org/show_bug.cgi?id=761534.
            libxml_use_internal_errors(true);

            // Parse the post content into an HTML document.
            $doc->loadHTML(
            // loadHTML expects ISO-8859-1, so we need to convert the post content to
            // that format. We use html entities to encode Unicode characters not
            // supported by ISO-8859-1 as HTML entities. However, this function also
            // converts all special characters like < or > to HTML entities, so we use
            // htmlspecialchars_decode to decode them.
                '<html><head><meta charset="UTF-8"></head><body>' . $content . '</body></html>'
            );

            // We're done parsing, so we can disable user error handling. This also
            // clears any existing errors, which helps avoid a memory leak.
            libxml_use_internal_errors(false);

            // IE11 treats template elements like divs, so to avoid extracting heading
            // elements from them, we first have to remove them.
            // We can't use foreach directly on the $templates DOMNodeList because it's a
            // dynamic list, and removing nodes confuses the foreach iterator. So
            // instead, we convert the iterator to an array and then iterate over that.

            if (!isset($doc->documentElement) || !is_object($doc->documentElement)) {

                return array();
            }

            $templates = iterator_to_array(
                $doc->documentElement->getElementsByTagName('template')
            );

            foreach ($templates as $template) {
                $template->parentNode->removeChild($template);
            }

            $xpath = new DOMXPath($doc);

            // Get all non-empty heading elements in the post content.
            $headings = iterator_to_array(
                $xpath->query(
                    '//*[self::h1 or self::h2 or self::h3 or self::h4 or self::h5 or self::h6]'
                )
            );

            return array_map(
                function ($heading) {

                    $exclude_heading = null;

                    if (isset($heading->attributes)) {
                        $class_name = $heading->attributes->getNamedItem('class');
                        if (null !== $class_name && '' !== $class_name->value) {
                            $exclude_heading = $class_name->value;
                        }
                    }

                    $mapping_header = 0;

                    if ('atbs-toc-hide-heading' !== $exclude_heading) {

                        return array(
                            // A little hacky, but since we know at this point that the tag will
                            // be a h1-h6, we can just grab the 2nd character of the tag name
                            // and convert it to an integer. Should be faster than conditionals.
                            'level' => (int)$heading->nodeName[1],
                            'id' => $this->clean($heading->textContent),
                            'content' => wp_strip_all_tags($heading->textContent),
                            'depth' => intval(substr($heading->tagName, 1)),
                        );
                    }
                },
                $headings
            );
            /* phpcs:enable */
        }

        /**
         * Clean up heading content.
         * @param $string
         * @return string
         */
        public function clean($string)
        {

            $string = preg_replace('/[\x00-\x1F\x7F]*/u', '', $string);
            $string = str_replace(array('&amp;', '&nbsp;'), ' ', $string);
            // Remove all except alphabets, space, `-` and `_`.
            $string = preg_replace('/[^A-Za-z0-9 _-]/', '', $string);
            // Convert space characters to an `_` (underscore).
            $string = preg_replace('/\s+/', '_', $string);
            // Replace multiple `_` (underscore) with a single `-` (hyphen).
            $string = preg_replace('/_+/', '-', $string);
            // Replace multiple `-` (hyphen) with a single `-` (hyphen).
            $string = preg_replace('/-+/', '-', $string);
            // Remove trailing `-` and `_`.
            $string = trim($string, '-_');

            if (empty($string)) {
                $string = 'toc_' . uniqid();
            }

            return strtolower($string); // Replaces multiple hyphens with single one.
        }

        /**
         * Converts a flat list of heading parameters to a hierarchical nested list
         * based on each header's immediate parent's level.
         * @param $heading_list
         * @param int $index
         * @return array
         */
        public function table_of_contents_linear_to_nested_heading_list(
            $heading_list,
            $index = 0
        )
        {
            $nested_heading_list = array();

            foreach ($heading_list as $key => $heading) {

                if (!is_null($heading_list[$key])) {

                    $nested_heading_list[] = array(
                        'heading' => $heading,
                        'index' => $index + $key,
                        'children' => null,
                    );

                }
            }

            return $nested_heading_list;
        }

        /**
         * Renders the heading list of the Table Of Contents block.
         * @param $nested_heading_list
         * @param $page_url
         * @param $attributes
         * @return string
         */
        public function table_of_contents_render_list($nested_heading_list, $page_url, $attributes)
        {
            $toc = '<ol class="atbs-toc__list">';
            $last_level = '';
            $parent_level = '';
            $first_level = '';
            $current_depth = 0;
            $depth_array = array(
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
            );

            foreach ($nested_heading_list as $anchor => $heading) {

                $level = $heading['heading']['level'];
                $title = $heading['heading']['content'];
                $id = $heading['heading']['id'];

                if (0 === $anchor) {
                    $first_level = $level;
                }

                if ($level < $first_level) {
                    continue;
                }

                if (empty($parent_level) || $level < $parent_level) {
                    $parent_level = $level;
                }

                if (!empty($last_level)) {

                    if ($level > $last_level) {
                        $toc .= '<ul class="atbs-toc__list">';
                        $current_depth++;
                        $depth_array[$level] = $current_depth;

                    } elseif ($level === $last_level && $level !== $parent_level) {

//                        $toc .= '<li class="atbs-toc__list">';
                        $depth_array[$level] = $current_depth;

                    } elseif ($level < $last_level) {

                        $closing = absint($current_depth - $depth_array[$level]);

                        if ($level > $parent_level) {

                            $toc .= str_repeat('</li></ul>', $closing);
                            $current_depth = absint($current_depth - $closing);

                        } elseif ($level === $parent_level) {

                            $toc .= str_repeat('</li></ul>', $closing);
                            $toc .= '</li>';
                        }
                    }
                }

                $toc .= sprintf('<li class="atbs-toc__list"><a href="#%s">%s</a>', esc_attr($id), $title);
                $last_level = $level;
            }

            $toc .= str_repeat('</li></ul>', $current_depth);
            $toc .= '</ol>';
            return $toc;
        }

        /**
         * Filters the Headings according to Mapping Headers Array.
         * @param $headings
         * @param $mapping_headers_array
         * @return array
         */
        public function filter_headings_by_mapping_headers($headings, $mapping_headers_array)
        {

            $filtered_headings = array();

            foreach ($headings as $heading) {

                $mapping_header = 0;

                foreach ($mapping_headers_array as $key => $value) {

                    if ($mapping_headers_array[$key]) {

                        $mapping_header = ($key + 1);
                    }

                    if (isset($heading) && $mapping_header === $heading['level']) {

                        $filtered_headings[] = $heading;
                        break;
                    }
                }
            }

            return $filtered_headings;

        }

        /**
         * Renders the Table Of Contents block.
         * @param $attributes
         * @param $content
         * @param $block
         * @return false|string
         */
        public function render_table_of_contents($attributes, $content, $block)
        {

            global $post;

            if (!isset($post->ID)) {
                return '';
            }
            $toc_script_path = '/assets/static/table-of-contents.js';
            wp_enqueue_script('attire-blocks-toc', ATTIRE_BLOCKS_DIR_URL . $toc_script_path, ['jquery'], filemtime(ATTIRE_BLOCKS_DIR_PATH . $toc_script_path));
            wp_localize_script(
                'attire-blocks-toc',
                'block_attr',
                $attributes
            );
            $atbs_toc_options = get_post_meta($post->ID, '_atbs_toc_options', true);
            $atbs_toc_heading_content = !empty($atbs_toc_options['_atbs_toc_headings']) ? $atbs_toc_options['_atbs_toc_headings'] : '';

            if (empty($atbs_toc_heading_content)) {

                $atbs_toc_heading_content = $this->table_of_contents_get_headings_from_content(get_post($post->ID)->post_content);

                $meta_array = array(
                    '_atbs_toc_headings' => $atbs_toc_heading_content,
                );

                update_post_meta($post->ID, '_atbs_toc_options', $meta_array);

            }

            $atbs_toc_heading_content = $this->filter_headings_by_mapping_headers($atbs_toc_heading_content, $attributes['mappingHeaders']);

            $mapping_header_func = function ($value) {
                return $value;
            };

            $wrap = array(
                'wp-block-atbs-table-of-contents',
                ((true === $attributes['initialCollapse']) ? 'atbs-toc__collapse' : ' '),
                ((true === $attributes['makeCollapsible']) ? 'atbs-toc-is_collapsible' : ''),
                'atbs-block-' . $attributes['blockID'],
                (isset($attributes['className'])) ? $attributes['className'] : '',
                $attributes['wideContent'] === true ? 'container-fluid' : 'container',
            );
            $listType = $attributes['listStyle'] === 'ul' ? 'unordered' : 'ordered';
            $iconClass = $attributes['initialCollapse'] ? $attributes['collapseIndicator'][0] : $attributes['collapseIndicator'][1];
            $textAlign = ['left' => 'flex-start', 'right' => 'flex-end', 'center' => 'center'];

            ob_start();
            ?>
            <style>
                .atbs-block-<?=$attributes['blockID']?> .atbs-toc__list::before, .atbs-block-<?=$attributes['blockID']?> .atbs-toc__list-wrap, .atbs-block-<?=$attributes['blockID']?> .atbs-toc__list-wrap a {
                <?= Util::typographyCss($attributes,'content')?>
                }

                .atbs-block-<?=$attributes['blockID']?> .atbs-toc__title-wrap {
                    justify-content: <?=$textAlign[$attributes['titleTextAlign']]?>;
                }
            </style>
            <div class="<?php echo esc_html(implode(' ', $wrap)); ?>"
                 data-scroll="true"
                 data-offset="30"
                 data-delay="800"
                 style="background: <?= $attributes['BgOverlay'] . ';' . Util::getSpacingStyles($attributes) . ';' . Util::get_border_css($attributes) ?>;">
                <div class="atbs-toc__wrap">
                    <div class="atbs-toc__title-wrap row no-gutters">
                        <div class="atbs-toc__title" style="<?= Util::typographyCss($attributes, 'title') ?>">
                            <?php echo wp_kses_post($attributes['headingTitle']); ?>
                        </div>
                        <?php
                        if ($attributes['makeCollapsible']) {
                            ?>
                            &nbsp;
                            <span class="atbs-toc__collapsible-wrap">
                            <i style="<?= Util::typographyCss($attributes, 'icon') ?>"
                               class="<?= $iconClass ?>"></i></span>
                            <?php
                        }
                        ?>
                    </div>
                    <?php if ($atbs_toc_heading_content && count($atbs_toc_heading_content) > 0 && count(array_filter($attributes['mappingHeaders'], $mapping_header_func)) > 0) { ?>
                        <div class="atbs-toc__list-wrap <?= $listType ?>">
                            <?php
                            echo wp_kses_post(
                                $this->table_of_contents_render_list(
                                    $this->table_of_contents_linear_to_nested_heading_list($atbs_toc_heading_content),
                                    get_permalink($post->ID),
                                    $attributes
                                )
                            );
                            ?>
                        </div>
                    <?php } else { ?>
                        <p class='atbs_table-of-contents-placeholder'>
                            <?php echo esc_html($attributes['emptyHeadingText']); ?>
                        </p>
                    <?php } ?>
                </div>
            </div>
            <?php

            return ob_get_clean();
        }

        /**
         * Registers the Table Of Contents block.
         */
        public function register_table_of_contents()
        {
            $mapping_headers_array = array_fill_keys(array(0, 1, 2, 3, 4, 5), true);

            register_block_type(
                'attire-blocks/table-of-contents',
                array(
                    'attributes' => array_merge(
                        array(
                            'blockID' => [
                                'type' => 'string',
                                'default' => 'not_set',
                            ],
                            'listStyle' => [
                                'type' => 'string',
                                'default' => 'ol',
                            ],
                            'wideContent' => [
                                'type' => 'boolean',
                                'default' => false,
                            ],
                            'makeCollapsible' => [
                                'type' => 'boolean',
                                'default' => false,
                            ],
                            'collapseIndicator' => [
                                'type' => 'array',
                                'default' => ['fas fa-chevron-right', 'fas fa-chevron-down'],
                            ],
                            'initialCollapse' => [
                                'type' => 'boolean',
                                'default' => false,
                            ],
                            'heading' => [
                                'type' => 'string',
                                'selector' => '.atbs-toc__title',
                                'default' => __('Table Of Contents', 'attire-blocks'),
                            ],
                            'headingTitle' => [
                                'type' => 'string',
                                'default' => __('Table Of Contents', 'attire-blocks'),
                            ],
                            'mappingHeaders' => [
                                'type' => 'array',
                                'default' => $mapping_headers_array,
                            ],
                            'emptyHeadingText' => [
                                'type' => 'string',
                                'default' => __('No header blocks found! Add headers to generate the table of contents', 'attire-blocks'),
                            ],
                            "hasCustomCSS" => [
                                "type" => "boolean",
                                "default" => false
                            ],
                            "customCSS" => [
                                "type" => "string",
                                "default" => ""
                            ],
                        ),
                        Util::getBgAttributes('', array('ColorLeft' => '#3373DC', 'ColorRight' => '#3373DC', 'Alpha' => '5')),
                        Util::getSpacingProps('', array('Padding' => [30, 35, 20, 35], 'Margin' => [0, 0, 0, 0])),
                        Util::getBorderAttributes('', array('BorderColor' => '#F2EFEF', 'BorderRadius' => 10, 'BorderWidth' => 1)),
                        Util::getTypographyProps('title', array('FontSize' => 20, 'FontWeight' => 700, 'LineHeight' => 1.6, 'TextAlign' => 'left', 'TextTransform' => 'none', 'TextColor' => '#1A264A')),
                        Util::getTypographyProps('icon', array('FontSize' => 16, 'FontWeight' => 800, 'TextColor' => '#1A264A', 'LineHeight' => 2,)),
                        Util::getTypographyProps('content', array('FontSize' => 16, 'FontWeight' => 400, 'LineHeight' => 2.1, 'TextAlign' => 'left', 'TextTransform' => 'none', 'TextColor' => '#8996C1'))
                    ),
                    'render_callback' => array($this, 'render_table_of_contents'),
                )
            );
        }

    }

    /**
     *  Prepare if class 'ATBS_Table_Of_Content' exist.
     *  Kicking this off by calling 'get_instance()' method
     */
    ATBS_Table_Of_Content::get_instance();
}
