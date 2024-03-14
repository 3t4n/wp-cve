<?php

/**
 * DO NOT EDIT!
 * This file was automatically generated via bin/generate-validator-spec.php.
 */
namespace Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;

use Google\Web_Stories_Dependencies\AmpProject\Format;
use Google\Web_Stories_Dependencies\AmpProject\Html\Tag as Element;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\AttributeList;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Identifiable;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\SpecRule;
use Google\Web_Stories_Dependencies\AmpProject\Validator\Spec\Tag;
/**
 * Tag class VideoTrack.
 *
 * @package ampproject/amp-toolbox.
 *
 * @property-read string $tagName
 * @property-read string $specName
 * @property-read string $mandatoryParent
 * @property-read array<string> $attrLists
 * @property-read array<string> $htmlFormat
 */
final class VideoTrack extends Tag implements Identifiable
{
    /**
     * ID of the tag.
     *
     * @var string
     */
    const ID = 'video > track';
    /**
     * Array of spec rules.
     *
     * @var array
     */
    const SPEC = [SpecRule::TAG_NAME => Element::TRACK, SpecRule::SPEC_NAME => 'video > track', SpecRule::MANDATORY_PARENT => Element::VIDEO, SpecRule::ATTR_LISTS => [AttributeList\TrackAttrsNoSubtitles::ID], SpecRule::HTML_FORMAT => [Format::AMP, Format::AMP4ADS]];
}
