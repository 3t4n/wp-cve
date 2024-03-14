<?php
/**
 * TB Helper.
 *
 * @package TB
 */
if (!class_exists('TB_Helper')) {

    /**
     * Class TB_Helper.
     */
    final class TB_Helper {

        /**
         * Member Variable
         *
         * @since 0.0.1
         * @var instance
         */
        private static $instance;

        /**
         * Member Variable
         *
         * @since 0.0.1
         * @var instance
         */
        public static $block_list;

        /**
         * Store Json variable
         *
         * @since 1.8.1
         * @var instance
         */
        public static $icon_json;

        /**
         * Page Blocks Variable
         *
         * @since 1.6.0
         * @var instance
         */
        public static $page_blocks;

        /**
         * Google fonts to enqueue
         *
         * @var array
         */
        public static $gfonts = array();

        /**
         *  Initiator
         *
         * @since 0.0.1
         */
        public static function get_instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            require( TB_DIR . 'src/tb-helper/class-tb-config.php' );
            require( TB_DIR . 'src/tb-helper/class-tb-block-helper.php' );

            self::$block_list = TB_Config::get_block_attributes();

            add_action('wp_head', array($this, 'generate_stylesheet'), 80);
        }

        /**
         * Parse CSS into correct CSS syntax.
         *
         * @param array  $selectors The block selectors.
         * @param string $id The selector ID.
         * @since 0.0.1
         */
        public static function generate_css($selectors, $id) {

            $styling_css = '';

            if (empty($selectors)) {
                return;
            }

            foreach ($selectors as $key => $value) {
                $styling_css .= $id;

                $styling_css .= $key . ' { ';
                $css = '';

                foreach ($value as $j => $val) {
                    $css .= $j . ': ' . $val . ';';
                }

                $styling_css .= $css . ' } ';
            }

            return $styling_css;
        }

        /**
         * Generates CSS recurrsively.
         *
         * @param object $block The block object.
         * @since 0.0.1
         */
        public function get_block_css($block) {

            // @codingStandardsIgnoreStart

            $block = (array) $block;

            $name = $block['blockName'];
            $css = '';

            if (!isset($name)) {
                return;
            }

            if (isset($block['attrs']) && is_array($block['attrs'])) {
                $blockattr = $block['attrs'];
                if (isset($blockattr['block_id'])) {
                    $block_id = $blockattr['block_id'];
                }
            }

            switch ($name) {

                case 'timeline-blocks/tb-timeline-blocks':
                    $css .= TB_Block_Helper::get_post_grid_css($blockattr, $block_id);
                    // TB_Block_Helper::blocks_post_gfont( $blockattr );
                    break;

                default:
                    // Nothing to do here.
                    break;
            }

            echo $css;

            // @codingStandardsIgnoreEnd
        }

        /**
         * Generates stylesheet and appends in head tag.
         *
         * @since 0.0.1
         */
        public function generate_stylesheet() {

            $this_post = array();

            if (is_single() || is_page() || is_404()) {
                global $post;
                $this_post = $post;
                $this->_generate_stylesheet($this_post);
                if (!is_object($post)) {
                    return;
                }
            } elseif (is_archive() || is_home() || is_search()) {
                global $wp_query;

                foreach ($wp_query as $post) {
                    $this->_generate_stylesheet($post);
                }
            }
        }

        /**
         * Generates stylesheet in loop.
         *
         * @param object $this_post Current Post Object.
         * @since 1.7.0
         */
        public function _generate_stylesheet($this_post) {

            if (has_blocks(get_the_ID())) {
                if (isset($this_post->post_content)) {
                    $blocks = $this->parse($this_post->post_content);
                    self::$page_blocks = $blocks;

                    if (!is_array($blocks) || empty($blocks)) {
                        return;
                    }

                    ob_start();
                    ?>
                    <style type="text/css" media="all" id="tb-style-frontend"><?php $this->get_stylesheet($blocks); ?></style>
                    <?php
                    ob_end_flush();
                }
            }
        }

        /**
         * Parse Guten Block.
         *
         * @param string $content the content string.
         * @since 1.1.0
         */
        public function parse($content) {

            global $wp_version;

            return ( version_compare($wp_version, '5', '>=') ) ? parse_blocks($content) : gutenberg_parse_blocks($content);
        }

        /**
         * Generates stylesheet for reusable blocks.
         *
         * @param array $blocks Blocks array.
         * @since 1.1.0
         */
        public function get_stylesheet($blocks) {

            foreach ($blocks as $i => $block) {
                if (is_array($block)) {
                    if ('core/block' == $block['blockName']) {
                        $id = ( isset($block['attrs']['ref']) ) ? $block['attrs']['ref'] : 0;

                        if ($id) {
                            $content = get_post_field('post_content', $id);

                            $reusable_blocks = $this->parse($content);

                            $this->get_stylesheet($reusable_blocks);
                        }
                    } else {
                        // Get CSS for the Block.
                        $this->get_block_css($block);
                    }
                }
            }
        }

        /**
         *  Get - RGBA Color
         *
         *  Get HEX color and return RGBA. Default return RGB color.
         *
         * @param  var   $color      Gets the color value.
         * @param  var   $opacity    Gets the opacity value.
         * @param  array $is_array Gets an array of the value.
         * @since   1.11.0
         */
        static public function hex2rgba($color, $opacity = false, $is_array = false) {

            $default = $color;

            // Return default if no color provided.
            if (empty($color)) {
                return $default;
            }

            // Sanitize $color if "#" is provided.
            if ('#' == $color[0]) {
                $color = substr($color, 1);
            }

            // Check if color has 6 or 3 characters and get values.
            if (strlen($color) == 6) {
                $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
            } elseif (strlen($color) == 3) {
                $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
            } else {
                return $default;
            }

            // Convert hexadec to rgb.
            $rgb = array_map('hexdec', $hex);

            // Check if opacity is set(rgba or rgb).
            if (false !== $opacity && '' !== $opacity) {
                if (abs($opacity) > 1) {
                    $opacity = $opacity / 100;
                }
                $output = 'rgba(' . implode(',', $rgb) . ',' . $opacity . ')';
            } else {
                $output = 'rgb(' . implode(',', $rgb) . ')';
            }

            if ($is_array) {
                return $rgb;
            } else {
                // Return rgb(a) color string.
                return $output;
            }
        }

    }

    /**
     *  Prepare if class 'TB_Helper' exist.
     *  Kicking this off by calling 'get_instance()' method
     */
    TB_Helper::get_instance();
}
