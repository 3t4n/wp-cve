<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
/**
 * Match by `TagAttributeFinder`.
 * @internal
 */
class TagAttributeMatcher extends AbstractMatcher
{
    /**
     * See `AbstractMatcher`.
     *
     * @param TagAttributeMatch $match
     */
    public function match($match)
    {
        $result = $this->createResult($match);
        if (!$result->isBlocked()) {
            return $result;
        }
        $linkAttribute = $match->getLinkAttribute();
        $link = $match->getLink();
        $this->applyCommonAttributes($result, $match, $linkAttribute, $link);
        return $result;
    }
    /**
     * See `AbstractMatcher`.
     *
     * @param TagAttributeMatch $match
     */
    public function createResult($match)
    {
        $result = $this->createPlainResultFromMatch($match);
        $isDataUrlScript = $match->getTag() === 'script' && $match->isAttributeDataUrl($match->getLinkAttribute()) !== \false;
        $this->iterateBlockablesInString(
            $result,
            $match->getLink(),
            // Consider `script[src]` data URL as inline script
            $isDataUrlScript,
            $isDataUrlScript
        );
        $this->probablyDisableDueToSkipped($result, $match);
        return $this->applyCheckResultHooks($result, $match);
    }
}
