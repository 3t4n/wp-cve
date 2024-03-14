<?php


namespace Attire\Blocks;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class ATBSCustomCss.
 */
class ATBSCustomCss
{

    /**
     * The main instance var.
     *
     * @var ATBSCustomCss
     */
    public static $instance = null;


    protected $slug = 'atbs-ccss';

    /**
     * Initialize the class
     */
    public function init()
    {
        add_action('wp_head', array($this, 'render_server_side_css'));
    }


    public function parse_blocks($content)
    {
        if (!function_exists('parse_blocks')) {
            return gutenberg_parse_blocks($content);
        } else {
            return parse_blocks($content);
        }
    }

    public function render_server_side_css()
    {
        if (function_exists('has_blocks') && has_blocks(get_the_ID())) {
            global $post;

            if (!is_object($post)) {
                return;
            }

            $blocks = $this->parse_blocks($post->post_content);

            if (!is_array($blocks) || empty($blocks)) {
                return;
            }

            $style = "\n" . '<style type="text/css" media="all" id="atbs-ccss">' . "\n";
            $style .= $this->get_block_custom_css($blocks);
            $style .= "\n" . '</style>' . "\n";

            echo $style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    public function get_block_custom_css($inner_blocks)
    {
        $style = '';
        foreach ($inner_blocks as $block) {
            if (isset($block['attrs'])) {
                if (isset($block['attrs']['hasCustomCSS']) && isset($block['attrs']['customCSS'])) {
                    $style .= $block['attrs']['customCSS'];
                }
            }

            if ('core/block' === $block['blockName'] && !empty($block['attrs']['ref'])) {
                $reusable_block = get_post($block['attrs']['ref']);

                if (!$reusable_block || 'wp_block' !== $reusable_block->post_type) {
                    return;
                }

                if ('publish' !== $reusable_block->post_status || !empty($reusable_block->post_password)) {
                    return;
                }

                $blocks = $this->parse_blocks($reusable_block->post_content);

                $style .= $this->get_block_custom_css($blocks);
            }

            if (isset($block['innerBlocks']) && !empty($block['innerBlocks']) && is_array($block['innerBlocks'])) {
                $style .= $this->get_block_custom_css($block['innerBlocks']);
            }
        }
        return $style;
    }


    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }

        return self::$instance;
    }


    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, esc_html__('Cheatin\' huh?', 'attire-blocks'), '1.0.0');
    }

    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, esc_html__('Cheatin\' huh?', 'attire-blocks'), '1.0.0');
    }
}
