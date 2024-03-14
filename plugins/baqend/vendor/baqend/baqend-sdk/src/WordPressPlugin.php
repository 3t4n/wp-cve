<?php

namespace Baqend\SDK;

use Baqend\SDK\Client\RequestBuilder;
use Baqend\SDK\Exception\ResponseException;
use Baqend\SDK\Resource\AbstractResource;
use Baqend\SDK\Value\Version;

/**
 * Class WordPressPlugin created on 19.09.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK
 */
class WordPressPlugin extends AbstractResource
{

    /**
     * @var Version[]
     */
    private static $versionCache = [];

    /**
     * Checks, if the WordPress plugin has the latest version.
     *
     * @param string|Version $versionToCompare
     * @param int|null $major The major version to check, null by default.
     * @param int|null $minor The minor version to check, null by default.
     * @return int A version comparison result.
     * @throws ResponseException When the latest version cannot be retrieved.
     */
    public function compareVersion($versionToCompare, $major = null, $minor = null) {
        if (!$versionToCompare instanceof Version) {
            $versionToCompare = Version::parse($versionToCompare);
        }

        return $versionToCompare->compare($this->getLatestVersion($major, $minor));
    }

    /**
     * Retrieves the latest version of the WordPress plugin.
     *
     * @param int|null $major The major version to check, null by default.
     * @param int|null $minor The minor version to check, null by default.
     * @return Version The current plugin's version.
     * @throws ResponseException
     */
    public function getLatestVersion($major = null, $minor = null) {
        $track = $this->getTrack($major, $minor);
        if (isset(self::$versionCache[$track])) {
            return self::$versionCache[$track];
        }

        $requestBuilder = (new RequestBuilder($this->serializer))
            ->withHost('www.baqend.com')
            ->withScheme('https')
            ->withBasePath('/wordpress-plugin')
            ->asHead()
            ->withPath('/'.$track.'/baqend.zip');

        $response = $this->execute($requestBuilder->build());
        if ($response->getStatusCode() !== 200) {
            throw new ResponseException($response);
        }

        self::$versionCache[$track] = Version::parse($response->getHeaderLine('x-amz-meta-version'));
        return self::$versionCache[$track];
    }

    /**
     * Returns the track for a $major/$minor version pair.
     *
     * @param int|null $major The major version to check, null by default.
     * @param int|null $minor The minor version to check, null by default.
     * @return string
     */
    private function getTrack($major = null, $minor = null) {
        if ($major === null && $minor === null) {
            return 'latest';
        }

        if ($major !== null && $minor === null) {
            return $major.'.X.X';
        }

        return $major.'.'.$minor.'.X';
    }
}
