<?php

namespace Baqend\SDK\Value;

/**
 * Class Version created on 18.09.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Value
 */
final class Version
{

    /**
     * @var Version[]
     */
    private static $versions = [];

    /**
     * @var int
     */
    private $major;

    /**
     * @var int
     */
    private $minor;

    /**
     * @var int
     */
    private $patch;

    /**
     * @var string|null
     */
    private $stability;

    /**
     * @var string
     */
    private $string;

    /**
     * Creates a version.
     *
     * @param int $major The major version.
     * @param int $minor The minor version.
     * @param int $patch The patch version.
     * @param string|null $stability The version's stability.
     * @param string $string A string representation.
     */
    private function __construct($major, $minor, $patch, $stability, $string) {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
        $this->stability = $stability;
        $this->string = $string;
    }

    /**
     * Creates a version from its components.
     *
     * @param int $major         The major version component.
     * @param int $minor         The minor version component, defaults to zero.
     * @param int $patch         The patch version component, defaults to zero.
     * @param null|string $stability The version's stability component, defaults to null.
     * @return self
     */
    public static function fromValues($major, $minor = 0, $patch = 0, $stability = null) {
        $string = self::stringify($major, $minor, $patch, $stability);
        if (!isset(self::$versions[$string])) {
            self::$versions[$string] = new self($major, $minor, $patch, $stability, $string);
        }

        return self::$versions[$string];
    }

    /**
     * Creates a version from a string.
     *
     * @param string $version The version string to parse.
     * @return self
     */
    public static function parse($version) {
        if (!is_string($version)) {
            throw new \InvalidArgumentException('Cannot parse '.gettype($version));
        }

        if (!preg_match('/^(\d+)\.(\d+)\.(\d+)(?:|-(\w+))$/', $version, $versionArg)) {
            throw new \InvalidArgumentException('Not a version string: '.$version);
        }

        // Version already exists?
        if (isset(self::$versions[$version])) {
            return self::$versions[$version];
        }

        if (count($versionArg) < 5) {
            $versionArg[4] = null;
        }

        list(, $major, $minor, $patch, $stability) = $versionArg;
        return self::fromValues((int) $major, (int) $minor, (int) $patch, $stability);
    }

    /**
     * Stringifies the given version components.
     *
     * @param int $major             The major version.
     * @param int $minor             The minor version.
     * @param int $patch             The patch version.
     * @param string|null $stability The version's stability.
     * @return string
     */
    private static function stringify($major, $minor, $patch, $stability) {
        $stabilityStr = $stability ? '-'.$stability : '';

        return $major.'.'.$minor.'.'.$patch.$stabilityStr;
    }

    /**
     * Returns the version's major component.
     *
     * @return int The major component.
     */
    public function getMajor() {
        return $this->major;
    }

    /**
     * Returns the version's minor component.
     *
     * @return int The minor component.
     */
    public function getMinor() {
        return $this->minor;
    }

    /**
     * Returns the version's patch component.
     *
     * @return int The patch component.
     */
    public function getPatch() {
        return $this->patch;
    }

    /**
     * Returns the version's stability component.
     *
     * @return null|string The stability component or null, if stable.
     */
    public function getStability() {
        return $this->stability;
    }

    /**
     * Determines whether this version is stable.
     *
     * @return bool True, if this version is stable.
     */
    public function isStable() {
        return $this->stability === null;
    }

    /**
     * @param Version $other
     * @return int Negative, if $other is greater, positive, if $this is greater.
     */
    public function compare(Version $other) {
        if (($majorDiff = $this->major - $other->major) !== 0) {
            return $majorDiff;
        }
        if (($minorDiff = $this->minor - $other->minor) !== 0) {
            return $minorDiff;
        }
        if (($patchDiff = $this->patch - $other->patch) !== 0) {
            return $patchDiff;
        }
        if ($this->stability === $other->stability) {
            return 0;
        }
        if ($this->stability === null && $other->stability) {
            return 1;
        }
        if ($other->stability === null && $this->stability) {
            return -1;
        }

        return 0;
    }

    /**
     * Checks, if this version is less than another one.
     *
     * @param Version|string $other The other version to check.
     * @return bool True, if $this is less than $other.
     */
    public function lessThan($other) {
        if (!$other instanceof Version) {
            $other = self::parse($other);
        }

        return $this->compare($other) < 0;
    }

    /**
     * Checks, if this version is less than or equal to another one.
     *
     * @param Version|string $other The other version to check.
     * @return bool True, if $this is less than or equal to $other.
     */
    public function lessThanOrEqualTo($other) {
        if (!$other instanceof Version) {
            $other = self::parse($other);
        }

        return $this->compare($other) <= 0;
    }

    /**
     * Checks, if this version is greater than another one.
     *
     * @param Version|string $other The other version to check.
     * @return bool True, if $this is greater than $other.
     */
    public function greaterThan($other) {
        if (!$other instanceof Version) {
            $other = self::parse($other);
        }

        return $this->compare($other) > 0;
    }

    /**
     * Checks, if this version is greater than or equal to another one.
     *
     * @param Version|string $other The other version to check.
     * @return bool True, if $this is greater than or equal to $other.
     */
    public function greaterThanOrEqualTo($other) {
        if (!$other instanceof Version) {
            $other = self::parse($other);
        }

        return $this->compare($other) >= 0;
    }

    /**
     * Increments the patch and sets stability to dev.
     *
     * @return Version
     */
    public function incrementDev() {
        return self::fromValues($this->major, $this->minor, $this->patch + 1, 'dev');
    }

    /**
     * Writes a version to a composer.json.
     *
     * @param string $filename The composer.json filename.
     */
    public function writeToComposerJson($filename) {
        $composerJson = json_decode(file_get_contents($filename), true);
        $composerJson['version'] = $this->__toString();
        file_put_contents(
            $filename,
            str_replace('    ', '  ', json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))."\n"
        );
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->string;
    }
}
