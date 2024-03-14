<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event;

use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Abstract_Ilabs_Plugin;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Abstracts\Abstract_Event;
class Wp_Cron_Every_N_Minutes extends Abstract_Event
{
    /**
     * @var int
     */
    private $interval;
    /**
     * @var string
     */
    private $schedule_id;
    public function __construct(int $interval, string $schedule_id)
    {
        $this->interval = $interval;
        $this->schedule_id = $schedule_id;
    }
    public function create()
    {
        $schedule_id = Abstract_Ilabs_Plugin::$initial_instance->get_plugin_prefix() . '_' . $this->schedule_id;
        $schedule_id_hook_name = $schedule_id . '_hook';
        add_filter('cron_schedules', function ($schedules) use($schedule_id) {
            $schedules[$schedule_id] = ['interval' => $this->interval * 60, 'display' => $schedule_id];
            return $schedules;
        });
        if (!wp_next_scheduled($schedule_id_hook_name)) {
            wp_schedule_event(\time(), $schedule_id, $schedule_id_hook_name);
        }
        add_action($schedule_id_hook_name, function () {
            $this->callback();
        });
    }
}
