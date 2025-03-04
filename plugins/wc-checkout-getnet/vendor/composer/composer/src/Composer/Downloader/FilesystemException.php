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

namespace CoffeeCode\Composer\Downloader;

/**
 * Exception thrown when issues exist on local filesystem
 *
 * @author Javier Spagnoletti <jspagnoletti@javierspagnoletti.com.ar>
 */
class FilesystemException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, ?\Exception $previous = null)
    {
        parent::__construct("Filesystem exception: \n".$message, $code, $previous);
    }
}
