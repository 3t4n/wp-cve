<?php

declare(strict_types=1);

namespace CKPL\Pay\Security\JWT\Part;

/**
 * Interface PartInterface.
 *
 * @package CKPL\Pay\Security\JWT\Part
 */
interface PartInterface
{
    /**
     * @param string $encodedPart
     *
     * @return PartInterface
     */
    public static function fromEncoded(string $encodedPart): PartInterface;

    /**
     * @return array
     */
    public function raw(): array;

    /**
     * @return string
     */
    public function encoded(): string;
}
