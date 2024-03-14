<?php

namespace FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes;

use FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\Abstracts\BoxInterface;
use FlexibleWishlistVendor\WPDesk\View\Renderer\Renderer;
use FlexibleWishlistVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use FlexibleWishlistVendor\WPDesk\View\Resolver\ChainResolver;
use FlexibleWishlistVendor\WPDesk\View\Resolver\DirResolver;
/**
 * Renders fields boxes on the submitted data.
 */
class BoxRenderer
{
    /** @var array<string, array{type: string}> */
    private $boxes;
    /** @var Renderer */
    private $renderer;
    /** @var Helpers\BBCodes */
    private $bbcodes;
    /** @var Helpers\Markers */
    private $markers;
    /** @param array<string, array{type: string}> $boxes */
    public function __construct(array $boxes, \FlexibleWishlistVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        $this->boxes = $boxes;
        $this->renderer = $renderer ?? new \FlexibleWishlistVendor\WPDesk\View\Renderer\SimplePhpRenderer(new \FlexibleWishlistVendor\WPDesk\View\Resolver\DirResolver(__DIR__ . '/Views/'));
        $this->bbcodes = new \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\Helpers\BBCodes();
        $this->markers = new \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\Helpers\Markers();
    }
    public function has_boxes() : bool
    {
        return !empty($this->boxes);
    }
    public function has_box(string $box_id) : bool
    {
        return isset($this->boxes[$box_id]);
    }
    /**
     * Get single marketing box.
     */
    public function get_single(string $box_id) : string
    {
        if ($this->has_box($box_id)) {
            $box = $this->get_box_type($this->boxes[$box_id]);
            return $box->render(['bbcodes' => $this->bbcodes, 'markers' => $this->markers]);
        }
        return '';
    }
    /**
     * Get all marketing boxes (displays all boxes in the layout).
     */
    public function get_all() : string
    {
        return $this->renderer->render('all', ['boxes' => $this->boxes, 'renderer' => $this->renderer, 'plugin' => $this, 'bbcodes' => $this->bbcodes, 'markers' => $this->markers]);
    }
    /**
     * @param array{type: string} $box
     */
    public function get_box_type(array $box) : \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\Abstracts\BoxInterface
    {
        switch ($box['type']) {
            case 'slider':
                return new \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\BoxType\SliderBox($box, $this->renderer);
            case 'image':
                return new \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\BoxType\ImageBox($box, $this->renderer);
            case 'video':
                return new \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\BoxType\VideoBox($box, $this->renderer);
            case 'simple':
                return new \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\BoxType\SimpleBox($box, $this->renderer);
            default:
                return new \FlexibleWishlistVendor\WPDesk\Library\Marketing\Boxes\BoxType\UnknownBox($box, $this->renderer);
        }
    }
}
