<?php


namespace WilokeEmailCreator\Templates\Services\PostMeta;

use WilokeEmailCreator\Templates\Services\PostMeta\PostMetaService;

class UpdatePostMetaService extends PostMetaService
{
    public function updatePostMeta(array $aRawData): array
    {
        $this->setIsUpdate(true);
        $this->setRawData($aRawData);

        return $this->performSaveData();
    }
}
