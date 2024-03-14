<?php

declare(strict_types=1);

namespace CKPL\Pay\Storage\DataConverter;

/**
 * Interface ConvertibleInterface.
 *
 * @package CKPL\Pay\Storage\DataConverter
 */
interface ConvertibleInterface
{
    /**
     * @param mixed $data
     *
     * @return ConvertibleInterface
     */
    public static function restore($data): ConvertibleInterface;

    /**
     * @return mixed
     */
    public function convert();
}
