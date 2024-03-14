<?php

namespace Memsource\Dao;

use Memsource\Dto\ContentSettingsDto;
use Memsource\Utils\DatabaseUtils;

class ContentSettingsDao extends AbstractDao
{
    /** @var string */
    private $contentSettings;

    public function __construct()
    {
        parent::__construct();
        $this->contentSettings = $this->wpdb->prefix . DatabaseUtils::TABLE_CONTENT_SETTINGS;
    }

    /**
     * @return ContentSettingsDto[]
     */
    public function findAllContentSettings(): array
    {
        $sql = "SELECT * FROM $this->contentSettings";
        return $this->findAll($sql, ContentSettingsDto::class);
    }

    public function insertContentSettings(string $hash, string $metaName, string $metaType, bool $exportForTranslation)
    {
        $row = [
            'hash' => $hash,
            'content_id' => $metaName,
            'content_type' => $metaType,
            'send' => $exportForTranslation,
        ];
        $this->insert($this->contentSettings, $row);
    }

    /**
     * @return ContentSettingsDto|null
     */
    public function findOneByHash(string $hash)
    {
        $sql = "SELECT * FROM $this->contentSettings WHERE `hash` = '$hash'";
        $result = $this->findAll($sql, ContentSettingsDto::class);
        return $result[0] ?? null;
    }

    public function updateContentSettings(string $id, string $metaType, bool $exportForTranslation)
    {
        $this->update(
            $this->contentSettings,
            ['id' => $id],
            ['content_type' => $metaType, 'send' => $exportForTranslation]
        );
    }
}
