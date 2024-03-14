<?php

class ConveyThisCron
{

    public function __construct()
    {

    }

    // Custom array time
    public static function ConveyThisСustomСronSchedule($schedules) {

        $variables = new Variables();
        $clear_cache = ( is_numeric($variables->clear_cache) && $variables->clear_cache > 0 ) ? $variables->clear_cache : 0;

        $schedules = Array(
            'every_ten_seconds' => Array(
                'interval' => 10,
                'display'  => __('Every Ten Seconds', 'text-domain')
            ),
            'every_24_hours' => Array(
                'interval' => 24 * 60 * 60,
                'display'  => __( 'Every 24 Hours' )
            ),
            'custome_time' => Array(
                'interval' => $clear_cache * 60 * 60,
                'display'  => __( 'Custome Time' )
            )
        );
        return $schedules;
    }

    // Start cron method
    public static function ConveyThisActivationCron() {

        if (!wp_next_scheduled('ConveyThisClearCache')) {
            $variables = new Variables();
            $clear_cache = ( is_numeric($variables->clear_cache) && $variables->clear_cache > 0 ) ? $variables->clear_cache : 0;

            // Cron method
            wp_schedule_event(time(), $clear_cache > 0 ? 'custome_time' : 'every_24_hours', 'ConveyThisClearCache');
        }

    }

    public static function ConveyThisDeactivationCron() {
        wp_clear_scheduled_hook('ConveyThisClearCache');
    }

    public static function ClearCache() {

        try {
            $directoryIterator = new RecursiveDirectoryIterator(CONVEYTHIS_CACHE_TRANSLATIONS_PATH, RecursiveDirectoryIterator::SKIP_DOTS);
            $iterator = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::SELF_FIRST);

            $currentTime = time();

            foreach ($iterator as $fileInfo) {
                if ($fileInfo->isFile()) {
                    $fileTime = $fileInfo->getMTime();
                    if (($currentTime - $fileTime) > 24*3600) {
                        unlink($fileInfo->getRealPath());
                    }
                }
            }
        } catch (UnexpectedValueException $e) {

        } catch (Exception $e) {

        }

    }
}

new ConveyThisCron();



