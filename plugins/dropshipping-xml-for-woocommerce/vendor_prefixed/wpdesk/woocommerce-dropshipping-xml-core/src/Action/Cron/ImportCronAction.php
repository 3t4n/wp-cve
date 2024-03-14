<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Cron;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\ImportProcessAction;
/**
 * Class ImportCronAction, class that handles import process through cron.
 */
class ImportCronAction implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Items\Hookable\Hookable
{
    const CRON_ACTION = 'dropshipping_import_process';
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var ImportProcessAction
     */
    private $import_process;
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Request\Request $request, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Config $config, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\Process\ImportProcessAction $import_process)
    {
        $this->request = $request;
        $this->config = $config;
        $this->import_process = $import_process;
    }
    public function hooks()
    {
        \add_filter('cron_schedules', array($this, 'cron_schedules'));
        \add_action('init', array($this, 'register_cron'));
        \add_action(self::CRON_ACTION, array($this->import_process, 'process'));
    }
    public function register_cron()
    {
        if (!\wp_next_scheduled(self::CRON_ACTION)) {
            \wp_schedule_event(\time(), 'every_minute', self::CRON_ACTION);
        }
    }
    public function cron_schedules($schedules) : array
    {
        $schedules['every_minute'] = array('interval' => 60, 'display' => \__('Every minute'));
        return $schedules;
    }
}
