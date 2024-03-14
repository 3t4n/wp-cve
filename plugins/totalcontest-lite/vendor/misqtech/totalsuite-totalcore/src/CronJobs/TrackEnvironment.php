<?php
/** @noinspection PhpUnused */

namespace TotalContestVendors\TotalCore\CronJobs;

use TotalContestVendors\TotalCore\Application;
use TotalContestVendors\TotalCore\Helpers\Arrays;
use TotalContestVendors\TotalCore\Http\TrackingRequest;
use TotalContestVendors\TotalCore\Scheduler;
use TotalContestVendors\TotalCore\Scheduler\CronJob;


class TrackEnvironment extends CronJob
{
    public function execute()
    {
        global $wpdb;

        // Env
        $url = Application::getInstance()->env('api.tracking.environment');

        $data = array_map(function ($item) {
            return substr(str_replace('.', '', $item), 0, 3);
        },
            [
                'php'       => phpversion(),
                'mysql'     => $wpdb->db_version(),
                'wordpress' => get_bloginfo('version'),
                'product'   => Application::getInstance()->env('version')
            ]);

        $data['locale'] = get_locale();

        // Activity
        $options = get_option(Application::getInstance()->env('tracking-key'));
        $options = Arrays::getDotNotation($options, 'features', []);

        $events = [];

        foreach ($options as $event) {
            $events[] = $event['date'];
        }

        $data['firstUsage'] = Application::getInstance()->firstUsage();

        if (!empty($events)) {
            $data['lastUsage'] = max($events);
        } else {
            $data['lastUsage'] = $data['firstUsage'];
        }

        // Modules
        $extensions = (array)Application::get('modules.repository')
                                        ->getAllInstalled();

        $data['modules'] = [];

        foreach ($extensions as $extension) {
            $data['modules'][] = [
                'id'        => $extension['id'],
                'name'      => $extension['name'],
                'type'      => $extension['type'],
                'activated' => $extension['activated']
            ];
        }

        // Objects
        $data['objects'] = Application::getInstance()
                                      ->getPlugin()
                                      ->objectsCount();

        // Options
        $data['options'] = [
            'structuredData' => Application::get('options')->get('general.structuredData.enabled', false),
            'showCredits'    => Application::get('options')->get('general.showCredits.enabled', false),
            'fullChecks'     => Application::get('options')->get('performance.fullChecks.enabled', false),
            'async'          => Application::get('options')->get('performance.async.enabled', false),
            'uninstallAll'   => Application::get('options')->get('advanced.uninstallAll', false),
            'inlineCss'      => Application::get('options')->get('advanced.inlineCss', false)
        ];

		$data['source'] = Application::getInstance()->env('source');

        TrackingRequest::send($url, $data);
    }

    /**
     * @return string
     */
    public function getRecurrence()
    {
        return Scheduler::SCHEDUL_WEEKLY;
    }

    /**
     * @return int
     */
    public function getStartTime()
    {
        return time();
    }

}
