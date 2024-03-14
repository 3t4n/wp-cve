<?php
declare(strict_types=1);

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

abstract class GJMAA_Cron_Abstract
{
    public function execute() : void
    {
        if($this->isLockedCronJob()) {
            return;
        }

        $this->lockCronJob();

        $this->runJob();

        $this->unlockCronJob();
    }

    abstract public function getCode() : string;

    abstract public function runJob() : void;

    public function isLockedCronJob() : bool
    {
        $lock = get_option($this->getLockName(), false);
        if(!$lock) {
            return false;
        }

        $time = time();
        if($lock < $time) {
            $this->unlockCronJob();
            return false;
        }

        error_log(sprintf('[My auctions allegro] Cron job: %s is locked', $this->getCode()));

        return true;
    }

    public function lockCronJob() : void
    {
        error_log(sprintf('[My auctions allegro] Cron job: %s lock', $this->getCode()));

        add_option($this->getLockName(), time() + (10 * 60));
    }

    public function unlockCronJob() : void
    {
        error_log(sprintf('[My auctions allegro] Cron job: %s unlock', $this->getCode()));

        delete_option($this->getLockName());
    }

    public function getLockName() : string
    {
        return sprintf('%s_lock', $this->getCode());
    }

    public function convert_filesize($bytes, $decimals = 2)
    {
        $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen((string) $bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[ $factor ];
    }
}