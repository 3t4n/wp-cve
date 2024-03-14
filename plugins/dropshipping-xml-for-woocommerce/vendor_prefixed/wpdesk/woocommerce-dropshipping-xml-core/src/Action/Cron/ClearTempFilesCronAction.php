<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Cron;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DateTimeImmutable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Cleaner\TempFilesCleanerService;
/**
 * Class ClearTempFilesCronAction.
 */
class ClearTempFilesCronAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    const CRON_ACTION = 'dropshipping_clear_files_process';
    /**
     *
     * @var TempFilesCleanerService
     */
    private $cleaner;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Cleaner\TempFilesCleanerService $cleaner)
    {
        $this->cleaner = $cleaner;
    }
    public function hooks()
    {
        \add_filter('cron_schedules', [$this, 'cron_schedules']);
        \add_action('init', [$this, 'register_cron']);
        \add_action(self::CRON_ACTION, [$this->cleaner, 'clean']);
    }
    public function register_cron()
    {
        if (!\wp_next_scheduled(self::CRON_ACTION)) {
            \wp_schedule_event($this->get_timestamp(), 'at_midnight', self::CRON_ACTION);
        }
    }
    public function cron_schedules($schedules) : array
    {
        $schedules['at_midnight'] = ['interval' => 86400, 'display' => \__('At midnight')];
        return $schedules;
    }
    private function get_timestamp() : int
    {
        $local_time = \current_datetime();
        $today_midnight = new \DateTimeImmutable('today midnight', \wp_timezone());
        return $today_midnight->getTimestamp() + $local_time->getOffset();
    }
}
