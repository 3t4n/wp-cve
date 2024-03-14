<?php

namespace Google\Web_Stories_Dependencies\AmpProject\CompatibilityFix;

use Google\Web_Stories_Dependencies\AmpProject\CompatibilityFix;
/**
 * Backwards compatibility fix for classes that were moved.
 *
 * @package ampproject/amp-toolbox
 */
final class MovedClasses implements CompatibilityFix
{
    /**
     * Mapping of aliases to be registered.
     *
     * @var array<string, string> Associative array of class alias mappings.
     */
    const ALIASES = [
        // v0.9.0 - moved HTML-based utility into a separate `Html` sub-namespace.
        'Google\\Web_Stories_Dependencies\\AmpProject\\AtRule' => 'Google\\Web_Stories_Dependencies\\AmpProject\\Html\\AtRule',
        'Google\\Web_Stories_Dependencies\\AmpProject\\Attribute' => 'Google\\Web_Stories_Dependencies\\AmpProject\\Html\\Attribute',
        'Google\\Web_Stories_Dependencies\\AmpProject\\LengthUnit' => 'Google\\Web_Stories_Dependencies\\AmpProject\\Html\\LengthUnit',
        'Google\\Web_Stories_Dependencies\\AmpProject\\RequestDestination' => 'Google\\Web_Stories_Dependencies\\AmpProject\\Html\\RequestDestination',
        'Google\\Web_Stories_Dependencies\\AmpProject\\Role' => 'Google\\Web_Stories_Dependencies\\AmpProject\\Html\\Role',
        'Google\\Web_Stories_Dependencies\\AmpProject\\Tag' => 'Google\\Web_Stories_Dependencies\\AmpProject\\Html\\Tag',
        // v0.9.0 - extracted `Encoding` out of `Dom\Document`, as it is turned into AMP value object.
        'Google\\Web_Stories_Dependencies\\AmpProject\\Dom\\Document\\Encoding' => 'Google\\Web_Stories_Dependencies\\AmpProject\\Encoding',
    ];
    /**
     * Register the compatibility fix.
     *
     * @return void
     */
    public static function register()
    {
        \spl_autoload_register(__CLASS__ . '::autoloader');
    }
    /**
     * Autoloader to register.
     *
     * @param string $oldClassName Old class name that was requested to be autoloaded.
     * @return void
     */
    public static function autoloader($oldClassName)
    {
        if (!\array_key_exists($oldClassName, self::ALIASES)) {
            return;
        }
        \class_alias(self::ALIASES[$oldClassName], $oldClassName, \true);
    }
}
