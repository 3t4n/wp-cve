<?php

namespace Modular\SDK\Services;

class BackupService extends AbstractService
{
    /**
     * @param string $backupId
     * @param array $data
     * @param array $opts
     * @return mixed
     * @throws \ErrorException
     */
    public function createUpload(string $backupId, array $data = [], array $opts = [])
    {
        $opts += [
            'auth' => true,
        ];

        return $this->raw(
            'post',
            $this->buildPath('/site/manager/backup/%s/upload', $backupId),
            $data,
            $opts
        );
    }
}
