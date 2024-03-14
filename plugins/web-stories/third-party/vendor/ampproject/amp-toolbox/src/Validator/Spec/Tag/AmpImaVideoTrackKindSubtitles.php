<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */
namespace Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;

use Google\Web_Stories_Dependencies\AmpProject\Extension;
use Google\Web_Stories_Dependencies\AmpProject\Format;
use Google\Web_Stories_Dependencies\AmpProject\Html\Tag as Element;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\AttributeList;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Identifiable;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\SpecRule;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;
/**
 * Tag class AmpImaVideoTrackKindSubtitles.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $tagName
 * @property-read string $specName
 * @property-read string $mandatoryParent
 * @property-read array<array> $attrs
 * @property-read array<string> $attrLists
 * @property-read string $specUrl
 * @property-read array<string> $htmlFormat
 */
final class AmpImaVideoTrackKindSubtitles extends Tag implements Identifiable
{
    /**
     * ID of the tag.
     *
     * @var string
     */
    const ID = 'amp-ima-video > track[kind=subtitles]';
    /**
     * Array of spec rules.
     *
     * @var array
     */
    const SPEC = [SpecRule::TAG_NAME => Element::TRACK, SpecRule::SPEC_NAME => 'amp-ima-video > track[kind=subtitles]', SpecRule::MANDATORY_PARENT => Extension::IMA_VIDEO, SpecRule::ATTRS => ['[label]' => [], '[src]' => [], '[srclang]' => []], SpecRule::ATTR_LISTS => [AttributeList\TrackAttrsSubtitles::ID], SpecRule::SPEC_URL => 'https://amp.dev/documentation/components/amp-ima-video/', SpecRule::HTML_FORMAT => [Format::AMP]];
}
