<?php
namespace BDroppy\CronJob;

use BDroppy\CronJob\Jobs\ChangeCatalogJob;
use BDroppy\CronJob\Jobs\QueueJob;
use BDroppy\CronJob\Jobs\SyncOrderJob;
use BDroppy\CronJob\Jobs\UpdateCatalogJob;
use BDroppy\CronJob\Jobs\UpdateProductJob;
use BDroppy\Init\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

class CronJob {

    private $core ;
    private $loader ;
    private $logger ;
    private $remote ;
    protected $settings;

    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->loader = $core->getLoader();
        $this->logger = $core->getLogger();
        $this->remote = $core->getRemote();

        $this->loader->addAction( 'admin_footer', $this, 'scheduleEvents',99,99 );
        $this->loader->addAction( 'plugins_loaded', $this, 'initialRun',99,99 );

        if ( defined( 'DOING_CRON' ) || isset( $_GET['doing_wp_cron'] ) )
        {
            new ChangeCatalogJob($core);
            new UpdateCatalogJob($core);
            new UpdateProductJob($core);
            new QueueJob($core);
            new SyncOrderJob($core);
        }
    }

    public function initialRun()
    {
        if(isset($_GET['doing_wp_cron'],$_GET['upload-image'],$_POST['bdroppy-product']))
        {
            @ini_set( 'max_execution_time', 500 );
            @wc_set_time_limit( 500 );

            $queue = new QueueJob( $this->core );
            echo  $queue->importMethodUploadImage($_POST['bdroppy-product']);
            die();
        }
    }


    public static function scheduleEvents()
    {
        if ( ! wp_next_scheduled( 'bdroppy_queues_event' ) ) {
            wp_schedule_event( time(), '1min', 'bdroppy_queues_event' );
        }

        if ( ! wp_next_scheduled( 'bdroppy_change_catalog_event' ) ) {
            wp_schedule_event( time(), '1min', 'bdroppy_change_catalog_event' );
        }

        if ( ! wp_next_scheduled( 'bdroppy_update_catalog_event' ) ) {
            wp_schedule_event( time(), '4hour', 'bdroppy_update_catalog_event' );
        }

        if ( ! wp_next_scheduled( 'bdroppy_update_product_event' ) ) {
            wp_schedule_event( time(), '1hour', 'bdroppy_update_product_event' );
        }

        if ( ! wp_next_scheduled( 'bdroppy_sync_order_event' ) ) {
            wp_schedule_event( time(), '15min', 'bdroppy_sync_order_event' );
        }
    }

    public static function unScheduleEvents()
    {
        wp_clear_scheduled_hook( 'bdroppy_queues_event' );
        wp_clear_scheduled_hook( 'bdroppy_update_catalog_event' );
        wp_clear_scheduled_hook( 'bdroppy_change_catalog_event' );
        wp_clear_scheduled_hook( 'bdroppy_sync_order_event' );
    }

    public static function cronSchedules( $schedules )
    {
        if (!isset( $schedules['1min'])) {
            $schedules['1min'] = [
                'interval' => 60,
                'display'  => __( 'Every minute' )
            ];
        }
        if ( !isset( $schedules['3min'] ) ) {
            $schedules['3min'] = [
                'interval' => 3 * 60,
                'display'  => __( 'Every 3 minute' )
            ];
        }
        if ( ! isset( $schedules['5min'] ) ) {
            $schedules['5min'] = [
                'interval' => 5 * 60,
                'display'  => __( 'Every 5 minutes' )
            ];
        }
        if ( ! isset( $schedules['15min'] ) ) {
            $schedules['15min'] = [
                'interval' => 15 * 60,
                'display'  => __( 'Every 15 minutes' )
            ];
        }

        if ( ! isset( $schedules['30min'] ) ) {
            $schedules['30min'] = [
                'interval' => 30 * 60,
                'display'  => __( 'Every 30 minutes' )
            ];
        }

        if ( ! isset( $schedules['1hour'] ) ) {
            $schedules['1hour'] = [
                'interval' => 60 * 60,
                'display'  => __( 'Every hour' )
            ];
        }

        if ( ! isset( $schedules['2hour'] ) ) {
            $schedules['2hour'] = [
                'interval' => 2* (60 * 60),
                'display'  => __( 'Every 2 hours' )
            ];
        }

        if ( ! isset( $schedules['4hour'] ) ) {
            $schedules['4hour'] = [
                'interval' => 4* (60 * 60),
                'display'  => __( 'Every 4 hours' )
            ];
        }
        return $schedules;
    }


}

