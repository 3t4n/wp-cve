<?php
namespace FluentSupport\App\Models\Traits;

use Exception;
use FluentSupport\App\Services\Helper;
use FluentSupport\Framework\Support\Arr;

trait ActivityTrait
{
    // Get All Activities
    public function getActivities ( $data )
    {
        $agentId = intval( Arr::get($data, 'filters.agent_id') );

        $activitiesQuery = static::with([
            'person' => function ($query) {
                $query->select(['first_name', 'person_type', 'last_name', 'id', 'avatar']);
            }
        ])->latest('id');

        $from = sanitize_text_field( Arr::get( $data, 'from', '' ) );
        $to = sanitize_text_field( Arr::get( $data, 'to', '') );

        if ( $from != $to ) {
            $from = $from . ' ' . '00:00:00'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            $to = $to . ' ' . '23:59:59'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        if ( ( !empty($from) && !empty($to) ) && $from == $to ) {
            $activitiesQuery->whereDate('created_at', '=', $from);
        } elseif (!empty($from) && !empty($to)) {
            $activitiesQuery->whereBetween('created_at', [ $from, $to ]);
        }

        if ($agentId) {
            $activitiesQuery->where('person_id', $agentId);
        }

        $activities = $activitiesQuery->paginate();

        if (!$activities) {
            throw new \Exception('No activities found');
        }

        $settings = $this->getSettings();

        return [
            'activities' => $activities,
            'settings'   => $settings['activity_settings']
        ];
    }

    // Update Activity Settings
    public function updateSettings ($settings)
    {
        $defaults = [
            'delete_days'  => 14,
            'disable_logs' => 'no'
        ];
        $settings = wp_parse_args($settings, $defaults);
        $settings['delete_days'] = (int)$settings['delete_days'];

        Helper::updateOption('_activity_settings', $settings);

        return [
            'message' => __('Activity settings has been updated', 'fluent-support')
        ];
    }

    // Get Activity Settings
    public function getSettings()
    {
        $settings = Helper::getOption('_activity_settings', []);

        $defaults = [
            'delete_days'  => 14,
            'disable_logs' => 'no'
        ];

        $settings = wp_parse_args($settings, $defaults);

        if (! $settings ) throw new \Exception('No activity settings found');

        return [
            'activity_settings' => $settings
        ];

    }


}
