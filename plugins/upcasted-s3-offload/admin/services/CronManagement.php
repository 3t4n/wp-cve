<?php

/**
 * Class CronManagement
 */
class CronManagement
{

    /**
     * @param string $cronName
     */
    public function deactivate_cron(string $cronName)
    {
        $options = get_option(UPCASTED_S3_OFFLOAD_SETTINGS);
        unset($options[$cronName]);
        update_option(UPCASTED_S3_OFFLOAD_SETTINGS, $options);
        wp_clear_scheduled_hook($cronName);
    }
}