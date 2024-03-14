<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare(strict_types=1);

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CoffeeCode\Composer\Package\Loader;

use CoffeeCode\Composer\Json\JsonFile;
use CoffeeCode\Composer\Package\BasePackage;
use CoffeeCode\Composer\Package\CompletePackage;
use CoffeeCode\Composer\Package\CompleteAliasPackage;
use CoffeeCode\Composer\Package\RootPackage;
use CoffeeCode\Composer\Package\RootAliasPackage;

/**
 * @author Konstantin Kudryashiv <ever.zet@gmail.com>
 */
class JsonLoader
{
    /** @var LoaderInterface */
    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param  string|JsonFile                      $json A filename, json string or JsonFile instance to load the package from
     * @return CompletePackage|CompleteAliasPackage|RootPackage|RootAliasPackage
     */
    public function load($json): BasePackage
    {
        if ($json instanceof JsonFile) {
            $config = $json->read();
        } elseif (file_exists($json)) {
            $config = JsonFile::parseJson(file_get_contents($json), $json);
        } elseif (is_string($json)) {
            $config = JsonFile::parseJson($json);
        } else {
            throw new \InvalidArgumentException(sprintf(
                "JsonLoader: Unknown \$json parameter %s. Please report at https://github.com/composer/composer/issues/new.",
                gettype($json)
            ));
        }

        return $this->loader->load($config);
    }
}
