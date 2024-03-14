<?php

namespace Google\Web_Stories_Dependencies\AmpProject\Dom\Document\Filter;

use Google\Web_Stories_Dependencies\AmpProject\Html\Attribute;
use Google\Web_Stories_Dependencies\AmpProject\Dom\Document;
use Google\Web_Stories_Dependencies\AmpProject\Dom\Document\AfterLoadFilter;
use Google\Web_Stories_Dependencies\AmpProject\Html\Tag;
/**
 * Filter to convert a possible head[profile] attribute to link[rel=profile].
 *
 * @package ampproject/amp-toolbox
 */
final class ConvertHeadProfileToLink implements AfterLoadFilter
{
    /**
     * Converts a possible head[profile] attribute to link[rel=profile].
     *
     * The head[profile] attribute is only valid in HTML4, not HTML5.
     * So if it exists and isn't empty, add it to the <head> as a link[rel=profile] and strip the attribute.
     *
     * @param Document $document Document to be processed.
     */
    public function afterLoad(Document $document)
    {
        if (!$document->head->hasAttribute(Attribute::PROFILE)) {
            return;
        }
        $profile = $document->head->getAttribute(Attribute::PROFILE);
        if ($profile) {
            $link = $document->createElement(Tag::LINK);
            $link->setAttribute(Attribute::REL, Attribute::PROFILE);
            $link->setAttribute(Attribute::HREF, $profile);
            $document->head->appendChild($link);
        }
        $document->head->removeAttribute(Attribute::PROFILE);
    }
}
