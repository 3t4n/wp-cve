<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Response;

use CKPL\Pay\Endpoint\MakeRefundEndpoint;
use CKPL\Pay\Model\ResponseModelInterface;

/**
 * Class CreatedRefundResponseModel.
 *
 * @package CKPL\Pay\Model\Response
 */
class CreatedRefundResponseModel implements ResponseModelInterface
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return CreatedRefundResponseModel
     */
    public function setId(string $id): CreatedRefundResponseModel
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return MakeRefundEndpoint::class;
    }
}
