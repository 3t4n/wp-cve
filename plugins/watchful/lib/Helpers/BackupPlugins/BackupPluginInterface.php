<?php


namespace Watchful\Helpers\BackupPlugins;


interface BackupPluginInterface
{
    /** @return \DateTime | false */
    public function get_last_backup_date();

    public function get_backup_list();
}
