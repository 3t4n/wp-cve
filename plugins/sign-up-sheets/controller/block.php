<?php
/**
 * Gutenberg Blocks Controller
 */

namespace FDSUS\Controller;

class Block
{
    /** @var string */
    private $dir;

    /**
     * @param string $dir
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
        add_action('init', array(&$this, 'registerBlocks'));
    }

    /**
     * Registers the block
     *
     * @see https://developer.wordpress.org/reference/functions/register_block_type/
     */
    public function registerBlocks()
    {
        register_block_type($this->dir . '/build');
    }
}
