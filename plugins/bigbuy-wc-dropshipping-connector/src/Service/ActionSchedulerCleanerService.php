<?php
declare(strict_types=1);

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

class ActionSchedulerCleanerService
{
    private const MAX_ACTIONS_TO_DELETE = 1000;

    /** @var LoggerService  */
    private $loggerService;

    public function __construct()
    {
        $this->loggerService = new LoggerService();
    }

    public function clean(): void
    {
        $actionSchedulerStore = \ActionScheduler::store();
        $scheduleActionsToDelete = $actionSchedulerStore->query_actions(
            [
                'status' => \ActionScheduler_Store::STATUS_COMPLETE,
                'per_page' => self::MAX_ACTIONS_TO_DELETE,
                'orderby' => 'date',
                'order' => 'ASC',
            ]
        );

        if (empty($scheduleActionsToDelete)) {
            return;
        }

        foreach ($scheduleActionsToDelete as $action) {
            try {
                $actionSchedulerStore->delete_action($action);
            } catch (\Exception $exception) {
                $this->loggerService->info('Could not delete action: '.$exception->getMessage(), $action);
            }
        }
    }
}