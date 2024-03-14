<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

interface ImportProcessInterface
{
    /**
     * @param int $id
     * @param int $fileId
     * @return bool
     */
    public function setSuccess(int $id, int $fileId): bool;

    /**
     * @param int $id
     * @param int $fileId
     * @return bool
     */
    public function setFailure(int $id, int $fileId): bool;
}