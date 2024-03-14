<?php

namespace FluentSupport\App\Http\Controllers;

use FluentSupport\App\Models\Activity;
use FluentSupport\Framework\Request\Request;

/**
 *  ActivityLoggerController class for REST API
 * This class is responsible for getting data for all request related to activity and activity settings
 * @package FluentSupport\App\Http\Controllers
 *
 * @version 1.0.0
 */
class ActivityLoggerController extends Controller
{
    /**
     * getActivities method will get information regarding all activity with users(agent/customer) and activity settings
     * @return \WP_REST_Response | array
     */

    public function getActivities (Request $request, Activity $activity)
    {
        try {
            return $activity->getActivities( [
                'page' => $request->getSafe('page', 'intval', 1),
                'per_page' => $request->getSafe('per_page', 'intval', 10),
                'from' => $request->getSafe('from', 'sanitize_text_field', ''),
                'to'   => $request->getSafe('to', 'sanitize_text_field', ''),
                'filters' => $request->getSafe('filters', null, []),
            ] );
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * updateSettings method will update existing activity settings
     * @return \WP_REST_Response | array
     */
    public function updateSettings (Request $request, Activity $activity)
    {
        try {
            return $activity->updateSettings($request->getSafe('activity_settings', 'sanitize_text_field', []));
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * getSettings method will get the list of activity settings and return
     * @return \WP_REST_Response | array
     */
    public function getSettings(Activity $activity)
    {
        try {
            return $activity->getSettings();
        } catch (\Exception $e) {
            return $this->sendError([
                'message' => $e->getMessage()
            ]);
        }
    }
}
