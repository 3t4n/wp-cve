<?php

/*
 * exported from @aguidrevitch/fpo-inpage-events
 * run "npm run build" to regenerate
 */

namespace WP_Meteor\Blocker;

class Event {
    public function initialize() {}
    public const EVENT_CSS_LOADED = "fpo:css-loaded";
    public const EVENT_ELEMENT_LOADED = "fpo:element-loaded";
    public const EVENT_FIRST_INTERACTION = "fpo:first-interaction";
    public const EVENT_IMAGES_LOADED = "fpo:images-loaded";
    public const EVENT_LAZY_ELEMENT = "fpo:lazy-element";
    public const EVENT_LCP_BOTH = "fpo:lcp-both";
    public const EVENT_LCP_ELEMENT = "fpo:lcp-element";
    public const EVENT_LCP_ELEMENT_CANDIDATE = "fpo:lcp-element-candidate";
    public const EVENT_LCP_LOADED = "fpo:lcp-loaded";
    public const EVENT_LCP_LOADED_CANDIDATE = "fpo:lcp-loaded-candidate";
    public const EVENT_REPLAY_CAPTURED_EVENTS = "fpo:replay-captured-events";
    public const EVENT_SCROLL_TO_REVEAL_ANIMATIONS = "fpo:scroll-to-reveal-animations";
    public const EVENT_STYLES_TAG_AVAILABLE = "fpo:styles-tag-available";
    public const EVENT_THE_END = "fpo:the-end";
};
