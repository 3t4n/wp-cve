<?php

declare(strict_types=1);

namespace CKPL\Pay\Model;

/**
 * Interface RequestModelInterface.
 *
 * @package CKPL\Pay\Model
 */
interface RequestModelInterface extends ModelInterface, ProcessedInputInterface
{
    /**
     * @type int
     */
    const FORM = 1;

    /**
     * @type int
     */
    const JSON_ARRAY = 2;

    /**
     * @type int
     */
    const JSON_OBJECT = 3;

    /**
     * @return int
     */
    public function getType(): int;
}
