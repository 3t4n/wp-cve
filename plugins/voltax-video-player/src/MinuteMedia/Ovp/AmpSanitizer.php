<?php

namespace MinuteMedia\Ovp;

/**
 * Class AmpSanitizer
 * @package MinuteMedia\Ovp
 */
class AmpSanitizer extends \AMP_Base_Sanitizer
{
    /**
     * Turns images with the class `mm-video-embed` into `amp-minute-media-player` elements
     */
    public function sanitize()
    {
        $nodes = $this->dom->getElementsByTagName('img');
        $numNodes = $nodes->length;
        if (0 === $numNodes) {
            return;
        }

        for ($i = $numNodes - 1; $i >= 0; $i--) {
            /**
             * Image element.
             *
             * @var \DOMElement $node
             */
            $node = $nodes->item($i);

            $classes = $node->getAttribute('class');
            if (strpos($classes, 'mm-video-embed') === false) {
                continue;
            }

            $playerId = $node->getAttribute('data-player-id');
            $contentId = $node->getAttribute('data-content-id');
            $dataContentId = $contentId . '#' . $playerId;

            if ($node->hasAttribute('data-extra-content-id')) {
                $extraContentId = $node->getAttribute('data-extra-content-id');
                if ($extraContentId) {
                    $dataContentId = $dataContentId . '#' . $extraContentId;
                }
            }

            if (empty($playerId) || empty($contentId)) {
                continue;
            }

            $attributes = [
                'data-content-type' => 'specific',
                'data-content-id' => $dataContentId,
                'layout' => 'responsive',
                'width' => '640',
                'height' => '360',
                'autoplay' => '',
            ];

            $attributes = \apply_filters('mm_video_amp_attributes', $attributes);

            $newNode = \AMP_DOM_Utils::create_node($this->dom, 'amp-minute-media-player', $attributes);
            $node->parentNode->replaceChild($newNode, $node);
        }
    }
}
